
<?php
$page_title="Custom Invoice";
include 'DB/config.php';
$page_id=85;
if(!isset($_SESSION)) 
{
    session_start();
}
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $query = "SELECT COUNT(id) as cnt FROM `tbl_contractorinvoice` WHERE `istype`=4 AND `isdelete`=0 AND `isactive`=0";
    $row =  $mysql -> selectFreeRun($query);
    $result = mysqli_fetch_array($row);
    $mysql -> dbDisconnect();

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
                    <div class="header">Custom Invoices (<?php echo $result['cnt'];?>)</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view"><i class="fas fa-plus"></i>   Create New Invoice</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Invoice</button>
                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                    <form method="post" id="ContactForm" name="ContactForm" action="">
                        <input type="hidden" name="id" id="id" value="">  
                            <div class="form-body">
                                <div class="row p-t-20">
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Name *</label>
                                              <input type="text" id="name" name="name" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Email *</label>
                                              <input type="email" id="email" name="email" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Phone *</label><br>
                                            <input type="text" class="form-control" name="phone" id="phone">
                                        </div>
                                    </div>
                                </div>
                                <script src="countrycode/build/js/intlTelInput.js"></script>
                                <script>
                                    var input = document.querySelector("#phone");
                                    window.intlTelInput(input, {
                                        autoHideDialCode: false,
                                        utilsScript: "countrycode/build/js/utils.js",
                                    });
                                </script>

                                <div class="form-group" data-aire-component="group" data-aire-for="type">
                                    <div class="card-header" style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="m-0">Address Information</h5>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Street Address *</label>
                                              <input type="text" id="street_address" name="street_address" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">State/Province *</label>
                                              <input type="text" id="state" name="state" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Postcode *</label>
                                              <input type="text" id="postcode" name="postcode" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">City *</label>
                                              <input type="text" id="city" name="city" class="form-control" placeholder="">
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
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <!-- <th>Period</th> -->
                                    <th>Due Date</th>
                                    <th data-orderable="false">VAT</th>
                                    <th data-orderable="false">Accountant</th>
                                    <th data-orderable="false"></th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>

            </div>
        </div>        
    </main>
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
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadCustomInvoicetabledata'
                        }
                    },
                    'columns': [
                        { data: 'amount' },
                        { data: 'status' },
                        { data: 'name' },
                        // { data: 'period' },
                        { data: 'due' },
                        { data: 'vat' },
                        { data: 'accountant' },
                        { data: 'Action' }
                    ]
        });

        $("#ContactForm").validate({

            rules: {

                name: 'required',
                email: {
                  required: true,
                  email: true,
                },
                phone: 'required',
                street_address: 'required',
                postcode: 'required',
                city: 'required',
                state: 'required',

            },
            messages: {
                name: "Please enter your document type",
                email: {
                  required: "Please enter your email",
                  email: "Please enter your valid email",
                },
                phone: "Please enter your phone",
                street_address: "Please enter your street address",
                postcode: "Please enter your postcode",
                city: "Please enter your city",
                state: "Please enter your state",
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#ContactForm").serialize()+"&action=VehicleContactForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#ContactForm')[0].reset();
                            if(data.name == 'Update')
                            {
                                var table = $('#myTable').DataTable();
                                table.ajax.reload();
                                $("#AddFormDiv,#AddDiv").hide();
                                $("#ViewFormDiv,#ViewDiv").show();
                            }
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
               // return false;
            }
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

    function ShowHideDiv(divValue)
    {
        $('#ContactForm')[0].reset(); 

        if(divValue == 'view')
        {

            $("#submit").attr('name', 'insert');
            $("#submit").text('Submit');
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