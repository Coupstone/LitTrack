<?php
require_once('./config.php');

// Get the study ID from the request
$study_id = $_GET['study_id'] ?? null;

if (!$study_id) {
    die(json_encode(['error' => 'Missing study ID']));
}

$db = new mysqli("localhost", "root", "", "otas_db");
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}


// Fetch all nodes (citing and cited papers), including authors, primary author, and citation count
$literature_query = "
    SELECT al.id, 
           COALESCE(al.title, 'Unknown Title') AS title, 
           COALESCE(al.year, 'Unknown Year') AS publication_year, 
           COALESCE(al.abstract, 'No Abstract Available') AS abstract,
           GROUP_CONCAT(CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) SEPARATOR ', ') AS authors,
           (SELECT CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) 
            FROM archive_authors aa 
            WHERE aa.archive_id = al.id AND aa.author_order = 1 LIMIT 1) AS primary_author,
           -- Citation count
           (SELECT COUNT(*) 
            FROM citation_relationships cr 
            WHERE cr.cited_paper_id = al.id) AS citation_count
    FROM archive_list al
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id
    WHERE al.status = 1 
    AND al.id IN (
        SELECT cited_paper_id FROM citation_relationships WHERE citing_paper_id = ? 
        UNION 
        SELECT citing_paper_id FROM citation_relationships WHERE cited_paper_id = ? 
        UNION 
        SELECT ?
    )
    GROUP BY al.id";

$stmt = $db->prepare($literature_query);
$stmt->bind_param("iii", $study_id, $study_id, $study_id);
$stmt->execute();
$literature_result = $stmt->get_result();

$literature = [];
while ($row = $literature_result->fetch_assoc()) {
    // Add the row to the $literature array
    $literature[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'year' => $row['publication_year'],
        'abstract' => $row['abstract'],
        'authors' => $row['authors'],
        'primary_author' => $row['primary_author'],
        'citation_count' => $row['citation_count'] // Add citation count here
    ];
}
$stmt->close();

// Fetch citation relationships
$citation_query = "
    SELECT citing_paper_id, cited_paper_id 
    FROM citation_relationships 
    WHERE citing_paper_id IN (
        SELECT id FROM archive_list WHERE status = 1
    ) 
    AND cited_paper_id IN (
        SELECT id FROM archive_list WHERE status = 1
    ) 
    AND (citing_paper_id = ? OR cited_paper_id = ?)";
$stmt = $db->prepare($citation_query);
$stmt->bind_param("ii", $study_id, $study_id);
$stmt->execute();
$citation_result = $stmt->get_result();

$citations = [];
while ($row = $citation_result->fetch_assoc()) {
    $citations[] = $row;
}
$stmt->close();

echo json_encode(['literature' => $literature, 'citations' => $citations]);
$db->close();
?>
