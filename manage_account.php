<?php 
check_login();
$user = $conn->query("SELECT s.*,d.name as department, c.name as curriculum,CONCAT(lastname,', ',firstname,' ',middlename) as fullname FROM student_list s inner join department_list d on s.department_id = d.id inner join curriculum_list c on s.curriculum_id = c.id where s.id ='{$_settings->userdata('id')}'");
foreach($user->fetch_array() as $k =>$v){
    $$k = $v;
}
?>
<style>
        .student-img {
            object-fit: scale-down;
            object-position: center center;
            height: 200px;
            width: 200px;
            border-radius: 50%; /* Make the image circular */
        }
        body {
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
    font-weight: var(--bs-body-font-weight);
    line-height: var(--bs-body-line-height);
    color: var(--bs-body-color);
    text-align: var(--bs-body-text-align);
    background-color: var(--bs-body-bg);
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    
        }
        .content {

            margin-top: 10px;
            transform: translateY(-3%); /* Moves the container up by 10% of its height */
        }

            #title-label,
    #abstract-label,
    #pdf-label {
        font-weight: normal; /* Ensures text is not bold */
    }
            #abstract {
        min-height: 150px; /* Set a minimum height for the textarea */
        width: 100%; /* Ensure it takes up all available width within its container */
    }

.container {
    max-width: 2000px; /* Adjust width as necessary */
    width: 103%; /* Use full width for smaller screens */
    margin: 10px; /* Reduced margin around the container */
    padding: 10px; /* Reduced padding inside the container for a compact look */
    transform: translateY(-10%); /* Moves the container up by 10% of its height */
}
    .form-control, .form-control:focus {
        border-color: #ced4da; /* Consistent with the design */
        box-shadow: none; /* No focus shadow */
    }
    .form-floating {
        margin-bottom: 16px; /* Space between fields */
    }
    .card {
        border-radius: 0; /* Flat design */

    }

    /* Smaller button styles */
    .btn {
        padding: 0.375rem 0.75rem; /* Reduced padding */
        font-size: 0.875rem; /* Smaller font size */
        line-height: 1.5; /* Standard line height */
    }
    .author-row .form-control {
        margin-right: 15px; /* Adds space to the right of each input field except the last in the row */
    }
    .author-row .form-control:last-child {
        margin-right: 0; /* Ensures the last input in the row does not have extra space on the right */
    }
    .author-row {
        display: flex; /* Ensures the input fields are aligned in a row */
        align-items: center; /* Aligns items vertically */
        margin-bottom: 15px; /* Adds space below each author row for better separation */
    }
    #pdf-label {
        display: block; /* Ensures the label takes up the full width and behaves like a block element */
        margin-bottom: 8px; /* Adds some space below the label before the input field */
        font-weight: normal; /* Keeps the label text normal, non-bold */
    }
    .form-control {
        display: block;
        width: 100%; /* Ensures the input takes full width of its container */
        padding: 0.375rem 0.75rem; /* Standard padding for Bootstrap form controls */
        font-size: 1rem; /* Standard font size for input text */
        line-height: 1.5; /* Standard line height for readability */
        color: #495057; /* Default text color */
        background-color: #fff; /* White background */
        background-clip: padding-box; /* Ensures background extends to the borders */
        border: 1px solid #ced4da; /* Standard border styling */
        border-radius: 0.25rem; /* Rounded borders for aesthetics */
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; /* Smooth transition for focus effects */
    }
    .btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .form-label {
        font-weight:bold; /* Ensures the label text is not bold */
        display: block;
        margin-bottom: 0.5rem;
    }
    .optional-text {
        font-size: 0.875rem; /* Slightly smaller than button text */
        color: #6c757d; /* Muted text color for secondary information */
        margin-left: 2px; /* Space between button and text */
        vertical-align: middle; /* Align text vertically with the button */
    }
    .card-tools .btn.bg-primary {
    background-color: #5DA8F9 !important; /* Lighter shade of blue */
    border-color: #5DA8F9 !important; /* Match the border */
    color: white !important; /* Ensure text remains white */
}

.card-tools .btn.bg-navy {
    background-color: #1E3E66 !important; /* Lighter shade of navy */
    border-color: #1E3E66 !important; /* Match the border */
    color: white !important; /* Ensure text remains white */
}

