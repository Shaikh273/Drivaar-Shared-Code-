<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=124;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
      $userid=$_SESSION['userid'];
    }
    else
    {
      header("location: login.php");       
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
                    <form action="" method="post" id="form">
                        <div class="row">
                            
                            <div class="col-md-4">
                                <div class="input-daterange input-group" id="date-range">
                                    <input type="text" class="form-control" name="start_date" id="start_date">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="icon-calender"></i></span>
                                        <span class="input-group-text bg-info b-0 text-white">TO</span>
                                    </div>
                                    <input type="text" class="form-control" name="end_date" id="end_date">
                                    <div class="input-group-append">
                                      <span class="input-group-text"><i class="icon-calender"></i></span>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-md-1">
                                <button type="button" class="btn btn-info" id="filter">Filter</button>
                            </div>
                           
                      </div>
                      </form> 
                      <br>
                        <div class="card col-md-12">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Routes</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                            <thead class="default">
                                                <tr>
                                                <th>Route</th>
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
                start_date: function() { return $('#start_date').val(); },
                end_date: function() { return $('#end_date').val(); },
                action: 'loadroutestabledata'
                
            },
        },
        'destroy': true,
        'columns': [
            { data: 'route' },
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