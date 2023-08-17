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

  <title>Search Student</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Search Student</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <div class="form-group margin-top1" style="vertical-align: top;">
                                    <span class="c-lbl-3">Search: <span class="text-danger"></span></span><br/>
                                    <form method="get">
                                      <input type="text" class="c-input-4 input-text-value font-size-o-1" style="display: inline-block;" name="s" id="s" placeholder="Search..." <?php echo ' value="' . $_GET['s'] . '" '; ?> ><input type="submit" class="btn btn-primary bg-2 btn-c-3" style="min-height: 40px;" value="Search">
                                    </form>
                                  </div>

                                </div>
                                

                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col">#</th>
                                    <th scope="col">I.D.</th>
                                    <th scope="col">Name</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    //
                                    //
                                    $search = trim($_GET['s']);
                                    //
                                    //
                                    //
                                    if(strlen($search) > 1) {
                                      $query = " SELECT a.studid,b.studfullname FROM srgb.semstudent AS a 
                                                 LEFT JOIN srgb.student AS b ON b.studid=a.studid 
                                                 WHERE ( sy='" . $log_user_active_sy . "' AND sem='" . $log_user_active_sem . "' ) AND ( TRIM(LOWER(b.studid)) LIKE TRIM(LOWER('%" . $search . "%')) OR TRIM(LOWER(b.studfullname)) LIKE TRIM(LOWER('%" . $search . "%')) ) 
                                                 GROUP BY a.studid,b.studfullname 
                                                 ORDER BY b.studfullname 
                                      ";
                                      $result = pg_query($pgconn, $query);
                                      if ($result) {
                                        $n = 0;
                                        //
                                        //
                                        while ($row = pg_fetch_array($result)) {
                                          $n++;
                                          //
                                          //
                                          $id = trim($row['studid']);
                                          $fullname = trim($row['studfullname']);
                                          //
                                          $link = 'profile.php?t=student&id=' . $id . '';
                                          //
                                          echo '
                                            <tr style="font-size: 0.7rem;">
                                              <td class="">' . $n . '</th>
                                              <td><a href="' . $link . '">' . $id . '</a></td>
                                              <td><a href="' . $link . '">' . trim($fullname) . '</a></td>
                                            </tr>
                                          ';
                                        }
                                      }
                                    }
                                    
                                  ?>
                                </tbody>
                              </table>
                              </div>

                                
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
