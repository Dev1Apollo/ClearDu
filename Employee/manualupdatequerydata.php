<?php
ob_start();
error_reporting(0);
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];

switch ($action) {

    case "Manualupdate":

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
                $AmountColumnCounter = -1;
                
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {
                    if ($ValCounter == 0) {
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "Account No") {
                                    $AppIDColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Amount") {
                                    $AmountColumnCounter = $icounter;
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
                                    $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppID . "' and  am_accaptance in('0', '1') ");
                                    if (mysqli_num_rows($lanapp) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " not match. <br/>";
                                    } else {
                                        $rowapp = mysqli_fetch_array($lanapp);
                                        $AppIDInRow = $rowapp['Account_No'];
                                    }
                                }
                            }
                        }
                    }

                    $ValCounter++;
                }
            }

            //step 2
            //get reords with form id 1 : excel column name
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='10' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString .= "Column Not found in excel " . $excelcolumnname . "<br/>";
                }
            }
            echo $statusMsg = $errorString ? $errorString : '0';

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
                    $AmountColumnCounter = -1;
                    $AppIDInRow = 0;
                    $AmountInRow = 0;
                    foreach ($Reader as $key => $slice) {
                        if ($key == 0) {
                            if ($setHeader == 0) {
                                for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                    if (trim($slice[$icounter]) != "") {
                                        $headerArray[$jCounterArray] = $slice[$icounter];
                                        $jCounterArray++;
                                        if (trim($slice[$icounter]) == "Account No") {
                                            $AppIDColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Amount") {
                                            $AmountColumnCounter = $icounter;
                                        }
                                    }
                                }
                                $setHeader = 1;
                            }
                        } else {
                            $insertString = "UPDATE `application` SET ";
                            $Whwere = "";
                            $ExeuteInsert = $insertString;
                            $fosid = "";

                            for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                //    $fosstatusIDInRow = 0;
                                if ($icounter == $AppIDColumnCounter) {
                                    $AppIDInRow = $slice[$icounter];
                                }
                                if($icounter == $AmountColumnCounter){
                                    $AmountInRow = $slice[$icounter];
                                    $insertString = $insertString .  "fos_completed_status = '1',fos_submit_datetime='',fos_completed='1',is_assignto_fos='1',totalamt='".$AmountInRow."',Payment_Collected_Date='".date('d-m-Y H:i:s')."'";
                                }
                                $ValCounter++;
                            }
                            $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppIDInRow . "' and fos_completed=1 and fos_completed_status=1 and  am_accaptance in('0', '1') ");
                            if(mysqli_num_rows($lanapp) == 0){
                                //$insertString = $insertString . ",fos_submit_datetime='" . date('d-m-Y') . "'  where  Account_No =" . "'" . $AppIDInRow . "' and am_accaptance IN ('0','1') ";
                                $insertString = $insertString . " where  Account_No =" . "'" . $AppIDInRow . "' and am_accaptance IN ('0','1') ";
                                
                                mysqli_query($dbconn, $insertString);
                                $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppIDInRow . "' "));
                                $userData = array(
                                    "fosid" => $lanapp['fosid'],
                                    "appid" => $lanapp['applicationid'],
                                    "status" => 1,
                                    "comment" => '',
                                    'ptp_datetime' => date('d-m-Y'),
                                    "strEntryDate" => date('d-m-Y'),
                                    "strIP" => $_SERVER['REMOTE_ADDR']
                                );
                                $insert = $connect->insertrecord($dbconn, 'foshistory', $userData);
                                $user_Data = array(
                                    "fosid" => $lanapp['fosid'],
                                    "applicationid" => $lanapp['applicationid'],
                                    "status" => 1,
                                    "Payment_Collected_Amount" => 0,
                                    "penal" => 0,
                                    "totalamt" => $AmountInRow,
                                    "strEntryDate" => date('d-m-Y'),
                                    "strIP" => $_SERVER['REMOTE_ADDR']
                                );
                                $connect->insertrecord($dbconn, 'applicationpaymentcollectedhistory', $user_Data);
                            }
                            
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
