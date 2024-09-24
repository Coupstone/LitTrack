<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * FROM `archive_list` where id = '{$_GET['id']}'");
    if($qry->num_rows){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
    if(isset($student_id)){
        if($student_id != $_settings->userdata('id')){
            echo "<script> alert('You don\'t have an access to this page'); location.replace('./'); </script>";
        }
    }
}
?>
<style>
    .banner-img{
		object-fit:scale-down;
		object-position:center center;
        height:30vh;
        width:calc(100%);
	}
    .input-line {
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0;
    box-shadow: none;
    outline: none;
    resize: none; 
    overflow: hidden;
    height: auto; 
    }
.input-line:focus {
    border-bottom: 2px solid #007bff; 
    outline: none;
    box-shadow: none;
}

.no-scroll {
    height: auto; 
    min-height: 150px; 
}
</style>
<div class="content py-4">
    <div class="card card-outline card-primary shadow rounded-0">
        <div class="card-header rounded-0">
            <h5 class="card-title"><?= isset($id) ? "Update Archive-{$archive_code} Details" : "Submit Project" ?></h5>
        </div>
        <div class="card-body rounded-0">
            <div class="container-fluid">
                <form action="" id="archive-form">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : "" ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="title" class="control-label text-navy">Project Title</label>
                                <input type="text" name="title" id="title" autofocus placeholder="Project Title" class="form-control form-control-border" value="<?= isset($title) ?$title : "" ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="year" class="control-label text-navy">Year</label>
                                <select name="year" id="year" class="form-control form-control-border" required>
                                    <?php 
                                        for($i= 0;$i < 51; $i++):
                                    ?>
                                    <option <?= isset($year) && $year == date("Y",strtotime(date("Y")." -{$i} years")) ? "selected" : "" ?>><?= date("Y",strtotime(date("Y")." -{$i} years")) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="abstract">Abstract</label>
                        <textarea class="form-control input-line no-scroll" id="abstract" rows="6" placeholder="abstract"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="projectMembers">Project Members</label>
                        <textarea class="form-control input-line no-scroll" id="projectMembers" rows="6" placeholder="members"></textarea>
                    </div>

                    <script>
                        document.addEventListener('input', function (event) {
                            if (event.target.classList.contains('no-scroll')) {
                                event.target.style.height = 'auto'; 
                                event.target.style.height = event.target.scrollHeight + 'px'; 
                            }
                        });
                    </script>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="img" class="control-label text-muted">Project Image/Banner Image</label>
                                <input type="file" id="img" name="img" class="form-control form-control-border" accept="image/png,image/jpeg" onchange="displayImg(this,$(this))" <?= !isset($id) ? "required" : "" ?>>
                            </div>

                            <div class="form-group text-center">
                                <img src="<?= validate_image(isset($banner_path) ? $banner_path : "") ?>" alt="My Avatar" id="cimg" class="img-fluid banner-img bg-gradient-dark border">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="pdf" class="control-label text-muted">Project Document (PDF File Only)</label>
                                <input type="file" id="pdf" name="pdf" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group text-center">
                                <button class="btn btn-default bg-navy btn-flat"> Update</button>
                                <a href="./?page=profile" class="btn btn-light border btn-flat"> Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
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
	    }else{
            $('#cimg').attr('src', "<?= validate_image(isset($avatar) ? $avatar : "") ?>");
        }
	}
    $(function(){
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                ['insert', ['link', 'picture']],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })
        $('.summernote-list-only').summernote({
            height: 200,
            toolbar: [
                [ 'font', [ 'bold', 'italic', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ]
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul' ] ],
                [ 'view', [ 'undo', 'redo', 'help' ] ]
            ]
        })
        // Archive Form Submit
        $('#archive-form').submit(function(e){
            e.preventDefault()
            var _this = $(this)
                $(".pop-msg").remove()
            var el = $("<div>")
                el.addClass("alert pop-msg my-2")
                el.hide()
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_archive",
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType:'json',
                error:err=>{
                    console.log(err)
                    el.text("An error occured while saving the data")
                    el.addClass("alert-danger")
                    _this.prepend(el)
                    el.show('slow')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href= "./?page=view_archive&id="+resp.id
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }else{
                        el.text("An error occured while saving the data")
                        el.addClass("alert-danger")
                        _this.prepend(el)
                        el.show('show')
                    }
                    end_loader();
                    $('html, body').animate({scrollTop: 0},'fast')
                }
            })
        })
    })
</script>