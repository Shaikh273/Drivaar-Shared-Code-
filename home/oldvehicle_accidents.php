 
<?php
$page_title="Vehicle accident";
include 'DB/config.php';
    $page_title = "Vehicles Expense";
    $page_id=64;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        
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
                    <div class="header">Accident</div>

                     <div> 
                     	<?php
                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][65]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                        { ?>
                            <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add accident</button>
                          <?php
                        }
                        else
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
                        

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View accident</button>
                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  	<div class="col-md-12">
	                    <form method="post" id="accidentForm" name="accidentForm" action="">
	                        <input type="hidden" name="id" id="id" value="">  
	                            <div class="form-body">
	                                <div class="row p-t-20">
	                                    <div class="col-md-4">
	                                          <div class="form-group">
	                                              <label class="control-label">Driver *</label>
	                                               <select class="select form-control custom-select" id="driver" name="driver">
	                                                   <?php
	                                                    $mysql = new Mysql();
	                                                    $mysql -> dbConnect();
	                                                    $exquery = "SELECT * FROM `contractor_detail`";
	                                                    $exrow =  $mysql -> selectFreeRun($exquery);
	                                                    while($coresult = mysqli_fetch_array($exrow))
	                                                    {
	                                                      ?>
	                                                         <option value="<?php echo $coresult['id']?>"><?php echo $coresult['name']?></option>
	                                                      <?php
	                                                    }
	                                                    $mysql -> dbDisconnect();
	                                                ?>
	                                              </select>
	                                          </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                          <div class="form-group">
	                                              <label class="control-label">Vehicle *</label>
	                                               <select class="select form-control custom-select" id="vehicle" name="vehicle">
	                                                 <?php
	                                                    $mysql = new Mysql();
	                                                    $mysql -> dbConnect();
	                                                    $expquery = "SELECT v.`id`,v.`registration_number`,vs.`name` FROM `tbl_vehicles` v LEFT JOIN `tbl_vehiclesupplier` vs ON vs.`id`=v.`supplier_id` WHERE v.`isdelete`=0";
	                                                    $exprow =  $mysql -> selectFreeRun($expquery);
	                                                    while($result = mysqli_fetch_array($exprow))
	                                                    {
	                                                      ?>
	                                                          <option value="<?php echo $result['id']?>"><?php echo $result['name']. '( ' . $result['registration_number'].')'?></option>
	                                                      <?php
	                                                    }
	                                                    $mysql -> dbDisconnect();
	                                                ?>
	                                              </select> 
	                                          </div>
	                                    </div>
	                                    <div class="col-md-4">
		                                    <div class="form-group">
	                                          <label class="control-label">Date Occurred *</label>
	                                          <div class="input-group">
	                                            <input type="text" class="form-control mydatepicker" id="dateoccurred" name="dateoccurred" placeholder="mm/dd/yyyy">
	                                            <div class="input-group-append">
	                                                <span class="input-group-text"><i class="icon-calender"></i></span>
	                                            </div>
	                                          </div>
		                                    </div>
	                                	</div>
	                                </div>

	                                <div class="row">
	                                	<div class="col-md-4">
		                                    <div class="form-group">
		                                        <label class="control-label">Type *</label>
		                                        <select class="select form-control custom-select" id="type" name="type">
	                                             <?php
	                                                $mysql = new Mysql();
	                                                $mysql -> dbConnect();
	                                                $query = "SELECT * FROM `tbl_vehicletypeaccident` WHERE `isdelete`=0 AND `isactive`=0";
	                                                $row =  $mysql -> selectFreeRun($query);
	                                                while($result1 = mysqli_fetch_array($row))
	                                                {
	                                                  ?>
	                                                      <option value="<?php echo $result1['id']?>"><?php echo $result1['name']?></option>
	                                                  <?php
	                                                }
	                                                $mysql -> dbDisconnect();
	                                            ?>
		                                        </select> 
		                                    </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                        <div class="form-group">
	                                           <label class="control-label">Stage *</label>
	                                            <select class="select form-control custom-select" id="stage" name="stage">
	                                             <?php
	                                                $mysql = new Mysql();
	                                                $mysql -> dbConnect();
	                                                $query1 = "SELECT * FROM `tbl_vehicleaccidentstage` WHERE `isdelete`=0 AND `isactive`=0";
	                                                $row1 =  $mysql -> selectFreeRun($query1);
	                                                while($result2 = mysqli_fetch_array($row1))
	                                                {
	                                                  ?>
	                                                      <option value="<?php echo $result2['id']?>"><?php echo $result2['name']?></option>
	                                                  <?php
	                                                }
	                                                $mysql -> dbDisconnect();
	                                            ?>
	                                      		</select> 
	                                        </div>
	                                    </div>
	                                    <div class="col-md-4">
	                                        <div class="form-group">
	                                           <label class="control-label">Reference *</label>
	                                            <input type="text" id="reference" name="reference" class="form-control" placeholder="">
	                                        </div>
	                                    </div>
	                                </div>
	                          
	                                <div class="row">
	                                    <div class="col-md-12">
	                                          <div class="form-group">
	                                              <label class="control-label">Description *</label>
	                                              <textarea type="text" id="description" name="description" class="form-control" placeholder="" rows="2"></textarea>	
	                                          </div>
	                                    </div>
	                                </div>

									<div class="row">
									    <div class="col-md-6">
									        <div class="form-group">
									            <label class="control-label">Other Person *</label>
									            <input type="text" class="form-control" id="otherperson" name="otherperson" placeholder="">
									        </div>
									        <div class="form-group">
									            <label class="control-label">Other vehicle plate number *</label>
									            <input type="text" class="form-control" id="otherplate" name="otherplate" placeholder="">
									        </div>
									        <div class="form-group">
									            <label class="control-label">Contact phone number *</label>
									            <input type="text" class="form-control" id="contactphone" name="contactphone" placeholder="">
									        </div>
									        <div class="form-group">
									            <label class="control-label">Other notes *</label>
									            <textarea type="text"  rows="2" class="form-control" id="othernotes" name="othernotes" placeholder=""></textarea>
									        </div>
									        <div class="form-group">
									          <label class="custom-control custom-checkbox m-b-0">
									              <input type="checkbox" name="reported_insurance"  id="reported_insurance" class="custom-control-input">
									              <span class="custom-control-label">Reported to the insurance company</span>
									          </label>
									        </div>
									    </div>
									    <div class="col-md-6">
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

                                    <th>File</th>
                                    <th>Date</th>
                                    <th data-orderable="false">Status</th>

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
        //update - 66 ,delete - 67
        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadvehicleaccidanttabledata'
                        }
                    },
                    'columns': [
                        { data: 'file' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        $("#accidentForm").validate({

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
                    data: $("#accidentForm").serialize()+"&action=VehicleaccidentForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#accidentForm')[0].reset();
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

    function setdriver(value)
    {
         $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'VehicleSetDriver', id: value},
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
  
    function edit(id)
    {

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'VehicleContactsUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#name').val($result_data['name']);
                $('#email').val($result_data['email']);
                $('#phone').val($result_data['phone']);
                $('#street_address').val($result_data['street_address']);
                $('#postcode').val($result_data['postcode']);
                $('#city').val($result_data['city']);
                $('#state').val($result_data['state']);

                $("#submit").attr('name', 'update');

                $("#submit").text('Update');

            }

        });
    }

    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'VehicleContactsDeleteData', id: id},

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
        $('#accidentForm')[0].reset(); 

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