<?php
include 'DB/config.php';
	$mysql = new Mysql();
	$mysql -> dbConnect();

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

	$currentdate = date('Y-m-d');
	$query = "SELECT * FROM `tbl_workforcelend` WHERE `isactive`=0 AND `isdelete`=0 AND `is_completed`=0";
	$row =  $mysql -> selectFreeRun($query);
	$rowcount=mysqli_num_rows($row);
	$flag=0;
	while($result = mysqli_fetch_array($row))
	{

		$no_of_installment = $result['no_of_instal'];
		$singleamount = ($result['amount']/$no_of_installment);
		$week = (int)date('W', strtotime($result['week_of_payment']));
		$yesterday = new DateTime('yesterday');
		$weekyear = date('Y', strtotime($yesterday->format('Y-m-d')));
		$yesterdayweek = (int)date('W', strtotime($yesterday->format('Y-m-d')));

		$data = array();
		for($i=0;$i<$no_of_installment;$i++)
		{

	        $data[] = $week + $result['time_interval'];
	        $week +=  $result['time_interval'];
		}

		$paymentquery = "SELECT SUM(`amount`) AS paidamount FROM `tbl_workforcepayment` WHERE `loan_id`=".$result['id']." AND `isdelete`=0 AND `wid`=".$result['wid'];
		$paymentrow =  $mysql -> selectFreeRun($paymentquery);
		$pyresult = mysqli_fetch_array($paymentrow);
		
		$remianamount = ($result['amount']-$pyresult['paidamount']);
		if($remianamount>=$singleamount)
		{	
			if($remianamount==$singleamount)
			{
				$flag=1;
			}
			$payamount = $singleamount;
		}
		else
		{
			$payamount = $remianamount;
			$flag=1;
		}	


		if(($payamount>0) && (max($data)<$yesterdayweek))
		{
			$maxvalue = max($data);
			while($maxvalue <= $yesterdayweek)
			{
				$data[] = $maxvalue+$result['time_interval'];
				$maxvalue+=$result['time_interval'];
			}
		}

		
		if (in_array($yesterdayweek, $data))
		{
			if($payamount>0)
			{
				$week_array = getStartAndEndDate($yesterdayweek,$weekyear);

				$valus[0]['wid'] = $result['wid'];
				$valus[0]['loan_id'] = $result['id'];
			    $valus[0]['week_no'] = $yesterdayweek;
			    $valus[0]['amount'] = $payamount;
			    $valus[0]['date'] = 'Week '.$yesterdayweek.' ('.$week_array['week_start'].' - '.$week_array['week_end'].')';
			    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
			    
			    $statusinsert = $mysql -> insert('tbl_workforcepayment',$valus);
			    if($statusinsert)
			    {
			        $message = 'Data has been inserted successfully.';
			    }
			    else
			    {
			        $message = 'Data can not been inserted successfully.';
			    }
			}
			
			echo $message;	
		}
		if($flag==1 || $payamount==0)
		{
			$valus1[0]['is_completed'] = 1;
			$valus1[0]['update_date'] = date('Y-m-d H:i:s');
	        $makecol = array('is_completed','update_date');
	        $where = 'id ='. $result['id'];
	        $statuupdate = $mysql -> update('tbl_workforcelend',$valus1,$makecol,'update',$where);
			//loan close is_complete is 1.
		}
	}

	$mysql -> dbDisConnect();
?>