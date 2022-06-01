<?php
// include "db_conn.php";
include "../DB/config.php";
$cid = base64_decode($_GET['cid']);
if(isset($cid))
{
	$mysql = new Mysql();
	$mysql -> dbConnect();
	$contractor =  $mysql -> selectWhere('tbl_contractor','id','=',$cid,'int');
	$ciresult = mysqli_fetch_array($contractor);
	if(isset($_POST['insert']))
	{
		$valus[0]['name'] = $_POST['name'];
		$valus[0]['dob']=$_POST['dob'];
		$valus[0]['birth_town'] = $_POST['birth_town'];
		$valus[0]['birth_country'] = $_POST['birth_country'];
		$valus[0]['email'] = $_POST['email'];
		$valus[0]['contact'] = $_POST['contact'];
		$valus[0]['emegency_name'] = $_POST['emegency_name'];

		$valus[0]['emegency_phone'] = $_POST['emegency_phone'];
		$valus[0]['ni_number'] = $_POST['ni_number'];
		$valus[0]['utr'] = $_POST['utr'];
		$valus[0]['address'] = $_POST['address'];
		$valus[0]['city'] = $_POST['city'];
		$valus[0]['state'] = $_POST['state'];
		$valus[0]['postcode'] = $_POST['postcode'];
		$valus[0]['country'] = $_POST['country'];
		$valus[0]['passport_number'] = $_POST['passport_number'];
		$valus[0]['passport_nationality'] = $_POST['passport_nationality'];
		$valus[0]['passport_country'] = $_POST['passport_country'];
		$valus[0]['passport_issuedate'] = $_POST['passport_issuedate'];
		$valus[0]['passport_expirydate'] = $_POST['passport_expirydate'];
		$valus[0]['drivinglicence_number'] = $_POST['drivinglicence_number'];
		$valus[0]['drivinglicence_country'] = $_POST['drivinglicence_country'];
		$valus[0]['work_permit_no'] = $_POST['work_permit_no'];
		$valus[0]['permit_expiry'] = $_POST['permit_expiry'];
		$valus[0]['bank_name'] = $_POST['bank_name'];
		$valus[0]['sort_code'] = $_POST['sort_code'];
		$valus[0]['account_number'] = $_POST['account_number'];
		$valus[0]['bank_account_name'] = $_POST['bank_account_name'];
		if(isset($_FILES["national_insurancefile"])) 
		{
		    $filename = $cid.'_'.$_FILES["national_insurancefile"]["name"];  
		    $folder = "documents/NationalInsurance/".$filename;   
            move_uploaded_file($_FILES['national_insurancefile']['tmp_name'], $folder);
            $valus[0]['national_insurancefile'] = $filename;
            $valusn[0]['contractor_id'] = $cid;
		    $valusn[0]['type'] = 17;
		    $valusn[0]['typename'] = 'National Insurance';
		    $valusn[0]['name'] = $_POST['name'];
		    $valusn[0]['file'] = $folder;
		    $valusn[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makeinsert = $mysql -> insert('tbl_contractordocument',$valusn);
		}
		if(isset($_FILES["address_proof_file"]))
		{
		    $filename = $cid.'_'.$_FILES["address_proof_file"]["name"];  
		    $folder = "documents/AddressProofFile/".$filename;   
	        move_uploaded_file($_FILES['address_proof_file']['tmp_name'], $folder);
            $valus[0]['address_proof_file'] = $filename;
            $valusa[0]['contractor_id'] = $cid;
		    $valusa[0]['type'] = 13;
		    $valusa[0]['typename'] = 'Proof of Address';
		    $valusa[0]['name'] = $_POST['name'];
		    $valusa[0]['file'] = $folder;
		    $valusa[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makeainsert = $mysql -> insert('tbl_contractordocument',$valusa);
		}
		if(isset($_FILES["passport_photo"]))
		{
		    $filename = $cid.'_'.$_FILES["passport_photo"]["name"];  
		    $folder = "documents/PassportPhoto/".$filename;   
            move_uploaded_file($_FILES['passport_photo']['tmp_name'], $folder);
            $valus[0]['passport_photo'] = $filename;
            $valusp[0]['contractor_id'] = $cid;
		    $valusp[0]['type'] = 11;
		    $valusp[0]['typename'] = 'Passport';
		    $valusp[0]['name'] = $_POST['name'];
		    $valusp[0]['file'] = $folder;
		    $valusn[0]['expiredate'] = $_POST['passport_expirydate'];
		    $valusp[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makepinsert = $mysql -> insert('tbl_contractordocument',$valusp);
		}
		if(isset($_FILES["driving_lic_fnt"]))
		{
		    $filename = $cid.'_Front_'.$_FILES["driving_lic_fnt"]["name"];  
		    $folder = "documents/DrivingLicence/".$filename;   
            move_uploaded_file($_FILES['driving_lic_fnt']['tmp_name'], $folder);
            $valus[0]['driving_lic_fnt'] = $filename;
            $valusd[0]['contractor_id'] = $cid;
		    $valusd[0]['type'] = 2;
		    $valusd[0]['typename'] = 'DL';
		    $valusd[0]['name'] = $_POST['name'];
		    $valusd[0]['file'] = $folder;
		    $valusn[0]['expiredate'] = $_POST['drivinglicence_expiry'];
		    $valusd[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makedinsert = $mysql -> insert('tbl_contractordocument',$valusd);
		}
		if(isset($_FILES["driving_lic_back"]))
		{
		    $filename = $cid.'_Back_'.$_FILES["driving_lic_back"]["name"];  
		    $folder = "documents/DrivingLicence/".$filename;   
            move_uploaded_file($_FILES['driving_lic_back']['tmp_name'], $folder);
            $valus[0]['driving_lic_back'] = $filename;
            $valusdb[0]['contractor_id'] = $cid;
		    $valusdb[0]['type'] = 2;
		    $valusdb[0]['typename'] = 'DL';
		    $valusdb[0]['name'] = $_POST['name'];
		    $valusdb[0]['file'] = $folder;
		    $valusn[0]['expiredate'] = $_POST['drivinglicence_expiry'];
		    $valusdb[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makedbinsert = $mysql -> insert('tbl_contractordocument',$valusdb);
		}
		if(isset($_FILES["work_permit_frt"]))
		{
		    $filename = $cid.'_Front_'.$_FILES["work_permit_frt"]["name"];  
		    $folder = "documents/WorkPermit/".$filename;   
            move_uploaded_file($_FILES['work_permit_frt']['tmp_name'], $folder);
            $valus[0]['work_permit_frt'] = $filename;
            $valusw[0]['contractor_id'] = $cid;
		    $valusw[0]['type'] = 2;
		    $valusw[0]['typename'] = 'Work Permit';
		    $valusw[0]['name'] = $_POST['name'];
		    $valusw[0]['file'] = $folder;
		    $valusn[0]['expiredate'] = $_POST['permit_expiry'];
		    $valusw[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makewinsert = $mysql -> insert('tbl_contractordocument',$valusw);
		}
		if(isset($_FILES["work_permit_back"]))
		{
		    $filename = $cid.'_Back_'.$_FILES["work_permit_back"]["name"];  
		    $folder = "documents/WorkPermit/".$filename; 
            move_uploaded_file($_FILES['work_permit_back']['tmp_name'], $folder);
            $valus[0]['work_permit_back'] = $filename;
            $valuswb[0]['contractor_id'] = $cid;
		    $valuswb[0]['type'] = 2;
		    $valuswb[0]['typename'] = 'Work Permit';
		    $valuswb[0]['name'] = $_POST['name'];
		    $valuswb[0]['file'] = $folder;
		    $valusn[0]['expiredate'] = $_POST['permit_expiry'];
		    $valuswb[0]['insert_date'] = date('Y-m-d H:i:s');
			$makewbinsert = $mysql -> insert('tbl_contractordocument',$valuswb);
		}
		if(isset($_FILES["drug_test"]))
		{
		    $filename = $cid.'_'.$_FILES["drug_test"]["name"];  
		    $folder = "documents/DrugTest/".$filename;   
            move_uploaded_file($_FILES['drug_test']['tmp_name'], $folder);
            $valus[0]['drug_test'] = $filename;
            $valusdu[0]['contractor_id'] = $cid;
		    $valusdu[0]['type'] = 14;
		    $valusdu[0]['typename'] = 'Drug & Alcohol Screening Report';
		    $valusdu[0]['name'] = $_POST['name'];
		    $valusdu[0]['file'] = $folder;
		    $valusdu[0]['insert_date'] = date('Y-m-d H:i:s');
		    $makeduinsert = $mysql -> insert('tbl_contractordocument',$valusdu);
		}

		$valus[0]['update_date'] = date('Y-m-d H:i:s');
	    $contra_col = array('name','dob','birth_town','birth_country','email','contact','emegency_name','emegency_phone','ni_number','utr','address','city','state','postcode','country','passport_number','passport_nationality','passport_country','passport_issuedate','passport_expirydate','national_insurancefile','address_proof_file','passport_photo','drivinglicence_number','drivinglicence_country','work_permit_no','permit_expiry','bank_name','sort_code','account_number','bank_account_name','driving_lic_fnt','driving_lic_back','work_permit_frt','work_permit_back','drug_test','update_date');
	    $where = 'id ='.$cid;
	    $contractorInsert = $mysql -> update('tbl_contractor',$valus,$contra_col,'update',$where);

		if($contractorInsert)
		{
		    echo "<script>myAlert('Insert @#@ Data has been inserted successfully.@#@success')</script>";
		    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
            $password = substr( str_shuffle( $chars ), 0, 10 );
            // Encrypt password
            //$password = password_hash($password, PASSWORD_ARGON2I);
            $email =$_POST['email'];
            $name = $_POST['name'];
            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Please login with given credentials.";
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
                        Bryanston Logistics has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using <a href="https://drivaar.com/contractorAdmin/login.php" target="_blank">https://drivaar.com/contractorAdmin/login.php</a>. Make sure to save this email so that you can access this link later.<br><u><b>Please verify login with this credentials : </b></u>Username: '.$email.' Password: '.$password.'.<br>
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
            require_once('../phpmailer/class.phpmailer.php');
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
                  if($password)
                  {
                        $valusc[0]['password'] = $password;
                        $col = array('password');
                        $where = 'id ='.$cid;
                        $update = $mysql -> update('tbl_contractor',$valusc,$col,'update',$where);
                  }   
            } 
            catch (phpmailerException $e) 
            {
              	//echo $e->errorMessage(); exit;
              	echo "<script>myAlert('Insert Error @#@ ".$mail->ErrorInfo.".@#@danger')</script>";
            } 
            catch (Exception $e) 
            {
              	//echo $e->getMessage();  exit;   
            } 
		}
		else
		{
		    echo "<script>myAlert('Insert Error @#@ Data can not been inserted.@#@danger')</script>";
		}
		$mysql -> dbDisConnect();
		header("Location: thankyou.php?name=$name");
	}
}
$mysql = new Mysql();
$mysql -> dbConnect();
$cntquery = "SELECT * FROM `tbl_country` WHERE `isdelete`=0 AND `isactive`=0";
$cntrow =  $mysql -> selectFreeRun($cntquery);
$country ="";
while($cntresult = mysqli_fetch_array($cntrow))
{
	$selc="";
	if($cntresult['id']==80)
	{
		$selc="selected";
	}
    $country .= "<option $selc value='".$cntresult['id']."'>".$cntresult['name']."</option>";
}
$mysql -> dbDisconnect();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	 <link rel="stylesheet" type="text/css" href="style.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
</head>
<body>

<!-- multistep form -->
<form id="msform" method="post" name="msform" action="" enctype="multipart/form-data">
 <?php 

 if (isset($_GET['error'])) 
 	{ ?>
     	<p class="error"><?php echo $_GET['error']; ?></p>
     <?php 
    } ?>
    <?php if (isset($_GET['success'])) 
    { ?>
        <p class="success"><?php echo $_GET['success']; ?></p>
      <?php 
    } ?>
<h1>Contractor Registration</h1>
<div class="container chkrequire" id="chkrequire"></div><br>
<ul id="progressbar">
        <li class="active">Personal Information</li>
        <li>ID/Passport</li>
        <li>Current Address</li>
        <li>ID/Passport</li>
		<li>Driving Licence</li>
		<li>Work Permit</li>
		<li>Alcohole Test</li>
		<li>Bank Details</li>
</ul>
<fieldset>
	<h5>Personal Information</h5>
		<div class="form-group">
			<label for="">First Name:</label>
			<input type="text" name="name" placeholder="First Name" class="form-control fieldset_1" value="<?php echo $ciresult['name']; ?>" required>
		</div>

		<div class="form-group">
			<label for="">Date Of Birth:</label>
			<input type="date" name="dob" placeholder="may 8, 1991" class="form-control fieldset_1" value="<?php echo $ciresult['dob']; ?>" required>
		</div>

		<div class="row">
			<div class="col form-group">
				<label for="">Town of Birth:</label>
				<input type="text" name="birth_town" placeholder="Town" class="form-control fieldset_1" value="<?php echo $ciresult['birth_town']; ?>" required>
			</div>
			
			<div class="col form-group">
				<label for="">Country of Birth:</label>
				<select class="custom-select myinput fieldset_1" name="birth_country" id="birth_country" required>
					  <?php
	                      echo $country;
                      ?>
				</select>
			</div>
		</div><br>

		<div class="box">
			<h5>Contact Information:</h5>
			<div class="row">
				<div class="col form-group">
					<label for="">Your E-mail Address:</label>
					<input type="email" name="email" placeholder="@" class="form-control fieldset_1" value="<?php echo $ciresult['email']; ?>"  required>
					<small>it will be used to access the platform</small>
				</div>
				
				<div class=" col form-group">
					<label for="">Your Phone Number:</label>
					<input type="number" name="contact" placeholder="Tel." class="form-control fieldset_1" value="<?php echo $ciresult['contact']; ?>" required>
					<small>Number format: +44 xxxx xxxxxx</small>
				</div>
			</div>
	    </div><br>

		<div class="box">
			<h5>Kin/Emergency Contact Details</h5>
			<div class="row">
				<div class="col form-group">
					<label for="">Name:</label>
					<input type="text" name="emegency_name" class="form-control fieldset_1" value="<?php echo $ciresult['emegency_name']; ?>" required>
				</div>

				<div class="col form-group">
					<label for="">Phone Number:</label>
				    <input type="number" name="emegency_phone" class="form-control fieldset_1" value="<?php echo $ciresult['emegency_phone']; ?>" required>
				</div>
			</div>
		</div>

        <input type="button" name="next" class="next action-button" id="fieldset_1" value="Continue"/>
</fieldset>

<fieldset>
	<h5>National Insurance Details</h5>							
	<div class="row">
		<div class="col form-group">
			<label for="">National Insurance Number:</label>
			<input type="text" name="ni_number" class="form-control fieldset_2" value="<?php echo $ciresult['ni_number']; ?>" required>
		</div>
        <div class="col form-group">
			<label for="">UTR Number:</label>
			<input type="text" name="utr" class="form-control" value="<?php echo $ciresult['utr']; ?>">
		</div>
	</div>

	<div class="row">
		<div class="col form-group">
			<label for=""><b>National Insurance Photo</b></label><br>
			<input type="file" id="national_insurancefile" name="national_insurancefile" class="fieldset_2" required/>
	        <!-- <label for="upload" >Select file(s)...</label> -->
		</div>
	</div>
	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_2"  value="Continue" />
</fieldset>

<fieldset>
	<h5>Your Current Address</h5>
    <small>Proof of current Address Required i.e utility bill/bank statement that is dated in last 3 months.</small>
	<div class="form-group">
		<label for="">Street Address</label>
		<input type="text" name="address" class="form-control fieldset_3" value="<?php echo $ciresult['address']; ?>" required>
	</div>
	
	<div class="row">
		<div class="col form-group">
			<label for="">Town/City</label>
			<input type="text" name="city" class="form-control fieldset_3" value="<?php echo $ciresult['city']; ?>" required>	
		</div>
		
		<div class="col form-group">
			<label for="">Country/District</label>
			<input type="text" name="state" class="form-control fieldset_3" value="<?php echo $ciresult['state']; ?>" required>
		</div>
	</div>

	<div class="row">
		<div class="col form-group">
			<label for="">Postal Code:</label>
			<input type="text" name="postcode" class="form-control fieldset_3" value="<?php echo $ciresult['postcode']; ?>" required>
		</div>
		<div class=" col form-group">
			<label for="">Country:</label>
			<select class="custom-select myinput fieldset_3" name="country" id="country" required>
				<?php
	                  echo $country;
	              ?>
			</select>
		</div>	
	</div>

	<div class="form-group">
		<label for=""><b>Proof of Address</b></label><br>
		<input type="file" id="address_proof_file" name="address_proof_file" class="fieldset_3" required/>
		<!-- <label for="upload">Select file(s)...</label> -->
	</div>

	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_3" value="Continue" />
</fieldset>

<fieldset>
	<h5>ID/Passport Details</h5>
	<div class="row">	
		<div class="col form-group">
			<label for="">Passport Number:</label>
			<input type="text" name="passport_number" class="form-control fieldset_4" value="<?php echo $ciresult['passport_number']; ?>" required>	</div>
	</div>

	<div class="row">
		<div class="col form-group">
			<label for="">Nationality:</label>
			<select class="custom-select myinput fieldset_4" name="passport_nationality" id="passport_nationality" required>
				<?php
	                      echo $country;
                      ?>
			</select>			
		</div>
		<div class="col form-group">
			<label for="">Country of Issue:</label>
			<select class="custom-select myinput fieldset_4" name="passport_country" id="passport_country" required>
				<?php
	                      echo $country;
                      ?>
			</select>
		</div>
	</div>

	<div class="row">
		<div class=" col form-group">
			<label for="">Issue Date:</label>
			<input type="date" name="passport_issuedate" class="form-control fieldset_4" value="<?php echo $ciresult['passport_issuedate']; ?>" max ="<?php echo date('Y-m-d'); ?>" required>
		</div>
		<div class=" col form-group">
			<label for="">Expiry Date:</label>
			<input type="date" name="passport_expirydate" class="form-control fieldset_4" value="<?php echo $ciresult['passport_expirydate']; ?>" min="<?php echo date('Y-m-d'); ?>" required>
		</div>
	</div>

	<div class="form-group">
		<label for=""><b>Passport Inside Photo</b></label><br>
		<input type="file" id="passport_photo"  name="passport_photo" class="fieldset_4" required/>
		<!-- <label for="upload">Select file(s)...</label> -->
	</div>
	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_4" value="Continue" />
</fieldset>

<fieldset>
	<h5>Driver Licence</h5>
	<div class="form-group">
		<label for="">Driver Licence Number:</label>
		<input type="text" name="drivinglicence_number" class="form-control fieldset_5" value="<?php echo $ciresult['drivinglicence_number']; ?>" required>
	</div>

	<div class="row">
		<div class="col form-group">
			<label for="">Expiry Date:</label>
			<input type="date" name="drivinglicence_expiry" class="form-control fieldset_5" value="<?php echo $ciresult['drivinglicence_expiry']; ?>" min="<?php echo date('Y-m-d'); ?>" required>
		</div>
		
		<div class=" col form-group">
			<label for="">Issue Country:</label>
			<select class="custom-select myinput fieldset_5" name="drivinglicence_country" id="drivinglicence_country" required>
				<?php
	                      echo $country;
                      ?>
			</select>
		</div>
	</div>
	
	<div class="row">
		<div class="col form-group">
			<label for=""><b>Driving Licence Front</b></label><br>
			<input type="file" id="driving_lic_fnt"  name="driving_lic_fnt" class="fieldset_5" required/>
			<!-- <label for="upload">Select file(s)...</label> -->
		</div>
		<div class="col form-group">
			<label for=""><b>Driving Licence Back</b></label><br>
			<input type="file" id="driving_lic_back"  name="driving_lic_back" class="fieldset_5" required/>
			<!-- <label for="upload">Select file(s)...</label> -->
		</div>
	</div>

	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_5" value="Continue" />
</fieldset>

<fieldset>
	<h5>Work Permit</h5>
	<div class="row">
		<div class="col form-group">
			<label for="">Permit Number:</label>
			<input type="text" name="work_permit_no" class="form-control" value="<?php echo $ciresult['work_permit_no']; ?>">
			<small>Leave empty if passport is your permit to work</small>
		</div>
		
		<div class="col form-group">
			<label for="">Expiry Date:</label>
			<input type="date" name="permit_expiry" class="form-control" value="<?php echo $ciresult['permit_expiry']; ?>" min="<?php echo date('Y-m-d'); ?>">
		</div>
	</div>
	
	<div class="row">
		<div class="col form-group">
			<label for=""><b>Work Permit Front</b></label><br>
			<input type="file" id="work_permit_frt"  name="work_permit_frt"/>
			<!-- <label for="upload">Select file(s)...</label> -->
		</div>
		<div class="col form-group">
			<label for=""><b>Work Permit Back</b></label><br>
			<input type="file" id="work_permit_back"  name="work_permit_back"/>
			<!-- <label for="upload">Select file(s)...</label> -->
		</div>
	</div>

	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_6" value="Continue" />
</fieldset>

<fieldset>
	<h5>Drug and Alcohole Test Photo</h5>
	<div class="form-group">
		<label for=""><b>Photo of Drug and Alcohole Test</b></label><br>
		<input type="file" id="drug_test"  name="drug_test"/>
		<!-- <label for="upload">Select file(s)...</label> -->
	</div>
	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<input type="button" name="next" class="next action-button" id="fieldset_7" value="Continue" />
</fieldset>

<fieldset>
	<h5>Bank Details</h5>
	<div class="form-group">
		<label for="">Name of the Bank:</label>
		<input type="text" name="bank_name" class="form-control fieldset_8" value="<?php echo $ciresult['bank_name']; ?>" required>
	</div>
	<div class="row">
		<div class="col form-group">
			<label for="">Sort Code:</label>
			<input type="text" name="sort_code" class="form-control fieldset_8" value="<?php echo $ciresult['sort_code']; ?>" required>	
		</div>
		<div class="col form-group">
			<label for="">Account Number:</label>
			<input type="text" name="account_number" class="form-control fieldset_8" value="<?php echo $ciresult['account_number']; ?>" required>
		</div>
	</div>
	<div class="form-group">
		<label for="">Account Name:</label>
		<input type="text" name="bank_account_name" class="form-control fieldset_8" value="<?php echo $ciresult['bank_account_name']; ?>" required>
	</div>
	<input type="button" name="previous" class="previous action-button" value="Go Back" />
	<button type="submit" name="insert" class="next action-button" id="submit">Finish</button>
</fieldset>

<fieldset>
	<h5>Thank You <?php echo $ciresult['name']; ?></h5>
	<p>You have now provided all necessary information and uploaded<br> copies of your documents(ID/Passport, Driver Licence, Bank<br> Statement, Visa).<br><br>
		when you press <b>FINISH</b> we will sent you few Forms for background<br> check and your Service Contract that you need to sign via e-mail. <br><br>
		This will be the <u>last</u> step in the Onboarding Process.<br><br>
		Thank you for your time. 		<img src="data:image/jpeg;base64,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" alt="" width="30px" style="background-color: white;">

	</p>
	
</fieldset>

</form>

	 <!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js" type="text/javascript"></script>
<!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> -->
<script src="script.js"></script>

<script>
	$('INPUT[type="file"]').change(function () {
	    var ext = this.value.match(/\.(.+)$/)[1];
	    switch (ext) {
	        case 'jpg':
	        case 'jpeg':
	        case 'png':
	        case 'pdf':
	        case 'JPG':
	        case 'JPEG':
	        case 'PNG':
	        case 'PDF':
	            //$('#uploadButton').attr('disabled', false);
	            var a = (this.files[0].size);
		        if(a > 7000000) {
		            alert('Sorry, Please select file size less than 5 MB.');
		            this.value = '';
		        };
	            break;
	        default:
	            alert('Sorry, only PDF, JPG, JPEG, & PNG files are allowed to upload.');
	            this.value = '';
	    }
	});
</script>
</body>
</html>