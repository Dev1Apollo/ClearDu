<?php

ob_start();

include('../config.php');
$where = "where 1=1 ";
if (isset($_REQUEST['Search_Txt'])) {
    if ($_REQUEST['Search_Txt'] != '') {

        $where.=" and  locationName like '%" . $_REQUEST[Search_Txt] . "%' ";
    }
}
$sql1 = "SELECT * FROM `location`  " . $where . " and isDelete='0'  and  istatus='1' order by  locationId desc";




$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmassigncase.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  Branch Name"
 . "\t  State Name"
 . "\t    District Name"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {
    
    $State = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='" . $rows['stateId'] . "'"));
    $District = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `district`  where isDelete='0'  and  istatus='1' and districtId='" . $rows['districtId'] . "'"));

    echo
    $i
    . "\t" . $rows['locationName']
    . "\t" . $State['stateName']
    . "\t" . $District['districtName']
    . "\n";
    $i++;
}
?>
