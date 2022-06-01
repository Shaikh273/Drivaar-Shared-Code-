<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=144;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
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
<?php
$page_title = "Cost Location";
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
                      <?php include('setting.php'); ?>   
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header"></div>
                                 <div> 
                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add New</button>
                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Location</button>
                                </div>                   
            
                            </div>
                        </div>
                        <div class="row" id="tabsection" >
                            <div class="col-md-12">
                                <ul class="nav nav-tabs p-t-10" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" id="cnt" href="#home" role="tab" >Contractor</a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" id="work" href="#advance" role="tab" ><span class="hidden-sm-up"><i class="fas fa-clipboard-list"></i></span> <span class="hidden-xs-down">Workforce</span></a> </li>
                                </ul>
                                <div class="tab-content tabcontent-border">
                                    <div class="tab-pane active " id="home" role="tabpanel">
                                        <div class="card-body" id="ViewFormDiv1">
                                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                                <table id="myTable1" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                                    <thead class="default">
                                                        <tr role="row">
                                                            <th>Department</th>
                                                            <th data-orderable="false">Location</th>
                                                            <th data-orderable="false">Service</th>
                                                            <th data-orderable="false">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane " id="advance" role="tabpanel">
                                        <div class="card-body" id="ViewFormDiv2">
                                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                                <table id="myTable2" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                                    <thead class="default">
                                                        <tr role="row">
                                                            <th>Department</th>
                                                            <th data-orderable="false">Location</th>
                                                            <th data-orderable="false">Service</th>
                                                            <th data-orderable="false">Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <br>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-30" id="AddFormDiv">
                            <div class="row">
                              <div class="col-md-12">
                                  <form method="post" id="StatusForm" name="StatusForm" action="">
                                      <input type="hidden" name="id" id="id" value="">  
                                      <input type="hidden" name="userid" id="userid" value="<?php echo $userid?>"> 
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-1">
                                                    <label class="control-label text-left col-md-12">Type</label>
                                                    <div class="col-md-12">
                                                        <div class="radio-list">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="self" name="type" class="custom-control-input" value="0" >
                                                                <label class="custom-control-label" for="self">Contractor</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="private" name="type" class="custom-control-input" value="1">
                                                                <label class="custom-control-label" for="private">Workforce</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label class="control-label text-left col-md-12">Payment</label>
                                                    <div class="col-md-12">
                                                        <div class="radio-list">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio11" name="paymenttype" class="custom-control-input" value="0">
                                                                <label class="custom-control-label" for="customRadio11">Fixed</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" id="customRadio22" name="paymenttype" class="custom-control-input" value="1">
                                                                <label class="custom-control-label" for="customRadio22">Variable</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
            		                              <div class="form-group">
            		                                <label class="control-label"> Department *</label>
                                                    <span style="float: right;"><i class='fas fa-plus' id="idept"></i></span>
            		                                <select class="select form-control custom-select " style="width: 100%; height:36px;" id="dept" name="dept">
                                                        
            		                                    <?php
            		                                    $mysql = new Mysql();
            		                                    $mysql -> dbConnect();
            		                                    $query = "SELECT id, name FROM `tbl_cost_department` WHERE `isactive` = 0 AND `isdelete`=0 ";
            		                                    $strow =  $mysql -> selectFreeRun($query);
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
            		                              <div class="form-group">
            		                                <label class="control-label"> Location *</label>
                                                     <span style="float: right;"><i class='fas fa-plus' id="iloc"></i></span>
            		                                <select class="select form-control custom-select" style="width: 100%; height:36px;" id="loc" name="loc">
                                                        
            		                                </select> 
            		                              </div>
            		                            </div>
            		                            <div class="col-md-3">
            		                              <div class="form-group">
            		                                <label class="control-label"> Service * </label>
                                                    <span style="float: right;"><i class='fas fa-plus' id="iservice"></i></span>
            		                                <select class="select form-control custom-select" style="width: 100%; height:36px;"  id="service" name="service">
                                                        
            		                                </select> 
            		                              </div>
            		                            </div>
            		                            <!--<div class="col-md-4">-->
            		                            <!--  <div class="form-group">-->
            		                            <!--    <label class="control-label"> Service *<span style="cursor:pointer;"><i class='fas fa-plus' id="iservice" style="padding-left: 300px;" ></i></span></label>-->
            		                            <!--    <select class="select2 form-control custom-select select2-hidden-accessible" style="width: 100%; height:36px;" data-select2-id="1" tabindex="-1" aria-hidden="true" id="service" name="service">-->
                                          <!--              <option value="">Select Service</option>-->
            		                            <!--    </select> -->
            		                            <!--  </div>-->
            		                            <!--</div>-->
                                                    
                                                
                                                
                                                <div class="form-group col-md-4" id="var-salary" style="display:none;">
                                                    <div class="col-md-12">
                                                        <label class="col-sm-12">Amount</label>
                                                        <input type="text" name="amount" class="form-control" placeholder="" id="amount">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="insert" class="btn btn-info" id="submit">Save</button>
                                        </div>
                                  </form>
                              </div>
                            </div>
                        </div>
                        
                    </div>        
                </main>
                
                <div id="adddept" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header" style="background-color: rgb(255 236 230);">
                              <h4 class="modal-title">Add Department</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          </div>
                          <div class="modal-body">
                              <form method="post" id="adddeptform" name="adddeptform" action="">
                                 <div class="form-group">
                                      <label class="control-label">Department *</label>
                                      <input type="text" name="adept" id="adept" class="form-control" placeholder="" required="required">
                                 </div>
                              </form>
                          </div>
                          <div class="modal-footer">
                              <button type="button" id="adddeptdata" class="btn btn-success waves-effect waves-light">Save changes</button>
                              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
                </div>
                <div id="addlocation" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header" style="background-color: rgb(255 236 230);">
                              <h4 class="modal-title">Add Location</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          </div>
                          <div class="modal-body">
                              <form method="post" id="addlocationform" name="addlocationform" action="">
                                  <div class="form-group">
                                      <label class="control-label">Department *</label>
                                      <select class="select form-control custom-select" style="width: 100%; height:36px;" id="bdept" name="bdept">
                                            <option value="">Select Department</option>
		                                    <?php
		                                    $mysql = new Mysql();
		                                    $mysql -> dbConnect();
		                                    $query = "SELECT id, name FROM `tbl_cost_department` WHERE `isactive` = 0 AND `isdelete`=0 ";
		                                    $strow =  $mysql -> selectFreeRun($query);
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
                                 <div class="form-group">
                                      <label class="control-label">Location *</label>
                                      <input type="text" name="bloc" id="bloc" class="form-control" placeholder="" required="required">
                                 </div>
                              </form>
                          </div>
                          <div class="modal-footer">
                              <button type="button" id="addlocdata" class="btn btn-success waves-effect waves-light">Save changes</button>
                              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
                </div>
                <div id="addservice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header" style="background-color: rgb(255 236 230);">
                              <h4 class="modal-title">Add Department</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                          </div>
                          <div class="modal-body">
                              <form method="post" id="addserviceform" name="addserviceform" action="">
                                 <div class="form-group">
                                      <label class="control-label">Department *</label>
                                      <select class="select form-control custom-select" style="width: 100%; height:36px;"  id="cdept" name="cdept">
                                            <option value="">Select Department</option>
    	                                    <?php
    	                                    $mysql = new Mysql();
    	                                    $mysql -> dbConnect();
    	                                    $query = "SELECT id, name FROM `tbl_cost_department` WHERE `isactive` = 0 AND `isdelete`=0 ";
    	                                    $strow =  $mysql -> selectFreeRun($query);
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
                                 <div class="form-group">
                                      <label class="control-label">Location *</label>
                                      <select class="select form-control custom-select" style="width: 100%; height:36px;"  id="cloc" name="cloc">
                                            <option value="">Select Location</option>
    	                                    <?php
    	                                    $mysql = new Mysql();
    	                                    $mysql -> dbConnect();
    	                                    $query = "SELECT id, name FROM `tbl_cost_location` WHERE `isactive` = 0 AND `isdelete`=0 ";
    	                                    $strow =  $mysql -> selectFreeRun($query);
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
                                 <div class="form-group">
                                      <label class="control-label">Service *</label>
                                      <input type="text" name="cser" id="cser" class="form-control" placeholder="" required="required">
                                 </div>
                              </form>
                          </div>
                          <div class="modal-footer">
                              <button type="button" id="addservicedata" class="btn btn-success waves-effect waves-light">Save changes</button>
                              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                          </div>
                      </div>
                  </div>
                </div>
                </div>
            </div>

        
        </div>
        <?php
        include('footer.php');
        ?>
        <?php
        include('footerScript.php');
        ?>
    </body>
    <script src="https://cdn.rawgit.com/ashl1/datatables-rowsgroup/fbd569b8768155c7a9a62568e66a64115887d7d0/dataTables.rowsGroup.js"></script>

