<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

$auth_key=$_POST['auth_key'];
$action=$_POST['action'];
$start_date=$_POST['start_date'];
$end_date=$_POST['end_date'];
$reason=$_POST['reason'];
$status=0;
if(isset($auth_key) && isset($action) && isset($start_date)  && isset($end_date)  && isset($reason) && $action=='leave_request')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
        if($row['isactive']==0)
        {
            //insert leave
            $valus[0]['cid'] = $row['id'];
            $valus[0]['start_date'] = $start_date;
            $valus[0]['end_date'] = $end_date;
            $valus[0]['notes'] = $reason;
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $insert = $mysql->insert('tbl_leaverequest', $valus);
            if($insert)
            {
                $status=1;
                $success = "Leave request has been inserted successfully";
            }
            else
            {
                $status=0;
                $error = "Leave request has not been inserted successfully.";
                $errorcode = "108";
            }
              
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
        $array = array("success" => $success);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,'data' => $array));
}
else
{  
	$status=0;
    $error = "Authentication filed.";
    $errorcode = "101";
    $array = array("errorcode" =>$errorcode,"error"=>$error);
    echo json_encode(array('status' => $status,'data' => $array));
}
?>