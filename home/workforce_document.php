
<?php
$page_title="Document";
include 'DB/config.php';
$page_id=37;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['wid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_user` WHERE `id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        if($cntresult['isactive']==0)
        {
            $colorcode= "green";
            $statusname = "Active";
        }
        else
        {
            $colorcode= "red";
            $statusname = "Inactive";
        }
        $mysql -> dbDisConnect();

    }else
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <style>
        .file > input{
          height: 100%;
          width: 100;
          opacity: 0;
          cursor: pointer;
        }
        .file{
          border: 1px solid #ccc;
          width: 100px;
          height: 100px;
          display: inline-block;
          overflow: hidden;
          cursor: pointer;
          
          /*for the background, optional*/
          background: center center no-repeat;
          background-size: 75% 75%;
          background-image:  url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBoZWlnaHQ9IjUxMnB4IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9Ii01MyAxIDUxMSA1MTEuOTk5MDYiIHdpZHRoPSI1MTJweCI+CjxnIGlkPSJzdXJmYWNlMSI+CjxwYXRoIGQ9Ik0gMjc2LjQxMDE1NiAzLjk1NzAzMSBDIDI3NC4wNjI1IDEuNDg0Mzc1IDI3MC44NDM3NSAwIDI2Ny41MDc4MTIgMCBMIDY3Ljc3NzM0NCAwIEMgMzAuOTIxODc1IDAgMC41IDMwLjMwMDc4MSAwLjUgNjcuMTUyMzQ0IEwgMC41IDQ0NC44NDM3NSBDIDAuNSA0ODEuNjk5MjE5IDMwLjkyMTg3NSA1MTIgNjcuNzc3MzQ0IDUxMiBMIDMzOC44NjMyODEgNTEyIEMgMzc1LjcxODc1IDUxMiA0MDYuMTQwNjI1IDQ4MS42OTkyMTkgNDA2LjE0MDYyNSA0NDQuODQzNzUgTCA0MDYuMTQwNjI1IDE0NC45NDE0MDYgQyA0MDYuMTQwNjI1IDE0MS43MjY1NjIgNDA0LjY1NjI1IDEzOC42MzY3MTkgNDAyLjU1NDY4OCAxMzYuMjg1MTU2IFogTSAyNzkuOTk2MDk0IDQzLjY1NjI1IEwgMzY0LjQ2NDg0NCAxMzIuMzI4MTI1IEwgMzA5LjU1NDY4OCAxMzIuMzI4MTI1IEMgMjkzLjIzMDQ2OSAxMzIuMzI4MTI1IDI3OS45OTYwOTQgMTE5LjIxODc1IDI3OS45OTYwOTQgMTAyLjg5NDUzMSBaIE0gMzM4Ljg2MzI4MSA0ODcuMjY1NjI1IEwgNjcuNzc3MzQ0IDQ4Ny4yNjU2MjUgQyA0NC42NTIzNDQgNDg3LjI2NTYyNSAyNS4yMzQzNzUgNDY4LjA5NzY1NiAyNS4yMzQzNzUgNDQ0Ljg0Mzc1IEwgMjUuMjM0Mzc1IDY3LjE1MjM0NCBDIDI1LjIzNDM3NSA0NC4wMjczNDQgNDQuNTI3MzQ0IDI0LjczNDM3NSA2Ny43NzczNDQgMjQuNzM0Mzc1IEwgMjU1LjI2MTcxOSAyNC43MzQzNzUgTCAyNTUuMjYxNzE5IDEwMi44OTQ1MzEgQyAyNTUuMjYxNzE5IDEzMi45NDUzMTIgMjc5LjUwMzkwNiAxNTcuMDYyNSAzMDkuNTU0Njg4IDE1Ny4wNjI1IEwgMzgxLjQwNjI1IDE1Ny4wNjI1IEwgMzgxLjQwNjI1IDQ0NC44NDM3NSBDIDM4MS40MDYyNSA0NjguMDk3NjU2IDM2Mi4xMTMyODEgNDg3LjI2NTYyNSAzMzguODYzMjgxIDQ4Ny4yNjU2MjUgWiBNIDMzOC44NjMyODEgNDg3LjI2NTYyNSAiIHN0eWxlPSIgZmlsbC1ydWxlOm5vbnplcm87ZmlsbC1vcGFjaXR5OjE7IiBzdHJva2U9IiMwMDAwMDAiIGZpbGw9IiMwMDAwMDAiLz4KPHBhdGggZD0iTSAzMDUuMTAxNTYyIDQwMS45MzM1OTQgTCAxMDEuNTM5MDYyIDQwMS45MzM1OTQgQyA5NC43MzgyODEgNDAxLjkzMzU5NCA4OS4xNzE4NzUgNDA3LjQ5NjA5NCA4OS4xNzE4NzUgNDE0LjMwMDc4MSBDIDg5LjE3MTg3NSA0MjEuMTAxNTYyIDk0LjczODI4MSA0MjYuNjY3OTY5IDEwMS41MzkwNjIgNDI2LjY2Nzk2OSBMIDMwNS4yMjY1NjIgNDI2LjY2Nzk2OSBDIDMxMi4wMjczNDQgNDI2LjY2Nzk2OSAzMTcuNTkzNzUgNDIxLjEwMTU2MiAzMTcuNTkzNzUgNDE0LjMwMDc4MSBDIDMxNy41OTM3NSA0MDcuNDk2MDk0IDMxMi4wMjczNDQgNDAxLjkzMzU5NCAzMDUuMTAxNTYyIDQwMS45MzM1OTQgWiBNIDMwNS4xMDE1NjIgNDAxLjkzMzU5NCAiIHN0eWxlPSIgZmlsbC1ydWxlOm5vbnplcm87ZmlsbC1vcGFjaXR5OjE7IiBzdHJva2U9IiMwMDAwMDAiIGZpbGw9IiMwMDAwMDAiLz4KPHBhdGggZD0iTSAxNDAgMjY4Ljg2MzI4MSBMIDE5MC45NTMxMjUgMjE0LjA3NDIxOSBMIDE5MC45NTMxMjUgMzQ5LjEyNSBDIDE5MC45NTMxMjUgMzU1LjkyNTc4MSAxOTYuNTE5NTMxIDM2MS40OTIxODggMjAzLjMyMDMxMiAzNjEuNDkyMTg4IEMgMjEwLjEyNSAzNjEuNDkyMTg4IDIxNS42ODc1IDM1NS45MjU3ODEgMjE1LjY4NzUgMzQ5LjEyNSBMIDIxNS42ODc1IDIxNC4wNzQyMTkgTCAyNjYuNjQwNjI1IDI2OC44NjMyODEgQyAyNjkuMTEzMjgxIDI3MS40NTcwMzEgMjcyLjMzMjAzMSAyNzIuODIwMzEyIDI3NS42Njc5NjkgMjcyLjgyMDMxMiBDIDI3OC42MzY3MTkgMjcyLjgyMDMxMiAyODEuNzMwNDY5IDI3MS43MDcwMzEgMjg0LjA3ODEyNSAyNjkuNDgwNDY5IEMgMjg5LjAyNzM0NCAyNjQuNzgxMjUgMjg5LjM5ODQzOCAyNTYuOTg4MjgxIDI4NC42OTkyMTkgMjUyLjA0Mjk2OSBMIDIxMi4yMjY1NjIgMTc0LjI1MzkwNiBDIDIwOS44NzUgMTcxLjc4MTI1IDIwNi42NjAxNTYgMTcwLjI5Njg3NSAyMDMuMTk5MjE5IDE3MC4yOTY4NzUgQyAxOTkuNzM0Mzc1IDE3MC4yOTY4NzUgMTk2LjUxOTUzMSAxNzEuNzgxMjUgMTk0LjE3MTg3NSAxNzQuMjUzOTA2IEwgMTIxLjY5OTIxOSAyNTIuMDQyOTY5IEMgMTE3IDI1Ni45ODgyODEgMTE3LjM3MTA5NCAyNjQuOTAyMzQ0IDEyMi4zMTY0MDYgMjY5LjQ4MDQ2OSBDIDEyNy41MTE3MTkgMjc0LjE3OTY4OCAxMzUuMzAwNzgxIDI3My44MDg1OTQgMTQwIDI2OC44NjMyODEgWiBNIDE0MCAyNjguODYzMjgxICIgc3R5bGU9IiBmaWxsLXJ1bGU6bm9uemVybztmaWxsLW9wYWNpdHk6MTsiIHN0cm9rZT0iIzAwMDAwMCIgZmlsbD0iIzAwMDAwMCIvPgo8L2c+Cjwvc3ZnPgo=)
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
    // include('menu.php');
