<?php
include 'DB/config.php';
$page_title="Training Detail";

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
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: userlogin.php");
        }
        else
        {
           header("location: login.php");  
        }
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

	            		<a href="https://bryanstonlogistics.karavelo.co.uk/accidents/11/edit" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Accident</a>
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
						      <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false"><span><i class="fas fa-image mr-2" style="font-size: 14px;"></i>Sessions</span></a>
						    	</div>
						  			</div>
						  			<div class="col-9">
						    			<div class="tab-content" id="v-pills-tabContent">
							      			<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
							      			<div class="row col-md-6">
							      				<div class="card">
								      				<div class="card-body">
                            						<div class="form-group">
						                              <label class=" cursor-pointer"> Name *</label>

						                              <input type="text" class="form-control" name="name" id="name" required>
                            						</div>
						                            <div class="form-group">
						                                <label class=" cursor-pointer">Refreshment period:</label><br>
						                                <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" id="refreshment" name="refreshment">
						                                    <option value="1-year">1 Year</option>
						                                    <option value="9-month">9 Months</option>
						                                    <option value="6-month">6 Months</option>
						                                    <option value="3-month">3 Months</option>
						                                    <option value="1-month">1 Months</option>
						                                </select> 
						                            </div>   
                            						<button type="" name="" class="btn btn-success" id="submit">Submit</button>
		    												</div>
								      				</div>
								      			</div>
							      			</div>
							      			</div>
							      	
							      	
								      		<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
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


</script>
</body>
</html>