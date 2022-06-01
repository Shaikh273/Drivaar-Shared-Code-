<?php
include 'DB/config.php';
header("content-Type: application/json");
$status=0;
$title = 'Error';
$message = 'Something Went Wrong';
$name = '';

$mysql = new Mysql();
$mysql -> dbConnect();
$paidFlag=0;
$checkPaid = "SELECT `id`,`status_id` FROM `tbl_contractorinvoice` WHERE `cid`=".$_POST['cntid']." AND '".$_POST['firstDate']."' BETWEEN `from_date` AND `to_date`";
$runPaind = $mysql->selectFreeRun($checkPaid);
while($getPaid = mysqli_fetch_array($runPaind))
{
    if($getPaid['status_id']==4)
    {
        $paidFlag=1;
    }
}
if($paidFlag==0)
{

    $flag=0;
    $valus[0]['cid'] = $_POST['cntid'];
    $valus[0]['loan_id'] = $_POST['loan_id'];
    $valus[0]['week_no'] = $_POST['date'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['date'] = $_POST['hidWeek'];
    $valus[0]['account'] = $_POST['account'];
    $valus[0]['isadvanced'] = $_POST['isadvanced'];
    $valus[0]['reason'] = $_POST['reason'];
    $valus[0]['category'] = $_POST['category'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $uploadDir = 'uploads/contractorwalletdocument/'; 
    $uploadedFile = ''; 
    $fileName = basename($_FILES["file"]["name"]); 
    if(!empty($fileName))
    { 
        $targetFilePath = $uploadDir . $_POST['cntid'].'_'.$fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
        // Allow certain file formats 
        $allowTypes = array('pdf','doc','docx','jpg','png','jpeg','PNG','JPEG','JPG'); 
        if(in_array($fileType, $allowTypes))
        { 

            if($_FILES['file']['size']<6000000)
            {
                // Upload file to the server 
                if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
                { 
                    $uploadedFile = $fileName; 
                    $valus[0]['file'] = $_POST['cntid'].'_'.$uploadedFile;
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
        $query = "SELECT * FROM `tbl_contractorlend` WHERE `isactive`=0 AND `isdelete`=0 AND `id`=".$_POST['loan_id'];
        $row =  $mysql -> selectFreeRun($query);
        $result = mysqli_fetch_array($row);
        $paymentquery = "SELECT SUM(`amount`) AS paidamount FROM `tbl_contractorpayment` WHERE `loan_id`=".$result['id']." AND `isdelete`=0 AND `cid`=".$result['cid'];
        $paymentrow =  $mysql -> selectFreeRun($paymentquery);
        $pyresult = mysqli_fetch_array($paymentrow);
        $remianamount = ($result['amount']-$pyresult['paidamount']);
        $payamount = 0;
        $remianamount = round($remianamount,2);
        if($remianamount>=$_POST['amount'])
        {   
            if($remianamount==$_POST['amount'])
            {
                $flag=1;
            }
            $payamount = $_POST['amount'];
        }
        if($payamount>0)
        {
             $statusinsert = $mysql -> insert('tbl_contractorpayment',$valus);
        }
        if($flag==1)
        {
            $valus1[0]['is_completed'] = 1;
            $valus1[0]['update_date'] = date('Y-m-d H:i:s');
            $makecol = array('is_completed','update_date');
            $where = 'id ='. $_POST['loan_id'];
            $statuupdate = $mysql -> update('tbl_contractorlend',$valus1,$makecol,'update',$where);
        }
        if($statusinsert)
        {
            $status=1;
            $title = 'Insert';
            $message = 'Data has been inserted successfully.';
        }
        else
        {
            $status=0;
            $title = 'Error';
            $message = 'This loan account has been already settled.';
        }
        $name = 'Insert';
    }
}
else
{
    $status=0;
    $title = 'Error';
    $message = 'The invoice status of selected week is paid.';
}
$mysql -> dbDisConnect();

$response['status'] = $status;
$response['title'] = $title;
$response['message'] = $message;
$response['name'] = 'Insert';
$response['payAmount'] = $payamount;
$response['qry'] = $statusinsert;
$response['remain'] = $remianamount;
$response['amnt'] = $_POST['amount'];
echo json_encode($response);

?>