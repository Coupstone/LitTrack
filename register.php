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
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                        <input type="text" name="student_number" id="student_number" 
                                class="form-control shadow-sm" 
                                maxlength="15" 
                                placeholder="2023-00123-SR-0" 
                                pattern="^20[0-9]{2}-00[0-9]{3}-SR-0$" 
                                title="Student number must follow the format 20XX-00XXX-SR-0, where X is a digit." 
                                required>
                            <label for="student_number" class="fw-medium text-carbon-grey font-13">
                                Student Number<span style="color: red;"> *</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" name="student_number" id="student_number" 
                                class="form-control shadow-sm" 
                                maxlength="15" 
                                placeholder="2021-00306-SR-0" 
                                pattern="^202[0-9]-00[0-9]{3}-SR-[0-9]$" 
                                title="Student number must follow the format 202X-00XXX-SR-0, where X is a digit." 
                                required>
                            <label for="student_number" class="fw-medium text-carbon-grey font-13">
                                Student Number (202X-00XXX-SR-0)<span style="color: red;"> *</span>
                            </label>
                        </div>
                    </div>
                </div> -->

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
                            <input type="file" name="cor" id="cor" class="form-control shadow-sm" accept=".pdf" required>
                            <label for="cor" class="fw-medium text-carbon-grey font-13">Certificate of Registration (PDF only)<span style="color: red;"> *</span></label>
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
                        <button class="btn btn-primary py-2 px-4 font-14" id="register-btn" style="background-color: #5875B5; color: white;" disabled>
    Register
