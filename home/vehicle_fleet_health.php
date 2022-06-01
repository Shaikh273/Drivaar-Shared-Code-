<?php
$page_title = "Fleet Health";
$page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if($_SESSION['userid']==1)
    {
       $userid='%'; 
    }
    else
    {
       $userid = $_SESSION['userid']; 
    }
    // if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    // {
        

    // }else
    // {
    //     if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    //     {
    //        header("location: login.php");
    //     }
    //     else
    //     {
    //        header("location: login.php");  
    //     }
    // }


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
<?php include('loader.php');?>

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
                    
                      <br>
                      <div class="row">
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="header text-sm">Vehicle By Status</div>
                                    </div>
                                </div>
                                 <?php
                                    include 'DB/config.php';
                                    $mysql = new Mysql();
                                    $mysql -> dbConnect();
                                    $userid = $_SESSION['userid'];    

                                    $sql = "SELECT COUNT(IF(`status` = 1,`id`,NULL)) AS inactive, COUNT(IF( `status`= 0,`id`,NULL)) AS active FROM `tbl_vehicles` WHERE userid LIKE ('".$userid."') AND `isdelete`=0";
                                    $fire = $mysql -> selectFreeRun($sql);
                                    $cntresult1 = mysqli_fetch_array($fire);
                                    // $mysql -> dbDisConnect();
                                  ?>
                                <div class="card-body">
                                 <div class="row">
                                    <div class="col-md-5 ml-3 p-3 rounded" style="background-color: #e0f7ea;">
                                        <h5 class="d-inline"><?php echo $cntresult1['active'];?></h5>
                                        <span class="label label-success d-inline ml-3 font-weight-bold text-xs">Active</span>
                                    </div>
                                    <div class="col-md-5 ml-3 p-3 rounded ml-2" style="background-color: #ffdddd;">
                                        <h5 class="d-inline"><?php echo $cntresult1['inactive'];?></h5>
                                        <span class="label label-danger d-inline ml-3 font-weight-bold text-xs">Inactive</span>
                                    </div>
                                 </div>
                              </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Vehicles with Defects</div>
                                       </div>
                                        </div>
                                        <div class="col-md-4 text-right pr-4">
                                            <div class="header text-right text-sm"><a href="">View All</a></div>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                  $sql = "SELECT COUNT(v.id) as totaldefectsvehicle FROM `tbl_vehicles` v INNER JOIN tbl_vehicleinspection ON tbl_vehicleinspection.vehicle_id=v.id WHERE v.userid LIKE ('".$userid."') AND v.isdelete=0";
                                  $fire = $mysql -> selectFreeRun($sql);
                                  $cntresult1 = mysqli_fetch_array($fire);
                                  ?>
                                <div class="card-body">
                                    <div class="p-3 rounded" style="background-color: #e3eefd;">
                                        <h5 class="text-sm font-weight-bold"><?php echo $cntresult1['totaldefectsvehicle'];?> 
                                        <span class="text-xs font-weight-normal">with defect(s)</span>
                                    </div>       
                              </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Vehicle Existing Damages</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <div class="p-3 rounded" style="background-color: #e3eefd;">
                                        <h5 class="text-sm font-weight-bold"><?php echo $cntresult1['totaldefectsvehicle'];?> 
                                        <span class="text-xs font-weight-normal">with defect(s)</span>
                                    </div> 
                              </div>
                            </div> 
                        </div>
                      </div>

                      <hr>
                      
                      <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Vehicle Renewals</div>
                                       </div>
                                        </div>
                                        <div class="col-md-4 text-right pr-4">
                                            <div class="header text-right text-sm"><a href="">View All</a></div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <?php
                                    $sql = "SELECT COUNT(`tbl_addvehiclerenewaltype`.`id`) as lastthreemonthdata FROM tbl_vehicles v INNER JOIN `tbl_addvehiclerenewaltype` ON `tbl_addvehiclerenewaltype`.`vehicle_id` = v.`id` WHERE v.`userid`LIKE ('".$userid."') AND tbl_addvehiclerenewaltype.insert_date <= last_day(now()) + interval 1 day - interval 3 month";
                                    $fire = $mysql -> selectFreeRun($sql);
                                    $cntresult1 = mysqli_fetch_array($fire);

                                    $sql1 ="SELECT COUNT(`tbl_addvehiclerenewaltype`.`id`) as currentmonthdata FROM tbl_vehicles v INNER JOIN `tbl_addvehiclerenewaltype` ON `tbl_addvehiclerenewaltype`.`vehicle_id` = v.`id` WHERE v.`userid`LIKE ('".$userid."') AND MONTH(tbl_addvehiclerenewaltype.insert_date) = MONTH(CURRENT_DATE()) AND YEAR(tbl_addvehiclerenewaltype.insert_date) = YEAR(CURRENT_DATE())";
                                    $fire1 = $mysql -> selectFreeRun($sql1);
                                    $cntresult2 = mysqli_fetch_array($fire1);

                                ?>
                                <div class="card-body">

                                    <div class="row">
                                    <div class="col-md-5 ml-3 p-3 rounded" style="background-color: #e0f7ea;">
                                        <h5 class="d-inline"><?php echo $cntresult2['currentmonthdata'];?></h5>
                                        <span class="label label-success d-inline font-weight-bold text-xs ml-3">This months</span>
                                    </div>
                                    <div class="col-md-5 ml-3 p-3 rounded ml-2" style="background-color: #ffdddd;">
                                        <h5 class="d-inline"><?php echo $cntresult1['lastthreemonthdata'];?></h5>
                                        <span class="label label-danger d-inline ml-3 font-weight-bold text-xs">3 months</span>
                                    </div>
                                 </div>
                              </div>
                            </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Overdue renewals</div>
                                       </div>
                                        </div>
                                        <div class="col-md-4 text-right pr-4">
                                            <div class="header text-right text-sm"><a href="">View All</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                  <div class="row p-3 rounded" style="background-color: #e3eefd;">
                                    <div class="col-md-10">
                                        <h5 class="text-sm font-weight-bold"><?php echo $cntresult1['totaldefectsvehicle'];?> 
                                        <span class="text-xs font-weight-normal">with defect(s)</span>
                                    </div> 
                                    <div class="col-md-2">
                                        <svg style="height: 18px;float: right;" class="icon text-danger" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg>
                                    </div>
                                    </div>
                                 
                              </div>
                          </div> 
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Vehicle Utilization</div>
                                       </div>
                                        </div>
                                        <div class="col-md-4 text-right pr-4">
                                            <div class="header text-right text-sm"><a href="">BreckDown</a></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-md-5 ml-3 p-3 rounded" style="background-color: #e0f7ea;">
                                        <h5 class="d-inline"><?php echo $cntresult2['currentmonthdata'];?></h5>
                                        <span class="label label-success d-inline font-weight-bold text-xs ml-3">This months</span>
                                    </div>
                                    <div class="col-md-5 ml-3 p-3 rounded ml-2" style="background-color: #ffdddd;">
                                        <h5 class="d-inline"><?php echo $cntresult1['lastthreemonthdata'];?></h5>
                                        <span class="label label-danger d-inline ml-3 font-weight-bold text-xs">3 months</span>
                                    </div>
                                 </div>
                                 
                              </div>
                            </div> 
                        </div>
                      </div>

                      <hr>
                      
                      <div class="row mt-5">
                        <div class="col-md-4">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header text-sm">Inspection Failures last 7 days</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-md-5 ml-3 p-3 rounded" style="background-color: #e0f7ea;">
                                        <h5 class="d-inline"><?php echo $cntresult2['currentmonthdata'];?></h5>
                                        <span class="label label-success d-inline font-weight-bold text-xs ml-3">This months</span>
                                    </div>
                                    <div class="col-md-5 ml-3 p-3 rounded ml-2" style="background-color: #ffdddd;">
                                        <h5 class="d-inline"><?php echo $cntresult1['lastthreemonthdata'];?></h5>
                                        <span class="label label-danger d-inline ml-3 font-weight-bold text-xs">3 months</span>
                                    </div>
                                 </div>
                                 
                              </div>
                          </div> 
                        </div>
                        <div class="col-md-5">
                            <div class="card" style="box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;">   
                                <div class="card-header p-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="header text-sm">OSM Inspections last 30 days</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                  <div class="row">
                                    <div class="col-md-5 ml-3 p-3 rounded" style="background-color: #e0f7ea;">
                                        <h5 class="d-inline"><?php echo $cntresult2['currentmonthdata'];?></h5>
                                        <span class="label label-success d-inline font-weight-bold text-xs ml-3">This months</span>
                                    </div>
                                    <div class="col-md-5 ml-3 p-3 rounded ml-2" style="background-color: #ffdddd;">
                                        <h5 class="d-inline"><?php echo $cntresult1['lastthreemonthdata'];?></h5>
                                        <span class="label label-danger d-inline ml-3 font-weight-bold text-xs">3 months</span>
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
  </div>
</div>


<?php

include('footer.php');

?>

</div>
<!-- Date Picker Plugin JavaScript -->
<script src="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="../assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
<script src="../assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>

<?php

include('footerScript.php');

?>

<script>
function getdata(start_date, end_date) {
    console.log('grtsg');
    
}
$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();
    var table = $('#myTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'loadtabledata.php',
            'data': {
                start_date: function() { return $('#start_date').val(); },
                end_date: function() { return $('#end_date').val(); },
                action: 'loadroutestabledata'
                
            },
        },
        'destroy': true,
        'columns': [
            { data: 'route' },
            { data: 'count' }
           
        ]
    });
    
    $('#filter').on('click',function(){
        table.ajax.reload();
    });
});

</script>


</body>



</html>