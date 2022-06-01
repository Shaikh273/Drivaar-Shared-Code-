<?php
include 'DB/config.php';
    $page_title = "Vehicles Details";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        if(isset($_GET['vid']))
        {
          $id = $_GET['vid'];
          
        }
        else if($_SESSION['vid']>0)
        {
          $id = $_SESSION['vid'];
        }
        else
        {
          header("location: vehicle.php");
        }
        $userid = $_SESSION['userid']; 
        if($userid==1)
        {
          $uid=1;//'%';
        }
        else
        {
          $uid=$userid;
        }
        
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode`  FROM `tbl_vehicles` v 
                  LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
                  WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $stclr=$cntresult['colorcode'];
        $strname=$cntresult['statusname'];
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
    <style type="text/css">
        .datacard {
          border: 1px solid pink;
          height: auto;
          border-radius: 5px;
        }
        .dataheader {
           background-color: rgb(255 236 230);
        }
        .cardmb2 {
           height: 8px;
        }
        .fasicon {
            color: #f87171;
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
                                <div class="header">Registration Number : <?php echo $cntresult['registration_number'];?></div>
                                <div> 
                                  <a href="">
                                        <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                                   </a>
                                  <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $stclr;?>;"></i> <?php echo $strname;?></button>
                                  <a href=""> 
                                        <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report</button>
                                  </a>
                               </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php include('vehicle_setting.php'); ?>
                            <div class="row">
                              <div class="col-md-12">
                                <form method="post" id="vendor_detailsForm" name="vendor_detailsForm" action="">
                                    <input type="hidden" name="id" id="id" value="">  
                                       <div class="form-body">
                                          <div class="row p-t-20">
                                             <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Status *</label>
                                                          <select class="select form-control custom-select" id="status" name="status">
                                                             <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $statusquery = "SELECT * FROM `tbl_vehiclestatus` WHERE `isdelete`= 0 AND `isactive`= 0";
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
                                                      <div class="form-group">
                                                          <label class="control-label">Registration Number *</label>
                                                          <input type="text" class="form-control" name="registration_number" id="registration_number" value="<?php echo $cntresult['registration_number'];?>" readonly="true"> 
                                                      </div>
                                              </div>
                                              <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">VIN Number *</label>
                                                          <input type="text" class="form-control" name="vin_number" id="vin_number"> 
                                                      </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Supplier *</label>
                                                          <select class="select form-control custom-select" id="supplier" name="supplier">
                                                             <?php

                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $supplierquery = "SELECT * FROM `tbl_vehiclesupplier` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $supplierrow =  $mysql -> selectFreeRun($supplierquery);
                                                                while($supplierresult = mysqli_fetch_array($supplierrow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $supplierresult['id']?>"><?php echo $supplierresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 
                                                      </div>
                                              </div>
                                              <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Group *</label>
                                                          <select class="select form-control custom-select" id="group" name="group">
                                                              <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $groupquery1 = "SELECT * FROM `tbl_vehiclegroup` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $grouprow =  $mysql -> selectFreeRun($groupquery1);
                                                                while($groupresult = mysqli_fetch_array($grouprow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $groupresult['id']?>"><?php echo $groupresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 
                                                      </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label class="control-label">Depot *</label>
                                                      <select class="select form-control custom-select" id="depot" name="depot">
                                                         <?php
                                                            $mysql = new Mysql();
                                                            $mysql -> dbConnect();
                                                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid`=".$uid;
                                                            echo $statusquery;
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
                                          </div>
                                          <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                              <div class="d-flex justify-content-between align-items-center">
                                                  <h5 class="m-0">Details</h5>
                                              </div>
                                          </div>
                                          <div class="row">
                                            <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Make *</label>
                                                          <select class="select form-control custom-select" id="make" name="make" value="<?php echo $cntresult['make_id'];?>" readonly="true"> 
                                                              <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $makequery = "SELECT * FROM `tbl_vehiclemake` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $makerow =  $mysql -> selectFreeRun($makequery);
                                                                while($makeresult = mysqli_fetch_array($makerow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $makeresult['id']?>"><?php echo $makeresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 
                                                      </div>
                                              </div>
                                              <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Model *</label>

                                                          <select class="select form-control custom-select" id="model" name="model">
                                                              <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $modelquery = "SELECT * FROM `tbl_vehiclemodel` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $modelrow =  $mysql -> selectFreeRun($modelquery);
                                                                while($modelresult = mysqli_fetch_array($modelrow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $modelresult['id']?>"><?php echo $modelresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 

                                                      </div>

                                              </div>
                                              <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Type of Vehicle *</label>

                                                          <select class="select form-control custom-select" id="vehicle_type" name="vehicle_type">
                                                              <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $typequery = "SELECT * FROM `tbl_vehicletype` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $typerow =  $mysql -> selectFreeRun($typequery);
                                                                while($typeresult = mysqli_fetch_array($typerow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $typeresult['id']?>"><?php echo $typeresult['name']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 

                                                      </div>

                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">color *</label>

                                                          <input type="text" class="form-control" name="color" id="color" value="<?php echo $cntresult['color'];?>" readonly="true"> 

                                                      </div>

                                              </div>
                                               <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Fuel Type *</label>

                                                          <input type="text" class="form-control" name="fuel_type" id="fuel_type" value="<?php echo $cntresult['fuel_type'];?>" readonly="true"> 

                                                      </div>

                                              </div>
                                          </div>
                                           <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">

                                              <div class="d-flex justify-content-between align-items-center">

                                                  <h5 class="m-0">Insurance Details</h5>

                                              </div>

                                          </div>
                                          <div class="row">
                                             
                                               <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Insurance *</label>
                                                          <select class="select form-control custom-select" name="insurance" id="insurance">
                                                              <?php
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $makequery = "SELECT * FROM `tbl_vehiclefleetinsuarnce` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                                $makerow =  $mysql -> selectFreeRun($makequery);
                                                                while($makeresult = mysqli_fetch_array($makerow))
                                                                {
                                                                  ?>
                                                                      <option value="<?php echo $makeresult['id']?>"><?php echo $makeresult['insurance_company']?></option>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                          </select> 
                                                      </div>

                                              </div>
                                              <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Goods In Transit</label>

                                                          <input type="text" class="form-control" name="goods_in_transit" id="goods_in_transit"> 

                                                      </div>

                                              </div>
                                              <div class="col-md-4">

                                                      <div class="form-group">

                                                          <label class="control-label">Public Liability</label>

                                                          <input type="text" class="form-control" name="public_liability" id="public_liability"> 

                                                      </div>

                                              </div>
                                          </div>
                                          <hr><br>
                                          <div class="row">
                                             <div class="col-md-4">
                                              <?php
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                  $exquery = "SELECT * FROM `tbl_vehicleextra` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                  $exrow =  $mysql -> selectFreeRun($exquery);
                                                  while($exresult = mysqli_fetch_array($exrow))
                                                  {
                                                    ?>
                                                      <label class="custom-control custom-checkbox m-b-0">
                                                        <input type="checkbox" class="custom-control-input" name="options[]" id="<?php echo $exresult['id'];?>" value="<?php echo $exresult['id'];?>">
                                                        <span class="custom-control-label"><?php echo $exresult['name'];?> </span>
                                                      </label>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                              ?>
                                             </div>
                                             <div class="col-md-8">
                                                    <div class="form-group">
                                                          <label class="control-label">Rental Conditions</label>
                                                           <textarea type="text" rows="4" class="form-control" id="rental_condition" name="rental_condition"></textarea> 
                                                    </div>
                                             </div>
                                          </div>
                                       <div class="form-actions">
                                          <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                                       </div>
                                </form>
                              </div>
                            </div>
                        </div>                        
            </main>
        </div>
    </div>
</div>
<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
</body>
<script type="text/javascript">
  $(document).ready(function(){
      var vehicle_id = <?php echo $id ?>;
      if(vehicle_id)
      {
        edit(vehicle_id);
      }
   
      $("#vendor_detailsForm").validate({
        rules: {
          status: 'required',
            registration_number: 'required',
            vin_number: 'required',
            supplier: 'required',
            group: 'required',
            depot: 'required',
            make: 'required',
            model: 'required',
            vehicle_type: 'required',
            color: 'required',
            fuel_type: 'required',
            insurance: 'required',
        },
        messages: {
          status: "Please enter your status",
            registration_number: "Please enter your vehicle registration number",
            vin_number: "Please enter your VIN number",
            supplier: "Please enter your supplier",
            group: "Please enter your group",
            depot: "Please enter your depot",
            make: "Please enter your make",
            model: "Please enter your model",
            vehicle_type: "Please enter your vehicle_type",
            color: "Please enter your color",
            fuel_type: "Please enter your fuel_type",
            insurance: "Please enter your insurance",
        },
         submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "InsertData.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#vendor_detailsForm").serialize()+"&action=VehicleDetailsform",
                cache: false,             
                processData: false,      
                success: function(data) {
                    if(data.status==1)
                    {
                        window.location = '<?php echo $webroot ?>vehicle_details_show.php?vid=<?php echo $id ?>';
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                      
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
           // return false;
        }
      });
  });

  function Gotopage()
  {
    window.location = '<?php echo $webroot ?>vehicle_details_show.php?vid=<?php echo $id ?>';
  }

  function edit(id)
  {
    $('#id').val(id);
    $.ajax({
        type: "POST",
        url: "loaddata.php",
        data: {action : 'VehicleUpdateData', id: id},
        dataType: 'json',
        success: function(data) {
          if(data.status == 1)
          {
              $result_data = data.statusdata;
              $('#status').val($result_data['status']);
              $('#vin_number').val($result_data['vin_number']);
              $('#supplier').val($result_data['supplier_id']);
              $('#group').val($result_data['group_id']);
              $('#depot').val($result_data['depot_id']);
              $('#make').val($result_data['make_id']);
              $('#model').val($result_data['model_id']);
              $('#vehicle_type').val($result_data['type_id']);
              $('#color').val($result_data['color']);
              $('#fuel_type').val($result_data['fuel_type']);
              $('#insurance').val($result_data['insurance']);
             // $("#insurance option[value="+$result_data['insurance']+"]").attr('selected', 'selected');

              $('#goods_in_transit').val($result_data['goods_in_transit']);
              $('#public_liability').val($result_data['public_liability']);
              $('#rental_condition').val($result_data['rental_condition']);
              var options =  $result_data['options'].split(',');
              for(i=0;i<options.length;i++)
              {
                $("#"+options[i]).prop("checked", true);
              }
          }
          else
          {
             $('#vendor_detailsForm')[0].reset(); 
          }
        }
    });
  }
</script>
</html>