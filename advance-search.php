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
/* Ensure the page uses full height and accounts for sidebar */
body, html {
    height: 100%;
    margin: 0;
    background-color: #f8f9fa;
}

/* Adjust layout for main content when sidebar is present */
.outer-container {
    width: 100%;
    max-width: 1700px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Center child elements horizontally */
    justify-content: center; /* Center child elements vertically */
    height: 80vh; /* Ensure it takes the full viewport height */
    transition: all 0.3s ease;
}


/* Refined title */
.refined-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    font-family: 'Arial', sans-serif;
    text-align: center;
    margin-bottom: 10px;
}

/* Underline for title */
.underline {
    width: 80px;
    height: 3px;
    background-color: #007bff;
    margin: 10px auto;
    border-radius: 2px;
}

/* Adjust form styling */
.card {
    width: 200%;
    max-width: 600px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    margin: 10 auto; /* Center horizontally */
}


/* Add media query for responsive design */
@media (max-width: 768px) {
}
</style>
</head>
<body>
<div class="outer-container">
    <h1 class="refined-title">Advanced Search</h1>  
    <div class="card mt-4">
        <div class="card-body">
            <form id="advanceSearchForm" action="walapa.php" method="GET">
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" required>
                    <label for="title">Search by Title</label>
                </div>
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="author" name="author" placeholder="Enter Author Name" required pattern="^[a-zA-Z\s\.\-]+$" title="Only letters, spaces, dots, and hyphens are allowed">
                    <label for="author">Search by Author</label>
                </div>
                <div class="form-group mb-3 text-center">
                    <div class="row">
                        <div class="col">
                            <select class="form-control" id="year_from" name="year_from" required>
                                <option value="" disabled hidden selected>From Year</option>
                                <!-- JavaScript will populate the years -->
                            </select>
                        </div>
                        <div class="col-auto d-flex align-items-center">
                            <span>To</span>
                        </div>
                        <div class="col">
                            <select class="form-control" id="year_to" name="year_to" required>
                                <option value="" disabled hidden selected>To Year</option>
                                <!-- JavaScript will populate the years -->
                            </select>
                        </div>
                    </div>
                    <label for="year">Search by Publication Year</label>
                </div>
                <div class="form-group mb-3 text-center">
                    <input type="text" class="form-control" id="department" name="department" placeholder="Enter Department" required pattern="^[a-zA-Z\s\.\-]+$" title="Only letters, spaces, dots, and hyphens are allowed">
                    <label for="department">Search by Department</label>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="searchButton" disabled>Search</button>
                    <p id="formMessage" style="color: red; display: none;">Please fill out all fields to proceed.</p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to populate the year dropdowns
    document.addEventListener('DOMContentLoaded', function () {
        const yearFromSelect = document.getElementById('year_from');
        const yearToSelect = document.getElementById('year_to');
        const currentYear = new Date().getFullYear();

        // Populate year dropdowns starting from 1974 to the current year
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

    // Restrict inputs to letters and special characters only
    document.getElementById('author').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^a-zA-Z\s\.\-]/g, '');
    });

    document.getElementById('department').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^a-zA-Z\s\.\-]/g, '');
    });

    // JavaScript function to validate form inputs and enable/disable the search button
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('advanceSearchForm');
        const searchButton = document.getElementById('searchButton');
        const formMessage = document.getElementById('formMessage');
        const inputs = form.querySelectorAll('input, select');

        // Function to check all fields are filled
        function checkInputs() {
            let allFilled = true;
            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    allFilled = false;
                }
            });

            if (allFilled) {
                searchButton.removeAttribute('disabled');
                formMessage.style.display = 'none';
            } else {
                searchButton.setAttribute('disabled', 'true');
                formMessage.style.display = 'block';
            }
        }

        // Add event listeners to input fields to check when user types
        inputs.forEach(input => {
            input.addEventListener('input', checkInputs);
        });

        // Run validation once on page load
        checkInputs();
    });
</script>
</body>
</html>
