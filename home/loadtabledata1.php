<?php
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}
include 'DB/config.php';

if($_SESSION['userid']==1)
{
   $userid='%'; 
}
else
{
   $userid = $_SESSION['userid']; 
}


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
$mysql->dbDisconnect();

if(isset($_POST['action']) && $_POST['action'] == 'loadticketsettingtabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (tbl_user.name LIKE '%".$searchValue."%' or tbl_department.department LIKE '%".$searchValue."%' or tbl_issuetype.issuetype LIKE '%".$searchValue."%' or tbl_role.role_type LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $sql1 = "SELECT count(tbl_addraiseticket.id) as allcount FROM tbl_addraiseticket
        INNER JOIN tbl_issuetype ON tbl_issuetype.id = tbl_addraiseticket.issue
        INNER JOIN tbl_department ON tbl_department.issuetypeId = tbl_addraiseticket.issue
        INNER JOIN tbl_role ON tbl_role.id = tbl_department.role_id
        INNER JOIN tbl_contractor ON tbl_contractor.id = tbl_addraiseticket.cid
        INNER JOIN tbl_user ON tbl_user.id = tbl_contractor.userid
        WHERE tbl_addraiseticket.`isDelete`=0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(tbl_addraiseticket.id) as allcount FROM tbl_addraiseticket
        INNER JOIN tbl_issuetype ON tbl_issuetype.id = tbl_addraiseticket.issue
        INNER JOIN tbl_department ON tbl_department.issuetypeId = tbl_addraiseticket.issue
        INNER JOIN tbl_role ON tbl_role.id = tbl_department.role_id
        INNER JOIN tbl_contractor ON tbl_contractor.id = tbl_addraiseticket.cid
        INNER JOIN tbl_user ON tbl_user.id = tbl_contractor.userid
        WHERE tbl_addraiseticket.`isDelete`=0".$searchQuery;
    
    // $sql2 = "SELECT count(*) as allcount FROM `tbl_addraiseticket` WHERE `isDelete`=0".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";

    // $row12 = "SELECT ts.*,at.`id` as tcid,d.*,r.*,tis.* FROM `tbl_ticketstatus` ts 
    // INNER JOIN tbl_addraiseticket at ON at.`id` = ts.`ticketId`
    // INNER JOIN tbl_issuetype tis ON tis.`id` = at.`issue`
    // INNER JOIN tbl_department d ON d.`issuetypeId` = at.`issue`
    // INNER JOIN tbl_role r ON r.`id` = d.`role_id`
    // WHERE ts.`isdelete`=0";
   
    $row12 = "SELECT tbl_addraiseticket.*,tbl_addraiseticket.id as tcid, tbl_issuetype.issuetype, tbl_department.department,tbl_role.role_type,tbl_role.id as roleid,tbl_contractor.id,tbl_contractor.name as contractorname,tbl_contractor.userid,tbl_user.name FROM tbl_addraiseticket
        INNER JOIN tbl_issuetype ON tbl_issuetype.id = tbl_addraiseticket.issue
        INNER JOIN tbl_department ON tbl_department.issuetypeId = tbl_addraiseticket.issue
        INNER JOIN tbl_role ON tbl_role.id = tbl_department.role_id
        INNER JOIN tbl_contractor ON tbl_contractor.id = tbl_addraiseticket.cid
        INNER JOIN tbl_user ON tbl_user.id = tbl_contractor.userid
        WHERE tbl_addraiseticket.`isDelete`=0".$searchQuery." order by tbl_addraiseticket.`id` ".$columnSortOrder. ", tbl_addraiseticket.id ASC limit ".$row.",".$rowperpage;
    // $row12 = "SELECT * FROM `tbl_addraiseticket` WHERE `isDelete`=0".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $fire =  $mysql -> selectFreeRun($row12);

    $data = array();
    $i=$row+1;
    
    while($userresult = mysqli_fetch_array($fire))
    {
        $cid = $userresult['cid'];
        $id = $userresult['tcid'];
        $no_of_digit = 5;
        $number = $userresult['tcid'];
        
        
        $length = strlen((string)$number);
        for($a = $length;$a<$no_of_digit;$a++)
        {
            $number = '0'.$number;
        }

        $response_status = $userresult['status'];
        if($response_status == "1")
        {
           $resp_status =  '<span class="label label-info text-sm">'.$response_status.'</span>';
        }
        elseif ($response_status == "open") 
        {
            $resp_status =  '<span class="label label-success text-sm">'.$response_status.'</span>';
        } 
        elseif ($response_status == "disputes")   
        {
            $resp_status =  '<span class="label label-warning text-sm">'.$response_status.'</span>';
        }
        elseif ($response_status == "3")   
        {
            $resp_status =  '<span class="label label-danger text-sm">'.$response_status.'</span>';
        }





        $status = "<button class='btn btn-primary p-0 col-sm-6' name='status' type='' onclick=\"editstatus(".$id.",'".$userresult['department']."','".$userresult['roleid']."')\">Edit</button>
        <button class='btn btn-primary' name='status' id='status' type='' onclick='viewhistory(".$id.");'>History</button>";
        $roletype1="<input type='hidden' name='roletype' id='roletype' value='".$userresult['role_type']."''>";
        $roletype=$userresult['role_type'];
        
        
        $data[] = [
                    'srno'=>$i,
                    'id'=>$number,
                    'user'=>$userresult['contractorname'],
                    'role'=>$roletype,
                    'department'=>$userresult['department'],
                    'problem'=>$userresult['issuetype'],
                    'issue'=>$userresult['issue'],
                    'status'=>$resp_status,
                    'edit'=>$status,
                   
                  ];
                   $i++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'srno'=>"",
                'id'=>"",
                'user'=>"",
                'role'=>$row12,
                'department'=>"!! NO DATA FOUND !!!",
                'problem'=>"",
                'issue'=>"",
                'status'=>"",
                'edit'=>"",
               
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
        $searchQuery = " and (vf.occurred_date LIKE '%".$searchValue."%' or vf.amount LIKE '%".$searchValue."%' or vf.admin_fee LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(DISTINCT vf.`id`) as allcount 
            FROM tbl_vehicleoffences vf
            INNER JOIN `tbl_vehicles` v ON v.`id`=vf.`vehicle_id`
            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE vf.`isdelete` = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT vf.`id`) as allcount 
            FROM tbl_vehicleoffences vf
            INNER JOIN `tbl_vehicles` v ON v.`id`=vf.`vehicle_id`
            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE vf.`isdelete` = 0".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];

    $query = "SELECT DISTINCT vf.*, DATE_FORMAT(vf.`occurred_date`,'%D %M%, %Y') as date1 
             FROM `tbl_vehicleoffences` vf
             INNER JOIN `tbl_vehicles` v ON v.`id`=vf.`vehicle_id`
             INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
             WHERE vf.`isdelete`= 0".$searchQuery." order by vf.`id` ".$columnSortOrder. ", vf.id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
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

                    
        $vehicleqry = "SELECT v.`registration_number`,c.`name` FROM `tbl_vehicleoffences` o
                        INNER JOIN `tbl_vehicles` v ON v.`id`=o.`vehicle_id`
                        INNER JOIN `tbl_contractor` c ON c.`id`=o.`driver_id`
                        WHERE o.`id`=".$userresult['id']." AND o.`isdelete`=0";
        $fire =  $mysql -> selectFreeRun($vehicleqry);
        $records1 = mysqli_fetch_array($fire);
        $vehicle_idrow = "".$records1['registration_number']."<br>".$records1['name']."" ."-".$userresult['identifier'];

        $action = "<div class='row'>
                    <a href='#' class='delete pl-2' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $statustd= "<div id='".$userresult['id']."-td'>
                        <button type='button' ".$isdisabled." class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','vehicleoffencesisactive')\">".$statusname."</button>
                    </div>";            

        $data[] = [
                    'occurred_date' =>$userresult['date1'],    
                    'vehicle_id'=>$vehicle_idrow,
                    'pcnticket_typeid'=>$userresult['pcnticket_typeid'],
                    'amount' =>$userresult['amount'],
                    'admin_fee'=>$userresult['admin_fee'],
                    'status'=>$statustd,
                    'Action'=>$action,
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
                'status'=>"",
                'Action'=>"",
                ];
            $i++;
    }
}



