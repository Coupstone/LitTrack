<?php
require_once('./config.php');

$query = trim($_GET['query']);
$db = new mysqli("localhost", "root", "", "otas_db");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Prepare the query to search by title or primary author's name or publication year
// Use UPPER to make the search case-insensitive
$searchTerm = "%" . strtoupper($query) . "%";
$stmt = $db->prepare("
    SELECT al.id, al.title, al.year AS publication_year, 
           CONCAT(aa.first_name, ' ', aa.last_name) AS author
    FROM archive_list al
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id
    WHERE al.status = 1 
    AND aa.author_order = 1  -- Ensure we are only considering the primary author
    AND (UPPER(al.title) LIKE ? 
         OR UPPER(aa.first_name) LIKE ? 
         OR UPPER(aa.last_name) LIKE ? 
         OR UPPER(CONCAT(aa.first_name, ' ', aa.last_name)) LIKE ? 
         OR al.year LIKE ?)
    GROUP BY al.id
    LIMIT 5
");

// Bind the same search term to all placeholders
$stmt->bind_param("sssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$studies = [];
while ($row = $result->fetch_assoc()) {
    $studies[] = $row;
}

echo json_encode($studies);

$stmt->close();
$db->close();
?>
