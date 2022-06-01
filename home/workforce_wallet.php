<?php
include 'DB/config.php';
  $page_title = "Workforce Wallet";
   $page_id=37;
    if(!isset($_SESSION)) 
    {
        session_start();
    }

    function getStartAndEndDate($week, $year) 
    {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
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
    <title><?php echo $page_title; ?></title>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
     <style type="text/css">
        .datacard {
          border: 1px solid pink;
          height: auto;
          border-radius: 5px;
        }
        .dataheader {
           background-color: rgb(255 236 230);
        }
        .cardmb2 {
           height: 8px;
        }
    </style>
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
      <div class="col-md-4">
       <div class="card datacard">
                <div class="card-header dataheader">
                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Wallet Balance 
                    <span type="button"><i class="fas fa-calendar-alt"></i> <b><?php echo date("M d, Y"); ?></b></span></h6>
                </div>
                <div class="card-body">
                   <?php
                    $mysql = new Mysql();
                    $mysql -> dbConnect();
                    $tquery = "SELECT SUM(`amount`) as totalamount,(SELECT SUM(`amount`) FROM `tbl_workforcepayment` WHERE `wid`=".$id." AND `isdelete`=0)  as paidamount FROM `tbl_workforcelend` WHERE `wid`=".$id." AND `isdelete`=0";
                    $trow =  $mysql -> selectFreeRun($tquery);
                    $tresult = mysqli_fetch_array($trow);
                    $walletAmount = ($tresult['totalamount']-$tresult['paidamount']);
                    $mysql -> dbDisConnect();
                  ?>
                  <h5><b>£ <?php echo $walletAmount; ?></b></h5>
                  <br><small>Estimated payout of balance in 8 weeks</small>
                </div>
              </div>
        <div class="card datacard">
            <div class="card-header dataheader">
                 <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Credits
                  <span type="button" onclick="AddLendInfo();"> <i class="fas fa-hand-holding-usd"></i> Lend Money</span></h6>
            </div>     
            <div class="card-body">
              <div class="row">
                  <div class="form-group col-md-12">
                    <table>
                       <table id="renttbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                          <thead class="default">
                              <tr role="row">
                                  <th></th>
                                  <th>Amount</th>
                                  <th>Category</th>
                                  <th>Reason</th>
                                  <th>Payments</th>
                                  <th>Date</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody id="Lendbody">
                              
                          </tbody>
                      </table>
                    </table>
                  </div>
              </div>
            </div>
        </div>
    </div>


    <div class="col-md-8">
        <div class="card datacard">
            <div class="card-header dataheader">
                 <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Debits
                  <span type="button" onclick="AddPaymentInfo();"> <i class="fas fa-calendar-check"></i> Make Payment</span></h6>
            </div>     
            <div class="card-body">
              <div class="row">
                  <div class="form-group col-md-12">
                    <table>
                       <table id="paymenttbl" class="table table-responsive table-bordered" aria-describedby="example2_info">
                          <thead class="default">
                              <tr role="row">
                                  <th></th>
                                  <th>Amount</th>
                                  <th>Reason</th>
                                  <th>Invoice</th>
                                  <th>Invoiced</th>
                                  <th>Paid</th>
                                  <th>Deduction date</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody id="Paymentbody">
                              
                          </tbody>
                      </table>
                    </table>
                     
                  </div>
              </div>
            </div>
        </div>
    </div>   
  </div>
</div>           


<div id="LendMoneyModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Lend money to <?php echo $cntresult['name'];?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <form method="post" id="LendMoneyForm" name="LendMoneyForm" action="">
                <input type="hidden" name="wid" id="wid">
                  <input type="hidden" name="lendid" id="lendid">
                  <div class="row">
                    <div class="form-group col-md-6">
                      <label class="control-label">Amount *</label>
                       <div class="input-group">
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                          </div>
                          <input type="text" class="form-control" id="amount" name="amount" placeholder="">
                      </div>
                    </div>
                    <div class="form-group col-md-6">
                      <label class="control-label">Number of instalments *</label>
                          <input type="text" class="form-control" id="no_of_instal" name="no_of_instal" placeholder="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Time Interval *</label>
                       <div class="input-group">
                          <div class="input-group-append">
                              <span class="input-group-text">Every</span>
                          </div>
                          <input type="text" class="form-control" id="time_interval" name="time_interval" placeholder="">
                          <div class="input-group-append">
                              <span class="input-group-text">Week</span>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Week of first payment *</label>
                      <input type="date" class="form-control" id="week_of_payment" name="week_of_payment">
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Reason</label>
                      <textarea type="text" rows="2" class="form-control" id="reason" name="reason" placeholder=""></textarea> 
                    </div>
                  </div>
              </form>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success waves-effect waves-light" name="insert"  id="submit">Lend Money</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
</div>



<div id="PaymentModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(255 236 230);">
              <h4 class="modal-title">Make a payment for <?php echo $cntresult['name'];?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
              <form method="post" id="PaymentForm" name="PaymentForm" action="">
                <input type="hidden" name="wrkid" id="wrkid">
                  <input type="hidden" name="paymentid" id="paymentid">

                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Loan *</label>
                       <select class="select form-control custom-select" id="loan_id" name="loan_id">
                        <?php
                          $mysql = new Mysql();
                          $mysql -> dbConnect();
                          $query = "SELECT * FROM `tbl_workforcelend` WHERE `isactive`=0 AND `isdelete`=0 AND `wid`=".$id." AND `is_completed`=0";
                          $row =  $mysql -> selectFreeRun($query);
                          $rowcount=mysqli_num_rows($row);
                          if($rowcount>0)
                          {
                            while($result = mysqli_fetch_array($row))
                            {
                              ?>
                              <option value="<?php echo $result['id'];?>"><?php echo '#'.$result['id'];?></option>
                              <?php
                            }
                          }
                          else
                          { 
                            ?>
                             <option value="0">--</option>
                            <?php
                          }
                          
                          $mysql -> dbDisConnect();
                        ?>
                      </select> 
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Date *</label>
                       <select class="select form-control custom-select" id="date" name="date">
                        <?php
                        $i = 1;
                        $year = date("Y"); 
                        for($i=1;$i<74;$i++)
                        {
                          $valD = getStartAndEndDate($i,$year);
                          ?>
                          <option value="<?php echo $i; ?>"><?php echo "Week $i ( ".$valD['week_start']." - ".$valD['week_end']." )"; ?></option>
                          <?php
                        }
                        ?>
                      </select> 
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Amount *</label>
                       <div class="input-group">
                          <div class="input-group-append">
                              <span class="input-group-text"><i class="fas fa-pound-sign"></i></span>
                          </div>
                          <input type="text" class="form-control" id="amount" name="amount" placeholder="">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Reason</label>
                      <textarea type="text" rows="2" class="form-control" id="reason" name="reason" placeholder=""></textarea> 
                    </div>
                  </div>
<!-- 
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="custom-control custom-checkbox m-b-0">
                          <input type="checkbox" class="custom-control-input hideall" id="show_invoice" name="show_invoice" value="1">
                           <span class="custom-control-label">
                            <div class="header">Show on invoice</div>
                        </span>
                      </label>
                    </div>
                  </div> -->

                  <div class="row">
                    <div class="form-group col-md-12">
                      <label class="control-label">Account *</label>
                      <select class="select form-control custom-select" id="account" name="account">
                        <option value="0">--</option>
                      </select>
                    </div>
                  </div>

                  <div class="row">
                    <div class="form-group col-md-12">
                        <label class="control-label">Show as advanced payment?</label>
                          <div class="custom-control custom-radio">
                              <input type="radio" id="yes" name="isadvanced" class="custom-control-input" value="1" checked>
                              <label class="custom-control-label" for="yes">Yes</label>
                          </div>
                          <div class="custom-control custom-radio">
                              <input type="radio" id="no" name="isadvanced" class="custom-control-input" value="2">
                              <label class="custom-control-label" for="no">No</label>
                          </div>
                    </div>
                  </div>
                  
              </form>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success waves-effect waves-light" name="insert"  id="submitPayment">Make Payment</button>
              <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
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
      showLendInfo();
      showPaymentInfo();
  });

  function AddLendInfo()
  {
    $('#LendMoneyModel').modal('show');
    $('#wid').val(<?php echo $id?>);
    event.preventDefault();
  }

  function AddPaymentInfo()
  {
    $('#PaymentModel').modal('show');
    $('#wrkid').val(<?php echo $id?>);
    event.preventDefault();
  }

  $(function() {
    $('#submit').on('click', function(e) {
      e.preventDefault();
      $.ajax({
                type: "POST",
                url: "InsertData.php",
                dataType:"JSON",
                data: $('#LendMoneyForm').serialize()+"&action=WorkforceLendForm",
                success: function(data) {
                  if(data.status==1)
                  {
                       myAlert(data.title + "@#@" + data.message + "@#@success");
                       $('#LendMoneyForm')[0].reset();
                       $('#LendMoneyModel').modal('hide');
                       if(data.name == 'Update')
                       {
                          var table = $('#myTable').DataTable();
                          table.ajax.reload();
                          $("#AddFormDiv,#AddDiv").hide();
                          $("#ViewFormDiv,#ViewDiv").show();
                       }
                       showLendInfo();
                  }
                  else
                  {
                      myAlert(data.title + "@#@" + data.message + "@#@danger");
                  }
              }
      });
    });
  });

  $(function() {
    $('#submitPayment').on('click', function(e) {
      e.preventDefault();
      $date = $("#date option:selected").text();
      $.ajax({
            type: "POST",
            url: "InsertData.php",
            dataType:"JSON",
            data: $('#PaymentForm').serialize()+"&action=WorkforcePaymentForm"+"&dateval="+$date,
            success: function(data) {
              if(data.status==1)
              {
                   myAlert(data.title + "@#@" + data.message + "@#@success");
                   $('#PaymentForm')[0].reset();
                   $('#PaymentModel').modal('hide');
                   showPaymentInfo();
              }
              else
              {
                  myAlert(data.title + "@#@" + data.message + "@#@danger");
              }
          }
      });
    });
  });

  function showLendInfo()
  {
     var wid = <?php echo $id?>;
     $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ShowWorkforceLendInfoDetails', id: wid},
      dataType: 'json',
      success: function(data) {
          $('#Lendbody').html(data.tbldata);
      }

     });
  }

  function showPaymentInfo()
  {
     var wid = <?php echo $id?>;
     $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ShowWorkforcePaymentInfoDetails', id: wid},
      dataType: 'json',
      success: function(data) {
          $('#Paymentbody').html(data.tbldata);
      }

     });
  }

  function editlendrow(id)
  {
    AddLendInfo();
    $('#lendid').val(id);

    $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'WorkforceLendUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;
                $('#amount').val($result_data['amount']);
                $('#no_of_instal').val($result_data['no_of_instal']);
                $('#time_interval').val($result_data['time_interval']);
                $('#week_of_payment').val($result_data['week_of_payment']);
                $('#reason').val($result_data['reason']);
                $("#submit").attr('name', 'update');
                $("#submit").text('Update');

            }

        });
  }

</script>
</body>
</html>