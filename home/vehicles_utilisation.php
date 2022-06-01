<?php
if(session_status() === PHP_SESSION_NONE)
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

include 'DB/config.php';
$page_title = "Drivaar";

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
    .titlehead {
          font-size: 28px;
          font-weight: 500;
    }
</style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>

<div id="main-wrapper">

<?php

include('header.php');

?>

<div class="page-wrapper">
  <div class="container-fluid">
      <div class="row"> 
          <div class="col-md-12">
              <div class="card">
                  <div class="card-body">
                    <?php include('report.php'); ?>
                        <div class="card col-md-12">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Vehicle Utilisation</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <table id="myTable" class="dataTable table table-responsive table-bordered">
                                            <thead class="default">
                                                <tr>
                                                  <th width="10%">Supplier</th>
                                                  <th width="5%">Total</th>
                                                  <th width="5%">Active</th>
                                                  <th width="5%">Available</th>
                                                  <th width="5%">Inactive</th>
                                                  <th width="5%">Garage</th>
                                                  <th width="5%">Returned</th>
                                                  <th width="5%">Theft</th>
                                                  <th width="5%">UnRoadWorthy</th>
                                                  <th width="5%">Grounded-Insurance Claim</th>
                                                  <th width="15%">Utilisation</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php 
                                                $mysql = new Mysql();
                                                $mysql -> dbConnect();
                                                $qry="SELECT * FROM `tbl_vehiclesupplier` WHERE `isactive`=0 AND `isdelete`=0";
                                                $row =  $mysql -> selectFreeRun($qry);
                                                while($result = mysqli_fetch_array($row))
                                                {
                                                  $statusqry="SELECT 
                                                              COUNT(DISTINCT IF(v.`status` = 1,v.`id`,NULL)) AS Active,
                                                              COUNT(DISTINCT IF(v.`status` = 2,v.`id`,NULL)) AS Inactive,
                                                              COUNT(DISTINCT IF(v.`status` = 3,v.`id`,NULL)) AS Garage,
                                                              COUNT(DISTINCT IF(v.`status` = 4,v.`id`,NULL)) AS Available,
                                                              COUNT(DISTINCT IF(v.`status` = 5,v.`id`,NULL)) AS Returned,
                                                              COUNT(DISTINCT IF(v.`status` = 6,v.`id`,NULL)) AS Theft,
                                                              COUNT(DISTINCT IF(v.`status` = 7,v.`id`,NULL)) AS OutOfService,
                                                              COUNT(DISTINCT IF(v.`status` = 8,v.`id`,NULL)) AS GroundedInsuranceClaim
                                                              FROM `tbl_vehiclesupplier` vs
                                                              INNER JOIN `tbl_vehicles` v ON v.`supplier_id`=vs.`id` AND v.`isdelete`=0
                                                              INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL 
                                                              WHERE vs.`id`=".$result['id'];
                                                  $strow =  $mysql -> selectFreeRun($statusqry);
                                                  $stresult = mysqli_fetch_array($strow);
                                                  $total = $stresult['Available']+$stresult['Inactive']+$stresult['Garage']+$stresult['Returned']+$stresult['Theft']+$stresult['OutOfService']+$stresult['GroundedInsuranceClaim'];
                                                  $totalwid= "1000";
                                                  if($total==0)
                                                  {
                                                     $count=$stresult['Active'];
                                                     $to=($count/$totalwid)*100;
                                                     $clr='progress-bar bg-success';
                                                  }
                                                  else
                                                  {
                                                    $count=$total;
                                                    $to= ($count/$totalwid)*100;
                                                    $clr='progress-bar bg-danger';
                                                  }
                                                  ?>
                                                      <tr>
                                                        <td><b><?php echo $result['name']?></b></td>
                                                        <td><?php echo  $stresult['Active']+$total;?></td>
                                                        <td><?php echo $stresult['Active']?></td>
                                                        <td><?php echo $stresult['Available']?></td>
                                                        <td><?php echo $stresult['Inactive']?></td>
                                                        <td><?php echo $stresult['Garage']?></td>
                                                        <td><?php echo $stresult['Returned']?></td>
                                                        <td><?php echo $stresult['Theft']?></td>
                                                        <td><?php echo $stresult['OutOfService']?></td>
                                                        <td><?php echo $stresult['GroundedInsuranceClaim']?></td>
                                                        <td class="border">
                                                            <div class="progress">
                                                                <div class="<?php echo $clr?>" role="progressbar" style="width:<?php echo $to?>;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?php echo $to?></div>
                                                            </div>
                                                        </td>
                                                      </tr>
                                                  <?php
                                                }
                                                $mysql -> dbDisconnect();
                                              ?>
                                              
                                            </tbody>
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


<?php

include('footer.php');

?>

</div>



<?php

include('footerScript.php');

?>
</body>
</html>