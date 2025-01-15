<head>

<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>
    <link rel="icon" href="uploads/LitTrack.png" type="image/png">

<style>
    /* Overlay for the pop-up */
    .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        z-index: 9999;
    }

    /* Make the overlay visible */
    .popup-overlay.visible {
        opacity: 1;
        visibility: visible;
    }

    /* Pop-up container */
    .popup {
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-50px);
        opacity: 0;
        animation: slideIn 0.5s forwards;
    }

    /* Animation for the pop-up */
    @keyframes slideIn {
        0% {
            transform: translateY(-50px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Pop-up header */
    .popup h4 {
        margin: 0;
        font-size: 22px;
        color: #28a745;
        font-family: 'Arial', sans-serif;
        font-weight: bold;
    }

    /* Pop-up body */
    .popup p {
        margin-top: 10px;
        color: #333;
        font-size: 16px;
        line-height: 1.5;
    }

    /* Button styles */
    .popup button {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-family: 'Arial', sans-serif;
        transition: background-color 0.3s ease;
    }

    /* Button hover effect */
    .popup button:hover {
        background-color: #0056b3;
    }
    .hidden-output {
    display: none;
}

</style>
</head>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

require_once('./config.php'); // Database connection

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    
        // Sanitize email input to prevent SQL injection
        $email = $conn->real_escape_string($email);

        // Query to check if the email exists in the student table
        $query = "SELECT * FROM student_list WHERE email = '$email'";
        $result = $conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            echo '<div class="hidden-output">exists</div>';
        } else {
            echo '<div class="hidden-output">not_exists</div>';
        }

    // Query to find the user by email
    $query = $conn->prepare("SELECT * FROM student_list WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // User exists
        $student_list = $result->fetch_assoc();

        // Generate token and expiration time
        $token = bin2hex(random_bytes(16)); // Generate a random token
        $expiration = date("Y-m-d H:i:s", strtotime('+1 hour')); // Expiration time (1 hour)

        // Update token and expiration in student_list
        $update_query = $conn->prepare("UPDATE student_list SET reset_token = ?, reset_token_expiration = ? WHERE email = ?");
        $update_query->bind_param("sss", $token, $expiration, $email);
        if (!$update_query->execute()) {
            die("Execute failed: " . $update_query->error);
        }

        // Set up PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server for Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'kimjudeamayon468@gmail.com'; // Your email
            $mail->Password = 'gcmf ngei whwl wigq'; // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS encryption
            $mail->Port = 587; // Gmail SMTP port

            // Email settings
            $mail->setFrom('your-email@gmail.com', 'LitTrack'); // Sender email and name
            $mail->addAddress($student_list['email']); // Recipient's email
            $mail->addReplyTo($student_list['email']); // Set "Reply-To" to recipient's email (optional)
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Password Reset';
            
            // Correct reset link with http://localhost/ URL
            $reset_link = "http://localhost/LitTrack/reset_password.php?token=" . $token;
            $mail->Body = "Click <a href='$reset_link'>here</a> to reset your password. This link will expire in 1 hour.";

            $mail->send();

            // Success message with SweetAlert2
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Reset Link Sent!',
                    text: 'Please check your email to proceed with the password reset.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect based on email provider
                        var email = '$email';
                        var emailDomain = email.split('@')[1];
                        var emailProviders = {
                            'gmail.com': 'https://mail.google.com/',
                            'yahoo.com': 'https://mail.yahoo.com/',
                            'outlook.com': 'https://outlook.live.com/mail/',
                            'hotmail.com': 'https://outlook.live.com/mail/',
                        };

                        // Default to Gmail if email provider not matched
                        var redirectUrl = emailProviders[emailDomain] || 'https://mail.google.com/';
                        window.location.href = redirectUrl;
                    }
                });
            </script>";
        } catch (Exception $e) {
            // Error message with SweetAlert2
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to send the reset email. Please try again later.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    } else {
        // If no user exists with that email, display error
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                title: 'No User Found',
                text: 'No account exists with the provided email address.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
} else {
    // If email is not provided
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'Input Required',
            text: 'Please provide your email address.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    </script>";
}
?>