<?php

ob_start();

include('../config.php');
include('../common.php');
$connect = new connect();
$where = "where 1=1";
//$strLocationID = '0';
//$stateid = '0';
//$user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
//while ($userid = mysqli_fetch_array($user)) {
//    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
//}
//$strLocationID = rtrim($strLocationID, ", ");

if ($_REQUEST['agency'] != "") {

    $where.=" and  agencyid = '" . $_REQUEST['agency'] . "' ";
}


if ($_REQUEST['completedstatus'] != "") {

    if ($_REQUEST['completedstatus'] == 8) {
        $where.=" and is_assignto_fos = '1' and fosid > '0' and   fos_completed_status = '0' ";
    } else if ($_REQUEST['completedstatus'] == 9) {
        $where.=" and is_assignto_fos = '0' and fosid = '0'  ";
    } else {
        $where.=" and  fos_completed_status = '" . $_REQUEST['completedstatus'] . "' ";
    }
}

$FormDate = $_REQUEST['FormDate'];
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {

    $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y') ";
}
$toDate = $_REQUEST['toDate'];
;
if ($_REQUEST['toDate'] != NULL && isset($_REQUEST['toDate'])) {

    $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $_REQUEST['toDate'] . "','%d-%m-%Y') ";
}

$sql1 = "SELECT * FROM `colleted_application` " . $where . " and  is_assignto_am ='1' and  am_accaptance='1'      ";




$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmassigncase.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  Allocation Date"
 . "\t  Supervisor Assigned Date Time"
 . "\t  unique Id"
 . "\t  Account No"
 . "\t  Agency Name"
 . "\t  PRODUCT"
 . "\t  App Id"
 . "\t  Bkt"
 . "\t  Customer Name"
 . "\t  Fathers name"
 . "\t  Asset Make"
 . "\t  Branch"
 . "\t  State"
 . "\t  Due Month"
 . "\t  Allocation Date"
 . "\t  Allocation CODE"
 . "\t  Bounce Reason"
 . "\t  Loan amount"
 . "\t  Loan booking Date"
 . "\t  Loan maturity date"
 . "\t  Frist Emi Date"
 . "\t  Due date"
 . "\t  Emi Amount Collected"
 . "\t  Installment_Overdue_Amount"
 . "\t  Bcc"
 . "\t  Lpp"
 . "\t  Total penlty"
 . "\t  Principal outstanding"
 . "\t  Vehicle Registration No"
 . "\t  Supplier"
 . "\t  Tenure"
 . "\t  Customer Address"
 . "\t  Contact Number"
 . "\t  Collection Manager"
 . "\t  State Manager "
 . "\t  Ref_1_Name"
 . "\t  Contact_Detail"
 . "\t  Ref_2_Name"
 . "\t  Contact_Detail_ref2"
 . "\t  Fos Name"
 . "\t  Fos Id"
 . "\t  Fos Comment"
 . "\t  Fos Status"
 . "\t  Payment Collected Date"
 . "\t  Payment Collected Amount"
 . "\t  Penal Amount Collected"
 . "\t  Total  Amount Collected"
 . "\t  ptp Re-schedule date"
 . "\t  PTP Date"
 . "\t  PTP Amount"
 . "\t  Time Slot"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));
    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rows['fos_completed_status'] . "'"));
    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $rows['fosid'] . "'"));

    echo
    $i
    . "\t" . $rows['strEntryDate']
    . "\t" . $rows['assign_as_datetime']
    . "\t" . $rows['uniqueId']
    . "\t" . $rows['Account_No']
    . "\t" . $asname['agencyname']
    . "\t" . $rows['PRODUCT']
    . "\t" . $rows['App_Id']
    . "\t" . $rows['Bkt']
    . "\t" . $rows['Customer_Name']
    . "\t" . $rows['Fathers_name']
    . "\t" . $rows['Asset_Make']
    . "\t" . $rows['Branch']
    . "\t" . $rows['State']
    . "\t" . $rows['Due_Month']
    . "\t" . $rows['Allocation_Date']
    . "\t" . $rows['Allocation_CODE']
    . "\t" . $rows['Bounce_Reason']
    . "\t" . $rows['Loan_amount']
    . "\t" . $rows['Loan_booking_Date']
    . "\t" . $rows['Loan_maturity_date']
    . "\t" . $rows['Frist_Emi_Date']
    . "\t" . $rows['Due_date']
    . "\t" . $rows['Emi_amount']
    . "\t" . $rows['Installment_Overdue_Amount']
    . "\t" . $rows['Bcc']
    . "\t" . $rows['Lpp']
    . "\t" . $rows['Total_penlty']
    . "\t" . $rows['Principal_outstanding']
    . "\t" . $rows['Vehicle_Registration_No']
    . "\t" . $rows['Supplier']
    . "\t" . $rows['Tenure']
    . "\t" . $connect->RemoveSpecialChapr($rows['Customer_Address'])
    . "\t" . $rows['Contact_Number']
    . "\t" . $rows['Collection_Manager']
    . "\t" . $connect->RemoveSpecialChapr($rows['State_Manager'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Ref_1_Name'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Contact_Detail'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Ref_2_Name'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Contact_Detail_ref2'])
    . "\t" . $fos['employeename']
    . "\t" . $fos['loginId']
    . "\t" . $connect->RemoveSpecialChapr($rows['fos_comment'])
    . "\t" . $status['status']
    . "\t" . $rows['Payment_Collected_Date']
    . "\t" . $rows['Payment_Collected_Amount']
    . "\t" . $rows['penal']
    . "\t" . $rows['totalamt']
    . "\t" . $rows['ptp_datetime']
            . "\t" . $rows['PTP_Date']
            . "\t" . $rows['PTP_Amount']
            . "\t" . $rows['Time_Slot']
    . "\n";
    $i++;
}
?>
