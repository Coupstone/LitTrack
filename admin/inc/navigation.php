<style>
    body, p, a, span, li, h1, h2, h3, h4, h5, h6 {
      font-weight: 600;
    }
    .main-sidebar .nav-sidebar .nav-link p,
    .main-sidebar .nav-sidebar .nav-header,
    .main-sidebar .nav-sidebar .brand-text {
      font-weight: 700;
    }
    .main-sidebar .os-content {
      margin-top: 0; 
    }
    .main-sidebar .nav-sidebar .nav-item:first-child {
      margin-top: 0.5rem; 
    }
    .brand-text {
      margin-left: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center; 
    }
    .brand-text .littrack-text {
      font-family: 'Georgia', serif; 
      font-size: 1.2rem; 
      background: linear-gradient(to bottom, #007bff, #4e4e4e, #b81d24); 
      -webkit-background-clip: text; 
      color: transparent;
      font-weight: 800; 
      margin-top: -0.4rem; 
      line-height: 1; 
    }
    .brand-link {
      display: flex;
      align-items: center;
      transition: all 0.3s ease-in-out;
    }
    .sidebar-mini.sidebar-collapse .brand-link .brand-image {
      margin-left: auto;
      margin-right: auto;
      display: block;
    }
    .sidebar-mini.sidebar-collapse .brand-link .brand-text {
      display: none; 
    }
    .nav-link.active {
      background-color: #007bff; 
      color: white !important; 
    }
    .nav-link.active i {
      color: white !important; 
    }
  </style>
</head>
<body>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light elevation-4 sidebar-no-expand bg-white">
    <!-- Brand Logo -->
    <a href="<?php echo base_url ?>admin" class="brand-link bg-transparent text-sm shadow-sm d-flex align-items-center">
      <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 2.5rem; height: 2.5rem; max-height: unset; object-fit: scale-down; object-position: center center;">
      <span class="brand-text font-weight-light">
        <span style="font-family: 'Georgia', serif; font-size: 0.9rem; background: linear-gradient(to right, #651d32, #b81d24); -webkit-background-clip: text; color: transparent; font-weight: normal;">PUP Sta. Rosa</span>
        <span class="littrack-text">LitTrack</span>
      </span>
    </a>  

    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
      <div class="os-resize-observer-host observed">
        <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
      </div>
      <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
        <div class="os-resize-observer"></div>
      </div>
      <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
      <div class="os-padding">
        <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
          <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
            <!-- Sidebar user panel (optional) -->
            <div class="clearfix"></div>
            <!-- Sidebar Menu -->
            <nav class="mt-4">
              <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item dropdown">
                  <a href="./" class="nav-link nav-home <?php echo ($page == 'index') ? 'active' : '';?>">
                    <i class="nav-icon fas fa-home"></i>
                    <p class="text-dark">
                      Home
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=archives" class="nav-link nav-archives <?php echo ($page == 'archives') ? 'active' : '';?>">
                  <i class="nav-icon fas fa-project-diagram"></i>
                    <p class="text-dark">
                      Researches
                    </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo base_url ?>admin/?page=students" class="nav-link nav-students <?php echo ($page == 'students') ? 'active' : '';?>">
                    <i class="nav-icon fas fa-user text-dark"></i>
                    <p class="text-dark">
                      Student
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=departments" class="nav-link nav-departments <?php echo ($page == 'departments') ? 'active' : '';?>">
                    <i class="nav-icon fas fa-th-list text-dark"></i>
                    <p class="text-dark">
                      Department
                    </p>
                  </a>
                </li>
                <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=curriculum" class="nav-link nav-curriculum <?php echo ($page == 'curriculum') ? 'active' : '';?>">
                    <i class="nav-icon fas fa-map text-dark"></i>
                    <p class="text-dark">
                      Courses
                    </p>
                  </a>
                </li>
                <!-- <li class="nav-item dropdown">
                  <a href="<?php echo base_url ?>admin/?page=system_info" class="nav-link nav-system_info <?php echo ($page == 'system_info') ? 'active' : '';?>">
                    <i class="nav-icon fas fa-tools #007bfftext-dark"></i>
                    <p class="text-dark">
                      System Settings
                    </p>
                  </a>
                </li> -->
                <!-- Add Upload Research to the Sidebar -->
                  <li class="nav-item">
                    <a href="<?php echo base_url ?>/admin/?page=uploadresearch" class="nav-link nav-upload <?php echo ($page == 'uploadresearch') ? 'active' : '';?>">
                      <i class="nav-icon fas fa-upload text-dark"></i>
                      <p class="text-dark">
                        Upload Research
                      </p>
                    </a>
                  </li>
                  <script>
                      document.querySelector('.nav-upload').addEventListener('click', function(event) {
                        event.preventDefault();
                        const currentPage = window.location.href;

                        // Check if the current page is already the upload research page
                        if (!currentPage.includes('uploadresearch')) {
                          window.location.href = "<?php echo base_url ?>admin/?page=uploadresearch";
                        }
                      });
                          </script>


              </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
        </div>
      </div>
      <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
        <div class="os-scrollbar-track">
          <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
        </div>
      </div>
      <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
        <div class="os-scrollbar-track">
          <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
        </div>
      </div>
      <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
  </aside>