<?php
include 'DB/config.php';
$page_title = "Inspection Issue";
$page_id = 5;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $id = $_SESSION['vid'];
    if($_SESSION['userid']==1)
    {
       $userid='%'; 
    }
    else
    {
       $userid = $_SESSION['userid']; 
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` WHERE v.`id`=" . $id;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    $mysql->dbDisConnect();
} else {
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
</head>

<body class="skin-default-dark fixed-layout">
    <?php include('loader.php'); ?>
    <div id="main-wrapper">
        <?php include('header.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class=" container-fluid  animated mt-4">
                    <div class="container" style="max-width: 100%;">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header " style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Issues </div>
                                        </div>
                                    </div>


                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 15px;">
                                                <div class="col-md-3" style="margin-bottom: 15px;">
                                                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Advanced Search
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse col-md-12" id="collapseExample">
                                                <br><br>
                                                <div class="card card-body border border-primary rounded">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group has-primary">
                                                                        <select class="select form-control custom-select" id="vehicle_id" name="vehicle_id" onchange="loadtable();">
                                                                            <option value="%">All Vehicle</option>
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

                                                                <div class="col-md-4">
                                                                    <div class="form-group has-primary">
                                                                        <select class="select form-control custom-select" id="question" name="question" onchange="loadtable();">
                                                                            <option value="%">All Issue</option>
                                                                            <?php
                                                                            $mysql = new Mysql();
                                                                            $mysql -> dbConnect();
                                                                            $statusquery = "SELECT * FROM `tbl_vehiclechecklist` WHERE `isdelete`=0 AND `isactive`=0";
                                                                            $strow =  $mysql->selectFreeRun($statusquery);
                                                                            while ($statusresult = mysqli_fetch_array($strow)) {
                                                                            ?>
                                                                                <option value="<?php echo $statusresult['id'] ?>"><?php echo $statusresult['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            $mysql -> dbDisconnect();
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <table id="myTable" class="display dataTable table table-responsive" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                    <th>Vehicle</th>
                                                    <th>Issue</th>
                                                    <th data-orderable="false">State</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include('footerScript.php'); ?>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata1.php',
                    'data': function(d) {
                        d.action = 'loadinspectionissuetabledata';
                        d.vehicle_id = $('#vehicle_id').val();
                        d.question = $('#question').val();
                    }
                },
                'columns': [
                    {
                        data: 'vehicle_id'
                    },
                    {
                        data: 'issue'
                    },
                    {
                        data: 'state'
                    },
                    {
                        data: 'action'
                    },
                ]
            });
        });

        function loadtable() {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
        }

        function inspectionresponse(id, status) {
            $.ajax({
                type: "POST",
                url: "loaddata1.php",
                data: {
                    action: 'inspectionissueresponse',
                    id: id,
                    status: status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        location.reload(true);
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }

            });
        }
    </script>

</body>

</html>