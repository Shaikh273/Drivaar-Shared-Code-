<?php
include 'DB/config.php';
    $page_title = "Contractor Details";
    $page_id=5;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    // if(isset($_GET['cpcid']) && !empty($_GET['cpcid']))
    // {
    //   $_SESSION['cid'] = base64_decode($_GET['cpcid']);
    // }else
    // {
    //   header("location: contractor.php");
    // }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['cid'];
        //$id=$_GET['cid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        // $query = "SELECT * FROM `tbl_contractor` WHERE `id`=".$id;
        $query = "SELECT c.*,(select c1.`name` from `tbl_country` c1 where c1.`id`=c.`passport_country`) as passport_country_name, (select c2.`name` from `tbl_country` c2 where c2.`id`=c.`drivinglicence_country`) as drivinglicence_country_name FROM `tbl_contractor` c WHERE c.`id` = ".$id;
        $row =  $mysql -> selectFreeRun($query);
        $cntresult = mysqli_fetch_array($row);
        $datashow = $cntresult['isactive'];


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
                                        <button type="button" class="btn btn-info" id="editdetail"><i class="fas fa-pencil-alt"></i> Edit Details</button>
                                  </a>
                               </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col">
                                  <div class="d-flex align-items-center">
                                      <div class="mr-2">
                                          <span class="label label-success" id="active">Active</span>
                                          <span class="label label-danger" id="deactive">Deactive</span>
                                          <span class="label label-info" id="onboarding">Onboarding</span>
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
                                                <input type="checkbox" class="custom-control-input" name="onboard" id="onboard-<?php echo $result['id'];?>" onchange="SaveOnboard(this,<?php echo $result['id'];?>);" value="1">
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
                              <div class="right-column">
                                  <div class="px-md-3">             
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                     <h6 class="mb-2 cardmb2"><i class="fas fa-phone-volume"></i> Contact Details</h6>
                                                </div>     
                                                <div class="card-body">
                                                    <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                      <dt class="col-md-5 mb-2 text-grey-dark">Email</dt>
                                                      <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                          <?php echo $cntresult['email'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Phone</dt>
                                                      <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                          <?php echo $cntresult['contact'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Associate ID</dt>
                                                      <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                          <?php echo $cntresult['employee_id'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Date of Birth</dt>
                                                      <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                          <?php echo $cntresult['dob'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">NI Number</dt>
                                                      <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                         <?php echo $cntresult['ni_number'];?>
                                                      </dd>
                                                    </dl>
                                                </div>
                                            </div>

                                            <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Passport 
                                                  <a href="contractor_passport.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                                              </div>
                                              <div class="card-body">
                                                  <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2 text-grey-dark">Issue Country:</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['passport_country_name'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Number:</dt>
                                                    <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                         <?php echo $cntresult['passport_number'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Expiry Date:</dt>
                                                    <dd class="col-md-7 mb-2" style="font-weight: 500;">
                                                        <?php echo $cntresult['passport_expirydate'];?>
                                                    </dd>
                                                  </dl>
                                              </div>
                                            </div>

                                            <div class="card datacard">  
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Driving Licence 
                                                  <a href="contractor_drivinglicence.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                                              </div>
                                              <div class="card-body">
                                                  <dl class="row mb-0" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2 text-grey-dark">Issue Country:</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        <?php echo $cntresult['drivinglicence_country_name'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Licence Number:</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        <?php echo $cntresult['drivinglicence_number'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Expiry Date:</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        <?php echo $cntresult['drivinglicence_expiry'];?>
                                                    </dd>
                                                  </dl>
                                              </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card datacard">
                                              <div class="card-header dataheader">
                                                  <h6 class="mb-2 cardmb2">Additional Information</h6>
                                              </div>            
                                              <div class="card-body">
                                                  <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2 text-grey-dark">Start Date</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        <?php echo $cntresult['insert_date'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Leave Date</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        &nbsp;
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">Company Reg.</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                        <?php echo $cntresult['company_reg'];?>
                                                    </dd>

                                                    <dt class="col-md-5 mb-2 text-grey-dark">UTR</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['utr'];?>
                                                    </dd>
                                                  </dl>
                                              </div>
                                            </div>

                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                     <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Tax Information  
                                                      <a href="contactor_tax.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
                                                      </h6>
                                                </div>  
                                                <div class="card-body">
                                                  <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                  <dt class="col-md-5 mb-2 text-grey-dark">VAT Number</dt>
                                                  <dd class="col-md-7 mb-2" style="font-weight: 500;">GB<?php echo $cntresult['vat_number'];?></dd>
                                                  </dl>
                                                </div>
                                            </div>

                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Address  
                                                      <a href="contractor_address.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
                                                      </h6>
                                                </div>            
                                                <div class="card-body">
                                                    <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                      <dt class="col-md-5 mb-2 text-grey-dark">Address Line 1</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;"><?php echo $cntresult['address'];?></dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Address Line 2</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;"><?php echo $cntresult['street_address'];?></dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">City</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;"><?php echo $cntresult['city'];?></dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">State/Province</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;"><?php echo $cntresult['state'];?></dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Postcode</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['postcode'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Country</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                          <?php echo $cntresult['country'];?>
                                                      </dd>
                                                   </dl>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                    <h6 class="mb-2 cardmb2">Payment Info</h6>
                                                </div>    
                                               <div class="card-body">
                                                  <dl class="row mb-0 mb-3" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2 text-grey-dark">Email for Invoices</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['email'];?>
                                                    </dd>
                                                   </dl>
                                               </div>
                                            </div>

                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Payment Arrears
                                                      <?php
                                                        if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][174]==1) )
                                                        {
                                                            ?>
                                                            <a href="contracter_paymentarrear.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
                                                            <?php
                                                        }
                                                      ?>
                                                    </h6>
                                                </div>
                                                <div class="card-body">                                  
                                                  <dl class="row mb-0 mb-3" style="line-height: 1.2;">
                                                    <dt class="col-md-5 mb-2 text-grey-dark">Arrears</dt>
                                                    <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                    	<?php
                                                  	    $mysql = new Mysql();
                                        						    $mysql -> dbConnect();
                                        						    $arrear =  $mysql -> selectWhere('tbl_arrears','id','=',$cntresult['arrears'],'int');
                                        						    $arrearresult = mysqli_fetch_array($arrear);
                                        						    echo $arrearresult['name'];
                                                    	?>
                                                    </dd>
                                                  </dl>
                                                </div>
                                            </div>

                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Bank Details
                                                    <a href="contractor_bankdetail.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                                                </div>    
                                                <div class="card-body">
                                                  <dl class="row mb-0" style="line-height: 1.2;">
                                                      <dt class="col-md-5 mb-2 text-grey-dark">Bank:</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['bank_name'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Account Number:</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                          <?php echo $cntresult['account_number'];?>
                                                      </dd>

                                                      <dt class="col-md-5 mb-2 text-grey-dark">Sort Code:</dt>
                                                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                         <?php echo $cntresult['sort_code'];?>
                                                      </dd>
                                                  </dl>
                                                </div>
                                            </div>


                                            <div class="card datacard">
                                                <div class="card-header dataheader">
                                                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Emergency Contact
                                                    <a href="contractor_emergency.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                                                </div>
                                                <div class="card-body">
                                                  <dl class="row mb-0" style="line-height: 1.2;">
                                                    
                                                  <dt class="col-md-5 mb-2 text-grey-dark">Name:</dt>
                                                  <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                      <?php echo $cntresult['emegency_name'];?>
                                                  </dd>

                                                  <dt class="col-md-5 mb-2 text-grey-dark">Phone:</dt>
                                                  <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                                                      <?php echo $cntresult['emegency_phone'];?>
                                                  </dd>
                                                  </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-bottom: 30px;" class="hr-dashed">
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
<script>
	var cid = <?php echo $id ?>;
	$(document).ready(function(){
   // showhideEditdetail();
		$.ajax({
	        url: 'loaddata.php',
	        type: 'POST',
	        data: {action : 'ShowcontractorOnboardingData', cid : cid},
	        dataType: 'json',
	        success: function(response1) {
	        	var response = response1;
            var len = 0;
          	if(response.length>0)
          	{
          		len = response.length;
          	}
            for(var i=0; i<len; i++)
            {
      					if(response[i].is_onboard==1)
      					{
      						document.getElementById('onboard-'+response[i].onboard_id).checked = true;
      					}  
	          }
	        }
    });
    var status = <?php echo $datashow?>;
    if(status==0)
    {
      $('#editdetail').removeAttr('disabled');
      $('#deactive').addClass('hidden');
      $('#active').removeClass('hidden');
      $('#onboarding').addClass('hidden');
    }
    else if(status==1)
    {
      $('#editdetail').attr('disabled','disabled');
      $('#deactive').removeClass('hidden');
      $('#active').addClass('hidden');
      $('#onboarding').addClass('hidden');
    }
    else if(status==2)
    {
      $('#editdetail').attr('disabled','disabled');
      $('#onboarding').removeClass('hidden');
      $('#active').addClass('hidden');
      $('#deactive').addClass('hidden');
    }
	});

	function SaveOnboard(datav,id)
	{
		if(document.getElementById('onboard-'+id).checked)
    {
        var flag =1;
    }
    else
    {
    	var flag =0;
    }
		$.ajax({
        url: 'InsertData.php',
        type: 'POST',
        data: {action : 'saveOnboard', cid : cid,onboard_id:id,is_onboard:flag},
        dataType: 'json',
        success: function(data) 
        {
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
    showhideEditdetail(); 
	}

  function showhideEditdetail()
  {
    $.ajax({
      url: 'loaddata.php',
      type: 'POST',
      data: {action : 'IscheckcontractorOnboardingData', cid : cid},
      dataType: 'json',
      success: function(data) {
        if(data.status==1)
        {
           // alert('Your status has been updated.');
            if(data.statusdata==1)
            {
              $('#editdetail').removeAttr('disabled');
              $('#deactive').addClass('hidden');
              $('#active').removeClass('hidden');
              $('#onboarding').addClass('hidden');
            }
            else if(data.statusdata==0)
            {
              $('#editdetail').attr('disabled','disabled');
              $('#deactive').removeClass('hidden');
              $('#active').addClass('hidden');
              $('#onboarding').addClass('hidden');
            }
            // else if(data.statusdata==2)
            // {
            //   $('#editdetail').attr('disabled','disabled');
            //   $('#onboarding').removeClass('hidden');
            //   $('#active').addClass('hidden');
            //   $('#deactive').addClass('hidden');
            // }
        }
      }
    });
  }
</script>
</body>
</html>