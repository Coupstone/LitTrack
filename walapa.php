<?php
// Include the database connection and necessary files
$page = isset($_GET['page']) ? $_GET['page'] : 'advance-search';
require_once('./config.php');
require_once('inc/topBarNav.php');
require_once('inc/header.php');
// Define base URL
$base_url = "http://localhost/LitTrack/"; // Update this if your URL is different
// Start HTML output
echo "
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
            background-color: #fff;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            position: relative;
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
            color: #666;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            margin-bottom: 10px;
        }
        .stats {
            display: flex;
            justify-content: flex-end;
            font-size: 12px;
            color: #999;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
        .stats div {
            margin-left: 10px;
        }
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
        }
        #search-form {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        #search-input, .btn-primary {
            height: 38px;
            border-radius: 4px;
        }
        #search-input {
            border: 1px solid #ced4da;
            width: 50%;
            padding: 8px 12px;
            margin-right: 10px;
        }
.card-title {
    font-size: 22px; /* Increase from the previous size, adjust based on preference */
    color: #333; /* Dark color for better readability */
    transform: translateY(90%); /* Moves the container up by 10% of its height */
    margin-left: 5px
}
        .archive-item {
            position: relative;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .pagination-wrapper {
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            height: 100%;
            padding: 10px;
        }
        .table-container {
    transform: translateY(20%); /* Moves the container up by 10% of its height */
        }
        .table-container {
            margin-top: 50px; /* Adjust the value as needed */
        }
    </style>
";

// Main Content
echo "
<div class='table-container'>
    <div class='card card-outline card-primary shadow'>
        <div class='card-header bg-white'>
            <h3 class='card-title text-center text-dark header-title'><b>Advanced Search</b></h3>
            <form id='search-form' class='d-flex' action='search_results.php' method='GET'>
                <a href='advance-search.php' class='btn btn-primary ms-2'><i class='fa fa-arrow-left'></i> Back</a>
            </form>
            <hr class='bg-navy'>
            <div class='list-group'>
                <div class='table-container'>
                    <!-- Your table content goes here -->
                </div>
";

// Retrieve search inputs
$searchTitle = isset($_GET['title']) ? trim($_GET['title']) : '';
$searchAuthor = isset($_GET['author']) ? trim($_GET['author']) : '';
$yearFrom = isset($_GET['year_from']) ? trim($_GET['year_from']) : '';
$yearTo = isset($_GET['year_to']) ? trim($_GET['year_to']) : '';
$searchKeyword = isset($_GET['topic_keyword']) ? trim($_GET['topic_keyword']) : '';
$selectedCourse = isset($_GET['Select_Course']) ? intval($_GET['Select_Course']) : 0;
// Initialize an empty results array
$results = [];
// Check if a curriculum_id filter has been set for course filtering
if ($selectedCourse > 0) {
    // Modify the query to include a filter for matching curriculum_id
    $sql = "SELECT * FROM archive_list WHERE curriculum_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selectedCourse);
    $stmt->execute();
    $result = $stmt->get_result();
    // Fetch all matching rows
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}
// Proceed with the rest of your filtering for keywords, title, author, etc.
// Step 1: Search for keywords in lda_topics table if provided
$paper_ids = [];
if (!empty($searchKeyword)) {
    $keywords = preg_split('/[\s,]+/', $searchKeyword);
    $query = "SELECT paper_id FROM lda_topics WHERE ";
    $keywordConditions = [];
    $params = [];
    foreach ($keywords as $keyword) {
        $keywordConditions[] = "topic_keywords LIKE ?";
        $params[] = "%" . $keyword . "%";
    }
    $query .= implode(" OR ", $keywordConditions);
    $stmt = $conn->prepare($query);
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $lda_result = $stmt->get_result();
    while ($row = $lda_result->fetch_assoc()) {
        $paper_ids[] = $row['paper_id'];
    }
    $stmt->close();
}
// If no keywords matched in lda_topics, show the "No Results Found" modal
if (!empty($searchKeyword) && empty($paper_ids)) {
    echo "<p>No Results Found</p>";
} else {
    // Step 2: Build the query for archive_list using direct filters and, if available, paper_ids
    $query = "SELECT archive_list.id, archive_list.title, archive_list.abstract, 
    GROUP_CONCAT(DISTINCT CONCAT(archive_authors.first_name, ' ', archive_authors.last_name) SEPARATOR ', ') AS authors, 
    archive_list.year,
    (SELECT COUNT(*) FROM archive_citation WHERE archive_id = archive_list.id) AS citation_count
    FROM archive_list
    LEFT JOIN archive_authors ON archive_list.id = archive_authors.archive_id
    LEFT JOIN lda_topics lt ON archive_list.id = lt.paper_id
    WHERE 1=1";

    $params = [];
    $types = "";
    // Only add paper_ids filter if there are matches from the keyword search
    if (!empty($paper_ids)) {
        $placeholders = implode(',', array_fill(0, count($paper_ids), '?'));
        $query .= " AND archive_list.id IN ($placeholders)";
        $types .= str_repeat("i", count($paper_ids));
        $params = array_merge($params, $paper_ids);
    }
    // Apply course filtering
    if ($selectedCourse > 0) {
        $query .= " AND archive_list.curriculum_id = ?";
        $params[] = $selectedCourse;
        $types .= "i";
    }
    // Apply other filters (title, author, year)
    if (!empty($searchTitle)) {
        $query .= " AND archive_list.title LIKE ?";
        $params[] = "%" . $searchTitle . "%";
        $types .= "s";

    }
    if (!empty($searchAuthor)) {
        $query .= " AND CONCAT(archive_authors.first_name, ' ', archive_authors.last_name) LIKE ?";
        $params[] = "%" . $searchAuthor . "%";
        $types .= "s";
    }
    if (!empty($yearFrom) && !empty($yearTo)) {
        $query .= " AND archive_list.year BETWEEN ? AND ?";
        $params[] = $yearFrom;
        $params[] = $yearTo;
        $types .= "ss";
    } elseif (!empty($yearFrom)) {
        $query .= " AND archive_list.year >= ?";
        $params[] = $yearFrom;
        $types .= "s";
    } elseif (!empty($yearTo)) {
        $query .= " AND archive_list.year <= ?";
        $params[] = $yearTo;
        $types .= "s";
    }
    // Group by archive_list.id to consolidate authors for each paper
    $query .= " GROUP BY archive_list.id ORDER BY UNIX_TIMESTAMP(archive_list.date_created) DESC";
    // Apply pagination
    if (isset($limit) && isset($offset)) {
        $query .= " LIMIT {$limit} OFFSET {$offset}";
    }
    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = htmlspecialchars($row['title'] ?? 'No Title Available');
            $abstract = html_entity_decode($row['abstract'] ?? 'No Abstract Available');
            $members = htmlspecialchars($row['authors'] ?? 'No Authors Available');
            $year = htmlspecialchars($row['year'] ?? 'N/A');
            $id = $row['id'];
            $citation_count = $row['citations'] ?? 0;
            // Track reads (increased view count)
            // $stmt = $conn->prepare("INSERT INTO archive_reads (archive_id) VALUES (?)");
            // $stmt->bind_param("i", $id);
            // $stmt->execute();
            // Fetch read count
            $stmt = $conn->prepare("SELECT COUNT(*) AS read_count FROM archive_reads WHERE archive_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $read_result = $stmt->get_result();
            $read_data = $read_result->fetch_assoc();
            $read_count = $read_data['read_count'];
            // Fetch download count
            $stmt = $conn->prepare("SELECT COUNT(*) AS download_count FROM archive_downloads WHERE archive_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $download_result = $stmt->get_result();
            $download_data = $download_result->fetch_assoc();
            $download_count = $download_data['download_count'];
            

            

            echo "
                <div class='archive-item' data-id='{$id}'>
                    <a href='{$base_url}view_archive.php?id={$id}' class='list-group-item'>
                        <h5 class='mb-1 title'><b>{$title}</b></h5>
                        <div class='authors'>By: " . (!empty($members) ? $members : "N/A") . "</div>
                        <p class='mb-1 details'>" . strip_tags($abstract) . "</p>
                        <div class='stats'>
                            <div>Reads: {$read_count}</div>
                            <div>Downloads: {$download_count}</div>
                            
                        </div>
                    </a>
                </div>";
        }
    } else {
        echo "
        <div class='table-container' style='display: flex; justify-content: center; align-items: center; height: 70vh; background-color: #f8f9fa;'>
            <div class='no-results-box' style='text-align: center; background-color: #ffffff; padding: 50px 70px; border-radius: 25px; box-shadow: 0 40px 80px rgba(0, 0, 0, 0.2); opacity: 0; animation: fadeInUp 1s ease-out forwards; transform: translateY(50px);'>
                <p style='color: #636e72; font-size: 20px; font-family: Arial, sans-serif; line-height: 1.7; font-weight: 400; max-width: 400px; margin: 0 auto;'>We couldn't find any matches for your search. Please try again.</p>
                <a href='http://localhost/LitTrack/advance-search.php' style='margin-top: 30px; display: inline-block; padding: 12px 30px; background-color: #ff6b6b; color: white; font-size: 16px; border-radius: 30px; text-decoration: none; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease, transform 0.3s ease;'>Refine Search</a>
            </div>
        </div>
        <style>
            @keyframes fadeInUp {
                0% {
                    opacity: 0;
                    transform: translateY(50px);
                }
                100% {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            @keyframes bounce {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-20px);
                }
            }
            .no-results-box a:hover {
                background-color: #ff4d4d;
                transform: translateY(-3px);
            }
        </style>
        ";
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
</head>
<body>
</body>
</html>