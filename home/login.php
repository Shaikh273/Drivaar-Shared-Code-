<?php
include 'DB/config.php';
include 'base2n.php';
// include_once 'DB/user_activity_log.php'; 
date_default_timezone_set('Europe/London');
if (!isset($_SESSION)) {
    session_start();
}
$mysql = new Mysql();
$mysql->dbConnect();

if (isset($_GET['set'])) {
    unset($_SESSION['load']);
} else if (isset($_POST['loginsub'])) {


    // This checks whether the user already attempted to login before within an certain period of time.
    $time = time() - 60;
    $ip_address = $mysql->getIpAddr();
    $check_login_row = mysqli_fetch_assoc(mysqli_query($mysql->dbConnect(), "select count(*) as total_count from user_attempts where try_time>$time and ip_address='$ip_address'"));
    $total_count = $check_login_row['total_count'];


    // String Validation
    $user = mysqli_real_escape_string($mysql->dbConnect(), $_POST['user']);
    $pass = mysqli_real_escape_string($mysql->dbConnect(), $_POST['pass']);
    $userquery = "SELECT `tbl_user`.*,`tbl_permissioncheck`.`permissioncode` 
    FROM `tbl_user` 
    INNER JOIN `tbl_permissioncheck` ON `tbl_permissioncheck`.`role_id`=`tbl_user`.`roleid` 
    WHERE (`tbl_user`.`email`='$user' OR `tbl_user`.`contact`='$user') 
    AND `tbl_user`.`password`='$pass' AND `tbl_user`.`isdelete`=0 AND `tbl_permissioncheck`.`isdelete`=0";
    $userrow = $mysql->selectFreeRun($userquery);
    if ($row = mysqli_fetch_assoc($userrow)) {

        $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($row['urldate'])) / 3600, 1);
        if ($row['isactive'] == 0) {
            if ($hourdiff <= 24) {
                $binary = new Base2n(1);
                $binarycode =  $row['permissioncode'];
                if (isset($binarycode)) {
                    $prcode = $binary->encode($binarycode);
                    $permissioncode = str_split($prcode);
                    $_SESSION['permissioncode'] = $permissioncode;
                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['profile'] = $row['file'];
                    $_SESSION['load'] = 1;
                    $_SESSION['adt'] = 1;
                    header("Location: index.php");


                    // Will clear IP address from the db
                    $_SESSION['IS_LOGIN'] = 'yes';
                    mysqli_query($mysql->dbConnect(), "delete from user_attempts where id='userid' && ip_address='$ip_address'");
                } else {
                    $msg = "Permission can not give, Please verify your permission.";
                }
            } else if ($row['ispasswordchange'] == 1) {
                $binary = new Base2n(1);
                $binarycode =  $row['permissioncode'];
                if (isset($binarycode)) {
                    $prcode = $binary->encode($binarycode);
                    $permissioncode = str_split($prcode);
                    $_SESSION['permissioncode'] = $permissioncode;
                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['profile'] = $row['file'];
                    $_SESSION['load'] = 1;
                    $_SESSION['adt'] = 1;
                    header("Location: index.php");


                    // Clear IP Address from database
                    $_SESSION['IS_LOGIN'] = 'yes';
                    mysqli_query($mysql->dbConnect(), "delete from user_attempts where ip_address='$ip_address'");
                } else {
                    $msg = "Permission can not give, Please verify your permission.";
                }
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
            $msg = "To many failed login attempts. Please login after 60 sec";
        }


        // if the user is not able to login then this else condition will work
        else {
            $total_count++;
            $rem_attm = 3 - $total_count;
            $rem_attm1 = 5 - $total_count;
            if ($rem_attm == 0) {
                $msg = "To many failed login attempts. Please login after 60 sec";
            } else {
                $try_time = time();
                $con = "insert into user_attempts(ip_address,try_time) value('$ip_address','$try_time')";
                $connection = $mysql->selectFreeRun($con);
                $msg = "Wrong Credentials!!! UserId and/or Password entered by you is incorrect.<br/>$rem_attm attempts remaining";
            }
        }
    }
}


// // This wil get the IP address of the user.
// function getIpAddr()
// {
//    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
//       $ipAddr = $_SERVER['HTTP_CLIENT-IP'];
//    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
//       $ipAddr = $_SERVER['HTTP_X_FORWARDED_FOR'];
//    } else {
//       $ipAddr = $_SERVER['REMOTE_ADDR'];
//    }
//    return $ipAddr;
// }


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
                <div class="cardbody">
                    <form class="form-horizontal form-material" id="loginform" action="#" method="POST">
                        <h3 class="text-center m-b-20">Log-in</h3>
                        <?php if (@$msg) { ?>
                            <div id="alert" class="alert alert-danger" role="alert">
                                <span class="sr-only"></span>
                                <?= $msg ?>
                            </div>
                        <?php    } ?>
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
                                    <p> You can try Again after <span id="countdowntimer" style="color:red ; font-size: 15px; font-weight: 300;">60</span> Seconds</p>
                                </div>
                                <script type="text/javascript">
                                    var timeleft = 60;
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
    <!--Bootstrap tether Core JavaScript -->
    <script src="../assets/node_modules/popper/popper.min.js"></script>
    <script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="dist/js/validation.js"></script>
    <script src="../assets/node_modules/sweetalert/sweetalert.min.js"></script>
    <script src="../assets/node_modules/sweetalert/jquery.sweet-alert.custom.js"></script>
    <?php
    include('footerScript.php');
    ?>
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