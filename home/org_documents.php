<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();

$valus[0]['name'] = $_POST['name'];

$uploadDir = 'uploads/organizationdocuments/'; 
$uploadedFile = ''; 
$uploadDir1 = 'uploads/organizationdocuments/'; 
$uploadedFile1 = '';

$fileName = basename($_FILES["file"]["name"]); 
$fileName1 = basename($_FILES["file1"]["name"]);

if(!empty($fileName))
{ 
    $targetFilePath = $uploadDir.$fileName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
    $allowTypes = array('pdf','doc','docx','jpg','png','jpeg','PNG','JPG','JPEG'); 
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
        $message = 'Sorry, Only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
    } 
}

if(!empty($fileName1))
{ 
    $targetFilePath1 = $uploadDir1.$fileName1; 
    $fileType1 = pathinfo($targetFilePath1, PATHINFO_EXTENSION); 
    $allowTypes1 = array('pdf','doc','docx','jpg','png','jpeg','PNG','JPG','JPEG'); 
    if(in_array($fileType1, $allowTypes1))
    { 
        if($_FILES['file1']['size']<6000000)
        {
            if(move_uploaded_file($_FILES["file1"]["tmp_name"], $targetFilePath1))
            { 
                $uploadedFile1 = $fileName1; 
                $valus[0]['file1'] = $uploadedFile1;
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



if($status == 1)
{
    if($_POST['id']>0)
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $value[0]['file'] = $_POST['file'];
        $value[0]['file1'] = $_POST['file1'];

        $makecol = array('name','file','file1','update_date');
        $where = 'id = 1';
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $statuupdate = $mysql -> update('tbl_orgdocument',$valus,$makecol,'update',$where);

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
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_orgdocument',$valus);
        if($statusinsert)
        {
            $status=1;
            $title = 'Insert';
            $message = 'Data has been inserted successfully.';
        }
        else
        {
            $status=0;
            $title = 'Insert Error';
            $message = 'Data can not been inserted.';
        }
        $name = 'Insert';
    }
    $mysql -> dbDisConnect();

}

$response['status'] = $status;
$response['title'] = $title;
$response['message'] = $message;
$response['name'] = $name;

echo json_encode($response);
