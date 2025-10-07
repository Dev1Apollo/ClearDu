<?php

ob_start();

include('../config.php');

$iBankId = 0;
$where = "";
if(isset($_REQUEST['bankid']) && $_REQUEST['bankid'] != 0){
    $iBankId = $_REQUEST['bankid'];    
    $where .= " and iBankId='".$_REQUEST['bankid']."'";
}
if ($_SESSION['Type'] == 'Central Manager') {
    if ($_REQUEST['counttype'] == 'Total Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance > '0' ".$where." ";
        //cmindexcountReportDownload
    }
    if ($_REQUEST['counttype'] == 'Withdraw') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '2' ".$where." ";
//and  locationId in  (" . $_REQUEST['id'] . ")        
    }
    if ($_REQUEST['counttype'] == 'Return') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '3' ".$where." ";
        //and  locationId in  (" . $_REQUEST['id'] . ")
    }
    if ($_REQUEST['counttype'] == 'Retention') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' ".$where." ";
        //and  locationId in  (" . $_REQUEST['id'] . ")
    }


    if ($_REQUEST['counttype'] == 'State wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and stateid= '" . $_REQUEST['id'] . "' ".$where." ";
        //and  locationId in  (" . $_REQUEST['locationid'] . ") 
    }
    if ($_REQUEST['counttype'] == 'Agency wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and stateid= '" . $_REQUEST['stateid'] . "' and  agencyid = '" . $_REQUEST['agencyid'] . "' ".$where." ";
        //and  locationId in  (" . $_REQUEST['locationid'] . ")
    }
    if ($_REQUEST['counttype'] == 'Disposition Summary') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and stateid= '" . $_REQUEST['stateid'] . "' and  agencyid = '" . $_REQUEST['agencyid'] . "' and  fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' ".$where." ";
        //and  locationId in  (" . $_REQUEST['locationid'] . ")  
    }
    if ($_REQUEST['counttype'] == 'Bucket wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt = '" . $_REQUEST['bkt'] . "' ".$where." ";
        //and  locationId in  (" . $_REQUEST['locationid'] . ")   
    }
    if ($_REQUEST['counttype'] == 'bkt Disposition') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt= '" . $_REQUEST['bkt'] . "' and   fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' ".$where." ";
        //and  locationId in  (" . $_REQUEST['locationid'] . ") 
    }

    //echo $_REQUEST['counttype'];
}

if ($_SESSION['Type'] == 'Agency Manager') {

    if ($_REQUEST['counttype'] == 'amTotal Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance > '0' and  customer_city_id in  (" . $_REQUEST['id'] . ") ".$where."";
    }
    if ($_REQUEST['counttype'] == 'amWithdraw') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '2' and  customer_city_id in  (" . $_REQUEST['id'] . ") ".$where."";
    }
    if ($_REQUEST['counttype'] == 'amReturn') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '3' and  customer_city_id in  (" . $_REQUEST['id'] . ") ".$where."";
    }
    if ($_REQUEST['counttype'] == 'amRetention') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and  customer_city_id in  (" . $_REQUEST['id'] . ") ".$where."";
    }


    if ($_REQUEST['counttype'] == 'amBranch wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1'  and  customer_city_id = '" . $_REQUEST['id'] . "' ".$where." ";
    }

    if ($_REQUEST['counttype'] == 'amDisposition Summary') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1'  and  fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' and  customer_city_id = '" . $_REQUEST['locationid'] . "' ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'amBucket wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt = '" . $_REQUEST['bkt'] . "' and  customer_city_id in  (" . $_REQUEST['locationid'] . ")  ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'ambkt Disposition') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt= '" . $_REQUEST['bkt'] . "' and   fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' and  customer_city_id in  (" . $_REQUEST['locationid'] . ") ".$where." ";
    }
}

