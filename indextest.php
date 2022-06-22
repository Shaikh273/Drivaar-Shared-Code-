<?php
$page_title = "Drivaar";
include 'DB/config.php';
$page_id = 40;
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
    if (isset($_POST['insert'])) {
        $mysql = new Mysql();
        $mysql->dbConnect();
        $valus[0]['name'] = $_POST['name'];
        $valus[0]['userid'] = $_POST['userid'];
        // $valus[0]['invoicetype'] = $_POST['invoicetype'];
        // $supervisorIds = implode(",", $_POST['supervisor']);
        // $valus[0]['supervisor'] = $supervisorIds;
        // $cat_result= array();
        // $i = 0;

        // foreach ($_POST['supervisor'] as $supervisorid) {

        //     $category =  $mysql -> selectWhere('tbl_user','id','=',$supervisorid,'int');

        //     $catresult = mysqli_fetch_array($category);

        //     $cat_result[] = $catresult['name'];

        //     $i++;
        // }

        // $valus[0]['supervisor_type'] = implode(",", $cat_result);
        $valus[0]['insert_date'] = date('Y-m-d H:i:s');

        $depotinsert = $mysql->insertre('tbl_depot', $valus);


        if ($depotinsert) {
            $dvalus[0]['userid'] = 1;
            $dvalus[0]['wid'] = 1;
            $dvalus[0]['depot_id'] = $depotinsert;
            $dvalus[0]['uniqid'] = '1-' . $depotinsert;
            $dvalus[0]['assign_date'] = date('Y-m-d H:i:s');
            $dvalus[0]['insert_date'] = date('Y-m-d H:i:s');

            $insert = $mysql->insert('tbl_workforcedepotassign', $dvalus);

            echo "<script>myAlert('Insert @#@ Data has been inserted successfully.@#@success')</script>";
        } else {
            echo "<script>myAlert('Insert Error @#@ Data can not been inserted.@#@danger')</script>";
        }

        $mysql->dbDisConnect();
    }

    if (isset($_POST['update'])) {
        $mysql = new Mysql();
        $mysql->dbConnect();

        $valus[0]['name'] = $_POST['name'];
        // $valus[0]['invoicetype'] = $_POST['invoicetype'];
        // $supervisorIds = implode(",", $_POST['supervisor']);
        // $valus[0]['supervisor'] = $supervisorIds;

        // $cat_result= array();
        // $i = 0;

        // foreach ($_POST['supervisor'] as $supervisorid) {

        //     $category =  $mysql -> selectWhere('tbl_user','id','=',$supervisorid,'int');

        //     $catresult = mysqli_fetch_array($category);

        //     $cat_result[] = $catresult['name'];

        //     $i++;

        // }

        // $valus[0]['supervisor_type'] = implode(",", $cat_result);
        $valus[0]['update_date'] = date('Y-m-d H:i:s');

        $usercol = array('name', 'update_date'); //'invoicetype'
        $where = 'id =' . $_POST['id'];
        $userupdate = $mysql->update('tbl_depot', $valus, $usercol, 'update', $where);

        if ($userupdate) {
            echo "<script>myAlert('Update @#@ Data has been updated successfully.@#@success')</script>";
        } else {
            echo "<script>myAlert('Update Error @#@ Data can not been updated.@#@danger')</script>";
        }

        $mysql->dbDisConnect();
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
        // include('menu.php');
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Depots</div>
                                <div>

                                    <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Depot</button>

                                    <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Depot</button>

                                </div>

                            </div>
                        </div>
                        <div class="card-body" id="AddFormDiv">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" id="depotForm" name="depotForm" action="">
                                        <input type="hidden" name="id" id="id" value="">
                                        <input type="hidden" name="userid" id="userid" value="<?php echo $userid ?>">
                                        <div class="form-body">
                                            <div class="row p-t-20">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Name *</label>
                                                        <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-6">
                                            <label class="control-label">Create Invoice *</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="weekly" name="invoicetype" class="custom-control-input" value="1" checked>
                                                <label class="custom-control-label" for="weekly">Weekly</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="monthly" name="invoicetype" class="custom-control-input" value="2">
                                                <label class="custom-control-label" for="monthly">Monthly</label>
                                            </div>
                                        </div> -->

                                                <!--  <div class="col-md-4">

                                            <div class="form-group">
                                                <label class="control-label">Supervisor(s) *</label>
                                                <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" name="supervisor[]" id="supervisor"  Placeholder="Select Supervisor">
                                                    <?php
                                                    //$mysql = new Mysql();
                                                    //$mysql -> dbConnect();
                                                    // $statusquery = "SELECT * FROM `tbl_user` WHERE  `roleid` IN (10) AND `isdelete`=0 AND `isactive`=0";
                                                    // $strow =  $mysql -> selectFreeRun($statusquery);
                                                    //while($statusresult = mysqli_fetch_array($strow))
                                                    {
                                                    ?>
                                                     //   <option value="<?php  // echo $statusresult['id']
                                                                            ?>"><?php //echo $statusresult['//name']
                                                                                ?></option>
                                                      <?php
                                                    }
                                                    // $mysql -> dbDisconnect();
                                                        ?>
                                                </select> 
                                            </div>

                                        </div> -->
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <button type="submit" name="insert" class="btn btn-success" id="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="ViewFormDiv">
                            <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                <table id="myTable" class="table table-responsive tabelvendor">
                                    <thead class="default">
                                        <tr>
                                            <th>Name</th>
                                            <th>Invoice Type</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $mysql = new Mysql();
                                        $mysql->dbConnect();
                                        $query = "SELECT *, DATE_FORMAT(`insert_date`,'%D %M%, %Y') as date FROM `tbl_depot` WHERE `isdelete`= 0";
                                        $rolerow =  $mysql->selectFreeRun($query);
                                        while ($roleresult = mysqli_fetch_array($rolerow)) {
                                            // $invoicetype='';
                                            // if($roleresult['invoicetype']==1)
                                            // {
                                            //     $invoicetype = 'Weekly';
                                            // }
                                            // else if($roleresult['invoicetype']==2)
                                            // {
                                            //     $invoicetype = 'Monthly';
                                            // }
                                        ?>
                                            <tr>
                                                <td style="color: blue;">
                                                    <span onclick="loadpage(<?php echo $roleresult['id']; ?>)"><?php echo $roleresult['name'] ?><br>
                                                        <small><?php echo $roleresult['supervisor_type']; ?></small>
                                                    </span>
                                                </td>
                                                <td><?php //echo $invoicetype;
                                                    ?></td>
                                                <td><?php echo $roleresult['date']; ?></td>
                                                <td>
                                                    <button type="button" data-toggle='tooltip' title='Add Customer' class="btn btn-success" onclick="addcustomer(<?php echo $roleresult['id']; ?>);">Add Customer</button>
                                                </td>
                                                <td id="<?php echo $roleresult['id']; ?>-td"><?php
                                                                                                if ($roleresult['isactive'] == 0) {
                                                                                                ?>
                                                        <button type="button" class="btn btn-success isactivebtn" onclick="Isactivebtn(<?php echo $roleresult['id']; ?>,<?php echo $roleresult['isactive']; ?>,'Depotisactive');">Active</button>
                                                </td>
                                            <?php
                                                                                                } else {
                                            ?>
                                                <button type="button" class="btn btn-danger isactivebtn" onclick="Isactivebtn(<?php echo $roleresult['id']; ?>,<?php echo $roleresult['isactive']; ?>,'Depotisactive');">Inactive</button></td>
                                            <?php
                                                                                                }
                                            ?>
                                            <td>

                                                <a href="#" class="edit" onclick="edit(<?php echo $roleresult['id']; ?>);"><span><i class="fas fa-edit fa-lg"></i></span></a>

                                                <a href="#" class="delete" onclick="deleterow(<?php echo $roleresult['id']; ?>);"><span><i class="fas fa-trash-alt fa-lg"></i></span></a>



                                            </td>
                                            </tr>
                                        <?php
                                        }
                                        $mysql->dbDisconnect();
                                        ?>
                                    </tbody>

                                </table>
                                <br>

                            </div>
                        </div>
                    </div>
                </main>

                <div id="addcustomer" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color: rgb(255 236 230);">
                                <h4 class="modal-title">Add Customer</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Ã—</button>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="addcustomerForm" name="addcustomerForm" action="">
                                    <input type="hidden" name="depot_id" id="depot_id">
                                    <div class="form-group">
                                        <label class="control-label">Customer *</label>
                                        <input type="text" name="customer" id="customer" class="form-control" placeholder="" required="required">
                                    </div>
                                </form>
                                <br>
                                <table id="Ownertbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                                    <thead class="default">
                                        <tr role="row">
                                            <th width="500">Customer</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="customerbody">
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="AddCustomberDatatbl()" class="btn btn-success waves-effect waves-light">Save changes</button>
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
        function loadpage(did) {
            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'DepotSetSessionData',
                    did: did
                },

                dataType: 'json',

                success: function(data) {
                    if (data.status == 1) {
                        window.location = '<?php echo $webroot ?>depot_detail.php';
                    }

                }

            });

        }

        function addcustomer(did) {
            $('#addcustomer').modal('show');
            $('#depot_id').val(did);
            event.preventDefault();
            showCustomber(did);
        }

        function AddCustomberDatatbl() {
            var did = $('#depot_id').val();
            var customer = $('#customer').val();
            event.preventDefault();
            if (did && customer) {
                $.ajax({
                    type: "POST",
                    url: "InsertData.php",
                    data: {
                        action: 'AddWorkforceDepotCustomber',
                        did: did,
                        customer: customer
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == 1) {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                            $('#addcustomerForm')[0].reset();
                            showCustomber(did);
                        } else {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                        $('#addcustomer').modal('hide');
                    }
                });
            } else {
                myAlert("Error @#@ Please Enter Your Customer.@#@danger");
            }

        }

        function showCustomber(id) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'ShowWorkforceDepotCustomber',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        $('#customerbody').html(data.tbldata);
                    } else {
                        $('#customerbody').html(data.tbldata);
                    }
                }
            });
        }

        function deleteworkforcedepotcustomerrow(id, depotid) {
            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'deleteworkforcedepotcustomer',
                    id: id
                },

                dataType: 'json',

                success: function(data) {
                    if (data.status == 1) {
                        myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                        $('#addcustomer').modal('hide');
                        showvehicle(depotid);

                    } else {
                        myAlert("Delete @#@ Data can not been deleted.@#@danger");
                    }

                }

            });
        }

        $(document).ready(function() {
            $("#AddFormDiv,#AddDiv").hide();

            $("#depotForm").validate({

                rules: {
                    name: 'required',
                },

                messages: {

                    name: "Please enter your name",

                },

            });

        });

        $(function() {

            $('#myTable').DataTable();

            $(function() {

                var table = $('#example').DataTable({

                    "columnDefs": [{

                        "visible": false,

                        "targets": 2

                    }],

                    "order": [

                        [2, 'asc']

                    ],

                    "displayLength": 25,

                    "drawCallback": function(settings) {

                        var api = this.api();

                        var rows = api.rows({

                            page: 'current'

                        }).nodes();

                        var last = null;

                        api.column(2, {

                            page: 'current'

                        }).data().each(function(group, i) {

                            if (last !== group) {

                                $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');

                                last = group;

                            }

                        });

                    }

                });

            });
        });

        function edit(id) {

            $('#id').val(id);

            ShowHideDiv('view');

            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'DepotUpdateData',
                    id: id
                },

                dataType: 'json',

                success: function(data) {

                    $result_data = data.userdata;
                    $('#name').val($result_data['name']);
                    // $("input[name='invoicetype'][value='"+$result_data['invoicetype']+"']").prop('checked', true);
                    $("#submit").attr('name', 'update');
                    $("#submit").text('Update');

                }

            });
        }

        function deleterow(id) {

            $.ajax({

                type: "POST",

                url: "loaddata.php",

                data: {
                    action: 'DepotDeleteData',
                    id: id
                },

                dataType: 'json',

                success: function(data) {
                    if (data.status == 1) {
                        window.location.reload();
                        myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                    } else {
                        myAlert("Delete @#@ Data can not been deleted.@#@danger");
                    }
                }

            });
        }

        function ShowHideDiv(divValue) {

            if (divValue == 'view')

            {

                $('#depotForm')[0].reset();

                $("#AddFormDiv,#AddDiv").show();

                $("#ViewFormDiv,#ViewDiv").hide();

            }

            if (divValue == 'add')

            {

                $("#AddFormDiv,#AddDiv").hide();

                $("#ViewFormDiv,#ViewDiv").show();

            }
        }
    </script>
</body>

</html>