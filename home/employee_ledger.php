<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['load']) || !isset($_SESSION['uid']))
{
    header("location: login.php");
}
$_SESSION['page_id']=2;
//include('authentication.php');
include('config.php');
$page_title="Employee Ledger";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $page_title;?></title>
        <?php include('head.php');?>
    </head>
    <body class="skin-default-dark fixed-layout">
        <?php
            include('loader.php');
        ?>
        <div id="main-wrapper">
            <?php
                include('header.php');
                include('menu.php');
            ?>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">Employee Ledger</h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>-->
                                <!--    <li class="breadcrumb-item active">Dashboard</li>-->
                                <!--</ol>-->
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15" onclick="javascript:window.location.href='enployeeRegistration.php'";><i class="fa fa-plus-circle"></i> New Registration</button>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Employee List</h4>
                                <h6 class="card-subtitle">Use Action column for more options</h6>
                                <div class="btn-group m-t-15" data-toggle="buttons" role="group">
                                            <label class="btn btn-outline btn-info active">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio1" name="options" value="male" class="custom-control-input" checked>
                                                    <label class="custom-control-label" for="customRadio1"> <i class="ti-check text-active" aria-hidden="true"></i> Active</label>
                                                </div>
                                            </label>
                                            <label class="btn btn-outline btn-info">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2" name="options" value="female" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio2"> <i class="ti-check text-active" aria-hidden="true"></i> Old</label>
                                                </div>
                                            </label>
                                            <!--<label class="btn btn-outline btn-info active">-->
                                            <!--    <div class="custom-control custom-radio">-->
                                            <!--        <input type="radio" id="customRadio3" name="options" value="n/a" class="custom-control-input">-->
                                            <!--        <label class="custom-control-label" for="customRadio3"> <i class="ti-check text-active" aria-hidden="true"></i> N/A</label>-->
                                            <!--    </div>-->
                                            <!--</label>-->
                                        </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr. No</th>
                                                <th>Name</th>
                                                <th>ID</th>
                                                <th>Contact</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                include('getEmployeeList.php');
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    
                    
                    
                    <?php
                        include('right_sidebar.php');
                    ?>
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