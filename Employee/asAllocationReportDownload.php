    <?php

ob_start();

include('../config.php');


$where = "where 1=1";

if ($_REQUEST['location'] != NULL && isset($_REQUEST['location'])) {
    // $state = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='" . $_REQUEST['State'] . "' order by  stateName asc"));
//        $location = mysqli_fetch_array(mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0' and locationId='" . $_REQUEST['location'] . "' order by locationId ASC"));
//
//        $where.=" and DESTINATION_CITY ='" . $location['locationName'] . "'";
    $where.=" and  customer_city_id = '" . $_REQUEST['location'] . "'";
}

$useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencysupervisorid'] . "' and isDelete=0"));
$strLocationID = '0';
$user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $_SESSION['agencysupervisorid'] . "' ");
while ($userid = mysqli_fetch_array($user)) {
    $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
}
$strLocationID = rtrim($strLocationID, ", ");
//$sql1 = "SELECT * FROM `application` " . $where . " and  customer_city_id in(" . $strLocationID . ") and  is_assignto_as='1'  and is_assignto_fos='0' and  agencyid='" . $useragency['agencyname'] . "' and am_accaptance = '1' ";
$sql1 = "SELECT * FROM `application` left join applicationpaymentcollectedhistory on application.applicationid=applicationpaymentcollectedhistory.applicationid " . $where . " and  customer_city_id in(" . $strLocationID . ") and is_assignto_fos='0' and  agencyid='" . $useragency['agencyname'] . "' and am_accaptance = '1' ";


$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'asAllocation.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t"."Allocation Date"
 . "\t"."Bank Name"
//  . "\t  Return Till"
 . "\t"."Supervisor Assigned Date Time"
 . "\t"."Unique Id"
 . "\t"."Account No"
 . "\t"."Agency Name"
 . "\t"."PRODUCT"
 . "\t"."App Id"
 . "\t"."Bkt"
 . "\t"."Customer Name"
 . "\t"."Fathers Name"
 . "\t"."Asset Make"
 . "\t"."Branch"
 . "\t"."Customer City"
 . "\t"."State"
 . "\t"."Cycle"
//  . "\t  Due Month"
 . "\t"."Allocation Date"
 . "\t"."Allocation CODE"
 . "\t"."Bounce Reason"
 . "\t"."Loan Amount"
 . "\t"."Loan Booking Date"
 . "\t"."Loan Maturity Date"
//  . "\t  Frist Emi Date"
 . "\t"."Due Date"
 . "\t"."Emi/Collectible Amount"
 . "\t"."Total POS Amount"
//  . "\t  Installment_Overdue_Amount"
//  . "\t  Bcc"
//  . "\t  Lpp"
 . "\t"."Total Penlty"
//  . "\t  Principal outstanding"
//  . "\t  Vehicle Registration No"
//  . "\t  Supplier"
//  . "\t  Tenure"
 . "\t"."Residence Address"
 . "\t"."Business Address"
 . "\t"."Contact Number"
 . "\t"."Alternate Contact Number"
//  . "\t  Collection Manager"
//  . "\t  State Manager "
 . "\t"."Ref_1_Name"
 . "\t"."Contact_Detail"
 . "\t"."Ref_2_Name"
 . "\t"."Contact_Detail_ref2"
 // . "\t"."Area Name"
 . "\t"."ptp Re-Schedule Date"
//  . "\t  PTP Date"
//  . "\t  PTP Amount"
//  . "\t  Time Slot"
 . "\t"."Pin Code" 
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));
    echo
    $i
    . "\t" . $rows['strEntryDate']
    . "\t" . $rows['Bank_Name']
    // . "\t" . $rows['excel_return_date']
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
    . "\t" . $rows['customer_city']
    . "\t" . $rows['State']
    . "\t" . $rows['cycle']
    // . "\t" . $rows['Due_Month']
    . "\t" . $rows['Allocation_Date']
    . "\t" . $rows['Allocation_CODE']
    . "\t" . $rows['Bounce_Reason']
    . "\t" . $rows['Loan_amount']
    . "\t" . $rows['Loan_booking_Date']
    . "\t" . $rows['Loan_maturity_date']
    // . "\t" . $rows['Frist_Emi_Date']
    . "\t" . $rows['Due_date']
    . "\t" . $rows['Emi_amount']
    . "\t" . $rows['Total_Pos_Amount']
    // . "\t" . $rows['Installment_Overdue_Amount']
    // . "\t" . $rows['Bcc']
    // . "\t" . $rows['Lpp']
    . "\t" . $rows['Total_penlty']
    // . "\t" . $rows['Principal_outstanding']
    // . "\t" . $rows['Vehicle_Registration_No']
    // . "\t" . $rows['Supplier']
    // . "\t" . $rows['Tenure']
    . "\t" . $rows['Customer_Address']
    . "\t" . $rows['Business_Address']
    . "\t" . $rows['Contact_Number']
    . "\t" . $rows['alternate_contact_number']
    // . "\t" . $rows['Collection_Manager']
    // . "\t" . $rows['State_Manager']
    . "\t" . $rows['Ref_1_Name']
    . "\t" . $rows['Contact_Detail']
    . "\t" . $rows['Ref_2_Name']
    . "\t" . $rows['Contact_Detail_ref2']
    . "\t" . $rows['ptp_datetime']
    // . "\t" . $rows['PTP_Date']
    // . "\t" . $rows['PTP_Amount']
    // . "\t" . $rows['Time_Slot']
    . "\t" . $rows['Pincode']
    . "\n";
    $i++;
}
?>

