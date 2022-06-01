<?php

$page_title = "Accidents";

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
            .yellow {
                background-color: #fffae6!important;
            }
            .default {
                background-color: #f8fafc!important;
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
        <div class="col-md-4">
           <div class="form-group has-primary">
                   <select class="select2 form-control custom-select">
                        <option>By Stage</option>
                        <option value="AK">Open</option>
                        <option value="HI">Driver Accident Form</option>
                        <option value="">Expecting Correspondence</option>
                        <option value="">We need To reply</option>
                   </select>    
            </div>
        </div>
        
         <div class="col-md-4">
           <div class="form-group has-primary">
                   <select class="select2 form-control custom-select">
                        <option>By Status</option>
                        <option value="AK">Open</option>
                        <option value="HI">Closed</option>
                   </select>    
            </div>
        </div>
       
       
        <div class="col-md-3">
             <button type="button" class="btn btn-info">Filter Accidents</button>
        </div>

</div>
<br>
            <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Accidents (8)</div>
                            <div> 
                                <a href="addvehicleaccident.php"> 
                                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Report an Accident</button>
                                </a>
                            </div>
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

                                    <thead class="default">

                                        <tr>

                                        <th>Stage</th>

                                        <th>Driver</th>

                                        <th>Vehicle</th>

                                        <th>Reg. Plate</th>

                                        <th>Ref</th>

                                        <th>Date</th>

                                        <th>Reported</th>

                                        <th>Type</th>

                                        <th></th>

                                        <th></th>

                                        <th></th>

                                    </tr>

                                    </thead>

                                    <tbody id="extendedTable">

                                        <tr>

                                            <td>Open</td>

                                            <td><a href="">Amrit Gurung</a></td>

                                            <td><a href="">Supreme (FD69LKY)</a></td>

                                            <td>FD69LKY</td>

                                            <td>--</td>

                                            <td>Jun 21, 2020</td>

                                            <td>Jun 24, 2020</td>

                                            <td>Non-Fault</td>

                                            <td><span class="label label-success">Reported</span></td>

                                            <td><span class="label label-primary">Open</span></td>


                                            <td>
                                              <div class="btn-group">
                                                <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i>

                                                </button>



                                                <div class="dropdown-menu">

                                                    <div class="dropdown-item">Accident Actions</div>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-edit">  Edit  </i></a>

                                                    <div class="dropdown-item">Close Actions</div>

                                                    <div class="dropdown-divider"></div>


                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-trash-alt"> Delete</i></a>

                                                    
                                                </div>
                                            </div>
                                            </td>

                                        </tr>

                                         <tr>

                                            <td>Driver Accident Form</td>

                                            <td><a href="">Oliver Totman</a></td>

                                            <td><a href="">Herd Hire (DX68YRC)</a></td>

                                            <td>DX68YRC</td>

                                            <td>--</td>

                                            <td>Jun 12, 2020</td>

                                            <td>Jun 24, 2020</td>

                                            <td>Fault</td>

                                            <td><span class="label label-success">Reported</span></td>


                                            <td><span class="label label-primary">Open</span></td>


                                            <td>
                                              <div class="btn-group">
                                                <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i>

                                                </button>



                                                <div class="dropdown-menu">

                                                    <div class="dropdown-item">Accident Actions</div>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-edit">  Edit  </i></a>

                                                    <div class="dropdown-item">Close Actions</div>

                                                    <div class="dropdown-divider"></div>


                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-trash-alt"> Delete</i></a>

                                                    
                                                </div>
                                            </div>
                                            </td>

                                        </tr>

                                           <tr>

                                            <td>We need to reply</td>

                                            <td><a href="">James Harrington</a></td>

                                            <td><a href="">Supreme (LC07 GKN)</a></td>

                                            <td>LC07 GKN</td>

                                            <td>--</td>

                                            <td>May 31, 2020</td>

                                            <td>Jun 8, 2020</td>

                                            <td>Fault</td>

                                            <td></td>

                                            <td><span class="label label-primary">Open</span></td>


                                            <td>
                                              <div class="btn-group">
                                                <button type="button" class="btn btn-success" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i>

                                                </button>



                                                <div class="dropdown-menu">

                                                    <div class="dropdown-item">Accident Actions</div>

                                                    <div class="dropdown-divider"></div>

                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-edit">  Edit  </i></a>

                                                    <div class="dropdown-item">Close Actions</div>

                                                    <div class="dropdown-divider"></div>


                                                    <a class="dropdown-item" href="javascript:void(0)"><i class="fas fa-trash-alt"> Delete</i></a>

                                                    
                                                </div>
                                            </div>
                                            </td>

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