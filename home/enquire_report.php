<?php

if(session_status() === PHP_SESSION_NONE)
{
  session_start();
}

 
require('header.php');
$pgSt = 0;
if(!in_array(81,$pageIDs) && !in_array(82,$pageIDs))
{ 
	echo '<script > location.replace("unAuthorised.php"); </script>';	
}else{
	$pgSt=1;
} 


$_SESSION['whr']="1";

require('config.php');

$status = '';
$message = '';
$where='';
$brand_where='';
$id='';
$invt="class='active'";
$brnd="";
$invt_tab="active";
$brnd_tab="";
$from_date="";
$to_date="";
$from_date1="";
$to_date1="";

			if(isset($_GET['ok']))
			{
				$from_date= $_GET['from_datepicker']?date('d-m-Y',strtotime($_GET['from_datepicker'])):null;
				if(!$from_date==null)
					$from=$from_date;

				$to_date= $_GET['to_datepicker']?date('d-m-Y',strtotime($_GET['to_datepicker'])):null;
				if(!$to_date==null)
					$to=$to_date;
				$id='franchise';
				$_SESSION['whr']="0";
				if(!@$from== null && !@$to == null)
				{
					if(strtotime($_GET['from_datepicker']) < strtotime($_GET['to_datepicker']))
					{
						$_SESSION['whr']="AND enquire.enquite_date BETWEEN '$from' AND '$to'";
					}
				}
				$invt="class='active'";
				$brnd="";
				$invt_tab="active";
				$brnd_tab="";
			}
			if(isset($_GET['ok1']))
			{
				$from_date1= $_GET['from_datepicker']?date('d-m-Y',strtotime($_GET['from_datepicker'])):null;
				if(!$from_date1==null)
					$from1=$from_date1;

				$to_date1= $_GET['to_datepicker']?date('d-m-Y',strtotime($_GET['to_datepicker'])):null;
				if(!$to_date1==null)
					$to1=$to_date1;
				$id='franchise';
				$_SESSION['whr']="0";
				if(!@$from1== null && !@$to1 == null)
				{
					if(strtotime($_GET['from_datepicker']) < strtotime($_GET['to_datepicker']))
					{
						$_SESSION['whr']="AND enquire.enquite_date BETWEEN '$from1' AND '$to1'";
					}
				}
				$invt="";
				$brnd="class='active'";
				$invt_tab="";
				$brnd_tab="active";
			}
			if(isset($_GET['clear']))
			{
				$_SESSION['whr']="1";
				$to_date="";
				$from_date="";
			}
			if(@$_GET["del"] == "del")
			{
				$id = mysqli_real_escape_string($db,base64_decode($_GET['id']));
				$sql = "update enquire set status = 'Deactive' where id = '$id'";
				if ($db->query($sql) === TRUE) {
					$status = 'success';
					$message = 'Enquire deleted successfully !';
				} else {
					$status = 'fail';
					$message = 'Something went wrong !';
				}
			}


?>

<link href="admin_assest/plugins/datepicker/datepicker3.css" rel="stylesheet">

<style>
	.dataTables_wrapper { padding: 0px 30px 0px 30px !important; }
#exTab1 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

#exTab2 h3 {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}

/* remove border radius for the tab */

#exTab1 .nav-pills > li > a {
  border-radius: 0;
}

/* change border radius for the tab , apply corners on top*/

#exTab3 .nav-pills > li > a {
  border-radius: 4px 4px 0 0 ;
}

#exTab3 .tab-content {
  color : white;
  background-color: #428bca;
  padding : 5px 15px;
}
</style>
 <link href='DataTables/datatables.min.css' rel='stylesheet' type='text/css'>

<!-- jQuery Library -->
<!-- <script src="jquery-3.3.1.min.js"></script> -->
<!-- <script src="admin_assest/plugins/jQuery/jQuery-2.2.0.min.js"></script> -->

<!-- Datatable JS -->

