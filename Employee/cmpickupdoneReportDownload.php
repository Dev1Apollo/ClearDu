<?php

ob_start();

include('../config.php');
        

$strLocationID = '';
    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");
    
    $sql1 = "SELECT * FROM `application` where  locationid in(".$strLocationID.") and  fos_completed='1' and fos_completed_status='1' ";

$result1 = mysqli_query($dbconn, $sql1);
//$date=date('d-m-Y');

$filename = 'cmpickupdone.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"SrNo"
 . "\t  unique Id"
        . "\t  FOS Comment"
 . "\t  Agency Name"
 . "\t  Assign DateTime"
 . "\t  LAN NUMBER"
 . "\t  PRODUCT"
 . "\t  State"
 . "\t  CONSIGNEE"
 . "\t  CONSIGNEE_ADDRESS1"
 . "\t  CONSIGNEE_ADDRESS2"
 . "\t  CONSIGNEE_ADDRESS3"
 . "\t  CONSIGNEE_ADDRESS4"
 . "\t  DESTINATION_CITY"
 . "\t  PINCODE"
 . "\t  STATE"
 . "\t  MOBILE"
 . "\t  TELEPHONE"
 . "\t  ITEM_DESCRIPTION"
 . "\t  DROP_VENDOR_CODE"
 . "\t  DROP_NAME"
 . "\t  DROP_ADDRESS_LINE1"
 . "\t  DROP_ADDRESS_LINE2"
 . "\t  DROP_ADDRESS_LINE3"
 . "\t  DROP_ADDRESS_LINE4"
 . "\t  DROP_PINCODE"
 . "\t  DROP_PHONE"
 . "\t  DROP_MOBILE"
 . "\t  COLLECTABLE_VALUE"
 . "\t  SLOT"
 . "\t  DATE"
 . "\t  ACTIVITY_CODE1"
 . "\t  Mandatory_Optional1"
 . "\t  DOCUMENT_REF_NUMBER1"
 . "\t  REMARKS1"
 . "\t  ACTIVITY_CODE2 "
 . "\t  Mandatory_Optional2"
 . "\t  DOCUMENT_REF_NUMBER2"
 . "\t  REMARKS2"
 . "\t  ACTIVITY_CODE3"
 . "\t  Mandatory_Optional3"
 . "\t  DOCUMENT_REF_NUMBER3"
 . "\t  REMARKS3"
 . "\n";
$i = 1;
while ($rows = mysqli_fetch_array($result1)) {

    //$lager = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `ledger` where  iUserId='" . $rows['usersid'] . "'     ORDER BY `ledger` DESC limit 1 "));
    $asname = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agency`  where  Agencyid='" . $rows['agencyid'] . "' "));

    echo
    $i
    . "\t" . $rows['uniqueId']
            . "\t" . $rows['fos_comment']
    . "\t" . $asname['agencyname']
    . "\t" . $rows['assign_am_datetime']
    . "\t" . $rows['LAN_NUMBER']
    . "\t" . $rows['PRODUCT']
    . "\t" . $rows['STATE']
    . "\t" . $rows['CONSIGNEE']
    . "\t" . $rows['CONSIGNEE_ADDRESS1']
    . "\t" . $rows['CONSIGNEE_ADDRESS2']
    . "\t" . $rows['CONSIGNEE_ADDRESS3']
    . "\t" . $rows['CONSIGNEE_ADDRESS4']
    . "\t" . $rows['DESTINATION_CITY']
    . "\t" . $rows['PINCODE']
    . "\t" . $rows['STATE']
    . "\t" . $rows['MOBILE']
    . "\t" . $rows['TELEPHONE']
    . "\t" . $rows['ITEM_DESCRIPTION']
    . "\t" . $rows['DROP_VENDOR_CODE']
    . "\t" . $rows['DROP_NAME']
    . "\t" . $rows['DROP_ADDRESS_LINE1']
    . "\t" . $rows['DROP_ADDRESS_LINE2']
    . "\t" . $rows['DROP_ADDRESS_LINE3']
    . "\t" . $rows['DROP_ADDRESS_LINE4']
    . "\t" . $rows['DROP_PINCODE']
    . "\t" . $rows['DROP_PHONE']
    . "\t" . $rows['DROP_MOBILE']
    . "\t" . $rows['COLLECTABLE_VALUE']
    . "\t" . $rows['SLOT']
    . "\t" . $rows['DATE']
    . "\t" . $rows['ACTIVITY_CODE1']
    . "\t" . $rows['Mandatory_Optional1']
    . "\t" . $rows['DOCUMENT_REF_NUMBER1']
    . "\t" . $rows['REMARKS1']
    . "\t" . $rows['ACTIVITY_CODE3']
    . "\t" . $rows['Mandatory_Optional3']
    . "\t" . $rows['DOCUMENT_REF_NUMBER2']
    . "\t" . $rows['DOCUMENT_REF_NUMBER3']
    . "\t" . $rows['REMARKS3']
    . "\n";
    $i++;
}
?>
