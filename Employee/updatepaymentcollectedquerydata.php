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

    case "Updatepaymentcollected":

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
                $PaymentCollectedDateColumnCounter = -1;



                //$errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;


                                if (trim($slice[$icounter]) == "App ID") {
                                   echo  $AppIDColumnCounter = $icounter;
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
                                if (trim($slice[$icounter]) == "Payment Collected Date") {
                                    $PaymentCollectedDateColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {

                        $AppIDInRow = 0;
                        $excelnameIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";
                   //     $fosid = "";
                        $PaymentCollectedAmount = "";
                        $PenalAmountCollected = "";
                        $emiamount = 0;

                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                           
                            if ($icounter == $AppIDColumnCounter) {

                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and  agencyid = '" . $_SESSION['agencyname'] . "'  "));
                                    if ($lanapp['countL'] == 0) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " Agency not match. <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
                                    }
                                }
                            }                           



                            if ($icounter == $AppIDColumnCounter) {

                                $AppID = $slice[$icounter];
                                if (trim($AppID) != "") {

                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $AppID . "' and  fos_completed_status  IN ('1','10','11','12','13','14','15') and  am_accaptance in('0', '1') "));
                                    if ($lanapp['countL'] > 0) {

                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . " App ID Already paid Status <br/>";
                                    } else {
                                        $AppIDInRow = $lanapp['App_Id'];
                                    }
                                }
                            }                            

                            if ($icounter == $PaymentCollectedAmountColumnCounter) {
                                $PaymentCollectedAmount = $slice[$icounter];
                            }
                            if ($icounter == $PenalAmountCollectedColumnCounter) {
                                $PenalAmountCollected = $slice[$icounter];
                            }

                            if ($icounter == $StatusColumnCounter) {

                                $statusname = $slice[$icounter];
                                if (trim($statusname) != "") {

                                    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,fosstatusdrropdown.* FROM `fosstatusdrropdown`  where  status='" . $statusname . "' "));
                                    if ($status['countL'] > 0) {
                                        $fosstatusIDInRow = $status['fosstatusdrropdownid'];
                                        if ($fosstatusIDInRow == 1) {
                                            if ($PaymentCollectedAmount < $emiamount) {
                                                $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "  PaymentCollectedAmount is less then EMI Amount <br/>";
                                            }
                                        } else if ($fosstatusIDInRow == 10) {
                                            if ($PaymentCollectedAmount > $emiamount) {
                                                $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "  Short Payment must be  less then EMI Amount <br/>";
                                            }
                                        } else if ($fosstatusIDInRow == 11) {
                                            if ($PenalAmountCollected <= 0) {
                                                $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "  PenalAmountCollected must be  greater then ZERO <br/>";
                                            }
                                            if ($PaymentCollectedAmount > 0) {
                                                $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "  Payment Collected Amount must be ZERO <br/>";
                                            }
                                        }
                                    } else {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "  Status not match <br/>";
                                    }
                                }
                            }
                            if ($icounter == $PaymentCollectedDateColumnCounter) {

                                $PaymentCollectedDate = $slice[$icounter];

                                if (trim($PaymentCollectedDate) != "") {
                                    $currentmonth = date('m');
                                    $currentyear = date('Y');
                                    $d = cal_days_in_month(CAL_GREGORIAN, $currentmonth, $currentyear);
                                    if ($PaymentCollectedDate > $d) {
                                        $errorString .= "Row " . $ValCounter . " & AppID  =" . $AppID . "Wrong  Date.  <br/>";
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
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='8' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString.= "Column Not found in excel " . $excelcolumnname . "<br/>";
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
                
                    $setHeader = 0;
                    foreach ($Reader as $key => $slice) {

                        if ($key == 0 ) {
                            if ($setHeader == 0) {
                                for ($icounter = 0; $icounter < count($slice); $icounter ++) {


                                    if (trim($slice[$icounter]) != "") {
                                    
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
                                        if (trim($slice[$icounter]) == "Payment Collected Date") {
                                            $PaymentCollectedDateColumnCounter = $icounter;
                                        }
                                    }

                                }
                                $setHeader = 1;
                            }
                        }else{
                                        
                            $insertString = "UPDATE `application` SET ";
                            $Whwere = "";
                            $ExeuteInsert = $insertString;

                            $AppIDInRow = 0;
                            $PaymentCollectedAmountIDInRow = 0;
                            $PenalAmountCollectedIDInRow = 0;
                            $StatusIDInRow = 0;
                            $totalPenalCollected = 0;
                            $fosstatusIDInRow = 0;
                            $PaymentCollectedDateIDInRow= 0;
                      


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
                                // if ($icounter == $AppIDColumnCounter) {
                                //     $AppID = $slice[$icounter];                                   
                                    
                                // }
                                 if ($icounter == $PaymentCollectedDateColumnCounter) {

                                    $PaymentCollectedDate = $slice[$icounter];
                                    $currentdatemonth= date('m-Y');
                                    $PaymentCollectedDateIDInRow = $PaymentCollectedDate.'-'.$currentdatemonth;                                   
                                }

                                $totalPenalCollected = $PaymentCollectedAmountIDInRow + $PenalAmountCollectedIDInRow;

                                $ValCounter ++;
                            }

                            $date = date('d-m-Y');

                            $insertString = $insertString . "Payment_Collected_Amount = " . " '" . $PaymentCollectedAmountIDInRow . "' ," . "Payment_Collected_Date = " . " '" . $PaymentCollectedDateIDInRow . "' ," . "penal = " . " '" . $PenalAmountCollectedIDInRow . "' ," . "fos_completed_status = " . " '" . $fosstatusIDInRow . "' ," . "totalamt = " . " '" . $totalPenalCollected . "', fos_completed='1',is_assignto_fos='1' " . $Whwere = " where  App_Id =" . "'" . $AppIDInRow . "' and am_accaptance != '2' and am_accaptance != '3' ";


                            mysqli_query($dbconn, $insertString);
                            //echo $insertString;
                        }
                    }
                }
            }
        }


        break;
}