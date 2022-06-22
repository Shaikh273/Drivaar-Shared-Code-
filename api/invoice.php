<?php
include 'DB/config.php';
include("../home/invoiceAmountClass.php");
date_default_timezone_set('Europe/London');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$action=$_POST['action'];
$auth_key=$_POST['auth_key'];
$cid=$_POST['cid'];
$status=0;
$todaydate =  date('Y-m-d H:i:s');

function getStartAndEndDate($week, $year) 
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

if(isset($action) && isset($auth_key)  && ($action=='invoice'))
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $sql = "SELECT * FROM `tbl_contractor` WHERE `key`='$auth_key' AND `isdelete`=0";
    //$sql = "SELECT * FROM `tbl_contractor` WHERE `id`='$cid' AND `isdelete`=0";
    $userrow = $mysql -> selectFreeRun($sql);
    if($row = mysqli_fetch_array($userrow))
    {
        if($row['isactive']==0)
        {
            $query="SELECT ci.*,ist.contractorView,DATE_FORMAT(ci.`duedate`,'%D %M%, %Y') as duedate 
            from tbl_contractorinvoice ci  
            INNER JOIN tbl_invoicestatus ist ON ist.id=ci.status_id 
            WHERE ci.isdelete=0 AND (ci.status_id=4 OR ci.status_id=7 OR ci.status_id=8) 
            AND ci.cid=".$row['id']." order by ci.id ASC";
            
            $typerow =  $mysql -> selectFreeRun($query);
            $todaydate = date('Y-m-d');
            $data = array();
        
            while($typeresult = mysqli_fetch_array($typerow))
            {

                $userid = $typeresult['id'];
                
                $date1 = $typeresult["insert_date"];
                $date2 = date('Y-m-d H:i:s');
                $datetime = new DateTime(date("Y-m-d H:i:s"));
                $datetime1 = new DateTime($date1);
                $diff = $datetime->diff($datetime1);
                $year = $diff->y;
                $month = 12 * $diff->y + $diff->m;
                $day = $diff->days;
                $hours = $diff->h + ($diff->days * 24);
                $second = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
                $minute = (($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i);

                if($hours > 48)
                {
                    $status1 = "Timed Out!";
                    
                }
                else
                {
                    $status1 = "Dispute"; 
                }

                $vat = '';
                if($typeresult['vat']==1)
                {
                    $vat ='Yes';
                }

                $invoicestatus = $typeresult['status_id'];
                
                $week_array = getStartAndEndDate($typeresult['week_no'],$typeresult['weekyear']);

                $fromdate = date("d M, Y",strtotime($week_array['week_start']));
                $todate = date("d M, Y",strtotime($week_array['week_end']));

                $stquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`= 0 AND `isactive`= 0 AND `id`=".$invoicestatus;
             
                $strow =  $mysql -> selectFreeRun($stquery);
                $stresult = mysqli_fetch_array($strow);

                $invc = new InvoiceAmountClass();
                $totalshow = $invc->ContractorInvoiceTotal($typeresult['invoice_no']);
                $totalshow = $totalshow['finaltotal'];

                $statusname = strtoupper($stresult['name']);
                if($typeresult['status_id']==4)
                {
                    $due = $typeresult['duedate'];

                }
                else
                {
                    if($todaydate>=$typeresult['duedate'])
                    {
                       // $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> '.$typeresult['duedate'].'</div>';
                        $due = $typeresult['duedate'];
                    }
                    else
                    {
                       // $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> '.$typeresult['duedate'].'</b></div>';
                        $due = $typeresult['duedate'];
                    }
                    
                }
                $b64 = base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]);
                $invkey=base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]."#".$b64);
                // $action = "<a href='../home/contractor_invoice.php?invkey=".$invkey."' target='_blank' class='delete'><span><b>View</b></span></a>
                //             &nbsp"; 
            
                $data[] = [
                            'total'=>$totalshow,
                            'status'=>$statusname,
                            'period'=>$typeresult['week_no'].' - '.$fromdate.' >> '.$todate,
                            'invoice_no'=>$typeresult['invoice_no'],
                            'due'=>$due,
                            'dispute'=>$status1,
                            'View_id'=>$invkey
                        ];
            }
            
            if($typerow)
            {
                $status=1;
                $success = "Invoice get successfully";
                $data1 =  $data;
            }
            else
            {
                $status=0;
                $error = "Invoice can not generate.";
                $errorcode = "106";
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
        $error = "Authentication filed.";
        $errorcode = "101";
    }

    if($status==1)
    {
        $array = array("success" => $success,"data" => $data1);
    }
    else
    {
    	$array = array("errorcode" =>$errorcode,"error"=>$error);
    }
	echo json_encode(array('status' => $status,'data' => $array));
}
else
{
    echo 'error';
}
?>