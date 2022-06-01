<?php
include 'DB/config.php';
    $page_title = "Workforce Timesheet";
    $page_id=37;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
    	$userid = $_SESSION['userid'];
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
          padding-right: 0px;
          padding-left: 0px;
          margin-left: 30px;
        }
        .dataheader {
           background-color: rgb(255 236 230);
        }
        .cardmb2 {
           height: 8px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">

        var wid = <?php echo $id ?>;
        var userid = <?php echo $userid ?>;
        $(document).ready(function() {
            var j = 0;
            for (j = 1; j < 8; j++) {
                showdata(document.getElementById("hiddendate-" + j));
            }
            $('.fc-right').addClass('hidden');

            var date1 = '';
            $.ajax({
                type: "POST",
                url: "setTimezone.php",
                async: false, 
                data: {action : 'GetDateFunction'},
                dataType: 'text',
                success: function(data) {
                    date1=data;   
                }
            });

            $( "#exampleModal" ).on('shown.bs.modal', function(){
                $("#modalType").val("0");
                var date = new Date($('#hiddendate-1').val());
                var prweek = new Date(date.getFullYear(), date.getMonth(), date.getDate()-7);
                $('#setdates').html('');
                setdate(prweek);
            });

            $( "#exampleModal2" ).on('shown.bs.modal', function(){
                $("#modalType").val("1");
                var date = new Date($('#hiddendate-1').val());
                var nextweek = new Date(date.getFullYear(), date.getMonth(), date.getDate()+7);
                $('#setdates').html('');
                setdate(nextweek);
            });

            var date = new Date(date1);
            var currentdate = date;
            var oneJan = new Date(currentdate.getFullYear(),0,1);
            var numberOfDays = Math.floor((currentdate - oneJan) / (24 * 60 * 60 * 1000));
            var result = Math.ceil(( currentdate.getDay() + 1 + numberOfDays) / 7);
            result =result -1;
            $('#setweek').html('View Invoice for Week '+result+'');
            var wdt = $("#hiddendate-1").val();
            getInvcKey(wid,wdt,1);
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear(); 

            $('#calendar1').fullCalendar({
                header: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                theme: true,
                editable: false,
            });
            $('#calendar').fullCalendar({
                header: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                theme: true,
            });
            $('#calendar1').fullCalendar('next');
            $('#previous').click(function() {
                $('#calendar').fullCalendar('prev');
                $('#calendar1').fullCalendar('prev');

            });
            $('#next').click(function() {
                $('#calendar').fullCalendar('next');
                $('#calendar1').fullCalendar('next');

            });

            $('#todaydate').click(function() {
                var date1 = '';
                $.ajax({
                  type: "POST",
                  url: "setTimezone.php",
                  async: false, 
                  data: {action : 'GetDateFunction'},
                  dataType: 'text',
                  success: function(data) {
                    date1=data;   
                  }
                });
                var currentDate = new Date(date1);
                $('#calendar').fullCalendar('gotoDate', currentDate);
                $('#calendar1').fullCalendar('gotoDate', currentDate);
                $('#calendar1').fullCalendar('next');

            });

        });
        
        function showdata(data) {

            var hiddendate_id = data.id;
            var seq = hiddendate_id.split('-')[1];
            var hiddendate_val = data.value;
            var cls = document.getElementsByClassName("td-" + seq);
            var i;
            var allInOne = [];
            var showdF = 0;
            for (i = 0; i < cls.length; i++) {

                var chk = cls[i].id.split('-');
                var chk1 = "";
                if (chk.length > 2) {
                    chk1 = chk[3] + "-" + chk[4];
                } else {
                    chk1 = cls[i].id;
                }
                var hiddendateid = hiddendate_val + "-" + chk1;
                cls[i].setAttribute("id", hiddendateid);
                var tp = document.getElementById(hiddendateid + '').getAttribute("type");
                if (tp == "text") {
                    document.getElementById(hiddendateid + '').value = "";
                } else {
                    document.getElementById(hiddendateid + '').checked = false;
                }
                allInOne[i] = hiddendateid;
            }
            var showDate = data.value;
            $.ajax({
                url: "loaddata.php",
                type: "POST",
                dataType: "JSON",
                data: {
                    action: 'WorkforceTimesheetData',
                    uniqid: allInOne,
                    userid: wid,
                    showDate:showDate
                },
                success: function(response) {
                    if(response.status==1)
                    {
                        var data = response.statusdata;
                        var n=0;
                        for(n=0;n<data.length;n++)
                        {
                            if(data[n].value)
                            {
                                if(data[n].ischeckval == 1)
                                {
                                    var fl = false;
                                    if(data[n].value==1)
                                    {
                                        fl=true;
                                    }
                                    $("#" + data[n].uniqid).prop("checked", fl);
                                }
                                else
                                {
                                   // document.getElementById(data[n].uniqid+'').value = data[n].value;
                                    $("#" + data[n].uniqid).val(data[n].value);
                                }                          
                            }
                        }
                        showdF = 1;
                        if(response.paidFlag==1 && response.updateTimesheet==0)
                        {
                          //$(".td-"+seq).prop("disabled", true);
                          showdF = 0;
                        }
                    }else{
                        //$(".td-"+seq).prop("disabled", true);
                        showdF = 0;
                    } 
                }
            });

            return showdF;
        }

        function insertTimesheet(data, userid, rateid, number, ischeckval) {
            var str = '#hiddendate-';
            var id = str.concat(number);
            var date = $(id).val();
            var splt = (data.id).split('-')[3];
            if (Number(data.value) == data.value || splt == 0) {
                var value = data.value;
                if (ischeckval == 1 && !data.checked) {
                    value = 0;
                } else if (ischeckval == 1 && data.checked) {
                    value = 1;
                }
                var uniqnumber = date + '-' + rateid + '-' + wid;
                var startdate = $('#hiddendate-1').val();
                var enddate = $('#hiddendate-7').val();

                $.ajax({
                    url: "InsertData.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'WorkforceTimesheetForm',
                        date: date,
                        rateid: rateid,
                        uniqid: uniqnumber,
                        value: value,
                        wid: wid,
                        userid: userid,
                        ischeckval: ischeckval,
                        startdate: startdate,
                        enddate: enddate
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            myAlert(data.title + "@#@" + data.message + "@#@success");
                        } else {
                            myAlert(data.title + "@#@" + data.message + "@#@danger");
                        }
                    }
                });
            } else {
                data.value = "";
                myAlert("TYPE ERROR@#@Only float or integer are allowed!!!@#@danger");
            }
        }

        function PreviousWeek()
        {
            $('#exampleModal').modal('show');
        }

        function NextWeek()
        {
            $('#exampleModal2').modal('show');
        }

        function setdate(date1) {
            var date = date1;
            var currentdate = new Date(date);
            var oneJan = new Date(currentdate.getFullYear(),0,1);
            var numberOfDays = Math.floor((currentdate - oneJan) / (24 * 60 * 60 * 1000));
            var result = Math.ceil(( currentdate.getDay() + 1 + numberOfDays) / 7);
            var week = [];
            var set = ['<th style="width: 35%; vertical-align: middle;padding-left: 20px;" class="border-right bg-white"><span class="align-items-right col-md-2"><button  class="btn btn-secondary" onclick="PreviousWeek();">Previous</button><button  class="btn btn-secondary" onclick="NextWeek();">Next</button></span><a href="workforce_invoice.php?invkey=" class="btn btn-secondary btn- ml-2" small="small" target="_blank" id="setweek">View Invoice for Week '+result+'</a></th>'];
            var monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var i;
            // for (i = 1; i <= 7; i++) {
            //     var first = date.getDate() - date.getDay() + (i - 1);
            //     date.setDate(first);
            //     var day = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate();
            //     var Formated_day = date.getDate() + " " + monthNames[date.getMonth()];
            //     week.push(day);
            //     set.push('<th class="text-center"><input type="hidden" id="hiddendate-' + i + '" name="hiddendate" class="th" onchange="showdata(this);" value=' + day + '><span id="dayname-' + i + '">' + days[date.getDay()] + '</span><br><small><span id="day-' + i + '">' + Formated_day + '</span></small></th>');
            // }
            for (i = 1; i <= 7; i++) 
            {
                  var first = date.getDate() - date.getDay() + (i-1); 
                  date.setDate(first);
                  //var day = date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
                  var pmon = (date.getMonth()+1)+'';
                  var pdat = date.getDate()+'';
                  var day = date.getFullYear()+"-"+pmon.padStart(2,'0')+"-"+pdat.padStart(2,'0');
                  var Formated_day = date.getDate() + " " + monthNames[date.getMonth()];
                  week.push(day);
                  set.push('<th class="text-center"><input type="hidden" id="hiddendate-'+i+'" name="hiddendate'+i+'" class="th" onchange="showdata(this);" value='+day+'><span id="dayname-'+i+'">'+days[date.getDay()]+'</span><br><small><span id="day-'+i+'">'+Formated_day+'</span></small></th>');
            }
            $('#setdates').html(set);
            for (j = 1; j < 8; j++) {
                showdata(document.getElementById("hiddendate-" + j));
            }
            
            if($("#modalType").val()==0)
            {
                $("#exampleModal").modal('toggle');
            }else{
                $("#exampleModal2").modal('toggle');
            }
            var wdt = $("#hiddendate-1").val();
            getInvcKey(wid,wdt,1);
        }

         function getInvcKey(wid,cdate,utype)
        {
            $.ajax({
                  type: "POST",
                  url: "loaddata.php",
                  async: false, 
                  data: {action : 'getInvcKey',id:wid,cdate:cdate,utype:utype},
                  dataType: 'JSON',
                  success: function(data) {
                    $('#setweek').attr("href", "workforce_invoice.php?invkey="+data.invcKey);
                    $("#weekTotalAmount").html("£ "+data.weekTotal);
                  }
              });
        }
    </script>
