<?php
include 'DB/config.php';
$page_title="Vehicle Offences";
$page_id = 59;
 if(!isset($_SESSION)) 
    { 
        session_start();  
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid=$_SESSION['userid'];
        if($userid==1)
        {
           $uid='%'; 
        }
        else
        {
           $uid = $_SESSION['userid']; 
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
<?php include('loader.php');?>
<div id="main-wrapper">
<?php include('header.php');
// include('menu.php');
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Offences</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Offences</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Offences</button>
                     </div>                   

                </div>
            </div>
            
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                    <form method="post" id="offencesForm" name="offencesForm" action="">
                        <input type="hidden" name="id" id="id" value="">  
                        <input type="hidden" name="userid" id="userid" value="<?php echo $userid;?>">
                            <div class="form-body">

                                <div class="form-group" data-aire-component="group" data-aire-for="type">
                                    <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="m-0">PCN Allocation <small> (Main details of the PCN Ticket)</small></h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-t-20">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Date *</label>
                                            <div class='input-group date' id=''>
                                                <input type='datetime-local' class="form-control" name="occurred_date" id='occurred_date' required="">
                                            </div>
                                            <div id="msg" class="mt-1 pl-1" style="color: red;"></div>
                                        </div>
                                    </div>
                    
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Vehicle *</label>
                                               <select class="select form-control custom-select" id="vehicle" name="vehicle_id" onchange="setdriver(this.value);" required="">
                                                <?php
                                                    $mysql = new Mysql();
                                                    $mysql -> dbConnect();
                                                    $vquery = "SELECT DISTINCT v.*,vsp.`name` as suppliername
                                                            FROM `tbl_vehicles` v 
                                                            LEFT JOIN `tbl_vehiclesupplier` vsp ON vsp.`id`=v.`supplier_id`
                                                            INNER JOIN tbl_workforcedepotassign w ON v.`depot_id` IN (w.`depot_id`) AND w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            WHERE v.`isdelete`= 0 ";
                                                    $vrow =  $mysql -> selectFreeRun($vquery);
                                                    while($vresult = mysqli_fetch_array($vrow))
                                                    {
                                                      ?>
                                                          <option value="<?php echo $vresult['id']?>"><?php echo $vresult['suppliername']. ' ( '.$vresult['registration_number'].' )'?></option>
                                                      <?php
                                                    }
                                                    $mysql -> dbDisconnect();
                                                ?>
                                              </select> 
                                          </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                          <div class="form-group">
                                          <label class="control-label">Driver *</label><br>
                                           <select class="select form-control custom-select" id="driver" name="driver" required="" disabled="">
                                              
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Identifier *</label>
                                              <input type="text" id="identifier" name="identifier" class="form-control" placeholder="GHB 1243 GB" required="">
                                          </div>
                                    </div>
                                </div>

                                <div class="form-group" data-aire-component="group" data-aire-for="type">
                                    <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="m-0">PCN Details <small> (Main details of the PCN Ticket)</small></h5>
                                        </div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Type *</label>
                                              <select class="select form-control custom-select" id="type" name="type">
                                                <option value="0">---</option>
                                                <?php
                                                    $mysql = new Mysql();
                                                    $mysql -> dbConnect();
                                                    $supplierquery1 = "SELECT * FROM `tbl_vehicleoffencestype` WHERE `isdelete`= 0";
                                                    $supplierrow1 =  $mysql -> selectFreeRun($supplierquery1);
                                                    while($supplierresult1 = mysqli_fetch_array($supplierrow1))
                                                    {
                                                      ?>
                                                          <option value="<?php echo $supplierresult1['id']?>"><?php echo $supplierresult1['type']?></option>
                                                      <?php
                                                    }
                                                    ?>
                                               
                                              </select> 
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Hire Company *</label>
                                               <select class="select form-control custom-select" id="hirecompany" name="hirecompany" >
                                                 <?php
                                                   
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
                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Hirer Reference *</label>
                                               <input type="text" id="hirerreference" name="hirerreference" class="form-control" required>
                                          </div>
                                    </div>
                                </div>

                                 <div class="row">
                                    <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label">Description *</label>
                                              <textarea type="text" id="description" name="description" class="form-control" rows="2"></textarea>
                                          </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="m-0">Amounts <small> (Details about the amount and admin fee of the ticket)</small></h5>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                              <label class="control-label">Amount *</label>
                                              <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="amount" name="amount" placeholder="" required="">
                                              </div>
                                        </div>
                                        <div class="form-group">
                                              <label class="control-label">Admin Fee *</label>
                                              <div class="input-group">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                                                </div>
                                                <input type="text" class="form-control" id="amountfee" name="amountfee" placeholder="" required="">
                                              </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h5 class="m-0">Attachments <small> (Upload scanned files for the pcn ticket, image or the pdf directly)</small></h5>
                                            </div>
                                        </div>
                                        <input type="file" class="form-control p-1" name="image" id="image" multiple="multiple" style="margin-top: 30px;" required="">
                                    </div>
                                </div>
                            </div>
                             <div class="form-actions">
                                <button type="submit" name="insert" class="btn btn-success" id="submit" onclick="insertdata(<?php echo $userid?>);">Submit</button>
                             </div>
                      </form>
                  </div>
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
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Admin Fee</th>
                                    <th data-orderable="false">Status</th>
                                    <th data-orderable="false">Action</th>
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

<?php
    include('footer.php');
?>
</div>

<?php
include('footerScript.php');
?>

<script>

    $(function(){
        var date1 = '';
        $.ajax({
            type: "POST",
            url: "setTimezone.php",
            async: false, 
            data: {action : 'GetDateFunction'},
            dataType: 'text',
            success: function(data) {
              date1=data;   
            }
        });
        var currentdate = new Date(date1);

        var datetime =currentdate.getDate() + "-"
                    + (currentdate.getMonth()+1)  + "-" 
                    + currentdate.getFullYear() + "  "  
                    + currentdate.getHours() + ":"  
                    + currentdate.getMinutes();
        $('#occurred_date').attr('max', datetime);
    });

    function insertdata(uid)
    {
      var user_id = uid; 
      var occurred_date = $('#occurred_date').val();
      var vehicle = $('#vehicle').val();
      var identifier = $('#identifier').val();
      var hirerreference = $('#hirerreference').val();
      var pcnticket_typeid = $('#type').val();
      var driver = $('#driver').val();
      var hirecompany = $('#hirecompany').val();
      var description = $('#description').val();
      var amount = $('#amount').val();
      var amountfee = $('#amountfee').val();
      
      var form_data = new FormData();
      var totalfiles = document.getElementById('image').files.length;
       for (var index = 0; index < totalfiles; index++) {
          form_data.append("files[]", document.getElementById('image').files[index]);
       }
       
       form_data.append("occurred_date",occurred_date);
       form_data.append("vehicle_id",vehicle);
       form_data.append("identifier",identifier);
       form_data.append("pcnticket_typeid",pcnticket_typeid);
       form_data.append("hirer_reference",hirerreference);
       form_data.append("driver_id",driver);
       form_data.append("company_id",hirecompany);
       form_data.append("description",description);
       form_data.append("amount",amount);
       form_data.append("admin_fee",amountfee);
       form_data.append("user_id",user_id);
       form_data.append("action","VehicleoffencesForm");
       
        event.preventDefault();
        $.ajax({
           url: 'InsertData1.php', 
           type: 'post',
           data: form_data,
           dataType: 'json',
           contentType: false,
           processData: false,
           success: function (response) {
              if(response.status==1)
              {
                  myAlert(response.title + "@#@" + response.message + "@#@success");
                  for(var index = 0; index < response.length; index++)
                  {
                      var src = response[index];
                      $('#image').append('<img src="'+src+'" width="200px;" height="200px">');
                  }
                  // window.location.replace("");
              }
              else
              {
                  myAlert(response.title + "@#@" + response.message + "@#@danger");
              }
          }
        });  
    }

    $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': {
                    'action': 'loadvehicleOffencestabledata'
                }
            },
            'columns': [
                { data: 'occurred_date' },
                { data: 'vehicle_id' },
                { data: 'pcnticket_typeid' },
                { data: 'amount' },
                { data: 'admin_fee' },
                { data: 'status' },
                { data: 'Action' }
            ]
        });
    });

    function setdriver(value)
    {
        var vid=value;
        var offencesdate=$('#occurred_date').val();
        if(offencesdate == ""){
            document.getElementById("occurred_date").style.borderColor = "red";
            $('#msg').html("Date is required");

        }
         $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {
                action : 'VehicleSetDriver',
            id: value,
            vid:vid,
            offencesdate:offencesdate
            },
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                    $('#driver').html(data.options);
                    $('#driver').select('refresh');
                }
               
            }
        });
    }
    
    function getmdl(id)
    {
      var id = id;
      $('#empModal').modal('show');
      $('#offences_id').val(id);
      $.ajax({
         url: 'loaddata.php', 
         type: 'post',
         data: {
            'action':'tbloffencesImageloaddata',
             offences_id:id
         },
         dataType: 'json',
         success: function (response) {
            if(response.status==1)
            {
                $('#Ownerbody').html(response.tbldata);
            }
            else
            {
                myAlert(response.title + "@#@" + response.message + "@#@danger");
            }
        }
      });
    }

    function deleterow(id)
    {
        $.ajax({
            type: "POST",
            url: "loaddata1.php",
            data: {action : 'VehicleOffencesDeleteData', id: id},
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
        $('#offencesForm')[0].reset(); 
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