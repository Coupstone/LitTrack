<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'advance-search';
require_once('./config.php'); 
require_once('inc/topBarNav.php'); 
require_once('inc/header.php'); 
?>
<!DOCTYPE html>

<html lang="en" style="height: auto;">
<head>
    <title>Advanced Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<style>
.outer-container {
    width: 100%;
    max-width: 1700px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 80vh;
    transition: all 0.3s ease;
}
.refined-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    font-family: 'Arial', sans-serif;
    text-align: center;
    margin-bottom: 10px;
}
.underline {
    width: 80px;
    height: 3px;
    background-color: #007bff;
    margin: 10px auto;
    border-radius: 2px;
}
.card {
    width: 200%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin: 10 auto;
}
@media (max-width: 768px) {
}
 /* Sidebar styling */
 .sidebar {
            overflow: hidden; /* Hide scrollbar */
            /* other styling like width, height, background color, etc. */
        }
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
<div class="outer-container">
    <h1 class="refined-title">Advanced Search</h1>  
    <div class="card mt-4">
        <div class="card-body">
            <form id="advanceSearchForm" action="walapa.php" method="GET" onsubmit="return validateForm()">
                <!-- Title Input -->
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
                    <label for="title">Search by Title</label>
                </div>
                <!-- Author Input -->
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="author" name="author" placeholder="Enter Author Name" pattern="^[a-zA-Z\s\.\-]+$" title="Only letters, spaces, dots, and hyphens are allowed">
                    <label for="author">Search by Author</label>
                </div>
                <!-- Publication Year -->
                <div class="form-group mb-3 text-center">
                    <div class="row">
                        <div class="col">
                            <select class="form-control" id="year_from" name="year_from">
                                <option value="" disabled hidden selected>From Year</option>
                                <!-- JavaScript populates years -->
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <span>To</span>
                        </div>
                        <div class="col">
                            <select class="form-control" id="year_to" name="year_to">
                                <option value="" disabled hidden selected>To Year</option>
                                <!-- JavaScript populates years -->
                            </select>
                        </div>
                    </div>
                    <label for="year">Search by Publication Year</label>
                </div>
                <!-- Keywords Input Field -->
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="topic_keyword" name="topic_keyword" placeholder="Enter Keywords">
                    <label for="topic_keyword">Search by Keywords</label>
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                    <p id="formMessage" style="color: red; display: none;">Please fill out at least one field to proceed.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to populate year dropdowns
    document.addEventListener('DOMContentLoaded', function () {
        const yearFromSelect = document.getElementById('year_from');
        const yearToSelect = document.getElementById('year_to');
        const currentYear = new Date().getFullYear();

        // Populate years from 1974 to current year
        for (let year = 1974; year <= currentYear; year++) {
            const optionFrom = document.createElement('option');
            optionFrom.value = year;
            optionFrom.textContent = year;
            yearFromSelect.appendChild(optionFrom);

            const optionTo = document.createElement('option');
            optionTo.value = year;
            optionTo.textContent = year;
            yearToSelect.appendChild(optionTo);
        }
    });

    // JavaScript function for form validation
    function validateForm() {
        const title = document.getElementById('title').value.trim();
        const author = document.getElementById('author').value.trim();
        const yearFrom = document.getElementById('year_from').value;
        const yearTo = document.getElementById('year_to').value;
        const topicKeyword = document.getElementById('topic_keyword').value.trim();
        
        // Check if at least one field is filled
        if (title || author || yearFrom || yearTo || topicKeyword) {
            document.getElementById('formMessage').style.display = 'none'; // Hide the message if valid
            return true; // Allow form submission
        } else {
            document.getElementById('formMessage').style.display = 'block'; // Show message if no fields are filled
            return false; // Prevent form submission
        }
    }
</script>
</body>
</html>
