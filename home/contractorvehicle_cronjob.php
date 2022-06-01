<?php
include 'DB/config.php';

$cid = $_GET['id'];
$currentDate = date('Y-m-d');

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

$currentweek = weekOfYear($currentDate);

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
	$query = "SELECT * FROM `tbl_vehicles` WHERE `id`='$cid' AND `isdelete`=0";
}
else
{
	$query = "SELECT * FROM `tbl_vehicles` WHERE `isdelete`=0";
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

	if($result['depot_id']>0)
	{

		$invoiceqry1 = "SELECT * FROM `tbl_contractorinvoice` WHERE `cid`=".$result['id']." AND `isdelete`=0 AND `isactive`=0 AND `istype`=3 ORDER BY `to_date` DESC LIMIT 1";
        $invoicerow1 =  $mysql -> selectFreeRun($invoiceqry1);
        if($invresult1 = mysqli_fetch_array($invoicerow1))
        {
        	$todate =  $invresult1['end_date'];

		$cntquery = "SELECT *, week(`pickup_date`) AS week_no, year(`pickup_date`) As dateyear FROM `tbl_vehiclerental_agreement` WHERE `vehicle_id`=".$result['id']." AND `isdelete`=0 AND `isactive`=0 AND (`pickup_date`>'$todate' OR (`pickup_date`<'$todate' AND `return_date`>'$todate')) ORDER BY `pickup_date` ASC";
	        $cntrow =  $mysql -> selectFreeRun($cntquery);	
	        $n=0;
	        while($cntresult = mysqli_fetch_array($cntrow))
	        {
	        	$conqry = "SELECT * FROM `tbl_contractor` WHERE `id`=".$cntresult['driver_id'];
				$conrow =  $mysql -> selectFreeRun($conqry);
				$conresult = mysqli_fetch_array($conrow);

				$duedate= date("Y/m/d", strtotime("$currentDate +".$conresult['arrears']." week"));

	        	$weekyear = $cntresult['dateyear'];
		        if($n==0)
		        {
		        	$weekno1 = $cntresult['week_no'];
		        	$n++;
		        }
		        if($n==2)
		        {
		        	$n=1;
		        	break;
		        }
	        	$j=0;
	        	$fromDate=0;
	        	$flg=0;
	        	while($weekno1<=$currentweek)
	        	{
	        		$week_array = getStartAndEndDate($weekno1,$weekyear);

	        		if($fromDate==0)
	        		{
	        			$fromDate=$cntresult['pickup_date'];
	        		}
	        		else
	        		{
	        			$fromDate= $week_array['week_start'];
	        		}
	        		if(strtotime($cntresult['return_date'])<=strtotime($week_array['week_end']))  //($weekno1==$currentweek)
	        		{
	        			if(strtotime($currentDate)<=strtotime($cntresult['return_date']))
	        			{
	        				$yesterday = new DateTime('yesterday');
							$today = $yesterday->format('Y-m-d');
	        			}
	        			else
	        			{
	        				$today=$cntresult['return_date'];
	        			}
	        			$flg=1;	 
	        		}
	        		else
	        		{
	        			if(strtotime($currentDate)<=strtotime($week_array['week_end']))
	        			{
	        				$yesterday = new DateTime('yesterday');
							$today = $yesterday->format('Y-m-d');
	        				$flg=1;	 
	        			}
	        			else
	        			{
	        				$today = $week_array['week_end'];
	        			}
	        			
	        		}
	        		
	        	
	        		if(strtotime($fromDate)<=strtotime($today))
	        		{
	        		}
	        		else
	        		{
	        			$n=2;
	        			break;
	        		}


	        		$date = new DateTime($fromDate);
					$week = $date->format("W");
	        		$weekno1 = $week;

					$valus[$j]['cid'] = $result['id'];
					$valus[$j]['depot_id'] = $result['depot_id'];
				    $valus[$j]['invoice_no'] = '#'.generate_Uniqid();
				    $valus[$j]['week_no'] = $weekno1;
				    $valus[$j]['from_date'] = $fromDate;
				    $valus[$j]['to_date'] = $today;
				    $valus[$j]['weekyear'] = date('Y');
				    $valus[$j]['status_id'] = 1;
				    $valus[$j]['vat'] = $vat;
				    $valus[$j]['duedate'] = $duedate;
				    $valus[$j]['istype'] = 3;
				    $valus[$j]['insert_date'] = date('Y-m-d H:i:s');

				    if(strtotime($week_array['week_end'])<=strtotime($today))
				    {
				    	$weekno1++;
				    }
				    $j++;
				    if($j>50)
				    {
				    	break;
				    }
				    // $insert = $mysql -> insert('tbl_contractorinvoice',$valus);

				    if($flg==1)
				    {
				    	break;
				    }
	        	}  
	        }       
	    //     while($cntresult = mysqli_fetch_array($cntrow))
	    //     {
	    //     	$weekyear = $cntresult['dateyear'];
	    //     	$weekno1 = $cntresult['week_no'];
	    //     	if(strtotime($cntresult['start_date'])<strtotime($todate))
	    //     	{
	    //     		//todate ka weekno 
	    //     		$date = new DateTime($todate);
					// $week = $date->format("W");
	    //     		$weekno1 = $week;
	    //     	}
	        	
	    //     	$j=0;
	    //     	$fromDate=0;
	    //     	$flg=0;
	    //     	while($weekno1<=$currentweek)
	    //     	{
	    //     		$week_array = getStartAndEndDate($weekno1,$weekyear);
	    //     		if($fromDate==0)
	    //     		{
	    //     			$fromDate=$cntresult['start_date'];
	    //     			if(strtotime($cntresult['start_date'])<strtotime($todate))
			  //       	{
			  //       		//todate ka weekno 
			  //       		$fromDate=$today->modify('+1 day');
			  //       	}
	    //     		}
	    //     		else
	    //     		{
	    //     			$fromDate= $week_array['week_start'];
	    //     		}
	    //     		if(strtotime($cntresult['end_date'])<=strtotime($week_array['week_end']))  //($weekno1==$currentweek)
	    //     		{
	    //     			if(strtotime($currentDate)<=strtotime($cntresult['end_date']))
	    //     			{
	    //     				$yesterday = new DateTime('yesterday');
					// 		$today = $yesterday->format('Y-m-d');
	    //     			}
	    //     			else
	    //     			{
	    //     				$today=$cntresult['end_date'];
	    //     			}
	    //     			$flg=1;	 
	    //     		}
	    //     		else
	    //     		{
	    //     			if(strtotime($currentDate)<strtotime($week_array['week_end']))
	    //     			{
	    //     				$yesterday = new DateTime('yesterday');
					// 		$today = $yesterday->format('Y-m-d');
	    //     				$flg=1;	 
	    //     			}
	    //     			else
	    //     			{
	    //     				$today = $week_array['week_end'];
	    //     			}
	        			
	    //     		}
	    //     		if(strtotime($fromDate)<=strtotime($today))
	    //     		{}
	    //     		else
	    //     		{
	    //     			break;
	    //     		}

					// $valus[$j]['cid'] = $result['id'];
					// $valus[$j]['depot_id'] = $result['depot_id'];
				 //    $valus[$j]['invoice_no'] = '#'.generate_Uniqid();
				 //    $valus[$j]['week_no'] = $weekno1;
				 //    $valus[$j]['from_date'] = $fromDate;
				 //    $valus[$j]['to_date'] = $today;
				 //    $valus[$j]['weekyear'] = date('Y');
				 //    $valus[$j]['status_id'] = 1;
				 //    $valus[$j]['vat'] = $vat;
				 //    $valus[$j]['duedate'] = $duedate;
				 //    $valus[$j]['istype'] = 3;
				 //    $valus[$j]['insert_date'] = date('Y-m-d H:i:s');

				 //    $weekno1++;
				 //    $j++;

				 //    $insert = $mysql -> insert('tbl_contractorinvoice',$valus);

				 //    if($flg==1)
				 //    {
				 //    	break;
				 //    }
	    //     	}   
	    //     }
        }
        else
        {
        	$cntquery = "SELECT *, week(`pickup_date`) AS week_no, year(`pickup_date`) As dateyear FROM `tbl_vehiclerental_agreement` WHERE `vehicle_id`=".$result['id']." AND `isdelete`=0 AND `isactive`=0 ORDER BY `pickup_date` ASC";
	        $cntrow =  $mysql -> selectFreeRun($cntquery);	      
	        $n=0;
	        while($cntresult = mysqli_fetch_array($cntrow))
	        {
	        	$weekyear = $cntresult['dateyear'];
		        if($n==0)
		        {
		        	$weekno1 = $cntresult['week_no'];
		        	$n++;
		        }
		        if($n==2)
		        {
		        	$n=1;
		        	break;
		        }
	        	$j=0;
	        	$fromDate=0;
	        	$flg=0;
	        	while($weekno1<=$currentweek)
	        	{
	        		$week_array = getStartAndEndDate($weekno1,$weekyear);

	        		if($fromDate==0)
	        		{
	        			$fromDate=$cntresult['pickup_date'];
	        		}
	        		else
	        		{
	        			$fromDate= $week_array['week_start'];
	        		}
	        		if(strtotime($cntresult['return_date'])<=strtotime($week_array['week_end']))  //($weekno1==$currentweek)
	        		{
	        			if(strtotime($currentDate)<=strtotime($cntresult['return_date']))
	        			{
	        				$yesterday = new DateTime('yesterday');
							$today = $yesterday->format('Y-m-d');
	        			}
	        			else
	        			{
	        				$today=$cntresult['return_date'];
	        			}
	        			$flg=1;	 
	        		}
	        		else
	        		{
	        			if(strtotime($currentDate)<=strtotime($week_array['week_end']))
	        			{
	        				$yesterday = new DateTime('yesterday');
							$today = $yesterday->format('Y-m-d');
	        				$flg=1;	 
	        			}
	        			else
	        			{
	        				$today = $week_array['week_end'];
	        			}
	        			
	        		}
	        		
	        	
	        		if(strtotime($fromDate)<=strtotime($today))
	        		{
	        		}
	        		else
	        		{
	        			$n=2;
	        			break;
	        		}


	        		$date = new DateTime($fromDate);
					$week = $date->format("W");
	        		$weekno1 = $week;

	        		$conqry = "SELECT * FROM `tbl_contractor` WHERE `id`=".$cntresult['driver_id'];
					$conrow =  $mysql -> selectFreeRun($conqry);
					$conresult = mysqli_fetch_array($conrow);

					$duedate= date("Y/m/d", strtotime("$currentDate +".$conresult['arrears']." week"));

					$valus[$j]['cid'] = $result['id'];
					$valus[$j]['depot_id'] = $result['depot_id'];
				    $valus[$j]['invoice_no'] = '#'.generate_Uniqid();
				    $valus[$j]['week_no'] = $weekno1;
				    $valus[$j]['from_date'] = $fromDate;
				    $valus[$j]['to_date'] = $today;
				    $valus[$j]['weekyear'] = date('Y');
				    $valus[$j]['status_id'] = 1;
				    $valus[$j]['vat'] = $vat;
				    $valus[$j]['duedate'] = $duedate;
				    $valus[$j]['istype'] = 3;
				    $valus[$j]['insert_date'] = date('Y-m-d H:i:s');

				    if(strtotime($week_array['week_end'])<=strtotime($today))
				    {
				    	$weekno1++;
				    }
				    $j++;
				    if($j>50)
				    {
				    	break;
				    }
				    $insert = $mysql -> insert('tbl_contractorinvoice',$valus);

				    if($flg==1)
				    {
				    	break;
				    }
	        	}  
	        }                                                                                                                               
        }
        if($insert)
        {
        	echo "Inserted successfully...";
        }
	}
}
$mysql->dbDisconnect();
?>