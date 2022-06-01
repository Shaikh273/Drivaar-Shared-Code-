
<?php
$page_title="Workforce";
include 'DB/config.php';
    $page_id=34;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid=$_SESSION['userid'];
        if($_SESSION['userid']==1)
        {
           $uid='%'; 
           $qry = '';
        }
        else
        {
           $uid = $_SESSION['userid']; 
           $qry = ' AND u.`depot` IN (w.`depot_id`)';
        }
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT 
                COUNT(DISTINCT IF(u.`isactive` = 0,u.`id`,NULL)) AS active,
                COUNT(DISTINCT IF(u.`isactive` = 1,u.`id`,NULL)) AS deactive 
                FROM `tbl_user` u
                INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('".$uid."') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                WHERE u.`isdelete`=0  AND u.`id` NOT IN (1)".$qry;
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
                    <div class="header">Workforce</div>
                    <div class="col-md-8"> </div>
                    <div class="col-md-1"> 
                    <div class="d-flex text-center align-items-right">
                            <div class="col  border-right">
                                <div class="text-grey-dark">Active</div>
                                <h5 class="m-0" style="font-weight: 700;">
                                    <span class="text-green-600"><?php echo $result['active'];?></span> 
                                </h5>
                            </div>
                            <div class="col  border-right">
                                <div class="text-grey-dark">Inactive</div>
                                <h5 class="m-0" style="font-weight: 700;">
                                    <span class="text-red-600"><?php echo $result['deactive'];?></span>
                                </h5>
                            </div>
                        </div>
                    </div>
                        <div class="col-md-1"> 
                        <?php
                            if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][36]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
                            { ?>
                                <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Workforce</button>
                              <?php
                            }
                            else
                            {
                                header("location: login.php");  
                            }
                        ?>
                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Workforce</button>

                     </div>                   
                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                      <form method="post" id="WorkforceForm" name="WorkforceForm" action="">
                            <input type="hidden" name="id" id="id" value=""> 
                            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">  
                             <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label"> Name *</label>
                                              <input type="text" id="name" name="name" class="form-control" placeholder="">
                                          </div>
                                        </div>
                                        <div class="col-md-12">
                                              <div class="form-group">
                                                  <label class="control-label"> Email *</label>
                                                  <input type="text" id="email" name="email" class="form-control" placeholder="">
                                              </div>
                                        </div>
                                        <div class="col-md-12">
                                              <div class="form-group">
                                                  <label class="control-label"> Phone *</label><br>
                                                  <input type="text" id="phone" name="phone" class="form-control" placeholder="">
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <label class="control-label"> Type *</label>
                                               <?php
                                                $mysql = new Mysql();
                                                $mysql -> dbConnect();
                                                $rolequery = "SELECT * FROM `tbl_role` WHERE `isactive` = 0 and `isdelete` = 0 AND `id` NOT IN (1)";
                                                $rolerow =  $mysql -> selectFreeRun($rolequery);
                                                while($roleresult = mysqli_fetch_array($rolerow))
                                                {
                                                    ?>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="role-<?php echo $roleresult['id']?>" name="roleid" class="custom-control-input" value="<?php echo $roleresult['id']?>">
                                                        <label class="custom-control-label" for="role-<?php echo $roleresult['id']?>"><?php echo $roleresult['role_type']?></label>
                                                    </div>
                                                   <?php
                                                }
                                                $mysql -> dbDisconnect();
                                                ?>
                                            </div>
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
                    <div class="col-md-4">
                       <div class="form-group has-primary">
                        <select class="select form-control custom-select" id="role_id" name="role_id" onchange="loadtable(this.value);">
                            <option value="%">Select Role</option>
                              <?php
                                  $mysql = new Mysql();
                                  $mysql -> dbConnect();
                                  $rolequery = "SELECT * FROM `tbl_role` WHERE `isactive` = 0 and `isdelete` = 0 AND `id` NOT IN (1)";
                                  $rolerow =  $mysql -> selectFreeRun($rolequery);
                                  while($statusresult = mysqli_fetch_array($rolerow))
                                  {
                                    ?>
                                        <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['role_type']?></option>
                                    <?php
                                  }
                                  $mysql -> dbDisconnect();
                              ?>
                        </select>    
                        </div>
                    </div>


                    <div class="col-md-4">
                       <div class="form-group has-primary">
                        <select class="select form-control custom-select" id="manager_id" name="manager_id" onchange="loadtable(this.value);">
                            <option value="%">Filter By Line Manager</option>
                                <?php
                                    $mysql = new Mysql();
                                    $mysql -> dbConnect();
                                    $query = "SELECT * FROM `tbl_user` WHERE `isactive`=0 AND `isdelete`=0 AND id NOT IN (1)";
                                    $row =  $mysql -> selectFreeRun($query);
                                    while($result = mysqli_fetch_array($row))
                                    {
                                      ?>
                                          <option value="<?php echo $result['id']?>"><?php echo $result['name']?></option>
                                      <?php
                                    }
                                    $mysql -> dbDisconnect();
                                ?>
                        </select>    
                        </div>
                    </div>
                </div>
                <br>
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">
                                    <th>Role</th>
                                    <th>Name</th>
                                    <th>Manager</th>
                                    <th>Email</th>
                                    <th>Contact</th>
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
    </main>
