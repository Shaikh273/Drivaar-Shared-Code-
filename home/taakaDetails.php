<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['load']) || !isset($_SESSION['uid']))
{
    header("location: login.php");
}
$_SESSION['page_id']=4;
//include('authentication.php');
include('config.php');
$page_title="Taaka Ledger";
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
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15" onclick="javascript:window.location.href='newTakaLedger.php'";><i class="fa fa-plus-circle"></i> New Entry</button>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Taaka List</h4>
                                <h6 class="card-subtitle">Use Action column for more options</h6>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="row" style="margin-top: 10px;margin-bottom: 10px;">
                                        <div class="btn-group" data-toggle="buttons" role="group">
                                            <label class="btn btn-outline btn-info active">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio1" name="options" value="male" class="custom-control-input" checked>
                                                    <label class="custom-control-label" for="customRadio1"> <i class="ti-check text-active" aria-hidden="true"></i> Bunch 12</label>
                                                </div>
                                            </label>
                                            <label class="btn btn-outline btn-info">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="customRadio2" name="options" value="female" class="custom-control-input">
                                                    <label class="custom-control-label" for="customRadio2"> <i class="ti-check text-active" aria-hidden="true"></i> Bunch 24</label>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Year</label>
                                            <select class="form-control custom-select" name="gender" required>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Month</label>
                                            <select class="form-control custom-select" name="gender" required>
                                                <option value="January">January</option>
                                                <option value="Fabruary">Fabruary</option>
                                            </select>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    
                                    <div class="col-md-7 table-responsive" style="margin-top: 10px;">
                                        <table class="table table-sm table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th></th> 
                                                    <th>Total Taaka</th>
                                                    <th>Totla Meter</th>
                                                    <th>Total Weight</th>
                                                    <th>Unbilled Taaka</th>
                                                </tr>
                                                <tr>
                                                    <th>Unbilled Carry Forward</th>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                </tr>
                                                <tr>
                                                    <th>Selected Month</th>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                    <td class="text-center">59</td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <th class="text-center">59</th>
                                                    <th class="text-center">59</th>
                                                    <th class="text-center">59</th>
                                                    <th class="text-center">59</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <hr color="RED">
                                <div id="tk">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Taaka Count</th>
                                                <th>Total Meter</th>
                                                <th>Total Weight</th>
                                                <!--<th>Avg. Weight</th>-->
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                //include('getEmployeeList.php');
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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