<?php
include 'DB/config.php';
if (!isset($_SESSION)) {
    session_start();
}

if(isset($_POST['action']) && $_POST['action'] == 'adddepartmentinputfied')
{

    header("content-Type: application/json");
    $status=0;
    $issuetype = $_POST['issuetype_id'];
    
    $mysql = new Mysql();
    $mysql -> dbConnect();

   
        $permission = "SELECT * FROM `tbl_department` WHERE `issuetypeId`=$issuetype  AND `isdelete`=0";
        $permissionrow =  $mysql -> selectFreeRun($permission);       
        if($pr_result = mysqli_fetch_array($permissionrow))
        {
            $department = $pr_result['department'];
            $status=1;
        }
        else
        {
           $status=0; 
        }
        
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['issuetypeId'] = $department;
    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'reportaccidentUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_reportaccident` WHERE `id`=".$id; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['statusdata'] = $statusresult['description_accident'];
        $statusdata['statusdata'] = $statusresult['name'];
        $statusdata['statusdata'] = $statusresult['vehicle_plat_number'];
        $statusdata['statusdata'] = $statusresult['contact'];
        $statusdata['statusdata'] = $statusresult['notes'];
        $statusdata['statusdata'] = $statusresult['reported_insurance_company'];
        // $statusdata['update_date'] = date('Y-m-d H:i:s');     
    }
    else
    {
        $status = 0;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'reportaccidentDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_reportaccident',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'updatecontractorinvoicestatus')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus[0]['status_id'] = 9;

    $usercol = array('update_date','status_id');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractorinvoice',$valus,$usercol,'update',$where);

    $mysql -> dbDisConnect();
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'loadticketstatustabledata')
{

    header("content-Type: application/json");
    $status=0;
    $cid = $_SESSION['cid'];
    $ticketid = $_POST['ticket_id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $sql = "SELECT * FROM `tbl_ticketstatus` WHERE `ticketId`=$ticketid";
    $fire =  $mysql -> selectFreeRun($sql);
    
    $rowcount=mysqli_num_rows($fire);
    if($rowcount > 0)
    {
        $status=1;
        $data = array();
        while($ownerresult = mysqli_fetch_array($fire))
        {
            $response_status = $ownerresult['status'];
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
                $resp_status =  '<span class="label label-success text-sm">ASCOLET</span>';
            }
        
            // $fileshow = $ownerresult['commet'];
            $data[] = "<tr><td>".$ownerresult['commet']."</td><td>".$resp_status."</td>
            <td>".$ownerresult['insert_date']."</td></tr>";   
        }
    }
    else
    {
        $status=0;
        $data[] = "<tr><td></td><td>Data Not Found..!</td><td></td></tr>"; 
    }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $data;
    echo json_encode($response);
}


