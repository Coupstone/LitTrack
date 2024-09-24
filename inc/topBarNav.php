<?php
require_once './config.php';

if (isset($_settings) && method_exists($_settings, 'userdata')) {
    $userId = $_settings->userdata('id');
} else {
    $userId = null; 
}
?>
    <!-- Site Icon -->
    <link rel="icon" href="uploads/LitTrack.png" type="image/png"/>
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
    .navbar-nav {
        position: relative;
        z-index: 1030;
        transition: none;
    }
    body.sidebar-collapsed .navbar-nav {
        margin-left: 60px;
    }
    body.sidebar-expanded .navbar-nav {
        margin-left: 250px;
    }
    .main-sidebar {
        width: 250px;
        transition: margin-left 0.3s ease, width 0.3s ease;
    }
    body.sidebar-collapsed .main-sidebar {
        width: 60px;
        margin-left: 0;
    }
    body.sidebar-expanded .main-sidebar {
        width: 250px;
        margin-left: 0;
    }
    .main-sidebar .nav-link p {
        display: inline;
    }
    body.sidebar-collapsed .main-sidebar .nav-link p {
        display: none;
    }
    .main-sidebar .nav-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    body.sidebar-collapsed .main-sidebar .nav-link i {
        margin-right: 0;
        text-align: center;
        width: 100%;
    }
    .brand-link {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        transition: padding 0.3s ease;
        height: 3.5rem;
        overflow: hidden;
    }
    .brand-link .brand-image {
        width: 2.5rem;
        height: 2.5rem;
        transition: width 0.3s ease, height 0.3s ease;
        margin-right: 0.5rem;
    }
    .brand-link .brand-text {
        font-size: 1rem;
        transition: opacity 0.3s ease;
        white-space: nowrap;
    }
    body.sidebar-collapsed .brand-link {
        padding: 0.5rem;
    }
    body.sidebar-collapsed .brand-link .brand-image {
        width: 2rem;
        height: 2rem;
        margin-right: 0;
    }
    body.sidebar-collapsed .brand-link .brand-text {
        opacity: 0;
        overflow: hidden;
    }
    body.sidebar-expanded .brand-link .brand-text {
        opacity: 1;
    }
</style>
<body class="sidebar-expanded">
 <!-- Sidebar -->
 <nav class="main-sidebar sidebar-light-dark">
        <div class="brand-link" id="brand-toggle">
            <!-- Brand Logo -->
            <img src="uploads/pupLogo.jfif" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 2.5rem; height: 2.5rem; max-height: unset; object-fit: scale-down; object-position: center center;">
            <span class="brand-text font-weight-light mr-3">
                <span style="font-family: 'Georgia', serif; font-size: 0.9rem; background: linear-gradient(to right, #651d32, #b81d24); -webkit-background-clip: text; color: transparent; font-weight: normal;">PUP Sta. Rosa</span>
                <span class="littrack-text">LitTrack</span>
            </span>
        </div>

        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Home Link -->
                    <li class="nav-item mt-4">
                        <a href="./index.php" class="nav-link <?= isset($page) && $page == 'home' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Home</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="./advance-search.php" class="nav-link mx-1 <?= isset($page) && $page == 'advance-search' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            <p class ="mx-2">Advanced Search</p>
                        </a>
                    </li>

                    <?php if($_settings->userdata('id') > 0): ?>
                    <li class="nav-item mt-1">
                        <a href="./?page=profile" class="nav-link <?= isset($page) && $page == 'profile' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user"></i>
                            <p>My Profile</p>
                        </a>
                    </li>

                    <li class="nav-item mx-1">
                        <a href="./library.php" class="nav-link d-flex align-items-center <?= isset($page) && $page == 'library' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                        </svg>
                            <p class ="mx-3">My Library</p>
                        </a>
                    </li>

                    <!-- Projects Link -->
                    <li class="nav-item mt-1">
                        <a href="./?page=projects" class="nav-link <?= isset($page) && ($page == 'projects' || $page == 'view_archive') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>Researches</p>
                        </a>
                    </li>

                    <li class="nav-item mt-1">
                        <a href="./Litmaps.php" class="nav-link mx-1 <?= isset($page) && $page == 'Litmaps' ? 'active' : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                        </svg>
                            <p class ="mx-2">Literature Mapping</p>
                        </a>
                    </li>

                    <!-- Department Dropdown -->
                    <!-- <li class="nav-item has-treeview">
                        <a href="#" class="nav-link <?= isset($page) && $page == 'projects_per_department' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-building"></i>
                            <p>
                                Department
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php 
                            $departments = $conn->query("SELECT * FROM department_list WHERE status = 1 ORDER BY `name` ASC");
                            while($row = $departments->fetch_assoc()): 
                            ?>
                            <li class="nav-item">
                                <a href="./?page=projects_per_department&id=<?= $row['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= ucwords($row['name']) ?></p>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </li> -->

                    <!-- Courses Dropdown -->
                    <!-- <li class="nav-item has-treeview">
                        <a href="#" class="nav-link <?= isset($page) && $page == 'projects_per_curriculum' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Courses
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php 
                            $curriculums = $conn->query("SELECT * FROM curriculum_list WHERE status = 1 ORDER BY `name` ASC");
                            while($row = $curriculums->fetch_assoc()): 
                            ?>
                            <li class="nav-item">
                                <a href="./?page=projects_per_curriculum&id=<?= $row['id'] ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p><?= ucwords($row['name']) ?></p>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </li> -->

                    <!-- About Us Link -->
                    <!-- <li class="nav-item">
                        <a href="./?page=about" class="nav-link <?= isset($page) && $page == 'about' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-info-circle"></i>
                            <p>About Us</p>
                        </a>
                    </li> -->


                    <li class="nav-item">
                        <a href="./?page=submit-archive" class="nav-link <?= isset($page) && $page == 'submit-archive' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-upload"></i>
                            <p>Upload Research</p>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <br><br><br><br><br><br><br><br><br><br><br><br><br>
                <div class="text-center mt-4">
                  <a href="<?= base_url.'classes/Login.php?f=student_logout' ?>" class="btn btn-dark" style="color: white;"><i class="fa fa-power-off"></i></a>
                </div>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </nav>



    <!-- JavaScript to Toggle Sidebar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const brandToggle = document.getElementById('brand-toggle');
        const body = document.body;

        brandToggle.addEventListener('click', function() {
            if (body.classList.contains('sidebar-expanded')) {
                body.classList.remove('sidebar-expanded');
                body.classList.add('sidebar-collapsed');
            } else {
                body.classList.remove('sidebar-collapsed');
                body.classList.add('sidebar-expanded');
            }
        });
    });
</script>
</body>