import pandas as pd
import pymysql
import pdfplumber
import os
import re
import gensim
from gensim import corpora, matutils
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

# APA reference formatting function
def format_apa_reference(author, year, title, source, additional_info=""):
    return f"{author} ({year}). {title}. {source}. {additional_info}"

# Multiple patterns for different APA 7th edition reference formats
reference_patterns = [
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. [A-Z]?\.?)?(?:, & [A-Z][a-zA-Z]+, [A-Z]\. [A-Z]?\.?)*) \((\d{4}|n\.d\.)\)\. (.+?)\. ([A-Za-z0-9\s,:\(\)\-]+?)\.(?:\s*(https?://[^\s]+)?)", re.MULTILINE | re.DOTALL),
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. [A-Z]?\.?)+) \((\d{4})\)\. (.+?)\. (In [A-Z][a-zA-Z]+, Title of Proceedings.*)\. (.+?)\.(?:\s*(https?://[^\s]+)?)", re.MULTILINE | re.DOTALL),
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. [A-Z]?\.?)+) \((\d{4}, [A-Za-z]+ [0-9]{1,2})\)\. (.+?)\. ([A-Za-z\s]+)\.(?:\s*(https?://[^\s]+)?)", re.MULTILINE | re.DOTALL),
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. [A-Z]?\.?)+) \((n\.d\.)\)\. (.+?)\. ([A-Za-z\s]+)\.(?:\s*(https?://[^\s]+)?)", re.MULTILINE | re.DOTALL)
]

# Function to extract APA-style references from the text using multiple patterns
def extract_references(text):
    text = text.replace('\n', ' ')  # Remove line breaks within paragraphs
    formatted_references = []
    for pattern in reference_patterns:
        matches = pattern.findall(text)
        for match in matches:
            author, year, title, source, url = match if len(match) == 5 else (match + ("",))  # Handle cases without URLs
            additional_info = url if url else ""
            formatted_reference = format_apa_reference(author, year, title, source, additional_info)
            formatted_references.append(formatted_reference)
    return formatted_references

# Connect to MySQL database
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

# Directory where the PDFs are stored
pdf_base_directory = "uploads/pdf/"

# Create citation links and collect references per document
citation_links = []
documents = []
document_ids = []
all_references_per_study = {}

for idx, row in df.iterrows():
    cleaned_document_path = clean_document_path(row.get('document_path'))
    pdf_filename = os.path.basename(cleaned_document_path)
    pdf_path = os.path.join(pdf_base_directory, pdf_filename)
    
    combined_text = row.get('abstract', "")

    if os.path.exists(pdf_path):
        print(f"Processing PDF: {pdf_path}")
        pdf_text = extract_text_from_pdf(pdf_path)
        combined_text += " " + pdf_text  # Combine abstract and PDF text
    
    # Debug: print extracted text
    print(f"Extracted text for Study ID {row['id']}:\n{combined_text}\n")

    # Preprocess and store combined text for LDA
    processed_text = preprocess_text(combined_text)
    documents.append(processed_text)
    document_ids.append(row['id'])

    # Extract and store formatted references
    references = extract_references(combined_text)
    all_references_per_study[row['id']] = references

# Print all extracted references per study
print("\nAll Extracted References per Study:")
for doc_id, references in all_references_per_study.items():
    print(f"\nStudy ID {doc_id}:")
    for ref in references:
        print(f" - {ref}")

# LDA Topic Modeling
# Create a dictionary representation of the documents
dictionary = corpora.Dictionary(documents)

# Create a corpus: Term Document Frequency
corpus = [dictionary.doc2bow(doc) for doc in documents]

# Build the LDA model
lda_model = gensim.models.LdaModel(corpus, num_topics=5, id2word=dictionary, passes=15)

# Print the topics discovered by LDA, filtering out keywords that are numbers
topics = lda_model.print_topics(num_words=10)
filtered_topics = []

print("\nFiltered LDA Topics:")
for topic in topics:
    topic_id, words = topic
    # Filter out words that contain only numbers
    filtered_words = ", ".join([word for word in re.findall(r'\"(\w+)\"', words) if not re.match(r'^\d+$', word)])
    print(f"Topic {topic_id}: {filtered_words}")
    filtered_topics.append((topic_id, filtered_words))

# Store LDA topics for each document in the database
cursor = conn.cursor()
for idx, doc_id in enumerate(document_ids):
    doc_topics = lda_model.get_document_topics(corpus[idx], minimum_probability=0.1)
    for topic_id, prob in doc_topics:
        # Filtered keywords for current topic
        topic_keywords = filtered_topics[topic_id][1]
        cursor.execute(
            "INSERT INTO lda_topics (paper_id, topic_id, topic_name, topic_keywords) VALUES (%s, %s, %s, %s) ON DUPLICATE KEY UPDATE topic_keywords=%s",
            (doc_id, topic_id, f"Topic {topic_id + 1}", topic_keywords, topic_keywords)
        )

# Generate topic-based connections between research papers
topic_based_links = []
for i in range(len(documents)):
    for j in range(i + 1, len(documents)):
        similarity_score = matutils.cossim(lda_model[dictionary.doc2bow(documents[i])], lda_model[dictionary.doc2bow(documents[j])])
        if similarity_score > 0.5:  # Adjust the threshold as needed
            topic_based_links.append((df.iloc[i]['id'], df.iloc[j]['id']))
            print(f"Similar Topic Found: Paper {df.iloc[i]['id']} <-> Paper {df.iloc[j]['id']} (Similarity: {similarity_score})")

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
