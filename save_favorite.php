<?php
require_once('./config.php'); 


if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

try {
    // Ensure the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate input
    $student_id = $_SESSION['student_id'];
    $archive_id = $_POST['archive_id'] ?? null;
    $favorite = $_POST['favorite'] ?? null;

    if (!$archive_id || !isset($favorite)) {
        throw new Exception('Invalid input data');
    }

    if ($favorite == 1) {
        // Add to favorites
        $stmt = $conn->prepare("
            INSERT INTO favorites (student_id, archive_id, created_at)
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE created_at = NOW()
        ");
    } else {
        // Remove from favorites
        $stmt = $conn->prepare("DELETE FROM favorites WHERE student_id = ? AND archive_id = ?");
    }

    $stmt->bind_param("ii", $student_id, $archive_id);

    if (!$stmt->execute()) {
        throw new Exception('Database error: ' . $stmt->error);
    }

    echo json_encode(['status' => 'success', 'message' => $favorite == 1 ? 'Added to favorites' : 'Removed from favorites']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    error_log('Error: ' . $e->getMessage());
}
?>
