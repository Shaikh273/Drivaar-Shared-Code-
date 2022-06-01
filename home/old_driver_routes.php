<?php

$page_title = "Routes";

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
                        <div class="row">
                              <div class="col-md-4">
                                  <div class="input-group">

                                      <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">

                                      <div class="input-group-append">

                                          <span class="input-group-text"><i class="icon-calender"></i></span>

                                      </div>
                                  </div>
                                 
                              </div>
                               <div class="col-md-1">
                                  <button type="button" class="btn btn-info">Filter</button>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group" style="margin-top: 5px;">
                                      <label class="custom-control custom-checkbox m-b-0">

                                          <input type="checkbox" class="custom-control-input">

                                          <span class="custom-control-label">Group by date</span>

                                      </label>
                                  </div>
                              </div>
                             
                      </div>
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



<?php

include('footerScript.php');

?>

<script>
$(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata1.php',
                        'data': {
                            'action': 'loadroutestabledata'
                        }
                    },
                    'columns': [
                        { data: 'route' },
                        { data: 'count' }
                       
                    ]
        });

});
</script>


</body>



</html>