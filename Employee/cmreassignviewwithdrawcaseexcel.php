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
                $LANNUMBERColumnCounter = -1;
                $excelnameColumnCounter = -1;
                $PTPDateColumnCounter = -1;
                $PTPAmountColumnCounter = -1;
                $PTPTimeSlotColumnCounter = -1;
                $AgencyColumnCounter = 1;
                $errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;


                                if (trim($slice[$icounter]) == "App Id") {
                                    $LANNUMBERColumnCounter = $icounter;
                                }
//                                if (trim($slice[$icounter]) == "excelname") {
//                                    $excelnameColumnCounter = $icounter;
//                                }
                                if (trim($slice[$icounter]) == "Agency_name") {
                                    $AgencyColumnCounter = $icounter;
                                }
                                 if (trim($slice[$icounter]) == "PTP Date") {
                                    $PTPDateColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "PTP Amount") {
                                    $PTPAmountColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Time Slot") {
                                    $PTPTimeSlotColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {

                        $LANNUMBERInRow = 0;
                        $excelnameIdInRow = 0;
                        $AgencynameIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                            if ($icounter == $LANNUMBERColumnCounter) {

                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "'"));
                                    if ($lanapp['countL'] == 0) {
                                        $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . " not match <br/>";
                                    } else {
                                        $LANNUMBERInRow = $lanapp['App_Id'];
                                    }
                                }
                            }

//                            if ($icounter == $excelnameColumnCounter) {
//
//                                $excelname = $slice[$icounter];
//                                if (trim($excelname) != "") {
//                                    $appexcelname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  excelfilename='" . $excelname . "'"));
//                                    if ($appexcelname['countL'] == 0) {
//                                        $errorString .= "Row " . $ValCounter . " & excelname =" . $excelname . " not match <br/>";
//                                    } else {
//                                        $excelnameIdInRow = $appexcelname['excelfilename'];
//                                    }
//                                }
//                            }

                            if ($icounter == $AgencyColumnCounter) {

                                $Agencyname = $slice[$icounter];
                                if (trim($Agencyname) != "") {
                                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  agency='" . $Agencyname . "'"));
                                    if ($agencyname['countL'] == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Agencyname =" . $Agencyname . " not match <br/>";
                                    } else {
                                        $AgencynameIdInRow = $agencyname['agency'];
                                    }
                                }
                            }
                        }
                    }

                    $ValCounter ++;
                }
            }

            //step 2
//get reords with form id 1 : excel column name
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='3' ";
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

                                        if (trim($slice[$icounter]) == "App Id") {
                                            $LANNUMBERColumnCounter = $icounter;
                                        }

//                                        if (trim($slice[$icounter]) == "excelname") {
//                                            $excelnameColumnCounter = $icounter;
//                                        }
                                        if (trim($slice[$icounter]) == "Agency_name") {
                                            $AgencyColumnCounter = $icounter;
                                        }
                                        
                                         if (trim($slice[$icounter]) == "PTP Date") {
                                            $PTPDateColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "PTP Amount") {
                                            $PTPAmountColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Time Slot") {
                                            $PTPTimeSlotColumnCounter = $icounter;
                                        }
                                    }


                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='3' and excelColumnName='" . trim($slice[$icounter]) . "'";
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
                            $excelnameIdInRow = 0;
                            $LANNUMBERInRow = 0;
                            $stasteInRow = 0;


                            for ($icounter = 0; $icounter < count($slice); $icounter++) {

                                if ($icounter == $LANNUMBERColumnCounter) {

                                    $LANNUMBER = $slice[$icounter];
                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "'"));
                                    $LANNUMBERInRow = $lanapp['App_Id'];
                                }
//                                if ($icounter == $excelnameColumnCounter) {
//
//                                    $excelname = $slice[$icounter];
//                                    $appexcelname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  excelfilename='" . $excelname . "'"));
//                                    $excelnameIdInRow = $appexcelname['excelfilename'];
//                                }

                                if ($icounter == $AgencyColumnCounter) {

                                    $Agencyname = $slice[$icounter];
                                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  agency='" . $Agencyname . "'"));
                                    $agencynameIdInRow = $agencyname['agency'];


                                    $Agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as counta,agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='" . $Agencyname . "'"));
                                    $AgencyidInRow = $Agencyid['Agencyid'];
                                }
                                
                                if ($icounter == $PTPDateColumnCounter) {

                                    $date1 = $slice[$icounter];
                                    $date = DateTime::createFromFormat('m-d-y', $date1);
                                    $DateInInRow = date_format($date, 'd-m-Y');
                                    // echo $DateInInRow;
                                }
                                if ($icounter == $PTPAmountColumnCounter) {

                                    $PTPAmount = $slice[$icounter];
                                }
                                if ($icounter == $PTPTimeSlotColumnCounter) {

                                    $PTPTimeSlot = $slice[$icounter];
                                }


                                $ValCounter ++;
                            }



                            $currentmonth = date('d-m-Y');

                            $insertString = $insertString . " Time_Slot" . "=" . "'" . $PTPTimeSlot . "'".  ",PTP_Amount" ."=" . "'". $PTPAmount . "'" . ",PTP_Date " . "=" . "'" . $DateInInRow . "'" . ",assign_as_datetime" . "=" . " '" . date('d-m-Y H:i:s') . "'" . ",am_accaptance" . "=" . "1" . ",agency = " . "'" . $agencynameIdInRow . "' " . ",agencyid = " . $AgencyidInRow . $Whwere = " where  App_Id =" . "'" . $LANNUMBERInRow . "' and am_accaptance = 2 and fos_completed_status !=1 and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  ";


                            mysqli_query($dbconn, $insertString);
                            //echo $insertString;
                        }
                    }
                }
            }
        }


        break;
}