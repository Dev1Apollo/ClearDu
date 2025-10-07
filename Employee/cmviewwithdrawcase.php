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
        <title><?php echo $ProjectName; ?> | View Withdraw Case</title>
        <?php include_once './include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url;?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
             
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url;?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>Withdraw Case</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">




                            
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption font-red-sunglo">
                                            <i class="icon-settings font-red-sunglo"></i>
                                            <span class="caption-subject bold uppercase">List of View Withdraw Case</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                        <input type="hidden" value="AddEmployeeLedger" name="action" id="action">

                                        <div class="form-body">
                                            <div class="row">


                                                <div class="form-group  col-md-4">
                                                    <label for="form_control_1">Select State</label>
                                                    <?php

                                                    
                                                    $querys = "SELECT * FROM `state`  where   isDelete='0'  and  istatus='1' order by  stateName asc";
                                                    $results = mysqli_query($dbconn, $querys);
                                                    echo '<select class="form-control" name="State" id="State" required="" onchange="getlocation();" >';
                                                    echo "<option value='' >Select State Name</option>";
                                                    while ($rows = mysqli_fetch_array($results)) {
                                                        echo "<option value='" . $rows['stateId'] . "'>" . $rows['stateName'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>

                                                <div class="col-md-4 ">
                                                    <label for="form_control_1">Location*</label>
                                                    <div id="locationDiv">
                                                    <select class="form-control" name="location" id="location" class="form-control" required="">
                                                        <option value="">--Select location--</option>

                                                    </select>  
                                                        </div>
                                                    
                                                </div>

                                            </div>
                                            <div class="form-actions noborder">
                                                <a href="#" class="btn blue " onclick="PageLoadData(1);">Search</a>
                                                <button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
                                                <a href="#" onclick="checkb4submit();" class="btn green pull-right margin-bottom-20"><i class="fa fa-file-excel-o"></i></a>
                                            </div>

                                        </div>


                                    </form>
                                        
                                        <div class="row">
                                         <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="TestUploadApplicationAdminEmployee" name="action" id="action">

<!--                                            <input type="hidden" name="aplicationID" id="aplicationID" value="<?php echo $_REQUEST['token']; ?>">
                                            <input type="hidden" name="CategoryID" id="CategoryID" value="<?php echo $_REQUEST['tokencatid']; ?>">
                                             <input type="hidden" name="companyID" id="companyID" value="<?php echo $_REQUEST['tokencomanyId']; ?>">-->

                                            <div class="form-body">

                                                <div class=" col-md-12">
<!--                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">Excel File Name</label><br />
                                                        <input type="text"  id="name" name="name"  required=""/>

                                                    </div>-->
                                                    <div class="form-group">
                                                        <label for="exampleInputFile1">Excel File Upload</label><br />
                                                        <input type="file"  id="gallery" name="gallery" class="btn blue" required=""/>
                                                        <input type="hidden" name="galeryID" ID="galeryID" />
                                                    </div>
                                                    <div id="ImageGallery" style="display:none;">  </div>
                                                </div>    

                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                <button type="button" class="btn blue " onClick="checkclose();">Cancel</button>
                                            </div>

                                        </form>
                                       
                                           <div class="col-md-6">
                                        <h4 style="color : #f03f2a; font-weight: bold" id="errorlog">

                                        </h4>
                                    </div>
                                             </div>
                                       
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
        
          <script type="text/javascript">

            function checkclose() {
                window.location.href = 'TestCEUploadApplication.php';
            }


            $('#frmparameter').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>Employee/cmreassignviewwithdrawcaseexcel.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        console.log(response);
                        $('#loading').css("display", "none");
                         //alert(response);
                        if (response == 0)
                        {
                            $('#loading').css("display", "none");
                            alert('Upload Application add Sucessfully.');
                            window.location.href = 'cmviewwithdrawcase.php';
                        } else {
                            $('#loading').css("display", "none");
                            $("#errorlog").html(response);
                            // window.location.href = '';
                        }
                    }

                });
            });




            $(document).ready(function ()
            {
                $("#gallery").on('change', function ()
                {
                     $('#loading').css("display", "block");
                    var galeryID = 0;
                    galeryID = galeryID + 1;

                    $("#galeryID").val(galeryID);
                    $("#ImageGallery").html('<img src="<?php echo $web_url; ?>Employee/images/loader1.gif" alt="Uploading...."/>');

                    var formData = new FormData($('form#frmparameter')[0]);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url; ?>Employee/uploadExcelTemp.php",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (msg) {
                              $('#loading').css("display", "none");
                            $("#ImageGallery").show();
                            $("#ImageGallery").html(msg);

                        },
                    });
                    // $("#addTreatmentForm").attr('action', '');
                });
            });

        </script>       
        
        
        
            <script type="text/javascript">


            function getlocation() {
                var State = $('#State').val();
                var urlp = '<?php echo $web_url; ?>Employee/findlocation.php?sid=' + State;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#locationDiv').html(data);
                    }

                }).error(function () {
                    alert('An error occured');
                });
            }

            function getagency() {
                var location = $('#location').val();
                var urlp = '<?php echo $web_url; ?>Employee/findagency.php?locationid=' + location;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#agencyid').html(data);
                    }

                }).error(function () {
                    alert('An error occured');
                });
            }
        </script>
        <script type="text/javascript">
            
            function PageLoadData(Page) {
                
                 var State = $('#State').val();
                var location = $('#location').val();
                var agencymanager = $('#agencymanager').val();
                
                
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url;?>Employee/Ajaxcmviewwithdrawcase.php",
                    data: {action: 'ListUser', Page: Page,State:State,location:location},
                    success: function (msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }
            PageLoadData(1);
            
             function checkb4submit()
            {

                 var State = $('#State').val();
                var location = $('#location').val();

                var strURL = "cmviewwithdrawcaseReportDownload.php?State=" + State + "&location=" + location;
                //alert(strURL);           
                window.open(strURL, '_blank');
            }

        </script>
<!--         <script type="text/javascript">




            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';
            }

            $('#frmparameterhold').submit(function (e) {
                e.preventDefault();
                var $form = $(this);
                var Status = $("#Status").val();


                valid = true;


                if (valid)
                {
                    $('#loading').css("display", "block");
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $web_url; ?>Employee/queryDataAdminEmployee.php',
                        data: $('#frmparameterhold').serialize(),
                        success: function (response) {
                            // alert(response);
                             if (response == 2)
                            {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Appointment Added Sucessfully.');
                            window.location.href = '<?php echo $web_url; ?>Employee/AllocatedApplicationAE.php';
                        }
                        }

                    });
                }
            });



        </script>-->
    </body>
</html>