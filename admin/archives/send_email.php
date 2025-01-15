<?php
// Include database connection and PHPMailer setup
include('../../config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update status in the database
    $updateQry = $conn->query("UPDATE archive_list SET status = '$status' WHERE id = '$id'");

    if ($updateQry) {
        // Fetch student email and title of the study
        $qry = $conn->query("SELECT student_list.email, a.title 
                             FROM student_list 
                             INNER JOIN archive_list a ON student_list.id = a.student_id
                             WHERE a.id = '$id'");
        $student = $qry->fetch_assoc();

        if ($student) {
            $email = $student['email'];
            $title = $student['title'];
            $status_text = $status == 1 ? 'approved' : 'rejected';

            // Send email notification
            $subject = "Research Status Update";
            $message = "Dear Student,<br><br>Your research titled <strong>$title</strong> has been <strong>$status_text</strong>.<br><br>Thank you.";

            if (sendEmail($email, $subject, $message)) {
                echo json_encode(["status" => "success", "msg" => "Email sent successfully."]);
            } else {
                echo json_encode(["status" => "failed", "msg" => "Failed to send email."]);
            }
        } else {
            echo json_encode(["status" => "failed", "msg" => "Student not found."]);
        }
    } else {
        echo json_encode(["status" => "failed", "msg" => "Failed to update status."]);
    }
}

// Function to send email using PHPMailer
function sendEmail($toEmail, $subject, $body)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kimjudeamayon468@gmail.com'; // Your email
        $mail->Password = 'gcmf ngei whwl wigq'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('pupsrclittrack@gmail.com', 'LitTrack'); // Sender details
        $mail->addAddress($toEmail); // Add recipient

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Attempt to send the email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}"); // Log error details
        return false;
    }
}
?>