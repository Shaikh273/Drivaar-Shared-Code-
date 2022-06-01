<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';
$mysql = new Mysql();
$mysql -> dbConnect();

$id = $_POST['id'];
$valus[0]['name'] = $_POST["name"];
$valus[0]['contact'] = $_POST['phone'];
$valus[0]['update_date'] = date('Y-m-d H:i:s');

$uploadDir = 'uploads/Userprofile/'; 
$uploadedFile = ''; 
$fileName = basename($_FILES["file"]["name"]); 


if($_POST['id']>0)
{
    if(!empty($fileName))
    { 
        $valus[0]['file'] = $_POST['file'];
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
                    $valus[0]['file'] = $uploadedFile;
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
            $message = 'Sorry, Only  JPG, JPEG, & PNG files are allowed to upload.'; 
        } 
    }

    if(!empty($fileName))
    {
        $makecol = array('name','contact','file','update_date');
    }
    else
    {
        $makecol = array('name','contact','update_date');
    }
    
    $whr = 'id ='.$id;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$whr);

    if($statuupdate)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been updated successfully.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been updated.';
    }
    $name = 'Update';  
}
else
{
    $status=0;
    $title = 'Update Error';
    $message = 'Data can not been updated.';
}
$mysql -> dbDisConnect();

$response['status'] = $status;
$response['title'] = $title;
$response['message'] = $message;
$response['name'] = $fileName;
echo json_encode($response);
