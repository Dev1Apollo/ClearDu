<?php

ob_start();

include('../config.php');

$strLocationID = '0';
// echo "<pre>";
$agencymanagerid = $_SESSION['agencysupervisor']['agencymanagerid'];
$user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $agencymanagerid . "'  ");
while ($userid = mysqli_fetch_array($user)) {
    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
}
$strLocationID = rtrim($strLocationID, ", ");

$useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $agencymanagerid . "' "));

$iBankId = 0;
$where = "";
if(isset($_REQUEST['bankid']) && $_REQUEST['bankid'] != ""){
    $iBankId = $_REQUEST['bankid'];    
    $where .= " and iBankId='".$_REQUEST['bankid']."'";
} 

if(isset($_REQUEST['cycleId']) && $_REQUEST['cycleId'] != ""){
    $cycleId = $_REQUEST['cycleId'];    
    $where .= " and cycle='".$_REQUEST['cycleId']."'";
}
    
$branch = "";
if ($_SESSION['Type'] == 'Agency supervisor') {
    $branch = "SELECT SUM(IF(fosid != 0, 1, 0)) AS totalcount,
            SUM(IF(fosid = 0, 1, 0)) AS pendingCount, 
            application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' ".$where." group by application.customer_city_id,application.Branch";
            
    $branchwiseVisitData = "SELECT SUM(IF(fos_completed_status != 0, 1, 0)) AS VisitDone,
            SUM(IF(fos_completed_status = 0, 1, 0)) AS VisitPending, 
            application.customer_city_id,application.Branch  FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' and fosid!= 0 ".$where." group by application.customer_city_id,application.Branch";
            
    $branchwiseStatusData = "SELECT SUM(IF(fos_completed_status = 1, 1, 0)) AS PaymentCollected,
        SUM(IF(fos_completed_status = 2, 1, 0)) AS RefusetoPay,
        SUM(IF(fos_completed_status = 3, 1, 0)) AS PTPResheduled,
        SUM(IF(fos_completed_status = 4, 1, 0)) AS CustomerNotAvailable,
        SUM(IF(fos_completed_status = 5, 1, 0)) AS PTPfortheday,
        SUM(IF(fos_completed_status = 7, 1, 0)) AS AlreadyPaid,
        SUM(IF(fos_completed_status = 17, 1, 0)) AS BrokenPTP,
        SUM(IF(fos_completed_status = 18, 1, 0)) AS PartialPaymentCollected,
        SUM(IF(fos_completed_status = 19, 1, 0)) AS Other,
        application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $strLocationID . ") and application.am_accaptance = 1 and agencyid='" . $useragency['agencyname'] . "' and fosid!= 0 ".$where." group by application.customer_city_id,application.Branch";
        
    $fosPaidSummary = mysqli_query($dbconn, "select agencymanager.employeename,
            (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.fos_completed_status=1
            and application.iStatus=1 and application.isDelete=0 ". $where ." and  customer_city_id in  (" . $strLocationID . ")) as Collected,
            (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.fos_completed_status != 1 
            and application.iStatus=1 and application.isDelete=0 ". $where ." and  customer_city_id in  (" . $strLocationID . ")) as OtherStatus,
            (select count(*) from application where application.fosid = agencymanager.agencymanagerid and application.iStatus=1 and 
            application.isDelete=0 ". $where ."  and  customer_city_id in  (" . $strLocationID . ")) as TotalCount from agencymanager 
            where type='FOS' and agencyname='" . $useragency['agencyname'] . "' and agencymanager.istatus=1 and agencymanager.isDelete=0 
            and agencymanager.agencymanagerid in (select agencymanagerlocation.iagencymanagerid from agencymanagerlocation where 
            agencymanager.agencymanagerid=agencymanagerlocation.iagencymanagerid) order by agencymanager.employeename asc");
}

$result1 = mysqli_query($dbconn, $branch);
$result2 = mysqli_query($dbconn, $branchwiseVisitData);
$result3 = mysqli_query($dbconn, $branchwiseStatusData);
//$date=date('d-m-Y');

$filename = 'ApplicationDetails.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo "Branch wise Cases"
    . "\n";
echo
"Location"
 . "\t  Allocated"
 . "\t  Non Allocated"
 . "\t  Total"
 . "\n";
$i = 1;
$AllocatedTotal = 0;
$NonAllocatedTotal = 0;
$casesgrandTotal = 0;
while ($rows = mysqli_fetch_array($result1)) {
    $totalCase = $rows['totalcount']+$rows['pendingCount'];
    
    $AllocatedTotal += $rows['totalcount'];
    $NonAllocatedTotal += $rows['pendingCount'];
    $casesgrandTotal += $totalCase;
    echo
    $rows['Branch']
    . "\t" . $rows['totalcount']
    . "\t" . $rows['pendingCount']
    . "\t" . $totalCase
    . "\n";
    $i++;
}
echo
"Grand Total"
 . "\t ". $AllocatedTotal
 . "\t ". $NonAllocatedTotal
 . "\t ". $casesgrandTotal
 . "\n";
 
 
/*****************************Brach Wise Visit Data*******************************/
echo  "\n";
echo  "\n";
echo "Branch wise Allocation"
    . "\n";
echo
"Location"
 . "\t  Visit Done"
 . "\t  Visit Pending"
 . "\t  Total"
 . "\n";
$i = 1;
$VisitDoneTotal = 0;
$VisitPendingTotal = 0;
$totalVisit = 0;
while ($rowData = mysqli_fetch_array($result2)) {
    $visitTotal = 0;
    $visitTotal = $rowData['VisitPending'] + $rowData['VisitDone'];
    
    $VisitDoneTotal += $rowData['VisitDone'];
    $VisitPendingTotal += $rowData['VisitPending'];
    $totalVisit += $visitTotal;
    echo
    $rowData['Branch']
    . "\t" . $rowData['VisitDone']
    . "\t" . $rowData['VisitPending']
    . "\t" . $visitTotal
    . "\n";
    $i++;
}
echo
"Grand Total"
 . "\t ". $VisitDoneTotal
 . "\t ". $VisitPendingTotal
 . "\t ". $totalVisit
 . "\n";
 
 /*****************************Brach Wise Visit Status Data*******************************/
echo  "\n";
echo  "\n";
echo "Branch Wise Visit Status"
    . "\n";
echo
"Location"
 . "\t  Payment Collected"
 . "\t  Refuse to Pay"
 . "\t PTP Re-sheduled"
 . "\t Customer Not Available"
 . "\t PTP for the day"
 . "\t Already Paid"
 . "\t Broken PTP"
 . "\t Partial Payment Collected"
 . "\t Other"
 . "\t Total"
 . "\n";
$i = 1;
$PaymentCollectedTotal= 0;
$RefusetoPayTotal = 0;
$PTPResheduledTotal = 0;
$CustomerNotAvailableTotal = 0;
$PTPforthedayTotal = 0;
$AlreadyPaidTOtal = 0;
$BrokenPTPTotal = 0;
$PartialPaymentCollectedTotal = 0;
$OtherTotal = 0;
$GrandTotal =0;
while ($row = mysqli_fetch_array($result3)) {
    $total = $row['PaymentCollected']+$row['RefusetoPay']+$row['PTPResheduled']+$row['CustomerNotAvailable']+$row['PTPfortheday']+$row['AlreadyPaid']+$row['BrokenPTP']+$row['PartialPaymentCollected']+$row['Other'];
    
    $PaymentCollectedTotal+=$row['PaymentCollected']; 
    $RefusetoPayTotal+=$row['RefusetoPay']; 
    $PTPResheduledTotal+= $row['PTPResheduled']; 
    $CustomerNotAvailableTotal+= $row['CustomerNotAvailable']; 
    $PTPforthedayTotal+=$row['PTPfortheday']; 
    $AlreadyPaidTOtal+=$row['AlreadyPaid']; 
    $BrokenPTPTotal+=$row['BrokenPTP']; 
    $PartialPaymentCollectedTotal+=$row['PartialPaymentCollected']; 
    $OtherTotal+=$row['Other']; 
    $GrandTotal += $total; 
    
    echo
    $row['Branch']
    . "\t" . $row['PaymentCollected']
    . "\t" . $row['RefusetoPay']
    . "\t" . $row['PTPResheduled']
    . "\t" . $row['CustomerNotAvailable']
    . "\t" . $row['PTPfortheday']
    . "\t" . $row['AlreadyPaid']
    . "\t" . $row['BrokenPTP']
    . "\t" . $row['PartialPaymentCollected']
    . "\t" . $row['Other']
    . "\t" . $total
    . "\n";
    $i++;
}
echo
"Grand Total"
 . "\t ". $PaymentCollectedTotal
 . "\t ". $RefusetoPayTotal
 . "\t ". $PTPResheduledTotal
 
 . "\t ". $CustomerNotAvailableTotal
 . "\t ". $PTPforthedayTotal
 . "\t ". $AlreadyPaidTOtal
 . "\t ". $BrokenPTPTotal
 . "\t ". $PartialPaymentCollectedTotal
 . "\t ". $OtherTotal
 . "\t ". $GrandTotal
 
 . "\n";
 
/*****************************FOS Paid Summary*******************************/
echo  "\n";
echo  "\n";
echo "FOS Paid Summary"
    . "\n";
echo
"FOS Name"
 . "\t  Total Cases"
 . "\t  Paid Cases"
 . "\t  Unpaid Cases"
 . "\t  Paid %"
 . "\n";
$i = 1;
while ($rowfosPaidSummary = mysqli_fetch_array($fosPaidSummary)) {
    $TotalCount = $rowfosPaidSummary['TotalCount'];
    $Collected = $rowfosPaidSummary['Collected'];
    $OtherStatus = $rowfosPaidSummary['OtherStatus'];
    $paidPercentage = 0;
    if($TotalCount != 0 && $Collected != 0){
        $paidPercentage = round(($Collected / $TotalCount) * 100,2) . " %";
    } else {
        $paidPercentage = round(0,2);
    }
    if($rowfosPaidSummary['TotalCount'] > 0){
    echo
    $rowfosPaidSummary['employeename']
    . "\t" . $rowfosPaidSummary['TotalCount']
    . "\t" . $rowfosPaidSummary['Collected']
    . "\t" . $rowfosPaidSummary['OtherStatus']
    . "\t" . $paidPercentage
    . "\n";
    $i++;
    }
}

?>
