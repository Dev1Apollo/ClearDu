<?php

include('../config.php');

?>
<?php

$Agency=intval($_GET['Agency']);
$locationid='0';
$locid =  mysqli_query($dbconn, "SELECT * FROM `agncylocation`  where agencyid ='".$Agency."'");
  while ($locations = mysqli_fetch_array($locid)) {
                        $locationid = $locations['locationid'] . ',' . $locationid;
                    }
                    $locationid = rtrim($locationid, ", ");
               
              


$result=mysqli_query($dbconn,"select * from location  where  istatus='1' and isDelete='0' and locationId in (".$locationid.") order by locationId ASC");
 $i = 1;
                                                        while ($row_menu = mysqli_fetch_array($result)) {
                                                            echo "<input type='checkbox' name='Location[]' value='" . $row_menu['locationId'] . "' id='Location[]'/>&nbsp" . $row_menu['locationName'];
                                                            $i++;
                                                            echo "<br />";
                                                        }

?>
