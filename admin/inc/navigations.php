<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">

    <!-- Inline CSS -->
<style>
    /* Import Fonts */
@import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

/* Root Variables */
:root {
    --header-height: 3rem;
    --nav-width: 73px; /* Sidebar width */
    --first-color: #FFB2B2; /* Sidebar background color */
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

    transform: translateX(-13%); /* Moves the container up by 10% of its height */

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
/* Adjust header styles */
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

/* Profile Dropdown */
.custom-dropdown {
    position: relative;
    display: inline-block;
}

.custom-dropdown .custom-dropdown-menu {
    display: none;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    list-style: none;
    padding: 0;
    z-index: 1000;
    right: 0;  /* Align the dropdown to the right */
    width: 100%;

}
.custom-dropdown {
    position: relative;
    display: inline-block;
    margin-top: 20px;
}
.custom-dropdown .custom-dropdown-menu li {
    padding: 10px;
}

.custom-dropdown .custom-dropdown-menu li:hover {
    background-color: #f7f7f7;
}

.custom-dropdown.show .custom-dropdown-menu {
    display: block;
}

/* Adjust alignment of the profile dropdown */
.d-flex.justify-content-end {
    display: flex;
    justify-content: flex-end; /* Align the profile dropdown to the right */
    width: 100%;
}

</style>
<body id="body-pd">
<header class="header" id="header">
    <div class="header_toggle d-flex align-items-center">
        <!-- Hamburger Button -->
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>

    <div class="d-flex justify-content-end">
        <!-- Profile Dropdown -->
        <div class="btn-group custom-dropdown">
            <a href="#" 
               class="btn btn-light btn-sm d-flex align-items-center shadow-sm toggle-dropdown" 
               style="border-radius: 25px; padding: 5px 15px;">

                <!-- Profile Image -->
                <img src="<?php echo validate_image($_settings->userdata('avatar')) ?>"
                     class="img-circle border border-primary shadow-sm" 
                     alt="User Image" 
                     style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">

                <!-- User Name -->
                <span class="fw-bold text-dark" style="font-size: 0.9rem;"><?php echo ucwords($_settings->userdata('firstname') . ' ' . $_settings->userdata('lastname')) ?></span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow custom-dropdown-menu">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo base_url . 'admin/?page=user' ?>">
                        <span class="fa fa-user me-2 text-primary"></span> My Account
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="<?php echo base_url . '/classes/Login.php?f=logout' ?>">
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
                <img src="../admin/sidebarlogo.png" alt="Logo" class="nav_logo-img mr-4">
            </a>
            <div class="nav_list">
                <a href="./index.php" class="nav_link <?php echo ($page == 'index') ? 'active' : '';?>">
                    <i class='bx bx-home nav_icon'></i>
                    <span class="nav_name">Home</span>
                </a>
                <a href="<?php echo base_url ?>admin/?page=students" class="nav_link <?php echo ($page == 'students') ? 'active' : '';?>">
                    <i class='bx bx-user nav_icon'></i>
                    <span class="nav_name">Student List</span>
                </a>
                <a href="<?php echo base_url ?>admin/?page=archives" class="nav_link <?php echo ($page == 'archives') ? 'active' : '';?>">
                    <i class='bx bx-book nav_icon'></i>
                    <span class="nav_name">Research List</span>
                </a>
                <!-- Updated Department List with Building Icon -->
                <a href="<?php echo base_url ?>admin/?page=departments" class="nav_link <?php echo ($page == 'departments') ? 'active' : '';?>">
                    <i class='bx bx-building nav_icon'></i>
                    <span class="nav_name">Department List</span>
                </a>
                <a href="<?php echo base_url ?>admin/?page=curriculum" class="nav_link <?php echo ($page == 'curriculum') ? 'active' : '';?>">
                    <i class='bx bx-bookmark nav_icon'></i>
                    <span class="nav_name">Curriculum List</span>
                </a>
                <a href="<?php echo base_url ?>/admin/?page=uploadresearch" class="nav_link <?php echo ($page == 'uploadresearch') ? 'active' : '';?>">
                    <i class='bx bx-upload nav_icon'></i>
                    <span class="nav_name">Upload Research</span>
                </a>
        </div>
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
document.addEventListener("DOMContentLoaded", () => {
    const dropdownToggle = document.querySelector(".toggle-dropdown");
    const dropdownMenu = document.querySelector(".custom-dropdown-menu");
    const dropdown = document.querySelector(".custom-dropdown");

    dropdownToggle.addEventListener("click", (e) => {
        e.preventDefault();
        dropdown.classList.toggle("show");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });
});
    </script>
</body>
</html>