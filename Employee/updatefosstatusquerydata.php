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

    case "Updatefosstatus":

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
                $StatusColumnCounter = -1;
                $PaymentCollectedDateColumnCounter = -1;


                //$errorString = "";
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
                                if (trim($slice[$icounter]) == "Status") {
                                    $StatusColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Date") {
                                    $PaymentCollectedDateColumnCounter = $icounter;
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
                            //         //and  fos_completed_status  IN ('1','10','11','12','13','14','15')
                            //         $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppID . "'  and  am_accaptance in('0', '1') ");
                            //         if (mysqli_num_rows($lanapp) > 0) {
                            //             $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " Account No Already paid Status. <br/>";
                            //         } else {
                            //             $rowapp = mysqli_fetch_array($lanapp);
                            //             $AppIDInRow = $rowapp['Account_No'];
                            //         }
                            //     } else {
                            //         $errorString .= "Row " . $ValCounter . " & Account No  can not null ,";
                            //     }
                            // }

                            if ($icounter == $AppIDColumnCounter) {
                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {
                                    $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppID . "' and  am_accaptance in('0', '1')  and  fosid='0'");
                                    if (mysqli_num_rows($lanapp) > 0) {
                                        $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " is not assign to FOS . <br/>";
                                    } else {
                                        $rowapp = mysqli_fetch_array($lanapp);
                                        $AppIDInRow = $rowapp['Account_No'];
                                    }
                                }
                            }

                            if ($icounter == $StatusColumnCounter) {

                                $statusname = $slice[$icounter];
                                if (trim($statusname) != "") {
                                    $status = mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where  status='" . $statusname . "' and istatus=1 and isDelete=0");
                                    if (mysqli_num_rows($status) > 0) {
                                        $rowStatus = mysqli_fetch_array($status);
                                        $fosstatusIDInRow = $rowStatus['fosstatusdrropdownid'];
                                    } else {
                                        $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . "  Status not match <br/>";
                                    }
                                } else {
                                    $errorString .= "Row " . $ValCounter . " & Status can not null ,";
                                }
                            }

                            if ($icounter == $PaymentCollectedDateColumnCounter) {

                                $PaymentCollectedDate = $slice[$icounter];
                                $lanapp = mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  Account_No='" . $AppID . "' and am_accaptance in('0', '1') ");
                                if ($fosstatusIDInRow == 3) {
                                    if (trim($PaymentCollectedDate) != "") {
                                        $currentdate = date('d');
                                        $currentmonth = date('m');
                                        $currentyear = date('Y');
                                        $d = cal_days_in_month(CAL_GREGORIAN, $currentmonth, $currentyear);
                                        if ($PaymentCollectedDate > $d || $PaymentCollectedDate < $currentdate) {
                                            $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " Wrong PTP Reschedule Date.  <br/>";
                                        } else {
                                            $rowapp = mysqli_fetch_array($lanapp);
                                            $AppIDInRow = $rowapp['Account_No'];
                                        }
                                    } else {
                                        $errorString .= "Row " . $ValCounter . " & Account No  =" . $AppID . " PTP reschedule data is NULL.  <br/>";
                                    }
                                } else {
                                    $rowapp = mysqli_fetch_array($lanapp);
                                    $AppIDInRow = $rowapp['Account_No'];
                                }
                            }
                        }
                    }

                    $ValCounter++;
                }
            }

            //step 2
            //get reords with form id 1 : excel column name
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='9' ";
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

                    $insertString = "UPDATE `application` SET ";
                    $ColumnCounter = 0;
                    $ApplicationNumberPosition = 0;
                    $jCounterArray = 0;
                    $setHeader = 0;
                    $ValCounter = 0;
                    $AppIDColumnCounter = -1;
                    $StatusColumnCounter = -1;
                    $PaymentCollectedDateColumnCounter = -1;
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
                                        if (trim($slice[$icounter]) == "Status") {
                                            $StatusColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "Date") {
                                            $PaymentCollectedDateColumnCounter = $icounter;
                                        }
                                    }
                                }
                                $setHeader = 1;
                            }
                        } else {
                            $insertString = "UPDATE `application` SET ";
                            $Whwere = "";
                            $ExeuteInsert = $insertString;
                            $AppIDInRow = 0;
                            $PaymentCollectedDateIDInRow = 0;
                            $fosid = "";

                            for ($icounter = 0; $icounter < count($slice); $icounter++) {
                                //    $fosstatusIDInRow = 0;
                                if ($icounter == $AppIDColumnCounter) {
                                    $AppIDInRow = $slice[$icounter];
                                }
                                if ($icounter == $StatusColumnCounter) {
                                    $StatusIDInRow = $slice[$icounter];
                                    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where  status='" . $StatusIDInRow . "'  "));
                                    $fosstatusIDInRow = $status['fosstatusdrropdownid'];
                                    if ($fosstatusIDInRow != 3) {
                                        $insertString = $insertString .  "fos_completed_status = '" . $fosstatusIDInRow . "',fos_completed='1',is_assignto_fos='1' ";
                                    }else{
                                        $insertString = $insertString .  "fos_completed='1',is_assignto_fos='1' ";
                                    }
                                }
                                if ($icounter == $PaymentCollectedDateColumnCounter) {
                                    $PaymentCollectedDate = $slice[$icounter];
                                    if (trim($PaymentCollectedDate) != "") {
                                        if ($fosstatusIDInRow == 3) {
                                            $currentdatemonth = date('m-Y');
                                            $PaymentCollectedDateIDInRow = $PaymentCollectedDate . '-' . $currentdatemonth;                                            
                                            $insertString = $insertString .  ",ptp_datetime = " . " '" . $PaymentCollectedDateIDInRow . "'";
                                            if($PaymentCollectedDate==$currentdate){
                                                $insertString = $insertString .  ",fos_completed_status = '5'"; 
                                            }else{
                                                $insertString = $insertString .  ",fos_completed_status = '" . $fosstatusIDInRow . "'";
                                            }
                                        }
                                    }
                                }
                                $ValCounter++;
                            }
                            $insertString = $insertString . ",fos_submit_datetime='" . date('d-m-Y') . "'  where  Account_No =" . "'" . $AppIDInRow . "' and am_accaptance IN ('0','1') ";
    
                            $updateStatus = mysqli_query($dbconn, $insertString);
                            $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AppIDInRow . "' "));
                            $userData = array(
                                "fosid" => $lanapp['fosid'],
                                "appid" => $lanapp['applicationid'],
                                "status" => $fosstatusIDInRow,
                                "comment" => '',
                                'ptp_datetime' => $PaymentCollectedDateIDInRow,
                                "strEntryDate" => date('d-m-Y'),
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );
                            $insert = $connect->insertrecord($dbconn, 'foshistory', $userData);
                        }
                    }
                }
            }
        }
        $filename = trim($_REQUEST['IMgallery']);
        $file_path = 'temp/' . $filename;
        unlink($file_path);
        break;
}
