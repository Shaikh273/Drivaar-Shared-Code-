 <?php
  $page_title = "Drivaar";
  include 'DB/config.php';
  $page_id = '175';
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
   <style type="text/css">
     .rightborder {
       border-right: 0.3px solid #4b5563a6 !important;
     }

     .bg-striped {
       background-image: linear-gradient(45deg, hsla(0, 0%, 100%, .15) 25%, transparent 0, transparent 50%, hsla(0, 0%, 100%, .15) 0, hsla(0, 0%, 100%, .15) 75%, transparent 0, transparent);
       background-size: 1rem 1rem;
     }

     #btn-back-to-top {
       position: fixed;
       bottom: 20px;
       right: 20px;
       display: none;
     }

   </style>
 </head>

 <body class="skin-default-dark fixed-layout">
   <?php include('loader.php');  ?>
   <div id="main-wrapper">
     <?php include('header.php'); ?>
     <div class="page-wrapper content" id="top">
       <div class="container-fluid">
         <div class="row">
           <div class="col-md-12">
             <div class="card">
               <div class="card-body">
                 <!-- 
<div class="row">
  <div class="col-md-6">
    
  </div>
  <div class="col-md-6">
    
  </div>
</div> -->


                 <div class="row">
                   <div class="d-flex justify-content-between align-items-center col-md-12">
                     <div class="col-md-3">
                       <div class="form-group has-primary">
                         <label class="control-label">Select Depot*</label>
                         <select class="select form-control custom-select" id="depot_id" name="depot_id" onchange="showrotatable(this.value);">
                           <option value="%">All Depot</option>
                           <?php
                            $selected = '%';
                            $mysql = new Mysql();
                            $mysql->dbConnect();
                            $depoListWf = "";
                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                                    INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                                    WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL AND w.`wid` LIKE '" . $uid . "'";
                            $strow =  $mysql->selectFreeRun($statusquery);
                            while ($statusresult = mysqli_fetch_array($strow)) {
                              $depoListWf .= "<option value='" . $statusresult['id'] . "'>" . $statusresult['name'] . "</option>";
                            }
                            echo $depoListWf;
                            $mysql->dbDisconnect();
                            ?>
                         </select>
                       </div>
                     </div>


                     <!-- old code -->


                     <!-- <div class="col-md-3">
                                <div class="form-group has-primary">
                                    <label class="control-label">Seach by Contractor</label>
                                    <input type="text" class="form-control" data-aire-component="input" placeholder="Search by Contractor" aria-label="Search by Contractor" name="contractorSearch" id="contractorSearch" data-aire-for="contractorSearch" > 
                                </div>
                            </div>
													<div class="col-md-1">
														<div class="dropdown">
														  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														    More Actions
														  </button>
														  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
														    <a class="dropdown-item" href="#" onclick="DepotData();">Weekly Availability</a>
														    <a class="dropdown-item" href="#" onclick="excelUpload();">Import Excel</a> -->
                     <!-- <a class="dropdown-item" href="#">Something else here</a> -->
                     <!--  </div>
														</div> -->
                     <!-- <button class="btn btn-secondary float-right"  onclick="DepotData();">Weekly Availability</button> -->
                     <!-- </div> -->

                     <div class="col-md-9">
                       <div class="row">
                         <div class="col-md-4">
                           <div class="form-group has-primary">
                             <label class="control-label">Seach by Contractor</label>
                             <input type="text" class="form-control" data-aire-component="input" placeholder="Search by Contractor" aria-label="Search by Contractor" name="contractorSearch" id="contractorSearch" data-aire-for="contractorSearch">
                           </div>
                         </div>
                         <div class="col-md-2">
                           <div class="dropdown">
                             <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top: 28px;">
                               More Actions
                             </button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                               <a class="dropdown-item" href="#" onclick="DepotData();">Weekly Availability</a>
                               <a class="dropdown-item" href="#" onclick="excelUpload();">Import Excel</a>
                               <!-- <a class="dropdown-item" href="#">Something else here</a> -->
                             </div>
                           </div>
                         </div>

                         <div class="col-md-6">
                           <div class="input-group" style="margin-top: 28px;">
                             <div class="input-group-prepend">
                               <button class="input-group-text btn btn-secondary" onclick="PreviousWeek();">
                                 << </button>
                             </div>
                             <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 65%">
                               <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                               <span>26/05/2021 - 01/06/2021</span> <b class="caret"></b>
                             </div>
                             <div class="input-group-append">
                               <button class=" input-group-text btn btn-secondary" onclick="NextWeek();"> >> </button>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>


                     <!--  <div class="col-md-4">
													 <div class="input-group ">
												      <div class="input-group-prepend">
												        <button  class="input-group-text btn btn-secondary" onclick="PreviousWeek();"> << </button>
												      </div>
												      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 65%">
													    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
													    <span>26/05/2021 - 01/06/2021</span> <b class="caret"></b>
													</div>
													<div class="input-group-append">
        <button  class=" input-group-text btn btn-secondary" onclick="NextWeek();"> >> </button>
      </div>
												    </div>
											</div> -->
                   </div>
                   <div class="position-relative" style="width: 100%;margin-top: 15px;">
                     <div class="align-items-center col-md-12">
                       <div class="table-responsive-lg">
                         <table class="table w-100 table-hover" small="small" hover="hover">
                           <thead>
                             <tr id="rotadayoffCount">
                             </tr>

                             <tr id="setdate">
                             </tr>

                             <tr class="hidden" id="othDepotSupport">
                               <th class="border-right bg-grey-lightest">Supports From Other Depot</th>
                               <th class="p-0 position-relative border-right" id="ods-td-1"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-2"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-3"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-4"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-5"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-6"></th>
                               <th class="p-0 position-relative border-right" id="ods-td-7"></th>
                             </tr>

                             <tr class="default">
                               <td class="bg-striped bg-grey-lighter" colspan="8">&nbsp;</td>
                             </tr>
                           </thead>
                           <tbody id="calrows">

                           </tbody>
                         </table>
                         <button type="button" class="btn btn-danger btn-floating btn-lg" id="btn-back-to-top"><i class="fas fa-arrow-down"></i></button>
                         <input type='hidden' value='0' id='scrlId'>
                         <input type='hidden' value='%' id='Lastdpt'>
                       </div>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <div id="DetailModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-lg">
           <div class="modal-content">
             <div class="modal-header" style="background-color: rgb(255 236 230);">
               <h4 class="modal-title"></h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <div class="modal-body">
               <input type="hidden" id="tddataid" name="tddataid">
               <div class="row border-bottom mt-n4">
                 <div class="col-md-4 border-right bg-grey-lightest pt-3">
                   <div class="text-center mb-2">
                     <h5 class="m-0">
                       <a href="" id="cntname"></a>
                     </h5>
                     <small id="cnttype"></small>
                   </div>
                   <p class="text-center">
                     Maximum working days: <u></u>
                   </p>
                   <p class="text-center">
                     Earnings for the day:
                     <span class="label label-success">£0.00</span>
                   </p>
                   <p class="text-center" id="invoicebtn">
                     <!-- <a href="" target="_blank" class="btn btn-primary btn-sm">View Invoice</a> -->
                   </p>
                 </div>

                 <div class="col-md-8 pt-3" id="scheduleFunc">
                   <div class="row">
                     <div class="col-md-6">
                       <div class="input-group mb-3">
                         <div class="input-group-prepend">
                           <label class="input-group-text" for="inputGroupSelect01">Status:</label>
                         </div>
                         <select id="contractor_status" name="contractor_status" class="custom-select" onchange="updateFields(this.value)">
                           <?php
                            $mysql = new Mysql();
                            $mysql->dbConnect();
                            $query = "SELECT * FROM `tbl_contractorstatus` WHERE `isdelete`=0 AND `isactive`=0 AND id IN(1,2,3,4,16) ORDER BY id DESC";
                            $row =  $mysql->selectFreeRun($query);
                            while ($result = mysqli_fetch_array($row)) {
                            ?>
                             <option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                           <?php
                            }
                            $mysql->dbDisConnect();
                            ?>
                         </select>
                       </div>
                     </div>
                     <div class="col-md-6" id="quick">
                       <div class="mb-4">
                         <lable>Quick Action:</lable>
                         <div class="btn-group" role="group" aria-label="Basic example">
                           <button type="button" class="btn-sm btn btn-outline-primary" onclick="changestatus(1);">Working
                           </button>
                           <button type="button" class="btn-sm btn btn-outline-danger" onclick="changestatus(2);">Day Off
                           </button>
                           <!-- <button type="button" class="btn-sm btn btn-outline-dark">Stand By
                                </button>
                                <button type="button" class="btn-sm btn btn-outline-danger">Holiday
                                </button>
                                <button type="button" class="btn-sm btn btn-outline-dark">Same Day Route
                                </button> -->
                         </div>
                       </div>
                     </div>
                     <div class="col-md-6" id="extWorking">
                       <div class="mb-4">
                         <div class="input-group mb-3">
                           <div class="input-group-prepend">
                             <label class="input-group-text" for="inputGroupSelect02">Working Type:</label>
                           </div>
                           <select id="contractor_ext_status" name="contractor_ext_status" class="custom-select" onchange="ifOtherDepot(this.value);">
                             <?php
                              $mysql = new Mysql();
                              $mysql->dbConnect();
                              $query = "SELECT `id`, `workStatus` FROM `tbl_contractorWorkingStatus` WHERE `isDelete`=0 AND `isActive`=1 order by id asc";
                              $row =  $mysql->selectFreeRun($query);
                              while ($result = mysqli_fetch_array($row)) {
                              ?>
                               <option value="<?php echo $result['id']; ?>"><?php echo $result['workStatus']; ?></option>
                             <?php
                              }

                              ?>
                           </select>
                         </div>
                       </div>
                     </div>
                     <div class="col-md-12 hidden" id="depotDivList">
                       <div class="form-group mb-1" data-aire-component="group" data-aire-for="wave">
                         <label class="cursor-pointer" data-aire-component="label" for="otherDepot">Other Depots</label>
                         <select class="form-control custom-select" data-aire-component="select" name="otherDepot" id="otherDepot">
                           <option value="" selected></option>
                           <?php
                            $depoListWf = "";
                            $statusquery = "SELECT DISTINCT d.`id`,d.`name` FROM `tbl_workforcedepotassign` w 
                                            INNER JOIN `tbl_depot` d ON d.`id`=w.`depot_id`
                                            WHERE w.`isdelete`=0 AND w.`isactive`=0 AND w.`release_date` IS NULL";
                            $strow =  $mysql->selectFreeRun($statusquery);
                            while ($statusresult = mysqli_fetch_array($strow)) {
                              $depoListWf .= "<option value='" . $statusresult['id'] . "'>" . $statusresult['name'] . "</option>";
                            }
                            echo $depoListWf;
                            $mysql->dbDisConnect();
                            ?>
                         </select>
                         <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="otherDepot"></div>
                       </div>

                     </div>
                   </div>

                   <div class="row">
                     <div class="col-md-6">
                       <div class="form-group">
                         <label for="exampleInputEmail1">Route:</label>
                         <input type="text" class="form-control" name="route" id="route">
                         <small class="form-text text-muted"></small>
                       </div>
                     </div>

                     <div class="col-md-6">
                       <div class="form-group mb-1" data-aire-component="group" data-aire-for="wave">
                         <label class="cursor-pointer" data-aire-component="label" for="wave">Wave</label>
                         <select class="form-control custom-select" data-aire-component="select" name="wave" id="wave">
                           <option value="" selected="">--:--</option>
                           <?php
                            //date_default_timezone_set("Europe/London");
                            $range = range(strtotime("06:00"), strtotime("18:00"), 15 * 60);
                            foreach ($range as $time) {
                            ?>
                             <option value="<?php echo date("H:i", $time); ?>"><?php echo date("H:i", $time); ?></option>
                           <?php
                            }
                            ?>
                         </select>
                         <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="wave"></div>
                       </div>

                     </div>
                   </div>
                 </div>
               </div>
               <div class="row">
                 <div class="col-md-12 p-0">
                   <div class="table-responsive-lg">

                     <table class="table w-100 table-hover table-sm table-vcenter border-bottom" small="small" centered="centered" hover="hover">
                       <thead>
                         <tr>
                           <th colspan="2" class="border-top-0 text-center">Payments</th>
                           <th class="text-right border-top-0">Price</th>
                           <th class="text-right border-top-0">Total</th>
                           <th class="border-top-0"></th>
                         </tr>
                       </thead>
                       <tbody id="paymenttypetd">

                       </tbody>
                     </table>

                     <div class="alert alert-danger mx-3 mt-4" role="alert" id="alertmsg">
                       <div class="d-flex align-items-baseline">
                         <div class="flex-grow-1">
                           <i class="fas fa-exclamation-circle"></i>To add payments you need to assign the driver as working
                         </div>
                       </div>
                     </div>
                   </div>

                   <div id="no_payment" class="hidden">
                     <!-- <div class="card card-info m-3">
					            <div class="card-header">
						            <div class="d-flex justify-content-between align-items-center">
						                <div>
						                    <h6 class="m-0"><i class="fas fa-lightbulb"></i> Suggested Payments</h6>
						                </div>
						            </div>
					        	</div>
    
					            <div class="table-responsive-lg">
					    			<table class="table w-100 table-sm table-vcenter" small="small" centered="centered">

						        		<tbody id="">
					                        <tr>
				                                <td class="pl-3">Remote Debrief - block of 8Hours (£110.00)</td>
				                                <td class="text-right">
				                                    <button class="btn btn-light btn-sm border">AddPayment</button>
				                                </td>
					                        </tr>
					                    </tbody>
								    </table>
								</div>
    						</div> -->

                     <div class="d-flex align-items-center justify-content-center mt-4 px-3">
                       <select class="custom-select" name="payment_id" id="payment_id">
                       </select>

                       <button class="btn btn-primary ml-2 whitespace-no-wrap" onclick="assignPaymentType();">
                         Add Payment
                       </button>
                     </div>
                   </div>
                 </div>
               </div>
             </div>
             <div class="modal-footer" id="scheduleModalFooter">
               <button type="button" onclick="changestatus();" class="btn btn-success waves-effect waves-light">Save changes</button>
               <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
             </div>
           </div>
         </div>
       </div>

       <div id="DepotModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-lg" style="max-width: 90%;">
           <div class="modal-content">
             <div class="modal-header" style="background-color: rgb(255 236 230);">
               <h4 class="modal-title">Weekly Availability</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <div class="modal-body">
               <input type="hidden" id="id" name="id">
               <div class="table-responsive">
                 <table class="table w-100 table-hover table-vcenter table-fixed" centered="centered" hover="hover" fixed="fixed">
                   <thead>
                     <tr id="setmodaldate">
                     </tr>
                   </thead>

                   <tbody id="setmodalleavedate">

                   </tbody>
                 </table>
               </div>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
             </div>
           </div>
         </div>
       </div>

       <div id="TimeModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-lg" style="max-width: 900px;">
           <div class="modal-content">
             <div class="modal-header" style="background-color: rgb(255 236 230);">
               <h4 class="modal-title" id="modaldt"></h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <div class="modal-body">
               <input type="hidden" id="tddataid" name="tddataid">
               <table class="table w-100 table-sm my-4 border">
                 <thead>
                   <tr class="default">
                     <th class="border">6am</th>
                     <th class="border">7am</th>
                     <th class="border">8am</th>
                     <th class="border">9am</th>
                     <th class="border">10am</th>
                     <th class="border">11am</th>
                     <th class="border">12pm</th>
                     <th class="border">1pm</th>
                     <th class="border">2pm</th>
                     <th class="border">3pm</th>
                     <th class="border">4pm</th>
                     <th class="border">5pm</th>
                     <th class="border">6pm</th>
                     <th class="border">7pm</th>
                     <th class="border">8pm</th>
                     <th class="border">9pm</th>
                   </tr>
                 </thead>
                 <tbody id="timedata">

                 </tbody>
               </table>
             </div>
             <div class="modal-footer">
               <button type="button" class="btn btn-success waves-effect waves-light">Send Schedule</button>
               <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
             </div>
           </div>
         </div>
       </div>

       <div id="BulkModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-lg">
           <div class="modal-content">
             <div class="modal-header" style="background-color: rgb(255 236 230);">
               <h4 class="modal-title"></h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <div class="modal-body">
               <form method="post" id="BulkForm" name="BulkForm" action="">
                 <input type="hidden" id="bulkdate" name="bulkdate">
                 <input type="hidden" id="colno" name="colno">
                 <div class="row border-bottom mt-n4">
                   <div class="col-md-12">
                     <div class="input-group mb-3">
                       <div class="input-group-prepend">
                         <label class="input-group-text" for="inputGroupSelect01">Apply Status:</label>
                       </div>
                       <select id="bulk_status" name="bulk_status" class="custom-select">
                         <?php
                          $mysql = new Mysql();
                          $mysql->dbConnect();
                          $query = "SELECT * FROM `tbl_contractorstatus` WHERE `isdelete`=0 AND `isactive`=0 AND id IN(1,2,3,4,16) ORDER BY id DESC";
                          $row =  $mysql->selectFreeRun($query);
                          while ($result = mysqli_fetch_array($row)) {
                          ?>
                           <option value="<?php echo $result['id']; ?>"><?php echo $result['name']; ?></option>
                         <?php
                          }
                          $mysql->dbDisConnect();
                          ?>
                       </select>
                     </div>
                   </div>

                   <div class="card col-md-12">

                     <div class="card-header">
                       <div class="d-flex justify-content-between align-items-center">
                         <div>
                           <h6 class="m-0">Select drivers</h6>
                         </div>
                       </div>
                     </div>
                     <div class="card-body">

                       <div>
                         <label class="custom-control custom-checkbox m-b-0">
                           <input type="checkbox" class="custom-control-input" id="checkAll" onchange="bulkcheckAll()">
                           <span class="custom-control-label">
                             <h6 class="m-0">Select All</h6>
                           </span>
                         </label>
                       </div>

                       <div style="max-height:300px; overflow-y: auto;" id="Setdriver">

                       </div>
                     </div>
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="submit" name="insert" class="btn btn-success waves-effect waves-light" id="submit">Save</button>
                   <!-- <button type="button" onclick="BulkdataSave();" class="btn btn-success waves-effect waves-light">Save</button> -->
                   <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                 </div>
               </form>
             </div>

           </div>
         </div>
       </div>


       <div id="ExcelUpload" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
         <div class="modal-dialog modal-md">
           <div class="modal-content">
             <div class="modal-header" style="background-color: rgb(255 236 230);">
               <h3 class="modal-title">Upload excel exported/sent by Amazon</h3>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
             </div>
             <form method="post" id="image-form" enctype="multipart/form-data" onSubmit="return false;">
               <div class="modal-body">
                 <div id="msg"></div>
                 <div class="form-group">
                   <input type="file" name="file" class="file" style="visibility: hidden;position: absolute;" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                   <div class="input-group my-3">
                     <input type="text" class="form-control" disabled placeholder="Upload File" id="file">
                     <div class="input-group-append">
                       <button type="button" class="browse btn btn-primary">Browse...</button>
                     </div>
                   </div>
                 </div>
                 <div class="form-group">
                   <input type="submit" name="submit" value="Upload" class="btn btn-danger">
                 </div>
               </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
               </div>
             </form>
           </div>
         </div>
       </div>

       <?php include('footer.php'); ?>
     </div>
     <?php
      include('footerScript.php');
      ?>
     <script>
       $("#contractorSearch").keyup(function() {
         var dptid = $('#depot_id').val();
         showrotatable(dptid);
       });
     </script>

     <script type="text/javascript">
       let mybutton = document.getElementById("btn-back-to-top");

       function scrollFunction() {
         if (
           document.body.scrollTop > 20 ||
           document.documentElement.scrollTop > 20
         ) {
           mybutton.style.display = "block";
         } else {
           mybutton.style.display = "none";
         }
       }
       mybutton.addEventListener("click", backToTop);

       function backToTop() {
         document.body.scrollTop = Math.max($(document).height(), $(window).height());
         document.documentElement.scrollTop = Math.max($(document).height(), $(window).height());
         var dptid = $('#depot_id').val();
         showrotatable(dptid);
       }


       $(document).on("click", ".browse", function() {
         var file = $(this)
           .parent()
           .parent()
           .parent()
           .find(".file");
         file.trigger("click");
       });
       $('input[type="file"]').change(function(e) {
         var fileName = e.target.files[0].name;
         $("#file").val(fileName);

         var reader = new FileReader();
         reader.onload = function(e) {
           // get loaded data and render thumbnail.
           // document.getElementById("preview").src = e.target.result;
         };
         // read the image file as a data URL.
         reader.readAsDataURL(this.files[0]);
       });



       var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
       var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
       var userid = <?php echo $userid ?>;
       $(document).ready(function() {
         var date1 = '';
         $.ajax({
           type: "POST",
           url: "setTimezone.php",
           async: false,
           data: {
             action: 'GetDateFunction'
           },
           dataType: 'text',
           success: function(data) {
             date1 = data;
           }
         });
         var date = new Date(date1);
         setdate(date);
         showrotatable('%');
         // setTimeout(() => {
         //   NextWeek();
         //   PreviousWeek();
         // }, 1000);  
         $(window).scroll(function() {
           scrollFunction();
           var dptid = $('#depot_id').val();
           if (($(window).scrollTop() + $(window).height() == $(document).height()) && (dptid != 0)) {
             showrotatable(dptid);
           }
         });





         $("#image-form").on("submit", function() {
           $("#msg").html('<div class="alert alert-info"><i class="fa fa-spin fa-spinner"></i> Please wait...!</div>');
           $.ajax({
             type: "POST",
             url: "loadDataExtended1.php",
             data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
             contentType: false, // The content type used when sending data to the server.
             cache: false, // To unable request pages to be cached
             processData: false, // To send DOMDocument or non processed data file it is set to false
             success: function(data) {
               if (data == 1 || parseInt(data) == 1) {
                 $("#msg").html(
                   '<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Data updated successfully.</div>'
                 );
               } else {
                 $("#msg").html(
                   '<div class="alert alert-info"><i class="fa fa-exclamation-triangle"></i> Extension not good only try with <strong>GIF, JPG, PNG, JPEG</strong>.</div>'
                 );
               }
             },
             error: function(data) {
               $("#msg").html(
                 '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> There is some thing wrong.</div>'
               );
             }
           });
         });


       });

       function loadpage(id) {
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'ContractorSetSessionData',
             cid: id
           },
           dataType: 'json',
           success: function(data) {
             if (data.status == 1) {
               window.location = '<?php echo $webroot ?>contractorDetails.php';
             }
           }
         });
       }

       function showrotatable(dataval) {
         var searchKey = $('#contractorSearch').val();
         var scrlId = document.getElementById('scrlId').value;
         var lastDptid = document.getElementById('Lastdpt').value;
         document.getElementById('Lastdpt').value = dataval;
         if (dataval != lastDptid) {
           scrlId = 0;
         }
         if (dataval != '%') {
           $('#othDepotSupport').removeClass('hidden');
         } else {
           $('#othDepotSupport').addClass('hidden');
         }
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'ShowrotatableData',
             depot_id: dataval,
             scrID: scrlId,
             serachKey: searchKey
           },
           dataType: 'JSON',
           success: function(data1) {
             const data = JSON.parse(JSON.stringify(data1));
             if (data.status == 1) {
               if (dataval == lastDptid) {
                 if (searchKey.length > 0) {
                   $('#calrows').html(data.dts);
                 } else {
                   $("#calrows").append(data.dts);
                 }
               } else {
                 $('#calrows').html(data.dts);
               }
               document.getElementById('scrlId').value = data.scrlId;
               var date = new Date($('#hiddate_1').val());
               var nextweek = new Date(date.getFullYear(), date.getMonth(), date.getDate());
               setdate(nextweek);
             }
           }
         });
       }

       function PreviousWeek() {
         var date = new Date($('#hiddate_1').val());
         var prweek = new Date(date.getFullYear(), date.getMonth(), date.getDate() - 7);
         setdate(prweek);
       }

       function NextWeek() {
         var date = new Date($('#hiddate_1').val());
         var nextweek = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 7);
         setdate(nextweek);
       }

       function setdate(date1) {
         var date = date1;
         var week = [];
         var set = ['<th class="border-right bg-grey-lightest"></th>'];

         var i;
         for (i = 1; i <= 7; i++) {
           //i = i-1;
           var first = date.getDate() - date.getDay() + (i - 1);
           date.setDate(first);
           var day = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
           var Formated_day = monthNames[date.getMonth()] + " " + date.getDate() + " , " + date.getFullYear();
           week.push(day);
           set.push("<th class='border-right bg-grey-lightest'><div class='text-left'>" + days[date.getDay()] + "<br>" + Formated_day + "<input type='hidden' id='hiddate_" + i + "' name='hiddate_" + i + "' value='" + day + "'/><button style='float: right;' class='text-right btn btn-light btn-sm' data-toggle='tooltip' data-title='Bulk Action' onclick=\"bulkaction(\'" + day + "\'," + i + ");\"><i class='fas fa-exchange-alt'></i></button></div></th>");
           $('.td-' + i).html('<div class="w-full h-full"><div class="w-full h-full d-flex align-items-center justify-content-center text-grey-light hover:text-grey-darkest hover:bg-pink-200" style="font-size: 24px; font-weight: 300;height: 62px;">+</div></div>');
           showdata('hiddate_' + i, day);
         }
         $('#setdate').html(set);
         weekdatashow();
       }

       function bulkaction(daydate, colno) {
         $('#BulkModel').modal('show');
         var dptid = $('#depot_id').val();
         $('#bulkdate').val(daydate);
         $('#colno').val(colno);
         var d = new Date(daydate);
         var date = (monthNames[d.getMonth()]) + " " + d.getDate() + ", " + d.getFullYear();
         $('.modal-title').html('Bulk Status Change - ' + date);
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'GetBulkDriver',
             depotid: dptid
           },
           dataType: 'text',
           success: function(data) {
             $('#Setdriver').html(data);
           }
         });
       }

       // function BulkdataSave()
       // {
       // 	var bulkdate = $('#bulkdate').val();

       // }

       $("#BulkForm").validate({
         submitHandler: function(form) {
           event.preventDefault();
           var startdate = $('#bulkdate').val();
           var cid = document.getElementsByName("driverid[]");
           var flg1 = 0;

           // for(var x=0;x<cid.length;x++){
           // 	 //checkdays(startdate,cid[x].id,userid,0);
           // 	 $.ajax({
           //         type: "POST",
           //         url: "PreviousWeekDayChange.php",
           //         async:false,
           //         data: {action : 'ShowPreviousWeekDayOnchange',startdate:startdate,cid:cid[x].id,userid:userid},
           //         dataType: 'text',
           //         success: function(data) {
           //           if(data>6)
           //           {
           //             myAlert("Alert@#@Only 6 working days allowed in a row.@#@danger");
           //             $("#"+cid[x].id).prop('checked',false);
           //           }
           //         }
           //     });
           // }

           $.ajax({
             url: "InsertData.php",
             async: false,
             type: "POST",
             dataType: "JSON",
             data: $("#BulkForm").serialize() + "&action=BulkDriversform",
             success: function(data) {
               if (data.status == 1) {
                 myAlert(data.title + "@#@" + data.message + "@#@success");
                 $('#BulkModel').modal('hide');
                 weekdatashow();
               } else {
                 myAlert(data.title + "@#@" + data.message + "@#@danger");
               }
             }
           });
         }
       });


       function bulkcheckAll() {
         if ($('#checkAll').prop('checked') == true) {
           $(".subchecked").prop('checked', true);
         } else {
           $(".subchecked").prop('checked', false);
         }
       }

       //$("#checkAll").change(function () {
       // });

       function showdata(id, val) {
         var hiddendate_id = id;
         var seq = hiddendate_id.split('_')[1];
         var hiddendate_val = val;
         var cls = document.getElementsByClassName("td-" + seq);
         var i;
         for (i = 0; i < cls.length; i++) {
           var chk = cls[i].id.split('_');
           var chk1 = "";
           if (chk.length > 4) {
             chk1 = chk[0] + "_" + chk[1] + "_" + chk[2] + "_" + chk[3];
           } else {
             chk1 = cls[i].id;
           }
           var hiddendateid = chk1 + "_" + hiddendate_val;
           cls[i].setAttribute("id", hiddendateid);
         }
       }

       function DepotData() {
         $('#DepotModel').modal('show');
         // var date1 = new Date();
         // var date = date1;
         var date1 = '';
         $.ajax({
           type: "POST",
           url: "setTimezone.php",
           async: false,
           data: {
             action: 'GetDateFunction'
           },
           dataType: 'text',
           success: function(data) {
             date1 = data;
           }
         });
         var date = new Date(date1);
         var week = [];
         var set = ['<th class="border-top-0 border-right">Depot</th>'];

         var i;
         for (i = 1; i <= 7; i++) {
           var first = date.getDate() - date.getDay() + (i - 1);
           date.setDate(first);
           var day = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
           var Formated_day = monthNames[date.getMonth()] + " " + date.getDate() + " , " + date.getFullYear();
           week.push(day);
           set.push("<th class='text-center border-top-0 bg-grey-lightest'>" + days[date.getDay()] + "<br>" + Formated_day + "<input type='hidden' id='mdhiddate_" + i + "' name='mdhiddate_" + i + "' value='" + day + "'/></th>");
         }
         $('#setmodaldate').html(set);

         var stdate = $('#mdhiddate_1').val();
         var endate = $('#mdhiddate_7').val();
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'ModalRotaWeeklyData',
             stdate: stdate,
             endate: endate,
             userid: userid
           },
           dataType: 'text',
           success: function(data) {
             $('#setmodalleavedate').html(data);
           }
         });
       }

       function excelUpload() {
         $('#ExcelUpload').modal('show');
       }

       function updateFields(val1) {
         if (val1 == 1) {
           $('#quick').addClass('hidden');
           $('#extWorking').removeClass('hidden');
           $('#contractor_ext_status').val(11);
         } else {
           $('#depotDivList').addClass('hidden');
           $('#quick').removeClass('hidden');
           $('#extWorking').addClass('hidden');
         }
       }

       function ifOtherDepot(dpId) {
         if (dpId == 2) {
           $('#depotDivList').removeClass('hidden');
           $('#otherDepot option').eq(0).prop('selected', true);
         } else {
           $('#otherDepot option').eq(0).prop('selected', true);
           $('#depotDivList').addClass('hidden');
         }
       }

       function DetailModel(data, cid) {
         $('#DetailModel').modal('show');
         data.id = data.id.split('@')[0];
         $('#tddataid').val(data.id);
         var status = $('#status_' + data.id).val();
         var ndt = data.id.split('_')[4];
         var d = new Date(ndt);
         var date = (monthNames[d.getMonth()]) + " " + d.getDate() + ", " + d.getFullYear();
         $('.modal-title').html(date);
         $('#contractor_status').val(status);
         $('#contractor_ext_status').val(11);

         if (status == 1) {
           $('#no_payment').removeClass('hidden');
           $('#quick').addClass('hidden');
           $('#extWorking').removeClass('hidden');
           $('#alertmsg').addClass('hidden');
           $('#contractor_ext_status').val($('#ext_status_' + data.id).val());
           if ($('#ext_status_' + data.id).val() == 2) {
             $('#depotDivList').removeClass('hidden');
             $('#otherDepot').val($('#othDepot_' + data.id).val());
           }
         } else if (status == 16) {
           $('#depotDivList').addClass('hidden');
           $('#quick').removeClass('hidden');
           $('#extWorking').addClass('hidden');
           $('#alertmsg').removeClass('hidden');
         } else {
           $('#depotDivList').addClass('hidden');
           $('#contractor_status').val(16);
           $('#quick').removeClass('hidden');
           $('#extWorking').addClass('hidden');
           $('#no_payment').addClass('hidden');
           $('#alertmsg').removeClass('hidden');
         }
         IndexPaymentTypeDataFun(cid, ndt);
         ShowPaymentTypeData(data.id, cid);
         event.preventDefault();
       }

       function ShowPaymentTypeData(uniqid, cid) {
         var uniqid = $('#tddataid').val();
         var date = uniqid.split('_')[4];
         var cid = uniqid.split('_')[2];
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'ShowRotaPaymentTypeData',
             userid: userid,
             id: cid,
             date: date
           },
           dataType: 'json',
           success: function(data) {
             $('#paymenttypetd').html(data.options);
           }
         });
         IndexPaymentTypeDataFun(cid, date);
       }

       function delPaymentType(paymid) {
         var uniqid = $('#tddataid').val();
         var date = uniqid.split('_')[4];
         var cid = uniqid.split('_')[2];
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'delPaymentType',
             paymid: paymid
           },
           dataType: 'json',
           success: function(data) {
             var uniqid = $('#tddataid').val();
             var cid = uniqid.split('_')[2];
             ShowPaymentTypeData(uniqid, cid);
             myAlert("Success@#@Payment deleted successfully!!!@#@success");
           }

         });
         IndexPaymentTypeDataFun(cid, date);
       }

       function changestatus(val) {
         var id = $('#tddataid').val();
         var cid = id.split('_')[2];
         var date = id.split('_')[4];
         var startdate = $('#hiddate_1').val();
         var enddate = $('#hiddate_7').val();

         if (val) {
           var status = val;
         } else {
           var status = $('#contractor_status').val();
         }
         var route = $('#route').val();
         var wave = $('#wave').val();
         var flg = 0;
         var worFlg = 0;
         var othDepFlg = 0;
         if (status == 1) {
           //alert(date+"-----"+cid+"-----"+userid);
           //flg = checkdays(date,cid,userid,1);
           $.ajax({
             type: "POST",
             url: "PreviousWeekDayChange.php",
             async: false,
             data: {
               action: 'ShowPreviousWeekDayOnchange',
               startdate: date,
               cid: cid,
               userid: userid
             },
             dataType: 'text',
             success: function(data) {
               if (data > 6) {
                 myAlert("Alert@#@Only 6 working days allowed in a row.@#@danger");
               } else {
                 flg = 1;
                 worFlg = $('#contractor_ext_status').val();
                 if (worFlg == 2) {
                   othDepFlg = $('#otherDepot').val();
                 }
               }
             }
           });

         } else {
           flg = 1;
         }

         if (flg == 1) {
           $.ajax({
             type: "POST",
             url: "loaddata.php",
             data: {
               action: 'contractorAssignStatusUpdateData',
               cid: cid,
               statusid: status,
               uniqid: id,
               date: date,
               userid: userid,
               route: route,
               wave: wave,
               startdate: startdate,
               enddate: enddate,
               extWorking: worFlg,
               othDepFlg: othDepFlg
             },
             dataType: 'json',
             success: function(result) {
               if (result.status == 1) {

                 myAlert(result.title + "@#@" + result.message + "@#@success");
                 if (status == 1) {
                   $('#quick').addClass('hidden');
                   $('#extWorking').removeClass('hidden');
                   $('#no_payment').removeClass('hidden');
                   $('#alertmsg').addClass('hidden');
                 } else {
                   $('#quick').removeClass('hidden');
                   $('#extWorking').addClass('hidden');
                   $('#paymenttypetd').html(result.nopaymentdata);
                   $('#no_payment').addClass('hidden');
                   $('#alertmsg').removeClass('hidden');
                 }
                 weekdatashow();
               }
             }
           });
         }
       }


       function countValPayment(val, uniqid, amount) {
         var cal = (val * amount);
         $('#' + uniqid).html('£' + cal);

         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'InsertRotaPaymentTypeData',
             userid: userid,
             value: val,
             uniqid: uniqid
           },
           dataType: 'json',
           success: function(data) {
             if (data.status == 1) {
               myAlert("Assign @#@ Payment data assign successfully.@#@success");
             }
           }
         });
       }

       function checkdays(startdate, cid, userid, st) {
         $.ajax({
           type: "POST",
           url: "PreviousWeekDayChange.php",
           async: false,
           data: {
             action: 'ShowPreviousWeekDayOnchange',
             startdate: startdate,
             cid: cid,
             userid: userid
           },
           dataType: 'text',
           success: function(data) {
             var data = parseInt(data);
             if (data > 6) {
               myAlert("Alert@#@Only 6 working days allowed in a row.@#@danger");
               if (st == 0) {
                 $("#" + cid).prop('checked', false);
               }
               return 0;
             } else {
               //flg=1;
               return 1;
             }
           }
         });
       }

       // <small class="position-absolute w-4 h-4 font-weight-600 bg-green-50 rounded d-flex align-items-center justify-content-center border-grey-dark border text-grey-dark" style="top:2px; right:2px">2</small>
       Date.prototype.getWeek = function() {
         var onejan = new Date(this.getFullYear(), 0, 1);
         var today = new Date(this.getFullYear(), this.getMonth(), this.getDate());
         var dayOfYear = ((today - onejan + 86400000) / 86400000);
         return Math.ceil(dayOfYear / 7)
       };

       function weekdatashow() {
         var startdate = $('#hiddate_1').val();
         var enddate = $('#hiddate_7').val();
         var dpid = $('#depot_id').val();

         $.ajax({
           url: "loaddata.php",
           type: "POST",
           dataType: "JSON",
           data: {
             action: 'RotaWeekDataShow',
             userid: userid,
             startdate: startdate,
             enddate: enddate,
             dpid: dpid
           },
           success: function(response1) {
             if (response1) {
               var response = response1;
               var len = 0;
               var wcnt = 0; //count of days working

               if (response.length > 0) {
                 len = response.length;
                 var cnt = 1;
                 var ccid = 0;

                 var prd = "";
                 for (var i = 0; i < len; i++) {
                   var uniqid = response[i].uniqid;
                   if (response[i].wave) {
                     var wave = response[i].wave;
                   } else {
                     var wave = '';
                   }

                   $('#wave').val(response[i].wave);
                   $('#route').val(response[i].route);
                   $('#daysCount-itag-' + ccid).addClass('hidden');
                   if (ccid != response[i].cid) {
                     prd = response[i].date;
                     cnt = 1;
                     if (wcnt == 6) {
                       $('#daysCount-itag-' + ccid).removeClass('hidden');
                     } else {
                       $('#daysCount-itag-' + ccid).addClass('hidden');
                     }
                     $('#daysCount-' + ccid).html(wcnt);
                     wcnt = 0;
                   }

                   const date1 = new Date(prd);
                   const date2 = new Date(response[i].date);
                   const diffTime = Math.abs(date2 - date1);
                   const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                   if (diffDays > 1) {
                     cnt = 1;
                   }
                   if (response[i].status_id == 1) {
                     // if the date is the first date of the week
                     var myDate = new Date(startdate);
                     var prettyDate = (myDate.getFullYear() + '-' + ('0' + (myDate.getMonth() + 1)).slice(-2)) + '-' + ('0' + myDate.getDate()).slice(-2);
                     if (prettyDate == response[i].date) {
                       // $.ajax({
                       //       type: "POST",
                       //       url: "loaddata.php",
                       //       async:false,
                       //       data: {action : 'ShowPreviousWeekDayOff',startdate:prettyDate,cid:response[i].cid,userid:userid},
                       //       dataType: 'JSON',
                       //       success: function(data) {
                       //           const myObj = JSON.parse(JSON.stringify(data));
                       // 		      var dl = myObj.dtl.length;
                       //                   if(dl>0)
                       //                   {
                       //                     var cnt2 = 0;
                       //                     var j = 0;
                       //                     var calcDate = data.enddate;
                       //                     var cDate = data.dtl[j].date;
                       //                     while(data.dtl[j].status_id==1 && cDate==calcDate &&  dl>0)
                       //                     {
                       //                       cnt2++;
                       //                       j++;
                       //                       dl--;
                       //                       cDate = data.dtl[j].date;
                       //                       var date1 = new Date(calcDate);
                       //                       date1.setDate(date1.getDate() - 1);
                       //                       var date2 =(date1.getFullYear() +'-'+ ('0' + (date1.getMonth()+1)).slice(-2)) +'-'+ ('0' + date1.getDate()).slice(-2);
                       //                       calcDate = date2;
                       //                     }
                       //                     cnt = ++cnt2;
                       //                   }
                       //                   else
                       //                   {
                       //                     cnt = 1;
                       //                   }
                       //               }
                       // }); 
                     }

                     //$('#contractor_status').val(1);
                     // $('#no_payment').removeClass('hidden');
                     // $('#alertmsg').addClass('hidden');
                     //$('#quick').addClass('hidden');
                     var clrcode = "bg-green-200 border-green-600";
                     var othDepDiv = "";
                     if (response[i].ext_status_id == 2) {
                       clrcode = "bg-gray-300 border-gray-600";
                       othDepDiv = "<div style='width:150px;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'><strong>" + response[i].otherDptName + "</strong></div>";
                     }
                     var span = '<input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="1"><input type="hidden" id="ext_status_' + uniqid + '" name="ext_status_' + uniqid + '" value="' + response[i].ext_status_id + '"><input type="hidden" id="othDepot_' + uniqid + '" name="othDepot__' + uniqid + '" value="' + response[i].otherDepot + '"><div><div class="border rounded d-inline-block text-grey-darker position-relative h-full w-full px-1 ' + clrcode + '" style="height: 62px;">';

                     // span += '<small class="position-absolute w-4 h-4 font-weight-600 bg-green-50 rounded d-flex align-items-center justify-content-center border-grey-dark border text-grey-dark" style="top:2px; right:2px">'+cnt+'</small>';

                     span += '<div class="d-flex align-items-center justify-content-between pr-4"><div><strong class="text-grey-darkest">' + wave + '</strong></div><div></div></div><div class="d-flex align-items-center justify-content-between pr-4"><div></div><div></div></div><div><div class="whitespace-no-wrap">Working</div>' + othDepDiv + '</div><div class="whitespace-no-wrap overflow-hidden text-overflow-ellipsis"></div><div></div></div></div></div>';
                     $('#' + uniqid).html(span);
                     wcnt++;
                   } else if (response[i].status_id == 2) {
                     //$('#contractor_status').val(2);
                     //$('#quick').addClass('hidden');
                     var span = '<input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="2"><div class="w-full h-full"><div class="border rounded d-inline-block text-grey-darker position-relative h-full w-full px-1 bg-red-100 bg-striped border border-red-200 align-items-center justify-content-center d-flex font-weight-bold text-uppercase text-red-200 border-dashed" style="height: 62px;"><div><div class="whitespace-no-wrap">Day Off</div></div><div class="whitespace-no-wrap overflow-hidden text-overflow-ellipsis"></div><div></div></div></div>';
                     $('#' + uniqid).html(span);
                     cnt = 0;
                   } else if (response[i].status_id == 3) {
                     //$('#contractor_status').val(3);
                     // $('#no_payment').removeClass('hidden');
                     // $('#alertmsg').addClass('hidden');
                     //$('#quick').addClass('hidden');
                     var span = '<input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="3"><div class="w-full h-full"><div class="border rounded d-inline-block text-grey-darker position-relative h-full w-full px-1 bg-blue-100 border-blue-400" style="height: 62px;"><div class="d-flex align-items-center justify-content-between pr-4"><div><strong class="text-grey-darkest">' + wave + '</strong></div><div></div></div><div><div class="whitespace-no-wrap">Stand By</div></div><div class="whitespace-no-wrap overflow-hidden text-overflow-ellipsis"></div><div></div></div></div>';
                     $('#' + uniqid).html(span);
                     cnt = 0;
                   } else if (response[i].status_id == 4) {
                     //$('#contractor_status').val(4);
                     //$('#quick').addClass('hidden');
                     var span = '<input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="4"><div class="w-full h-full"><div class="border rounded d-inline-block text-grey-darker position-relative h-full w-full px-1 bg-pink-100 border-pink-200 bg-striped align-items-center justify-content-center d-flex font-weight-bold text-uppercase text-pink-200 border-dashed" style="height: 62px;"><div><div class="whitespace-no-wrap">Holiday</div><div class="whitespace-no-wrap overflow-hidden text-overflow-ellipsis"></div><div></div></div></div>';
                     $('#' + uniqid).html(span);
                     cnt = 0;
                   } else if (response[i].status_id == 16) {
                     //$('#contractor_status').val(16);
                     var span = '<input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="16"><div class="w-full h-full"><div class="w-full h-full d-flex align-items-center justify-content-center text-grey-light hover:text-grey-darkest hover:bg-pink-200" style="font-size: 24px; font-weight: 300;height: 62px;">+</div></div>';
                     $('#' + uniqid).html(span);
                     cnt = 0;
                   }
                   cnt++;
                   ccid = response[i].cid;
                   prd = response[i].date;
                 }
                 $('#daysCount-' + ccid).html(wcnt);
               }
             } else {
               $('.daysCount-itag').addClass('hidden');
               $('.daysCount').html('0');
             }
           }
         });
         if (dpid != '%') {
           for (var i = 1; i <= 7; i++) {
             $('#ods-td-' + i).html("");
           }
           $.ajax({
             url: "loaddata.php",
             type: "POST",
             dataType: "JSON",
             data: {
               action: 'OtherDepotSupportData',
               userid: userid,
               startdate: startdate,
               enddate: enddate,
               dpid: dpid
             },
             success: function(response1) {
               if (response1) {
                 var response = response1;
                 var len = 0;
                 if (response.length > 0) {
                   len = response.length;
                   var dts123 = response[0].date;
                   const span = [];
                   for (var i = 0; i <= 7; i++) {
                     span[i] = "";
                   }
                   var odstdcn = 0;
                   for (var i = 0; i < len; i++) {
                     var uniqid = response[i].uniqid;
                     if (response[i].wave) {
                       var wave = response[i].wave;
                     } else {
                       var wave = '';
                     }
                     $('#wave').val(response[i].wave);
                     $('#route').val(response[i].route);
                     $('#contractor_status').val(1);
                     $('#no_payment').removeClass('hidden');
                     $('#alertmsg').addClass('hidden');

                     const date1 = new Date(startdate);
                     const date2 = new Date(dts123);
                     const diffTime = Math.abs(date2 - date1);
                     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                     odstdcn = odstdcn + diffDays;

                     span[odstdcn] += '<div id="' + uniqid + '@d" onclick="DetailModel(this,' + response[i].cid + ');"><input type="hidden" id="status_' + uniqid + '" name="status_' + uniqid + '" value="1"><input type="hidden" id="ext_status_' + uniqid + '" name="ext_status_' + uniqid + '" value="' + response[i].ext_status_id + '"><input type="hidden" id="othDepot_' + uniqid + '" name="othDepot__' + uniqid + '" value="' + response[i].otherDepot + '"><div><div class="border rounded d-inline-block text-grey-darker position-relative h-full w-full px-1 bg-gray-300 border-gray-600" style="height: 62px;"><div class="d-flex align-items-center justify-content-between pr-4"><div><strong class="text-grey-darkest">' + wave + '</strong></div><div></div></div><div class="d-flex align-items-center justify-content-between pr-4"><div></div><div></div></div><div><div class="whitespace-no-wrap">' + response[i].cname + '</div><div style="width:150px;  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><strong>' + response[i].otherDptName + '</strong></div></div><div class="whitespace-no-wrap overflow-hidden text-overflow-ellipsis"></div><div></div></div></div></div></div>';
                     startdate = dts123;
                     dts123 = response[i].date;
                   }
                   for (var i = 1; i <= 7; i++) {
                     $('#ods-td-' + i).html(span[i]);
                   }
                 }
               }
             }
           });
         }

         RotaCountSceduling(userid, startdate, enddate);
         var stDate = new Date(Date.parse(startdate.replace(/[-]/g, '/')));
         var wno = stDate.getWeek();
         stDate = stDate.toLocaleDateString("en-US");
         var edDate = new Date(Date.parse(enddate.replace(/[-]/g, '/')));
         edDate = edDate.toLocaleDateString("en-US");
         $('#reportrange span').html(stDate + ' - ' + edDate + '  Week ' + wno);
       }

       function RotaCountSceduling(uid, startdate, enddate) {
         var depotid = $('#depot_id').val();
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'RotaCountSceduling',
             userid: uid,
             startdate: startdate,
             enddate: enddate,
             depotid: depotid
           },
           dataType: 'text',
           success: function(data) {
             $('#rotadayoffCount').html(data);
           }
         });
       }

       function Needed(data) {
         data.id = data.id.split('@')[0];
         var uniqid = data.id;
         var depotid = uniqid.split('_')[0];
         var userid = uniqid.split('_')[1];
         var date = uniqid.split('_')[2];

         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'RotaContractorSchedule',
             userid: userid,
             uniqid: uniqid,
             date: date,
             depotid: depotid,
             value: data.value
           },
           dataType: 'JSON',
           success: function(result) {
             if (result.status == 1) {
               myAlert(result.title + "@#@" + result.message + "@#@success");
             } else {
               myAlert(result.title + "@#@" + result.message + "@#@danger");
             }
           }
         });
       }

       function assignPaymentType(data) {
         var paymenttype = $('#payment_id').val();
         var uniqid = $('#tddataid').val();
         var date = uniqid.split('_')[4];
         var cid = uniqid.split('_')[2];
         var value = 1;
         var othDpt = 0;
         if ($("#otherDepot").val()) {
           othDpt = $("#otherDepot").val();
         }

         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'InsertRotaPaymentTypeData',
             userid: userid,
             id: cid,
             paymenttype: paymenttype,
             date: date,
             value: value,
             othDpt: othDpt
           },
           dataType: 'json',
           success: function(data) {
             if (data.status == 1) {
               ShowPaymentTypeData(uniqid, cid);
               myAlert("Assign @#@ Payment data assign successfully.@#@success");
             }
           }
         });
       }

       function Timemodal(date1) {
         var dte = new Date(date1);
         var modaldate = monthNames[dte.getMonth()] + " " + dte.getDate() + " , " + dte.getFullYear();
         $('#modaldt').html(modaldate);
         $('#TimeModel').modal('show');
         var depotid = $('#depot_id').val();
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'ShowTimeOfDriverRotaData',
             date: date1,
             userid: userid,
             depotid: depotid
           },
           dataType: 'text',
           success: function(data) {
             $('#timedata').html(data);
           }
         });

       }

       function IndexPaymentTypeDataFun(cid, date) {
         $.ajax({
           type: "POST",
           url: "loaddata.php",
           data: {
             action: 'IndexPaymentTypeData',
             id: cid,
             dt: date
           },
           dataType: 'json',
           success: function(data) {
             if (data.paidFlag == 1) {
               $("#scheduleFunc").hide();
               $("#no_payment").hide();
               $("#scheduleModalFooter").hide();
             } else {
               $("#scheduleFunc").show();
               $("#no_payment").show();
               $("#scheduleModalFooter").show();
             }
             $('#wave').val(data.wave);
             $('#route').val(data.route);
             $('#cntname').html(data.name);
             $('#cnttype').html(data.type);
             $('#payment_id').html(data.options);
             $('#invoicebtn').html('<a href="contractor_invoice.php?invkey=' + data.invc_key + '" target="_blank" class="btn btn-primary btn-sm">View Invoice</a>');
             $('#payment_id').select('refresh');


           }
         });
       }
     </script>
 </body>

 </html>