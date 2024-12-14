<?php
require_once('./config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUPSRC LitTrack</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="uploads/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <body>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
<div class="container-lg">
    <a class="navbar-brand fw-bold" href="#">
        <!-- Replace Jane Doe with your logo -->
        <img src="uploads/LitTrack.png" alt="LitTrack Logo" style="height: 80px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    </button>


    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#services">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#researches">Researches</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#news">News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link d-lg-none" href="#contact">Contact</a>
        </li>
      </ul>
      <a class="btn btn-outline-dark d-none d-lg-block" href="choices.php">Sign In</a>
    </div>
  </div>
</nav>

<section class="home" id="home">
  <div class="container-lg">
    <div class="row align-items-center">
      <!-- Left Column -->
      <div class="col-sm-6 text-center">
        <!-- Logo above text -->
        <!-- <img src="uploads/LitLogo.png" class="img-fluid mb-4" style="max-width: 40%; height: auto;" alt="LitTrack Logo"> -->
        <!-- Smaller 'About LitTrack' text -->
        <h5 class="fw-semibold">About Us</h5>
        <p>Welcome to PUPSRC LitTrack, the official research repository and literature mapping system of PUP Santa Rosa Campus.

This platform showcases the academic achievements of our community, offering students exclusive tools for research exploration and management, including literature mapping. Visitors can browse titles and abstracts, while full access is reserved for students.

Join us in fostering innovation, collaboration, and academic excellence as we shape a future driven by knowledge and progress.
        </p>
        <a href="#researches" class="btn btn-outline-dark btn-lg">Researches</a>
      </div>
      
      <!-- Right Column -->
      <div class="col-sm-6 text-center">
        <!-- Right image -->
        <img src="uploads/PUPSRCLOGO.png" class="img-fluid" style="max-width: 60%; height: auto;" alt="Barista Image">
      </div>
    </div>
  </div>
</section>

<section class="services" id="services">
  <div class="container">
    <h4 class="display-5 fw-bold mb-4 text-center">Services</h4>
    <div class="row">
      <!-- First Row -->
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Centralized Research Storage</h5>
            <p class="card-text">Offers a secure platform to store, manage, and preserve research outputs for long-term access and use.</p>
          </div>
        </div>
      </div>
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Advanced Search and Retrieval</h5>
            <p class="card-text">Enables users to quickly locate research papers and related materials through keyword searches and filters.</p>
          </div>
        </div>
      </div>
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Literature Mapping</h5>
            <p class="card-text">Visualizes relationships between research topics, keywords, and authors to identify trends, gaps, and connections in the literature.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Second Row -->
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">User Access Management</h5>
            <p class="card-text">Provides role-based access, allowing students to upload and download full research files while limiting non-student users to view-only access of abstracts and titles.</p>
          </div>
        </div>
      </div>
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Collaboration and Networking</h5>
            <p class="card-text">Facilitates collaboration among researchers by linking related works and suggesting similar studies.</p>
          </div>
        </div>
      </div>
      <div class="col-lg col-sm-6 mt-4">
        <div class="card shadow">
          <div class="card-body">
            <h5 class="card-title fw-bold">Research Analytics</h5>
            <p class="card-text">Generates insights on research trends, frequently explored topics, and areas needing further study.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>




<section class="researches" id="researches">
    <div class="container-fluid">
        <h4 class="display-5 fw-bold mb-4 text-center" style="margin-top: 20px;">Researches</h4>
        <div class="mb-3 d-flex justify-content-center">
            <div class="input-group" style="width: 50%;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search..." onkeyup="filterTable()">
                <button class="btn btn-primary" onclick="filterTable()">Search</button>
            </div>
        </div>

        <div class="card card-outline card-primary shadow rounded-0 table-container">
            <div class="card-body rounded-0">
                <div class=" table-content">
                    <table class="table table-hover table-striped" id="researchTable">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $qry = $conn->query("
                        SELECT 
                            al.*, 
                            -- Concatenate all authors' names
                            GROUP_CONCAT(CONCAT(aa.first_name, ' ', aa.last_name) SEPARATOR ', ') AS authors,
                            -- Citation count as a subquery
                            (SELECT COUNT(*) 
                            FROM citation_relationships cr 
                            WHERE cr.cited_paper_id = al.id) AS citation_count
                        FROM 
                            archive_list al
                        LEFT JOIN 
                            archive_authors aa ON al.id = aa.archive_id
                        WHERE 
                            al.status = 1  -- Only approved studies (status = 1)
                        GROUP BY 
                            al.id
                        ORDER BY 
                            unix_timestamp(al.date_created) DESC
                        ");
                  while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo date("Y", strtotime($row['date_created'])); ?></td>
                            <td><?php echo ucwords($row['title']); ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="showModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                    <i class="fa fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="news" id="news">
  <div class="container">
    <h4 class="display-5 fw-bold mb-4">News</h4>
    <div class="row justify-content-center">
      <div class="col-lg-8 col-sm-10 mt-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title fw-bold">2nd Leon C. Arcillas Collegiate Research Coloquium</h5>
            <p class="card-text">
            February 24, 2024, the Polytechnic University of the Philippines (PUP) Santa Rosa Campus proudly hosted its 2nd Leon Arcillas Collegiate Research Colloquium
            </p>

            <!-- Slideshow Section -->
            <div id="projectCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img src="uploads/NEWS1.png" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS2.png" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS3.png" class="d-block w-100" alt="Slide 3">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS4.png" class="d-block w-100" alt="Slide 4">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS5.png" class="d-block w-100" alt="Slide 5">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS6.png" class="d-block w-100" alt="Slide 6">
                </div>
                <div class="carousel-item">
                  <img src="uploads/NEWS7.png" class="d-block w-100" alt="Slide 7">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>

            <a href="https://sites.google.com/view/pupsrcreo/home" class="btn btn-outline-dark">View More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="contact" id="contact">
<section class="contact-section bg-light text-center py-2">
  <div class="container">
    <div class="row">
      <!-- Social Media Icons -->
      <div class="col-12 mb-3">
      <p class="fw-bold">KEEP IN TOUCH</p>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-facebook"></i></a>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-twitter"></i></a>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-youtube"></i></a>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-linkedin"></i></a>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-rss"></i></a>
        <a href="#" class="social-icon" style="font-size: 2rem;"><i class="bi bi-spotify"></i></a>
      </div>
      <!-- Contact Information -->
      <div class="col-12">
        <p class="fw-bold">CONTACT US</p>
        <p>
          Phone: (+63 2) 5335-1PUP (5335-1787) or 5335-1777 <br>
          Email: <a href="mailto:inquire@pup.edu.ph">inquire@pup.edu.ph</a>
        </p>
      </div>
    </div>
  </div>
</section>
<!-- Modal Structure -->
<div id="studyModal" class="modal" style="display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.4);">
    <div style="background-color:#fff; margin:8% auto; padding:20px; border-radius:8px; width:75%; max-width:800px; height:auto; position:relative; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
        <span id="closeModal" style="position:absolute; top:5px; right:5px; font-size:20px; font-weight:bold; cursor:pointer;">&times;</span>
        <h2 id="modalTitle" style="text-align:center; font-size:22px; margin-bottom:15px;"></h2>
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom:15px;">
            <p style="font-size:16px; width:48%;"><strong>Authors:</strong> <span id="modalAuthors"></span></p>
            <p style="font-size:16px; width:48%;"><strong>Year:</strong> <span id="modalYear"></span></p>
  
            <p style="font-size:16px; width:48%;"><strong>Citations:</strong> <span id="modalCitations"></span></p>
        </div>
        <p style="font-size:16px; margin-top:20px;"><strong>Abstract:</strong> <span id="modalAbstract"></span></p>
    </div>
</div>


</body>

<script>
function showModal(d) {
    console.log(d); // This will show the entire data object, so you can inspect the fields
    document.getElementById("modalTitle").innerText = d.title || "No Title Available";
    document.getElementById("modalAuthors").innerText = d.authors || "Unknown Author(s)";
    document.getElementById("modalAbstract").innerText = d.abstract || "No Abstract Available";
    document.getElementById("modalYear").innerText = d.year || d.publication_year || "Unknown Year";
    document.getElementById('modalCitations').textContent = d.citation_count || "No Citations Available"; // Display citation count or fallback text

    // Show the modal
    document.getElementById("studyModal").style.display = "block";
}

// Close the modal when the close button is clicked
document.getElementById("closeModal").onclick = function() {
    document.getElementById("studyModal").style.display = "none";
}

// Close the modal if the user clicks anywhere outside the modal
window.onclick = function(event) {
    if (event.target == document.getElementById("studyModal")) {
        document.getElementById("studyModal").style.display = "none";
    }
}

// Function to filter the research table based on search input
function filterTable() {
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("researchTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdTitle = tr[i].getElementsByTagName("td")[1]; // Title column
        var tdDate = tr[i].getElementsByTagName("td")[0]; // Date column (assuming date is in the first column)

        if (tdTitle || tdDate) {
            var titleValue = tdTitle.textContent || tdTitle.innerText;
            var dateValue = tdDate.textContent || tdDate.innerText;

            if (titleValue.toLowerCase().indexOf(filter) > -1 || dateValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

<footer class="text-center p-3 bg-body-tertiary">
  <div>&copy; 2024. All Rights Reserved.</div>
</footer>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
  --accent-color: Red;
}

section [class^="container"] {
  padding: 4rem 2rem;
}
@media screen and (min-width: 1024px) {
  section [class^="container"] {
    padding: 4rem;
  }
  nav [class^="container"] {
    padding: 0 4rem;
  }
}

section:not(:first-of-type) {
  text-align: center;
}

section:nth-child(2n) {
  background-color: #C4D7F1;
}

.container a {
  color: #000000;
  text-decoration: none;
}
.container a:hover {
  color: var(--accent-color);
}

section .card, #services .card,
.btn-outline-dark {
  border: 2px solid #000000;
  box-shadow: 4px 4px #000000;
  transition: all 0.4s;
}
.btn-outline-dark:hover {
  box-shadow: 4px 4px var(--accent-color);
}

/* NAVBAR */

.navbar {
  background-color: #ffffff;
  padding: 0rem; /* Reduce padding for less height */
}

.navbar-nav .nav-link {
  color: #000000;
  font-size: 1.1rem;
  transition: all 0.5s;
}
.navbar-nav .nav-link:hover {
  color: var(--accent-color);
}
.navbar-brand img {
  height: 50px; /* Adjust the logo height to make the navbar shorter */
  transition: height 0.3s; /* Smooth transition for hover effects */
}

.navbar-toggler {
  padding: 0.25rem 0.5rem; /* Adjust the toggler button size */
}
@media screen and (min-width: 1024px) {
  .navbar-nav .nav-item {
    padding: 0 1rem;
  }
  .navbar-brand {
    font-size: 1.5rem;
  }
}

/* HOME (formerly HERO) */
.home {
    width: 100%; /* Full width */
    height: 100vh; /* Full viewport height */
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa; /* Optional background color */
  }
section.home {
  padding-top: 72px;
}
@media screen and (max-width: 576px) {
  section.home {
    text-align: center;
  }
  section.home img {
    width: 80%;
    margin: 0.5rem 0;
  }
}
.home h1 {
    font-size: 1rem; /* Adjust the size */
    font-weight: bold; /* Keep it bold */
  }

/* SERVICES */
.services h4 {
  transform: translateY(70%); /* Moves the container up by 10% of its height */
  font-size: 45px;
}
.services {
  width: 100%; /* Full width */
  padding: 4rem 0; /* Add vertical spacing */
  background-color: #FFD0D0; /* Optional background color */
}

.services .container-fluid {
  padding-left: 0; /* Remove container padding */
  padding-right: 0; /* Remove container padding */
}

.services h2 {
  margin-bottom: 2rem; /* Space below heading */
  text-align: center; /* Center the heading */
}

.services .row {
  margin-bottom: 2rem; /* Space between rows */
}

.services .card {
  text-align: center; /* Center card content */
  padding: 2rem;
  border: 1px solid #ddd; /* Light border */
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow for a better look */
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* Ensure even space distribution */
  height: 100%; /* Ensure all cards are the same height */
  max-width: 22rem; /* Set a maximum width */
  margin: 0 auto; /* Center card horizontally */
}

.services .card-body {
  flex-grow: 1; /* Allow card body to take available space */
}

.services .card i {
  font-size: 2rem; /* Icon size */
  color: #007bff; /* Icon color */
  margin-bottom: 1rem;
}

section.services i {
  font-size: 2rem;
  margin: 1rem auto 0;
  border: 2px solid #000000;
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--accent-color); /* Replace with your desired accent color */
}

.services .col-lg-4, .services .col-sm-6 {
  padding: 0.5rem; /* Reduce space between columns */
}

.services .card-title {
  font-weight: bold;
}

.services .card-text {
  font-size: 0.95rem;
  margin-top: 0.5rem;
}

/* Optional media queries for responsive adjustments */
@media (max-width: 768px) {
  .services .card {
    max-width: 100%; /* Allow cards to stretch fully on smaller screens */
    margin-bottom: 1.5rem; /* Add some space between cards in small screens */
  }

  .services h2 {
    font-size: 1.75rem; /* Reduce heading size on small screens */
  }
}.services {
    width: 100%; /* Full width */
    padding: 2rem 0; /* Add vertical spacing */
    background-color: #FFD0D0; /* Optional background color */
  }

  .services .container-fluid {
    padding-left: 0; /* Remove container padding */
    padding-right: 0; /* Remove container padding */
  }

  .services .card {
    text-align: center; /* Center the card content */
    padding: 1.5rem;
    border: 1px solid #ddd; /* Add a light border */
    border-radius: 10px;
  }

  .services .card i {
    font-size: 2rem; /* Icon size */
    color: #007bff; /* Icon color */
    margin-bottom: 1rem;
  }

  .services h2 {
    margin-bottom: 2rem; /* Space below heading */
  }
section.services i {
  font-size: 2rem;
  margin: 1rem auto 0;
  border: 2px solid #000000;
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--accent-color);
}

/* RESEARCHES (formerly ABOUT) */
/* Container styling */
.researches h4 {
    margin-top: 70px; /* Adjust the value as needed */
    font-size: 45px;
}

.researches {
    width: 100%; /* Full width */
    height: 100vh; /* Full viewport height */
    display: flex;
    align-items: center;
    background-color: #f8f9fa; /* Optional background color */
  }
        .dropdown-menu {
            min-width: 120px;
        }
        .align-middle {
            vertical-align: middle !important;
}
/* .table-container {
    width: 1000px; /* Adjust this value to make it smaller or larger */
    margin: 0 auto; /* Centers the table */
  } */
  .table {
    width:1000px;
    margin-left: -80px;
    transform: translateY(-59%); /* Moves the container up by 10% of its height */
  }
  #searchInput {
    width: 30%;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    
}

/* Button hover effect */
.card-tools .btn.bg-primary:hover,
.card-tools .btn.bg-navy:hover {
    filter: brightness(1.1) !important; /* Brighten on hover */
}
/* Responsive table */
.table-responsive {
    overflow-x: auto; /* Allows scrolling on smaller screens */
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on mobile */
}

/* Mobile-specific adjustments */
@media (max-width: 768px) {
    .content {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .table th, .table td {
        font-size: 0.875rem; /* Smaller font size for mobile screens */
        padding: 8px; /* Less padding for mobile */
    }
}

/* Optional color change for table row hover */
.table-hover tbody tr:hover {
    background-color: #efefef;
}
/* NEWS SECTION */
.news {
    width: 100%; /* Full width */
    height: 100vh; /* Full viewport height */
    display: flex;
    align-items: center;
    background-color: #FFD0D0; /* Optional background color */
  }
  .carousel img {
  border-radius: 10px;
  max-height: 200px;
  object-fit: cover;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
}
.news h4{
  transform: translateY(49%); /* Moves the container up by 10% of its height */
}
.footer {
  background-color: #f8f9fa; /* Light background */
  color: #6c757d; /* Neutral text color */
}

.footer .social-icon {
  font-size: 24px; /* Icon size */
  margin: 0 10px;
  color: #800000; /* Maroon color matching the attachment */
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer .social-icon:hover {
  color: #d00000; /* Slightly lighter maroon on hover */
}

.footer a {
  text-decoration: none;
  color: inherit;
}

.footer a:hover {
  text-decoration: underline;
}

.footer p {
  margin: 0; /* Remove spacing around text */
}

/* FOOTER */
.contact{
    width: 100%; /* Full width */
    height: 50%; /* Full viewport height *//
  }
  .contact-container{
    border-radius: 10px;
    max-height: 200px;
  }
  .contact-section .social-icon {
    font-size: 2rem; /* Adjust size as needed */
    color: #800000;
    margin: 0 8px;
  }
  #studyModal > div {
    background-color: #fff;
    margin: auto;
    padding: 20px;
    border-radius: 8px;
    width: 80%;
    max-width: 600px;
    position: relative;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    top: 50%;
    transform: translateY(-50%);
}


</style>