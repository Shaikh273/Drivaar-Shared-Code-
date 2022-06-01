<?php

$page_title = "Profile";

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
            <div class="page-wrapper content" id="top">

                <div class="container-fluid">
                    
                     <div class="row">

                        <div class="col-md-12">

                            <div class="card">

                                <div class="card-body"><!-- 

                                    <h4 class="card-title">Vertical Tab</h4> -->

                                    <!-- <h6 class="card-subtitle">Use default tab with class <code>vtabs & tabs-vertical</code></h6>
 -->
                                    <!-- Nav tabs -->

                                 
                                      <div class="vtabs">

                                    <ul class="nav nav-tabs tabs-vertical" role="tablist">

                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home4" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">PROFILE</span> </a> </li>

                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile4" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">ORGANISATION</span></a> </li>

                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages4" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">USAGE & BILLING</span></a> </li>

                                         <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages5" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">CONTRACTORS</span></a> </li>

                                          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages6" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">FINANCE</span></a> </li>

                                       <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#messages7" role="tab"><span class="hidden-sm-up"></span> <span class="hidden-xs-down">VEHICLES</span></a> </li>

                                    </ul>

                                    <!-- Tab panes -->

                                    <div class="tab-content">

                                    <div class="tab-pane active" id="home4" role="tabpanel">
                                        <div class="p-20">
                                           <div  class="col-md-6">
                                            <select class="form-control custom-select">
                                                     <option value="">General Settings</option>
                                                     <option value="">Security</option>      
                                            </select>
                                             </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane p-20" id="profile4" role="tabpanel">
                                         <div  class="col-md-6">
                                            <select class="form-control custom-select">
                                                     <option value="">General Settings</option>
                                                     <option value="">Billing Details</option>
                                                     <option value="">Permissions</option>
                                                     <option value="">Documents</option>
                                                     <option value="">Useful Links</option>
                                                </select>
                                             </div>
                                    </div>

                                    <div class="tab-pane p-20" id="messages4" role="tabpanel"></div>

                                    <div class="tab-pane p-20" id="messages5" role="tabpanel"></div>


                                    <div class="tab-pane p-20" id="messages6" role="tabpanel">
                                         <div  class="col-md-6">
                                            <select class="form-control custom-select">
                                                     <option value="">Settings</option>
                                                </select>
                                         </div>

                                         <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                 <div class="card">    
                                                    <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h5 class="m-0">Account Details</h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                             <div class="col-md-12">
                                                                <form action="" method="post">
                                                                    <input type="hidden" name="_token" value="">                
                                                                    <input type="hidden" name="_method" value="put">
                                                                    <div class="form-group row required">
                                                                        <div class="col-md-4">
                                                                            <label class="d-block mb-0">Name</label>
                                                                            <small class="text-muted">
                                                                                Your name (used for invoices, etc.)
                                                                            </small>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="text" name="name" id="" value="" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row">
                                                                        <div class="col-md-4">
                                                                            <label class="d-block mb-0">Phone</label>
                                                                            <small class="text-muted">
                                                                                For receiving text messages
                                                                            </small>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="text" name="phone" id="" value="" class="form-control">
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                     <div class="card-footer">
                                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" name="addmetric">Update
                                                                        </button>
                                                                        <a href="profile.php" class="btn">Cancel</a>
                                                                   
                                                                </form>
                                                            </div>
                                                        </div>
                                                     </div>
                                                   </div>        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane p-20" id="messages7" role="tabpanel"></div>
                                    

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