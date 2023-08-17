<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  include "access_check.php";
  //
  //
  $allow_clearance_add = 0;
  //
  $deny = 0;
  /*
  if( ((strtolower(trim($log_user_type)) == strtolower(trim("student")) && $setting_clearance_allow_student_create <= 0) || (strtolower(trim($log_user_type)) == strtolower(trim("employee")) && $log_user_role_isadmin <= 0)) && $setting_clearance_allowed <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  */
  //
  //
  if( strtolower(trim($log_user_type)) == strtolower(trim("student")) ) {
    //$deny++;
  }
  if( strtolower(trim($log_user_type)) == strtolower(trim("employee")) && $log_user_role_isadmin <= 0 ) {
    //$deny++;
  }
  if( $setting_clearance_allowed <= 0 ) {
    $deny++;
  }
  if($deny > 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  if( $setting_clearance_allowed > 0 ) {
    if( strtolower(trim($log_user_type)) == strtolower(trim("student")) && $setting_clearance_allow_student_create > 0 ) {
      $allow_clearance_add++;
    }
    if( strtolower(trim($log_user_type)) == strtolower(trim("employee")) && $log_user_role_isadmin > 0 && $setting_clearance_allow_admin_create > 0 ) {
      $allow_clearance_add++;
    }
  }
  //
  //
  if(trim($log_userid) != "" && $allow_clearance_add > 0) {
    if (trim(strtolower($log_user_type)) == trim(strtolower("student"))) {
      if($_POST['btnadd']) {
        $cprofid = trim($_POST['cprofid']);
        //
        //
        $csy = "";
        $csem = "";
        // GET SY SEM
        $fquery = "SELECT * FROM tbl_clearance_profile WHERE active='1' and TRIM(LOWER(profileid))='" . trim(strtolower($cprofid)) . "' ";
        $fresult = mysqli_query($conn, $fquery);
        if ($fresult) {
          while ($frow = mysqli_fetch_array($fresult)) {
            $csy = trim($frow['sy']);
            $csem = trim($frow['sem']);
          }
        }
        //
        $added_sy = "";
        $added_sem = "";
        $added_sy = trim($log_user_active_student_sy);
        $added_sem = trim($log_user_active_student_sem);
        //
        //
        $errn = 0;
        $errmsg = "";
        //
        if(trim($cprofid) == "") {
          $errn++;
          $errmsg = $errmsg . "Clearance type required. ";
        }
        //
        if($errn <= 0) {
          //echo $group . "  ". $description;
          //INSERT CLEARANCE
          $query = "INSERT INTO tbl_clearance (clearanceid,studid,addedby,sy,sem,added_sy,added_sem) VALUES ('" . $cprofid . "','" . trim($log_userid) . "','" . trim($log_userid) . "','" . $csy . "','" . $csem . "','" . $added_sy . "','" . $added_sem . "') ";
          $result = mysqli_query($conn,$query);
          if($result) {
            $cid = trim(mysqli_insert_id($conn));
            //echo $cid;
            //
            //INSERT ALL CLEARANCE TASK
            $fquery = "SELECT * FROM tbl_clearance_profile_tasks WHERE active='1' and TRIM(LOWER(profileid))='" . trim(strtolower($cprofid)) . "' ORDER BY norder ASC";
            $fresult = mysqli_query($conn, $fquery);
            if ($fresult) {
              while ($frow = mysqli_fetch_array($fresult)) {
                $tid = trim($frow['tasklistid']);
                $empid = trim($frow['empid']);
                $autoemployee = trim($frow['autoemployee']);
                $approv = trim($frow['approv']);
                $lipna = trim($frow['lockifprevnotapproved']);
                $lnina = trim($frow['locknextifnotapproved']);
                $unlockble = trim($frow['unlockble']);
                $norder = trim($frow['norder']);
                //
                if($autoemployee == "") {
                  $autoemployee = "0";
                }
                if($approv == "") {
                  $approv = "0";
                }
                if($lipna == "") {
                  $lipna = "0";
                }
                if($lnina == "") {
                  $lnina = "0";
                }
                if($unlockble == "") {
                  $unlockble = "0";
                }
                if($norder == "") {
                  $norder = "1";
                }
                //
                // AUTO EMPLOYEE GET
                if(trim(strtolower($autoemployee)) == trim(strtolower("1")) && trim($tid) != "") {
                  $tan = 0;
                  if(trim(strtolower($tid)) == trim(strtolower($setting_clearance_field_deptchair))) {
                    $tan++;
                    $empid = trim($log_user_department_chairman);
                  }
                  if(trim(strtolower($tid)) == trim(strtolower($setting_clearance_field_collegedean))) {
                    $tan++;
                    $empid = trim($log_user_college_dean);
                  }
                }
                //
                //
                if($tid != "") {
                  //INSERT CLEARANCE TASK
                  $query2 = "INSERT INTO tbl_clearance_tasks (clearanceid,tasklistid,empid,studid,autoemployee,approv,lockifprevnotapproved,locknextifnotapproved,unlockble,norder,addedby) VALUES 
                             ('" . $cid . "','" . $tid . "','" . $empid . "','" . trim($log_userid) . "','" . $autoemployee . "','" . $approv . "','" . $lipna . "','" . $lnina . "','" . $unlockble . "','" . $norder . "','" . trim($log_userid) . "') ";
                  $result2 = mysqli_query($conn,$query2);
                }
              }
            }
            //
          }
          //echo $result;
          //
          $dr = '
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> Clearance added.
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }else{
          $dr = '
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> ' . $errmsg . '
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
      }
      //
      if($_POST['btnupdate']) {
        $id = trim($_POST['id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $visible = trim($_POST['visible']);
        //
        if(trim($visible) == "") {
          $visible = "0";
        }
        //
        $errn = 0;
        $errmsg = "";
        //
        if(trim($name) == "") {
          $errn++;
          $errmsg = $errmsg . "Task required. ";
        }
        //
        if($errn <= 0) {
          //echo $id . "  " . $group . "  " . $description;
          $query = "UPDATE tbl_clearance_profile set profilename='" . $name . "',details='" . $description . "',isvisible='" . $visible . "' WHERE profileid='" . $id . "' ";
          $result = mysqli_query($conn,$query);
          //echo $result;
          //
          $dr = '
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> Profile updated.
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }else{
          $dr = '
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> ' . $errmsg . '
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
      }
      //
      if($_POST['btndelete']) {
        $id = trim($_POST['id']);
        //
        $errn = 0;
        $errmsg = "";
        //
        //
        if($errn <= 0) {
          //echo $group . "  ". $description;
          $query = "DELETE FROM tbl_clearance_profile  WHERE profileid='" . $id . "' ";
          $result = mysqli_query($conn,$query);
          //echo $result;
          //
          $dr = '
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> Profile deleted.
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }else{
          $dr = '
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> ' . $errmsg . '
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
      }
      //
    }
    //
    if (trim(strtolower($log_user_type)) == trim(strtolower("employee"))) {
      if($_POST['btnadde']) {
        //
        $cprofid = trim($_POST['cprofid']);
        $studid = trim($_POST['studid']);
        //
        //
        $errn = 0;
        $errmsg = "";
        //
        if(trim($cprofid) == "") {
          $errn++;
          $errmsg = $errmsg . "Clearance type required. ";
        }
        if(trim($studid) == "") {
          $errn++;
          $errmsg = $errmsg . "Student required. ";
        }
        //
        if($errn <= 0) {
          //echo $group . "  ". $description;
          //INSERT CLEARANCE
          $query = "INSERT INTO tbl_clearance (clearanceid,studid,addedby) VALUES ('" . $cprofid . "','" . trim($studid) . "','" . trim($log_userid) . "') ";
          $result = mysqli_query($conn,$query);
          if($result) {
            $cid = trim(mysqli_insert_id($conn));
            //echo $cid;
            //
            //INSERT ALL CLEARANCE TASK
            $fquery = "SELECT * FROM tbl_clearance_profile_tasks WHERE active='1' and TRIM(LOWER(profileid))='" . trim(strtolower($cprofid)) . "' ORDER BY norder ASC";
            $fresult = mysqli_query($conn, $fquery);
            if ($fresult) {
              while ($frow = mysqli_fetch_array($fresult)) {
                $tid = trim($frow['tasklistid']);
                $empid = trim($frow['empid']);
                $autoemployee = trim($frow['autoemployee']);
                $approv = trim($frow['approv']);
                $lipna = trim($frow['lockifprevnotapproved']);
                $lnina = trim($frow['locknextifnotapproved']);
                $unlockble = trim($frow['unlockble']);
                $norder = trim($frow['norder']);
                //
                if($autoemployee == "") {
                  $autoemployee = "0";
                }
                if($approv == "") {
                  $approv = "0";
                }
                if($lipna == "") {
                  $lipna = "0";
                }
                if($lnina == "") {
                  $lnina = "0";
                }
                if($unlockble == "") {
                  $unlockble = "0";
                }
                if($norder == "") {
                  $norder = "1";
                }
                //
                if($tid != "") {
                  //INSERT CLEARANCE TASK
                  $query2 = "INSERT INTO tbl_clearance_tasks (clearanceid,tasklistid,empid,studid,autoemployee,approv,lockifprevnotapproved,locknextifnotapproved,unlockble,norder,addedby) VALUES 
                             ('" . $cid . "','" . $tid . "','" . $empid . "','" . trim($studid) . "','" . $autoemployee . "','" . $approv . "','" . $lipna . "','" . $lnina . "','" . $unlockble . "','" . $norder . "','" . trim($log_userid) . "') ";
                  $result2 = mysqli_query($conn,$query2);
                }
              }
            }
            //
            //
            $dr = '
              <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
                <strong></strong> Clearance added.
                <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
                </button>
              </div>
            ';
            //
          }
          //echo $result;
        }else{
          $dr = '
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> ' . $errmsg . '
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
      }
    }
    //
    //
    //LOAD ITEMS FOR CLEARANCE PROFILE
    //
    $tq = "";
    //
    $tq = " ( sy IS NULL ) OR ( sem IS NULL ) OR ( TRIM(sy)='' ) OR ( TRIM(sem)='' )  OR ( TRIM(UPPER(sy))=TRIM(UPPER('" . $log_user_active_student_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $log_user_active_student_sem . "')) )";
    // AQ : USER TYPE
    $tq = " ( " . $tq . " ) AND " . " ( TRIM(UPPER(forusertype))=TRIM(UPPER('" . $log_user_type . "')) OR TRIM(UPPER(forusertype))=TRIM(UPPER('')) OR forusertype IS NULL ) ";
    // AQ FINALIZE
    if(trim($tq) != "") {
      $tq = " AND ( " . $tq . " ) ";
    }
    //
    $n = 0;
    $lquery2 = "SELECT * FROM tbl_clearance_profile WHERE isvisible='1' " . $tq . " ORDER BY profilename ASC";
    $lresult2 = mysqli_query($conn, $lquery2);
    if ($lresult2) {
      while ($lrow2 = mysqli_fetch_array($lresult2)) {
        $tid = trim($lrow2['profileid']);
        $tname = trim($lrow2['profilename']);
        if($tid != "" && $tname != "") {
          $gd_items_cprof = $gd_items_cprof . '
                  <input type="hidden" id="cprof_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="cprof_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'cprof','cprofid','cprofname'" . ')">' . $tname . '</a>
                ';
          //
          $gd_items_cprof_u = $gd_items_cprof_u . '
                  <input type="hidden" id="cprofu_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="cprofu_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'cprofu','cprofidu','cprofnameu'" . ')">' . $tname . '</a>
                ';
          $n++;
        }
      }
    }
    //END LOAD ITEMS FOR CLEARANCE PROFILE
    //
    //
  } //END CHECK USER
  //
  //
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Clearance</title>

  <?php include "header-imports.php"; ?>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include "header.php"; ?>
        <!-- End of Topbar -->


          <!-- Begin Page Content -->
          <div class="container-fluid" style="padding-left: 10px;padding-right: 10px;">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"></h1>
            </div>



            <!-- Content Row -->
            <div class="row">
              <div class="col-sm-3" style="">
                <div align="left">
                  <?php
                    if (trim(strtolower($log_user_type)) == trim(strtolower("employee"))) {
                      echo '
                          <form method="get">
                            <div class="form-group margin-top1" >
                              <span class="label1 c-lbl-1-1">Search: <span class="text-danger"></span></span><br/>
                              <input type="text" class="c-input-1" style="margin-right: 0px;"  name="s" id="s" placeholder="Search" value="' . $_GET['s'] . '" >
                              <input type="submit" class="btn btn-success btn-c-2 bg-2" style="display: inline-block; margin-left: 0px;" value="Search">
                            </div>
                          </form>
                      ';
                    }
                  ?>
                </div>
              </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="left">

                          <?php
                            if (trim(strtolower($log_user_type)) == trim(strtolower("student")) && $allow_clearance_add > 0) {
                              echo '
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" style="font-size: 0.6rem; border-radius: 0px; border-top-right-radius: 16px; border-bottom-right-radius: 16px; margin-left: 12px; margin-bottom: 12px;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD CLEARANCE</b></button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                        <h5 class="modal-title" id="" style="font-size: 0.8rem;">Add New Clearance</h5>
                                        <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body" style="font-size: 0.7rem;">
                                        <div align="left">

                                          <div class="form-group" >
                                            <span class="label1">Clearance For: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="cprofid" id="cprofid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="cprofname" id="cprofname" value=""  onkeyup="filterFunction(' ."'cprofname','cprofItems'" . ')" placeholder="Clearance Type" onfocus="elementShowHide(' ."'cprofItems'". ')" onclick="elementShowHide(' ."'cprofItems'" . ')" required>
                                              <div id="cprofItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  ' . $gd_items_cprof . '
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnadd" value="Add Clearance" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                              ';
                            }
                            if (trim(strtolower($log_user_type)) == trim(strtolower("employee")) && $allow_clearance_add > 0) {
                              echo '
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" style="font-size: 0.6rem; border-radius: 0px; border-top-right-radius: 16px; border-bottom-right-radius: 16px; margin-left: 12px; margin-bottom: 12px;" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#modalAdd" onclick="LoadStudentOption_Clearance_Add();"><i class="fas fa-plus fa-sm"></i> <b>CREATE STUDENT CLEARANCE</b></button>

                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                        <h5 class="modal-title" id="" style="font-size: 0.8rem;">Add New Student Clearance</h5>
                                        <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body" style="font-size: 0.7rem;">
                                        <div align="left">

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Clearance For: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="cprofid" id="cprofid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="cprofname" id="cprofname" value=""  onkeyup="filterFunction(' ."'cprofname','cprofItems'" . ')" placeholder="Clearance Type" onfocus="elementShowHide(' ."'cprofItems'". ')" onclick="elementShowHide(' ."'cprofItems'" . ')" required>
                                              <div id="cprofItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  ' . $gd_items_cprof . '
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Student: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="studid" id="studid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="studname" id="studname" value=""  onkeyup="filterFunction(' ."'studname','studItems'" . ')" placeholder="Student" onfocus="elementShowHide(' ."'studItems'". ')" onclick="elementShowHide(' ."'studItems'" . ')" required>
                                              <div id="studItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div id="opt_clearance_add_stud_list" class="div-text-filter-holder-wrapper-1">
                                                  
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnadde" value="Add Clearance" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                              ';
                            }
                          ?>
                                

                        </div>

                      </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                      <div class="col-sm-12" style="padding-left: 12px;padding-right: 12px;">

                        <div align="left">

                          <div class="row">

                          <?php
                            //
                            $res_ongoing = "";
                            $res_complete = "";
                            //
                            // CHECK IF STUDENT
                            if (trim(strtolower($log_user_type)) == trim(strtolower("student"))) {
                              //
                              $n = 0;
                              $n2 = 0;
                              //
                              $res_ongoing = "";
                              $res_complete = "";
                              //
                              $aq_sysem = "";
                              if ( $setting_clearance_restrict_sysem_student > 0 ) {
                                $aq_sysem = " AND ( ( TRIM(UPPER(a.sy))=TRIM(UPPER('" . $log_user_active_student_sy . "')) AND TRIM(UPPER(a.sem))=TRIM(UPPER('" . $log_user_active_student_sem . "')) ) OR ( TRIM(UPPER(a.sy))=TRIM(UPPER('')) OR a.sy IS NULL OR TRIM(UPPER(a.sem))=TRIM(UPPER('')) OR a.sem IS NULL ) ) ";
                              }
                              //
                              //
                              $ntasks = 0;
                              $napprove = 0;
                              //
                              $query = "SELECT 
                                          a.id,b.profilename,b.details  
                                          FROM tbl_clearance AS a 
                                          LEFT JOIN tbl_clearance_profile AS b ON b.profileid=a.clearanceid 
                                          WHERE a.active='1' AND TRIM(UPPER(a.studid))='" . trim(strtoupper($log_userid)) . "' " . $aq_sysem . " 
                                          ORDER BY a.entrydate DESC, b.profilename ASC ";
                              $result = mysqli_query($conn, $query);
                              if ($result) {
                                while ($row = mysqli_fetch_array($result)) {
                                  //
                                  //
                                  $tid = trim($row['id']);
                                  $tname = trim($row['profilename']);
                                  $tdesc = trim($row['details']);
                                  $tlink = "./clearance-tasks.php?cid=" . trim($tid);
                                  //
                                  //
                                  $ticon = " fas fa-certificate ";
                                  $ticonstyle = ' style="color: #dddfeb;" ';
                                  $tstat = "";
                                  //
                                  //GET TASK COUNT
                                  $squery = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($tid)) . "' AND TRIM(UPPER(studid))='" . trim(strtoupper($log_userid)) . "' ";
                                  $sresult = mysqli_query($conn, $squery);
                                  if ($sresult) {
                                    $ntasks = mysqli_num_rows($sresult);
                                  }
                                  //END GET TASK COUNT
                                  //GET APPROVED TASK COUNT
                                  $squery2 = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($tid)) . "' AND TRIM(UPPER(studid))='" . trim(strtoupper($log_userid)) . "' AND approv='1' ";
                                  $sresult2 = mysqli_query($conn, $squery2);
                                  if ($sresult2) {
                                    $napprove = mysqli_num_rows($sresult2);
                                  }
                                  //END GET APPROVED  TASK COUNT
                                  //
                                  if($napprove >= $ntasks) {
                                    $ticon = "far fa-check-circle";
                                    $ticonstyle = ' style="color: #28a745;" ';
                                  }else{
                                    if($napprove < $ntasks) {
                                      $tstat = ' <br/> <span style="color: #dddfeb; font-size: 0.6rem; position: absolute;">' . $napprove . ' / ' . $ntasks . '</span> ';
                                    }
                                  }
                                  //
                                  //
                                  if($tid != "" && $tname != "") {
                                    //
                                    //IF ON-GOING
                                    if($napprove < $ntasks) {
                                      //
                                      $n++;
                                      //
                                      $ticon = " fas fa-certificate ";
                                      $ticonstyle = ' style="color: #dddfeb;" ';
                                      //
                                      $tstat = ' <br/> <span style="color: #dddfeb; font-size: 0.6rem; position: absolute;">' . $napprove . ' / ' . $ntasks . '</span> ';
                                      //
                                      $res_ongoing = $res_ongoing . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card border-left-primary shadow h-100 py-2">
                                            <a class="link-card-1" href="' . $tlink . '">
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 54px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . trim($tname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-weight: normal; font-style: italic; font-size: 0.7rem;">' . trim($tdesc) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i> ' . $tstat . '
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                      ';
                                    }
                                    //END IF ON-GOING
                                    //IF COMPLETED
                                    if($napprove >= $ntasks) {
                                      //
                                      $n2++;
                                      //
                                      $ticon = "far fa-check-circle";
                                      $ticonstyle = ' style="color: #28a745;" ';
                                      //
                                      $tstat = '';
                                      //
                                      $res_complete = $res_complete . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card border-left-primary shadow h-100 py-2">
                                            <a class="link-card-1" href="' . $tlink . '">
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 54px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . trim($tname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-weight: normal; font-style: italic; font-size: 0.7rem;">' . trim($tdesc) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i> ' . $tstat . '
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                      ';
                                    }
                                    //END IF COMPLETED
                                    //
                                  }
                                }
                              }
                              //
                              //RESULT
                              // SHOW RESULT
                              if($n > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>On-going</b></div>
                                      </div>
                                ';
                                // END TILE
                                // SHOW RESULT
                                echo $res_ongoing;
                              }
                              if($n2 > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>Completed</b></div>
                                      </div>
                                ';
                                // END TILE
                                // SHOW RESULT
                                echo $res_complete;
                              }
                              //
                            } // END CHECK USER TYPE : STUDENT
                            ///
                            //
                            //
                            //
                            // CHECK IF EMPLOYEE
                            if (trim(strtolower($log_user_type)) == trim(strtolower("employee"))) {
                              //
                              //
                              $search = trim($_GET['s']);
                              //
                              $n = 0;
                              $n2 = 0;
                              //
                              $res_ongoing = "";
                              $res_complete = "";
                              //
                              $aq_sysem = "";
                              if ( $setting_clearance_restrict_sysem_employee > 0 ) {
                                $aq_sysem = " AND ( ( TRIM(UPPER(a.sy))=TRIM(UPPER('" . $log_user_active_sy . "')) AND TRIM(UPPER(a.sem))=TRIM(UPPER('" . $log_user_active_sem . "')) ) OR ( TRIM(UPPER(a.sy))=TRIM(UPPER('')) OR a.sy IS NULL OR TRIM(UPPER(a.sem))=TRIM(UPPER('')) OR a.sem IS NULL ) ) ";
                              }
                              //
                              //
                              $query = "SELECT 
                                          a.id,a.studid,b.profilename,b.details  
                                          FROM tbl_clearance AS a 
                                          LEFT JOIN tbl_clearance_profile AS b ON b.profileid=a.clearanceid 
                                          LEFT JOIN tbl_clearance_tasks AS c ON c.clearanceid=a.id 
                                          WHERE a.active='1' AND TRIM(UPPER(c.empid))='" . trim(strtoupper($log_userid)) . "' " . $aq_sysem . " 
                                          GROUP BY c.clearanceid ORDER BY b.profilename ASC ";
                              $result = mysqli_query($conn, $query);
                              if ($result) {
                                while ($row = mysqli_fetch_array($result)) {
                                  $tid = trim($row['id']);
                                  $tname = trim($row['profilename']);
                                  $tdesc = trim($row['details']);
                                  $tstudid = trim($row['studid']);
                                  //
                                  $tlink = "./clearance-tasks.php?cid=" . trim($tid);
                                  $tlinkcomp = "./student-clearance.php?cid=" . trim($tid) . "&studid=" . $tstudid . "&bt=c";
                                  //
                                  //
                                  $ticon = " fas fa-certificate ";
                                  $ticonstyle = ' style="color: #dddfeb;" ';
                                  $tstat = "";
                                  //
                                  $tstudname = "";
                                  // GET STUDENT
                                  $squery = "SELECT studfullname FROM srgb.student WHERE  TRIM(UPPER(studid))='" . trim(strtoupper($tstudid)) . "' ";
                                  $sresult = pg_query($pgconn, $squery);
                                  if ($sresult) {
                                    while ($srow = pg_fetch_array($sresult)) {
                                      $tstudname = trim($srow['studfullname']);
                                    }
                                  }
                                  //
                                  //
                                  //GET TASK COUNT
                                  $squery = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($tid)) . "' AND TRIM(UPPER(empid))='" . trim(strtoupper($log_userid)) . "' ";
                                  $sresult = mysqli_query($conn, $squery);
                                  if ($sresult) {
                                    $ntasks = mysqli_num_rows($sresult);
                                  }
                                  //END GET TASK COUNT
                                  //GET APPROVED TASK COUNT
                                  $squery2 = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($tid)) . "' AND TRIM(UPPER(empid))='" . trim(strtoupper($log_userid)) . "' AND approv='1' ";
                                  $sresult2 = mysqli_query($conn, $squery2);
                                  if ($sresult2) {
                                    $napprove = mysqli_num_rows($sresult2);
                                  }
                                  //END GET APPROVED  TASK COUNT
                                  //
                                  if($napprove >= $ntasks) {
                                    $ticon = "far fa-check-circle";
                                    $ticonstyle = ' style="color: #28a745;" ';
                                  }else{
                                    if($napprove < $ntasks) {
                                      $tstat = ' <br/> <span style="color: #dddfeb; font-size: 0.6rem; position: absolute;">' . $napprove . ' / ' . $ntasks . '</span> ';
                                    }
                                  }
                                  //
                                  $tshow = 1;
                                  // SEARCH
                                  if(trim($search) != "") {
                                    $ttn = 0;
                                    //echo $search;
                                    if(strpos(strtolower($tname), strtolower($search)) !== false) {
                                      $ttn++;
                                    }
                                    if(strpos(strtolower($tdesc), strtolower($search)) !== false) {
                                      $ttn++;
                                    }
                                    if(strpos(strtolower($tstudid), strtolower($search)) !== false) {
                                      $ttn++;
                                    }
                                    if(strpos(strtolower($tstudname), strtolower($search)) !== false) {
                                      $ttn++;
                                    }
                                    //
                                    if($ttn <= 0) {
                                      $tshow = 0;
                                    }
                                  }
                                  //
                                  //
                                  if($tid != "" && $tname != "" && $tshow > 0) {
                                    //
                                    //IF ON-GOING
                                    if($napprove < $ntasks) {
                                      //
                                      $n++;
                                      //
                                      $res_ongoing = $res_ongoing . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card border-left-primary shadow h-100 py-2">
                                            <a class="link-card-1" href="' . $tlink . '">
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 54px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . trim($tstudname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-weight: normal; font-style: italic; font-size: 0.7rem;">' . trim($tname) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i> ' . $tstat . '
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                      ';
                                    }
                                    //END IF ON-GOING
                                    //IF COMPLETED
                                    if($napprove >= $ntasks) {
                                      //
                                      $n2++;
                                      //
                                      $res_complete = $res_complete . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card border-left-primary shadow h-100 py-2">
                                            <a class="link-card-1" href="' . $tlinkcomp . '">
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 54px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">' . trim($tstudname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800" style="font-weight: normal; font-style: italic; font-size: 0.7rem;">' . trim($tname) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i> ' . $tstat . '
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                      ';
                                    }
                                    //END IF COMPLETED
                                    //
                                  }
                                }
                              }
                              //
                              // RESULT
                              // SHOW RESULT
                              if($n > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>On-going</b></div>
                                      </div>
                                ';
                                // END TILE
                                // SHOW RESULT
                                echo $res_ongoing;
                              }
                              if($n2 > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>Fully Approved</b></div>
                                      </div>
                                ';
                                // END TILE
                                // SHOW RESULT
                                echo $res_complete;
                              }
                              //
                            } // END CHECK USER TYPE : EMPLOYEE
                            //
                          ?>
                          <!--  -->

                          </div>

                        </div>

                      </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          
                        </div>

                      </div>

            </div>



            </div>

          <!-- Content Row -->

          <div class="row">

          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-sm-12">
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            </div>
          </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include "footer.php"; ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>


  <?php include "footer-imports.php"; ?>

  <script>
    
    function LoadStudentOption_Clearance_Add() {
      try{
        //
        var tar = document.getElementById('opt_clearance_add_stud_list');
        //
        try{
          var cs = 'get-student-list-clearance.php?t=a';
          $.get(cs, function(data) {
            //
            tar.innerHTML  = data;
            //
          });

        }catch(err){}
        //
      }catch(err){}
    }

  </script>

</body>

</html>
