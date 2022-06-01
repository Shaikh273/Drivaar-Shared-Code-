<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    
}
else
{
    if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    {
       header("location: userlogin.php");
    }
    else
    {
       header("location: login.php");  
    }
}
//include('authentication.php');
include('config.php');
$page_title="Documents";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
</head>
    <body class="skin-default-dark fixed-layout">
        <?php
        
            include('loader.php');
        ?>
        
        <div id="main-wrapper" id="top">
            <?php
                include('header.php');
            ?>
            
          
            
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    
                    <div class="card">   
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">
                                    <h4>Company Documents</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                            <div class="col-md-3 ">
                                </div>
                                <div class="col-md-6">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" >
                                <thead class="default" style="width:100%">
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Name</th>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                               </thead>
                               <tbody>
                               </tbody>
                        </table>
                        <br>
                            </div>
                            </div>
                            <div class="col-md-3 ">
                            </div>
                        </div>
                       </div>
            
                    </div>
                    

                    
                   
                </div>
            </div>
                    
                    
                    
            <?php include('footer.php');?>         
        </div>
            
<?php include('footerScript.php'); ?>
<script src="dist/jq.dice-menu.js"></script>
<script type="text/javascript">

$(document).ready(function(){
    var cid=2; 
        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadtablecontractordocumentdata',
                    'cid':cid
                }
            },
            'columns': [
                { data: 'srno' },
                { data: 'name' },
                { data: 'file' },
                { data: 'action' }
            ]
        });
    });
    
</script>

</body>

</html>