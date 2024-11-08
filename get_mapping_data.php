<?php
require_once('./config.php');

$study_id = $_GET['study_id'];
$db = new mysqli("localhost", "root", "", "otas_db");

$literature_query = "
    SELECT al.id, al.title, al.year AS publication_year, CONCAT(aa.first_name, ' ', aa.last_name) AS author 
    FROM archive_list al 
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id 
    WHERE al.status = 1 AND (al.id = ? OR al.id IN (SELECT cited_paper_id FROM citation_relationships WHERE citing_paper_id = ?))
    AND aa.author_order = 1";  // Fetch only primary authors

$stmt = $db->prepare($literature_query);
$stmt->bind_param("ii", $study_id, $study_id);
$stmt->execute();
$result = $stmt->get_result();

$literature = [];
while ($row = $result->fetch_assoc()) {
    $literature[] = $row;
}

// Get citation relationships
$citation_query = "SELECT citing_paper_id, cited_paper_id FROM citation_relationships WHERE (citing_paper_id = ? OR cited_paper_id = ?) AND cited_paper_id IN (SELECT id FROM archive_list WHERE status = 1)";
$stmt = $db->prepare($citation_query);
$stmt->bind_param("ii", $study_id, $study_id);
$stmt->execute();
$result = $stmt->get_result();

$citations = [];
while ($row = $result->fetch_assoc()) {
    $citations[] = $row;
}

echo json_encode(['literature' => $literature, 'citations' => $citations]);

$stmt->close();
$db->close();
?>
