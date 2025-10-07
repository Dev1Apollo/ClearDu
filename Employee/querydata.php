<?php

ob_start();
error_reporting(E_ALL);
include('../common.php');
$connect = new connect();
include 'IsLogin.php';
include 'password_hash.php';
require '../PHPMailer-master/PHPMailerAutoload.php';


$action = $_REQUEST['action'];

switch ($action) {

    case "UserProfileChangePassword":

        if ($_SESSION['Type'] == 'Central Manager') {

            $hash_result = create_hash($_POST['oldpassword']);
            $hash_params = explode(":", $hash_result);
            $salt = $hash_params[HASH_SALT_INDEX];
            $hash = $hash_params[HASH_PBKDF2_INDEX];
            $existsmail = "SELECT * FROM centralmanager where centralmanagerid ='" . $_SESSION['centralmanagerid'] . "'";
            $result = mysqli_query($dbconn, $existsmail);
            $num_rows = mysqli_num_rows($result);
            $row = mysqli_fetch_array($result);

            if ($num_rows >= 1) {
                $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
                $oldpassword = mysqli_real_escape_string($_REQUEST['oldpassword']);
                if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                    $hash_result = create_hash($_REQUEST['password']);
                    $hash_params = explode(":", $hash_result);
                    $salt = $hash_params[HASH_SALT_INDEX];
                    $hash = $hash_params[HASH_PBKDF2_INDEX];
                    $getItems1 = mysqli_query($dbconn, "update centralmanager SET password = '" . $hash . "', salt = '" . $salt . "' where centralmanagerid='" . $_SESSION['centralmanagerid'] . "'");
                    echo "Sucess";
                } else {
                    echo "OldNot";
                }
            } else {
                echo "ID not found";
            }
        } else if ($_SESSION['Type'] == 'Agency Manager') {

            $hash_result = create_hash($_POST['oldpassword']);
            $hash_params = explode(":", $hash_result);
            $salt = $hash_params[HASH_SALT_INDEX];
            $hash = $hash_params[HASH_PBKDF2_INDEX];
            $existsmail = "SELECT * FROM agencymanager where agencymanagerid='" . $_SESSION['agencymanagerid'] . "'";
            $result = mysqli_query($dbconn, $existsmail);
            $num_rows = mysqli_num_rows($result);
            $row = mysqli_fetch_array($result);

            if ($num_rows >= 1) {
                $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
                $oldpassword = mysqli_real_escape_string($_REQUEST['oldpassword']);
                if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                    $hash_result = create_hash($_REQUEST['password']);
                    $hash_params = explode(":", $hash_result);
                    $salt = $hash_params[HASH_SALT_INDEX];
                    $hash = $hash_params[HASH_PBKDF2_INDEX];
                    $getItems1 = mysqli_query($dbconn, "update agencymanager SET password = '" . $hash . "', salt = '" . $salt . "' where agencymanagerid='" . $_SESSION['agencymanagerid'] . "'");
                    echo "Sucess";
                } else {
                    echo "OldNot";
                }
            } else {
                echo "ID not found";
            }
        } else if ($_SESSION['Type'] == 'Agency supervisor') {

            $hash_result = create_hash($_POST['oldpassword']);
            $hash_params = explode(":", $hash_result);
            $salt = $hash_params[HASH_SALT_INDEX];
            $hash = $hash_params[HASH_PBKDF2_INDEX];
            $existsmail = "SELECT * FROM agencymanager where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "'";
            $result = mysqli_query($dbconn, $existsmail);
            $num_rows = mysqli_num_rows($result);
            $row = mysqli_fetch_array($result);

            if ($num_rows >= 1) {
                $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
                $oldpassword = mysqli_real_escape_string($_REQUEST['oldpassword']);
                if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                    $hash_result = create_hash($_REQUEST['password']);
                    $hash_params = explode(":", $hash_result);
                    $salt = $hash_params[HASH_SALT_INDEX];
                    $hash = $hash_params[HASH_PBKDF2_INDEX];
                    // echo "update agencymanager SET password = '" . $hash . "', salt = '" . $salt . "' where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "'";
                    $getItems1 = mysqli_query("update agencymanager SET password = '" . $hash . "', salt = '" . $salt . "' where agencymanagerid='" . $_SESSION['agencysupervisorid'] . "'");
                    echo "Sucess";
                } else {
                    echo "OldNot";
                }
            } else {
                echo "ID not found";
            }
        } else if ($_SESSION['Type'] == 'Company Employee') {

            $hash_result = create_hash($_POST['oldpassword']);
            $hash_params = explode(":", $hash_result);
            $salt = $hash_params[HASH_SALT_INDEX];
            $hash = $hash_params[HASH_PBKDF2_INDEX];
            $existsmail = "SELECT * FROM companyemployee where companyemployeeid='" . $_SESSION['companyemployeeid'] . "'";
            $result = mysqli_query($dbconn, $existsmail);
            $num_rows = mysqli_num_rows($result);
            $row = mysqli_fetch_array($result);

            if ($num_rows >= 1) {
                $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
                $oldpassword = mysqli_real_escape_string($_REQUEST['oldpassword']);
                if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                    $hash_result = create_hash($_REQUEST['password']);
                    $hash_params = explode(":", $hash_result);
                    $salt = $hash_params[HASH_SALT_INDEX];
                    $hash = $hash_params[HASH_PBKDF2_INDEX];
                    $getItems1 = mysqli_query($dbconn, "update companyemployee SET password = '" . $hash . "', salt = '" . $salt . "' where companyemployeeid='" . $_SESSION['companyemployeeid'] . "'");
                    echo "Sucess";
                } else {
                    echo "OldNot";
                }
            } else {
                echo "ID not found";
            }
        }

        break;


    case "Addagencymanager":

        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "agencyname" => $_POST['Agency'],
            "employeename" => $_POST['employeename'],
            "type" => $_POST['Type'],
            "address" => $_POST['address'],
            "loginId" => $_POST['LoginID'],
            "password" => $hash,
            "salt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'agencymanager', $data);
        if ($_POST['Type'] == 'Agency Manager') {
            mysqli_query($dbconn, "INSERT INTO `agencymanagerlocation`(`iagencymanagerid`, `iLocationId`, `stateId`, `districtId`) SELECT $dealer_res,locationId,stateId,districtId FROM location ");
        }
