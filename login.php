<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" style="height: auto;">
 <?php require_once('inc/header.php') ?>
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>

    <!-- Bootstrap CSS -->
    <link href="styles/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- CDN jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->

    <!-- Site Icon -->
    <link rel="icon" href="uploads/LitTrack.png" type="image/png"/>
    
</head>

<body class="bg-image">

        <!-- Forgot Password Modal -->
        <div class="modal fade" id="forgotPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered px-3">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-carbon-grey fw-semibold" id="forgotPasswordModalLabel">Let's recover your account</h5>
            <button type="button" id="closeSendEmailBtn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form class="needs-validation" id="forgotPasswordForm" novalidate>
            <div class="modal-body">
              <p class="px-1 font-14">Please provide your registered email address for sending of reset link.</p>
              <div class="form-floating mb-3">
                <input type="email" class="form-control shadow-sm" name="email" id="email" placeholder="Email Address" autocomplete="email" required>
                <label for="email" class="text-muted font-13">Email Address<span style="color: red;"> *</span></label>
                <div class="valid-feedback font-13" id="email-valid">
                  <!-- Display valid email message -->
                </div>
                <div class="invalid-feedback font-13" id="email-error">
                  <!-- Display error messages -->
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="cancelSendEmailBtn" class="btn fw-medium btn-outline-carbon-grey text-capitalize py-2 px-4 my-3 font-14" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
              <button type="submit" name="send-email-btn" id="sendEmailBtn" class="btn fw-medium btn-mediumtext-capitalize py-2 px-4 font-14" style="background-color: #5875B5; color: white;">Send Link</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  
  <?php if($_settings->chk_flashdata('success')): ?>
    <script>
      alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
    </script>
  <?php endif;?> 
  
  <div class="container-fluid position-absolute top-50 start-50 translate-middle">
    <div class="row justify-content-center align-items-center">
      <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
        <div class="text-center login-form-gradient shadow-lg">
          <div class="d-flex align-items-center justify-content-center">
            <!-- Logo and Title -->
            <div>
                <img src="uploads/LitLogo.png" alt="PUP Logo" class="mb-4 img-fluid" style="max-width: 200px;">
            </div>
          </div>
          <div class="text-carbon-grey justify-content-center">
            <div class="d-flex align-items-center justify-content-center">
              <!-- <div class="fs-4 mb-0 fw-semibold">Welcome!</div>   -->
            </div>
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
                  <input class="form-check-input fs-5" type="checkbox" id="showPassword" onclick="togglePassword()">
                  <label class="form-check-label text-carbon-grey fw-medium pt-1 font-13" for="showPassword">Show</label>
                </div>
                <a href="#" class="text-carbon-grey ms-auto font-13" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
              </div>

              <div for="submitForm" class="justify-content-center d-md-flex mt-4 mb-2">
                <div class="input-group">
                  <button type="submit" name="sign_in_btn" id="submitForm" class="btn col-12 btn-primary py-3 font-14" style="background-color: #5875B5; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-arrow-right-circle-fill pe-1" viewBox="0 0 16 16">
                      <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                    </svg>
                    Sign In
                  </button>
                </div>
              </div>
                <!-- Sign Up link -->
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


  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  <!-- Select2 -->
  <script src="<?php echo base_url ?>plugins/select2/js/select2.full.min.js"></script>
  

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('slogin-form');

    form.addEventListener('submit', function (event) {
        // Prevent the form from submitting by default
        event.preventDefault();

        // Get the input fields
        var emailInput = form.querySelector('input[name="email"]');
        var passwordInput = form.querySelector('input[name="password"]');

        // Reset validation states
        emailInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');

        // Flag to track if the form is valid
        var isValid = true;

        // Check if email is filled
        if (!emailInput.value) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        }

        // Check if password is filled
        if (!passwordInput.value) {
            passwordInput.classList.add('is-invalid');
            isValid = false;
        }

        // If the form is valid, proceed with AJAX submission
        if (isValid) {
            start_loader(); // Assuming you have a loader function
            $.ajax({
                url: _base_url_ + "classes/Login.php?f=student_login",
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                error: function (err) {
                    console.log(err);
                    var el = $("<div>").addClass("alert alert-danger my-2").text("An error occurred while saving the data");
                    $(form).prepend(el);
                    end_loader(); // Assuming you have a loader function
                },
                success: function (resp) {
                    if (resp.status === 'success') {
                        location.href = "./index.php";
                    } else {
                        var el = $("<div>").addClass("alert alert-danger my-2").text(resp.msg || "Invalid email or password.");
                        $(form).prepend(el);
                        end_loader();
                        $('html, body').animate({ scrollTop: 0 }, 'fast');
                    }
                }
            });
        }
    });

    // Handle real-time validation feedback
    form.querySelectorAll('input[required]').forEach(function (input) {
        input.addEventListener('input', function () {
            if (input.value) {
                input.classList.remove('is-invalid');
            }
        });
    });
});

  </script>
    <script>
        // Toggle show password
        function togglePassword() {
        const passwordInput = document.getElementById("password");
        const isPassword = passwordInput.type === "password";
        passwordInput.type = isPassword ? "text" : "password";
        }
    </script>
</body>
</html>
