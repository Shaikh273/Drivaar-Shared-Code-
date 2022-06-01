<?php
include 'DB/config.php';

$page_title = "Audits";

if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['userid'] == 1) {
    $userid = '%';
} else {
    $userid = $_SESSION['userid'];
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
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">
                                <?php include('report.php'); ?>
                                <div class="card">
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">My Audits</div>

                                            <div>
                                                <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Audits</button>

                                                <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Audits</button>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-body" id="AddFormDiv">
                                        <form method="post" action="" name="auditform" id="auditform">
                                            <input type="hidden" name="id" id="id" value="">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3"> Name of the Audit:</label>
                                                        <input type="text" class="form-control" data-aire-component="input" name="name" id="name" data-aire-for="Name" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group" data-aire-component="group" data-aire-for="users">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-users12">
                                                            People to audit:
                                                        </label>
                                                        <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" id="contractor" name="contractor[]">
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();
                                                            $statusquery = "SELECT DISTINCT c.* FROM `tbl_contractor` c 
                                                                    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                   WHERE  c.`depot` IN (w.depot_id) AND c.`isdelete`=0 AND c.`isactive`=0 AND c.`iscomplated`=1";
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


                                                <div class="col-md-4">
                                                    <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">Document Types:
                                                        </label>
                                                        <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" id="document" name="document[]">
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();
                                                            $statusquery = "SELECT * FROM `tbl_vehicledocumenttype` WHERE `isdelete`=0 AND `isactive`=0";
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

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="cursor-pointer" data-aire-component="label" for="__aire-0-name3">Opens at:
                                                    </label>
                                                    <div class="input-group">

                                                        <input type="datetime-local" class="form-control" placeholder="" name="opendate" id="opendate">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="cursor-pointer" data-aire-component="label" for="__aire-0-name3">Closes at:
                                                    </label>
                                                    <div class="input-group">

                                                        <input type="datetime-local" class="form-control" placeholder="" name="closedate" id="closedate">
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input" name="paidcheck" id="paidcheck" value="1">
                                                    <span class="custom-control-label">Include PAID invoices</span>
                                                </label>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-success" id="submit">Submit</button>
                                        </form>
                                    </div>


                                    <div class="card-body" id="ViewFormDiv">
                                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>People</th>
                                                    <th>Document</th>
                                                    <th>Opened For</th>
                                                    <th>Close For</th>
                                                    <th data-orderable="false">Status</th>
                                                    <th data-orderable="false">Action</th>
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
    </div>
    </div>


    <?php include('footer.php'); ?>

    </div>



    <?php include('footerScript.php'); ?>
    <script>
        $(document).ready(function() {
            $("#AddFormDiv,#AddDiv").hide();

            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata1.php',
                    'data': {
                        'action': 'loadaudittabledata',
                    }
                },
                'columns': [{
                        data: 'name'
                    },
                    {
                        data: 'people_to_audit'
                    },
                    {
                        data: 'document_type'
                    },
                    {
                        data: 'open_at'
                    },
                    {
                        data: 'close_at'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ]
            });

            $("#auditform").validate({
                rules: {
                    document: 'required',
                    name: 'required',
                    contractor: 'required',
                    opendate: 'required',
                    closedate: 'required',
                },
                messages: {
                    document: "Please select your document type",
                    name: "Please enter your name",
                    contractor: "Please select your contractor name",
                    opendate: "Please enter a date",
                    closedate: "Please enter a date",
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "InsertData1.php",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#auditform").serialize() + "&action=auditForm",
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#auditform')[0].reset();
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
                }
            });
        });

        function edit(id) {
            $('#id').val(id);
            ShowHideDiv('view');
            $.ajax({
                type: "POST",
                url: "loaddata1.php",
                data: {
                    action: 'AuditUpdateData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $result_data = data.statusdata;
                    var opendateString = $result_data['opendate'];

                    var dateVal = new Date(opendateString);
                    var day = dateVal.getDate().toString().padStart(2, "0");
                    var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
                    var hour = dateVal.getHours().toString().padStart(2, "0");
                    var minute = dateVal.getMinutes().toString().padStart(2, "0");
                    var sec = dateVal.getSeconds().toString().padStart(2, "0");
                    var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
                    var inputDate = dateVal.getFullYear() + "-" + (month) + "-" + (day) + "T" + (hour) + ":" + (minute) + ":" + (sec) + "." + (ms);

                    var closedateString = $result_data['closedate'];
                    var dateVal1 = new Date(closedateString);
                    var day1 = dateVal1.getDate().toString().padStart(2, "0");
                    var month1 = (1 + dateVal1.getMonth()).toString().padStart(2, "0");
                    var hour1 = dateVal1.getHours().toString().padStart(2, "0");
                    var minute1 = dateVal1.getMinutes().toString().padStart(2, "0");
                    var sec1 = dateVal1.getSeconds().toString().padStart(2, "0");
                    var ms1 = dateVal1.getMilliseconds().toString().padStart(3, "0");
                    var inputDate1 = dateVal1.getFullYear() + "-" + (month1) + "-" + (day1) + "T" + (hour1) + ":" + (minute1) + ":" + (sec1) + "." + (ms1);
                    $('#name').val($result_data['name']);
                    if ($result_data['contractor']) {
                        $('#contractor').val($result_data['contractor'].split(",")).change();
                    }

                    if ($result_data['document']) {
                        $('#document').val($result_data['document'].split(",")).change();
                    }
                    $('#opendate').val(inputDate);
                    $('#closedate').val(inputDate1);
                    $('#paidcheck').val($result_data['paidcheck']);
                    if ($result_data['paidcheck'] == 1) {
                        $("#paidcheck").prop("checked", true);
                    } else {
                        $("#paidcheck").prop("checked", false);
                    }
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');
                }
            });
        }

        function deleterow(id) {
            $.ajax({
                type: "POST",
                url: "loaddata1.php",
                data: {
                    action: 'AuditDeleteData',
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
            $('#auditform')[0].reset(); 
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