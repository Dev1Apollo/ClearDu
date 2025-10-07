<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

//coustomer not availabel =4
    
      $strLocationID = '';
    $user = mysqli_query($dbconn, "SELECT * FROM `centralmanagerlocation`  where  icentralmanagerid='" . $_SESSION['centralmanagerid'] . "'  ");
    while ($userid = mysqli_fetch_array($user)) {
        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
    }
    $strLocationID = rtrim($strLocationID, ", ");
    
    
    $filterstr = "SELECT * FROM `application` where locationid in(".$strLocationID.") and fos_completed='1' and fos_completed_status='4' ";
    $countstr = "SELECT count(*) as TotalRow FROM  `application` where locationid in(".$strLocationID.") and  fos_completed='1' and fos_completed_status='4'  ";

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
                    <th></th>
                    <th class="all">unique Id</th>
                    <th class="all">LAN NUMBER</th>
                    <th class="all">PRODUCT</th>
                    
                     <th class="all">FOS Comment</th>
                      <th class="all">ptp datetime</th>
                      
                    <th class="desktop">CONSIGNEE</th>
                    <th class="none">CONSIGNEE_ADDRESS1</th>
                    <th class="desktop">CONSIGNEE_ADDRESS2</th>
                    <th class="desktop">CONSIGNEE_ADDRESS3</th>
                    <th class="none">CONSIGNEE_ADDRESS4</th>
                    <th class="none">DESTINATION_CITY</th>
                    <th class="none">PINCODE</th>
                    <th class="none">STATE</th>
                    <th class="none">MOBILE</th>

                    <th class="none">TELEPHONE </th>
                    <th class="none">ITEM_DESCRIPTION</th>
                    <th class="desktop">DROP_VENDOR_CODE</th>
                    <th class="desktop">DROP_NAME</th>
                    <th class="none">DROP_ADDRESS_LINE1</th>
                    <th class="none">DROP_ADDRESS_LINE2</th>
                    <th class="none">DROP_ADDRESS_LINE3</th>
                    <th class="none">DROP_ADDRESS_LINE4</th>
                    <th class="none">DROP_PINCODE</th>
                    <th class="none">DROP_PHONE</th>
                    <th class="none">DROP_MOBILE</th>
                    <th class="none">COLLECTABLE_VALUE</th>
                    <th class="none">SLOT</th>
                    <th class="none">DATE</th>
                    <th class="none">ACTIVITY_CODE1</th>
                    <th class="none">Mandatory_Optional1</th>
                    <th class="none">DOCUMENT_REF_NUMBER1</th>
                    <th class="none">REMARKS1</th>

                    <th class="none">ACTIVITY_CODE2 </th>
                    <th class="none">Mandatory_Optional2</th>

                    <th class="none">DOCUMENT_REF_NUMBER2</th>
                    <th class="none">REMARKS2</th>
                    <th class="none">ACTIVITY_CODE3</th>
                    <th class="none">Mandatory_Optional3</th>
                    <th class="none">DOCUMENT_REF_NUMBER3</th>
                    <th class="none">REMARKS3</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    ?>

                    <tr >
                        <td></td>
                          <td>
                           <div class="form-group form-md-line-input ">
                                <?php
                                echo $rowfilter['uniqueId'];
                                ?>
                            </div>
                                </td>
                        <td>
                            <div class="form-group form-md-line-input ">
                                <?php
                                echo $rowfilter['LAN_NUMBER'];
                                ?>
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['PRODUCT']; ?> 
                            </div>
                        </td> 
                         <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['PRODUCT']; ?> 
                            </div>
                        </td> 
                         <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['fos_comment']; ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CONSIGNEE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CONSIGNEE_ADDRESS1']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CONSIGNEE_ADDRESS2']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CONSIGNEE_ADDRESS3']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CONSIGNEE_ADDRESS4']; ?> 
                            </div>
                        </td>


                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DESTINATION_CITY']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['PINCODE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['STATE']; ?> 
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['MOBILE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['TELEPHONE']; ?> 
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ITEM_DESCRIPTION']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_VENDOR_CODE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_NAME']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_ADDRESS_LINE1']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_ADDRESS_LINE2']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_ADDRESS_LINE3']; ?> 

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_ADDRESS_LINE4']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_PINCODE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_PHONE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DROP_MOBILE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['COLLECTABLE_VALUE']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['SLOT']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DATE']; ?>

                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ACTIVITY_CODE1']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Mandatory_Optional1']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DOCUMENT_REF_NUMBER1']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['REMARKS1']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['ACTIVITY_CODE3']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Mandatory_Optional3']; ?> 
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DOCUMENT_REF_NUMBER2']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DOCUMENT_REF_NUMBER3']; ?> 
                            </div>
                        </td>

                        <td style="width: 10%">
                            <div class="form-group form-md-line-input">
                                <?php echo $rowfilter['REMARKS3'] ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['remarks']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['allocationRemarks']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['appoimentComment']; ?> 
                            </div>
                        </td>
                        <?php
                        $i++;
                    }
                    ?>

                </tr>
            </tbody>
        </table>
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






<script type="text/javascript">

    function phoneNumberAdd(appID) {
        // alert(id);

        var phoneNumber = $('#phoneNumber' + appID).val();
        //alert(sequenceNo);
        $.ajax({
            type: "POST",
            url: "queryDataAdminEmployee.php",
            data: {action: 'SubmitPhoneNumber', appID: appID, phoneNumber: phoneNumber},
            success: function (msg) {
                if (msg == 1) {
                    alert('Submit Sucessfully.');
                } else {
                    alert('Please Fillup The Phone Number.');
                }
                window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';


            },
        });

    }


</script>  



<script type="text/javascript">

    function officeNumberAdd(appID) {
        // alert(id);

        var officeNumber = $('#officeNumber' + appID).val();
        //alert(sequenceNo);
        $.ajax({
            type: "POST",
            url: "queryDataAdminEmployee.php",
            data: {action: 'SubmitOfficeNo', appID: appID, officeNumber: officeNumber},
            success: function (msg) {
                if (msg == 1) {
                    alert('Submit Sucessfully.');
                } else {
                    alert('Please Fillup the Office No.');
                }
                window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';


            },
        });

    }


</script>  

<!--<button type="button" class="btn blue btn-sm" onclick="return showDetail('<?php echo $rowfilter['appID']; ?>');"><i class="fa fa-history"></i></button>-->
<script type="text/javascript">
    function showDetail(Id)
    {
        $('#myModal').modal('show');
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>Employee/AjaxAppointmentHistory.php",
            data: {action: 'ListUser', appID: Id},
            success: function (msg) {
                // alert(msg);
                $('#PlaceUsersDataHere123').html(msg);
            }
        });

        return false;
    }
</script> 
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn blue btn-md pull-right" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Appointment History</h4>

            </div>
            <div class="modal-body">

                <span id="PlaceUsersDataHere123"></span>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn blue btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>








