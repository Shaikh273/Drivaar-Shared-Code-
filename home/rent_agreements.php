<?php
include 'DB/config.php';
$page_title = "Drivaar";
$page_id = 47;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $id = $_SESSION['vid'];
    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $uid = '%';
    } else {
        $uid = $userid;
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
    <link rel="stylesheet" type="text/css" href="rentstyle.css">
    <link href="dist/css/switch.css" rel="stylesheet" />
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .kbw-signature {
            width: 400px;
            height: 200px;
        }

        #sig canvas {
            width: 100% !important;
            height: auto;
        }
    </style>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
</head>

<body class="skin-default-dark fixed-layout">
    <?php //include('loader.php'); ?>
    <div id="main-wrapper">
        <?php include('header.php'); ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Rental Agreements<span class="label label-warning ml-2">BETA</span></div>
                                <div>
                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Rental Agreement</button>
                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Rental Agreement</button><a href="#" class="btn btn-danger" onclick="releaseID(<?php echo $userid;?>);"><span class="spmodal"><i class="fas fa-trash-alt"></i></span></a>
                                    	
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                    <div class="col-md-12" id="AddFormDiv" style="padding:0px">
                                                <form id="msform_0" method="post" class="msform" name="msform" action="" enctype="multipart/form-data" style="width:97%">
                                                    <input type="hidden" id="userid" name="userid" value="<?php echo $userid ?>">
                                                    <input type="hidden" name="last_insert_id" id="last_insert_id" class="" value="">
                                                    <br>
                                                    <div class="chkrequire" id="chkrequire"></div>
                                                    <br>
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="form-body">
        <div class="form-row">
            
            <div class="col-md-12">
                <br><br>
                <label>Select Depot *</label>
                <select id="depotList" name="depotList" class="form-control step_0" data-live-search="false" onchange="getDriverList(this);">
                    <?php
                    $mysql = new Mysql();
                    $mysql->dbConnect();
                    $expquery = "SELECT DISTINCT dpt.* FROM `tbl_depot` dpt INNER JOIN tbl_workforcedepotassign w ON w.`depot_id`=dpt.id WHERE dpt.`isdelete`=0 AND dpt.`isactive`=0 AND w.release_date IS NULL AND w.wid=".$_SESSION['userid']." ORDER BY dpt.name asc";
                    $exprow =  $mysql->selectFreeRun($expquery);
                    $dFlag = 0;
                    $sdpid = 0;
                    $dptOption=""; 
                    while ($result = mysqli_fetch_array($exprow)) {
                        if($dFlag==0)
                        {
                            $sdpid = $result['id'];
                            $dFlag=1;
                        }
                        $dptOption .= "<option value='".$result['id']."'>".$result['name']."</option>";
                    }
                    echo $dptOption;
                        ?>
                </select>

                <br><br>
                <label>Select Driver *</label>
                <select id="driver" name="driver" class="form-control step_0" data-live-search="flase">
                    <?php
                    $expquery = "SELECT DISTINCT c.*
                                FROM `tbl_contractor` c 
                                INNER JOIN `tbl_depot` w ON w.`id`=c.`depot` WHERE w.`id`=$sdpid AND c.`isdelete`=0 AND c.`isactive`=0 AND c.`iscomplated`=1   ORDER BY c.`name` ASC";
                    $exprow =  $mysql->selectFreeRun($expquery);
                    while ($result = mysqli_fetch_array($exprow)) {
                    ?>
                        <option value="<?php echo $result['id']; ?>" ><?php echo $result['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
                <br><br>
            </div>
        </div>
    </div>
    <button type="button" name="next" id="next-step_0" class="next action-button"> Continue </button>
</fieldset>

                                             <!--//1//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="form-body" id="SomeDiv">
        <div class="row">
            
            <div class="col-md-12">
        <label class="font-weight-bold mt-2">Vehicle & Rental Period</label>
        <br>
        <div class="col-md-12 border p-3 rounded-lg mt-2">
            <div class="form-row text-left mt-2">
                <div class="form-group col-md-6">
                    <label>Vehicle *</label>
                    <select id="vehicle" name="vehicle_id" class="form-control step_1" >
                    </select>
                </div>
                <div class="form-group col-md-3 mb-4">
                    <label class="control-label">Term Period Type</label>
                    <select id="termSize" name="termSize" class="form-control step_1" onchange="chnagePerDayPrice(this)">
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label class="control-label">Price Per Day *</label>
                    <input type="text" class="form-control step_1" id="priceper_day" value="" name="price_per_day" placeholder="" required="">
                </div>
            </div>

            <div class="form-row text-left">
                <div class="form-group col-md-4 mb-4">
                    <label class="control-label">Pick Up Date</label>
                    <input id="pickup_date" type="date" name="pickup_date" placeholder="mm/dd/yyyy" value="" class="form-control bg-white border-left-0 border-md step_1" required="" onkeydown="return false" onchange="setMinReturnDate(this)">
                    <!-- onchange="disableFirld();" -->
                </div>

                <div class="form-group col-md-4 mb-4">
                    <label>Return Date</label>
                    <input id="return_date" type="date" name="return_date" placeholder="mm/dd/yyyy" value="" class="form-control bg-white border-left-0 border-md step_1" required="" onkeydown="return false" disabled>
                    <!-- onchange="disableFirld();" -->
                </div>
                <!-- <div class="form-group col-md-2 mb-4">
                    <label>Get Availablility</label>
                    <input type="button" value="Get Availble Vehicle" onclick="checkAvailVeh();" class="btn btn-primary">
                </div> -->
            </div>
        </div>
    </div>
    </div>
    </div>
    <br>
    <input type="button" name="previous" id="pre-step_1" class="previous action-button" value="Go Back"/>
    <input type="button" name="next" id="next-step_1" class="next action-button ml-3" value="Continue"/>
</fieldset>

                                                    <!--//2//-->
<fieldset class="col-md-12 offset-md-2 " style="margin: 0px;width: fit-content;margin: auto;">
    
    <label class="font-weight-bold">Damage Report</label>
    <div class="row">
        <div class="col-md-6">
            <img src="imp_files/Depositphotos_144057949_s-2019 - Copy.jpg" alt="" id="mapImageDiv" usemap="#image-map" class="map" style="width:475px;height: 715px;"/>
            <map name="image-map">
                <area alt="non driver right side wheels" title="non driver right side wheels" href="javascript: void(0);" coords="436,123,25" shape="circle" onclick="areamodel(this);">
                <area alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="434,420,25" shape="circle" onclick="areamodel(this);">
                <area alt="driver left side wheels" title="driver left side wheels" href="javascript: void(0);" coords="41,123,24" shape="circle" onclick="areamodel(this);">
                <area alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="41,422,24" shape="circle" onclick="areamodel(this);">
                <area alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="173,694,188,694,192,675,174,671" shape="poly" onclick="areamodel(this);">
                <area alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="292,675,307,671,309,696,292,693" shape="poly" onclick="areamodel(this);">
                <area alt="non diver right  side wheels" title="non diver right  side wheels" href="javascript: void(0);" coords="290,14,299,12,305,15,303,29,287,30" shape="poly" onclick="areamodel(this);">
                <area alt="driver left  side wheels" title="driver left  side wheels" href="javascript: void(0);" coords="171,15,179,12,185,13,185,24,170,31" shape="poly" onclick="areamodel(this);">

                <area alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="157,585,167,583,170,602,154,598" shape="poly" onclick="areamodel(this);">
                <area alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="149,107,150,124,166,126,170,104" shape="poly" onclick="areamodel(this);">
                <area alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="114,148,125,150,132,159,110,159" shape="poly" onclick="areamodel(this);">
                <area alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="309,107,324,108,326,122,309,126" shape="poly" onclick="areamodel(this);">
                <area alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="311,600,317,581,329,584,330,602" shape="poly" onclick="areamodel(this);">
                <area alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="346,157,367,158,367,146,352,148" shape="poly" onclick="areamodel(this);">

                <area alt="back left side light" title="back left side light" href="javascript: void(0);" coords="181,610,172,603,174,644,184,643" shape="poly" onclick="areamodel(this);">
                <area alt="back left side light" title="back left side light" href="javascript: void(0);" coords="72,514,108,512,104,524,72,529" shape="poly" onclick="areamodel(this);">
                <area alt="back right side light" title="back right side light" href="javascript: void(0);" coords="298,607,309,602,311,638,300,637" shape="poly" onclick="areamodel(this);">
                <area alt="back right side light" title="back right side light" href="javascript: void(0);" coords="366,511,406,511,404,525,372,525" shape="poly" onclick="areamodel(this);">
                <area alt="front diver left side head light" title="front diver left side head light" href="javascript: void(0);" coords="94,94,90,75,84,64,71,65,75,79,83,91" shape="poly" onclick="areamodel(this);">
                <area alt="front diver side head  light" title="front diver side head  light" href="javascript: void(0);" coords="170,91,195,78,199,62,177,67,171,76" shape="poly" onclick="areamodel(this);">
                <area alt="front non diver right side head  light" title="front non diver right side head  light" href="javascript: void(0);" coords="277,66,297,65,309,86,295,90,279,81" shape="poly" onclick="areamodel(this);">
                <area alt="front non diver side head  light" title="front non diver side head  light" href="javascript: void(0);" coords="397,82,404,65,385,69,383,80,387,90" shape="poly" onclick="areamodel(this);">
                <area alt="bonat " title="bonat " href="javascript: void(0);" coords="183,86,194,81,224,84,261,84,279,84,292,87,299,109,293,109,269,110,251,111,229,114,208,111,189,111,177,109,180,95" shape="poly" onclick="areamodel(this);">
                <area alt="left side bonat" title="left side bonat" href="javascript: void(0);" coords="87,65,101,78,108,99,114,108,113,120,98,87" shape="poly" onclick="areamodel(this);">
                <area alt="right side bonat" title="right side bonat" href="javascript: void(0);" coords="364,116,364,98,377,72,384,65,383,74" shape="poly" onclick="areamodel(this);">

                <area alt="front glass" title="front glass" href="javascript: void(0);" coords="203,113,299,111,296,132,293,151,273,155,252,156,231,155,211,156,185,152,181,137,177,111,260,112,231,111,279,112" shape="poly" onclick="areamodel(this);">
                <area alt="front glass" title="front glass" href="javascript: void(0);" coords="116,113,159,156,157,162,114,123" shape="poly" onclick="areamodel(this);">
                <area alt="front glass" title="front glass" href="javascript: void(0);" coords="319,154,359,112,361,119,344,137,318,164" shape="poly" onclick="areamodel(this);">

                <area alt="left window glass" title="left window glass" href="javascript: void(0);" coords="151,169,159,207,146,213,121,204,115,192,110,168,134,155,144,165" shape="poly" onclick="areamodel(this);">
                <area alt="right window glass" title="right window glass" href="javascript: void(0);" coords="323,168,340,154,366,168,362,186,357,201,338,207,320,208,319,192,321,181" shape="poly" onclick="areamodel(this);">

                <area alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="262,31,278,26,296,24,306,38,308,63,299,66,276,61,250,58,219,57,199,62,185,64,174,65,162,55,167,38,178,27,212,30,239,32" shape="poly" onclick="areamodel(this);">
                <area alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="67,109,67,89,73,78,72,61,57,50,39,55,31,73,29,99,48,93" shape="poly" onclick="areamodel(this);">
                <area alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="401,73,408,58,419,50,431,50,442,62,446,77,446,96,431,93,417,98,408,111,408,85" shape="poly" onclick="areamodel(this);">
                <area alt="front left bumper guard" title="front left bumper guard" href="javascript: void(0);" coords="66,89,69,107,57,100,46,94,34,94,33,78,38,67,43,56,49,55,59,54,67,63,71,75" shape="poly" onclick="areamodel(this);">

                <area alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="66,437,72,532,44,529,35,484,34,456,55,453" shape="poly" onclick="areamodel(this);">
                <area alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="404,524,405,436,426,451,441,452,440,480,436,508,436,528,419,528" shape="poly" onclick="areamodel(this);">
                <area alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="171,646,171,671,191,682,214,674,238,682,257,675,285,678,308,678,312,645,302,641,296,662,268,662,241,663,216,663,187,662,183,642" shape="poly" onclick="areamodel(this);">

                <area alt="roof" title="roof" href="javascript: void(0);" coords="180,154,295,156,285,187,282,304,281,418,281,504,238,512,191,506,191,418,192,313,192,193" shape="poly" onclick="areamodel(this);">
                <area alt="driver  side door" title="driver  side door" href="javascript: void(0);" coords="34,180,37,167,78,144,103,144,112,191,122,207,152,211,164,205,167,218,145,219,127,215,113,215,95,214,70,213,50,213,40,204,35,195" shape="poly" onclick="areamodel(this);">
                <area alt="non driver  side door" title="non driver  side door" href="javascript: void(0);" coords="367,141,402,143,420,159,441,163,438,190,435,209,384,215,354,216,330,218,311,219,318,172,321,209,350,210,372,152" shape="poly" onclick="areamodel(this);">
                <area alt="driver side body" title="driver side body" href="javascript: void(0);" coords="183,310,183,411,182,518,134,523,108,526,111,514,71,512,70,439,70,413,56,392,35,393,33,221,90,221,150,222,182,223" shape="poly" onclick="areamodel(this);">
                <area alt="non driver side body" title="non driver side body" href="javascript: void(0);" coords="292,222,289,325,288,513,370,524,368,511,407,514,406,435,406,407,419,392,440,385,437,285,439,221,372,219" shape="poly" onclick="areamodel(this);">
                <area alt="back door driver side glass" title="back door driver side glass" href="javascript: void(0);" coords="240,553,194,554,191,577,193,599,215,602,242,600" shape="poly" onclick="areamodel(this);">
                <area alt="back door non driver side glass" title="back door non driver side glass" href="javascript: void(0);" coords="242,552,286,552,290,573,288,596,264,600,241,600,242,582" shape="poly" onclick="areamodel(this);">
                <area alt="driver back door" title="driver back door" href="javascript: void(0);" coords="241,522,274,522,289,527,298,561,300,584,299,606,300,654,280,663,242,661,242,599,270,601,287,595,292,575,287,551,270,552,257,551,242,552" shape="poly" onclick="areamodel(this);">
                <area alt="non driver back door" title="non driver back door" href="javascript: void(0);" coords="191,598,218,603,241,599,242,633,243,662,212,661,186,662,182,638,182,609,190,539,197,523,239,524,240,552,219,553,193,554,192,575" shape="poly" onclick="areamodel(this);">
                <area alt="radiator cover" title="radiator cover" href="javascript: void(0);" coords="194,79,204,62,227,57,252,56,273,65,281,82,237,85" shape="poly" onclick="areamodel(this);">
                <area alt="driver side fender" title="driver side fender" href="javascript: void(0);" coords="89,92,94,83,95,74,101,90,104,97,116,122,114,130,105,124,106,138,98,141,79,143,62,156,37,164,33,157,72,130,65,94,72,77,80,85" shape="poly" onclick="areamodel(this);">
                <area alt="non driver side fender" title="non driver side fender" href="javascript: void(0);" coords="364,117,380,77,384,85,391,89,399,82,404,75,410,90,409,98,409,106,406,125,406,135,418,149,428,151,442,155,438,161,427,161,414,157,397,140,379,142,371, 139,364,138" shape="poly" onclick="areamodel(this);">
                <area alt="non driver side wheel" title="non driver side wheel" href="javascript: void(0);" coords="435,122,23" shape="circle">
                <area alt="driver side wheel" title="driver side wheel" href="javascript: void(0);" coords="41,125,24" shape="circle">
                <area alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="42,421,24" shape="circle">
                <area alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="434,419,26" shape="circle">
                <area alt="front driver side wheel" title="front driver side wheel" href="javascript: void(0);" coords="170,12,188,11,189,27,170,33" shape="poly">
                <area alt="front non driver side wheel" title="front non driver side wheel" href="javascript: void(0);" coords="306,14,288,13,290,27,307,34" shape="poly">
                <area alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="172,693,173,675,192,675,193,695" shape="poly">
                <area alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="290,694,311,693,310,671,290,672" shape="poly">
            </map>
        </div>
        <div class="col-md-6" style="height: 100%;overflow: auto;">
            <div class="card datacard">
                <div class="card-header dataheader">
                    <h6 class="mb-2 cardmb2">Vehicle Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                        <dt class="col-md-4 mb-2">Vehicle Reg:</dt>
                        <dd class="col-md-8 mb-2 fortWeight" id="veh_reg">
                        </dd>

                        <dt class="col-md-4 mb-2">Make:</dt>
                        <dd class="col-md-8 mb-2 fortWeight" id="veh_make">
                        </dd>

                        <dt class="col-md-4 mb-2">Model:</dt>
                        <dd class="col-md-8 mb-2 fortWeight" id="veh_model">
                        </dd>

                    </dl>
                </div>
            </div>
            <div id="vehicledetails" style="width:100%;">

            </div>

        </div>
    </div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_step_2" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_2" class="next action-button" value="Continue" />
    <br>
</fieldset>

                                                    <!--//3//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
<div class="row">
    
    <div class="col-md-12">
    <label class="font-weight-bold">Damage Report</label>
    <div class="card" style="border: 1px solid #b5b5b4;">
        <div class="card-body">
            <h5 class="font-weight-bold">Report Details</h5>

            <div class="card mt-3 border" style="border-radius: 5px;">
                <div class="card-header" style="background-color: #f9cbbe;">
                    <h5 class="font-weight-bold">Mileage</h5>
                </div>

                <div class="card-body">

                    <label class=" cursor-pointer">What is the current mileage of the vehicle?</label>


                    <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group">
                        <div class="input-group-prepend" data-aire-component="prepend" data-aire-validation-key="group_prepend">
                            <div class="input-group-text">
                                mi
                            </div>
                        </div>
                        <input type="text" class="form-control step_3" data-aire-component="input" placeholder="234 431" name="current_odometer" id="current_odometer" required="">
                    </div>

                    <small class="form-text text-muted" data-aire-component="help_text" data-aire-validation-key="group_help_text">
                        Current odometer is 1mi
                    </small>

                    <label>Fuel - 4/8</label>
                    <input type="range" class="form-control-range step_3" data-aire-component="input" max="100" min="0" name="fuel_range" step="12.5" value="50" wire:model="state.fuel" data-aire-for="range" id="__aire-4-range5">
                    <!--<div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="range"></div>-->
                    <div class="d-flex justify-content-between">
                        <strong>E</strong>
                        <strong>F</strong>
                    </div>
                </div>
            </div>


            <div class="card border" style="border-radius: 5px;">
                <div class="card-header" style="background-color: #f9cbbe;">
                    <h5 class="font-weight-bold">Tyres</h5>
                </div>
                <div class="row p-3">
                    <div class="col-md-6">
                        <label>Front left tyre:</label>
                        <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group">
                            <input type="text" class="form-control step_3" name="front_left_type" id="front_left_type" required="">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Front right tyre:</label>
                        <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group">
                            <input type="text" class="form-control step_3" name="front_right_type" id="front_right_type" required="">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col-md-6">
                        <label>Back Left Tyre:</label>
                        <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group">
                            <input type="text" class="form-control step_3" name="back_left_type" id="back_left_type" required="">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Back Right Tyre:</label>
                        <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group">
                            <input type="text" class="form-control step_3" name="back_right_type" id="back_right_type" required="">
                            <div class="input-group-text">mm</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
    <br>

    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_3" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_3" class="next action-button" value="Continue" />

</fieldset>

                                                    <!--//4//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="row">
        <div class="col-md-12">
    <label class="font-weight-bold">Upload Vehicle Images</label>
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-8">
                <input type="file" class="form-control p-1" name="image" id="image" multiple="multiple" required="">
            </div>
        </div>
    </div>
    </div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_4" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_4" class="next action-button" value="Continue" />
</fieldset>

                                                    <!--//5//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
       <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">

                <label class="font-weight-bold">Damage Report Signature of Driver</label>
                <div class="card" style="border: 1px solid #b5b5b4;">
                    <div class="card-body">
                        <input type="hidden" name="status" id="status" value="">
                        <div id="signature" style=''>
                            <canvas id="signature-pad" class="signature-pad border-2" style="width: auto;touch-action: none;height: auto;"></canvas>
                        </div>
                        <br/>
                        <input type="hidden" id="signcanvas">
                        <input type='button' id='click' class="btn btn-primary action-button" value='Save'><br/>
                        <input type='button' id='clear' class="btn btn-secondary action-button" value='Clear'><br/>
                        <textarea id='signature64' class="" style="display: none;"></textarea><br/>
                        <img id="sign_prev" src="" class="border-2 col-md-3" style="display: none;">
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>
    <br /><br />
    <!--<div class="col-md-12">-->
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_5" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_5" class="next action-button" value="Continue" style="display:none;"/>
    <!--</div>-->
</fieldset>

                                                    <!--//6//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">

                <label class="font-weight-bold">Damage Report</label>
                <div class="card" style="border: 1px solid #b5b5b4;">
                    <div class="card-body">
                        <label class="font-weight-bold">Lessor Signature</label>
                        <div class="">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="status2" id="status" value="">
                                    <div id="signature" style=''>
                                        <canvas id="signature-pad2" class="signature-pad border-2" style="width: auto;touch-action: none;height: auto;"></canvas>
                                    </div>
                                    <br/>
                                    <input type="hidden" id="signcanvas2">
                                    <input type='button' id='click2' class="btn btn-primary action-button" value='Save'><br/>
                                    <input type='button' id='clear2' class="btn btn-secondary action-button" value='Clear'><br/>
                                    <textarea id='signature642' class="" style="display: none;"></textarea><br/>
                                    <img id="sign_prev2" src="" class="border-2 col-md-3" style="display: none;">
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
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_6" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_6" class="next action-button" value="Continue" />
</fieldset>

                                                    <!--//7//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
<div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">
                <div class="card rounded-lg border">
                    <div class="card-body">
                        <label>Deposit:</label>
                        <div class="input-group" data-aire-component="input_group" data-aire-validation-key="group_input_group" data-aire-for="deposit">
                            <div class="input-group-prepend" data-aire-component="prepend" data-aire-validation-key="group_prepend" data-aire-for="deposit">
                                <div class="input-group-text">
                                    £
                                </div>
                            </div>

                            <input type="text" class="form-control step_7" name="deposit" required="">
                        </div>

                        <div class="p-2 mt-3" style="background-color: #e4e6e8;">
                            <div class="d-flex align-items-baseline">
                                <svg class="icon mr-2 d-inline" style="height: 10px; width: 10px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                                </svg>
                                <span class="d-inline">Vehicle deposit for the vehicle hire</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <br><br>
        </div>
    </div>
</div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_7" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_7" class="next action-button" value="Continue" />
</fieldset>
                                                    <!--//8//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;" id="type">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12 border rounded-lg p-3">
                <span class="pt-3 font-weight-bold">Please select one of the following:</span><br><br>
                <div class="row">
                    <div class="col-md-12">
                        <label>
                        Drivaar's Insurance</label>
                        <input type="radio" id="drivaar" style="height:15px; width:15px; " name="ins_cmp_type" class="step_8 check" value="0" required="">
                        <label style="margin-right: 10px;">Hirer's Insurance</label>
                        <input type="radio" id="Hirer" style="height:15px; width:15px; " name="ins_cmp_type" class="step_8 check" value="1" required="">
                    </div>

                </div>

            </div>
        </div>
    </div>
    </div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_8" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_8" class="next action-button" value="Continue" />
</fieldset>

<!--//9//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">

    <input type="hidden" name="" id="insurance" value="">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">

                <div class="card rounded-lg border" id="insurance_1">
                    <div class="card-header" style="background-color: #fb9678;">
                        <h6>Hirer's Insurance</h6>
                    </div>
                    <div class="card-body">
                        <p class="tex-c">
                            This vehicle is hired to you expressly on condition that you have arranged a motor insurance policy of Fully Comprehensive Insurance to be in force for the duration of the hire period.
                            As Hirer, I hereby acknowledge that these insurance conditions have been drawn to my attention and that I am accordingly responsible for arranging insurance.
                            I will provide the insurance certificate file as proof to the Lessor.
                        </p>
                        <!--<input type="button" name="is_ins_cmp_type" class="previous action-button" name="previous" style="width: 150px; background-color: #fb9678;" id="is_ins_cmp_type" value="Change Insurance">-->

                        <div class="form-group custom-control custom-checkbox m-0" data-aire-component="group">
                            <input type="checkbox" name="is_ins_cmp_type" value="1" class="custom-control-input step_9" data-aire-component="checkbox" wire:model="confirmed" id="insurance0">

                            <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" for="insurance0">
                                I have read and agree with the above
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card rounded-lg border" id="insurance_0">
                    <div class="card-header" style="background-color: #fb9678;">
                        <h6>Insurance Type</h6>
                    </div>
                    <div class="card-body">
                        <p class="tex-c">
                            Drivaar's Insurance
                            The vehicle is hired under the Terms and Condition of the Lessor’s policy of motor insurance.
                            The Hirer has been acquainted with the Terms and Conditions and agrees to those Terms and Conditions with his signature.
                        </p>

                        <div class="form-group custom-control custom-checkbox" data-aire-component="group">
                            <input type="checkbox" value="1" class="custom-control-input step_9" data-aire-component="checkbox" wire:model="confirmed" id="insurance1">

                            <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" for="insurance1">
                                I have read and agree with the above
                            </label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_9" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_9" class="next action-button" value="Continue" />
</fieldset>

<!--//10//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">
                <div class="card border p-4">
                    <h4 class="font-weight-bold">Liability Statement</h4>
                    <p class="mt-3">
                        I hereby acknowledge that during the length of the hiring agreement I shall be liable as the owner of the vehicle let to me thereunder in respect of:

                        a.) Any fixed penalty offence or contravention in respect of that vehicle under part III or section 66 of Road Traffic Act 19 88 including congestion charging and

                        b.) Any excess parking charge which may be incurred in respect of that vehicle in pursuance of an Order under section 45 and/ or 46 of the Road Regulation Traffic Act 1984 (as amended)

                        c.) Any penalty charge incurred under the Road Traffic Act 1991.

                        d) Any mechanical or bodywork damage.

                        I also acknowledge that this liability shall extend to any other vehicle let to me under the same hiring agreement and to any period by which the original period of hiring may be extended. I hereby agree to hire the above vehicle on the Terms & Conditions set out herein & overleaf and confirm that if payment hereunder is to be made by credit/debit card my signature below shall constitute authority to debit my nominated credit/debit card with the total due amount plus any administration charges, extensions or additional charges resulting from this rental.

                        The Hirer and, if I am not the Hirer, I consent to my personal information (including name, address, photo and drivers licence details) and information concerning the Hirer and the hire of the vehicle under this rental agreement (including details as to payment record, credit worthiness, accidents or claims or theft or damage to the vehicle, delays in vehicle return, threatening or abusive behaviour and any other relevant information) being shared with other vehicle rental companies, suppliers to such companies and the police and other regulatory authorities, insurers and credit reference agencies, for the purposes of crime detection, risk management and assessing whether or not others may wish to hire a vehicle to me.
                    </p>

                    <div class="border border-dashed rounded-lg p-2 bg-blue-50 mt-3">
                        <div class="form-group custom-control custom-checkbox m-0" data-aire-component="group">
                            <input type="checkbox" name="is_liability" value="1" class="custom-control-input step_10" id="liability" required="">
                            <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" for="liability">I have read and agree with the above</label>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>

        </div>
    </div>
    </div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_10" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_10" class="next action-button" value="Continue" />
