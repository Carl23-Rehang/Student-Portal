<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  $isadmin = 0;
  $adminroleid = "";
  //GET ADMIN ROLE ID
  $tquery1 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(isadmin))='" . strtolower(trim("1")) . "' AND active='1' ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $adminroleid = trim($trow1['roletypeid']);
    }
  }
  //
  //GET ADMIN ROLE ID
  $tquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(userrole))='" . strtolower(trim($adminroleid)) . "' AND active='1' ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $isadmin = 1;
    }
  }
  //
  if ($isadmin <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    exit();
  }
  //
  //
  $g_opt_emp = [];
  // GET OPTION EMPLOYEE
  $tquery1 = "SELECT * FROM tbl_clearance_tasklist WHERE active='1' ORDER BY taskname ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $tid = trim($trow1['tasklistid']);
      $tname = trim($trow1['taskname']);
      //
      if(trim($tid) != "" && trim($tname) != "") {
        $tv = [];
        $tv[count($tv)] = $tid;
        $tv[count($tv)] = $tname;
        $g_opt_emp[count($g_opt_emp)] = $tv;
      }
      //
    }
  }
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

  <title>Manage Settings</title>

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

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-xl-8 col-lg-8 col-md-8" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Settings</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>

                                  <div class="c-lbl-2-1"><b>Active Student S.Y. and Semester:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">S.Y.</td>
                                          <td style="border: 0px;"><?php echo $log_user_active_student_sy; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;"  data-toggle="modal" data-target="#modalUpdate_StudentActiveSYSem">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Semester</td>
                                          <td style="border: 0px;"><?php echo $log_user_active_student_sem; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;"  data-toggle="modal" data-target="#modalUpdate_StudentActiveSYSem">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Grade Encoding:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Grade Encoding</td>
                                          <td style="border: 0px;"><?php echo $setting_grade_encoding_allowed; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Grade Encoding','<?php echo $setting_grade_encoding_allowed; ?>','<?php echo $setting_settname_grade_encoding_allowed; ?>','','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Grade Encoding School Year</td>
                                          <td style="border: 0px;"><?php echo $setting_grade_encoding_allowed_sy; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Grade Encoding School Year','<?php echo $setting_grade_encoding_allowed_sy; ?>','<?php echo $setting_settname_grade_encoding_allowed_sy; ?>','','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Grade Encoding Semester</td>
                                          <td style="border: 0px;"><?php echo $setting_grade_encoding_allowed_sem; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Grade Encoding Semester','<?php echo $setting_grade_encoding_allowed_sem; ?>','<?php echo $setting_settname_grade_encoding_allowed_sem; ?>','','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Student Next Semester Enrollment:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Enrollment</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_enabled; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Enrollment','<?php echo $setting_enrollment_enabled; ?>','<?php echo $setting_settname_enrollment_enabled; ?>','<?php echo $setting_settdesc_enrollment_enabled; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enrollment School Year</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_sy; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment School Year','<?php echo $setting_enrollment_sy; ?>','<?php echo $setting_settname_enrollment_sy; ?>','<?php echo $setting_settdesc_enrollment_sy; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enrollment Semester</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_sem; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment Semester','<?php echo $setting_enrollment_sem; ?>','<?php echo $setting_settname_enrollment_sem; ?>','<?php echo $setting_settdesc_enrollment_sem; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enrollment Form Title</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_title; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment Form Title','<?php echo $setting_enrollment_title; ?>','<?php echo $setting_settname_enrollment_title; ?>','<?php echo $setting_settdesc_enrollment_title; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enrollment Result Message Success</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_msg_success; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment Result Message Success','<?php echo $setting_enrollment_msg_success; ?>','<?php echo $setting_settname_enrollment_msg_success; ?>','<?php echo $setting_settdesc_enrollment_msg_success; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enrollment Result Message Error</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_msg_error; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment Result Message Error','<?php echo $setting_enrollment_msg_error; ?>','<?php echo $setting_settname_enrollment_msg_error; ?>','<?php echo $setting_settdesc_enrollment_msg_error; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Restrict Enrollee By Previous Semester</td>
                                          <td style="border: 0px;"><?php echo $setting_enrollment_restrict_enrollee_by_prev_sem; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enrollment Result Message Error','<?php echo $setting_enrollment_restrict_enrollee_by_prev_sem; ?>','<?php echo $setting_settname_enrollment_restrict_enrollee_by_prev_sem; ?>','<?php echo $setting_settdesc_enrollment_restrict_enrollee_by_prev_sem; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Account Profile:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Base Path Employee</td>
                                          <td style="border: 0px;"><?php echo $setting_profilephoto_basepath_employee; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Base Path Employee','<?php echo $setting_profilephoto_basepath_employee; ?>','<?php echo $setting_settname_profilephoto_basepath_employee; ?>','<?php echo $setting_settdesc_profilephoto_basepath_employee; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Base Path Student</td>
                                          <td style="border: 0px;"><?php echo $setting_profilephoto_basepath_student; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Base Path Student','<?php echo $setting_profilephoto_basepath_student; ?>','<?php echo $setting_settname_profilephoto_basepath_student; ?>','<?php echo $setting_settdesc_profilephoto_basepath_student; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Default Password Type</td>
                                          <td style="border: 0px;"><?php echo $setting_default_pass_is_auto; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Base Path Student','<?php echo $setting_default_pass_is_auto; ?>','<?php echo $setting_settname_default_pass_is_auto; ?>','<?php echo $setting_settdesc_default_pass_is_auto; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Default Password Type</td>
                                          <td style="border: 0px;"><?php echo $setting_default_pass_type; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Default Password Type','<?php echo $setting_default_pass_type; ?>','<?php echo $setting_settname_default_pass_type; ?>','<?php echo $setting_settdesc_default_pass_type; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Default Password : Manual 1</td>
                                          <td style="border: 0px;"><?php echo $setting_default_pass_manual_value; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Default Password : Manual 1','<?php echo $setting_default_pass_manual_value; ?>','<?php echo $setting_settname_default_pass_manual_value; ?>','<?php echo $setting_settdesc_default_pass_manual_value; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Default Password : Manual 2</td>
                                          <td style="border: 0px;"><?php echo $setting_default_pass_manual_value_2; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Default Password : Manual 2','<?php echo $setting_default_pass_manual_value_2; ?>','<?php echo $setting_settname_default_pass_manual_value_2; ?>','<?php echo $setting_settdesc_default_pass_manual_value_2; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Clearance:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Clearance</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_allowed; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Clearance','<?php echo $setting_clearance_allowed; ?>','<?php echo $setting_settname_clearance_allowed; ?>','<?php echo $setting_settdesc_clearance_allowed; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Student Create Clearance</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_allow_student_create; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Student Create Clearance','<?php echo $setting_clearance_allow_student_create; ?>','<?php echo $setting_settname_clearance_allow_student_create; ?>','<?php echo $setting_settdesc_clearance_allow_student_create; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Clearance Field: Department Chairman</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_field_deptchair; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Clearance Field: Department Chairman','<?php echo $setting_clearance_field_deptchair; ?>','<?php echo $setting_settname_clearance_field_deptchair; ?>','<?php echo $setting_settdesc_clearance_field_deptchair; ?>','sett_name_2','sett_value','sett_id_2','sett_desc_2');" data-toggle="modal" data-target="#modalUpdateClearanceFieldDeptChair">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Clearance Field: College Dean</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_field_collegedean; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Clearance Field: College Dean','<?php echo $setting_clearance_field_collegedean; ?>','<?php echo $setting_settname_clearance_field_collegedean; ?>','<?php echo $setting_settdesc_clearance_field_collegedean; ?>','sett_name_3','sett_value','sett_id_3','sett_desc_3');" data-toggle="modal" data-target="#modalUpdateClearanceFieldCollegeDean">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Restrict S.Y. & Semester (STUDENT):</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_restrict_sysem_student; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Restrict S.Y. & Semester (STUDENT)','<?php echo $setting_clearance_restrict_sysem_student; ?>','<?php echo $setting_settname_clearance_restrict_sysem_student; ?>','<?php echo $setting_settdesc_clearance_restrict_sysem_student; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Restrict S.Y. & Semester (EMPLOYEE):</td>
                                          <td style="border: 0px;"><?php echo $setting_clearance_restrict_sysem_employee; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Restrict S.Y. & Semester (EMPLOYEE)','<?php echo $setting_clearance_restrict_sysem_employee; ?>','<?php echo $setting_settname_clearance_restrict_sysem_employee; ?>','<?php echo $setting_settdesc_clearance_restrict_sysem_employee; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>LRN Notification:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable LRN Notification</td>
                                          <td style="border: 0px;"><?php echo $setting_lrn_required; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable LRN Notification','<?php echo $setting_lrn_required; ?>','<?php echo $setting_settname_lrn_required; ?>','<?php echo $setting_settdesc_lrn_required; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">LRN Notification Year Level</td>
                                          <td style="border: 0px;"><?php echo $setting_lrn_required_yearlevel; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('LRN Notification Year Level','<?php echo $setting_lrn_required_yearlevel; ?>','<?php echo $setting_settname_lrn_required_yearlevel; ?>','<?php echo $setting_settdesc_lrn_required_yearlevel; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">LRN Notification Program Exemption</td>
                                          <td style="border: 0px;"><?php echo $setting_lrn_exemption_program_allowed; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('LRN Notification Program Exemption','<?php echo $setting_lrn_exemption_program_allowed; ?>','<?php echo $setting_settname_lrn_exemption_program_allowed; ?>','<?php echo $setting_settdesc_lrn_exemption_program_allowed; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Assessment Notification:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Assessment Notification</td>
                                          <td style="border: 0px;"><?php echo $setting_popup_assessment_required; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Assessment Notification','<?php echo $setting_popup_assessment_required; ?>','<?php echo $setting_settname_popup_assessment_required; ?>','<?php echo $setting_settdesc_popup_assessment_required; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Assessment Notification Program Type</td>
                                          <td style="border: 0px;"><?php echo $setting_popup_assessment_programtype; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Assessment Notification','<?php echo $setting_popup_assessment_programtype; ?>','<?php echo $setting_settname_popup_assessment_programtype; ?>','<?php echo $setting_settdesc_popup_assessment_programtype; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Student Request:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Student Request Items Per Page</td>
                                          <td style="border: 0px;"><?php echo $setting_default_request_rows; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Student Request Items Per Page','<?php echo $setting_default_request_rows; ?>','<?php echo $setting_settname_default_request_rows; ?>','<?php echo $setting_settdesc_default_request_rows; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>

                                  <div class="c-lbl-2-1"><b>Login Blocking:</b></div>
                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="c-tbl-setting-col-name-1" scope="col">Settings</th>
                                          <th class="c-tbl-setting-col-value-1" scope="col">Value</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <tr style="border: 0px;">
                                          <td style="border: 0px;">Enable Login Blocking</td>
                                          <td style="border: 0px;"><?php echo $setting_login_blocked_check; ?></td>
                                          <td style="border: 0px; text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="loadSettingToField2('Enable Login Blocking','<?php echo $setting_login_blocked_check; ?>','<?php echo $setting_settname_login_blocked_check; ?>','<?php echo $setting_settdesc_login_blocked_check; ?>','sett_name','sett_value','sett_id','sett_desc');" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>


                                  <!-- Modal -->
                                  <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                          <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Setting</h5>
                                          <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                          </button>
                                        </div>
                                          <form method="post">
                                        <div class="modal-body" style="font-size: 0.7rem;">
                                          <div align="left">

                                            <input type="hidden" id="sett_id" name="sett_id" value="" hidden />

                                            <div class="form-group margin-top1">
                                              <span class="label1"><span id="sett_name"></span> <span class="text-danger"></span></span>
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1" name="sett_value" id="sett_value" placeholder="Value" value="">
                                            </div>

                                            <div class="form-group">
                                              <span class="label1"><span id="sett_desc"></span> <span class="text-danger"></span></span>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsettsave" value="Save Setting" />
                                        </div>
                                          </form>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Modal -->
                                  <div class="modal fade" id="modalUpdateClearanceFieldDeptChair" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                          <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Setting</h5>
                                          <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                          </button>
                                        </div>
                                          <form method="post">
                                        <div class="modal-body" style="font-size: 0.7rem;">
                                          <div align="left">

                                            <input type="hidden" id="sett_id_2" name="sett_id" value="" hidden />

                                            <div class="form-group margin-top1">
                                              <span class="label1"><span id="sett_name_2"></span> <span class="text-danger"></span></span>
                                              <select class="form-control form-control-user  input-text-value font-size-o-1" name="sett_value" id="sett_value">
                                                <?php
                                                  for ($i=0; $i<count($g_opt_emp); $i++) {
                                                    $tsel = "";
                                                    if(trim($setting_clearance_field_deptchair) != "") {
                                                      if(trim(strtolower($setting_clearance_field_deptchair)) == trim(strtolower($g_opt_emp[$i][0]))) {
                                                        $tsel = " selected ";
                                                      }
                                                    }
                                                    //
                                                    echo '<option value="' . $g_opt_emp[$i][0] . '" ' . $tsel . ' >' . $g_opt_emp[$i][1] . '</option>';
                                                    //
                                                  }
                                                ?>
                                              </select>
                                            </div>

                                            <div class="form-group">
                                              <span class="label1"><span id="sett_desc_2"></span> <span class="text-danger"></span></span>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsettsave" value="Save Setting" />
                                        </div>
                                          </form>
                                      </div>
                                    </div>
                                  </div>

                                  <!-- Modal -->
                                  <div class="modal fade" id="modalUpdateClearanceFieldCollegeDean" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                          <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Setting</h5>
                                          <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                          </button>
                                        </div>
                                          <form method="post">
                                        <div class="modal-body" style="font-size: 0.7rem;">
                                          <div align="left">

                                            <input type="hidden" id="sett_id_3" name="sett_id" value="" hidden />

                                            <div class="form-group margin-top1">
                                              <span class="label1"><span id="sett_name_3"></span> <span class="text-danger"></span></span>
                                              <select class="form-control form-control-user  input-text-value font-size-o-1" name="sett_value" id="sett_value">
                                                <?php
                                                  for ($i=0; $i<count($g_opt_emp); $i++) {
                                                    $tsel = "";
                                                    if(trim($setting_clearance_field_collegedean) != "") {
                                                      if(trim(strtolower($setting_clearance_field_collegedean)) == trim(strtolower($g_opt_emp[$i][0]))) {
                                                        $tsel = " selected ";
                                                      }
                                                    }
                                                    //
                                                    echo '<option value="' . $g_opt_emp[$i][0] . '" ' . $tsel . ' >' . $g_opt_emp[$i][1] . '</option>';
                                                    //
                                                  }
                                                ?>
                                              </select>
                                            </div>

                                            <div class="form-group">
                                              <span class="label1"><span id="sett_desc_3"></span> <span class="text-danger"></span></span>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsettsave" value="Save Setting" />
                                        </div>
                                          </form>
                                      </div>
                                    </div>
                                  </div>


                                  <!-- Modal -->
                                  <div class="modal fade" id="modalUpdate_StudentActiveSYSem" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                          <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Student Active S.Y. and Semester</h5>
                                          <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                          </button>
                                        </div>
                                          <form method="post">
                                        <div class="modal-body" style="font-size: 0.7rem;">
                                          <div align="left">

                                            <input type="hidden" id="sett_id" name="sett_id" value="cus_active_sy" hidden />

                                            <div class="form-group margin-top1">
                                              <span class="label1"><span id="sett_name_sy">S.Y.</span> <span class="text-danger"></span></span>
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1" id="sett_value_sy" name="sett_value_sy" placeholder="S.Y." value="<?php echo $log_user_active_student_sy; ?>">
                                            </div>

                                            <div class="form-group margin-top1">
                                              <span class="label1"><span id="sett_name_sem">Semester</span> <span class="text-danger"></span></span>
                                              <select class="form-control form-control-user  input-text-value font-size-o-1" id="sett_value_sem" name="sett_value_sem" placeholder="Semester">
                                                <?php
                                                  //
                                                  $topt = "";
                                                  //
                                                  $tsel = "";
                                                  $tval = "1";
                                                  if(trim(strtolower($log_user_active_student_sem)) == trim(strtolower($tval))) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option values="' . $tval . '" ' . $tsel . ' >' . $tval . '</option>';
                                                  //
                                                  $tsel = "";
                                                  $tval = "2";
                                                  if(trim(strtolower($log_user_active_student_sem)) == trim(strtolower($tval))) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option values="' . $tval . '" ' . $tsel . ' >' . $tval . '</option>';
                                                  //
                                                  $tsel = "";
                                                  $tval = "S";
                                                  if(trim(strtolower($log_user_active_student_sem)) == trim(strtolower($tval))) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option values="' . $tval . '" ' . $tsel . ' >' . $tval . '</option>';
                                                  //
                                                  //
                                                  echo $topt;
                                                  //
                                                ?>
                                              </select>
                                            </div>

                                            <div class="form-group">
                                              <span class="label1"><span id="sett_desc_sysem"></span> <span class="text-danger"></span></span>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsettsave" value="Save Setting" />
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
    function loadSettingToField(name,val,tag,targetName,targetVal,targetTag){
      try{
        document.getElementById(targetName).innerHTML = name;
        document.getElementById(targetVal).value = val;
        document.getElementById(targetTag).value = tag;
      }catch(err){}
    }
    function loadSettingToField2(name,val,tag,desc,targetName,targetVal,targetTag,targetDesc){
      try{
        document.getElementById(targetName).innerHTML = name;
        document.getElementById(targetVal).value = val;
        document.getElementById(targetTag).value = tag;
        document.getElementById(targetDesc).innerHTML = desc;
      }catch(err){}
    }
  </script>


</body>

</html>
