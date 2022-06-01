<?php
include 'DB/config.php';

$page_title = "Traning";

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

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta name="viewport" content="width=1024">

<title><?php echo $page_title; ?></title>

<?php include('head.php'); ?>

<link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">

<link rel="stylesheet" href="countrycode/build/css/demo.css">


<style type="text/css">
    .titlehead {
          font-size: 28px;
          font-weight: 500;
    }
</style>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php');?>

<div id="main-wrapper">

<?php

include('header.php');

?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-md-12">
                <div class="card">   
                    <div class="card-header" style="background-color: rgb(255 236 230);">
                                    <div class="d-flex justify-content-between align-items-center">
                                    <div class="header">Tranings</div>

                                     <div> 
                                        <button type="button" class="btn btn-primary" id="ViewDiv" onclick="ShowHideDiv(this.value);" value="view">Add Traings</button>

                                        <button type="button" class="btn btn-primary" id="AddDiv" onclick="ShowHideDiv(this.value);" value="add">View Tranings</button>
                                     </div>                   

                                    </div>
                    </div>
                    <div class="card-body" id="AddFormDiv">
                        <form method="post" action="" name="trainingform" id="trainingform">
                            
                            <div class="form-group" data-aire-component="group" data-aire-for="name">
                                <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3"> Name *</label>

                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="form-group">
                                <label class=" cursor-pointer">Refreshment period:</label><br>
                                <select class="form-select form-select-lg mb-3 form-control" aria-label=".form-select-lg example" id="refreshment" name="refreshment">
                                    <option value="1-year">1 Year</option>
                                    <option value="9-month">9 Months</option>
                                    <option value="6-month">6 Months</option>
                                    <option value="3-month">3 Months</option>
                                    <option value="1-month">1 Months</option>
                                </select> 
                            </div>   
                            <button type="" name="" class="btn btn-success" id="submit">Submit</button>
                            <a href="javascript:window.top.location.reload(true)" type="" name="" class="btn btn-default border ml-2" value="cancel">Cancel</a>
                        </form>    
                    </div>


                    <div class="card-body" id="ViewFormDiv">
                        <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="font-weight: 400;">
                            <thead class="default">
                                <tr>
                                <th>Name</th>
                                <th>Refreshment Period</th>
                                <th>Next Training</th>
                                <th># sessions</th>
                                <th data-orderable="false">Action</th>
                                </tr>
                            </thead>
                                           
                        </table>
                                  
                    </div>

                </div>
                                    
            </div>
        </div>
    </div>
</div>
   <!--  </div>
  </div> -->
</div>
<?php include('footer.php');?>
</div>

<?php include('footerScript.php');?>
<script>
    
$(document).ready(function(){
    $("#AddFormDiv,#AddDiv").hide();

    $('#myTable').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'loadtabledata1.php',
            'data': {
                'action': 'loadtrainingtabledata',
            }
        },
        'columns': [
            { data: 'name' },
            { data: 'refreshment' },
            { data: 'next_traing' },
            { data: 'session' },
            { data: 'action' }
        ]
    });

    $("#trainingform").validate({

            rules: {

                name: 'required',

            },
            messages: {
                name: "Please enter your name",
            },
            submitHandler: function(form) {
                event.preventDefault();
                $.ajax({
                    url: "InsertData1.php", 
                    type: "POST", 
                    dataType:"JSON",            
                    data: $("#trainingform").serialize()+"&action=trainingForm",
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


});

function edit(id)
{

        $('#id').val(id);

        ShowHideDiv('view');

        $.ajax({

            type: "POST",

            url: "loaddata1.php",

            data: {action : 'TrainingUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#name').val($result_data['name']);
                $('#refreshment').val($result_data['refreshment']);
                $("#submit").attr('name', 'update');
                $("#submit").text('Update');

            }

        });
    }

function deletetraing(id)
{

    $.ajax({

        type: "POST",

        url: "loaddata1.php",

        data: {action : 'traningDeleteData', id: id},

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
        // $('#offencesForm')[0].reset(); 
        if(divValue == 'view')
        {
            $("#submit").attr('name', 'insert');
            $("#submit").text('Submit');
            $("#AddFormDiv,#AddDiv").show();
            $("#ViewFormDiv,#ViewDiv").hide();     
        }else if(divValue == 'add')
        {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
            $("#AddFormDiv,#AddDiv").hide();
            $("#ViewFormDiv,#ViewDiv").show();     
        }
        // else if(divValue == 'cancel')
        // {
        //     $("#AddFormDiv,#AddDiv").show();
        //     $("#ViewFormDiv,#ViewDiv").hide();        
        // }
    }
</script>

</body>
</html>