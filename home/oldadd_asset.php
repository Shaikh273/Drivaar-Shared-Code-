<?php

$page_title = "Add asset";

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
                                                <div class="header">Add new asset</div>
                                            </div>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                 <div class="col-md-12">

                                                <div class="form-group has-primary">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">Type:
                                                        </label>
                                                       <select class="select2 form-control custom-select">
                                                            <option>--</option>
                                                            <option>Leptop</option>
                                                            <option>PC</option>
                                                            <option>Phone</option>
                                                            <option>GPS</option>
                                                       </select>   
                                                </div>

                                                 <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Name:
                                                    </label>
                                                    <input type="text" class="form-control" data-aire-component="input" name="metric_name" data-aire-for="name" required>
                                                </div>

                                                 <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Number:
                                                    </label>
                                                    <input type="text" class="form-control" data-aire-component="input" name="metric_name" data-aire-for="name" required>
                                                </div>


                                                <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Price:
                                                            </label>
                                                            <input type="text" class="form-control" data-aire-component="input" name="metric_name" data-aire-for="name" required>
                                                            </div>
                                                         </div>
                                                        <div class="col-md-6">
                                                            
                                                        </div>
                                                </div><hr>

                                        
                                                <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Description
                                                    </label>
                                                    <textarea type="text" rows="2" class="form-control" data-aire-component="input" name="metric_name" data-aire-for="name" required></textarea>
                                                </div>
                                                 </div>
                                            </div>

                                         <div class="card-footer">

                                            <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">

                                                Add Asset

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