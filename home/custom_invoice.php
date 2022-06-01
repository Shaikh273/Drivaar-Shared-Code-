
<?php
    $page_title="Invoice";
    include 'DB/config.php';
    $page_id=85;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
        $mysql = new Mysql();
        $mysql -> dbConnect();
        $id = $_GET['id'];
        $query1 = "SELECT c.*,i.`name` AS statusname FROM `tbl_contractorinvoice` c 
                    INNER JOIN `tbl_invoicestatus` i On i.`id`=c.`status_id`
                    WHERE c.`id`=".$id;
        $row1 =  $mysql -> selectFreeRun($query1);
        $result = mysqli_fetch_array($row1);

        $cntquery = "SELECT * FROM `tbl_vehiclecontact` WHERE `id`=".$result['cid'];
        $cntrow =  $mysql -> selectFreeRun($cntquery);
        $result1 = mysqli_fetch_array($cntrow);

        $mysql->dbDisconnect();
    }
    else
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
    <title><?php echo $page_title;?></title>
    <?php include('head.php');?>
    <link rel="stylesheet" href="countrycode/build/css/intlTelInput.css">
    <link rel="stylesheet" href="countrycode/build/css/demo.css">
    <style>
        body {
            font-weight: 390;
        }
        @media only screen and (min-device-width: 480px){
        .label.arrowed-in-right,
        .label.arrowed-right {
            margin-right: 5px
        }

        .label.arrowed,
        .label.arrowed-in {
            margin-left: 5px
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
            color: #69AA46!important;
        }

        .red {
            color: #DD5A43!important;
        }

        .hr-8, .hr8 {
            margin: 8px 0;
        }
        .hr-dotted, .hr.dotted {
            border-style: dotted;
        }

        .label-info.arrowed-in:before {
            border-color: #3A87AD #3A87AD #3A87AD transparent;
        }

        .hr-double {
            height: 3px;
            border-top: 1px solid #E3E3E3;
            border-bottom: 1px solid #E3E3E3;
            border-top-color: rgba(0,0,0,.11);
            border-bottom-color: rgba(0,0,0,.11);
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
            color: #855D10!important;
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
            z-index: 1040!important
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
                            <div class="header">Invoice</div>               
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="container" style="padding-bottom: 10%;">
                            <div class='container bootdey'>
                                <div class='row' style='width: auto;' id='printTable'>
                                    <div class='col-lg-12'>
                                        <div class='widget-box'>
                                            <div class='row widget-header widget-header-large' style="padding-right: 50px;padding-left: 0px;padding-bottom: 15px; margin-left: 0px; margin-right: 0px; padding-top: 10px;">
                                                <div class="col-lg-2">
                                                    <img src="logo/fulllogo.jpg" alt="" style="height: 100px;width: 100px;margin-bottom: 5px;margin-top: 5px;">
                                                </div>
                                                 <?php
                                                    $fromdate = date("d/m/Y",strtotime($result['from_date']));
                                                    $todate = date("d/m/Y",strtotime($result['to_date']));
                                                ?>
                                                <div class="col-lg-7 mt-3">
                                                    <span class='invoice-info-label lable-header'>Invoice:</span>
                                                    <span class='red lable-header'><?php echo $result['invoice_no'];?></span><br>
                                                    <span class='invoice-info-label'><?php echo $result['statusname'];?></span>
                                                </div>

                                                <div class="col-lg-3 mt-3">
                                                        <span class='widget-toolbar hidden-480'>
                                                            <a href='#'>
                                                                <i class='ace-icon fa fa-print'></i>
                                                            </a>
                                                        </span>
                                                        <br>
                                                        <button type="button" class="btn waves-effect waves-light btn-xs btn-primary" style="height: 24px;" onclick="addcustom(<?php echo $result['id'];?>)">Add New Line</button><br>
                                                        <span class='invoice-info-label'>Period:</span>
                                                        <span class='blue'><?php echo $fromdate.' - '.$todate;?></span><br>
                                                        <span class='invoice-info-label'>Associate:</span>
                                                        <span class='invoice-info-label'><?php echo $result1['name'];?></span><br>
                                                </div>
                                            </div>

                                            <div class='widget-body'>
                                                <div class='widget-main padding-24'>
                                                    <div class='row'>

                                                    	<?php
                                                        $mysql = new Mysql();
                                                        $mysql -> dbConnect();
                                                            $cmpquery = "SELECT * FROM `tbl_company` WHERE `isactive`=0 AND `isdelete`=0 ORDER BY id DESC LIMIT 1";
                                                            $cmprow =  $mysql -> selectFreeRun($cmpquery);
                                                            $cmpresult = mysqli_fetch_array($cmprow);
                                                        $mysql -> dbDisConnect();
                                                        ?>
                                                        <div class='col-sm-7'>
                                                            <div>
                                                                <ul class='list-unstyled  spaced'>
                                                                    <li>
                                                                        <div class='col-xs-11 label label-lg label-success'><b>FROM</b></div>
                                                                    </li>
                                                                    <li><b><?php echo $cmpresult['name'];?></b></li>
                                                                    <li><?php echo $cmpresult['address'];?></li>
                                                                    <li>Register Number: <?php echo $cmpresult['registration_no'];?></li>
                                                                    <li>VAT: <?php echo $cmpresult['vat'];?></li>
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <div class='col-sm-5'>
                                                            <div>
                                                                <ul class='list-unstyled spaced'>
                                                                    <li>
                                                                        <div class='col-xs-11 label label-lg label-info'>
                                                                            <b>To</b>
                                                                        </div>
                                                                    </li>
                                                                    <li><b><?php echo $result1['name'];?></b></li>
                                                                    <li><?php echo $result1['street_address'] . "-" .$result1['postcode'] . "<br>" .$result1['city'] ." ".$result1['state'];?></li>
                                                                    <li>Phone: <?php echo $result1['contact'];?></li>
                                                                    <li>Email: <a href="<?php echo $result1['email'];?>"><?php echo $result1['email'];?></a></li>
                                                                </ul>
                                                            </div>
                                                        </div><!-- /.col -->
                                                        <!-- /.col -->
                                                    </div><!-- /.row -->

                                                    <div class='space'></div><br><br>

                                                    <div>
    <?php
    $finaltotal=0;
    $totalnet=0;
    $mysql = new Mysql();
    $mysql -> dbConnect();
    $tblquery = "SELECT * FROM `tbl_custominvoicedata` WHERE `isdelete`=0 AND `isactive`=0 AND `invoice_id`=".$result['id']." ORDER BY `id` desc";
    $tblrow =  $mysql -> selectFreeRun($tblquery);
    $rows = mysqli_num_rows($tblrow);
    if($rows>0)
    {
    	?>
    	<table class='table table-responsive table'>
            <thead class="default">
                <tr style="background-color: #e9ecef;">
	                <th style="width: 60%;">STANDARD SERVICES</th>
	                <th>QUANTITY</th>
	                <th>UNIT COST</th>
	                <th>NET</th>
	                <th>GROSS</th>
	            </tr>
            </thead>
                                                           
			<tbody>
			    <?php
			    while($tblresult = mysqli_fetch_array($tblrow))
			    {

			        	$adddate = date("d/m/Y",strtotime($tblresult['date']));
			            $net = $tblresult['price']*$tblresult['quantity'];
			            $total = ($net) + (($net*$tblresult['tax'])/100);
			            ?>
			            <tr>
			                <td><?php echo $tblresult['name']?><br>
			                	<small class="text-muted"><?php echo $tblresult['description']?></small><br>
			                	<small class="edit" onclick="edit(<?php echo $tblresult['id']?>);">Edit </small>|
			                	<small class="delete" onclick="deleterow(<?php echo $tblresult['id']?>);"> Remove</small>
			                </td>
			                <td><?php echo $tblresult['quantity']; ?></td>
			                <td><?php echo '£ '.$tblresult['price']; ?></td>
			                <td><?php echo '£ '.$net; ?></td>
			                <td><?php echo '£ '.$total; ?></td>
			            </tr>
			            <?php
			            $finaltotal+=$total;
			    }
			    $mysql -> dbDisConnect();
			    ?>
			</tbody>
                
        </table>
    	<?php
    }
    ?>
                                                    	
                                                    </div>

                                                    <div class='hr hr8 hr-double hr-dotted'></div>
                                                    <div class='row'>
                                                        <div class='col-sm-7 pull-left'></div>
                                                            <div class='col-sm-5 pull-right' style="text-align: right;">
                                                                <h4 class='pull-right'>
                                                                 <b>GROSS : <span class='green'>£ <?php echo $finaltotal;?></b></span>
                                                                </h4>

                                                            </div>
                                                    </div>

                                                    <div class='space-6'></div>
                                                    
                                                </div>
                                            </div>
                        
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary" style="margin-top: 10px; margin-left: 8px;" onclick="printJ()">PRINT</button>

                            <script>
                                 function printJ()
                                 {
                                     var printContents = document.getElementById("printTable").innerHTML;
                                     var originalContents = document.body.innerHTML;
                                     document.body.innerHTML = printContents;
                                     window.print();
                                     document.body.innerHTML = originalContents;
                                 }
                            </script>

                        </div>
                    </div>
                 </div>        
            
    </main>
</div>
</div>
 <div id="addcustom" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header" style="background-color: rgb(255 236 230);">
                  <h4 class="modal-title">Add invoice line</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              </div>
              <div class="modal-body">
                  <form method="post" id="AddCustomForm" name="AddCustomForm" action="">
                     <input type="hidden" name="id" id="id">
                     <input type="hidden" name="cid" id="cid">
                     <div class="row">
                     	<div class="col-md-12">
	                     	<div class="form-group">
	                          <label class="control-label">Name *</label>
	                          <input type="text" id="name" name="name" class="form-control" placeholder="">
	                        </div>
	                    </div>
                     </div>
                     <div class="row">
                     	<div class="col-md-12">
	                     	<div class="form-group">
		                        <label class="control-label">Description </label>
		                        <textarea type="text" id="description" name="description" class="form-control" placeholder="" rows="2"></textarea>
	                        </div>
                    	</div>
                     </div>
                     <div class="row">
                     	<div class="col-md-4">
                     		<div class="form-group">
	                     		<label class="control-label">Quantity *</label>
	                            <input type="number" id="quantity" name="quantity" class="form-control" placeholder="">
                            </div>
                     	</div>
                     	<div class="col-md-4">
                     		<div class="form-group">
	                     		<label class="control-label">Price *</label>
	                            <input type="text" id="price" name="price" class="form-control" placeholder="">
                         	</div>
                     	</div>
                     	<div class="col-md-4">
                     		<div class="form-group">
	                     		<label class="control-label">Tax </label>
	                            <input type="text" id="tax" name="tax" class="form-control" placeholder="">
                         	</div>
                     	</div>
                     </div>
                     <div class="modal-footer">
                  <button type="submit" name="insert" class="btn btn-success waves-effect waves-light" id="submit">Add Line</button>
                  <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
              </div>
                  </form>
              </div>
              
          </div>
      </div>
    </div>
<?php
    include('footer.php');
?>
</div>

<?php
	include('footerScript.php');
?>
 <script>
	function addcustom(id)
	{
		$('#addcustom').modal('show');
        $('#AddCustomForm')[0].reset(); 
        $("#submit").attr('name', 'insert');
        $("#submit").text('Add Line');
		$('#cid').val(id);
	}

	$("#AddCustomForm").validate({
	    rules: {
	        name: 'required',
	        quantity: 'required',
	        price: 'required',
	    },
	    messages: {
	        name: "Please enter name",
	        quantity: "Please enter quantity",
	        price: "Please enter price",
	    },
	    submitHandler: function(form) {
	        event.preventDefault();
	        $.ajax({
	            url: "InsertData.php", 
	            type: "POST", 
	            dataType:"JSON",            
	            data: $("#AddCustomForm").serialize()+"&action=AddFinanceCustomForm",    
	            success: function(data) {
	                if(data.status==1)
	                {
	                    myAlert(data.title + "@#@" + data.message + "@#@success");
	                    $('#AddCustomForm')[0].reset();
	                    $('#addcustom').modal('hide');
	                    location.reload(true);
	                }
	                else
	                {
	                    myAlert(data.title + "@#@" + data.message + "@#@danger");
	                }
	            }
	        });
	       // return false;
	    }
	});      
 
 	function edit(id)
    {
		$('#addcustom').modal('show');
        $('#id').val(id);

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'custominvoiceUpdateData', id: id},

            dataType: 'json',

            success: function(data) {

                $result_data = data.statusdata;

                $('#name').val($result_data['name']);
                $('#description').val($result_data['description']);
                $('#quantity').val($result_data['quantity']);
                $('#price').val($result_data['price']);
                $('#tax').val($result_data['tax']);

                $("#submit").attr('name', 'update');
                $("#submit").text('Update');

            }

        });
    }

    function deleterow(id)
    {

        $.ajax({

            type: "POST",

            url: "loaddata.php",

            data: {action : 'custominvoiceDeleteData', id: id},

            dataType: 'json',

            success: function(data) {
                if(data.status==1)
                {
                    myAlert("Delete @#@ Data has been deleted successfully.@#@success");
                    location.reload(true);
                }
                else
                {
                    myAlert("Delete @#@ Data can not been deleted.@#@danger");
                }
                
            }

        });
    }
 </script>
</body>

</html>   