//       $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(iagencymanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }
        echo $dealer_res;
        break;
    case "EditAgencymanager":
        $data = array(
            "agencyname" => $_POST['Agency'],
            "employeename" => $_POST['employeename'],
            "type" => $_POST['Type'],
            "address" => $_POST['address'],
            "loginId" => $_POST['LoginID'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  agencymanagerid=' . $_REQUEST['agencymanagerid'];
        $dealer_res = $connect->updaterecord($dbconn, 'agencymanager', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';

//        $sql_res = mysqli_query($dbconn,"delete from agencymanagerlocation where  iagencymanagerid= " . $_REQUEST['agencymanagerid'] . " ");
//        
//            $resultLocation = mysqli_query($dbconn,"SELECT * FROM `location`  where isDelete='0'  and  istatus='1'");
//        while ($rowC = mysqli_fetch_array($resultLocation)) {
//            if (isset($_POST['Location' . $rowC['locationId']]))
//                mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(iagencymanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $_REQUEST['agencymanagerid'] . "','" . $rowC['locationId'] . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }

        break;
    case "agancyuserlocationstatedistrict":
//        
        //  print_r($_POST);
        //     exit;
        $sql_res = mysqli_query($dbconn, "delete from agencymanagerlocation where  iagencymanagerid = " . $_REQUEST['agencymanagerid'] . " and stateId = " . $_POST['State'] . "  ");
        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            $data = array(
                "iagencymanagerid" => $_POST['agencymanagerid'],
                "iLocationId" => $value,
                "stateId" => $_POST['State'],
                "districtId" => 0,
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'agencymanagerlocation', $data);
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;



    case "Editreturndate":
        // customer_city_id
        // customer_city
        // $queryCom = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM  location WHERE locationId='" . $_POST['customer_city'] . "'"));
        $data = array(
            "excel_return_date" => $_POST['returndate'],
        );
        $where = ' where  applicationid = ' . $_REQUEST['applicationid'];
        $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);


        echo $statusMsg = $dealer_res ? '2' : '0';

        break;







    case "cmpendingapplication":


        //assign to agency manager

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_am" => '1',
                "assign_am_datetime" => date('d-m-Y H:i:s'),
                //"agencymanagerid" => $_POST['agencymanagerid'],
                "agencyid" => $_POST['agency'],
                "am_accaptance" => '1',
            );
            $where = ' where applicationid =' . trim($value);
            //$update = $connect->updaterecord($dbconn, 'ledger', $where);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';


            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['centralmanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'cm assign to agency',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }

        break;

    case "cmwithdrawcase":

        //cm withdraw case
        $applicationids;
        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "am_accaptance" => '2',
                "fosid" => '0',
                "is_assignto_fos" => '0',
                "withdraw_date" => date('d-m-Y'),
                "fos_completed_status" => '0',
                "fos_completed" => '0',
            );
            $where = ' where applicationid =' . trim($value)." and am_accaptance IN ('1','0') and fos_completed_status NOT IN ('1','12','13','14','15')";
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            $applicationids = $value . ',' . $applicationids;

            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['centralmanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'Withdraw By Central Team',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }
        $currentmonth = date('d-m-Y');
        $applicationids = rtrim($applicationids, ", ");



        $filename = 'casewithdrawnbycentralteam.xls';
        $output = fopen($filename, 'w');
        $str_filedata = '';
        $str_filedata_head = '';
        $str_filedata_head .=
                "SrNo"
//                        . "\t  Allocation Date"
//                        . "\t  Supervisor Assigned Date Time"
//                        . "\t  unique Id"
//                        . "\t  Account No"
//                        . "\t  Agency Name"
//                        . "\t  PRODUCT"
                . "\t  App Id"
//                        . "\t  Bkt"
//                        . "\t  Customer Name"
//                        . "\t  Fathers name"
//                        . "\t  Asset Make"
//                        . "\t  Branch"
//                        . "\t  Customer City"
//                        . "\t  State"
//                        . "\t  Due Month"
//                        . "\t  Allocation Date"
//                        . "\t  Allocation CODE"
//                        . "\t  Bounce Reason"
//                        . "\t  Loan amount"
//                        . "\t  Loan booking Date"
//                        . "\t  Loan maturity date"
//                        . "\t  Frist Emi Date"
//                        . "\t  Due date"
//                        . "\t  Emi Amount Collected"
//                        . "\t  Installment_Overdue_Amount"
//                        . "\t  Bcc"
//                        . "\t  Lpp"
//                        . "\t  Total penlty"
//                        . "\t  Principal outstanding"
//                        . "\t  Vehicle Registration No"
//                        . "\t  Supplier"
//                        . "\t  Tenure"
//                        . "\t  Customer Address"
//                        . "\t  Contact Number"
//                        . "\t  Collection Manager"
//                        . "\t  State Manager "
//                        . "\t  Ref_1_Name"
//                        . "\t  Contact_Detail"
//                        . "\t  Ref_2_Name"
//                        . "\t  Contact_Detail_ref2"
//                        . "\t  Fos Name"
//                        . "\t  Fos Id"
//                        . "\t  Fos Comment"
//                        . "\t  Fos Status"
//                        . "\t  Payment Collected Date"
//                        . "\t  Payment Collected Amount"
//                        . "\t  Penal Amount Collected"
//                        . "\t  Total  Amount Collected"
//                        . "\t  ptp Re-schedule date"
//                        . "\t  PTP Date"
//                        . "\t  PTP Amount"
//                        . "\t  Time Slot"
                . "\n";
        fwrite($output, $str_filedata_head);
        $i = 1;
        $freshal = mysqli_query($dbconn, "SELECT * FROM application where applicationid in(" . $applicationids . ") ");
        while ($row1 = mysqli_fetch_array($freshal)) {

            $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
            $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
            $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));


            $str_filedata .=
                    $i
//                            . "\t" . $row1['strEntryDate']
//                            . "\t" . $row1['assign_as_datetime']
//                            . "\t" . $row1['uniqueId']
//                            . "\t" . $row1['Account_No']
//                            . "\t" . $asname['agencyname']
//                            . "\t" . $row1['PRODUCT']
                    . "\t" . $row1['App_Id']
//                            . "\t" . $row1['Bkt']
//                            . "\t" . $row1['Customer_Name']
//                            . "\t" . $row1['Fathers_name']
//                            . "\t" . $row1['Asset_Make']
//                            . "\t" . $row1['Branch']
//                            . "\t" . $row1['customer_city']
//                            . "\t" . $row1['State']
//                            . "\t" . $row1['Due_Month']
//                            . "\t" . $row1['Allocation_Date']
//                            . "\t" . $row1['Allocation_CODE']
//                            . "\t" . $row1['Bounce_Reason']
//                            . "\t" . $row1['Loan_amount']
//                            . "\t" . $row1['Loan_booking_Date']
//                            . "\t" . $row1['Loan_maturity_date']
//                            . "\t" . $row1['Frist_Emi_Date']
//                            . "\t" . $row1['Due_date']
//                            . "\t" . $row1['Emi_amount']
//                            . "\t" . $row1['Installment_Overdue_Amount']
//                            . "\t" . $row1['Bcc']
//                            . "\t" . $row1['Lpp']
//                            . "\t" . $row1['Total_penlty']
//                            . "\t" . $row1['Principal_outstanding']
//                            . "\t" . $row1['Vehicle_Registration_No']
//                            . "\t" . $row1['Supplier']
//                            . "\t" . $row1['Tenure']
//                            . "\t" . $connect->RemoveSpecialChapr($row1['Customer_Address'])
//                            . "\t" . $row1['Contact_Number']
//                            . "\t" . $row1['Collection_Manager']
//                            . "\t" . $connect->RemoveSpecialChapr($row1['State_Manager'])
//                            . "\t" . $connect->RemoveSpecialChapr($row1['Ref_1_Name'])
//                            . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail'])
//                            . "\t" . $connect->RemoveSpecialChapr($row1['Ref_2_Name'])
//                            . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail_ref2'])
//                            . "\t" . $fos['employeename']
//                            . "\t" . $fos['loginId']
//                            . "\t" . $connect->RemoveSpecialChapr($row1['fos_comment'])
//                            . "\t" . $status['status']
//                            . "\t" . $row1['Payment_Collected_Date']
//                            . "\t" . $row1['Payment_Collected_Amount']
//                            . "\t" . $row1['penal']
//                            . "\t" . $row1['totalamt']
//                            . "\t" . $row1['ptp_datetime']
//                            . "\t" . $row1['PTP_Date']
//                            . "\t" . $row1['PTP_Amount']
//                            . "\t" . $row1['Time_Slot']
                    . "\n";
            $i++;
        }

        $AgencyReturnCases = mysqli_query($dbconn, "SELECT * FROM application where applicationid in(" . $applicationids . ")  GROUP by agencyid");
        while ($row = mysqli_fetch_array($AgencyReturnCases)) {

            //$TotalWithdrawnCount = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as TotalWithdrawnCount FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and applicationid in(" . $applicationids . ")"));
            $Totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as Totalbktx FROM `application` WHERE (Bkt='x' or Bkt='0')  and  agencyid='" . $row['agencyid'] . "' and applicationid in(" . $applicationids . ")"));
            $Totalbkt1 = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as Totalbkt1 FROM `application` WHERE Bkt='1'  and  agencyid='" . $row['agencyid'] . "' and am_accaptance= '2' and applicationid in(" . $applicationids . ")"));
            $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $row['agencyid'] . "' "));

            $mailFormat = file_get_contents("agencywithdrawncases.html");
            $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);
            //$mailFormat = str_replace("#TotalWithdrawnCount#", ucfirst(urldecode($TotalWithdrawnCount['TotalWithdrawnCount'])), $mailFormat);
            $mailFormat = str_replace("#Totalbktx#", ucfirst(urldecode($Totalbktx['Totalbktx'])), $mailFormat);
            $mailFormat = str_replace("#Totalbkt1#", ucfirst(urldecode($Totalbkt1['Totalbkt1'])), $mailFormat);
            $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);

            $mailTR = file_get_contents("agencywithdrawncases_tr.html");
            $TrValueToReplace = "";
            $branch = mysqli_query($dbconn, "SELECT Branch  FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  applicationid in(" . $applicationids . ") GROUP by Branch");
            while ($rowbranch = mysqli_fetch_array($branch)) {
                $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $row['agencyid'] . "' and (Bkt='x' or Bkt='0')  and applicationid in(" . $applicationids . ") and Branch = '" . $rowbranch['Branch'] . "'  "));
                $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  Bkt='1'  and applicationid in(" . $applicationids . ") and Branch = '" . $rowbranch['Branch'] . "' "));
                $trValueToChaneg = $mailTR;

                $trValueToChaneg = str_replace("#branchName#", ucfirst(urldecode($rowbranch['Branch'])), $trValueToChaneg);
                $trValueToChaneg = str_replace("#bktx#", ucfirst(urldecode($bktx['bktx'])), $trValueToChaneg);
                $trValueToChaneg = str_replace("#bkt1#", ucfirst(urldecode($bkt1['bkt1'])), $trValueToChaneg);

                $TrValueToReplace = $TrValueToReplace . $trValueToChaneg;
            }
            $mailFormat = str_replace("#branchtr#", $TrValueToReplace, $mailFormat);
            fwrite($output, $str_filedata);
            fclose($output);

            $connect->sendmultimail($mailFormat, $foremail['emailto'], $foremail['frommail'], $foremail['fromePassword'], $sub = 'Case withdrawn By Central Team -' . $foremail['agencyname'], $filename, $foremail['cc'], '');
        }


        echo $statusMsg = $dealer_res ? '1' : '0';

        break;

    case "cmviewwithdrawcase":


        //cm withdraw case

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_am" => '1',
                "assign_am_datetime" => date('d-m-Y H:i:s'),
                // "agencymanagerid" => $_POST['agencymanagerid'],
                "agencyid" => $_POST['agency'],
                "am_accaptance" => '1',
            );
            $where = ' where applicationid =' . trim($value);
            //$update = $connect->updaterecord($dbconn, 'ledger', $where);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';



            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['centralmanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'Reassign withdraw case By Central Team',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }

        break;

    case "cmreassignreturncase":

        //    print_r($_POST);
        //    exit;

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_am" => '1',
                "assign_am_datetime" => date('d-m-Y H:i:s'),
                "assign_as_datetime" => date('d-m-Y H:i:s'),
                "agencyid" => $_POST['agency'],
                "am_accaptance" => '1',
                "fosid" => '0',
                "is_assignto_fos" => '0',
                "fos_completed_status" => '0',
            );
            $where = ' where applicationid =' . trim($value);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';



            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['centralmanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'Reassign Return Case By Central Team',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }


        break;


    case "cmptpreassign":


        //assign to agency manager

        $CheckList = $_POST['check_list'];

        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_am" => '1',
                "assign_am_datetime" => date('d-m-Y H:i:s'),
                //"agencymanagerid" => $_POST['agencymanagerid'],
                "agencyid" => $_POST['agency'],
                "am_accaptance" => '1',
                "is_assignto_as" => '1',
                "agencysupervisorid" => '0',
                "is_assignto_fos" => '0',
                "assign_fos_datetime" => '',
                "fosid" => '0',
                "fos_completed" => '0',
                "fos_completed_status" => '0',
                "fos_comment" => '',
                "ptp_datetime" => '',
                "fos_submit_datetime" => '',
            );


            $where = ' where applicationid =' . trim($value);

            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';


            $data1 = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['centralmanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'cm PTP Re-Assign',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data1);
        }

        break;



    case "amassigntoas":


        //cm withdraw case

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_as" => '1',
                "assign_as_datetime" => date('d-m-Y H:i:s'),
                "agencysupervisorid" => $_POST['agencysupervisorid'],
                    //"am_accaptance"=>'1',
            );
            $where = ' where applicationid =' . trim($value);
            //$update = $connect->updaterecord($dbconn, 'ledger', $where);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';



            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['agencymanagerid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'am assign to as',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }



        break;


    case "asreturncases":

        $CheckList = $_POST['check_list'];
        $applicationids;
        foreach ($CheckList as $key => $value) {
            $lanappbkt1 = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `application`  where  applicationid='" . $value . "'  "));
            //   if ($lanappbkt1['Bkt'] == 1) {
            $currentdate = date('d-m-Y');
            if (strtotime($currentdate) > strtotime($lanappbkt1['excel_return_date'])) {
                echo 'Return Restricted for ' . $lanappbkt1['App_Id'] . '<br/>';
            } else {
                $data = array(
                    "am_accaptance" => '3',
                    "is_assignto_fos" => '0',
                    "fos_completed_status" => '0',
                    "fos_completed" => '0',
                    "fosid" => '0',
                    "return_date" => date('d-m-Y'),
                );
                $where = ' where applicationid = ' . trim($value)." AND am_accaptance in ('1','0')";
                $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
                $applicationids = $value . ',' . $applicationids;
                $data1 = array(
                    "app_id" => $value,
                    "emp_id" => $_SESSION['agencysupervisorid'],
                    "emp_type" => $_SESSION['Type'],
                    "action_name" => 'Return By Agency supervisor',
                    "strEntryDate" => date('d-m-Y H:i:s'),
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data1);
            }
//            } else if ($lanappbkt1['Bkt'] == 'x' || $lanappbkt1['Bkt'] == '0' || $lanappbkt1['Bkt'] == 'X') {
//                $currentdate = date('d');
//                if ($currentdate > 18) {
//                    echo 'Return Restricted for ' . $lanappbkt1['App_Id'] . + \n . '\n<br/>';
//                } else {
//                    $data = array(
//                        "am_accaptance" => '3',
//                        "is_assignto_fos" => '0',
//                        "fosid" => '0',
//                        "return_date" => date('d-m-Y'),
//                    );
//                    $where = ' where applicationid = ' . trim($value);
//                    $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
//                    $applicationids = $value . ',' . $applicationids;
//                    $data1 = array(
//                        "app_id" => $value,
//                        "emp_id" => $_SESSION['agencysupervisorid'],
//                        "emp_type" => $_SESSION['Type'],
//                        "action_name" => 'Return By Agency supervisor',
//                        "strEntryDate" => date('d-m-Y H:i:s'),
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                    $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data1);
//                }
//            }

            echo $statusMsg = $dealer_res ? 'sucess' : 'fail';
        }
        $applicationids = rtrim($applicationids, ", ");
        $currentmonth = date('d-m-Y');




        $filename = 'freshallocationuploaded.xls';
        $output = fopen($filename, 'w');
        $str_filedata = '';
        $str_filedata_head = '';
        $str_filedata_head .=
                "SrNo"
