<?php
include 'DB/config.php';

if(isset($_POST['action']) && $_POST['action'] == 'updateticketsettingtable')
{
    header("content-Type: application/json");
    $status=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $isactivecol = array('release_date','update_date');
    $makeinsert = $_POST['last_role_id'];
    $valuss[0]['release_date'] = date('Y-m-d H:i:s');
    $valuss[0]['update_date'] = date('Y-m-d H:i:s');
    $where = 'id ='.$makeinsert;
    $isactiveupdate = $mysql -> update('tbl_ticketsetting',$valuss,$isactivecol,'update',$where);
    
    
    if($isactiveupdate)
    {
        $status=1;
        $title = 'Update';
        $message = 'Data has been succesfully Update.';
    }
    else
    {
        $status=0;
        $title = 'Update Error';
        $message = 'Data can not been Update.';
    }
    
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response); 
    
    
}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleOffencesDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_vehicleoffences',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    
    if($user)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'selectdepartmentticket')
{   

    header("content-Type: application/json");

    $status=0;

    $department = $_POST['department'];
    $role = $_POST['role'];


    $mysql = new Mysql();

    $mysql -> dbConnect();

    $options = array();
    $sql = "SELECT t.*,r.role_type FROM `tbl_ticketsetting` t INNER JOIN tbl_role r ON r.`id` = t.`role_id` WHERE t.`role_id`=$role";
    $fire =  $mysql -> selectFreeRun($sql);
    while($fetch = mysqli_fetch_array($fire))
    {
        $status=1;
        $options[] ="<option value=".$fetch['id'].">".$fetch['role_type']."</option>";      
    }

    $mysql -> dbDisConnect();
    
    if($sql)
    {
        $response['status'] = $status;
        $response['options'] = $options;
    }

    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'selectsamedepartmentticket')
{   

    header("content-Type: application/json");

    $status=0;

    $department = $_POST['department'];
    $role = $_POST['role'];


    $mysql = new Mysql();

    $mysql -> dbConnect();

    $sql1 = "SELECT * FROM `tbl_role` WHERE role_type='$role'";
    $fire1 = $mysql -> selectFreeRun($sql1);
    $fetch1 = mysqli_fetch_array($fire1);
    $roleid = $fetch1['id'];

    $options = array();
    $sql = "SELECT u.`name`,u.`id` FROM `tbl_ticketsetting` s INNER JOIN `tbl_user` u ON u.roleid=s.`role_id` AND s.`depot_id` IN (u.`depot`) WHERE s.`role_id`=$role AND s.`isDelete`=0";
    $fire =  $mysql -> selectFreeRun($sql);
    while($fetch = mysqli_fetch_array($fire))
    {
        $status=1;
        $options[] ="<option value=".$fetch['id'].">".$fetch['name']."</option>";      
    }

    $mysql -> dbDisConnect();
    
    if($sql)
    {
        $response['status'] = $status;
        $response['options'] = $options;
    }

    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'leaverequestresponsestatus')
{   

    header("content-Type: application/json");

    $status=0;
    
    $response_status = $_POST['status'];
    if($response_status == 1)
    {
        $status=1;
        $title = 'Update';
        $message = 'Leave Request has been Approved.';
    }
    else{
        $status=2;
        $title = 'Update';
        $message = 'Leave Request has been Rejected.';
    } 
    // $lrid = $_POST['lrid'];

    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus[0]['status'] = $response_status;

    $usercol = array('update_date','status');

    $where = 'id ='. $_POST['lrid'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_leaverequest',$valus,$usercol,'update',$where);

    $mysql -> dbDisConnect();
    
   
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response); 

}


else if(isset($_POST['action']) && $_POST['action'] == 'inspectionissueresponse')
{   

    header("content-Type: application/json");

    $status=0;
    
    $response_status = $_POST['status'];
    if($response_status == 1)
    {
        $status=1;
        $title = 'Update';
        $message = 'Inspection Issue has been Resolved.';
    }
    else{

        $status=2;
        $title = 'Update';
        $message = 'Inspection Issue has been Close.';
    } 
    // $inspection_id = $_POST['id'];

    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $valus[0]['answer_type'] = $response_status;

    $usercol = array('update_date','answer_type');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $user =  $mysql -> update('tbl_vehicleinspection',$valus,$usercol,'update',$where);

    $mysql -> dbDisConnect();
    
   
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;

    echo json_encode($response); 

}

