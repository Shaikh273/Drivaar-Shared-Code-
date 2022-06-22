<?php
include 'DB/config.php';
include("invoiceAmountClass.php");
include 'base2n.php';
date_default_timezone_set('Europe/London');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userid = $_SESSION['userid'];

function GetAttendanceTime($starttime, $endtime)
{
    $time1 = $starttime;
    $time2 = $endtime;
    $time1 = explode(':', $time1);
    $time2 = explode(':', $time2);
    $hours1 = $time1[0];
    $hours2 = $time2[0];
    $mins1 = $time1[1];
    $mins2 = $time2[1];
    $hours = $hours2 - $hours1;
    $mins = 0;
    if ($hours < 0) {
        $hours = 24 + $hours;
    }
    if ($mins2 >= $mins1) {
        $mins = $mins2 - $mins1;
    } else {
        $mins = ($mins2 + 60) - $mins1;
        $hours--;
    }
    if ($mins < 9) {
        $mins = str_pad($mins, 2, '0', STR_PAD_LEFT);
    }
    if ($hours < 9) {
        $hours = str_pad($hours, 2, '0', STR_PAD_LEFT);
    }
    return  $hours . ':' . $mins;
}

function weekOfYear($date1)
{
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
    $dto->setISODate($year, $week, 0);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}
function getAlphaCode($n,$pad){
        $alphabet   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $n = (int)$n;
        if($n <= 26){
            $alpha =  $alphabet[$n-1];
        } elseif($n > 26) {
            $dividend   = ($n);
            $alpha      = '';
            $modulo     ='';
            while($dividend > 0){
                $modulo     = ($dividend - 1) % 26;
                $alpha      = $alphabet[$modulo].$alpha;
                $dividend   = floor((($dividend - $modulo) / 26));
            }
        }
        return str_pad($alpha,$pad,"0",STR_PAD_LEFT);
}

function WorkforceInvoiceTotal($id)
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $cntquery = "SELECT c.*,ci.*,ci.cid as workforceid FROM `tbl_user` c 
    INNER JOIN `tbl_contractorinvoice` ci ON ci.cid=c.id WHERE ci.invoice_no='$id'";
    $cntrow =  $mysql->selectFreeRun($cntquery);
    $result1 = mysqli_fetch_array($cntrow);
    $totalamount = 0;
    $vatFlag = 0;
    $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ci.`vat` as civat,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat`,u.`vat_number` as vat_number1   
            FROM `tbl_contractorinvoice` ci
            INNER JOIN `tbl_workforcetimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`wid`=ci.`cid`
            INNER JOIN `tbl_user` u ON u.`id`=ci.`cid`
            INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
            WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=" . $id . " ORDER BY p.`type` ASC";
    $tblrow =  $mysql->selectFreeRun($tblquery);
    $finaltotal = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        // if(!empty($tblresult['vat_number1']))
        // {
        //    $vatFlag = 1;
        // }else
        // {
        //     $vatFlag = 0;
        // }
        $vatFlag = $tblresult['civat'];
        $type = $tblresult['type'];
        $net = $tblresult['amount'] * $tblresult['value'];
        $vat = 0;
        if ($vatFlag == 1) {
            $vat = ($net * $tblresult['vat']) / 100;
        }
        $neg = "";
        if ($type == 3) {
            $net = -$net;
            $vat = -$vat;
            $neg = "-";
        }
        $total = $net + $vat;
        $finaltotal += $total;
    }

    $paidamount = 0;
    $singleamount = 0;
    $tquery = "SELECT c.*,l.`amount` as totalamount,l.`no_of_instal`,c.reason  
    FROM `tbl_workforcepayment` c INNER JOIN `tbl_workforcelend` l ON l.`id`=c.`loan_id` 
    WHERE c.`wid`=" . $result1['workforceid'] . " AND c.`week_no`=" . $result1['week_no'] . " AND c.`isdelete`=0";
    $trow =  $mysql->selectFreeRun($tquery);
    while ($tresult = mysqli_fetch_array($trow)) {
        $resn[] = $tresult['reason'];
        $amnt123[] = $tresult['amount'];
        $paidamount += $tresult['amount'];
        $singleamount += $tresult['totalamount'] / $tresult['no_of_instal'];
    }
    $totalamount = ($finaltotal - $paidamount);
    $mysql->dbDisConnect();
    return  $totalamount;
}


