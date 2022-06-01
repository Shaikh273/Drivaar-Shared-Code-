<?php
if (!isset($_SESSION)) {
    session_start();
}
$page_id = '';
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
include('config.php');
$page_title="Raise Ticket";
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=1024">
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="dist/jq.dice-menu.min.css" />
    <style>

    @media only screen and (max-width: 600px){
  
      .dataTables_filter {
        float: left;
        margin-top: 10px;
        }
    }
    </style>
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
                    
                    <main class="container-fluid  animated">
                        <div class="card">   
                                <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="header" style="float: right;">
                                        <!-- <h4>Ticket</h4> -->
                                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Raise Ticket</button>
                    
                                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Raise Ticket</button>
                                    </div>
                                </div>                   
                            <?php
                            include 'DB/config.php';
                            $mysql = new Mysql();
                            $mysql -> dbConnect();
                            $sql = "SELECT * FROM `tbl_contractorinvoice` WHERE id=$id";
                            $fire = $mysql -> selectFreeRun($sql);
                            $fetch = mysqli_fetch_array($fire);
                            
                            ?>
                            <input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo $fetch['invoice_no'];?>">  
                           
                            <div class="col-md-12 mt-3" id="ViewFormDiv">
                                <div class="modal fade" id="editstatus" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content" style="width: 130%;">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Ticket Status</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body" id="modelBoby">
                                                <input type="hidden" name="ticketid" id="ticketid">
                                                  <div class="col-sm-12 table-responsive">
                                                    <div class="row mt-2">
                                                        <div class="col-md-12 escolet box" id="escolet_box">
                                                            <table id="myTable1" class="display dataTable table table table-striped table-bordered" role="grid" aria-describedby="example2_info">
                                                                <thead class="default">
                                                                    <tr role="row">
                                                                        <th>Commet</th>
                                                                        <th>Status</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="Ownerbody">
                                                                </tbody>
                                                            </table>    
                                                               
                                                        </div>
                                                    </div>
                                                  </div>
                                                <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive m-t-40" style="margin-top: 0px;">
                                            <table id="myTable" class="display dataTable table" role="grid" aria-describedby="example2_info" style="width: 100%;">
                                                <thead class="default">
                                                    <tr role="row">
                                                        <th>Issue</th>
                                                        <th>Department</th>
                                                        <th>Commit</th>
                                                        <th>Status</th>
                                                        <th>Expond</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <br>
                                </div>
                            </div>

                            <div class="" id="AddFormDiv">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card p-4 m-3 border">
                                            <form method="post" action="" id="addraiseticketForm" name="addraiseticketForm">
                                                <input type="hidden" name="cid" id="cid" value="<?php echo $_SESSION['cid'];?>">  
                                                <div class="form-row">
                                                  <div class="form-group col-md-6">
                                                        <label class="form-label">Issue</label>
                                                        <select class="custom-select custom-select-md mb-2" name="issue" id="issue">
                                                              <option>--</option>
                                                              <?php
                                                                $sql1 = "SELECT * FROM `tbl_issuetype` WHERE `isdelete`=0";
                                                                $fire1 = $mysql -> selectFreeRun($sql1);
                                                                while($fetch1 = mysqli_fetch_array($fire1)){
                                                              ?> 
                                                              <option value="<?php echo $fetch1['id'];?>"><?php echo $fetch1['issuetype'];?></option>
                                                              <?php
                                                                }
                                                              ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Department</label>
                                                        <input type="text" class="form-control" id="department" name="department" placeholder="" value="" disabled>
                                                    </div>
                                                </div>
                                                  
                                                   <div class="form-group">
                                                    <label class="form-label">Commit</label>
                                                    <textarea type="text" class="form-control" id="commit" name="commit" placeholder="" rows="2"></textarea>
                                                  </div>
                                                  <div class="form-group mt-2">
                                                      <button type="" class="btn btn-primary update" id="submit" name="update">OK</button>
                                                  </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>        
                    </main>
                    
                    
                   
                </div>
            </div>
                    
                    
                    
            <?php include('footer.php');?>         
        </div>
            
       
<script src="dist/jq.dice-menu.js"></script>

