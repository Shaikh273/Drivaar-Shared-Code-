<?php
include 'DB/config.php';
$mysql = new Mysql();
$mysql -> dbConnect();

 $url = "http://battuta.medunes.net/api/country/all/?key=f2a7fbb4d99d09d17fc8df7431d248e0";
	    $ch = curl_init();
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    $res = curl_exec($ch);
	    curl_close($ch);
	    $dcd = json_decode($res);
	    foreach ($dcd as $k) 
	    {
	    	//$date = date('Y-m-d H:i:s');
	    	$inst[] = "('".$k->name."','".$k->code."')";
	    }
	    $sql1 = "INSERT INTO `tbl_country`(`name`, `code`) VALUES ".implode(",",$inst);
            echo $sql1;
$mysql -> dbDisConnect();

?>        

       