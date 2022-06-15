<?php
$page_title = "Permission";
include 'DB/config.php';
if (!isset($_SESSION)) {
    session_start();
}
$userid = $_SESSION['userid'];

if ($userid == 1 || $userid == 4) {
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
    <link href="dist/css/pages/form-icheck.css" rel="stylesheet">
    <style type="text/css">
        .myadmin-dd .dd-list .dd-item .dd-handle {
            border: 1px solid #6a81e4;
        }

        /*.custom-checkbox .custom-control-input:checked~.custom-control-label:before
    {
        background-color: #78affb !important;
    }*/
    </style>
    <!-- rgba(59, 130, 246, 0.5); -->
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

        <div class="page-wrapper">

            <div class="container-fluid">

                <main class="container-fluid  animated mt-4">

                    <div class="card">

                        <div class="">
                            <?php include('setting.php'); ?>

                            <div class="col-md-4">
                                <select class="form-control custom-select" style="border: 1px solid #d1d5db;" id="roletype" value="" onchange="viewpermission()">
                                    <option value="">Select Role</option>
                                    <?php
                                    $mysql = new Mysql();
                                    $mysql->dbConnect();
                                    $rolequery = "SELECT * FROM `tbl_role` WHERE `isactive` = 0 and `isdelete` = 0 AND `id` NOT IN (1)";
                                    $rolerow =  $mysql->selectFreeRun($rolequery);
                                    while ($role_result = mysqli_fetch_array($rolerow)) {
                                    ?>
                                        <option value="<?php echo $role_result['id'] ?>"><?php echo $role_result['role_type'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div><br>

                            <div class="card-header" style="height: 55px;border-bottom: 0.2px solid #00000047 !important;">
                                <div style="float: right;">
                                    <!--  <label class="custom-control custom-checkbox m-b-0">
                                    <input type="checkbox" checked class="custom-control-input hideall" id="all" value="all" onclick="hideall();">
                                    <span class="custom-control-label">
                                        <div class="header">Permission</div>
                                    </span>
                                </label> -->
                                    <button type="submit" name="insert" class="btn btn-success" id="submit" onclick="Save()">Save Permission</button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row viewport">
                                    <div class="col-md-12">
                                        <?php
                                        $mysql = new Mysql();
                                        $mysql->dbConnect();
                                        $query = "SELECT * FROM `tbl_permission` WHERE `super_categoryid`= 0 and `isactive` = 0 and `isdelete` = 0";
                                        $permissionrow =  $mysql->selectFreeRun($query);
                                        while ($pr_result = mysqli_fetch_array($permissionrow)) {
                                        ?>
                                            <div class="card-header" style="height: 38px;border: 0.2px solid #fb9678;background-color: rgb(255 236 230);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label class="custom-control custom-checkbox m-b-0">
                                                        <input type="checkbox" class="custom-control-input subdiv <?php echo $pr_result['id'] ?>-parent" onchange="mainmenu(this)" id="<?php echo $pr_result['id'] ?>">
                                                        <span class="custom-control-label">
                                                            <h6 class="m-0"><?php echo $pr_result['category_name'] ?></h6>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="table-responsive-lg" id="<?php echo $pr_result['id'] ?>-div">
                                                <table class="table w-100 table-sm" small="small">
                                                    <tbody>

                                                        <?php
                                                        $clsp =  $pr_result['id'] . '-child';
                                                        $query_menu = "SELECT * FROM `tbl_permission` WHERE `super_categoryid`= " . $pr_result['id'] . " and `isactive` = 0 and `isdelete` = 0 ";
                                                        $permissionmenurow =  $mysql->selectFreeRun($query_menu);
                                                        while ($prmenu_result = mysqli_fetch_array($permissionmenurow)) {
                                                        ?>
                                                            <tr id="<?php echo $prmenu_result['id'] ?>">
                                                                <td class="whitespace-no-wrap border-right w-1/3 p-3">
                                                                    <label class="custom-control custom-checkbox m-b-0">
                                                                        <input type="checkbox" class="custom-control-input subdiv <?php echo $clsp ?> <?php echo $prmenu_result['id'] ?>-parent" id="<?php echo $prmenu_result['id'] ?>" onchange="mainmenu(this)" data-id="<?php echo $pr_result['id'] ?>">
                                                                        <span class="custom-control-label"><strong><?php echo $prmenu_result['category_name'] ?></strong>
                                                                    </label>
                                                                </td>
                                                                <td class="p-3">
                                                                    <?php
                                                                    $cls =  $clsp . " " . $prmenu_result['id'] . '-child';
                                                                    $query_module = "SELECT * FROM `tbl_permission` WHERE `super_categoryid`=" . $prmenu_result['id'] . " and `isactive` = 0 and `isdelete` = 0";
                                                                    $permissionmodulerow =  $mysql->selectFreeRun($query_module);
                                                                    while ($prmodule_result = mysqli_fetch_array($permissionmodulerow)) {
                                                                    ?>
                                                                        <div class="d-flex">
                                                                            <label class="custom-control custom-checkbox m-b-0">

                                                                                <input type="checkbox" class="custom-control-input subdiv <?php echo $cls ?> <?php echo $prmodule_result['id'] ?>-parent" id="<?php echo $prmodule_result['id'] ?>" onchange="mainmenu(this)" data-id="<?php echo $prmenu_result['id'] ?>">
                                                                                <span class="custom-control-label"><?php echo $prmodule_result['category_name'] ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </td>
                                                            </tr>
                                                        <?php
                                                            //  $mysql -> dbDisConnect();
                                                            $cls = "";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <?php include('footer.php'); ?>
        </div>

        <?php

        include('footerScript.php');

        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.viewport').hide();
            });
            <?php include('permission.js'); ?>
        </script>
        <script src="../assets/node_modules/icheck/icheck.min.js"></script>
        <script src="../assets/node_modules/icheck/icheck.init.js"></script>
</body>

</html>