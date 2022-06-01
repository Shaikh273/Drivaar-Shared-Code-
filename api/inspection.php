<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

$auth_key=$_POST['auth_key'];
$inspection_status=$_POST['action'];
$answer=$_POST['answer'];
$status=0;
$todaydate =  date('Y-m-d H:i:s');
if(isset($answer) && isset($auth_key) && isset($inspection_status) && $inspection_status=='inspection_answer')
{
	//$inspection_status = array();
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
        if($row['isactive']==0)
        {

            $ajson = var_dump(json_decode($answer));
            echo $ajson['answer'][0];

            
            // $insquery = $mysql -> selectFreeRun("SELECT * FROM `tbl_vehiclechecklist` WHERE `isdelete`=0 AND `isactive`=0 ORDER BY id ASC");
            // $inspection_status[] = array(
            //         "id" => '0',
            //         "question" =>'Odometer',
            //         "type"=>'text'
            // );

            // while($insresult = mysqli_fetch_array($insquery))
            // {
            // 	$inspection_status[] = array(
            //         "id" => $insresult['id'],
            //         "question" => $insresult['name'],
            //         "type"=>'mcq'
            //     );
            // }
            // $status=1;
        }
        else
        {
            $status=0;
            $error = "Driver is not active.";
            $errorcode = "102";
        }
    }
    else
    {
        $status=0;
        $error = "Authentication filed.";
        $errorcode = "101";
    }

    if($status==1)
    {
        $array = array("inspection_status" => $inspection_status);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,'data' => $array));
}
else
{
    echo 'error';
}
?>