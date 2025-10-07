<?php
include('../config.php');
?>
<?php
$did = intval($_GET['did']);
$sId = intval($_GET['sId']);
$agencyid = intval($_GET['agencyid']);
$result = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0' and districtId=" . $did . " and stateId = ".$sId." order by locationId ASC");
$i = 1;
while ($row_menu = mysqli_fetch_array($result)) {
    $Client = "SELECT * FROM `agncylocation`  where isDelete='0'  and  istatus='1'   and locationid='" . $row_menu['locationId'] . "' and  stateId='" . $row_menu['stateId'] . "' and  districtId='" . $row_menu['districtId'] . "' and agencyid = '".$agencyid."' ";
    $resultC = mysqli_query($dbconn, $Client);
    ?>
    <input type='checkbox' name='Location[]' value="<?php echo $row_menu['locationId']; ?>"<?php
    if (mysqli_num_rows($resultC) > 0) {
        echo "checked";
    }
    ?> id='Location<?php echo $row_menu['locationId']; ?>' />&nbsp <?php echo $row_menu['locationName']; ?>
    <!--                                                            echo "<input type='checkbox' name='Location[]' value='" . $row_menu['locationId'] . "' id='location'/>&nbsp" . $row_menu['locationName'];-->
           <?php
           // echo "<input type='checkbox' name='Location[]' value='" . $row_menu['locationId'] . "' id='Location[]'/>&nbsp" . $row_menu['locationName'];
           $i++;
           echo "<br />";
       }
//$data='<select name="location" id="location" class="form-control"  required onchange="getagency();" multiple="multiple" >
//<option value="">Select location Name</option>';
// while($row=mysqli_fetch_array($result)) { 
//	$data.='<option value='.$row['locationId'].'>'.$row['locationName'].'</option>';
//}
//$data .='</select>';
//echo $data;
       ?>
