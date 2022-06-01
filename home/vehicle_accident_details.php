<?php
include 'DB/config.php';
$page_title="Vehicle accident";
$page_id = 64;
    if(!isset($_SESSION)) 
    { 
        session_start();
        
    }
    if($_SESSION['userid']==1)
	    {
	       $userid='%'; 
	    }
	    else
	    {
	       $userid = $_SESSION['userid']; 
	    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        
    }else
    {
         header("location: login.php");  
    }

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_GET['aid'];

    $sql = "SELECT a.*,vs.`name` as stagename,va.`name` as typename,v.`registration_number`,s.`name` as suppliername,c.`name` as contractorname, DATE_FORMAT(a.`date_occured`,'%D %M%, %Y') as date1  FROM `tbl_accident` a
    INNER JOIN `tbl_contractor` c ON c.`id` = a.`driver_id`
    INNER JOIN `tbl_vehicleaccidentstage` vs ON vs.`id` = a.`stage_id`
    INNER JOIN `tbl_vehicletypeaccident` va ON va.`id` = a.`type_id` 
    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id` = v.`supplier_id`
    WHERE a.`isdelete`=0 AND a.`user_id` LIKE ('".$userid."') AND a.`id`=$id";
    $fire1 =  $mysql -> selectFreeRun($sql);
    $userresult1 = mysqli_fetch_array($fire1);
    // $mysql -> dbDisConnect();
    
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
<div class="page-wrapper p-0">
<div class="">
    <main class="animated">
    	<div class="row">
    		<div class="col-md-12">
        		<div class="card">  
        			<div class="card-header" style="background-color: rgb(255 236 230);">
               <h2 class="align-items-center text-xl" style="line-height: 1;">Accident</h2>
	        	 <div class="row d-flex align-items-center pl-2 border-bottom" title="Accident" style="">
			        <div class="d-flex align-items-center">
					    <small class="mr-3 text-grey-darkest d-inline-block">
					    	<i class="fas fa-bell pr-1"></i> <?php echo $userresult1['typename'];?>
						</small>
					    <small class="mr-3 text-grey-darkest d-inline-block">
						    <i class="fas fa-car pr-1"></i> Driver Accident Form
						</small>
			            <small class="mr-3 text-grey-darkest d-inline-block">
						    <i class="fa fa-user pr-1" aria-hidden="true"></i> <?php echo $userresult1['contractorname'];?>
						</small>
					</div>
					<div class="col text-right">
		           		<button type="submit" class="btn btn-secondary">Close Accident</button>	

	            		<a href="" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Accident</a>
	        		</div>

        		</div>
            		</div>
            		<div class="card-body" id="AddFormDiv">
    					<div class="row">
        					<div class="col-md-12">
        						<input type="hidden" name="aid" id="aid" value="<?php echo $_GET['aid'];?>">
        						<div class="row">
						  			<div class="col-3">
						    	<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><span><i class="fas fa-list mr-2" style="color: black;font-size: 12px;"></i><span class="pl-1">Details</span></span></a>
						      <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><span><i class="fas fa-image mr-2" style="font-size: 14px;"></i>Pictures</span></a>
						      <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false"><span><i class="fas fa-hand-holding-usd mr-2" style="font-size: 14px;"></i>Finance</span></a>
						    	</div>
						  			</div>
						  			<div class="col-9">
						    			<div class="tab-content" id="v-pills-tabContent">
							      			<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
							      			<div class="card">
							      		
							      				<div class="card-body">
							      					<div class="row">
							      						<div class="col-md-5">
							      						<div class="card border rounded border border-primary p-3">	
								      					<div class="d-flex">
								      						<i class="fas fa-caret-down mr-2"></i><h5 class="font-weight-bold">Accident Details</h5>
								      					</div>	
									      					<?php
										      				$mysql = new Mysql();
				                                            $mysql -> dbConnect();
				                                            $aid =$_GET['aid'];
				                                            $exquery = "SELECT * FROM `tbl_accident` WHERE `id`=$aid AND `isdelete`=0 AND `isactive`=0";
				                                            $exrow =  $mysql -> selectFreeRun($exquery);
				                                            $fetch = mysqli_fetch_array($exrow);
									      					?>
											      			<div class="row mt-2">
											      				<div class="col-md-3">
											      					<h5>Vehicle</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['vehicle_id'];?>
											      				</div>
											      			</div>
											      			<div class="row">
											      				<div class="col-md-3">
											      					<h5>Driver</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['driver_id'];?>
											      				</div>
											      			</div>
											      			<div class="row">
											      				<div class="col-md-3">
											      					<h5>Date Occurred</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['date_occured'];?>
											      				</div>
											      			</div>
											      			<div class="row">
											      				<div class="col-md-3">
											      					<h5>Description</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['description'];?>
											      				</div>
											      			</div>
											      			<div class="d-flex mt-2">
									      						<i class="fas fa-caret-down mr-2"></i><h5 class="font-weight-bold">Other Person</h5>
									      					</div>
											      			<div class="row mt-2">
											      				<div class="col-md-3">
											      					<h5>Name</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['other_person'];?>
											      				</div>
											      			</div>
											      			<div class="row">
											      				<div class="col-md-3">
											      					<h5>Reg. Plate</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['other_vehicle'];?>
											      				</div>
											      			</div>
											      			<div class="row">
											      				<div class="col-md-3">
											      					<h5>Notes</h5>
											      				</div>
											      				<div class="col-md-9">
											      					<?php echo $fetch['other_notes'];?>
											      				</div>
											      			</div>
											      		</div>	


							      						</div>
							      						<div class="col-md-7">
							      							<form method="post" name="addcommetForm" id="addcommetForm" action="#">
							      								<input type="hidden" name="accident_id" id="accident_id" value="<?php echo $_GET['aid'];?>">
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 600px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modelbody">
      	<input type="hidden" name="status" id="status" value="0">
      	<input type="hidden" name="commetid" id="commetid">
          <div class="form-group">
            <label class="cursor-pointer">Title</label>
	    	<input type="text" class="form-control"  name="title" id="title" placeholder="Optional title">
          </div>
          <div class="form-group">
            <label class=" cursor-pointer">Comment</label>
	    <textarea class="form-control" name="commet" id="commet" rows="4"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" value="0" id="submitcommet" onclick="addcommet(this.value);">Add Comment</button>
      </div>
    </div>
  </div>
</div>
					    									</form>
							      							<div class="card border rounded">
			            										<div class="card-header p-2" style="background-color: #fb9678;">
			            											<div class="d-flex justify-content-between align-items-center">
			                    										<h6 class="m-0">Comments (0)</h6>
			                    										<div class="text-right">
						        											<a href="#" class="btn btn-secondary btn-sm" id="addcommet" data-toggle="modal" data-target="#exampleModal">+ Add Comment</a>
						    											</div>
			                                    					</div>
			                                    				</div> 


					            								<div class="card-body">
					            									
						                            				<div class="text-center">
						                    							<div class="empty">
						        										<table id="myTable" class="display dataTable table table-responsive table-bordered" role="grid" aria-describedby="example2_info">
												                            <thead class="default">
												                                <tr role="row">
												                                	<th>Title</th>
												                                	<th>Commet</th>
												                                    <th data-orderable="false">Action</th>
												                                </tr>
												                            </thead>
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
							      	
							      	
								      		<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
								      			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="width: 600px;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Pictures</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modelbody2">
      	<form method='post' action='' enctype="multipart/form-data">
      	<!-- <input type="hidden" name="status" id="status" value="0">
      	<input type="hidden" name="commetid" id="commetid"> -->
          <div class="form-group">
            <label class="cursor-pointer">Upload Image</label>
	    	<input type="file" class="form-control p-1" multiple  name="files[]" id="image" placeholder="">
          </div>
         </form> 
         <div id='preview'></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" value="0" id="submiticture">Upload</button>
      </div>
    </div>
  </div>
</div>
								      		<div class="card border rounded">
								      			<div class="card-header p-2" style="background-color: #fb9678;">
        											<div class="d-flex justify-content-between align-items-center">
                										<h6 class="m-0">Pictures</h6>
                										<div class="text-right">
		        											<a href="#" class="btn btn-secondary btn-sm" id="addcommet" data-toggle="modal" data-target="#exampleModal2">+ Add Pictures</a>
		    											</div>
                                					</div>
                                				</div> 
                                				<div class="card-body">
                                					<table id="myTable1" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
							                            <thead class="default">
							                                <tr role="row">
							                                	<th>Image</th>
							                                    <th data-orderable="false">Action</th>
							                                </tr>
							                            </thead>
	                        						</table>
                                				</div>   
								      		</div>	
								      		
								      		</div>
								      		<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
								      		<div class="row">
								      			<div class="col-md-5">
								      				<div class="card border">
										      			<div class="card-body p-3">
														<h5>Accident Cost</h5>
										      			<label class="mt-2">Amount</label>
										      			<div class="input-group">
			                            					<div class="input-group-prepend">
											                    <div class="input-group-text">
											                        £
											                    </div>
			                								</div>
			            									<input type="text" class="form-control" name="cost" value="0" id="cost">
			                    						</div>
			                    						<button class="btn btn-secondary mt-3 mb-2">Credit excess to Driver</button>
			                    					</div>
			                    						<div class="card-footer">
			                    							<button class="btn btn-primary">Update</button>
			                    						</div>
								      				</div>
								      			</div>
								      			<div class="col-md-7">
								      				<div class="card">
								      					<div class="card-header">
								      						<h5>Driver Instalments</h5>
								      					</div>
								      					<div class="card-body"></div>
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
        	</div>
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
$(document).ready(function(){
    $("#AddDiv").hide();
    var aid = $('#aid').val();

    $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': {
                    'action': 'loadaccidentcommettabledata',
                    'aid':aid
                }
            },
            'columns': [
	            { data: 'title' },
	            { data: 'commet' },	            
                { data: 'action' }
            ]
        });

    $('#myTable1').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': {
                    'action': 'loadaccidentimagetabledata',
                    'aid':aid
                }
            },
            'columns': [
	            { data: 'file' },
                { data: 'action' }
            ]
        });
});

function addcommet(val)
{
	var value = val;
	$.ajax({
        url: "InsertData1.php", 
        type: "POST", 
        dataType:"JSON",            
        data: $("#addcommetForm").serialize()+"&action=VehicleAccidentCommetForm",
        cache: false,             
        processData: false,      
        success: function(data) {
            if(data.status==1)
            {
            	var table = $('#myTable').DataTable();
               	table.ajax.reload();
                myAlert(data.title + "@#@" + data.message + "@#@success");
                $('#exampleModal').modal('hide');
            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            }
        }
    });
}


$('#submiticture').click(function(){
	 var aid = $('#aid').val();

   var form_data = new FormData();

   // Read selected files
   var totalfiles = document.getElementById('image').files.length;
   for (var index = 0; index < totalfiles; index++) {
      form_data.append("files[]", document.getElementById('image').files[index]);
   }
   form_data.append("action","insertAccidentImageTabledata");
   form_data.append("aid",aid);

   // AJAX request
   $.ajax({
     url: 'InsertData1.php', 
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
            for(var index = 0; index < data.length; index++) 
            {
	         	var src = data[index];
	         // Add img element in <div id='preview'>
	         $('#preview').append('<img src="'+src+'" width="200px;" height="200px">');
       		}
       		$('#exampleModal2').modal('hide');
       		var table = $('#myTable1').DataTable();
               table.ajax.reload();

       	}	
       else
       {
       		myAlert(data.title + "@#@" + data.message + "@#@danger");
       }
     }
   });

});

function editcommet(id)
{

    $('#commetid').val(id);

    // ShowHideDiv('view');

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {action : 'accidentcommetUpdateData', id: id},

        dataType: 'json',

        success: function(data) {

        	$result_data = data.statusdata;

        	$('#exampleModal').modal('show');

            $('#title').val($result_data['title1']);
          
            $('#commet').val($result_data['commet']);

            $("#status").val(1);

            // $("#addcommet").attr('name', 'update');

            $("#submitcommet").text('Update');

        }

    });
}

function deleterowcommet(id)
{

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {action : 'accidentcommetDeleteData', id: id},

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

function deleterowimage(id,file)
{

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {action : 'accidentimageDeleteData', id: id,file:file},

        dataType: 'json',

        success: function(data) {
            if(data.status==1)
            {
               var table = $('#myTable1').DataTable();
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

</script>
</body>
</html>