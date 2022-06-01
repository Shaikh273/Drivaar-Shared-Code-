<?php
$page_title = "Drivaar";
include 'DB/config.php';
$page_id = 45;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
    if($_SESSION['userid']==1)
    {
       $uid='%'; 
       $qry = '';
    }
    else
    {
       $uid = $_SESSION['userid']; 
       $qry = ' AND u.`depot` IN (w.`depot_id`)';
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
    <?php include('loader.php'); ?>

    <div id="main-wrapper">
        <?php include('header.php'); // include('menu.php'); ?>
        <div class="page-wrapper content" id="top">

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Workforce Schedule</div>
                                    <span class="align-items-right col-md-2">
                                        <button class="btn btn-secondary" onclick="PreviousWeek();">Previous</button>
                                        <button class="btn btn-secondary" onclick="NextWeek();">Next</button>
                                    </span>
                                    <!-- <a href="" class="btn btn-secondary  col-md-2">Next</a> -->
                                </div>
                            </div>


                            <div class="card-body">
                                <div class="row">
                                    <div class="position-relative" style="width: 100%">
                                        <div class="align-items-center col-md-12">
                                            <div class="table-responsive-lg">
                                                <table class="table w-100 table-hover" small="small" hover="hover">
                                                    <thead class="default">
                                                        <tr id="setdate">
                                                        </tr>
                                                    </thead>
                                                    <tbody id="calrows">
                                                        <?php
                                                        $mysql = new Mysql();
                                                        $mysql->dbConnect();

                                                        $statusquery =  "SELECT DISTINCT u.* FROM `tbl_user` u
                                                        INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                        WHERE u.`isactive`=0 AND u.`isdelete`=0  AND u.`id` NOT IN (1)".$qry;

                                                       // $statusquery = "SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0";
                                                        $strow =  $mysql->selectFreeRun($statusquery);
                                                        $rowcount = mysqli_num_rows($strow);
                                                        while ($row = mysqli_fetch_array($strow)) {
                                                        ?>
                                                            <tr id="scheduledata">
                                                                <td class="border-right bg-grey-lightest">
                                                                    <div><?php echo $row['name']; ?></div>
                                                                    <small><?php echo $row['role_type']; ?></small>
                                                                </td>

                                                                <?php
                                                                $k;
                                                                for ($k = 1; $k <= 7; $k++) {
                                                                ?>
                                                                    <td class="p-0 position-relative border-right td-<?php echo $k ?>" id="td_<?php echo $userid . "_" . $row['id'] . "_" . $k; ?>" onclick="insertLeave(this,<?php echo $row['id'] ?>);">
                                                                    </td>
                                                                <?php
                                                                }
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                        $mysql->dbDisconnect();
                                                        ?>
                                                    </tbody>
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
            <div id="addType" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(255 236 230);">
                            <h4 class="modal-title">Type</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="AddTypeForm" name="AddTypeForm" action="">
                                <input type="hidden" name="typeid" id="typeid">
                                <input type="hidden" name="wid" id="wid">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="isweekoff" name="isweekoff" class="custom-control-input" value="1" onchange="dayoff();">
                                            <label class="custom-control-label" for="isweekoff">DAY OFF</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden" id="showdiv">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="paid" name="ispaid" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="paid">PAID</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="unpaid" name="ispaid" class="custom-control-input" value="2">
                                            <label class="custom-control-label" for="unpaid">UNPAID</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="sick" name="ispaid" class="custom-control-input" value="3">
                                            <label class="custom-control-label" for="sick">SICK</label>
                                        </div>
                                    </div>

                                </div>


                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" onclick="AddLeaveData();" class="btn btn-success waves-effect waves-light">Save changes</button>
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>
        <?php include('footerScript.php'); ?>

        <script type="text/javascript">
            $(document).ready(function() {
                //var date = new Date();
                var date1 = '';
                $.ajax({
                    type: "POST",
                    url: "setTimezone.php",
                    async: false,
                    data: {
                        action: 'GetDateFunction'
                    },
                    dataType: 'text',
                    success: function(data) {
                        date1 = data;
                    }
                });
                var date = new Date(date1);
                setdate(date);
                weekdatashow();
            });

            function dayoff() {
                if ($('#isweekoff').is(':checked')) {
                    $('#showdiv').removeClass('hidden');
                } else {
                    $('#showdiv').addClass('hidden');
                }
            }

            function PreviousWeek() {
                var date = new Date($('#hiddate_1').val());
                var prweek = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 7);
                setdate(prweek);
            }

            function NextWeek() {
                var date = new Date($('#hiddate_1').val());
                var nextweek = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 7);
                setdate(nextweek);
            }

            function setdate(date1) {
                var date = date1;
                var week = [];
                var set = ["<th class='border-right'></th>"];
                var monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var i;
                for (i = 1; i <= 7; i++) {
                    var first = date.getDate() - date.getDay() + (i - 1);
                    date.setDate(first);
                    var day = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
                    var Formated_day = monthNames[date.getMonth()] + " " + date.getDate() + " , " + date.getFullYear();
                    week.push(day);
                    set.push("<th class='text-center'>" + days[date.getDay()] + "<br>" + Formated_day + "<input type='hidden' id='hiddate_" + i + "' name='hiddate_" + i + "' value='" + day + "'/></th>");
                    $('.td-' + i).html('');
                    showdata('hiddate_' + i, day);
                }
                $('#setdate').html(set);
                weekdatashow();
            }

            function showdata(id, val) {
                var hiddendate_id = id;
                var seq = hiddendate_id.split('_')[1];
                var hiddendate_val = val;
                var cls = document.getElementsByClassName("td-" + seq);
                var i;
                for (i = 0; i < cls.length; i++) {
                    var chk = cls[i].id.split('_');
                    var chk1 = "";
                    if (chk.length > 4) {
                        chk1 = chk[0] + "_" + chk[1] + "_" + chk[2] + "_" + chk[3];
                    } else {
                        chk1 = cls[i].id;
                    }
                    var hiddendateid = chk1 + "_" + hiddendate_val;
                    cls[i].setAttribute("id", hiddendateid);
                }
            }

            function insertLeave(data, wid) {
                $('#addType').modal('show');
                $('#typeid').val(data.id);
                $('#wid').val(wid);
            }

            function AddLeaveData() {
                var data = $('#typeid').val();
                var userid = <?php echo $userid ?>;
                var date = data.split('_')[4];

                $.ajax({
                    url: "InsertData.php",
                    type: "POST",
                    dataType: "JSON",
                    data: $("#AddTypeForm").serialize() + "&userid=" + userid + "&date=" + date + "&action=WorkforcescheduleInsert",
                    //{action: 'WorkforcescheduleInsert',uniqid:data,userid:userid,wid:wid,date:date},
                    success: function(response) {
                        if (response.status == 1) {
                            myAlert(response.title + "@#@" + response.message + "@#@success");
                            if (response.isweek == 0) {
                                $('#' + data).html('<div></div>');
                            } else {
                                if (response.ispaidval == 1) {
                                    var paidstatus = "<span class='label label-success' style='margin-top: 5px;'>PAID</span><br>";
                                } else if (response.ispaidval == 2) {
                                    var paidstatus = "<span class='label label-info' style='margin-top: 5px;'>UNPAID</span><br>";
                                } else if (response.ispaidval == 3) {
                                    var paidstatus = "<span class='label label-warning' style='margin-top: 5px;'>SICK</span><br>";
                                }
                                var span = "<div style='text-align: center;'>" + paidstatus + "<span class='label label-danger'>DAY OFF</span></div>";
                                $('#' + data).html(span);
                            }

                        } else {
                            myAlert(response.title + "@#@" + response.message + "@#@danger");
                        }
                    }
                });
            }

            function weekdatashow() {
                var userid = <?php echo $userid ?>;
                var startdate = $('#hiddate_1').val();
                var enddate = $('#hiddate_7').val();

                $.ajax({
                    url: "loaddata.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'WorkforceScheduleWeekDataShow',
                        userid: userid,
                        startdate: startdate,
                        enddate: enddate
                    },
                    success: function(response1) {
                        var response = response1;
                        var len = 0;
                        if (response.length > 0) {
                            len = response.length;
                        }
                        for (var i = 0; i < len; i++) {
                            var uniqid = response[i].uniqid;
                            if (response[i].isweekoff == 1) {

                                if (response[i].ispaidval == 1) {
                                    var paidstatus = "<span class='label label-success' style='margin-top: 5px;'>PAID</span><br>";
                                } else if (response[i].ispaidval == 2) {
                                    var paidstatus = "<span class='label label-info' style='margin-top: 5px;'>UNPAID</span><br>";
                                } else if (response[i].ispaidval == 3) {
                                    var paidstatus = "<span class='label label-warning' style='margin-top: 5px;'>SICK</span><br>";
                                }
                                var span = "<div style='text-align: center;'>" + paidstatus + "<span class='label label-danger'>DAY OFF</span></div>";
                                $('#' + uniqid).html(span);
                            } else {
                                $('#' + uniqid).html();
                            }
                        }

                    }
                });
            }
        </script>
</body>

</html>