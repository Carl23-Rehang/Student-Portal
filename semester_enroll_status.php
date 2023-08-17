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
  // CHECK ENROLL STATUS
  include_once "semester_enroll_check.php";
  $log_use_enroll_stat = trim($_SESSION[$appid . "c_user_enroll_stat"]);
  if($log_use_enroll_stat <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
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


          <div align="center">

            <div class="row align-items-center">

              <div class="col-sm-12">
                <!--  -->


                <?php

                        // LOAD NOTES
                        $r_note = "";
                        $ssql = " SELECT value from tblsettings WHERE TRIM(LOWER(name))=TRIM(LOWER('enrollment-result-msg-error')) ";
                        $sqry = mysqli_query($conn,$ssql);
                        while($sdat=mysqli_fetch_array($sqry)) {
                            //
                            $r_note = $r_note . ($sdat['value']);
                            //
                        }

                        echo '
                            <div class="col-sm-6" style="margin-top: 2rem; margin-bottom: 16rem;">
                              <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                  <div>
                                    <h6 class="m-0 font-weight-bold text-primary-1">' . $setting_enrollment_title . '</h6>
                                  </div>
                                </div>
                                  <div class="div-description1" align="left">
                                    <span class="span-description1 text-danger"></span>
                                  </div>
                                <!-- Card Body -->
                                <div class="card-body padding-lr1" style="padding-top: 2rem; padding-bottom: 3rem;">

                                  <div class="result-note-1 ' . $ts_class . ' " style="font-size: 0.8rem;" align="left">
                                    
                                    ' . $r_note . '

                                    <br/>

                                  </div>

                                </div>
                              </div>
                            </div>
                        ';

                ?>

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


</body>

</html>
