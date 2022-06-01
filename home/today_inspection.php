<?php
	$page_title = "Drivaar";
	include 'DB/config.php';
    $page_id=82;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        if($_SESSION['userid']==1)
        {
           $userid='%'; 
        }
        else
        {
           $userid = $_SESSION['userid']; 
        }
    }
    else
    {
        header("location: login.php");  
    }
?>
<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

        <link rel="stylesheet" href="countrycode/build/css/demo.css">
       <style type="text/css">
            .titlehead {
                  font-size: 28px;
                  font-weight: 500;
            }
        </style>
    </head>

    <body class="skin-default-dark fixed-layout">

        <?php

        include('loader.php');

        ?>

        <div id="main-wrapper">

            <?php

            include('header.php');

            // include('menu.php');

            ?>

           <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                  <div class="form-group has-primary">
                                                       <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable();">
                                                        <option value="%">All Depot</option>
                                                            <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                                    WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '".$userid."'";
                                                                $strow =  $mysql -> selectFreeRun($statusquery);
                                                                while($statusresult = mysqli_fetch_array($strow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                    </select>   
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                 <div class="form-group has-primary">
                                                    <select class="select form-control custom-select">
                                                    <option value="%">All Drivers</option>
                                                    <?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                        $query = "SELECT DISTINCT c.`id`,c.`name` FROM `tbl_vehicles` v  
                                                                  INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                  INNER JOIN `tbl_vehiclerental_agreement` a ON a.`vehicle_id`=v.`id`
                                                                  INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver_id`
                                                                  WHERE v.`isdelete`=0 AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date`";
                                                        $row =  $mysql -> selectFreeRun($query);
                                                        while($result = mysqli_fetch_array($row))
                                                        {
                                                          ?>
                                                              <option value="<?php echo $result['id']?>"><?php echo $result['name']?></option>
                                                          <?php
                                                        }
                                                        $mysql -> dbDisconnect();
                                                    ?>   
                                                    </select>   
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                               <div class="form-group has-primary">
                                                    <select class="select form-control custom-select">
                                                    <option value="%">All Vehicles</option>
                                                    <?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                        $vquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                                                FROM `tbl_vehicles` v 
                                                                LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                                                INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                WHERE v.`isdelete`= 0 ";
                                                        $vrow =  $mysql -> selectFreeRun($vquery);
                                                        while($vresult = mysqli_fetch_array($vrow))
                                                        {
                                                          ?>
                                                              <option value="<?php echo $vresult['id']?>"><?php echo $vresult['suppliername']. ' ( '.$vresult['registration_number'].' )'?></option>
                                                          <?php
                                                        }
                                                        $mysql -> dbDisconnect();
                                                    ?>   
                                                    </select>   
                                                </div>
                                            </div>
<!-- 
                                            <div class="col-md-2">
                                                 <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label">Pending Approval</span>
                                                </label>

                                                  <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label">Contains Failures</span>
                                                </label>
                                                
                                            </div> -->


                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info">Filter Inspections</button>
                                                <button type="button" class="btn btn-info">Clear</button>
                                            </div>
                                        </div><br>
                                        <div class="card">    
                                            <div class="card-header" style="background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="header">Today's Inspections 0 of 4</div>                   
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                                    <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                        <thead class="default">
                                                            <tr role="row">
                                                                <th>Depot</th>
                                                                <th>Date</th>
                                                                <th>Vehicle</th>
                                                                <th>User</th>
                                                                <th>Odometer</th>
                                                                <th data-orderable="false">Checks</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="card-footer"></div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
            <?php
            include('footer.php');
            ?>
        </div>
        <?php
        include('footerScript.php');
        ?>
         <script type="text/javascript">


            $(document).ready(function(){
                $('#myTable').DataTable({
                            'processing': true,
                            'serverSide': true,
                            'serverMethod': 'post',
                            'ajax': {
                                'url':'loadtabledata.php',
                                'data': {
                                    'action': 'loadtodayinspectiontabledata'
                                }
                            },
                            'columns': [
                            	{ data: 'depot' },
                                { data: 'date' },
                                { data: 'vehicle_id' },
                                { data: 'user' },
                                { data: 'odometer' },
                                { data: 'check' },
                                { data: 'Action' }
                            ]
                });
            });
        </script>
    </body>
</html>