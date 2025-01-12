<html lang="en" class="" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="dist/css/adminlte.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var (--bs-body-bg);
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            min-height: 100vh;
            padding-left: 100px;
        }
        .card-outline.card-primary {
            border-color: #800000; 
            border-radius: 15px;
        }
        .card-header {
            background-color: #800000; 
            color: white;
            padding: 15px;
            border-radius: 15px 15px 0 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 0;
        }
        .table-container {
            background-color: #ffffff;
            border-radius: 0 0 15px 15px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
        }
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .table thead {
            color: #343a40;
        }
        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
            padding: 12px 15px;
            border: none;
            border-bottom: 2px solid #dee2e6;
        }
        .table th {
            font-weight: bold;
            text-transform: uppercase;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .table-hover tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }
        .badge-pill {
            font-size: 0.85em;
            padding: 0.5em 1em;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table td {
                display: block;
                width: 100%;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #dee2e6;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 15px;
                text-align: left;
                font-weight: bold;
                color: #343a40;
            }

            .table-container {
                padding: 15px;
            }
        }
        .btn, .btn-flat, .btn-default, .btn-sm, .btn-primary, .btn-secondary {
            border-radius: 25px; 
        }
        .dropdown-menu {
            border-radius: 15px; 
        }
        .pagination .page-item .page-link {
            border-radius: 0;  
        }
        .dataTables_length .form-control {
            border-radius: 25px; 
        }
        .dataTables_filter .form-control {
            border-radius: 25px; 
        }
        .table .dropdown-toggle {
            border-radius: 25px; 
        }
        .card-header h3 {
            font-size: 1.25em;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Researches</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="20%">
                    <col width="20%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created</th>
                        <th>Archive Code</th>
                        <th>Project Title</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * from archive_list order by year desc, title desc ");
                        while($row = $qry->fetch_assoc()): ?>
                    <tr>
                        <td data-label="#" class="text-center"><?php echo $i++; ?></td>
                        <td data-label="Date Created"><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td data-label="Archive Code"><?php echo ($row['archive_code']) ?></td>
                        <td data-label="Project Title"><?php echo ucwords($row['title']) ?></td>
                        <td data-label="Status" class="text-center">
                            <?php
                                switch($row['status']){
                                    case '1':
                                        echo "<span class='badge badge-success badge-pill' style='color: green;'>Published</span>";
                                        break;
                                    case '0':
                                        echo "<span class='badge badge-secondary badge-pill' style='color: black;'>Not Published</span>";
                                        break;
                                    case '2':
                                        echo "<span class='badge badge-danger badge-pill' style='color: red;'>Rejected</span>";
                                        break;
                                }
                            ?>
                        </td>
                        <td data-label="Action" align="center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="<?= base_url ?>admin/?page=view_research&id=<?php echo $row['id'] ?>"><span class="fa fa-external-link-alt text-gray"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    
                                    <?php
                                    $time_diff = time() - strtotime($row['date_created']);
                                    $is_recently_uploaded = $time_diff < 86400; // 86400 seconds = 1 day

                                    if ($row['status'] == 0 && $is_recently_uploaded): ?>
                                        <a class="dropdown-item update_status" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-status="<?php echo $row['status'] ?>"><span class="fa fa-check text-dark"></span> Update Status</a>
                                    <?php elseif ($row['status'] == 1 || $row['status'] == 2): ?>
                                        <span class="dropdown-item text-muted"><span class="fa fa-lock text-warning"></span> Status Locked</span>
                                    <?php endif; ?>

                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
    <span class="fa fa-trash text-danger"></span> Delete
</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
$(document).ready(function(){
    // Delete archive functionality with SweetAlert
    $('.delete_data').click(function(){
        var id = $(this).attr('data-id');  // Get the archive ID from the data-id attribute
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
                delete_archive(id);  // Call the delete function if confirmed
            }
        });
    });

    // Update status functionality (if needed)
    $('.update_status').click(function(){
        uni_modal("Update Details", "archives/update_status.php?id=" + $(this).attr('data-id') + "&status=" + $(this).attr('data-status'));
    });
    
    // Table styling for consistency
    $('.table td, .table th').addClass('py-2 px-3 align-middle');
    
    // DataTable initialization with column sorting disabled on the last column
    $('.table').dataTable({
        columnDefs: [
            { orderable: false, targets: [5] }  // Disable sorting on the 5th column (action buttons)
        ],
    });
});

// Function to delete archive via AJAX
function delete_archive(id){
    start_loader();  // Show loader to indicate action is in progress
    
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_archive",  // Make sure the URL is correct
        method: "POST",
        data: { id: id },  // Send archive ID to delete
        dataType: "json",  // Expect JSON response
        error: function(err) {
            console.log(err);  // Log any errors
            alert("An error occurred while deleting the archive.");
            end_loader();  // Hide loader after error
        },
        success: function(resp) {
            if (resp.status == 'success') {
                alert("Project successfully deleted.");
                location.reload();  // Reload the page to reflect changes
            } else {
                alert("Failed to delete project. Please try again.");
            }
            end_loader();  // Hide loader after the operation finishes
        }
    });
}

</script>
</body>
</html>
