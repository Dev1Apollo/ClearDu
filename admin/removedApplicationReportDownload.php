<?php

ob_start();

include('../config.php');
include('../common.php');
$connect = new connect();
$where = "where 1=1";
$strLocationID = '0';
$stateid = '0';
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

    $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . " 00:00:00','%d-%m-%Y %H:%i:%s') ";
}
$toDate = $_REQUEST['toDate'];
;
if ($_REQUEST['toDate'] != NULL && isset($_REQUEST['toDate'])) {

    $where.="  and STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $_REQUEST['toDate'] . " 23:59:59','%d-%m-%Y %H:%i:%s') ";
}

$sql1 = "SELECT bulkremovalapplication.strEntryDate,Bank_Name,assign_as_datetime,uniqueId,Account_No,PRODUCT,App_Id,Bkt,Customer_Name,Fathers_name,Asset_Make,Branch,customer_city,State,cycle,Allocation_Date,
        Allocation_CODE,Bounce_Reason,Loan_amount,Loan_booking_Date,Loan_maturity_date,Due_date,Emi_amount,Total_Pos_Amount,Total_penlty,Customer_Address,Business_Address, Contact_Number,
        alternate_contact_number,Ref_1_Name,Contact_Detail,Ref_2_Name,Contact_Detail_ref2,Area_Name,fos_comment,status,AlternetMobileNo,Pincode,Payment_Collected_Date,applicationpaymentcollectedhistory.Payment_Collected_Amount,
        applicationpaymentcollectedhistory.penal,applicationpaymentcollectedhistory.totalamt,ptp_datetime,agencyid,fos_completed_status,bulkremovalapplication.fosid,bulkremovalapplication.applicationid,fos_submit_datetime
        FROM `bulkremovalapplication` left join (select applicationpaymentcollectedhistory.applicationid,status,Payment_Collected_Amount,penal,totalamt
           from 
           applicationpaymentcollectedhistory where isDelete=0 and (applicationpaymentcollectedhistory.Payment_Collected_Amount>0 or applicationpaymentcollectedhistory.penal>0 or applicationpaymentcollectedhistory.totalamt>0) ) as applicationpaymentcollectedhistory on bulkremovalapplication.applicationid=applicationpaymentcollectedhistory.applicationid " . $where . " and  bulkremovalapplication.is_assignto_am ='1' and bulkremovalapplication.isDelete=0 and  bulkremovalapplication.am_accaptance='1'";

$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmassigncase.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();
$header = "";
$header = "SrNo"
 . "\t"."Allocation Date"
 . "\t"."Bank Name"
//  . "\t"."Return Till"
 . "\t"."Supervisor Assigned Date Time"
 . "\t"."unique Id"
 . "\t"."Account No"
 . "\t"."Agency Name"
 . "\t"."PRODUCT"
 . "\t"."App Id"
 . "\t"."Bkt"
 . "\t"."Customer Name"
 . "\t"."Fathers name"
 . "\t"."Asset Make"
 . "\t"."Branch"
 . "\t"."Customer City"
 . "\t"."State"
 . "\t"."Cycle"
//  . "\t  Due Month"
 . "\t"."Allocation Date"
 . "\t"."Allocation CODE"
 . "\t"."Bounce Reason"
 . "\t"."Loan amount"
 . "\t"."Loan booking Date"
 . "\t"."Loan maturity date"
//  . "\t  Frist Emi Date"
 . "\t"."Due date"
 . "\t"."Emi/Collectible Amount"
 . "\t"."Total POS Amount"
//  . "\t  Installment_Overdue_Amount"
//  . "\t  Bcc"
//  . "\t  Lpp"
 . "\t"."Total penlty"
//  . "\t  Principal outstanding"
//  . "\t  Vehicle Registration No"
//  . "\t  Supplier"
//  . "\t  Tenure"
 . "\t"."Residence Address"
 . "\t"."Business Address"
//  . "\t"."Visit Address"
 . "\t"."Contact Number"
 . "\t"."Alternate Contact Number"
//  . "\t  Collection Manager"
//  . "\t  State Manager "
 . "\t"."Ref_1_Name"
 . "\t"."Contact_Detail"
 . "\t"."Ref_2_Name"
 . "\t"."Contact_Detail_ref2"
 . "\t"."Area Name"
 . "\t"."Fos Name"
 . "\t"."Fos Id"
 . "\t"."Fos Comment"
 . "\t"."Fos Status"
 . "\t"."Fos Submit Date"
 . "\t"."Fos Submit Time";
 for($iCounter = 1; $iCounter <= 10; $iCounter++){
    $header.= "\t"."Fos Status_" . $iCounter
     . "\t"."Fos Submit Date_" . $iCounter
     . "\t"."Fos Submit Time_". $iCounter;
 }
 $header.= "\t"."Payment Collected Date"
 . "\t"."Payment Collected Amount"
 . "\t"."Penal Amount Collected"
 . "\t"."Total  Amount Collected"
 . "\t"."ptp Re-schedule date"
//  . "\t"."PTP Date"
//  . "\t"."PTP Amount"
//  . "\t"."Time Slot"
 . "\t"."Pin code"
 . "\n";
echo $header;
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));
    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rows['fos_completed_status'] . "'"));
    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $rows['fosid'] . "'"));
    $fossubmit = explode(' ', $rows['fos_submit_datetime']);
    $data = "";
    //echo
    $data = $i
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
    . "\t" . $connect->RemoveSpecialChapr($rows['Customer_Address'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Business_Address'])
    // . "\t" . $rows['visit_address']
    . "\t" . $rows['Contact_Number']
    . "\t" . $rows['alternate_contact_number']
    // . "\t" . $rows['Collection_Manager']
    // . "\t" . $connect->RemoveSpecialChapr($rows['State_Manager'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Ref_1_Name'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Contact_Detail'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Ref_2_Name'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Contact_Detail_ref2'])
    . "\t" . $connect->RemoveSpecialChapr($rows['Area_Name'])
    . "\t" . $fos['employeename']
    . "\t" . $fos['loginId']
    . "\t" . $connect->RemoveSpecialChapr($rows['fos_comment'])
    . "\t" . $status['status']
    . "\t" . $fossubmit[0]
    . "\t" . $fossubmit[1];
    $sql = mysqli_query($dbconn,"select *,(SELECT status FROM `fosstatusdrropdown` where fosstatusdrropdown.fosstatusdrropdownid=foshistory.status) as status from foshistory where appid='".$rows['applicationid']."' order by foshistoryid desc limit 1,10");
    $numrows = mysqli_num_rows($sql);
    while($historyData = mysqli_fetch_assoc($sql)){
        $fossubmit = explode(' ', $historyData['strEntryDate']);
        $data .= "\t" . $historyData['status']
            . "\t" . $fossubmit[0]
            . "\t" . $fossubmit[1];
    }
    if($numrows < 10){
        for($iCounter = $numrows; $iCounter < 10; $iCounter++){
            $data.= "\t".""
             . "\t".""
             . "\t"."";
        }
    }
    
    $data .= "\t" . $rows['Payment_Collected_Date']
    . "\t" . $rows['Payment_Collected_Amount']
    . "\t" . $rows['penal']
    . "\t" . $rows['totalamt']
    . "\t" . $rows['ptp_datetime']
    // . "\t" . $rows['PTP_Date']
    // . "\t" . $rows['PTP_Amount']
    // . "\t" . $rows['Time_Slot']
    . "\t" . $rows['Pincode']
    . "\n";
    echo $data;
    $i++;
}
?>
