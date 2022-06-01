<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['load']) || !isset($_SESSION['uid']))
{
    header("location: login.php");
}
$_SESSION['page_id']=3;
//include('authentication.php');
include('config.php');
$page_title="Employee Registration";



?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=1024">
        <title><?php echo $page_title;?></title>
        <?php include('head.php');?>
    </head>
    <body class="skin-default-dark fixed-layout">
        <?php
            include('loader.php');
        ?>
        <div id="main-wrapper">
            <?php
                include('header.php');
                include('menu.php');
            ?>
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor">New Employee Registration</h4>
                        </div>
                        <!--<div class="col-md-7 align-self-center text-right">-->
                        <!--    <div class="d-flex justify-content-end align-items-center">-->
                                <!--<ol class="breadcrumb">-->
                                <!--    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>-->
                                <!--    <li class="breadcrumb-item active">Dashboard</li>-->
                                <!--</ol>-->
                                <!--<button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    
                    
                    
                    <!--Main content-->
                    <!--<iframe scrolling src="https://usdemo.livebox.co.in/lbmeeting/?key=416f5ff096e0ef425a8deac9269579e6" width="1000px" height="1000px" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen> </iframe>-->
                    
                    
                    <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h4 class="m-b-0 text-white">Registration Form</h4>
                            </div>
                            <div class="card-body">
                                <form action="#" method="post">
                                    <div class="form-body">
                                        <h3 class="card-title">Person Info</h3>
                                        <hr>
                                        <div class="row p-t-20">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Full Name</label>
                                                    <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Full Name" required>
                                                    <!--<small class="form-control-feedback"> This is inline help </small> -->
                                                    </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Contact Number</label>
                                                    <input type="text" id="contact" name="contact" class="form-control" placeholder="Contact Number" required>
                                                    <!--<small class="form-control-feedback"> This field has error. </small> -->
                                                    </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Gender</label>
                                                    <select class="form-control custom-select" name="gender" required>
                                                        <option value="1">Male</option>
                                                        <option value="0">Female</option>
                                                    </select>
                                                    <!--<small class="form-control-feedback"> Select your gender </small> -->
                                                    </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label class="control-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob" placeholder="dd/mm/yyyy">
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Role / Post</label>
                                                    <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" required name="role">
                                                        <option value="">Select Role</option>
                                                        <option value="1">Looms Operator</option>
                                                        <option value="2">Master</option>
                                                        <option value="3">Bobin machine operator</option>
                                                        <option value="4">Supervisior</option>
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label class="control-label">Joining Date</label>
                                                    <input type="date" class="form-control" name="joindate" placeholder="dd/mm/yyyy">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <!--<div class="col-md-6">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <label class="control-label">Membership</label>-->
                                            <!--        <div class="custom-control custom-radio">-->
                                            <!--            <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input">-->
                                            <!--            <label class="custom-control-label" for="customRadio1">Free</label>-->
                                            <!--        </div>-->
                                            <!--        <div class="custom-control custom-radio">-->
                                            <!--            <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input">-->
                                            <!--            <label class="custom-control-label" for="customRadio2">Paid</label>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <h3 class="card-title m-t-40">Local Address</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group has-danger">
                                                    <label>Street</label>
                                                    <input type="text" class="form-control" name="lstreet" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>City</label>
                                                    <input type="text" class="form-control" name="lcity" required>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" name="lstate" required>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>Post Code</label>
                                                    <input type="text" class="form-control" name="lpostcode" required>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>Country</label>
                                                    <select class="form-control custom-select" name="lcountry" required>
                                                        <option value="India">India</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <h3 class="card-title m-t-40">Permanent Address</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group has-success">
                                                    <label>Street</label>
                                                    <input type="text" class="form-control" name="pstreet">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>City</label>
                                                    <input type="text" class="form-control" name="pcity" required>
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" name="pstate" required>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <!--/row-->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label>Post Code</label>
                                                    <input type="text" class="form-control" name="ppostcode">
                                                </div>
                                            </div>
                                            <!--/span-->
                                            <div class="col-md-6">
                                                <div class="form-group has-danger">
                                                    <label>Country</label> 
                                                    <select class="form-control custom-select" name="pcountry" required>
                                                        <option value="India">India</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!--/span-->
                                        </div>
                                        <h3 class="card-title m-t-40">KYC Detail</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label>ID Proof Type</label>
                                                    <input type="text" class="form-control" name="ppostcode">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group has-success">
                                                    <label>ID Proof Number</label>
                                                    <input type="text" class="form-control" name="ppostcode">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!--<h4 class="card-title">ID Proof Image Upload</h4>-->
                                                        <label for="input-file-now">ID Proof Image Upload (Optional)</label>
                                                        <input type="file" id="input-file-now" class="dropify" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-success" name="newReg"> <i class="fa fa-check"></i> Save</button>
                                        <button type="button" class="btn btn-inverse" onClick="javascript:history.go(-1)">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                    
                    <?php
                        include('right_sidebar.php');
                    ?>
                </div>
            </div>
            
            <?php
                include('footer.php');
            ?>
        </div>
        
        <?php
            include('footerScript.php');
        ?>
    </body>

</html>