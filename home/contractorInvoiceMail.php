<?php
    include 'DB/config.php';
    
    $ginvID = "";
    function getAlphaCode($n,$pad)
    {
	    $alphabet   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	    $n = (int)$n;
	    if($n <= 26){
	        $alpha =  $alphabet[$n-1];
	    } elseif($n > 26) {
	        $dividend   = ($n);
	        $alpha      = '';
	        $modulo      ='';
	        while($dividend > 0){
	            $modulo     = ($dividend - 1) % 26;
	            $alpha      = $alphabet[$modulo].$alpha;
	            $dividend   = floor((($dividend - $modulo) / 26));
	        }
	    }
        return str_pad($alpha,$pad,"0",STR_PAD_LEFT);
	}
    $conid = 0;
    $hideHeader =0;
    $dueDate = "";
    $bank_name="";
    $account_number="";
    $bank_account_name="";
    $sort_code="";
   
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $data = base64_decode($_GET['invkey']);
        $extDt = explode("#",$data);
        if(count($extDt)==5)
        {
            $deco2 = base64_decode($extDt[4]);
            $verf = explode("#", $deco2);
            $Arrresult=array_diff($extDt,$verf);
            $Arrresult1=array_diff($verf,$extDt);
            if(count($Arrresult)==1 && count($Arrresult1)==0 && $Arrresult[4]==$extDt[4])
            {
                $hideHeader=1;

            }else
            {
                header("Location: ../contractorAdmin/myinvoice.php");
            }
        }
        $wkCode = getAlphaCode($extDt[2],2);
		$yrCode = (int)$extDt[3] - 2020;
		$tcode='NULL';
		if($extDt[1]==1)
		{
			$tcode='C';
		}
        $conid = $extDt[0];
        $cntqueryN = "SELECT c.*,ar.name as arname FROM `tbl_contractor` c LEFT JOIN `tbl_arrears` ar On ar.id=c.arrears WHERE c.id=".$extDt[0];
        $cntrowN =  $mysql -> selectFreeRun($cntqueryN);
        $result1N = mysqli_fetch_array($cntrowN);
        $depCode="";
        if($extDt[3]==2021)
        {
            $depCode = getAlphaCode($result1N['depot'],3);
        }
        if (isset($result1N['vat_number']) || !empty($result1N['vat_number'])) 
        {
        	$vatset=1;
        }
        else
        {
        	$vatset=0;
        }
		$invId = $yrCode.$tcode.$depCode.$wkCode.$extDt[0];
		$ginvID = $invId;
        // echo "<script>alert('".$extDt[0]."---".$extDt[1]."---".$extDt[2]."---".$extDt[3]."----".$invId."');</script>";
        $bank_name=$result1N['bank_name'];
        $account_number=$result1N['account_number'];
        $bank_account_name=$result1N['bank_account_name'];
        $sort_code=$result1N['sort_code'];
		$week_start = new DateTime();
		$week_start->setISODate($extDt[3],$extDt[2]);
		$wkStart = date('Y-m-d', strtotime('-1 day', strtotime($week_start->format('Y-m-d'))));
		$wkEnd = date('Y-m-d', strtotime('+5 day', strtotime($week_start->format('Y-m-d'))));
        $onUpdate = "";
        if(isset($result1N['arname']) && $result1N['arname']!=NULL)
        {
            $arTemp = explode(" ",$result1N['arname'])[0];
            $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($wkEnd)));
            $onUpdate = ", duedate='$dueDate'";
        }else
        {
            $onUpdate = "";
        }
		$values = array();
		$values[0]['cid']= $extDt[0];
		$values[0]['invoice_no']= $invId;
		$values[0]['week_no']= $extDt[2];
		$values[0]['vat']= $vatset;
		$values[0]['from_date']= $wkStart;
		$values[0]['to_date']= $wkEnd;
		$values[0]['weekyear']= $extDt[3];
		$values[0]['istype']= 1;
        $values[0]['depot_id']= $result1N['depot'];
        if($dueDate!="")
        {
            $values[0]['duedate']= $dueDate;
        }
        $mysql -> OnduplicateInsert('tbl_contractorinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");
        $cntquery = "SELECT c.*,ci.* FROM `tbl_contractor` c INNER JOIN `tbl_contractorinvoice` ci ON ci.cid=c.id WHERE ci.invoice_no='$invId'";
        $cntrow =  $mysql -> selectFreeRun($cntquery);
        $result1 = mysqli_fetch_array($cntrow);
        $cmpquery = "SELECT * FROM `tbl_company` WHERE `isactive`=0 AND `isdelete`=0 ORDER BY id DESC LIMIT 1";
        $cmprow =  $mysql -> selectFreeRun($cmpquery);
        $cmpresult = mysqli_fetch_array($cmprow);
        $mysql->dbDisconnect();
        $fromdate = date("d/m/Y",strtotime($result1['from_date']));
        $todate = date("d/m/Y",strtotime($result1['to_date']));
        $dueDate = date("d/m/Y",strtotime($result1['duedate']));
        

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
	<title><?php echo "Invoice - ".$ginvID;?></title>
	<meta name="generator" content="https://conversiontools.io" />
	<meta name="author" content="A2E_Engine"/>
	<meta name="created" content="2021-12-14T20:49:14"/>
	<meta name="changedby" content="Asad Kardame"/>
	<meta name="changed" content="2021-12-15T04:48:30"/>
	<meta name="AppVersion" content="16.0300"/>
	<meta name="Company" content="Investintech.com Inc."/>
	<meta name="DocSecurity" content="0"/>
	<meta name="HyperlinksChanged" content="false"/>
	<meta name="LinksUpToDate" content="false"/>
	<meta name="ScaleCrop" content="false"/>
	<meta name="ShareDoc" content="false"/>
	
	<style type="text/css">

 * {
        -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
        color-adjust: exact !important;  /*Firefox*/
    }
		body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Arial"; font-size:x-small}
		a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
		a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
		comment { display:none;  } 
	</style>
	
</head>

<body onload="myFunction()">
<table cellspacing="0" border="0" style="border: 2px solid #999;" id='printTable'>
	<colgroup width="64"></colgroup>
	<colgroup width="546"></colgroup>
	<colgroup width="76"></colgroup>
	<colgroup width="72"></colgroup>
	<colgroup width="77"></colgroup>
	<colgroup width="175"></colgroup>
	<colgroup width="54"></colgroup>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999; background-color: #f0f0f0;">

		<td rowspan=4 height="70" align="center" valign=bottom><img src="http://drivaar.com/home/logo/fulllogo.jpg" alt="" style="height: 100px;width: 100px;"></td>
		<td align="center" valign=bottom><span style="font-size: 20px;color: black;">Invoice:</span><span style="font-size: 20px;color: #DD5A43!important;"><?php echo $result1['invoice_no'];?></span></td>
		<td colspan=5 align="left" valign=bottom style="font-size: 20px;color: black;">Week: <?php echo $result1['week_no'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;background-color: #f0f0f0;">
		<td align="center" valign=bottom style="color:rgba(59, 130, 246, 0.5);">Payment Date: </td>
		<td colspan=5 align="left" valign=bottom style="color:rgba(59, 130, 246, 0.5);">Period: <?php echo $fromdate.' - '.$todate;?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;background-color: #f0f0f0 !important;">
		<td align="left" valign=bottom></td>
		<td colspan=5 align="left" valign=bottom style="color:rgba(59, 130, 246, 0.5);">Associate: <?php echo $result1['name'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;background-color: #f0f0f0;">
		<td align="left" valign=bottom><br></td>
		<td colspan=5 align="left" valign=bottom style="color:rgba(59, 130, 246, 0.5);">Total Attendence: <span  id="totalAttendance"></span></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;background-color: #f0f0f0;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><font face="Arial Bold" size=1><span style="background-color: #2196F3;color: white; padding: 4px; font-weight: bolder;">FROM</span></font></td>
		<td align="left" valign=bottom><font face="Arial Bold" size=1><br></font></td>
		<td colspan=4 align="left" valign=bottom><font face="Arial Bold" size=1><span style="background-color: #04AA6D;color: white; padding: 4px; font-weight: bolder;">To</span></font></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><font face="Arial Bold" style="font-weight: bolder;"><?php echo $result1['name'];?></font></td>
		<td align="left" valign=bottom><font face="Arial Bold"><br></font></td>
		<td colspan=4 align="left" valign=bottom style="font-weight: bolder;"><?php echo $cmpresult['name'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><?php echo $result1['address']. "<br>" .$result1['street_address'] . "-" .$result1['postcode'] . "<br>" .$result1['city'] ." ".$result1['state'];?></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=4 align="left" valign=bottom><?php echo $cmpresult['street'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>-</td>
		<td align="left" valign=bottom><br></td>
		<td colspan=4 align="left" valign=bottom><?php echo $cmpresult['postcode'].", ".$cmpresult['city'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>UTR: <?php echo $result1['utr'];?></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=4 align="left" valign=bottom>Register Number: <?php echo $cmpresult['registration_no'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>Phone: <?php echo $result1['contact'];?></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=4 align="left" valign=bottom>VAT: <?php echo $cmpresult['vat'];?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom>Email: <?php echo $result1['email'];?></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
<?php
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $tblGetDept = "SELECT `tbl_depot`.`name`,`tbl_depot`.`reference`,`tbl_contractor`.`vat_number` FROM `tbl_contractor` INNER JOIN `tbl_depot` on `tbl_depot`.`id`=`tbl_contractor`.`depot` WHERE `tbl_contractor`.`id`=$conid";
    $tblDeptRow1 =  $mysql -> selectFreeRun($tblGetDept);
    $tblDeptRow = mysqli_fetch_array($tblDeptRow1);
    $vatFlag = 0;
     // if (isset($tblDeptRow['vat_number']) || !empty($tblDeptRow['vat_number'])) 
     // {
     //    $vatFlag = 1;
     // }
    $tblquery = "SELECT ci.`id`,ci.`week_no`,ci.`invoice_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,ci.`vat` as civat,p.`name`,p.`type`,p.`amount`,p.`vat`,tdp.name as OTHDPT, tdp.reference as OTHDPTR, ct.othDepotId,ci.from_date,ci.to_date 
    	FROM `tbl_contractorinvoice` ci 
    	INNER JOIN `tbl_contractortimesheet` ct ON ct.cid=ci.cid INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` INNER JOIN `tbl_depot` tdp ON tdp.id=ct.othDepotId WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`invoice_no`='".$ginvID."' AND ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`value` NOT LIKE '0' AND ct.`value` NOT LIKE '' AND ct.isdelete=0 ORDER BY p.`type` ASC, ct.`date` ASC,ct.`rateid` ASC";
                //echo $tblquery;
    $tblrow =  $mysql -> selectFreeRun($tblquery);
    $type=0;
    $totalAttendance=0;
    $finaltotal=0;
    $totalnet=0;
    $totalvat=0;
    $route="";
    $deptName = "";
    $deptRef = "";
    $prvDate="";
    $vanDeduct = array();
    $frm_Date = "";
    $to_Date = "";
    while($tblresult = mysqli_fetch_array($tblrow))
    {
    	$vatFlag = $tblresult['civat'];
        $frm_Date = $tblresult['from_date'];
        $to_Date = $tblresult['to_date'];
    	if($tblresult['rateid']==0 && $tblresult['value']!=NULL && $tblresult['value']!='')
    	{
    		$route = " (".$tblresult['value'].")";
    	}
        if($prvDate!=$tblresult['date'])
        {
            if($tblresult['othDepotId']==1)
            {
                $deptName = $tblresult['OTHDPT'];
                $deptRef = $tblresult['OTHDPTR'];
            }else
            {
                $deptName = $tblDeptRow['name'];
                $deptRef = $tblDeptRow['reference'];
            }
            $prvDate = $tblresult['date'];
            $vanDeduct[] = "'$prvDate' BETWEEN tav.`start_date` AND tav.`end_date`";
            $totalAttendance++;
        }
    	if($tblresult['rateid']>0 && $tblresult['value']!=NULL && $tblresult['value']!='')
    	{
        $adddate = date("d/m/Y",strtotime($tblresult['date']));
        if($type!=$tblresult['type'])
        {
            if($tblresult['type']==1)
            {
                $typename = 'STANDARD SERVICES';
            }
            else if($tblresult['type']==2)
            {
                 $typename = 'BONUS';
            }
            else if($tblresult['type']==3)
            {
                 $typename = 'DEDUCTION';
            }
            ?>


	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999; background-color: #e9ecef;">
		<td colspan=3 height="18" align="center" valign=bottom><?php echo $typename;?></td>
		<td align="left" valign=bottom>QUANTITY</td>
		<td align="left" valign=bottom>UNIT COST</td>
		<td align="left" valign=bottom>NET</td>
		<?php if($vatFlag==1){ ?>
        <td align="left" valign=bottom>VAT</td>
        <?php }?>
		<td align="left" valign=bottom>GROSS</td>
	</tr>
	<?php
        $type=$tblresult['type'];
    }
   

        $net = $tblresult['amount']*$tblresult['value'];
        $vat=0;
        if($vatFlag==1){ 
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
        ?>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td colspan=3 height="18" align="left" valign=bottom><?php echo $tblresult['name'].' - <strong>'.$adddate."</strong> ".$route." <strong>".$deptRef."</strong>"; ?></td>
		<td align="left" valign=bottom sdval="1" sdnum="1033;0;0"><?php echo $tblresult['value']; ?></td>
		<td align="left" valign=bottom sdval="1.8" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$neg.$tblresult['amount']; ?></td>
		<td align="left" valign=bottom sdval="1.8" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$net; ?>
		<?php if($vatFlag==1){ ?>
	        <td align="left" valign=bottom sdval="1.8" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$vat; ?></td>
	    <?php }?>
		<td align="left" valign=bottom sdval="1.8" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$total; ?></td>
	</tr>
	<?php
            $finaltotal+=$total;
            $totalnet+=$net;
            $totalvat+=$vat;
        }
    }
    $vanWher = implode(" OR ",$vanDeduct);
    $tqueryNE = "SELECT tav.*,tv.registration_number FROM `tbl_assignvehicle` tav INNER JOIN `tbl_vehicles` tv ON tv.id=tav.vid WHERE ($vanWher) AND tav.isdelete=0 AND tav.driver=".$result1['cid']." ORDER BY tav.`start_date` ASC";
    $trowNE =  $mysql -> selectFreeRun($tqueryNE);
    while($tresultNE = mysqli_fetch_array($trowNE))
    {
    	if($type!=3)
        {
        	$typename = 'DEDUCTION';
                
                ?>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999; background-color: #e9ecef;">
		<td colspan=3 height="18" align="center" valign=bottom>DEDUCTION</td>
		<td align="left" valign=bottom>QUANTITY</td>
		<td align="left" valign=bottom>UNIT COST</td>
		<td align="left" valign=bottom>NET</td>
		<?php if($vatFlag==1){ ?>
        <td align="left" valign=bottom>VAT</td>v
        <?php }?>
		<td align="left" valign=bottom>GROSS</td>
	</tr>

	<?php
		        $type=3;
		}
		$net = -$tresultNE['amount'];
		$vat=0;
		if($vatFlag==1){ 
		 $vat = -($tresultNE['amount']*20)/100;
		}
		$total = $net+$vat;
		$i=0;
		while($vanDeduct[$i]>=$tresultNE['start_date'] && $vanDeduct<=$tresultNE['end_date'])
		{
			?>

	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td colspan=3 height="18" align="left" valign=bottom>Van deduction [<?php echo $tresultNE['registration_number']; ?>] <?php echo $vanDeduct;?></td>
		<td align="left" valign=bottom sdval="1" sdnum="1033;0;0">1</td>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$net; ?></td>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$net; ?></td>
		<?php if($vatFlag==1){ ?>
            <td  align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$vat; ?></td>
        <?php }?>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$total; ?></td>
	</tr>

	<?php

	        $finaltotal+=$total;
	        $totalnet+=$net;
	        $totalvat+=$vat;
	        $i++;
	}
	}
	// $frm_Date;
	// $to_Date;
	$VehRentQry = "SELECT DISTINCT va.*,v.registration_number FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_vehicles` v ON v.id=va.vehicle_id WHERE va.`driver_id`=$conid AND (va.`pickup_date` BETWEEN '$frm_Date' AND '$to_Date' OR va.`return_date` BETWEEN '$frm_Date' AND '$to_Date' OR '$frm_Date' BETWEEN va.`pickup_date` AND va.`return_date` OR '$to_Date' BETWEEN va.`pickup_date` AND va.`return_date`) AND va.`iscompalete`=1 AND va.`isdelete`=0 AND v.supplier_id>1  ORDER BY va.`pickup_date` ASC";
	$VehRentFire =  $mysql -> selectFreeRun($VehRentQry);
	while($VehRentData = mysqli_fetch_array($VehRentFire))
	{
	    if($type!=3)
	    {
	    $typename = 'DEDUCTION';
	        
	        ?>
	   <tr style="border-top: 2px solid #999;border-bottom: 2px solid #999; background-color: #e9ecef;">
		<td colspan=3 height="18" align="center" valign=bottom><?php echo $typename;?></td>
		<td align="left" valign=bottom>QUANTITY</td>
		<td align="left" valign=bottom>UNIT COST</td>
		<td align="left" valign=bottom>NET</td>
		<?php if($vatFlag==1){ ?>
        <td align="left" valign=bottom>VAT</td>v
        <?php }?>
		<td align="left" valign=bottom>GROSS</td>
	</tr>
	<?php
        $type=3;
    }
    $pikupD = $VehRentData['pickup_date'];
    if(strtotime($VehRentData['pickup_date'])<strtotime($frm_Date))
    {
        $pikupD = $frm_Date;
    }
    while($to_Date>=$pikupD && $pikupD<=$VehRentData['return_date'] && $pikupD<=date("Y-m-d"))
    {
        $net = -$VehRentData['price_per_day'];
        $vat=0;
        if($vatFlag==1){ 
         $vat = -$VehRentData['price_per_day'] * 0.2;
        }
        
        $total = $net + $vat;
    ?>

	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td colspan=3 height="18" align="left" valign=bottom>Van deduction - <strong><?php echo $pikupD;?></strong> - [<?php echo $VehRentData['registration_number']; ?>]</td>
		<td align="left" valign=bottom sdval="1" sdnum="1033;0;0">1</td>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$net; ?></td>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$net; ?></td>
		<?php if($vatFlag==1){ ?>
            <td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$vat; ?></td>
        <?php }?>
		<td align="left" valign=bottom sdval="-30.7" sdnum="1033;0;[$&pound;-809]#,##0.00"><?php echo '£ '.$total; ?></td>
	</tr>
	 <?php

                $finaltotal+=$total;
                $totalnet+=$net;
                $totalvat+=$vat;
                $pikupD = date('Y-m-d', strtotime($pikupD . ' +1 day'));
            }
        }
    


    $paidamount=0;
    $singleamount=0;
    $resn = array();
    $amnt123 = array();
    $tquery = "SELECT c.*,l.`amount` as totalamount,l.`no_of_instal` FROM `tbl_contractorpayment` c INNER JOIN `tbl_contractorlend` l ON l.`id`=c.`loan_id` WHERE c.`cid`=".$result1['cid']." AND c.`week_no`=".$result1['week_no']." AND c.`isdelete`=0";
    $trow =  $mysql -> selectFreeRun($tquery);
    $flg=0;
    // if($tresult = mysqli_fetch_array($trow))
    // {
    // 	$flg=1;
    //      $paidamount = $tresult['amount'];
    //      $singleamount = $tresult['totalamount']/$tresult['no_of_instal'];
    // }
    while($tresult = mysqli_fetch_array($trow))
    {
    	 $flg=1;
         $resn[] = $tresult['reason'];
         $amnt123[] = $tresult['amount'];
         $paidamount += $tresult['amount'];
         $singleamount += $tresult['totalamount']/$tresult['no_of_instal'];
    }
   

    $totalamount = ($finaltotal-$paidamount);

    $mysql -> dbDisConnect();
    ?>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom sdnum="1033;0;0"><br></td>
		<td align="left" valign=bottom sdnum="1033;0;[$&pound;-809]#,##0.00"><br></td>
		<td align="left" valign=bottom sdnum="1033;0;[$&pound;-809]#,##0.00"><br></td>
		<td align="left" valign=bottom sdnum="1033;0;[$&pound;-809]#,##0.00"><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td rowspan=4 align="left" valign=bottom>I agree and give consent to deduct fees and van charges from my invoices as shown above and approved by myself, to take advantage of combined purchase discounts via Bryanston Logistics Limited, and to repay any driver penalties enforced by law, and/or van repairs on rented vehicles I drive and have total responsibility for.</td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=3 rowspan=3 align="right" valign=bottom>NET : &pound; <?php echo $totalnet;?><br>VAT : &pound; <?php echo $totalvat;?><br>Total : &pound; <?php echo $finaltotal;?>
	<?php
        // if($flg==1)
        // {
            ?>
              <!-- <span><?php //echo $tresult['reason'];?> : -£ <?php //echo $tresult['amount'];?></span> -->
            <?php
        // }
        if($flg==1)
        {
            $i=0;
            while($resn[$i])
            {
            ?>
              <span><?php echo $resn[$i];?> <br>: -£ <?php echo $amnt123[$i];?></span><br>
            <?php
            $i++;
            }
        }
        ?></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=4 align="center" valign=bottom><font face="Arial Italic">The VAT shown is your output tax due to HM Revenue &amp; Customs</font></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="18" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000">Bank Name: <?php echo $bank_name;?></font></b></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000"><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
	</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000">Sort Code: <?php echo $sort_code;?></font></b></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000"><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=2 align="center" valign=bottom><font size=3>GROSS : <span style="color:green;">&pound; <?php echo $totalamount;?></span></font></td>
		</tr>
	<tr style="border-top: 2px solid #999;border-bottom: 2px solid #999;">
		<td height="20" align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000">Account Number: <?php echo $account_number;?></font></b></td>
		<td align="left" valign=bottom><b><font face="Segoe UI" size=1 color="#000000"><br></font></b></td>
		<td align="left" valign=bottom><br></td>
		<td align="left" valign=bottom><br></td>
		<td colspan=2 align="center" valign=bottom><font size=3 style="font-weight: bolder;">Due Date : <?php echo $dueDate;?></font></td>
	</tr>
</table>
<input type="hidden" id="totalAttendanceval" value="<?php echo $totalAttendance;?>">
<button type="button" style="margin-top: 10px; margin-left: 8px;" onclick="javascript:window.print();">PRINT</button>

<script type="text/javascript">
     function myFunction()
    {
    	document.getElementById('totalAttendance').innerHTML=document.getElementById('totalAttendanceval').value;
    }
 </script>
</body>

</html>
