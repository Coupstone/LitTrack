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

<?php if (!isset($page) || $page !== 'view_archive'): ?>
<body class="sidebar-expanded">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Hamburger Button -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#" id="sidebar-toggle" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </nav>



    <!-- Sidebar -->
    <nav class="main-sidebar sidebar-light-dark">
        <div class="brand-link">
            <!-- Brand Logo -->
            <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 2.5rem; height: 2.5rem; max-height: unset; object-fit: scale-down; object-position: center center;">
            <span class="brand-text font-weight-light">
                <span style="font-family: 'Georgia', serif; font-size: 0.9rem; background: linear-gradient(to right, #651d32, #b81d24); -webkit-background-clip: text; color: transparent; font-weight: normal;">PUP Sta. Rosa</span>
                <span class="littrack-text">LitTrack</span>
            </span>
        </div>

        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-4">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Home Link -->
                    <li class="nav-item mt-4">
                        <a href="./index.php" class="nav-link <?= isset($page) && $page == 'home' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Home</p>
                        </a>
                    </li>

                    <!-- Projects Link -->
                    <li class="nav-item">
                        <a href="./?page=projects" class="nav-link <?= isset($page) && $page == 'projects' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>Projects</p>
                        </a>
                    </li>

                    <!-- Department Dropdown -->
                    <li class="nav-item has-treeview">
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
                    </li>

                    <!-- Courses Dropdown -->
                    <li class="nav-item has-treeview">
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
                    </li>

                    <!-- About Us Link -->
                    <li class="nav-item">
                        <a href="./?page=about" class="nav-link <?= isset($page) && $page == 'about' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-info-circle"></i>
                            <p>About Us</p>
                        </a>
                    </li>

                    <!-- Additional Links for Logged-in Users -->
                    <?php if($_settings->userdata('id') > 0): ?>
                    <li class="nav-item">
                        <a href="./?page=profile" class="nav-link <?= isset($page) && $page == 'profile' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="./?page=submit-archive" class="nav-link <?= isset($page) && $page == 'submit-archive' ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-upload"></i>
                            <p>Submit Research</p>
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
<?php endif; ?>



    <!-- JavaScript to Toggle Sidebar -->
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('sidebar-toggle');
    const body = document.body;

    toggleButton.addEventListener('click', function () {
        body.classList.toggle('sidebar-collapsed');
        body.classList.toggle('sidebar-expanded');
    });
    
    if (!body.classList.contains('sidebar-expanded')) {
        body.classList.add('sidebar-expanded');
    }
});
    </script>
</body>