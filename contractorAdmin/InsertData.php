<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');
if(!isset($_SESSION)) 
{
    session_start();
}

if(isset($_POST['action']) && $_POST['action'] == 'addfeedbackForm')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['cid'] = $_SESSION['cid'];
    $valus[0]['feedback'] = $_POST['feedback'];

 
    
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $makeinsert = $mysql -> insert('tbl_contactorfeedback',$valus);
    if($makeinsert)
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
    $mysql -> dbDisConnect();
    
    

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'addReportAccidentForm')
{
    header("content-Type: application/json");

    $mysql = new Mysql();     
    $mysql -> dbConnect();

    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $cid=$_SESSION['cid'];
    $valus[0]['cid'] = $cid;
    $valus[0]['description_accident'] = $_POST['description_accident'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['vehicle_plat_number'] = $_POST['vehicle_plat_number'];
    $valus[0]['contact'] = $_POST['contact'];
    $valus[0]['notes'] = $_POST['notes'];
    $valus[0]['reported_insurance_company'] = $_POST['reported_insurance_company'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $makeinsert = $mysql -> insertre('tbl_reportaccident',$valus);
    $countfiles = count($_FILES['files']['name']);

    $upload_location = "../home/uploads/contractor-report-accident/";

    $files_arr = array();

    for($index = 0;$index < $countfiles;$index++){

    if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
      $filename = $_FILES['files']['name'][$index];

      $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

      $valid_ext = array("png","jpeg","jpg");

      if(in_array($ext, $valid_ext)){

         $path = $upload_location.uniqid().$filename;

         if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){
            $files_arr[] = $path;
            $valuss[0]['report_id'] = $makeinsert;
            $valuss[0]['cid'] = $cid;
            $valuss[0]['file'] = $filename;
            $valuss[0]['insert_date'] = date('Y-m-d H:i:s');
            $makeinsert1 = $mysql -> insert('tbl_reportaccidentImage',$valuss);
         }
     } 
   }
    

    }
    
    if($makeinsert1)
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
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'addraiseticketForm')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['cid'] = intval($_SESSION['cid']);
    $valus[0]['issue'] = intval($_POST['issue']);
    // if($_POST['issue'] == 1)
    // {
    //    $valus[0]['department'] = 1; 
    // }
    // else
    // {
    //   $valus[0]['department'] = 0;  
    // }
    
    $valus[0]['commit'] = $_POST['commit'];
    
     $valus[0]['status'] = 4;
 
    
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $makeinsert = $mysql -> insertre('tbl_addraiseticket',$valus);
    if($makeinsert)
    {
        $sql = "SELECT `tbl_department`.`department`,`tbl_department`.`role_id`,`tbl_ticketsetting`.`user_id` FROM `tbl_addraiseticket`
         INNER JOIN `tbl_department` ON `tbl_department`.`issuetypeId` = `tbl_addraiseticket`.`issue`
         INNER JOIN `tbl_ticketsetting` ON `tbl_ticketsetting`.`role_id` = `tbl_department`.`role_id` 
         WHERE `tbl_addraiseticket`.`id` = $makeinsert";
        $sel =  $mysql -> selectFreeRun($sql);
        $records = mysqli_fetch_array($sel);

        $valuss[0]['ticketId'] = $makeinsert;
        $valuss[0]['adminId'] = intval($records['user_id']);
        $valuss[0]['insert_date'] = date('Y-m-d H:i:s');
        $makeinsert1 = $mysql -> insert('tbl_ticketstatus',$valuss);

        $status=1;
        $title = 'Insert';
        $message = 'Raise Ticket has been inserted successfully.';
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Raise Ticket can not been inserted.';
    }
    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    
    

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 

}



