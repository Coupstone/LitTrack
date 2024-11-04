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
    $curriculum_id = $_settings->userdata('curriculum_id'); // Automatically retrieve user's curriculum_id
    $author_firstnames = $_POST['author_firstname'];
    $author_lastnames = $_POST['author_lastname'];
    $student_id = $_settings->userdata('id');

    if (empty($id)) {
        $yearCode = date("Y");
        $increment = 1;

        $existingCodeQuery = $conn->query("SELECT archive_code FROM archive_list WHERE archive_code LIKE '$yearCode%' ORDER BY archive_code DESC LIMIT 1");
        if ($existingCodeQuery && $existingCodeQuery->num_rows > 0) {
            $lastCode = $existingCodeQuery->fetch_assoc()['archive_code'];
            $increment = (int)substr($lastCode, -4) + 1;
        }

        $archive_code = $yearCode . str_pad($increment, 4, '0', STR_PAD_LEFT);
        $qry = $conn->query("INSERT INTO archive_list (title, year, abstract, archive_code, student_id, curriculum_id) VALUES ('$title', '$year', '$abstract', '$archive_code', '$student_id', '$curriculum_id')");
        $id = $conn->insert_id;
    } else {
        $qry = $conn->query("UPDATE archive_list SET title='$title', year='$year', abstract='$abstract', curriculum_id='$curriculum_id' WHERE id='$id'");
    }

    if ($id && $author_firstnames && $author_lastnames) {
        $conn->query("DELETE FROM archive_authors WHERE archive_id='$id'");
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
