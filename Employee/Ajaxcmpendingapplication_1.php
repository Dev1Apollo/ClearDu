<?php
error_reporting(0);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    
   $where = "where 1=1";

     if ($_REQUEST['State'] != NULL && isset($_REQUEST['State']) && $_REQUEST['location'] != NULL && isset($_REQUEST['location']))
         
         $state= mysqli_fetch_array (mysqli_query ($dbconn,"SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='".$_REQUEST['State']."' order by  stateName asc"));
         $location= mysqli_fetch_array (mysqli_query ($dbconn,"select * from location  where  istatus='1' and isDelete='0' and locationId='".$_REQUEST['location']."' order by locationId ASC"));
         
        $where.=" and STATE  = '" . $state['stateName'] . "' and location ='". $location['locationName'] . "'";
   


     $filterstr = "SELECT * FROM `application`  " . $where . " and is_assignto_am='0' " ;
    $countstr = "SELECT count(*) as TotalRow FROM  `application` " . $where . " and is_assignto_am='0' ";

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
 <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>

        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <form  role="form"  method="POST"  action="" name="frmparameterrr"  id="frmparameterrr" enctype="multipart/form-data">
            <input type="hidden" value="cmpendingapplication" name="action" id="action">
            <div class="form-group col-md-4">
                
                <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                <input class="btn blue " type="hidden"   value="<?php echo $_REQUEST['agencymanager']  ?>" name="agencymanagerid" id="agencymanagerid" >  
                
            </div>
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">

                <thead class="tbg">
                    <tr>
                           <th>
                            <div class="md-checkbox">
                                <input type="checkbox"  onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                <label for="check_listall">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>

                            </div>
                        </th>
                    <th></th>
                    <th class="all">LAN NUMBER</th>
                    <th class="all">PRODUCT</th>
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
                                <!--                                <div class="form-group form-md-line-input ">-->
                                <!--                                    <div class="form-group form-md-checkboxes">-->
                                <!--                                        <div class="md-checkbox-inline">-->
                                <div class="md-checkbox">
                                    <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check " value="<?php echo $rowfilter['applicationid']; ?> ">
                                    <label for="check_list<?php echo $i; ?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                                <!--                                         </div>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
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
        </form>

        <script type="text/javascript">


            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/cmwithdrawcase.php';
            }

            $('#frmparameterrr').submit(function (e) {

                e.preventDefault();
                var $form = $(this);


                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                    data: $('#frmparameterrr').serialize(),
                    success: function (response) {
                      alert(response);
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('assign Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>Employee/cmwithdrawcase.php';
                                    }
                                });
                            });





        </script> 

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


  <script>
        function CheckAll()
        {
            
            if ($('#check_listall').is(":checked"))
            {
                // alert('cheked');
                $('input[type=checkbox]').each(function () {
                    $(this).prop('checked', true);
                });
            } else
            {
                //alert('cheked fail');
                $('input[type=checkbox]').each(function () {
                    $(this).prop('checked', false);
                });
            }
        }
    </script>