?>
<div class="page-wrapper">
<div class="container-fluid">
    <main class="container-fluid  animated">
        <div class="card">    
            <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header">Workforce / <?php echo $cntresult['name'];?></div>
                            <div> 
                              <a href="">
                                    <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                               </a>
                              <a href="workforce_edit.php"> 
                                    <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                              </a>
                           </div>
                        </div>
            </div>
<div class="card-body">
  <div class="col">
    <div class="d-flex align-items-center">
        <div class="mr-2">
          <?php 
          if($cntresult['isactive']==0)
          {
            ?>
              <span class="label label-success">Active</span>
            <?php
          }
          else
          {
            ?>
              <span class="label label-danger">Inactive</span>
            <?php
          }
          ?>  
        </div>
        <div class="mr-3 text-grey-darkest whitespace-no-wrap">
          <i class="fas fa-suitcase"></i> 
          <?php echo $cntresult['role_type'];?>
        </div>
        <div class="mr-3 text-grey-darkest whitespace-no-wrap">
           <i class="fas fa-envelope-open"></i>
            <?php echo $cntresult['email'];?>
        </div>
    </div>  
</div><br><hr>
<?php include('workforce_setting.php'); ?>
  <div class="row">
    <div class="card col-md-12" style="border: 1px solid #d1d5db;">   
        <div class="card-header" style="background-color: #fff;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header">Documents</div>

                 <div> 
                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Documents</button>
                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Documents</button>
                 </div>                   

            </div>
        </div>
        <div class="card-body" id="AddFormDiv">
            <div class="row">
              <div class="col-md-12">
                  <form method="post" id="DocumentForm" name="DocumentForm" action="" enctype="multipart/form-data">
                      <input type="hidden" name="id" id="id" value="">
                      <input type="hidden" name="wid" id="wid" value="<?php echo $id?>">  
                         <div class="form-body">

                            <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label"> Type *</label>
                                          <select class="select form-control custom-select" id="type" name="type">
                                            <?php
                                            $mysql = new Mysql();
                                            $mysql -> dbConnect();
                                            $statusquery = "SELECT * FROM `tbl_vehicledocumenttype` WHERE `isdelete`=0 AND `isactive`=0";
                                            $strow =  $mysql -> selectFreeRun($statusquery);
                                            while($statusresult = mysqli_fetch_array($strow))
                                            {
                                              ?>
                                                  <option value="<?php echo $statusresult['id']?>"><?php echo $statusresult['name']?></option>
                                              <?php
                                            }
                                            $mysql -> dbDisconnect();
                                            ?>
                                        </select> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                          <label class="control-label"> Name *</label>
                                          <input type="text" id="name" name="name" class="form-control" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Expires Date *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control mydatepicker" id="expiredate" name="expiredate" placeholder="mm/dd/yyyy">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="file">
                                          <label class="control-label"> File *</label><br>
                                          <input type="file" id="file" name="file" class="form-control" placeholder=""  onchange="loadFile(event)" >
                                      </span> 
                                </div>
                                <div class="col-md-4">
                                      <iframe src="" id="imgsrc" name="imgsrc" style="height: 140%;" class="hidden"></iframe>
                                </div>
                            </div>
                        </div>
                        <br>
                         <div class="form-actions">
                            <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
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
                                <th>File</th>
                                <th>Type</th>
                                <th>Expiry Date</th>
                                <th>Uploaded by</th>
                                <th>Size (In Bytes)</th>
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
    var wid="<?php echo $id?>";
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


    $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();
        $('#myTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'ajax': {
                        'url':'loadtabledata.php',
                        'data': {
                            'action': 'loadaddworkforcedocumenttabledata',
                             wid:wid
                        }
                    },
                    'columns': [
                        { data: 'file' },
                        { data: 'typename' },
                        { data: 'expiredate' },
                        { data: 'name' },
                        { data: 'Size' },
                        { data: 'date' },
                        { data: 'Status' },
                        { data: 'Action' }
                    ]
        });

        $("#DocumentForm").validate({
            rules: {
                name: 'required',
                type: 'required',
                expiredate: 'required',
                file: 'required',
            },
            messages: {
                name: "Please enter your name",
                type: "Please select your type",
                expiredate: "Please  enter your expire date",
                file: "Please select your file",
            },
        });

        $("#DocumentForm").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'workforcedocumentupload.php',
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
                        $('#DocumentForm')[0].reset();
                        if(data.name == 'Update')
                        {
                            var table = $('#myTable').DataTable();
                            table.ajax.reload();
                            $("#AddFormDiv,#AddDiv").hide();
                            $("#ViewFormDiv,#ViewDiv").show();
                        }
                        $('#imgsrc').addClass('hidden');
                        $('#imgsrc').src('');
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        });
    });

    function edit(id)
    {

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'WorkforceDocumentUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#name').val($result_data['name']);
                $('#type').val($result_data['type']);
                $('#expiredate').val($result_data['expiredate']);
               // $('#file').val($result_data['file']);
                $('#imgsrc').removeClass('hidden');
                $('#imgsrc').attr('src',webroot+'uploads/contractordocument/'+$result_data['file']);
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

    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'WorkforceDocumentDeleteData', id: id},

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
        $('#DocumentForm')[0].reset(); 
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