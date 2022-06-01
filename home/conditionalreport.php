<?php
include 'DB/config.php';
    $page_title = "Conditional Report";
    $page_id=54;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
        $id="";
        if(isset($_GET['vid']) && base64_encode(base64_decode($_GET['vid']))==$_GET['vid'])
        {
            $id = base64_decode($_GET['vid']);
        }else if(isset($_SESSION['vid']))
        {
            $id = $_SESSION['vid']; 
        }else
        {
            header("Location: index.php");
        }
        $mysql = new Mysql();
        $mysql -> dbConnect();
      //   $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,vfi.`insurance_company` as insname 
      // FROM `tbl_vehicles` v 
      // LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
      // LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
      // LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` 
      // LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
      // LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=v.`insurance_id`
      // LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
      // LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id`
      // WHERE v.`id`=".$id;
      $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vm.`name` as makename,vmo.`name` as modelname 
            FROM `tbl_vehicles` v 
            LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
            LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
            LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
            WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        
        $query1 = "SELECT MAX(`odometer`) AS odometer FROM `tbl_vehicleinspection` WHERE `isactive`=0 AND `isdelete`=0 AND `vehicle_id`=".$id;
        $row1 =  $mysql -> selectFreeRun($query1);
        $maxodometer = mysqli_fetch_array($row1);
        
        $mysql -> dbDisConnect();

    }else
    {
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: login.php");
        }
        else
        {
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
    <link href="dist/css/switch.css" rel="stylesheet" />
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <!--<link type="text/css" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> -->
    <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
    
    <style type="text/css">
    .kbw-signature { width: 400px; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
        .lined {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .mb-5, .my-5 {
            margin-bottom: 3rem!important;
        }
        .lined:after, .lined:before {
            content: "";
            border-top: 1px solid #ccc;
            margin: 0 20px 0 0;
            flex: 1 0 20px;
        }
        .lined:after {
            margin: 0 0 0 20px;
        }
    </style>
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
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $cntresult['colorcode']?>;"></i> <?php echo $cntresult['statusname'];?></button>
                              <a href="conditionalreport.php"> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report</button>
                              </a>
                           </div>
                        </div>
                    </div>
                    <input type="hidden" id="odometerVal" value="<?php echo $maxodometer['odometer']; ?>">
                    <div class="card-body">
                        <?php include('vehicle_setting.php'); ?>
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="card datacard">
                                    
                                 <div class="card-body">
                                    
                                      <div class="modal-body">
                                            <form method="post" id="conditionalreportform" name="conditionalreportform" action="" enctype="multipart/form-data">
                                                 <input type="hidden" id="id" name="id" value="" class="form-control">
                                                 <input type="hidden" name="userid" id="userid" value="<?php echo $userid?>">
                                                 <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $id?>">
                                                 <input type="hidden" name="uniqueid" id="uniqueid" value="">
                                                 <input type="hidden" name="attachments" id="attachments">
                                                 <div id="select1">
                                                    <div class="form-group">
                                                      <label class="control-label">Select Driver</label>
<select class="select form-control custom-select valid" id="driver" name="driver" aria-invalid="false">
  <option value="%">Select Driver</option> 
   <?php
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $dptLIst = "";
        if($userid!='%' && $userid!=1)
        {
          	$dpt1 = $mysql -> selectFreeRun("SELECT `depot` FROM `tbl_user` WHERE `id`=".$userid);
          	$dpttusresult = mysqli_fetch_array($dpt1);
            $dptLIst = " AND c.`depot` IN (".$dpttusresult['depot'].")";
        }
        $statusquery = "SELECT * FROM `tbl_contractor` c WHERE c.`isdelete`= 0 AND c.`isactive`= 0 ".$dptLIst;
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
                                                  <div class="lined my-5">OR</div>
                                                    <div class="form-group" style="text-align:center;">
                                                      <button type="button" class="btn btn-primary" id="withoutdriver" >Continue without selecting a driver </button>
                                                    </div>
                                                 </div>
                                                <div id="select2" class="row">
                                                    <div class="col-md-5">
                                                        <img src="imp_files/Depositphotos_144057949_s-2019 - Copy.jpg" alt="" usemap="#image-map" class="map" />
                                                        <map name="image-map">
                                    <area  alt="non driver right side wheels" title="non driver right side wheels" href="javascript: void(0);" coords="436,123,25" shape="circle" onclick="areamodel(this);">
                                    <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="434,420,25" shape="circle" onclick="areamodel(this);">
                                    <area  alt="driver left side wheels" title="driver left side wheels" href="javascript: void(0);" coords="41,123,24" shape="circle" onclick="areamodel(this);">
                                    <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="41,422,24" shape="circle" onclick="areamodel(this);">
                                    <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="173,694,188,694,192,675,174,671" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="292,675,307,671,309,696,292,693" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non diver right  side wheels" title="non diver right  side wheels" href="javascript: void(0);" coords="290,14,299,12,305,15,303,29,287,30" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver left  side wheels" title="driver left  side wheels" href="javascript: void(0);" coords="171,15,179,12,185,13,185,24,170,31" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="157,585,167,583,170,602,154,598" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="149,107,150,124,166,126,170,104" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="114,148,125,150,132,159,110,159" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="309,107,324,108,326,122,309,126" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="311,600,317,581,329,584,330,602" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="346,157,367,158,367,146,352,148" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="181,610,172,603,174,644,184,643" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="72,514,108,512,104,524,72,529" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="298,607,309,602,311,638,300,637" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="366,511,406,511,404,525,372,525" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front diver left side head light" title="front diver left side head light" href="javascript: void(0);" coords="94,94,90,75,84,64,71,65,75,79,83,91"  shape="poly" onclick="areamodel(this);">
                                    <area  alt="front diver side head  light" title="front diver side head  light" href="javascript: void(0);" coords="170,91,195,78,199,62,177,67,171,76" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front non diver right side head  light" title="front non diver right side head  light" href="javascript: void(0);"  coords="277,66,297,65,309,86,295,90,279,81" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front non diver side head  light" title="front non diver side head  light" href="javascript: void(0);" coords="397,82,404,65,385,69,383,80,387,90"  shape="poly" onclick="areamodel(this);">
                                    <area  alt="bonat " title="bonat " href="javascript: void(0);"  coords="183,86,194,81,224,84,261,84,279,84,292,87,299,109,293,109,269,110,251,111,229,114,208,111,189,111,177,109,180,95" shape="poly" onclick="areamodel(this);">
                                    <area  alt="left side bonat" title="left side bonat" href="javascript: void(0);" coords="87,65,101,78,108,99,114,108,113,120,98,87" shape="poly" onclick="areamodel(this);">
                                    <area  alt="right side bonat" title="right side bonat" href="javascript: void(0);" coords="364,116,364,98,377,72,384,65,383,74" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="front glass" title="front glass" href="javascript: void(0);"  coords="203,113,299,111,296,132,293,151,273,155,252,156,231,155,211,156,185,152,181,137,177,111,260,112,231,111,279,112" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="116,113,159,156,157,162,114,123" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="319,154,359,112,361,119,344,137,318,164" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="left window glass" title="left window glass" href="javascript: void(0);" coords="151,169,159,207,146,213,121,204,115,192,110,168,134,155,144,165"  shape="poly" onclick="areamodel(this);">
                                    <area  alt="right window glass" title="right window glass" href="javascript: void(0);"  coords="323,168,340,154,366,168,362,186,357,201,338,207,320,208,319,192,321,181" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="262,31,278,26,296,24,306,38,308,63,299,66,276,61,250,58,219,57,199,62,185,64,174,65,162,55,167,38,178,27,212,30,239,32" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="67,109,67,89,73,78,72,61,57,50,39,55,31,73,29,99,48,93" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="401,73,408,58,419,50,431,50,442,62,446,77,446,96,431,93,417,98,408,111,408,85" shape="poly" onclick="areamodel(this);">
                                    <area  alt="front left bumper guard" title="front left bumper guard" href="javascript: void(0);"  coords="66,89,69,107,57,100,46,94,34,94,33,78,38,67,43,56,49,55,59,54,67,63,71,75" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="66,437,72,532,44,529,35,484,34,456,55,453" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="404,524,405,436,426,451,441,452,440,480,436,508,436,528,419,528" shape="poly" onclick="areamodel(this);">
                                    <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="171,646,171,671,191,682,214,674,238,682,257,675,285,678,308,678,312,645,302,641,296,662,268,662,241,663,216,663,187,662,183,642" shape="poly" onclick="areamodel(this);">
                                    
                                    <area  alt="roof" title="roof" href="javascript: void(0);" coords="180,154,295,156,285,187,282,304,281,418,281,504,238,512,191,506,191,418,192,313,192,193" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver  side door" title="driver  side door" href="javascript: void(0);"  coords="34,180,37,167,78,144,103,144,112,191,122,207,152,211,164,205,167,218,145,219,127,215,113,215,95,214,70,213,50,213,40,204,35,195" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver  side door" title="non driver  side door" href="javascript: void(0);"  coords="367,141,402,143,420,159,441,163,438,190,435,209,384,215,354,216,330,218,311,219,318,172,321,209,350,210,372,152" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver side body" title="driver side body" href="javascript: void(0);" coords="183,310,183,411,182,518,134,523,108,526,111,514,71,512,70,439,70,413,56,392,35,393,33,221,90,221,150,222,182,223" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver side body" title="non driver side body" href="javascript: void(0);" coords="292,222,289,325,288,513,370,524,368,511,407,514,406,435,406,407,419,392,440,385,437,285,439,221,372,219" shape="poly" onclick="areamodel(this);">
                                    <area alt="back door driver side glass" title="back door driver side glass" href="javascript: void(0);" coords="240,553,194,554,191,577,193,599,215,602,242,600" shape="poly" onclick="areamodel(this);">
                                    <area alt="back door non driver side glass" title="back door non driver side glass"  href="javascript: void(0);" coords="242,552,286,552,290,573,288,596,264,600,241,600,242,582" shape="poly" onclick="areamodel(this);">
                                    <area alt="driver back door" title="driver back door" href="javascript: void(0);"coords="241,522,274,522,289,527,298,561,300,584,299,606,300,654,280,663,242,661,242,599,270,601,287,595,292,575,287,551,270,552,257,551,242,552" shape="poly" onclick="areamodel(this);">
                                    <area alt="non driver back door" title="non driver back door" href="javascript: void(0);" coords="191,598,218,603,241,599,242,633,243,662,212,661,186,662,182,638,182,609,190,539,197,523,239,524,240,552,219,553,193,554,192,575" shape="poly" onclick="areamodel(this);">
                                    <area  alt="radiator cover" title="radiator cover" href="javascript: void(0);" coords="194,79,204,62,227,57,252,56,273,65,281,82,237,85" shape="poly" onclick="areamodel(this);">
                                    <area  alt="driver side fender" title="driver side fender" href="javascript: void(0);"  coords="89,92,94,83,95,74,101,90,104,97,116,122,114,130,105,124,106,138,98,141,79,143,62,156,37,164,33,157,72,130,65,94,72,77,80,85" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver side fender" title="non driver side fender" href="javascript: void(0);"  coords="364,117,380,77,384,85,391,89,399,82,404,75,410,90,409,98,409,106,406,125,406,135,418,149,428,151,442,155,438,161,427,161,414,157,397,140,379,142,371, 139,364,138" shape="poly" onclick="areamodel(this);">
                                    <area  alt="non driver side wheel" title="non driver side wheel" href="javascript: void(0);" coords="435,122,23" shape="circle">
                                    <area  alt="driver side wheel" title="driver side wheel" href="javascript: void(0);" coords="41,125,24" shape="circle">
                                    <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="42,421,24" shape="circle">
                                    <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="434,419,26" shape="circle">
                                    <area  alt="front driver side wheel" title="front driver side wheel" href="javascript: void(0);" coords="170,12,188,11,189,27,170,33" shape="poly">
                                    <area  alt="front non driver side wheel" title="front non driver side wheel" href="javascript: void(0);" coords="306,14,288,13,290,27,307,34" shape="poly">
                                    <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="172,693,173,675,192,675,193,695" shape="poly">
                                    <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="290,694,311,693,310,671,290,672" shape="poly">
                                </map>
                                                    </div>
                                                    <div class="col-md-7" >
                                                      <div class="card datacard">
                                                          <div class="card-header dataheader">
                                                              <h6 class="mb-2 cardmb2">Vehicle Details</h6>
                                                          </div>    
                                                         <div class="card-body">
                                                              <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                                <dt class="col-md-4 mb-2">Vehicle Reg:</dt>
                                                                <dd class="col-md-8 mb-2 fortWeight">
                                                                    <?php echo $cntresult['registration_number']?>
                                                                </dd>
                
                                                                <dt class="col-md-4 mb-2">Make:</dt>
                                                                <dd class="col-md-8 mb-2 fortWeight">
                                                                    <?php echo $cntresult['makename']?>
                                                                </dd>
                
                                                                <dt class="col-md-4 mb-2">Model:</dt>
                                                                <dd class="col-md-8 mb-2 fortWeight">
                                                                    <?php echo $cntresult['modelname']?>
                                                                </dd>
                                                               
                                                              </dl>
                                                          </div>
                                                      </div>
                                                      <div id="vehicledetails" style="width:100%;">
                                                      
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="card-footer col-md-12">
                                                        <button class="text-left btn btn-primary btn-" type="button" id="backbtn1" onclick="backcard(1);">Back</button>
                                                        <button class="text-right btn btn-primary btn-" type="button" id="nextbtn1" style="float: right;" onclick="nextcard(1);">Continue</button>
                                                    </div>
                                                    
                                                </div>
                                                <div id="select3">
                                                    <div class="card-header ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="m-0">Report Details</h6>
                                                            </div>
            
                                                        </div>
                                                    </div>
    
                                                    <div class="card-body">
                                                        <div class="card mb-4">
                                                            <div class="card-header ">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <h6 class="m-0"><svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                                                <path d="M288 32C128.94 32 0 160.94 0 320c0 52.8 14.25 102.26 39.06 144.8 5.61 9.62 16.3 15.2 27.44 15.2h443c11.14 0 21.83-5.58 27.44-15.2C561.75 422.26 576 372.8 576 320c0-159.06-128.94-288-288-288zm0 64c14.71 0 26.58 10.13 30.32 23.65-1.11 2.26-2.64 4.23-3.45 6.67l-9.22 27.67c-5.13 3.49-10.97 6.01-17.64 6.01-17.67 0-32-14.33-32-32S270.33 96 288 96zM96 384c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm48-160c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32zm246.77-72.41l-61.33 184C343.13 347.33 352 364.54 352 384c0 11.72-3.38 22.55-8.88 32H232.88c-5.5-9.45-8.88-20.28-8.88-32 0-33.94 26.5-61.43 59.9-63.59l61.34-184.01c4.17-12.56 17.73-19.45 30.36-15.17 12.57 4.19 19.35 17.79 15.17 30.36zm14.66 57.2l15.52-46.55c3.47-1.29 7.13-2.23 11.05-2.23 17.67 0 32 14.33 32 32s-14.33 32-32 32c-11.38-.01-20.89-6.28-26.57-15.22zM480 384c-17.67 0-32-14.33-32-32s14.33-32 32-32 32 14.33 32 32-14.33 32-32 32z"></path>
                                                                            </svg> Mileage</h6>
                                                                    </div>
            
                                                                </div>
                                                            </div>
            
                                                            <div class="card-body">
                                                                <div >
                                                                    <div class="form-group" data-aire-component="group">
                                                                        <label class=" cursor-pointer" data-aire-component="label">
                                                                            What is the current mileage of the vehicle?
                                                                        </label>
                                                                        <div class="controls">
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text">
                                                                                        mi
                                                                                    </div>
                                                                                </div>
                                                                                <input type="text" class="form-control" placeholder="234 431" id="odometer" name="odometer">
                                                                            </div>
                                                                            <label class="error" for="odometer"></label>
                                                                        </div>
                                                                    </div>
            
                                                                    <div class="form-group m-0" >
                                                                        <label class=" cursor-pointer">
                                                                            Fuel - 4/8
                                                                        </label>
                
                
                                                                        <input type="range" class="form-control-range" max="100" min="0" name="fuel" step="12.5" value="50" id="fuel">
                                                                        <div class="text-danger" >
                                                                        </div>
                
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <strong>E</strong>
                                                                        <strong>F</strong>
                                                                    </div>
                                                                </div>
            
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-header ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="m-0">Tyres</h6>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group" >
                                                                    <label class=" cursor-pointer">Front left tyre:</label>
                                                                    <div class="input-group" >
                                                                        <input type="text" class="form-control" id="front_left_tyre" name="front_left_tyre" inputmode="numeric" >
                                                                        <div class="input-group-append" >
                                                                            <div class="input-group-text">
                                                                                mm
                                                                            </div>
                                                                        </div><br/>
                                                                        
                                                                    </div>
                                                                    <label class="error" for="front_left_tyre"></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group" >
                                                                    <label class=" cursor-pointer" >Front right tyre:</label>
                                                                    <div class="input-group" >
                                                                        <input type="text" class="form-control" id="front_right_tyre" name="front_right_tyre" inputmode="numeric" >
    
                                                                        <div class="input-group-append" >
                                                                            <div class="input-group-text">
                                                                                mm
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <label class="error" for="front_right_tyre"></label>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group" >
                                                                    <label class=" cursor-pointer" >
                                                                        Back Left Tyre:
                                                                    </label>
                                                                    <div class="input-group" >
                                                                        <input type="text" class="form-control" id="back_left_tyre" name="back_left_tyre" inputmode="numeric" enterkeyhint="next" >
    
                                                                        <div class="input-group-append" >
                                                                            <div class="input-group-text">
                                                                                mm
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <label class="error" for="back_left_tyre"></label>
    
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group" >
                                                                    <label class=" cursor-pointer" >
                                                                        Back Right Tyre:
                                                                    </label>
                                                                    <div class="input-group" >
                                                                        <input type="text" class="form-control" id="back_right_tyre" name="back_right_tyre" inputmode="numeric" >
    
                                                                        <div class="input-group-append" >
                                                                            <div class="input-group-text">
                                                                                mm
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <label class="error" for="back_right_tyre"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer col-md-12">
                                                        <button class="text-left btn btn-primary btn-" type="button" id="backbtn2" onclick="backcard(2);">Back</button>
                                                        <button class="text-right btn btn-primary btn-" type="button" id="nextbtn2" style="float: right;" >Continue</button>
                                                    </div>
                                                </div>
                                              
                                                <div id="select4" class="vehiclephoto">
                                                     <div class="card-header ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="m-0">Vehicle Photos</h6>
                                                            </div>
                                        
                                                        </div>
                                                    </div>
    
                                                    <div class="card-body">
                                                        <div id="vehiclephoto" class="dropzone">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card-footer col-md-12">
                                                        <button class="text-left btn btn-primary btn-" type="button" id="backbtn3" onclick="backcard(3);">Back</button>
                                                        <button class="text-right btn btn-primary btn-" type="button" id="nextbtn3" style="float: right;" >Continue</button>
                                                    </div>
                                                </div>
                                                <div id="select5">
                                                     <div class="card-header ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="m-0">Driver Signature</h6>
                                                            </div>
                                        
                                                        </div>
                                                    </div>
    
                                                    <div class="card-body">
                                                  
                                                        <div class='col-md-12'>
                                                            <!-- <div id='sig' class='border' ></div>
                                                            <br/>
                                                            <button id='clear'>Clear Signature</button>
                                                            <textarea id='signature64' class='step_5' name='sig' style='display: none'></textarea> -->
                        <input type="hidden" name="status" id="status" value="">
                        <div id="signature" style=''>
                            <canvas id="signature-pad" class="signature-pad border-2" style="width: auto;touch-action: none;height: auto;"></canvas>
                        </div>
                        <br/>
                        <input type="hidden" id="signcanvas">
                        <input type='button' id='click' class="btn btn-primary action-button" value='Save'><br/>
                        <input type='button' id='clear' class="btn btn-secondary action-button" value='Clear'><br/>
                        <textarea id='signature64' name="signature64" class="" style="display: none;"></textarea><br/>
                        <img id="sign_prev" src="" class="border-2 col-md-3" style="display: none;">


                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card-footer col-md-12">
                                                        <button class="text-left btn btn-primary btn-" type="button" id="backbtn4" onclick="backcard(4);">Back</button>
                                                        <button class="text-right btn btn-primary btn-" type="button" id="nextbtn4" style="float: right;" >Continue</button>
                                                    </div>
                                                </div>
                                                <div id="select6">
                                                     <div class="card-header ">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="m-0">Lessor Signature</h6>
                                                            </div>
                                        
                                                        </div>
                                                    </div>
    
                                                    <div class="card-body">
                                                        <div class='col-md-12'>
                                                            <!-- <div id='sig1' class='border'></div>
                                                            <br/>
                                                            <button id='clear1'>Clear Signature</button>
                                                            <textarea id='signature641' class='step_5' name='sig1' style='display: none'></textarea> -->
                                    <input type="hidden" name="status2" id="status" value="">
                                    <div id="signature" style=''>
                                        <canvas id="signature-pad2" class="signature-pad border-2" style="width: auto;touch-action: none;height: auto;"></canvas>
                                    </div>
                                    <br/>
                                    <input type="hidden" id="signcanvas2">
                                    <input type='button' id='click2' class="btn btn-primary action-button" value='Save'><br/>
                                    <input type='button' id='clear2' class="btn btn-secondary action-button" value='Clear'><br/>
                                    <textarea id='signature642' name="signature642" class="" style="display: none;"></textarea><br/>
                                    <img id="sign_prev2" src="" class="border-2 col-md-3" style="display: none;">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="card-footer col-md-12">
                                                        <input type="hidden" name="insert" id="insert" value="insert">
                                                        <button class="text-left btn btn-primary btn-" type="button" id="backbtn5" onclick="backcard(5);">Back</button>
                                                        <button class="text-right btn btn-primary btn-" type="submit" id="nextbtn5" style="float: right;">Continue</button>
                                                    </div>
                                                </div>
                                            </form>
                                      </div>
                                      
                                 </div>
                                </div>
                            </div>
                            
                        </div>
                            
                    </div>
                </div>                        
            </main>
            <div id="addimg" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header" style="background-color: rgb(255 236 230);">
                          <h4 class="modal-title">Add Damage Details</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      </div>
                      <div class="modal-body">
                    <form method="post" id="addimgform" name="addimgform" action="" enctype="multipart/form-data" >
                        <input type="hidden" id="id" name="id" value="" class="form-control">
                         <input type="hidden" name="userid" id="userid" value="<?php echo $userid?>">
                         <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $id?>">
                         <input type="hidden" name="damage_part" id="damage_part" value="">
                         <input type="hidden" name="image_name" id="image_name" value="">
                       
                        <div id="addimg1" class="dropzone">
                        </div>
                            
                        <div class="form-group">
                            <label class="control-label">Description:</label>
                            <textarea type="text" rows="2" id="description" name="description" class="form-control" placeholder=""></textarea>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Severity:</label>
                            <div class="switch-toggle switch-3 switch-candy switch" id="switch" >
                            
                            <input id="low_1" name="damage_type" class="step_1 swt" type="radio" value="1" checked="checked">
                            <label for="low_1" onclick="" style="color: green;">LOW</label>
                            <input id="medium_1" name="damage_type" class="step_1 swt" type="radio" value="2">
                            <label for="medium_1" onclick="" style="color: yellow;">MEDIUM</label>
                            <input id="high_1" class="step_1 swt" name="damage_type" type="radio" value="3">
                            <label for="high_1" onclick="" style="color: red;">HIGH</label>
                            <a></a>
                            </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label">Condition:</label>
                            <div class="row">
                                <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-1" name="conditionid" class="custom-control-input" value="1" checked="true">
                                    <label class="custom-control-label" for="condition-1" >Broken</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-2" name="conditionid" class="custom-control-input" value="2">
                                    <label class="custom-control-label" for="condition-2">Dented</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-3" name="conditionid" class="custom-control-input" value="3">
                                    <label class="custom-control-label" for="condition-3">Chipped</label>
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-4" name="conditionid" class="custom-control-input" value="4">
                                    <label class="custom-control-label" for="condition-4">Scratched</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-5" name="conditionid" class="custom-control-input" value="5">
                                    <label class="custom-control-label" for="condition-5">Missing</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-6" name="conditionid" class="custom-control-input" value="6">
                                    <label class="custom-control-label" for="condition-6">Worn</label>
                                </div>
                                </div>
                            </div>
                        </div>
                        
                            <button type="submit " name="insert" class=" btn btn-success" id="submit">Submit</button>
                          <button type="button" class=" btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    </form></div>
                  </div>
              </div>
            </div>
            
        </div>
    </div>
</div>
<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.0/jquery.maphilight.min.js"></script>
<script type="text/javascript" src="../js/jquery.signature.min.js"></script>
<script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>
<script>
Dropzone.autoDiscover = false;
var imgname = new Array();

 $(function() {
    // var a = Math.floor(100000 + Math.random() * 900000)
    // a = a.toString().substring(0, 4);
    // a =  parseInt(a);
    const d = new Date();
    a = d.valueOf();
    $("#uniqueid").val(a);
    var u = $("#uniqueid").val();
$("#nextbtn4").hide();
$("#nextbtn5").hide();
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));

            $('#click').click(function(){
                var data = signaturePad.toDataURL('image/png');
                $('#signature64').val(data);
                $("#sign_prev").show();
                $("#sign_prev").attr("src",data);
                $("#nextbtn4").show();
            });
            $('#clear').click(function(){
                signaturePad.clear();
                $("#sign_prev").hide();
                $("#sign_prev").attr("src","");
                $("#nextbtn4").hide();
            });
    var signaturePad2 = new SignaturePad(document.getElementById('signature-pad2'));

            $('#click2').click(function(){
                var data2 = signaturePad2.toDataURL('image/png');
                $('#signature642').val(data2);
                $("#sign_prev2").show();
                $("#sign_prev2").attr("src",data2);
                $("#nextbtn5").show();
            });
            $('#clear2').click(function(){
                signaturePad2.clear();
                $("#sign_prev2").hide();
                $("#sign_prev2").attr("src","");
                $("#nextbtn5").hide();
            });
     $("#select1").show();
     $("#select2").hide();
     $("#select3").hide();
     $("#select4").hide();
     $("#select5").hide();
     $("#select6").hide();
     var userid = <?php echo $userid ?>;
     var vid = <?php echo $id ?>;
     $('.map').maphilight({
        fill:true,
        fillColor:'03a9f3',
        fillOpacity: 0.4,
        stroke: false
    });
    
    var myDropzone1 = new Dropzone("div #vehiclephoto", {
        url: "extradamage_imgupload.php",
        paramName: "file",
        params: {'uniqueid':a},
        maxFilesize: 500,
        maxFiles: 20,
        autoProcessQueue: true,
        addRemoveLinks: true,
        success:function( file, response ) {
            
        }
    });
    
    var myDropzone = new Dropzone("div #addimg1", {
        url: "damage_imgupload.php",
        paramName: "file",
        maxFilesize: 500,
        maxFiles: 20,
        autoProcessQueue: true,
        addRemoveLinks: true,
        success:function(data) {
            imgname.push(data.name);
        }
    });
     
    
    $("#addimgform").validate({
        rules: {
            file: 'required',
            damage_type: 'required',
        },
        messages: {
            file: "Please select image",
            damage_type: "Please select damge type",
        }
    }); 
    $("#addimgform").on('submit', function(e){
            e.preventDefault();
            //myDropzone.processQueue();
           
            for(i = 0; i<myDropzone.files.length; i++) {
                $("#image_name").val(myDropzone.files[i].name);
                $.ajax({
                        type: 'POST',
                        url: 'damage_img.php',
                        data: new FormData(this),
                        contentType: false,
                        dataType: 'json',
                        cache: false,
                        processData:false,
                        success: function(data)
                        { 
                            if(data.status==1)
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#addimgform')[0].reset();
                                $('#addimg').modal('hide');
                                myDropzone.removeAllFiles(true); 
                                ajaxcall();
                            }
                            else
                            {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                            
                        }
                });
            }
            
        });
    
    $("#driver").change(function () {
        $("#select2").show();
        ajaxcall();
         $("#select1").hide();
         $("#select3").hide();
         $("#select4").hide();
         $("#select5").hide();
         $("#select6").hide();
    });
    $("#withoutdriver").on('click',function(){
        $("#select2").show();
        ajaxcall();
         $("#select1").hide();
         $("#select3").hide();
         $("#select4").hide();
        $("#select5").hide();
        $("#select6").hide();
    });
    
    $("#conditionalreportform").validate({
        rules: {
            front_left_tyre: 'required',
            front_right_tyre: 'required',
            back_left_tyre: 'required',
            back_right_tyre: 'required',
            vehiclephoto: 'required',
            signature64: 'required',
            signature642: 'required',
            odometer: {
              required: true,
              min: $("#odometerVal").val(),
              number: true,
            },
        },
        messages: {
            file: "Please select image",
            damage_type: "Please select damge type",
        }
    });
    
    $("#nextbtn2").on('click', function() {
      //e.preventDefault();
      if ($('#conditionalreportform').valid()) {
          nextcard(2);
        }

    });
    $("#nextbtn3").on('click', function() {
        if ($('#conditionalreportform').valid()) {
          nextcard(3);
        }
    });
    $("#nextbtn4").on('click', function() {
        if ($('#conditionalreportform').valid()) {
          nextcard(4);
        }
    });
    $("#nextbtn5").on('click', function() {
        $("#conditionalreportform").valid();

    });
    
    
    $("#conditionalreportform").on('submit', function(e){

        e.preventDefault();
        myDropzone1.processQueue();
        
        for(i = 0; i<myDropzone1.files.length; i++) {
            var img = myDropzone1.files[i].name;
            imgname[i] = u + img ;
        }
        
        $('[name="attachments"]').val(imgname);
        $.ajax({
            type: 'POST',
            url: 'InsertData.php',
            data: $("#conditionalreportform").serialize()+"&action=conditionalreportform",
            dataType: 'json',
            success: function(data)
            { 
                if(data.status==1)
                {
                    myAlert(data.title + "@#@" + data.message + "@#@success");
                    window.location.href = 'damage_reports.php';
                }
                else
                {
                    myAlert(data.title + "@#@" + data.message + "@#@danger");
                }
                
            }
        });
    });
});

    function nextcard(number) {
        var number = number+2;
        for(var i=1;i<=6;i++) {
          
            if (i == number) {
              //console.log(i);
                $("#select"+i).show();
               // console.log("show#select"+i);
            }
            else {
                $("#select"+i).hide();
                //console.log("#select"+i);
            }
        }
    }
    
    function backcard(number) {
        for(i=1;i<=6;i++) {
            if(i == number){
                $("#select"+i).show();
            }
            else {
                $("#select"+i).hide();
            }
        }
    }
    function areamodel(obj) {
        $('#addimg').modal('show');
        var damage_part = obj.title;
        $("#damage_part").val(damage_part);
    }
    function ajaxcall() {
        var userid = <?php echo $userid ?>;
        var id = <?php echo $id ?>;
        $.ajax({
                type: 'POST',
                url: 'loaddata.php',
                data:'userid='+userid+'&id='+id+'&action=vehicleDamagedata',
                success: function(data)
                { 
                    var tabledata = data.data;
                    $("#vehicledetails").html(" ");
                    $("#vehicledetails").append(data.div);
                    $(".delete").remove();
                    var obj1 = JSON.parse(JSON.stringify(data.part));
                    var i =0;
                    var j = obj1.length;
                    for(i = 0; i<j; i++) {
                        // console.log(data.type[i]);
                        var fillcolor;
                        var c = data.type[i];
                        var c1 = data.part[i];
                        var c2 = data.state[i];
                        if(c2 == 0) {
                            if(c == 1) {
                                fillcolor = '00c292';
                            } else if(c == 2) {
                                fillcolor = 'fec107';
                            } else if(c == 3) {
                                fillcolor = 'e46a76';
                            }
                            $("area[title='"+c1+"']").each(function(){
                                
                                var data = $(this).data('maphilight') || {};
                                data.alwaysOn = true;
                                data.fillColor = fillcolor;
                                data.fill = true;
                                //console.log(this);
                                
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        } else {
                            $("area[title='"+c1+"']").each(function(){
                                //console.log('bgn');
                              var data = $(this).data('maphilight') || {};
                                data.alwaysOn = false;
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        }
                        
                    }
                    
                }
        });
    }
    
    
    
</script>
<script src="signature_pad-master/js/signature_pad.js"></script>
</body>
</html>