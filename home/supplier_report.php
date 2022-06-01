<?php

$page_title = "Supplier Report";

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
                      <br>
                        <div class="card col-md-12">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Vehicle Supplier</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                            <thead class="default">
                                                <tr>
                                                <th>Supplier</th>
                                                <th>Count</th>
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


<?php

include('footer.php');

?>

</div>
<!-- Date Picker Plugin JavaScript -->
<script src="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="../assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
<script src="../assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>

<?php

include('footerScript.php');

?>

<script>

$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();
    var table = $('#myTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'loadtabledata.php',
            'data': {
                action: 'loadsupplierdata'  
            },
        },
        'destroy': true,
        'columns': [
            { data: 'supplier' },
            { data: 'count' }
           
        ]
    });
    
    $('#filter').on('click',function(){
        table.ajax.reload();
    });
});

</script>


</body>



</html>