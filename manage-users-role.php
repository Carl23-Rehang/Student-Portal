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
      $userid = trim($_POST['userid']);
      $usertype = trim($_POST['usertype']);
      $role = trim($_POST['role']);
      $description = trim($_POST['description']);
      $alevel = trim($_POST['alevel']);
      $active = trim($_POST['active']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($userid) == "") {
        $errn++;
        $errmsg = $errmsg . "User ID required. ";
      }
      if(trim($usertype) == "") {
        $errn++;
        $errmsg = $errmsg . "User Type required. ";
      }
      if(trim($role) == "") {
        $errn++;
        $errmsg = $errmsg . "Role required. ";
      }
      if(trim($alevel) == "") {
        $errn++;
        $errmsg = $errmsg . "Acces Level required. ";
      }
      if(trim($active) == "") {
        $errn++;
        $errmsg = $errmsg . "Active required. ";
      }
      //
      //CHECK ID
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($userid)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten <= 0) {
        $errn++;
        $errmsg = $errmsg . "User ID is invalid. ";
      }
      //CHECK ROLE
      $ten = 0;
      $sresult = mysqli_query($conn, "SELECT * from tblroletype where LOWER(TRIM(roletypeid))='" . strtolower(trim($role)) . "' OR  LOWER(TRIM(rolecode))='" . strtolower(trim($role)) . "' OR  LOWER(TRIM(rolename))='" . strtolower(trim($role)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = mysqli_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      //echo "XX " . $ten . " " . $role;
      if ($ten <= 0) {
        $errn++;
        $errmsg = $errmsg . "Role is invalid. ";
      }
      //CHECK ROLE EXIST
      $ten = 0;
      $sresult = mysqli_query($conn, "SELECT * from tbluserroles where LOWER(TRIM(userid))='" . strtolower(trim($userid)) . "' AND  LOWER(TRIM(userrole))='" . strtolower(trim($role)) . "' AND  LOWER(TRIM(active))='" . strtolower(trim("1")) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = mysqli_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten > 0) {
        $errn++;
        $errmsg = $errmsg . "User role already exist. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //
        //SAVE ROLE
        $query = "INSERT INTO tbluserroles (userid,usertype,userrole,details,alevel,active) VALUES ('" . $userid . "','" . $usertype . "','" . $role . "','" . $description . "','" . $alevel . "','" . $active . "') ";
        $result = mysqli_query($conn,$query);
        //
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> User role added.
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
      $userid = trim($_POST['userid']);
      $usertype = trim($_POST['usertype']);
      $role = trim($_POST['role']);
      $description = trim($_POST['description']);
      $alevel = trim($_POST['alevel']);
      $active = trim($_POST['active']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($id) == "") {
        $errn++;
        $errmsg = $errmsg . "ID required. ";
      }
      if(trim($userid) == "") {
        $errn++;
        $errmsg = $errmsg . "User ID required. ";
      }
      if(trim($usertype) == "") {
        $errn++;
        $errmsg = $errmsg . "User Type required. ";
      }
      if(trim($role) == "") {
        $errn++;
        $errmsg = $errmsg . "Role required. ";
      }
      if(trim($alevel) == "") {
        $errn++;
        $errmsg = $errmsg . "Acces Level required. ";
      }
      if(trim($active) == "") {
        $errn++;
        $errmsg = $errmsg . "Active required. ";
      }
      //
      //CHECK ID
      $ten = 0;
      $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($userid)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten <= 0) {
        $errn++;
        $errmsg = $errmsg . "User ID is invalid. ";
      }
      //CHECK ROLE
      $ten = 0;
      $sresult = mysqli_query($conn, "SELECT * from tblroletype where LOWER(TRIM(roletypeid))='" . strtolower(trim($role)) . "' OR  LOWER(TRIM(rolecode))='" . strtolower(trim($role)) . "' OR  LOWER(TRIM(rolename))='" . strtolower(trim($role)) . "' LIMIT 1 ");
      if ($sresult) {
        while ($srow = mysqli_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $ten++;
          //
        }
      }
      if ($ten <= 0) {
        $errn++;
        $errmsg = $errmsg . "Role is invalid. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tbluserroles set usertype='" . $usertype . "',userrole='" . $role . "',details='" . $description . "',alevel='" . $alevel . "',active='" . $active . "'  WHERE userroleid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> User role updated.
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
        $query = "UPDATE tbluserroles SET active='0'  WHERE userroleid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> User role deactivated.
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

  <title>User Roles</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">User Roles</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1 btn-c-4-1" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-2">
                                        <h5 class="modal-title modal-title-1" id="">Add New User Role</h5>
                                        <button type="button" class="close color-white-1 no-outline modal-close-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <br/>

                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">User ID: <span class="text-danger"></span></span>
                                            <input type="text" class="v3-input-txt-1 input-text-value font-size-o-1" name="userid" id="userid" placeholder="User ID" <?php echo ' value="' . $_POST['userid'] . '" '; ?> required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">User Type: <span class="text-danger"></span></span>
                                            <select class="v3-input-txt-1 input-text-value font-size-o-1" name="usertype" id="usertype" placeholder="User Type">
                                              <?php
                                                $topt = "";
                                                $tpv = trim($_POST['usertype']);
                                                for ($i=0; $i<count($opt_memtype); $i++) {
                                                  $tsel = "";
                                                  if ( strtolower(trim($opt_memtype[$i][0])) == strtolower(trim($tpv)) || strtolower(trim($opt_memtype[$i][1])) == strtolower(trim($tpv)) ) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option value="' . trim($opt_memtype[$i][1]) . '" ' . $tsel . ' >' . trim($opt_memtype[$i][1]) . '</option>';
                                                }
                                                echo $topt;
                                              ?>
                                            </select>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">Role: <span class="text-danger"></span></span>
                                            <select class="v3-input-txt-1 input-text-value font-size-o-1" name="role" id="role" placeholder="Role">
                                              <?php
                                                $topt = "";
                                                $tpv = trim($_POST['role']);
                                                for ($i=0; $i<count($opt_roles); $i++) {
                                                  $tsel = "";
                                                  if ( strtolower(trim($opt_roles[$i][0])) == strtolower(trim($tpv)) || strtolower(trim($opt_roles[$i][1])) == strtolower(trim($tpv)) || strtolower(trim($opt_roles[$i][2])) == strtolower(trim($tpv)) ) {
                                                    $tsel = " selected ";
                                                  }
                                                  $topt = $topt . '<option value="' . trim($opt_roles[$i][0]) . '" ' . $tsel . ' >' . "[" . trim($opt_roles[$i][1]) . "] " . trim($opt_roles[$i][2]) . '</option>';
                                                }
                                                echo $topt;
                                              ?>
                                            </select>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">Description: <span class="text-danger"></span></span>
                                            <textarea class="v3-input-txta-1 text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description"><?php echo $_POST['description']; ?></textarea>
                                          </div>


                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">Access Level: <span class="text-danger"></span></span>
                                            <input type="number" class="v3-input-txt-1 input-text-value font-size-o-1" name="alevel" id="alevel" min="1"  placeholder="Access Level" 
                                              <?php 
                                                $tv = "";
                                                $tv = trim($_POST['alevel']);
                                                if (trim($tv) == "") {
                                                  $tv = "1";
                                                }
                                                echo ' value="' . $tv . '" ';
                                              ?> 
                                            required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="v3-input-lbl-1">Active: <span class="text-danger"></span></span>
                                            <input type="number" class="v3-input-txt-1 input-text-value font-size-o-1" name="active" id="active" min="0" max="1" placeholder="Is Admin" 
                                              <?php 
                                                $tv = "";
                                                $tv = trim($_POST['active']);
                                                if (trim($tv) == "") {
                                                  $tv = "1";
                                                }
                                                echo ' value="' . $tv . '" ';
                                              ?> 
                                            required>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary modal-btn-1" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary bg-2 modal-btn-1" name="btnadd" value="Save changes" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>


                                <div class="table-responsive">
                                <table class="table table-hover">
                                <thead class="thead-1 font-size-o-1 tbl-header-1-1-thead-1" style="">
                                  <tr class="tbl-header-1-1-tr-1" style="font-size: 0.6rem;">
                                    <th scope="col" class="tbl-header-1-1" style="width: 140px; min-width: 140px;"></th>
                                    <th scope="col" class="tbl-header-1-1" style="width: 50px; min-width: 50px;">#</th>
                                    <th scope="col" class="tbl-header-1-1">User ID</th>
                                    <th scope="col" class="tbl-header-1-1">Name</th>
                                    <th scope="col" class="tbl-header-1-1">User Type</th>
                                    <th scope="col" class="tbl-header-1-1">Role</th>
                                    <th scope="col" class="tbl-header-1-1">Access Level</th>
                                    <th scope="col" class="tbl-header-1-1">Active</th>
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
                                    $nquery = " SELECT COUNT(*) FROM tbluserroles 
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
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users-role.php?page=1"><i class="fas fa-angle-double-left"></i></a>';
                                      //PAGING PREV
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users-role.php?page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                    }
                                    //echo $maxpage;
                                    for($i=1; $i<=$maxpage; $i++) {
                                      $tstyle = "";
                                      if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                        $tstyle = " active ";
                                      }
                                      $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="manage-users-role.php?page=' . $i . '">' . $i . '</a>';
                                    }
                                    if($ps_page <= $maxpage) {
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                      //PAGING NEXT
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users-role.php?page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                      //PAGING LAST
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-users-role.php?page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                    }
                                    //
                                    //
                                    //
                                    //
                                    $query = "SELECT * FROM tbluserroles 
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
                                        $groupname = "";
                                        $groups = "";
                                        //
                                        $empid = trim($row['userid']);
                                        $fullname = "";
                                        //
                                        $roleid = trim($row['userrole']);
                                        $rolecode = "";
                                        $rolename = "";
                                        $trole = "";
                                        //
                                        $activeval = trim($row['active']);
                                        $active = "No";
                                        if ( trim(strtolower($activeval)) == trim(strtolower("1")) ) {
                                          $active = "Yes";
                                        }else{
                                          $active = "No";
                                        }
                                        //
                                        $query0 = "SELECT * FROM pis.employee WHERE TRIM(LOWER(empid))='" . trim(strtolower($empid)) . "' ";
                                        $result0 = pg_query($pgconn, $query0);
                                        if ($result0) {
                                          while ($row0 = pg_fetch_array($result0)) {
                                            $fullname = trim($row0['fullname']);
                                          }
                                        }
                                        //
                                        $query0 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(roletypeid))='" . trim(strtolower($roleid)) . "' ";
                                        $result0 = mysqli_query($conn, $query0);
                                        if ($result0) {
                                          while ($row0 = mysqli_fetch_array($result0)) {
                                            $rolecode = trim($row0['rolecode']);
                                            $rolename = trim($row0['rolename']);
                                          }
                                        }
                                        $trole = "[" . $rolecode . "] " . $rolename;
                                        //
                                        //
                                        //OPT MEM TYPE
                                        $topt_utype = "";
                                        $tpv_utype = trim($row['usertype']);
                                        for ($i=0; $i<count($opt_memtype); $i++) {
                                          $tsel = "";
                                          if ( strtolower(trim($opt_memtype[$i][0])) == strtolower(trim($tpv_utype)) || strtolower(trim($opt_memtype[$i][1])) == strtolower(trim($tpv_utype)) ) {
                                            $tsel = " selected ";
                                          }
                                          $topt_utype = $topt_utype . '<option value="' . trim($opt_memtype[$i][1]) . '" ' . $tsel . ' >' . trim($opt_memtype[$i][1]) . '</option>';
                                        }
                                        //OPT ROLES
                                        $topt_role = "";
                                        $tpv_role = trim($row['userrole']);
                                        for ($i=0; $i<count($opt_roles); $i++) {
                                          $tsel = "";
                                          if ( strtolower(trim($opt_roles[$i][0])) == strtolower(trim($tpv_role)) || strtolower(trim($opt_roles[$i][1])) == strtolower(trim($tpv_role)) || strtolower(trim($opt_roles[$i][2])) == strtolower(trim($tpv_role)) ) {
                                            $tsel = " selected ";
                                          }
                                          $topt_role = $topt_role . '<option value="' . trim($opt_roles[$i][0]) . '" ' . $tsel . ' >' . "[" . trim($opt_roles[$i][1]) . "] " . trim($opt_roles[$i][2]) . '</option>';
                                        }
                                        //
                                        //
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-2">
                                                    <h5 class="modal-title modal-title-1" id="">Update User Role</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['userroleid']) . '" hidden />

                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">User ID: <span class="text-danger"></span></span>
                                                        <input type="text" class="v3-input-txt-1 input-text-value font-size-o-1" name="userid" id="userid" placeholder="User ID" value="' . $row['userid'] . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">User Type: <span class="text-danger"></span></span>
                                                        <select class="v3-input-txt-1 input-text-value font-size-o-1" name="usertype" id="usertype" placeholder="User Type">
                                                          ' . $topt_utype . '
                                                        </select>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">Role: <span class="text-danger"></span></span>
                                                        <select class="v3-input-txt-1 input-text-value font-size-o-1" name="role" id="role" placeholder="Role">
                                                          ' . $topt_role . '
                                                        </select>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">Description: <span class="text-danger"></span></span>
                                                        <textarea class="v3-input-txta-1 text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description">' . $row['details'] . '</textarea>
                                                      </div>


                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">Access Level: <span class="text-danger"></span></span>
                                                        <input type="number" class="v3-input-txt-1 input-text-value font-size-o-1" name="alevel" id="alevel" min="1"  placeholder="Access Level" value="' . $row['alevel'] . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="v3-input-lbl-1">Active: <span class="text-danger"></span></span>
                                                        <input type="number" class="v3-input-txt-1 input-text-value font-size-o-1" name="active" id="active" min="0" max="1" placeholder="Is Admin" value="' . $row['active'] . '" required>
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary modal-btn-1" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 modal-btn-1" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-2">
                                                    <h5 class="modal-title modal-title-1" id="">Delete Request Type</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['userroleid']) . '" hidden />

                                                      Delete role <b>' . trim($trole) . '</b> for <b>' . trim($fullname) . '</b> ?

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary modal-btn-1" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-danger bg-2 modal-btn-1" name="btndelete" value="Delete" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <td scope="row" class="" style="">
                                              <button type="button" class="btn btn-success btn-table-2-1 no-outline" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button><button type="button" class="btn btn-danger btn-table-2-1 no-outline" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </td>
                                            <td class="">' . $n . '</td>
                                            <td>' . trim($row['userid']) . '</td>
                                            <td>' . trim($fullname) . '</td>
                                            <td>' . trim($row['usertype']) . '</td>
                                            <td>' . "[" . trim($rolecode) . "] " . trim($rolename) . '</td>
                                            <td>' . trim($row['alevel']) . '</td>
                                            <td>' . trim($active) . '</td>
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
