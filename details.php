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
            $reads_count = 0;
        }

        // Fetch download count from archive_downloads
        $download_stmt = $conn->prepare("SELECT COUNT(*) AS download_count FROM archive_downloads WHERE archive_id = ?");
        $download_stmt->bind_param("i", $id);
        $download_stmt->execute();
        $download_result = $download_stmt->get_result();
        if ($download_result->num_rows > 0) {
            $download_data = $download_result->fetch_assoc();
            $downloads_count = $download_data['download_count'];
        } else {
            $downloads_count = 0;
        }

        // Fetch citation count from archive_citations
        $citation_stmt = $conn->prepare("SELECT COUNT(*) AS citation_count FROM archive_citation WHERE archive_id = ?");
        $citation_stmt->bind_param("i", $id);
        $citation_stmt->execute();
        $citation_result = $citation_stmt->get_result();
        if ($citation_result->num_rows > 0) {
            $citation_data = $citation_result->fetch_assoc();
            $citations_count = $citation_data['citation_count'];
        } else {
            $citations_count = 0;
        }
    }

    // Fetch authors and format specifically for APA, MLA, Chicago, Harvard, and Vancouver citation styles
    $authors_stmt = $conn->prepare("SELECT DISTINCT first_name, last_name FROM archive_authors WHERE archive_id = ? ORDER BY author_order ASC");
    $authors_stmt->bind_param("i", $id);
    $authors_stmt->execute();
    $authors_result = $authors_stmt->get_result();

    $apa_authors = [];
    $general_authors = [];
    $mla_author = null;
    $has_multiple_authors = false;
    $chicago_authors = [];
    $harvard_authors = [];
    $vancouver_authors = [];

    $is_first_author = true;
    while ($author_row = $authors_result->fetch_assoc()) {
        $first_name = htmlspecialchars($author_row['first_name']);
        $last_name = htmlspecialchars($author_row['last_name']);
        $first_name_initial = strtoupper(substr($first_name, 0, 1));
        $middle_name_initial = (strpos($first_name, ' ') !== false) ? strtoupper(substr($first_name, strpos($first_name, ' ') + 1, 1)) : '';

        // APA format: Last, F.
        $apa_authors[] = "{$last_name}, {$first_name_initial}.";

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
        $initials = $middle_name_initial ? "{$first_name_initial}.{$middle_name_initial}." : "{$first_name_initial}.";
        $harvard_authors[] = "{$last_name}, {$initials}";

        // Vancouver format: Last F M (without periods)
        $vancouver_initials = $middle_name_initial ? "{$first_name_initial}{$middle_name_initial}" : "{$first_name_initial}";
        $vancouver_authors[] = "{$last_name} {$vancouver_initials}";
    }

    // APA authors formatted string
    if (count($apa_authors) > 1) {
        $last_author = array_pop($apa_authors);
        $apa_authors_formatted = implode(", ", $apa_authors) . ", & " . $last_author;
    } else {
        $apa_authors_formatted = $apa_authors[0];
    }

    // MLA format: use "et al." if there are multiple authors
    $mla_authors_formatted = $has_multiple_authors ? "{$mla_author}, et al." : $mla_author;

    // Chicago format: authors in "Last, First" for the first author and "First Last" for others
    $chicago_authors_formatted = implode(", ", $chicago_authors);

    // Harvard format: authors with initials
    if (count($harvard_authors) > 1) {
        $last_author = array_pop($harvard_authors);
        $harvard_authors_formatted = implode(", ", $harvard_authors) . " and " . $last_author;
    } else {
        $harvard_authors_formatted = $harvard_authors[0];
    }

    // Vancouver format: authors in "Last F M" style
    $vancouver_authors_formatted = implode(", ", $vancouver_authors);

    // Join authors for general formatting
    $general_authors_formatted = implode(", ", $general_authors);

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

