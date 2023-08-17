<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  //
  //
  /*
  $data = array( 
    array("NAME" => "John Doe", "EMAIL" => "john.doe@gmail.com", "GENDER" => "Male", "COUNTRY" => "United States"), 
    array("NAME" => "Gary Riley", "EMAIL" => "gary@hotmail.com", "GENDER" => "Male", "COUNTRY" => "United Kingdom"), 
    array("NAME" => "Edward Siu", "EMAIL" => "siu.edward@gmail.com", "GENDER" => "Male", "COUNTRY" => "Switzerland"), 
    array("NAME" => "Betty Simons", "EMAIL" => "simons@example.com", "GENDER" => "Female", "COUNTRY" => "Australia"), 
    array("NAME" => "Frances Lieberman", "EMAIL" => "lieberman@gmail.com", "GENDER" => "Female", "COUNTRY" => "United Kingdom") 
);
  function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}
// Excel file name for download 
$fileName = "codexworld_export_data-" . date('Ymd') . ".csv"; 
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel"); 
 
$flag = false; 
foreach($data as $row) { 
    if(!$flag) { 
        // display column names as first row 
        echo implode("\t", array_keys($row)) . "\n"; 
        $flag = true; 
    } 
    // filter data 
    array_walk($row, 'filterData'); 
    echo implode("\t", array_values($row)) . "\n"; 
} 
 
exit;
*/
  //
  //
  $td_cols = '"Name","Major","Year","Sex","Remarks"';
  //
  $tn = 0;
  //
  $fsy = trim($_GET['sy']);
  $fsem = trim($_GET['sem']);
  $fsubjcode = trim($_GET['subjcode']);
  $fsection = trim($_GET['section']);
  $fsems = "";
  if(strtolower(trim($fsem))==strtolower(trim("1"))) {
    $fsems = "1st Semester";
  }
  if(strtolower(trim($fsem))==strtolower(trim("2"))) {
    $fsems = "2nd Semester";
  }
  if(strtolower(trim($fsem))==strtolower(trim("s"))) {
    $fsems = "Summer";
  }
  //echo $fsy . "--" . $fsem . "--" . $fsubjcode . "--" . $fsection;
  //
  //echo $log_userid;
  //pg_set_client_encoding($pgconn, "ALT");
  $result = pg_query($pgconn, "SELECT A.sy,A.sem,A.studid,A.subjcode,A.section,A.remarks,B.studlastname,B.studfirstname,B.studmidname from srgb.registration A INNER JOIN srgb.student B ON A.studid=B.studid where  UPPER(TRIM(A.sy))='" . strtoupper(trim($fsy)) . "' and UPPER(TRIM(A.sem))='" . strtoupper(trim($fsem)) . "' and UPPER(TRIM(A.subjcode))='" . strtoupper(trim($fsubjcode)) . "' and UPPER(TRIM(A.section))='" . strtoupper(trim($fsection)) . "' order by B.studlastname ASC,B.studfirstname ASC,B.studmidname ASC ");
  //echo $log_userid;
  if ($result) {
    $sy = "";
    $tsy = "";
    $fd = "";
    $n = 0;
    //
    //
    while ($row = pg_fetch_array($result)) {
      $tn++;
      $n++;
      //echo $n;
      //
      $subjdesc = "";
      $studcount = 0;
      //
      $studid = strtoupper(trim($row['studid']));
      $remarks = trim($row['remarks']);
      $studname = "";
      $gender = "";
      $studmajor = "";
      $studyear = "";
      $remarks = "";
      //
      //echo " -XXX- " . $studid;
      //GET NAME
      $result2 = pg_query($pgconn, "SELECT * from srgb.student where UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' limit 5");
      if ($result2) {
        while ($row2 = pg_fetch_array($result2)) {
          //$ln = utf8_encode(strtoupper(trim($row2['studfirstname'])));
          //$ln = utf8_decode(strtoupper(trim($row2['studfirstname'])));
          //$ln = utf8_decode($ln);
          
          $studname = strtoupper(trim($row2['studlastname'])) . ", " . strtoupper(trim($row2['studfirstname'])) . " " . strtoupper(trim($row2['studmidname']));
          $studname = mb_convert_encoding($studname, "UTF-8", "auto");
          $studname = str_replace("?", "Ñ", $studname);
          $gender = strtoupper(trim($row2['studgender']));
        }
      }
      //GET MAJOR YEAR
      $result2 = pg_query($pgconn, "SELECT * from srgb.semstudent where UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' and UPPER(TRIM(sy))='" . strtoupper(trim($fsy)) . "' and UPPER(TRIM(sem))='" . strtoupper(trim($fsem)) . "' order by sy DESC,sem DESC limit 1");
      if ($result2) {
        while ($row2 = pg_fetch_array($result2)) {
          $studmajor = strtoupper(trim($row2['studmajor']));
          $studyear = strtoupper(trim($row2['studlevel']));
        }
      }
      //GET SUBJECT DESC
      $result2 = pg_query($pgconn, "SELECT * from srgb.subject where LOWER(TRIM(subjcode))='" . strtolower(trim($fsubjcode)) . "' limit 1");
      if ($result2) {
        while ($row2 = pg_fetch_array($result2)) {
          $subjdesc = trim($row2['subjdesc']);
        }
      }
      //
      //
      //
      $link = "profile.php?t=student&id=" . $studid;
      $fd = $fd . '
        <tr style="font-size: 0.6rem;">
          <th scope="row">' . $n . '</th>
          <td><a href="' . $link . '">' . $studid . '</a></td>
          <td><a href="' . $link . '">' . $studname . '</a></td>
          <td>' . $studmajor . '</td>
          <td>' . $studyear . '</td>
          <td>' . $gender . '</td>
          <td>' . $remarks . '</td>
        </tr>
      ';


      //
    } //END WHILE
    //
    //
    $tlink = "classlist-print.php?sy=" . $fsy . "&sem=" . $fsem . "&subjcode=" . $fsubjcode . "&section=" . $fsection . "";
    $tlink_export_excel = "classlist-export-excel.php?sy=" . $fsy . "&sem=" . $fsem . "&subjcode=" . $fsubjcode . "&section=" . $fsection . "";
    //
  //
  if(isset($_POST['export_excel'])) {
    //
    $fileName = "classlist-" . $fsubjcode . "-" . $fsection . ".csv";
    //
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Type: application/vnd.ms-excel");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    //
    echo $td_cols;
    //
  }
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

  <title>Faculty Subject Load</title>

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
                  //
                  if(trim($fd) != "") {
                    echo '
                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-xl-8 col-lg-10 col-md-10 col-sm-10" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">' . strtoupper(trim($fsubjcode)) . " : " . strtoupper(trim($subjdesc)) . " : " . $fsems . '</span></h6>
                                  <div class="position-top-right card-title-top-right" style="background: #f8f9fc;">
                                    <a class="c-link-g-1 card-title-link-1" href="' . $tlink_export_excel . '" target="_blank" style="display: inline-block;"><i class="fa fa-file-excel" aria-hidden="true"></i> EXCEL</a>   <a class="c-link-g-1 card-title-link-1" href="' . $tlink . '" target="_blank" style="display: inline-block;"><i class="fa fa-print" aria-hidden="true"></i> PRINT</a>
                                  </div>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                

                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1" style="font-size: 0.6rem;">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col" style="width: 50px; min-width: 50px; max-width: 50px;">#</th>
                                    <th scope="col" style="width: 100px; min-width: 100px; max-width: 100px;">ID No.</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" style="width: 100px; min-width: 100px; max-width: 100px;">Major</th>
                                    <th scope="col" style="width: 50px; min-width: 50px; max-width: 50px;">Year</th>
                                    <th scope="col" style="width: 50px; min-width: 50px; max-width: 50px;">Sex</th>
                                    <th scope="col" style="width: 100px; min-width: 100px; max-width: 100px;">Remarks</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1" style="font-size: 0.7rem;">
                                  ' . $fd . '
                                </tbody>
                              </table>
                              </div>

                                <hr>
                                
                              <br/>
                              <div align="left" style="color: #404040; font-size: 0.8rem; padding: 2px 10px;">
                                <div><b>Printing Note:</b></div>
                                <div style="padding-left: 20px;">
                                  <div>&bullet; If you want landscape/portrait change in upper-right part of print dialog.</div>
                                  <div>&bullet; If DSSC logo or table header background does not show, click more settings on upper-right part of print dialog then check background graphics.</div>
                                  <div>&bullet; If you want to show print date and time, and page link, click more settings on upper-right part of print dialog then check headers and footers. Unchecking this option will hide them.</div>
                                </div>
                              </div>
                              <br/>

                              </div>
                            </div>

                          </div>
                          
                        </div>

                      </div>
                    ';
                    //
                    $fd = "";
                  }
                  //
                }

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


  <?php include "footer-imports.php"; ?>


</body>

</html>
