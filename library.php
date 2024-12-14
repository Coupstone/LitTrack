<?php
require_once('config.php'); 

$limit = 10;
$page = isset($_GET['p']) ? $_GET['p'] : 1;
$offset = 10 * ($page - 1);
$paginate = "LIMIT {$limit} OFFSET {$offset}";

$count_all = $conn->query("SELECT * FROM archive_list a WHERE a.is_favorite = 1");
if (!$count_all) {
    echo "Error in count_all query: " . $conn->error;
    exit;
}
$pages = ceil($count_all->num_rows / $limit);

$favorites_query = $conn->query("SELECT a.*, 
           (SELECT COUNT(*) FROM archive_reads WHERE archive_id = a.id) AS `reads`,
           (SELECT COUNT(*) FROM archive_downloads WHERE archive_id = a.id) AS downloads,
           (SELECT COUNT(*) FROM archive_citation WHERE archive_id = a.id) AS citations
    FROM archive_list a
    WHERE a.is_favorite = 1
    ORDER BY UNIX_TIMESTAMP(a.date_created) DESC 
    {$paginate}
");

if (!$favorites_query) {
    echo "Error in fetching favorites: " . $conn->error;
    exit;
}

$favorites_data = [];
while ($row = $favorites_query->fetch_assoc()) {
    $favorites_data[$row['id']] = $row;
    $favorites_data[$row['id']]['authors'] = []; // Placeholder for authors
}

$archive_ids = implode(',', array_keys($favorites_data));
if ($archive_ids) {
    $authors = $conn->query("SELECT au.archive_id, CONCAT(au.first_name, ' ', au.last_name) AS full_name
        FROM archive_authors au
        WHERE au.archive_id IN ({$archive_ids})
        ORDER BY au.author_order
    ");

    if (!$authors) {
        echo "Error in fetching authors: " . $conn->error;
        exit;
    }

    while ($author = $authors->fetch_assoc()) {
        $favorites_data[$author['archive_id']]['authors'][] = $author['full_name'];
    }

    if (!isset($_SESSION['student_id'])) {
        die("Student is not logged in.");
    }
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
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
/* General body styling */
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
    transform: translateX(-0%); /* Moves the container up by 10% of its height */
    transform: translateY(-30%); /* Moves the container up by 10% of its height */
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
.card-header {
margin-top: 32px;

}
</style>

</head>
<body>
<!-- Single container for the content -->
<div class="wrapper container-fluid content-container card card-outline card-primary shadow">
        <div class="card-header bg-white">
            <h3 class="card-title text-center text-dark header-title"><b>Favorites</b></h3>
            <form id="search-form" class="d-flex" action="favorites_search.php" method="GET">
            </form>
            <hr class="bg-navy">
    </div>
    
    <!-- List group for displaying favorite research items -->
    <div class="list-group">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="archive-item" data-id="<?= $row['id'] ?>">
                    <div class="star-btn-wrapper">
                        <i class="fas fa-star star-btn red" data-id="<?= $row['id'] ?>"></i>
                    </div>
                    <a href="view_archive.php?id=<?= $row['id'] ?>" class="list-group-item">
                        <h5 class="mb-1 title"><b><?= htmlspecialchars($row['title']) ?></b></h5>
                        <!-- <div class="authors">By: <?= !empty($favorites_data[$row['id']]['authors']) ? implode(', ', $favorites_data[$row['id']]['authors']) : "N/A" ?></div> -->
                    <p class="mb-1 details"><?= strip_tags(html_entity_decode($row['abstract'])) ?></p>
                            <div class="stats">
                                <small>Views: <?= $row['reads'] ?></small>
                                <small>Downloads: <?= $row['downloads'] ?></small>
                                <small>Citations: <?= $row['citations'] ?></small>
                            </div>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No favorite research projects yet!</p>
        <?php endif; ?>
    </div>
    
    <!-- Pagination section -->
    <div class="pagination-wrapper">
        <ul class="pagination pagination-sm m-0">
            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page > 1 ? './?page=library' . $isSearch . '&p=' . ($page - 1) : '#' ?>">«</a></li>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="./?page=library<?= $isSearch ?>&p=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
            <li class="page-item <?= $page == $pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $page < $pages ? './?page=library' . $isSearch . '&p=' . ($page + 1) : '#' ?>">»</a></li>
        </ul>
    </div>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
        $('.star-btn-wrapper').on('click', function(e) {
            e.stopPropagation();
            const star = $(this).find('.star-btn');
            const archiveId = star.data('id');

            // Automatically remove the favorite without confirmation
            $.ajax({
                url: 'save_favorite.php',
                method: 'POST',
                data: { archive_id: archiveId, favorite: 0 },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            window.location.reload(true); // Reload the page to reflect the change
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText);
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