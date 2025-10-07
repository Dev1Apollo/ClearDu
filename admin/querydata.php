<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
$connect = new connect();
include 'IsLogin.php';
include 'password_hash.php';


$action = $_REQUEST['action'];
switch ($action) {
    case "UserProfileChangePassword":
        $hash_result = create_hash($_POST['oldpassword']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $existsmail = "SELECT * FROM admin where id='" . $_SESSION['AdminId'] . "'";
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
                $getItems1 = mysqli_query($dbconn, "update admin SET password = '" . $hash . "', salt = '" . $salt . "' where id='" . $_SESSION['AdminId'] . "'");
                echo "Sucess";
            } else {
                echo "OldNot";
            }
        } else {
            echo "ID not found";
        }
        break;


    case "Adddistrict":
        $data = array(
            "districtName" => $_POST['District'],
            "stateId" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'district', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;


    case "GetAdminDistrict":
        $filterstr = "SELECT * FROM `district`  where  isDelete='0'  and  istatus='1' and  districtId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;


    case "EditDistrict":

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





    case "AddState":
        $data = array(
            "stateName" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'state', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminState":
        $filterstr = "SELECT * FROM `state`  where  isDelete='0'  and  istatus='1' and  stateId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditState":

        $data = array(
            "stateName" => $_REQUEST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  stateId=' . $_REQUEST['stateId'];
        $dealer_res = $connect->updaterecord($dbconn, 'state', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;



    case "AddLocation":
        $data = array(
            "locationName" => $_POST['Location'],
            "stateId" => $_POST['State'],
            "districtId" => $_POST['district'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'location', $data);
        mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(`iagencymanagerid`, `iLocationId`, `stateId`, `districtId`) SELECT agencymanagerid,$dealer_res,".$_POST['State'].",".$_POST['district']." FROM `agencymanager` where type = 'Agency Manager'"); 
        mysqli_query($dbconn, "INSERT INTO `agncylocation`(`agencyid`, `locationid`, `stateId`, `districtId`) SELECT Agencyid,$dealer_res,".$_POST['State'].",".$_POST['district']." FROM `agency`  ");
        echo $statusMsg = $dealer_res ? '1' : '0';
        
        break;


    case "GetAdminLocation":
        $filterstr = "SELECT * FROM `location`  where  isDelete='0'  and  istatus='1' and  locationId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;


    case "EditLocation":

        $data = array(
            "locationName" => $_POST['Location'],
            "stateId" => $_POST['State'],
            "districtId" => $_POST['district'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  locationId=' . $_REQUEST['locationId'];
        $dealer_res = $connect->updaterecord($dbconn, 'location', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;



    case "AddAgency":
        //  print_r($_POST);
//          exit;
        $data = array(
            "agencyname" => $_POST['Agency'],
            "frommail" => $_POST['frommail'],
            "fromePassword" => $_POST['fromePassword'],
            "emailto" => $_POST['emailto'],
            "cc" => $_POST['cc'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'agency', $data);
          $sql_res = mysqli_query($dbconn, "delete from agncylocation where  agencyid = " . $dealer_res . "  ");
          mysqli_query($dbconn, "INSERT INTO `agncylocation`(`agencyid`, `locationid`, `stateId`, `districtId`) SELECT $dealer_res,locationId,stateId,districtId FROM `location`  ");
//        $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn, "INSERT INTO `agncylocation`(agencyid,locationid,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }

        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminAgency":
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

    case "EditAgency":

        $data = array(
            "agencyname" => $_REQUEST['Agency'],
            "frommail" => $_POST['frommail'],
            "fromePassword" => $_POST['fromePassword'],
            "emailto" => $_POST['emailto'],
            "cc" => $_POST['cc'],
            //   "stateid"=>$_POST['State'],
            //   mihir@apolloinfotech.com
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




    case "agancystatedistrict":
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


    case "AddProduct":
        $data = array(
            "productname" => $_POST['Product'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'product', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminProduct":
        $filterstr = "SELECT * FROM `product`  where  isDelete='0'  and  istatus='1' and  productid =" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditProduct":

        $data = array(
            "productname" => $_REQUEST['Product'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  productid=' . $_REQUEST['productid'];
        $dealer_res = $connect->updaterecord($dbconn, 'product', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;


    case "AddBKT":
        $data = array(
            "bktname" => $_POST['BKTname'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'bkt', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminBKT":
        $filterstr = "SELECT * FROM `bkt`  where  isDelete='0'  and  istatus='1' and  bktid =" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditBKT":

        $data = array(
            "bktname" => $_REQUEST['BKTname'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  bktid=' . $_REQUEST['bktid'];
        $dealer_res = $connect->updaterecord($dbconn, 'bkt', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;








    case "Addcentralmanager":
        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "employeeName" => $_POST['Employee'],
            "email" => $_POST['Email'],
            "phoneNo" => $_POST['Phone'],
            "mobileNo" => $_POST['Mobile'],
            "loginId" => $_POST['LoginID'],
            "canaddagency" => $_POST['canaddagency'],
            "password" => $hash,
            "salt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'centralmanager', $data);

        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `centralmanagerlocation`(icentralmanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;


    case "Editcentralmanager":

        $data = array(
            "employeeName" => $_POST['Employee'],
            "email" => $_POST['Email'],
            "phoneNo" => $_POST['Phone'],
            "mobileNo" => $_POST['Mobile'],
            "loginId" => $_POST['LoginID'],
            "canaddagency" => $_POST['canaddagency'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  centralmanagerid=' . $_REQUEST['centralmanagerid'];
        $dealer_res = $connect->updaterecord($dbconn, 'centralmanager', $data, $where);

        $sql_res = mysqli_query($dbconn, "delete from centralmanagerlocation where  icentralmanagerid = " . $_REQUEST['centralmanagerid'] . " ");


        $resultLocation = mysqli_query($dbconn, "SELECT * FROM `location`  where isDelete='0'  and  istatus='1'");
        while ($rowC = mysqli_fetch_array($resultLocation)) {
            if (isset($_POST['Location' . $rowC['locationId']]))
                mysqli_query($dbconn, "INSERT INTO `centralmanagerlocation`(icentralmanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $_REQUEST['centralmanagerid'] . "','" . $rowC['locationId'] . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        echo $statusMsg = $dealer_res ? '2' : '0';

        break;

    case "centralmanagerChangePassword":
        $hash_result = create_hash($_REQUEST['password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn, "update centralmanager SET password = '" . $hash . "', salt = '" . $salt . "' where centralmanagerid='" . $_POST['centralmanagerid'] . "'");
        echo "Sucess";

        break;


    case "AddLocationEmployee":
        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "employeeName" => $_POST['Employee'],
            "email" => $_POST['Email'],
            "phoneNo" => $_POST['Phone'],
            "mobileNo" => $_POST['Mobile'],
            "loginId" => $_POST['LoginID'],
            "password" => $hash,
            "salt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'centralmanager', $data);

        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            mysqli_query($dbconn, "INSERT INTO `centralmanagerlocation`(icentralmanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
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
            "strLatitude" => $_POST['strLatitude'],
            "strLongitude" => $_POST['strLongitude'],
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'agencymanager', $data);
        if($_POST['Type']=='Agency Manager'){
          mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(`iagencymanagerid`, `iLocationId`, `stateId`, `districtId`) SELECT $dealer_res,locationId,stateId,districtId FROM location "); 
        }
         
//       $Location = $_POST['Location'];
//        foreach ($Location as $key => $value) {
//            mysqli_query($dbconn,"INSERT INTO `agencymanagerlocation`(iagencymanagerid,iLocationId,strEntryDate,strIP) VALUES ('" . $dealer_res . "','" . $value . "', '" . date('d-m-Y H:i:s') . "', '" . $_SERVER['REMOTE_ADDR'] . "' ) ");
//        }
        echo $dealer_res;
        break;


    case "agancyuserlocationstatedistrict":
//        
        //  print_r($_POST);
        //     exit;
        $sql_res = mysqli_query($dbconn, "delete from agencymanagerlocation where  iagencymanagerid = " . $_REQUEST['agencymanagerid'] . " and stateId = " . $_POST['State'] . " and districtId = " . $_POST['district'] . " ");
        $Location = $_POST['Location'];
        foreach ($Location as $key => $value) {
            $data = array(
                "iagencymanagerid" => $_POST['agencymanagerid'],
                "iLocationId" => $value,
                "stateId" => $_POST['State'],
                "districtId" => $_POST['district'],
                "strEntryDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'agencymanagerlocation', $data);
        }
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;



    case "EditAgencymanager":
        $data = array(
            "agencyname" => $_POST['Agency'],
            "employeename" => $_POST['employeename'],
            "type" => $_POST['Type'],
            "address" => $_POST['address'],
            "loginId" => $_POST['LoginID'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strLatitude" => $_POST['strLatitude'],
            "strLongitude" => $_POST['strLongitude'],
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

    case "AgencyChangePassword":
        $hash_result = create_hash($_REQUEST['password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn, "update agencymanager SET password = '" . $hash . "', salt = '" . $salt . "' where agencymanagerid='" . $_POST['agencymanagerid'] . "'");
        echo "Sucess";

        break;

    case "Addcompanyemployee":
        //print_r($_POST);
        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "name" => $_POST['employeename'],
            "mobile" => $_POST['mobile'],
            "address" => $_POST['address'],
            "loginid" => $_POST['LoginID'],
            "password" => $hash,
            "salt" => $salt,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'companyemployee', $data);


        echo $dealer_res;
        break;

    case "companyemployeeChangePassword":
        $hash_result = create_hash($_REQUEST['password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn, "update companyemployee SET password = '" . $hash . "', salt = '" . $salt . "' where companyemployeeid ='" . $_POST['companyemployeeid'] . "'");
        echo "Sucess";

        break;

    case "Editcompanyemployee":

        $data = array(
            "name" => $_POST['Employee'],
            "loginid" => $_POST['LoginID'],
            "mobile" => $_POST['Mobile'],
            "address" => $_POST['address'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  companyemployeeid = ' . $_REQUEST['companyemployeeid'];
        $dealer_res = $connect->updaterecord($dbconn, 'companyemployee', $data, $where);


        echo $statusMsg = $dealer_res ? '2' : '0';

        break;

    case "Editcase":

        $data = array(
            "fos_completed_status" => $_POST['fos_completed_status'],
            "Payment_Collected_Amount" => $_POST['Payment_Collected_Amount'],
            "penal" => $_POST['penal'],
            "totalamt" => $_POST['totalamt'],
        );
        $where = ' where  applicationid = ' . $_REQUEST['applicationid'];
        $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);


        echo $statusMsg = $dealer_res ? '2' : '0';

        break;

    case "EditCoustomercitycase":
        // customer_city_id
        // customer_city
        $queryCom = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM  location WHERE locationId='" . $_POST['customer_city'] . "'"));
        $data = array(
            "customer_city_id" => $_POST['customer_city'],
            "customer_city" => $queryCom['locationName'],           
        );
        $where = ' where  applicationid = ' . $_REQUEST['applicationid'];
        $dealer_res = $connect->updaterecord($dbconn, 'application', $data, $where);


        echo $statusMsg = $dealer_res ? '2' : '0';

        break;














    case "AddFormType":
        $data = array(
            "formName" => $_POST['FormType'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'formtype', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminFormType":
        $filterstr = "SELECT * FROM `formtype`  where  isDelete='0'  and  istatus='1' and  formId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditFormType":

        $data = array(
            "formName" => $_POST['FormType'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  formId=' . $_REQUEST['formId'];
        $dealer_res = $connect->updaterecord($dbconn, 'formtype', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;








    case "AddFormTypeDetail":
        $data = array(
            "formId" => $_POST['formId'],
            "excelColumnName" => trim($_POST['excelColumnName']),
            "dbColumnName" => trim($_POST['dbColumnName'])
        );
        $dealer_res = $connect->insertrecord($dbconn, 'formdetail', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminFormTypeDetail":
        $filterstr = "SELECT * FROM `formdetail`  where  formDetailId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn, $filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditFormTypeDetail":

        $data = array(
            "formId" => $_POST['formId'],
            "excelColumnName" => trim($_POST['excelColumnName']),
            "dbColumnName" => trim($_POST['dbColumnName'])
        );
        $where = ' where  formDetailId=' . $_REQUEST['formDetailId'];
        $dealer_res = $connect->updaterecord($dbconn, 'formdetail', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
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

    case "GetAdminBank":
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

    case "GetAdminProductBankMapping":
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

    default:
# code...
        echo "Page not Found";
        break;
}
?>