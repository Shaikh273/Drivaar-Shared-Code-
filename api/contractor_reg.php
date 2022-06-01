<?php
include 'DB/config.php';
date_default_timezone_set('Europe/London');
$contractor_registration=$_POST['action'];
$id = base64_decode($_POST['id']);
$status=0;
if(isset($contractor_registration) && $contractor_registration=='contractor_registration' && isset($id))
{

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $valus[0]['userid'] = 1;   
    $valus[0]['type'] = $_POST['type'];
    $valus[0]['address'] = $_POST['address'];
    $valus[0]['bank_name'] = $_POST['bank_name'];
    $valus[0]['account_number'] = $_POST['account_number'];
    $valus[0]['sort_code'] = $_POST['sort_code'];
    $valus[0]['utr'] = $_POST['utr'];
    if (isset($_POST['company'])) {
        $valus[0]['company'] = $_POST['company'];//company_id from company master
        if ($_POST['company'] != ' ') 
        {
            $companyname = $mysql -> selectWhere('tbl_contractorcompany','id','=',$_POST['company'],'int');
            $result = mysqli_fetch_array($companyname);
            $valus[0]['company_name'] = $result['name'];
        }
    }
    else {
    }
    $valus[0]['company_reg'] = $_POST['company_reg'];//company_regi_no
    //$valus[0]['email'] = $_POST['email'];
    $valus[0]['contact'] = $_POST['contact'];
    $valus[0]['iscomplated'] = 1;

    $query = "SELECT * FROM `tbl_contractor` WHERE `id` = ".$id."  AND `isdelete`= 0";
    $rows = $mysql->selectFreeRun($query);
    $fetch_rows = mysqli_num_rows($rows);
    if($fetch_rows>0)
    {
        $valus[0]['update_date'] = date('Y-m-d H:i:s');
        $prcol = array('userid','type','address','bank_name','account_number','sort_code','utr','company','company_name','company_reg','contact','update_date','iscomplated');
        $where = 'id =' . $id;
        $statusinsert = $mysql->update('tbl_contractor', $valus, $prcol, 'update', $where);

       // $statusinsert = $mysql -> insertre('tbl_contractor',$valus);
        if($statusinsert)
        {
            $status=1;
            $title = 'Insert';
            $message = 'Data has been inserted successfully.';

            $cnt_reg = [
                "registration_status"=> 1,
                "Insert" => 'Success',
                "message"=> 'Data has been inserted successfully.',
            ];

        }
        else
        {
            $status=0;
            $cnt_reg = [
                "registration_status"=> 0,
                "Insert" => 'Error',
                "message"=> 'Data can not inserted successfully.',
            ];
        }
    }
    else
    {
        $status=0;
        $cnt_reg = [
                "registration_status"=> 0,
                "Insert" => 'Insert Error',
                "message"=> 'Please Check Your Onboard Contractor.',
        ];
    }

    $mysql -> dbDisConnect();   
}
else
{
    $status=0;
    $cnt_reg = [
        "error"=> 'Authentication filed',
        "errorcode" => 101,
    ];
}

if($status==1)
{
    $array = array("registration_status" => $cnt_reg);
}
else
{
    $array = array("registration_status" =>$cnt_reg);
}
echo json_encode(array('status' => $status,'data' => $array));
?>