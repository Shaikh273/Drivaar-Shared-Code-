<?php
include "config.php";
include 'DB/config.php';
date_default_timezone_set('Europe/London');
if (!isset($_SESSION)) {
    session_start();
}
$mysql = new Mysql();
$mysql->dbConnect();
if (isset($_GET['set'])) {
    unset($_SESSION['load']);
}
// if (isset($_SESSION['load'])) {
//     header("location: index.php");
//}
else {
    if (isset($_POST['loginsub'])) {



        // This checks whether the user already attempted to login before within an certain period of time.
        $time = time() - 30;
        $ip_address = $mysql->getIpAddr();
        $check_login_row = mysqli_fetch_assoc(mysqli_query($connection, "select count(*) as total_count from user_attempts where try_time>$time and ip_address='$ip_address'"));
        $total_count = $check_login_row['total_count'];


        // String Validation
        $user = mysqli_real_escape_string($connection, $_POST['user']);
        $pass = mysqli_real_escape_string($connection, $_POST['pass']);
        $sql = "SELECT * FROM `tbl_contractor` WHERE `email`='$user' AND `password`='$pass' AND `isdelete`=0";
        $fire = mysqli_query($connection, $sql);
        if ($row = mysqli_fetch_array($fire)) {
            $todate = date('Y-m-d');
            $drqry = "SELECT DISTINCT v.`id` as vid,v.`vehicle_id` FROM `tbl_vehiclerental_agreement` v 
                INNER JOIN `tbl_contractor` c ON c.`id`=v.`driver_id`
                WHERE c.`id`=" . $row['id'] . " AND ('" . $todate . "' BETWEEN v.`pickup_date` AND v.`return_date`)";
            $drsrow = mysqli_query($connection, $drqry);
            $drresult = mysqli_fetch_array($drsrow);
            $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($row['urldate'])) / 3600, 1);
            if ($row['isactive'] == 0) {
                if ($row['ispasswordchange'] == 1) {
                    $_SESSION['cid'] = $row['id'];
                    $_SESSION['load'] = 1;
                    $_SESSION['adt'] = 1;
                    $_SESSION['cfname'] = $row['name'];
                    $_SESSION['profile'] = $row['file'];
                    $_SESSION['vid'] = $drresult['vid'];
                    $_SESSION['vehicle_id'] = $drresult['vehicle_id'];
                    header("location: index.php");


                    // Will clear IP address from the db
                    $_SESSION['IS_LOGIN'] = 'yes';
                    mysqli_query($connection, "delete from user_attempts where ip_address='$ip_address'");
                } else if ($hourdiff <= 24) {
                    $_SESSION['cid'] = $row['id'];
                    $_SESSION['load'] = 1;
                    $_SESSION['adt'] = 1;
                    $_SESSION['cfname'] = $row['name'];
                    $_SESSION['profile'] = $row['file'];
                    $_SESSION['vid'] = $drresult['vid'];
                    $_SESSION['vehicle_id'] = $drresult['vehicle_id'];
                    header("location: index.php");



                    // Will clear IP address from the db
                    $_SESSION['IS_LOGIN'] = 'yes';
                    mysqli_query($connection, "delete from user_attempts where ip_address='$ip_address'");
                } else {
                    $msg = "Your Credentials has been expired.Please contact to authorized person.";
                }
            } else {
                $msg = "Your account has been blocked. Please contact to authorized person.";
            }
        }

        // login attempts counter
        else {
            if ($total_count == 3) {
                $msg = "To many failed login attempts. Please login after 10 sec";
            }


            // if the user is not able to login then this else condition will work
            else {
                $total_count++;
                $rem_attm = 3 - $total_count;
                $rem_attm1 = 5 - $total_count;
                if ($rem_attm == 0) {
                    $msg = "To many failed login attempts. Please login after 10 sec";
                } else {
                    $try_time = time();
                    $con = "insert into user_attempts(ip_address,try_time) value('$ip_address','$try_time')";
                    $conn = $mysql->selectFreeRun($con);
                    $msg = "Wrong Credentials!!! UserId and/or Password entered by you is incorrect.<br/>$rem_attm attempts remaining";
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <link href="../assets/node_modules/sweetalert/sweetalert.css" rel="stylesheet" type="text/css">
    <title>Login</title>
    <link href="dist/css/pages/login-register-lock.css" rel="stylesheet">
    <link href="dist/css/style.min.css" rel="stylesheet">
</head>

<body class="skin-default card-no-border">
    <?php
    include('loader.php');
    ?>
    <section id="wrapper">
        <div class="login-register" style="background-image:url(../assets/images/background/login-register.jpg);">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" id="loginform" action="#" method="POST">
                        <h3 class="text-center m-b-20">Log-in</h3>
                        <?php if (@$msg) { ?>
                            <div id="alert" class="alert alert-danger" role="alert">
                                <span class="sr-only"></span>
                                <?= $msg ?>
                            </div>
                        <?php     } ?>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required="" placeholder="Username" name="user">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="Password" name="pass">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="d-flex no-block align-items-center">
                                    <div class="custom-control custom-checkbox">
                                        <!--<input type="checkbox" class="custom-control-input" id="customCheck1">-->
                                        <!--<label class="custom-control-label" for="customCheck1">Remember me</label>-->
                                    </div>
                                    <div class="ml-auto">
                                        <a href="javascript:void(0)" id="to-recover" class="text-muted"><i class="fas fa-lock m-r-5"></i> Forgot password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">


                            <!-- This will start a timer at failed login attempt. -->
                            <?php
                            if (@$total_count == 3) {
                            ?><div id="timer">
                                    <p> You can try Again after <span id="countdowntimer">10</span> Seconds</p>
                                </div>
                                <script type="text/javascript">
                                    var timeleft = 10;
                                    var downloadTimer = setInterval(function() {
                                        timeleft--;
                                        document.getElementById("countdowntimer").textContent = timeleft;
                                        let btn = document.querySelector("#timer");
                                        let login = document.querySelector(".btn-info");
                                        let alert = document.querySelector("#alert");
                                        if (timeleft > 0) {
                                            login.style.display = "none";
                                            login.disabled = true;
                                        }
                                        if (timeleft == 0) {
                                            console.log(alert);
                                            clearInterval(downloadTimer);
                                            btn.style.display = "none";
                                            login.style.display = "block";
                                            login.disabled = false;
                                            alert.style.display = "none";
                                        }
                                    }, 1000);
                                </script><?php } ?>


                            <div class="col-xs-12 p-b-20">
                                <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" name="loginsub">Log In</button>
                            </div>
                        </div>
                    </form>
                    <form class="form-horizontal" id="recoverform" action="" method="post">
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <h3>Recover Password</h3>
                                <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" id="name" name="name" required="" placeholder="Name">
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="email" id="email" name="email" required="" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit" id="recoverbtn" name="recoverbtn">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/validation.js"></script>
    <script src="../assets/node_modules/sweetalert/sweetalert.min.js"></script>
    <script src="../assets/node_modules/sweetalert/jquery.sweet-alert.custom.js"></script>
    <!--Custom JavaScript -->
    <script type="text/javascript">
        $(function() {
            $(".preloader").fadeOut();
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('#to-recover').on("click", function() {
            $("#loginform").slideUp();
            $("#recoverform").fadeIn();
        });
        $('#recoverbtn').on("click", function() {
            $("#recoverform").validate({
                rules: {
                    email: 'required',
                    name: 'required',
                },
                messages: {
                    email: "Please enter your email",
                    name: "Please enter your name",
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "InsertData.php",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#recoverform").serialize() + "&action=recoverform",
                        cache: false,
                        processData: false,
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $("#loginform").fadeIn();
                                $("#recoverform").slideUp();
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });

                }
            });
        });
    </script>

</body>

</html>