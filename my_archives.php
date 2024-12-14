<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="styles/main.css" rel="stylesheet">
    <link rel="icon" href="images/LitTrack.png" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
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
        padding-left: 0;
        
        }
        .content {
            margin-left: 40px;
            margin-top: 10px;
            transform: translateY(-.5%); /* Moves the container up by 10% of its height */
        }
        .card-header {
            background-color: #C4D7F1;
            color: 007bff;
        }
        .card-header h5 {
            margin: 0;
        }
        .card-title {
            font-size: 1rem;
            margin: 0;
            font-weight: bold;
        }
        table {
            margin-top: 10px;
        }
        thead {
            background-color: #007bff;
            color: #fff;
        }
        thead th {
            text-align: center;
            font-weight: 600;
        }
        tbody tr:hover {
            background-color: #e9ecef;
        }
        .badge-pill {
            font-size: 0.875rem;
        }
        .btn {
            font-size: 0.875rem;
        }
        .dropdown-menu {
            min-width: 120px;
        }
        .table {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }
        .align-middle {
            vertical-align: middle !important;
        }
        body.sidebar-collapsed #content {
            margin-left: 60px;
        }
        .card-header {
            background-color: #C4D7F1;
            color: 007bff;
        }
        .card-header h5 {
            margin: 0;
        }

        @media (max-width: 768px) {
            body.sidebar-expanded #content {
                margin-left: 0px;
                
            }
        }

    </style>
<body>
<div class="content py-3">
    <div class="container-fluid">
        <div class="card card-outline card-primary shadow rounded-0">
            <div class="card-header rounded-0">
                <h4 class="card-title">Submitted Projects</h4>
            </div>
            <div class="card-body rounded-0">
                <div class="container-fluid">
                    <table class="table table-hover table-striped">
                        <colgroup>
                            <col width="5%">
                            <col width="8%">
                            <col width="60%">
                            <col width="15%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Project Title</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                $qry = $conn->query("SELECT * from `archive_list` where student_id = '{$_settings->userdata('id')}' order by unix_timestamp(`date_created`) asc ");
                                while($row = $qry->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++; ?></td>
                                    <td><?php echo date("Y-m-d", strtotime($row['date_created'])) ?></td>
                                    <td><?php echo ucwords($row['title']) ?></td>
                                    <td class="text-center">
                                        <?php
                                            switch($row['status']){
                                                case '1':
                                                    echo "<span class='badge badge-success badge-pill'>Published</span>";
                                                    break;
                                                case '0':
                                                    echo "<span class='badge badge-secondary badge-pill'>Not Published</span>";
                                                    break;
                                            }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item" href="<?= base_url ?>./?page=view_archive&id=<?php echo $row['id'] ?>" target="_blank"><span class="fa fa-external-link-alt text-gray"></span> View</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>