<?php

// Include the database configuration file 
include_once 'config.php';

$mysql = new Mysql();
$mysql->dbConnect();

// Get current page URL 
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

$user_current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];

// Get server related info 
//  $user_ip_address = $_SERVER['REMOTE_ADDR']; il get the IP address of the user.
$user_ip_address = $mysql->getIpAddr();
$referrer_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
$user_agent = $_SERVER['HTTP_USER_AGENT'];

// Insert visitor activity log into database 
$sql ="insert into visitor_activity_logs(user_ip_address, user_agent, page_url, referrer_url) values   ('$user_ip_address','$user_agent','$user_current_url','$user_agent')";
$insert = mysqli_query($mysql->dbConnect(),$sql);

?>
<!-- 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>How to Store Visitor Activity Log in the MySql Database Using PHP</title>
</head>
<body>
    <div class="container">
        <div class="cw-info-box">
            <p><strong>Visitor Activity Log</strong></p>
            <div class="log-data">
                <p><b>Ip Address</b> : <?php //echo $user_ip_address; ?></p>
                <p><b>User Agent</b> : <?php //echo $user_agent; ?></p>
                <p><b>Current page url</b> : <?php //echo $user_current_url; ?></p>
                <p><b>Referrer url</b> : <?php // echo $referrer_url; ?></p>
            </div>
        </div>
    </div>
</body>
</html> -->