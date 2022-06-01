<?php
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['144'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
} else {
    header("location: login.php");
}
//include('authentication.php');
include 'DB/config.php';
$page_title = "Dashboard";

$mysql = new Mysql();
$mysql->dbConnect();
$query = "SELECT COUNT(id) as cnt FROM `tbl_contractor` WHERE `isdelete`=0  AND userid=" . $userid . " AND `depot` LIKE '%'";
$row =  $mysql->selectFreeRun($query);
$cnt = mysqli_fetch_array($row);

$row1 =  $mysql->selectFreeRun("SELECT COUNT(id) as dpt FROM `tbl_depot` WHERE `isdelete`=0 AND userid=" . $userid);
$depot = mysqli_fetch_array($row1);

$row2 =  $mysql->selectFreeRun("SELECT COUNT(id) as wrk FROM `tbl_user` WHERE `isdelete`=0 AND `userid`=" . $userid . " AND `depot` LIKE '%'");
$workforce = mysqli_fetch_array($row2);

$row3 =  $mysql->selectFreeRun("SELECT COUNT(id) as vehicle FROM `tbl_vehicles` WHERE `isdelete`=0 AND userid=" . $userid . " AND `depot_id` LIKE '%'");
$vehicle = mysqli_fetch_array($row3);
$mysql->dbDisConnect();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
    <link href="../assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <style>
        html body .font-light {
            font-size: 36px;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <?php

    include('loader.php');
    ?>

    <div id="main-wrapper" id="top">
        <?php
        include('header.php');
        ?>

        /date_interval_create_from_date_string

        <div class="page-wrapper content" id="top">
            <div class="container-fluid">

                <!--   <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                              <div class="header">Dashboard</div>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                                <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>
                            </div>
                        </div>  -->
            </div>




            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs justify-content-center" role="tablist">
                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Dashboard</span></a> </li>
                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#advance" role="tab"><span class="hidden-sm-up"><i class="fas fa-clipboard-list"></i></span> <span class="hidden-xs-down">Advance Details</span></a> </li>
                    </ul>
                    <div class="tab-content tabcontent-border">
                        <div class="tab-pane active p-20" id="home" role="tabpanel">
                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['168'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                            ?>

                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Total Contractor</h4>
                                                <ul class="list-inline two-part">
                                                    <li>
                                                        <div id="sparklinedash"></div>
                                                    </li>
                                                    <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success"><?php echo $cnt['cnt']; ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Total Depot</h4>
                                                <ul class="list-inline two-part">
                                                    <li>
                                                        <div id="sparklinedash2"></div>
                                                    </li>
                                                    <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple"><?php echo $depot['dpt']; ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Total WorkForce</h4>
                                                <ul class="list-inline two-part">
                                                    <li>
                                                        <div id="sparklinedash3"></div>
                                                    </li>
                                                    <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info"><?php echo $workforce['wrk']; ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Total Vehicle</h4>
                                                <ul class="list-inline two-part">
                                                    <li>
                                                        <div id="sparklinedash4"></div>
                                                    </li>
                                                    <li class="text-right"><i class="ti-arrow-down text-danger"></i> <span class="text-danger"><?php echo $vehicle['vehicle']; ?></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                header("location: login.php");
                            }
                            ?>

                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['169'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                            ?>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Contractor Feedback</h4>
                                                <?php
                                                $mysql = new Mysql();
                                                $mysql->dbConnect();

                                                $feed = "SELECT COUNT(a.`id`) FROM `tbl_contactorfeedback` a INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` WHERE  a.`isdelete`=0 AND DATE(a.`insert_date`) = CURRENT_DATE() AND b.`userid` =" . $userid;
                                                $fbrow =  $mysql->selectFreeRun($feed);
                                                $resultfb = mysqli_fetch_array($fbrow, MYSQLI_NUM);

                                                $feed1 = "SELECT COUNT(a.`id`) FROM `tbl_contactorfeedback` a INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` WHERE  a.`isdelete`=0 AND MONTH(a.`insert_date`) = MONTH(CURRENT_DATE()) AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.`userid` =" . $userid;
                                                $fbrow1 =  $mysql->selectFreeRun($feed1);
                                                $resultfb1 = mysqli_fetch_array($fbrow1, MYSQLI_NUM);

                                                $feed2 = "SELECT COUNT(a.`id`) FROM `tbl_contactorfeedback` a INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` WHERE  a.`isdelete`=0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.`userid` =" . $userid;
                                                $fbrow2 =  $mysql->selectFreeRun($feed2);
                                                $resultfb2 = mysqli_fetch_array($fbrow2, MYSQLI_NUM);

                                                $mysql->dbDisconnect();
                                                ?>
                                                <div class="stats-row">
                                                    <div class="stat-item">
                                                        <h6>Yearly</h6>
                                                        <b><?= $resultfb2[0]; ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Montly</h6>
                                                        <b><?= $resultfb1[0] ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Today</h6>
                                                        <b><?= $resultfb[0] ?></b>
                                                    </div>
                                                </div>
                                                <div id="sparkline8"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Assign Vehicle</h4>
                                                <?php
                                                $mysql = new Mysql();
                                                $mysql->dbConnect();

                                                $statusquery = "SELECT COUNT(a.`id`) as today FROM `tbl_assignvehicle` a INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vid` WHERE a.`isdelete`=0  AND CURRENT_DATE() BETWEEN a.`start_date` AND a.`end_date` AND v.`userid`=" . $userid;
                                                $strow =  $mysql->selectFreeRun($statusquery);
                                                $result = mysqli_fetch_array($strow);

                                                $statusquery1 = "SELECT COUNT(a.`id`) as monthly FROM `tbl_assignvehicle` a INNER JOIN `tbl_vehicles` v ON v.`id`=a.`id` WHERE a.`isdelete`=0  AND MONTH(CURRENT_DATE()) IN (MONTH(a.`start_date`), MONTH(a.`end_date`)) AND v.`userid`=" . $userid;
                                                $strow1 =  $mysql->selectFreeRun($statusquery1);
                                                $result1 = mysqli_fetch_array($strow1);

                                                $statusquery2 = "SELECT COUNT(a.`id`) as yearly FROM `tbl_assignvehicle` a INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vid` WHERE a.`isdelete`=0  AND YEAR(CURRENT_DATE()) IN (YEAR(a.`start_date`), YEAR(a.`end_date`)) AND v.`userid`=" . $userid;
                                                $strow2 =  $mysql->selectFreeRun($statusquery2);
                                                $result2 = mysqli_fetch_array($strow2);

                                                $mysql->dbDisconnect();
                                                ?>
                                                <div class="stats-row">
                                                    <div class="stat-item">
                                                        <h6>Yearly</h6>
                                                        <b><?= $result2['yearly']; ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Montly</h6>
                                                        <b><?= $result1['monthly']; ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Today</h6>
                                                        <b><?= $result['today']; ?></b>
                                                    </div>
                                                </div>
                                                <div id="sparkline9"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card">
                                            <div class="card-body analytics-info">
                                                <h4 class="card-title">Assign Contractor</h4>
                                                <?php
                                                $mysql = new Mysql();
                                                $mysql->dbConnect();

                                                $assigncnt = " SELECT COUNT(id) FROM `tbl_contractortimesheet` WHERE  `isdelete`=0 AND `status_id`=1 AND `date`=CURRENT_DATE() AND `userid`=" . $userid;
                                                $row =  $mysql->selectFreeRun($assigncnt);
                                                $assigncnt_res = mysqli_fetch_array($row, MYSQLI_NUM);

                                                $assigncnt1 = "SELECT COUNT(id) FROM `tbl_contractortimesheet` WHERE `isdelete`=0 AND `status_id`=1 AND  MONTH(`date`)=MONTH(CURRENT_DATE()) AND YEAR(`date`)=YEAR(CURRENT_DATE()) AND `userid`=" . $userid;
                                                $row1 =  $mysql->selectFreeRun($assigncnt1);
                                                $assigncnt_res1 = mysqli_fetch_array($row1, MYSQLI_NUM);

                                                $assigncnt2 = "SELECT COUNT(id) FROM `tbl_contractortimesheet` WHERE  `isdelete`=0 AND `status_id`=1 AND  YEAR(`date`)=YEAR(CURRENT_DATE()) AND `userid`=" . $userid;
                                                $row2 =  $mysql->selectFreeRun($assigncnt2);
                                                $assigncnt_res2 = mysqli_fetch_array($row2, MYSQLI_NUM);

                                                $mysql->dbDisconnect();
                                                ?>
                                                <div class="stats-row">
                                                    <div class="stat-item">
                                                        <h6>Yearly</h6>
                                                        <b><?= $assigncnt_res2[0]; ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Montly</h6>
                                                        <b><?= $assigncnt_res1[0] ?></b>
                                                    </div>
                                                    <div class="stat-item">
                                                        <h6>Today</h6>
                                                        <b><?= $assigncnt_res[0] ?></b>
                                                    </div>
                                                </div>
                                                <div id="sparkline10"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                header("location: login.php");
                            }
                            ?>

                            <div class="row">
                                <?php
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['170'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                                ?>
                                    <div class="col-lg-4 col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="col-md-12">
                                                    <div class="form-group has-primary">
                                                        <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="getdepotdata()">
                                                            <option value="all">All Depot</option>
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();
                                                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                                INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                                WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid`=" . $userid;
                                                            $strow =  $mysql->selectFreeRun($statusquery);
                                                            while ($statusresult = mysqli_fetch_array($strow)) {
                                                            ?>
                                                                <option value="<?php echo $statusresult['id'] ?>"><?php echo $statusresult['name'] ?></option>
                                                            <?php
                                                            }
                                                            $mysql->dbDisconnect();
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <h5 class="card-title ">Leads by Source</h5>
                                                <div id="morris-donut-chart" style="height: 317px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    header("location: login.php");
                                }
                                ?>
                                <?php
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['171'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                                ?>
                                    <div class="col-lg-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Monthly Payment Report</h4>
                                                <ul class="list-inline text-center m-t-40">
                                                    <li>
                                                        <h5><i class="fa fa-circle m-r-5 text-info" style="color: #fb9678!important;"></i>Contractor</h5>
                                                    </li>
                                                    <li>
                                                        <h5><i class="fa fa-circle m-r-5 text-success" style="color: #00c292!important;"></i>Workforce</h5>
                                                    </li>
                                                </ul>
                                                <div id="extra-area-chart" style="height: 330px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    header("location: login.php");
                                }
                                ?>
                            </div>
                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['172'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                            ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Contractor Invoice Report</h4>
                                                <div id="bar-chart1" style="width:100%; height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                header("location: login.php");
                            }
                            ?>

                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['172'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
                            ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Workforce Invoice Report</h4>
                                                <div id="bar-chart2" style="width:100%; height:400px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                                header("location: login.php");
                            }
                            ?>

                        </div>


                        <div class="tab-pane p-20" id="advance" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-dark">
                                        <div class="card-header bg-dark">
                                            <h4 class="m-b-0 text-white">Communications</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment</h3>
                                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                            <br><a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-header bg-info">
                                            <h4 class="m-b-0 text-white">Celebrations</h4>
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment</h3>
                                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                            <br><a href="javascript:void(0)" class="btn btn-primary">Go somewhere</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <!-- Column -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Daily Sales</h4>
                                            <div class="text-right"> <span class="text-muted">Todays Income</span>
                                                <h1 class="font-light"><sup><i class="ti-arrow-up text-success"></i></sup> $120</h1>
                                            </div>
                                            <span class="text-success">20%</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 20%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Weekly Sales</h4>
                                            <div class="text-right"> <span class="text-muted">Todays Income</span>
                                                <h1 class="font-light"><sup><i class="ti-arrow-up text-info"></i></sup> $5,000</h1>
                                            </div>
                                            <span class="text-info">30%</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 30%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Monthly Sales</h4>
                                            <div class="text-right"> <span class="text-muted">Todays Income</span>
                                                <h1 class="font-light"><sup><i class="ti-arrow-down text-danger"></i></sup> $8,000</h1>
                                            </div>
                                            <span class="text-danger">60%</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 60%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-lg-3 col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">Yearly Sales</h4>
                                            <div class="text-right"> <span class="text-muted">Todays Income</span>
                                                <h1 class="font-light"><sup><i class="ti-arrow-up text-inverse"></i></sup> $12,000</h1>
                                            </div>
                                            <span class="text-inverse">80%</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-inverse" role="progressbar" style="width: 80%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                            </div>
                            <div class="row">
                                <!-- Column -->
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5 class="card-title m-b-40">SALES IN 2018</h5>
                                                    <p>Lorem ipsum dolor sit amet, ectetur adipiscing elit. viverra tellus. ipsumdolorsitda amet, ectetur adipiscing elit.</p>
                                                    <p>Ectetur adipiscing elit. viverra tellus.ipsum dolor sit amet, dag adg ecteturadipiscingda elitdglj. vadghiverra tellus.</p>
                                                </div>
                                                <div class="col-md-8 col-sm-6 col-xs-12">
                                                    <div id="morris-area-chart" style="height: 250px; position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg height="250" version="1.1" width="785.92" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.951407px; top: -0.614592px;">
                                                            <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.2.0</desc>
                                                            <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="49.1875" y="211.65625" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan>
                                                            </text>
                                                            <path fill="none" stroke="#e0e0e0" d="M61.6875,211.65625H760.92" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="49.1875" y="164.9921875" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">5,000</tspan>
                                                            </text>
                                                            <path fill="none" stroke="#e0e0e0" d="M61.6875,164.9921875H760.92" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="49.1875" y="118.32812499999999" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                                                                <tspan dy="4.445312499999986" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">10,000</tspan>
                                                            </text>
                                                            <path fill="none" stroke="#e0e0e0" d="M61.6875,118.32812499999999H760.92" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="49.1875" y="71.6640625" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">15,000</tspan>
                                                            </text>
                                                            <path fill="none" stroke="#e0e0e0" d="M61.6875,71.6640625H760.92" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="49.1875" y="24.99999999999997" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                                                                <tspan dy="4.445312499999972" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">20,000</tspan>
                                                            </text>
                                                            <path fill="none" stroke="#e0e0e0" d="M61.6875,24.99999999999997H760.92" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="760.92" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2019</tspan>
                                                            </text><text x="683.2747725889869" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2018</tspan>
                                                            </text><text x="605.6295451779738" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2017</tspan>
                                                            </text><text x="527.7715911165196" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2016</tspan>
                                                            </text><text x="450.12636370550655" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2015</tspan>
                                                            </text><text x="372.48113629449347" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2014</tspan>
                                                            </text><text x="294.8359088834804" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2013</tspan>
                                                            </text><text x="216.97795482202616" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2012</tspan>
                                                            </text><text x="139.33272741101308" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2011</tspan>
                                                            </text><text x="61.6875" y="224.15625" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,6.6719)">
                                                                <tspan dy="4.4453125" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2010</tspan>
                                                            </text>
                                                            <path fill="#494949" stroke="none" d="M61.6875,211.562921875C81.09880685275327,207.696804296875,119.92142055825981,201.81713242187502,139.33272741101308,196.0984515625C158.74403426376637,190.379770703125,197.56664796927288,168.2599868950923,216.97795482202616,165.81347499999998C236.4424433373897,163.36026033259233,275.37142036811684,178.71678409285227,294.8359088834804,176.4995453125C314.24721573623367,174.28836456160226,353.0698294417402,150.3198396484375,372.48113629449347,148.09979687499998C391.89244314724675,145.87975410156247,430.71505685275326,156.417666015625,450.12636370550655,158.739203125C469.53767055825983,161.06074023437498,508.36028426376635,177.626642011692,527.7715911165196,166.67209375C547.2360796318832,155.68753302731702,586.1650566626103,74.79926829150989,605.6295451779738,70.98276718749997C625.0408520307271,67.1766936821349,663.8634657362336,118.6092759765625,683.2747725889869,136.18179531250001C702.6860794417402,153.75431464843751,741.5086931472467,192.717640234375,760.92,211.562921875L760.92,211.65625L61.6875,211.65625Z" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></path>
                                                            <path fill="none" stroke="#3d3d3d" d="M61.6875,211.562921875C81.09880685275327,207.696804296875,119.92142055825981,201.81713242187502,139.33272741101308,196.0984515625C158.74403426376637,190.379770703125,197.56664796927288,168.2599868950923,216.97795482202616,165.81347499999998C236.4424433373897,163.36026033259233,275.37142036811684,178.71678409285227,294.8359088834804,176.4995453125C314.24721573623367,174.28836456160226,353.0698294417402,150.3198396484375,372.48113629449347,148.09979687499998C391.89244314724675,145.87975410156247,430.71505685275326,156.417666015625,450.12636370550655,158.739203125C469.53767055825983,161.06074023437498,508.36028426376635,177.626642011692,527.7715911165196,166.67209375C547.2360796318832,155.68753302731702,586.1650566626103,74.79926829150989,605.6295451779738,70.98276718749997C625.0408520307271,67.1766936821349,663.8634657362336,118.6092759765625,683.2747725889869,136.18179531250001C702.6860794417402,153.75431464843751,741.5086931472467,192.717640234375,760.92,211.562921875" stroke-width="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                                                            <circle cx="61.6875" cy="211.562921875" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="139.33272741101308" cy="196.0984515625" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="216.97795482202616" cy="165.81347499999998" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="294.8359088834804" cy="176.4995453125" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="372.48113629449347" cy="148.09979687499998" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="450.12636370550655" cy="158.739203125" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="527.7715911165196" cy="166.67209375" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="605.6295451779738" cy="70.98276718749997" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="683.2747725889869" cy="136.18179531250001" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                            <circle cx="760.92" cy="211.562921875" r="0" fill="#3d3d3d" stroke="#ffffff" stroke-width="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></circle>
                                                        </svg>
                                                        <div class="morris-hover morris-default-style" style="left: 9.184px; top: 113px; display: none;">
                                                            <div class="morris-hover-row-label">2010</div>
                                                            <div class="morris-hover-point" style="color: #3d3d3d">
                                                                Site A:
                                                                10
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                            </div>
                            <div class="row">
                                <!-- Column -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title m-b-0">WEATHER</h5>
                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="d-flex no-block align-items-center">
                                                <span>
                                                    <h2 class="">Monday</h2><small>7th May 2017</small>
                                                </span>
                                                <div class="ml-auto">
                                                    <canvas class="sleet" width="44" height="44"></canvas> <span class="display-6">32<sup>°F</sup></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table no-border">
                                                        <tbody>
                                                            <tr>
                                                                <td>Wind</td>
                                                                <td class="font-medium">ESE 17 mph</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Humidity</td>
                                                                <td class="font-medium">83%</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pressure</td>
                                                                <td class="font-medium">28.56 in</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table no-border">
                                                        <tbody>
                                                            <tr>
                                                                <td>Cloud Cover</td>
                                                                <td class="font-medium">78%</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Ceiling</td>
                                                                <td class="font-medium">25760 ft</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Tue</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="sleet" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>32<sup>°F</sup></h5>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Wed</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="clear-day" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>34<sup>°F</sup></h5>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Thu</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="partly-cloudy-day" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>31<sup>°F</sup></h5>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Fri</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="cloudy" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>32<sup>°F</sup></h5>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Sat</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="snow" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>12<sup>°F</sup></h5>
                                                </div>
                                                <!-- Column -->
                                                <div class="col-lg-2 col-md-4 col-4 text-center">
                                                    <h5>Sun</h5>
                                                    <div class="m-t-10 m-b-10">
                                                        <canvas class="wind" width="30" height="30"></canvas>
                                                    </div>
                                                    <h5>32<sup>°F</sup></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">USER ACTIVITY</h5>
                                            <div class="steamline m-t-40">
                                                <div class="sl-item">
                                                    <div class="sl-left bg-success"> <i class="ti-user"></i></div>
                                                    <div class="sl-right">
                                                        <div class="font-medium">Meeting today <span class="sl-date"> 5pm</span></div>
                                                        <div class="desc">you can write anything </div>
                                                    </div>
                                                </div>
                                                <div class="sl-item">
                                                    <div class="sl-left bg-info"><i class="fa fa-image"></i></div>
                                                    <div class="sl-right">
                                                        <div class="font-medium">Send documents to Clark</div>
                                                        <div class="desc">Lorem Ipsum is simply </div>
                                                    </div>
                                                </div>
                                                <div class="sl-item">
                                                    <div class="sl-left"> <img class="img-circle" alt="user" src="../assets/images/users/2.jpg"> </div>
                                                    <div class="sl-right">
                                                        <div class="font-medium">Go to the Doctor <span class="sl-date">5 minutes ago</span></div>
                                                        <div class="desc">Contrary to popular belief</div>
                                                    </div>
                                                </div>
                                                <div class="sl-item">
                                                    <div class="sl-left"> <img class="img-circle" alt="user" src="../assets/images/users/3.jpg"> </div>
                                                    <div class="sl-right">
                                                        <div><a href="javascript:void(0)">Tiger Sroff</a> <span class="sl-date">5 minutes ago</span></div>
                                                        <div class="desc">Approve meeting with tiger
                                                            <br><a href="javascript:void(0)" class="btn m-t-10 m-r-5 btn-rounded btn-outline-success">Apporve</a> <a href="javascript:void(0)" class="btn m-t-10 btn-rounded btn-outline-danger">Refuse</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">FEEDS</h5>
                                            <ul class="feeds">
                                                <li>
                                                    <div class="bg-info"><i class="far fa-bell"></i></div> You have 4 pending tasks. <span class="text-muted">Just Now</span>
                                                </li>
                                                <li>
                                                    <div class="bg-success"><i class="ti-server"></i></div> Server #1 overloaded.<span class="text-muted">2 Hours ago</span>
                                                </li>
                                                <li>
                                                    <div class="bg-warning"><i class="ti-shopping-cart"></i></div> New order received.<span class="text-muted">31 May</span>
                                                </li>
                                                <li>
                                                    <div class="bg-danger"><i class="ti-user"></i></div> New user registered.<span class="text-muted">30 May</span>
                                                </li>
                                                <li>
                                                    <div class="bg-dark"><i class="far fa-bell"></i></div> New Version just arrived. <span class="text-muted">27 May</span>
                                                </li>
                                                <li>
                                                    <div class="bg-info"><i class="far fa-bell"></i></div> You have 4 pending tasks. <span class="text-muted">Just Now</span>
                                                </li>
                                                <li>
                                                    <div class="bg-danger"><i class="ti-user"></i></div> New user registered.<span class="text-muted">30 May</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>

                <div style="z-index:1;">
                    <ul class="jq-dice-menu" default-open="false" layout="column" reverse="false" snap-to="right" offset="35%" show-hints="true" hints-order="footer">
                        <div class="jq-items">
                            <li state="close"><span class="fa fa-th-large"></span></li>
                            <!--     <li><span class="fa fa-header" href="#para2" hint="para2" data-toggle="modal" data-target="#myModal"></span></li>
                        <li><span class="fa fa-arrows-v" href="#para3" hint="para3" data-toggle="modal" data-target="#Modal"></span></li>
                        <li><span class="fa fa-github"  hint="Github" data-toggle="modal" data-target="#Modal3"></span></li>
                        <li><span class="fa fa-envelope"  hint="Email for support" data-toggle="modal" data-target="#Modal4"></span></li> -->
                            <li><span class="fa fa-angle-double-up" href="#top"></span></li>
                        </div>
                        <!-- hints of button -->
                        <div class="jq-hints">
                            <div class="hint">&nbsp;</div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        include('footer.php');
        ?>
    </div>
    <script src="dist/jq.dice-menu.js"></script>
    <?php include('footerScript.php'); ?>

    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>

    <!-- Chart JS -->
    <script src="../assets/node_modules/echarts/echarts-all.js"></script>
    <!--morris JavaScript -->
    <script src="../assets/node_modules/raphael/raphael-min.js"></script>
    <script src="../assets/node_modules/morrisjs/morris.min.js"></script>

    <script>
        $(function() {
            var i = 1;
            var table = [];
            table[1] = 'tbl_contractor';
            table[2] = 'tbl_user';


            do {
                chartajax(i, table[i]);
                i++;
            } while (i < 3)

            function chartajax(i, table) {
                $.ajax({
                    method: "POST",
                    url: "loaddata.php",
                    data: {
                        typeid: i,
                        table: table,
                        userid: <?php echo $userid; ?>,
                        action: 'getechartdata'
                    },
                    dataType: "json",
                    success: function(response) {
                        pending = response['pending'];
                        approved = response['approved'];
                        dispute = response['disputes'];
                        chartplot(pending, approved, dispute, i);
                    }
                });
            }

            function chartplot(pending, approved, dispute, i) {
                var myChart = echarts.init(document.getElementById('bar-chart' + i));
                option = {
                    tooltip: {
                        trigger: 'axis'
                    },
                    legend: {
                        data: ['Approved', 'Pending', 'Dispute']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    color: ["#55ce63", "#009efb", "#e83e65"],
                    calculable: true,
                    xAxis: [{
                        type: 'category',
                        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']
                    }],
                    yAxis: [{
                        type: 'value'
                    }],
                    series: [{
                            name: 'Approved',
                            type: 'bar',
                            data: approved,
                        },
                        {
                            name: 'Pending',
                            type: 'bar',
                            data: pending,
                        },
                        {
                            name: 'Dispute',
                            type: 'bar',
                            data: dispute,
                        }

                    ]
                };
                myChart.setOption(option, true), $(function() {
                    function resize() {
                        setTimeout(function() {
                            myChart.resize()
                        }, 100)
                    }
                    $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
                });
            }
        });

        var cnt = <?php echo $cnt['cnt']; ?>;
        var workforce = <?php echo $workforce['wrk']; ?>;
        var vehicle = <?php echo $vehicle['vehicle']; ?>;
        donutdata(cnt, workforce, vehicle);

        function getdepotdata() {
            if ($('#depot_id').val() == 'all') {
                var cnt = <?php echo $cnt['cnt']; ?>;
                var workforce = <?php echo $workforce['wrk']; ?>;
                var vehicle = <?php echo $vehicle['vehicle']; ?>;
                donutdata(cnt, workforce, vehicle);
            } else {
                depot_id = $('#depot_id').val();
                $.ajax({
                    method: "POST",
                    url: "loaddata.php",
                    data: {
                        depot_id: depot_id,
                        userid: <?php echo $userid; ?>,
                        action: 'getdashboarddata'
                    },
                    dataType: "json",
                    success: function(response) {
                        cnt = response['cnt']['COUNT(id)'];
                        workforce = response['workforce']['COUNT(id)'];
                        vehicle = response['vehicle']['COUNT(id)'];
                        donutdata(cnt, workforce, vehicle);
                    }
                });
            }
        }

        function donutdata(cnt, w, v) {
            $("#morris-donut-chart").empty();
            $("#morris-donut-chart svg").remove();
            Morris.Donut({
                element: 'morris-donut-chart',
                data: [{
                    label: "Contractor",
                    value: cnt
                }, {
                    label: "WorkForce",
                    value: w
                }, {
                    label: "Vehicle",
                    value: v
                }],
                resize: true,
                colors: ['#fb9678', '#01c0c8', '#4F5467']
            });
        }

        var cntinvoice;
        var wfinvoice;

        $(function() {

            $.ajax({
                method: "POST",
                url: "loaddata.php",
                data: {
                    userid: <?php echo $userid; ?>,
                    action: 'getpaymentdata'
                },
                dataType: "json",
                success: function(response) {
                    cntinvoice = response['cnt'];
                    wfinvoice = response['wf'];
                    invoicechart(cntinvoice, wfinvoice);
                }
            });

            function invoicechart(cntinvoice, wfinvoice) {
                var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
                var data = [];
                for (i = 0; i < 12; i++) {
                    values = {
                        period: months[i],
                        cntinvoice: cntinvoice[i],
                        wfinvoice: wfinvoice[i]
                    }
                    data.push(values);
                }
                console.log(data);
                Morris.Area({
                    element: 'extra-area-chart',
                    data: data,
                    lineColors: ['#fb9678', '#00c292'],
                    xkey: 'period',
                    ykeys: ['cntinvoice', 'wfinvoice'],
                    labels: ['Contractor', 'Workforce'],
                    pointSize: 0,
                    lineWidth: 0,
                    resize: true,
                    fillOpacity: 0.8,
                    behaveLikeLine: true,
                    gridLineColor: '#e0e0e0',
                    hideHover: 'auto',
                    parseTime: false

                });
            }
        });
    </script>

    <!-- Popup message jquery -->
    <script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>

    <!--jQuery peity -->
    <script src="../assets/node_modules/peity/jquery.peity.min.js"></script>
    <script src="../assets/node_modules/peity/jquery.peity.init.js"></script>

</body>

</html>