else if(isset($_POST['action']) && $_POST['action'] == 'todayinspectioncheckdata')
{

    header("content-Type: application/json");
    $status=0;
    $name = 1;
    $cid = $_SESSION['cid'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $status = 0;
    $check = 0;
    $clockstatus = $_POST['clockstatus'];
    $valus[0]['contractor_id'] = $cid;
    $valus[0]['insert_date'] = date('Y-m-d H:i:s');

    if($clockstatus == "clockin")
    {
        //print_r("hiii");
        $check=1;
        $sql2 = "SELECT * FROM `tbl_contractorattendance` WHERE `contractor_id`=$cid AND `starts` IS NOT NULL AND date(`insert_date`) = CURDATE()";
        $fire2 =  $mysql -> selectFreeRun($sql2);
        $rowcount2=mysqli_num_rows($fire2);
        if($rowcount2 > 0)
        {
            $status = 0;
            $title = 'Clock In Error';
            $message = 'Alredy Clock In.';

        }else
        {
            $status = 1;
            $valus[0]['starts'] = date('H:i:s');
            $makeinsert = $mysql -> insert('tbl_contractorattendance',$valus);
            $title = 'Clock In';
            $message = 'Clock In Successfully.';
        }
         
    }
    else if ($clockstatus == "clockout")
    {
        $sql = "SELECT a.*,v.* from `tbl_vehicleinspection` v 
        INNER JOIN `tbl_assignvehicle` a ON a.`vid` = v.`vehicle_id` 
        WHERE date(v.`insert_date`) = CURDATE() AND a.`driver`=$cid AND (CURRENT_DATE() BETWEEN a.`start_date` AND a.`end_date`) AND v.`odometer` IS NOT NULL";
        $fire =  $mysql -> selectFreeRun($sql);
    
        $rowcount=mysqli_num_rows($fire);
        if($rowcount > 0)
        {
            $check=1;

            $sql2 = "SELECT * FROM `tbl_contractorattendance` WHERE `contractor_id`=$cid AND `end` IS NOT NULL AND date(`insert_date`) = CURDATE()";
            $fire2 =  $mysql -> selectFreeRun($sql2);
            $rowcount2=mysqli_num_rows($fire2);
            if($rowcount2 > 0)
            {
                $status = 0;
                $title = 'Clock Out Error';
                $message = 'Alredy Clock Out.';

            }else
            {
                $status = 1;
                // $valus[0]['contractor_id'] = $cid;
                $valus[0]['end'] = date('H:i:s');
                // $valus[0]['insert_date'] = date('Y-m-d H:i:s');
                $makeinsert = $mysql -> insert('tbl_contractorattendance',$valus);
                $title = 'Clock Out';
                $message = 'Clock Out Successfully.';
            }
        }
        else
        {
            $check=0;
            $status = 0;
            $title = 'Clock Out';
            $message = "Before Clock Out Add a Today's Inspection.";

        }
           
    }    
    


   
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $check;
    echo json_encode($response);
}


else if(isset($_POST['action']) && $_POST['action'] == 'contractoroldpassword')
{

    header("content-Type: application/json");
    $status=0;
    $cid = $_SESSION['cid'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $status = 0;

    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus[0]['password'] = $_POST['newpassword'];

    $valus[0]['ispasswordchange'] = 1;

    $usercol = array('update_date','password','ispasswordchange');

    $where = 'id ='.$cid;

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractor',$valus,$usercol,'update',$where);
  
  
    if($paymentdata)
    {
        $status = 1;
        $title = 'Update';
        $message = 'New Password Create Successfully.';

    }else
    {
        $status = 0;
        $title = 'Update';
        $message = 'New Password Can Not Create.';
    }
         
    $name = "Update";
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
}

else if(isset($_POST['action']) && $_POST['action'] == 'contractorDetailUpdate')
{

    header("content-Type: application/json");

    $status=0;
    $cid = $_SESSION['cid'];

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus[0]['email'] = $_POST['email'];

    $valus[0]['contact'] = $_POST['contact'];

    $valus[0]['address'] = $_POST['address'];

    $valus[0]['street_address'] = $_POST['street_address'];

    $valus[0]['city'] = $_POST['city'];

    $valus[0]['state'] = $_POST['state'];

    $valus[0]['postcode'] = $_POST['postcode'];

    $valus[0]['country'] = $_POST['country'];

    if($_POST['newpassword'] != "")
    {
        $valus[0]['password'] = $_POST['newpassword'];
    }    


    $valus[0]['ispasswordchange'] = 1;

    $usercol = array('email','contact','address','street_address','city','state','postcode','country','update_date','password','ispasswordchange');

    $where = 'id ='.$cid;

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_contractor',$valus,$usercol,'update',$where);
    //print_r($paymentdata);
  
  
    if($paymentdata)
    {
        $status = 1;
        $title = 'Update';
        $message = 'Data has been Successfully.';

    }else
    {
        $status = 0;
        $title = 'Update';
        $message = 'Data can not updated.';
    }
         
    $name = "Update";
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;
    echo json_encode($response);
}



?>