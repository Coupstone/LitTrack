<?php
require_once('config.php'); // Database connection
// Fetch research projects
$research_projects = $conn->query("SELECT * FROM archive_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - Research Projects</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        #content {
            transition: margin-left 0.3s;
            margin-left: 250px;
        }
        body.sidebar-collapsed #content {
            margin-left: 50px;
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
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container-fluid content-container">
        <div class="col-12">
            <div class="card card-outline card-primary shadow rounded-0">
                <div class="card-body rounded-0">
                    <div class="header-wrapper">
                        <h2>Research List</h2>
                        <!-- Search Form aligned to the right side -->
                        <form id="search-form" class="d-flex align-items-center" action="search_results.php" method="GET">
                            <input type="search" id="search-input" class="form-control rounded-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <hr class="bg-navy">
                    
                    <?php 
                    $limit = 10;
                    $page = isset($_GET['p']) ? $_GET['p'] : 1; 
                    $offset = 10 * ($page - 1);
                    $paginate = " limit {$limit} offset {$offset}";
                    $isSearch = isset($_GET['q']) ? "&q={$_GET['q']}" : "";
                    $search = "";
                    if(isset($_GET['q'])){
                        $keyword = $conn->real_escape_string($_GET['q']);
                        $search = " and (title LIKE '%{$keyword}%' or abstract LIKE '%{$keyword}%' or members LIKE '%{$keyword}%')";
                    }
                    $students = $conn->query("SELECT * FROM `student_list` where id in (SELECT student_id FROM archive_list where `status` = 1 {$search})");
                    $student_arr = array_column($students->fetch_all(MYSQLI_ASSOC),'email','id');
                    $count_all = $conn->query("SELECT * FROM archive_list where `status` = 1 {$search}")->num_rows;    
                    $pages = ceil($count_all / $limit);
                    $archives = $conn->query("SELECT * FROM archive_list where `status` = 1 {$search} order by unix_timestamp(date_created) desc {$paginate}");    
                    ?>
                    
                    <div class="list-group">
                        <?php while($row = $archives->fetch_assoc()): ?>
                        <div class="archive-item" data-id="<?= $row['id'] ?>">
                            <div class="star-btn-wrapper">
                                <i class="fas fa-star star-btn <?= $row['is_favorite'] ? 'red' : '' ?>" data-id="<?= $row['id'] ?>"></i>
                            </div>
                            <div class="row clickable-row">
                                <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                                    <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img img-fluid" alt="Banner Image">
                                </div>
                                <div class="col-lg-8 col-md-7 col-sm-12">
                                    <h3 class="text-navy"><b><?php echo $row['title'] ?></b></h3>
                                    <small class="text-muted">By <b class="text-info"><?= isset($student_arr[$row['student_id']]) ? $student_arr[$row['student_id']] : "N/A" ?></b></small>
                                    <p class="truncate-5"><?= strip_tags(html_entity_decode($row['abstract'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="card-footer clearfix rounded-0">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6"><span class="text-muted">Display Items: <?= $archives->num_rows ?></span></div>
                            <div class="col-md-6">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item"><a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $page - 1 ?>" <?= $page == 1 ? 'disabled' : '' ?>>«</a></li>
                                    <?php for($i = 1; $i <= $pages; $i++): ?>
                                    <li class="page-item"><a class="page-link <?= $page == $i ? 'active' : '' ?>" href="./?page=projects<?= $isSearch ?>&p=<?= $i ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>
                                    <li class="page-item"><a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $page + 1 ?>" <?= $page == $pages ? 'disabled' : '' ?>>»</a></li>
                                </ul>
                            </div>
                        </div>
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
            var star = $(this).find('.star-btn');
            var id = star.data('id');
            star.toggleClass('red');
            $.ajax({
                url: 'save_favorite.php',
                method: 'POST',
                data: { archive_id: id, favorite: star.hasClass('red') ? 1 : 0 },
                success: function(response) {
                    console.log('Favorite status updated');
                },
                error: function(xhr, status, error) {
                    console.log('An error occurred: ' + error);
                }
            });
        });
        $('.clickable-row').on('click', function() {
            var id = $(this).closest('.archive-item').data('id');
            window.location.href = './?page=view_archive&id=' + id;
        });
    });
</script>

</body>
</html>
