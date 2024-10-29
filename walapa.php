<?php
// Include the database connection
include 'config.php';
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 

// Define base URL
$base_url = "http://localhost/LitTrack/"; // Update this if your URL is different

// Start HTML output
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Advanced Search Results</title>

    <!-- Include Bootstrap Icons -->
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css'>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f9f9f9;
            overflow: hidden;
        }

        .header {
            display: none; 
        }

        .content {
            padding: 30px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 1200px;
            background-color: #ffffff; 
            box-shadow: none; 
            height: calc(100vh - 60px);
            overflow: hidden;
        }

        .scrollable-table {
            height: 100%;
            overflow-y: auto;
            padding-right: 15px;
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff; 
            border: none; 
            box-shadow: none; 
            margin-bottom: 20px;
        }

        .card-body {
            padding: 20px;
            background-color: #ffffff; 
        }

        .btn-flat {
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-navy {
            background-color: #a8001d; 
            color: #ffffff;
            border: none;
        }

        .btn-navy:hover {
            background-color: #80001a; 
        }

        .text-info {
            color: #17a2b8;
        }

        .text-navy {
            color: #003366;
        }

        .border {
            border: 2px solid #a8001d;
        }

        .bg-gradient-dark {
            background-color: #ffffff; 
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .fieldset {
            margin-bottom: 20px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 20px;
            background-color: #ffffff; 
            box-shadow: none; 
        }

        .legend {
            font-size: 1.25rem;
            font-weight: bold;
            color: #a8001d; 
            border-bottom: 2px solid #80001a;
            padding-bottom: 8px;
            margin-bottom: 12px;
            margin-top: 40px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #e1e1e1;
            text-align: left;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .table-row {
            transition: background-color 0.3s;
        }

        .table-row:hover {
            background-color: #f9f9f9;
        }

        /* Style for No Results Found */
        .no-results {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            background-color: #f9f9f9;
            font-size: 1.5rem;
            color: #a8001d;
            font-weight: bold;
            text-align: center;
        }

        .no-results .icon {
            font-size: 4rem;
            color: #a8001d;
            margin-bottom: 15px;
        }

        .no-results p {
            font-size: 1.25rem;
            color: #333333;
        }
    </style>
</head>
<body>";

// Retrieve search inputs
$searchTitle = isset($_GET['title']) ? trim($_GET['title']) : '';
$searchAuthor = isset($_GET['author']) ? trim($_GET['author']) : '';
$yearFrom = isset($_GET['year_from']) ? trim($_GET['year_from']) : '';
$yearTo = isset($_GET['year_to']) ? trim($_GET['year_to']) : '';
$searchCurriculum = isset($_GET['curriculum_id']) ? trim($_GET['curriculum_id']) : '';

// Build the SQL query dynamically based on the inputs
$query = "SELECT * FROM archive_list WHERE 1=1";

if (!empty($searchTitle)) {
    $query .= " AND (title LIKE '%" . $conn->real_escape_string($searchTitle) . "%')";
}

if (!empty($searchAuthor)) {
    $query .= " AND (members LIKE '%" . $conn->real_escape_string($searchAuthor) . "%')";
}

if (!empty($yearFrom) && !empty($yearTo)) {
    $query .= " AND (year BETWEEN '" . $conn->real_escape_string($yearFrom) . "' AND '" . $conn->real_escape_string($yearTo) . "')";
} elseif (!empty($yearFrom)) {
    $query .= " AND (year >= '" . $conn->real_escape_string($yearFrom) . "')";
} elseif (!empty($yearTo)) {
    $query .= " AND (year <= '" . $conn->real_escape_string($yearTo) . "')";
}

if (!empty($searchCurriculum)) {
    $query .= " AND (curriculum_id = '" . $conn->real_escape_string($searchCurriculum) . "')";
}

// Execute the query
$result = $conn->query($query);

// Check for results and format the display based on the second code style
if ($result && $result->num_rows > 0) {
    echo "<div class='container-fluid content'>
            <div class='scrollable-table'>";
    while ($row = $result->fetch_assoc()) {
        $archiveCode = htmlspecialchars($row['archive_code'] ?? '');
        $title = htmlspecialchars($row['title'] ?? 'No Title Available');
        $year = htmlspecialchars($row['year'] ?? '----');
        $abstract = html_entity_decode($row['abstract'] ?? 'No Abstract Available');
        $members = html_entity_decode($row['members'] ?? 'No Authors Available');
        $bannerPath = validate_image($row['banner_path'] ?? '');
        $documentPath = htmlspecialchars($row['document_path'] ?? '');
        $dateCreated = htmlspecialchars($row['date_created'] ?? '');

        echo "
            <div class='card-body rounded-0'>
                <div class='container-fluid'>
                    <h2 class='font-weight-bold'>{$title}</h2>
                    <small class='text-muted'>Submitted on " . ($dateCreated ? date("F d, Y h:i A", strtotime($dateCreated)) : "Date not available") . "</small>
                    <hr>
                    <div class='text-center'>
                        <img src='{$bannerPath}' alt='Banner Image' class='img-fluid border bg-gradient-dark'>
                    </div>
                    <fieldset class='fieldset mt-4'>
                        <legend class='legend'>Project Year:</legend>
                        <div class='pl-4'><large>{$year}</large></div>
                    </fieldset>
                    <fieldset class='fieldset'>
                        <legend class='legend'>Abstract:</legend>
                        <div class='pl-4'><large>{$abstract}</large></div>
                    </fieldset>
                    <fieldset class='fieldset'>
                        <legend class='legend'>Authors:</legend>
                        <div class='pl-4'><large>{$members}</large></div>
                    </fieldset>
                    <fieldset class='fieldset'>
                        <legend class='legend'>Project Document:</legend>
                        <div class='pl-4'>
                            <p>Document: <strong>{$documentPath}</strong></p>
                            <div class='doc-controls'>
                                <button onclick='downloadDocument(\"" . $base_url . $documentPath . "\")' class='btn btn-flat btn-navy'><i class='bi bi-download'></i> Download</button>
                                <a href='" . $base_url . $documentPath . "' target='_blank' class='btn btn-flat btn-navy'><i class='bi bi-eye'></i> View</a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>";
    }
    echo "</div></div>";
} else {
    echo "<div class='no-results'>
            <div class='icon'><i class='bi bi-emoji-frown'></i></div>
            <p>No results found.</p>
          </div>";
}

echo "<script>
    function downloadDocument(url) {
        window.location.href = url;
    }
</script>
</body>
</html>";
?>
