<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();
$valus[0]['workforce_id'] = $_POST['wid'];
$valus[0]['type'] = $_POST['type'];
    $query1 = "SELECT * FROM `tbl_vehicledocumenttype` WHERE `id`=". $_POST['type'];
    $userrow1 =  $mysql -> selectFreeRun($query1);
    $renewresult1 = mysqli_fetch_array($userrow1);
$valus[0]['typename'] = $renewresult1['name'];
$valus[0]['name'] = $_POST['name'];
$valus[0]['expiredate'] = $_POST['expiredate'];

$uploadDir = 'uploads/workforcedocument/'; 
$uploadedFile = ''; 

$fileName = basename($_FILES["file"]["name"]); 
if(!empty($fileName))
{ 
    $targetFilePath = $uploadDir . $_POST['wid'].'_'.$fileName; 
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
    // Allow certain file formats 
    $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
    if(in_array($fileType, $allowTypes))
    { 

        if($_FILES['file']['size']<6000000)
        {
            // Upload file to the server 
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
            { 
                $uploadedFile = $fileName; 
                $valus[0]['file'] = $_POST['wid'].'_'.$uploadedFile;
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


if($status == 1)
{
    if($_POST['id']>0)
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('type','typename','name','expiredate','file','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforcedocument',$valus,$makecol,'update',$where);

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
        $statusinsert = $mysql -> insert('tbl_workforcedocument',$valus);
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

?>