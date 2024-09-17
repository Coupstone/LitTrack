<?php require_once('./config.php'); ?>
<!DOCTYPE html>
<html lang="en" class="" style="height: auto;">
<?php require_once('inc/header.php') ?>
  <body class="layout-top-nav layout-fixed layout-navbar-fixed" style="height: auto;">
    <div class="wrapper">
     <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home';  ?>
     <?php require_once('inc/topBarNav.php') ?>
     <?php if($_settings->chk_flashdata('success')): ?>
      <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
      </script>
      <?php endif;?>    
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper pt-5" style="background-color: white;">
        <?php if($page == "home" || $page == "about_us"): ?>
          <div id="header" class="mb-4 d-flex flex-column align-items-center justify-content-center text-center">
              <img src="uploads/LitLogo.png" alt="PUP Logo" class="mb-3 img-fluid" style="max-width: 100%; height: auto;">
              <div class="search-bar mt-4 w-100 d-flex flex-column align-items-center">
                  <form id="search-form" class="d-flex w-100 flex-column flex-md-row align-items-center justify-content-center" action="search_results.php" method="GET">
                      <input type="search" id="search-input" class="form-control rounded-0 mb-2 mb-md-0" name="q" required placeholder="Search..." value="<?= isset($_GET['q']) ? $_GET['q'] : '' ?>" style="width: 100%; max-width: 400px;">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </form>
                  <div class="mt-2">
                  </div>
              </div>
          </div>
        <?php endif; ?>
        
        <!-- Main content -->
        <section class="content">
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
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div id="delete_content"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="modal fade" id="uni_modal_right" role='dialog'>
        <div class="modal-dialog modal-full-height modal-md" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="fa fa-arrow-right"></span>
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

    <script>
      $(function(){
        $('#search-form').submit(function(e){
          e.preventDefault();
          if($('#search-input').val().length == 0)
            location.href = './';
          else
            location.href = './?page=projects&q=' + encodeURI($('#search-input').val());
        });
        $('#search_icon').click(function(){
          $('#search-field').addClass('show')
          $('#search-input').focus();
        });
        $('#search-input').focusout(function(e){
          $('#search-field').removeClass('show')
        });
        $('#search-input').keydown(function(e){
          if(e.which == 13){
            e.preventDefault();
            $('#search-form').submit();
          }
        });
      });
    </script>
    

  </body>
</html>

<!-- Additional CSS for Modal Responsiveness -->
<style>
  @media (max-width: 768px) {
    .modal-dialog {
      max-width: 100%;
      margin: 0;
    }
    .modal-content {
      border-radius: 0;
    }
    .modal-header, .modal-body, .modal-footer {
      padding: 1rem;
    }
    .modal-header .close {
      padding: 0.5rem;
      margin: 0;
    }
  }

  #content {
            transition: margin-left 0.3s;
            margin-left: 250px; 
        }
  body.sidebar-collapsed #content {
            margin-left: 60px;
        }
  .student-img {
            object-fit: scale-down;
            object-position: center center;
            height: 200px;
            width: 200px;
        }
        .card-tools .btn {
            margin-left: 10px;
        }
        /* Ensure layout adapts well on smaller screens */
        @media (max-width: 768px) {
            body.sidebar-expanded #content {
                margin-left: 80px; /* For smaller screens, default is collapsed */
            }
        }
</style>
