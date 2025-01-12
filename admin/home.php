<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

.interactive-box {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.3s, box-shadow 0.3s;
}

.interactive-box:hover {
    transform: scale(1.02); 
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); }

.info-box {
    height: 120px;
    display: flex;
    align-items: center;
    background-color: #ffffff; 
    font-family: 'Roboto', sans-serif; 
}
.info-box-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 60px; 
    height: 60px; 
    border-radius: 8px; 
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
    font-family: 'Roboto', sans-serif; 
    font-size: 2em; 
    margin-bottom: 20px; 
    color: #333; 
}


.department-box {
    background-color: #e0f7fa; 
}

.curriculum-box {
    background-color: #f3e5f5; 
}

.verified-students-box {
    background-color: #c8e6c9; 
}

.not-verified-students-box {
    background-color: #fff9c4; 
}

.verified-archives-box {
    background-color: #c8e6c9; 
}

.not-verified-archives-box {
    background-color: #f5f5f5; }
</style>
<!-- Include SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>
    <link href="dist/css/style.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<body>
<hr class="border-info">
<div class="row">
    <!-- Department List Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box department-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-info text-white square-icon p-3">
                <i class="fas fa-building fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Department List</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM department_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Curriculum List Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box curriculum-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-gradient-dark text-white square-icon p-3">
                <i class="fas fa-book-open fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Curriculum List</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM curriculum_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Verified Students Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box verified-students-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-primary text-white square-icon p-3">
                <i class="fas fa-user-check fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Verified Students</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM student_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Not Verified Students Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box not-verified-students-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-warning text-dark square-icon p-3">
                <i class="fas fa-user-times fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Not Verified Students</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM student_list WHERE status = 0")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Verified Archives Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box verified-archives-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-success text-white square-icon p-3">
                <i class="fas fa-archive fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Verified Archives</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM archive_list WHERE status = 1")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Not Verified Archives Box -->
    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
        <a href="#" class="info-box not-verified-archives-box rounded p-2 mb-3 interactive-box">
            <span class="info-box-icon bg-dark text-white square-icon p-3">
                <i class="fas fa-box fa-lg"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Not Verified Archives</span>
                <span class="info-box-number text-right font-weight-bold">
                    <?php echo $conn->query("SELECT * FROM archive_list WHERE status = 0")->num_rows; ?>
                </span>
            </div>
        </a>
    </div>
</div>
</body>
</html>