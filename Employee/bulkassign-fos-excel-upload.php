<?php

error_reporting(0);
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
            $reasonColumnCounter = 1;
            $LANNUMBERColumnCounter = -1;
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);

            $Sheets = $Reader->Sheets();
            //$errorString = "";
            foreach ($Sheets as $Index => $Name) {

                $Reader->ChangeSheet($Index);


                $ValCounter = 0;

                $excelnameColumnCounter = -1;
                $AgencyColumnCounter = 1;

                $errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "Account No") {
                                    $LANNUMBERColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "FosId") {
                                    $reasonColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {
                        break;
                    }

                    $ValCounter ++;
                }
            }
            $statusMsg = '0';
            if ($statusMsg == '0') {

//old code

                foreach ($Sheets as $Index => $Name) {

                    $Reader->ChangeSheet($Index);

                    $icount = 1;

                    $ValCounter = 0;
                    $ColumnCounter = 0;
                    $ApplicationNumberPosition = 0;
                    $jCounterArray = 0;
                    $setHeader = 0;
                    foreach ($Reader as $key => $slice) {
                        if ($ValCounter > 0) {
                            if ($slice != null) {
                                if ($slice[$LANNUMBERColumnCounter] != "" && $slice[$reasonColumnCounter] != "") {
                                    //agencyname
                                    //  echo "select agencymanagerid as agencymanagerid from agencymanager where loginId = '".$slice[$reasonColumnCounter]."' and type='FOS'";
                                    $fosid = mysqli_query($dbconn, "select agencymanagerid as agencymanagerid from agencymanager where loginId = '" . $slice[$reasonColumnCounter] . "' and type='FOS' and agencyname='" . $_SESSION['agencyname'] . "' and isDelete='0' and istatus='1' ");
                                    if (mysqli_num_rows($fosid) > 0) {
                                        $fosids = mysqli_fetch_assoc($fosid);
                                        if ($fosids['agencymanagerid'] != '') {
                                            $sql = "update application set application.fosid ='" . $fosids['agencymanagerid'] . "' ,is_assignto_fos = 1,assign_fos_datetime = '" . date('d-m-Y H:i:s') . "' where application.Account_No = '" . $slice[$LANNUMBERColumnCounter] . "' and application.agencyid='" . $_SESSION['agencyname'] . "'";
//                                    echo "update application set application.fosid ='".$fosids['agencymanagerid']."' ,is_assignto_fos = 1,assign_fos_datetime = '".date('d-m-Y H:i:s')."' where application.App_Id = '".$slice[$LANNUMBERColumnCounter]."' and application.agencyid='".$_SESSION['agencyname']."'";
//                                    exit;
                                            mysqli_query($dbconn, $sql);
                                        }
                                    } else {
                                        echo $slice[$reasonColumnCounter] . " Not Match <br/>";
                                    }
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }
                        }
                        $ValCounter++;
                    }
                }
            }
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            unlink($file_path);
        } else {
            echo 'else';
        }
        break;
}