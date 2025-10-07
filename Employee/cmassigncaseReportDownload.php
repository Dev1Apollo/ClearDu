<?php

ob_start();
//ini_set('max_allowed_packet','500M');
//ini_set('memory_limit', '1024M'); // Increase memory limit
//set_time_limit(-1); // Increase execution time to 5 minutes
include('../config.php');
//$where = "where 1=1";
//mysqli_query($dbconn, "SET SESSION max_allowed_packet = 500000000"); // 500M
//mysqli_query($dbconn, "SET SESSION wait_timeout = 28800"); // 8 hours (28800 seconds)
//mysqli_query($dbconn, "SET SESSION interactive_timeout = 28800"); // 8 hours (28800 seconds)
$strLocationID = '0';
$stateid = '0';

$where = "";
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

    $where.="  and STR_TO_DATE(application.strEntryDate,'%d-%m-%Y %H:%i:%s') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y') ";
}
$toDate = $_REQUEST['toDate'];
;
if ($_REQUEST['toDate'] != NULL && isset($_REQUEST['toDate'])) {

    $where.="  and STR_TO_DATE(application.strEntryDate,'%d-%m-%Y %H:%i:%s') <= STR_TO_DATE('" . $_REQUEST['toDate'] . "','%d-%m-%Y') ";
}

/*$sql1 = "SELECT application.strEntryDate,Bank_Name,assign_as_datetime,uniqueId,Account_No,PRODUCT,App_Id,Bkt,Customer_Name,Fathers_name,Asset_Make,Branch,customer_city,State,cycle,Allocation_Date,
        Allocation_CODE,Bounce_Reason,Loan_amount,Loan_booking_Date,Loan_maturity_date,Due_date,Emi_amount,Total_Pos_Amount,Total_penlty,Customer_Address,Business_Address, Contact_Number,strFosOtherRemark,
        alternate_contact_number,Ref_1_Name,Contact_Detail,Ref_2_Name,Contact_Detail_ref2,Area_Name,fos_comment,status,AlternetMobileNo,Pincode,Payment_Collected_Date,applicationpaymentcollectedhistory.Payment_Collected_Amount,
        applicationpaymentcollectedhistory.penal,applicationpaymentcollectedhistory.totalamt,ptp_datetime,agencyid,fos_completed_status,application.fosid,application.applicationid,fos_submit_datetime
        ,(SELECT agencyname FROM `agency`  where  agency.Agencyid=application.agencyid) as agencyname,
        (SELECT status FROM `fosstatusdrropdown`  where fosstatusdrropdown.fosstatusdrropdownid =application.fos_completed_status) as status
        
        FROM `application` left join (select applicationpaymentcollectedhistory.applicationid,status,Payment_Collected_Amount,penal,totalamt
           from 
           applicationpaymentcollectedhistory where isDelete=0 and (applicationpaymentcollectedhistory.Payment_Collected_Amount>0 or applicationpaymentcollectedhistory.penal>0 or applicationpaymentcollectedhistory.totalamt>0) ) as applicationpaymentcollectedhistory on application.applicationid=applicationpaymentcollectedhistory.applicationid " . $where . " and  application.is_assignto_am ='1' and application.isDelete=0 and  application.am_accaptance='1'";*/
           
         /*$sql1 = "SELECT application.strEntryDate,Bank_Name,assign_as_datetime,uniqueId,Account_No,PRODUCT,App_Id,Bkt,Customer_Name,
    Fathers_name,Asset_Make,Branch,customer_city,State,cycle,Allocation_Date,Allocation_CODE,Bounce_Reason,Loan_amount,Loan_booking_Date,
    Loan_maturity_date,Due_date,Emi_amount,
    Total_Pos_Amount,
    Total_penlty,
    Customer_Address,
    Business_Address,
    Contact_Number,
    strFosOtherRemark,
    alternate_contact_number,
    Ref_1_Name,
    Contact_Detail,
    Ref_2_Name,
    Contact_Detail_ref2,
    Area_Name,
    fos_comment,
    fosstatusdrropdown.status,
    AlternetMobileNo,
    Pincode,
    Payment_Collected_Date,
    applicationpaymentcollectedhistory.Payment_Collected_Amount, 
    applicationpaymentcollectedhistory.penal,
    applicationpaymentcollectedhistory.totalamt,
    ptp_datetime,
    application.agencyid,
    application.fosid,
    application.applicationid,
    fos_submit_datetime,
    agency.agencyname, 
    fosstatusdrropdown.status
FROM 
    application
LEFT JOIN 
    applicationpaymentcollectedhistory ON application.applicationid = applicationpaymentcollectedhistory.applicationid 
    AND applicationpaymentcollectedhistory.isDelete = 0 
    AND (applicationpaymentcollectedhistory.Payment_Collected_Amount > 0 OR applicationpaymentcollectedhistory.penal > 0 OR applicationpaymentcollectedhistory.totalamt > 0)
LEFT JOIN 
    agency ON agency.Agencyid = application.agencyid
LEFT JOIN 
    fosstatusdrropdown ON fosstatusdrropdown.fosstatusdrropdownid = application.fos_completed_status
WHERE 
    application.is_assignto_am = '1' 
    AND application.isDelete = 0 
    AND application.am_accaptance = '1' " . $where . " ";*/
           
           
           $sql1 = "SELECT application.strEntryDate,Bank_Name,assign_as_datetime,uniqueId,Account_No,PRODUCT,App_Id,Bkt,Customer_Name,
    Fathers_name,Asset_Make,Branch,customer_city,State,cycle,Allocation_Date,Allocation_CODE,Bounce_Reason,Loan_amount,Loan_booking_Date,
    Loan_maturity_date,Due_date,Emi_amount,
    Total_Pos_Amount,
    Total_penlty,
    Customer_Address,
    Business_Address,
    Contact_Number,
    strFosOtherRemark,
    alternate_contact_number,
    Ref_1_Name,
    Contact_Detail,
    Ref_2_Name,
    Contact_Detail_ref2,
    Area_Name,
    fos_comment,
    fosstatusdrropdown.status,
    AlternetMobileNo,
    Pincode,
    Payment_Collected_Date,
    applicationpaymentcollectedhistory.Payment_Collected_Amount, 
    applicationpaymentcollectedhistory.penal,
    applicationpaymentcollectedhistory.totalamt,
    ptp_datetime,
    application.agencyid,
    application.fosid,
    application.applicationid,
    fos_submit_datetime,
    agency.agencyname, 
    fosstatusdrropdown.status
