<html lang="en" class="" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/css/styles.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <!-- jQuery (Optional for Bootstrap 5) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap 5 Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

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
            background-color: #fff;
            color: #333;
            padding-left: 100px;
            min-height: 100vh;
        }

        .interactive-box {
            display: block;
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .interactive-box:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .info-box {
            height: 120px;
            display: flex;
            align-items: center;
            background-color: #fff;
            border: 2px solid #800000;
            border-radius: 10px;
            font-family: 'Roboto', sans-serif;
        }

        .info-box-icon {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background-color: #800000;
            color: #fff;
        }

        .square-icon {
            border-radius: 8px;
        }

        .info-box-content {
            padding-left: 15px;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .welcome-title {
            font-size: 2em;
            margin-bottom: 20px;
            color: #800000;
        }
        .info-box-number {
    color: maroon;
}


        .department-box {
            background-color: #ffe6e6;
        }

        .curriculum-box {
            background-color: #fbe9e7;
        }

        .verified-students-box {
            background-color: #f8e1e1;
        }

        .not-verified-students-box {
            background-color: #fff2f2;
        }

        .verified-archives-box {
            background-color: #f9ebeb;
        }

        .not-verified-archives-box {
            background-color: #fdf2f2;
        }

        hr {
            border: 1px solid #800000;
        }
        .interactive-box {
    pointer-events: none; /* Disable clicking */
}
    </style>
</head>
<body>
    <h1 class="welcome-title">Welcome ADMIN!</h1>
    <hr>
    <div class="row">
        <!-- Department List Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="department_list.php" class="info-box department-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-building fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Department List:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM department_list WHERE status = 1")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Curriculum List Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="curriculum_list.php" class="info-box curriculum-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-book-open fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Curriculum List:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM curriculum_list WHERE status = 1")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Verified Students Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="verified_students.php" class="info-box verified-students-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-user-check fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Verified Students:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM student_list WHERE status = 1")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Not Verified Students Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="not_verified_students.php" class="info-box not-verified-students-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-user-times fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Not Verified Students:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM student_list WHERE status = 0")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Verified Archives Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="verified_archives.php" class="info-box verified-archives-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-archive fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Verified Archives:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM archive_list WHERE status = 1")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Not Verified Archives Box -->
        <div class="col-12 col-sm-12 col-md-6 col-lg-4">
            <a href="not_verified_archives.php" class="info-box not-verified-archives-box rounded p-2 mb-3 interactive-box">
                <span class="info-box-icon">
                    <i class="fas fa-box fa-lg"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text font-weight-bold">Not Verified Archives:</span>
                    <span class="info-box-number text-right font-weight-bold">
                        <?php echo $conn->query("SELECT * FROM archive_list WHERE status = 0")->num_rows; ?>
                    </span>
                </div>
            </a>
        </div>
    </div>
</body>
</html>