<div class="content-wrapper">
    <section class="content">
		<div class="row">
			<div class="col-md-12">
				<?php 	if($status == 'success')
							{
								$status = 'success';
								$message = 'Enquiry deleted successfully !';
							} else if($status == 'fail') {
								$status = 'fail';
								$message = 'Something went wrong !';
							}
				if($status == 'success')
				{ ?>
					<div class="alert alert-success" role="alert">
					<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<strong><?php echo $message; ?></strong> 
					</div>
			  <?php } ?>
			  <?php if($status == 'fail')
				{ ?>
					<div class="alert alert-danger" role="alert">
					<button type="button" class="close" data-dismiss="alert">
					<span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<strong><?php echo $message; ?></strong> 
					</div>
			  <?php }?>		
			</div>		
		</div>
		<input type="hidden" name="pgid" id="pgid" value="<?php echo $pgSt;?>">
		<div class="modal fade" id="msgModal" role="dialog">
						
					</div>
		<div class="row">
			<div class="col-md-12">
					  <div class="box">
						<div class="box-header">
						  <h3 class="box-title"><i class="fa fa-fw fa-angle-double-right"></i>  Enquire Reports</h3>
						</div>
						<div class="box-body">
							
							<div id="exTab2">	
								<ul class="nav nav-tabs">
									<li <?php echo $invt;?>><a  href="#1" data-toggle="tab">Investor </a></li>
									<li <?php echo $brnd;?>><a href="#2" data-toggle="tab">Brand</a></li>
								</ul>
					
							<div class="tab-content">
								<div class="tab-pane <?php echo $invt_tab;?>" id="1">

								
					<form method="get" enctype="multipart/form-data">
						<table class='table table-striped'>
							<tr>
								<td width="20%">
									<div class="input-group date">
										<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" id="datepicker" name="from_datepicker" placeholder="From Date" value="<?= @$from_date ?>">
									</div>
								</td>
								<td width="20%">
									<div class="input-group date">
										<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" id="datepicker1" name="to_datepicker" placeholder="To Date" value="<?= @$to_date ?>">
									</div>
								</td>

								<td width="5%">
									<button class="btn btn-primary" type="submit" name="ok">Filter</button>
								</td>
								<td width="5%">
									<button class="btn btn-primary" type="submit" name="clear">Clear Filter</button>
								</td>
								<td width="50%">
									
								</td>
							</tr>
						</table>
					</form>

					
								<div class="table-responsive">
                                                                   
                                                               
									<table id="empTable" class=" display dataTable table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
										<thead>
											<tr role="row">
												<th data-orderable="false">Sno</th>
												<th>Date</th>
												<th>Name</th>
												<th>Email</th>
												<th>Mobile</th>
												<th data-orderable="false">City</th>
												<th>Brand Name</th>
												<th data-orderable="false">Investment</th>
												<th data-orderable="false">Message</th>
												<th data-orderable="false">Assign To Employee</th>
												<th data-orderable="false">Action</th>
											</tr>
										</thead>
										
									</table>
								</div>		
																	
								</div>
								
				<div class="tab-pane <?php echo $brnd_tab;?>" id="2">	
					<form class="form2" method="get" enctype="multipart/form-data"  action="#">
						<table class='table table-striped'>
							<tr>
								<td width="20%">
									<div class="input-group date">
										<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" id="datepicker2" name="from_datepicker" placeholder="From Date"  value="<?= @$from_date1 ?>">
									</div>
								</td>
								<td width="20%">
									<div class="input-group date">
										<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker" id="datepicker3" name="to_datepicker" placeholder="To Date"  value="<?= @$to_date1 ?>">
									</div>
								</td>
								<td width="5%">
									<button class="btn btn-primary" type="submit" name="ok1">Filter</button>
								</td>
								<td width="5%">
									<button class="btn btn-primary" type="submit" name="clear">Clear Filter</button>
								</td>
								<td width="50%">
									
								</td>
							</tr>
						</table>
					</form>
									<table id="empTable2" class=" display dataTable table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
										<thead>
											<tr role="row">
												<th data-orderable="false">Sno</th>
												<th>Date</th>
												<th>Name</th>
												<th>Email</th>
												<th>Mobile</th>
												<th>Brand Name</th>
												<th>Brand Origin</th>
												<th data-orderable="false">Message</th>
												<th data-orderable="false">Action</th>
											</tr>
										</thead>
									</table>								
									</div>
								</div>
							</div>						
											
						</div>
					  </div>
					  <!-- /.box -->
					</div>		
		</div>
	</section>
