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
//include('authentication.php');
include('config.php');
$page_title="Raise Ticket";
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
                        <div class="col-md-6 container">
                            <div class="card p-3 pt-5 mt-5">
                              <form method="post" action="" id="addraiseticketForm" name="addraiseticketForm">
                                  <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Problem</label>
                                    <div class="col-sm-10">
                                        <select class="custom-select custom-select-md mb-2" name="problem" id="problem">
                                          <option selected>--</option>
                                          <option value="1">Invoice</option>
                                          <option value="0">Leave Request</option>
                                        </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Department</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="department" name="department" placeholder="" value="" disabled>
                                    </div>
                                  </div>
                                   <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Issue</label>
                                    <div class="col-sm-10">
                                      <textarea type="text" class="form-control" id="issue" name="issue" placeholder="" rows="2"></textarea>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-sm-10">
                                      <button type="submit" class="btn btn-primary">OK</button>
                                    </div>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    
                   
                </div>
            </div>
                    
                    
                    
            <?php include('footer.php');?>         
        </div>
            
       
<script src="dist/jq.dice-menu.js"></script>

<?php include('footerScript.php'); ?>

<script>

$( "#problem" ).change(function() {
  var val = $(this).val();
  if(val == 1){
    $('#department').val("Finance Department");
  }else{
    $('#department').val("Admin Department");  
  }
});

$("#addraiseticketForm").validate({

    rules: {

        department: 'required',
        problem: 'required',
        issue: 'required'

    },

    messages: {
        department: "Please Enter department",
        problem:"Please Select Problem",
        issue:"Please Enter a Issue"
       
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

</script>

</body>

</html>