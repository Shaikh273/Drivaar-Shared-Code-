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

$page_title = "Vehicle terms";

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>
        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
        <link rel="stylesheet" href="countrycode/build/css/demo.css">

        <!--<link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">-->
        <!--<link rel="stylesheet" href="../assets/node_modules/html5-editor/bootstrap-wysihtml5.css" />-->
        <!--<link rel="stylesheet" href="countrycode/build/css/demo.css">-->
       <style type="text/css">
            /*.titlehead {*/
            /*      font-size: 28px;*/
            /*      font-weight: 500;*/
            /*}*/
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
                                           <div class="card">   
                                                <?php include('setting.php'); ?> 
                                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="header">Default Vehicle Hire Terms & Conditions</div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <form method="post" id="form">
                                                        <div class="form-group">
                                                            <textarea id="mymce" name="area" ></textarea>
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
        <script src="../assets/node_modules/tinymce/tinymce.min.js"></script>
        <script>
        $(document).ready(function() {
            
            if ($("#mymce").length > 0) {
                tinymce.init({
                    selector: "textarea#mymce",
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link lists charmap print preview hr anchor pagebreak spellchecker",
                        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime  nonbreaking",
                        "save table contextmenu directionality emoticons template paste textcolor"
                    ],
                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | print preview fullpage | forecolor backcolor emoticons link",
    
                });
            }
            ajaxreload();
            $("#form").validate({
                rules: {
                    mymce: 'required',
                },
                messages: {
                    mymce: "Please enter Terms",
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "loaddata.php", 
                        type: "POST", 
                        dataType:"JSON",            
                        data: $("#form").serialize()+"&action=updatetermdetails",
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
        });
        function ajaxreload() {
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data: {action : 'loadtermdetails'},
            success:function(response){  
                $("#mymce").val(response);
            }
        });
    }
        
        
        </script>
        
    </body>
</html>