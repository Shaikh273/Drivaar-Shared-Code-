
<?php
$page_title="Create Users";
include 'DB/config.php';
if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}

$userid = $_SESSION['userid']; 
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
      <div class="row">
        <div class="card col-md-12">
            <div class="card-body">
                <?php include('setting.php'); ?>
                <div class="col-md-12">
                     <div class="card">    
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">User Details</div>

                                 <div> 

                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add User</button>

                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View User</button>

                                 </div>                   

                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                              <div class="col-md-12">
                                  <form method="post" id="vendorForm" name="vendorForm" action="">
                                      <input type="hidden" name="id" id="id" value="">
                                      <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">  
                                         <div class="form-body">

                                            <div class="row p-t-20">

                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Role *</label>

                                                          <select class="form-control form-control-md" id="roleid" name="roleid" data-placeholder="Choose a Role" tabindex="1">
                                                            <?php

                                                            $mysql = new Mysql();

                                                            $mysql -> dbConnect();

                                                            $rolequery = "SELECT * FROM `tbl_role` WHERE `isactive` = 0 and `isdelete` = 0 AND `id` NOT IN (1)";
                                                            $rolerow =  $mysql -> selectFreeRun($rolequery);

                                                            while($roleresult = mysqli_fetch_array($rolerow))
                                                            {
                                                                ?>
                                                                 <option value="<?php echo $roleresult['id']?>"><?php echo $roleresult['role_type']?></option>
                                                               <?php
                                                            }
                                                            $mysql -> dbDisconnect();
                                                            ?>

                                                          </select>
                                                      </div>
                                                </div>

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
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">

                                                     <div class="form-group">

                                                        <label class="control-label">Contact *</label>

                                                        <input type="number" id="contact" name="contact" class="form-control" placeholder="">

                                                    </div>


                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group">

                                                        <label class="control-label">Address *</label>

                                                        <textarea type="text" rows="2" id="address" name="address" class="form-control" placeholder=""></textarea>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">

                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label class="control-label">Password *</label>

                                                            <input type="password" name="password" id="password" value="" class="form-control">

                                                        </div>

                                                    </div>

                                                    <div class="col-md-6">

                                                        <div class="form-group">

                                                            <label class="control-label">Confirm Password *</label>

                                                            <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control">

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
                        <div class="card-body" id="ViewFormDiv">
                                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                    <table id="UserTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                        <thead class="default">
                                            <tr role="row">
                                               <th>Role</th>

                                                <th>Name</th>

                                                <th>Email</th>

                                                <th>Conatct</th>
                                                
                                                <th>Address</th>
                                                
                                                <th data-orderable="false">Date</th>

                                                <th data-orderable="false">Status</th>

                                                <th data-orderable="false"></th>
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

        $('#UserTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': 10,                    
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadusertabledata'
                        }
                    },
                    'columns': [
                        { data: 'roleid' },
                        { data: 'name' },
                        { data: 'email' },
                        { data: 'contact' },
                        { data: 'address' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        function resendurl(wid)
        {
          $.ajax({
                url: "loaddata.php", 
                type: "POST", 
                dataType:"JSON",            
                data: {action : 'WorkforceURLSend', wid: wid},      
                success: function(data) {
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        }

        $("#vendorForm").validate({
          
            submitHandler: function(form) {
                console.log($("#vendorForm").serialize());
                event.preventDefault();
                var mobile_no = $('#contact').val();
                var email = $('#email').val();
                var submitaction = $('#submit').attr('name');
                var id = '';
                if (submitaction == 'update') {
                    id = $('#id').val();
                }
                $.ajax({
                    url: 'loaddata.php',
                    type: 'POST',
                    data: {action : 'checkusercontact', phone : mobile_no, email : email, submitaction : submitaction, id : id},
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 0) {
                            $.ajax({
                                url: "InsertData.php", 
                                type: "POST", 
                                dataType:"JSON",            
                                data: $("#vendorForm").serialize()+"&action=vendorForm&"+submitaction+"",
                                cache: false,             
                                processData: false,      
                                success: function(data) {
                                    if(data.status==1)
                                    {
                                        myAlert(data.title + "@#@" + data.message + "@#@success");
                                        $('#vendorForm')[0].reset();
                                        if(data.name == 'Update')
                                        {
                                            var table = $('#UserTable').DataTable();
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
                        } 
                        else if(response.status == 1) {
                            myAlert("Error @#@" + response.msg + "@#@danger");
                        }
                        
                    }
                });
               // return false;
            }
        });
    });

    $(function() {

            $('#UserTable').DataTable();

            $(function() {

                var table = $('#example').DataTable({

                    "columnDefs": [{

                        "visible": false,

                        "targets": 2

                    }],

                    "order": [

                        [2, 'asc']

                    ],

                    "displayLength": 25,

                    "drawCallback": function(settings) {

                        var api = this.api();

                        var rows = api.rows({

                            page: 'current'

                        }).nodes();

                        var last = null;

                        api.column(2, {

                            page: 'current'

                        }).data().each(function(group, i) {

                            if (last !== group) {

                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');

                                last = group;

                            }

                        });

                    }

                });

            });
    });

    $('#password').bind('cut copy', function(e) {
      e.preventDefault();
    });

    $('#confirm_password').bind('cut copy', function(e) {
       e.preventDefault();
     });

    function edit(id)
    {

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'UserUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.userdata;

                $('#roleid').val($result_data['roleid']);

                $('#name').val($result_data['name']);

                $('#contact').val($result_data['contact']);

                $('#email').val($result_data['email']);

                $('#address').val($result_data['address']);

                $('#password').val($result_data['password']);

                $('#confirm_password').val($result_data['confirm_password']);

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

            data: {action : 'UserDeleteData', id: id},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                    window.location.reload();
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

            $('#vendorForm')[0].reset(); 

            $("#AddFormDiv,#AddDiv").show();

            $("#ViewFormDiv,#ViewDiv").hide();     

        }

        if(divValue == 'add')

        {

            $("#AddFormDiv,#AddDiv").hide();

            $("#ViewFormDiv,#ViewDiv").show();     

        }
    }
    </script>
</body>

</html>