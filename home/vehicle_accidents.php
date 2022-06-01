<?php
include 'DB/config.php';
$page_title="Vehicle accident";
$page_id = 64;
if(!isset($_SESSION)) 
{ 
    session_start();
    
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $userid=$_SESSION['userid'];
    if($userid==1)
    {
       $uid='%'; 
    }
    else
    {
       $uid = $_SESSION['userid']; 
    }
}else
{
    header("location: login.php");    
}
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">

</head>
<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>
<div id="main-wrapper">
<?php include('header.php');?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Accident</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add accident</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View accident</button>
                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  	<div class="col-md-12">
	                    <form method="post" id="accidentForm" name="accidentForm" action="" enctype="multipart/form-data">
	                        <input type="hidden" name="userid" id="userid" value="<?php echo $userid;?>">
	                        <input type="hidden" name="id" id="id" value="">
	                            <div class="form-body">
	                                <div class="row p-t-20">
	                                    <div class="col-md-4">
	                                          <div class="form-group">
	                                              <label class="control-label">Driver *</label>
	                                               <select class="select form-control custom-select" id="driver_id" name="driver_id">
	                                                   <?php
	                                                    $mysql = new Mysql();
	                                                    $mysql -> dbConnect();
	                                                    $exquery = "SELECT DISTINCT c.*
																	FROM `tbl_contractor` c 
																	INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
																	WHERE c.`depot` IN (w.depot_id) AND c.`isdelete`=0 AND c.`iscomplated`=1";
	                                                    $exrow =  $mysql -> selectFreeRun($exquery);
	                                                    while($coresult = mysqli_fetch_array($exrow))
	                                                    {
	                                                      ?>
	                                                         <option value="<?php echo $coresult['id']?>"><?php echo $coresult['name']?></option>
	                                                      <?php
	                                                    }
	                                                    $mysql -> dbDisconnect();
	                                                ?>
	                                              </select>
	                                          </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                          <div class="form-group">
	                                              <label class="control-label">Vehicle *</label>
	                                               <select class="select form-control custom-select" id="vehicle_id" name="vehicle_id">
	                                                 <?php
	                                                    $mysql = new Mysql();
	                                                    $mysql -> dbConnect();
	                                                    $vquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                                            FROM `tbl_vehicles` v 
                                                            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            WHERE v.`isdelete`= 0 ";
	                                                    $exprow =  $mysql -> selectFreeRun($vquery);
	                                                    while($result = mysqli_fetch_array($exprow))
	                                                    {
	                                                      ?>
	                                                          <option value="<?php echo $result['id']?>"><?php echo $result['suppliername']. ' ( ' . $result['registration_number'].' )'?></option>
	                                                      <?php
	                                                    }
	                                                    $mysql -> dbDisconnect();
	                                                ?>
	                                              </select> 
	                                          </div>
	                                    </div>
	                                    <div class="col-md-4">
		                                    <div class="form-group">
	                                          <label class="control-label">Date Occurred *</label>
	                                          <div class="input-group">
	                                            <input type="datetime-local" class="form-control" id="date_occured" name="date_occured">
	                                          </div>
		                                    </div>
	                                	</div>
	                                </div>

	                                <div class="row">
	                                	<div class="col-md-4">
		                                    <div class="form-group">
		                                        <label class="control-label">Type *</label>
		                                        <select class="select form-control custom-select" id="type_id" name="type_id">
	                                             <?php
	                                                $mysql = new Mysql();
	                                                $mysql -> dbConnect();
	                                                $query = "SELECT * FROM `tbl_vehicletypeaccident` WHERE `isdelete`=0 AND `isactive`=0";
	                                                $row =  $mysql -> selectFreeRun($query);
	                                                while($result1 = mysqli_fetch_array($row))
	                                                {
	                                                  ?>
	                                                      <option value="<?php echo $result1['id']?>"><?php echo $result1['name']?></option>
	                                                  <?php
	                                                }
	                                                $mysql -> dbDisconnect();
	                                            ?>
		                                        </select> 
		                                    </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                        <div class="form-group">
	                                           <label class="control-label">Stage *</label>
	                                            <select class="select form-control custom-select" id="stage_id" name="stage_id">
	                                             <?php
	                                                $mysql = new Mysql();
	                                                $mysql -> dbConnect();
	                                                $query1 = "SELECT * FROM `tbl_vehicleaccidentstage` WHERE `isdelete`=0 AND `isactive`=0";
	                                                $row1 =  $mysql -> selectFreeRun($query1);
	                                                while($result2 = mysqli_fetch_array($row1))
	                                                {
	                                                  ?>
	                                                      <option value="<?php echo $result2['id']?>"><?php echo $result2['name']?></option>
	                                                  <?php
	                                                }
	                                                $mysql -> dbDisconnect();
	                                            ?>
	                                      		</select> 
	                                        </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                        <div class="form-group">
	                                           <label class="control-label">Reference *</label>
	                                            <input type="text" id="reference" name="reference" class="form-control" placeholder="">
	                                        </div>
	                                    </div>
	                                </div>
	                          
		                                <div class="row">
		                                    <div class="col-md-12">
		                                          <div class="form-group">
		                                              <label class="control-label">Description *</label>
		                                              <textarea type="text" id="description" name="description" class="form-control" placeholder="" rows="2"></textarea>	
		                                          </div>
		                                    </div>
		                                </div>

									    <div class="row">
									        <div class="form-group col-md-6">
									            <label class="control-label">Other Person *</label>
									            <input type="text" class="form-control" id="other_person" name="other_person" placeholder="">
									        </div>
									        <div class="form-group col-md-6">
									            <label class="control-label">Other vehicle plate number *</label>
									            <input type="text" class="form-control" id="other_vehicle" name="other_vehicle" placeholder="">
									        </div>
									    </div>  

									    <div class="row">
									        <div class="form-group col-md-6">
									            <label class="control-label">Contact phone number *</label>
									            <input type="number" class="form-control" id="contact" name="contact" placeholder="">
									        </div>

									        <div class="form-group col-md-6">
								            <label class="control-label">Other notes *</label>
								            <textarea type="text"  class="form-control" id="other_notes" name="other_notes" placeholder=""></textarea>
									    	</div>
									    </div>

									    <div class="form-group col-md-6">
									        <label class="custom-control custom-checkbox m-b-0 mt-1">
									            <input type="file" name="accident_image"  id="accident_image" class="form-control p-1 mt-4" multiple="multiple">
									        </label>
									    </div>
	                            </div>

	                            <div class="form-actions">
	                                <button type="submit" name="insert" class="btn btn-success" id="submit" onclick="insertdata();">Submit</button>
	                            </div>
	                    </form>
                  	</div>
                </div>
            </div>
            <div class="card-body" id="ViewFormDiv">
            	<div class="row">          
                    <div class="col-md-12">
                        <div class="row">
                        	<div class="col-md-3">
                                 <div class="form-group has-primary">
                                    <input type="text" class="form-control" name="driver_name_serch" id="driver_name_serch" placeholder="By driver..." onkeyup="loadtable();">  
                                </div>
                            </div>
                            <div class="col-md-3">
                                 <div class="form-group has-primary">
                                <select class="select form-control custom-select" id="vehicle_serch" name="vehicle_serch" onchange="loadtable();">
                                    <option value="%">All Vehicle</option>
                                    <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $viquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                            FROM `tbl_vehicles` v 
                                            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                            WHERE v.`isdelete`= 0 ";
                                        $vexprow =  $mysql -> selectFreeRun($viquery);
                                        while($result1 = mysqli_fetch_array($vexprow))
                                        {
                                          ?>
                                              <option value="<?php echo $result1['id']?>"><?php echo $result1['suppliername']. ' ( ' . $result1['registration_number'].' )'?></option>
                                          <?php
                                        }
                                        $mysql -> dbDisconnect();
                                    ?>
                                </select>   
                                </div>
                            </div>

                            <div class="col-md-3">
                              <div class="form-group has-primary">
                                   <select class="select form-control custom-select" id="stage_serch" name="stage_serch" onchange="loadtable();">
                                    <option value="%">All Stage</option>
                                    <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
	                                    $query1 = "SELECT * FROM `tbl_vehicleaccidentstage` WHERE `isdelete`=0 AND `isactive`=0";
	                                    $row1 =  $mysql -> selectFreeRun($query1);
	                                    while($result2 = mysqli_fetch_array($row1))
	                                    {
	                                      ?>
	                                          <option value="<?php echo $result2['id']?>"><?php echo $result2['name']?></option>
	                                      <?php
	                                    }
	                                    $mysql -> dbDisconnect();
                                    ?>
                                </select> 
                              </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group has-primary">
                                  <select class="select form-control custom-select" id="status_serch" name="status_serch" onchange="loadtable();">
                                    <option value="%">By Status</option>
                                    <option value="0">Open</option>
                                    <option value="1">Close</option>
                                </select>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>

                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                	<th>Stage</th>
                                	<th>Driver</th>
                                    <th>Vehicle</th>
                                    <th>Reg. Plate</th>
                                    <th>References</th>
                                    <th>Date</th>
                                    <th>Reported</th>
                                    <th>Type</th>
                                    <th></th>
                                    <th data-orderable="false">Status</th>
                                    <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>
            </div>
        </div>        
    </main>
