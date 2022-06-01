<?php

include 'DB/config.php';
$page_title = "Drivaar";
$page_id = 5;
if (!isset($_SESSION))
{
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1))
{
    $userid=$_SESSION['userid'];  
    if($_SESSION['userid']==1)
    {
       $userid='%'; 
    }
    else
    {
       $userid = $_SESSION['userid']; 
    }
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
        .default {
            background-color: #f8fafc !important;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">

    <?php include('loader.php'); ?>

    <div id="main-wrapper">
        <?php include('header.php'); ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <div class="card">
                            <div class="card-header" style="background-color: rgb(255 236 230);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Leave Requests</div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                                    <thead class="default">
                                        <tr>
                                            <th>Name</th>
                                            <th>Period</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>

    <?php include('footerScript.php'); ?>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': 'loadtabledata1.php',
                    'data': {
                        'action': 'loadleaverequesttabledata',
                    }
                },
                'columns': [
                        { data: 'name'},
                        { data: 'period'},
                        { data: 'status'},
                        { data: 'action'}
                ]
            });
        });

        function leaveresponse(id, status) {
            var table = $('#myTable').DataTable();
            $.ajax({
                url: "loaddata1.php",
                type: "POST",
                data: {
                    'action': 'leaverequestresponsestatus',
                    'lrid': id,
                    'status': status
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        table.ajax.reload();
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                        table.ajax.reload();
                    }
                }
            });
        }
    </script>
</body>

</html>