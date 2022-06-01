<?php
include 'DB/config.php';
	// $keyword = strval($_POST['query']);
	// $search_param = "{$keyword}%";
	$uid = $_POST['userid'];
	if($userid==1)
    {
      $uid='%';
    }
    $mysql = new Mysql();
    $mysql->dbConnect();
    $statusquery =  "SELECT DISTINCT c.*,d.`name` as dname
            FROM `tbl_contractor` c 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL INNER JOIN `tbl_depot` d ON d.`id`=c.`depot`
            WHERE  c.`isdelete`=0 AND c.`iscomplated`=1 AND c.`depot` IN (w.depot_id)";
            // echo $statusquery;
    $result =  $mysql->selectFreeRun($statusquery);
	while($row = mysqli_fetch_array($result)) {
        if ($row['isactive'] == 0) {
            $statusname = '<span class="label label-success">Active</span>';
        } else if ($row['isactive'] == 1) {
            $statusname = '<span class="label label-danger">Inactive</span>';
        } else if ($row['isactive'] == 2) {
            $statusname = '<span class="label label-info">Onboarding</span>';
        }
		$countryResult[] = '<a href="#" onclick="Contractormove('.$row["id"].')"  class="txt-wrap"><strong>'.$row["name"].'</strong> '.$row["dname"].' '.$statusname.'</a>';
	}

    $mysql->dbDisconnect();
	echo json_encode($countryResult);
?>