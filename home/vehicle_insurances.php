<?php
$page_title = "Insurances";
include 'DB/config.php';
$page_id = 69;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
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
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Insurances</div>

                                <div>

                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Insurance</button>

                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Insurance</button>

                                </div>

                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" id="insuranceForm" name="insuranceForm" action="">
                                        <input type="hidden" name="id" id="id" value="">
                                        <div class="form-body">

                                            <div class="row p-t-20">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"> Name *</label>
                                                        <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"> Insurance Company *</label>
                                                        <input type="text" id="insurance_company" name="insurance_company" class="form-control" placeholder="">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label"> Reference Number *</label>
                                                        <input type="text" id="reference_number" name="reference_number" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row p-t-20">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"> Start Date *</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control mydatepicker" id="startdate" name="startdate" placeholder="mm/dd/yyyy">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="icon-calender"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label"> Expiry Date *</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control mydatepicker" id="expirydate" name="expirydate" placeholder="mm/dd/yyyy">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="icon-calender"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="ViewFormDiv">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th>Company</th>
                                            <th>Reference</th>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>Expiry Date</th>
                                            <th data-orderable="false">Date</th>
                                            <th data-orderable="false">Status</th>
                                            <th data-orderable="false"></th>
                                        </tr>
                                    </thead>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </main>


                <div id="addvehicle" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(255 236 230);">
                                <h4 class="modal-title">Add Vehicle</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="addvehicleForm" name="addvehicleForm" action="">
                                    <input type="hidden" name="insurance_id" id="insurance_id">
                                    <div class="form-group">
                                        <label class="control-label">Vehicle *</label>
                                        <select class="select form-control custom-select" id="vehicle" name="vehicle">

                                        </select>
                                    </div>
                                </form>
                                <br>
                                <table id="Ownertbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th width="500">Vehicle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vehiclebody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="AddVehicleData();" class="btn btn-success waves-effect waves-light">Save changes</button>
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
        function addvehicle(id) {
            $('#addvehicle').modal('show');
            $('#insurance_id').val(id);
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'VehiclefleetInsuranceshow'
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#vehicle').html(data.options);
                        $('#vehicle').select('refresh');
                    }
                }
            });

            showvehicle(id);
        }

        function AddVehicleData() {
            var id = $('#insurance_id').val();
            var vid = $('#vehicle').val();
            $.ajax({
                type: "POST",
                url: "InsertData.php",
                data: {
                    action: 'AddVehiclefleetInsurance',
                    id: id,
                    vid: vid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#addvehicleForm')[0].reset();
                        showvehicle(id);
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                    //$('#addvehicle').modal('hide');
                }
            });
        }

        function showvehicle(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ShowVehiclefleetInsurance',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#vehiclebody').html(data.tbldata);
                    } else {
                        $('#vehiclebody').html(data.tbldata);
                    }
                }
            });
        }

        function deleteaddvehiclefleetinsurancerow(id, insuranceid) {
            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'deleteaddvehiclefleetinsurance',
                    id: id
                },

                dataType: 'json',

                success: function(data) {
                    if (data.status == 1) {
                        myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                        $('#addvehicle').modal('hide');
                        showvehicle(insuranceid);

                    } else {
                        myAlert("Delete @#@ Data can not been deleted.@#@danger");
                    }

                }

            });
        }

        $(document).ready(function() {
            $("#AddFormDiv,#AddDiv").hide();

            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata.php',
                    'data': {
                        'action': 'loadvehiclefleetinsurancetabledata'
                    }
                },
                'columns': [{
                        data: 'insurance_company'
                    },
                    {
                        data: 'reference_number'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'startdate'
                    },
                    {
                        data: 'expirydate'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'Status'
                    },
                    {
                        data: 'Action'
                    }
                ]
            });

            $("#insuranceForm").validate({

                rules: {

                    name: 'required',
                    insurance_company: 'required',
                    reference_number: 'required',
                    startdate: 'required',
                    expirydate: 'required',

                },

                messages: {
                    name: "Please enter your name",
                    insurance_company: "Please enter your insurance company",
                    reference_number: "Please enter your reference number",
                    startdate: "Please enter your startdate",
                    expirydate: "Please enter your expirydate",
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "InsertData.php",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#insuranceForm").serialize() + "&action=VehicleinsuranceForm",
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#insuranceForm')[0].reset();
                                if (data.name == 'Update') {
                                    var table = $('#myTable').DataTable();
                                    table.ajax.reload();
                                    $("#AddFormDiv,#AddDiv").hide();
                                    $("#ViewFormDiv,#ViewDiv").show();
                                }
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });
                    // return false;
                }
            });
        });


        function edit(id) {

            $('#id').val(id);

            ShowHideDiv('view');

            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'VehicleinsuranceUpdateData',
                    id: id
                },

                dataType: 'json',

                success: function(data) {

                    $result_data = data.statusdata;

                    $('#name').val($result_data['name']);
                    $('#insurance_company').val($result_data['insurance_company']);
                    $('#reference_number').val($result_data['reference_number']);
                    $('#startdate').val($result_data['startdate']);
                    $('#expirydate').val($result_data['expirydate']);

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
                    action: 'VehicleinsuranceDeleteData',
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

        function ShowHideDiv(divValue) {
            $('#insuranceForm')[0].reset();
            if (divValue == 'view') {
                $("#submit").attr('name', 'insert');
                $("#submit").text('Submit');
                $("#AddFormDiv,#AddDiv").show();
                $("#ViewFormDiv,#ViewDiv").hide();

            }
            if (divValue == 'add') {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
                $("#AddFormDiv,#AddDiv").hide();
                $("#ViewFormDiv,#ViewDiv").show();

            }
        }
    </script>
</body>

</html>