<?php
include 'DB/config.php';
session_start();

if(isset($_POST['action']) && $_POST['action'] == 'inserttblstatus')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['commet'] = $_POST['commet'];    
    $valus[0]['ticketId'] = intval($_POST['ticket_id']);
    $valus[0]['status'] = intval($_POST['status']);
    $valus[0]['odId'] = intval($_POST['other_dept']);
    $valus[0]['oaId'] = intval($_POST['same_dept']);
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    
    
    $makeinsert =  $mysql -> insert('tbl_ticketstatus',$valus);
   
    if($makeinsert){
        $valuss[0]['update_date'] = date('Y-m-d H:i:s');
        $valuss[0]['status'] = $_POST['status'];
        $ticketid = $_POST['ticket_id'];
        $prcol = array('status','update_date');
        $where = 'id ='.$ticketid;
        $permissioninsert = $mysql -> update('tbl_addraiseticket',$valuss,$prcol,'update',$where);
        $status=1;
        $title = 'Insert';
        $message = 'Data has been succesfully Inserted.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been Inserted.';
    }
    
    $name = 'insert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}


else if(isset($_POST['action']) && $_POST['action'] == 'addticketsettingtable')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['role_id'] = intval($_POST['role_id']);    
    $valus[0]['user_id'] = intval($_POST['user_id']);
    $valus[0]['depot_id'] = intval($_POST['depot_id']);
    $valus[0]['assigned_at'] = date('Y-m-d H:i:s');
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    
    
    $makeinsert = $mysql -> insertre('tbl_ticketsetting',$valus);
        
    
    if($makeinsert){
         $status=1;
         $_SESSION['status_id'] = $status;
         $title = 'Insert';
         $message = 'Data has been succesfully Inserted.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been Inserted.';
    }

    
    $name = 'insert';
        
    $mysql -> dbDisConnect();
    $response['last_id'] = $makeinsert;
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}


else if(isset($_POST['action']) && $_POST['action'] == 'VehicleoffencesForm')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $userid = $_POST['user_id'];
    $valus[0]['user_id'] = intval($userid);    
    $valus[0]['occurred_date'] = $_POST['occurred_date'];
    $valus[0]['vehicle_id'] = $_POST['vehicle_id'];
    $valus[0]['driver_id'] = $_POST['driver_id'];
    $valus[0]['identifier'] = $_POST['identifier'];
    $valus[0]['pcnticket_typeid'] = $_POST['pcnticket_typeid'];
    $valus[0]['company_id'] = $_POST['company_id'];
    $valus[0]['hirer_reference'] = $_POST['hirer_reference'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['admin_fee'] = $_POST['admin_fee'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    
    $makeinsert1 = $mysql -> insertre('tbl_vehicleoffences',$valus);

    $countfiles = count($_FILES['files']['name']);

    $upload_location = "uploads/offencesform-image/";

    $files_arr = array();

    for($index = 0;$index < $countfiles;$index++)
    {
       if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != '')
        {
          $filename = $makeinsert1 ."-".uniqid().$_FILES['files']['name'][$index];
    
          $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
          $valid_ext = array("png","jpeg","jpg");
    
          if(in_array($ext, $valid_ext))
          {
    
             $path = $upload_location.$filename;
    
             if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path))
             {
                $files_arr[] = $path;
                $valuss[0]['file'] = $filename;
                // $valuss[0]['user_id'] = $_POST['user_id'];
                $valuss[0]['offences_id'] = $makeinsert1;
                $valuss[0]['insert_date'] = date('Y-m-d H:i:s');
                $makeinsert = $mysql -> insert('tbl_offencesImage',$valuss);
             }
          } 
        }
    }
    
    if($makeinsert1){
         $status=1;
         $_SESSION['status_id'] = $status;
         $title = 'Insert';
         $message = 'Offences has been succesfully Inserted.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Offences can not been Inserted.';
    }
        
        
    $name = 'insert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}

