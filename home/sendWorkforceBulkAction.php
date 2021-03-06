<?php
		include 'DB/config.php';
		include("invoiceAmountClass.php");

        function WorkforceInvoiceTotal($id)
        {
            $mysql = new Mysql();
            $mysql->dbConnect();
            $cntquery = "SELECT c.*,ci.*,ci.cid as workforceid FROM `tbl_user` c 
            INNER JOIN `tbl_contractorinvoice` ci ON ci.cid=c.id WHERE ci.invoice_no='$id'";
            // echo $cntquery;
            $cntrow =  $mysql->selectFreeRun($cntquery);
            $result1 = mysqli_fetch_array($cntrow);

            $totalamount = 0;
            $vatFlag = 0;
            $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ci.`vat` as civat,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat`
                    FROM `tbl_contractorinvoice` ci
                    INNER JOIN `tbl_workforcetimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.wid=ci.cid
                    INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
                    WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=" . $id . " ORDER BY p.`type` ASC";
            $tblrow =  $mysql->selectFreeRun($tblquery);
            $finaltotal = 0;
            $totalnet = 0;
            $totalvat = 0;
            while ($tblresult = mysqli_fetch_array($tblrow)) {
                $vatFlag = $tblresult['civat'];
                $type = $tblresult['type'];

                $net = $tblresult['amount'] * $tblresult['value'];
                $vat = 0;
                if ($vatFlag == 1) {
                    $vat = ($net * $tblresult['vat']) / 100;
                }
                $neg = "";
                if ($type == 3) {
                    $net = -$net;
                    $vat = -$vat;
                    $neg = "-";
                }
                $total = $net + $vat;
                $finaltotal += $total;
            }

            $paidamount = 0;
            $singleamount = 0;
            $resn = array();
            $amnt123 = array();
            $tquery = "SELECT c.*,l.`amount` as totalamount,l.`no_of_instal`,c.reason  
            FROM `tbl_workforcepayment` c INNER JOIN `tbl_workforcelend` l ON l.`id`=c.`loan_id` 
            WHERE c.`wid`=" . $result1['workforceid'] . " AND c.`week_no`=" . $result1['week_no'] . " AND c.`isdelete`=0";
            $trow =  $mysql->selectFreeRun($tquery);
            while ($tresult = mysqli_fetch_array($trow)) {
                $resn[] = $tresult['reason'];
                $amnt123[] = $tresult['amount'];
                $paidamount += $tresult['amount'];
                $singleamount += $tresult['totalamount'] / $tresult['no_of_instal'];
            }
            $totalamount = ($finaltotal - $paidamount);
            $mysql->dbDisConnect();
            return  $totalamount;
        }

		$mysql = new Mysql();
		$db = $mysql -> dbConnect();
		$query="SELECT DISTINCT ci.id as ciid, ci.*,c.name as cname,c.email as cemail 
        FROM `tbl_contractorinvoice` ci 
        INNER JOIN `tbl_user` c ON c.id=ci.cid 
        WHERE ci.`isdelete`=0 AND c.`isdelete`=0 AND ci.`invoice_no`='".$argv[1]."'";
	    $typerow =  $mysql -> selectFreeRun($query);
	    $cnt = 0;
	    $sent = "";
	    $failed = "";
	    while($typeresult = mysqli_fetch_array($typerow))
	    {
	    	$invc = new InvoiceAmountClass();
            $totalshow = WorkforceInvoiceTotal($typeresult['id']);
        	//$totalshow = $invc->ContractorInvoiceTotal($typeresult['invoice_no']);
        	$b64 = base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]);
	        $invkey=base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]."#".$b64);
	        $action = "<a href='http://drivaar.com/home/workforceInvoiceMail.php?invkey=".$invkey."' target='_blank' style='padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #9f5c5c;text-decoration: none;font-weight:bold;display: inline-block;'>Click here to view Invoice</a>";
	        $email = $typeresult['cemail'];
            $name = $typeresult['cname'];
            $frmDate = date("d M, Y",strtotime($typeresult['from_date']));
            $toDate = date("d M, Y",strtotime($typeresult['to_date']));
            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            // $emlusername='bryanstoninvoices@gmail.com';
            // $emlpassword='BRHL@123#';
            $emltitle = 'Invoice @ Drivaar';
            $subject = "Invoice for week no ".$typeresult["week_no"];
            $body = 'Invoice # '.$typeresult['invoice_no'];
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
                        Your invoice for week number '.$typeresult["week_no"].' (Dated From '. $frmDate.' to '.$toDate.') with invoice id '.$typeresult['invoice_no'].' has been generated. Invoice amount is ?? '.$totalshow.'
                    </p>
                     <p>
                        You can login to your account and can visit the MyInvoice page to see the invoices. Also you can click on the following button to review or print your invoice.<br>'.$action.'
                     </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
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
                // $title = 'Insert';
                // $message = 'Email sent to User And Data has been inserted.';
                // $sent .= $typeresult['invoice_no']." --- ";
                $cnt++;
            } 
            catch (phpmailerException $e) 
            {
                // $status=0;
                // $title = 'Email not Sent';
                // $message = 'Failed to sent mail.';
                // $failed .= "<br>".$typeresult['invoice_no']." # ".$e;
                exit;
            } 
            catch (Exception $e) 
            {
            	// echo $e;
             //    $status=0;
             //    $title = 'Email not Sent';
             //    $message = 'Failed to sent mail.';
             //    $failed .= "<br>".$typeresult['invoice_no']." # ".$e;
                exit;   
            }
	    }
      $mysql->dbDisConnect();
?>