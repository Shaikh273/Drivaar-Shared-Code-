<?php

$page_title = "Vehicle Garage Report";

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
                        <div class="card">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Damages</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                     
                                  <table class="table table-responsive" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th>Date</th>
                                                <th>Vehicle Reg.</th>
                                                <th>Description</th>
                                                <th>Depot</th>
                                                <th>DA Name</th>
                                                <th>Garage</th>
                                                <th>Invoice Amount</th>
                                                <th>Excess Amount</th>
                                                <th>Diff</th>
                                                <th>Reference</th>
                                                <th>Paid</th>
                                                <th>Subtotal</th>
                                            </tr>
                                            </thead>
                                            <tbody id="extendedTable">
                                                
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


<?php include('footer.php');?>

</div>



<?php include('footerScript.php');?>
<script>
    
$(document).ready(function(){
      
    // $('#myTable').DataTable({
    //     'processing': true,
    //     'serverSide': true,
    //     'serverMethod': 'post',
    //     'ajax': {
    //         'url':'loadtabledata1.php',
    //         'data': {
    //             'action': 'loadleaverequesttabledata',
    //         }
    //     },
    //     'columns': [
    //         { data: 'name' },
    //         { data: 'period' },
    //         { data: 'status' },
    //         { data: 'action' }
            
    //     ]
    // });

});
</script>


</body>
</html>