<?php

$page_title = "Driver Pool Report";
include 'DB/config.php';
$page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
        
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        // $userid=$_SESSION['userid'];
        $id = $_SESSION['vid'];
        $userid = $_SESSION['userid']; 
        if($userid==1)
        {
          $uid='%';
        }
        else
        {
          $uid=$userid;
        }
    }
    else
    {
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: userlogin.php");
        }
        else
        {
           header("location: login.php");  
        }
    }
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
</head>
<body class="skin-default-dark fixed-layout">

<?php include('loader.php');?>

<div id="main-wrapper">

    <?php include('header.php');?>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 card">
                    <div class="card">
                        <div class="card-body">
                        <?php include('report.php'); ?>
                        <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select class="select form-control" id="locationname" name="locationname" onchange="loadtable();">
                                            <option value="%">All Depot Name</option>
                                            <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid` LIKE '".$uid."'";
                                                $strow =  $mysql -> selectFreeRun($statusquery);
                                                while($statusresult = mysqli_fetch_array($strow))
                                                {
                                            ?>
                                                <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>  
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                        <div class="card shadow-sm">
                
                                            <div class="card-body">
                                                <h1>1 of 1</h1>
                                                <div class="text-muted">
                                                    Worked at least 1 day <svg data-toggle="tooltip" style="width: 15px;" data-title="People who have worked at least 1 day within the last 7 days" class="icon d-inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-original-title="" title=""><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>                    
                                                </div>
                                            </div>
                
                                        </div>
                                </div>

                                <div class="col-md-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h1 class="">300%</h1>
                                                <div class="text-muted">
                                                    Have worked 6 days <svg data-toggle="tooltip" style="width: 15px;" data-title="People who have worked at least 6 days within the last 7 days" class="icon d-inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-original-title="" title=""><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>                    
                                                </div>
                                            </div>
                
                                        </div>
                                </div>
                                <div class="col-md-3">    
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <h1 class=" text-red-500 ">
                                                -28  <svg style="font-size:22px; width: 15px" class="icon d-inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M466.27 225.31c4.674-22.647.864-44.538-8.99-62.99 2.958-23.868-4.021-48.565-17.34-66.99C438.986 39.423 404.117 0 327 0c-7 0-15 .01-22.22.01C201.195.01 168.997 40 128 40h-10.845c-5.64-4.975-13.042-8-21.155-8H32C14.327 32 0 46.327 0 64v240c0 17.673 14.327 32 32 32h64c11.842 0 22.175-6.438 27.708-16h7.052c19.146 16.953 46.013 60.653 68.76 83.4 13.667 13.667 10.153 108.6 71.76 108.6 57.58 0 95.27-31.936 95.27-104.73 0-18.41-3.93-33.73-8.85-46.54h36.48c48.602 0 85.82-41.565 85.82-85.58 0-19.15-4.96-34.99-13.73-49.84zM64 296c-13.255 0-24-10.745-24-24s10.745-24 24-24 24 10.745 24 24-10.745 24-24 24zm330.18 16.73H290.19c0 37.82 28.36 55.37 28.36 94.54 0 23.75 0 56.73-47.27 56.73-18.91-18.91-9.46-66.18-37.82-94.54C206.9 342.89 167.28 272 138.92 272H128V85.83c53.611 0 100.001-37.82 171.64-37.82h37.82c35.512 0 60.82 17.12 53.12 65.9 15.2 8.16 26.5 36.44 13.94 57.57 21.581 20.384 18.699 51.065 5.21 65.62 9.45 0 22.36 18.91 22.27 37.81-.09 18.91-16.71 37.82-37.82 37.82z"></path></svg>                     </h1>
                                                <div class="text-muted">
                                                Variance <svg data-toggle="tooltip" style="width: 15px;" data-title="What is the variance between active people and demand for the upcoming week" class="icon d-inline" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-original-title="" title=""><path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path></svg>                    </div>
                                            </div>
                
                                        </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card shadow-sm">
                
                                        <div class="card-body">
                                            <h1>0</h1>
                                            <div class="text-muted">Support to Driver ratio</div>
                                        </div>
                
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                    <div class="col-md-12">
                
                                        <div class="card shadow-sm mb-4">
                                            <div class="card-header ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0">Depot drivers demand in the upcoming weeks</h6>
                                                </div>
                                            </div>
        
                                            <div class="table-responsive">
                                                <table class="table w-100 table-sm table-vcenter" small="small" centered="centered">
                                                <thead>
                                                <tr>
                                                    <th class="whitespace-no-wrap">Depot</th>
                                                    <th>7-day Drivers</th>
                                                    <th style="width:80px" class="text-center">WK 31</th>
                                                    <th style="width:80px" class="text-center">WK 32</th>
                                                    <th style="width:80px" class="text-center">WK 33</th>
                                                    <th style="width:80px" class="text-center">WK 34</th>
                                                    <th style="width:80px" class="text-center">WK 35</th>
                                                    <th style="width:80px" class="text-center">WK 36</th>
                                                    <th style="width:80px" class="text-center">WK 37</th>
                                                    <th style="width:80px" class="text-center">WK 38</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                <td style="width: 250px;">Avonmounth (DBS2) - Amazon Logistics (BS11 0YH)</td>
                                                <td>1</td>
                                                <td class="px-1 bg-red-100"></td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-08-14">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-08-21">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-08-28">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-09-04">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-09-11">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-09-18">
                                                </td>
                                                <td class="px-1">
                                                <input type="text" value="" class="form-control form-control-sm text-center d-inline-block" name="" placeholder="" wire:model.lazy="rota.2021-09-25">
                                                </td>
                                                </tr>
                                                <tr>
                                                <td></td>
                                                <td>
                                                <div class="form-group m-0" data-aire-component="group" style="width:100px" data-aire-for="allocation">
                                                    <div class="input-group input-group-sm" data-aire-component="input_group" data-aire-validation-key="group_input_group" data-aire-for="allocation">
                                                        <div class="input-group-prepend" data-aire-component="prepend" data-aire-validation-key="group_prepend" data-aire-for="allocation">
                                                            <div class="input-group-text">
                                                                %
                                                            </div>
                                                        </div>
                                        
                                                        <input type="text" class="form-control text-center form-control-sm" data-aire-component="input" name="allocation" wire:model.lazy="allocation" data-aire-for="allocation" id="__aire-0-allocation3">


                                                    </div>
                                                </div>

                                                </td>
                                                <td colspan="8"></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                            </div>
                
                                        </div>

                                        <div class="card shadow-sm mb-4">
                                            <div class="card-header ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0">Drivers &amp; Support</h6>
                                                </div>
                                            </div>
                
                                            <div class="table-responsive">
                                            <table class="table w-100">
                                            <thead>
                                                <tr>
                                                    <th>All Drivers</th>
                                                    <th>Home Drivers</th>
                                                    <th>Support Drivers</th>
                                                    <th>Support to Drivers Ratio</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>1</td>
                                                    <td>0</td>
                                                    <td>0</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header ">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0">Routes</h6>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table w-100">
                                                    <thead>
                                                        <tr>

                                                            <th>Routes <br> (home driver)</th>
                                                            <th>Routes <br> (support driver)</th>
                                                            <th>Average <br> Daily Routes</th>
                                                            <th>All Drivers</th>
                                                            <th>Home Drivers</th>
                                                            <th>Support Drivers</th>
                                                            <th>Support to <br> Total Drivers Ratio</th>
                                                            <th>Driver/Route <br> Ratio (all drivers)</th>
                                                            <th>Driver/Route <br> Ratio (home drivers)</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>

                                                            <td class="border-right text-center">2</td>
                                                            <td class="border-right text-center">0</td>
                                                            <td class="border-right text-center">0.29</td>
                                                            <td class="border-right text-center">1</td>
                                                            <td class="border-right text-center">1</td>
                                                            <td class="border-right text-center">0</td>
                                                            <td class="border-right text-center">
                                                                                                0
                                                                                        </td>
                                                            <td class="border-right text-center">
                                                                                            2
                                                            </td>
                                                            <td class="border-right text-center">
                                                                                            2
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                </table>
                                            </div>
                
                                        </div>


                                        <div class="progress mb-4">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 86%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">Working Days</div>
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 14%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">Days Off</div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table w-100 table-hover table-sm border" small="small" hover="hover">
                                                <thead>
                                                <tr>
                                                    <th>Drivers (1)</th>
                                                    <th class="text-center" style="width:100px">Thu </th>
                                                    <th class="text-center" style="width:100px">Fri </th>
                                                    <th class="text-center" style="width:100px">Sat </th>
                                                    <th class="text-center" style="width:100px">Sun </th>
                                                    <th class="text-center" style="width:100px">Mon </th>
                                                    <th class="text-center" style="width:100px">Tue </th>
                                                    <th class="text-center" style="width:100px">Wed  (Today) </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    <td class=" bg-green-100 " style="border-right: 1px solid #b1b1b1;">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <a href="https://bryanstonlogistics.karavelo.co.uk/users/9000" target="_blank" style="color:#000;"><span>Marian Cotea </span></a>
                                                                <span class="label label-success">6 day</span>
                                                                 </div>
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-red-100 text-red-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    <td class="text-center border  bg-green-100 text-green-200 ">
                                                    <svg class="icon" style="width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"></path></svg> 
                                                    </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div> 
        </div>
    </div>

<?php include('footer.php');?>
 
</div>

<?php include('footerScript.php');?>

<script>
function loadtable()
{
  var table = $('#myTable').DataTable();
  table.ajax.reload();
}
</script>    

</body>
</html>