<?php
include 'DB/config.php';
    $page_title = "Contractor Details";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    $id=0;
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['cid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
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
                                <div class="header">Contractors / <?php echo $cntresult['name'];?></div>
                                <div> 
                                    <a href="">
                                        <button type="button" class="btn btn-secondary"><i class="fas fa-paper-plane"></i> Send Message</button>
                                    </a>
                                    <a href="editcontractor.php"> 
                                        <button type="button" class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                                    </a>
                               </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                  <div class="d-flex align-items-center">
                                      <div class="mr-2">
                                          <span class="label label-success">Active</span>
                                      </div>
                                      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                        <i class="fas fa-suitcase"></i> 
                                         <?php
                                        if($cntresult['type']==1)
                                        {
                                          echo 'self-employed';
                                        }
                                        else
                                        {
                                          echo 'company';
                                        }
                                        ?>
                                      </div>
                                      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                         <i class="fas fa-envelope-open"></i>
                                          <?php echo $cntresult['email'];?>
                                      </div>
                                      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                          <i class="fas fa-warehouse"></i>
                                          <?php echo $cntresult['depot_type'];?>
                                      </div>
                                  </div>  
                            </div>
                            <br><hr>
                           <?php include('contractor_setting.php');?>
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
      <form method="post" id="ContractorDetailForm" name="ContractorDetailForm" action="">
        <input type="hidden" name="id" id="id" value="<?php echo $cntresult['id'] ?>"> 
        <input type="hidden" name="cid" id="cid" value="<?php echo $cntresult['id'] ?>">
        <input type="hidden" name="hidtype" id="hidtype" value="<?php echo $cntresult['type'] ?>">  
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label"> Name *</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="">
                      </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label"> Phone *</label><br>
                            <input type="number" id="phone" name="phone" class="form-control" placeholder="">
                        </div>
                    </div>
                    <?php
                    if($cntresult['type']==1)
                    {?>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label"> Email *</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="">
                          </div>
                        </div>
                    <?php
                    }
                    else
                    {?>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="control-label"> Company Register Number *</label>
                            <input type="text" id="company_reg" name="company_reg" class="form-control" placeholder="">
                          </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>

                <script src="countrycode/build/js/intlTelInput.js"></script>
                <script>
                    var input = document.querySelector("#phone");
                    window.intlTelInput(input, {
                        autoHideDialCode: false,
                        utilsScript: "countrycode/build/js/utils.js",
                    });
                </script>

                <?php
                    if($cntresult['type']==1)
                    {?>
                         <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label class="control-label">Invoices Email </label>
                                  <input type="email" id="invoice_email" name="invoice_email" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label"> Company  </label>
                                <select class="select form-control custom-select" id="company" name="company">
                                    <?php
                                    $mysql = new Mysql();
                                    $mysql -> dbConnect();
                                    $statusquery = "SELECT `id`, `name`, `company_reg`, `insert_date`, `update_date`, `delete_date`, `isdelete`, `isactive` FROM `tbl_contractorcompany` WHERE `isdelete`=0 AND `isactive`=0";
                                    $strow =  $mysql -> selectFreeRun($statusquery);
                                    while($statusresult = mysqli_fetch_array($strow))
                                    {
                                      ?>
                                          <option value="<?php echo $statusresult['id']?>" <?php if($id==$statusresult['id']) echo "selected";?>><?php echo $statusresult['name']?></option>
                                      <?php
                                    }
                                    $mysql -> dbDisconnect();
                                    ?>
                                </select> 
                                <small>If you choose self-employed it means he wont be an employee of <?php echo $cntresult['name'] ?></small>
                              </div>
                            </div>
                         
                         </div>


                        <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label">Start Date </label>
                                <input type="text" class="form-control mydatepicker" id="start_date" name="start_date" placeholder="mm/dd/yyyy">
                              </div>
                            </div>
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
                        </div>
                    <?php
                    }
                ?>
                <!-- </div> -->
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">UTR </label>
                        <input type="text" class="form-control" id="utr" name="utr" placeholder="">
                      </div>
                    </div>

                    <?php
                        if($cntresult['type']==1)
                        {?>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label">NI Number</label>
                              <input type="text" class="form-control" id="ni_number" name="ni_number" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label">Employee ID </label>
                              <input type="text" class="form-control" id="employee_id" name="employee_id" placeholder="">
                            </div>
                          </div>
                        <?php
                        }
                        else
                        {?>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label">VAT Number</label>
                              <input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="">
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="form-group">
                              <label class="control-label">Bank Name </label>
                              <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="">
                            </div>
                          </div>
                        <?php  
                        }
                    ?>
                </div>

                <?php
                    if($cntresult['type']==2)
                    {?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                  <label class="control-label">Account Number</label>
                                  <input type="text" id="account_number" name="account_number" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label"> Sort Code  </label>
                                 <input type="text" id="sort_code" name="sort_code" class="form-control" placeholder="">
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label"> Street Address </label>
                                <input type="text" id="street_address" name="street_address" class="form-control" placeholder="">
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
                    <?php
                    }
                ?>

                <div class="row">
                      <?php
                        if($cntresult['type']==1)
                        { ?>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label"> Daily Rate  </label>
                                <select class="select form-control custom-select" id="daily_rate" name="daily_rate">
                                    <option value="0">--</option>
                                </select> 
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="control-label"> Daily Miles Rate </label>
                                <select class="select form-control custom-select" id="daily_miles_rate" name="daily_miles_rate">
                                   <option value="0">--</option>
                                </select> 
                              </div>
                            </div>
                        <?php
                        }
                      ?>

                   <div class="col-md-4">
                      <label class="control-label">Uses Accountancy Services from Tax Buddy Ltd</label>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="yes" name="accountancy_service" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="yes">Yes</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="no" name="accountancy_service" class="custom-control-input" value="2" checked>
                            <label class="custom-control-label" for="no">No</label>
                        </div>
                    </div>
                </div>
                <br>
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
  $("#ContractorDetailForm").validate({
    rules: {
        name: 'required',
        email: 'required',
        phone: 'required',
    },
    messages: {
        name: "Please enter your name",
        email: "Please enter your email",
        phone: "Please enter your phone",
    },
    submitHandler: function(form) {
      event.preventDefault();
      $.ajax({
        url: "InsertData.php", 
        type: "POST", 
        dataType:"JSON",            
        data: $("#ContractorDetailForm").serialize()+"&action=ContractorDetailForm",
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

function Functionrate(data)
{
  var id = data;
  $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ContractorFunctionrateData', id: id},
      dataType: 'json',
      success: function(data) {
          $('#daily_rate').html(data.options);
          $('#daily_miles_rate').html(data.options);
      }
  });
}

function showdata(id)
{
  $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ContractorUpdateData', id: id},
      dataType: 'json',
      success: function(data) {
          $result_data = data.statusdata;
          $('#name').val($result_data['name']);
          $('#phone').val($result_data['contact']);
          $('#email').val($result_data['email']);
          $('#invoice_email').val($result_data['invoice_email']);
          if($result_data['depot'])
          {
            $('#depot').val($result_data['depot']);
            Functionrate($result_data['depot']);
          }
          $('#start_date').val($result_data['start_date']);
          $('#leave_date').val($result_data['leave_date']);
          $('#dob').val($result_data['dob']);
          $('#ni_number').val($result_data['ni_number']);
          if($result_data['company'])
          {
            $('#company').val($result_data['company']);
          }
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