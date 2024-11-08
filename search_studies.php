<?php
require_once('./config.php');

$query = $_GET['query'];
$db = new mysqli("localhost", "root", "", "otas_db");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Prepare the query to search by title or primary author's name or publication year
$stmt = $db->prepare("
    SELECT al.id, al.title, al.year AS publication_year, 
           CONCAT(aa.first_name, ' ', aa.last_name) AS author
    FROM archive_list al
    LEFT JOIN archive_authors aa ON al.id = aa.archive_id
    WHERE al.status = 1 
    AND aa.author_order = 1  -- Ensure we are only considering the primary author
    AND (al.title LIKE CONCAT('%', ?, '%') 
         OR aa.first_name LIKE CONCAT('%', ?, '%') 
         OR aa.last_name LIKE CONCAT('%', ?, '%') 
         OR al.year LIKE CONCAT('%', ?, '%'))
    GROUP BY al.id
    LIMIT 5
");

// Split the query to bind it multiple times
$stmt->bind_param("ssss", $query, $query, $query, $query);
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
