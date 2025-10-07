<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $ProjectName; ?> | Generate Stable Customer</title>
    <?php include_once './include.php'; ?>
    <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
</head>

<body class="page-container-bg-solid page-boxed">
    <?php include_once './header.php'; ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content-wrapper">

            <div class="page-content">
                <div class="container">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span>Assign Case</span>
                        </li>
                    </ul>

                    <div class="page-content-inner">

                        <div class="portlet light ">
                            <div class="portlet-title">
                                <div class="caption font-red-sunglo">
                                    <i class="icon-settings font-red-sunglo"></i>
                                    <span class="caption-subject bold uppercase">List of Assign Case</span>
                                </div>
                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                            </div>
                            <div class="portlet-body form">
                                <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                    <div class="form-group  col-md-3">
                                        <label for="form_control_1">Agency</label>
                                        
                                        <?php
                                        $queryCom = "SELECT * FROM `agency`  where isDelete='0'  and  istatus='1' order by  agencyname asc";
                                        $resultCom = mysqli_query($dbconn, $queryCom);
                                        echo '<select class="form-control mdb-select" name="agency[]" id="agency" multiple="" >';                                  
                                        while ($rowCom = mysqli_fetch_array($resultCom)) {
                                            echo "<option value='" . $rowCom['Agencyid'] . "'>" . $rowCom['agencyname'] . "</option>";
                                        }
                                        echo "</select>";
                                        ?>
                                    </div>
                                    <div class="form-body">

                                        <div class="form-actions noborder">
                                            <a href="#" class="btn blue " onclick="PageLoadData(1);">Search</a>
                                            <button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
                                            <!--                                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>-->
                                        </div>
                                    </div>



                                </form>





                                <div id="PlaceUsersDataHere">
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>


    <?php include_once './footer.php'; ?>
    <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>
    <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>


    <style>
        .multiselect {
            display: block;
            width: 100%;

            text-align: left !important;
            line-height: 1.42857;
            /* color: #DFDFDF; */
            background-color: #fff;
            background-image: none;
            border: 1px solid #DFDFDF !important;
            border-radius: 4px;
            color: #555555;
            font-size: 15px;
            font-weight: normal !important;
            text-transform: lowercase;

        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
          //  PageLoadData(1);
        });
    </script>
    <script type="text/javascript">
        function PageLoadData(Page) {

            $('#loading').css("display", "block");
            var agency = $('#agency').val();
            if(agency == null )
            {
                agency=0;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Employee/AjaxgenStableApp.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    agency: agency
                },
                success: function(msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        } // end of filter
        $('#agency').multiselect({

            nonSelectedText: 'Select Agency',
            includeSelectAllOption: true,
            buttonWidth: '100%',
            searchable: true,
        });
    </script>

</body>

</html>