<?php
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
// if(!isset($_SESSION['load']) || !isset($_SESSION['uid']))
// {
//     header("location: login.php");
// }
// $_SESSION['page_id']=1;
// //include('authentication.php');
// include('config.php');
$page_title="ANSARI";

if (isset($_POST['submit']))
{
    include("config.php");
	//date_default_timezone_set('Europe/London');
	$enquire_date = date("d-M-Y h:i:s A");
	
    $name = $_POST['depot_name'];
    $supervisor = $_POST['depot_supervisor'];
   
    
    // $dbc = new Databaseclass();
 $tFieldsName = array("name", "supervisor");
 $tFieldValues = "'".$name."','".$supervisor."'";
  $sql = "INSERT INTO add_depot(" . implode(",", $tFieldsName) . ") VALUES($tFieldValues)";
    error_log($sql, 0);
    $fire = mysqli_query($connection, $sql);
    // $fetch = mysqli_fetch_array($fire);
//  $dbc->insert_qry(implode(",",$tFieldsName),$tFieldValues,'add_depot');
    
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
    </head>
    <body class="skin-default-dark fixed-layout">
        <?php
            include('loader.php');
        ?>
        <div id="main-wrapper">
            <?php
                include('header.php');
                // include('menu.php');
            ?>
            <div class="page-wrapper">
                <div class="container-fluid">
                    
<main class=" container-fluid  animated mt-4">
            <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="#" method="POST" class="" data-aire-component="form" data-aire-id="0">

			<input type="hidden" name="_token" value="XPPvmuqm6tl2EbKO1nMHFiTdOhO1hZwUmVHrNLuW">
		
		
	<div class="card">
            
        <div class="card-header " style="background-color: rgb(255 236 230);margin-bottom: 25px;">
            <div class="d-flex justify-content-between align-items-center">
               <div class="header">Add New Depot</div>
                            </div>
        </div>
    
            <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" data-aire-component="group" data-aire-for="name">
    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-name3">
	Name:
</label>


            <input type="text" class="form-control" data-aire-component="input" name="depot_name" data-aire-for="name" required>

    
    
    <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="name">
            </div>

</div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group" data-aire-component="group" data-aire-for="email">
    <label class=" cursor-pointer" data-aire-component="label" for="__aire-0-email6">
	Supervisor(s);
</label>


            <input type="text" class="form-control" data-aire-component="input" name="depot_supervisor" data-aire-for="email" required>

    
    
    <div class="text-danger" data-aire-component="errors" data-aire-validation-key="group_errors" data-aire-for="email">
            </div>

</div>
</div>
                            
       
            
    
	
	
	
</form>

            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary " name="submit" data-aire-component="button" type="submit">
	Add Depot
</button>

                        <a href="depots.php" class="btn">Cancel</a>
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
        
    </body>

</html>