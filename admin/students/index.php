<!-- <head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head> -->
<?php check_login();?>
<style>
            body {
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
    font-weight: var(--bs-body-font-weight);
    line-height: var(--bs-body-line-height);
    color: var(--bs-body-color);
    text-align: var(--bs-body-text-align);
    background-color: var(--bs-body-bg);
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        min-height: 100vh;
        padding-left: 100px;

        }
        .img-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }
        .modal-dialog {
            max-width: 800px;
            margin: auto; /* Center the modal */
        }
        .modal-content {
            border-radius: 15px;
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
        }
        .btn-link:hover {
            color: #6c757d;
        }
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
    letter-spacing: .5px;
}

.card-title {
        font-weight: bold;
        text-transform: uppercase;

        margin-bottom: 0;
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
    letter-spacing: .5px;;
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
/* Center modals vertically and horizontally */
.modal-dialog {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    min-width: 300px; /* Optional: Set a minimum width for modals */
}

/* Optional: Set a max width for modals */
.modal-content {
    width: 100%;
    max-width: 800px;
}

/* Ensure the modal appears above the table */
.modal {
    z-index: 1050 !important;  /* Ensure modal has a high z-index */
}

.modal-backdrop {
    background-color: transparent !important;
    z-index: 1040 !important;  /* Ensure backdrop appears below the modal */
}
.swal2-container {
    z-index: 1060 !important; /* Set the z-index of SweetAlert higher than modals */
}
.swal2-backdrop {
    z-index: 1050 !important; /* Set the backdrop z-index lower than the SweetAlert */
}

    </style>
</head>
<body>
<div class="card card-outline">
        <div class="card-header">
        <h3 class="card-title">List of Students<button class="btn btn-danger"style="position: absolute; right: 0; transform: translateY(-15%); padding: 3px 8px; font-size: 15px; width: auto; height: 30px;" data-toggle="modal" data-target="#archiveModal">View Archived Students</button></h3>
                        <!-- Button to open the modal -->
                        <div class="d-flex justify-content-end">
            </div>
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
                            <th>Date Created</th> <!-- New Column -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                            $i = 1;
                            // Updated query to exclude students with status = 3 (soft-deleted students)
                            $qry = $conn->query("SELECT *, concat(lastname,', ',firstname,' ', middlename) as name 
                                                FROM `student_list` 
                                                WHERE status != 3   -- Exclude soft-deleted students
                                                ORDER BY concat(lastname,', ',firstname,' ', middlename) ASC");

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
                                    <span class="badge badge-pill badge-success" style='color: green;'>Verified</span>
                                <?php elseif($row['status'] == 2): ?>
                                    <span class="badge badge-pill badge-danger" style='color: red;'>Rejected</span>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-primary" style='color: blue;'>Not Verified</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['date_created'] ? date('Y-m-d H:i:s', strtotime($row['date_created'])) : 'N/A'; ?></td> <!-- Date Created -->
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
                                    
                                    <div class="dropdown-menu" role="menu">
    <!-- View Details -->
    <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
        <span class="fa fa-eye text-dark"></span> View
    </a>
    <div class="dropdown-divider"></div>

    <!-- View COR (kept as is) -->
    <a class="dropdown-item view_cor" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#corModal">
        <span class="fa fa-file text-dark"></span> View COR
    </a>
    <div class="dropdown-divider"></div>

            <!-- Approve User -->
            <?php if ($row['status'] != 1 && $row['status'] != 2): ?>
                <a class="dropdown-item approve_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-email="<?= $row['email'] ?>">
                    <span class="fa fa-check text-success"></span> Approve
                </a>
                <div class="dropdown-divider"></div>
            <?php endif; ?> 
            <!-- <?php if($row['status'] != 1): ?>
                                            <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-name="<?= $row['email'] ?>">
                                                <span class="fa fa-check text-primary"></span> Verify
                                            </a>
                                            <div class="dropdown-divider"></div>
                                        <?php endif; ?> -->
            <!-- Reject User -->
            <?php if ($row['status'] != 1 && $row['status'] != 2): ?>
                <a class="dropdown-item reject_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>" data-email="<?= $row['email'] ?>">
                    <span class="fa fa-times text-danger"></span> Reject
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
             <!-- COR Modal Header -->
            <div id="corModalHeader" class="modal-header d-flex justify-content-between">
        <h5 class="modal-title" id="corModalLabel">Certificate of Registration (COR)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
        </button>
    </div>

            <!-- COR Modal Body -->
            <div id="corModalBody" class="modal-body">
                <!-- The COR details will be displayed here dynamically -->
                <div id="corContent"></div>
            </div>
        </div>
    </div>
</div>
<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Rejection Modal Header -->
            <div id="rejectModalHeader" class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Rejection Modal Body -->
            <div id="rejectModalBody" class="modal-body">
                <label for="rejectionReason">Please enter the reason for rejection:</label>
                <textarea id="rejectionReason" class="form-control" rows="3"></textarea>
            </div>

            <!-- Rejection Modal Footer -->
            <div id="rejectModalFooter" class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="submitRejection">Reject</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Archived Students</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" 
                style="position: absolute; right: 0;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Table to display archived students -->
                <table class="table table-hover table-striped" id="archiveTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Student Number</th>
                            <th>Date Deleted</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="archiveList">
                        <!-- Archived students will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



    
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
    <!-- SweetAlert2 CDN -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->


    <script>
$(document).ready(function () {

// Handle COR link click
$('.view_cor').click(function () {
    var studentId = $(this).attr('data-id');
    console.log('Fetching COR for Student ID:', studentId);

    // Open the COR modal
    $('#corModal').modal('show');

    // Fetch the COR data via AJAX
    $.ajax({
        url: 'students/get_cor.php',
        method: 'GET',
        data: { id: studentId },
        success: function (response) {
            $('#corContent').html(response); // Populate the modal with COR data
        },
        error: function (err) {
            console.error('Error fetching COR:', err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching COR data.',
                showConfirmButton: true
            });
        },
    });
});

// Approve user
$(document).on('click', '.approve_user', function () {
        var studentId = $(this).attr('data-id');
        var studentEmail = $(this).attr('data-email');
        console.log('Sending approval for student:', studentId, studentEmail);

        // Show loading SweetAlert
        Swal.fire({
            title: 'Processing Approval...',
            text: 'Please wait while we approve the student.',
            didOpen: () => {
                Swal.showLoading();
            },
            showConfirmButton: false,
            allowOutsideClick: false
            
        });

        $.ajax({
            url: 'students/approve_reject_account.php',
            method: 'POST',
            data: {
                action: 'approve',
                id: studentId,
                email: studentEmail
            },
            dataType: 'json',
            success: function (response) {
                console.log('Response received:', response);
                Swal.close(); // Close the loading SweetAlert
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        // Update the student status without reloading
                        $("tr[data-id='" + studentId + "'] td.status span")
                            .text("Verified")
                            .removeClass("badge-primary")
                            .addClass("badge-success");

                        // Force page reload after SweetAlert is dismissed
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function (err) {
                console.log('Error:', err);
                Swal.close(); // Close the loading SweetAlert
                Swal.fire({
                    icon: 'warning',
                    title: 'Error',
                    text: 'An error occurred while processing the approval. Please try again.',
                    showConfirmButton: true
                });
            }
        });
    });

    // Reject user
    $(document).on('click', '.reject_user', function () {
        var studentId = $(this).attr('data-id');
        var studentEmail = $(this).attr('data-email');
        $('#rejectModal').modal('show');

        // Handle form submission in the rejection modal
        $('#submitRejection').off('click').on('click', function () {
            var reason = $('#rejectionReason').val();

            if (reason.trim() !== '') {
                console.log('Sending rejection for student:', studentId, studentEmail, reason);

                // Show loading SweetAlert
                Swal.fire({
                    title: 'Processing Rejection...',
                    text: 'Please wait while we reject the student.',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    showConfirmButton: false,
                    allowOutsideClick: false
                });

                $.ajax({
                    url: 'students/approve_reject_account.php',
                    method: 'POST',
                    data: {
                        action: 'reject',
                        id: studentId,
                        email: studentEmail,
                        reason: reason
                    },
                    dataType: 'json',
                    success: function (response) {
                        console.log('Rejection response:', response);
                        Swal.close(); // Close the loading SweetAlert
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected',
                                text: response.message,
                                showConfirmButton: true
                            }).then(() => {
                                // Update the UI to reflect the rejected status
                                $("tr[data-id='" + studentId + "'] td.status span")
                                    .text("Rejected")
                                    .removeClass("badge-primary")
                                    .addClass("badge-danger");

                                // Force page reload after SweetAlert is dismissed
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Error',
                                text: response.message,
                                showConfirmButton: true
                            });
                        }
                        $('#rejectModal').modal('hide');
                    },
                    error: function (err) {
                        console.log('Error:', err);
                        Swal.close(); // Close the loading SweetAlert
                        Swal.fire({
                            icon: 'info',
                            title: 'Error',
                            text: 'An error occurred while processing the rejection. Please try again.',
                            showConfirmButton: true
                        });
                        $('#rejectModal').modal('hide');
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rejection Reason Missing',
                    text: 'Please provide a reason for rejection.',
                    showConfirmButton: true
                });
            }
        });
    });

// Modal setup for proper aria-hidden handling
$('#corModal').on('show.bs.modal', function () {
    $(this).removeAttr('aria-hidden');
    $(this).attr('inert', true); // Use inert to prevent focus on elements outside the modal
});

$('#corModal').on('shown.bs.modal', function () {
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

// Delete user action
$('.delete_data').click(function () {
    var studentId = $(this).attr('data-id');
    var studentEmail = $(this).attr('data-name');
    
    // Ask for confirmation before proceeding with the soft delete
    _conf(
        "Are you sure you want to soft delete <b>" + studentEmail + "</b>?",
        'delete_student',
        [studentId]
    );
});

// Verify user action
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


// Soft delete function using AJAX
function delete_student($id){
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=delete_student",  // Make sure this URL is correct
        method: "POST",
        data: { id: $id },
        dataType: "json",
        success: function (resp) {
            if (resp.status == 'success') {
                location.reload();  // Reload the page to reflect the soft delete
            } else {
                alert_toast("An error occurred.", 'error');
                end_loader();
            }
        },
        error: function (err) {
            console.log(err);
            alert_toast("An error occurred.", 'error');
            end_loader();
        }
    });
}



    // Function for deleting a user
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

    // Function for verifying a user
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

    $('#archiveModal').on('show.bs.modal', function () {
    // Fetch archived students via AJAX
    $.ajax({
        url: _base_url_ + "classes/Users.php?f=get_archived_students",
        method: "GET",
        dataType: "json",
        success: function (response) {
            // Clear the previous data
            $('#archiveList').empty();

            // Check if the response has a 'status' indicating error
            if (response.status === "error") {
                $('#archiveList').append('<tr><td colspan="6" class="text-center">' + response.message + '</td></tr>');
                return;
            }

            // Loop through the archived students and append them to the table
            if (response.length > 0) {
                response.forEach(function (student, index) {
                    $('#archiveList').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${student.name}</td>
                            <td>${student.email}</td>
                            <td>${student.student_number}</td>
                            <td>${student.deleted_at}</td>
                            <td>
                                <button class="btn btn-info restore_student" data-id="${student.id}">Restore</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                $('#archiveList').append('<tr><td colspan="6" class="text-center">No archived students.</td></tr>');
            }
        },
        error: function (err) {
            console.log(err);
            alert_toast("An error occurred while fetching archived students.", 'error');
        }
    });
});

// Restore user function (using AJAX)
function restore_user($id) {
    start_loader();  // Start the loading animation or spinner

    $.ajax({
        url: _base_url_ + "classes/Users.php?f=restore_user",  // Ensure this URL is correct
        method: "POST",
        data: { id: $id },  // Pass the student ID to the PHP function
        dataType: "json",
        success: function (resp) {
            if (resp.status == 'success') {
                Swal.fire("Restored!", "The student account has been restored.", "success");
                location.reload();  // Reload the page to reflect the restoration
            } else {
                Swal.fire("Error!", "An error occurred while restoring the student.", "error");
            }
        },
        error: function (err) {
            console.log(err);
            alert_toast("An error occurred.", 'error');
            end_loader();
        }
    });
}



    // Handle restore button click in the modal
    $('#archiveTable').on('click', '.restore_student', function () {
        var studentId = $(this).data('id');
        _conf(
            "Are you sure you want to restore this student?",
            'restore_user',
            [studentId]
        );
    });
</script>

    