<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

$auth_key=$_POST['auth_key'];
$action=$_POST['action'];
$old_password=$_POST['old_password'];
$new_password=$_POST['new_password'];
$status=0;
$todaydate =  date('Y-m-d H:i:s');
if(isset($new_password) && isset($auth_key) && isset($action))
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
        if($row['isactive']==0)
        {
            if($action=='reset_password')
            {
                if(isset($old_password))
                {
                    if($row['password'] == $old_password)
                    {
                        $status=0;
                        $error = "Your Password is incorrect.";
                        $errorcode = "105";
                    }
                }
            }
            //insert password
            $valus[0]['password'] = $new_password;
            $valus[0]['update_date'] = $todaydate ;
            $makecol = array('password','update_date');
            $where = 'id ='. $row['id'];
            $update = $mysql -> update('tbl_contractor',$valus,$makecol,'update',$where);
            if($update)
            {
                $status=1;
                $success = "Password has been updated successfully";
            }
            else
            {
                $status=0;
                $error = "Password has not been updated successfully.";
                $errorcode = "106";
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
    echo 'error';
}
?>