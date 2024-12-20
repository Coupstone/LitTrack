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

// Recursive function to fetch all related nodes up to a certain depth
function fetchRelatedNodes($db, $study_id, $depth = 2, $seen = []) {
    if ($depth == 0 || in_array($study_id, $seen)) {
        return [];
    }

    $seen[] = $study_id;

    $query = "
        SELECT cr.citing_paper_id, cr.cited_paper_id
        FROM citation_relationships cr
        WHERE cr.citing_paper_id = ? OR cr.cited_paper_id = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $study_id, $study_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $related = [];
    while ($row = $result->fetch_assoc()) {
        $related[] = $row;
        $related = array_merge($related, fetchRelatedNodes($db, $row['citing_paper_id'], $depth - 1, $seen));
        $related = array_merge($related, fetchRelatedNodes($db, $row['cited_paper_id'], $depth - 1, $seen));
    }
    $stmt->close();

    return $related;
}

// Fetch all related nodes and citations
$citations = fetchRelatedNodes($db, $study_id, 2);

// Fetch literature details for all related nodes
$node_ids = array_unique(array_merge(
    array_column($citations, 'citing_paper_id'),
    array_column($citations, 'cited_paper_id')
));

if (empty($node_ids)) {
    $node_ids = [$study_id]; // Ensure the main node is included
}

$placeholders = implode(',', array_fill(0, count($node_ids), '?'));
$query = "
    SELECT al.id, 
           COALESCE(al.title, 'Unknown Title') AS title, 
           COALESCE(al.year, 'Unknown Year') AS publication_year, 
           COALESCE(al.abstract, 'No Abstract Available') AS abstract,
           GROUP_CONCAT(CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) SEPARATOR ', ') AS authors,
           (SELECT CONCAT(COALESCE(aa.first_name, ''), ' ', COALESCE(aa.last_name, '')) 
            FROM archive_authors aa 
            WHERE aa.archive_id = al.id AND aa.author_order = 1 LIMIT 1) AS primary_author,
           (SELECT COUNT(*) 
            FROM citation_relationships cr 
            WHERE cr.cited_paper_id = al.id) AS citation_count
    FROM archive_list al
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id
    WHERE al.id IN ($placeholders)
    GROUP BY al.id";

$stmt = $db->prepare($query);
$stmt->bind_param(str_repeat('i', count($node_ids)), ...$node_ids);
$stmt->execute();
$result = $stmt->get_result();

$literature = [];
while ($row = $result->fetch_assoc()) {
    $literature[] = $row;
}
$stmt->close();

echo json_encode(['literature' => $literature, 'citations' => $citations]);
$db->close();
?>