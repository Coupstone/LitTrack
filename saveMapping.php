<?php
require_once('./config.php');
header('Content-Type: application/json');

$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $host = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $database = "otas_db"; 

    $db = new mysqli($host, $username, $password, $database);
    if ($db->connect_error) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . $db->connect_error]);
        exit;
    }

    // Ensure unique entry by updating `viewed_at` if it already exists
    $query = "INSERT INTO recent_student_mappings (student_id, mapping_id, viewed_at) 
              VALUES (?, ?, NOW()) 
              ON DUPLICATE KEY UPDATE viewed_at = NOW()";

    $stmt = $db->prepare($query);
    $stmt->bind_param("ii", $student_id, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Mapping saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error saving mapping: " . $stmt->error]);
    }
    $stmt->close();
    $db->close();
} else {
    echo json_encode(["status" => "error", "message" => "No mapping ID provided."]);
}
