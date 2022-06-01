<?php
include 'DB/config.php';

    $page_title = "Vehicles Inspections";
    $page_id=5;
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
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` WHERE v.`id`=".$id;
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
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="dist/css/switch.css" rel="stylesheet" />
    <style type="text/css">
        .chk {
          float: left;
          border: 1px solid red;
          border-radius: 5px;
        }
        .chkicn {
           color: #ce0909;
           margin:5px
        }
        .cross {
            float: right;
            border: 1px solid green;
            border-radius: 5px;
        }
        .crossicn {
           color: green;
           margin:8px;
        }
       .switch-toggle {
          width: 14em;
        }
        
        .switch-toggle label:not(.disabled) {
          cursor: pointer;
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
                            <div class="header">Vehicle Inspection</div>

                            <div id="inspectiondata"> 
                    
                                <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Inspections</button>
                
                                <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Inspections</button>
                    
                            </div> 

                            <!-- <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php //echo $cntresult['colorcode']?>;"></i> <?php //echo $cntresult['statusname'];?></button>
                              <a href=""> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report</button>
                              </a>
                           </div> -->
                        </div>
                    </div>
                    
                    <div class="card-body">
                      <?php include('vehicle_setting.php'); ?>
                      
                      <div class="row">
                        <div class="card col-md-12">   
                            <div class="card-header" style="background-color: #fff;">
                                <div class="d-flex justify-content-between align-items-center">
                                 <!--  <div class="header">Inspections</div> -->
                    
                                                     
                    
                                </div>
                            </div>
                            
                              <div class="card-body" id="AddFormDiv">
                                  <div class="row">
                                    <div class="col-md-12" style="height: 800px">
                                
                                    <form id="msform_0" method="post" class="msform" name="msform" action="" enctype="multipart/form-data">
                                        <input type="hidden" id="vid" name="vid" value="<?php echo $id?>">
                                        <div class="header">Vehicle Inspection - <?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                                        <br>
                                        <div class="container chkrequire" id="chkrequire"></div>
                                        <br>
                                        <fieldset style="margin: 0 10%;"> 
                                          <div class="form-body">
                                              <div class="row">
                                                  <div class="col-md-12">
                                                       <div class="form-group">
                                                         <label class="control-label">Odometer *</label>
                                                          <div class="input-group">
                                                              <input type="number" class="form-control step_0 chkErr" id="odometer" name="odometer" placeholder="" required="">
                                                          </div>
                                                          <small>Current odometer value is __________ mi</small>
                                                      </div>
                                                  </div>
                                              </div>
                                            </div>
                                          <button type="button" name="next" id="next-step_0"  class="next action-button"> Continue </button>
                                        </fieldset>
                                        <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $userNmaeLi1 =  $mysql -> selectFreeRun("SELECT `name` FROM `tbl_user` WHERE `id`=$userid");
                                                        $userNmaeLi = mysqli_fetch_array($userNmaeLi1);
                                        $expquery = "SELECT * FROM `tbl_vehiclechecklist` WHERE `isdelete`= 0 AND `isactive`= 0 ORDER BY id ASC";
                                        $exprow =  $mysql -> selectFreeRun($expquery);
                                        $i = 1;
                                        while($result = mysqli_fetch_array($exprow))
                                        {
                                      ?>
                                            <fieldset class="col-md-9 offset-md-2" style="text-align: center!important;">
                                              <div  class="header" style="font-size:22px;"><?php echo $result['name']?></div><br><br> 
                                              
                                               <!----------------------changes------------------------------->
                                               
                                              <input type="hidden" name="question_id" id="question_id" class="step_<?php echo $i;?>" value="<?php echo $result['id']?>">
                                              <input type="hidden" name="qvtId" id="qvtId" class="step_<?php echo $i;?> qvt" value="<?php echo $result['id']?>-<?php echo $_GET['vid']?>-">
                                              <input type="hidden" name="odometerInsert_date" class="step_<?php echo $i;?> oid" value="">
                                                <div class="switch-toggle switch-3 switch-candy switch" id="switch" style="margin-left: 180px; height: 40px;">
                                                    <input id="off_<?php echo $result['id']?>" name="state-d_<?php echo $i;?>" class="step_<?php echo $i;?> swt" type="radio" value="0" >
                                                    <label for="off_<?php echo $result['id']?>" onclick="" style="color: red;">NO</label>
                                                    
                                                    
                                                    <input id="na_<?php echo $result['id']?>" class="step_<?php echo $i;?> swt" name="state-d_<?php echo $i;?>" value="2" type="radio" checked="checked" disabled>
                                                    <label for="na_<?php echo $result['id']?>" class="" onclick="" >&nbsp;</label>
                                                    
                                                    <input id="on_<?php echo $result['id']?>" class="step_<?php echo $i;?> swt" name="state-d_<?php echo $i;?>" type="radio" value="1">
                                                    <label for="on_<?php echo $result['id']?>" onclick="" style="color: green;">YES</label>
                                                    
                                                    <a></a>
                                                </div>
                                                
                                                <br><br><hr><br>
                                                
                                                <div id="remarkForm-step_<?php echo $i;?>" style="display: none;"></div> 
                                                 
                                                <input type="button" style="margin-left: 0px;" name="previous" id="pre-step_<?php echo $i;?>" class="previous action-button" value="Go Back"/>
                                                <input type="button" name="next" id="next-step_<?php echo $i;?>" class="next action-button"  value="Continue" />           
                                            </fieldset>
                                        
                                    <?php
                                        $i++;
                                        
                                    }
                                    $mysql -> dbDisconnect();
                                    ?>
                                    
                                    <fieldset>
                                            <h5>Thank You <?php echo $userNmaeLi['name'];?></h5>
                                            <p>You have now provided all necessary information for inspection.

                                              
                                    
                                            </p>
                                            <input type="button" name="previous" class="previous action-button" value="Go Back" />
                                            <input type="submit" name="" class="next action-button" value="Finish" />
                                            <!-- 
                                            <button type="submit" class="next action-button"> Finish</button> -->
                                        </fieldset>
                                    </form>
                                    
                                    
                                    
                                        
                                      
                                    </div>
                                </div>
                            </div>
                            
                              <div class="card-body" id="ViewFormDiv">
                                <div class="row">
                                         
                                          <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                     <div class="form-group has-primary">
                                                    <select class="select form-control custom-select" id="depot" name="depot" onchange="loadtable();">
                                                        <option value="%">All Depot Name</option>
                                                        <?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                        
                                                        $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid` LIKE '".$uid."'";
                                                            $strow =  $mysql -> selectFreeRun($statusquery);
                                                            while($statusresult = mysqli_fetch_array($strow))
                                                            {
                                                        ?>
                                                            <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                                        <?php
                                                            }
                                                        ?>
                                                    </select>   
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                  <div class="form-group has-primary">
                                                       <input type="text" class="form-control" id="vehicle" name="vehicle" placeholder="Search by driver, vehicle reg..." value="" onchange="loadtable();">  
                                                  </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group has-primary">
                                                      <input type="date" class="form-control" id="inspectiondate" name="inspectiondate" onchange="loadtable();">
                                                    </div>
                                                </div>
                                            </div>
                                          </div>
                                      </div>
                                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                            <thead class="default">
                                                <tr role="row">
                                                    <th>Date</th>
                                                    <th>Vehicle</th>
                                                    <th>User</th>
                                                    <th>Odometer</th>
                                                    <th>Checks</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <br>
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
        var vid = $('#vid').val();
        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': function(d) 
                        {
                            d.action = 'loadvehicleInspectiontabledata';
                            d.depot = $('#depot').val();
                            d.vehicle = $('#vehicle').val();
                            d.inspectiondate = $('#inspectiondate').val();
                            d.vid = vid;
                          
                        }
                    },
                    'columns': [
                        { data: 'date' },
                        { data: 'vehicle_id' },
                        { data: 'user' },
                        { data: 'odometer' },
                        { data: 'check' },
                        { data: 'Action' }
                    ]
        });

    });

    function loadtable()
    {
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }
  
  	function showInput(value)
  	{
        var clas = value;
        $.ajax({
                url: "remark.php", 
                type: "POST", 
                dataType:"text",            
                data: {
                   'cls': clas 
                },      
                success: function(data)
                {
					document.getElementById("remarkForm-"+clas).style.display = "block";
					var input = data;
					document.getElementById("remarkForm-"+clas).innerHTML=input;       
                }
        });
  	} 
 
	$(document).on("change", ".swt", function () {
		var selectedVal = "";
		var cl3= $(this).attr("name");
		var cl4 = cl3.split(" ")[0];
		var selected = $("input[type='radio'][name='"+cl4+"']:checked");
		var selectedVal = selected.val();
	    var cl1= $(this).attr("class");
	    var cl2 = cl1.split(" ")[0];
	    
	    if(selectedVal == "1"){
	        document.getElementById("remarkForm-"+cl2).innerHTML="";
	        document.getElementById("remarkForm-"+cl2).style.display = "none";
	    }
	    else
	    {
	        showInput(cl2);
	    }
	});

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
 
	var loadFile = function(event,flg) 
	{
		var output = document.getElementById(flg+'');
		output.src = URL.createObjectURL(event.target.files[0]);
		output.onload = function() 
		{
		  URL.revokeObjectURL(output.src) // free memory
		}
	}
</script>

<script src="https://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>
<script src="script.js"></script> 

</body>
</html>