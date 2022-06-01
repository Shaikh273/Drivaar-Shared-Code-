<?php
date_default_timezone_set('Europe/London');
if(isset($_POST['action']) && $_POST['action'] == 'GetDateFunction')
{
	//echo 'date';
	date_default_timezone_set('Europe/London');
	$dt = date('D M d Y H:i:s');
	//echo date('D M d Y H:i:s')." GMT-0400 (Time in New York)";
	echo $dt;
}
?>