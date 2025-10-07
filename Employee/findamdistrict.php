<?php

error_reporting(0);
include('../config.php');
include('IsLogin.php');
?>
<?php
    
$sId = intval($_GET['sId']);
$strLocationID = '';

$user = mysqli_query($dbconn, "SELECT * FROM `agncylocation`  where  agencyid='" . $_SESSION['agencyname'] . "'  and stateId = '" . $sId . "' ");
while ($userid = mysqli_fetch_array($user)) {
    $strLocationID = $userid['districtId'] . ',' . $strLocationID;
}
$strLocationID = rtrim($strLocationID, ", ");
$result = mysqli_query($dbconn, "select * from district  where  istatus='1' and isDelete='0' and districtId in (" . $strLocationID . ")  and stateId=" . $sId . " ");
$data = '<select name="district" id="district" class="form-control"  required onchange="getlocation();">
<option value="">Select District</option>';
while ($row = mysqli_fetch_array($result)) {
    $data.='<option value=' . $row['districtId'] . '>' . $row['districtName'] . '</option>';
}
$data .='</select>';
echo $data;
?>
