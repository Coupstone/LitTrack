<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'view_archive';
require_once('./config.php');
require_once('inc/topBarNav.php');
require_once('inc/header.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);

    // Fetch archive entry details, including archive_code and student_id
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row); // Extract fields like title, year, abstract, document_path, archive_code, student_id, etc.
    }

    // Fetch authors for the archive entry in order of author_order
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
}
?>
<style>
    /* Styling for the layout */
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: #f9f9f9;
    }
    .content {
        padding: 30px;
        margin: 20px auto;
        max-width: 1200px;
        background-color: #ffffff; 
    }
    .card-header {
        background-color: #a8001d;
        color: #ffffff;
        padding: 20px;
    }
    .card-title {
        font-size: 1.75rem;
        font-weight: bold;
    }
    .fieldset {
        margin-bottom: 20px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        padding: 20px;
    }
    .legend {
        font-size: 1.25rem;
        font-weight: bold;
        color: #a8001d;
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
</style>

<div class="content py-4">
    <div class="container-fluid">
        <div class="card card-outline card-primary rounded-0">
            <div class="card-header">
                <h3 class="card-title">Archive - <?= htmlspecialchars($archive_code ?? "Unknown") ?></h3>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <h2 class="font-weight-bold"><?= htmlspecialchars($title ?? "Untitled") ?></h2>
                    <small class="text-muted">Submitted by <b class="text-info"><?= htmlspecialchars($submitted) ?></b> on <?= isset($date_created) ? date("F d, Y h:i A", strtotime($date_created)) : "N/A" ?></small>
                    <hr>

                    <!-- Project Year -->
                    <fieldset class="fieldset">
                        <legend class="legend">Project Year:</legend>
                        <div><?= htmlspecialchars($year ?? "----") ?></div>
                    </fieldset>

                    <!-- Abstract -->
                    <fieldset class="fieldset">
                        <legend class="legend">Abstract:</legend>
                        <div><?= isset($abstract) ? html_entity_decode($abstract) : "No abstract available" ?></div>
                    </fieldset>

                    <!-- Authors -->
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
                                    <a href="<?= htmlspecialchars($document_path) ?>" download class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</a>

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
        window.open("<?= htmlspecialchars($document_path) ?>", '_blank');
    }
</script>
