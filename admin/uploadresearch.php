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

<style>
    .banner-img {
        object-fit: scale-down;
        object-position: center center;
        height: 30vh;
        width: calc(100%);
    }
    .author-row {
        margin-top: 10px;
    }
    .author-row input[type="text"] {
        width: 45%;
        margin-right: 15px;
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
                        <label class="form-label">Publication Details</label><br>
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
