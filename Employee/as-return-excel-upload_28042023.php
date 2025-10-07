<?php
ob_start();
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];

switch ($action) {

    case "TestUploadApplicationAdminEmployee":

        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);
            $ListOfAppId = array();
            $Sheets = $Reader->Sheets();
            //$errorString = "";
            foreach ($Sheets as $Index => $Name) {

                $Reader->ChangeSheet($Index);


                $ValCounter = 0;
                $LANNUMBERColumnCounter = -1;
                $excelnameColumnCounter = -1;
                $AgencyColumnCounter = 1;
                $reasonColumnCounter = 1;
                $errorString = "";
                $jCounterArray = 0;
                foreach ($Reader as $key => $slice) {

                    if ($ValCounter == 0) {

                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;


                                if (trim($slice[$icounter]) == "App Id") {
                                    $LANNUMBERColumnCounter = $icounter;
                                }

                                if (trim($slice[$icounter]) == "Agency_name") {
                                    $AgencyColumnCounter = $icounter;
                                }

                                if (trim($slice[$icounter]) == "reason") {
                                    $reasonColumnCounter = $icounter;
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

                                    $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0')");
                                    if (mysqli_num_rows($lanapp) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . " not match <br/>";
                                    } else {
                                        $rowlanapp = mysqli_fetch_array($lanapp);
                                        $LANNUMBERInRow = $rowlanapp['App_Id'];
                                    }
                                }
                            }
                            if ($icounter == $LANNUMBERColumnCounter) {
                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {

                                    $lanappbkt1 = mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0') ");
                                    if (mysqli_num_rows($lanappbkt1) > 0) {
                                        $rowlanappbkt1 = mysqli_fetch_array($lanappbkt1);
                                        $currentdate = date('d-m-Y');
                                        if (strtotime($currentdate) > strtotime($rowlanappbkt1['excel_return_date'])) {
                                            $errorString .= "Return Restricted for App Id" . $LANNUMBER . " &row No =" . $ValCounter . " <br/>";
                                        } else {
                                          
                                            $LANNUMBERInRow = $rowlanappbkt1['App_Id'];
                                        }
                                    }
                                }
                            }

                            if ($icounter == $AgencyColumnCounter) {
                                $Agencyname = $slice[$icounter];
                                if (trim($Agencyname) != "") {
                                    $agencyname = mysqli_query($dbconn, "SELECT * FROM `application`  where  agency='" . $Agencyname . "' AND am_accaptance in ('1','0')");
                                    if (mysqli_num_rows($agencyname) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Agencyname =" . $Agencyname . " not match <br/>";
                                    } else {
                                        $rowagencyname=mysqli_fetch_array($agencyname);
                                        $AgencynameIdInRow = $rowagencyname['agency'];
                                    }
                                }
                            }

                            if ($icounter == $reasonColumnCounter) {
                                $reason = $slice[$icounter];
                                if (trim($reason) != "") {
                                    $reson = $reason;
                                } else {
                                    $errorString .= "Row " . $ValCounter . " & Reason NULL  <br/>";
                                }
                            }

                            if ($icounter == $LANNUMBERColumnCounter) {

                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {

                                    $lanappBktx = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "' and fos_completed_status IN ('1','10','11','12','13','14','15') AND am_accaptance in ('1','0')"));
                                    if ($lanappBktx['countL'] > 0) {
                                        $errorString .= " App Id" . $LANNUMBER . "App ID already PAID  <br/>";
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
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='5' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString .= "Column Not found in excel " . $excelcolumnname . "<br/>";
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

                                for ($icounter = 0; $icounter < count($slice); $icounter++) {


                                    if (trim($slice[$icounter]) != "") {
                                        $headerArray[$jCounterArray] = $slice[$icounter];
                                        $jCounterArray++;

                                        if (trim($slice[$icounter]) == "App Id") {
                                            $LANNUMBERColumnCounter = $icounter;
                                        }

                                        if (trim($slice[$icounter]) == "Agency_name") {
                                            $AgencyColumnCounter = $icounter;
                                        }

                                        if (trim($slice[$icounter]) == "reason") {
                                            $reasonColumnCounter = $icounter;
                                        }
                                    }

                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='5' and excelColumnName='" . trim($slice[$icounter]) . "'";
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
                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0')"));
                                    $LANNUMBERInRow = $lanapp['App_Id'];
                                    array_push($ListOfAppId, $LANNUMBERInRow);

                                    $currentmonth = date('d-m-Y');
                                    $applicationid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))    "));
                                    $appid = $applicationid['applicationid'];
                                }

                                if ($icounter == $reasonColumnCounter) {

                                    $reason = $slice[$icounter];
                                }

                                if ($icounter == $AgencyColumnCounter) {
                                    $Agencyname = $slice[$icounter];
                                    $agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  agency='" . $Agencyname . "'"));
                                    $agencynameIdInRow = $agencyname['agency'];


                                    $Agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='" . $Agencyname . "'"));
                                    $AgencyidInRow = $Agencyid['Agencyid'];
                                }

                                $ValCounter++;
                            }

                            $userData = array(
                                "appid" => $appid,
                                "acno" => $LANNUMBERInRow,
                                "agnecyname" => $agencynameIdInRow,
                                "agencyid" => $AgencyidInRow,
                                "reason" => $reason,
                                "strEntryDate" => date('d-m-Y H:i:s'),
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );

                            $insert = $connect->insertrecord($dbconn, 'returncasehistory', $userData);
                            $data1 = array(
                                "app_id" => $appid,
                                "emp_id" => $_SESSION['agencysupervisorid'],
                                "emp_type" => $_SESSION['Type'],
                                "action_name" => 'Return By Agency supervisor',
                                "strEntryDate" => date('d-m-Y H:i:s'),
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );
                            $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data1);

                            $currentmonth = date('d-m-Y');

                            $insertString = $insertString . "return_date" . "=" . "'" . date('d-m-Y') . "'" . ",is_assignto_fos" . "=" . "0" . ",fos_completed_status" . "=" . "0" . ",fos_completed" . "=" . "0" . ",fosid" . "=" . "0" . ",am_accaptance" . "=" . "3" . ",agency = " . "'" . $agencynameIdInRow . "' " . ",reason = " . "'" . $reason . "' " . ",agencyid = " . $AgencyidInRow . $Whwere = " where  App_Id = " . "'" . $LANNUMBERInRow . "' and  fos_completed_status NOT IN ('1','10','11','12','13','14','15')  AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  ";
                            mysqli_query($dbconn, $insertString);
                            //echo $insertString;
                        }
                    }
                }


                $filename = 'freshallocationuploaded.xls';
                $output = fopen($filename, 'w');
                $str_filedata = '';
                $str_filedata_head = '';
                $str_filedata_head .=
                    "SrNo"
                    . "\t  Agency Name"
                    . "\t  App Id"
                    . "\t Reason"
                    . "\n";
                fwrite($output, $str_filedata_head);
                $i = 1;
                $freshal = mysqli_query($dbconn, "SELECT *  FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  App_Id in (" . implode(',', $ListOfAppId) . ") ");
                while ($row1 = mysqli_fetch_array($freshal)) {

                    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
                    $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
                    $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));

                    $str_filedata .=
                        $i
                        . "\t" . $asname['agencyname']
                        . "\t" . $row1['App_Id']
                        . "\t" . $row1['reason']
                        . "\n";
                    $i++;
                }

                $totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbktx FROM `application` WHERE (Bkt='x' or Bkt='0')  and  agencyid='" . $AgencyidInRow . "'and App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0')"));
                $totalbkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbkt1 FROM `application` WHERE Bkt='1'  and  agencyid='" . $AgencyidInRow . "' and App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0')"));
                $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $AgencyidInRow . "' "));

                $mailFormat = file_get_contents("agencyreturncases.html");
                $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);

                $mailFormat = str_replace("#totalbktx#", ucfirst(urldecode($totalbktx['totalbktx'])), $mailFormat);
                $mailFormat = str_replace("#totalbkt1#", ucfirst(urldecode($totalbkt1['totalbkt1'])), $mailFormat);
                $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);

                $mailTR = file_get_contents("agencyreturncases_tr.html");
                $TrValueToReplace = "";
                $branch = mysqli_query($dbconn, "SELECT Branch  FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0') GROUP by Branch");
                while ($rowbranch = mysqli_fetch_array($branch)) {
                    $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $AgencyidInRow . "' and (Bkt='x' or Bkt='0')  and App_Id in (" . implode(',', $ListOfAppId) . ") and Branch = '" . $rowbranch['Branch'] . "'  and am_accaptance IN ('1','0')"));
                    $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  Bkt='1'  and App_Id in (" . implode(',', $ListOfAppId) . ") and Branch = '" . $rowbranch['Branch'] . "' and am_accaptance IN ('1','0')"));
                    $trValueToChaneg = $mailTR;

                    $trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['Branch'])), $trValueToChaneg);
                    $trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
                    $trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

                    $TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
                }
                $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);
                fwrite($output, $str_filedata);
                fclose($output);

                $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Case Return From -' . $foremail['agencyname'], $filename, $foremail['cc'], '');
            }

            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            unlink($file_path);
        }
        break;
}
