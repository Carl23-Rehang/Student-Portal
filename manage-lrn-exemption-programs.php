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
    if($_POST['btnadd']) {
      $program = trim($_POST['program']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($program) == "") {
        $errn++;
        $errmsg = $errmsg . "Program required. ";
      }
      //
      //CHECK PROGRAM EXIST
      $ten = 0;
      $sresult = mysqli_query($conn, "SELECT * from tblexempted_lrn_program where LOWER(TRIM(program))='" . strtolower(trim($program)) . "' AND  LOWER(TRIM(active))='" . strtolower(trim("1")) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = mysqli_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        $errn++;
        $errmsg = $errmsg . "Program already exist. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //
        //SAVE ROLE
        $query = "INSERT INTO tblexempted_lrn_program (program,active) VALUES ('" . $program . "','" . "1" . "') ";
        $result = mysqli_query($conn,$query);
        //
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Success!</strong> Program added.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Error!</strong> ' . $errmsg . '</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btnupdate']) {
      $id = trim($_POST['id']);
      //
      $program = trim($_POST['program']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($program) == "") {
        $errn++;
        $errmsg = $errmsg . "Program required. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tblexempted_lrn_program set program='" . $program . "'  WHERE id='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Success!</strong> Program updated.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Error!</strong> ' . $errmsg . '</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btndelete']) {
      $id = trim($_POST['id']);
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "UPDATE tblexempted_lrn_program SET active='0'  WHERE id='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Success!</strong> Program deactivated.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong>Error!</strong> ' . $errmsg . '</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    //
    //LOAD PROGRAMS
    $opt_programs = [];
    $opt_programs_sv = "";
    $sresult = pg_query($pgconn, "SELECT * from srgb.program ORDER BY progdesc ASC ");
    if ($sresult) {
      while ($srow = pg_fetch_array($sresult)) {
        //
        $tv = [];
        $tv[0] = trim($srow['progcode']);
        $tv[1] = trim($srow['progdesc']);
        $opt_programs[count($opt_programs)] = $tv;
        //
        $opt_programs_sv = $opt_programs_sv . '<option value="' . trim($srow['progcode']) . '">' . trim($srow['progdesc']) . '</option>';
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

  <title>LRN Exemption Programs</title>

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

    .s-c-label-1 {
      font-size: 0.6rem;
      margin-bottom: 4px;
    }

    .s-c-input-1 {
      font-size: 0.7rem;
      border-radius: 0px;
    }

    .s-c-input-2 {
      font-size: 0.7rem;
      border-radius: 0px;
      min-width: 100px;
    }


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

                        <div align="center">

                          <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">LRN Exemption Programs</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1 s-c-input-1" style="font-size: 0.6rem;" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1">
                                        <h5 class="modal-title" style="font-size: 0.7rem; margin-top: 6px;" id="">Add New Exemption</h5>
                                        <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <div class="form-group margin-top1">
                                            <span class="s-c-label-1">Program: <span class="text-danger"></span></span>
                                            <select class="form-control form-control-user input-text-value font-size-o-1 s-c-input-1" name="program" id="program" placeholder="Program">
                                              <?php
                                                $topt = "";
                                                $tpv = trim($_POST['program']);
                                                for ($i=0; $i<count($opt_programs); $i++) {
                                                  $tsel = "";
                                                  if ( strtolower(trim($opt_programs[$i][0])) == strtolower(trim($tpv)) || strtolower(trim($opt_programs[$i][1])) == strtolower(trim($tpv)) ) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option value="' . trim($opt_programs[$i][0]) . '" ' . $tsel . ' >' . trim($opt_programs[$i][1]) . '</option>';
                                                }
                                                echo $topt;
                                              ?>
                                            </select>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1 s-c-input-2" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary bg-2 font-size-o-1 s-c-input-2" name="btnadd" value="Save changes" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>


                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col"></th>
                                    <th scope="col">#</th>
                                    <th scope="col">Program</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Entry Date</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    //
                                    //
                                    //
                                    $ps_pagerows = trim($setting_default_request_rows);
                                    //$ps_pagerows = 1;
                                    if (trim($ps_pagerows) == "") {
                                      $ps_pagerows = 10;
                                    }
                                    $ps_page = trim($_GET['page']);
                                    if (trim($ps_page) == "" || $ps_page < 1) {
                                      $ps_page = 1;
                                    }
                                    //echo $ps_page;
                                    //
                                    if(trim($ps_pagerows)=="") {
                                      $ps_pagerows = 10;
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
                                    $nquery = " SELECT COUNT(*) FROM tblexempted_lrn_program 
                                              WHERE active='1' 
                                              ORDER BY entrydate ASC  ";
                                    $nresult = mysqli_query($conn, $nquery);
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
                                    if($ps_page >= 1) {
                                      //PAGING FIRST
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-lrn-exemption-programs.php?page=1"><i class="fas fa-angle-double-left"></i></a>';
                                      //PAGING PREV
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-lrn-exemption-programs.php?page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                    }
                                    //echo $maxpage;
                                    for($i=1; $i<=$maxpage; $i++) {
                                      $tstyle = "";
                                      if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                        $tstyle = " active ";
                                      }
                                      $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="manage-lrn-exemption-programs.php?page=' . $i . '">' . $i . '</a>';
                                    }
                                    if($ps_page <= $maxpage) {
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                      //PAGING NEXT
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-lrn-exemption-programs.php?page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                      //PAGING LAST
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-lrn-exemption-programs.php?page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                    }
                                    //
                                    //
                                    //
                                    //
                                    $query = "SELECT * FROM tblexempted_lrn_program 
                                              WHERE active='1' 
                                              ORDER BY entrydate ASC 
                                              LIMIT " . $toffset . "," . $ps_pagerows . " 
                                    ";
                                    $result = mysqli_query($conn, $query);
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
                                        $program = trim($row['program']);
                                        $progdesc = "";
                                        //
                                        //
                                        $activeval = trim($row['active']);
                                        $active = "No";
                                        if ( trim(strtolower($activeval)) == trim(strtolower("1")) ) {
                                          $active = "Yes";
                                        }else{
                                          $active = "No";
                                        }
                                        //
                                        //GET PROGRAM DESC
                                        $query0 = "SELECT * FROM srgb.program WHERE TRIM(LOWER(progcode))='" . trim(strtolower($program)) . "' ";
                                        $result0 = pg_query($pgconn, $query0);
                                        if ($result0) {
                                          while ($row0 = pg_fetch_array($result0)) {
                                            $progdesc = trim($row0['progdesc']);
                                          }
                                        }
                                        //
                                        //OPT MEM TYPE
                                        $topt_programs = "";
                                        $tpv_programs = trim($row['program']);
                                        for ($i=0; $i<count($opt_programs); $i++) {
                                          $tsel = "";
                                          if ( strtolower(trim($opt_programs[$i][0])) == strtolower(trim($tpv_programs)) || strtolower(trim($opt_programs[$i][1])) == strtolower(trim($tpv_programs)) ) {
                                            $tsel = " selected ";
                                          }
                                          $topt_programs = $topt_programs . '<option value="' . trim($opt_programs[$i][0]) . '" ' . $tsel . ' >' . trim($opt_programs[$i][1]) . '</option>';
                                        }
                                        //
                                        //
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" style="font-size: 0.7rem; margin-top: 6px;" id="">Update Exemption</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['id']) . '" hidden />

                                                      <div class="form-group margin-top1">
                                                        <span class="s-c-label-1">Program: <span class="text-danger"></span></span>
                                                        <select class="form-control form-control-user input-text-value font-size-o-1 s-c-input-1" name="program" id="program" placeholder="Program">
                                                          ' . $topt_programs . '
                                                        </select>
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1 s-c-input-2" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1 s-c-input-2" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" style="font-size: 0.7rem; margin-top: 6px;" id="">Delete Exemption</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['id']) . '" hidden />

                                                      Delete <b>' . trim($progdesc) . '</b> ?

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1 s-c-input-2" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-danger bg-2 font-size-o-1 s-c-input-2" name="btndelete" value="Delete" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <th scope="row" class="table-row-width-1" style="width: 60px; min-width: 60px; max-width: 60px;">
                                              <button type="button" class="btn btn-success btn-table-1 s-c-input-1" style="font-size: 0.6rem;" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button>
                                              <button type="button" class="btn btn-danger btn-table-1 s-c-input-1" style="font-size: 0.6rem;" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="">' . $n . '</th>
                                            <td>' . trim($program) . '</td>
                                            <td>' . trim($progdesc) . '</td>
                                            <td>' . trim($active) . '</td>
                                            <td>' . trim($row['entrydate']) . '</td>
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


</body>

</html>
