<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $userid = $_SESSION['userid'];
}
else
{
    if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    {
       header("location: userlogin.php");
    }
    else
    {
       header("location: login.php");  
    }
}
//include('authentication.php');
include 'DB/config.php';
$page_title="Dashboard";

$mysql = new Mysql();
$mysql -> dbConnect();
$query = "SELECT COUNT(id) FROM `tbl_contractor` WHERE `isdelete`=0  AND userid=".$userid;
$row =  $mysql -> selectFreeRun($query);
$cntresult = mysqli_fetch_array($row, MYSQLI_NUM);
$row1 =  $mysql -> selectFreeRun("SELECT COUNT(id) FROM `tbl_depot` WHERE `isdelete`=0 AND userid=".$userid);
$depot = mysqli_fetch_array($row1, MYSQLI_NUM);
$row2 =  $mysql -> selectFreeRun("SELECT COUNT(id) FROM `tbl_user` WHERE `isdelete`=0 AND `userid`=".$userid);
$workforce = mysqli_fetch_array($row2, MYSQLI_NUM);
$row3 =  $mysql -> selectFreeRun("SELECT COUNT(id) FROM `tbl_vehicles` WHERE `isdelete`=0 AND userid=".$userid);
$vehicle = mysqli_fetch_array($row3, MYSQLI_NUM);
$mysql -> dbDisConnect();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
</head>
    <body class="skin-default-dark fixed-layout">
        <?php
        
            include('loader.php');
        ?>
        
        <div id="main-wrapper" id="top">
            <?php
                include('header.php');
            ?>
            
          
            
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <!-- <h4 class="text-themecolor">Dashboard</h4> -->
                              <div class="header">Dashboard</div>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Contractor</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash"></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success"><?php echo $cntresult[0]; ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Depot</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash2"></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple"><?php echo $depot[0]; ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total WorkForce</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash3"></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info"><?php echo $workforce[0]; ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Vehicle</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash4"></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-down text-danger"></i> <span class="text-danger"><?php echo $vehicle[0]; ?></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Visit <small class="pull-right text-success"><i class="fa fa-sort-asc"></i> 18% High then last month</small></h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline8"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Site Traffic</h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline9"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Site Visit</h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline10"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title ">Leads by Source</h5>
                                    <div id="morris-donut-chart" class="ecomm-donute" style="height: 317px;"></div>
                                    <ul class="list-inline m-t-30 text-center">
                                        <li class="p-r-20">
                                            <h5 class="text-muted"><i class="fa fa-circle" style="color: #fb9678;"></i> Ads</h5>
                                            <h4 class="m-b-0">8500</h4>
                                        </li>
                                        <li class="p-r-20">
                                            <h5 class="text-muted"><i class="fa fa-circle" style="color: #01c0c8;"></i> Tredshow</h5>
                                            <h4 class="m-b-0">3630</h4>
                                        </li>
                                        <li>
                                            <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4F5467;"></i> Web</h5>
                                            <h4 class="m-b-0">4870</h4>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Top Products sales</h5>
                                    <ul class="list-inline text-center">
                                        <li>
                                            <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>iMac</h5>
                                        </li>
                                        <li>
                                            <h5><i class="fa fa-circle m-r-5" style="color: #b4becb;"></i>iPhone</h5>
                                        </li>
                                    </ul>
                                    <div id="morris-area-chart2" style="height: 370px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Monthly Report</h4>
                                    <div id="bar-chart" style="width:100%; height:400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div  style="z-index:1;">
                <ul class="jq-dice-menu" default-open="false" layout="column" reverse="false" snap-to="right" offset="35%" show-hints="true" hints-order="footer">
                    <div class="jq-items">
                        <!-- first element as a switch button -->
                        <li state="close"><span class="fa fa-th-large"></span></li>
                        <!-- page anchor to paragraph 2 -->
                        <li><span class="fa fa-header" href="#para2" hint="para2" data-toggle="modal" data-target="#myModal"></span></li>
                        <!-- page anchor to paragraph 3 -->
                        <li><span class="fa fa-arrows-v" href="#para3" hint="para3" data-toggle="modal" data-target="#Modal"></span></li>
                        <!-- open a page in a new window -->
                        <!-- open a page in current window -->
                        <li><span class="fa fa-github"  hint="Github" data-toggle="modal" data-target="#Modal3"></span></li>
                        <!-- page link without hint -->
                        <!-- link to phone number -->
                        <!-- link to email address -->
                        <li><span class="fa fa-envelope"  hint="Email for support" data-toggle="modal" data-target="#Modal4"></span></li>
                        <!-- page anchor to the top of the page -->
                        <li><span class="fa fa-angle-double-up" href="#top"></span></li>
                </div>
                <!-- hints of button -->
                <div class="jq-hints">
                    <div class="hint">&nbsp;</div>
                </div>
            </ul>
        </div>
    </div>
