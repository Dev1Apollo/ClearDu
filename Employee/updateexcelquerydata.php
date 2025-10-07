<?php

error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];

switch ($action) {

    case "TestUploadApplicationAdminEmployee":


//        if ($_REQUEST['companyID'] == '2') {

        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);

            $Sheets = $Reader->Sheets();
            //$errorString = "";
            foreach ($Sheets as $Index => $Name) {

                $Reader->ChangeSheet($Index);


                $ValCounter = 0;
                $AppIDColumnCounter = -1;
                $excelnameColumnCounter = -1;
                $InstallmentOverdueAmountColumnCounter = -1;
                $BccColumnCounter = -1;
                $LppColumnCounter = -1;
                $updatedateColumnCounter = -1;
                $PTPDateColumnCounter = -1;
                $PTPAmountColumnCounter = -1;
                $TimeSlotColumnCounter = -1;
                $VisitAddressColumnCounter = -1;
                $AlternateContactNumberColumnCounter = -1;

                $errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;


                                if (trim($slice[$icounter]) == "App ID") {
                                    $AppIDColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Installment Overdue Amount") {
                                    $InstallmentOverdueAmountColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Bcc") {
                                    $BccColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Lpp") {
                                    $LppColumnCounter = $icounter;
                                }
//                                if (trim($slice[$icounter]) == "update date") {
//                                    $updatedateColumnCounter = $icounter;
//                                }
                                if (trim($slice[$icounter]) == "PTP Date") {
                                    $PTPDateColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "PTP Amount") {
                                    $PTPAmountColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Time Slot") {
                                    $TimeSlotColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Visit Address") {
                                    $VisitAddressColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Alternate Contact Number") {
                                    $AlternateContactNumberColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {

                        $AppIDInRow = 0;
                        $excelnameIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                            if ($icounter == $AppIDColumnCounter) {

                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and fos_completed_status !=1 and fos_completed_status !=10 and fos_completed_status !=11 "));
                                    if ($lanapp['countL'] == 0) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " fos  submitted <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
                                    }
                                }
                            }

                            if ($icounter == $PTPDateColumnCounter) {

                                $PTPDate = $slice[$icounter];
                                if (trim($PTPDate) == "") {
                                    $errorString .= "Row " . $ValCounter . " & PTP Date  NULL <br/>";
                                }
                            }
                        }
                    }

                    $ValCounter ++;
                }
            }

            //step 2
