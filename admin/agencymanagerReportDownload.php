<?php

ob_start();

include('../config.php');
$where = "where 1=1 ";

if (isset($_REQUEST['Location'])) {
    if ($_REQUEST['Location'] != '') {

        //$where.=" and  locationemployeelocation.iLocationId='" . $_POST['Location'] . "'";
        $where .= " and `agencymanager`.`agencymanagerid` in (SELECT iagencymanagerid FROM `agencymanagerlocation` where `agencymanagerlocation`.`iLocationId`  = '" . $_REQUEST['Location'] . "')";
    }
}
if (isset($_REQUEST['Type'])) {
    if ($_REQUEST['Type'] != '') {

        $where.=" and type='" . $_REQUEST['Type'] . "'";
    }
}
if (isset($_REQUEST['Search_Txt'])) {
    if ($_REQUEST['Search_Txt'] != '') {

        $where.=" and  employeename like '%$_REQUEST[Search_Txt]%'";
    }
}
if (isset($_REQUEST['agency'])) {
    if ($_REQUEST['agency'] != '') {

        $where.=" and  agencyname = '" . $_REQUEST['agency'] . "' ";
    }
}

$sql1 = "SELECT * FROM `agencymanager`  " . $where . " and isDelete='0'  and  istatus='1' order by  agencymanagerid desc";




$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'agencymanagerReportDownload.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  Agency Name"
 . "\t  Employee Name"
 . "\t  Type"
 . "\t  Login Id"
 . "\t  Assign Branch"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    $agency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency` WHERE Agencyid = '".$rows['agencyname']."' and isDelete='0' and istatus='1' "));
    
    $location = "SELECT * FROM `agencymanagerlocation`  where isDelete='0'  and  istatus='1' and iagencymanagerid='" . $rows['agencymanagerid'] . "'";
    $resultL = mysqli_query($dbconn, $location);
    $count = 1;
    $locname='';
    while ($rowl = mysqli_fetch_array($resultL)) {
        $Category = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' and locationId='" . $rowl['iLocationId'] . "' order by locationId asc"));
    
            $locname = $Category['locationName'] .','. $locname;
             
    }

    $locname = rtrim($locname, ", ");

    echo
    $i
    . "\t" . $agency['agencyname']
    . "\t" . $rows['employeename']
    . "\t" . $rows['type']
    . "\t" . $rows['loginId']
    . "\t" . $locname
    . "\n";
    $i++;
}
?>
