<?php

$page_title = "Vehicle Damages";
include 'DB/config.php';
    $page_id=56;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid=$_SESSION['userid'];
        if($userid==1)
        {
          $userid='%';
        }
        else
        {
          $userid=$_SESSION['userid'];
        }
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $notfixed = "SELECT count(id) as count FROM `tbl_vehicledamage_img` WHERE `userid` LIKE '".$userid."' AND `isdelete`=0 AND `isactive`=0 AND `state`=0";
        $notfixedrow =  $mysql -> selectFreeRun($notfixed);
        $result1 = mysqli_fetch_array($notfixedrow);
        
        $fixed = "SELECT count(id) as count FROM `tbl_vehicledamage_img` WHERE `userid` LIKE '".$userid."' AND `isdelete`=0 AND `isactive`=0 AND `state`=1";
        $fixedrow =  $mysql -> selectFreeRun($fixed);
        $result2 = mysqli_fetch_array($fixedrow);
        $mysql -> dbDisconnect();
      
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

    <?php include('loader.php'); ?>

    <div id="main-wrapper">

        <?php
        include('header.php');
        ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="card col-md-12 divborder">
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-4" style="border-radius: 5px;border: 0.3px solid black;">
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="chart3" height="100"></canvas>
                                            </div>
                                        </div>
                                            
                                    </div>
                                    <div class="col-md-8" >
                                        
                                    </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="vehicle" name="vehicle">
                                                <option value="%">All Vehicle</option>
                                                <?php
        
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                  $supplierquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                                            FROM `tbl_vehicles` v 
                                                            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            WHERE v.`isdelete`= 0 AND v.`status` NOT IN (5)";
                                                  $supplierrow =  $mysql -> selectFreeRun($supplierquery);
                                                  while($supplierresult = mysqli_fetch_array($supplierrow))
                                                  {
                                                    ?>
                                                        <option value="<?php echo $supplierresult['id']?>"><?php echo $supplierresult['suppliername']?> (<?php echo $supplierresult['registration_number']?>)</option>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                                ?>
                                          </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="driver" name="driver">
                                                <option value="%">All Driver</option>
                                                <?php
        
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                  $supplierquery = "SELECT DISTINCT c.*
                                                                    FROM `tbl_contractor` c 
                                                                    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                    WHERE c.`depot` IN (w.depot_id) AND c.`isdelete`=0 AND c.`isactive`=0";
                                                  $supplierrow =  $mysql -> selectFreeRun($supplierquery);
                                                  while($supplierresult = mysqli_fetch_array($supplierrow))
                                                  {
                                                    ?>
                                                        <option value="<?php echo $supplierresult['id']?>"><?php echo $supplierresult['name']?></option>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                                ?>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="severity" name="severity">
                                                <option value="%">All Severity</option>
                                                <option value="1">Light</option>
                                                <option value="2">Moderate</option>
                                                <option value="3">Severe</option>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-info" onclick="ajaxcall();">Filter </button>
                                    </div>
                                </div>
                                <br>
                                <div class="card">
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <?php  
                                                $mysql = new Mysql();
                                                $mysql -> dbConnect();
                                                $sql1 = "select count(*) as allcount from tbl_vehicledamage_img where isdelete = 0 AND `isactive`=0 AND `userid` LIKE '".$userid."'";
                                                $sel =  $mysql -> selectFreeRun($sql1);
                                                $records = mysqli_fetch_array($sel);
                                                $totalRecords = $records['allcount'];
                                                $mysql -> dbDisconnect();
                                            ?>
                                            <div class="header">Damages (<?php echo $totalRecords ?>)</div>

                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table id="myTable" class="display dataTable table table-responsive" style="font-weight: 400;" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th>Vehicle</th>
                                                        <th>Driver</th>
                                                        <th>Date</th>
                                                        <th>Severity</th>
                                                        <th>Condition</th>
                                                        <th>Location</th>
                                                        <th>State</th>
                                                        <th>Cost</th>
                                                        <th>Action</td>
                                                    </tr>
                                                </thead>
                                            </table>
                                    </div>
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
<script src="../assets/node_modules/Chart.js/Chart.min.js"></script>
 <script type="text/javascript">

    $(document).ready(function(){
        new Chart(document.getElementById("chart3"),
        {
            "type":"pie",
            "data":{"labels":["Not Fixed","Fixed"],
            "datasets":[{
                "label":"My First Dataset",
                "data":[<?php echo $result1['count']?>,<?php echo $result2['count']?>],
                "backgroundColor":["rgb(255, 99, 132)","#00c292"]}
            ]}
        });
        ajaxcall();
        $("#severity").on('change',function(){
           ajaxcall();
        });
        $("#vehicle").on('change',function(){ 
           ajaxcall();
        });
        $("#driver").on('change',function(){
           ajaxcall();
        });
        
        
    });
    
    function ajaxcall() {
         $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'destroy': true,
            'dom': '<"pull-left"f><"pull-right"l>tip',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loaddamagetabledata',
                     'userid':<?php echo $userid ?>,
                     'vehicle':$("#vehicle").find(":selected").val(),
                     'driver':$("#driver").find(":selected").val(),
                     'severity':$("#severity").find(":selected").val()
                },
            },
            'columns': [
                { data: 'Vehicle' },
                { data: 'Driver' },
                { data: 'Date' },
                { data: 'Severity' },
                { data: 'Condition' },
                { data: 'Location' },
                { data: 'State' },
                { data: 'Cost' },
                { data: 'Action' }
            ]
        });
    }

    function loadtable()
    {
        var table = $('#myTable').DataTable();
        table.ajax.reload();
    }
    function mark(id,part) {
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'vehicleDamagestate', id: id},
            dataType: 'json',
            success: function(result) {
                if(result.status==1)
                {
                    myAlert("Fixed @#@ Damage is marked as fixed successfully.@#@success");
                    loadtable();
                }
                else
                {
                    myAlert("Fixed @#@ Data can not been updated.@#@danger");
                }
            }

        });
    }
    </script>



</body>



</html>