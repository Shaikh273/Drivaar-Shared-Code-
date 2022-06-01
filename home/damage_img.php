<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();

$valus[0]['userid'] = $_POST['userid'];
$valus[0]['vehicle_id'] = $_POST['vehicleid'];
$valus[0]['description'] = $_POST['description'];
$valus[0]['damage_part'] = $_POST['damage_part'];
$valus[0]['damage_type'] = $_POST['damage_type'];
$valus[0]['image'] = $_POST['image_name'];
$valus[0]['condition'] = $_POST['conditionid'];
$valus[0]['insert_date'] = date('Y-m-d H:i:s');
$statusinsert =  $mysql -> insertre('tbl_vehicledamage_img',$valus);
    if($statusinsert)
    {
        $status=1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    $query = "SELECT damage_part,damage_type FROM `tbl_vehicledamage_img` WHERE id =".$statusinsert;
    $row =  $mysql -> selectFreeRun($query); 
    $result = mysqli_fetch_array($row);
    $damage_part = $result['damage_part'];
    $damage_type = $result['damage_type'];
    $mysql -> dbDisConnect();


$response['status'] = $status;
$response['title'] = $title;
$response['message'] = $message;
$response['name'] = $name;
$response['damage_part'] = $damage_part;
$response['damage_type'] = $damage_type;


echo json_encode($response);
