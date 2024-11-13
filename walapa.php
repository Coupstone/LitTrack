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
        /* Responsive content shift when sidebar is collapsed */
        #content {
            margin-left: 250px;
            padding: 20px;
            overflow-y: auto; /* Enable scrolling for main content */
            height: 100vh;
            transition: margin-left 0.3s;
        }
        body.sidebar-collapsed #content {
            margin-left: 50px;
        }

        body {
            background-color: #f9f9f9;
        }

        .content-container {
            margin-top: 20px;
            padding: 0 20px;
        }

        .archive-item {
            width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            color: inherit;
            display: block;
            text-decoration: none;
        }

        .text-container {
            flex-grow: 1;
            display: block;
            padding: 10px;
        }

        .text-container h3 {
            font-size: 1.5rem;
            color: #003366;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .text-container .author {
            color: #17a2b8;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .text-container p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
            max-height: 6.4em;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
        }

        /* Modal Styles for No Results Found */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            color: #a8001d;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .modal-content p {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 20px;
        }

        .retry-button {
            background-color: #0066cc;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
        }

        .retry-button:hover {
            background-color: #005bb5;
        }

        /* Additional styling for detailed view */
        .stats {
            display: flex;
            align-items: center;
            padding-top: 10px;
            color: #555;
            font-size: 0.85rem;
        }

        .stats div {
            margin-right: 20px;
            display: flex;
            align-items: center;
        }

        .stats i {
            margin-right: 5px;
            font-size: 1.2rem;
        }
                    /* Sidebar styling */
        .sidebar {
            overflow: hidden; /* Hide scrollbar */
            /* other styling like width, height, background color, etc. */
        }
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
    </style>

<!-- Main Content -->
<div id='content' class='content py-2'>
    <div class='container-fluid content-container'>
        <div class='col-12'>
            <div class='card card-outline card-primary shadow rounded-0'>
                <div class='card-body rounded-0'>
                    <h2>Search Results</h2>
                    <hr class='bg-navy'>
                    <div class='list-group'>";

                    // Retrieve search inputs
                    $searchTitle = isset($_GET['title']) ? trim($_GET['title']) : '';
                    $searchAuthor = isset($_GET['author']) ? trim($_GET['author']) : '';
                    $yearFrom = isset($_GET['year_from']) ? trim($_GET['year_from']) : '';
                    $yearTo = isset($_GET['year_to']) ? trim($_GET['year_to']) : '';
                    $searchKeyword = isset($_GET['topic_keyword']) ? trim($_GET['topic_keyword']) : '';

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
                        echo "
                            <script>
                                window.onload = function() { document.getElementById('noResultsModal').style.display = 'flex'; };
                            </script>";
                    } else {
                        // Step 2: Build the query for archive_list using direct filters and, if available, paper_ids
                        $query = "SELECT archive_list.id, archive_list.title, archive_list.abstract, 
                                  GROUP_CONCAT(CONCAT(archive_authors.first_name, ' ', archive_authors.last_name) SEPARATOR ', ') AS authors, 
                                  archive_list.year
                                  FROM archive_list
                                  LEFT JOIN archive_authors ON archive_list.id = archive_authors.archive_id
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
                        $query .= " GROUP BY archive_list.id";

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
                                $members = htmlspecialchars($row['authors'] ?? 'No Authors Available'); // Use concatenated authors
                                $year = htmlspecialchars($row['year'] ?? 'N/A');
                                $id = $row['id'];

                                // Track reads (increased view count)
                                $stmt = $conn->prepare("INSERT INTO archive_reads (archive_id) VALUES (?)");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();

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

                                // Fetch citation count
                                $stmt = $conn->prepare("SELECT COUNT(*) AS citation_count FROM archive_citation WHERE archive_id = ?");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $citation_result = $stmt->get_result();
                                $citation_data = $citation_result->fetch_assoc();
                                $citation_count = $citation_data['citation_count'];

                                echo "
                                    <a href='{$base_url}details.php?id={$id}' class='archive-item'>
                                        <div class='text-container'>
                                            <h3>{$title}</h3>
                                            <small class='author'>By {$members}</small>
                                            <div class='stats'>
                                                <div><i class='fa fa-eye'></i> Reads: {$read_count}</div>
                                                <div><i class='fa fa-download'></i> Downloads: {$download_count}</div>
                                                <div><i class='fa fa-quote-left'></i> Citations: {$citation_count}</div>
                                            </div>
                                            <p>{$abstract}</p>
                                            <small>Year: {$year}</small>
                                        </div>
                                    </a>";
                            }
                        } else {
                            // Trigger the modal for no results found
                            echo "
                                <script>
                                    window.onload = function() { document.getElementById('noResultsModal').style.display = 'flex'; };
                                </script>";
                        }
                    }

echo "
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for No Results Found -->
<div id='noResultsModal' class='modal'>
    <div class='modal-content'>
        <h3>No Results Found</h3>
        <p>Your search did not match any records. Please try again with different keywords.</p>
        <a href='advance-search.php' class='retry-button'>Retry</a>
    </div>
</div>

<script>
    // Add or remove sidebar-collapsed class on body
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
    }

    // Close the modal
    function closeModal() {
        document.getElementById('noResultsModal').style.display = 'none';
    }
</script>";
?>