else if(isset($_POST['action']) && $_POST['action'] == 'auditForm')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['userid'] = intval($_SESSION['userid']);    
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['open_at'] = $_POST['opendate'];
    $valus[0]['close_at'] = $_POST['closedate'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $valus[0]['include_paid_invoice'] = intval($_POST['paidcheck']);
    $contractor = implode(",", $_POST['contractor']);
    $valus[0]['people_to_audit'] = $contractor;
    $documents = implode(",", $_POST['document']);
    $valus[0]['document_type'] = $documents;


    if(isset($_POST['insert']))
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_audit',$valus);

        if($makeinsert)
        {
            $status=1;
            $title = 'Insert';
            $message = 'Data has been succesfully Inserted.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Data can not been Inserted.';
        }

        $name = 'insert';
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {

        $mysql = new Mysql();
        $mysql -> dbConnect();

        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','open_at','close_at','include_paid_invoice','people_to_audit','document_type','update_date');

        $where = 'id='.$_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_audit',$valus,$makecol,'update',$where);

        if($statuupdate)
        {
            $status=1;
            $title = 'Update';
            $message = 'Audit updated successfully.';
        }
        else
        {
            $status=0;
            $title = 'Update Error';
            $message = 'Audit can not been updated.';
        }

        $name = 'Update';
        $mysql -> dbDisConnect();

    }    
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentCommetForm')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $status1 = $_POST['status'];

    $valus[0]['accident_id'] = $_POST['accident_id'];    
    $valus[0]['title'] = $_POST['title'];
    $valus[0]['commet'] = $_POST['commet'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    if($_POST['status'] == 0)
    {
        
        $makeinsert = $mysql -> insert('tbl_accidentcommet',$valus);
            
        
        if($makeinsert){
             $status=1;
             $title = 'Insert';
             $message = 'Accident Commet has been succesfully Inserted.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Accident Commet can not been Inserted.';
        }

        $name = 'insert';
        
        $mysql -> dbDisConnect();
    } 
    else
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['commetid'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_accidentcommet',$valus,$makecol,'update',$where);

        if($statuupdate)
        {
            $status=1;
            $title = 'Update';
            $message = 'Accident Commet updated successfully.';
        }
        else
        {
            $status=0;
            $title = 'Update Error';
            $message = 'Accident Commet can not been updated.';
        }
        $name = 'Update';
        $mysql -> dbDisConnect();

    }    

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}

else if(isset($_POST['action']) && $_POST['action'] == 'insertAccidentImageTabledata')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();


    $aid = $_POST['aid'];

    $countfiles = count($_FILES['files']['name']);

    $upload_location = "uploads/accidentimage/";

    $files_arr = array();

    for($index = 0;$index < $countfiles;$index++)
    {
       if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != '')
        {
          $filename = $aid ."-".uniqid().$_FILES['files']['name'][$index];
    
          $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
          $valid_ext = array("png","jpeg","jpg");
    
          if(in_array($ext, $valid_ext))
          {
    
             $path = $upload_location.$filename;
    
             if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path))
             {
                // move_uploaded_file($_FILES['files']['name'], $path);
                $files_arr[] = $path;
                $valus[0]['file'] = $filename;
                $valus[0]['accident_id'] = $_POST['aid'];
                $valus[0]['insert_date'] = date('Y-m-d H:i:s');
                $makeinsert = $mysql -> insert('tbl_accident_image',$valus);
             }
          } 
        }
    }
            
        
        if($makeinsert){
             $status=1;
             $title = 'Insert';
             $message = 'Accident Image has been succesfully Uploaded.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Accident Image can not been Uploaded.';
        }

        $name = 'insert';    
        $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}


else if(isset($_POST['action']) && $_POST['action'] == 'trainingForm')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['userid'] = intval($_SESSION['userid']);    
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['refreshment'] = $_POST['refreshment'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    
    $makeinsert = $mysql -> insert('tbl_training',$valus);
        
    
    if($makeinsert){
         $status=1;
         $title = 'Insert';
         $message = 'Trainig has been succesfully Inserted.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Trainig can not been Inserted.';
    }

    
    $name = 'insert';
        
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}

