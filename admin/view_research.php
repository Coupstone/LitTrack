<?php
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row);
                // Fetch topic keywords from lda_topics table using paper_id
                $lda_stmt = $conn->prepare("SELECT topic_keywords FROM lda_topics WHERE paper_id = ?");
                $lda_stmt->bind_param("i", $id);
                $lda_stmt->execute();
                $lda_result = $lda_stmt->get_result();
                $lda_keywords = [];
                if ($lda_result->num_rows > 0) {
                    while ($lda_row = $lda_result->fetch_assoc()) {
                        $lda_keywords[] = $lda_row['topic_keywords'];
                    }
                }
                $lda_keywords_formatted = implode(', ', $lda_keywords);

        // Insert a new read record
        $insert_read_stmt = $conn->prepare("INSERT INTO archive_reads (archive_id) VALUES (?)");
        $insert_read_stmt->bind_param("i", $id);
        $insert_read_stmt->execute();

        // Fetch read count
        $read_stmt = $conn->prepare("SELECT COUNT(*) AS read_count FROM archive_reads WHERE archive_id = ?");
        $read_stmt->bind_param("i", $id);
        $read_stmt->execute();
        $read_result = $read_stmt->get_result();
        $reads_count = ($read_result->num_rows > 0) ? $read_result->fetch_assoc()['read_count'] : 0;

        // Fetch citation count
        $citations_stmt = $conn->prepare("SELECT COUNT(*) AS citation_count FROM archive_citation WHERE archive_id = ?");
        $citations_stmt->bind_param("i", $id);
        $citations_stmt->execute();
        $citations_result = $citations_stmt->get_result();
        $citations_count = ($citations_result->num_rows > 0) ? $citations_result->fetch_assoc()['citation_count'] : 0;

        // Fetch download count
        $download_stmt = $conn->prepare("SELECT COUNT(*) AS download_count FROM archive_downloads WHERE archive_id = ?");
        $download_stmt->bind_param("i", $id);
        $download_stmt->execute();
        $download_result = $download_stmt->get_result();
        $downloads_count = ($download_result->num_rows > 0) ? $download_result->fetch_assoc()['download_count'] : 0;

        // Fetch authors and format citations
        $authors_stmt = $conn->prepare("SELECT DISTINCT first_name, last_name FROM archive_authors WHERE archive_id = ? ORDER BY author_order ASC");
        $authors_stmt->bind_param("i", $id);
        $authors_stmt->execute();
        $authors_result = $authors_stmt->get_result();

        $authors_data = $authors_result->fetch_all(MYSQLI_ASSOC);
        $apa_authors = [];
        $general_authors = [];
        $mla_author = null;
        $has_multiple_authors = false;
        $chicago_authors = [];
        $harvard_authors = [];
        $vancouver_authors = [];

        $is_first_author = true;
        foreach ($authors_data as $author_row) {
            $first_name = htmlspecialchars($author_row['first_name']);
            $last_name = htmlspecialchars($author_row['last_name']);
            $first_name_initial = strtoupper(substr($first_name, 0, 1));
            $middle_name_initial = (strpos($first_name, ' ') !== false) ? strtoupper(substr($first_name, strpos($first_name, ' ') + 1, 1)) : '';

            // APA format: Last, F. M.
            $initials = $middle_name_initial ? "{$first_name_initial}. {$middle_name_initial}." : "{$first_name_initial}.";
            $apa_authors[] = "{$last_name}, {$initials}";

            // General format for other citations
            $general_authors[] = "{$first_name} {$last_name}";

            // MLA format: first author + "et al." if multiple authors
            if ($mla_author === null) {
                $mla_author = "{$last_name}, {$first_name}";
            } else {
                $has_multiple_authors = true;
            }

            // Chicago format: First author as "Last, First" and others as "First Last"
            if ($is_first_author) {
                $chicago_authors[] = "{$last_name}, {$first_name}";
                $is_first_author = false;
            } else {
                $chicago_authors[] = "{$first_name} {$last_name}";
            }

            // Harvard format: Last, F.M. (or Last, F. if no middle name initial)
            $harvard_authors[] = "{$last_name}, {$initials}";

            // Vancouver format: Last F M (without periods)
            $vancouver_initials = $middle_name_initial ? "{$first_name_initial}{$middle_name_initial}" : "{$first_name_initial}";
            $vancouver_authors[] = "{$last_name} {$vancouver_initials}";
        }

        // APA authors formatted string
        $apa_authors_formatted = count($apa_authors) > 1
            ? implode(", ", array_slice($apa_authors, 0, -1)) . ", & " . end($apa_authors)
            : $apa_authors[0] ?? "N/A";

        // MLA format: use "et al." if there are multiple authors
        $mla_authors_formatted = $has_multiple_authors ? "{$mla_author}, et al." : $mla_author ?? "N/A";

        // Chicago format
        $chicago_authors_formatted = !empty($chicago_authors) ? implode(", ", $chicago_authors) : "N/A";

        // Harvard format
        $harvard_authors_formatted = count($harvard_authors) > 1
            ? implode(", ", array_slice($harvard_authors, 0, -1)) . " and " . end($harvard_authors)
            : $harvard_authors[0] ?? "N/A";

        // Vancouver format
        $vancouver_authors_formatted = !empty($vancouver_authors) ? implode(", ", $vancouver_authors) : "N/A";

        // General authors formatted string
        $general_authors_formatted = implode(", ", $general_authors);

        // Fetch the student's email who submitted the archive
        $submitted = "N/A";
        if (!empty($student_id)) {
            $student_stmt = $conn->prepare("SELECT email FROM student_list WHERE id = ?");
            $student_stmt->bind_param("i", $student_id);
            $student_stmt->execute();
            $student_result = $student_stmt->get_result();
            if ($student_result->num_rows > 0) {
                $student = $student_result->fetch_assoc();
                $submitted = $student['email'];
            }
        }

        // Check if the record was submitted by an admin
        if (!empty($uploader_id)) {
            $admin_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $admin_stmt->bind_param("i", $uploader_id);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            if ($admin_result->num_rows > 0) {
                $admin = $admin_result->fetch_assoc();
                $submitted = $admin['username'];
            }
        }
    }
}
?>
<html lang="en" class="" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/css/adminlte.css" rel="stylesheet">
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
        min-height: 100vh;
        padding-left: 100px;
        
        }
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
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden; /* Prevents scrolling */
    background-color: rgba(0, 0, 0, 0.4);
}


