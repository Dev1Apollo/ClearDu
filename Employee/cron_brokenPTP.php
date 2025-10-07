<?php
//9/10/2024
ob_start()_
error_reporting(E_ALL);

//$dbhost = "localhost";
//$dbuser = "httpastutemanage";
//$dbpass = "#4XP*Gh8}mWR";
//$dbname = "httpastutemanage_credsure";

$dbhost = "localhost";
$dbuser = "fieldadmin";
$dbpass = "URr?Sf3BzbQo";
$dbname = "cleardu-field";
    
$dbconn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die('Could not connect: ' . mysqli_connect_error());



$currentmonth = date('Y-m-d');
//Old
// echo $sqlbroken="update `application` set fos_completed_status=17 WHERE DATE_FORMAT(STR_TO_DATE(PTP_Date, '%d-%m-%Y %T'), '%Y-%m-%d') < '".$currentmonth."' and fos_completed_status IN (0,5) and am_accaptance=1 and PTP_Date!='' and  (ptp_datetime='' or ptp_datetime IS NULL)";
// $resbroken=mysqli_query($dbconn,$sqlbroken);

//New 
//$sqlbroken="update `application` set fos_completed_status=17 WHERE PTP_Date!='' and DATE_FORMAT(STR_TO_DATE(PTP_Date, '%d-%m-%Y %T'), '%Y-%m-%d') < '".$currentmonth."' and fos_completed_status IN (0,5) and am_accaptance=1 and   (ptp_datetime='' or ptp_datetime IS NULL)";
echo $sqlbroken="update `application` set fos_completed_status=17 WHERE DATE_FORMAT(STR_TO_DATE(ptp_datetime, '%d-%m-%Y %T'), '%Y-%m-%d') < '".$currentmonth."' and fos_completed_status IN (0,5) and am_accaptance=1";
$resbroken=mysqli_query($dbconn,$sqlbroken);

//echo $sqlbroken="update `application` set fos_completed_status=5 WHERE DATE_FORMAT(STR_TO_DATE(PTP_Date, '%d-%m-%Y %T'), '%Y-%m-%d') = '".$currentmonth."' and fos_completed_status=0 and am_accaptance=1 and PTP_Date!='' ";
echo "<br />";
echo "<br />";
// $resbroken=mysqli_query($dbconn,$sqlbroken);

//Old
// echo $sqlbroken="update  `application` set fos_completed_status=17 where fosid!=0 and am_accaptance=1 and is_assignto_fos=1 and 
// (DATE_FORMAT(STR_TO_DATE(ptp_datetime,'%d-%m-%Y %T'),'%Y-%m-%d') < '".$currentmonth."' 
//  and fos_completed_status IN (3,5) and fos_submit_datetime!='') and ptp_datetime!='' ";
//  $resbroken=mysqli_query($dbconn,$sqlbroken);

//New
echo $sqlbroken="update  `application` set fos_completed_status=17 where fosid!=0 and am_accaptance=1 and is_assignto_fos=1 and ptp_datetime!='' and 
(DATE_FORMAT(STR_TO_DATE(ptp_datetime,'%d-%m-%Y %T'),'%Y-%m-%d') < '".$currentmonth."' 
 and fos_completed_status IN (3,5)) ";
//and fos_submit_datetime!=''
echo "<br />";
echo "<br />";
 $resbroken=mysqli_query($dbconn,$sqlbroken);

//Old
// echo $sqlbroken="update  `application` set fos_completed_status=5 where fosid!=0 and am_accaptance=1 and is_assignto_fos=1 and 
// (DATE_FORMAT(STR_TO_DATE(ptp_datetime,'%d-%m-%Y %T'),'%Y-%m-%d') = '".$currentmonth."' 
//  and fos_completed_status=3 and fos_submit_datetime!='') and ptp_datetime!='' ";
// $resbroken=mysqli_query($dbconn,$sqlbroken);

//New
echo $sqlbroken="update  `application` set fos_completed_status=5 where fosid!=0 and am_accaptance=1 and is_assignto_fos=1 and ptp_datetime!='' and 
(DATE_FORMAT(STR_TO_DATE(ptp_datetime,'%d-%m-%Y %T'),'%Y-%m-%d') = '".$currentmonth."' 
 and fos_completed_status=3)";
// and fos_submit_datetime!=''
echo "<br />";
echo "<br />";
$resbroken=mysqli_query($dbconn,$sqlbroken);

?>
