
<?php
$page_title="Drivaar";
include 'DB/config.php';
    $page_id=10;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
    }
    else
    {
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: login.php");
        }
        else
        {
           header("location: login.php");  
        }
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
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Review onboarding applicants</div>
                </div>
            </div>
            <div class="card-body">
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>NINO</th>
                                    <th>UTR</th>
                                    <th>Depot</th>
                                    <th>Arrears</th>
                                    <th data-orderable="false">Date</th>
                                    <th data-orderable="false"></th>
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
        loadtable();
    });


    function loadtable()
    {

        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadContractorOnboardtabledata'
                }
            },
            'columns': [
                { data: 'name' },
                { data: 'email' },
                { data: 'company_name' },
                { data: 'NINO' },
                { data: 'utr' },
                { data: 'depot_type' },
                { data: 'Arrears' },
                { data: 'date' },
                { data: 'Action' }
            ]
        });
    }
    </script>
</body>

</html>