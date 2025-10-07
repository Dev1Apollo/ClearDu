<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
$resquery = mysqli_fetch_array(mysqli_query($dbconn,"select max(applicationid) as `maxId` from colleted_application"));
$startId=$resquery['maxId'];
//$startId=1;
$resqueryapp=mysqli_query($dbconn,"select * from application order by applicationid asc");
while ($rowQuery=mysqli_fetch_array($resqueryapp)) {
    $newAppId=$startId+$rowQuery['applicationid'];
    //$updfoshistory=mysqli_query($dbconn,"UPDATE foshistory set appid=".$newAppId." where foshistory.appid=".$rowQuery['applicationid']." and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y %T'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE('01-09-2019', '%d-%m-%Y'), '%Y-%m-%d')");
   // $updreturncasehistory=mysqli_query($dbconn,"UPDATE returncasehistory set appid=".$newAppId." where returncasehistory.appid=".$rowQuery['applicationid']." and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y %T'), '%Y-%m-%d') >= DATE_FORMAT(STR_TO_DATE('01-09-2019', '%d-%m-%Y'), '%Y-%m-%d')");
    $updApp=mysqli_query($dbconn,"UPDATE application set applicationid=".$newAppId." where application.applicationid=".$rowQuery['applicationid']);
    
}
  
mysqli_query($dbconn,"UPDATE application set uniqueId=CONCAT('ALPS',applicationid)");
  
