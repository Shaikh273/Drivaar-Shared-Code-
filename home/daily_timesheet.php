<?php

$page_title = "Daily Timesheet";

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
                        <option value="">Bardon Leicester (DNG1) - Amazon Logistics</option>
                   </select>   
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">

                <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">

                <div class="input-group-append">

                    <span class="input-group-text"><i class="icon-calender"></i></span>

                </div>
            </div>
           
        </div>
         <div class="col-md-3">
            <div class="input-group">

                <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">

                <div class="input-group-append">

                    <span class="input-group-text"><i class="icon-calender"></i></span>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-info">Filter Timesheet</button>
             <button type="button" class="btn btn-info">Clear</button>
        </div>

</div>
<br>
            <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- <h5 class="m-0">Daily Timesheets</h5> -->
                             <div class="header">Daily Timesheets</div>
                            <div><lable class="label label-primary"  style="margin-top: 10px;">Timesheets Totals </lable> # 
                            <lable class="label label-primary"  style="margin-top: 10px;">Grand Total Route Rates </lable> 0 </div>
                        </div>
                    </div>
                    <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 align-right">
                                    <input class="form-control form-control-sm" type="text" id="searchTable" placeholder="Search in Table" style="margin-top: 23px;">
                                </div>
                                <div class="col-md-8"></div>
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
                                    <thead>
                                        <tr>
                                        <th>Depot</th>
                                        <th>Driver</th>
                                        <th>Rute</th>
                                        <th>Entries</th>
                                        <th>Daily Totals</th>
                                    </tr>
                                    </thead>
                                    <tbody id="extendedTable">
                                        <tr>
                                            <td>Avonmounth (DBS2)</td>
                                            <td>Arvind</td>
                                            <td>NewYork</td>
                                            <td>--</td>
                                            <td>10</td>
                                        </tr>
                                         <tr>
                                            <td>Banbury (DBS2)</td>
                                            <td>Barman</td>
                                            <td>Cambrige</td>
                                            <td>--</td>
                                            <td>7</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-9 mt-5">
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