</div>
</div>

<?php include('footer.php');?>
</div>

<?php include('footerScript.php');?>

<script type="text/javascript">

function insertdata()
{
  var status = "";
  var user_id = $('#userid').val(); 
  var driver_id = $('#driver_id').val();
  var vehicle_id = $('#vehicle_id').val();
  var date_occured = $('#date_occured').val();
  var type_id = $('#type_id').val();
  var stage_id = $('#stage_id').val();
  var reference = $('#reference').val();
  var description = $('#description').val();
  var other_person = $('#other_person').val();
  var other_vehicle = $('#other_vehicle').val();
  var contact = $('#contact').val();
  var other_notes = $('textarea#other_notes').val();
  
  // var amountfee = $('#amountfee').val();
  
   var form_data = new FormData();

   var totalfiles = document.getElementById('accident_image').files.length;
   for (var index = 0; index < totalfiles; index++) 
   {
    form_data.append("files[]", document.getElementById('accident_image').files[index]);
   }

   var id=$('#id').val(); 
   var a=$('#submit').attr('name');

   if(a == "update")
   {
  		status = "update";
  		form_data.append("id",id);
   }
   else
   {
  		status = "Insert";
   }	

   form_data.append("driver_id",driver_id);
   form_data.append("contact",contact);
   form_data.append("vehicle_id",vehicle_id);
   form_data.append("date_occured",date_occured);
   form_data.append("type_id",type_id);
   form_data.append("stage_id",stage_id);
   form_data.append("reference",reference);
   form_data.append("description",description);
   form_data.append("other_person",other_person);
   form_data.append("other_vehicle",other_vehicle);
   form_data.append("user_id",user_id);
   form_data.append("other_notes",other_notes);
   form_data.append("action","VehicleaccidentForm");
   form_data.append("status",status);
   
    event.preventDefault();
    $.ajax({
     url: 'InsertData.php', 
     type: 'post',
     data: form_data,
     dataType: 'json',
     contentType: false,
     processData: false,
     success: function (data)
    {
        if(data.status==1)
        {
            myAlert(data.title + "@#@" + data.message + "@#@success");
        }
        else
        {
            myAlert(data.title + "@#@" + data.message + "@#@danger");
        }
    }
});
   
}
 
