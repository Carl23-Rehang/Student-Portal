<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  if (strtolower(trim($log_user_type)) != strtolower(trim("employee"))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  $g_sel_subject = trim($_GET['subject']);
  $g_sel_section = trim($_GET['section']);
  $g_sel_subject_desc = "";
  $g_sel_subject_faculty = "";
  $g_sel_mode_admin = trim($_GET['adm']);
  //
  $g_sel_subject_locked = 1;
  //
  if(trim($g_sel_mode_admin) == "") {
    $g_sel_mode_admin = "0";
  }
  //
  $d_validgrades = [];
  //
  //GET SUBJECT DESC
  $qry = "SELECT * from srgb.subject where UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "'  ";
  $result = pg_query($pgconn, $qry);
  //echo $log_userid;
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $g_sel_subject_desc = trim($row['subjdesc']);
      //
    }
  }
  //GET SUBJECT LOCK STATUS
  $qry = "SELECT subjcode,section,lock from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($g_sel_section)) . "'  ";
  $result = pg_query($pgconn, $qry);
  //echo $log_userid;
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $tval = ($row['lock']);
      if(strtolower(trim($tval)) == strtolower(trim("t")) || strtolower(trim($tval)) == strtolower(trim("true"))) {
        $g_sel_subject_locked = 1;
      }else{
        $g_sel_subject_locked = 0;
      }
      //
    }
  }
  //GET ALL VALID GRADES
  $qry = "SELECT * from srgb.validgrades ORDER BY grade ASC  ";
  $result = pg_query($pgconn, $qry);
  //echo $log_userid;
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $tv = trim($row['grade']);
      if($tv != "") {
        $d_validgrades[count($d_validgrades)] = $tv;
      }
      //
    }
  }
  //echo $g_sel_subject_locked;
  //
  if(trim($log_userid) != "") {
    //
    //LOCKING
    if($_POST['btnlock']) {
      //
      $canupdate = 1;
      if($log_user_role_isadmin > 0) {
        $canupdate = 1;
      }
      // GET SUBJECT FACULTY AND CHECK
      $qry = "SELECT facultyid from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($g_sel_section)) . "'  ";
      $result = pg_query($pgconn, $qry);
      //echo $log_userid;
      if ($result) {
        $n = 0;
        while ($row = pg_fetch_array($result)) {
          //
          $g_sel_subject_faculty = ($row['facultyid']);
        }
      }
      //IF LOCKED
      if($g_sel_subject_locked > 0) {
        if($log_user_role_isadmin <= 0) {
          $canupdate = 0;
        }
      }
      //
      //
      if($canupdate > 0) {
        //
        $tval = "false";
        if($g_sel_subject_locked > 0) {
          $tval = "false";
        }else{
          $tval = "true";
        }
        //
        $sqry = " UPDATE srgb.semsubject SET lock=" . $tval . " where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($g_sel_section)) . "' ";
        $sresult = pg_query($pgconn, $sqry);
        if($sresult) {
          //
          //GET SUBJECT LOCK STATUS
          $qry = "SELECT subjcode,section,lock from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($g_sel_section)) . "'  ";
          $result = pg_query($pgconn, $qry);
          //echo $log_userid;
          if ($result) {
            $n = 0;
            while ($row = pg_fetch_array($result)) {
              //
              $tval = ($row['lock']);
              if(strtolower(trim($tval)) == strtolower(trim("t")) || strtolower(trim($tval)) == strtolower(trim("true"))) {
                $g_sel_subject_locked = 1;
              }else{
                $g_sel_subject_locked = 0;
              }
              //
            }
          }
          //
        }
        //
      }
      //
    }
    //LOCKING END
    //SUBJECT TAGGING
    if($_POST['btnsavesubjecttag']) {
      //
      if($log_user_role_isadmin > 0 && $g_sel_mode_admin > 0) {
        $subj = trim($_POST['subject']);
        $sect = trim($_POST['section']);
        $faculty = trim($_POST['faculty']);
        //
        $errn = 0;
        $errmsg = "";
        //
        if($subj == "") {
          $errn++;
          $errmsg = $errmsg . "Subject required. ";
        }
        if($sect == "") {
          $errn++;
          $errmsg = $errmsg . "Section required. ";
        }
        if($faculty == "") {
          $errn++;
          $errmsg = $errmsg . "Faculty required. ";
        }
        //
        if($errn <= 0) {
          $sqry = " UPDATE srgb.semsubject SET facultyid='" . $faculty . "' where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($subj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($sect)) . "' ";
          $sresult = pg_query($pgconn, $sqry);
          if($sresult) {
            $res_st = '
                  <div class="alert alert-success alert-dismissible fade show-1" role="alert">
                    <strong></strong> Subject added to faculty loading.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
            ';
          }
        }else{
          $res_st = '
                <div class="alert alert-danger alert-dismissible fade show-1" role="alert">
                  <strong></strong> Error subject tagging. ' . trim($errmsg) . '
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
          ';
        }
        //
      }
      //
    }
    //SUBJECT TAGGING END
    //
    $tselsub_inlist = 0;
    //LOAD DATA : SUBJECTS
    //
    $tq_faculty = " AND UPPER(TRIM(facultyid))='" . strtoupper(trim($log_userid)) . "' ";
    if($log_user_role_isadmin > 0) {
      if($g_sel_mode_admin > 0) {
        $tq_faculty = "";
      }
    }
    //
    $subjects_opt = "";
    $qry = "SELECT subjcode,section from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "' " . $tq_faculty . " order by subjcode,section ";
    $result = pg_query($pgconn, $qry);
    //echo $log_userid;
    if ($result) {
      $n = 0;
      while ($row = pg_fetch_array($result)) {
        //
        $tsubj = trim($row['subjcode']);
        $tsec = trim($row['section']);
        $tval = $tsubj . '•' . $tsec;
        $tsel = "";
        if($tsubj != "" && $tsec != "") {
          //
          if(strtoupper(trim($g_sel_subject)) == strtoupper(trim($tsubj)) && strtoupper(trim($g_sel_section)) == strtoupper(trim($tsec))) {
            $tsel = " selected ";
          }
          //CHECK IF SELECTED SUBJECT IS IN LIST
          if(trim($g_sel_subject) != "") {
            if(strtoupper(trim($g_sel_subject)) == strtoupper(trim($tsubj)) && strtoupper(trim($g_sel_section)) == strtoupper(trim($tsec))) {
              $tselsub_inlist++;
            }
          }
          //
          $subjects_opt = $subjects_opt . '<option value="' . $tval . '" ' . $tsel . ' >' . strtoupper($tsubj) . ' - ' . strtoupper($tsec) . '</option>';
          //
        }
        //
      }
    }
    //
    if(trim($g_sel_subject) != "") {
      if($tselsub_inlist <= 0) {
        echo '<meta http-equiv="refresh" content="0;URL=grading-module.php" />';
        exit();
      }
    }
    //
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

  <title>Subject Load</title>

  <?php include "header-imports.php"; ?>

  <style>
    .dropbtn {
      background-color: #04AA6D;
      color: white;
      padding: 16px;
      font-size: 16px;
      border: none;
      cursor: pointer;
    }

    .dropbtn:hover, .dropbtn:focus {
      background-color: #3e8e41;
    }

    #myInput {
      box-sizing: border-box;
      background-image: url('searchicon.png');
      background-position: 14px 12px;
      background-repeat: no-repeat;
      font-size: 16px;
      padding: 14px 20px 12px 45px;
      border: none;
      border-bottom: 1px solid #ddd;
    }

    #myInput:focus {outline: 3px solid #ddd;}

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f6f6f6;
      min-width: 230px;
      overflow: auto;
      border: 1px solid #ddd;
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 6px 16px;
      text-decoration: none;
      display: block;
      font-size: 0.7rem;
    }

    .dropdown a:hover {background-color: #ddd;}

    .dropdown-content-a-1 {
      color: black;
      cursor: pointer;
    }

    .show-1 {display: block;}

  </style>

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

                <div class="col-sm-12">
                <div class="row">

                  <div class="col-sm-12">

                    <div class="card shadow mb-4">
                      <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                        
                        <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                          <div align="left" style="font-size: 0.7rem;">

                            <?php echo $res_st; ?>
                            
                            <b>Subject:</b>
                            <form method="post">
                              <select class="c-input-1" id="subject" name="subject" onchange="selectSubject(this, 'adm');">
                                <?php echo $subjects_opt; ?>
                              </select>
                              <button type="button" class="btn btn-primary bg-2" style="border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px;" onclick="selectSubject2('subject','adm');">Load Subject</button>
                              <?php
                                if($log_user_role_isadmin > 0) {
                                  if($g_sel_mode_admin > 0) {
                                    echo '<input type="hidden" id="adm" name="adm" value="' . trim($g_sel_mode_admin) . '" hidden />';
                                  }
                                }
                              ?>
                            </form>
                            <script>
                              function selectSubject(e, src2) {
                                try{
                                  var tv = e.value;
                                  var tvs = tv.split("•");
                                  var al1 = "";
                                  //
                                  try{
                                    var tsrc2 = document.getElementById(src2);
                                    if(tsrc2 != null) {
                                      if(tsrc2.value == true){
                                        al1 = "&adm=1";
                                      }
                                    }
                                  }catch(err){alert(err);}
                                  //
                                  location.replace("grading-module.php?subject=" + tvs[0] + "&section=" + tvs[1] + al1);
                                }catch(err){}
                              }
                              function selectSubject2(src,src2) {
                                try{
                                  var tv = document.getElementById(src).value;
                                  var tvs = tv.split("•");
                                  var al1 = "";
                                  //
                                  try{
                                    var tsrc2 = document.getElementById(src2);
                                    if(tsrc2 != null) {
                                      if(tsrc2.value == true){
                                        al1 = "&adm=1";
                                      }
                                    }
                                  }catch(err){alert(err);}
                                  //
                                  location.replace("grading-module.php?subject=" + tvs[0] + "&section=" + tvs[1] + al1);
                                }catch(err){}
                              }
                            </script>

                            <?php
                              if(trim($g_sel_subject) != "") {
                                echo '
                                  <form method="post" style="display: inline-block;">
                                    <input type="submit" class="btn btn-primary bg-2" style="display: inline-block; border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px;" id="btnloadcurrentsubjectinvalid" name="btnloadcurrentsubjectinvalid" value="Load Invalid Grades (Current Subject and Section)" />
                                  </form>
                                ';
                              }
                            ?>


                            <?php
                              if($log_user_role_isadmin > 0) {
                                $tchecked = "";
                                if(trim($_GET['adm']) == "" || trim($_GET['adm']) != "1") {
                                  $tchecked = "";
                                }
                                if(trim($_GET['adm']) == "1") {
                                  $tchecked = " checked ";
                                }
                                //
                                echo '
                                  <form method="get">
                                    <input type="hidden" name="subject" value="' . trim($g_sel_subject) . '" hidden />
                                    <input type="hidden" name="section" value="' . trim($g_sel_section) . '" hidden />
                                    <table>
                                      <tr>
                                        <td>
                                          <div class="checkbox" style="margin-top: 10px;">
                                            <input type="checkbox" name="adm" value="1" onchange="this.form.submit()" ' . $tchecked . ' />
                                          </div>
                                        </td>
                                        <td><div style="margin-top: 8px; margin-left: 4px;">Admin Mode</div></td>
                                      </tr>
                                    </table>
                                  </form>
                                ';
                                //
                                if($g_sel_mode_admin > 0) {
                                  echo '
                                    <form method="post" style="display: inline-block;">
                                      <input type="submit" class="btn btn-primary bg-2" style="display: inline-block; border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px;" id="btnloadinvalid" name="btnloadinvalid" value="Load All Invalid Grades" />
                                    </form>
                                  ';
                                }
                                //
                              }
                            ?>


                          </div>
                        </div>

                      </div>
                    </div>

                  </div>

                  <div class="col-sm-9" style="padding-left: 4px;padding-right: 4px;">



                    <div class="card shadow mb-4">
                      <div class="card-header py-3">
                        <div align="left">
                          <h6 class="m-0 font-weight-bold text-primary"><span class="color-blue-1"><?php 
                            $td = " ";
                            if ($g_sel_subject_desc != null && trim($g_sel_subject_desc) != "") {
                              $td = $g_sel_subject_desc;
                              if(trim($g_sel_section) != "") {
                                $td = $g_sel_subject_desc . " (" . $g_sel_section . ")";
                              }
                            }
                            echo $td; 
                          ?></span></h6>
                        </div>
                        
                      </div>
                      <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                        
                        <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                          <div align="left" style="font-size: 0.7rem;">
                            <?php
                                //GLOBAL DISABLE
                                if(trim($setting_grade_encoding_allowed) == "0" || $setting_grade_encoding_allowed <= 0) {
                                  //
                                  echo '
                                        <div align="center" class="text-danger" style="font-size: 1.2rem; padding: 16px 4px;">
                                          <b>*** GRADE ENCODING IS DISABLED AS OF THE MOMENT. PLEASE TRY AGAIN LATER. ***</b>
                                        </div>
                                  ';
                                  //
                                }
                            ?>
                            <b></b>
                          </div>
                          
                          <div class="table-responsive">
                          <table class="table table-striped table-hover">
                            <thead class="thead-1 font-size-o-1">
                              <tr style="font-size: 0.6rem;">
                                <th scope="col">#</th>
                                <th scope="col">IDNO</th>
                                <th scope="col">Name</th>
                                <th scope="col">Mid-Term</th>
                                <th scope="col">Final Term</th>
                                <th scope="col">Final Grade</th>
                                <th scope="col">Remarks</th>
                              </tr>
                            </thead>
                            <tbody class="font-size-o-1">
                              <?php
                                //
                                $tad = "";
                                //
                                //
                                $tdisable = "";
                                //
                                $na_ecoding = 0;
                                //
                                if($g_sel_subject_locked > 0) {
                                  $tdisable = " disabled ";
                                }else{
                                  $tdisable = "";
                                }
                                //CHECK ALLOWED ENCODING SY SEM
                                if(trim($setting_grade_encoding_allowed_sy) != "" && trim($setting_grade_encoding_allowed_sy) != "all") {
                                  if(trim(strtoupper($setting_grade_encoding_allowed_sy)) != trim(strtoupper($log_user_active_sy)) &&
                                     trim(strtoupper($setting_grade_encoding_allowed_sem)) != trim(strtoupper($log_user_active_sem))) {
                                    $tdisable = " disabled ";
                                  }
                                }
                                //
                                //GLOBAL DISABLE
                                if(trim($setting_grade_encoding_allowed) == "0" || $setting_grade_encoding_allowed <= 0) {
                                  $tdisable = " disabled ";
                                  $na_ecoding = 1;
                                }
                                //
                                //
                                $q_where = "
                                        WHERE UPPER(TRIM(a.sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(a.sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(a.subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(a.section))='" . strtoupper(trim($g_sel_section)) . "' 
                                        order by b.studfullname ASC 
                                ";
                                if($_POST['btnloadinvalid'] && $log_user_role_isadmin > 0 && $g_sel_mode_admin > 0) {
                                  //
                                  $tcq = "";
                                  //
                                  $tcq = $tcq . " AND grade IS NOT NULL ";
                                  $tcq = $tcq . " AND grade!='' ";
                                  //
                                  for($i=0; $i<count($d_validgrades); $i++) {
                                    $tv = trim($d_validgrades[$i]);
                                    if($tv != "") {
                                      if(trim($tcq) == "") {
                                        $tcq = " AND grade!='" . $tv . "' ";
                                      }else{
                                        $tcq = $tcq . " AND grade!='" . $tv . "' ";
                                      }
                                    }
                                  }
                                  //
                                  $q_where = "
                                          WHERE UPPER(TRIM(a.sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(a.sem))='" . strtoupper(trim($log_user_active_sem)) . "' 
                                          " . $tcq . " 
                                          order by b.studfullname ASC 
                                  ";
                                }
                                if($_POST['btnloadcurrentsubjectinvalid']) {
                                  //
                                  $tcq = "";
                                  //
                                  $tcq = $tcq . " AND grade IS NOT NULL ";
                                  $tcq = $tcq . " AND grade!='' ";
                                  //
                                  for($i=0; $i<count($d_validgrades); $i++) {
                                    $tv = trim($d_validgrades[$i]);
                                    if($tv != "") {
                                      if(trim($tcq) == "") {
                                        $tcq = " AND grade!='" . $tv . "' ";
                                      }else{
                                        $tcq = $tcq . " AND grade!='" . $tv . "' ";
                                      }
                                    }
                                  }
                                  //
                                  $q_where = "
                                          WHERE UPPER(TRIM(a.sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(a.sem))='" . strtoupper(trim($log_user_active_sem)) . "'  AND UPPER(TRIM(a.subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(a.section))='" . strtoupper(trim($g_sel_section)) . "' 
                                          " . $tcq . " 
                                          order by b.studfullname ASC 
                                  ";
                                }
                                //
                                $qry = "SELECT a.studid,b.studfullname,a.midterm,a.finalterm,a.grade,a.remarks,a.subjcode,a.section 
                                        from srgb.registration AS a 
                                        LEFT JOIN srgb.student AS b ON b.studid=a.studid 
                                        " . $q_where . "
                                        ";
                                $result = pg_query($pgconn, $qry);
                                if ($result) {
                                  $n = 0;
                                  while ($row = pg_fetch_array($result)) {
                                    //
                                    $n++;
                                    //
                                    $tremark = trim($row['remarks']);
                                    $tcolor = "";
                                    if(strtolower(trim($tremark)) != strtolower(trim("pass")) && strtolower(trim($tremark)) != strtolower(trim("passed"))) {
                                      $tcolor = " text-danger ";
                                    }
                                    //
                                    $tad = $tad . '
                                                    <tr style="font-size: 0.7rem;">
                                                      <td id="v_td_no_' . $n . '" class="' . $tcolor . '">' . $n . '</td>
                                                      <td id="v_td_studid_' . $n . '" class="' . $tcolor . '">' . trim($row['studid']) . '<input type="hidden" id="v_studid_' . $n . '" value="' . trim($row['studid']) . '" /><input type="hidden" id="v_subjcode_' . $n . '" value="' . trim($row['subjcode']) . '" /><input type="hidden" id="v_section_' . $n . '" value="' . trim($row['section']) . '" /></td>
                                                      <td id="v_td_fullname_' . $n . '" class="' . $tcolor . '">' . trim($row['studfullname']) . '</td>
                                                      <td class="' . $tcolor . '"><input type="text" class="c-input-2 ' . $tcolor . ' " id="v_mt_' . $n . '" value="' . trim($row['midterm']) . '" onkeyup="' . "checkKey(event,'" . $n . "');". '" onfocusout="' . "saveGrade('" . $n . "');" . '" ' . $tdisable . ' /></td>
                                                      <td class="' . $tcolor . '"><input type="text" class="c-input-2 ' . $tcolor . ' " id="v_ft_' . $n . '" value="' . trim($row['finalterm']) . '" onkeyup="' . "checkKey(event,'" . $n . "');". '" onfocusout="' . "saveGrade('" . $n . "');" . '" ' . $tdisable . ' /></td>
                                                      <td class="' . $tcolor . '"><input type="text" class="c-input-2 ' . $tcolor . ' " id="v_grade_' . $n . '" value="' . trim($row['grade']) . '" onkeyup="' . "checkKey(event,'" . $n . "');". '" onfocusout="' . "updateGradeRemarks('" . $n . "'); saveGrade('" . $n . "');" . '" ' . $tdisable . ' /></td>
                                                      <td class="' . $tcolor . '"><input type="text" class="c-input-2 ' . $tcolor . ' " id="v_remarks_' . $n . '" value="' . trim($row['remarks']) . '" ' . $tdisable . ' /></td>
                                                    </tr>
                                    ';
                                    //
                                  }
                                }
                                //
                                echo $tad;
                                //
                              ?>
                            </tbody>
                          </table>
                          </div>
                        </div>

                        <hr>
                        
                      </div>
                    </div>

                  </div>

                  <div class="col-sm-3">

                    <div class="card shadow mb-4">
                      <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                        
                        <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                          <div align="center" style="font-size: 0.7rem; min-height: 70px;">

                            <?php
                              if(trim($g_sel_subject) != "" && trim($g_sel_section) != ""){
                                $tlbl = "Lock All Grades";
                                //
                                $tmsg = "";
                                //
                                if($g_sel_subject_locked > 0) {
                                  $tlbl = "Un-Lock All Grades";
                                  $tmsg = "Do you really want un-lock grade encoding for this subject ?";
                                }else{
                                  $tlbl = "Lock All Grades";
                                  $tmsg = "Are you sure you want lock grade encoding for this subject ? This means you cannot edit / update grades onced it's locked.";
                                }
                                //
                                $tshow = 1;
                                //
                                if($g_sel_subject_locked > 0) {
                                  if($log_user_role_isadmin <= 0) {
                                    $tshow = 0;
                                  }
                                }
                                //
                                if($_POST['btnloadinvalid'] || $_POST['btnloadcurrentsubjectinvalid']){
                                  $tshow = 0;
                                }
                                //
                                if($tshow > 0) {
                                  //
                                  $fm = '
                                      <!-- Modal -->
                                      <div class="modal fade" id="modalLocking" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header bg-3 color-white-1 modal-header-1">
                                              <h5 class="modal-title" style="font-size: 0.8rem;" id="">' . $tlbl . '</h5>
                                              <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                              <form method="post">
                                            <div class="modal-body">
                                              <div align="left">

                                                ' . $tmsg . '

                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                              <input type="submit" class="btn btn-primary bg-2 font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnlock" value="' . $tlbl . '" />
                                            </div>
                                              </form>
                                          </div>
                                        </div>
                                      </div>
                                  ';
                                  //
                                  echo '
                                      <form method="post">

                                        <input type="button" class="btn btn-primary bg-2 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px;"  data-toggle="modal" data-target="#modalLocking" value="' . $tlbl . '" />

                                      </form>
                                      ' . $fm . '
                                  ';
                                }
                                //
                                //PRINTING
                                //
                                $tshow2 = 1;
                                if($_POST['btnloadinvalid'] || $_POST['btnloadcurrentsubjectinvalid']){
                                  $tshow2 = 0;
                                }
                                //
                                if($tshow2 > 0) {
                                  echo '<a class="btn btn-primary bg-2 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px; margin-top: 12px;" target="_blank" href="grade-printing.php?sy=' . $log_user_active_sy . '&sem=' . $log_user_active_sem . '&subject=' . $g_sel_subject . '&section=' . $g_sel_section . '">Print</a>';
                                }
                                //
                              }
                              //
                            ?>
                            

                          </div>
                        </div>

                      </div>
                    </div>

                    <?php
                      //
                      if($log_user_role_isadmin > 0 && $g_sel_mode_admin > 0) {
                        //
                        //
                        $fm = '
                            <!-- Modal -->
                            <div class="modal fade" id="modalSubjectTagging" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                    <h5 class="modal-title" style="font-size: 0.8rem;" id="">Subject Tagging</h5>
                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                    <form method="post">
                                  <div class="modal-body">
                                    <div align="left">


                                      <input type="hidden" id="st_subject" name="subject" value="" hidden />
                                      <input type="hidden" id="st_section" name="section" value="" hidden />
                                      <input type="hidden" id="st_faculty_id" name="faculty" value="" hidden />

                                      <span class="" style="font-size: 0.6rem;">Subject: <span class="text-danger"></span></span>
                                      <div class="dropdown" style="display: block; width: 100%;">
                                        <button type="button" onclick="' . "ItemListShow('st_subject_h_items');" . '" style="display: block; max-width: 100%; width: 100%; padding: 12px 12px; text-align: left;" class="c-input-1" id="st_subject_name">Select Subject...</button>
                                        <div id="st_subject_h_items" class="dropdown-content" style="width: 100%; color: black;">
                                          <input type="text" class="c-input-1" style="width: 100%;" placeholder="Search.." id="st_subject_fs" onkeyup="' . "ItemListFilter('st_subject_h_items','st_subject_fs');" . '">
                                          <div id="st_subject_h_items_inner" style="max-height: 128px;">

                                          </div>
                                        </div>
                                      </div>

                                      <div style="font-size: 0.7rem; font-weight: bold; margin-top: 8px; margin-bottom: 4px; min-height: 24px;" id="st_subject_faculty_current">
                                        
                                      </div>

                                      <span class="" style="font-size: 0.6rem;">Faculty: <span class="text-danger"></span></span>
                                      <div class="dropdown" style="display: block; width: 100%;">
                                        <button type="button" onclick="' . "ItemListShow('st_faculty_h_items');" . '" style="display: block; max-width: 100%; width: 100%; padding: 12px 12px; text-align: left;" class="c-input-1" id="st_faculty_name">Select Faculty...</button>
                                        <div id="st_faculty_h_items" class="dropdown-content" style="width: 100%; color: black;">
                                          <input type="text" class="c-input-1" style="width: 100%;" placeholder="Search.." id="st_faculty_fs" onkeyup="' . "ItemListFilter('st_faculty_h_items','st_faculty_fs');" . '">
                                          <div id="st_faculty_h_items_inner" style="max-height: 128px;">

                                          </div>
                                        </div>
                                      </div>

                                      <br/>

                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsavesubjecttag" value="Tag Subject" />
                                  </div>
                                    </form>
                                </div>
                              </div>
                            </div>
                        ';
                        //
                        echo '
                            <div class="card shadow mb-4" style="">
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                                  <div align="center" style="font-size: 0.7rem;">
                                    <input type="button" class="btn btn-primary bg-2 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem; padding-top: 7px; padding-bottom: 7px;" onclick="' . "ItemListLoad('st_subject_h_items_inner','st_faculty_h_items_inner');" . '"  data-toggle="modal" data-target="#modalSubjectTagging" value="Tag Subject" />
                                  </div>
                                </div>
                              </div>
                            </div>
                            ' . $fm . '
                        ';
                        //
                      }
                      //
                    ?>


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
            <br/>
            <br/>
            </div>
          </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->


      <!-- Modal -->
      <div class="modal fade" id="modalCommonMessage" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header bg-3 color-white-1 modal-header-1">
              <h5 class="modal-title" style="font-size: 0.8rem;" id="">' . $tlbl . '</h5>
              <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <form method="post">
            <div class="modal-body">
              <div align="left">

                <span id="modal_cmsg_content"></span>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
            </div>
              </form>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalInvalidGrade" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header bg-danger color-white-1 modal-header-1">
              <h5 class="modal-title" style="font-size: 0.8rem;" id="">Invalid Grade</h5>
              <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <form method="post">
            <div class="modal-body">
              <div align="left" style="font-size: 0.7rem;">

                <span class="text-danger">Grade is invalid.</span>
                <br/>
                <br/>
                <p>
                  <?php
                    $tcvalidgrades = "";
                    for($i=0; $i<count($d_validgrades); $i++) {
                      $tv = trim($d_validgrades[$i]);
                      if($tv != "") {
                        if(trim($tcvalidgrades) == "") {
                          $tcvalidgrades = $tv;
                        }else{
                          $tcvalidgrades = $tcvalidgrades . "<br/>" . $tv;
                        }
                      }
                    }
                  ?>
                  <b>Valid Grades:<br/><?php echo $tcvalidgrades; ?></b>
                </p>
                <br/>
                <span class="text-danger">Note: Please make sure that there is space before, after, or between each character.</span>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
            </div>
              </form>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="modalSYSEMEncodingNotAllowed" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header bg-danger color-white-1 modal-header-1">
              <h5 class="modal-title" style="font-size: 0.8rem;" id="">Grade Encoding</h5>
              <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <form method="post">
            <div class="modal-body">
              <div align="left" style="font-size: 0.7rem;">

                <span class="text-danger">Grade encoding for this School Year (S.Y.) and Semester (SEM) is not allowed.</span>

              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary font-size-o-1 btn-width-min-1-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
            </div>
              </form>
          </div>
        </div>
      </div>


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

    var tglock = parseInt(<?php echo $g_sel_subject_locked; ?>)

    var d_validgrades = <?php echo json_encode($d_validgrades); ?>;

    function isGradeValid(id) {
      var result = 0;
      try{
        var tg = document.getElementById("v_grade_" + id).value;
        if(tg.trim() != "") {
          for(var i=0; i<d_validgrades.length; i++){
            try{
              var tv = d_validgrades[i];
              if(tg == tv) {
                result = 1;
                break;
              }
            }catch(err){
            }
          }
        }
        if(tg == "") {
          result = 1;
        }
      }catch(err){
      }
      return result;
    }


    function updateGradeRemarks(id) {
      try{
        if(tglock <= 0) {
          var tg = document.getElementById("v_grade_" + id).value;
          var target =  document.getElementById("v_remarks_" + id);
          //target.value = "Passed";
          try{
            var cs = 'get-remark.php?v=' + tg + '';
            $.get(cs, function(data) {
              target.value = data;
              //
              //SET COLOR
              try{
                var at1 = document.getElementById("v_grade_" + id);
                var at2 = document.getElementById("v_mt_" + id);
                var at3 = document.getElementById("v_ft_" + id);
                var at4 = document.getElementById("v_td_no_" + id);
                var at5 = document.getElementById("v_td_studid_" + id);
                var at6 = document.getElementById("v_td_fullname_" + id);
                //
                var tcol_fail = '#e74a3b';
                var tcol_pass = '#000';
                var tcol_pass2 = '#858796';
                var tclass_fail = "text-danger";
                //
                if(data.toLowerCase().trim() != "pass".toLowerCase().trim() && data.toLowerCase().trim() != "passed".toLowerCase().trim()) {
                  //RED
                  target.style.color = tcol_fail;
                  //
                  at1.style.color = tcol_fail;
                  at2.style.color = tcol_fail;
                  at3.style.color = tcol_fail;
                  at4.style.color = tcol_fail;
                  at5.style.color = tcol_fail;
                  at6.style.color = tcol_fail;
                }else{
                  //NORMAL
                  target.style.color = tcol_pass;
                  target.classList.remove(tclass_fail);
                  //
                  at1.style.color = tcol_pass;
                  at1.classList.remove(tclass_fail);
                  at2.style.color = tcol_pass;
                  at2.classList.remove(tclass_fail);
                  at3.style.color = tcol_pass;
                  at3.classList.remove(tclass_fail);
                  //
                  at4.style.color = tcol_pass2;
                  at4.classList.remove(tclass_fail);
                  at5.style.color = tcol_pass2;
                  at5.classList.remove(tclass_fail);
                  at6.style.color = tcol_pass2;
                  at6.classList.remove(tclass_fail);
                }
              }catch(err){alert(err);}
              //
            });

          }catch(err){}
          //
        }
      }catch(err){}
    }

    function checkKey(e, id) {
      try{
        var tc = (e.keyCode ? e.keyCode : e.which);
        if(tc == 13 || e.code === "Enter") {

          if(tglock <= 0) {

            //alert('Enter - ' + id);
            updateGradeRemarks(id);
            if(isGradeValid(id) > 0) {
              saveGrade(id);
            }else{
              $('#modalInvalidGrade').modal('show');
            }

          }

        }
      }catch(err){}
    }

    function saveGrade(id) {
      try{

        if(tglock <= 0) {

          var sy = "<?php echo $log_user_active_sy; ?>";
          var sem = "<?php echo $log_user_active_sem; ?>";
          //var subj = "<?php echo $g_sel_subject; ?>";
          //var section = "<?php echo $g_sel_section; ?>";
          var subj = "";
          var section = "";
          //
          var tstudid = document.getElementById("v_studid_" + id).value;
          var tmt = document.getElementById("v_mt_" + id).value;
          var tft = document.getElementById("v_ft_" + id).value;
          var tg = document.getElementById("v_grade_" + id).value;
          //
          subj = document.getElementById("v_subjcode_" + id).value;
          section = document.getElementById("v_section_" + id).value;
          //
          var allow_sy = "<?php echo $setting_grade_encoding_allowed_sy; ?>";
          var allow_sem = "<?php echo $setting_grade_encoding_allowed_sem; ?>";
          var allow_grade_encoding = "<?php echo $setting_grade_encoding_allowed; ?>";
          //
          try{
            if(allow_sy.trim() != "" && allow_sy.trim().toLowerCase() != "all".trim().toLowerCase()) {
              if(sy.toUpperCase().trim() != allow_sy.toUpperCase().trim()) {
                $('#modalSYSEMEncodingNotAllowed').modal('show');
                return;
              }
              if(sy.toUpperCase().trim() == allow_sy.toUpperCase().trim() && sem.toUpperCase().trim() != allow_sem.toUpperCase().trim()) {
                $('#modalSYSEMEncodingNotAllowed').modal('show');
                return;
              }
              if(sy.toUpperCase().trim() != allow_sy.toUpperCase().trim() && sem.toUpperCase().trim() != allow_sem.toUpperCase().trim()) {
                $('#modalSYSEMEncodingNotAllowed').modal('show');
                return;
              }
            }
          }catch(err){}
          //
          try{
            if(allow_grade_encoding.toUpperCase().trim() == "0".toUpperCase().trim()) {
              $('#modalSYSEMEncodingNotAllowed').modal('show');
              return;
            }
          }catch(err){}
          //
          if(isGradeValid(id) <= 0) {
            $('#modalInvalidGrade').modal('show');
            return;
          }
          //
          try{
            var cs = 'save-grade.php?sy=' + sy + '&sem=' + sem + '&studid=' + tstudid + '&subject=' + subj + '&section=' + section + '&mt=' + tmt + '&ft=' + tft + '&grade=' + tg + '';
            $.get(cs, function(data) {
              // RESULT
              //console.log(data);
            });

          }catch(err){}
          
        }

      }catch(err){}
    }
    
  </script>

  <script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function ItemListShow(target) {
      document.getElementById(target).classList.toggle("show-1");
    }

    function ItemListFilter(target,fsearch) {
      var input, filter, ul, li, a, i;
      input = document.getElementById(fsearch);
      filter = input.value.toUpperCase();
      div = document.getElementById(target);
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

    function ItemListSelect(itemholder,valID,valName,targetID,targetName) {
      try{
        document.getElementById(targetID).value = valID;
      }catch(err){}
      try{
        document.getElementById(targetName).value = valName;
      }catch(err){}
      try{
        document.getElementById(targetName).innerText = valName;
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListSelect_Subject(itemholder,value,targetSubject,targetSection,targetName,valCurrFaculty,targetCurrFaculty) {
      try{
        if(value.trim() != "") {
          var td = value.split("•");
          if(td.length > 0) {
            try{
              document.getElementById(targetSubject).value = td[0].trim();
            }catch(err){}
            try{
              document.getElementById(targetSection).value = td[1].trim();
            }catch(err){}
            try{
              var tname = "" + td[0].trim() + " : " + td[1].trim();
              try{
                document.getElementById(targetName).value = tname.trim();
              }catch(err){}
              try{
                document.getElementById(targetName).innerText = tname.trim();
              }catch(err){}
            }catch(err){}
          }
        }
      }catch(err){}
      try{
        try{
          document.getElementById(targetCurrFaculty).innerText = "";
        }catch(err){}
        if(valCurrFaculty.trim() != "") {
          document.getElementById(targetCurrFaculty).innerText = "Current Faculty: " + valCurrFaculty.trim();
        }
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListSelect_Faculty(itemholder,valID,valName,targetID,targetName) {
      try{
        document.getElementById(targetID).value = valID;
      }catch(err){}
      try{
        document.getElementById(targetName).value = valName;
      }catch(err){}
      try{
        document.getElementById(targetName).innerText = valName;
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListLoad(targetSubject,targetFaculty) {
      try{
        var tshow = true;
        if(tshow == true) {
          //
          var tsy = "<?php echo $log_user_active_sy; ?>";
          var tsem = "<?php echo $log_user_active_sem; ?>";
          //
          var tsubj = document.getElementById(targetSubject);
          var tfac = document.getElementById(targetFaculty);
          //
          try{
            var cs = 'get-subjects-subjtag.php?sy=' + tsy + '&sem=' + tsem + '';
            $.get(cs, function(data) {
              //
              tsubj.innerHTML  = data;
              //
              //LOAD FACULTY
              try{
                var cs2 = 'get-faculty-subjtag.php' + '';
                $.get(cs2, function(data2) {
                  //
                  tfac.innerHTML  = data2;
                  //
                });

              }catch(err){}
              //
            });

          }catch(err){}
          //
        }
      }catch(err){}
    }


  </script>

</body>

</html>
