<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');

$auth_key=$_POST['auth_key'];
$clockin_status=$_POST['action'];
$clockin_Id=$_POST['id'];
$inspection_date=$_POST['end'];
$description=$_POST['description'];
$status=0;
if(isset($auth_key) && isset($clockin_status) && $clockin_status=='clockout' && isset($inspection_date) && isset($clockin_Id))
{

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {    
        if($row['isactive']==0)
        {
            $driverquery = $mysql -> selectFreeRun("SELECT a.*,v.`registration_number`,v.`vin_number`,v.`id` as vehicle_id 
                            FROM `tbl_vehiclerental_agreement` a  
                            INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
                            WHERE a.`driver_id`=".$row['id']." AND CURRENT_DATE() BETWEEN a.`pickup_date` and a.`return_date` AND a.`isdelete`=0");
            $driverresult = mysqli_fetch_array($driverquery);
            if($driverresult>0)
            {
                $insquery = $mysql -> selectFreeRun("SELECT * FROM `tbl_vehicleinspection` WHERE `vehicle_id`=".$driverresult['vehicle_id']." AND `odometer` IS NOT NULL AND `isdelete`=0 AND CAST(`insert_date` as date) = CAST(CURRENT_DATE() as date)");
                $insresult = mysqli_fetch_array($insquery);
                if($insresult>0)
                {
                   // clockin & clockout
                    $valus[0]['end'] = $inspection_date;
                    $valus[0]['description'] = $description;
    				$valus[0]['update_date'] = date('Y-m-d H:i:s');

			        $makecol = array('end','description','update_date');
                    $where = 'id ='. $clockin_Id;
                    $update = $mysql -> update('tbl_contractorattendance',$valus,$makecol,'update',$where);

                	$inspection_status1 = [
                		"clockin_status"=> 1,
                        "clockin" => $inspection_date,
                        "message"=> 'Clock In successful.',
                    ];

                    $status=1;
                }
                else
                {

                    $status=0; 
                    $error = "Driver has not been Inspected.";
                    $errorcode = "103";  
                }
            }
            else
            {

            	$status=0; 
            	$error = "Driver has no fleet assigned.";
				$errorcode = "103";

            	// $inspection_status1 = [
            	// 	"clockin_status"=> 0,
             //        "clockin" => '',
             //        "message"=> 'Clock In Error,something went wrong.',
             //    ];
                
                
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
        $array = array("clockin_status" => $inspection_status1);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,'data' => $array));
}
?>