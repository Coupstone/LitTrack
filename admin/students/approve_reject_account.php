<?php
// Include database connection and PHPMailer setup
include('../../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

// Define response array
$response = array();

// Check if action, id, and email are set
if (isset($_POST['action'], $_POST['id'], $_POST['email'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    $email = $_POST['email'];

    // Prepare SQL statements based on action
    if ($action == 'approve') {
        // Approve user - update status to 1 (Verified)
        $query = "UPDATE student_list SET status = 1 WHERE id = ? AND email = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('is', $id, $email); // 'i' for integer, 's' for string
            if ($stmt->execute()) {
                // Success response
                $response['status'] = 'success';
                $response['message'] = 'Student approved successfully.';

                // Send email notification
                sendEmail($email, 'Account Approved', 'Your account has been approved. Welcome to the system! You can now access the system using the link: http://localhost/LitTrack/login.php');
            } else {
                // Error executing the query
                $response['status'] = 'error';
                $response['message'] = 'Failed to approve student.';
            }
            $stmt->close();
        } else {
            // SQL preparation error
            $response['status'] = 'error';
            $response['message'] = 'Database error: ' . $conn->error;
        }
    } elseif ($action == 'reject') {
        // Reject user - update status to 2 (Rejected) and store rejection reason
        if (isset($_POST['reason'])) {
            $reason = $_POST['reason'];
            $query = "UPDATE student_list SET status = 2, rejection_reason = ? WHERE id = ? AND email = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param('sis', $reason, $id, $email); // 's' for string, 'i' for integer
                if ($stmt->execute()) {
                    // Success response
                    $response['status'] = 'success';
                    $response['message'] = 'Student rejected successfully.';

                    // Send email notification
                    sendEmail($email, 'Account Rejected', 'Your account has been rejected. Reason: ' . $reason);
                } else {
                    // Error executing the query
                    $response['status'] = 'error';
                    $response['message'] = 'Failed to reject student.';
                }
                $stmt->close();
            } else {
                // SQL preparation error
                $response['status'] = 'error';
                $response['message'] = 'Database error: ' . $conn->error;
            }
        } else {
            // If reason is not provided for rejection
            $response['status'] = 'error';
            $response['message'] = 'Rejection reason is required.';
        }
    } else {
        // Invalid action
        $response['status'] = 'error';
        $response['message'] = 'Invalid action provided.';
    }
} else {
    // Missing required fields
    $response['status'] = 'error';
    $response['message'] = 'Required fields (action, id, email) are missing.';
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// Send email function using PHPMailer
function sendEmail($toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com';  // Set your mail server (e.g., 'smtp.gmail.com')
        $mail->SMTPAuth = true;
        $mail->Username = 'kimjudeamayon468@gmail.com'; // Your email
        $mail->Password = 'gcmf ngei whwl wigq'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'LitTrack');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Close the database connection
$conn->close();
?>
