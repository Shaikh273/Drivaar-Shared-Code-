
<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=48;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
      $userid=$_SESSION['userid'];
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
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
                 <div class="card">    
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Vehicles</div>

                             <div> 
                                <a href="request_vehicle.php">
                                    <button type="button" class="btn btn-primary"><i class="fa fa-chevron-down"></i> Request Vehicle</button>
                                </a>

                                 <?php
                                    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][49]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                                    { ?>
                                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Vehicles</button>
                                      <?php
                                    }
                                    else
                                    {
                                        header("location: login.php");
                                    }
                                ?>
                                <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Vehicles</button>
                             </div>                   

                        </div>
                    </div>
                    <div class="card-body" id="AddFormDiv">
                        <div class="row">
                          <div class="col-md-12">
                              <form method="post" id="VehicleForm" name="VehicleForm" action="">
                                  <input type="hidden" name="id" id="id" value="">
                                  <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">  
                                     <div class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label class="control-label">Vehicle Registration Number *</label>
                                                      <input type="text" id="registration_number" name="registration_number" class="form-control" placeholder="">
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="form-actions">
                                        <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                                     </div>
                              </form>
                          </div>
                        </div>
                    </div>
                    <div class="card-body" id="ViewFormDiv">
                        <div class="row">
                            <div class="col-md-3">
                              <div class="form-group has-primary">
                                  <select class="select form-control custom-select" id="supplier" name="supplier" onchange="loadtable();">
                                        <option value="%">All Supplier</option>
                                        <?php

                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $supplierquery = "SELECT * FROM `tbl_vehiclesupplier` WHERE `isdelete`= 0 AND `isactive`= 0";
                                          $supplierrow =  $mysql -> selectFreeRun($supplierquery);
                                          while($supplierresult = mysqli_fetch_array($supplierrow))
                                          {
                                            ?>
                                                <option value="<?php echo $supplierresult['id']?>"><?php echo $supplierresult['name']?></option>
                                            <?php
                                          }
                                          $mysql -> dbDisconnect();
                                        ?>
                                  </select> 
                              </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-primary">
                                      <select class="select form-control custom-select" id="status" name="status" onchange="loadtable();">
                                      <option value="%">All Status</option>
                                      <?php

                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $statusquery = "SELECT * FROM `tbl_vehiclestatus` WHERE `isdelete`= 0 AND `isactive`= 0";
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

                            <div class="col-md-3">
                               <div class="form-group has-primary">
                                      <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable();">
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

                            <div class="col-md-3">
                                <label class="custom-control custom-checkbox m-b-0">
                                    <input type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label">Owned vehicles</span>
                                </label>

                                <label class="custom-control custom-checkbox m-b-0">
                                    <input type="checkbox" class="custom-control-input">
                                    <span class="custom-control-label">Not inspected today</span>
                                </label>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group has-primary">
                                       <select class="select form-control custom-select" id="vehicle_type" name="vehicle_type" onchange="loadtable();">
                                        <option value="%">All Types</option>
                                        <?php
                                          $mysql = new Mysql();
                                          $mysql -> dbConnect();
                                          $typequery = "SELECT * FROM `tbl_vehicletype` WHERE `isdelete`= 0 AND `isactive`= 0";
                                          $typerow =  $mysql -> selectFreeRun($typequery);
                                          while($typeresult = mysqli_fetch_array($typerow))
                                          {
                                            ?>
                                                <option value="<?php echo $typeresult['id']?>"><?php echo $typeresult['name']?></option>
                                            <?php
                                          }
                                          $mysql -> dbDisconnect();
                                        ?>
                                 </select>    
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group has-primary">
                                      <select class="select form-control custom-select" id="group" name="group" onchange="loadtable();">
                                        <option value="%">All Groups</option>
                                        <?php
                                      $mysql = new Mysql();
                                      $mysql -> dbConnect();
                                      $groupquery1 = "SELECT * FROM `tbl_vehiclegroup` WHERE `isdelete`= 0 AND `isactive`= 0";
                                      $grouprow =  $mysql -> selectFreeRun($groupquery1);
                                      while($groupresult = mysqli_fetch_array($grouprow))
                                      {
                                        ?>
                                            <option value="<?php echo $groupresult['id']?>"><?php echo $groupresult['name']?></option>
                                        <?php
                                      }
                                      $mysql -> dbDisconnect();
                                        ?>
                                </select>   
                                </div>
                            </div>

                            <div class="col-md-3">
                                 <input type="text" class="form-control" data-aire-component="input" placeholder="Reg. Number" aria-label="Reg. Number" name="register_number" id="register_number" data-aire-for="register_number" required >
                            </div>

                            <div class="col-md-2">
                                  <button type="button" class="btn btn-info" onclick="loadtable();">Filter</button>
                            </div>

                         </div><hr><br>
                            <div class="table-responsive">       
                                 <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th style="width: 70px;">Supplier</th>
                                            <th>Reg. Plate</th>
                                            <th>Type</th>
                                            <th>Depot</th>
                                            <th>Group</th>
                                            <th style="width: 70px;">Insurance</th>
                                            <th style="width: 70px;">Owner(s)</th>
                                            <th data-orderable="false">Driver Today</th>
                                            <th data-orderable="false">Extras</th>
                                            <th data-orderable="false" style="width: 60px;">Inspected today?</th>
                                            <th data-orderable="false">Date</th>
                                            <th data-orderable="false">Status</th>
                                            <th data-orderable="false" style="width: 70px;">Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>  
                    </div>
                 </div>        
            </div>
    </main>
