<?php

$page_title = "Pending documents";

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
                        <div class="card">   
                            <div class="alert alert-warning mb-4">
                    <svg class="icon d-inline" fill="currentColor" style="width: 12px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> All amounts in the Payments Overview are inclusive with any VAT if you have contractors registered for VAT.
                </div>
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Pending documents for signing</div>
                                       </div>
                                </div>
                                <div class="card-body">
                                     
                                  <table class="table table-responsive" style="font-weight: 400;">
                                            <thead class="default">
                                                <tr>
                                                <th>Document</th>
                                                <th>Signer</th>
                                                <th>Sent</th>
                                            </tr>
                                            </thead>
                                            <tbody id="extendedTable">
                                                <tr>
                                                    <td colspan="3"><b>Sending documents coming very soon 🔥</b><br><br>Here will be showing documents which need signing from your contractors</td>
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