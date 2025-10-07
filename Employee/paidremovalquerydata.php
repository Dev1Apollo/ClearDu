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

    case "paidremoval":

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
            //step 2
            //get reords with form id 1 : excel column name
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
                            $insertString = "UPDATE `application` SET ";
                            $Whwere = "";
                            for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                //if($icounter != 0){
                                if($slice[$icounter] != 'Account No'){
                                    // echo $icounter;
                                    // // echo "<br />";
                                    // echo $AppIDColumnCounter;
                                    // // echo "<br />";
                                    // echo $slice[$icounter];
                                    // echo "<br />";
                                    //if ($icounter == $AppIDColumnCounter) {
                                        $AppIDInRow = $slice[$icounter];
                                    //}
                                }
                                // $ValCounter++;
                            }
                            $insertString = $insertString . " fos_completed_status=0,fos_completed=0,fos_comment='',fos_submit_datetime='',Payment_Collected_Amount=0,penal=0,totalamt=0,Payment_Collected_Date='' where  Account_No =" . "'" . $AppIDInRow . "' and am_accaptance IN ('0','1') ";
                            //echo $insertString;
                            mysqli_query($dbconn, $insertString);
                            
                            $qry = "update applicationpaymentcollectedhistory set isDelete=1 where applicationid in (select applicationid from application where  Account_No =" . "'" . $AppIDInRow . "')";
                            mysqli_query($dbconn, $qry);
                            
                        }
                    }
                }
            }
            exit;
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            unlink($file_path);
        }
        break;
}
