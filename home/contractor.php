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
    $active = 33;
    $inactive = 33;
    $onboard = 34;
    $mysql = new Mysql();
    $mysql->dbConnect();
    $query1 = "SELECT 
				COUNT(DISTINCT IF( c.`isactive` = 0,c.`id`,NULL)) AS isactive,
				COUNT(DISTINCT IF( c.`isactive`= 1,c.`id`,NULL)) AS inactive,
				COUNT(DISTINCT IF( c.`isactive` = 2,c.`id`,NULL)) AS onboard
				FROM `tbl_contractor` c 
				INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $uid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
				WHERE c.`depot` IN (w.depot_id) AND c.`isdelete`=0";
    $row1 =  $mysql->selectFreeRun($query1);
    $result = mysqli_fetch_array($row1);
    if ($result > 0) {
        $active = $result['isactive'];
        $inactive = $result['inactive'];
        $onboard = $result['onboard'];
    }

    $mysql->dbDisconnect();
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
                                <div class="header">Contractor</div>
                                <div class="col-md-6"> </div>
                                <div class="col-md-2">
                                    <div class="d-flex text-center align-items-right">
                                        <div class="col  border-right">
                                            <div class="text-grey-dark">Active</div>
                                            <h5 class="m-0" style="font-weight: 700;">
                                                <span class="text-green-600"><?php echo $active; ?></span>

                                            </h5>
                                        </div>
                                        <div class="col  border-right">
                                            <div class="text-grey-dark">Inactive</div>
                                            <h5 class="m-0" style="font-weight: 700;">
                                                <span class="text-red-600"><?php echo $inactive; ?></span>

                                            </h5>
                                        </div>
                                        <div class="col  border-right">
                                            <div class="text-grey-dark">Onboard</div>
                                            <h5 class="m-0" style="font-weight: 700;">
                                                <span class="text-blue-600"><?php echo  $onboard; ?></span>

                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <?php
                                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][10] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) { ?>
                                        <button type="button" class="btn btn-primary"><a href="onboard_contractor.php">Onboard Contractor</a></button>
                                    <?php
                                    } else {
                                        header("location: login.php");
                                    }
                                    ?>
                                    <?php
                                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][6] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) { ?>
                                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Contractor</button>
                                    <?php
                                    } else {
                                        header("location: login.php");
                                    }
                                    ?>
                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Contractor</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" id="ContractorForm" name="ContractorForm" action="">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">
                                        <div class="form-body">
                                            <div class="card-header" style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="m-0">Personal Details</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <br>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="self" name="type" class="custom-control-input" value="1" checked>
                                                        <label class="custom-control-label" for="self">Self-Employed</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="private" name="type" class="custom-control-input" value="2">
                                                        <label class="custom-control-label" for="private">Limited Company</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3"><br>
                                                    <div class="form-group" style="display:none;" id="companydiv">
                                                        <select class="select form-control custom-select" id="company" name="company">
                                                            <option value="0">Select Company</option>
                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();

                                                            $query = "SELECT id,name,company_reg FROM `tbl_contractorcompany` WHERE isdelete=0 AND isactive=0";
                                                            $strow =  $mysql->selectFreeRun($query);
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
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Name *</label>
                                                        <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Email *</label>
                                                        <input type="email" id="email" name="email" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Phone *</label>
                                                    <div class="form-group">

                                                        <input type="number" id="contact" name="contact" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <script src="countrycode/build/js/intlTelInput.js"></script>
                                                <script>
                                                    var input = document.querySelector("#contact");
                                                    window.intlTelInput(input, {
                                                        autoHideDialCode: false,
                                                        utilsScript: "countrycode/build/js/utils.js",
                                                    });
                                                </script>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label"> Depot *</label>
                                                        <select class="select form-control custom-select" id="depot" name="depot">

                                                            <?php
                                                            $mysql = new Mysql();
                                                            $mysql->dbConnect();

                                                            $query = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                    WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
                                                            $strow =  $mysql->selectFreeRun($query);
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
                                            <div class="card-header" style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="m-0">Bank Details</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Bank Name *</label>
                                                        <input type="text" id="bank_name" name="bank_name" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Account number *</label>
                                                        <input type="text" id="account_number" name="account_number" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Sort Code *</label>
                                                        <input type="text" id="sort_code" name="sort_code" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-header" style="background-color: rgb(255 236 230);margin-bottom: 25px;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="m-0">Address & Company Details</h5>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Address *</label>
                                                        <input type="text" id="address" name="address" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Company register number *</label>
                                                        <input type="text" id="company_reg" name="company_reg" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">UTR *</label>
                                                        <input type="text" id="utr" name="utr" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="insert" class="btn btn-info" id="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="ViewFormDiv">
                            <div class="row">
                                <div class="col-md-6" style="margin-top: 23px;">
                                    <div class="col-md-3" style="margin-bottom: 15px;">
                                        <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Advanced Search
                                        </a>
                                    </div>
                                </div>
                                <div class="collapse col-md-12" id="collapseExample">
                                    <br><br>
                                    <div class="card card-body border border-primary rounded">
                                        <div class="row">
                                            <!--  <div class="col-md-6">
                                          <div class="col-md-12 pt-2">
                                            <div class="progress mb-3 w-100">
                                                <div class="progress-bar" data-toggle="tooltip" data-title="573" role="progressbar" style="background: #e1f8eb; color:#000; width:<?php //echo $active; 
                                                                                                                                                                                    ?>%">
                                                    <a href="#"  style="color: #000;">Active</a>
                                                </div>
                                            </div>
                                          </div>

                                          <div class="col-md-12 pt-2">
                                              <div class="progress mb-3 w-100">
                                                  <div class="progress-bar" data-toggle="tooltip" data-title="3908" role="progressbar" style="background: #fde9e0; color:#000; width:<?php //echo $inactive; 
                                                                                                                                                                                        ?>%">
                                                      <a href="#" style="color: #000;">Inactive</a>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col-md-12 pt-2">
                                              <div class="progress mb-3 w-100">
                                                  <div class="progress-bar" data-toggle="tooltip" data-title="553" role="progressbar" style="background: #d6eaff; color:#000; width:<?php //echo $onboard; 
                                                                                                                                                                                    ?>%">
                                                      <a href="#"  style="color: #000;">Onboarding</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div> -->

                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group has-primary">
                                                            <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable();">
                                                                <option value="%">All Depot</option>
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

                                                    <div class="col-md-4">
                                                        <div class="form-group has-primary">
                                                            <select class="select form-control custom-select" id="statusid" name="statusid" onchange="loadtable();">
                                                                <option value="%">All Status</option>
                                                                <option value="0">Active</option>
                                                                <option value="1">Inactive</option>
                                                                <option value="2">Onboarding</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group has-primary">
                                                            <select class="select form-control custom-select" id="vehicle" name="vehicle" onclick="loadtable();">
                                                                <option value="%">All Vehicles</option>
                                                                <option value="1">With assigned vehicle</option>
                                                                <option value="2">Without assigned vehicle</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" data-aire-component="input" placeholder="Search by Email" aria-label="Search by Email" name="emailsearch" id="emailsearch" data-aire-for="emailsearch" required>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-info" onclick="loadtable();">Filter</button>
                                                    </div>

                                                    <span class="custom-control custom-checkbox">
                                                        <label class="custom-control custom-checkbox m-b-0">
                                                            <input type="checkbox" class="custom-control-input" name="isvat" id="isvat" value="1" onchange="loadtable();">
                                                            <span class="custom-control-label">With VAT Number</span>
                                                        </label>
                                                    </span>

                                                    <span class="custom-control custom-checkbox">
                                                        <label class="custom-control custom-checkbox m-b-0">
                                                            <input type="checkbox" class="custom-control-input" name="istype" id="istype" value="1" onchange="loadtable();">
                                                            <span class="custom-control-label">Self-Employed</span>
                                                        </label>
                                                    </span>

                                                    <span class="custom-control custom-checkbox">
                                                        <label class="custom-control custom-checkbox m-b-0">
                                                            <input type="checkbox" class="custom-control-input" name="iscmp" id="iscmp" value="2" onchange="loadtable();">
                                                            <span class="custom-control-label">Company</span>
                                                        </label>
                                                    </span>

                                                    <!-- <span class="custom-control custom-checkbox">
                                                <label class="custom-control custom-checkbox m-b-0">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <span class="custom-control-label">Associate</span>
                                                </label>
                                            </span> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Company Name</th>
                                            <th>NINO</th>
                                            <th>UTR</th>
                                            <th>VAT</th>
                                            <th>Depot</th>
                                            <th>Arrears</th>
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


    </div>

    <?php
    include('footerScript.php');
    ?>
    <script type="text/javascript">
        //window.did = '%';
        // function getDid()
        // {
        // 	alert(window.did);
        // 	return window.did;
        // }
        // $('#myTable').DataTable({
        //         'processing': true,
        //         'serverSide': true,
        //         'serverMethod': 'post',
        //         'pageLength': 10,
        //          ajax: {
        //             url:'loadtabledata.php',
        //             data: {
        //             	did:getDid(),
        //                 action: 'loadContractortabledata'
        //             }
        //         },
        //         'columns': [
        //             { data: 'name' },
        //             { data: 'email' },
        //             { data: 'company_name' },
        //             { data: 'NINO' },
        //             { data: 'utr' },
        //             { data: 'depot_type' },
        //             { data: 'Arrears' },
        //             { data: 'date' },
        //             { data: 'Status' },
        //             { data: 'Action' }
        //         ]
        //     });


        $(document).ready(function() {
            $("#AddFormDiv,#AddDiv").hide();
            $('input:radio[name="type"]').change(
                function() {
                    $('#company_reg').val(" ");
                    if ($(this).val() == '2') {
                        $("#companydiv").css("display", "block");
                        //$("#company").val(0);
                    }
                    if ($(this).val() == '1') {
                        $('#company_reg').val(" ");
                        $("#companydiv").css("display", "none");
                    }
                });

            $("#company").change(function() {
                var companyid = $('#company').val();
                $.ajax({
                    type: "POST",
                    url: "loaddata.php",
                    data: {
                        action: 'Companydata',
                        companyid: companyid
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            $('#company_reg').val(data.company_reg);
                        }
                    }
                });
            });

            $('#myTable').DataTable({
                'processing': true,
                "serverSide": true,
                "destroy": true,
                'pageLength': 10,
                "ajax": {
                    "url": "loadtabledata.php",
                    "type": "POST",
                    "data": function(d) {
                        d.action = 'loadContractortabledata';
                        d.did = $('#depot_id').val();
                        d.statusid = $('#statusid').val();
                        d.emailsearch = $('#emailsearch').val();
                        d.vehicle = $('#vehicle').val();
                        d.vatid = $('#isvat').is(':checked');
                        d.istype = $('#istype').is(':checked');
                        d.iscmp = $('#iscmp').is(':checked');
                    }
                },
                'columns': [{
                        data: 'name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'company_name'
                    },
                    {
                        data: 'NINO'
                    },
                    {
                        data: 'utr'
                    },
                    {
                        data: 'vat_number'
                    },
                    {
                        data: 'depot_type'
                    },
                    {
                        data: 'Arrears'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'Status'
                    },
                    {
                        data: 'Action'
                    }
                ] /* <---- this setting reinitialize the table */
            });

            $("#ContractorForm").validate({
                rules: {
                    name: 'required',
                    email: 'required',
                    contact: 'required',
                    depot: 'required',
                    bank_name: 'required',
                    account_number: 'required',
                    sort_code: 'required',
                    address: 'required',
                    company_reg: 'required',
                    utr: 'required',
                },
                messages: {
                    name: "Please enter your name",
                    email: "Please enter your email",
                    contact: "Please enter your contact",
                    depot: "Please enter your depot",
                    bank_name: 'Please enter your bank name',
                    account_number: 'Please enter your account number',
                    sort_code: 'Please enter your sort code',
                    address: 'Please enter your address',
                    company_reg: 'Please enter your company register number',
                    utr: 'Please enter your UTR number',
                },
                submitHandler: function(form) {
                    event.preventDefault();
                    $.ajax({
                        url: "InsertData.php",
                        type: "POST",
                        dataType: "JSON",
                        data: $("#ContractorForm").serialize() + "&action=ContractorForm",
                        success: function(data) {
                            if (data.status == 1) {
                                myAlert(data.title + "@#@" + data.message + "@#@success");
                                $('#ContractorForm')[0].reset();
                                if (data.name == 'Update') {
                                    var table = $('#myTable').DataTable();
                                    table.ajax.reload();
                                    $("#AddFormDiv,#AddDiv").hide();
                                    $("#ViewFormDiv,#ViewDiv").show();
                                }
                            } else {
                                myAlert(data.title + "@#@" + data.message + "@#@danger");
                            }
                        }
                    });
                    // return false;
                }
            });
        });

        function loadtable() {
            // var isvat=0;
            // var istype=0;
            // var iscmp=0;
            // if($('#isvat').val()==1)
            // {
            //   isvat = 1;
            // }
            // if($('#istype').val()==1)
            // {
            //   istype = 1;
            // }
            // if($('#iscmp').val()==2)
            // {
            //   iscmp = 2;
            // }

            var table = $('#myTable').DataTable();
            table.ajax.reload();
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
                        window.location = '<?php echo $webroot ?>contractorDetails.php';
                    }
                }
            });
        }

        function resendurl(cid) {
            $.ajax({
                url: "loaddata.php",
                type: "POST",
                dataType: "JSON",
                data: {
                    action: 'ContractorURLSend',
                    cid: cid
                },
                success: function(data) {
                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
                    }
                }
            });
        }

        function resendonboardurl(cid) {
            $.ajax({
                url: "loaddata.php",
                type: "POST",
                dataType: "JSON",
                data: {
                    action: 'ContractorOnboardURLSend',
                    cid: cid
                },
                success: function(data) {
                    if (data.status == 1) {
                        myAlert(data.title + "@#@" + data.message + "@#@success");
                    } else {
                        myAlert(data.title + "@#@" + data.message + "@#@danger");
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
                    action: 'ContractorUpdateData',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    $result_data = data.statusdata;
                    $("input[name='type'][value='" + $result_data['type'] + "']").prop('checked', true);
                    $('#name').val($result_data['name']);
                    $('#email').val($result_data['email']);
                    $('#email').attr("disabled", "disabled");
                    $('#contact').val($result_data['contact']);
                    $('#contact').attr("disabled", "disabled");
                    $('#depot').val($result_data['depot']);
                    $('#bank_name').val($result_data['bank_name']);
                    $('#account_number').val($result_data['account_number']);
                    $('#sort_code').val($result_data['sort_code']);
                    $('#address').val($result_data['address']);
                    $('#company_reg').val($result_data['company_reg']);
                    $('#utr').val($result_data['utr']);
                    if ($result_data['type'] == 2) {
                        $("#companydiv").css("display", "block");
                        $('#company').val($result_data['company']);
                    } else {
                        $("#companydiv").css("display", "none");
                    }
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');
                }

            });
        }

        function deleterow(id) {
            var htm = "<h3>Are you sure, You want to delete this contractor???</h3>";
            swal({
                title: "Alert !",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Delete Now!",
                closeOnConfirm: false,
                html: htm,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "loaddata.php",
                        data: {
                            action: 'ContractorDeleteData',
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
            });
        }

        function ShowHideDiv(divValue) {
            $('#ContractorForm')[0].reset();
            if (divValue == 'view') {
                $("#submit").attr('name', 'insert');
                $("#submit").text('Submit');
                $("#AddFormDiv,#AddDiv").show();
                $("#ViewFormDiv,#ViewDiv").hide();
            }

            if (divValue == 'add') {
                var table = $('#myTable').DataTable();
                table.ajax.reload();
                $("#AddFormDiv,#AddDiv").hide();
                $("#ViewFormDiv,#ViewDiv").show();
            }
        }
    </script>
</body>

</html>