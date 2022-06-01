<?php
if (!isset($_SESSION)) {
    session_start();
}
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();
$cid = $_SESSION['cid'];
$valus[0]['update_date'] = date('Y-m-d H:i:s');
$valus[0]['email'] = $_POST['email'];
$valus[0]['contact'] = $_POST['contact'];
$valus[0]['address'] = $_POST['address'];
$valus[0]['street_address'] = $_POST['street_address'];
$valus[0]['city'] = $_POST['city'];
$valus[0]['state'] = $_POST['state'];
$valus[0]['postcode'] = $_POST['postcode'];
$valus[0]['country'] = $_POST['country'];
if($_POST['newpassword'] != "")
{
    $valus[0]['password'] = $_POST['newpassword'];
}    
$valus[0]['ispasswordchange'] = 1;

$uploadDir = 'upload/Userprofile/'; 
$uploadedFile = ''; 
$fileName = basename($_FILES["file"]["name"]); 


if($cid>0)
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
        $makecol = array('email','contact','address','street_address','city','state','postcode','country','update_date','password','file','ispasswordchange');
    }
    else
    {
        $makecol = array('email','contact','address','street_address','city','state','postcode','country','update_date','password','ispasswordchange');
    }
    
    $whr = 'id ='.$cid;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $statuupdate = $mysql -> update('tbl_contractor',$valus,$makecol,'update',$whr);

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
?>