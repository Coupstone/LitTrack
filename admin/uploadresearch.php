<?php
// Ensure user is logged in and uploader ID is available
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "<script>alert('Uploader ID not set. Please log in again.'); location.replace('./login.php');</script>";
    exit();
}

$uploader_id = $_SESSION['user_id']; // Get the logged-in user's ID

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $qry = $conn->query("SELECT * FROM `archive_list` WHERE id = '{$_GET['id']}'");
    if ($qry->num_rows) {
        foreach ($qry->fetch_array() as $k => $v) {
            if (!is_numeric($k)) {
                $$k = $v;
            }
        }
    }
    if (isset($uploader_id) && isset($id)) {
        if ($uploader_id != $_settings->userdata('user_id')) { // Check access rights
            echo "<script>alert('You don\'t have access to this page'); location.replace('./');</script>";
            exit();
        }
    }
}
?>
<!-- Include SweetAlert Library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="dist/css/adminlte.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        }

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
    max-width: 2000px; /* Adjust width as necessary */
    width: 103%; /* Use full width for smaller screens */
    margin: 10px; /* Reduced margin around the container */
    padding: 10px; /* Reduced padding inside the container for a compact look */
    transform: translateY(0%); /* Moves the container up by 10% of its height */
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
        width: 90%;
    }
    .card-header {
        background-color: #800000; 
        color: white;
        padding: 15px;
        border-radius: 15px 15px 0 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

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
    .card-header h3 {
    font-size: 1.25em;
    font-weight: bold;
}

</style>

</head>
<body>


<div class="container h-100 d-flex justify-content-center align-items-center mt-5">
    <div class="card card-outline card-primary shadow">
        <div class="card-header">
            <h3 class="card-title">UPLOAD RESEARCH</h3>
        </div>
        <div class="card-body">
            <form action="" id="archive-form" enctype="multipart/form-data" method="POST" class="needs-validation" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
                    <label for="title" id="title-label">Research Title</label>
                </div>
                <div class="form-floating mb-3">
    <select class="form-control" id="year" name="year" required>
        <?php for ($i = 0; $i < 51; $i++): ?>
            <option value="<?= date("Y", strtotime(date("Y")." -{$i} years")) ?>" <?= isset($year) && $year == date("Y", strtotime(date("Y")." -{$i} years")) ? 'selected' : '' ?>>
                <?= date("Y", strtotime(date("Y")." -{$i} years")) ?>
            </option>
        <?php endfor; ?>
    </select>
    <label for="year">Year</label>
</div>
                <div class="form-floating mb-3">
                    <textarea class="form-control" id="abstract" name="abstract" placeholder="Abstract" required rows="5"><?= isset($abstract) ? html_entity_decode($abstract) : '' ?></textarea>
                    <label for="abstract" id="abstract-label">Abstract</label>
                </div>
                <div id="author-container">
                    <h6><strong>Authors</strong></h6>
                    <div class="author-row d-flex align-items-center mb-2">
                        <input type="text" class="form-control" name="author_firstname[]" placeholder="First Name" required>
                        <input type="text" class="form-control" name="author_lastname[]" placeholder="Last Name" required>
                        <button type="button" class="btn btn-success btn-sm" onclick="addAuthorRow()">+</button>
                    </div>
                </div>
                    <!-- Project Document -->
                    <div class="form-group mb-3">
                        <label for="pdf" class="control-label text-muted"><strong>Research Document</strong> (PDF, maximum of 25MB)</label>
                        <input type="file" id="pdf" name="pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
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
                <!-- Publication Details Form (initially hidden) -->
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

                    <!-- Submit Button -->
                    <div class="form-group text-center">
                        <button class="btn btn-default bg-navy btn-flat">Upload</button>
                        <a href="./" class="btn btn-light border btn-flat">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (Optional for Bootstrap 5) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Bootstrap 5 Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

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
    $title = $_POST['title'];
    $year = $_POST['year'];
    $abstract = htmlentities($_POST['abstract']);
    $curriculum_id = $_settings->userdata('curriculum_id') ?? 0;
    $author_firstnames = $_POST['author_firstname'];
    $author_lastnames = $_POST['author_lastname'];

    try {
        if (empty($id)) {
            // Generate archive code
            $yearCode = date("Y");
            $increment = 1;

            $existingCodeQuery = $conn->query("SELECT archive_code FROM archive_list WHERE archive_code LIKE '$yearCode%' ORDER BY archive_code DESC LIMIT 1");
            if ($existingCodeQuery && $existingCodeQuery->num_rows > 0) {
                $lastCode = $existingCodeQuery->fetch_assoc()['archive_code'];
                $increment = (int)substr($lastCode, -4) + 1;
            }

            $archive_code = $yearCode . str_pad($increment, 4, '0', STR_PAD_LEFT);
            $qry = $conn->prepare("INSERT INTO archive_list (title, year, abstract, archive_code, uploader_id, curriculum_id) VALUES (?, ?, ?, ?, ?, ?)");
            $qry->bind_param("ssssii", $title, $year, $abstract, $archive_code, $uploader_id, $curriculum_id);
            $qry->execute();
            $id = $qry->insert_id;
        } else {
            $qry = $conn->prepare("UPDATE archive_list SET title=?, year=?, abstract=?, curriculum_id=? WHERE id=?");
            $qry->bind_param("ssssi", $title, $year, $abstract, $curriculum_id, $id);
            $qry->execute();
        }

        // Insert authors
        if ($id && $author_firstnames && $author_lastnames) {
            $conn->query("DELETE FROM archive_authors WHERE archive_id='$id'");
            foreach ($author_firstnames as $key => $first_name) {
                $last_name = $author_lastnames[$key];
                $order = $key + 1;
                $qry = $conn->prepare("INSERT INTO archive_authors (archive_id, first_name, last_name, author_order) VALUES (?, ?, ?, ?)");
                $qry->bind_param("issi", $id, $first_name, $last_name, $order);
                $qry->execute();
            }
        }

        // File upload with PDF validation
        if (isset($_FILES['pdf']) && $_FILES['pdf']['tmp_name']) {
            $fileType = mime_content_type($_FILES['pdf']['tmp_name']);
            $allowedType = "application/pdf";
        
            if ($fileType !== $allowedType) {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Only PDF files are allowed. Please upload a valid PDF file.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
                exit();
            }
        
            // Define the absolute path to the 'uploads/pdf/' folder
            $base_upload_dir = __DIR__ . '/../uploads/pdf/'; // Moves one directory up from 'admin'
            $filePath = $base_upload_dir . "archive-$id.pdf";
        
            // Ensure the directory exists
            if (!file_exists($base_upload_dir)) {
                mkdir($base_upload_dir, 0777, true);
            }
        
            // Move the uploaded file
            if (move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath)) {
                // Save the relative path in the database
                $relativePath = "uploads/pdf/archive-$id.pdf";
                $updateFilePathQuery = $conn->prepare("UPDATE archive_list SET document_path = ? WHERE id = ?");
                $updateFilePathQuery->bind_param("si", $relativePath, $id);
                $updateFilePathQuery->execute();
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to upload the document. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
        }

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
    } catch (Exception $e) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'An error occurred: " . $conn->error . "',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
