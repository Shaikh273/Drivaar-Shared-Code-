<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<header>
<!-- <nav class="bg-white" style="background-color: #fb9678; "> -->
<!-- style="background-color: #fb9678;" -->
<nav class="navbar top-navbar navbar-expand-md navbar-dark" style="background-color: #fb9678;"> 
    <div class="max-w-full mx-auto px-2 sm:px-8">

        <div class="flex flex-wrap p-2">

            <div class="w-2/12">

                <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo inline-block" />

                <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo inline-block" />

            </div>
            <div class="w-7/12">

                <ul class="nav nav-tabs customtab2" role="tablist" style="border-bottom: 1px solid #fb9678;">

                    <div>

                        <a href="index.php" class="btn btn-primary btnborder">

                        Dashboard

                        </a>

                    </div>

                    <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">

                            Contractor

                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="contractor.php">Contractors</a></li>

                            <li><a class="dropdown-item" href="expiring_documents.php">Expiring Documents</a></li>

                            <li><a class="dropdown-item" href="leave_request.php">Leave Request</a></li>

                            <li><a class="dropdown-item" href="daily_attendance.php">Daily Attendence</a></li>

                            <li><a class="dropdown-item" href="daily_timesheet.php">Daily Timesheet</a></li>

                            <li><a class="dropdown-item" href="feedback.php">Feedback</a></li>

                            <li><a class="dropdown-item" href="performance.php">Perfomance</a></li>

                        </ul>

                    </div>

                    <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">

                            Workforce

                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="depots.php">Depots</a></li>

                            <li><a class="dropdown-item" href="workforce.php">Workforce</a></li>

                            <li><a class="dropdown-item" href="schedule.php">Schedule</a></li>

                            <li><a class="dropdown-item" href="task.php">Tasks</a></li>

                            <li><a class="dropdown-item" href="add_metric.php">Metrics</a></li>

                        </ul>

                    </div>

                    <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">

                            Vehicle

                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="vehicle.php">Vehicles</a></li>

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

                           <li><a class="dropdown-item" href="vehicle_groups.php">Vehicle Groups</a></li>

                            <li><a class="dropdown-item" href="insurances.php">Fleet Insurance</a></li>

                        </ul>

                    </div>

                    <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">

                            Finance

                        </button>

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

                    <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">

                            Health & Safety
                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="risk_assessment.php">Risk Assessments</a></li>

                            <li><a class="dropdown-item" href="inspection.php">Vehicle Inspections</a></li>

                            <li><a class="dropdown-item" href="health_issues.php">Inspections Issues</a></li>

                            <li><a class="dropdown-item" href="accidents.php">Accidents</a></li>

                            <li><a class="dropdown-item" href="commnunications.php">Communication Center</a></li>

                           <li><a class="dropdown-item" href="documents.php">Company Policies</a></li>

                           <li><a class="dropdown-item" href="trainings.php">Trainings</a></li>

                        </ul>

                    </div>

                    <div class="btn-group">

                        <a href="report_setting.php" class="btn btn-primary btnborder">

                           Reports

                        </a>

                    </div>

                   <!--  <div class="btn-group">

                        <a href="./contractorEmailRegistration/" class="btn btn-primary btnborder">

                            EmailForm

                        </a>   

                    </div> -->

                   <!--  <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-cog"></i>
                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="generale_setting.php"> <i class="far fa-address-card"></i> Payment settings</a></li>
                           <li><a class="dropdown-item" href=""><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                        </ul>

                    </div> -->

                    </ul>

            </div>

            <div class="w-3/12">

                <div class="flex">

                    <input class="w-86 p-2 rounded-pill inline-block" type="text" placeholder="Search..." style="border: 1px solid #b8b8b8;">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block -ml-8 mt-2" viewBox="0 0 20 20" fill="black">

                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>

                     <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 float-right">

                        <button class="bg-white p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                            <span class="sr-only">View notifications</span>

                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />

                            </svg>
                        </button>

                        <div class="btn-group">

                        <button class="btn btn-primary dropdown-toggle btnborder" data-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-cog"></i>
                        </button>

                        <ul class="dropdown-menu">

                           <li><a class="dropdown-item" href="generale_setting.php"> <i class="far fa-address-card"></i> Payment settings</a></li>
                           <li><a class="dropdown-item" href=""><i class="fas fa-sign-out-alt"></i> Logout</a></li>

                        </ul>

                    </div>

                    </div>

                </div>
                 

            </div>

            <!-- <div class="w-2/12">

                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 float-right">

                    <button class="bg-white p-1 rounded-full text-gray-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">

                        <span class="sr-only">View notifications</span>

                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />

                        </svg>

                    </button>

                </div>

            </div> -->

        </div>

    </div>



<style>

    .dropdown-submenu {

    position: relative;

}

.dropdown-submenu > a.dropdown-item:after {

    font-family: FontAwesome;

    content: "\f054";

    float: right;

}

.dropdown-submenu > a.dropdown-item:after {

    content: ">";

    float: right;

}

.dropdown-submenu > .dropdown-menu {

    top: 0;

    left: 100%;

    margin-top: 0px;

    margin-left: 0px;

}

.dropdown-submenu:hover > .dropdown-menu {

    display: block;

}
.btnborder {
    border-radius: 0px;
    padding-right: 6px;
    padding-left: 6px;
}

</style>


<!-- <div class="w-full p-2 border-t">

    <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-end">

        <div class="flex space-x-4">

        </div>

    </div>

</div> -->

<!-- <script>

    $(".dropdown-toggle").on("mouseenter", function () {

    // make sure it is not shown:

    if (!$(this).parent().hasClass("show")) {

        $(this).click();

    }

});

$(".btn-group, .dropdown").on("mouseleave", function () {

    // make sure it is shown:

    if ($(this).hasClass("show")){

        $(this).children('.dropdown-toggle').first().click();

    }

});

</script> -->

</nav>

</header>