/* Button hover effect */
.card-tools .btn.bg-primary:hover,
.card-tools .btn.bg-navy:hover {
    filter: brightness(1.1) !important; /* Brighten on hover */
}
.card-header{
    background-color: #fff;
}
.card-title{
    font-weight: bold;
    transform: translateY(20%); /* Moves the container up by 10% of its height */
}
</style>
<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update or Upload Research</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    </body>
<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0 ">
        <div class="card-header rounded-0" style="background-color: white">
            <h5 class="card-title">Update Details</h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="update-form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $_settings->userdata('id') ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="firstname" class="control-label text-navy">First Name</label>
                                <input type="text" name="firstname" id="firstname" autofocus placeholder="Firstname" class="form-control form-control-border" value="<?= isset($firstname) ?$firstname : "" ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="middlename" class="control-label text-navy">Middle Name</label>
                                <input type="text" name="middlename" id="middlename" placeholder="Middlename (optional)" class="form-control form-control-border" value="<?= isset($middlename) ?$middlename : "" ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="lastname" class="control-label text-navy">Last Name</label>
                                <input type="text" name="lastname" id="lastname" placeholder="Lastname" class="form-control form-control-border" value="<?= isset($lastname) ?$lastname : "" ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-auto">
                        <label for="" class="control-label text-navy">Gender</label>
                        </div>
                        <div class="form-group col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderMale" name="gender" value="Male" required  <?= isset($gender) && $gender == "Male" ? "checked" : "" ?>>
                                <label for="genderMale" class="custom-control-label" style="font-weight: normal">Male</label>
                            </div>
                        </div>
                        <div class="form-group col-auto">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="genderFemale" name="gender" value="Female" <?= isset($gender) && $gender == "Female" ? "checked" : "" ?>>
                                <label for="genderFemale" class="custom-control-label" style="font-weight: normal">Female</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="email" class="control-label text-navy">Email</label>
                                <input type="email" name="email" id="email" placeholder="Email" class="form-control form-control-border" required value="<?= isset($email) ?$email : "" ?>">
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label text-navy">New Password</label>
                                <input type="password" name="password" id="password" placeholder="Password" class="form-control form-control-border">
                            </div>

                            <div class="form-group">
                                <label for="cpassword" class="control-label text-navy">Confirm New Password</label>
                                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" class="form-control form-control-border">
                            </div>
                            <small class='text-muted'>Leave the New Password and Confirm New Password Blank if you don't wish to change your password.</small>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="img" class="control-label text-muted">Choose Image</label>
                                <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))">
                            </div>

                            <div class="form-group text-center">
                                <img src="<?= validate_image(isset($avatar) ? $avatar : "") ?>" alt="My Avatar" id="cimg" class="img-fluid student-img bg-gradient-dark border">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="oldpassword">Please Enter your Current Password</label>
                                <input type="password" name="oldpassword" id="oldpassword" placeholder="Current Password" class="form-control form-control-border" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default bg-blue btn-flat"> Update</button>
                                <a href="./?page=profile" class="btn btn-light border btn-flat"> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
    }

    $(function(){
        $('#update-form').submit(function(e) {
    e.preventDefault();
    var _this = $(this);

    // Debugging: Check password and confirm password fields
    console.log("Password: ", $('#password').val());
    console.log("Confirm Password: ", $('#cpassword').val());

    $(".pop-msg").remove();
    $('#update-form .is-invalid').removeClass("is-invalid"); // Clear previous validation errors

    let isValid = true;

    // Check required fields
    $('#update-form input[required]').each(function() {
        if ($(this).val().trim() === '') {
            $(this).addClass('is-invalid'); // Highlight the invalid field
            isValid = false;
        }
    });

    if (!isValid) {
        var el = $("<div>");
        el.addClass("alert pop-msg my-2 alert-danger");
        el.text("Please fill in all required fields.");
        _this.prepend(el);
        el.show('slow');
        return; // Stop form submission if validation fails
    }

    start_loader();

    $.ajax({
        url: _base_url_ + "classes/Users.php?f=save_student",
        data: new FormData(_this[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        dataType: 'json',
        error: function(err) {
            console.log("AJAX error:", err);
            var el = $("<div>");
            el.addClass("alert pop-msg my-2 alert-danger");
            el.text("An error occurred while saving the data. Please try again.");
            _this.prepend(el);
            el.show('slow');
            end_loader();
        },
        success: function(resp) {
            console.log("AJAX response:", resp); // Debug response

            var el = $("<div>");
            el.addClass("alert pop-msg my-2");
            el.hide();

            if (resp.status === 'success') {
                location.href = "./?page=profile";
            } else if (resp.msg) {
                el.text(resp.msg);
                el.addClass("alert-danger");
                _this.prepend(el);
                el.show('slow');
            } else {
                el.text("An error occurred while saving the data.");
                el.addClass("alert-danger");
                _this.prepend(el);
                el.show('slow');
            }
            end_loader();
            $('html, body').animate({scrollTop: 0}, 'fast');
        }
    });
});
});
</script>
