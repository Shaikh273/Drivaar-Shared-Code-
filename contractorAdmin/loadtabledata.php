<?php

if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}
include 'DB/config.php';
include("../home/invoiceAmountClass.php");
$mysql = new Mysql();
$db = $mysql -> dbConnect();
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

function getStartAndEndDate($week, $year) 
{
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}


function ContractorInvoiceTotal($id)
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $tblquery = "SELECT DISTINCT ci.`id`,ci.`week_no`,ct.`rateid`,ct.`date`,ct.`value`,ct.`ischeckval`,p.`name`,p.`type`,p.`amount`,p.`vat` FROM `tbl_contractorinvoice` ci 
                INNER JOIN `tbl_contractortimesheet` ct ON ct.`date` BETWEEN ci.`from_date` AND ci.`to_date` 
                INNER JOIN `tbl_paymenttype` p ON p.`id`=ct.`rateid`
                WHERE ci.`isdelete`=0 AND ci.`isactive`=0 AND ci.`id`=".$id." ORDER BY p.`type` ASC";

    $tblrow =  $mysql -> selectFreeRun($tblquery);
    $finaltotal=0;
    $totalnet=0;
    $totalvat=0;
    while($tblresult = mysqli_fetch_array($tblrow))
    {

        $net = $tblresult['amount']*$tblresult['value'];
        $vat = ($net*$tblresult['vat'])/100;
        $total = $net+$vat;
       
        $finaltotal+=$total;
        $totalnet+=$net;
        $totalvat+=$vat;
    }
    $mysql -> dbDisConnect(); 
    return  $finaltotal;
}


