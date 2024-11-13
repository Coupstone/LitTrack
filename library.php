<?php
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 

// Fetch only favorite research projects
$favorites = $conn->query("SELECT * FROM archive_list WHERE is_favorite = 1");

// Fetch student information for display
$students = $conn->query("SELECT * FROM student_list WHERE id IN (SELECT student_id FROM archive_list WHERE is_favorite = 1)");
$student_arr = array_column($students->fetch_all(MYSQLI_ASSOC), 'email', 'id');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - Favorite Research</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
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
        .banner-img {
            object-fit: cover;
            width: 100%;
            height: 150px;
        }
        .content-container {
            margin-top: 20px; /* Adjusted to raise the table slightly */
        }
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
        }
        .header-wrapper .form-control {
            max-width: 300px;
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

<!-- Main Content -->
<div id="content" class="content py-2">
    <div class="container-fluid content-container">
        <div class="col-12">
            <div class="card card-outline card-primary shadow rounded-0">
                <div class="card-body rounded-0">
                    <h2>My Library - Favorite Researches</h2>
                    <hr class="bg-navy">
                    <div class="list-group">
                        <?php if ($favorites->num_rows > 0): ?>
                            <?php while($row = $favorites->fetch_assoc()): ?>
                                <div class="archive-item" data-id="<?= $row['id'] ?>">
                                    <div class="star-btn-wrapper">
                                        <i class="fas fa-star star-btn <?= $row['is_favorite'] ? 'red' : '' ?>" data-id="<?= $row['id'] ?>"></i>
                                    </div>
                                    <div class="row clickable-row">
                                        <!-- <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                                            <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img img-fluid" alt="Banner Image">
                                        </div> -->
                                        <div class="col-lg-8 col-md-7 col-sm-12">
                                            <h3 class="text-navy"><b><?= $row['title'] ?></b></h3>
                                            <small class="text-muted">By <b class="text-info"><?= isset($student_arr[$row['student_id']]) ? $student_arr[$row['student_id']] : "N/A" ?></b></small>
                                            <p class="truncate-5 text-truncate"><?= strip_tags(html_entity_decode($row['abstract'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No favorite research projects yet!</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-footer clearfix rounded-0">
                    <!-- Pagination can be added here if needed -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle star button click
        $('.star-btn-wrapper').on('click', function(e) {
            e.stopPropagation(); // Prevent the row click event from firing
            var star = $(this).find('.star-btn');
            var id = star.data('id');
            var archiveItem = star.closest('.archive-item');

            // Toggle the red class (favorite state)
            star.toggleClass('red');

            // Send AJAX request to save the state (Favorite or not)
            $.ajax({
                url: 'save_favorite.php',
                method: 'POST',
                data: { archive_id: id, favorite: star.hasClass('red') ? 1 : 0 },
                success: function(response) {
                    if (!star.hasClass('red')) {
                        archiveItem.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                    console.log('Favorite status updated');
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred: ' + error);
                }
            });
        });

        // Handle row click for navigation
        $('.clickable-row').on('click', function() {
            var id = $(this).closest('.archive-item').data('id');
            window.location.href = './?page=view_archive&id=' + id;
        });
    });
</script>

</body>
</html>