else if(isset($_POST['action']) && $_POST['action'] == 'loadroutestabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (value LIKE '%".$searchValue."%' or route LIKE '%".$searchValue."%') ";
    }

    $mysql = new Mysql();
    $mysql -> dbConnect();
    $userid = $_SESSION['userid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_contractortimesheet` WHERE `isdelete` = 0 AND `isactive` = 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_contractortimesheet` WHERE `isdelete` = 0 AND `isactive` = 0".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";

    $query = "SELECT * FROM `tbl_contractortimesheet` WHERE isdelete=0 AND isactive=0 AND rateid=0 AND userid=$userid UNION SELECT * FROM `tbl_workforcetimesheet` WHERE isdelete=0 AND isactive=0 AND rateid=0 AND userid=$userid".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {

        $sqlqry = "SELECT row1.value as route,  COUNT(*) as routecount FROM ( SELECT * FROM `tbl_contractortimesheet` WHERE `value`='".$userresult['value']."' AND `userid`=$userid UNION SELECT * FROM `tbl_workforcetimesheet` WHERE `value`='".$userresult['value']."' AND `userid`=$userid ) AS row1 GROUP BY value";
        // echo $sqlqry;
        $fire =  $mysql -> selectFreeRun($sqlqry);
        $records3 = mysqli_fetch_array($fire);
       
        $data[] = [
                    'route' => $records3['route'],    
                    'count'=>$records3['routecount'],
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'route'=>"",
                'count'=>"",
                ];
            $i++;
    }
}



