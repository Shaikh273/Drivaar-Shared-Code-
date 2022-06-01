
<?php
$page_title="Vehicles Expiring Documents";
$page_id=48;
if(!isset($_SESSION)) 
{
    session_start();
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
   

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
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">

</head>
<body class="skin-default-dark fixed-layout">
<?php
include('loader.php');
?>
<div id="main-wrapper">
<?php
    include('header.php');
    // include('menu.php');
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Expiring Documents</div>
               

                </div>
            </div>            
            <div class="card-body" >
                
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th data-orderable="false">Vehicle</th>
                                    <th data-orderable="false">Document</th>
                                    <th data-orderable="false">Expires</th>
                                    <th data-orderable="false">Status</th>
                                    <th data-orderable="false">View</th>
                                    
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>

            </div>
        </div>        
    </main>
</div>
</div>

<?php
    include('footer.php');
?>
</div>

<?php
include('footerScript.php');
?>
 <script type="text/javascript">


    $(document).ready(function(){

        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadexpiredocdata'
                        }
                    },
                    'columns': [
                        { data: 'vehicle' },
                        { data: 'document' },
                        { data: 'expires' },
                        { data: 'status' },
                        { data: 'view' }
                        
                    ]
        });

    });
  
    </script>
</body>

</html>