87% of storage used … If you run out, you can't create, edit and upload files. Get 100 GB of storage for ₱89.00 ₱0 for 1 month.
<?php
// Include database configuration
require_once('./config.php'); // Assuming you have a connection variable $conn

if (isset($_POST['email'])) {
    $email = $_POST['email']; // email from AJAX request
    $query = "SELECT * FROM student_list WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Email exists in the database
        echo json_encode(['status' => 'exists']);
    } else {
        // Email does not exist
        echo json_encode(['status' => 'not_exists']);
    }
}    
?>