<?php
include 'DB/config.php';
$page_title = "Risk Assessment";

if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['userid'] == 1) {
    $userid = '%';
} else {
    $userid = $_SESSION['userid'];
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    //$userid=$_SESSION['userid'];
} else {
    if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
        header("location: userlogin.php");
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
                        <div class="card-header p-2" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Risk Assessment</div>

                                <div>
                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Risk Assessment</button>

                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Risk Assessment</button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row d-flex align-items-center mb-4 py-3 border-bottom" title="Risk Assessment">
                                <div class="col-md-6">
                                    <h2 class="mb-1 font-weight-normal text-xl align-items-center">Risk Assessment</h2>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="d-flex justify-content-end">
                                        <a href="https://bryanstonlogistics.karavelo.co.uk/risk-assessments/7/pdf" class="btn btn-light btn- mr-2">
                                            Safe System of Work PDF</a>
                                        <a href="https://bryanstonlogistics.karavelo.co.uk/risk-assessments/7/ssow-pdf" class="btn btn-light btn- mr-2">Download PDF</a>
                                        <button class="btn btn-primary btn-" type="submit">Publish</button>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <h3 class="mb-3 font-weight-normal text-sm">A Risk Assessment will help you keep a simple record of:</h3>
                                    <ol>
                                        <li> -- Who might be harmed and how</li>
                                        <li> -- What you're already doing to control the risks</li>
                                        <li> -- What further action you need to take to control the risks</li>
                                        <li> -- Who needs to carry out the action</li>
                                        <li> -- When the action is needed by</li>
                                    </ol>

                                    <h3 class="mb-3 mt-3 font-weight-normal text-sm">Risk Assessment Steps:</h3>
                                    <h5>1. Identify the hazard</h5>
                                    <h5>2. Decide who might be harmed and how</h5>
                                    <h5>3. Evaluate the risks and decide on the precautions</h5>
                                    <h5>4. Record your findings and implement them</h5>
                                    <h5>5. Review your assessment and update if necessary</h5>

                                    <h3 class="mb-3 mt-3 font-weight-normal text-sm">When</h3>
                                    <ul>
                                        <li> -- Every 12 months</li>
                                        <li> -- If controls are no longer effective</li>
                                        <li> -- Potential new risks, e.g COVID19</li>
                                        <li> -- After a significant incident/accident</li>
                                        <li> -- New depot/location</li>
                                        <li> -- Keep previous versions to evidence when you are updating your Risk Assessment</li>
                                    </ul>

                                    <h3 class="mb-3 mt-3 font-weight-normal text-sm">Details of the Risk Assessment process need to be shared with all drivers!</h3>
                                </div>
                                <div class="col-md-8">
                                    <form method="post" id="accidentForm" name="accidentForm" action="" enctype="multipart/form-data">
                                        <div class="card border rounded p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Date Occurred *</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="title" name="title">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Completed Date</label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" id="completedate" name="completedate">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Next Review Date</label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" id="reviewdate" name="reviewdate">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Description</label>
                                                        <textarea type="text" id="description" name="description" class="form-control" placeholder="" rows="2"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group has-primary">
                                                        <label class="control-label">Depot</label>
                                                        <select class="select form-control custom-select" id="locationname" name="locationname" onchange="loadtable();">
                                                            <option value="%">All Depot Name</option>
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();
                                                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid` LIKE '" . $userid . "'";
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

                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="insert" class="btn btn-success" id="submit" onclick="insertdata();">Submit</button>
                                        </div>

                                        <div class="card mb-4 mt-3 border">
                                            <div class="card-header p-2 bg-primary">
                                                <h6 class="m-0">Safe System of Work</h6>
                                            </div>

                                            <div class="card-body">
                                                <label class=" cursor-pointer" data-aire-component="label" for="__aire-18-description_work19">Description</label>
                                                <textarea class="form-control" data-aire-component="textarea" name="description_work" data-aire-for="description_work" id="__aire-18-description_work19"></textarea>
                                            </div>
                                            <div class="card-footer">
                                                <button class="btn btn-primary " data-aire-component="button" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="card">
                                        <div class="card-header ">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="m-0">Hazads and Controls</h6>
                                                <button class="btn btn-primary btn-" wire:click="createHazard()">+ Add New Hazard</button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                                <thead class="default">
                                                    <tr>
                                                        <th>Hazard Name</th>
                                                        <th>Who might be harmed?</th>
                                                        <th>How might they be harmed?</th>
                                                        <th>Risk Controls?</th>
                                                        <th>Severity?</th>
                                                        <th>Probability?</th>
                                                        <th>Risk Score?</th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="ViewFormDiv">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th>Title</th>
                                            <th>Completion Date</th>
                                            <th>Next Review Date</th>
                                            <th>Created Date</th>
                                            <th>Assigned To</th>
                                            <th>Depot</th>
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
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <?php include('footerScript.php'); ?>

    <script type="text/javascript">
        function insertdata() {

            var status = "";
            var user_id = $('#userid').val();
            var driver_id = $('#driver_id').val();
            var vehicle_id = $('#vehicle_id').val();
            var date_occured = $('#date_occured').val();
            var type_id = $('#type_id').val();
            var stage_id = $('#stage_id').val();
            var reference = $('#reference').val();
            var description = $('#description').val();
            var other_person = $('#other_person').val();
            var other_vehicle = $('#other_vehicle').val();
            var contact = $('#contact').val();
            var other_notes = $('textarea#other_notes').val();

            // var amountfee = $('#amountfee').val();

            var form_data = new FormData();

            var totalfiles = document.getElementById('accident_image').files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('accident_image').files[index]);
            }

            var id = $('#id').val();
            var a = $('#submit').attr('name');

            if (a == "update") {
                status = "update";
                form_data.append("id", id);
            } else {
                status = "Insert";
            }

            form_data.append("driver_id", driver_id);
            form_data.append("contact", contact);
            form_data.append("vehicle_id", vehicle_id);
            form_data.append("date_occured", date_occured);
            form_data.append("type_id", type_id);
            form_data.append("stage_id", stage_id);
            form_data.append("reference", reference);
            form_data.append("description", description);
            form_data.append("other_person", other_person);
            form_data.append("other_vehicle", other_vehicle);
            form_data.append("user_id", user_id);
            form_data.append("other_notes", other_notes);
            form_data.append("action", "VehicleaccidentForm");
            form_data.append("status", status);

            event.preventDefault();
            $.ajax({
                url: 'InsertData.php',
                type: 'post',
                data: form_data,
                dataType: 'json',
                contentType: false,
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

        $(document).ready(function() {
            $("#AddFormDiv,#AddDiv").hide();

            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata.php',
                    'data': {
                        'action': 'loadaccidenttabledata'
                    }
                },
                'columns': [{
                        data: 'stage_id'
                    },
                    {
                        data: 'driver_id'
                    },
                    {
                        data: 'vehicle_id'
                    },
                    {
                        data: 'registration_number'
                    },
                    {
                        data: 'reference'
                    },
                    {
                        data: 'date_occured'
                    },
                    {
                        data: 'reported'
                    },
                    {
                        data: 'type_id'
                    },
                    {
                        data: 'other_person'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action'
                    }
                ]
            });
        });

        function edit(id) {

            $('#id').val(id);

            ShowHideDiv('view');

            $.ajax({

                type: "POST",

                url: "loaddata1.php",

                data: {
                    action: 'VehicleaccidentUpdateData',
                    id: id
                },

                dataType: 'json',

                success: function(data) {

                    $result_data = data.statusdata;
                    var a = $result_data['date'];
                    var dateVal = new Date(a);
                    var day = dateVal.getDate().toString().padStart(2, "0");
                    var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
                    var hour = dateVal.getHours().toString().padStart(2, "0");
                    var minute = dateVal.getMinutes().toString().padStart(2, "0");
                    var sec = dateVal.getSeconds().toString().padStart(2, "0");
                    var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
                    var inputDate = dateVal.getFullYear() + "-" + (month) + "-" + (day) + "T" + (hour) + ":" + (minute) + ":" + (sec) + "." + (ms);
                    var d = $result_data['type_id'];
                    var s = $result_data['stage_id'];

                    $('#type_id option[value="' + d + '"]').attr("selected", "selected");
                    $('#stage_id option[value="' + s + '"]').attr("selected", "selected");
                    $('#driver_id').val($result_data['driver']);
                    $('#vehicle_id').val($result_data['vehicle']);
                    $('#date_occured').val(inputDate);
                    // $('#type_id').val($result_data['type_id']);
                    // $('#stage_id').val($result_data['stage_id']);
                    $('#reference').val($result_data['reference']);
                    $('#description').val($result_data['description']);
                    $('#other_person').val($result_data['other_person']);
                    $('#other_vehicle').val($result_data['other_vehicle']);
                    $('#contact').val($result_data['contact']);
                    // ('#other_notes').text($result_data['other_notes']);
                    $("textarea#other_notes").val($result_data['other_notes']);
                    $("#accident_image").hide();



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
                    action: 'accidentDeleteData',
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
            $('#accidentForm')[0].reset();

            if (divValue == 'view') {
                $("#submit").attr('name', 'insert');
                $("#submit").text('Submit');
                $("#AddFormDiv,#AddDiv").show();
                $("#ViewFormDiv,#ViewDiv").hide();
            }

            if (divValue == 'add')

            {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
                $("#AddFormDiv,#AddDiv").hide();
                $("#ViewFormDiv,#ViewDiv").show();

            }
        }
    </script>
</body>

</html>