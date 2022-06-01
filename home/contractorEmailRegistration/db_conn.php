<?php

$sname= "localhost";
$unmae= "root";
$password = "BRHL@Drivar!564";
$db_name = "drivaar_db";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}