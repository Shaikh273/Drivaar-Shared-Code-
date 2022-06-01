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
$page_title="Security";
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
                                     <div class="card">  
                                        <div class="card-body">
                                            <div class="header"><b>Change Password</b></div>
                                            <br>
                                            <div class="row">
                                                 <div class="col-md-12">
                                                     <div class="row">
                                                        <div class="alert alert-warning alert-rounded col-sm-12"><i class="fas fa-exclamation-circle"></i> <b> WARNING! You will be logged out when successful password change to login again!.</b></div>
                                                     </div>
                                                    <form action="" method="post" id="form">
                                                        <input type="hidden" name="id" id="id" value="<?php echo $userid?>">
                                                        <div class="form-group row required">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Current password *</label>
                                                                <small class="text-muted">
                                                                    Enter your current password
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="password" id="password" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                         <div class="form-group row required">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">New password *</label>
                                                                <small class="text-muted">
                                                                    Enter your new password
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="new_password" id="new_password" value="" class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Confirm password *</label>
                                                                <small class="text-muted">
                                                                    Enter your new password again
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="confirm_password" id="confirm_password" value="" class="form-control">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                         <div class="card-footer">
                                                            <button type="submit" name="update" class="btn btn-primary" id="submit">Update</button>
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
        
    });  
    $("#form").validate({
        rules: {
            password: 'required',
            new_password: "required",
            confirm_password: {
                equalTo: "#new_password"
            }
        },
        messages: {
            password: "Please enter your Current password",
            new_password: " Enter New Password",
            confirm_password: " Enter Confirm Password Same as New Password"
        },
        submitHandler: function(form) {
            event.preventDefault();
            $.ajax({
                url: "loaddata.php", 
                type: "POST", 
                dataType:"JSON",            
                data: $("#form").serialize()+"&action=updatesecuritydetails",
                success: function(data) {
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#form')[0].reset();
                        window.location.href = 'login.php';
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        }
    });     
    </script>
</html>