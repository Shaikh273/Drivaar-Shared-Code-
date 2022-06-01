<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['load']) || !isset($_SESSION['uid'])) {
    header("location: login.php");
}
$_SESSION['page_id'] = 1;
include('authentication.php');
include('config.php');
$page_title = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Favicon icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <!--  Dice Menu stylesheet -->
        <link rel="stylesheet" href="../src/scss/jq.dice-menu.min.css" />
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <title>Elite Admin Template - The Ultimate Multipurpose admin template</title>
        <!-- Custom CSS -->
        <link href="dist/css/style.min.css" rel="stylesheet">
        <title><?php echo $page_title; ?></title>
        <?php include('head.php'); ?>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link href="dist/css/style.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
        <link rel="stylesheet" href="https://https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
        <script>
            $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
            });
        </script>
        <style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}

            @import url('https://fonts.googleapis.com/css?family=Montserrat:600&display=swap');

            * {
                margin: 0;
                padding: 0;
                list-style: none;
                text-decoration: none;
                box-sizing: border-box
            }

            body {
                font-family: 'Montserrat', sans-serif;
                background: url(https://i.imgur.com/3Ad4Atf.jpg);
                background-position: center;
                background-size: cover;
                height: 100vh
            }

            .cnav {
                position: fixed;
                width: 60px;
                margin-top: 150px;
                transition: all 0.3s linear;
                box-shadow: 2px 2px 8px 0px rgba(0, 0, 0, .4)
            }

            .cnav ul {
                margin-top: 0;
                margin-bottom: 0rem
            }

            .cnav li {
                height: 45px;
                position: relative
            }

            .cnav li a {
                color: #fff !important;
                display: block;
                height: 100%;
                width: 100%;
                line-height: 45px;
                padding-left: 25%;
                border-bottom: 1px solid rgba(0, 0, 0, .4);
                transition: all .3s linear;
                text-decoration: none !important
            }

            .cnav li:nth-child(1) a {
                background: #4267B2
            }

            .cnav li:nth-child(2) a {
                background: #1DA1F2
            }

            .cnav li:nth-child(3) a {
                background: #E1306C
            }

            .cnav li:nth-child(4) a {
                background: #2867B2
            }

            .cnav li:nth-child(5) a {
                background: #333
            }

            .cnav li:nth-child(6) a {
                background: #ff0000
            }

            .cnav li a i {
                position: absolute;
                top: 14px;
                left: 24px;
                font-size: 15px
            }

            .cul li a span {
                display: none;
                font-weight: bold;
                letter-spacing: 1px;
                text-transform: uppercase
            }

            .ca:hover {
                z-index: 1;
                width: 200px;
                border-bottom: 1px solid rgba(0, 0, 0, .5);
                box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3)
            }

            .cul li:hover a span {
                padding-left: 30%;
                display: block;
                font-size: 15px
            }
            .has-search .form-control-feedback {
                position: absolute;
                z-index: 2;
                display: block;
                width: 2.375rem;
                height: 2.375rem;
                line-height: 2.375rem;
                text-align: center;
                pointer-events: none;
                color: #aaa;
            }
            .has-search .form-control {
                padding-left: 2.375rem;
            }

            .tfoot input {
                width: 100%;
                padding: 3px;
                box-sizing: border-box;
            }

        </style>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        
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
            <nav class="cnav">
                <ul class="cul">
                    <li><a href="#"><i class="fab fa-facebook-f ca"></i><span>Facebook</span></a></li>
                    <li><a href="#"><i class="fab fa-twitter ca"></i><span>Twitter</span></a></li>
                    <li><a href="#"><i class="fab fa-instagram ca"></i><span>Instagram</span></a></li>
                    <li><a href="#"><i class="fab fa-linkedin-in ca"></i><span>Linkedin</span></a></li>
                    <li><a href="#"><i class="fab fa-github ca"></i><span>Github</span></a></li>
                    <li><a href="#"><i class="fab fa-youtube ca"></i><span>Youtube</span></a></li>
                </ul>
            </nav>
            <div class="page-wrapper">
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
                                <tr>
                                    <td style="border: 1px solid #b3b3b3;">Ali Memeno Assan</td>
                                    <td id="6799015f-c4ea-4d99-ae78-b815cf893393" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #dae1e7;
                                        " wire:click="$emit('daySelected', 8241, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                                <script>
                                                $(document).ready(function(){
                                                  $('[data-toggle="tooltip"]').tooltip();   
                                                });
                                                </script>

                                        </div>

                                      <!--  <div class="text-grey-darker whitespace-no-wrap">


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Has clocked in for the day" data-original-title="" title="">
                                                <svg class="icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z"></path></svg>                </span>

                                            <div>
                                            </div>
                                        </div>-->
                                       <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>
                                    <td id="67f47c41-8a9c-483d-94ee-fcf043efc242" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #dae1e7;
                                        " wire:click="$emit('daySelected', 8241, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Other Depot</small>
                                        </div>

                                        <div class="position-absolute px-2 bg-grey-lightest" style="bottom:0; left:0; right:0; border-top: 1px solid #b3b3b3;line-height: 1.4;">
                                            <code class="d-inline-block mr-1" style="color:#3d4852;">08:45</code>


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>
                                    <td style="border: 1px solid #b3b3b3;"></td>
                                    <td style="border: 1px solid #b3b3b3;"></td>
                                    <td style="border: 1px solid #b3b3b3;"></td>
                                    <td style="border: 1px solid #b3b3b3;"></td>
                                    <td style="border: 1px solid #b3b3b3;"></td>
                                </tr>
                                <tr class="position-relative">

                                    <td>Memeno Assad</td>


                                    <td id="e675bf86-bde3-459b-adc6-581a3aa58538" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="4f854ce7-3db3-4d46-9ae4-dcf80e313ede" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="2ee592df-9843-4525-98a4-63b919d68092" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="15ae2086-6357-44f9-949b-a99d5986b417" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>

 <td id="15ae2086-6357-44f9-949b-a99d5986b417" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>
                                    
                                    <td id="71979550-49ba-44bf-b555-0e79eb1ad19d" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="8c77d7fd-d524-47bf-8289-3a661923a150" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5513, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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

                                    <td>Ali fateh</td>


                                    <td id="2a1014f9-2f7c-4f95-a837-fd7c260ec035" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="b75091e0-cfc9-4948-9521-960939af5453" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="12bacd0e-b96e-4523-a863-a48274e946d9" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="d702b222-1856-4116-9390-ae2cbdcb8294" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="ed17f156-4513-4404-9b1e-8dca63100a1f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="24171696-9089-4b6e-9475-7168378d1a6d" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>
                                    
                                    <td id="24171696-9089-4b6e-9475-7168378d1a6d" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7080, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                           <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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

                                    <td>Ali Assad</td>
                                    
                                    <td id="e8e83f1a-c4b8-4ae2-ae57-266553154b29" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Unasigned</small>
                                        </div>

                                    </td>


                                    <td id="5df64ed1-d155-4e65-a3b3-330741dfffce" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5511, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>
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

                                    <td>Assan</td>

 

                                    <td id="bf07f6d0-769b-4594-832d-910f397eb6c5" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>
                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">
                                            <strong class="d-inline-block mr-1" style="font-size: 11px;" data-toggle="tooltip" data-title="Number of consecutive working days." data-original-title="" title="">
                                                (6)
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


                                        </div>
                                    </td>


                                    <td id="10c03089-34fb-4f94-8be4-12f9bcf8795f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="f1546798-de00-47fb-bdd1-30fbb379c7b6" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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


                                    <td id="3d29c155-1128-49f9-bb42-e04760a55f41" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>

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

<td id="9dd765be-efdf-4aa7-a72c-afc523636639" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4335, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>
                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="438b796f-9164-4b48-92e4-2623e17d88fa" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>
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


                                    <td id="a51443b7-591b-40a7-b87b-3c5863793c03" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 8283, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="6 working days" data-original-title="" title="">
                                            <small><strong>6</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            <div class="container">
                                              <label data-toggle="tooltip" title="Ilie Alexandru Simion"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                            </div>
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

                                    <td id="c956793b-8649-43b7-8b65-2d2f19255115" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4335, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Ali Memeno Assan

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
                                <tr class="position-relative">

                                    <td id="ecd0c51e-49d9-4232-9672-c58b3b7d099e" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7691, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="682d60f9-1fd2-40cb-bb63-43eea5225322" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

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


                                    <td id="c9d50592-201c-460a-9520-03261a99c295" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

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


                                    <td id="04511576-6549-4c6c-b792-73b5e93a4b49" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

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


                                    <td id="e4ecd884-8769-432b-876c-583202d6845c" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

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


                                    <td id="dab40eb6-75b4-4b2a-aff0-a3a0c53e8848" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="880a8d3d-3103-4c1d-865c-a9579d7bcb1e" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 7691, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="4 working days" data-original-title="" title="">
                                            <small><strong>4</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Lorna Thurston

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

                                    <td id="18c95cd6-99c7-48bf-9724-921e479b5629" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="5c32aa5f-ba4f-4c07-bf95-d17bac9a216a" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="fea60a60-48ff-48fa-b13b-451b9c9d78b9" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="18464693-b5a8-4d52-87f0-acd163c4582b" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="c114bf1e-c500-40aa-a756-509f6d8f3d13" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="c9a8ca27-a838-4ba8-8a3d-ea63cfec024f" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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


                                    <td id="87d31551-27f7-469c-8fcf-8ed9318b55c6" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #FFF9C2;
                                        " wire:click="$emit('daySelected', 5891, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Max Wayne Cottle

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

                                    <td id="e8a72ba5-0270-48cc-a779-ad85a455b993" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4337, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

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


                                        </div>
                                    </td>


                                    <td id="e5926917-5678-486a-bc4d-db053b695bf2" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="cfee7a3f-b7c9-41c2-b921-66cab9467dae" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

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


                                    <td id="ef1879c0-0495-49bf-ba34-539f23c2fb26" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

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


                                    <td id="e974e372-0ba6-4af4-93c2-7868eba64049" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

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


                                    <td id="ef976989-42c7-4dff-a65a-db9fa3af88ac" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="2d85d5da-4732-4cc4-ad85-5ea1ced08574" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4337, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="5 working days" data-original-title="" title="">
                                            <small><strong>5</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Mohammed Elrashid

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
                                <tr class="position-relative">

                                    <td id="560e461c-962e-49bd-aa24-e0cc1da3680e" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4363, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

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


                                    <td id="2cd8e1c4-0507-4f04-9fdd-97dacb3fccfc" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

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


                                    <td id="562bc13a-ddb5-4115-89c0-0b7d1b576b87" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

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


                                            <span class="d-inline-block mr-1" data-toggle="tooltip" data-title="Driver has confirmed start time." data-original-title="" title="">
                                                <svg class="icon icon text-success" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>                        </span>
                                        </div>
                                    </td>


                                    <td id="54c85560-527d-49c6-88e0-e141495708bc" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="74037cc0-ae4b-44ea-9b6e-b91c34a2d8ef" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="cefe4627-97a0-4104-97d4-9743feff0bc7" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Holiday</small>
                                        </div>

                                    </td>


                                    <td id="1a5f32f2-e168-4986-ab68-dc11d7dcef77" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #ffe7cf;
                                        " wire:click="$emit('daySelected', 4363, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="3 working days" data-original-title="" title="">
                                            <small><strong>3</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            mohammed nasar

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

                                    <td id="2a93d801-2b4f-483b-882f-6fe14f5a3205" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="51dc57d0-149d-47d4-82d2-4c2b6e463c37" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="5c92181b-d354-4cb0-9ae5-f2b15be13220" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="3eca3ecf-197a-4b8b-90c3-9720740ef558" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="681ec542-4945-4763-b68e-46e989df5e2b" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="7f56485d-89e3-4c82-8199-609741dd443c" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="db7fa053-8269-4535-9f66-3392638126d1" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 6706, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            monika kulicka

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

                                    <td id="248bf836-9b4a-4fde-b1bd-f776a09a4df5" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5861, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="813b731a-ec61-44ea-924e-f03030c56459" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="2770f03a-c456-45f1-844f-cd347d9cb3a6" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #f7e5e1;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

                                        </div>

                                        <div class="text-grey-darker whitespace-no-wrap">



                                            <div>
                                            </div>
                                        </div>
                                        <div class="text-grey-darkest">
                                            <small>Day Off</small>
                                        </div>

                                    </td>


                                    <td id="d68d1e81-1c1f-47dd-bec1-71b137b9e820" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

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


                                    <td id="3767311e-8dc6-4b2e-b881-aeeb2915c03c" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

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


                                    <td id="319d17b3-bc4b-49b1-bf68-f07d5f7020a9" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

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


                                    <td id="5101afd7-1815-486f-a8dd-caa09be6ba3f" class="px-2 py-1 position-relative overflow-hidden " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #a4fdca;
                                        " wire:click="$emit('daySelected', 5861, '2021-03-06')">
                                        <div class="position-absolute bg-white d-flex align-items-center justify-content-center" style="border:1px solid #b3b3b3; top:-1px; right:-1px;height: 15px; width:15px;line-height: 15px;" data-toggle="tooltip" data-title="4 working days" data-original-title="" title="">
                                            <small><strong>4</strong></small>
                                        </div>
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            Sven Schroeder

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

                                    <td id="ef33eefb-8c67-4e20-a54c-e0b74005b2e0" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-02-28')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="7c7e7505-9df4-4040-a65d-e3ff6ff92320" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-01')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="4e331464-68a6-42af-9d30-aa3aa18e7139" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-02')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="f4448ea5-ab23-42f6-92c7-c70974441438" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-03')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="4fab90e8-655b-474f-99ed-b4508956c4aa" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-04')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="c6184c34-8041-436a-8236-9d8b3c5ce9c7" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #fff;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-05')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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


                                    <td id="cbb9e9a9-7177-4153-a668-0b57a157476c" class="px-2 py-1 position-relative overflow-hidden  unassigned " style="border: 1px solid #b3b3b3; cursor: pointer;height: 65px; font-size: 12px; max-width: 191px; line-height: 1.2;
                                        background: #FFF9C2;
                                        " wire:click="$emit('daySelected', 8200, '2021-03-06')">
                                        <div class="overflow-hidden whitespace-no-wrap" style="text-overflow: ellipsis; max-width: 120px;font-size: 12px;color: #3d4852;">
                                            TESTDA1

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
                            </tbody>
                        </table>
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
        <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <script src="../assets/node_modules/popper/popper.min.js"></script>
        <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="dist/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="dist/js/waves.js"></script>
        <script src="dist/js/sidebarmenu.js"></script>
        <script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <script src="dist/js/custom.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/node_modules/sparkline/jquery.charts-sparkline.js"></script>
        <!--<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>-->
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>


        <script>
            $(document).ready(function () {
                // Setup - add a text input to each footer cell
                $('#example tfoot th').each(function () {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                });

                // DataTable
                var table = $('#example').DataTable({
                    initComplete: function () {
                        // Apply the search
                        this.api().columns().every(function () {
                            var that = this;

                            $('input', this.footer()).on('keyup change clear', function () {
                                if (that.search() !== this.value) {
                                    that
                                            .search(this.value)
                                            .draw();
                                }
                            });
                        });
                    }
                });

            });
        </script>
    </body>

</html>