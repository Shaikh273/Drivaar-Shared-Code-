<?php
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    //include 'DB/config.php';
    $userid = $_SESSION['userid'];
    // $mysql = new Mysql();
    // $mysql->dbConnect();
    // $result = $mysql->selectWhere('tbl_user', 'id', '=', $userid, 'int');
    // $response = mysqli_fetch_array($result);
    // $mysql->dbDisConnect();
?>
<!-- <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet"> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- <header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.html">
                <b>
                    <a href="index2.php"><img src="../home/logo/logo.jpg" alt="homepage" class="light-logo inline-block"></a>
                </b> 
            </a>
        </div>


        <div class="navbar-collapse">
            <ul class="navbar-nav my-lg-0">

                <div class="btn-group divcolor">
                    <a href="documents.php" color="#fff"; class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                               Documents
                        </button>
                    </a>
                </div>

                <div class="btn-group divcolor">
                    <a href="myinvoice.php" color="#fff"; class="divcolor"> 
                        <button class="btn btn-default dropdown-toggle">
                               My Invoices
                        </button>
                    </a>
                    
                </div>

                <div class="btn-group divcolor">
                    <a href="offences.php" color="#fff"; class="divcolor"> 
                        <button class="btn btn-default dropdown-toggle">
                            Offences
                        </button>
                    </a>    

                </div>

                <div class="btn-group divcolor">
                    <a href="trainings.php" color="#fff"; class="divcolor"> 
                        <button class="btn btn-default dropdown-toggle">
                            Trainings
                        </button>
                    </a>    
                </div>

                <div class="btn-group divcolor">
                    <a href="profile.php" class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                               Profile
                        </button>
                    </a>
                </div>
                
                <div class="btn-group divcolor">
                    <a href="feedback_create.php" class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                              Feedback
                        </button>
                    </a>
                </div>
                
                <div class="btn-group divcolor">
                    <a href="accident.php" class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                             Accident  
                        </button>
                    </a>
                </div>
                
                <div class="btn-group divcolor">
                    <a href="raiseTicket.php" class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                            Ticket  
                        </button>
                    </a>
                </div>
                
                <div class="btn-group divcolor">
                    <a href="report_accident.php" class="divcolor">
                        <button class="btn btn-default dropdown-toggle">
                            Report Accident   
                        </button>
                    </a>
                </div>


                <div class="flex mt-2">

                    <form class="app-search d-none d-md-block d-lg-block">
                        <input type="text" class="form-control" placeholder="Search &amp; enter">
                    </form>

                     <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 float-right">

                        <i class="far fa-bell fa-lg"></i> &nbsp;

                        <div class="btn-group">

                                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                   <i class="fas fa-cog fa-lg"></i>
                                </button>

                                <ul class="dropdown-menu">

                                   <li><a class="dropdown-item" href="generale_setting.php"> <i class="far fa-address-card"></i> Payment settings</a></li>
                                   <li><a class="dropdown-item" href="vehicle_status.php"> <i class="fas fa-cogs"></i> Manage Data settings</a></li>
                                   <li><a class="dropdown-item" href=""><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                                </ul>

                        </div>

                    </div>

                </div>
            </ul>
        </div>
    </nav>
</header> -->

