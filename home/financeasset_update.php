<?php
$page_title="Vehicle Contact";
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
                    <div class="header">Contacts</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Contacts</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Contacts</button>
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
                                    <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
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
                <div class="row">
                    <div class="alert alert-danger alert-rounded col-sm-12"><i class="fas fa-exclamation-circle"></i> <b> Contacts can be assigned to resources like vehicles, e.g. a person outside of your organization.</b></div>
                 </div>
                    <div class="table-responsive m-t-40" style="margin-top: 0px;">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                            <thead class="default">
                                <tr role="row">

                                        <th>Type</th>

                                        <th>Name</th>

                                        <th>Notes</th>

                                        <th>Number</th>

                                        <th>Price</th>
                                        
                                        <th>Assign To </th>

                                        <th>Action</th>
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