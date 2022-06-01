
<?php
    // session_start();
    // session_unset($_SESSION['permissioncode']);
    // session_unset($_SESSION['userid']);
    // session_unset($_SESSION['adt']);
    // session_destroy(); 
    // header("Location:login.php");
?>
<?
 if (!isset($_SESSION)) 
 { session_start(); }
 $_SESSION = array(); 
 session_destroy(); 
 header("Location: login.php"); // Or wherever you want to redirect
 
?>
