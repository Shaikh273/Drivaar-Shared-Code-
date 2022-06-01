<?php
include 'DB/config.php';
    $page_title = "Workforce Edit";
    $page_id=37;
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
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>
<div id="main-wrapper">
<?php include('header.php'); ?>
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
        <div class="header">Details</div>                  
    </div>
</div>
<div class="card-body" id="AddFormDiv">
  <div class="row">
    <div class="col-md-12">
      <form method="post" id="workforceDetailForm" name="workforceDetailForm" action="">
        <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
        <input type="hidden" name="wid" id="wid" value="<?php echo $cntresult['id'] ?>">  
            <div class="form-body">
                <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"> Type *</label>
                         <?php
                          $mysql = new Mysql();
                          $mysql -> dbConnect();
                          $rolequery = "SELECT * FROM `tbl_role` WHERE `isactive` = 0 and `isdelete` = 0 AND `id` NOT IN (1)";
                          $rolerow =  $mysql -> selectFreeRun($rolequery);
                          while($roleresult = mysqli_fetch_array($rolerow))
                          {
                              ?>
                              <div class="custom-control custom-radio">
                                  <input type="radio" id="role-<?php echo $roleresult['id']?>" name="roleid" class="custom-control-input" value="<?php echo $roleresult['id']?>">
                                  <label class="custom-control-label" for="role-<?php echo $roleresult['id']?>"><?php echo $roleresult['role_type']?></label>
                              </div>
                             <?php
                          }
                          $mysql -> dbDisconnect();
                          ?>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          <label class="control-label"> Name *</label>
                          <input type="text" id="name" name="name" class="form-control" placeholder="">
                      </div>
                      <div class="form-group">
                          <label class="control-label"> Email *</label>
                          <input type="email" id="email" name="email" class="form-control" placeholder="">
                      </div>
                      <div class="form-group">
                          <label class="control-label">Invoices Email </label>
                          <input type="email" id="invoice_email" name="invoice_email" class="form-control" placeholder="">
                      </div>
                  </div>
                  <div class="col-md-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"> Phone *</label><br>
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="" onfocusout="check();">
                        </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label"> Depot </label>
                        <select class="select2 select2-multiple" style="width: 100%" multiple="multiple" id="depot" name="depot[]">
                            <?php
                            $mysql = new Mysql();
                            $mysql -> dbConnect();
                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                    WHERE d.`isdelete`=0 AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND  w.`userid` LIKE '".$uid."'";
                          //  $statusquery = "SELECT * FROM `tbl_depot` WHERE `isdelete`=0 AND `isactive`=0";
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
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="control-label">Start Date </label>
                        <input type="text" class="form-control mydatepicker" id="start_date" name="start_date" placeholder="mm/dd/yyyy">
                      </div>
                    </div>
                  </div>
                </div>
                <script src="countrycode/build/js/intlTelInput.js"></script>
                <script>
                    var input = document.querySelector("#phone");
                    window.intlTelInput(input, {
                        autoHideDialCode: false,
                        utilsScript: "countrycode/build/js/utils.js",
                    });
                </script>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Leave Date </label>
                        <input type="text" class="form-control mydatepicker" id="leave_date" name="leave_date" placeholder="mm/dd/yyyy">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Date of Birth </label>
                        <input type="text" class="form-control mydatepicker" id="dob" name="dob" placeholder="mm/dd/yyyy">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">NI Number *</label>
                        <input type="text" class="form-control" id="ni_number" name="ni_number" placeholder="">
                      </div>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Company Name </label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Company reg. number </label>
                        <input type="text" class="form-control" id="company_reg" name="company_reg" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">VAT Number </label>
                        <input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="">
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">UTR </label>
                        <input type="text" class="form-control" id="utr" name="utr" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Employee ID </label>
                        <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Bank Name </label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="">
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Account Number </label>
                        <input type="text" class="form-control" id="account_number" name="account_number" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Sort Code </label>
                        <input type="text" class="form-control" id="sort_code" name="sort_code" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Street Address </label>
                        <input type="text" class="form-control" id="street_address" name="street_address" placeholder="">
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Postcode </label>
                        <input type="text" class="form-control" id="postcode" name="postcode" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">State/Province </label>
                        <input type="text" class="form-control" id="state" name="state" placeholder="">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">City </label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="">
                      </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                      <label class="control-label">Uses Accountancy Services from Tax Buddy Ltd</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="yes" name="accountancy_service" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="yes">Yes</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="no" name="accountancy_service" class="custom-control-input" value="2">
                            <label class="custom-control-label" for="no">No</label>
                        </div>
                    </div>
                </div><br>

            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-success" id="submit">Submit</button>
            </div>
      </form>
    </div>
  </div>
</div>
</div>    
</div>
          </div> 
        </div>                       
      </main>
    </div>
  </div>
</div>

<?php include('footer.php');?>
</div>
<?php include('footerScript.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
  var id = <?php echo $id ?>;
  showdata(id);
  
  $("#workforceDetailForm").validate({
    rules: {
        name: 'required',
        email: 'required',
        phone: 'required',
        roleid: 'required',
    },
    messages: {
        name: "Please enter your name",
        email: "Please enter your email",
        phone: "Please enter your phone",
        roleid: "Please enter your role",
    },
    submitHandler: function(form) {
      event.preventDefault();
      $.ajax({
        url: "InsertData.php", 
        type: "POST", 
        dataType:"JSON",            
        data: $("#workforceDetailForm").serialize()+"&action=AddworkforceEditForm",
        success: function(data) {
            if(data.status==1)
            {
                 myAlert(data.title + "@#@" + data.message + "@#@success");
                 //$('#workforceDetailForm')[0].reset();
                 //showdata(id);
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

function showdata(id)
{
  $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'WorkforcwUpdateData', id: id},
      dataType: 'json',
      success: function(data) {
          $result_data = data.statusdata;
          $("input[name='roleid'][value='"+$result_data['roleid']+"']").prop('checked', true);
          $('#name').val($result_data['name']);
          $('#phone').val($result_data['phone']);
          $('#email').val($result_data['email']);
          $('#invoice_email').val($result_data['invoice_email']);
          if($result_data['depot'])
          {
             $('#depot').val($result_data['depot'].split(",")).change();
          }
          
          $('#start_date').val($result_data['start_date']);
          $('#leave_date').val($result_data['leave_date']);
          $('#dob').val($result_data['dob']);
          $('#ni_number').val($result_data['ni_number']);
          $('#company_name').val($result_data['company_name']);
          $('#company_reg').val($result_data['company_reg']);
          $('#vat_number').val($result_data['vat_number']);
          $('#utr').val($result_data['utr']);
          $('#employee_id').val($result_data['employee_id']);
          $('#bank_name').val($result_data['bank_name']);
          $('#account_number').val($result_data['account_number']);
          $('#sort_code').val($result_data['sort_code']);
          $('#street_address').val($result_data['street_address']);
          $('#postcode').val($result_data['postcode']);
          $('#state').val($result_data['state']);
          $('#city').val($result_data['city']);
          $("input[name='accountancy_service'][value='"+$result_data['accountancy_service']+"']").prop('checked', true);

      }
  });
}
</script>
</body>
</html>