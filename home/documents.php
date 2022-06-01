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
$page_title="Documents";
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
                                                    <div class="header">Documents</div>
                                                </div>
                                            </div>
                                              <div class="card-body">
                                                        <div class="row">
                                                             <div class="col-md-12">
                                                                <form action="" method="post" id="DocumentForm" enctype="multipart/form-data">
                                                                    <div class="form-group row required">
                                                                         <input type="hidden" id="id" name="id" value="" class="form-control">
                                                                        <div class="col-md-4">
                                                                            <label class="d-block mb-0">Name</label>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="text" name="name" id="name" value="" class="form-control">
                                                                        </div>
                                                                    </div>

                                                                   

                                                                    <div class="form-group row">
                                                                        <div class="col-md-4">
                                                                            <label class="d-block mb-0">Logo</label>
                                                                            <small class="text-muted">
                                                                              Upload Only Image
                                                                            </small>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <input type="file" id="file1" name="file1" value="" class="form-control" placeholder=""  onchange="loadFile1(event)" >
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                              <iframe src="" id="imgsrc1" name="imgsrc1" style="height: 100%;" class="hidden"></iframe>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group row" style="display: none;">
                                                                        <div class="col-md-4">
                                                                            <label class="d-block mb-0">Document</label>
                                                                            <small class="text-muted">
                                                                              Upload document .PDF
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
                                                                        <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
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
            </div>
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
        
    </body>
<script type="text/javascript">
    var webroot="<?php echo $webroot?>";

    $("#file").change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5])))
        {
            alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
            $('#imgsrc').addClass('hidden');
            $('#imgsrc').src('');
            $("#file").val('');
            return false;
        }
    });

    $("#file1").change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['image/JPEG', 'image/PNG', 'image/JPG', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5])))
        {
            alert('Sorry, JPG, JPEG, & PNG files are allowed to upload.');
            $('#imgsrc1').addClass('hidden');
            $('#imgsrc1').src('');
            $("#file1").val('');
            return false;
        }
    });


    $(document).ready(function(){
        var webroot="<?php echo $webroot?>";
        ajaxreload(); 
        
        $("#DocumentForm").validate({
            rules: {
                name: 'required',
                file: 'required',
            },
            messages: {
                name: "Please enter your name",
                file: "Please select your file",
            },
        });

        $("#DocumentForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'org_documents.php',
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
                type: "POST",
                url: "loaddata.php",
                data: {action : 'OrgDocumentUpdateData'},
                dataType: 'json',
                success: function(data) {
                    $result_data = data.statusdata;
                    $('#name').val($result_data['name']);
                    $('#imgsrc').removeClass('hidden');
                    $('#imgsrc').attr('src',webroot+'uploads/organizationdocuments/'+$result_data['file']);
                    $('#imgsrc1').removeClass('hidden');
                    $('#imgsrc1').attr('src',webroot+'uploads/organizationdocuments/'+$result_data['file1']);
                    $('#id').val($result_data['id']);
                    $('#file').val($result_data['file']);
                    $('#file1').val($result_data['file1']);
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');
                }
            });

        }

    var loadFile = function(event) {
        $('#imgsrc').removeClass('hidden');
        var image = document.getElementById('imgsrc');
        image.src = URL.createObjectURL(event.target.files[0]);
    };

    var loadFile1 = function(event) {
        $('#imgsrc1').removeClass('hidden');
        var image1 = document.getElementById('imgsrc1');
        image1.src = URL.createObjectURL(event.target.files[0]);
    };
    
</script>
</html>