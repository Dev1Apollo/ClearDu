<?php   
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

    
if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";
    if (isset($_REQUEST['Location'])) {
        if ($_POST['Location'] != '') {

            //$where.=" and  locationemployeelocation.iLocationId='" . $_POST['Location'] . "'";
            $where .= " and `agencymanager`.`agencymanagerid` in (SELECT iagencymanagerid FROM `agencymanagerlocation` where `agencymanagerlocation`.`iLocationId`  = '" . $_POST['Location'] . "')";
        }
    }
    if (isset($_REQUEST['Type'])) {
        if ($_POST['Type'] != '') {

            $where.=" and type='" . $_POST['Type'] . "'";
        }
    }
    if (isset($_REQUEST['Search_Txt'])) {
        if ($_POST['Search_Txt'] != '') {

            $where.=" and  employeename like '%$_POST[Search_Txt]%'";
        }
    }
    if (isset($_REQUEST['agency'])) {
        if ($_POST['agency'] != '') {

            $where.=" and  agencyname = '" . $_POST['agency'] . "' ";
        }
    }

    $useragency = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid ='" . $_SESSION['agencymanagerid'] . "' "));
    $filterstr = "SELECT * FROM `agencymanagerlocation`  where iagencymanagerid= '" . $_REQUEST['agencymanagerid'] . "' and isDelete='0'  and  istatus='1' order by  agencymanagerlocationid desc";
    $countstr = "SELECT count(*) as TotalRow FROM `agencymanagerlocation` iagencymanagerid= '" . $_REQUEST['agencymanagerid'] . "' and isDelete='0' and  istatus='1' ";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 1;
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />

        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

            <thead class="tbg">
                <tr>

                   <th class="all">Agency Name</th>
                    <th class="all">Branch</th>
                    <th class="desktop">State</th>
                    <th class="desktop">District</th>
                     <th class="desktop">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>

                    <tr >
  <td>
                            <?php
                            $agencyuser = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE agencymanagerid = '" . $rowfilter['iagencymanagerid'] . "' and isDelete='0' and istatus='1' "));
                            $Location = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `location` WHERE locationId = '" . $rowfilter['iLocationId'] . "' and isDelete='0' and istatus='1' "));
                            $state = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `state` WHERE stateId = '" . $rowfilter['stateId'] . "' and isDelete='0' and istatus='1' "));
                            $district = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `district` WHERE districtId = '" . $rowfilter['districtId'] . "' and isDelete='0' and istatus='1' "));
                            ?>
                            <div class="form-group form-md-line-input "><?php echo $agencyuser['employeename']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $Location['locationName']; ?> 
                            </div>
                        </td> 



                        <td>
                            <div class="form-group form-md-line-input "><?php echo $state['stateName']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $district['districtName']; ?> 
                            </div>
                        </td>
                         <td>
                            <div class="form-group form-md-line-input "> 
                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['agencymanagerlocationid']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                            </div>
                        </td>
          
            <?php
            $i++;
        }
        ?>

                </tr>
            </tbody>
        </table>
        </form>

        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
                                $(document).ready(function () {
                                    $('#tableC').DataTable({
                                    });
                                });
        </script>
        <?php
    } else {
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                    <h1 class="font-white text-center"> No Data Found ! </h1>
                </div>   
            </div>
        </div>
        <?php
    }
}

if ($_REQUEST['action'] == 'Delete') {
    
    $delete=  mysqli_query($dbconn, "DELETE FROM `agencymanagerlocation` WHERE agencymanagerlocationid='".$_REQUEST['ID']."' ");
//    $data = array(
//        "isDelete" => '1',
//        "strEntryDate" => date('d-m-Y H:i:s')
//    );
//    $where = ' where  	agencymanagerid=' . $_REQUEST['ID'];
//    $dealer_res = $connect->updaterecord($dbconn, 'agencymanager', $data, $where);
}
?>
<?php if ($totalrecord > $per_page) { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
            <div class="form-actions noborder">
    <?php
    echo '<div class="pagination">';

    if ($totalrecord > $per_page) {
        echo paginate($reload = '', $show_page, $total_pages);
    }
    echo "</div>";
    ?>
            </div>
        </div>
    </div>
<?php } ?>








