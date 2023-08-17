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
      $user = trim($_POST['user']);
      $pass = trim($_POST['pass']);
      $pass2 = trim($_POST['pass2']);
      $empid = trim($_POST['empid']);
      $ln = trim($_POST['ln']);
      $fn = trim($_POST['fn']);
      $mn = trim($_POST['mn']);
      $fullname = "";
      $fullname = trim($ln) . ", " . trim($fn);
      if (trim($mn) != "") {
        if ( strlen(trim($mn)) == 1 ) {
          $mn = trim($mn) . ".";
        }
        $fullname = trim($ln) . ", " . trim($fn) . " " . trim($mn);
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($empid) == "") {
        $errn++;
        $errmsg = $errmsg . "Employee ID required. ";
      }
      if(trim($user) == "") {
        $errn++;
        $errmsg = $errmsg . "Username required. ";
      }
      if($pass == "") {
        $errn++;
        $errmsg = $errmsg . "Password required. ";
      }
      if($pass != $pass2) {
        $errn++;
        $errmsg = $errmsg . "Passwords don't match. ";
      }
      if(trim($ln) == "") {
        $errn++;
        $errmsg = $errmsg . "Lastname required. ";
      }
      if(trim($fn) == "") {
        $errn++;
        $errmsg = $errmsg . "Firstname required. ";
      }
      //
      $tempiderr = 0;
      //CHECK EMPLOYEE ID
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($empid)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        //$errn++;
        //$errmsg = $errmsg . "Employee ID is invalid. ";
        $tempiderr++;
      }
      //CHECK EMPLOYEE ID
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($empid)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        //$errn++;
        //$errmsg = $errmsg . "Employee ID is invalid. ";
        $tempiderr++;
      }
      if ($tempiderr > 0) {
        $errn++;
        $errmsg = $errmsg . "Employee ID is invalid. ";
      }
      //CHECK EMPLOYEE USERNAME
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(username))='" . strtolower(trim($user)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        $errn++;
        $errmsg = $errmsg . "Username is invalid. ";
      }
      //CHECK EMPLOYEE NAME
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(lastname))='" . strtolower(trim($ln)) . "' AND  LOWER(TRIM(firstname))='" . strtolower(trim($fn)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        $errn++;
        $errmsg = $errmsg . "Employee Name is invalid. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        //SAVE EMPLOYEE
        $query = "INSERT INTO pis.employee (empid,lastname,firstname,middlename,fullname) VALUES ('" . $empid . "','" . $ln . "','" . $fn . "','" . $mn . "','" . $fullname . "') ";
        $result = pg_query($pgconn,$query);
        //SAVE EMPLOYEE LOGIN DETAILS
        $query = "INSERT INTO web.employee (empid,username,password) VALUES ('" . $empid . "','" . $user . "','" . $pass . "') ";
        $result = pg_query($pgconn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Employee added.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
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
      $ouser = trim($_POST['ouser']);
      $user = trim($_POST['user']);
      $pass = trim($_POST['pass']);
      $pass2 = trim($_POST['pass2']);
      $ln = trim($_POST['ln']);
      $fn = trim($_POST['fn']);
      $mn = trim($_POST['mn']);
      $fullname = "";
      $fullname = trim($ln) . ", " . trim($fn);
      if (trim($mn) != "") {
        if ( strlen(trim($mn)) == 1 ) {
          $mn = trim($mn) . ".";
        }
        $fullname = trim($ln) . ", " . trim($fn) . " " . trim($mn);
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($id) == "") {
        $errn++;
        $errmsg = $errmsg . "Employee ID required. ";
      }
      if(trim($user) == "") {
        $errn++;
        $errmsg = $errmsg . "Username required. ";
      }
      if($pass != "") {
        if($pass != $pass2) {
          $errn++;
          $errmsg = $errmsg . "Passwords don't match. ";
        }
      }
      if(trim($ln) == "") {
        $errn++;
        $errmsg = $errmsg . "Lastname required. ";
      }
      if(trim($fn) == "") {
        $errn++;
        $errmsg = $errmsg . "Firstname required. ";
      }
      //
      $tempiderr = 0;
      //CHECK EMPLOYEE ID
      $ten1 = 0;
      $sresult = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($id)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten1++;
          //
        }
      }
      if ($ten1 > 0) {
        //$errn++;
        //$errmsg = $errmsg . "Employee ID is invalid. ";
        //$tempiderr++;
      }
      //CHECK EMPLOYEE ID
      $ten2 = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($id)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten2++;
          //
        }
      }
      if ($ten2 > 0) {
        //$errn++;
        //$errmsg = $errmsg . "Employee ID is invalid. ";
        //$tempiderr++;
      }
      if ($ten1 <= 0 || $ten2 <= 0) {
        $errn++;
        $errmsg = $errmsg . "Employee ID is invalid. ";
      }
      //CHECK EMPLOYEE USERNAME
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(username))!='" . strtolower(trim($ouser)) . "' AND LOWER(TRIM(username))='" . strtolower(trim($user)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      //echo $ten;
      if ($ten > 0) {
        $errn++;
        $errmsg = $errmsg . "Username is invalid. ";
      }
      //
      //
      if(trim($ln) == "") {
        $errn++;
        $errmsg = $errmsg . "Lastname required. ";
      }
      if(trim($fn) == "") {
        $errn++;
        $errmsg = $errmsg . "Firstname required. ";
      }
      //
      //echo "XXX";
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        //UPDATE EMPLOYEE
        $query = "UPDATE pis.employee set lastname='" . $ln . "',firstname='" . $fn . "',middlename='" . $mn . "',fullname='" . $fullname . "'  WHERE empid='" . $id . "' ";
        $result = pg_query($pgconn,$query);
        //
        //CHECK IF IN web.employee
        $ten = 0;
        $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($id)) . "' LIMIT 1 ");
        if ($sresult) {
          while ($srow = pg_fetch_array($sresult)) {
            //$inmdb = trim($srow[0]);
            $ten++;
            //
          }
        }
        if ($ten > 0) {
          //
          //UPDATE WEB EMPLOYEE
          $taq = "";
          if ($pass != "") {
            $taq = " ,password='" . $pass . "' ";
          }
          $query = "UPDATE web.employee set username='" . $user . "' " . $taq . "  WHERE empid='" . $id . "' ";
          $result = pg_query($pgconn,$query);
          //
        }else{
          //
          //INSERT IN WEB EMPLOYEE
          $query = "INSERT INTO web.employee (empid,username,password) VALUES ('" . $id . "','" . $user . "','" . $pass . "') ";
          $result = pg_query($pgconn,$query);
          //
        }
        //echo $ten;
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> User updated.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
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
        //$query = "DELETE FROM tbl  WHERE empid='" . $id . "' ";
        //$result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> User deactivated.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
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

  <title>Users</title>

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

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Users</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1">
                                        <h5 class="modal-title" id="">Add New User</h5>
                                        <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <div class="form-group margin-top1">
                                            <span class="label1">Username: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="user" id="user" placeholder="Username" <?php echo ' value="' . $_POST['user'] . '" '; ?> required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Password: <span class="text-danger"></span></span>
                                            <input type="password" class="form-control form-control-user input-text-value font-size-o-1" name="pass" id="pass" placeholder="Password" <?php echo ' value="' . $_POST['pass'] . '" '; ?> >
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Repeat Password: <span class="text-danger"></span></span>
                                            <input type="password" class="form-control form-control-user input-text-value font-size-o-1" name="pass2" id="pass2" placeholder="Repeat Password" <?php echo ' value="' . $_POST['pass2'] . '" '; ?> >
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Employee ID: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="empid" id="empid" placeholder="Employee ID" <?php 
                                                $tval = trim($_POST['empid']);
                                                if ( trim($tval) == "" ) {
                                                  $tval = trim($emp_newid);
                                                }
                                                echo ' value="' . $tval . '" '; 
                                            ?> required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Lastname: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="ln" id="ln" placeholder="Lastname" <?php echo ' value="' . $_POST['ln'] . '" '; ?> required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Firstname: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="fn" id="fn" placeholder="Firstname" <?php echo ' value="' . $_POST['fn'] . '" '; ?> required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Middlename: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="mn" id="mn" placeholder="Middlename" <?php echo ' value="' . $_POST['mn'] . '" '; ?> >
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary bg-2 font-size-o-1" name="btnadd" value="Save changes" />
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
                                    <th scope="col">Employee ID</th>
                                    <th scope="col">Username</th>
                                    <th scope="col">Lastname</th>
                                    <th scope="col">Firstname</th>
                                    <th scope="col">Middlename</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    //
                                    //
                                    //
                                    $ps_pagerows = trim($setting_default_request_rows);
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
                                    $nquery = "SELECT COUNT(a.empid) FROM pis.employee AS a 
                                              LEFT JOIN web.employee AS b ON b.empid=a.empid ";
                                    $nresult = pg_query($pgconn, $nquery);
                                    if ($nresult) {
                                      $nrow = pg_fetch_array($nresult);
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
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users.php?page=1"><i class="fas fa-angle-double-left"></i></a>';
                                      //PAGING PREV
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users.php?page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                    }
                                    //echo $maxpage;
                                    for($i=1; $i<=$maxpage; $i++) {
                                      $tstyle = "";
                                      if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                        $tstyle = " active ";
                                      }
                                      $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="manage-users.php?page=' . $i . '">' . $i . '</a>';
                                    }
                                    if($ps_page <= $maxpage) {
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                      //PAGING NEXT
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users.php?page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                      //PAGING LAST
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users.php?page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                    }
                                    //
                                    //
                                    //
                                    //
                                    $query = "SELECT a.empid,b.username,b.password,a.lastname,a.firstname,a.middlename,a.fullname FROM pis.employee AS a 
                                              LEFT JOIN web.employee AS b ON b.empid=a.empid 
                                              ORDER BY a.lastname ASC, a.firstname ASC, a.middlename ASC, b.username ASC 
                                              OFFSET " . $toffset . " LIMIT " . $ps_pagerows . " 
                                    ";
                                    $result = pg_query($pgconn, $query);
                                    if ($result) {
                                      $n = 0;
                                      //
                                      $n = ($ps_page-1) * $ps_pagerows;
                                      if (trim($n) == "") {
                                        $n = 0;
                                      }
                                      //
                                      while ($row = pg_fetch_array($result)) {
                                        $n++;
                                        //
                                        //
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" id="">Update User</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['empid']) . '" hidden />
                                                      <input type="hidden" name="ouser" value="' . trim($row['username']) . '" hidden />

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Username: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="user" id="user" placeholder="Username" value="' . $row['username'] . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Password: <span class="text-danger"></span></span>
                                                        <input type="password" class="form-control form-control-user input-text-value font-size-o-1" name="pass" id="pass" placeholder="Password" value="" >
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Repeat Password: <span class="text-danger"></span></span>
                                                        <input type="password" class="form-control form-control-user input-text-value font-size-o-1" name="pass2" id="pass2" placeholder="Repeat Password" value="" >
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Lastname: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="ln" id="ln" placeholder="Lastname" value="' . $row['lastname'] . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Firstname: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="fn" id="fn" placeholder="Firstname" value="' . $row['firstname'] . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Middlename: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user input-text-value font-size-o-1" name="mn" id="mn" placeholder="Middlename" value="' . $row['middlename'] . '" >
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <th scope="row" class="table-row-width-1" style="width: 60px; min-width: 60px; max-width: 60px;">
                                              <button type="button" class="btn btn-success btn-table-1" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="">' . $n . '</th>
                                            <td>' . trim($row['empid']) . '</td>
                                            <td>' . trim($row['username']) . '</td>
                                            <td>' . trim($row['lastname']) . '</td>
                                            <td>' . trim($row['firstname']) . '</td>
                                            <td>' . trim($row['middlename']) . '</td>
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
