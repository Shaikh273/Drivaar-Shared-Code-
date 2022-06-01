<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    
}
else
{
    if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    {
       header("location: login.php");
    }
    else
    {
       header("location: login.php");  
    }
}
//include('authentication.php');
include('config.php');
$page_title="Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
</head>
    <body class="skin-default-dark fixed-layout">
        <?php
        
            include('loader.php');
        ?>
        
        <div id="main-wrapper" id="top">
            <?php
                include('header.php');
            ?>
            
          
            
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <!-- <h4 class="text-themecolor">Dashboard</h4> -->
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
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
              <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Visit</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success">1659</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Page Views</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash2"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple">7469</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Unique Visitor</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash3"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info">6011</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Bounce Rate</h4>
                                <ul class="list-inline two-part">
                                    <li>
                                        <div id="sparklinedash4"><canvas width="67" height="30" style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas></div>
                                    </li>
                                    <li class="text-right"><i class="ti-arrow-down text-danger"></i> <span class="text-danger">18%</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Total Visit <small class="pull-right text-success"><i class="fa fa-sort-asc"></i> 18% High then last month</small></h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline8"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Site Traffic</h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline9"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body analytics-info">
                                <h4 class="card-title">Site Visit</h4>
                                <div class="stats-row">
                                    <div class="stat-item">
                                        <h6>Overall Growth</h6>
                                        <b>80.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Montly</h6>
                                        <b>15.40%</b></div>
                                    <div class="stat-item">
                                        <h6>Day</h6>
                                        <b>5.50%</b></div>
                                </div>
                                <div id="sparkline10"><canvas width="490" height="50" style="display: inline-block; width: 490.984px; height: 50px; vertical-align: top;"></canvas></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                
                        </div>
                       
                    </div>
                    
                    
                    <div class="container-fluid">
                    

                    <div class="row pl-2">
                        
                        <table class="
                               table table-striped w-100
                                mb-4 border
                               " style="border: 1px solid #b3b3b3!important;">


                            <thead>
                                <tr>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">Name</th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                28 Sun
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="U0QJTqJZ2xCOc6NKjZ8k" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="3">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-3-content4"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="toMtvhdGluuJ4oqZ8hPR" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                </div>


                                            </div>
                                        </div>
                                    </th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                1 Mon
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="7YL8oWHPT4isfufxsITm" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="8">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-8-content9"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="CG1eXPRPHS3KFpC12mTk" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                </div>


                                            </div>
                                        </div>
                                    </th>
                                    <th class=" bg-yellow-lighter  border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                2 Tue
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="hfj3E3r9hawlnSumA6De" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="13">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-13-content14"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="Ps3NL3uWYcOJCha8GBav" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                    <h6>How many people you need for today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="18">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="input-group mb-3">
                                                                    <select class="custom-select dont" wire:model="people">
                                                                        <option selected="">Choose...</option>
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>

                                                </div>

                                                <div class="bg-blue-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Driver Assignment</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="assignAll('2021-03-02')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> Assign all unassigned drivers
                                                    </a>
                                                </div>

                                                <div class="bg-red-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Everyone Off</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="allOff('2021-03-02')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> Assign day off all drivers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                3 Wed
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="M1h4GTMMKfNSqIvpbScu" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="19">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-19-content20"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="aX7InKxXfO0J37i5FNdJ" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                    <h6>How many people you need for today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="24">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="input-group mb-3">
                                                                    <select class="custom-select dont" wire:model="people">
                                                                        <option selected="">Choose...</option>
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>

                                                </div>

                                                <div class="bg-blue-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Driver Assignment</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="assignAll('2021-03-03')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> Assign all unassigned drivers
                                                    </a>
                                                </div>

                                                <div class="bg-red-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Everyone Off</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="allOff('2021-03-03')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> Assign day off all drivers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                4 Thu
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="w7HJ8G63U3EvBBHUGoCN" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="25">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-25-content26"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="UEUPKLgsPe9NojvhZx0m" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                    <h6>How many people you need for today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="30">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="input-group mb-3">
                                                                    <select class="custom-select dont" wire:model="people">
                                                                        <option selected="">Choose...</option>
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>

                                                </div>

                                                <div class="bg-blue-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Driver Assignment</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="assignAll('2021-03-04')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> Assign all unassigned drivers
                                                    </a>
                                                </div>

                                                <div class="bg-red-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Everyone Off</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="allOff('2021-03-04')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> Assign day off all drivers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                5 Fri
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="YTvjOl8KomizuYFJkJOU" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="31">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-31-content32"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="sR3rdc0Wz9WxMepYhLpI" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                    <h6>How many people you need for today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="36">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="input-group mb-3">
                                                                    <select class="custom-select dont" wire:model="people">
                                                                        <option selected="">Choose...</option>
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>

                                                </div>

                                                <div class="bg-blue-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Driver Assignment</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="assignAll('2021-03-05')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> Assign all unassigned drivers
                                                    </a>
                                                </div>

                                                <div class="bg-red-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Everyone Off</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="allOff('2021-03-05')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> Assign day off all drivers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class=" border" style="border: 1px solid #b3b3b3!important;">
                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-blue-darkest text-center d-block">
                                                6 Sat
                                            </a>
                                            <div class="dropdown-menu pt-3" style="min-width: 400px;">

                                                <div wire:id="kFHJvEEsSDbofHGQ42wv" class="px-3 pb-3" wire:loading.class="bg-grey-lightest">
                                                    <h6>What is happening today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="37">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="form-group" data-aire-component="group" data-aire-for="content">


                                                            <textarea class="form-control" data-aire-component="textarea" name="content" placeholder="Add note to assist with your schedulling today..." wire:model.defer="content" data-aire-for="content" id="__aire-37-content38"></textarea>



                                                            <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
                                                            </div>

                                                        </div>



                                                        <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
                                                            Save
                                                        </button>



                                                    </form>

                                                </div>
                                                <div wire:id="Hoaf1pJrrIGlQfNQu3is" class="px-3 pb-3" wire:loading.class="bg-grey-lightest" xmlns:wire="http://www.w3.org/1999/xhtml">
                                                    <h6>How many people you need for today?</h6>

                                                    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="save" data-aire-id="42">

                                                        <input type="hidden" name="_token" value="PdEI9Mo67xvfRAR9O91rep3OVW4QFaoGWN2XZrH3">


                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="input-group mb-3">
                                                                    <select class="custom-select dont" wire:model="people">
                                                                        <option selected="">Choose...</option>
                                                                        <option value="0">0</option>
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                        <option value="11">11</option>
                                                                        <option value="12">12</option>
                                                                        <option value="13">13</option>
                                                                        <option value="14">14</option>
                                                                        <option value="15">15</option>
                                                                        <option value="16">16</option>
                                                                        <option value="17">17</option>
                                                                        <option value="18">18</option>
                                                                        <option value="19">19</option>
                                                                        <option value="20">20</option>
                                                                        <option value="21">21</option>
                                                                        <option value="22">22</option>
                                                                        <option value="23">23</option>
                                                                    </select>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>



                                                    </form>

                                                </div>

                                                <div class="bg-blue-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Driver Assignment</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="assignAll('2021-03-06')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> Assign all unassigned drivers
                                                    </a>
                                                </div>

                                                <div class="bg-red-lightest border-top border-bottom px-3 py-3">
                                                    <h6>Everyone Off</h6>

                                                    <a href="#" class="btn btn-sm btn-secondary" wire:click.prevent="allOff('2021-03-06')">
                                                        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg> Assign day off all drivers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td></td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="7KZh6hgFwL4mLFGleD37" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-02-28-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 35%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="8 assigned to work" data-original-title="" title="">
                                                        8             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 65%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="15 people will be off" data-original-title="" title="">15</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-02-28-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Krystian Golebieski
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Mohammed Elrashid
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - mohammed nasar
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Eugen Zamfirescu
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ben Staton-Bevan
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ali Memeno Assan
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Lee Morris
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Alexandru Csizmarik
                                                                            </td>
                                                                        </tr>
                                                                         <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Alexandru Csizmarik
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-02-28', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="klg3cTaA6eA5pMo6L9ig" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-01-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="7 assigned to work" data-original-title="" title="">
                                                        7             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 70%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="16 people will be off" data-original-title="" title="">16</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-01-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ben Staton-Bevan
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Krystian Golebieski
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ilie Alexandru Simion
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Lorna Thurston
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Lee Morris
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Alexandru Csizmarik
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - mohammed nasar
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Eugen Zamfirescu
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Eugen Zamfirescu
                                                                            </td>
                                                                        </tr>
                                                                         <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Alexandru Csizmarik
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-01', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="aGvcouYtTR0qDksSJScm" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-02-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 39%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="9 assigned to work" data-original-title="" title="">
                                                        9             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 61%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="14 people will be off" data-original-title="" title="">14</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-02-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Darius Harrison
                                                                            </td>
                                                                        </tr>
                                                                         <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Darius Harrison
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Krystian Golebieski
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Eugen Zamfirescu
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ilie Alexandru Simion
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Lorna Thurston
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Lee Morris
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Dylan James Pritchard
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - mohammed nasar
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Mohammed Elrashid
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="14" class="bg-green-lightest border" style="text-align: left;">
                                                                                <svg class="icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                        <strong>08:45</strong>
                                                                                - Ali Memeno Assan
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-02', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="RGj1eVSqiMjS7ZOQ0CGT" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-03-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 39%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="9 assigned to work" data-original-title="" title="">
                                                        9             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 61%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="14 people will be off" data-original-title="" title="">14</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-03-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="text-center py-4">There are no waves
                                                                                set up for
                                                                                today. 😞
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-03', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="7iGR2bg1PBfLlMT7rFGB" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-04-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 48%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="11 assigned to work" data-original-title="" title="">
                                                        11             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 52%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="12 people will be off" data-original-title="" title="">12</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-04-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="text-center py-4">There are no waves
                                                                                set up for
                                                                                today. 😞
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-04', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="VJVGerMPQ0BKM5UqxDqT" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-05-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 39%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="9 assigned to work" data-original-title="" title="">
                                                        9             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 61%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="14 people will be off" data-original-title="" title="">14</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-05-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="text-center py-4">There are no waves
                                                                                set up for
                                                                                today. 😞
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-05', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                    <td class="border  text-center pb-1" style="border: 1px solid #b3b3b3!important;">
                                        <div wire:id="YuDgAcsqSHn7bzOxdCW7" style="cursor: pointer">
                                            <a data-toggle="modal" data-target="#js-2021-03-06-56">
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: 43%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="10 assigned to work" data-original-title="" title="">
                                                        10             </div>
                                                    <div class="progress-bar bg-red-light" role="progressbar" style="width: 57%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-title="13 people will be off" data-original-title="" title="">13</div>
                                                </div>
                                            </a>

                                            <div wire:ignore="" class="modal modal-fullscreen-sm fade " id="js-2021-03-06-56">
                                                <div class="modal-dialog
                                                     modal-lg " role="document">
                                                    <div class="modal-content
                                                         ">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Today's wave of drivers</h5>

                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body ">
                                                            <div class="table-responsive-lg">
                                                                <table class="
                                                                       table w-100
                                                                       table-sm my-4 border
                                                                       ">


                                                                    <thead>
                                                                        <tr>
                                                                            <th class="border">8am</th>
                                                                            <th class="border">9am</th>
                                                                            <th class="border">10am</th>
                                                                            <th class="border">11am</th>
                                                                            <th class="border">12pm</th>
                                                                            <th class="border">1pm</th>
                                                                            <th class="border">2pm</th>
                                                                            <th class="border">3pm</th>
                                                                            <th class="border">4pm</th>
                                                                            <th class="border">5pm</th>
                                                                            <th class="border">6pm</th>
                                                                            <th class="border">7pm</th>
                                                                            <th class="border">8pm</th>
                                                                            <th class="border">9pm</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="14" class="text-center py-4">There are no waves
                                                                                set up for
                                                                                today. 😞
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

                                        <a href="#" wire:click.prevent="$emit('bulk.action.show', '2021-03-06', 56)">
                                            <small class="text-grey-darker">Bulk Action »</small>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="p-0 border-0 bg-grey-lighter" style="    background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 2rem 2rem;}">
                                        <!--<div class="text-center py-2" data-toggle="tooltip" data-title="This will auto assign drivers to working, If their 7th day - will be put to Day Off (Yellow cells)" data-original-title="" title="">-->
                                        <!--    <a href="#" wire:click.prevent="assignWeek('2021-03-06')" class="btn btn-primary btn-sm">-->
                                        <!--        <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M224 96l16-32 32-16-32-16-16-32-16 32-32 16 32 16 16 32zM80 160l26.66-53.33L160 80l-53.34-26.67L80 0 53.34 53.33 0 80l53.34 26.67L80 160zm352 128l-26.66 53.33L352 368l53.34 26.67L432 448l26.66-53.33L512 368l-53.34-26.67L432 288zm70.62-193.77L417.77 9.38C411.53 3.12 403.34 0 395.15 0c-8.19 0-16.38 3.12-22.63 9.38L9.38 372.52c-12.5 12.5-12.5 32.76 0 45.25l84.85 84.85c6.25 6.25 14.44 9.37 22.62 9.37 8.19 0 16.38-3.12 22.63-9.37l363.14-363.15c12.5-12.48 12.5-32.75 0-45.24zM359.45 203.46l-50.91-50.91 86.6-86.6 50.91 50.91-86.6 86.6z"></path></svg>                                    Auto Assign Drivers for the Week-->
                                        <!--    </a>-->
                                        <!--</div>-->
                                    </td>
                                </tr>
                                <tr class="position-relative">

                                    <td>Memeno Assad</td>


                                    <td id="e675bf86-bde3-459b-adc6-581a3aa58538" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>

                                        <!--<div class="text-grey-darker whitespace-no-wrap">-->



                                        <!--    <div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <!--<div class="text-grey-darkest">-->
                                        <!--    <small>Day Off</small>-->
                                        <!--</div>-->

                                    </td>


                                    <td id="4f854ce7-3db3-4d46-9ae4-dcf80e313ede" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>

                                    </td>


                                    <td id="2ee592df-9843-4525-98a4-63b919d68092" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label> <small class="pl-2">Day Off</small> </div>

                                        </div>
                                    </td>


                                    <td id="15ae2086-6357-44f9-949b-a99d5986b417" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>


                                    </td>

 <td id="15ae2086-6357-44f9-949b-a99d5986b417" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>


                                    </td>
                                    
                                    <td id="71979550-49ba-44bf-b555-0e79eb1ad19d" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>

                                    </td>


                                    <td id="8c77d7fd-d524-47bf-8289-3a661923a150" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Day Off</small></div>

                                        </div>

                                    </td>

                                </tr>
                                
                                <tr class="position-relative">

                                    <td>Ali Assad</td>
                                    
                                    <td id="e8e83f1a-c4b8-4ae2-ae57-266553154b29" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label><small class="pl-2">Unasigned</small></div>

                                        </div>

                                    </td>


                                    <td id="5df64ed1-d155-4e65-a3b3-330741dfffce" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="4bcd8029-90d6-4365-b6a6-511c49a4f96e" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="4ed1cc1f-c1ef-41d1-9f90-33930bcf2cd7" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="ad76e668-607a-4ebb-afaf-68c1ba10936d" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="a4a974a5-580d-4c6c-bb7c-7492df1fa864" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="da33fdba-f344-4f08-842a-30e9d3981f52" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="" data-original-title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </label></div>
                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

                                </tr>
                                
                                
                                <tr class="position-relative">

                                    <td id="c956793b-8649-43b7-8b65-2d2f19255115" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           Assad

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="c65fcc1e-ed41-47e1-bc3b-4acad0905db3" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="59db9526-671e-4b7b-8760-48e04df42559" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="c191b8f3-2204-410e-b7bc-28371c902b6a" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="f98d1b1d-174a-4143-be61-990c8506d982" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="3bc54251-12b1-4adf-b277-bc3a65ed8732" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>
                                    
                                    <td id="3bc54251-12b1-4adf-b277-bc3a65ed8732" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="25ee04c4-08f1-4887-8ed1-f863c99ad486" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="cb026328-8282-4350-8a49-d8034ab9b729" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="7b2d6c43-c684-431a-8965-20e4f1972341" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="3ee8b339-d7b7-48fb-afa9-f5ee52de16ad" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="0fb1da57-3a8a-49b0-85b9-0fd25bb1a056" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="f89c83d6-af1d-4cce-bcb7-46093396e241" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="a993f086-45a6-4255-9c0c-d4ccc4c484f3" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6483, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ben Staton-Bevan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="3ad8d24f-39aa-4ba6-a2c9-d8043a2a5fa0" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="fe9c3d9e-59a9-43b6-9810-a13ebaf78969" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="5f6d761b-280f-40bf-a4c2-82c500afe9dc" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="03fae807-ce09-467c-9cf3-ce4ad6154560" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="b427bf29-d17c-49a9-8461-b08b3088ab69" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="c67d2b16-6eb2-4f42-93aa-6815b368f388" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="bf638b9f-279a-4e53-b43c-463200c1decb" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #FFF9C2;
                                        " wire:click="$emit('daySelected', 8332, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Chloe Bateman

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (7)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="06772d9e-88a9-43c0-be84-56802d540537" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 7590, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="5532ce47-eec8-4543-90e0-54579283616b" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="0bd84e47-42d1-4472-83e8-479fb8407631" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="abd45d44-459b-4a3b-8884-d03e6d47d7ea" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="9b52c3ed-8091-456c-9aee-12fcfdc6a0f8" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="f24ff2c2-88be-4ce2-b611-8258fbeb9b5f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="97cfad39-3644-412e-b542-0a63b40e27f0" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7590, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="5 working days" data-original-title="" title="">
                                            <small><strong>5</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Darius Harrison

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="b7d21e21-715f-43ad-ba2f-08e21375a2aa" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="1aec8987-18f2-4aca-bea1-a9c26ec65510" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="d0808d25-5aba-441e-805b-0fb20518c6aa" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="cd915362-9049-4ec8-801c-6eff348a5658" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="9c88deff-fcac-4807-92e8-78b04b3efb2a" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="69dd10c9-f54b-4c4a-b21d-e1605a78be9b" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="667cdb3c-3908-44af-ae5f-4169c7628c93" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 6938, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            David Wilkins

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="c5a036c7-c663-4e5a-b546-1eb0d521398d" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5376, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="ecda2a42-5114-4e1a-8a36-850459360d50" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="b010d8cd-d4ed-4968-b0e3-c866e1b8c320" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="d23e7974-2ee9-4c46-86c7-85545949e490" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="42b3b345-01b3-425d-9a0e-47fef17a612b" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="e1e19168-7141-4ff5-9894-b8ea7573a8a5" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="6fcb0c09-f09d-4bda-81b6-6731e799de50" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5376, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="4 working days" data-original-title="" title="">
                                            <small><strong>4</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Dylan James Pritchard

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="6248905e-1310-4281-8e32-40e14360ece8" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Has clocked in for the day" data-original-title="" title="">
                                                <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                </span>

                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="e936dcf8-c491-4c27-bb1b-aa66a04267cd" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Has clocked in for the day" data-original-title="" title="">
                                                <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                </span>

                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="e626acb3-acd0-4d82-b6f7-574b768e0661" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="96f26505-97bf-4e57-b548-3c52c3be449d" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="76212fe8-ca84-417e-aa51-c18180474d12" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="88df0b12-d03e-491a-9e91-745c588c096b" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="7f664937-d907-41cd-8873-52a8baac68f5" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 8334, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Eugen Zamfirescu

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="015175e8-4be8-46c7-b6fc-04b3b5f5a1cc" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>



                                    <td id="454e3f8c-5b72-4d4b-a5d7-67a87d0c6af1" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="0b720af8-5c2a-4a90-abb8-68eb211bf116" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="b5bf99d4-1e82-45ff-9d0f-b3e0f9483b05" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="5595c04e-db78-4d44-b0f2-006dcd3d2afa" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="2331dc7e-2749-427f-9375-cfcb2aedbd68" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="b450785d-10c0-4793-ab98-ba9f7f918c0b" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #FFF9C2;
                                        " wire:click="$emit('daySelected', 5508, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Fahretin Bunyamin Altay

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (7)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="1c892744-abf6-4830-9ba2-7ff508509ffe" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6366, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="3443bb04-5fac-49f6-b856-196275fc5f07" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="2eff4000-741d-4715-9d49-9898f74aab65" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="fc6b5451-0b31-4705-975a-088a06036c15" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="9dcbb81d-a4fd-49cd-941c-981d2a9415ad" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="6e823393-f4d8-4589-a7a9-cb0a5d779361" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="aa4f43a4-aa5f-4a08-91f6-c9f2d6a5f17f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6366, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="2 working days" data-original-title="" title="">
                                            <small><strong>2</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jacob Stone

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="8a31ae8b-4268-4547-b1aa-cd2a3360a5cd" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="eef162b7-4b59-4f89-ba92-d9bc80f5261a" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="eac69e99-4a09-4aa6-ac2d-147d82590496" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="2049f707-a583-4245-a21b-46764242a2ce" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="95c9ad45-c617-49f7-ae88-b80611a7b13e" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="1ac55d7a-e6e9-4f88-8507-72a7a33c02ad" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="b3940c25-3610-481f-90e8-e0456b30bf36" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #FFF9C2;
                                        " wire:click="$emit('daySelected', 8286, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Jean Claude Cisse

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (7)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="42ff1e56-4b3e-4f30-9fd4-f602b5734e3f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="db42237b-b47f-4892-a363-efa95d61471c" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="50c24226-ed19-4bea-843c-b50a7789222e" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                        </div>
                                    </td>


                                    <td id="39f56a9f-a565-468b-98dc-64406a30a3cd" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="626670a8-fd6f-434b-8132-8ecc3926c828" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="77ecfdae-a283-42f7-a4a7-af779fd2fe1c" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="8b447c83-caad-4a0d-98be-557e84211aa9" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8250, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Krystian Golebieski

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                                <tr class="position-relative">

                                    <td id="12aa74ce-7684-46f1-bdf4-d976e3ca0cac" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (2)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="b5f9ff4f-6947-4110-82e2-2f6af4046122" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (3)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="2c25a9b6-4481-4da9-b386-82beb30c0c60" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (4)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="b9b200e9-21c1-4aec-bc8e-ea5c3349d840" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (5)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="52616e00-3651-4fd9-8467-7b7868064791" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>


                                    <td id="5c0ffabc-961c-4ad8-847e-4af4defab473" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="f58f2b28-f666-410d-b05e-4d4f34f8b388" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 6566, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lee Morris

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (1)
                                            </strong>



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Working</small>
                                        </div>

                                    </td>

                                </tr>
                      
                            </tbody>
                        </table>
                    </div>

                    
                       



                    <div class="right-sidebar ps ps--theme_default" data-ps-id="9f66ecaa-7214-2542-b1d8-3d3647921e92">
    <div class="slimscrollright">
        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body">
            <ul id="themecolors" class="m-t-20">
                <li><b>With Light sidebar</b></li>
                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme">1</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme working">7</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
            </ul>
            <ul class="m-t-20 chatonline">
                <li><b>Chat option</b></li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                </li>
                <li>
                    <a href="javascript:void(0)"><img src="../assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                </li>
            </ul>
        </div>
    </div>
<div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 0px; right: 0px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>                </div>
                    
                    
                    
                    <div  style="z-index:1;">
                    <ul class="jq-dice-menu" default-open="false" layout="column" reverse="false" snap-to="right" offset="35%" show-hints="true" hints-order="footer">
            <div class="jq-items">
                <!-- first element as a switch button -->
                <li state="close"><span class="fa fa-th-large"></span></li>
                <!-- page anchor to paragraph 2 -->
                <li><span class="fa fa-header" href="#para2" hint="para2" data-toggle="modal" data-target="#myModal"></span></li>
                <!-- page anchor to paragraph 3 -->
                <li><span class="fa fa-arrows-v" href="#para3" hint="para3" data-toggle="modal" data-target="#Modal"></span></li>
                <!-- open a page in a new window -->
                <!-- open a page in current window -->
                <li><span class="fa fa-github"  hint="Github" data-toggle="modal" data-target="#Modal3"></span></li>
                <!-- page link without hint -->
                <!-- link to phone number -->
                <!-- link to email address -->
                <li><span class="fa fa-envelope"  hint="Email for support" data-toggle="modal" data-target="#Modal4"></span></li>
                <!-- page anchor to the top of the page -->
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
            
       
        <!--<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>-->
        <!--<script src="../assets/node_modules/popper/popper.min.js"></script>-->
        <!--<script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>-->
        <!--<script src="dist/js/perfect-scrollbar.jquery.min.js"></script>-->
        <!--<script src="dist/js/waves.js"></script>-->
        <!--<script src="dist/js/sidebarmenu.js"></script>-->
        <!--<script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>-->
        <!--<script src="dist/js/custom.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>-->
        <!--<script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>-->
        <!--<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>-->
        <!--<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>-->
        

<script>
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#example tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
            } );
            // DataTable
            var table = $('#example').DataTable({
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
         
                        $( 'input', this.footer() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that
                                    .search( this.value )
                                    .draw();
                            }
                        } );
                    } );
                }
            });
         
        });   
