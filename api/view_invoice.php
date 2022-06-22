<?php
include 'DB/config.php';
include("../home/invoiceAmountClass.php");
date_default_timezone_set('Europe/London');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$action = $_POST['action'];
$view = $_POST['viewid'];
$invoiceno = $_POST['invoiceno'];
$status = 0;
$todaydate =  date('Y-m-d H:i:s');

if (!isset($_SESSION)) {
    session_start();
}
$ginvID = "";

function getAlphaCode($n, $pad)
{
    $alphabet   = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $n = (int)$n;
    if ($n <= 26) {
        $alpha =  $alphabet[$n - 1];
    } elseif ($n > 26) {
        $dividend   = ($n);
        $alpha      = '';
        $modulo     = "";
        while ($dividend > 0) {
            $modulo     = ($dividend - 1) % 26;
            $alpha      = $alphabet[$modulo] . $alpha;
            $dividend   = floor((($dividend - $modulo) / 26));
        }
    }
    return str_pad($alpha, $pad, "0", STR_PAD_LEFT);
}

$conid = 0;
$hideHeader = 0;
$dueDate = "";
$bank_name = "";
$account_number = "";
$bank_account_name = "";
$sort_code = "";
$result1 = array();
$finaldata = array();

if (isset($action) && isset($invoiceno) && isset($view)  && ($action == 'view_invoice')) {
    $mysql = new Mysql();
    $mysql->dbConnect();
    $data = base64_decode($view);
    $extDt = explode("#", $data);
    if (count($extDt) == 5) {
        $deco2 = base64_decode($extDt[4]);
        $verf = explode("#", $deco2);
        $Arrresult = array_diff($extDt, $verf);
        $Arrresult1 = array_diff($verf, $extDt);
        if (count($Arrresult) == 1 && count($Arrresult1) == 0 && $Arrresult[4] == $extDt[4]) {
            $hideHeader = 1;
        } else {
            header("Location: ../contractorAdmin/myinvoice.php");
        }
    }
    $wkCode = getAlphaCode($extDt[2], 2);
    $yrCode = (int)$extDt[3] - 2020;
    $tcode = '';
    if ($extDt[1] == 1) {
        $tcode = 'C';
    }
    $conid = $extDt[0];
    $cntqueryN = "SELECT c.*,ar.name as arname FROM `tbl_contractor` c LEFT JOIN `tbl_arrears` ar On ar.id=c.arrears WHERE c.id=" . $extDt[0];
    $cntrowN =  $mysql->selectFreeRun($cntqueryN);
    $result1N = mysqli_fetch_array($cntrowN);
    $depCode = "";
    if ($extDt[3] == 2021) {
        $depCode = getAlphaCode($result1N['depot'], 3);
    }
    $invId = $yrCode . $tcode . $depCode . $wkCode . $extDt[0];
    $ginvID = $invId;
    $bank_name = $result1N['bank_name'];
    $account_number = $result1N['account_number'];
    $bank_account_name = $result1N['bank_account_name'];
    $sort_code = $result1N['sort_code'];
    $week_start = new DateTime();
    $week_start->setISODate($extDt[3], $extDt[2]);
    $wkStart = date('Y-m-d', strtotime('-1 day', strtotime($week_start->format('Y-m-d'))));
    $wkEnd = date('Y-m-d', strtotime('+5 day', strtotime($week_start->format('Y-m-d'))));
    $onUpdate = "";

    if (isset($result1N['arname']) && $result1N['arname'] != NULL) {
        $arTemp = explode(" ", $result1N['arname'])[0];
        $dueDate = date('Y-m-d', strtotime('+' . $arTemp . ' week', strtotime($wkEnd)));
        $onUpdate = ", duedate='$dueDate'";
    } else {
        $onUpdate = "";
    }
    if (isset($result1N['vat_number']) || !empty($result1N['vat_number'])) {
        $vatset = 1;
    } else {
        $vatset = 0;
    }
    $values = array();
    $values[0]['cid'] = $extDt[0];
    $values[0]['invoice_no'] = $invId;
    $values[0]['week_no'] = $extDt[2];
    $values[0]['from_date'] = $wkStart;
    $values[0]['to_date'] = $wkEnd;
    $values[0]['weekyear'] = $extDt[3];
    $values[0]['vat'] = $vatset;
    $values[0]['istype'] = 1;
    $values[0]['depot_id'] = $result1N['depot'];
    if ($dueDate != "") {
        $values[0]['duedate'] = $dueDate;
    }
    $mysql->OnduplicateInsert('tbl_contractorinvoice', $values, "ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
    $cntquery = "SELECT c.*,ci.* FROM `tbl_contractor` c INNER JOIN `tbl_contractorinvoice` ci ON ci.cid=c.id WHERE ci.invoice_no='$invId'";
    $cntrow =  $mysql->selectFreeRun($cntquery);
    $result1 = mysqli_fetch_array($cntrow);
    $mysql->dbDisconnect();
    $fromdate = date("d/m/Y", strtotime($result1['from_date']));
    $todate = date("d/m/Y", strtotime($result1['to_date']));
    $dueDate = date("d/m/Y", strtotime($result1['duedate']));


    $imgid = 1;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $imgqry =  $mysql->selectWhere('tbl_orgdocument', 'id', '=', $imgid, 'int');
    $imgresult = mysqli_fetch_array($imgqry);
    $imagelogo = $webroot . 'uploads/organizationdocuments/' . $imgresult['file1'];

    $showvat = '';
    if ($result1['vat_number']) {
        $showvat = $result1['vat_number'];
    }

    $cmpquery = "SELECT * FROM `tbl_company` WHERE `isactive`=0 AND `isdelete`=0 ORDER BY id DESC LIMIT 1";
    $cmprow =  $mysql->selectFreeRun($cmpquery);
    $cmpresult = mysqli_fetch_array($cmprow);

    $form = array();
    $form[] = [
        'name' => $result1['name'],
        'address' => $result1['address'] . " , " . $result1['street_address'] . "-" . $result1['postcode'] . " ," . $result1['city'] . " " . $result1['state'],
        'utr' => $result1['utr'],
        'vat' => $showvat,
        'phone' => $result1['contact'],
        'email' => $result1['email']
    ];
    $to = array();
    $to[] = [
        'name' => $cmpresult['name'],
        'street' => $cmpresult['street'],
        'postcode' => $cmpresult['postcode'],
        'city' => $cmpresult['city'],
        'registration_no' => $cmpresult['registration_no'],
        'vat' => $cmpresult['vat']
    ];

    $tblGetDept = "SELECT `tbl_depot`.`name`,`tbl_depot`.`reference`,`tbl_contractor`.`vat_number` 
        FROM `tbl_contractor` 
        INNER JOIN `tbl_depot` on `tbl_depot`.`id`=`tbl_contractor`.`depot` 
        WHERE `tbl_contractor`.`id`=$conid";
    $tblDeptRow1 =  $mysql->selectFreeRun($tblGetDept);
    $tblDeptRow = mysqli_fetch_array($tblDeptRow1);
    $vatFlag = 0;

    $tblquery = "SELECT ci.`id`,ci.`week_no`,ci.`invoice_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,ci.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`,tdp.name as OTHDPT, tdp.reference as OTHDPTR, ct.othDepotId,ci.from_date,ci.to_date FROM `tbl_contractorinvoice` ci 
            INNER JOIN `tbl_contractortimesheet` ct ON ct.cid=ci.cid 
            INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
            INNER JOIN `tbl_depot` tdp ON tdp.id=ct.othDepotId WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`invoice_no`='" . $ginvID . "' AND ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`value` NOT LIKE '0' AND ct.`value` NOT LIKE '' AND ct.isdelete=0 ORDER BY p.`type` ASC, ct.`date` ASC,ct.`rateid` ASC";
    //echo $tblquery;
    $tblrow =  $mysql->selectFreeRun($tblquery);
    $type = 0;
    $totalAttendance = 0;
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    $route = "";
    $deptName = "";
    $deptRef = "";
    $prvDate = "";
    $vanDeduct = array();
    $frm_Date = "";
    $to_Date = "";

    $detail = array();
    $detail1 = array();
    $i = 0;
    $j=0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {
         
        $vatFlag = $tblresult['civat'];
        $frm_Date = $tblresult['from_date'];
        $to_Date = $tblresult['to_date'];
        if ($tblresult['rateid'] == 0 && $tblresult['value'] != NULL && $tblresult['value'] != '') {
            $route = " (" . $tblresult['value'] . ")";
        }
        if ($prvDate != $tblresult['date']) {
            if ($tblresult['othDepotId'] > 0) {
                $deptName = $tblresult['OTHDPT'];
                $deptRef = $tblresult['OTHDPTR'];
            } else {
                $deptName = $tblDeptRow['name'];
                $deptRef = $tblDeptRow['reference'];
            }
            $prvDate = $tblresult['date'];
            $vanDeduct[] = "'$prvDate' BETWEEN tav.`start_date` AND tav.`end_date`";
            $totalAttendance++;
        }
        if ($tblresult['rateid'] > 0 && $tblresult['value'] != NULL && $tblresult['value'] != '') {
            $adddate = date("d/m/Y", strtotime($tblresult['date']));
            if ($type != $tblresult['type']) {
                if ($tblresult['type'] == 1) {
                    $typename = 'STANDARD SERVICES';
                } else if ($tblresult['type'] == 2) {
                    $typename = 'BONUS';
                    $i++;
                } else if ($tblresult['type'] == 3) {
                    $typename = 'DEDUCTION';
                    $i++;
                }
                //  show typename loop
                $detail['type'][$i] = [
                    'typename'=>$typename,
                    'vatflag'=>$vatFlag
                ];
                $type = $tblresult['type'];
                
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

            // details
           
            $detail['type'][$i]['subdetail'][$j] = [
                'name' => $tblresult['name'] . ' - ' . $adddate . " - " . $route . "  - " . $deptRef,
                'route' => $route,
                'value' => $tblresult['value'],
                'amount' => '£ ' . $neg . $tblresult['amount'],
                'net' => '£ ' . $net,
                'vat' => '£ ' . $vat,
                'total' => '£ ' . $total,
                'i' => $i,
            ];

            $finaltotal += $total;
            $totalnet += $net;
            $totalvat += $vat;

        
            $j++;
        }
    }

    $vanWher = implode(" OR ", $vanDeduct);
    $tqueryNE = "SELECT tav.*,tv.registration_number FROM `tbl_assignvehicle` tav INNER JOIN `tbl_vehicles` tv ON tv.id=tav.vid WHERE ($vanWher) AND tav.isdelete=0 AND tav.driver=" . $result1['cid'] . " ORDER BY tav.`start_date` ASC";
    //echo $tqueryNE;
    $trowNE =  $mysql->selectFreeRun($tqueryNE);
    $q=0;
    $r=0;
    while ($tresultNE = mysqli_fetch_array($trowNE)) {
        if ($type != 3) {
            $typename = 'DEDUCTION';

            $detail['type2'][$q]=[
                'typename'=>$typename,
                'vatflag'=>$vatFlag,
            ];
            $type = 3;
        }
        $net = -$tresultNE['amount'];
        $vat = 0;
        if ($vatFlag == 1) {
            $vat = - ($tresultNE['amount'] * 20) / 100;
        }
        $total = $net + $vat;
        while ($vanDeduct[$i] >= $tresultNE['start_date'] && $vanDeduct <= $tresultNE['end_date']) {

            // details
            $detail['type2'][$q]['subdetail'][$r] = [
                'name' => ' Van deduction  - ' . $tresultNE['registration_number'] . " - " . $vanDeduct[$i],
                'route' => '',
                'value' => 1,
                'amount' => '£ ' . $net,
                'net' => '£ ' . $net,
                'vat' => '£ ' . $vat,
                'total' => '£ ' . $total,
                'r' => $r,
            ];

            $finaltotal += $total;
            $totalnet += $net;
            $totalvat += $vat;
            $r++;
        }
    }

    $frm_Date = $result1['from_date'];
    $to_Date = $result1['to_date'];
    $VehRentQry = "SELECT DISTINCT va.*,v.registration_number FROM `tbl_vehiclerental_agreement` va 
        INNER JOIN `tbl_vehicles` v ON v.id=va.vehicle_id 
        WHERE va.`driver_id`=$conid AND (va.`pickup_date` BETWEEN '$frm_Date' AND '$to_Date' OR va.`return_date` BETWEEN '$frm_Date' AND '$to_Date' OR '$frm_Date' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_Date' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 AND v.supplier_id>1  ORDER BY va.`pickup_date` ASC";
    //echo $VehRentQry;

    $VehRentFire =  $mysql->selectFreeRun($VehRentQry);
    $k=0;
    $l=0;
    while ($VehRentData = mysqli_fetch_array($VehRentFire)) {
        if ($type != 3) {
            $typename = 'DEDUCTION';
            $detail['type3'][$k] = [
                'typename'=>$typename,
                'vatflag'=>$vatFlag,
            ];
            $type = 3;
        }
        $pikupD = $VehRentData['pickup_date'];
        if (strtotime($VehRentData['pickup_date']) < strtotime($frm_Date)) {
            $pikupD = $frm_Date;
        }
        while ($to_Date >= $pikupD && $pikupD <= $VehRentData['return_date'] && $pikupD <= date("Y-m-d")) {
            $net = -$VehRentData['price_per_day'];
            $vat = 0;
            if ($vatFlag == 1) {
                $vat = -$VehRentData['price_per_day'] * 0.2;
            }

            $total = $net + $vat;

            // details
            $detail['type3'][$k]['subdetail'][$l] = [
                'name' => ' Van deduction  - ' . $pikupD . " - " . "[".$VehRentData['registration_number']."]",
                'route' => '',
                'value' => 1,
                'amount' => '£ ' . $net,
                'net' => '£ ' . $net,
                'vat' => '£ ' . $vat,
                'total' => '£ ' . $total,
            ];


            $finaltotal += $total;
            $totalnet += $net;
            $totalvat += $vat;
            $pikupD = date('Y-m-d', strtotime($pikupD . ' +1 day'));

            $l++;
        }
        
    }

    $paidamount = 0;
    $singleamount = 0;
    $resn = array();
    $amnt123 = array();
    $tquery = "SELECT c.*,l.`amount` as totalamount,l.`no_of_instal`,c.reason FROM `tbl_contractorpayment` c INNER JOIN `tbl_contractorlend` l ON l.`id`=c.`loan_id` WHERE c.`cid`=" . $result1['cid'] . " AND c.`week_no`=" . $result1['week_no'] . " AND c.`isdelete`=0";
    $trow =  $mysql->selectFreeRun($tquery);
    while ($tresult = mysqli_fetch_array($trow)) {
        $resn[] = $tresult['reason'];
        $amnt123[] = $tresult['amount'];
        $paidamount += $tresult['amount'];
        $singleamount += $tresult['totalamount'] / $tresult['no_of_instal'];
    }
    $totalamount = ($finaltotal - $paidamount);

    $reson=array();
    if($paidamount>0)
    {
        $i=0;
        while($resn[$i])
        {
            $reson['reason']=$resn[$i];
            $reson['amount']= $amnt123[$i];
            $i++;
        }
    }

    $finaldata[] = [
        'image_logo' => $imagelogo,
        'inovice_no' => $result1['invoice_no'],
        'status_name' => $result1['statusname'],
        'week_no' => $result1['week_no'],
        'period' => $fromdate . ' - ' . $todate,
        'associate' => $result1['name'],
        'totalAttendance' => $totalAttendance,
        'form' => $form,
        'to' => $to,
        'detail' => $detail,
        'net'=>$totalnet,
        'vat'=>$totalvat,
        'total'=>$finaltotal,
        'reason'=>$reson,
        'gross'=>$totalamount,
        'duedate'=>$dueDate,
        'bank_name'=>$bank_name,
        'sort_code'=>$sort_code,
        'account_number'=>$account_number
    ];

    $status = 1;
    $success = 'success';
    $data1 = $finaldata;



    if ($status == 1) {
        $array = array("success" => $success, "data" => $data1);
    } else {
        $array = array("errorcode" => $errorcode, "error" => $error);
    }
    echo json_encode(array('status' => $status, 'data' => $array));
} else {
    echo 'error';
}
