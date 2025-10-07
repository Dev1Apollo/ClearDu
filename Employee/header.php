<?php
$MasterEntry = array("cmpendingapplication.php", "cmwithdrawcase.php", "cmviewwithdrawcase.php", "cmviewandreturncase.php");
$Agencymanagerallocation = array("ampendingapplication.php", "amreturncase.php", "amviewreturncase.php");
$CMcompleted = array("cmpickupdone.php", "cmrefusetopay.php", "cmptprescheduled.php", "cmcustomernotavailabel.php", "cmaddressnottracable.php");
$AMcompleted = array("ampickupdone.php", "amrefusetopay.php", "amptprescheduled.php", "amcustomernotavailabel.php", "amaddressnottracable.php");
$Ascompleted = array("aspickupdone.php", "asrefusetopay.php", "asptprescheduled.php", "ascustomernotavailabel.php", "asaddressnottracable.php");
$adminMasterEntry = array("cmState.php", "cmLocation.php", "cm-agency.php", "cmdistrict.php");
$AlloationEntry=array("TestCEUploadApplication.php", "deleteCEErrorApplication.php");
$map = array("employee_map.php","employee_map_new.php");
?>

<div class="page-header">
    <div class="page-header-top">
        <div class="container">
            <div class=" col-md-2">
                <a href="<?php echo $web_url; ?>Employee/index.php">
                    <!--<img src="<?php echo $web_url; ?>Employee/assets/images/logo.png" width="150px" alt="logo" class="logo-default">-->
                    <img src="../images/ClearDu.png" width="150px" style="margin: 5px 0" alt="logo" class="logo-default">
                </a>    
            </div>

            <script src="<?php echo $web_url; ?>Employee/assets/jquery-1.8.0.min.js"></script>

            <a href="javascript:;" class="menu-toggler"></a>
            <!--<div class=" pull-right">-->

            <!--    <img src="../images/herofincorp.png" width="100px" style="margin: 5px 0" alt="logo" class="logo-default">-->

            <!--</div>-->
            <div class=" pull-right">

                <!--<img src="../images/CREDSURE_LOGO_PNG-01.png" width="150px" style="margin: 5px 0" alt="logo" class="logo-default">-->
                <!--<img src="../images/ClearDu.png" width="150px" style="margin: 5px 0" alt="logo" class="logo-default">-->

            </div>
            <?php
            if ($_SESSION['Type'] != 'Company Employee') {
                ?>
                <div class="form-group col-md-2">
                    <input name="searchName" id="searchNameHeader" value="" placeholder="search"  class="margin-top-20 form-control"  required="">
                </div>
                <div class="col-md-6">
                    <a href="#" class=" btn blue margin-top-20" onclick="headerSearch();">Search</a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="page-header-menu">
        <div class="container">
            <div class="hor-menu">
                <ul class="nav navbar-nav">
                    <?php
                    if ($_SESSION['Type'] == 'Central Manager') {
                        ?>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (in_array(basename($_SERVER['REQUEST_URI']), $AlloationEntry)) {
                            echo
                            'active';
                        }
                        ?>">
                           
                              <a href="#">Upload Allocation</a>
                            <ul class="dropdown-menu pull-left">

                                <li>
                                    <a href="<?php echo $web_url; ?>Employee/TestCEUploadApplication.php">Upload Allocation</a>
                                </li> 
                                <li>
                                    <a href="<?php echo $web_url; ?>Employee/deleteCEErrorApplication.php">Delete Error Allocation</a>
                                </li> 
                            </ul>
                        </li>

                        
                        <!-- <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'autoassignfos.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/autoassignfos.php">Auto Assign Fos</a>
                        </li> -->
                        
                        

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'cmassigncase.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="cmassigncase.php">Assigned Cases</a>
                        </li>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'cm-monthclose.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/cm-monthclose.php">Month close</a>
                        </li>   

                        <?php
                        if ($_SESSION['centralmanager']['canaddagency'] == '1') {
                            ?>
                            <li class="menu-dropdown classic-menu-dropdown <?php
                            if (in_array(basename($_SERVER['REQUEST_URI']), $adminMasterEntry)) {
                                echo
                                'active';
                            }
                            ?>">
                                <a href="#">Master Entry</a>
                                <ul class="dropdown-menu pull-left">
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/cmState.php" class="nav-link">
                                            State
                                        </a>
                                    </li>
<!--                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/cmdistrict.php" class="nav-link">
                                            District
                                        </a>
                                    </li>                              -->
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/cmLocation.php" class="nav-link">
                                            Branch  <!--Location-->
                                        </a>
                                    </li>   

                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/cm-agency.php" class="nav-link">
                                            Agency
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/bankmaster.php" class="nav-link">
                                            Bank
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/productbankmapping.php" class="nav-link">
                                            Product Bank Mapping
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'cm-agencyuser.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>Employee/cm-agencyuser.php">Agency User</a>
                            </li> 
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'updatefosstatusbycm.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>Employee/updatefosstatusbycm.php">Update FOS Status</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'manualupdate.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>Employee/manualupdate.php">Paid Updation</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'bulkremoval.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>Employee/bulkremoval.php">Bulk Removal</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                            if (basename($_SERVER['REQUEST_URI']) == 'paidremoval.php') {
                                echo 'active';
                            }
                            ?>">
                                <a href="<?php echo $web_url; ?>Employee/paidremoval.php">Paid Removal</a>
                            </li>
                            
                            
                            
                            <li class="menu-dropdown classic-menu-dropdown <?php
                            if (in_array(basename($_SERVER['REQUEST_URI']), $map)) {
                                echo
                                'active';
                            }
                            ?>">
                                <a href="#">Map</a>
                                <ul class="dropdown-menu pull-left">
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/employee_map.php" class="nav-link">
                                            Employee Map
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo $web_url; ?>Employee/employee_map_new.php" class="nav-link">
                                            Employee Map New
                                        </a>
                                    </li>   

                                    
                                </ul>
                            </li>
                            
                            
                            <!--<li class="menu-dropdown classic-menu-dropdown <?php if (basename($_SERVER['REQUEST_URI']) == 'employee_map.php') { echo 'active'; } ?>">-->
                            <!--    <a href="<?php echo $web_url; ?>Employee/employee_map.php">Employee Map</a>-->
                            <!--</li>-->
                            <!--<li class="menu-dropdown classic-menu-dropdown <?php if (basename($_SERVER['REQUEST_URI']) == 'employee_map_new.php') { echo 'active'; } ?>">-->
                            <!--    <a href="<?php echo $web_url; ?>Employee/employee_map_new.php">Employee Map New</a>-->
                            <!--</li>-->
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'rejectedcases.php') {
                                    echo 'active';
                                }
                                ?>">
                                    <a href="<?php echo $web_url; ?>Employee/rejectedcases.php">Rejected Cases</a>
                            </li>
                            <li class="menu-dropdown classic-menu-dropdown  <?php
                                if (basename($_SERVER['REQUEST_URI']) == 'reassignrejectedcases.php') {
                                    echo 'active';
                                }
                                ?>">
                                    <a href="<?php echo $web_url; ?>Employee/reassignrejectedcases.php">Reassign Rejected Cases</a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['Type'] == 'Agency Manager') {
                        ?>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'amassigned.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/amassigned.php">Supervisor Wise Allocation</a>
                            <ul class="dropdown-menu pull-left">
                                <?php
                                $agencymanager = mysqli_query($dbconn, "SELECT * FROM `agencymanager` WHERE type='Agency supervisor' and  agencyname='".$_SESSION['agencyname']."'");
                                while ($rowid = mysqli_fetch_assoc($agencymanager)) {

                                    $strLocationID = '0';
                                    $user = mysqli_query($dbconn, "SELECT * FROM `agencymanagerlocation`  where  iagencymanagerid='" . $rowid['agencymanagerid'] . "' ");
                                    while ($userid = mysqli_fetch_array($user)) {
                                        $strLocationID = $userid['iLocationId'] . ',' . $strLocationID;
                                    }
                                    $strLocationID = rtrim($strLocationID, ", ");
                                   
                                    $totalapp = mysqli_fetch_assoc(mysqli_query($dbconn, "SELECT count(*) as TotalRow FROM  `application` where customer_city_id in(" . $strLocationID . ") and   is_assignto_as='1'  and   agencyid='" . $rowid['agencyname'] . "' and am_accaptance = '1'   "));
                                    ?>
                                    <li>
                                        <a href="#" class="nav-link"><?php echo $rowid['employeename'];echo "  " . $totalapp['TotalRow']; ?></a>
                                        <ul class="dropdown_2">
                                            <?php           
                                            
                                            $status = mysqli_query($dbconn, "SELECT *,(SELECT count(*) as TotalRow FROM `application` where fos_completed_status=fosstatusdrropdown.fosstatusdrropdownid and agencyid='" . $rowid['agencyname'] . "' and is_assignto_as='1'  and am_accaptance = '1') as count FROM `fosstatusdrropdown` GROUP BY fosstatusdrropdownid");
                                            while ($statusid = mysqli_fetch_assoc($status)) {
                                                ?>
                                                <li>
                                                    <a href="#"><?php echo $statusid['status']; echo " " . $statusid['count'] ?></a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </li>



                        <!--                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'amassigned.php') {
                            echo 'active';
                        }
                        ?>">
                                                    <a href="<?php echo $web_url; ?>Employee/amassigned.php">Supervisor Wise Allocation</a>
                                                </li>-->
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'amassignedtofos.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/amassignedtofos.php">FOS Wise Allocation</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'amManageuser.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/amManageuser.php">Manage User</a>
                        </li>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'am-annexure-excel-upload.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/am-annexure-excel-upload.php">Annexure Excel Upload</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown <?php if (basename($_SERVER['REQUEST_URI']) == 'employee_map.php') { echo 'active'; } ?>">
                            <a href="<?php echo $web_url; ?>Employee/employee_map.php">Employee Map</a>
                        </li>
                        <?php
                    }
                    ?>      
                    <?php
                    if ($_SESSION['Type'] == 'Agency supervisor') {
                        ?>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'asAllocation.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/asAllocation.php">Allocation</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'bulkassignfos.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/bulkassignfos.php">Bulk Allocation</a>
                        </li>
                        <!-- <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'asreturncase.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/asreturncase.php">Quick Return</a>
                        </li>   -->

                        


                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'asfosAssigned.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/asfosAssigned.php">Assigned To Fos</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'asReassignFos.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/asReassignFos.php">Reassign To Fos</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'downloadimages.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/downloadimages.php">Download Images</a>
                        </li> 
                        <li class="menu-dropdown classic-menu-dropdown <?php if (basename($_SERVER['REQUEST_URI']) == 'employee_map.php') { echo 'active'; } ?>">
                            <a href="<?php echo $web_url; ?>Employee/employee_map.php">Employee Map</a>
                        </li>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'rejectedcases.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/rejectedcases.php">Rejected Cases</a>
                        </li> 
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'reassignrejectedcases.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/reassignrejectedcases.php">Reassign Rejected Cases</a>
                        </li> 
                        <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['Type'] == 'Company Employee') {
                        ?>

                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'index.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="<?php echo $web_url; ?>Employee/index.php">Dashboard</a>
                        </li>
                        <?php
                    }
                    
                    if ($_SESSION['Type'] == 'State Manager') {
                        ?>
                        <li class="menu-dropdown classic-menu-dropdown  <?php
                        if (basename($_SERVER['REQUEST_URI']) == 'smassigncase.php') {
                            echo 'active';
                        }
                        ?>">
                            <a href="smassigncase.php">Assigned Cases</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>

            </div>
            <div class="hor-menu pull-right">
                <ul class="nav navbar-nav">
                    <li class="menu-dropdown classic-menu-dropdown active">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-user"></i>
                            <?php
                            if ($_SESSION['Type'] == 'Central Manager') {
                                ?>
                                <span class="username username-hide-mobile"><?php echo $_SESSION['employeeName']; ?></span>
                                <?php
                            } else if ($_SESSION['Type'] == 'Agency Manager') {
                                ?>
                                <span class="username username-hide-mobile"><?php echo $_SESSION['employeename']; ?></span>
                                <?php
                            } else if ($_SESSION['Type'] == 'Agency supervisor') {
                                ?>
                                <span class="username username-hide-mobile"><?php echo $_SESSION['employeename']; ?></span>
                                <?php
                            } else if ($_SESSION['Type'] == 'Company Employee') {
                                ?>
                                <span class="username username-hide-mobile"><?php echo $_SESSION['name']; ?></span>
                                <?php
                            }
                            ?>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/ChangePassword.php">
                                    <i class="icon-lock"></i>Change Password 
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo $web_url; ?>Employee/Logout.php">
                                    <i class="icon-key"></i>Log Out 
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>
    function headerSearch()
    {
        var check_list = $('#check_listHeader').val();
        var searchName = $('#searchNameHeader').val();
        window.location.href = "SearchApplication.php?q=" + searchName + "&check_list=" + check_list + "";
    }
</script>  

