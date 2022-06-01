<?php

$page_title = "Usage";

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

       <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!--    <style type="text/css">
            .titlehead {
                  font-size: 28px;
                  font-weight: 500;
            }
        </style> -->
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
                                                            <div class="header">Application Usage</div>
                                                       </div>
                                                </div>
                                                <div class="card-body">
                                                      <div id="morris-bar-chart"></div>
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
        <script src="../assets/node_modules/morrisjs/morris.js"></script>
        <script src="dist/js/pages/morris-data.js"></script>
    </body>



</html>