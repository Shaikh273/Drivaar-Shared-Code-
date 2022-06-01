<?php

$page_title = "Health Issues";

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

                        <div class="container"  style="max-width: 100%;">

                            <div class="row justify-content-center">

                                <div class="col-md-12">

                                    <div class="card">    

                                        <div class="card-header " style="background-color: rgb(255 236 230);">

                                            <div class="d-flex justify-content-between align-items-center">

                                              <div class="header">Issues </div>
                                            
                                            </div>

                                        </div>
 <div class="card-body">
    <div class="row">                 
       <div class="col-md-2 align-left">
            <input class="form-control form-control-sm" type="text" id="searchTable" placeholder="Search in Table" style="margin-top: 23px;">
        </div>
     <div class="col-md-9">
            
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <label for="showdt">Show</label>
                <select class="form-control form-control-sm" id="showdt">
                  <option value="10">10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
              </div>
        </div>                         
     </div>


    <table class="table table-responsive" style="font-weight: 400;">
        <thead class="default">
            <tr>
                <th></th>
                <th>Vehicle</th>
                <th>Issue</th>
                <th>State</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>1</td>
                    <td><a href="">Supreme (LL69JZD)</a></td>
                    <td>
                        <strong>#169 - </strong>
                        <span>Reported 1 year ago by Essak Abraham</span>
                    </td>
                    <td>
                         <span class="label label-primary">open</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm">Close</button>
                        <button type="button" class="btn btn-info btn-sm">Resolve</button>
                    </td>
                </tr>

                 <tr>
                    <td>2</td>
                    <td><a href="">Europcar (BT17TTJ)</a></td>
                    <td>
                        <strong>#169 - </strong>
                        <span>Reported 1 year ago by Essak Abraham</span>
                    </td>
                    <td>
                        <span class="label label-primary">open</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm">Close</button>
                        <button type="button" class="btn btn-info btn-sm">Resolve</button>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td><a href="">Supreme (PG68OGY)</a></td>
                    <td>
                        <strong>#169 - </strong>
                        <span>Reported 1 year ago by Essak Abraham</span>
                    </td>
                    <td>
                        <span class="label label-primary">open</span>

                    </td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm">Close</button>
                        <button type="button" class="btn btn-info btn-sm">Resolve</button>
                    </td>
                </tr>
            </tbody>
        </table>


<div class="row">
    <div class="col-md-9">
        Showing 1 to 25 of 57 entries
    </div>
    <div class="col-md-3" style="text-align: right;">
        <ul class="pagination"><li class="paginate_button page-item previous disabled" id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item next" id="example_next"><a href="#" aria-controls="example" data-dt-idx="4" tabindex="0" class="page-link">Next</a></li></ul>
    </div>
</div>
</div>



     <!-- <div class="card-footer"></div> -->


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