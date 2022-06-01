<?php
    $page_title="Drivaar";
    include 'DB/config.php';
    $page_id=109;
    if(!isset($_SESSION)) 
    {
        session_start();
    }
    if((isset($_SESSION['permissioncode']) && $_SESSION['permissioncode'][$page_id]==1) || (isset($_SESSION['adt']) && $_SESSION['adt']==1))
    {
      $userid=$_SESSION['userid'];
    }
    else
    {
      header("location: login.php");       
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
            .titlehead {
                  font-size: 28px;
                  font-weight: 500;
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
                    <div class="row">
                      <?php include('report.php'); ?> 
                    </div>
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



    </body>



</html>