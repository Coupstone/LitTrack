<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';
require_once('./config.php'); // Database connection


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Grant Access Confirmation</title>
        <link rel='icon' href='uploads/LitTrack.png' type='image/png'/>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script> <!-- Include jQuery -->
    </head>
    <body>
        <script>
    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to grant the request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, grant access',
        cancelButtonText: 'No, deny access'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading screen for granting access
            Swal.fire({
                title: 'Processing...',
                text: 'Granting access, please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Grant access via AJAX
            $.ajax({
                url: 'grant_access.php',
                method: 'POST',
                data: { action: 'grant', token: '{$token}' },
                success: function() {
                    Swal.close(); // Close loading screen
                    Swal.fire({
                        title: 'Success',
                        text: 'Access granted successfully.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = 'https://mail.google.com'; // Redirect to Gmail
                    });
                },
                error: function() {
                    Swal.close(); // Close loading screen
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to process the request.',
                        icon: 'error'
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Show loading screen for denying access
            Swal.fire({
                title: 'Processing...',
                text: 'Denying access, please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Deny access via AJAX
            $.ajax({
                url: 'grant_access.php',
                method: 'POST',
                data: { action: 'deny', token: '{$token}' },
                success: function() {
                    Swal.close(); // Close loading screen
                    Swal.fire({
                        title: 'Request Denied',
                        text: 'Access request denied successfully.',
                        icon: 'info'
                    }).then(() => {
                        window.location.href = 'https://mail.google.com'; // Redirect to Gmail
                    });
                },
                error: function() {
                    Swal.close(); // Close loading screen
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to process the request.',
                        icon: 'error'
                    });
                }
            });
        }
    });
</script>

    </body>
    </html>";
    exit;
}

// Function to send email notifications
function sendEmail($action, $request) {
    $student_email = $request['email']; // Assuming the email column exists
    $from_email = "pupsrclittrack@gmail.com";

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kimjudeamayon468@gmail.com
        '; // Replace with your email
                    $mail->Password = 'gcmf ngei whwl wigq
        ';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom($from_email, 'LitTrack');
        $mail->addAddress($student_email);

        // Email content
        if ($action === 'grant') {
            $mail->Subject = "Access Request Granted";
            $mail->Body = "Your request for access has been granted. You can now view the requested Research.<br><br>Thank you.";
        } elseif ($action === 'deny') {
            $mail->Subject = "Access Request Denied";
            $mail->Body = "We regret to inform you that your request for access has been denied.<br><br>Thank you.";
        } else {
            throw new Exception("Invalid action.");
        }

        $mail->isHTML(true);
        $mail->send();
        echo "Email sent successfully to $student_email.";
    } catch (Exception $e) {
        echo "Failed to send email. PHPMailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $token = $_POST['token'] ?? null;

    if ($token) {
        // Verify the token
        $stmt = $conn->prepare("SELECT * FROM access_requests WHERE token = ? AND status = 'pending'");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $request = $result->fetch_assoc();
            $request_id = $request['id'];
            $user_id = $request['user_id'];

            // Fetch the requestor's email
            $requestor_stmt = $conn->prepare("SELECT email FROM student_list WHERE id = ?");
            $requestor_stmt->bind_param('i', $user_id);
            $requestor_stmt->execute();
            $requestor_result = $requestor_stmt->get_result();
            $requestor_data = $requestor_result->fetch_assoc();
            $requestor_email = $requestor_data['email'];

            if ($action === 'grant') {
                // Update the status to 'granted'
                $update_stmt = $conn->prepare("UPDATE access_requests SET status = 'granted' WHERE id = ?");
                $update_stmt->bind_param('i', $request_id);
                $update_stmt->execute();

                // Send email to the requestor
                sendEmail('grant', $requestor_data);
                echo json_encode(['status' => 'success', 'message' => 'Access granted and email sent.']);
            } elseif ($action === 'deny') {
                // Update the status to 'denied'
                $update_stmt = $conn->prepare("UPDATE access_requests SET status = 'denied' WHERE id = ?");
                $update_stmt->bind_param('i', $request_id);
                $update_stmt->execute();

                // Send email to the requestor
                sendEmail('deny', $requestor_data);
                echo json_encode(['status' => 'success', 'message' => 'Access denied and email sent.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Missing token.']);
    }
}
?>