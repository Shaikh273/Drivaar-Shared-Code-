<?php
include 'DB/config.php';

    $page_title = "Inspection Detail";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_GET['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT vi.*,v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,DATE_FORMAT(vi.`insert_date`,'%D %M%, %Y') as inspectioninsertdate FROM `tbl_vehicleinspection` vi
        	LEFT JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`  
        	LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
        	LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
        	LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` 
        	LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
        	LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
        	WHERE vi.`id`=".$id;
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
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
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
                      	
                        <div class="card  col-md-12" style="border: 1px solid #d1d5db;">   
                            <div class="card-header" style="background-color: #fff;">
                                <div class="d-flex justify-content-between align-items-center">
                                  <div class="header">Inspection details <?php echo ($cntresult['inspectioninsertdate']);?></div>
                                </div>
                            </div>
                            <div class="card-body">
								<div class="row">
									<div class="col-md-4">
							            <div class="card mb-4"  style="margin-top: 7px;">
								            <div class="card-header">
									            <div class="d-flex justify-content-between align-items-center">
									                <div>
									                    <h6 class="m-0">Inspection Details</h6>
									                </div>
									            </div>
								        	</div>
								    
								            <div class="card-body">
									            <div class="row mb-3">
									                <div class="col-md-4 text-muted text-right">Vehicle:</div>
									                <div class="col-md-8">
									                    <a href=""><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></a>
									                </div>
									            </div>
									            <div class="row mb-3">
									                <div class="col-md-4 text-muted text-right">Submitted:</div>
									                <div class="col-md-8"><u>Sun, Jun 2021, 08:43 AM</u></div>
									            </div>
									            <div class="row mb-3">
									                <div class="col-md-4 text-muted text-right">Driver:</div>
									                <div class="col-md-8">
									                    <a href="https://bryanstonlogistics.karavelo.co.uk/users/6761">Dean Rahaman</a>
									                </div>
									            </div>
									            <div class="row mb-3">
									                <div class="col-md-4 text-muted text-right">Signature:</div>
									                    <div class="col-md-8"></div>
									            </div>
											</div>
							    		</div>

							            <div class="card">
								            <div class="card-header ">
									            <div class="d-flex justify-content-between align-items-center">
									                <div>
									                    <h6 class="m-0">Location</h6>
									                </div>
									            </div>
								        	</div>
								    
								            <div class="position-relative" style="height: 200px;">
								                <div id="map" style="height: 200px; width: 100%;" class="mapboxgl-map"><div class="mapboxgl-canary" style="visibility: hidden;"></div><div class="mapboxgl-canvas-container mapboxgl-interactive mapboxgl-touch-drag-pan mapboxgl-touch-zoom-rotate"><canvas class="mapboxgl-canvas" tabindex="0" aria-label="Map" width="527" height="250" style="position: absolute; width: 422px; height: 200px;"></canvas></div><div class="mapboxgl-control-container"><div class="mapboxgl-ctrl-top-left"></div><div class="mapboxgl-ctrl-top-right"></div><div class="mapboxgl-ctrl-bottom-left"><div class="mapboxgl-ctrl" style="display: block;"><a class="mapboxgl-ctrl-logo" target="_blank" rel="noopener nofollow" href="https://www.mapbox.com/" aria-label="Mapbox logo"></a></div></div><div class="mapboxgl-ctrl-bottom-right"></div></div></div>
								            </div>
							    		</div>
						        	</div>

									<div class="col-md-8">
										<div class="card">
											<div class="card-header">
												<div class="row">
													<div class="col-md-8">
														<h6>Inspection Items</h6>
													</div>
													<div class="col-md-4 text-right">	
														<h6 class=""><i class="fas fa-tachometer-alt pr-1"></i>Odometer: <span class="font-weight-bold"><?php echo $cntresult['odometer'];?>mi</span></h6>
													</div>	
												</div>
											</div>
											<div class="card-body">
												<div class="table-responsive m-t-40" style="margin-top: 0px;">
							                <table id="myTable" class="dataTable table" aria-describedby="example2_info">
							                    <thead class="default">
							                        <tr role="row">
							                            <th></th>
							                            <th>Item</th>
							                            <th>Status</th>
							                        </tr>
							                    </thead>
							                    <tbody>
							                    	<?php
							                    		$mysql = new Mysql();
													    $mysql -> dbConnect();
													    $query1 = "SELECT * FROM `tbl_vehicleinspection` WHERE id=".$_GET['id'];
													    $row1 =  $mysql -> selectFreeRun($query1);
													    $result1 = mysqli_fetch_array($row1);


											            $query= "SELECT v.*,c.`name` FROM `tbl_vehicleinspection` v
																INNER JOIN `tbl_vehiclechecklist` c ON c.`id`=v.`question_id`
																WHERE v.`odometerInsert_date`='".$result1['odometerInsert_date']."' AND v.`question_id` IS NOT NULL";
													    $row =  $mysql -> selectFreeRun($query);
														while($result = mysqli_fetch_array($row))
													    {
													    	?>
													    	<tr>
									                    		<td>#<?php echo $result['question_id'];?></td>
									                    		<td><?php echo $result['name'];?></td>
									                    		<td>
										                    		<?php 
										                    			if($result['answer_type']==1)
										                    			{
										                    				?>
										                    				<span class="label label-secondary" style="color: #466442;background-color: #d2f0cd"><b>PASSED</b></span>
										                    				<?php
										                    			}
										                    			else
										                    			{
										                    				?>
										                    				<span class="label label-secondary" style="color: #7b3232;background-color: #ffe0e0"><b>FAILED</b></span>
										                    				<?php
										                    			}
										                    		?>
									                    		</td>
									                    	</tr>
									                    	<?php
													    }
							                    	?>
							                    	
							                    </tbody>
							                </table>
							                <br>
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

<?php include('footerScript.php'); ?>
<script>
 $(document).ready(function(){
 });
</script>
</body>
</html>