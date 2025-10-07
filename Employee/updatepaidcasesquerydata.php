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

    case "updatepaidcasesAdminEmployee":

        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);

            $Sheets = $Reader->Sheets();
            $errorString = "";
            foreach ($Sheets as $Index => $Name) {

                $Reader->ChangeSheet($Index);


                $ValCounter = 0;
                $AppIDColumnCounter = -1;
                $PaymentCollectedAmountColumnCounter = -1;
                $PenalAmountCollectedColumnCounter = -1;
                $StatusColumnCounter = -1;


                //$errorString = "";
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
                                if (trim($slice[$icounter]) == "Payment Collected Amount") {
                                    $PaymentCollectedAmountColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Penal Amount Collected") {
                                    $PenalAmountCollectedColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Status") {
                                    $StatusColumnCounter = $icounter;
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

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and  am_accaptance = '2'  "));
                                    if ($lanapp['countL'] > 0) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " App ID already Withdrawn <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
                                    }
                                }
                            }
                             if ($icounter == $AppIDColumnCounter) {

                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and  am_accaptance = '3'  "));
                                    if ($lanapp['countL'] > 0) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " App ID Already returned <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
                                    }
                                }
                            }
                               if ($icounter == $AppIDColumnCounter) {

                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and  fos_completed_status in ('1','10','11')  "));
                                    if ($lanapp['countL'] > 0) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " App ID in collected status <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
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
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='7' ";
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
                                        if (trim($slice[$icounter]) == "Payment Collected Amount") {
                                            $PaymentCollectedAmountColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Penal Amount Collected") {
                                            $PenalAmountCollectedColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Status") {
                                            $StatusColumnCounter = $icounter;
                                        }
                                    }


                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='7' and excelColumnName='" . trim($slice[$icounter]) . "'";
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
                            $PaymentCollectedAmountIDInRow = 0;
                            $PenalAmountCollectedIDInRow = 0;
                            $StatusIDInRow = 0;
                            $totalPenalCollected = 0;
                            $fosstatusIDInRow = 0;
                            

                            for ($icounter = 0; $icounter < count($slice); $icounter++) {

                                if ($icounter == $AppIDColumnCounter) {

                                    $AppID = $slice[$icounter];
                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "'  "));
                                    $AppIDInRow = $lanapp['App_Id'];
                                }
                                if ($icounter == $PaymentCollectedAmountColumnCounter) {

                                    $PaymentCollectedAmountIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $PenalAmountCollectedColumnCounter) {

                                    $PenalAmountCollectedIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $StatusColumnCounter) {

                                    $StatusIDInRow = $slice[$icounter];
                                    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,fosstatusdrropdown.* FROM `fosstatusdrropdown`  where  status='" . $StatusIDInRow . "'  "));
                                    $fosstatusIDInRow = $status['fosstatusdrropdownid'];
                                }

                                $totalPenalCollected = $PaymentCollectedAmountIDInRow + $PenalAmountCollectedIDInRow ;
                                        
                                $ValCounter ++;
                            }



                            $date = date('d-m-Y');

                            $insertString = $insertString . "Payment_Collected_Amount = " . " '" . $PaymentCollectedAmountIDInRow . "' ," . "penal = " . " '" . $PenalAmountCollectedIDInRow . "' ," . "fos_completed_status = " . " '" . $fosstatusIDInRow . "' ," . "totalamt = " . " '" . $totalPenalCollected . "' ," . "fosid = " . " '500000' ,fos_completed='1',is_assignto_fos='1' " . $Whwere = " where  App_Id =" . "'" . $AppIDInRow . "' and am_accaptance != '2' and am_accaptance != '3' ";


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