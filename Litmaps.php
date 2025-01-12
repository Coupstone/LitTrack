<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'Litmaps';
require_once('./config.php');
check_login();
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

// Ensure user is logged in
$student_id = $_SESSION['student_id'] ?? null;
if (!$student_id) {
    die("User not logged in. Please log in first.");
}

// Fetch recent mappings
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

// Fetch mapping details
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

// Handle delete mapping request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $mapping_id = intval($_POST['id']);

    // Prepare and execute delete statement
    $stmt = $db->prepare("DELETE FROM mappings WHERE id = ?");
    $stmt->bind_param("i", $mapping_id);
    if ($stmt->execute()) {
        echo "Mapping deleted successfully.";
    } else {
        echo "Error deleting mapping: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Literature Mapping Library</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
/* General Body Styles */
body {
    font-family: var(--bs-body-font-family);
    font-size: var(--bs-body-font-size);
    font-weight: var(--bs-body-font-weight);
    line-height: var(--bs-body-line-height);
    color: var(--bs-body-color);
    text-align: var(--bs-body-text-align);
    background-color: var(--bs-body-bg);
}

.header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 10px;
}

/* Shared Container Styles */
.wrapper {
    max-width: 90%;
    margin: 20px auto;
}

/* Card Styles */
.card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
}

.card-header {
    background-color: #fff;
    padding: 20px;
    border-bottom: 1px solid #ddd;
}

.header-title {
    font-size: 24px;
    color: #333;
    font-weight: bold;
}

/* Recent Mappings */
.recent-mappings {
    display: grid;
    grid-template-rows: repeat(2, minmax(200px, auto));
    grid-template-columns: repeat(5, 1fr);
    grid-gap: 20px;
    padding: 20px;
    justify-items: center;
    margin-top: 20px;
    align-items: start;
}

.mapping-card {
    position: relative; /* Important for positioning the dots menu */
    width: calc(16% - 10px);
    max-width: 200px;
    height: 200px;
    border: 1px dashed #ced4da;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    padding: 10px;
    margin: 5px;
    box-sizing: border-box;
}

.mapping-card:hover {
    background-color: #e9ecef;
}

.add-card {
    border: 2px dashed #6c757d;
}

.add-icon {
    text-align: center;
}

.mapping-icon {
    font-size: 60px;
    color: #6c757d;
}

.mapping-info {
    font-size: 16px;
    color: #333;
}

/* Style for the 3-Dot Menu */
.dots-menu {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    font-size: 20px;
    color: #6c757d;
    z-index: 2; /* Ensure it's above the menu options */
}

.dots-menu:hover {
    color: #343a40;
}

/* Style for the Dropdown Menu */
.menu-options {
    display: none;
    position: absolute;
    top: 30px; /* Adjusted for better spacing */
    right: 10px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
    z-index: 1;
    min-width: 100px; /* Prevent menu from being too narrow */
}

.menu-options.show { /* Use a class to toggle visibility */
    display: block;
}

.menu-options a {
    display: block;
    padding: 8px 12px;
    color: #343a40;
    text-decoration: none;
    font-size: 16px;
    white-space: nowrap; /* Prevent text from wrapping */
}

.menu-options a:hover {
    background-color: #f1f1f1;
}

/* Media Queries */
@media (max-width: 1200px) {
    .recent-mappings {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .recent-mappings {
        grid-template-columns: 1fr;
    }
}
.container{
    transform: translateY(16%); /* Moves the container up by 10% of its height */
}
    </style>
</head>
<body>

<?php
// Check if the number of mappings is less than 9
$maxMappings = 9;
$currentMappingsCount = count($recentMappingData);

// Set the maximum number of cards per row
$maxRow = 12; // Maximum cards in a row (including Add button)
$cardCount = 0;
?>


<div class="container d-flex justify-content-center align-items-center mt-1">
        <div class="card card-outline card-primary shadow-lg col-lg-10 col-md-10 col-sm-12">
            <div class="card-header bg-white">
                <h3 class="card-title text-center text-dark mt-3" style="font-size: 20px;"><b>Literature Mapping</b></h3>
            </div>
            <div class="card-body">
                <div class="recent-mappings-container">
                <div class="row d-flex justify-content-center gap-3" style="height: 470px; overflow-y: auto;">
                        <!-- Add New Mapping Card -->
                        <div class="mapping-card add-card" onclick="redirectToAddMapping()">
                            <div class="add-icon text-center">
                                <p class="text-primary fs-2">+</p>
                                <p class="text-dark">New Litmap</p>
                            </div>
                        </div>

                        <!-- Display Recent Mappings -->
                        <?php if ($currentMappingsCount > 0): ?>
                            <?php foreach ($recentMappingData as $mapping): ?>
    <div class="mapping-card" onclick="redirectToAddMapping(<?= $mapping['id'] ?>)">
        <!-- 3-Dot Menu -->
        <div class="dots-menu" onclick="toggleMenu(event, <?= $mapping['id'] ?>)">
            <i class="fas fa-ellipsis-v"></i>
        </div>

        <!-- Dropdown Menu (View, Delete) -->
        <div id="menu-<?= $mapping['id'] ?>" class="menu-options">
            <a href="javascript:void(0);" onclick="redirectToAddMapping(<?= $mapping['id'] ?>)">View</a>
            <a href="javascript:void(0);" onclick="deleteMapping(event, <?= $mapping['id'] ?>)">Delete</a>
        </div>

        <!-- Mapping Content -->
        <p class="text-dark text-center">
            <?= htmlspecialchars($mapping['author']) ?> (<?= htmlspecialchars($mapping['year']) ?>)
        </p>
    </div>
<?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function redirectToAddMapping(id = null) {
            const currentMappingsCount = <?= $currentMappingsCount ?>; // Pass PHP count
            const maxMappings = 9;

            if (currentMappingsCount >= maxMappings && !id) {
                // SweetAlert2 error for limit reached
                Swal.fire({
                    icon: 'error',
                    title: 'Limit Reached',
                    text: 'You can only add up to 9 literature mappings.',
                    confirmButtonColor: '#d33'
                });
            } else {
                // Redirect to the add mapping page
                window.location.href = id ? `AddMapping.php?id=${id}` : 'AddMapping.php';
            }
        }
// Function to redirect to addmapping.php
function redirectToAddMapping(id) {
    window.location.href = 'addmapping.php?id=' + id;
}

// Function to toggle the dropdown menu when 3 dots are clicked
function toggleMenu(event, id) {
    event.stopPropagation(); // Prevent the event from propagating to the body
    document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none'); // Close all menus
    document.getElementById('menu-' + id).style.display = 'block'; // Show the clicked menu
}

// Close the dropdown menu if the user clicks outside of it
document.addEventListener('click', () => {
    document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none');
});

// Function to delete a mapping
function deleteMapping(event, id) {
    event.stopPropagation(); // Prevent click from closing the menu immediately

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('deleteMapping.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            }).then(response => response.text())
            .then(data => {
                Swal.fire(
                    'Deleted!',
                    data, // Display the server's response (success/error)
                    'success'
                ).then(() => {
                    window.location.href = 'Litmaps.php';
                });
            }).catch(error => {
                Swal.fire(
                    'Error!',
                    'An error occurred during deletion.',
                    'error'
                );
            });
        } else if (result.isDismissed) {
            // User clicked cancel or closed the modal
            // No need to redirect as the user is already on the page
            document.querySelectorAll('.menu-options').forEach(menu => menu.style.display = 'none'); //Close the menu
        }
    });
}
    </script>
</body>
</html>