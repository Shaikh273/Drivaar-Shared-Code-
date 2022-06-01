<?php
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
// unset($_SESSION['uid']);
// unset($_SESSION['load']);
// session_unset();
// session_destroy();
// header("location: login.php");


//ORIGINAL
    // session_start();
    // session_unset($_SESSION['cid']);
    // // session_unset($_SESSION['userid']);
    // // session_unset($_SESSION['adt']);
    // session_destroy(); 
    // header("Location:login.php");


    // ADDED FOR TESTING 
if (!isset($_SESSION)) 
{ 
    session_start(); 
}
session_unset();
//  session_unset($_SESSION['adt']);
//  session_unset($_SESSION['load']);
header("Location: login.php"); // Or wherever you want to redirect
?>