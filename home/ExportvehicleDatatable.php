<?php
include 'DB/config.php';
include 'base2n.php';
date_default_timezone_set('Europe/London');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userid = $_SESSION['userid'];
if (isset($_POST['action']) && $_POST['action'] == 'exportVehicleTableData') 
{
    header("content-Type: application/json");
    $supplierid = $_POST['supplierid'];
    $status = $_POST['status'];
    $did = $_POST['did'];
    $vtype = $_POST['vtype'];
    $vgroup = $_POST['vgroup'];
    if ($_POST['regno']) {
        $regqry = ' AND v.`registration_number` LIKE ("' . $_POST['regno'] . '")';
    }
    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " and (v.registration_number LIKE '%" . $searchValue . "%')";
    }
   
    $mysql = new Mysql();
    $mysql->dbConnect();
    $return_arr = array();
     switch ($columnName) {
        case 'supplier_id':
            $columnName = 'v.supplier_id';
            break;
        case 'insurance':
            $columnName = 'vi.insurance';
            break;
        case 'group_id':
            $columnName = 'v.group_id';
            break;
        case 'type_id':
            $columnName = 'v.type_id';
            break;
    }
    $query = "";
    if($status=="25")
    {
        $query = "SELECT DISTINCT v.*,DATE_FORMAT(v.`insert_date`,'%D %M%, %Y') as date1,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vt.`name` as typename,vmo.`name` as modelname,vfi.`insurance_company` as insname, avo.`vehicle_ownername` as vehicle_owner
        FROM `tbl_vehicles` v 
        LEFT JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE '%' AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
        LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
        LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
        LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id`
        LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
        LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
        LEFT JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id` 
        LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id` 
        LEFT JOIN `tbl_addvehiclesowner` avo ON avo.`vehicle_id`=v.`id` AND avo.`isdelete`=0 AND avo.`insert_date` = (SELECT MAX(avo1.`insert_date`) FROM `tbl_addvehiclesowner` avo1 WHERE avo1.`vehicle_id`=v.`id`)
        LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance` WHERE v.`depot_id` IS NULL";
    }
    else
    {
    $query = "SELECT DISTINCT v.*,DATE_FORMAT(v.`insert_date`,'%D %M%, %Y') as date1,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vt.`name` as typename,vmo.`name` as modelname,vfi.`insurance_company` as insname, dpt.`name` as dpt_name, avo.`vehicle_ownername` as vehicle_owner,(SELECT c.`name` FROM `tbl_vehiclerental_agreement` v 
                INNER JOIN `tbl_contractor` c ON c.`id`=v.`driver_id`
                WHERE v.`vehicle_id`=v.`id` AND (CURRENT_DATE BETWEEN v.`pickup_date` AND v.`return_date`)) as drivernm FROM `tbl_vehicles` v 
        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
        LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
        LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
        LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id`
        LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
        LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
        LEFT JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id` 
        LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id` 
        LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance`
        LEFT JOIN `tbl_depot` dpt ON dpt.id=v.`depot_id`
        LEFT JOIN `tbl_addvehiclesowner` avo ON avo.vehicle_id=v.id AND avo.isdelete=0 AND avo.insert_date = (SELECT MAX(avo1.insert_date) FROM `tbl_addvehiclesowner` avo1 WHERE avo1.vehicle_id=v.id)
        WHERE v.`isdelete`= 0  AND v.`depot_id` LIKE ('" . $did . "') AND v.`supplier_id` LIKE ('" . $supplierid . "') AND v.`group_id` LIKE ('" . $vgroup . "') AND v.`type_id` LIKE ('" . $vtype . "') AND v.`status` LIKE ('" . $status . "') " . $regqry . $searchQuery;
    }
    $strow =  $mysql->selectFreeRun($query);
    $dt="";
    while ($statusresult = mysqli_fetch_array($strow)) {
        $dt .= "<tr>
                <td>".$statusresult['suppliername']."</td>
                <td>".$statusresult['registration_number']."</td>
                <td>".$statusresult['makename']."/".$statusresult['modelname']."</td>
                <td>".$statusresult['typename']."</td>
                <td>".$statusresult['groupname']."</td>
                <td>".$statusresult['color']."</td>
                <td>".$statusresult['fuel_type']."</td>
                <td>".$statusresult['dpt_name']."</td>
                <td>".$statusresult['insname']."</td>
                <td>".$statusresult['drivernm']."</td>
                <td>".$statusresult['vehicle_owner']."</td>
                <td>NO</td>
                <td>".$statusresult['vin_number']."</td>
                <td>".$statusresult['statusname']."</td>
                <td>".$statusresult['date1']."</td>
                </tr>";
    }
    $return_arr['status']=1;
    $return_arr['dt']=$dt;
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
?>