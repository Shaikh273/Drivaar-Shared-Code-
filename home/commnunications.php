<?php
include 'DB/config.php';
$page_title="Communication Center";

 if(!isset($_SESSION)) 
    { 
        session_start();
        
    }
    if($_SESSION['userid']==1)
    {
       $userid='%'; 
    }
    else
    {
       $userid = $_SESSION['userid']; 
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        // $userid=$_SESSION['userid'];
    }else
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
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link href="../assets/node_modules/dropzone-master/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <link href="dist/css/pages/stylish-tooltip.css" rel="stylesheet">
</head>
<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>
<div id="main-wrapper">
<?php include('header.php');?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">   
            <div class="card-header" style="background-color: rgb(255 236 230);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header">Send email to depots</div>

                     <div> 
                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Communication Center</button>

                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Communication Center</button>
                     </div>                   

                </div>
            </div>
            <div class="card-body" id="AddFormDiv">
                <form  method="post" name="communication-Form" id="communication-Form" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<?php echo $_SESSION['userid'];?>">
                    <input type="hidden" name="image_name" id="image_name" value="">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" data-aire-component="group" data-aire-for="subject">
                                        <label class=" cursor-pointer" >Subject:</label>
                                        <input type="text" class="form-control" name="subject" id="subject">
                                    </div>
                                    <div class="form-group" data-aire-component="group" data-aire-for="subject">
                                        <label class=" cursor-pointer" >Images / Files:</label>
                                        <input type="file" class="form-control" name="emailFiles" id="emailFiles" multiple>
                                    </div>
                                    <div class="form-group" data-aire-component="group" data-aire-for="subject" id="uploadTable" style="display:none">
                                        <label class=" cursor-pointer" >Link for copy</label>
                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Links</th>
                                                </tr>
                                            </thead>
                                            <tbody id="linkBody">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                         <textarea id="mymce" name="area" required></textarea>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Category</label>
                                        <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="ctype">
                                            <option value="0">Active</option>
                                            <option value="1">Inactive</option>
                                            <option value="2">All</option>
                                        </select>
                                    </div>
                                    

                                    <div class="card" style="display:none;">
                                        <div class="card-body">
                                            <div id="photo" class="dropzone">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6 pl-5">
                                    <label>Depots:</label>
                                    <?php
                                        $mysql = new Mysql();
                                        $mysql -> dbConnect();
                                        $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id` WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL ";
                                        $strow =  $mysql -> selectFreeRun($statusquery);
                                        while($statusresult = mysqli_fetch_array($strow))
                                        {
                                    ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="depot_id[]" id="<?php echo $statusresult['id'];?>" value="<?php echo $statusresult['id'];?>">
                                            <label class="custom-control-label" for="<?php echo $statusresult['id'];?>"><?php echo $statusresult['name'];?></label>
                                        </div>
                                    <?php
                                        }
                                    ?>

                                </div>    

                            </div>
                        </div> 
                        <div class="card-footer">
                            <button class="btn btn-success " type="submit" name="insert" value="1" id="submit">Send</button>
                            <a href="" class="btn btn-link btn-">Cancel</a>
                        </div>   
                    </div>
                </form>
            </div>

            <div class="card-body" id="ViewFormDiv">
                <!-- <div class="row">
                                     
                  <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                             <div class="form-group has-primary">
                                <input type="text" class="form-control" name="driver_name_serch" id="driver_name_serch" placeholder="By driver..." onkeyup="loadtable();">  
                            </div>
                        </div>
                        <div class="col-md-3">
                             <div class="form-group has-primary">
                            <select class="select form-control custom-select" id="vehicle_serch" name="vehicle_serch" onchange="loadtable();">
                                <option value="%">All Vehicle</option>
                                <?php
                                    $statusquery = "SELECT * FROM `tbl_vehicles` WHERE `isdelete`=0 AND `userid` LIKE '".$userid."'";
                                        $strow =  $mysql -> selectFreeRun($statusquery);
                                        while($statusresult = mysqli_fetch_array($strow))
                                        {
                                ?>
                                    <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['registration_number']?></option>
                                <?php
                                    }
                                ?>
                            </select>   
                            </div>
                        </div>

                        <div class="col-md-3">
                          <div class="form-group has-primary">
                               <select class="select form-control custom-select" id="stage_serch" name="stage_serch" onchange="loadtable();">
                                <option value="%">All Stage</option>
                                <?php
                               
                                $query1 = "SELECT * FROM `tbl_vehicleaccidentstage` WHERE `isdelete`=0 AND `isactive`=0";
                                $row1 =  $mysql -> selectFreeRun($query1);
                                while($result2 = mysqli_fetch_array($row1))
                                {
                                  ?>
                                      <option value="<?php echo $result2['id']?>"><?php echo $result2['name']?></option>
                                  <?php
                                }
                                // $mysql -> dbDisconnect();
                            ?>
                            </select> 
                          </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group has-primary">
                              <select class="select form-control custom-select" id="status_serch" name="status_serch" onchange="loadtable();">
                                <option value="%">By Status</option>
                                <option value="0">Open</option>
                                <option value="1">Close</option>
                                
                            </select>
                            </div>
                        </div>
                    </div>
                  </div>
                </div> -->

                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                    <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                        <thead class="default">
                            <tr role="row">
                                <th>Date</th>
                                <th>Issued By</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Depots</th>
                                <th>References</th>
                                <th># of Recipients</th>
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

<?php include('footer.php');?>
</div>

<?php include('footerScript.php');?>
<script src="../assets/node_modules/tinymce/tinymce.min.js"></script>
<script src="../assets/node_modules/dropzone-master/dist/dropzone.js"></script>

<script>
    var myDropzone1 = "";
    var img = new Array();
    Dropzone.autoDiscover = false;

        $(document).ready(function() {
        var myDropzone1 = new Dropzone("#photo", {
        url: "communication_image.php",
        paramName: "file",
        // params: {'uniqueid':a},
        maxFilesize: 2,
        maxFiles: 10,
        autoProcessQueue: true,
        success:function(response ) {
            
        }
    });


        if ($("#mymce").length > 0) {
            tinymce.init({
                selector: "textarea#mymce",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
                relative_urls : false,
                remove_script_host : false,

            });
        }


    $("#AddFormDiv,#AddDiv").hide();

    $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata1.php',
                'data': 
                {
                    'action' : 'loadacommunicationtabledata',
                          
                }
            },
            'columns': [
                { data: 'date' },
                { data: 'issued_by' },
                { data: 'subject' },
                { data: 'meassage' },
                { data: 'depot_id' },
                { data: 'reference' },
                { data: 'receipt' },
            ]
        });

    $("#communication-Form").validate({

            rules: {

                subject: 'required',
                area: 'required',
                depot_id: 'required'
            },

            messages: {
                subject: "Please enter a subject",
                area: 'Email content must nor be blank.',
                depot_id: 'Please select at least one depot.'
            },
            submitHandler: function(form) {
                event.preventDefault();
                // for(i = 0; i<myDropzone1.files.length; i++) {
                //     var imgname = myDropzone1.files[i].name;
                //     img[i] = imgname;
                   
                // }
                // $("#image_name").val(img);
                $.ajax({
                    url: "InsertData1.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#communication-Form").serialize()+"&action=communicationForm",
                    // cache: false,             
                    // processData: false,      
                    success: function(data) 
                    {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#communication-Form')[0].reset();
                            if(data.name == 'Update')
                            {
                                var table = $('#myTable').DataTable();
                                table.ajax.reload();
                                $("#AddFormDiv,#AddDiv").hide();
                                $("#ViewFormDiv,#ViewDiv").show();
                            }
                            location.reload();
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

function ajaxreload() {
    $.ajax({
        url:"loaddata.php",
        method:"POST",
        dataType:"json",
        data: {action : 'loadtermdetails'},
        success:function(response){  
            $("#mymce").val(response);
        }
    });
}
        

function loadtable()
{
      var table = $('#myTable').DataTable();
      table.ajax.reload();
}

function edit(id)
{

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata1.php",

            data: {action : 'VehicleaccidentUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;
                var a = $result_data['date'];
                var dateVal = new Date(a);
                var day = dateVal.getDate().toString().padStart(2, "0");
                var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
                var hour = dateVal.getHours().toString().padStart(2, "0");
                var minute = dateVal.getMinutes().toString().padStart(2, "0");
                var sec = dateVal.getSeconds().toString().padStart(2, "0");
                var ms = dateVal.getMilliseconds().toString().padStart(3, "0");
                var inputDate = dateVal.getFullYear() + "-" + (month) + "-" + (day) + "T" + (hour) + ":" + (minute) + ":" + (sec) + "." + (ms);
                var d = $result_data['type'];
                var s = $result_data['stage'];
                var c = $result_data['driver'];
                $('#type_id option[value="'+d+'"]').attr("selected", "selected");
                $('#stage_id option[value="'+s+'"]').attr("selected", "selected");
                $('#driver_id option[value="'+c+'"]').attr("selected", "selected");
                $('#vehicle_id').val($result_data['vehicle']);
                $('#date_occured').val(inputDate);
                // $('#type_id').val($result_data['type_id']);
                // $('#stage_id').val($result_data['stage_id']);
                $('#reference').val($result_data['reference']);
                $('#description').val($result_data['description']);
                $('#other_person').val($result_data['other_person']);
                $('#other_vehicle').val($result_data['other_vehicle']);
                $('#contact').val($result_data['contact']);
                // ('#other_notes').text($result_data['other_notes']);
                $("textarea#other_notes").val($result_data['other_notes']);
                $("#accident_image").hide();



                $("#submit").attr('name', 'update');
                $("#submit").attr('value', '0');

                $("#submit").text('Update');

            }

        });
}


function deleterow(id)
{

    $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'accidentDeleteData', id: id},

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
        // $('#accidentForm')[0].reset(); 

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
<script>
    var fileNode = document.querySelector('#emailFiles'),
    form = new FormData(),
    xhr = new XMLHttpRequest();
    fileNode.addEventListener('change', function( event ) {
    event.preventDefault();
    var files = this.files;
    for (var i = 0, numFiles = files.length; i < numFiles; i++) 
    {
        var file = files[i];
        form.append('myfiles[]', file, file.name);
        xhr.onload = function() {
            if (xhr.status === 200) {
                $("#uploadTable").show();
                $("#linkBody").append(xhr.responseText);
            }
        }
        xhr.open('POST', 'uploadEmailFiles.php');
        xhr.send(form);
    }
    });




    $(function () {
      $('[data-toggle="tooltip"]').tooltip();
    })
</script>
</body>
</html>