<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <?php require_once('inc/header.php') ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>

    <!-- Bootstrap CSS -->
    <link href="styles/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Site Icon -->
    <link rel="icon" href="uploads/LitTrack.png" type="image/png"/>
</head>

<body class="bg-image">
  
  <!-- Forgot Password Modal -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="forgot_password.php" method="POST">
                <div class="modal-body">
                    <p>Please enter your registered email address.</p>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                        <label for="email">Email Address</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Reset Link</button>
                </div>
            </form>
        </div>
    </div>
  </div>

  <div class="container-fluid position-absolute top-50 start-50 translate-middle">
    <div class="row justify-content-center align-items-center">
      <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
        <div class="text-center login-form-gradient shadow-lg">
          <div class="d-flex align-items-center justify-content-center">
            <!-- Logo -->
            <div>
                <img src="uploads/LitLogo.png" alt="PUP Logo" class="mb-4 img-fluid" style="max-width: 200px;">
            </div>
          </div>
          <div class="text-carbon-grey justify-content-center">
            <div class="mb-4 font-14">Sign in to your account to get started.</div>
          </div>

          <form action="" method="post" id="slogin-form" class="needs-validation" novalidate>
            <?php if (isset($errorMessage)) : ?>
              <div id="serverSideErrorMessage" class="text-danger mb-3 text-center fw-medium font-13">
                <?php echo $errorMessage; ?>
              </div>
            <?php endif; ?>

            <div class="form-floating mb-3"> 
              <input type="email" class="form-control text-carbon-grey shadow-sm" name="email" id="email" placeholder="Email Address" autocomplete="email" required>
              <label for="email" class="fw-medium text-carbon-grey font-13">Email<span style="color: red;"> *</span></label>
              <div class="valid-feedback"></div>
              <div class="invalid-feedback text-start font-13">
                Please enter a valid email.
              </div> 
            </div>
          
            <div class="form-floating">
              <input type="password" class="form-control text-carbon-grey shadow-sm" name="password" id="password" placeholder="Password" autocomplete="current-password" required>
              <label for="password" class="fw-medium text-carbon-grey font-13">Password<span style="color: red;"> *</span></label>          
              <div class="valid-feedback"></div>
              <div class="invalid-feedback text-start font-13">
                Please enter your password.
              </div>
              <div class="input-group mt-2 align-items-center">
                <div class="form-check ms-2">
                  <input class="form-check-input fs-5" type="checkbox" id="showPassword">
                  <label class="form-check-label text-carbon-grey fw-medium pt-1 font-13" for="showPassword">Show</label>
                </div>
                <a href="#" class="text-carbon-grey ms-auto font-13" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
              </div>

              <div class="justify-content-center d-md-flex mt-4 mb-2">
                <button type="submit" name="sign_in_btn" class="btn col-12 btn-primary py-3 font-14" style="background-color: #5875B5; color: white;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-arrow-right-circle-fill pe-1" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                  </svg>
                  Sign In
                </button>
              </div>
              <div class="text-center mt-3">
                <span class="text-carbon-grey font-14">Don't have an account? 
                  <a href="./register.php" class="text-primary fw-semibold" style="text-decoration: underline;">Sign Up</a>
                </span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  
  <!-- Toggle password visibility -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("showPassword").addEventListener("click", function () {
            const passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        });
    });
  </script>

  <!-- AJAX Login Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('slogin-form');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            var emailInput = form.querySelector('input[name="email"]');
            var passwordInput = form.querySelector('input[name="password"]');

            emailInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');

            var isValid = true;

            if (!emailInput.value) {
                emailInput.classList.add('is-invalid');
                isValid = false;
            }
            if (!passwordInput.value) {
                passwordInput.classList.add('is-invalid');
                isValid = false;
            }

            if (isValid) {
                $.ajax({
                    url: _base_url_ + "classes/Login.php?f=student_login",
                    method: 'POST',
                    data: $(form).serialize(),
                    dataType: 'json',
                    error: function () {
                        var errorMsg = $("<div>").addClass("alert alert-danger my-2").text("An error occurred.");
                        $(form).prepend(errorMsg);
                    },
                    success: function (resp) {
                        if (resp.status === 'success') {
                            location.href = "./index.php";
                        } else {
                            var errorMsg = $("<div>").addClass("alert alert-danger my-2").text(resp.msg || "Invalid email or password.");
                            $(form).prepend(errorMsg);
                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        }
                    }
                });
            }
        });
    });
  </script>

</body>
</html>
