<?php
require_once('./config.php');

$host = "localhost";
$username = "root";
$password = "";
$database = "otas_db";

$db = new mysqli($host, $username, $password, $database);
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_POST['id'])) {
    $id = $db->real_escape_string($_POST['id']);

    // Duplicate the record in the `archive_list` table
    $query = "INSERT INTO archive_list (title, year) 
              SELECT title, year FROM archive_list WHERE id = $id";
    if ($db->query($query) === TRUE) {
        $newId = $db->insert_id; // Get the ID of the duplicated record

        // Duplicate related data in the `archive_authors` table if applicable
        $queryAuthors = "INSERT INTO archive_authors (archive_id, first_name, last_name) 
                         SELECT $newId, first_name, last_name FROM archive_authors WHERE archive_id = $id";
        $db->query($queryAuthors);

        echo "Mapping duplicated successfully.";
    } else {
        echo "Error duplicating mapping: " . $db->error;
    }
}

$db->close();
?>
