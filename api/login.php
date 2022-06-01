<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

function generateRandomString($length = 15) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}

$id=$_POST['id'];
$password=$_POST['password'];
$todaydate =  date('Y-m-d H:i:s');
$status=0;

if(isset($id) && isset($password))
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `email`='$id' AND `password`='$password' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
            $hourdiff = round((strtotime(date('Y-m-d H:i:s')) - strtotime($row['urldate']))/3600, 1);
            if($row['isactive']==0)
            {
                if($hourdiff<=24 || $row['ispasswordchange']==1)
                {
                    $key = generateRandomString();
                    $valus[0]['key'] = $key;
                    $valus[0]['key_date'] = $todaydate;
                    $makecol = array('key','key_date');
                    $where = 'id ='. $row['id'];
                    $update = $mysql -> update('tbl_contractor',$valus,$makecol,'update',$where);

                    $driverquery = $mysql -> selectFreeRun("SELECT a.*,v.`registration_number`,v.`vin_number`,vs.`name` as suppliername,v.`id` as vehicle_id 
                                    FROM `tbl_vehiclerental_agreement` a  
                                    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
                                    LEFT JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id`
                                    WHERE a.`driver_id`=".$row['id']." AND CURRENT_DATE() BETWEEN a.`pickup_date` and a.`return_date` AND a.`isdelete`=0");
                    $driverresult = mysqli_fetch_array($driverquery);
                    $fleet_status=1;
                    if($driverresult>0)
                    {
                        //rental agreement && expired check
                        //if not  $fleet_status=0
                        if($driverresult<=0)
                        {
                            $fleet_status = 0;
                        }
                        //if $fleet_status=1

                        //onboarding all task
                        $onboard = $mysql -> selectFreeRun("SELECT DISTINCT c.* FROM `tbl_contractor` c WHERE c.`isdelete`=0 AND c.`id`=".$row['id']." AND c.`iscomplated`=1 AND ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)= (SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))");
                        $onboardresult = mysqli_fetch_array($onboard);
                        if($onboardresult<=0)
                        {
                            $fleet_status = 0;
                        }

                        $fleet = [
                            "fleet_status" => $fleet_status,
                            "registration_number" => $driverresult['registration_number'],
                            "vin_number" => $driverresult['vin_number'],
                            "suppliername" => $driverresult['suppliername'],
                        ];
                    }
                    else
                    {
                        $fleet = [
                            "fleet_status" => "0",
                        ];
                        $error = "Driver has no fleet assigned.";
                        $errorcode = "103";
                    }

                    $profilearray = [
                        "name" => $row['name'],
                        "contact" => $row['contact'],
                        "email" => $row['email'],
                        "address" => $row['address'],
                        "postcode" => $row['postcode'],
                        "state" => $row['state'],
                        "city" => $row['city'],
                        "country" => $row['country'],
                        "depot" => $row['depot'],
                        "type" => $row['type'],
                    ];

                    $key_auth = [
                        "auth_key" => $key,
                        "auth_keydate" => $todaydate,
                    ];

                    $status=1;
                }
                else
                {
                    $msg="Your Credentials has been expired.Please contact to authorized person.";
                    $status=0;

                    $error = "Your login time has been expired.";
                    $errorcode = "104";
                } 
            }
            else
            {
                $msg="Your account has been blocked. Please contact to authorized person.";
                $status=0;

                $error = "Driver is not active.";
                $errorcode = "102";
            }
        }
        else
        {
            $error = "Wrong Credentials!!! UserId and/or Password entered by you is incorrect.";
            $errorcode = "100";
        }

    if($status==1)
    {
        $array = array("profile" => $profilearray,"fleet" => $fleet);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,"key" => $key_auth,'data' => $array));
}
?>