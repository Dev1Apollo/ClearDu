<?php
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];
$ListOfAppId = "0";
$resons = "0";

switch ($action) {

    case "TestUploadApplicationAdminEmployee":

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
                $LANNUMBERColumnCounter = -1;
                $excelnameColumnCounter = -1;
                $PTPDateColumnCounter = -1;
                $PTPAmountColumnCounter = -1;
                $PTPTimeSlotColumnCounter = -1;
                $reasonColumnCounter = -1;
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

                                if (trim($slice[$icounter]) == "reason") {
                                    $reasonColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {

                        $LANNUMBERInRow = 0;
                        $excelnameIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";

                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                            if ($icounter == $LANNUMBERColumnCounter) {

                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {

                                    // $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "' and am_accaptance IN ('1','0')"));
                                    // if ($lanapp['countL'] == 0) {
                                    //     $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . " not match <br/>";
                                    // } else {
                                    //     $LANNUMBERInRow = $lanapp['App_Id'];
                                    //     $ListOfAppId .= "," . $LANNUMBER;
                                    // }
                                    $lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0')");
                                    if (mysqli_num_rows($lanapp) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . " not match <br/>";
                                    } else {
                                        $rowlanapp = mysqli_fetch_array($lanapp);
										$LANNUMBERInRow = $rowlanapp['App_Id'];
                                        $ListOfAppId .= "," . $LANNUMBER;
                                    }
                                }
                            }
                            
                            
                             if ($icounter == $LANNUMBERColumnCounter) {

                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {

                                    // $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "' and am_accaptance IN ('1','0') and fos_completed_status IN ('1','10','11','12','13','14','15')"));
                                    // if ($lanapp['countL'] > 0) {
                                    //     $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . "App ID already PAID <br/>";
                                    // }
                                    
                                    $lanappbkt1 = mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0') ");
                                    if (mysqli_num_rows($lanappbkt1) > 0) {
                                        $rowlanappbkt1 = mysqli_fetch_array($lanappbkt1);
                                        $currentdate = date('d-m-Y');
										$fos_completed_status = array(1,10,11);

										if(in_array($rowlanappbkt1['fos_completed_status'], $fos_completed_status)) {
											$completedstatus= "";
											if($rowlanappbkt1['fos_completed_status'] == 1){
												$completedstatus = "Payment Collected";
											} else if($rowlanappbkt1['fos_completed_status'] == 10){
												$completedstatus = "Short Payment";
											} else {
												$completedstatus = "Penalty collected";
											}
											//$errorString .= "Restricted for App Id " . $LANNUMBER . " & Status =" . $completedstatus . " <br/>";
											$errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . "App ID already PAID <br/>";
										}
									}
                                }
                            }

                            if ($icounter == $reasonColumnCounter) {
                                $reason = $slice[$icounter];
                                if (trim($reason) != "") {
                                    $resons .= "," . $reason;
                                } else {
                                    $errorString .= "Row " . $ValCounter . " & Reason NULL  <br/>";
                                }
                            }
                        }
                    }

                    $ValCounter ++;
                }
            }

            //step 2
