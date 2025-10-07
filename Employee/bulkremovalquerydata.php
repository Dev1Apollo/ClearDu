<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];

switch ($action) {

    case "Bulkremoval":

        if (isset($_REQUEST['IMgallery'])) {
            //$headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);

            $Sheets = $Reader->Sheets();
            $errorString = "";
            foreach ($Sheets as $Index => $Name) {

                $Reader->ChangeSheet($Index);
                $ValCounter = 0;
                $AppIDColumnCounter = -1;
                // print_r($Reader);
                // exit;
                //$jCounterArray = 0;
                foreach ($Reader as $key => $slice) {
                    if ($ValCounter == 0) {
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            //print_r(trim($slice[$icounter]));
                            if (trim($slice[$icounter]) != "") {
                                // $headerArray[$jCounterArray] = $slice[$icounter];
                                // $jCounterArray++;
                                if (trim($slice[$icounter]) == "Account No") {
                                    $AppIDColumnCounter = $icounter;
                                } else{
                                    $errorString .= "Hearder Account No is not match. <br/>";
                                }
                            }
                        }
                    } else {
                        $AppIDInRow = 0;
                        $col1Value = "";
                        $fosstatusIDInRow = '';
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }
                            
                            if ($icounter == $AppIDColumnCounter) {
                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {
                                    //echo "SELECT * FROM `application`  where  Account_No='" . $AppID . "' and  am_accaptance in('0', '1') ";
                                    $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppID . "' and  am_accaptance in('0', '1') ");
                                    if (mysqli_num_rows($lanapp) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " not match. <br/>";
                                    } else {
                                        $rowapp = mysqli_fetch_array($lanapp);
                                        $AppIDInRow = $rowapp['Account_No'];
                                    }
                                }
                            }
                            // if ($icounter == $AppIDColumnCounter) {
                            //     $AppID = $slice[$icounter];
                            //     if (trim($AppID) != "") {
                            //         $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppID . "'  and  fos_completed_status  IN ('1','18') and  am_accaptance in('0', '1') ");
                            //         if (mysqli_num_rows($lanapp) > 0) {
                            //             $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " Account No Already Paid or Partial Paid Status. <br/>";
                            //         } else {
                            //             $rowapp = mysqli_fetch_array($lanapp);
                            //             $AppIDInRow = $rowapp['Account_No'];
                            //         }
                            //     } else {
                            //         $errorString .= "Row " . $ValCounter . " & Account No  can not null ,";
                            //     }
                            // }
                        }
                    }

                    $ValCounter++;
                }
            }
            // echo $errorString;
            // exit;
            //step 2
            //get reords with form id 1 : excel column name
            echo $statusMsg = $errorString ? $errorString : '0';
            $Account_No = '';
            if ($statusMsg == '0') {
                foreach ($Sheets as $Index => $Name) {
                    $Reader->ChangeSheet($Index);
                    $icount = 1;
                    $ValCounter = 0;
                    //$insertString = "UPDATE `application` SET ";
                    $ColumnCounter = 0;
                    $ApplicationNumberPosition = 0;
                    $jCounterArray = 0;
                    $setHeader = 0;
                    $ValCounter = 0;
                    $AppIDColumnCounter = -1;
                    foreach ($Reader as $key => $slice) {
                        if ($key == 0) {
                            for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                if (trim($slice[$icounter]) != "") {
                                    $headerArray[$jCounterArray] = $slice[$icounter];
                                    $jCounterArray++;
                                    if (trim($slice[$icounter]) == "Account No") {
                                        $AppIDColumnCounter = $icounter;
                                    }
                                }
                            }  
                        } else{
                            //$insertString = "UPDATE `application` SET ";
                            $insertString = "";
                            $Whwere = "";
                            for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                if ($icounter == $AppIDColumnCounter) {
                                    $AppIDInRow = $slice[$icounter];
                                }
                                $ValCounter++;
                            }
                            // $deleteSql = "delete from applicationpaymentcollectedhistory where applicationid in (SELECT applicationid FROM `application` where Account_No='" . $AppIDInRow . "')";
                            // mysqli_query($dbconn, $deleteSql);
                            // echo "INSERT INTO bulkremovalapplication SELECT * FROM application WHERE Account_No =" . "'" . $AppIDInRow . "' and am_accaptance IN ('0','1')";
                            // exit;
                            mysqli_query($dbconn,"INSERT INTO bulkremovalapplication (applicationid, Bank_Name, Account_No, App_Id, Bkt, Customer_Name, Fathers_name, Asset_Make, PRODUCT, Branch, customer_city, State, cycle, Due_date, Allocation_Date, Allocation_CODE, Bounce_Reason, Loan_amount, Loan_booking_Date, Loan_maturity_date, Emi_amount, Total_Pos_Amount, Total_penlty, Customer_Address, Business_Address, Contact_Number, alternate_contact_number, Ref_1_Name, Contact_Detail, Ref_2_Name, Contact_Detail_ref2, agency, Pincode, Area_Name, agencyid, is_assignto_fos, assign_fos_datetime, fosid, fos_completed, fos_completed_status, strFosOtherRemark, fos_comment, ptp_datetime, fos_submit_datetime, Payment_Collected_Date, Payment_Collected_Amount, reason, runsheet, runsheetsequnce, PTP_Date, PTP_Amount, Time_Slot, customer_city_id, withdraw_date, return_date, withdraw_reason, is_photo_uploaded, error_upload, uniqueId, excelfilename, excelnameid, locationid, iStatus, isDelete, strEntryDate, strIP, created_at, updated_at, is_assignto_am, am_accaptance, penal, totalamt, stateid, SrNo, assign_as_datetime, AlternetMobileNo, iBankId, iProductId, dateTimeRemoval) 
                            SELECT applicationid, Bank_Name, Account_No, App_Id, Bkt, Customer_Name, Fathers_name, Asset_Make, PRODUCT, Branch, customer_city, State, cycle, Due_date, Allocation_Date, Allocation_CODE, Bounce_Reason, Loan_amount, Loan_booking_Date, Loan_maturity_date, Emi_amount, Total_Pos_Amount, Total_penlty, Customer_Address, Business_Address, Contact_Number, alternate_contact_number, Ref_1_Name, Contact_Detail, Ref_2_Name, Contact_Detail_ref2, agency, Pincode, Area_Name, agencyid, is_assignto_fos, assign_fos_datetime, fosid, fos_completed, fos_completed_status, strFosOtherRemark, fos_comment, ptp_datetime, fos_submit_datetime, Payment_Collected_Date, Payment_Collected_Amount, reason, runsheet, runsheetsequnce, PTP_Date, PTP_Amount, Time_Slot, customer_city_id, withdraw_date, return_date, withdraw_reason, is_photo_uploaded, error_upload, uniqueId, excelfilename, excelnameid, locationid, iStatus, isDelete, strEntryDate, strIP, created_at, updated_at, is_assignto_am, am_accaptance, penal, totalamt, stateid, SrNo, assign_as_datetime, AlternetMobileNo, iBankId, iProductId,'".date('Y-m-d H:i:s')."' 
                            FROM application WHERE Account_No ='" . $AppIDInRow . "' and am_accaptance IN ('0','1')"); 
                            $Account_No .= "'".$AppIDInRow."',";
                            
                            // $Account_No = rtrim($Account_No,",");
                            // $insertString = " delete from application where  Account_No ='" . $AppIDInRow . "' and am_accaptance IN ('0','1') ";
                            // mysqli_query($dbconn, $insertString);
                        }
                    }
                }
                $Account_No = rtrim($Account_No,",");
                $insertString = " delete from application where  Account_No in (" . $Account_No . ") and am_accaptance IN ('0','1') ";
                mysqli_query($dbconn, $insertString);
            }
            //exit;
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            unlink($file_path);
        }
        break;
}
