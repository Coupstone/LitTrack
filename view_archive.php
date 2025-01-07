<?php


$page = isset($_GET['page']) ? $_GET['page'] : 'view_archive';
require_once('./config.php');
check_login(); 
require_once('inc/topBarNav.php');
require_once('inc/header.php');



if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
} elseif (isset($_GET['uuid'])) {
    $uuid = $conn->real_escape_string($_GET['uuid']);
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE uuid = ?");
    $stmt->bind_param("s", $uuid);
} else {
    // Handle the case where neither id nor uuid is provided
    die("Invalid request.");
}
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
    }



 // Fetch citation count from citation_relationships using cited_paper_id
 $citations_stmt = $conn->prepare("SELECT COUNT(*) AS citation_count FROM citation_relationships WHERE cited_paper_id = ?");
 $citations_stmt->bind_param("i", $id);
 $citations_stmt->execute();
 $citations_result = $citations_stmt->get_result();
 $citations_count = ($citations_result->num_rows > 0) ? $citations_result->fetch_assoc()['citation_count'] : 0;



    // Prepare a statement to select the download count from archive_downloads
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
    if (count($apa_authors) > 1) {
        $last_author = array_pop($apa_authors);
        $apa_authors_formatted = implode(", ", $apa_authors) . ", & " . $last_author;
    } else {
        $apa_authors_formatted = $apa_authors[0];
    }


    // MLA format: use "et al." if there are multiple authors
    $mla_authors_formatted = $has_multiple_authors ? "{$mla_author}, et al" : $mla_author;


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
         $student_stmt = $conn->prepare("SELECT firstname, lastname FROM student_list WHERE id = ?");
         $student_stmt->bind_param("i", $student_id);
         $student_stmt->execute();
         $student_result = $student_stmt->get_result();
         if ($student_result->num_rows > 0) {
             $student = $student_result->fetch_assoc();
             $submitted = $student['firstname'] . ' ' . $student['lastname'];
         }
     }


// Check if user_id exists in the session before using it
if (isset($_SESSION['user_id']) && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row);


        // Safely access user_id from session
        $user_id = $_SESSION['student_id'];
        $access_stmt = $conn->prepare("SELECT status FROM access_requests WHERE archive_id = ? AND user_id = ? AND status = 'granted'");
        $access_stmt->bind_param("ii", $id, $user_id);
        $access_stmt->execute();
        $access_result = $access_stmt->get_result();


        $access_granted = ($access_result->num_rows > 0) ? true : false;
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
    <legend class="legend">Topic Keywords:</legend>
    <div><?= htmlspecialchars($lda_keywords_formatted) ?></div>
</fieldset>
<!-- Modal for publicaton details -->
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

        <fieldset class="fieldset">
            <legend class="legend">Project Document:</legend>
            <div>
            
<?php
// Check if user_id exists in the session before using it
if (isset($_SESSION['student_id']) && isset($_GET['id']) && $_GET['id'] > 0) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM archive_list WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        extract($row);

        // Safely access user_id from session
        $user_id = $_SESSION['student_id'];

        // Check if the current user is the owner
        $is_owner = ($student_id == $user_id);

        // Check if the user has been granted access
        $access_stmt = $conn->prepare("SELECT status FROM access_requests WHERE archive_id = ? AND user_id = ? AND status = 'granted'");
        $access_stmt->bind_param("ii", $id, $user_id);
        $access_stmt->execute();
        $access_result = $access_stmt->get_result();

        $access_granted = ($access_result->num_rows > 0);

        // Determine document access
        if ($visibility === "private" && !$is_owner && !$access_granted) {
            echo '<p>This document is private. You need to request access from the owner.</p>
                  <div class="doc-controls">
                    <button onclick="requestAccess(' . $id . ')" class="btn btn-flat btn-danger btn-sm"><i class="fa fa-lock"></i> Request Access</button>
                  </div>';
        } elseif (!empty($document_path) && file_exists($document_path)) {
            // Display the document
            echo '<iframe src="' . htmlspecialchars($document_path) . '" frameborder="0" style="width: 100%; height: 500px;"></iframe>';
            echo '<div class="doc-controls">
                    <button id="citeButton" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-quote-right"></i> Cite</button>
                    <a href="javascript:void(0);" onclick="downloadDocument()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</a>
                    <a href="' . htmlspecialchars($document_path) . '" target="_blank" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-eye"></i> View</a>
                    <button id="publicationDetailsButton" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-info-circle"></i> Publication Details</button>
                  </div>';
        } else {
            echo '<p>Document not available or could not be loaded.</p>';
        }
    }
}
?>




            </div>
        </fieldset>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">



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


