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
$page_title = "Vehicle Inspection Report";

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
                        <div class="card col-md-12 divborder">
                            <div class="card-header mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="m-0">Vehicle Inspections conducted by drivers per day for the last 6 weeks
                                        <div class="text-yellow-600 mt-1 animate-pulse"><i class='fa fa-exclamation-triangle' style='color: red;'> Scroll to the right to see most recent inspections.</i></div></h6>
                                    </div>
                                </div>
                            </div>
                                <div class="p-4">
                                    <div class="d-flex items-center mb-2">
                                        <div class="border h-6 w-6 text-center text-yellow-500 mr-2"></div>
                                        Day Off
                                    </div>
                                    <div class="d-flex items-center mb-2">
                                        <div class="bg-red-100 border h-6 w-6 text-center text-red-500 mr-2"><i class="fas fa-times"></i>
                                    </div>
                                    No inspection made
                                    </div>
                                    <div class="d-flex items-center">
                                        <div class="bg-green-100 border h-6 w-6 text-center text-green-800 mr-2"><i class="fas fa-check"></i>
                                        </div>
                                        Vehicle inspected
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">    
            			    <table id="myTable" role="grid" aria-describedby="example2_info" class="table table-bordered">
            					<thead class="default">
            					  <tr role="row">
            						<th style="width:200px">Driver</th>
            						<?php
                						for($i = 15; $i >= 0; $i--) {
                                            $d =  Date('d/m', strtotime('-'. $i .' days'));
                                            echo "<th style='background-color: #f7d694;'>".$d."</th>";
                						}
            						?>
            					  </tr>
            					</thead>
            					
            					<tbody id="depotdata">
            					    
            					</tbody>
            			  	</table>
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
<script src="../assets/node_modules/datatables/datatables.min.js"></script>
<script>
var userid = '<?php echo $userid ?>';
$.ajax({
    type: "POST",
    url: "loaddata.php",
    dataType:"JSON",
    data: {'action':'inspectionreportdata',userid:userid},
    success: function(data) 
    {
        //var obj = data;
        //var obj = JSON.parse(JSON.stringify(data));
        //console.log(obj);
        //$("tbody").html(data.div);
        //markup = "<tr><td> + information + </td></tr>";
        var tableBody = $("table tbody");
        tableBody.append(data.div);
    }
});
</script>
</body>



</html>