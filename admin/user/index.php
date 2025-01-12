<?php 
$user = $conn->query("SELECT * FROM users where id ='".$_settings->userdata('id')."'");
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
?>
<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
    .student-img {
        object-fit: scale-down;
        object-position: center center;
        height: 200px;
        width: 200px;
        border-radius: 50%;
    }

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
    }

    .content {
        margin-top: 10px;
        transform: translateY(-3%);
    }

    .form-control, .form-control:focus {
        border-color: #ced4da;
        box-shadow: none;
    }

    .form-floating {
        margin-bottom: 16px;
    }

    .card {
        border-radius: 0;
    }

    .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .author-row .form-control {
        margin-right: 15px;
    }

    .author-row .form-control:last-child {
        margin-right: 0;
    }

    .author-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    #pdf-label {
        display: block;
        margin-bottom: 8px;
        font-weight: normal;
    }

    .form-control {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .form-label {
        font-weight: bold;
        display: block;
        margin-bottom: 0.5rem;
    }

    .optional-text {
        font-size: 0.875rem;
        color: #6c757d;
        margin-left: 2px;
        vertical-align: middle;
    }

    .card-tools .btn.bg-primary {
        background-color: #5DA8F9 !important;
        border-color: #5DA8F9 !important;
        color: white !important;
    }

    .card-tools .btn.bg-navy {
        background-color: #1E3E66 !important;
        border-color: #1E3E66 !important;
        color: white !important;
    }

    .card-header {
        background-color: #fff;
    }

    .card-title {
        font-weight: bold;
        transform: translateY(20%);
    }
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
	.control-label{
		font-size: small;
	}
</style>

<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update or Upload Research</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
</head>
<body>
<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0 ">
        <div class="card-header rounded-0" style="background-color: white">
            <h6 class="card-title">Update Details</h6>
        </div>
        <div class="container-fluid">
            <div id="msg"></div>
            <form action="" id="manage-user">
                <input type="hidden" name="id" value="<?php echo $_settings->userdata('id') ?>">
                
                <div class="form-group">
                    <label for="firstname" class="control-label text-navy">First Name</label>
                    <input type="text" name="firstname" id="firstname" class="form-control form-control-border" value="<?php echo isset($meta['firstname']) ? $meta['firstname']: '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="lastname" class="control-label text-navy">Last Name</label>
                    <input type="text" name="lastname" id="lastname" class="form-control form-control-border" value="<?php echo isset($meta['lastname']) ? $meta['lastname']: '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="username" class="control-label text-navy">Username</label>
                    <input type="text" name="username" id="username" class="form-control form-control-border" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="password" class="control-label text-navy">Password</label>
                    <input type="password" name="password" id="password" class="form-control form-control-border" value="" autocomplete="off">
                    <small><i>Leave this blank if you don’t want to change the password.</i></small>
                </div>
                
                <div class="form-group">
                    <label for="img" class="control-label text-navy">Avatar</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img" onchange="displayImg(this,$(this))">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                
                <div class="form-group d-flex justify-content-center">
                    <img src="<?php echo validate_image(isset($meta['avatar']) ? $meta['avatar'] :'') ?>" alt="" id="cimg" class="img-fluid img-thumbnail student-img bg-gradient-dark border">
                </div>
            </form>
        </div>
    </div>
    
    <div class="card-footer rounded-0">
        <div class="col-md-12">
		<div class="row justify-content-center">
    <button class="btn btn-sm btn-primary col-1" form="manage-user">Update</button>
</div>
        </div>
    </div>
</div>


<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-user').submit(function(e){
		e.preventDefault();
var _this = $(this)
		start_loader()
		$.ajax({
			url:_base_url_+'classes/Users.php?f=save',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp ==1){
					location.reload()
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_loader()
				}
			}
		})
	})

</script>
</body>
</html>
