<?php
include_once('DB/config.php');
include 'base2n.php';
date_default_timezone_set('Europe/London');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userid = $_SESSION['userid'];
if($userid==1)
    {
      $userid="'%'";
    }
if(isset($_POST['action']) && $_POST['action']=="getVehicleSchedule")
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $dpid = $_POST['dpid'];
    $month = $_POST['mont'];
    $year = $_POST['yer'];
    $statusquery = "SELECT DISTINCT v.*,vt.`name` as type,vs.`name` as suppliername FROM `tbl_vehicles` v 
                    INNER JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id`
                    INNER JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id`
                    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ".$userid." AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
                    WHERE v.`isdelete`=0 AND v.`depot_id`=".$dpid;
    $strow =  $mysql->selectFreeRun($statusquery);
    $rowcount = mysqli_num_rows($strow);
    $today = date('d');
    $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $i = 0;
    $trdt = "";
    while ($row = mysqli_fetch_array($strow)) {
        $trdt .= "<tr>
            <td>[" . $row["type"] . "] " . $row["registration_number"] . "(" . $row["suppliername"] . ")</td>";
        for ($i = 1; $i <= $days; $i++) 
            {
                $fulldate = $i . "-" . $month . "-" . $year;
                if ($i == $today && $month==date('m')) 
                {
                    // $trdt .= "<td style='background-color: #f7d694;' onclick=\"AddDeiverInfo('" . $year . "','" . str_pad($month,2,"0",STR_PAD_LEFT) . "','" . str_pad($i,2,"0",STR_PAD_LEFT) . "','" . $row['id'] . "','" . $row['registration_number'] . "','spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "')\" id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                    $trdt .= "<td style='background-color: #f7d694;' id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                } else 
                {
                    // $trdt .= "<td onclick=\"AddDeiverInfo('" . $year . "','" . str_pad($month,2,"0",STR_PAD_LEFT) . "','" . str_pad($i,2,"0",STR_PAD_LEFT) . "','" . $row['id'] . "','" . $row['registration_number'] . "','spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "')\" id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                    $trdt .= "<td id='spandiv_" . $row['id'] . "_" . str_pad($i,2,"0",STR_PAD_LEFT) . "_" . str_pad($month,2,"0",STR_PAD_LEFT) . "_" . $year . "'></td>";
                }
            }
        $trdt .= "</tr>";
        $i++;
        }
        $resp= array();
        $resp["status"] =  1;
        $resp["tr"] =  $trdt;
        $mysql -> dbDisConnect();
        echo json_encode($resp);
}else if(isset($_POST['action']) && $_POST['action']=="dptDriverList")
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $sdpid = $_POST['did'];
    $expquery = "SELECT DISTINCT c.*
                FROM `tbl_contractor` c 
                INNER JOIN `tbl_depot` w ON w.`id`=c.`depot` WHERE w.`id`=$sdpid AND c.`isdelete`=0 AND c.`isactive`=0 AND c.`iscomplated`=1   ORDER BY c.`id` ASC";
    $exprow =  $mysql->selectFreeRun($expquery);
    $resp= array();
    $resp["status"] =  0;
    $dt ="";
    while ($result = mysqli_fetch_array($exprow)) {
        $resp["status"] =  1;
        $dt .= "<option value='".$result['id']."' data-tokens='".$result['name']."'>".$result['name']."</option>";
    }
    $mysql -> dbDisConnect();
    $resp["dt"] =  $dt;
    echo json_encode($resp);
}else if(isset($_POST['action']) && $_POST['action']=="checkAvailableVeh")
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $resp = array();
    $pdt = $_POST['pdt'];
    $rdt = $_POST['rdt'];
    $dpt = $_POST['dpt'];
    $qry = "SELECT DISTINCT v.*,vs.name as sname from `tbl_vehiclerental_agreement` va LEFT JOIN `tbl_vehicles` v ON v.id=va.vehicle_id INNER JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` WHERE v.`id` NOT IN (SELECT DISTINCT va1.vehicle_id from `tbl_vehiclerental_agreement` va1 WHERE ('$pdt' BETWEEN va1.pickup_date AND va1.return_date OR '$rdt' BETWEEN va1.pickup_date AND va1.return_date) AND va1.depot_id=$dpt ) AND v.depot_id=13 AND v.status=4 ORDER BY v.`id` ASC";
    $exprow =  $mysql->selectFreeRun($qry);
    $resp["status"] =  0;
    $title = "ERROR";
    $message = "Sorry! No vehicle available for selected date range.";
    $dt ="";
    while ($result = mysqli_fetch_array($exprow)) {
        $resp["status"] =  1;
        $dt .= "<option value='".$result['id']."''>".$result['registration_number']." ".$result['sname']."</option>";
    }
    $mysql -> dbDisConnect();
    $resp['dt'] = $dt;
    $resp['qry'] = $qry;
    echo json_encode($resp);
}else if(isset($_POST['action']) && $_POST['action']=="deleteMissingAgreement")
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $valusu = array();
    $valusu[0]['isdelete'] = 1;
    $agId = " id=".$_POST['agid'];
    $col = array('isdelete');
    $status=0;
    $title = 'Error';
    $message = 'Some error occured while deleteing.';
    $update = $mysql -> update('tbl_vehiclerental_agreement',$valusu,$col,'update',$agId);
    if($update)
    {
        $status = 1;
        $title = 'Delete';
        $message = 'Data distroyed successfully!!!';
    }
    $mysql -> dbDisConnect();
    $rowcount = mysqli_num_rows($strow);
    $resp["status"] =  $status;
    $resp["title"] =  $title;
    $resp["message"] =  $message;
    echo json_encode($resp);
}else if(isset($_POST['action']) && $_POST['action']=="deleteAgreement")
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $valusu = array();
    $valusu[0]['isdelete'] = 1;
    $agId = " id=".$_POST['agid'];
    $col = array('isdelete');
    $status=0;
    $title = 'Error';
    $message = 'Some error occured while deleteing.';
    $update = $mysql -> update('tbl_vehiclerental_agreement',$valusu,$col,'update',$agId);
    if($update)
    {
        $status = 1;
        $title = 'Delete';
        $message = 'Data distroyed successfully!!!';
    }
    $mysql -> dbDisConnect();
    $rowcount = mysqli_num_rows($strow);
    $resp["status"] =  $status;
    $resp["title"] =  $title;
    $resp["message"] =  $message;
    echo json_encode($resp);

}else
{
$file           =   $_FILES['file']['name'];
$file_image     =   '';
if($_FILES['file']['name']!=""){
    extract($_REQUEST);
    // $db->delete('images',array('img_order'=>555));
    $infoExt        =   getimagesize($_FILES['file']['tmp_name']);
     
    if(strtolower($infoExt['mime']) == 'image/gif' || strtolower($infoExt['mime']) == 'image/jpeg' || strtolower($infoExt['mime']) == 'image/jpg' || strtolower($infoExt['mime']) == 'image/png'){
         
        $file   =   preg_replace('/\\s+/', '-', time().$file);
         
        $path   =   '../uploads/'.$file;
         
        move_uploaded_file($_FILES['file']['tmp_name'],$path);
        $data   =   array(
            'img_name'=>$file,
            'img_order'=>555
        );
        // $insert     =   $db->insert('images',$data);
        // if($insert){ echo 1; } else { echo 0; }
        echo 0;
    }else{
        echo 2;
    } 
    }
}
?>