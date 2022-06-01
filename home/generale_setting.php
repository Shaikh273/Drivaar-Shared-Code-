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
$page_title="Generale Setting";
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
                                                    <div class="header">Account Details</div>
                                                </div>
                                            </div>
<div class="card-body">
    <div class="row">
         <div class="col-md-12">
            <form action="" method="post" id="DocumentForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id" value="<?php echo $userid?>">

                <div class="form-group row required">
                    <div class="col-md-4">
                        <label class="d-block mb-0">Name</label>
                        <small class="text-muted">
                            Your name (used for invoices, etc.)
                        </small>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="d-block mb-0">Phone</label>
                        <small class="text-muted">
                            For receiving text messages
                        </small>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="phone" id="phone"  class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="d-block mb-0">Profile Photo</label>
                        <small class="text-muted">
                          Upload Only Image
                        </small>
                    </div>
                    <div class="col-md-4">
                        <input type="file" id="file" name="file" value="" class="form-control" placeholder=""  onchange="loadFile(event)" >
                    </div>
                    <div class="col-md-4">
                          <iframe src="" id="imgsrc" name="imgsrc" style="height: 100%;" class="hidden"></iframe>
                    </div>
                </div>
                <hr>
                 <div class="card-footer">
                    <!--<button class="btn btn-primary " data-aire-component="button" type="submit" name="update" id="update">Update-->
                    <!--</button>-->
                     <button type="submit" name="update" class="btn btn-primary" id="submit">Update</button>

                    <a href="generale_setting.php" class="btn">Cancel</a>
               
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
    <script type="text/javascript"> 
    var webroot="<?php echo $webroot?>";

    $("#file").change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['image/JPEG', 'image/PNG', 'image/JPG', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5])))
        {
            alert('Sorry, only JPG, JPEG, & PNG files are allowed to upload.');
            $('#imgsrc').addClass('hidden');
            $('#imgsrc').src('');
            $("#file").val('');
            return false;
        }
    });

    // $("#form").validate({
    //     rules: {
    //         name: 'required',
    //         phone: 'required',
    //     },
    //     messages: {
    //         name: "Please enter your make name",
    //         phone:  "Please enter your mobile",
    //     },
    //     submitHandler: function(form) {
    //         event.preventDefault();
    //         $.ajax({
    //             url: "loaddata.php", 
    //             type: "POST", 
    //             dataType:"JSON",            
    //             data: $("#form").serialize()+"&action=updategenerale_settingdetails",
    //             success: function(data) {
    //                 if(data.status==1)
    //                 {
    //                     myAlert(data.title + "@#@" + data.message + "@#@success");
    //                     $('#form')[0].reset();
    //                     ajaxreload();
    //                 }
    //                 else
    //                 {
    //                     myAlert(data.title + "@#@" + data.message + "@#@danger");
    //                 }
    //             }
    //         });
    //        // return false;
    //     }
    // });



     $(document).ready(function(){
        $id = <?php echo $userid?>;
        var webroot="<?php echo $webroot?>";
        ajaxreload(); 
        
        $("#DocumentForm").validate({
            rules: {
                name: 'required',
                phone: 'required',
            },
            messages: {
                name: "Please enter your name",
                phone:  "Please enter your mobile",
            },
        });

        $("#DocumentForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'Userprofile.php',
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                { 
                    if(data.status==1)
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                        ajaxreload();
                        window.location.href = "http://drivaar.com/home/logout.php";
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        });
    });


    function ajaxreload() {
        $.ajax({
            url:"loaddata.php",
            method:"POST",
            dataType:"json",
            data: {action : 'loadgenerale_settingdetails', id: $id},
            success:function(response){  
                $("#name").val(response['name'].toUpperCase());
                $("#phone").val(response['contact']);
                if(response['file']!='')
                {
                	$('#imgsrc').removeClass('hidden');
                	$('#imgsrc').attr('src',webroot+'uploads/Userprofile/'+response['file']);
                	$('#file').val(response['file']);
                }
                
            }
        });
    }

    var loadFile = function(event) {
        $('#imgsrc').removeClass('hidden');
        var image = document.getElementById('imgsrc');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
                
    </script>

    </body>
</html>