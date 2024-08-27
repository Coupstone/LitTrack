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
    }
    
    $submitted = "N/A";
    if (isset($student_id)) {
        $stmt = $conn->prepare("SELECT email FROM student_list WHERE id = ?");
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $student = $result->fetch_assoc();
            $submitted = $student['email'];
        }
    }
}
?>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .content {
        padding: 30px;
        background-color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
       
    }
    .card-header {
        background-color: #a8001d;
        color: #ffffff;
        padding: 20px;

    }
    .card-title {
        font-size: 2rem;
        margin: 0;
        font-weight: bold;
    }
    .card-body {
        padding: 20px;
    }
    .btn-flat {
        border-radius: 0;
        padding: 8px 16px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .btn-navy {
        background-color: #a8001d; 
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-navy:hover {
        background-color: #80001a; 
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
    }
    .btn-danger {
        background-color: #dc3545;
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .btn-danger:hover {
        background-color: #c82333;
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
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
        background: linear-gradient(135deg, #343a40 0%, #000000 100%);
    }
    .img-fluid {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .fieldset {
        margin-bottom: 20px;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .legend {
        font-size: 1.5rem;
        font-weight: bold;
        color: #a8001d; 
        border-bottom: 2px solid #80001a;
        padding-bottom: 10px;
        margin-bottom: 10px;
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
    }
    .doc-controls {
        margin-top: 20px;
        text-align: center;
    }
    .doc-controls button {
        margin: 0 10px;
        padding: 10px 20px;
        font-size: 0.875rem;
    }
    .table-shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
<div class="content py-4">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow rounded-0">
            <div class="card-header">
                <h3 class="card-title">
                    Archive - <?= $archive_code ?? "" ?>
                </h3>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <h2 class="font-weight-bold"><?= $title ?? "" ?></h2>
                    <small class="text-muted">Submitted by <b class="text-info"><?= $submitted ?></b> on <?= date("F d, Y h:i A", strtotime($date_created)) ?></small>
                    <?php if (isset($student_id) && $_settings->userdata('login_type') == "2" && $student_id == $_settings->userdata('id')): ?>
                        <div class="form-group mt-3">
                            <a href="./?page=submit-archive&id=<?= $id ?? "" ?>" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-edit"></i> Edit</a>
                            <button type="button" data-id="<?= $id ?? "" ?>" class="btn btn-flat btn-danger btn-sm delete-data"><i class="fa fa-trash"></i> Delete</button>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <div class="text-center">
                        <img src="<?= validate_image($banner_path ?? "") ?>" alt="Banner Image" id="banner-img" class="img-fluid border bg-gradient-dark">
                    </div>
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
                        <div class="pl-4"><large><?= html_entity_decode($members ?? "") ?></large></div>
                    </fieldset>
                    <fieldset class="fieldset">
                        <legend class="legend">Project Document:</legend>
                        <div class="pl-4">
                            <iframe src="<?= base_url . ($document_path ?? "") ?>" frameborder="0" id="document_field" class="text-center w-100">Loading Document ...</iframe>
                            <div class="doc-controls">
                                <button onclick="window.print()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-print"></i> Print</button>
                                <button onclick="downloadDocument()" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-download"></i> Download</button>
                                <a href="<?= base_url . ($document_path ?? "") ?>" target="_blank" class="btn btn-flat btn-navy btn-sm"><i class="fa fa-eye"></i> View</a>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $('.delete-data').click(function() {
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
            success: function(resp) {
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.replace("./");
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }

    function downloadDocument() {
        const url = "<?= base_url . ($document_path ?? "") ?>";
        const link = document.createElement('a');
        link.href = url;
        link.download = url.substring(url.lastIndexOf('/') + 1);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