else if(isset($_POST['action']) && $_POST['action'] == 'loadticketsettingdata1')
{

    $depot_id = $_POST['depot_id'];

    if($depot_id == "")
    {
    $data[] = 
        [
        'department'=>"",
        'user'=>"!! NO DATA FOUND !!!",
        'release'=>"",
               
        ];
    }
       

    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (roleid LIKE '%".$searchValue."%' or depot LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $sql1 = "SELECT count(*) as allcount FROM `tbl_user` WHERE `isdelete` = 0 AND `isactive` = 0 AND `depot`=$depot_id";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    
    $sql2 = "SELECT count(*) as allcount FROM `tbl_user` WHERE `isdelete` = 0 AND `isactive` = 0 AND `depot`=$depot_id".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    
    // $row12 = "SELECT * FROM `tbl_user` WHERE  `depot` LIKE "'.$depot_id.'" AND `isdelete`=0".$searchQuery." order by id , id ASC limit ".$row.",".$rowperpage;
    // $fire =  $mysql -> selectFreeRun($row12);

    $data = array();
    $i=$row+1;
    
    $row13 = "SELECT * FROM `tbl_role` WHERE `isdelete`=0".$searchQuery." order by `id` , `id` ASC limit ".$row.",".$rowperpage;
    $fire1 =  $mysql -> selectFreeRun($row13);

    while($userresult1 = mysqli_fetch_array($fire1))
    {
        $roleid = $userresult1['id'];

        $name = "<select class='form-select form-select-lg  p-1' aria-label='.form-select-lg example' id='username' name='username' onchange='insertrolesetting(this.value,$roleid);'><option value='0'>".$roleuserfetch['name']."</option>";

        $user = "SELECT * FROM `tbl_user` WHERE `roleid`=$roleid AND `depot`=$depot_id AND `isdelete`=0";
        $fire2 =  $mysql -> selectFreeRun($user);
        
        while($fetch2 = mysqli_fetch_array($fire2))
        {
            $name.= '<option value='.$fetch2['id'].'>'.$fetch2['name'].'</option>';
        } 
       
        $name.= "</select>";
        
        $status = "<button class='btn btn-primary p-0 col-sm-4' name='release' type='button' onclick='updaterolesetting();'>Release All</button>";

        $data[] = [
                    'department'=>$userresult1['role_type'],
                    'user'=>$name,
                    'release'=>$status,
                   
                  ];
                   $i++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'department'=>"",
                'user'=>"!! NO DATA FOUND !!!",
                'release'=>"",
               
               ];
            $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadleaverequesttabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and ( DATE_FORMAT(l.`start_date`,'%D %M%, %Y') LIKE '%".$searchValue."%' or 
         DATE_FORMAT(l.`end_date`,'%D %M%, %Y') LIKE '%".$searchValue."%' or 
         c.`name` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(DISTINCT l.`id`) as allcount FROM tbl_leaverequest l
            INNER JOIN `tbl_contractor` c ON c.`id`= l.`cid` 
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE l.`isdelete` = 0  AND c.`depot` IN (w.`depot_id`)";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];                                

    $sql2 = "SELECT count(DISTINCT l.`id`) as allcount FROM tbl_leaverequest l 
            INNER JOIN `tbl_contractor` c ON c.`id`= l.`cid`
            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            WHERE l.`isdelete` = 0  AND c.`depot` IN (w.`depot_id`)".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];


    $query = "SELECT DISTINCT l.*, DATE_FORMAT(l.`start_date`,'%D %M%, %Y') as startdate,DATE_FORMAT(l.`end_date`,'%D %M%, %Y') as enddate,c.`name` FROM `tbl_leaverequest` l
    INNER JOIN `tbl_contractor` c ON c.`id`= l.`cid`
    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
    WHERE l.`isdelete`= 0  AND c.`depot` IN (w.`depot_id`)".$searchQuery." order by l.`id` ".$columnSortOrder. ", l.id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        $startd = new DateTime($userresult['start_date']);
        $endd = new DateTime($userresult['end_date']);
        $datetime =$startd;
        $datetime1 = $endd;
        $diff = $datetime->diff($datetime1);
        $year = $diff->y;
        $month = 12 * $diff->y + $diff->m;

        $day = $diff->days;
        $hours = $diff->h + ($diff->days * 24);
        $second = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
        $minute = (($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i);
        if($year>0)
        {
           $difftime = $diff->y . " years, " . $diff->m ." months, ". $diff->d ." days ";  
        }
        else if($month > 0)
        {
            $difftime = $month ." ".month ." ". $day ." ".days; 
        }else if($day > 0)
        {
           $difftime = $day ." ".day;  
        }
        // $difftime = $day ." ".day;  
        $date123 = date('F d, Y', strtotime($userresult['start_date']));
        $d= date('F d, Y', strtotime($userresult['end_date']));

        $period = "<strong style='color: #172b4d;'>".$date123. " - " .$d."</strong> <label style='color: #828181;'> (".$difftime.") </label>";            
        $action = "<button type='button' class='btn btn-sm btn-light' onclick=\"leaveresponse('".$userresult['id']."','0')\">Reject</button>
        <button type='submit' class='btn btn-sm btn-primary' onclick=\"leaveresponse('".$userresult['id']."','1')\">Approve</button>";

        $leave = $userresult['status'];

        if($leave == 0)
        {
            $leave = "<span class='label label-danger'>Rejected</span>";
        }elseif($leave == 1)
        {
            $leave = "<span class='label label-success'>Approved</span>";
        }else
        {
           $leave = ""; 
        }    

        $data[] = [
                    'name' =>$userresult['name'],    
                    'period'=>$period,
                    'status'=>$leave,
                    'action'=>$action,
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'name'=>"",
                'period'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
                'status'=>"",
                'action'=>"",
                ];
            $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadinspectionissuetabledata')
{
    ## Search 
    $vehiclename = $_POST['vehicle_id'];
    $question = $_POST['question'];

    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (v.registration_number LIKE '%".$searchValue."%' or vq.name LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(DISTINCT vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`id` LIKE ('".$vehiclename."')
    INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
    INNER JOIN `tbl_vehiclechecklist` vq ON vq.`id`=vi.`question_id` AND vq.`id` LIKE ('".$question."')
    WHERE vi.`isdelete`= 0 AND vi.`isactive`=0 AND `answer_type`=0";
    
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT vi.`id`) as allcount FROM tbl_vehicleinspection vi
            INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`id` LIKE ('".$vehiclename."')
            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            INNER JOIN `tbl_vehiclechecklist` vq ON vq.`id`=vi.`question_id` AND vq.id LIKE ('".$question."')
            WHERE vi.`isdelete`= 0 AND vi.`isactive`=0 AND `answer_type`=0".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
    

    $query="SELECT DISTINCT vi.*,v.`registration_number` as registrationnumber,vq.`name` as questionname
            FROM `tbl_vehicleinspection` vi 
            INNER JOIN `tbl_vehicles` v ON v.`id`=vi.`vehicle_id` AND v.`id` LIKE ('".$vehiclename."')
            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
            INNER JOIN `tbl_vehiclechecklist` vq ON vq.`id`=vi.`question_id` AND vq.id LIKE ('".$question."')
            WHERE vi.`isdelete`= 0 AND vi.`answer_type`=0".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        
        // date_default_timezone_set('Europe/London');
        $statuscheck = $userresult['status'];
        $id = $userresult['id'];

        if($statuscheck == 0)
        {
            $statuscls = 'success';
            $statusname = 'Active';
        }
        else
        {
            $statuscls = 'danger';
            $statusname = 'Inactive';
        }
        
        $status="<span type='' class='label label-".$statuscls." text-xs'>".$statusname."</span>";

        $date1 = $userresult['insert_date'];
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

        if($year>0)
        {
           $difftime = $diff->y . " years, " . $diff->m ." months, ". $diff->d ." days ";  
        }
        else if($month > 0)
        {
            $difftime = $month ." ".month ." ". $day ." ".days; 
        }else if($day > 0)
        {
           $difftime = $day ." ".day;  
        }else if($hours > 0)
        {
          $difftime = $hours ." ".hours;    
        }else if($minute > 0)
        {
            $difftime = $minute ." ".minute; 
        }else if($second > 0)
        {
           $difftime = $second ." ".seconds; 
        }else{
           $difftime = "now"; 
        }
        
        $issue1 = "<strong style='color: #172b4d; font-size: 14px;'>".$userresult['questionname']."</strong><br><label style='color: #ccc9c9;font-weight: 500;'>Reported $difftime ago</label>";

        $action = "
                    <button class='btn btn-light btn-sm col-sm-3' onclick=\"inspectionresponse('".$userresult['id']."','3')\">Close</button>
                    <button class='btn btn-primary btn-sm ml-3 col-sm-3' onclick=\"inspectionresponse('".$userresult['id']."','1')\">Resolve</button>
                   ";

        $data[] = [
                    'vehicle_id' =>"<a href='vehicle_details_show.php' class='' style='color: blue;'> ".$userresult['registrationnumber']."</a><input type='hidden' name='registerno' id='registerno' value='".$userresult['registrationnumber']."'>",    
                    'issue'=>$issue1,
                    'state'=>$status,
                    'action'=>$action,
                    
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'vehicle_id'=>"",
                'issue'=>"",
                'state'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
                'action'=>"",
                
                ];
            $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadrentalagrementtabledata')
{
    
   ## Search 
   $hirer = "";
   if(isset($_POST['hirer']) && $_POST['hirer']!='%')
   {
        $hirer = " AND c.id=".$_POST['hirer'];
   }
   $locationname = "";
   if(isset($_POST['locationname']) && $_POST['locationname']!='%')
   {
        $locationname = " AND d.id = ".$_POST['locationname'];
   }
   $statusid = "";
    if(isset($_POST['statusid']) && $_POST['statusid']!='%')
   {
        $statusid = " AND vr.`isactive`=".$_POST['statusid'];
   }
   
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (v.`registration_number` LIKE '%".$searchValue."%' or c.`name` LIKE '%".$searchValue."%' or d.`name` LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $sql1 =  "select count(DISTINCT  vr.id) as allcount from `tbl_vehiclerental_agreement` vr
            INNER JOIN `tbl_contractor` c ON c.id = vr.driver_id ".$hirer."
            INNER JOIN `tbl_vehicles` v ON v.`id`=vr.`vehicle_id`
            INNER JOIN `tbl_depot` d ON d.id=v.depot_id  INNER JOIN `tbl_workforcedepotassign` wda on wda.depot_id=vr.depot_id ".$locationname."
            where  wda.`wid` LIKE ('".$userid."') AND wda.release_date IS NULL AND vr.`isdelete`= 0 AND vr.iscompalete=1 ".$statusid;
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];
    
    $sql2 = "select count(DISTINCT  vr.id) as allcount from `tbl_vehiclerental_agreement` vr
            INNER JOIN `tbl_contractor` c ON c.id = vr.driver_id ".$hirer."
            INNER JOIN `tbl_vehicles` v ON v.`id`=vr.`vehicle_id`
            INNER JOIN `tbl_depot` d ON d.id=v.depot_id  INNER JOIN `tbl_workforcedepotassign` wda on wda.depot_id=vr.depot_id  ".$locationname."
            where  wda.`wid` LIKE ('".$userid."')  AND wda.release_date IS NULL AND vr.`isdelete`= 0 AND vr.iscompalete=1 ".$statusid." ".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    
    // $ord="";

    $data = array();
    $query = "SELECT DISTINCT vr.*, vr.`id` as rentalid,DATE_FORMAT(vr.`insert_date`,'%D %M%, %Y') as date1,
    DATE_FORMAT(vr.`pickup_date`,'%D %M%, %Y') as pickupdate, 
    DATE_FORMAT(vr.`return_date`,'%D %M%, %Y') as returndate,vr.`isactive` as status,
    c.`type`,c.`name` as `contractorname`,d.`name` as `depotname`
    FROM `tbl_vehiclerental_agreement` vr
    INNER JOIN `tbl_vehicles` v ON v.`id`=vr.`vehicle_id`
    INNER JOIN `tbl_depot` d ON d.`id`=v.`depot_id` 
    INNER JOIN `tbl_contractor` c ON c.`id` = vr.`driver_id`
     INNER JOIN `tbl_workforcedepotassign` wda on wda.depot_id=vr.depot_id 
    WHERE vr.`isdelete`= 0 ".$statusid."  AND vr.iscompalete=1 AND 
    wda.`wid` LIKE ('".$userid."') AND wda.release_date IS NULL ".$locationname." ".$hirer." ".$searchQuery." order by vr.`id` ".$columnSortOrder. ", vr.`id` ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    while($userresult = mysqli_fetch_array($userrow))
    {
        $rentid =  $userresult['id'];
        $sql ="SELECT DISTINCT vr.*,v.`registration_number` as registrationnumber,DATE_FORMAT(vr.`insert_date`,'%D %M%, %Y') as date1,
    DATE_FORMAT(vr.`pickup_date`,'%D %M%, %Y') as pickupdate, 
    DATE_FORMAT(vr.`return_date`,'%D %M%, %Y') as returndate,vr.`isactive` as status, g.`name` as groupname,
    c.`type`,c.`name` as `contractorname`,s.`name` as `suppliername`,d.`name` as `depotname`
    FROM `tbl_vehiclerental_agreement` vr
    INNER JOIN `tbl_vehicles` v ON v.`id`=vr.`vehicle_id`
    INNER JOIN `tbl_vehiclegroup` g ON g.`id`=v.`group_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
    INNER JOIN `tbl_depot` d ON d.`id`=v.`depot_id` 
    INNER JOIN `tbl_contractor` c ON c.`id` = vr.`driver_id`
    INNER JOIN `tbl_workforcedepotassign` wda on wda.depot_id=vr.depot_id 
    WHERE vr.`isdelete`= 0  AND vr.iscompalete=1  AND  vr.`id`=".$rentid."";
    $row =  $mysql -> selectFreeRun($sql);
    $userresult1 = mysqli_fetch_array($row);
        $rid = $userresult['id'];
        $statuscheck = $userresult['status'];
        $onclick="";
        $statuscls = "danger";
        $onclick = "disabled";
        if($statuscheck == 0 && strtotime(date('Y-m-d'))>=strtotime($userresult['pickup_date']) && strtotime(date('Y-m-d'))<=strtotime($userresult['return_date']))
        {
            $statuscls = 'success';
            $statusname = 'Active';
            $onclick = "onclick=\"terminateAgreement('$rid','".$userresult['status']."','".$userresult['pickup_date']."','".$userresult['return_date']."')\"";
        }else if($statuscheck == 0 && strtotime(date('Y-m-d'))<strtotime($userresult['pickup_date']))
        {
            $statuscls = 'info';
            $statusname = 'Inactive';
        }
        else if($statuscheck == 2)
        {
            $statusname = 'Terminated';
        }
        else if($statuscheck == 1 || strtotime(date('Y-m-d'))>strtotime($userresult['return_date']))
        {
            $statusname = 'Expired';
        }
        
        // $status = "<div id='$id-td'><button type='button' class='isactivebtn btn btn-".$statuscls."'onclick=\"Isactivebtn('$id','".$userresult['status']."','RentalAgreementisactive')\">".$statusname."</button></div>";
        $status="<div id='$rid-td'>
                    <button type='button' class='isactivebtn btn btn-".$statuscls."' ".$onclick.">".$statusname."</button>&nbsp;&nbsp;<a href='#' class='delete' onclick='deleteAgreement($rid)'><span><i class='fas fa-trash-alt fa-lg'></i></span></a></div>";
                

        if($userresult['type'] == "1"){
            $type = "Self-Employed";
        }
        else if($userresult['type'] == "2"){
            $type = "Limited Company";
        }
        else{
            $type="error";
        }

       $vehicle_reg_no = $userresult1['registrationnumber'];
       

       $name = "<a href='rentalagreement_details_show.php?id=".base64_encode($rid)."' class='text-info'>".$userresult1['contractorname']."<br><label style='color: #a5a1a1f7;'>$type</label></a>";
       
       $vehicle = "<a href='rentalagreement_details_show.php?id=".$rid."' class='text-info'>".$userresult1['suppliername']." (".$vehicle_reg_no.") "." <label class='rounded-lg col-sm-3 text-center badge badge-primary text-wrap' style='font-weight: bold; color:white'>".$userresult1['groupname']."</label></a>";
       
       $action = "<a href='#' class='delete' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
            
                    'hirer'=>$name,   
                    'vehicle_reg_no'=>$vehicle,
                    'location'=>$userresult1['depotname'],
                    'pickup_date'=>$userresult['pickupdate'],
                    'return_date'=>$userresult['returndate'],
                    'price_per_day'=>'£'.$userresult['price_per_day'].'',
                    'status'=>$status,
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [

                'hirer'=>"",  
                'vehicle_reg_no'=>"",
                'location'=>"",
                'pickup_date'=>"No Data Found!!!",
                'return_date'=>"",
                'price_per_day'=>"",
                'status'=>"",
                ];
            $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadvehicleOffencestabledata1')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (occurred_date LIKE '%".$searchValue."%' or amount LIKE '%".$searchValue."%' or admin_fee LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(*) as allcount FROM tbl_vehicleoffences WHERE `isdelete` = 0 AND `isactive` = 0 AND vehicle_id=$vid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM tbl_vehicleoffences WHERE `isdelete` = 0 AND isactive = 0 AND vehicle_id=$vid".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";

    $vid = $_SESSION['vid'];

    $query = "SELECT *, DATE_FORMAT(`occurred_date`,'%D %M%, %Y') as date1 FROM `tbl_vehicleoffences` WHERE `isdelete`= 0 AND vehicle_id=$vid".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
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

        $status="<button type='button' class='isactivebtn btn btn-".$statuscls."'>".$statusname."</button>";

        $roletype1="<input type='hidden' name='roletype' id='roletype' value='".$fetch2['role_type']."''>";
        $roletype=$fetch2['role_type'];

                    
        $vehicleqry = "SELECT v.`registration_number`,c.`name` FROM `tbl_vehicleoffences` o
                        INNER JOIN `tbl_vehicles` v ON v.`id`=o.`vehicle_id`
                        INNER JOIN `tbl_contractor` c ON c.`id`=o.`driver_id`
                        WHERE o.`id`=".$userresult['id']." AND o.`isdelete`=0";
        $fire =  $mysql -> selectFreeRun($vehicleqry);
        $records1 = mysqli_fetch_array($fire);
        $vehicle_idrow = "".$records1['registration_number']."<br>".$records1['name']."" ."-".$userresult['identifier'];
            

        $data[] = [
                    'occurred_date' =>$userresult['date1'],    
                    'vehicle_id'=>$vehicle_idrow,
                    'amount' =>$userresult['amount'],
                    'admin_fee'=>$userresult['admin_fee'],
                    'status'=>$status,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'occurred_date'=>"",
                'vehicle_id'=>"",
                'amount'=>"No Record Found!!!",
                'admin_fee'=>"",
                'status'=>"",
                
                ];
            $i++;
    }
}



else if(isset($_POST['action']) && $_POST['action'] == 'loadaudittabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (c.`name` LIKE '%".$searchValue."%' or d.`name` LIKE '%".$searchValue."%' or document_type LIKE '%".$searchValue."%', DATE_FORMAT(a.`open_at`,'%D %M%, %Y') LIKE '%".$searchValue."%',DATE_FORMAT(a.`close_at`,'%D %M%, %Y') LIKE '%".$searchValue."%' or a.`name` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(DISTINCT a.`id`) as allcount FROM tbl_audit a
    INNER JOIN `tbl_contractor` c ON c.`id`= a.`people_to_audit`
    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND c.`depot` IN (w.depot_id) WHERE a.`isdelete`= 0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(DISTINCT a.`id`) as allcount FROM tbl_audit a
    INNER JOIN `tbl_contractor` c ON c.`id`= a.`people_to_audit`
    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND c.`depot` IN (w.depot_id) WHERE a.`isdelete`= 0 ".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query1 = "SELECT DISTINCT a.*,DATE_FORMAT(a.`open_at`,'%D %M%, %Y') as opendate, DATE_FORMAT(a.`close_at`,'%D %M%, %Y') as closedate
    FROM `tbl_audit` a 
    INNER JOIN `tbl_contractor` c ON c.`id`= a.`people_to_audit` 
    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND c.`depot` IN (w.depot_id)
    WHERE a.`isdelete`= 0".$searchQuery." order by a.`id` ".$columnSortOrder. ", a.id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query1);
    $data = array();
    $n=1;
    while($userresult = mysqli_fetch_array($userrow))
    {
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

        $status="<button type='button' class='isactivebtn btn btn-".$statuscls."' onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','Auditisactive')\">".$statusname."</button>";

        $action = "<a href='#' class='edit' onclick=\"edit('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
            <a href='#' class='delete' onclick=\"deleterow('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";  

       // print_r('/'.$n.'-----------'.$userresult['people_to_audit']);

        $cat_result = array();
        
        $where = "( id = ".implode(' OR id = ',explode(',',$userresult['people_to_audit'])).")";
        $categories = "SELECT * FROM `tbl_contractor` WHERE ".$where." AND `isdelete`=0";
        // $catresult =  mysqli_fetch_all($categories);
        $userrow1 =  $mysql -> selectFreeRun($categories);
        //$catresult = mysqli_fetch_array($userrow1);
        while($catresult = mysqli_fetch_array($userrow1))
        {
            $canname = $catresult['name'];
            
        }                         
        
        $contractorname = implode(",", $canname);
        // print_r('------------'.$catresult['name']);


        // $result = count($ids);
        // for($j=0;$j<$result;$j++)
        // {
        //     $categories = "SELECT * FROM `tbl_contractor` WHERE id=".$result[$j]." AND `isdelete`=0";
        //     $userrow1 =  $mysql -> selectFreeRun($categories);
        //     $catresult = mysqli_fetch_array($userrow1);
        //     $cat_result[] = $catresult['name'];
        //     $j++;

        // }
        



        // $cat_result1 = array();
        // $documenttypeids = explode(',',$userresult['document_type']);
        // $documentcount = count($documenttypeids);
        // for($a=0;$a<$documentcount;$a++)
        // {
        //     $document = "SELECT * FROM `tbl_vehicledocumenttype` WHERE id=".$documentcount[$a]."";
        //     $userrow2 =  $mysql -> selectFreeRun($document);
        //     $catresult1 = mysqli_fetch_array($userrow2);
        //     $cat_result1[] = $catresult1['name'];
        //     $a++;
        // }

        // $documentname = implode(",", $cat_result1);

        

  
        $data[] = [
                    'name' =>$userresult['name'],    
                    'people_to_audit'=>$contractorname,
                    'document_type' =>$documentname,
                    'open_at'=>$userresult['opendate'],
                    'close_at'=>$userresult['closedate'],
                    'status'=>$status,
                    'action'=>$action,
                   
                  ];
        $n++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'name'=>"",
                'people_to_audit'=>"",
                'document_type'=>"No Record Found!!!",
                'open_at'=>"",
                'close_at'=>"",
                'status'=>"",
                'action'=>"",
                
                ];
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadaccidentcommettabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`title` LIKE '%".$searchValue."%' or `commet` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $aid = $_POST['aid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_accidentcommet` WHERE `isdelete`= 0 AND `accident_id`=$aid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_accidentcommet` WHERE `isdelete`= 0 AND `accident_id`=$aid".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query = "SELECT * FROM `tbl_accidentcommet` WHERE `isdelete`= 0 AND `accident_id`=$aid".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {

        $action = "<a href='#' class='edit' onclick=\"editcommet('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterowcommet('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";    
        
        $data[] = [
                    'title' =>$userresult['title'],    
                    'commet'=>$userresult['commet'],
                    'action'=>$action,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'title'=>"",
                'commet'=>"",
                'action'=>"",
                
                ];
            $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadaccidentimagetabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`file` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $aid = $_POST['aid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_accident_image` WHERE `isdelete`= 0 AND `accident_id`=$aid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_accident_image` WHERE `isdelete`= 0 AND `accident_id`=$aid".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query = "SELECT * FROM `tbl_accident_image` WHERE `isdelete`= 0 AND `accident_id`=$aid".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {

        $action = "
        <a target = '_blank' href='uploads/accidentimage/".$userresult['file']."' class='adddata'>
        <i class='fas fa-eye fa-lg'></i></a>
        <a href='#' class='delete' onclick=\"deleterowimage('".$userresult['id']."','".$userresult['file']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";    
        
        $data[] = [
                    'file' =>$userresult['file'],    
                    'action'=>$action,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'file'=>"",
                'action'=>"",
                
                ];
            $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadtrainingtabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`file` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $aid = $_POST['aid'];
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_training` WHERE `isdelete`= 0 AND `accident_id`=$aid";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_training` WHERE `isdelete`= 0".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query = "SELECT * FROM `tbl_training` WHERE `isdelete`= 0".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        $tid = $userresult['id'];
        $query1 = "SELECT * FROM `tbl_trainingsession` WHERE `isdelete`= 0 AND `training_id`=$tid";
        $userrow1 =  $mysql -> selectFreeRun($query1);
        $userresult1 = mysqli_fetch_array($userrow1);

        $action = "
        <a href='traningedit.php?id=".$userresult['id']."#v-pills-profile-tab' class='view'><span><i class='fas fa-eye pr-1' style='font-size: 18px;'></i></span></a>
        <a href='traningedit.php?id=".$userresult['id']."' class='edit' onclick=\"editcommet('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
        <a href='#' class='delete' onclick=\"deletetraing('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";    
        
        $data[] = [
                    'name' =>$userresult['name'],    
                    'refreshment'=>$userresult['refreshment'],
                    'next_traing'=>"",
                    'session'=>$userresult1['training_id'],
                    'action'=>$action,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'name'=>"",
                'refreshment'=>"",
                'next_traing'=>"",
                'session'=>"",
                'action'=>"",
                
                ];
            $i++;
    }
}

else if(isset($_POST['action']) && $_POST['action'] == 'loadTrainingSessiontabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (DATE_FORMAT(ts.`date`,'%D %M%, %Y') LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $aid = $_POST['aid'];
        
    $sql1 = "SELECT count(ts.`id`) as allcount FROM `tbl_trainingsession` ts 
    INNER JOIN `tbl_training` t ON t.`id`=ts.`training_id`
    WHERE ts.`isdelete`= 0 AND t.`userid` LIKE ('".$userid."')";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(ts.`id`) as allcount FROM `tbl_trainingsession` ts
    INNER JOIN `tbl_training` t ON t.`id`=ts.`training_id`
    WHERE ts.`isdelete`= 0 AND t.`userid` LIKE ('".$userid."')".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query = "SELECT ts.*,t.`userid`,DATE_FORMAT(ts.`date`,'%D %M%, %Y') as sessiondate FROM `tbl_trainingsession` ts
    INNER JOIN `tbl_training` t ON t.`id`=ts.`training_id`
    WHERE ts.`isdelete`= 0 AND t.`userid` LIKE ('".$userid."')".$searchQuery." order by ts.`id` ".$columnSortOrder. ", ts.`id` ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {

        $action = "
        <a href='#' class='edit' onclick=\"editsession('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>
        <a href='#' class='delete' onclick=\"deletesession('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";    
        
        $data[] = [
                    'date' =>$userresult['sessiondate'],    
                    'attendeces'=>"",
                    'action'=>$action,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'date'=>"",
                'attendeces'=>"",
                'action'=>"",
                
                ];
            $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadaRentalAdjustmenttabledata')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (`amount` LIKE '%".$searchValue."%' or `description` LIKE '%".$searchValue."%') ";
    }
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT count(*) as allcount FROM `tbl_rentaladjustment` WHERE `isdelete`= 0 AND `userid` LIKE ('".$userid."')";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT count(*) as allcount FROM `tbl_rentaladjustment` WHERE `isdelete`= 0 AND `userid` LIKE ('".$userid."')".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];
    $ord="";
 
    $query = "SELECT * FROM `tbl_rentaladjustment` WHERE `isdelete`= 0 AND `userid` LIKE ('".$userid."')".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {

        $action = "<a href='#' class='edit' onclick=\"editcommet('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

                    <a href='#' class='delete' onclick=\"deleterowcommet('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";    
        
        $data[] = [
                    'label' =>$userresult['title'],    
                    'category'=>$userresult['type'],
                    'amount'=>$userresult['amount'],
                    'description'=>$userresult['description'],
                    'insert_date'=>$userresult['insert_date'],
                    'Action'=>$action,
                   
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'label'=>"",
                'category'=>"",
                'amount'=>"No Data Found !!!",
                'description'=>"",
                'insert_date'=>"",
                'Action'=>"",
                
                ];
            $i++;
    }
}else if(isset($_POST['action']) && $_POST['action'] == 'loadMissedAgreement')
{
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (c.name LIKE '%".$searchValue."%' or va.vehicle_reg_no LIKE '%".$searchValue."%') ";
    }
    $depL = $_POST['depL'];
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql1 = "SELECT COUNT(va.id) as allcount FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_contractor` c ON c.id=va.driver_id LEFT JOIN `tbl_vehicles` v on v.id=va.vehicle_id WHERE va.depot_id=$depL AND va.iscompalete=0 AND va.isdelete=0";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql2 = "SELECT COUNT(va.id) as allcount FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_contractor` c ON c.id=va.driver_id LEFT JOIN `tbl_vehicles` v on v.id=va.vehicle_id WHERE va.depot_id=$depL AND va.iscompalete=0 AND va.isdelete=0 ".$searchQuery;
    $sel2 =  $mysql -> selectFreeRun($sql2);
    $records2 = mysqli_fetch_assoc($sel2);
    $totalRecordwithFilter = $records2['allcount'];

    $query = "SELECT va.id,v.registration_number,c.name FROM `tbl_vehiclerental_agreement` va INNER JOIN `tbl_contractor` c ON c.id=va.driver_id LEFT JOIN `tbl_vehicles` v on v.id=va.vehicle_id WHERE va.depot_id=$depL AND va.iscompalete=0  AND va.isdelete=0 ".$searchQuery." order by va.`id` ".$columnSortOrder. ", va.id ASC limit ".$row.",".$rowperpage;
    $userrow =  $mysql -> selectFreeRun($query);
    $data = array();
    $i=0;
    while($userresult = mysqli_fetch_array($userrow))
    {
        $i++;
        $srn= $i;
        $cont = $userresult['name'];
        $veh = $userresult['registration_number'];
        $action = "<a href='#'' class='delete' onclick='deleteMissedAgreement(".$userresult['id'].")'><span><i class='fas fa-trash-alt fa-lg'></i></span></a>";

        $data[] = [
                    'srn' =>$srn,    
                    'contractor'=>$cont,
                    'fleet'=>$veh,
                    'action' =>$action
                  ];
    }
    if(count($data)==0)
    {
        $data[] = [
                'srn' =>"",    
                'contractor'=>"NO Data Found!!!",
                'fleet'=>"",
                'action' =>""
                ];
            $i++;
    }
}


// else if(isset($_POST['action']) && $_POST['action'] == 'loadpendingdocumentstabledata')
// {
//     ## Search 
//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (c.`name` LIKE '%".$searchValue."%' or d.`name` LIKE '%".$searchValue."%' or document_type LIKE '%".$searchValue."%', DATE_FORMAT(a.`open_at`,'%D %M%, %Y') LIKE '%".$searchValue."%',DATE_FORMAT(a.`close_at`,'%D %M%, %Y') LIKE '%".$searchValue."%' or a.`name` LIKE '%".$searchValue."%') ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();
        
//     $sql1 = "SELECT count(c.`id`) as allcount FROM tbl_contractor c
//     INNER JOIN `tbl_contractordocument` d ON d.`contractor_id`= c.`id`
//     WHERE c.`isdelete`= 0 AND c.`userid` LIKE ('".$userid."') AND d.`file` IS NULL";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];

//     $sql2 = "SELECT count(c.`id`) as allcount FROM tbl_contractor c
//     INNER JOIN `tbl_contractordocument` d ON d.`contractor_id`= c.`id`
//     WHERE c.`isdelete`= 0 AND c.`userid` LIKE ('".$userid."') AND d.`file` IS NULL".$searchQuery;
//     $sel2 =  $mysql -> selectFreeRun($sql2);
//     $records2 = mysqli_fetch_assoc($sel2);
//     $totalRecordwithFilter = $records2['allcount'];
//     $ord="";
 
//     $query = "SELECT * FROM tbl_contractor c
//     INNER JOIN `tbl_contractordocument` d ON d.`contractor_id`= c.`id`
//     WHERE c.`isdelete`= 0 AND c.`userid` LIKE ('".$userid."') AND d.`file` IS NULL".$searchQuery." order by `id` ".$columnSortOrder. ", id ASC limit ".$row.",".$rowperpage;
//     $userrow =  $mysql -> selectFreeRun($query);
//     $data = array();
//     while($userresult = mysqli_fetch_array($userrow))
//     {

//         $data[] = [
//                     'document' =>$userresult['name'],    
//                     'signer'=>$userresult['contractorname'],
//                     'sent' =>$userresult['documentname'],
                   
//                   ];
//     }
//     if(count($data)==0)
//     {
//         $data[] = [
//                 'document'=>"",
//                 'signer'=>"No Record Found!!!",
//                 'sent'=>"",
                
//                 ];
//             $i++;
//     }
// }


// else if(isset($_POST['action']) && $_POST['action'] == 'loadRentalInspectionShowtabledata')
// {
//     ## Search 
//     $searchQuery = " ";
//     if($searchValue != '')
//     {
//         $searchQuery = " and (`amount` LIKE '%".$searchValue."%' or `description` LIKE '%".$searchValue."%') ";
//     }
//     $mysql = new Mysql();
//     $mysql -> dbConnect();
//     $rental_id = $_POST['rental_id'];
//     $sql = "SELECT * FROM `tbl_vehiclerentagreement` WHERE id=$rental_id";
//     $fire =  $mysql -> selectFreeRun($sql);
//     $fetch = mysqli_fetch_array($fire);
//     $vehicle_id = $fetch['vehicle_id'];

        
//     $sql1 = "SELECT count(*) as allcount FROM `tbl_vehicleinspection` 
//     WHERE `vehicle_id`=$vehicle_id `isdelete`= 0 AND `userid` LIKE ('".$userid."')";
//     $sel =  $mysql -> selectFreeRun($sql1);
//     $records = mysqli_fetch_assoc($sel);
//     $totalRecords = $records['allcount'];

//     $sql2 = "SELECT count(*) as allcount FROM `tbl_vehicleinspection` 
//     WHERE `vehicle_id`=$vehicle_id `isdelete`= 0 AND `userid` LIKE ('".$userid."')".$searchQuery;
//     $sel2 =  $mysql -> selectFreeRun($sql2);
//     $records2 = mysqli_fetch_assoc($sel2);
//     $totalRecordwithFilter = $records2['allcount'];
//     $ord="";
 
//     // $query = "
//     // SELECT i.*,d.`name` as depotname FROM `tbl_vehicleinspection` i
//     // INNER JOIN `tbl_vehicles` v ON v.`id` = i.`vehicle_id`
//     // INNER JOIN `tbl_depot` d ON d.`id` = v.`depot_id`
//     // WHERE i.`vehicle_id`= $vehicle_id AND i.`isdelete`= 0".$searchQuery;
//     $query = "SELECT r.*,i.*,d.`name` as depotname,v.* FROM `tbl_vehiclerentagreement` r 
//     INNER JOIN `tbl_vehicleinspection` i ON i.`vehicle_id`=r.`vehicle_id`
//     INNER JOIN `tbl_vehicles` v ON v.`id` = r.`vehicle_id`
//     INNER JOIN `tbl_depot` d ON d.`id`=v.`depot_id`
//     WHERE r.`id`=$rental_id";
//     $userrow =  $mysql -> selectFreeRun($query);
//     $data = array();
//     while($userresult = mysqli_fetch_array($userrow))
//     {

//         $action = "<a href='#' class='edit' onclick=\"editcommet('".$userresult['id']."')\"><span><i class='fas fa-edit fa-lg'></i></span></a>

//                     <a href='#' class='delete' onclick=\"deleterowcommet('".$userresult['id']."')\"><span><i class='fas fa-trash-alt fa-lg'></i></span></a>"; 
//         $approve = '<button type="submit" class="btn btn-sm btn-primary">Approve</button>';               
        
//         $data[] = [
//                     'depot' =>$userresult['depotname'],    
//                     'date'=>$userresult['type'],
//                     'vehicle'=>$userresult['registration_number'],
//                     'user'=>$userresult['description'],
//                     'odometer'=>$userresult['odometer'],
//                     'checks'=>$userresult['insert_date'],
//                     'status'=>$userresult['insert_date'],
//                     'inspect'=>"Inspect",
//                     'approve'=>$approve,
//                     'action'=>$action,
                   
//                   ];
//     }
//     if(count($data)==0)
//     {
//         $data[] = [
//                 'depot'=>"",
//                 'date'=>"",
//                 'vehicle'=>"No Data Found !!!",
//                 'user'=>"",
//                 'odometer'=>"",
//                 'checks'=>$query,
//                 'status'=>"",
//                 'inspect'=>"",
//                 'approve'=>"",
//                 'action'=>"",
                
//                 ];
//             $i++;
//     }
// }

else if(isset($_POST['action']) && $_POST['action'] == 'loadRentalInspectionShowtabledata')
{
    ## Search 

    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (v.`odometer` LIKE '%".$searchValue."%' or v.`registration_number` LIKE '%".$searchValue."%' or v.`depot_id` LIKE '%".$searchValue."%' or vi.`insert_date` LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $vehicle_id = $_POST['vid'];
    
    $sql2 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
     INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
     WHERE vi.`isdelete`=0 AND vi.`isactive`=0 AND cast(vi.`insert_date` as Date) LIKE cast('".$inspectiondate."' as Date) AND v.`registration_number` LIKE ('".$vehicle."') AND v.`depot_id` LIKE ('".$depot."') AND v.`userid` LIKE ('".$userid."')";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql3 = "SELECT count(vi.`id`) as allcount FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id` 
    WHERE vi.`isdelete` = 0 AND vi.`isactive` = 0 AND cast(vi.`insert_date` as Date) LIKE cast('".$inspectiondate."' as Date) AND v.`registration_number` LIKE ('".$vehicle."') AND v.`depot_id` LIKE ('".$depot."') AND v.`userid` LIKE ('".$userid."')".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql3);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    $currentdate = date('d-m-Y');
    
    $query = "SELECT vi.*,v.`registration_number`,s.`name` as suppliername,v.`userid`, DATE_FORMAT(vi.`insert_date`,'%D %M%, %Y') as date1,d.`name` as depotname FROM `tbl_vehicleinspection` vi
    INNER JOIN `tbl_vehicles` v ON v.`id`= vi.`vehicle_id`
    INNER JOIN `tbl_vehiclesupplier` s ON s.`id`= v.`supplier_id`
    INNER JOIN `tbl_depot` d ON d.`id`= v.`depot_id`
    WHERE vi.`isdelete`= 0 AND vi.`odometer` IS NOT NULL AND vi.`vehicle_id`=".$vehicle_id."";
    // $query = "SELECT i.*, r.*,d.`name` as depotname,DATE_FORMAT(i.`insert_date`,'%D %M%, %Y') as date1,v.`registration_number` FROM `tbl_vehiclerental_agreement` r
    // INNER JOIN `tbl_vehicles` v ON v.`id`= r.`vehicle_id`
    // INNER JOIN `tbl_vehicleinspection` i ON i.`vehicle_id` = r.`vehicle_id`
    // INNER JOIN `tbl_depot` d ON d.`id`= v.`depot_id`
    // WHERE i.`isdelete`= 0 AND i.`odometer` IS NOT NULL";
    $userrow =  $mysql -> selectFreeRun($query);
    

    $data = array();
    
    $chklist = "SELECT COUNT(*) AS ct1 FROM tbl_vehiclechecklist WHERE `isdelete`= 0";
    $chkfire = $mysql -> selectFreeRun($chklist);
    $fetch = mysqli_fetch_array($chkfire);
    $row = (int)$fetch['ct1'] + 1;
    
    while($userresult = mysqli_fetch_array($userrow))
    {
        // $vid = $userresult['vehicle_id'];
        $oInsertId = $userresult['odometerInsert_date'];
        
        $entry = "SELECT COUNT(*) AS cnt2 FROM tbl_vehicleinspection WHERE odometerInsert_date='".$oInsertId."'";
        $chkentry = $mysql -> selectFreeRun($entry);
        $fetch1 = mysqli_fetch_array($chkentry);
        $result = (int)$row-(int)$fetch1['cnt2'];
        if($result == 0){
            $sql3 = "SELECT * FROM tbl_vehicleinspection WHERE odometerInsert_date='".$oInsertId."' AND answer_type=0";
            $fire3 = $mysql -> selectFreeRun($sql3);
            if($fire3){
            $rowcount=mysqli_num_rows($fire3);
                
            if($rowcount > 0){
                $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
            }else{
               $chek = "<span style='color: green;'><i class='fa fa-check-circle' aria-hidden='true'></i> All Check Passed</span>"; 
            }
          }
        }
        else{
            
            $chek = "<span style='color: red;'><i class='fas fa-times-circle'></i> All Check Are Not Passed</span>";
        }
        
     
        
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

        $statustd= "<div id='".$userresult['id']."-td'>
                        <button type='button' class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','WorkforceMetricisactive')\">".$statusname."</button>
                    </div>";

        $action = "<a href='vehicleinspection_showdetails.php?id=".$userresult['id']."'' style='color: #03a9f3;'><span>View</span></a>";
        $approve = '<button type="submit" class="btn btn-sm btn-primary">Approve</button>'; 

        $data[] = [

                    'depot' =>$userresult['depotname'], 
                    'date' =>$userresult['date1'],    
                    'vehicle'=>$userresult['registration_number'],
                    'user'=>$userresult['suppliername'],
                    'odometer' =>$userresult['odometer'],
                    'check'=>$chek,
                    'status'=>"",
                    'inspect'=>"Inspect",
                    'approve'=>$approve,
                    'action'=>$action
                  ];
    }
    
    if(count($data)==0)
    {
        $data[] = [
                'depot'=>"",
                'date'=>"",
                'vehicle'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
                'user'=>"",
                'odometer'=>"",
                'check'=>$query,
                'status'=>"",
                'inspect'=>"",
                'approve'=>"",
                'action'=>"",
               ];
            $i++;
    }
}


else if(isset($_POST['action']) && $_POST['action'] == 'loadacommunicationtabledata')
{
    ## Search 

    $searchQuery = " ";
    // if($searchValue != '')
    // {
    //     $searchQuery = " and (v.`odometer` LIKE '%".$searchValue."%' or v.`registration_number` LIKE '%".$searchValue."%' or v.`depot_id` LIKE '%".$searchValue."%' or vi.`insert_date` LIKE '%".$searchValue."%') ";
    // }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();
        
    $sql2 = "SELECT count(*) as allcount FROM `tbl_communication` WHERE `isdelete`=0 AND `isactive`=0 ";
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];

    $sql3 = "SELECT count(*) as allcount FROM `tbl_communication`
    WHERE `isdelete` = 0 AND `isactive` = 0 ".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql3);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    $ord="";
    $currentdate = date('d-m-Y');
    
    $query = "SELECT *,DATE_FORMAT(c.`insert_date`,'%D %M%, %Y') as date1,u.`name` as username FROM `tbl_communication` c
    INNER JOIN `tbl_user` u ON u.`id`=c.`userid`
    WHERE c.`isdelete`= 0 AND c.`isactive`=0";
    $userrow =  $mysql -> selectFreeRun($query);
    
    $data = array();
    while($userresult = mysqli_fetch_array($userrow))
    {
        $depot1 = explode(",",$userresult['depot_id']);
        $depot = " id = ".implode(" OR id = ",$depot1);
        $sql = "SELECT * FROM `tbl_depot` WHERE ".$depot;
        $fire =  $mysql -> selectFreeRun($sql);
        $name1 = array();
        while($fetch = mysqli_fetch_array($fire))
        {
            $name1[] = $fetch['name'];
        }
        $name = implode(" <strong> || </strong> ",$name1);
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

        $statustd= "<div id='".$userresult['id']."-td'>
                        <button type='button' class='isactivebtn btn btn-".$statuscls."' 
                        onclick=\"Isactivebtn('".$userresult['id']."','".$userresult['isactive']."','WorkforceMetricisactive')\">".$statusname."</button>
                    </div>";

        $action = "<a href='vehicleinspection_showdetails.php?id=".$userresult['id']."' style='color: #03a9f3;'><span>View</span></a>";
        $msg = $userresult['meassage'];
        $toltip = "<span class='mytooltip tooltip-effect-1'><span class='tooltip-item2'>Email Content</span><span class='tooltip-content4 clearfix'><span class='tooltip-text2'>$msg</span></span>";
        // $toltip = "<a class='mytooltip' href='javascript:void(0)'> Message Content<span class='tooltip-content5'><span class='tooltip-text3'><span class='tooltip-inner2'>$msg</span></span></span></a>";
        // $toltip = "<button type='button' class='btn btn-secondary' data-toggle='tooltip' data-html='true' title='hi'>Content</button>";
        $data[] = [
                    'date' =>$userresult['date1'], 
                    'issued_by' =>$userresult['username'],    
                    'subject'=>$userresult['subject'],
                    'meassage'=>$toltip,
                    'depot_id' =>$name,
                    'reference'=>"",
                    'receipt'=>"",
                  ];
    }
    
    if(count($data)==0)
    {
        $data[] = [
                'date'=>"",
                'issued_by'=>"",
                'subject'=>"",
                'meassage'=>"<img src='drivaarpic/document.PNG' alt='' width='300' height='100'>",
                'depot_id'=>"",
                'reference'=>"",
                'receipt'=>"",
               ];
            $i++;
    }
}



else if(isset($_POST['action']) && $_POST['action'] == 'loadCloseticketdata')
{
    $ticketstatus = $_POST['status'];
    ## Search 
    $searchQuery = " ";
    if($searchValue != '')
    {
        $searchQuery = " and (tbl_department.department LIKE '%".$searchValue."%') ";
    }
    
    $mysql = new Mysql();
    $mysql -> dbConnect();

    $sql1 = "SELECT count(ts.`id`) as allcount FROM tbl_ticketstatus ts
    INNER JOIN tbl_addraiseticket at ON at.`id` = ts.`ticketId`
    INNER JOIN tbl_issuetype tis ON tis.`id` = at.`issue`
    INNER JOIN tbl_department d ON d.`issuetypeId` = at.`issue`
    INNER JOIN tbl_role r ON r.`id` = d.`role_id`
    WHERE ts.`isdelete`=0 AND ts.`status` LIKE '".$ticketstatus."'";
    $sel =  $mysql -> selectFreeRun($sql1);
    $records = mysqli_fetch_assoc($sel);
    $totalRecords = $records['allcount'];


    // $sql2 = "SELECT count(ts.`id`) as allcount FROM tbl_ticketstatus ts
    // INNER JOIN tbl_addraiseticket at ON at.`id` = ts.`ticketId`
    // INNER JOIN tbl_issuetype tis ON tis.`id` = at.`issue`
    // INNER JOIN tbl_department d ON d.`issuetypeId` = at.`issue`
    // INNER JOIN tbl_role r ON r.`id` = d.`role_id`
    // WHERE ts.`isdelete`=0 AND ts.`status`='clos'";
    // print_r($sql2);
    
    $sql2 = "SELECT count(ts.`id`) as allcount FROM tbl_ticketstatus ts
    INNER JOIN tbl_addraiseticket at ON at.`id` = ts.`ticketId`
    INNER JOIN tbl_issuetype tis ON tis.`id` = at.`issue`
    INNER JOIN tbl_department d ON d.`issuetypeId` = at.`issue`
    INNER JOIN tbl_role r ON r.`id` = d.`role_id`
    WHERE ts.`isdelete`=0 AND ts.`status` LIKE '".$ticketstatus."'".$searchQuery;
    $sel =  $mysql -> selectFreeRun($sql2);
    $records = mysqli_fetch_assoc($sel);
    $totalRecordwithFilter = $records['allcount'];
    //print_r($sql2);
    // $ord="";

    $row12 = "SELECT ts.*,at.`id` as tcid,d.*,r.`id` as roleid,r.*,tis.* FROM `tbl_ticketstatus` ts 
    INNER JOIN tbl_addraiseticket at ON at.`id` = ts.`ticketId`
    INNER JOIN tbl_issuetype tis ON tis.`id` = at.`issue`
    INNER JOIN tbl_department d ON d.`issuetypeId` = at.`issue`
    INNER JOIN tbl_role r ON r.`id` = d.`role_id`
    WHERE ts.`isdelete`=0 AND ts.`status` LIKE '".$ticketstatus."'".$searchQuery." order by ts.`id` ".$columnSortOrder. ", ts.id ASC limit ".$row.",".$rowperpage;
   
    // $row123 = "SELECT tbl_addraiseticket.*,tbl_addraiseticket.id as tcid, tbl_issuetype.issuetype, tbl_department.department,tbl_role.role_type,tbl_role.id as roleid,tbl_contractor.id,tbl_contractor.name as contractorname,tbl_contractor.userid,tbl_user.name FROM tbl_addraiseticket
    //     INNER JOIN tbl_issuetype ON tbl_issuetype.id = tbl_addraiseticket.issue
    //     INNER JOIN tbl_department ON tbl_department.issuetypeId = tbl_addraiseticket.issue
    //     INNER JOIN tbl_role ON tbl_role.id = tbl_department.role_id
    //     INNER JOIN tbl_contractor ON tbl_contractor.id = tbl_addraiseticket.cid
    //     INNER JOIN tbl_ticketstatus ON tbl_ticketstatus.id = tbl_addraiseticket.id
    //     INNER JOIN tbl_user ON tbl_user.id = tbl_contractor.userid
    //     WHERE tbl_addraiseticket.`isDelete`=0 AND tbl_ticketstatus.`status`="clos"".$searchQuery." order by tbl_addraiseticket.`id` ".$columnSortOrder. ", tbl_addraiseticket.id ASC limit ".$row.",".$rowperpage;
    $fire =  $mysql -> selectFreeRun($row12);

    $data = array();
    $i=$row+1;
    
    while($userresult = mysqli_fetch_array($fire))
    {
        $cid = $userresult['cid'];
        $id = $userresult['tcid'];
        $no_of_digit = 5;
        $number = $userresult['tcid'];
        $length = strlen((string)$number);
        for($a = $length;$a<$no_of_digit;$a++)
        {
            $number = '0'.$number;
        }

        $response_status = $userresult['status'];
        if($response_status == "1")
        {
           $resp_status =  '<span class="label label-info text-sm">PROCESSING</span>';
        }
        elseif ($response_status == "4") 
        {
            $resp_status =  '<span class="label label-success text-sm">OPEN</span>';
        } 
        elseif ($response_status == "disputes")   
        {
            $resp_status =  '<span class="label label-warning text-sm">'.$response_status.'</span>';
        }
        elseif ($response_status == "3")   
        {
            $resp_status =  '<span class="label label-danger text-sm">CLOSE</span>';
        }
        elseif ($response_status == "2")   
        {
            $resp_status =  '<span class="label label-success text-sm">ESCALATE</span>';
        }





        $status = "<button class='btn btn-primary p-0 col-sm-6' name='status' type='' onclick=\"editstatus(".$id.",'".$userresult['department']."','".$userresult['roleid']."')\">Edit</button>
       ";
        // <button class='btn btn-primary' name='status' id='status' type='' onclick='viewhistory(".$id.");'>History</button>
        $roletype1="<input type='hidden' name='roletype' id='roletype' value='".$userresult['role_type']."''>";
        $roletype=$userresult['role_type'];
        
        
        $data[] = [
                    'srno'=>$i,
                    'id'=>$number,
                    'user'=>$userresult['contractorname'],
                    'role'=>$roletype,
                    'department'=>$userresult['department'],
                    'problem'=>$userresult['issuetype'],
                    'issue'=>$userresult['issue'],
                    'status'=>$resp_status,
                    'edit'=>$status,
                   
                  ];
                   $i++;
    }
    if(count($data)==0)
    {
        $data[] = [
                'srno'=>"",
                'id'=>"",
                'user'=>"",
                'role'=>"",
                'department'=>"!! NO DATA FOUND !!!",
                'problem'=>"",
                'issue'=>"",
                'status'=>"",
                'edit'=>"",
               
               ];
            $i++;
    }
}



// else if(isset($_POST['action']) && $_POST['action'] == 'loadCloseticketdata')
// {
//     echo "hiii";
// }


$response = array(
    "draw" => $draw,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);


echo json_encode($response,JSON_UNESCAPED_SLASHES);

?>