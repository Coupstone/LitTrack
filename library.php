<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'library';
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 
?>
<!DOCTYPE html>

<html lang="en" style="height: auto;">
<head>
        <title>My Library</title>

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

</body>
</html>