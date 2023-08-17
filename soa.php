<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  if (strtolower(trim($log_user_type)) != strtolower(trim("student"))) {
      header("Location: index.php");
    //echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
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

  <title>Statement Of Account</title>

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


              <div class="col-sm-12">
              <div class="row">

              <?php
                $tn = 0;
                //echo "XXX1";
                //
                $csy = trim(strtoupper($_GET['sy']));
                $csem = trim(strtoupper($_GET['sem']));
                //
                $semt = "";
                if(strtolower(trim($csem)) == strtolower(trim("1"))) {
                  $semt = "1st Semester";
                }
                if(strtolower(trim($csem)) == strtolower(trim("2"))) {
                  $semt = "2nd Semester";
                }
                if(strtolower(trim($csem)) == strtolower(trim("S"))) {
                  $semt = "Summer";
                }
                //
                //
                $n = 0;
                $total = 0;
                $tc = "";
                //GET ASSESSMENT
                $result1 = pg_query($pgconn, "SELECT feecode,amt from srgb.ass_details where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($csy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($csem)) . "' ORDER BY feecode ASC, amt ASC ");
                //echo $log_userid;
                if ($result1) {
                  while ($row1 = pg_fetch_array($result1)) {
                    //
                    $feecode = trim($row1['feecode']);
                    $amt = trim($row1['amt']);
                    $feedesc = "";
                    //
                    $fresult2 = pg_query($pgconn, "SELECT * from srgb.fees where LOWER(TRIM(feecode))='" . strtolower(trim($feecode)) . "' limit 1");
                    if ($fresult2) {
                      while ($frow2 = pg_fetch_array($fresult2)) {
                        $feedesc = trim($frow2['feedesc']);
                      }
                    }
                    //
                    if (trim($feecode) != "" && trim($amt) != "") {
                      //
                      $n++;
                      $total = $total + $amt;
                      $tc = $tc . '
                          <tr style="font-size: 0.7rem;">
                            <th scope="row">' . $n . '</th>
                            <td>' . $feecode . '</td>
                            <td class="td-g-desc-1">' . $feedesc . '</td>
                            <td>' . number_format($amt, 2) . '</td>
                          </tr>
                      ';
                      //
                    }
                    //
                  } // END WHILE
                } //END RESULT
                //ADD TOTAL
                $tc = $tc . '
                    <tr style="font-size: 0.7rem;">
                      <th scope="row"></th>
                      <td></td>
                      <td class="td-g-desc-1"><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr style="font-size: 0.7rem;">
                      <th scope="row"></th>
                      <td></td>
                      <td class="td-g-desc-1"><b>Total Amount: </b></td>
                      <td><b>' . number_format($total, 2) . '</b></td>
                    </tr>
                ';
                //
                //SHOW ASSESSMENT
                echo '
                  <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                    <div align="center">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div class="card shadow mb-4">
                          <div class="card-header py-3">
                            <div align="left">
                              <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">' . 'Assessment' . '</span></h6>
                            </div>
                            
                          </div>
                          <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                            
                              <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                                <div align="left">
                                  <b></b>
                                </div>
                                
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                  <thead class="thead-1 font-size-o-1">
                                    <tr style="font-size: 0.6rem;">
                                      <th scope="col">#</th>
                                      <th>Fee Code</th>
                                      <th>Description</th>
                                      <th>Amount</th>
                                    </tr>
                                  </thead>
                                  <tbody class="font-size-o-1">
                                    ' . $tc . '
                                  </tbody>
                                </table>
                                </div>
                              </div>

                            <hr>
                            
                          </div>
                        </div>

                      </div>
                      
                    </div>

                  </div>
                ';
                //
                //
                $n = 0;
                $total = 0;
                $tc = "";
                //GET TOTAL PAID
                $qry1 = "
                          SELECT 
                            b.orno,b.feecode,c.feedesc,b.amt,a.paydate 
                          FROM srgb.collection_header AS a 
                          LEFT JOIN srgb.collection_details AS b ON b.orno=a.orno 
                          LEFT JOIN srgb.fees AS c ON c.feecode=b.feecode 
                          WHERE UPPER(TRIM(sy))='" . strtoupper(trim($csy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($csem)) . "' 
                          AND UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' 
                          ORDER BY a.paydate ASC, b.orno ASC, b.feecode ASC, b.amt ASC 
                ";
                $result1 = pg_query($pgconn, $qry1);
                //echo $log_userid;
                if ($result1) {
                  while ($row1 = pg_fetch_array($result1)) {
                    //
                    $orno = trim($row1['orno']);
                    $feecode = trim($row1['feecode']);
                    $feedesc = trim($row1['feedesc']);
                    $amt = trim($row1['amt']);
                    $paydate = trim($row1['paydate']);
                    //
                    $n++;
                    $total = $total + $amt;
                    $tc = $tc . '
                        <tr style="font-size: 0.7rem;">
                          <th scope="row">' . $n . '</th>
                          <td>' . $orno . '</td>
                          <td>' . $feecode . '</td>
                          <td class="td-g-desc-1">' . $feedesc . '</td>
                          <td>' . number_format($amt, 2) . '</td>
                          <td>' . $paydate . '</td>
                        </tr>
                    ';
                    //
                    //
                    //
                  } //END WHILE
                } //END RESULT
                //
                //ADD TOTAL
                $tc = $tc . '
                    <tr style="font-size: 0.7rem;">
                      <th scope="row"></th>
                      <td></td>
                      <td></td>
                      <td class="td-g-desc-1"><b></b></td>
                      <td><b></b></td>
                      <td><b></b></td>
                    </tr>
                    <tr style="font-size: 0.7rem;">
                      <th scope="row"></th>
                      <td></td>
                      <td></td>
                      <td class="td-g-desc-1"><b>Total Amount: </b></td>
                      <td><b>' . number_format($total, 2) . '</b></td>
                      <td></td>
                    </tr>
                ';
                //SHOW PAYMENTS
                echo '
                  <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                    <div align="center">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div class="card shadow mb-4">
                          <div class="card-header py-3">
                            <div align="left">
                              <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">' . 'Payment' . '</span></h6>
                            </div>
                            
                          </div>
                          <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                            
                              <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                                <div align="left">
                                  <b></b>
                                </div>
                                
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                  <thead class="thead-1 font-size-o-1">
                                    <tr style="font-size: 0.6rem;">
                                      <th scope="col">#</th>
                                      <th>OR #</th>
                                      <th>Fee Code</th>
                                      <th>Description</th>
                                      <th>Amount</th>
                                      <th>Date</th>
                                    </tr>
                                  </thead>
                                  <tbody class="font-size-o-1">
                                    ' . $tc . '
                                  </tbody>
                                </table>
                                </div>
                              </div>

                            <hr>

                            
                          </div>
                        </div>

                      </div>
                      
                    </div>

                  </div>
                ';
                //

              ?>

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
