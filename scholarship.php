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

  <title>Scholarship</title>

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
                $result = pg_query($pgconn, "SELECT sy from srgb.semstudent where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' group by sy order by sy DESC ");
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
                    //
                    $tsy = strtoupper(trim($row['sy']));
                    //
                    $result1 = pg_query($pgconn, "SELECT sem from srgb.semstudent where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' group by sem order by sem ASC ");
                    //echo $log_userid;
                    if ($result1) {
                      while ($row1 = pg_fetch_array($result1)) {
                        //
                        $n++;
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
                        $result2 = pg_query($pgconn, "SELECT scholarstatus from srgb.semstudent where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($tsem)) . "' order by scholarstatus ASC ");
                        //echo $log_userid;
                        if ($result2) {
                          while ($row2 = pg_fetch_array($result2)) {
                            //
                            $tscholar = strtoupper(trim($row2['scholarstatus']));
                            $tscholardesc = "";
                            //
                            //GET DETAILS
                            $result3 = pg_query($pgconn, "SELECT schdesc from srgb.scholar where UPPER(TRIM(schcode))='" . strtoupper(trim($tscholar)) . "' LIMIT 1 ");
                            //echo $log_userid;
                            if ($result3) {
                              while ($row3 = pg_fetch_array($result3)) {
                                //
                                $tscholardesc = strtoupper(trim($row3['schdesc']));
                                //
                                //
                              }
                            }
                            //
                            $tc = $tc . '
                                <tr style="font-size: 0.6rem;">
                                  <th scope="row">' . $n . '</th>
                                  <td>' . $tsy . '</td>
                                  <td>' . $tsem . '</td>
                                  <td>' . $tscholar . '</td>
                                  <td>' . $tscholardesc . '</td>
                                </tr>
                            ';
                            //
                          }
                        }
                        //
                      }
                    }
                    //
                    //
                    $tlink = '';
                    //
                    //
                    //
                    //
                  } //END WHILE
                  //
                  //
                } //END RESULT
                //
                //
                if(trim($tc) != "") {
                  $tbld = $tbld . '
                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                        <div align="left">
                          <b></b>
                        </div>
                        
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                          <thead class="thead-1 font-size-o-1">
                            <tr style="font-size: 0.7rem;">
                              <th scope="col">#</th>
                              <th scope="col">S.Y.</th>
                              <th scope="col">Semester</th>
                              <th>Scholarship Code</th>
                              <th>Description</th>
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
                if(trim($fd) != "") {
                  echo '
                    <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                      <div align="center">

                        <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                          <div class="card shadow mb-4">
                            <div class="card-header py-3">
                              <div align="left">
                                <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">' . "Scholarship" . '</span></h6>
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
