import pandas as pd
import pymysql
import pdfplumber
import os
import re
import gensim
from gensim import corpora
from nltk.corpus import stopwords
import nltk

nltk.download('stopwords')

# Function to clean document path and remove query parameters
def clean_document_path(document_path):
    return document_path.split("?")[0]

# Function to preprocess text for LDA
def preprocess_text(text):
    stop_words = set(stopwords.words('english'))
    text = re.sub(r'\W+', ' ', text)  # Remove punctuation
    tokens = [word.lower() for word in text.split() if word.lower() not in stop_words]
    return tokens

# Function to extract text from the entire PDF using pdfplumber
def extract_text_from_pdf(pdf_path):
    try:
        with pdfplumber.open(pdf_path) as pdf:
            text = ""
            for page in pdf.pages:
                page_text = page.extract_text() if page.extract_text() else ""
                text += page_text
        return text
    except Exception as e:
        print(f"Error reading PDF file {pdf_path}: {e}")
        return ""

# Function to extract APA-style references from the text
def extract_references(text):
    reference_pattern = re.compile(r"([A-Z][a-zA-Z]+, [A-Z]\. [A-Z]?\.? \(\d{4}\).+)", re.MULTILINE)
    return reference_pattern.findall(text)

# Function to compare topic distributions between two documents
def compare_topic_similarity(doc1, doc2):
    vec1 = lda_model[dictionary.doc2bow(doc1)]
    vec2 = lda_model[dictionary.doc2bow(doc2)]
    
    # Convert LDA vectors to dense format for cosine similarity
    dense_vec1 = gensim.matutils.corpus2dense([vec1], num_terms=lda_model.num_topics).T[0]
    dense_vec2 = gensim.matutils.corpus2dense([vec2], num_terms=lda_model.num_topics).T[0]
    
    # Calculate cosine similarity
    similarity = gensim.matutils.cossim(vec1, vec2)
    return similarity

# Connect to MySQL database using pymysql
conn = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="otas_db"
)

# Fetch titles, abstracts, and corresponding document paths from the database
query = "SELECT id, title, abstract, document_path FROM archive_list WHERE status = 1"
df = pd.read_sql(query, conn)

# Normalize titles for better matching
titles_dict = {re.sub(r"[^\w\s]", "", row['title'].lower()).strip(): row['id'] for index, row in df.iterrows()}

# Print titles to verify
print("Titles in the Database (Normalized):")
for title in titles_dict.keys():
    print(title)

# Directory where the PDFs are stored
pdf_base_directory = "uploads/pdf/"

# Create citation links based on references found in PDFs
citation_links = []
documents = []  # Store preprocessed texts for LDA
document_ids = []  # Store paper IDs for LDA

for idx, row in df.iterrows():
    # Clean the document path
    cleaned_document_path = clean_document_path(row.get('document_path'))
    pdf_filename = os.path.basename(cleaned_document_path)
    
    # Construct the full path to the PDF
    pdf_path = os.path.join(pdf_base_directory, pdf_filename)
    
    combined_text = row.get('abstract', "")  # Start with the abstract

    if os.path.exists(pdf_path):
        print(f"Processing PDF: {pdf_path}")
        pdf_text = extract_text_from_pdf(pdf_path)
        combined_text += " " + pdf_text  # Combine abstract and PDF text
    
    # Preprocess and store combined text for LDA
    processed_text = preprocess_text(combined_text)
    documents.append(processed_text)
    document_ids.append(row['id'])

    # Extract and print references
    references = extract_references(combined_text)
    print(f"\nExtracted references from {pdf_filename}: {references}")
    
    # Match references to existing titles in the database to establish connections
    for ref in references:
        ref_normalized = re.sub(r"[^\w\s]", "", ref.lower()).strip()
        matched = False
        for title in titles_dict:
            print(f"Comparing Reference: '{ref_normalized}' with Title: '{title}'")
            if title in ref_normalized:
                citing_paper_id = row['id']
                cited_paper_id = titles_dict[title]
                if citing_paper_id != cited_paper_id:
                    citation_links.append((citing_paper_id, cited_paper_id))
                    print(f"Matched Reference: {title} (Cited by ID: {citing_paper_id})")
                    matched = True
        if not matched:
            print(f"No match found for extracted reference: {ref}")

# LDA Topic Modeling
# Create a dictionary representation of the documents
dictionary = corpora.Dictionary(documents)

# Create a corpus: Term Document Frequency
corpus = [dictionary.doc2bow(doc) for doc in documents]

# Build the LDA model
lda_model = gensim.models.LdaModel(corpus, num_topics=5, id2word=dictionary, passes=15)

# Print the topics discovered by LDA
topics = lda_model.print_topics(num_words=4)
print("\nLDA Topics:")
for topic in topics:
    print(topic)

# Store LDA topics for each document in the database
cursor = conn.cursor()
for idx, doc_id in enumerate(document_ids):
    doc_topics = lda_model.get_document_topics(corpus[idx], minimum_probability=0.1)
    for topic_id, prob in doc_topics:
        topic_keywords = ", ".join([word for word, _ in lda_model.show_topic(topic_id, topn=4)])
        cursor.execute(
            "INSERT INTO lda_topics (paper_id, topic_id, topic_name, topic_keywords) VALUES (%s, %s, %s, %s) ON DUPLICATE KEY UPDATE topic_keywords=%s",
            (doc_id, topic_id, f"Topic {topic_id + 1}", topic_keywords, topic_keywords)
        )

# Generate topic-based connections between research papers
topic_based_links = []
for i in range(len(documents)):
    for j in range(i + 1, len(documents)):
        similarity_score = compare_topic_similarity(documents[i], documents[j])
        if similarity_score > 0.5:  # Adjust the threshold as needed
            topic_based_links.append((df.iloc[i]['id'], df.iloc[j]['id']))
            print(f"Similar Topic Found: Paper {df.iloc[i]['id']} <-> Paper {df.iloc[j]['id']} (Similarity: {similarity_score})")

# Store the citation links into the database
for citing_paper_id, cited_paper_id in citation_links:
    cursor.execute(
        "INSERT INTO citation_relationships (citing_paper_id, cited_paper_id) VALUES (%s, %s) ON DUPLICATE KEY UPDATE citing_paper_id=citing_paper_id",
        (citing_paper_id, cited_paper_id)
    )

# Store the topic-based connections into the database
for citing_paper_id, cited_paper_id in topic_based_links:
    cursor.execute(
        "INSERT INTO topic_relationships (citing_paper_id, cited_paper_id) VALUES (%s, %s) ON DUPLICATE KEY UPDATE citing_paper_id=citing_paper_id",
        (citing_paper_id, cited_paper_id)
    )

conn.commit()
cursor.close()
conn.close()

# Optional: Print citation and topic-based links
print("Citation Links Created:")
for link in citation_links:
    print(link)

print("Topic-Based Links Created:")
for link in topic_based_links:
    print(link)