FROM 
    application
LEFT JOIN 
    applicationpaymentcollectedhistory ON application.applicationid = applicationpaymentcollectedhistory.applicationid 

LEFT JOIN 
    agency ON agency.Agencyid = application.agencyid
LEFT JOIN 
    fosstatusdrropdown ON fosstatusdrropdown.fosstatusdrropdownid = application.fos_completed_status
WHERE 
    application.is_assignto_am = '1' 
    AND application.isDelete = 0 
    AND application.am_accaptance = '1' " . $where . ""; 
$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmassigncase.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

//echo
$header="";
    $header= "SrNo"
        . "\t"."Allocation Date"
        . "\t"."Bank Name"
        // . "\t"."Return Till"
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
        // . "\t  Due Month"
        . "\t"."Allocation Date"
        . "\t"."Allocation CODE"
        . "\t"."Bounce Reason"
        . "\t"."Loan Amount"
        . "\t"."Loan Booking Date"
        . "\t"."Loan Maturity Date"
        // . "\t  Frist Emi Date"
        . "\t"."Due Date"
        . "\t"."Emi/Collectible Amount"
        . "\t"."Total POS Amount"
        // . "\t  Installment_Overdue_Amount"
        // . "\t  Bcc"
        // . "\t  Lpp"
        . "\t"."Total Penlty"
        // . "\t  Principal outstanding"
        // . "\t  Vehicle Registration No"
        // . "\t  Supplier"
        // . "\t  Tenure"
        . "\t"."Residence Address"
        . "\t"."Business Address"
        // . "\t"."TC1"
        // . "\t"."TC2"
        // . "\t"."TC3"
        // . "\t"."Visit Address"
        . "\t"."Contact Number"
        . "\t"."Alternate Contact Number"
        // . "\t  Collection Manager"
        // . "\t  State Manager "
        . "\t"."Ref_1_Name"
        . "\t"."Contact_Detail"
        . "\t"."Ref_2_Name"
        . "\t"."Contact_Detail_ref2"
        . "\t"."Area Name"
        . "\t"."Fos Name"
        . "\t"."Fos Id"
        . "\t"."Fos Comment"
        . "\t"."Fos Status"
        . "\t"."Fos Other Remarks"
        . "\t"."Fos Submit Date"
        . "\t"."Fos Submit Time";
        /*for($iCounter = 1; $iCounter <= 10; $iCounter++){
            $header.= "\t"."Fos Status_" . $iCounter
            . "\t"."Fos Other Remarks_" . $iCounter
             . "\t"."Fos Submit Date_" . $iCounter
             . "\t"."Fos Submit Time_". $iCounter;
         }*/
        //. "\t  ptp datetime"
        $header.= "\t"."Alternet MobileNo"
        . "\t"."Pincode"
        . "\t"."Payment Collected Date"
        . "\t"."Payment Collected Amount"
        . "\t"."Penal Amount Collected"
        . "\t"."Total  Amount Collected"
        . "\t"."ptp Re-Schedule Date"
        // . "\t  PTP Date"
        // . "\t  PTP Amount"
        // . "\t  Time Slot"
        . "\n";
        //echo $header;