</div>	

<?php
require('footer.php');
?>

	<script>
$(document).ready(function(){
	var pgid = document.getElementById("pgid").value;
		$('#empTable').DataTable({
	                'processing': true,
	                'serverSide': true,
	                'serverMethod': 'post',
	                'pageLength': 25,
	                'dom': '<"pull-left"f><"pull-right"l>tip',
	                'ajax': {
	                    'url':'ajaxFetchEnquireReport.php',
	                    'data': {
					        'pgid': pgid
					    }
	                },
	                'columns': [
	                    { data: 'Sno' },
	                    { data: 'enquite_date' },
	                    { data: 'name' },
	                    { data: 'email' },
	                    { data: 'mobile' },
	                    { data: 'city' },
	                    { data: 'brand_name' },
	                    { data: 'investment' },
	                    { data: 'Message' },
	                    { data: 'emp_name' },
	                    { data: 'Action' }
	                ]
	            });
		$('#empTable2').DataTable({
	                'processing': true,
	                'serverSide': true,
	                'serverMethod': 'post',
	                'pageLength': 25,
	                'dom': '<"pull-left"f><"pull-right"l>tip',
	                'ajax': {
	                    'url':'ajaxFetchEnquireReportBrand.php',
	                    'data': {
					        'pgid': pgid
					    }
	                },
	                'columns': [
	                    { data: 'Sno' },
	                    { data: 'enquite_date' },
	                    { data: 'name' },
	                    { data: 'email' },
	                    { data: 'mobile' },
	                    { data: 'brand_name' },
	                    { data: 'brand_origin' },
	                    { data: 'Message' },
	                    { data: 'Action' }
	                ]
	            });
 });
		function getMsg(enqId)
		{
			$.ajax({
		        url : 'getEnqMsg.php?enqId='+enqId, 	
		        success: function(result3){
		        	if(result3)
		        	{
		        		document.getElementById("msgModal").innerHTML = result3;
		        		$("#msgModal").modal();
		        	}
		        },
		        error: function (jqXHR, exception) {
			        var msg = '';
			        if (jqXHR.status === 0) {
			            msg = 'Not connect.\n Verify Network.';
			        } else if (jqXHR.status == 404) {
			            msg = 'Requested page not found. [404]';
			        } else if (jqXHR.status == 500) {
			            msg = 'Internal Server Error [500].';
			        } else if (exception === 'parsererror') {
			            msg = 'Requested JSON parse failed.';
			        } else if (exception === 'timeout') {
			            msg = 'Time out error.';
			        } else if (exception === 'abort') {
			            msg = 'Ajax request aborted.';
			        } else {
			            msg = 'Uncaught Error.\n' + jqXHR.responseText;
			        }
			        alertmsg(msg);
			    },
	        });
		}
		function idChanged(sel)
		{

        //Selected value
	        
	        var employee_id = sel.options[sel.selectedIndex].value;;
	        var emp_name = sel.options[sel.selectedIndex].text;
	        var user_id = sel.getAttribute('userid');
	         

	        $.ajax({
	        url : 'update_employee1.php?employee_id='+employee_id+'&userid='+user_id, 	
	        success: function(result1){
	        	if(result1=='done')
	        	{
				        $.ajax({
				                url : 'getEmployeeAdminId1.php?employee_id='+employee_id, 	
				                success: function(result2)
				                {
				                        var aid;
				                        if(result2 != "NO")
				                        {
				                                aid = result2;
				                                adminMsg("New Task@#@A new costumer is assigned to you.@#@success",aid,user_id);
				                                // alert("Lead has been assigned successfully!!!");
				                                // alert("Lead has been assigned to "+emp_name+" successfully!!!");
				                                adminMsg("Lead Distributed@#@Lead has been assigned to <b>"+emp_name+"</b> successfully!!!@#@danger",1,user_id);


				                //                 $.toast({
										          //   heading: "Lead Distributed",
										          //   text: "Lead has been assigned to <b>"+emp_name+"</b> successfully!!!",
										          //   position: 'top-right',
										          //   loaderBg:'#ff6849',
										          //   icon: 'error',
										          //   hideAfter: 4000
										          // });

				                                
				                        }else{
				                        	alert("Error : "+result2);
				                        }
				                },
							    error: function (jqXHR, exception) {
							        var msg = '';
							        if (jqXHR.status === 0) {
							            msg = 'Not connect.\n Verify Network.';
							        } else if (jqXHR.status == 404) {
							            msg = 'Requested page not found. [404]';
							        } else if (jqXHR.status == 500) {
							            msg = 'Internal Server Error [500].';
							        } else if (exception === 'parsererror') {
							            msg = 'Requested JSON parse failed.';
							        } else if (exception === 'timeout') {
							            msg = 'Time out error.';
							        } else if (exception === 'abort') {
							            msg = 'Ajax request aborted.';
							        } else {
							            msg = 'Uncaught Error.\n' + jqXHR.responseText;
							        }
							        alertmsg(msg);
							    },
				                });
				    }else
				    {
				    	alert("Error : "+result1);
				    }
	        },
		    error: function (jqXHR, exception) {
		        var msg = '';
		        if (jqXHR.status === 0) {
		            msg = 'Not connect.\n Verify Network.';
		        } else if (jqXHR.status == 404) {
		            msg = 'Requested page not found. [404]';
		        } else if (jqXHR.status == 500) {
		            msg = 'Internal Server Error [500].';
		        } else if (exception === 'parsererror') {
		            msg = 'Requested JSON parse failed.';
		        } else if (exception === 'timeout') {
		            msg = 'Time out error.';
		        } else if (exception === 'abort') {
		            msg = 'Ajax request aborted.';
		        } else {
		            msg = 'Uncaught Error.\n' + jqXHR.responseText;
		        }
		        alertmsg(msg);
		    },
	        });
		}
		 $(document).ready(function(){

			$('.brand_name').change(function(){ 
				var brand_id = $(this).val();
				var book_id = $(this).closest('td').find('.book_id').val();
				
	                $.ajax({
	                 url : 'assign_appoinment.php?brand_id='+brand_id+'&book_id='+book_id,    
	                    success: function(result){
	                      alert(result);
	                    }
	                });				
				
				
			});





			
	       
			$('.employee_name').change(function(){
        //Selected value
       //  alert("called");
       //  var employee_id = $(this).val();
       //  var emp_name = $(this).find('option:selected').text();
       //  var user_id = $(this).attr('userid');
       //  $.ajax({
       //  url : 'update_employee1.php?employee_id='+employee_id+'&userid='+user_id, 	
       //  success: function(result){
       //  	if(result=='done')
       //  	{
			    //     $.ajax({
			    //             url : 'getEmployeeAdminId1.php?employee_id='+employee_id, 	
			    //             success: function(result)
			    //             {
			    //                     var aid;
			    //                     if(result != "NO")
			    //                     {
			    //                             aid = result;
			    //                             adminMsg("New Task@#@A new costumer is assigned to you.@#@success",aid,user_id);
			    //                             // alert("Lead has been assigned successfully!!!");
			    //                             // alert("Lead has been assigned to "+emp_name+" successfully!!!");
			    //                             adminMsg("Lead Distributed@#@Lead has been assigned to <b>"+emp_name+"</b> successfully!!!@#@danger",1,user_id);


			    //                             $.toast({
							// 		            heading: "Lead Distributed",
							// 		            text: "Lead has been assigned to <b>"+emp_name+"</b> successfully!!!",
							// 		            position: 'top-right',
							// 		            loaderBg:'#ff6849',
							// 		            icon: 'error',
							// 		            hideAfter: 4000
							// 		          });

			                                
			    //                     }else{
			    //                     	alert("Error : "+result);
			    //                     }
			    //             }
			    //             });
			    // }
       //  }
       //  });
        });
		});

</script>
<!-- <script src="plugins/datepicker/bootstrap-datepicker.js"> -->
</script>

   <script>
            
   $('#datepicker').datepicker({
      autoclose: true,
	});
	$('#datepicker1').datepicker({
      autoclose: true,
    });
     $('#datepicker2').datepicker({
      autoclose: true,
	});
	$('#datepicker3').datepicker({
      autoclose: true,
    });
     $('#brand_filter').on('click', function(){
    	$('#1').removeClass('active');
    	$('#2').addClass('active');

   });

	</script>
