<?php

$page_title = "Drivaar";
include 'DB/config.php';
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
                                            <div class="header">Available Cars for Today</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <table id="myTable" class="dataTable table table-responsive table-bordered">
                                            <thead class="default">
                                                <tr>
                                                  <th>Vehicle Class</th>
                                                  <th>Available</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php 
                                                $date=date('Y-m-d');
                                                $mysql = new Mysql();
                                                $mysql -> dbConnect();
                                                $qry="SELECT * FROM `tbl_vehicletype` WHERE `isdelete`=0 AND `isactive`=0";
                                                $row =  $mysql -> selectFreeRun($qry);
                                                $total=0;
                                                while($result = mysqli_fetch_array($row))
                                                {
                                                  $typeqry="SELECT COUNT(DISTINCT v.`id`) as available FROM `tbl_vehicletype` vt
                                                            INNER JOIN `tbl_vehicles` v ON v.`type_id`=vt.`id` AND v.`isdelete`=0 
                                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $uid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL 
                                                            WHERE vt.`id`=".$result['id']." AND  v.insert_date LIKE '%".$date."%'";
                                                  $strow =  $mysql -> selectFreeRun($typeqry);
                                                  $stresult = mysqli_fetch_array($strow);
                                                  $cls='';
                                                  if($stresult['available']>0)
                                                  {
                                                    $cls='bg-yellow-100';
                                                  }
                                                  $total+=$stresult['available'];
                                                  ?>
                                                      <tr class="<?php echo $cls?>">
                                                        <td><?php echo $result['name']?></td>
                                                        <td><?php echo $stresult['available'];?></td>
                                                      </tr>
                                                  <?php
                                                }
                                                $mysql -> dbDisconnect();
                                              ?>
                                              <tr class="bg-blue-100">
                                                  <td><b>Total</b></td>
                                                  <td><b><?php echo $total;?></b></td>
                                              </tr>
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