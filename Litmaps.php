<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'Litmaps';
require_once('./config.php');
require_once('inc/topBarNav.php');
require_once('inc/header.php');

// Database connection
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "otas_db"; 

$db = new mysqli($host, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Ensure `student_id` is in session
$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    die("User not logged in as student. Session student_id not set.");
} else {
}


// Fetch recently viewed mappings specific to the logged-in student
$recentMappingsQuery = "SELECT DISTINCT rm.mapping_id 
                        FROM recent_student_mappings rm 
                        WHERE rm.student_id = ?";
$stmt = $db->prepare($recentMappingsQuery);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->bind_result($mappingId);
$recentMappings = [];
while ($stmt->fetch()) {
    $recentMappings[] = $mappingId;
}
$stmt->close();

// Fetch details for each mapping in `$recentMappings`
$recentMappingData = [];
foreach ($recentMappings as $mappingId) {
    $query = "SELECT al.id, al.title, al.year, CONCAT(aa.first_name, ' ', aa.last_name) AS author 
              FROM archive_list al 
              LEFT JOIN archive_authors aa ON al.id = aa.archive_id 
              WHERE al.id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $mappingId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $recentMappingData[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="icon" href="images/LitTrack.png" type="image/png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
<head>
    <title>Literature Mapping Library</title>
    <style>
        .litmap-container {
            width: 80%;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            background-color: #fff; /* Ensure white background */
        }
        .recent-mappings {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        .mapping-card {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        padding: 10px;
    }

    .mapping-info {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 14px;
        color: #333;
        text-align: left;
    }

    .mapping-icon {
        color: #ccc;
        font-size: 40px;
        margin-top: 20px;
    }

    .more-options {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 16px;
        color: #333;
    }
        .add-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ccc;
            border-radius: 8px;
            border: none;
            cursor: pointer;
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
        .more-options {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 16px;
            color: #333;
            cursor: pointer;
        }
        .dropdown-menu {
            position: absolute;
            top: 35px;
            right: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        .custom-large-margin-top {
        margin-top: 100px; /* Add a large margin value */
    }
    </style>
</head>
<body>

<div class="litmap-container custom-large-margin-top">
    <h2>Literature Mapping Library</h2>
    <div class="recent-mappings">
    <?php if (count($recentMappingData) > 0): ?>
    <?php foreach ($recentMappingData as $mapping): ?>
        <?php if (isset($mapping['id']) && !empty($mapping['id'])): ?>
            <div class="mapping-card" onclick="redirectToAddMapping(<?= $mapping['id'] ?>)">
                <p class="mapping-info"><strong><?= $mapping['author'] ?> (<?= $mapping['year'] ?>)</strong></p>
                <div class="mapping-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>
                <i class="bi bi-three-dots-vertical more-options" onclick="toggleOptionsMenu(event, <?= $mapping['id'] ?>)"></i>
                <div class="dropdown-menu" id="options-menu-<?= $mapping['id'] ?>">
                    <a href="#" onclick="event.stopPropagation(); deleteMapping(<?= $mapping['id'] ?>)">Delete</a>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>No recent mappings. Click "Add" to create a new mapping.</p>
<?php endif; ?>
    </div>
    <button class="add-button mt-3" onclick="redirectToAddMapping()">+ Add</button>
</div>


<script>
    function toggleOptionsMenu(event, mappingId) {
        event.stopPropagation();
        closeAllMenus();
        const menu = document.getElementById(`options-menu-${mappingId}`);
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    function closeAllMenus() {
        const menus = document.querySelectorAll('.dropdown-menu');
        menus.forEach(menu => menu.style.display = 'none');
    }

    document.addEventListener('click', closeAllMenus);

    function redirectToAddMapping(mappingId = null) {
    if (!mappingId) {
        // Direct the user to a new page without saving if no ID is provided
        window.location.href = 'AddMapping.php';
        return;
    }

    // Attempt to save the mapping
    fetch('saveMapping.php?id=' + mappingId)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // After saving, redirect to AddMapping with the specified ID
                window.location.href = 'AddMapping.php?id=' + mappingId;
            } else {
                console.error("Failed to save mapping:", data.message);
                alert("Failed to save mapping: " + data.message);
            }
        })
        .catch(error => console.error('Request failed', error));
}



function deleteMapping(mappingId) {
    if (confirm("Are you sure you want to remove this mapping from recent mappings?")) {
        fetch("deleteMapping.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `id=${mappingId}`
        })
        .then(response => response.text())
        .then(responseText => {
            alert(responseText);
            // Redirect to Litmaps.php after successful deletion
            window.location.href = "Litmaps.php";
        })
        .catch(error => {
            console.error("Error removing mapping from recent mappings:", error);
            alert("Error removing mapping from recent mappings.");
        });
    }
}


</script>

</body>
</html>
