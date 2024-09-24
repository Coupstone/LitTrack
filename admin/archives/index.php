<style>
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
        letter-spacing: 1px;
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
</style>

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
                    <!-- <col width="10%"> -->
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date Created</th>
                        <th>Archive Code</th>
                        <th>Project Title</th>
                        <!-- <th>Curriculum</th> -->
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 1;
                        $curriculum = $conn->query("SELECT * FROM curriculum_list where id in (SELECT curriculum_id from archive_list)");
                        $cur_arr = array_column($curriculum->fetch_all(MYSQLI_ASSOC),'name','id');
                        $qry = $conn->query("SELECT * from archive_list order by year desc, title desc ");
                        while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td data-label="#" class="text-center"><?php echo $i++; ?></td>
                        <td data-label="Date Created"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                        <td data-label="Archive Code"><?php echo ($row['archive_code']) ?></td>
                        <td data-label="Project Title"><?php echo ucwords($row['title']) ?></td>
                        <!-- <td data-label="Curriculum"><?php echo $cur_arr[$row['curriculum_id']] ?></td> -->
                        <td data-label="Status" class="text-center">
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
                        <td data-label="Action" align="center">
                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                Action
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                                <a class="dropdown-item" href="<?= base_url ?>admin/?page=view_research&id=<?php echo $row['id'] ?>"><span class="fa fa-external-link-alt text-gray"></span> View</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item update_status" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-status="<?php echo $row['status'] ?>"><span class="fa fa-check text-dark"></span> Update Status</a>
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

<script>
    $(document).ready(function(){
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this project permanently?","delete_archive",[$(this).attr('data-id')])
        })
        $('.update_status').click(function(){
            uni_modal("Update Details","archives/update_status.php?id="+$(this).attr('data-id')+"&status="+$(this).attr('data-status'))
        })
        $('.table td,.table th').addClass('py-2 px-3 align-middle')
        $('.table').dataTable({
            columnDefs: [
                { orderable: false, targets: 5 }
            ],
        });
    })
    function delete_archive($id){
        start_loader();
        $.ajax({
            url:_base_url_+"classes/Master.php?f=delete_archive",
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