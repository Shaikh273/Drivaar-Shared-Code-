<?php
include 'DB/config.php';
    $page_title = "Vehicles Service History";
     $page_id=52;
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
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
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
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Service Reminders</div>

               <div> 

                  <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][53]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Service Reminder</button>
                          <?php
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
                  <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Service Reminder</button>

               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="ServiceReminderForm" name="ServiceReminderForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="vid" id="vid" value="<?php echo $id ?>">  
                       <div class="form-body">

                        <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                      <label class="control-label">Service Task *</label>
                                      <select class="select form-control custom-select" id="servicetaskid" name="servicetaskid">
                                         <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $expquery = "SELECT * FROM `tbl_vehicleservicetask` WHERE `isdelete`= 0 AND `isactive`= 0";
                                            $exprow =  $mysql -> selectFreeRun($expquery);
                                            while($result = mysqli_fetch_array($exprow))
                                            {
                                              ?>
                                                  <option value="<?php echo $result['id']?>"><?php echo $result['name']?></option>
                                              <?php
                                            }
                                            $mysql -> dbDisconnect();
                                        ?>
                                      </select> 
                                  </div>
                              </div>  

                              <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="control-label">Odometer Interval *</label>
                                      <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Every</span>
                                        </div>
                                        <input type="text" class="form-control" id="odometer_interval" name="odometer_interval" placeholder="">
                                        <div class="input-group-append">
                                            <span class="input-group-text">miles</span>
                                        </div>
                                      </div>
                                      <small>Repeat based on usage (e.g. Oil Change every 5,000 miles). Leave blank if you don't want to use this option.</small>
                                  </div>
                              </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="control-label">Time Interval *</label>
                                      <div class="input-group">
                                          <div class="input-group-append">
                                              <span class="input-group-text">Every</span>
                                          </div>
                                          <input type="text" class="form-control" id="time_interval" name="time_interval" placeholder="">
                                      </div>
                                      <small>Repeat based on a time interval (e.g. Car Wash every 1 month). Leave blank if you don't want to use this option.</small>
                                  </div>
                              </div>

                             <div class="col-md-6">
                                 <div class="form-group">
                                      <label class="control-label">Type *</label>
                                      <select class="select form-control custom-select" id="type_interval" name="type_interval">
                                         <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $query = "SELECT * FROM `tbl_vehicletypereminder` WHERE `isdelete`= 0 AND `isactive`= 0";
                                            $row =  $mysql -> selectFreeRun($query);
                                            while($typeresult = mysqli_fetch_array($row))
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
                            <div class="alert alert-primary alert-rounded col-sm-12"><i class="fas fa-info-circle"></i> <b> "Due soon" Settings  We will mark the selected service as "Due Soon" and send an email based on these threshold settings.</b></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                   <label class="control-label">Odometer Threshold *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="odometer_threshold" name="odometer_threshold" placeholder="">
                                        <div class="input-group-append">
                                              <span class="input-group-text">miles</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                   <label class="control-label">Time Threshold *</label>
                                    <input type="number" class="form-control" id="time_threshold" name="time_threshold" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                   <label class="control-label">Type *</label>
                                    <select class="select form-control custom-select" id="type_threshold" name="type_threshold">
                                       <?php
                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $query = "SELECT * FROM `tbl_vehicletypereminder` WHERE `isdelete`= 0 AND `isactive`= 0";
                                          $row =  $mysql -> selectFreeRun($query);
                                          while($typeresult = mysqli_fetch_array($row))
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
                        </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                        </div>
                </form>
            </div>
          </div>
      </div>
      <div class="card-body" id="ViewFormDiv">
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th style="width: 400px">Service Task</th>
                                    <th style="width: 300px">Next Due</th>
                                    <th data-orderable="false">Date</th>
                                    <th data-orderable="false">Status</th>
                                    <th data-orderable="false"></th>
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
        </div>
    </div>
</div>

<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">
	function Gotopage()
	{
		window.location = '<?php echo $webroot ?>vehicle_details.php?vid=<?php echo $id ?>';
	}

	$(document).ready(function(){
	    $("#AddFormDiv,#AddDiv").hide();
      var vid = <?php echo $id ?>;
      $('#myTable').DataTable({
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  'ajax': {
                      'url':'loadtabledata.php',
                      'data': {
                          'action': 'loadvehicleserviceremindertabledata',
                          'vid':vid,
                      }
                  },
                  'columns': [
                      { data: 'servicetask' },
                      { data: 'odometer_threshold' },
                      { data: 'date' },
                      { data: 'Status' },
                      { data: 'Action' }
                  ]
      });

	    $("#ServiceReminderForm").validate({
	        rules: {
	            odometer_interval: 'required',
	            time_interval: 'required',
              servicetaskid: 'required',
              type_interval: 'required',
              odometer_threshold: 'required',
              time_threshold: 'required',
              type_threshold: 'required',
	        },
	        messages: {
	            odometer_interval: "Please enter your odometer interval",
	            time_interval: "Please enter your completion time interval",
              servicetaskid: "Please enter your service task",
              type_interval: "Please enter your type",
              odometer_threshold: "Please enter your odometer threshold",
              time_threshold: "Please enter your time threshold",
              type_threshold: "Please enter your type",
	        },
	        submitHandler: function(form) {
	            event.preventDefault();
	            $.ajax({
	                url: "InsertData.php", 
	                type: "POST", 
	                dataType:"JSON",            
	                data: $("#ServiceReminderForm").serialize()+"&action=AddVehicleServiceReminderForm",
	                cache: false,             
	                processData: false,      
	                success: function(data) {
	                    if(data.status==1)
	                    {
	                         myAlert(data.title + "@#@" + data.message + "@#@success");
	                         $('#ServiceReminderForm')[0].reset();
                              if(data.name == 'Update')
                              {
                                  var table = $('#myTable').DataTable();
                                  table.ajax.reload();
                                  $("#AddFormDiv,#AddDiv").hide();
                                  $("#ViewFormDiv,#ViewDiv").show();
                              }
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

  function edit(id)
  {
        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'AddVehicleServiceReminderUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#odometer_interval').val($result_data['odometer_interval']);
                $('#time_interval').val($result_data['time_interval']);
                $('#type_interval').val($result_data['type_interval']);
                $('#servicetaskid').val($result_data['servicetaskid']);
                $('#time_threshold').val($result_data['time_threshold']);
                $('#type_threshold').val($result_data['type_threshold']);
                $('#odometer_threshold').val($result_data['odometer_threshold']);

                $("#submit").attr('name', 'update');
                $("#submit").text('Update');

            }

        });
  }

  function deleterow(id)
  {

      $.ajax({

          type: "POST",

          url: "loaddata.php",

          data: {action : 'AddVehicleServiceReminderDeleteData', id: id},

          dataType: 'json',

          success: function(data) {
              if(data.status==1)
              {
                  var table = $('#myTable').DataTable();
                  table.ajax.reload();
                  myAlert("Delete @#@ Data has been deleted successfully.@#@success");
              }
              else
              {
                  myAlert("Delete @#@ Data can not been deleted.@#@danger");
              }
              
          }

      });
  }

  function ShowHideDiv(divValue)
  {
      $('#ServiceReminderForm')[0].reset(); 
      if(divValue == 'view')
      {
          $("#submit").attr('name', 'insert');
          $("#submit").text('Submit');
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
</body>
</html>