.modal-content {
    background-color: #fefefe;
    margin: auto; /* Center horizontally */
    top: 50%; /* Center vertically */
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    border: 1px solid #888;
    width: 90%;
    max-width: 600px;
    border-radius: 8px;
    overflow: hidden; /* Prevents scrolling within modal content */
    max-height: calc(100vh - 100px); /* Limits height to avoid scrolling */
    position: fixed;
}


.close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .citation-style {
        display: flex;
        justify-content: space-between;
        margin: 10px 0;
        font-size: 14px;
    }

    .citation-style strong {
        flex-basis: 30%; /* Adjusts the width of the label column */
        text-align: left;
    }

    .citation-style span {
        flex-basis: 100%; /* Adjusts the width of the value column */
        text-align: start;
    }
    </style>
<div class="card-body rounded-0">
    <div class="container-fluid">
        <fieldset class="fieldset">
            <legend class="legend"></legend>
            <h2 class="font-weight-bold"><?= $title ?? "" ?></h2>
            <small class="text-muted">Submitted by <b class="text-info"><?= $submitted ?></b> on <?= date("F d, Y h:i A", strtotime($date_created)) ?></small>
            <?php if (isset($student_id) && $_settings->userdata('login_type') == "2" && $student_id == $_settings->userdata('id')): ?>
                        <div class="form-group mt-3">
                            <a href=".admin/inc/?page=uploadresearch&id=<?= $id ?? "" ?>" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-edit"></i> Edit</a>
                            <button type="button" data-id="<?= $id ?? "" ?>" class="btn btn-flat btn-danger btn-sm delete-data"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                    <?php endif; ?>
                    <!-- <div class="text-center">
                        <img src="<?= validate_image($banner_path ?? "") ?>" alt="Banner Image" id="banner-img" class="img-fluid border bg-gradient-dark">
                    </div> -->
            <div class="stats">
                <div><i class="fa fa-eye"></i><span class="stat-value"><?= $reads_count ?? "0" ?></span> Reads</div>
                <div><i class="fa fa-quote-left"></i><span class="stat-value"><?= $citations_count ?? "0" ?></span> Citations</div>
                <div><i class="fa fa-download"></i><span class="stat-value"><?= $downloads_count ?? "0" ?></span> Downloads</div>
            </div>
        </fieldset>
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
            <div><?= $general_authors_formatted ?></div>
        </fieldset>
        <fieldset class="fieldset">
            <legend class="legend">Topic Keywords:</legend>
            <div><?= htmlspecialchars($lda_keywords_formatted) ?></div>
        </fieldset>
        <fieldset class="fieldset">
            <legend class="legend">Project Document:</legend>
            <div>
            <?php
                                if (!empty($document_path)) {
                                    $file_url = base_url . $document_path; // Combine the base URL with the relative path

                                    if (file_exists(__DIR__ . '/../' . $document_path)) { // Check file existence
                                        echo '<iframe src="' . htmlspecialchars($file_url) . '" frameborder="0" style="width: 100%; height: 500px;"></iframe>';
                                        echo '<div class="doc-controls">
                                                <button id="citeButton" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-quote-right"></i> Cite</button>
                                                <a href="javascript:void(0);" onclick="downloadDocument()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</a>
                                                <a href="' . htmlspecialchars($file_url) . '" target="_blank" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-eye"></i> View</a>
                                                <button id="publicationDetailsButton" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-info-circle"></i> Publication Details</button>
                                            </div>';
                                    } else {
                                        echo '<p>Document not available or could not be loaded.</p>';
                                    }
                                } else {
                                    echo '<p>Document path is empty or not set in the database.</p>';
                                }
                            ?>
            </div>
        </fieldset>
    </div>
