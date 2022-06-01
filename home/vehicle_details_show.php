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
        $id="";
        if(isset($_GET['vid']))
        {
            $id = base64_decode($_GET['vid']);
        }
        else if($_SESSION['vid']>0)
        {
            $id = $_SESSION['vid'];
        }else
        {
            header("Location:index.php");
        }
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name`tatusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,vfi.`insurance_company` as insname
          FROM `tbl_vehicles` v 
          LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
          LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
          LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` 
          LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
          LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
          LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id`
          LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance`
          WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $mysql -> dbDisConnect();

    }
    else
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
        .smalldiv{
          font-size: 40px;
        }
        .fortWeight{
          font-weight: 500;
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
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $cntresult['colorcode']?>;"></i> <?php echo $cntresult['statusname'];?></button>
                              <a href="conditionalreport.php?vid=<?php echo base64_encode($id);?>" 
                                     class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report
                              </a>
                           </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include('vehicle_setting.php'); ?>
                        <div class="row">
                            <div class="right-column">
                                <div class="px-md-3">             
                                  <div class="row mb-4">
                                      <div class="col-md-4">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                   <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Vehicle Information 
                                                    <span type="button" onclick="Gotopage();"> <i class="fas fa-edit"></i> Edit</span></h6>

                                              </div>     
                                              <div class="card-body">
                                                  <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2">Provider</dt>
                                                    <dd class="col-md-7 mb-2 fortWeight">
                                                        <?php echo $cntresult['suppliername'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2">VIN</dt>
                                                    <dd class="col-md-7 mb-2 fortWeight">
                                                        <?php echo $cntresult['vin_number'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2">Make/Model</dt>
                                                    <dd class="col-md-7 mb-2 fortWeight">
                                                        <?php echo $cntresult['makename'].' '.$cntresult['modelname'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2">Color</dt>
                                                    <dd class="col-md-7 mb-2 fortWeight">
                                                        <?php echo $cntresult['color'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2">Vehicle</dt>
                                                    <dd class="col-md-7 mb-2 fortWeight">
                                                        <?php echo $cntresult['groupname'];?>
                                                    </dd>
                                                  </dl>
                                              </div>
                                          </div>
                                      </div>

                                      <div class="col-md-4">
                                          <div class="card datacard">
                                            <div class="card-header dataheader">
                                                <h6 class="mb-2 cardmb2">Vehicle Renewal Dates</h6>
                                            </div>            
                                            <div class="card-body">
                                                <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                  <dt class="col-md-5 mb-2 text-grey-dark">MOT/Annual Test</dt>
                                                  <dd class="col-md-7 mb-2 fortWeight">
                                                      <?php echo $cntresult['timestamp'];?>
                                                  </dd>

                                                  <dt class="col-md-5 mb-2 text-grey-dark">Road/Vehicle Tax</dt>
                                                  <dd class="col-md-7 mb-2 fortWeight">
                                                      &nbsp;
                                                  </dd>

                                                  <dt class="col-md-5 mb-2 text-grey-dark">Vehicle Service</dt>
                                                  <dd class="col-md-7 mb-2 fortWeight">
                                                      <?php echo $cntresult['company_register_no'];?>
                                                  </dd>

                                                  <dt class="col-md-5 mb-2 text-grey-dark">Insurance</dt>
                                                  <dd class="col-md-7 mb-2 fortWeight">
                                                       <?php echo $cntresult['insname'];?>
                                                  </dd>

                                                  <dt class="col-md-5 mb-2 text-grey-dark">Inspection</dt>
                                                  <dd class="col-md-7 mb-2 fortWeight">
                                                       <?php echo $cntresult['utr'];?>
                                                  </dd>
                                                </dl>
                                            </div>
                                          </div>
                                      </div>

                                      <div class="col-md-4">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Vehicle Extras
                                                    <span type="button" onclick="Gotopage();"> <i class="fas fa-edit"></i> Edit</span></h6>
                                              </div> 
                                              <?php
                                               $options = explode(",", $cntresult['options']);
                                              ?>   
                                             <div class="card-body">
                                                <dl class="row mb-0 mb-3" style="line-height: 1.2;">
                                                    <?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                        $exquery = "SELECT * FROM `tbl_vehicleextra` WHERE `isdelete`= 0 AND `isactive`= 0";
                                                        $exrow =  $mysql -> selectFreeRun($exquery);
                                                      
                                                        while($exresult = mysqli_fetch_array($exrow))
                                                        {
                                                          ?>

                                                            <dt class="col-md-8 mb-2 text-grey-dark" id="<?php echo $exresult['id'];?>"><?php echo $exresult['name'];?></dt>
                                                            <dd class="col-md-4 mb-2 fortWeight">
                                                                <?php
                                                                $extra = 'No';
                                                                $exclr = "danger";
                                                                  for($i=0;$i<count($options);$i++)
                                                                  {

                                                                    if($options[$i]==$exresult['id'])
                                                                    {
                                                                       $extra = "Yes";
                                                                       $exclr = "success";
                                                                       break;
                                                                    }
                                                                  } 
                                                                ?>
                                                                <span class="label label-<?php echo $exclr;?>"><?php echo $extra;?></span>

                                                            </dd>
                                                          <?php

                                                        }
                                                        $mysql -> dbDisconnect();
                                                    ?>
                                                </dl>
                                             </div>
                                          </div>
                                      </div>
                                  </div>
                                   <div class="row mb-4">
                                      <div class="col-md-6">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                   <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Vehicle Owner(s)</h6>

                                              </div>     
                                              <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-9">
                                                        <select class="select form-control custom-select" id="owner_id" name="owner_id">
                                                           <?php
                                                              $mysql = new Mysql();
                                                              $mysql -> dbConnect();
                                                              $statusquery = "SELECT * FROM `tbl_vehicleowner` WHERE `isdelete`= 0 AND `isactive`= 0";
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
                                                    <div class="col-md-2">
                                                       <button type="button" onclick="AddOwner();" name="insert" class="btn btn-success" id="submit">Submit</button>
                                                    </div>
                                                    <div class="col-md-1">
                                                      <a href="" class="adddata" onclick="ShowOwner(<?php echo $id;?>)" data-toggle="tooltip" title="Owner Detail"><span><i class="fas fa-info-circle fa-2x"></i></span></a>
                                                    </div>
                                                </div>
                                              </div>
                                          </div>
                                      </div>

                                 
                                      <div class="col-md-2">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2">Vehicle Hires</h6>
                                              </div>    
                                             <div class="card-body">
                                                <span class="smalldiv ">3</span>
                                             </div>
                                          </div>
                                      </div>

                                      <div class="col-md-2">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2">In Damages</h6>
                                              </div>    
                                             <div class="card-body">
                                                <span class="smalldiv ">£0.00</span>
                                             </div>
                                          </div>
                                      </div>

                                      <div class="col-md-2">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2">Offences</h6>
                                              </div>    
                                             <div class="card-body">
                                               <span class="smalldiv">0</span>
                                             </div>
                                          </div>
                                      </div>
                                   </div>
                                   <div class="row mb-4">
                                      <div class="col-md-6">
                                          <div class="card datacard">
                                              <div class="card-header dataheader">
                                                   <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Rental Information
                                                    <span type="button" onclick="AddRentalInfo(<?php echo $id?>);"> <i class="fas fa-edit"></i> Edit</span></h6>
                                              </div>     
                                              <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">

                                                      <table>
                                                         <table id="renttbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                                                            <thead class="default">
                                                                <tr role="row">
                                                                    <th>Rent Date</th>
                                                                    <th>Rent Return Date</th>
                                                                    <th>Rent Price</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="Rentbody">
                                                                
                                                            </tbody>
                                                        </table>
                                                      </table>
                                                       
                                                    </div>
                                                </div>
                                              </div>
                                          </div>
                                      </div>

                                 
                                      <div class="col-md-6">
                                          
                                      </div>
                                   </div>
                                </div>
                            </div>
                        </div>
                </div>                        
            </main>
        </div>
    </div>
</div>
<div id="RentalInfoModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background-color: rgb(255 236 230);">
            <h4 class="modal-title">Vehicle supplier rent period</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
            <form method="post" id="AddRentalInfoForm" name="AddRentalInfoForm" action="">
                <input type="hidden" name="vehiclerentid" id="vehiclerentid">
                 <input type="hidden" name="rentid" id="rentid">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label class="control-label">Start Date</label>
                     <div class="input-group">
                        <input type="text" class="form-control mydatepicker" id="startdate" name="startdate" placeholder="mm/dd/yyyy">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="icon-calender"></i></span>
                        </div>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <label class="control-label">End Date</label>
                    <div class="input-group">
                        <input type="text" class="form-control mydatepicker" id="enddate" name="enddate" placeholder="mm/dd/yyyy">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="icon-calender"></i></span>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-12">
                    <label class="control-label">Rent price per day</label>
                    <input type="text" id="rentpriceperday" name="rentpriceperday" class="form-control" placeholder="">
                  </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="AddVehicleRentalInfoData();" class="btn btn-success waves-effect waves-light">Update vehicle rent period</button>
            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
<div id="ShowOwnerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(255 236 230);">
                <h4 class="modal-title">Owner Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <table id="Ownertbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                  <thead class="default">
                      <tr role="row">
                          <th>Owner Name</th>
                          <th>Date</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody id="Ownerbody">
                  </tbody>
              </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">
   $(document).ready(function(){
      showRentalInfo();
   });


  function Gotopage()
  {
    window.location = '<?php echo $webroot ?>vehicle_details.php?vid=<?php echo $id ?>';
  
  }

  function AddOwner()
  {
     var ID = <?php echo $id?>;
     var ownerId = $('#owner_id').val();
     $.ajax({
          url: "loaddata.php", 
          type: "POST", 
          dataType:"JSON",            
          data: {action : 'VehicleAddOwnerData', ownerId: ownerId, id: ID},      
          success: function(data) {
              if(data.status==1)
              {
                  myAlert("Insert @#@ Vehicle Owner has been inserted successfully. @#@success");
              }
              else
              {
                  myAlert("Insert Error @#@ Vehicle Owner can not been inserted. @#@danger");
              }
          }
      });
  }

  function AddRentalInfo(id)
  {
    $('#RentalInfoModel').modal('show');
    $('#vehiclerentid').val(id);
    event.preventDefault();
    $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'VehicleRentalInfoDetails', id: id},
          dataType: 'json',
          success: function(data) {
              if(data.status==1)
              {

                $result_data = data.rentdata;
                $('#rentid').val($result_data['rentid']);

                $('#startdate').val($result_data['startdate']);

                $('#enddate').val($result_data['enddate']);

                $('#rentpriceperday').val($result_data['rentpriceperday']);
              }
          }

      });
  }

  function AddVehicleRentalInfoData()
  {
    var id = $('#vehiclerentid').val();
    var rentid = $('#rentid').val();
    var startdate = $('#startdate').val();
    var enddate = $('#enddate').val();
    var rentpriceperday = $('#rentpriceperday').val();
    $.ajax({
          type: "POST",
          url: "InsertData.php",
          data: {action : 'AddVehicleRentalInfoDetails', id: id, rentid: rentid, startdate:startdate, enddate:enddate, rentpriceperday:rentpriceperday},
          dataType: 'json',
          success: function(data) {
              if(data.status==1)
              {
                 myAlert(data.title + "@#@" + data.message + "@#@success");
                 $('#AddRentalInfoForm')[0].reset();
                 showRentalInfo();
              }
              else
              {
                 myAlert(data.title + "@#@" + data.message + "@#@danger");
              }
              $('#RentalInfoModel').modal('hide');
          }

      });
  }

  function showRentalInfo()
  {
     var vid = <?php echo $id?>;
     $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ShowVehicleRentalInfoDetails', id: vid},
      dataType: 'json',
      success: function(data) {
          $('#Rentbody').html(data.tbldata);
      }

  });

  }

  function ShowOwner(id)
  {
      $('#ShowOwnerModal').modal('show');
      $('#vehicleid').val(id);
      event.preventDefault();
      OwnerModal(id);  
  }

  function deleteownerrow(id,vid)
  {
      $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'VehicledeleteOwnerData', id: id},
          dataType: 'json',
          success: function(data) {
              if(data.status==1)
              {
                  OwnerModal(vid);
                  myAlert("Delete @#@ Data has been deleted successfully.@#@success");
              }
              else
              {
                  myAlert("Delete @#@ Data can not been deleted.@#@danger");
              }
          }
      });
  }

  function OwnerModal(id)
  {
     $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'VehicleOwnerDetails', id: id},
          dataType: 'json',
          success: function(data) {
              if(data.status==1)
              {
                 $('#Ownerbody').html(data.tbldata);
              }
              else
              {
                  $('#Ownerbody').html(data.tbldata);
              }
          }

      });
  }
</script>
</body>
</html>