//get reords with form id 1 : excel column name
            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='2' ";
            $result = mysqli_query($dbconn, $Sql);

            while ($row = mysqli_fetch_array($result)) {
                $excelcolumnname = $row['excelColumnName'];

                if (!in_array($excelcolumnname, $headerArray)) {
                    $errorString.= "Column Not found in excel " . $excelcolumnname . "<br/>";
                }
            }
            echo $statusMsg = $errorString ? $errorString : '0';



            if ($statusMsg == '0') {


                $AgencyWithdrawnCases = mysqli_query($dbconn, "SELECT distinct  agencyid FROM application where App_Id in (" . $ListOfAppId . ") and am_accaptance IN ('1','0') ");
                while ($row = mysqli_fetch_array($AgencyWithdrawnCases)) {


                    $filename = 'casewithdrawnbycentralteam.xls';
                    $output = fopen($filename, 'w');
                    $str_filedata = '';
                    $str_filedata_head = '';
                    $str_filedata_head .=
                            "SrNo"
                            . "\t  App Id"
                            . "\t  Withdraw Reason"
                            . "\n";
                    fwrite($output, $str_filedata_head);
                    $i = 1;
                    $freshal = mysqli_query($dbconn, "SELECT *   FROM application where App_Id in (" . $ListOfAppId . ") and  agencyid='" . $row['agencyid'] . "' and am_accaptance IN ('1','0')  ");
                    while ($row1 = mysqli_fetch_array($freshal)) {

                        $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
                        $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
                        $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));
                        $reson1 = explode(',', $resons);

                        $str_filedata .=
                                $i
                                . "\t" . $row1['App_Id']
                                . "\t" . $reson1[$i]
                                . "\n";
                        $i++;
                    }

                    // $TotalWithdrawnCount = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as TotalWithdrawnCount FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and   App_Id in (".$ListOfAppId.")"));
                    $Totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as Totalbktx FROM `application` WHERE (Bkt='x' or Bkt='0')  and  agencyid='" . $row['agencyid'] . "'  and App_Id in (" . $ListOfAppId . ") and am_accaptance IN ('1','0')"));
                    $Totalbkt1 = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as Totalbkt1 FROM `application` WHERE Bkt='1'  and  agencyid='" . $row['agencyid'] . "'  and App_Id in (" . $ListOfAppId . ") and am_accaptance IN ('1','0') "));
                    $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $row['agencyid'] . "' "));

                    $mailFormat = file_get_contents("agencywithdrawncases.html");
                    $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);
                    //$mailFormat = str_replace("#TotalWithdrawnCount#", ucfirst(urldecode($TotalWithdrawnCount['TotalWithdrawnCount'])), $mailFormat);
                    $mailFormat = str_replace("#Totalbktx#", ucfirst(urldecode($Totalbktx['Totalbktx'])), $mailFormat);
                    $mailFormat = str_replace("#Totalbkt1#", ucfirst(urldecode($Totalbkt1['Totalbkt1'])), $mailFormat);
                    $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);

                    $mailTR = file_get_contents("agencywithdrawncases_tr.html");
                    $TrValueToReplace = "";
                    $branch = mysqli_query($dbconn, "SELECT Branch  FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  App_Id in (" . $ListOfAppId . ") GROUP by Branch");
                    while ($rowbranch = mysqli_fetch_array($branch)) {
                        $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $row['agencyid'] . "' and (Bkt='x' or Bkt='0')  and App_Id in (" . $ListOfAppId . ") and Branch = '" . $rowbranch['Branch'] . "'  "));
                        $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  Bkt='1'  and App_Id in (" . $ListOfAppId . ") and Branch = '" . $rowbranch['Branch'] . "' "));
                        $trValueToChaneg = $mailTR;

                        $trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['Branch'])), $trValueToChaneg);
                        $trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
                        $trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

                        $TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
                    }
                    $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);
                    fwrite($output, $str_filedata);
                    fclose($output);

                    $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Case withdrawn By Central Team-' . $foremail['agencyname'], $filename, $foremail['cc'], '');
                }
            }
            if ($statusMsg == '0') {

//old code  
                $agencyArray = array();
                $agencys;
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
                                        if (trim($slice[$icounter]) == "reason") {
                                            $reasonColumnCounter = $icounter;
                                        }
                                    }

                                    if (trim($slice[$icounter]) != "") {

                                        $ColumnCounter = $ColumnCounter + 1;
                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='2' and excelColumnName='" . trim($slice[$icounter]) . "'";
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

                            $agencys;
                            $LANNUMBERInRow = 0;
                            $DateInInRow = 0;
                            $PTPAmount = 0;
                            $PTPTimeSlot = 0;
                            $agencyidInRow = 0;

                            for ($icounter = 0; $icounter < count($slice); $icounter++) {

                                if ($icounter == $LANNUMBERColumnCounter) {

                                    $LANNUMBER = $slice[$icounter];
                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBER . "'"));
                                    $LANNUMBERInRow = $lanapp['App_Id'];
                                    $agencyidInRow = $lanapp['agencyid'] . ',' . $agencyidInRow;
                                }
                                if ($icounter == $reasonColumnCounter) {

                                    $reason = $slice[$icounter];
//                                    $lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "'"));
//                                    $LANNUMBERInRow = $lanapp['App_Id'];
//                                    $agencyidInRow = $lanapp['agencyid'] . ',' . $agencyidInRow;
                                }

                                $ValCounter ++;
                            }
                            $agencyidInRow = rtrim($agencyidInRow, ", ");
                            $data = array(
                                "app_id" => $LANNUMBERInRow,
                                "emp_id" => $_SESSION['centralmanagerid'],
                                "emp_type" => $_SESSION['Type'],
                                "action_name" => 'Withdraw By Central Team',
                                "strEntryDate" => date('d-m-Y H:i:s'),
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );
                            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);

                            $agencys = $agencyidInRow;


                            $currentmonth = date('d-m-Y');

                            $insertString = $insertString . "withdraw_reason" . "=" . "'" . $reason . "'" . ",withdraw_date" . "=" . "'" . date('d-m-Y') . "'" . ",am_accaptance" . "=" . "2" . ",fosid = 0 ,is_assignto_fos = 0,fos_completed_status=0,fos_completed=0 where  App_Id =" . "'" . $LANNUMBERInRow . "'   and fos_completed_status NOT IN ('1','12','13','14','15') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and am_accaptance IN ('1','0')  ";

                            mysqli_query($dbconn, $insertString);                          
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