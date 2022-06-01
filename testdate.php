<?php
	date_default_timezone_set("America/New_York");
    $dto =  new DateTime();
	$dto->setISODate(2021, 30,0);
	echo $dto->format('Y-m-d').'<br>';
	//echo $ret['week_start'];
	$dto->modify('+6 days');
	echo $dto->format('Y-m-d');
	

?>