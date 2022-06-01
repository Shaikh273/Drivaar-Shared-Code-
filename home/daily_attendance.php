<?php
$page_title = "Drivaar";
    include 'DB/config.php';
    $page_id=4;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
        if($userid==1)
        {
          $uid='%';
        }
        else
        {
          $uid=$userid;
        }
    }
    else
    {
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
            background-color: #f8fafc!important;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
   <?php include('loader.php'); ?>
     <div id="main-wrapper">
       <?php  include('header.php'); ?>
        <div class="page-wrapper">
             <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card">    
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Daily Attendance</div>
                                            <div id="fulldate"></div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                               <div class="form-group has-primary">
                                                <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="AttendanceData();">
                                                    <option value="%">All Depot</option>
                                                        <?php
                                                            $mysql = new Mysql();
                                                            $mysql -> dbConnect();

                                                             $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                    WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '".$uid."'";
                                                            $strow =  $mysql -> selectFreeRun($statusquery);
                                                            while($statusresult = mysqli_fetch_array($strow))
                                                            {
                                                              ?>
                                                                  <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                                              <?php
                                                            }
                                                            $mysql -> dbDisconnect();
                                                        ?>
                                                </select>    
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <input type="date" class="form-control" id="inputdate" name="inputdate"   onchange="AttendanceData();" value="<?php echo date('Y-m-d'); ?>">
                                                </div>
                                            </div>
                                       
                                            <div class="col-md-2">
                                                 <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label">Haven't Clocked In</span>
                                                </label>
                                            </div>

                                            <div class="col-md-2">
                                                 <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label">Not Approved</span>
                                                </label>
                                            </div>
                                        </div><br>
                                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th>Name</th>
                                                        <th>Total Hours</th>
                                                        <th>Route</th>
                                                        <th>Availability</th>
                                                        <th class="yellow">Wave</th>
                                                        <th>Working Time</th>
                                                        <th class="border-left">Vehicle(s)</th>
                                                        <th>Inspected</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="extendedTable">
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
        </div>
<div id="DetailModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-md">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Working Time</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="wid" name="wid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Start:</label>
                                    <input type="time" class="form-control" name="start" id="start">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">End:</label>
                                    <input type="time" class="form-control" name="end" id="end">
                                </div>
                            </div>
                           
                        </div>
                  </div>
                </div>
          </div>
          <div class="modal-footer">
              <button type="button" onclick="AddWorkingTime();" class="btn btn-success waves-effect waves-light">Save changes</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
<?php include('footer.php'); ?>
</div>
<?php include('footerScript.php');?>

<script type="text/javascript">
    var monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"]; 

    $(document).ready(function(){
          AttendanceData();
    });

    function AttendanceData()
    {
        var depot_id = $('#depot_id').val();
        var inputdate = $('#inputdate').val();
        var d = new Date(inputdate);
        var date = (monthNames[d.getMonth()]) + " " + d.getDate() + ", " + d.getFullYear();
        if($('#inputdate').val())
        {
           $('#fulldate').html(date);
        }
        else
        {
          $('#fulldate').html('');
        }
       
       
        $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'AttendanceDataLoadTable',depot_id:depot_id,inputdate: inputdate},
          dataType: 'text',
          success: function(data) {
            if(data)
            {
              $('#extendedTable').html(data);
            }
          }
        }); 
    }

    function DetailModel(id)
    {
        $('#DetailModel').modal('show');
        $('#wid').val(id);
        if(start)
        {
            $('#start').val(start);
        }
        if(end)
        {
            $('#end').val(end);
        }
        event.preventDefault();
    }

    function AddWorkingTime()
    {
      var id = $('#wid').val();
      var start = $('#start').val();
      var end = $('#end').val();

      $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'ContractorAddWorkingTime',id: id,start:start,end:end},
          dataType: 'json',
          success: function(result) {
            if(result.status==1)
            {
                myAlert(result.title + "@#@" + result.message + "@#@success");    
            }
            else
            {
                myAlert(result.title + "@#@" + result.message + "@#@danger");    
            }
          }
      });
    }


    function loadpage(vid)
    {
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'VehicleSetSessionData', vid: vid},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                    window.location = '<?php echo $webroot ?>vehicle_details_show.php';
                } 
            }
        });
    }
</script>
</body>
</html>