if(isset($_POST['action']) && $_POST['action'] == 'loadtablecontractordocumentdata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = "and (name LIKE '%".$searchValue."%' or file LIKE '%".$searchValue."%')";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_SESSION['cid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_contractordocument` WHERE `contractor_id`= ".$cid." AND `isdelete` = 0 AND isactive = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(*) as allcount FROM `tbl_contractordocument` WHERE `contractor_id`= ".$cid." AND `isdelete` = 0 AND isactive = 0".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    if($columnName!="Sno" && $columnName!="")
    {
            $ord = $columnName." ".$columnSortOrder.", ";
    }
    $query = "SELECT * FROM `tbl_contractordocument` WHERE `isdelete`=0 AND `contractor_id`= ".$cid."" .$searchQuery. " order by id ".$columnSortOrder.", id desc limit ".$row.",".$rowperpage;

    // $mysql->selectWhere2("tbl_contractordocument","*","`isdelete`= 0 AND `contractor_id`= ".$cid."" .$searchQuery. " order by id ".$columnSortOrder. ", id desc limit ".$row.",".$rowperpage);
    $row12 =  $mysql -> selectFreeRun($query);
    $data = array();
    $i=$row+1;
    while($userresult = mysqli_fetch_array($row12))
    {
        $view = "<a target = '_blank' href='".$webroot.'uploads/contractordocument/'.$userresult['file']."' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>";

        $data[] = [
                    'srno'=>$i,
                    'name'=>$userresult['name'],
                    'file'=>$userresult['file'],
                    'action'=>$view
                   
                  ];
                   $i++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'srno'=>"",
                'name'=>"",
                'file'=>"",
                'action'=>""
               ];
            $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadrerportaccidenttabledata')
{
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`name` LIKE '%".$searchValue."%' or `vehicle_plat_number` LIKE '%".$searchValue."%' ) ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_SESSION['cid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_reportaccident` WHERE `cid`=".$cid;
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(*) as allcount FROM `tbl_reportaccident` WHERE `cid`=".$cid;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_reportaccident` WHERE `isdelete`=0 AND `cid`=".$cid.$searchQuery." order by `id` desc limit ".$row.",".$rowperpage;
    $typerow =  $mysql -> selectFreeRun($query);
    $data = array();
    $i = $row+1;
    while($typeresult = mysqli_fetch_array($typerow))
    {
        $id = $typeresult['id'];
        
        // <a href='report_accident.php?id=$id' class='edit'><span><i class='fas fa-edit fa-lg'></i></span></a>

        $action = "<a href='#' class='delete' onclick=\"deleterow('".$typeresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
                    'srno'=>$i,
                    'vehicle_plat_number'=>$typeresult['vehicle_plat_number'],
                    'date'=>$typeresult['insert_date'],
                    'reference'=>$typeresult['name'],
                    'info'=>$typeresult['description_accident'],
                    'Action'=>$action 
                  ];
                  $i++;
    }

    if(count($data)==0)
    {
        $data[] = [
            'srno'=>"",
            'vehicle_plat_number'=>"",
            'date'=>"",
            'reference'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
            'info'=>"",
            'Action'=>""];
        $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadraisetickettabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (tbl_issuetype.issuetype LIKE '%".$searchValue."%' or tbl_department.department LIKE '%".$searchValue."%' or tbl_addraiseticket.commit LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_SESSION['cid'];
        
    $sql1 = "SELECT count(tbl_addraiseticket.`id`) as allcount FROM tbl_addraiseticket INNER JOIN tbl_issuetype ON tbl_issuetype.`id` = tbl_addraiseticket.`issue` INNER JOIN tbl_department ON tbl_department.`issuetypeId` = tbl_addraiseticket.`issue` WHERE tbl_addraiseticket.`id` AND tbl_addraiseticket.`isDelete`=0 AND tbl_addraiseticket.`cid`=".$cid;
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    
    $sql2 = "SELECT count(tbl_addraiseticket.`id`) as allcount FROM tbl_addraiseticket INNER JOIN tbl_issuetype ON tbl_issuetype.`id` = tbl_addraiseticket.`issue` INNER JOIN tbl_department ON tbl_department.`issuetypeId` = tbl_addraiseticket.`issue` WHERE tbl_addraiseticket.`id` AND tbl_addraiseticket.`isDelete`=0 AND tbl_addraiseticket.`cid`=".$cid.$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    

    $query12 = "SELECT tbl_addraiseticket .*, tbl_issuetype.`issuetype`, tbl_department.`department` FROM tbl_addraiseticket INNER JOIN tbl_issuetype ON tbl_issuetype.`id` = tbl_addraiseticket.`issue` INNER JOIN tbl_department ON tbl_department.`issuetypeId` = tbl_addraiseticket.`issue` WHERE tbl_addraiseticket.`id` AND tbl_addraiseticket.`isDelete`=0 AND tbl_addraiseticket.`cid`=".$cid.$searchQuery."order by tbl_addraiseticket.id ".$columnSortOrder.", tbl_addraiseticket.id desc limit ".$row.",".$rowperpage;
    // $mysql->selectWhere2("tbl_addraiseticket","*","is_active=0 AND `cid`=".$cid.$searchQuery. " order by id ".$columnSortOrder. ", id desc limit ".$row.",".$rowperpage);
    $row12 =  $mysql -> selectFreeRun($query12);
    $data = array();
    $i=$row+1;
    
    while($userresult = mysqli_fetch_array($row12))
    {

        $status = "<button class='btn btn-primary'>".$userresult['status']."</button>";

        $response_status = $userresult['status'];
        if($response_status == "1")
        {
           $resp_status =  '<span class="label label-info text-sm">PROCESSING</span>';
        }
        elseif ($response_status == "4") 
        {
            $resp_status =  '<span class="label label-success text-sm">OPEN</span>';
        } 
        elseif ($response_status == "5")   
        {
            $resp_status =  '<span class="label label-warning text-sm">DISPUTE</span>';
        }
        elseif ($response_status == "3")   
        {
            $resp_status =  '<span class="label label-danger text-sm">CLOSE</span>';
        }
        elseif ($response_status == "2")   
        {
            $resp_status =  '<span class="label label-success text-sm">ASCOLET</span>';
        }

        $history = "<button class='btn btn-primary' name='status' id='status' type='' onclick=\"editstatus('".$userresult['id']."')\">History</button>";
        $ticketid = $userresult['id'];    

        // $sql = "SELECT tbl_issuetype.`issuetype`, tbl_department.`department` FROM tbl_addraiseticket INNER JOIN tbl_issuetype ON tbl_issuetype.`id` = tbl_addraiseticket.`issue` INNER JOIN tbl_department ON tbl_department.`issuetypeId` = tbl_addraiseticket.`issue` WHERE tbl_addraiseticket.`id`=$ticketid ";
        // $sel1 =  $mysql -> selectFreeRun($sql);
        // $records1 = mysqli_fetch_array($sel1);

      
        $data[] = [
                    'issue'=>$userresult['issuetype'],
                    'department'=>$userresult['department'],
                    'commit'=>$userresult['commit'],
                    'status'=>$resp_status,
                    'expond'=>$history
                   
                  ];
                   $i++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'issue'=>"",
                'department'=>"",
                'commit'=>"<img src='drivaarpic/document.PNG' alt='' width='200' height='80' class='mt-5' style='margin-left: 155px;'>",
                'status'=>"",
                'expond'=>""
               ];
            $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadvehicleoffencestabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (identifier LIKE '%".$searchValue."%' or amount LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_POST['cid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_vehicleoffences` WHERE `driver_id`=".$cid." AND isdelete = 0";

    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(*) as allcount FROM `tbl_vehicleoffences` WHERE `driver_id`=".$cid." AND isdelete = 0".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT *, DATE_FORMAT(`occurred_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleoffences` WHERE `driver_id`=".$cid." AND `isdelete`= 0".$searchQuery." order by ".$columnName." ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;

    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        $id = $userresult['id'];
        
        
        if($userresult['isactive']==0)
        {
            $statuscls = 'success';
            $statusname = 'Active';
        }
        else
        {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }
        $id = $userresult['id'];
        

        $statustd= "<div id='".$userresult['id']."-td'>
                        <button type='button' class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','WorkforceMetricisactive')\">".$statusname."</button>
                    </div>";
                    
        $vehicle = "SELECT v.*,c.`name`,s.`registration_number` FROM tbl_vehicleoffences v INNER JOIN tbl_contractor c ON c.`id`= v.`driver_id` INNER JOIN tbl_vehicles s ON s.`id`=v.`vehicle_id` WHERE v.`id`=$id AND v.`isdelete`=0";
        $fire =  $mysql -> selectFreeRun($vehicle);
        $records1 = mysqli_fetch_array($fire);
        
        $vehicle_idrow = "".$records1['registration_number']."<br>".$records1['name']."" ."-".$userresult['identifier'];

        $action = "<div class='row'><div class='col-md-1'><a href='assetupdate.php?id=".$userresult['id']."' class='edit pl-2' onclick=\"edit('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a></div>
            <div class='col-md-3 pl-4'>
                <button class='btn btn-primary'  style='height: 30px;' onclick=\"getmdl('".$userresult['id']."')\" id='".$userresult['id']."' name='assign_to' value='".$userresult['id']."' type='button'>View</button></div>
            <div class='col-md-1'><a href='#' class='delete pl-2' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a></div>
        </div>";

        $data[] = [
                    'occurred_date' =>$userresult['date1'],    
                    'vehicle_id'=>$vehicle_idrow,
                    'pcnticket_typeid'=>$userresult['pcnticket_typeid'],
                    'amount' =>$userresult['amount'],
                    'admin_fee'=>$userresult['admin_fee'],
                    'status'=>$userresult['status']
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'occurred_date'=>"",
                'vehicle_id'=>"",
                'pcnticket_typeid'=>"<img src='drivaarpic/document.PNG' alt='' width='200' height='80' class='mt-5' style='margin-left: 155px;'>",
                'amount'=>"",
                'admin_fee'=>"",
                'status'=>"",
                ];
            $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadticketstatustabledata')
{
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`name` LIKE '%".$searchValue."%' or `vehicle_plat_number` LIKE '%".$searchValue."%' ) ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_SESSION['cid'];
    $ticketid = $_POST['ticket_id'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_ticketstatus` WHERE `ticketId`=$ticketid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "SELECT count(*) as allcount FROM `tbl_ticketstatus` WHERE `ticketId`=$ticketid";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT * FROM `tbl_ticketstatus` WHERE `ticketId`=$ticketid".$searchQuery." order by `id` desc limit ".$row.",".$rowperpage;

    $typerow =  $mysql -> selectFreeRun($query);
    $data = array();
    $i = $row+1;
    while($typeresult = mysqli_fetch_array($typerow))
    {

        // $action = "<a href='' class='delete'><span><b>View</b></span></a>
        //             &nbsp";

        $data[] = [
                    'commet'=>$typeresult['commet'],
                    'status'=>$typeresult['status'],
                    'date'=>$typeresult['insert_date'],
                   
                  ];
                  $i++;
    }

    if(count($data)==0)
    {
        $data[] = [
            'commet'=>"",
            'status'=>"$query",
            'date'=>""
            ];
        $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadvehicleOffencestabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (occurred_date LIKE '%".$searchValue."%' or amount LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "select count(*) as allcount from tbl_vehicleoffences where isdelete = 0 AND isactive = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehicleoffences WHERE isdelete = 0 AND isactive = 0".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT *, DATE_FORMAT(`occurred_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleoffences` WHERE `isdelete`= 0".$searchQuery." order by ".$columnName." ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        $id = $userresult['id'];
        
        
        if($userresult['isactive']==0)
        {
            $statuscls = 'success';
            $statusname = 'Active';
        }
        else
        {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }
        
        $id = $userresult['id'];
        

        $statustd= "<div id='".$userresult['id']."-td'>
                        <button type='button' class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','WorkforceMetricisactive')\">".$statusname."</button>
                    </div>";
                    
        $vehicle = "SELECT v.*,c.`name`,s.`registration_number` FROM tbl_vehicleoffences v INNER JOIN tbl_contractor c ON c.`id`= v.`driver_id` INNER JOIN tbl_vehicles s ON s.`id`=v.`vehicle_id` WHERE v.`id`=$id AND v.`isdelete`=0";
        $fire =  $mysql -> selectFreeRun($vehicle);
        $records1 = mysqli_fetch_array($fire);
        
        $vehicle_idrow = "".$records1['registration_number']."<br>".$records1['name']."" ."-".$userresult['identifier'];

        $action = "<div class='row'><div class='col-md-1'><a href='assetupdate.php?id=".$userresult['id']."' class='edit pl-2' onclick=\"edit('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a></div>
            <div class='col-md-3 pl-4'>
                <button class='btn btn-primary'  style='height: 30px;' onclick=\"getmdl('".$userresult['id']."')\" id='".$userresult['id']."' name='assign_to' value='".$userresult['id']."' type='button'>View</button></div>
            <div class='col-md-1'><a href='#' class='delete pl-2' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a></div>
            </div>";

        $data[] = [
                    'occurred_date' =>$userresult['date1'],    
                    'vehicle_id'=>$vehicle_idrow,
                    'pcnticket_typeid'=>$userresult['pcnticket_typeid'],
                    'amount' =>$userresult['amount'],
                    'admin_fee'=>$userresult['admin_fee'],
                    'Action'=>$action
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'occurred_date'=>"",
                'vehicle_id'=>"",
                'pcnticket_typeid'=>"No Record Found!!!",
                'amount'=>"",
                'admin_fee'=>"",
                'Action'=>"",
                ];
            $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadrentalagrementtabledata')
{
   ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (vehicle_reg_no LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "select count(*) as allcount from tbl_vehiclerental_agreement  WHERE isdelete = 0 AND isactive = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_vehiclerental_agreement  WHERE isdelete = 0 AND isactive = 0".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date1 , DATE_FORMAT(`pickup_date`,'%D %M%, %Y') as pickupdate, DATE_FORMAT(`return_date`,'%D %M%, %Y') as returndate FROM `tbl_vehiclerental_agreement` WHERE `isdelete`= 0".$searchQuery." order by id ".$columnSortOrder. ", id desc limit ".$row.",".$rowperpage;
//  print_r($query);
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        
        $sql = "SELECT tbl_vehiclegroup.`name` FROM tbl_vehiclegroup INNER JOIN tbl_vehicles ON tbl_vehicles.`group_id` = tbl_vehiclegroup.`id` INNER JOIN tbl_vehiclerental_agreement ON tbl_vehiclerental_agreement.`vehicle_reg_no` = tbl_vehicles.`registration_number` WHERE tbl_vehiclerental_agreement.`vehicle_reg_no`='".$userresult['vehicle_reg_no']."'";
        $fire = $mysql -> selectFreeRun($sql);
        $fetch = mysqli_fetch_array($fire);
        $userid = $userresult['id'];
        $sql1 = "SELECT tbl_contractor.`type`,tbl_contractor.`name` FROM tbl_contractor INNER JOIN tbl_vehiclerental_agreement ON tbl_contractor.`id` = tbl_vehiclerental_agreement.`driver_id` WHERE tbl_vehiclerental_agreement.`id`=$userid";
        $fire1 = $mysql -> selectFreeRun($sql1);
        $fetch1 = mysqli_fetch_array($fire1);
        if($fetch1['type'] == "1"){
            $type = "Self-Employed";
        }else if($fetch1['type'] == "2"){
            $type = "Limited Company";
        }else{
            $type="error";
        }

       $vehicle_reg_no = $userresult['vehicle_reg_no'];

       $name = "".$fetch1['name']."<br><label style='color: #a5a1a1f7;'>$type</label>";
       
       $location = "SELECT v.*,d.`name` as depotname, s.`name` as suppliername FROM `tbl_vehicles` v INNER JOIN `tbl_depot` d ON d.`id`=v.`depot_id` INNER JOIN `tbl_vehiclesupplier` s ON s.`id`= v.`supplier_id` WHERE v.`id`=".$userresult['vehicle_id'];
       $fire2 = $mysql -> selectFreeRun($location);
       $fetch2 = mysqli_fetch_array($fire2);
       
       $vehicle = "".$fetch2['suppliername']." ($vehicle_reg_no) "." <label class='rounded-lg col-sm-3 text-center badge badge-primary text-wrap' style='font-weight: bold; color:white'>".$fetch['name']."</label>";
       
       
       if($userresult['isactive']==0)
        {
            $statuscls = 'success';
            $statusname = 'Active';
        }
        else
        {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }
        
       $status="<div id='".$userresult['id']."-td'>
                        <button type='button' class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','RentalAgreementisactive')\">".$statusname."</button>
                    </div>";
       
       $action = "<a href='#' class='delete' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            
                    'hirer'=>$name,   
                    'vehicle_reg_no'=>$vehicle,
                    'location'=>$fetch2['depotname'],
                    'pickup_date'=>$userresult['pickupdate'],
                    'return_date'=>$userresult['returndate'],
                    'price_per_day'=>£. $userresult['price_per_day'],
                    ''=>$status,
                    'action'=>$action
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [

                'hirer'=>"",  
                'vehicle_reg_no'=>"",
                'location'=>"",
                'pickup_date'=>"",
                'return_date'=>"",
                'price_per_day'=>"",
                ''=>"",
                'action'=>"",
                ];
            $i++;
    }
}
else if(isset($_POST['action']) && $_POST['action'] == 'loadFinancecntInvoicestabledata')
{
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (cv.`invoice_no` LIKE '%".$searchValue."%' or cv.`week_no` LIKE '%".$searchValue."%' or DATE_FORMAT(cv.`duedate`,'%D %M%, %Y') LIKE '%".$searchValue."%')";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $cid = $_POST['cid'];
        
    // $sql1 = "SELECT count(cv.id) as allcount FROM `tbl_contractorstatusinvoice` csi INNER JOIN `tbl_contractorinvoice` cv ON cv.invoice_no=csi.invoice_no INNER JOIN `tbl_invoicestatus` is2 ON is2.id=csi.newstatus_id WHERE csi.newstatus_id IN (SELECt is1.id from tbl_invoicestatus is1 WHERE is1.contractorView NOT LIKE '0') AND csi.insert_date IN (SELECT MAX(csi1.insert_date) FROM tbl_contractorstatusinvoice csi1 WHERE csi1.cid=$cid AND csi1.invoice_no IN (SELECT DISTINCT csi2.invoice_no FROM tbl_contractorstatusinvoice csi2) GROUP BY csi1.invoice_no) AND csi.cid=$cid AND cv.`isdelete` = 0 AND cv.`istype`=1";
    $sql1 = "SELECT count(ci.id) as allcount from tbl_contractorinvoice ci INNER JOIN tbl_invoicestatus ist ON ist.id=ci.status_id WHERE ci.isdelete=0 AND (ci.status_id=4 OR ci.status_id=7 OR ci.status_id=8) AND ci.cid=$cid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    // $sql2 ="SELECT count(cv.id) as allcount FROM `tbl_contractorstatusinvoice` csi INNER JOIN `tbl_contractorinvoice` cv ON cv.invoice_no=csi.invoice_no INNER JOIN `tbl_invoicestatus` is2 ON is2.id=csi.newstatus_id WHERE csi.newstatus_id IN (SELECt is1.id from tbl_invoicestatus is1 WHERE is1.contractorView NOT LIKE '0') AND csi.insert_date IN (SELECT MAX(csi1.insert_date) FROM tbl_contractorstatusinvoice csi1 WHERE csi1.cid=$cid AND csi1.invoice_no IN (SELECT DISTINCT csi2.invoice_no FROM tbl_contractorstatusinvoice csi2) GROUP BY csi1.invoice_no) AND csi.cid=$cid AND cv.`isdelete` = 0 AND cv.`istype`=1 ".$searchQuery;
    $sql2 = "SELECT count(ci.id) as allcount from tbl_contractorinvoice ci INNER JOIN tbl_invoicestatus ist ON ist.id=ci.status_id WHERE ci.isdelete=0 AND (ci.status_id=4 OR ci.status_id=7 OR ci.status_id=8) AND ci.cid=$cid ".$searchQuery;
    // $sql2 = "select count(*) as allcount from tbl_contractorinvoice WHERE `cid`=".$cid." AND `isdelete` = 0 AND `istype`=1";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    // $query="SELECT cv.*,is2.contractorView,DATE_FORMAT(cv.`duedate`,'%D %M%, %Y') as duedate FROM `tbl_contractorstatusinvoice` csi INNER JOIN `tbl_contractorinvoice` cv ON cv.invoice_no=csi.invoice_no INNER JOIN `tbl_invoicestatus` is2 ON is2.id=csi.newstatus_id WHERE csi.newstatus_id IN (SELECt is1.id from tbl_invoicestatus is1 WHERE is1.contractorView NOT LIKE '0') AND csi.insert_date IN (SELECT MAX(csi1.insert_date) FROM tbl_contractorstatusinvoice csi1 WHERE csi1.cid=$cid AND csi1.invoice_no IN (SELECT DISTINCT csi2.invoice_no FROM tbl_contractorstatusinvoice csi2) GROUP BY csi1.invoice_no) AND csi.cid=$cid AND cv.`isdelete` = 0 AND cv.`istype`=1 ".$searchQuery;

    $query="SELECT ci.*,ist.contractorView,DATE_FORMAT(ci.`duedate`,'%D %M%, %Y') as duedate from tbl_contractorinvoice ci INNER JOIN tbl_invoicestatus ist ON ist.id=ci.status_id WHERE ci.isdelete=0 AND (ci.status_id=4 OR ci.status_id=7 OR ci.status_id=8) AND ci.cid=$cid  ".$searchQuery." order by ci.id ASC limit ".$row.",".$rowperpage;
    // $query = "SELECT *, DATE_FORMAT(`duedate`,'%D %M%, %Y') as duedate FROM `tbl_contractorinvoice` WHERE `cid`=".$cid." AND `istype`=1 AND `isdelete`= 0 ".$searchQuery." order by id ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
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
            $status1 = "<button type='button' class='btn btn-primary' disabled>Timed Out!</button>";
            
        }
        else
        {
             $status1 = "<a href='raiseTicket.php?id=$userid' class='btn btn-primary text-light' name='release'>Dispute</a>"; 
        }

        

        $vat = '';
        if($typeresult['vat']==1)
        {
            $vat ='<i class="fas fa-check fa-lg edit"></i>';
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
        // $totalshow = ContractorInvoiceTotal($typeresult['id']);

        $statusname = strtoupper($stresult['name']);
        if($typeresult['status_id']==4)
        {
            $due = $typeresult['duedate'];

        }
        else
        {
            if($todaydate>=$typeresult['duedate'])
            {
                $due = '<div class="delete"><i class="fas fa-exclamation-triangle"></i> '.$typeresult['duedate'].'</div>';
            }
            else
            {
                $due = '<div style="color: #ff7800e0;"><i class="fas fa-exclamation-triangle"></i><b> '.$typeresult['duedate'].'</b></div>';
            }
            
        }
        $b64 = base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]);
        $invkey=base64_encode($typeresult["cid"]."#1#".$typeresult["week_no"]."#".$typeresult["weekyear"]."#".$b64);
        $action = "<a href='../home/contractor_invoice.php?invkey=".$invkey."' target='_blank' class='delete'><span><b>View</b></span></a>
                    &nbsp"; 

        $data[] = [
                    'total'=>'<b>£ '.$totalshow.'</b>',
                    'status'=>"<span class='label label-secondary' style='color: ".$stresult['color'].";background-color: ".$stresult['backgroundcolor']."'><b>".$statusname."</b></span>",
                    'period'=>'<b>Wk '.$typeresult['week_no'].'</b> - <small>'.$fromdate.' >> '.$todate.'</small>',
                    'invoice_no'=>$typeresult['invoice_no'],
                    'due'=>$due,
                    'dispute'=>$status1,
                    'Action'=>$action 
                  ];
    }

    if(count($data)==0)
    {
        $data[] = [
            'total'=>"",
            'status'=>"",
            'period'=>"",
            'invoice_no'=>"<img src='drivaarpic/document.PNG' alt='' width='200' height='80' class='mt-5 ml-5'>",
            'due'=>"",
            'dispute'=>"",
            'Action'=>""
            ];
        $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadleaverequesttabledata')
{
    $cid = $_SESSION['cid'];
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (DATE_FORMAT(`start_date`,'%D %M%, %Y') LIKE '%".$searchValue."%' or DATE_FORMAT(`end_date`,'%D %M%, %Y') LIKE '%".$searchValue."%')";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "select count(*) as allcount from tbl_leaverequest  where `cid`=".$cid." AND `isdelete` = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_leaverequest  WHERE `cid`=".$cid." AND `isdelete` = 0";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT *, DATE_FORMAT(`start_date`,'%D %M%, %Y') as startdate,DATE_FORMAT(`end_date`,'%D %M%, %Y') as enddate FROM `tbl_leaverequest` WHERE `cid`=".$cid." AND `isdelete`= 0 ".$searchQuery." order by id ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;

    $typerow =  $mysql -> selectFreeRun($query);
    $todaydate = date('Y-m-d');
    $data = array();
    while($typeresult = mysqli_fetch_array($typerow))
    {

       $leave = $typeresult["status"];
       if($leave == 0)
       {
         $leavestatus = "<span class='label label-danger'>Rejected</span>";
       }
       elseif ($leave == 1) {
           $leavestatus = "<span class='label label-success'>Approved</span>";
       }
       elseif($leave == "")
       {
         $leavestatus = "";
       }
 

        $data[] = [
                    'date'=>$typeresult["startdate"] ." - ". $typeresult["enddate"],
                    'notes'=>$typeresult["notes"],
                    'status'=>$leavestatus,
                    
                  ];
    }

    if(count($data)==0)
    {
        $data[] = [
            'date'=>"",
            'notes'=>"No Data found!!",
            'status'=>""
            
            ];
        $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadtrainingstabledata')
{
    $cid = $_SESSION['cid'];
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (DATE_FORMAT(`insert_date`,'%D %M%, %Y') LIKE '%".$searchValue."%')";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "select count(*) as allcount from tbl_training  where `isdelete` = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    $sql2 = "select count(*) as allcount from tbl_training  WHERE `isdelete` = 0";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    $query = "SELECT t.*,DATE_FORMAT(t.`insert_date`,'%D %M%, %Y') as trainingdate  FROM `tbl_contractor` c  
    INNER JOIN tbl_training t ON t.`userid`=c.`userid` AND t.`isdelete`=0 WHERE c.`id`=$cid".$searchQuery." order by t.`id` ".$columnSortOrder. ", t.`id` ASC limit ".$row.",".$rowperpage;
    // print_r($query);

    $typerow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($typeresult = mysqli_fetch_array($typerow))
    {

       $leave = $typeresult["status"];
       if($leave == 0)
       {
         $leavestatus = "<span class='label label-danger'>Rejected</span>";
       }
       elseif ($leave == 1) {
           $leavestatus = "<span class='label label-success'>Approved</span>";
       }
       elseif($leave == "")
       {
         $leavestatus = "";
       }
 

        $data[] = [
                    'srno'=>$i,
                    'name'=>$typeresult["name"],
                    'conducted'=>"",
                    'training_date'=>$typeresult["trainingdate"],
                    'status'=>$leavestatus,
                    
                  ];
    }

    if(count($data)==0)
    {
        $data[] = [
            'srno'=>"",
            'name'=>"",
            'conducted'=>"No Data found!!",
            'training_date'=>"",
            'status'=>""
            
            ];
        $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadvehicleInspectiontabledata')
{
    $cid = $_SESSION['cid'];
    ## Search 
    // $depot = $_POST['depot'];
    // if($depot == "")
    // {
    //     $depot = "%";
    // }
    // $vehicle = $_POST['vehicle'];
    // if($vehicle == "")
    // {
    //     $vehicle = "%";
    // }
    $inspectiondate = $_POST['inspectiondate'];
    if($inspectiondate == "")
    {
        $inspectiondate = date('Y-m-d');
    }

    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (v.`odometer` LIKE '%".$searchValue."%' or v.`registration_number` LIKE '%".$searchValue."%'";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $vehicle_id = $_POST['vid'];

    $sql2 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
     INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
     WHERE vi.`isdelete`=0 AND vi.`isactive`=0 AND vi.`odometer` IS NOT NULL AND cast(vi.`insert_date` as Date) LIKE cast('" . $inspectiondate . "' as Date) AND vi.`vehicle_id`=$vehicle_id";
    $sel =  $mysql->selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql3 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id` 
    WHERE vi.`isdelete` = 0 AND vi.`isactive` = 0 AND vi.`odometer` IS NOT NULL AND cast(vi.`insert_date` as Date) LIKE cast('" . $inspectiondate . "' as Date) AND vi.`vehicle_id`=$vehicle_id" . $searchQuery;
    $sel =  $mysql->selectFreeRun($sql3);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord = "";
    $currentdate = date('d-m-Y');

    $query = "SELECT vi.*,v.`registration_number`,s.`name` as suppliername,v.`userid`, DATE_FORMAT(vi.`insert_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id`= v.`supplier_id`
    WHERE vi.`isdelete`= 0 AND vi.`isactive` = 0 AND vi.`odometer` IS NOT NULL AND cast(vi.`insert_date` as Date) LIKE cast('" . $inspectiondate . "' as Date) AND vi.`vehicle_id`=$vehicle_id";
    $userrow =  $mysql->selectFreeRun($query);
    $data = array();
    
    $chklist = "SELECT COUNT(*) AS ct1 FROM tbl_vehiclechecklist WHERE `isdelete`= 0";
    $chkfire = $mysql->selectFreeRun($chklist);
    $fetch = mysqli_fetch_array($chkfire);
    $row = (int)$fetch['ct1'] + 1;
    
    while($userresult = mysqli_fetch_array($userrow))
    {
        $oInsertId = $userresult['odometerInsert_date'];

        $entry = "SELECT COUNT(*) AS cnt2 FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "'";
        $chkentry = $mysql->selectFreeRun($entry);
        $fetch1 = mysqli_fetch_array($chkentry);
        $result = (int)$row - (int)$fetch1['cnt2'];
        if ($result == 0) {
            $sql3 = "SELECT * FROM tbl_vehicleinspection WHERE odometerInsert_date='" . $oInsertId . "' AND answer_type=0";
            $fire3 = $mysql->selectFreeRun($sql3);
            if ($fire3) {
                $rowcount = mysqli_num_rows($fire3);

                if ($rowcount > 0) {
                    $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
                } else {
                    $chek = "<span style='color: green;'><i class='fa fa-check-circle' aria-hidden='true'></i> All Check Passed</span>";
                }
            }
        } else {

            $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
        }



        if ($userresult['isactive'] == 0) {
            $statuscls = 'success';
            $statusname = 'Active';
        } else {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }

        $statustd = "<div id='" . $userresult['id'] . "-td'>
                        <button type='button' class='isactivebtn btn btn-" . $statuscls . "' 
                        onclick=\"Isactivebtn('" . $userresult['id'] . "','" . $userresult['isactive'] . "','WorkforceMetricisactive')\">" . $statusname . "</button>
                    </div>";

        $action = "<a href='vehicleinspection_showdetails.php?id=" . $userresult['id'] . "'' style='color: #03a9f3;'><span>View</span></a>";


        $data[] = [
            'date' => $userresult['date1'],
            'vehicle_id' => $userresult['registration_number'],
            //'user' => $userresult['suppliername'],
            'odometer' => $userresult['odometer'],
            'check' => $chek,
            'Action' => $action
        ];         
    }
    
    if(count($data)==0)
    {
        $data[] = [
                'date'=>"",
                'vehicle_id'=>"",
                'odometer'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
                'check'=>"",
                'Action'=>""
               ];
            $i++;
    }
}


$response = array(
    "draw" => $draw,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response,JSON_UNESCAPED_SLASHES);

?>