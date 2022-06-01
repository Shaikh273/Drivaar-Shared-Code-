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
                                            <div class="header">Feedback</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group has-primary">
                                                    <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable(this.value);">
                                                        <option value="%">All Depot</option>
                                                        <?php
                                                        $mysql = new Mysql();
                                                        $mysql->dbConnect();
                                                        $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                        INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                        WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid`=" . $userid;
                                                        $strow =  $mysql->selectFreeRun($statusquery);
                                                        while ($statusresult = mysqli_fetch_array($strow)) {
                                                        ?>
                                                            <option value="<?php echo $statusresult['id'] ?>"><?php echo $statusresult['name'] ?></option>
                                                        <?php
                                                        }
                                                        $mysql->dbDisconnect();
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div><br>
                                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th></th>
                                                        <th>From</th>
                                                        <th>Content</th>
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
            $('#myTable').DataTable({
                'processing': true,
                "serverSide": true,
                "destroy": true,
                'pageLength': 10,
                "ajax": {
                    "url": "loadtabledata.php",
                    "type": "POST",
                    "data": function(d) {
                        d.action = 'loadFeedbacktabledata';
                        d.did = $('#depot_id').val();
                    }
                },
                'columns': [{
                        data: 'id'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'feedback'
                    },
                    {
                        data: 'date'
                    },
                ] /* <---- this setting reinitialize the table */
            });
        });

        function loadtable(did1) {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
        }
    </script>
</body>

</html>