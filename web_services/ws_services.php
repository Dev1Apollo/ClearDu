<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include_once '../common.php';
include_once '../password_hash.php';
$connect = new connect();
$actions = isset($_REQUEST['action']) ? strtolower(trim($_REQUEST['action'])) : '';
extract($_REQUEST);
$output = [];
if ($actions == 'loginfos') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            $sql1 = "select * from agencymanager where loginId ='" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
            $result1 = mysqli_query($dbconn, $sql1);
            $row1 = mysqli_fetch_assoc($result1);
            
            $updatelast = "UPDATE `agencymanager` SET `LastLogin` = '" . date('d-m-Y H:i:s') . "' WHERE  `loginId` ='" . $obj->loginId . "'";
            mysqli_query($dbconn, $updatelast);
            
            $LastLogin = date('d-m-Y',strtotime($row1['LastLogin']));
            if($LastLogin != date('d-m-Y')){
                $userData = array(
                    "strFosId" => $obj->loginId,
                    "iAgencyManagerid" => $row['agencymanagerid'],
                    "strLatitude" => isset($obj->strLatitude) && $obj->strLatitude != "" ? $obj->strLatitude : "",
                    "strLongitude" => isset($obj->strLongitude) && $obj->strLongitude != "" ? $obj->strLongitude : "",
                    "strEntryDate" => date('d-m-Y H:i:s'),
                    "strLocation" => "Login Entry",
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $insert = $connect->insertrecord($dbconn, 'fosloginlog', $userData);
            }
            $output['FosDetail'] = $row1;
            $output['message'] = 'login sucessfull';
            $output['success'] = '1';
        } else {
            $output['message'] = 'Password not match';
            $output["success"] = '0';
        }
    } else {

        $output['message'] = 'User or Password not match';
        $output['success'] = '0';
    }
} else if ($actions == 'fosassignapplication') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            if ($obj->applicationid == '0') {
                //$filterstr = "select *  from application WHERE is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' and runsheet='0' and (PTP_Date is null or ptp_datetime ='') ORDER BY STR_TO_DATE(PTP_Date,'%d-%m-%Y') ASC";
                $filterstr = "select *  from application WHERE is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' and fos_completed_status!=20 and runsheet='0' and (PTP_Date is null or ptp_datetime ='') ORDER BY applicationid ASC";
            } else {
                $filterstr = "select *,(select fosstatusdrropdown.status from fosstatusdrropdown WHERE fosstatusdrropdown.fosstatusdrropdownid=application.fos_completed_status)as status  from application WHERE  applicationid='" . $obj->applicationid . "' and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  ORDER BY STR_TO_DATE(PTP_Date,'%d-%m-%Y') ASC  ";
            }
            // echo $filterstr;
            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                $jCounter = 1;
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['fosassignapplicationDetail'][] = array(
                                "iCounter" => $jCounter,
                                "applicationid" => $row['applicationid'],
                                "Bank_Name" => $row['Bank_Name'],
                                "Account_No" => $row['Account_No'],
                                "App_Id" => $row['App_Id'],
                                "Bkt" => $row['Bkt'],
                                "Customer_Name" => $row['Customer_Name'],
                                "Fathers_name" => $row['Fathers_name'],
                                "Asset_Make" => $row['Asset_Make'],
                                "PRODUCT" => $row['PRODUCT'],
                                "Branch" => $row['Branch'],
                                "customer_city" => $row['customer_city'],
                                "State" => $row['State'],
                                "cycle" => $row['cycle'],
                                "Due_date" => $row['Due_date'],
                                "Allocation_Date" => $row['Allocation_Date'],
                                "Allocation_CODE" => $row['Allocation_CODE'],
                                "Bounce_Reason" => $row['Bounce_Reason'],
                                "Loan_amount" => $row['Loan_amount'],
                                "Loan_booking_Date" => $row['Loan_booking_Date'],
                                "Loan_maturity_date" => $row['Loan_maturity_date'],
                                "Emi_amount" => $row['Emi_amount'],
                                "Total_Pos_Amount" => isset($row['Total_Pos_Amount']) && $row['Total_Pos_Amount'] != "" ? $row['Total_Pos_Amount'] : 0,
                                "Total_penlty" => $row['Total_penlty'],
                                "Customer_Address" => $row['Customer_Address'],
                                "Business_Address" => $row['Business_Address'],
                                "Contact_Number" => $row['Contact_Number'],
                                "alternate_contact_number" => $row['alternate_contact_number'],
                                "Ref_1_Name" => $row['Ref_1_Name'],
                                "Contact_Detail" => $row['Contact_Detail'],
                                "Ref_2_Name" => $row['Ref_2_Name'],
                                "Contact_Detail_ref2" => $row['Contact_Detail_ref2'],
                                "agency" => $row['agency'],
                                "Pincode" => $row['Pincode'],
                                "Area_Name" => $row['Area_Name'],
                                "agencyid" => $row['agencyid'],
                                "is_assignto_fos" => $row['is_assignto_fos'],
                                "assign_fos_datetime" => $row['assign_fos_datetime'],
                                "fosid" => $row['fosid'],
                                "fos_completed" => $row['fos_completed'],
                                "fos_completed_status" => $row['fos_completed_status'],
                                "strFosOtherRemark" => $row['strFosOtherRemark'],
                                "fos_comment" => $row['fos_comment'],
                                "ptp_datetime" => $row['ptp_datetime'],
                                "fos_submit_datetime" => $row['fos_submit_datetime'],
                                "Payment_Collected_Date" => $row['Payment_Collected_Date'],
                                "Payment_Collected_Amount" => $row['Payment_Collected_Amount'],
                                "reason" => $row['reason'],
                                "runsheet" => $row['runsheet'],
                                "runsheetsequnce" => $row['runsheetsequnce'],
                                "PTP_Date" => $row['PTP_Date'],
                                "PTP_Amount" => $row['PTP_Amount'],
                                "Time_Slot" => $row['Time_Slot'],
                                "customer_city_id" => $row['customer_city_id'],
                                "withdraw_date" => $row['withdraw_date'],
                                "return_date" => $row['return_date'],
                                "withdraw_reason" => $row['withdraw_reason'],
                                "is_photo_uploaded" => $row['is_photo_uploaded'],
                                "error_upload" => $row['error_upload'],
                                "uniqueId" => $row['uniqueId'],
                                "excelfilename" => $row['excelfilename'],
                                "excelnameid" => $row['excelnameid'],
                                "locationid" => $row['locationid'],
                                "iStatus" => $row['iStatus'],
                                "isDelete" => $row['isDelete'],
                                "strEntryDate" => $row['strEntryDate'],
                                "strIP" => $row['strIP'],
                                "created_at" => $row['created_at'],
                                "updated_at" => $row['updated_at'],
                                "is_assignto_am" => $row['is_assignto_am'],
                                "am_accaptance" => $row['am_accaptance'],
                                "penal" => $row['penal'],
                                "totalamt" => $row['totalamt'],
                                "stateid" => $row['stateid'],
                                "SrNo" => $row['SrNo'],
                                "assign_as_datetime" => $row['assign_as_datetime'],
                                "AlternetMobileNo" => $row['AlternetMobileNo'],
                                "iBankId" => $row['iBankId'],
                                "iProductId" => $row['iProductId'],
                                "status" => isset($row['status']) ? $row['status'] : "",
                            );
                    $jCounter++;
                }
                if ($obj->applicationid > '0'){
                	if($obj->applicationid != '') {
                	
                    	$filterstrData = "select *,(select status from fosstatusdrropdown where fosstatusdrropdown.fosstatusdrropdownid=foshistory.status) as fosStatus from foshistory WHERE appid='".$obj->applicationid."' order by foshistoryid desc";
                    	$resultData = mysqli_query($dbconn, $filterstrData);
    			if (mysqli_num_rows($resultData) > 0) {
                        while ($rowData = mysqli_fetch_assoc($resultData)) {
                            $output['foshistory'][] = $rowData;
                        }
                    } else {
                        $output['foshistory']=[];
                    }
                    }
                } else {
                    $output['foshistory']=[];
                }
		
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'tcdetaillist') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            $filterstr = "SELECT * FROM tchistory  where  applicationid='" . $obj->applicationid . "' ORDER BY tchistoryid desc limit 3 ";

            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['tcdetaillistview'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
}  else if ($actions == 'foscomplitedapplication') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {


            $filterstr = "select *  from application WHERE is_assignto_fos='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='1' ";
            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['fosassignapplicationDetail'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {

            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'submitmultiapplicationdetails') {
    //veryfied app
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {


            $count = count($obj->applicationdetails);
            for ($iCounter = 0; $iCounter < $count; $iCounter++) {
                if ($obj->applicationdetails[$iCounter]->fos_completed_status == '1') {
                    $fos_completed = '1';
                } else if ($obj->applicationdetails[$iCounter]->fos_completed_status == '2') {
                    $fos_completed = '1';
                } else if ($obj->applicationdetails[$iCounter]->fos_completed_status == '3') {
                    //ptpresheduled
                    $fos_completed = '1';
                } else if ($obj->applicationdetails[$iCounter]->fos_completed_status == '4') {
                    $fos_completed = '1';
                } else if ($obj->applicationdetails[$iCounter]->fos_completed_status == '16') {
                    $fos_completed = '1';
                }
                $applicationdetails = array(
                    "fos_completed_status" => $obj->applicationdetails[$iCounter]->fos_completed_status,
                    "fos_completed" => $fos_completed,
                    "fos_comment" => $obj->applicationdetails[$iCounter]->fos_comment,
                    'ptp_datetime' => $obj->applicationdetails[$iCounter]->ptp_datetime,
                    "fos_submit_datetime" => $obj->applicationdetails[$iCounter]->fos_submit_datetime,
                    "runsheet"=>'0',
                    "strIP" => $obj->applicationdetails[$iCounter]->strIP,
                );
                $where = " where applicationid = '" . $obj->applicationdetails[$iCounter]->applicationid . "' ";
                $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
            }
            if ($applicationdetails_res) {
                $output['message'] = '';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'Not Data Found';
            $output["success"] = '0';
        }
    } else {
        $output['message'] = 'User or Password not match';
        $output['success'] = '0';
    }
} else if ($actions == 'submitsingelapplicationdetails') {
    //veryfied app
    $request_body = @file_get_contents('php://input');
    
    $obj = json_decode($request_body);
    file_put_contents('submitsingelapplicationdetails.txt', $request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            $fos_submit_date = date('d-m-Y');
            if ($obj->Payment_Collected_Amount == "") {
                $Payment_Collected_Amount = '0';
            } else {
                $Payment_Collected_Amount = $obj->Payment_Collected_Amount;
            }
            if ($obj->ptp_date == "") {
                $ptp_date = 0;
            } else {
                $ptp_date = $obj->ptp_date;
            }

            if ($obj->fos_completed_status == '1') {
                $Payment_Collected_Date = date('d-m-Y H:i:s');
                $fos_completed = '1';
                $user_Data = array(
                    "fosid" => $row['agencymanagerid'],
                    "applicationid" => $obj->applicationid,
                    "status" => $obj->fos_completed_status,
                    "Payment_Collected_Amount" => $Payment_Collected_Amount,
                    "penal" => $obj->penal ?? 0,
                    "totalamt" => $obj->totalamt ?? 0,
                    "comment" => $obj->fos_comment ?? "",
                    "strEntryDate" => date('d-m-Y H:i:s'),
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $insert = $connect->insertrecord($dbconn, 'applicationpaymentcollectedhistory', $user_Data);
            } else if ($obj->fos_completed_status == '2') {
                $Payment_Collected_Date = "NA";
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '3') {
                //ptpresheduled
                $Payment_Collected_Date = "NA";
                $fos_submit_date = explode('-', $fos_submit_date);
                $ptp_date = explode('-', $obj->ptp_date);

                $date1 = date_create($fos_submit_date[2] . "-" . $fos_submit_date[1] . "-" . $fos_submit_date[0]);
                $date2 = date_create($ptp_date[2] . "-" . $ptp_date[1] . "-" . $ptp_date[0]);

                $diff = date_diff($date1, $date2);
                $days = $diff->format("%a");

                if ($days > 2) {
                    $fos_completed = '3';
                } else {
                    $fos_completed = '2';
                }
                if($days==0){
                    $obj->fos_completed_status=5;  
                }
                //print_r($applicationdetails);
                //exit;
                //  $fos_completed = '2';
            } else if ($obj->fos_completed_status == '4') {
                $Payment_Collected_Date = "";
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '6') {
                $Payment_Collected_Date = "NA";
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '7') {
                $Payment_Collected_Date = "NA";
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '16') {
                $Payment_Collected_Date = "NA";
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '10') {
                $Payment_Collected_Date = date('d-m-Y H:i:s');
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '11') {
                $Payment_Collected_Date = date('d-m-Y H:i:s');
                $fos_completed = '1';
            } else if ($obj->fos_completed_status == '18') {
                // $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
                $Payment_Collected_Date = date('d-m-Y H:i:s');
                $fos_completed = '1';
                $user_Data = array(
                    "fosid" => $row['agencymanagerid'],
                    "applicationid" => $obj->applicationid,
                    "status" => $obj->fos_completed_status,
                    "Payment_Collected_Amount" => $Payment_Collected_Amount,
                    "penal" => $obj->penal,
                    "comment" => $obj->fos_comment,
                    "totalamt" => $obj->totalamt,
                    "strEntryDate" => date('d-m-Y H:i:s'),
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $insert = $connect->insertrecord($dbconn, 'applicationpaymentcollectedhistory', $user_Data);
                
                // $where = " where applicationid = '" . $obj->applicationid . "' ";
                $sqlQry = mysqli_fetch_assoc(mysqli_query($dbconn,"select * from application ".$where.""));
                $obj->totalamt = $obj->totalamt + $sqlQry['totalamt'];
            } 

            if ($obj->fos_completed_status == '1' || $obj->fos_completed_status == '18') {
                $applicationdetails = array(
                    "fos_completed_status" => $obj->fos_completed_status,
                    "fos_completed" => $fos_completed,
                    "fos_comment" => $obj->fos_comment ?? 0,
                    "AlternetMobileNo" => $obj->AlternetMobileNo ?? 0,
                    'ptp_datetime' => $obj->ptp_date ,
                    "fos_submit_datetime" => date('d-m-Y H:i:s'),
                    "Payment_Collected_Amount" => $Payment_Collected_Amount,
                    "penal" => $obj->penal ?? 0,
                    "runsheet" => '0',
                    "totalamt" => $obj->totalamt ?? 0,
                    "Payment_Collected_Date" => $Payment_Collected_Date, //date('d-m-Y H:i:s'),
                    "strIP" => $_SERVER['REMOTE_ADDR'],
                );
                //print_r($applicationdetails);exit;
                $where = " where applicationid = '" . $obj->applicationid . "' ";
                $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
            } else {
                $applicationdetails = array(
                    "fos_completed_status" => $obj->fos_completed_status,
                    "strFosOtherRemark" => $obj->strFosOtherRemark ?? "",
                    "fos_completed" => $fos_completed,
                    "fos_comment" => $obj->fos_comment,
                    "AlternetMobileNo" => $obj->AlternetMobileNo,
                    'ptp_datetime' => $obj->ptp_date,
                    "fos_submit_datetime" => date('d-m-Y H:i:s'),
                    "runsheet" => '0',
                    "strIP" => $_SERVER['REMOTE_ADDR'],
                );
                //print_r($applicationdetails);exit;
                $where = " where applicationid = '" . $obj->applicationid . "' ";
                $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
            }

            $userData = array(
                "fosid" => $obj->loginId,
                "appid" => $obj->applicationid,
                "status" => $obj->fos_completed_status,
                "comment" => $obj->fos_comment,
                "strFosOtherRemark" => $obj->strFosOtherRemark ?? "",
                'ptp_datetime' => $obj->ptp_date,
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $insert = $connect->insertrecord($dbconn, 'foshistory', $userData);
            
            $fosData = array(
                "strFosId" => $obj->loginId,
                "iAgencyManagerid" => $row['agencymanagerid'],
                "strLatitude" => isset($obj->strLatitude) && $obj->strLatitude !="" ? $obj->strLatitude : "",
                "strLongitude" => isset($obj->strLongitude) && $obj->strLongitude != "" ? $obj->strLongitude : "",
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strLocation" => "Status Change Submit Entry",
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $insert = $connect->insertrecord($dbconn, 'fosloginlog', $fosData);

            if ($applicationdetails_res) {
                $output['message'] = 'added sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = ' Password Not match';
            $output["success"] = '0';
        }
    } else {

        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'fosstatusdropdown') {
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            // echo "SELECT * FROM `fosstatusdrropdown`  where isDelete='0'  and  istatus='1'  order by  fosstatusdrropdownid ASC";
            $sql = "SELECT * FROM `fosstatusdrropdown`  where isDelete='0'  and  istatus='1'  order by  fosstatusdrropdownid ASC";
            $result = mysqli_query($dbconn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = array("fosstatusdrropdownid" => '0', "status" => 'Select Status ');
                $output['fosstatus'] [] = array_map('utf8_encode', $data);

                while ($roworderdatails_sql = mysqli_fetch_assoc($result)) {
                    $output['fosstatus'] [] = $roworderdatails_sql;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User not match';
        $output['success'] = '0';
    }
} else if ($actions == 'count') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            //$filterstr = "select *  from application WHERE is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' ";
            // and am_accaptance='1' 
            
            $totalapp = mysqli_query($dbconn, "select count(*) as totalassignapp from application WHERE is_assignto_fos='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' and fos_completed_status='0'");
            $totalappcount = mysqli_fetch_assoc($totalapp);
            $countapp = $totalappcount['totalassignapp'];
           //$countapp = '0';

            //$filterstr = "select *  from application WHERE is_assignto_fos='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='1' ";

            $totalcompapp = mysqli_query($dbconn, "select count(*) as completedapp from application WHERE is_assignto_fos='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed > '0'  and fos_completed_status NOT IN (3,5,17) ");
            $totalcompletedcount = mysqli_fetch_assoc($totalcompapp);
            $countcompletedapp = $totalcompletedcount['completedapp'];

            $total = mysqli_query($dbconn, "select sum(totalamt) as totalPayment_Collected_Amount from application WHERE is_assignto_fos='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed > '0' ");
            $totalCollectedcount = mysqli_fetch_assoc($total);
            if ($totalCollectedcount['totalPayment_Collected_Amount'] == '') {
                $totalCollected = '0';
            } else {
                $totalCollected = $totalCollectedcount['totalPayment_Collected_Amount'];
            }



            $output['totalassignapp'] = $countapp;
            $output['completedapp'] = $countcompletedapp;
            $output['collectedamount'] = $totalCollected;


            $output['message'] = 'sucess';
            $output['success'] = '1';
        } else {
            $output['message'] = 'User or Password not match';
            $output["success"] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'fosdeshbord') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            //$filterstr = "select *  from application WHERE is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' ";

            $totalapp = "SELECT fosstatusdrropdown.fosstatusdrropdownid,fosstatusdrropdown.status ,IFNULL(application1.statuscount,0) AS StatusCount  FROM  `fosstatusdrropdown`  LEFT JOIN (select count(*) as statuscount,application.fos_completed_status from application where application.fosid = '" . $row['agencymanagerid'] . "' group by application.fos_completed_status) as application1 ON fosstatusdrropdown.fosstatusdrropdownid =  application1.fos_completed_status where fosstatusdrropdown.fosstatusdrropdownid not in (3,17) ";
            //group by application1.fos_completed_status,fosstatusdrropdown.fosstatusdrropdownid,fosstatusdrropdown.status  
            $totalapp .="UNION ALL
            select 5,'PTP For-The-Day',count(*)
            from application where application.fosid = '" . $row['agencymanagerid'] . "'
            and  ((STR_TO_DATE(application.PTP_date,'%d-%m-%Y %T') = '".date('Y-m-d')."'
            and application.PTP_date != '' and fos_completed_status=0) or (STR_TO_DATE(application.ptp_datetime,'%d-%m-%Y %T') = '".date('Y-m-d')."'
            and application.ptp_datetime != '' and fos_completed_status=5)) 
            UNION ALL
            select 3,'PTP Reschdule',count(*)
            from application where application.fosid = '" . $row['agencymanagerid'] . "'
            and  ((STR_TO_DATE(application.PTP_date,'%d-%m-%Y %T') > '".date('Y-m-d')."'
            and application.PTP_date != '' and fos_completed_status=0) or (STR_TO_DATE(application.ptp_datetime,'%d-%m-%Y %T') > '".date('Y-m-d')."'
            and application.ptp_datetime != '' and fos_completed_status=3))
            UNION ALL
            select 17,'Broken PTP',count(*)
            from application where application.fosid = '" . $row['agencymanagerid'] . "'
            and  ((STR_TO_DATE(application.PTP_date,'%d-%m-%Y %T') < '".date('Y-m-d')."'
            and application.PTP_date != '' and fos_completed_status=17) or (STR_TO_DATE(application.ptp_datetime,'%d-%m-%Y %T') < '".date('Y-m-d')."'
            and application.ptp_datetime != '' and fos_completed_status=17))
            UNION ALL
            select 0 as 'fosstatusdrropdownid','Pending Count',count(*) as StatusCount from application where  application.fosid = '" . $row['agencymanagerid'] . "'
            and application.fos_completed_status=0
            UNION ALL
            select 20 as 'fosstatusdrropdownid','Rejected Count',count(*) as StatusCount from application where  application.fosid = '2003'
            and application.fos_completed_status=20";
            //echo $totalapp;
            $result1 = mysqli_query($dbconn, $totalapp);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['fosdeshbordcount'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }


            // $output['message'] = 'sucess';
            // $output['success'] = '1';
        } else {
            $output['message'] = 'User or Password not match';
            $output["success"] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'deshbordstatusviselist') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            if($obj->fosstatusdrropdownid=='5'){
                $filterstr = "select *  from application WHERE  runsheet=0 and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  and  ((STR_TO_DATE(PTP_Date,'%d-%m-%Y %H:%i:%s') = '" . date('Y-m-d') . "' and PTP_Date!='' and fos_completed_status IN (0,5)) or (STR_TO_DATE(ptp_datetime,'%d-%m-%Y %H:%i:%s') = '" . date('Y-m-d') . "' and  ptp_datetime!='' and fos_completed_status IN (0,5))) ";
            }else if($obj->fosstatusdrropdownid=='3'){
                $filterstr = "select *  from application WHERE  runsheet=0 and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  and  ((STR_TO_DATE(PTP_Date,'%d-%m-%Y %H:%i:%s') > '" . date('Y-m-d') . "' and PTP_Date!='' and fos_completed_status=0) or (STR_TO_DATE(ptp_datetime,'%d-%m-%Y %H:%i:%s') > '" . date('Y-m-d') . "' and ptp_datetime!='' and fos_completed_status=3)) ";
            }else if($obj->fosstatusdrropdownid=='17'){
                $filterstr = "select *  from application WHERE  runsheet=0 and   is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  and  ((STR_TO_DATE(PTP_Date,'%d-%m-%Y %H:%i:%s') < '" . date('Y-m-d') . "' and PTP_Date!='' and fos_completed_status=17) or (STR_TO_DATE(ptp_datetime,'%d-%m-%Y %H:%i:%s') < '" . date('Y-m-d') . "' and ptp_datetime!='' and fos_completed_status=17)) ";
            }else{
            //$filterstr = "select *  from application WHERE    is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed_status = '" . $obj->fosstatusdrropdownid . "'  ";
            $filterstr = "select *  from application WHERE    is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed_status = '" . $obj->fosstatusdrropdownid . "'  ";
            }
            //echo $filterstr;
            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['deshbordstatusviselist'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'runsheetapplication') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            if ($obj->applicationid == '0') {
                $filterstr = "select *  from application WHERE is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  and runsheet='1' order by runsheetsequnce asc ";
            } else {
                $filterstr = "select *,(select fosstatusdrropdown.status from fosstatusdrropdown WHERE fosstatusdrropdown.fosstatusdrropdownid=application.fos_completed_status)as status  from application WHERE  applicationid='" . $obj->applicationid . "' and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and  runsheet='1'  order by runsheetsequnce asc ";
            }
         //   echo $filterstr;
            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['runsheetapplicationDetail'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'addtorunsheet') {
//veryfied app
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);

    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            $applicationdetails = array(
                "runsheet" => '1',
                "strIP" => $_SERVER['REMOTE_ADDR'],
            );
            $where = " where applicationid = '" . $obj->applicationid . "' ";
            
            $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);

            if ($applicationdetails_res) {
                $output['message'] = 'added sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Added Successfully';
                $output["success"] = '1';
            }
        } else {
            $output['message'] = ' Password Not match';
            $output["success"] = '0';
        }
    } else {

        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'addmultipalrunsheetsequnce') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    //print_r($obj);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            $obj = json_decode($obj->applicationdetails);
            $count = count($obj);
            for ($iCounter = 0; $iCounter < $count; $iCounter++) {

                $applicationdetails = array(
                    "runsheetsequnce" => $obj[$iCounter]->runsheetsequnce,
                    "strIP" => $_SERVER['REMOTE_ADDR'],
                );
                $where = " where applicationid = '" . $obj[$iCounter]->applicationid . "' ";
                $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
            }
            if ($applicationdetails_res) {
                $output['message'] = 'added sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'Password not match';
            $output["success"] = '0';
        }
    } else {
        $output['message'] = 'User or Password not match';
        $output['success'] = '0';
    }
} else if ($actions == 'removefromrunsheet') {
//veryfied app
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);

    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            $applicationdetails = array(
                "runsheet" => '0',
                "strIP" => $_SERVER['REMOTE_ADDR'],
            );
            $where = " where applicationid = '" . $obj->applicationid . "' ";

            $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);

            if ($applicationdetails_res) {
                $output['message'] = 'Remove sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = ' Password Not match';
            $output["success"] = '0';
        }
    } else {

        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'serchapplications') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);

    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            $where = "where 1=1 ";
            // $where = " where Chassis_no = '" . $obj->prefix . $obj->postfix . "' and applicationID = " . $obj->applicationId . "";
            // if ($obj->App_Id != '') {
            //     $where = $where . "and App_Id = '" . $obj->App_Id . "'  ";
            // }
            if ($obj->Customer_Name != '') {
                $where = $where . "and Customer_Name like '" . $obj->Customer_Name . "%'";
            }
            if ($obj->cycle != '') {
                $where = $where . "and cycle like '" . $obj->cycle . "%'";
            }
            if ($obj->Area_Name != '') {
                $where = $where . "and Area_Name like '" . $obj->Area_Name . "%'";
            }
            if ($obj->Pincode != '') {
                $where = $where . "and Pincode='" . $obj->Pincode . "'";
            }
            if ($obj->Bank_Name != '') {
                $where = $where . "and Bank_Name like '" . $obj->Bank_Name . "%'";
            }
            
            if ($obj->iMobile != '') {
                $where = $where . "and Contact_Number like '" . $obj->iMobile . "%'";
            }

            $filterstr = "select *  from application " . $where . " and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  ";

            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {
                    $output['fosassignapplicationDetail'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'addapplicationphotos') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);

    $applicationid = $_REQUEST['applicationid'];

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $appid = "select * from application where applicationid = '" . $applicationid . "'   ";
    $appidresult = mysqli_query($dbconn, $appid);
    $appidrow = mysqli_fetch_array($appidresult);

    $file_name = $appidrow['Account_No'] . '_' . time() . '.' . $ext; //$_FILES['image']['name'];
    //$file_name =  time() . '.' . $ext; //$_FILES['image']['name'];

    $data = array(
        "applicationid" => $applicationid,
        "photo" => $file_name,
        "strEntryDate" => date('d-m-Y H:i:s'),
        "strAddress" => $_REQUEST['strAddress'],
        "strLatitude" => $_REQUEST['strLatitude'],
        "strLongitude" => $_REQUEST['strLongitude'],
        "entryDateTime" => $_REQUEST['entryDateTime'],
        "strIP" => $_SERVER['REMOTE_ADDR']
    );

    $dealer_res = $connect->insertrecord($dbconn, 'applicationphotos', $data);

    $application = array(
        "is_photo_uploaded" => '1'       
    );
    $where = " where applicationid = '" . $applicationid . "' ";

    $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $application, $where);



    $sql = "select strEntryDate from application where applicationid = '" . $applicationid . "'   ";
    $result = mysqli_query($dbconn, $sql);
    $row = mysqli_fetch_array($result);
    $EntrDate = $row['strEntryDate'];

    $arr = explode(' ', $EntrDate);
    $dateArrar = explode('-', $arr[0]);

    if (!file_exists('../Document/' . $dateArrar[2] . "/")) {

        mkdir('../Document/' . $dateArrar[2], 0777, TRUE);
    }
    if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1])) {

        mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1], 0777, TRUE);
    }
    if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0])) {

        mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0], 0777, TRUE);
    }

    if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid . "/")) {

        mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid, 0777, TRUE);
    }

    $target_path = '../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid . "/" . $file_name;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $output['success'] = "0";
        $output['message'] = 'Could not move the image!';
    } else {
        // $output['imageId'] = $dealer_res;
        $output['message'] = 'sucess';
        $output['success'] = '1';
    }
} else if ($actions == 'viewapplicationphotos') {

    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);

    $applicationid = $obj->applicationid;

    //$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    // $appid = "select * from application where applicationid = '" . $applicationid . "'   ";
    // $appidresult = mysqli_query($dbconn, $appid);
    // $appidrow = mysqli_fetch_array($appidresult);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            $sql = "select strEntryDate from application where applicationid = '" . $applicationid . "'   ";
            $result = mysqli_query($dbconn, $sql);
            $row = mysqli_fetch_array($result);
            $EntrDate = $row['strEntryDate'];
        
            $arr = explode(' ', $EntrDate);
            $dateArrar = explode('-', $arr[0]);
        
            if (!file_exists('../Document/' . $dateArrar[2] . "/")) {
        
                mkdir('../Document/' . $dateArrar[2], 0777, TRUE);
            }
            if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1])) {
        
                mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1], 0777, TRUE);
            }
            if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0])) {
        
                mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0], 0777, TRUE);
            }
        
            if (!file_exists('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid . "/")) {
        
                mkdir('../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid, 0777, TRUE);
            }
            
            $appPhotos = "select * from applicationphotos where applicationid='".$applicationid."'";
            $resultPhotos = mysqli_query($dbconn, $appPhotos);
            $data = [];
            while($rowphotos = mysqli_fetch_assoc($resultPhotos)){
                $target_path = $web_url. 'Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid . "/" . $rowphotos['photo'];
                $data[] = array(
                    "applicationphotosid" => $rowphotos['applicationphotosid'],
                    "applicationid" => $rowphotos['applicationid'],
                    "photo" => $target_path,
                    "strEntryDate" => $rowphotos['strEntryDate'],
                    "strAddress" => $rowphotos['strAddress'],
                    "strLatitude" => $rowphotos['strLatitude'],
                    "strLongitude" => $rowphotos['strLongitude'],
                    "entryDateTime" => $rowphotos['entryDateTime'],
                );
            }
            
            if (!empty($data)) {
                $output['viewapplicationphotos'] = $data;
                $output['success'] = "1";
                $output['message'] = 'Data Found';
            } else {
                // $output['imageId'] = $dealer_res;
                $output['message'] = 'No Data Found';
                $output['success'] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if ($actions == 'deleteapplicationphotos') {
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $applicationid = $obj->applicationid;

    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
        if (validate_password($obj->password, $good_hash)) {
            $sql = "select strEntryDate from application where applicationid = '" . $applicationid . "'   ";
            $result = mysqli_query($dbconn, $sql);
            $row = mysqli_fetch_array($result);
            $EntrDate = $row['strEntryDate'];
        
            $arr = explode(' ', $EntrDate);
            $dateArrar = explode('-', $arr[0]);
            
            $appPhotos = "select * from applicationphotos where applicationid='".$applicationid."' and applicationphotosid='".$obj->applicationphotosid."'";
            $resultPhotos = mysqli_query($dbconn, $appPhotos);
            
            $rowphotos = mysqli_fetch_assoc($resultPhotos);
            $target_path =  '../Document/' . $dateArrar[2] . "/" . $dateArrar[1] . "/" . $dateArrar[0] . "/" . $applicationid . "/" . $rowphotos['photo'];
            unlink($target_path);
                
            $appPhoto = "delete from applicationphotos where applicationid='".$applicationid."' and applicationphotosid='".$obj->applicationphotosid."'";
            $resultPhoto = mysqli_query($dbconn, $appPhoto);
            if ($resultPhoto) {
                $output['success'] = "1";
                $output['message'] = 'success';
            } else {
                $output['message'] = 'Invalid Request';
                $output['success'] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} 
else if($actions == "foshistory"){
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {

            if ($obj->applicationid != '0' || $obj->applicationid != '') {
                $filterstr = "select *,(select status from fosstatusdrropdown where fosstatusdrropdown.fosstatusdrropdownid=foshistory.status) as fosStatus from foshistory WHERE appid='".$obj->applicationid."' order by foshistoryid desc";
                //is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "' and fos_completed='0' and runsheet='0' and (PTP_Date is null or ptp_datetime ='') ORDER BY STR_TO_DATE(PTP_Date,'%d-%m-%Y') ASC";
            } else{
                $output['message'] = 'Application ID Not  Found';
                $output['success'] = '0';
            }
            // else {
            //     $filterstr = "select *,(select fosstatusdrropdown.status from fosstatusdrropdown WHERE fosstatusdrropdown.fosstatusdrropdownid=application.fos_completed_status)as status  from application WHERE  applicationid='" . $obj->applicationid . "' and  is_assignto_fos='1' and am_accaptance='1' and fosid = '" . $row['agencymanagerid'] . "'  ORDER BY STR_TO_DATE(PTP_Date,'%d-%m-%Y') ASC  ";
            // }
            $result1 = mysqli_query($dbconn, $filterstr);
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_assoc($result1)) {

                    $output['fosassignapplicationDetail'] [] = $row;
                }
                $output['message'] = 'Data Found';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
} else if($actions == "foslog"){
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            $fosData = array(
                "strFosId" => $obj->loginId,
                "iAgencyManagerid" => $row['agencymanagerid'],
                "strLatitude" => isset($obj->strLatitude) && $obj->strLatitude !="" ? $obj->strLatitude : "",
                "strLongitude" => isset($obj->strLongitude) && $obj->strLongitude != "" ? $obj->strLongitude : "",
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strLocation" => "FOS Auto Log",
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $insert = $connect->insertrecord($dbconn, 'fosloginlog', $fosData);

            if ($insert) {
                $output['message'] = 'added sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
}
else if($actions == "addtorejectedcase"){
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            $applicationdetails = array(
                "fos_completed_status" => 20,
                "strFosOtherRemark" => $obj->strFosOtherRemark ?? "",
                "fos_completed" => 0,
                //"fos_comment" => $obj->fos_comment,
                //"AlternetMobileNo" => $obj->AlternetMobileNo,
                //'ptp_datetime' => $obj->ptp_date,
                "fos_submit_datetime" => date('d-m-Y H:i:s'),
                "runsheet" => '0',
                "strIP" => $_SERVER['REMOTE_ADDR'],
            );
            //print_r($applicationdetails);exit;
            $where = " where applicationid = '" . $obj->applicationid . "' ";
            $applicationdetails_res = $connect->updaterecord($dbconn, 'application', $applicationdetails, $where);
            
            $userData = array(
                "fosid" => $obj->loginId,
                "appid" => $obj->applicationid,
                "status" => 20,
                //"comment" => $obj->fos_comment,
                "strFosOtherRemark" => $obj->strFosOtherRemark ?? "",
                //'ptp_datetime' => $obj->ptp_date,
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $insert = $connect->insertrecord($dbconn, 'foshistory', $userData);
            
            if ($insert) {
                $output['message'] = 'Case Rejected Sucessfully';
                $output['success'] = '1';
            } else {
                $output['message'] = 'Not Data Found';
                $output["success"] = '0';
            }
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
}
else if($actions == "rejectedcasedropdown"){
    $request_body = @file_get_contents('php://input');
    $obj = json_decode($request_body);
    $md5Has = md5($obj->password);
    $sql = "select * from agencymanager where loginId = '" . $obj->loginId . "' and isDelete='0' and type='FOS' ";
    $result = mysqli_query($dbconn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result);
        $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];

        if (validate_password($obj->password, $good_hash)) {
            
            $data = array(
                /*array(
                    "id" => "",
                    "value" => "Select Rejected Reason",
                ),*/
                array(
                    "id" => "Short Address",
                    "value" => "Short Address",
                ),
                array(
                    "id" => "Long Distance",
                    "value" => "Long Distance",
                ),
                array(
                    "id" => "Other",
                    "value" => "Other",
                ),
            );
            
            $output['rejectedcasedropdown'] = $data;
            $output['message'] = 'Rejected Case Dropdown';
            $output['success'] = '1';
            
        } else {
            $output['message'] = 'User or Password not match';
            $output['success'] = '0';
        }
    } else {
        $output['message'] = 'User  not match';
        $output['success'] = '0';
    }
}
//print(json_encode($output));
$json = json_encode(utf8ize($output));
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Encode Error: " . json_last_error_msg());
}
print($json);

function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } elseif (is_string($mixed)) {
        // If it's not already UTF-8, convert it.
        if (!mb_detect_encoding($mixed, 'utf-8', true)) {
            return mb_convert_encoding($mixed, 'UTF-8');
        }
    }
    return $mixed;
}
?>
