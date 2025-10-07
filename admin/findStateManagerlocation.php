<?php
include('../config.php');
?>
<?php
$sId = intval($_GET['sId']);
$iStateManagerId = intval($_GET['iStateManagerId']);

$result = mysqli_query($dbconn, "select * from location  where  istatus='1' and isDelete='0'  and stateId = ".$sId." order by locationId ASC");
$i = 1;
?>
<div class="md-checkbox">
    <input type="checkbox" onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
    <label for="check_listall"> Select All
        <span></span>
        <span class="check"></span>
        <span class="box"></span>
    </label>
</div>
<?php 
while ($row_menu = mysqli_fetch_array($result)) {
    $Client = "SELECT * FROM `statemanagerlocation`  where isDelete='0'  and  istatus='1'   and iLocationId='" . $row_menu['locationId'] . "' and  iStateId='" . $row_menu['stateId'] . "'  and iStatemanagerId = '".$iStateManagerId."' ";
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
<script>
    function CheckAll() {

        if ($('#check_listall').is(":checked")) {
            // alert('cheked');
            $('input[type=checkbox]').each(function() {
                $(this).prop('checked', true);
            });
        } else {
            //alert('cheked fail');
            $('input[type=checkbox]').each(function() {
                $(this).prop('checked', false);
            });
        }
    }
</script>