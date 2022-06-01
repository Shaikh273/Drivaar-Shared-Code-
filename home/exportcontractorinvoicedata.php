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

            // MADE A CHANGE HERE    ($modulo;  to  $modulo="";)
            $modulo     = "";
            while($dividend > 0){
                $modulo     = ($dividend - 1) % 26;
                $alpha      = $alphabet[$modulo].$alpha;
                $dividend   = floor((($dividend - $modulo) / 26));
            }
        }
        return str_pad($alpha,$pad,"0",STR_PAD_LEFT);
}
if (isset($_POST['action']) && $_POST['action'] == 'exportcontactorDetailsInvoiceTableData') 
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
    $sql="SELECT w.*,i.`invoice_no`,i.`cid`,i.`week_no`,i.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`, i.`weekyear`,d.`name` as dname,IF(w.`rateid`!=0,'NA',w.`date`) as routedate,
    IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,
    c.`name` as username, is1.`name` as sname
    FROM `tbl_contractorinvoice` i 
    INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
    INNER JOIN `tbl_depot` d ON d.`id`=i.`depot_id`
    INNER JOIN `tbl_contractortimesheet` w ON w.`cid`=i.`cid` AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`date` BETWEEN i.`from_date` AND i.`to_date` AND w.`value` NOT LIKE '0' AND w.`value` NOT LIKE ''
    INNER JOIN `tbl_paymenttype` p ON p.`id`=w.`rateid`
    INNER JOIN `tbl_invoicestatus` is1 ON is1.`id`=i.`status_id` 
    WHERE i.`isdelete`=0 AND i.`week_no`=$wikNo AND i.`istype`=1 AND i.`weekyear` = $wikYear ".$extraquery;
    $strow =  $mysql->selectFreeRun($sql);
    $dt="";
    $type=0;
    $totalAttendance=0;
    $finaltotal=0;
    $totalnet=0;
    $totalvat=0;
    $route="";
    $deptName = "";
    $deptRef = "";
    $prvDate="";
    $rvDate="";
    $vanDeduct = array();
    $frm_Date = "";
    $to_Date = "";
    $vatFlag1 = 0;
    while($tblresult = mysqli_fetch_array($strow)){
        $vatFlag1 = $tblresult['civat'];
        $frm_Date = $tblresult['from_date'];
        $to_Date = $tblresult['to_date'];
        if($tblresult['rateid']==0 && $tblresult['value']!=NULL && $tblresult['value']!='' && $tblresult['date']==$tblresult['routedate'])
        {
            $route = " (".$tblresult['value'].")";           
        }
        if($prvDate!=$tblresult['date'])
        {
            $prvDate = $tblresult['date'];
            $vanDeduct[] = "'$prvDate' BETWEEN tav.`start_date` AND tav.`end_date`";
        }
        if($tblresult['rateid']>0 && $tblresult['value']!=NULL && $tblresult['value']!='')
        {
            $adddate = date("d/m/Y",strtotime($tblresult['date']));
            $type=$tblresult['type'];
            if($type==1)
            {
                $typename = 'STANDARD SERVICES';
            }
            else if($type==2)
            {
                    $typename = 'BONUS';
            }
            else if($type==3)
            {
                    $typename = 'DEDUCTION';
            }
            $net = $tblresult['amount']*$tblresult['value'];
            $vat=0;
            if($vatFlag1==1){ 
                $vat = ($net*$tblresult['vat'])/100;   
            }
            $neg = "";
            if($type==3)
            {
                $net = -$net;
                $vat = -$vat;
                $neg = "-";
            }
            $total = $net+$vat;
           // " if($vatFlag1==1){ "£ ".$vat.""} else { "-" }";
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
                    <td>".$neg.$tblresult['amount']."</td>
                    <td>";
                    if($vatFlag1==1)
                    {
                        $dt .= $vat;
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
    
        

        // $vanWher = implode(" OR ",$vanDeduct);
        // $tqueryNE = "SELECT tav.*,tv.`registration_number` FROM `tbl_assignvehicle` tav INNER JOIN `tbl_vehicles` tv ON tv.`id`=tav.`vid` WHERE ($vanWher) AND tav.`isdelete`=0 AND tav.`driver`=".$tblresult['cid']." ORDER BY tav.`start_date` ASC";
        // $trowNE =  $mysql -> selectFreeRun($tqueryNE);
        // while($tresultNE = mysqli_fetch_array($trowNE))
        // {

        // }
    }

    $week_array = getStartAndEndDate($wikNo,$wikYear);
	$frm_Date = $week_array['week_start'];
	$to_Date = $week_array['week_end'];

    $mysql = new Mysql();
    $mysql->dbConnect();
    $VehRentQry = "SELECT DISTINCT va.*, i.`week_no`,v.`registration_number`,i.`invoice_no`,i.`vat` as civat,c.`name` as username,IF(i.`duedate` IS NULL,'NA',DATE_FORMAT(i.`duedate`, '%d-%m-%Y')) as duedate,is1.`name` as sname,d.`name` as dname
	FROM `tbl_contractorinvoice` i
	INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid`
    INNER JOIN `tbl_depot` d ON d.`id`=i.`depot_id`
	INNER JOIN `tbl_vehiclerental_agreement` va ON va.`driver_id`=c.`id` AND (va.`pickup_date` BETWEEN '$frm_Date' AND '$to_Date' OR va.`return_date` BETWEEN '$frm_Date' AND '$to_Date' OR '$frm_Date' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_Date' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 
	INNER JOIN `tbl_vehicles` v ON v.`id`=va.`vehicle_id` AND v.`supplier_id`>1 
	INNER JOIN `tbl_invoicestatus` is1 ON is1.`id`=i.`status_id`
	WHERE i.`isdelete`=0 AND i.`week_no`=$wikNo AND i.`istype`=1 AND i.`weekyear` = $wikYear ".$extraquery; 
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
                    <td>".$VehRentData['username']."</td>
                    <td>".$VehRentData['dname']."</td>
                    <td>DEDUCTION</td>
                    <td><b>Wk ".$VehRentData['week_no']."</td>
                    <td><small>" .$pikupD1. "</small></td>
                    <td>Van Deduction</td>
                    <td>".$VehRentData['registration_number']."</td>
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


    $return_arr['status']=1;
    $return_arr['dt']=$dt.$dt1;
    $return_arr['dt1']=$dt1;
    $return_arr['qry']='';
    $mysql->dbDisConnect();
    echo json_encode($return_arr);
}
?>