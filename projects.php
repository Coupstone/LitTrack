<?php
require_once('config.php');
check_login();  // Database connection

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
           (SELECT COUNT(*) FROM citation_relationships cr WHERE cr.cited_paper_id = a.id) AS citations,
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
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
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
    
        }
        .list-group-item {
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 220px;
            overflow: hidden;
            text-decoration: none;
            color: black;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
        }
        .title {
            font-size: 22px;
            color: #333;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .authors {
            font-size: 14px;
            color: #007bff;
            margin-bottom: 6px;
        }
        .details {
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
        }
        .stats {
    align-self: flex-end; /* Aligns stats to the right (flex-end of the flex container) */
    margin-top: auto; /* Pushes it to the bottom */
    font-size: 12px;
    color: #666;
}


        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            
        }
        #search-form {
            margin-top: -10px;
        }
        #search-input {
            width: 250px;
        }
        #search-form {
            
            display: flex;
            justify-content: right;
            margin-top: 20px;
            margin-bottom: 10px
        }
        #search-input, .btn-primary {
            height: 38px;
            border-radius: 4px;
        }
        #search-input {
            border: 1px solid #ced4da;
            width: 50%;
            padding: 8px 12px;
        }

.card-title {
    font-size: 22px; /* Increase from the previous size, adjust based on preference */
    color: #333; /* Dark color for better readability */
    transform: translateY(90%); /* Moves the container up by 10% of its height */
    margin-left: 5px
}
.star-btn-wrapper {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 100; /* Make sure it's on top and clickable */
}

.star-btn {
    color: gray; /* Default state */
    cursor: pointer;
}

.star-btn.red {
    color: red; /* Favorite state */
}
.archive-item {
    position: relative;

    margin-bottom: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Ensures space distribution */
}

/* Style for the pagination container */
.pagination-wrapper {
    display: flex;
    justify-content: flex-end; /* Align pagination to the right */
    align-items: flex-end; /* Align pagination to the bottom */
    height: 100%; /* Full height of the container */
    padding: 10px; /* Optional padding */
}

    </style>
</head>
<body>

<div class="wrapper" class="container-fluid content-container" class="card card-outline card-primary shadow" class="card-header bg-white">
        <div class="card card-outline card-primary shadow" class="card-header bg-white">
    <div class="card-header bg-white">
        <h3 class="card-title text-center text-dark header-title"><b>Research List</b></h3>
                    <form id="search-form" class="d-flex" action="search_results.php" method="GET">
                        <input type="search" id="search-input" class="form-control rounded-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>">
                        <button type="submit" class="btn btn-primary ms-2"><i class="fa fa-search"></i></button>
                    </form>
                    <hr class="bg-navy">
                    <div class="list-group">
                    <?php foreach ($archive_data as $id => $row): ?>
    <div class="archive-item" data-id="<?= $id ?>">
        <div class="star-btn-wrapper">
            <i class="fas fa-star star-btn <?= $row['is_favorite'] ? 'red' : '' ?>" data-id="<?= $row['id'] ?>"></i>
        </div>
        <a href="view_archive.php?id=<?= $row['id'] ?>&uuid=<?= $row['uuid'] ?>" class="list-group-item">
                    <h5 class="mb-1 title"><b><?= $row['title'] ?></b></h5>
                    <div class="authors">By: <?= !empty($row['authors']) ? implode(', ', $row['authors']) : "N/A" ?></div>
                    <p class="mb-1 details"><?= strip_tags(html_entity_decode($row['abstract'])) ?></p>
                    <div class="stats">
                        <small>Views: <?= $row['reads'] ?></small>
                        <small>Downloads: <?= $row['downloads'] ?></small>
                        <small>Citations: <?= $row['citations'] ?></small>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
            <div class="pagination-wrapper">
    <ul class="pagination pagination-sm m-0">
        <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page > 1 ? './?page=projects' . $isSearch . '&p=' . ($page - 1) : '#' ?>">«</a></li>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="./?page=projects<?= $isSearch ?>&p=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor; ?>
        <li class="page-item <?= $page == $pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page < $pages ? './?page=projects' . $isSearch . '&p=' . ($page + 1) : '#' ?>">»</a></li>
    </ul>
</div>
</body>
</html>

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
            var uuid = $(this).closest('.archive-item').data('uuid');
            window.location.href = './?page=view_archive&id=' + id + '&uuid=' + uuid;
        });
    });
</script>

</body>
</html>