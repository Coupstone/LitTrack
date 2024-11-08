<?php
// Assuming you have a configuration file that sets up your database connection
require_once('./config.php');

// Check if the archive_id is sent via POST
if (isset($_POST['archive_id'])) {
    $archive_id = intval($_POST['archive_id']); // Get the archive ID and ensure it's an integer

    // Prepare the statement to insert a new download record
    $insert_download_stmt = $conn->prepare("INSERT INTO archive_downloads (archive_id) VALUES (?)");
    $insert_download_stmt->bind_param("i", $archive_id);
    $success = $insert_download_stmt->execute();

    // Check if the insertion was successful
    if ($success) {
        // Optionally, fetch the new download count to return it to the front-end
        $download_stmt = $conn->prepare("SELECT COUNT(*) AS download_count FROM archive_downloads WHERE archive_id = ?");
        $download_stmt->bind_param("i", $archive_id);
        $download_stmt->execute();
        $download_result = $download_stmt->get_result();
        $download_data = $download_result->fetch_assoc();

        echo json_encode([
            'success' => true,
            'download_count' => $download_data['download_count']
        ]);
    } else {
        // Return an error message if the insertion failed
        echo json_encode([
            'success' => false,
            'error' => 'Failed to increment download count.'
        ]);
    }
} else {
    // Return an error message if no archive_id was provided
    echo json_encode([
        'success' => false,
        'error' => 'No archive ID provided.'
    ]);
}
?>
