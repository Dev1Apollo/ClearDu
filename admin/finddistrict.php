<?php
error_reporting(0);
include('../config.php');
include('IsLogin.php');
?>
<?php
$sId=intval($_GET['sId']);

$result=mysqli_query($dbconn,"select * from district  where  istatus='1' and isDelete='0' and stateId=".$sId." ");
$data='<select name="district" id="district" class="form-control"  required onchange="getlocation();">
<option value="">Select District</option>';
 while($row=mysqli_fetch_array($result)) { 
	$data.='<option value='.$row['districtId'].'>'.$row['districtName'].'</option>';
}
$data .='</select>';
echo $data;
?>
