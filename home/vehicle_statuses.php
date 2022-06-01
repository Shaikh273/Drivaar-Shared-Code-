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
$page_title = "Vehicle Statuses";
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
                                <div class="header">Vehicle Statuses</div>
            
                                 <div> 
            
                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Status</button>
            
                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Status</button>
            
                                 </div>                   
            
                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                              <div class="col-md-12">
                                  <form method="post" id="StatusForm" name="StatusForm" action="">
                                      <input type="hidden" name="id" id="id" value="">  
                                        <div class="form-body">
                                            <div class="row p-t-20">
                                                <div class="col-md-4">
                                                      <div class="form-group">
                                                          <label class="control-label">Status Name *</label>
                                                          <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                      </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Color *</label>
                                                        <input type="color" id="colorcode" name="colorcode" class="form-control" placeholder="">
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
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
        
                                            <th>Status</th>
                                            <th data-orderable="false">Vehicles</th>
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

        
        </div>
        <?php
        include('footer.php');
        ?>
        <?php
        include('footerScript.php');
        ?>
    </body>

<script type="text/javascript">
    $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'pageLength': 10,
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadstatusdata',
                            'userid': <?= $userid?>
                        }
                    },
                    'columns': [
                        { data: 'Status' },
                        { data: 'Vehicles' }
                    ]
        });

        $("#StatusForm").validate({

            rules: {
                name: 'required',
                colorcode: 'required',
            },

            messages: {
                name: "Please enter your status name",
                colorcode: "Please enter your status color", 
            },

             submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#StatusForm").serialize()+"&action=vehiclestatusform",
                    cache: false,             
                    processData: false,      
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

    function ShowHideDiv(divValue)
    {
        $('#StatusForm')[0].reset(); 

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

</html>