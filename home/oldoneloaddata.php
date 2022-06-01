<?php
include 'DB/config.php';
include 'base2n.php';
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}

$userid = $_SESSION['userid']; 

function GetAttendanceTime($starttime,$endtime)
{
  $time1 = $starttime;
  $time2 = $endtime;
  $time1 = explode(':',$time1);
  $time2 = explode(':',$time2);
  $hours1 = $time1[0];
  $hours2 = $time2[0];
  $mins1 = $time1[1];
  $mins2 = $time2[1];
  $hours = $hours2 - $hours1;
  $mins = 0;
  if($hours < 0)
  {
    $hours = 24 + $hours;
  }
  if($mins2 >= $mins1) {
        $mins = $mins2 - $mins1;
    }
    else {
      $mins = ($mins2 + 60) - $mins1;
      $hours--;
    }
    if($mins < 9)
    {
      $mins = str_pad($mins, 2, '0', STR_PAD_LEFT);
    }
    if($hours < 9)
    {
      $hours =str_pad($hours, 2, '0', STR_PAD_LEFT);
    }
    return  $hours.':'.$mins;
}

function weekOfYear($date1) {
    $date = strtotime($date1);
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        $weekOfYear = 0;    
    }
    return $weekOfYear;
}
function getStartAndEndDate($week, $year) 
{
    date_default_timezone_set('Europe/London');
    $dto = new DateTime();
    $dto->setISODate($year, $week,0);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}
