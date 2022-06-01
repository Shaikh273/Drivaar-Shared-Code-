
<?php
$page_title="Company";
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
                    <div class="header">Manage Company</div>

                     <div> 

                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Company</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Company</button>

                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <div class="row">
                  <div class="col-md-12">
                      <form method="post" id="ContractorCompanyForm" name="ContractorCompanyForm" action="">
                          <input type="hidden" name="id" id="id" value="">  
                             <div class="form-body">

                                <div class="row p-t-20">

                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Company Name *</label>
                                              <input type="text" id="name" name="name" class="form-control" placeholder="">
                                          </div>
                                    </div>

                                    <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label">Company register number *</label>
                                              <input type="text" id="company_reg" name="company_reg" class="form-control" placeholder="">
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

                                    <th width="30%">Company Name</th>
                                    <th width="30%">Company Register Number</th>
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
                    'pageLength': 10,
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadContractorCompanytabledata'
                        }
                    },
                    'columns': [
                        { data: 'name' },
                        { data: 'company_reg' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        $("#ContractorCompanyForm").validate({

            rules: {

                name: 'required',
                company_reg: 'required',
            },

            messages: {
                name: "Please enter your company name",
                company_reg: "Please enter your company registration",
            },

             submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#ContractorCompanyForm").serialize()+"&action=ContractorCompanyForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#ContractorCompanyForm')[0].reset();
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

            data: {action : 'ContractorCompanyUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;
                $('#name').val($result_data['name']);
                $('#company_reg').val($result_data['company_reg']);
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

            data: {action : 'ContractorCompanyDeleteData', id: id},

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
        $('#ContractorCompanyForm')[0].reset(); 

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