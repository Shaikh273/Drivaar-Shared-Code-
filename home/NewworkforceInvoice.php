<?php
include 'DB/config.php';
$page_title = "Workforce Invoice";
$page_id = 37;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $id = $_SESSION['wid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT * FROM `tbl_user` WHERE `id`=" . $id;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    if ($cntresult['isactive'] == 0) {
        $colorcode = "green";
        $statusname = "Active";
    } else {
        $colorcode = "red";
        $statusname = "Inactive";
    }
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
    <?php include('loader.php'); ?>
    <div id="main-wrapper">
        <?php include('header.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Workforce / <?php echo $cntresult['name']; ?></div>
                                <div>
                                    <a href="">
                                        <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                                    </a>
                                    <a href="workforce_edit.php">
                                        <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <?php
                                        if ($cntresult['isactive'] == 0) {
                                        ?>
                                            <span class="label label-success">Active</span>
                                        <?php
                                        } else {
                                        ?>
                                            <span class="label label-danger">Inactive</span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-suitcase"></i>
                                        <?php echo $cntresult['role_type']; ?>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-envelope-open"></i>
                                        <?php echo $cntresult['email']; ?>
                                    </div>
                                </div>
                            </div><br>
                            <hr>
                            <?php include('workforce_setting.php'); ?>
                            <div class="row">
                                <div class="card col-md-12" style="border: 1px solid #d1d5db;">
                                    <div class="card-header" style="background-color: #fff;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Invoices</div>
                                            <div>
                                                <button type="button" disabled class="btn btn-secondary">Total : <b><i class="fas fa-pound-sign"></i> 0.00</b></button>
                                                <div class="btn-group">
                                                    <button class="btn btn-secondary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Timesheet</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="workforceInvoice.php">Timesheet Invoice</a></li>
                                                        <li><a class="dropdown-item" href="#">Chart</a></li>
                                                        <li><a class="dropdown-item" href="#">Action Log</a></li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-secondary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Vehicle Hires</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="workforceVehicleInvoice.php">Vehicle Invoice</a></li>
                                                        <li><a class="dropdown-item" href="#">Chart</a></li>
                                                        <li><a class="dropdown-item" href="#">Action Log</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                                    <table id="example45" class="display nowrap table table-hover table-striped" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Total</th>
                                                                <th data-orderable="false">Status</th>
                                                                <th>Period</th>
                                                                <th>Number</th>
                                                                <th data-orderable="false">VAT</th>
                                                                <th data-orderable="false">Due</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="setdata">
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <br><br>
                                                <div class="table-responsive m-t-40" style="margin-top: 16px;">
                                                    <table id="myTable1" class="display dataTable table table-responsive" aria-describedby="example2_info" style="width: 100%;">
                                                        <thead class="default">
                                                            <tr role="row">
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="actionlog">
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </main>
            </div>
        </div>
    </div>
    <div id="addstatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(255 236 230);">
                    <h4 class="modal-title">Change Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form method="post" id="AddStatusForm" name="AddStatusForm" action="">
                        <input type="hidden" name="statusid" id="statusid">
                        <div class="form-group">
                            <label class="control-label">Status *</label>
                            <select class="select form-control custom-select" id="modalstatus" name="modalstatus">

                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="AddInvoiceStatusData();" class="btn btn-success waves-effect waves-light">Save changes</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    </div>
    <?php include('footerScript.php'); ?>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script type="text/javascript">
        var wid = <?php echo $id ?>;
        $(document).ready(function() {
            actionLog();
            loadExporterTable();
            $("#AddFormDiv,#AddDiv").hide();
        });

        function loadExporterTable() {
            $('#example45').DataTable().rows().remove().draw();
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'loadWorkforceInvoicestabledata',
                    wid: wid
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-overlay").show();
                },
                success: function(data) {
                    if (data.status == 1) {
                        $('#example45').DataTable().destroy();
                        $('#example45').find('tbody').append(data.dt);
                        $('#example45').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                                'copy', 'csv', 'excel', 'print'
                            ]
                        }).draw();
                        $("#loading-overlay").hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loading-overlay").hide();
                    myAlert("Error @#@ Something went wrong!!!.@#@danger");
                }

            });

        }

        function addstatus(id, statusid) {
            $('#addstatus').modal('show');
            $('#statusid').val(id);
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'InvoiceStatusData',
                    id: id,
                    statusid: statusid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#modalstatus').html(data.options);
                        $('#modalstatus').select('refresh');
                    }
                }

            });
        }

        function AddInvoiceStatusData() {
            var statusid = $('#statusid').val();
            var status = $('#modalstatus').val();
            $.ajax({
                type: "POST",
                url: "InsertData.php",
                data: {
                    action: 'AddInvoiceStatusData',
                    id: statusid,
                    status: status
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        var table = $('#myTable').DataTable();
                        table.ajax.reload();
                        actionLog();
                        myAlert("Update @#@ Status has been changed successfully.@#@success");
                    } else {
                        myAlert("Update Error @#@ Status can not been changed successfully.@#@danger");
                    }
                    $('#addstatus').modal('hide');
                }
            });
        }

        function actionLog() {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ShowActionLog',
                    type: 2
                },
                dataType: 'text',
                success: function(data) {
                    $('#actionlog').html(data);
                }

            });
        }
        $('#example45').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    </script>
</body>

</html>