</head>

<body class="skin-default-dark fixed-layout">
<?php include('loader.php'); ?>
<div id="main-wrapper">
<?php include('header.php'); ?>
   <div class="page-wrapper">
        <div class="container-fluid">
            <main class="container-fluid  animated">


                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" style="width:fit-content;">
                    <div class="modal-content">
                      <div class="modal-body">
                        <img src="loader.gif" alt="loading" style="position: relative;"  />
                      </div>
                    </div>
                  </div>
                </div>
<input type="hidden" id="modalType">
                <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document" style="width:fit-content;">
                    <div class="modal-content">
                      <div class="modal-body">
                        <img src="loader.gif" alt="loading" style="position: relative;"  />
                      </div>
                    </div>
                  </div>
                </div>


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
    <div class="col-md-3"></div>
    <div class="col-md-4">
        <button class="prev-day btn btn-outline-secondary" id="previous"><i class="fa fa-angle-left" aria-hidden='true'></i></button>
        <button class="today-date btn btn-outline-secondary" type="button" id="todaydate">Today</button>
        <button class='next-day btn btn-outline-secondary' id="next" ><i class='fa fa-angle-right' aria-hidden='true'></i></button>
    </div>
    <div class="col-md-2"></div>
</div>
                          
<div class="row">
     <div class="col-md-4">
    	  <div class="card">
    	      <div class="card-body b-l calender-sidebar">
    	          <div id="calendar"></div>
    	      </div>
    	  </div>
     </div>
     <div class="col-md-4">
      <div class="card">
          <div class="card-body b-l calender-sidebar">
              <div id="calendar1"></div>
          </div>
      </div>
  </div>
     <div class="col-md-4">
    		<div class="row">
    			<div class="card datacard col-md-5" style="margin-top: 110px;">
    			  <div class="card-header dataheader">
    			      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Working Days</h6>
    			  </div>
    			  <div class="card-body">
    			    <h5 class="m-0" style="font-weight: 700;">0</h5>
    			  </div>
    			</div>
    			<div class="card datacard col-md-5" style="margin-top: 110px;">
    			  <div class="card-header dataheader">
    			      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Payments</h6>
    			  </div>
    			  <div class="card-body">
    			    <h5 class="m-0" style="font-weight: 700;">£ 0.00</h5>
    			  </div>
    			</div>
    		</div>
    		<div class="row">
    			<div class="card datacard col-md-5">
    			  <div class="card-header dataheader">
    			      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Daily Rate</h6>
    			  </div>
    			  <div class="card-body">
    			    <h5 class="m-0" style="font-weight: 700;">£0.00</h5>
    			  </div>
    			</div>
    			<div class="card datacard col-md-5">
    			  <div class="card-header dataheader">
    			      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Loans Remaining</h6>
    			  </div>
    			  <div class="card-body">
    			    <h5 class="m-0" style="font-weight: 700;">£0.00</h5>
    			  </div>
    			</div>
    		</div>    
     </div>
