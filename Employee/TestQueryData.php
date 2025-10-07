<?php
ob_start();
error_reporting(E_ALL);
ini_set('max_execution_time', 0);
include_once '../common.php';
$connect = new connect();
header('Content-Type: text/html; charset=utf-8');
mysqli_set_charset($dbconn, "utf8");
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';
require '../PHPMailer-master/PHPMailerAutoload.php';
$action = $_REQUEST['action'];

switch ($action) {

    case "TestUploadApplicationAdminEmployee":


        //        if ($_REQUEST['companyID'] == '2') {
        $succesCount = 0;
        $failureCount = 0;
        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path, false, 'UTF-8');
            
            $Sheets = $Reader->Sheets();
            
            $errorString_final = "";
            $errorIds = array();
            $agencyArray = array();
            $EmailBodyArray = array();
            $listofappid = 0;
            foreach ($Sheets as $Index => $Name) {
                $Reader->ChangeSheet($Index);

                $ValCounter = 0;
                $iCompanyColumnCounter = -1;
                $iSTATEColumnCounter = -1;
                $iPRODUCTColumnCounter = -1;
                $iBranchColumnCounter = -1;
                $iBktColumnCounter = -1;
                $iLocationColumnCounter = -1;
                $AgencyColumnCounter = -1;
                $AppIdColumnCounter = -1;
                $AccountNoColumnCounter = -1;
          //      $PTPDateColumnCounter = -1;
           //     $returndateColumnCounter = -1;
                $TimeSlotColumnCounter = -1;
                $customercityColumnCounter = -1;
                $AllocationDateColumnCounter = -1;
                $TimeSlotColumnCounter = -1;
                $iCustomerNameColumnCounter = -1;
                $iTotalPOSAmountColumnCounter = -1;
                $iTotalPenltyColumnCounter = -1;
                $ContactNumberColumnCounter = -1;
                $iBankNameColumnCounter = -1;
                //$errorString = "";
                $jCounterArray = 0;
                $icounter = 0;
                $ColumnCounter = 0;
                foreach ($Reader as $key => $slice) {
                    $errorString = "";
                    if ($ValCounter == 0) {
                        
                        $insertString = '';
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {

                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "Bank Name") {
                                    $iBankNameColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Bkt") {
                                    $iBktColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Customer Name") {
                                    $iCustomerNameColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "PRODUCT") {
                                    $iPRODUCTColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Branch") {
                                    $iBranchColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "State") {
                                    $iSTATEColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Total POS Amount") {
                                    $iTotalPOSAmountColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Total penlty") {
                                    $iTotalPenltyColumnCounter = $icounter;
                                }

                                if (trim($slice[$icounter]) == "Agency_name") {
                                    $AgencyColumnCounter = $icounter;
                                }

                                if (trim($slice[$icounter]) == "App Id") {
                                    $AppIdColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Account No") {
                                    $AccountNoColumnCounter = $icounter;
                                }
                                // if (trim($slice[$icounter]) == "PTP Date") {
                                //     $PTPDateColumnCounter = $icounter;
                                // }
                                if (trim($slice[$icounter]) == "Customer City") {
                                    $customercityColumnCounter = $icounter;
                                }
                                // if (trim($slice[$icounter]) == "Return Till") {
                                //     $returndateColumnCounter = $icounter;
                                // }
                                if (trim($slice[$icounter]) == "Allocation Date") {
                                    $AllocationDateColumnCounter = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Contact Number") {
                                    $ContactNumberColumnCounter = $icounter;
                                }


                                $ColumnCounter = $ColumnCounter + 1;
                                $Sql = "SELECT * FROM `formdetail` WHERE `formId`='1' and excelColumnName='" . trim($slice[$icounter]) . "'";
                                $formDetail = mysqli_fetch_array(mysqli_query($dbconn, $Sql));

                                $insertString = $insertString . $formDetail['dbColumnName'] . ",";
                            }
                        }
                        $insertString = trim($insertString, ',');
                        $insertString = $insertString . ",assign_as_datetime,agencyid,stateid,locationid,customer_city_id,iBankId,iProductId,strEntryDate,strIP";
                        $insertString .= ") values (";
                        $ExeuteInsert = $insertString;
                    } else {
                        $ExeuteInsert = $insertString;
                        $CompanyIdInRow = 0;
                        $stasteInRow = 0;
                        $AgencyidInRow = 0;
                        $LocationIdInRow = 0;
                        $col1Value = "";
                        $stateName = "";
                        $bkt = "";
                        $bktInRow = 0;
                        $PRODUCTInRow = 0;
                        $PRODUCT = '';
                        $BranchInRow = 0;
                        $Branch = '';
                        $BranchlocInRow = 0;
                        $customercity = 0;
                        $customercityInRow = 0;
                        $AppIdInRow = 0;
                        $returnDateInInRow = 0;
                        $AllocationDateInInRow = 0;
                        $TimeSlotColumnCounter = 0;
                        $BankIdInRow = 0;
                        $PRODUCTIDInRow = 0;
                        $DateInInRow = 0;
                        $AccountNoInRow=0;
                        for ($icounter = 0; $icounter < 34; $icounter++) {
                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }
                            if ($icounter == $iBankNameColumnCounter) {
                                $bankName = $slice[$icounter];
                                if (trim($bankName) != "") {
                                    $bankNamequery = mysqli_query($dbconn, "SELECT * FROM `bankmaster` where strBankName='".trim($bankName)."' and isDelete=0 and iStatus=1");
                                    if (mysqli_num_rows($bankNamequery) == 0) {
                                        $errorString .= "Row " . $ValCounter . " and Bank Name " . $bankName . " not match ,";
                                    } else {
                                        $rownamename = mysqli_fetch_array($bankNamequery);
                                        $BankIdInRow = $rownamename['iBankId'];
                                    }
                                } else {
                                    $errorString .= "Row " . $ValCounter . " and Bank Name Is Null ,";
                                }
                            }
                            if ($icounter == $AccountNoColumnCounter) {
                                $AccountNo = $slice[$icounter];
                                if (trim($AccountNo) != "") {
                                    $AccountNoquery = mysqli_query($dbconn, "SELECT * FROM `application`  where  Account_No='" . $AccountNo . "' and am_accaptance IN (0,1) ");
                                    if (mysqli_num_rows($AccountNoquery) > 0) {
                                        $errorString .= "Row " . $ValCounter . " and AccountNo " . $AccountNo . " match ,";
                                    } else {
                                        $AccountNoInRow = $AccountNo;
                                    }
                                } else {
                                    $errorString .= "Row " . $ValCounter . " and AccountNo Is Null ,";
                                }
                            }
                            if ($icounter == $iCustomerNameColumnCounter){
                                $CustomerName = $slice[$icounter];
                                if (trim($CustomerName) == "") {
                                    $errorString .= "Row " . $ValCounter . " & Customer name can not null ,";
                                }
                            }
                            if ($icounter == $iPRODUCTColumnCounter) {
                                $PRODUCT = $slice[$icounter];
                                if (trim($PRODUCT) != "") {
                                    $productname = mysqli_query($dbconn, "SELECT * FROM `product`  where  productname ='" . $PRODUCT . "' and istatus=1 and isDelete=0");
                                    if (mysqli_num_rows($productname) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & productname =" . $PRODUCT . " not match ,";
                                    } else {
                                        $rowproductname = mysqli_fetch_array($productname);
                                        $PRODUCTInRow = $rowproductname['productname'];
                                        $PRODUCTIDInRow = $rowproductname['productid'];
                                    }
                                }
                            }
                            if ($icounter == $iBranchColumnCounter) {
                                $Branch = $slice[$icounter];
                                if (trim($Branch) != "") {
                                    $Branchname = mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' and locationName='" . $Branch . "' and isDelete=0 ");
                                    if (mysqli_num_rows($Branchname) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Branchname =" . $Branch . " not match ,";
                                    } else {
                                        $rowBranchname = mysqli_fetch_array($Branchname);
                                        $BranchInRow = $rowBranchname['locationId'];
                                    }
                                }
                            }
                            if ($icounter == $customercityColumnCounter) {
                                $customercity = $slice[$icounter];
                                if (trim($customercity) != "") {
                                    $customercityname = mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1' and locationName='" . $customercity . "' and isDelete=0 ");
                                    if (mysqli_num_rows($customercityname) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Customer City Name =" . $customercity . " not match ,";
                                    } else {
                                        $rowcustomercityname=mysqli_fetch_array($customercityname);
                                        $customercityInRow = $rowcustomercityname['locationId'];
                                    }
                                }
                            }
                            if ($icounter == $iSTATEColumnCounter) {
                                $stateName = $slice[$icounter];
                                if (trim($stateName) != "") {
                                    $state = mysqli_query($dbconn, "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateName='" . $stateName . "'");
                                    if (mysqli_num_rows($state) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & state =" . $stateName . " not match ,";
                                    } else {
                                        $rowstate=mysqli_fetch_array($state);
                                        $stasteInRow = $rowstate['stateId'];
                                    }
                                }
                            }
                            // if ($icounter == $iTotalPOSAmountColumnCounter){
                            //     $TotalPOSAmount = $slice[$icounter];
                            //     if (trim($TotalPOSAmount) == "") {
                            //         $errorString .= "Row " . $ValCounter . " & Total POS Amount can not null ,";
                            //     }
                            // }
                            if ($icounter == $iTotalPenltyColumnCounter){
                                $TotalPenlty = $slice[$icounter];
                                if (trim($TotalPenlty) == "") {
                                    $errorString .= "Row " . $ValCounter . " & Total Penlty can not null ,";
                                }
                            }
                            if($icounter == $ContactNumberColumnCounter){
                                $ContactNumber = $slice[$icounter];
                                if (trim($ContactNumber) == "") {
                                    $errorString .= "Row " . $ValCounter . " & Contact Number can not null ,";
                                }
                            }
                            if ($icounter == $AgencyColumnCounter) {
                                $AgencyName = $slice[$icounter];
                                if (trim($AgencyName) != "") {
                                    $SqlAgency = "SELECT agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='" . $AgencyName . "'";
                                    $Agencyres = mysqli_query($dbconn, $SqlAgency);
                                    if (mysqli_num_rows($Agencyres) == 0) {
                                        $errorString .= "Row " . $ValCounter . " & Agency =" . $AgencyName . " not match ,";
                                    } else {
                                        $Agency = mysqli_fetch_array($Agencyres);
                                        $AgencyidInRow = $Agency['Agencyid'];
                                    }
                                } else {
                                    $errorString .= "Row " . $ValCounter . " & Agency   can not null ,";
                                }
                            }
                            
                            // if ($icounter == $iBktColumnCounter) {
                            //     $bkt = $slice[$icounter];
                            //     if (trim($bkt) != "") {
                            //         $bktname = mysqli_query($dbconn, "SELECT * FROM `bkt`  where  bktname ='" . $bkt . "' and istatus=1 and isDelete=0");
                            //         if (mysqli_num_rows($bktname) == 0) {
                            //             //$errorString .= "Row " . $ValCounter . " & bktname =" . $bkt . " not match ,";
                            //         } else {
                            //             $rowbktname = mysqli_fetch_array($bktname);
                            //             $bktInRow = $rowbktname['bktname'];
                            //         }
                            //     }
                            // }

                            // if ($icounter == $AppIdColumnCounter) {
                            //     $AppId = $slice[$icounter];
                            //     if (trim($AppId) != "") {
                            //         $AppIdquery = mysqli_query($dbconn, "SELECT * FROM `application`  where  App_Id='" . $AppId . "' and am_accaptance IN (0,1)  ");
                            //         if (mysqli_num_rows($AppIdquery) > 0) {
                            //             //$errorString .= "Row " . $ValCounter . " and App_Id " . $AppId . " Match ,";
                            //         } else {
                            //             $AppIdInRow = $AppId;
                            //         }
                            //     } 
                            //     // else {
                            //     //     $errorString .= "Row " . $ValCounter . " and App_Id Is Null ,";
                            //     // }
                            // }
                            
                            // if ($icounter == $PTPDateColumnCounter) {
                            //     $date1 = $slice[$icounter];
                            //     if (trim($date1) != "") {
                            //         $date = DateTime::createFromFormat('m-d-y', $date1);
                            //         if ($date) {
                            //             $DateInInRow = date_format($date, 'd-m-Y');
                            //             $ExeuteInsert = $ExeuteInsert . "'" . $DateInInRow . "',";
                            //         } else {
                            //             $errorString .= "Row " . $ValCounter . " and date  formate must be dd-mm-YYYY ,";
                            //             $ExeuteInsert = $ExeuteInsert . "'',";
                            //         }
                            //     } else {
                            //         $ExeuteInsert = $ExeuteInsert . "'',";
                            //     }
                            // } else if ($icounter == $returndateColumnCounter) {
                            //     $date2 = $slice[$icounter];
                            //     if (trim($date2) != "") {
                            //         $date = DateTime::createFromFormat('m-d-y', $date2);
                            //         if ($date) {
                            //             $returnDateInInRow = date_format($date, 'd-m-Y');
                            //             $ExeuteInsert = $ExeuteInsert . "'" . $returnDateInInRow . "',";
                            //         } else {
                            //             $errorString .= "Row " . $ValCounter . " and date  formate must be dd-mm-YYYY ,";
                            //             $ExeuteInsert = $ExeuteInsert . "'',";
                            //         }
                            //     } else {
                            //         $errorString .= "Row " . $ValCounter . " and Return Date Is Null ,";
                            //         $ExeuteInsert = $ExeuteInsert . "'',";
                            //     }
                            // } else 
                            
                            if ($icounter == $AllocationDateColumnCounter) {

                                $date3 = $slice[$icounter];
                                if (trim($date3) != "") {
                                    $date = DateTime::createFromFormat('m-d-y', $date3);
                                    if ($date) {
                                        $AllocationDateInInRow = date_format($date, 'd-m-Y');
                                        $ExeuteInsert = $ExeuteInsert . "'" . $AllocationDateInInRow . "',";
                                    } else {
                                        $errorString .= "Row " . $ValCounter . " and date  formate must be dd-mm-YYYY ,";
                                        $ExeuteInsert = $ExeuteInsert . "'',";
                                    }
                                } else {
                                    //$errorString .= "Row " . $ValCounter . " and Allocation Date Is Null ,";
                                    $ExeuteInsert = $ExeuteInsert . "'',";
                                }
                            } else {
                                // print_r($slice[$icounter]);
                                // echo "<br />";
                                $ExeuteInsert = $ExeuteInsert . "'" . str_replace("'", "''", trim($slice[$icounter])) . "',";
                            }
        //                     if ($icounter == $returndateColumnCounter) {

        //                         $date2 = $slice[$icounter];
        //                         if (trim($date2) != "") {
        //                             $date = DateTime::createFromFormat('m-d-y', $date2);
        //                             $date2 = date_format($date, 'd-m-Y');
        //                             $crrDate = date('d-m-Y');
        //                             if (strtotime($date2) < strtotime($AllocationDateInInRow)) {
        //                                 $errorString .= "Return date must be Grater then Allocation Date &row No =" . $ValCounter . " ,";
        //                             }
								// 	if (strtotime($date2) < strtotime($crrDate)) {
								// 		$errorString .= "Return date must be Grater then Current Date &row No =" . $ValCounter . " ,";
								// 	}
        //                         } else {
        //                             $errorString .= "Row " . $ValCounter . " and Return Date Is Null ,";
        //                         }
        //                     }
                        }
                        if($BankIdInRow != 0 && $PRODUCTIDInRow != 0){
                            $productbankmappingquery = mysqli_query($dbconn, "SELECT * FROM `product_bank_mapping` where iBankId='".$BankIdInRow."' and iProductId='".$PRODUCTIDInRow."' and isDelete=0 and iStatus=1");
                            if (mysqli_num_rows($productbankmappingquery) == 0) {
                                $errorString .= "Row " . $ValCounter . " and Bank Name or Product not mapped ,";
                            }
                        }
                        $ExeuteInsert = trim($ExeuteInsert, ',');
                        $ExeuteInsert = $ExeuteInsert . ",'" . date('d-m-Y H:i:s') . "','" . $AgencyidInRow . "','" . $stasteInRow . "','" . $BranchInRow . "','" . $customercityInRow . "','". $BankIdInRow ."','".$PRODUCTIDInRow."','" . date('d-m-Y H:i:s') . "','" . $_SERVER['REMOTE_ADDR'];
                        $ExeuteInsert = $ExeuteInsert . "')";
                        // echo $errorString;
                        if ($errorString != '') {
                            $ExeuteInsert = "insert into error_application(" . $ExeuteInsert;
                        } else {

                            $ExeuteInsert = "insert into application(" . $ExeuteInsert;
                        }
                        // echo $ExeuteInsert;
                        // // echo '<br />';
                        // exit;
                        $ExecuteInsert = true;
                        
                        mysqli_query($dbconn, $ExeuteInsert);
                        $update = mysqli_insert_id($dbconn);

                        if ($errorString != '') {
                            $failureCount++;
                            $SqlUpdate = "UPDATE `error_application` SET `uniqueId`='ALPS" . $update . "',error_upload='" . $errorString  . "' WHERE `applicationid`='" . $update . "'";
                        } else {
                            $succesCount++;
                            $SqlUpdate = "UPDATE `application` SET `uniqueId`='ALPS" . $update . "' WHERE `applicationid`='" . $update . "'";
                            $listofappid .= "," . $update;
                        }
                        
                        mysqli_query($dbconn, $SqlUpdate);
                        $ExeuteInsert = $insertString;
                    }
                    $ValCounter++;
                }
            }
            //exit;
            //           }
            //        }
            //            $Sql = "SELECT * FROM `formdetail` WHERE `formId`='1' ";
            //            $result = mysqli_query($dbconn, $Sql);
            //
            //            while ($row = mysqli_fetch_array($result)) {
            //                $excelcolumnname = $row['excelColumnName'];
            //
            //                if (!in_array($excelcolumnname, $headerArray)) {
            //                    $errorString .= "Column Not found in excel " . $excelcolumnname . " ,";
            //                }
            //            }
            //
            //            $statusMsg = $errorString_final ? $errorString_final : '0';
            //        $statusMsg = '1';
            //        if ($errorString == "") {
            //            $statusMsg = '0';
            //        }
            //        if ($statusMsg == '0') {
            //
            //            $agencyArray = array();
            //            $EmailBodyArray = array();
            //            $listofappid = 0;
            //            foreach ($Sheets as $Index => $Name) {
            //
            //                $Reader->ChangeSheet($Index);
            //
            //                $icount = 1;
            //
            //                $ValCounter = 0;
            //
            //                $ColumnCounter = 0;
            ////  $ApplicationNumberPosition = 0;
            //                $jCounterArray = 0;
            //                $setHeader = 0;
            //                foreach ($Reader as $key => $slice) {
            ////                        $insertString = '';
            //                    if ($key == 0 || $key == 1) {
            //                            if ($setHeader == 0) {
            //
            //                                for ($icounter = 0; $icounter < count($slice); $icounter ++) {
            //
            //                                    if (trim($slice[$icounter]) != "") {
            //
            //                                        if (trim($slice[$icounter]) == "Bkt") {
            //                                            $iBktColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "PRODUCT") {
            //                                            $iPRODUCTColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "Branch") {
            //                                            $iBranchColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "Customer City") {
            //                                            $customercityColumnCounter = $icounter;
            //                                        }
            //
            //                                        if (trim($slice[$icounter]) == "State") {
            //                                            $iSTATEColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "Agency_name") {
            //                                            $AgencyColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "PTP Date") {
            //                                            $PTPDateColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "Return Till") {
            //                                            $returndateColumnCounter = $icounter;
            //                                        }
            //                                        if (trim($slice[$icounter]) == "Allocation Date") {
            //                                            $AllocationDateColumnCounter = $icounter;
            //                                        }
            //                                    }
            //
            //
            //                                    if (trim($slice[$icounter]) != "") {
            //
            //                                        $ColumnCounter = $ColumnCounter + 1;
            //                                        $Sql = "SELECT * FROM `formdetail` WHERE `formId`='1' and excelColumnName='" . trim($slice[$icounter]) . "'";
            //                                        $formDetail = mysqli_fetch_array(mysqli_query($dbconn, $Sql));
            //
            //                                        $insertString = $insertString . $formDetail['dbColumnName'] . ",";
            //                                    }
            //                                }
            //                                $setHeader = 1;
            //                            }
            //                            $insertString = trim($insertString, ',');
            //                            $insertString = $insertString . ",assign_as_datetime,agencyid,stateid,locationid,customer_city_id,strEntryDate,strIP";
            //                            $insertString .= ") values (";
            //                            $ExeuteInsert = $insertString;
            //                    } else {
            //                            foreach ($Reader as $key => $slice) {
            //
            //                                if ($key > 0) {
            //                                    for ($icounter = 0; $icounter < count($slice); $icounter++) {
            //                                if ($icounter == $iBktColumnCounter) {
            //
            //                                    $bkt = $slice[$icounter];
            //                                    $bktname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countb,bkt.* FROM `bkt`  where  bktname ='" . $bkt . "'"));
            //                                    $bktInRow = $bktname['bktname'];
            //                                }
            //
            //                                if ($icounter == $iPRODUCTColumnCounter) {
            //
            //                                    $PRODUCT = $slice[$icounter];
            //                                    $productname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countp,product.* FROM `product`  where  productname ='" . $PRODUCT . "'"));
            //                                    $PRODUCTInRow = $productname['productname'];
            //                                }
            //
            //                                if ($icounter == $iBranchColumnCounter) {
            //
            //                                    $Branch = $slice[$icounter];
            //                                    $Branchname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,location.* FROM `location`  where isDelete='0'  and  istatus='1' and locationName='" . $Branch . "'"));
            //                                    $BranchInRow = $Branchname['locationId'];
            //                                }
            //                                if ($icounter == $customercityColumnCounter) {
            //                                    //echo  "SELECT count(*)as countL,location.* FROM `location`  where isDelete='0'  and  istatus='1' and locationName='" . $customercity . "'";
            //                                    
            //                                    $customercity = $slice[$icounter];
            //                                    $customercityname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,location.* FROM `location`  where isDelete='0'  and  istatus='1' and locationName='" . $customercity . "'"));
            //                                    if($customercityname['countL']==1)
            //                                        $customercityInRow = $customercityname['locationId'];
            //                                }
            //                                if ($icounter == $iSTATEColumnCounter) {
            //
            //                                    $stateName = $slice[$icounter];
            //                                    $state = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countL,state.* FROM `state`  where isDelete='0'  and  istatus='1' and stateName='" . $stateName . "'"));
            //                                    $stasteInRow = $state['stateId'];
            //                                }
            //                                if ($icounter == $AgencyColumnCounter) {
            //
            //                                    $AgencyName = $slice[$icounter];
            //                                    $Agency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as counta,agency.* FROM `agency`  where isDelete='0'  and  istatus='1' and agencyname='".$AgencyName."'"));
            //                                    $AgencyidInRow = $Agency['Agencyid'];
            //                                }
            //                                if ($icounter == $PTPDateColumnCounter) {
            //
            //                                    $date1 = $slice[$icounter];
            //                                    if($date1!='' || $date1!=NULL){
            //                                        $date = DateTime::createFromFormat('m-d-y', $date1);
            //                                        $DateInInRow = date_format($date, 'd-m-Y');
            //                                    }
            //                                    
            //                                    // echo $DateInInRow;
            //                                }
            //                                if ($icounter == $returndateColumnCounter) {
            //
            //                                    $date2 = $slice[$icounter];
            //                                    $date = DateTime::createFromFormat('m-d-y', $date2);
            //                                    $returnDateInInRow = date_format($date, 'd-m-Y');
            //                                    // echo $DateInInRow;
            //                                }
            //                                if ($icounter == $AllocationDateColumnCounter) {
            //
            //                                    $date3 = $slice[$icounter];
            //                                    $date = DateTime::createFromFormat('m-d-y', $date3);
            //                                    $AllocationDateInInRow = date_format($date, 'd-m-Y');
            //                                }
            //                            }
            //                            $ValCounter ++;
            //                            for ($icounter = 0; $icounter < $ColumnCounter; $icounter ++) {
            //
            //                                if ($icounter == $PTPDateColumnCounter) {
            //
            //                                    $date1 = $slice[$icounter];
            //                                    if ($date1 != '' || $date1 != NULL) {
            //                                        $date = DateTime::createFromFormat('m-d-y', $date1);
            //                                        $DateInInRow = date_format($date, 'd-m-Y');
            //                                    }
            //                                    $ExeuteInsert = $ExeuteInsert . "'" . $DateInInRow . "',";
            //                                } else if ($icounter == $returndateColumnCounter) {
            //
            //                                    $date2 = $slice[$icounter];
            //                                    $date = DateTime::createFromFormat('m-d-y', $date2);
            //                                    $returnDateInInRow = date_format($date, 'd-m-Y');
            //                                    $ExeuteInsert = $ExeuteInsert . "'" . $returnDateInInRow . "',";
            //                                } else if ($icounter == $AllocationDateColumnCounter) {
            //
            //                                    $date3 = $slice[$icounter];
            //                                    $date = DateTime::createFromFormat('m-d-y', $date3);
            //                                    $AllocationDateInInRow = date_format($date, 'd-m-Y');
            //                                    $ExeuteInsert = $ExeuteInsert . "'" . $AllocationDateInInRow . "',";
            //                                } else {
            //                                    $ExeuteInsert = $ExeuteInsert . "'" . str_replace("'", "''", trim($slice[$icounter])) . "',";
            //                                }
            //                            }
            //                        $ExeuteInsert = trim($ExeuteInsert, ',');
            //                        $ExeuteInsert = $ExeuteInsert . ",'" . date('d-m-Y H:i:s') . "','" . $AgencyidInRow . "','" . $stasteInRow . "','" . $BranchInRow . "','" . $customercityInRow . "','" . date('d-m-Y H:i:s') . "','" . $_SERVER['REMOTE_ADDR'];
            //                        $ExeuteInsert = $ExeuteInsert . "')";
            //
            //
            //                        if (array_key_exists($ValCounter, $errorIds)) {
            //                            $failureCount++;
            //                            $ExeuteInsert = "insert into error_application(" . $ExeuteInsert;
            //                        } else {
            //                            $succesCount++;
            //                            $ExeuteInsert = "insert into application(" . $ExeuteInsert;
            //                        }
            //                        $ExecuteInsert = true;
            //
            //                        mysqli_query($dbconn, $ExeuteInsert);
            //                        $update = mysqli_insert_id($dbconn);
            //
            //                        if (array_key_exists($ValCounter, $errorIds)) {
            //                            $SqlUpdate = "UPDATE `error_application` SET `uniqueId`='ALPS" . $update . "',error_upload='" . $errorIds[$ValCounter] . "' WHERE `applicationid`='" . $update . "'";
            //                        } else {
            //                            $SqlUpdate = "UPDATE `application` SET `uniqueId`='ALPS" . $update . "' WHERE `applicationid`='" . $update . "'";
            //                            $listofappid .= "," . $update;
            //                        }
            //
            //                        mysqli_query($dbconn, $SqlUpdate);
            //                    }
            //
            //
            //
            //                    if (in_array($LocationIdInRow, $agencyArray) == false) {
            //                        array_push($agencyArray, $LocationIdInRow);
            ////array_push($EmailBodyArray[''], '');
            //                    }
            //
            //                    $ExeuteInsert = $insertString;
            //                }
            //            }




            // $freshallocation = mysqli_query($dbconn, "SELECT * FROM application WHERE applicationid in (" . $listofappid . ") GROUP by agencyid");
            // while ($row = mysqli_fetch_array($freshallocation)) {


            //     $filename = 'freshallocationuploaded.xls';
            //     $output = fopen($filename, 'w');
            //     $str_filedata = '';
            //     $str_filedata_head = '';
            //     $str_filedata_head .= "SrNo"
            //         . "\t  Allocation Date"
            //         . "\t  Supervisor Assigned Date Time"
            //         . "\t  unique Id"
            //         . "\t  Account No"
            //         . "\t  Agency Name"
            //         . "\t  PRODUCT"
            //         . "\t  App Id"
            //         . "\t  Bkt"
            //         . "\t  Customer Name"
            //         . "\t  Fathers name"
            //         . "\t  Asset Make"
            //         . "\t  Branch"
            //         . "\t  Customer City"
            //         . "\t  State"
            //         . "\t  Due Month"
            //         . "\t  Allocation Date"
            //         . "\t  Allocation CODE"
            //         . "\t  Bounce Reason"
            //         . "\t  Loan amount"
            //         . "\t  Loan booking Date"
            //         . "\t  Loan maturity date"
            //         . "\t  Frist Emi Date"
            //         . "\t  Due date"
            //         . "\t  Emi Amount Collected"
            //         . "\t  Installment_Overdue_Amount"
            //         . "\t  Bcc"
            //         . "\t  Lpp"
            //         . "\t  Total penlty"
            //         . "\t  Principal outstanding"
            //         . "\t  Vehicle Registration No"
            //         . "\t  Supplier"
            //         . "\t  Tenure"
            //         . "\t  Customer Address"
            //         . "\t  Visit Address"
            //         . "\t  Contact Number"
            //         . "\t  Alternate Contact Number"
            //         . "\t  Collection Manager"
            //         . "\t  State Manager "
            //         . "\t  Ref_1_Name"
            //         . "\t  Contact_Detail"
            //         . "\t  Ref_2_Name"
            //         . "\t  Contact_Detail_ref2"
            //         . "\t  Fos Name"
            //         . "\t  Fos Id"
            //         . "\t  Fos Comment"
            //         . "\t  Fos Status"
            //         . "\t  Payment Collected Date"
            //         . "\t  Payment Collected Amount"
            //         . "\t  Penal Amount Collected"
            //         . "\t  Total  Amount Collected"
            //         . "\t  ptp Re-schedule date"
            //         . "\t  PTP Date"
            //         . "\t  PTP Amount"
            //         . "\t  Time Slot"
            //         . "\t  Pin code"
            //         . "\n";
            //     fwrite($output, $str_filedata_head);
            //     $i = 1;
            //     $freshal = mysqli_query($dbconn, "SELECT * FROM application WHERE applicationid in(" . $listofappid . ") and  agencyid='" . $row['agencyid'] . "' ");
            //     while ($row1 = mysqli_fetch_array($freshal)) {

            //         $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
            //         $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
            //         $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "' and isDelete=0"));


            //         $str_filedata .= $i
            //             . "\t" . $row1['strEntryDate']
            //             . "\t" . $row1['assign_as_datetime']
            //             . "\t" . $row1['uniqueId']
            //             . "\t" . $row1['Account_No']
            //             . "\t" . $asname['agencyname']
            //             . "\t" . $row1['PRODUCT']
            //             . "\t" . $row1['App_Id']
            //             . "\t" . $row1['Bkt']
            //             . "\t" . $row1['Customer_Name']
            //             . "\t" . $row1['Fathers_name']
            //             . "\t" . $row1['Asset_Make']
            //             . "\t" . $row1['Branch']
            //             . "\t" . $row1['customer_city']
            //             . "\t" . $row1['State']
            //             . "\t" . $row1['Due_Month']
            //             . "\t" . $row1['Allocation_Date']
            //             . "\t" . $row1['Allocation_CODE']
            //             . "\t" . $row1['Bounce_Reason']
            //             . "\t" . $row1['Loan_amount']
            //             . "\t" . $row1['Loan_booking_Date']
            //             . "\t" . $row1['Loan_maturity_date']
            //             . "\t" . $row1['Frist_Emi_Date']
            //             . "\t" . $row1['Due_date']
            //             . "\t" . $row1['Emi_amount']
            //             . "\t" . $row1['Installment_Overdue_Amount']
            //             . "\t" . $row1['Bcc']
            //             . "\t" . $row1['Lpp']
            //             . "\t" . $row1['Total_penlty']
            //             . "\t" . $row1['Principal_outstanding']
            //             . "\t" . $row1['Vehicle_Registration_No']
            //             . "\t" . $row1['Supplier']
            //             . "\t" . $row1['Tenure']
            //             . "\t" . $row1['Customer_Address']
            //             . "\t" . $row1['visit_address']
            //             . "\t" . $row1['Contact_Number']
            //             . "\t" . $row1['alternate_contact_number']
            //             . "\t" . $row1['Collection_Manager']
            //             . "\t" . $connect->RemoveSpecialChapr($row1['State_Manager'])
            //             . "\t" . $connect->RemoveSpecialChapr($row1['Ref_1_Name'])
            //             . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail'])
            //             . "\t" . $connect->RemoveSpecialChapr($row1['Ref_2_Name'])
            //             . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail_ref2'])
            //             . "\t" . $fos['employeename']
            //             . "\t" . $fos['loginId']
            //             . "\t" . $connect->RemoveSpecialChapr($row1['fos_comment'])
            //             . "\t" . $status['status']
            //             . "\t" . $row1['Payment_Collected_Date']
            //             . "\t" . $row1['Payment_Collected_Amount']
            //             . "\t" . $row1['penal']
            //             . "\t" . $row1['totalamt']
            //             . "\t" . $row1['ptp_datetime']
            //             . "\t" . $row1['PTP_Date']
            //             . "\t" . $row1['PTP_Amount']
            //             . "\t" . $row1['Time_Slot']
            //             . "\t" . $row1['Pincode']
            //             . "\n";
            //         $i++;
            //     }



            //     //                    $totalallocation = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalallocation FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  applicationid in(" . $listofappid . ")"));
            //     $totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbktx FROM `application` WHERE  agencyid='" . $row['agencyid'] . "' and (Bkt='x' or Bkt='0')  and applicationid in(" . $listofappid . ") "));
            //     $totalbkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbkt1 FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  Bkt='1'  and applicationid in(" . $listofappid . ")"));
            //     $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $row['agencyid'] . "' "));

            //     $mailFormat = file_get_contents("agencyfreshallocation_new.html");
            //     $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);
            //     //                    $mailFormat = str_replace("#TotalAllocationCount#", ucfirst(urldecode($totalallocation['totalallocation'])), $mailFormat);
            //     $mailFormat = str_replace("#totalbktx#", ucfirst(urldecode($totalbktx['totalbktx'])), $mailFormat);
            //     $mailFormat = str_replace("#totalbkt1#", ucfirst(urldecode($totalbkt1['totalbkt1'])), $mailFormat);
            //     //                    $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);


            //     $mailTR = file_get_contents("agencyfreshallocation_new_tr.html");
            //     $TrValueToReplace = "";
            //     $branch = mysqli_query($dbconn, "SELECT *  FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  applicationid in(" . $listofappid . ") GROUP by Branch");
            //     while ($rowbranch = mysqli_fetch_array($branch)) {

            //         $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $row['agencyid'] . "' and (Bkt='x' or Bkt='0')  and applicationid in(" . $listofappid . ") and Branch = '" . $rowbranch['Branch'] . "'  "));
            //         $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  Bkt='1'  and applicationid in(" . $listofappid . ") and Branch = '" . $rowbranch['Branch'] . "' "));
            //         $trValueToChaneg = $mailTR;


            //         $trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['Branch'])), $trValueToChaneg);
            //         $trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
            //         $trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

            //         $TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
            //     }
            //     $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);
            //     fwrite($output, $str_filedata);
            //     fclose($output);


            //     $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Fresh Allocation Uploaded-' . $foremail['agencyname'], $filename, $foremail['cc'], '');
            // }

            echo 'There are ' . $succesCount . ' Successful Application and ' . $failureCount . " Error Application.";
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            unlink($file_path);
        } else {
            echo "sorry not found in else";
        }
        break;
}
