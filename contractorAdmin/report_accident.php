<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '144';
if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
{
    
}
else
{
    if((isset($_SESSION['adt']) && $_SESSION['adt']==0))
    {
       header("location: userlogin.php");
    }
    else
    {
       header("location: login.php");  
    }
}
//include('authentication.php');
include('config.php');
$page_title="Report Accident";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" /> -->
</head>
    <body class="skin-default-dark fixed-layout">
        <?php
        
            include('loader.php');
        ?>
        
        <div id="main-wrapper" id="top">
            <?php
                include('header.php');
            ?>
            
          
            
            <div class="page-wrapper content" id="top" >
                <div class="container-fluid">
                    
                    <div class="row">
                        <div class="col-md-10 container">
                        <form action="" method="POST" class="" id="addReportAccidentForm" name="addReportAccidentForm" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>">
                            <div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                                <div class="card-header">
                                    <h6 class="m-0"><svg class="icon d-inline" fill="currentColor" style="height: 20px; width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg> Report Accident</h6>
                                </div>
                                <div class="card-body">
                                    <div class="card border p-3">
                                        <label class="cursor-pointer">Brief Description of the Accident:</label>
                                        <textarea class="form-control" name="description_accident" id="description_accident" required=""></textarea>
                                    </div>
                                    
                                    <div class="card border">
                                        <div class="card-header">        
                                        <h5 class="">Other Participant Details</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group" data-aire-component="group" data-aire-for="name">
                                                            <label class=" cursor-pointer">Other Person Name:</label>
                                                            <input type="text" class="form-control" name="name"  id="name" required="">
                                                        </div>
                                                    
                                                    
    
                                                    <div class="form-group" data-aire-component="group" data-aire-for="registration_number">
                                                        <label class="cursor-pointer">Other Vehicle Plate Number:</label>
                                                        <input type="text" class="form-control" name="vehicle_plat_number" id="vehicle_plat_number" required="">
                                                    </div>
                    
                                                    <div class="form-group" data-aire-component="group" data-aire-for="phone" >
                                                        <label class="cursor-pointer">Contact Phone Number:</label>
                                                        <input type="number" class="form-control" name="contact" id="contact" required="">
                                                    </div>
                    
                                                    <div class="form-group" data-aire-component="group" data-aire-for="notes">
                                                        <label class="cursor-pointer">Other Notes:</label>
                                                        <textarea class="form-control" name="notes"  id="notes"></textarea>
                                                    </div>
                    
                                                    <div class="form-group custom-control custom-checkbox">
                                                       <input type="checkbox" value="1" class="custom-control-input" data-aire-component="checkbox" name="reported_insurance_company" data-aire-for="reported_insurance_company" id="reported_insurance_company">
                                                       <label class="custom-control-label" data-aire-component="label" data-aire-validation-key="checkbox_label" data-aire-for="reported_insurance_company" for="reported_insurance_company">Reported to the insurance company?</label>

                                                    </div>
                                                    
    
                                                </div>
    
                                                    <div class="col-md-6">
                                                        <!--<script src="https://releases.transloadit.com/uppy/v1.29.1/uppy.min.js"></script>-->
    
                                                        <!--<script>-->
                                                        <!--    (function() {-->
                                                        <!--        var uppy = Uppy.Core({-->
                                                        <!--            debug: true,-->
                                                        <!--            autoProceed: true,-->
                                                        <!--            restrictions: {-->
                                                        <!--                maxFileSize: null,-->
                                                        <!--                maxNumberOfFiles: 10,-->
                                                        <!--                minNumberOfFiles: 1,-->
                                                        <!--                allowedFileTypes: ["image/*", "application/pdf"]-->
                                                        <!--            }-->
                                                        <!--        });-->
                                                        <!--        uppy.use(Uppy.Dashboard, {-->
                                                        <!--            inline: true,-->
                                                        <!--            target: "#js-upload-component",-->
                                                        <!--            replaceTargetContent: true,-->
                                                        <!--            showProgressDetails: true,-->
                                                        <!--            note: "Images and pdf only",-->
                                                        <!--            height: 300,-->
                                                        <!--            browserBackButtonClose: true,-->
                                                        <!--            metaFields: [-->
                                                        <!--                { id: 'name', name: 'Name', placeholder: 'file name' },-->
                                                        <!--                { id: 'license', name: 'License', placeholder: 'specify license' },-->
                                                        <!--                { id: 'caption', name: 'Caption', placeholder: 'describe what the image is about' }-->
                                                        <!--            ]-->
                                                        <!--        });-->
                                                        <!--        uppy.use(Uppy.Webcam, {-->
                                                        <!--            target: Uppy.Dashboard,-->
                                                        <!--            modes: [-->
                                                        <!--                'picture'-->
                                                        <!--            ],-->
                                                        <!--            mirror: false,-->
                                                        <!--            facingMode: 'environment',-->
                                                        <!--        });-->
                                                        <!--        uppy.use(Uppy.GoogleDrive, { target: Uppy.Dashboard, companionUrl: "https://companion.uppy.io" });-->
                                                        <!--        uppy.use(Uppy.Dropbox, { target: Uppy.Dashboard, companionUrl: "https://companion.uppy.io" });-->
                                                        <!--        uppy.use(Uppy.Tus, { endpoint: "https://master.tus.io/files/" });-->
                                                        
                                                        <!--        uppy.on("complete", function(result) {-->
                                                        <!--            var el = document.getElementById('js-upload-component')-->
                                                        <!--            if (result.successful.length > 0) {-->
                                                        <!--                for (var i = 0; i < result.successful.length; i++) {-->
                                                        <!--                    var file = result.successful[i];-->
                                                        
                                                        <!--                    var fileInput = document.createElement("input");-->
                                                        <!--                    fileInput.type = "hidden";-->
                                                        <!--                    fileInput.value = file.response.uploadURL;-->
                                                        <!--                    fileInput.name = "files[]";-->
                                                        
                                                        <!--                    el.appendChild(fileInput);-->
                                                        
                                                        <!--                    var nameInput = document.createElement("input");-->
                                                        <!--                    nameInput.type = "hidden";-->
                                                        <!--                    nameInput.value = file.name;-->
                                                        <!--                    nameInput.name = "files_names[]";-->
                                                        <!--                    nameInput.id = "files_names";-->
                                                        
                                                        <!--                    el.appendChild(nameInput);-->
                                                        <!--                }-->
                                                        <!--            }-->
                                                        <!--        });-->
                                                        <!--    })();-->
                                                        <!--</script>-->
                                                        
                                                    <input type="file" class="form-control p-1" name="image" id="image" multiple="multiple" required="">
    
    
                                                    </div>
                                            </div>
                                        </div>
                                    </div>  
                                     <div class="card-footer">
                                        <button class="btn btn-primary " type="" name="submit" id="submit" onclick="insert();">Report</button>
                                        <a href="/" class="btn btn-linl">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    
                    
        <?php include('footer.php');?>         
    </div>
            
