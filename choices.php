<?php
// Expanded Terms and Conditions content with h6 headings
$terms = "
<p>By accessing and using the PUPSRC LitTrack system, you agree to abide by the following terms and conditions:</p>

<h6>1. System Usage</h6>
<ul>
    <li>The system is strictly for academic and research purposes only, as approved by the Polytechnic University of the Philippines Sta. Rosa Campus (PUP Sta. Rosa).</li>
    <li>Unauthorized use of the system for commercial purposes, personal gain, or non-academic activities is prohibited.</li>
    <li>Users must use their official PUP Sta. Rosa credentials to access the system.</li>
</ul>

<h6>2. User Responsibilities</h6>
<ul>
    <li>Users are responsible for maintaining the confidentiality of their login credentials and securing their accounts.</li>
    <li>Any suspicious activity, such as unauthorized access, must be reported to the Research Office immediately.</li>
    <li>Users must ensure the accuracy and integrity of data they upload to the system.</li>
</ul>

<h6>3. Data Privacy and Protection</h6>
<ul>
    <li>User data will be processed in compliance with applicable privacy laws, including the Philippine Data Privacy Act of 2012 (RA 10173).</li>
    <li>All user data, including uploaded files and metadata, will be securely stored and handled by authorized personnel.</li>
    <li>Aggregated, anonymized data may be used for institutional research and reporting but will not contain personally identifiable information.</li>
</ul>

<h6>4. Prohibited Actions</h6>
<ul>
    <li>Users must not upload content that is illegal, offensive, or infringes on intellectual property rights.</li>
    <li>Reverse engineering, scraping, or compromising the system's functionality is strictly prohibited.</li>
    <li>Sharing accounts or login credentials with unauthorized persons is not allowed.</li>
</ul>

<h6>5. Liability</h6>
<ul>
    <li>PUP Sta. Rosa is not responsible for data loss caused by user negligence, system downtime, or unauthorized access resulting from weak user security practices.</li>
    <li>The system is provided \"as-is\" and \"as-available.\" While we strive for reliability, uninterrupted access is not guaranteed.</li>
</ul>

<h6>6. Intellectual Property</h6>
<ul>
    <li>All content uploaded to the system remains the intellectual property of the user but must comply with institutional policies.</li>
    <li>PUP Sta. Rosa retains ownership of the system, its features, and all underlying technology.</li>
</ul>

<h6>7. System Modifications and Updates</h6>
<ul>
    <li>PUP Sta. Rosa reserves the right to modify the system, its features, and these terms as necessary for system improvement or compliance with institutional policies.</li>
    <li>Users will be notified of major updates or changes through the official PUP communication channels.</li>
</ul>

<h6>8. Termination of Access</h6>
<ul>
    <li>PUP Sta. Rosa reserves the right to terminate or suspend user access in cases of non-compliance with these terms, misuse of the system, or security breaches.</li>
    <li>Upon termination, users may request a copy of their uploaded data, subject to institutional review and approval.</li>
</ul>

<h6>9. Governing Law</h6>
<p>These terms and conditions are governed by the laws of the Philippines. Any disputes arising from the use of the system will be resolved under the jurisdiction of the relevant Philippine courts.</p>

<h6>10. Contact Information</h6>
<p>For inquiries, assistance, or reporting system-related issues, please contact the Research Office at PUP Sta. Rosa:</p>
<ul>
    <li>Email: researchoffice@pupstarosa.edu.ph</li>
    <li>Address: Arambulo Street 4026 Santa Rosa Laguna</li>
</ul>
";
require_once('./config.php');
check_active_session();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>

    <!-- Bootstrap CSS -->
    <link href="styles/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Site Icon -->
    <link rel="icon" href="uploads/LitTrack.png" type="image/png"/>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="container position-absolute top-50 start-50 translate-middle">
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4 text-center p-4 shadow-lg rounded" style="background-color: white;">
                <img src="uploads/LitLogo.png" alt="PUP Logo" class="mb-4 img-fluid" style="max-width: 210px;">
                <p class="mb-4">Please click or tap your destination</p>
                
                <a href="./login.php" class="btn btn-primary btn-lg mb-3 w-100" style="background-color: #5875B5; color: white;">Student</a>
                <a href="./admin" class="btn btn-danger btn-lg mb-3 w-100" style="background-color: #810100; color: white;">Admin</a>
                
                <p class="mt-3">
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Modal widened with 'modal-lg' -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $terms; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>