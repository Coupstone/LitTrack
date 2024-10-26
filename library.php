<?php 
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in by validating session data
if (!isset($_SESSION['userdata']['id']) || empty($_SESSION['userdata']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['userdata']['id'];

// Fetch user's favorite research items
$favorites_query = $conn->prepare("
    SELECT archive_list.* 
    FROM favorites 
    JOIN archive_list ON favorites.research_id = archive_list.id 
    WHERE favorites.user_id = ?");
$favorites_query->bind_param("i", $user_id);
$favorites_query->execute();
$favorites_result = $favorites_query->get_result();
?>
<!DOCTYPE html>

<html lang="en" style="height: auto;">
<head>
    <title>My Library</title>

    <!-- Bootstrap CSS and Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden; 
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

        /* Custom styles for the library content */
        .list-group-item {
            cursor: pointer;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .banner-img {
            object-fit: scale-down;
            height: 200px;
            width: 100%;
        }

        .truncate-5 {
            display: -webkit-box;
            -webkit-line-clamp: 5;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
        <div class="container-fluid py-4">
            <h2>My Favorite Researches</h2>
            <div class="list-group">
                <?php if ($favorites_result->num_rows > 0): ?>
                    <?php while($row = $favorites_result->fetch_assoc()): ?>
                    <a href="./?page=view_archive&id=<?= $row['id'] ?>" class="list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img" alt="Banner Image">
                            </div>
                            <div class="col-md-8">
                                <h4 class="text-navy"><b><?= $row['title'] ?></b></h4>
                                <p class="text-muted">By <?= $row['student_id'] ?></p>
                                <p class="truncate-5"><?= strip_tags($row['abstract']) ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No favorite researches added yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
