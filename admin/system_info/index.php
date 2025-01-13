<?php check_login();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Information Update</title>
    <style>
        .img-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            object-position: center center;
            border-radius: 50%;
        }

        .card-outline.card-primary {
            border-color: #800000; 
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
        }
        .card-header h5 {
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
            border-radius: 0 0 15px 15px;
            background-color: #ffffff !important; 
        }

        .card-body.transparent {
            background-color: transparent !important; 
        }

        .table-container {
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
            border-radius: 15px;
            overflow: hidden;
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
            padding: 12px 15px;
            border: none;
            border-bottom: 2px solid #dee2e6;
        }

        .table th {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }

        .badge-pill {
            font-size: 0.85em;
            padding: 0.5em 1em;
            font-weight: bold;
            border-radius: 50px;
        }

        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table td {
                display: block;
                width: 100%;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #dee2e6;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 15px;
                text-align: left;
                font-weight: bold;
                color: #343a40;
            }
        }

        img#cimg {
            height: 15vh;
            width: 15vh;
            object-fit: scale-down;
            border-radius: 100%;
        }

        img#cimg2 {
            height: 50vh;
            width: 100%;
            object-fit: contain;
        }

        .btn-rounded {
            border-radius: 50px;
        }

        .btn-primary {
            font-weight: bold;
        }

        .form-control-sm {
            background-color: transparent;
        }
    </style>
</head>
<body>

<?php if($_settings->chk_flashdata('success')): ?>
<script>
    alert_toast("<?php echo $_settings->flashdata('success') ?>",'success');
</script>
<?php endif;?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h5 class="card-title">System Information</h5>
        </div>
        <div class="card-body transparent">
            <form action="update_system_info.php" id="system-frm" method="POST" enctype="multipart/form-data">
                <div id="msg" class="form-group"></div>
                <div class="form-group">
                    <label for="name" class="control-label">System Name</label>
                    <input type="text" class="form-control form-control-sm" name="name" id="name" value="<?php echo $_settings->info('name') ?>">
                </div>
                <div class="form-group">
                    <label for="short_name" class="control-label">System Short Name</label>
                    <input type="text" class="form-control form-control-sm" name="short_name" id="short_name" value="<?php echo $_settings->info('short_name') ?>">
                </div>
                <div class="form-group">
                    <label for="content[welcome]" class="control-label">Welcome Content</label>
                    <textarea class="form-control form-control-sm summernote" name="content[welcome]" id="welcome"><?php echo is_file(base_app.'welcome.html') ? file_get_contents(base_app.'welcome.html') : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label for="content[about_us]" class="control-label">About Us</label>
                    <textarea class="form-control form-control-sm summernote" name="content[about_us]" id="about_us"><?php echo is_file(base_app.'about_us.html') ? file_get_contents(base_app.'about_us.html') : '' ?></textarea>
                </div>
                <div class="form-group">
                    <label for="logo" class="control-label">System Logo</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo" name="img" onchange="displayImg(this)">
                        <label class="custom-file-label" for="logo">Choose file</label>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                </div>
                <div class="form-group">
                    <label for="cover" class="control-label">Website Cover</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="cover" name="cover" onchange="displayImg2(this)">
                        <label class="custom-file-label" for="cover">Choose file</label>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image($_settings->info('cover')) ?>" alt="" id="cimg2" class="img-fluid img-thumbnail">
                </div>
                <div class="form-group d-flex justify-content-center">
                    <button class="btn btn-sm btn-primary btn-rounded" type="submit">Update</button>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <!-- Footer content if needed -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.min.js"></script>

<script>
    function displayImg(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function displayImg2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

</body>
</html>
