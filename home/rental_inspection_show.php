<?php
include 'DB/config.php';

    $page_title = "Inspection Issue";
    $page_id=5;
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
        $id = $_GET['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT r.*,r.`vehicle_id` as vid,v.`depot_id`as depotid,v.`registration_number` as regestrationnumber, s.`name` as suppliername,c.`name` as contaractorname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehiclerental_agreement` r
        INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
        INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
        INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
        INNER JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
        INNER JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
        WHERE r.`id`=$id AND r.`isdelete`=0 AND r.`userid` LIKE '".$uid."'";
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

    </head>

    <body class="skin-default-dark fixed-layout">

        <?php include('loader.php');?>

        <div id="main-wrapper">

            <?php include('header.php');?>

            <div class="page-wrapper">

                <div class="container-fluid">

                    <main class=" container-fluid  animated mt-4">

                        <div class="container"  style="max-width: 100%;">

                            <div class="row justify-content-center">

                                <div class="col-md-12">
                                    
                                    <div class="card">    

                                        <div class="card-header " style="background-color: rgb(255 236 230);">

                                            <div class="d-flex justify-content-between align-items-center">

                                              <div class="header">
                                            <label><h2 style="font-size:30px;">Hire Agreement</h2></label><br>
                                            <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                <i class="fa fa-user pr-2 pt-1" aria-hidden="true" style="font-size: 12px;"></i>
                                                <?php echo $cntresult['contaractorname']; ?>
                                            </small>
                                            <a href="#" class="text-gray-700">
                                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                    <i class="fas fa-car pr-2 pt-1"></i>
                                                    <?php echo $cntresult['suppliername'] . ' (' . $cntresult['regestrationnumber'] . ')'; ?>
                                                </small>
                                            </a>
                                            <a href="#" class="text-gray-700">
                                                <small class="bg-secondary rounded px-2 d-inline-flex align-items-center">
                                                    <i class="fas fa-clock pt-1 pr-2"></i>
                                                    <?php echo $cntresult['insert_date']; ?>
                                                </small>
                                            </a>
                                       </div>
                                            
                                            </div>

                                        </div>
                                
                                        
                                        <div class="card-body">
                                            <?php include('rent_agreement_setting.php'); ?>
                                            
                                            <input type="hidden" name="rental_id" value="<?php echo $_GET['id'];?>" id="rental_id">
                                            <input type="hidden" name="vid" value="<?php echo $cntresult['vid'];?>" id="vid">
                                            <input type="hidden" name="depot_id" value="<?php echo $cntresult['depotid'];?>" id="depot_id">
                                            <table id="myTable" class="display dataTable table table-responsive" style="font-weight: 400;">
                                                <thead class="default">
                                                    <tr>
                                                        <th>Depot</th>
                                                        <th>Date</th>
                                                        <th>Vehicle</th>
                                                        <th>User</th>
                                                        <th>Odometer</th>
                                                        <th data-orderable="false">Checks</th>
                                                        <th data-orderable="false">Status</th>
                                                        <th data-orderable="false"></th>
                                                        <th data-orderable="false"></th>
                                                        <th data-orderable="false"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                   
                                                </tbody>
                                            </table>
                                        </div>


                                    </div>        

                                </div>

                            </div>

                        </div>

                    </main>

                </div>

            </div>

        <?php include('footer.php');?>

        </div>

        <?php include('footerScript.php');?>
        
<script>
$(document).ready(function(){
    $('#vid').val();
     $('#myTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'loadtabledata1.php',
            'data': 
            {
                'action' : 'loadRentalInspectionShowtabledata',
                'vid':$('#vid').val(),
                // 'depotid':$('#depot_id').val(),
            }
        },
        'columns':[
            
            { data: 'depot'},
            { data: 'date'},
            { data: 'vehicle'},
            { data: 'user'},
            { data: 'odometer'},
            { data: 'check'},
            { data: 'status'},
            { data: 'inspect'},
            { data: 'approve'},
            { data: 'action'},
        ]
    });
});

function loadtable()
{
  var table = $('#myTable').DataTable();
  table.ajax.reload();
}

function inspectionresponse(id,status)
{
    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {
            action : 'inspectionissueresponse',
            id: id,
            status:status
        },

        dataType: 'json',

        success: function(data) 
        {
            if(data.status==1)
            {
                myAlert(data.title + "@#@" + data.message + "@#@success");
                location.reload(true);
            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            } 
        }

    });
}
</script>        

</body>
</html>