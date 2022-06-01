<?php

$page_title = "Audits";

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

<?php include('header.php');?>

<div class="page-wrapper">
  <div class="container-fluid">
      <div class="row"> 
          <div class="col-md-12">
              <div class="card">
                  
                  <div class="card-body">
                    <?php include('report.php'); ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">   
                                <div class="card-header p-1" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Disputes by Depot</div>                 
                                    </div>
                                </div>
                                <div class="card-body" id="ViewFormDiv">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                
                                            </tr>
                                            </thead>
                                           
                                  </table>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">   
                                <div class="card-header p-1" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Average Process Time by Depot</div>                 
                                    </div>
                                </div>
                                <div class="card-body" id="ViewFormDiv">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                
                                            </tr>
                                            </thead>
                                           
                                  </table>
                                  
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">   
                                <div class="card-header p-1" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="header">Top 10 Supervisors With Most Disputes</div>
                                    </div>
                                </div>
                                <div class="card-body" id="ViewFormDiv">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                
                                            </tr>
                                            </thead>
                                           
                                  </table>
                                  
                                </div>
                            </div>
                            <div class="card">   
                                <div class="card-header p-1" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Top 10 Drivers with disputes</div>                 
                                    </div>
                                </div>
                                <div class="card-body" id="ViewFormDiv">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                
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
  </div>
</div>


<?php include('footer.php');?>

</div>



<?php include('footerScript.php');?>

<script>
    
$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();
      
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

function ShowHideDiv(divValue)
    {
        // $('#offencesForm')[0].reset(); 
        if(divValue == 'view')
        {
            $("#submit").attr('name', 'insert');
            $("#submit").text('Submit');
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
</script>

</body>
</html>