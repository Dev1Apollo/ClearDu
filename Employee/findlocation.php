<?php

include('../config.php');
?>
<?php

$sId = intval($_GET['sid']);

//$strLocationID = '';
//$user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//while ($userid = mysqli_fetch_array($user)) {
//    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//}
//$strLocationID = rtrim($strLocationID, ", ");

//and locationId in (".$strLocationID.")
$result = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0' and stateId=" . $sId . "  order by locationId ASC");
$data = '<select name="location" id="location" class="form-control"  required onchange="getagency();" >
<option value="">Select location Name</option>';
while ($row = mysqli_fetch_array($result)) {
    $data.='<option value=' . $row['locationId'] . '>' . $row['locationName'] . '</option>';
}
$data .='</select>';
echo $data;
?>
