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
$page_title="Accident";
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
    <style>

    @media only screen and (max-width: 600px){
  
      .dataTables_filter {
        float: left;
        margin-top: 10px;
        }
    }
    </style>
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
                                    <h4>My accidents</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                <thead class="default">
                                <tr role="row">
                                    
                                    <th>Sr No.</th>
                                    <th>Vehicle</th>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Info</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                        </table>
                        <br>
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

        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadrerportaccidenttabledata'
                }
            },
            'columns': [
                { data: 'srno' },
                { data: 'vehicle_plat_number' },
                { data: 'date' },
                { data: 'reference' },
                { data: 'info' },
                { data: 'Action' }
            ]
        });
});



    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'reportaccidentDeleteData', id: id},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                   var table = $('#myTable').DataTable();
                   table.ajax.reload();
                    myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                }
                else
                {
                    myAlert("Delete @#@ Data can not been deleted.@#@danger");
                }
                
            }

        });
    }
  

</script>

</body>

</html>