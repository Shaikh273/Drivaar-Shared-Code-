<?php
include 'DB/config.php';
    $page_title = "Contractor Wallet";
    $page_id=5;
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
        $id = $_SESSION['cid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);

        $sql = "SELECT a.*,v.`registration_number` FROM `tbl_vehiclerental_agreement`  a 
          INNER JOIN `tbl_vehicles` v ON v.`id`=a.`vehicle_id`
          WHERE a.`driver_id`=$id  AND CURRENT_DATE() BETWEEN a.`pickup_date` AND a.`return_date`";
        $fire = $mysql -> selectFreeRun($sql);
        $cntresult1 = mysqli_fetch_array($fire);
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
                                      <div class="mr-3 text-grey-darkest whitespace-no-wrap">
                                           <i class="fas fa-car"></i>
                                           <?php echo $cntresult1['registration_number'];?>
                                      </div>
                                  </div>  
                            </div>
                            <br><hr>
                           <?php include('contractor_setting.php');?>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                      <div class="mr-2">
                                           <b><u><a data-toggle="collapse" href="#collapseExample" role="" aria-expanded="false" aria-controls="collapseExample"><i class="fas fa-bars"></i>  Onboarding Tasks<a></u></b>
                                      </div>
                                </div>
                            </div>
                            <div class="row">
                              <div class="collapse col-md-12" id="collapseExample">
                                <div class="card card-body border border-secondary rounded">
                                  <div class="row">
                                  
                                    <div class="col-md-4">
                                      <?php
                                      $mysql = new Mysql();
                                      $mysql -> dbConnect();
                                      $onboradquery = "SELECT * FROM `tbl_onboarding` WHERE `isdelete`=0 AND `isactive`=0";
                                      $onboardrow =  $mysql -> selectFreeRun($onboradquery);
                                      $i=1;
                                      while($result = mysqli_fetch_array($onboardrow))
                                      {
                                         $rowcount=mysqli_num_rows($onboardrow);
                                         $number = (int)($rowcount/3);
                                         ?>
                                          <span class="custom-control custom-checkbox">
                                          <label class="custom-control custom-checkbox m-b-0">
                                                <input type="checkbox" class="custom-control-input">
                                                <span class="custom-control-label"><?php
                                                echo $result['name'];?></span>
                                            </label>
                                          </span>
                                         <?php
                                         if($i % $number ==0)
                                         {
                                            ?>
                                               </div>
                                               <div class="col-md-4">
                                            <?php
                                         }
                                        
                                         $i++;

                                      }
                                      $mysql -> dbDisConnect();
                                      ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <br>
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
                    $tquery = "SELECT SUM(`amount`) as totalamount,(SELECT SUM(`amount`) FROM `tbl_contractorpayment` WHERE `cid`=".$id." AND `isdelete`=0)  as paidamount FROM `tbl_contractorlend` WHERE `cid`=".$id." AND `isdelete`=0";
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
                    
                       <table id="renttbl" class="table table-responsive table-bordered" aria-describedby="example2_info" style="display:block;">
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
                                  <th>Category</th>
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
                            <input type="hidden" name="cid" id="cid">
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
                          <button type="submit" class="btn btn-success waves-effect waves-light" name="insert" id="submit">Lend Money</button>
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
                          <form method="post" id="PaymentForm" name="PaymentForm" action="" enctype="multipart/form-data">
                            <input type="hidden" name="cntid" id="cntid">
                            <input type="hidden" name="paymentid" id="paymentid">
                              <div class="row">
                                <div class="form-group col-md-12">
                                  <label class="control-label">Loan *</label>
                                   <select class="select form-control custom-select" id="loan_id" name="loan_id">
                                    <?php
                                      $mysql = new Mysql();
                                      $mysql -> dbConnect();
                                      $query = "SELECT * FROM `tbl_contractorlend` WHERE `isactive`=0 AND `isdelete`=0 AND `cid`=".$id." AND `is_completed`=0";
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
                                <div class="col-md-12">
                                      <div class="input-group"> 
                                        <input type="text" class="form-control" id="datepicker-autoclose" placeholder="Week Number">
                                              <div class="input-group-append datepickercls">
                                                  <span class="input-group-text"><strong><span class="text-grey-dark" id="weekCal"></span></strong></span>
                                              </div>
                                      </div>
                                      <input type="hidden" id="hidWeek" name="hidWeek">
                                      <input type="hidden" id="date123" name="date">
                                      <input type="hidden" id="firstDate" name="firstDate">
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

                   <!--            <div class="row">
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
                                  <label class="control-label">Category </label>
                                  <select class="select form-control custom-select" id="category" name="category">
                                    <option value="0">Select Category</option>
                                    <?php
                                      $mysql = new Mysql();
                                      $mysql -> dbConnect();
                                      $query1 = "SELECT * FROM `tbl_walletcategory` WHERE `isdelete`=0 AND `isactive`=0";
                                      $row1 =  $mysql -> selectFreeRun($query1);
                                      $rowcount=mysqli_num_rows($row1);
                                      while($result1 = mysqli_fetch_array($row1))
                                      {
                                        ?>
                                        <option value="<?php echo $result1['id'];?>"><?php echo $result1['name'];?></option>
                                        <?php
                                      }
                                      $mysql -> dbDisConnect();
                                    ?>
                                  </select>
                                </div>
                              </div>


                              <div class="row">
                                <div class="form-group col-md-12">
                                  <label class="control-label">Account *</label>
                                  <select class="select form-control custom-select" id="account" name="account">
                                    <option value="0">--</option>
                                  </select>
                                </div>
                              </div>

                              <div class="row">
                                <div class="form-group col-md-6">
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
                                <div class="col-md-6">
                                    <span class="file">
                                          <label class="control-label">Upload Document</label><br>
                                          <input type="file" id="file" name="file" class="form-control" placeholder="">
                                    </span> 
                                </div>
                              </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-success waves-effect waves-light" name="insert"  id="submitPayment">Make Payment</button>
                          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                      </div>
                      </form>
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
      $('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true,
        calendarWeeks : true,
    }).on("changeDate", function (e) {
      var d = $.datepicker.iso8601Week(e.date);
      var dy = new Date(e.date).getDay();
      if(dy==0)
      {
        d++;
      }
      var currentDate = new Date(e.date);
      var firstday = new Date(currentDate.setDate(currentDate.getDate() - currentDate.getDay() + 1)).toISOString().split('T')[0];
      var lastday = new Date(currentDate.setDate(currentDate.getDate() - currentDate.getDay() + 7)).toISOString().split('T')[0];
     $('#weekCal').html("Week # : " + d);
     $('#date123').val(d);
     $('#hidWeek').val("Week "+d+" ( "+firstday+" => "+lastday+" )");
     $('#firstDate').val(firstday);
  });
  });

  function AddLendInfo()
  {
    $('#LendMoneyModel').modal('show');
    $('#cid').val(<?php echo $id?>);
    event.preventDefault();
  }

  function AddPaymentInfo()
  {
    $('#PaymentModel').modal('show');
    $('#cntid').val(<?php echo $id?>);
    event.preventDefault();
  }

  $(function() {
    $('#submit').on('click', function(e) {
      e.preventDefault();
      $.ajax({
                type: "POST",
                url: "InsertData.php",
                dataType:"JSON",
                data: $('#LendMoneyForm').serialize()+"&action=ContractorLendForm",
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
                       location.reload();
                  }
                  else
                  {
                      myAlert(data.title + "@#@" + data.message + "@#@danger");
                  }
              }
      });
    });
  });

  // $(function() {
  //   $('#submitPayment').on('click', function(e) {
  //     e.preventDefault();
  //     date = $("#hidWeek").val();
  //     $.ajax({
  //           type: "POST",
  //           url: "InsertData.php",
  //           dataType:"JSON",
  //           data: $('#PaymentForm').serialize()+"&action=ContractorPaymentForm"+"&dateval="+date,
  //           success: function(data) {
  //             if(data.status==1)
  //             {
  //                  myAlert(data.title + "@#@" + data.message + "@#@success");
  //                  $('#PaymentForm')[0].reset();
  //                  $('#PaymentModel').modal('hide');
  //                  showPaymentInfo();
  //                  location.reload();
  //             }
  //             else
  //             {
  //                 myAlert(data.title + "@#@" + data.message + "@#@danger");
  //             }
  //         }
  //     });
  //   });
  // });

  $("#PaymentForm").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'contractorwalletdocumentupload.php',
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
                    $('#PaymentForm')[0].reset();
                    $('#PaymentModel').modal('hide');
                    showPaymentInfo();
                    location.reload();
                       
                }
                else
                {
                    myAlert(data.title + "@#@" + data.message + "@#@danger");
                }
            }
        });
    });

  function showLendInfo()
  {
     var cid = <?php echo $id?>;
     $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ShowContractorLendInfoDetails', id: cid},
      dataType: 'json',
      success: function(data) {
          $('#Lendbody').html(data.tbldata);
      }

     });
  }

  function deleterow(id,loan_id,cid)
  {

      $.ajax({

          type: "POST",

          url: "loaddata.php",

          data: {action : 'ContractorWalletDeleteData', id: id, cid: cid, loan_id:loan_id},

          dataType: 'json',

          success: function(data) {
              if(data.status==1)
              {
                  showPaymentInfo();
                  myAlert("Delete @#@ Data has been deleted successfully.@#@success");
              }
              else
              {
                  myAlert("Delete @#@ Data can not been deleted.@#@danger");
              }
              
          }

      });
  }

  function showPaymentInfo()
  {
     var cid = <?php echo $id?>;
     $.ajax({
      type: "POST",
      url: "loaddata.php",
      data: {action : 'ShowContractorPaymentInfoDetails', id: cid},
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

            data: {action : 'ContractorLendUpdateData', id: id},

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