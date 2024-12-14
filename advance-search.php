<?php 
$page = isset($_GET['page']) ? $_GET['page'] : 'advance-search';
require_once('./config.php'); // Ensure database config is included
require_once('inc/topBarNav.php'); // Top navigation bar
require_once('inc/header.php'); // Page header

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Research Search</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="uploads/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
    font-weight: var(--bs-body-font-weight);
    line-height: var(--bs-body-line-height);
    color: var(--bs-body-color);
    text-align: var(--bs-body-text-align);
    background-color: var(--bs-body-bg);
    -webkit-text-size-adjust: 100%;
        }
        .outer-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button.btn-primary {
            width: 10%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .container {
            transform: translateY(10%); /* Moves the container up by 10% of its height */
        }
        .card-body {
            color:#5D5D5D;
        }
        
    </style>
</head>
<body>
<div class="container h-100 d-flex justify-content-center align-items-center mt-5">
    <div class="card card-outline card-primary shadow-lg col-lg-10 col-md-10 col-sm-12">
        <div class="card-header bg-white">
            <h3 class="card-title text-center text-dark mt-3" style="font-size: 20px;"><b>Advanced Search</b></h3>
        </div>
        <div class="card-body">
        <form id="advanceSearchForm" action="walapa.php" method="GET" onsubmit="return validateForm()">
                <div class="form-group mb-3">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title to search">
                </div>
                <div class="form-group mb-3">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name">
                </div>
                <div class="form-group mb-3">
    <div class="row">
        <div class="col-sm-6">
            <label for="from_year">From Year:</label>
            <select class="form-control" id="from_year" name="from_year">
                <?php
                $currentYear = date('Y');
                for ($year = 1960; $year <= $currentYear; $year++) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-sm-6">
            <label for="to_year">To Year:</label>
            <select class="form-control" id="to_year" name="to_year">
                <?php
                $currentYear = date('Y');
                for ($year = 1960; $year <= $currentYear; $year++) {
                    echo "<option value='$year'>$year</option>";
                }
                ?>
            </select>
        </div>
    </div>
</div>
                <div class="form-group mb-3">
                    <label for="keywords">Keywords:</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Enter keywords">
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
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