<?php
include 'DB/config.php';
$mysql = new Mysql();
$mysql->dbConnect();
$permissionrow =  $mysql->selectFreeRun("SELECT `id`,`cid`,`userid`,`uniqid`,`rota_uniqid` FROM `tbl_contractortimesheet` WHERE 1");
$whenCase = "";
$win = array();
$win2 = array();
while($pr_result = mysqli_fetch_array($permissionrow))
{
	$exp = explode("_",$pr_result['rota_uniqid']);
	$exp[1] = "0";
	$impl = implode("_",$exp);
	if((!empty($pr_result['rota_uniqid']) || $pr_result['rota_uniqid']!=NULL || $pr_result['rota_uniqid']!="") && !in_array($impl, $win2))
	{
		$whenCase .= " WHEN `rota_uniqid` = '".$pr_result['rota_uniqid']."' THEN '$impl'";
		$win2[] = $impl;
		$win[] = $pr_result['rota_uniqid'];
	}
}
$arWrid = "('".implode("','",$win)."')";
echo "UPDATE `tbl_contractortimesheet` SET `rota_uniqid` = CASE
    $whenCase
    ELSE `rota_uniqid`
    END
WHERE `rota_uniqid`  in $arWrid";


// $user =  $mysql->update('tbl_user', $valus, $usercol, 'delete', $where);
$mysql->dbDisConnect();
?>