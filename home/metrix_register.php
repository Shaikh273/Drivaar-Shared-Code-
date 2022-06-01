<?php

$page_title = "Metrix Register";

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

        <link rel="stylesheet" href="countrycode/build/css/demo.css">

    </head>

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



                    <main class=" container-fluid  animated mt-4">

                        <div class="container">

                            <div class="row justify-content-center">

                                <div class="col-md-6">

                                    <div class="card">    

                                        <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="header">Add New Metric</div>
                                            </div>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-12">
                            
                                                    <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Name:
                                                        </label>


                                                    <input type="text" class="form-control" data-aire-component="input" name="metric_name" data-aire-for="name" required>

                        
                                                 </div>
                                                 <div class="form-group">

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="Percentage" name="Metric" class="custom-control-input">

                                                        <label class="custom-control-label" for="Percentage">Percentage</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="Number" name="Metric" class="custom-control-input">

                                                        <label class="custom-control-label" for="Number">Number</label>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                         <div class="card-footer">

                                                    <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">

                                                        Add Metric

                                                    </button>

                                                    <a href="metrix_register.php" class="btn">Cancel</a>

                                                </div>


                                    </div>        

                                </div>

                            </div>

                        </div>

                    </main>

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