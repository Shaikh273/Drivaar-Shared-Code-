<?php

$page_title = "Rental Agreements";

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

            ?>

           <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title titlehead">Rental Agreements  <span class="label label-warning">BETA</span></h6>
                                        <hr><br>
                                        <div>
                                            
                   <div class="card">    
                    <div class="card-header " style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Rental Agreements</div>
                              <a href=""> <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Add Rental Agreement</button></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                        <div class="col-md-3 align-right">
                                            <input class="form-control form-control-sm" type="text" id="searchTable" placeholder="Search in Table" style="margin-top: 23px;">
                                        </div>
                                        <div class="col-md-8"  style="margin-top: 23px;">
                                             <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Advanced Search 
                                              </a>
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
                                <div class="row">
                                    <div class="collapse col-md-12" id="collapseExample">
                                      <div class="card card-body border border-primary rounded">
                                        <div class="row">
                                             <div class="col-md-4">
                                              <div class="form-group has-primary">
                                                   <select class="select2 form-control custom-select">
                                                        <option>All Vehicles</option>
                                                        <option value="AK">(LS69OHC)</option>
                                                        <option value="HI">(BK69YNP)</option>
                                                        <option value="">(KY19RGF)</option>
                                                   </select>    
                                              </div>
                                            </div>

                                            <div class="col-md-4">
                                                 <div class="form-group has-primary">
                                                       <select class="select2 form-control custom-select">
                                                            <option>All Hirers</option>
                                                            <option value="AK">Aadam Choudhury</option>
                                                            <option value="HI">Aaron Green</option>
                                                            <option value="">Aaron Phillips</option>
                                                       </select>   
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                               <div class="form-group has-primary">
                                                       <select class="select2 form-control custom-select">
                                                            <option>All Locations</option>
                                                            <option value="AK">Avonmounth (DBS2) - Amazon Logistics (BS11 0YH)</option>
                                                            <option value="HI">Aylesford (DME1) - Amazon Logistics (ME20 7PA)</option>
                                                            <option value="">Banbury (DOX2) - Amazon Logistics (OX16 2SN)</option>
                                                            <option value="">Bardon Leicester (DNG1) - Amazon Logistics</option>
                                                       </select>    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">SWB</span>
                    </label>
                </span>

                <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">LWB</span>
                    </label>
                </span>
                
                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">MWB</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">XLWB</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">LUTON</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Car</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Truck</span>
                    </label>
                </span>

                 <span class="custom-control custom-checkbox">
                    <label class="custom-control custom-checkbox m-b-0">
                        <input type="checkbox" class="custom-control-input">
                        <span class="custom-control-label">Scooter</span>
                    </label>
                </span>
        
                                        </div>
       
                                      </div>
                                    </div>
                                </div>
                                <table class="table table-responsive" style="font-weight: 400;">
                                    <thead class="default">
                                        <tr>
                                        <th>Hirer</th>
                                        <th>Vehicle</th>
                                        <th>Location</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Price</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="extendedTable">
                                       <tr>
                                           <td>
                                             <a href="">Alexander Garlick</a><small>Regional Depot Manager<small>
                                           </td>
                                           <td> 
                                             <a href="">(YB21LSC) <span class="label label-primary"> SWB</span></a><br>
                                             <small>BMW 5 SERIES<small>
                                            </td>
                                           <td> Wembley (DHA1) - Amazon Logistics (NW11 0UP)</td>
                                           <td>07/04/2021 12:59</td>
                                           <td>07/04/2022 12:59</td>
                                           <td>£0.00</td>
                                           <td>

                                            <span class="label label-success">Active</span>

                                           </td>
                                            <td> 

                                                <div class="btn-group">



                                                    <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i>

                                                    </button>



                                                    <div class="dropdown-menu">

                                                        <div class="dropdown-item" >Vehicle Actions</div>

                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-book-open">  View Details  </i></a>

                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-car-crash">  Demages</i></a>

                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-camera-retro">  Images  </i></a>

                                                        <div class="dropdown-divider"></div>

                                                        <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-calendar-times">  End Agreement </i></a>

                                                    </div>

                                                </div>

                                            </td>
                                       </tr>
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                        <div class="row">
                                    <div class="col-md-9 mt-5">
                                        Showing 1 to 25 of 57 entries
                                    </div>
                                    <div class="col-md-3" style="text-align: right;">
                                        <ul class="pagination"><li class="paginate_button page-item previous disabled" id="example_previous"><a href="#" aria-controls="example" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="example" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="example" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item next" id="example_next"><a href="#" aria-controls="example" data-dt-idx="4" tabindex="0" class="page-link">Next</a></li></ul>
                                    </div>
                                </div>
                    </div>
                    <div class="card-footer"></div>
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