//get reords with form id 1 : excel column name
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='4' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString.= "Column Not found in excel " . $excelcolumnname . "<br/>";
                }
            }
            echo $statusMsg = $errorString ? $errorString : '0';


            if ($statusMsg == '0') {

//old code

                foreach ($Sheets as $Index => $Name) {

                    $Reader->ChangeSheet($Index);

                    $icount = 1;

                    $ValCounter = 0;

                    $insertString = "UPDATE `application` SET ";
                    $ColumnCounter = 0;
                    $ApplicationNumberPosition = 0;
                    $jCounterArray = 0;
                    $setHeader = 0;
                    foreach ($Reader as $key => $slice) {

                        if ($key == 0 || $key == 1) {
                            if ($setHeader == 0) {

                                for ($icounter = 0; $icounter < count($slice); $icounter ++) {


                                    if (trim($slice[$icounter]) != "") {
                                        $headerArray[$jCounterArray] = $slice[$icounter];
                                        $jCounterArray++;




                                        if (trim($slice[$icounter]) == "App ID") {
                                            $AppIDColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Installment Overdue Amount") {
                                            $InstallmentOverdueAmountColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Bcc") {
                                            $BccColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Lpp") {
                                            $LppColumnCounter = $icounter;
                                        }
//                                        if (trim($slice[$icounter]) == "update date") {
//                                            $updatedateColumnCounter = $icounter;
//                                        }
                                        if (trim($slice[$icounter]) == "PTP Date") {
                                            $PTPDateColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "PTP Amount") {
                                            $PTPAmountColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Time Slot") {
                                            $TimeSlotColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Visit Address") {
                                            $VisitAddressColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Alternate Contact Number") {
                                            $AlternateContactNumberColumnCounter = $icounter;
                                        }
                                    }


                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='4' and excelColumnName='" . trim($slice[$icounter]) . "'";
                                        $formDetail = mysqli_fetch_array(mysqli_query($dbconn, $Sql));

                                        $insertString = $insertString . $formDetail['dbColumnName'] . ",";
                                    }
                                }
                                $setHeader = 1;
                            }
                        }
                    }




                    foreach ($Reader as $key => $slice) {

                        if ($key > 0) {

                            $insertString = "UPDATE `application` SET ";
                            $Whwere = "";
                            $ExeuteInsert = $insertString;

                            $AppIDInRow = 0;
                            $InstallmentOverdueAmountIDInRow = 0;
                            $BccIDInRow = 0;
                            $LppIDInRow = 0;
                            $updatedateIDInRow = 0;
                            $PTPDateIDInRow = 0;
                            $PTPAmountIDInRow = 0;
                            $TimeSlotIDInRow = 0;
                            $VisitAddressInRow = 0;
                            $AlternateContactNumberInRow=0;
                            
                            for ($icounter = 0; $icounter < count($slice); $icounter++) {

                                if ($icounter == $AppIDColumnCounter) {

                                    $AppID = $slice[$icounter];
                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and fos_completed_status !=1 and fos_completed_status !=10 and fos_completed_status !=11 "));
                                    $AppIDInRow = $lanapp['App_Id'];
                                }
                                if ($icounter == $InstallmentOverdueAmountColumnCounter) {

                                    $InstallmentOverdueAmountIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $BccColumnCounter) {

                                    $BccIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $LppColumnCounter) {

                                    $LppIDInRow = $slice[$icounter];
                                }
//                                if ($icounter == $updatedateColumnCounter) {
//
//                                    $updatedateIDInRow = $slice[$icounter];
//                                }
                                if ($icounter == $PTPDateColumnCounter) {

                                    $date1 = $slice[$icounter];
                                    $date = DateTime::createFromFormat('m-d-y', $date1);
                                    $PTPDateIDInRow = date_format($date, 'd-m-Y');
                                    //PTP_Date
//                                    $ExeuteInsert = $ExeuteInsert . "'" . $DateInInRow . "',";
                                }
                                if ($icounter == $PTPAmountColumnCounter) {

                                    $PTPAmountIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $TimeSlotColumnCounter) {

                                    $TimeSlotIDInRow = $slice[$icounter];
                                }
                                 if ($icounter == $VisitAddressColumnCounter) {

                                    $VisitAddressInRow = $slice[$icounter];
                                }
                                 if ($icounter == $AlternateContactNumberColumnCounter) {

                                    $AlternateContactNumberInRow = $slice[$icounter];
                                }

                                $ValCounter ++;
                            }



                            $date = date('d-m-Y');

                            $insertString = $insertString . "alternate_contact_number = " . " '" . $AlternateContactNumberInRow . "' ," . "visit_address = " . " '" . $VisitAddressInRow . "' ," . "PTP_Date = " . " '" . $PTPDateIDInRow . "' ," . "PTP_Amount = " . " '" . $PTPAmountIDInRow . "' ," . "Time_Slot = " . " '" . $TimeSlotIDInRow . "' ," . "Installment_Overdue_Amount = " . " '" . $InstallmentOverdueAmountIDInRow . "' ," . "Bcc = " . " '" . $BccIDInRow . "' ," . "Lpp = " . " '" . $LppIDInRow . "' ," . " updatedate = " . " '" . $date . "',fos_completed_status = 0,fos_completed =0,fos_submit_datetime = '', fos_comment = '' " . $Whwere = " where  App_Id =" . "'" . $AppIDInRow . "'  and fos_completed_status !=1 and fos_completed_status !=10 and fos_completed_status !=11  ";


                            mysqli_query($dbconn, $insertString);
                            //echo $insertString;
                        }
                    }
                }
            }
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
             unlink($file_path);
        }


        break;
}