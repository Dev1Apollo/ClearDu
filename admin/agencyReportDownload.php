<?php

ob_start();

include('../config.php');
$where = "where 1=1 ";
if (isset($_REQUEST['Search_Txt'])) {
    if ($_REQUEST['Search_Txt'] != '') {

        $where.=" and  agencyname like '%$_REQUEST[Search_Txt]%'";
    }
}

$sql1 = "SELECT * FROM `agency`  " . $where . " and isDelete='0'  and  istatus='1' order by  Agencyid desc";




$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'agencyReportDownload.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  Agency Name"
 . "\t  Branch Name"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {


    $location = "SELECT * FROM `agncylocation`  where isDelete='0'  and  istatus='1' and agencyid='" . $rows['Agencyid'] . "'";
    $resultL = mysqli_query($dbconn, $location);
    $locname = '';
    while ($rowl = mysqli_fetch_array($resultL)) {
        $locationname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' and locationId='" . $rowl['locationid'] . "' order by locationId asc"));

        $locname = $locationname['locationName'] . ',' . $locname;
    }
    $locname = rtrim($locname, ", ");

    echo
    $i
    . "\t" . $rows['agencyname']
    . "\t" . $locname
    . "\n";
    $i++;
}
?>
