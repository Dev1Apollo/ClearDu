<?php
include('../config.php');
?>  
<?php

$sId = intval($_GET['sId']);
$agencyid = intval($_GET['agencyid']);
$agencymanagerid =$_GET['agencymanagerid'];
$result = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0'  and stateId = ".$sId." order by locationName ASC");
$i = 1;
while ($row_menu = mysqli_fetch_array($result)) {
    $Client = "SELECT * FROM `agencymanagerlocation`  where isDelete='0'  and  istatus='1'  and iagencymanagerid='".$agencymanagerid."' and iLocationId='" . $row_menu['locationId'] . "' and  stateId='" . $row_menu['stateId'] . "' ";
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
