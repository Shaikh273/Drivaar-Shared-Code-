
<?php
$page_title="Link";
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
                                                    <div class="header">Add New Link</div>
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
                                                                    <label class="d-block mb-0">Name: *</label>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" name="name" id="" value="" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-md-4">
                                                                    <label class="d-block mb-0">URL *</label>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" name="phone" id="" value="" class="form-control">
                                                                </div>
                                                            </div>

                                                            <hr>
                                                             <div class="card-footer">
                                                                <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Add Link
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