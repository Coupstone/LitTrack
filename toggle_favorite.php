<?php
session_start();
require_once('initialize.php');
require_once('classes/DBConnection.php');

$db = new DBConnection;
$conn = $db->conn;

header('Content-Type: application/json');

if (isset($_POST['research_id'])) {
    $research_id = intval($_POST['research_id']);
    $user_id = $_SESSION['userdata']['id'];

    $checkUser = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $checkUser->bind_param("i", $user_id);
    $checkUser->execute();
    $userExists = $checkUser->get_result()->num_rows > 0;

    $checkResearch = $conn->prepare("SELECT * FROM research WHERE id = ?");
    $checkResearch->bind_param("i", $research_id);
    $checkResearch->execute();
    $researchExists = $checkResearch->get_result()->num_rows > 0;

    if (!$userExists || !$researchExists) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user or research ID.']);
        exit;
    }

    $query = $conn->prepare("SELECT * FROM favorites WHERE user_id = ? AND research_id = ?");
    $query->bind_param("ii", $user_id, $research_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $deleteQuery = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND research_id = ?");
        $deleteQuery->bind_param("ii", $user_id, $research_id);
        $deleteQuery->execute();

        echo json_encode(['status' => 'removed']);
    } else {
        $insertQuery = $conn->prepare("INSERT INTO favorites (user_id, research_id) VALUES (?, ?)");
        $insertQuery->bind_param("ii", $user_id, $research_id);
        $insertQuery->execute();

        echo json_encode(['status' => 'added']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No research ID provided.']);
}
?>
