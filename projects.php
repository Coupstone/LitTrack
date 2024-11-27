<?php
require_once('config.php'); // Database connection

$limit = 10;
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$offset = $limit * ($page - 1);
$paginate = "LIMIT {$limit} OFFSET {$offset}";
$isSearch = isset($_GET['q']) ? "&q=" . urlencode($_GET['q']) : "";
$search = "";

if (isset($_GET['q'])) {
    $keyword = $conn->real_escape_string($_GET['q']);
    $search = " AND (
        a.title LIKE '%{$keyword}%' OR 
        a.abstract LIKE '%{$keyword}%' OR 
        a.year LIKE '%{$keyword}%' OR 
        EXISTS (
            SELECT 1 
            FROM archive_authors au 
            WHERE au.archive_id = a.id 
            AND (
                au.first_name LIKE '%{$keyword}%' OR 
                au.last_name LIKE '%{$keyword}%' OR 
                CONCAT(au.first_name, ' ', au.last_name) LIKE '%{$keyword}%'
            )
        )
    )";
}

// Count total items for pagination
$count_all = $conn->query("SELECT COUNT(*) AS total FROM archive_list a WHERE a.status = 1 {$search}");
if (!$count_all) {
    echo "Error in count_all query: " . $conn->error;
    exit;
}
$total_items = $count_all->fetch_assoc()['total'];
$pages = ceil($total_items / $limit);

// Fetch archive list with favorite status
$archives = $conn->query("
    SELECT a.*, 
           (SELECT COUNT(*) FROM archive_reads WHERE archive_id = a.id) AS `reads`,
           (SELECT COUNT(*) FROM archive_downloads WHERE archive_id = a.id) AS downloads,
           (SELECT COUNT(*) FROM archive_citation WHERE archive_id = a.id) AS citations,
           (SELECT COUNT(*) FROM favorites WHERE student_id = {$_SESSION['student_id']} AND archive_id = a.id) AS is_favorite
    FROM archive_list a
    WHERE a.status = 1 {$search}
    ORDER BY UNIX_TIMESTAMP(a.date_created) DESC 
    {$paginate}
");


if (!$archives) {
    echo "Error in fetching archives: " . $conn->error;
    exit;
}

$archive_data = [];
while ($row = $archives->fetch_assoc()) {
    $archive_data[$row['id']] = $row;
    $archive_data[$row['id']]['authors'] = []; // Placeholder for authors
}

// Fetch authors for each archive
$archive_ids = implode(',', array_keys($archive_data));
if ($archive_ids) {
    $authors = $conn->query("
        SELECT au.archive_id, CONCAT(au.first_name, ' ', au.last_name) AS full_name
        FROM archive_authors au
        WHERE au.archive_id IN ({$archive_ids})
        ORDER BY au.author_order
    ");

    if (!$authors) {
        echo "Error in fetching authors: " . $conn->error;
        exit;
    }

    while ($author = $authors->fetch_assoc()) {
        $archive_data[$author['archive_id']]['authors'][] = $author['full_name'];
    }
}
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
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .stats {
            display: flex;
            justify-content: flex-end;
            padding: 10px 0;
            font-size: 0.75rem;
        }
        .stats span {
            display: flex;
            align-items: center;
            margin-left: 9px;
            font-weight: bold;
            color: black;
        }
        .fa-eye, .fa-download, .fa-quote-left {
            margin-right: 4px;
            color: #696969;
            font-size: 0.70rem;
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
                        <form id="search-form" class="d-flex align-items-center" action="projects.php" method="GET">
                            <input type="search" id="search-input" class="form-control rounded-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <hr class="bg-navy">
                    
                    <!-- Display Search Header if Query Exists -->
                    <?php if (isset($_GET['q'])): ?>
                        <div class="search-header mb-3">
                            <p>Search Results for: <strong><?= htmlspecialchars($_GET['q']) ?></strong></p>
                        </div>
                    <?php endif; ?>

                    <div class="list-group">
                        <!-- Iterate Through Archive Data -->
                        <?php foreach ($archive_data as $id => $row): ?>
                            <div class="archive-item" data-id="<?= $id ?>">
                                <div class="star-btn-wrapper">
                                    <i class="fas fa-star star-btn <?= $row['is_favorite'] ? 'red' : '' ?>" data-id="<?= $id ?>"></i>
                                </div>
                                <div class="row clickable-row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <h3 class="text-navy"><b><?= htmlspecialchars($row['title']) ?></b></h3>
                                        <small class="text-muted">By: <b class="text-info"><?= !empty($row['authors']) ? implode(', ', $row['authors']) : "N/A" ?></b></small>
                                        <p class="truncate-5"><?= htmlspecialchars(strip_tags(html_entity_decode($row['abstract']))) ?></p>
                                    </div>
                                </div>
                                <div class="stats">
                                    <span><i class="fa fa-eye"></i> <?= $row['reads'] ?></span>
                                    <span><i class="fa fa-download"></i> <?= $row['downloads'] ?></span>
                                    <span><i class="fa fa-quote-left"></i> <?= $row['citations'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Handle Empty Search Results -->
                        <?php if (empty($archive_data)): ?>
                            <div class="text-center text-muted">
                                <p>No research projects found for the search term <strong><?= htmlspecialchars($_GET['q'] ?? '') ?></strong>.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="card-footer clearfix rounded-0">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6"><span class="text-muted">Display Items: <?= count($archive_data) ?></span></div>
                            <div class="col-md-6">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $page > 1 ? 'projects.php?p=' . ($page - 1) . $isSearch : '#' ?>">«</a>
                                    </li>
                                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                            <a class="page-link" href="projects.php?p=<?= $i . $isSearch ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= $page == $pages ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= $page < $pages ? 'projects.php?p=' . ($page + 1) . $isSearch : '#' ?>">»</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Pagination -->
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.star-btn-wrapper').on('click', function(e) {
            e.stopPropagation();
            var star = $(this).find('.star-btn');
            var id = star.data('id');
            star.toggleClass('red');

            // AJAX request to toggle favorite
            $.ajax({
                url: 'save_favorite.php',
                method: 'POST',
                data: { archive_id: id, favorite: star.hasClass('red') ? 1 : 0 },
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.status === 'success') {
                        console.log('Favorite status updated');
                    } else {
                        alert('Error: ' + res.message);
                    }
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