</script>
<script src="dist/jq.dice-menu.js"></script>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="card mb-4">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">My Tasks</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <div class="border border-dashed cursor-pointer px-2 py-1 rounded mb-2 " wire:click="toggle(97763)">
                         <svg class="icon mr-1 text-grey-dark d-inline" style="height: 15px; width: 15px;border-radius: 50%;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200z"></path></svg>                            
                         Hi Asad, Please add dashboard for on road hours. Thanks, Omar
             </div>
        </div>
    
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="Modal4">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div wire:id="BcsR7UwUGyUc4E9tmae2" xmlns:wire="http://www.w3.org/1999/xhtml" class="position-relative">
    <div wire:loading="" class="position-fixed top-0 right-0 bottom-0 bg-yellow">

    </div>
    <form action="" method="POST" class="" data-aire-component="form" wire:submit.prevent="send" data-aire-id="43">

            <input type="hidden" name="_token" value="weHzvy6b22cUeGmzxxHz4dmvRjw45NRK0tmZvYOA">
        
        
    <div class="card no-shaddow mb-4">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Send message to all drivers in Avonmounth (DBS2) - Amazon Logistics (BS11 0YH) (23)</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <div class="form-group" data-aire-component="group" data-aire-for="content">
    <label class=" cursor-pointer" data-aire-component="label" for="js-message">
    Your message:
