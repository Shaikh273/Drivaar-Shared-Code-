<?php
include 'DB/config.php';

    $page_title = "Rental Agreement";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
        
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid=$_SESSION['userid'];
    }else
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
                               
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title><?php echo $page_title; ?></title>
    <meta name="viewport" content="width=1024">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
    .kbw-signature { width: 400px; height: 200px;}
        #sig canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
   
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>

<div id="main-wrapper">
<?php include('header.php'); ?>

    <div class="page-wrapper">
        <div class="container-fluid">
            
            <main class="container-fluid  animated">
                <div class="card">    
                
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Rental Aggreemnet</div>
                            <div> 
                    
                                <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Rental Agreement</button>
                    
                                <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Rental Agreement</button>
                    
                            </div>                   
                        </div>
                    </div>
                    
                    <div class="card-body">
                      <?php //include('vehicle_setting.php'); ?>
                      
                      <div class="row">
                        <div class="card col-md-12" style="border: 1px solid #d1d5db; height: 1200px;">   
                            <div class="card-header" style="background-color: #fff;">
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="header"></div>
                    
                                   
                                </div>
                            </div>
                            
                            <div class="card-body" id="ViewFormDiv">
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
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            
                              <div class="card-body" id="AddFormDiv">
                                  <div class="row">
                                    <div class="col-md-12">
                                
                                    <form id="msform_0" method="post" class="msform" name="msform" action="" enctype="multipart/form-data">
                                        <input type="hidden" id="userid" name="userid" value="<?php echo $userid?>">
                                        <input type="hidden" name="last_insert_id" id="last_insert_id" class="" value="">
                                        <br>
                                        <div class="container chkrequire" id="chkrequire"></div>
                                        <br>
                                        

                                        <!--//0//-->
                                        <fieldset style="margin: 0 10%;"> 
                                          <div class="form-body">
                                              <div class="form-row">
                                                <div class="col-md-12">
                                                  <br><br>    
                                                  <label >Select Driver *</label>
                                                  <select id="driver" name="driver" class="form-control mb-2 step_0">
                                                    <?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                        $expquery = "SELECT * FROM `tbl_contractor` WHERE  `user_id`=$userid AND `isdelete`= 0 AND `isactive`= 0 ORDER BY id ASC";
                                                        $exprow =  $mysql -> selectFreeRun($expquery);
                                                        while($result = mysqli_fetch_array($exprow))
                                                        {
                                                     ?>
                                                    <option value="<?php echo $result['id'];?>"><?php echo $result['name'];?></option>
                                                    <?php
                                                      }
                                                    $mysql -> dbDisconnect();
                                                  ?>
                                                  </select>
                                                  <br><br><br>
                                                </div> 
                                                
                                            </div> 
                                            </div>
                                          <button type="button" name="next" id="next-step_0"  class="next action-button step_0"> Continue </button>
                                        </fieldset>
                                        
                                        <!--//1//-->
                                        <fieldset style="width: 100%;">
                                            <div class="form-body" id="SomeDiv">
                                               
                                                <label class="font-weight-bold mt-2">Vehicle & Rental Period</label>
                                                <br> 
                                                    <div class="col-md-12 border p-3 rounded-lg mt-2">
                                                        <div class="form-row text-left mt-2">
                                                            <div class="form-group col-md-6">
                                                                <label>Vehicle *</label>
                                         
                                                                <select id="vehicle" name="vehicle_id" class="form-control step_1">
                                                                     <?php
                                                                        $mysql = new Mysql();
                                                                        $mysql -> dbConnect();
                                                                        $expquery = "SELECT * FROM `tbl_vehicles` WHERE `user_id`=$userid AND `isdelete`= 0 ORDER BY id ASC";
                                                                        $exprow =  $mysql -> selectFreeRun($expquery);
                                                                        while($result = mysqli_fetch_array($exprow))
                                                                        {
                                                                            $sid = $result['supplier_id'];
                                                                            $expquery1 = "SELECT * FROM `tbl_vehiclesupplier` WHERE `isdelete`= 0 AND `isactive`= 0 AND id=$sid ORDER BY id ASC";
                                                                            $exprow1 =  $mysql -> selectFreeRun($expquery1);
                                                                            $result1 = mysqli_fetch_array($exprow1);    
                                                                     ?>
                                                                    <option value="<?php echo $result['id'];?>"><?php echo $result['registration_number']. " " .$result1['name'];?></option>
                                                                    <?php
                                                                       }
                                                                    $mysql -> dbDisconnect();
                                                                  ?>
                                                                </select>
                                                
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label class="control-label">Price Per Day *</label>
                                                                <input type="text" class="form-control step_1" id="priceper_day" value="" name="price_per_day" placeholder="" required="">
                                                            </div>
                                                        </div>
                                         
                                                        <div class="form-row text-left">
                                          
                                                            <div class="form-group col-md-6 mb-4">
                                                                <label class="control-label">Pick Up Date</label>
                                                                <input id="pickup_date" type="datetime-local" name="pickup_date" placeholder="<?php echo date('d-m-Y H:i'); ?>" value="<?php echo date('d-m-Y H:i'); ?>"  class="form-control bg-white border-left-0 border-md step_1" required="">
                                                            </div>
                                                         
                                                            <div class="form-group col-md-6 mb-4">
                                                                <label>Return Date</label> 
                                                                <input id="return_date" type="datetime-local" name="return_date" placeholder="<?php echo date("Y-m-d H:i:s"); ?>" value="<?php echo date('d-m-Y H:i'); ?>" class="form-control bg-white border-left-0 border-md step_1" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                            </div>        
                                         <br>
                                            <input type="button" name="previous" id="pre-step_1" class="previous action-button" value="Go Back" style="float: left;"/>
                                            <input type="button" name="next" id="next-step_1" class="next action-button ml-3"  value="Continue" style="float: left;"/>           
                                           
                                        </fieldset>
                                        
                                        <!--//2//-->
                                        <fieldset class="col-md-12 offset-md-2"> 
                                            <label class="font-weight-bold">Damage Report</label>
                                             <div class="card" style="border: 1px solid black">
                                                <div class="card-body">
                                                    <label class="font-weight-bold">Select Damages</label>
                                                    <div class="card" style="border: 1px solid black">
                                                      <div class="card-header font-weight-bold" style="background-color: #f9cbbe;">
                                                         Vehicle Details
                                                      </div>
                                                      <div class="p-3">
                                                        <div class="col-md-12">
                                                            <label class="font-weight-bold">Vehicle Reg : </label>
                                                            <input type="" id="vehicle_reg_no" name="vehicle_reg_no" class="p-1 rounded-lg col-md-2 text-center text-white font-weight-bold step_2" value="" style="background-color: #fb9678;" required="">
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="font-weight-bold">Make :	</label>
                                                            <label name="makeid" id="makeid" class=""></label>
                                                        </div>
                                                        <div class="col-md-12">    
                                                            <label class="font-weight-bold">Model :	</label>
                                                            <label type="" name="modelid" id="modelid" class=""></label>
                                                        </div>    
                                                      </div>    
                                                    </div>
                                                </div>
                                              </div>
                                                 
                                            <br>
                                            
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_step_2" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_2" class="next action-button"  value="Continue" />           
                                        </fieldset>

                                        <!--//3//-->
                                        <fieldset style="width: 100%;"> 
                                         
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
                                                            <input type="text" class="form-control step_3"  name="front_left_type" id="front_left_type" required="">
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
                                                       
                                                <br>
                                                
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_3" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_3" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                        <!--//4//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                          <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-8">
                                                    <input type="file" class="form-control p-1" name="image" id="image" multiple="multiple" required="">
                                                    </div>
                                              </div> 
                                            </div>
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_4" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_4" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                        <!--//5//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                          <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-12">
                                                 
                                                    <label class="font-weight-bold">Damage Report</label>
                                                    <div class="card" style="border: 1px solid #b5b5b4;">
                                                        <div class="card-body">
                                                            <input type="hidden" name="status" id="status" value="">
                                                            <button type="button" name="signcanvas" id="signcanvas" value="" class="action-button step_5" onclick="sign(this.value);">Signature</button>
                                                            <script>
                                                            var timer;
                                                            var cont = 1;
                                                                function sign(val){
                                                                    
                                                                    var id = val;
                                                                    alert(id);
                                                                    newWindow = window.open ("https://karavelo.abuzer.online/drivaarr/home/signature.php?id="+id+"","mywindow","menubar=1,resizable=1,width=500,height=500");
                                                                    timer = setTimeout(function(){autoload();},3000);
                                                                }
                                                                function autoload(){
                                                                    if(cont==10)
                                                                    {
                                                                        clearTimeout(timer);
                                                                        newWindow.close();
                                                                        myAlert("SESSION TIMEOUT!!!@#@Session timeout for signature. Please click again on Upload Signature to try again.@#@danger");
                                                                    }else
                                                                    {
                                                                    var status_id = $('#signcanvas').val();
                                                                    $.ajax({
                                                                        type : 'POST',
                                                                        url: 'loaddata.php', 
                                                                        data : {
                                                                            'action':'rentalaggreementsignature_validation',
                                                                            'status_id': status_id,
                                                                        },
                                                                        success: function(data) {
                                                                            if(data.status==1)
                                                                            {
                                                                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                                                                newWindow.close();
                                                                                $('#status').val(data.status);
                                                                                clearTimeout(timer);
                                                                            }else
                                                                            {
                                                                                timer = setTimeout(function(){autoload();},3000);
                                                                            }
                                                                        }
                                                                    });
                                                                    cont++;
                                                                    }
                                                                }
                                                                
                                                            </script>
                                                        </div>
                                                      
                                                       </div>
                                                    </div>  
                                                       
                                            </div> 
                                            </div>
                                            <br/><br/>
                                            <!--<div class="col-md-12">-->
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_5" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_5" class="next action-button"  value="Continue" />           
                                            <!--</div>-->
                                        </fieldset>
                                        
                                         <!--//6//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                          <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-12">
                                                  
                                                    <label class="font-weight-bold">Damage Report</label>
                                                    <div class="card" style="border: 1px solid #b5b5b4;">
                                                        <div class="card-body">
                                                            <label class="font-weight-bold">Lessor Signature</label>   
                                                            <div class="container p-3 mt-3" style="border: 1px solid #b5b5b4;">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <input type="hidden" name="status2" id="status2" value="">
                                                                        <!--<input type="hidden" name="status" id="status" value="">-->
                                                            <button type="button" name="signcanvas" id="signcanvas1" value="" class="action-button step_6" onclick="sign1(this.value);">Signature</button>
                                                            <script>
                                                            var timer;
                                                            var cont = 1;
                                                                function sign1(val){
                                                                    var id = val;
                                                                    newWindow = window.open ("https://karavelo.abuzer.online/drivaarr/home/signature.php?sid="+id+"","mywindow","menubar=1,resizable=1,width=500,height=500");
                                                                    timer = setTimeout(function(){autoload1();},3000);
                                                                }
                                                                function autoload1(){
                                                                    if(cont==10)
                                                                    {
                                                                        clearTimeout(timer);
                                                                        newWindow.close();
                                                                        myAlert("SESSION TIMEOUT!!!@#@Session timeout for signature. Please click again on Upload Signature to try again.@#@danger");
                                                                    }else
                                                                    {
                                                                    var status_id = $('#signcanvas1').val();
                                                                    $.ajax({
                                                                        type : 'POST',
                                                                        url: 'loaddata.php', 
                                                                        data : {
                                                                        'action':'rentalaggreementsignature2_validation',
                                                                        'status_id': status_id,
                                                          
                                                                        },
                                                                        success: function(data) {
                                                                            if(data.status==1)
                                                                            {
                                                                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                                                                newWindow.close();
                                                                                $('#status2').val(data.status);
                                                                                clearTimeout(timer);
                                                                            }else
                                                                            {
                                                                                timer = setTimeout(function(){autoload();},3000);
                                                                            }
                                                                        }
                                                                    });
                                                                    cont++;
                                                                    }
                                                                }
                                                                
                                                            </script>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>  
                                                  
                                                     </div> 
                                                </div>
                                            </div>
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_6" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_6" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                       
                                       <!--//7//-->
                                        <fieldset  style="margin: 0 10%;"> 
                                        
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
                                                                <svg class="icon mr-2 d-inline" style="height: 10px; width: 10px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>
                                                                <span class="d-inline">Vehicle deposit for the vehicle hire</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                   </div>
                                                </div>
                                                     
                                                <br><br>    
                                              </div>
                                            </div>
                                                
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_7" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_7" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                       
                                        <!--//8//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;" id="type"> 
                                            <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-12 border rounded-lg p-3">
                                                    <span class="pt-3 font-weight-bold">Please select one of the following:</span><br><br>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        <label>Bryanston Logistics Limited's Insurance</label>
                                                        <input type="radio" id="Bryanston" style="height:15px; width:15px; " name="ins_cmp_type" class="step_8 check" value="0" required="">
                                                        <label style="margin-right: 10px;">Hirer's Insurance</label>
                                                        <input type="radio" id="Hirer" style="height:15px; width:15px; " name="ins_cmp_type" class="step_8 check" value="1" required="">
                                                    </div>
                                                    
                                                </div> 
                                             
                                                </div> 
                                              </div>
                                            </div>
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_8" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_8" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                        <!--//9//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                        <input type="hidden" name="" id="insurance" value="">
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
                                                                Bryanston Logistics Limited's Insurance
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
                                        
    
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_9" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_9" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                        <!--//10//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
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
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_10" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_10" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                       
                                        <!--//11//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
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
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_11" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_11" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                             <!--//12//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                          <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-12">
                                                    <div class="card border p-4">
                                                        <h4 class="font-weight-bold">Terms and Conditions</h4>
                                                        <p class="mt-3">
                                                            Rental Contract Terms & Conditions
                  
                                                            I confirm that I the owner/hirer agree to the following Rental Agreement Conditions stated below:
                                                             
                                                            Bryanston Logistics Limited charge the rental of the vehicle at a daily rate of; 
                                                             
                                                            ·        Short Wheelbase Vehicles Short-term = £34.50
                                                             ·        Short Wheelbase Vehicles Long-term = £30.70 
                                                            ·        Long Wheelbase Vehicle Short-term = £39.50
                                                            ·        Long Wheelbase Vehicle Long-term = £33.58
                                                             
                                                            Under this agreement, I will be liable for all parking and traffic violations from the date and time section, from the start of this agreement until the return date and time.
                                                             
                                                            I agree that there will be no other person(s) other than myself utilizing the vehicle under this agreement as sole responsibility will fall on me for any damages, traffic violations, and accidents incurred.
                                                             
                                                            I will be responsible for making sure the vehicle is always roadworthy including regular maintenance and checks but not limited to; the tires, thread, locking system, and lights. I must inform members of the Bryanston Logistics management team of any issues with the vehicle as soon as an issue is found.
                                                             
                                                            Under this agreement, I will be liable for any damage caused to the vehicle, but not limited to punctures, damage to tires, wheel rims, and windscreens. I will be responsible for using the correct fuel to ensure the vehicle functions are not compromised and maintain a roadworthy status.
                                                            I agree that any incidents involving myself regardless of the fault will be raised and reported to Bryanston Management within 12 hours from the time of the incident. An unreported accident insurance fee of up to £500 will be applied if any accidents are not reported within 12hours. It will be my sole responsibility to provide a detailed report of the incident. Immediate action must be taken if me (the Hirer) and/or the vehicle is stolen or is involved in any accidents.
                                                             
                                                            A full report is required with a rough plan of the scene of the incident showing the position of the vehicles involved with measurements.
                                                            ·        The names and addresses of the person(s) involved in the accident should be taken.
                                                            ·        The name and address of any witnesses should be taken.
                                                            ·        Pictures of the incident must be taken showing the full extent of the damage/collision.
                                                            ·        Take the name and address of the third party's Insurance Company.
                                                            ·        I must assist Bryanston Logistics with any information in dealing with any claims or ongoing disputes that involves me directly.
                                                            ·        Under this rental agreement I will be subject to a standard excess of £2500.00, this excess is not applied if you are involved in a clear non-fault accident with another insured motor vehicle.
                                                             
                                                            A deposit of £500.00 will be held by Bryanston Logistics to ensure the following points stated above are met.
                                                             
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
                                            <input type="button" style="margin-left: -10px;" name="previous" id="pre-step_12" class="previous action-button" value="Go Back"/>
                                            <input type="button" name="next" id="next-step_12" class="next action-button"  value="Continue" />           
                                        </fieldset>
                                        
                                        <!--//13//-->
                                        <fieldset  style="margin: 0 10%; width: 100%;"> 
                                          <div class="form-body">
                                              <div class="form-row mt-4">
                                                <div class="col-md-12">
                                                    <div class="card border p-4">
                                                        <h4 class="font-weight-bold">Complete Hire Agreement</h4>
                                                        <div class="row">
                                                            <div class="col-md-5">
                                                                
                                                            </div>
                                                            <div class="col-md-6 pl-4">
                                                                <svg class="icon mt-5" fill="#fb9678" style="height: 50px; width: 50px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"></path></svg>
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                        <h5 class="mt-3 font-weight-bold">Start Agreement?</h5>
                                                        <p>By clicking finish you confirm that all the information entered is complete and cannot be amended.</p>
                                                        
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4"></div>
                                                            <div class="col-md-4 pl-5 mt-3">
                                                                <button class="btn btn-primary action-button step_13" type="submit" name="iscompalete" value="1" id="next-step_13">Complete</button>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                     
                                                  <br><br>    
                                                </div> 
                                                
                                            </div> 
                                            </div>
                                            <!--<input type="button" style="margin-left: -10px;" name="previous" id="pre-step_<?php //echo $i;?>" class="previous action-button" value="Go Back"/>-->
                                            <!--<input type="button" name="next" id="next-step_<?php //echo $i;?>" class="next action-button"  value="Continue" />           -->
                                          <!--<button type="button" name="next" id="next-step_0"  class="next action-button"> Continue </button>-->
                                        </fieldset>
                                        
                                        <fieldset>
                                            <h5>Thank You Abuzer Ansari</h5>
                                            <p>You have now provided all necessary information and uploaded<br> copies of your documents(ID/Passport, Driver Licence, Bank<br> Statement, Visa).<br><br>
                                              when you press <b>FINISH</b> we will sent you few Forms for background<br> check and your Service Contract that you need to sign via e-mail. <br><br>
                                              This will be the <u>last</u> step in the Onboarding Process.<br><br>
                                              Thank you for your time.    <img src="data:image/jpeg;base64,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" alt="" width="30px" style="background-color: white;">
                                    
                                            </p>
                                            <input type="button" name="previous" class="previous action-button" value="Go Back" />
                                            <input type="submit" name="" class="next action-button" value="Finish" />
                                            <button type="submit" class="next action-button"> Finish</button>
                                        </fieldset>
                                    </form>
                                  
                            </div>
                            
                              
                        </div>    
                      </div>
                    </div> 
                </div>
                </div>
                </div>
                 
            </main>
           
        </div>
    
    
    </div>
    