$i = 1;
$data = "";
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    // $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));
    // $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $rows['fos_completed_status'] . "'"));
    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $rows['fosid'] . "'"));
    //$totalamt = $rows['Payment_Collected_Amount'] + $rows['Emi_amount'];
    $Payment_Collected_Amount = 0;
	if(isset($rows['Payment_Collected_Amount']) && $rows['Payment_Collected_Amount'] != ""){
		$Payment_Collected_Amount = $rows['Payment_Collected_Amount'];
	}
	$Emi_amount = 0;
	if(isset($rows['Emi_amount']) && $rows['Emi_amount'] != ""){
		if($rows['Emi_amount'] == "-"){
			$Emi_amount =0;		
		} else {
			$Emi_amount = $rows['Emi_amount'];
		}
	}
    $totalamt = $Payment_Collected_Amount + $Emi_amount;
    /*$tc = '';
    $tccomment = mysqli_query($dbconn, "SELECT * FROM tchistory  where  applicationid='" . $rows['applicationid'] . "' ORDER BY tchistoryid desc limit 3");
    if (mysqli_num_rows($tccomment) > 0) {
        while ($tccomments = mysqli_fetch_array($tccomment)) {
            $tc = $tccomments['tccomment'] . ',' . $tc;
        }
    }
    $comment = rtrim($tc, ',');
    $tccom = explode(',', $tc);*/
    $fossubmit = explode(' ', $rows['fos_submit_datetime']);
    //echo
    $fossubmitdate = !empty($fossubmit[0]) ? $fossubmit[0] : "";
    $fossubmitTime = !empty($fossubmit[1]) ? $fossubmit[1] : "";
    $data .= $i
            . "\t" . $rows['strEntryDate']
            . "\t" . $rows['Bank_Name']
            // . "\t" . $rows['excel_return_date']
            . "\t" . $rows['assign_as_datetime']
            . "\t" . $rows['uniqueId']
            . "\t" . $rows['Account_No']
            . "\t" . $rows['agencyname']
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
            // . "\t" . $tccom[0]
            // . "\t" . $tccom[1]
            // . "\t" . $tccom[2]
            // . "\t" . $rows['visit_address']
            . "\t" . $rows['Contact_Number']
             . "\t" . $rows['alternate_contact_number']
            // . "\t" . $rows['Collection_Manager']
            // . "\t" . $rows['State_Manager']
            . "\t" . $rows['Ref_1_Name']
            . "\t" . $rows['Contact_Detail']
            . "\t" . $rows['Ref_2_Name']
            . "\t" . $rows['Contact_Detail_ref2']
            . "\t" . $rows['Area_Name']
            . "\t" . $fos['employeename']
            . "\t" . $fos['loginId']
            . "\t" . trim(str_replace("\n"," ",$rows['fos_comment']))
            . "\t" . $rows['status']
            . "\t" . trim(str_replace("\n"," ",$rows['strFosOtherRemark']))
            . "\t" . $fossubmitdate
            . "\t" . $fossubmitTime;
            
            /*$sql = mysqli_query($dbconn,"select *,(SELECT status FROM `fosstatusdrropdown` where fosstatusdrropdown.fosstatusdrropdownid=foshistory.status) as status from foshistory where appid='".$rows['applicationid']."' order by foshistoryid desc limit 1,10");
            $numrows = mysqli_num_rows($sql);
            while($historyData = mysqli_fetch_assoc($sql)){
                $fossubmit = explode(' ', $historyData['strEntryDate']);
                $data .= "\t" . $historyData['status']
                    . "\t" . trim(str_replace("\n"," ",$historyData['comment']))
                    . "\t" . $fossubmit[0]
                    . "\t" . $fossubmit[1];
            }
            if($numrows < 10){
                for($iCounter = $numrows; $iCounter < 10; $iCounter++){
                    $data.= "\t".""
                     . "\t".""
                     . "\t".""
                     . "\t"."";
                }
            }*/
            
            //. "\t" . $rows['ptp_datetime']
            $data .= "\t" . $rows['AlternetMobileNo']
            . "\t" . $rows['Pincode']
            . "\t" . $rows['Payment_Collected_Date']
            . "\t" . $rows['Payment_Collected_Amount']
            . "\t" . $rows['penal']
            . "\t" . $rows['totalamt']
            . "\t" . $rows['ptp_datetime']
            // . "\t" . $rows['PTP_Date']
            // . "\t" . $rows['PTP_Amount']
            // . "\t" . $rows['Time_Slot']
            . "\n";
            //echo $data;
    $i++;
}
ob_end_clean();
echo chr(255) . chr(254) .mb_convert_encoding($header, 'UTF-16LE', 'UTF-8');
echo chr(255) . chr(254) .mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');

ob_flush();
?>
