<?php 
session_start(); 
include "db_conn.php";
$cid = base64_decode($_GET['cid']);
// if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['dob'])) {

// 	function validate($data){
//        $data = trim($data);
// 	   $data = stripslashes($data);
// 	   $data = htmlspecialchars($data);
// 	   return $data;
// 	}

// 	$name = validate($_POST['name']);
// 	$email = validate($_POST['email']);
//     $dob = validate($_POST['dob']);
    
    
// 	$user_data = 'name='. $name. '&name='. $name;
// 	if (empty($name)) {
// 		header("Location: index.php?error=User Name is required&$user_data");
// 	    exit();
// 	}
// 	else
// 	{
// 		// hashing the password
// 	    $sql = "SELECT * FROM tbl_contractor WHERE id=".$cid;
// 		$result = mysqli_query($conn, $sql);
// 		if (mysqli_num_rows($result) > 0) {
// 			header("Location: index.php?error=The username is taken try another&$user_data");
// 	        exit();
// 		}else {
//            $sql2 = "INSERT INTO tbl_contractor(name,email,dob) VALUES('$name','$email','$dob')";
//            $result2 = mysqli_query($conn, $sql2);
//            if ($result2) {
//            	 header("Location: index.php?success=Your data send successfully");
// 	         exit();
//            }else {
// 	           	header("Location: index.php?error=unknown error occurred&$user_data");
// 		        exit();
//            }
// 		}
// 	}
// }
// else
// {
// 	header("Location: index.php");
// 	exit();
// }