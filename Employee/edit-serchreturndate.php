<?php
ob_start();
error_reporting(E_ALL);
include_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `application` WHERE `applicationid`='" . $_REQUEST['applicationid'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> |Edit Return Date</title>
        <?php include_once './include.php'; ?>
        </head> <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        

            <div class="page-content">
                <div class="container">                    
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="<?php echo $web_url; ?>Employee/LocationEmployee.php">List Of Edit Return Date</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span> Edit Return Date Case</span>

                        </li>
                    </ul>

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase"> Edit Return Date</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="Editreturndate" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['applicationid'] ?>" name="applicationid" id="applicationid">
                                            <div class="form-body">

                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Return Date</label>                                                    
                                                    <input type="text" id="returndate" name="returndate" value="<?php echo $row['excel_return_date']?>" class="form-control date-picker" placeholder="Enter The TO Date"/>
                                                </div>                                                                                            
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


        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

 <script type="text/javascript">

                                                    $(document).ready(function () {
                                                        $("#returndate").datepicker({
                                                            format: 'dd-mm-yyyy',
                                                            autoclose: true,
                                                            todayHighlight: true,
                                                            defaultDate: "now",
                                                            endDate: "now"
                                                        });
                                                    });


        </script>
    <script type="text/javascript">




        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>Employee/sercheditcustomercity.php';
        }




        $('#frmparameter').submit(function (e) {

            e.preventDefault();
            var $form = $(this);           
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>Employee/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function (response) {
                    // alert(response);
                   // console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response == 2)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        window.location.href = '<?php echo $web_url; ?>Employee/edit-returndate.php';
                    }
                    else{
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Date Not Updateted.');
                        window.location.href = '<?php echo $web_url; ?>admin/sercheditcustomercity.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
