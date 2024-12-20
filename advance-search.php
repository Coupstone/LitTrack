<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'advance-search';
require_once('./config.php');
require_once('inc/topBarNav.php');
require_once('inc/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Advanced Research Search</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<link href="styles/main.css" rel="stylesheet">
<link rel="icon" href="uploads/LitTrack.png" type="image/png">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <!-- Title Input -->
                <div class="form-group mb-3">
                    <label for="title">Title:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title to search">
                </div>
                <!-- Author Input -->
                <div class="form-group mb-3">
                    <label for="author">Author:</label>
                    <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name">
                </div>
                <!-- Year Range Input -->
                <div class="form-group mb-3">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="year_from">From Year:</label>
                            <select class="form-control" id="year_from" name="year_from">
                                <option value="" disabled hidden selected>Select Year</option>
                                <?php
                                $currentYear = date('Y');
                                for ($year = 1960; $year <= $currentYear; $year++) {
                                    echo "<option value='$year'>$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="year_to">To Year:</label>
                            <select class="form-control" id="year_to" name="year_to">
                                <option value="" disabled hidden selected>Select Year</option>
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
                <!-- Keywords Input -->
                <div class="form-group mb-3">
                    <label for="topic_keyword">Keywords:</label>
                    <input type="text" class="form-control" id="topic_keyword" name="topic_keyword" placeholder="Enter keywords">
                </div>
                <!-- Submit Button -->
<div class="form-group text-center">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6 col-md-4">
            <button type="submit" class="btn btn-primary w-100" id="searchButton" disabled>Search</button>
        </div>
    </div>
</div>

                    </div>
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
    for (let year = 2018; year <= currentYear; year++) {
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
    const searchButton = document.getElementById('searchButton'); // Get the search button

    // Check if at least one field is filled
    if (title || author || yearFrom || yearTo || topicKeyword) {
        searchButton.disabled = false; // Enable the search button if valid
        return true; // Allow form submission
    } else {
        searchButton.disabled = true; // Disable the search button if no fields are filled
        return false; // Prevent form submission
    }
}

// Attach event listeners to input fields to check when their values change
document.getElementById('title').addEventListener('input', validateForm);
document.getElementById('author').addEventListener('input', validateForm);
document.getElementById('year_from').addEventListener('change', validateForm);
document.getElementById('year_to').addEventListener('change', validateForm);
document.getElementById('topic_keyword').addEventListener('input', validateForm);

// Initialize validation on page load
document.addEventListener('DOMContentLoaded', validateForm);

</script>
</body>
</html>




