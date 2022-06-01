
<?php
$page_title="Organisation Generale Setting";
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
    <style>
        
    </style>
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
                                        <div class="card-header " style="background-color: rgb(255 236 230);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="header">Organisation Details</div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                 <div class="col-md-12">
                                                    <form action="" method="post" id="form">
                                                        <input type="hidden" name="_token" value="">                
                                                        <input type="hidden" name="_method" value="put">
                                                        <div class="form-group row required">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Name *</label>
                                                                <small class="text-muted">
                                                                   The name of your organisation
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="name" id="name" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Registration Number *</label>
                                                                <small class="text-muted">
                                                                    Your company's registration number
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="registration_no" id="registration_no" value="" class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">VAT Number *</label>
                                                                <small class="text-muted">
                                                                    Your company's VAT number

                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="vat" id="vat" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <div class="card-footer">
                                                            <button class="btn btn-info " data-aire-component="button" type="button" id="update">Update
                                                            </button>
                                                        </div>
                                                    </form>
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
        </div>
        
    </body>
<script>
    $(document).ready(function(){
        ajaxreload(); 
    });

    $("#update").on('click', function(event){
        var formData = $('#form').serialize();
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data:$('#form').serialize()+"&action=updateorgdetails",
            success:function(data){
                if(data.status==1)
                {
                    myAlert(data.title + "@#@" + data.message + "@#@success");
                    $('#form')[0].reset();
                    ajaxreload();
                }
                else
                {
                    myAlert(data.title + "@#@" + data.message + "@#@danger");
                }  
            }
        })   
    }); 

    function ajaxreload() {
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data: {action : 'loadbillingdetails'},
            success:function(response){  
                $("#name").val(response['name'].toUpperCase());
                $("#registration_no").val(response['registration_no']);
                $("#vat").val(response['vat']);
            }
        });
    }
</script>
</html>