<script type="text/javascript">

    $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();
        locdata();
        servicedata();
        
        $("#customRadio11").on("click", function() {
            $("#var-salary").show();
        });
        $("#customRadio22").on("click", function() {
            $("#var-salary").hide();
        });
        $('#adddeptdata').click(function()
        {
          var dept = $('#adept').val();
          event.preventDefault();
          if(dept)
          {
             $.ajax({
                    type: "POST",
                    url: "InsertData.php",
                    data: {action : 'Adddepartment', dept :dept},
                    dataType: 'json',
                    success: function(data) {
                      if(data.status==1)
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#adddeptform')[0].reset();
                            $('#StatusForm')[0].reset();
                            $('#dept').append(`<option value=`+data.deptid+`>`
                                       +data.deptname+
                                  `</option>`);
                      }
                      else
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                      }
                      $('#adddept').modal('hide');
                    }
                });
          }
          else
          {
               myAlert("Error @#@ Please Enter Your Department.@#@danger");
          }
                
        });
        $('#addlocdata').click(function()
        {
          var dept = $('#bdept').val();
          var loc = $('#bloc').val();
          event.preventDefault();
          if(dept && loc)
          {
             $.ajax({
                    type: "POST",
                    url: "InsertData.php",
                    data: {action : 'Addlocation', dept :dept, loc :loc},
                    dataType: 'json',
                    success: function(data) {
                      if(data.status==1)
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#addlocationform')[0].reset();
                            $('#StatusForm')[0].reset();
                      }
                      else
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                      }
                      $('#addlocation').modal('hide');
                    }
                });
          }
          else
          {
               myAlert("Error @#@ Please Enter Your Location.@#@danger");
          }
                
        });
        $('#addservicedata').click(function()
        {
          var dept = $('#cdept').val();
          var loc = $('#cloc').val();
          var ser = $('#cser').val();
          event.preventDefault();
          if(dept && loc && ser)
          {
             $.ajax({
                    type: "POST",
                    url: "InsertData.php",
                    data: {action : 'Addservice', dept :dept, loc :loc, ser :ser},
                    dataType: 'json',
                    success: function(data) {
                      if(data.status==1)
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#addserviceform')[0].reset();
                            $('#StatusForm')[0].reset();
                      }
                      else
                      {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                      }
                      $('#addservice').modal('hide');
                    }
                });
          }
          else
          {
               myAlert("Error @#@ Please Enter Your Service.@#@danger");
          }
                
        });
        
        $('#dept').on('change', function(){
            locdata();
        });
        $('#cdept').on('change', function(){
            var deptid = $(this).val();
            if(deptid){
                $.ajax({
                    type:'POST',
                    url:'loaddata.php',
                    data:'deptid='+deptid+"&action=locationdata",
                    success: function(response){
                        $('#cloc').html(response.option);
                    }
                }); 
            }else{
                $('#cloc').html('<option value="">Select Department first</option>');
            }
        });
        $('#loc').on('change', function(){
            servicedata();
        });

        $('#idept').click(function(){
           $('#adddept').modal('show');
           event.preventDefault();
        });
        $('#iloc').click(function(){
           $('#addlocation').modal('show');
           event.preventDefault();
        });
        $('#iservice').click(function(){
           $('#addservice').modal('show');
           event.preventDefault();
        });
    
        $('#myTable1').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,
            //'order': [[0, 'asc']],
            'rowsGroup': [0,1],
            
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadcostcenterdata',
                    'userid': <?= $userid?>,
                    'type': 0
                }
            },
            'columns': [
                { data: 'Department' },
                { data: 'Location' },
                { data: 'Service' },
                { data: 'Action' },
            ]
        });
        $('#myTable2').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'pageLength': 10,
            'rowsGroup': [0,1],
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadcostcenterdata',
                    'userid': <?= $userid?>,
                    'type': 1
                }
            },
            'columns': [
                { data: 'Department' },
                { data: 'Location' },
                { data: 'Service' },
                { data: 'Action' },
            ]
        });
        
        $("#StatusForm").validate({
            rules: {
                type: 'required',
                dept: 'required',
                loc: 'required',
                service: 'required',
                paymenttype: 'required',
                // amount: 'required',   
            },

            messages: {
                type: "Please select type",
                dept: "Please select Department",
                loc: "Please select Location",
                service: "Please select Service",
                paymenttype: "Please select Payment Type",
                // amount: "Please enter amount",   
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#StatusForm").serialize()+"&action=Costcenterform",    
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#StatusForm')[0].reset(); 
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
              
            }
        });
    });
    
    function locdata() {
        var deptid = $("#dept").val();
        if(deptid){
            $.ajax({
                type:'POST',
                url:'loaddata.php',
                data:'deptid='+deptid+"&action=locationdata",
                success: function(response){
                    $('#loc').html(response.option);
                    servicedata();
                }
            }); 
        }else{
            $('#loc').html('<option value="">Select Department first</option>');
            $('#service').html('<option value="">Select Location first</option>'); 
        }
    }
    
    function servicedata() {
        var locid = $("#loc").val();
        var deptid = $("#dept").val();
        
        if(locid && deptid){
            $.ajax({
                type:'POST',
                url:'loaddata.php',
                data:'locid='+locid+"&action=servicedata"+"&deptid="+deptid,
                success: function(response){
                    $('#service').html(response.option);
                }
            }); 
        }else{
            $('#service').html('<option value="">Select Location first</option>');
        }
    }
    
    function edit(id)
    {
        $('#id').val(id);
        ShowHideDiv('view');
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'Costcenteredit', id: id},
            dataType: 'json',
            success: function(data) {
                $result_data = data.statusdata;
                $("input[name='type'][value='"+$result_data['type']+"']").prop('checked', true);
                
                $("select[name='dept']").find("option[value='"+$result_data['dept_id']+"']").attr("selected",true);
                $('#dept').val($result_data['dept_id']).change();
                
                var deptid = $("#dept").val();
                if(deptid){
                    $.ajax({
                        type:'POST',
                        url:'loaddata.php',
                        data:'deptid='+deptid+"&action=locationdata",
                        success: function(response){
                            $('#loc').html(response.option);
                            $("select[name='loc']").find("option[value='"+$result_data['loc_id']+"']").attr("selected",true);
                            $('#loc').val($result_data['loc_id']).change();
                            var locid = $("#loc").val();
                            var deptid = $("#dept").val();
                            
                            if(locid && deptid){
                                $.ajax({
                                    type:'POST',
                                    url:'loaddata.php',
                                    data:'locid='+locid+"&action=servicedata"+"&deptid="+deptid,
                                    success: function(response){
                                        $('#service').html(response.option);
                                        $("select[name='service']").find("option[value='"+$result_data['ser_id']+"']").attr("selected",true);
                                        $('#service').val($result_data['ser_id']).change();
                                    }
                                }); 
                            }
                        }
                    }); 
                }
                
                $("input[name='paymenttype'][value='"+$result_data['payment_type']+"']").prop('checked', true);
                if ($result_data['payment_type'] == 0) {
                    $("#var-salary").show();
                    $('#amount').val($result_data['amount']);
                }
                
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

            data: {action : 'Costcenterdata', id: id},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                    var table1 = $('#myTable1').DataTable();
                    table1.ajax.reload();
                    var table2 = $('#myTable2').DataTable();
                    table2.ajax.reload();
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
        $('#StatusForm')[0].reset(); 

        if(divValue == 'view')
        {
            
            $("#submit").attr('name', 'insert');

            $("#submit").text('Submit');

            $("#AddFormDiv,#AddDiv").show();

            $("#tabsection,#ViewDiv").hide();     

        }

        if(divValue == 'add')
        {
            var table1 = $('#myTable1').DataTable();
            table1.ajax.reload();
            var table2 = $('#myTable2').DataTable();
            table2.ajax.reload();
            $("#AddFormDiv,#AddDiv").hide();

            $("#tabsection,#ViewDiv").show();     

        }
    }
</script>

</html>