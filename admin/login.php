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

    <!-- CDN jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->

    <!-- Site Icon -->
    <link rel="icon" href="images/LitTrack.png" type="image/png"/>

    
  <script>
        start_loader()
  </script>
    
</head>

<body class="d-flex align-items-center vh-100">
    <div class="container-fluid position-absolute top-50 start-50 translate-middle">
        <div class="row justify-content-center align-items-center">
            <div class="ccol-sm-10 col-md-7 col-lg-5 col-xl-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="text-danger" >Login - Admin</h4>
                    </div>
                    <div class="card-body">
                        <form id="login-frm" action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" autofocus>
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                            </div>
                            <div class="">
                              <div class="col">
                                  <div for="submitForm" class="justify-content-center d-md-flex mt-4 mb-2">
                                      <div class="input-group">
                                          <button type="submit" class="btn col-12 py-3 font-14" style="background-color: #810100; color: white;">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#fff" class="bi bi-arrow-right-circle-fill pe-1" viewBox="0 0 16 16">
                                                  <path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
                                              </svg>
                                              Sign In
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <div class="row-4 text-center mt-3">
                                  <span class="text-carbon-grey font-14"> 
                                      <a href="<?php echo base_url ?>./choices.php" class="text-primary fw-semibold" style="text-decoration: underline;">Go to Back</a>
                                  </span>
                              </div>
                            </div>
                        </form>
                    </div>
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

    <script>
        $(document).ready(function() {
            end_loader();
        });
    </script>
</body>

</html>
