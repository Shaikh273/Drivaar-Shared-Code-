<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();

$uploadDir = 'uploads/communication/'; 
$uploadedFile = ''; 
$uniuqid = uniqid();
$fileName = $uniuqid . basename($_FILES["file"]["name"]); 

if(!empty($fileName))
{ 
    $targetFilePath = $uploadDir . $fileName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
    
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
    if(in_array($fileType, $allowTypes))
    { 
        
        if($_FILES['file']['size']<6000000)
        {
           
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
            { 
                $uploadedFile = $fileName; 
                //$valus[0]['image'] = $uploadedFile;
                $status = 1; 
            }
            else
            { 
                $status = 0; 
                $title = 'Error';
                $message = 'Sorry, There was an error uploading your file.'; 
            } 
        }
        else
        {
            $status = 0; 
            $title = 'Error';
            $message = 'Sorry, Please Upload maximum file size must be 5Mb.';
        }
        
    }
    else
    { 
        $status = 0; 
        $title = 'Error';
        $message = 'Sorry, Only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
    } 
}


$response['status'] = $status;
$response['name'] = $uploadedFile;



echo json_encode($response);
