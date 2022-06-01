<?php
$page_title = "Contractor Invoices";
include 'DB/config.php';
$page_id = 85;
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
  $mysql = new Mysql();
  $mysql->dbConnect();
  $query = "SELECT 
                  COUNT(DISTINCT IF(i.`status_id` = 3,i.`id`,NULL)) AS Holdcount, 
                  COUNT(DISTINCT IF(i.`status_id` = 9,i.`id`,NULL)) AS Disputed, 
                  COUNT(DISTINCT IF(i.`status_id` = 4,i.`id`,NULL)) AS Paid, 
                  COUNT(DISTINCT IF(i.`status_id` = 8,i.`id`,NULL)) AS send, 
                  COUNT(DISTINCT IF(i.`status_id` = 2,i.`id`,NULL)) AS approved, 
                  COUNT(DISTINCT IF(i.`status_id` = 1,i.`id`,NULL)) AS pending 
                  FROM `tbl_contractorinvoice` i 
                  INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
                  INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $uid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                  WHERE  i.`depot_id` IN (w.depot_id) AND i.`isdelete`=0 AND i.`isactive`=0 AND i.`istype`=1";
  $row =  $mysql->selectFreeRun($query);
  $result = mysqli_fetch_array($row);
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
  <style>
    #loading-overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      display: none;
      align-items: center;
      background-color: #000;
      z-index: 999;
      opacity: 0.5;
    }

    .loading-icon {
      position: absolute;
      border-top: 2px solid #fff;
      border-right: 2px solid #fff;
      border-bottom: 2px solid #fff;
      border-left: 2px solid #767676;
      border-radius: 25px;
      width: 25px;
      height: 25px;
      margin: 0 auto;
      position: absolute;
      left: 50%;
      margin-left: -20px;
      top: 50%;
      margin-top: -20px;
      z-index: 4;
      -webkit-animation: spin 1s linear infinite;
      -moz-animation: spin 1s linear infinite;
      animation: spin 1s linear infinite;
    }

    @-moz-keyframes spin {
      100% {
        -moz-transform: rotate(360deg);
      }
    }

    @-webkit-keyframes spin {
      100% {
        -webkit-transform: rotate(360deg);
      }
    }

    @keyframes spin {
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
  </style>
</head>

<body class="skin-default-dark fixed-layout">
  <?php include('loader.php'); ?>
  <div id="loading-overlay">
    <div class="loading-icon">adfasdsafasdf</div>
  </div>
  <div id="main-wrapper">
    <?php include('header.php'); ?>
    <div class="page-wrapper">
      <div class="container-fluid">
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document" style="width:fit-content;">
            <div class="modal-content">
              <div class="modal-body">
                <img src="loader.gif" alt="loading" style="position: relative;" />
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Paid (<?php echo $result['Paid']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Sent (<?php echo $result['send']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Hold (<?php echo $result['Holdcount']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Approved (<?php echo $result['approved']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Pending (<?php echo $result['pending']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                  <div class="col  border-right  py-2">
                    <div class="text-grey-dark"><small>Disputed (<?php echo $result['Disputed']; ?>)</small></div>
                    <h5 class="m-0" style="font-weight: 700;"><b>£0.00</b></h5>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="row">
                      <button type="button" class="btn btn-info btn-sm col-md-12" disabled>Review and pay invoices</button>
                    </div><br>

                    <div class="row">
                      <div class="card text-left col-md-12 ">
                        <div class="card-header">
                          <i class="fa fa-exclamation-triangle"></i><b>Disputed Invoices</b>
                        </div>
                        <div class="card-body divborder">
                          <p class="card-text">You have <?php echo $result['Disputed']; ?> invoices which are still disputed
                            <br><a href="javascript:void(0)" onclick="invoice(this.id);" id="disputedinvoice">Show more >> </a>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="card text-left col-md-12">
                        <div class="card-header"><span class="label label-danger">21</span><b>Depots with overdue Invoices</b></div>
                        <div class="card-body text-left divborder">
                          <p class="card-text">You have 153 invoices that are overdue within the following depots:
                          </p><br>
                          <div class="list-group list-group-flush">
                            <a class="list-group-item d-flex justify-content-between align-items-baseline px-0 py-2" href="#">
                              <span class="overflow-hidden text-overflow-ellipsis whitespace-no-wrap">Avonmounth (DBS2) - Amazon Logistics (BS11 0YH)</span>
                              <span class="label label-info">7</span>
                            </a>
                          </div>

                          <div class="list-group list-group-flush">
                            <a class="list-group-item d-flex justify-content-between align-items-baseline px-0 py-2" href="#">
                              <span class="overflow-hidden text-overflow-ellipsis whitespace-no-wrap">Banbury (DOX2) - Amazon Logistics (OX16 2SN)</span>
                              <span class="label label-info">7</span>
                            </a>
                          </div>

                          <div class="list-group list-group-flush">
                            <a class="list-group-item d-flex justify-content-between align-items-baseline px-0 py-2" href="#">
                              <span class="overflow-hidden text-overflow-ellipsis whitespace-no-wrap">Bournemouth Airport (DBH2) - AMZL</span>
                              <span class="label label-info">7</span>
                            </a>
                          </div>

                          <div class="list-group list-group-flush">
                            <a class="list-group-item d-flex justify-content-between align-items-baseline px-0 py-2" href="#">
                              <span class="overflow-hidden text-overflow-ellipsis whitespace-no-wrap">Camberley (DZL4) - Amazon Logistics (GU15 3AD)</span>
                              <span class="label label-info">7</span>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>

                    <div class="row">
                      <div class="card text-left col-md-12">
                        <div class="card-header"><i class="fa fa-exclamation-triangle"></i><b>Invoice still on hold</b></div>
                        <div class="card-body divborder">
                          <p class="card-text">You have <b><?php echo $result['Holdcount']; ?></b>invoices still on hold<br>
                            <a href="javascript:void(0)" onclick="invoice(this.id);" id="holdinvoice">Show more >> </a>
                          </p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="card text-left col-md-12">
                        <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                          <button type="button" class="btn btn-info btn-sm col-md-12"><b>All Actions</b></button>
                        </a>
                        <div class="card-body divborder collapse" id="collapseExample">
                          <div class="table-responsive m-t-0">
                            <table id="myTable1" class="dataTable table table-responsive" style="width: 100%;">
                              <tbody id="actionlog">
                              </tbody>
                            </table>
                            <br>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-9" id="allInvoicesSection">
                    <div class="row">
                      <div class="col-md-3">
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">Depot</span></div>
                          <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="loadtable();">
                            <option value="%">All Depot</option>
                            <?php
                            $mysql = new Mysql();
                            $mysql->dbConnect();
                            // $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                            //     INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                            //     WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`userid` LIKE '".$uid."'";
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

                      <div class="col-md-3">
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">Status</span></div>
                          <select class="select form-control custom-select" id="status" name="status" onchange="loadtable();">
                            <option value="%">All Status</option>
                            <?php
                            $mysql = new Mysql();
                            $mysql->dbConnect();
                            $statusquery = "SELECT * FROM `tbl_invoicestatus` WHERE `isdelete`=0 AND `isactive`=0";
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

                      <div class="col-md-3">
                        <div class="input-group">
                          <div class="input-group-prepend"><span class="input-group-text">VAT</span></div>
                          <select class="select form-control custom-select" id="vat" name="vat" onchange="loadtable();">
                            <option value="%">All</option>
                            <option value="1">With VAT</option>
                            <option value="2">Without VAT</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group">
                          <input type="text" class="form-control" id="datepicker-autoclose" placeholder="Week Number">
                          <div class="input-group-append datepickercls">
                            <span class="input-group-text"><strong><span class="text-grey-dark" id="weekCal"></span></strong></span>
                          </div>
                        </div>
                        <input type="hidden" id="hidWeek">
                      </div>
                    </div><br>

                    <div class="card">
                      <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                          <?php
                          $mysql = new Mysql();
                          $mysql->dbConnect();
                          $cntquery = "SELECT COUNT(DISTINCT i.`id`) AS count
                                                            FROM `tbl_contractorinvoice` i 
                                                            INNER JOIN `tbl_contractor` c ON c.`id`=i.`cid` 
                                                            INNER JOIN tbl_workforcedepotassign w ON w.`wid` LIKE ('" . $uid . "') AND w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL
                                                            WHERE i.`depot_id` IN (w.depot_id) AND i.`isdelete`=0 AND i.`isactive`=0 AND i.`istype`=1";
                          $cntrow =  $mysql->selectFreeRun($cntquery);
                          $cntresult = mysqli_fetch_array($cntrow);
                          $mysql->dbDisconnect();
                          ?>
                          <div class="header">Invoices(<?php echo $cntresult['count'];  ?>)</div>
                          <div class="col-md-5"></div>
                          <div class="btn-group">
                            <button type="button" class="btn btn-info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Export <i class="fa fa-chevron-down"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="#" onclick="loadExporterTable();"><i class="fas fa-check"></i> Export</a>
                              <a class="dropdown-item" href="#" onclick="loadDetailExporterTable();"><i class="fas fa-file"></i> Export Detail</a>
                            </div>
                          </div>
                          <!-- <div class="col-md-1 d-flex align-items-right " >
                                                <input type="button" class="btn btn-info" value='Export' onclick="loadExporterTable();">
                                            </div> -->
                          <div class="col-md-1 d-flex align-items-right ">
                            <label class="custom-control custom-checkbox m-b-0">
                              <input type="checkbox" class="custom-control-input" id="duedate" value="" onclick="duedata(this);">
                              <span class="custom-control-label">Due this week</span>
                            </label>
                          </div>

                          <div class="btn-group">
                            <button type="button" class="btn btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Bulk Action <i class="fa fa-chevron-down"></i>
                            </button>

                            <div class="dropdown-menu">
                              <div> Mark selected as: </div>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="#" onclick="bulkActFun('1');"><i class="fas fa-check"></i> Approve</a>
                              <a class="dropdown-item" href="#" onclick="bulkActFun('2');"><i class="fas fa-paper-plane"></i> Send</a>
                              <a class="dropdown-item" href="#" onclick="bulkActFun('3');"><i class="fab fa-cc-amazon-pay"></i> Pay</a>
                            </div>
                          </div>
                          <input type="hidden" id="bulkOperationInvcID">
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive m-t-40" style="margin-top: 0px;">
                          <table id="myTable" class="display dataTable table table-responsive" role="grid" aria-describedby="example2_info" style="width: 100%;">
                            <thead class="default">
                              <tr role="row">
                                <th data-orderable="false"><input type="checkbox" class="form-control" id="bulkSelect" name="bulkSelect" style="width:auto;" onchange="bulkSelectOption()"></th>
                                <th>Contractor</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th data-orderable="false">Period</th>
                                <th>Number</th>
                                <th data-orderable="false">VAT</th>
                                <th data-orderable="false">Due</th>
                                <th></th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                      <div class="card-footer"></div>
                    </div>
                  </div>
                  <div class="col-md-9" id="exportInvoicesSection" style="display:none;">
                    <div class="card">
                      <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="header">Invoice Exporter</div>
                          <div class="col-md-5"></div>
                          <div class="col-md-2 d-flex align-items-right ">
                            <input type="button" class="btn btn-info" id="exportInvoicesBtn" value="Back" onclick="loadInvoiceTable();">
                            <input type="hidden" id="hidWeek1">
                          </div>
                        </div>
                      </div>
                      <div class="card-body">

                        <div class="table-responsive m-t-40">
                          <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Week</th>
                                <th>From date</th>
                                <th>To date</th>
                                <th>Year</th>
                                <th>Due Date</th>
                                <th>Associate</th>
                                <th>Company</th>
                                <th>Depot</th>
                                <th>Status</th>
                                <th>Vat</th>
                              </tr>
                            </thead>
                            <tbody id="exportDataBody">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9" id="exportDetailInvoicesSection" style="display:none;">
                    <div class="card">
                      <div class="card-header" style="background-color: rgb(255 236 230);">
                        <div class="d-flex justify-content-between align-items-center">
                          <div class="header">Invoice Exporter</div>
                          <div class="col-md-5"></div>
                          <div class="col-md-2 d-flex align-items-right ">
                            <input type="button" class="btn btn-info" id="exportInvoicesBtn" value="Back" onclick="loadInvoiceTable();">
                            <input type="hidden" id="hidWeek1">
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive m-t-40">
                          <table id="example56" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th>Invoice #</th>
                                <th>Name</th>
                                <th>Depot</th>
                                <th>Type</th>
                                <th>Week</th>
                                <th>Date</th>
                                <th>Service Name/Reg No</th>
                                <th>Route</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Vat</th>
                                <th>Gross</th>
                                <th>Due Date</th>
                                <th>Status</th>
                              </tr>
                            </thead>
                            <tbody id="exportDataBody1">

                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <div id="addstatus" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color: rgb(255 236 230);">
          <h4 class="modal-title">Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <form method="post" id="AddStatusForm" name="AddStatusForm" action="">
            <input type="hidden" name="statusid" id="statusid">
            <div class="form-group">
              <label class="control-label">Status *</label>
              <select class="select form-control custom-select" id="modalstatus" name="modalstatus">

              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="AddInvoiceStatusData();" class="btn btn-success waves-effect waves-light">Save changes</button>
          <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php include('footer.php'); ?>
  </div>
  <?php include('footerScript.php'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true,
        calendarWeeks: true,
      }).on("changeDate", function(e) {
        var d = $.datepicker.iso8601Week(e.date);
        var dy = new Date(e.date).getDay();
        if (dy == 0) {
          d++;
        }
        $('#weekCal').html("Week # : " + d);
        $('#hidWeek').val(d);
        loadtable();
      });
      actionLog();
      $("#AddFormDiv,#AddDiv").hide();

      $('#myTable').DataTable({
        'processing': true,
        "serverSide": true,
        "destroy": true,
        'pageLength': 10,
        "drawCallback": function(settings) {
          var arr = [];
          var str = $("#bulkOperationInvcID").val();
          if (str != "") {
            arr = str.split(",");
            $('input.check:checkbox').each(function() {
              var crn = $(this).val();
              if (arr.indexOf(crn) > -1)
                $(this).prop("checked", true);
            });
          }
          $("#exampleModal").modal('hide');
        },
        "ajax": {
          "url": "loadtabledata.php",
          "type": "POST",
          "data": function(d) {
            d.action = 'loadFinancecntInvoicestabledata';
            d.did = $('#depot_id').val();
            d.statusid = $('#status').val();
            d.vatid = $('#vat').val();
            d.duedate = $('#duedate').val();
            d.weekNo = $('#hidWeek').val();
            d.bulkSelect = $("#bulkSelect").is(":checked");
          }
        },
        'columns': [{
            data: 'bulkOption'
          },
          {
            data: 'name'
          },
          {
            data: 'total'
          },
          {
            data: 'status'
          },
          {
            data: 'period'
          },
          {
            data: 'invoice_no'
          },
          {
            data: 'vat'
          },
          {
            data: 'due'
          },
          {
            data: 'Action'
          },
        ]
      });
      $('#myTable_filter input')
        .off()
        .on('keyup', function() {
          $("#bulkSelect").prop("checked", false);
          $("#bulkOperationInvcID").val("");
          $('#myTable').DataTable().search(this.value.trim(), false, false).draw();
        });
    });

    function loadtable() {
      $("#bulkSelect").prop("checked", false);
      $("#bulkOperationInvcID").val("");
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }

    function invoice(obj) {
      if (obj == 'disputedinvoice') {
        $('#status').val(9);
      }
      if (obj == 'holdinvoice') {
        $('#status').val(3);
      }
      loadtable();
    }

    function duedata(obj) {
      $('#duedate').val($(obj).is(":checked"));
      loadtable();
    }

    function addstatus(id, statusid) {
      $('#addstatus').modal('show');
      $('#statusid').val(id);
      event.preventDefault();
      $.ajax({
        type: "POST",
        url: "loaddata.php",
        data: {
          action: 'InvoiceStatusData',
          id: id,
          statusid: statusid
        },
        dataType: 'json',
        success: function(data) {
          if (data.status == 1) {
            $('#modalstatus').html(data.options);
            $('#modalstatus').select('refresh');
          }
        }

      });
    }

    function AddInvoiceStatusData() {
      var statusid = $('#statusid').val();
      var status = $('#modalstatus').val();
      $.ajax({
        type: "POST",
        url: "InsertData.php",
        data: {
          action: 'AddInvoiceStatusData',
          id: statusid,
          status: status
        },
        dataType: 'json',
        success: function(data) {
          if (data.status == 1) {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
            actionLog();
            myAlert("Update @#@ Status has been changed successfully.@#@success");
          } else {
            myAlert("Update Error @#@ Status can not been changed successfully.@#@danger");
          }
          $('#addstatus').modal('hide');
        }
      });
    }

    function actionLog() {
      $.ajax({
        type: "POST",
        url: "loaddata.php",
        data: {
          action: 'ShowActionLog',
          type: 1
        },
        dataType: 'text',
        success: function(data) {
          $('#actionlog').html(data);
        }

      });
    }

    function loadExporterTable() {
      if ($("#hidWeek").val()) {
        var wikNo = $("#hidWeek").val();
        var vat = $("#vat").val();
        var status = $("#status").val();
        var depot_id = $("#depot_id").val();
        var duedate = $("#duedate").val();
        if (duedate != true) {
          duedate = false;
        }
        var date = new Date($('#datepicker-autoclose').val());
        var wikYear = date.getFullYear();
        document.getElementById('allInvoicesSection').style.display = 'none';
        document.getElementById('exportDetailInvoicesSection').style.display = 'none';
        document.getElementById('exportInvoicesSection').style.display = 'block';
        $('#example23').DataTable().rows().remove().draw();
        $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {
            action: 'exportInvoiceTableData',
            wikNo: wikNo,
            vat: vat,
            status: status,
            depot_id: depot_id,
            duedate: duedate,
            wikYear: wikYear
          },
          dataType: 'json',
          beforeSend: function() {
            $("#loading-overlay").show();
          },
          success: function(data) {
            if (data.status == 1) {
              //$("#exportDataBody").html(data.dt);
              $('#example23').DataTable().destroy();
              $('#example23').find('tbody').append(data.dt);
              $('#example23').DataTable({
                dom: 'Bfrtip',
                buttons: [
                  'copy', 'csv', 'excel', 'print'
                ]
              }).draw();
              $("#loading-overlay").hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $("#loading-overlay").hide();
            myAlert("Error @#@ Something went wrong!!!.@#@danger");
          }

        });
      } else {
        myAlert("Alert @#@ Please select week number first!!!.@#@danger");
      }
    }

    function loadDetailExporterTable() {
      if ($("#hidWeek").val()) {
        var wikNo = $("#hidWeek").val();
        var vat = $("#vat").val();
        var status = $("#status").val();
        var depot_id = $("#depot_id").val();
        var duedate = $("#duedate").val();
        if (duedate != true) {
          duedate = false;
        }
        var date = new Date($('#datepicker-autoclose').val());
        var wikYear = date.getFullYear();
        document.getElementById('allInvoicesSection').style.display = 'none';
        document.getElementById('exportInvoicesSection').style.display = 'none';
        document.getElementById('exportDetailInvoicesSection').style.display = 'block';
        $('#example56').DataTable().rows().remove().draw();
        $.ajax({
          type: "POST",
          url: "exportcontractorinvoicedata.php",
          data: {
            action: 'exportcontactorDetailsInvoiceTableData',
            wikNo: wikNo,
            vat: vat,
            status: status,
            depot_id: depot_id,
            duedate: duedate,
            wikYear: wikYear
          },
          dataType: 'json',
          beforeSend: function() {
            $("#loading-overlay").show();
          },
          success: function(data) {
            if (data.status == 1) {
              //$("#exportDataBody").html(data.dt);
              $('#example56').DataTable().destroy();
              $('#example56').find('tbody').append(data.dt);
              $('#example56').DataTable({
                dom: 'Bfrtip',
                buttons: [
                  'copy', 'csv', 'excel', 'print'
                ]
              }).draw();
              $("#loading-overlay").hide();
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            $("#loading-overlay").hide();
            myAlert("Error @#@ Something went wrong!!!.@#@danger");
          }

        });
      } else {
        myAlert("Alert @#@ Please select week number first!!!.@#@danger");
      }
    }

    function loadInvoiceTable() {
      document.getElementById('exportInvoicesSection').style.display = 'none';
      document.getElementById('exportDetailInvoicesSection').style.display = 'none';
      document.getElementById('allInvoicesSection').style.display = 'block';
    }

    function selectInvoiceNo(ths) {
      var boIn1 = $("#bulkOperationInvcID").val();
      var boIn2 = [];
      boIn2 = boIn1.split(",");
      if (ths.checked) {
        if (boIn1 == "") {
          $("#bulkOperationInvcID").val(ths.value);
        } else {
          $("#bulkOperationInvcID").val(boIn1 + "," + ths.value);
        }
      } else {
        if ($("#bulkSelect").is(":checked")) {
          var table = $('#myTable').DataTable();
          var search = table.search();
          var did = $('#depot_id').val();
          var statusid = $('#status').val();
          var vatid = $('#vat').val();
          var duedate = $('#duedate').val();
          var weekNo = $('#hidWeek').val();
          var noInvc = ths.value;
          $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {
              action: 'getSelectedInvoice',
              search: search,
              did: did,
              statusid: statusid,
              vatid: vatid,
              duedate: duedate,
              weekNo: weekNo,
              noInvc: noInvc
            },
            dataType: 'json',
            success: function(data) {
              var data1 = data.dt;
              $("#bulkOperationInvcID").val(data1);
              boIn2 = data1.split(",");;
              boIn2 = jQuery.grep(boIn2, function(value) {
                return value != ths.value;
              });
              $("#bulkOperationInvcID").val(boIn2.join());
            }
          });
          $("#bulkSelect").prop("checked", false);
        } else {
          boIn2 = jQuery.grep(boIn2, function(value) {
            return value != ths.value;
          });
          $("#bulkOperationInvcID").val(boIn2.join());
        }
      }
    }

    function bulkSelectOption() {
      $("#exampleModal").modal('show');
      $("#bulkOperationInvcID").val("");
      var table = $('#myTable').DataTable();
      table.ajax.reload();
    }
    $('#example23').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copy', 'csv', 'excel', 'print'
      ]
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-excel').addClass('btn btn-primary mr-1');

    function bulkActFun(act) {
      var blkIds = $("#bulkOperationInvcID").val();
      if (blkIds != "") {
        $("#bulkOperationInvcID").val("");
        $(".check").prop("checked", false);
        $.ajax({
          type: "POST",
          url: "loaddata.php",
          data: {
            action: 'performAction',
            blkIds: blkIds,
            act: act
          },
          dataType: 'json',
          success: function(data) {
            var table = $('#myTable').DataTable();
            table.ajax.reload();
            myAlert("Successfull!! @#@ Bulk action performed successfully.@#@success");
          }
        });
      } else {
        myAlert("Alert!! @#@ Please select inveoices first..@#@danger");
      }
    }
  </script>
</body>

</html>