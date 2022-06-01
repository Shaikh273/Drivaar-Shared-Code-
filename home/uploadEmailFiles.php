<?php
$target = "/emailUploads/";
$tr = array();
$countfiles = count($_FILES['myfiles']['name']);
for($i=0;$i<$countfiles;$i++)
	{
		$dt = strtotime(date('Y-m-d'));
		$filename = $dt.$_FILES['myfiles']['name'][$i];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^A-Za-z0-9\-]/', '', $filename).".".$ext;

		if(move_uploaded_file($_FILES['myfiles']['tmp_name'][$i],'emailUploads/'.$filename)) {
    		$tr[] = "<tr><td>http://drivaar.com/home/emailUploads/".$filename."</td></tr>";
	    }else
	    {
	    	$tr[] = "<tr><td>".$_FILES['myfiles']['error'][$i]."</td></tr>";
	    }
	}



// foreach ($_FILES['myfiles']['name'] as $filename) 
// {
// 	$dt = strtotime(date('Y-m-d'));
//     $temp=$target;
//     $tmp=$_FILES['myfiles']['tmp_name'][$count];
//     $temp=$temp.$dt.basename($filename);
//     if(move_uploaded_file($tmp,$temp)) {
//     	$tr[] = "<tr><td>http://drivaar.com".$temp."</td></tr>";
//     }else
//     {
//     	$tr[] = "<tr><td>".$_FILES['myfiles']['error'][$count]."</td></tr>";
//     }
//     $count=$count + 1;
//     $temp='';
//     $tmp='';
// }
$tr1 = implode(" ",$tr);
echo $tr1;
?>