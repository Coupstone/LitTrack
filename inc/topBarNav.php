<?php
require_once './config.php';


// Retrieve user data, including the avatar image
if (isset($_settings) && method_exists($_settings, 'userdata')) {
    $userId = $_settings->userdata('id');
    $avatar = $_settings->userdata('avatar'); // Assume 'avatar' field contains the image path
} else {
    $userId = null;
    $avatar = 'uploads/default.png'; // Default image if no user is logged in
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="styles/main.css" rel="stylesheet">
    <title>PUPSRC LitTrack</title>
    <link rel="icon" href="uploads/LitTrack.png" type="image/png">
    <!-- Resources -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>


    <!-- Inline CSS -->
<style>
    /* Import Fonts */
@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

/* Root Variables */
:root {
    --header-height: 3rem;
    --nav-width: 73px; /* Sidebar width */
    --first-color: #C4D7F1; /* Sidebar background color */
    --first-color-light: #AFA5D9; /* Sidebar hover color */
    --white-color: #000000; /* Text color */
    --body-font: 'Nunito', sans-serif; /* Default font family */
    --normal-font-size: 1rem; /* Base font size */
    --z-fixed: 100; /* Z-index for fixed elements */
}

/* Universal Styles */
*, ::before, ::after {
    box-sizing: border-box;
}

/* Body Styles */
body {
    position: relative;
    margin: var(--header-height) 0 0 0;
    padding: 0 1rem;
    font-family: var(--body-font);
    font-size: var(--normal-font-size);
    transition: .5s; /* Smooth transitions */
}

/* Anchor Tag Styles */
a {
    text-decoration: none;
}
.nav_item-placeholder {
    display: block; /* Mimic the original display style */
    height: 2rem; /* Adjust height to match the original size */
    margin: 1rem 0; /* Adjust spacing as needed */
}
/* Remove underline for all states */
.nav_link:link,
.nav_link:visited,
.nav_link:hover,
.nav_link:focus,
.nav_link:active {
    text-decoration: none; /* Ensure underline is removed for all states */
    outline: none; /* Remove focus outline if any */
}
/* Header Styles */
.header {
    width: 100%;
    height: var(--header-height);
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    background-color: #FFFFFF;
    z-index: var(--z-fixed);
    transition: .5s; /* Smooth transitions */
}

.header_toggle {
    color: var(--first-color);
    font-size: 1.5rem;
    cursor: pointer;
}

.header_img {
    width: 35px;
    height: 35px;
    display: flex;
    justify-content: center;
    border-radius: 50%; /* Makes the image round */
    overflow: hidden;
}

.header_img img {
    width: 40px;
}

/* Sidebar (Navbar) Styles */
.l-navbar {
    position: fixed;
    top: 0;
    left: -30%; /* Hidden by default */
    width: var(--nav-width);
    height: 100vh;
    background-color: var(--first-color); /* Sidebar background */
    padding: .5rem 0 0 0; /* Remove right padding */
    transition: .5s; /* Smooth transitions */
    z-index: var(--z-fixed);
}
.l-navbar:hover {
    width: calc(var(--nav-width) + 156px); /* Adjusted visible sidebar width */
    padding-right:0px;
}
.l-navbar:hover .nav_logo-img {
    width: 100%; /* Larger size when sidebar is open */
    height: 100%; /* Larger size when sidebar is open */
    transform: translateX(0%);
}
/* Sidebar Content Styles */
.nav {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
}

/* Logo Styles in Sidebar */
.nav_logo, .nav_link {
    display: grid;
    grid-template-columns: max-content max-content;
    align-items: center;
    column-gap: 1rem;
    padding: .5rem 0 .5rem 1.5rem;
}

.nav_logo {
    margin-bottom: 2rem;
}

.nav_logo-icon {
    font-size: 1.5rem;
    color: var(--white-color);
}

.nav_logo-name {
    color: var(--white-color);
    font-weight: 700; /* Thicker font for labels */
}

/* Sidebar Links */
.nav_link {
    position: relative;
    color: var(--white-color); /* Text color */
    margin-bottom: 1.5rem;
    font-weight: 600; /* Thicker font for labels */
    transition: .3s; /* Smooth hover effect */
}

.nav_link:hover {
    color: var(--white-color); /* Keep text white on hover */
}

.nav_icon {
    font-size: 1.4rem;
}

/* Sidebar Active Link */
.active {
    color: var(--white-color);
    font-weight: 700;
}

.active::before {
    content: '';
    position: absolute;
    left: 0;
    width: 2px;
    height: 32px;
    background-color: var(--white-color);
}

/* Height Utility */
.height-100 {
    height: 100vh;
}

/* Sidebar Logo Styles */
.nav_logo {
    display: flex;
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
    padding: 10px; /* Padding around the logo */
    width: 100%; /* Full width for container */
    margin-bottom: 0.5rem;
    transition: transform 0.3s ease, width 0.3s ease; /* Smooth size transition */
}

/* Logo Image */
.nav_logo-img {
    width: 85%; /* Default smaller size */
    height: 85%; /* Default smaller size */
    object-fit: contain; /* Scale image within the container */

    transform: translateX(-8%); /* Moves the container up by 10% of its height */

}

/* Sidebar Visible State (Larger Logo) */
.show .nav_logo-img {
    width: 100%; /* Larger size when sidebar is open */
    height: 100%; /* Larger size when sidebar is open */
    transform: translateX(0%); /* Moves the container up by 10% of its height */
}


/* Adjust Body Padding When Sidebar is Visible */
.body-pd {
    padding-left: calc(var(--nav-width) + 1rem);
}

/* Responsive Styles */
@media screen and (min-width: 768px) {
    body {
        margin: calc(var(--header-height) + 1rem) 0 0 0;
        padding-left: calc(var(--nav-width) + 2rem);
    }

    .header {
        height: calc(var(--header-height) + 1rem);
        padding: 0 2rem 0 calc(var(--nav-width) + 2rem);
    }

    .header_img {
        width: 40px;
        height: 40px;
    }

    .header_img img {
        width: 45px;
        height: 45px;
        border-radius: 0; /* Make square */
        object-fit: cover; /* Cover the image area */
    }

    .l-navbar {
        left: 0;
        padding: 1rem 1rem 0 0;
        margin-right: .2px;
    }

    .show {
        width: calc(var(--nav-width) + 156px); /* Adjusted visible sidebar width */
        padding-right: 0; /* Remove padding/margin on the right */
    }

    .body-pd {
        padding-left: calc(var(--nav-width) + 188px);
    }
        /* Add padding on hover */
        .l-navbar:hover + .body {
        padding-left: calc(var(--nav-width) + 2rem); /* Adjust body padding */
    }
}
</style>
</head>
<body id="body-pd">
<header class="header" id="header">
    <div class="header_toggle d-flex align-items-center"> 
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>
    
    <div class="d-flex justify-content-end">
        <div class="btn-group">
            <a href="#" 
               class="btn btn-light btn-sm d-flex align-items-center dropdown-toggle shadow-sm" 
               data-bs-toggle="dropdown" 
               aria-expanded="false" 
               style="border-radius: 25px; padding: 5px 15px;">
                
                <!-- Profile Image -->
                <img src="<?= validate_image(isset($avatar) ? $avatar : 'uploads/default.png') ?>" 
                     class="img-circle border border-primary shadow-sm" 
                     alt="User Image" 
                     style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                
                <!-- Name -->
                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?= ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname')) ?></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow">
                <li>
                    <!-- My Account Link -->
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo base_url.'/?page=profile' ?>">
                        <span class="fa fa-user me-2 text-primary"></span> My Account
                    </a>
                </li>
                <li>
                    <!-- Logout Link -->
                    <a class="dropdown-item d-flex align-items-center" href="<?= base_url.'/classes/Login.php?f=logout' ?>">
                        <span class="fas fa-sign-out-alt me-2 text-danger"></span> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div> 
            <a href="#" class="nav_logo">
                <img src="uploads/sidebarlogo.png" alt="Logo" class="nav_logo-img mr-4">
            </a>
                <div class="nav_list">
                    <a href="./index.php" class="nav_link <?= isset($page) && $page == 'home' ? 'active' : '' ?>"> 
                        <i class='bx bx-home nav_icon'></i> 
                        <span class="nav_name">Home</span> 
                    </a> 
                    <a href="./advance-search.php" class="nav_link <?= isset($page) && $page == 'advance-search' ? 'active' : '' ?>"> 
                        <i class='bx bx-search nav_icon'></i> 
                        <span class="nav_name">Advanced Search</span> 
                    </a>
                    <?php if ($_settings->userdata('id') > 0): ?>
                    <a href="./?page=profile" class="nav_link <?= isset($page) && $page == 'profile' ? 'active' : '' ?>"> 
                        <i class='bx bx-user nav_icon'></i> 
                        <span class="nav_name">My Profile</span> 
                    <a href="./?page=library" class="nav_link <?= isset($page) && $page == 'favorites' ? 'active' : '' ?>"> 
                            <i class='bx bx-star nav_icon'></i> 
                            <span class="nav_name">My Favorites</span> 
                        </a>
                    <a href="./?page=projects" class="nav_link <?= isset($page) && ($page == 'projects' || $page == 'view_archive') ? 'active' : '' ?>"> 
                        <i class='bx bx-folder nav_icon'></i> 
                        <span class="nav_name">Researches</span> 
                        </a>
                    <a href="./Litmaps.php" class="nav_link <?= isset($page) && $page == 'Litmaps' ? 'active' : '' ?>"> 
                        <i class='bx bx-share-alt nav_icon'></i> 
                        <span class="nav_name">Literature Mapping</span> 
                    </a>
                    </a>
                    <a href="./?page=submit-archive" class="nav_link <?= isset($page) && $page == 'submit-archive' ? 'active' : '' ?>"> 
                        <i class='bx bx-upload nav_icon'></i> 
                        <span class="nav_name">Upload Research</span> 
                    <?php endif; ?>

            <a href="javascript:void(0);" class="nav_item-placeholder" aria-hidden="true"></a>
</nav>
</div>
    <script>
document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
              nav = document.getElementById(navId),
              bodypd = document.getElementById(bodyId),
              headerpd = document.getElementById(headerId);
        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            // Hover event for opening sidebar
            nav.addEventListener("mouseenter", () => {
                // Show the sidebar
                nav.classList.add("show");
                // Adjust padding
                bodypd.classList.add("body-pd");
                headerpd.classList.add("body-pd");
            });

            // Hover event for closing sidebar
            nav.addEventListener("mouseleave", () => {
                // Hide the sidebar
                nav.classList.remove("show");
                // Remove padding
                bodypd.classList.remove("body-pd");
                headerpd.classList.remove("body-pd");
            });

            // Click event for hamburger button (toggle sidebar visibility)
            toggle.addEventListener("click", () => {
                // Toggle sidebar visibility without changing the icon
                nav.classList.toggle("show");
                // Adjust padding
                bodypd.classList.toggle("body-pd");
                headerpd.classList.toggle("body-pd");
            });
        }
    };

    showNavbar("header-toggle", "nav-bar", "body-pd", "header");

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll(".nav_link");

    function colorLink() {
        if (linkColor) {
            linkColor.forEach((l) => l.classList.remove("active"));
            this.classList.add("active");
        }
    }

    linkColor.forEach((l) => l.addEventListener("click", colorLink));
});

    </script>
</body>
</html>

               