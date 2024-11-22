<?php
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 

if (!isset($_SESSION['student_id'])) {
    die("Student is not logged in.");
}

$student_id = $_SESSION['student_id'];

// Fetch only favorite research projects for the logged-in student
$favorites = $conn->prepare("
    SELECT a.*, f.created_at AS favorite_date 
    FROM archive_list a 
    JOIN favorites f ON a.id = f.archive_id 
    WHERE f.student_id = ?
");
$favorites->bind_param("i", $student_id);
$favorites->execute();
$result = $favorites->get_result();

if ($result === false) {
    die("Error fetching favorites: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - Favorite Research</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        #content {
            transition: margin-left 0.3s;
            margin-left: 250px;
            overflow-y: auto;
            height: 100vh; 
        }
        body.sidebar-collapsed #content {
            margin-left: 100px;
        }
        .star-btn-wrapper {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            z-index: 10;
        }
        .star-btn {
            color: gray;
            font-size: 24px;
        }
        .star-btn.red {
            color: red;
        }
        .archive-item {
            position: relative;
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .content-container {
            margin-top: 20px;
        }
         /* Sidebar styling */
         .sidebar {
            overflow: hidden; /* Hide scrollbar */
            /* other styling like width, height, background color, etc. */
        }
        .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            transition: width 0.3s ease-in-out; 
            overflow-y: auto; 
            overflow-x: hidden; 
            background-color: white;
        }
        .main-sidebar::-webkit-scrollbar {
            display: none;
        }
        .main-sidebar {
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
        body.sidebar-collapsed .main-sidebar {
            width: 70px;
        }
        .main-sidebar .nav-link p {
            display: inline;
        }
        body.sidebar-collapsed .main-sidebar .nav-link p {
            display: none;
        }
        .main-sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 10px;
        }
        body.sidebar-collapsed .main-sidebar .nav-link i {
            text-align: center;
            margin-right: 0;
            width: 100%;
        }
        .content-wrapper {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
            height: 100%;
            overflow: hidden;
        }
        body.sidebar-collapsed .content-wrapper {
            margin-left: 60px;
        }
        .brand-link {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            transition: padding 0.3s ease;
            height: 3.5rem;
            overflow: hidden;
        }
        .brand-link .brand-image {
            width: 2.5rem;
            height: 2.5rem;
            transition: width 0.3s ease, height 0.3s ease;
            margin-right: 0.5rem;
        }
        body.sidebar-collapsed .brand-link .brand-image {
            width: 2rem;
            height: 2rem;
            margin-right: 0; 
        }
        .brand-link .brand-text {
            font-size: 1rem;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }
        body.sidebar-collapsed .brand-link .brand-text {
            opacity: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div id="content" class="content py-2">
    <div class="container-fluid content-container">
        <div class="col-12">
            <div class="card card-outline card-primary shadow rounded-0">
                <div class="card-body rounded-0">
                    <h2>My Library - Favorite Researches</h2>
                    <hr class="bg-navy">
                    <div class="list-group">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="archive-item" data-id="<?= $row['id'] ?>">
                                    <div class="star-btn-wrapper">
                                        <i class="fas fa-star star-btn red" data-id="<?= $row['id'] ?>"></i>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-7 col-sm-12">
                                            <h3 class="text-navy"><b><?= htmlspecialchars($row['title']) ?></b></h3>
                                            <small class="text-muted">Added on: <b><?= htmlspecialchars($row['favorite_date']) ?></b></small>
                                            <p><?= htmlspecialchars(strip_tags($row['abstract'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No favorite research projects yet!</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.star-btn-wrapper').on('click', function(e) {
            e.stopPropagation();
            const star = $(this).find('.star-btn');
            const archiveId = star.data('id');

            if (confirm("Are you sure you want to remove this item from your favorites?")) {
                $.ajax({
                    url: 'save_favorite.php',
                    method: 'POST',
                    data: { archive_id: archiveId, favorite: 0 },
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.status === 'success') {
                                window.location.reload(true); // Reload the page
                            }
                        } catch (error) {
                            // Silent failure, no alert
                            console.error('Error parsing response:', error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Silent failure, no alert
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }
        });
    });
</script>

</body>
</html>