else if(isset($_POST['action']) && $_POST['action'] == 'TrainingSessionForm')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $status1 = $_POST['status'];

    $valus[0]['training_id'] = $_POST['tid'];
    $valus[0]['date'] = $_POST['sessiondate'];
    $valus[0]['depot_id'] = $_POST['depot'];
    $valus[0]['people_id'] = $_POST['people'];        
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $status1 = $_POST['status'];

    if($status1 == 0)
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();

    
        $makeinsert = $mysql -> insert('tbl_trainingsession',$valus);
            
        
        if($makeinsert){
             $status=1;
             $title = 'Insert';
             $message = 'Session has been succesfully Inserted.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Session can not been Inserted.';
        }

        $name = 'insert';
        $mysql -> dbDisConnect();
    }
    elseif($status1 == 1)
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $statusresult = array('date','depot_id','people_id','update_date');
        $where = 'id ='.$id;
        $isactiveupdate = $mysql -> update('tbl_trainingsession',$valus,$statusresult,'update',$where);

        if($isactiveupdate)
        {
            $status=1;
            $title = 'Update';
            $message = 'Training has been succesfully Update.';
        }
        else
        {
            $status=0;
            $title = 'Update Error';
            $message = 'Training can not been Update.';
        }

        $name = 'update'; 
        $mysql -> dbDisConnect();
    }    
    

    $response['status'] = $isactiveupdate;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}

else if(isset($_POST['action']) && $_POST['action'] == 'communicationForm')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['subject'] = $_POST['subject'];
    $valus[0]['meassage'] = $_POST['area'];
    $valus[0]['type'] = $_POST['ctype'];

    $valus[0]['files'] = $_POST['image_name'];        
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $st = $_POST['insert'];
    // $depot = count($_POST['depot_id']);
    $depot = (join(",",$_POST['depot_id']));
    
    $valus[0]['depot_id'] = $depot; 

    if($st == "1")
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_communication',$valus); 

        $emlusername='noreply@drivaar.com';
        $emlpassword='DrivaarInvitation@123';
        $emltitle = 'Important notification from Drivaar';
        $subject = $_POST['subject'];
        $body = 'Drivaar';
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
                    <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear Associates</p></h2>
               '.$_POST['area'].'

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
              $dpids = explode(",",$depot);
              $whrDept = " d.id = ".implode(" OR d.id = ",$dpids);
              $active = "";
              if($_POST['ctype']<2)
              {
                $active = "c.isactive = ".$_POST['ctype']." AND";
              }
              $query = "SELECT c.`name`,c.`email` FROM `tbl_contractor` c INNER JOIN `tbl_depot` d on d.`id`=c.`depot` WHERE $active $whrDept ";
              $strow =  $mysql->selectFreeRun($query);
              while($statusresult = mysqli_fetch_array($strow))
              {
                  $mail->AddAddress($statusresult['email'],$statusresult['name']);
              }
              $mail->SetFrom($emlusername,$emltitle);
              $mail->mailtype = 'html';
              $mail->charset = 'iso-8859-1';
              $mail->wordwrap = TRUE;
              $mail->Subject = $subject;
              $mail->AltBody = $body; 
              $mail->MsgHTML($dataMsg);
              $mail->Send();
              $status=1;
              $mail=1;
              $cnt++;
        } 
        catch (phpmailerException $e) 
        {
            // $status=0;
            $mail=0;
            // $title = 'Email not Sent';
            // $message = 'Failed to sent mail.';
            // $failed .= "<br>".$typeresult['invoice_no']." # ".$e;
            exit;
        } 
        catch (Exception $e) 
        {
            // echo $e;
            $mail=0;
            // $status=0;
            // $title = 'Email not Sent';
            // $message = 'Failed to sent mail.';
            // $failed .= "<br>".$typeresult['invoice_no']." # ".$e;
            exit;   
        }  

        if($makeinsert){
            $status=1;
            $title = 'Insert';
            $message = 'Session has been succesfully Inserted.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Session can not been Inserted.';
        }

        $name = 'insert';
        $mysql -> dbDisConnect();
    }


    // elseif($_POST['update'])
    // {
    //     $mysql = new Mysql();
    //     $mysql -> dbConnect();

    //     $valus[0]['update_date'] = date('Y-m-d H:i:s');
    //     $statusresult = array('date','depot_id','people_id','update_date');
    //     $where = 'id ='.$id;
    //     $isactiveupdate = $mysql -> update('tbl_trainingsession',$valus,$statusresult,'update',$where);

    //     if($isactiveupdate)
    //     {
    //         $status=1;
    //         $title = 'Update';
    //         $message = 'Training has been succesfully Update.';
    //     }
    //     else
    //     {
    //         $status=0;
    //         $title = 'Update Error';
    //         $message = 'Training can not been Update.';
    //     }

    //     $name = 'update'; 
    //     $mysql -> dbDisConnect();
    // }    
    

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['mail'] = $mail;
    echo json_encode($response);  
}

