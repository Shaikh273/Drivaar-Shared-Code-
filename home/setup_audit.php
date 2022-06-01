<?php

$page_title = "Set up an external audit window";

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

        <link rel="stylesheet" href="countrycode/build/css/demo.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>


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
                                             <div class="header">Set up an external audit window</div>
                                            </div>

                                        </div>

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="col-md-12">
                            
                                                   <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3"> Name of the Audit:
                                                        </label>


                                                    <input type="text" class="form-control" data-aire-component="input" name="name:" data-aire-for="Name" required>
                                                  </div>



                                                   <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                       <select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select" name="test">
                                                            <option value=""></option>
                                                            <option>American Black Bear</option>
                                                            <option>Asiatic Black Bear</option>
                                                            <option>Brown Bear</option>
                                                            <option>Giant Panda</option>
                                                            <option>Sloth Bear</option>
                                                            <option>Sun Bear</option>
                                                            <option>Polar Bear</option>
                                                            <option>Spectacled Bear</option>
                                                          </select>     
                                                  </div>

                                                   <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">Document Types::
                                                        </label>


                                                    <input type="text" class="form-control" data-aire-component="input" name="reference_cmp" data-aire-for="reference_cmp" required>
                                                  </div>

                                                  <div class="row">
                                                    <div class="col-md-6">
                                                        <label class="cursor-pointer" data-aire-component="label" for="__aire-0-name3">Opens at:
                                                        </label>
                                                        <div class="input-group">

                                                            <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">

                                                            <div class="input-group-append">

                                                                <span class="input-group-text"><i class="icon-calender"></i></span>

                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                    <div class="col-md-6">
                                                         <label class="cursor-pointer" data-aire-component="label" for="__aire-0-name3">Closes at:
                                                        </label>
                                                        <div class="input-group">

                                                            <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">

                                                            <div class="input-group-append">

                                                                <span class="input-group-text"><i class="icon-calender"></i></span>

                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                  </div>
                                                  <br>
                                                   <div class="form-group">

                                                        <label class="custom-control custom-checkbox m-b-0">

                                                            <input type="checkbox" class="custom-control-input">

                                                            <span class="custom-control-label">Include PAID invoices</span>

                                                        </label>

                                                    </div>
                                                  

                                                  </div>

                                        </div>

                                        <br>

                                         <div class="card-footer">

                                                    <button class="btn btn-primary " data-aire-component="button" type="submit" name="addgroup">

                                                        Set up

                                                    </button>

                                                    <a href="vehicle_groups.php" class="btn">Cancle</a>

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



        <?php include('footerScript.php');?>
        <script>
            $(".chosen-select").chosen({
              no_results_text: "Oops, nothing found!"
            });
        </script>

</body>
</html>