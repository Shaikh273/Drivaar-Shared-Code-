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
        <link rel="stylesheet" href="../assets/node_modules/html5-editor/bootstrap-wysihtml5.css" />
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
                                           <div class="card">   
                                                <?php include('setting.php'); ?> 
                                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                            <div class="header">Default Vehicle Hire Terms & Conditions</div>
                                                       </div>
                                                </div>
                                                <div class="card-body">
                                                     <form method="post">

                                                        <div class="form-group">

                                                            <textarea class="textarea_editor form-control" rows="15" placeholder="Enter text ..."></textarea>

                                                        </div>
                                                        <hr>
                                                             <div class="card-footer">
                                                                <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Save
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
        <script src="../assets/node_modules/html5-editor/wysihtml5-0.3.0.js"></script>
        <script src="../assets/node_modules/html5-editor/bootstrap-wysihtml5.js"></script>
        <script>
            $(document).ready(function() {
                $('.textarea_editor').wysihtml5();
            });
        </script>
    </body>



</html>