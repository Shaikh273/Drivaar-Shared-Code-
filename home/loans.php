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
$page_title = "Loan";

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
                                            <div class="header">Remaining Loans</div>
                                            <div><b>Total:- £<h4 id="total" style="display: inline;"></h4></b>
                                            </div>
                                       </div>
                                </div>
                                <div class="card-body">
                                  <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                        <thead class="default">
                                            <tr>
                                            <th>Type</th>    
                                            <th>Associate</th>
                                            <th>Remaining</th>
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


<?php

include('footer.php');

?>

</div>



<?php

include('footerScript.php');

?>
<script src="../assets/node_modules/datatables/datatables.min.js"></script>
<script>
$('#myTable').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'pageLength': 10,
    'ajax': {
        'url':'loadtabledata.php',
        'data': {
            'action': 'loadloandata'
        },
        'complete': function(data) {
            var obj = data['responseJSON']['aaData'];
            $("#total").text(obj[obj.length-1]['total']);
        }
    },
    'columns': [
        { data: 'type' },
        { data: 'name' },
        { data: 'remain' }
    ]
});
</script>
</body>



</html>