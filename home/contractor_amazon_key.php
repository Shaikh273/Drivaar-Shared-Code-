<?php
$page_title = "Drivaar";
include 'DB/config.php';
$page_id = 4;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $uid = '%';
    } else {
        $uid = $userid;
    }
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
    <style type="text/css">
        .default {
            background-color: #f8fafc !important;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <?php include('loader.php'); ?>
    <div id="main-wrapper">
        <?php include('header.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Show Contractor Data</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <!-- <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group has-primary">
                                                    <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable(this.value);">
                                                        <option value="%">All Depot</option>
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div><br> -->
                                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th></th>
                                                        <th>File</th>
                                                        <th>Time</th>
                                                        <th>Process Schedle</th>
                                                        <th>Status</th>
                                                        <th data-orderable="false">Date</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include('footerScript.php'); ?>
    <script type="text/javascript">
        $(document).ready(function() {  
        });

        // function loadtable(did1) {
        //     var table = $('#myTable').DataTable();
        //     table.ajax.reload();
        // }
    </script>
</body>

</html>