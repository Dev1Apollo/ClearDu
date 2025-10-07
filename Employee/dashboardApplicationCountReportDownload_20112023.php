<?php

ob_start();

include('../config.php');


// if ($_SESSION['Type'] == 'Central Manager') {
    
// }

// if ($_SESSION['Type'] == 'Agency Manager') {
    
// }
$branch = "";
if ($_SESSION['Type'] == 'Agency supervisor') {
    $branch = "SELECT SUM(IF(fosid != 0, 1, 0)) AS totalcount,
            SUM(IF(fosid = 0, 1, 0)) AS pendingCount, 
            application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $_REQUEST['id'] . ") and application.am_accaptance = 1 and agencyid='" . $_REQUEST['agencyid'] . "' group by application.customer_city_id,application.Branch";
            
    $branchwiseVisitData = "SELECT SUM(IF(fos_completed_status != 0, 1, 0)) AS VisitDone,
            SUM(IF(fos_completed_status = 0, 1, 0)) AS VisitPending, 
            application.customer_city_id,application.Branch  FROM `application` where  customer_city_id in  (" . $_REQUEST['id'] . ") and application.am_accaptance = 1 and agencyid='" . $_REQUEST['agencyid'] . "' and fosid!= 0  group by application.customer_city_id,application.Branch";
            
    $branchwiseStatusData = "SELECT SUM(IF(fos_completed_status = 1, 1, 0)) AS PaymentCollected,
        SUM(IF(fos_completed_status = 2, 1, 0)) AS RefusetoPay,
        SUM(IF(fos_completed_status = 3, 1, 0)) AS PTPResheduled,
        SUM(IF(fos_completed_status = 4, 1, 0)) AS CustomerNotAvailable,
        SUM(IF(fos_completed_status = 5, 1, 0)) AS PTPfortheday,
        SUM(IF(fos_completed_status = 7, 1, 0)) AS AlreadyPaid,
        SUM(IF(fos_completed_status = 17, 1, 0)) AS BrokenPTP,
        SUM(IF(fos_completed_status = 18, 1, 0)) AS PartialPaymentCollected,
        SUM(IF(fos_completed_status = 19, 1, 0)) AS Other,
        application.customer_city_id,application.Branch FROM `application` where  customer_city_id in  (" . $_REQUEST['id'] . ") and application.am_accaptance = 1 and agencyid='" . $_REQUEST['agencyid'] . "' and fosid!= 0  group by application.customer_city_id,application.Branch";
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
 . "\t ". $visitTotal
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
    $GrandTotal+=$total; 
    
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
?>
