<?php
include 'DB/config.php';
    $page_title = "Vehicles Details";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {

        $id="";
        if(isset($_GET['vid']))
        {
            $id = base64_decode($_GET['vid']);
        }
        else if($_SESSION['vid']>0)
        {
            $id = $_SESSION['vid'];
        }else
        {
            header("Location:index.php");
        }
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vg.`name` as groupname,vm.`name` as makename,vmo.`name` as modelname,vi.`insurance` 
      FROM `tbl_vehicles` v 
      LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
      LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
      LEFT JOIN `tbl_vehiclegroup` vg ON vg.`id`=v.`group_id` 
      LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id` 
      LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
      LEFT JOIN `tbl_addvehicleinsurance` vi ON vi.`vehicle_id`=v.`id`
      WHERE v.`id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $mysql -> dbDisConnect();

    }else
    {
        if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
        {
           header("location: login.php");
        }
        else
        {
           header("location: login.php");  
        }
    }
                               
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <style type="text/css">
        .datacard {
          border: 1px solid pink;
          height: auto;
          border-radius: 5px;
        }
        .dataheader {
           background-color: rgb(255 236 230);
        }
        .cardmb2 {
           height: 8px;
        }
        .fasicon {
            color: #f87171;
        }
        .smalldiv{
          font-size: 40px;
        }
        .fortWeight{
          font-weight: 500;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>
<div id="main-wrapper">
<?php include('header.php'); ?>
   <div class="page-wrapper">
        <div class="container-fluid">
            <main class="container-fluid  animated">
                <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $cntresult['colorcode']?>;"></i> <?php echo $cntresult['statusname'];?></button>
                              <a href="conditionalreport.php?vid=<?php echo base64_encode($id);?>" 
                                     class="btn btn-info"><i class="fas fa-pencil-alt"></i> New Conditional Report
                              </a>
                           </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include('vehicle_setting.php'); ?>
                        <div class="card" style="border: 1px solid #d1d5db;">   
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="header">Offences</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="AddDiv" value="add">View Offences</button>
                     </div>                   

            </div>
            
            <div class="card-body" id="ViewFormDiv">
                
                <div class="modal fade" id="empModal" role="dialog">
                <div class="modal-dialog">
             
                 <!-- Modal content-->
                 <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Offences Images</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body" id="modelBoby">
                      <input type="hidden" id="offences_id" name="offences_id">
                        <table id="Ownertbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                          <thead class="default">
                              <tr role="row">
                                  <th>File Name</th>
                                  <th>View</th>
                              </tr>
                          </thead>
                          <tbody id="Ownerbody">
                          </tbody>
                        </table>
                  </div>
                  <div class="modal-footer"> 
                   <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                 </div>
                </div>
             </div>
                
                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                    <th>Occured Date</th>
                                    <th>Vehicle</th>
                                    <th>Amount</th>
                                    <th>Admin Fee</th>
                                    <th data-orderable="false">Status</th>
                                    <!--<th data-orderable="false">Status</th>-->
                                    <!--<th data-orderable="false">Action</th>-->
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>

            </div>
            
        </div> 
                    </div>
                </div>    
    </main>
</div>
</div>

<?php
    include('footer.php');
?>
</div>

<?php
include('footerScript.php');
?>

<script type="text/javascript">

$(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': {
                    'action': 'loadvehicleOffencestabledata1'
                }
            },
            'columns': [
                { data: 'occurred_date' },
                { data: 'vehicle_id' },
                { data: 'amount' },
                { data: 'admin_fee' },
                { data: 'status' },
                // { data: 'status' }
                // { data: 'Action' }
            ]
        });
});
   
</script>
</body>

</html>