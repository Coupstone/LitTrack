<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

require_once('./config.php'); // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $archive_id = $_POST['archive_id'] ?? null;
    // $action = $_POST['action'] ?? '';
    $user_id = $_SESSION['student_id'];

    if (!$archive_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        exit;
    }

    // Fetch the owner's email and other archive details
    $owner_stmt = $conn->prepare("SELECT a.title, s.email
                                  FROM archive_list a
                                  JOIN student_list s ON a.student_id = s.id
                                  WHERE a.id = ?");
    $owner_stmt->bind_param("i", $archive_id);
    $owner_stmt->execute();
    $owner_result = $owner_stmt->get_result();

    if ($owner_result->num_rows > 0) {
        $owner_data = $owner_result->fetch_assoc();
        $owner_email = $owner_data['email'];
        $archive_title = htmlspecialchars($owner_data['title']);

        $token = bin2hex(random_bytes(32));

        $timestamp = (new DateTime())->format('Y-m-d H:i:s');
        $status = 'pending';
        $requestor = $conn->prepare("INSERT INTO access_requests(archive_id, user_id, token, status, created_at, updated_at) VALUES(?, ?, ?, ?, ?, ?)");
        $requestor->bind_param('iissss', $archive_id, $user_id, $token, $status, $timestamp, $timestamp);
        $requestor->execute();

        // Send the email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'kimjudeamayon468@gmail.com'; // Your email
            $mail->Password = 'phcf xlrb gvwh rwes'; // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'LitTrack'); // Sender's email
            $mail->addAddress($owner_email); // Owner's email


            // Confirmation link
            $base_url = "http://localhost/LITTRACK"; // Replace 'your_project_folder' with your XAMPP project folder


            // Generate confirmation URL
            $confirm_url = "{$base_url}/grant_access.php?token={$token}";


            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Access Request for Research: {$archive_title}";
            $mail->Body = "Hello,<br><br>A user has requested access to your research titled <b>{$archive_title}</b>.
                           Please <a href='{$confirm_url}'>click here</a> to grant or deny the request.<br><br>Thank you!";
            $mail->AltBody = "Hello, A user has requested access to your research titled {$archive_title}.
                              Please visit the following link to grant or deny the request: {$confirm_url}";


            $mail->send();
            echo json_encode(['success' => true, 'message' => 'Access request saved, and notification email sent.']);
        } catch (Exception $e) {
            echo json_encode(['success' => true, 'message' => 'Access request saved, but email could not be sent.']);
            error_log("PHPMailer Error: {$mail->ErrorInfo}");
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Access request saved, but no owner details found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save the access request.']);
}

?>