//                        . "\t  Allocation Date"
//                        . "\t  Supervisor Assigned Date Time"
//                        . "\t  unique Id"
//                        . "\t  Account No"
                . "\t  Agency Name"
//                        . "\t  PRODUCT"
                . "\t  App Id"
//                        . "\t  Bkt"
//                        . "\t  Customer Name"
//                        . "\t  Fathers name"
//                        . "\t  Asset Make"
//                        . "\t  Branch"
//                        . "\t  Customer City"
//                        . "\t  State"
//                        . "\t  Due Month"
//                        . "\t  Allocation Date"
//                        . "\t  Allocation CODE"
//                        . "\t  Bounce Reason"
//                        . "\t  Loan amount"
//                        . "\t  Loan booking Date"
//                        . "\t  Loan maturity date"
//                        . "\t  Frist Emi Date"
//                        . "\t  Due date"
//                        . "\t  Emi Amount Collected"
//                        . "\t  Installment_Overdue_Amount"
//                        . "\t  Bcc"
//                        . "\t  Lpp"
//                        . "\t  Total penlty"
//                        . "\t  Principal outstanding"
//                        . "\t  Vehicle Registration No"
//                        . "\t  Supplier"
//                        . "\t  Tenure"
//                        . "\t  Customer Address"
//                        . "\t  Contact Number"
//                        . "\t  Collection Manager"
//                        . "\t  State Manager "
//                        . "\t  Ref_1_Name"
//                        . "\t  Contact_Detail"
//                        . "\t  Ref_2_Name"
//                        . "\t  Contact_Detail_ref2"
//                        . "\t  Fos Name"
//                        . "\t  Fos Id"
//                        . "\t  Fos Comment"
//                        . "\t  Fos Status"
//                        . "\t  Payment Collected Date"
//                        . "\t  Payment Collected Amount"
//                        . "\t  Penal Amount Collected"
//                        . "\t  Total  Amount Collected"
//                        . "\t  ptp Re-schedule date"
//                        . "\t  PTP Date"
//                        . "\t  PTP Amount"
//                        . "\t  Time Slot"
                . "\n";
        fwrite($output, $str_filedata_head);
        $i = 1;
        $freshal = mysqli_query($dbconn, "SELECT * FROM application where applicationid in(" . $applicationids . ") AND am_accaptance in ('1','0')");
        while ($row1 = mysqli_fetch_array($freshal)) {

            $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $row1['agencyid'] . "' "));
            $status = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `fosstatusdrropdown`  where fosstatusdrropdownid ='" . $row1['fos_completed_status'] . "'"));
            $fos = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager`  where agencymanagerid ='" . $row1['fosid'] . "'"));


            $str_filedata .=
                    $i
//                                . "\t" . $row1['strEntryDate']
//                                . "\t" . $row1['assign_as_datetime']
//                                . "\t" . $row1['uniqueId']
//                                . "\t" . $row1['Account_No']
                    . "\t" . $asname['agencyname']
//                                . "\t" . $row1['PRODUCT']
                    . "\t" . $row1['App_Id']
//                                . "\t" . $row1['Bkt']
//                                . "\t" . $row1['Customer_Name']
//                                . "\t" . $row1['Fathers_name']
//                                . "\t" . $row1['Asset_Make']
//                                . "\t" . $row1['Branch']
//                                . "\t" . $row1['customer_city']
//                                . "\t" . $row1['State']
//                                . "\t" . $row1['Due_Month']
//                                . "\t" . $row1['Allocation_Date']
//                                . "\t" . $row1['Allocation_CODE']
//                                . "\t" . $row1['Bounce_Reason']
//                                . "\t" . $row1['Loan_amount']
//                                . "\t" . $row1['Loan_booking_Date']
//                                . "\t" . $row1['Loan_maturity_date']
//                                . "\t" . $row1['Frist_Emi_Date']
//                                . "\t" . $row1['Due_date']
//                                . "\t" . $row1['Emi_amount']
//                                . "\t" . $row1['Installment_Overdue_Amount']
//                                . "\t" . $row1['Bcc']
//                                . "\t" . $row1['Lpp']
//                                . "\t" . $row1['Total_penlty']
//                                . "\t" . $row1['Principal_outstanding']
//                                . "\t" . $row1['Vehicle_Registration_No']
//                                . "\t" . $row1['Supplier']
//                                . "\t" . $row1['Tenure']
//                                . "\t" . $connect->RemoveSpecialChapr($row1['Customer_Address'])
//                                . "\t" . $row1['Contact_Number']
//                                . "\t" . $row1['Collection_Manager']
//                                . "\t" . $connect->RemoveSpecialChapr($row1['State_Manager'])
//                                . "\t" . $connect->RemoveSpecialChapr($row1['Ref_1_Name'])
//                                . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail'])
//                                . "\t" . $connect->RemoveSpecialChapr($row1['Ref_2_Name'])
//                                . "\t" . $connect->RemoveSpecialChapr($row1['Contact_Detail_ref2'])
//                                . "\t" . $fos['employeename']
//                                . "\t" . $fos['loginId']
//                                . "\t" . $connect->RemoveSpecialChapr($row1['fos_comment'])
//                                . "\t" . $status['status']
//                                . "\t" . $row1['Payment_Collected_Date']
//                                . "\t" . $row1['Payment_Collected_Amount']
//                                . "\t" . $row1['penal']
//                                . "\t" . $row1['totalamt']
//                                . "\t" . $row1['ptp_datetime']
//                                . "\t" . $row1['PTP_Date']
//                                . "\t" . $row1['PTP_Amount']
//                                . "\t" . $row1['Time_Slot']
                    . "\n";
            $i++;
        }


        $AgencyReturnCases = mysqli_query($dbconn, "SELECT * FROM application where applicationid in(" . $applicationids . ")  GROUP by agencyid");
        while ($row = mysqli_fetch_array($AgencyReturnCases)) {

            //  $totalreturn = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalreturn FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and applicationid in(" . $applicationids . ")"));
            $totalbktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) astotal bktx FROM `application` WHERE (Bkt='x' or Bkt='0')  and  agencyid='" . $row['agencyid'] . "' and applicationid in(" . $applicationids . ")"));
            $totalbkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as totalbkt1 FROM `application` WHERE Bkt='1'  and  agencyid='" . $row['agencyid'] . "' and applicationid in(" . $applicationids . ")"));
            $foremail = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT *  FROM agency WHERE  Agencyid='" . $row['agencyid'] . "' "));

            $mailFormat = file_get_contents("agencyreturncases.html");
            $mailFormat = str_replace("#agencyname#", ucfirst(urldecode($foremail['agencyname'])), $mailFormat);
            // $mailFormat = str_replace("#totalreturn#", ucfirst(urldecode($totalreturn['totalreturn'])), $mailFormat);
            $mailFormat = str_replace("#totalbktx#", ucfirst(urldecode($totalbktx['totalbktx'])), $mailFormat);
            $mailFormat = str_replace("#totalbkt1#", ucfirst(urldecode($totalbkt1['totalbkt1'])), $mailFormat);
            $mailFormat = str_replace("#date#", date('d-m-Y H:i:s'), $mailFormat);

            $mailTR = file_get_contents("agencyreturncases_tr.html");
            $TrValueToReplace = "";
            $branch = mysqli_query($dbconn, "SELECT Branch  FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  applicationid in(" . $applicationids . ") GROUP by Branch");
            while ($rowbranch = mysqli_fetch_array($branch)) {
                $bktx = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bktx FROM `application` WHERE  agencyid='" . $row['agencyid'] . "' and (Bkt='x' or Bkt='0')  and applicationid in(" . $applicationids . ") and Branch = '" . $rowbranch['Branch'] . "'  "));
                $bkt1 = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as bkt1 FROM `application` WHERE agencyid='" . $row['agencyid'] . "' and  Bkt='1'  and applicationid in(" . $applicationids . ") and Branch = '" . $rowbranch['Branch'] . "' "));
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


        break;


    case "asassigntofos":

//         print_r($_POST);
//     exit;
        //cm withdraw case

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_fos" => '1',
                "assign_fos_datetime" => date('d-m-Y H:i:s'),
                "fosid" => $_POST['fosid'],
            );
            $where = ' where applicationid =' . trim($value);
            //$update = $connect->updaterecord($dbconn, 'ledger', $where);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';



            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['agencysupervisorid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'Agency Supervisor Assign To FOS',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }


        break;


    case "asreassigntofos":



        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $data = array(
                "is_assignto_fos" => '1',
                "assign_fos_datetime" => date('d-m-Y H:i:s'),
                "fosid" => $_POST['refosid'],
            );
            $where = ' where applicationid =' . trim($value);
            //$update = $connect->updaterecord($dbconn, 'ledger', $where);
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
            echo $statusMsg = $dealer_res ? '1' : '0';



            $data = array(
                "app_id" => $value,
                "emp_id" => $_SESSION['agencysupervisorid'],
                "emp_type" => $_SESSION['Type'],
                "action_name" => 'as reassign to fos',
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'applicationlog', $data);
        }


        break;


    case "fosreassign":

        $data = array(
            "is_assignto_fos" => '1',
            "fosid" => $_POST['fosid'],
            "fos_completed" => '0',            
            "fos_comment" => '',
            //   "is_assignto_fos" => '1',
            "assign_fos_datetime" => date('d-m-Y H:i:s'),
                //"fosid" => $_POST['fosid'],
        );
        $where = ' where  applicationid =' . $_REQUEST['editapplicationId'];
        $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);


        $data1 = array(
            "app_id" => $_REQUEST['editapplicationId'],
            "emp_id" => $_SESSION['agencymanagerid'],
            "emp_type" => $_SESSION['Type'],
            "action_name" => 'as re assign fos',
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data1);

        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AgencyChangePassword":
        $hash_result = create_hash($_REQUEST['password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn, "update agencymanager SET password = '" . $hash . "', salt = '" . $salt . "' where agencymanagerid='" . $_POST['agencymanagerid'] . "'");
        echo "Sucess";

        break;


    case "cmAddAgency":

        $data = array(
            "agencyname" => $_POST['Agency'],
            "frommail" => $_POST['frommail'],
            "fromePassword" => $_POST['fromePassword'],
            "emailto" => $_POST['emailto'],
            "cc" => $_POST['cc'],
            //  "stateid"=>$_POST['State'],
            //"locationid"=>$_POST['location'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'agency', $data);
        mysqli_query($dbconn, "INSERT INTO `agncylocation`(`agencyid`, `locationid`, `stateId`, `districtId`) SELECT $dealer_res,locationId,stateId,districtId FROM `location`  ");
//        $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn, "INSERT INTO `agncylocation`(agencyid,locationid,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }

        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetcmAgency":
        $filterstr = "SELECT * FROM `agency`  where  isDelete='0'  and  istatus='1' and  Agencyid=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);


//        $filterstr_software = "SELECT * FROM `agncylocation`  where agencyid=" . $_REQUEST['ID'] . "";
//        $result_software = mysqli_query($dbconn, $filterstr_software);
//        if (mysqli_num_rows($result_software) > 0) {
//            while ($get_result = mysqli_fetch_array($result_software)) {
//                $row['locationlist'][] = $get_result['locationid'];
//            }
//        } else {
//            $row['locationlist'][] = '0';
//        }
//        
        print_r(json_encode($row));
        break;

    case "cmEditAgency":

        $data = array(
            "agencyname" => $_REQUEST['Agency'],
            "frommail" => $_POST['frommail'],
            "fromePassword" => $_POST['fromePassword'],
            "emailto" => $_POST['emailto'],
            "cc" => $_POST['cc'],
            //   "stateid"=>$_POST['State'],
            // "locationid"=>$_POST['location'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  Agencyid=' . $_REQUEST['Agencyid'];
        $dealer_res = $connect->updaterecord($dbconn, 'agency', $data, $where);

//        $sql_res = mysqli_query($dbconn,"delete from agncylocation where  agencyid = " . $_REQUEST['Agencyid'] . " ");
//        
//        $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn, "INSERT INTO `agncylocation`(agencyid,locationid,strEntryDate,strIP) VALUES ('" . $_REQUEST['Agencyid'] . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }
//        $resultLocation = mysqli_query($dbconn,"SELECT * FROM `location`  where isDelete='0'  and  istatus='1'");
//        while ($rowC = mysqli_fetch_array($resultLocation)) {
//            if (isset($_POST['Location' . $rowC['locationId']]))
//                mysqli_query($dbconn,"INSERT INTO `agncylocation`(agencyid,locationid,strEntryDate,strIP) VALUES ('" . $_REQUEST['Agencyid'] . "','" . $rowC['locationId'] . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }


        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "cmagancystatedistrict":
//        
//       print_r($_POST);
//         exit;
        $sql_res = mysqli_query($dbconn, "delete from agncylocation where  agencyid = " . $_REQUEST['agencyid'] . " and stateId = " . $_POST['State'] . " and districtId = " . $_POST['district'] . " ");
        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            $data = array(
                "agencyid" => $_POST['agencyid'],
                "locationid" => $value,
                "stateId" => $_POST['State'],
                "districtId" => $_POST['district'],
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'agncylocation', $data);
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "Addcmagencymanager":
        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "agencyname" => $_POST['Agency'],
            "employeename" => $_POST['employeename'],
            "type" => $_POST['Type'],
            "address" => $_POST['address'],
            "loginId" => $_POST['LoginID'],
            "password" => $hash,
            "salt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'agencymanager', $data);
        if ($_POST['Type'] == 'Agency Manager') {
            mysqli_query($dbconn, "INSERT INTO `agencymanagerlocation`(`iagencymanagerid`, `iLocationId`, `stateId`, `districtId`) SELECT $dealer_res,locationId,stateId,districtId FROM location ");
        }
//       $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(iagencymanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }
        echo $dealer_res;
        break;

    case "EditcmAgencymanager":

        $data = array(
            "agencyname" => $_POST['Agency'],
            "employeename" => $_POST['employeename'],
            "type" => $_POST['Type'],
            "address" => $_POST['address'],
            "loginId" => $_POST['LoginID'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  agencymanagerid=' . $_REQUEST['agencymanagerid'];
        $dealer_res = $connect->updaterecord($dbconn, 'agencymanager', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';

        break;

    case "cmagancyuserlocationstatedistrict":
//        
        //  print_r($_POST);
        //     exit;
        $sql_res = mysqli_query($dbconn, "delete from agencymanagerlocation where  iagencymanagerid = " . $_REQUEST['agencymanagerid'] . " and stateId = " . $_POST['State'] . " ");
        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            $data = array(
                "iagencymanagerid" => $_POST['agencymanagerid'],
                "iLocationId" => $value,
                "stateId" => $_POST['State'],
                "districtId" => 0,
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'agencymanagerlocation', $data);
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "cmAddState":
        $data = array(
            "stateName" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'state', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetcmState":
        $filterstr = "SELECT * FROM `state`  where  isDelete='0'  and  istatus='1' and  stateId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditcmState":

        $data = array(
            "stateName" => $_REQUEST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  stateId=' . $_REQUEST['stateId'];
        $dealer_res = $connect->updaterecord($dbconn, 'state', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;


    case "Addcmdistrict":
        $data = array(
            "districtName" => $_POST['District'],
            "stateId" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'district', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;


    case "GetcmDistrict":
        $filterstr = "SELECT * FROM `district`  where  isDelete='0'  and  istatus='1' and  districtId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;


    case "EditcmDistrict":

        $data = array(
            "districtName" => $_POST['District'],
            "stateId" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  districtId=' . $_REQUEST['districtId'];
        $dealer_res = $connect->updaterecord($dbconn, 'district', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;



    case "AddcmLocation":
        $data = array(
            "locationName" => $_POST['Location'],
            "stateId" => $_POST['State'],
            "districtId" => 0,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'location', $data);
        mysqli_query($dbconn, "INSERT INTO `agencymanagerlocation`(`iagencymanagerid`, `iLocationId`, `stateId`, `districtId`) SELECT agencymanagerid,$dealer_res," . $_POST['State'] . "," . $_POST['district'] . " FROM `agencymanager` where type = 'Agency Manager'");
        mysqli_query($dbconn, "INSERT INTO `agncylocation`(`agencyid`, `locationid`, `stateId`, `districtId`) SELECT Agencyid,$dealer_res," . $_POST['State'] . "," . $_POST['district'] . " FROM `agency`  ");
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;


    case "GetcmLocation":
        $filterstr = "SELECT * FROM `location`  where  isDelete='0'  and  istatus='1' and  locationId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;


    case "EditcmLocation":

        $data = array(
            "locationName" => $_POST['Location'],
            "stateId" => $_POST['State'],
            "districtId" => 0,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  locationId=' . $_REQUEST['locationId'];
        $dealer_res = $connect->updaterecord($dbconn, 'location', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "tccomment":
        if (isset($_REQUEST['chk_PTP'])) {
            if ($_POST['ptpredate'] != '') {
                $data = array(
                    "ptp_datetime" => $_POST['ptpredate'],
                );
                $where = ' where  applicationid =' . $_REQUEST['applicationid'];
                $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
                $data1 = array(
                    "applicationid" => $_REQUEST['applicationid'],
                    "ptpredate" => $_POST['ptpredate'],
                    "tccomment" => $_POST['comment'],
                    "strEntryDate" => date('d-m-Y H:i:s'),
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res1 = $connect->insertrecord($dbconn, 'tchistory', $data1);
            }
        } else {
            $data = array(
                "ptp_datetime" => '',
            );
            $where = ' where  applicationid =' . $_REQUEST['applicationid'];
            $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);
        }



        $data2 = array(
            "app_id" => $_REQUEST['applicationid'],
            "emp_id" => $_SESSION['agencymanagerid'],
            "emp_type" => $_SESSION['Type'],
            "action_name" => 'tc comment',
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res1 = $connect->insertrecord($dbconn, 'applicationlog', $data2);

        echo $statusMsg = $dealer_res ? '2' : '0';
        break;
    case "deleteAllocation":
        $strId= implode(',', $_POST['check_list']);
        $deleteErrorAllocationQuery="delete from error_application where applicationid in (".$strId.")";
        mysqli_query($dbconn, $deleteErrorAllocationQuery);
        break;
        
    case "AddBank":
        $data = array(
            "strBankName" => $_POST['strBankName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'bankmaster', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
    break;

    case "GetEmployeeBank":
        $filterstr = "SELECT * FROM `bankmaster`  where  isDelete='0'  and  iStatus='1' and  iBankId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
    break;

    case "EditBanbk":

        $data = array(
            "strBankName" => $_REQUEST['strBankName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  iBankId=' . $_REQUEST['iBankId'];
        $dealer_res = $connect->updaterecord($dbconn, 'bankmaster', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
    break;
    
    case "AddProductBankMapping":
        $data = array(
            "iBankId" => $_POST['iBankId'],
            "iProductId" => $_POST['iProductId'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'product_bank_mapping', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
    break;

    case "GetEmployeeProductBankMapping":
        $filterstr = "SELECT * FROM `product_bank_mapping`  where  isDelete='0'  and  iStatus='1' and  iProductBankMapping=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
    break;

    case "EditProductBankMapping":

        $data = array(
            "iBankId" => $_POST['iBankId'],
            "iProductId" => $_POST['iProductId'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  iProductBankMapping=' . $_REQUEST['iProductBankMapping'];
        $dealer_res = $connect->updaterecord($dbconn, 'product_bank_mapping', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
    break;
}
?>