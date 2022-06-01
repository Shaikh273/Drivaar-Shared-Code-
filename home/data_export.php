
<?php
$page_title="Drivaar";
include 'DB/config.php';
$page_id=4;
if(!isset($_SESSION)) 
{
    session_start();
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $userid = $_SESSION['userid'];
    if($userid==1)
    {
      $uid='%';
    }
    else
    {
      $uid=$userid;
    }
    
}
else
{
  header("location: login.php");  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">

</head>
<body class="skin-default-dark fixed-layout">
<?php
include('loader.php');
?>
<div id="main-wrapper">
<?php
    include('header.php');
    // include('menu.php');
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Contractor</div>
                    <div class="col-md-6"> </div>
                    <div class="col-md-2"> 
                        
                    </div>    

                </div>
            </div>
            
            <div class="card-body " >
            	<div class="col-md-12">
            	    <form action="" method="post" id="form">
                    <div class="row">
                        <div class="col-md-4">
                          <div class="form-group has-primary">
                               <select class="select form-control custom-select" id="depot_id" name="depot_id" >
                                    <option value="%">All Depot</option>
                                    <?php
                                    $mysql = new Mysql();
                                    $mysql -> dbConnect();
                                    $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                        INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                        WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid`=".$userid;
                                    $strow =  $mysql -> selectFreeRun($statusquery);
                                    while($statusresult = mysqli_fetch_array($strow))
                                    {
                                      ?>
                                          <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                      <?php
                                    }
                                    $mysql -> dbDisconnect();
                                ?>
                               </select>    
                          </div>
                        </div>
                     
                        <div class="col-md-4">
                             <div class="form-group has-primary">
                            <select class="select form-control custom-select" id="statusid" name="statusid" >
                                <option value="%">All Status</option>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                                <option value="2">Onboarding</option>
                            </select>   
                            </div>
                        </div>
                        <div class="col-md-4">
                             <button type="submit" name="submit" class="btn btn-info" id="submit">Update</button>
                        </div>
                    </div>
                    </form>
                  </div>
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                    <th>Name</th>
                                    <th data-orderable="false">Email</th>
                                    <th data-orderable="false">Type</th>
                                    <th data-orderable="false">Phone</th>
                                    <th data-orderable="false">NI Number</th>
                                    <th data-orderable="false">Identifier</th>
                                    <th data-orderable="false">Date of Birth</th>
                                    <th data-orderable="false">UTR</th>
                                    <th data-orderable="false">Start Date</th>
                                    <th data-orderable="false">Leave Date</th>
                                    <th data-orderable="false">Street Address</th>
                                    <th data-orderable="false">City</th>
                                    <th data-orderable="false">State</th>
                                    <th data-orderable="false">Postcode</th>
                                    <th data-orderable="false">Register Number</th>
                                    <th data-orderable="false">VAT</th>
                                    <th data-orderable="false">Bank Name</th>
                                    <th data-orderable="false">Account Number</th>
                                    <th data-orderable="false">Sort Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    
                                    $mysql = new mysql();
                                    $mysql->dbConnect();
                                    $depotid = '%';
                                    $statusid = '%';
                                    if(isset($_REQUEST['depot_id'])) {
                                        $depotid = $_REQUEST['depot_id'];
                                    } 
                                    if(isset($_REQUEST['statusid'])){
                                         $statusid = $_REQUEST['statusid'];
                                    }
                                    // "SELECT DISTINCT c.* FROM `tbl_contractor` c INNER JOIN tbl_workforcedepotassign w ON w.`depot_id`=c.`depot` WHERE c.`depot` LIKE '%' AND c.`isactive` LIKE '%' AND c.`isdelete`=0 AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL";
                                    $query = "SELECT DISTINCT c.* FROM `tbl_contractor` c INNER JOIN tbl_workforcedepotassign w ON w.`depot_id`=c.`depot` WHERE c.`depot` LIKE '".$depotid."' AND c.`isactive` LIKE '".$statusid."' AND c.`isdelete`=0 AND w.`wid`=$userid AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL";
                                    $userrow =  $mysql -> selectFreeRun($query);
                                    $data = array();
                                    $i = 1;
                                    while($userresult = mysqli_fetch_array($userrow))
                                    {
                                        
                                        if($userresult['type']==1)
                                        {
                                            $typename = 'Self Employeed';
                                        }
                                        else if($userresult['type']==2)
                                        {
                                            $typename = 'Limited Company';
                                        }
                                      
                                   ?>
                                    <tr>
                                        <td><?php echo $userresult['name'];?></td>
                                        <td><?php echo $userresult['email'];?></td>
                                        <td><?php echo $typename;?></td>
                                        <td><?php echo $userresult['contact'];?></td>
                                        <td><?php echo $userresult['ni_number'];?></td>
                                        <td><?php echo $userresult['employee_id'];?></td>
                                        <td><?php echo $userresult['dob'];?></td>
                                        <td><?php echo $userresult['utr'];?></td>
                                        <td><?php echo $userresult['start_date'];?></td>
                                        <td><?php echo $userresult['leave_date'];?></td>
                                        <td><?php echo $userresult['street_address'];?></td>
                                        <td><?php echo $userresult['city'];?></td>
                                        <td><?php echo $userresult['state'];?></td>
                                        <td><?php echo $userresult['postcode'];?></td>
                                        <td><?php echo $userresult['id'];?></td>
                                        <td><?php echo $userresult['vat_number'];?></td>
                                        <td><?php echo $userresult['bank_name'];?></td>
                                        <td><?php echo $userresult['account_number'];?></td>
                                        <td><?php echo $userresult['sort_code'];?></td>
                                    </tr>
                                    <?php
                                        $i++;
                                    }
                                    ?>
                                
                            </tbody>
                        </table>
                        <br>
                    </div>
            </div>
        </div>        
    </main>
</div>

<?php
  include('footer.php');
?>
</div>
</div>

<?php
  include('footerScript.php');
?>

<?php 

   
?>

<!-- start - This is for export functionality only -->
 <script src="../assets/node_modules/datatables/datatables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<!-- end - This is for export functionality only -->
 <script>
    
    var table = $('#myTable').DataTable({
        'pageLength': 100,
        dom: 'Bfrtip',
        'destroy':true,
        buttons: [
            'excel','csv'
        ]
    });
    $('.buttons-excel','.buttons-csv').addClass('btn btn-primary mr-1');
    $("#submit").on("click", function () { 
        $('#myTable').DataTable();
        // $('#form')[0].reset();
    });
    </script>
</body>

</html>