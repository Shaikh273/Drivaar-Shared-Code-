<?php
include '../DB/config.php';

if (!isset($_SESSION)) {
    session_start();
}

if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode']['144'] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {

    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $userid = '%';
    } else {
        $userid = $_SESSION['userid'];
    }
} else {
    header("location: ../login.php");
}
if (isset($_POST['platinum'])) {
    // header("Location: ../loginpage/login.php");
    $id = $_SESSION['userid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query = "SELECT * FROM `tbl_user` WHERE `id`=" . $id;
    $row =  $mysql->selectFreeRun($query);
    $cntresult = mysqli_fetch_array($row);
    $mysql->dbDisConnect();
    $name = $cntresult['name'];
    $contact = $cntresult['contact'];
    $email = $cntresult['email'];
    $basic = "insert into `subscription` (`full_name`, `email`,`mobile_no`,`plan`) values ('$name','$email','$contact','Platinum')";
    $result = mysqli_query($mysql->dbConnect(),$basic);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome to platinum Page</h1>
    <form action="#" method="POST">
        <button class="c19" type="submit" name="platinum">Choose</button>
    </form>

</body>
</html>