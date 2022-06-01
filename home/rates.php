
<?php
 $page_title="Rate";
 include 'DB/config.php';
 $page_id=103;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $userid = $_SESSION['userid'];
        if($userid==1)
        {
          $uid='%';
        }
        else
        {
          $uid=$userid;
        }
        
        if(isset($_POST['insert']))
        {

            $valus[0]['name'] = $_POST['name'];

            $valus[0]['blockoftime'] = $_POST['blockoftime'];

            $valus[0]['vat']=$_POST['vat'];
            
            $valus[0]['amount']=$_POST['amount'];
            
            $valus[0]['profitprice']=$_POST['profitprice'];
            
            $valus[0]['account_id']=$_POST['account'];
            
            $valus[0]['type']=$_POST['type'];
            
            $valus[0]['paymentperstop']=$_POST['paymentperstop'];
            
            $valus[0]['granular']=$_POST['granular'];
            
            $valus[0]['applies']=$_POST['applies'];
            
            $valus[0]['routerate']=$_POST['routerate'];
            
            $valus[0]['isactive']=$_POST['status'];

            if($_POST['depot'])
            {
                $valus[0]['depot_id']= implode(",", $_POST['depot']);
            }
            else
            {
                $valus[0]['depot_id']=0;
            }
            
            $valus[0]['insert_date'] = date('Y-m-d H:i:s');

            $mysql = new Mysql();

            $mysql -> dbConnect();

            $paymenttypeinsert = $mysql -> insert('tbl_paymenttype',$valus);

            if($paymenttypeinsert)
            {
                echo "<script>myAlert('Insert @#@ Data has been inserted successfully.@#@success')</script>";
            }
            else
            {
                echo "<script>myAlert('Insert Error @#@ Data can not been inserted.@#@danger')</script>";
            }

            $mysql -> dbDisConnect();

         }

         if(isset($_POST['update']))
         {
            $valus[0]['name'] = $_POST['name'];

            $valus[0]['blockoftime'] = $_POST['blockoftime'];

            $valus[0]['vat']=$_POST['vat'];
            
            $valus[0]['amount']=$_POST['amount'];
            
            $valus[0]['profitprice']=$_POST['profitprice'];
            
            $valus[0]['account_id']=$_POST['account'];
            
            $valus[0]['type']=$_POST['type'];
            
            $valus[0]['paymentperstop']=$_POST['paymentperstop'];
            
            $valus[0]['granular']=$_POST['granular'];
            
            $valus[0]['applies']=$_POST['applies'];
            
            $valus[0]['routerate']=$_POST['routerate'];
            
            $valus[0]['isactive']=$_POST['status'];
            
             if($_POST['depot'])
            {
                $valus[0]['depot_id']= implode(",", $_POST['depot']);
            }
            else
            {
                $valus[0]['depot_id']=0;
            }
            
            $valus[0]['update_date'] = date('Y-m-d H:i:s');

            $paymentcol = array('name','blockoftime','vat','amount','profitprice','account_id','type','paymentperstop','granular','applies','routerate','isactive','depot_id','update_date');

            $where = 'id ='. $_POST['id'];

            $mysql = new Mysql();

            $mysql -> dbConnect();

            $paymentupdate = $mysql -> update('tbl_paymenttype',$valus,$paymentcol,'update',$where);

            if($paymentupdate)
            {
                echo "<script>myAlert('Update @#@ Data has been updated successfully.@#@success')</script>";
            }
            else
            {
                echo "<script>myAlert('Update Error @#@ Data can not been updated.@#@danger')</script>";
            }

            $mysql -> dbDisConnect();
         }
    }
    else
    {
        header("location: login.php");  
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
                            <div class="header">Payment Type</div>
                             <div> 
                                <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Paymenttype</button>
                                <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Paymenttype</button>
                             </div>                   
                        </div>
                    </div>
                    <div class="card-body" id="AddFormDiv">
                        <div class="row">
                          <div class="col-md-12">
                              <form method="post" id="PaymenttypeForm" name="PaymenttypeForm" action="">
                                  <input type="hidden" name="id" id="id" value="">  
                                     <div class="form-body">
                                        <div class="row p-t-20">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Name *</label>
                                                    <input type="text" id="name" name="name" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Block of Time*</label>
                                                    <input type="text" id="blockoftime" name="blockoftime" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Amount *</label>
                                                    <input type="text" id="amount" name="amount" class="form-control" placeholder="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label class="control-label">VAT *</label>

                                                    <input type="text" id="vat" name="vat" class="form-control" placeholder="">

                                                </div>

                                            </div>
                                             <div class="col-md-4">

                                                <div class="form-group">

                                                    <label class="control-label">Profit Price </label>

                                                    <input type="text" id="profitprice" name="profitprice" class="form-control" value="0"placeholder="">
 
                                                </div>

                                            </div>
                                            <div class="col-md-4">

                                                    <div class="form-group">
        
                                                        <label class="control-label">Account</label>
        
                                                        <select class="select form-control custom-select" id="account" name="account">
                                                            <option value="0">--</option>
                                                        </select> 
        
                                                    </div>

                                                </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label class="control-label">Type *</label>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="standard" name="type" class="custom-control-input" value="1" checked="checked">

                                                        <label class="custom-control-label" for="standard">Standard Services</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="extra" name="type" class="custom-control-input" value="2">

                                                        <label class="custom-control-label" for="extra">Extra Services</label>

                                                    </div>
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="deduction" name="type" class="custom-control-input" value="3">

                                                        <label class="custom-control-label" for="deduction">Deduction</label>

                                                    </div>

                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                
                                                <div class="form-group">

                                                    <label class="control-label">Payment per stop: *</label>
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="flat" name="paymentperstop" class="custom-control-input" value="1" checked="checked">

                                                        <label class="custom-control-label" for="flat">Flat</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="quantity" name="paymentperstop" value="2" class="custom-control-input">

                                                        <label class="custom-control-label" for="quantity">Quantity</label>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-4">
                                                
                                                <div class="form-group">

                                                    <label class="control-label">Granular: *</label>
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="granularyes" name="granular" value="1" class="custom-control-input">

                                                        <label class="custom-control-label" for="granularyes">Yes</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="granularno" name="granular" value="2" class="custom-control-input" checked="checked">

                                                        <label class="custom-control-label" for="granularno">No</label>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div><hr><br>
                                        <div class="row">
                                            <div class="col-md-4">

                                                <div class="form-group">

                                                    <label class="control-label">Applies to *</label>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="drivers" name="applies" value="1" class="custom-control-input" checked="checked">

                                                        <label class="custom-control-label" for="drivers">Drivers</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="workforce" name="applies" value="2" class="custom-control-input">

                                                        <label class="custom-control-label" for="workforce">Workforce</label>

                                                    </div>
                                                    
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="custom" name="applies" value="3" class="custom-control-input">

                                                        <label class="custom-control-label" for="custom">Custom</label>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                
                                                <div class="form-group">

                                                    <label class="control-label">Status *</label>
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="active" name="status" value="0" class="custom-control-input" checked="checked">

                                                        <label class="custom-control-label" for="active">Active</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="inactive" name="status" value="1" class="custom-control-input">

                                                        <label class="custom-control-label" for="inactive">Inactive</label>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-4">
                                                
                                                <div class="form-group">

                                                    <label class="control-label">Route Rate *</label>
                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="yes" name="routerate" value="1" class="custom-control-input">

                                                        <label class="custom-control-label" for="yes">Yes</label>

                                                    </div>

                                                    <div class="custom-control custom-radio">

                                                        <input type="radio" id="no" name="routerate" value="2" class="custom-control-input" checked="checked">

                                                        <label class="custom-control-label" for="no">No</label>

                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                         <div class="row">
                                             <div class="col-md-12">
                                                 <div class="form-group">
                                                    <label class="control-label">In Depot</label>
                                                     
                                                    <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" name="depot[]" id="depot" value="" Placeholder="Select Depot">

                                                    <?php
                                                      $mysql = new Mysql();
                                                      $mysql -> dbConnect();
                                                      $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                        INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                        WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '".$uid."'";
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
                                                     <small>Select depots where the payment type will be available in.</small>
                                                 </div>
                                             </div>
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
                            <div class="row">
                                <div class="col-md-3">
                                   <div class="form-group has-primary">
                                           <select class="select form-control custom-select" name="istype" id="istype" onchange="loadtable();">
                                                <option value="%">All Types</option>
                                                <option value="1">Standard Sevice</option>
                                                <option value="2">Extra service</option>
                                                <option value="3">Deduction</option>
                                           </select>  
                                    </div>
                                </div>
                        
                                <div class="col-md-4">
                                    <select class="select form-control custom-select" name="isapplies" id="isapplies" onchange="loadtable();">
                                        <option value="%">Applies To All</option>
                                        <option value="1">Drivers</option>
                                        <option value="2">Workforce</option>
                                        <option value="3">Custom</option>
                                    </select>
                                </div>
                        
                                <div class="col-md-2">
                                     <button type="button" class="btn btn-info"  onclick="loadtable();">serach</button>
                                     <button type="button" class="btn btn-info" onclick="cleardta();">Clear</button>
                                </div>
                            </div><hr><br>

                            <div class="table-responsive">
                                    <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info">
                                        <thead class="default">
                                            <tr role="row">
                                               <th>Name</th>
                                                <th data-orderable="false">Applies to</th>
                                                <th>Price</th>
                                                <th>VAT</th>
                                                <th data-orderable="false">Depots</th>
                                                <th data-orderable="false">Date</th>
                                                <th data-orderable="false">Status</th>
                                                <th data-orderable="false"></th>
                                            </tr>
                                        </thead>
                                    </table>
                            </div>  
                    </div>
                 </div>        
            </div>
    </main>
</div>

<?php
    include('footer.php');
?>
</div>

<?php
include('footerScript.php');
?>
 <script type="text/javascript">

    $(document).ready(function(){
        $("#AddFormDiv,#AddDiv").hide();

        $('#myTable').DataTable({
                'processing': true,
                "serverSide": true,
                "destroy" : true,
                'pageLength': 10,
                "ajax":
                {
                  "url": "loadtabledata.php",
                  "type": "POST",
                  "data": function(d) 
                  {
                        d.action = 'loadpaymenttabledata';
                        d.istype = $('#istype').val();
                        d.isapplies = $('#isapplies').val();
                  }
                },
                'columns': [
                        { data: 'Name' },
                        { data: 'Applies to' },
                        { data: 'amount' },
                        { data: 'vat' },
                        { data: 'Depots' },
                        { data: 'Date' },
                        { data: 'Status' },
                        { data: 'Action' }
                ]  /* <---- this setting reinitialize the table */
        });

        $("#PaymenttypeForm").validate({
            rules: {
                name: 'required',
                amount: 'required',
                vat: 'required',
            },
            messages: {
                name: "Please enter your name",
                amount: "Please enter your amount",
                vat: "Please enter your vat",
            },
        });
    });
 

    function loadtable()
    {
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }

    function cleardta()
    {
        $('#istype').val('%').attr("selected","selected");
        $('#isapplies').val('%').attr("selected","selected");
        loadtable();
    }

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

    function edit(id)
    {
        $('#id').val(id);
        ShowHideDiv('view');
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'PaymenttypeUpdateData', id: id},
            dataType: 'json',
            success: function(data) {
                $result_data = data.paymentdata;
                $('#name').val($result_data['name']);
                $('#blockoftime').val($result_data['blockoftime']);
                $('#vat').val($result_data['vat']);
                $('#amount').val($result_data['amount']);
                $('#profitprice').val($result_data['profitprice']);
                $('#account').val($result_data['account']);
                $('#depot').val($result_data['depot'].split(",")).change();
                $("input[name=type][value=" + $result_data['type'] + "]").prop('checked', true);
                $("input[name=paymentperstop][value=" + $result_data['paymentperstop'] + "]").prop('checked', true);
                $("input[name=granular][value=" + $result_data['granular'] + "]").prop('checked', true);
                $("input[name=applies][value=" + $result_data['applies'] + "]").prop('checked', true);
                $("input[name=routerate][value=" + $result_data['routerate'] + "]").prop('checked', true);
                $("input[name=status][value=" + $result_data['isactive'] + "]").prop('checked', true);
                $("#submit").attr('name', 'update');
                $("#submit").text('Update');
            }

        });
    }

    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'PaymenttypeDeleteData', id: id},

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

        if(divValue == 'view')

        {

            $('#PaymenttypeForm')[0].reset(); 

            $("#AddFormDiv,#AddDiv").show();

            $("#ViewFormDiv,#ViewDiv").hide();     

        }

        if(divValue == 'add')

        {

            $("#AddFormDiv,#AddDiv").hide();

            $("#ViewFormDiv,#ViewDiv").show();     

        }
    }
    </script>
</body>

</html>