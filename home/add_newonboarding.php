
<?php
$page_title="Add onboarding.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=1024">
        <title><?php echo $page_title;?></title>
        <?php include('head.php');?>
        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
        <link rel="stylesheet" href="countrycode/build/css/demo.css">

       <style type="text/css">

            .btn-group {
                margin-left: 16px;
            }

        </style>
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
                                                    <h5 class="m-0">Add Onboardings</h5>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                     <div class="col-md-12">
                                                        <form action="" method="post">
                                                            <input type="hidden" name="_token" value="">                
                                                            <input type="hidden" name="_method" value="put">
                                                            <div class="form-group row required">
                                                                <div class="col-md-4">
                                                                    <label class="d-block mb-0">Name *</label>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" name="name" id="" value="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                             <div class="card-footer">
                                                                <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Add
                                                                </button>
                                                                 <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Cancle
                                                                </button>
                                                           
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
            </div>
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
        
    </body>

</html>