else if(isset($_POST['action']) && $_POST['action'] == 'loadticketstatustabledata')
{

    header("content-Type: application/json");
    $status=0;
    // $cid = $_SESSION['cid'];
    $ticketid = $_POST['ticket_id'];
    $mysql = new Mysql();
    $mysql -> dbConnect();
    
    $sql = "SELECT tbl_addraiseticket.cid,tbl_ticketstatus .*,DATE_FORMAT(tbl_ticketstatus.`insert_date`,'%D %M%, %Y') as dateinsert,tbl_user.role_type,tbl_user.name,tbl_contractor.userid FROM tbl_ticketstatus INNER JOIN tbl_addraiseticket ON tbl_addraiseticket.id = tbl_ticketstatus.ticketId INNER JOIN tbl_contractor ON tbl_contractor.id = tbl_addraiseticket.cid INNER JOIN tbl_user ON tbl_user.id=tbl_contractor.userid WHERE tbl_ticketstatus.ticketId=$ticketid";
    $fire =  $mysql -> selectFreeRun($sql);
        $status=1;
        // $res = array();
        $ownerresult = mysqli_fetch_array($fire);
        $res .='<div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-body">
                            <div class="timeline">
                                <div class="timeline__wrap">
                                    <div class="timeline__items">'; 
        // foreach($row as $ownerresult)
        // {
                                    while($row = mysqli_fetch_array($fire))
                                    {
                                        $fileshow = $ownerresult['commet'];

                                        if($row['status'] == "1")
                                        {
                                            $statuscls = "info";
                                            $statusnm = "PROCESSING";
                                        }else if($row['status'] == "3")
                                        {
                                            $statuscls = "success";
                                            $statusnm = "CLOSE";
                                        }else if($row['status'] == "5")
                                        {
                                            $statuscls = "primary";
                                            $statusnm = "DISPUTE";
                                        }else if($row['status'] == "2")
                                        {
                                            $statuscls = "danger";
                                            $statusnm = "ASCOLET";
                                        }
                                       
                                        $res .= '
                                           <div class="timeline__item">
                                                 <div class="timeline__content">
                                                  <span style="font-weight: 500;">'.$row['dateinsert'].'</span><span class="label label-'.$statuscls.' ml-2">'.$statusnm.'</span><br>
                                                  <span class="" style="font-weight: 500;">'.$row['name'].'</span><span class="text-xs"> ('.$row['role_type'].')</span><br>
                                                  <label class="border border-'.$statuscls.' p-1 rounded mt-2 col-md-12">'.$row['commet'].'</label>
                                                 </div>
                                                </div>
                                        ';  
                                    }
        $res .= '</div>
                  </div>
                 </div>
                </div>
                </div>
  </div>';


    // else
    // {
    //     $status=0;
    //     $res.[] = 'dsasd'; 
    // }
    
    $mysql -> dbDisConnect();
    $response['status'] = $status;
    $response['tbldata'] = $res;
    echo json_encode($response);
}