if ($_SESSION['Type'] == 'Agency supervisor') {

    if ($_REQUEST['counttype'] == 'asTotal Allocation') {
        $sql1 = "SELECT * FROM `application` where am_accaptance > '0' and  customer_city_id in  (" . $_REQUEST['id'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'asWithdraw') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '2' and  customer_city_id in  (" . $_REQUEST['id'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'asReturn') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '3' and  customer_city_id in  (" . $_REQUEST['id'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where."";
    }
    if ($_REQUEST['counttype'] == 'asRetention') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and  customer_city_id in  (" . $_REQUEST['id'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where."";
    }


    if ($_REQUEST['counttype'] == 'asBranch wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1'  and  customer_city_id = '" . $_REQUEST['id'] . "' and agencyid='" . $_SESSION['agencyname'] . "' ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'asFOS wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1'  and  customer_city_id = '" . $_REQUEST['agencyid'] . "' and  locationId = '" . $_REQUEST['locationid'] . "' and fosid = '" . $_REQUEST['fosid'] . "' ".$where." ";
    }


    if ($_REQUEST['counttype'] == 'asDisposition Summary') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1'  and  fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' and  customer_city_id = '" . $_REQUEST['locationid'] . "' and fosid = '" . $_REQUEST['fosid'] . "' and   agencyid = '" . $_REQUEST['agencyid'] . "' ".$where."";
    }
    if ($_REQUEST['counttype'] == 'asBucket wise Allocation') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt = '" . $_REQUEST['bkt'] . "' and  customer_city_id in  (" . $_REQUEST['locationid'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where." ";
    }
    if ($_REQUEST['counttype'] == 'asbkt Disposition') {

        $sql1 = "SELECT * FROM `application` where am_accaptance = '1' and Bkt= '" . $_REQUEST['bkt'] . "' and   fos_completed_status = '" . $_REQUEST['fos_completed_status'] . "' and  customer_city_id in  (" . $_REQUEST['locationid'] . ") and agencyid='" . $_SESSION['agencyname'] . "' ".$where." ";
    }
}












$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmassigncase.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  Allocation Date"
 . "\t  Return Till"
 . "\t  Return Date"
 . "\t  withdraw Date"
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
 . "\t  Visit Address"
 . "\t  Contact Number"
 . "\t  Alternate Contact Number"
 . "\t  Collection Manager"
 . "\t  State Manager "
 . "\t  Ref_1_Name"
 . "\t  Contact_Detail"
 . "\t  Ref_2_Name"
 . "\t  Contact_Detail_ref2"
 . "\t  Fos Name"
 . "\t  Fos Comment"
 . "\t  Fos Status"
 . "\t  Payment Collected Date"
 . "\t  Payment Collected Amount"
 . "\t  Penal Amount Collected"
 . "\t  Total  Amount Collected"
 . "\t  Return Reason"
 . "\t  Withdrow Reason"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));
    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rows['fos_completed_status'] . "'"));
    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $rows['fosid'] . "'"));
//    $returndate = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `applicationlog`  where app_id ='" . $rows['applicationid'] . "' and action_name='Return By Agency supervisor'  ORDER BY  applicationlogid DESC limit 1 "));
//    $withdrawdate = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `applicationlog`  where app_id ='" . $rows['applicationid'] . "' and action_name='Withdraw By Central Team'  ORDER BY  applicationlogid DESC limit 1 "));

    echo
    $i
    . "\t" . $rows['strEntryDate']
    . "\t" . $rows['excel_return_date']
    . "\t" . $rows['return_date']
    . "\t" . $rows['withdraw_date']
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
    . "\t" . $fos['employeename']
    . "\t" . $rows['fos_comment']
    . "\t" . $status['status']
    . "\t" . $rows['Payment_Collected_Date']
    . "\t" . $rows['Payment_Collected_Amount']
    . "\t" . $rows['penal']
    . "\t" . $rows['totalamt']
    . "\t" . $rows['reason']
    . "\t" . $rows['withdraw_reason']
    . "\n";
    $i++;
}
?>
