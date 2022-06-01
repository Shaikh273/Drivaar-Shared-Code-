<?php
include 'DB/config.php';
include("invoiceAmountClass.php");
date_default_timezone_set('Europe/London');
if(!isset($_SESSION)) 
{
    session_start();
}
function generate_Uniqid()
{
    $invoiceno = str_pad(rand(000000,999999), 10, "0", STR_PAD_LEFT);
    if($invoiceno>0)
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $invoiceqry = "SELECT * FROM `tbl_contractorinvoice` WHERE `invoice_no`='$invoiceno' AND `isdelete`=0 AND `isactive`=0";
        $invoicerow =  $mysql -> selectFreeRun($invoiceqry);
        $invresult = mysqli_fetch_array($invoicerow);
        if($invresult['invoice_no']>0)
        {
            $InvoiceId = generate_Uniqid();
        }
        else
        {
            $InvoiceId = $invoiceno;
        }
        $mysql -> dbConnect();
    }
    return $InvoiceId;
}
function getAlphaCode($n,$pad)
{
        $alphabet   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $n = (int)$n;
        if($n <= 26){
            $alpha =  $alphabet[$n-1];
        } elseif($n > 26) {
            $dividend   = ($n);
            $alpha      = '';
            $modulo;
            while($dividend > 0){
                $modulo     = ($dividend - 1) % 26;
                $alpha      = $alphabet[$modulo].$alpha;
                $dividend   = floor((($dividend - $modulo) / 26));
            }
        }
        return str_pad($alpha,$pad,"0",STR_PAD_LEFT);
}
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
    $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ci.`vat` as civat,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat`,u.`vat_number` as vat_number1   
            FROM `tbl_contractorinvoice` ci
            INNER JOIN `tbl_workforcetimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` AND ct.`wid`=ci.`cid`
            INNER JOIN `tbl_user` u ON u.`id`=ci.`cid`
            INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid` 
            WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=" . $id . " ORDER BY p.`type` ASC";
    $tblrow =  $mysql->selectFreeRun($tblquery);
    $finaltotal = 0;
    $totalnet = 0;
    $totalvat = 0;
    while ($tblresult = mysqli_fetch_array($tblrow)) {
        if(!empty($tblresult['vat_number1']))
        {
           $vatFlag = 1;
        }else
        {
            $vatFlag = 0;
        }
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

if(isset($_POST['action']) && $_POST['action'] == 'ownerform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_vehicleowner',$valus);
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

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleowner',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddworkforcedepotDetailForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $mysql = new Mysql();

    $mysql -> dbConnect();
    $valus=array();
    $cat_result= array();
    $i = 0;
    $mc=0;
    $makecol = array();
    if(!empty($_POST['name']))
        {
            $valus[0]['name'] = $_POST['name'];
            $makecol[$mc]='name';
            $mc++;
        }
    if(!empty($_POST['supervisor']))
    {
        $supervisorIds = implode(",", $_POST['supervisor']);
        $valus[0]['supervisor'] = $supervisorIds;
        $makecol[$mc]='supervisor';
        $mc++;
        foreach ($_POST['supervisor'] as $supervisorid) 
        {
            $category =  $mysql -> selectWhere('tbl_user','id','=',$supervisorid,'int');
            $catresult = mysqli_fetch_array($category);
            $cat_result[] = $catresult['name'];
            $i++;
        }
        $valus[0]['supervisor_type'] = implode(",", $cat_result);
        $makecol[$mc]='supervisor_type';
        $mc++;
    }

    if(!empty($_POST['reference']))
        {
            $valus[0]['reference'] = $_POST['reference'];
            $makecol[$mc]='reference';
            $mc++;
        }
    if(!empty($_POST['isactive']))
        {
            $valus[0]['isactive'] = $_POST['isactive'];
            $makecol[$mc]='isactive';
            $mc++;
        }

    if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $makecol[$mc]='update_date';
        $mc++;

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_depot',$valus,$makecol,'update',$where);

        if($statuupdate)
        {
            $status=1;
            $title = 'Insert';
            $message = 'Data has been insert successfully.';
        }
        else
        {
            $status=0;
            $title = 'Insert Error';
            $message = 'Data can not been inserted.';
        }
        $name = 'Insert';
        

    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
        $name = 'Insert';
    }
   $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'vehiclestatusform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['name'] = $_POST['name'];
    $valus[0]['colorcode']=$_POST['colorcode'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehiclestatus',$valus);
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

        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclestatus',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleSupplierform')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['name'] = $_POST['name'];
    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $statusinsert = $mysql -> insert('tbl_vehiclesupplier',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;
        $makecol = array('name','update_date');
        $where = 'id ='. $_POST['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $statuupdate = $mysql -> update('tbl_vehiclesupplier',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleTypeform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehicletype',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicletype',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleGroupform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = 'Update';
    $valus[0]['name'] = $_POST['name'];

    $valus[0]['rentper_day']=$_POST['rentper_day'];

    $valus[0]['insuranceper_day']=$_POST['insuranceper_day'];

    $valus[0]['total']=$_POST['rentper_day']+$_POST['insuranceper_day'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehiclegroup',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','rentper_day','insuranceper_day','total','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclegroup',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleMakeform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['description']=$_POST['description'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehiclemake',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclemake',$valus,$makecol,'update',$where);

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

    $response['status'] = $status;$response['name'] = $name;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleModelform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['description']=$_POST['description'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehiclemodel',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclemodel',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExtraform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehicleextra',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleextra',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDetailsform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['status'] = $_POST['status'];
    $valus[0]['vin_number'] = $_POST['vin_number'];
    $valus[0]['supplier_id'] = $_POST['supplier'];
    $valus[0]['group_id'] = $_POST['group'];
    $valus[0]['depot_id'] = $_POST['depot'];
    $valus[0]['make_id'] = $_POST['make'];
    $valus[0]['model_id'] = $_POST['model'];
    $valus[0]['type_id'] = $_POST['vehicle_type'];
    $valus[0]['color'] = $_POST['color'];
    $valus[0]['fuel_type'] = $_POST['fuel_type'];
    $valus[0]['options'] = implode(",", $_POST['options']);
    $valus[0]['rental_condition'] = $_POST['rental_condition'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus1[0]['vehicle_id'] = $_POST['id'];
    $valus1[0]['insurance'] = $_POST['insurance'];
    $valus1[0]['goods_in_transit'] = $_POST['goods_in_transit'];
    $valus1[0]['public_liability'] = $_POST['public_liability'];

    $detailscol = array('status','vin_number','supplier_id','group_id','depot_id','make_id','model_id','type_id','color','fuel_type','options','rental_condition','update_date');
    $where = 'id ='.$_POST['id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $insurance = "SELECT * FROM `tbl_addvehicleinsurance` WHERE `vehicle_id`= ".$_POST['id']." AND `isdelete` = 0";
    $strow =  $mysql -> selectFreeRun($insurance);
    $ownerresult = mysqli_fetch_array($strow);

    if($ownerresult['id'])
    {
        $valus1[0]['update_date'] = date('Y-m-d H:i:s');
        $inscol = array('insurance','goods_in_transit','public_liability','update_date');
        $inswhere = 'id ='.$ownerresult['id'];
        $update = $mysql -> update('tbl_addvehicleinsurance',$valus1,$inscol,'update',$inswhere);
        $name = 'Update';
    }
    else
    {
        $valus1[0]['insert_date'] = date('Y-m-d H:i:s');
        $insert = $mysql -> insert('tbl_addvehicleinsurance',$valus1);
        $name = 'Insert';
    }

    $detailsupdate = $mysql -> update('tbl_vehicles',$valus,$detailscol,'update',$where);
    if($detailsupdate)
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Vehicleform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $vid='';

    $reg_number = $_POST['registration_number'];

    if($reg_number)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://driver-vehicle-licensing.api.gov.uk/vehicle-enquiry/v1/vehicles',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{"registrationNumber":"'.$reg_number.'"}',
          CURLOPT_HTTPHEADER => array(
            'x-api-key: auOqUGGMw33GvCZk3kGFD1oNxsknqWUt1NvR4oGS',
            'Content-Type: application/json',
          ),
        ));

        $response1 = curl_exec($curl);
        curl_close($curl);
        $resData = json_decode($response1,true);
        //print_r($resData);
        
        if(is_array($resData["errors"]))
        {
             $status=0;
             $title = $resData["errors"][0]["title"];
             $message = $resData["errors"][0]["detail"];
             $vid='';
        }
        else
        {

            $valus[0]['userid'] = $_POST['userid'];
            $valus[0]['registration_number'] = $_POST['registration_number'];
            
            $mysql = new Mysql();
            $mysql -> dbConnect();


            $make =  $resData["make"];
            if($make)
            {
                $query1 = "SELECT * FROM `tbl_vehiclemake` WHERE `name` LIKE ('".$make."') AND `isactive`=0 AND `isdelete`=0";
                $row1 =  $mysql -> selectFreeRun($query1);
                if($result = mysqli_fetch_array($row1))
                {
                    $valus[0]['make_id']=$result['id'];

                }
                else
                {
                    $valusm[0]['name']=$make;
                    $valusm[0]['insert_date'] = date('Y-m-d H:i:s');
                    $makeinsert = $mysql -> insertre('tbl_vehiclemake',$valusm);
                    $valus[0]['make_id']=$makeinsert;
                }
            }
            $valus[0]['color'] = $resData["colour"];
            $valus[0]['fuel_type'] = $resData["fuelType"];
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $statusinsert = $mysql -> insertre('tbl_vehicles',$valus);
            if($statusinsert)
            {
                $status=1;
                $title = 'Insert';
                $message = 'Data has been inserted successfully.';
                $vid=$statusinsert;
            }
            else
            {
                $status=0;
                $title = 'Insert Error';
                $message = 'Data can not been inserted.';
                $vid='';
            }

            $mysql -> dbDisConnect();
        }

    }
   
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['vid'] = $vid;
    $response['response'] = $response1;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleStatusData')
{

    header("content-Type: application/json");
    $status=0;

    $id = $_POST['id'];
    $valus[0]['status'] = $_POST['status'];

    $usercol = array('status');

    $where = 'id ='. $id;

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_vehicles',$valus,$usercol,'update',$where);

    if($paymentdata)
    {
        $status = 1;
    }
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleRentalInfoDetails')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';

    $id = $_POST['rentid'];
    $valus[0]['startdate'] = $_POST['startdate'];
    $valus[0]['enddate'] = $_POST['enddate'];
    $valus[0]['rentpriceperday'] = $_POST['rentpriceperday'];
   
    $mysql = new Mysql();
    $mysql -> dbConnect();
    if($id)
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $usercol = array('startdate','enddate','rentpriceperday');

        $where = 'id ='.$id;

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statusinsert =  $mysql -> update('tbl_vehiclerentagreement',$valus,$usercol,'update',$where);
    }
    else 
    {
        $valus[0]['vehicle_id'] = $_POST['id'];
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehiclerentagreement',$valus);
    }
    
    if($statusinsert)
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

    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleRenewalTypeform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_vehiclerenewaltype',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclerenewaltype',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehiclerenewalform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['renewal_id'] = $_POST['renewaltype'];
        $query = "SELECT * FROM `tbl_vehiclerenewaltype` WHERE `id`=".$_POST['renewaltype'];
        $userrow =  $mysql -> selectFreeRun($query);
        $renewresult = mysqli_fetch_array($userrow);
    $valus[0]['renewal_type'] = $renewresult['name'];
    $valus[0]['duedate'] = $_POST['duedate'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_addvehiclerenewaltype',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_addvehiclerenewaltype',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'ChecklistForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehiclechecklist',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclechecklist',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleExpenseTypeForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleexpensetype',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleexpensetype',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WalletCategoryTypeForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_walletcategory',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_walletcategory',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleExpenseForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['label'] = $_POST['label'];
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['expense_id'] = $_POST['expensetype'];
        $query = "SELECT * FROM `tbl_vehicleexpensetype` WHERE `id`=".$_POST['expensetype'];
        $userrow =  $mysql -> selectFreeRun($query);
        $renewresult = mysqli_fetch_array($userrow);
    $valus[0]['expense_type'] = $renewresult['name'];
    $valus[0]['expensedate'] = $_POST['expensedate'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['amount'] = $_POST['amount'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleexpense',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('expense_id','label','expense_type','expensedate','amount','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleexpense',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleServiceTaskForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleservicetask',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleservicetask',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceHistoryForm')
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
    $valus[0]['completiondate'] = $_POST['completiondate'];
    $valus[0]['reference'] = $_POST['reference'];
    $valus[0]['servicetaskid'] = $_POST['servicetaskid'];
        $query = "SELECT * FROM `tbl_vehicleservicetask` WHERE `id`=".$_POST['servicetaskid'];
        $userrow =  $mysql -> selectFreeRun($query);
        $renewresult = mysqli_fetch_array($userrow);
    $valus[0]['servicetask'] = $renewresult['name'];
    $valus[0]['labour'] = $_POST['labour'];
    $valus[0]['parts'] = $_POST['parts'];
    $valus[0]['subtotal'] = $_POST['subtotal'];
    $valus[0]['description'] = $_POST['description'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleservicehistory',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleservicehistory',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehicleServiceReminderForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['odometer_interval'] = $_POST['odometer_interval'];
    $valus[0]['time_interval'] = $_POST['time_interval'];
    $valus[0]['type_interval'] = $_POST['type_interval'];
        $query1 = "SELECT * FROM `tbl_vehicletypereminder` WHERE `id`=".$_POST['type_interval'];
        $userrow1 =  $mysql -> selectFreeRun($query1);
        $renewresult1 = mysqli_fetch_array($userrow1);
    $valus[0]['type_intervalname'] = $renewresult1['name'];
    $valus[0]['servicetaskid'] = $_POST['servicetaskid'];
        $query = "SELECT * FROM `tbl_vehicleservicetask` WHERE `id`=".$_POST['servicetaskid'];
        $userrow =  $mysql -> selectFreeRun($query);
        $renewresult = mysqli_fetch_array($userrow);
    $valus[0]['servicetask'] = $renewresult['name'];
    $valus[0]['odometer_threshold'] = $_POST['odometer_threshold'];
    $valus[0]['time_threshold'] = $_POST['time_threshold'];
    $valus[0]['type_threshold'] = $_POST['type_threshold'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleservicereminder',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleservicereminder',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumenttypeForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['isexpiry_date'] = $_POST['isexpiry_date'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicledocumenttype',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','isexpiry_date','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicledocumenttype',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleContactForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $todaydate = date('Y-m-d');
       
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['email'] = $_POST['email'];
    $valus[0]['phone'] = $_POST['phone'];
    $valus[0]['street_address'] = $_POST['street_address'];
    $valus[0]['postcode'] = $_POST['postcode'];
    $valus[0]['city'] = $_POST['city'];
    $valus[0]['state'] = $_POST['state'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insertre('tbl_vehiclecontact',$valus);
        if($statusinsert>0)
        {

            $valusc[0]['cid'] = $statusinsert;
            $valusc[0]['invoice_no'] = '#'.generate_Uniqid();
            $valusc[0]['week_no'] = (int)date('W', strtotime($todaydate));
            $valusc[0]['from_date'] = $todaydate;
            $valusc[0]['to_date'] = $todaydate;
            $valusc[0]['weekyear'] = date('Y');
            $valusc[0]['status_id'] = 1;
            $valusc[0]['istype'] = 4;
            $valusc[0]['insert_date'] = date('Y-m-d H:i:s');

            $insert = $mysql -> insert('tbl_contractorinvoice',$valusc);

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
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclecontact',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleDocumentForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['type'] = $_POST['type'];
        $query1 = "SELECT * FROM `tbl_vehicledocumenttype` WHERE `id`=". $_POST['type'];
        $userrow1 =  $mysql -> selectFreeRun($query1);
        $renewresult1 = mysqli_fetch_array($userrow1);
    $valus[0]['typename'] = $renewresult1['name'];

    $valus[0]['name'] = $_POST['name'];
    $valus[0]['expiredate'] = $_POST['expiredate'];
    $file = $_FILES['file'];
    $errors= array();
    if($file)
    {
        $file_name = $_FILES['file']['name'];
        $file_size =$_FILES['file']['size'];
        $file_tmp =$_FILES['file']['tmp_name'];
        $file_type=$_FILES['file']['type'];
        $file_ext=strtolower(end(explode('.',$file_name)));
        $extensions= array("jpeg","jpg","png","pdf","doc","docx","txt","csv","ppt");
        $uploads_dir = 'uploads/vehicledocument/';
        $target = $_POST['vid'].basename( $_FILES['file']['name']); 
    }

    $valus[0]['file'] = $target;
    if(in_array($file_ext,$extensions)===false)
    {
         $errors[]="extension not allowed, please choose a valid file.";
         $status = 0;
         $title = 'Upload File Error';
         $message = 'Extension not allowed, please choose a valid file.';
    }
    if($file_size < 10000000)
    {
        $errors[]='Upload a valid file size';
        $status = 0;
        $title = 'File Size Error';
        $message = 'Upload a valid file size.';
    }
    
    if(empty($errors)==true)
    {
        move_uploaded_file($file_tmp,"$uploads_dir/$target");
        if(isset($_POST['insert']))
        {
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $statusinsert = $mysql -> insert('tbl_vehicledocument',$valus);
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
            $mysql -> dbDisConnect();
        }
        else if(isset($_POST['update']))
        {
            $valus[0]['update_date'] = date('Y-m-d H:i:s');

            $makecol = array('name','update_date');

            $where = 'id ='. $_POST['id'];

            $mysql = new Mysql();

            $mysql -> dbConnect();

            $statuupdate = $mysql -> update('tbl_vehicledocument',$valus,$makecol,'update',$where);

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
    }
    else
    {
        $status = 0;
        $title = 'File  Error';
        $message = 'Upload a valid file.';
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidenttypeForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicletypeaccident',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicletypeaccident',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleaccidentstage',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleaccidentstage',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleAccidentstageForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehicleaccidentstage',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicleaccidentstage',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleinsuranceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['insurance_company'] = $_POST['insurance_company'];
    $valus[0]['reference_number'] = $_POST['reference_number'];
    $valus[0]['startdate'] = $_POST['startdate'];
    $valus[0]['expirydate'] = $_POST['expirydate'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_vehiclefleetinsuarnce',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehiclefleetinsuarnce',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddVehiclefleetInsurance')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['insurance_id']= $_POST['id'];
    $valus[0]['vehicle_id']= $_POST['vid'];

    $expquery = "SELECT v.`id`,v.`registration_number`,vs.`name` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` WHERE v.`id`=".$_POST['vid'];
    $exprow =  $mysql -> selectFreeRun($expquery);
    $result = mysqli_fetch_array($exprow);
    $valus[0]['vehicle']=  $result['name']. ' ('. $result['registration_number'].')';

    if($_POST['id'] && $_POST['vid'])
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_addvehiclefleetinsurance',$valus);
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
        $mysql -> dbDisConnect();
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleWorkforceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $submitaction=$_POST['submitaction'];
        
    $valus[0]['roleid'] = $_POST['roleid'];

    $assignee = $mysql -> selectWhere('tbl_role','id','=',$_POST['roleid'],'int');
    $catresult = mysqli_fetch_array($assignee);
    $valus[0]['role_type'] = $catresult['role_type']; 
    $valus[0]['userid'] = $_POST['userid'];  
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['email'] = $_POST['email'];
    $valus[0]['contact'] = $_POST['phone'];

    if($submitaction == 'insert')
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insertre('tbl_user',$valus);
        if($statusinsert)
        {
            $status=1;
            //$title = 'Insert';
            $token = md5($valus[0]['email']).rand(10,9999);
            $where = "email = '".$valus[0]['email']."'";
            $valus[0]['reset_link_token'] = $token;
            $update = $mysql -> update('tbl_user', $valus, 'reset_link_token', 'update', $where);

            $link = "<a href='".$webroot."reset-password.php?key=".$statusinsert."&token=".$token."'>
            Click here To set password</a>";
            
            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Drivaar : Set Your Password";
            $body = 'Please verify it"s you.';

            $dataMsg = "
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
                    <div class='row content'>
                        <h2><p style='background-color:#03a9f3; text-align: center;color:#ffffff;'> Dear ".$valus[0]['name']."</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using ".$valus[0]['email'].". Make sure to save this email so that you can access this link later.<br>Set your Drivaar password <u>".$link."</u>.<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driver’s license, UTR number, NINO number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When you’ve completed these tasks, Drivaar will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br> <a href='#'><img height='50px' width='200px' src='./google-play.png'></a> <a href='#!'><img height='50px' width='200px' src='./app-store.png'></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Drivaar Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>";  
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
                  $mail->AddAddress($valus[0]['email'],$valus[0]['name']);
                  $mail->SetFrom($emlusername,$emltitle);
                  $mail->mailtype = 'html';
                  $mail->charset = 'iso-8859-1';
                  $mail->wordwrap = TRUE;
                  $mail->Subject = $subject;
                  $mail->AltBody = $body; 
                  $mail->MsgHTML($dataMsg);
                  $mail->Send();

                $valusu[0]['urldate'] = date('Y-m-d H:i:s');
                $col = array('urldate');
                $where = 'id ='.$statusinsert;
                $update = $mysql -> update('tbl_user',$valusu,$col,'update',$where);
                if($update)
                {
                    $status=1;
                    $title = 'Email Send';
                    $message = 'Email Message has been send successfully.';
                }
                else
                {
                    $status=0;
                    $title = 'Error';
                    $message = 'Email Message has not been send successfully.';
                }     
            } 
            catch (phpmailerException $e) 
            {
                $status=0;
                $title = 'Email not Send';
                $message = 'Failed to send mail.';
                exit;
            } 
            catch (Exception $e) 
            {
                $status=0;
                $title = 'Email not Send';
                $message = 'Failed to send mail.';
                exit;   
            }  
            //$message = 'Data has been inserted successfully.';
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
    else if($submitaction == 'update')
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceMetricForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['userid'] = $_POST['userid'];   
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['type'] = $_POST['type'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_workforcemartic',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforcemartic',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTaskForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['assignee'] = $_POST['assignee'];

    $assigne =  $mysql -> selectWhere('tbl_user','id','=',$_POST['assignee'],'int');
    $catresult = mysqli_fetch_array($assigne);

    $valus[0]['assignee_type'] = $catresult['name'];
    $valus[0]['duedate'] = $_POST['duedate'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_workforcetask',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforcetask',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddWorkforceDepotCustomber')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['depot_id'] = $_POST['did'];
    $valus[0]['customer'] = $_POST['customer'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statusinsert = $mysql -> insert('tbl_workforcedepotcustomer',$valus);
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddDepotTargetform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['depot_id'] = $_POST['did'];
    $valus[0]['metric'] = $_POST['metric'];
    $assigne =  $mysql -> selectWhere('tbl_workforcemartic','id','=',$_POST['metric'],'int');
    $matresult = mysqli_fetch_array($assigne);
    $valus[0]['metric_type'] =  $matresult['name'];
    $valus[0]['target'] = $_POST['target'];
    $valus[0]['threshold'] = $_POST['threshold'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_depottarget',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('metric','metric_type','target','threshold','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_depottarget',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddworkforceEditForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['roleid'] = $_POST['roleid'];
    $assignee =  $mysql -> selectWhere('tbl_role','id','=',$_POST['roleid'],'int');
    $catresult = mysqli_fetch_array($assignee);
    $valus[0]['role_type'] = $catresult['role_type'];   
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['email'] = $_POST['email'];
    $valus[0]['contact'] = $_POST['phone'];
    $valus[0]['invoice_email'] = $_POST['invoice_email'];

    $depotIds = implode(",", $_POST['depot']);
    $valus[0]['depot'] = $depotIds;
    $dpt_result= array();
    $where = array();
    $i = 0;


    foreach ($_POST['depot'] as $depotid) {
        $depot =  $mysql -> selectWhere('tbl_depot','id','=',$depotid,'int');
        $dptresult = mysqli_fetch_array($depot);
        $dpt_result[] = $dptresult['name'];

        $wvalus[$i]['userid'] = intval($_SESSION['userid']);
        $wvalus[$i]['wid'] = intval($_POST['id']);
        $wvalus[$i]['depot_id'] = intval($depotid);
        $wvalus[$i]['uniqid'] = $_POST['id'].'-'.$depotid;
        $wvalus[$i]['assign_date'] = date('Y-m-d H:i:s');
        $wvalus[$i]['insert_date'] = date('Y-m-d H:i:s');

        $where[] = $_SESSION['userid'].'-'.$_POST['id'].'-'.$depotid;
        $i++;
    }

    $uniqid = implode(",", $where);
    $selectqry = "SELECT * FROM `tbl_workforcedepotassign` WHERE `depot_id` NOT IN ('$depotIds') AND `isdelete`=0 AND `isactive`=0 AND `release_date` IS NULL AND `wid`=".$_POST['id'];
    $uniqresult =  $mysql -> selectFreeRun($selectqry);
    while($uniqrow = mysqli_fetch_array($uniqresult))
    {
        $uvalus[0]['uniqid'] = $uniqrow['uniqid'].'-'.date('Y-m-d H:i:s');
        $uvalus[0]['update_date'] = date('Y-m-d H:i:s');
        $uvalus[0]['release_date'] = date('Y-m-d H:i:s');
        $prcol = array('release_date','update_date');
        $where = 'id='.$uniqrow['id'];
        $update = $mysql -> update('tbl_workforcedepotassign',$uvalus,$prcol,'update',$where);
    }

    $extra = 'ON DUPLICATE KEY UPDATE `wid`='.$_POST['id'];
    $mysql -> OnduplicateInsert('tbl_workforcedepotassign',$wvalus,$extra);

    $valus[0]['depot_type'] = implode(",", $dpt_result);
    $valus[0]['start_date'] = $_POST['start_date'];
    $valus[0]['leave_date'] = $_POST['leave_date'];
    $valus[0]['dob'] = $_POST['dob'];
    $valus[0]['ni_number'] = $_POST['ni_number'];
    $valus[0]['company_name'] = $_POST['company_name'];
    $valus[0]['company_reg'] = $_POST['company_reg'];
    $valus[0]['vat_number'] = $_POST['vat_number'];
    $valus[0]['utr'] = $_POST['utr'];
    $valus[0]['employee_id'] = $_POST['employee_id'];
    $valus[0]['bank_name'] = $_POST['bank_name'];
    $valus[0]['account_number'] = $_POST['account_number'];
    $valus[0]['sort_code'] = $_POST['sort_code'];
    $valus[0]['street_address'] = $_POST['street_address'];
    $valus[0]['postcode'] = $_POST['postcode'];
    $valus[0]['state'] = $_POST['state'];
    $valus[0]['city'] = $_POST['city'];
    $valus[0]['accountancy_service'] = $_POST['accountancy_service'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $makecol = array('roleid','role_type','name','email','contact','invoice_email','depot','depot_type','start_date','leave_date','dob','ni_number','company_name','company_reg','vat_number','utr','employee_id','bank_name','account_number','sort_code','street_address','postcode','state','city','accountancy_service','update_date');

    $where = 'id ='. $_POST['id'];

    $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);

    if($statuupdate)
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
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforcePassportForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['passport_number'] = $_POST['passport_number'];
    $valus[0]['passport_nationality'] = $_POST['passport_nationality'];
    $valus[0]['passport_country'] = $_POST['passport_country'];
    $valus[0]['passport_issuedate'] = $_POST['passport_issuedate'];
    $valus[0]['passport_expirydate'] = $_POST['passport_expirydate'];

    if(isset($_POST['update']))
    {
        $makecol = array('passport_number','passport_nationality','passport_country','passport_issuedate','passport_expirydate');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'workforceDrivinglicenceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['drivinglicence_country'] = $_POST['drivinglicence_country'];
    $valus[0]['drivinglicence_number'] = $_POST['drivinglicence_number'];
    $valus[0]['drivinglicence_expiry'] = $_POST['drivinglicence_expiry'];

    if(isset($_POST['update']))
    {
        $makecol = array('drivinglicence_country','drivinglicence_number','drivinglicence_expiry');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'workforcetaxForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vat_number'] = $_POST['vat_number'];

    if(isset($_POST['update']))
    {
        $makecol = array('vat_number');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAddressForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['address'] = $_POST['address'];
    $valus[0]['street_address'] = $_POST['street_address'];
    $valus[0]['postcode'] = $_POST['postcode'];
    $valus[0]['state'] = $_POST['state'];
    $valus[0]['city'] = $_POST['city'];
    $valus[0]['country'] = $_POST['country'];

    if(isset($_POST['update']))
    {
        $makecol = array('address','street_address','postcode','state','city','country');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceBankForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['bank_name'] = $_POST['bank_name'];
    $valus[0]['account_number'] = $_POST['account_number'];
    $valus[0]['sort_code'] = $_POST['sort_code'];

    if(isset($_POST['update']))
    {
        $makecol = array('bank_name','account_number','sort_code');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceEmergencyForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['emegency_phone'] = $_POST['emegency_phone'];
    $valus[0]['emegency_name'] = $_POST['emegency_name'];

    if(isset($_POST['update']))
    {
        $makecol = array('emegency_phone','emegency_name');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforcePaymentArreasForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['arrears'] = $_POST['arrears'];

    if(isset($_POST['update']))
    {
        $makecol = array('arrears');

        $where = 'id ='. $_POST['id'];

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAttendanceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['workforce_id'] = $_POST['wid'];
    $valus[0]['type'] = $_POST['type'];
    $valus[0]['starts'] = $_POST['starts'];
    $valus[0]['end'] = $_POST['end'];
    $valus[0]['description'] = $_POST['description'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_workforceattendance',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('workforce_id','type','starts','end','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforceattendance',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'OnboardingForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['name'] = $_POST['name'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_onboarding',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_onboarding',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['userid'] = $_POST['userid'];   
    $valus[0]['type'] = $_POST['type'];
    $valus[0]['name'] = $_POST['name'];
    // $valus[0]['email'] = $_POST['email'];
    // $valus[0]['contact'] = $_POST['contact'];
    $valus[0]['depot'] = $_POST['depot'];
    $depot =  $mysql -> selectWhere('tbl_depot','id','=',$_POST['depot'],'int');
    $dptresult = mysqli_fetch_array($depot);
    $valus[0]['depot_type'] = $dptresult['name'];
    $valus[0]['address'] = $_POST['address'];
    $valus[0]['bank_name'] = $_POST['bank_name'];
    $valus[0]['account_number'] = $_POST['account_number'];
    $valus[0]['sort_code'] = $_POST['sort_code'];
    $valus[0]['company_reg'] = $_POST['company_reg'];
    $valus[0]['utr'] = $_POST['utr'];
    if (isset($_POST['company'])) {
        $valus[0]['company'] = $_POST['company'];
        if ($_POST['company'] != ' ') 
        {
            $companyname = $mysql -> selectWhere('tbl_contractorcompany','id','=',$_POST['company'],'int');
            $result = mysqli_fetch_array($companyname);
            $valus[0]['company_name'] = $result['name'];
        }
    }
    else {
    }
    $valus[0]['company_reg'] = $_POST['company_reg'];

    if(isset($_POST['insert']))
    {
        $valus[0]['email'] = $_POST['email'];
        $valus[0]['contact'] = $_POST['contact'];
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $valus[0]['iscomplated'] = 1;

        $query = "SELECT id FROM `tbl_contractor` WHERE `contact` = '".$_POST['contact']."' OR `email` LIKE '".$_POST['email']."' AND `isdelete`= 0";
        $rows = $mysql->selectFreeRun($query);
        $fetch_rows = mysqli_num_rows($rows);
        if($fetch_rows>0)
        {
            $status=0;
            $title = 'Insert Error';
            $message = 'Please Check Your Email OR Contact Can Not Be Duplicated.';
        }
        else
        {
            $statusinsert = $mysql -> insertre('tbl_contractor',$valus);
            if($statusinsert)
            {
                $status=1;
                
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
                        Drivaar has invited you to deliver packages on behalf of Byanston Logistics.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using <a href="http://drivaar.com/contractorAdmin/">http://drivaar.com/contractorAdmin/</a>. Make sure to save this email so that you can access this link later.<br><u><b>Please verify login with this credentials :</b></u><br>Username: '.$email.' <br>Password: '.$password.'.<br>
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
                      if($password)
                      {
                            $urldate = date('Y-m-d H:i:s');
                            $valusu[0]['password'] = $password;
                            $valusu[0]['urldate'] = $urldate;
                            $col = array('password');
                            $where = 'id ='.$statusinsert;
                            $update = $mysql -> update('tbl_contractor',$valusu,$col,'update',$where);
                      }   
                } 
                catch (phpmailerException $e) 
                {
                    $status=0;
                    $title = 'Insert Error';
                    $message = $mail->ErrorInfo;
                  //echo $e->errorMessage(); exit;
                } 
                catch (Exception $e) 
                {
                  //echo $e->getMessage();  exit;   
                } 

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
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $makecol = array('type','name','address','bank_name','account_number','sort_code','company','company_name','company_reg','utr','update_date');
        $where = 'id ='. $_POST['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();


        // $query = "SELECT id FROM `tbl_contractor` WHERE `contact` = '".$_POST['contact']."' OR `email` LIKE '".$_POST['email']."' AND `isdelete`= 0 AND id NOT IN (".$_POST['id'].")";
        // $rows = $mysql->selectFreeRun($query);
        // $fetch_rows = mysqli_num_rows($rows);
        // if($fetch_rows>0)
        // {
        //     $status=0;
        //     $title = 'Error';
        //     $message = 'Please Check Your Email OR Contact Can Not Be Duplicated.';
        // }
        // else
        // {
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
        // }
        $mysql -> dbDisConnect();
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'ContractorCompanyForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       

    $valus[0]['name'] = $_POST['name'];
    $valus[0]['company_reg'] = $_POST['company_reg'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_contractorcompany',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('name','company_reg','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_contractorcompany',$valus,$makecol,'update',$where);

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
}


else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDetailForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';


    $mysql = new Mysql();
    $mysql -> dbConnect();  
    $type = $_POST['hidtype'];

    $valus[0]['name'] = $_POST['name'];
    $valus[0]['contact'] = $_POST['phone'];
    $valus[0]['utr'] = $_POST['utr'];
    $valus[0]['accountancy_service'] = $_POST['accountancy_service'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $flg123 = 0;
    $makecol= array();
    if($type == 1)
    {
        if(!empty($_POST['email'])){$valus[0]['email'] = $_POST['email'];$makecol[$flcnt]='email';$flcnt++;}
        if(!empty($_POST['invoice_email'])){$valus[0]['invoice_email'] = $_POST['invoice_email'];$makecol[$flcnt]='invoice_email';$flcnt++;}

        if(!empty($_POST['company'])){$valus[0]['company'] = $_POST['company'];$makecol[$flcnt]='company';$flcnt++;}
        $depot =  $mysql -> selectWhere('tbl_contractor','id','=',$_POST['company'],'int');
        $dptresult = mysqli_fetch_array($depot);
        if(!empty($dptresult['name'])){$valus[0]['company_name'] = $dptresult['name'];$makecol[$flcnt]='company_name';$flcnt++;} 
        if(!empty($_POST['daily_rate'])){$valus[0]['daily_rate'] = $_POST['daily_rate'];$makecol[$flcnt]='daily_rate';$flcnt++;} 
        if(!empty($_POST['daily_miles_rate'])){$valus[0]['daily_miles_rate'] = $_POST['daily_miles_rate'];$makecol[$flcnt]='daily_miles_rate';$flcnt++;} 
        if(!empty($_POST['start_date'])){$valus[0]['start_date'] = $_POST['start_date'];$makecol[$flcnt]='start_date';$flcnt++;}
        if(!empty($_POST['leave_date'])){$valus[0]['leave_date'] = $_POST['leave_date'];$makecol[$flcnt]='leave_date';$flcnt++;}
        if(!empty($_POST['dob'])){$valus[0]['dob'] = $_POST['dob'];$makecol[$flcnt]='dob';$flcnt++;}
        if(!empty($_POST['ni_number'])){$valus[0]['ni_number'] = $_POST['ni_number'];$makecol[$flcnt]='ni_number';$flcnt++;}
        if(!empty($_POST['employee_id'])){$valus[0]['employee_id'] = $_POST['employee_id'];$makecol[$flcnt]='employee_id';$flcnt++;}
    }
    else
    {
        if(!empty($_POST['company_reg'])){$valus[0]['company_reg'] = $_POST['company_reg'];$makecol[$flcnt]='company_reg';$flcnt++;}
        if(!empty($_POST['vat_number'])){$valus[0]['vat_number'] = $_POST['vat_number'];$makecol[$flcnt]='vat_number';$flcnt++;}
        if(!empty($_POST['bank_name'])){$valus[0]['bank_name'] = $_POST['bank_name'];$makecol[$flcnt]='bank_name';$flcnt++;}
        if(!empty($_POST['account_number'])){$valus[0]['account_number'] = $_POST['account_number'];$makecol[$flcnt]='account_number';$flcnt++;}
        if(!empty($_POST['sort_code'])){$valus[0]['sort_code'] = $_POST['sort_code'];$makecol[$flcnt]='sort_code';$flcnt++;}
        if(!empty($_POST['street_address'])){$valus[0]['street_address'] = $_POST['street_address'];$makecol[$flcnt]='street_address';$flcnt++;}
        if(!empty($_POST['postcode'])){$valus[0]['postcode'] = $_POST['postcode'];$makecol[$flcnt]='postcode';$flcnt++;}
        if(!empty($_POST['state'])){$valus[0]['state'] = $_POST['state'];$makecol[$flcnt]='state';$flcnt++;}
        if(!empty($_POST['city'])){$valus[0]['city'] = $_POST['city'];$makecol[$flcnt]='city';$flcnt++;}
    }

    $where = 'id ='. $_POST['id'];
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
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorPassportForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['passport_number'] = $_POST['passport_number'];
    $valus[0]['passport_nationality'] = $_POST['passport_nationality'];
    $valus[0]['passport_country'] = $_POST['passport_country'];
    $valus[0]['passport_issuedate'] = $_POST['passport_issuedate'];
    $valus[0]['passport_expirydate'] = $_POST['passport_expirydate'];

    if(isset($_POST['update']))
    {
        $makecol = array('passport_number','passport_nationality','passport_country','passport_issuedate','passport_expirydate');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'ContractoreDrivinglicenceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['drivinglicence_country'] = $_POST['drivinglicence_country'];
    $valus[0]['drivinglicence_number'] = $_POST['drivinglicence_number'];
    $valus[0]['drivinglicence_expiry'] = $_POST['drivinglicence_expiry'];

    if(isset($_POST['update']))
    {
        $makecol = array('drivinglicence_country','drivinglicence_number','drivinglicence_expiry');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContactortaxForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vat_number'] = $_POST['vat_number'];

    if(isset($_POST['update']))
    {
        $makecol = array('vat_number');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAddressForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['address'] = $_POST['address'];
    $valus[0]['street_address'] = $_POST['street_address'];
    $valus[0]['postcode'] = $_POST['postcode'];
    $valus[0]['state'] = $_POST['state'];
    $valus[0]['city'] = $_POST['city'];
    $valus[0]['country'] = $_POST['country'];

    if(isset($_POST['update']))
    {
        $makecol = array('address','street_address','postcode','state','city','country');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorBankForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['bank_name'] = $_POST['bank_name'];
    $valus[0]['account_number'] = $_POST['account_number'];
    $valus[0]['sort_code'] = $_POST['sort_code'];


    $query = "SELECT * FROM `tbl_contractor` WHERE `bank_name` = '".$_POST['bank_name']."' and  `account_number` = '".$_POST['account_number']."' and  `sort_code` = '".$_POST['sort_code']."' and id NOT IN (".$_POST['id'].")";
    $rows = $mysql -> selectFreeRun($query);
    $fetch_rows = mysqli_num_rows($rows);
    if($fetch_rows>0)
    {
        $status=0;
        $title = 'Error';
        $message = 'Data has been already exited.';
    }
    else
    {
        if(isset($_POST['update']))
        {
            $makecol = array('bank_name','account_number','sort_code');
            $where = 'id ='. $_POST['id'];
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
        }
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorEmergencyForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['emegency_phone'] = $_POST['emegency_phone'];
    $valus[0]['emegency_name'] = $_POST['emegency_name'];

    if(isset($_POST['update']))
    {
        $makecol = array('emegency_phone','emegency_name');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAttendanceForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['contractor_id'] = $_POST['cid'];
    $valus[0]['type'] = $_POST['type'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['starts'] = $_POST['starts'];
    $valus[0]['end'] = $_POST['end'];
    $valus[0]['description'] = $_POST['description'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_contractorattendance',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('contractor_id','type','date','starts','end','description','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_contractorattendance',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorDocumentForm')
{

    header("content-Type: application/json");
    $status=1;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['contractor_id'] = $_POST['cid'];
    $valus[0]['type'] = $_POST['type'];
        $query1 = "SELECT * FROM `tbl_vehicledocumenttype` WHERE `id`=". $_POST['type'];
        $userrow1 =  $mysql -> selectFreeRun($query1);
        $renewresult1 = mysqli_fetch_array($userrow1);
    $valus[0]['typename'] = $renewresult1['name'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['expiredate'] = $_POST['expiredate'];

    $uploadDir = 'uploads/vehicledocument/'; 
    $uploadedFile = ''; 
    if(!empty($_FILES["file"]["name"]))
    { 
        // File path config 
        $fileName = basename($_FILES["file"]["name"]); 
        $targetFilePath = $uploadDir . $fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
        // Allow certain file formats 
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
        if(in_array($fileType, $allowTypes))
        { 
            // Upload file to the server 
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))
            { 
                $uploadedFile = $fileName; 
                $valus[0]['file'] = $_POST['vid'].$uploadedFile;
            }
            else
            { 
                $status = 0; 
                $title = 'Error';
                $message = 'Sorry, there was an error uploading your file.'; 
            } 
        }
        else
        { 
            $status = 0; 
            $title = 'Error';
            $message = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
        } 
    }

    if($status == 1)
    {
         if(isset($_POST['insert']))
        {
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $statusinsert = $mysql -> insert('tbl_contractordocument',$valus);
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
            $mysql -> dbDisConnect();
        }
        else if(isset($_POST['update']))
        {
            $valus[0]['update_date'] = date('Y-m-d H:i:s');

            $makecol = array('name','update_date');

            $where = 'id ='. $_POST['id'];

            $mysql = new Mysql();

            $mysql -> dbConnect();

            $statuupdate = $mysql -> update('tbl_contractordocument',$valus,$makecol,'update',$where);

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
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorPaymentArreasForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['arrears'] = $_POST['arrears'];

    if(isset($_POST['update']))
    {
        $makecol = array('arrears');

        $where = 'id ='. $_POST['id'];

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
    }


    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'OnboardEmailForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['email'] = $_POST['email'];
    $valus[0]['depot'] = $_POST['depot'];
    $valus[0]['isactive'] = 2;
    $depot =  $mysql -> selectWhere('tbl_depot','id','=',$_POST['depot'],'int');
    $dptresult = mysqli_fetch_array($depot);
    $valus[0]['depot_type'] = $dptresult['name']; 

    if(isset($_POST['insert']))
    {

        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insertre('tbl_contractor',$valus);

        $imgid = 1;
        $imgqry =  $mysql -> selectWhere('tbl_orgdocument','id','=',$imgid,'int');
        $imgresult = mysqli_fetch_array($imgqry);
        $image = $webroot.'uploads/organizationdocuments/'.$imgresult['file1'];
        $image1 = $webroot.'uploads/organizationdocuments/'.$imgresult['file'];

        $cid = base64_encode($statusinsert);
        $emlusername='noreply@drivaar.com';
        $emlpassword='DrivaarInvitation@123';
        $emltitle = 'Drivaar';
        $subject = 'Drivaar';
        $body = 'Hello,'.$_POST["name"].'Here is your registration link.';
        $email =$_POST['email'];
        $name = $_POST['name'];
    
        $dataMsg = "
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
                    <div class='row content'>
                        <img height='50px' width='150px' src='".$image."'>
                        <img height='50px' width='150px' src='".$image1."'>
                    </div>
                    <div class='row content'>
                        <h2><p style='background-color:#03a9f3; text-align: center;color:#ffffff;'> Dear $name</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using  $email  . Make sure to save this email so that you can access this link later.<br>You have recieved this email for registration in Drivaar.Please click on the link and complete your registration process.<a href='http://drivaar.com/getreg.php?cid=$cid'>Click here</a> to register.<br>
                    2. If applicable, please provide the requested information for the required background check and motor vehicle report i.e., right to work, driver’s license, UTR number, NINO number and other relevant information. Please Note: You need to take photos to prove the accuracy of the details provided 
                    </p>
                    <p>
                        When you’ve completed these tasks, Drivaar will contact you with next steps. 
                    </p>
                    Sincerely,<br>
                    Bryanston Logistics<br> 
                    <p>
                    If you cannot click on the link above to accept the invitation, please copy, and paste the following URL to your browser:<br> <a href='#'><img height='50px' width='200px' src='./google-play.png'></a> <a href='#!'><img height='50px' width='200px' src='./app-store.png'></a>
                    </p>
                    <p>
                    Important: Note that if you fail to submit all information required within 24 hours of receiving the original email invitation, this screening will be automatically cancelled, and your Start Date in Drivaar Limited may have to be postponed. 
                    </p>
                    <p>
                    Please do not reply to this email. It was sent from an address that cannot accept incoming messages
                    </p>
                    </div>
                    </body>
                    </html>";  

        require_once('phpmailer/class.phpmailer.php');
        $mail = new PHPMailer(true);            
        $mail->IsSMTP(); 
          try {
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


                $onboardutldate = date('Y-m-d H:i:s');
                $valusu[0]['onboardutldate'] = $onboardutldate;
                $col = array('onboardutldate');
                $where = 'id ='.$statusinsert;
                $update = $mysql -> update('tbl_contractor',$valusu,$col,'update',$where);   
          
            } catch (phpmailerException $e) {
              //echo $e->errorMessage(); exit;
            } catch (Exception $e) {
              //echo $e->getMessage();  exit;    
            } 

        //SendMessage($emlusername,$emlpassword,$emltitle,$subject,$body,$dataMsg,$email,$name);

        if($statusinsert)
        {
            $status = 1;
            $title = 'Insert';
            $message = 'Data has been inserted successfully.';
        }
        else
        {
            $status = 0;
            $title = 'Insert Error';
            $message = 'Data can not been inserted.';
        }

        $name = 'Insert';
        $mysql -> dbDisConnect();
    }

    $response['status'] = $status;$response['name'] = $name;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorTimesheetForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['rateid']= $_POST['rateid'];
    $valus[0]['value']= $_POST['value'];
    $valus[0]['ischeckval'] = $_POST['ischeckval'];
    $valus[0]['uniqid']= $_POST['uniqid'];
    
    
    if($_POST['date']  && $_POST['uniqid'])
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_contractortimesheet` WHERE `isdelete`= 0 AND `isactive`= 0 AND `uniqid`='".$_POST['uniqid']."'";
        $raterow =  $mysql -> selectFreeRun($query);
        $result = mysqli_fetch_array($raterow);

        if($result['id'])
        {
            $valus[0]['update_date'] = date('Y-m-d H:i:s');;

            $makecol = array('cid','userid','date','rateid','value','ischeckval','uniqid','update_date');

            $where = 'id ='. $result['id'];

            $statuupdate = $mysql -> update('tbl_contractortimesheet',$valus,$makecol,'update',$where);

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
            $statusinsert = $mysql -> insert('tbl_contractortimesheet',$valus);
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceTimesheetForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';


    $mysql = new Mysql();
    $mysql->dbConnect();
    $dt = date('Y-m-d', strtotime($_POST['date']));

    $valus[0]['wid'] = $_POST['wid'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['date'] = $_POST['date'];
    $valus[0]['rateid']= $_POST['rateid'];
    $valus[0]['value']= $_POST['value'];
    $valus[0]['ischeckval'] = $_POST['ischeckval'];
    $valus[0]['uniqid']= $_POST['uniqid'];
    
    if($_POST['date']  && $_POST['uniqid'])
    {
        $invId="";
        //Invoice code.......
        $query1 = "SELECT c.depot,ar.name as arname,c.vat_number FROM `tbl_user` c LEFT JOIN `tbl_arrears` ar On ar.id=c.arrears WHERE c.id=".$_POST['wid'];
        $strow1 =  $mysql->selectFreeRun($query1);
        $statusresult = mysqli_fetch_array($strow1);
        $dueDate="";
        $onUpdate ="";
        $values[0]['vat']= 0;
        if(isset($statusresult['arname']) && $statusresult['arname']!=NULL)
        {
            $arTemp = explode(" ",$statusresult['arname'])[0];
            $dueDate = date('Y-m-d', strtotime('+'.$arTemp.' week', strtotime($_POST['enddate'])));
            $onUpdate = ", duedate='$dueDate'";
        }else
        {
            $onUpdate = "";
        }
        if($statusresult['vat_number'] != NULL && $statusresult['vat_number']!="" && !empty($statusresult['vat_number']))
        {
            $values[0]['vat']= 1;
        }
        $wkn = date("W", strtotime($_POST['startdate'].' +1 day'));
        //$wkn++;
        $wkCode = getAlphaCode($wkn,2);
        $wkYear = date('Y',strtotime($_POST['startdate']));
        $yrCode = (int)($wkYear) - 2020;
        $tcode='W';
        $depCode="";
        if($wkYear==2021)
        {
            $depCode = getAlphaCode($statusresult['depot'],3);
        }
        $invId = $yrCode.$tcode.$depCode.$wkCode.$_POST['wid'];
        $values = array();

        $values[0]['cid']= $_POST['wid'];
        $values[0]['invoice_no']= $invId;
        $values[0]['week_no']= $wkn;
        $values[0]['from_date']= $_POST['startdate'];
        $values[0]['to_date']= $_POST['enddate'];
        $values[0]['weekyear']= $wkYear;
        $values[0]['istype']= 2;
        $values[0]['depot_id']= $statusresult['depot'];
        if($dueDate!="")
        {
            $values[0]['duedate']= $dueDate;
        }

        $mysql -> OnduplicateInsert('tbl_contractorinvoice',$values,"ON DUPLICATE KEY UPDATE `invoice_no` = '$invId' $onUpdate");

        //timesheet code....

        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_workforcetimesheet` WHERE `isdelete`= 0 AND `isactive`= 0 AND `uniqid`='".$_POST['uniqid']."'";
        $raterow =  $mysql -> selectFreeRun($query);
        $result = mysqli_fetch_array($raterow);
        if($result['id'])
        {
            $valus[0]['update_date'] = date('Y-m-d H:i:s');;

            $makecol = array('wid','userid','date','rateid','value','ischeckval','uniqid','update_date');

            $where = 'id ='. $result['id'];

            $statuupdate = $mysql -> update('tbl_workforcetimesheet',$valus,$makecol,'update',$where);

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
            $statusinsert = $mysql -> insert('tbl_workforcetimesheet',$valus);
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
    $response['sql'] = $statuupdate;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorLendForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['no_of_instal'] = $_POST['no_of_instal'];
    $valus[0]['time_interval'] = $_POST['time_interval'];
    $valus[0]['week_of_payment'] = $_POST['week_of_payment'];
    $valus[0]['reason'] = $_POST['reason'];
    if($_POST['lendid']>0)
    {

        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('amount','no_of_instal','time_interval','week_of_payment','reason','update_date');

        $where = 'id ='. $_POST['lendid'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_contractorlend',$valus,$makecol,'update',$where);

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
    else 
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_contractorlend',$valus);
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
        $mysql -> dbDisConnect();
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorPaymentForm')
{
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
    $valus[0]['date'] = $_POST['dateval'];
    $valus[0]['account'] = $_POST['account'];
    $valus[0]['isadvanced'] = $_POST['isadvanced'];
    $valus[0]['reason'] = $_POST['reason'];
    $valus[0]['category'] = $_POST['category'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceLendForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['wid'] = $_POST['wid'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['no_of_instal'] = $_POST['no_of_instal'];
    $valus[0]['time_interval'] = $_POST['time_interval'];
    $valus[0]['week_of_payment'] = $_POST['week_of_payment'];
    $valus[0]['reason'] = $_POST['reason'];
    if($_POST['lendid']>0)
    {

        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $makecol = array('amount','no_of_instal','time_interval','week_of_payment','reason','update_date');

        $where = 'id ='. $_POST['lendid'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforcelend',$valus,$makecol,'update',$where);

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
    else 
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insert('tbl_workforcelend',$valus);
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
        $mysql -> dbDisConnect();
    }

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforcePaymentForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['wid'] = $_POST['wrkid'];
    $valus[0]['loan_id'] = $_POST['loan_id'];
    $valus[0]['week_no'] = $_POST['date'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['date'] = $_POST['dateval'];
    $valus[0]['account'] = $_POST['account'];
    $valus[0]['isadvanced'] = $_POST['isadvanced'];
    $valus[0]['reason'] = $_POST['reason'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $query = "SELECT * FROM `tbl_workforcelend` WHERE `isactive`=0 AND `isdelete`=0 AND `id`=".$_POST['loan_id'];
    $row =  $mysql -> selectFreeRun($query);
    $result = mysqli_fetch_array($row);

    $paymentquery = "SELECT SUM(`amount`) AS paidamount FROM `tbl_workforcepayment` WHERE `loan_id`=".$result['id']." AND `isdelete`=0 AND `wid`=".$result['wid'];
    $paymentrow =  $mysql -> selectFreeRun($paymentquery);
    $pyresult = mysqli_fetch_array($paymentrow);

    $singleamount = ($result['amount']/$result['no_of_instal']);
    $remianamount = ($result['amount']-$pyresult['paidamount']);
    if($remianamount>=$singleamount)
    {   
        if($remianamount==$singleamount)
        {
            $flag=1;
        }
        $payamount = $singleamount;
    }
    else
    {
        $payamount = $remianamount;
        $flag=1;
    }

    if($payamount>0)
    {
         $statusinsert = $mysql -> insert('tbl_workforcepayment',$valus);
    }

    if($flag==1 || $payamount==0)
    {
        $valus1[0]['is_completed'] = 1;
        $valus1[0]['update_date'] = date('Y-m-d H:i:s');
        $makecol = array('is_completed','update_date');
        $where = 'id ='. $result['id'];
        $statuupdate = $mysql -> update('tbl_workforcelend',$valus1,$makecol,'update',$where);
        //loan close is_complete is 1.
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
else if(isset($_POST['action']) && $_POST['action'] == 'AssignVehicleForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['vid'] = $_POST['vid'];
    $valus[0]['driver'] = $_POST['driver'];
    $valus[0]['tduniqid'] = $_POST['tdid'];
    $valus[0]['start_date'] = $_POST['start_date'];
    $valus[0]['end_date'] = $_POST['end_date'];
    $valus[0]['amount'] = $_POST['amount'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');



    if($_POST['start_date'] && $_POST['end_date'])
    {
        $qrychk = $mysql -> selectFreeRun("SELECT count(*) AS contra FROM `tbl_assignvehicle` 
                            WHERE `isdelete`=0 AND `isactive`=0 
                            AND ((`start_date`>='".$_POST['start_date']."' AND `end_date`<='".$_POST['start_date']."' ) OR
                            (`start_date`<='".$_POST['end_date']."' AND `end_date`>='".$_POST['end_date']."' ))
                            AND driver='".$_POST['driver']."'");
        $countdata = mysqli_fetch_array($qrychk);


        $qrychk1 = $mysql -> selectFreeRun("SELECT count(*) AS contra FROM `tbl_assignvehicle` 
                            WHERE `isdelete`=0 AND `isactive`=0 
                            AND ((`start_date`>='".$_POST['start_date']."' AND `end_date`<='".$_POST['start_date']."' ) OR
                            (`start_date`<='".$_POST['end_date']."' AND `end_date`>='".$_POST['end_date']."' ))
                             AND vid='".$_POST['vid']."'");
        $countdata1 = mysqli_fetch_array($qrychk1);

        if($countdata['contra']==0 && $countdata1['vehicle']==0)
        {
            $statusinsert = $mysql -> insert('tbl_assignvehicle',$valus);
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
        }
        else
        {
            $status=0;
            $title = 'Insert Error';
            $message = 'Oops,Vehicle allready assigned.';
        }
   
    }
    else
    {
        $status=0;
        $title = 'Error';
        $message = 'Please,Fill all required field.';
    } 
    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforcescheduleInsert')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['wid'] = $_POST['wid'];
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['uniqid'] = $_POST['typeid'];
    $valus[0]['date'] = $_POST['date'];
    $setvalue = 1;

    if($_POST['wid'] && $_POST['userid'] && $_POST['typeid'])
    {
        $query =  $mysql -> selectWhere('tbl_workforceschedule','uniqid','=',$_POST['typeid'],'char');
        $statusresult = mysqli_fetch_array($query);
        if($_POST['isweekoff']==1)
        {
            $setvalue = 1;
            $ispaid = $_POST['ispaid']; 
        }
        else
        {
            $setvalue = 0;
            $ispaid = 0;
        }
        if($statusresult>0)
        {

            $valus[0]['ispaid'] = $ispaid;
            $valus[0]['isweekoff'] = $setvalue;
            
            $valus[0]['update_date'] = date('Y-m-d H:i:s');

            $makecol = array('ispaid','isweekoff','update_date');

            $where = 'id ='. $statusresult['id'];

            $statuupdate = $mysql -> update('tbl_workforceschedule',$valus,$makecol,'update',$where);

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
            $valus[0]['ispaid'] = $_POST['ispaid'];
            $valus[0]['isweekoff'] =  $_POST['isweekoff'];
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $statusinsert = $mysql -> insert('tbl_workforceschedule',$valus);
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
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    } 
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['isweek'] = $setvalue;
    $response['ispaidval'] = $_POST['ispaid'];
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
            error_log($name,0);
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
else if(isset($_POST['action']) && $_POST['action'] == 'AddInvoiceStatusData')
{

    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $id = $_POST['id'];
    $valuse[0]['status_id'] = $_POST['status'];
    $valuse[0]['update_date'] = date('Y-m-d H:i:s');
    $stt = "";
     //$query = "SELECT * FROM `tbl_contractorinvoice` WHERE id =".$id;
    $query = "SELECT DISTINCT ci.id as ciid, ci.*  FROM `tbl_contractorinvoice` ci WHERE ci.`isdelete`=0  AND ci.`id`=".$id;
    $row =  $mysql -> selectFreeRun($query); 
    if($result = mysqli_fetch_array($row))
    {
        $valus[0]['cid'] = $result['cid'];
        $valus[0]['invoiceid'] = $result['id'];
        $valus[0]['invoice_no'] = $result['invoice_no'];
        $valus[0]['oldstatus_id'] = $result['status_id'];
        $valus[0]['newstatus_id'] = $_POST['status'];
        $valus[0]['status_date'] = date('Y-m-d');
        $valus[0]['type'] = $result['istype'];
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $statusinsert = $mysql -> insertre('tbl_contractorstatusinvoice',$valus);


        if($result['istype']==1)
        {
            $cntquery = "SELECT * FROM `tbl_contractor` WHERE `id`=".$result['cid'];
            $cntrow =  $mysql->selectFreeRun($cntquery);
            $result1 = mysqli_fetch_array($cntrow);

            $invc = new InvoiceAmountClass();
            $totalshow = $invc->ContractorInvoiceTotal($result['invoice_no']);

        }
        else if($result['istype']==2)
        {
            $cntquery = "SELECT * FROM `tbl_user` WHERE `id`=".$result['cid'];
            $cntrow =  $mysql->selectFreeRun($cntquery);
            $result1 = mysqli_fetch_array($cntrow);

            $totalshow = WorkforceInvoiceTotal($id);
        }

        if($_POST['status']==7 || $_POST['status']==8)
        {
                $email = $result1['email'];
                $name = $result1['name'];
                $cnt = 0;
            
               
                $b64 = base64_encode($result["cid"]."#1#".$result["week_no"]."#".$result["weekyear"]);
                $invkey=base64_encode($result["cid"]."#1#".$result["week_no"]."#".$result["weekyear"]."#".$b64);

                if($result['istype']==1)
                {
                     $action = "<a href='http://drivaar.com/home/contractorInvoiceMail.php?invkey=".$invkey."' target='_blank' style='padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #9f5c5c;text-decoration: none;font-weight:bold;display: inline-block;'>Click here to view Invoice</a>";
                
                }
                else if($result['istype']==2)
                {
                     $action = "<a href='http://drivaar.com/home/workforceInvoiceMail.php?invkey=".$invkey."' target='_blank' style='padding: 8px 12px; border: 1px solid #ED2939;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #9f5c5c;text-decoration: none;font-weight:bold;display: inline-block;'>Click here to view Invoice</a>";
                
                }
               
                $frmDate = date("d M, Y",strtotime($result['from_date']));
                $toDate = date("d M, Y",strtotime($result['to_date']));
                $emlusername='noreply@drivaar.com';
                $emlpassword='DrivaarInvitation@123';
                $emltitle = 'Invoice @ Drivaar';
                $subject = "Invoice for week no ".$result["week_no"];
                $body = 'Invoice # '.$result['invoice_no'];
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
                            Your invoice for week number '.$result["week_no"].' (Dated From '. $frmDate.' to '.$toDate.') with invoice id '.$result['invoice_no'].' has been generated. Invoice amount is £ '.$totalshow.'
                            
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
                    $title = 'Insert';
                    $message = 'Email sent to User And Data has been inserted.';
                    $stt = "Invoice Sent";
                    $cnt++;
                } 
                catch (phpmailerException $e) 
                {
                    $status=0;
                    $title = 'Email not Sent';
                    $message = 'Failed to sent mail.';
                    // $stt = $result['invoice_no']." # ".$e;
                    $stt = "Invoice NOT Sent";
                    exit;
                } 
                catch (Exception $e) 
                {
                    echo $e;
                    $status=0;
                    $title = 'Email not Sent';
                    $message = 'Failed to sent mail.';
                    // $stt = $result['invoice_no']." # ".$e;
                    $stt = "Invoice NOT Sent";
                    exit;
                }
        }
    }

    $usercol = array('status_id');
    $where = 'id ='. $id;
    $paymentdata =  $mysql -> update('tbl_contractorinvoice',$valuse,$usercol,'update',$where);

    if($paymentdata && $statusinsert)
    {
        $status = 1;
    }
    $mysql -> dbDisConnect();
    // $response['stt'] = $stt;
    $response['status'] = $status;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'addassets')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['type_name'] = $_POST['assest_option'];
    $valus[0]['name'] = $_POST['asset_name'];
    $valus[0]['number'] = $_POST['asset_number'];
    $valus[0]['price'] = $_POST['asset_price'];
    $valus[0]['description'] = $_POST['asset_description'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
   
    $statusinsert = $mysql -> insert('tbl_financeassets',$valus);
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
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'finance_asset_assignupdate')
{

    header("content-Type: application/json");
    $status=0;
    $valus[0]['assign_to'] = $_POST['assign_to'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $where = "id=".$_POST['id'];
    $makecol = array('assign_to');
    $assignupdate = $mysql -> update('tbl_financeassets',$valus,$makecol,'update',$where);
    if($assignupdate)
        {
            $status=1;
            $title = 'Update';
            $message = 'Data has been update successfully.';
        }
        else
        {
            $status=0;
            $title = 'Update Error';
            $message = 'Data can not been update.';
        }

  
    
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'saveOnboard')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['onboard_id'] = $_POST['onboard_id'];
    $valus[0]['is_onboard'] = $_POST['is_onboard'];
    if($_POST['cid'] && $_POST['onboard_id'])
    {
        $query =  $mysql -> selectFreeRun("SELECT * FROM `tbl_contractoronboarding` WHERE `cid`=".$_POST['cid']." AND `onboard_id`=".$_POST['onboard_id']." AND `isdelete`=0 AND `isactive`=0");
        $statusresult = mysqli_fetch_array($query);
  
        if($statusresult>0)
        {
            
            $valus[0]['update_date'] = date('Y-m-d H:i:s');

            $makecol = array('is_onboard','update_date');

            $where = 'id ='. $statusresult['id'];

            $statuupdate = $mysql -> update('tbl_contractoronboarding',$valus,$makecol,'update',$where);

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
            $statusinsert = $mysql -> insert('tbl_contractoronboarding',$valus);
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

    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    } 
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['isweek'] = $setvalue;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'AddFinanceCustomForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

   
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['quantity'] = $_POST['quantity'];
    $valus[0]['price']=$_POST['price'];
    $valus[0]['tax']=$_POST['tax'];

    if(isset($_POST['insert']))
    { 
        $valus[0]['invoice_id'] = $_POST['cid'];
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $mysql = new Mysql();
        $mysql -> dbConnect();

        $statusinsert = $mysql -> insert('tbl_custominvoicedata',$valus);
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
        $mysql -> dbDisConnect();
    }
    else if(isset($_POST['update']))
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('name','description','quantity','price','tax','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_custominvoicedata',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'ContractorAssetForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['cid'] = $_POST['cid'];
    $valus[0]['asset_id'] = $_POST['asset'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();
        $mysql -> dbConnect();
        $makeinsert = $mysql -> insert('tbl_contractorassets',$valus);
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

        $makecol = array('cid','asset_id','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_contractorassets',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceAssetForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['wid'] = $_POST['wid'];
    $valus[0]['asset_id'] = $_POST['asset'];

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();
        $mysql -> dbConnect();
        $makeinsert = $mysql -> insert('tbl_workforceassets',$valus);
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

        $makecol = array('wid','asset_id','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_workforceassets',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'WorkforceManagerForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['assignto'] = $_POST['assignto'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');;
    $makecol = array('assignto','update_date');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);

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
else if(isset($_POST['action']) && $_POST['action'] == 'msformrentalagreement')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $valus[0]['driver_id'] = (int)$_POST['driver'];
    $valus[0]['userid'] = (int)$_POST['userid'];
    $valus[0]['depot_id'] = $_POST['dpid'];
    $valus[0]['tduniqid'] = "spandiv_".$_POST['userid']."_".date('Y-m-d');
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');
    $sdpid = $_POST['dpid'];
    $dtv = "";
    $statusinsert = $mysql -> insertre('tbl_vehiclerental_agreement',$valus);
    if($statusinsert)
    {
        $status=1;
        $title = 'Insert';
        $message = 'Data has been inserted successfully.';
        $expquery =  "SELECT DISTINCT v.*,vs.`name` as sname 
        FROM `tbl_vehicles` v 
        INNER JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` 
        WHERE v.`depot_id`=$sdpid AND v.`status` IN ('1','4') AND v.`isdelete`=0 AND v.`supplier_id` iS NOT NULL ORDER BY v.`id` ASC";
        $exprow =  $mysql->selectFreeRun($expquery);
        while ($result = mysqli_fetch_array($exprow)) 
        {
            $dtv .= "<option value='".$result['id']."'>".$result['registration_number']." ".$result['sname']."</option>";
        }
        $mysql->dbDisconnect();
    }
    else
    {
        $status=0;
        $title = 'Insert Error';
        $message = 'Data can not been inserted.';
    }
        
    $mysql -> dbDisConnect();
    $response['id'] = $statusinsert;
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['vehList'] = $dtv;
    $response['qry'] = $expquery;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'msformrentalagreementstep2')
{
    header("content-Type: application/json");
    
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    
    
    $driver = (int)$_POST['last_insert_id'];
    
    $where = "id=" .$driver;
    $cntf = 0;
    $valus = array();
    $key123 = array();
    $vehID = $_POST['vehicle_id'];
    $drvAvl =1;
    $response = array();
    $response['end'] = 0;
    if(isset($_POST['step']) && $_POST['step']==1)
    {
        $getDri = "SELECT va.id FROM `tbl_vehiclerental_agreement` va WHERE '".$_POST['pickup_date']."' BETWEEN va.pickup_date AND va.return_date AND '".$_POST['return_date']."' BETWEEN va.pickup_date AND va.return_date AND (va.driver_id = (SELECT va1.driver_id FROM `tbl_vehiclerental_agreement` va1 where va1.id=$driver) OR va.vehicle_id = $vehID) AND va.isdelete=0  AND va.iscompalete=0";

        // "SELECT DISTINCT v.* FROM `tbl_vehiclerental_agreement` va RIGHT JOIN `tbl_contractor` v ON v.id = va.driver_id WHERE v.id NOT IN (SELECT DISTINCT va1.driver_id from `tbl_vehiclerental_agreement` va1 WHERE NOW() BETWEEN va1.pickup_date AND va1.return_date AND va1.depot_id=(SELECT va2.`depot_id` FROM `tbl_vehiclerental_agreement` va2 WHERE va2.`id`=$driver)  AND va1.isdelete=0) AND v.depot=(SELECT va2.`depot_id` FROM `tbl_vehiclerental_agreement` va2 WHERE va2.`id`=$driver) AND v.iscomplated=1 AND v.isactive=0 AND v.isdelete=0 AND v.id=(SELECT va2.`driver_id` FROM `tbl_vehiclerental_agreement` va2 WHERE va2.`id`=$driver)  AND va.isdelete=0";

        $rowDri =  $mysql -> selectFreeRun($getDri); 
        $resultDri = mysqli_num_rows($rowDri);
        if($resultDri>0)
        {
        	$status=0;
            $title = 'Error';
            $message = 'Driver OR Vehicle might not available on selected time period.';
            $drvAvl =0;
            
        }else
        {
            $drvAvl = 1;
            $dte = explode("-",$_POST['pickup_date']);
            $valus[$cntf]['tduniqid'] = "spandiv_".$_POST['vehicle_id']."_".$dte[2]."_".$dte[1]."_".$dte[0];
        }
    }


    if($drvAvl == 1)
    {
    foreach($_POST as $key=>$value)
    {
        if($key == 'action' || $key == 'last_insert_id' || $key == 'step' || $key == 'termSize'){
              
        }else{
            $valus[$cntf][$key] = $value;
            $key123[$cntf] = $key;
        }
    }
    if(isset($_POST['step']) && $_POST['step']==13)
    {
        $valus[$cntf]['iscompalete'] = 1;
        $key123[$cntf] = "iscompalete";
        $response['end'] = 1;

        //conditional repoer query here
    }
    $status=1;
    $title = 'Insert';
    $message = 'Data has been update successfully.';
    if(count($valus)>0)
    {
        $statusinsert = $mysql -> update('tbl_vehiclerental_agreement',$valus ,$key123, 'update', $where); 
        $condiQry = "SELECT `userid`, `vehicle_id`, `driver_id`, `pickup_date`, `return_date`, `current_odometer`, `fuel_range`, `front_left_type`, `front_right_type`, `back_left_type`, `back_right_type`, `driver_signature`, `ledger_signature` FROM `tbl_vehiclerental_agreement` WHERE ".$where;
        $condiData = $mysql -> selectFreeRun($condiQry);
        if($getRentalDtVondi = mysqli_fetch_array($condiData))
        {
            // $valCondi[0]['userid'] = $getRentalDtVondi['userid'];
            // $valCondi[0]['vehicle_id'] = $getRentalDtVondi['vehicle_id'];
            // $valCondi[0]['driver_id'] = $getRentalDtVondi['driver_id'];
            // $valCondi[0]['uniqueid'] = strtotime(date("Y-m-d H:i:s"));
            // $valCondi[0]['odometer'] = $getRentalDtVondi['current_odometer'];
            // $valCondi[0]['fuel'] = $getRentalDtVondi['fuel_range'];
            // $valCondi[0]['front_left_tyre'] = $getRentalDtVondi['front_left_type'];
            // $valCondi[0]['front_right_tyre'] = $getRentalDtVondi['front_right_type'];
            // $valCondi[0]['back_left_tyre'] = $getRentalDtVondi['back_left_type'];
            // $valCondi[0]['back_right_tyre'] = $getRentalDtVondi['back_right_type'];
            // $valCondi[0]['conductsign'] = $getRentalDtVondi['driver_signature'];
            // $valCondi[0]['vehiclephoto'] = $getRentalDtVondi[''];
            // $valCondi[0]['driversign'] = $getRentalDtVondi['ledger_signature'];
            // $valCondi[0]['rentAgreementId'] = $driver;
        }
            if($statusinsert)
            {
                $status=1;
                $title = 'Insert';
                $message = 'Data has been update successfully.';
            }
            else
            {
                $status=0;
                $title = 'Insert Error';
                $message = 'Data can not been update.';
            }
    }
    if(isset($_POST['last_insert_id']))
    {
        $query3 = "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id =".$_POST['last_insert_id'];
        $row3 =  $mysql -> selectFreeRun($query3); 
        $result3 = mysqli_fetch_array($row3);
        $vehID = $result3['vehicle_id'];
    }
        $query = "SELECT * FROM `tbl_vehicles` WHERE id =".$vehID;
        $row =  $mysql -> selectFreeRun($query); 
        $result = mysqli_fetch_array($row);
        $registration_number = $result['registration_number'];

        $sql1 = "SELECT * FROM `tbl_vehiclemake` WHERE id =".$result['make_id'];
        $row1 =  $mysql -> selectFreeRun($sql1); 
        $result1 = mysqli_fetch_array($row1);
        
        $sql2 = "SELECT * FROM `tbl_vehiclemodel` WHERE id =".$result['model_id'];
        $row2 =  $mysql -> selectFreeRun($sql2); 
        $result2 = mysqli_fetch_array($row2);
    
    
    
        $mysql -> dbDisConnect();     
            
        $response['vehicle_reg_no'] = $result['registration_number'];
        $response['make_id'] = $result1['name'];
        $response['model_id'] = $result2['name'];
    }
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    //$response['qry'] = $getDri;
    echo json_encode($response); 

}
else if(isset($_POST['action']) && $_POST['action'] == 'vehiclerentalaggreementimage')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $countfiles = count($_FILES['files']['name']);
    $upload_location = "uploads/vehicle-rental-agreement-document/";
    $files_arr = array();
    for($index = 0;$index < $countfiles;$index++){

      if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
      $filename = $_FILES['files']['name'][$index];
      $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
      $valid_ext = array("png","jpeg","jpg");

        if(in_array($ext, $valid_ext)){

        $filename = $_POST['last_insert_id']."-".$filename;
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = str_replace(' ', '-', $filename);
        $tmpFile = preg_replace('/[^A-Za-z0-9\-]/', '', $filename).".".$ext;
        $path = $upload_location.$tmpFile;
        $path2 = "uploads/conditionalreport/extraimage/".$tmpFile;

         if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path)){
            if(!file_exists($targetFile))
            {
                move_uploaded_file($_FILES['files']['tmp_name'][$index],$path2);
            }
            $files_arr[] = $path;
            $valus[0]['rental_driver_id'] = $_POST['last_insert_id'];
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');
            $valus[0]['files'] = $filename;

            // $makeinsert = $mysql -> insert('tbl_vehiclerentalagreement_image',$valus);
        $extra = "ON DUPLICATE KEY UPDATE insert_date='".date('Y-m-d H:i:s')."'";
        $makeinsert = $mysql -> OnduplicateInsert('tbl_vehiclerentalagreement_image',$valus,$extra);
            // error_log($makeinsert,0);
            // print_r($makeinsert);
                    
            if($makeinsert){
                // echo "Uploaded Successfully";
                 $status=1;
                 $title = 'Insert';
                 $message = 'Data has been update successfully.';
            }
            else{
                $status=0;
                $title = 'Insert Error';
                $message = 'Data can not been update.';
            }
            
        }
      }
   }
}

    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    // $response['status'] = $status;
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['qry'] = $makeinsert;
    echo json_encode($response);  
    // echo json_encode($files_arr);


}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalinsuranceupdate')
{
   header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
        $last_insert_id = $_POST['last_insert_id'];
        $insurance_type = $_POST['insurance_type'];
        $valus[0]['ins_cmp_type'] = $insurance_type;
        $where = "id=" .$last_insert_id;
        $key = "insurance_type,id";

        $makeinsert = $mysql -> update('tbl_vehiclerental_agreement',$valus ,$key, 'update', $where);
                
       
        if($makeinsert){
             $status=1;
             $_SESSION['status_id'] = $status;
             $title = 'Insert';
             $message = 'Insurance Type has been update successfully.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Insurance Type can not been update.';
        }
        
        $sql = "SELECT * FROM `tbl_vehiclerental_agreement` WHERE id=$last_insert_id";
        $fire = mysqli_query($mysql, $sql);
        $fetch = mysqli_fetch_array($fire);
        $insurance = $fetch['insurance_type'];
   
    
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['insurance_type'] = $insurance;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
        $folderPath = "uploads/rental-agreement-signature/driver-signature/";
        $image_parts = explode(";base64,", $_POST['signed']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);
        $driver = $_POST['last_insert_id'];
        $valus[0]['driver_signature'] = $file;
        $where = "id=" .$driver;
        $key = "driver_signature,id";

        $makeinsert = $mysql -> update('tbl_vehiclerental_agreement',$valus ,$key, 'update', $where);
                
       
        if($makeinsert){
             $status=1;
             $_SESSION['status_id'] = $status;
             $title = 'Insert';
             $message = 'Signature has been update successfully.';
        }
        else{
            $status=0;
            $title = 'Insert Error';
            $message = 'Signature can not been update.';
        }
   
    
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'rentalaggreementsignature2')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $folderPath = "uploads/rental-agreement-signature/lessor-signature/";
    $image_parts = explode(";base64,", $_POST['signed']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    $driver = $_POST['last_insert_id'];
    $valus[0]['ledger_signature'] = $file;
    $where = "id=" .$driver;
    $key = "ledger_signature,id";

    $makeinsert = $mysql -> update('tbl_vehiclerental_agreement',$valus ,$key, 'update', $where);
    if($makeinsert){
         $status=1;
         $_SESSION['status_id'] = $status;
         $title = 'Insert';
         $message = 'Signature2 has been update successfully.';
    }
    else{
        $status=0;
        $title = 'Insert Error';
        $message = 'Signature2 can not been update.';
    }
    
    $name = 'updateinsert';
        
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);  
}
else if(isset($_POST['action']) && $_POST['action'] == 'VehicleaccidentForm')
{

    header("content-Type: application/json");
    
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();

    // echo $_POST['status'];
    $valus[0]['user_id'] = (int)$_POST['user_id'];
    $valus[0]['driver_id'] = (int)$_POST['driver_id'];    
    $valus[0]['vehicle_id'] = (int)$_POST['vehicle_id'];
    $valus[0]['date_occured'] = $_POST['date_occured'];
    $valus[0]['type_id'] = (int)$_POST['type_id'];
    $valus[0]['stage_id'] = (int)$_POST['stage_id'];
    $valus[0]['reference'] = $_POST['reference'];
    $valus[0]['description'] = $_POST['description'];
    $valus[0]['other_person'] = $_POST['other_person'];
    $valus[0]['other_vehicle'] = $_POST['other_vehicle'];
    $valus[0]['contact'] = $_POST['contact'];
    $valus[0]['other_notes'] = $_POST['other_notes'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    if($_POST['status'] == "Insert")
    {
        
        $makeinsert = $mysql -> insertre('tbl_accident',$valus);

        $countfiles = count($_FILES['files']['name']);
       
        $upload_location = "uploads/accidentimage/";

        $files_arr = array();

        for($index = 0;$index < $countfiles;$index++)
        {
            if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != '')
            {
                $filename = $_FILES['files']['name'][$index];
            
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
                $valid_ext = array("png","jpeg","jpg");

                if(in_array($ext, $valid_ext)){
                     $path = $upload_location.uniqid().$filename;
                     if(move_uploaded_file($_FILES['files']['tmp_name'][$index],$path))
                     {
                        $files_arr[] = $path;
                        $valuss[0]['accident_id'] = $makeinsert;
                        $valuss[0]['insert_date'] = date('Y-m-d H:i:s');
                        $valuss[0]['file'] = $filename;
                        $makeinsert1 = $mysql -> insert('tbl_accident_image',$valuss);   
                    }
                }
            }
        }

        if($makeinsert){
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


    elseif($_POST['status'] == "update")
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('driver_id','vehicle_id','date_occured','type_id','stage_id','reference','description','other_person','other_vehicle','contact','other_notes','update_date');
        $where = 'id ='. $_POST['id'];
        $statuupdate = $mysql -> update('tbl_accident',$valus,$usercol,'update',$where);

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
}

else if(isset($_POST['action']) && $_POST['action'] == 'vendorForm')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['userid'] = $_POST['userid'];   
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['email']=$_POST['email'];
    $valus[0]['contact'] = $_POST['contact'];
    $valus[0]['address'] = $_POST['address'];
    $valus[0]['password'] = $_POST['password'];
    $valus[0]['confirm_password'] = $_POST['confirm_password'];
    $role_Id = $_POST['roleid'];
    $valus[0]['roleid'] = $_POST['roleid'];
    $valus[0]['ispasswordchange'] = 1;

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');
        $role =  $mysql -> selectWhere('tbl_role','id','=',$role_Id,'int');
        $catresult = mysqli_fetch_array($role);
        $valus[0]['role_type'] = $catresult['role_type'];
        $statusinsert = $mysql -> insert('tbl_user',$valus);
        if($statusinsert)
        {
            $status=1;
            $email =$valus[0]['email'];
            $password = $valus[0]['password'];
            $name = $valus[0]['name'];

            $link = "<a href='".$webroot."reset-password.php?key=".$statusinsert."&token=".$token."'>
            Click here To set password</a>";

            $emlusername='noreply@drivaar.com';
            $emlpassword='DrivaarInvitation@123';
            $emltitle = 'Drivaar';
            $subject = "Drivaar : Please login with given credentials.";
            $body = 'Please login with given credentials.';

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
                    1. Sign In using omar.ismai@outlook.com. Make sure to save this email so that you can access this link later.<br> <u><b>Please login with this credentials: </b></u> Username: '.$email.' Password: '.$password.'.<br>
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
                $title = 'Insert';
                $message = 'Email sent to User And Data has been inserted.';
                    
            } 
            catch (phpmailerException $e) 
            {
                $status=0;
                $title = 'Email not Sent';
                $message = 'Failed to sent mail.';
                exit;
            } 
            catch (Exception $e) 
            {
                $status=0;
                $title = 'Email not Sent';
                $message = 'Failed to sent mail.';
                exit;   
            }  
            //$message = 'Data has been inserted successfully.';
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
        
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $usercol = array('roleid','role_type','name','email','contact','address','password','confirm_password','update_date');
        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();
        $mysql -> dbConnect();
        $category =  $mysql -> selectWhere('tbl_role','id','=',$role_Id,'int');
        $catresult = mysqli_fetch_array($category);
        $valus[0]['role_type'] = $catresult['role_type'];
        //print_r($valus);
        $statuupdate = $mysql -> update('tbl_user',$valus,$usercol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'resetform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['password'] = $_POST['password'];
    $valus[0]['confirm_password'] = $_POST['current_password'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');
    $valus[0]['ispasswordchange'] = 1;
    $key = $_POST['key'];
    $token = $_POST['token'];
        $makecol = array('password','confirm_password','ispasswordchange','update_date');

        $where = "id ='$key' AND reset_link_token ='$token'";

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_user',$valus,$makecol,'update',$where);

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
    $result = $mysql -> selectWhere('tbl_user','email','=',$email,'char');
    $row= mysqli_fetch_array($result);
    //print_r($row);
    if($row) {
        $token = md5($email).rand(10,9999);
        $where = "email = '$email'";
        $valus[0]['reset_link_token'] = $token;
        $update = $mysql -> update('tbl_user', $valus, 'reset_link_token', 'update', $where);

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
                        <h2><p style="background-color:#03a9f3; text-align: center;color:#ffffff;"> Dear '.$name.'</p></h2>
                    <p>
                        Drivaar has invited you to deliver packages on behalf of Drivaar.
                    </p>

                     <p>
                        Please complete these tasks to accept your invitation: 
                     </p>
                    </p>
                    1. Sign In using omar.ismai@outlook.com. Make sure to save this email so that you can access this link later.<br><u><b>We received a request to reset your Drivaar password :</b></u>'.$link.'.<br>
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
              $title = 'Email Sent';
              $message = 'Check Your Email and Click on the link sent to your email.';
                
        } 
        catch (phpmailerException $e) 
        {
            $status=0;
            $title = 'Email not Sent';
            $message = $mail->ErrorInfo;
            exit;
        } 
        catch (Exception $e) 
        {
            exit;   
        }  
    }
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['link'] = $link;
    echo json_encode($response); 

}
//bhumika changes
// else if(isset($_POST['action']) && $_POST['action'] == 'vehiclestatusform')
// {

//     header("content-Type: application/json");
//     $status=0;
//     $title = 'Error';
//     $message = 'Something Went Wrong';
//     $name = '';

//     $valus[0]['name'] = $_POST['name'];
//     $valus[0]['colorcode']=$_POST['colorcode'];

//     if(isset($_POST['insert']))
//     {
//         $valus[0]['insert_date'] = date('Y-m-d H:i:s');
//         $mysql = new Mysql();
//         $mysql -> dbConnect();

//         $statusinsert = $mysql -> insert('tbl_vehiclestatus',$valus);
//         if($statusinsert)
//         {
//             $status=1;
//             $title = 'Insert';
//             $message = 'Data has been inserted successfully.';
//         }
//         else
//         {
//             $status=0;
//             $title = 'Insert Error';
//             $message = 'Data can not been inserted.';
//         }
//         $name = 'Insert';

//         $mysql -> dbDisConnect();
//     }
//     else if(isset($_POST['update']))
//     {
//         $valus[0]['update_date'] = date('Y-m-d H:i:s');;

//         $makecol = array('name','update_date');

//         $where = 'id ='. $_POST['id'];

//         $mysql = new Mysql();

//         $mysql -> dbConnect();

//         $statuupdate = $mysql -> update('tbl_vehiclestatus',$valus,$makecol,'update',$where);

//         if($statuupdate)
//         {
//             $status=1;
//             $title = 'Update';
//             $message = 'Data has been updated successfully.';
//         }
//         else
//         {
//             $status=0;
//             $title = 'Update Error';
//             $message = 'Data can not been updated.';
//         }
//         $name = 'Update';
//         $mysql -> dbDisConnect();

//     }

//     $response['status'] = $status;
//     $response['title'] = $title;
//     $response['message'] = $message;
//     $response['name'] = $name;
//     echo json_encode($response); 
// }
else if(isset($_POST['action']) && $_POST['action'] == 'Adddepartment')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['name'] = $_POST['dept'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statusinsert = $mysql -> insertre('tbl_cost_department',$valus);
    $result = $mysql->selectWhere('tbl_cost_department','id','=',$statusinsert,'int');
    $deptname = mysqli_fetch_array($result);
    
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['deptname'] = $deptname['name'];
    $response['deptid'] = $deptname['id'];
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Addlocation')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['name'] = $_POST['loc'];
    $valus[0]['dept_id'] = $_POST['dept'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statusinsert = $mysql -> insertre('tbl_cost_location',$valus);
    $result = $mysql->selectWhere('tbl_cost_location','id','=',$statusinsert,'int');
    $deptname = mysqli_fetch_array($result);
    
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['locname'] = $deptname['name'];
    $response['locid'] = $deptname['id'];
    $response['deptid'] = $deptname['dept_id'];
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Addservice')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $valus[0]['loc_id'] = $_POST['loc'];
    $valus[0]['dept_id'] = $_POST['dept'];
    $valus[0]['name'] = $_POST['ser'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statusinsert = $mysql -> insertre('tbl_cost_service',$valus);
    $result = $mysql->selectWhere('tbl_cost_service','id','=',$statusinsert,'int');
    $deptname = mysqli_fetch_array($result);
    
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
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['sername'] = $deptname['name'];
    $response['serid'] = $deptname['id'];
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'Costcenterform')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';


    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['type'] = $_POST['type'];
    $valus[0]['dept_id'] = $_POST['dept'];
    $valus[0]['loc_id'] = $_POST['loc'];
    $valus[0]['ser_id'] = $_POST['service'];
    $valus[0]['payment_type'] = $_POST['paymenttype'];
    if($_POST['paymenttype']==0)
    {
        $valus[0]['amount'] = $_POST['amount'];
    }
    
    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_costcenter',$valus);
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

        $makecol = array('userid','type','dept_id','loc_id','ser_id','payment_type','amount','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_costcenter',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'BulkDriversform')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $date = date('Y-m-d',strtotime($_POST['bulkdate']));
    $status = $_POST['bulk_status'];
    $cid = implode(",", $_POST['driverid']);
    $colno = $_POST['colno'];
  
    if(isset($_POST['insert']))
    {  
        $i = 0;
        //$rotauniqid="'td_".$_SESSION['userid'].'_'.$driverid.'_'.$colno.'_'.$_POST['bulkdate']."'";
        foreach ($_POST['driverid'] as $driverid) {

            $valus[$i]['cid'] = $driverid;
            $valus[$i]['userid'] = $_SESSION['userid'];
            $valus[$i]['uniqid'] = $date.'-0-'.$driverid;
            $valus[$i]['rota_uniqid'] = 'td_0_'.$driverid.'_'.$colno.'_'.$_POST['bulkdate'];
            $valus[$i]['status_id'] = $status; 
            $valus[$i]['date'] = $date;
            $valus[$i]['insert_date'] = date('Y-m-d H:i:s');
            $i++;
        }

        $mysql = new Mysql();
        $mysql -> dbConnect();


        $extra = "ON DUPLICATE KEY UPDATE status_id=$status";
        $makeinsert = $mysql -> OnduplicateInsert('tbl_contractortimesheet',$valus,$extra);

        //$makeinsert = $mysql -> insert('tbl_contractortimesheet',$valus);
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

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'repaircostform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['vehicle_id'] = $_POST['vehicleid'];
    $valus[0]['cost'] = $_POST['cost'];
    $valus[0]['excess_amount'] = $_POST['excess'];
    $valus[0]['paid_at'] = date('Y-m-d H:i:s',strtotime($_POST['paid_at']));
    //$valus[0]['paid_at'] = date_format($_POST['paid_at'],"Y-m-d H:i:s");

    $valus[0]['reference_invoice'] = $_POST['reference'];
    

    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_vehicledamage_cost',$valus);
        if($makeinsert)
        {
            $status = 1;
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

        $makecol = array('cost','excess_amount','paid_at','reference_invoice','update_date');

        $where = 'id ='. $_POST['id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_vehicledamage_cost',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'conditionalreportform')
{
    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['userid'] = $_POST['userid'];
    $valus[0]['vehicle_id'] = $_POST['vehicleid'];
    if($_POST['driver']!='%')
    {
        $valus[0]['driver_id'] = $_POST['driver'];
    }
    $valus[0]['odometer'] = $_POST['odometer'];
    $valus[0]['fuel'] = $_POST['fuel'];
    $valus[0]['front_left_tyre'] = $_POST['front_left_tyre'];
    $valus[0]['front_right_tyre'] = $_POST['front_right_tyre'];
    $valus[0]['back_left_tyre'] = $_POST['back_left_tyre'];
    $valus[0]['back_right_tyre'] = $_POST['back_right_tyre'];
    $valus[0]['vehiclephoto'] = $_POST['attachments'];
    $valus[0]['driversign'] = $_POST['signature64'];
    $valus[0]['conductsign'] = $_POST['signature642'];
    $valus[0]['uniqueid'] = $_POST['uniqueid'];
    
    $uniqid = uniqid($valus[0]['uniqueid']);
    $folderPath = "uploads/conditionalreport/signature/";

    $image_parts = explode(";base64,", $_POST['signature64']);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . $uniqid . '.'.$image_type;
    file_put_contents($file, $image_base64);
    $valus[0]['driversign'] =  $uniqid . '.'.$image_type;

    $uniqid = uniqid($valus[0]['uniqueid']."123");
    $image_parts1 = explode(";base64,", $_POST['signature642']);
    $image_type_aux1 = explode("image/", $image_parts1[0]);
    $image_type1 = $image_type_aux1[1];
    $image_base641 = base64_decode($image_parts1[1]);
    $file1 = $folderPath . $uniqid . '.'.$image_type1;
    file_put_contents($file1, $image_base641);
    $valus[0]['conductsign'] = $uniqid . '.'.$image_type;
    

    //print_r($valus);
    if(isset($_POST['insert']))
    {
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $makeinsert = $mysql -> insert('tbl_conditionalreportdata',$valus);
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

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    $response['qry'] = $makeinsert;
    echo json_encode($response); 
}
else if(isset($_POST['action']) && $_POST['action'] == 'estimatemodelform')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';
    $valus[0]['additional_info'] = $_POST['description'];

    
        $valus[0]['update_date'] = date('Y-m-d H:i:s');;

        $makecol = array('additional_info','update_date');

        $where = 'id ='. $_POST['estimatemodel_id'];

        $mysql = new Mysql();

        $mysql -> dbConnect();

        $statuupdate = $mysql -> update('tbl_conditionalreportdata',$valus,$makecol,'update',$where);

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
}
else if(isset($_POST['action']) && $_POST['action'] == 'NewAssignVehicleForm')
{

    header("content-Type: application/json");
    $status=0;
    $title = 'Error';
    $message = 'Something Went Wrong';
    $name = '';

    $mysql = new Mysql();
    $mysql -> dbConnect();
       
    $valus[0]['userid'] = $_SESSION['userid'];
    $valus[0]['tduniqid'] = $_POST['tdid'];
    $valus[0]['vehicle_id'] = $_POST['vid'];
    $valus[0]['driver_id'] = $_POST['driver'];
    $valus[0]['pickup_date'] = $_POST['start_date'];
    $valus[0]['return_date'] = $_POST['end_date'];
    $valus[0]['price_per_day'] = $_POST['amount'];
    $valus[0]['depot_id'] = $_POST['DptId'];
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    $cquery = "SELECT * FROM `tbl_vehicles` WHERE `id`=".$_POST['vid'];
    $crow =  $mysql->selectFreeRun($cquery);
    $dh = mysqli_fetch_array($crow);
    //$valus[0]['price_per_day'] = $_POST['amount'];

    if($_POST['driver']>0)
    {
        if($_POST['start_date'] && $_POST['end_date'])
        {
            $qrychk = $mysql -> selectFreeRun("SELECT count(*) AS contra FROM `tbl_vehiclerental_agreement` 
                                WHERE `isdelete`=0 AND `isactive`=0 
                                AND ((`pickup_date`>='".$_POST['start_date']."' AND `return_date`<='".$_POST['start_date']."' ) OR
                                (`pickup_date`<='".$_POST['end_date']."' AND `return_date`>='".$_POST['end_date']."' ))
                                AND driver_id='".$_POST['driver']."'");
            $countdata = mysqli_fetch_array($qrychk);


            $qrychk1 = $mysql -> selectFreeRun("SELECT count(*) AS contra FROM `tbl_vehiclerental_agreement` 
                                WHERE `isdelete`=0 AND `isactive`=0 
                                AND ((`pickup_date`>='".$_POST['start_date']."' AND `return_date`<='".$_POST['start_date']."' ) OR
                                (`pickup_date`<='".$_POST['end_date']."' AND `return_date`>='".$_POST['end_date']."' ))
                                 AND vehicle_id='".$_POST['vid']."'");
            $countdata1 = mysqli_fetch_array($qrychk1);

            if($countdata['contra']==0 && $countdata1['contra']==0)
            {
                $statusinsert = $mysql -> insert('tbl_vehiclerental_agreement',$valus);
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
            }
            else
            {
                $status=0;
                $title = 'Insert Error';
                $message = 'Oops,Vehicle allready assigned.';
            }
       
        }
        else
        {
            $status=0;
            $title = 'Error';
            $message = 'Please,Fill all required field.';
        } 
    }else{
        $status=0;
        $title = 'Error';
        $message = 'Please Fill all required field.';
    }
    
    
    $name = 'Insert';
    $mysql -> dbDisConnect();
    
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response); 
}
?>