<?php include('footer.php');?>

</div>

<?php include('footerScript.php'); ?>


<script>
$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();
     $('#myTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'loadtabledata.php',
            'data': {
                'action': 'loadrentalagrementtabledata'

            }
        },
        'columns':[
            
            { data: 'hirer'},
            { data: 'vehicle_reg_no'},
            { data: 'location'},
            { data: 'pickup_date'},
            { data: 'return_date'},
            { data: 'price_per_day'},
            { data: ''},
            { data: 'action'}
        ]
    });

});
</script>


                          
<script type="text/javascript">


$(function () {
    $('#type input[type=radio]').change(function(){
        
    //   alert($(this).val());
      var insurance_type = $(this).val();
       if(insurance_type == 1)
       {
        $("#insurance_1").show();
        $("#insurance_0").hide();
      }else
      {
        $("#insurance_0").show();
        $("#insurance_1").hide();
      }
     
    })
})

$(function () {
    $('#vehicle').change(function(){
        
     // alert($(this).val());
      
     
    })
})


// --------------------changes-----------------------------// changes end
function ShowHideDiv(divValue)
{
        if(divValue == 'view')
        {

            $('#msform_0')[0].reset(); 

            $("#AddFormDiv,#AddDiv").show();

            $("#ViewFormDiv,#ViewDiv").hide();     

        }

        if(divValue == 'add')

        {
            var table = $('#myTable').DataTable();
            table.ajax.reload();

            $("#AddFormDiv,#AddDiv").hide();

            $("#ViewFormDiv,#ViewDiv").show();     

        }
  }
  
  
</script>


<script>

var loadFile = function(event,flg) {
    var output = document.getElementById(flg+'');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function() {
      URL.revokeObjectURL(output.src) // free memory
    }
};


$("#vehicle").change(function() {
      
        var val = $(this).val();
        alert(val);
        $('#vehicle_register_id').val(val);
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'vehicle_details', vid: val},
            dataType: 'json',
            success: function(data) {

                $('#priceper_day').val(data.amount);
                var today = new Date();
                var date = today. getFullYear()+'-'+(today. getMonth()+1)+'-'+today. getDate();
                document. getElementById("pickup_date").defaultValue = date;
                
            }
        });
            
});
</script>



<script src="https://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

<script src="rentalscript.js"></script> 

</body>
</html>
