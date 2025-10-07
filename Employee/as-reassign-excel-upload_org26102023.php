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
            $errorString = "";
            foreach ($Sheets as $Index => $Name) {
                $Reader->ChangeSheet($Index);

                $ValCounter = 0;
                $LANNUMBERColumnCounter = -1;
                $excelnameColumnCounter = -1;
                $AgencyColumnCounter = 1;
                $reasonColumnCounter = 1;
                //$errorString = "";
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
                                if (trim($slice[$icounter]) == "FosId") {
                                    $AgencyColumnCounter = $icounter;
                                }
                            }
                        }
                    } else {
                        $LANNUMBERInRow = 0;
                        $excelnameIdInRow = 0;
                        $AgencynameIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";
						$agencyid = 0;
						$agency = "";
						$LANNUMBER = 0;
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }

                            if ($icounter == $LANNUMBERColumnCounter) {
                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {
									$lanapp = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $LANNUMBER . "' AND am_accaptance in ('1','0')");
                                    if (mysqli_num_rows($lanapp) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & App Id =" . $LANNUMBER . " not match <br/>";
                                    } else {
                                        $rowlanapp = mysqli_fetch_array($lanapp);
                                        $LANNUMBERInRow = $rowlanapp['Account_No'];
                                    }
                                }
                            }
							
							if ($icounter == $LANNUMBERColumnCounter) {
                                $LANNUMBER = $slice[$icounter];
                                if (trim($LANNUMBER) != "") {
									$lanappbkt1 = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $LANNUMBER . "' AND am_accaptance in ('1','0') ");
                                    if (mysqli_num_rows($lanappbkt1) > 0) {
                                        $rowlanappbkt1 = mysqli_fetch_array($lanappbkt1);
                                        $currentdate = date('d-m-Y');
										$fos_completed_status = array(1,10,11);
                                        // if (strtotime($currentdate) > strtotime($rowlanappbkt1['excel_return_date'])) {
                                        //     $errorString .= "Restricted for App Id" . $LANNUMBER . " &row No =" . $ValCounter . " <br/>";
                                        // } else 
										if(in_array($rowlanappbkt1['fos_completed_status'], $fos_completed_status)) {
											$completedstatus= "";
											if($rowlanappbkt1['fos_completed_status'] == 1){
												$completedstatus = "Payment Collected";
											} else if($rowlanappbkt1['fos_completed_status'] == 10){
												$completedstatus = "Short Payment";
											} else {
												$completedstatus = "Penalty collected";
											}
											$errorString .= "Restricted for App Id " . $LANNUMBER . " & Status =" . $completedstatus . " <br/>";
										} else {
                                            $LANNUMBERInRow = $rowlanappbkt1['Account_No'];
											$agencyid = $rowlanappbkt1['agencyid'];
											$agency = $rowlanappbkt1['agency'];
                                        }
                                    }
                                }
                            }
                          
                            if ($icounter == $AgencyColumnCounter) {
								$Agencyname = $slice[$icounter];
								if (trim($Agencyname) != "") {
									$agencyname = mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where  loginId like '" . $Agencyname . "' AND type='FOS' ");
                                    if (mysqli_num_rows($agencyname) == 0) {
										$errorString .= "Restricted for App Id " . $LANNUMBER . " & Agencyname =" . $agency . " not match <br/>";
                                    } else {
                                        $rowagencyname=mysqli_fetch_array($agencyname);
										if($agencyid != 0){
											if($agencyid == $rowagencyname['agencyname']){
												$AgencynameIdInRow = $Agencyname;
											} else {
												$errorString .= "Restricted for App Id " . $LANNUMBER . " & Fos Agencyname =" . $agency . " not match <br/>";
											}
										}
                                    }
                                }
                            }
                        }
                    }

                    $ValCounter ++;
                }
            }
			if(isset($errorString) && ($errorString != "" || $errorString != null)){
				echo $statusMsg = $errorString;
				$filename = trim($_REQUEST['IMgallery']);
				$file_path = 'temp/' . $filename;
				unlink($file_path);
			} else {
				//step 2
				
				$insertString = "";
				$LANNUMBERInRow = 0;
				$ListOfAppId = array();
				$appid = 0;
				$agencynameIdInRow = 0;
				$AgencyidInRow = 0;
				$fosid = 0;
				$agencyid = 0;
				$fos_completed_status = 0;
				foreach ($Sheets as $Index => $Name) {
					$Reader->ChangeSheet($Index);
					$icount = 1;
					$ValCounter = 0;

					//$insertString .= "UPDATE `application` SET ";
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
										if (trim($slice[$icounter]) == "Account No") {
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
								$setHeader = 1;
							}
						}
					}
					
					foreach ($Reader as $key => $slice) {
						if ($key > 0) {

							$insertString = "UPDATE `application` SET ";
							$Whwere = "";
							//$ExeuteInsert = $insertString;
							$excelnameIdInRow = 0;
							$stasteInRow = 0;
							
							for ($icounter = 0; $icounter < count($slice); $icounter++) {
								if ($icounter == $LANNUMBERColumnCounter) {
									$LANNUMBERInRow = $slice[$icounter];
									$appid = $slice[$icounter];
									$lanapp = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,application.* FROM `application`  where  Account_No='" . $LANNUMBERInRow . "' AND am_accaptance in ('1','0')"));
									$fos_completed_status = $lanapp['fos_completed_status'];
									$LANNUMBERInRow = $lanapp['Account_No'];                                    
									array_push($ListOfAppId, $LANNUMBERInRow);

									// $currentmonth = date('d-m-Y');
									// $applicationid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as counta,application.* FROM `application`  where  App_Id='" . $LANNUMBER . "' AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))    "));
									// $appid = $LANNUMBER;
								}                             

								// if ($icounter == $AgencyColumnCounter) {
								// 	$Agencyname = $slice[$icounter];
								// 	$agencyname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $LANNUMBERInRow . "'"));
								// 	$agencynameIdInRow = $agencyname['agency'];
								// 	echo "SELECT count(*)as counta,agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='" . $agencynameIdInRow . "'";
								// 	exit;
								// 	$Agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as counta,agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='" . $agencynameIdInRow . "'"));
								// 	$AgencyidInRow = $Agencyid['Agencyid'];
								// }

								if ($icounter == $AgencyColumnCounter) {
									$Agencyname = $slice[$icounter];
									if (trim($Agencyname) != "") {
										$agencyname = mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where  loginId like '" . $Agencyname . "' AND type='FOS' ");
										$rowagencyname=mysqli_fetch_array($agencyname);
										$fosid = $rowagencyname['agencymanagerid'];
										$agencyid = $rowagencyname['agencyname'];

										$Agencyid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as counta,agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and Agencyid='" . $agencyid . "'"));
										$agencyname = $Agencyid['agencyname'];
									}
								}
								$ValCounter ++;
							}
							$data1 = array(
								"app_id" => $appid,
								"emp_id" => $_SESSION['agencysupervisorid'],
								"emp_type" => $_SESSION['Type'],
								"action_name" => 'Reassign To Fos',
								"strEntryDate" => date('d-m-Y H:i:s'),
								"strIP" => $_SERVER['REMOTE_ADDR']
							);
							$dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data1);
							$currentmonth = date('d-m-Y');
							//$insertString .= "return_date" . "=" . "'" . date('d-m-Y') . "'" . ",is_assignto_fos" . "=" . "0" . ",fos_completed_status" . "=" . "0" . ",fos_completed" . "=" . "0" . ",fosid" . "=" . $fosid . ",am_accaptance" . "=" . "3" . ",agency = " . "'" . $agencyname . "' " . ", agencyid = " . $agencyid . $Whwere = " where  App_Id = " . "'" . $LANNUMBERInRow . "' and  fos_completed_status NOT IN ('1','10','11','12','13','14','15')  AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  ";
							// echo $fos_completed_status;
							if($fos_completed_status == 5){
								$insertString .= "fosid" . "=" . $fosid . ",agency = " . "'" . $agencyname . "' " . ", agencyid = " . $agencyid . $Whwere = " where  Account_No = " . "'" . $appid . "' and  fos_completed_status NOT IN ('1','10','11')  AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  ";
							} else {
								$insertString .= "is_assignto_fos" . "=" . "1" . ",ptp_datetime" . "=" . "''" .",PTP_Date" . "=" . "''" .",fos_submit_datetime" . "=" . "''". ",fos_completed_status" . "=" . "0" . ",fos_completed" . "=" . "0" . ",fosid" . "=" . $fosid . ",agency = " . "'" . $agencyname . "' " . ", agencyid = " . $agencyid . $Whwere = " where  Account_No = " . "'" . $appid . "' and  fos_completed_status NOT IN ('1','10','11')  AND am_accaptance in ('1','0') and month(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Month(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y')) and Year(STR_TO_DATE(strEntryDate,'%d-%m-%Y %H:%i:%s'))= Year(STR_TO_DATE('" . $currentmonth . "','%d-%m-%Y'))  ";
							}
							mysqli_query($dbconn, $insertString);	
						}
					}
				}
				// $filename = 'freshallocationuploaded.xls';
				// $output = fopen($filename, 'w');
				// $str_filedata = '';
				// $str_filedata_head = '';
				// $str_filedata_head .=
				// 		"SrNo"
				// 		. "\t  Agency Name"
				// 		. "\t  App Id"
				// 		. "\t Reason"
				// 		. "\n";
				// fwrite($output, $str_filedata_head);
				// $i = 1;
				
				// $freshal = mysqli_query($dbconn, "SELECT *  FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  App_Id in (" . implode(',', $ListOfAppId) . ") ");
				// while ($row1 = mysqli_fetch_array($freshal)) {

				// 	$asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
				// 	$status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
				// 	$fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));

				// 	$str_filedata .=
				// 			$i
				// 			. "\t" . $asname['agencyname']
				// 			. "\t" . $row1['App_Id']
				// 			. "\t" . $row1['reason']
				// 			. "\n";
				// 	$i++;
				// }

				// $totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbktx FROM `application` WHERE (Bkt='x' or Bkt='0')  and  agencyid='" . $AgencyidInRow . "'and App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0')"));
				// $totalbkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbkt1 FROM `application` WHERE Bkt='1'  and  agencyid='" . $AgencyidInRow . "' and App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0')"));
				// $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $AgencyidInRow . "' "));

				// $mailFormat = file_get_contents("agencyreturncases.html");
				// $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);

				// $mailFormat = str_replace("#totalbktx#", ucfirst(urldecode($totalbktx['totalbktx'])), $mailFormat);
				// $mailFormat = str_replace("#totalbkt1#", ucfirst(urldecode($totalbkt1['totalbkt1'])), $mailFormat);
				// $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);

				// $mailTR = file_get_contents("agencyreturncases_tr.html");
				// $TrValueToReplace = "";
				// $branch = mysqli_query($dbconn, "SELECT Branch  FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  App_Id in (" . implode(',', $ListOfAppId) . ") and am_accaptance IN ('1','0') GROUP by Branch");
				// while ($rowbranch = mysqli_fetch_array($branch)) {
				// 	$bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $AgencyidInRow . "' and (Bkt='x' or Bkt='0')  and App_Id in (" . implode(',', $ListOfAppId) . ") and Branch = '" . $rowbranch['Branch'] . "'  and am_accaptance IN ('1','0')"));
				// 	$bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $AgencyidInRow . "' and  Bkt='1'  and App_Id in (" . implode(',', $ListOfAppId) . ") and Branch = '" . $rowbranch['Branch'] . "' and am_accaptance IN ('1','0')"));
				// 	$trValueToChaneg = $mailTR;

				// 	$trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['Branch'])), $trValueToChaneg);
				// 	$trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
				// 	$trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

				// 	$TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
				// }
				// $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);
				// fwrite($output, $str_filedata);
				// fclose($output);

				// $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Case Return From -' . $foremail['agencyname'], $filename, $foremail['cc'], '');
				// }
            
				$filename = trim($_REQUEST['IMgallery']);
				$file_path = 'temp/' . $filename;
				unlink($file_path);
				//echo 0;
			}
        }
        break;
}    