else if(isset($_POST['action']) && $_POST['action'] == 'addleaverequestForm')
{
    header("content-Type: application/json");

    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['cid'] = $_SESSION['cid'];
    $valus[0]['notes'] = $_POST['notes'];
   
    $valus[0]['start_date'] = $_POST['startdate'];
    
    $valus[0]['end_date'] = $_POST['enddate'];
    
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $makeinsert = $mysql -> insert('tbl_leaverequest',$valus);
    if($makeinsert)
    {
        $status=1;
        $title = 'Insert';
        $message = 'Leave Request has been inserted successfully.';
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Leave Request can not been inserted.';
    }
    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    
    

    $response['status'] =  $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'UpdateAndInsertcontractorinvoicestatus')
{
    header("content-Type: application/json");

    
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
   
    $valus[0]['status'] = 5;

    $issueid = intval($_POST['issue']);

    $valus[0]['issue'] = $issueid;
   
    $valus[0]['commit'] = $_POST['commit'];
    
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $invoice = $_POST['invoice_no'];

    $mysql = new Mysql();

    $mysql -> dbConnect();


    // $sql = "SELECT * FROM `tbl_department` WHERE issuetypeId=$issueid";
    // $fire =  $mysql -> selectFreeRun($sql);
    // $fetch_rows = mysqli_fetch_array($fire);
    // $id = $fetch_rows['id'];
    // $valus[0]['department'] = $fetch_rows['id'];

    $sql1 = "SELECT * FROM `tbl_contractorinvoice` WHERE invoice_no='$invoice'";
    $fire1 =  $mysql -> selectFreeRun($sql1);
    $fetch_rows1 = mysqli_fetch_array($fire1);
    $cid = $fetch_rows1['cid'];
    $valus[0]['cid'] = intval($cid);
    
    $makeinsert = $mysql -> insert('tbl_addraiseticket',$valus);
    if($makeinsert)
    {
        $status=1;
        // $invoice = $_POST['invoice_no'];
        $valuss[0]['status_id']=9;
        $valuss[0]['disputed_comment'] = $_POST['commit'];
        $valuss[0]['disputed_date'] = date('Y-m-d H:i:s');  
        $valuss[0]['update_date'] = date('Y-m-d H:i:s');  
        $where = 'invoice_no='."'$invoice'";
        $usercol = array('status_id','disputed_comment','invoice_no','update_date','disputed_date');
        $paymentdata =  $mysql -> update('tbl_contractorinvoice',$valuss,$usercol,'update',$where);

        $status=1;
        $title = 'Insert';
        $message = 'Ticket has been successfully Disputed.';
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Ticket can not been Disputed.';
    }

    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    
    

    $response['status'] =  $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'recoverform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $email = $_POST['email'];
    $name =$_POST['name'];
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $query = "SELECT * FROM `tbl_contractor` WHERE `isdelete`=0 AND email ='".$email."'";
    $result =  $mysql -> selectFreeRun($query); 
    $row= mysqli_fetch_array($result);

    if($row) {
        $token = md5($email).rand(10,9999);
        $where = "email = '$email'";
        $valus[0]['reset_link_token'] = $token;
        $update = $mysql -> update('tbl_contractor', $valus, 'reset_link_token', 'update', $where);

        $link = "<a href='".$webroot."reset-password.php?key=".$row['id']."&token=".$token."'>
        Click here To Reset password</a>";

        $emlusername='noreply@drivaar.com';
        $emlpassword='DrivaarInvitation@123';
        $emltitle = 'Drivaar';
        $subject = "Drivaar : Recover Password";
        $body = 'Please verify it"s you.';

         $dataMsg = '
                    <html>
                        <head>
                            <style>
                                .content {
                                  max-width: 1000px;
                                  margin: auto;
                                }
                                </style>
                        </head>
                    <body>
                    <div class="row content">
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$row["name"].'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of  Bryanston Logistics.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using omar.ismai@outlook.com. Make sure to save this email so that you can access this link later.<br><u><b>We received a request to reset your Drivaar password :</b></u>'.$link.'.<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driving license, UTR number, National Insurance (NI) number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When you have completed these tasks, Drivaar will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br> <a href="#"><img height="50px" width="200px" src="./google-play.png"></a> <a href="#!"><img height="50px" width="200px" src="./app-store.png"></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Drivaar Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>';  
        require_once('phpmailer/class.phpmailer.php');
        $mail = new PHPMailer(true);            
        $mail->IsSMTP(); 
        try 
        {
              $mail->Host       = "smtp.office365.com"; 
              $mail->SMTPDebug  = 0;
              $mail->SMTPAuth   = true; 
              $mail->Port       = 587;
              $mail->SMTPSecure = 'tls';     
              $mail->Username   = $emlusername; 
              $mail->Password   = $emlpassword;        
              $mail->AddAddress($email,$name);
              $mail->SetFrom($emlusername,$emltitle);
              $mail->mailtype = 'html';
              $mail->charset = 'iso-8859-1';
              $mail->wordwrap = TRUE;
              $mail->Subject = $subject;
              $mail->AltBody = $body; 
              $mail->MsgHTML($dataMsg);
              $mail->Send();
              $status=1;
              $title = 'Email Sent';
              $message = 'Check Your Email and Click on the link sent to your email.';
                
        } 
        catch (phpmailerException $e) 
        {
            alert($e);
            $status=0;
            $title = 'Email not Sent';
            $message = $mail->ErrorInfo;
            exit;
        } 
        catch (Exception $e) 
        {
            alert($e);
            exit;   
        }  
    }
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['link'] = $link;
    $response['name'] = $row['name'];
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'resetform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['password'] = $_POST['password'];
    // $valus[0]['confirm_password'] = $_POST['current_password'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $valus[0]['ispasswordchange'] = 1;

    $key = $_POST['key'];
    $token = $_POST['token'];

        $mysql = new Mysql();
        $mysql -> dbConnect();
        $makecol = array('password','ispasswordchange','update_date');
       // $where = "id ='$key' AND reset_link_token ='$token'";
        $where = 'id ='.$_POST['key'];
        $statuupdate = $mysql -> update('tbl_contractor',$valus,$makecol,'update',$where);

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
        $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'msformOdometer')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['odometer'] = $_POST['odometer'];
    $valus[0]['answer_type'] = 1;
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $inserdate = $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $valus[0]['odometerInsert_date'] = $inserdate;
    $valus[0]['queVehclIdtime'] = 0 ."-". $_POST['vid'] ."-". $inserdate;
    
    $statusinsert = $mysql -> insertre('tbl_vehicleinspection',$valus);
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
    
        $query = "SELECT * FROM `tbl_vehicleinspection` WHERE id =".$statusinsert;
        $row =  $mysql -> selectFreeRun($query); 
        $result = mysqli_fetch_array($row);
        $date = $result['insert_date'];
        $mysql -> dbDisConnect();

    $response['date'] = $date;
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'msformremark')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $dbc=$mysql -> dbConnect();
    
    $vehicle_id = $_POST['vid'];
    $answer_type = $_POST['answer_type'];
    $question_id = $_POST['question_id'];
    $oid = $_POST['oid'];
    $qvtId = $_POST['queVehclIdtime'];
    $remark = $_POST['remark'];
    $uploadStatus = 1; 
    
    $file = $_FILES["file"]["name"];
    $file_tmp =$_FILES['file']['tmp_name'];
    
    if(!empty($_FILES["file"]["name"]))
    { 
                 
        $uploadDir = 'uploads/vehicleinspectiondocument/'; 
        $str = $_POST['queVehclIdtime']; 
        function RemoveSpecialChar($str) 
        {
            $res = str_replace( array( '\'', '',
            '-' , ':' ), '', $str);
            $name = preg_replace(' ', '', $str);
            return $res;
        }
      
        $qvtId1 = basename(RemoveSpecialChar($str));
        $fileName = $qvtId1.basename($_FILES["file"]["name"]); 
        $targetFilePath = $uploadDir . $fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
         
        // Allow certain file formats 
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
        if(in_array($fileType, $allowTypes))
        { 
            if(move_uploaded_file($file_tmp, $targetFilePath))
            { 
                $uploadedFile = $fileName; 
                
            }
            else
            { 
                $uploadStatus = 0; 
                $response['message'] = 'Sorry, there was an error uploading your file.'; 
            } 
        }
        else
        { 
            $uploadStatus = 0; 
            $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
        } 
    }   
            
    if($uploadStatus == 1 && $answer_type == 0)
    { 
        $statusinsert = "INSERT INTO `tbl_vehicleinspection`(`vehicle_id`,`queVehclIdtime`,`question_id`,`answer_type`,`remark`,`file`,odometerInsert_date) VALUES ($vehicle_id,'".$qvtId."',$question_id,$answer_type,'".$remark."','".$fileName."','".$oid."') ON DUPLICATE KEY UPDATE `remark`='".$remark."' AND `file`='".$file."'";
        $fire = mysqli_query($dbc, $statusinsert);
    } 
    else
    {
        $statusinsert = "INSERT INTO `tbl_vehicleinspection`(`vehicle_id`,`queVehclIdtime`,`question_id`,`answer_type`,`remark`,`file`,odometerInsert_date) VALUES ($vehicle_id,'".$qvtId."',$question_id,$answer_type,NULL,NULL,'".$oid."') ON DUPLICATE KEY UPDATE `answer_type`=$answer_type AND `remark`= NULL AND `file`= NULL";
        $fire = mysqli_query($dbc, $statusinsert);
    }
    if($fire)
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
     
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
?>