</div>
</div>
<div id="addcustomer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Assign User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <form method="post" id="AssignUserForm" name="AssignUserForm" action="">
                 <input type="hidden" name="user_id" id="user_id">
                 <div class="form-group">
                      <label class="control-label">User *</label>
                      <select class="select form-control custom-select" id="assignuser" name="assignuser">
                        <option>--</option>
                      </select>
                 </div>
              </form>
                <br>
                <table id="Ownertbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                  <thead class="default">
                      <tr role="row">
                          <th width="200">Assign User</th>
                          <th>Date</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody id="customerbody">
                  </tbody>
                </table>
          </div>
          <div class="modal-footer">
              <button type="button" onclick="AssignUserDatatbl()" class="btn btn-success waves-effect waves-light">Save changes</button>
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
              "serverSide": true,
              "destroy" : true,
              'pageLength': 10,
              "ajax":
              {
                  "url": "loadtabledata.php",
                  "type": "POST",
                  "data": function(d) 
                  {
                    d.action = 'loadworkforcetabledata';
                    d.role_id = $('#role_id').val();
                    d.manager_id = $('#manager_id').val();
                  }
              },
              'columns': [
                    { data: 'roleid'},
                    { data: 'name'},
                    { data: 'manager'},
                    { data: 'email'},
                    { data: 'contact'},
                    { data: 'date'},
                    { data: 'Status'},
                    { data: 'Action'}
                ]  /* <---- this setting reinitialize the table */
        });

        $("#WorkforceForm").validate({
            rules: {
              name: 'required',
              email: 'required',
              phone: 'required',
              roleid: 'required',
            },
            messages: {
              name: "Please enter your name",
              email: "Please enter your email",
              phone: "Please enter your phone",
              roleid: "Please enter your type",
            },
            submitHandler: function(form){
                event.preventDefault();
                var mobile_no = $('#phone').val();
                var email = $('#email').val();
                var submitaction = $('#submit').attr('name');
                $.ajax({
                    url: 'loaddata.php',
                    type: 'POST',
                    data: {action : 'checkusercontact', phone : mobile_no, email : email, submitaction : submitaction},
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 0) {
                          $.ajax({
                              url: "InsertData.php", 
                              type: "POST", 
                              dataType:"JSON",            
                              data: $("#WorkforceForm").serialize()+"&action=VehicleWorkforceForm&submitaction="+submitaction,     
                              success: function(data) {
                                  if(data.status==1)
                                  {
                                      myAlert(data.title + "@#@" + data.message + "@#@success");
                                      $('#WorkforceForm')[0].reset();
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

    function loadtable()
    {
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }

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

    function loadpage(wid)
    {
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'WorkforceSetSessionData', wid: wid},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                    window.location = '<?php echo $webroot ?>workForceDetails.php';
                }              
            }
        });
    }

    function Isassignbtn(id)
    {
        $('#addcustomer').modal('show');
        $('#user_id').val(id);
        event.preventDefault();
        $.ajax({
            url: 'loaddata.php',
            type: 'POST',
            data: {action : 'assignWorkforceUser', id : id},
            dataType: 'json',
            success: function(response) {
              if (response.status == 1)
              {
                 $('#assignuser').html(response.options);
              }
            }
        });

        showAssignUser(id);
    }

    function AssignUserDatatbl()
    {
        var assignid = $('#assignuser').val();
        var userid = $('#user_id').val();
        $.ajax({
            url: 'loaddata.php',
            type: 'POST',
            data: {action : 'InsertUserAssignData', userid : userid,assignid:assignid},
            dataType: 'json',
            success: function(data) {
              myAlert(data.title + "@#@" + data.message + "@#@success");
              showAssignUser(userid);
            }
        });
    }

    function showAssignUser(id)
    {
        $.ajax({
            url: 'loaddata.php',
            type: 'POST',
            data: {action : 'UserAssignData',id:id},
            dataType: 'json',
            success: function(data) {
              $('#customerbody').html(data.tbldata);
            }
        }); 
    }

    function deleteuserrow(id,userid)
    {
        $.ajax({
            url: 'loaddata.php',
            type: 'POST',
            data: {action : 'DeleteUserAssignData', id:id},
            dataType: 'json',
            success: function(data) {
                if(data.status==1)
                {
                    myAlert("Delete@#@Data has been deleted successfully.@#@success");
                    showAssignUser(userid);
                }
                else
                {
                    myAlert("Delete@#@Data can not been deleted successfully.@#@error");
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
            data: {action : 'WorkforcwUpdateData', id: id},
            dataType: 'json',
            success: function(data) {
                $result_data = data.statusdata;
                $("input[name='roleid'][value='"+$result_data['roleid']+"']").prop('checked', true);
                $('#name').val($result_data['name']);
                $('#phone').val($result_data['phone']);
                $('#email').val($result_data['email']);
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
            data: {action : 'WorkforceDeleteData', id: id},
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
        $('#WorkforceForm')[0].reset(); 
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