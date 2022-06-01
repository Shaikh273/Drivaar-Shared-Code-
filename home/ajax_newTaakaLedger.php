<?php
if(isset($_GET['tid']) && isset($_GET['mtr']) && isset($_GET['wgt']) && isset($_GET['undt']) && isset($_GET['machno']))
{
    include("config.php");
    $tid = $_GET['tid'];
    $machno = $_GET['machno'];
    $mtr = $_GET['mtr'];
    $wgt = $_GET['wgt'];
    $undt = $_GET['undt'];
    $sql = "INSERT INTO `taaka_full_details`(`taaka_ID`, `mach_ID`, `total_weight`, `total_meter`, `unload_date`) VALUES ('$tid',$machno,'$wgt','$mtr','$undt')";
    // $fire = mysqli_query($connection,$sql);
    if ($fire = mysqli_query($connection,$sql)) 
    {
        $last_id = $connection->insert_id;
        $date=date_create($undt);
        $date = date_format($date,"d/m/Y");
        echo "$date@#@$tid@#@$machno@#@$mtr@#@$wgt@#@".$_GET['awgt']."@#@<button type='button' class='btn btn-warning' value='$last_id' id='upd-$last_id' data-toggle='modal' data-target='#exampleModal' data-whatever='@mdo'><i class='fa fa-paint-brush' aria-hidden='true'></i></button>
                <button type='button' class='btn btn-danger' value='$last_id' id='del-$last_id' onclick='delNTE($last_id)'><i class='fa fa-trash'></i></button>";
    }else {
        echo "ERROR";
    }
}
?>