<div id="addstatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Change Status</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <form method="post" id="AddStatusForm" name="AddStatusForm" action="">
                  <input type="hidden" name="statusid" id="statusid">
                 <div class="form-group">
                      <label class="control-label">Status *</label>
                      <select class="select form-control custom-select" id="modalstatus" name="modalstatus">
                        
                      </select> 
                 </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="button" onclick="AddVehicleStatusData();" class="btn btn-success waves-effect waves-light">Save changes</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
<div id="ShowExtraData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Extra</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-md-12" id="extraoption">
                  
                </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
              'processing': true,
              "serverSide": true,
              "destroy" : true,
              'pageLength': 10,
              'dom': '<"pull-left"f><"pull-right"l>tip',
              "ajax":
              {
                  "url": "loadtabledata.php",
                  "type": "POST",
                  "data": function(d) 
                    {
                      d.action = 'loadvehicletabledata';
                      d.supplierid = $('#supplier').val();
                      d.status = $('#status').val();
                      d.did = $('#depot_id').val();
                      d.vtype = $('#vehicle_type').val();
                      d.vgroup = $('#group').val();
                      d.regno = $('#register_number').val();
                    }
              },
              'columns': [
                    { data: 'supplier_id' },
                    { data: 'registration_number' },
                    { data: 'type_id' },
                    { data: 'dpt_name' },
                    { data: 'group_id' },
                    { data: 'insurance' },
                    { data: 'owner_id' },
                    { data: 'Driver' },
                    { data: 'Extras' },
                    { data: 'Inspected today?' },
                    { data: 'Date' },
                    { data: 'Status' },
                    { data: 'Action' }
                ]  /* <---- this setting reinitialize the table */
        });

        $("#VehicleForm").validate({
        rules: {
            registration_number: 'required',
        },
        messages: {
            registration_number: "Please enter your vehicle registration number",
        },

        submitHandler: function(form) {
            event.preventDefault();
            var reg_number = $('#registration_number').val();
            $.ajax({
              url: 'loaddata.php',
              type: 'post',
              data: {action : 'check_vehicle_registration_number', reg_number : reg_number},
              dataType: 'json',
              success: function(response) {
                  if (response.status == 1)
                  {
                     myAlert("Error @#@ Vehicle Registration number has been already registered.@#@danger");
                  }
                  else
                  {
                     $.ajax({
                      url: "InsertData.php", 
                      type: "POST", 
                      dataType:"JSON",            
                      data: $("#VehicleForm").serialize()+"&action=Vehicleform",
                      cache: false,             
                      processData: false,      
                      success: function(data) {
                          if(data.status==1)
                          {
                              window.location = '<?php echo $webroot ?>vehicle_details.php?vid='+data.vid;
                              myAlert(data.title + "@#@" + data.message + "@#@success");
                              
                          }
                          else
                          {
                              myAlert(data.title + "@#@" + data.message + "@#@danger");
                          }
                      }
                });
                  }
              }
             });
        }

        });
    });


    function loadtable()
    {
        var table = $('#myTable').DataTable();
        table.ajax.reload();
    }


    // $(function(){
    //     $("#VehicleForm").on("submit", function(e){
    //         var form = this;
    //         var reg_number = $('#registration_number').val();
    //         $.ajax({
    //           url: 'loaddata.php',
    //           type: 'post',
    //           data: {action : 'check_vehicle_registration_number', reg_number : reg_number},
    //           dataType: 'json',
    //           success: function(response) {
    //               if (response.status == 1)
    //               {
    //                  myAlert("Error @#@ Vehicle Registration number has been already registered.@#@danger");
    //                  return false;
    //               }
    //               else
    //               {
    //                   $('#VehicleForm').submit();
    //                   return true;
    //               }
    //           }
    //          });
    //          e.preventDefault();
    //     });
    // });

    function ShowExtra(id)
    {
        $('#ShowExtraData').modal('show');
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'VehicleExtraData', id: id},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                   $('#extraoption').html(data.extraresult);
                }
            }

      });

    }


    function addstatus(id)
    {
        $('#addstatus').modal('show');
        $('#statusid').val(id);
        event.preventDefault();
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'VehicleStatusData', id: id},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                   $('#modalstatus').html(data.options);
                   $('#modalstatus').select('refresh');
                }
            }

        });
    }

    function AddVehicleStatusData()
    {
        var statusid = $('#statusid').val();
        var status = $('#modalstatus').val();
        $.ajax({
            type: "POST",
            url: "InsertData.php",
            data: {action : 'AddVehicleStatusData', id: statusid, status:status},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                    var table = $('#myTable').DataTable();
                    table.ajax.reload();
                    myAlert("Update @#@ Status has been changed successfully.@#@success");
                }
                else
                {
                    myAlert("Update Error @#@ Status can not been changed successfully.@#@danger");
                }
                $('#addstatus').modal('hide');
            } 
        });        
    }

    function loadpage(vid)
    {
        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'VehicleSetSessionData', vid: vid},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                    window.location = '<?php echo $webroot ?>vehicle_details_show.php';
                }
                
            }

        });
      
    }

    function edit(id)
    {
        window.location = '<?php echo $webroot ?>vehicle_details.php?vid='+id;
    }

    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'VehicleDeleteData', id: id},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                    var table = $('#myTable').DataTable();
                    table.ajax.reload();
                    myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                }
                else
                {
                    myAlert("Delete @#@ Data can not been deleted.@#@danger");
                }
            }

        });
    }



    function ShowHideDiv(divValue)
    {

        if(divValue == 'view')

        {

            $('#VehicleForm')[0].reset(); 

            $("#AddFormDiv,#AddDiv").show();

            $("#ViewFormDiv,#ViewDiv").hide();     

        }

        if(divValue == 'add')
        {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
            $("#AddFormDiv,#AddDiv").hide();

            $("#ViewFormDiv,#ViewDiv").show();     

        }
    }
 
    </script>
</body>

</html>