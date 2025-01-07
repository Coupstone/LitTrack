

<div class="container-fluid">
    <form action="" id="update_status_form">
        <input type="hidden" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : "" ?>">
        <div class="form-group">
            <label for="status" class="control-label text-navy">Status</label>
            <select name="status" id="status" class="form-control form-control-border" required>
                <option value="2" <?= isset($_GET['status']) && $_GET['status'] == 2 ? "selected" : "" ?>>Reject</option>
                <option value="1" <?= isset($_GET['status']) && $_GET['status'] == 1 ? "selected" : "" ?>>Approved</option>
            </select>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>
<script>
    $(function(){

        $('#update_status_form').submit(function(e){
            e.preventDefault();
            start_loader(); // Start preloader
            // var el = $('<div>');
            // el.addClass("pop-msg alert");
            // el.hide();
            
            // AJAX request to send email
            $.ajax({
                url: _base_url_ + "admin/archives/send_email.php",
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert("An error occurred while saving the data.", "error");
                    end_loader(); // End preloader even on error
                },
                success: function(resp){
                    end_loader(); // End preloader even on error
                    if(resp.status === 'success'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Status updated successfully!',
                            showConfirmButton: true
                        }).then(() => {
                            location.reload(); // Reload page on success
                        });
                    } else {
                        el.addClass("alert-danger");
                        el.text(resp.msg || "An error occurred due to unknown reason.");
                        $('#update_status_form').prepend(el); // Add message at the top of the form
                        el.show('slow'); // Show alert smoothly
                    }
                    end_loader(); // End preloader after response
                }
            });
        });
    });

    function start_loader() {
     Swal.fire({
         title: 'Processing, please wait...',
         html: 'This may take a moment.',
         didOpen: () => {
             Swal.showLoading()
         },
         allowOutsideClick: false,
         showConfirmButton: false
     });
 }


    function end_loader() {
        $('#preloader').fadeOut('fast', function () {
            $(this).remove();
        });
    }
</script>
