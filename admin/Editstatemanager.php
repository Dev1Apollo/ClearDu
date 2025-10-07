<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');
$result = mysqli_query($dbconn,"SELECT * FROM `statemanager` WHERE `iStateManagerId`='" . $_REQUEST['token'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $filterStateBank = mysqli_query($dbconn,"SELECT * FROM `statemanageBank` where iStateManagerId='".$row['iStateManagerId']."' and isDelete='0'  and  istatus='1' order by iBankId asc");
    $bank = [];
    while($rawBank = mysqli_fetch_assoc($filterStateBank)){
        $bank[] = $rawBank['iBankId'];
    }
    
    $filterStateAgency = mysqli_query($dbconn,"SELECT * FROM `statemanageAgency` where iStateManagerId='".$row['iStateManagerId']."' and isDelete='0'  and  istatus='1' order by iAgencyId asc");
    $Agency = [];
    while($rawAgency = mysqli_fetch_assoc($filterStateAgency)){
        $Agency[] = $rawAgency['iAgencyId'];
    }
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Edit State Manager </title>
        <?php include_once 'include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
        </div>
        <div class="page-container">
            <div class="page-content-wrapper">

                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>admin/LocationEmployee.php">List Of State Manager</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Edit State Manager</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase"> Edit State Manager</span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="EditStatemanager" name="action" id="action">
                                                <input type="hidden" value="<?= $row['iStateManagerId'] ?>" name="iStateManagerId" id="iStateManagerId">
                                                <div class="form-body">

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Employee Name</label>
                                                        <input name="strEmployeeName" id="strEmployeeName"  class="form-control" value="<?= $row['strEmployeeName'] ?>" placeholder="Enter Your Employee Name" type="text" required="">
                                                    </div>
                                                    
                                                    <!--<div class="form-group col-md-4">-->
                                                    <!--    <label for="form_control_1">Can Edit Agency</label>-->
                                                        
                                                    <!--    <select name="canEditagency" id="canEditagency"  class="form-control">-->
                                                    <!--        <option value="0">NO</option>-->
                                                    <!--        <option value="1">YES</option>-->
                                                    <!--    </select>-->
                                                    <!--</div>-->
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Email</label>
                                                        <input name="strEmail" id="strEmail"  class="form-control" value="<?= $row['strEmail'] ?>" placeholder="Enter Your Email Address"  type="email">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Phone No.</label>
                                                        <input name="strPhoneNo" id="strPhoneNo"  class="form-control" value="<?= $row['strPhoneNo'] ?>" placeholder="Enter Your Phone No." pattern="[0-9]{11}" type="text">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Mobile No.</label>
                                                        <input name="strMobileNo" id="strMobileNo"  class="form-control" value="<?= $row['strMobileNo'] ?>" placeholder="Enter Your Mobile No." pattern="[7-9]{1}[0-9]{9}" type="text">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                        <input name="loginId" id="loginId"  class="form-control" value="<?= $row['loginId'] ?>" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                    </div> 
                                                    
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Bank</label><br/>
                                                        <?php
                                                        $sql_bank = "SELECT * FROM `bankmaster` where isDelete='0'  and  iStatus='1' order by strBankName asc";
                                                        $result_Bank = mysqli_query($dbconn, $sql_bank);
                                                        echo '<select class="form-control" name="Bank[]" id="Bank" required=""  multiple >';
                                                        //echo "<option value='' >Select Agency Name</option>";
                                                        while ($row_Bank = mysqli_fetch_array($result_Bank)) {
                                                            
                                                            if (in_array($row_Bank['iBankId'], $bank)){
                                                                echo "<option value='" . $row_Bank['iBankId'] . "' selected>" . $row_Bank['strBankName'] . "</option>";
                                                            } else {
                                                                echo "<option value='" . $row_Bank['iBankId'] . "'>" . $row_Bank['strBankName'] . "</option>";
                                                            }
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Agency</label><br/>
                                                        <?php
                                                        $querys = "SELECT * FROM `agency`  where isDelete='0'  and  istatus='1' order by  agencyname asc";
                                                        $results = mysqli_query($dbconn, $querys);
                                                        echo '<select class="form-control" name="Agency[]" id="Agency" required=""  multiple >';
                                                        //echo "<option value='' >Select Agency Name</option>";
                                                        while ($rows = mysqli_fetch_array($results)) {
                                                            
                                                            if (in_array($rows['Agencyid'], $Agency)){
                                                                echo "<option value='" . $rows['Agencyid'] . "' selected>" . $rows['agencyname'] . "</option>";
                                                            } else {
                                                                echo "<option value='" . $rows['Agencyid'] . "'>" . $rows['agencyname'] . "</option>";
                                                            }
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>
                                                    <!-- <div class="form-group col-md-4">
                                                        <label for="form_control_1">Branch</label><br/>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox"  onclick="javascript:CheckBranchAll();" id="check_branch_listall" class="md-check" value="">
                                                            <label for="check_branch_listall">Check All
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span>
                                                            </label>

                                                        </div>
                                                        <?php
                                                        $sql_menu = "SELECT * FROM `location` where isDelete='0'  and  istatus='1' order by locationId asc";
                                                        $result_menu = mysqli_query($dbconn, $sql_menu);
                                                        $i = 1;
                                                        while ($row_menu = mysqli_fetch_array($result_menu)) {
                                                            echo "<input type='checkbox' class='branchcheck' name='Location[]' value='" . $row_menu['locationId'] . "' id='Location[]'/>&nbsp" . $row_menu['locationName'];
                                                            $i++;
                                                            echo "<br />";
                                                        }
                                                        ?>  
                                                    </div> -->
                                                </div>
                                                <div class="form-actions noborder">
                                                    <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                    <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>

    <style>
        .multiselect {
            display: block;
            height: 35px;
            padding: 6px;
            text-align: left !important;
            line-height: 1.42857;
            /* color: #DFDFDF; */
            background-color: #fff;
            background-image: none;
            border: 1px solid #2E7FC1 !important;
            border-radius: 4px;
            color: #555555;
            font-size: 15px;
            font-weight: normal !important;
            text-transform: lowercase;
        }
    </style>
    <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>
        
        <script>
        
            $('#Agency').multiselect({
                nonSelectedText: 'Select Agency Name',
                includeSelectAllOption: true,
                buttonWidth: '100%',
                maxHeight: 250,
                enableFiltering: true
            });
            
            $('#Bank').multiselect({
                nonSelectedText: 'Select Bank',
                includeSelectAllOption: true,
                buttonWidth: '100%',
                maxHeight: 250,
                enableFiltering: true
            });
            
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
            
            // function CheckBranchAll(){
            //     if ($('#check_branch_listall').is(":checked"))
            //     {
            //         // alert('cheked');
            //         $('.branchcheck input[type=checkbox]').each(function () {
            //             $(this).prop('checked', true);
            //         });
            //     } else
            //     {
            //         //alert('cheked fail');
            //         $('.branchcheck input[type=checkbox]').each(function () {
            //             $(this).prop('checked', false);
            //         });
            //     }
            // }
        </script>
        <script type="text/javascript">


            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>admin/statemanager.php';
            }

            function chkLoginId(ID)
            {

                var q = $('#loginId').val();
                var iStateManagerId = $('#iStateManagerId').val();

                var urlp = '<?php echo $web_url; ?>admin/findstatemanagerEditLoginID.php?ID=' + q+'&iStateManagerId=' + iStateManagerId;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        if (data == 0)
                        {
                            $('#errordiv').html('');
                        } else
                        {
                            $('#errordiv').html(data);
                            $('#loginId').val('');
                        }
                    }
                }).error(function () {
                    alert('An error occured');
                });

            }

            $('#frmparameter').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>admin/querydata_statemanager.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        //alert(response);
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');
                        if (response == 2)
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Edited Sucessfully.');
                            window.location.href = '<?php echo $web_url; ?>admin/statemanager.php';

                        } else {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Invalid Request.');
                            window.location.href = '<?php echo $web_url; ?>admin/statemanager.php';
                        }
                    }

                });
            });

        </script>
    </body>
</html>