<?php
include 'DB/config.php';
    $page_title = "Drivaar";
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
              <div class="header">Drivers</div> 
          </div>
      </div>
      <div class="card-body col-md-6">
          <div class="table-responsive m-t-40" style="margin-top: 0px;">
              <table id="myTable" class="dataTable table table-responsive table-bordered">
                  <thead class="default">
                      <tr role="row">
                          <th>Driver</th>
                          <th>Price</th>
                      </tr>
                  </thead>
                  <tbody>
                    <?php
                      $mysql = new Mysql();
                      $mysql -> dbConnect();
                      $driverqry=  $mysql->selectFreeRun("SELECT a.*,c.`name` FROM `tbl_vehiclerental_agreement` a INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver_id` WHERE a.`isdelete`=0 AND a.`vehicle_id`=".$id);
                      while($drvresult = mysqli_fetch_array($driverqry))
                      {
                        ?>
                        <tr>
                          <td><?php echo $drvresult['name'];?></td>
                          <td><b>£<?php echo $drvresult['price_per_day'];?></b></td>
                        </tr>
                        <?php
                      }
                      $mysql -> dbDisConnect();
                    ?>
                    
                  </tbody>
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
      var vid = <?php echo $id ?>;
	});
</script>
</body>
</html>