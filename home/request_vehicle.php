<?php

$page_title = "Request Vehicle";

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
                                            <div class="header">Request vehicle for hire</div>
                                        </div>

                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-12">

                                                <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3"> Short description
                                                    </label>

                                                    <textarea row="4" type="text" class="form-control" data-aire-component="input" name="description" data-aire-for="description" required></textarea>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="card-footer">

                                            <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Request vehicle
                                            </button>

                                            <a href="request_vehicle.php" class="btn">Cancel</a>

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