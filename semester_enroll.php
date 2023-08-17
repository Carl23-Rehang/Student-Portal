<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  //
  if(trim($log_userid) == "") {
    echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    exit();
  }
  // CHECK ENROL ALLOWED
  if($setting_enrollment_enabled <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  if($setting_enrollment_show <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  // CHECK IF STUDENT
  if (strtolower(trim($log_user_type)) != strtolower(trim("student"))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  // CHECK ENROLL STATUS
  include_once "semester_enroll_check.php";
  $log_use_enroll_stat = trim($_SESSION[$appid . "c_user_enroll_stat"]);
  if($log_use_enroll_stat > 0) {
    echo '<meta http-equiv="refresh" content="0;URL=semester_enroll_status.php" />';
    exit();
  }
  //
  //
  // GET EXISTING DATA
  $ed_ln = "";
  $ed_fn = "";
  $ed_mn = "";
  $ed_ext = "";
  //ESMS
  $qry = "SELECT * from srgb.student WHERE TRIM(UPPER(studid))=TRIM(UPPER('" . $log_userid . "')) LIMIT 1  ";
  $result = pg_query($pgconn, $qry);
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $ed_ln = trim($row['studlastname']);
      $ed_fn = trim($row['studfirstname']);
      $ed_mn = trim($row['studmidname']);
      $ed_ext = trim($row['studsuffix']);
      //
    }
  }
  //
  //
  //
  $gender = "";
  $opt_sexorient = "";
  $opt_nhgender = "";
  //LOAD GENDER
  $sql = " select * from tblgender order by gender ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['gender']) != "") {
      $cv = "<option value='" . trim($dat['gender']) . "'>" . trim($dat['gender']) . "</option>";
      if(trim($gender) == "") {
        $gender = $cv;
      }else{
        $gender = $gender . $cv;
      }
    }
    //
  }
  //LOAD SEX ORIENT
  $sql = " select * from tblsexualorientation order by orientation ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['orientation']) != "") {
      $cv = "<option value='" . trim($dat['orientation']) . "'>" . trim($dat['orientation']) . "</option>";
      if(trim($opt_sexorient) == "") {
        $opt_sexorient = $cv;
      }else{
        $opt_sexorient = $opt_sexorient . $cv;
      }
    }
    //
  }
  //LOAD NH GENDER
  $opt_nhgender = $opt_nhgender . "<option value=''></option>";
  $sql = " select * from tblnhgender order by gender ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['gender']) != "") {
      $cv = "<option value='" . trim($dat['gender']) . "'>" . trim($dat['gender']) . "</option>";
      if(trim($opt_nhgender) == "") {
        $opt_nhgender = $cv;
      }else{
        $opt_nhgender = $opt_nhgender . $cv;
      }
    }
    //
  }
  //
  //
  $incomerange = "";
  //LOAD INCOME
  $sql = " select * from tblincomerange order by id ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['incomemin']) != "" && trim($dat['incomemax']) != "") {
      $v = trim($dat['incomemin']) . " - " . trim($dat['incomemax']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($incomerange) == "") {
        $incomerange = $cv;
      }else{
        $incomerange = $incomerange . $cv;
      }
    }
    //
  }
  //
  //
  $opt_civilstatus = "";
  //LOAD CIVIL STATUS
  $sql = " select * from tblcivilstatus ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['civilstatus']) != "") {
      $v = trim($dat['civilstatus']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_civilstatus) == "") {
        $opt_civilstatus = $cv;
      }else{
        $opt_civilstatus = $opt_civilstatus . $cv;
      }
    }
    //
  }
  //
  //
  $opt_studenttype = "";
  //LOAD STUDENT TYPE
  $sql = " select * from tblstudenttypes ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['studenttype']) != "") {
      $v = trim($dat['studenttype']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_studenttype) == "") {
        $opt_studenttype = $cv;
      }else{
        $opt_studenttype = $opt_studenttype . $cv;
      }
    }
    //
  }
  //
  //
  $opt_employmentstatus = "";
  //LOAD STUDENT TYPE
  $sql = " select * from tblemploymentstatus ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['employmentstatus']) != "") {
      $v = trim($dat['employmentstatus']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_employmentstatus) == "") {
        $opt_employmentstatus = $cv;
      }else{
        $opt_employmentstatus = $opt_employmentstatus . $cv;
      }
    }
    //
  }
  //
  //
  $opt_vaccines = "";
  //LOAD VACCINES
  $sql = " SELECT * from tblvaccines where active='1' ORDER BY name ASC ";
  $qry = mysqli_query($conn_21,$sql);
  //ADD EMPTY
  $opt_vaccines = $opt_vaccines . "<option value=''></option>";
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['name']) != "") {
      $v = trim($dat['name']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_vaccines) == "") {
        $opt_vaccines = $cv;
      }else{
        $opt_vaccines = $opt_vaccines . $cv;
      }
    }
    //
  }
  //
  $opt_vaccine_dose = "";
  //LOAD VACCINE DOSE
  $sql = " SELECT * from tblvaccinedose ";
  $qry = mysqli_query($conn_21,$sql);
  //ADD EMPTY
  $opt_vaccine_dose = $opt_vaccine_dose . "<option value=''></option>";
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['dose']) != "") {
      $v = trim($dat['dose']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_vaccine_dose) == "") {
        $opt_vaccine_dose = $cv;
      }else{
        $opt_vaccine_dose = $opt_vaccine_dose . $cv;
      }
    }
    //
  }
  //
  //
  //
  $opt_province = "";
  //LOAD ADDRESS
  $sql = " select province from tbladdress group by province order by province ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    $tv = trim($dat['province']);
    if(trim($tv) != "") {
      $opt_province = $opt_province . '<option value="' . $tv . '">' . $tv . '</option>';
    }
    //
  }
  //
  //
  // LOAD POLICY
  $v_policy = "";
  //LOAD ADDRESS
  $sql = " SELECT details from tblprivacypolicy WHERE active='1' ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    $v_policy = $v_policy . ($dat['details']);
    //
  }
  if(trim($v_policy) != "") {
    $v_policy = nl2br(trim($v_policy));
  }
  //
  //
  //
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

  <title>Semester Enrollment Admission</title>

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
          <div class="container-fluid">

              <!-- Page Heading -->
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"></h1>
              </div>

              <!-- Content Row -->

          <div style="margin-top: 4rem; margin-bottom: 10rem;" align="center">

            <div class="row align-items-center">

              <div class="col-xl-12 col-lg-12 col-md-12">
                <!--  -->
                <div class="col-xl-5 col-lg-7 col-md-8">
                  <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <div align="left">
                        <h6 class="m-0 font-weight-bold text-primary-1"><?php echo $setting_enrollment_title; ?></h6>
                      </div>
                    </div>
                      <br/>
                      <div class="div-description1" align="left">
                        <span class="span-description1 text-danger">* Required</span>
                      </div>
                    <!-- Card Body -->
                    <div class="card-body padding-lr1" style="overflow: hidden;">

                      <div align="left">
                        
                        <form class="user" method="post" name="enrollform" id="enrollform" enctype="multipart/form-data" action="earesult">


                          <div class="v3-input-title-1">A.  PERSONAL INFORMATION</div>

                          <div class="row">
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Last Name: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="ln" id="ln" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  required readonly 
                                  <?php 
                                    $tv = trim($_GET['ln']);
                                    if(trim($tv) == "") {
                                      $tv = trim($_POST['ln']);
                                    }
                                    if(trim($tv) == "") {
                                      $tv = trim($ed_ln);
                                    }
                                    echo ' value="' . trim($tv) . '"';
                                  ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">First Name: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="fn" id="fn" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  required readonly 
                                  <?php 
                                    $tv = trim($_GET['fn']);
                                    if(trim($tv) == "") {
                                      $tv = trim($_POST['fn']);
                                    }
                                    if(trim($tv) == "") {
                                      $tv = trim($ed_fn);
                                    }
                                    echo ' value="' . trim($tv) . '"';
                                  ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Middle Name: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="mn" id="mn" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" readonly 
                                  <?php 
                                    $tv = trim($_GET['mn']);
                                    if(trim($tv) == "") {
                                      $tv = trim($_POST['mn']);
                                    }
                                    if(trim($tv) == "") {
                                      $tv = trim($ed_mn);
                                    }
                                    echo ' value="' . trim($tv) . '"';
                                  ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Suffixes: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="extension" id="extension" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" readonly 
                                  <?php 
                                    $tv = trim($_GET['extension']);
                                    if(trim($tv) == "") {
                                      $tv = trim($_POST['extension']);
                                    }
                                    if(trim($tv) == "") {
                                      $tv = trim($ed_ext);
                                    }
                                    echo ' value="' . trim($tv) . '"';
                                  ?>
                                >
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Sex: <span class="text-danger">*</span></span>
                            <select class="v3-input-txt-1 input-text-value input-select-value" id="gender" name="gender" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              <?php echo "$gender";
                              ?>
                            </select>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Sexual Orientation: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="sexorient" name="sexorient" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                                  <?php echo "$opt_sexorient";
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Gender, if non-heterosexual: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="nhgender" name="nhgender" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                                  <?php echo "$opt_nhgender";
                                  ?>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Birthdate: <span class="text-danger">*</span></span>
                          </div>
                          <table width="100%">
                            <tr>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Month: <span class="text-danger">*</span></span>
                                  <select class="v3-input-txt-1 input-text-value input-select-value" name="bdate_month" id="bdate_month" onchange="calculateAge()" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php
                                      for ($i=1; $i<=12; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                              </td>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Day: <span class="text-danger">*</span></span>
                                  <select class="v3-input-txt-1 input-text-value input-select-value" name="bdate_day" id="bdate_day" onchange="calculateAge()" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php
                                      for ($i=1; $i<=31; $i++) {
                                        echo "<option value='$i'>$i</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                              </td>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Year: <span class="text-danger">*</span></span>
                                  <input type="number" class="v3-input-txt-1 input-text-value" name="bdate_year" id="bdate_year" onchange="calculateAge()" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required 
                                    <?php
                                      $ty = date("Y") - 15;
                                      echo " value='$ty' ";
                                    ?>
                                  >
                                </div>
                              </td>
                            </tr>
                          </table>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Age: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="age" id="age" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" value="0" placeholder="" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Religion: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="religion" id="religion" value="<?php echo trim($_POST['religion']); ?>" placeholder="" >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Active Contact #: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="contactno" id="contactno" placeholder="" maxlength="11" oninput="maxLengthCheck(this)" value="<?php echo trim($_POST['contactno']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">E-mail: <span class="text-danger">*</span></span>
                                <input type="email" class="v3-input-txt-1 input-text-value" name="email" id="email" placeholder="" value="<?php echo trim($_POST['email']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Civil Status: <span class="text-danger"></span></span>
                            <select class="v3-input-txt-1 input-text-value input-select-value" id="civilstatus" name="civilstatus" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                              <?php echo "$opt_civilstatus";
                              ?>
                            </select>
                          </div>

                          <div class="form-group margin-top1" style="margin-top: 4px;">
                            <span class="v3-input-lbl-1">Present Address: <span class="text-danger">*</span></span>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">House No.: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="houseno" id="houseno" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" 
                                  <?php echo ' value="' . trim($_POST['houseno']) . '"'; ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Street Name: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="streetname" id="streetname" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" 
                                  <?php echo ' value="' . trim($_POST['streetname']) . '"'; ?>
                                >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Province: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="province" name="province" placeholder="" onclick="updateMunicipality('province','municipality'); checkEmptyRequiredInput2('enrollform','submit');" onchange="updateMunicipality('province','municipality'); checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php echo $opt_province; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Municipality: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="municipality" name="municipality" placeholder="" onclick="updateBarangay('municipality','barangay'); checkEmptyRequiredInput2('enrollform','submit');" onchange="updateBarangay('municipality','barangay'); checkEmptyRequiredInput2('enrollform','submit')" required>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Barangay: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="barangay" name="barangay" placeholder="" onclick="checkEmptyRequiredInput2('enrollform','submit');" onchange="checkEmptyRequiredInput2('enrollform','submit')" required>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Postal Code: <span class="text-danger"></span></span>
                            <input type="text" class="v3-input-txt-1 input-text-value" name="postalcode" id="postalcode" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" 
                              <?php echo ' value="' . trim($_POST['postalcode']) . '"'; ?>
                            >
                          </div>


                          <div class="form-group">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary" style="width: 16px; height: 16px; margin-top: 6px;" id="withdisability" name="withdisability" value="1" placeholder="" >
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.9rem;">I belong to person's with disability.<span class="text-danger"></span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">If yes, please specify disability: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="disabilitytype" id="disabilitytype" placeholder="If yes, please specify disability" value="<?php echo trim($_POST['disabilitytype']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                              </div>
                            </div>
                            <div class="col-sm-6" style="">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Disability I.D. (Image): <span class="text-danger"></span></span>
                                <br/>
                                <input type="file" class="input-text-value" name="disability_image" id="disability_image" onchange="preview_image(event,'disability_image_preview')" accept="image/*" placeholder="">
                                <br/>
                                <div align="left">
                                  <img class="image-preview2" style="" id="disability_image_preview">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary" style="width: 16px; height: 16px; margin-top: 6px;" id="soloparent" name="soloparent" value="1" placeholder="" >
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.9rem;">Solo Parent<span class="text-danger"></span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Solo Parent I.D. (Image): <span class="text-danger"></span></span>
                            <br/>
                            <input type="file" class="input-text-value" name="soloparent_image" id="soloparent_image" onchange="preview_image(event,'soloparent_image_preview')" accept="image/*" placeholder="">
                            <br/>
                            <div align="left">
                              <img class="image-preview1" style="" id="soloparent_image_preview">
                            </div>
                          </div>


                          <br/>

                          <div class="v3-input-title-1">IN CASE OF EMERGENCY, CONTACT:</div>
                          
                          <div class="form-group">
                            <span class="v3-input-lbl-1">Name: <span class="text-danger">*</span></span>
                            <input type="text" class="v3-input-txt-1 input-text-value" name="ec_name" id="ec_name" placeholder="" value="<?php echo trim($_POST['ec_name']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Relationship: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="ec_relationship" id="ec_relationship" placeholder="" value="<?php echo trim($_POST['ec_relationship']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Contact No.: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="ec_contactno" id="ec_contactno" placeholder="" value="<?php echo trim($_POST['ec_contactno']); ?>" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                          </div>


                          <br/>

                          <div class="v3-input-title-1">VACCINATION:</div>
                          
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">Vaccinated ? <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccinated" name="vaccinated" placeholder="" value="" onchange="checkVaccineRequiredt3('vaccinated','vaccine_dose','vaccine_name','vaccine_image'); checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required >
                                  <option value="0">No</option>
                                  <option value="1">Yes</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">If yes, vaccine dose: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccine_dose" name="vaccine_dose" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  >
                                  <?php echo $opt_vaccine_dose; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">If yes, vaccine name: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccine_name" name="vaccine_name" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  >
                                  <?php echo $opt_vaccines; ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">If yes, Vaccination Proof (Image): <span class="text-danger"></span></span>
                            <br/>
                            <input type="file" class="input-text-value" name="vaccine_image" id="vaccine_image" onchange="preview_image(event,'vaccine_image_preview');checkEmptyRequiredInput2('enrollform','submit');" accept="image/*" placeholder="">
                            <br/>
                            <div align="left">
                              <img class="image-preview1" style="" id="vaccine_image_preview">
                            </div>
                          </div>


                          <br/>
                          <br/>
                          <br/>


                          <div class="form-group margin-top1">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary form-checkbox-box1" id="iagree" name="iagree" value="1" placeholder="" required>
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.8rem;"><?php echo $v_policy; ?> <span class="text-danger">*</span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>



                          <br/>
                          <br/>


                          <input type="submit" class="btn btn-primary btn-user btn-block bg-2" name="submit" id="submit" value="Submit" >

                          <hr/>

                          <div align="center">
                            
                          </div>
                          



                          <!-- Modal -->
                          <div class="modal fade" id="modalResult" tabindex="-1" role="dialog" aria-labelledby="Result" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="bg-primary modal-header">
                                  <h5 class="modal-title modal-header-text1" id="ModalTitle">Result</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="modal-header-close1">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  <div align="center">

                                    <br/>

                                    <div id="result"></div>
                                    
                                    <br/>

                                  </div>
                                  
                      
                                </div>
                                <div class="modal-footer">
                                  <div id="result-button"></div>
                                  
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Modal Error -->
                          <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="Result" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                <div class="bg-primary modal-header">
                                  <h5 class="modal-title modal-header-text1" id="ModalTitle"></h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" class="modal-header-close1">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  
                                  <div align="center">

                                    <br/>

                                    <div id="errorMessage"></div>
                                    
                                    <br/>

                                  </div>
                                  
                      
                                </div>
                                <div class="modal-footer">
                                  
                                  <button type="button" class="btn btn-secondary btn-bg1 btn1" data-dismiss="modal">Close</button>
                                  
                                </div>
                              </div>
                            </div>
                          </div>


                        </form>

                        <br/>

                      </div>

                    </div>
                  </div>
                </div>

              </div>

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


    function checkVaccineRequired(srcval,target1,target2){
      try{
        var val = document.getElementById(srcval).value;
        if(val.toLowerCase().trim() == "yes".toLowerCase().trim() || val.toLowerCase().trim() == "1".toLowerCase().trim()) {
          try{
            document.getElementById(target1).required = true;
            document.getElementById(target1).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target2).required = true;
            document.getElementById(target2).setAttribute("required", "");
          }catch(err){}
        }else{
          try{
            document.getElementById(target1).required = false;
            document.getElementById(target1).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target2).required = false;
            document.getElementById(target2).removeAttribute("required");
          }catch(err){}
        }
      }catch(err){}
    }

    function checkVaccineRequiredt3(srcval,target1,target2,target3){
      try{
        var val = document.getElementById(srcval).value;
        if(val.toLowerCase().trim() == "yes".toLowerCase().trim() || val.toLowerCase().trim() == "1".toLowerCase().trim()) {
          try{
            document.getElementById(target1).required = true;
            document.getElementById(target1).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target2).required = true;
            document.getElementById(target2).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target3).required = true;
            document.getElementById(target3).setAttribute("required", "");
          }catch(err){}
        }else{
          try{
            document.getElementById(target1).required = false;
            document.getElementById(target1).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target2).required = false;
            document.getElementById(target2).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target3).required = false;
            document.getElementById(target3).removeAttribute("required");
          }catch(err){}
        }
      }catch(err){}
    }


  </script>



</body>

</html>
