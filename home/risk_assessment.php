<?php

$page_title = "Risk Assessment";

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
            <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Risk Assessments</div>    
                             <div> 
                                <a href=""> 
                                <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> New Assessments</button>
                                </a>
                            </div>               
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
                                        <th>Title</th>
                                        <th>Completion Date</th>
                                        <th>Next Review Date</th>
                                        <th>Created Date</th>
                                        <th>Assigned To</th>
                                        <th>Depot</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="extendedTable">
                                       <tr>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>31/03/2021 16:45</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td><button type="button" class="btn btn-success">Draft</button></td>
                                           <td>
                                            <a href="#"><span><i class="fas fa-edit"></i></span></a>
                                            <a href="#"><span><i class="fas fa-trash-alt"></i></span></a>
                                           </td>
                                       </tr>
                                      
                                       <tr>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>16/02/2021 18:02</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td><button type="button" class="btn btn-success">Draft</button></td>
                                           <td>
                                            <a href="#"><span><i class="fas fa-edit"></i></span></a>
                                            <a href="#"><span><i class="fas fa-trash-alt"></i></span></a>
                                           </td>
                                       </tr>
                                       <tr>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td>15/02/2021 17:42</td>
                                           <td>--</td>
                                           <td>--</td>
                                           <td><button type="button" class="btn btn-success">Draft</button></td>
                                           <td>
                                            <a href="#"><span><i class="fas fa-edit"></i></span></a>
                                            <a href="#"><span><i class="fas fa-trash-alt"></i></span></a>
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