</div>

<!-- Modal for publication details -->
<div id="publicationDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closePublicationDetailsModal()">&times;</span>
        <h2>Publication Details</h2>
        <div class="citation-style">
            <strong>Journal:</strong>
            <span><?= !empty($journal) ? htmlspecialchars($journal) : 'N/A' ?></span>
        </div>
        <div class="citation-style">
            <strong>Volume:</strong>
            <span><?= !empty($volume) ? htmlspecialchars($volume) : 'N/A' ?></span>
        </div>
        <div class="citation-style">
            <strong>Pages:</strong>
            <span><?= !empty($pages) ? htmlspecialchars($pages) : 'N/A' ?></span>
        </div>
        <div class="citation-style">
            <strong>DOI:</strong>
            <span>
                <?= !empty($doi) ? '<a href="https://doi.org/' . htmlspecialchars($doi) . '" target="_blank">' . htmlspecialchars($doi) . '</a>' : 'N/A' ?>
            </span>
        </div>
        <div class="citation-style">
            <strong>Publication Date:</strong>
            <span><?= !empty($publication_date) ? htmlspecialchars(date("F d, Y", strtotime($publication_date))) : 'N/A' ?></span>
        </div>
    </div>
</div>

<!-- Modal for citation -->
<div id="citationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCitationModal()">&times;</span>
        <h2>Cite</h2>
        <div class="citation-style">
            <strong>MLA:</strong> <span id="mlaCitation">Sample MLA Citation</span>
            <i class="bi bi-copy" onclick="copyToClipboard('mlaCitation')" style="cursor: pointer; margin-left: 10px;" title="Copy"></i>
        </div>
        <div class="citation-style">
            <strong>APA:</strong> <span id="apaCitation">Sample APA Citation</span>
            <i class="bi bi-copy" onclick="copyToClipboard('apaCitation')" style="cursor: pointer; margin-left: 10px;" title="Copy"></i>
        </div>
        <div class="citation-style">
            <strong>Chicago:</strong> <span id="chicagoCitation">Sample Chicago Citation</span>
            <i class="bi bi-copy" onclick="copyToClipboard('chicagoCitation')" style="cursor: pointer; margin-left: 10px;" title="Copy"></i>
        </div>
        <div class="citation-style">
            <strong>Harvard:</strong> <span id="harvardCitation">Sample Harvard Citation</span>
            <i class="bi bi-copy" onclick="copyToClipboard('harvardCitation')" style="cursor: pointer; margin-left: 10px;" title="Copy"></i>
        </div>
        <div class="citation-style">
            <strong>Vancouver:</strong> <span id="vancouverCitation">Sample Vancouver Citation</span>
            <i class="bi bi-copy" onclick="copyToClipboard('vancouverCitation')" style="cursor: pointer; margin-left: 10px;" title="Copy"></i>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- jQuery (Optional for Bootstrap 5) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap 5 Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const citeButton = document.getElementById("citeButton");
        if (citeButton) {
            citeButton.addEventListener("click", generateCitations);
        }
    });

    function generateCitations() {
        console.log("Cite button clicked!"); // Debugging log

        // Citation variables
        const mlaAuthors = <?= json_encode($mla_authors_formatted ?? "") ?>;
        const apaAuthors = <?= json_encode($apa_authors_formatted ?? "") ?>;
        const chicagoAuthors = <?= json_encode($chicago_authors_formatted ?? "") ?>;
        const harvardAuthors = <?= json_encode($harvard_authors_formatted ?? "") ?>;
        const vancouverAuthors = <?= json_encode($vancouver_authors_formatted ?? "") ?>;
        const year = <?= json_encode($year ?? "----") ?>;
        const title = <?= json_encode($title ?? "No Title") ?>;

        // Generate citations
        document.getElementById("mlaCitation").innerText = `${mlaAuthors}. "${title}." (${year}).`;
        document.getElementById("apaCitation").innerText = `${apaAuthors} (${year}). ${title}.`;
        document.getElementById("chicagoCitation").innerText = `${chicagoAuthors}. "${title}" (${year}).`;
        document.getElementById("harvardCitation").innerText = `${harvardAuthors}, ${year}. ${title}.`;
        document.getElementById("vancouverCitation").innerText = `${vancouverAuthors}. ${title}. ${year}.`;

        // Show citation modal
        document.getElementById("citationModal").style.display = "block";
    }

    function closeCitationModal() {
        // Hide the citation modal
        document.getElementById("citationModal").style.display = "none";
    }

    function downloadCitation() {
        const citationText =
            `MLA: ${document.getElementById('mlaCitation').innerText}
            APA: ${document.getElementById('apaCitation').innerText}
            Chicago: ${document.getElementById('chicagoCitation').innerText}
            Harvard: ${document.getElementById('harvardCitation').innerText}
            Vancouver: ${document.getElementById('vancouverCitation').innerText}`;

        const blob = new Blob([citationText], { type: "text/plain" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "citations.txt";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        // AJAX request to update citation count in the database
        $.ajax({
            url: 'increment_citation.php',
            type: 'POST',
            data: { archive_id: <?= $id ?> },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Update displayed citation count
                    $('.stats .fa-quote-left').next('.stat-value').text(response.new_citation_count + ' Citations');
                } else {
                    console.error('Error:', response.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    $(function () {
        $('.delete-data').click(function () {
            const archiveCode = <?= json_encode($archive_code ?? "") ?>;
            _conf(`Are you sure to delete <b>Archive-${archiveCode}</b>`, "delete_archive");
        });
    });

    function delete_archive() {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_archive",
            method: "POST",
            data: { id: <?= json_encode($id ?? "") ?> },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.href = ".admin/?page=archives";
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }

    function downloadDocument() {
        window.open("<?= base_url . ($document_path ?? "") ?>", '_blank');
    }

    function openPublicationDetailsModal() {
        // Show the modal
        document.getElementById("publicationDetailsModal").style.display = "block";
    }

    function closePublicationDetailsModal() {
        // Hide the modal
        document.getElementById("publicationDetailsModal").style.display = "none";
    }

    // Attach event listener to the "Publication Details" button
    document.getElementById("publicationDetailsButton").addEventListener("click", openPublicationDetailsModal);

    // Close the modal when the user clicks anywhere outside the modal
    window.onclick = function (event) {
        const modal = document.getElementById("publicationDetailsModal");
        if (event.target === modal) {
            closePublicationDetailsModal();
        }
    };
</script>
