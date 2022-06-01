<?php
include 'DB/config.php';
    $page_title = "Rental Conditional Reports";
    $page_id=47;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
        if($userid==1)
        {
          $userid='%';
        }
        else
        {
          $userid=$userid;
        }
        $id = $_GET['id'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT r.*,r.`vehicle_id` as vid,v.`registration_number` as regestrationnumber, s.`name` as suppliername,c.`name` as contaractorname,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_vehiclerental_agreement` r
        INNER JOIN `tbl_contractor` c ON c.`id`= r.`driver_id`
        INNER JOIN `tbl_vehicles` v ON v.`id`=r.`vehicle_id`
        INNER JOIN `tbl_vehiclesupplier` s ON s.`id`=v.`supplier_id`
        INNER JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
        INNER JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
        WHERE r.`id`=$id AND r.`isdelete`=0 AND r.`userid` LIKE '".$userid."'";
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        //print_r($cntresult);
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
    <link href="dist/css/switch.css" rel="stylesheet" />
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        
    </style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>
<div id="main-wrapper">
<?php include('header.php'); ?>
   <div class="page-wrapper">
        <div class="container-fluid">
            <main class="container-fluid animated">
                <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
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
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div id="tbldata" class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: rgb(255 236 230);">
                                            <div class="header">Condition Reports</div>
                                           <!-- <div> -->
                                           <!--   <button type="button" class="btn btn-primary" id="uploaddamages" onclick="uploaddamages(this);" value="uploaddamages">Upload Damages</button>-->
                                           <!--</div>-->
                                        </div>
                                        <table id="damagetbl" class="table display dataTable table table-responsive no-footer" aria-describedby="example2_info">
                                          <thead class="default">
                                              <tr role="row">
                                                <th data-orderable="false">Conducted By</th>
                                                <th data-orderable="false">Driver</th>
                                                <th data-orderable="false">Damages</th>
                                                <th data-orderable="false">Time</th>
                                                <th data-orderable="false">Action</th>
                                              </tr>
                                          </thead>
                                          <tbody id="damagebody">
                                              
                                          </tbody>
                                        </table>
                                    </div>
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
<?php include('footerScript.php'); ?>

<script>

 $(function() {
     var userid = '<?php echo $userid; ?>';
     var vid = <?php echo $cntresult['vid']; ?>;
     var rid = <?php echo $id; ?>;
        $('#damagetbl').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            "paging":   false,
            "ordering": false,
            "searching": false,
            "info": false,
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadrentalconditionreportsdata',
                     'vid':vid,
                     'userid':userid,
                     'rid':rid
                }
            },
            'columns': [
                { data: 'conduct' },
                { data: 'driver' },
                { data: 'damages' },
                { data: 'time' },
                { data: 'Action' }
            ]
        });
         
        $("#estimatemodelform").on('submit',function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'InsertData.php',
                data: $("#estimatemodelform").serialize()+"&action=estimatemodelform",
                dataType: 'json',
                success: function(data)
                { 
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $("#insert").val('update');
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                    
                }
            });
        });   
    });
    
    function requestestimate(id, vid, additional_info) {
         $('#estimatemodel').modal('show');
         $('#reportid').text(id);
         $('#estimatemodel_id').val(id);
         $('#description').text(additional_info);
    }
    
    
    // function view(id,part) {
    //     $("#viewdata").show();
    //     $("#tbldata").hide();
    //     $("#uploaddamagesdiv").hide();
    //     $("area").each(function(){
    //       var data = $(this).data('maphilight') || {};
    //         data.alwaysOn = false;
    //         $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
    //     });
    //     $("area[title='"+part+"']").each(function(){
    //       var data = $(this).data('maphilight') || {};
    //         data.alwaysOn = true;
    //         $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
    //     });
        
    // }
    
    
</script>
</body>
</html>