if (isset($_POST['action']) && $_POST['action'] == 'permission') {

    header("content-Type: application/json");
    $status = 0;
    $binary = new Base2n(1);
    $decoded = $binary->decode($_POST['binarycode']);
    $valus[0]['permissioncode'] = $decoded;
    $roleid = $_POST['role'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($valus[0]['permissioncode'] && $roleid > 0) {
        $permission = "SELECT * FROM `tbl_permissioncheck` WHERE `role_id` = " . $roleid . " and  `isactive` = 0 and `isdelete` = 0";
        $permissionrow =  $mysql->selectFreeRun($permission);
        if ($pr_result = mysqli_fetch_array($permissionrow)) {

            $valus[0]['update_date'] = date('Y-m-d H:i:s');
            $prid =  $pr_result['id'];
            $prcol = array('permissioncode', 'update_date');
            $where = 'id =' . $prid;
            $permissioninsert = $mysql->update('tbl_permissioncheck', $valus, $prcol, 'update', $where);
        } else {
            $valus[0]['role_id']  = $_POST['role'];
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $permissioninsert = $mysql->insert('tbl_permissioncheck', $valus);
        }
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'viewpermission') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $roleid = $_POST['role'];

    if ($roleid) {
        $binary = new Base2n(1);
        $permission = "SELECT * FROM `tbl_permissioncheck` WHERE `role_id` = " . $roleid . " and  `isactive` = 0 and `isdelete` = 0";
        $permissionrow =  $mysql->selectFreeRun($permission);
        $pr_result = mysqli_fetch_array($permissionrow);
        $binarycode =  $pr_result['permissioncode'];
        if ($binarycode) {
            $permissioncode = $binary->encode($binarycode);
            $status = 1;
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['permissioncode'] = $permissioncode;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'UserUpdateData') {
    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $user =  $mysql->selectWhere('tbl_user', 'id', '=', $id, 'int');
    $userresult = mysqli_fetch_array($user);
    if ($userresult > 0) {
        $status = 1;
        $userdata['roleid'] = $userresult['roleid'];
        $userdata['name'] = $userresult['name'];
        $userdata['contact'] = $userresult['contact'];
        $userdata['email'] = $userresult['email'];
        $userdata['address'] = $userresult['address'];
        $userdata['password'] = $userresult['password'];
        $userdata['confirm_password'] = $userresult['confirm_password'];
    }
    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['userdata'] = $userdata;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'UserDeleteData') {
    header("content-Type: application/json");
    $status = 0;
    $valus[0]['delete_date'] = date('Y-m-d H:i:s');
    $valus[0]['isdelete'] = 1;
    $usercol = array('delete_date', 'isdelete');
    $where = 'id =' . $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $user =  $mysql->update('tbl_user', $valus, $usercol, 'delete', $where);
    $mysql->dbDisConnect();
    if ($user) {
        $response['status'] = 1;
    }
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Userisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }
    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_user', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
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
else if (isset($_POST['action']) && $_POST['action'] == 'DepotUpdateData') {
    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $user =  $mysql->selectWhere('tbl_depot', 'id', '=', $id, 'int');
    $userresult = mysqli_fetch_array($user);
    if ($userresult > 0) {
        $status = 1;
        $userdata['name'] = $userresult['name'];
        $userdata['invoicetype'] = $userresult['invoicetype'];
        $userdata['supervisor'] = $userresult['supervisor'];
        $userdata['reference'] = $userresult['reference'];
        $userdata['isactive'] = $userresult['isactive'];
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['userdata'] = $userdata;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DepotDeleteData') {

    header("content-Type: application/json");
    $status = 0;
    $valus[0]['delete_date'] = date('Y-m-d H:i:s');
    $valus[0]['isdelete'] = 1;
    $usercol = array('delete_date', 'isdelete');
    $where = 'id =' . $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $user =  $mysql->update('tbl_depot', $valus, $usercol, 'delete', $where);
    $mysql->dbDisConnect();
    if ($user) {
        $response['status'] = 1;
    }
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Depotisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_depot', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'PaymenttypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $payment =  $mysql->selectWhere('tbl_paymenttype', 'id', '=', $id, 'int');

    $userresult = mysqli_fetch_array($payment);

    if ($userresult > 0) {

        $status = 1;

        $paymentdata['name'] = $userresult['name'];

        $paymentdata['blockoftime'] = $userresult['blockoftime'];

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

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['paymentdata'] = $paymentdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'PaymenttypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_paymenttype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $status = 1;
    }

    $response['status'] = $status;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Paymenttypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_paymenttype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'check_vehicle_registration_number') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $reg_number = $_POST['reg_number'];
    $query = "SELECT * FROM `tbl_vehicles` WHERE `registration_number` = '$reg_number' and `isdelete`= 0";
    $rows = $mysql->selectFreeRun($query);
    $fetch_rows = mysqli_num_rows($rows);
    if ($fetch_rows > 0) {
        $status = 1;
    } else {
        $status = 0;
    }
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleStatusUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclestatus', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];

        $statusdata['colorcode'] = $statusresult['colorcode'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleStatusDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $statuscol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclestatus', $valus, $statuscol, 'delete', $where);


    $mysql->dbDisConnect();

    if ($paymentdata) {
        $status = 1;
    }

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclestatusisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclestatus', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleMakeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclemake', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];

        $statusdata['description'] = $statusresult['description'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleMakeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclemake', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclemakeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclemake', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleModelUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclemodel', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];

        $statusdata['description'] = $statusresult['description'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleModelDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclemodel', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclemodelisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclemodel', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleGroupUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $grouprow =  $mysql->selectWhere('tbl_vehiclegroup', 'id', '=', $id, 'int');

    $groupresult = mysqli_fetch_array($grouprow);

    if ($groupresult > 0) {

        $status = 1;

        $groupdata['name'] = $groupresult['name'];

        $groupdata['rentper_day'] = $groupresult['rentper_day'];

        $groupdata['insuranceper_day'] = $groupresult['insuranceper_day'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['groupdata'] = $groupdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleGroupDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $groupdata =  $mysql->update('tbl_vehiclegroup', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($groupdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclegroupisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclegroup', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleSupplierUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclesupplier', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleSupplierDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclesupplier', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclesupplierisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclesupplier', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleTypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicletype', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleTypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicletype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicletypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicletype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleowner', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleowner', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleownerisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleowner', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleExtraUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleextra', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleExtraDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleextra', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleextraisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleextra', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleRentalInfoDetails') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $rentquery = "SELECT * FROM `tbl_vehiclerentagreement` WHERE `vehicle_id`=" . $_POST['id'] . " AND `isdelete`= 0 AND `isactive`= 0";
    $rentrow =  $mysql->selectFreeRun($rentquery);
    $statusresult = mysqli_fetch_array($rentrow);
    if ($statusresult) {
        $status = 1;
        $rentdata['rentid'] = $statusresult['id'];
        $rentdata['startdate'] = $statusresult['startdate'];
        $rentdata['enddate'] = $statusresult['enddate'];
        $rentdata['rentpriceperday'] = $statusresult['rentpriceperday'];
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['rentdata'] = $rentdata;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAddOwnerData') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $valus[0]['vehicle_id'] = $_POST['id'];
    $valus[0]['vehicle_ownerid'] = $_POST['ownerId'];
    $query = "SELECT * FROM `tbl_vehicleowner` WHERE `id`=" . $_POST['ownerId'];
    $userrow =  $mysql->selectFreeRun($query);
    $userresult = mysqli_fetch_array($userrow);
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $valus[0]['vehicle_ownername'] = $userresult['name'];


    $statusinsert = $mysql->insert('tbl_addvehiclesowner', $valus);
    if ($statusinsert) {
        $status = 1;
    } else {
        $status = 0;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus = "SELECT v.*,vs.`name` as statusname,vs.`colorcode`,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,vfi.`id` as insurance,vi.`goods_in_transit`,vi.`public_liability` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
            LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id` 
            LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance`
            WHERE v.`id`=" . $id;
    $row =  $mysql->selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if ($statusresult > 0) {

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
    } else {
        $status = 0;
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicles', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicles', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleStatusData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusquery = "SELECT * FROM `tbl_vehiclestatus` WHERE `isdelete`= 0 AND `isactive`= 0";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        $options = array();
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleOwnerDetails') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_addvehiclesowner` WHERE `vehicle_id`= " . $id . " AND `isdelete` = 0";
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($ownerresult = mysqli_fetch_array($strow)) {

            $data[] = "<tr><td>" . $ownerresult['vehicle_ownername'] . "</td><td>" . $ownerresult['date1'] . "</td><td><a href='#' class='delete' onclick=\"deleteownerrow('" . $ownerresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicledeleteOwnerData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_addvehiclesowner', $valus, $usercol, 'delete', $where);
    $mysql->dbDisConnect();

    if ($paymentdata) {
        $status = 1;
    }
    $response['status'] = $status;
    echo json_encode($response);
}
 else if (isset($_POST['action']) && $_POST['action'] == 'VehicleExtraData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusquery = "SELECT * FROM `tbl_vehicles` WHERE `id`=" . $_POST['id'];
    $strow =  $mysql->selectFreeRun($statusquery);
    $statusresult = mysqli_fetch_array($strow);
    $options = $statusresult['options'];
    // $extra = explode(",",$statusresult['options']);
    // $extWh = "id = ".implode(" AND id = ",$extra);
    $extranameqry = "SELECT name FROM `tbl_vehicleextra` WHERE `id` IN (" . $options . ")";
    $extrarow =  $mysql->selectFreeRun($extranameqry);
    $ext = array();
    if ($extrarow) {
        $status = 1;
        while ($extraresult = mysqli_fetch_array($extrarow)) {
            $ext[] = "<span class='label label-info' style='font-size: 14px;margin-bottom: 5px;font-weight: 500;'>" . $extraresult['name'] . "</span>&nbsp;&nbsp;";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['extraresult'] = $ext;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'WalletImagesData') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $statusquery = "SELECT * FROM `tbl_contractorpayment` WHERE `id`=" . $_POST['id'];
    $strow =  $mysql->selectFreeRun($statusquery);
    $statusresult = mysqli_fetch_array($strow);
    $options = explode(",", $statusresult['file']);
    ?>
    <table class='display nowrap table table-hover table-striped table-bordered'>
        <thead>
            <tr><th>Image</th><th>View</th></tr>
        </thead>
        <tbody>
    <?php
    if ($options) {
        $status = 1;
        for($i=0;$i<count($options);$i++)
        {
            echo "<tr><td>".$options[$i]."</td>
                    <td><a target='_blank' href='http://drivaar.com/home/uploads/contractorwalletdocument/".$options[$i]."' class='adddata'>
                    <i class='fas fa-eye fa-lg'></i></a>
                    </td>
                  </tr>";
        }}
    ?>
    </tbody></table>
    <?php
    $mysql->dbDisConnect();
} 
else if (isset($_POST['action']) && $_POST['action'] == 'VehicleRenewalTypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclerenewaltype', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleRenewalTypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclerenewaltype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclerenewaltypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclerenewaltype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleRenewalTypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_addvehiclerenewaltype', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['renewal_id'] = $statusresult['renewal_id'];
        $statusdata['duedate'] = $statusresult['duedate'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleRenewalTypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_addvehiclerenewaltype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehiclerenewaltypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_addvehiclerenewaltype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowVehicleRentalInfoDetails') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclerentagreement` WHERE `vehicle_id`=" . $id . " AND `isdelete`= 0 AND `isactive`= 0";
    $rentrow =  $mysql->selectFreeRun($rentquery);
    $rowcount = mysqli_num_rows($rentrow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($rentresult = mysqli_fetch_array($rentrow)) {

            $data[] = "<tr><td>" . $rentresult['startdate'] . "</td><td>" . $rentresult['enddate'] . "</td><td>" . $rentresult['rentpriceperday'] . "</td><td>" . $rentresult['date1'] . "</td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='4'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleChecklistUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere(' tbl_vehiclechecklist', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleChecklistDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update(' tbl_vehiclechecklist', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehiclechecklistisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclechecklist', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'VehicleExpenseTypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleexpensetype', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
}
else if (isset($_POST['action']) && $_POST['action'] == 'WalletCategoryTypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_walletcategory', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'VehicleExpenseTypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleexpensetype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleexpensetypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleexpensetype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'WalletCategoryTypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_walletcategory', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'Walletcategorytypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_walletcategory', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleExpenseUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleexpense', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['expense_id'] = $statusresult['expense_id'];
        $statusdata['label'] = $statusresult['label'];
        $statusdata['amount'] = $statusresult['amount'];
        $statusdata['expensedate'] = $statusresult['expensedate'];
        $statusdata['description'] = $statusresult['description'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleExpenseDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleexpense', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleexpenseisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleexpense', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleservicetask', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleservicetask', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleservicetask', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceHistoryUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleservicehistory', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

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

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceHistoryDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleservicehistory', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleServiceHistoryisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleservicehistory', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceReminderUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleservicereminder', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['odometer_interval'] = $statusresult['odometer_interval'];
        $statusdata['time_interval'] = $statusresult['time_interval'];
        $statusdata['type_interval'] = $statusresult['type_interval'];
        $statusdata['servicetaskid'] = $statusresult['servicetaskid'];
        $statusdata['time_threshold'] = $statusresult['time_threshold'];
        $statusdata['type_threshold'] = $statusresult['type_threshold'];
        $statusdata['odometer_threshold'] = $statusresult['odometer_threshold'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceReminderDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleservicereminder', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleServiceReminderisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleservicereminder', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicledocumenttype', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['isexpiry_date'] = $statusresult['isexpiry_date'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicledocumenttype', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicledocumenttype', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleContactsUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclecontact', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['email'] = $statusresult['email'];
        $statusdata['phone'] = $statusresult['phone'];
        $statusdata['street_address'] = $statusresult['street_address'];
        $statusdata['postcode'] = $statusresult['postcode'];
        $statusdata['city'] = $statusresult['city'];
        $statusdata['state'] = $statusresult['state'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleContactsDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclecontact', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleContactsisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclecontact', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicledocument', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['expiredate'] = $statusresult['expiredate'];
        $statusdata['file'] = $statusresult['file'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicledocument', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicledocument', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicletypeaccident', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicletypeaccident', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicletypeaccident', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehicleaccidentstage', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehicleaccidentstage', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleaccidentstage', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleSetDriver') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $id = $_POST['id'];
    $vid = $_POST['vid'];
    $offencesdate = $_POST['offencesdate'];

    $statusquery = "SELECT a.*,c.name as contractorname,c.id FROM `tbl_vehiclerental_agreement` a INNER JOIN tbl_contractor c ON c.id=a.`driver_id` WHERE a.vehicle_id=$vid AND '$offencesdate' BETWEEN a.`pickup_date` AND a.`return_date`";
    $strow =  $mysql->selectFreeRun($statusquery);

    if ($strow) {
        $status = 1;
        $options = array();
        while ($fetch = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $fetch['id'] . ">" . $fetch['contractorname'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleinsuranceUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_vehiclefleetinsuarnce', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['insurance_company'] = $statusresult['insurance_company'];
        $statusdata['reference_number'] = $statusresult['reference_number'];
        $statusdata['startdate'] = $statusresult['startdate'];
        $statusdata['expirydate'] = $statusresult['expirydate'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleinsuranceDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_vehiclefleetinsuarnce', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Vehicleinsuranceisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclefleetinsuarnce', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowVehiclefleetInsurance') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_addvehiclefleetinsurance` where `insurance_id`=" . $id . " AND `isdelete`=0 ORDER BY  `id` DESC";
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($ownerresult = mysqli_fetch_array($strow)) {

            $data[] = "<tr><td>" . $ownerresult['vehicle'] . "</td><td><a href='#' class='delete' onclick=\"deleteaddvehiclefleetinsurancerow('" . $ownerresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehiclefleetInsuranceshow') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $expquery = "SELECT v.`id`,v.`registration_number`,vs.`name` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` WHERE v.`isdelete`=0";
    $strow =  $mysql->selectFreeRun($expquery);
    if ($strow) {
        $status = 1;
        $options = array();
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "  (" . $statusresult['registration_number'] . ")</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'deleteaddvehiclefleetinsurance') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_addvehiclefleetinsurance', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforcwUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_user', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

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

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_user', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Workforceisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_user', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_workforcemartic', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['type'] = $statusresult['type'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_workforcemartic', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_workforcemartic', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_workforcetask', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['assignee'] = $statusresult['assignee'];
        $statusdata['duedate'] = $statusresult['duedate'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_workforcetask', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_workforcetask', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowWorkforceDepotCustomber') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];
    $statusquery = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcedepotcustomer` where `depot_id`=" . $id . " AND `isdelete`=0 ORDER BY  `id` DESC";

    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($ownerresult = mysqli_fetch_array($strow)) {

            $data[] = "<tr><td>" . $ownerresult['customer'] . "</td><td><a href='#' class='delete' onclick=\"deleteworkforcedepotcustomerrow('" . $ownerresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'deleteworkforcedepotcustomer') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_workforcedepotcustomer', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DepotTargetUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_depottarget', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['metric'] = $statusresult['metric'];
        $statusdata['target'] = $statusresult['target'];
        $statusdata['threshold'] = $statusresult['threshold'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DepotTargetDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_depottarget', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DepotTargetisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_depottarget', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'VehicleSetSessionData') {

    header("content-Type: application/json");

    $status = 0;

    $vid = $_POST['vid'];

    $mysql = new Mysql();

    $mysql->dbConnect();


    if ($vid > 0) {

        $status = 1;
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['vid'] = $vid;
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DepotSetSessionData') {

    header("content-Type: application/json");

    $status = 0;

    $did = $_POST['did'];

    $mysql = new Mysql();

    $mysql->dbConnect();


    if ($did > 0) {

        $status = 1;
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['did'] = $did;
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceSetSessionData') {

    header("content-Type: application/json");

    $status = 0;

    $wid = $_POST['wid'];

    $mysql = new Mysql();

    $mysql->dbConnect();


    if ($wid > 0) {

        $status = 1;
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['wid'] = $wid;
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorSetSessionData') {

    header("content-Type: application/json");

    $status = 0;

    $cid = $_POST['cid'];

    $mysql = new Mysql();

    $mysql->dbConnect();


    if ($cid > 0) {

        $status = 1;
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['cid'] = $cid;
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_workforceattendance', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;
        $statusdata['wid'] = $statusresult['workforce_id'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['starts'] = $statusresult['starts'];
        $statusdata['end'] = $statusresult['end'];
        $statusdata['description'] = $statusresult['description'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_workforceattendance', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_workforceattendance', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'OnboardingUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_onboarding', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'OnboardingDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_onboarding', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Onboardingisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_onboarding', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_contractor', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

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

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_contractor', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'Contractorisactive') {

    header("content-Type: application/json");
    $mysql = new Mysql();
    $mysql->dbConnect();
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
        $iscompleted = 0;
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $valus[0]['is_onboard'] = 0;
        $makecol = array('is_onboard', 'update_date');
        $where = 'cid =' . $id;
        $statuupdate = $mysql->update('tbl_contractoronboarding', $valus, $makecol, 'update', $where);
        if ($statuupdate) {
            if ($id > 0) {
                $isactiveupdate =  $mysql->selectFreeRun("UPDATE tbl_contractor SET `update_date`='" . date('Y-m-d H:i:s') . "',`isactive`=1,`iscomplated`=0 WHERE id =" . $id . "");
                $status = 1;
            }
        } else {
            $status = 0;
        }
    } else {
        $status = 0;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_contractorcompany', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
        $statusdata['company_reg'] = $statusresult['company_reg'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_contractorcompany', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_contractorcompany', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_contractorattendance', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;
        $statusdata['cid'] = $statusresult['contractor_id'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['starts'] = $statusresult['starts'];
        $statusdata['end'] = $statusresult['end'];
        $statusdata['description'] = $statusresult['description'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_contractorattendance', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_contractorattendance', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_contractordocument', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['expiredate'] = $statusresult['expiredate'];
        $statusdata['file'] = $statusresult['file'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_contractordocument', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_contractordocument', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_workforcedocument', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['expiredate'] = $statusresult['expiredate'];
        $statusdata['file'] = $statusresult['file'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_workforcedocument', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceDocumentisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_workforcedocument', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorTimesheetData') {
    header("content-Type: application/json");
    $status = 0;
    $uniqOrId = array();
    $mysql = new Mysql();
    $mysql->dbConnect();
    $status = 0;
    $statusdata = array();
    $i =0;
    $ndtPad = explode("-",$_POST['showDate']);
    $ndtFin = $ndtPad[0]."-".str_pad($ndtPad[1], 2, "0", STR_PAD_LEFT)."-".str_pad($ndtPad[2], 2, "0", STR_PAD_LEFT);
    $uidt = $ndtFin."-0-".$_POST['userid'];
    $paidFlag=0;
    $checkPaid = "SELECT `id`,`status_id` FROM `tbl_contractorinvoice` WHERE `cid`=".$_POST['userid']." AND '$ndtFin' BETWEEN `from_date` AND `to_date`";
    $runPaind = $mysql->selectFreeRun($checkPaid);
    while($getPaid = mysqli_fetch_array($runPaind))
    {
        if($getPaid['status_id']==4)
        {
            $paidFlag=1;
        }
    }
    $query12 = "SELECT * FROM `tbl_contractortimesheet` WHERE `cid`= " . $_POST['userid'] . " AND uniqid='$uidt' AND `isdelete`=0 AND `status_id`=1 AND `date`='".$ndtFin."'";
    $timesheetrow12 =  $mysql->selectFreeRun($query12);
    if($resZ = mysqli_fetch_array($timesheetrow12))
    {
        $status = 1;
        foreach($_POST['uniqid'] as $value) 
        {
            $expDtT =array();
            $expDtT = explode("-",$value);
            $expDtT[1] = str_pad($expDtT[1], 2, "0", STR_PAD_LEFT);
            $expDtT[2] = str_pad($expDtT[2], 2, "0", STR_PAD_LEFT);
            $uniqOrId[] = implode("-",$expDtT);
        }
        $whrUniqOr = "(`uniqid` = '".implode("' OR `uniqid` = '",$uniqOrId)."' )";
        $query = "SELECT * FROM `tbl_contractortimesheet` WHERE `cid`= " . $_POST['userid'] . " AND ".$whrUniqOr." AND `isdelete`=0";
        $timesheetrow =  $mysql->selectFreeRun($query);
        while($result = mysqli_fetch_array($timesheetrow)) {
            if($result['value']!='0' && $result['value']!=null && $result['value']!="")
            {
                $statusdata[$i]['uniqid'] = $result['uniqid'];
                $statusdata[$i]['value'] = $result['value'];
                $statusdata[$i]['date'] = $result['date'];
                $statusdata[$i]['rateid'] = $result['rateid'];
                $statusdata[$i]['ischeckval'] = $result['ischeckval'];
                $i++;
            }
        }
    }
    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    $response['paidFlag'] = $paidFlag;
    $response['updateTimesheet'] = 0;
    if(isset($_SESSION['permissioncode'][187]) && $_SESSION['permissioncode'][187]==1)
    {
        $response['updateTimesheet'] = 1;
    }
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceTimesheetData') {
    header("content-Type: application/json");
    $status = 0;
    $uniqOrId = array();
    $mysql = new Mysql();
    $mysql->dbConnect();
    $status = 0;
    $statusdata = array();
    $i =0;
    // $ndtPad = explode("-",$_POST['showDate']);
    // $ndtFin = $ndtPad[0]."-".str_pad($ndtPad[1], 2, "0", STR_PAD_LEFT)."-".str_pad($ndtPad[2], 2, "0", STR_PAD_LEFT);
    // $uidt = $ndtFin."-0-".$_POST['userid'];
    // $paidFlag=0;



    // $checkPaid = "SELECT `id`,`status_id` FROM `tbl_workforcetimesheet` WHERE `wid`=".$_POST['userid']." AND '$ndtFin' BETWEEN `from_date` AND `to_date`";
    // $runPaind = $mysql->selectFreeRun($checkPaid);
    // while($getPaid = mysqli_fetch_array($runPaind))
    // {
    //     if($getPaid['status_id']==4)
    //     {
    //         $paidFlag=1;
    //     }
    // }
    // $query12 = "SELECT * FROM `tbl_workforcetimesheet` WHERE `wid`= " . $_POST['userid'] . " AND uniqid='$uidt' AND `isdelete`=0 AND `status_id`=1 AND `date`='".$ndtFin."'";
    // $timesheetrow12 =  $mysql->selectFreeRun($query12);
    // if(
    //     $resZ = mysqli_fetch_array($timesheetrow12);
    // )
    // {
    //     foreach($_POST['uniqid'] as $value) 
    //     {
    //         $expDtT =array();
    //         $expDtT = explode("-",$value);
    //         $expDtT[1] = str_pad($expDtT[1], 2, "0", STR_PAD_LEFT);
    //         $expDtT[2] = str_pad($expDtT[2], 2, "0", STR_PAD_LEFT);
    //         $uniqOrId[] = implode("-",$expDtT);
    //     }
    //     $whrUniqOr = "(`uniqid` = '".implode("' OR `uniqid` = '",$uniqOrId)."' )";
    //     $query = "SELECT * FROM `tbl_workforcetimesheet` WHERE `wid`= " . $_POST['userid'] . " AND ".$whrUniqOr." AND `isdelete`=0";
    //     $timesheetrow =  $mysql->selectFreeRun($query);
    //     while($result = mysqli_fetch_array($timesheetrow)) {
    //         $status = 1;
    //         if($result['value']!='0' && $result['value']!=null && $result['value']!="")
    //         {
    //             $statusdata[$i]['uniqid'] = $result['uniqid'];
    //             $statusdata[$i]['value'] = $result['value'];
    //             $statusdata[$i]['date'] = $result['date'];
    //             $statusdata[$i]['rateid'] = $result['rateid'];
    //             $statusdata[$i]['ischeckval'] = $result['ischeckval'];
    //             $i++;
    //         }
    //     }
    // }

    foreach($_POST['uniqid'] as $value) 
        {
            $uniqOrId[] = $value;
        }
    $whrUniqOr = "(`uniqid` = '".implode("' OR `uniqid` = '",$uniqOrId)."' )";
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT * FROM `tbl_workforcetimesheet` WHERE `wid`= " . $_POST['userid'] . " AND ".$whrUniqOr." AND `isdelete`=0";
    $timesheetrow =  $mysql->selectFreeRun($query);
    $status = 0;
    $statusdata = array();
    $i =0;
    while($result = mysqli_fetch_array($timesheetrow)) {
        $status = 1;
        $statusdata[$i]['uniqid'] = $result['uniqid'];
        $statusdata[$i]['value'] = $result['value'];
        $statusdata[$i]['date'] = $result['date'];
        $statusdata[$i]['rateid'] = $result['rateid'];
        $statusdata[$i]['ischeckval'] = $result['ischeckval'];
        $i++;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    $response['paidFlag'] = $paidFlag;
    $response['updateTimesheet'] = 1;
    if(isset($_SESSION['permissioncode'][187]) && $_SESSION['permissioncode'][187]==1)
    {
        $response['updateTimesheet'] = 1;
    }
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorFunctionrateData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusquery = "SELECT * FROM `tbl_paymenttype` WHERE `applies`=1 AND `isactive`=0 AND `depot_id` IN (" . $_POST['id'] . ",0)";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        $options = array();
        $options[] = "<option value='0'>--</option>";
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
}  
else if (isset($_POST['action']) && $_POST['action'] == 'ShowContractorLendInfoDetails') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 
    FROM `tbl_contractorlend` WHERE `cid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql->selectFreeRun($rentquery);
    $rowcount = mysqli_num_rows($rentrow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($rentresult = mysqli_fetch_array($rentrow)) {
            $lnqry = $mysql->selectFreeRun("SELECT SUM(amount) as loanamount FROM `tbl_contractorpayment` WHERE `loan_id`=".$rentresult['id']." AND `isdelete`=0");
            $lnresult = mysqli_fetch_array($lnqry);
            $remaining = $rentresult['amount']-$lnresult['loanamount'];
            $data[] = "<tr>";
            $data[] .= "<td> #" . $rentresult['id'] . "</td>";
            $data[] .= "<td> Â£" . $rentresult['amount'] . "</td>";
            $data[] .= "<td>Generic</td>";
            $data[] .= "<td>" . $rentresult['reason'] . "</td>";
            $data[] .= "<td>Pay : Â£".$lnresult['loanamount']."<br>Remaining : Â£".$remaining."</td>";
            $data[] .= "<td>" . $rentresult['date1'] . "</td>";
            $data[] .= "<td><a href='#' class='edit' onclick=\"editlendrow('" . $rentresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Edit'><span><i class='fas fa-clock'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='6'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'ContractorLendUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_contractorlend', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['amount'] = $statusresult['amount'];
        $statusdata['no_of_instal'] = $statusresult['no_of_instal'];
        $statusdata['time_interval'] = $statusresult['time_interval'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['week_of_payment'] = $statusresult['week_of_payment'];
        //changes
        $statusdata['month_of_payment'] = $statusresult['month_of_payment'];
        $statusdata['reason'] = $statusresult['reason'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowContractorPaymentInfoDetails') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorpayment` WHERE `cid`=$id AND `isdelete`= 0 AND `isactive`= 0 ORDER BY `week_no` ASC";

    $paidFlag = array();
    $checkPaid = "SELECT week_no FROM `tbl_contractorinvoice` WHERE `cid`=".$_POST['id']." AND `status_id`=4 AND isdelete=0";
    $runPaind = $mysql->selectFreeRun($checkPaid);
    while($getPaid = mysqli_fetch_array($runPaind))
    {
        $paidFlag[]=$getPaid['week_no'];
    }

    $rentrow =  $mysql->selectFreeRun($rentquery);
    $rowcount = mysqli_num_rows($rentrow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($rentresult = mysqli_fetch_array($rentrow)) {
            $wknoSt = explode(" ",$rentresult['date'])[1];
            $tdcancle = "PERMISSION DENIED";
            $category = '';
            $ct = "SELECT * FROM `tbl_walletcategory` WHERE `id`=".$rentresult['category'];
            $rd = $mysql->selectFreeRun($ct);
            $gd = mysqli_fetch_array($rd);
            $category = $gd['name'];
            $file='';
            if(isset($rentresult['file']))
            {
                //$file='<a target="_blank" href="http://drivaar.com/home/uploads/contractorwalletdocument/'.$rentresult['file'].'" class="adddata"><i class="fas fa-eye fa-lg"></i></a>';
                $file = '<a href="#" onclick=\'modelView("'.$rentresult['id'].'")\'>View</a>';
            }
            if((isset($_SESSION['permissioncode'][189]) && $_SESSION['permissioncode'][189]==1))
            {
                if(!in_array($wknoSt,$paidFlag) || (isset($_SESSION['permissioncode'][190]) && $_SESSION['permissioncode'][190]==1))
                {
                    $tdcancle = "<a href='#' class='delete' onclick=\"deleterow('" . $rentresult['id'] . "','" . $rentresult['loan_id'] . "','" . $id . "')\"data-toggle='tooltip' title='Cancle'><span>Cancel</span></a>";
                }else
                {
                    $tdcancle = "Invoice Paid";
                }
                //$tdcancle .= "   ".$_POST['cntid']."    ".$wknoSt." -- ".implode("-",$paidFlag);
            }
            $data[] = "<tr>
                        <td> #" . $rentresult['loan_id'] . "</td>
                        <td> Â£" . $rentresult['amount'] . "</td>
                        <td>" . $rentresult['reason'] . "</td>
                        <td>".$category."</td>
                        <td>Invoiced</td>
                        <td>paid</td>
                        <td>" . $rentresult['date'] . "</td>
                        <td>".$tdcancle." | ".$file."</td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='7'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowWorkforceLendInfoDetails') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcelend` WHERE `wid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql->selectFreeRun($rentquery);
    $rowcount = mysqli_num_rows($rentrow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($rentresult = mysqli_fetch_array($rentrow)) {
            $data[] = "<tr>";
            $data[] .= "<td> #" . $rentresult['id'] . "</td>";
            $data[] .= "<td> Â£" . $rentresult['amount'] . "</td>";
            $data[] .= "<td>Generic</td>";
            $data[] .= "<td>" . $rentresult['reason'] . "</td>";
            $data[] .= "<td></td>";
            $data[] .= "<td>" . $rentresult['date1'] . "</td>";
            $data[] .= "<td><a href='#' class='edit' onclick=\"editlendrow('" . $rentresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Edit'><span><i class='fas fa-clock'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='6'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowWorkforcePaymentInfoDetails') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $rentquery = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcepayment` WHERE `wid`=$id AND `isdelete`= 0 AND `isactive`= 0";

    $rentrow =  $mysql->selectFreeRun($rentquery);
    $rowcount = mysqli_num_rows($rentrow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($rentresult = mysqli_fetch_array($rentrow)) {
            $data[] = "<tr>";
            $data[] .= "<td> #" . $rentresult['loan_id'] . "</td>";
            $data[] .= "<td> Â£" . $rentresult['amount'] . "</td>";
            $data[] .= "<td>" . $rentresult['reason'] . "</td>";
            $data[] .= "<td>view</td>";
            $data[] .= "<td>Invoiced</td>";
            $data[] .= "<td>paid</td>";
            $data[] .= "<td>" . $rentresult['date'] . "</td>";
            $data[] .= "<td><a href='#' class='delete' onclick=\"deleterow('" . $rentresult['id'] . "','" . $id . "')\"data-toggle='tooltip' title='Cancle'><span>Cancle</span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='7'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceLendUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_workforcelend', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['amount'] = $statusresult['amount'];
        $statusdata['no_of_instal'] = $statusresult['no_of_instal'];
        $statusdata['time_interval'] = $statusresult['time_interval'];
        $statusdata['type'] = $statusresult['type'];
        $statusdata['week_of_payment'] = $statusresult['week_of_payment'];
        $statusdata['reason'] = $statusresult['reason'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ScheduledataGet') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusquery = "SELECT a.*,c.`name` FROM `tbl_assignvehicle` a INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver` WHERE a.`isdelete`=0 AND a.`isactive`=0";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        while ($statusresult = mysqli_fetch_array($strow)) {

            $return_arr[] =  array(
                "startdate" => $statusresult['start_date'],
                "enddate" => $statusresult['end_date'],
                "uniqid" => $statusresult['tduniqid'],
                "name" => $statusresult['name']
            );
        }
    }

    $mysql->dbDisConnect();
    echo json_encode($return_arr);
} else if (isset($_POST['action']) && $_POST['action'] == 'ScheduleDriverData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $vehicleid = $_POST['id'];
    $startdate = $_POST['startdate'];

    $statusquery = "SELECT DISTINCT c.* 
                    FROM `tbl_contractor` c 
                    INNER JOIN `tbl_depot` d ON d.`id`=c.`depot` 
                    INNER JOIN `tbl_vehicles` v ON v.`depot_id`=d.`id` 
                    LEFT JOIN `tbl_assignvehicle` av ON av.`driver`=c.`id` 
                    AND av.`start_date`>=" . $startdate . " AND av.`end_date`<=" . $startdate . "
                    WHERE c.`isdelete`=0 AND c.`iscomplated`=1 AND v.`id`=" . $vehicleid . " AND 
                    ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)=
                    (SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        $options = array();
        $options[] = "<option value='0'>--</option>";
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceScheduleData') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $statusquery = "SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0";
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    while ($row = mysqli_fetch_array($strow)) {
?>
        <tr>
            <td class="border-right bg-grey-lightest">
                <div><?php echo $row['name']; ?></div>
                <small><?php echo $row['role_type']; ?></small>
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
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceScheduleWeekDataShow') {

    header("content-Type: application/json");

    $status = 0;

    $userid = $_POST['userid'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $mysql = new Mysql();
    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectFreeRun("SELECT * FROM `tbl_workforceschedule` WHERE `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0");

    while ($statusresult = mysqli_fetch_array($vehiclestatus)) {
        $return_arr[] =  array(
            "uniqid" => $statusresult['uniqid'],
            "date" => $statusresult['date'],
            "ispaidval" => $statusresult['ispaid'],
            "isweekoff" => $statusresult['isweekoff']
        );
    }

    $mysql->dbDisConnect();
    echo json_encode($return_arr);
} else if (isset($_POST['action']) && $_POST['action'] == 'InvoiceStatusData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $status = $_POST['statusid'];

    $statusquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`=0 AND `isactive`=0 AND `id` NOT IN (" . $status . ")";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        $options = array();
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowActionLog') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $type = $_POST['type'];
    if ($type == 1) {
        $statusquery = "SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr FROM `tbl_contractorstatusinvoice` cs 
                    INNER JOIN `tbl_contractor` c ON c.`id`=cs.`cid`
                    INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid`
                    INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id`
                    INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id`
                    WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=" . $type." AND cs.cid=".$_POST['cid'];
    } else if ($type == 2) {
        $statusquery = "SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr FROM `tbl_contractorstatusinvoice` cs 
                    INNER JOIN `tbl_user` c ON c.`id`=cs.`cid`
                    INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid`
                    INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id`
                    INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id`
                    WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=" . $type." AND cs.cid=".$_POST['cid'];
    } else if ($type == 3) {
        $statusquery = "SELECT DISTINCT cs.*,c.`name`,ci.`week_no`,i.`name` as oldstatus,i.`color`,i.`backgroundcolor`,i1.`name`as newstatus,i1.`color` as newcolor,i1.`backgroundcolor` as newbgclr 
             FROM `tbl_contractorstatusinvoice` cs
             INNER JOIN `tbl_contractor` c ON c.`id`=" . $_POST['cid'] . "
             INNER JOIN `tbl_contractorinvoice` ci ON ci.`id`=cs.`invoiceid` 
             INNER JOIN `tbl_invoicestatus` i ON i.`id`=cs.`oldstatus_id` 
             INNER JOIN `tbl_invoicestatus` i1 ON i1.`id`=cs.`newstatus_id` 
             WHERE cs.`isactive`=0 AND cs.`isdelete`=0 AND cs.`type`=" . $type." AND cs.cid=".$_POST['cid'];
    }
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    while ($result = mysqli_fetch_array($strow)) {
        $date = date("d/m/Y", strtotime($result['status_date']));
    ?>
        <tr>
            <td>
                <small><a href="javascript:void(0);"><?php echo $result['name']; ?></a>
                    Changed status for <strong>Week <?php echo $result['week_no']; ?>
                        <br></strong>
                    from <span class='label label-secondary' style='color:<?php echo $result['color']; ?> ;background-color:<?php echo $result['backgroundcolor']; ?>'><b><?php echo $result['oldstatus']; ?></b></span>

                    to <span class='label label-secondary' style='color:<?php echo $result['newcolor']; ?> ;background-color:<?php echo $result['newbgclr']; ?>'><b><?php echo $result['newstatus']; ?></b></span>
                    <div class="text-muted">
                        <?php echo $date; ?>
                </small></div>
            </td>
        </tr>
    <?php
    }
    $mysql->dbDisconnect();
} else if (isset($_POST['action']) && $_POST['action'] == 'financeassetdelete') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_financeassets', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AssetsUpdateData') {

    header("content-Type: application/json");

    $status = 0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $where = "id=" . $id;
    $makecol = "type_name,name,number,price,description,assign_to";

    $valus[0]['type_name'] = $_POST['type_name'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['number'] = $_POST['number'];
    $valus[0]['price'] = $_POST['price'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['assign_to'] = $_POST['assign_to'];

    $statuupdate = $mysql->update('tbl_financeassets', $valus, $makecol, 'update', $where);

    if ($statuupdate) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    $mysql->dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'financeAssetsUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_financeassets', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['type_name'] = $statusresult['type_name'];
        $statusdata['number'] = $statusresult['number'];
        $statusdata['price'] = $statusresult['price'];
        $statusdata['description'] = $statusresult['description'];
        $statusdata['name'] = $statusresult['name'];
        $statusdata['assign_to'] = $statusresult['assign_to'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowcontractorOnboardingData') {
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT * FROM `tbl_contractoronboarding` WHERE `isdelete`=0 AND `isactive`=0 AND `cid`=" . $_POST['cid'];
    $strow =  $mysql->selectFreeRun($query);
    if ($strow) {
        $status = 1;
        while ($statusresult = mysqli_fetch_array($strow)) {

            $return_arr[] =  array(
                "onboard_id" => $statusresult['onboard_id'],
                "is_onboard" => $statusresult['is_onboard']
            );
        }
    }

    $mysql->dbDisConnect();
    echo json_encode($return_arr);
} else if (isset($_POST['action']) && $_POST['action'] == 'IscheckcontractorOnboardingData') {
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT COUNT(c.`id`) as cntrow FROM `tbl_contractor` c WHERE c.`id`=" . $_POST['cid'] . " AND ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)=(SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))";
    $strow =  $mysql->selectFreeRun($query);
    if ($strow) {
        $status = 1;
        $statusresult = mysqli_fetch_array($strow);
        if ($statusresult['cntrow'] != 0) {
            //if active
            $result = 1;
            $valus[0]['iscomplated'] = 1;
            $valus[0]['isactive'] = 0;
        } else {
            //if not active
            $result = 0;
            $valus[0]['iscomplated'] = 0;
            $valus[0]['isactive'] = 1;
        }
        $makecol = array('isactive', 'iscomplated');
        $where = 'id =' . $_POST['cid'];
        $update = $mysql->update('tbl_contractor', $valus, $makecol, 'update', $where);
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $result;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'custominvoiceUpdateData') {

    header("content-Type: application/json");

    $status = 0;


    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_custominvoicedata', 'id', '=', $id, 'int');

    $statusresult = mysqli_fetch_array($vehiclestatus);

    if ($statusresult > 0) {

        $status = 1;

        $statusdata['name'] = $statusresult['name'];
        $statusdata['description'] = $statusresult['description'];
        $statusdata['quantity'] = $statusresult['quantity'];
        $statusdata['price'] = $statusresult['price'];
        $statusdata['tax'] = $statusresult['tax'];
    }

    $mysql->dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'custominvoiceDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $paymentdata =  $mysql->update('tbl_custominvoicedata', $valus, $usercol, 'delete', $where);

    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorWalletDeleteData') {
    header("content-Type: application/json");
    $status = 0;
    $valus[0]['delete_date'] = date('Y-m-d H:i:s');
    $valus[0]['isdelete'] = 1;
    $usercol = array('delete_date', 'isdelete');
    $where = 'id =' . $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $paymentdata =  $mysql->update('tbl_contractorpayment', $valus, $usercol, 'delete', $where);
    $valus1[0]['update_date'] = date('Y-m-d H:i:s');
    $valus1[0]['is_completed'] = 0;
    $usercol1 = array('is_completed','update_date');
    $where1 = 'id =' . $_POST['loan_id'];
    $paymentdata1 =  $mysql->update('tbl_contractorlend', $valus1, $usercol1, 'delete', $where1);
    $mysql->dbDisConnect();
    if ($paymentdata) {
        $response['status'] = 1;
    }
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'contractorAssignStatusUpdateData') {

    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql->dbConnect();
    $dt = date('Y-m-d', strtotime($_POST['date']));
    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniqid'] =  $dt . '-0-' . $_POST['cid'];
    $valus[0]['rota_uniqid'] = $_POST['uniqid'];
    $valus[0]['status_id'] = $_POST['statusid'];
    $valus[0]['work_status_id'] = $_POST['extWorking'];
    $valus[0]['othDepotId'] = $_POST['othDepFlg'];
    
    if (isset($_POST['route'])) {
        $valus[0]['value'] = $_POST['route'];
        $valus[0]['ischeckval'] = 2;
    } else {
        $valus[0]['ischeckval'] = 'NULL';
    }
    if (isset($_POST['wave'])) {
        $valus[0]['wave'] = $_POST['wave'];
    }
    $invId="";
    if($_POST['statusid']==1)
    {
        $query = "SELECT c.depot,d.invoicetype,ar.name as arname,c.vat_number 
        FROM `tbl_contractor` c 
        LEFT JOIN `tbl_arrears` ar On ar.id=c.arrears
        INNER JOIN `tbl_depot`  d ON d.id=c.depot
        WHERE c.id=".$_POST['cid'];
        $strow =  $mysql->selectFreeRun($query);
        $statusresult = mysqli_fetch_array($strow); 
        if($statusresult['invoicetype']==2)
        {
            $firstdayofmonth = date('Y-m-01', strtotime($_POST['date']));
            // Last day of the month.
            $lastdayofmonth =  date('Y-m-t', strtotime($_POST['date']));
            $dueDate="";
            $onUpdate ="";
            $values[0]['vat']=0;
            if(isset($statusresult['arname']) && $statusresult['arname']!=NULL)
            {
                $arTemp = explode(" ",$statusresult['arname'])[0];
                $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($lastdayofmonth)));
                $onUpdate = ", duedate='$dueDate'";
            }else
            {
                $onUpdate = "";
            }
            $month = date("m", strtotime($firstdayofmonth));
            //$wkn++;
            $wkCode = getAlphaCode($month,2);
            $monthYear = date('Y',strtotime($firstdayofmonth));
            $yrCode = (int)($monthYear) - 2020;
            $tcode='C';
            $depCode="";
            if($monthYear==2021)
            {
                $depCode = getAlphaCode($statusresult['depot'],3);
            }
            $invId = $yrCode.$tcode.$depCode.$wkCode.$_POST['cid'];
            $values = array();
            $values[0]['cid']= $_POST['cid'];
            $values[0]['invoicetype']=$statusresult['invoicetype'];
            $values[0]['invoice_no']= $invId;
            $values[0]['month']= $month;
            if($statusresult['vat_number'])
            {
                $values[0]['vat']=1;
            }
            $values[0]['from_date']= $firstdayofmonth;
            $values[0]['to_date']= $lastdayofmonth;
            $values[0]['monthyear']= $monthYear;
            $values[0]['istype']=1;
            $values[0]['depot_id']= $statusresult['depot'];
            if($dueDate!="")
            {
                $values[0]['duedate']= $dueDate;
            }
            $mysql -> OnduplicateInsert('tbl_monthlyinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
        }
        else
        {
            
            $dueDate="";
            $onUpdate ="";
            $values[0]['vat']= 0;
            $values[0]['invoicetype']=$statusresult['invoicetype'];
            if(isset($statusresult['arname']) && $statusresult['arname']!=NULL)
            {
                $arTemp = explode(" ",$statusresult['arname'])[0];
                $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($_POST['enddate'])));
                $onUpdate = ", duedate='$dueDate'";
            }else
            {
                $onUpdate = "";
            }
            
            $wkn = date("W", strtotime($_POST['startdate'].' +1 day'));
            //$wkn++;
            $wkCode = getAlphaCode($wkn,2);
            $wkYear = date('Y',strtotime($_POST['startdate']));
            $yrCode = (int)($wkYear) - 2020;
            $tcode='C';
            $depCode="";
            if($wkYear==2021)
            {
                $depCode = getAlphaCode($statusresult['depot'],3);
            }
            $invId = $yrCode.$tcode.$depCode.$wkCode.$_POST['cid'];
            $values = array();
            $values[0]['cid']= $_POST['cid'];
            $values[0]['invoice_no']= $invId;
            $values[0]['week_no']= $wkn;
            if($statusresult['vat_number'])
            {
                $values[0]['vat']=1;
            }
            $values[0]['from_date']= $_POST['startdate'];
            $values[0]['to_date']= $_POST['enddate'];
            $values[0]['weekyear']= $wkYear;
            $values[0]['istype']=1;
            $values[0]['depot_id']= $statusresult['depot'];
            if($dueDate!="")
            {
                $values[0]['duedate']= $dueDate;
            }
            $mysql -> OnduplicateInsert('tbl_contractorinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
        }
    }
    $valus[0]['date'] =  $dt;
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];

    $query = "SELECT * FROM `tbl_contractortimesheet` WHERE `isactive`=0 AND `isdelete`=0 AND (`rota_uniqid`='" . $_POST['uniqid'] . "' OR `uniqid`='".$valus[0]['uniqid']."') AND `cid`=" . $_POST['cid'];
    $strow =  $mysql->selectFreeRun($query);
    if ($statusresult = mysqli_fetch_array($strow)) {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('status_id', 'ischeckval', 'update_date','work_status_id','othDepotId');
        if (isset($_POST['wave'])) {
            array_push($usercol, 'wave');
        }
        if (isset($_POST['value'])) {
            array_push($usercol, 'value');
        }
        $where = 'id =' . $statusresult['id'];
        $makeinsert =  $mysql->update('tbl_contractortimesheet', $valus, $usercol, 'update', $where);
        if($_POST['statusid']!=1)
        {
            $valus1[0]['isdelete']=1;
            $valus1[0]['delete_date']=date('Y-m-d H:i:s');
            $usercol1 = array('isdelete','delete_date');
            $where1 = "`cid`=".$_POST['cid']." AND `date`='$dt' AND `rota_uniqid` IS NULL";
            $makeinsert1 =  $mysql->update('tbl_contractortimesheet', $valus1, $usercol1, 'update', $where1);
        }
    } else {
        $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
        $makeinsert = $mysql->insert('tbl_contractortimesheet', $valus);
    }
    
    $usercol12 = array('othDepotId');
    $valus12[0]['othDepotId'] =  $_POST['othDepFlg'];
    $where12 = "`cid` =" . $_POST['cid']." AND `date`='".$dt."'";
    $makeinsert =  $mysql->update('tbl_contractortimesheet', $valus12, $usercol12, 'update', $where12);

    if ($makeinsert) {
        //$dayoff = "SELECT * FROM `tbl_contractortimesheet` WHERE `status_id`=1 AND `cid`=".$_POST['cid']." AND `insert_date` BETWEEN '".$startdate."' AND '".$enddate."' ";
        $status = 1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    // $usercol = array('isassign_status');
    // $where = 'id ='.$_POST['cid'];
    // $paymentdata =  $mysql -> update('tbl_contractor',$valus,$usercol,'update',$where);
    $mysql->dbDisConnect();
    $response['status'] =  $status;
    $response['title'] =  $title;
    $response['message'] =  $message;
    $response['invoiceID'] =  $invId;
    $response['nopaymentdata'] = "<tr>
                      <td colspan='5' class='text-center py-2'>
                        <div class='empty my-3'>
                            <p class='empty-title h4'>No payments added yet</p>
                            <p class='empty-subtitle text-muted'>You can add todays timesheet entries using the list below</p>
                        </div>
                      </td>
                    </tr>";
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'RotaWeekDataShow') {
    header("content-Type: application/json");
    $status = 0;

    $userid = $_POST['userid'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $dpid = $_POST['dpid'];
    if($dpid=='%')
    {
        $dpid = "'%'";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();
    
    $getWids = $mysql->selectFreeRun("SELECT DISTINCT a.`wid` FROM `tbl_workforcedepotassign` a INNER JOIN `tbl_workforcedepotassign` b ON a.depot_id=b.depot_id WHERE b.wid=".$userid);
    $wids = array();
    while($fetchWids = mysqli_fetch_array($getWids)) {
        $wids[] = $fetchWids['wid'];
    }
    $widWhr = " (`tbl_contractortimesheet`.`userid` = ".implode(" OR `tbl_contractortimesheet`.`userid` = ",$wids)." ) ";

    $vehiclestatus =  $mysql->selectFreeRun("SELECT `tbl_contractortimesheet`.*, `tbl_depot`.`name` as dptName FROM `tbl_contractortimesheet` INNER JOIN `tbl_contractor` ON `tbl_contractor`.`id` = `tbl_contractortimesheet`.`cid` LEFT JOIN `tbl_depot` ON `tbl_depot`.`id`=`tbl_contractortimesheet`.`othDepotId` WHERE " . $widWhr . "  AND `tbl_contractortimesheet`.`date`>='$startdate' AND `tbl_contractortimesheet`.`date`<='$enddate' AND `tbl_contractor`.`depot` LIKE $dpid AND `tbl_contractortimesheet`.`isdelete`=0 AND `tbl_contractortimesheet`.`isactive`=0 ORDER BY `tbl_contractortimesheet`.`cid` asc,`tbl_contractortimesheet`.`date` asc");


    // $vehiclestatus =  $mysql->selectFreeRun("SELECT * FROM `tbl_contractortimesheet` WHERE `userid`=" . $userid . "  AND `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0 ORDER BY `cid` asc,`date` asc");

    //echo ;
    //$return_arr=array();
    while ($statusresult = mysqli_fetch_array($vehiclestatus)) {

        $return_arr[] =  array(
            "cid" => $statusresult['cid'],
            "uniqid" => $statusresult['rota_uniqid'],
            "date" => $statusresult['date'],
            "status_id" => $statusresult['status_id'],
            "ext_status_id" => $statusresult['work_status_id'],
            "otherDepot" => $statusresult['othDepotId'],
            "otherDptName" => $statusresult['dptName'],
            "route" => $statusresult['value'],
            "wave" => $statusresult['wave']
        );
    }

    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}else if (isset($_POST['action']) && $_POST['action'] == 'OtherDepotSupportData'){
    header("content-Type: application/json");
    $status = 0;
    $userid = $_POST['userid'];
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $dpid = $_POST['dpid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $getWids = $mysql->selectFreeRun("SELECT DISTINCT a.`wid` FROM `tbl_workforcedepotassign` a INNER JOIN `tbl_workforcedepotassign` b ON a.depot_id=b.depot_id WHERE b.wid=".$userid);
    $wids = array();
    while($fetchWids = mysqli_fetch_array($getWids)) {
        $wids[] = $fetchWids['wid'];
    }
    $widWhr = " (`tbl_contractortimesheet`.`userid` = ".implode(" OR `tbl_contractortimesheet`.`userid` = ",$wids)." ) ";
    $vehiclestatus =  $mysql->selectFreeRun("SELECT `tbl_contractortimesheet`.*, `tbl_depot`.`name` as dptName, `tbl_contractor`.`name` as cname FROM `tbl_contractortimesheet` INNER JOIN `tbl_contractor` ON `tbl_contractor`.`id` = `tbl_contractortimesheet`.`cid` LEFT JOIN `tbl_depot` ON `tbl_depot`.`id`=`tbl_contractor`.`depot` WHERE " . $widWhr . "  AND `tbl_contractortimesheet`.`date`>='$startdate' AND `tbl_contractortimesheet`.`date`<='$enddate' AND `tbl_contractortimesheet`.`status_id`=1 AND `tbl_contractortimesheet`.`work_status_id`=2 AND `tbl_contractortimesheet`.`isdelete`=0 AND `tbl_contractortimesheet`.`isactive`=0 AND `tbl_contractortimesheet`.`othDepotId`=$dpid ORDER BY `tbl_contractortimesheet`.`cid` asc,`tbl_contractortimesheet`.`date` asc");
    while ($statusresult = mysqli_fetch_array($vehiclestatus)) {
        $return_arr[] =  array(
            "cid" => $statusresult['cid'],
            "cname" => $statusresult['cname'],
            "uniqid" => $statusresult['rota_uniqid'],
            "date" => $statusresult['date'],
            "status_id" => $statusresult['status_id'],
            "ext_status_id" => $statusresult['work_status_id'],
            "otherDepot" => $statusresult['othDepotId'],
            "otherDptName" => $statusresult['dptName'],
            "route" => $statusresult['value'],
            "wave" => $statusresult['wave']
        );
    }
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
} else if (isset($_POST['action']) && $_POST['action'] == 'RotaCountSceduling') {
    $userid = $_POST['userid'];
    $depotid = $_POST['depotid'];
    $depStr = "`depotid` =" . $depotid;
    $cdepStr = "`depot` =" . $depotid;
    if ($depotid == '%') {
        $disabled = 'disabled';
        $depStr = "`depotid` LIKE '" . $depotid . "'";
        $cdepStr = "`depot` LIKE '" . $depotid . "'";
    } else {
        $disabled = '';
    }
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    ?>
    <td class="border-bottom-0 w-40"></td>
    <?php
    $getWids = $mysql->selectFreeRun("SELECT DISTINCT a.`wid` FROM `tbl_workforcedepotassign` a INNER JOIN `tbl_workforcedepotassign` b ON a.depot_id=b.depot_id WHERE b.wid=".$userid);
    $wids = array();
    while($fetchWids = mysqli_fetch_array($getWids)) {
        $wids[] = $fetchWids['wid'];
    }
    $widWhr = " (`tbl_contractortimesheet`.`userid` = ".implode(" OR `tbl_contractortimesheet`.`userid` = ",$wids)." ) ";
    // echo "SELECT COUNT(IF(`status_id` = 1,`id`,NULL)) AS working, COUNT(IF( `status_id` = 2,`id`,NULL)) AS dayoff, `date` as rotadate FROM `tbl_contractortimesheet` WHERE `userid`=".$userid." AND `date`>='$startdate' AND `isdelete`=0 AND `isactive`=0 GROUP BY `date` ORDER BY `date` asc limit 7";

    $working = $mysql->selectFreeRun("SELECT COUNT(ct.`id`) AS working, (SELECT COUNT(c1.`id`) FROM `tbl_contractor` c1 WHERE c1.`isdelete`=0 AND c1.".$cdepStr." AND c1.isactive=0) AS dayoff, ct.`date` as rotadate FROM `tbl_contractortimesheet` ct INNER JOIN `tbl_contractor` c ON c.id=ct.cid WHERE ct.`date`>='$startdate' AND ct.`isdelete`=0 AND ct.`isactive`=0 AND c.".$cdepStr." AND c.isactive=0 AND ct.`status_id` = 1 GROUP BY ct.`date` ORDER BY ct.`date` asc limit 7");



    
    $workingCount = mysqli_fetch_array($working);

    // for($i=$startdate;$i<=$enddate;$i++)
    while (strtotime($startdate) <= strtotime($enddate)) {
        $i = $startdate;
        $uniqid = $depotid . '_' . $userid . '_' . $i;
        $needed = 0;
        $uidA = " (`userid` = ".implode(" OR `userid` = ",$wids)." ) ";
        $avaqry = $mysql->selectFreeRun("SELECT SUM(`value`) as needed FROM `tbl_rotacontractor` WHERE (`uniqid`='$uniqid')  OR  (".$uidA." AND " . $depStr . " AND `date`='$i')");
        $avaresult = mysqli_fetch_array($avaqry);
        if ($avaresult > 0) {
            $needed = $avaresult['needed'];
        }
        $diffrent = (int)($workingCount['working']) - (int)($needed);
        if (isset($workingCount['rotadate']) && strtotime($i) == strtotime($workingCount['rotadate'])) {
            $sub = $workingCount['dayoff']-$workingCount['working'];
    ?>
            <td class="rightborder">
                
                <div class="progress mb-1 border border-grey-light bor cursor-pointer" style="height: 13px;" onclick="Timemodal('<?php echo $i; ?>');">
                    <div class="progressbar bg-green-200 text-center" style="width:33.333333333333%;line-height: 1;" data-toggle="tooltip" data-title="Assigned to work">
                        <small><?php echo $workingCount['working']; ?></small>
                    </div>
                    <div class="progressbar bg-red-100 p-0 text-center" style="width:66.666666666667%;line-height: 1;" data-toggle="tooltip" data-title="People off this day">
                        <small><?php echo $sub; ?></small>
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
                                <input type="text" <?php echo $disabled; ?> name="needed" id="<?php echo $uniqid; ?>" class="w-full border-0 text-center" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed; ?>">
                            </div>
                            <div class="w-1/3 text-center border-right">
                                <small><?php echo $workingCount['working']; ?></small>
                            </div>
                            <div class="w-1/3 text-center bg-red-50 font-weight-600 text-red-600">
                                <small><?php echo $diffrent; ?></small>
                            </div>
                        </div>
                        <div>

                            <small class="mt-1 text-red-600">
                                <i class="fa fa-exclamation-triangle"></i> You are understaffed
                            </small>
                        </div>
            </td>
        <?php
            if ($workingCount = mysqli_fetch_array($working)) {
            }
        } else {
        ?>
            <td class="rightborder">
                <div class="progress mb-1 border border-grey-light bor cursor-pointer" style="height: 13px;" onclick="Timemodal('<?php echo $i; ?>');">
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
                                <input type="text" <?php echo $disabled; ?> name="needed" id="<?php echo $uniqid; ?>" class="w-full border-0 text-center" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed; ?>">
                            </div>
                            <div class="w-1/3 text-center border-right">
                                <small>0</small>
                            </div>
                            <div class="w-1/3 text-center bg-red-50 font-weight-600 text-red-600">
                                <small><?php echo $needed; ?></small>
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

    $mysql->dbDisConnect();
} else if (isset($_POST['action']) && $_POST['action'] == 'IndexPaymentTypeData') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $options = array();
    $wek_no = date('W', strtotime($_POST['dt']));
    if(date('l', strtotime($_POST['dt'])) == 'Sunday')
    {
        $wek_no = $wek_no + 1;
    }
    $year_no = explode('-', $_POST['dt'])[0];
    $cid = $_POST['id'];
    $invc_key = base64_encode($cid."#1#".$wek_no."#".$year_no);
    $query = "SELECT * FROM `tbl_contractor` WHERE `id`=" . $cid;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    $dt = date('Y-m-d', strtotime($_POST['dt']));
    $paidFlag=0;
    $checkPaid = "SELECT `id`,`status_id` FROM `tbl_contractorinvoice` WHERE `cid`=$cid AND '$dt' BETWEEN `from_date` AND `to_date`";
    $runPaind = $mysql->selectFreeRun($checkPaid);
    while($getPaid = mysqli_fetch_array($runPaind))
    {
        if($getPaid['status_id']==4)
        {
            $status = 1;
            $paidFlag=1;
        }
    }
    if($paidFlag==0)
    {
        $statusquery = "SELECT * FROM `tbl_paymenttype` WHERE `isdelete`= 0 AND `isactive`= 0 AND  `applies`=1 AND (`depot_id` LIKE '".$cntresult['depot'].",%' OR `depot_id` LIKE '%,".$cntresult['depot']."' OR `depot_id` LIKE '".$cntresult['depot']."' OR `depot_id` LIKE '%,".$cntresult['depot'].",%') AND id NOT IN (SELECT DISTINCT c.rateid FROM `tbl_contractortimesheet` c INNER JOIN `tbl_paymenttype` p ON p.`id`=c.`rateid` AND p.`isdelete`= 0 AND p.`isactive`= 0 AND p.`applies`=1 WHERE c.`cid`=$cid AND c.`date`='$dt' AND c.`rateid`>0 AND c.`isdelete`=0)";
            
        $strow =  $mysql->selectFreeRun($statusquery);
        if ($strow) {
            $status = 1;
            while ($statusresult = mysqli_fetch_array($strow)) {
                $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "(Â£" . $statusresult['amount'] . ")</option>";
            }
        } else {
            $options[] = "<option value='0'>--</option>";
        }
    }

    if ($cntresult['type'] == 1) {
        $type = 'Self-Employed';
    } else {
        $type = 'Limited Company';
    }
    $strow123 =  $mysql->selectFreeRun("SELECT * FROM `tbl_contractortimesheet` WHERE `cid`=".$_POST['id']." AND `rateid`=0 AND `date`='".$_POST['dt']."'");
    $statusresult123 = mysqli_fetch_array($strow123);
    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['route'] = $statusresult123['value'];
    $response['wave'] = $statusresult123['wave'];
    $response['options'] = $options;
    $response['name'] = $cntresult['name'];
    $response['type'] =  $type;
    $response['invc_key'] =  $invc_key;
    $response['paidFlag'] =  $paidFlag;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'InsertRotaPaymentTypeData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['value'] =  $_POST['value'];

    $query = "SELECT * FROM `tbl_contractortimesheet` WHERE `userid` LIKE '" . $_POST['userid'] . "' AND `uniqid`='" . $_POST['uniqid'] . "'";
    $strow =  $mysql->selectFreeRun($query);
    if ($result1 = mysqli_fetch_array($strow)) {
        $valus[0]['update_date'] =  date('Y-m-d H:i:s');
        $usercol = array('value', 'update_date');
        $where = 'id =' . $result1['id'];
        $makeinsert =  $mysql->update('tbl_contractortimesheet', $valus, $usercol, 'update', $where);
    } else {
        $dt = date('Y-m-d', strtotime($_POST['date']));
        $valus[0]['cid'] = $_POST['id'];
        $valus[0]['uniqid'] = $dt . '-' . $_POST['paymenttype'] . '-' . $_POST['id'];
        $valus[0]['rateid'] = $_POST['paymenttype'];
        $valus[0]['date'] = $dt;
        $query1 = "SELECT * FROM `tbl_paymenttype` WHERE `id`=" . $_POST['paymenttype'];
        $strow =  $mysql->selectFreeRun($query1);
        $result = mysqli_fetch_array($strow);
        $valus[0]['ischeckval'] = $result['paymentperstop'];
        $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
        $valus[0]['othDepotId'] = $_POST['othDpt'];
        $makeinsert = $mysql->insert('tbl_contractortimesheet', $valus);
    }

    if ($makeinsert) {
        $status = 1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    $mysql->dbDisConnect();

    $response['status'] =  $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ShowRotaPaymentTypeData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $options = "";
    $paidFlag=0;
    $dt = date('Y-m-d', strtotime($_POST['date']));
    $checkPaid = "SELECT `id`,`status_id` FROM `tbl_contractorinvoice` WHERE `cid`=".$_POST['id']." AND '$dt' BETWEEN `from_date` AND `to_date`";
    $runPaind = $mysql->selectFreeRun($checkPaid);
    while($getPaid = mysqli_fetch_array($runPaind))
    {
        if($getPaid['status_id']==4)
        {
            $paidFlag=1;
        }
    }
    $query = "SELECT c.*,p.`name`,p.`amount`,p.`paymentperstop`,p.`blockoftime` FROM `tbl_contractortimesheet` c
            INNER JOIN `tbl_paymenttype` p ON p.`id`=c.`rateid` AND p.`isdelete`= 0 AND p.`isactive`= 0 AND p.`applies`=1
            WHERE c.`cid`=".$_POST['id']." AND c.`date`='".$dt."' AND c.`rateid`>0 AND c.`isdelete`=0";
            // echo $query;
    $strow =  $mysql->selectFreeRun($query);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        $status = 1;
        // <small><a href="#">Add comment...</a></small>
        while ($result = mysqli_fetch_array($strow)) {

            if($result['value']=='0' || $result['value']=='' || $result['value']==null)
            {
            }else
            {

            $uniqid =  "'" . $dt . '-' . $result['rateid'] . '-' . $_POST['id'] . "'";
            $calamount = ($result['value'] * $result['amount']);
            $bltime="";
            if($result['blockoftime']!=null || strlen($result['blockoftime'])>0)
            {
                $bltime=" - ".$result['blockoftime']. " Hours";
            }
            $options .= '<tr>
                            <td class="border-right">' . $result["name"] . ' '.$bltime.'<br><div></div><div></div></td>
                            <td style="width: 80px;" class="border-right">';
            if ($result["paymentperstop"] == 2) {

                $options .= '<div class="form-group m-0" data-aire-component="group">
                                    <input type="text" class="form-control-plaintext form-control-sm text-center"  value=';
                                    if($result['value']>0)
                                    {
                                        $options .= $result['value'];
                                    }
                                    else
                                    {
                                        $options .= 1;
                                    }
                                    $options .= ($paidFlag==1?'disabled':' "  onfocusout=countValPayment(this.value,' . $uniqid . ',' . $result["amount"] . ');').'>
                                </div>';
            }
            if ($result["paymentperstop"] == 1) {

                $options .= '<div class="form-group m-0" data-aire-component="group">
                                    <input type="text" class="form-control-plaintext form-control-sm text-center" value="1" disabled="">
                                </div>';
            }

            if($paidFlag==1)
            {
                $options .= '</td>
                            <td class="text-right money" style="width: 80px">Â£' . $result["amount"] . '</td>
                            <td class="text-right border-left money" style="width: 100px;" id=' . $uniqid . '>Â£' . $calamount . '</td>
                            <td class=" border-left bg-red-100 text-center" style="padding: 8px 12px; width: 10px;"></td>
                        </tr>';
                    }else
                    {
                        $options .= '</td>
                            <td class="text-right money" style="width: 80px">Â£' . $result["amount"] . '</td>
                            <td class="text-right border-left money" style="width: 100px;" id=' . $uniqid . '>Â£' . $calamount . '</td>
                            <td class=" border-left bg-red-100 text-center" style="padding: 8px 12px; width: 10px;">
                                
                                <a href="#" class="d-block" onclick="delPaymentType('.$result["id"].')"><span><i class="fas fa-trash-alt fa-lg"></i></span></a>

                            </td>
                        </tr>';
                    }
            


            // $options[] = '<tr><td class="pl-3">'.$result["name"].'(Â£'.$result["amount"].')</td><td class="text-right"><button class="btn btn-light btn-sm border">AddPayment</button></td></tr>';
            }
        }
    }
    if($options=="") {
        $status = 0;

        $options = '<tr>
                      <td colspan="5" class="text-center py-2">
                        <div class="empty my-3">
                            <p class="empty-title h4">No payments added yet</p>
                            <p class="empty-subtitle text-muted">You can add todays timesheet entries using the list below</p>
                        </div>
                      </td>
                    </tr>';
    }

    $mysql->dbDisConnect();
    $response['status'] =  $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'delPaymentType'){
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $valus[0]['isdelete'] = 1;
    $valus[0]['delete_date'] =  date('Y-m-d H:i:s');
    $usercol = array('delete_date', 'isdelete');
    $where = 'id =' . $_POST['paymid'];
    $makeinsert =  $mysql->update('tbl_contractortimesheet', $valus, $usercol, 'update', $where);
    $mysql->dbDisConnect();
    $response['status'] =  1;
    echo json_encode($response);
}else if (isset($_POST['action']) && $_POST['action'] == 'ShowTimeOfDriverRotaData') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $userid = $_POST['userid'];
    $depotid = $_POST['depotid'];
    $date = $_POST['date'];

    $statusquery = "SELECT c.*,r.`name` as cntname FROM `tbl_contractortimesheet` c INNER JOIN `tbl_contractor` r ON r.`id`=c.`cid` AND r.`depot` LIKE '" . $depotid . "' WHERE c.`date`='" . $date . "' AND c.`userid`=" . $userid . " AND c.`wave`>0";
    $strow =  $mysql->selectFreeRun($statusquery);
    if (mysqli_num_rows($strow) > 0) {
        while ($statusresult = mysqli_fetch_array($strow)) {
            if ($statusresult['wave'] != NULL) {
            ?>
                <tr>
                    <?php
                    $tmt = explode(":", $statusresult['wave']);
                    $col1 = 0;
                    if ($tmt[0] >= 6) {
                        $col1 = $tmt[0] - 6;
                    }
                    $col2 = 9;
                    $col3 = 16 - 9 - $col1;
                    if ($tmt[0] >= 12) {
                        $col2 = 16 - $col1;
                        $col3 = 0;
                    }
                    ?>
                    <td colspan='<?php echo $col1; ?>' class='default'></td>
                    <td colspan='<?php echo $col2; ?>' style='text-align: left;background-color: #e3fcec!important;'>
                        <i class='far fa-clock'></i>
                        <strong><?php echo $statusresult['wave']; ?></strong>-<?php echo $statusresult['cntname']; ?> - 9h
                    </td>
                    <?php if ($col3 != 0) { ?>

                        <td colspan='<?php echo $col3; ?>' class='default'></td>
                    <?php
                    }
                    ?>
                </tr>
        <?php
            }
        }
    } else {
        ?>
        <tr>
            <td colspan="14" class="text-center py-4">There are no waves set up for today. ðŸ˜ž</td>
        </tr>
        <?php
    }

    $mysql->dbDisConnect();
} else if (isset($_POST['action']) && $_POST['action'] == 'assignWorkforceUser') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $options = array();

    $statusquery = "SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0 AND `id` NOT IN (1) AND `userid`=" . $_POST['id'];
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        while ($statusresult = mysqli_fetch_array($strow)) {
            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    } else {
        $options[] = "<option value='0'>--</option>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'InsertUserAssignData') {

    header("content-Type: application/json");
    $status = 0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['wid'] = $_POST['userid'];
    $valus[0]['assignuserid'] = $_POST['assignid'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusinsert = $mysql->insert('tbl_assignuser', $valus);
    if ($statusinsert) {
        $status = 1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    $mysql->dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'UserAssignData') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];

    $statusquery = "SELECT *,DATE_FORMAT(a.`Insert_date`,'%D %M%, %Y') as date1,u.`name`,a.`id` as assignid FROM `tbl_assignuser` a  
                    INNER JOIN `tbl_user` u ON u.`id`=a.`assignuserid`
                    WHERE a.`isdelete`=0  AND  a.`isactive`=0 AND a.`wid`=" . $id;
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($ownerresult = mysqli_fetch_array($strow)) {

            $data[] = "<tr><td>" . $ownerresult['name'] . "</td><td>" . $ownerresult['date1'] . "</td><td><a href='#' class='delete' onclick=\"deleteuserrow('" . $ownerresult['assignid'] . "','" . $id . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'DeleteUserAssignData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id=' . $_POST['id'];

    $mysql = new Mysql();
    $mysql->dbConnect();
    $paymentdata =  $mysql->update('tbl_assignuser', $valus, $usercol, 'delete', $where);
    $mysql->dbDisConnect();

    if ($paymentdata) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'ContractorExpiringDocumentData') {
    $depotid = $_POST['did'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $query = "SELECT DISTINCT d.`name` as depotname,c.`name`,c.`id` as id 
             FROM `tbl_depot` d 
             INNER JOIN `tbl_contractor` c ON c.`depot`=d.`id`
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE d.`id` LIKE '" . $depotid . "' AND d.`isdelete`= 0 AND d.`isactive` = 0  ORDER BY c.`id` DESC";
    $typerow =  $mysql->selectFreeRun($query);
    // $data = array();
    $dt = "";
    $month = date("m");
    $nextmonth = $month + 1;
    $prevmonth = $month - 1;
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $i = 0;
        $subquery = "SELECT * FROM `tbl_contractordocument` WHERE `contractor_id` =" . $typeresult['id'] . " AND MONTH(`expiredate`) IN ($month,$nextmonth,$prevmonth) ORDER BY `expiredate` DESC";
        $subrow = $mysql->selectFreeRun($subquery);
        while ($result = mysqli_fetch_array($subrow)) {
            $date = strtotime($result['expiredate']);
            $expiredate =  date('d M, Y', $date);
            $warning = "";
            if (date('Y-m-d H:i:s') < $result['expiredate']) {
                $warning = "<i class='fa fa-exclamation-triangle' style='color: red;'></i> ";
            }

            if ($i == 0) {
                $_SESSION['cid'] = $typeresult['id'];
                $dt .= "<tr><td rowspan='@#@#'>" . $typeresult['depotname'] . "</td>
                <td rowspan='@#@#'><span  style='color: blue;' onclick='loadpage(" . $typeresult['id'] . ")'>" . $typeresult['name'] . "</span></td>
                <td>" . $result['file'] . "</td><td>" . $warning . $expiredate . "</td><td><button type='button' class='btn btn-info'><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            } else {
                $dt .= "<tr><td>" . $result['file'] . "</td><td>" . $warning . $expiredate . "</td><td><button type='button' class='btn btn-info'><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            }
            $i++;
        }
        $dt = str_replace('@#@#', $i, $dt);
    }
    echo $dt;
} 
else if (isset($_POST['action']) && $_POST['action'] == 'MonthlyContractorExpiringDocumentData') {
    $depotid = $_POST['did'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $query = "SELECT DISTINCT d.`name` as depotname,c.`name`,c.`email`,c.`id` as id 
             FROM `tbl_depot` d 
             INNER JOIN `tbl_contractor` c ON c.`depot`=d.`id` AND c.`isdelete`= 0 AND c.`isactive` = 0 
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE d.`id` LIKE '" . $depotid . "' AND d.`isdelete`= 0 AND d.`isactive` = 0  ORDER BY c.`id` DESC";
    $typerow =  $mysql->selectFreeRun($query);
    // $data = array();
    $dt = "";
    $month = date("m");
    $year = date("Y");
    $nextmonth = $month + 1;
    $prevmonth = $month - 1;
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $i = 0;
        $subquery = "SELECT c.*,v.`isexpiry_date` FROM `tbl_contractordocument`  c  INNER JOIN `tbl_vehicledocumenttype` v ON v.`id`=c.`type` WHERE c.`contractor_id` =" . $typeresult['id'] . " AND MONTH(c.`expiredate`) IN ($month) AND YEAR(c.`expiredate`) IN ($year) ORDER BY c.`expiredate` DESC";
        $subrow = $mysql->selectFreeRun($subquery);
        while ($result = mysqli_fetch_array($subrow)) {
            $date = strtotime($result['expiredate']);
            $expiredate =  date('d M, Y', $date);
            $warning = "";
            if (date('Y-m-d H:i:s') < $result['expiredate']) {
                $warning = "<i class='fa fa-exclamation-triangle' style='color: red;'></i> ";
            }

            if ($i == 0) {
                $_SESSION['cid'] = $typeresult['id'];
                $dt .= "<tr><td rowspan='@#@#'><span  style='color: blue;' onclick='loadpage(" . $typeresult['id'] . ")'>" . $typeresult['name'] . "</span></td><td rowspan='@#@#'>" . $typeresult['depotname'] . "</td><td rowspan='@#@#'>" . $typeresult['email'] . "</td>
                <td>" . $result['file'] . "</td><td>" . $warning . $expiredate . "</td><td><button type='button' class='btn btn-info' onclick=\"addstatus('".$typeresult['id']."','" . $result['id'] . "','".$result['typename']."',".$result['isexpiry_date'].")\"><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            } else {
                $dt .= "<tr><td>" . $result['file'] . "</td><td>" . $warning . $expiredate . "</td><td><button type='button' class='btn btn-info' onclick=\"addstatus('".$typeresult['id']."','" . $result['id'] . "','".$result['typename']."',".$result['isexpiry_date'].")\"><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
            }
            $i++;
        }
        $dt = str_replace('@#@#', $i, $dt);
    }
    echo $dt;
} 
else if (isset($_POST['action']) && $_POST['action'] == 'VehicleSetDriver') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $id = $_POST['id'];
    $vid = $_POST['vid'];
    $offencesdate = $_POST['offencesdate'];

    $statusquery = "SELECT a.*,c.`name` as contractorname,c.`id` FROM `tbl_vehiclerental_agreement` a INNER JOIN tbl_contractor c ON c.`id`=a.`driver_id` WHERE a.`vehicle_id`=$vid AND '$offencesdate' BETWEEN a.`pickup_date` AND a.`return_date`";
    $strow =  $mysql->selectFreeRun($statusquery);

    if ($strow) {
        $status = 1;
        $options = array();
        while ($fetch = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $fetch['id'] . ">" . $fetch['contractorname'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'tbloffencesImageloaddata') {

    header("content-Type: application/json");
    $status = 0;
    $offences_id = $_POST['offences_id'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql = "SELECT * FROM `tbl_offencesImage` WHERE `offences_id`=$offences_id";
    $fire =  $mysql->selectFreeRun($sql);

    $rowcount = mysqli_num_rows($fire);
    if ($rowcount > 0) {
        $status = 1;
        $data = array();
        while ($ownerresult = mysqli_fetch_array($fire)) {
            $fileshow = $ownerresult['file'];
            $data[] = "<tr><td>" . $ownerresult['file'] . "</td><td><a target='_blank' href='uploads/offencesform-image/$fileshow' class='adddata'><i class='fas fa-eye fa-lg'></i></a></td></tr>";
        }
    } else {
        $status = 0;
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'vehicle_details') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $vid = $_POST['vid'];
    $sql = "SELECT vpp.* FROM `tbl_vehicles` v INNER JOIN `tbl_vehicletype` vt ON v.type_id=vt.id INNER JOIN `tbl_vehiclePerDayPrice` vpp ON vpp.veh_type_id=vt.id WHERE v.id=$vid AND vpp.isdelete=0";
    $exprow =  $mysql->selectFreeRun($sql);
    $status=0;
    $dt = "";
    while($result1 = mysqli_fetch_array($exprow))
    {
        $status = 1;
        $dt .= "<option value='".$result1['perDayPrice']."'>".$result1['term_size']."</option>";
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['dt'] = $dt;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature') {
    header("content-Type: application/json");
    $status = 0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql->dbConnect();
    $folderPath = "uploads/rental-agreement-signature/driver-signature/";
    $image_parts = explode(";base64,", $_POST['signed']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.' . $image_type;
    file_put_contents($file, $image_base64);
    $driver = $_POST['last_insert_id'];
    $valus[0]['driver_signature'] = $file;
    $where = "id=" . $driver;
    $key = "driver_signature,id";

    $makeinsert = $mysql->update('tbl_vehiclerental_agreement', $valus, $key, 'update', $where);

    if ($makeinsert) {
        $status = 1;
        $_SESSION['status_id'] = $status;
        $title = 'Insert';
        $message = 'Signature has been update successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Signature can not been update.';
    }

    $name = 'updateinsert';

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature_validation') {
    header("content-Type: application/json");
    $status = 0;

    $mysql = new Mysql();
    $mysql->dbConnect();
    $status_valid =  $_POST['status_id'];
    $usercol = array('driver_signature', 'id');
    $where = 'id =' . $_POST['status_id'];

    $sql =  "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=$status_valid";
    $exprow =  $mysql->selectFreeRun($sql);
    $result1 = mysqli_fetch_array($exprow);
    $driver_signature = $result1['driver_signature'];
    if ($driver_signature != "") {
        $status = 1;
        $title = 'Insert';
        $message = 'Signature1 has been Inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Signature1 can not been Insereted.';
    }

    $name = 'Insert';
    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['signature1'] = $driver_signature;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature2_validation') {
    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $usercol = array('ledger_signature', 'id');
    $status_valid =  $_POST['status_id'];

    $sql =  "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=$status_valid";
    $exprow =  $mysql->selectFreeRun($sql);
    $result1 = mysqli_fetch_array($exprow);
    $driver_signature = $result1['ledger_signature'];

    if ($driver_signature != "") {
        $status = 1;
        $title = 'Insert';
        $message = 'Signature2 has been Inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Signature2 can not been Insereted.';
    }

    $name = 'Insert';
    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['ledger_signature_name'] = $driver_signature;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'AttendanceDataLoadTable') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $depot_id = $_POST['depot_id'];
    $inputdate = $_POST['inputdate'];

    $statusquery = "SELECT DISTINCT a.*,c.`name`,v.`registration_number`,v.`id` as vid FROM `tbl_contractortimesheet` a
                    INNER JOIN `tbl_contractor` c ON c.`id`=a.`cid` AND c.`depot` LIKE ('" . $depot_id . "')
                    INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "')  AND w.`isactive`=0 AND w.`release_date` IS NULL
                    LEFT JOIN `tbl_vehiclerental_agreement` av ON av.`driver_id`=c.`id` AND a.`date` 
                    BETWEEN av.`pickup_date` AND av.`return_date`
                    LEFT JOIN `tbl_vehicles` v ON v.`id`=av.`vehicle_id`
                    WHERE a.`status_id`=1 AND a.`date`='" . $inputdate . "' ";
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    if ($rowcount > 0) {
        while ($ownerresult = mysqli_fetch_array($strow)) {
            $vquery = "SELECT * FROM `tbl_vehicleinspection` WHERE `isdelete`=0 AND `vehicle_id`=" . $ownerresult['vid'] . " AND `odometerInsert_date` LIKE '" . $_POST['inputdate'] . "%' AND `odometer` IS NOT NULL";
            $row =  $mysql->selectFreeRun($vquery);
            $result = mysqli_fetch_array($row);

            if ($result['id'] > 0) {
                $inspection = '';
            } else {
                $inspection = '<div class="text-red-600"><strong>Not Inspected</strong></div>';
            }

            $duration =  $ownerresult['duration'];
            $hh = explode(":", $duration);
            $mm = $hh[1];
            $ss = $hh[2];


            if ($ownerresult['route']) {
                $route = $ownerresult['route'];
            } else {
                $route = '--';
            }
        ?>
            <tr>
                <td class="default">
                    <i class="fa fa-exclamation-triangle" style="color: red;"> </i> <?php echo $ownerresult['name'] ?>
                </td>
                <td class="border-left">
                    <?php
                    if ($ownerresult['duration'] > 0) {
                        echo $hh[0] . 'h ' . $mm . 'm ' . $ss . 's ';
                    }
                    ?>
                </td>
                <td><?php echo $route; ?></td>
                <td>Working</td>
                <td class="yellow"><?php echo $ownerresult['wave'] ?></td>
                <td>
                    <div class="border border-dashed rounded px-2 py-1 d-flex justify-content-between" style="border-color: #999 !important;">
                        <div>
                            <?php
                            if ($ownerresult['start']) {
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
                            <a href="#" onclick="DetailModel(<?php echo $ownerresult['id']; ?>)"><i class="fas fa-plus"> Set Time</i></a>
                        </div>
                    </div>
                </td>
                <td class="border-left">
                    <span style='color: blue;' onclick='loadpage(<?php echo $ownerresult['vid'] ?>)'><?php echo $ownerresult['registration_number'] ?><span>
                </td>
                <td><?php echo $inspection; ?></td>
            </tr>
        <?php
        }
    } else {
        $data[] = "<tr><td colspan='3'>Data Not Found..!</td></tr>";
    }

    $mysql->dbDisConnect();
} else if (isset($_POST['action']) && $_POST['action'] == 'ContractorAddWorkingTime') {

    header("content-Type: application/json");
    $status = 0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['start'] = $_POST['start'];
    if ($_POST['end']) {
        $valus[0]['end'] = $_POST['end'];
        $duration = GetAttendanceTime($_POST['start'], $_POST['end']);
    } else {
        $valus[0]['end'] = '00:00';
        $duration = '00:00:00';
    }
    $valus[0]['duration'] = $duration;
    $valus[0]['attendance_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    $makecol = array('start', 'end', 'duration', 'attendance_date');
    $where = 'id =' . $_POST['id'];

    $statusinsert = $mysql->update('tbl_contractortimesheet', $valus, $makecol, 'update', $where);
    if ($statusinsert) {
        $status = 1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    $mysql->dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'RentalAgreementisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehiclerental_agreement', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'accidentDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $user =  $mysql->update('tbl_accident', $valus, $usercol, 'delete', $where);
    $mysql->dbDisConnect();

    if ($user) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'vehicleoffencesisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicleoffences', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'accidentDeleteData') {

    header("content-Type: application/json");

    $status = 0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date', 'isdelete');

    $where = 'id =' . $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $user =  $mysql->update('tbl_accident', $valus, $usercol, 'delete', $where);
    error_log($user, 0);

    $mysql->dbDisConnect();

    if ($user) {
        $response['status'] = 1;
    }

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'getdashboarddata') {
    header("content-Type: application/json");
    $depotid = $_POST['depot_id'];
    $userid = $_POST['userid'];

    $mysql = new Mysql();
    $mysql->dbConnect();

    ////total 1st row....
    $query = "SELECT COUNT(DISTINCT c.`id`) FROM `tbl_contractor` c
              INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
              WHERE c.`isdelete`=0 AND c.`depot` IN (w.depot_id) AND c.`depot` LIKE '" . $depotid . "'";
    $row =  $mysql->selectFreeRun($query);
    $cnt = mysqli_fetch_array($row);

    $query1 = "SELECT COUNT(DISTINCT u.`id`) FROM `tbl_user` u
               INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
               WHERE u.`isdelete`=0 AND u.`depot` LIKE '" . $depotid . "'";
    $row2 =  $mysql->selectFreeRun($query1);
    //user id not defines..
    $workforce = mysqli_fetch_array($row2);
    $query2 = "SELECT COUNT(DISTINCT v.`id`) FROM `tbl_vehicles` v
               INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
               WHERE v.`isdelete`=0 AND v.`depot_id` LIKE '" . $depotid . "'";
    $row3 =  $mysql->selectFreeRun($query2);
    $vehicle = mysqli_fetch_array($row3);


    ////2nd row....
    $feed = "SELECT COUNT(DISTINCT a.`id`) AS fb FROM `tbl_contactorfeedback` a 
            INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
            WHERE  a.`isdelete`=0 AND DATE(a.`insert_date`) = CURRENT_DATE() AND b.depot LIKE '" . $depotid . "'";
    $fbrow =  $mysql->selectFreeRun($feed);
    $resultfb = mysqli_fetch_array($fbrow);

    $feed1 = "SELECT COUNT(DISTINCT a.`id`) AS fb1 FROM `tbl_contactorfeedback` a 
              INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
              INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
              WHERE  a.`isdelete`=0 AND MONTH(a.`insert_date`) = MONTH(CURRENT_DATE()) AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.depot LIKE '" . $depotid . "'";
    $fbrow1 =  $mysql->selectFreeRun($feed1);
    $resultfb1 = mysqli_fetch_array($fbrow1);

    $feed2 = "SELECT COUNT(DISTINCT a.`id`) AS fb2 FROM `tbl_contactorfeedback` a 
             INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
             WHERE  a.`isdelete`=0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.depot LIKE '" . $depotid . "'";
    $fbrow2 =  $mysql->selectFreeRun($feed2);
    $resultfb2 = mysqli_fetch_array($fbrow2);

    $statusquery = "SELECT COUNT(DISTINCT a.`id`) as today FROM `tbl_vehiclerental_agreement` a 
                   INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                   INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                   WHERE a.`isdelete`=0  AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date` AND v.`status` != 5 AND v.`depot_id` LIKE '" . $depotid . "'";
    $strow =  $mysql->selectFreeRun($statusquery);
    $result = mysqli_fetch_array($strow);

    $statusquery1 = "SELECT COUNT(DISTINCT a.`id`) as monthly FROM `tbl_vehiclerental_agreement` a 
                    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                    WHERE a.`isdelete`=0  AND MONTH(CURRENT_DATE()) IN (MONTH(a.`pickup_date`), MONTH(a.`return_date`)) AND v.`status` != 5 AND v.`depot_id` LIKE '" . $depotid . "'";
    $strow1 =  $mysql->selectFreeRun($statusquery1);
    $result1 = mysqli_fetch_array($strow1);

    $statusquery2 = "SELECT COUNT(DISTINCT a.`id`) as yearly FROM `tbl_vehiclerental_agreement` a 
                    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                    WHERE a.`isdelete`=0  AND YEAR(CURRENT_DATE()) IN (YEAR(a.`pickup_date`), YEAR(a.`return_date`)) AND v.`status` != 5 AND v.`depot_id` LIKE '" . $depotid . "'";
    $strow2 =  $mysql->selectFreeRun($statusquery2);
    $result2 = mysqli_fetch_array($strow2);

    $assigncnt = " SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                   INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                   INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
                   WHERE  a.`isdelete`=0 AND a.`status_id`=1 AND a.`date`=CURRENT_DATE() AND b.`depot` LIKE '" . $depotid . "'";
    $row =  $mysql->selectFreeRun($assigncnt);
    $assigncnt_res = mysqli_fetch_array($row);

    $assigncnt1 = "SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                  INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                  INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
                  WHERE a.`isdelete`=0 AND a.`status_id`=1 AND  MONTH(a.`date`)=MONTH(CURRENT_DATE()) AND YEAR(a.`date`)=YEAR(CURRENT_DATE()) AND b.depot LIKE '" . $depotid . "'";
    $row1 =  $mysql->selectFreeRun($assigncnt1);
    $assigncnt_res1 = mysqli_fetch_array($row1);

    $assigncnt2 = "SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                   INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                   INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
                   WHERE  a.`isdelete`=0 AND a.`status_id`=1 AND  YEAR(a.`date`)=YEAR(CURRENT_DATE()) AND b.depot LIKE '" . $depotid . "'";
    $row2 =  $mysql->selectFreeRun($assigncnt2);
    $assigncnt_res2 = mysqli_fetch_array($row2);

    $mysql->dbDisConnect();
    $response['cnt'] = $cnt;
    $response['vehicle'] = $vehicle;
    $response['workforce'] = $workforce;
    $response['resultfb'] = $resultfb;
    $response['resultfb1'] = $resultfb1;
    $response['resultfb2'] = $resultfb2;
    $response['result'] = $result;
    $response['result1'] = $result1;
    $response['result2'] = $result2;
    $response['assigncnt'] = $assigncnt_res;
    $response['assigncnt1'] = $assigncnt_res1;
    $response['assigncnt2'] = $assigncnt_res2;

    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'getechartdata') {
    $pendingarray = [];
    $approvedarray = [];
    $disputesarray = [];
    $response = [];
    $table = $_POST['table'];
    $depot = $_POST['depot'];

    header("content-Type: application/json");
    $userid = $_POST['userid'];
    if($userid==1)
    {
        $uid='%';
    }
    else
    {
        $uid=$userid;
    }
    $typeid = $_POST['typeid'];

    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT IFNULL(COUNT(a.`id`),0) as cnt 
    FROM tbl_contractorinvoice a 
    INNER JOIN " . $table . " b ON b.`id` = a.`cid` 
    WHERE a.`isdelete` = 0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) 
    AND b.`userid` LIKE '" . $uid . "' AND a.istype =" . $typeid . " AND b.depot LIKE '" . $depot . "'";
    //echo $query;
    for ($i = 1; $i <= 12; $i++) {
        $query1 = $query . " AND a.status_id = 1 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(" . $i . ", '%m'))";
        $row =  $mysql->selectFreeRun($query1);
        $pending = mysqli_fetch_array($row);
        //print_r($pending);
        //echo $query1;
        array_push($pendingarray, $pending['cnt']);

        $query2 = $query . " AND a.status_id = 2 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(" . $i . ", '%m'))";
        $row2 =  $mysql->selectFreeRun($query2);
        $approved = mysqli_fetch_array($row2);
        array_push($approvedarray, $approved['cnt']);

        $query3 = $query . " AND a.status_id = 9 AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(" . $i . ", '%m'))";
        $row3 =  $mysql->selectFreeRun($query3);
        $disputes = mysqli_fetch_array($row3);

        array_push($disputesarray, $disputes['cnt']);
    }
    $mysql->dbDisConnect();
    $response['pending'] = $pendingarray;
    $response['approved'] = $approvedarray;
    $response['disputes'] = $disputesarray;
    echo json_encode($response);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'getmonthwisecontractorinvoicedata') {
    header("content-Type: application/json");
   
    $pendingarray = [];
    $approvedarray = [];
    $disputesarray = [];
    $response = [];

    $typeid = $_POST['typeid'];
    $table = $_POST['table'];
    $depot = $_POST['depot'];
    $year = $_POST['year'];
    $getmonth = $_POST['month'];
    $userid = $_POST['userid'];
    if($userid==1)
    {
        $uid='%';
    }
    else
    {
        $uid=$userid;
    }
    if($getmonth=='%')
    {
        $month='';
    }
    else
    {
        $month=" AND MONTH(a.`insert_date`) = MONTH(STR_TO_DATE(" . $getmonth . ", '%m'))";
    }
    
    $mysql = new Mysql();
    $mysql->dbConnect();

    $query = "SELECT IFNULL(COUNT(a.`id`),0) as cnt 
    FROM tbl_contractorinvoice a 
    INNER JOIN " . $table . " b ON b.`id` = a.`cid` 
    WHERE a.`isdelete` = 0 AND YEAR(a.`insert_date`) = ".$year . $month ." 
    AND b.`userid` LIKE '" . $uid . "' AND a.istype =" . $typeid . " AND b.depot LIKE '" . $depot . "'";

    $query1 = $query . " AND a.status_id = 1 ";
    $row =  $mysql->selectFreeRun($query1);
    $pending = mysqli_fetch_array($row);

    $query2 = $query . " AND a.status_id = 2 ";
    $row2 =  $mysql->selectFreeRun($query2);
    $approved = mysqli_fetch_array($row2);

    $query3 = $query . " AND a.status_id = 9 ";
    $row3 =  $mysql->selectFreeRun($query3);
    $disputes = mysqli_fetch_array($row3);

    $query4 = $query . " AND a.status_id = 3";
    $row4 =  $mysql->selectFreeRun($query4);
    $hold = mysqli_fetch_array($row4);

    $query5 = $query . " AND a.status_id = 4 ";
    $row5 =  $mysql->selectFreeRun($query5);
    $pay = mysqli_fetch_array($row5);

    $query6 = $query . " AND a.status_id = 8";
    $row3 =  $mysql->selectFreeRun($query6);
    $send = mysqli_fetch_array($row3);

    // $dataPoints = array( 
    //     array("y"=>$hold['cnt'],"label"=>"Hold"),
    //     array("y"=>$send['cnt'],"label"=>"Send"),
    //     array("y"=>$pay['cnt'],"label"=>"Paid"),
    //     array("y"=>$approved['cnt'],"label"=>"Approved"),
    //     array("y"=>$pending['cnt'],"label"=>"Pending"),
    //     array("y"=>$disputes['cnt'],"label"=>"Disputed")
    // );
    
    $mysql->dbDisConnect();
    $response['hold'] = $hold['cnt'];
    $response['send'] = $send['cnt'];
    $response['paid'] = $pay['cnt'];
    $response['approved'] = $approved['cnt'];
    $response['pending'] = $pending['cnt'];
    $response['disputes'] = $disputes['cnt'];
    echo json_encode($response);
    // $data1 = json_encode($dataPoints, JSON_NUMERIC_CHECK);
    // echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
} 
else if (isset($_POST['action']) && $_POST['action'] == 'getpaymentdata') {
    $cntarray = [];
    $wfarray = [];
    $response = [];
    header("content-Type: application/json");
    $userid = $_POST['userid'];
    if($userid==1)
    {
        $uid = '%';
    }
    else
    {
        $uid = $userid;
    }
    $depot = $_POST['depot'];
    if ($_SESSION['userid'] == 1) {
        $qry = '';
    } else {
        $qry = ' AND u.`depot` IN (wo.`depot_id`)';
    }

    $wfquery = "SELECT IFNULL(SUM(w.`amount`),0) FROM `tbl_workforcepayment` w 
                INNER JOIN `tbl_user` u ON u.`id`=w.`wid`
                INNER JOIN tbl_workforcedepotassign wo ON wo.`wid` LIKE ('" . $uid . "') AND wo.`isdelete`=0 AND wo.`isactive`=0 AND wo.`release_date` IS NULL
                WHERE  w.`isdelete`=0  " . $qry . " AND YEAR(w.`insert_date`) = YEAR(CURRENT_DATE()) AND u.depot LIKE '" . $depot . "'";

    $cntquery = "SELECT IFNULL(SUM(a.`amount`),0) FROM `tbl_contractorpayment` a  
                INNER JOIN `tbl_contractor` b ON b.`id`=a.`cid` 
                INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $uid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
                WHERE  a.`isdelete`=0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE()) AND b.depot LIKE '" . $depot . "'";

    $mysql = new Mysql();
    $mysql->dbConnect();
    for ($i = 1; $i <= 12; $i++) {
        $query1 = $wfquery . " AND MONTH(STR_TO_DATE(" . $i . ", '%m')) = MONTH(w.insert_date) ";
        $row =  $mysql->selectFreeRun($query1);
        $wf = mysqli_fetch_array($row, MYSQLI_NUM);
        array_push($wfarray, $wf[0]);

        $query2 = $cntquery . " AND MONTH(STR_TO_DATE(" . $i . ", '%m')) = MONTH(a.insert_date) ";
        $row2 =  $mysql->selectFreeRun($query2);
        $cnt = mysqli_fetch_array($row2, MYSQLI_NUM);
        array_push($cntarray, $cnt[0]);
    }
    $mysql->dbDisConnect();
    $response['wf'] = $wfarray;
    $response['cnt'] = $cntarray;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'accidenttableisactive') {

    header("content-Type: application/json");
    $status = 0;
    $id = $_POST['id'];
    if ($_POST['status'] == 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    $valus[0]['isactive'] = $status;
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $mysql = new Mysql();
    $mysql->dbConnect();

    if ($id > 0) {
        $isactivecol = array('isactive', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_accident', $valus, $isactivecol, 'update', $where);
        $status = 1;
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'loadbillingdetails') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $result = $mysql->selectAll('tbl_company');
    $response = mysqli_fetch_array($result);
    $mysql->dbDisConnect();
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'updatebillingdetails') {
    header('Content-Type: application/json');
    $value = array();
    $mysql = new Mysql();
    $mysql->dbConnect();

    $value[0]['street'] = $_POST["street"];
    $value[0]['postcode'] = $_POST['postcode'];
    $value[0]['city'] = $_POST['city'];
    $value[0]['country'] = $_POST['country'];
    $value[0]['country1'] = $_POST['country1'];
    $value[0]['vat_per'] = $_POST['vat_per'];

    $value[0]['update_date'] = date('Y-m-d H:i:s');

    $colname = array('street', 'postcode', 'city', 'country', 'country1', 'vat_per');
    $whr = 'id = 1';
    $empQuery = $mysql->update("tbl_company", $value, $colname, 'update', $whr);

    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';
    $mysql->dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'updateorgdetails') {

    $mysql = new Mysql();
    $mysql->dbConnect();
    header('Content-Type: application/json');
    $value = array();
    $value[0]['name'] = $_POST["name"];
    $value[0]['registration_no'] = $_POST['registration_no'];
    $value[0]['vat'] = $_POST['vat'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');

    $colname = array('name', 'registration_no', 'vat', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql->update("tbl_company", $value, $colname, 'update', $whr);

    $mysql->dbDisConnect();
    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
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
else if (isset($_POST['action']) && $_POST['action'] == 'ShowrotatableData') 
{
    $depotid = $_POST['depot_id'];
    $userid = $_SESSION['userid'];
    $scrId = $_POST['scrID'];
    $scrIdQry = "";
    $serachKey = "'%".$_POST['serachKey']."%'";
    if(!strlen($_POST['serachKey'])>0)
    {
        $serachKey = "'%'";
        $scrIdQry = "AND c.id > $scrId";
    }
    if($userid==1)
    {
      $uid='%';
    }
    else
    {
      $uid=$userid;
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $statusquery =  "SELECT DISTINCT c.*
            FROM `tbl_contractor` c 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE c.`name` LIKE $serachKey AND c.`depot` LIKE '".$depotid."' AND c.`isdelete`=0 AND c.`isactive`=0 AND c.`iscomplated`=1 AND c.`depot` IN (w.depot_id) $scrIdQry order by c.id asc limit 15";  
    // $statusquery = "SELECT * FROM `tbl_contractor` WHERE `userid`=" . $_SESSION['userid'] . "  AND `isdelete`=0 AND `iscomplated`=1 AND `depot` LIKE '" . $depotid . "'";
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    $dts="";
    $lastID = 0;
    if($rowcount<=0)
    {
        $lastID = $scrId;
    }
    while ($row = mysqli_fetch_array($strow)) {
        $dts .= "<tr id='scheduledata'>
            <td class='border-right bg-grey-lightest'>
                <div>
                    <strong class='text-blue-600 whitespace-no-wrap' onclick='loadpage(".$row['id'].")'>".$row['name']."</strong>
                </div>
                <small class='d-block text-grey-darker'>
                    <i class='fa fa-exclamation-triangle delete daysCount-itag' id='daysCount-itag-".$row['id']."'></i>
                    <strong class='text-grey-darke daysCount' id='daysCount-".$row['id']."'></strong> working days
                </small>
            </td>";
            for ($k = 1; $k <= 7; $k++) {
                // $merge = 'td_' . $_SESSION['userid'] . '_' . $row['id'] . '_' . $k;
                $merge = 'td_0_' . $row['id'] . '_' . $k;
                $td = 'td-' . $k;

                $dts .= "<td class='p-0 position-relative border-right $td' id='$merge' onclick='DetailModel(this,".$row['id'].");'>

                    <div class='w-full h-full'>
                        <div class='w-full h-full d-flex align-items-center justify-content-center text-grey-light hover:text-grey-darkest hover:bg-pink-200' style='font-size: 24px; font-weight: 300;height: 62px;'>+</div>
                    </div>

                </td>";
            }

        $dts .= "</tr>";
        $lastID = $row['id'];
    }
    $mysql->dbDisconnect();

 
    $response['status'] =  1;
    $response['dts'] =  $dts;
    $response['scrlId'] =  $lastID;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'RotaContractorSchedule') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniqid'] = $_POST['uniqid'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['depotid'] = $_POST['depotid'];
    $valus[0]['value'] = $_POST['value'];

    $query = "SELECT * FROM `tbl_rotacontractor` WHERE `isdelete`=0 AND `uniqid`='" . $_POST['uniqid'] . "' AND `userid`=" . $_POST['userid'] . " AND `depotid`=" . $_POST['depotid'] . " AND `date`='" . $_POST['date'] . "'";
    $strow =  $mysql->selectFreeRun($query);
    if ($statusresult = mysqli_fetch_array($strow)) {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('value', 'update_date');
        $where = 'id =' . $statusresult['id'];
        $makeinsert =  $mysql->update('tbl_rotacontractor', $valus, $usercol, 'update', $where);
    } else {
        $valus[0]['insert_date'] =  date('Y-m-d H:i:s');
        $makeinsert = $mysql->insert('tbl_rotacontractor', $valus);
    }

    if ($makeinsert) {
        $status = 1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
    } else {
        $status = 0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
    $name = 'Insert';
    $mysql->dbDisConnect();
    $response['status'] =  $status;
    $response['title'] =  $title;
    $response['message'] =  $message;
    echo json_encode($response);
}
//bhumika changes
else if (isset($_POST['action']) && $_POST['action'] == 'updategenerale_settingdetails') {
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql->dbConnect();

    $id = $_POST['id'];
    $value[0]['name'] = $_POST["name"];
    $value[0]['contact'] = $_POST['phone'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');

    $colname = array('name', 'contact', 'update_date');
    $whr = 'id =' . $id;
    $empQuery = $mysql->update("tbl_user", $value, $colname, 'update', $whr);
    $mysql->dbDisConnect();

    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'loadgenerale_settingdetails') {
    $id = $_POST['id'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $result = $mysql->selectWhere('tbl_user', 'id', '=', $id, 'int');
    $response = mysqli_fetch_array($result);
    $mysql->dbDisConnect();
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'updatesecuritydetails') {
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_POST['id'];
    $password = $_POST['password'];
    $value[0]['password'] = $_POST["new_password"];
    $value[0]['confirm_password'] = $_POST['confirm_password'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');
    $colname = array('password', 'confirm_password', 'update_date');
    $whr = 'id =' . $id . ' AND password="' . $password . '"';
    $empQuery = $mysql->update("tbl_user", $value, $colname, 'update', $whr);
    $mysql->dbDisConnect();
    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'getusageechartdata') {
    $cntarray = [];
    $wfarray = [];
    $vehiclesarray = [];
    $response = [];

    header("content-Type: application/json");
    $userid = $_POST['userid'];
    if ($_SESSION['userid']== 1) {
        $qry = '';
    } else {
        $qry = ' AND u.`depot` IN (w.`depot_id`)';
    }

    $mysql = new Mysql();
    $mysql->dbConnect();
   
    for ($i = 1; $i <= 12; $i++) {
        $query1 = "SELECT IFNULL(COUNT(DISTINCT c.`id`),0) AS COUNT FROM `tbl_contractor` c
                   INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."')  AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                   WHERE c.`isdelete`=0  AND c.`depot` IN (w.depot_id) AND YEAR(c.`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(c.`insert_date`) = MONTH(STR_TO_DATE(".$i.", '%m'))";
        $row =  $mysql->selectFreeRun($query1);
        $cnt = mysqli_fetch_array($row);
        array_push($cntarray, $cnt['COUNT']);

        $query2 = "SELECT IFNULL(COUNT(DISTINCT u.`id`),0) AS COUNT FROM `tbl_user` u
                   INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                   WHERE u.`isdelete`=0 AND u.id NOT IN (1) ".$qry." AND YEAR(u.`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(u.`insert_date`) = MONTH(STR_TO_DATE(".$i.", '%m'))";
        $row2 =  $mysql->selectFreeRun($query2);
        $wf = mysqli_fetch_array($row2);
        array_push($wfarray, $wf['COUNT']);

        $query3 = "SELECT IFNULL(COUNT(DISTINCT v.`id`),0) AS COUNT FROM `tbl_vehicles` v
                   INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                   WHERE v.`isdelete`=0 AND YEAR(v.`insert_date`) = YEAR(CURRENT_DATE()) AND MONTH(v.`insert_date`) = MONTH(STR_TO_DATE(".$i.", '%m'))";
        $row3 =  $mysql->selectFreeRun($query3);
        $vehicles = mysqli_fetch_array($row3);

        array_push($vehiclesarray, $vehicles['COUNT']);
    }
    $mysql->dbDisConnect();
    $response['Contractors'] = $cntarray;
    $response['Vehicles'] = $vehiclesarray;
    $response['Workforce'] = $wfarray;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'updatefinance_settingsdetails') {
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql->dbConnect();


    $value[0]['period'] = $_POST["period"];
    $value[0]['update_date'] = date('Y-m-d H:i:s');

    $colname = array('period', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql->update("tbl_disputepeiod", $value, $colname, 'update', $whr);
    $mysql->dbDisConnect();

    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'loadfinance_settingsdetails') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $result = $mysql->selectAll('tbl_disputepeiod');
    $response = mysqli_fetch_array($result);
    $mysql->dbDisConnect();

    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'updatetermdetails') {
    header('Content-Type: application/json');
    $mysql = new Mysql();
    $mysql->dbConnect();
    $value[0]['term'] = $_POST['area'];
    $value[0]['update_date'] = date('Y-m-d H:i:s');

    $colname = array('term', 'update_date');
    $whr = 'id = 1';
    $empQuery = $mysql->update("tbl_vehicle_term", $value, $colname, 'update', $whr);
    $mysql->dbDisConnect();

    if ($empQuery) {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    } else {
        $status = 0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'loadtermdetails') {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $result = $mysql->selectAll('tbl_vehicle_term');
    $response = mysqli_fetch_array($result);
    $mysql->dbDisConnect();

    echo json_encode($response['term']);
} else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceNewTaskUpdateData') {

    header("content-Type: application/json");

    $status = 0;

    $id = $_POST['id'];

    $mysql = new Mysql();

    $mysql->dbConnect();

    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $valus[0]['status'] = '1';
    $col = array('status', 'update_date');
    $where = 'id =' . $id;
    $vehiclestatus =  $mysql->update('tbl_workforcetask', $valus, $col, 'update', $where);

    $status = 1;
    $mysql->dbDisConnect();

    $response['status'] = $status;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'OrgDocumentUpdateData') {

    header("content-Type: application/json");
    $status = 0;
    $id = 1;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $vehiclestatus =  $mysql->selectWhere('tbl_orgdocument', 'id', '=', $id, 'int');
    $statusresult = mysqli_fetch_array($vehiclestatus);
    if ($statusresult > 0) {
        $status = 1;
        $statusdata['id'] = $statusresult['id'];
        $statusdata['name'] = $statusresult['name'];
        $statusdata['file'] = $statusresult['file'];
        $statusdata['file1'] = $statusresult['file1'];
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['statusdata'] = $statusdata;
    echo json_encode($response);
} else if (isset($_POST['action']) && $_POST['action'] == 'ModalRotaWeeklyData') {
    $userid = $_POST['userid'];
    $startdate = $_POST['stdate'];
    $enddate = $_POST['endate'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                  INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                  WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid`=" . $userid;
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    while ($row = mysqli_fetch_array($strow)) {
        $startdate = $_POST['stdate'];
        $enddate = $_POST['endate'];
        $needed = 0;
    ?>
        <tr id="">
            <td class="border-right bg-grey-lightest">
                <div><span class="whitespace-no-wrap">
                        <?php echo $row['name']; ?>
                    </span></div>
            </td>
            <?php

            $working = $mysql->selectFreeRun("SELECT COUNT(IF(a.`status_id` = 1,a.`id`,NULL)) AS working, COUNT(IF( a.`status_id` = 2,a.`id`,NULL)) AS dayoff, a.`date` as rotadate FROM `tbl_contractortimesheet` a 
                INNER JOIN `tbl_contractor` c ON c.`id`=a.`cid`
                WHERE a.`userid`=" . $userid . " AND a.`date`>='$startdate' AND a.`isdelete`=0 AND a.`isactive`=0 AND c.depot=" . $row['id'] . " GROUP BY a.`date` ORDER BY a.`date` asc limit 7");
            $workingCount = mysqli_fetch_array($working);
            // for($i=$startdate;$i<=$enddate;$i++)
            while (strtotime($startdate) <= strtotime($enddate)) {
                $i = $startdate;
                $uniqid = $row['id'] . '_' . $userid . '_' . $i;
                $avaqry = $mysql->selectFreeRun("SELECT SUM(value) as needed FROM `tbl_rotacontractor` WHERE (`uniqid`='$uniqid')  OR  (`userid`=" . $userid . " AND `depotid` LIKE '" . $row['id'] . "' AND `date`='$i')");
                $avaresult = mysqli_fetch_array($avaqry);
                if ($avaresult > 0) {
                    $needed = $avaresult['needed'];
                }
                $diffrent = (int)($workingCount['working']) - (int)($needed);
                if (isset($workingCount['rotadate']) && strtotime($i) == strtotime($workingCount['rotadate'])) {
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
                                <input type="text" name="needed" id="<?php echo $uniqid; ?>" class="w-full border-0 text-center font-weight-600" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed; ?>">
                            </div>
                            <div class="w-1/3 text-center">
                                <small><?php echo $workingCount['working']; ?></small>
                            </div>
                            <div class="w-1/3 text-center  bg-red-50 text-red-600 ">
                                <small class=" font-weight-600"><?php echo $diffrent; ?></small>
                            </div>
                        </div>

                        <div>
                        </div>
                    </td>
                <?php
                    if ($workingCount = mysqli_fetch_array($working)) {
                    }
                } else {
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
                                <input type="text" name="needed" id="<?php echo $uniqid; ?>" class="w-full border-0 text-center font-weight-600" style="font-size:11px" onfocusout="Needed(this);" value="<?php echo $needed; ?>">
                            </div>
                            <div class="w-1/3 text-center">
                                <small>0</small>
                            </div>
                            <div class="w-1/3 text-center  bg-red-50 text-red-600 ">
                                <small class=" font-weight-600"><?php echo $needed; ?></small>
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

        $mysql->dbDisConnect();
    } else if (isset($_POST['action']) && $_POST['action'] == 'inspectionissueisactive') {

        header("content-Type: application/json");
        $status = 0;
        $id = $_POST['id'];
        if ($_POST['status'] == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $valus[0]['isactive'] = $status;
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql->dbConnect();

        if ($id > 0) {
            $isactivecol = array('isactive', 'update_date');
            $where = 'id =' . $id;
            $isactiveupdate = $mysql->update('tbl_vehicleinspection', $valus, $isactivecol, 'update', $where);
            $status = 1;
        }

        $mysql->dbDisConnect();
        $response['status'] = $status;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'Companydata') {

        header("content-Type: application/json");

        $status = 0;

        $companyid = $_POST['companyid'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $user =  $mysql->selectWhere('tbl_contractorcompany', 'id', '=', $companyid, 'int');

        $userresult = mysqli_fetch_array($user);

        if ($userresult > 0) {

            $status = 1;

            $userdata['company_reg'] = $userresult['company_reg'];
        }

        $mysql->dbDisConnect();

        $response['status'] = $status;

        $response['company_reg'] = $userdata['company_reg'];

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'checkusercontact') {
        header("content-Type: application/json");
        $status = 0;
        $msg = '';
        $mysql = new Mysql();
        $mysql->dbConnect();
        $contact = $_POST['phone'];
        $email = $_POST['email'];
        $id = $_POST['id'];
        $submitaction = $_POST['submitaction'];
        $query = "SELECT id FROM `tbl_user` WHERE `contact` = '" . $contact . "'  AND `isdelete`= 0";
        $rows = $mysql->selectFreeRun($query);
        $fetch_rows = mysqli_num_rows($rows);
        $query1 = "SELECT id FROM `tbl_user` WHERE  `email` = '" . $email . "' AND `isdelete`= 0";
        $rows1 = $mysql->selectFreeRun($query1);
        $fetch_rows1 = mysqli_num_rows($rows1);

        if ($submitaction == 'insert') {
            if ($fetch_rows > 0) {
                $status = 1;
                $msg = 'Contact number has been already registered.';
            } else if ($fetch_rows1 > 0) {
                $status = 1;
                $msg = 'Email has been already registered.';
            } else {
                $status = 0;
            }
        }
        if ($submitaction == 'update') {
            $query2 = $query . " AND id != " . $id;
            //echo $query2;
            $rows2 = $mysql->selectFreeRun($query2);
            $fetch_rows2 = mysqli_num_rows($rows2);

            $query3 = $query1 . " AND id != " . $id;
            $rows3 = $mysql->selectFreeRun($query3);
            $fetch_rows3 = mysqli_num_rows($rows3);

            if ($fetch_rows2 > 0) {
                $status = 1;
                $msg = 'Contact number has been already registered.';
            } else if ($fetch_rows3 > 0) {
                $status = 1;
                $msg = 'Email has been already registered.';
            } else {
                $status = 0;
            }
        }

        $response['status'] = $status;
        $response['msg'] = $msg;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'ShowPreviousWeekDayOff') {
        header("content-Type: application/json");

        $laststartdate = date('Y-m-d', strtotime($_POST['startdate'] . " -1 days"));
        $weekno = weekOfYear($laststartdate);
        $weekyear = date('Y', strtotime($laststartdate));

        $week_array = getStartAndEndDate($weekno, $weekyear);
        $startdate = $week_array['week_start'];
        $enddate = $week_array['week_end'];
        $userid = $_POST['userid'];
        $cid = $_POST['cid'];

        $mysql = new Mysql();
        $mysql->dbConnect();
        $return_arr['dtl'] = array();
        $return_arr['startdate'] = $startdate;
        $return_arr['enddate'] = $enddate;
        $vehiclestatus =  $mysql->selectFreeRun("SELECT * FROM `tbl_contractortimesheet` WHERE `userid`=" . $userid . "  AND `date`>='$startdate' AND `date`<='$enddate' AND `isdelete`=0 AND `isactive`=0 AND `cid` LIKE '" . $cid . "' ORDER BY `date` desc");
        //$return_arr=array();
        while ($statusresult = mysqli_fetch_array($vehiclestatus)) {

            $return_arr['dtl'][] =  array(
                "cid" => $statusresult['cid'],
                "uniqid" => $statusresult['rota_uniqid'],
                "date" => $statusresult['date'],
                "status_id" => $statusresult['status_id'],
                "route" => $statusresult['value'],
                "wave" => $statusresult['wave']
            );
        }

        $mysql->dbDisConnect();

        echo json_encode($return_arr);
        // echo $laststartdate.'-----------'.$weekno.'------'.$fromDate.'---------'.$toDate;
    } else if (isset($_POST['action']) && $_POST['action'] == 'Auditisactive') {

        header("content-Type: application/json");

        $status = 0;
        $id = $_POST['id'];
        if ($_POST['status'] == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $valus[0]['isactive'] = $status;
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql->dbConnect();

        if ($id > 0) {
            $isactivecol = array('isactive', 'update_date');
            $where = 'id =' . $id;
            $isactiveupdate = $mysql->update('tbl_audit', $valus, $isactivecol, 'update', $where);
            $status = 1;
        }

        $mysql->dbDisConnect();
        $response['status'] = $isactiveupdate;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'locationdata') {

        header("content-Type: application/json");
        $option = array();

        $deptid = $_POST['deptid'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $query = "SELECT id, dept_id, name FROM `tbl_cost_location` WHERE `isactive` = 0 AND `isdelete`=0 AND `dept_id`=" . $deptid;
        $strow =  $mysql->selectFreeRun($query);
        if (mysqli_num_rows($strow) > 0) {
            while ($statusresult = mysqli_fetch_array($strow)) {
                $option[] = '<option value="' . $statusresult['id'] . '" data-dept="' . $statusresult['dept_id'] . '">' . $statusresult['name'] . '</option>';
            }
        } else {
            $option[] = '<option value="">Location not available</option>';
        }

        $mysql->dbDisconnect();
        $response['option'] = $option;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'servicedata') {

        header("content-Type: application/json");
        $option = array();

        $locid = $_POST['locid'];
        $deptid = $_POST['deptid'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $query = "SELECT id, name FROM `tbl_cost_service` WHERE `isactive` = 0 AND `isdelete`=0 AND `loc_id`=" . $locid . " AND `dept_id`= " . $deptid;

        $strow =  $mysql->selectFreeRun($query);
        if (mysqli_num_rows($strow) > 0) {

            while ($statusresult = mysqli_fetch_array($strow)) {
                $option[] = '<option value="' . $statusresult['id'] . '">' . $statusresult['name'] . '</option>';
            }
        } else {
            $option[] = '<option value="">Service not available</option>';
        }

        $mysql->dbDisconnect();
        $response['option'] = $option;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'Costcenterdata') {

        header("content-Type: application/json");

        $status = 0;

        $valus[0]['delete_date'] = date('Y-m-d H:i:s');

        $valus[0]['isdelete'] = 1;

        $usercol = array('delete_date', 'isdelete');

        $where = 'id =' . $_POST['id'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $paymentdata =  $mysql->update('tbl_costcenter', $valus, $usercol, 'delete', $where);

        $mysql->dbDisConnect();

        if ($paymentdata) {
            $response['status'] = 1;
        }

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'Costcenteredit') {

        header("content-Type: application/json");

        $status = 0;

        $id = $_POST['id'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $vehiclestatus =  $mysql->selectWhere('tbl_costcenter', 'id', '=', $id, 'int');

        $statusresult = mysqli_fetch_array($vehiclestatus);

        if ($statusresult > 0) {

            $status = 1;
            $statusdata['type'] = $statusresult['type'];
            $statusdata['dept_id'] = $statusresult['dept_id'];
            $statusdata['loc_id'] = $statusresult['loc_id'];
            $statusdata['ser_id'] = $statusresult['ser_id'];
            $statusdata['payment_type'] = $statusresult['payment_type'];
            $statusdata['amount'] = $statusresult['amount'];
        }

        $mysql->dbDisConnect();

        $response['status'] = $status;

        $response['statusdata'] = $statusdata;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'GetBulkDriver') {
        $depotid = $_POST['depotid'];
        $mysql = new Mysql();
        $mysql->dbConnect();
        $statusquery = "SELECT * FROM `tbl_contractor` WHERE `isdelete`=0 AND `iscomplated`=1 AND `depot` =" . $depotid;
        $strow =  $mysql->selectFreeRun($statusquery);
        $rowcount = mysqli_num_rows($strow);
        while ($row = mysqli_fetch_array($strow)) {
            ?>
            <div>
                <label class="custom-control custom-checkbox m-b-0">
                    <input type="checkbox" class="custom-control-input subchecked" id="<?php echo $row['id'] ?>" name="driverid[]" value="<?php echo $row['id']; ?>">
                    <span class="custom-control-label">
                        <h6 class="m-0"><?php echo $row['name']; ?></h6>
                    </span>
                </label>
            </div>
    <?php
        }
        $mysql->dbDisconnect();
    } else if (isset($_POST['action']) && $_POST['action'] == 'getvehiclesechartdata') 
    {
        $insarray = [];
        $notinsarray = [];
        $dates = [];
        $response = [];

        header("content-Type: application/json");
        $userid = $_POST['userid'];
        $mysql = new Mysql();
        $mysql->dbConnect();

        for ($i = 0; $i <= 30; $i++) {
            $d =  Date('Y-m-d', strtotime('-' . $i . ' days'));
            $dates[] = $d;
            $query1 = "SELECT DISTINCT a.`vehicle_id` AS count  FROM tbl_vehicleinspection a 
                       INNER JOIN tbl_vehicles b ON a.`vehicle_id` = b.`id` 
                       INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                       WHERE odometerInsert_date LIKE '%" . $d . "%' AND a.isdelete = 0  AND b.isdelete = 0 AND a.answer_type = 1 GROUP BY a.vehicle_id ";
            $row =  $mysql->selectFreeRun($query1);
            $ins = mysqli_num_rows($row);
            array_push($insarray, $ins);

            $query2 = "SELECT DISTINCT a.`vehicle_id` AS count FROM tbl_vehicleinspection a 
                        INNER JOIN tbl_vehicles b  ON a.`vehicle_id` = b.`id` 
                        INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                        WHERE odometerInsert_date LIKE '%" . $d . "%' AND a.isdelete = 0  AND b.isdelete = 0 and b.userid LIKE '" . $userid . "' AND a.answer_type = 0 GROUP BY a.vehicle_id ";
            $row1 =  $mysql->selectFreeRun($query2);
            $notins = mysqli_num_rows($row1);
            // print_r($notins);
            // echo $query2;
            array_push($notinsarray, $notins);
        }

        $mysql->dbDisConnect();
        $response['inspected'] = $insarray;
        $response['notinspected'] = $notinsarray;
        $response['dates'] = $dates;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'gettopinspectionchartdata') {
        $top5ins = [];
        $top5failins = [];
        $top5ins1 = [];
        $top5failins1 = [];
        $insname = [];
        $inscnt = [];
        $finsname = [];
        $finscnt = [];
        $response = [];

        header("content-Type: application/json");
        $mysql = new Mysql();
        $mysql->dbConnect();

        $query = "SELECT DISTINCT b.`name` AS name,a.`question_id`,SUM(a.`answer_type`) AS count 
                 FROM `tbl_vehicleinspection` a 
                 INNER JOIN `tbl_vehicles` v ON v.`id`= a.`vehicle_id`
                 INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                 INNER JOIN tbl_vehiclechecklist b ON a.`question_id`= b.`id` WHERE a.`isdelete`=0
                 AND a.`question_id` != 'null' AND b.`isdelete` = 0 GROUP BY question_id ";

        $query1 = $query . "ORDER BY count DESC LIMIT 5";
        $row =  $mysql->selectFreeRun($query1);

        while ($ins = mysqli_fetch_array($row)) {
            array_push($insname, $ins['name']);
            array_push($inscnt, $ins['count']);
        }

        $query2 = $query . " ORDER BY count ASC LIMIT 5";
        $row1 =  $mysql->selectFreeRun($query2);
        while ($failins = mysqli_fetch_array($row1)) {
            array_push($finsname, $failins['name']);
            array_push($finscnt, $failins['count']);
        }

        $mysql->dbDisConnect();
        $response['insname'] = $insname;
        $response['inscnt'] = $inscnt;
        $response['finsname'] = $finsname;
        $response['finscnt'] = $finscnt;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'vehicleDamagedata') {
        $data = [];
        $data1 = [];
        $response = [];
        $part = [];
        $type = [];
        $state = [];
        $div = [];
        $imgdata = '';

        header("content-Type: application/json");
        $userid = $_POST['userid'];
        $id = $_POST['id'];
        $mysql = new Mysql();
        $mysql->dbConnect();

        // $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        // `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  AND `vehicle_id` = " . $id . " GROUP BY `damage_part`";
        $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        `isdelete` = 0  AND `vehicle_id` = " . $id . " GROUP BY `damage_part`";
        $row =  $mysql->selectFreeRun($query);
        while ($data = mysqli_fetch_array($row)) {
            // $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  
            // AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0   
            AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $row1 =  $mysql->selectFreeRun($query1);
            while ($divdata = mysqli_fetch_array($row1)) {
                $imgdata = $imgdata . "<div class='col-4'>
                <img src='" . $webroot . "uploads/vehiclesdamageimages/" . $divdata['image'] . "' id='" . $divdata['id'] . "' width='150'  name='imgsrc' class='img-thumbnail img-fluid'/>
                </div>";
            }

            if ($data['state'] == 0) {
                $div[] = "<div class='col-md-12'>
                  <div class='card datacard'>
                      <div class='card-header dataheader d-flex justify-content-between align-items-center'>
                          <div><h6 class='mb-2 cardmb2 '>" . $data['damage_part'] . "</h6></div>
                        <div><a href='#' class='delete' onclick=\"deleterow('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt'></i></span></a>
                         </div> 
                      </div>    
                     <div class='card-body'>
                        <div class='d-flex justify-content-between align-items-center'>
                            <div>
                                " . $data['description'] . "
                            </div>
                        </div>
                        <div class='row'>
                            " . $imgdata . "
                        </div>
                    </div>
                  </div>
                </div>";

                $statuscls = '#de2727';
                $statusname = 'EXISTENT';
            } else {
                $statuscls = '#199509';
                $statusname = 'FIXED';
            }
            $imgdata = '';
            $data1[] = "<tr>
        <td></td>
        <td>" . $data['damage_part'] . "</td>
        <td>" . $data['date'] . "</td>
        <td>
            <span class='label label-secondary' style='background-color:" . $statuscls . ";'>" . $statusname . "</span>
        </td>
        <td></td>
        <td>
        <a href='#' class='mark' onclick=\"mark('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Mark as Fixed'><span><i class='fas fa-check-circle'></i></span></a>&nbsp;
        <a href='#' class='edit' onclick=\"view('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='View'><span><i class='fas fa-eye'></i></span></a>&nbsp;
        <a href='#' class='delete' onclick=\"deleterow('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a>
        </td></tr>";
            $part[] = $data['damage_part'];
            $type[] = $data['damage_type'];
            $state[] = $data['state'];
        }

        $mysql->dbDisConnect();
        $response['data'] = $data1;
        $response['part'] = $part;
        $response['type'] = $type;
        $response['state'] = $state;
        $response['div'] = $div;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'vehicleDamageDeleteData') {

        header("content-Type: application/json");

        $status = 0;

        $valus[0]['delete_date'] = date('Y-m-d H:i:s');

        $valus[0]['isdelete'] = 1;

        $usercol = array('delete_date', 'isdelete');

        $where = 'id =' . $_POST['id'];

        $mysql = new Mysql();

        $mysql->dbConnect();

        $user =  $mysql->update('tbl_vehicledamage_img', $valus, $usercol, 'delete', $where);

        $mysql->dbDisConnect();

        if ($user) {
            $response['status'] = 1;
        }

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'vehicleDamagestate') {
        header("content-Type: application/json");
        $status = 0;
        $id = $_POST['id'];

        $valus[0]['state'] = 1;
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql->dbConnect();

        $isactivecol = array('state', 'update_date');
        $where = 'id =' . $id;
        $isactiveupdate = $mysql->update('tbl_vehicledamage_img', $valus, $isactivecol, 'update', $where);
        $status = 1;

        $mysql->dbDisConnect();
        $response['status'] = $status;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'vehicleDamagedata') {
        $data = [];
        $data1 = [];
        $response = [];
        $part = [];
        $type = [];
        $state = [];
        $div = [];
        $imgdata = '';
        $condition = ['Broken', 'Dented', 'Chipped', 'Scratched', 'Missing', 'Worn'];

        header("content-Type: application/json");

        $userid = $_POST['userid'];
        $id = $_POST['id'];

        $mysql = new Mysql();
        $mysql->dbConnect();

        // $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        // `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  AND `vehicle_id` = " . $id . " GROUP BY `damage_part`";
        $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        `isdelete` = 0  AND `vehicle_id` = " . $id . " GROUP BY `damage_part`";
        // $query2 = "SELECT * FROM `tbl_vehicledamage_img`";
        // $row3 =  $mysql -> selectFreeRun($query2);
        // while($data4 = mysqli_fetch_array($row3))
        // {
        //     print_r($data4);
        // }
        $row =  $mysql->selectFreeRun($query);
        while ($data = mysqli_fetch_array($row)) {
            // $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  
            // AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0   
            AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $row1 =  $mysql->selectFreeRun($query1);
            while ($divdata = mysqli_fetch_array($row1)) {
                $imgdata = $imgdata . "<div class='col-4'>
                <img src='" . $webroot . "uploads/vehiclesdamageimages/" . $divdata['image'] . "' id='" . $divdata['id'] . "' width='150'  name='imgsrc' class='img-thumbnail img-fluid'/>
            </div>";
            }

            if ($data['state'] == 0) {
                //echo $imgdata;
                $div[] = "<div class='col-md-12'>
                  <div class='card datacard'>
                      <div class='card-header dataheader d-flex justify-content-between align-items-center'>
                          <div><h6 class='mb-2 cardmb2 '>" . $data['damage_part'] . "</h6></div>
                        <div><a href='#' class='delete' onclick=\"deleterow('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt'></i></span></a>
                         </div> 
                      </div>    
                     <div class='card-body'>
                        <div class='d-flex justify-content-between align-items-center'>
                            <div>
                                " . $condition[$data['condition']] . "
                            </div>
                        </div>
                        <div class='row'>
                            " . $imgdata . "
                        </div>
                    </div>
                  </div>
                </div>";

                $statuscls = '#de2727';
                $statusname = 'EXISTENT';
            } else {
                $statuscls = '#199509';
                $statusname = 'FIXED';
            }
            $imgdata = '';
            $data1[] = "<tr>
        <td></td>
        <td>" . $data['damage_part'] . "</td>
        <td>" . $data['date'] . "</td>
        <td>
            <span class='label label-secondary' style='background-color:" . $statuscls . ";'>" . $statusname . "</span>
        </td>
        <td></td>
        <td>
        <a href='#' class='mark' onclick=\"mark('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Mark as Fixed'><span><i class='fas fa-check-circle'></i></span></a>&nbsp;
        <a href='#' class='edit' onclick=\"view('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='View'><span><i class='fas fa-eye'></i></span></a>&nbsp;
        <a href='#' class='delete' onclick=\"deleterow('" . $data['id'] . "','" . $data['damage_part'] . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a>
        </td></tr>";
            $part[] = $data['damage_part'];
            $type[] = $data['damage_type'];
            $state[] = $data['state'];
        }

        $mysql->dbDisConnect();
        $response['data'] = $data1;
        $response['part'] = $part;
        $response['type'] = $type;
        $response['state'] = $state;
        $response['div'] = $div;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'damagereportpdf') {
        $data = [];
        $data1 = [];
        $data2 = [];
        $data3 = [];
        $data4 = [];
        $response = [];
        $div = [];
        $imgdata = '';
        $imgdata2 = '';
        $imgdata3 = '';
        $dmg_type = '';
        $condition = ['Broken', 'Dented', 'Chipped', 'Scratched', 'Missing', 'Worn'];
        $dmg_type = ['Low', 'Medium', 'High'];

        header("content-Type: application/json");

        $userid = $_POST['userid'];
        $id = $_POST['id'];
        $date = $_POST['date'];
        $reportid = $_POST['reportid'];

        $mysql = new Mysql();
        $mysql->dbConnect();

    //     $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
    // `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  AND `vehicle_id` = " . $id . "  AND `insert_date` < '" . $date . "' AND state = 0 GROUP BY `damage_part`";
        $query = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        `isdelete` = 0   AND `vehicle_id` = " . $id . "  AND `insert_date` < '" . $date . "' AND state = 0 GROUP BY `damage_part`";
        $row =  $mysql->selectFreeRun($query);

        // $query2 = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        // `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  AND `vehicle_id` = " . $id . "  AND `insert_date` >= '" . $date . "' AND state = 0 GROUP BY `damage_part`";
        $query2 = "SELECT *,DATE_FORMAT(`insert_date`,'%D %M%, %Y') AS date FROM `tbl_vehicledamage_img` WHERE 
        `isdelete` = 0   AND `vehicle_id` = " . $id . "  AND `insert_date` >= '" . $date . "' AND state = 0 GROUP BY `damage_part`";
        $row2 =  $mysql->selectFreeRun($query2);

        while ($data = mysqli_fetch_array($row)) {
            // $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  
            // AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $query1 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0  AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $data['damage_part'] . "'";
            $row1 =  $mysql->selectFreeRun($query1);
            while ($divdata = mysqli_fetch_array($row1)) {
                $imgdata = $imgdata . "<div class='col-4'>
                <img src='" . $webroot . "uploads/vehiclesdamageimages/" . $divdata['image'] . "' id='" . $divdata['id'] . "' width='150'  name='imgsrc' class='img-thumbnail img-fluid'/>
            </div>";
            }

            $data1[] = "<tr style='background-color:bisque;'>
        <td style='text-transform: uppercase;'><b>" . $data['damage_part'] . "</b></td>
        <td>Condition : <b>" . $condition[$data['condition']] . "</b></td>
        <td>Severity : <b>" . $dmg_type[$data['damage_type']] . "</b></td>
        </tr>
        <tr>
        <td colspan='3' style='padding-bottom:20px;'><div class='row'>" . $imgdata . "</div></td>
        </tr>";
            $imgdata = '';
        }

        while ($newdamage = mysqli_fetch_array($row2)) {
            // $q2 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0 AND `userid` LIKE '" . $userid . "'  
            // AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $newdamage['damage_part'] . "'";
            $q2 = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete` = 0   
            AND `vehicle_id` = " . $id . " AND `state` = 0 AND `damage_part` LIKE '" . $newdamage['damage_part'] . "'";
            $r2 =  $mysql->selectFreeRun($q2);
            while ($divdata2 = mysqli_fetch_array($r2)) {
                $imgdata2 = $imgdata2 . "<div class='col-4'>
                <img src='" . $webroot . "uploads/vehiclesdamageimages/" . $divdata2['image'] . "' id='" . $divdata2['id'] . "' width='150'  name='imgsrc' class='img-thumbnail img-fluid'/>
            </div>";
            }


            $data2[] = "<tr style='background-color:bisque;'>
        <td style='text-transform: uppercase;'><b>" . $newdamage['damage_part'] . "</b></td>
        <td>Condition : <b>" . $condition[$newdamage['condition']] . "</b></td>
        <td>Severity : <b>" . $dmg_type[$newdamage['damage_type']] . "</b></td>
        </tr>
        <tr>
        <td colspan='3' style='padding-bottom:20px;'><div class='row'>" . $imgdata2 . "</div></td>
        </tr>";
            $imgdata2 = '';
        }

        // $query3 = "SELECT `vehiclephoto` FROM `tbl_conditionalreportdata` WHERE `isactive`=0 AND `isdelete`=0 AND 
        // `userid` LIKE '" . $userid . "'  AND `vehicle_id` = " . $id . " AND `id`=" . $reportid;
        $query3 = "SELECT `vehiclephoto` FROM `tbl_conditionalreportdata` WHERE `isactive`=0 AND `isdelete`=0 AND 
         `vehicle_id` = " . $id . " AND `id`=" . $reportid;
        $row3 =  $mysql->selectFreeRun($query3);

        while ($data3 = mysqli_fetch_array($row3)) {
            $img = explode(",", $data3['vehiclephoto']);
            $count = count($img);

            for ($i = 0; $i < $count; $i++) {
                $imgdata3 = $imgdata3 . "<div class='col-4'>
                <img src='" . $webroot . "uploads/conditionalreport/extraimage/" . $img[$i] . "' width='150'  name='imgsrc' class='img-thumbnail img-fluid'/>
            </div>";
            }
            $data4[] = "
        <tr>
        <td colspan='3' style='padding-bottom:20px;'><div class='row'>" . $imgdata3 . "</div></td>
        </tr>";
            $imgdata3 = '';
        }

        $mysql->dbDisConnect();
        $response['data'] = $data1;
        $response['data2'] = $data2;
        $response['data3'] = $data4;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'repaircostformdata') {

        header("content-Type: application/json");

        $mysql = new Mysql();
        $mysql->dbConnect();

        $userid = $_POST['userid'];
        $id = $_POST['id'];

        $permission = "SELECT * FROM `tbl_vehicledamage_cost` WHERE `userid` = " . $userid . " and `vehicle_id` = " . $id . " and  `isactive` = 0 and `isdelete` = 0";
        $permissionrow =  $mysql->selectFreeRun($permission);
        $pr_result = mysqli_fetch_array($permissionrow);


        $mysql->dbDisConnect();

        $response['data'] = $pr_result;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'vehicledata') {
        header("content-Type: application/json");

        $mysql = new Mysql();
        $mysql->dbConnect();

        $uid = $_POST['uid'];
        $vid = $_POST['vid'];

        $permission = "SELECT v.*,vm.`name` as makename,vmo.`name` as modelname 
      FROM `tbl_vehicles` v 
      LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
      LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
      WHERE v.`id`=" . $vid . " AND v.`userid`=" . $uid;
        $permissionrow =  $mysql->selectFreeRun($permission);
        $pr_result = array();
        $cnt = mysqli_num_rows($permissionrow);
        $response['status'] = 0;
        if($cnt != 0)
        {
            $response['status'] = 1;
        }
        $pr_result = mysqli_fetch_array($permissionrow);
        $mysql->dbDisConnect();
        $response['statusdata'] = $pr_result;
        // $response['regno'] = $pr_result['regno'];
        // $response['make'] = $pr_result['makename'];
        // $response['model'] = $pr_result['modelname'];

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'inspectionreportdata') {
        $response = [];
        $depotresult1 = [];
        //$div = [];

        header("content-Type: application/json");
        $userid = $_POST['userid'];

        $mysql = new Mysql();
        $mysql->dbConnect();

        $chklist = "SELECT COUNT(*) AS ct1 FROM tbl_vehiclechecklist WHERE `isdelete`= 0";
        $chkfire = $mysql->selectFreeRun($chklist);
        $fetch = mysqli_fetch_array($chkfire);
        $chkcount = (int)$fetch['ct1'] + 1;

        $statusquery = "SELECT DISTINCT b.`name` as name,b.`id` as id FROM `tbl_vehicles` a 
                        INNER JOIN tbl_workforcedepotassign w ON a.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                        INNER JOIN tbl_depot b ON b.`id` = a.`depot_id` WHERE a.`isdelete` = 0 GROUP BY a.`depot_id`";
        $depot =  $mysql->selectFreeRun($statusquery);

        while ($depotresult = mysqli_fetch_array($depot)) {
            $div .= "<tr ><td colspan='17' style='background-color: lightgray;'>" . $depotresult['name'] . "</td></tr>";
            $query = "SELECT DISTINCT a.`id`,c.`name`,b.`id` FROM `tbl_vehiclerental_agreement` a 
                      INNER JOIN tbl_vehicles b ON b.`id` = a.`vehicle_id`
                      INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL 
                      INNER JOIN tbl_contractor c ON c.`id` = a.`driver_id` 
                      WHERE b.`depot_id` LIKE '" . $depotresult['id'] . "' GROUP BY c.name ";
            $row =  $mysql->selectFreeRun($query);
            while ($cnt = mysqli_fetch_array($row)) {

                $div .= "<tr><td>" . $cnt['name'] . "</td>";
                for ($i = 15; $i >= 0; $i--) {
                    $d =  Date('Y-m-d', strtotime('-' . $i . ' days'));
                    $vid = $cnt['id'];
                    $query1 = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleinspection` WHERE `isdelete`= 0 AND vehicle_id=$vid AND odometer IS NOT NULL AND `odometerInsert_date` LIKE '" . $d . "%'";
                    $userrow =  $mysql->selectFreeRun($query1);
                    $userrowcount = mysqli_num_rows($userrow);

                    if ($userrowcount > 0) {
                        while ($userresult = mysqli_fetch_array($userrow)) {
                            $entry = "SELECT COUNT(*) AS cnt2 FROM tbl_vehicleinspection WHERE `odometerInsert_date` LIKE '" . $d . "%'";
                            $chkentry = $mysql->selectFreeRun($entry);
                            $fetch1 = mysqli_fetch_array($chkentry);
                            $result = (int)$chkcount - (int)$fetch1['cnt2'];
                            if ($result == 0) {
                                $sql3 = "SELECT * FROM tbl_vehicleinspection WHERE `odometerInsert_date` LIKE '" . $d . "%' AND answer_type=0";
                                $fire3 = $mysql->selectFreeRun($sql3);
                                if ($fire3) {
                                    $rowcount = mysqli_num_rows($fire3);

                                    if ($rowcount > 0) {
                                        $div .= "<td style='color: rgba(239,68,68,1);background-color: rgba(254,226,226,1);text-align: center;'><i class='fas fa-times'></i></td>";
                                    } else {
                                        $div .= "<td style='color: rgba(6,95,70,1);background-color: rgba(209,250,229,1);text-align: center;'><i class='fas fa-check'></i></td>";
                                    }
                                }
                                $div .= "<td></td>";
                            }
                        }
                    } else {
                        $div .= "<td></td>";
                    }
                }
                $div .= "</tr>";
            }
        }
        $mysql->dbDisConnect();
        $response['div'] = $div;

        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'bank_detailcheck') {
        header("content-Type: application/json");
        $status = 0;
        $msg = '';
        $mysql = new Mysql();
        $mysql->dbConnect();
        $account_number = $_POST['account_number'];
        $bank_name = $_POST['bank_name'];
        $id = $_POST['id'];
        $varid = '';
        if ($id > 0) {
            $varid = ' AND id NOT IN (' . $id . ')';
        }

        $query = "SELECT id FROM `tbl_contractor` WHERE `account_number` = '" . $account_number . "' AND  `bank_name` = '" . $bank_name . "'  AND `isdelete`= 0 " . $varid;
        $rows = $mysql->selectFreeRun($query);
        $fetch_rows = mysqli_num_rows($rows);
        if ($fetch_rows > 0) {
            $status = 0;
        } else {
            $status = 1;
        }


        $response['status'] = $status;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'workforcebank_detailcheck') {
        header("content-Type: application/json");
        $status = 0;
        $msg = '';
        $mysql = new Mysql();
        $mysql->dbConnect();
        $account_number = $_POST['account_number'];
        $bank_name = $_POST['bank_name'];
        $id = $_POST['id'];
        $varid = '';
        if ($id > 0) {
            $varid = ' AND id NOT IN (' . $id . ')';
        }

        $query = "SELECT id FROM `tbl_user` WHERE `account_number` = '" . $account_number . "' AND  `bank_name` = '" . $bank_name . "'  AND `isdelete`= 0 " . $varid;
        $rows = $mysql->selectFreeRun($query);
        $fetch_rows = mysqli_num_rows($rows);
        if ($fetch_rows > 0) {
            $status = 0;
        } else {
            $status = 1;
        }

        $response['status'] = $status;
        echo json_encode($response);
    } else if (isset($_POST['action']) && $_POST['action'] == 'getalldashboarddata') {
        header("content-Type: application/json");
        $userid = $_POST['userid'];

        $mysql = new Mysql();
        $mysql->dbConnect();

        ////2nd row....
        $feed = "SELECT COUNT(DISTINCT a.`id`) AS fb FROM `tbl_contactorfeedback` a 
                INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                WHERE  a.`isdelete`=0 AND DATE(a.`insert_date`) = CURRENT_DATE()";
        $fbrow =  $mysql->selectFreeRun($feed);
        $resultfb = mysqli_fetch_array($fbrow);

        $feed1 = "SELECT COUNT(DISTINCT a.`id`) AS fb1 FROM `tbl_contactorfeedback` a 
                  INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                  INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                  WHERE  a.`isdelete`=0 AND MONTH(a.`insert_date`) = MONTH(CURRENT_DATE()) AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE())";
        $fbrow1 =  $mysql->selectFreeRun($feed1);
        $resultfb1 = mysqli_fetch_array($fbrow1);

        $feed2 = "SELECT COUNT(DISTINCT a.`id`) AS fb2 FROM `tbl_contactorfeedback` a 
                  INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                  INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                  WHERE  a.`isdelete`=0 AND YEAR(a.`insert_date`) = YEAR(CURRENT_DATE())";
        $fbrow2 =  $mysql->selectFreeRun($feed2);
        $resultfb2 = mysqli_fetch_array($fbrow2);

        $statusquery = "SELECT COUNT(DISTINCT a.`id`) as today FROM `tbl_vehiclerental_agreement` a 
                        INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                        WHERE a.`isdelete`=0  AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date` AND v.`status` != 5 ";
        $strow =  $mysql->selectFreeRun($statusquery);
        $result = mysqli_fetch_array($strow);

        $statusquery1 = "SELECT COUNT(DISTINCT a.`id`) as monthly FROM `tbl_vehiclerental_agreement` a 
                         INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                         INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                         WHERE a.`isdelete`=0 AND MONTH(CURRENT_DATE()) IN (MONTH(a.`pickup_date`), MONTH(a.`return_date`)) AND v.`status` != 5";
        $strow1 =  $mysql->selectFreeRun($statusquery1);
        $result1 = mysqli_fetch_array($strow1);

        $statusquery2 = "SELECT COUNT(DISTINCT a.`id`) as yearly FROM `tbl_vehiclerental_agreement` a 
                        INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
                        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                        WHERE a.`isdelete`=0  AND YEAR(CURRENT_DATE()) IN (YEAR(a.`pickup_date`), YEAR(a.`return_date`)) AND v.`status` != 5";
        $strow2 =  $mysql->selectFreeRun($statusquery2);
        $result2 = mysqli_fetch_array($strow2);

        $assigncnt = " SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                       INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid` 
                       INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id)
                       WHERE  a.`isdelete`=0 AND a.`status_id`=1 AND a.`date`=CURRENT_DATE()";
        $row =  $mysql->selectFreeRun($assigncnt);
        $assigncnt_res = mysqli_fetch_array($row);

        $assigncnt1 = "SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                       INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid`
                       INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id) 
                       WHERE a.`isdelete`=0 AND a.`status_id`=1 AND  MONTH(a.`date`)=MONTH(CURRENT_DATE()) AND YEAR(a.`date`)=YEAR(CURRENT_DATE())";
        $row1 =  $mysql->selectFreeRun($assigncnt1);
        $assigncnt_res1 = mysqli_fetch_array($row1);

        $assigncnt2 = "SELECT COUNT(DISTINCT a.`id`) FROM `tbl_contractortimesheet` a 
                       INNER JOIN `tbl_contractor` b ON b.`id` = a.`cid`
                       INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND b.`depot` IN (w.depot_id) 
                       WHERE  a.`isdelete`=0 AND a.`status_id`=1 AND  YEAR(a.`date`)=YEAR(CURRENT_DATE())";
        $row2 =  $mysql->selectFreeRun($assigncnt2);
        $assigncnt_res2 = mysqli_fetch_array($row2);

        $mysql->dbDisConnect();
        $response['resultfb'] = $resultfb;
        $response['resultfb1'] = $resultfb1;
        $response['resultfb2'] = $resultfb2;
        $response['result'] = $result;
        $response['result1'] = $result1;
        $response['result2'] = $result2;
        $response['assigncnt'] = $assigncnt_res;
        $response['assigncnt1'] = $assigncnt_res1;
        $response['assigncnt2'] = $assigncnt_res2;

        echo json_encode($response);
    }
    else if (isset($_POST['action']) && $_POST['action'] == 'ContractorURLSend') 
    {
        header("content-Type: application/json");
        $status = 0;
        $id = $_POST['cid'];
        $mysql = new Mysql();
        $mysql->dbConnect();

        $user =  $mysql->selectWhere('tbl_contractor', 'id', '=', $id, 'int');
        $userresult = mysqli_fetch_array($user);
        if ($userresult > 0) {
        
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
            $password = substr( str_shuffle( $chars ), 0, 10 );
            // Encrypt password
            //$password = password_hash($password, PASSWORD_ARGON2I);
            $email = $userresult['email'];
            $name = $userresult['name'];

            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Please login with given credentials.";
            $body = 'Please verify it"s you.';
            // $dataMsg =  "Hello, ".$name."\r\n"
            //             ." Please verify login with this credentials"."\r\n"
            //             ." Username: ".$email."\r\n"
            //             ." Password: ".$password; 
            $dataMsg = '
                    <html>
                        <head>
                            <style>
                                .content {
                                  max-width: 1000px;
                                  margin: auto;
                                }
                                </style>
                        </head>
                    <body>
                    <div class="row content">
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$name.'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using <a href="http://drivaar.com/contractorAdmin/">http://drivaar.com/contractorAdmin/</a>. Make sure to save this email so that you can access this link later.Please verify login with this credentials. 
                                       <u><b><br>Username:</b></u>'.$email.' 
                                       <u><b><br>Password:</b></u>'.$password.'<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driverâ€™s license, UTR number, NINO number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When youâ€™ve completed these tasks, Drivaar will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br>http://drivaar.com/contractorAdmin/ <a href="#"><img height="50px" width="200px" src="./google-play.png"></a> <a href="#!"><img height="50px" width="200px" src="./app-store.png"></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Drivaar Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>';  
            require_once('phpmailer/class.phpmailer.php');
            $mail = new PHPMailer(true);            
            $mail->IsSMTP(); 
            try 
            {
                  $mail->Host       = "smtp.office365.com"; 
                  $mail->SMTPDebug  = 0;
                  $mail->SMTPAuth   = true; 
                  $mail->Port       = 587;
                  $mail->SMTPSecure = 'tls';     
                  $mail->Username   = $emlusername; 
                  $mail->Password   = $emlpassword;        
                  $mail->AddAddress($email,$name);
                  $mail->SetFrom($emlusername,$emltitle);
                  $mail->mailtype = 'html';
                  $mail->charset = 'iso-8859-1';
                  $mail->wordwrap = TRUE;
                  $mail->Subject = $subject;
                  $mail->AltBody = $body; 
                  $mail->MsgHTML($dataMsg);
                  $mail->Send();
                  if($password)
                  {
                        $valusu[0]['password'] = $password;
                        $urldate = date('Y-m-d H:i:s');
                        $valusu[0]['urldate'] = $urldate;
                        $col = array('password','urldate');
                        $where = 'id ='.$id;
                        $update = $mysql -> update('tbl_contractor',$valusu,$col,'update',$where);
                        if($update)
                        {
                            $status=1;
                            $title = 'Send';
                            $message = 'Email Message has been send successfully.';
                        }
                        else
                        {
                            $status=0;
                            $title = 'Error';
                            $message = 'Email Message has not been send successfully.';
                        }
                  }   
            } 
            catch (phpmailerException $e) 
            {
                $status=0;
                $title='Error';
                $message = $mail->ErrorInfo;
                
              //echo $e->errorMessage(); exit;
            } 
            catch (Exception $e) 
            {
              //echo $e->getMessage();  exit;   
            } 

        }
        $mysql->dbDisConnect();
        $response['status'] = $status;
        $response['title'] = $title;
        $response['message'] = $message;
        echo json_encode($response);
    }
    else if (isset($_POST['action']) && $_POST['action'] == 'WorkforceURLSend') 
    {
        header("content-Type: application/json");
        $status = 0;
        $id = $_POST['wid'];
        $mysql = new Mysql();
        $mysql->dbConnect();

        $user =  $mysql->selectWhere('tbl_user', 'id', '=', $id, 'int');
        $userresult = mysqli_fetch_array($user);
        if ($userresult > 0) {

            $token = md5($userresult['email']).rand(10,9999);
            $where = "email = '".$userresult['email']."'";
            $valus[0]['reset_link_token'] = $token;
            $update = $mysql -> update('tbl_user', $valus, 'reset_link_token', 'update', $where);
        
            $link = "<a href='".$webroot."reset-password.php?key=".$id."&token=".$token."'>
            Click here To set password</a>";

            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Drivaar : Set Your Password";
            $body = 'Please verify it"s you.';
            $dataMsg = '
                    <html>
                        <head>
                            <style>
                                .content {
                                  max-width: 1000px;
                                  margin: auto;
                                }
                                </style>
                        </head>
                    <body>
                    <div class="row content">
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$userresult['name'].'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using '.$userresult['email'].'. Make sure to save this email so that you can access this link later.<br>Set Your Drivaar Password <u>'.$link.'</u>.<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driverâ€™s license, UTR number, NINO number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When youâ€™ve completed these tasks, Drivaar will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br> <a href="#"><img height="50px" width="200px" src="./google-play.png"></a> <a href="#!"><img height="50px" width="200px" src="./app-store.png"></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Drivaar Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>';  
            require_once('phpmailer/class.phpmailer.php');
            $mail = new PHPMailer(true);            
            $mail->IsSMTP();  
            try 
            {
                  $mail->Host       = "smtp.office365.com"; 
                  $mail->SMTPDebug  = 0;
                  $mail->SMTPAuth   = true; 
                  $mail->Port       = 587;
                  $mail->SMTPSecure = 'tls';     
                  $mail->Username   = $emlusername; 
                  $mail->Password   = $emlpassword;        
                  $mail->AddAddress($userresult['email'],$userresult['name']);
                  $mail->SetFrom($emlusername,$emltitle);
                  $mail->mailtype = 'html';
                  $mail->charset = 'iso-8859-1';
                  $mail->wordwrap = TRUE;
                  $mail->Subject = $subject;
                  $mail->AltBody = $body; 
                  $mail->MsgHTML($dataMsg);
                  $mail->Send();
                 
                    $valusu[0]['urldate'] = date('Y-m-d H:i:s');
                    $col = array('urldate');
                    $where = 'id ='.$id;
                    $update = $mysql -> update('tbl_user',$valusu,$col,'update',$where);
                    if($update)
                    {
                        $status=1;
                        $title = 'Send';
                        $message = 'Email Message has been send successfully.';
                    }
                    else
                    {
                        $status=0;
                        $title = 'Error';
                        $message = 'Email Message has not been send successfully.';
                    }    
            } 
            catch (phpmailerException $e) 
            {
                $status=0;
                $title = 'Email not Sent';
                $message = $mail->ErrorInfo;
                //exit;
            } 
            catch (Exception $e) 
            {
                // $status=0;
                // $title = 'Email not Sent';
                // $message = 'Failed to sent mail.';
                // exit;   
            } 

        }
        $mysql->dbDisConnect();
        $response['status'] = $status;
        $response['title'] = $title;
        $response['message'] = $message;
        echo json_encode($response);
    }
    else if (isset($_POST['action']) && $_POST['action'] == 'ContractorOnboardURLSend') 
    {
        header("content-Type: application/json");
        $status = 0;
        $id = $_POST['cid'];
        $mysql = new Mysql();
        $mysql->dbConnect();

        $user =  $mysql->selectWhere('tbl_contractor', 'id', '=', $id, 'int');
        $userresult = mysqli_fetch_array($user);
        if ($userresult > 0) {
        
        $imgid = 1;
        $imgqry =  $mysql -> selectWhere('tbl_orgdocument','id','=',$imgid,'int');
        $imgresult = mysqli_fetch_array($imgqry);
        $image = $webroot.'uploads/organizationdocuments/'.$imgresult['file1'];
        $image1 = $webroot.'uploads/organizationdocuments/'.$imgresult['file'];

        $cid = base64_encode($id);
        $emlusername='noreply@drivaar.com';
        $emlpassword='DrivaarInvitation@123';
        $emltitle = 'Drivaar';
        $subject = 'Drivaar';
        $body = 'Hello,'.$userresult["name"].'Here is your registration link.';
        $email =$userresult['email'];
        $name = $userresult['name'];

        $dataMsg = '
                    <html>
                        <head>
                            <style>
                                .content {
                                  max-width: 1000px;
                                  margin: auto;
                                }
                                </style>
                        </head>
                    <body>
                    <div class="row content">
                        <img height="50px" width="150px" src="'.$image.'">
                        <img height="50px" width="150px" src="'.$image1.'">
                    </div>
                    <div class="row content">
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$name.'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Bryanston Logistics.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using '.$email.'. Make sure to save this email so that you can access this link later.<br>You have recieved this email for registration in Drivaar. Please click on the link and complete your registration process.<a href="http://drivaar.com/getreg.php?cid='.$cid.'">Click here</a> to register.<br>

                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driving  license, UTR number, National Insurance(NI) Number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When you\'ve completed these tasks, Bryanston Logistics will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br>http://drivaar.com/getreg.php?cid='.$cid.' <a href="#"><img height="50px" width="200px" src="./google-play.png"></a> <a href="#!"><img height="50px" width="200px" src="./app-store.png"></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Bryanston Logistics Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>';  

        require_once('phpmailer/class.phpmailer.php');
        $mail = new PHPMailer(true);            
        $mail->IsSMTP(); 
          try {
                  $mail->Host       = "smtp.office365.com"; 
                  $mail->SMTPDebug  = 0;
                  $mail->SMTPAuth   = true; 
                  $mail->Port       = 587;
                  $mail->SMTPSecure = 'tls';     
                  $mail->Username   = $emlusername; 
                  $mail->Password   = $emlpassword;        
                  $mail->AddAddress($email,$name);
                  $mail->SetFrom($emlusername,$emltitle);
                  $mail->mailtype = 'html';
                  $mail->charset = 'iso-8859-1';
                  $mail->wordwrap = TRUE;
                  $mail->Subject = $subject;
                  $mail->AltBody = $body; 
                  $mail->MsgHTML($dataMsg);
                  $mail->Send();


                $onboardutldate = date('Y-m-d H:i:s');
                $valusu[0]['onboardutldate'] = $onboardutldate;
                $col = array('onboardutldate');
                $where = 'id ='.$id;
                $update1 = $mysql -> update('tbl_contractor',$valusu,$col,'update',$where);  

            } catch (phpmailerException $e) {
                $status = 0;
                $title = 'Email Error';
                $message = $mail->ErrorInfo;
              //echo $e->errorMessage(); exit;
            } catch (Exception $e) {
              //echo $e->getMessage();  exit;    
            }

            if($update1)
            {
                $status = 1;
                $title = 'Send Onboard Email';
                $message = 'Send Onboard Email has been send successfully.';
            }
            else
            {
                $status = 0;
                $title = 'Email Error';
                $message = 'Send Onboard Email can not send.';
            }
 
            $name = 'Send';
            $mysql -> dbDisConnect();

        }
        $mysql->dbDisConnect();
        $response['status'] = $status;
        $response['title'] = $title;
        $response['message'] = $message;
        echo json_encode($response);
    }
    else if (isset($_POST['action']) && $_POST['action'] == 'NewScheduleDriverData') 
    {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();

    $vehicleid = $_POST['id'];
    $startdate = $_POST['startdate'];

    $statusquery = "SELECT DISTINCT c.* 
                    FROM `tbl_contractor` c 
                    INNER JOIN `tbl_depot` d ON d.`id`=c.`depot` 
                    INNER JOIN `tbl_vehicles` v ON v.`depot_id`=d.`id` 
                    LEFT JOIN `tbl_vehiclerental_agreement` av ON av.`driver_id`=c.`id` 
                    AND av.`pickup_date`>=" . $startdate . " AND av.`return_date`<=" . $startdate . "
                    WHERE c.`isdelete`=0 AND c.`iscomplated`=1 AND v.`id`=" . $vehicleid . " AND 
                    ((SELECT COUNT(co.`id`) FROM `tbl_contractoronboarding` co Where co.`cid`=c.`id` AND co.`is_onboard`=1)=
                    (SELECT COUNT(id) FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0))";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        $options = array();
        $options[] = "<option value='0'>--</option>";
        while ($statusresult = mysqli_fetch_array($strow)) {

            $options[] = "<option value=" . $statusresult['id'] . ">" . $statusresult['name'] . "</option>";
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
}
else if(isset($_POST['action']) && $_POST['action'] == 'NewScheduledataGet') {

    header("content-Type: application/json");
    $status = 0;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
    $dpid = $_POST['dpid'];
    $mnt = $_POST['mont'];
    $yer = $_POST['yer'];
    $statusquery = "SELECT a.*,c.`name` FROM `tbl_vehiclerental_agreement` a INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver_id` WHERE a.`isdelete`=0 AND a.`isactive`=0 AND a.depot_id=$dpid AND ((MONTH(a.pickup_date)= $mnt  AND YEAR(a.pickup_date)=$yer) OR (MONTH(a.return_date)= $mnt  AND YEAR(a.return_date)=$yer)) AND a.iscompalete=1 AND a.isdelete=0";
    $strow =  $mysql->selectFreeRun($statusquery);
    if ($strow) {
        $status = 1;
        while ($statusresult = mysqli_fetch_array($strow)) {
            $stdt = "";
            $endt = "";
            $unqid = $statusresult['tduniqid'];
            $pdMon = str_pad($mnt, 2, "0", STR_PAD_LEFT);;
            //$mont = (int)date('m',strtotime($statusresult['pickup_date']));
            // if($mont<$mnt)
            $newPickupDate = $yer."-".$pdMon."-01";
            if(strtotime($statusresult['pickup_date'])<strtotime($newPickupDate))
            {
                $stdt = $newPickupDate;
                $exp = explode("_",$unqid);
                $exp[2] = "01";
                $exp[3] = $pdMon;
                $exp[4] = $yer;
                $unqid = implode("_",$exp);
            }else
            {
                $stdt = $statusresult['pickup_date'];
            }
            // if(date('m',strtotime($statusresult['return_date']))>$mnt)
            $newReturnDate = date("Y-m-t", strtotime($yer."-".$pdMon."-01"));
            if(strtotime($statusresult['return_date'])>strtotime($newReturnDate))
            {
                $endt = $newReturnDate;
            }else
            {
                $endt = $statusresult['return_date'];
            }
            $return_arr[] =  array(
                "startdate" => $stdt,
                "enddate" => $endt,
                "uniqid" => $unqid,
                "name" => $statusresult['name']
            );
        }
    }

    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}else if(isset($_POST['action']) && $_POST['action'] == 'getInvcKey') {
    header("content-Type: application/json");
    $status = 0;
    //changes
    if($_POST['invoicetype']==2)
    {
        $setistype = $_POST['month'];
    }
    else
    {
        $setistype = date('W', strtotime($_POST['cdate']));
        if(date('l', strtotime($_POST['cdate'])) == 'Sunday')
        {
            $setistype = $setistype + 1;
        }
    }
   // $wek_no = date('W', strtotime($_POST['cdate']));
    
    $year_no = explode('-', $_POST['cdate'])[0];
    $cid = $_POST['id'];
    $invc_key = base64_encode($cid."#1#".$setistype."#".$year_no);
    $invc = new InvoiceAmountClass();
    $invcID = $invc->getInvoiceNo($cid,$_POST['cdate']);
    $total = $invc->ContractorInvoiceTotal($invcID);
    $total = $total['finaltotal'];
    $res['status'] = 1;
    $res['invcKey'] = $invc_key;
    $res['weekTotal'] =$total;
    echo json_encode($res);
}else if (isset($_POST['action']) && $_POST['action'] == 'exportInvoiceTableData') 
{
    header("content-Type: application/json");
    $wikNo = $_POST['wikNo'];
    $wikYear = $_POST['wikYear'];
    $vat = $_POST['vat'];
    $duedate = $_POST['duedate'];
    $status = $_POST['status'];
    $depot_id = $_POST['depot_id'];
    $extraquery = "";
    // if($vat==1)
    // {
    //     $extraquery .= " AND c.`vat_number` LIKE '%'";
    // }else if($vat==2)
    // {
    //     $extraquery .= " AND c.`vat_number` IS NULL";
    // }
     if($vat==1)
    {
        $extraquery .= " AND i.`vat`=1";
    }else if($vat==2)
    {
        $extraquery .= " AND i.`vat`=0";
    }
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery .= " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }
    if($depot_id!="%")
    {
        $extraquery .= " and i.`depot_id`=".$depot_id;
    }
    if($status!="%")
    {
        $extraquery .= " AND i.`status_id`=".$status;
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
    $sql = "SELECT i.`invoice_no`, i.`week_no`, DATE_FORMAT(i.`from_date`, '%d-%m-%Y') as from_date, 
    DATE_FORMAT(i.`to_date`, '%d-%m-%Y') as to_date, i.`weekyear`, 
    IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,
    c.name as cname,c.arrears,d.name as dname, is1.name as sname,IF(cc.name IS NULL,'NA',cc.name) as compName,
    IF(i.`vat`=0, 'NO', 'YES') as vatStatus,
    ROUND((getTimesheetTotal(i.`invoice_no`)-getVanDeduction(i.cid,i.from_date,i.to_date,i.`invoice_no`)
    -getLoanDedunction(i.cid,i.week_no)),3) as totalAmount 
    FROM `tbl_contractorinvoice` i 
    INNER JOIN `tbl_contractor` c ON c.id=i.cid 
    INNER JOIN `tbl_depot` d ON d.id=i.depot_id 
    INNER JOIN `tbl_invoicestatus` is1 ON is1.id=i.status_id 
    LEFT JOIN `tbl_contractorcompany` cc ON cc.id=c.company 
    WHERE i.`isdelete`=0 AND i.`week_no`=$wikNo AND i.`istype`=1 AND i.`weekyear` = $wikYear ".$extraquery;
    $strow =  $mysql->selectFreeRun($sql);
    $dt="";
    $totalshow = 0;
    $$net  = 0;
    $vatam = 0;
    while ($statusresult = mysqli_fetch_array($strow)) {

        // $invc = new InvoiceAmountClass();
        // $totalshow = $invc->ContractorInvoiceTotal($statusresult['invoice_no']);
        // $totalshow = $totalshow['finaltotal'];
        // $net = $totalshow['$totalnet'];
        // $vatam = $totalshow['$totalvat'];


        $dt .= "<tr>
                <td>".$statusresult['invoice_no']."</td>
                <td>-</td>
                <td>-</td>
                <td>".$statusresult['totalAmount']."</td>
                <td>".$statusresult['week_no']."</td>
                <td>".$statusresult['from_date']."</td>
                <td>".$statusresult['to_date']."</td>
                <td>".$statusresult['weekyear']."</td>
                <td>".$statusresult['duedate']."</td>
                <td>".$statusresult['cname']."</td>
                <td>".$statusresult['compName']."</td>
                <td>".$statusresult['dname']."</td>
                <td>".$statusresult['sname']."</td>
                <td>".$statusresult['vatStatus']."</td>
                <td>".$statusresult['arrears']."</td>
                </tr>";
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    //$return_arr['qry']=$sql;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'exportNewWorkforceInvoiceTableData') 
{
    header("content-Type: application/json");
    $wid = $_POST['wid'];
    $extraquery = "";
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();

    $sql = "SELECT w.*,i.name as statusname,c.`invoice_no`,c.`week_no`,c.`status_id`,c.`duedate`,c.`weekyear`,c.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`,u.`vat_number` as vat_number1 
    FROM `tbl_contractorinvoice`  c 
    INNER JOIN `tbl_workforcetimesheet` w ON w.wid=c.cid AND w.isdelete=0 AND w.isactive=0 AND w.date BETWEEN c.from_date AND c.to_date AND w.`value` NOT LIKE '0' AND w.`value` NOT LIKE ''
    INNER JOIN `tbl_paymenttype` p ON p.`id`=w.`rateid`
    INNER JOIN `tbl_user` u ON u.`id`=c.`cid` 
    INNER JOIN `tbl_invoicestatus` i ON i.id=c.status_id
    WHERE c.`istype`=2 AND c.cid=".$wid." AND c.isdelete=0 AND c.isactive=0".$extraquery;
    $tblrow =  $mysql->selectFreeRun($sql);
    $dt="";
    $type = 0;
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    $totalAttendance = 0;
    $route = "";
    $prvDate = "";
    $frm_Date = "";
    $to_Date = "";
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        // if($tblresult['vat_number1']>0 || $tblresult['vat_number1'] != '')
        // {
        //     $vatFlag = 1;
        // }
        // else
        // {
        //     $vatFlag = 0;
        // }
        $vatFlag = $tblresult['civat'];
        $frm_Date = $tblresult['from_date'];
        $to_Date = $tblresult['to_date'];
        if(!empty($_POST['duedate']))
        {
            $dueDate = date("d/m/Y", strtotime($tblresult['duedate']));
        }
        else
        {
            $dueDate = "NA";
        }
        
        if ($tblresult['rateid'] == 0 && $tblresult['value'] != NULL && $tblresult['value'] != ''){
            $route = " (" . $tblresult['value'] . ")";
        }

        if ($tblresult['rateid'] > 0 && $tblresult['value'] != NULL && $tblresult['value'] != '') 
        {
            $adddate = date("d/m/Y", strtotime($tblresult['date']));
            $type = $tblresult['type'];
            if ($type == 1) {
                $typename = 'STANDARD SERVICES';
            } else if ($type == 2) {
                $typename = 'BONUS';
            } else if ($type == 3) {
                $typename = 'DEDUCTION';
            }

            $net = $tblresult['amount'] * $tblresult['value'];
            $vat = 0;
            if ($vatFlag == 1) {
                $vat = ($net * $tblresult['vat']) / 100;
            }
            $neg = "";
            if ($type == 3) {
                $net = -$net;
                $vat = -$vat;
                $neg = "-";
            }
            $total = $net + $vat;
            $dt .= "<tr>
                    <td>".$tblresult['invoice_no']."</td>
                    <td>".$typename."</td>
                    <td><b>Wk".$tblresult['week_no']."</b> - <small>" .$adddate. "</small></td>
                    <td>".$tblresult['name']."</td>
                    <td>".$tblresult['value']."</td>
                    <td>Â£ ".$neg.$tblresult['amount']."</td>
                    <td>";
                    if($vatFlag==1)
                    {
                        $dt .="Â£ ".$vat;
                    }
                    else
                    {
                        $dt .="-";
                    }
            $dt .= "</td>
                    <td>Â£ ".$total."</td>
                    <td>".$dueDate."</td>
                    <td>".$tblresult['statusname']."</td>
                    </tr>";

        }
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'loanReportData') 
{
    header("content-Type: application/json");
    $depotid = $_POST['did'];
    $statusid = $_POST['statusid'];
    $isloanid = $_POST['isloanid'];

    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();

    if ($_SESSION['userid'] == 1) {
        $userid = '%';
    } else {
        $userid = $_SESSION['userid'];
    }

    $query = "SELECT * FROM (SELECT 'Contractor' as type, b.`name` AS name,b.`isactive`,COALESCE(SUM(a.`amount`),0) as totalamount,
    (SELECT COALESCE(SUM(`amount`),0) FROM `tbl_contractorpayment` 
    WHERE `isdelete`=0  AND cid = a.`cid`) as paidamount,
    CASE
    WHEN (COALESCE(SUM(a.`amount`),0)=(SELECT COALESCE(SUM(`amount`),0) FROM `tbl_contractorpayment` 
    WHERE `isdelete`=0  AND cid = a.`cid`)) THEN '0'
    ELSE '1'
	END AS isstatus
    FROM `tbl_contractorlend` a 
    INNER join tbl_contractor b WHERE a.`cid` = b.`id` AND b.`isdelete` = 0 AND a.`isdelete` = 0 
    AND b.`userid` LIKE ('" . $userid . "') AND b.`depot` LIKE ('" . $depotid . "') AND b.`isactive` LIKE ('" . $statusid . "')  GROUP BY a.cid 
    UNION 
    SELECT 'Workforce' as type, b.name AS name,b.isactive,COALESCE(SUM(a.`amount`),0) as totalamount,
    (SELECT COALESCE(SUM(`amount`),0) FROM `tbl_workforcepayment` 
    WHERE `isdelete`=0  AND wid = a.wid) as paidamount,
                CASE
    WHEN (COALESCE(SUM(a.`amount`),0)=(SELECT COALESCE(SUM(`amount`),0) FROM `tbl_workforcepayment` 
    WHERE `isdelete`=0  AND wid = a.wid)) THEN '0'
    ELSE '1'
	END AS isstatus FROM `tbl_workforcelend` a 
    INNER join tbl_user b WHERE a.wid = b.id AND b.isdelete = 0 AND a.isdelete = 0 
    AND b.userid LIKE ('" . $userid . "') AND b.`depot` LIKE ('" . $depotid . "') AND b.`isactive` LIKE ('" . $statusid . "') GROUP BY a.wid ) as tbl WHERE tbl.isstatus LIKE ('".$isloanid."') order by tbl.type asc, tbl.name asc ";
    $tblrow =  $mysql->selectFreeRun($query);
    while ($tblresult = mysqli_fetch_array($tblrow)) 
    {
        $remain = $tblresult['totalamount'] - $tblresult['paidamount'];
        $dt .= "<tr>
                <td>".$tblresult['type']."</td>
                <td>".$tblresult['name']."</td>
                <td>Â£ ".$remain."</td>
                </tr>";
    }

    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'exportNewContractorInvoiceTableData') 
{
    header("content-Type: application/json");
    $cid = $_POST['cid'];
    $extraquery = "";
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();

    $sql = "SELECT w.*,i.name as statusname,c.`invoice_no`,w.date,c.`week_no`,c.`status_id`,IF(c.`duedate` IS NULL,'NA',DATE_FORMAT(c.`duedate`, '%d-%m-%Y')) as duedate,c.`weekyear`,c.`vat` as civat,
    p.`name`,p.`type`,p.`amount`,p.`vat`,d.`name` as dname   
    FROM `tbl_contractorinvoice`  c 
    INNER JOIN `tbl_contractortimesheet` w ON w.`cid`=c.`cid` AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`date` BETWEEN c.`from_date` AND c.`to_date` AND w.`value` NOT LIKE '0' AND w.`value` NOT LIKE ''
    INNER JOIN `tbl_paymenttype` p ON p.`id`=w.`rateid`
    INNER JOIN `tbl_contractor` u ON u.`id`=c.`cid` 
    INNER JOIN `tbl_depot` d ON d.`id`=c.`depot_id`
    INNER JOIN `tbl_invoicestatus` i ON i.id=c.status_id
    WHERE c.`istype`=1 AND c.cid=".$cid." AND c.isdelete=0 AND c.isactive=0".$extraquery;

    $tblrow =  $mysql->selectFreeRun($sql);
    $dt="";
    $type = 0;
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    $totalAttendance = 0;
    $route = "";
    $prvDate = "";
    $vanDeduct = array();
    $frm_Date = "";
    $to_Date = "";
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        $vatFlag = $tblresult['civat'];
        $frm_Date = $tblresult['from_date'];
        $to_Date = $tblresult['to_date'];
        // if(!empty($_POST['duedate']))
        // {
        //     $dueDate = date("d/m/Y", strtotime($tblresult['duedate']));
        // }
        // else
        // {
        //     $dueDate = "NA";
        // }
        
        if ($tblresult['rateid'] == 0 && $tblresult['value'] != NULL && $tblresult['value'] != ''){
            $route = " (" . $tblresult['value'] . ")";
        }

        if($prvDate!=$tblresult['date'])
        {
            $prvDate = $tblresult['date'];
            $vanDeduct[] = "'$prvDate' BETWEEN tav.`start_date` AND tav.`end_date`";
        }

        if ($tblresult['rateid'] > 0 && $tblresult['value'] != NULL && $tblresult['value'] != '') 
        {
            $adddate = date("d/m/Y", strtotime($tblresult['date']));
            $type = $tblresult['type'];
            if ($type == 1) {
                $typename = 'STANDARD SERVICES';
            } else if ($type == 2) {
                $typename = 'BONUS';
            } else if ($type == 3) {
                $typename = 'DEDUCTION';
            }

            $net = $tblresult['amount'] * $tblresult['value'];
            $vat = 0;
            if ($vatFlag == 1) {
                $vat = ($net * $tblresult['vat']) / 100;
            }
            $neg = "";
            if ($type == 3) {
                $net = -$net;
                $vat = -$vat;
                $neg = "-";
            }
            $total = $net + $vat;
            $dt .= "<tr>
                    <td>".$tblresult['invoice_no']."</td>
                    <td>".$tblresult['dname']."</td>
                    <td>".$typename."</td>
                    <td><b>Wk".$tblresult['week_no']."</b> - <small>" .$adddate. "</small></td>
                    <td>".$tblresult['name']."</td>
                    <td>".$tblresult['value']."</td>
                    <td>Â£ ".$neg.$tblresult['amount']."</td>
                    <td>";
                    if($vatFlag==1)
                    {
                        $dt .="Â£ ".$vat;
                    }
                    else
                    {
                        $dt .="-";
                    }
            $dt .= "</td>
                    <td>Â£ ".$total."</td>
                    <td>".$tblresult['duedate']."</td>
                    <td>".$tblresult['statusname']."</td>
                    </tr>";

        }
    }

    // $week_array = getStartAndEndDate($wikNo,$wikYear);
	// $frm_Date = $week_array['week_start'];
	// $to_Date = $week_array['week_end'];

    // $mysql = new Mysql();
    // $mysql->dbConnect();
    // $vanWher = implode(" OR ",$vanDeduct);
    // $tqueryNE = "SELECT tav.*,tv.registration_number,i.name as statusname 
    // FROM `tbl_assignvehicle` tav 
    // INNER JOIN `tbl_contractorinvoice` c ON c.cid=tav.driver AND c.`istype`=1 
    // INNER JOIN `tbl_invoicestatus` i ON i.id=c.status_id 
    // INNER JOIN `tbl_vehicles` tv ON tv.id=tav.vid 
    // WHERE ($vanWher) AND tav.isdelete=0 AND tav.driver=".$cid." 
    // ORDER BY tav.`start_date` ASC";
    // //echo $tqueryNE;
    // $trowNE =  $mysql -> selectFreeRun($tqueryNE);
    // $vatFlag = 0;
    // $rvDate1="";
    // $dt1="";
    // $route1='';

    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql1 = "SELECT w.*,c.`week_no`,c.`weekyear`  
    FROM `tbl_contractorinvoice`  c 
    INNER JOIN `tbl_contractortimesheet` w ON w.`cid`=c.`cid` AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`date` BETWEEN c.`from_date` AND c.`to_date` AND w.`value` NOT LIKE '0' AND w.`value` NOT LIKE ''
    WHERE c.`istype`=1 AND c.cid=".$cid." AND c.isdelete=0 AND c.isactive=0";
    $tblrow1 =  $mysql->selectFreeRun($sql1);
    while ($tblresult1 = mysqli_fetch_array($tblrow1))
    {
        $weekno = $tblresult1['week_no'];
        $weekyear = $tblresult1['weekyear'];
        $week_array = getStartAndEndDate($weekno,$weekyear);
	    $frm_Date = $week_array['week_start'];
	    $to_Date = $week_array['week_end'];
        $VehRentQry = "SELECT DISTINCT va.*, i.`week_no`,v.`registration_number`,i.`invoice_no`,i.`vat` as civat,c.`name` as username,IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,is1.`name` as sname,d.`name` as dname
        FROM `tbl_contractorinvoice` i
        INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
        INNER JOIN `tbl_depot` d ON d.`id`=i.`depot_id`
        INNER JOIN `tbl_vehiclerental_agreement` va ON va.`driver_id`=c.`id` AND (va.`pickup_date` BETWEEN '$frm_Date' AND '$to_Date' OR va.`return_date` BETWEEN '$frm_Date' AND '$to_Date' OR '$frm_Date' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_Date' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 
        INNER JOIN `tbl_vehicles` v ON v.`id`=va.`vehicle_id` AND v.`supplier_id`>1 
        INNER JOIN `tbl_invoicestatus` is1 ON is1.`id`=i.`status_id`
        WHERE i.`isdelete`=0 AND i.`week_no`=$weekno AND i.`cid`=".$cid." AND i.`istype`=1 AND i.`weekyear` = $weekyear ".$extraquery; 
        $VehRentFire =  $mysql -> selectFreeRun($VehRentQry);
        $vatFlag = 0;
        $rvDate1="";
        $dt1="";
        $route1='';
        while($VehRentData = mysqli_fetch_array($VehRentFire))
        {
            $vatFlag = $VehRentData['civat'];
            $pikupD = $VehRentData['pickup_date'];
            if($tblresult['rateid']==0 && $tblresult['value']!=NULL && $tblresult['value']!='' && $tblresult['routedate']==$tblresult['date'])
            {
                $route1 = " (".$tblresult['value'].")";
            }
            
            if(strtotime($VehRentData['pickup_date'])<strtotime($frm_Date))
            {
                $pikupD = $frm_Date;
            }
            $i=0;
            while($to_Date>=$pikupD && $pikupD<=$VehRentData['return_date'] && $pikupD<=date("Y-m-d"))
            {
                $net = -$VehRentData['price_per_day'];
                $vat=0;
                if($vatFlag==1){ 
                 $vat = -$VehRentData['price_per_day'] * 0.2;
                }
                
                $total = $net + $vat;
                $pikupD1 = date("d/m/Y",strtotime($pikupD));
                $dt1 .= "<tr>
                        <td>".$VehRentData['invoice_no']."</td>
                        <td>".$VehRentData['dname']."</td>
                        <td>DEDUCTION</td>
                        <td><b>Wk ".$VehRentData['week_no']."-<small>" .$pikupD1. "</small></td>
                        <td>Van Deduction ( ".$VehRentData['registration_number']." )</td>
                        <td>1</td>
                        <td>".$net."</td>
                        <td>";
                        if($vatFlag==1)
                        {
                            $dt1 .=$vat;
                        }
                        else
                        {
                            $dt1 .="-";
                        }
                $dt1 .= "</td>
                        <td>".$total."</td>
                        <td>".$VehRentData['duedate']."</td>
                        <td>".$VehRentData['sname']."</td>
                        </tr>";
                 $pikupD = date('Y-m-d', strtotime($pikupD . ' +1 day'));
                 $i++;
            }
        }
    }


    $return_arr['status']=1;
    $return_arr['dt']=$dt.$dt1;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'exportworkforceDetailsInvoiceTableData') 
{
    header("content-Type: application/json");
    $wikNo = $_POST['wikNo'];
    $wikYear = $_POST['wikYear'];
    $vat = $_POST['vat'];
    $duedate = $_POST['duedate'];
    $status = $_POST['status'];
    $depot_id = $_POST['depot_id'];
    $extraquery = "";
    if($vat==1)
    {
        $extraquery .= " AND c.`vat_number` LIKE '%'";
    }else if($vat==2)
    {
        $extraquery .= " AND c.`vat_number` IS NULL";
    }
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery .= " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }
    if($depot_id!="%")
    {
        $extraquery .= " and i.`depot_id`=".$depot_id;
    }
    if($status!="%")
    {
        $extraquery .= " AND i.`status_id`=".$status;
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
    $sql = "SELECT w.*,i.`invoice_no`, i.`week_no`,i.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`, i.`weekyear`,d.`name` as dname,IF(w.`rateid`!=0,'NA',w.`date`) as routedate,
    IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,
    c.name as username, is1.name as sname,c.`vat_number` as vat_number1  FROM `tbl_contractorinvoice` i 
    INNER JOIN `tbl_user` c ON c.`id`=i.`cid` 
    INNER JOIN `tbl_depot` d ON  i.`depot_id` IN (d.`id`)
    INNER JOIN `tbl_invoicestatus` is1 ON is1.`id`=i.`status_id` 
    INNER JOIN `tbl_workforcetimesheet` w ON w.`wid`=i.`cid` AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`date` BETWEEN i.`from_date` AND i.`to_date` AND w.`value` NOT LIKE '0' AND w.`value` NOT LIKE ''
    INNER JOIN `tbl_paymenttype` p ON p.`id`=w.`rateid` 
    WHERE i.`isdelete`=0 AND i.`week_no`=$wikNo AND i.`istype`=2 AND i.`weekyear` = $wikYear ".$extraquery;
   // echo $sql;
    $tblrow =  $mysql->selectFreeRun($sql);
    $dt="";
    $type = 0;
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    $totalAttendance = 0;
    $route = "";
    $prvDate = "";
    $frm_Date = "";
    $to_Date = "";
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        // if(!empty($tblresult['vat_number1']))
        // {
        //    $vatFlag = 1;
        // }else
        // {
        //     $vatFlag = 0;
        // }
        $vatFlag = $tblresult['civat'];
        //$dueDate = date("d/m/Y", strtotime($tblresult['duedate']));
        if ($tblresult['rateid'] == 0 && $tblresult['value'] != NULL && $tblresult['value'] != '' && $tblresult['date']==$tblresult['routedate']) 
        {
            $route = " (" . $tblresult['value'] . ")";
        }

        if ($tblresult['rateid'] > 0 && $tblresult['value'] != NULL && $tblresult['value'] != '') 
        {
            $adddate = date("d/m/Y", strtotime($tblresult['date']));
            $type = $tblresult['type'];
            if ($type == 1) {
                $typename = 'STANDARD SERVICES';
            } else if ($type == 2) {
                $typename = 'BONUS';
            } else if ($type == 3) {
                $typename = 'DEDUCTION';
            }

            $net = $tblresult['amount'] * $tblresult['value'];
            $vat = 0;
            if ($vatFlag == 1) {
                $vat = ($net * $tblresult['vat']) / 100;
            }
            $neg = "";
            if ($type == 3) {
                $net = -$net;
                $vat = -$vat;
                $neg = "-";
            }
            $total = $net + $vat;
            $dt .= "<tr>
                    <td>".$tblresult['invoice_no']."</td>
                    <td>".$tblresult['username']."</td>
                    <td>".$tblresult['dname']."</td>
                    <td>".$typename."</td>
                    <td><b>Wk ".$tblresult['week_no']."</b></td>
                    <td><small>" .$adddate. "</small></td>
                    <td>".$tblresult['name']."</td>
                    <td>".$route."</td>
                    <td>".$tblresult['value']."</td>
                    <td>".$neg.$tblresult['amount']."<td>";
                    if($vatFlag==1)
                    {
                        $dt .=$vat;
                    }
                    else
                    {
                        $dt .="-";
                    }
            $dt .= "</td>
                    <td>".$total."</td>
                    <td>".$tblresult['duedate']."</td>
                    <td>".$tblresult['sname']."</td>
                    </tr>";

        }
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'exportworkforceInvoiceTableData') 
{
    header("content-Type: application/json");
    $wikNo = $_POST['wikNo'];
    $wikYear = $_POST['wikYear'];
    $vat = $_POST['vat'];
    $duedate = $_POST['duedate'];
    $status = $_POST['status'];
    $depot_id = $_POST['depot_id'];
    $extraquery = "";
    if($vat==1)
    {
        $extraquery .= " AND c.`vat_number` LIKE '%'";
    }else if($vat==2)
    {
        $extraquery .= " AND c.`vat_number` IS NULL";
    }
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery .= " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }
    if($depot_id!="%")
    {
        $extraquery .= " and i.`depot_id`=".$depot_id;
    }
    if($status!="%")
    {
        $extraquery .= " AND i.`status_id`=".$status;
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
    $sql = "SELECT i.`invoice_no`, i.`week_no`, DATE_FORMAT(i.`from_date`, '%d-%m-%Y') as from_date,i.id, 
    DATE_FORMAT(i.`to_date`, '%d-%m-%Y') as to_date, i.`weekyear`,c.arrears,i.`vat` as civat, 
    IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,
    c.name as cname, d.name as dname, is1.name as sname,c.vat_number,
    IF(c.vat_number IS NULL, 'NO', 'YES') as vatStatus 
    FROM `tbl_contractorinvoice` i 
    INNER JOIN `tbl_user` c ON c.id=i.cid 
    INNER JOIN `tbl_depot` d ON i.depot_id IN (d.id)
    INNER JOIN `tbl_invoicestatus` is1 ON is1.id=i.status_id  
    WHERE i.`isdelete`=0 AND i.`week_no`=$wikNo AND i.istype=2 AND i.`weekyear` = $wikYear ".$extraquery;
    //echo $sql;
    $strow =  $mysql->selectFreeRun($sql);
    $dt="";
    while ($statusresult = mysqli_fetch_array($strow)) {
        $totalshow = WorkforceInvoiceTotal($statusresult['id']);
        $vat = 'NO';
        if ($statusresult['civat']==1) {
            $vat = 'YES';
        }
        $dt .= "<tr>
                <td>".$statusresult['invoice_no']."</td>
                <td>".$totalshow."</td>
                <td>".$statusresult['week_no']."</td>
                <td>".$statusresult['from_date']."</td>
                <td>".$statusresult['to_date']."</td>
                <td>".$statusresult['weekyear']."</td>
                <td>".$statusresult['duedate']."</td>
                <td>".$statusresult['cname']."</td>
                <td>".$statusresult['dname']."</td>
                <td>".$statusresult['sname']."</td>
                <td>".$vat."</td>
                <td>".$statusresult['arrears']."</td>
                </tr>";
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'loadWorkforceInvoicestabledata') 
{
    header("content-Type: application/json");
    $wid = $_POST['wid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
    $query = "SELECT c.*, DATE_FORMAT(c.`duedate`,'%D %M%, %Y') as duedate, 
    DATE_FORMAT(c.`update_date`,'%D %M%, %Y') as updatedate,i.name as statusname,i.color as stcolor,i.backgroundcolor as bgcolor 
    FROM `tbl_contractorinvoice` c 
    INNER JOIN tbl_invoicestatus i ON i.id=c.status_id AND i.`isdelete`= 0 AND i.`isactive`= 0 
    WHERE c.`istype`=2 AND c.`isdelete`= 0 AND `cid`=" . $wid . " 
    order by c.`week_no` desc";
    //echo $query;
    $strow =  $mysql->selectFreeRun($query);
    $dt="";
    while ($typeresult = mysqli_fetch_array($strow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }


        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));
        $totalshow = WorkforceInvoiceTotal($typeresult['id']);
        if ($typeresult['status_id'] == 4) {

            $due = 'Paid: ' . $typeresult['updatedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }
        $invkey=base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]);
        $action = "<a href='workforce_invoice.php?invkey=" . $invkey . "' class='delete'><span><b>View</b></span></a>
                    &nbsp;
                   <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";


        $dt .= "<tr>
                <td><b>Â£ ".$totalshow."</b></td>
                <td><span class='label label-secondary' style='color: " . $typeresult['stcolor'] . ";background-color: " . $typeresult['bgcolor'] . "'><b>" . strtoupper($typeresult['statusname']) . "</b></span></td>
                <td><b>Wk " .$typeresult['week_no']."</b> - <small>".$fromdate." >> ".$todate."</small></td>
                <td>".$typeresult['invoice_no']."</td>
                <td>".$vat."</td>
                <td>".$due."</td>
                <td>".$action."</td>
                </tr>";
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorInvoicestabledata') 
{
    header("content-Type: application/json");
    $cid = $_POST['cid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $status = 0;
    $query = "SELECT DISTINCT c.*, DATE_FORMAT(c.`duedate`,'%D %M%, %Y') as duedate, DATE_FORMAT(c.`update_date`,'%D %M%, %Y') as updatedate,i.name as statusname,i.color as stcolor,i.backgroundcolor as bgcolor 
            FROM `tbl_contractorinvoice` c 
            INNER JOIN tbl_invoicestatus i ON i.id=c.status_id AND i.`isdelete`= 0 AND i.`isactive`= 0
            WHERE c.`istype`=1 AND c.`isdelete`= 0 AND c.`cid`=" . $cid . " order by c.`week_no` desc";
    $strow =  $mysql->selectFreeRun($query);
    if ($strow) {
        $status = 1;
        $options = array();
        $j=0;
        while ($typeresult = mysqli_fetch_array($strow)) {
            $vat = '';
            if ($typeresult['vat'] == 1) 
            {
                $statuscls = 'success';
                $statusname = 'Applied';
            } else 
            {
                $statuscls = 'danger';
                $statusname = 'Apply';
            }
            $vat = "<div id='" . $typeresult['id'] . "-td'><button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                onclick=\"Isactivevat('" . $typeresult['id'] . "','" . $typeresult['vat'] . "','validateVat')\">" . $statusname . "</button>
                </div>";
            $invoicestatus = $typeresult['status_id'];
            $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);
            $fromdate = date("d M, Y", strtotime($week_array['week_start']));
            $todate = date("d M, Y", strtotime($week_array['week_end']));
            $invc = new InvoiceAmountClass();
            $totalshow = $invc->ContractorInvoiceTotal($typeresult['invoice_no']);
            $totalshow = $totalshow['finaltotal'];
            if ($typeresult['status_id'] == 4) 
            {
                $due = 'Paid: ' . $typeresult['updatedate'];
            } 
            else 
            {
                if ($todaydate >= $typeresult['duedate']) {
                    $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
                } else {
                    $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
                }
            }
            $invkey=base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]);
            $action = "<a href='contractor_invoice.php?invkey=" . $invkey . "' class='delete'><span><b>View</b></span></a>
                    &nbsp; <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";
            
            $options[]= "<tr>
                        <td><b>Â£ ".$totalshow."</b></td>
                        <td><span class='label label-secondary' style='color: " . $typeresult['stcolor'] . ";background-color: " . $typeresult['bgcolor'] . "'><b>" . strtoupper($typeresult['statusname']) . "</b></span></td>
                        <td><b>Wk " .$typeresult['week_no']."</b> - <small>".$fromdate." >> ".$todate."</small></td>
                        <td>".$typeresult['invoice_no']."</td>
                        <td>".$vat."</td>
                        <td>".$due."</td>
                        <td>".$action."</td>
                        </tr>";
            $j++;
        }
    }

    $mysql->dbDisConnect();
    $response['status'] = $status;
    $response['options'] = $options;
    echo json_encode($response);
}
else if (isset($_POST['action']) && $_POST['action'] == 'getSelectedInvoice') 
{
    $depot = $_POST['did'];
    $statusid = $_POST['statusid'];

    $vat = "";
    if($_POST['vatid']==1)
    {
        $vat = " AND c.`vat_number` LIKE '%'";
    }else if($_POST['vatid']==2)
    {
        $vat = " AND c.`vat_number` IS NULL";
    }

    $duedate = $_POST['duedate'];
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery = " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }

    $wekNo = $_POST['weekNo'];
    if(!empty($_POST['weekNo']))
    {
        $wekNo = " AND i.`week_no`=".$_POST['weekNo'];
    }else{
        $wekNo = "";
    }

    $searchQuery = " ";
    $extraquery = " ";
    if (isset($_POST['search'])) {
        $searchValue=$_POST['search'];
        $searchQuery = " and (i.`invoice_no` LIKE '%" . $searchValue . "%' or c.`name` LIKE '%" . $searchValue . "%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%" . $searchValue . "%') ";
    }
    $noinvc = $_POST['noInvc'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT DISTINCT i.`invoice_no` FROM `tbl_contractorinvoice` i INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL WHERE i.invoice_no NOT LIKE '$noinvc' AND i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' $vat AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery." ".$wekNo." order by i.`id` desc ";
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) 
    {
        $data[] = $typeresult['invoice_no'];
    }
    $mysql->dbDisConnect();
    $dt = implode(",",$data);
    $return_arr['dt']=$dt;
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'getworkforceSelectedInvoice') 
{
    $depot = $_POST['did'];
    $statusid = $_POST['statusid'];

    $vat = "";
    if($_POST['vatid']==1)
    {
        $vat = " AND c.`vat_number` LIKE '%'";
    }else if($_POST['vatid']==2)
    {
        $vat = " AND c.`vat_number` IS NULL";
    }

    $duedate = $_POST['duedate'];
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery = " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }

    $wekNo = $_POST['weekNo'];
    if(!empty($_POST['weekNo']))
    {
        $wekNo = " AND i.`week_no`=".$_POST['weekNo'];
    }else{
        $wekNo = "";
    }

    $searchQuery = " ";
    $extraquery = " ";
    if (isset($_POST['search'])) {
        $searchValue=$_POST['search'];
        $searchQuery = " and (i.`invoice_no` LIKE '%" . $searchValue . "%' or c.`name` LIKE '%" . $searchValue . "%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%" . $searchValue . "%') ";
    }
    $noinvc = $_POST['noInvc'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT DISTINCT i.`invoice_no` FROM `tbl_contractorinvoice` i 
    INNER JOIN `tbl_user` c ON c.`id`=i.`cid`
    INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('".$userid."') 
    AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL 
    WHERE i.invoice_no NOT LIKE '$noinvc' AND i.`depot_id` IN (w.depot_id) 
    AND i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' $vat AND i.`status_id` 
    LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery." ".$wekNo." order by i.`id` desc ";
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) 
    {
        $data[] = $typeresult['invoice_no'];
    }
    $mysql->dbDisConnect();
    $dt = implode(",",$data);
    $return_arr['dt']=$dt;
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'performAction')
{
    $valuse = array();
    $blkIds = explode(",",$_POST['blkIds']);
    if($_POST['act']==2)
    {
        $i=0;
        while($blkIds[$i])
        {
            try{
                system("php sendBulkAction.php ".$blkIds[$i]." >/dev/null >&- >/dev/null &");
            }catch(Exception $e)
            {}
            $i++;
        }
        $valuse[0]['status_id'] = 8;
    }else if($_POST['act']==1)
    {
        $valuse[0]['status_id'] = 2;
    }else if($_POST['act']==3)
    {
        $valuse[0]['status_id'] = 4;
    }
    $valuse[0]['update_date'] = date('Y-m-d H:i:s');
    $usercol = array('status_id');
    $where = " invoice_no = '".implode("' OR invoice_no = '",$blkIds)."'";
    $mysql = new Mysql();
    $mysql->dbConnect();
    $paymentdata =  $mysql -> update('tbl_contractorinvoice',$valuse,$usercol,'update',$where); 
    $mysql->dbDisConnect();
    $return_arr['status']=1;
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'performworkforceAction')
{
    $valuse = array();
    $blkIds = explode(",",$_POST['blkIds']);
    if($_POST['act']==2)
    {
        $i=0;
        while($blkIds[$i])
        {
            try{
                system("php sendWorkforceBulkAction.php ".$blkIds[$i]." >/dev/null >&- >/dev/null &");
            }catch(Exception $e)
            {}
            $i++;
        }
        $valuse[0]['status_id'] = 8;
    }else if($_POST['act']==1)
    {
        $valuse[0]['status_id'] = 2;
    }else if($_POST['act']==3)
    {
        $valuse[0]['status_id'] = 4;
    }
    $valuse[0]['update_date'] = date('Y-m-d H:i:s');
    $usercol = array('status_id');
    $where = " invoice_no = '".implode("' OR invoice_no = '",$blkIds)."'";
    $mysql = new Mysql();
    $mysql->dbConnect();
    $paymentdata =  $mysql -> update('tbl_contractorinvoice',$valuse,$usercol,'update',$where); 
    $mysql->dbDisConnect();
    $return_arr['status']=1;
    echo json_encode($return_arr);
}
else if (isset($_POST['action']) && $_POST['action'] == 'validateVat') {

    header("content-Type: application/json");
    $status = 0;
    $status123 = 0;
    $id = $_POST['id'];
    $mesg = "";
    if(isset($_SESSION['permissioncode'][194]) && $_SESSION['permissioncode'][194]==1)
    {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT c.`id`,c.`vat_number`,ci.status_id FROM `tbl_contractor` c INNER JOIN tbl_contractorinvoice ci ON ci.cid=c.id WHERE ci.id=$id";
    $typerow =  $mysql->selectFreeRun($query);
    $tdatavat = mysqli_fetch_array($typerow);
    if($tdatavat['status_id']!=4 || isset($_SESSION['permissioncode'][195]) && $_SESSION['permissioncode'][195]==1)
    {
    if($tdatavat['vat_number'] != "" && !empty($tdatavat['vat_number']) && $tdatavat['vat_number'] != NULL)
    {
        if ($_POST['status'] == 0) 
        {
            $status = 1;
        }
        else
        {
            $status = 0;
        }

        $valus[0]['vat'] = $status;
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        

        if ($id > 0) {
            $isactivecol = array('vat', 'update_date');
            $where = 'id =' . $id;
            $isactiveupdate = $mysql->update('tbl_contractorinvoice', $valus, $isactivecol, 'update', $where);
            $status = 1;
        }
        $status123=1;
    }else{
        $mesg = "VAT number is not assigned !!!";
    }
    }
    else
    {
        $mesg = "Invoice is already Paid!!!";
    }
    $mysql->dbDisConnect();
}else
{
    $mesg="Permission Denied!!!";
}
    $response['status123'] = $status123;
    $response['status'] = $status;
    $response['mesg'] = $mesg;
    echo json_encode($response);
}
else if (isset($_POST['action']) && $_POST['action'] == 'NewvalidateVat') 
{

    header("content-Type: application/json");
    $status = 0;
    $status123 = 0;
    $id = $_POST['id'];
    $mesg = "";
    if(isset($_SESSION['permissioncode'][194]) && $_SESSION['permissioncode'][194]==1)
    {
        $mysql = new Mysql();
        $mysql->dbConnect();
        $query = "SELECT c.`id`,c.`vat_number`,ci.status_id FROM `tbl_contractor` c INNER JOIN tbl_contractorinvoice ci ON ci.cid=c.id WHERE ci.id=$id";
        $typerow =  $mysql->selectFreeRun($query);
        $tdatavat = mysqli_fetch_array($typerow);
        if($tdatavat['status_id']!=4 || isset($_SESSION['permissioncode'][195]) && $_SESSION['permissioncode'][195]==1)
        {
            if($tdatavat['vat_number'] != "" && !empty($tdatavat['vat_number']) && $tdatavat['vat_number'] != NULL)
            {
                if ($_POST['status'] == 0) 
                {
                    $status = 1;
                }
                else
                {
                    $status = 0;
                }

                $valus[0]['vat'] = $status;
                $valus[0]['update_date'] = date('Y-m-d H:i:s');
                

                if ($id > 0) {
                    $isactivecol = array('vat', 'update_date');
                    $where = 'id =' . $id;
                    $isactiveupdate = $mysql->update('tbl_contractorinvoice', $valus, $isactivecol, 'update', $where);
                    $status = 1;
                }
                $status123=1;
            }
            else
            {
                $mesg = "VAT number is not assigned !!!";
            }
        }
        else
        {
            $mesg = "Invoice is already Paid!!!";
        }
        $mysql->dbDisConnect();
    }
    else
    {
        $mesg="Permission Denied!!!";
    }
    $response['status123'] = $status123;
    $response['status'] = $status;
    $response['mesg'] = $mesg;
    echo json_encode($response);
}
else if (isset($_POST['action']) && $_POST['action'] == 'NewvalidateVatWorkforce') 
{

    header("content-Type: application/json");
    $status = 0;
    $status123 = 0;
    $id = $_POST['id'];
    $mesg = "";
   
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT c.`id`,c.`vat_number`,ci.status_id FROM `tbl_user` c INNER JOIN tbl_contractorinvoice ci ON ci.cid=c.id WHERE ci.id=$id";
    $typerow =  $mysql->selectFreeRun($query);
    $tdatavat = mysqli_fetch_array($typerow);
    if($tdatavat['status_id']!=4 )
    {
        if($tdatavat['vat_number'] != "" && !empty($tdatavat['vat_number']) && $tdatavat['vat_number'] != NULL)
        {
            if ($_POST['status'] == 0) 
            {
                $status = 1;
            }
            else
            {
                $status = 0;
            }

            $valus[0]['vat'] = $status;
            $valus[0]['update_date'] = date('Y-m-d H:i:s');
            

            if ($id > 0) {
                $isactivecol = array('vat', 'update_date');
                $where = 'id =' . $id;
                $isactiveupdate = $mysql->update('tbl_contractorinvoice', $valus, $isactivecol, 'update', $where);
                $status = 1;
            }
            $status123=1;
        }
        else
        {
            $mesg = "VAT number is not assigned !!!";
        }
    }
    else
    {
        $mesg = "Invoice is already Paid!!!";
    }
    $mysql->dbDisConnect();
   
    $response['status123'] = $status123;
    $response['status'] = $status;
    $response['mesg'] = $mesg;
    echo json_encode($response);
}
?>