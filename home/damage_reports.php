<?php
include 'DB/config.php';
    $page_title = "Damage Reports";
    $page_id=53;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
        $id = $_SESSION['vid']; 
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT v.*,vs.`name` as statusname,vs.`colorcode` ,vsp.`name` as suppliername,vm.`name` as makename,vmo.`name` as modelname 
            FROM `tbl_vehicles` v 
            LEFT JOIN `tbl_vehiclestatus` vs ON vs.`id`=v.`status` 
            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id` 
            LEFT JOIN `tbl_vehiclemake` vm ON vm.`id`=v.`make_id`
            LEFT JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=v.`model_id`
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
                            <div class="header"><?php echo $cntresult['suppliername'].' ('.$cntresult['registration_number'].')';?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Action Log</button>
                               </a>
                              <button type="button" class="btn btn-secondary" disabled="true"><i class="fas fa-circle" style="color: <?php echo $cntresult['colorcode']?>;"></i> <?php echo $cntresult['statusname'];?></button>
                              <a href="conditionalreport.php"> 
                                    <button type="button" class="btn btn-info" ><i class="fas fa-pencil-alt"></i> New Conditional Report</button>
                              </a>
                           </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php include('vehicle_setting.php'); ?>
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
                                                <th data-orderable="false">New Damages</th>
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
            <div id="estimatemodel" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(255 236 230);">
                          <h4 class="modal-title">Request Estimate - Condition Report #</h4><h4 id="reportid" style="line-height: unset;"></h4>
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <form action="" method="post" id="estimatemodelform" name="estimatemodelform">
                            <input type="hidden" id="estimatemodel_id" name="estimatemodel_id" value="" class="form-control">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-7">
                                        <p class="font-weight-600">The service provides the following benefits:</p>
                            
                                        <div><span><i class="fas fa-check-circle"></i></span> Collection and Delivery of the vehicle from your adress - Free of charge</div>
                                        <div><span><i class="fas fa-check-circle"></i></span> Valet: Free of charge</div>
                                        <div><span><i class="fas fa-check-circle"></i></span> Labour rate: £28.00 per hour</div>
                                        <div><span><i class="fas fa-check-circle"></i></span> Parts: Retail cost + 2% for sundry items</div>
                                        <div><span><i class="fas fa-check-circle"></i></span> Guarantee of no further/additional poor repair charges from the rental provider</div>
                            
                                        <p class="mt-4">The requested estimate will cost you <strong>£10.00</strong> and will be taken from your payment method when our repair
                                            partner upload and provide you with the estimate.</p>
                                        <p>The estimate is valid for 5 working days from the date of receiving.</p><br/>
                            
                                        <div class="alert alert-warning" role="alert">
                                            <div class="d-flex align-items-baseline">
                                                <div>
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                            
                                                <div class="flex-grow-1">
                                                    N.B. The estimate is based on images but vehicle damages will be re-assessed ones on site to confirm the damage is as imaged and there are no hidden damages.”
                                                </div>
                            
                                            </div>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-5">
                                        <div class="table-responsive">
                                            <table class="table w-100 table-sm table-borderless  mb-4" borderless="borderless" small="small">
                            
                                                <tbody>
                                                    <tr>
                                                        <td class="text-gray-400 text-right">Vehicle:</td>
                                                        <td ><?php echo $cntresult['registration_number']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-400 text-right">Make/Model:</td>
                                                        <td ><?php echo $cntresult['makename'] ?> - <?php echo $cntresult['modelname'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-400 text-right">Damages:</td>
                                                        <td id="damages">
                                                            <ol class="">
                                                            <?php
                                                                $condition =['Broken','Dented','Chipped','Scratched','Missing','Worn'];
                                                                $mysql = new Mysql();
                                                                $mysql -> dbConnect();
                                                                $statusquery = "SELECT * FROM `tbl_vehicledamage_img` WHERE `isdelete`= 0 AND `isactive`= 0 
                                                                AND `userid`= $userid AND `state`=0 AND `vehicle_id`=".$id;
                                                                $strow =  $mysql -> selectFreeRun($statusquery);
                                                                while($statusresult = mysqli_fetch_array($strow))
                                                                {
                                                                  ?>
                                                                        <li><?php echo $statusresult['damage_part']?> - <u><?php echo $condition[$statusresult['condition']]?></u></li>
                                                                  <?php
                                                                }
                                                                $mysql -> dbDisconnect();
                                                            ?>
                                                            </ol>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group" >
                                            <label class="cursor-pointer" >
                                                Additional Information:
                                            </label>
                                            <textarea class="form-control" placeholder="Provide any additional information regarding the requested estimate, damage descriptions etc." rows="6" id="description" name="description"> </textarea>
                            
                                            <div class="text-danger" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="insert" id="insert" value="insert">
                                <button type="submit" class="btn btn-primary" id="submit" >Send Estimate Request</button>
                            </div>
                        </form>
              </div>
            </div>
            
        </div>
    </div>
</div>
<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>

<script>

 $(function() {
     var userid = <?php echo $userid ?>;
     var vid = <?php echo $id ?>;
        $('#damagetbl').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loaddamagereportsdata',
                     'vid':vid,
                     'userid':userid
                }
            },
            'columns': [
                { data: 'conduct' },
                { data: 'driver' },
                { data: 'damages' },
                { data: 'newdamages'},
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