</label>


            <textarea class="form-control" data-aire-component="textarea" name="content" id="js-message" wire:model.debounce.250ms="content" data-aire-for="content"></textarea>

    
            <small class="form-text text-muted" data-aire-component="help_text" data-aire-validation-key="group_help_text" data-aire-for="content">
            This will send an email to all the drivers in the depot!
        </small>
    
    <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="content">
            </div>

</div>


            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" wire:model="attachment">
                <label class="custom-file-label" for="validatedCustomFile">
                                        Attach a file...
                                    </label>
            </div>
        </div>
    
            <div class="card-footer">
            <button class="btn btn-primary " data-aire-component="button" type="submit" wire:loading.attr="disabled">
    Send Message
</button>
        </div>
    </div>
    
    
    
</form>

</div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
    <div class="modal fade" id="Modal3">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="
    card
                        mb-4 no-shadow
    ">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Vehicle Inspection Issues</h6>
                                    <div></div>
                            </div>
        </div>
    
            <div class="card-body">
            <table class="table">
            <tbody>
            <tr>
                <td class="border-top-0 pl-0"><svg class="icon text-warning d-inline" style="height|: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> <span class="pl-1">Open</span></td>
                <td class="border-top-0 pr-0 text-right money"><strong>36</strong> issues</td>
            </tr>
            <tr>
                <td class="pl-0"><svg class="icon text-green-dark d-inline" fill="currentColor" style="height|: 15px; width: 15px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg> <span class="pl-1">Resolved</span></td>
                <td class="pr-0 text-right money"><strong>0</strong> issues</td>
            </tr>
            </tbody>
        </table>
        </div>
    
            <div class="card-footer">
            <a href="/issues">View all issues</a>
        </div>
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>


  <div class="modal fade" id="Modal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <div class="
    card
                        no-shadow
    ">
            <div class="card-header ">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0">Vehicle Renewals</h6>
                            </div>
        </div>
    
            <div class="card-body">
            <table class="table">
            <tbody>
            <tr>
                <td class="border-top-0 pl-0"><svg class="icon text-danger d-inline" style="height: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> <span class="pl-1">Overdue</span></td>
                <td class="border-top-0 pr-0 text-right money text-danger"><strong>1</strong> overdue</td>
            </tr>
            <tr>
                <td class="pl-0"><svg class="icon text-warning d-inline" style="height: 15px; width: 15px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg> <span class="pl-1">Due soon</span></td>
                <td class="pr-0 text-right money"><strong>1</strong> due soon</td>
            </tr>
            </tbody>
        </table>
        </div>
    
            <div class="card-footer">
            <a href="#">View All Renewals</a>
        </div>
    </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
   <?php

        include('footerScript.php');

    ?>

</body>

</html>