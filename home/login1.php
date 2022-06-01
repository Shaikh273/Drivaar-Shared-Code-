<?php
include 'DB/config.php';
include 'base2n.php';

if (!isset($_SESSION)) {
    session_start();
}
if (isset($_GET['set'])) {
    unset($_SESSION['load']);
} else if (isset($_POST['loginsub'])) {

    $mysql = new Mysql();
    $mysql->dbConnect();
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    $userquery = "SELECT `tbl_user`.*,`tbl_permissioncheck`.`permissioncode` 
    FROM `tbl_user` 
    INNER JOIN `tbl_permissioncheck` ON `tbl_permissioncheck`.`role_id`=`tbl_user`.`roleid` 
    WHERE (`tbl_user`.`email`='$user' OR `tbl_user`.`contact`='$user') 
    AND `tbl_user`.`password`='$pass' AND `tbl_user`.`isdelete`=0 AND `tbl_permissioncheck`.`isdelete`=0";
    $userrow = $mysql->selectFreeRun($userquery);
    if ($row = mysqli_fetch_array($userrow)) {
        if ($row['isactive'] == 0) {
            $binary = new Base2n(1);
            $binarycode =  $row['permissioncode'];
            if (isset($binarycode)) {
                $prcode = $binary->encode($binarycode);
                $permissioncode = str_split($prcode);
                $_SESSION['permissioncode'] = $permissioncode;
                $_SESSION['userid'] = $row['id'];
                $_SESSION['load'] = 1;
                $_SESSION['adt'] = 1;
                header("Location: index.php");
            } else {
                $msg = "Permission can not give, Please verify your permission.";
            }
        } else {
            $msg = "Your account has been blocked. Please contact to authorized person.";
        }
    } else {
        $msg = "Wrong Credentials!!! UserId and/or Password entered by you is incorrect.";
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
                            <div class="alert alert-danger" role="alert">
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
                                    </div>
                                    <div class="ml-auto">
                                        <a href="javascript:void(0)" id="to-recover" class="text-muted"><i class="fas fa-lock m-r-5"></i> Forgot password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
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