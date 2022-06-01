<?php

$page_title = "Vehicle Group Create";

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
                                             <div class="header">Add New Group</div>
                                            </div>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-12">
                            
                                                   <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3"> Name::
                                                        </label>


                                                    <input type="text" class="form-control" data-aire-component="input" name="name:" data-aire-for="Name" required>
                                                  </div>


                                                     <div class="form-group">

                                                        <label for="exampleInputuname">Rent per day:</label>

                                                        <div class="input-group mb-3">

                                                            <div class="input-group-prepend">

                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-pound-sign"></i></span>

                                                            </div>

                                                            <input type="text" class="form-control" placeholder="0.00" aria-label="0.00" aria-describedby="basic-addon1">

                                                        </div>

                                                    </div>

                                                    <div class="form-group">

                                                        <label for="exampleInputuname">Insurance per day:</label>

                                                        <div class="input-group mb-3">

                                                            <div class="input-group-prepend">

                                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-pound-sign"></i></span>

                                                            </div>

                                                            <input type="text" class="form-control" placeholder="0.00" aria-label="0.00" aria-describedby="basic-addon1">

                                                        </div>

                                                    </div>

                                            </div>

                                        </div>

                                         <div class="card-footer">

                                                    <button class="btn btn-primary " data-aire-component="button" type="submit" name="addgroup">

                                                        Add Group

                                                    </button>

                                                    <a href="vehicle_groups.php" class="btn">Go Back</a>

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