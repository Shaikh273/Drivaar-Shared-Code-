<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();

$uploadDir = 'uploads/conditionalreport/extraimage/'; 
$uploadedFile = ''; 
$uniuqid = $_POST['uniqueid'];
$fileName = $uniuqid.basename($_FILES["file"]["name"]);

$request = 1;
if(isset($_POST['request'])){ 
  $request = $_POST['request'];
}
if($request == 2)
{ 
  $filename = $uploadDir.$_POST['name'];  
  //unlink($filename); 
  exit;
}
else
{
    if(!empty($fileName))
    { 
        $targetFilePath = $uploadDir.$fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
        $allowTypes = array('jpg','png','jpeg','PNG','JPG','JPEG'); 
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
}

$response['status'] = $status;
$response['name'] = $fileName;
echo json_encode($response);
