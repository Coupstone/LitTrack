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

        .card-tools .btn-primary {
            background-color: #800000;
            border-color: #800000;
            border-radius: 25px; 
        }

        .card-body {
            padding: 20px;
            border-radius: 0 0 15px 15px;
            background-color: #ffffff;
        }

        .table-container {
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
            border-radius: 15px;
            overflow: hidden;
            background-color: #f8f9fa;
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
            letter-spacing: 0.05em;
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
            border-radius: 50px;
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

        .dataTables_length .form-control,
        .dataTables_filter .form-control {
            border-radius: 25px; 
        }

        .table .dropdown-toggle {
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
    </style>
</head>
<body>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">List of Curriculum</h3>
            <div class="card-tools">
                <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-sm btn-primary"><span class="fas fa-plus"></span> Add New Curriculum</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table table-hover table-striped">
                    <colgroup>
                        <col width="5%">
                        <col width="20%">
                        <col width="25%">
                        <col width="25%">
                        <col width="15%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Curriculum</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT c.*, d.name as department from `curriculum_list` c inner join `department_list` d on c.department_id = d.id order by c.`name` asc");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['department'] ?></td>
                            <td><?php echo ucwords($row['name']) ?></td>
                            <td class="text-center">
                                <?php
                                    switch($row['status']){
                                        case '1':
                                            echo "<span class='badge badge-success badge-pill'>Active</span>";
                                            break;
                                        case '0':
                                            echo "<span class='badge badge-secondary badge-pill'>Inactive</span>";
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
                                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#create_new').click(function(){
                uni_modal("Curriculum Details","curriculum/manage_curriculum.php")
            })
            $('.edit_data').click(function(){
                uni_modal("Curriculum Details","curriculum/manage_curriculum.php?id="+$(this).attr('data-id'))
            })
            $('.delete_data').click(function(){
                _conf("Are you sure to delete this Curriculum permanently?","delete_curriculum",[$(this).attr('data-id')])
            })
            $('.view_data').click(function(){
                uni_modal("Curriculum Details","curriculum/view_curriculum.php?id="+$(this).attr('data-id'))
            })
            $('.table td,.table th').addClass('py-1 px-2 align-middle')
            $('.table').dataTable({
                columnDefs: [
                    { orderable: false, targets: 5 }
                ],
            });
        })

        function delete_curriculum($id){
            start_loader();
            $.ajax({
                url: _base_url_+"classes/Master.php?f=delete_curriculum",
                method: "POST",
                data: {id: $id},
                dataType: "json",
                error: err => {
                    console.log(err)
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                },
                success: function(resp){
                    if(typeof resp == 'object' && resp.status == 'success'){
                        location.reload();
                    }else{
                        alert_toast("An error occurred.", 'error');
                        end_loader();
                    }
                }
            })
        }
    </script>