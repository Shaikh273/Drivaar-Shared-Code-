<?php
$page_title = "Vehicle";
include 'DB/config.php';
$page_id = 48;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $setUid=0;
    if ($_SESSION['userid'] == 1) {
        $userid = '%';
        $setUid = "('$userid')";
    } else {
        $userid = $_SESSION['userid'];
        $setUid = $_SESSION['userid'];
    }
    $seltd=0;
    if(isset($_GET['dptid']))
    {
        $seltd = $_GET['dptid'];
    }
$mysql = new Mysql();
$mysql->dbConnect();
$equery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE ".$setUid;
$feDept=0;
$erow =  $mysql->selectFreeRun($equery);
while ($eresult = mysqli_fetch_array($erow)) {
    $actd = "";
    if($seltd == $eresult['id'])
    {
        $actd = "selected";
    }
    $dpOption = "<option value='".$eresult['id']."' $actd>".$eresult['name']."</option>";
    if($feDept==0)
    {
        $feDept = $eresult['id'];
    }
}

    if(isset($_GET['dptid']))
    {
        $dptid = $_GET['dptid'];
    }
    else
    {
        $dptid = $feDept;
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
        // include('menu.php');
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <?php
                        $mysql = new Mysql();
                        $mysql->dbConnect();
                        $statusquery = "SELECT DISTINCT v.*,vt.`name` as type,vs.`name` as suppliername FROM `tbl_vehicles` v 
                                        INNER JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id`
                                        INNER JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id`
                                        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE $setUid AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
                                        WHERE v.`isdelete`=0 AND v.`depot_id`=".$dptid;
                        $strow =  $mysql->selectFreeRun($statusquery);
                        $rowcount = mysqli_num_rows($strow);

                        $today = date('d');
                        $month = date('m');
                        $year = date('Y');

                        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        ?>
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header"><span id="setmdname"></span>
                                    <?php echo " - " . $rowcount . " vehicles"; ?>
                                    <input type="hidden" name="hidmonth" id="hidmonth">
                                    <input type="hidden" name="hidyear" id="hidyear">
                                </div>

                            </div>
                        </div>

                        <br><br>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="d-flex text-center">
                                        <div class="col  border-right  py-2">
                                            <div class="text-grey-dark"><small>All Vehicles</small></div>
                                            <h5 class="m-0" style="font-weight: 700;"><?php echo $rowcount ?></h5>
                                        </div>

                                        <div class="col  border-right  py-2">
                                            <?php
                                            $tdate = date('Y-m-d');
                                            $vrqr = "SELECT COUNT(v.`id`) as cnt FROM `tbl_vehiclerental_agreement` v WHERE ('" . $tdate . "' BETWEEN v.`pickup_date` AND v.`return_date`)";
                                            $vrrow =  $mysql->selectFreeRun($vrqr);
                                            $vrresult = mysqli_fetch_array($vrrow);

                                            ?>
                                            <div class="text-grey-dark"><small>Available Today</small></div>
                                            <h5 class="m-0" style="font-weight: 700;">
                                                <span class=" text-warning "><?php echo $vrresult['cnt']; ?></span>
                                            </h5>
                                        </div>

                                        <div class="col border-right py-2">
                                            <div class="text-grey-dark"><small class="whitespace-no-wrap">Returns Tomorrow</small></div>
                                            <h5 class="m-0" style="font-weight: 700;">0</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="d-flex text-center">
                                        <div class="col-md-10">
                                            <div class="form-group has-primary">
                                                <select class="select form-control custom-select" id="locationname" name="locationname" onchange="loadschedule();">
                                                    <!-- <option value="%">All Depot Name</option> -->
                                                    <?php
                                                    echo $dpOption;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-header ">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="m-0"></h6>
                                </div>
                                <div>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-sm btn-info" onclick="previous()">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info" onclick="currentmonth(<?php echo date('d'); ?>,<?php echo date('m'); ?>,<?php echo date('Y'); ?>)" id="setmname"></button>
                                        <button type="button" class="btn btn-sm btn-info" onclick="next()">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="myTable" role="grid" aria-describedby="example2_info" class="table table-bordered">
                                <thead class="default">
                                    <tr role="row" id="setdate">
                                        <th></th>
                                        <?php
                                        for ($i = 1; $i <= $days; $i++) {
                                            if ($i == $today && $month==date('m')) {
                                                echo "<th style='background-color: #f7d694;'>" . $i . "</th>";
                                            } else {
                                                echo "<th>" . $i . "</th>";
                                            }
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody id="vehSch">
                                    <?php
                                    $i = 0;
                                    while ($row = mysqli_fetch_array($strow)) {
                                    ?>
                                        <tr>
                                            <td><?php echo "[" . $row["type"] . "] " . $row["registration_number"] . "(" . $row["suppliername"] . ")"; ?></td>
                                            <?php
                                            for ($i = 1; $i <= $days; $i++) 
                                            {
                                                $fulldate = $i . "-" . $month . "-" . $year;
                                                if ($i == $today && $month==date('m')) 
                                                {
                                                    // echo "<td style='background-color: #f7d694;' onclick=\"AddDeiverInfo('" . $year . "','" . $month . "','" . $i . "','" . $row['id'] . "','" . $row['registration_number'] . "','spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "')\" id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";

echo "<td style='background-color: #f7d694;' id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                                                } else 
                                                {
                                                    // echo "<td onclick=\"AddDeiverInfo('" . $year . "','" . str_pad($month,2,"0",STR_PAD_LEFT) . "','" . str_pad($i,2,"0",STR_PAD_LEFT) . "','" . $row['id'] . "','" . $row['registration_number'] . "','spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "')\" id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";

echo "<td id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                                                }
                                            }
                                            ?>
                                            <td></td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                    $mysql->dbDisconnect();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
            <div id="AssignVehicleModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(255 236 230);">
                            <h4 class="modal-title">Vehicle Assignment <span class="label label-info" id="reg_no" style="font-size: 15px;"> </span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="AssignVehicleForm" name="AssignVehicleForm" action="">
                                <input type="hidden" name="vid" id="vid">
                                <input type="hidden" name="id" id="id">
                                <input type="hidden" name="tdid" id="tdid">
                                <input type="hidden" name="DptId" id="DptId" value="<?php echo $feDept;?>">
                                <div>DIRECT ASSIGNMENT</div>
                                <br><br>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Driver *</label>
                                        <select class="select form-control custom-select" id="driver" name="driver">
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Start Date *</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" placeholder="mm/dd/yyyy">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">End Date *</label>
                                        <input type="date" class="form-control " id="end_date" name="end_date" placeholder="mm/dd/yyyy" onchange="datevalidation(this.value);">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Price *</label>
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="amount" name="amount" placeholder="">
                                        </div>
                                        <small>Price per day with VAT</small>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success waves-effect waves-light" name="insert" id="submit">Assign Vehicle</button>
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
        const monthNames = new Array();
        monthNames[1] = "January";
        monthNames[2] = "February";
        monthNames[3] = "March";
        monthNames[4] = "April";
        monthNames[5] = "May";
        monthNames[6] = "June";
        monthNames[7] = "July";
        monthNames[8] = "August";
        monthNames[9] = "September";
        monthNames[10] = "October";
        monthNames[11] = "November";
        monthNames[12] = "December";

        $(document).ready(function() {
            $('#hidmonth').val(<?php echo date('m'); ?>);
            $('#hidyear').val(<?php echo date('Y'); ?>);
            var mon = $('#hidmonth').val();
            var yer = $('#hidyear').val();
            var mfname = monthNames[mon];
            $('#setmname').html(mfname);
            $('#setmdname').html(mfname+' '+yer);
            getdatashow();
            //getVehicleScedule(mon,yer,dpid); 
        });

        function loadschedule()
        {
            var dptid = $('#locationname').val();
            window.location.href = "http://drivaar.com/home/vehicle_schedule.php?dptid="+dptid;
        }
        
        function getVehicleScedule()
        {
            var dpid = $("#locationname").val();
            var mnt = $('#hidmonth').val();
            var yer = $('#hidyear').val();
            $.ajax({
                    type: "POST",
                    url: "loadDataExtended1.php",
                    data: {
                        action : 'getVehicleSchedule',
                        dpid : dpid,
                        mont : mnt,
                        yer : yer
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            $('#vehSch').html(data.tr);
                            getdatashow();
                        }
                    }
            });

        }
              
        function datevalidation(enddate) {
            var startDate = $('#start_date').val();
            var endDate = enddate;
            if ((Date.parse(endDate) <= Date.parse(startDate))) {
                myAlert("Error @#@ End date should be greater than Start date @#@danger");
                document.getElementById("end_date").value = "";
                $('#end_date').css("border", "1px solid red");
            } else {
                $('#end_date').css("border", "");
            }
        }

        function pad(n) {
            return (n < 10) ? ("0" + n) : n;
        }

        function previous() {
            var d = '';
            var m = $('#hidmonth').val() - 1;
            if($('#hidmonth').val()==1)
            {
                m=12;
                $('#hidyear').val($('#hidyear').val()-1);
            }
            var y = $('#hidyear').val();
            currentmonth(d, m, y);
            getVehicleScedule()
        }

        function next() {
            var d = '';
            var mon = ($('#hidmonth').val())%12;
            var m = mon + 1;
            var y = $('#hidyear').val();
            if(mon==0)
            {
                y++;
            }
            currentmonth(d, m, y);
            getVehicleScedule()
        }

        function currentmonth(d, m, y) {
            $('#hidmonth').val(m);
            $('#hidyear').val(y);
            var month = m;
            var year = y;
            var daysInMonth = new Date(year, month, 0).getDate();
            
            var mname = monthNames[month];
            $('#setmname').html(mname);
            $('#setmdname').html(mname+' '+year);

            $('#setdate').html();
            var set = ["<th></th>"];
            var i;
            var data = '';
            for (i = 1; i <= daysInMonth; i++) {
                if (i == d) {
                    var data = "<th style='background-color: #f7d694;'>" + i + "</th>";
                } else {
                    var data = "<th>" + i + "</th>";
                }
                set.push(data);
            }
            $('#setdate').html(set);
        }

        function AddDeiverInfo(y, m, d, vehicleid, reg, tdid) {
            $('#AssignVehicleModel').modal('show');
            $('#vid').val(vehicleid);
            $('#reg_no').text(reg);
            $('#tdid').val(tdid);
            document.getElementById("start_date").value = y + "-" + pad(m) + "-" + pad(d);
            $("#start_date").attr("disabled", true);

            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'NewScheduleDriverData',
                    id: vehicleid,
                    startdate: y + "-" + pad(m) + "-" + pad(d)
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#driver').html(data.options);
                    }
                }
            });
        }

        $(function() {
            $('#submit').on('click', function(e) {
                e.preventDefault();
                $("#DptId").val($("#locationname").val());
                $("#start_date").removeAttr('disabled');
                var startDate = $('#start_date').val();
                // $("#start_date").attr('disabled','disabled');
                var endDate = $('#end_date').val();
                var start = new Date(startDate);
                var end = new Date(endDate);
                var diffDate = (end - start) / (1000 * 60 * 60 * 24);
                var days = Math.round(diffDate);
                var driver = $('#driver option:selected').text();
                if (endDate && startDate) {
                    $.ajax({
                        type: "POST",
                        url: "InsertData.php",
                        dataType: "JSON",
                        data: $('#AssignVehicleForm').serialize() + "&action=NewAssignVehicleForm",
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#AssignVehicleForm')[0].reset();
                                $('#AssignVehicleModel').modal('hide');
                                if (data.name == 'Update') {
                                    var table = $('#myTable').DataTable();
                                    table.ajax.reload();
                                    $("#AddFormDiv,#AddDiv").hide();
                                    $("#ViewFormDiv,#ViewDiv").show();
                                }
                                var tdid = $('#tdid').val();

                                for (var j = 0; j < (days); j++) {
                                    $('#' + tdid).next().remove();
                                }
                                document.getElementById(tdid + '').setAttribute('colspan', days+1);
                                document.getElementById(tdid + '').innerHTML += "<span class='label label-success' style='color: black;background-color: #bfdbfe !important;border: 1px solid #305ae9;border-radius: 0px;width: 100%'>" + driver + "</span>";
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });
                } else {
                    myAlert("Error @#@ Please,Fill all required field. @#@danger");
                }
            });
        });

        function getdatashow() {
            var dpid = $("#locationname").val();
            var mnt = $('#hidmonth').val();
            var yer = $('#hidyear').val();
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'NewScheduledataGet',
                    dpid : dpid,
                    mont : mnt,
                    yer : yer
                },
                dataType: 'json',
                success: function(response1) {
                    var response = response1;
                    var len = 0;
                    if (response.length > 0) 
                    {
                        len = response.length;
                    }
                    for (var i = 0; i < len; i++) 
                    {
                        var uniqid = response[i].uniqid;
                        var start = new Date(response[i].startdate);
                        var end = new Date(response[i].enddate);
                        var diffDate = new Date(end - start) / (1000 * 60 * 60 * 24);
                        var days = Math.round(diffDate) + 1;
                        for (var j = 0; j < (days - 1); j++) 
                        {
                            $('#' + uniqid).next().remove();
                        }
                        if (days>0) 
                        {
                            var st = document.getElementById(uniqid+'');
                            st.setAttribute('colspan', days);
                            document.getElementById(uniqid + '').innerHTML += "<span class='label label-success' style='color: black;background-color: #bfdbfe !important;border: 1px solid #305ae9;border-radius: 0px;width: 100%'>" + response[i].name + "</span>";
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>