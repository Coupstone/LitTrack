<?php
require_once('config.php'); // Database connection

$limit = 10;
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$offset = 10 * ($page - 1);
$paginate = "LIMIT {$limit} OFFSET {$offset}";
$isSearch = isset($_GET['q']) ? "&q={$_GET['q']}" : "";
$search = "";

if (isset($_GET['q'])) {
    $keyword = $conn->real_escape_string($_GET['q']);
    $search = " AND (a.title LIKE '%{$keyword}%' OR a.abstract LIKE '%{$keyword}%')";
}

$count_all = $conn->query("SELECT * FROM archive_list a WHERE a.status = 1 {$search}");
if (!$count_all) {
    echo "Error in count_all query: " . $conn->error;
    exit;
}
$pages = ceil($count_all->num_rows / $limit);

$archives = $conn->query("
    SELECT a.*, 
           (SELECT COUNT(*) FROM archive_reads WHERE archive_id = a.id) AS `reads`,
           (SELECT COUNT(*) FROM archive_downloads WHERE archive_id = a.id) AS downloads,
           (SELECT COUNT(*) FROM archive_citations WHERE archive_id = a.id) AS citations
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
        }
        .content-container {
            margin-top: 20px;
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
        .archive-item {
    position: relative;
    padding: 15px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ensures space distribution */
}

.stats {
    display: flex;
    justify-content: flex-end; /* Aligns stats to the right */
    padding: 10px 0; /* Padding on top for spacing, none on the right */
    font-size: 0.75rem; /* Smaller font size */
}

.stats span {
    display: flex; /* Makes the icon and text align better */
    align-items: center; /* Centers the items vertically */
    margin-left: 9px; /* Increases spacing between different stats types */
    font-weight: bold;
    color: black; /* Text color remains black */
}

.fa-eye, .fa-download, .fa-quote-left {
    margin-right: 4px; /* Reduces space between the icon and the number */
    color: #696969; /* Dark grey color for icons */
    font-size: 0.70rem; /* Smaller icons */
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
                        <form id="search-form" class="d-flex align-items-center" action="search_results.php" method="GET">
                            <input type="search" id="search-input" class="form-control rounded-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                            <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <hr class="bg-navy">
                    
                    <div class="list-group">
    <?php foreach ($archive_data as $id => $row): ?>
    <div class="archive-item" data-id="<?= $id ?>">
        <div class="star-btn-wrapper">
            <i class="fas fa-star star-btn <?= $row['is_favorite'] ? 'red' : '' ?>" data-id="<?= $id ?>"></i>
        </div>
        <div class="row clickable-row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h3 class="text-navy"><b><?= $row['title'] ?></b></h3>
                <small class="text-muted">By: <b class="text-info"><?= !empty($row['authors']) ? implode(', ', $row['authors']) : "N/A" ?></b></small>
                <p class="truncate-5"><?= strip_tags(html_entity_decode($row['abstract'])) ?></p>
            </div>
        </div>
        <!-- Stats display at the bottom right corner of the archive item -->
        <div class="stats">
            <span><i class="fa fa-eye"></i> <?= $row['reads'] ?></span>
            <span><i class="fa fa-download"></i> <?= $row['downloads'] ?></span>
            <span><i class="fa fa-quote-left"></i> <?= $row['citations'] ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</div>
                </div>
                <div class="card-footer clearfix rounded-0">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6"><span class="text-muted">Display Items: <?= count($archive_data) ?></span></div>
                            <div class="col-md-6">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page > 1 ? './?page=projects' . $isSearch . '&p=' . ($page - 1) : '#' ?>">«</a></li>
                                <?php for ($i = 1; $i <= $pages; $i++): ?>
                                    <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $i ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page == $pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page < $pages ? './?page=projects' . $isSearch . '&p=' . ($page + 1) : '#' ?>">»</a></li>
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
