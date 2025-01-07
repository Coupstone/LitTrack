<?php
require_once('config.php');

function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

$result = $conn->query("SELECT id FROM archive_list WHERE uuid IS NULL OR uuid = ''");
while ($row = $result->fetch_assoc()) {
    $uuid = generate_uuid(); // Generate a unique identifier
    $id = $row['id'];
    $conn->query("UPDATE archive_list SET uuid = '$uuid' WHERE id = $id");
}

$result = $conn->query("SELECT uuid, COUNT(*) as count FROM archive_list GROUP BY uuid HAVING count > 1 OR uuid = ''");
while ($row = $result->fetch_assoc()) {
    echo "Duplicate or empty UUID found: " . $row['uuid'] . " with count: " . $row['count'] . "\n";
}

echo "UUIDs populated successfully.";
?>