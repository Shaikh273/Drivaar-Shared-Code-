<?php
include 'DB/config.php';
include('config.php');
$page_title = "Ticket Setting";
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $id = $_SESSION['vid'];
    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $uid = '%';
    } else {
        $uid = $userid;
    }

    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode`  FROM `tbl_vehicles` v 
                  LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
                  WHERE v.`id`=" . $id;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    $stclr = $cntresult['colorcode'];
    $strname = $cntresult['statusname'];
    $mysql->dbDisConnect();
} else {
    if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
        header("location: login.php");
    } else {
        header("location: login.php");
    }
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
                <main class="container-fluid  animated">
                    <div class="card">
                        <?php include('pages_setting.php'); ?>
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Manage Ticket</div>

                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <label class="form-label">Select Depot</label><br>
                            <select class="form-select form-select-lg mb-3 col-lg-5 p-2" aria-label=".form-select-lg example" id="depot" name="depot">
                                <option value="0">All Depot</option>
                                <?php
                                $mysql = new Mysql();
                                $mysql->dbConnect();
                                $sql1 = "SELECT * FROM `tbl_depot` WHERE `isdelete`=0";
                                $fire1 = $mysql->selectFreeRun($sql1);
                                while ($fetch1 = mysqli_fetch_array($fire1)) {
                                ?>
                                    <option value="<?php echo $fetch1['id']; ?>"><?php echo $fetch1['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>

                        </div>
                        <div class="card-body" id="ViewFormDiv" style="display: none;">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <form method="post" name="addticketsetting" id="addticketsetting" action="">
                                    <input type="hidden" name="last_role_id" id="last_role_id">
                                    <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                        <thead class="default">
                                            <tr role="row">
                                                <th>Department</th>
                                                <th>User</th>
                                                <th>Release All</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </form>
                                <br>
                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>

        <?php
        include('footer.php');
        ?>
    </div>

    <?php
    include('footerScript.php');
    ?>

    <script>
        var table = $('#myTable').DataTable();
        var depot_id = "";

        $(document).ready(function() {

            $('#ViewFormDiv').hide();

            table = $('#myTable').DataTable({
                'processing': true,
                "serverSide": true,
                "destroy": true,
                'pageLength': 10,
                "ajax": {
                    "url": "loadtabledata1.php",
                    "type": "POST",
                    "data": function(d) {
                        d.action = 'loadticketsettingdata1';
                        d.depot_id = $('#depot').val();

                    }
                },
                'columns': [{
                        data: 'department'
                    },
                    {
                        data: 'user'
                    },
                    {
                        data: 'release'
                    }
                ] /* <---- this setting reinitialize the table */
            });
        });


        $("#depot").change(function() {
            $('#ViewFormDiv').show();
            table.ajax.reload();
        });

        function insertrolesetting(user_id, role_id) {
            var user_id = user_id;
            var depot_id = $('#depot').val();
            var role_id = role_id;

            $.ajax({

                type: "POST",
                url: "InsertData1.php",
                data: {
                    'action': 'addticketsettingtable',
                    'user_id': user_id,
                    'role_id': role_id,
                    'depot_id': depot_id

                },

                dataType: 'json',

                success: function(data) {

                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#last_role_id').val(data.last_id);
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }

                }

            });
        }

        function updaterolesetting() {
            var depot = $('#depot').val();

            $.ajax({
                url: "loaddata1.php",
                type: "POST",
                dataType: "JSON",
                data: $("#addticketsetting").serialize() + "&action=updateticketsettingtable",
                cache: false,
                processData: false,

                success: function(data) {

                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");

                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }

                }

            });
        }
    </script>

</body>

</html>