else if(isset($_POST['action']) && $_POST['action'] == 'AuditUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();
    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_audit` WHERE id=".$id." AND isdelete=0 AND isactive=0"; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
        $statusdata['contractor'] = $statusresult['people_to_audit'];
        $statusdata['document'] = $statusresult['document_type'];
        $statusdata['opendate'] = $statusresult['open_at'];
        $statusdata['closedate'] = $statusresult['close_at'];
        $statusdata['paidcheck'] = $statusresult['include_paid_invoice'];
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

else if(isset($_POST['action']) && $_POST['action'] == 'AuditDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_audit',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    // $response['status'] = $paymentdata;
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'VehicleaccidentUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_accident` WHERE id=$id AND isdelete=0 AND isactive=0"; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['driver'] = $statusresult['driver_id'];
        $statusdata['vehicle'] = $statusresult['vehicle_id'];
        $statusdata['date'] = $statusresult['date_occured'];
        $statusdata['type'] = $statusresult['type_id'];
        $statusdata['stage'] = $statusresult['stage_id'];
        $statusdata['reference'] = $statusresult['reference'];
        $statusdata['description'] = $statusresult['description'];
        $statusdata['other_person'] = $statusresult['other_person'];
        $statusdata['other_vehicle'] = $statusresult['other_vehicle'];
        $statusdata['contact'] = $statusresult['contact'];
        $statusdata['other_notes'] = $statusresult['other_notes'];
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

else if(isset($_POST['action']) && $_POST['action'] == 'accidentcommetUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_accidentcommet` WHERE `id`=$id AND `isdelete`=0"; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['title1'] = $statusresult['title'];
        $statusdata['commet'] = $statusresult['commet'];
       
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

else if(isset($_POST['action']) && $_POST['action'] == 'accidentcommetDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_accidentcommet',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    // $response['status'] = $paymentdata;
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'accidentimageDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $filename = "uploads/accidentimage/".$_POST['file']."";
      if (file_exists($filename)) {
        unlink($filename);
        // echo 'File '.$filename.' has been deleted';
        $paymentdata =  $mysql -> update('tbl_accident_image',$valus,$usercol,'delete',$where);

      } else {
        // echo 'Could not delete '.$filename.', file does not exist';
      }

    $mysql -> dbDisConnect();
    // $response['status'] = $paymentdata;
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'TrainingUpdateData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_training` WHERE `id`=$id AND `isdelete`=0"; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['name'] = $statusresult['name'];
        $statusdata['refreshment'] = $statusresult['refreshment'];
       
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


else if(isset($_POST['action']) && $_POST['action'] == 'TrainingEditData')
{

    header("content-Type: application/json");

    $status=0;

    $id=$_POST['tid'];
    $valus[0]['name'] = $_POST['name'];
    $valus[0]['refreshment'] = $_POST['refre'];
    $valus[0]['update_date'] = date('Y-m-d H:i:s');

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $statusresult = array('name','refreshment','update_date');
    $where = 'id ='.$id;
    $isactiveupdate = $mysql -> update('tbl_training',$valus,$statusresult,'update',$where);

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
    
    $name = 'Update';
    $mysql -> dbDisConnect();

    $response['status'] = $status;
    $response['title'] = $title;
    $response['message'] = $message;
    $response['name'] = $name;


    $mysql -> dbDisConnect();
    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'traningDeleteData')
{   

    header("content-Type: application/json");

    $status=0;

    $valus[0]['delete_date'] = date('Y-m-d H:i:s');

    $valus[0]['isdelete'] = 1;

    $usercol = array('delete_date','isdelete');

    $where = 'id ='. $_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $paymentdata =  $mysql -> update('tbl_training',$valus,$usercol,'delete',$where);

    $mysql -> dbDisConnect();
    // $response['status'] = $paymentdata;
    
    if($paymentdata)
    {
        $response['status'] = 1;
    }

    echo json_encode($response); 
}

else if(isset($_POST['action']) && $_POST['action'] == 'TrainingSessionUpdateData')
{
     header("content-Type: application/json");

    $status=0;

    $id=$_POST['id'];

    $mysql = new Mysql();

    $mysql -> dbConnect();

    $vehiclestatus = "SELECT * FROM `tbl_trainingsession` WHERE `id`=$id AND `isdelete`=0"; 
    $row =  $mysql -> selectFreeRun($vehiclestatus);
    $statusresult = mysqli_fetch_array($row);

    if($statusresult > 0) {

        $status = 1;
        $statusdata['date'] = $statusresult['date'];
        $statusdata['depot_id'] = $statusresult['depot_id'];
        $statusdata['people_id'] = $statusresult['people_id'];
       
    }
    else
    {
        $status = 0;
    }

    $mysql -> dbDisConnect();

    $response['status'] = $status;

    $response['statusdata'] = $statusdata;

    echo json_encode($response); 

    // header("content-Type: application/json");

    // $status=0;

    // $id=$_POST['id'];
    // $valus[0]['date'] = $_POST['name'];
    // $valus[0]['depot_id'] = $_POST['refre'];
    // $valus[0]['people_id'] = $_POST['refre'];
    // $valus[0]['update_date'] = date('Y-m-d H:i:s');

    // $mysql = new Mysql();

    // $mysql -> dbConnect();

    // $statusresult = array('date','depot_id','people_id','update_date');
    // $where = 'id ='.$id;
    // $isactiveupdate = $mysql -> update('tbl_trainingsession',$valus,$statusresult,'update',$where);

    // if($isactiveupdate)
    // {
    //     $status=1;
    //     $title = 'Update';
    //     $message = 'Training has been succesfully Update.';
    // }
    // else
    // {
    //     $status=0;
    //     $title = 'Update Error';
    //     $message = 'Training can not been Update.';
    // }
    
    // $name = 'Update';
    // $mysql -> dbDisConnect();

    // $response['status'] = $status;
    // $response['title'] = $title;
    // $response['message'] = $message;
    // $response['name'] = $name;


    // $mysql -> dbDisConnect();
    // echo json_encode($response); 
}





?>