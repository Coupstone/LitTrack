<?php require_once('../config.php') ?>
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

    <!-- Site Icon -->
    <link rel="icon" href="images/LitTrack.png" type="image/png"/>

    <script>
        start_loader()
    </script>
</head>

<body class="d-flex align-items-center vh-100">
    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="text-center login-form-gradient shadow-lg">
                    <div class="align-items-center justify-content-center">
                        <img src="../uploads/LitLogo.png" alt="PUP Logo" class="mb-4 img-fluid" style="max-width: 200px;">
                    </div>
                    <div class="text-carbon-grey justify-content-center">
                        <div class="align-items-center justify-content-center">
                            <h4 class="fs-4 mb-0 fw-semibold">Login - Admin</h4>
                        </div>
                        <div class="mb-4 font-14">Sign in to your account to get started.</div>
                    </div>

                    <form id="login-frm" action="" method="post" class="needs-validation" novalidate>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control text-carbon-grey shadow-sm" id="username" name="username" placeholder="Username" required autofocus>
                            <label for="username" class="fw-medium text-carbon-grey font-13">Username<span style="color: red;"> *</span></label>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback text-start font-13">
                                Please enter a valid username.
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control text-carbon-grey shadow-sm" id="password" name="password" placeholder="Password" required>
                            <label for="password" class="fw-medium text-carbon-grey font-13">Password<span style="color: red;"> *</span></label>
                            <div class="valid-feedback"></div>
                            <div class="invalid-feedback text-start font-13">
                                Please enter your password.
                            </div>
                        </div>
                        <div class="justify-content-center d-md-flex mt-4 mb-2">
                            <div class="input-group">
                                <button type="submit" class="btn col-12 btn-primary py-3 font-14" style="background-color: #5875B5; color: white;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-arrow-right-circle-fill pe-1" viewBox="0 0 16 16">
                                        <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                    </svg>
                                    Sign In
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function() {
            end_loader();
        });
    </script>
</body>

</html>
