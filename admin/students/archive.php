<?php
include '../../config.php'; // Include your database connection

// Query to get soft-deleted students
$qry = $conn->query("SELECT *, concat(lastname,', ',firstname,' ', middlename) as name 
                     FROM student_list 
                     WHERE deleted_at IS NOT NULL 
                     ORDER BY deleted_at DESC");

// Display the list of soft-deleted students
while ($row = $qry->fetch_assoc()):
?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo ucwords($row['name']); ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['student_number']; ?></td>
    <td><span class="badge badge-pill badge-danger">Deleted</span></td>
    <td><?php echo date('Y-m-d H:i:s', strtotime($row['deleted_at'])); ?></td>
    <td>
        <!-- Restore button for soft-deleted students -->
        <button class="btn btn-info restore_user" data-id="<?= $row['id'] ?>">Restore</button>
    </td>
</tr>
<?php endwhile; ?>
