<?php

ob_start();

include('../config.php');
?>
<?php

$where = "where 1=1";
if ($_REQUEST['location'] != NULL || $_REQUEST['State'] != NULL) {



    if ($_REQUEST['location'] != NULL && isset($_REQUEST['location'])) {

        //$where.=" and  locationid = '" . $_REQUEST['location'] . "'";
        $where.=" and  customer_city_id = '" . $_REQUEST['location'] . "'";
    }

    if ($_REQUEST['State'] != NULL && isset($_REQUEST['State'])) {

        $where.=" and  stateid = '" . $_REQUEST['State'] . "'";
    }
}
$strLocationID = '0';
$stateid = '0';
$user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
while ($userid = mysqli_fetch_array($user)) {
    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
}
$strLocationID = rtrim($strLocationID, ", ");
$sql1 = "SELECT * FROM `application` where  customer_city_id in  (" . $strLocationID . ") and  is_assignto_am='1' and am_accaptance='3'  ";



$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmviewandreturncase.xls';

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
 . "\t  Visit Address"
 . "\t  Contact Number"
 . "\t  Alternate Contact Number"
 . "\t  Collection Manager"
 . "\t  State Manager "
 . "\t  Ref_1_Name"
 . "\t  Contact_Detail"
 . "\t  Ref_2_Name"
 . "\t  Contact_Detail_ref2"
 . "\t  Return Reason"
 . "\t  ptp Re-schedule date"
 . "\t  PTP Date"
 . "\t  PTP Amount"
 . "\t  Time Slot"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));

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
    . "\t" . $rows['Customer_Address']
    . "\t" . $rows['visit_address']
    . "\t" . $rows['Contact_Number']
    . "\t" . $rows['alternate_contact_number']
    . "\t" . $rows['Collection_Manager']
    . "\t" . $rows['State_Manager']
    . "\t" . $rows['Ref_1_Name']
    . "\t" . $rows['Contact_Detail']
    . "\t" . $rows['Ref_2_Name']
    . "\t" . $rows['Contact_Detail_ref2']
    . "\t" . $rows['reason']
    . "\t" . $rows['ptp_datetime']
    . "\t" . $rows['PTP_Date']
    . "\t" . $rows['PTP_Amount']
    . "\t" . $rows['Time_Slot']
    . "\n";
    $i++;
}
?>
