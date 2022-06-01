<?php
include 'DB/config.php';
$page_title = "Contractor Invoices";
$page_id = 7;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $id = $_SESSION['cid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT * FROM `tbl_contractor` WHERE `id`=" . $id;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    if ($cntresult['isactive'] == 0) {
        $colorcode = "green";
        $statusname = "Active";
    } else {
        $colorcode = "red";
        $statusname = "Inactive";
    }

    $sql = "SELECT a.*,v.`registration_number` FROM `tbl_vehiclerental_agreement`  a 
          INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
          WHERE a.`driver_id`=$id  AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date`";
    $fire = $mysql->selectFreeRun($sql);
    $cntresult1 = mysqli_fetch_array($fire);


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
    <meta name="viewport" content="cidth=1024">
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
                <main class="container-fluid animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Contractor / <?php echo $cntresult['name']; ?></div>
                                <div>
                                    <a href="">
                                        <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                                    </a>
                                    <a href="editcontractor.php">
                                        <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <span class="label label-success">Active</span>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-suitcase"></i>
                                        <?php
                                        if ($cntresult['type'] == 1) {
                                            echo 'self-employed';
                                        } else {
                                            echo 'company';
                                        }
                                        ?>

                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-envelope-open"></i>
                                        <?php echo $cntresult['email']; ?>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-warehouse"></i>
                                        <?php echo $cntresult['depot_type']; ?>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-car"></i>
                                        <?php echo $cntresult1['registration_number']; ?>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <?php include('contractor_setting.php'); ?>
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
                                                        <li><a class="dropdown-item" href="contractorInvoice.php">Timesheet Invoice</a></li>
                                                        <li><a class="dropdown-item" href="#">Chart</a></li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-secondary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Vehicle Hires</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="contractorVehicleInvoice.php">Vehicle Invoice</a></li>
                                                        <li><a class="dropdown-item" href="#">Chart</a></li>
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
                                                <div class="table-responsive m-t-40" style="margin-top: 5px;">
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
        var cid = <?php echo $id ?>;

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
                    action: 'loadContractorInvoicestabledata',
                    cid: cid
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-overlay").show();
                },
                success: function(data) {
                    if (data.status == 1) {
                        $('#example45').DataTable().destroy();
                        $('#example45').find('tbody').html(data.options);
                        //$('#example45').find('tbody').append(data.options);
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
                    type: 1,
                    cid: cid
                },
                dataType: 'text',
                success: function(data) {
                    $('#actionlog').html(data);
                }

            });
        }

        function Isactivevat(id, status1, action1) {
            var setstatus = status1;
            swal({
                title: "Alert !",
                text: "Are you sure, You want Change it.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, change it!"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "loaddata.php",
                        data: {
                            action: action1,
                            id: id,
                            status: status1
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status123 == 1) {
                                swal("Congratulations!", "Your Status has been changed.", "success");
                                if (setstatus == 0) {
                                    $("#" + id + "-td").html("<button type='button' class='btn btn-success isactivebtn' onclick=\"Isactivevat(" + id + "," + 1 + ",'" + action1 + "');\">Applied</button>");
                                } else {
                                    $("#" + id + "-td").html("<button type='button' class='btn btn-danger isactivebtn' onclick=\"Isactivevat(" + id + "," + 0 + ",'" + action1 + "');\">Apply</button>");
                                }
                            } else {
                                myAlert("Alert @#@" + data.mesg + "@#@danger");
                            }
                        }
                    });
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