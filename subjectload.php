<?php session_start(); include "connect.php"; error_reporting(0);
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
                //echo $log_userid;
                $result = pg_query($pgconn, "SELECT sy from srgb.semsubject where UPPER(TRIM(facultyid))='" . strtoupper(trim($log_userid)) . "' group by sy order by sy DESC ");
                //echo $log_userid;
                if ($result) {
                  $sy = "";
                  $tsy = "";
                  $sem = "";
                  $tsem = "";
                  $fd = "";
                  $tbld = "";
                  $tc = "";
                  $n = 0;
                  while ($row = pg_fetch_array($result)) {
                    $tn++;
                    $n++;
                    //
                    $tsy = strtoupper(trim($row['sy']));
                    $sy = strtoupper(trim($row['sy']));
                    //
                    $result2 = pg_query($pgconn, "SELECT sem from srgb.semsubject where UPPER(TRIM(facultyid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' group by sem order by sem DESC ");
                    //echo $log_userid;
                    if ($result2) {
                      $sem = "";
                      $tsem = "";
                      $tc = "";
                      $n = 0;
                      while ($row2 = pg_fetch_array($result2)) {
                        //$tn++;
                        //$n++;
                        //
                        $sem = strtoupper(trim($row2['sem']));
                        $tsem = strtoupper(trim($row2['sem']));
                        $semt = "";
                        if(strtolower(trim($sem)) == strtolower(trim("1"))) {
                            $semt = "1st Semester";
                        }
                        if(strtolower(trim($sem)) == strtolower(trim("2"))) {
                            $semt = "2nd Semester";
                        }
                        if(strtolower(trim($sem)) == strtolower(trim("S"))) {
                            $semt = "Summer";
                        }
                        //
                        //
                        $result3 = pg_query($pgconn, "SELECT subjcode,section from srgb.semsubject where UPPER(TRIM(facultyid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' order by subjcode ASC,section ASC ");
                        //echo $log_userid;
                        if ($result3) {
                          $n = 1;
                          while ($row3 = pg_fetch_array($result3)) {
                            //$tn++;
                            //$n++;
                            //
                            //
                            $subjcode = strtoupper(trim($row3['subjcode']));
                            $section = strtoupper(trim($row3['section']));
                            $subjdesc = "";
                            $studcount = 0;
                            //echo "$subjcode";
                            //
                            $fresult2 = pg_query($pgconn, "SELECT * from srgb.subject where LOWER(TRIM(subjcode))='" . strtolower(trim($subjcode)) . "' limit 1");
                            if ($fresult2) {
                              while ($frow2 = pg_fetch_array($fresult2)) {
                                $subjdesc = trim($frow2['subjdesc']);
                              }
                            }
                            //
                            $fresult3 = pg_query($pgconn, "SELECT COUNT(*) from srgb.registration where  UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' and UPPER(TRIM(sem))='" . strtoupper(trim($tsem)) . "' and UPPER(TRIM(subjcode))='" . strtoupper(trim($subjcode)) . "' and UPPER(TRIM(section))='" . strtoupper(trim($section)) . "' ");
                            if ($fresult3) {
                              while ($frow3 = pg_fetch_array($fresult3)) {
                                $studcount = trim($frow3[0]);
                              }
                            }
                            //
                            //
                            $tlink = 'classlist.php?sy=' . $tsy . '&sem=' . $tsem . '&subjcode=' . $subjcode . '&section=' . $section . '';
                            //
                            $tc = $tc . '
                              <tr style="font-size: 0.7rem;">
                                <th scope="row">' . $n . '</th>
                                <td>' . $subjcode . '</td>
                                <td>' . $subjdesc . '</td>
                                <td>' . $section . '</td>
                                <td><a href="' . $tlink . '" target="_blank">' . $studcount . '</a> <a class="btn btn-primary bg-2 btn-c-5" style="width: 90px; margin-left: 10px;" href="' . $tlink . '" target="_blank">View Student</a></td>
                              </tr>
                            ';
                            //
                            $n++;
                            //
                          } //END WHILE DATA
                        }
                        //
                        $tbld = $tbld . '
                            <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                              <div align="left" class="lbl-1" style="font-size: 0.7rem;">
                                <b>' . $semt . '</b>
                              </div>
                              
                              <div class="table-responsive">
                              <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col" style="width: 50px; min-width: 50px; max-width: 50px;">#</th>
                                    <th scope="col" style="width: 120px; min-width: 120px; max-width: 120px;">Subject Code</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" style="width: 100px; min-width: 100px; max-width: 100px;">Section</th>
                                    <th scope="col" style="width: 160px; min-width: 160px; max-width: 160px;">Student Count</th>
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
                        $tc = "";
                        $tbld = "";
                        //
                      } //END WHILE SEM
                    }
                    //
                    echo '
                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary"><span class="color-blue-1">' . strtoupper(trim($sy)) . '</span></h6>
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
                    //
                  } //END WHILE SY
                }
                //
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
            <br/>
            <br/>
            </div>
          </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->

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
