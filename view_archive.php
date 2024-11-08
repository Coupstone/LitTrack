<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'view_archive';
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']); 
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row); 

        // AJAX request handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'increment_download') {
    $id = intval($_POST['id']);
    $insert_download_stmt = $conn->prepare("INSERT INTO archive_downloads (archive_id, download_date) VALUES (?, NOW())");
    $insert_download_stmt->bind_param("i", $id);
    header('Content-Type: application/json');
    if ($insert_download_stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    exit;
}

// Page rendering logic
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']); 
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row);
    }
}
        // Insert a new read record
        $insert_read_stmt = $conn->prepare("INSERT INTO archive_reads (archive_id) VALUES (?)");
        $insert_read_stmt->bind_param("i", $id);
        $insert_read_stmt->execute();

        // Fetch read count from archive_reads
        $read_stmt = $conn->prepare("SELECT COUNT(*) AS read_count FROM archive_reads WHERE archive_id = ?");
        $read_stmt->bind_param("i", $id);
        $read_stmt->execute();
        $read_result = $read_stmt->get_result();
        if ($read_result->num_rows > 0) {
            $read_data = $read_result->fetch_assoc();
            $reads_count = $read_data['read_count'];
        } else {
            $reads_count = 0; // Default to 0 if no rows returned
        }
    }
// Prepare a statement to select the download count from archive_downloads
$download_stmt = $conn->prepare("SELECT COUNT(*) AS download_count FROM archive_downloads WHERE archive_id = ?");
$download_stmt->bind_param("i", $id);
$download_stmt->execute();
$download_result = $download_stmt->get_result();

if ($download_result->num_rows > 0) {
    $download_data = $download_result->fetch_assoc();
    $downloads_count = $download_data['download_count'];
} else {
    $downloads_count = 0; // Default to 0 if no rows returned
}
    }
 
    // Fetch authors
    $authors_stmt = $conn->prepare("SELECT first_name, last_name FROM archive_authors WHERE archive_id = ? ORDER BY author_order ASC");
    $authors_stmt->bind_param("i", $id);
    $authors_stmt->execute();
    $authors_result = $authors_stmt->get_result();

    $authors = [];
    while ($author_row = $authors_result->fetch_assoc()) {
        $authors[] = $author_row;
    }

    // Fetch the student's email who submitted the archive
    $submitted = "N/A";
    if (isset($student_id)) {
        $student_stmt = $conn->prepare("SELECT email FROM student_list WHERE id = ?");
        $student_stmt->bind_param("i", $student_id);
        $student_stmt->execute();
        $student_result = $student_stmt->get_result();
        if ($student_result->num_rows > 0) {
            $student = $student_result->fetch_assoc();
            $submitted = $student['email'];
        }
    }

?>




<style>
    .main-sidebar .nav-sidebar .nav-link p,
