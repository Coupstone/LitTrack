<?php
include '../../config.php'; // Include your database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    error_log("Fetching COR for Student ID: $id");

    // Query to get COR filename for the student
    $query = $conn->query("SELECT cor FROM student_list WHERE id = $id");

    if (!$query) {
        error_log("Database query error: " . $conn->error);
        echo '<p>Database query failed. Check server logs for details.</p>';
        exit;
    }

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();

        // Define the base path for the COR files
        $cor_path = '../../classes/uploads/cor/';
        $cor_file = $cor_path . $row['cor'];

        // Debugging output
        error_log("Generated COR file path: " . realpath($cor_file));

        // Check if the COR file exists and is not empty
        if (!empty($row['cor']) && file_exists($cor_file)) {
            // echo '<p><strong>Certificate of Registration (COR):</strong></p>';
            echo '<iframe src="/LitTrack/classes/uploads/cor/' . $row['cor'] . '" width="100%" height="500px"></iframe>';
        } else {
            error_log("COR file not found or missing: " . realpath($cor_file));
            echo '<p>No COR data found for this student or file is missing.</p>';
        }
    } else {
        error_log("No COR data found for Student ID: $id");
        echo '<p>No COR data found for this student.</p>';
    }
} else {
    error_log("No Student ID provided in request.");
    echo '<p>No student ID provided.</p>';
}
?>
