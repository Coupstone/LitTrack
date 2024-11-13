<?php
require_once('./config.php');

// Check if student is logged in
$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    die("User not logged in.");
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Database connection
    $host = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $database = "otas_db"; 

    $db = new mysqli($host, $username, $password, $database);
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Delete the mapping from `recent_student_mappings` table
    $query = "DELETE FROM recent_student_mappings WHERE student_id = ? AND mapping_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $student_id, $id);

    if ($stmt->execute()) {
        echo "Mapping removed from recent mappings successfully.";
    } else {
        echo "Error removing mapping: " . $stmt->error;
    }

    $stmt->close();
    $db->close();
} else {
    echo "Mapping ID is required.";
}
?>
