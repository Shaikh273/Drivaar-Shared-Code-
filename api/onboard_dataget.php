<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');
$onboard_data=$_POST['action'];
$email=$_POST['email'];
$status=0;
if(isset($onboard_data) && $onboard_data=='onboard_data' && isset($email))
{

    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $query = "SELECT * FROM `tbl_contractor` WHERE `email` LIKE '".$_POST['email']."' AND `isdelete`= 0";
    $rows = $mysql->selectFreeRun($query);
    $fetch_rows = mysqli_num_rows($rows);
    $res = mysqli_fetch_array($rows);

    $depot =  $mysql -> selectWhere('tbl_depot','id','=',$res['depot'],'int');
    $dptresult = mysqli_fetch_array($depot);

    if($fetch_rows>0)
    {
        $status=0;
        $id = base64_encode($res['id']);
        $cnt_reg = [
                "Onboard_status"=> 1,
                "id"=>$id,
                "email" => $res['email'],
                "name" => $res['name'],
                "depot" => $res['depot'],
                "depot_type" => $dptresult['name'],
                "message"=> 'Data has been get successfull.',
        ];
    }
    else
    {
        $cnt_reg = [
            "Onboard_status"=> 0,
            "Data" => 'Error',
            "message"=> 'Your Email Id does not match..!',
        ];
    }
    $mysql -> dbDisConnect();   
}
else
{
    $status=0;
    $cnt_reg = [
        "error"=> 'Authentication filed',
        "errorcode" => 101,
    ];
}

if($status==1)
{
    $array = array("Onboarddata_status" => $cnt_reg);
}
else
{
    $array = array("Onboarddata_status" =>$cnt_reg);
}
echo json_encode(array('status' => $status,'data' => $array));
?>