if(isset($_POST['action']) && $_POST['action'] == 'permission')
{

    header("content-Type: application/json");
    $status=0;
    $binary = new Base2n(1);
    $decoded = $binary->decode($_POST['binarycode']);
    $valus[0]['permissioncode'] = $decoded;
    $roleid = $_POST['role'];
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($valus[0]['permissioncode'] && $roleid > 0)
    {
        $permission = "SELECT * FROM `tbl_permissioncheck` WHERE `role_id` = ".$roleid ." and  `isactive` = 0 and `isdelete` = 0";
        $permissionrow =  $mysql -> selectFreeRun($permission);       
        if($pr_result = mysqli_fetch_array($permissionrow))
        {
             $valus[0]['update_date'] = date('Y-m-d H:i:s');
             $prid =  $pr_result['id'];
             $prcol = array('permissioncode','update_date');
             $where = 'id ='.$prid;
             $permissioninsert = $mysql -> update('tbl_permissioncheck',$valus,$prcol,'update',$where);
        }
        else
        {
            $valus[0]['role_id']  = $_POST['role'];
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $permissioninsert = $mysql -> insert('tbl_permissioncheck',$valus);
        }
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'viewpermission')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $roleid = $_POST['role'];

    if($roleid)
    {
        $binary = new Base2n(1);
        $permission = "SELECT * FROM `tbl_permissioncheck` WHERE `role_id` = ".$roleid ." and  `isactive` = 0 and `isdelete` = 0";
        $permissionrow =  $mysql -> selectFreeRun($permission);
        $pr_result = mysqli_fetch_array($permissionrow);
        $binarycode =  $pr_result['permissioncode'];
        if($binarycode)
        {
             $permissioncode = $binary->encode($binarycode);
            $status=1;
        }
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['permissioncode'] = $permissioncode;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'UserUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> selectWhere('tbl_user','id','=',$id,'int');

    $userresult = mysqli_fetch_array($user);

    if($userresult > 0) {

        $status = 1;

        $userdata['roleid'] = $userresult['roleid'];

        $userdata['name'] = $userresult['name'];

        $userdata['contact'] = $userresult['contact'];

        $userdata['email'] = $userresult['email'];

        $userdata['address'] = $userresult['address'];

        $userdata['password'] = $userresult['password'];

        $userdata['confirm_password'] = $userresult['confirm_password'];

    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['userdata'] = $userdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'UserDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_user',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($user)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Userisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_user',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
// else if(isset($_POST['action']) && $_POST['action'] == 'checkusercontact')
// {
//   header("content-Type: application/json");
//   $status=0;
//   $mysql = new Mysql();
//   $mysql -> dbConnect();
//   $contact = $_POST['phone'];
//   $query = "SELECT * FROM `tbl_user` WHERE `contact` = '$contact' and `isdelete`= 0";
//   $rows = $mysql -> selectFreeRun($query);
//   $fetch_rows = mysqli_num_rows($rows);
//   if ($fetch_rows > 0)
//   {
//         $status=1;
//   }
//   else
//   {
//         $status=0;
//   }
//    $response['status'] = $status;
//    echo json_encode($response); 
// }
else if(isset($_POST['action']) && $_POST['action'] == 'DepotUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> selectWhere('tbl_depot','id','=',$id,'int');

    $userresult = mysqli_fetch_array($user);

    if($userresult > 0) {

        $status = 1;

        $userdata['name'] = $userresult['name'];

        $userdata['supervisor'] = $userresult['supervisor'];

        $userdata['reference'] = $userresult['reference'];

        $userdata['isactive'] = $userresult['isactive'];

    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['userdata'] = $userdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'DepotDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_depot',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($user)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Depotisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_depot',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'PaymenttypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $payment =  $mysql -> selectWhere('tbl_paymenttype','id','=',$id,'int');

    $userresult = mysqli_fetch_array($payment);

    if($userresult > 0) {

        $status = 1;

        $paymentdata['name'] = $userresult['name'];

        $paymentdata['vat'] = $userresult['vat'];

        $paymentdata['amount'] = $userresult['amount'];
        
        $paymentdata['profitprice'] = $userresult['profitprice'];

        $paymentdata['account'] = $userresult['account_id'];

        $paymentdata['type'] = $userresult['type'];

        $paymentdata['paymentperstop'] = $userresult['paymentperstop'];
        
        $paymentdata['granular'] = $userresult['granular'];
        
        $paymentdata['applies'] = $userresult['applies'];
        
        $paymentdata['routerate'] = $userresult['routerate'];
        
        $paymentdata['depot'] = $userresult['depot_id'];
        
        $paymentdata['isactive'] = $userresult['isactive'];
        
        

    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['paymentdata'] = $paymentdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'PaymenttypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_paymenttype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $status = 1;
    }

    $response['status']= $status;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Paymenttypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_paymenttype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'check_vehicle_registration_number')
{
  header("content-Type: application/json");
  $status=0;
  $mysql = new Mysql();
  $mysql -> dbConnect();
  $reg_number = $_POST['reg_number'];
  $query = "SELECT * FROM `tbl_vehicles` WHERE `registration_number` = '$reg_number' and `isdelete`= 0";
  $rows = $mysql -> selectFreeRun($query);
  $fetch_rows = mysqli_num_rows($rows);
  if ($fetch_rows > 0)
  {
        $status=1;
  }
  else
  {
        $status=0;
  }
   $response['status'] = $status;
   echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleStatusUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclestatus','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        
        $statusdata['colorcode'] = $statusresult['colorcode'];
        
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleStatusDeleteData')
{ 

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $statuscol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclestatus',$valus,$statuscol,'delete',$where);
   

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
         $status = 1;
    }

    $response['status']= $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclestatusisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclestatus',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleMakeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclemake','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        
        $statusdata['description'] = $statusresult['description'];
        
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleMakeDeleteData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclemake',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclemakeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclemake',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleModelUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclemodel','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        
        $statusdata['description'] = $statusresult['description'];
        
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleModelDeleteData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclemodel',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclemodelisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclemodel',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleGroupUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $grouprow =  $mysql -> selectWhere('tbl_vehiclegroup','id','=',$id,'int');

    $groupresult = mysqli_fetch_array($grouprow);

    if($groupresult > 0) {

        $status = 1;

        $groupdata['name'] = $groupresult['name'];
        
        $groupdata['rentper_day'] = $groupresult['rentper_day'];

        $groupdata['insuranceper_day'] = $groupresult['insuranceper_day'];
        
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['groupdata'] = $groupdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleGroupDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $groupdata =  $mysql -> update('tbl_vehiclegroup',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($groupdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclegroupisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclegroup',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSupplierUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclesupplier','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSupplierDeleteData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclesupplier',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclesupplierisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclesupplier',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleTypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicletype','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleTypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicletype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicletypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicletype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleowner','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleowner',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleownerisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleowner',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExtraUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleextra','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExtraDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleextra',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleextraisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleextra',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleRentalInfoDetails')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $rentquery = "SELECT * FROM `tbl_vehiclerentagreement` WHERE `vehicle_id`=".$_POST['id']." AND `isdelete`= 0 AND `isactive`= 0";
    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $statusresult = mysqli_fetch_array($rentrow);
    if($statusresult)
    {
        $status=1;
        $rentdata['rentid'] = $statusresult['id'];
        $rentdata['startdate'] = $statusresult['startdate'];
        $rentdata['enddate'] = $statusresult['enddate'];
        $rentdata['rentpriceperday'] = $statusresult['rentpriceperday'];
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['rentdata'] = $rentdata;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAddOwnerData')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['vehicle_id']=$_POST['id'];
    $valus[0]['vehicle_ownerid'] = $_POST['ownerId'];
        $query = "SELECT * FROM `tbl_vehicleowner` WHERE `id`=".$_POST['ownerId'];
        $userrow =  $mysql -> selectFreeRun($query);
        $userresult = mysqli_fetch_array($userrow);
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $valus[0]['vehicle_ownername'] = $userresult['name'];

    
    $statusinsert = $mysql -> insert('tbl_addvehiclesowner',$valus);
    if($statusinsert)
    {
        $status=1;
    }
    else
    {
        $status=0;
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT v.*,vs.`name` as statusname,vs.`colorcode`,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,vfi.`id` as insurance,vi.`goods_in_transit`,vi.`public_liability` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
            LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id` 
            LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance`
            WHERE v.`id`=".$id; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['registration_number'] = $statusresult['registration_number'];
        $statusdata['vin_number'] = $statusresult['vin_number'];
        $statusdata['supplier_id'] = $statusresult['supplier_id'];
        $statusdata['owner_id'] = $statusresult['owner_id'];
        $statusdata['group_id'] = $statusresult['group_id'];
        $statusdata['depot_id'] = $statusresult['depot_id'];
        $statusdata['make_id'] = $statusresult['make_id'];
        $statusdata['model_id'] = $statusresult['model_id'];
        $statusdata['type_id'] = $statusresult['type_id'];
        $statusdata['color'] = $statusresult['color'];
        $statusdata['fuel_type'] = $statusresult['fuel_type'];
        $statusdata['insurance'] = $statusresult['insurance'];
        $statusdata['goods_in_transit'] = $statusresult['goods_in_transit'];
        $statusdata['public_liability'] = $statusresult['public_liability'];
        $statusdata['options'] = $statusresult['options'];
        $statusdata['rental_condition'] = $statusresult['rental_condition'];
        $statusdata['status'] = $statusresult['status'];     
    }
    else
    {
        $status = 0;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicles',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicles',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleStatusData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusquery = "SELECT * FROM `tbl_vehiclestatus` WHERE `isdelete`= 0 AND `isactive`= 0";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        $options = array();
        while($statusresult = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerDetails')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_addvehiclesowner` WHERE `vehicle_id`= ".$id." AND `isdelete` = 0";
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($strow))
        {
            
            $data[] = "<tr><td>".$ownerresult['vehicle_ownername']."</td><td>".$ownerresult['date1']."</td><td><a href='#' class='delete' onclick=\"deleteownerrow('".$ownerresult['id']."','".$id."')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicledeleteOwnerData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_addvehiclesowner',$valus,$usercol,'delete',$where);
    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $status = 1;
    }
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExtraData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusquery = "SELECT * FROM `tbl_vehicles` WHERE `id`=".$_POST['id'];
    $strow =  $mysql -> selectFreeRun($statusquery);
    $statusresult = mysqli_fetch_array($strow);
    $options = $statusresult['options'];
    // $extra = explode(",",$statusresult['options']);
    // $extWh = "id = ".implode(" AND id = ",$extra);
    $extranameqry = "SELECT name FROM `tbl_vehicleextra` WHERE `id` IN (".$options.")";
    $extrarow =  $mysql -> selectFreeRun($extranameqry);
    $ext=array();
    if($extrarow)
    {
        $status=1;
        while($extraresult = mysqli_fetch_array($extrarow))
        { 
            $ext[] ="<span class='label label-info' style='font-size: 14px;margin-bottom: 5px;font-weight: 500;'>".$extraresult['name']."</span>&nbsp;&nbsp;";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['extraresult'] = $ext;
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleRenewalTypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclerenewaltype','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleRenewalTypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclerenewaltype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclerenewaltypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclerenewaltype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleRenewalTypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_addvehiclerenewaltype','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['renewal_id'] = $statusresult['renewal_id'];  
        $statusdata['duedate'] = $statusresult['duedate'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleRenewalTypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_addvehiclerenewaltype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehiclerenewaltypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_addvehiclerenewaltype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowVehicleRentalInfoDetails')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclerentagreement` WHERE `vehicle_id`=".$id." AND `isdelete`= 0 AND `isactive`= 0";
    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $rowcount=mysqli_num_rows($rentrow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($rentresult = mysqli_fetch_array($rentrow))
        {
            
            $data[] = "<tr><td>".$rentresult['startdate']."</td><td>".$rentresult['enddate']."</td><td>".$rentresult['rentpriceperday']."</td><td>".$rentresult['date1']."</td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='4'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleChecklistUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere(' tbl_vehiclechecklist','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleChecklistDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update(' tbl_vehiclechecklist',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehiclechecklistisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclechecklist',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExpenseTypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleexpensetype','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExpenseTypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleexpensetype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleexpensetypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleexpensetype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleExpenseUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleexpense','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['expense_id'] = $statusresult['expense_id']; 
        $statusdata['label'] = $statusresult['label']; 
        $statusdata['amount'] = $statusresult['amount']; 
        $statusdata['expensedate'] = $statusresult['expensedate']; 
        $statusdata['description'] = $statusresult['description'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleExpenseDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleexpense',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleexpenseisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleexpense',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleservicetask','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleservicetask',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleservicetask',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceHistoryUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleservicehistory','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['odometer'] = $statusresult['odometer'];  
        $statusdata['completiondate'] = $statusresult['completiondate'];  
        $statusdata['reference'] = $statusresult['reference'];  
        $statusdata['servicetaskid'] = $statusresult['servicetaskid'];  
        $statusdata['labour'] = $statusresult['labour'];  
        $statusdata['parts'] = $statusresult['parts'];  
        $statusdata['subtotal'] = $statusresult['subtotal'];  
        $statusdata['description'] = $statusresult['description'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceHistoryDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleservicehistory',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceHistoryisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleservicehistory',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceReminderUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleservicereminder','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['odometer_interval'] = $statusresult['odometer_interval'];  
        $statusdata['time_interval'] = $statusresult['time_interval'];  
        $statusdata['type_interval'] = $statusresult['type_interval'];  
        $statusdata['servicetaskid'] = $statusresult['servicetaskid'];  
        $statusdata['time_threshold'] = $statusresult['time_threshold'];  
        $statusdata['type_threshold'] = $statusresult['type_threshold'];  
        $statusdata['odometer_threshold'] = $statusresult['odometer_threshold'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceReminderDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleservicereminder',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceReminderisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleservicereminder',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicledocumenttype','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];   
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicledocumenttype',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicledocumenttype',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleContactsUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclecontact','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];   
        $statusdata['email'] = $statusresult['email'];   
        $statusdata['phone'] = $statusresult['phone'];   
        $statusdata['street_address'] = $statusresult['street_address'];   
        $statusdata['postcode'] = $statusresult['postcode'];   
        $statusdata['city'] = $statusresult['city'];   
        $statusdata['state'] = $statusresult['state'];   
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleContactsDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclecontact',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleContactsisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclecontact',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicledocument','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];   
        $statusdata['type'] = $statusresult['type'];   
        $statusdata['expiredate'] = $statusresult['expiredate'];   
        $statusdata['file'] = $statusresult['file'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicledocument',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicledocument',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicletypeaccident','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicletypeaccident',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicletypeaccident',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehicleaccidentstage','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicleaccidentstage',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleaccidentstage',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSetDriver')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $id=$_POST['id'];
    $vid=$_POST['vid'];
    $offencesdate = $_POST['offencesdate'];

    $statusquery = "SELECT a.*,c.name as contractorname,c.id FROM `tbl_assignvehicle` a INNER JOIN tbl_contractor c ON c.id=a.`driver` WHERE a.vid=$vid AND '$offencesdate' BETWEEN a.`start_date` AND a.`end_date`";
    $strow =  $mysql -> selectFreeRun($statusquery);
    
    if($strow)
    {
        $status=1;
        $options = array();
        while($fetch = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$fetch['id'].">".$fetch['contractorname']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'VehicleinsuranceUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_vehiclefleetinsuarnce','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name']; 
        $statusdata['insurance_company'] = $statusresult['insurance_company']; 
        $statusdata['reference_number'] = $statusresult['reference_number']; 
        $statusdata['startdate'] = $statusresult['startdate']; 
        $statusdata['expirydate'] = $statusresult['expirydate'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleinsuranceDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehiclefleetinsuarnce',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleinsuranceisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclefleetinsuarnce',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'ShowVehiclefleetInsurance')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_addvehiclefleetinsurance` where `insurance_id`=".$id." AND `isdelete`=0 ORDER BY  `id` DESC";
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($strow))
        {
            
            $data[] = "<tr><td>".$ownerresult['vehicle']."</td><td><a href='#' class='delete' onclick=\"deleteaddvehiclefleetinsurancerow('".$ownerresult['id']."','".$id."')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'VehiclefleetInsuranceshow')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $expquery = "SELECT v.`id`,v.`registration_number`,vs.`name` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` WHERE v.`isdelete`=0";
    $strow =  $mysql -> selectFreeRun($expquery);
    if($strow)
    {
        $status=1;
        $options = array();
        while($statusresult = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."  (". $statusresult['registration_number'].")</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'deleteaddvehiclefleetinsurance')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_addvehiclefleetinsurance',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'WorkforcwUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_user','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name']; 
        $statusdata['email'] = $statusresult['email']; 
        $statusdata['phone'] = $statusresult['contact']; 
        $statusdata['roleid'] = $statusresult['roleid'];

        $statusdata['invoice_email'] = $statusresult['invoice_email'];
        $statusdata['depot'] = $statusresult['depot'];
        $statusdata['start_date'] = $statusresult['start_date'];
        $statusdata['leave_date'] = $statusresult['leave_date'];
        $statusdata['dob'] = $statusresult['dob'];
        $statusdata['ni_number'] = $statusresult['ni_number'];
        $statusdata['company_name'] = $statusresult['company_name'];
        $statusdata['company_reg'] = $statusresult['company_reg'];
        $statusdata['vat_number'] = $statusresult['vat_number'];
        $statusdata['utr'] = $statusresult['utr'];
        $statusdata['employee_id'] = $statusresult['employee_id'];
        $statusdata['bank_name'] = $statusresult['bank_name'];
        $statusdata['account_number'] = $statusresult['account_number'];
        $statusdata['sort_code'] = $statusresult['sort_code']; 
        $statusdata['street_address'] = $statusresult['street_address']; 
        $statusdata['postcode'] = $statusresult['postcode']; 
        $statusdata['state'] = $statusresult['state']; 
        $statusdata['city'] = $statusresult['city'];
        $statusdata['accountancy_service'] = $statusresult['accountancy_service'];

        $statusdata['arrears'] = $statusresult['arrears'];
        $statusdata['assignto'] = $statusresult['assignto'];
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_user',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'Workforceisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_user',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_workforcemartic','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name']; 
        $statusdata['type'] = $statusresult['type'];   
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_workforcemartic',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_workforcemartic',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_workforcetask','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name']; 
        $statusdata['assignee'] = $statusresult['assignee']; 
        $statusdata['duedate'] = $statusresult['duedate'];   
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_workforcetask',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_workforcetask',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowWorkforceDepotCustomber')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];
    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcedepotcustomer` where `depot_id`=".$id." AND `isdelete`=0 ORDER BY  `id` DESC";

    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($strow))
        {
            
            $data[] = "<tr><td>".$ownerresult['customer']."</td><td><a href='#' class='delete' onclick=\"deleteworkforcedepotcustomerrow('".$ownerresult['id']."','".$id."')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'deleteworkforcedepotcustomer')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_workforcedepotcustomer',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'DepotTargetUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_depottarget','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['metric'] = $statusresult['metric']; 
        $statusdata['target'] = $statusresult['target']; 
        $statusdata['threshold'] = $statusresult['threshold']; 
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'DepotTargetDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_depottarget',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'DepotTargetisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_depottarget',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSetSessionData')
{

    header("content-Type: application/json");

    $status=0;

    $vid=$_POST['vid'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    
    if($vid > 0) {

        $status = 1;
        if(!isset($_SESSION)) 
        {
            session_start();
        }
        $_SESSION['vid']=$vid;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'DepotSetSessionData')
{

    header("content-Type: application/json");

    $status=0;

    $did=$_POST['did'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    
    if($did > 0) {

        $status = 1;
        if(!isset($_SESSION)) 
        {
            session_start();
        }
        $_SESSION['did']=$did;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceSetSessionData')
{

    header("content-Type: application/json");

    $status=0;

    $wid=$_POST['wid'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    
    if($wid > 0) {

        $status = 1;
        if(!isset($_SESSION)) 
        {
            session_start();
        }
        $_SESSION['wid']=$wid;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorSetSessionData')
{

    header("content-Type: application/json");

    $status=0;

    $cid=$_POST['cid'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    
    if($cid > 0) {

        $status = 1;
        if(!isset($_SESSION)) 
        {
            session_start();
        }
        $_SESSION['cid']=$cid;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_workforceattendance','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['wid'] = $statusresult['workforce_id'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['starts'] = $statusresult['starts'];
        $statusdata['end'] = $statusresult['end'];
        $statusdata['description'] = $statusresult['description'];
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_workforceattendance',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_workforceattendance',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'OnboardingUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_onboarding','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'OnboardingDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_onboarding',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Onboardingisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_onboarding',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'ContractorUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_contractor','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['type'] = $statusresult['type'];
        $statusdata['name'] = $statusresult['name'];
        $statusdata['email'] = $statusresult['email'];
        $statusdata['contact'] = $statusresult['contact'];
        $statusdata['invoice_email'] = $statusresult['invoice_email'];
        $statusdata['depot'] = $statusresult['depot'];
        $statusdata['start_date'] = $statusresult['start_date'];
        $statusdata['leave_date'] = $statusresult['leave_date'];
        $statusdata['dob'] = $statusresult['dob'];
        $statusdata['company'] = $statusresult['company'];
        $statusdata['company_reg'] = $statusresult['company_reg'];

        $statusdata['bank_name'] = $statusresult['bank_name'];
        $statusdata['account_number'] = $statusresult['account_number'];
        $statusdata['sort_code'] = $statusresult['sort_code'];

        
        $statusdata['ni_number'] = $statusresult['ni_number'];
        $statusdata['utr'] = $statusresult['utr'];
        $statusdata['vat_number'] = $statusresult['vat_number'];
        $statusdata['employee_id'] = $statusresult['employee_id'];

        $statusdata['address'] = $statusresult['address']; 
        $statusdata['street_address'] = $statusresult['street_address']; 
        $statusdata['postcode'] = $statusresult['postcode']; 
        $statusdata['state'] = $statusresult['state']; 
        $statusdata['city'] = $statusresult['city'];
        $statusdata['country'] = $statusresult['country'];

        $statusdata['accountancy_service'] = $statusresult['accountancy_service'];

        $statusdata['passport_number'] = $statusresult['passport_number'];
        $statusdata['passport_nationality'] = $statusresult['passport_nationality'];
        $statusdata['passport_country'] = $statusresult['passport_country'];
        $statusdata['passport_issuedate'] = $statusresult['passport_issuedate'];
        $statusdata['passport_expirydate'] = $statusresult['passport_expirydate'];

        $statusdata['drivinglicence_country'] = $statusresult['drivinglicence_country'];
        $statusdata['drivinglicence_number'] = $statusresult['drivinglicence_number'];
        $statusdata['drivinglicence_expiry'] = $statusresult['drivinglicence_expiry']; 

        $statusdata['emegency_phone'] = $statusresult['emegency_phone'];
        $statusdata['emegency_name'] = $statusresult['emegency_name'];  

         $statusdata['arrears'] = $statusresult['arrears'];  
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractor',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Contractorisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_contractor',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_contractorcompany','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
        $statusdata['company_reg'] = $statusresult['company_reg'];
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractorcompany',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_contractorcompany',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_contractorattendance','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['cid'] = $statusresult['contractor_id'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['starts'] = $statusresult['starts'];
        $statusdata['end'] = $statusresult['end'];
        $statusdata['description'] = $statusresult['description'];
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractorattendance',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_contractorattendance',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_contractordocument','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];   
        $statusdata['type'] = $statusresult['type'];   
        $statusdata['expiredate'] = $statusresult['expiredate'];   
        $statusdata['file'] = $statusresult['file'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractordocument',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_contractordocument',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_workforcedocument','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];   
        $statusdata['type'] = $statusresult['type'];   
        $statusdata['expiredate'] = $statusresult['expiredate'];   
        $statusdata['file'] = $statusresult['file'];    
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_workforcedocument',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_workforcedocument',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorTimesheetData')
{
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT * FROM `tbl_contractortimesheet` WHERE `userid`= '".$_POST['userid']."' AND `uniqid`='".$_POST['uniqid']."'";
    $timesheetrow =  $mysql -> selectFreeRun($query);
    $result = mysqli_fetch_array($timesheetrow);

    if($result > 0) {

        $status = 1;
        $statusdata['uniqid'] = $result['uniqid'];
        $statusdata['value'] = $result['value'];
        $statusdata['date'] = $result['date'];
        $statusdata['rateid'] = $result['rateid']; 
        $statusdata['ischeckval'] = $result['ischeckval'];  
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTimesheetData')
{
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT * FROM `tbl_workforcetimesheet` WHERE `userid`= '".$_POST['userid']."' AND `uniqid`='".$_POST['uniqid']."'";
    $timesheetrow =  $mysql -> selectFreeRun($query);
    $result = mysqli_fetch_array($timesheetrow);

    if($result > 0) {

        $status = 1;
        $statusdata['uniqid'] = $result['uniqid'];
        $statusdata['value'] = $result['value'];
        $statusdata['date'] = $result['date'];
        $statusdata['rateid'] = $result['rateid']; 
        $statusdata['ischeckval'] = $result['ischeckval'];  
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorFunctionrateData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusquery = "SELECT * FROM `tbl_paymenttype` WHERE `applies`=1 AND `isactive`=0 AND `depot_id` IN (".$_POST['id'].",0)";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        $options = array();
        $options[] = "<option value='0'>--</option>";
        while($statusresult = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowContractorLendInfoDetails')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorlend` WHERE `cid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $rowcount=mysqli_num_rows($rentrow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($rentresult = mysqli_fetch_array($rentrow))
        {
            $data[] = "<tr>";
            $data[] .= "<td> #".$rentresult['id']."</td>";
            $data[] .= "<td> £".$rentresult['amount']."</td>";
            $data[] .= "<td>Generic</td>";
            $data[] .= "<td>".$rentresult['reason']."</td>";
            $data[] .= "<td></td>";
            $data[] .= "<td>".$rentresult['date1']."</td>";
            $data[] .= "<td><a href='#' class='edit' onclick=\"editlendrow('".$rentresult['id']."','".$id."')\"data-toggle='tooltip' title='Edit'><span><i class='fas fa-clock'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='6'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorLendUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_contractorlend','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['amount'] = $statusresult['amount'];
        $statusdata['no_of_instal'] = $statusresult['no_of_instal'];
        $statusdata['time_interval'] = $statusresult['time_interval'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['week_of_payment'] = $statusresult['week_of_payment']; 
        $statusdata['reason'] = $statusresult['reason']; 
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowContractorPaymentInfoDetails')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorpayment` WHERE `cid`=$id AND `isdelete`= 0 AND `isactive`= 0 ORDER BY `week_no` ASC";

    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $rowcount=mysqli_num_rows($rentrow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($rentresult = mysqli_fetch_array($rentrow))
        {
            $data[] = "<tr>";
            $data[] .= "<td> #".$rentresult['loan_id']."</td>";
            $data[] .= "<td> £".$rentresult['amount']."</td>";
            $data[] .= "<td>".$rentresult['reason']."</td>";
            $data[] .= "<td>view</td>";
            $data[] .= "<td>Invoiced</td>";
            $data[] .= "<td>paid</td>";
            $data[] .= "<td>".$rentresult['date']."</td>";
            $data[] .= "<td><a href='#' class='delete' onclick=\"deleterow('".$rentresult['id']."','".$id."')\"data-toggle='tooltip' title='Cancle'><span>Cancle</span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='7'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowWorkforceLendInfoDetails')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcelend` WHERE `wid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $rowcount=mysqli_num_rows($rentrow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($rentresult = mysqli_fetch_array($rentrow))
        {
            $data[] = "<tr>";
            $data[] .= "<td> #".$rentresult['id']."</td>";
            $data[] .= "<td> £".$rentresult['amount']."</td>";
            $data[] .= "<td>Generic</td>";
            $data[] .= "<td>".$rentresult['reason']."</td>";
            $data[] .= "<td></td>";
            $data[] .= "<td>".$rentresult['date1']."</td>";
            $data[] .= "<td><a href='#' class='edit' onclick=\"editlendrow('".$rentresult['id']."','".$id."')\"data-toggle='tooltip' title='Edit'><span><i class='fas fa-clock'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='6'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowWorkforcePaymentInfoDetails')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcepayment` WHERE `wid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql -> selectFreeRun($rentquery);
    $rowcount=mysqli_num_rows($rentrow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($rentresult = mysqli_fetch_array($rentrow))
        {
            $data[] = "<tr>";
            $data[] .= "<td> #".$rentresult['loan_id']."</td>";
            $data[] .= "<td> £".$rentresult['amount']."</td>";
            $data[] .= "<td>".$rentresult['reason']."</td>";
            $data[] .= "<td>view</td>";
            $data[] .= "<td>Invoiced</td>";
            $data[] .= "<td>paid</td>";
            $data[] .= "<td>".$rentresult['date']."</td>";
            $data[] .= "<td><a href='#' class='delete' onclick=\"deleterow('".$rentresult['id']."','".$id."')\"data-toggle='tooltip' title='Cancle'><span>Cancle</span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='7'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceLendUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_workforcelend','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['amount'] = $statusresult['amount'];
        $statusdata['no_of_instal'] = $statusresult['no_of_instal'];
        $statusdata['time_interval'] = $statusresult['time_interval'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['week_of_payment'] = $statusresult['week_of_payment']; 
        $statusdata['reason'] = $statusresult['reason']; 
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ScheduledataGet')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusquery = "SELECT a.*,c.`name` FROM `tbl_assignvehicle` a INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver` WHERE a.`isdelete`=0 AND a.`isactive`=0";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        while($statusresult = mysqli_fetch_array($strow))
        {

            $return_arr[] =  array(
            "startdate" => $statusresult['start_date'],
            "enddate" => $statusresult['end_date'],
            "uniqid" => $statusresult['tduniqid'],
            "name" => $statusresult['name']); 
        }
    }
    
    $mysql -> dbDisConnect();
    echo json_encode($return_arr); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ScheduleDriverData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $vehicleid = $_POST['id'];
    $startdate = $_POST['startdate'];

    $statusquery = "SELECT DISTINCT c.* 
                    FROM `tbl_contractor` c 
                    INNER JOIN `tbl_depot` d ON d.`id`=c.`depot` 
                    INNER JOIN `tbl_vehicles` v ON v.`depot_id`=d.`id` 
                    LEFT JOIN `tbl_assignvehicle` av ON av.`driver`=c.`id` 
                    AND av.`start_date`>=".$startdate." AND av.`end_date`<=".$startdate."
                    WHERE c.`isdelete`=0 AND c.`iscomplated`=1 AND v.`id`=".$vehicleid." AND 
                    ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)=
                    (SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        $options = array();
        $options[] = "<option value='0'>--</option>";
        while($statusresult = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceScheduleData')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $statusquery = "SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0";
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    while($row = mysqli_fetch_array($strow))
    {
        ?>
            <tr>
                <td class="border-right bg-grey-lightest">
                    <div><?php echo $row['name'];?></div>
                    <small><?php echo $row['role_type'];?></small>
                </td>

                <td class="p-0 position-relative border-right">
                    
                </td>

                <td class="p-0 position-relative border-right">
                    
                </td>

                <td class="p-0 position-relative border-right">
                   
                </td>

                <td class="p-0 position-relative border-right">
                   
                </td>

                <td class="p-0 position-relative border-right">
                    
                </td>

                <td class="p-0 position-relative border-right">
                   
                </td>

                <td class="p-0 position-relative border-right">
                    
                </td>                       
            </tr>
        <?php
    }
    $mysql->dbDisconnect();
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceScheduleWeekDataShow')
{

    header("content-Type: application/json");

    $status=0;

    $userid=$_POST['userid'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectFreeRun("SELECT * FROM `tbl_workforceschedule` WHERE `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0");

    while($statusresult = mysqli_fetch_array($vehiclestatus))
    {
            $return_arr[] =  array(
            "uniqid" => $statusresult['uniqid'],
            "date" => $statusresult['date'],
            "ispaidval" => $statusresult['ispaid'],
            "isweekoff" => $statusresult['isweekoff']);
    }

    $mysql -> dbDisConnect();
    echo json_encode($return_arr); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'InvoiceStatusData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $status = $_POST['statusid'];

    $statusquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`=0 AND `isactive`=0 AND `id` NOT IN (".$status.")";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        $options = array();
        while($statusresult = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowActionLog')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $type = $_POST['type'];
    if($type==1)
    {
        $statusquery = "SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr FROM `tbl_contractorstatusinvoice` cs 
                    INNER JOIN `tbl_contractor` c ON c.`id`=cs.`cid`
                    INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid`
                    INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id`
                    INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id`
                    WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=".$type;
    }
    else if($type==2)
    {
        $statusquery ="SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr FROM `tbl_contractorstatusinvoice` cs 
                    INNER JOIN `tbl_user` c ON c.`id`=cs.`cid`
                    INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid`
                    INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id`
                    INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id`
                    WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=".$type;
    }
    else if($type==3)
    {
        $statusquery ="SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr 
             FROM `tbl_contractorstatusinvoice` cs
             INNER JOIN `tbl_contractor` c ON c.`id`=".$_POST['cid']."
             INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid` 
             INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id` 
             INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id` 
             WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=".$type."";
    }
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    while($result = mysqli_fetch_array($strow))
    {
        $date = date("d/m/Y",strtotime($result['status_date']));
        ?>
            <tr>
                <td>
                    <small><a href="javascript:void(0);"><?php echo $result['name'];?></a>
                    Changed status for <strong>Week <?php echo $result['week_no'];?>
                        <br></strong>
                    from <span class='label label-secondary' style='color:<?php echo $result['color'];?> ;background-color:<?php echo $result['backgroundcolor'];?>'><b><?php echo $result['oldstatus'];?></b></span>

                    to <span class='label label-secondary' style='color:<?php echo $result['newcolor'];?> ;background-color:<?php echo $result['newbgclr'];?>'><b><?php echo $result['newstatus'];?></b></span>
                    <div class="text-muted">
                          <?php echo $date;?>
                        </small></div>
                </td>  
            </tr>
        <?php
    }
    $mysql->dbDisconnect();
}
else if(isset($_POST['action']) && $_POST['action'] == 'financeassetdelete')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_financeassets',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AssetsUpdateData')
{

    header("content-Type: application/json");

    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();
    
    $where = "id=". $id;
    $makecol = "type_name,name,number,price,description,assign_to";
    
    $valus[0]['type_name'] = $_POST['type_name'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['number'] = $_POST['number'];
    $valus[0]['price'] = $_POST['price'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['assign_to'] = $_POST['assign_to'];
    
    $statuupdate = $mysql -> update('tbl_financeassets',$valus,$makecol,'update',$where);

        if($statuupdate)
        {
            $status=1;
            $title = 'Update';
            $message = 'Data has been updated successfully.';
        }
        else
        {
            $status=0;
            $title = 'Update Error';
            $message = 'Data can not been updated.';
        }
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'financeAssetsUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();
    
    $vehiclestatus =  $mysql -> selectWhere('tbl_financeassets','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['type_name'] = $statusresult['type_name'];  
        $statusdata['number'] = $statusresult['number'];   
        $statusdata['price'] = $statusresult['price'];   
        $statusdata['description'] = $statusresult['description'];   
        $statusdata['name'] = $statusresult['name'];   
        $statusdata['assign_to'] = $statusresult['assign_to'];   
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowcontractorOnboardingData')
{
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT * FROM `tbl_contractoronboarding` WHERE `isdelete`=0 AND `isactive`=0 AND `cid`=".$_POST['cid'];
    $strow =  $mysql -> selectFreeRun($query);
    if($strow)
    {
        $status=1;
        while($statusresult = mysqli_fetch_array($strow))
        {

            $return_arr[] =  array(
            "onboard_id" => $statusresult['onboard_id'],
            "is_onboard" => $statusresult['is_onboard']); 
        }
    }
    
    $mysql -> dbDisConnect();
    echo json_encode($return_arr);
}
else if(isset($_POST['action']) && $_POST['action'] == 'IscheckcontractorOnboardingData')
{
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT COUNT(c.`id`) as cntrow FROM `tbl_contractor` c WHERE c.`id`=".$_POST['cid']." AND ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)=(SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))";
    $strow =  $mysql -> selectFreeRun($query);
    if($strow)
    {
        $status=1;
        $statusresult = mysqli_fetch_array($strow);
        if($statusresult['cntrow']!=0)
        {
            //if active
            $result = 1;
            $valus[0]['iscomplated'] = 1;
            $valus[0]['isactive'] = 0;
        }
        else
        {
            //if not active
            $result = 0;
            $valus[0]['iscomplated'] = 0;
            $valus[0]['isactive'] = 1;
            
        }
        $makecol = array('isactive','iscomplated');
        $where = 'id ='. $_POST['cid'];
        $update = $mysql -> update('tbl_contractor',$valus,$makecol,'update',$where);
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $result;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'custominvoiceUpdateData')
{

    header("content-Type: application/json");

    $status=0;
    

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_custominvoicedata','id','=',$id,'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['description'] = $statusresult['description'];
        $statusdata['quantity'] = $statusresult['quantity'];
        $statusdata['price'] = $statusresult['price'];
        $statusdata['tax'] = $statusresult['tax'];
        
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'custominvoiceDeleteData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_custominvoicedata',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorWalletDeleteData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractorpayment',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'contractorAssignStatusUpdateData')
{

    header("content-Type: application/json");
    $status=0;

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniq_id'] = $_POST['uniqid'];
    $valus[0]['status_id'] = $_POST['statusid'];
    $valus[0]['route'] = $_POST['route'];
    $valus[0]['wave'] = $_POST['wave'];
    $valus[0]['date'] = $_POST['date'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    
    $query = "SELECT * FROM `tbl_contractorstatusassign` WHERE `isactive`=0 AND `isdelete`=0 AND `uniq_id`='".$_POST['uniqid']."' AND `cid`=".$_POST['cid'];
    $strow =  $mysql -> selectFreeRun($query);
    if($statusresult = mysqli_fetch_array($strow))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('status_id','route','wave','update_date');
        $where = 'id ='.$statusresult['id'];
        $makeinsert =  $mysql -> update('tbl_contractorstatusassign',$valus,$usercol,'update',$where);
    }
    else
    {   $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
        $makeinsert = $mysql -> insert('tbl_contractorstatusassign',$valus);
    }

    if($makeinsert)
    {
       //$dayoff = "SELECT * FROM `tbl_contractorstatusassign` WHERE `status_id`=1 AND `cid`=".$_POST['cid']." AND `insert_date` BETWEEN '".$startdate."' AND '".$enddate."' ";

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
    // $usercol = array('isassign_status');
    // $where = 'id ='.$_POST['cid'];
    // $paymentdata =  $mysql -> update('tbl_contractor',$valus,$usercol,'update',$where);
    $mysql -> dbDisConnect();
    $response['status'] =  $status;
    $response['title'] =  $title;
    $response['message'] =  $message;
    echo json_encode($response);
}
else if (isset($_POST['action']) && $_POST['action'] == 'RotaWeekDataShow')
{
    header("content-Type: application/json");
    $status=0;

    $userid=$_POST['userid'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectFreeRun("SELECT * FROM `tbl_contractorstatusassign` WHERE `userid`=".$userid."  AND `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0 ORDER BY `cid` asc,`date` asc");
	//$return_arr=array();
    while($statusresult = mysqli_fetch_array($vehiclestatus))
    {

            $return_arr[] =  array(
            "cid" => $statusresult['cid'],
            "uniqid" => $statusresult['uniq_id'],
            "date" => $statusresult['date'],
            "status_id" => $statusresult['status_id'],
            "route" => $statusresult['route'],
            "wave" => $statusresult['wave']);
    }

    $mysql -> dbDisConnect();
    echo json_encode($return_arr);
}
else if(isset($_POST['action']) && $_POST['action'] == 'RotaCountSceduling')
{
    $userid=$_POST['userid'];
    $depotid = $_POST['depotid'];
    if($depotid=='%')
    {
        $disabled=disabled;
    }
    else
    {
        $disabled='';
    }
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
	?>
		<td class="border-bottom-0 w-40"></td>
	<?php

    // echo "SELECT COUNT(IF(`status_id` = 1,`id`,NULL)) AS working, COUNT(IF( `status_id` = 2,`id`,NULL)) AS dayoff, `date` as rotadate FROM `tbl_contractorstatusassign` WHERE `userid`=".$userid." AND `date`>='$startdate' AND `isdelete`=0 AND `isactive`=0 GROUP BY `date` ORDER BY `date` asc limit 7";

	$working = $mysql -> selectFreeRun("SELECT COUNT(IF(`status_id` = 1,`id`,NULL)) AS working, COUNT(IF( `status_id` = 2,`id`,NULL)) AS dayoff, `date` as rotadate FROM `tbl_contractorstatusassign` WHERE `userid`=".$userid." AND `date`>='$startdate' AND `isdelete`=0 AND `isactive`=0 GROUP BY `date` ORDER BY `date` asc limit 7");
	$workingCount = mysqli_fetch_array($working);

    // for($i=$startdate;$i<=$enddate;$i++)
    while(strtotime($startdate)<=strtotime($enddate))
    {
        $i = $startdate;
        $uniqid = $depotid.'_'.$userid.'_'.$i;
		$needed = 0;

        $avaqry= $mysql -> selectFreeRun("SELECT SUM(value) as needed FROM `tbl_rotacontractor` WHERE (`uniqid`='$uniqid')  OR  (`userid`=".$userid." AND `depotid` LIKE '".$depotid."' AND `date`='$i')");
        $avaresult = mysqli_fetch_array($avaqry);
        if($avaresult>0)
        {
            $needed = $avaresult['needed'];
        }
        $diffrent = (int)($workingCount['working'])-(int)($needed);
    	if(isset($workingCount['rotadate']) && strtotime($i)==strtotime($workingCount['rotadate']))
		{
			?>
	            <td class="rightborder">
	                <div class="progress mb-1 border border-grey-light bor cursor-pointer" style="height: 13px;"  onclick="Timemodal('<?php echo $i;?>');">
	                    <div class="progressbar bg-green-200 text-center" style="width:33.333333333333%;line-height: 1;" data-toggle="tooltip" data-title="Assigned to work">
	                        <small><?php echo $workingCount['working'];?></small>
	                    </div>
	                    <div class="progressbar bg-red-100 p-0 text-center" style="width:66.666666666667%;line-height: 1;" data-toggle="tooltip" data-title="People off this day">
	                        <small><?php echo $workingCount['dayoff'];?></small>
	                    </div>
	                </div>

	                <div id="1626421080.5535">
	                	<div wire:id="G38g3CmNlaWXVYpjIuyh">
						    <div class="border d-flex whitespace-no-wrap border-bottom-0 rounded-t overflow-hidden">
						        <div class="w-1/3 bg-gray-50 text-center border-right" data-toggle="tooltip" data-title="Needed for the day">
						        	<small>Needed</small>
						        </div>
						        <div class="w-1/3 bg-gray-50 text-center border-right" data-toggle="tooltip" data-title="Working for the day">
						       		<small>Avail.</small>
						        </div>
						        <div class="w-1/3 bg-gray-50 text-center">
						        	<small>Diff.</small>
						        </div>
						    </div>
	    
						    <div class="border d-flex rounded-b overflow-hidden">
						        <div class="w-1/3 text-center border-right">
						            <input type="text" <?php echo $disabled;?> name="needed" id="<?php echo $uniqid;?>" class="w-full border-0 text-center" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed;?>">
						        </div>
						        <div class="w-1/3 text-center border-right">
						            <small><?php echo $workingCount['working'];?></small>
						        </div>
						        <div class="w-1/3 text-center bg-red-50 font-weight-600 text-red-600">
						            <small><?php echo $diffrent;?></small>
						        </div>
						    </div>
	    				<div>

			            <small class="mt-1 text-red-600">
			        		<i class="fa fa-exclamation-triangle"></i> You are understaffed
			        	</small>
			        </div>
	            </td>
	        <?php
	        if($workingCount = mysqli_fetch_array($working))
            {

            }
		}
		else
		{
			?>
	            <td class="rightborder">
	                <div class="progress mb-1 border border-grey-light bor cursor-pointer" style="height: 13px;" onclick="Timemodal('<?php echo $i;?>');">
	                    <div class="progressbar bg-green-200 text-center" style="width:33.333333333333%;line-height: 1;" data-toggle="tooltip" data-title="Assigned to work">
	                        <small>0</small>
	                    </div>
	                    <div class="progressbar bg-red-100 p-0 text-center" style="width:66.666666666667%;line-height: 1;" data-toggle="tooltip" data-title="People off this day">
	                        <small>0</small>
	                    </div>
	                </div>

	                <div id="1626421080.5535">
	                	<div wire:id="G38g3CmNlaWXVYpjIuyh">
						    <div class="border d-flex whitespace-no-wrap border-bottom-0 rounded-t overflow-hidden">
						        <div class="w-1/3 bg-gray-50 text-center border-right" data-toggle="tooltip" data-title="Needed for the day">
						        	<small>Needed</small>
						        </div>
						        <div class="w-1/3 bg-gray-50 text-center border-right" data-toggle="tooltip" data-title="Working for the day">
						       		<small>Avail.</small>
						        </div>
						        <div class="w-1/3 bg-gray-50 text-center">
						        	<small>Diff.</small>
						        </div>
						    </div>
	    
						    <div class="border d-flex rounded-b overflow-hidden">
						        <div class="w-1/3 text-center border-right">
                                    <input type="text" <?php echo $disabled;?> name="needed" id="<?php echo $uniqid;?>" class="w-full border-0 text-center" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed;?>">
                                </div>
						        <div class="w-1/3 text-center border-right">
						            <small>0</small>
						        </div>
						        <div class="w-1/3 text-center bg-red-50 font-weight-600 text-red-600">
						            <small><?php echo $needed;?></small>
						        </div>
						    </div>
	    				<div>

			            <small class="mt-1 text-red-600">
			        		<i class="fa fa-exclamation-triangle"></i> You are understaffed
			        	</small>
			        </div>
	            </td>
	        <?php
		}
        $startdate = date('Y-m-d', strtotime($startdate . ' +1 day'));
    }

    $mysql -> dbDisConnect();
}
else if(isset($_POST['action']) && $_POST['action'] == 'IndexPaymentTypeData')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $options = array();
    $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$_POST['id'];
    $row =  $mysql -> selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);

    $statusquery ="SELECT * FROM `tbl_paymenttype` WHERE `isdelete`= 0 AND `isactive`= 0 AND `type`=1 AND `applies`=1 AND `depot_id` IN (".$cntresult['depot'].",0)";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        while($statusresult = mysqli_fetch_array($strow))
        {
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."(£".$statusresult['amount'].")</option>";   
        }
    }
    else
    {
        $options[]="<option value='0'>--</option>";
    }

    if($cntresult['type']==1)
    {
        $type='Self-Employed';
    }
    else
    {
        $type='Limited Company';
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    $response['name'] = $cntresult['name'];
    $response['type'] =  $type;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'InsertRotaPaymentTypeData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['cid'] = $_POST['id'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniqid'] = $_POST['uniqid'];
    $valus[0]['payment_typeid'] = $_POST['paymenttype'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
    $makeinsert = $mysql -> insert('tbl_contractorrotapaymenttype',$valus);

    if($makeinsert)
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
    $mysql -> dbDisConnect();

    $response['status'] =  $status;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowRotaPaymentTypeData')
{

    header("content-Type: application/json");
    $status=0;

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $options = array();

    $query="SELECT c.*,p.`name`,p.`amount` FROM `tbl_contractorrotapaymenttype`  c
            INNER JOIN `tbl_paymenttype` p ON p.`id`=c.`payment_typeid`
            WHERE c.`cid`=".$_POST['id']." AND c.`userid`=".$_POST['userid']."  
            AND c.`uniqid`='".$_POST['uniqid']."' AND c.`date`='".$_POST['date']."'";
    $strow =  $mysql -> selectFreeRun($query);
    if($strow)
    {
        $status=1;
        while($result = mysqli_fetch_array($strow))
        {
            $options[] = '<tr>
                            <td class="pl-3">'.$result["name"].'(£'.$result["amount"].')</td>
                            <td class="text-right">
                                <button class="btn btn-light btn-sm border">AddPayment</button>
                            </td>
                         </tr>';
        }
    }
  
    $mysql -> dbDisConnect();
    $response['status'] =  $status;
    $response['options'] = $options;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowTimeOfDriverRotaData')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $userid = $_POST['userid'];
    $depotid = $_POST['depotid'];
    $date = $_POST['date'];

    $statusquery ="SELECT c.*,r.`name` as cntname FROM `tbl_contractorstatusassign` c INNER JOIN `tbl_contractor` r ON r.`id`=c.`cid` AND r.`depot` LIKE '".$depotid."' WHERE c.`date`='".$date."' AND c.`userid`=".$userid." AND c.`wave`>0";
    $strow =  $mysql -> selectFreeRun($statusquery);
    if(mysqli_num_rows($strow)>0)
    {
        while($statusresult = mysqli_fetch_array($strow))
        {
            if($statusresult['wave']>0)
            {
               ?>   
                    <tr>
                        <?php
                        $tmt = explode(":",$statusresult['wave']);
                        $col1 = $tmt[0] - 8;
                        $col2 = 9;
                        $col3 = 14 - 9 - $col1;
                        if($tmt[0]>=12)
                        {
                            $col2 = 14 - $col1;
                            $col3 = 0;
                        }
                        ?>
                        <td colspan='<?php echo $col1;?>' class='default'></td>
                        <td colspan='<?php echo $col2;?>' style='text-align: left;background-color: #e3fcec!important;'>
                            <i class='far fa-clock'></i>                
                            <strong><?php echo $statusresult['wave'];?></strong>-<?php echo $statusresult['cntname'];?> - 9h
                        </td>
                        <?php if($col3!=0)
                        {
                            ?>

                        <td colspan='<?php echo $col3;?>' class='default'></td>
                        <?php 
                        }
                        ?>
                    </tr>
               <?php 
            }   
        }
    }
    else
    {
       ?>
        <tr>
            <td colspan="14" class="text-center py-4">There are no waves set up for today. 😞</td>
        </tr>
       <?php
    }
    
    $mysql -> dbDisConnect(); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'assignWorkforceUser')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $options = array();

    $statusquery ="SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0 AND `id` NOT IN (1) AND `userid`=".$_POST['id'];
    $strow =  $mysql -> selectFreeRun($statusquery);
    if($strow)
    {
        $status=1;
        while($statusresult = mysqli_fetch_array($strow))
        {
            $options[] ="<option value=".$statusresult['id'].">".$statusresult['name']."</option>";   
        }
    }
    else
    {
        $options[]="<option value='0'>--</option>";
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'InsertUserAssignData')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['wid'] = $_POST['userid'];
    $valus[0]['assignuserid'] = $_POST['assignid'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusinsert = $mysql -> insert('tbl_assignuser',$valus);
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'UserAssignData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(a.`Insert_date`,'%D %M%, %Y') as date1,u.`name`,a.`id` as assignid FROM `tbl_assignuser` a  
                    INNER JOIN `tbl_user` u ON u.`id`=a.`assignuserid`
                    WHERE a.`isdelete`=0  AND  a.`isactive`=0 AND a.`wid`=".$id;
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($strow))
        {
            
            $data[] = "<tr><td>".$ownerresult['name']."</td><td>".$ownerresult['date1']."</td><td><a href='#' class='delete' onclick=\"deleteuserrow('".$ownerresult['assignid']."','".$id."')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'DeleteUserAssignData')
{

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id='.$_POST['id'];

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $paymentdata =  $mysql -> update('tbl_assignuser',$valus,$usercol,'delete',$where);
    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorExpiringDocumentData')
{
    $depotid = $_POST['did'];
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $query = "SELECT DISTINCT d.`name` as depotname,c.`name`,c.`id` as id 
             FROM `tbl_depot` d 
             INNER JOIN `tbl_contractor` c ON c.`depot`=d.`id` AND c.`userid` LIKE ('".$userid."') 
             INNER JOIN tbl_workforcedepotassign w ON w.`userid`=c.`userid`
             WHERE d.`id` LIKE '".$depotid."' AND d.`isdelete`= 0 AND d.`isactive` = 0 AND w.`isactive`=0 AND w.`release_date` IS NULL ORDER BY c.`id` DESC";
    $typerow =  $mysql -> selectFreeRun($query);
    // $data = array();
    $dt="";
    $month = date("m");
    $nextmonth = $month + 1;
    $prevmonth = $month - 1;
    while($typeresult = mysqli_fetch_array($typerow))
    {
        $i = 0;
        $subquery = "SELECT * FROM `tbl_contractordocument` WHERE `contractor_id` =".$typeresult['id']." AND MONTH(`expiredate`) IN ($month,$nextmonth,$prevmonth) ORDER BY `expiredate` DESC";
        $subrow = $mysql -> selectFreeRun($subquery);
        while($result = mysqli_fetch_array($subrow))
        {
            $date = strtotime($result['expiredate']);
            $expiredate =  date('d M, Y', $date);
            $warning = "";
            if(date('Y-m-d H:i:s') < $result['expiredate'])
            {
            $warning = "<i class='fa fa-exclamation-triangle' style='color: red;'></i> ";
            }

            if($i==0)
            {
                $_SESSION['cid'] = $typeresult['id'];
                $dt.= "<tr><td rowspan='@#@#'>".$typeresult['depotname']."</td>
                <td rowspan='@#@#'><span  style='color: blue;' onclick='loadpage(".$typeresult['id'].")'>".$typeresult['name']."</span></td>
                <td>".$result['file']."</td><td>" .$warning . $expiredate."</td><td><button type='button' class='btn btn-info'><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            }
            else
            {
                $dt.= "<tr><td>".$result['file']."</td><td>" .$warning . $expiredate."</td><td><button type='button' class='btn btn-info'><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            }
            $i++;
        }
        $dt = str_replace('@#@#', $i,$dt);
    }
    echo $dt;                             
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSetDriver')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $id=$_POST['id'];
    $vid=$_POST['vid'];
    $offencesdate = $_POST['offencesdate'];

    $statusquery = "SELECT a.*,c.`name` as contractorname,c.`id` FROM `tbl_assignvehicle` a INNER JOIN tbl_contractor c ON c.`id`=a.`driver` WHERE a.`vid`=$vid AND '$offencesdate' BETWEEN a.`start_date` AND a.`end_date`";
    $strow =  $mysql -> selectFreeRun($statusquery);
    
    if($strow)
    {
        $status=1;
        $options = array();
        while($fetch = mysqli_fetch_array($strow))
        {
            
            $options[] ="<option value=".$fetch['id'].">".$fetch['contractorname']."</option>";   
        }
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'tbloffencesImageloaddata')
{

    header("content-Type: application/json");
    $status=0;
    $offences_id = $_POST['offences_id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $sql = "SELECT * FROM `tbl_offencesImage` WHERE `offences_id`=$offences_id";
    $fire =  $mysql -> selectFreeRun($sql);
    
    $rowcount=mysqli_num_rows($fire);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($fire))
        {
            $fileshow = $ownerresult['file'];
            $data[] = "<tr><td>".$ownerresult['file']."</td><td><a target='_blank' href='uploads/offencesform-image/$fileshow' class='adddata'><i class='fas fa-eye fa-lg'></i></a></td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'vehicle_details')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $vid = $_POST['vid'];
    $sql = "SELECT * FROM `tbl_assignvehicle` WHERE `vid` = $vid order by id desc limit 1";
    $exprow =  $mysql -> selectFreeRun($sql);
    $result1 = mysqli_fetch_array($exprow);
    {
        $status=1;
        if($result1['amount'] > 0)
        {
            $priceperday = $result1['amount'];
        }
        else
        {
            $priceperday = "";
            $result1['start_date'] = "";
            $result1['end_date'] = "";
        }
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['amount'] = $priceperday;
    $response['start_date'] = $result1['start_date'];
    $response['end_date'] = $result1['end_date'];
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $folderPath = "uploads/rental-agreement-signature/driver-signature/";
    $image_parts = explode(";base64,", $_POST['signed']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    $driver = $_POST['last_insert_id'];
    $valus[0]['driver_signature'] = $file;
    $where = "id=" .$driver;
    $key = "driver_signature,id";

    $makeinsert = $mysql -> update('tbl_vehiclerental_agreement',$valus ,$key, 'update', $where);
            
    if($makeinsert){
         $status=1;
         $_SESSION['status_id'] = $status;
         $title = 'Insert';
         $message = 'Signature has been update successfully.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Signature can not been update.';
    }
    
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature_validation')
{   
    header("content-Type: application/json");
    $status=0;
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $status_valid =  $_POST['status_id'];
    $usercol = array('driver_signature','id');
    $where = 'id ='. $_POST['status_id'];

    $sql =  "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=$status_valid";
    $exprow =  $mysql -> selectFreeRun($sql);
    $result1 = mysqli_fetch_array($exprow);
    $driver_signature = $result1['driver_signature'];
    if($driver_signature != "")
    {
        $status=1;
        $title = 'Insert';
        $message = 'Signature1 has been Inserted successfully.';
    }else
    {
      $status=0;
      $title = 'Insert Error';
      $message = 'Signature1 can not been Insereted.';
    }
    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature2_validation')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $usercol = array('ledger_signature','id');
    $status_valid =  $_POST['status_id'];

    $sql =  "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=$status_valid";
    $exprow =  $mysql -> selectFreeRun($sql);
    $result1 = mysqli_fetch_array($exprow);
    $driver_signature = $result1['ledger_signature'];
    
    if($driver_signature != "")
    {
        $status=1;
        $title = 'Insert';
        $message = 'Signature2 has been Inserted successfully.';
    }else
    {
      $status=0;
      $title = 'Insert Error';
      $message = 'Signature2 can not been Insereted.';
    }
   
    $name = 'Insert';
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'AttendanceDataLoadTable')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $depot_id = $_POST['depot_id'];
    $inputdate = $_POST['inputdate'];

    $statusquery = "SELECT DISTINCT a.*,c.`name`,v.`registration_number`,v.`id` as vid FROM `tbl_contractorstatusassign` a
                        INNER JOIN `tbl_contractor` c ON c.`id`=a.`cid`  AND c.`depot` LIKE ('".$depot_id."')
                        INNER JOIN `tbl_workforcedepotassign` w ON w.`userid`=c.`userid`
                        LEFT JOIN `tbl_assignvehicle` av ON av.`driver`=c.`id` AND a.`date` 
                        BETWEEN av.`start_date` AND av.`end_date`
                        LEFT JOIN `tbl_vehicles` v ON v.`id`=av.`vid`
                        WHERE a.`userid`='".$userid."' AND a.`status_id`=1 AND a.`date`='".$inputdate."' AND w.`isactive`=0 AND w.`release_date` IS NULL";
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    if($rowcount > 0)
    {
        while($ownerresult = mysqli_fetch_array($strow))
        {

            $vquery="SELECT * FROM `tbl_vehicleinspection` WHERE `isdelete`=0 AND `vehicle_id`=".$ownerresult['vid']." AND `odometerInsert_date` LIKE '".$_POST['inputdate']."%' AND `odometer` IS NOT NULL";
            $row =  $mysql -> selectFreeRun($vquery);
            $result = mysqli_fetch_array($row);

            if($result['id']>0)
            {
                $inspection = '';
            }
            else
            {
                $inspection = '<div class="text-red-600"><strong>Not Inspected</strong></div>';
            }

            $duration =  $ownerresult['duration'];
            $hh = explode(":", $duration);
            $mm = $hh[1];
            $ss = $hh[2];


            if($ownerresult['route'])
            {
                $route = $ownerresult['route'];
            }
            else
            {
                $route = '--';
            }
            ?>
                <tr>
                    <td  class="default">
                    <i class="fa fa-exclamation-triangle" style="color: red;"> </i>  <?php echo $ownerresult['name'] ?></td>
                    <td class="border-left">
                        <?php 
                            if($ownerresult['duration']>0)
                            {
                                echo $hh[0].'h '.$mm.'m '.$ss.'s ';
                            }
                        ?> 
                    </td>
                    <td><?php echo $route;?></td>
                    <td>Working</td>
                    <td class="yellow"><?php echo $ownerresult['wave']?></td>
                    <td>
                        <div class="border border-dashed rounded px-2 py-1 d-flex justify-content-between" style="border-color: #999 !important;">
                            <div>
                                <?php 
                                if($ownerresult['start'])
                                {
                                    ?>
                                    <i class="fas fa-history"></i> 
                                    <time class="font-weight-600"><?php echo $ownerresult['start'] ?></time>
                                    <i class="fas fa-angle-double-right"></i>                                    
                                    <time class="font-weight-600"><?php echo $ownerresult['end'] ?></time>
                                    <?php
                                }
                                ?>                            
                            </div>
                            <div>
                                <a href="#" onclick="DetailModel(<?php echo $ownerresult['id'];?>)"><i class="fas fa-plus"> Set Time</i></a> 
                            </div>
                        </div>
                    </td>
                    <td class="border-left">
                        <span  style='color: blue;' onclick='loadpage(<?php echo $ownerresult['vid']?>)'><?php echo $ownerresult['registration_number'] ?><span>
                    </td>
                    <td><?php echo $inspection;?></td>
                </tr>
            <?php 
        }
    }
    else
    {
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAddWorkingTime')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['start'] = $_POST['start'];
    if($_POST['end'])
    {
        $valus[0]['end'] = $_POST['end'];
        $duration = GetAttendanceTime($_POST['start'],$_POST['end']);
    }
    else
    {
        $valus[0]['end'] = '00:00';
        $duration = '00:00:00';
    }
    $valus[0]['duration'] = $duration;
    $valus[0]['attendance_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $makecol = array('start','end','duration','attendance_date');
    $where = 'id ='. $_POST['id'];

    $statusinsert = $mysql -> update('tbl_contractorstatusassign',$valus,$makecol,'update',$where);
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'RentalAgreementisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehiclerental_agreement',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'accidentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_accident',$valus,$usercol,'delete',$where);
    $mysql -> dbDisConnect();
    
    if($user)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}



else if(isset($_POST['action']) && $_POST['action'] == 'vehicleoffencesisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleoffences',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'accidentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_accident',$valus,$usercol,'delete',$where);
    error_log($user,0);

    $mysql -> dbDisConnect();
    
    if($user)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'getdashboarddata')
{   
    header("content-Type: application/json");
    $depotid = $_POST['depot_id'];
    $userid = $_POST['userid'];

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT COUNT(id) FROM `tbl_contractor` WHERE `isdelete`=0 AND userid=".$userid." AND depot=".$depotid;
    $row =  $mysql -> selectFreeRun($query);
    $cnt = mysqli_fetch_array($row,MYSQLI_ASSOC);

    $query1 = "SELECT COUNT(id) FROM `tbl_user` WHERE `isdelete`=0  AND depot=".$depotid;
    $row2 =  $mysql -> selectFreeRun($query1);
    //user id not defines..
    $workforce = mysqli_fetch_array($row2,MYSQLI_ASSOC);
    $query2 = "SELECT COUNT(id) FROM `tbl_vehicles` WHERE `isdelete`=0 AND userid=".$userid." AND depot_id=".$depotid;
    $row3 =  $mysql -> selectFreeRun($query2);
    
    $vehicle = mysqli_fetch_array($row3,MYSQLI_ASSOC);
    $mysql -> dbDisConnect();
    $response['cnt'] = $cnt;
    $response['vehicle'] = $vehicle;
    $response['workforce'] = $workforce;
    
    echo json_encode($response);     
}
else if(isset($_POST['action']) && $_POST['action'] == 'getechartdata')
{   
    $pendingarray = [];
    $approvedarray = [];
    $disputesarray = [];
    $response = [];
    $table = $_POST['table'];

    header("content-Type: application/json");
    $userid = $_POST['userid'];
    $typeid = $_POST['typeid'];

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT IFNULL(COUNT(a.`id`),0) as cnt FROM tbl_contractorinvoice a INNER JOIN ".$table." b ON b.`id` = a.`cid` WHERE a.`isdelete` = 0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.`userid`=".$userid ." AND a.istype =".$typeid."";
    for($i=1;$i<=12;$i++) 
    {
        $query1 = $query ." AND a.status_id = 1 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row =  $mysql -> selectFreeRun($query1);
        $pending = mysqli_fetch_array($row);
        //print_r($pending);
        //echo $query1;
        array_push($pendingarray,$pending['cnt']);
        
        $query2 = $query . " AND a.status_id = 2 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row2 =  $mysql -> selectFreeRun($query2);
        $approved = mysqli_fetch_array($row2);
        array_push($approvedarray,$approved['cnt']);
        
        $query3 = $query . " AND a.status_id = 9 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row3 =  $mysql -> selectFreeRun($query3);
        $disputes = mysqli_fetch_array($row3);
        
        array_push($disputesarray,$disputes['cnt']);
        
    }
    $mysql -> dbDisConnect();
    $response['pending'] = $pendingarray;
    $response['approved'] = $approvedarray;
    $response['disputes'] = $disputesarray;
    echo json_encode($response);   
}
else if(isset($_POST['action']) && $_POST['action'] == 'getpaymentdata')
{
    $cntarray = [];
    $wfarray = [];
    $response = [];
    header("content-Type: application/json");
    $userid = $_POST['userid'];
    
    $wfquery = "SELECT IFNULL(SUM(w.`amount`),0) FROM `tbl_workforcepayment` w INNER JOIN `tbl_user` u ON u.`id`=w.`wid`
    WHERE  w.`isdelete`=0 AND YEAR(w.`insert_date`) = YEAR(CURRENT_DATE()) AND u.`userid`=".$userid ;
    
    $cntquery = "SELECT IFNULL(SUM(a.`amount`),0) FROM `tbl_contractorpayment` a INNER JOIN `tbl_contractor` b ON b.`id`=a.`cid` WHERE  a.`isdelete`=0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.`userid`=".$userid ;
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    for($i=1;$i<=12;$i++) 
    { 
        $query1 = $wfquery . " AND MONTH(STR_TO_DATE(". $i .", '%m')) = MONTH(w.insert_date) ";
        $row =  $mysql -> selectFreeRun($query1);
        $wf = mysqli_fetch_array($row,MYSQLI_NUM);
        array_push($wfarray,$wf[0]);
        
        $query2 = $cntquery . " AND MONTH(STR_TO_DATE(". $i .", '%m')) = MONTH(a.insert_date) ";
        $row2 =  $mysql -> selectFreeRun($query2);
        $cnt = mysqli_fetch_array($row2,MYSQLI_NUM);
        array_push($cntarray,$cnt[0]);
    }
    $mysql -> dbDisConnect();
    $response['wf'] = $wfarray;
    $response['cnt'] = $cntarray;
    echo json_encode($response);    
}
else if(isset($_POST['action']) && $_POST['action'] == 'accidenttableisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_accident',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadbillingdetails')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $result = $mysql->selectAll('tbl_company');
    $response = mysqli_fetch_array($result);
    $mysql -> dbDisConnect();
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'updatebillingdetails')
{
    header('Content-Type: application/json');
    $value = array();
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $value[0]['street'] = $_POST["street"];
    $value[0]['postcode'] = $_POST['postcode'];
    
    $value[0]['city'] = $_POST['city'];
    $value[0]['country'] = $_POST['country'];
    $value[0]['country1'] = $_POST['country1'];
    
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    
    $colname = array('street', 'postcode', 'city', 'country', 'country1');
    $whr = 'id = 1';
    $empQuery = $mysql -> update("tbl_company", $value, $colname, 'update', $whr);

    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'updateorgdetails')
{
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    header('Content-Type: application/json');
    $value = array();
    $value[0]['name'] = $_POST["name"];
    $value[0]['registration_no'] = $_POST['registration_no'];
    $value[0]['vat'] = $_POST['vat'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    
    $colname = array('name', 'registration_no', 'vat', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql -> update("tbl_company", $value, $colname, 'update', $whr);
    
    $mysql -> dbDisConnect();
    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowrotatableData')
{
    $depotid=$_POST['depot_id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $statusquery = "SELECT * FROM `tbl_contractor` WHERE `userid`=".$_SESSION['userid']."  AND `isdelete`=0 AND `iscomplated`=1 AND `depot` LIKE '".$depotid."'";
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    while($row = mysqli_fetch_array($strow))
    {
        ?>
            <tr id="scheduledata">
                <td class="border-right bg-grey-lightest">
                    <div><strong class="text-blue-600 whitespace-no-wrap">
                        <?php echo $row['name'];?>
                    </strong></div>
                </td>

                <?php
                    $k=0;
                    for($k=1;$k<=7;$k++)
                    {
                        $merge="td_".$_SESSION['userid']."_".$row['id']."_".$k;
                        $td = "td-".$k;
                        ?>
                        <td class="p-0 position-relative border-right <?php echo $td; ?>" id="<?php echo $merge; ?>" onclick="DetailModel(this,<?php echo $row['id']?>);">
                        
                        <div class="w-full h-full">
                          <div class="w-full h-full d-flex align-items-center justify-content-center text-grey-light hover:text-grey-darkest hover:bg-pink-200" style="font-size: 24px; font-weight: 300;height: 62px;">+</div>
                        </div>

                        </td>
                        <?php
                    }
                ?>                 
            </tr>
        <?php
    }
    $mysql->dbDisconnect();
}
else if(isset($_POST['action']) && $_POST['action'] == 'RotaContractorSchedule')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniqid'] = $_POST['uniqid'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['depotid'] = $_POST['depotid'];
    $valus[0]['value'] = $_POST['value'];
    
    $query = "SELECT * FROM `tbl_rotacontractor` WHERE `isdelete`=0 AND `uniqid`='".$_POST['uniqid']."' AND `userid`=".$_POST['userid']." AND `depotid`=".$_POST['depotid']." AND `date`='".$_POST['date']."'";
    $strow =  $mysql -> selectFreeRun($query);
    if($statusresult = mysqli_fetch_array($strow))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('value','update_date');
        $where = 'id ='.$statusresult['id'];
        $makeinsert =  $mysql -> update('tbl_rotacontractor',$valus,$usercol,'update',$where);
    }
    else
    {   $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
        $makeinsert = $mysql -> insert('tbl_rotacontractor',$valus);
    }

    if($makeinsert)
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
    $mysql -> dbDisConnect();
    $response['status'] =  $status;
    $response['title'] =  $title;
    $response['message'] =  $message;
    echo json_encode($response);
}
//bhumika changes
else if(isset($_POST['action']) && $_POST['action'] == 'updategenerale_settingdetails')
{
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $id = $_POST['id'];
    $value[0]['name'] = $_POST["name"];
    $value[0]['contact'] = $_POST['phone'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    
    $colname = array('name', 'contact', 'update_date');
    $whr = 'id ='.$id;
    $empQuery = $mysql -> update("tbl_user", $value, $colname, 'update', $whr);
    $mysql -> dbDisConnect();
    
    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadgenerale_settingdetails')
{
    $id=$_POST['id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $result = $mysql->selectWhere('tbl_user','id','=',$id,'int');
    $response = mysqli_fetch_array($result);
    $mysql -> dbDisConnect();
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'updatesecuritydetails')
{
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];
    $password = $_POST['password'];
    $value[0]['password'] = $_POST["new_password"];
    $value[0]['confirm_password'] = $_POST['confirm_password'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    $colname = array('password', 'confirm_password', 'update_date');
    $whr = 'id ='.$id.' AND password="'.$password.'"';
    $empQuery = $mysql -> update("tbl_user", $value, $colname, 'update', $whr);
    $mysql -> dbDisConnect();
    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'getusageechartdata')
{
    $cntarray = [];
    $wfarray = [];
    $vehiclesarray = [];
    $response = [];

    header("content-Type: application/json");
    $userid = $_POST['userid'];

    $mysql = new Mysql();
    $mysql -> dbConnect();
    for($i=1;$i<=12;$i++) 
    {
        $query1 = "SELECT IFNULL(COUNT(`id`),0) AS COUNT FROM `tbl_contractor` WHERE `isdelete`=0 AND `userid`=".$userid ." AND YEAR(`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row =  $mysql -> selectFreeRun($query1);
        $cnt = mysqli_fetch_array($row);
        //print_r($pending);
        //echo $query1;
        array_push($cntarray,$cnt['COUNT']);
        
        $query2 = "SELECT IFNULL(COUNT(`id`),0) AS COUNT FROM `tbl_user` WHERE `isdelete`=0  AND `userid`=".$userid ." AND YEAR(`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row2 =  $mysql -> selectFreeRun($query2);
        $wf = mysqli_fetch_array($row2);
        array_push($wfarray,$wf['COUNT']);
        
        $query3 = "SELECT IFNULL(COUNT(`id`),0) AS COUNT FROM `tbl_vehicles` WHERE `isdelete`=0 AND `userid`=".$userid ." AND YEAR(`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(`insert_date`) = MONTH(STR_TO_DATE(". $i .", '%m'))";
        $row3 =  $mysql -> selectFreeRun($query3);
        $vehicles = mysqli_fetch_array($row3);
        
        array_push($vehiclesarray,$vehicles['COUNT']);
        
    }
    $mysql -> dbDisConnect();
    $response['Contractors'] = $cntarray;
    $response['Vehicles'] = $wfarray;
    $response['Workforce'] = $vehiclesarray;
    echo json_encode($response);   
}
else if(isset($_POST['action']) && $_POST['action'] == 'updatefinance_settingsdetails')
{
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
   
    $value[0]['period'] = $_POST["period"];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    
    $colname = array('period', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql -> update("tbl_disputepeiod", $value, $colname, 'update', $whr);
    $mysql -> dbDisConnect();
    
    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadfinance_settingsdetails')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $result = $mysql->selectAll('tbl_disputepeiod');
    $response = mysqli_fetch_array($result);
    $mysql -> dbDisConnect();
    
    echo json_encode($response);   
}
else if(isset($_POST['action']) && $_POST['action'] == 'updatetermdetails')
{
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $value[0]['term'] = $_POST['area'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    
    $colname = array('term', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql -> update("tbl_vehicle_term", $value, $colname, 'update', $whr);
    $mysql -> dbDisConnect();
    
    if($empQuery)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadtermdetails')
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $result = $mysql->selectAll('tbl_vehicle_term');
    $response = mysqli_fetch_array($result);
    $mysql -> dbDisConnect();
    
    echo json_encode($response['term']);
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceNewTaskUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $valus[0]['status'] = '1';
    $col = array('status','update_date');
    $where = 'id ='.$id;
    $vehiclestatus =  $mysql -> update('tbl_workforcetask',$valus,$col,'update',$where);
    
    $status=1;
    $mysql -> dbDisConnect();
    
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'OrgDocumentUpdateData')
{

    header("content-Type: application/json");
    $status=0;
    $id=1;
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $vehiclestatus =  $mysql -> selectWhere('tbl_orgdocument','id','=',$id,'int');
    $statusresult = mysqli_fetch_array($vehiclestatus);
    if($statusresult > 0) {
        $status = 1;
        $statusdata['id'] = $statusresult['id']; 
        $statusdata['name'] = $statusresult['name']; 
        $statusdata['file'] = $statusresult['file'];    
    }

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ModalRotaWeeklyData')
{
    $userid=$_POST['userid'];
    $startdate = $_POST['stdate'];
    $enddate = $_POST['endate'];
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                  INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                  WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid`=".$userid;
    $strow =  $mysql -> selectFreeRun($statusquery);
    $rowcount=mysqli_num_rows($strow);
    while($row = mysqli_fetch_array($strow))
    {
        $startdate = $_POST['stdate'];
        $enddate = $_POST['endate'];
        $needed = 0;
          ?>
          <tr id="">
              <td class="border-right bg-grey-lightest">
                  <div><span class="whitespace-no-wrap">
                      <?php echo $row['name'];?>
                  </span></div>
              </td>
            <?php

            $working = $mysql -> selectFreeRun("SELECT COUNT(IF(a.`status_id` = 1,a.`id`,NULL)) AS working, COUNT(IF( a.`status_id` = 2,a.`id`,NULL)) AS dayoff, a.`date` as rotadate FROM `tbl_contractorstatusassign` a 
                INNER JOIN `tbl_contractor` c ON c.`id`=a.`cid`
                WHERE a.`userid`=".$userid." AND a.`date`>='$startdate' AND a.`isdelete`=0 AND a.`isactive`=0 AND c.depot=".$row['id']." GROUP BY a.`date` ORDER BY a.`date` asc limit 7");
            $workingCount = mysqli_fetch_array($working);
            // for($i=$startdate;$i<=$enddate;$i++)
            while(strtotime($startdate)<=strtotime($enddate))
            {
                $i = $startdate;
                $uniqid = $row['id'].'_'.$userid.'_'.$i;
                $avaqry= $mysql -> selectFreeRun("SELECT SUM(value) as needed FROM `tbl_rotacontractor` WHERE (`uniqid`='$uniqid')  OR  (`userid`=".$userid." AND `depotid` LIKE '".$row['id']."' AND `date`='$i')");
                $avaresult = mysqli_fetch_array($avaqry);
                if($avaresult>0)
                {
                    $needed = $avaresult['needed'];
                }
                $diffrent = (int)($workingCount['working'])-(int)($needed);
                if(isset($workingCount['rotadate']) && strtotime($i)==strtotime($workingCount['rotadate']))
                {
                    ?>
                        <td class="position-relative">
                            <div class="position-absolute d-flex"></div>

                            <div class="border  border-red-300  d-flex whitespace-no-wrap border-bottom-0 rounded-t overflow-hidden cursor-help">
                              <div class="w-1/3 bg-gray-50 text-center text-gray-300" data-toggle="tooltip" data-title="Needed for the day" title="" data-original-title="">
                                <small>N</small>
                              </div>
                              <div class="w-1/3 bg-gray-50 text-gray-300 text-center" data-toggle="tooltip" data-title="Working for the day" title="" data-original-title="">
                                <small>A</small>
                              </div>
                              <div class="w-1/3 bg-gray-50 text-gray-300 text-center" data-toggle="tooltip" data-title="Difference" data-original-title="" title="">
                                <small>D</small>
                              </div>
                            </div>
                            <div class="border  border-red-300   d-flex rounded-b overflow-hidden">
                                  <div class="w-1/3 text-center ">
                                      <input type="text" name="needed" id="<?php echo $uniqid;?>"  class="w-full border-0 text-center font-weight-600" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed;?>">
                                  </div>
                                  <div class="w-1/3 text-center">
                                     <small><?php echo $workingCount['working'];?></small>
                                  </div>
                                  <div class="w-1/3 text-center  bg-red-50 text-red-600 ">
                                      <small class=" font-weight-600"><?php echo $diffrent;?></small>
                                  </div>
                            </div>

                              <div>
                              </div>
                        </td>
                    <?php
                    if($workingCount = mysqli_fetch_array($working))
                    {

                    }
                }
                else
                {
                    ?>
                        <td class="position-relative">
                            <div class="position-absolute d-flex"></div>

                            <div class="border  border-red-300  d-flex whitespace-no-wrap border-bottom-0 rounded-t overflow-hidden cursor-help">
                              <div class="w-1/3 bg-gray-50 text-center text-gray-300" data-toggle="tooltip" data-title="Needed for the day" title="" data-original-title="">
                                <small>N</small>
                              </div>
                              <div class="w-1/3 bg-gray-50 text-gray-300 text-center" data-toggle="tooltip" data-title="Working for the day" title="" data-original-title="">
                                <small>A</small>
                              </div>
                              <div class="w-1/3 bg-gray-50 text-gray-300 text-center" data-toggle="tooltip" data-title="Difference" data-original-title="" title="">
                                <small>D</small>
                              </div>
                            </div>
                            <div class="border  border-red-300   d-flex rounded-b overflow-hidden">
                                  <div class="w-1/3 text-center ">
                                      <input type="text" name="needed" id="<?php echo $uniqid;?>"  class="w-full border-0 text-center font-weight-600" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed;?>">
                                  </div>
                                  <div class="w-1/3 text-center">
                                     <small>0</small>
                                  </div>
                                  <div class="w-1/3 text-center  bg-red-50 text-red-600 ">
                                      <small class=" font-weight-600"><?php echo $needed;?></small>
                                  </div>
                            </div>

                              <div>
                              </div>
                        </td>
                    <?php
                }
                $startdate = date('Y-m-d', strtotime($startdate . ' +1 day'));
            }

    }

    $mysql -> dbDisConnect();
}


else if(isset($_POST['action']) && $_POST['action'] == 'inspectionissueisactive')
{

    header("content-Type: application/json");
    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_vehicleinspection',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Companydata')
{

    header("content-Type: application/json");

    $status=0;

    $companyid=$_POST['companyid'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> selectWhere('tbl_contractorcompany','id','=',$companyid,'int');

    $userresult = mysqli_fetch_array($user);

    if($userresult > 0) {

        $status = 1;

        $userdata['company_reg'] = $userresult['company_reg'];

    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['company_reg'] = $userdata['company_reg'];

    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'checkusercontact')
{
  header("content-Type: application/json");
  $status=0;
  $msg = '';
  $mysql = new Mysql();
  $mysql -> dbConnect();
  $contact = $_POST['phone'];
  $email = $_POST['email'];
  $id = $_POST['id'];
  $submitaction = $_POST['submitaction'];
  $query = "SELECT id FROM `tbl_user` WHERE `contact` = '$contact'  AND `isdelete`= 0";
  $rows = $mysql -> selectFreeRun($query);
  $fetch_rows = mysqli_num_rows($rows);
  $query1 = "SELECT id FROM `tbl_user` WHERE  `email` = '$email' AND `isdelete`= 0";
  $rows1 = $mysql -> selectFreeRun($query1);
  $fetch_rows1 = mysqli_num_rows($rows1);
  
  if($submitaction == 'insert') {
      if ($fetch_rows > 0)
      {
            $status=1;
            $msg = 'Contact number has been already registered.';
            
      }
      else if($fetch_rows1 > 0)
      {
            $status=1;
            $msg = 'Email has been already registered.';
      }
      else {
            $status=0;
      }
  }
  if($submitaction == 'update') {
      $query2 = $query . " AND id != ".$id;
      //echo $query2;
      $rows2 = $mysql -> selectFreeRun($query2);
      $fetch_rows2 = mysqli_num_rows($rows2);
      
      $query3 = $query1 . " AND id != ".$id;
      $rows3 = $mysql -> selectFreeRun($query3);
      $fetch_rows3 = mysqli_num_rows($rows3);
      
      if ($fetch_rows2 > 0)
      {
            $status=1;
            $msg = 'Contact number has been already registered.';
            
      }
      else if($fetch_rows3 > 0)
      {
            $status=1;
            $msg = 'Email has been already registered.';
      }
      else {
            $status=0;
      }
  }
  
   $response['status'] = $status;
   $response['msg'] = $msg;
   echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowPreviousWeekDayOff')
{
    header("content-Type: application/json");

    $laststartdate = date('Y-m-d', strtotime($_POST['startdate'] . " -1 days")); 
    $weekno = weekOfYear($laststartdate);
    $weekyear = date('Y', strtotime($laststartdate));

    $week_array = getStartAndEndDate($weekno,$weekyear);
    $startdate=$week_array['week_start'];
    $enddate=$week_array['week_end'];
    $userid=$_POST['userid'];
    $cid = $_POST['cid'];

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $return_arr['startdate']=$startdate;
    $return_arr['enddate']=$enddate;
    $vehiclestatus =  $mysql -> selectFreeRun("SELECT * FROM `tbl_contractorstatusassign` WHERE `userid`=".$userid."  AND `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0 AND `cid` LIKE '".$cid."' ORDER BY `date` desc");
    //$return_arr=array();
    while($statusresult = mysqli_fetch_array($vehiclestatus))
    {

            $return_arr['dtl'][] =  array(
            "cid" => $statusresult['cid'],
            "uniqid" => $statusresult['uniq_id'],
            "date" => $statusresult['date'],
            "status_id" => $statusresult['status_id'],
            "route" => $statusresult['route'],
            "wave" => $statusresult['wave']);
    }

    $mysql -> dbDisConnect();

    echo json_encode($return_arr);
   // echo $laststartdate.'-----------'.$weekno.'------'.$fromDate.'---------'.$toDate;
}
else if(isset($_POST['action']) && $_POST['action'] == 'ShowPreviousWeekDayOnchange')
{
    //header("content-Type: application/json");
    //$laststartdate = date('Y-m-d', strtotime($_POST['startdate'] . " -1 days")); 
    $PreviousDate = array();
    $nextDate = array();

    for ($i=1; $i<=7; $i++)
    {
        $PreviousDate[]=date('Y-m-d', strtotime($_POST['startdate'] . $i." days ago")).'<br />';
        $nextDate[]=date('Y-m-d', strtotime($_POST['startdate'] ."+".$i." day")).'<br />';

    }
    echo implode(',', $PreviousDate);
    echo implode(',', $nextDate);

    // $startdate=$week_array['week_start'];
    // $enddate=$week_array['week_end'];
    // $userid=$_POST['userid'];
    // $cid = $_POST['cid'];

}


else if(isset($_POST['action']) && $_POST['action'] == 'Auditisactive')
{

    header("content-Type: application/json");

    $status=0;
    $id = $_POST['id'];
    if($_POST['status'] == 0)
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }
    
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql -> dbConnect();

    if($id > 0)
    {
        $isactivecol = array('isactive','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_audit',$valus,$isactivecol,'update',$where);
        $status=1;
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $isactiveupdate;
    echo json_encode($response); 
}


?>