<?php include('footerScript.php'); ?>

<script>

$( "#issue" ).change(function() {
  var val = $(this).val();
  $.ajax({

        type: "POST",
        url: "loaddata.php",
        data: {
            'action' : 'adddepartmentinputfied', 
            'issuetype_id': val
        },

        dataType: 'json',

        success: function(data) {
            
            if(data.status==1)
            {
                $('#department').val(data.issuetypeId);
            }
            else
            {
                myAlert(data.title + "@#@" + data.message + "@#@danger");
            }
            
        }

    });
});

$(document).ready(function(){
    
var invoice_no = $('#invoice_no').val();

if(invoice_no != "")
{
    $('#ViewFormDiv').hide();
    $('#ViewDiv').hide();
    $('#AddDiv').hide();
    $("#AddFormDiv,#AddDiv").show();
    

    var d = $('#issue').val(1);
    var issue1  = $('#issue').val();
    d.prop('disabled', 'disabled');
     
    var department = $('#department').val("Finance Department");
    department.prop('disabled', 'disabled');

    var department1 = $('#department').val();

    $("#submit").attr('type', 'button');

    $('#submit').click(function() {
        var commit = $('textarea#commit').val();

        $.ajax({

            type: "POST",

            url: "InsertData.php",

            data: {
                action : 'UpdateAndInsertcontractorinvoicestatus', 
                invoice_no: invoice_no,
                issue:issue1,
                department:department1,
                commit:commit
            },

            dataType: 'json',

            success: function(response) {
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
    });

    $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadraisetickettabledata'
                }
            },
            'columns': [
                { data: 'issue' },
                { data: 'department' },
                { data: 'commit' },
                { data: 'status' },
                { data: 'expond' }
            ]
        });
}
else
{
    $("#submit").attr('type', 'submit');
    
        $("#AddFormDiv,#AddDiv").hide();


        $('#myTable').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url':'loadtabledata.php',
                'data': {
                    'action': 'loadraisetickettabledata'
                }
            },
            'columns': [
                { data: 'issue' },
                { data: 'department' },
                { data: 'commit' },
                { data: 'status' },
                { data: 'expond' }
            ]
        });

        $("#addraiseticketForm").validate({

            rules: {
                issue: 'required',
                department: 'required',
                commit: 'required'

            },

            messages: {
                issue:"Please Select Problem",
                department: "Please Enter department",
                commit:"Please Enter a Issue"
               
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#addraiseticketForm").serialize()+"&action=addraiseticketForm",
                    cache: false,             
                    processData: false,      
                    success: function(data) {
                        if(data.status==1)
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                        }
                        else
                        {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
            }
        });
}

});


var id = "";

function editstatus(id)
{

  id = id;
  $('#editstatus').modal('show');
  // table.ajax.reload();
 
   var table = $.ajax({
     url: 'loaddata.php', 
     type: 'post',
     data: {
        'action':'loadticketstatustabledata',
         ticket_id:id
     },
     dataType: 'json',
     success: function (response) {
        if(response.status==1)
        {
            $('#Ownerbody').html(response.tbldata);
        }
        else
        {
            $('#Ownerbody').html(response.tbldata);
            // myAlert(response.title + "@#@" + response.message + "@#@danger");
        }
    }
});

}

function ShowHideDiv(divValue)
{
        $('#addraiseticketForm')[0].reset(); 

        var invoice_no = $('#invoice_no').val();

        if(invoice_no != "")
        {
            $("#AddFormDiv,#AddDiv").show();
            $("#ViewFormDiv,#ViewDiv").hide();  
        }

        if(divValue == 'view')
        {

            $("#submit").attr('name', 'insert');
            $("#submit").text('Submit');
            $("#AddFormDiv,#AddDiv").show();
            $("#ViewFormDiv,#ViewDiv").hide(); 
            var invoiceno = $('#invoice_no').val();

            if(invoiceno != "")
            {
                $('#department').val("Finance Department");
                var d = $('#issue').val(1);
                d.prop('disabled', 'disabled'); 
                var department = $('#department').val("Finance Department");
                department.prop('disabled', 'disabled');

            }    

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