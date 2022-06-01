<?php
include 'home/DB/config.php';
if (isset($_GET['cid'])) {
    $cid = base64_decode($_GET['cid']);
    $mysql = new Mysql();
    $mysql->dbConnect();
    $contractor =  $mysql->selectWhere('tbl_contractor', 'id', '=', $cid, 'int');
    $contractorresult = mysqli_fetch_array($contractor);
    $insertdate = $contractorresult['onboardutldate'];
    $date = date('Y-m-d h:i:s');
    $mysql->dbDisConnect();
    if ((strtotime($date) - strtotime($insertdate)) <= 86400) {
        header("Location: /home/contractorEmailRegistration/?cid=" . $_GET['cid']);
        //time baki h
    } else {
        header("Location: /home/404page.php");
    }
}
