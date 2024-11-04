<?php 
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        #content {
            transition: margin-left 0.3s;
        }
        body.sidebar-collapsed #content {
            margin-left: 60px;
        }
        .student-img {
            object-fit: scale-down;
            object-position: center center;
            height: 200px;
            width: 200px;
            border-radius: 50%;
        }
        .card-tools .btn {
            margin-left: 10px;
        }
        @media (max-width: 768px) {
            body.sidebar-expanded #content {
                margin-left: 80px;
            }
        }
    </style>
</head>
<body class="sidebar-expanded">
<div id="content" class="content-container">
    <div class="content py-4">
        <div class="card card-outline card-primary shadow rounded-0">
            <div class="card-header rounded-0">
                <h5 class="card-title">Your Information:</h5>
                <div class="card-tools">
                    <a href="./?page=my_archives" class="btn btn-default bg-primary btn-flat">
                        <i class="fa fa-archive"></i> My Archives
                    </a>
                    <a href="./?page=manage_account" class="btn btn-default bg-navy btn-flat">
                        <i class="fa fa-edit"></i> Update Account
                    </a>
                </div>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <div class="row d-flex justify-content-center align-items-center"> 
                        <div class="col-lg-4 col-sm-12 text-center">
                            <img src="<?= validate_image(isset($avatar) ? $avatar : 'uploads/default.png') ?>" alt="Student Image" class="img-fluid student-img bg-gradient-dark border">
                        </div>
                        <div class="col-lg-8 col-sm-12">
                            <dl>
                                <dt class="text-navy">Student Name:</dt>
                                <dd class="pl-4"><?= ucwords($fullname) ?></dd>
                                <dt class="text-navy">Gender:</dt>
                                <dd class="pl-4"><?= ucwords($gender) ?></dd>
                                <dt class="text-navy">Email:</dt>
                                <dd class="pl-4"><?= $email ?></dd>
                                <dt class="text-navy">Department:</dt>
                                <dd class="pl-4"><?= ucwords($department) ?></dd>
                                <dt class="text-navy">Curriculum:</dt>
                                <dd class="pl-4"><?= ucwords($curriculum) ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
