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
    //echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    //exit();
  }
  //
  // CHECK IF ALLOWED TO VIEW THIS PAGE
  if($log_user_sem_enroll_admin <= 0 && $log_user_sem_enroll_view <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  //
  $emp_newid = "";
  $fresult = pg_query($pgconn, "SELECT empid from pis.employee where empid ~ '[0-9]' ORDER BY empid DESC LIMIT 1 ");
  if ($fresult) {
    while ($frow = pg_fetch_array($fresult)) {
      //
      //$emp_newid = trim($frow[0]);
      //
    }
  }
  //
  //
  if(trim($log_userid)!="") {
    //
    if($log_user_role_isadmin > 0) {
      if(isset($_POST['btnupd_enroll_ylcrs'])) {
        //
        $tsy = $setting_enrollment_sy;
        $tsem = $setting_enrollment_sem;
        //
        $tprevsy = "";
        $tprevsem = "";
        //
        $sqry = "SELECT * from tblconstudent WHERE active='1' AND TRIM(UPPER(sy))=TRIM(UPPER('" . $tsy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $tsem . "')) AND ( (TRIM(yearlevel)='' OR yearlevel IS NULL) OR (TRIM(courseprogram)='' OR courseprogram IS NULL) OR (TRIM(section)='' OR section IS NULL) ) ";
        $sresult = mysqli_query($conn_21, $sqry);
        if ($sresult) {
          while ($srow = mysqli_fetch_array($sresult)) {
            //
            $id = trim($srow['id']);
            $studid = trim($srow['studid']);
            //
            if(trim($id) != "" && trim($studid) != "") {
              //
              $tyearlevel = "";
              $tprogram = "";
              $tsection = "";
              //
              // GET PREV SY, SEM
              $query0 = "SELECT sy,sem FROM srgb.registration WHERE TRIM(LOWER(studid))='" . trim(strtolower($studid)) . "' ORDER BY sy DESC,sem DESC LIMIT 1 ";
              $result0 = pg_query($pgconn, $query0);
              if ($result0) {
                while ($row0 = pg_fetch_array($result0)) {
                  $tprevsy = trim($row0['sy']);
                  $tprevsem = trim($row0['sem']);
                  //
                }
              }
              // GET YEAR PROGRAM
              $query0 = "SELECT studlevel,studmajor FROM srgb.semstudent WHERE TRIM(LOWER(studid))='" . trim(strtolower($studid)) . "' ORDER BY sy DESC,sem DESC LIMIT 1 ";
              $result0 = pg_query($pgconn, $query0);
              if ($result0) {
                while ($row0 = pg_fetch_array($result0)) {
                  $tyearlevel = trim($row0['studlevel']);
                  $tprogram = trim($row0['studmajor']);
                }
              }
              // GET SECTION START ===
              $tsecs = [];
              $query0 = "SELECT section FROM srgb.registration WHERE TRIM(LOWER(studid))='" . trim(strtolower($studid)) . "' AND TRIM(LOWER(sy))='" . trim(strtolower($tprevsy)) . "' AND TRIM(LOWER(sem))='" . trim(strtolower($tprevsem)) . "' ORDER BY section ASC ";
              $result0 = pg_query($pgconn, $query0);
              if ($result0) {
                while ($row0 = pg_fetch_array($result0)) {
                  $tv = trim($row0['section']);
                  //
                  $ten = 0;
                  for($i=0; $i<count($tsecs); $i++) {
                    $tec = $tsecs[$i][0];
                    if(trim(strtolower($tec)) == trim(strtolower($tv))) {
                      //UPDATE COUNT
                      $tsecs[$i][1] += 1;
                      $ten++;
                    }
                  }
                  // IF NOT IN LIST, ADD
                  $tnewd = [];
                  $tnewd[0] = $tv;
                  $tnewd[1] = 1;
                  $tsecs[count($tsecs)] = $tnewd;
                  //
                }
              }
              // GET LARGEST SECTION COUNT
              $tsec_lcount = 0;
              $tsec_lval = "";
              for($i=0; $i<count($tsecs); $i++) {
                $tcv = $tsecs[$i][0];
                $tcn = $tsecs[$i][1];
                if($i == 0 || $tcn > $tsec_lcount) {
                  $tsec_lval = $tcv;
                  $tsec_lcount = $tcn;
                }
              }
              $tsection = $tsec_lval;
              // GET SECTION END ===
              //
              //
              // SAVE
              $fqry = " UPDATE tblconstudent SET courseprogram='" . $tprogram . "',yearlevel='" . $tyearlevel . "',section='" . $tsection . "' WHERE id='" . $id . "' ";
              $fresult = mysqli_query($conn_21, $fqry);
              //
            }
            //
          }
        //
        }
      }
    }
    //
    //
    //LOAD MEM TYPE OPT
    $opt_memtype = [];
    $opt_memtype_sv = "";
    $sresult = mysqli_query($conn, "SELECT * from tblusermemtype ORDER BY orderno ASC, type ASC ");
    if ($sresult) {
      while ($srow = mysqli_fetch_array($sresult)) {
        //
        $tv = [];
        $tv[0] = trim($srow['usermemtypeid']);
        $tv[1] = trim($srow['type']);
        $opt_memtype[count($opt_memtype)] = $tv;
        //
        $opt_memtype_sv = $opt_memtype_sv . '<option value="' . trim($srow['type']) . '">' . trim($srow['type']) . '</option>';
        //
      }
    }
    //echo "XXX " . count($opt_memtype);
    //LOAD OPT ROLE
    $opt_roles = [];
    $opt_roles_sv = "";
    $sresult = mysqli_query($conn, "SELECT * from tblroletype ORDER BY rolename ASC ");
    if ($sresult) {
      while ($srow = mysqli_fetch_array($sresult)) {
        //
        $tv = [];
        $tv[0] = trim($srow['roletypeid']);
        $tv[1] = trim($srow['rolecode']);
        $tv[2] = trim($srow['rolename']);
        $opt_roles[count($opt_roles)] = $tv;
        //
        $opt_roles_sv = $opt_roles_sv . '<option value="' . trim($srow['roletypeid']) . '">' . "[" . trim($srow['rolecode']) . "] " . trim($srow['rolename']) . '</option>';
        //
      }
    }
    //
  } // END CHECK USER ID
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

  <title>Enrollee</title>

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

                      <div class="col-xl-12 col-lg-12 col-md-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-xl-8 col-lg-8 col-md-8" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Next Semester Enrollee</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                </div>

                                <?php
                                  // CREATE SEARCH QRY
                                  $qs_sysem = "";
                                  $qs = "";
                                  $qs2 = "";
                                  //
                                  // QRY SY SEM
                                  $qs_sysem = " ( (TRIM(LOWER(sy)) LIKE TRIM(LOWER('%" . $setting_enrollment_sy . "%'))) AND (TRIM(LOWER(sem)) LIKE TRIM(LOWER('%" . $setting_enrollment_sem . "%'))) ) ";
                                  // QRY SEARCH
                                  //
                                  $fsearch = trim($_GET['search']);
                                  $fyearlevel = trim($_GET['yearlevel']);
                                  $fprogram = trim($_GET['program']);
                                  $fsection = trim($_GET['section']);
                                  //
                                  if($fsearch != "") {
                                    $qv = " ( (TRIM(LOWER(lastname)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) OR (TRIM(LOWER(firstname)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) OR (TRIM(LOWER(middlename)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) ) ";
                                    if(trim($qs) == "") {
                                      $qs = $qv;
                                    }else{
                                      $qs = $qs . " AND " . $qv;
                                    }
                                  }
                                  if($fyearlevel != "") {
                                    $qv = " ( (TRIM(LOWER(yearlevel))=TRIM(LOWER('" . $fyearlevel . "'))) ) ";
                                    if(trim($qs) == "") {
                                      $qs = $qv;
                                    }else{
                                      $qs = $qs . " AND " . $qv;
                                    }
                                  }
                                  if($fprogram != "") {
                                    $qv = " ( (TRIM(LOWER(courseprogram))=TRIM(LOWER('" . $fprogram . "'))) ) ";
                                    if(trim($qs) == "") {
                                      $qs = $qv;
                                    }else{
                                      $qs = $qs . " AND " . $qv;
                                    }
                                  }
                                  if($fsection != "") {
                                    $qv = " ( (TRIM(LOWER(section))=TRIM(LOWER('" . $fsection . "'))) ) ";
                                    if(trim($qs) == "") {
                                      $qs = $qv;
                                    }else{
                                      $qs = $qs . " AND " . $qv;
                                    }
                                  }
                                  // FINALIZE
                                  if(trim($qs) != "") {
                                    $qs = " AND " . $qs;
                                  }
                                  //
                                ?>

                                <div align="left">

                                  <form method="get">
                                    <div class="row">
                                      <div class="col-xl-6 col-lg-6 col-md-6">
                                        <table style="width: 100%; vertical-align: top;">
                                          <tr>
                                            <td style="vertical-align: top;">
                                              <div class="form-group margin-top1">
                                                <span class="v3-input-lbl-1">Search: <span class="text-danger"></span></span>
                                                <input type="text" class="v3-input-txt-1 input-text-value font-size-o-1" name="search" id="search" placeholder="" <?php echo ' value="' . $_GET['search'] . '" '; ?> >
                                              </div>
                                            </td>
                                            <td style="padding-top: 11px; vertical-align: top;">
                                              <input type="submit" class="btn btn-primary bg-2 modal-btn-1" style="height: 33px;" value="Search" />
                                            </td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="row">
                                          <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="form-group margin-top1">
                                              <span class="v3-input-lbl-1">Year Level: <span class="text-danger"></span></span>
                                              <select class="v3-input-txt-1 input-text-value font-size-o-1" name="yearlevel" id="yearlevel" onchange="this.form.submit();">
                                                <?php
                                                  $cv = trim($_GET['yearlevel']);
                                                  //
                                                  $yls = array("1","2","3","4","5");
                                                  // EMPTY
                                                  echo '<option value=""  >--- ALL ---</option>';
                                                  //
                                                  for ($i=0; $i<count($yls); $i++) {
                                                    $tv = $yls[$i];
                                                    $tsel = "";
                                                    if(trim(strtolower($cv)) == trim(strtolower($tv))) {
                                                      $tsel = " selected ";
                                                    }
                                                    echo '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
                                                  }
                                                  //
                                                ?>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="form-group margin-top1">
                                              <span class="v3-input-lbl-1">Program: <span class="text-danger"></span></span>
                                              <select class="v3-input-txt-1 input-text-value font-size-o-1" name="program" id="program" onchange="this.form.submit();">
                                                <?php
                                                  //
                                                  $cv = trim($_GET['program']);
                                                  //
                                                  // EMPTY
                                                  echo '<option value=""  >--- ALL ---</option>';
                                                  //
                                                  $query0 = "SELECT progcode FROM srgb.program ORDER BY progcode ASC ";
                                                  $result0 = pg_query($pgconn, $query0);
                                                  if ($result0) {
                                                    while ($row0 = pg_fetch_array($result0)) {
                                                      $tv = trim($row0['progcode']);
                                                      $tsel = "";
                                                      if(trim(strtolower($cv)) == trim(strtolower($tv))) {
                                                        $tsel = " selected ";
                                                      }
                                                      echo '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
                                                    }
                                                  }
                                                  //
                                                  //
                                                ?>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="form-group margin-top1">
                                              <span class="v3-input-lbl-1">Section: <span class="text-danger"></span></span>
                                              <select class="v3-input-txt-1 input-text-value font-size-o-1" name="section" id="section" onchange="this.form.submit();">
                                                <?php
                                                  //
                                                  $cv = trim($_GET['section']);
                                                  $cylevel = trim($_GET['yearlevel']);
                                                  $cprog = trim($_GET['program']);
                                                  //
                                                  // QRY S PROG
                                                  $fq_s = "";
                                                  if(trim($cylevel) != "") {
                                                    $fq_v = " ( TRIM(UPPER(yearlevel))=TRIM(UPPER('" . $cylevel . "')) ) ";
                                                    if(trim($fq_s) == "") {
                                                      $fq_s = trim($fq_v);
                                                    }else{
                                                      $fq_s = $fq_s . " AND ". trim($fq_v);
                                                    }
                                                  }
                                                  if(trim($cprog) != "") {
                                                    $fq_v = " ( TRIM(UPPER(courseprogram))=TRIM(UPPER('" . $cprog . "')) ) ";
                                                    if(trim($fq_s) == "") {
                                                      $fq_s = trim($fq_v);
                                                    }else{
                                                      $fq_s = $fq_s . " AND ". trim($fq_v);
                                                    }
                                                  }
                                                  if(trim($fq_s) != "") {
                                                    $fq_s = " AND " . trim($fq_s) . " ";
                                                  }
                                                  //
                                                  // EMPTY
                                                  echo '<option value=""  >--- ALL ---</option>';
                                                  //
                                                  $query0 = "SELECT section FROM tblconstudent WHERE active='1' " . " AND " . $qs_sysem . " " . $fq_s . " " . " GROUP BY section ORDER BY section ASC ";
                                                  $result0 = mysqli_query($conn_21, $query0);
                                                  if ($result0) {
                                                    while ($row0 = mysqli_fetch_array($result0)) {
                                                      $tv = trim($row0['section']);
                                                      $tsel = "";
                                                      if(trim(strtolower($cv)) == trim(strtolower($tv))) {
                                                        $tsel = " selected ";
                                                      }
                                                      echo '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
                                                    }
                                                  }
                                                  //
                                                  //
                                                ?>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-xl-3 col-lg-3 col-md-3">
                                            <div class="form-group margin-top1">
                                              <span class="v3-input-lbl-1">Page Rows: <span class="text-danger"></span></span>
                                              <input type="number" class="v3-input-txt-1 input-text-value font-size-o-1" name="rows" id="rows" min="5" placeholder="" 
                                              <?php
                                                $cv = trim($_GET['rows']);
                                                if($cv == "") {
                                                  $cv = 20;
                                                }
                                                echo ' value="' . $cv . '" ';
                                              ?>
                                               >
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                    <input type="hidden" id="page" name="page" value="<?php echo $_GET['page']; ?>">

                                  </form>

                                </div>

                                <br/>

                                <div class="c-lbl-4" align="right">Rows: 
                                  <?php
                                    $rowcount = 0;
                                    $nquery = " SELECT COUNT(*) FROM tblconstudent 
                                              WHERE active='1' " . " AND " . $qs_sysem . " " . $qs . " 
                                                ";
                                    $nresult = mysqli_query($conn_21, $nquery);
                                    if ($nresult) {
                                      $nrow = mysqli_fetch_array($nresult);
                                      $rowcount = trim($nrow[0]);
                                    }
                                    echo number_format($rowcount);
                                  ?>
                                </div>

                                <div class="table-responsive">
                                  <table class="table table-hover">
                                    <thead class="thead-1 font-size-o-1 tbl-header-1-1-thead-1" style="">
                                      <tr class="tbl-header-1-1-tr-1" style="font-size: 0.6rem;">
                                        <?php
                                          if($log_user_sem_enroll_editor > 0) {
                                            echo '<th scope="col" class="tbl-header-1-1"></th>';
                                          }
                                        ?>
                                        <th scope="col" class="tbl-header-1-1" style="width: 50px; min-width: 50px;">#</th>
                                        <th scope="col" class="tbl-header-1-1">Student ID</th>
                                        <th scope="col" class="tbl-header-1-1">Name</th>
                                        <th scope="col" class="tbl-header-1-1">Current Year Level</th>
                                        <th scope="col" class="tbl-header-1-1">Program / Course</th>
                                        <th scope="col" class="tbl-header-1-1">Section</th>
                                        <th scope="col" class="tbl-header-1-1">Contact #</th>
                                        <th scope="col" class="tbl-header-1-1">Date Submitted</th>
                                      </tr>
                                    </thead>
                                    <tbody class="font-size-o-1">
                                      <?php
                                        //
                                        //
                                        //$ps_pagerows = trim($setting_default_request_rows);
                                        //
                                        $ps_pagerows = trim($_GET['rows']);
                                        //
                                        //$ps_pagerows = 1;
                                        if (trim($ps_pagerows) == "") {
                                          $ps_pagerows = 20;
                                        }
                                        $ps_page = trim($_GET['page']);
                                        if (trim($ps_page) == "" || $ps_page < 1) {
                                          $ps_page = 1;
                                        }
                                        //echo $ps_page;
                                        //
                                        if(trim($ps_pagerows)=="") {
                                          $ps_pagerows = 20;
                                        }
                                        if($ps_pagerows < 1) {
                                          $ps_pagerows = 1;
                                        }
                                        //
                                        if(trim($ps_page)=="") {
                                          $ps_page = 1;
                                        }
                                        if($ps_page < 1) {
                                          $ps_page = 1;
                                        }
                                        //
                                        $toffset = 0;
                                        $toffset = ($ps_page - 1) * $ps_pagerows;
                                        //
                                        $numrows = 0;
                                        $maxpage = 1;
                                        //
                                        $tn = 0;
                                        //
                                        $nquery = " SELECT COUNT(*) FROM tblconstudent 
                                                  WHERE active='1' " . " AND " . $qs_sysem . " " . $qs . " 
                                                    ";
                                        $nresult = mysqli_query($conn_21, $nquery);
                                        if ($nresult) {
                                          $nrow = mysqli_fetch_array($nresult);
                                          $numrows = trim($nrow[0]);
                                        }
                                        $maxpage = ($numrows / $ps_pagerows);
                                        //echo "XXX-" . $numrows;
                                        //echo $maxpage;
                                        //echo $maxpage;
                                        //
                                        $wdecimal = -1;
                                        $wdecimal = strpos($maxpage, ".");
                                        if( trim($wdecimal) != "" && $wdecimal >= 0 ) {
                                          $ts = explode(".", $maxpage);
                                          $maxpage = $ts[0] + 1;
                                        }
                                        if( ($maxpage * $ps_pagerows) < $numrows ) {
                                          $maxpage = $maxpage + 1;
                                        }
                                        if($maxpage < 1) {
                                          if($numrows > 0) {
                                            $maxpage = 1;
                                          }
                                          $maxpage = 1;
                                        }
                                        //
                                        if ($ps_page > $maxpage) {
                                          $ps_page = $maxpage;
                                          $toffset = ($ps_page - 1) * $ps_pagerows;
                                        }
                                        if($toffset < 0) {
                                          $toffset = 0;
                                        }
                                        //echo $maxpage;
                                        //
                                        //
                                        $tpaging = "";
                                        //
                                        $tps_search = trim($_GET['search']);
                                        $tps_yearlevel = trim($_GET['yearlevel']);
                                        $tps_program = trim($_GET['program']);
                                        $tps_section = trim($_GET['section']);
                                        $tps_rows = trim($_GET['rows']);
                                        //
                                        $tlink = "manage_semester_enrollee.php?search=" . $tps_search . "&yearlevel=" . $tps_yearlevel . "&program=" . $tps_program . "&section=" . $tps_section . "&rows=" . $tps_rows . "&";
                                        if($ps_page >= 1) {
                                          //PAGING FIRST
                                          $tpaging = $tpaging . '<a class="paging-btn-1" href="' . $tlink . 'page=1"><i class="fas fa-angle-double-left"></i></a>';
                                          //PAGING PREV
                                          $tpaging = $tpaging . '<a class="paging-btn-1" href="' . $tlink . 'page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                          //SPACE
                                          $tpaging = $tpaging . '';
                                        }
                                        //echo $maxpage;
                                        for($i=1; $i<=$maxpage; $i++) {
                                          $tstyle = "";
                                          if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                            $tstyle = " active ";
                                          }
                                          $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="' . $tlink . 'page=' . $i . '">' . $i . '</a>';
                                        }
                                        if($ps_page <= $maxpage) {
                                          //SPACE
                                          $tpaging = $tpaging . '';
                                          //PAGING NEXT
                                          $tpaging = $tpaging . '<a class="paging-btn-1" href="' . $tlink . 'page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                          //PAGING LAST
                                          $tpaging = $tpaging . '<a class="paging-btn-1" href="' . $tlink . 'page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                        }
                                        //
                                        //
                                        //
                                        //
                                        $query = "SELECT * FROM tblconstudent 
                                                  WHERE active='1' " . " AND " . $qs_sysem . " " . $qs . " 
                                                  ORDER BY datesubmitted ASC 
                                                  LIMIT " . $toffset . "," . $ps_pagerows . " 
                                        ";
                                        $result = mysqli_query($conn_21, $query);
                                        if ($result) {
                                          $n = 0;
                                          //
                                          $n = ($ps_page-1) * $ps_pagerows;
                                          if (trim($n) == "") {
                                            $n = 0;
                                          }
                                          //
                                          while ($row = mysqli_fetch_array($result)) {
                                            $n++;
                                            //
                                            $studid = trim($row['studid']);
                                            $ln = trim($row['lastname']);
                                            $fn = trim($row['firstname']);
                                            $mn = trim($row['middlename']);
                                            $ext = trim($row['extension']);
                                            $fullname = $ln . ", " . $fn;
                                            if($mn != "") {
                                              $fullname = $fullname . " " . $mn;
                                            }
                                            if($ext != "") {
                                              $fullname = $fullname . " " . $ext;
                                            }
                                            //
                                            $contactno = trim($row['contactno']);
                                            //
                                            $yearlevel = trim($row['yearlevel']);
                                            $course = trim($row['courseprogram']);
                                            $section = trim($row['section']);
                                            //
                                            $datesubmitted = trim($row['datesubmitted']);
                                            //
                                            //
                                            /*
                                            $query0 = "SELECT studlevel,studmajor FROM srgb.semstudent WHERE TRIM(LOWER(studid))='" . trim(strtolower($studid)) . "' ORDER BY sy DESC, sem DESC LIMIT 1 ";
                                            $result0 = pg_query($pgconn, $query0);
                                            if ($result0) {
                                              while ($row0 = pg_fetch_array($result0)) {
                                                $yearlevel = trim($row0['studlevel']);
                                                $course = trim($row0['studmajor']);
                                              }
                                            }
                                            */
                                            //
                                            //
                                            //
                                            $fm = '
                                            ';
                                            //
                                            $tp1 = '';
                                            if($log_user_sem_enroll_editor > 0) {
                                              $tp1 = '<td class="tbl-row-item-1" style="vertical-align: top;"><a class="btn btn-success btn-table-2-1 no-outline" href="manage_semester_enrollee_update.php?studid=' . $studid . '">UPDATE</a></td>';
                                            }
                                            //
                                            echo '
                                              <tr style="font-size: 0.7rem;">
                                                ' . $tp1 . '
                                                <td class="tbl-row-item-1">' . $n . '</td>
                                                <td class="tbl-row-item-1">' . $studid . '</td>
                                                <td class="tbl-row-item-1">' . $fullname . '</td>
                                                <td class="tbl-row-item-1">' . $yearlevel . '</td>
                                                <td class="tbl-row-item-1">' . $course . '</td>
                                                <td class="tbl-row-item-1">' . $section . '</td>
                                                <td class="tbl-row-item-1">' . $contactno . '</td>
                                                <td class="tbl-row-item-1">' . $datesubmitted . '</td>
                                              </tr>
                                            ';
                                          }
                                        }
                                        
                                      ?>
                                  </tbody>
                                </table>
                              </div>

                                <?php 
                                  echo '
                                      <hr>

                                      ' . $tpaging . '

                                  ';
                                ?>

                              <div align="right">
                                <br/>
                                <br/>
                                <?php
                                  $tps_search = trim($_GET['search']);
                                  $tps_yearlevel = trim($_GET['yearlevel']);
                                  $tps_program = trim($_GET['program']);
                                  $tps_section = trim($_GET['section']);
                                  $tps_rows = trim($_GET['rows']);
                                  $tps_page = trim($_GET['page']);
                                  //
                                  $tlink_all = "semester_enrollee_print.php?search=" . $tps_search . "&yearlevel=" . $tps_yearlevel . "&program=" . $tps_program . "&section=" . $tps_section . "&rows=" . $tps_rows . "&all=1";
                                  $tlink_pageonly = "semester_enrollee_print.php?search=" . $tps_search . "&yearlevel=" . $tps_yearlevel . "&program=" . $tps_program . "&section=" . $tps_section . "&rows=" . $tps_rows . "&page=" . $tps_page . "&all=0";
                                  //
                                ?>
                                <a class="btn btn-primary bg-2 modal-btn-1" style="margin-top: 2px; width: 150px; text-align: left;" target="_blank" href="<?php echo $tlink_all; ?>"><i class="fa fa-print" aria-hidden="true"></i> PRINT ALL</a>
                                <br/>
                                <a class="btn btn-primary bg-2 modal-btn-1" style="margin-top: 2px; width: 150px; text-align: left;" target="_blank" href="<?php echo $tlink_pageonly; ?>"><i class="fa fa-print" aria-hidden="true"></i> PRINT THIS PAGE ONLY</a>
                              </div>


                              <div align="right">
                                <?php
                                  if($log_user_role_isadmin > 0) {
                                    echo '
                                      <br/>
                                      <form method="post"><input type="submit" class="btn btn-primary bg-2 modal-btn-1" name="btnupd_enroll_ylcrs" value="UPDATE YEAR LEVEL & COURSE" /></form>
                                    ';
                                  }
                                ?>
                              </div>


                              <br/>
                              <div align="left" style="color: #404040; font-size: 0.8rem; padding: 2px 10px;">
                                <div><b>Printing Note:</b></div>
                                <div style="padding-left: 20px;">
                                  <div>&bullet; If you want landscape/portrait change in upper-right part of print dialog.</div>
                                  <div>&bullet; If DSSC logo or table header background doesn't show, click more settings on upper-right part of print dialog then check background graphics.</div>
                                  <div>&bullet; If you want to show print date and time, and page link, click more settings on upper-right part of print dialog then check headers and footers. Unchecking this option will hide them.</div>
                                </div>
                              </div>
                              <br/>

                                
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

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php include "footer-imports.php"; ?>


</body>

</html>