</button>
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
    $(document).ready(function () {
        end_loader();

        // Initialize Select2 for dropdowns
        $('.select2').select2({ width: "100%" });

        // Dynamically load curriculum based on selected department
        $('#department_id').change(function () {
            var did = $(this).val();
            $('#curriculum_id').html("");
            if (cur_arr[did]) {
                cur_arr[did].forEach(function (curriculum) {
                    $('#curriculum_id').append(
                        $("<option>").val(curriculum.id).text(curriculum.name)
                    );
                });
            }
            $('#curriculum_id').trigger("change");
        });

        // File validation on file attachment
        $('#cor').on('change', function () {
            var corFile = this.files[0]; // Get the file object

            if (!corFile) {
                Swal.fire({
                    icon: 'error',
                    title: 'No file selected',
                    text: 'Please upload a Certificate of Registration.',
                });
                return;
            }

            var fileExtension = corFile.name.split('.').pop().toLowerCase();
            var allowedExtensions = ['pdf'];
            var mimeType = corFile.type;

            if (!allowedExtensions.includes(fileExtension) || mimeType !== 'application/pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid file format',
                    text: 'Only PDF files are allowed for the Certificate of Registration.',
                });

                // Clear the file input
                $(this).val('');
                return;
            }
        });

        // Registration Form Validation for Button Enable/Disable
        const form = document.getElementById("registration-form");
        const registerButton = document.querySelector(".btn-primary");

        const validateForm = () => {
            const inputs = form.querySelectorAll("input[required], select[required]");
            let isValid = true;

            inputs.forEach((input) => {
                if (!input.checkValidity()) {
                    isValid = false;
                }
            });

            return isValid;
        };

        const toggleRegisterButton = () => {
            if (validateForm()) {
                registerButton.disabled = false;
            } else {
                registerButton.disabled = true;
            }
        };

        // Attach input and change event listeners to all required fields
        form.addEventListener("input", toggleRegisterButton);
        form.addEventListener("change", toggleRegisterButton);

        // Disable the button initially
        registerButton.disabled = true;

        // Additional validation for passwords
        const passwordField = document.getElementById("password");
        const confirmPasswordField = document.getElementById("cpassword");

        confirmPasswordField.addEventListener("input", () => {
            if (passwordField.value !== confirmPasswordField.value) {
                confirmPasswordField.setCustomValidity("Passwords do not match.");
            } else {
                confirmPasswordField.setCustomValidity("");
            }
            toggleRegisterButton();
        });

        // Real-Time Email Validation
        const emailField = document.getElementById("email");
        const emailPattern = /^[a-zA-Z0-9._%+-]+@(gmail\.com|iskolarngbayan\.pup\.edu\.ph)$/;

        emailField.addEventListener("blur", () => {
            const errorMessage = document.getElementById("email-error");

            if (!emailPattern.test(emailField.value)) {
                emailField.classList.add("is-invalid");
                if (!errorMessage) {
                    const errorElement = document.createElement("div");
                    errorElement.id = "email-error";
                    errorElement.className = "invalid-feedback";
                    errorElement.textContent = "Invalid email address. Must be @gmail.com or @iskolarngbayan.pup.edu.ph.";
                    emailField.parentNode.appendChild(errorElement);
                }
            } else {
                emailField.classList.remove("is-invalid");
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
            toggleRegisterButton();
        });

        // Real-Time Student Number Validation
        const studentNumberField = document.getElementById("student_number");
        const studentNumberPattern = /^20[0-9]{2}-00[0-9]{3}-SR-0$/;

        studentNumberField.addEventListener("blur", () => {
            const errorMessage = document.getElementById("student-number-error");

            if (!studentNumberPattern.test(studentNumberField.value)) {
                studentNumberField.classList.add("is-invalid");
                if (!errorMessage) {
                    const errorElement = document.createElement("div");
                    errorElement.id = "student-number-error";
                    errorElement.className = "invalid-feedback";
                    errorElement.textContent = "Invalid student number format. Format: 20XX-00XXX-SR-0.";
                    studentNumberField.parentNode.appendChild(errorElement);
                }
            } else {
                studentNumberField.classList.remove("is-invalid");
                if (errorMessage) {
                    errorMessage.remove();
                }
            }
            toggleRegisterButton();
        });

        // Registration Form Submit
        $('#registration-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this);
            $('#message-container').removeClass("alert-danger alert-success d-none").text(""); // Clear previous messages

            var corFileInput = $('#cor')[0];
            var corFile = corFileInput.files[0];

            if (
                !$('#firstname').val().trim() ||
                !$('#lastname').val().trim() ||
                !$('#student_number').val().trim() ||
                !$('#password').val().trim() ||
                !$('#cpassword').val().trim() ||
                !$('#department_id').val() ||
                !$('#curriculum_id').val() ||
                !$('#email').val().trim() ||
                !$('#cor')[0].files.length
            ) {
                $('#message-container')
                    .addClass("alert alert-danger")
                    .text("Please fill in all required fields.")
                    .removeClass("d-none");
                return false;
            }

            var studentNumber = $('#student_number').val().trim();
            if (studentNumber !== "") {
                var studentNumberRegex = /^20[0-9]{2}-00[0-9]{3}-SR-0$/;
                if (!studentNumberRegex.test(studentNumber)) {
                    $('#message-container')
                        .addClass("alert alert-danger")
                        .text("Invalid student number format. Example: 2021-00306-SR-0.")
                        .removeClass("d-none");
                    return false;
                }
            }

            if ($("#password").val() !== $("#cpassword").val()) {
                $('#message-container')
                    .addClass("alert alert-danger")
                    .text("Passwords do not match.")
                    .removeClass("d-none");
                return false;
            }

            start_loader();

            var formData = new FormData();
            formData.append('id', _this.find('input[name="id"]').val());
            formData.append('student_number', studentNumber);
            formData.append('firstname', $('#firstname').val());
            formData.append('lastname', $('#lastname').val());
            formData.append('gender', $('input[name="gender"]:checked').val());
            formData.append('email', $('#email').val());
            formData.append('department_id', $('#department_id').val());
            formData.append('curriculum_id', $('#curriculum_id').val());
            formData.append('password', $('#password').val());
            formData.append('cpassword', $('#cpassword').val());
            formData.append('cor', corFile);

            $.ajax({
                url: _base_url_ + "classes/Users.php?f=save_student",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                error: function (err) {
                    console.log("AJAX error:", err);
                    $('#message-container')
                        .addClass("alert alert-danger")
                        .text("An error occurred while saving the data.")
                        .removeClass("d-none");
                    end_loader();
                },
                success: function (resp) {
                    if (resp.status === 'success') {
                        $('#message-container')
                            .addClass("alert alert-success")
                            .text("Registration successful! Redirecting...")
                            .removeClass("d-none");
                        setTimeout(function () {
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