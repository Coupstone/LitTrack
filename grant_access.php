<?php
require_once('./config.php'); // Database connection


if (isset($_GET['token'])) {
    $token = $_GET['token'];


    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Grant Access Confirmation</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='https://code.jquery.com/jquery-3.6.0.min.js'></script> <!-- Include jQuery -->
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to grant the request?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, grant access',
                cancelButtonText: 'No, deny access'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Grant access via AJAX
                    $.ajax({
                        url: 'grant_access.php',
                        method: 'POST',
                        data: { action: 'grant', token: '{$token}' },
                        success: function() {
                            Swal.fire({
                                title: 'Success',
                                text: 'Access granted successfully.',
                                icon: 'success'
                            }).then(() => {
                                window.location.href = 'https://mail.google.com'; // Redirect to Gmail
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to process the request.',
                                icon: 'error'
                            });
                        }
                    });
                } else {
                    // Deny access via AJAX
                    $.ajax({
                        url: 'grant_access.php',
                        method: 'POST',
                        data: { action: 'deny', token: '{$token}' },
                        success: function() {
                            Swal.fire({
                                title: 'Request Denied',
                                text: 'Access request denied successfully.',
                                icon: 'info'
                            }).then(() => {
                                window.location.href = 'https://mail.google.com'; // Redirect to Gmail
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to process the request.',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        </script>
    </body>
    </html>";
    exit;
}








    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? null;
        $token = $_POST['token'] ?? null;




        if ($token) {
            // Verify the token
            $stmt = $conn->prepare("SELECT * FROM access_requests WHERE token = ? AND status = 'pending'");
            $stmt->bind_param('s', $token);
            $stmt->execute();
            $result = $stmt->get_result();




            if ($result->num_rows > 0) {
                $request = $result->fetch_assoc();
                $request_id = $request['id'];
                $archive_id = $request['archive_id'];




                if ($action === 'grant') {
                    // Update the status to 'granted'
                    $update_stmt = $conn->prepare("UPDATE access_requests SET status = 'granted' WHERE id = ?");
                    $update_stmt->bind_param('i', $request_id);




                    if ($update_stmt->execute()) {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                title: 'Success',
                                text: 'Access request granted successfully.',
                                icon: 'success'
                            }).then(() => {
                                // Reload the page to reflect the updated content
                                window.location.reload();
                            });
                        </script>";
                    } else {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to update access request status. Please try again later.',
                                icon: 'error'
                            });
                        </script>";
                    }
                   
                } elseif ($action === 'deny') {
                    // Update the status to 'denied'
                    $update_stmt = $conn->prepare("UPDATE access_requests SET status = 'denied' WHERE id = ?");
                    $update_stmt->bind_param('i', $request_id);




                    if ($update_stmt->execute()) {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                title: 'Request Denied',
                                text: 'Access request denied successfully.',
                                icon: 'info'
                            }).then(() => {
                                window.location.href = 'view_archive.php';
                            });
                        </script>";
                    } else {
                        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            Swal.fire({
                                title: 'Error',
                                text: 'Failed to update access request status. Please try again later.',
                                icon: 'error'
                            });
                        </script>";
                    }
                }
            } else {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        title: 'Invalid Request',
                        text: 'Invalid or expired token.',
                        icon: 'warning'
                    }).then(() => {
                        window.location.href = 'view_archive.php';
                    });
                </script>";
            }
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Error',
                    text: 'No token provided.',
                    icon: 'error'
                }).then(() => {
                    window.location.href = 'view_archive.php';
                });
            </script>";
        }
    }
    ?>