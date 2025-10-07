<?php
include('../config.php');
?>
<?php
//$did = intval($_GET['did']);
$sId = intval($_GET['sId']);
$agencyid = intval($_GET['agencyid']);
//and districtId=" . $did . "
$result = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0'  and stateId = " . $sId . " order by locationId ASC");
$i = 1;
while ($row_menu = mysqli_fetch_array($result)) {
//and  districtId='" . $row_menu['districtId'] . "'
    $Client = "SELECT * FROM `agncylocation`  where isDelete='0'  and  istatus='1'   and locationid='" . $row_menu['locationId'] . "' and  stateId='" . $row_menu['stateId'] . "'  and agencyid = '" . $agencyid . "' ";
    $resultC = mysqli_query($dbconn, $Client);
    ?>
    <input type='checkbox' name='Location[]' value="<?php echo $row_menu['locationId']; ?>"
    <?php
    if (mysqli_num_rows($resultC) > 0) {
        echo "checked";
    }
    ?> 
    id='Location<?php echo $row_menu['locationId']; ?>' />&nbsp <?php echo $row_menu['locationName']; ?>    
    <?php
    $i++;
    echo "<br />";
}
?>
