import pandas as pd
import pymysql
import pdfplumber
import os
import re
import gensim
from gensim import corpora, matutils
from nltk.corpus import stopwords
import nltk
from fuzzywuzzy import fuzz
from fuzzywuzzy import process

nltk.download('stopwords')

def clean_document_path(document_path):
    return document_path.split("?")[0]

def normalize_text(text):
    text = text.replace("\n", " ")
    text = re.sub(r"\s{2,}", " ", text)
    text = re.sub(r"-\s", "", text)
    text = re.sub(r"(\d)\s+–\s+(\d)", r"\1–\2", text)
    return text.strip()

def preprocess_text(text):
    stop_words = set(stopwords.words('english')).union({'https', 'www', 'com', 'doi', 'org', 'journal', 'vol', 'pp', 'ed', 'isbn'})
    text = re.sub(r"http\S+", "", text)
    text = re.sub(r"\d+", "", text)
    text = re.sub(r'\W+', ' ', text)
    tokens = [word.lower() for word in text.split() if word.lower() not in stop_words and len(word) > 2]
    return tokens

def extract_text_from_pdf(pdf_path):
    try:
        full_text = []
        with pdfplumber.open(pdf_path) as pdf:
            for i, page in enumerate(pdf.pages):
                page_text = page.extract_text() or ""
                if not page_text.strip():
                    print(f"Warning: No text extracted from page {i + 1} in {pdf_path}")
                full_text.append(page_text)
        return normalize_text(" ".join(full_text))
    except Exception as e:
        print(f"Error reading PDF file {pdf_path}: {e}")
        return ""

def extract_references_section(text):
    references_start = re.search(r"\bReferences\b", text, re.IGNORECASE)
    if references_start:
        return text[references_start.start():]
    return text

def format_apa_reference(author, year, title, source, additional_info=""):
    return f"{author} ({year}). {title}. {source}. {additional_info}"

# Regex patterns for APA references
reference_patterns = [
    # General pattern for references with authors, year, and uppercase title
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?)+(?:, & [A-Z][a-zA-Z]+, [A-Z]\. ?)?) \((\d{4})\)\. ([A-Z\s\(\)]+)\."),
    
    # Fallback pattern for simpler references
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?)+(?:, & [A-Z][a-zA-Z]+, [A-Z]\. ?)?) \((\d{4})\)\. (.+?)"),
    
    # Multiple authors, journal article with DOI
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?(?:[A-Z]\. ?)?)+,? & [A-Z][a-zA-Z]+, [A-Z]\. ?(?:[A-Z]\. ?)?) \((\d{4})\)\. (.+?)\. ([A-Za-z\s,&\']+), (\d+\(?\d+\)?)?, (\d+–\d+)\. (https?://[^\s]+|doi:[^\s]+)"),
    
    # Single author or organization, book with ISBN
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. ?)+|[A-Z][a-zA-Z]+) \((\d{4})\)\. (.+?)\. (.+?), (\d+(?:th|nd|rd|st)? ed\.|ISBN(?:-\d+)+)\. (https?://[^\s]+|doi:[^\s]+)"),
    
    # Journal articles without DOI
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?(?:[A-Z]\. ?)?)+,? & [A-Z][a-zA-Z]+, [A-Z]\. ?(?:[A-Z]\. ?)?) \((\d{4})\)\. (.+?)\. ([A-Za-z\s,&\']+), (\d+), (\d+–\d+)\."),
    
    # Conference papers with editors and page numbers
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?)+) \((\d{4})\)\. (.+?)\. (In .+?,) (pp\. \d+–\d+)\. (https?://[^\s]+)"),
    
    # Blog articles or posts
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. ?)+) \((\d{4}, [A-Za-z]+ \d{1,2})\)\. (.+?)\. (.+?)\. (https?://[^\s]+)"),
    
    # Cases with "n.d."
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. ?)+) \((n\.d\.)\)\. (.+?)\. ([A-Za-z\s,&\']+)\. (https?://[^\s]+)"),
    
    # Catch-all for references ending with URLs or DOIs
    re.compile(r"((?:[A-Z][a-zA-Z]+, [A-Z]\. ?)+) \((\d{4})\)\. (.+?)\. (.+?)\. (https?://[^\s]+|doi:[^\s]+)"),
    
    # Custom pattern to handle the reference you mentioned
    re.compile(r"([A-Z][a-zA-Z]+(?:, [A-Z]\. ?)+(?:, & [A-Z][a-zA-Z]+, [A-Z]\. ?)*) \((\d{4})\)\. ([A-Za-z\s\(\),]+(?:[A-Z\s]+)+)\. ?([A-Za-z\s]*)\.?\s*")
]

def extract_references(text):
    text = normalize_text(text)
    formatted_references = []
    for pattern in reference_patterns:
        matches = pattern.findall(text)
        for match in matches:
            if len(match) >= 4:
                author, year, title, source = match[:4]
                additional_info = " ".join(match[4:])
                formatted_reference = format_apa_reference(author, year, title, source, additional_info)
                formatted_references.append(formatted_reference)
    return formatted_references

def normalize_string(s):
    return re.sub(r'[^\w\s]', '', s.lower()).strip()

def match_references_to_studies(references, titles_dict):
    matches = []
    for ref in references:
        normalized_ref_title = normalize_string(ref)
        best_match = process.extractOne(normalized_ref_title, titles_dict.keys(), scorer=fuzz.token_sort_ratio)
        if best_match and best_match[1] > 85:
            matched_study_id = titles_dict[best_match[0]]
            matches.append((ref, matched_study_id))
    return matches

conn = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="otas_db"
)

