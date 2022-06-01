<?php
$page_title = "Add asset";
?>

<!DOCTYPE html>

<html lang="en">

    <head>

        <meta name="viewport" content="width=1024">

        <title><?php echo $page_title; ?></title>

        <?php include('head.php'); ?>

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



                    <main class=" container-fluid  animated mt-4">

                        <div class="container">

                            <div class="row justify-content-center">

                                <div class="col-md-6">

                                    <div class="card">    

                                        <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="header">Add new asset</div>
                                            </div>

                                        </div>

                                        <div class="card-body">
                                                <input type="hidden" name="asset_id" id="asset_id" value="<?php echo $_GET['id'];?>">
                                                <div class="row">
    
                                                     <div class="col-md-12">
    
                                                    <div class="form-group has-primary">
                                                            <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">Type: </label>
                                                            <input type="text" class="form-control" data-aire-component="input" name="type_name" data-aire-for="type_name" id="type_name" required>
                                                    </div>
    
                                                     <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Name:
                                                        </label>
                                                        <input type="text" class="form-control" data-aire-component="input" name="asset_name" data-aire-for="name" id="name" required>
                                                    </div>
    
                                                     <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Number:
                                                        </label>
                                                        <input type="number" class="form-control" data-aire-component="input" name="asset_number" id="number" data-aire-for="name" required>
                                                    </div>
    
    
                                                    <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                                <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Price:
                                                                </label>
                                                                <input type="text" class="form-control" data-aire-component="input" name="asset_price" id="price" data-aire-for="name" required>
                                                                </div>
                                                             </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                                <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Assign To : </label><br>
                                                                <select class="form-select form-control form-select-lg mb-3 p-1 col-md-12" id="assign_to">
                                                                    <?php
                                                                    
                                                                        include 'DB/config.php';
                                                                        $mysql = new Mysql();
                                                                        $mysql -> dbConnect();
                                                                        
                                                                        $sql = $mysql->selectAll("tbl_user");
                                                                        error_log($sql,0);
                                                                        while($fetch = mysqli_fetch_array($sql)){
                                                                    ?>
                                                                      <option value="<?php echo $fetch['id'];?>"><?php echo $fetch['name'];?></option>
                                                                      <?php
                                                                        }
                                                                       $mysql -> dbDisConnect();
                                                                      ?>
                                                                </select>

                                                                <!--<input type="text" class="form-control" data-aire-component="input" name="assign_to" id="assign_to" data-aire-for="name" required>-->
                                                                </div>
                                                            </div>
                                                    </div><hr>
    
                                            
                                                    <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                        <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">  Description
                                                        </label>
                                                        <textarea type="text" rows="2" class="form-control" data-aire-component="input" name="asset_description" id="description" data-aire-for="name"></textarea>
                                                    </div>
                                                     </div>
                                                </div>
    
                                                <div class="card-footer">
    
                                                <button class="btn btn-primary " data-aire-component="button" type="submit" id="update_asset" name="addmetric">
    
                                                    Update Asset
    
                                                </button>
    
                                                <a href="metrix_register.php" class="btn">Cancel</a>
    
                                            </div>
    
                                        </div>        

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
<script>
$(document).ready(function(){
var id = $('#asset_id').val();
$.ajax({

        type: "POST",

        url: "loaddata.php",

        data: {action : 'financeAssetsUpdateData', id: id},

        dataType: 'json',

        success: function(data) {

            $result_data = data.statusdata;
            $('#type_name').val($result_data['type_name']);
            $('#name').val($result_data['name']);
            $('#price').val($result_data['price']);
            $('#description').val($result_data['description']);
            $('#number').val($result_data['number']);
            $('#assign_to').val($result_data['assign_to']);

            // $("#submit").attr('name', 'update');

            // $("#submit").text('Update');

        }

    });
});  

$('#update_asset').click(function()
{
    var id = $('#asset_id').val();
    var type_name = $('#type_name').val();
    var name = $('#name').val();
    var price = $('#price').val();
    var description = $('#description').val();
    var number = $('#number').val();
    var assign_to = $('#assign_to').val();

    $.ajax({

        type: "POST",

        url: "loaddata.php",

        data: {action : 'AssetsUpdateData',
        id: id,
        type_name: type_name,
        name:name,
        price:price,
        description:description,
        number:number,
        assign_to:assign_to
           
        },

        dataType: 'json',

        success: function(data) {
            if(data.status==1)
            {
                myAlert(data.title + "@#@" + data.message + "@#@success");
                window.location.href="assets.php";
              
            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            }

        }

    });
});
    


</script>  


</body>



</html>