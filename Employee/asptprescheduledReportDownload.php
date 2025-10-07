<?php

ob_start();

include('../config.php');

    $strLocationID = '0';
    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "'  ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");
    
    $sql1 = "SELECT * FROM `application` where locationid in(".$strLocationID.") and fos_completed='2' and fos_completed_status='3'   ";
    








$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'asptprescheduled.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  unique Id"
 . "\t  Account No"
 . "\t  Agency Name"
 . "\t  PRODUCT"
 . "\t  App Id"
 . "\t  Return Till"
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
 . "\t  Emi amount"
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
 . "\t  Remarks"
 . "\t  Payment Collected Date"
 . "\t  Payment Collected Amount"
 . "\t  ptp datetime"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where  agencymanagerid='" . $rows['fosid'] . "' "));
   

    echo
    $i
    . "\t" . $rows['uniqueId']
    . "\t" . $rows['Account_No']
    . "\t" . $asname['agencyname']
 
    . "\t" . $rows['PRODUCT']
    . "\t" . $rows['App_Id']
    . "\t" . $rows['excel_return_date']            
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
    . "\t" . $rows['Customer_Address']
    . "\t" . $rows['Contact_Number']
    . "\t" . $rows['Collection_Manager']
    . "\t" . $rows['State_Manager']
    . "\t" . $rows['Ref_1_Name']
    . "\t" . $rows['Contact_Detail']
    . "\t" . $rows['Ref_2_Name']
    . "\t" . $rows['Contact_Detail_ref2']
    . "\t" . $rows['fos_comment']
    . "\t" . $rows['Payment_Collected_Date']
    . "\t" . $rows['Payment_Collected_Amount']
    . "\t" . $rows['ptp_datetime']
    . "\n";
    $i++;
}
?>