$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();

    $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': function(d) 
                        {
                            d.action = 'loadaccidenttabledata';
                            d.driver_name_serch = $('#driver_name_serch').val();
                            d.vehicle_serch = $('#vehicle_serch').val();
                            d.stage_serch = $('#stage_serch').val();
                            d.status_serch = $('#status_serch').val();
                        }
                // 'data': {
                //     'action': 'loadaccidenttabledata'
                // }
            },
            'columns': [
	            { data: 'stage_id' },
	            { data: 'driver_id' },
	            { data: 'vehicle_id' },
	            { data: 'registration_number' },
	            { data: 'reference' },
                { data: 'date_occured' },
                { data: 'reported' },
                { data: 'type_id' },
                { data: 'other_person' },
                { data: 'status' },
                { data: 'action' }
            ]
        });
});

function loadtable()
    {
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }

 function edit(id)
 {
        $('#id').val(id);
        ShowHideDiv('view');
        $.ajax({
            type: "POST",
            url: "loaddata1.php",
            data: {action : 'VehicleaccidentUpdateData', id: id},
            dataType: 'json',
            success: function(data) {
                $result_data = data.statusdata;
                var a = $result_data['date'];
                var dateVal = new Date(a);
                var day = dateVal.getDate().toString().padStart(2, "0");
                var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
                var hour = dateVal.getHours().toString().padStart(2, "0");
                var minute = dateVal.getMinutes().toString().padStart(2, "0");
                var sec = dateVal.getSeconds().toString().padStart(2, "0");
                var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
                var inputDate = dateVal.getFullYear() + "-" + (month) + "-" + (day) + "T" + (hour) + ":" + (minute) + ":" + (sec) + "." + (ms);
                var d = $result_data['type'];
                var s = $result_data['stage'];
                var c = $result_data['driver'];
                $('#type_id option[value="'+d+'"]').attr("selected", "selected");
                $('#stage_id option[value="'+s+'"]').attr("selected", "selected");
                $('#driver_id option[value="'+c+'"]').attr("selected", "selected");
                $('#vehicle_id').val($result_data['vehicle']);
                $('#date_occured').val(inputDate);
                // $('#type_id').val($result_data['type_id']);
                // $('#stage_id').val($result_data['stage_id']);
                $('#reference').val($result_data['reference']);
                $('#description').val($result_data['description']);
                $('#other_person').val($result_data['other_person']);
                $('#other_vehicle').val($result_data['other_vehicle']);
                $('#contact').val($result_data['contact']);
                // ('#other_notes').text($result_data['other_notes']);
                $("textarea#other_notes").val($result_data['other_notes']);
                $("#accident_image").hide();
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
        data: {action : 'accidentDeleteData', id: id},
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
    $('#accidentForm')[0].reset(); 
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