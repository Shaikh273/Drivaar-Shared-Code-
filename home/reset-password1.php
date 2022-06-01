
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
                    <form class="form-horizontal form-material" id="resetform" action="" method="post">
                        <h3 class="box-title m-b-20">Recover Password</h3>
                         <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" id="password" type="password" required="" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="current_password" type="password" required="" placeholder="Confirm Password">
                            </div>
                        </div>
                        <div class="form-group text-center p-b-10">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" name="submit" type="submit">Reset</button>
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
    <script>
        $(document).ready(function(){
            $(function() {
                $(".preloader").fadeOut();
            });
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });
            var key = location.search.split('key=')[1] ? location.search.split('key=')[1] : '';
            var token = location.search.split('token=')[1] ? location.search.split('token=')[1] : '';
            // console.log(token);
            $("#resetform").validate({

            rules: {
                password: 'required',
                current_password: {
                    required: true,
                    equalTo: "#password"
                }

            },

            messages: {
                password: "Please enter your password",
                current_password: "Please match your current password with password",
            },
            submitHandler: function(form) {
                
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#resetform").serialize()+"&action=resetform&key="+key+"&token="+token,
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            //myAlert(data.title + "@#@" + data.message + "@#@success");
                            window.location.href = 'login.php';
                            
                        }
                        else
                        {
                            //myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
               // return false;
            }
        });
        });
    </script>
</body>

</html>