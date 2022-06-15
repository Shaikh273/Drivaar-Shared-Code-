<?php
if (!isset($_SESSION)) {
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

<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
    .divcolor {
        color: black;
        padding-top: 10px;
    }

    .btn-default {
        font-size: 16px;
    }
</style>
<input type="hidden" id="loggedinUser" value="<?php echo $_SESSION['userid']; ?>">
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <span class="d-none d-lg-block">
                    <img src="logo/logo.jpg" alt="homepage" class="light-logo inline-block" />
                </span>
            </a>
        </div>
        <div class="navbar-collapse">
            <span class="d-none d-lg-block">
                <ul class="navbar-nav my-lg-0">
                    <?php
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][144] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3"><a href="index.php">Dashboard</a>
                            </button>
                        </div>
                    <?php
                    }
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][175] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3"><a href="rota.php">Scheduling</a>
                            </button>
                        </div>
                    <?php
                    }
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][2] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">Contractor
                            </button>
                            <ul class="dropdown-menu">
                                <?php
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][3] == 1)) {
                                ?><li><a class="dropdown-item" href="contractor.php">Contractors</a></li><?php
                                                                                                        }
                                                                                                            ?>
                                <li><a class="dropdown-item" href="monthlyexpiring_documents.php">Expiring Documents</a></li>
                                <!-- <li><a class="dropdown-item" href="expiring_documents.php">Expiring Documents</a></li> -->
                                <li><a class="dropdown-item" href="leave_request.php">Leave Request</a></li>
                                <li><a class="dropdown-item" href="daily_attendance.php">Daily Attendence</a></li>
                                <li><a class="dropdown-item" href="daily_timesheet.php">Daily Timesheet</a></li>
                                <li><a class="dropdown-item" href="feedback.php">Feedback</a></li>
                                <li><a class="dropdown-item" href="performance.php">Perfomance</a></li>
                            </ul>
                        </div>
                    <?php
                    }
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][32] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">Workforce</button>
                            <ul class="dropdown-menu">
                                <?php
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][39] == 1)) {
                                ?>
                                    <li><a class="dropdown-item" href="depots.php">Depots</a></li>
                                <?php
                                }
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][33] == 1)) {
                                ?>
                                    <li><a class="dropdown-item" href="workforce.php">Workforce</a></li>
                                <?php
                                }
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][44] == 1)) {
                                ?>
                                    <li><a class="dropdown-item" href="schedule.php">Schedule</a></li>
                                <?php
                                }
                                ?>
                                <li><a class="dropdown-item" href="task.php">Tasks</a></li>
                                <li><a class="dropdown-item" href="add_metric.php">Metrics</a></li>
                            </ul>
                        </div>
                    <?php
                    }
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][46] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">Fleet</button>
                            <ul class="dropdown-menu">
                                <?php
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][47] == 1)) {
                                ?>
                                    <li><a class="dropdown-item" href="vehicle.php">Vehicles</a></li>
                                <?php
                                }
                                ?>

                                <li><a class="dropdown-item" href="vehicle_schedule.php">Schedule</a></li>
                                <li><a class="dropdown-item" href="rent_agreements.php">Rental Agreements</a></li>
                                <li><a class="dropdown-item" href="today_inspection.php">Today's Inspections</a></li>
                                <li><a class="dropdown-item" href="inspection_issue.php">Inspections Issues</a></li>
                                <li><a class="dropdown-item" href="forms.php">Forms</a></li>
                                <li><a class="dropdown-item" href="vehicle_contacts.php">Contacts</a></li>
                                <li><a class="dropdown-item" href="vehicle_offences.php">Offences</a></li>
                                <li><a class="dropdown-item" href="vehicle_accidents.php">Accidents</a></li>
                                <li><a class="dropdown-item" href="vehicle_damages.php">Damages</a></li>
                                <li><a class="dropdown-item" href="vehicles_expiring_documents.php">Expiring Documents</a></li>
                                <!-- <li><a class="dropdown-item" href="vehicle_groups.php">Vehicle Groups</a></li> -->
                                <li><a class="dropdown-item" href="vehicle_insurances.php">Fleet Insurance</a></li>
                            </ul>
                        </div>
                    <?php
                    }
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][83] == 1)) {
                    ?>
                        <div class="btn-group">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">Finance</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="contractor_invoices.php">Contractors Invoices</a></li>
                                <li><a class="dropdown-item" href="workforce_invoices.php">Workforce Invoices</a></li>
                                <li><a class="dropdown-item" href="custom_invoices.php">Invoices</a></li>
                                <li><a class="dropdown-item" href="disputes.php">Disputes</a></li>
                                <li><a class="dropdown-item" href="rates.php">Payment Types</a></li>
                                <li><a class="dropdown-item" href="assets.php">Assets</a></li>
                                <li><a class="dropdown-item" href="instalments.php">Credit Instalments</a></li>
                            </ul>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">Health & Safety</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="risk_assetment.php">Risk Assessments</a></li>
                            <li><a class="dropdown-item" href="vehicle_inspection.php">Vehicle Inspections</a></li>
                            <li><a class="dropdown-item" href="inspection_issue.php">Inspections Issues</a></li>
                            <li><a class="dropdown-item" href="vehicle_accidents.php">Accidents</a></li>
                            <li><a class="dropdown-item" href="commnunications.php">Communication Center</a></li>
                            <li><a class="dropdown-item" href="documents.php">Company Policies</a></li>
                            <li><a class="dropdown-item" href="traings_health_safety.php">Trainings</a></li>
                        </ul>
                    </div>
                    <?php
                    if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][109] == 1)) {
                    ?>
                        <div class="btn-group">
                            <a href="report_setting.php"><button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3">Reports</button></a>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="btn-group">
                        <a href="ticket.php">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3">Ticket</button>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="subscription/subscription.php">
                            <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3">Subscription</button>
                        </a>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-default dropdown-toggle m-1 p-1 mt-3 mb-3" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog fa-lg"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="generale_setting.php"> <i class="far fa-address-card"></i> Payment settings</a></li>
                            <li><a class="dropdown-item" href="vehicle_status.php"> <i class="fas fa-cogs"></i> Manage Data settings</a></li>
                            <li><a class="dropdown-item" href="logoutnew.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                    <li class="nav-item">
                        <!-- <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" class="form-control typeahead" placeholder="Search &amp; enter" id="contSearch" name="contSearch">
                            </form> -->
                        <div align="center">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" name="search" id="search" placeholder="Search Contractor" class="form-control" onfocusout="emptyUl()" />
                            </form>
                        </div>
                        <ul class="list-group" id="result" style="width:15%;"></ul>
                    </li>
                    <div class="user-profile" style="margin-top: 8px; margin-left: 5px;">
                        <div class="user-pro-body">
                            <?php
                            if ($_SESSION['profile'] != '') {
                            ?>
                                <div><a href="#" onclick="loadpageforuserdetail(<?php echo $_SESSION['userid']; ?>)"><img src="uploads/Userprofile/<?php echo $_SESSION['profile']; ?>" alt="user-img" class="img-circle" style="margin-bottom: 0px;"></a></div>
                            <?php
                            } else {
                            ?>
                                <div><a href="#" onclick="loadpageforuserdetail(<?php echo $_SESSION['userid']; ?>)"><img src="../assets/images/users/2.jpg" alt="user-img" class="img-circle" style="margin-bottom: 0px;"></a></div>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </ul>
            </span>
            <ul class="navbar-nav mr-auto  d-lg-none">
                <li class="nav-item"> <a class="nav-link nav-toggler d-none d-lg-block d-md-block  waves-effect waves-dark" href="javascript:void(0)"><i class="ti-close"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu ti-menu"></i></a> </li>
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
                    <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Setting<span class="caret"></span></a>
                    <div class="dropdown-menu animated flipInY">
                        <a href="generale_setting.php" class="dropdown-item"><i class="fas fa-cogs"></i> Payment settings</a>
                        <a href="vehicle_status.php" class="dropdown-item"><i class="fas fa-cog"></i> Manage Data </a>
                        <a href="logoutnew.php" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <?php
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][144] == 1)) {
                ?>
                    <li>
                        <a class="waves-effect waves-dark" href="index.php" aria-expanded="false">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                <?php
                }
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][175] == 1)) {
                ?>
                    <li>
                        <a class="waves-effect waves-dark" href="rota.php" aria-expanded="false">
                            <i class="fas fa-file"></i>
                            <span class="hide-menu">Scheduling</span>
                        </a>
                    </li>
                <?php
                }
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][2] == 1)) {
                ?>
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-user-secret"></i>
                            <span class="hide-menu">Contractor</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][3] == 1)) {
                            ?><li><a href="contractor.php">Contractors</a></li><?php
                                                                            }
                                                                                ?>
                            <li><a href="monthlyexpiring_documents.php">Expiring Documents</a></li>
                            <!-- <li><a href="expiring_documents.php">Expiring Documents</a></li> -->
                            <li><a href="leave_request.php">Leave Request</a></li>
                            <li><a href="daily_attendance.php">Daily Attendence</a></li>
                            <li><a href="daily_timesheet.php">Daily Timesheet</a></li>
                            <li><a href="feedback.php">Feedback</a></li>
                            <li><a href="performance.php">Perfomance</a></li>
                        </ul>
                    </li>
                <?php
                }
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][32] == 1)) {
                ?>
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-users"></i>
                            <span class="hide-menu">Workforce</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][39] == 1)) {
                            ?><li><a href="depots.php">Depots</a></li>
                                ]<?php
                                }
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][33] == 1)) {
                                    ?><li><a href="workforce.php">Workforce</a></li>
                            <?php
                                }
                                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][44] == 1)) {
                            ?><li><a href="schedule.php">Schedule</a></li>
                            <?php
                                }
                            ?>
                            <li><a href="task.php">Tasks</a></li>
                            <li><a href="add_metric.php">Metrics</a></li>
                        </ul>
                    </li>
                <?php
                }
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][46] == 1)) {
                ?>
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-taxi"></i>
                            <span class="hide-menu">Fleet</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <?php
                            if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][47] == 1)) {
                            ?>
                                <li><a href="vehicle.php">Vehicles</a></li>
                            <?php
                            }
                            ?>
                            <li><a href="vehicle_schedule.php">Schedule</a></li>
                            <li><a href="rent_agreements.php">Rental Agreements</a></li>
                            <li><a href="today_inspection.php">Today's Inspections</a></li>
                            <li><a href="inspection_issue.php">Inspections Issues</a></li>
                            <li><a href="forms.php">Forms</a></li>
                            <li><a href="vehicle_contacts.php">Contacts</a></li>
                            <li><a href="vehicle_offences.php">Offences</a></li>
                            <li><a href="vehicle_accidents.php">Accidents</a></li>
                            <li><a href="vehicle_damages.php">Damages</a></li>
                            <li><a href="vehicles_expiring_documents.php">Expiring Documents</a></li>
                            <!-- <li><a class="dropdown-item" href="vehicle_groups.php">Vehicle Groups</a></li> -->
                            <li><a href="vehicle_insurances.php">Fleet Insurance</a></li>
                        </ul>
                    </li>
                <?php
                }
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][83] == 1)) {
                ?>
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="far fa-credit-card"></i>
                            <span class="hide-menu">Finance</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="contractor_invoices.php">Contractors Invoices</a></li>
                            <li><a href="workforce_invoices.php">Workforce Invoices</a></li>
                            <li><a href="custom_invoices.php">Invoices</a></li>
                            <li><a href="disputes.php">Disputes</a></li>
                            <li><a href="rates.php">Payment Types</a></li>
                            <li><a href="assets.php">Assets</a></li>
                            <li><a href="instalments.php">Credit Instalments</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>
                <li>
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="fas fa-medkit"></i>
                        <span class="hide-menu">Health & Safety</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="risk_assetment.php">Risk Assessments</a></li>
                        <li><a href="vehicle_inspection.php">Vehicle Inspections</a></li>
                        <li><a href="inspection_issue.php">Inspections Issues</a></li>
                        <li><a href="vehicle_accidents.php">Accidents</a></li>
                        <li><a href="commnunications.php">Communication Center</a></li>
                        <li><a href="documents.php">Company Policies</a></li>
                        <li><a href="traings_health_safety.php">Trainings</a></li>
                    </ul>
                </li>
                <?php
                if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][109] == 1)) {
                ?>
                    <li>
                        <a class="waves-effect waves-dark" href="report_setting.php" aria-expanded="false">
                            <i class="fas fa-file-pdf"></i>
                            <span class="hide-menu">Reports</span>
                        </a>
                    </li>
                <?php
                }
                ?>
                <li>
                    <a class="waves-effect waves-dark" href="ticket.php" aria-expanded="false">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="hide-menu">Ticket</span>
                    </a>
                </li>
                <li>
                    <a class="waves-effect waves-dark" href="subscription/subscription.php" aria-expanded="false">
                        <i class="fas fa-subscribe-alt"></i>
                        <span class="hide-menu">Subscription</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<script type="text/javascript">
    function loadpageforuserdetail(wid) {
        if (wid != 1) {
            $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {
                    action: 'WorkforceSetSessionData',
                    wid: wid
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == 1) {
                        window.location = '<?php echo $webroot ?>workForceDetails.php';
                    }
                }
            });
        } else {
            window.location = '<?php echo $webroot ?>generale_setting.php';
        }

    }
</script>