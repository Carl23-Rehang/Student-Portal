<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  if (strtolower(trim($log_user_type)) != strtolower(trim("student"))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
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

  <title>Grades</title>

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


              <?php
                $tn = 0;
                //echo "XXX1";
                //echo strtoupper(trim($log_userid));
                //echo $log_userid;
                $result = pg_query($pgconn, "SELECT sy from srgb.registration where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' group by sy order by sy DESC");
                //echo $log_userid;
                if ($result) {
                  //echo "XXX";
                  $sy = "";
                  $tsy = "";
                  $sem = "";
                  $tsem = "";
                  $fd = "";
                  $tbld = "";
                  $studcount = 0;
                  $tc = "";
                  $n = 0;
                  while ($row = pg_fetch_array($result)) {
                    $tn++;
                    $n++;
                    //
                    $tsy = strtoupper(trim($row['sy']));
                    //
                    $result1 = pg_query($pgconn, "SELECT sem from srgb.registration where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' group by sem order by sem ASC");
                    //echo $log_userid;
                    if ($result1) {
                      while ($row1 = pg_fetch_array($result1)) {
                        //
                        $tsem = strtoupper(trim($row1['sem']));
                        //
                        $semt = "";
                        if(strtolower(trim($tsem)) == strtolower(trim("1"))) {
                          $semt = "1st Semester";
                        }
                        if(strtolower(trim($tsem)) == strtolower(trim("2"))) {
                          $semt = "2nd Semester";
                        }
                        if(strtolower(trim($tsem)) == strtolower(trim("S"))) {
                          $semt = "Summer";
                        }
                        //
                        //
                        $tc = "";
                        $n0 = 0;
                        //
                        $result0 = pg_query($pgconn, "SELECT subjcode,section,grade,gcompl from srgb.registration where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($tsem)) . "' order by subjcode ASC, section ASC");
                        //echo $log_userid;
                        if ($result0) {
                          while ($row0 = pg_fetch_array($result0)) {
                            //
                            $subjcode = strtoupper(trim($row0['subjcode']));
                            $section = strtoupper(trim($row0['section']));
                            $subjdesc = "";
                            //
                            $grade = strtoupper(trim($row0['grade']));
                            $gcompl = strtoupper(trim($row0['gcompl']));
                            $fgrade = "";
                            //
                            $fgrade = strtoupper(trim($grade));
                            if ( trim($grade) == "" || strtoupper(trim($grade)) == strtoupper(trim("INC")) || strtoupper(trim($grade)) == strtoupper(trim("INCOMPLETE")) || strtoupper(trim($grade)) == strtoupper(trim("DR")) ) {
                              if ( trim($gcompl) != "" ) {
                                if ( trim($fgrade) == "" ) {
                                  $fgrade = strtoupper(trim($gcompl));
                                }else{
                                  $fgrade = trim($fgrade) . " / " . strtoupper(trim($gcompl));
                                }
                              }
                            }
                            //
                            $fresult2 = pg_query($pgconn, "SELECT * from srgb.subject where LOWER(TRIM(subjcode))='" . strtolower(trim($subjcode)) . "' limit 1");
                            if ($fresult2) {
                              while ($frow2 = pg_fetch_array($fresult2)) {
                                $subjdesc = trim($frow2['subjdesc']);
                              }
                            }
                            //
                            $n0++;
                            $tc = $tc . '
                                <tr style="font-size: 0.7rem;">
                                  <th scope="row">' . $n0 . '</th>
                                  <td>' . $subjcode . '</td>
                                  <td class="td-g-desc-1">' . $subjdesc . '</td>
                                  <td>' . $section . '</td>
                                  <td>' . $fgrade . '</td>
                                </tr>
                            ';
                            //
                          } //END WHILE
                        } //END RESULT
                        //
                        //
                        if(trim($tc) != "") {
                          $tbld = $tbld . '
                              <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                                <div align="left" style="font-size: 0.7rem;">
                                  <b>' . $semt . '</b>
                                </div>
                                
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                  <thead class="thead-1 font-size-o-1">
                                    <tr style="font-size: 0.6rem;">
                                      <th scope="col">#</th>
                                      <th scope="col">Subject Code</th>
                                      <th scope="col">Description</th>
                                      <th scope="col">Section</th>
                                      <th scope="col">Grade</th>
                                    </tr>
                                  </thead>
                                  <tbody class="font-size-o-1">
                                    ' . $tc . '
                                  </tbody>
                                </table>
                                </div>
                              </div>
                          ';
                          //echo $tbld;
                          $fd = $fd . $tbld;
                        }
                        $tbld = "";
                        //
                      } //END WHILE
                    } //END RESULT
                    //
                    //
                    //
                    if(trim($fd) != "") {
                      echo '
                        <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                          <div align="center">

                            <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                              <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                  <div align="left">
                                    <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.8rem;"><span class="color-blue-1">' . strtoupper(trim($tsy)) . '</span></h6>
                                  </div>
                                  
                                </div>
                                <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                  
                                      ' . $fd . '

                                  <hr>
                                  
                                </div>
                              </div>

                            </div>
                            
                          </div>

                        </div>
                      ';
                      $fd = "";
                    }
                    //
                  } //END WHILE
                  //
                  //
                } //END RESULT

              ?>



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
