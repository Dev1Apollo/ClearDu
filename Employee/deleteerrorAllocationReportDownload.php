<?php
ob_start();
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';

$i = 1;
  $filename = 'errorallocationReport.xls';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename);

    ob_end_clean();

    $str_filedata = '';
    $str_filedata_head = '';
  echo  "SrNo"
            . "\t  Account No"
            . "\t  App Id"
            . "\t  Bkt"
            . "\t  Customer Name"
            . "\t  Fathers name"
            . "\t  Asset Make"
            . "\t  PRODUCT"
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
            . "\t  Emi Amount"
            . "\t  Installment Overdue Amount"
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
            . "\t  State Manager"
            . "\t  Ref_1_Name"
            . "\t  Contact_Detail"
            . "\t  Ref_2_Name"
            . "\t  Contact_Detail_ref2"            
            . "\t  Agency_name"
            . "\t  Pincode"                        
            . "\t  PTP Date"
            . "\t  PTP Amount"
            . "\t  Time Slot"
            . "\t  Visit Address"
            . "\t  Alternate Contact Number"
            . "\t  Return Till"
            . "\t  Error"
            . "\t Bank Name"
            . "\t Uploaded Date"
            . "\n";

    $freshal = mysqli_query($dbconn, "SELECT * FROM error_application   ");

    while ($row1 = mysqli_fetch_array($freshal)) {

        $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
        $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
        $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));


       echo   $i
                . "\t" . $row1['Account_No']
                . "\t" . $row1['App_Id']
                . "\t" . $row1['Bkt']
                . "\t" . $row1['Customer_Name']
                . "\t" . $row1['Fathers_name']
                . "\t" . $row1['Asset_Make']
                . "\t" . $row1['PRODUCT']
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
                . "\t" . $row1['Customer_Address']             
                . "\t" . $row1['Contact_Number']
                . "\t" . $row1['Collection_Manager']                
                . "\t" . $connect->RemoveSpecialChapr($row1['State_Manager'])
                . "\t" . $connect->RemoveSpecialChapr($row1['Ref_1_Name'])
                . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail'])
                . "\t" . $connect->RemoveSpecialChapr($row1['Ref_2_Name'])
                . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail_ref2'])
                . "\t" . $fos['employeename']            
                . "\t" . $row1['pincode']            
                . "\t" . $row1['PTP_Date']
                . "\t" . $row1['PTP_Amount']
                . "\t" . $row1['Time_Slot']                
                . "\t" . $row1['visit_address']
                . "\t" . $row1['alternate_contact_number']         
                . "\t" . $row1['excel_return_date']         
                . "\t" . $row1['error_upload']
                . "\t" . $row1['Bank_Name']
                . "\t" . date('d-m-Y',strtotime($row1['strEntryDate']))
                . "\n";
        $i++;
    }

//echo $str_filedata_head . $str_filedata;
?>
