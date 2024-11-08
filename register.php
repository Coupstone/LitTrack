<?php require_once('./config.php') ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
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

<body>
<div class="container h-100 d-flex justify-content-center align-items-center mt-5" id="login">
    <div class="card card-outline card-primary shadow-lg col-lg-6 col-md-8 col-sm-10">
        <div class="card-header bg-white">
            <h5 class="card-title text-center text-dark mt-3"><b>Registration</b></h5>
        </div>
        <div class="card-body">
            <!-- Message container inside the form container -->
            <div id="message-container" class="alert d-none" role="alert"></div>

            <form action="" id="registration-form" class="needs-validation" novalidate>
                <input type="hidden" name="id">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="firstname" id="firstname" placeholder="Firstname" class="form-control shadow-sm" required>
                            <label for="firstname" class="fw-medium text-carbon-grey font-13">Firstname<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="middlename" id="middlename" placeholder="Middlename (optional)" class="form-control shadow-sm">
                            <label for="middlename" class="fw-medium text-carbon-grey font-13">Middlename (optional)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control shadow-sm" required>
                            <label for="lastname" class="fw-medium text-carbon-grey font-13">Lastname<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="genderMale" name="gender" value="Male" required checked>
                            <label for="genderMale" class="form-check-label text-carbon-grey font-13">Male</label>
                        </div>
                    </div>
                    <div class="form-group col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="genderFemale" name="gender" value="Female">
                            <label for="genderFemale" class="form-check-label text-carbon-grey font-13">Female</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-floating mb-3">
                            <select name="department_id" id="department_id" class="form-select shadow-sm" required>
                                <option value="" disabled selected>Select Department</option>
                                <?php 
                                $department = $conn->query("SELECT * FROM `department_list` where status = 1 order by `name` asc");
                                while($row = $department->fetch_assoc()):
                                ?>
                                <option value="<?= $row['id'] ?>"><?= ucwords($row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                            <label for="department_id" class="fw-medium text-carbon-grey font-13">Department<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-floating mb-3">
                            <select name="curriculum_id" id="curriculum_id" class="form-select shadow-sm" required>
                                <option value="" disabled selected>Select Curriculum</option>
                                <?php 
                                $curriculum = $conn->query("SELECT * FROM `curriculum_list` where status = 1 order by `name` asc");
                                $cur_arr = [];
                                while($row = $curriculum->fetch_assoc()){
                                    $row['name'] = ucwords($row['name']);
                                    $cur_arr[$row['department_id']][] = $row;
                                }
                                ?>
                            </select>
                            <label for="curriculum_id" class="fw-medium text-carbon-grey font-13">Curriculum<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" placeholder="Email" class="form-control shadow-sm" required>
                            <label for="email" class="fw-medium text-carbon-grey font-13">Email<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-floating mb-3">
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control shadow-sm" required>
                            <label for="password" class="fw-medium text-carbon-grey font-13">Password<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-floating mb-3">
                            <input type="password" id="cpassword" placeholder="Confirm Password" class="form-control shadow-sm" required>
                            <label for="cpassword" class="fw-medium text-carbon-grey font-13">Confirm Password<span style="color: red;"> *</span></label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-end">
                            <button class="btn btn-primary py-2 px-4 font-14" style="background-color: #5875B5; color: white;">Register</button>
                        </div>
                    </div>
                </div>
                
            </form>
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
    var cur_arr = $.parseJSON('<?= json_encode($cur_arr) ?>');
    $(document).ready(function(){
        end_loader();
        $('.select2').select2({
            width: "100%"
        });
        
        $('#department_id').change(function(){
            var did = $(this).val();
            $('#curriculum_id').html("");
            if(!!cur_arr[did]){
                Object.keys(cur_arr[did]).map(k => {
                    var opt = $("<option>")
                        .attr('value', cur_arr[did][k].id)
                        .text(cur_arr[did][k].name);
                    $('#curriculum_id').append(opt);
                });
            }
            $('#curriculum_id').trigger("change");
        });

        // Registration Form Submit
        $('#registration-form').submit(function(e){
            e.preventDefault();
            var _this = $(this);
            $('#message-container').removeClass("alert-danger alert-success d-none").text(""); // Clear previous messages

            if ($("#password").val() !== $("#cpassword").val()) {
                $('#message-container')
                    .addClass("alert alert-danger")
                    .text("Passwords do not match.")
                    .removeClass("d-none");
                return false;
            }

            start_loader();

            $.ajax({
                url: _base_url_ + "classes/Users.php?f=save_student",
                method: 'POST',
                data: {
                    id: _this.find('input[name="id"]').val(),
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    email: $('#email').val(),
                    department_id: $('#department_id').val(),
                    curriculum_id: $('#curriculum_id').val(),
                    password: $('#password').val(),
                    cpassword: $('#cpassword').val(),
                },
                dataType: 'json',
                error: function(err) {
                    console.log("AJAX error:", err);
                    $('#message-container')
                        .addClass("alert alert-danger")
                        .text("An error occurred while saving the data.")
                        .removeClass("d-none");
                    end_loader();
                },
                success: function(resp) {
                    console.log("AJAX response:", resp); // Debug response

                    if (resp.status === 'success') {
                        $('#message-container')
                            .addClass("alert alert-success")
                            .text("Registration successful! Redirecting...")
                            .removeClass("d-none");
                        setTimeout(function() {
                            location.href = "./login.php";
                        }, 2000);
                    } else {
                        $('#message-container')
                            .addClass("alert alert-danger")
                            .text(resp.msg || "An error occurred while saving the data.")
                            .removeClass("d-none");
                    }
                    end_loader();
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                }
            });
        });
    });
</script>
</body>
</html>
