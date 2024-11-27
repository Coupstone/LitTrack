<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>

<style>
.img-avatar {
    width: 45px;
    height: 45px;
    object-fit: cover;
    object-position: center center;
    border-radius: 50%;
}

.card-outline.card-primary {
    border-color: #800000; 
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
}

.card-header {
    background-color: #800000; 
    color: white;
    padding: 15px;
    border-radius: 15px 15px 0 0;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.card-title {
    font-weight: bold; 
}

.card-body {
    padding: 20px;
    border-radius: 0 0 15px 15px;
    background-color: #ffffff;
}

.table {
    width: 100%;
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 15px;
    overflow: visible;
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
    padding: 12px 15px;
    position: relative;
}

.table th {
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background-color: #f8f9fa;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f2f2f2;
}

.table-hover tbody tr:hover {
    background-color: #e9ecef;
    cursor: pointer;
}

.table-container {
    position: relative;
    overflow: visible;
    z-index: 1;
}

.badge-pill {
    font-size: 0.85em;
    padding: 0.5em 1em;
    font-weight: bold;
    border-radius: 50px;
}

.btn, .btn-flat, .btn-default, .btn-sm, .btn-primary, .btn-secondary {
    border-radius: 25px; 
}

/* .dropdown {
    position: relative;
    z-index: 1060;
}

.dropdown-menu {
    border-radius: 15px;
    background-color: white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    max-height: 300px;
    overflow-y: auto;
    width: auto;
    min-width: 10rem;
    z-index: 1060;
}

.dropdown-item {
    padding: 8px 12px;
    font-size: 0.9rem;
} */

.dropdown-item:hover {
    background-color: #f1f1f1;
}

.dropdown-toggle {
    border-radius: 25px;
    padding: 6px 12px;
}

.table .dropdown-toggle {
    border-radius: 25px; 
    z-index: 1060;
}

.pagination .page-item .page-link {
    border-radius: 0; 
}

.dataTables_length .form-control,
.dataTables_filter .form-control {
    border-radius: 25px; 
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
}

.modal-dialog {
    max-width: 800px;
}

.modal-header {
    background-color: #800000;
    color: white;
}

.modal-body {
    background-color: #f8f9fa;
    padding: 15px;
}

.btn-link {
    color: #800000;
    /* text-decoration: underline; */
}

.btn-link:hover {
    color: #6c757d;
}

.card-header h3 {
    font-size: 1.25em;
    font-weight: bold;
}
.btn:focus {
    outline: none;
    box-shadow: none;
}

    </style>
</head>
<body>
<div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">List of Students</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Student Number</th> <!-- New Column for Student Number -->
                            <th>Status</th>
                            <!-- <th>COR</th> New Column for COR link -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT *, concat(lastname,', ',firstname,' ', middlename) as name from `student_list` order by concat(lastname,', ',firstname,' ', middlename) asc ");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
                            <td><?php echo ucwords($row['name']) ?></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
                            <td><?php echo $row['student_number'] ?></td> <!-- Display Student Number -->
                            <td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-pill badge-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-primary">Not Verified</span>
                                <?php endif; ?>
                            </td>
                            <!-- <td class="text-center">
                            <button type="button" class="btn btn-link view_cor" data-toggle="modal" data-target="#corModal" data-id="<?php echo $row['id']; ?>">View COR</button>
                            </td> -->
                            <td align="center">
                                <!-- Dropdown button -->
                                <div class="dropdown">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <!-- Dropdown menu -->
                                    <div class="dropdown-menu" role="menu">
                                        <!-- View Details -->
                                        <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                            <span class="fa fa-eye text-dark"></span> View
                                        </a>
                                        <div class="dropdown-divider"></div>

                                        <!-- View COR (moved inside dropdown) -->
                                        <a class="dropdown-item view_cor" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#corModal">
                                            <span class="fa fa-file text-dark"></span> View COR
                                        </a>
                                        <div class="dropdown-divider"></div>

                                        <!-- Verify User (if not verified) -->
                                        <?php if($row['status'] != 1): ?>
                                            <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-name="<?= $row['email'] ?>">
                                                <span class="fa fa-check text-primary"></span> Verify
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        <?php endif; ?>

                                        <!-- Delete User -->
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-name="<?= $row['email'] ?>">
                                            <span class="fa fa-trash text-danger"></span> Delete
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- COR Modal -->
<div class="modal fade" id="corModal" tabindex="-1" role="dialog" aria-labelledby="corModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="corModalLabel">Certificate of Registration (COR)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- The COR details will be displayed here dynamically -->
                <div id="corContent"></div>
            </div>
        </div>
    </div>
</div>


    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
$(document).ready(function () {
    // Handle COR link click
    $('.view_cor').click(function () {
        var studentId = $(this).attr('data-id');
        console.log('Fetching COR for Student ID:', studentId);

        // Open the COR modal
        $('#corModal').modal('show');

        // Wait for the modal to fully open
        $('#corModal').on('shown.bs.modal', function () {
            // Dynamically set the iframe source again to ensure it loads in mobile view
            var iframe = $('#corModal iframe');
            iframe.attr('src', iframe.attr('src')); // Refresh the iframe
            console.log('Modal opened:', $('#corModal').is(':visible'));
        });

        // Fetch the COR data via AJAX
        $.ajax({
            url: 'students/get_cor.php', // Adjusted path
            method: 'GET',
            data: { id: studentId },
            success: function (response) {
                $('#corContent').html(response); // Populate the modal with COR data
            },
            error: function (err) {
                console.error('Error fetching COR:', err); // Log error details
                alert('An error occurred while fetching COR data.');
            },
        });
    });

    // Modal setup for proper aria-hidden handling
    $('#corModal').on('show.bs.modal', function () {
        $(this).removeAttr('aria-hidden');
        $(this).attr('inert', true); // Use inert to prevent focus on elements outside the modal
    });

    $('#corModal').on('shown.bs.modal', function () {
        // Ensure the modal gets focus
        $(this).focus();
        $(this).removeAttr('inert'); // Remove inert once the modal is visible
    });

    // Fix aria-hidden on window resize
    $(window).on('resize', function () {
        if ($('#corModal').hasClass('show')) {
            $('#corModal').removeAttr('aria-hidden');
        }
    });

    // Initialize DataTables with error handling
    try {
        $('.table').dataTable();
        console.log('DataTable initialized successfully.');
    } catch (e) {
        console.error('Error initializing DataTables:', e);
    }

    // Add generic logging for debugging other interactions
    $('.delete_data').click(function () {
        console.log('Delete button clicked for Student ID:', $(this).attr('data-id'));
        _conf(
            "Are you sure to delete <b>" + $(this).attr('data-name') + "</b>?",
            'delete_user',
            [$(this).attr('data-id')]
        );
    });

    $('.verify_user').click(function () {
        console.log('Verify button clicked for Student ID:', $(this).attr('data-id'));
        _conf(
            "Are you sure to verify <b>" + $(this).attr('data-name') + "</b>?",
            'verify_user',
            [$(this).attr('data-id')]
        );
    });

     // Handle View Details (Dynamic Modal)
     $('.view_details').click(function () {
        var studentId = $(this).attr('data-id');
        uni_modal('Student Details', 'students/view_details.php?id=' + studentId, 'mid-large');
    });
});

        function delete_user($id){
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=delete_student",
                method:"POST",
                data:{id: $id},
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("An error occurred.",'error');
                    end_loader();
                },
                success:function(resp){
                    if(typeof resp== 'object' && resp.status == 'success'){
                        location.reload();
                    }else{
                        alert_toast("An error occurred.",'error');
                        end_loader();
                    }
                }
            })
        }
        function verify_user($id){
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Users.php?f=verify_student",
                method:"POST",
                data:{id: $id},
                dataType:"json",
                error:err=>{
                    console.log(err)
                    alert_toast("An error occurred.",'error');
                    end_loader();
                },
                success:function(resp){
                    if(typeof resp== 'object' && resp.status == 'success'){
                        location.reload();
                    }else{
                        alert_toast("An error occurred.",'error');
                        end_loader();
                    }
                }
            })
        }
    </script>
    