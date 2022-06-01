<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=150;
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
          <?php include('pages_setting.php'); ?>   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Manage Group</div>

                     <div> 

                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Group</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Group</button>

                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                      <form method="post" id="GroupForm" name="GroupForm" action="">
                          <input type="hidden" name="id" id="id" value="">  
                             <div class="form-body">

                                <div class="row p-t-20">

                                    <div class="col-md-4">
                                          <div class="form-group">
                                              <label class="control-label">Group Name *</label>
                                              <input type="text" id="name" name="name" class="form-control" placeholder="">
                                          </div>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="exampleInputuname">Rent per day</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="rent"><i class="fas fa-pound-sign"></i></span>
                                                </div>
                                                <input type="text" id="rentper_day" name="rentper_day" class="form-control" placeholder="" value=0.00>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-4">
                                          <div class="form-group">
                                            <label for="exampleInputuname">Insurance per day</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="insurance"><i class="fas fa-pound-sign"></i></span>
                                                </div>
                                                <input type="text" id="insuranceper_day" name="insuranceper_day" class="form-control" placeholder="" value=0.00>
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

                                    <th>Rent</th>

                                    <th>Insurance</th>

                                    <th>Total</th>

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
                            'action': 'loadvehiclegrouptabledata'
                        }
                    },
                    'columns': [
                        { data: 'name' },
                        { data: 'rentper_day' },
                        { data: 'insuranceper_day' },
                        { data: 'total' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        $("#GroupForm").validate({

            rules: {

                name: 'required',

                rentper_day: 'required',

                insuranceper_day: 'required',

            },

            messages: {

                name: "Please enter your group name",

                rentper_day: "Please enter your rent per day",

                insuranceper_day: "Please enter insurance per day",
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#GroupForm").serialize()+"&action=VehicleGroupform",    
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#GroupForm')[0].reset();
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

            data: {action : 'VehicleGroupUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.groupdata;

                $('#name').val($result_data['name']);

                $('#rentper_day').val($result_data['rentper_day']);

                $('#insuranceper_day').val($result_data['insuranceper_day']);

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

            data: {action : 'VehicleGroupDeleteData', id: id},

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
        $('#GroupForm')[0].reset(); 
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