<script>
    function copyToClipboard(elementId) {
        const citationText = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(citationText).then(() => {
            showPopupMessage('Copied to clipboard!');
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }

    function showPopupMessage(message) {
        const popup = document.createElement('div');
        popup.innerText = message;
        popup.style.position = 'fixed';
        popup.style.top = '20px'; /* Center-top position */
        popup.style.left = '50%'; /* Center horizontally */
        popup.style.transform = 'translateX(-50%)'; /* Adjust for true center */
        popup.style.padding = '10px 20px';
        popup.style.backgroundColor = '#28a745'; /* Success color */
        popup.style.color = 'white';
        popup.style.fontSize = '14px';
        popup.style.borderRadius = '5px';
        popup.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        popup.style.zIndex = '1000';
        popup.style.opacity = '1';
        popup.style.transition = 'opacity 0.5s ease';

        document.body.appendChild(popup);

        // Remove the popup after 3 seconds
        setTimeout(() => {
            popup.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(popup);
            }, 500);
        }, 1000);
    }
</script>









<script>
function generateCitations() {
    // Generate and display citation text in the modal
    const mlaAuthors = "<?= $mla_authors_formatted ?>";
    const apaAuthors = "<?= $apa_authors_formatted ?>";
    const chicagoAuthors = "<?= $chicago_authors_formatted ?>";
    const harvardAuthors = "<?= $harvard_authors_formatted ?>";
    const vancouverAuthors = "<?= $vancouver_authors_formatted ?>";
    const year = "<?= htmlspecialchars($year ?? '----') ?>";
    const title = "<?= htmlspecialchars($title ?? 'No Title') ?>".toUpperCase(); // Convert title to uppercase

    // Format citations
    document.getElementById('mlaCitation').innerText = `${mlaAuthors}. "${title}." (${year}).`;
    document.getElementById('apaCitation').innerText = `${apaAuthors} (${year}). ${title}.`;
    document.getElementById('chicagoCitation').innerText = `${chicagoAuthors}. "${title}" (${year}).`;
    document.getElementById('harvardCitation').innerText = `${harvardAuthors}, ${year}. ${title}.`;
    document.getElementById('vancouverCitation').innerText = `${vancouverAuthors}. ${title}. ${year}.`;

    // Show the citation modal
    document.getElementById("citationModal").style.display = "block";
}

function closeCitationModal() {
    // Hide the citation modal
    document.getElementById("citationModal").style.display = "none";
}

// Attach event listener to the Cite button to open the citation modal
document.getElementById("citeButton").addEventListener("click", generateCitations);

// Attach event listener to the close button to close the citation modal
document.querySelector(".close").addEventListener("click", closeCitationModal);



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
        success: function(response) {
            if (response.success) {
                // Update displayed citation count
                $('.stats .fa-quote-left').next('.stat-value').text(response.new_citation_count + ' Citations');
            } else {
                console.error('Error:', response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}




function downloadDocument() {
    var archiveId = <?= $id ?>;
    window.open("<?= htmlspecialchars($document_path) ?>", '_blank');


    $.ajax({
        url: 'download_document.php',
        type: 'POST',
        data: {archive_id: archiveId},
        success: function(response) {
            const result = JSON.parse(response);
            if (result.success && result.download_count !== undefined) {
                $('.stats .fa-download').next().text(result.download_count + ' Downloads');
            } else {
                console.error('Failed to increment download count:', result.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}


function requestAccess(archiveId) {
    Swal.fire({
        title: 'Request Access',
        text: 'This document is private. Would you like to send an access request to the owner?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Request Access',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'request_access.php',
                method: 'POST',
                data: { archive_id: archiveId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log(response.success);
                        console.log(response.message);
                        console.log('Hello');
                        Swal.fire('Success', 'Your access request has been sent.', 'success');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error: ', error);
                    console.log('Status', status);
                    console.error('XHR: ', xhr.responseText);
                    Swal.fire('Error', 'Failed to send access request.', 'error');
                }
            });
        }
    });
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
window.onclick = function(event) {
    const modal = document.getElementById("publicationDetailsModal");
    if (event.target === modal) {
        closePublicationDetailsModal();
    }
};
</script>

</script>


