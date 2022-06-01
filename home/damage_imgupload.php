<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();
$uploadDir = 'uploads/vehiclesdamageimages/'; 
$uploadedFile = ''; 
$fileName = basename($_FILES["file"]["name"]); 

if(!empty($fileName))
{ 
    $targetFilePath = $uploadDir.$fileName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
    
    $allowTypes = array('pdf','doc','docx','jpg','png','jpeg','JPG','PNG','JPEG'); 
    if(in_array($fileType, $allowTypes))
    { 
        
        if($_FILES['file']['size']<7000000)
        {
            ini_set("upload_max_filesize","80M");
            ini_set("post_max_size","100M");
            ini_set('max_execution_time', 1200); 
            ini_set('memory_limit', '1024M' );
    		ini_set('max_input_time', 1200);  
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
            { 
                $uploadedFile = $fileName; 
                $valus[0]['image'] = $uploadedFile;
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
$response['name'] = $fileName;
echo json_encode($response);
