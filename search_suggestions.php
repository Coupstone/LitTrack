<?php
require_once('./config.php');

// Get the search query
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search_query !== '') { // Ensure the query is not empty
    // SQL query to fetch matching studies
    $sql = "
        SELECT 
            a.id, 
            a.title, 
            GROUP_CONCAT(CONCAT(aa.first_name, ' ', aa.last_name) SEPARATOR ', ') AS authors 
        FROM 
            archive_list a
        LEFT JOIN archive_authors aa ON a.id = aa.archive_id
        WHERE 
            a.title LIKE ? OR 
            aa.first_name LIKE ? OR 
            aa.last_name LIKE ? OR
            CONCAT(aa.first_name, ' ', aa.last_name) LIKE ?
        GROUP BY a.id
        LIMIT 10
    ";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $results = $stmt->get_result();

    // Fetch and return the results as JSON
    $suggestions = [];
    while ($row = $results->fetch_assoc()) {
        $suggestions[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'authors' => $row['authors'] ?? 'N/A'
        ];
    }

    echo json_encode($suggestions);
    exit;
}

// Return an empty JSON array if no query
echo json_encode([]);
exit;