</div>
                    
                    
                    
    <?php
        include('footer.php');
    ?>         
    </div>
            
       
        <!--<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>-->
        <!--<script src="../assets/node_modules/popper/popper.min.js"></script>-->
        <!--<script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>-->
        <!--<script src="dist/js/perfect-scrollbar.jquery.min.js"></script>-->
        <!--<script src="dist/js/waves.js"></script>-->
        <!--<script src="dist/js/sidebarmenu.js"></script>-->
        <!--<script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>-->
        <!--<script src="dist/js/custom.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>-->
        <!--<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>-->
        <!--<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>-->
        

<script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            } );
            // DataTable
            var table = $('#example').DataTable({
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
         
                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                }
            });
         
        });   
</script>
<script src="dist/jq.dice-menu.js"></script>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="card mb-4">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">My Tasks</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <div class="border border-dashed cursor-pointer px-2 py-1 rounded mb-2 " wire:click="toggle(97763)">
                         <svg class="icon mr-1 text-grey-dark d-inline" style="height: 15px; width: 15px;border-radius: 50%;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200z"></path></svg>                            
                         Hi Asad, Please add dashboard for on road hours. Thanks, Omar
             </div>
        </div>
    
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="Modal4">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div wire:id="BcsR7UwUGyUc4E9tmae2" xmlns:wire="http://www.w3.org/1999/xhtml" class="position-relative">
    <div wire:loading="" class="position-fixed top-0 right-0 bottom-0 bg-yellow">

    </div>
    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="send" data-aire-id="43">

            <input type="hidden" name="_token" value="weHzvy6b22cUeGmzxxHz4dmvRjw45NRK0tmZvYOA">
        
        
    <div class="card no-shaddow mb-4">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Send message to all drivers in Avonmounth (DBS2) - Amazon Logistics (BS11 0YH) (23)</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <div class="form-group" data-aire-component="group" data-aire-for="content">
    <label class=" cursor-pointer" data-aire-component="label" for="js-message">
    Your message:
</label>


            <textarea class="form-control" data-aire-component="textarea" name="content" id="js-message" wire:model.debounce.250ms="content" data-aire-for="content"></textarea>

    
            <small class="form-text text-muted" data-aire-component="help_text" data-aire-validation-key="group_help_text" data-aire-for="content">
            This will send an email to all the drivers in the depot!
        </small>
    
    <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
            </div>

</div>


            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" wire:model="attachment">
                <label class="custom-file-label" for="validatedCustomFile">
                                        Attach a file...
                                    </label>
            </div>
        </div>
    
            <div class="card-footer">
            <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
    Send Message
</button>
        </div>
    </div>
    
    
    
</form>

</div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="Modal3">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="
    card
                        mb-4 no-shadow
    ">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Vehicle Inspection Issues</h6>
                                    <div></div>
                            </div>
        </div>
    
            <div class="card-body">
            <table class="table">
            <tbody>
            <tr>
                <td class="border-top-0 pl-0"><svg class="icon text-warning d-inline" style="height|: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> <span class="pl-1">Open</span></td>
                <td class="border-top-0 pr-0 text-right money"><strong>36</strong> issues</td>
            </tr>
            <tr>
                <td class="pl-0"><svg class="icon text-green-dark d-inline" fill="currentColor" style="height|: 15px; width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> <span class="pl-1">Resolved</span></td>
                <td class="pr-0 text-right money"><strong>0</strong> issues</td>
            </tr>
            </tbody>
        </table>
        </div>
    
            <div class="card-footer">
            <a href="/issues">View all issues</a>
        </div>
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>


  <div class="modal fade" id="Modal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="
    card
                        no-shadow
    ">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Vehicle Renewals</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <table class="table">
            <tbody>
            <tr>
                <td class="border-top-0 pl-0"><svg class="icon text-danger d-inline" style="height: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> <span class="pl-1">Overdue</span></td>
                <td class="border-top-0 pr-0 text-right money text-danger"><strong>1</strong> overdue</td>
            </tr>
            <tr>
                <td class="pl-0"><svg class="icon text-warning d-inline" style="height: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg> <span class="pl-1">Due soon</span></td>
                <td class="pr-0 text-right money"><strong>1</strong> due soon</td>
            </tr>
            </tbody>
        </table>
        </div>
    
            <div class="card-footer">
            <a href="#">View All Renewals</a>
        </div>
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
   <?php

        include('footerScript.php');

    ?>
    
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>
     <!-- Chart JS -->
    <script src="../assets/node_modules/echarts/echarts-all.js"></script>
    <script src="../assets/node_modules/echarts/echarts-init.js"></script>
    <!-- flot chart js -->
    <script src="../assets/node_modules/flot/excanvas.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.pie.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.time.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.stack.js"></script>
    <script src="../assets/node_modules/flot/jquery.flot.crosshair.js"></script>
    <script src="../assets/node_modules/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
    <script src="dist/js/pages/flot-data.js"></script>
    <!--morris JavaScript -->
    <script src="../assets/node_modules/raphael/raphael-min.js"></script>
    <script src="../assets/node_modules/morrisjs/morris.min.js"></script>
    <script src="../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
     <!-- Popup message jquery -->
    <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- Chart JS -->
    <script src="dist/js/dashboard1.js"></script>
    <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- jQuery peity -->
    <script src="../assets/node_modules/peity/jquery.peity.min.js"></script>
    <script src="../assets/node_modules/peity/jquery.peity.init.js"></script>

</body>

</html>