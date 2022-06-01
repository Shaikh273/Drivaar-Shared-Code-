 <?php

$servername = "localhost";
$username = 	"root";
$password = "";//BRHL@Drivar!564
$dbname = "drivaar_db";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

?>