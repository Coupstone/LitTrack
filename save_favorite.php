<!-- <?php
require_once('config.php'); // Database connection

if (isset($_POST['archive_id']) && isset($_POST['favorite'])) {
    $archive_id = $_POST['archive_id'];
    $favorite = $_POST['favorite']; // 1 = favorite, 0 = not favorite

    // Update the favorite status of the research in the database
    $stmt = $conn->prepare("UPDATE archive_list SET is_favorite = ? WHERE id = ?");
    $stmt->bind_param('ii', $favorite, $archive_id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}
?> -->
