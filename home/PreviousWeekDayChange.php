
<?php
include 'DB/config.php';
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}

if(isset($_POST['action']) && $_POST['action'] == 'ShowPreviousWeekDayOnchange')
{
    $userid=$_POST['userid'];
    $cid = $_POST['cid'];

    $prevEnd = date('Y-m-d', strtotime($_POST['startdate']." 1 days ago"));
    $preStart = date('Y-m-d', strtotime($_POST['startdate']." 7 days ago"));
    $nextEnd = date('Y-m-d', strtotime($_POST['startdate']." +1 day"));
    $nextStart = date('Y-m-d', strtotime($_POST['startdate']." +7 day"));

    $mysql = new Mysql();
    $conn = $mysql -> dbConnect();
    $preExt = array();
    $preInt = array();
    mysqli_query($conn,"set @i = 1");

    $vehiclestatus1 =  mysqli_query($conn,"SELECT DATE(ADDDATE('$prevEnd', INTERVAL @i:=@i-1 DAY)) AS date123 FROM `tbl_contractortimesheet` HAVING ABS(@i) < DATEDIFF('$prevEnd', '$preStart')");
    while($row = mysqli_fetch_array($vehiclestatus1))
    {
        $preExt[] = $row['date123'];
    }
    $vehiclestatus2 =  mysqli_query($conn,"SELECT `date` from `tbl_contractortimesheet` WHERE `date` between '$preStart' AND '$prevEnd' AND `status_id`=1 AND `cid`=".$cid." ORDER BY `date` DESC");
    while($statusresult1 = mysqli_fetch_array($vehiclestatus2) )
    {
        $preInt[] = $statusresult1['date'];
    }
    $j=0;
    while($j<7)
    {
        if($preExt[$j] != $preInt[$j])
        {
            break;
        }
        $j++;
    }
    $finStart = $preExt[$j];


    $nextExt = array();
    $nextInt = array();
    mysqli_query($conn,"set @i = -1");
    $vehiclestatus1 =  mysqli_query($conn,"SELECT DATE(ADDDATE('$nextEnd', INTERVAL @i:=@i+1 DAY)) AS date123 FROM `tbl_contractortimesheet` HAVING @i < DATEDIFF('$nextStart', '$nextEnd')");
    while($row = mysqli_fetch_array($vehiclestatus1))
    {
        $nextExt[] = $row['date123'];
    }
    $vehiclestatus2 =  mysqli_query($conn,"SELECT `date` from `tbl_contractortimesheet` WHERE `date` between '$nextEnd' AND '$nextStart' AND `status_id`=1 AND `cid`=$cid ORDER BY `date` asc");
    while($statusresult1 = mysqli_fetch_array($vehiclestatus2) )
    {
        $nextInt[] = $statusresult1['date'];
    }
    $j=0;
    while($j<7)
    {
        if($nextExt[$j] != $nextInt[$j])
        { 
            break;
        }
        $j++;
    }
    $finEnd = $nextExt[$j];


    $date1=date_create($finStart);
    $date2=date_create($finEnd);
    $diff=date_diff($date1,$date2);
    $mysql -> dbDisConnect();
    echo $days = $diff->format("%a")-1;
}
?>
