<?php
include 'DB/config.php';
    $page_title = "Workforce Details";
    $page_id=35;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $id = $_SESSION['wid'];
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $query = "SELECT * FROM `tbl_user` WHERE `id`=".$id;
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
<?php //include('loader.php'); ?>
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
                            </div>
                            <br><hr>
                            <?php include('workforce_setting.php'); ?>
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
                        <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                            <?php echo $cntresult['email'];?>
                        </dd>

                        <dt class="col-md-5 mb-2 text-grey-dark">Phone</dt>
                        <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                            <?php echo $cntresult['contact'];?>
                        </dd>

                        <dt class="col-md-5 mb-2 text-grey-dark">Associate ID</dt>
                        <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                            &nbsp;
                        </dd>

                        <dt class="col-md-5 mb-2 text-grey-dark">Date of Birth</dt>
                        <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                            <?php echo $cntresult['dob'];?>
                        </dd>

                        <dt class="col-md-5 mb-2 text-grey-dark">NI Number</dt>
                        <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                           <?php echo $cntresult['ni_number'];?>
                        </dd>
                      </dl>
                  </div>
              </div>

              <div class="card datacard">
                <div class="card-header dataheader">
                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Passport
                    <a href="workforce_passport.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
                    </h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                      <dt class="col-md-5 mb-2 text-grey-dark">Issue Country:</dt>
                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                           <?php echo $cntresult['passport_country'];?>
                      </dd>

                      <dt class="col-md-5 mb-2 text-grey-dark">Number:</dt>
                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                           <?php echo $cntresult['passport_number'];?>
                      </dd>

                      <dt class="col-md-5 mb-2 text-grey-dark">Expiry Date:</dt>
                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                          <?php echo $cntresult['passport_expirydate'];?>
                      </dd>
                    </dl>
                </div>
              </div>

              <div class="card datacard">  
                <div class="card-header dataheader">
                    <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Driving Licence 
                    <a href="workForce_drivinglicence.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0" style="line-height: 1.2;">
                      <dt class="col-md-5 mb-2 text-grey-dark">Issue Country:</dt>
                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                          <?php echo $cntresult['drivinglicence_country'];?>
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
                        <a href="workforce_tax.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
                        </h6>

                  </div>  
                  <div class="card-body">
                    <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                    <dt class="col-md-5 mb-2 text-grey-dark">VAT Number</dt>
                    <dd class="col-md-7 mb-2" style="font-weight: 500;">GB  <?php echo $cntresult['vat_number'];?></dd>
                    </dl>
                  </div>
              </div>

              <div class="card datacard">
                  <div class="card-header dataheader">
                        <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Address  
                        <a href="workforce_address.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a>
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
                      <h6 class="mb-2 cardmb2 d-flex justify-content-between align-items-center">Manager
                      <a href="workforce_manager.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
                  </div>
                  <div class="card-body">                                  
                    <dl class="row mb-0 mb-3" style="line-height: 1.2;">
                      <dt class="col-md-5 mb-2 text-grey-dark">Assigned to:</dt>
                      <dd class="col-md-7 mb-2 " style="font-weight: 500;">
                          <?php
                            $mysql = new Mysql();
                            $mysql -> dbConnect();
                            $arrear =  $mysql -> selectWhere('tbl_user','id','=',$cntresult['assignto'],'int');
                            $arrearresult = mysqli_fetch_array($arrear);
                            echo $arrearresult['name'];
                        ?>
                      </dd>
                    </dl>
                  </div>
              </div>


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
                      <a href="workforce_paymentarrear.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
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
                      <a href="workforce_bankdetail.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
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
                      <a href="workforce_emergency.php"><span type="button"><i class="fas fa-edit"></i> Edit</span></a></h6>
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
</body>
</html>