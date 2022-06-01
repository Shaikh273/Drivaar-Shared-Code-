<?php

$page_title = "Inspection";

?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

        <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

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

            // include('menu.php');

            ?>

           <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
    <div class="row">
        <div class="col-md-3">
              <div class="form-group has-primary">
                   <select class="select2 form-control custom-select">
                        <option>All Depots</option>
                        <option value="AK">Avonmounth (DBS2) - Amazon Logistics (BS11 0YH)</option>
                        <option value="HI">Aylesford (DME1) - Amazon Logistics (ME20 7PA)</option>
                        <option value="">Banbury (DOX2) - Amazon Logistics (OX16 2SN)</option>
                   </select>   
                    
            </div>
        </div>

        <div class="col-md-2">
             <div class="form-group has-primary">
                   <select class="select2 form-control custom-select">
                        <option>All Drivers</option>
                        <option value="AK">Ali Monfor</option>
                        <option value="HI">Declan Collins</option>
                        <option value="">07ukraj@gmail.com</option>
                        <option value="">Aadam Choudhury</option>
                   </select>   
                    
            </div>
        </div>

        <div class="col-md-2">
           <div class="form-group has-primary">
                   <select class="select2 form-control custom-select">
                        <option>All Vehicles</option>
                        <option value="AK">Supreme (YS69HVJ)</option>
                        <option value="HI">Own (YP68HZK)</option>
                        <option value="">Supreme (YS69LLR)</option>
                   </select>  
                      
            </div>
        </div>

        <div class="col-md-2">
             <label class="custom-control custom-checkbox m-b-0">

                <input type="checkbox" class="custom-control-input">

                <span class="custom-control-label">Pending Approval</span>

            </label>

              <label class="custom-control custom-checkbox m-b-0">

                <input type="checkbox" class="custom-control-input">

                <span class="custom-control-label">Contains Failures</span>

            </label>
            
        </div>


        <div class="col-md-3">
            <button type="button" class="btn btn-info">Filter Inspections</button>
            <button type="button" class="btn btn-info">Clear</button>
        </div>

</div>
<br>
            <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Today's Inspections 0 of 4</div>                   
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
                                        <th>Depot</th>
                                        <th>Date</th>
                                        <th>Vehicle</th>
                                        <th>User</th>
                                        <th>Odometer</th>
                                        <th>Checks</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody id="extendedTable">
                                       
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