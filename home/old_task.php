
<?php
   $page_title="Task";
   include 'DB/config.php';
   $page_id=33;
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
                    <div class="header">Tasks</div>

                     <div> 

                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Tasks</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Tasks</button>

                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                      <form method="post" id="TaskForm" name="TaskForm" action="">
                          <input type="hidden" name="id" id="id" value="">
                          <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">  
                             <div class="form-body">

                                <div class="row">
                                    <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="control-label"> Name *</label>
                                              <textarea type="text" rows="2" id="name" name="name" class="form-control" placeholder=""></textarea> 
                                        </div>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="control-label"> Assignee *</label>
                                        <select class="select form-control custom-select" id="assignee" name="assignee">
                                            <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $statusquery = "SELECT * FROM `tbl_user` WHERE  `roleid` IN (1,2,4,7,10) AND `isdelete`=0 AND `isactive`=0 AND `userid`=".$userid;
                                            $strow =  $mysql -> selectFreeRun($statusquery);
                                            while($statusresult = mysqli_fetch_array($strow))
                                            {
                                              ?>
                                                  <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name'].' ('.$statusresult['role_type'].')'?></option>
                                              <?php
                                            }
                                            $mysql -> dbDisconnect();
                                            ?>
                                        </select> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <label class="control-label">Due Date *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control mydatepicker" id="duedate" name="duedate" placeholder="mm/dd/yyyy">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="icon-calender"></i></span>
                                                </div>
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
                                    <th>Name</th>
                                    <th>Assignee</th>
                                    <th>Due Date</th>
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
                            'action': 'loadworkforcetasktabledata'
                        }
                    },
                    'columns': [
                        { data: 'name' },
                        { data: 'assignee_type' },
                        { data: 'duedate' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        $("#TaskForm").validate({

            rules: {

                name: 'required',
                assignee: 'required',
                duedate: 'required',

            },

            messages: {
                name: "Please enter your name",
                assignee: "Please select your assignee",
                duedate: "Please select your duedate",
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#TaskForm").serialize()+"&action=WorkforceTaskForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#TaskForm')[0].reset();
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

    function edit(id)
    {

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'WorkforceTaskUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#name').val($result_data['name']);
                $('#assignee').val($result_data['assignee']);
                $('#duedate').val($result_data['duedate']);

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

            data: {action : 'WorkforceTaskDeleteData', id: id},

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
        $('#TaskForm')[0].reset(); 
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