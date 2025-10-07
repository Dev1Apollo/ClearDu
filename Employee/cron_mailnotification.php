<?php

ob_start();
error_reporting(0);
include_once '../common.php';
$connect = new connect();
require '../PHPMailer-master/PHPMailerAutoload.php';
?>
<?php

$currentmonth = date('d-m-Y');
//$i = 1;

$filename = 'freshallocationuploaded.xls';
$output = fopen($filename, 'w');
$str_filedata = '';
$str_filedata_head = '';
$str_filedata_head .=
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
        . "\t  Customer City"
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
fwrite($output, $str_filedata_head);
$i = 1;
$freshal = mysqli_query($dbconn, "SELECT * FROM application WHERE agencyid='1' ");
while ($row1 = mysqli_fetch_array($freshal)) {

    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));


    $str_filedata .=
            $i
            . "\t" . $row1['strEntryDate']
            . "\t" . $row1['assign_as_datetime']
            . "\t" . $row1['uniqueId']
            . "\t" . $row1['Account_No']
            . "\t" . $asname['agencyname']
            . "\t" . $row1['PRODUCT']
            . "\t" . $row1['App_Id']
            . "\t" . $row1['Bkt']
            . "\t" . $row1['Customer_Name']
            . "\t" . $row1['Fathers_name']
            . "\t" . $row1['Asset_Make']
            . "\t" . $row1['Branch']
            . "\t" . $row1['customer_city']
            . "\t" . $row1['State']
            . "\t" . $row1['Due_Month']
            . "\t" . $row1['Allocation_Date']
            . "\t" . $row1['Allocation_CODE']
            . "\t" . $row1['Bounce_Reason']
            . "\t" . $row1['Loan_amount']
            . "\t" . $row1['Loan_booking_Date']
            . "\t" . $row1['Loan_maturity_date']
            . "\t" . $row1['Frist_Emi_Date']
            . "\t" . $row1['Due_date']
            . "\t" . $row1['Emi_amount']
            . "\t" . $row1['Installment_Overdue_Amount']
            . "\t" . $row1['Bcc']
            . "\t" . $row1['Lpp']
            . "\t" . $row1['Total_penlty']
            . "\t" . $row1['Principal_outstanding']
            . "\t" . $row1['Vehicle_Registration_No']
            . "\t" . $row1['Supplier']
            . "\t" . $row1['Tenure']
            . "\t" . $connect->RemoveSpecialChapr($row1['Customer_Address'])
            . "\t" . $row1['Contact_Number']
            . "\t" . $row1['Collection_Manager']
            . "\t" . $connect->RemoveSpecialChapr($row1['State_Manager'])
            . "\t" . $connect->RemoveSpecialChapr($row1['Ref_1_Name'])
            . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail'])
            . "\t" . $connect->RemoveSpecialChapr($row1['Ref_2_Name'])
            . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail_ref2'])
            . "\t" . $fos['employeename']
            . "\t" . $fos['loginId']
            . "\t" . $connect->RemoveSpecialChapr($row1['fos_comment'])
            . "\t" . $status['status']
            . "\t" . $row1['Payment_Collected_Date']
            . "\t" . $row1['Payment_Collected_Amount']
            . "\t" . $row1['penal']
            . "\t" . $row1['totalamt']
            . "\t" . $row1['ptp_datetime']
            . "\t" . $row1['PTP_Date']
            . "\t" . $row1['PTP_Amount']
            . "\t" . $row1['Time_Slot']
            . "\n";
    $i++;
}


$AgencyReturnCases = mysqli_query($dbconn, "SELECT *  FROM agency where isDelete='0' and istatus ='1' and Agencyid ='1' ");
while ($row = mysqli_fetch_array($AgencyReturnCases)) {
//    echo $i;
    //                    $totalallocation = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalallocation FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  applicationid in(" . $listofappid . ")"));
    $totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbktx FROM `application` WHERE  agencyid='1' and (Bkt='x' or Bkt='0')  "));
    $totalbkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbkt1 FROM `application` WHERE agencyid='1' and  Bkt='1'  "));
    $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='1' "));

    $mailFormat = file_get_contents("monthlyallocation.html");
    $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);
//                    $mailFormat = str_replace("#TotalAllocationCount#", ucfirst(urldecode($totalallocation['totalallocation'])), $mailFormat);
    $mailFormat = str_replace("#totalbktx#", ucfirst(urldecode($totalbktx['totalbktx'])), $mailFormat);
    $mailFormat = str_replace("#totalbkt1#", ucfirst(urldecode($totalbkt1['totalbkt1'])), $mailFormat);
//                    $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);


    $mailTR = file_get_contents("monthlyallocation_tr.html");
    $TrValueToReplace = "";
    $branch = mysqli_query($dbconn, "SELECT *  FROM `application` WHERE agencyid='1'  GROUP by customer_city");
    while ($rowbranch = mysqli_fetch_array($branch)) {

        $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='1' and (Bkt='x' or Bkt='0')  and applicationid in(" . $listofappid . ") and customer_city = '" . $rowbranch['customer_city'] . "'  "));
        $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='1' and  Bkt='1'  and applicationid in(" . $listofappid . ") and customer_city = '" . $rowbranch['customer_city'] . "' "));
        $trValueToChaneg = $mailTR;




        $trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['customer_city'])), $trValueToChaneg);
        $trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
        $trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

        $TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
    }
    $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);


    fwrite($output, $str_filedata);
    fclose($output);
    $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Central team allocates cases to agency.', $filename, $foremail['cc'], '');

    $i++;
}
?>