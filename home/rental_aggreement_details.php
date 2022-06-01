<?php
include 'DB/config.php';
    $page_title = "Renatl Details";
    $page_id=52;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    $userid = $_SESSION['userid']; 
        if($userid==1)
        {
          $uid='%';
        }
        else
        {
          $uid=$userid;
        }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_GET['rid']; 
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT r.*,v.`registration_number` as regestrationnumber, s.`name` as suppliername,c.`name` as contaractorname FROM `tbl_vehiclerental_agreement` r
        INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
        INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
        INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
        WHERE r.`id`=$id AND r.`isdelete`=0 AND r.`userid` LIKE '".$uid."'";
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
                            <div class="header">
                                <label>Hire Agreement</label><br>
                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fa fa-user pr-2 pt-1" aria-hidden="true" style="font-size: 12px;"></i>
                                    <?php echo $cntresult['contaractorname'];?>
                                </small>
                                <a href="#" class="text-gray-700">
                                    <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fas fa-car pr-2 pt-1"></i>
                                        <?php echo $cntresult['suppliername']. ' ('.$cntresult['regestrationnumber'].')';?>
                                    </small>
                                </a>
                                <a href="#" class="text-gray-700">
                                    <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                    <i class="fas fa-clock pt-1 pr-2"></i>
                                        <?php echo $cntresult['insert_date'];?>
                                    </small>
                                </a>
                            </div>
                            <div> 
                              <a href="#" class="btn btn-primary btn- btn btn-primary" formclass="d-inline-block">End Agreement</a>
                           </div>
                        </div>
                    </div>
<div class="card-body">
  <?php include('vehicle_setting.php'); ?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
      <div class="card-header" style="background-color: #fff;">
          <div class="d-flex justify-content-between align-items-center">
              <div class="header">Vehicle Renewals</div>

               <div> 
                   <button type="button" class="btn btn-primary" id="renewal" value="renewal"> Check Insurance Online</button>

                    <?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][53]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Renewal</button>
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

                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Renewal</button>
               </div>                   

          </div>
      </div>
      <div class="card-body" id="AddFormDiv">
          <div class="row">
            <div class="col-md-12">
                <form method="post" id="RenewalForm" name="RenewalForm" action="">
                    <input type="hidden" name="id" id="id" value=""> 
                     <input type="hidden" name="vid" id="vid" value="<?php echo $id ?>">  
                       <div class="form-body">
                          <div class="row p-t-20">
                              <div class="col-md-6">
                                   <div class="form-group">
                                      <label class="control-label">Renewal Type *</label>
                                      <select class="select form-control custom-select" id="renewaltype" name="renewaltype">
                                        <?php
                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $renewalquery = "SELECT * FROM `tbl_vehiclerenewaltype` WHERE `isdelete`= 0 AND `isactive`= 0";
                                          $renewalrow =  $mysql -> selectFreeRun($renewalquery);
                                          while($renewalresult = mysqli_fetch_array($renewalrow))
                                          {
                                            ?>
                                                <option value="<?php echo $renewalresult['id']?>"><?php echo $renewalresult['name']?></option>
                                            <?php
                                          }
                                          $mysql -> dbDisconnect();
                                      ?>
                                      </select> 
                                  </div>
                              </div>
                               <div class="col-md-6">
                                   <label class="control-label">Due Date *</label>
				                    <div class="input-group">
				                        <input type="text" class="form-control mydatepicker" id="duedate" name="duedate" placeholder="mm/dd/yyyy">
				                        <div class="input-group-append">
				                            <span class="input-group-text"><i class="icon-calender"></i></span>
				                        </div>
				                    </div>
				                     <small>You will be reminded 2 weeks before the due date.</small>
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
      <div class="card-body" id="ViewFormDiv">
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th>Renewal Type</th>

                                    <th data-orderable="false">Due Date</th>

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

      $('#myTable').DataTable({
                  'processing': true,
                  'serverSide': true,
                  'serverMethod': 'post',
                  'ajax': {
                      'url':'loadtabledata.php',
                      'data': {
                          'action': 'loadaddvehiclerenewaltypetabledata'
                      }
                  },
                  'columns': [
                      { data: 'renewal_type' },
                      { data: 'duedate' },
                      { data: 'date' },
                      { data: 'Status' },
                      { data: 'Action' }
                  ]
      });

	    $("#RenewalForm").validate({
	        rules: {
	            renewaltype: 'required',
	            duedate: 'required',
	        },
	        messages: {
	            renewaltype: "Please enter your renewal type",
	            duedate: "Please enter your due date",
	        },
	        submitHandler: function(form) {
	            event.preventDefault();
	            $.ajax({
	                url: "InsertData.php", 
	                type: "POST", 
	                dataType:"JSON",            
	                data: $("#RenewalForm").serialize()+"&action=AddVehiclerenewalform",
	                cache: false,             
	                processData: false,      
	                success: function(data) {
	                    if(data.status==1)
	                    {
	                         myAlert(data.title + "@#@" + data.message + "@#@success");
	                         $('#RenewalForm')[0].reset();
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

            data: {action : 'AddVehicleRenewalTypeUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#renewaltype').val($result_data['renewal_id']);
                $('#duedate').val($result_data['duedate']);
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

          data: {action : 'AddVehicleRenewalTypeDeleteData', id: id},

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
      $('#RenewalForm')[0].reset(); 
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