<header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">
                	<!-- <img src="../home/logo/logo.jpg" alt="homepage" class="light-logo inline-block"> -->
                    <span class="d-none d-lg-block">
                        <img src="../home/logo/logo.jpg" alt="homepage" class="light-logo inline-block">
                    </span>
                </a>
            </div>
            <!-- <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                    <li class="nav-item">
                        <form class="app-search d-none d-md-block d-lg-block">
                            <input type="text" class="form-control" placeholder="Search &amp; enter">
                        </form>
                    </li>
                </ul> 
            </div> -->
            <div class="navbar-collapse">
                    
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <span class="d-none d-lg-block">
	                    <ul class="navbar-nav my-lg-0">
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="documents.php" >Documents
	                            </a>
	                        </li>
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="myinvoice.php">Invoices
	                            </a>
	                        </li>
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="offences.php">Offences
	                            </a>
	                        </li>
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="trainings.php">Trainings
	                            </a>
	                        </li>
	                        <!-- <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="profile.php">Profile
	                            </a>
	                        </li> -->
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="feedback_create.php">Feedback
	                            </a>
	                        </li>
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="accident.php">Accident
	                            </a>
	                        </li>
	                        <?php

	                        if(isset($_SESSION['vid']))
	                        {
	                        	?>
	                        	<li class="nav-item dropdown">
	                                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="contractor_vehicle_inspection.php">Inspection<?php // echo $_SESSION['vid']; ?>
	                                </a>
	                            </li>
	                        	<?php
	                        }
	                        else
	                        {

	                        }
	                        ?>
                            
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="raiseTicket.php">Ticket
	                            </a>
	                        </li>
	                        <li class="nav-item dropdown">
	                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="report_accident.php" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accident
	                            </a>
	                        </li>

                            <div class="btn-group">
	                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">
	                                <i class="fas fa-cog fa-lg"></i>
	                            </button>
	                            <ul class="dropdown-menu">
	                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
	                            </ul>
                        	</div>


                        	<div class="user-profile" style="margin-top: 8px; margin-left: 5px;">
                            <div class="user-pro-body">
                                <?php
                                if(isset($_SESSION['profile']))
                                {
                                    ?>
                                    <div><a href="#" onclick="loadpageforuserdetail(<?php echo $_SESSION['cid'];?>)"><img src="upload/Userprofile/<?php echo $_SESSION['profile'];?>" alt="user-img" class="img-circle" style="margin-bottom: 0px;"></a></div>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <div><a href="#" onclick="loadpageforuserdetail(<?php echo $_SESSION['cid'];?>)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle" style="margin-bottom: 0px;"></a></div>
                                    <?php
                                }
                                ?>
                                
                            </div>
                        </div>

                           <!--  <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle waves-effect waves-dark" href="logout.php">
                                    LOG OUT
                                </a>
                            </li> -->
	                    </ul>
                	</span>

                	<ul class="navbar-nav mr-auto  d-lg-none">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-none d-lg-block d-md-block  waves-effect waves-dark" href="javascript:void(0)"><i class="ti-close"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu ti-menu"></i></a> </li>
                        <!-- <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control" placeholder="Search &amp; enter">
                            </form>
                        </li> -->
                    </ul>
            </div>
        </nav>
</header>

<aside class="left-sidebar  d-lg-none">
    <div class="scroll-sidebar">
        <div class="user-profile">
            <div class="user-pro-body">
                <div><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle"></div>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['cfname'];?><span class="caret"></span></a>
                    <div class="dropdown-menu animated flipInY">
                        <a href="logout.php" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li>
                    <a class="waves-effect waves-dark" href="#" aria-expanded="false">
                        <i class="fas fa-file"></i>
                        <span class="hide-menu">Documents</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="myinvoice.php" aria-expanded="false">
                        <i class="fas fa-file-alt"></i>
                        <span class="hide-menu">My Invoices</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="offences.php" aria-expanded="false">
                        <i class="fas fa-briefcase"></i>
                        <span class="hide-menu">Offences</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="trainings.php" aria-expanded="false">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="hide-menu">Trainings</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="profile.php" aria-expanded="false">
                        <i class="fas fa-id-badge"></i>
                        <span class="hide-menu">Profile</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="feedback_create.php" aria-expanded="false">
                        <i class="fas fa-comments"></i>
                        <span class="hide-menu">Feedback</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="accident.php" aria-expanded="false">
                        <i class="fas fa-car"></i>
                        <span class="hide-menu">Accident</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="raiseTicket.php" aria-expanded="false">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="hide-menu">Ticket</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="report_accident.php" aria-expanded="false">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="hide-menu">Report Accident</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<script type="text/javascript">
    function loadpageforuserdetail(wid)
    {
        // if(wid!=1)
        // {
        //     $.ajax({
        //         type: "POST",
        //         url: "loaddata.php",
        //         data: {action : 'WorkforceSetSessionData', wid: wid},
        //         dataType: 'json',
        //         success: function(data) {
        //             if(data.status==1)
        //             {
        //                 window.location = '<?php //echo $webroot ?>workForceDetails.php';
        //             }              
        //         }
        //     });
        // }
        // else
        // {
        // 	window.location = '<?php //echo $webroot ?>generale_setting.php';
        // }
		if(wid!='')
		{
			window.location = 'http://drivaar.com/contractorAdmin/profile.php';
		}
		else
		{
			
		}
        
    }
   
</script>