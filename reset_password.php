<?php
require 'config.php';

$message = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token and check if it's not expired
    $stmt = $conn->prepare("SELECT * FROM student_list WHERE reset_token = ? AND reset_token_expiration > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['password'];

            // Password validation in PHP
            if (preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
                $new_password_hashed = md5($new_password);

                // Update password and clear reset token
                $stmt = $conn->prepare("UPDATE student_list SET password = ?, reset_token = '', reset_token_expiration = '0000-00-00 00:00:00' WHERE id = ?");
                $stmt->bind_param("si", $new_password_hashed, $user['id']);
                $stmt->execute();

                $message = "Your password has been successfully reset.";
                $popup_class = "success";
            } else {
                $message = "Password does not meet the required criteria.";
                $popup_class = "error";
            }
        }
    } else {
        $message = "Invalid or expired reset token.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack - Reset Password</title>

    <!-- Bootstrap CSS -->
    <link href="styles/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Site Icon -->
    <link rel="icon" href="uploads/LitTrack.png" type="image/png"/>

    <!-- Custom CSS for Pop-up -->
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 1.2s ease-in-out, visibility 1.2s ease-in-out;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .popup {
            background-color: white;
            padding: 35px;
            border-radius: 15px;
            text-align: center;
            max-width: 450px;
            width: 100%;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transform: translateY(150px);
            opacity: 0;
            transition: transform 1s cubic-bezier(0.19, 1, 0.22, 1), opacity 1s ease;
        }

        .popup.success {
            border: 3px solid #28a745;
        }

        .popup.error {
            border: 3px solid #dc3545;
        }

        .popup.active {
            transform: translateY(0);
            opacity: 1;
        }

        .popup h4 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        .popup button {
            margin-top: 25px;
            padding: 12px 30px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.4s ease, transform 0.4s ease;
        }

        .popup button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .popup.success h4 {
            color: #28a745;
            text-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }

        .popup.error h4 {
            color: #dc3545;
            text-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
        }
    </style>
</head>

<body class="bg-image">

<div class="container-fluid position-absolute top-50 start-50 translate-middle">
    <div class="row justify-content-center align-items-center">
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
            <div class="text-center login-form-gradient shadow-lg">
                <div class="d-flex align-items-center justify-content-center">
                    <div>
                        <img src="uploads/LitLogo.png" alt="PUP Logo" class="mb-4 img-fluid" style="max-width: 200px;">
                    </div>
                </div>
                <div class="text-carbon-grey justify-content-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <h4 class="fs-4 mb-0 fw-semibold">Reset Password</h4>
                    </div>
                    <div class="mb-4 font-14">Enter your new password below.</div>
                </div>

                <!-- Reset Password Form -->
                <?php if (isset($_GET['token']) && !empty($_GET['token'])): ?>
                    <form action="" method="POST" id="reset-password-form" class="needs-validation" novalidate>
                        <div class="form-floating mb-3"> 
                            <input type="password" class="form-control text-carbon-grey shadow-sm" name="password" id="password" placeholder="New Password" autocomplete="new-password" required>
                            <label for="password" class="fw-medium text-carbon-grey font-13">New Password<span style="color: red;"> *</span></label>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback text-start font-13">
                                Please enter your new password.
                            </div> 
                        </div>
                      
                        <div class="form-floating">
                            <input type="password" class="form-control text-carbon-grey shadow-sm" name="confirm_password" id="confirm_password" placeholder="Confirm Password" autocomplete="new-password" required>
                            <label for="confirm_password" class="fw-medium text-carbon-grey font-13">Confirm Password<span style="color: red;"> *</span></label>          
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback text-start font-13">
                                Please confirm your password.
                            </div>
                        </div>
                        
                        <div for="submitForm" class="justify-content-center d-md-flex mt-4 mb-2">
                            <div class="input-group">
                                <button type="submit" name="reset_password_btn" id="submitForm" class="btn col-12 btn-primary py-3 font-14" style="background-color: #5875B5; color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-arrow-right-circle-fill pe-1" viewBox="0 0 16 16">
                                        <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <p class="text-danger">Invalid request. No token provided.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- Pop-up Message -->
<?php if (!empty($message)): ?>
    <div class="overlay active">
        <div class="popup <?php echo $popup_class; ?> active">
            <h4><?php echo $message; ?></h4>
            <button onclick="window.location.href='login.php'">Go to Login</button>
        </div>
    </div>
<?php endif; ?>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('reset-password-form');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var passwordInput = form.querySelector('input[name="password"]');
            var confirmPasswordInput = form.querySelector('input[name="confirm_password"]');

            // Reset validation states
            passwordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.remove('is-invalid');

            var isValid = true;

            // Strong password regex: 8 characters, one uppercase, one lowercase, one number, and one special character
            var strongPasswordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/;

            // Check if passwords meet criteria
            if (!passwordInput.value.match(strongPasswordPattern)) {
                showPopup("Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a digit, and a special character.", "error");
                isValid = false;
            }

            if (!confirmPasswordInput.value || confirmPasswordInput.value !== passwordInput.value) {
                showPopup("Passwords do not match.", "error");
                isValid = false;
            }

            // If valid, proceed with form submission
            if (isValid) {
                form.submit();
            }
        });

        // Function to show a pop-up message
        function showPopup(message, type) {
            var overlay = document.createElement('div');
            overlay.classList.add('overlay', 'active');

            var popup = document.createElement('div');
            popup.classList.add('popup', type, 'active');

            var messageElement = document.createElement('h4');
            messageElement.textContent = message;

            var okButton = document.createElement('button');
            okButton.textContent = "OK";
            okButton.classList.add('btn', 'btn-primary');
            okButton.onclick = function () {
                document.body.removeChild(overlay);
            };

            popup.appendChild(messageElement);
            popup.appendChild(okButton);
            overlay.appendChild(popup);
            document.body.appendChild(overlay);
        }
    });
</script>

</body>
</html>
