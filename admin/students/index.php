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
            overflow: hidden;
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
            padding: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25); 
            border-radius: 15px;
            overflow: hidden;
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
            <h3 class="card-title">List of Students</h3>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 1;
                            $qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ', middlename) as name from `student_list`  order by concat(lastname,', ',firstname,' ', middlename) asc ");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td class="text-center"><img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
                            <td><?php echo ucwords($row['name']) ?></td>
                            <td><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
                            <td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-pill badge-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge badge-pill badge-primary">Not Verified</span>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    <?php if($row['status'] != 1): ?>
                                    <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>"  data-name="<?= $row['email'] ?>"><span class="fa fa-check text-primary"></span> Verify</a>
                                    <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?= $row['email'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
            $('.delete_data').click(function(){
                _conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Student List permanently?","delete_user",[$(this).attr('data-id')])
            })
            $('.table td,.table th').addClass('py-1 px-2 align-middle')
            $('.verify_user').click(function(){
                _conf("Are you sure to verify <b>"+$(this).attr('data-name')+"<b/>?","verify_user",[$(this).attr('data-id')])
            })
            $('.view_details').click(function(){
                uni_modal('Student Details',"students/view_details.php?id="+$(this).attr('data-id'),'mid-large')
            })
            $('.table').dataTable();

        })
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