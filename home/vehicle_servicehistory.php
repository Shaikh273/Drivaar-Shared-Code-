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
              <div class="header">Service History</div>

               <div> 
                  <?php
                      if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][53]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                      { ?>
                          <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Service History</button>
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
                  <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Service History</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="ServiceHistoryForm" name="ServiceHistoryForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="vid" id="vid" value="<?php echo $id ?>">  
                       <div class="form-body">
                          <div class="row">

                              <div class="col-md-6">
                                   <div class="form-group">
                                     <label class="control-label">Odometer *</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control" id="odometer" name="odometer" placeholder="">
                                          <div class="input-group-append">
                                              <span class="input-group-text">mi</span>
                                          </div>
                                      </div>
                                  </div>
                              </div>

                              <div class="col-md-6">
                                  <div class="form-group">
                                     <label class="control-label">Completion Date *</label>
                                      <div class="input-group">
                                          <input type="text" class="form-control mydatepicker" id="completiondate" name="completiondate" placeholder="mm/dd/yyyy">
                                          <div class="input-group-append">
                                              <span class="input-group-text"><i class="icon-calender"></i></span>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row">
                              <div class="col-md-12">
                                   <div class="form-group">
                                      <label class="control-label">Reference *</label>
                                      <input type="text" class="form-control" id="reference" name="reference" placeholder="">
                                      <small>Optional (e.g. invoice number, etc.)</small>
                                  </div>
                              </div>
                          </div>

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

                              <div class="col-md-2">
                                  <div class="form-group">
                                     <label class="control-label">Labour *</label>
                                      <div class="input-group">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="labour" name="labour" placeholder="">
                                      </div>
                                  </div>
                              </div>

                               <div class="col-md-2">
                                  <div class="form-group">
                                     <label class="control-label">Parts *</label>
                                      <div class="input-group">
                                          <div class="input-group-append">
                                              <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                          </div>
                                          <input type="text" class="form-control" id="parts" name="parts" placeholder="">
                                      </div>
                                  </div>
                              </div>


                               <div class="col-md-2">
                                  <div class="form-group">
                                      <label class="control-label">Subtotal *</label>
                                      <div class="input-group">
                                          <div class="input-group-append">
                                              <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                          </div>
                                          <input type="text" class="form-control" id="subtotal" name="subtotal" placeholder="">
                                      </div>
                                  </div>
                              </div>
                          </div>

                          <div class="row">

                              <div class="col-md-12">
                                   <div class="form-group">
                                     <label class="control-label">General Notes</label>
                                  <div class="input-group">
                                      <textarea type="text" rows="2" class="form-control" id="description" name="description" placeholder=""></textarea>
                                  </div>
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

                                    <th style="width: 300px">Service Task</th>
                                    <th style="width: 120px">Completion Date</th>
                                    <th style="width: 70px">odometer</th>
                                    <th>Total</th>
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
                          'action': 'loadvehicleservicehistorytabledata',
                          'vid':vid,
                      }
                  },
                  'columns': [
                      { data: 'servicetask' },
                      { data: 'completiondate' },
                      { data: 'odometer' },
                      { data: 'subtotal' },
                      { data: 'date' },
                      { data: 'Status' },
                      { data: 'Action' }
                  ]
      });

	    $("#ServiceHistoryForm").validate({
	        rules: {
	            odometer: 'required',
	            completiondate: 'required',
              servicetaskid: 'required',
              labour: 'required',
              parts: 'required',
              subtotal: 'required',
	        },
	        messages: {
	            odometer: "Please enter your odometer",
	            completiondate: "Please enter your completion date",
              servicetaskid: "Please enter your service task",
              labour: "Please enter your labour",
              parts: "Please enter your parts",
              subtotal: "Please enter your subtotal",
	        },
	        submitHandler: function(form) {
	            event.preventDefault();
	            $.ajax({
	                url: "InsertData.php", 
	                type: "POST", 
	                dataType:"JSON",            
	                data: $("#ServiceHistoryForm").serialize()+"&action=AddVehicleServiceHistoryForm",
	                cache: false,             
	                processData: false,      
	                success: function(data) {
	                    if(data.status==1)
	                    {
	                         myAlert(data.title + "@#@" + data.message + "@#@success");
	                         $('#ServiceHistoryForm')[0].reset();
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

            data: {action : 'AddVehicleServiceHistoryUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#odometer').val($result_data['odometer']);
                $('#completiondate').val($result_data['completiondate']);
                $('#reference').val($result_data['reference']);
                $('#servicetaskid').val($result_data['servicetaskid']);
                $('#labour').val($result_data['labour']);
                $('#parts').val($result_data['parts']);
                $('#subtotal').val($result_data['subtotal']);
                $('#description').val($result_data['description']);

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

          data: {action : 'AddVehicleServiceHistoryDeleteData', id: id},

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
      $('#ServiceHistoryForm')[0].reset(); 
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