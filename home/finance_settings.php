<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=144;
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
<?php
$page_title="Finance Settings";
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
    <style>
        
    </style>
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
                        <div class="row">
                            <div class="card col-md-12">
                                <div class="card-body">
                                    <?php include('setting.php'); ?>
                                    <div class="col-md-12">
                                         <div class="card">    
                                            <div class="card-header " style="background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="header">Finance Settings</div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <form action="" method="post" id="form">
                                                            <div class="form-group row required">
                                                                <div class="col-md-4">
                                                                    <label class="d-block mb-0">Dispute period: *</label>
                                                                    <small class="text-muted">
                                                                       Number of days that a sent invoice can be disputed
                                                                    </small>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" name="period" id="period" value="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="card-footer">
                                                                <button type="submit" name="update" class="btn btn-primary" id="submit">Update</button>
                                                            </div>
                                                        </form>
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
        
    </body>
<script>

    $(function(){
        ajaxreload();
    });  
    $("#form").validate({
        rules: {
            period: 'required',
        },
        messages: {
            period: "Please enter dispute period",
        },
        submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "loaddata.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#form").serialize()+"&action=updatefinance_settingsdetails",
                success: function(data) {
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#form')[0].reset();
                        ajaxreload();
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
           // return false;
        }
    });
    function ajaxreload() {
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data: {action : 'loadfinance_settingsdetails'},
            success:function(response){  
                $("#period").val(response['period']);
            }
        });
    }
                
    </script>
</html>