</div>

<div class="row">
	<div class="table-responsive-lg">
    <table class="table w-100 table-hover table-sm table-vcente" small="small" centered="centered" hover="hover">
        <thead>
            <tr id="setdates">
            	<th style="width: 35%; vertical-align: middle;padding-left: 20px;" class="border-right bg-white">
                 <span class="align-items-right col-md-2">
                    <button  class="btn btn-secondary" onclick="PreviousWeek();">Previous</button>
                    <button  class="btn btn-secondary" onclick="NextWeek();">Next</button>
                    <a href="#" class="btn btn-secondary btn- ml-2" small="small" target="_blank" id="setweek"></a>
                 </span>    
                </th>
            	<?php
            		$i;
            		for($i=1;$i<=7;$i++)
            		{
            			?>
            			<th class="text-center">
            				<input type="hidden" id="hiddendate-<?php echo $i;?>" name="hiddendate" class="th" onchange="showdata(this);">
		                    <span id='dayname-<?php echo $i;?>'></span>
		                    <br>
		                    <small>
		                        <span id='day-<?php echo $i;?>'></span>
		                    </small>
		                </th>
            			<?php
            		}
            	?>
            </tr>
            <tr>
                <th style="width: 35%;" class="vertical-center">
                	<span class="custom-control custom-checkbox">
                        <label class="custom-control custom-checkbox m-b-0">
                            <input type="checkbox" class="custom-control-input">
                            <span class="custom-control-label">Show inactive rates</span>
                        </label>
                    </span>
                </th>

                <?php
            		$k;
            		for($k=1;$k<=7;$k++)
            		{
            			?>
            			<th class="text-center" style="vertical-align: top">
                            <input type="text" class="form-control border-grey-base text-center td-<?php echo $k;?>" id="0-<?php echo $id; ?>" placeholder="Route" wire:model.debounce.500ms="routes.2021-04-25" onfocusout="insertTimesheet(this,<?php echo $userid?>,0,<?php echo $k?>,2);">
                        </th>
            			<?php
            		}
            	?>
               
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left" style="background: #f5f5f5; text-transform: uppercase;" colspan="8">
                    <strong>Standard</strong>
                </td>
            </tr>
            <?php
            		$mysql = new Mysql();
                    $mysql -> dbConnect();
                    $query = "SELECT * FROM `tbl_paymenttype` WHERE `isdelete`= 0 AND `isactive`= 0 AND `type`=1 AND `applies`=2 AND depot_id IN (".$cntresult['depot'].",0)";
                    $raterow =  $mysql -> selectFreeRun($query);
                    while($result = mysqli_fetch_array($raterow))
                    {
                        $blocktime='';
                        if($result['blockoftime'])
                        {
                            $blocktime = $result['blockoftime'].' Hour';
                        }
                    ?>
            <tr>
                <td class="text-left text-sm" style="font-size: 11px; border-right:1px solid #ccc;" width="35%">
                    <div>
                        <strong><?php echo $result['name'].' '.$blocktime; ?></strong>
                        <span class="text-muted">(£<?php echo $result['amount'];?>)</span>
                    </div>
                </td>

                <?php
                    $j;
            		for($j=1;$j<=7;$j++)
            		{
            			if($result['paymentperstop']==1)
            			{
            				?>
            				<td class="text-center border">
            					<span class="custom-control custom-checkbox">
                                    <label class="custom-control custom-checkbox m-b-0">
                                      <input type="checkbox" id="<?php echo $result['id']."-".$id; ?>" class="custom-control-input td-<?php echo $j;?>" onchange="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,1);" value="1">
                                        <span class="custom-control-label"></span>
                                    </label>
                                </span>
			                </td>
            				<?php
            			}
            			else
            			{
            				?>
            				<td class="text-center border">
			                  <input type="text" class="border rounded text-center td-<?php echo $j;?>" id="<?php echo $result['id']."-".$id; ?>" style="width:60px" wire:model="values.59.2021-04-25" onfocusout="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,2);">
			                </td>
            				<?php
            			}
            			?>     
            			<?php
            		}
                ?>
            </tr>
                <?php
                }
                $mysql -> dbDisconnect();
            ?>

             <tr>
                <td class="text-left" style="background: #f5f5f5; text-transform: uppercase;" colspan="8">
                    <strong>BONUS</strong>
                </td>
            </tr>
            <?php
                    $mysql = new Mysql();
                    $mysql -> dbConnect();
                    $query = "SELECT * FROM `tbl_paymenttype` WHERE `isdelete`= 0 AND `isactive`= 0 AND `type`=2 AND `applies`=2 AND depot_id IN (".$cntresult['depot'].",0)";
                    $raterow =  $mysql -> selectFreeRun($query);
                    while($result = mysqli_fetch_array($raterow))
                    {
                        $blocktime='';
                        if($result['blockoftime'])
                        {
                            $blocktime = $result['blockoftime'].' Hour';
                        }
                    ?>
            <tr>
                <td class="text-left text-sm" style="font-size: 11px; border-right:1px solid #ccc;" width="35%">
                    <div>
                        <strong><?php echo $result['name'].' '.$blocktime; ?></strong>
                        <span class="text-muted">(£<?php echo $result['amount'];?>)</span>
                    </div>
                </td>

                <?php
                    $j;
                    for($j=1;$j<=7;$j++)
                    {
                        if($result['paymentperstop']==1)
                        {
                            ?>
                            <td class="text-center border">
                                <span class="custom-control custom-checkbox">
                                    <label class="custom-control custom-checkbox m-b-0">


<input type="checkbox" id="<?php echo $result['id']."-".$id; ?>" class="custom-control-input td-<?php echo $j;?>" onchange="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,1);" value="1">


                                        <span class="custom-control-label"></span>
                                    </label>
                                </span>
                            </td>
                            <?php
                        }
                        else
                        {
                            ?>
                            <td class="text-center border">

<input type="text" class="border rounded text-center td-<?php echo $j;?>" id="<?php echo $result['id']."-".$id; ?>" style="width:60px" wire:model="values.59.2021-04-25" onfocusout="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,2);">

                            </td>
                            <?php
                        }
                        ?>     
                        <?php
                    }
                ?>
            </tr>
                <?php
                }
                $mysql -> dbDisconnect();
            ?>

             <tr>
                <td class="text-left" style="background: #f5f5f5; text-transform: uppercase;" colspan="8">
                    <strong>DEDUCTION</strong>
                </td>
            </tr>
            <?php
                    $mysql = new Mysql();
                    $mysql -> dbConnect();
                    $query = "SELECT * FROM `tbl_paymenttype` WHERE `isdelete`= 0 AND `isactive`= 0 AND `type`=3 AND `applies`=2 AND depot_id IN (".$cntresult['depot'].",0)";
                    $raterow =  $mysql -> selectFreeRun($query);
                    while($result = mysqli_fetch_array($raterow))
                    {
                        $blocktime='';
                        if($result['blockoftime'])
                        {
                            $blocktime = $result['blockoftime'].' Hour';
                        }
                    ?>
            <tr>
                <td class="text-left text-sm" style="font-size: 11px; border-right:1px solid #ccc;" width="35%">
                    <div>
                        <strong><?php echo $result['name'].' '.$blocktime; ?></strong>
                        <span class="text-muted">(£<?php echo $result['amount'];?>)</span>
                    </div>
                </td>

                <?php
                    $j;
                    for($j=1;$j<=7;$j++)
                    {
                        if($result['paymentperstop']==1)
                        {
                            ?>
                            <td class="text-center border">
                                <span class="custom-control custom-checkbox">
                                    <label class="custom-control custom-checkbox m-b-0">


<input type="checkbox" id="<?php echo $result['id']."-".$id; ?>" class="custom-control-input td-<?php echo $j;?>" onchange="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,1);" value="1">


                                        <span class="custom-control-label"></span>
                                    </label>
                                </span>
                            </td>
                            <?php
                        }
                        else
                        {
                            ?>
                            <td class="text-center border">

<input type="text" class="border rounded text-center td-<?php echo $j;?>" id="<?php echo $result['id']."-".$id; ?>" style="width:60px" wire:model="values.59.2021-04-25" onfocusout="insertTimesheet(this,<?php echo $userid?>,<?php echo $result['id']?>,<?php echo $j?>,2);">

                            </td>
                            <?php
                        }
                        ?>     
                        <?php
                    }
                ?>
            </tr>
                <?php
                }
                $mysql -> dbDisconnect();
            ?>
    </tbody></table>
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

</body>
</html>