<?php
define('host','localhost');
define('username','pibypyvy_gifted30db');
define('password','aka_gifted30@2018');
define('database','pibypyvy_gifted30');

$connection = mysqli_connect(host,username,password) or die("Cannot connect to the server");
$connect_db = mysqli_select_db($connection,database)or die("Cannot connect to database");

?>