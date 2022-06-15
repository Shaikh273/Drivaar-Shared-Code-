

<?php
include 'DB/config.php';
if (!isset($_SESSION)) {
    session_start();
}

$mysql = new Mysql();
$mysql->dbConnect();

session_unset();
// $_SESSION['IS_LOGOUT'] = 'YES';

// mysqli_query($mysql->dbConnect(), "delete from visitor_activity_logs where user_ip_address='$user_ip_address'");
//  session_unset($_SESSION['adt']);
//  session_unset($_SESSION['load']);
header("Location:  startpage/Drivaar.php"); // Or wherever you want to redirect
?>
