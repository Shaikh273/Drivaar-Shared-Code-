<?php
$state="";
if(isset($_GET['state']))
{
    $state=$_GET['state'];
}
else
{
    $state='current';
}
$sql="";
switch($state)
{
    case 'current':
            $sql="SELECT `employee_registration`.* FROM `employee_registration` inner join `emp_reg_detail` on `employee_registration`.`ID`=`emp_reg_detail`.`emp_ID` WHERE `emp_reg_detail`.`leave_date` like null";
        break;
    case 'leaved':
            $sql="SELECT `employee_registration`.* FROM `employee_registration` inner join `emp_reg_detail` on `employee_registration`.`ID`=`emp_reg_detail`.`emp_ID` WHERE `emp_reg_detail`.`leave_date` not like null";
        break;
    // case '':
    //     break;
    // case '':
    //     break;
}
$fire = mysqli_query($connection,$sql);
$i=1;
while($eow=mysqli_fetch_array($fire))
{
    ?>
    <tr>
        <td><?php echo $i;?></td>
        <td><?php echo $eow['full_name'];?></td>
        <td><?php echo $eow['ID'];?></td>
        <td><?php echo $eow['contact'];?></td>
        <td><?php echo $eow['gender'];?></td>
        <td><?php echo $eow['perm_city'];?></td>
        <td>
            
        </td>
    </tr>
    <?php
}

?>