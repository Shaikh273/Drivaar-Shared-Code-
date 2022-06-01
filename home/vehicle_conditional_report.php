<?php
$page_title = "Vehicle Conditional Report";
include 'DB/config.php';
$page_id=58;
if(!isset($_SESSION)) 
{
    session_start();
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1))
{
    $userid=$_SESSION['userid'];
    if($userid==1)
    {
      $userid='%';
    }
    else
    {
      $userid=$_SESSION['userid'];
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

    <title><?php echo $page_title; ?></title>

    <?php include('head.php'); ?>

    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <style type="text/css">
        .titlehead {
            font-size: 28px;
            font-weight: 500;
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">

    <?php include('loader.php'); ?>

    <div id="main-wrapper">

        <?php
        include('header.php');
        ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="card">
                    <?php include('report.php'); ?>
                            <div class="card-body">
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="conduct" name="conduct">
                                                <option value="%">Conducted By</option>
                                                <?php
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                    if ($userid == 1) 
                                                    {
                                                        $qry = '';
                                                    } else {
                                                        $qry = ' AND u.`depot` IN (w.`depot_id`)';
                                                    }
                                                  $query = "SELECT DISTINCT u.* FROM `tbl_user` u  
                                                                    INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $userid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                                    WHERE u.`isdelete`=0 AND u.`id` NOT IN (1)".$qry;
                                                  $row =  $mysql -> selectFreeRun($query);
                                                  while($result = mysqli_fetch_array($row))
                                                  {
                                                    ?>
                                                        <option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                                ?>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="vehicle" name="vehicle">
                                                <option value="%">All Vehicle</option>
                                                <?php
        
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                  $supplierquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                                            FROM `tbl_vehicles` v 
                                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                                            WHERE v.`isdelete`= 0 AND v.`status` NOT IN (5)";
                                                  $supplierrow =  $mysql -> selectFreeRun($supplierquery);
                                                  while($supplierresult = mysqli_fetch_array($supplierrow))
                                                  {
                                                    ?>
                                                        <option value="<?php echo $supplierresult['id']?>"><?php echo $supplierresult['suppliername']?> (<?php echo $supplierresult['registration_number']?>)</option>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                                ?>
                                          </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group has-primary">
                                            <select class="select2 form-control custom-select" id="driver" name="driver">
                                                <option value="%">All Driver</option>
                                                <?php
        
                                                  $mysql = new Mysql();
                                                  $mysql -> dbConnect();
                                                  $cquery = "SELECT DISTINCT c.* FROM `tbl_contractor` c 
                                                            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$userid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            WHERE c.`depot` IN (w.depot_id) AND c.`isdelete`=0 AND c.`isactive`=0";
                                                  $crow =  $mysql -> selectFreeRun($cquery);
                                                  while($crresult = mysqli_fetch_array($crow))
                                                  {
                                                    ?>
                                                        <option value="<?php echo $crresult['id']?>"><?php echo $crresult['name']?></option>
                                                    <?php
                                                  }
                                                  $mysql -> dbDisconnect();
                                                ?>
                                            </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-info" onclick="ajaxcall();">Filter </button>
                                    </div>
                                </div>
                                <!--<div class="row">-->
                                <!--    <div class="col-md-4">-->
                                <!--        <div class="input-daterange input-group" id="date-range">-->
                                <!--            <input type="text" class="form-control" name="start_date" id="start_date">-->
                                <!--            <div class="input-group-append">-->
                                <!--                <span class="input-group-text"><i class="icon-calender"></i></span>-->
                                <!--                <span class="input-group-text bg-info b-0 text-white">TO</span>-->
                                <!--            </div>-->
                                <!--            <input type="text" class="form-control" name="end_date" id="end_date">-->
                                <!--            <div class="input-group-append">-->
                                <!--              <span class="input-group-text"><i class="icon-calender"></i></span>-->
                                <!--            </div>-->
                                <!--        </div>-->
                                <!--    </div>-->
                                <!--    <div class="col-md-2">-->
                                <!--        <button type="button" class="btn btn-info" onclick="ajaxcall();">Filter </button>-->
                                <!--    </div>-->
                                <!--</div>-->
                                <br>
                                <div class="card">
                                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="header">Condition Reports</div>

                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <table id="myTable" class="display dataTable table table-responsive" style="font-weight: 400;" role="grid" aria-describedby="example2_info">
                                            <thead class="default">
                                                <tr role="row">
                                                    <th>Conduct By</th>
                                                    <th>Vehicle</th>
                                                    <th>Driver</th>
                                                    <th>Fuel</th>
                                                    <th>Damages</th>
                                                    <th>New Damages</th>
                                                    <th>Date</th>
                                                    <th>Action</td>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
            </div>
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
                                                        <td id="veh_reg_no"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-400 text-right">Make/Model:</td>
                                                        <td id="veh_model"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-gray-400 text-right">Damages:</td>
                                                        <td > 
                                                            <ol class="" id="damages">
                                                            
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
    <?php
    include('footer.php');
    ?>
    </div>
    <?php
    include('footerScript.php');
    ?>
 <script type="text/javascript">

    $(document).ready(function(){
        ajaxcall();
        $("#conduct").on('change',function(){
           ajaxcall();
        });
        $("#vehicle").on('change',function(){ 
           ajaxcall();
        });
        $("#driver").on('change',function(){
           ajaxcall();
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
    
    function ajaxcall() {
         $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'destroy': true,
            'dom': '<"pull-left"f><"pull-right"l>tip',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadconditionalreportdata',
                     'vehicle':$("#vehicle").find(":selected").val(),
                     'driver':$("#driver").find(":selected").val(),
                     'conduct':$("#conduct").find(":selected").val()
                },
                complete: function (data) {
                    var data1 = data['responseJSON']['aaData'];
                    //console.log(data1);
                        $("#veh_reg_no").text(data1[0].reg_no);
                        $("#veh_model").text(data1[0].model);
                        $("#damages").html(data1[0].div);
                    }
            },
            'columns': [
                { data: 'Conduct' },
                { data: 'Vehicle' },
                { data: 'Driver' },
                { data: 'Fuel' },
                { data: 'Damages' },
                { data: 'NewDamages' },
                { data: 'Date' },
                { data: 'Action' }
            ]
            
        });
    }

    function requestestimate(id, vid, additional_info) {
         $('#estimatemodel').modal('show');
         $('#reportid').text(id);
         $('#estimatemodel_id').val(id);
         $('#description').text(additional_info);
    }

    function loadtable()
    {
        var table = $('#myTable').DataTable();
        table.ajax.reload();
    }
    
    </script>



</body>



</html>