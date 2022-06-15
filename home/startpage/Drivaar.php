<?php
include '../DB/config.php';
if (!isset($_SESSION)) {
    session_start();
}
$mysql = new Mysql();
$mysql->dbConnect();

if (isset($_GET['set'])) {
    unset($_SESSION['load']);
} elseif (isset($_POST['getstarted'])) {
    header("Location: ../login.php");
} elseif (isset($_POST['basic'])) {
    header("Location: ../subscription/basic.php");
} elseif (isset($_POST['pro'])) {
    header("Location: ../subscription/pro.php");
} elseif (isset($_POST['platinum'])) {
    header("Location: ../subscription/platinum.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="Bootstrap\bootstrap-4.3.1-dist\css\bootstrap.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivaar</title>
    <link rel="stylesheet" href="Drivaar.css">

</head>

<body style="background: rgba(27, 39, 99, 0.15) 88.86%">

    <div class="container" style="padding-top: 10px;">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-expand-md navbar-light " style="background:#dddee7">
                    <img src="Images/DRIVAAR LOGO.png" alt="">

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item " style="margin-left: 160px">
                                <a class="nav-link" href="#" style="font-weight: bold; color: #1b2763;">OUR FLEET </a>
                            </li>
                            <li class="nav-item" style="margin-left: 65px">
                                <a class="nav-link" href="#" style="font-weight: bold ; color: #1b2763;">ABOUT</a>
                            </li>
                            <li class="nav-item" style="margin-left: 65px">
                                <a class="nav-link" href="#" style="font-weight: bold; color: #1b2763;">NEWS</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/login') }}" style="font-weight: bold; color: #1b2763; margin-right:149px;">CONTACT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/register') }}" style="font-weight: bold; color: #1b2763;">SIGN IN</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 c1">
                <p class="c2">The world's largest corporate car rental & inspection app</p>
                <p style="font-family:'Montserrat';font-style:normal;font-weight:500;font-size:28px;line-height:42px;color:#1B2763;">
                    Assign cars to your registered <br> drivers, Inspect and keep track of them!!</p>
            </div>
            <div class="col-md-6" style="position: absolute;width: 550px;height: 665px;left: 903px;top: 0px;background: linear-gradient(145.62deg, rgba(78, 54, 252, 0.045) 1.58%, rgba(27, 39, 99, 0.075) 88.86%);border-radius: 0px 0px 350px 350px;">
                <img style="position: absolute;width: 593px;height: 543px;left: -20px;top: 125px;" src="Images/MOCKUPS.png" alt="">
            </div>
            <form class="form-horizontal form-material" id="loginform" action="#" method="POST">
                <button class="c3" type="submit" name="getstarted"><span class="c4">Get Started</span></button>
            </form>
            <span class="c5"><a href="#plans" style="color: #1B2763;">See plans</a></span>

            <div class="c6" id="plans">Subscription Plans</div>

            <button class="c7"> ANNUAL</button>
            <button class="c8"> MONTHLY</button>
        </div>


        <div class="row">
            <div class="col-md-4 c9">
                <div class="c12">

                    <div class="c20"> BASIC</div>
                    <div class="c16">Starter</div>
                    <div class="c17">
                        <ul>
                            <li>02 Depots</li>
                            <li>10 Contractors</li>
                            <li>10 Vehicles</li>
                            <li>20 Workforces</li>
                            <li>400 Gb/s Storage</li>
                            <li>Tutorial Pack</li>
                        </ul>
                    </div>
                    <div class="c18"> $ 79/ <span class="c23">Month</span> </div>
                    <form action="#" method="POST">
                        <button class="c19" type="submit" name="basic">Choose</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4 c10">
                <div class="c13">
                    <div class="c20">PRO</div>
                    <div class="c21">Better Results</div>
                    <div class="c17">
                        <ul>
                            <li>10 Depots</li>
                            <li>50 Contractors</li>
                            <li>50 Vehicles</li>
                            <li>80 Workforces</li>
                            <li>20 Tb/s Storage</li>
                            <li>Tutorial Pack</li>
                            <li>Online Support</li>
                        </ul>
                    </div>
                    <div class="c18"> $ 129/ <span class="c23">Month</span> </div>
                    <form action="#" method="POST">
                        <button class="c19" type="submit" name="pro">Choose</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4 c11">
                <div class="c14">
                    <div class="c20">PLATINUM</div>
                    <div class="c21">Go all in</div>
                    <div class="c17">
                        <ul>
                            <li>50 Depots</li>
                            <li>500 Contractors</li>
                            <li>500 Vehicles</li>
                            <li>200 Workforces</li>
                            <li>200 Tb/s Storage</li>
                            <li>Tutorial Pack</li>
                            <li>Online & Onsite Support</li>
                        </ul>
                    </div>
                    <div class="c18"> $ 299/ <span class="c23">Month</span> </div>
                    <form action="#" method="POST">
                        <button class="c19" type="submit" name="platinum">Choose</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"> </script> -->