.main-sidebar .nav-sidebar .nav-header,
.main-sidebar .nav-sidebar .brand-text {
    font-weight: 700;
}

    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #f9f9f9;
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
    }

    .card {
        border-radius: 10px;
        overflow: hidden;
        background-color: #ffffff; 
        border: none; 
        box-shadow: none; 
    }

    .card-header {
        background-color: #a8001d;
        color: #ffffff;
        padding: 20px;
        border-bottom: 1px solid #8c0000;
    }

    .card-title {
        font-size: 1.75rem;
        margin: 0;
        font-weight: bold;
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

    .btn-danger {
        background-color: #dc3545;
        color: #ffffff;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
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
    }

    .pl-4 {
        padding-left: 1.5rem;
    }

    .text-center {
        text-align: center;
    }

    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .doc-controls {
        margin-top: 20px;
        text-align: center;
    }

    .doc-controls button, .doc-controls a {
        background-color: #a8001d;
        color: #ffffff;
        margin-right: 5px;
        padding: 8px 15px;
        font-size: 0.875rem;
        border-radius: 4px;
        text-decoration: none;
    }
    .table-shadow {
        border: 1px solid #e1e1e1;
    }

    iframe#document_field {
        height: 1000px; 
        background-color: #ffffff; 
        border: none;
    }

    .main-sidebar .os-content {
        margin-top: 0;
    }
    .main-sidebar .nav-sidebar .nav-item:first-child {
        margin-top: 0.5rem;
    }
    .brand-text {
        margin-left: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .brand-text .littrack-text {
        font-family: 'Georgia', serif;
        font-size: 1.2rem;
        background: linear-gradient(to bottom, #007bff, #4e4e4e, #b81d24);
        -webkit-background-clip: text;
        color: transparent;
        font-weight: bold;
        margin-top: -0.4rem;
        line-height: 1;
    }
    .brand-link {
        display: flex;
        align-items: center;
        transition: all 0.3s ease-in-out;
    }
    .sidebar-mini.sidebar-collapse .brand-link .brand-image {
        margin-left: auto;
        margin-right: auto;
        display: block;
    }
    .sidebar-mini.sidebar-collapse .brand-link .brand-text {
        display: none;
    }
    .nav-link.active {
        background-color: #007bff;
        color: white !important;
    }
    .nav-link.active i {
        color: white !important;
    }
    .navbar-nav {
        position: relative;
        z-index: 1030;
        transition: none;
    }
    body.sidebar-collapsed .navbar-nav {
        margin-left: 60px;
    }
    body.sidebar-expanded .navbar-nav {
        margin-left: 250px;
    }
    .main-sidebar {
        width: 250px;
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    body.sidebar-collapsed .main-sidebar {
        width: 60px;
        margin-left: 0;
    }
    body.sidebar-expanded .main-sidebar {
        width: 250px;
        margin-left: 0;
    }
    .main-sidebar .nav-link p {
        display: inline;
    }
    body.sidebar-collapsed .main-sidebar .nav-link p {
        display: none;
    }
    .main-sidebar .nav-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    body.sidebar-collapsed .main-sidebar .nav-link i {
        margin-right: 0;
        text-align: center;
        width: 100%;
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
    .brand-link .brand-text {
        font-size: 1rem;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }
    body.sidebar-collapsed .brand-link {
        padding: 0.5rem;
    }
    body.sidebar-collapsed .brand-link .brand-image {
        width: 2rem;
        height: 2rem;
        margin-right: 0;
    }
    body.sidebar-collapsed .brand-link .brand-text {
        opacity: 0;
        overflow: hidden;
    }
    body.sidebar-expanded .brand-link .brand-text {
        opacity: 1;
    }
    .stats {
    display: flex;
    align-items: center;
    justify-content: start;
    padding: 5px 0; /* Reduced padding */
    font-size: 0.75rem; /* Smaller font size */
    color: #555;
}

.stats div {
    margin-right: 10px; /* Reduced margin */
    display: flex;
    align-items: center;
}

.stats i {
    margin-right: 5px; /* Added more spacing between icon and text */
    font-size: 0.6rem; /* Smaller icons */
    color: #000 /* Theme color */
}

.stats .stat-value::after {
    content: " " attr(data-label); /* Adds a space followed by the label */
    font-weight: normal;
    color: #000;
    margin-left: 3px; /* Optional, adjust as needed for more spacing */
}

</style>

<div class="card-body rounded-0">
    <div class="container-fluid">
        <fieldset class="fieldset">
            <legend class="legend"></legend>
            <h2 class="font-weight-bold"><?= $title ?? "" ?></h2>
            <small class="text-muted">Submitted by <b class="text-info"><?= $submitted ?></b> on <?= date("F d, Y h:i A", strtotime($date_created)) ?></small>
            <!-- Updated Inline Statistics with spacing before text -->
            <div class="stats">
    <div><i class="fa fa-eye"></i><span class="stat-value"><?= $reads_count ?? "0" ?></span> Reads</div>
    <div><i class="fa fa-quote-left"></i><span class="stat-value"><?= $citations_count ?? "0" ?></span> Citations</div>
    <div><i class="fa fa-download"></i><span class="stat-value"><?= $downloads_count ?? "0" ?></span> Downloads</div>
</div>

        </fieldset>
        <hr>
        <fieldset class="fieldset">
            <legend class="legend">Project Year:</legend>
            <div class="pl-4"><large><?= $year ?? "----" ?></large></div>
        </fieldset>
        <fieldset class="fieldset">
            <legend class="legend">Abstract:</legend>
            <div class="pl-4"><large><?= html_entity_decode($abstract ?? "") ?></large></div>
        </fieldset>
        <fieldset class="fieldset">
        <legend class="legend">Authors:</legend>
                        <div>
                            <?php 
                            $author_names = [];
                            foreach ($authors as $author) {
                                $author_names[] = htmlspecialchars($author['first_name'] . " " . $author['last_name']);
                            }
                            echo implode(", ", $author_names); // Join authors with commas
                            ?>
                        </div>
        </fieldset>
                    <!-- Project Document -->
                    <fieldset class="fieldset">
                        <legend class="legend">Project Document:</legend>
                        <div>
                            <?php if (!empty($document_path) && file_exists($document_path)): ?>
                                <iframe src="<?= htmlspecialchars($document_path) ?>" frameborder="0" style="width: 100%; height: 500px;"></iframe>
                                <div class="doc-controls">
                                    <!-- Print Button -->
                                    <button onclick="window.print()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-print"></i> Print</button>

                                    <!-- Download Button -->
                                    <!-- Download Button -->
                                    <a href="javascript:void(0);" onclick="downloadDocument()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</a>




                                    <!-- View Button -->
                                    <a href="<?= htmlspecialchars($document_path) ?>" target="_blank" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-eye"></i> View</a>
                                </div>
                            <?php else: ?>
                                <p>Document not available or could not be loaded.</p>
                            <?php endif; ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
function downloadDocument() {
    var archiveId = <?= $id ?>;
    console.log("Starting download for archive ID:", archiveId);

    // Assuming document_path is set correctly and accessible
    window.open("<?= htmlspecialchars($document_path) ?>", '_blank');

    // AJAX call to increment download count
    $.ajax({
        url: 'download_document.php',
        type: 'POST',
        data: {archive_id: archiveId},
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success && result.download_count !== undefined) {
                $('.stats .fa-download').next().text(result.download_count + ' Downloads');
                console.log("Download count incremented successfully:", result.download_count);
            } else {
                console.error('Failed to increment download count:', result.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
        }
    });
}

</script>
