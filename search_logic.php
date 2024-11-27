<?php
require_once('./config.php');

// Get the search query from the GET request
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Debugging: Log the search query
echo "<script>console.log('Search Query: " . addslashes($search_query) . "');</script>";

if ($search_query) {
    // Add wildcards for LIKE queries
    $search_term = '%' . $search_query . '%';

    // SQL query to search across multiple columns
    $sql = "
        SELECT 
            a.id, 
            a.title, 
            a.abstract, 
            a.year, 
            GROUP_CONCAT(CONCAT(aa.first_name, ' ', aa.last_name) SEPARATOR ', ') AS authors, 
            IFNULL(s.email, u.username) AS submitted_by 
        FROM 
            archive_list a
        LEFT JOIN student_list s ON a.student_id = s.id
        LEFT JOIN users u ON a.uploader_id = u.id
        LEFT JOIN archive_authors aa ON a.id = aa.archive_id
        WHERE 
            a.title LIKE ? OR 
            a.abstract LIKE ? OR 
            aa.first_name LIKE ? OR 
            aa.last_name LIKE ? OR
            CONCAT(aa.first_name, ' ', aa.last_name) LIKE ? OR
            a.year LIKE ? OR 
            s.email LIKE ? OR 
            u.username LIKE ?
        GROUP BY a.id
    ";

    // Debugging: Log the raw SQL query
    echo "<script>console.log('Raw SQL Query: " . addslashes($sql) . "');</script>";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "<script>console.error('Error preparing query: " . addslashes($conn->error) . "');</script>";
        die("Error preparing query: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("ssssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);

    // Execute the query
    if (!$stmt->execute()) {
        echo "<script>console.error('Error executing query: " . addslashes($stmt->error) . "');</script>";
        die("Error executing query: " . $stmt->error);
    }

    // Fetch results
    $results = $stmt->get_result();
    if (!$results) {
        echo "<script>console.error('Error fetching results: " . addslashes($conn->error) . "');</script>";
        die("Error fetching results: " . $conn->error);
    }

    // Display results
    if ($results->num_rows > 0) {
        echo '<ul class="list-group">';
        while ($row = $results->fetch_assoc()) {
            echo '<li class="list-group-item">';
            echo '<h5>' . htmlspecialchars($row['title']) . '</h5>';
            echo '<p><strong>Authors:</strong> ' . htmlspecialchars($row['authors']) . '</p>';
            echo '<p><strong>Year:</strong> ' . htmlspecialchars($row['year']) . '</p>';
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo "<p>No results found for the query: " . htmlspecialchars($search_query) . "</p>";
    }
} else {
    echo "<p>No search query provided.</p>";
}
?>
