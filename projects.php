<?php require_once('inc/topBarNav.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Sidebar with Favorite Button</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css"> <!-- Bootstrap Icons CDN -->
    <style>
       #content {
            transition: margin-left 0.3s;
            margin-left: 250px; 
        }

        body.sidebar-collapsed #content {
            margin-left: 60px;
        }

        .student-img {
            object-fit: scale-down;
            object-position: center center;
            height: 200px;
            width: 200px;
        }

        .card-tools .btn {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            body.sidebar-expanded #content {
                margin-left: 80px; /
            }
        }
        
        .favorite-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .favorite-btn .bi-star {
            color: gray; 
        }

        .favorite-btn .bi-star-fill {
            color: gold; 
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="search-bar mt-4 w-100 d-flex flex-column align-items-end px-3">
        <form id="search-form" class="d-flex w-100 flex-column flex-md-row align-items-center justify-content-end" action="search_results.php" method="GET">
            <input type="search" id="search-input" class="form-control rounded-0 mb-2 mb-md-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>" style="width: 100%; max-width: 400px;">
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </form>
        <div class="mt-2"></div>
    </div>
    
    <!-- Page Content -->
    <div id="content" class="content py-2">
        <div class="container-fluid">
            <!-- Research List -->
            <div class="col-12">
                <div class="card card-outline card-primary shadow rounded-0">
                    <div class="card-body rounded-0">
                        <h2>Research List</h2>
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
                            $search = " and (title LIKE '%{$keyword}%' or abstract LIKE '%{$keyword}%' or members LIKE '%{$keyword}%' or curriculum_id in (SELECT id from curriculum_list where name LIKE '%{$keyword}%' or description LIKE '%{$keyword}%') or curriculum_id in (SELECT id from curriculum_list where department_id in (SELECT id FROM department_list where name LIKE '%{$keyword}%' or description LIKE '%{$keyword}%'))) ";
                        }
                        $students = $conn->query("SELECT * FROM `student_list` where id in (SELECT student_id FROM archive_list where `status` = 1 {$search})");
                        $student_arr = array_column($students->fetch_all(MYSQLI_ASSOC),'email','id');
                        $count_all = $conn->query("SELECT * FROM archive_list where `status` = 1 {$search}")->num_rows;    
                        $pages = ceil($count_all / $limit);
                        $archives = $conn->query("SELECT * FROM archive_list where `status` = 1 {$search} order by unix_timestamp(date_created) desc {$paginate}");    
                        ?>
                        <?php if(!empty($isSearch)): ?>
                        <h3 class="text-center"><b>Search Result for "<?= $keyword ?>" keyword</b></h3>
                        <?php endif ?>
                        <div class="list-group">
                            <?php 
                            while($row = $archives->fetch_assoc()):
                                $row['abstract'] = strip_tags(html_entity_decode($row['abstract']));
                            ?>
                            <a href="./?page=view_archive&id=<?= $row['id'] ?>" class="text-decoration-none text-dark list-group-item list-group-item-action">
                                <div class="row">
                                    <div class="col-lg-4 col-md-5 col-sm-12 text-center">
                                        <img src="<?= validate_image($row['banner_path']) ?>" class="banner-img img-fluid bg-gradient-dark" alt="Banner Image">
                                    </div>
                                    <div class="col-lg-8 col-md-7 col-sm-12 d-flex justify-content-between align-items-start">
                                        <div>
                                            <h3 class="text-navy"><b><?php echo $row['title'] ?></b></h3>
                                            <small class="text-muted">By <b class="text-info"><?= isset($student_arr[$row['student_id']]) ? $student_arr[$row['student_id']] : "N/A" ?></b></small>
                                            <p class="truncate-5"><?= $row['abstract'] ?></p>
                                        </div>
                                        <button class="btn favorite-btn" data-id="<?= $row['id'] ?>">
                                            <i class="bi bi-star"></i>
                                        </button>
                                    </div>
                                </div>
                            </a>
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
</div>


<!-- JavaScript to handle favorite toggle -->
<script>
$('.favorite-btn').click(function(e) {
    e.preventDefault();

    var button = $(this);
    var researchId = button.data('id');

    // Debug to check if button click is triggering
    console.log("Clicked favorite for research ID:", researchId);

    // Toggle icon classes for star
    var icon = button.find('i');
    if (icon.hasClass('bi-star')) {
        icon.removeClass('bi-star').addClass('bi-star-fill');
    } else {
        icon.removeClass('bi-star-fill').addClass('bi-star');
    }

    $.post('toggle_favorite.php', { research_id: researchId }, function(response) {
        console.log("Server response:", response);
        var res = JSON.parse(response);
        if (res.status !== 'added' && res.status !== 'removed') {
            alert('Failed to update favorites.');
        }
    });
});

</script>

</body>
</html>
