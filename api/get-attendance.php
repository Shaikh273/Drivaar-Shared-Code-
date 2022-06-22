<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

$date = $_POST['date'];
$auth_key = $_POST['auth_key'];
$clockin_status = $_POST['action'];
$clockin_Id = $_POST['id'];
$clockout_Id = $_POST['id'];
$inspection_date = $_POST['end'];
$description = $_POST['description'];
$status = 0;
if (isset($auth_key) && isset($clockin_status) && $clockin_status == 'clockout' && isset($inspection_date) && isset($clockin_Id) && isset($clockout_Id)) {

    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`= 0";
    $userrow = $mysql->selectFreeRun($sql);
    if ($row = mysqli_fetch_array($userrow)) {
        $Data = "SELECT * FROM tbl_contractorattendance WHERE `date` = '$date'";
        $result = $mysql->selectFreeRun($Data);
        if ($row = mysqli_num_rows($result)) {
            echo json_encode(array(
                "status" => true,
                "data" => $result
            ));
        } else {
            echo json_encode(array(
                "status" => false,
                "msg" => "Data not Found"
            ));
        }
    } else {

        echo "Data not Found";
    
    }
}
?>