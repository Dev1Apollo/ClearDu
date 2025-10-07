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

    case "uploadannexuredata":

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
                $annexureidColumnCounter = -1;
                $alpsidColumnCounter = -1;
                //$errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "annexureid") {
                                    $annexureidColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "appid") {
                                    $alpsidColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {

                        $annexureInRow = 0;
                        $agencyInRow = 0;
                        $AgencynameIdInRow = 0;
                        // $appidInRow = 0;
                        $col1Value = "";
                        $stateName = "";
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                            if ($icounter == $annexureidColumnCounter) {

                                $annexureid = $slice[$icounter];
                                if (trim($annexureid) != "") {

                                    $annexuremaster = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,annexuremaster.* FROM `annexuremaster`  where  annexureid='" . $annexureid . "'"));
                                    if ($annexuremaster['countL'] > 0) {
                                        $errorString .= "Row " . $ValCounter . " & annexureid =" . $annexureid . " match <br/>";
                                    }else if(!is_numeric($annexureid )) {
                                         $errorString .= "Row " . $ValCounter . " & annexureid is not numeric=" . $annexureid . "  <br/>";
                                    }else if(strlen($annexureid ) > 9) {
                                         $errorString .= "Row " . $ValCounter . " & annexureid is greterthen 9 =" . $annexureid . "  <br/>";
                                    }                                    
                                    else {
                                        $annexureInRow = $annexuremaster['annexureid'];
                                    }
                                }
                            }
                            if ($icounter == $alpsidColumnCounter) {

                                $alps = $slice[$icounter];
                                if (trim($alps) != "") {

                                    $applicationalps = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $alps . "'"));
                                    if ($applicationalps['countL'] == 0) {
                                        $errorString .= "Row " . $ValCounter . " & uniqueId =" . $alps . " not match <br/>";
                                    } else {
                                        $agencyInRow = $applicationalps['agencyid'];
                                    }
                                }
                            }

                        }
                    }

                    $ValCounter ++;
                }
            }
            //    echo $errorString;
            //step 2

            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='6' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString.= "Column Not found in excel " . $excelcolumnname . "<br/>";
                }
            }
            echo $statusMsg = $errorString ? $errorString : '0';
            //exit;

            if ($statusMsg == '0') {

//old code

                foreach ($Sheets as $Index => $Name) {

                    $Reader->ChangeSheet($Index);

                    $icount = 1;

                    $ValCounter = 0;

                    $insertString = "insert into annexuremaster(";
                    $ColumnCounter = 0;

                    $appidColumnCounter = -1;
                    $annexureColumnCounter = -1;
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

                                        if (trim($slice[$icounter]) == "annexureid") {
                                            $annexureColumnCounter = $icounter;
                                        }
                                        if (trim($slice[$icounter]) == "appid") {
                                            $appidColumnCounter = $icounter;
                                        }
                                    }

                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='6' and excelColumnName='" . trim($slice[$icounter]) . "'";
                                        $formDetail = mysqli_fetch_array(mysqli_query($dbconn, $Sql));

                                        $insertString = $insertString . $formDetail['dbColumnName'] . ",";
                                    }
                                }
                                $setHeader = 1;
                            }
                        }
                    }

                    $insertString = trim($insertString, ',');
                    $insertString = $insertString . ",agencyid,strEntryDate,strIP";
                    $insertString .= ") values (";
                    $ExeuteInsert = $insertString;


                    foreach ($Reader as $key => $slice) {

                        if ($key > 0) {
                            $annexureidInRow = 0;
                            $appidInRow = 0;
                            for ($icounter = 0; $icounter < count($slice); $icounter++) {

                                if ($icounter == $annexureColumnCounter) {
                                     $annexure = $slice[$icounter];
                                     $annexureidInRow = $annexure;
                                }
                                if ($icounter == $appidColumnCounter) {
                                    $appid = $slice[$icounter];
                                     $appidInRow = $appid;
                                }
                                $data = array(
                                    "annexureid" => $annexureidInRow,
                                );
                                $where = ' where  App_Id =' . $appidInRow;
                                $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
                                
                                $ValCounter ++;
                            }


                            for ($icounter = 0; $icounter < $ColumnCounter; $icounter ++) {
                                //$alpsidColumnCounter


                                $ExeuteInsert = $ExeuteInsert . "'" . trim($slice[$icounter]) . "',";
                                //}
                            }




                            $ExeuteInsert = trim($ExeuteInsert, ',');
                            $ExeuteInsert = $ExeuteInsert . ",'" . $agencyInRow . "','" . date('d-m-Y H:i:s') . "','" . $_SERVER['REMOTE_ADDR'];
                            $ExeuteInsert = $ExeuteInsert . "')";
                            $ExecuteInsert = true;

                            mysqli_query($dbconn, $ExeuteInsert);
                            // echo $ExeuteInsert;
                        }

                        $ExeuteInsert = $insertString;
                    }
                }
            } else {
                echo $errorString;
                exit;
            }
        } else {
            echo "sorry not found in else";
            exit;
        }


        break;
}    