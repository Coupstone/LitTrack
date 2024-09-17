<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'advance-search';
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 
?>
<!DOCTYPE html>

<html lang="en" style="height: auto;">
<head>
        <title>Advance Search</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden; 
}

.main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 250px;
    transition: width 0.3s ease-in-out; 
    overflow-y: auto; 
    overflow-x: hidden; 
    background-color: white;
}

.main-sidebar::-webkit-scrollbar {
    display: none;
}
.main-sidebar {
    -ms-overflow-style: none; 
    scrollbar-width: none; 
}

body.sidebar-collapsed .main-sidebar {
    width: 70px;
}

.main-sidebar .nav-link p {
    display: inline;
}

body.sidebar-collapsed .main-sidebar .nav-link p {
    display: none;
}

.main-sidebar .nav-link i {
    font-size: 1.2rem;
    margin-right: 10px;
}

body.sidebar-collapsed .main-sidebar .nav-link i {
    text-align: center;
    margin-right: 0;
    width: 100%;
}

.content-wrapper {
    margin-left: 250px;
    transition: margin-left 0.3s ease-in-out;
    height: 100%;
    overflow: hidden;
}

body.sidebar-collapsed .content-wrapper {
    margin-left: 60px;
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

body.sidebar-collapsed .brand-link .brand-image {
    width: 2rem;
    height: 2rem;
    margin-right: 0; 
}

.brand-link .brand-text {
    font-size: 1rem;
    transition: opacity 0.3s ease;
    white-space: nowrap;
}

body.sidebar-collapsed .brand-link .brand-text {
    opacity: 0;
    overflow: hidden;
}
</style>
</head>
<body>
    <div class="row justify-content-center mt-5">
        <h1 class="card-title text-center"style="margin-right: 500px; font-size: 2rem;">Advanced Search</h1>
            </div>
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10 col-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <form action="walapa.php" method="GET">
                            <div class="form-group mb-3 text-center">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                                <label for="title">Search by Title</label>
                            </div>
                            <div class="form-group mb-3 text-center">
                                <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name">
                                <label for="author">Search by Author</label>
                            </div>
                            <div class="form-group mb-3 text-center">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" id="year_from" name="year_from" placeholder="From year">
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <span>To</span>
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" id="year_to" name="year_to" placeholder="To year">
                                    </div>
                                </div>
                                <label for="year">Search by Publication Year</label>
                            </div>
                            <div class="form-group mb-3 text-center">
                                <input type="text" class="form-control" id="department" name="department" placeholder="Enter department">
                                <label for="department">Search by Department</label>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>