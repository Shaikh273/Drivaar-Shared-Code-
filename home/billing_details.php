
<?php
$page_title="Billing details";
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
                                        <div class="card-header " style="background-color: rgb(255 236 230);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="header">Billing Details</div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <span class="label label-secondary">Bellow details are used when you receive invoices from subcontractors.</span>
                                                    <form action="" method="post" name="form" id="form">
                                                        <!--<input type="hidden" name="_token" value="">                -->
                                                        <!--<input type="hidden" name="_method" value="put">-->
                                                        <div class="form-group row required">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">VAT *</label>
                                                            </div>
                                                                
                                                            <div class="col-md-8">
                                                                <input type="number" name="vat_per" id="vat_per" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row required">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Street *</label>
                                                                <small class="text-muted">
                                                                   Enter your company's street name
                                                                </small>
                                                            </div>
                                                                
                                                            <div class="col-md-8">
                                                                <input type="text" name="street" id="street" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Postcode *</label>
                                                                <small class="text-muted">
                                                                    Enter your company's postcode
                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="postcode" id="postcode" value="" class="form-control">
                                                            </div>
                                                        </div>


                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">City *</label>
                                                                <small class="text-muted">
                                                                    Your company's city

                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="city" id="city" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">County *</label>
                                                                <small class="text-muted">
                                                                   Your company's county

                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="country" id="country" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label class="d-block mb-0">Country *</label>
                                                                <small class="text-muted">
                                                                   Your company's country

                                                                </small>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" name="country1" id="country1" value="" class="form-control">
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <br>
                                                        <button class="btn btn-info " data-aire-component="button" type="button" name="update" id="update">Update
                                                        </button>
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
            </div>
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
        
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
                data:$('#form').serialize()+"&action=updatebillingdetails",
                success:function(data){
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        $('#form')[0].reset();
                        ajaxreload();
                        // if(data.name == 'Update')
                        // {
                        //     var table = $('#myTable').DataTable();
                        //     table.ajax.reload();
                        //     $("#AddFormDiv,#AddDiv").hide();
                        //     $("#ViewFormDiv,#ViewDiv").show();
                        // }
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
                    $("#street").val(response['street'].toUpperCase());
                    $("#postcode").val(response['postcode'].toUpperCase());
                    $("#city").val(response['city'].toUpperCase());
                    $("#country").val(response['country'].toUpperCase());
                    $("#country1").val(response['country1'].toUpperCase());
                    $("#vat_per").val(response['vat_per']);
                    
                }
            });
        }
    </script>

</html>