</fieldset>

<!--//11//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
        <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">
                <div class="card border p-4">
                    <h4 class="font-weight-bold">Unauthorized Driver Declaration</h4>
                    <p class="mt-3">
                        Any vehicle hired under this hire agreement may only be driven by authorised drivers, who have been approved the lessor.

                        I understand that should I breach these terms an additional rental charge will be levied. (This extra charge will not offer any insurance cover, and the hirer & driver will remain responsible for any losses incurred by the lessor or any third party.

                        In the event of an accident, please contact and report to the Lessor within 2 hours.
                    </p>

                    <div class="border border-dashed rounded-lg p-2 bg-blue-50 mt-3">
                        <div class="form-group custom-control custom-checkbox m-0" data-aire-component="group">
                            <input type="checkbox" name="driver_declare" value="1" class="custom-control-input step_11" data-aire-component="checkbox" wire:model="confirmed" id="driver_declare" required="">

                            <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" for="driver_declare">
                                I have read and agree with the above
                            </label>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>

        </div>
    </div>
    </div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_11" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_11" class="next action-button" value="Continue" />
</fieldset>

<!--//12//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">
                <div class="card border p-4">
                    <h4 class="font-weight-bold">Terms and Conditions</h4>
                    <p class="mt-3">
                        Rental Contract Terms & Conditions

                        I confirm that I the owner/hirer agree to the following Rental Agreement Conditions stated below:

                        Drivaar charge the rental of the vehicle at a daily rate of;

                        · Short Wheelbase Vehicles Short-term = £34.50
                        · Short Wheelbase Vehicles Long-term = £30.70
                        · Long Wheelbase Vehicle Short-term = £39.50
                        · Long Wheelbase Vehicle Long-term = £33.58

                        Under this agreement, I will be liable for all parking and traffic violations from the date and time section, from the start of this agreement until the return date and time.

                        I agree that there will be no other person(s) other than myself utilizing the vehicle under this agreement as sole responsibility will fall on me for any damages, traffic violations, and accidents incurred.

                        I will be responsible for making sure the vehicle is always roadworthy including regular maintenance and checks but not limited to; the tires, thread, locking system, and lights. I must inform members of the 
                        Drivaar management team of any issues with the vehicle as soon as an issue is found.

                        Under this agreement, I will be liable for any damage caused to the vehicle, but not limited to punctures, damage to tires, wheel rims, and windscreens. I will be responsible for using the correct fuel to ensure the vehicle functions are not compromised and maintain a roadworthy status.
                        I agree that any incidents involving myself regardless of the fault will be raised and reported to Drivaar Management within 12 hours from the time of the incident. An unreported accident insurance fee of up to £500 will be applied if any accidents are not reported within 12hours. It will be my sole responsibility to provide a detailed report of the incident. Immediate action must be taken if me (the Hirer) and/or the vehicle is stolen or is involved in any accidents.

                        A full report is required with a rough plan of the scene of the incident showing the position of the vehicles involved with measurements.
                        · The names and addresses of the person(s) involved in the accident should be taken.
                        · The name and address of any witnesses should be taken.
                        · Pictures of the incident must be taken showing the full extent of the damage/collision.
                        · Take the name and address of the third party's Insurance Company.
                        · I must assist 
                        Drivaar with any information in dealing with any claims or ongoing disputes that involves me directly.
                        · Under this rental agreement I will be subject to a standard excess of £2500.00, this excess is not applied if you are involved in a clear non-fault accident with another insured motor vehicle.

                        A deposit of £500.00 will be held by Drivaarto ensure the following points stated above are met.

                        By signing you agree:

                        To pay the standard policy excess should the hire vehicle be returned with any damage, other than the damage detailed in this checklist.

                        For any fuel supplied on this vehicle must be returned the same, any missing fuel will be charged £2.50 per liter
                    </p>

                    <div class="border border-dashed rounded-lg p-2 bg-blue-50 mt-3">
                        <div class="form-group custom-control custom-checkbox m-0" data-aire-component="group">
                            <input type="checkbox" name="terms_conditions" value="1" class="custom-control-input step_12" data-aire-component="checkbox" wire:model="confirmed" id="terms_conditions" required="">

                            <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" for="terms_conditions">
                                I have read and agree with the above
                            </label>
                        </div>
                    </div>
                </div>

                <br><br>
            </div>

        </div>
    </div>
    </div>
</div>
    <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_12" class="previous action-button" value="Go Back" />
    <input type="button" name="next" id="next-step_12" class="next action-button" value="Continue" />
</fieldset>

<!--//13//-->
<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <div class="row">
        <div class="col-md-12">
    <div class="form-body">
        <div class="form-row mt-4">
            <div class="col-md-12">
                <div class="card border p-4">
                    <h4 class="font-weight-bold">Complete Hire Agreement</h4>
                    <div class="row">
                        <div class="col-md-5">

                        </div>
                        <div class="col-md-2 pl-4">
                            <svg class="icon mt-5" fill="#fb9678" style="height: 50px; width: 50px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path>
                            </svg>
                        </div>
                        <div class="col-md-5">

                        </div>
                    </div>
                    <div class="text-center">
                        <h5 class="mt-3 font-weight-bold">Start Agreement?</h5>
                        <p>By clicking finish you confirm that all the information entered is complete and cannot be amended.</p>

                    </div>
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4 pl-5 mt-3">
                            <!-- <button class="btn btn-primary action-button step_13" type="submit" name="iscompalete" value="1" id="next-step_13">Start Now</button> -->
                                <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_13" class="previous action-button" value="Go Back" />
                            <input type="button" name="next" id="next-step_13" class="next action-button" value="Start Now" />
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>

                <br><br>
            </div>

        </div>
    </div>
    </div>
</div>
</fieldset>

<fieldset style="margin: 0px;width: fit-content;margin: auto;">
    <h5>Thank You!!!</h5>
    <p>You have now provided all necessary information and uploaded<br> copies of your documents(ID/Passport, Driver Licence, Bank<br> Statement, Visa).<br><br>
        <!-- when you press <b>FINISH</b> we will sent you few Forms for background<br> check and your Service Contract that you need to sign via e-mail. <br><br>
        This will be the <u>last</u> step in the Onboarding Process.<br><br>
        Thank you for your time.  -->
        <!-- <img src="data:image/jpeg;base64,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" alt="" width="30px" style="background-color: white;"> -->

    </p>
    <!-- <input type="button" name="previous" class="previous action-button" value="Go Back" /> -->
    <input type="submit" name="" class="next action-button" value="Finish" />
    <!-- <button type="submit" class="next action-button"> Finish</button> -->
</fieldset>
                                                </form>

                                            </div>
                                            <div class="col-md-12" id="ViewFormDiv">

                                        <div class="row">
                                            <div class="col-md-6" style="margin-top: 15px;">
                                                <div class="" style="margin-bottom: 15px;">
                                                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Advanced Search
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="collapse col-md-12" id="collapseExample">
                                                <br><br>
                                                <div class="card card-body border border-primary rounded">
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group has-primary">
                                                                        <select class="select form-control custom-select" id="hirer" name="hirer" onchange="loadtable();">
                                                                            <option value="%">All Hirer Name</option>
                                                                            <?php
                                                                            $mysql = new Mysql();
                                                                            $mysql->dbConnect();
                                                                            $cquery = "SELECT DISTINCT c.*
                                                                        FROM `tbl_contractor` c 
                                                                        INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                        WHERE c.`depot` IN (w.depot_id) AND c.`isdelete`=0 AND c.`isactive`=0 AND c.`iscompalete`=1";
                                                                            $crow =  $mysql->selectFreeRun($cquery);
                                                                            while ($cresult = mysqli_fetch_array($crow)) {
                                                                            ?>
                                                                                <option value="<?php echo $cresult['id'] ?>"><?php echo $cresult['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            //$mysql -> dbDisconnect();
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group has-primary">
                                                                        <select class="select form-control custom-select" id="statusid" name="statusid" onchange="loadtable();">
                                                                            <option value="%">All Status</option>
                                                                            <option value="0">Active</option>
                                                                            <option value="1">Inactive</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group has-primary">
                                                                        <select class="select form-control custom-select" id="locationname" name="locationname" onchange="loadtable();">
                                                                            <option value="%">All Depot Name</option>
                                                                            <?php
                                                                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
                                                                            $strow =  $mysql->selectFreeRun($statusquery);
                                                                            while ($statusresult = mysqli_fetch_array($strow)) {
                                                                            ?>
                                                                                <option value="<?php echo $statusresult['id'] ?>"><?php echo $statusresult['name'] ?></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                            <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th>Hirer</th>
                                                        <th>Vehicle</th>
                                                        <th>Location</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                            </table>
                                            <br>
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
                <form method="post" id="addimgform" name="addimgform" action="" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="" class="form-control">
                    <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">
                    <input type="hidden" name="vehicleid" id="vehicleid" value="">
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
                        <div class="switch-toggle switch-3 switch-candy switch" id="switch">

                            <input id="low_1" name="damage_type" class=" swt" type="radio" value="1" checked="checked">
                            <label for="low_1" onclick="" style="color: green;">Light</label>
                            <input id="medium_1" name="damage_type" class=" swt" type="radio" value="2">
                            <label for="medium_1" onclick="" style="color: yellow;">Moderate</label>
                            <input id="high_1" class=" swt" name="damage_type" type="radio" value="3">
                            <label for="high_1" onclick="" style="color: red;">Severe</label>
                            <a></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Condition:</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="condition-1" name="conditionid" class="custom-control-input" value="1" checked="true">
                                    <label class="custom-control-label" for="condition-1">Broken</label>
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
                </form>
            </div>
        </div>
    </div>
</div>
<div id="release" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255 236 230);">
                <h4 class="modal-title">Release incomplete agreements</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <select id="depotListModel" name="depotListModel" class="form-control selectpicker step_0" data-live-search="true" onchange="getDepotAgrement(this);">
                        <?php
                        echo $dptOption;
                        ?>
                    </select>
                        
                    </div>
                    <div class="col-md-12">
                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                     <table id="releaseTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example3_info" style="width: 100%;">
                            <thead class="default">
                                <tr class="">
                                    <th>#</th>
                                    <th>Contractor</th>
                                    <th>Veh Reg No</th>
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
    </div>
</div>
            </div>
        </div>


        <?php include('footer.php'); ?>

    </div>

    <?php include('footerScript.php'); ?>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.0/jquery.maphilight.min.js"></script>
    <script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>
    <script>
        
        Dropzone.autoDiscover = false;
        $(document).ready(function() {
            var signaturePad = new SignaturePad(document.getElementById('signature-pad'));

            $('#click').click(function(){
                var data = signaturePad.toDataURL('image/png');
                $('#signature64').val(data);
                $("#sign_prev").show();
                $("#sign_prev").attr("src",data);
                $("#next-step_5").show();
            });
            $('#clear').click(function(){
                signaturePad.clear();
                $("#sign_prev").hide();
                $("#sign_prev").attr("src","");
                $("#next-step_5").hide();
            });

            var signaturePad2 = new SignaturePad(document.getElementById('signature-pad2'));

            $('#click2').click(function(){
                var data2 = signaturePad2.toDataURL('image/png');
                $('#signature642').val(data2);
                $("#sign_prev2").show();
                $("#sign_prev2").attr("src",data2);
                $("#next-step_6").show();
            });
            $('#clear2').click(function(){
                signaturePad2.clear();
                $("#sign_prev2").hide();
                $("#sign_prev2").attr("src","");
                $("#next-step_6").hide();
            });

            $('#priceper_day').keypress(function(event) {
                alert("hi");
                  event.preventDefault();
                 return false;
             });
            ajaxcall();

            $("#AddFormDiv,#AddDiv").hide();
            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata1.php',
                    // 'data': {
                    //     'action': 'loadrentalagrementtabledata'

                    // }
                    'data': function(d) {
                        d.action = 'loadrentalagrementtabledata';
                        d.hirer = $('#hirer').val();
                        d.statusid = $('#statusid').val();
                        d.locationname = $('#locationname').val();
                    }
                },
                'columns': [

                    {
                        data: 'hirer'
                    },
                    {
                        data: 'vehicle_reg_no'
                    },
                    {
                        data: 'location'
                    },
                    {
                        data: 'pickup_date'
                    },
                    {
                        data: 'return_date'
                    },
                    {
                        data: 'price_per_day'
                    },
                    {
                        data: 'status'
                    },
                    // { data: 'action'}
                ]
                
            });

            $('#releaseTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata1.php',
                    'data': function(d) {
                        d.action = 'loadMissedAgreement';
                        d.depL = $('#depotListModel').val();
                    }
                },
                'columns': [
                    {
                        data: 'srn'
                    },
                    {
                        data: 'contractor'
                    },
                    {
                        data: 'fleet'
                    },
                    {
                        data: 'action'
                    }
                ]
            });

            $("#next-step_1").on('click', function() {
                $('.map').maphilight({
                    fill: true,
                    fillColor: '03a9f3',
                    fillOpacity: 0.4,
                    stroke: false
                });
                var vid = $('#vehicle').val();
                $("#vehicleid").val(vid);
                var uid = <?php echo $userid; ?>;
                $.ajax({
                    type: "POST",
                    url: "loaddata.php",
                    data: {
                        action: 'vehicledata',
                        vid: vid,
                        uid: uid
                    },
                    dataType: 'json',
                    success: function(data) {
                        ajaxcall();
                        if(data.status==1)
                        {
                            result_data = data.statusdata;
                            $("#veh_reg").text(result_data['registration_number']);
                            $("#veh_make").text(result_data['makename']);
                            $("#veh_model").text(result_data['modelname']);
                            $("#mapImageDiv").parent().css('background-size','');
                            // $("#mapImageDiv").parent().css('width','475px');
                            // $("#mapImageDiv").parent().css('height","715px');
                        }
                    }
                });
            });

            var myDropzone = new Dropzone("div#addimg1", {
                url: "damage_imgupload.php",
                paramName: "file",
                maxFilesize: 5,
                maxFiles: 10,
                // autoProcessQueue: false,
                // success:function(data) {
                //     // imgname.push(data.name);
                // }
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
            $("#addimgform").on('submit', function(e) {
                e.preventDefault();
                //myDropzone.processQueue();

                for (i = 0; i < myDropzone.files.length; i++) {
                    $("#image_name").val(myDropzone.files[i].name);
                    $.ajax({
                        type: 'POST',
                        url: 'damage_img.php',
                        data: new FormData(this),
                        contentType: false,
                        dataType: 'json',
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#addimgform')[0].reset();
                                $('#addimg').modal('hide');
                                myDropzone.removeAllFiles(true);
                                ajaxcall();
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }

                        }
                    });
                }
            });
        });

        $(function() {
            $('#type input[type=radio]').change(function() {
                var insurance_type = $(this).val();
                if (insurance_type == 1) {
                    $("#insurance_1").show();
                    $("#insurance_0").hide();
                } else {
                    $("#insurance_0").show();
                    $("#insurance_1").hide();
                }
            })
        })
        function loadtable()
        {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
        }
        function ajaxcall() {
            var id = $('#vehicle').val();
            var userid = <?php echo $userid; ?>;
            $.ajax({
                type: 'POST',
                url: 'loaddata.php',
                data: 'userid=' + userid + '&id=' + id + '&action=vehicleDamagedata',
                success: function(data) {
                    //console.log(data.div);
                    var tabledata = data.data;
                    //$("#damagetbl tbody").append(tabledata);
                    //$("#damagetbl").DataTable();
                    $("#vehicledetails").append(data.div);
                    $('.delete').remove();
                    // console.log(data.part);
                    var obj1 = JSON.parse(JSON.stringify(data.part));
                    var i = 0;
                    var j = obj1.length;
                    for (i = 0; i < j; i++) {
                        // console.log(data.type[i]);
                        var fillcolor;
                        var c = data.type[i];
                        var c1 = data.part[i];
                        var c2 = data.state[i];
                        if (c2 == 0) {
                            if (c == 1) {
                                fillcolor = '00c292';
                            } else if (c == 2) {
                                fillcolor = 'fec107';
                            } else if (c == 3) {
                                fillcolor = 'e46a76';
                            }
                            $("area[title='" + c1 + "']").each(function() {
                                var data = $(this).data('maphilight') || {};
                                data.alwaysOn = true;
                                data.fillColor = fillcolor;
                                data.fill = true;
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        } else {
                            $("area[title='" + c1 + "']").each(function() {
                                var data = $(this).data('maphilight') || {};
                                data.alwaysOn = false;
                                $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                            });
                        }

                    }

                }
            });
        }

        function ShowHideDiv(divValue) {
            if (divValue == 'view') {

                $('#msform_0')[0].reset();
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

        function areamodel(obj) {
            $('#addimg').modal('show');
            var damage_part = obj.title;
            $("#damage_part").val(damage_part);
        }
        function getDepotAgrement(dpt)
        {
            var table = $('#releaseTable').DataTable();
            table.ajax.reload();
        }
        function releaseID(uid)
        {
            $('#release').modal('show');
            var table = $('#releaseTable').DataTable();
            table.ajax.reload();
        }
        var loadFile = function(event, flg) {
            var output = document.getElementById(flg + '');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };


        function padZero(n)
        {
            if(n<10)
                n='0'+n;
            return n;
        }
        function dateMinMax()
        {
            var today = new Date();
            var dd,mm,yyyy,min,max;
            var termSize = document.getElementById("termSize").options[document.getElementById("termSize").selectedIndex].text;
            if(termSize=="Short Term")
            {
                today.setDate(today.getDate() - 49);
                // today.setDate(today.getDate() - 7);
                dd = today.getDate();
                mm = today.getMonth()+1; 
                yyyy = today.getFullYear();
                min = yyyy+'-'+padZero(mm)+'-'+padZero(dd);
                today.setDate(today.getDate() + 56);
                // today.setDate(today.getDate() + 14);
                dd = today.getDate();
                mm = today.getMonth()+1; 
                yyyy = today.getFullYear();
                max = yyyy+'-'+padZero(mm)+'-'+padZero(dd);
                $("#pickup_date").attr("min",min);
                $("#pickup_date").attr("max",max);
                $("#return_date").attr("min",min);
                $("#return_date").attr("max",max);
            }else if(termSize == "Long Term")
            {
                //today.setDate(today.getDate() - 49);
                today.setDate(today.getDate() - 7);
                dd = today.getDate();
                mm = today.getMonth()+1; 
                yyyy = today.getFullYear();
                min = yyyy+'-'+padZero(mm)+'-'+padZero(dd);
                //today.setDate(today.getDate() + 77);
                today.setDate(today.getDate() + 35);
                dd = today.getDate();
                mm = today.getMonth()+6; 
                yyyy = today.getFullYear();
                max = yyyy+'-'+padZero(mm)+'-'+padZero(dd);
                $("#pickup_date").attr("min",min);
                $("#pickup_date").attr("max",max);
                $("#return_date").attr("min",min);
                $("#return_date").attr("max",max);
            }
        }
        function getPricePerDay()
        {
            var val = $("#vehicle").val();
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'vehicle_details',
                    vid: val
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status==1)
                    {
                        $("#termSize").html(data.dt); 
                        $("#priceper_day").val($("#termSize").val());
                        dateMinMax();
                    }else{
                        myAlert("Data Error@#@No price decided for selected vehicle.@#@danger");
                    }
                }
            });
        }

        $("#vehicle").change(function() {
            getPricePerDay();
        });
        function chnagePerDayPrice(ths)
        {
            $("#priceper_day").val($(ths).val());
            dateMinMax();
        }
        function setMinReturnDate(ths)
        {
             $("#return_date").removeAttr("disabled");
             $("#return_date").attr("min",$("#pickup_date").val());
        }

        function getDriverList(ths)
        {
            var dDpId  = $(ths).val();
            $.ajax({
                type: 'POST',
                url: 'loadDataExtended1.php',
                data: {
                    action: 'dptDriverList',
                    did: dDpId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $("#driver").html(data.dt);
                        $('.selectpicker').selectpicker('refresh');

                    } else {
                        $("#driver").html("");
                        myAlert("Data Error@#@No active or free driver found from the selected Depot@#@danger");
                    }

                }
            });
        }
        function deleteMissedAgreement(agid)
        {
            $.ajax({
                type : 'POST',
                url : 'loadDataExtended1.php',
                data : {
                    action : 'deleteMissingAgreement',
                    agid : agid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) 
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        getDepotAgrement(1);
                        
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                    }

                }
            });
        }
        $("#release").on('hidden.bs.modal', function(){
            location.reload();
        });
        function checkAvailVeh()
        {
            var pdt = $("#pickup_date").val();
            var rdt = $("#return_date").val();
            var dpt = $("#depotList").val();
            $.ajax({
                type : 'POST',
                url : 'loadDataExtended1.php',
                data : {
                    action : 'checkAvailableVeh',
                    pdt : pdt,
                    rdt : rdt,
                    dpt : dpt
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) 
                    {
                        $("#vehicle").removeAttr("disabled");
                        $("#priceper_day").removeAttr("disabled");
                        document.getElementById('vehicle').innerHTML = data.dt;
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                    }

                }
            });
        }
        function disableFirld()
        {
            $("#vehicle").attr("disabled","disabled");
            $("#priceper_day").attr("disabled","disabled");
        }
        function deleteAgreement(agid)
        {
        	var htm = "<h3>Are you sure, You want to delete the agreement???</h3>";
        	swal({ 
                title: "Alert !", 
                text: "", 
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, Delete Now!",   
                closeOnConfirm: false,
                html: htm,
            }).then((result) => {
                if(result.value)
                {
                    $.ajax({
	                type: 'POST',
	                url: 'loadDataExtended1.php',
	                data: {
	                    action: 'deleteAgreement',
	                    agid: agid
	                },
	                dataType: 'json',
	                success: function(data) {
	                    if (data.status == 1) {
	                    	var table = $('#myTable').DataTable();
	            			table.ajax.reload();
	                        myAlert("Agreement Deleted@#@Agreement has been deleted succeccfuly!!@#@success");
	                    } else {
	                        $("#driver").html("");
	                        myAlert("Data Error@#@No active or free driver found from the selected Depot@#@danger");
	                    }

	                }
	            });
                }
            });
        }
    </script>
    <script src="rentalscript.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="signature_pad-master/js/signature_pad.js"></script>
</body>

</html>