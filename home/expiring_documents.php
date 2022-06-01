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

</head>

<body class="skin-default-dark fixed-layout">
    <?php
    include('loader.php');
    ?>
    <div id="main-wrapper">
        <?php
        include('header.php');
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Expiring Documents</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-primary">
                                        <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable(this.value);">
                                                <?php
                                                    $mysql = new Mysql();
                                                    $mysql->dbConnect();
                                                    $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                                INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                                WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
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
                            </div>
                            <br>
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="table table-responsive" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th width="100px">Depot</th>
                                            <th width="100px">Driver</th>
                                            <th width="100px">Documents</th>
                                            <th width="500px">Expires</th>
                                            <th width="100px"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="etable">

                                    </tbody>
                                </table>
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
    <script type="text/javascript">
        $(document).ready(function() {
            loadtable($("#depot_id").val());
        });

        function loadtable(did) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ContractorExpiringDocumentData',
                    did: did
                },
                dataType: 'text',
                success: function(data) {
                    if (data == '') {
                        $('#etable').html("<tr><td colspan='5'>No Data Table..!</td></tr>");
                    } else {
                        $('#etable').html(data);
                    }
                }
            });
        }

        function loadpage(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ContractorSetSessionData',
                    cid: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        window.location = '<?php echo $webroot ?>contractor_document.php';
                    }
                }
            });
        }

        function edit(id) {

            $('#id').val(id);
            ShowHideDiv('view');
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'VehicleExtraUpdateData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $result_data = data.statusdata;
                    $('#name').val($result_data['name']);
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');
                }
            });
        }

        function deleterow(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'VehicleExtraDeleteData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        var table = $('#myTable').DataTable();
                        table.ajax.reload();
                        myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                    } else {
                        myAlert("Delete @#@ Data can not been deleted.@#@danger");
                    }
                }
            });
        }
    </script>
</body>

</html>