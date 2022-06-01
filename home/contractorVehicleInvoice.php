<?php
include 'DB/config.php';
  $page_title = "Contractor Vehicles Invoices";
  $page_id=7;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['cid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        if($cntresult['isactive']==0)
        {
            $colorcode= "green";
            $statusname = "Active";
        }
        else
        {
            $colorcode= "red";
            $statusname = "Inactive";
        }
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
    <meta name="viewport" content="cidth=1024">
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
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
                            <div class="header">Contractor / <?php echo $cntresult['name'];?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                               </a>
                              <a href="editcontractor.php"> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                              </a>
                           </div>
                        </div>
                    </div>
                    <div class="card-body">
                          <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="mr-2">
                                        <span class="label label-success">Active</span>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                      <i class="fas fa-suitcase"></i> 
                                      <?php
                                      if($cntresult['type']==1)
                                      {
                                        echo 'self-employed';
                                      }
                                      else
                                      {
                                        echo 'company';
                                      }
                                      ?>
                                     
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                       <i class="fas fa-envelope-open"></i>
                                        <?php echo $cntresult['email'];?>
                                    </div>
                                    <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-warehouse"></i>
                                        <?php echo $cntresult['depot_type'];?>
                                    </div>
                                </div>  
                          </div>
                          <br><hr>
                         <?php include('contractor_setting.php');?>
                        <div class="row">
                            <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
                            <div class="card-header" style="background-color: #fff;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Invoices</div>
                                    <div> 
                                      <button type="button" disabled class="btn btn-secondary">Total : <b><i class="fas fa-pound-sign"></i> 0.00</b></button>
                                      <div class="btn-group">
                                          <button class="btn btn-secondary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Timesheet</button>
                                          <ul class="dropdown-menu">
                                             <li><a class="dropdown-item" href="contractorInvoice.php">Timesheet Invoice</a></li>
                                             <li><a class="dropdown-item" href="#">Chart</a></li>
                                          </ul>
                                      </div>
                                      <div class="btn-group">
                                          <button class="btn btn-secondary  dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Vehicle Hires</button>
                                          <ul class="dropdown-menu">
                                             <li><a class="dropdown-item" href="contractorVehicleInvoice.php">Vehicle Invoice</a></li>
                                             <li><a class="dropdown-item" href="#">Chart</a></li>
                                          </ul>
                                      </div>
                                   </div>
                                </div>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                  <div class="col-sm-8">
                                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                    <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                        <thead class="default">
                                            <tr role="row">
                                                <th>Total</th>
                                                <th data-orderable="false">Status</th>
                                                <th>Period</th>
                                                <th>Number</th>
                                                <th data-orderable="false">VAT</th>
                                                <th data-orderable="false">Due</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <br>
                                </div>
                                  </div>
                                  <div class="col-sm-4">
                                    <br><br>
                                    <div class="table-responsive m-t-40" style="margin-top: 5px;">
                                      <table id="myTable" class="display dataTable table table-responsive" aria-describedby="example2_info" style="width: 100%;">
                                          <thead class="default">
                                              <tr role="row">
                                                  <th>Action</th>
                                              </tr>
                                          </thead>
                                          <tbody id="actionlog">
                                          </tbody>
                                      </table>
                                      <br>
                                    </div>
                                  </div>
                              </div>
                                
                            </div>
                          </div>    
                        </div> 
                    </div>
                </div>                        
            </main>
        </div>
    </div>
</div>
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
              <button type="button" onclick="AddInvoiceStatusData();" class="btn btn-success waves-effect waves-light">Save changes</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>
<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">

  var cid=<?php echo $id ?>;

  $(document).ready(function(){
      actionLog();
      $("#AddFormDiv,#AddDiv").hide();
      $('#myTable').DataTable({
          'processing': true,
          'serverSide': true,
          'searching': false,
          'serverMethod': 'post',
          'ajax': {
              'url':'loadtabledata.php',
              'data': {
                  'action': 'loadContractorVehicleInvoicestabledata',
                  'cid':cid
              }
          },
          'columns': [
              { data: 'total' },
              { data: 'status' },
              { data: 'period' },
              { data: 'invoice_no' },
              { data: 'vat' },
              { data: 'due' },
              { data: 'Action' }
          ]
      });
  });

  function addstatus(id,statusid)
  {
      $('#addstatus').modal('show');
      $('#statusid').val(id);
      event.preventDefault();
      $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'InvoiceStatusData', id: id,statusid:statusid},
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

  function AddInvoiceStatusData()
  {
      var statusid = $('#statusid').val();
      var status = $('#modalstatus').val();
      $.ajax({
          type: "POST",
          url: "InsertData.php",
          data: {action : 'AddInvoiceStatusData', id: statusid, status:status},
          dataType: 'json',
          success: function(data) {
              if(data.status==1)
              {
                  var table = $('#myTable').DataTable();
                  table.ajax.reload();
                  actionLog();
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

  function actionLog()
  {
    $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {action : 'ShowActionLog', type: 3,cid:cid},
          dataType: 'text',
          success: function(data) {
                $('#actionlog').html(data); 
          }

      });
  }
</script>
</body>
</html>