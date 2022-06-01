<?php
$page_title = "Instalments";
include 'DB/config.php';
$page_id = 57;
if (!isset($_SESSION)) 
{
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
    
    if($userid == 1) {
        $uid = "%";
    } else {
        $uid = $userid;
    }   
} 
else {
    header("location: login.php");   
}
//$date_string = "2012-08-29";
$week = date("W");

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
    <?php 
    include('loader.php');
    ?>
    <div id="main-wrapper">
        <?php
        include('header.php');
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title titlehead">Scheduled Instalments</h6>
                                <hr><br>
                                <div>
                                    <div class="card">
                                        <div class="card-header " style="background-color: rgb(255 236 230);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="header">Scheduled Instalments for this week (<label id="instalmenttotal"></label>)</div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                        <table id="myTable" class="display dataTable table table-responsive" style="font-weight: 400;" role="grid" aria-describedby="example2_info">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th>Contractor</th>
                                                        <th>Reason</th>
                                                        <th>Invoice</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
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
        <?php include('footer.php'); ?>
    </div>
    <?php
    include('footerScript.php');?>
<script type="text/javascript">
    $(document).ready(function(){
        ajaxcall();
    });
    function ajaxcall() {
         $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'destroy': true,
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadcontractorpaymenttabledata',
                     'userid':'<?php echo $uid ?>',
                     'week':<?php echo $week ?>
                },
                complete: function (data) {
                    var data1 = data['responseJSON'];
                        $("#instalmenttotal").text(data1.iTotalRecords);
                    }
            },
            'columns': [
                { data: 'Contractor' },
                { data: 'Reason' },
                { data: 'Invoice' },
                { data: 'Amount' },
                { data: 'Date' }
            ]
            
        });
    }
</script>
</body>
</html>