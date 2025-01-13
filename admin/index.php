<?php require_once('../config.php');
?>
 <!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
  <body class="sidebar-mini layout-fixed control-sidebar-slide-open layout-navbar-fixed sidebar-mini-md sidebar-mini-xs" data-new-gr-c-s-check-loaded="14.991.0" data-gr-ext-installed="" style="height: auto;">
    <div class="wrapper">

     <?php require_once('inc/navigations.php') ?>
     <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper pt-3" style="min-height: 567.854px;">
     
        <!-- Main content -->
        <section class="content ">
          <div class="container-fluid">
            <?php 
              if(!file_exists($page.".php") && !is_dir($page)){
                  include '404.html';
              }else{
                if(is_dir($page))
                  include $page.'/index.php';
                else
                  include $page.'.php';

              }
            ?>
          </div>
        </section>
        <!-- /.content -->
  <div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-flat" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-flat" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-right"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="viewer_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
              <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
              <img src="" alt="">
      </div>
    </div>
  </div>
      </div>
      <!-- /.content-wrapper -->
      <?php require_once('inc/footer.php') ?>
  </body>
</html>
<script>
// When a modal is triggered
$('.modal').on('show.bs.modal', function () {
    $('body').addClass('modal-open'); // Add modal-open class
    $('.overlay-dark').fadeIn(); // Show the overlay
});

// When a modal is closed
$('.modal').on('hidden.bs.modal', function () {
    $('body').removeClass('modal-open'); // Remove modal-open class
    $('.overlay-dark').fadeOut(); // Hide the overlay
});
</script>

<style>
/* Darken the whole screen when modal opens */
.overlay-dark {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Dark background */
    z-index: 1030; /* Behind the modal but in front of the page content */
    display: none; /* Hidden by default, shown when modal opens */
}

/* Add transition for smooth fade */
.overlay-dark {
    transition: opacity 0.3s ease-in-out;
}

/* Modal Dialog and Content */
.modal-dialog {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    margin: 0 auto;
    z-index: 1050; /* Ensure modal is above the backdrop */
}

/* Optional: Max width for modal content */
.modal-content {
    max-width: 600px;
    width: 100%;
    z-index: 1060; /* Ensure content is above the backdrop */
}

/* Ensure the modal content looks good */
.modal-header {
    background-color: #800000;
    color: white;
    padding: 15px;
    border-radius: 15px 15px 0 0;
}

.modal-footer {
    background-color: #f1f1f1;
    padding: 10px;
    border-radius: 0 0 15px 15px;
}

.modal-body {
    padding: 20px;
}

/* Modal body image styling */
#viewer_modal .modal-body img {
    max-width: 100%;
    max-height: 80vh;
    display: block;
    margin: 0 auto;
}

/* Close button in viewer modal */
#viewer_modal .btn-close {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}

/* Make the modal close button large */
.close {
    font-size: 1.5em;
    color: #800000;
}

.modal-dialog-centered {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* For full-screen modal */
.modal-full-height {
    height: 100%;
    max-height: 100%;
}

.modal-md {
    max-width: 800px;
    width: 100%;
}

html {
    height: 100%; /* Ensure the page is tall enough */
}

/* Prevent page content from scrolling */
body.modal-open {
    overflow: hidden;
}

</style>
