<?php

include('../config.php');

?>
<?php

$locationid=intval($_GET['locationid']);
$agencymanagerid='0';
$result= mysqli_query($dbconn,"select * from agencymanagerlocation  where  istatus='1' and isDelete='0' and iLocationId=".$locationid." order by iLocationId ASC");
while($row=mysqli_fetch_array($result)) { 
    
  $agencymanagerid = $row['iagencymanagerid'] . ',' . $agencymanagerid;
}
 $agencymanagerid = rtrim($agencymanagerid,", ");
 
 $agencyid=  mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid='".$_SESSION['agencymanagerid']."' "));
 
  $result1=mysqli_query($dbconn,"select * from agencymanager  where  istatus='1' and isDelete='0' and agencymanagerid in (".$agencymanagerid.") and agencyname='".$agencyid['agencyname']."' and type='Agency supervisor' ");
$data='<select name="agencysupervisor" id="agencysupervisor" class="form-control"  required  >
<option value="">Select Agency Manager Name</option>';
 while($rows1=mysqli_fetch_array($result1)) { 
       $agencyname=  mysqli_fetch_array(mysqli_query($dbconn, "SELECT agencyname FROM agency where Agencyid='".$rows1['agencyname']."'"));
	$data.='<option value='.$rows1['agencymanagerid'].'>'. $agencyname['agencyname'].'  '.$rows1['employeename'].'</option>';
}
$data .='</select>';
echo $data;




?>
