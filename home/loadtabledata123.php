<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['userid'] == 1) {
    $userid = '%';
} else {
    $userid = $_SESSION['userid'];
}

include 'DB/config.php';
$mysql = new Mysql();
$db = $mysql->dbConnect();
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db, $_POST['search']['value']); // Search value
$mysql->dbDisconnect();


function getStartAndEndDate($week, $year)
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

function ContractorInvoiceTotal($id)
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat` FROM `tbl_contractorinvoice` ci 
                INNER JOIN `tbl_contractortimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` 
                INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid`
                WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=" . $id . " ORDER BY p.`type` ASC";

    $tblrow =  $mysql->selectFreeRun($tblquery);
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {

        $net = $tblresult['amount'] * $tblresult['value'];
        $vat = ($net * $tblresult['vat']) / 100;
        $total = $net + $vat;

        $finaltotal += $total;
        $totalnet += $net;
        $totalvat += $vat;
    }
    $mysql->dbDisConnect();
    return  $finaltotal;
}

function WorkforceInvoiceTotal($id)
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat`
            FROM `tbl_workforceinvoice` ci
            INNER JOIN `tbl_workforcetimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date`
            INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
            WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=" . $id . " ORDER BY p.`type` ASC";

    $tblrow =  $mysql->selectFreeRun($tblquery);
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {

        $net = $tblresult['amount'] * $tblresult['value'];
        $vat = ($net * $tblresult['vat']) / 100;
        $total = $net + $vat;

        $finaltotal += $total;
        $totalnet += $net;
        $totalvat += $vat;
    }
    $mysql->dbDisConnect();
    return  $finaltotal;
}

function ContractorVehicleInvoiceTotal($weekno, $cid)
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $tblquery = "SELECT c.*,v.`registration_number` FROM `tbl_contractorinvoice`c 
                INNER JOIN `tbl_vehicles` v ON v.`id`=c.`cid`
                WHERE c.`isdelete`=0 AND c.`isactive`=0 AND c.`week_no`=" . $weekno . " AND c.cid = " . $cid . " AND c.istype =3 ORDER BY c.`from_date` asc";
    $tblrow =  $mysql->selectFreeRun($tblquery);
    $type = 0;
    $finaltotal = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        $begin = new DateTime($tblresult['from_date']);
        $end   = new DateTime($tblresult['to_date']);

        for ($i = $begin; $i <= $end; $i->modify('+1 day')) {

            $qr = "SELECT * FROM `tbl_vehiclerental_agreement` WHERE '" . $i->format("Y-m-d") . "' between `pickup_date` and `return_date` AND `vid`=" . $tblresult['cid'];
            $qrrow =  $mysql->selectFreeRun($qr);
            $qrresult = mysqli_fetch_array($qrrow);

            $finaltotal += $qrresult['price_per_day'];
            $i++;
        }
    }
    $mysql->dbDisConnect();
    return $finaltotal;
}

function CustomInvoiceTotal($cid)
{
    $mysql = new Mysql();
    $mysql->dbConnect();
    $tblquery = "SELECT * FROM `tbl_custominvoicedata` WHERE `isdelete`=0 AND `isactive`=0 AND `invoice_id`=" . $cid . " ORDER BY `id` desc";
    $tblrow =  $mysql->selectFreeRun($tblquery);
    $finaltotal = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        $net = $tblresult['price'] * $tblresult['quantity'];
        $total = ($net) + (($net * $tblresult['tax']) / 100);
        $finaltotal += $total;
        $i++;
    }
    $mysql->dbDisConnect();
    return $finaltotal;
}


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


if (isset($_POST['action']) && $_POST['action'] == 'loadpaymenttabledata') {
    $istype = $_POST['istype'];
    $isapplies = $_POST['isapplies'];
    if ($_SESSION['userid'] == 1) {
        $qry = '';
    } else {
        $qry = ' AND w.depot_id IN (i.depot_id) ';
    }
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (i.name LIKE '%" . $searchValue . "%' or i.vat LIKE '%" . $searchValue . "%' or i.amount LIKE '%" . $searchValue . "%' or i.type LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT i.id) as allcount from tbl_paymenttype i  INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL WHERE  i.`isdelete` = 0  AND i.`type` LIKE '" . $istype . "' AND i.`applies` LIKE '" . $isapplies . "' AND i.`isdelete` = 0" . $qry;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
    $sql2 = "select count(DISTINCT i.id) as allcount from tbl_paymenttype i INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  WHERE i.`isdelete` = 0  AND i.`type` LIKE '" . $istype . "' AND i.`applies` LIKE '" . $isapplies . "' AND i.`isdelete` = 0" . $qry . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT i.*, 
             DATE_FORMAT(i.`insert_date`,'%D %M%, %Y') as date1, 
             CASE WHEN i.`type`= 1 THEN 'Standard Services' WHEN i.`type`= 2 THEN 'Extra Services' WHEN i.`type`= 3 THEN 'Deduction' END AS `typename` 
             FROM `tbl_paymenttype` i
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE i.`isdelete`= 0 " . $qry . " AND i.`type` LIKE '" . $istype . "' AND i.`applies` LIKE '" . $isapplies . "'" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", i.id desc limit " . $row . "," . $rowperpage;
    $paymentrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($roleresult = mysqli_fetch_array($paymentrow)) {
        $depotids = explode(",", $roleresult['depot_id']);
        $dpt_result = array();
        $i = 0;
        foreach ($depotids as $depotid) {

            $depotrow =  $mysql->selectWhere('tbl_depot', 'id', '=', $depotid, 'int');

            $dptresult = mysqli_fetch_array($depotrow);

            $dpt_result[] = "<li>" . $dptresult['name'] . "</li>";

            $i++;
        }

        $depottype = implode("", $dpt_result);

        if ($roleresult['applies'] == 1) {
            $applies = 'Drivers';
            $apcolor = 'info';
        } else if ($roleresult['applies'] == 2) {
            $applies = 'Workforce';
            $apcolor = 'primary';
        } else {
            $applies = 'Custom';
            $apcolor = 'success';
        }

        if ($roleresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $name = "" . $roleresult['name'] . "<br>
                 <small>" . $roleresult['typename'] . "</small>";

        $applies = "<span class='label label-" . $apcolor . "'>" . $applies . "</span>";

        $depottype = "<ul>" . $depottype . "</ul>";

        $statustd = "<div id='" . $roleresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $roleresult['id'] . "','" . $roleresult['isactive'] . "','Paymenttypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $roleresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $roleresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            'Name' => $name,
            'Applies to' => $applies,
            'amount' => $roleresult['amount'],
            'vat' => $roleresult['vat'],
            'Depots' => $depottype,
            'Date' => $roleresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'Name' => "",
            'Applies to' => "",
            'amount' => "",
            'vat' => "No data found!!!",
            'Depots' => "",
            'Date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadusertabledata') {
    ## Search 
    if ($_SESSION['userid'] == 1) {
        $qry = '';
    } else {
        $qry = ' AND u.`depot` IN (w.`depot_id`)';
    }
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (u.name LIKE '%" . $searchValue . "%' or u.role_type LIKE '%" . $searchValue . "%' or u.email LIKE '%" . $searchValue . "%' or u.contact LIKE '%" . $searchValue . "%' or u.address LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT u.id) as allcount 
            from tbl_user u 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            where u.isdelete = 0 AND u.isactive = 0 AND u.id NOT IN (1) ".$qry;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(DISTINCT u.id) as allcount 
            from tbl_user u 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            where u.isdelete = 0 AND u.isactive = 0 AND u.id NOT IN (1) ".$qry.$searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    // $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_user` WHERE `isdelete`= 0  AND id NOT IN (1)" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $query = "SELECT DISTINCT u.*, DATE_FORMAT(u.`Insert_date`,'%D %M%, %Y') as date1 
              FROM `tbl_user` u
              INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
              WHERE u.`isdelete`= 0  AND u.`id` NOT IN (1) " . $qry . "" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", u.id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','Userisactive')\">" . $statusname . "</button>
                    </div>";


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][37] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            if($userresult['ispasswordchange']==0)
            {
                $showurl ="<a href='#' style='color: #405efb;' onclick=\"resendurl('" . $userresult['id'] . "')\" data-toggle='tooltip' title='Send Email'><span><i class='fas fa-sync fa-lg'></i></span></a> ";
            }
            else
            {
                $showurl = '';
            }
            $update = $showurl." <a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a> ";
        } 

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][38] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = " <a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } 

        $data[] = [
            'roleid' => $userresult['role_type'],
            'name' => $userresult['name'],
            'email' => $userresult['email'],
            'contact' => $userresult['contact'],
            'address' => $userresult['address'],
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $update.$delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'Role' => "",
            'name' => "",
            'email' => "",
            'contact' => "No Record Found!!!",
            'address' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclestatustabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclestatus where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclestatus WHERE isdelete = 0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclestatus` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $statusrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($statusresult = mysqli_fetch_array($statusrow)) {
        if ($statusresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $colordiv = "<span class='label label-secondary' style='padding-top: 9px;background-color:" . $statusresult['colorcode'] . "';> </span> " .  $statusresult['colorcode'];

        $statustd = "<div id='" . $statusresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $statusresult['id'] . "','" . $statusresult['isactive'] . "','Vehiclestatusisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $statusresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $statusresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            'name' => $statusresult['name'],
            'Color' => $colordiv,
            'date' => $statusresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'Color' => "",
            'date' => "No data found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclemaketabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%' or description LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclemake where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclemake WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclemake` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $makerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($makeresult = mysqli_fetch_array($makerow)) {
        if ($makeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $makeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $makeresult['id'] . "','" . $makeresult['isactive'] . "','Vehiclemakeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $makeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $makeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        if ($makeresult['description'] == '') {
            $desc = "--";
        } else {
            $desc = $makeresult['description'];
        }

        $data[] = [
            'name' => $makeresult['name'],
            'description' => $desc,
            'date' => $makeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'description' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclemodeltabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%' or description LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclemodel where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclemodel WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclemodel` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $modelrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($modelresult = mysqli_fetch_array($modelrow)) {
        if ($modelresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $modelresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $modelresult['id'] . "','" . $modelresult['isactive'] . "','Vehiclemodelisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $modelresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $modelresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        if ($modelresult['description'] == '') {
            $desc = "--";
        } else {
            $desc = $modelresult['description'];
        }

        $data[] = [
            'name' => $modelresult['name'],
            'description' => $desc,
            'date' => $modelresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'description' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclegrouptabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%' or rentper_day LIKE '%" . $searchValue . "%' or insuranceper_day LIKE '%" . $searchValue . "%' or total LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclegroup where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclegroup WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclegroup` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $modelrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($modelresult = mysqli_fetch_array($modelrow)) {
        if ($modelresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $modelresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $modelresult['id'] . "','" . $modelresult['isactive'] . "','Vehiclegroupisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $modelresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $modelresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $total = "£" . $modelresult['total'];


        $data[] = [
            'name' => $modelresult['name'],
            'rentper_day' => "£" . $modelresult['rentper_day'],
            'insuranceper_day' => "£" . $modelresult['insuranceper_day'],
            'total' => $total,
            'date' => $modelresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'rentper_day' => "",
            'insuranceper_day' => "",
            'total' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclesuppliertabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclesupplier where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclesupplier WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclesupplier` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $supplierrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($supplierresult = mysqli_fetch_array($supplierrow)) {
        if ($supplierresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $supplierresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $supplierresult['id'] . "','" . $supplierresult['isactive'] . "','Vehiclesupplierisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $supplierresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $supplierresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $supplierresult['name'],
            'date' => $supplierresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicletypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehicletype where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehicletype WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicletype` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicletypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleownertabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehicleowner where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehicleowner WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleowner` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicleownerisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleextratabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehicleextra where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehicleextra WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleextra` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicleextraisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicletabledata') {
    ## Search 
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
        $searchQuery = " and (v.registration_number LIKE '%" . $searchValue . "%' or v.insert_date LIKE '%" . $searchValue . "%')";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql1 = "select count(DISTINCT v.id) as allcount from tbl_vehicles v  INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL    where v.isdelete = 0 AND v.`depot_id` LIKE ('" . $did . "') AND v.`supplier_id` LIKE ('" . $supplierid . "') AND v.`group_id` LIKE ('" . $vgroup . "') AND v.`type_id` LIKE ('" . $vtype . "') AND v.`status` LIKE ('" . $status . "')" . $regqry;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(DISTINCT v.id) as allcount from tbl_vehicles v
     INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
    WHERE v.isdelete = 0 AND v.`depot_id` LIKE ('" . $did . "') AND v.`supplier_id` LIKE ('" . $supplierid . "') AND v.`group_id` LIKE ('" . $vgroup . "') AND v.`type_id` LIKE ('" . $vtype . "') AND v.`status` LIKE ('" . $status . "')" . $regqry . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";
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

    $query = "SELECT DISTINCT v.*,DATE_FORMAT(v.`insert_date`,'%D %M%, %Y') as date1,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vt.`name` as typename,vmo.`name` as modelname,vfi.`insurance_company` as insname
        FROM `tbl_vehicles` v 
        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
        LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
        LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
        LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id`
        LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
        LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` 
        LEFT JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id` 
        LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id` 
        LEFT JOIN `tbl_vehiclefleetinsuarnce` vfi ON vfi.`id`=vi.`insurance`
        WHERE v.`isdelete`= 0  AND v.`depot_id` LIKE ('" . $did . "') AND v.`supplier_id` LIKE ('" . $supplierid . "') AND v.`group_id` LIKE ('" . $vgroup . "') AND v.`type_id` LIKE ('" . $vtype . "') AND v.`status` LIKE ('" . $status . "') " . $regqry . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", v.`id` desc limit " . $row . "," . $rowperpage;
    $vehiclesrow =  $mysql->selectFreeRun($query);
    $todate = date('Y-m-d');
    $data = array();
    while ($typeresult = mysqli_fetch_array($vehiclesrow)) {

        $drqry = "SELECT c.`name` FROM `tbl_vehiclerental_agreement` v 
                INNER JOIN `tbl_contractor` c ON c.`id`=v.`driver_id`
                WHERE v.`vehicle_id`=" . $typeresult['id'] . " AND ('" . $todate . "' BETWEEN v.`pickup_date` AND v.`return_date`)";
        $drsrow =  $mysql->selectFreeRun($drqry);
        $drresult = mysqli_fetch_array($drsrow);

        $extra = "<a href='' class='showdataclr' onclick=\"ShowExtra('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='Add Status'><span><i class='fas fa-bars fa-2x'></i></span></a>";
        $inspection = "<a href='' class='delete'><i class='fa fa-exclamation-triangle'></i> No</a>";
        $statustd = "<span class='label label-secondary' style='background-color:" . $typeresult['colorcode'] . "';> " . $typeresult['statusname'] . "</span>";


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][50] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {

            $supplier = "<span  style='color: blue;' onclick=\"loadpage('" . $typeresult['id'] . "')\">" . $typeresult['suppliername'] . "<br><small>" . $typeresult['makename'] . " " . $typeresult['modelname'] . "</small><span>";


            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\" data-toggle='tooltip' title='Edit'><span><i class='fas fa-edit fa-lg'></i></span></a>

                 <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='Delete'><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][51] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = " <a href='' class='adddata' onclick=\"addstatus('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='Add Status'><span><i class='fas fa-flag fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'supplier_id' => "<b>" . $supplier . "</b>",
            'registration_number' => "<b>" . $typeresult['registration_number'] . "</b>",
            'type_id' => $typeresult['typename'],
            'insurance' => $typeresult['insname'],
            'group_id' => $typeresult['groupname'],
            'owner_id' => '',
            'Driver' => $drresult['name'],
            'Extras' => $extra,
            'Inspected today?' => $inspection,
            'Date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete,
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'supplier_id' => "",
            'registration_number' => '',
            'type_id' => "",
            'insurance' => "",
            'group_id' => "",
            //"No Record Found!!!",
            'owner_id' => "<img src='drivaarpic/vehicles.PNG' alt='' style='max-width: unset;'>",
            'Driver' => '',
            'Extras' => "",
            'Inspected today?' => "",
            'Date' => "",
            'Status' => "",
            'Action' => ""
        ];
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclerenewaltypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclerenewaltype where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclerenewaltype WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclerenewaltype` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $supplierrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($supplierresult = mysqli_fetch_array($supplierrow)) {
        if ($supplierresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $supplierresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $supplierresult['id'] . "','" . $supplierresult['isactive'] . "','Vehiclerenewaltypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $supplierresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $supplierresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $supplierresult['name'],
            'date' => $supplierresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaddvehiclerenewaltypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (renewal_type LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_addvehiclerenewaltype where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_addvehiclerenewaltype WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_addvehiclerenewaltype` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $supplierrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($supplierresult = mysqli_fetch_array($supplierrow)) {
        if ($supplierresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $supplierresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $supplierresult['id'] . "','" . $supplierresult['isactive'] . "','AddVehiclerenewaltypeisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][54] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $supplierresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][55] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $supplierresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'renewal_type' => $supplierresult['renewal_type'],
            'duedate' => $supplierresult['duedate'],
            'date' => $supplierresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'renewal_type' => "",
            'duedate' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleChecklisttabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehiclechecklist where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehiclechecklist WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclechecklist` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehiclechecklistisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "<img src='drivaarpic/checklist.PNG' alt='' width='400' height='300'>",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleexpensetypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleexpensetype where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleexpensetype WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleexpensetype` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicleexpensetypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleDocumenttypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicledocumenttype where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicledocumenttype WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicledocumenttype` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleDocumenttypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleContactstabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or email LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%' or phone LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehiclecontact where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehiclecontact WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclecontact` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleContactsisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'email' => $typeresult['email'],
            'phone' => $typeresult['phone'],
            'street_address' => $typeresult['street_address'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'email' => "",
            'phone' => "",
            'street_address' => "No Record Found!!!",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaddvehicleexpensetabledata') {
    ## Search 
    $vid = $_POST['vid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (expense_type LIKE '%" . $searchValue . "%' or amount LIKE '%" . $searchValue . "%' or label LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleexpense where isdelete = 0 AND vehicle_id=" . $vid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleexpense WHERE isdelete = 0 
    AND vehicle_id=" . $vid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleexpense` WHERE `isdelete`= 0 AND vehicle_id=" . $vid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicleexpenseisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][54] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][55] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }


        $data[] = [
            //'vehicle_id'=>'',
            'expense_type' => $typeresult['expense_type'],
            'label' => $typeresult['label'],
            'amount' => $typeresult['amount'],
            'expensedate' => $typeresult['expensedate'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            //'vehicle_id'=>"",
            'expense_type' => "",
            'label' => "",
            'amount' => "<img src='drivaarpic/noexpense.PNG' alt='' width='130' height='90'>",
            'expensedate' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleServiceTasktabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleservicetask where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleservicetask WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleservicetask` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleServiceTaskisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleservicehistorytabledata') {
    ## Search 
    $vid = $_POST['vid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (servicetask LIKE '%" . $searchValue . "%' or completiondate LIKE '%" . $searchValue . "%' or odometer LIKE '%" . $searchValue . "%' or subtotal LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleservicehistory where isdelete = 0 AND vehicle_id=" . $vid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleservicehistory WHERE isdelete = 0 
    AND vehicle_id=" . $vid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleservicehistory` WHERE `isdelete`= 0 AND vehicle_id=" . $vid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleServiceHistoryisactive')\">" . $statusname . "</button>
                    </div>";


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][54] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][55] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }


        $data[] = [
            'servicetask' => $typeresult['servicetask'],
            'completiondate' => $typeresult['completiondate'],
            'odometer' => $typeresult['odometer'],
            'subtotal' => $typeresult['subtotal'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'servicetask' => "",
            'completiondate' => "",
            'odometer' => "<img src='drivaarpic/carservice.PNG' alt='' width='100%' height='100%'>",
            'subtotal' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleserviceremindertabledata') {
    ## Search 
    $vid = $_POST['vid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (odometer_threshold LIKE '%" . $searchValue . "%' or servicetask LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleservicereminder where isdelete = 0 AND vehicle_id=" . $vid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleservicereminder WHERE isdelete = 0 
    AND vehicle_id=" . $vid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleservicereminder` WHERE `isdelete`= 0 AND vehicle_id=" . $vid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleServiceReminderisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][54] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][55] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $odometer = "<i class='fas fa-calendar'></i>  " . $typeresult['time_interval'] . ' ' .
            $typeresult['type_intervalname'] . " from now </br> <i class='fas fa-tachometer-alt'></i>  " . $typeresult['odometer_threshold'] . " mi";

        $data[] = [
            'servicetask' => $typeresult['servicetask'],
            'odometer_threshold' => $odometer,
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'servicetask' => "",
            'odometer_threshold' => "<img src='drivaarpic/reminder.PNG' alt='' width='330' height='120'>",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaddvehicledocumenttabledata') {
    ## Search 
    $vid = $_POST['vid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (typename LIKE '%" . $searchValue . "%' or name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from   tbl_vehicledocument where isdelete = 0 AND vehicle_id=" . $vid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from   tbl_vehicledocument WHERE isdelete = 0 
    AND vehicle_id=" . $vid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicledocument` WHERE `isdelete`= 0 AND vehicle_id=" . $vid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleDocumentisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $view = "<a target = '_blank' href='" . $webroot . 'uploads/vehicledocument/' . $typeresult['file'] . "' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>";
        $headers = get_headers($webroot . 'uploads/vehicledocument/' . $typeresult['file'], true);
        $data[] = [
            'file' => $typeresult['file'],
            'typename' => $typeresult['typename'],
            'expiredate' => $typeresult['expiredate'],
            'name' => $typeresult['name'],
            'Size' => $headers['Content-Length'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action . $view
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'file' => "",
            'typename' => "",
            'expiredate' => "",
            'name' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'Size' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleaccidenttypetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicletypeaccident where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicletypeaccident WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicletypeaccident` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleAccidenttypeisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "<img src='drivaarpic/accidant1.PNG' alt='' width='400' height='100'>",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleaccidentstagetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehicleaccidentstage where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehicleaccidentstage WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleaccidentstage` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','VehicleAccidentstageisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "<img src='drivaarpic/accidant.PNG' alt='' width='400' height='100'>",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehiclefleetinsurancetabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insurance_company LIKE '%" . $searchValue . "%' or reference_number LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_vehiclefleetinsuarnce where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_vehiclefleetinsuarnce WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehiclefleetinsuarnce` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','Vehicleinsuranceisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>

                    <a href='' class='adddata' onclick=\"addvehicle('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='Add Vehicle'><span><i class='fas fa-plus fa-lg'></i></span></a>";
        $data[] = [
            'insurance_company' => $typeresult['insurance_company'],
            'reference_number' => $typeresult['reference_number'],
            'name' => $typeresult['name'],
            'startdate' => $typeresult['startdate'],
            'expirydate' => $typeresult['expirydate'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'insurance_company' => "",
            'reference_number' => "",
            'name' => "",
            'startdate' => "<img src='drivaarpic/insurance.PNG' alt='' width='400' height='100'>",
            'expirydate' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadworkforcetabledata') {
    ## Search 
    $role_id = $_POST['role_id'];
    $manager_id = $_POST['manager_id'];
    if ($_SESSION['userid'] == 1) {
        $qry = '';
    } else {
        $qry = ' AND u.`depot` IN (w.`depot_id`)';
    }
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (u.name LIKE '%" . $searchValue . "%' or u.role_type LIKE '%" . $searchValue . "%' or u.email LIKE '%" . $searchValue . "%' or u.contact LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT u.id) as allcount 
            from tbl_user u 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            where u.isdelete = 0 AND u.isactive = 0 AND u.id NOT IN (1) ".$qry." AND u.roleid LIKE '".$role_id."' AND u.assignto LIKE '".$manager_id."'";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(DISTINCT u.id) as allcount from tbl_user u INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL WHERE u.isdelete = 0 AND u.isactive = 0 AND u.id NOT IN (1) " . $qry . " AND u.roleid LIKE '".$role_id."' AND u.assignto LIKE '".$manager_id."'".$searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT u.*, DATE_FORMAT(u.`Insert_date`,'%D %M%, %Y') as date1 
              FROM `tbl_user` u
              INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
              WHERE u.`isdelete`= 0  AND u.`id` NOT IN (1) " . $qry . " AND u.roleid LIKE '" . $role_id . "' AND u.assignto LIKE '" . $manager_id . "'" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", u.id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {

        $mngqry =  $mysql->selectWhere('tbl_user', 'id', '=', $userresult['assignto'], 'int');
        $mngresult = mysqli_fetch_array($mngqry);

        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','Workforceisactive')\">" . $statusname . "</button>
                    </div>";

        $assign = "&nbsp;&nbsp;<button type='button' class='isactivebtn btn btn-primary' onclick=\"Isassignbtn('" . $userresult['id'] . "')\">Assign User</button>";


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][34] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $role = "<span style='color: blue;' onclick=\"loadpage('" . $userresult['id'] . "')\">" . $userresult['role_type'] . "</span>";
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][37] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            if($userresult['ispasswordchange']==0)
            {
                $showurl ="<a href='#' style='color: #405efb;' onclick=\"resendurl('" . $userresult['id'] . "')\" data-toggle='tooltip' title='Send Email'><span><i class='fas fa-sync fa-lg'></i></span></a> ";
            }
            else
            {
                $showurl = '';
            }
            $update = $showurl." <a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a> ";
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][38] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        }

        $data[] = [
            'roleid' => $role,
            'name' => $userresult['name'],
            'manager' => $mngresult['name'],
            'email' => $userresult['email'],
            'contact' => $userresult['contact'],
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete . $assign
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'roleid' => "",
            'name' => "",
            'manager' => "",
            'email' => "",
            'contact' => "No Record Found!!!",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadworkforcematrictabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_workforcemartic where isdelete = 0 AND isactive = 0 AND `userid` LIKE ('" . $userid . "')";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_workforcemartic WHERE isdelete = 0 AND isactive = 0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcemartic` WHERE `isdelete`= 0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
                    <a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            'name' => $userresult['name'],
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
}
// else if(isset($_POST['action']) && $_POST['action'] == 'loadworkforcetasktabledata')
// {
//     ## Search 
//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (name LIKE '%".$searchValue."%' or insert_date LIKE '%".$searchValue."%') ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();

//     $sql1 = "select count(*) as allcount from tbl_workforcetask where isdelete = 0 AND isactive = 0 AND `userid` LIKE ('".$userid."')";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];
//     $sql2 = "select count(*) as allcount from tbl_workforcetask WHERE isdelete = 0 AND isactive = 0 AND `userid` LIKE ('".$userid."')".$searchQuery;
//     $sel =  $mysql -> selectFreeRun($sql2);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecordwithFilter = $records['allcount'];
//     $ord="";

//     $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcetask` WHERE `isdelete`= 0 AND `userid` LIKE ('".$userid."')".$searchQuery." order by ".$columnName." ".$columnSortOrder. ", id desc limit ".$row.",".$rowperpage;
//     $userrow =  $mysql -> selectFreeRun($query);
//     $data = array();
//     while($userresult = mysqli_fetch_array($userrow))
//     {
//         if($userresult['isactive']==0)
//         {
//             $statuscls = 'success';
//             $statusname = 'Active';
//         }
//         else
//         {
//             $statuscls = 'danger';
//             $statusname = 'Inactive';
//         }

//         $statustd= "<div id='".$userresult['id']."-td'>
//                         <button type='button' class='isactivebtn btn btn-".$statuscls."' 
//                         onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','WorkforceMetricisactive')\">".$statusname."</button>
//                     </div>";

//         $action = "<a href='#' class='edit' onclick=\"edit('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

//                     <a href='#' class='delete' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

//         $date = strtotime($userresult['duedate']);
//         $duedate = date('d M, Y', $date);


//         $data[] = [
//                     'name'=>$userresult['name'],
//                     'assignee_type'=>$userresult['assignee_type'],
//                     'duedate'=>"<i class='fas fa-calendar-alt'></i>  ".$duedate,
//                     'date'=>$userresult['date1'],
//                     'Status'=>$statustd,
//                     'Action'=>$action
//                   ];
//     }
//     if(count($data)==0)
//     {
//         $data[] = [
//                 'name'=>"",
//                 'assignee_type'=>"",
//                 'duedate'=>"No Record Found!!!",
//                 'date'=>"",
//                 'Status'=>"",
//                 'Action'=>""];
//             $i++;
//     }
// }
else if (isset($_POST['action']) && $_POST['action'] == 'loaddepotTargettabledata') {
    ## Search 
    $did = $_POST['did'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (metric_type LIKE '%" . $searchValue . "%' or target LIKE '%" . $searchValue . "%' or threshold LIKE '%" . $searchValue . "%'  or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_depottarget where isdelete = 0 AND depot_id=" . $did;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_depottarget WHERE isdelete = 0 
    AND depot_id=" . $did . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_depottarget` WHERE `isdelete`= 0 AND depot_id=" . $did . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','DepotTargetisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][40] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][40] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'metric_type' => $typeresult['metric_type'],
            'target' => $typeresult['target'],
            'threshold' => $typeresult['threshold'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'metric_type' => "",
            'target' => "No Data Found!!",
            'threshold' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadWorkforceAttendancetabledata') {
    ## Search 
    $wid = $_POST['wid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (starts LIKE '%" . $searchValue . "%' or end LIKE '%" . $searchValue . "%'  or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_workforceattendance where isdelete = 0 AND workforce_id=" . $wid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_workforceattendance WHERE isdelete = 0 
    AND workforce_id=" . $wid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforceattendance` WHERE `isdelete`= 0 AND workforce_id=" . $wid . $searchQuery . " order by id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        // $startdate = date_create($typeresult['starts']);
        // $enddate = date_create($typeresult['end']);
        // $diff = date_diff($startdate, $enddate);
        // $duration = $diff->format("%a days".' - '."%H:%i:%s");

        $duration = GetAttendanceTime($typeresult['starts'], $typeresult['end']);
        if ($typeresult['type'] == 1) {
            $activity = 'Work';
        } else {
            $activity = 'Lunch Break';
        }
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','WorkforceAttendanceisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][35] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][35] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'Activity' => $activity,
            'starts' => $typeresult['starts'],
            'end' => $typeresult['end'],
            'Duration' => $duration,
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'Activity' => "",
            'starts' => "",
            'end' => "No Data Found!!",
            'Duration' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadWorkforceAssetstabledata') {
    ## Search 
    $wid = $_POST['wid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (price LIKE '%" . $searchValue . "%' or DATE_FORMAT(`insert_date`,'%D %M%, %Y') LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_workforceassets where isdelete = 0 AND wid=" . $wid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_workforceassets WHERE isdelete = 0 AND wid=" . $wid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforceassets` WHERE `isdelete`= 0 AND wid=" . $wid . $searchQuery . " order by id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {

        $cntquery = "SELECT * FROM `tbl_financeassets` WHERE id=" . $typeresult['asset_id'];
        $cntfire = $mysql->selectFreeRun($cntquery);
        $cntresult = mysqli_fetch_array($cntfire);

        $data[] = [
            'type' => $cntresult['type_name'],
            'name' => $cntresult['name'],
            'identifier' => '',
            'price' => $cntresult['price'],
            'assignon' => '',
            'date' => $typeresult['date1'],
            'Action' => ''
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'type' => "",
            'name' => "",
            'identifier' => "No Data Found!!",
            'price' => "",
            'assignon' => "",
            'date' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadonboardingtabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_onboarding where isdelete = 0 AND isactive = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_onboarding WHERE isdelete = 0 AND isactive = 0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_onboarding` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','Onboardingisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $date = strtotime($userresult['duedate']);
        $duedate = date('d M, Y', $date);


        $data[] = [
            'name' => $userresult['name'],
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'date' => "No Record Found!!!",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractortabledata') {
    ## Search 
    $depotid = $_POST['did'];
    $statusid = $_POST['statusid'];

    if ($_POST['emailsearch']) {
        $eml = " AND c.`email` LIKE '%" . $_POST['emailsearch'] . "%'";
    }
    if ($_POST['vehicle'] == 1) {
        $vehicleassign = " INNER JOIN `tbl_vehiclerental_agreement` av ON av.`driver_id`=c.`id` AND CURRENT_DATE() BETWEEN av.pickup_date AND av.return_date";
    } else if ($_POST['vehicle'] == 2) {
        $vehicleassign = " LEFT JOIN `tbl_vehiclerental_agreement` av ON av.`driver_id`!=c.`id` AND CURRENT_DATE() BETWEEN av.pickup_date AND av.return_date";
    }
    if($_POST['vatid']=='true')
    {
        $vatqry = " AND c.`vat_number` IS NOT NULL";
    }
    if($_POST['istype']=='true')
    {
        $istype = " AND c.`type`= 1 ";
    }
    if($_POST['iscmp']=='true')
    { 
        $iscmp = " AND c.`type`= 2 ";
    }

    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (c.name LIKE '%" . $searchValue . "%' or c.vat_number LIKE '%" . $searchValue . "%' or c.email LIKE '%" . $searchValue . "%' or c.company_reg LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT c.id) as allcount from tbl_contractor c
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') " . $vehicleassign . "  AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE c.`depot` LIKE '" . $depotid . "' AND c.`isactive` LIKE '" . $statusid . "' AND c.`isdelete`=0 AND c.`depot` IN (w.depot_id)" . $eml . $vatqry . $istype . $iscmp;

    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(DISTINCT c.id) as allcount from tbl_contractor c
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') " . $vehicleassign . "  AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE c.`depot` LIKE '" . $depotid . "' AND c.`isactive` LIKE '" . $statusid . "' AND c.`isdelete`=0 AND c.`depot` IN (w.depot_id)" . $eml . $vatqry . $istype . $iscmp . $searchQuery;

    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT c.*, DATE_FORMAT(c.`Insert_date`,'%D %M%, %Y') as date1,a.`name` as arrearweek 
            FROM `tbl_contractor` c 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') " . $vehicleassign . "  AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            LEFT JOIN `tbl_arrears` a ON a.`id`=c.`arrears`
            WHERE c.`depot` LIKE '" . $depotid . "' AND c.`isactive` LIKE '" . $statusid . "' AND c.`isdelete`=0 AND c.`depot` IN (w.depot_id)" . $eml . $vatqry . $istype . $iscmp . $searchQuery . " order by c." . $columnName . " " . $columnSortOrder . ", c.id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
            $isdisabled = '';
        } else if ($userresult['isactive'] == 1) {
            $statuscls = 'danger';
            $statusname = 'Inactive';
            $isdisabled = "disabled";
        } else if ($userresult['isactive'] == 2) {
            $statuscls = 'info';
            $statusname = 'Onboarding';
            $isdisabled = "disabled";
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][9] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' " . $isdisabled . " class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','Contractorisactive')\">" . $statusname . "</button>
                    </div>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][5] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) 
        {
            $name = "<span  style='color: blue;' onclick=\"loadpage('" . $userresult['id'] . "')\">" . $userresult['name'] . "</span>";
        } 

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][7] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            if($userresult['ispasswordchange']==0 && $userresult['isactive']==0)
            {
                $showurl ="<a href='#' style='color: #405efb;' onclick=\"resendurl('" . $userresult['id'] . "')\" data-toggle='tooltip' title='Send Email'><span><i class='fas fa-sync fa-lg'></i></span></a> ";
            }
            else
            {
                $showurl = '';
            } 
        }

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][7] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            if($userresult['ispasswordchange']==0 && $userresult['isactive']==2)
            {
                $showonboardurl =" <a href='#' style='color: #405efb;' onclick=\"resendonboardurl('" . $userresult['id'] . "')\" data-toggle='tooltip' title='Send Onboard Email'><span><i class='fas fa-sync fa-lg'></i></span></a> ";
            }
            else
            {
                $showonboardurl = '';
            }

             $update = $showurl.$showonboardurl." <a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a> ";
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][8] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = " <a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        }

        $data[] = [
            'name' => $name,
            'email' => $userresult['email'],
            'company_name' => $userresult['company_name'],
            'NINO' => $userresult['ni_number'],
            'utr' => $userresult['utr'],
            'vat_number' => $userresult['vat_number'],
            'depot_type' => $userresult['depot_type'],
            'Arrears' => $userresult['arrearweek'],
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $update . $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'email' => "",
            'company_name' => "",
            'NINO' => "",
            'utr' => "",
            'vat_number' => "",
            'depot_type' => "No Record Found!!!",
            'Arrears' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorOnboardtabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (c.name LIKE '%" . $searchValue . "%' or c.email LIKE '%" . $searchValue . "%' or c.company_reg LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_contractor where isdelete = 0 AND isactive = 0 AND `userid` LIKE ('" . $userid . "')";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_contractor WHERE isdelete = 0 AND isactive = 0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT c.*, DATE_FORMAT(c.`Insert_date`,'%D %M%, %Y') as date1,a.`name` as arrearweek  
              FROM `tbl_contractor` c
              LEFT JOIN `tbl_arrears` a ON a.`id`=c.`arrears`
              WHERE c.`isdelete`= 0 AND c.`iscomplated`=0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", c.id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][9] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','Contractorisactive')\">" . $statusname . "</button>
                    </div>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }




        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][7] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $update = "<a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }


        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][8] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'name' => $userresult['name'],
            'email' => $userresult['email'],
            'company_name' => $userresult['company_name'],
            'NINO' => $userresult['ni_number'],
            'utr' => $userresult['utr'],
            'depot_type' => $userresult['depot_type'],
            'Arrears' => $userresult['arrearweek'],
            'date' => $userresult['date1'],
            'Action' => ''
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'email' => "",
            'company_name' => "",
            'NINO' => "",
            'utr' => "",
            'depot_type' => "No Record Found!!!",
            'Arrears' => "",
            'date' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorCompanytabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or company_reg LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_contractorcompany where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_contractorcompany WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorcompany` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','ContractorCompanyisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'name' => $typeresult['name'],
            'company_reg' => $typeresult['company_reg'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'company_reg' => "No Data Found!..",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorAttendancetabledata') {
    ## Search 
    $cid = $_POST['cid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (starts LIKE '%" . $searchValue . "%' or end LIKE '%" . $searchValue . "%'  or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_contractorattendance where isdelete = 0 AND contractor_id=" . $cid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_contractorattendance WHERE isdelete = 0 
    AND contractor_id=" . $cid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorattendance` WHERE `isdelete`= 0 AND contractor_id=" . $cid . $searchQuery . " order by id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {

        $duration = GetAttendanceTime($typeresult['starts'], $typeresult['end']);


        if ($typeresult['type'] == 1) {
            $activity = 'Work';
        } else {
            $activity = 'Lunch Break';
        }

        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','ContractorAttendanceisactive')\">" . $statusname . "</button>
                    </div>";

        if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][35] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
            $delete = "<a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        } else {
            if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
                header("location: login.php");
            } else {
                header("location: login.php");
            }
        }

        $data[] = [
            'activity' => $activity,
            'starts' => $typeresult['starts'],
            'end' => $typeresult['end'],
            'Duration' => $duration,
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $delete
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'activity' => "",
            'starts' => "",
            'end' => "No Data Found!!",
            'Duration' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorAssetstabledata') {
    ## Search 
    $cid = $_POST['cid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (price LIKE '%" . $searchValue . "%'  or DATE_FORMAT(`insert_date`,'%D %M%, %Y') LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_contractorassets where isdelete = 0 AND cid=" . $cid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_contractorassets WHERE isdelete = 0 AND cid=" . $cid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractorassets` WHERE `isdelete`= 0 AND cid=" . $cid . $searchQuery . " order by id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $cntquery = "SELECT * FROM `tbl_financeassets` WHERE id=" . $typeresult['asset_id'];
        $cntfire = $mysql->selectFreeRun($cntquery);
        $cntresult = mysqli_fetch_array($cntfire);

        $data[] = [
            'type' => $cntresult['type_name'],
            'name' => $cntresult['name'],
            'identifier' => '',
            'price' => $cntresult['price'],
            'assignon' => '',
            'date' => $typeresult['date1'],
            'Action' => ''
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'type' => "",
            'name' => "",
            'identifier' => "No Data Found!!",
            'price' => "",
            'assignon' => "",
            'date' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaddcontractordocumenttabledata') {
    ## Search 
    $cid = $_POST['cid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (typename LIKE '%" . $searchValue . "%' or name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_contractordocument where isdelete = 0 AND contractor_id=" . $cid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_contractordocument WHERE isdelete = 0 
    AND contractor_id=" . $cid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_contractordocument` WHERE `isdelete`= 0 AND contractor_id=" . $cid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','ContractorDocumentisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $view = "<a target = '_blank' href='" . $webroot . 'uploads/contractordocument/' . $typeresult['file'] . "' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>";
        $headers = get_headers($webroot . 'uploads/contractordocument/' . $typeresult['file'], true);

        $data[] = [
            'file' => $typeresult['file'],
            'typename' => $typeresult['typename'],
            'expiredate' => $typeresult['expiredate'],
            'name' => $typeresult['name'],
            'Size' => $headers['Content-Length'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action . $view
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'file' => "",
            'typename' => "",
            'expiredate' => "",
            'name' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'Size' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaddworkforcedocumenttabledata') {
    ## Search 
    $wid = $_POST['wid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (typename LIKE '%" . $searchValue . "%' or name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from  tbl_workforcedocument where isdelete = 0 AND workforce_id=" . $wid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from  tbl_workforcedocument WHERE isdelete = 0 
    AND workforce_id=" . $wid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcedocument` WHERE `isdelete`= 0 AND workforce_id=" . $wid . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $typeresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $typeresult['id'] . "','" . $typeresult['isactive'] . "','WorkforceDocumentisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $view = "<a target = '_blank' href='" . $webroot . 'uploads/workforcedocument/' . $typeresult['file'] . "' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>";
        $headers = get_headers($webroot . 'uploads/workforcedocument/' . $typeresult['file'], true);

        $data[] = [
            'file' => $typeresult['file'],
            'typename' => $typeresult['typename'],
            'expiredate' => $typeresult['expiredate'],
            'name' => $typeresult['name'],
            'Size' => $headers['Content-Length'],
            'date' => $typeresult['date1'],
            'Status' => $statustd,
            'Action' => $action . $view
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'file' => "",
            'typename' => "",
            'expiredate' => "",
            'name' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'Size' => "",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadcontractorexpiringdocumenttabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or depotname LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_depot where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_depot WHERE isdelete = 0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT d.`name` as depotname,c.`name`,c.`id` as id
            FROM `tbl_depot` d 
            INNER JOIN `tbl_contractor` c ON c.`depot`=d.`id` 
            WHERE d.`isdelete`= 0 AND d.`isactive` = 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", d.name desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {

        $subquery = "SELECT * FROM `tbl_contractordocument` WHERE `contractor_id` =" . $typeresult['id'];
        $subrow = $mysql->selectFreeRun($subquery);
        $table = array();
        while ($result = mysqli_fetch_array($subrow)) {
            $table[] =  "<tr><td>" . $result['file'] . "</td><td><i class='fa fa-exclamation-triangle' style='color: red;'></i>" . $result['expiredate'] . "</td><td><button type='button' class='btn btn-info'><i class='fas fa-sync-alt'></i> Renewed</button></td></tr>";
        }

        //$tbldata = "<table>".$table."</table>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'depot' => "<b>" . $typeresult['depotname'] . "</b>",
            'name' => "<b>" . $typeresult['name'] . "</b>",
            'file' => $table,
            // 'expires'=>'',
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'depot' => "",
            'name' => "No Record Found!!!",
            'file' => "",
            // 'expires'=>"",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleInspectiontabledata') {
    ## Search 
    $depot = $_POST['depot'];
    if ($depot == "") {
        $depot = "%";
    }
    $vehicle = $_POST['vehicle'];
    if ($vehicle == "") {
        $vehicle = "%";
    }
    $inspectiondate = $_POST['inspectiondate'];
    if ($inspectiondate == "") {
        $inspectiondate = "%";
    }

    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (v.`odometer` LIKE '%" . $searchValue . "%' or v.`registration_number` LIKE '%" . $searchValue . "%' or v.`depot_id` LIKE '%" . $searchValue . "%' or vi.`insert_date` LIKE '%" . $searchValue . "%') ";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();

    $vehicle_id = $_SESSION['vid'];

    $sql2 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
     INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
     WHERE vi.`isdelete`=0 AND vi.`isactive`=0 AND vi.`odometer` IS NOT NULL AND cast(vi.`insert_date` as Date) LIKE cast('" . $inspectiondate . "' as Date) AND v.`registration_number` LIKE ('" . $vehicle . "') AND v.`depot_id` LIKE ('" . $depot . "') AND v.`userid` LIKE ('" . $userid . "') AND vi.`vehicle_id`=$vehicle_id";
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql3 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id` 
    WHERE vi.`isdelete` = 0 AND vi.`isactive` = 0 AND vi.`odometer` IS NOT NULL AND vi.`insert_date` LIKE ('" . $inspectiondate . "') AND v.`registration_number` LIKE ('" . $vehicle . "') AND v.`depot_id` LIKE ('" . $depot . "') AND v.`userid` LIKE ('" . $userid . "') AND vi.`vehicle_id`=$vehicle_id" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql3);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";
    $currentdate = date('d-m-Y');

    $query = "SELECT vi.*,v.`registration_number`,s.`name` as suppliername,v.`userid`, DATE_FORMAT(vi.`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id`= v.`supplier_id`
    WHERE vi.`isdelete`= 0 AND vi.`isactive` = 0 AND vi.`odometer` IS NOT NULL AND v.`registration_number` LIKE ('" . $vehicle . "') AND v.`depot_id` LIKE ('" . $depot . "') AND vi.`insert_date` LIKE ('" . $inspectiondate . "') AND v.`userid` LIKE ('" . $userid . "') AND vi.`vehicle_id`=$vehicle_id";
    $userrow =  $mysql->selectFreeRun($query);


    $data = array();

    $chklist = "SELECT COUNT(*) AS ct1 FROM tbl_vehiclechecklist WHERE `isdelete`= 0";
    $chkfire = $mysql->selectFreeRun($chklist);
    $fetch = mysqli_fetch_array($chkfire);
    $row = (int)$fetch['ct1'] + 1;

    while ($userresult = mysqli_fetch_array($userrow)) {
        // $vid = $userresult['vehicle_id'];
        $oInsertId = $userresult['odometerInsert_date'];

        $entry = "SELECT COUNT(*) AS cnt2 FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "'";
        $chkentry = $mysql->selectFreeRun($entry);
        $fetch1 = mysqli_fetch_array($chkentry);
        $result = (int)$row - (int)$fetch1['cnt2'];
        if ($result == 0) {
            $sql3 = "SELECT * FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "' AND answer_type=0";
            $fire3 = $mysql->selectFreeRun($sql3);
            if ($fire3) {
                $rowcount = mysqli_num_rows($fire3);

                if ($rowcount > 0) {
                    $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
                } else {
                    $chek = "<span style='color: green;'><i class='fa fa-check-circle' aria-hidden='true'></i> All Check Passed</span>";
                }
            }
        } else {

            $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
        }



        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='vehicleinspection_showdetails.php?id=" . $userresult['id'] . "'' style='color: #03a9f3;'><span>View</span></a>";

        $data[] = [
            'date' => $userresult['date1'],
            'vehicle_id' => $userresult['registration_number'],
            'user' => $userresult['suppliername'],
            'odometer' => $userresult['odometer'],
            'check' => $chek,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'date' => "",
            'vehicle_id' => "",
            'user' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'odometer' => "",
            'check' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadtodayinspectiontabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (vi.`odometer` LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $dbc = $mysql->dbConnect();

    $sql2 = "SELECT DISTINCT count(vi.`id`) as allcount
             FROM `tbl_vehicleinspection` vi
             INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE vi.`isdelete`= 0 AND DATE(vi.`odometerInsert_date`) = DATE(NOW()) AND vi.`odometer` IS NOT NULL";
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql3 = "SELECT DISTINCT count(vi.`id`) as allcount
             FROM `tbl_vehicleinspection` vi
             INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE vi.`isdelete`= 0 AND DATE(vi.`odometerInsert_date`) = DATE(NOW()) AND vi.`odometer` IS NOT NULL" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql3);
    $records = mysqli_fetch_assoc($sel);

    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT vi.*, DATE_FORMAT(vi.`insert_date`,'%D %M%, %Y') as date1,d.`name` as depotname 
             FROM `tbl_vehicleinspection` vi
             INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             LEFT JOIN `tbl_depot` d ON d.`id`=v.`depot_id`
             WHERE vi.`isdelete`= 0 AND DATE(vi.`odometerInsert_date`) = DATE(NOW()) AND vi.`odometer` IS NOT NULL";
    $userrow =  $mysql->selectFreeRun($query);

    $data = array();

    $chklist = "SELECT COUNT(*) AS ct1 FROM tbl_vehiclechecklist WHERE `isdelete`= 0";
    $chkfire = $mysql->selectFreeRun($chklist);
    $fetch = mysqli_fetch_array($chkfire);
    $row = (int)$fetch['ct1'] + 1;

    while ($userresult = mysqli_fetch_array($userrow)) {
        $vid = $userresult['vehicle_id'];
        $oInsertId = $userresult['odometerInsert_date'];

        $sql = "SELECT * FROM `tbl_vehicles` WHERE id = $vid";
        $fire = $mysql->selectFreeRun($sql);
        $fetch = mysqli_fetch_array($fire);
        $vehicle = $fetch['registration_number'];
        $supId = $fetch['supplier_id'];

        $sql1 = "SELECT * FROM `tbl_vehiclesupplier` WHERE id = $supId";
        $fire1 = $mysql->selectFreeRun($sql1);
        $fetch1 = mysqli_fetch_array($fire1);
        $user = $fetch1['name'];


        $entry = "SELECT COUNT(*) AS cnt2 FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "' ";
        $chkentry = $mysql->selectFreeRun($entry);
        $fetch1 = mysqli_fetch_array($chkentry);

        $result = (int)$row - (int)$fetch1['cnt2'];

        if ($result == 0) {
            $sql3 = "SELECT * FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "' AND answer_type=0";
            $fire3 = $mysql->selectFreeRun($sql3);
            if ($fire3) {
                $rowcount = mysqli_num_rows($fire3);

                if ($rowcount > 0) {
                    $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
                } else {
                    $chek = "<span style='color: green;'><i class='fa fa-check-circle' aria-hidden='true'></i> All Check Passed</span>";
                }
            }
        } else {

            $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
        }

        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "";

        $data[] = [
            'depot' => $userresult['depotname'],
            'date' => $userresult['date1'],
            'vehicle_id' => $vehicle,
            'user' => $user,
            'odometer' => $userresult['odometer'],
            'check' => $chek,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'depot' => "",
            'date' => "",
            'vehicle_id' => "No Record Found!!!",
            'user' => "",
            'odometer' => "",
            'check' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorInvoicestabledata') {
    ## Search 
    $cid = $_POST['cid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (CAST(`invoice_no` as VARCHAR(6)) LIKE '%" . $searchValue . "%' or CAST (`week_no` AS VARCHAR(30)) LIKE '%" . $searchValue . "%' or `insert_date` LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_contractorinvoice where isdelete = 0 AND `istype`=1 AND cid=" . $cid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_contractorinvoice WHERE isdelete = 0 AND `istype`=1 AND cid=" . $cid;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`duedate`,'%D %M%, %Y') as duedate, DATE_FORMAT(`update_date`,'%D %M%, %Y') as updatedate FROM `tbl_contractorinvoice` WHERE `istype`=1 AND `isdelete`= 0 AND `cid`=" . $cid . " order by `id` desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }

        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);

        $totalshow = ContractorInvoiceTotal($typeresult['id']);
        $statusname = strtoupper($stresult['name']);
        if ($typeresult['status_id'] == 4) {
            $due = 'Paid: ' . $typeresult['updatedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }

        $action = "<a href='contractor_invoice.php?id=" . $typeresult["id"] . "' class='delete'><span><b>View</b></span></a>
                    &nbsp;
                   <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'total' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            // 'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
            'invoice_no' => $typeresult['invoice_no'],
            'vat' => $vat,
            'due' => $due,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'total' => "",
            'status' => "",
            // 'period'=>"",
            'invoice_no' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'vat' => "",
            'due' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadWorkforceInvoicestabledata') {
    ## Search 
    $wid = $_POST['wid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (CAST(`invoice_no` as VARCHAR(6)) LIKE '%" . $searchValue . "%' or CAST (`week_no` AS VARCHAR(30)) LIKE '%" . $searchValue . "%' or `insert_date` LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_contractorinvoice where isdelete = 0 AND `istype`=2 AND cid=" . $wid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_contractorinvoice WHERE isdelete = 0 AND `istype`=2 AND cid=" . $wid;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`duedate`,'%D %M%, %Y') as duedate, DATE_FORMAT(`update_date`,'%D %M%, %Y') as updatedate FROM `tbl_contractorinvoice` WHERE `istype`=2 AND `isdelete`= 0 AND `cid`=" . $wid . " order by `id` desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);

    $todaydate = date('Y-m-d');
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }


        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);

        $totalshow = WorkforceInvoiceTotal($typeresult['id']);
        $statusname = strtoupper($stresult['name']);
        if ($typeresult['status_id'] == 4) {
            $due = 'Paid: ' . $typeresult['updatedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }

        $action = "<a href='workforce_invoice.php?id=" . $typeresult['id'] . "' class='delete'><span><b>View</b></span></a>&nbsp;
        <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'total' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            //'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
            'invoice_no' => $typeresult['invoice_no'],
            'vat' => $vat,
            'due' => $due,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'total' => "",
            'status' => "",
            //'period'=>"",
            'invoice_no' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'vat' => "",
            'due' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadContractorVehicleInvoicestabledata') {
    ## Search 
    $cid = $_POST['cid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (CAST(`invoice_no` as VARCHAR(6)) LIKE '%" . $searchValue . "%' or CAST (`week_no` AS VARCHAR(30)) LIKE '%" . $searchValue . "%' or `insert_date` LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT COUNT(DISTINCT c.`id`) as allcount FROM `tbl_vehiclerental_agreement` a INNER JOIN `tbl_contractorinvoice` c ON c.`cid`=a.`vehicle_id` WHERE  c.`istype`=3 AND c.`isdelete`=0 AND c.`isactive`=0 AND a.`driver_id`=" . $cid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT COUNT(DISTINCT c.`id`) as allcount FROM `tbl_vehiclerental_agreement` a INNER JOIN `tbl_contractorinvoice` c ON c.`cid`=a.`vehicle_id` WHERE  c.`istype`=3 AND c.`isdelete`=0 AND c.`isactive`=0 AND a.`driver_id`=" . $cid;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT c.*,DATE_FORMAT(c.`duedate`,'%D %M%, %Y') as duedate,a.`vehicle_id`,MIN(c.`invoice_no`) AS invoice FROM `tbl_vehiclerental_agreement` a INNER JOIN `tbl_contractorinvoice` c ON c.`cid`=a.`vehicle_id` WHERE a.`driver_id`=" . $cid . " AND c.`istype`=3 AND c.`isdelete`=0 AND c.`isactive`=0 GROUP BY c.week_no order by `id` desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }

        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);

        $totalshow = ContractorVehicleInvoiceTotal($typeresult['week_no'], $typeresult['cid']);
        $statusname = strtoupper($stresult['name']);
        if ($typeresult['status_id'] == 4) {
            $due = $typeresult['duedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }

        $action = "<a href='contractorvehicle_invoice.php?id=" . $typeresult['id'] . "' class='delete'><span><b>View</b></span></a>&nbsp;
                   <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'total' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            'period' => '<b>Wk ' . $typeresult['week_no'] . '</b> - <small>' . $fromdate . ' >> ' . $todate . '</small>',
            'invoice_no' => $typeresult['invoice'],
            'vat' => $vat,
            'due' => $due,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'total' => "",
            'status' => "",
            'period' => "",
            'invoice_no' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'vat' => "",
            'due' => $query,
            'Action' => ""
        ];
        $i++;
    }
}
// else if(isset($_POST['action']) && $_POST['action'] == 'loadFinancecntInvoicestabledata')
// {
//     $depot = $_POST['did'];
//     $statusid = $_POST['statusid'];
//     $vat = $_POST['vatid'];


//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (i.`invoice_no` LIKE '%".$searchValue."%' or c.`name` LIKE '%".$searchValue."%' or i.`week_no` LIKE '%".$searchValue."%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%".$searchValue."%' ) ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();

//     $sql1 = "SELECT count(i.`id`) as allcount FROM `tbl_contractorinvoice` i INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` WHERE i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];

//     $sql2 = "SELECT count(i.`id`) as allcount FROM `tbl_contractorinvoice` i INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` WHERE i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'".$searchQuery;
//     $sel =  $mysql -> selectFreeRun($sql2);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecordwithFilter = $records['allcount'];
//     $ord="";

//     $query = "SELECT *,(SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname,
//      DATE_FORMAT(i.`duedate`,'%D %M%, %Y') as duedate,c.`name` as cntname
//      FROM `tbl_contractorinvoice` i
//      INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
//      WHERE i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'".$searchQuery." order by i.`id` desc limit ".$row.",".$rowperpage;

//     $typerow =  $mysql -> selectFreeRun($query);
//     $data = array();
//     while($typeresult = mysqli_fetch_array($typerow))
//     {
//         $vat = '';
//         if($typeresult['vat']==1)
//         {
//             $vat ='<i class="fas fa-check fa-lg edit"></i>';
//         }

//         $invoicestatus = $typeresult['status_id'];
//         $week_array = getStartAndEndDate($typeresult['week_no'],$typeresult['weekyear']);

//         $fromdate = date("d M, Y",strtotime($week_array['week_start']));
//         $todate = date("d M, Y",strtotime($week_array['week_end']));

//         $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=".$invoicestatus;
//         $strow =  $mysql -> selectFreeRun($stquery);
//         $stresult = mysqli_fetch_array($strow);

//         $totalshow = ContractorInvoiceTotal($typeresult['id']);
//         $statusname = strtoupper($stresult['name']);
//         if($typeresult['status_id']==4)
//         {
//             $due = $typeresult['duedate'];

//         }
//         else
//         {
//             if($todaydate>=$typeresult['duedate'])
//             {
//                 $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> '.$typeresult['duedate'].'</div>';
//             }
//             else
//             {
//                 $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> '.$typeresult['duedate'].'</b></div>';
//             }

//         }

//         $action = "<a href='contractor_invoice.php?id=".$typeresult["id"]."' class='delete'><span><b>View</b></span></a>
//                     &nbsp;
//                    <a href='#' class='edit' onclick=\"addstatus('".$typeresult['id']."','".$typeresult['status_id']."')\"><span><i class='fas fa-eye'></i></span></a>"; 

//         $data[] = [
//                     'name'=>$typeresult['cntname'].'<br><small>'.$typeresult['depotname'].'</small>',
//                     'total'=>'<b>£ '.$totalshow.'</b>',
//                     'status'=>"<span class='label label-secondary' style='color: ".$stresult['color'].";background-color: ".$stresult['backgroundcolor']."'><b>".$statusname."</b></span>",
//                     'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
//                     'invoice_no'=>$typeresult['invoice_no'],
//                     'vat'=>$vat,
//                     'due'=>$due,
//                     'Action'=>$action 
//                   ];
//     }

//     if(count($data)==0)
//     {
//         $data[] = [
//             'name'=>"",
//             'total'=>"",
//             'status'=>"",
//             'period'=>"",
//             'invoice_no'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
//             'vat'=>"",
//             'due'=>"",
//             'Action'=>""];
//         $i++;
//     }
// }
// else if(isset($_POST['action']) && $_POST['action'] == 'loadFinanceWorkforceInvoicestabledata')
// {
//     $depot = $_POST['did'];
//     $statusid = $_POST['statusid'];
//     $vat = $_POST['vatid'];

//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (i.`invoice_no` LIKE '%".$searchValue."%' or c.`name` LIKE '%".$searchValue."%' or i.`week_no` LIKE '%".$searchValue."%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%".$searchValue."%' ) ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();

//     $sql1 = "SELECT count(i.`id`) as allcount FROM `tbl_contractorinvoice` i INNER JOIN `tbl_user` c ON c.`id`=i.`cid` WHERE i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];

//     $sql2 = "SELECT count(i.`id`) as allcount FROM `tbl_contractorinvoice` i INNER JOIN `tbl_user` c ON c.`id`=i.`cid` WHERE i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'".$searchQuery;
//     $sel =  $mysql -> selectFreeRun($sql2);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecordwithFilter = $records['allcount'];
//     $ord="";

//     $query = "SELECT *,(SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname, DATE_FORMAT(i.`duedate`,'%D %M%, %Y') as duedate,c.`name` as cntname FROM `tbl_contractorinvoice` i INNER JOIN `tbl_user` c ON c.`id`=i.`cid` WHERE i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '".$depot."' AND i.`vat` LIKE '".$vat."' AND i.`status_id` LIKE '".$statusid."' AND c.`userid` LIKE '".$userid."'".$searchQuery." order by i.`id` desc limit ".$row.",".$rowperpage;

//     $typerow =  $mysql -> selectFreeRun($query);

//     $todaydate = date('Y-m-d');
//     $data = array();
//     while($typeresult = mysqli_fetch_array($typerow))
//     {
//         $vat = '';
//         if($typeresult['vat']==1)
//         {
//             $vat ='<i class="fas fa-check fa-lg edit"></i>';
//         }

//         $invoicestatus = $typeresult['status_id'];
//         $week_array = getStartAndEndDate($typeresult['week_no'],$typeresult['weekyear']);
//         $fromdate = date("d M, Y",strtotime($week_array['week_start']));
//         $todate = date("d M, Y",strtotime($week_array['week_end']));
//         $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=".$invoicestatus;
//         $strow =  $mysql -> selectFreeRun($stquery);
//         $stresult = mysqli_fetch_array($strow);
//         $totalshow = WorkforceInvoiceTotal($typeresult['id']);
//         $statusname = strtoupper($stresult['name']);
//         if($typeresult['status_id']==4)
//         {
//             $due = $typeresult['duedate'];

//         }
//         else
//         {
//             if($todaydate>=$typeresult['duedate'])
//             {
//                 $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> '.$typeresult['duedate'].'</div>';
//             }
//             else
//             {
//                 $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> '.$typeresult['duedate'].'</b></div>';
//             }

//         }

//         $action = "<a href='workforce_invoice.php?id=".$typeresult['id']."' class='delete'><span><b>View</b></span></a>&nbsp;
//             <a href='#' class='edit' onclick=\"addstatus('".$typeresult['id']."','".$typeresult['status_id']."')\"><span><i class='fas fa-eye'></i></span></a>";    

//         $data[] = [
//                     'name'=>$typeresult['cntname'].'<br><small>'.$typeresult['depotname'].'</small>',
//                     'total'=>'<b>£ '.$totalshow.'</b>',
//                     'status'=>"<span class='label label-secondary' style='color: ".$stresult['color'].";background-color: ".$stresult['backgroundcolor']."'><b>".$statusname."</b></span>",
//                     'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
//                     'invoice_no'=>$typeresult['invoice_no'],
//                     'vat'=>$vat,
//                     'due'=>$due,
//                     'Action'=>$action 
//                   ];
//     }

//     if(count($data)==0)
//     {
//         $data[] = [
//             'name'=>"",
//             'total'=>"",
//             'status'=>"",
//             'period'=>"",
//             'invoice_no'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
//             'vat'=>"",
//             'due'=>"",
//             'Action'=>""];
//         $i++;
//     }
// }
else if (isset($_POST['action']) && $_POST['action'] == 'loadFinanceWorkforceInvoicestabledata') {
    $depot = $_POST['did'];
    $statusid = $_POST['statusid'];
    $vat = $_POST['vatid'];
    $duedate = $_POST['duedate'];

    $searchQuery = " ";
    $extraquery = " ";

    if ($searchValue != '') {
        $searchQuery = " and (i.`invoice_no` LIKE '%" . $searchValue . "%' or c.`name` LIKE '%" . $searchValue . "%' or i.`week_no` LIKE '%" . $searchValue . "%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%" . $searchValue . "%' ) ";
    }
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery = " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_user` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_user` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT i.*,
             (SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname, 
             DATE_FORMAT(i.`duedate`,'%D %M%, %Y') as duedate,
             DATE_FORMAT(i.`update_date`,'%D %M%, %Y') as updatedate,c.`name` as cntname 
             FROM `tbl_contractorinvoice` i 
             INNER JOIN `tbl_user` c ON c.`id`=i.`cid` 
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=2 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery . " order by i.`id` desc limit " . $row . "," . $rowperpage;

    $typerow =  $mysql->selectFreeRun($query);

    $todaydate = date('Y-m-d');
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }

        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);
        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));
        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);
        $totalshow = WorkforceInvoiceTotal($typeresult['id']);
        $statusname = strtoupper($stresult['name']);
        if ($typeresult['status_id'] == 4) {
            $due = 'Paid: ' . $typeresult['updatedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }

        $action = "<a href='workforce_invoice.php?id=" . $typeresult['id'] . "' class='delete'><span><b>View</b></span></a>&nbsp;
            <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'name' => $typeresult['cntname'] . '<br><small>' . $typeresult['depotname'] . '</small>',
            'total' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            //'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
            'invoice_no' => $typeresult['invoice_no'],
            'vat' => $vat,
            'due' => $due,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'total' => "",
            'status' => "",
            //'period'=>"",
            'invoice_no' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'vat' => "",
            'due' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadFinancecntInvoicestabledata') {
    $depot = $_POST['did'];
    $statusid = $_POST['statusid'];
    $vat = $_POST['vatid'];
    $duedate = $_POST['duedate'];

    $searchQuery = " ";
    $extraquery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (i.`invoice_no` LIKE '%" . $searchValue . "%' or c.`name` LIKE '%" . $searchValue . "%' or i.`week_no` LIKE '%" . $searchValue . "%' or DATE_FORMAT(i.`duedate`,'%D %M%, %Y') LIKE '%" . $searchValue . "%' ) ";
    }
    if ($duedate == 'true') {
        $monday = strtotime("last monday");
        $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
        $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
        $this_week_sd = date("Y-m-d", $monday);
        $this_week_ed = date("Y-m-d", $sunday);

        $extraquery = " and i.`duedate` between '" . $this_week_sd . "' and '" . $this_week_ed . "' ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery;

    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT i.*,
            (SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname,
            DATE_FORMAT(i.`duedate`,'%D %M%, %Y') as duedate, DATE_FORMAT(i.`update_date`,'%D %M%, %Y') as updatedate,c.`name` as cntname
            FROM `tbl_contractorinvoice` i
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`vat` LIKE '" . $vat . "' AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " " . $extraquery . " order by i.`id` desc limit " . $row . "," . $rowperpage;

    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }

        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);

        $totalshow = ContractorInvoiceTotal($typeresult['id']);
        $statusname = strtoupper($stresult['name']);
        if ($typeresult['status_id'] == 4) {
            $due = 'Paid: ' . $typeresult['updatedate'];
        } else {
            if ($todaydate >= $typeresult['duedate']) {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
            } else {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
            }
        }

        $action = "<a href='contractor_invoice.php?id=" . $typeresult["id"] . "' class='delete'><span><b>View</b></span></a>
                    &nbsp;
                   <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'name' => $typeresult['cntname'] . '<br><small>' . $typeresult['depotname'] . '</small>',
            'total' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            //'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
            'invoice_no' => $typeresult['invoice_no'],
            'vat' => $vat,
            'due' => $due,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'total' => "",
            'status' => "",
            //'period'=>"",
            'invoice_no' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'vat' => "",
            'due' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadCustomInvoicetabledata') {

    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (`invoice_no` LIKE '%" . $searchValue . "%' or `week_no` LIKE '%" . $searchValue . "%' or DATE_FORMAT(`duedate`,'%D %M%, %Y') LIKE '%" . $searchValue . "%' ) ";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(*) as allcount FROM `tbl_contractorinvoice` WHERE `isdelete` = 0 AND `istype`=4";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_contractorinvoice` WHERE `isdelete` = 0 AND `istype`=4" . $searchQuery;
    $sel2 =  $mysql->selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`duedate`,'%D %M%, %Y') as duedate,DATE_FORMAT(`update_date`,'%D %M%, %Y') as updatedate FROM `tbl_contractorinvoice` WHERE `istype`=4 AND `isdelete`= 0 " . $searchQuery . " order by `id` " . $columnSortOrder . ", `id` ASC limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);

    $todaydate = date('Y-m-d');
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $vat = '';
        if ($typeresult['vat'] == 1) {
            $vat = '<i class="fas fa-check fa-lg edit"></i>';
        }

        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);
        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT v.`name` as contactname, (SELECT i.`name` FROM `tbl_invoicestatus` i WHERE i.`isdelete`= 0 AND i.`isactive`= 0 AND i.`id`=" . $typeresult['status_id'] . ") as statusname, (SELECT i.`color` FROM `tbl_invoicestatus` i WHERE i.`isdelete`= 0 AND i.`isactive`= 0 AND i.`id`=" . $typeresult['status_id'] . ") as colorname, (SELECT i.`backgroundcolor` FROM `tbl_invoicestatus` i WHERE i.`isdelete`= 0 AND i.`isactive`= 0 AND i.`id`=" . $typeresult['status_id'] . ") as bgclrname FROM `tbl_vehiclecontact` v WHERE v.`id`=" . $typeresult['cid'];
        $strow =  $mysql->selectFreeRun($stquery);
        //$stresult = mysqli_fetch_array($strow);
        $contactname = '';
        $statusname = '';
        $color = '';
        $bgcolor = '';
        if ($stresult = mysqli_fetch_array($strow)) {
            $contactname =  $stresult['contactname'];
            $statusname = strtoupper($stresult['statusname']);
            $color =  $stresult['colorname'];
            $bgcolor =  $stresult['bgclrname'];
        }

        $totalshow = CustomInvoiceTotal($typeresult['id']);
        if ($typeresult['status_id'] == 4) {
            $due = 'Paid' . $typeresult['updatedate'];
        } else {
            if ($typeresult['duedate']) {
                if ($todaydate >= $typeresult['duedate']) {
                    $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> ' . $typeresult['duedate'] . '</div>';
                } else {
                    $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> ' . $typeresult['duedate'] . '</b></div>';
                }
            }
        }

        $action = "<a href='custom_invoice.php?id=" . $typeresult['id'] . "' class='delete'><span><b>View</b></span></a>&nbsp;
        <a href='#' class='edit' onclick=\"addstatus('" . $typeresult['id'] . "','" . $typeresult['status_id'] . "')\"><span><i class='fas fa-eye'></i></span></a>";

        $data[] = [
            'amount' => '<b>£ ' . $totalshow . '</b>',
            'status' => "<span class='label label-secondary' style='color: " . $color . ";background-color: " . $bgcolor . "'><b>" . $statusname . "</b></span>",
            'name' => $contactname,
            //'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
            'due' => $due,
            'vat' => $vat,
            'accountant' => '',
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'amount' => "",
            'status' => "",
            'name' => "",
            //'period'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'due' => "",
            'vat' => "",
            'accountant' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadDisputedInvoicestabledata') {
    $depot = $_POST['did'];
    $statusid = $_POST['statusid'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (i.`invoice_no` LIKE '%" . $searchValue . "%' or  c.`name` LIKE '%" . $searchValue . "%' or  i.`week_no` LIKE '%" . $searchValue . "%' or DATE_FORMAT(i.`disputed_date`,'%D %M%, %Y') LIKE '%" . $searchValue . "%' ) ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`status_id` LIKE '" . $statusid . "' AND i.`status_id`=9 AND i.`istype`=1";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT i.`id`) as allcount 
            FROM `tbl_contractorinvoice` i 
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`istype`=1 AND i.`isdelete`= 0 AND i.`depot_id` LIKE '" . $depot . "' AND i.`status_id` LIKE '" . $statusid . "' AND i.`status_id`=9 AND i.`istype`=1" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT DISTINCT i.*,
            (SELECT `name` FROM `tbl_depot` WHERE `id`=i.`depot_id`) as depotname,
            DATE_FORMAT(i.`disputed_date`,'%D %M%, %Y') as duedate,c.`name` as cntname 
            FROM `tbl_contractorinvoice` i
            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE i.`depot_id` IN (w.depot_id) AND i.`isdelete`= 0 AND i.`status_id`=9  AND i.`istype`=1 AND i.`depot_id` LIKE '" . $depot . "' AND i.`status_id` LIKE '" . $statusid . "'" . $searchQuery . " order by i.`id` desc limit " . $row . "," . $rowperpage;

    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        $invoicestatus = $typeresult['status_id'];
        $week_array = getStartAndEndDate($typeresult['week_no'], $typeresult['weekyear']);

        $fromdate = date("d M, Y", strtotime($week_array['week_start']));
        $todate = date("d M, Y", strtotime($week_array['week_end']));

        $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=" . $invoicestatus;
        $strow =  $mysql->selectFreeRun($stquery);
        $stresult = mysqli_fetch_array($strow);
        $statusname = strtoupper($stresult['name']);

        $action = "<a href='contractor_invoice.php?id=" . $typeresult["id"] . "' class='btn btn-primary'><span><b>Detail</b></span></a>";

        $data[] = [
            'name' => $typeresult['cntname'] . '<br><small>' . $typeresult['depotname'] . '</small>',
            'period' => '<b>Wk ' . $typeresult['week_no'] . '</b> - <small>' . $fromdate . ' >> ' . $todate . '</small>',
            'disputed_comment' => '<i>' . $typeresult['disputed_comment'] . '</i>',
            'status' => "<span class='label label-secondary' style='color: " . $stresult['color'] . ";background-color: " . $stresult['backgroundcolor'] . "'><b>" . $statusname . "</b></span>",
            'disputed_date' => '<div style="color: #ff7800e0;"><b>' . $typeresult['duedate'] . '</b></div>',
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'period' => "",
            'disputed_comment' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'status' => "",
            'disputed_date' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadfinanceAssetstable') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or type_name LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_financeassets where isdelete = 0 AND isactive = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_financeassets WHERE isdelete = 0 AND isactive = 0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_financeassets` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        $assign = $userresult['assign_to'];


        $emp = array();
        $emp = explode("-", $assign);
        // print_r($emp[1]);
        if ($emp[0] == "workforce") {
            $sql = "SELECT * FROM `tbl_user` WHERE id=$emp[1]";
            $fire = $mysql->selectFreeRun($sql);
            $fetch = mysqli_fetch_array($fire);
        } else {
            $sql = "SELECT * FROM `tbl_contractor` WHERE id=$emp[1]";
            $fire = $mysql->selectFreeRun($sql);
            $fetch = mysqli_fetch_array($fire);
        }


        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button>
                    </div>";

        $action = " <input type='hidden' name='assign_id' value='" . $userresult['id'] . "' id='assign_id'>
                    <button class='btn btn-primary' onclick=\"getmdl('" . $userresult['id'] . "')\" id='" . $userresult['id'] . "' name='assign_to' value='" . $userresult['id'] . "' type='button'>Assign</button>
                    
                    <a href='assetupdate.php?id=" . $userresult['id'] . "' class='edit pl-2' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete pl-2' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            'type_name' => $userresult['type_name'],
            'name' => $userresult['name'],
            'description' => $userresult['description'],
            'number' => $userresult['number'],
            'price' => $userresult['price'],
            'assign_to' => $fetch['name'],
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'type_name' => "",
            'name' => "",
            'description' => "",
            'number' => "No Record Found!!!",
            'price' => "",
            'number' => "",
            'assign_to' => "",
            'Action' => "",
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadFeedbacktabledata') {
    $depotid = $_POST['did'];
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (f.`feedback` LIKE '%" . $searchValue . "%' or DATE_FORMAT(f.`insert_date`,'%D %M%, %Y') LIKE '%" . $searchValue . "%' ) ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT COUNT(f.`id`) as allcount FROM `tbl_contactorfeedback` f  
            INNER JOIN `tbl_contractor` c ON c.`id`=f.`cid` AND c.`depot` LIKE ('" . $depotid . "') 
            INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE f.`isactive`=0 AND f.`isdelete`=0";

    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT COUNT(f.`id`) as allcount FROM `tbl_contactorfeedback` f 
            INNER JOIN `tbl_contractor` c ON c.`id`=f.`cid` AND c.`depot` LIKE ('" . $depotid . "') 
            INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE f.`isactive`=0 AND f.`isdelete`=0" . $searchQuery;

    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];

    $query = "SELECT DISTINCT f.*, DATE_FORMAT(f.`insert_date`,'%D %M%, %Y') as date1 
            FROM `tbl_contactorfeedback` f 
            INNER JOIN `tbl_contractor` c ON c.`id`=f.`cid` AND c.`depot` LIKE ('" . $depotid . "') 
            INNER JOIN `tbl_workforcedepotassign` w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE f.`isactive`=0 AND f.`isdelete`=0" . $searchQuery . " order by f.`id` asc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {

        $cntquery = "SELECT * FROM `tbl_contractor` WHERE id=" . $typeresult['cid'];
        $cntfire = $mysql->selectFreeRun($cntquery);
        $cntresult = mysqli_fetch_array($cntfire);

        $data[] = [
            'id' => $typeresult['id'],
            'name' => $cntresult['name'],
            'feedback' => $typeresult['feedback'],
            'date' => $typeresult['date1']
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'id' => "",
            'name' => "",
            'feedback' => "<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'date' => ""
        ];
        $i++;
    }
}
// else if(isset($_POST['action']) && $_POST['action'] == 'loadrentalagrementtabledata')
// {
//    ## Search 
//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (vehicle_reg_no LIKE '%".$searchValue."%') ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();

//     $sql1 = "SELECT count(*) as allcount FROM `tbl_vehiclerental_agreement`  WHERE `isdelete` = 0 AND `isactive` = 0";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];
//     $sql2 = "SELECT count(*) as allcount FROM `tbl_vehiclerental_agreement`  WHERE `isdelete` = 0 AND `isactive` = 0".$searchQuery;
//     $sel =  $mysql -> selectFreeRun($sql2);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecordwithFilter = $records['allcount'];
//     $ord="";

//     $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 , DATE_FORMAT(`pickup_date`,'%D %M%, %Y') as pickupdate, DATE_FORMAT(`return_date`,'%D %M%, %Y') as returndate FROM `tbl_vehiclerental_agreement` WHERE `isdelete`= 0".$searchQuery." order by id ".$columnSortOrder. ", id desc limit ".$row.",".$rowperpage;
//     $userrow =  $mysql -> selectFreeRun($query);
//     $data = array();
//     while($userresult = mysqli_fetch_array($userrow))
//     {

//         $sql = "SELECT `tbl_vehiclegroup`.`name` FROM `tbl_vehiclegroup` INNER JOIN `tbl_vehicles` ON `tbl_vehicles`.`group_id` = `tbl_vehiclegroup`.id INNER JOIN `tbl_vehiclerental_agreement` ON `tbl_vehiclerental_agreement`.`vehicle_reg_no` = `tbl_vehicles`.`registration_number` WHERE `tbl_vehiclerental_agreement`.`vehicle_reg_no`='".$userresult['vehicle_reg_no']."'";
//         $fire = $mysql -> selectFreeRun($sql);
//         $fetch = mysqli_fetch_array($fire);
//         $userid = $userresult['id'];
//         $sql1 = "SELECT `tbl_contractor`.`type`,`tbl_contractor`.`name` FROM `tbl_contractor` INNER JOIN `tbl_vehiclerental_agreement` ON `tbl_contractor`.`id` = `tbl_vehiclerental_agreement`.`driver_id` WHERE `tbl_vehiclerental_agreement`.`id`=$userid";
//         $fire1 = $mysql -> selectFreeRun($sql1);
//         $fetch1 = mysqli_fetch_array($fire1);
//         if($fetch1['type'] == "1"){
//             $type = "Self-Employed";
//         }else if($fetch1['type'] == "2"){
//             $type = "Limited Company";
//         }else{
//             $type="error";
//         }

//        $vehicle_reg_no = $userresult['vehicle_reg_no'];

//        $name = "".$fetch1['name']."<br><label style='color: #a5a1a1f7;'>$type</label>";

//        $location = "SELECT v.*,d.`name` as depotname, s.`name` as suppliername FROM `tbl_vehicles` v INNER JOIN `tbl_depot` d ON d.`id`=v.`depot_id` INNER JOIN `tbl_vehiclesupplier` s ON s.`id`= v.`supplier_id` WHERE v.`id`=".$userresult['vehicle_id'];
//        $fire2 = $mysql -> selectFreeRun($location);
//        $fetch2 = mysqli_fetch_array($fire2);

//        $vehicle = "".$fetch2['suppliername']." ($vehicle_reg_no) "." <label class='rounded-lg col-sm-3 text-center badge badge-primary text-wrap' style='font-weight: bold; color:white'>".$fetch['name']."</label>";


//        if($userresult['isactive']==0)
//         {
//             $statuscls = 'success';
//             $statusname = 'Active';
//         }
//         else
//         {
//             $statuscls = 'danger';
//             $statusname = 'Inactive';
//         }

//        $status="<div id='".$userresult['id']."-td'>
//                         <button type='button' class='isactivebtn btn btn-".$statuscls."' 
//                         onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','RentalAgreementisactive')\">".$statusname."</button>
//                     </div>";

//        $action = "<a href='#' class='delete' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

//         $data[] = [

//                     'hirer'=>$name,   
//                     'vehicle_reg_no'=>$vehicle,
//                     'location'=>$fetch2['depotname'],
//                     'pickup_date'=>$userresult['pickupdate'],
//                     'return_date'=>$userresult['returndate'],
//                     'price_per_day'=>£. $userresult['price_per_day'],
//                     ''=>$status,
//                     //'action'=>'',
//                   ];
//     }
//     if(count($data)==0)
//     {
//         $data[] = [

//                 'hirer'=>"",  
//                 'vehicle_reg_no'=>"",
//                 'location'=>"",
//                 'pickup_date'=>"",
//                 'return_date'=>"",
//                 'price_per_day'=>"",
//                 ''=>"",
//                 //'action'=>"",
//                 ];
//             $i++;
//     }
// }

else if (isset($_POST['action']) && $_POST['action'] == 'loadvehicleOffencestabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (occurred_date LIKE '%" . $searchValue . "%' or amount LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehicleoffences where isdelete = 0 AND isactive = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehicleoffences WHERE isdelete = 0 AND isactive = 0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`occurred_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleoffences` WHERE `isdelete`= 0" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id ASC limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }


        $vehicleqry = "SELECT v.`registration_number`,c.`name` FROM `tbl_vehicleoffences` o
                        INNER JOIN `tbl_vehicles` v ON v.`id`=o.`vehicle_id`
                        INNER JOIN `tbl_contractor` c ON c.`id`=o.`driver_id`
                        WHERE o.`id`=" . $userresult['id'] . " AND o.`isdelete`=0";
        $fire =  $mysql->selectFreeRun($vehicleqry);
        $records1 = mysqli_fetch_array($fire);
        $vehicle_idrow = "" . $records1['registration_number'] . "<br>" . $records1['name'] . "" . "-" . $userresult['identifier'];

        $statustd = "<div id='" . $userresult['id'] . "-td'><button type='button' class='isactivebtn btn btn-" . $statuscls . "' onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button></div>";

        $action = "<div class='row'>
                    <a href='assetupdate.php?id=" . $userresult['id'] . "' class='edit pl-2' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
                    <a href='#' class='delete pl-2' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>
                    <div class='col-md-1'><button class='btn btn-primary'  style='height: 30px;' onclick=\"getmdl('" . $userresult['id'] . "')\" id='" . $userresult['id'] . "' name='assign_to' value='" . $userresult['id'] . "' type='button'>View</button></div>
                    </div>";

        $data[] = [
            'occurred_date' => $userresult['date1'],
            'vehicle_id' => $vehicle_idrow,
            'pcnticket_typeid' => $userresult['pcnticket_typeid'],
            'amount' => $userresult['amount'],
            'admin_fee' => $userresult['admin_fee'],
            'Action' => '',
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'occurred_date' => "",
            'vehicle_id' => "",
            'pcnticket_typeid' => "No Record Found!!!",
            'amount' => "",
            'admin_fee' => "",
            'Action' => "",
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadaccidenttabledata') {
    ## Search 
    $driver_name_serch = $_POST['driver_name_serch'];
    if ($driver_name_serch == "") {
        $driver_name_serch = "%";
    }
    $vehicle_serch = $_POST['vehicle_serch'];
    $status_serch = $_POST['status_serch'];
    $stage_serch = $_POST['stage_serch'];

    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (a.`reference` LIKE '%" . $searchValue . "%' or a.`other_person` LIKE '%" . $searchValue . "%' or a.`description` LIKE '%" . $searchValue . "%' or a.`stage_id` LIKE '%" . $searchValue . "%' or v.`registration_number` LIKE '%" . $searchValue . "%' or a.`status` LIKE '%" . $searchValue . "%' or c.`name` LIKE '%" . $searchValue . "%') ";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(DISTINCT a.`id`) as allcount FROM `tbl_accident` a 
    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
    INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver_id`
    INNER JOIN `tbl_vehicleaccidentstage` s ON s.`id`=a.`stage_id`
    WHERE a.`isdelete` = 0  AND v.`id` LIKE ('" . $vehicle_serch . "') AND c.`name` LIKE ('" . $driver_name_serch . "') AND a.`stage_id` LIKE ('" . $stage_serch . "') AND a.`status` LIKE ('" . $status_serch . "')";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT a.`id`) as allcount FROM `tbl_accident` a 
    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id` 
    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
    INNER JOIN `tbl_contractor` c ON c.`id`=a.`driver_id`
    INNER JOIN `tbl_vehicleaccidentstage` s ON s.`id`=a.`stage_id`
    WHERE a.`isdelete` = 0  AND v.`id` LIKE ('" . $vehicle_serch . "') AND c.`name` LIKE ('" . $driver_name_serch . "') AND a.`stage_id` LIKE ('" . $stage_serch . "') AND a.`status` LIKE ('" . $status_serch . "')" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $data = array();
    $i = $row + 1;

    $row13 = "SELECT DISTINCT a.*,a.`isactive` as statusnam,vs.`name` as stagename,va.`name` as typename,v.`registration_number`,s.`name` as suppliername,c.`name` as contractorname, DATE_FORMAT(a.`date_occured`,'%D %M%, %Y') as date1  
    FROM `tbl_accident` a
    INNER JOIN `tbl_contractor` c ON c.`id` = a.`driver_id`
    INNER JOIN `tbl_vehicleaccidentstage` vs ON vs.`id` = a.`stage_id`
    INNER JOIN `tbl_vehicletypeaccident` va ON va.`id` = a.`type_id` 
    INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id` = v.`supplier_id`
    WHERE  a.`isdelete` = 0 AND v.`id` LIKE ('" . $vehicle_serch . "') AND c.`name` LIKE ('" . $driver_name_serch . "') AND a.`stage_id` LIKE ('" . $stage_serch . "') AND a.`status` LIKE ('" . $status_serch . "')" . $searchQuery . " order by a.`id` " . $columnSortOrder . ", a.`id` asc limit " . $row . "," . $rowperpage;
    $fire1 =  $mysql->selectFreeRun($row13);
    while ($userresult1 = mysqli_fetch_array($fire1)) {
        if ($userresult['statusnam'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $roleid = $userresult1['id'];

        $statustd = "<div id='" . $userresult1['id'] . "-td'><button type='button' class='isactivebtn btn btn-" . $statuscls . "' onclick=\"Isactivebtn('" . $userresult1['id'] . "','" . $userresult1['isactive'] . "','accidenttableisactive')\">" . $statusname . "</button></div>";


        $delete = "<a href='#' class='edit' onclick=\"edit('" . $userresult1['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
        <a href='#' class='delete' onclick=\"deleterow('" . $userresult1['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            'stage_id' => $userresult1['stagename'],
            'driver_id' => "<a href='vehicle_accident_details.php?aid=" . $userresult1['id'] . "' class='link-info text-info'> " . $userresult1['contractorname'] . "</a>",
            'vehicle_id' => $userresult1['suppliername'],
            'registration_number' => $userresult1['registration_number'],
            'reference' => $userresult1['reference'],
            'date_occured' => $userresult1['date1'],
            'reported' => $userresult1['reference'],
            'type_id' => $userresult1['typename'],
            'other_person' => $userresult1['other_person'],
            'status' => $statustd,
            'action' => $delete,

        ];
        $i++;
    }
    if (count($data) == 0) {
        $data[] = [
            'stage_id' => "",
            'driver_id' => "",
            'vehicle_id' => "",
            'registration_number' => "",
            'reference' => "",
            'date_occured' => "NO Data Found !!!",
            'reported' => "",
            'type_id' => "",
            'other_person' => "",
            'status' => "",
            'action' => "",

        ];
        $i++;
    }
}
//bhumika changes
else if (isset($_POST['action']) && $_POST['action'] == 'loadstatusdata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (a.`name` LIKE '%" . $searchValue . "%') ";
    }
    $userid = $_POST['userid'];
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_vehiclestatus where isdelete = 0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclestatus WHERE isdelete = 0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT a.`name` AS status,COUNT(b.`id`) AS vehicles FROM `tbl_vehiclestatus` a 
    LEFT JOIN `tbl_vehicles` b ON b.`status` = a.`id` AND b.`isdelete`=0 AND b.`userid`=" . $userid . "
    WHERE a.`isdelete`= 0 AND a.isactive=0  " . $searchQuery . " GROUP BY a.name order by " . $columnName . " " . $columnSortOrder . ", Status asc limit " . $row . "," . $rowperpage;

    $statusrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($statusresult = mysqli_fetch_array($statusrow)) {
        $data[] = [
            'Status' => $statusresult['status'],
            'Vehicles' => $statusresult['vehicles']
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'Status' => "",
            'Vehicles' => "No data found!!!"
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadworkforcetasktabledata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%' or insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_workforcetask where isdelete = 0 AND status=0 AND `userid` LIKE ('" . $userid . "')";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_workforcetask WHERE isdelete = 0 AND status=0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT *, DATE_FORMAT(`Insert_date`,'%D %M%, %Y') as date1 FROM `tbl_workforcetask` WHERE `isdelete`= 0 AND status=0 AND `userid` LIKE ('" . $userid . "')" . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . ", id desc limit " . $row . "," . $rowperpage;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($userresult = mysqli_fetch_array($userrow)) {
        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceTaskisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='#' class='edit' onclick=\"edit('" . $userresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $userresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $date = strtotime($userresult['duedate']);
        $duedate = date('d M, Y', $date);

        $name = "<input id='" . $userresult['id'] . "' onclick=\"check('" . $userresult['id'] . "')\" type='checkbox' style='width: 15px;
    height: 15px;
    margin-right: 15px;'>" . $userresult['name'];
        //echo $name;
        $data[] = [
            'name' => $name,
            'assignee_type' => $userresult['assignee_type'],
            'duedate' => "<i class='fas fa-calendar-alt'></i>  " . $duedate,
            'date' => $userresult['date1'],
            'Status' => $statustd,
            'Action' => $action
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'name' => "",
            'assignee_type' => "",
            'duedate' => "No Record Found!!!",
            'date' => "",
            'Status' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadexpiredocdata') {

    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " and (vsp.`name` LIKE '%" . $searchValue . "%' or vd.expiredate LIKE '%" . $searchValue . "%' or vt.`name` LIKE '%" . $searchValue . "%')";
    }
    $monday = strtotime("last monday");
    $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
    $sunday = strtotime(date("Y-m-d", $monday) . " +6 days");
    $this_week_sd = date("Y-m-d", $monday);
    $this_week_ed = date("Y-m-d", $sunday);

    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql1 = "SELECT count(DISTINCT v.`id`) as allcount
        FROM `tbl_vehicles` v 
        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
        INNER JOIN `tbl_vehicledocument` vd ON vd.`vehicle_id`=v.`id` 
        WHERE v.`isdelete`= 0 AND vd.`expiredate` between '" . $this_week_sd . "' and '" . $this_week_ed . "'";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(DISTINCT v.`id`) as allcount
        FROM `tbl_vehicles` v 
        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
        INNER JOIN `tbl_vehicledocument` vd ON vd.`vehicle_id`=v.`id` 
        WHERE v.`isdelete`= 0 AND vd.`expiredate` between '" . $this_week_sd . "' and '" . $this_week_ed . "'".$searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];


    $query = "SELECT DISTINCT v.*,DATE_FORMAT(vd.`expiredate`,'%D %M%, %Y') as expiredate,vs.`name` as statusname,vs.`colorcode`,
    vsp.`name` as suppliername,vt.`name` as typename, vd.`file` as file
        FROM `tbl_vehicles` v 
        INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  
        LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
        LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
        LEFT JOIN `tbl_vehicletype` vt ON vt.`id`=v.`type_id` 
        INNER JOIN `tbl_vehicledocument` vd ON vd.`vehicle_id`=v.`id` 
        WHERE v.`isdelete`= 0 AND vd.`expiredate` between '" . $this_week_sd . "' and '" . $this_week_ed . "'" . $searchQuery . " order by v.`id` " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    // echo $query;
    $vehiclesrow =  $mysql->selectFreeRun($query);

    $data = array();
    while ($typeresult = mysqli_fetch_array($vehiclesrow)) {

        $statustd = "<span class='label label-secondary' style='background-color:" . $typeresult['colorcode'] . "';> " . $typeresult['statusname'] . "</span>";

        $supplier = "<span  style='color: blue;' >" . $typeresult['suppliername'] . "(" . $typeresult['registration_number'] . ")<br><small>" . $typeresult['typename'] . "</small><span>";

        $view = "<a target = '_blank' href='" . $webroot . 'uploads/vehicledocument/' . $typeresult['file'] . "' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>";
        $data[] = [
            'vehicle' => "<b>" . $supplier . "</b>",
            'document' => $typeresult['file'],
            'expires' => $typeresult['expiredate'],
            'status' => $statustd,
            'view' => $view

        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'vehicle' => "",
            'document' => "",
            'expires' => "No data Found!!",
            'status' => "",
            'view' => ""
        ];
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadloandata') {
    ## Search 
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " and (name LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(*) as allcount FROM (SELECT DISTINCT a.`wid` as allcount FROM `tbl_workforcelend` a INNER join tbl_user b WHERE a.`wid` = b.`id` AND b.`isdelete` = 0 AND a.`isdelete` = 0 AND b.`userid` LIKE ('" . $userid . "') UNION SELECT DISTINCT a.cid as allcount FROM `tbl_contractorlend` a INNER join tbl_contractor b WHERE a.cid = b.id AND b.isdelete = 0 AND a.isdelete = 0 AND b.userid LIKE ('" . $userid . "')) AS t1";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(*) as allcount FROM (SELECT DISTINCT a.`wid` as allcount,b.`name` AS name FROM `tbl_workforcelend` a INNER join tbl_user b WHERE a.`wid` = b.`id` AND b.`isdelete` = 0 AND a.`isdelete` = 0 AND b.`userid` LIKE ('" . $userid . "') UNION SELECT DISTINCT a.cid as allcount,b.name AS name FROM `tbl_contractorlend` a INNER join tbl_contractor b WHERE a.cid = b.id AND b.isdelete = 0 AND a.isdelete = 0 AND b.userid LIKE ('" . $userid . "')) AS t1 where 1 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";

    $query = "SELECT 'Contractor' as type, b.`name` AS name,b.`isactive`,COALESCE(SUM(a.`amount`),0) as totalamount,(SELECT COALESCE(SUM(`amount`),0) FROM `tbl_contractorpayment` WHERE `isdelete`=0  AND cid = a.`cid`) as paidamount FROM `tbl_contractorlend` a INNER join tbl_contractor b WHERE a.`cid` = b.`id` AND b.`isdelete` = 0 AND a.`isdelete` = 0 AND b.`userid` LIKE ('" . $userid . "') " . $searchQuery . "  GROUP BY a.cid 
    UNION 
    SELECT 'Workforce' as type, b.name AS name,b.isactive,COALESCE(SUM(a.`amount`),0) as totalamount,(SELECT COALESCE(SUM(`amount`),0) FROM `tbl_workforcepayment` WHERE `isdelete`=0  AND wid = a.wid) as paidamount FROM `tbl_workforcelend` a INNER join tbl_user b WHERE a.wid = b.id AND b.isdelete = 0 AND a.isdelete = 0 AND b.userid LIKE ('" . $userid . "') " . $searchQuery . " GROUP BY a.wid order by " . $columnName . " " . $columnSortOrder . ", name asc limit " . $row . "," . $rowperpage;
    //echo $query;
    $statusrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($statusresult = mysqli_fetch_array($statusrow)) {

        if ($statusresult['isactive'] == 1) {
            $statustd = $statusresult['name'] . "<span class='label label-danger label-danger-subtle' style='margin-left: 4px;padding: 0 5px 3px 5px;'>INACTIVE</span> ";
        } else {
            $statustd = $statusresult['name'];
        }
        $remain = $statusresult['totalamount'] - $statusresult['paidamount'];
        $total += $remain;
        $data[] = [
            'type' => $statusresult['type'],
            'name' => $statustd,
            'remain' => '£' . $remain,
            'total' => $total
        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'type' => "",
            'name' => "No data found!!!",
            'remain' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadroutestabledata') {
    ## Search 
    $searchQuery = "1";
    if ($searchValue != '') {
        $searchQuery = " value LIKE '%" . $searchValue . "%' ";
    }
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $filter = " ";
    if ($start_date && $end_date != '') {
        $start_date = new DateTime($start_date);
        $end_date = new DateTime($end_date);
        $filter = " AND date BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "' ";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();
    $userid = $_SESSION['userid'];

    $sql1 = "SELECT  Z.`value`, COUNT(id) AS COUNT FROM (SELECT a.`value`,a.`date`, a.`id` FROM `tbl_contractortimesheet` a WHERE a.`isdelete` 
    = 0 AND a.`rateid`=0 AND a.`isactive` = 0 AND a.`userid`=$userid UNION SELECT b.`value`, b.`id`, b.`date` FROM `tbl_workforcetimesheet` b WHERE 
    b.`isdelete` = 0 AND b.`rateid`=0 AND b.`isactive` = 0 AND b.`userid`=$userid ) As Z WHERE 1 GROUP BY Z.`value`";
    $sel1 =  $mysql->selectFreeRun($sql1);
    $records1 = mysqli_num_rows($sel1);
    $totalRecords = $records1;


    $sql2 = "SELECT  Z.`value`, COUNT(id) AS COUNT FROM (SELECT a.`value`,a.`date`, a.`id` FROM `tbl_contractortimesheet` a WHERE a.`isdelete` 
    = 0 AND a.`rateid`=0 AND a.`isactive` = 0 AND a.`userid`=$userid UNION SELECT b.`value`,b.`date`,b.`id` FROM `tbl_workforcetimesheet` b WHERE 
    b.`isdelete` = 0 AND b.`rateid`=0 AND b.`isactive` = 0 AND b.`userid`=$userid ) As Z WHERE " . $searchQuery . " " . $filter . "GROUP BY Z.value";
    $sel2 =  $mysql->selectFreeRun($sql2);
    $records2 = mysqli_num_rows($sel2);
    $totalRecordwithFilter = $records2;
    $ord = "";

    $query = "SELECT  Z.`value`, COUNT(id) AS COUNT FROM 
    (SELECT a.`value`,a.`date`, a.`id` FROM `tbl_contractortimesheet` a WHERE a.`isdelete` 
    = 0 AND a.`rateid`=0 AND a.`isactive` = 0 AND a.`userid`=$userid UNION SELECT b.`value`,b.`date`,b.`id` FROM `tbl_workforcetimesheet` b WHERE 
    b.`isdelete` = 0 AND b.`rateid`=0 AND b.`isactive` = 0 AND b.`userid`=$userid ) As Z WHERE " . $searchQuery . " " . $filter . " GROUP BY Z.value order by `id` " . $columnSortOrder . ", id ASC limit " . $row . "," . $rowperpage;
    // echo $query;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($records3 = mysqli_fetch_array($userrow)) {
        $data[] = [
            'route' => $records3['value'],
            'count' => $records3['COUNT'],
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'route' => "",
            'count' => "",
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadsupplierdata') {
    ## Search 
    $searchQuery = "1";
    if ($searchValue != '') {
        $searchQuery = " b.name LIKE '%" . $searchValue . "%' ";
    }

    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql1 = "SELECT COUNT(DISTINCT a.`id`) AS COUNT FROM `tbl_vehiclesupplier` b 
            INNER JOIN `tbl_vehicles` a  ON b.`id` = a.`supplier_id`
            INNER JOIN tbl_workforcedepotassign w ON a.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE b.`isdelete`= 0 AND a.`userid` LIKE ('" . $userid . "') GROUP BY b.`id`";
    $sel1 =  $mysql->selectFreeRun($sql1);
    $records1 = mysqli_num_rows($sel1);
    $totalRecords = $records1;


    $sql2 = "SELECT b.`name` AS supplier,COUNT(DISTINCT b.`id`) AS COUNT 
            FROM `tbl_vehiclesupplier` b 
            INNER JOIN `tbl_vehicles` a  ON b.`id` = a.`supplier_id` 
            INNER JOIN tbl_workforcedepotassign w ON a.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE b.`isdelete`= 0 AND " . $searchQuery . " GROUP BY b.id";

    $sel2 =  $mysql->selectFreeRun($sql2);
    $records2 = mysqli_num_rows($sel2);
    $totalRecordwithFilter = $records2;
    $ord = "";

    $query ="SELECT b.`name` AS supplier,COUNT(DISTINCT a.`id`) AS COUNT FROM `tbl_vehiclesupplier` b 
            INNER JOIN `tbl_vehicles` a  ON b.`id` = a.`supplier_id` 
            INNER JOIN tbl_workforcedepotassign w ON a.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE a.`status` != 5 AND
            a.`isdelete`= 0 AND b.`isdelete`= 0 AND " . $searchQuery . "
            GROUP BY b.id order by b.`id` " . $columnSortOrder . ", b.id ASC limit " . $row . "," . $rowperpage;
    // echo $query;
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($records3 = mysqli_fetch_array($userrow)) {
        $data[] = [
            'supplier' => $records3['supplier'],
            'count' => $records3['COUNT'],
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'supplier' => "",
            'count' => "",
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadcostcenterdata') {

    $searchQuery = "";
    if ($searchValue != '') {
        $searchQuery = " and (b.`name` LIKE '%" . $searchValue . "%')";
    }

    $userid = $_POST['userid'];
    $type = $_POST['type'];

    $mysql = new Mysql();
    $mysql->dbConnect();
    $sql1 = "SELECT COUNT(a.`id`) AS allcount FROM `tbl_costcenter` a 
    WHERE a.`isdelete` = 0 AND a.`isactive` = 0 AND a.`userid` LIKE ('" . $userid . "') AND a.`type` LIKE ('" . $type . "')";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT COUNT(a.`id`) AS allcount FROM `tbl_costcenter` a
    LEFT JOIN tbl_cost_department b ON b.`id` = a.`dept_id`
    LEFT JOIN tbl_cost_location c ON c.`id` = a.`loc_id`
    LEFT JOIN tbl_cost_service d ON d.`id` = a.`ser_id`
    WHERE a.`isdelete` = 0 AND a.`isactive` = 0 AND a.`userid` LIKE ('" . $userid . "') AND a.`type` LIKE ('" . $type . "')" . $searchQuery;

    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];

    $ord = "";


    $query = "SELECT a.`id` as id, b.`name` as dname, c.`name` as lname, d.`name` as sname FROM `tbl_costcenter` a 
    LEFT JOIN tbl_cost_department b ON b.`id` = a.`dept_id`
    LEFT JOIN tbl_cost_location c ON c.`id` = a.`loc_id`
    LEFT JOIN tbl_cost_service d ON d.`id` = a.`ser_id`
    WHERE a.`isdelete` = 0 AND a.`isactive` = 0 AND a.`userid` LIKE ('" . $userid . "') AND a.`type` LIKE ('" . $type . "')" . $searchQuery . " order by a.`id` " . $columnSortOrder . " limit " . $row . "," . $rowperpage;
    //echo $query;
    $vehiclesrow =  $mysql->selectFreeRun($query);

    $data = array();
    while ($typeresult = mysqli_fetch_array($vehiclesrow)) {

        $action = "<a href='#' class='edit' onclick=\"edit('" . $typeresult['id'] . "')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterow('" . $typeresult['id'] . "')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";
        $data[] = [
            'Department' => $typeresult['dname'],
            'Location' => $typeresult['lname'],
            'Service' => $typeresult['sname'],
            'Action' => $action

        ];
    }
    if (count($data) == 0) {
        $data[] = [
            'Department' => " ",
            'Location' => "No data Found!!",
            'Service' => " ",
            'Action' => " "
        ];
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loaddamagereportsdata') {
    ## Search 
    $vid = $_POST['vid'];
    $userid = $_POST['userid'];
    $searchQuery = " ";
    $drivarname = "";
    if ($searchValue != '') {
        $searchQuery = " and ( insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(*) as allcount from tbl_conditionalreportdata where isdelete = 0 AND `isactive`=0 
    AND vehicle_id=" . $vid . " AND `userid`= $userid";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_num_rows($sel);
    $totalRecords = $records;
    $sql2 = "select count(*) as allcount from tbl_conditionalreportdata WHERE isdelete = 0 AND `isactive`=0 
    AND `userid`= $userid AND vehicle_id=" . $vid . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_num_rows($sel);
    $totalRecordwithFilter = $records;
    $ord = "";

    $query = "SELECT * FROM `tbl_conditionalreportdata` 
    WHERE `isdelete`=0 AND `isactive`=0 AND `vehicle_id`=" . $vid . " AND `userid`=" . $userid . "" . $searchQuery . "
    order by id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['driver_id'] != NULL) {
            $query1 = "SELECT b.name as drivar FROM `tbl_conditionalreportdata` a 
                INNER JOIN tbl_contractor b ON a.`userid` = b.userid
                WHERE b.id = " . $typeresult['driver_id'];
            $typerow1 =  $mysql->selectFreeRun($query1);
            $typeresult1 = mysqli_fetch_array($typerow1);
            $drivarname = $typeresult1['drivar'];
        } else {
            $drivarname = '--';
        }

        $query3 = "SELECT COUNT(`id`) AS count FROM `tbl_vehicledamage_img` WHERE `isactive`=0 AND `isdelete`=0 
        AND `vehicle_id`=" . $vid . " AND `userid`=" . $userid . " AND `state`=0";
        $extdamage = mysqli_fetch_array($mysql->selectFreeRun($query3));

        $insert_date = date("Y-m-d H:i:s", strtotime($typeresult['insert_date']));

        $query4 = $query3 . " AND `insert_date` >= '" . $insert_date . "'";
        $newdamage = mysqli_fetch_array($mysql->selectFreeRun($query4));

        $action = "<a href='#' class='requestestimate' onclick=\"requestestimate('" . $typeresult['id'] . "','" . $vid . "','" . $typeresult['additional_info'] . "')\" data-toggle='tooltip' title='Request Estimate'>
        <span><i class='fas fa-external-link-square-alt fa-lg'></i></span></a>&nbsp;
        <a href='damagereportpdf.php?id=" . $typeresult["id"] . "&vid=" . $vid . "' class='edit' onclick=\"view('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='View'><span><i class='fas fa-eye fa-lg'></i></span></a>";

        $date1 = date("dS F, Y H:i", strtotime($typeresult['insert_date']));

        $query5 = "SELECT name FROM `tbl_user` WHERE `id`=1";
        $conductby = mysqli_fetch_array($mysql->selectFreeRun($query5));

        $data[] = [
            'conduct' => $conductby['name'],
            'driver' => $drivarname,
            'damages' => "<b>" . ($extdamage['count'] - $newdamage['count']) . "</b> Existent Damages",
            'newdamages' => "<div style='color:#e46a76;'><span class='label label-danger label-danger-subtle'>" . $newdamage['count'] . "</span>
                            New Damages <i class='fa fa-exclamation-triangle'></i></div>",
            'time' => $date1,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'conduct' => "",
            'driver' => "",
            'damages' => "No Data Found!!!!",
            'newdamages' => '',
            'time' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loaddamagetabledata') {
    $userid = $_POST['userid'];
    $vehicle = $_POST['vehicle'];
    $driver = $_POST['driver'];
    $severity = $_POST['severity'];

    $searchQuery = " ";
    $condition = ['', 'Broken', 'Dented', 'Chipped', 'Scratched', 'Missing', 'Worn'];
    $dmg_type = ['', 'Light', 'Moderate', 'Severe'];
    $state = ['Existent', 'Fixed'];
    if ($searchValue != '') {
        $searchQuery = " and ( vi.insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT vi.id) as allcount from tbl_vehicledamage_img  vi  INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`status` NOT IN (5)
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL where vi.isdelete = 0 AND vi.`isactive`=0";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_array($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "select count(DISTINCT vi.id) as allcount from tbl_vehicledamage_img vi INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`status` NOT IN (5)
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL WHERE vi.isdelete = 0 AND vi.`isactive`=0" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_array($sel);
    $totalRecordwithFilter = $records['allcount'];

    $query = "SELECT DISTINCT vi.* FROM `tbl_vehicledamage_img` vi
             INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`status` NOT IN (5)
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE vi.`isdelete`=0 AND vi.`isactive`=0 AND vi.`driver_id`LIKE '%" . $driver . "%' AND vi.`vehicle_id`LIKE '" . $vehicle . "' AND vi.`damage_type` LIKE '%" . $severity . "%' " . $searchQuery . " order by vi.id desc limit " . $row . "," . $rowperpage;
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();

    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['driver_id'] != NULL) {
            $query1 = "SELECT b.`name` as drivar FROM `tbl_conditionalreportdata` a INNER JOIN tbl_contractor b ON a.`userid` = b.`userid` WHERE b.`id`= " . $typeresult['driver_id'];
            $typerow1 =  $mysql->selectFreeRun($query1);
            $typeresult1 = mysqli_fetch_array($typerow1);
            $drivarname = $typeresult1['drivar'];
        } else {
            $drivarname = '--';
        }

        $query1 = "SELECT a.`id` as id,a.`registration_number` as reg_number,vsp.`name` as suppliername FROM `tbl_vehicles` a LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=a.`supplier_id` WHERE  a.`id`=" . $typeresult['vehicle_id'];
        $supplierrow =  $mysql->selectFreeRun($query1);
        $supplierresult = mysqli_fetch_array($supplierrow);

        $action = "<a href='#' class='mark' onclick=\"mark('" . $typeresult['id'] . "','" . $typeresult['damage_part'] . "')\"data-toggle='tooltip' title='Mark as Fixed'><span><i class='fas fa-check-circle'></i></span></a>&nbsp;
        <a href='vehicle_damage.php?vid=" . $supplierresult['id'] . "' class='edit' data-toggle='tooltip' title='View'><span><i class='fas fa-eye'></i></span></a>";

        $date1 = date("dS F, Y", strtotime($typeresult['insert_date']));

        $permission = "SELECT cost FROM `tbl_vehicledamage_cost` WHERE `userid` LIKE '" . $userid . "' and `vehicle_id` = " . $typeresult['vehicle_id'] . " and  `isactive` = 0 and `isdelete` = 0 and `damage_id`=" . $typeresult['id'];
        $permissionrow =  $mysql->selectFreeRun($permission);
        $pr_result = mysqli_fetch_array($permissionrow);
        if ($pr_result['cost'] == NULL) {
            $pr_result['cost'] = 0;
        }

        if ($typeresult['state'] == 0) {
            $statuscls = '#de2727';
            $statusname = 'EXISTENT';
        } else {
            $statuscls = '#199509';
            $statusname = 'FIXED';
        }
        $data[] = [
            'Vehicle' => $supplierresult['suppliername'] . " (" . $supplierresult['reg_number'] . ")",
            'Driver' => $drivarname,
            'Date' => $date1,
            'Severity' => $dmg_type[$typeresult['damage_type']],
            'Condition' => $condition[$typeresult['condition']],
            'Location' => $typeresult['damage_part'],
            'State' => "<span class='label label-secondary' style='background-color:" . $statuscls . ";'>" . $statusname . "</span>",
            'Cost' => "<i class='fas fa-pound-sign'> " . $pr_result['cost'] . ".00</i>",
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'Vehicle' => "",
            'Driver' => "",
            'Date' => "",
            'Severity' => "",
            'Condition' => "No Data Found!!!!",
            'Location' => "",
            'State' => "",
            'Cost' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadconditionalreportdata') {
    ## Search 
    $vid = $_POST['vehicle'];
    $did = $_POST['driver'];
    $cid = $_POST['conduct'];

    $searchQuery = " ";
    $drivarname = "";
    if ($searchValue != '') {
        $searchQuery = " and ( c.insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "select count(DISTINCT c.id) as allcount from tbl_conditionalreportdata c INNER JOIN tbl_vehicles b ON b.`id` = c.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  where c.isdelete = 0 AND c.`isactive`=0 ";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_num_rows($sel);
    $totalRecords = $records;
    $sql2 = "select count(DISTINCT c.id) as allcount from tbl_conditionalreportdata c INNER JOIN tbl_vehicles b ON b.`id` = c.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  WHERE c.isdelete = 0 AND c.`isactive`=0 " . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_num_rows($sel);
    $totalRecordwithFilter = $records;
    $ord = "";

    $query = "SELECT DISTINCT c.* FROM `tbl_conditionalreportdata` c
             INNER JOIN tbl_vehicles b ON b.`id` = c.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON b.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL 
             WHERE c.`isdelete`=0 AND c.`isactive`=0  AND c.`driver_id` LIKE '" . $did . "' AND c.`vehicle_id`LIKE '" . $vid . "' 
             AND c.`userid` LIKE '" . $cid . "' " . $searchQuery . " order by c.id desc limit " . $row . "," . $rowperpage;

    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        if ($typeresult['driver_id'] != NULL) {
            $query1 = "SELECT b.`name` as drivar FROM `tbl_conditionalreportdata` a 
                INNER JOIN tbl_contractor b ON a.`userid` = b.`userid`
                WHERE b.`id` = " . $typeresult['driver_id'];
            $typerow1 =  $mysql->selectFreeRun($query1);
            $typeresult1 = mysqli_fetch_array($typerow1);
            $drivarname = $typeresult1['drivar'];
        } else {
            $drivarname = '--';
        }

        $query3 = "SELECT COUNT(`id`) AS count FROM `tbl_vehicledamage_img` WHERE `isactive`=0 AND `isdelete`=0 AND `state`=0 AND `vehicle_id`=" . $typeresult['vehicle_id'];
        $extdamage = mysqli_fetch_array($mysql->selectFreeRun($query3));

        $insert_date = date("Y-m-d H:i", strtotime($typeresult['insert_date']));

        $query4 = $query3 . " AND `insert_date` LIKE '" . $insert_date . "%'";
        $newdamage = mysqli_fetch_array($mysql->selectFreeRun($query4));

        $action = "<a href='#' class='requestestimate' onclick=\"requestestimate('" . $typeresult['id'] . "','" . $typeresult["vehicle_id"] . "','" . $typeresult['additional_info'] . "')\"data-toggle='tooltip' title='Request Estimate'>
        <span><i class='fas fa-external-link-square-alt fa-lg'></i></span></a>&nbsp;
        <a href='damagereportpdf.php?id=" . $typeresult["id"] . "&vid=" . $typeresult["vehicle_id"] . "' class='edit' onclick=\"view('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='View'><span><i class='fas fa-eye fa-lg'></i></span></a>";

        $date1 = date("dS F, Y H:i", strtotime($typeresult['insert_date']));

        $query5 = "SELECT name FROM `tbl_user` WHERE `id`=1";
        $conductby = mysqli_fetch_array($mysql->selectFreeRun($query5));

        $query6 = "SELECT v.*,vm.`name` as makename,vmo.`name` as modelname,vsp.name as supplier FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id` WHERE v.`id`=" . $typeresult['vehicle_id'];
        $row =  $mysql->selectFreeRun($query6);
        $cntresult = mysqli_fetch_array($row);
        //echo $cntresult;

        $condition = ['Broken', 'Dented', 'Chipped', 'Scratched', 'Missing', 'Worn'];
        $mysql = new Mysql();
        $mysql->dbConnect();
        $statusquery = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete`= 0 AND `isactive`= 0 AND `state`=0 AND `vehicle_id`=" . $typeresult['vehicle_id'];
        $strow =  $mysql->selectFreeRun($statusquery);
        //echo $statusquery;
        while ($statusresult = mysqli_fetch_array($strow)) {
            $div .= "<li>" . $statusresult['damage_part'] . " - <u>" . $condition[$statusresult['condition']] . "</u></li>";
        }
        $data[] = [
            'Conduct' => $conductby['name'],
            'Vehicle' => $cntresult['supplier'] . " (" . $cntresult['registration_number'] . ")",
            //'Vehicle'=>$cntresult,
            'Driver' => $drivarname,
            'Fuel' => $typeresult['fuel'] . "%",
            'Damages' => "<b>" . ($extdamage['count'] - $newdamage['count']) . "</b> Existent Damages",
            'NewDamages' => "<div style='color:#e46a76;'><span class='label label-danger label-danger-subtle'>" . $newdamage['count'] . "</span>
                            New Damages <i class='fa fa-exclamation-triangle'></i></div>",
            'Date' => $date1,
            'Action' => $action,
            'reg_no' => $cntresult['registration_number'],
            'model' => $cntresult['makename'] . " - " . $cntresult['modelname'],
            'div' => $div
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'Conduct' => "",
            'Vehicle' => "",
            'Driver' => "",
            'Fuel' => "",
            'Damages' => "No Data Found!!!!",
            'NewDamages' => '',
            'Date' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadrentalconditionreportsdata') {
    ## Search 
    $vid = $_POST['vid'];
    $userid = $_POST['userid'];
    $rid = $_POST['rid'];
    $searchQuery = " ";
    $drivarname = "";
    if ($searchValue != '') {
        //$searchQuery = " and ( insert_date LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=" . $rid;
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_array($sel);

    $query = "SELECT * FROM `tbl_conditionalreportdata` 
    WHERE `isdelete`=0 AND `isactive`=0 AND `insert_date` <= '" . $records['insert_date'] . "' AND `vehicle_id`=" . $vid . " AND `userid` LIKE '" . $userid . "' ORDER BY id DESC LIMIT 1";
    $typerow =  $mysql->selectFreeRun($query);
    $data = array();
    while ($typeresult = mysqli_fetch_array($typerow)) {
        //print_r($typeresult);
        if ($typeresult['driver_id'] != NULL) {
            $query1 = "SELECT b.name as drivar FROM `tbl_conditionalreportdata` a 
                INNER JOIN tbl_contractor b ON a.`userid` = b.userid
                WHERE b.id = " . $typeresult['driver_id'];
            $typerow1 =  $mysql->selectFreeRun($query1);
            $typeresult1 = mysqli_fetch_array($typerow1);
            $drivarname = $typeresult1['drivar'];
        } else {
            $drivarname = '--';
        }

        $query3 = "SELECT COUNT(`id`) AS count FROM `tbl_vehicledamage_img` WHERE `isactive`=0 AND `isdelete`=0 
        AND `vehicle_id`=" . $vid . " AND `userid` LIKE '" . $userid . "' AND `state`=0";
        $extdamage = mysqli_fetch_array($mysql->selectFreeRun($query3));


        $action = "<a href='damagereportpdf.php?id=" . $typeresult["id"] . "&vid=" . $vid . "' class='edit' onclick=\"view('" . $typeresult['id'] . "')\"data-toggle='tooltip' title='View'><span><i class='fas fa-eye fa-lg'></i></span></a>";

        $date1 = date("dS F, Y H:i", strtotime($typeresult['insert_date']));

        $query5 = "SELECT name FROM `tbl_user` WHERE `id`=1";
        $conductby = mysqli_fetch_array($mysql->selectFreeRun($query5));

        $data[] = [
            'conduct' => $conductby['name'],
            'driver' => $drivarname,
            'damages' => "<b>" . ($extdamage['count'] - $newdamage['count']) . "</b> Existent Damages",
            'time' => $date1,
            'Action' => $action
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'conduct' => "",
            'driver' => "",
            'damages' => "No Data Found!!!!",
            'time' => "",
            'Action' => ""
        ];
        $i++;
    }
} else if (isset($_POST['action']) && $_POST['action'] == 'loadcontractorpaymenttabledata') {
    $userid = $_POST['userid'];
    $week = $_POST['week'];


    $searchQuery = " ";

    if ($searchValue != '') {
        $searchQuery = " and ( a.insert_date LIKE '%" . $searchValue . "%') ";
    }
    $mysql = new Mysql();
    $mysql->dbConnect();

    $sql1 = "SELECT count(DISTINCT a.`id`) as allcount FROM `tbl_contractorpayment` a INNER JOIN tbl_contractor b ON a.`cid` = b.`id` INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL  WHERE w.depot_id IN (b.`depot`) AND  a.`isdelete`= 0 AND a.`isactive`= 0 AND b.`isdelete` = 0 AND a.`week_no`=" . $week . "";
    $sel =  $mysql->selectFreeRun($sql1);
    $records = mysqli_fetch_array($sel);
    $totalRecords = $records['allcount'];
    //$totalRecords = $sql1;

    $sql2 = "SELECT count(DISTINCT a.`id`) as allcount FROM `tbl_contractorpayment` a  INNER JOIN tbl_contractor b ON a.`cid` = b.`id` INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL WHERE  w.depot_id IN (b.`depot`) AND a.`isdelete`= 0 AND a.`isactive`= 0 AND b.`isdelete` = 0 AND a.`week_no`=" . $week . "" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_array($sel);
    $totalRecordwithFilter = $records['allcount'];
    //$totalRecordwithFilter = $sql2;

    $query = "SELECT DISTINCT b.`name` as name,a.`amount` as amount, DATE_FORMAT(a.`insert_date`,'%D %M%, %Y') as date1 
             FROM `tbl_contractorpayment` a 
             INNER JOIN tbl_contractor b ON a.`cid` = b.`id` 
             INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE w.depot_id IN (b.`depot`) AND a.`isdelete`= 0 AND a.`isactive`= 0 AND b.`isdelete` = 0 AND a.`week_no`=" . $week . "" . $searchQuery . " order by a.id desc limit " . $row . "," . $rowperpage;

    $typerow =  $mysql->selectFreeRun($query);
    $data = array();

    while ($typeresult = mysqli_fetch_array($typerow)) {

        $data[] = [
            'Contractor' => $typeresult['name'],
            'Reason' => '',
            'Invoice' => '',
            'Amount' => "£ " . $typeresult['amount'],
            'Date' => $typeresult['date1']
        ];
    }

    if (count($data) == 0) {
        $data[] = [
            'Contractor' => "",
            'Reason' => "",
            'Invoice' => "No Data Found!!!!",
            'Amount' => "",
            'Date' => ""
        ];
        $i++;
    }
}

$response = array(
    "draw" => $draw,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);
echo json_encode($response, JSON_UNESCAPED_SLASHES);
