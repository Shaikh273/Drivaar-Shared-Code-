<?php
include 'DB/config.php';

$cid = $_GET['id'];
$todaydate = date('Y-m-d');

function generate_Uniqid()
{
    $invoiceno = str_pad(rand(000000,999999), 10, "0", STR_PAD_LEFT);
    if($invoiceno>0)
    {
    	$mysql = new Mysql();
		$mysql -> dbConnect();
        $invoiceqry = "SELECT * FROM `tbl_contractorinvoice` WHERE `invoice_no`='$invoiceno' AND `isdelete`=0 AND `isactive`=0";
        $invoicerow =  $mysql -> selectFreeRun($invoiceqry);
        $invresult = mysqli_fetch_array($invoicerow);
        if($invresult['invoice_no']>0)
        {
            $InvoiceId = generate_Uniqid();
        }
        else
        {
            $InvoiceId = $invoiceno;
        }
        $mysql -> dbConnect();
    }
    return $InvoiceId;
}

function weekOfYear($date1) {
	$date = strtotime($date1);
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        $weekOfYear = 0;    
    }
    return $weekOfYear;
}

$currentweek = weekOfYear($todaydate);

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


$mysql = new Mysql();
$mysql -> dbConnect();
if($cid)
{
	$query = "SELECT * FROM `tbl_contractor` WHERE `id`='$cid' AND `isdelete`=0 AND `isactive`=0 AND `iscomplated`=1";
}
else
{
	$query = "SELECT * FROM `tbl_contractor` WHERE `isdelete`=0 AND `isactive`=0 AND `iscomplated`=1";
}

$row =  $mysql -> selectFreeRun($query);
while($result = mysqli_fetch_array($row))
{
	$fromDate='';
	$today = '';
	if($result['vat_number']>0)
	{
		$vat = 1;
	}
	else
	{
		$vat = 0;
	}
	if($result['arrears']>0)
	{
		$duedate= date("Y/m/d", strtotime("$todaydate +".$result['arrears']." week"));
	}
	else
	{
		$duedate= date("Y/m/d", strtotime("$todaydate + 1 week"));
	}
	
	if($result['depot']>0)
	{

		$invoiceqry1 = "SELECT * FROM `tbl_contractorinvoice` WHERE `cid`=".$result['id']." AND `isdelete`=0 AND `isactive`=0 AND `istype`=1 ORDER BY `week_no` DESC LIMIT 1";
        $invoicerow1 =  $mysql -> selectFreeRun($invoiceqry1);
        $invresult1 = mysqli_fetch_array($invoicerow1);
        if($invresult1['id']>0)
        {
        	$weekyear = $invresult1['weekyear'];
		    $weekno1 = $invresult1['week_no'];
		    $week_array1 = getStartAndEndDate($weekno1,$weekyear);

		    if($week_array1['week_end']==$invresult1['to_date'])
		    {
		    	$weekno1++;	
		    	$week_array = getStartAndEndDate($weekno1,$weekyear);
		    	$fromDate = $week_array['week_start'];
		    	$today = $week_array['week_end'];

		    }
		    else
		    {
		    	$datetime = new DateTime($invresult1['to_date']);
				$fromDate = $datetime->modify('+1 day')->format('Y-m-d');
				$today = $week_array1['week_end'];	
		    }
			
        	$j=0;
        	while($weekno1<=$currentweek)
        	{
        		if($weekno1==$currentweek)
        		{
        			$yesterday = new DateTime('yesterday');
					$today = $yesterday->format('Y-m-d');
        		}
        	    if(strtotime($today)<strtotime($fromDate))
        	    {
        	    	break;
        	    }
				$valus[$j]['cid'] = $result['id'];
				$valus[$j]['depot_id'] = $result['depot'];
			    $valus[$j]['invoice_no'] = '#'.generate_Uniqid();
			    $valus[$j]['week_no'] = $weekno1;
			    $valus[$j]['from_date'] = $fromDate;
			    $valus[$j]['to_date'] = $today;
			    $valus[$j]['weekyear'] = date('Y');
			    $valus[$j]['status_id'] = 1;
			    $valus[$j]['vat'] = $vat;
			    $valus[$j]['duedate'] = $duedate;
			    $valus[$j]['istype'] = 1;
			    $valus[$j]['insert_date'] = date('Y-m-d H:i:s');
			    
			    $week_array = getStartAndEndDate($weekno1,$weekyear);
			    $fromDate = $week_array['week_start'];
			    $today = $week_array['week_end'];

			    $weekno1++;
			    $j++;  
        	}
        	
        }
        else
        {
        	// contractor_timeshhet check
        	$cntquery = "SELECT week(`date`) AS week_no, year(`date`) As dateyear FROM `tbl_contractortimesheet` WHERE `cid`=".$result['id']." ORDER BY `date` ASC LIMIT 1";
	        $cntrow =  $mysql -> selectFreeRun($cntquery);
	        if($cntresult = mysqli_fetch_array($cntrow))
	        {
	        	$weekyear = $cntresult['dateyear'];
		        $weekno1 = $cntresult['week_no'];
	        	$j=0;
	        	while($weekno1<=$currentweek)
	        	{
	        		$week_array = getStartAndEndDate($weekno1,$weekyear);
	        		if($weekno1==$currentweek)
	        		{
	        			$yesterday = new DateTime('yesterday');
						$today = $yesterday->format('Y-m-d');
	        		}
	        		else
	        		{
	        			$today = $week_array['week_end'];
	        		}
	        		

	        		$querychk = "SELECT `to_date` FROM `tbl_contractorinvoice` WHERE `cid`=".$result['id']." AND `week_no`=".$weekno1." ORDER BY `to_date` DECS LIMIT 1";
	        		$cntrow1 =  $mysql -> selectFreeRun($querychk);
	        		if($cntresult1 = mysqli_fetch_array($cntrow1))
	        		{
	        			$datetime = new DateTime($cntresult1['to_date']);
					    $fromDate = $datetime->modify('+1 day');
	        		}
	        		else
	        		{
	        			$fromDate=$week_array['week_start'];

	        		}

					$valus[$j]['cid'] = $result['id'];
					$valus[$j]['depot_id'] = $result['depot'];
				    $valus[$j]['invoice_no'] = '#'.generate_Uniqid();
				    $valus[$j]['week_no'] = $weekno1;
				    $valus[$j]['from_date'] = $fromDate;
				    $valus[$j]['to_date'] = $today;
				    $valus[$j]['weekyear'] = date('Y');
				    $valus[$j]['status_id'] = 1;
				    $valus[$j]['vat'] = $vat;
				    $valus[$j]['duedate'] = $duedate;
				    $valus[$j]['istype'] = 1;
				    $valus[$j]['insert_date'] = date('Y-m-d H:i:s');

				    $weekno1++;
				    $j++;
	        	}   
	        }                                                                                                                               
        }
        $insert = $mysql -> insert('tbl_contractorinvoice',$valus);
        if($insert)
        {
        	echo "Inserted successfully...";
        }
	}
}
$mysql->dbDisconnect();
?>