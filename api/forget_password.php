<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');
$action=$_POST['action'];
$email=$_POST['email'];

// Function to generate OTP
function generateNumericOTP($n) {
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand()%(strlen($generator))), 1);
    }
    return $result;
}

$status=0;
if(isset($action) && ($action=='forget_password') && isset($email))
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `email`='".$email."' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
        if($row['isactive']==0)
        {
            //send email
            $email = $row['email'];
            $name = $row['name'];
            $n = 6;
            $otp = generateNumericOTP($n);

            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Please verify it's you..";
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
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$name.'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using '.$email.' Make sure to save this email so that you can access this link later.<br>Your OTP is <u><b>'.$otp.'</b></u>.<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driver’s license, UTR number, NINO number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When you’ve completed these tasks, Drivaar will contact you with next steps. 
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
                  $success = "Email has been send successfully.";
            
            } 
            catch (phpmailerException $e) 
            {
              //echo $e->errorMessage(); exit;
                $error = $mail->ErrorInfo;
                $errorcode = "109";
            } 
            catch (Exception $e) 
            {
              //echo $e->getMessage();  exit;   
            } 
        }
        else
        {
            $status=0;
            $error = "Driver is not active.";
            $errorcode = "102";
        }        
    }
    else
    {
        $status=0;
        $error = "Your email does not match.";
        $errorcode = "107";
    }

    if($status==1)
    {
        $array = array("success" => $success,"otp" => $otp);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,'data' => $array));
}
else
{
    $status=0;
    $error = "Authentication filed.";
    $errorcode = "101";
    $array = array("errorcode" =>$errorcode,"error"=>$error);
    echo json_encode(array('status' => $status,'data' => $array));
}
?>