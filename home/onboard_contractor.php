<?php
include 'DB/config.php';
$page_title = "Drivaar";
$page_id = 7;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $uid = '%';
    } else {
        $uid = $userid;
    }
} else {
    header("location: login.php");
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

</head>

<body class="skin-default-dark fixed-layout">

    <?php

    include('loader.php');

    ?>

    <div id="main-wrapper">
        <?php
        include('header.php');
        ?>

        <div class="page-wrapper">

            <div class="container-fluid">

                <main class=" container-fluid  animated mt-4">

                    <div class="container">

                        <div class="row justify-content-center">

                            <div class="col-md-6">

                                <div class="card" style="border: 1px solid #d1d5db;">
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Onboard Contractor</div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="AddFormDiv">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form method="post" id="OnboardEmailForm" name="OnboardEmailForm" action="">
                                                    <input type="hidden" name="id" id="id" value="">
                                                    <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label">Name *</label>
                                                                    <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"> email* </label>
                                                                    <input type="email" id="email" name="email" class="form-control" placeholder="">
                                                                    <small>It will be used for receiving a unique link to regsiter and for login.</small>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="control-label"> Depot </label>
                                                                    <select class="select form-control custom-select" id="depot" name="depot">
                                                                        <option value="0">--</option>
                                                                        <?php
                                                                        $mysql = new Mysql();
                                                                        $mysql->dbConnect();
                                                                        $query = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                                          INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                                          WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
                                                                        $strow =  $mysql->selectFreeRun($query);
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
                                                        </div>
                                                    </div>
                                                    <div class="form-actions">
                                                        <button type="submit" name="insert" class="btn btn-success" id="submit">Send Registration Invite</button>
                                                        <a href="contractor.php" class="btn">Cancel</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </main>

            </div>

        </div>

        <?php

        include('footer.php');

        ?>

    </div>

    <?php

    include('footerScript.php');

    ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#OnboardEmailForm").validate({
                rules: {
                    name: 'required',

                    email: 'required',
                },
                messages: {

                    name: "Please enter your name",

                    email: "Please enter your email",
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "InsertData.php",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#OnboardEmailForm").serialize() + "&action=OnboardEmailForm",
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#OnboardEmailForm')[0].reset();
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
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