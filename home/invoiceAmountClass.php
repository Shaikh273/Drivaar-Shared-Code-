<?php
$webroot = 'http://drivaar.com/home/';
date_default_timezone_set('Europe/London');

class InvoiceAmountClass
{
	function InvoiceAmountClass() {
    }
    function getInvoiceNo($cid,$dt)
    {
    	$mysql = new Mysql();
	    $mysql->dbConnect();
	    $statusquery12 = "SELECT `invoice_no` FROM `tbl_contractorinvoice` WHERE `cid`=$cid AND '$dt' BETWEEN `from_date` AND `to_date`";
	    $strow12 =  $mysql->selectFreeRun($statusquery12);
	    if($getInvRow = mysqli_fetch_array($strow12))
	    {
	    	return $getInvRow['invoice_no'];
	    }else
	    {
	    	return NULL;
	    }
	    $mysql->dbDisConnect();
    }
	function ContractorInvoiceTotal($ginvID)
	{
	    $mysql = new Mysql();
	    $mysql->dbConnect();
	    $tblquery ="SELECT ci.`id`,ci.`week_no`,ci.`invoice_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,ci.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`,ci.`cid`,ci.`from_date`,ci.`to_date`,c.`vat_number` FROM `tbl_contractorinvoice` ci INNER JOIN `tbl_contractortimesheet` ct ON ct.cid=ci.cid INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` INNER JOIN `tbl_contractor` c ON c.id = ci.cid  WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`invoice_no`='".$ginvID."' AND ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`value` NOT LIKE '0' AND ct.`value` NOT LIKE '' AND ct.isdelete=0 ORDER BY p.`type` ASC, ct.`date` ASC,ct.`rateid` ASC";
	    $tblrow =  $mysql->selectFreeRun($tblquery);
	    $finaltotal = 0;
	    $totalnet = 0;
	    $totalvat = 0;
	    $cid3 = "";
	    $frm_date3 = "";
	    $to_date3 = "";
	    $vat = 0;
	    $wkNo = 0;
	    while ($tblresult = mysqli_fetch_array($tblrow)) {
	        $cid3 = $tblresult['cid'];
	        $frm_date3 = $tblresult['from_date'];
	        $to_date3 = $tblresult['to_date'];
	        $wkNo = $tblresult['week_no'];
	        if($tblresult['rateid']>0)
	        {
		        $net = $tblresult['amount'] * $tblresult['value'];
		        $vat = 0;
		        //if(isset($tblresult['vat_number']) && !empty($tblresult['vat_number']))
		        if($tblresult['civat']==1)
		        {
		            $vat = ($net * $tblresult['vat']) / 100;
		        }
		        if($tblresult['type']==3)
		        {
		            $vat = -$vat;
		            $net = -$net;
		        }

		        $total = $net + $vat;

		        $finaltotal += $total;
		        $totalnet += $net;
		        $totalvat += $vat;
	    	}
	    }
	    $vat1 = 0;
	    if($vat!=0)
	    {
	    	$vat1 = (float)0.2;
	    }
	    $VehRentQry = "SELECT DISTINCT va.*,v.registration_number FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_vehicles` v ON v.id=va.vehicle_id WHERE va.`driver_id`=$cid3 AND (va.`pickup_date` BETWEEN '$frm_date3' AND '$to_date3' OR va.`return_date` BETWEEN '$frm_date3' AND '$to_date3' OR '$frm_date3' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_date3' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 AND v.supplier_id>1 ORDER BY va.`pickup_date` ASC";
	    $VehRentFire =  $mysql -> selectFreeRun($VehRentQry);
	    while($VehRentData = mysqli_fetch_array($VehRentFire))
	        {
	            $pikupD = $VehRentData['pickup_date'];
	            if(strtotime($VehRentData['pickup_date'])<strtotime($frm_date3))
			    {
			        $pikupD = $frm_date3;
			    }
                while($to_date3>=$pikupD && $pikupD<=$VehRentData['return_date'] && $pikupD<=date("Y-m-d"))
	            {
	                $net = -$VehRentData['price_per_day'];
	                $vat = -$VehRentData['price_per_day'] * $vat1;
	                $total = $net + $vat;
	                $finaltotal += $total;
	                $totalnet += $net;
	                $totalvat += $vat;
	                $pikupD = date('Y-m-d', strtotime($pikupD . ' +1 day'));
	            }
	        }

	        $tquery = "SELECT c.amount FROM `tbl_contractorpayment` c INNER JOIN `tbl_contractorlend` l ON l.`id`=c.`loan_id` WHERE c.`cid`=$cid3 AND c.`week_no`=$wkNo AND c.`isdelete`=0";
            $trow =  $mysql -> selectFreeRun($tquery);
            while($tresult = mysqli_fetch_array($trow))
            {
                 $finaltotal = $finaltotal - $tresult['amount'];
            }
           

            $totalamount = ($finaltotal-$paidamount);
	    $mysql->dbDisConnect();
	    return  $finaltotal;
	}
}
?>