query = "SELECT id, title, abstract, document_path FROM archive_list WHERE status = 1"
df = pd.read_sql(query, conn)
titles_dict = {normalize_string(row['title']): row['id'] for index, row in df.iterrows()}

pdf_base_directory = "uploads/pdf/"
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
        pdf_text = extract_references_section(pdf_text)
        combined_text += " " + pdf_text

    processed_text = preprocess_text(combined_text)
    documents.append(processed_text)
    document_ids.append(row['id'])

    references = extract_references(combined_text)
    all_references_per_study[row['id']] = references

    print(f"\nExtracted References for Study ID {row['id']} (from {pdf_filename}):")
    for ref in references:
        print(f" - {ref}")

    matched_references = match_references_to_studies(references, titles_dict)
    for ref, cited_study_id in matched_references:
        citation_links.append((row['id'], cited_study_id))
        print(f"Matched Reference: '{ref}' -> Study ID: {cited_study_id}")

cursor = conn.cursor()
for citing_paper_id, cited_paper_id in citation_links:
    cursor.execute(
        "INSERT INTO citation_relationships (citing_paper_id, cited_paper_id) VALUES (%s, %s) ON DUPLICATE KEY UPDATE citing_paper_id=citing_paper_id",
        (citing_paper_id, cited_paper_id)
    )

dictionary = corpora.Dictionary(documents)
corpus = [dictionary.doc2bow(doc) for doc in documents]
lda_model = gensim.models.LdaModel(corpus, num_topics=5, id2word=dictionary, passes=15)

print("\nDiscovered LDA Topics:")
topics = lda_model.print_topics(num_words=10)
for topic_id, topic in topics:
    print(f"Topic {topic_id + 1}: {topic}")

    # Insert LDA topics into lda_topics table
for idx, doc_id in enumerate(document_ids):
    doc_topics = lda_model.get_document_topics(corpus[idx], minimum_probability=0.1)
    for topic_id, prob in doc_topics:
        topic_keywords = ", ".join([word for word, _ in lda_model.show_topic(topic_id, topn=10)])
        try:
            cursor.execute(
                """
                INSERT INTO lda_topics (paper_id, topic_id, topic_name, topic_keywords)
                VALUES (%s, %s, %s, %s)
                ON DUPLICATE KEY UPDATE topic_keywords = VALUES(topic_keywords)
                """,
                (doc_id, topic_id, f"Topic {topic_id + 1}", topic_keywords)
            )
        except Exception as e:
            print(f"Error inserting topic for document {doc_id}: {e}")


topic_based_links = []
for idx, doc_id in enumerate(document_ids):
    doc_topics = lda_model.get_document_topics(corpus[idx], minimum_probability=0.1)
    for topic_id, prob in doc_topics:
        topic_keywords = ", ".join([word for word, prob in lda_model.show_topic(topic_id, topn=10)])
    for j in range(len(documents)):
        if idx != j:
            similarity_score = matutils.cossim(lda_model[dictionary.doc2bow(documents[idx])], lda_model[dictionary.doc2bow(documents[j])])
            if similarity_score > 0.5:
                topic_based_links.append((document_ids[idx], document_ids[j]))
                

for citing_paper_id, cited_paper_id in topic_based_links:
    cursor.execute(
        "INSERT INTO topic_relationships (citing_paper_id, cited_paper_id) VALUES (%s, %s) ON DUPLICATE KEY UPDATE citing_paper_id=citing_paper_id",
        (citing_paper_id, cited_paper_id)
    )

conn.commit()
cursor.close()
conn.close()

print("Citation and Topic-Based Links Processing Complete.")
