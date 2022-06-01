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
$page_title="Feedback";

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
                    
                    <div class="row">
                        <div class="col-md-12">
                            
                            
                            <div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    Add Feedback
                                </div>
                                <div class="card-body">
                                    <form method="post" id="addfeedbackForm" name="addfeedbackForm" action="">
                                        <div class="form-group">
                                            <label class="cursor-pointer">Your feedback</label>
                                            <textarea class="form-control" name="feedback"></textarea>
                                        </div>
                                        <hr>
                                        <button class="btn btn-primary mt-4" type="submit" name="submit">Submit your feedback</button>
                                        <a href="#" class="btn btn-primary mt-4">Go back</a>
                                    </form>
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
<script>

$("#addfeedbackForm").validate({

            rules: {

                feedback: 'required',

            },

            messages: {
                feedback: "Please Enter Feedback",
               
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#addfeedbackForm").serialize()+"&action=addfeedbackForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
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

</body>

</html>