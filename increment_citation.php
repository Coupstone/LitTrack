<?php
require_once('./config.php');

if (isset($_POST['archive_id'])) {
    $archive_id = intval($_POST['archive_id']);

    // Insert a new entry for each download citation event
    $insert_stmt = $conn->prepare("INSERT INTO archive_citation (archive_id) VALUES (?)");
    $insert_stmt->bind_param("i", $archive_id);
    $insert_stmt->execute();

    // Fetch the updated citation count by counting rows for this archive_id
    $count_stmt = $conn->prepare("SELECT COUNT(*) AS citation_count FROM archive_citation WHERE archive_id = ?");
    $count_stmt->bind_param("i", $archive_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $citation_count = ($count_result->num_rows > 0) ? $count_result->fetch_assoc()['citation_count'] : 0;

    // Return JSON response with the updated count
    echo json_encode(['success' => true, 'new_citation_count' => $citation_count]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
