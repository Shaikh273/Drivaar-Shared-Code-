<?php
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}
include 'config.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($db,$_POST['search']['value']); // Search value

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (enquire.name LIKE '%".$searchValue."%' or enquire.email LIKE '%".$searchValue."%' or enquire.city LIKE '%".$searchValue."%' or enquire.mobile LIKE '%".$searchValue."%' or enquire.brand_name LIKE '%".$searchValue."%' or enquire.investment LIKE '%".$searchValue."%' or enquire.message LIKE '%".$searchValue."%') ";
}

$dtWhr = "";
if($_SESSION['whr']!="0" && $_SESSION['whr']!="1")
{
	$dtWhr = $_SESSION['whr'];
}

// $myfile = fopen("myfile.txt", "w") or die("Unable to open file!");
// fwrite($myfile, $_SESSION['whr']."\n");
// fclose($myfile);


## Total number of records without filtering
$sql1 = "select count(*) as allcount from enquire where enquire_type='franchise' AND status = 'Active' ".$dtWhr;
$sel = mysqli_query($db,$sql1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sql2 = "select count(*) as allcount from enquire WHERE enquire_type='franchise' AND status = 'Active' ".$searchQuery." ".$dtWhr;
$sel = mysqli_query($db,$sql2);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];
$ord="";
if($columnName!="Sno")
{
	$ord = $columnName." ".$columnSortOrder.", ";
}
## Fetch records
// $empQuery = "select enquire.*,admin.employee_id as eid from enquire left join admin on enquire.employee_ID=admin.id where enquire.status = 'Active' AND enquire.enquire_type='franchise' ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empQuery = "select enquire.*,admin.employee_id as eid from enquire left join admin on enquire.employee_ID=admin.id where enquire.status = 'Active' AND enquire.enquire_type='franchise' ".$searchQuery." ".$dtWhr." order by $ord id desc  limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($db, $empQuery);
//$data = array();





// $myfile = fopen("myfile.txt", "w") or die("Unable to open file!");
// fwrite($myfile, $_POST['pgid']);
// fclose($myfile);


$i=$row+1;
while ($row = mysqli_fetch_assoc($empRecords)) {
    $opt ="";
    $employee_id=$row['eid'];
    $employee_query=mysqli_query($db,"SELECT * FROM employee_master");
    while($employee_row=mysqli_fetch_array($employee_query)){
            if($employee_id == $employee_row['id']){
                    $opt .=  "<option value='".$employee_row['id']."' selected>".$employee_row['name']."</option>";
            }
            else{
                    $opt .=  "<option value='".$employee_row['id']."'>".$employee_row['name']."</option>";
            }
    }
    $sel = "<select  name='employee_name' class='form-control select2 employee_name' style='width: 100%;' userid=".$row['id']." onchange=\"idChanged(this)\">
            <option value=''>Select Employee</option>$opt </select> ";
            $del="";
    if($_POST['pgid'])
    { 
    $del = " 
        <a class='mb-control1 btn btn-danger btn-rounded btn-sm' onclick=\"return confirm('Are you sure ?')\" href='enquire_report.php?del=del&id=".base64_encode($row['id'])."'>
            <span class='fa fa-times'></span>
        </a>";
    }
    $msg = "<button class='mb-control1 btn btn-success btn-rounded btn-sm' onclick=\"getMsg('".$row['id']."')\">
            <span class='fa fa-eye'></span>
        </button>";
    $data[] = [
    	'Sno'=>$i,
    	'enquite_date'=>$row['enquite_date'],
    	'name'=>$row['name'],
    	'email'=>$row['email'],
    	'mobile'=>$row['mobile'],
    	'city'=>$row['city'],
    	'brand_name'=>$row['brand_name'],
    	'investment'=>$row['investment'],
    	'Message'=>$msg,
    	'emp_name'=>$sel,
    	'Action'=>$del];
    // base64_encode($row['message'])
    $i++;
}
if(count($data)==0){
$data[] = [
    	'Sno'=>"",
    	'enquite_date'=>"",
    	'name'=>"",
    	'email'=>"No data found!!!",
    	'mobile'=>"",
    	'city'=>"",
    	'brand_name'=>"",
    	'investment'=>"",
    	'Message'=>"",
    	'emp_name'=>"",
    	'Action'=>""];
    // base64_encode($row['message'])
    $i++;
}

## Response
$response = array(
    "draw" => $draw,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

// $myfile = fopen("myfile.txt", "w") or die("Unable to open file!");
// foreach($response as $k => $v)
// {
// 	if(is_array($v))
// 	{
// 		foreach($v as $k1 => $v1)
// 		{
// 			foreach($v1 as $k2 => $v2)
// 			{
// 				fwrite($myfile, $k." => ".$k1." => ".$k2." => ".$v2."\n");
// 			}
// 		}
// 	}else
// 	{
// 		fwrite($myfile, $k." => ".$v."---else\n");
// 	}
	
// }
// fclose($myfile);


// echo $response;
echo json_encode($response,JSON_UNESCAPED_SLASHES);

                         ?>                       