// else if(isset($_POST['action']) && $_POST['action'] == 'AddRentalAdjustmentform')
// {
//     header("content-Type: application/json");
//     $status=0;
//     $title = 'Error';
//     $message = 'Something Went Wrong';
//     $name = '';

//     $valus[0]['userid'] = $_POST['userid'];
//     $valus[0]['rental_id'] = $_POST['rid'];
//     $valus[0]['type'] = $_POST['rent_type'];
//     $valus[0]['amount'] = $_POST['amount'];
//     $valus[0]['description'] = $_POST['description'];
//     $valus[0]['insert_date'] = date('Y-m-d H:i:s');

//     if(isset($_POST['insert']))
//     {
//         $mysql = new Mysql();
//         $mysql -> dbConnect();

    
//         $makeinsert = $mysql -> insert('tbl_rentaladjustment',$valus);
//         // echo $makeinsert;
//         if($makeinsert){
//              $status=1;
//              $title = 'Insert';
//              $message = 'Adjustment has been succesfully Inserted.';
//         }
//         else{
//             $status=0;
//             $title = 'Insert Error';
//             $message = 'Adjustment can not been Inserted.';
//         }

//         $name = 'insert';
//         $mysql -> dbDisConnect();
//     }
//     else
//     {
//         $mysql = new Mysql();
//         $mysql -> dbConnect();

//         $valus[0]['update_date'] = date('Y-m-d H:i:s');
//         $statusresult = array('type','amount','description','update_date');
//         $where = 'id ='.$id;
//         $isactiveupdate = $mysql -> update('tbl_rentaladjustment',$valus,$statusresult,'update',$where);

//         if($isactiveupdate)
//         {
//             $status=1;
//             $title = 'Update';
//             $message = 'Adjustment has been succesfully Update.';
//         }
//         else
//         {
//             $status=0;
//             $title = 'Update Error';
//             $message = 'Adjustment can not been Update.';
//         }

//         $name = 'update'; 
//         $mysql -> dbDisConnect();
//     }    
    

//     $response['status'] = $status;
//     $response['title'] = $title;
//     $response['message'] = $message;
//     $response['name'] = $name;
//     echo json_encode($response);  
// }

else if(isset($_POST['action']) && $_POST['action'] == 'AddRentalAdjustmentform')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';


    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['rental_id'] = $_POST['rid'];
    $valus[0]['type'] = $_POST['rent_type'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['description'] = $_POST['description'];
    
    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_rentaladjustment',$valus);
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
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('type','amount','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_rentaladjustment',$valus,$makecol,'update',$where);

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

    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}else if(isset($_POST['action']) && $_POST['action'] == 'terminateAgreement')
{
    header("content-Type: application/json");
    $agreenevtId = $_POST['id'];
    $terminateDate = $_POST['termdate'];
    $oldtermdate = $_POST['oldtermdate'];
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['return_date'] = $terminateDate;
    $valus[0]['isactive'] = 2;
    $valus[0]['old_return_date'] = $oldtermdate;
    $makecol = array("return_date","isactive","old_return_date");
    $where = ' id ='. $agreenevtId;
    $statuupdate = $mysql -> update('tbl_vehiclerental_agreement',$valus,$makecol,'update',$where);
    if($statuupdate)
    {
        $status=1;
        $title = 'Update';
        $message = 'Agreement has been terminated successfully!!!';
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
    echo json_encode($response); 
}





?>