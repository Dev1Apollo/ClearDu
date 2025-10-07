<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
$connect = new connect();
include 'IsLogin.php';
include 'password_hash.php';


$action = $_REQUEST['action'];
switch ($action) {


    case "AddStatemanager":
        
        $hash_result = create_hash($_REQUEST['strPassword']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "strEmployeeName" => $_POST['strEmployeeName'],
            "strEmail" => $_POST['strEmail'],
            "strPhoneNo" => $_POST['strPhoneNo'],
            "strMobileNo" => $_POST['strMobileNo'],
            "loginId" => $_POST['loginId'],
            //"canaddagency" => $_POST['canaddagency'],
            "strPassword" => $hash,
            "strSalt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'statemanager', $data);

        $Bank = $_POST['Bank'];
        foreach ($Bank as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `statemanageBank` (iStateManagerId,iBankId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        
        $Agency = $_POST['Agency'];
        foreach ($Agency as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `statemanageAgency` (iStateManagerId,iAgencyId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;


    case "EditStatemanager":

        $data = array(
            "strEmployeeName" => $_POST['strEmployeeName'],
            "strEmail" => $_POST['strEmail'],
            "strPhoneNo" => $_POST['strPhoneNo'],
            "strMobileNo" => $_POST['strMobileNo'],
            "loginId" => $_POST['loginId'],
            //"canaddagency" => $_POST['canaddagency'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  iStateManagerId=' . $_REQUEST['iStateManagerId'];
        $dealer_res = $connect->updaterecord($dbconn, 'statemanager', $data, $where);

        $sql_res = mysqli_query($dbconn, "delete from statemanageBank where  iStateManagerId = " . $_REQUEST['iStateManagerId'] . " ");
        $sql_res = mysqli_query($dbconn, "delete from statemanageAgency where  iStateManagerId = " . $_REQUEST['iStateManagerId'] . " ");

        // $resultLocation = mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1'");
        // while ($rowC = mysqli_fetch_array($resultLocation)) {
        //     if (isset($_POST['Location' . $rowC['locationId']]))
        //         mysqli_query($dbconn, "INSERT INTO `centralmanagerlocation`(icentralmanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $_REQUEST['centralmanagerid'] . "','" . $rowC['locationId'] . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        // }
        $Bank = $_POST['Bank'];
        foreach ($Bank as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `statemanageBank` (iStateManagerId,iBankId,strEntryDate,strIP) VALUES ('" . $_REQUEST['iStateManagerId'] . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        
        $Agency = $_POST['Agency'];
        foreach ($Agency as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `statemanageAgency` (iStateManagerId,iAgencyId,strEntryDate,strIP) VALUES ('" . $_REQUEST['iStateManagerId'] . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        echo $statusMsg = $dealer_res ? '2' : '0';

        break;

    case "statemanagerChangePassword":
        $hash_result = create_hash($_REQUEST['strPassword']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn, "update statemanager SET strPassword = '" . $hash . "', strSalt = '" . $salt . "' where iStateManagerId='" . $_POST['iStateManagerId'] . "'");
        echo "Sucess";

        break;
        
    case "statemanagerlocationstatedistrict":
        // print_r($_REQUEST);
        // exit;
        
        $sql_res = mysqli_query($dbconn, "delete from statemanagerlocation where  iStateManagerId = " . $_REQUEST['iStateManagerId'] . " and iStateId = " . $_POST['State'] . " and iDistrictId = " . $_POST['district'] . " ");
        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            $data = array(
                "iStateManagerId" => $_POST['iStateManagerId'],
                "iLocationId" => $value,
                "iStateId" => $_POST['State'],
                "iDistrictId" => $_POST['district'],
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'statemanagerlocation', $data);
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;
        
        default:
# code...
        echo "Page not Found";
        break;
}
?>