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
    header("Location: ../loginpage/login.php");
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
</body>
</html>