html, body {
            height: 100%;
            margin: 0;
            padding: 0;
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
/* Citation Modal */
.modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        max-width: 600px;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: left;
        font-size: 28px;
        font-weight: bold;
        margin-right: auto;
        margin-left: 520px;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .citation-style {
        margin: 10px 0;
        font-size: 14px;
    }

    .citation-style strong {
        display: inline-block;
        width: 80px;
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
        /* Content area */
        .content-wrapper {
            margin-left: 250px;
            transition: margin-left 0.3s ease-in-out;
            height: 100%;
            overflow-y: auto; /* Allow vertical scrolling in the content area */
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

<div class="content-wrapper"> <!-- Added wrapper div -->
    <!-- Display Citation and Document Details -->
    <div class="card-body rounded-0">
        <div class="container-fluid">
            <fieldset class="fieldset">
                <legend class="legend"></legend>
                <h2 class="font-weight-bold"><?= $title ?? "" ?></h2>
                <small class="text-muted">Submitted by <b class="text-info"><?= $submitted ?></b> on <?= date("F d, Y h:i A", strtotime($date_created)) ?></small>
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
                <div><?= $general_authors_formatted ?></div>
            </fieldset>
            <fieldset class="fieldset">
                <legend class="legend">Project Document:</legend>
                <div>
                    <?php if (!empty($document_path) && file_exists($document_path)): ?>
                        <iframe src="<?= htmlspecialchars($document_path) ?>" frameborder="0" style="width: 100%; height: 500px;"></iframe>
                        <div class="doc-controls">
                            <button id="citeButton" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-quote-right"></i> Cite</button>
                            <a href="javascript:void(0);" onclick="downloadDocument()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</a>
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

<!-- Citation Modal for Different Styles -->
<div id="citationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCitationModal()">&times;</span>
        <h2>Cite</h2>
        <div class="citation-style"><strong>MLA:</strong> <span id="mlaCitation"></span></div>
        <div class="citation-style"><strong>APA:</strong> <span id="apaCitation"></span></div>
        <div class="citation-style"><strong>Chicago:</strong> <span id="chicagoCitation"></span></div>
        <div class="citation-style"><strong>Harvard:</strong> <span id="harvardCitation"></span></div>
        <div class="citation-style"><strong>Vancouver:</strong> <span id="vancouverCitation"></span></div>
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="downloadCitation()" class="btn btn-flat btn-navy btn-sm">Download Citation</button>
        </div>
    </div>
</div>

<script>
// Generate citations and show modal
function generateCitations() {
    const mlaAuthors = "<?= $mla_authors_formatted ?>";
    const apaAuthors = "<?= $apa_authors_formatted ?>";
    const chicagoAuthors = "<?= $chicago_authors_formatted ?>";
    const harvardAuthors = "<?= $harvard_authors_formatted ?>";
    const vancouverAuthors = "<?= $vancouver_authors_formatted ?>";
    const year = "<?= htmlspecialchars($year ?? '----') ?>";
    const title = "<?= htmlspecialchars($title ?? 'No Title') ?>";

    document.getElementById('mlaCitation').innerText = `${mlaAuthors}. "${title}." (${year}).`;
    document.getElementById('apaCitation').innerText = `${apaAuthors} (${year}). ${title}.`;
    document.getElementById('chicagoCitation').innerText = `${chicagoAuthors}. "${title}" (${year}).`;
    document.getElementById('harvardCitation').innerText = `${harvardAuthors}, ${year}. ${title}.`;
    document.getElementById('vancouverCitation').innerText = `${vancouverAuthors}. ${title}.${year}.`;

    document.getElementById("citationModal").style.display = "block";
}

function closeCitationModal() {
    document.getElementById("citationModal").style.display = "none";
}

document.getElementById("citeButton")?.addEventListener("click", function () {
    generateCitations();
});

// Function to create a downloadable file with all citations and increment the citation count
function downloadCitation() {
    const mlaCitation = document.getElementById('mlaCitation').innerText;
    const apaCitation = document.getElementById('apaCitation').innerText;
    const chicagoCitation = document.getElementById('chicagoCitation').innerText;
    const harvardCitation = document.getElementById('harvardCitation').innerText;
    const vancouverCitation = document.getElementById('vancouverCitation').innerText;

    const citationText = `
MLA: ${mlaCitation}
APA: ${apaCitation}
Chicago: ${chicagoCitation}
Harvard: ${harvardCitation}
Vancouver: ${vancouverCitation}
    `;

    const blob = new Blob([citationText], { type: "text/plain" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "citations.txt";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    // AJAX request to increment citation count
    $.ajax({
        url: 'increment_citation.php',
        type: 'POST',
        data: { archive_id: <?= $id ?> },
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success && result.new_citation_count !== undefined) {
                $('.stats .fa-quote-left').next().text(result.new_citation_count + ' Citations');
            } else {
                console.error('Failed to increment citation count:', result.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}
</script>

