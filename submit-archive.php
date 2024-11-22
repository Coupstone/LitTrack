<?php 
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `archive_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k))
                $$k = $v;
        }
    }
    if (isset($student_id)) {
        if ($student_id != $_settings->userdata('id')) {
            echo "<script> alert('You don\'t have access to this page'); location.replace('./'); </script>";
        }
    }
}
?>

<!-- Include SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
            #title-label,
    #abstract-label,
    #pdf-label {
        font-weight: normal; /* Ensures text is not bold */
    }
            #abstract {
        min-height: 150px; /* Set a minimum height for the textarea */
        width: 100%; /* Ensure it takes up all available width within its container */
    }

    .container {
        max-width: 1600px; /* Keep the container wide as previously set */
    }
    .form-control, .form-control:focus {
        border-color: #ced4da; /* Consistent with the design */
        box-shadow: none; /* No focus shadow */
    }
    .form-floating {
        margin-bottom: 16px; /* Space between fields */
    }
    .card {
        border-radius: 0; /* Flat design */
    }
    .card-header {
        border-bottom: 2px solid #007bff; /* Stylish blue border top on card header */
    }

    /* Smaller button styles */
    .btn {
        padding: 0.375rem 0.75rem; /* Reduced padding */
        font-size: 0.875rem; /* Smaller font size */
        line-height: 1.5; /* Standard line height */
    }
    .author-row .form-control {
        margin-right: 15px; /* Adds space to the right of each input field except the last in the row */
    }
    .author-row .form-control:last-child {
        margin-right: 0; /* Ensures the last input in the row does not have extra space on the right */
    }
    .author-row {
        display: flex; /* Ensures the input fields are aligned in a row */
        align-items: center; /* Aligns items vertically */
        margin-bottom: 15px; /* Adds space below each author row for better separation */
    }
    #pdf-label {
        display: block; /* Ensures the label takes up the full width and behaves like a block element */
        margin-bottom: 8px; /* Adds some space below the label before the input field */
        font-weight: normal; /* Keeps the label text normal, non-bold */
    }
    .form-control {
        display: block;
        width: 100%; /* Ensures the input takes full width of its container */
        padding: 0.375rem 0.75rem; /* Standard padding for Bootstrap form controls */
        font-size: 1rem; /* Standard font size for input text */
        line-height: 1.5; /* Standard line height for readability */
        color: #495057; /* Default text color */
        background-color: #fff; /* White background */
        background-clip: padding-box; /* Ensures background extends to the borders */
        border: 1px solid #ced4da; /* Standard border styling */
        border-radius: 0.25rem; /* Rounded borders for aesthetics */
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; /* Smooth transition for focus effects */
    }
    .btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .form-label {
        font-weight: normal; /* Ensures the label text is not bold */
        display: block;
        margin-bottom: 0.5rem;
    }
    .optional-text {
        font-size: 0.875rem; /* Slightly smaller than button text */
        color: #6c757d; /* Muted text color for secondary information */
        margin-left: 2px; /* Space between button and text */
        vertical-align: middle; /* Align text vertically with the button */
    }
</style>


<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title"><?= isset($id) ? "Update Archive - {$archive_code} Details" : "Upload Research" ?></h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="archive-form" enctype="multipart/form-data" method="POST">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">

                    <!-- Research Title -->
                    <div class="form-group">
                        <label for="title" class="control-label text-navy">Research Title</label>
                        <input type="text" name="title" id="title" placeholder="Project Title" class="form-control form-control-border" value="<?= isset($title) ? $title : "" ?>" required>
                    </div>

                    <!-- Year -->
                    <div class="form-group">
                        <label for="year" class="control-label text-navy">Year</label>
                        <select name="year" id="year" class="form-control form-control-border" required>
                            <?php for ($i = 0; $i < 51; $i++): ?>
                            <option <?= isset($year) && $year == date("Y", strtotime(date("Y")." -{$i} years")) ? "selected" : "" ?>>
                                <?= date("Y", strtotime(date("Y")." -{$i} years")) ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Abstract -->
                    <div class="form-group">
                        <label for="abstract" class="control-label text-navy">Abstract</label>
                        <textarea rows="3" name="abstract" id="abstract" placeholder="abstract" class="form-control form-control-border summernote" required><?= isset($abstract) ? html_entity_decode($abstract) : "" ?></textarea>
                    </div>

                    <!-- Authors -->
                    <div class="form-group">
                        <h6 class="bold">Authors</h6>
                        <div id="author-container">
                            <div class="author-row d-flex align-items-center mb-2">
                                <input type="text" name="author_firstname[]" class="form-control" placeholder="First Name" required>
                                <input type="text" name="author_lastname[]" class="form-control" placeholder="Last Name" required>
                                <button type="button" class="btn btn-success btn-sm" onclick="addAuthorRow()">+</button>
                            </div>
                        </div>
                    </div>

                    
<!-- Visibility -->
<div class="form-group">
    <label for="visibility" class="control-label text-navy">Visibility</label>
    <select name="visibility" id="visibility" class="form-control form-control-border" required>
        <option value="public" <?= isset($visibility) && $visibility == 'public' ? "selected" : "" ?>>Public</option>
        <option value="private" <?= isset($visibility) && $visibility == 'private' ? "selected" : "" ?>>Private</option>
    </select>
</div>
<!-- Publication Details -->
<div class="mb-3">
    <label class="form-label">Publication Details</label>
    <button type="button" class="btn btn-info" onclick="togglePublicationDetails()">+ Add</button>
    <span class="optional-text">(Optional)</span>
</div>
<div id="publication-details" style="display: none;">
    <div class="form-group mb-3">
        <input type="text" class="form-control" id="journal" name="journal" placeholder="Journal" value="<?= isset($journal) ? $journal : '' ?>">
    </div>
    <div class="form-group mb-3">
        <input type="text" class="form-control" id="volume" name="volume" placeholder="Volume" value="<?= isset($volume) ? $volume : '' ?>">
    </div>
    <div class="form-group mb-3">
        <input type="text" class="form-control" id="pages" name="pages" placeholder="Pages" value="<?= isset($pages) ? $pages : '' ?>">
    </div>
    <div class="form-group mb-3">
        <input type="text" class="form-control" id="doi" name="doi" placeholder="DOI" value="<?= isset($doi) ? $doi : '' ?>">
    </div>
    <div class="form-group mb-3">
        <input type="date" class="form-control" id="publicationDate" name="publicationDate" value="<?= isset($publication_date) ? $publication_date : '' ?>">
    </div>
</div>



                    <!-- Project Document -->
                    <div class="form-group">
                        <label for="pdf" class="control-label text-muted">Project Document (PDF File Only, maximum of 25MB)</label>
                        <input type="file" id="pdf" name="pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-center">
                        <button class="btn btn-default bg-navy btn-flat">Upload</button>
                        <a href="./?page=profile" class="btn btn-light border btn-flat">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePublicationDetails() {
        const detailsDiv = document.getElementById('publication-details');
        detailsDiv.style.display = detailsDiv.style.display === 'none' ? 'block' : 'none';
    }

document.getElementById('pdf').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const fileType = file.type;
            if (fileType !== 'application/pdf') {
                Swal.fire({
                    title: 'Invalid File',
                    text: 'Only PDF files are allowed. Please upload a valid PDF file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                this.value = ''; // Clear the invalid file
            }
        }
    });

    function addAuthorRow() {
        const authorContainer = document.getElementById("author-container");
        const authorRows = authorContainer.getElementsByClassName("author-row");

        if (authorRows.length >= 6) {
            Swal.fire({
                title: 'Limit Reached',
                text: 'You can only add a maximum of 6 authors.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const authorRow = `
            <div class="author-row d-flex align-items-center mb-2">
                <input type="text" name="author_firstname[]" class="form-control" placeholder="First Name" required>
                <input type="text" name="author_lastname[]" class="form-control" placeholder="Last Name" required>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeAuthorRow(this)">-</button>
            </div>
        `;
        authorContainer.insertAdjacentHTML("beforeend", authorRow);
    }

    function removeAuthorRow(button) {
        button.parentElement.remove();
    }
</script>


<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $conn->real_escape_string($_POST['title']);
    $year = $conn->real_escape_string($_POST['year']);
    $abstract = htmlentities($_POST['abstract']);
    $curriculum_id = $_settings->userdata('curriculum_id'); // Automatically retrieve user's curriculum_id
    $author_firstnames = $_POST['author_firstname'];
    $author_lastnames = $_POST['author_lastname'];
    $student_id = $_settings->userdata('id');
    $visibility = $conn->real_escape_string($_POST['visibility']); // Capture the visibility
    $journal = !empty($_POST['journal']) ? $conn->real_escape_string($_POST['journal']) : null;
    $volume = !empty($_POST['volume']) ? $conn->real_escape_string($_POST['volume']) : null;
    $pages = !empty($_POST['pages']) ? $conn->real_escape_string($_POST['pages']) : null;
    $doi = !empty($_POST['doi']) ? $conn->real_escape_string($_POST['doi']) : null;
    $publication_date = !empty($_POST['publicationDate']) ? $conn->real_escape_string($_POST['publicationDate']) : null;

    if (empty($id)) {
        $yearCode = date("Y");
        $increment = 1;

        $existingCodeQuery = $conn->query("SELECT archive_code FROM archive_list WHERE archive_code LIKE '$yearCode%' ORDER BY archive_code DESC LIMIT 1");
        if ($existingCodeQuery && $existingCodeQuery->num_rows > 0) {
            $lastCode = $existingCodeQuery->fetch_assoc()['archive_code'];
            $increment = (int)substr($lastCode, -4) + 1;
        }

        $archive_code = $yearCode . str_pad($increment, 4, '0', STR_PAD_LEFT);
        // Insert the new record including publication details
        $qry = $conn->query("INSERT INTO archive_list 
            (title, year, abstract, archive_code, student_id, curriculum_id, visibility, journal, volume, pages, doi, publication_date) 
            VALUES 
            ('$title', '$year', '$abstract', '$archive_code', '$student_id', '$curriculum_id', '$visibility', 
            " . ($journal ? "'$journal'" : "NULL") . ", 
            " . ($volume ? "'$volume'" : "NULL") . ", 
            " . ($pages ? "'$pages'" : "NULL") . ", 
            " . ($doi ? "'$doi'" : "NULL") . ", 
            " . ($publication_date ? "'$publication_date'" : "NULL") . ")");
        
        $id = $conn->insert_id; // Get the inserted ID
    } else {
        // Update the existing record including publication details
        $qry = $conn->query("UPDATE archive_list 
            SET 
                title='$title', 
                year='$year', 
                abstract='$abstract', 
                curriculum_id='$curriculum_id', 
                visibility='$visibility', 
                journal=" . ($journal ? "'$journal'" : "NULL") . ", 
                volume=" . ($volume ? "'$volume'" : "NULL") . ", 
                pages=" . ($pages ? "'$pages'" : "NULL") . ", 
                doi=" . ($doi ? "'$doi'" : "NULL") . ", 
                publication_date=" . ($publication_date ? "'$publication_date'" : "NULL") . " 
            WHERE id='$id'");
    }

    if ($id && $author_firstnames && $author_lastnames) {
        $conn->query("DELETE FROM archive_authors WHERE archive_id='$id'"); // Clear existing authors
        foreach ($author_firstnames as $key => $first_name) {
            $last_name = $author_lastnames[$key];
            $order = $key + 1;
            $conn->query("INSERT INTO archive_authors (archive_id, first_name, last_name, author_order) VALUES ('$id', '$first_name', '$last_name', '$order')");
        }
    }

    if (isset($_FILES['pdf']) && $_FILES['pdf']['tmp_name']) {
        $filePath = "uploads/pdf/archive-$id.pdf";
        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath)) {
            $conn->query("UPDATE archive_list SET document_path='$filePath' WHERE id='$id'");
        }
    }

    if ($qry) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Research study uploaded successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.href = './?page=view_archive&id=$id';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred while uploading the research study.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>