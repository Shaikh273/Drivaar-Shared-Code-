<?php
$page_title = "Vehicle Damage Report";
include 'DB/config.php';
$page_id = 54;
if (!isset($_SESSION)) {
    session_start();
}
if ((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id] == 1) || (isset($_SESSION['adt']) && $_SESSION['adt'] == 1)) {
    
    $userid = $_SESSION['userid'];
    $mysql = new Mysql();
    $mysql->dbConnect();
    $id = $_GET['id'];
    $vid = $_GET['vid'];
    // $query1 = "SELECT c.*,c.`insert_date` AS date1,b.*,a.name,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_conditionalreportdata` c
    //                 INNER JOIN tbl_contractor a ON a.`id` = c.driver_id
    //                 INNER JOIN `tbl_vehicles` b On c.`vehicle_id`=b.`id`
    //                 INNER JOIN `tbl_vehiclemake` vm ON vm.`id`=b.`make_id` 
    //                 INNER JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=b.`model_id`
    //                 WHERE c.`isdelete`=0 AND c.`isactive`=0 AND c.`vehicle_id`=".$vid." AND 
    //                 c.`userid`=".$userid." AND a.id = c.`driver_id` AND
    //                 c.`id`=".$id;
    $query1 = "SELECT c.*,c.`insert_date` AS date1,b.*,a.name,vm.`name` as makename,vmo.`name` as modelname FROM `tbl_conditionalreportdata` c
    INNER JOIN tbl_contractor a ON a.`id` = c.driver_id
    INNER JOIN `tbl_vehicles` b On c.`vehicle_id`=b.`id`
    INNER JOIN `tbl_vehiclemake` vm ON vm.`id`=b.`make_id` 
    INNER JOIN `tbl_vehiclemodel` vmo ON vmo.`id`=b.`model_id`
    WHERE c.`isdelete`=0 AND c.`isactive`=0 AND c.`vehicle_id`=".$vid." AND a.id = c.`driver_id` AND
    c.`id`=".$id;
                    //echo $query1;
    $row1 =  $mysql->selectFreeRun($query1);
    $result = mysqli_fetch_array($row1); 
    $resultdate1 = date('d-m-Y H:i:s',strtotime($result['date1']));
    $resultdate2 = date('Y-m-d H:i',strtotime($result['date1']));
    // print_r($result['date1']);
    $mysql->dbDisconnect();
} else {
    if ((isset($_SESSION['adt']) && $_SESSION['adt'] == 0)) {
        header("location: login.php");
    } else {
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
    <style>
        body {
            font-weight: 390;
        }
        /*#tyres1 th, td{*/
        /*    border: 2px solid black;*/
        /*}*/
        /*#details th, td {*/
        /*    border: 1px solid black;*/
        /*}*/
        /*#vehicledetails1 th, td {*/
        /*    border: 0px solid black;*/
        /*}*/

        @media only screen and (min-device-width: 480px) {
            
            .card-header {
                background-color: bisque;
            }
            .label.arrowed-in-right,
            .label.arrowed-right {
                margin-right: 5px
            }

            .label.arrowed,
            .label.arrowed-in {
                margin-left: 5px
            }

            .label.arrowed,
            .label.arrowed-in {
                position: relative;
                z-index: 1
            }

            .label.arrowed-in:before,
            .label.arrowed:before {
                display: inline-block;
                content: "";
                position: absolute;
                top: 0;
                z-index: -1;
                border: 1px solid transparent;
                border-right-color: #ABBAC3
            }

            .label.arrowed-in:before {
                border-color: #ABBAC3 #ABBAC3 #ABBAC3 transparent
            }

            .label.arrowed-in-right,
            .label.arrowed-right {
                position: relative;
                z-index: 1
            }

            .label.arrowed-in-right:after,
            .label.arrowed-right:after {
                display: inline-block;
                content: "";
                position: absolute;
                top: 0;
                z-index: -1;
                border: 1px solid transparent;
                border-left-color: #ABBAC3
            }

            label.arrowed,
            .label.arrowed-in {
                position: relative;
                z-index: 1
            }

            .label.arrowed-in:before,
            .label.arrowed:before {
                display: inline-block;
                content: "";
                position: absolute;
                top: 0;
                z-index: -1;
                border: 1px solid transparent;
                border-right-color: #ABBAC3
            }

            .label.arrowed-in:before {
                border-color: #ABBAC3 #ABBAC3 #ABBAC3 transparent
            }

            .label.arrowed-in-right,
            .label.arrowed-right {
                position: relative;
                z-index: 1
            }

            .label.arrowed-in-right:after,
            .label.arrowed-right:after {
                display: inline-block;
                content: "";
                position: absolute;
                top: 0;
                z-index: -1;
                border: 1px solid transparent;
                border-left-color: #ABBAC3
            }

            .label.arrowed-in-right:after {
                border-color: #ABBAC3 transparent #ABBAC3 #ABBAC3
            }

            .label-info.arrowed:before {
                border-right-color: #3A87AD
            }

            .label-info.arrowed-in:before {
                border-color: #3A87AD #3A87AD #3A87AD transparent
            }

            .label-info.arrowed-right:after {
                border-left-color: #3A87AD
            }

            .label-info.arrowed-in-right:after {
                border-color: #3A87AD transparent #3A87AD #3A87AD
            }

            .label-primary.arrowed:before {
                border-right-color: #428BCA
            }

            .label-primary.arrowed-in:before {
                border-color: #428BCA #428BCA #428BCA transparent
            }

            .label-primary.arrowed-right:after {
                border-left-color: #428BCA
            }

            .label-primary.arrowed-in-right:after {
                border-color: #428BCA transparent #428BCA #428BCA
            }

            .label-success.arrowed:before {
                border-right-color: #82AF6F
            }

            .label-success.arrowed-in:before {
                border-color: #82AF6F #82AF6F #82AF6F transparent
            }

            .label-success.arrowed-right:after {
                border-left-color: #82AF6F
            }

            .label-success.arrowed-in-right:after {
                border-color: #82AF6F transparent #82AF6F #82AF6F
            }

            .label-warning.arrowed:before {
                border-right-color: #F89406
            }

            .label-danger.arrowed:before,
            .label-important.arrowed:before {
                border-right-color: #D15B47
            }

            .label-warning.arrowed-in:before {
                border-color: #F89406 #F89406 #F89406 transparent
            }

            .label-warning.arrowed-right:after {
                border-left-color: #F89406
            }

            .label-danger.arrowed-right:after,
            .label-important.arrowed-right:after {
                border-left-color: #D15B47
            }

            .label-warning.arrowed-in-right:after {
                border-color: #F89406 transparent #F89406 #F89406
            }

            .label-important.arrowed-in:before {
                border-color: #D15B47 #D15B47 #D15B47 transparent
            }

            .label-important.arrowed-in-right:after {
                border-color: #D15B47 transparent #D15B47 #D15B47
            }

            .label-danger.arrowed-in:before {
                border-color: #D15B47 #D15B47 #D15B47 transparent
            }

            .label-danger.arrowed-in-right:after {
                border-color: #D15B47 transparent #D15B47 #D15B47
            }

            .label-inverse.arrowed:before {
                border-right-color: #333
            }

            .label-inverse.arrowed-in:before {
                border-color: #333 #333 #333 transparent
            }

            .label-inverse.arrowed-right:after {
                border-left-color: #333
            }

            .label-inverse.arrowed-in-right:after {
                border-color: #333 transparent #333 #333
            }

            .label-pink.arrowed:before {
                border-right-color: #D6487E
            }

            .label-pink.arrowed-in:before {
                border-color: #D6487E #D6487E #D6487E transparent
            }

            .label-pink.arrowed-right:after {
                border-left-color: #D6487E
            }

            .label-pink.arrowed-in-right:after {
                border-color: #D6487E transparent #D6487E #D6487E
            }

            .label-purple.arrowed:before {
                border-right-color: #9585BF
            }

            .label-purple.arrowed-in:before {
                border-color: #9585BF #9585BF #9585BF transparent
            }

            .label-purple.arrowed-right:after {
                border-left-color: #9585BF
            }

            .label-purple.arrowed-in-right:after {
                border-color: #9585BF transparent #9585BF #9585BF
            }

            .label-yellow.arrowed:before {
                border-right-color: #FEE188
            }

            .label-yellow.arrowed-in:before {
                border-color: #FEE188 #FEE188 #FEE188 transparent
            }

            .label-yellow.arrowed-right:after {
                border-left-color: #FEE188
            }

            .label-yellow.arrowed-in-right:after {
                border-color: #FEE188 transparent #FEE188 #FEE188
            }

            .label-light.arrowed:before {
                border-right-color: #E7E7E7
            }

            .label-light.arrowed-in:before {
                border-color: #E7E7E7 #E7E7E7 #E7E7E7 transparent
            }

            .label-light.arrowed-right:after {
                border-left-color: #E7E7E7
            }

            .label-light.arrowed-in-right:after {
                border-color: #E7E7E7 transparent #E7E7E7 #E7E7E7
            }

            .label-grey.arrowed:before {
                border-right-color: #A0A0A0
            }

            .label-grey.arrowed-in:before {
                border-color: #A0A0A0 #A0A0A0 #A0A0A0 transparent
            }

            .label-grey.arrowed-right:after {
                border-left-color: #A0A0A0
            }

            .label-grey.arrowed-in-right:after {
                border-color: #A0A0A0 transparent #A0A0A0 #A0A0A0
            }

            .label {
                line-height: 1.15;
                height: 20px
            }

            .label.arrowed:before {
                left: -10px;
                border-width: 10px 5px
            }

            .label-lg.arrowed,
            .label-lg.arrowed-in {
                margin-left: 6px
            }

            .label.arrowed-in:before {
                left: -5px;
                border-width: 10px 5px
            }

            .label.arrowed-right:after {
                right: -10px;
                border-width: 10px 5px
            }

            .label-lg.arrowed-in-right,
            .label-lg.arrowed-right {
                margin-right: 6px
            }

            .label.arrowed-in-right:after {
                right: -5px;
                border-width: 10px 5px
            }

            .label-lg {
                padding: 0.2em .7em .5em;
                font-size: 18px;
                line-height: 0.9;
                height: 24px;
            }

            .green {
                color: #69AA46 !important;
            }

            .red {
                color: #DD5A43 !important;
            }

            .hr-8,
            .hr8 {
                margin: 8px 0;
            }

            .hr-dotted,
            .hr.dotted {
                border-style: dotted;
            }

            .label-info.arrowed-in:before {
                border-color: #3A87AD #3A87AD #3A87AD transparent;
            }

            .hr-double {
                height: 3px;
                border-top: 1px solid #E3E3E3;
                border-bottom: 1px solid #E3E3E3;
                border-top-color: rgba(0, 0, 0, .11);
                border-bottom-color: rgba(0, 0, 0, .11);
            }

            .spaced>li {
                margin-top: 9px;
                margin-bottom: 9px;
            }

            .label-lg.arrowed:before {
                left: -12px;
                border-width: 12px 6px
            }

            .label-xlg.arrowed,
            .label-xlg.arrowed-in {
                margin-left: 7px
            }

            .label-lg.arrowed-in:before {
                left: -6px;
                border-width: 12px 6px
            }

            .label-lg.arrowed-right:after {
                right: -12px;
                border-width: 12px 6px
            }

            .label-xlg.arrowed-in-right,
            .label-xlg.arrowed-right {
                margin-right: 7px
            }

            .label-lg.arrowed-in-right:after {
                right: -6px;
                border-width: 12px 6px
            }


            .widget-box {
                padding: 0;
                box-shadow: none;
                margin: 3px 0;
                border: 1px solid #000
            }

            .lable-header {
                font-size: 20px;
                color: black;
            }

            @media only screen and (max-width:767px) {
                .widget-box {
                    margin-top: 7px;
                    margin-bottom: 7px
                }
            }

            .widget-header {
                -webkit-box-sizing: content-box;
                -moz-box-sizing: content-box;
                box-sizing: content-box;
                position: relative;
                min-height: 38px;
                background: repeat-x #f7f7f7;
                background-image: -webkit-linear-gradient(top, #FFF 0, #EEE 100%);
                background-image: -o-linear-gradient(top, #FFF 0, #EEE 100%);
                background-image: linear-gradient(to bottom, #FFF 0, #EEE 100%);
                filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffeeeeee', GradientType=0);
                color: #669FC7;
                border-bottom: 1px solid #000;
                padding-left: 12px
            }

            .widget-box.transparent>.widget-header,
            .widget-header-flat {
                filter: progid: DXImageTransform.Microsoft.gradient(enabled=false)
            }

            .widget-header:after,
            .widget-header:before {
                content: "";
                display: table;
                line-height: 0
            }

            .widget-header:after {
                clear: right
            }

            .widget-box.collapsed>.widget-header {
                border-bottom-width: 0
            }

            .collapsed.fullscreen>.widget-header {
                border-bottom-width: 1px
            }

            .collapsed>.widget-body {
                display: none
            }

            .widget-header-flat {
                background: #F7F7F7
            }

            .widget-header-large {
                min-height: 49px;
                padding-left: 18px
            }

            .widget-header-small {
                min-height: 31px;
                padding-left: 10px
            }

            .widget-header>.widget-title {
                line-height: 36px;
                padding: 0;
                margin: 0;
                display: inline
            }

            .widget-header>.widget-title>.ace-icon {
                margin-right: 5px;
                font-weight: 400;
                display: inline-block
            }

            .infobox .infobox-content:first-child,
            .infobox>.badge,
            .infobox>.stat,
            .percentage {
                font-weight: 700
            }

            .widget-header-large>.widget-title {
                line-height: 48px
            }

            .widget-header-small>.widget-title {
                line-height: 30px
            }

            .widget-toolbar {
                display: inline-block;
                padding: 0 10px;
                line-height: 37px;
                float: right;
                position: relative
            }

            .widget-header-large>.widget-toolbar {
                line-height: 48px
            }

            .widget-header-small>.widget-toolbar {
                line-height: 29px
            }

            .widget-toolbar.no-padding {
                padding: 0
            }

            .widget-toolbar.padding-5 {
                padding: 0 5px
            }

            .widget-toolbar:before {
                display: inline-block;
                content: "";
                position: absolute;
                top: 3px;
                bottom: 3px;
                left: -1px;
                border: 1px solid #D9D9D9;
                border-width: 0 1px 0 0
            }

            .popover-notitle+.popover .popover-title,
            .popover.popover-notitle .popover-title,
            .widget-toolbar.no-border:before {
                display: none
            }

            .widget-header-large>.widget-toolbar:before {
                top: 6px;
                bottom: 6px
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar:before {
                border-color: #EEE
            }

            .widget-color-orange>.widget-header>.widget-toolbar:before {
                border-color: #FEA
            }

            .widget-color-dark>.widget-header>.widget-toolbar:before {
                border-color: #222;
                box-shadow: -1px 0 0 rgba(255, 255, 255, .2), inset 1px 0 0 rgba(255, 255, 255, .1)
            }

            .widget-toolbar label {
                display: inline-block;
                vertical-align: middle;
                margin-bottom: 0
            }

            .widget-toolbar>.widget-menu>a,
            .widget-toolbar>a {
                font-size: 14px;
                margin: 0 1px;
                display: inline-block;
                padding: 0;
                line-height: 24px
            }

            .widget-toolbar>.widget-menu>a:hover,
            .widget-toolbar>a:hover {
                text-decoration: none
            }

            .widget-header-large>.widget-toolbar>.widget-menu>a,
            .widget-header-large>.widget-toolbar>a {
                font-size: 15px;
                margin: 0 1px
            }

            .widget-toolbar>.btn {
                line-height: 27px;
                margin-top: -2px
            }

            .widget-toolbar>.btn.smaller {
                line-height: 26px
            }

            .widget-toolbar>.btn.bigger {
                line-height: 28px
            }

            .widget-toolbar>.btn-sm {
                line-height: 24px
            }

            .widget-toolbar>.btn-sm.smaller {
                line-height: 23px
            }

            .widget-toolbar>.btn-sm.bigger {
                line-height: 25px
            }

            .widget-toolbar>.btn-xs {
                line-height: 22px
            }

            .widget-toolbar>.btn-xs.smaller {
                line-height: 21px
            }

            .widget-toolbar>.btn-xs.bigger {
                line-height: 23px
            }

            .widget-toolbar>.btn-minier {
                line-height: 18px
            }

            .widget-toolbar>.btn-minier.smaller {
                line-height: 17px
            }

            .widget-toolbar>.btn-minier.bigger {
                line-height: 19px
            }

            .widget-toolbar>.btn-lg {
                line-height: 36px
            }

            .widget-toolbar>.btn-lg.smaller {
                line-height: 34px
            }

            .widget-toolbar>.btn-lg.bigger {
                line-height: 38px
            }

            .widget-toolbar-dark {
                background: #444
            }

            .widget-toolbar-light {
                background: rgba(255, 255, 255, .85)
            }

            .widget-toolbar>.widget-menu {
                display: inline-block;
                position: relative
            }

            .widget-toolbar>.widget-menu>a[data-action],
            .widget-toolbar>a[data-action] {
                -webkit-transition: transform .1s;
                -o-transition: transform .1s;
                transition: transform .1s
            }

            .widget-toolbar>.widget-menu>a[data-action]>.ace-icon,
            .widget-toolbar>a[data-action]>.ace-icon {
                margin-right: 0
            }

            .widget-toolbar>.widget-menu>a[data-action]:focus,
            .widget-toolbar>a[data-action]:focus {
                text-decoration: none;
                outline: 0
            }

            .widget-toolbar>.widget-menu>a[data-action]:hover,
            .widget-toolbar>a[data-action]:hover {
                -moz-transform: scale(1.2);
                -webkit-transform: scale(1.2);
                -o-transform: scale(1.2);
                -ms-transform: scale(1.2);
                transform: scale(1.2)
            }

            .widget-body {
                background-color: #FFF
            }

            .widget-main {
                padding: 12px
            }

            .widget-main.padding-32 {
                padding: 32px
            }

            .widget-main.padding-30 {
                padding: 30px
            }

            .widget-main.padding-28 {
                padding: 28px
            }

            .widget-main.padding-26 {
                padding: 26px
            }

            .widget-main.padding-24 {
                padding: 24px
            }

            .widget-main.padding-22 {
                padding: 22px
            }

            .widget-main.padding-20 {
                padding: 20px
            }

            .widget-main.padding-18 {
                padding: 18px
            }

            .widget-main.padding-16 {
                padding: 16px
            }

            .widget-main.padding-14 {
                padding: 14px
            }

            .widget-main.padding-12 {
                padding: 12px
            }

            .widget-main.padding-10 {
                padding: 10px
            }

            .widget-main.padding-8 {
                padding: 8px
            }

            .widget-main.padding-6 {
                padding: 6px
            }

            .widget-main.padding-4 {
                padding: 4px
            }

            .widget-main.padding-2 {
                padding: 2px
            }

            .widget-main.no-padding,
            .widget-main.padding-0 {
                padding: 0
            }

            .widget-toolbar .progress {
                vertical-align: middle;
                display: inline-block;
                margin: 0
            }

            .widget-toolbar>.dropdown,
            .widget-toolbar>.dropup {
                display: inline-block
            }

            .widget-toolbox.toolbox-vertical,
            .widget-toolbox.toolbox-vertical+.widget-main {
                display: table-cell;
                vertical-align: top
            }

            .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
            .widget-box>.widget-header>.widget-toolbar>[data-action=settings],
            .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
            .widget-color-dark>.widget-header>.widget-toolbar>[data-action=settings] {
                color: #99CADB
            }

            .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
            .widget-box>.widget-header>.widget-toolbar>[data-action=reload],
            .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
            .widget-color-dark>.widget-header>.widget-toolbar>[data-action=reload] {
                color: #ACD392
            }

            .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
            .widget-box>.widget-header>.widget-toolbar>[data-action=collapse],
            .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
            .widget-color-dark>.widget-header>.widget-toolbar>[data-action=collapse] {
                color: #AAA
            }

            .widget-box>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
            .widget-box>.widget-header>.widget-toolbar>[data-action=close],
            .widget-color-dark>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
            .widget-color-dark>.widget-header>.widget-toolbar>[data-action=close] {
                color: #E09E96
            }

            .widget-box[class*=widget-color-]>.widget-header {
                color: #FFF;
                filter: progid: DXImageTransform.Microsoft.gradient(enabled=false)
            }

            .widget-color-blue {
                border-color: #307ECC
            }

            .widget-color-blue>.widget-header {
                background: #307ECC;
                border-color: #307ECC
            }

            .widget-color-blue2 {
                border-color: #5090C1
            }

            .widget-color-blue2>.widget-header {
                background: #5090C1;
                border-color: #5090C1
            }

            .widget-color-blue3 {
                border-color: #6379AA
            }

            .widget-color-blue3>.widget-header {
                background: #6379AA;
                border-color: #6379AA
            }

            .widget-color-green {
                border-color: #82AF6F
            }

            .widget-color-green>.widget-header {
                background: #82AF6F;
                border-color: #82AF6F
            }

            .widget-color-green2 {
                border-color: #2E8965
            }

            .widget-color-green2>.widget-header {
                background: #2E8965;
                border-color: #2E8965
            }

            .widget-color-green3 {
                border-color: #4EBC30
            }

            .widget-color-green3>.widget-header {
                background: #4EBC30;
                border-color: #4EBC30
            }

            .widget-color-red {
                border-color: #E2755F
            }

            .widget-color-red>.widget-header {
                background: #E2755F;
                border-color: #E2755F
            }

            .widget-color-red2 {
                border-color: #E04141
            }

            .widget-color-red2>.widget-header {
                background: #E04141;
                border-color: #E04141
            }

            .widget-color-red3 {
                border-color: #D15B47
            }

            .widget-color-red3>.widget-header {
                background: #D15B47;
                border-color: #D15B47
            }

            .widget-color-purple {
                border-color: #7E6EB0
            }

            .widget-color-purple>.widget-header {
                background: #7E6EB0;
                border-color: #7E6EB0
            }

            .widget-color-pink {
                border-color: #CE6F9E
            }

            .widget-color-pink>.widget-header {
                background: #CE6F9E;
                border-color: #CE6F9E
            }

            .widget-color-orange {
                border-color: #E8B10D
            }

            .widget-color-orange>.widget-header {
                color: #855D10 !important;
                border-color: #E8B10D;
                background: #FFC657
            }

            .widget-color-dark {
                border-color: #5a5a5a
            }

            .widget-color-dark>.widget-header {
                border-color: #666;
                background: #404040
            }

            .widget-color-grey {
                border-color: #9e9e9e
            }

            .widget-color-grey>.widget-header {
                border-color: #aaa;
                background: #848484
            }

            .widget-box.transparent {
                border-width: 0
            }

            .widget-box.transparent>.widget-header {
                background: 0 0;
                border-width: 0;
                border-bottom: 1px solid #DCE8F1;
                color: #4383B4;
                padding-left: 3px
            }

            .widget-box.transparent>.widget-header-large {
                padding-left: 5px
            }

            .widget-box.transparent>.widget-header-small {
                padding-left: 1px
            }

            .widget-box.transparent>.widget-body {
                border-width: 0;
                background-color: transparent
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action],
            [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action] {
                text-shadow: 0 1px 1px rgba(0, 0, 0, .2)
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
            [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=settings] {
                color: #D3E4ED
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
            [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=reload] {
                color: #DEEAD3
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
            [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=collapse] {
                color: #E2E2E2
            }

            [class*=widget-color-]>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
            [class*=widget-color-]>.widget-header>.widget-toolbar>[data-action=close] {
                color: #FFD9D5
            }

            .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action],
            .widget-color-orange>.widget-header>.widget-toolbar>[data-action] {
                text-shadow: none
            }

            .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=settings],
            .widget-color-orange>.widget-header>.widget-toolbar>[data-action=settings] {
                color: #559AAB
            }

            .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=reload],
            .widget-color-orange>.widget-header>.widget-toolbar>[data-action=reload] {
                color: #7CA362
            }

            .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=collapse],
            .widget-color-orange>.widget-header>.widget-toolbar>[data-action=collapse] {
                color: #777
            }

            .widget-color-orange>.widget-header>.widget-toolbar>.widget-menu>[data-action=close],
            .widget-color-orange>.widget-header>.widget-toolbar>[data-action=close] {
                color: #A05656
            }

            .widget-box.light-border[class*=widget-color-]:not(.fullscreen) {
                border-width: 0
            }

            .widget-box.light-border[class*=widget-color-]:not(.fullscreen)>.widget-header {
                border: 1px solid;
                border-color: inherit
            }

            .widget-box.light-border[class*=widget-color-]:not(.fullscreen)>.widget-body {
                border: 1px solid #D6D6D6;
                border-width: 0 1px 1px
            }

            .widget-box.no-border {
                border-width: 0
            }

            .widget-box.fullscreen {
                position: fixed;
                margin: 0;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #FFF;
                border-width: 3px;
                z-index: 1040 !important
            }

            .widget-box.fullscreen:not([class*=widget-color-]) {
                border-color: #AAA
            }

            .widget-body .table {
                border-top: 1px solid #E5E5E5
            }

            .widget-body .table thead:first-child tr {
                background: #FFF
            }
        }
    </style>
</head>

<body class="skin-default-dark fixed-layout">
    <?php
    include('loader.php');
    ?>
    <div id="main-wrapper">
        <?php
        include('header.php');
        ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <main class="container-fluid  animated">
                    <div class="card">
                        <div class="card-header" style="background-color: rgb(255 236 230);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="header">Report #<?php echo $id; ?> - Details</div>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="container" style="padding-bottom: 10%;">
                                <div class='container bootdey'>
                                    <div class='row' style='width: 100%;' >
                                        <div class='col-md-12' id='printTable'>
                                            <div class='widget-box'>
                                                <div class='row widget-header widget-header-large' style="padding-right: 50px;padding-left: 0px;padding-bottom: 15px; margin-left: 0px; margin-right: 0px; padding-top: 10px;">

                                                    <div class="col-md-4">
                                                        <img src="logo/fulllogo.jpg" alt="" style="height: 100px;width: 100px;margin-bottom: 5px;margin-top: 5px;">
                                                    </div>
                                                    
                                                    <div class="col-md-5 mt-3">
                                                        <span class='invoice-info-label lable-header'><b>Drivaar Private Limited</b></span><br>
                                                        <!--<span class='invoice-info-label '>8 SHEPHERDS MARKET, MAYFAIR, W1J 7QE, England</span><br>-->
                                                        <!--<span class='invoice-info-label '><b>Company number 10551950 VAT number 259414193</b></span><br>-->
                                                        <!--<span class='invoice-info-label '>Email: driversupport@bryanstonlogistics.com</span>-->
                                                    </div>

                                                    <div class="col-md-3 mt-3">
                                                        <span class='invoice-info-label lable-header'>Condition Report</span>
                                                        <span class='invoice-info-label lable-header'><b>#<?php echo $id; ?></b></span>
                                                        
                                                        <span class='widget-toolbar hidden-480'>
                                                            <a href='#'>
                                                                <i class='ace-icon fa fa-print'></i>
                                                            </a>
                                                        </span>
                                                        <br>
                                                        <span class='invoice-info-label'>Vehicle Reg No</span>
                                                        <span class='invoice-info-label'><b><?php echo $result['registration_number'] ?></b></span>
                                                    </div>
                                                </div>

                                                <div class='widget-body'>
                                                    <div class='widget-main padding-24'>
                                                        
                                                        <table id="details" class="table display dataTable table-responsive no-footer table-bodered" style="width:100%;border: 1px solid black;">
                                                          <thead class="default">
                                                              <tr role="row" style='text-align: center;background-color: darkgray;'>
                                                                <th width="50%"><b>Report Information<b></th>
                                                                <th width="50%"><b>Damages<b></th>
                                                              </tr>
                                                            </thead>
                                                          <tbody >
                                                              <tr>
                                                                  <th>
                                                                      <dl class="row mb-0 mb-4" style="line-height: 1.2;">
                                                                          <dt class="col-md-5 mb-2 text-grey-dark">Driver Name:</dt>
                                                                          <dd class="col-md-7 mb-2 fortWeight"><?php echo $result['name']; ?></dd>
                        
                                                                          <dt class="col-md-5 mb-2 text-grey-dark">Vehicle Reg No:</dt>
                                                                          <dd class="col-md-7 mb-2 fortWeight"><?php echo $result['registration_number'] ?></dd>
                        
                                                                          <dt class="col-md-5 mb-2 text-grey-dark">Odometer:</dt>
                                                                          <dd class="col-md-7 mb-2 fortWeight"><?php echo $result['odometer']; ?></dd>
                        
                                                                          <dt class="col-md-5 mb-2 text-grey-dark">Make:</dt>
                                                                          <dd class="col-md-7 mb-2 fortWeight"><?php echo $result['makename'] ?></dd>
                        
                                                                          <dt class="col-md-5 mb-2 text-grey-dark">Model:</dt>
                                                                          <dd class="col-md-7 mb-2 fortWeight"><?php echo $result['modelname'] ?></dd>
                                                                        </dl>
                                                                        
                                                                        <p>The above vehicle has been returned by me authorized representative with all
                                                                        bodywork in its present state and defects agreed andrecorded above.<br>
                                                                        I confirm that if I have returned the vehicle dirty, I will be liable for any damage that
                                                                        is visible when the vehicle is cleaned.<br>
                                                                        By this signature I accepts any damages, missing accessories and fuel reading and
                                                                        resultant charges.<br>
                                                                        Any deposit paid will be withheld if the vehicle is returned with damage whatsoever,
                                                                        that is not on the Initial Condition Reportattached to my Rental Agreement.<br>
                                                                        </p>
                                                                        <table class="table dataTable" aria-describedby="example2_info" style="width:40%;border: 1px solid black;">
                                                                          <thead class="default">
                                                                              <tr role="row" style='text-align: center;'>
                                                                                <th>Date:    <?php echo $resultdate1 ?></th>
                                                                              </tr>
                                                                          </thead>
                                                                          <tbody id="sign">
                                                                              <tr>
                                                                                 <td>
                                                                                     <img src='<?php echo $webroot ?>uploads/conditionalreport/signature/<?php echo $result['driversign'] ?>' id='"<?php echo $id ?>"' width='210'  name='imgsrc' class='img-fluid'/>
                                                                                 </td>
                                                                              </tr>
                                                                        </tbody>
                                                                        </table>
                                                                        
                                                                        
                                                                  </th>
                                                                  <th>
                                                                      <div clas="row col-md-12">
                                                                          <img src="imp_files/Depositphotos_144057949_s-2019 - Copy.jpg" alt="" usemap="#image-map" class="map" />
                                                                        <map name="image-map">
            <area  alt="non driver right side wheels" title="non driver right side wheels" href="javascript: void(0);" coords="436,123,25" shape="circle" onclick="areamodel(this);">
            <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="434,420,25" shape="circle" onclick="areamodel(this);">
            <area  alt="driver left side wheels" title="driver left side wheels" href="javascript: void(0);" coords="41,123,24" shape="circle" onclick="areamodel(this);">
            <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="41,422,24" shape="circle" onclick="areamodel(this);">
            <area  alt="back left  side wheels" title="back left  side wheels" href="javascript: void(0);" coords="173,694,188,694,192,675,174,671" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side wheels" title="back right side wheels" href="javascript: void(0);" coords="292,675,307,671,309,696,292,693" shape="poly" onclick="areamodel(this);">
            <area  alt="non diver right  side wheels" title="non diver right  side wheels" href="javascript: void(0);" coords="290,14,299,12,305,15,303,29,287,30" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left  side wheels" title="driver left  side wheels" href="javascript: void(0);" coords="171,15,179,12,185,13,185,24,170,31" shape="poly" onclick="areamodel(this);">
            
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="157,585,167,583,170,602,154,598" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="149,107,150,124,166,126,170,104" shape="poly" onclick="areamodel(this);">
            <area  alt="driver left side glass" title="driver left side glass" href="javascript: void(0);" coords="114,148,125,150,132,159,110,159" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="309,107,324,108,326,122,309,126" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="311,600,317,581,329,584,330,602" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver right side glass" title="non driver right side glass" href="javascript: void(0);" coords="346,157,367,158,367,146,352,148" shape="poly" onclick="areamodel(this);">
            
            <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="181,610,172,603,174,644,184,643" shape="poly" onclick="areamodel(this);">
            <area  alt="back left side light" title="back left side light" href="javascript: void(0);" coords="72,514,108,512,104,524,72,529" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="298,607,309,602,311,638,300,637" shape="poly" onclick="areamodel(this);">
            <area  alt="back right side light" title="back right side light" href="javascript: void(0);" coords="366,511,406,511,404,525,372,525" shape="poly" onclick="areamodel(this);">
            <area  alt="front diver left side head light" title="front diver left side head light" href="javascript: void(0);" coords="94,94,90,75,84,64,71,65,75,79,83,91"  shape="poly" onclick="areamodel(this);">
            <area  alt="front diver side head  light" title="front diver side head  light" href="javascript: void(0);" coords="170,91,195,78,199,62,177,67,171,76" shape="poly" onclick="areamodel(this);">
            <area  alt="front non diver right side head  light" title="front non diver right side head  light" href="javascript: void(0);"  coords="277,66,297,65,309,86,295,90,279,81" shape="poly" onclick="areamodel(this);">
            <area  alt="front non diver side head  light" title="front non diver side head  light" href="javascript: void(0);" coords="397,82,404,65,385,69,383,80,387,90"  shape="poly" onclick="areamodel(this);">
            <area  alt="bonat " title="bonat " href="javascript: void(0);"  coords="183,86,194,81,224,84,261,84,279,84,292,87,299,109,293,109,269,110,251,111,229,114,208,111,189,111,177,109,180,95" shape="poly" onclick="areamodel(this);">
            <area  alt="left side bonat" title="left side bonat" href="javascript: void(0);" coords="87,65,101,78,108,99,114,108,113,120,98,87" shape="poly" onclick="areamodel(this);">
            <area  alt="right side bonat" title="right side bonat" href="javascript: void(0);" coords="364,116,364,98,377,72,384,65,383,74" shape="poly" onclick="areamodel(this);">
            
            <area  alt="front glass" title="front glass" href="javascript: void(0);"  coords="203,113,299,111,296,132,293,151,273,155,252,156,231,155,211,156,185,152,181,137,177,111,260,112,231,111,279,112" shape="poly" onclick="areamodel(this);">
            <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="116,113,159,156,157,162,114,123" shape="poly" onclick="areamodel(this);">
            <area  alt="front glass" title="front glass" href="javascript: void(0);" coords="319,154,359,112,361,119,344,137,318,164" shape="poly" onclick="areamodel(this);">
            
            <area  alt="left window glass" title="left window glass" href="javascript: void(0);" coords="151,169,159,207,146,213,121,204,115,192,110,168,134,155,144,165"  shape="poly" onclick="areamodel(this);">
            <area  alt="right window glass" title="right window glass" href="javascript: void(0);"  coords="323,168,340,154,366,168,362,186,357,201,338,207,320,208,319,192,321,181" shape="poly" onclick="areamodel(this);">
            
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="262,31,278,26,296,24,306,38,308,63,299,66,276,61,250,58,219,57,199,62,185,64,174,65,162,55,167,38,178,27,212,30,239,32" shape="poly" onclick="areamodel(this);">
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="67,109,67,89,73,78,72,61,57,50,39,55,31,73,29,99,48,93" shape="poly" onclick="areamodel(this);">
            <area  alt="front bumper guard" title="front bumper guard" href="javascript: void(0);" coords="401,73,408,58,419,50,431,50,442,62,446,77,446,96,431,93,417,98,408,111,408,85" shape="poly" onclick="areamodel(this);">
            <area  alt="front left bumper guard" title="front left bumper guard" href="javascript: void(0);"  coords="66,89,69,107,57,100,46,94,34,94,33,78,38,67,43,56,49,55,59,54,67,63,71,75" shape="poly" onclick="areamodel(this);">
            
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="66,437,72,532,44,529,35,484,34,456,55,453" shape="poly" onclick="areamodel(this);">
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="404,524,405,436,426,451,441,452,440,480,436,508,436,528,419,528" shape="poly" onclick="areamodel(this);">
            <area  alt="back bumper guard" title="back bumper guard" href="javascript: void(0);" coords="171,646,171,671,191,682,214,674,238,682,257,675,285,678,308,678,312,645,302,641,296,662,268,662,241,663,216,663,187,662,183,642" shape="poly" onclick="areamodel(this);">
            
            <area  alt="roof" title="roof" href="javascript: void(0);" coords="180,154,295,156,285,187,282,304,281,418,281,504,238,512,191,506,191,418,192,313,192,193" shape="poly" onclick="areamodel(this);">
            <area  alt="driver  side door" title="driver  side door" href="javascript: void(0);"  coords="34,180,37,167,78,144,103,144,112,191,122,207,152,211,164,205,167,218,145,219,127,215,113,215,95,214,70,213,50,213,40,204,35,195" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver  side door" title="non driver  side door" href="javascript: void(0);"  coords="367,141,402,143,420,159,441,163,438,190,435,209,384,215,354,216,330,218,311,219,318,172,321,209,350,210,372,152" shape="poly" onclick="areamodel(this);">
            <area  alt="driver side body" title="driver side body" href="javascript: void(0);" coords="183,310,183,411,182,518,134,523,108,526,111,514,71,512,70,439,70,413,56,392,35,393,33,221,90,221,150,222,182,223" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side body" title="non driver side body" href="javascript: void(0);" coords="292,222,289,325,288,513,370,524,368,511,407,514,406,435,406,407,419,392,440,385,437,285,439,221,372,219" shape="poly" onclick="areamodel(this);">
            <area alt="back door driver side glass" title="back door driver side glass" href="javascript: void(0);" coords="240,553,194,554,191,577,193,599,215,602,242,600" shape="poly" onclick="areamodel(this);">
            <area alt="back door non driver side glass" title="back door non driver side glass"  href="javascript: void(0);" coords="242,552,286,552,290,573,288,596,264,600,241,600,242,582" shape="poly" onclick="areamodel(this);">
            <area alt="driver back door" title="driver back door" href="javascript: void(0);"coords="241,522,274,522,289,527,298,561,300,584,299,606,300,654,280,663,242,661,242,599,270,601,287,595,292,575,287,551,270,552,257,551,242,552" shape="poly" onclick="areamodel(this);">
            <area alt="non driver back door" title="non driver back door" href="javascript: void(0);" coords="191,598,218,603,241,599,242,633,243,662,212,661,186,662,182,638,182,609,190,539,197,523,239,524,240,552,219,553,193,554,192,575" shape="poly" onclick="areamodel(this);">
            <area  alt="radiator cover" title="radiator cover" href="javascript: void(0);" coords="194,79,204,62,227,57,252,56,273,65,281,82,237,85" shape="poly" onclick="areamodel(this);">
            <area  alt="driver side fender" title="driver side fender" href="javascript: void(0);"  coords="89,92,94,83,95,74,101,90,104,97,116,122,114,130,105,124,106,138,98,141,79,143,62,156,37,164,33,157,72,130,65,94,72,77,80,85" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side fender" title="non driver side fender" href="javascript: void(0);"  coords="364,117,380,77,384,85,391,89,399,82,404,75,410,90,409,98,409,106,406,125,406,135,418,149,428,151,442,155,438,161,427,161,414,157,397,140,379,142,371, 139,364,138" shape="poly" onclick="areamodel(this);">
            <area  alt="non driver side wheel" title="non driver side wheel" href="javascript: void(0);" coords="435,122,23" shape="circle">
            <area  alt="driver side wheel" title="driver side wheel" href="javascript: void(0);" coords="41,125,24" shape="circle">
            <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="42,421,24" shape="circle">
            <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="434,419,26" shape="circle">
            <area  alt="front driver side wheel" title="front driver side wheel" href="javascript: void(0);" coords="170,12,188,11,189,27,170,33" shape="poly">
            <area  alt="front non driver side wheel" title="front non driver side wheel" href="javascript: void(0);" coords="306,14,288,13,290,27,307,34" shape="poly">
            <area  alt="back driver side wheel" title="back driver side wheel" href="javascript: void(0);" coords="172,693,173,675,192,675,193,695" shape="poly">
            <area  alt="back non driver side wheel" title="back non driver side wheel" href="javascript: void(0);" coords="290,694,311,693,310,671,290,672" shape="poly">
        </map>
                                                                      <table id="tyres" class="table" style="width:100%;background-color: white;border: 1px solid black;">
                                                                          <thead class="default">
                                                                              <tr role="row" style='text-align: center;'>
                                                                                <th colspan='2'><b>Tyres</b></th>
                                                                              </tr>
                                                                          </thead>
                                                                          <tbody id="tyres1" style='text-align: center;'>
                                                                             
                                                                              <tr style='background-color: bisque;'>
                                                                                 <td><b>NEARSIDE</b></td>
                                                                                 <td><b>OFFSIDE</b></td>
                                                                              </tr>
                                                                              <tr>
                                                                                 <td><?php echo $result['front_left_tyre'] ?></td>
                                                                                 <td><?php echo $result['front_left_tyre'] ?></td>
                                                                              </tr>
                                                                              <tr>
                                                                                 <td><?php echo $result['back_left_tyre'] ?></td>
                                                                                 <td><?php echo $result['back_right_tyre'] ?></td>
                                                                              </tr>
                                                                              <tr>
                                                                                 <td colspan='2' style='background-color: bisque;'><b>FUEL</b></td>
                                                                                 
                                                                              </tr>
                                                                              <tr>
                                                                                 <td colspan='2'><?php echo $result['fuel'] ?></td>
                                                                              </tr>
                                                                        </tbody>
                                                                        </table>
                                                                      </div>
                                                                      
                                                                  </th>
                                                              </tr>
                                                              
                                                          </tbody>
                                                        </table>
                                            
                                                        <table id="vehicledetails1" class="table" aria-describedby="example2_info" style="width:100%;border: 1px solid black;">
                                                          <thead class="default">
                                                              <tr role="row" style='text-align: center;background-color: darkgray;width:100%;'>
                                                                <th colspan='3'><b>EXISTING DAMAGES<b></th>
                                                              </tr>
                                                          </thead>
                                                          <tbody id="vehicledetails">
                                                              
                                                          </tbody>
                                                        </table>
                                                        <table  class="table" style="width:100%;border: 1px solid black;">
                                                          <thead class="default">
                                                              <tr role="row" style='text-align: center;background-color: darkgray;width:100%;'>
                                                                <th colspan='3'><b>NEW DAMAGES<b></th>
                                                              </tr>
                                                          </thead>
                                                          <tbody id="newdamagedetails">
                                                              
                                                          </tbody>
                                                        </table>
                                                        
                                                        <table  class="table" style="width:100%;border: 1px solid black;">
                                                          <thead class="default">
                                                              <tr role="row" style='text-align: center;background-color: darkgray;width:100%;'>
                                                                <th colspan='3'><b>VEHICLE IMAGES<b></th>
                                                              </tr>
                                                          </thead>
                                                          <tbody id="extravehicleimg">
                                                              
                                                          </tbody>
                                                        </table>
                                                        
                                                        <div class="row" style="background-color: white;">
                                                            <div class="col-md-6">
                                                                <table class="table dataTable"  style="width:40%;border: 1px solid black;">
                                                                <thead class="default">
                                                                    <tr role="row" style='text-align: center;'>
                                                                        <th>Date:    <?php echo $resultdate1 ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody >
    <?php
	    $mysql = new Mysql();
	    $mysql->dbConnect();
	    $statusquerycmp =  "SELECT * FROM `tbl_company` WHERE id=1";  
	    $strow =  $mysql->selectFreeRun($statusquerycmp);
	    $rowcmp = mysqli_fetch_array($strow);
    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <img src='<?php echo $webroot ?>uploads/conditionalreport/signature/<?php echo $result['conductsign'] ?>' id='"<?php echo $id ?>"' width='210'  name='imgsrc' class='img-fluid'/>
                                                                            <?php  echo $rowcmp['name'];?>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                </tbody>
                                                            </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <table class="table dataTable"  style="width:40%;border: 1px solid black;">
                                                                <thead class="default">
                                                                    <tr role="row" style='text-align: center;'>
                                                                        <th>Date:    <?php echo $resultdate1 ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody >
                                                                    <tr>
                                                                        <td>
                                                                            <img src='<?php echo $webroot ?>uploads/conditionalreport/signature/<?php echo $result['driversign'] ?>' id='"<?php echo $id ?>"' width='210'  name='imgsrc' class='img-fluid'/>
                                                                            <br/>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br>
                                          
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-primary" style="margin-top: 10px; margin-left: 8px;" onclick="printJ()">PRINT</button>

                            </div>
                        </div>
                    </div>

                </main>
            </div>
        </div>
        <?php
        include('footer.php');
        ?>
    </div>

    <?php
    include('footerScript.php');
    ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.0/jquery.maphilight.min.js"></script>
    
    <script>
        $(function()
        {
            $('.map').maphilight({
                fill:true,
                fillColor:'03a9f3',
                fillOpacity: 0.4,
                stroke: false
            });
            ajaxcall();
            var userid = <?php echo $userid ?>;
            var id = <?php echo $vid ?>;
            var reportid = <?php echo $id ?>;
            var date = '<?php echo $resultdate2; ?>';
            $.ajax({
                    type: 'POST',
                    url: 'loaddata.php',
                    data:'userid='+userid+'&id='+id+'&date='+date+'&reportid='+reportid+'&action=damagereportpdf',
                    success: function(data)
                    { 
                        $("#vehicledetails").append(data.data);
                        $("#newdamagedetails").append(data.data2);
                        $("#extravehicleimg").append(data.data3);
                    }
            });
        });
        function ajaxcall() {
            var userid = <?php echo $userid ?>;
            var id = <?php echo $vid ?>;
            $.ajax({
                    type: 'POST',
                    url: 'loaddata.php',
                    data:'userid='+userid+'&id='+id+'&action=vehicleDamagedata',
                    success: function(data)
                    { 
                        var obj1 = JSON.parse(JSON.stringify(data.part));
                        var i =0;
                        var j = obj1.length;
                        for(i = 0; i<j; i++) {
                            // console.log(data.type[i]);
                            var fillcolor;
                            var c = data.type[i];
                            var c1 = data.part[i];
                            var c2 = data.state[i];
                            if(c2 == 0) {
                                if(c == 1) {
                                    fillcolor = '00c292';
                                } else if(c == 2) {
                                    fillcolor = 'fec107';
                                } else if(c == 3) {
                                    fillcolor = 'e46a76';
                                }
                                $("area[title='"+c1+"']").each(function(){
                                  var data = $(this).data('maphilight') || {};
                                    data.alwaysOn = true;
                                    data.fillColor = fillcolor;
                                    data.fill = true;
                                    $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                                });
                            } else {
                                $("area[title='"+c1+"']").each(function(){
                                  var data = $(this).data('maphilight') || {};
                                    data.alwaysOn = false;
                                    $(this).data('maphilight', data).trigger('alwaysOn.maphilight');
                                });
                            }
                            
                        }
                        
                    }
            });
        }
        function printJ() {
            var printContents = document.getElementById("printTable").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            //console.log(document.body.innerHTML);
            window.print();
            document.body.innerHTML = originalContents;
        }
        
    </script>

</body>

</html>