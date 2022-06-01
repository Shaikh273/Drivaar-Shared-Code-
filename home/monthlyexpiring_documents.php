<?php
$page_title = "Drivaar";
include 'DB/config.php';
$page_id = 4;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    $userid = $_SESSION['userid'];
    if ($userid == 1) {
        $uid = '%';
    } else {
        $uid = $userid;
    }
} else {
    header("location: login.php");
}
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
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Expiring Documents</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-primary">
                                        <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable(this.value);">
                                            <option value="%">All Depots</option>
                                                <?php
                                                    $mysql = new Mysql();
                                                    $mysql->dbConnect();
                                                    $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                                INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                                WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
                                                    $strow =  $mysql->selectFreeRun($statusquery);
                                                    while ($statusresult = mysqli_fetch_array($strow)) {
                                                    ?>
                                                        <option value="<?php echo $statusresult['id'] ?>"><?php echo $statusresult['name'] ?></option>
                                                    <?php
                                                }
                                                $mysql->dbDisconnect();
                                                ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="table table-responsive" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th width="10%">Driver</th>
                                            <th width="20%">Depot</th>
                                            <th width="15%">Email</th>
                                            <th width="40%">Documents</th>
                                            <th width="5%">Expiry Date</th>
                                            <th width="5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="etable">

                                    </tbody>
                                </table>
                                <br>
                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>


        <div id="addstatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header" style="background-color: rgb(255 236 230);">
                      <h4 class="modal-title">Upload New Documents</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <form method="post" id="NewDocumentForm1" name="NewDocumentForm" action="" enctype="multipart/form-data">
                        <input type="hidden" name="cid" id="cid">
                        <input type="hidden" name="id" id="id">
                         <div class="form-body">
                            <div class="row">
                                <div class="col-md-12">
                                      <div class="form-group">
                                          <label class="control-label" id="Settype">Type : </label>
                                      </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 hidden" id="expdt">
                                    <label class="control-label">Expires Date *</label>
                                    <div class="form-group">
                                        <input type="date" class="form-control" id="expiredate" name="expiredate" placeholder="mm/dd/yyyy">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <span class="file">
                                          <label class="control-label"> File *</label><br>
                                          <input type="file" id="file" name="file" class="form-control" placeholder="">
                                    </span> 
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" name="insert" class="btn btn-success" id="submit">Save changes</button>
                      <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                  </div>
                  </form>
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

    $("#file").change(function() {
        var file = this.files[0];
        var fileType = file.type;
        var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
        if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5])))
        {
            alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
            $("#file").val('');
            return false;
        }
    });

        $(document).ready(function() {
            loadtable($("#depot_id").val());
        });

        // $(function() {
        //     event.preventDefault();
        //     $("#NewDocumentForm1 form").validate({
        //         rules: {
        //             expiredate: 'required',
        //             file: 'required'
        //         },
        //         messages: {
        //             expiredate: "Please  enter your expire date",
        //             file: "Please select your file"
        //         }
        //     });
            
        //     $('#NewDocumentForm1').submit(function(e) {
        //        e.preventDefault();
        //         $.ajax({
        //             type: 'POST',
        //             url: 'contractordocumentupload.php',
        //             data: new FormData(this),
        //             dataType: 'json',
        //             contentType: false,
        //             cache: false,
        //             processData:false,
        //             success: function(data)
        //             { 
        //                 if(data.status==1)
        //                 {
        //                     myAlert(data.title + "@#@" + data.message + "@#@success");
        //                     $('#DocumentForm')[0].reset();
        //                     if(data.name == 'Update')
        //                     {
        //                         var table = $('#myTable').DataTable();
        //                         table.ajax.reload();
        //                         $("#AddFormDiv,#AddDiv").hide();
        //                         $("#ViewFormDiv,#ViewDiv").show();
        //                     }
        //                     $('#imgsrc').addClass('hidden');
        //                     $('#imgsrc').src('');
        //                 }
        //                 else
        //                 {
        //                     myAlert(data.title + "@#@" + data.message + "@#@danger");
        //                 }
        //             }
        //         });
        //     });
        // });

        $("#NewDocumentForm1").validate({
                rules: {
                    expiredate: 'required',
                    file: 'required'
                },
                messages: {
                    expiredate: "Please  enter your expire date",
                    file: "Please select your file"
                }
        });

        $("#NewDocumentForm1").on('submit', function(e){
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'contractordocumentupload.php',
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
                        $('#addstatus').modal('hide');
                        $('#NewDocumentForm1')[0].reset();
                        //loadtable($("#depot_id").val());
                        var table = $('#myTable').DataTable();
                        table.ajax.reload();
                           
                    }
                    else
                    {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        });

        function loadtable(did) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'MonthlyContractorExpiringDocumentData',
                    did: did
                },
                dataType: 'text',
                success: function(data) {
                    if (data == '') {
                        $('#etable').html("<tr><td colspan='5'>No Data Table..!</td></tr>");
                    } else {
                        $('#etable').html(data);
                    }
                }
            });
        }

        function loadpage(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ContractorSetSessionData',
                    cid: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        window.location = '<?php echo $webroot ?>contractor_document.php';
                    }
                }
            });
        }

        function edit(id) {
            $('#id').val(id);
            ShowHideDiv('view');
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'VehicleExtraUpdateData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $result_data = data.statusdata;
                    $('#name').val($result_data['name']);
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');
                }
            });
        }

        function addstatus(cid,id,typename,isexpiry)
        {
           // $("#addstatus").reload();
            $('#addstatus').modal('show');
            $('#cid').val(cid);
            $('#id').val(id);
            $('#Settype').html('Type : '+typename);
            if(isexpiry==1)
            {
                $('#expdt').removeClass('hidden');
            }
            else
            {
                $('#expdt').addClass('hidden');
            }
            event.preventDefault();
        }

        function deleterow(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'VehicleExtraDeleteData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        var table = $('#myTable').DataTable();
                        table.ajax.reload();
                        myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                    } else {
                        myAlert("Delete @#@ Data can not been deleted.@#@danger");
                    }
                }
            });
        }
    </script>
</body>

</html>