<?php include('footerScript.php'); ?>
<script src="dist/jq.dice-menu.js"></script>
<script>


function insert(){
    var form_data = new FormData();
    var totalfiles = document.getElementById('image').files.length;
        for (var index = 0; index < totalfiles; index++) {
            form_data.append("files[]", document.getElementById('image').files[index]);
        }
       var description_accident = $('#description_accident').val();
       var vehicle_plat_number = $('#vehicle_plat_number').val();
       var name = $('#name').val();
       var contact = $('#contact').val();
       var notes = $('#notes').val();
       var reported_insurance_company = $('#reported_insurance_company').val();
        if (description_accident == ""  ||  vehicle_plat_number == "" ||  contact == "")
        {
            // alert("Required Field must be filled out");
            return false;
        }
        else
        {
            form_data.append("description_accident",description_accident);
            form_data.append("name",name);
            form_data.append("vehicle_plat_number",vehicle_plat_number);
            form_data.append("contact",contact);
            form_data.append("notes",notes);
            form_data.append("reported_insurance_company",reported_insurance_company);
            form_data.append("action","addReportAccidentForm");
                event.preventDefault();
                $.ajax({
                 url: 'InsertData.php', 
                 type: 'post',
                 data: form_data,
                 dataType: 'json',
                 contentType: false,
                 processData: false,
                 success: function (response) 
                 {
                    if(response.status==1)
                    {
                        myAlert(response.title + "@#@" + response.message + "@#@success");
                        
                    }
                    else
                    {
                        myAlert(response.title + "@#@" + response.message + "@#@danger");
                    }
                }
            });
        }    
    
}

var id = $('#id').val();

if(id != "")
{
     alert(id);

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'reportaccidentUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#description_accident').val($result_data['description_accident']);
                $('#name').val($result_data['name']);
                $('#vehicle_plat_number').val($result_data['vehicle_plat_number']);
                $('#contact').val($result_data['contact']);
                $('#notes').val($result_data['notes']);
                $('#reported_insurance_company').val($result_data['reported_insurance_company']);
               
                $("#submit").attr('name', 'update');

                $("#submit").text('Update');

            }

        });
}


</script>
</body>
</html>