<?php
require_once './config.php';
check_login();

// Retrieve user data, including the avatar image
if (isset($_settings) && method_exists($_settings, 'userdata')) {
    $userId = $_settings->userdata('id');
    $avatar = $_settings->userdata('avatar'); // Assume 'avatar' field contains the image path
} else {
    $userId = null;
    $avatar = 'uploads/default.png'; // Default image if no user is logged in
}

// Handle avatar upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileName = $_FILES['avatar']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $newFileName = $userId . '_avatar.' . $fileExtension;
    $uploadPath = 'uploads/' . $newFileName;

    if (move_uploaded_file($fileTmpPath, $uploadPath)) {
        // Update avatar path in the database
        $conn->query("UPDATE users SET avatar = '$uploadPath' WHERE id = '$userId'");
        $_settings->set_userdata('avatar', $uploadPath); // Update session data
        $avatar = $uploadPath; // Update the avatar variable
    }
}

// Fetch user details from the database
$user = $conn->query("SELECT s.*, d.name as department, c.name as curriculum, CONCAT(lastname, ', ', firstname, ' ', middlename) as fullname FROM student_list s INNER JOIN department_list d ON s.department_id = d.id INNER JOIN curriculum_list c ON s.curriculum_id = c.id WHERE s.id ='{$_settings->userdata('id')}'");
foreach ($user->fetch_array() as $k => $v) {
    $$k = $v;
}
?>
<?php require_once('inc/topBarNav.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
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
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        }
        #content {
            transition: margin-left 0.3s;
        }
        body.sidebar-collapsed #content {
            margin-left: 60px;
        }
        .student-img {
            object-fit: cover;
            object-position: center center;
            height: 150px;
            width: 150px;
            border-radius: 50%;
            border: 3px solid #C4D7F1;
        }
        .card-header {
            background-color: #C4D7F1;
            color: 007bff;
        }
        .card-header h5 {
            margin: 0;
        }
        .card-tools {
    position: absolute; /* Ensures the container can be positioned independently */
    top: 10px; /* Adjusts vertical alignment within the header */
    right: 10px; /* Aligns the container to the far-right corner */
    display: flex; /* Makes the buttons align horizontally */
    gap: 5px; /* Reduces the space between the buttons */
    transform: translateY(-20%); /* Moves the container up by 10% of its height */
    
}
.card-tools a:last-child {
    margin-right: 20px; /* Adds specific spacing for the last button */
}
.card-tools .btn {
    padding: 4px 8px; /* Makes buttons smaller */
    font-size: 12px; /* Adjusts font size for compact design */
    border-radius: 3px; /* Rounds button corners slightly */
    min-width: auto; /* Ensures buttons don't have a minimum width */
    height: auto; /* Ensures button height is dynamic */
}
        .card-body dl dt {

            color: #007bff;
        }
        .card-body dl dd {
            margin-bottom: 15px;
            font-size: 14px;
            color: #333;
        }
        @media (max-width: 768px) {
            body.sidebar-expanded #content {
                margin-left: 0px;
                
            }
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
.card-header h5 {
    font-weight: 500; /* Adjusted to medium weight */
    color:<i class="fa fa-black-tie" aria-hidden="true"></i>; /* Optional: Retain the blue color for consistency */
}
    </style>
</head>
<body class="sidebar-expanded">
<div class="container mt-4 mr-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
            <h5 class="card-title m-0"><b>Student Profile</b></h5>
            <div class="card-tools mt-2 mt-md-0">
                <a href="./?page=my_archives" class="btn btn-default bg-primary btn-flat mb-2 mb-md-0">
                    <i class="fa fa-archive"></i> My Researches
                </a>
                <a href="./?page=manage_account" class="btn btn-default bg-navy btn-flat">
                    <i class="fa fa-edit"></i> Update Account
                </a>
            </div>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center align-items-center">
                    <!-- Image Section -->
                    <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-3">
                        <img src="<?= validate_image(isset($avatar) ? $avatar : 'uploads/default.png') ?>" 
                             alt="Student Image" 
                             class="img-fluid student-img bg-gradient-dark rounded-circle">
                    </div>
                    <!-- Information Section -->
                    <div class="col-lg-8 col-md-6 col-sm-12">
                        <dl class="row">
                            <dt class="col-5 text-primary">Student Name:</dt>
                            <dd class="col-7"><?= ucwords($fullname) ?></dd>
                            <dt class="col-5 text-primary">Gender:</dt>
                            <dd class="col-7"><?= ucwords($gender) ?></dd>
                            <dt class="col-5 text-primary">Email:</dt>
                            <dd class="col-7"><?= $email ?></dd>
                            <dt class="col-5 text-primary">Department:</dt>
                            <dd class="col-7"><?= ucwords($department) ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>