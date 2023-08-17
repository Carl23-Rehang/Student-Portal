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
      $usertype = trim($_POST['usertype']);
      $userid = trim($_POST['user']);
      $reason = trim($_POST['reason']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($usertype) == "") {
        $errn++;
        $errmsg = $errmsg . "User Type required. ";
      }
      if(trim($userid) == "") {
        $errn++;
        $errmsg = $errmsg . "User required. ";
      }
      //
      //
      if($errn <= 0) {
        //
        //SAVE ROLE
        $query = "INSERT INTO web.users_blocked (userid,usertype,reason,addedby) VALUES ('" . $userid . "','" . $usertype . "','" . $reason . "','" . $log_userid . "') ";
        $result = pg_query($pgconn,$query);
        //
        //$res1 = pg_get_result($pgconn);
        //echo (pg_result_error($res1));
        //$error = pg_last_error($pgconn);
        //echo $error;
        //echo json_encode($erro);
        //
        if($result) {
            $dr = '
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span class="font-c-2-1"><strong></strong> User blocked.</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            ';
        }else{
            
        }
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong></strong> ' . $errmsg . '</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    //
    if($_POST['btndelete']) {
      //
      $id = trim($_POST['id']);
      $usertype = trim($_POST['usertype']);
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "UPDATE web.users_blocked SET active='0'  WHERE userid='" . $id . "' AND TRIM(LOWER(usertype))=TRIM(LOWER('" . $usertype . "')) ";
        $result = pg_query($pgconn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong></strong> User removed.</span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="font-c-2-1"><strong></strong> ' . $errmsg . '</span>
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

  <title>Blocked Users</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Blocked Users</span></h6>
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
                                        <h5 class="modal-title" style="font-size: 0.7rem; margin-top: 6px;" id="">Add New User</h5>
                                        <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <div class="form-group margin-top1">
                                            <span class="s-c-label-1">User Type: <span class="text-danger"></span></span>
                                            <select class="form-control form-control-user input-text-value font-size-o-1 s-c-input-1" name="usertype" id="usertype" placeholder="User Type" required>
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

                                          <span class="s-c-label-1" style="">User: <span class="text-danger"></span></span>
                                          <input type="hidden" id="st_user_id" name="user" value="" hidden />
                                          <div class="dropdown" style="display: block; width: 100%;">
                                            <button type="button" onclick="ItemListLoad_User('st_user_h_items_inner','usertype'); ItemListShow('st_user_h_items');" style="display: block; max-width: 100%; width: 100%; padding: 12px 12px; text-align: left;" class="c-input-1" id="st_user_name">Select User...</button>
                                            <div id="st_user_h_items" class="dropdown-content" style="width: 100%; color: black;">
                                              <input type="text" class="c-input-1" style="width: 100%;" placeholder="Search.." id="st_user_fs" onkeyup="ItemListFilter('st_user_h_items','st_user_fs');">
                                              <div id="st_user_h_items_inner" style="max-height: 128px;">

                                              </div>
                                            </div>
                                          </div>



                                          <div class="form-group margin-top1">
                                            <span class="s-c-label-1">Reason: <span class="text-danger"></span></span>
                                            <textarea class="form-control form-control-user input-text-value font-size-o-1 s-c-input-1" name="reason" id="reason" placeholder="Reason"><?php echo $_POST['reason']; ?></textarea>
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
                                    <th scope="col">User ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">User Type</th>
                                    <th scope="col">Reason</th>
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
                                    //$toffset = ($ps_page - 1) * $ps_pagerows; // FOR MY SQL OFFSET
                                    $toffset = $ps_page - 1; //FOR PG QUERY
                                    //
                                    $numrows = 0;
                                    $maxpage = 1;
                                    //
                                    $tn = 0;
                                    //
                                    $nquery = " SELECT COUNT(id) FROM web.users_blocked 
                                              WHERE active='1'  
                                               ";
                                    $nresult = pg_query($pgconn, $nquery);
                                    if ($nresult) {
                                      $nrow = pg_fetch_array($nresult);
                                      $numrows = trim($nrow[0]);
                                      //echo " FFF ";
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
                                      //$toffset = ($ps_page - 1) * $ps_pagerows;
                                      $toffset = $ps_page - 1;
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
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-blocked-users.php?page=1"><i class="fas fa-angle-double-left"></i></a>';
                                      //PAGING PREV
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-blocked-users.php?page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                    }
                                    //echo $maxpage;
                                    for($i=1; $i<=$maxpage; $i++) {
                                      $tstyle = "";
                                      if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                        $tstyle = " active ";
                                      }
                                      $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="manage-blocked-users.php?page=' . $i . '">' . $i . '</a>';
                                    }
                                    if($ps_page <= $maxpage) {
                                      //SPACE
                                      $tpaging = $tpaging . '';
                                      //PAGING NEXT
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-blocked-users.php?page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                      //PAGING LAST
                                      $tpaging = $tpaging . '<a class="paging-btn-1" href="manage-blocked-users.php?page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                    }
                                    //
                                    //
                                    //
                                    //$toffset = 0;
                                    //
                                    $query = "SELECT * FROM web.users_blocked 
                                              WHERE active='1' 
                                              ORDER BY entrydate ASC 
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
                                        $groupname = "";
                                        $groups = "";
                                        //
                                        $userid = trim($row['userid']);
                                        $fullname = "";
                                        $usertype = trim($row['usertype']);
                                        $reason = trim($row['reason']);
                                        $entrydate = trim($row['entrydate']);
                                        //
                                        $activeval = trim($row['active']);
                                        $active = "No";
                                        if ( trim(strtolower($activeval)) == trim(strtolower("1")) ) {
                                          $active = "Yes";
                                        }else{
                                          $active = "No";
                                        }
                                        //
                                        if(strtolower(trim($usertype)) == strtolower(trim("employee"))) {
                                          $query0 = "SELECT * FROM pis.employee WHERE TRIM(LOWER(empid))='" . trim(strtolower($userid)) . "' ";
                                          $result0 = pg_query($pgconn, $query0);
                                          if ($result0) {
                                            while ($row0 = pg_fetch_array($result0)) {
                                              $fullname = trim($row0['fullname']);
                                            }
                                          }
                                        }
                                        if(strtolower(trim($usertype)) == strtolower(trim("student"))) {
                                          $query0 = "SELECT * FROM srgb.student WHERE TRIM(LOWER(studid))='" . trim(strtolower($userid)) . "' ";
                                          $result0 = pg_query($pgconn, $query0);
                                          if ($result0) {
                                            while ($row0 = pg_fetch_array($result0)) {
                                              $fullname = trim($row0['studfullname']);
                                            }
                                          }
                                        }
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
                                        //
                                        //
                                        $fm = '
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" style="font-size: 0.7rem; margin-top: 6px;" id="">Remove User</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['userid']) . '" hidden />
                                                      <input type="hidden" name="usertype" value="' . trim($row['usertype']) . '" hidden />

                                                      Remove blocked user <b>' . trim($trole) . '</b> for <b>' . trim($fullname) . '</b> ?

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
                                              <button type="button" class="btn btn-danger btn-table-1 s-c-input-1" style="font-size: 0.6rem;" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="">' . $n . '</th>
                                            <td>' . trim($userid) . '</td>
                                            <td>' . trim($fullname) . '</td>
                                            <td>' . trim($usertype) . '</td>
                                            <td>' . trim($reason) . '</td>
                                            <td>' . trim($active) . '</td>
                                            <td>' . trim($entrydate) . '</td>
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


  <script>
    /* When the user clicks on the button,
    toggle between hiding and showing the dropdown content */
    function ItemListShow(target) {
      document.getElementById(target).classList.toggle("show-1");
    }

    function ItemListFilter(target,fsearch) {
      var input, filter, ul, li, a, i;
      input = document.getElementById(fsearch);
      filter = input.value.toUpperCase();
      div = document.getElementById(target);
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

    function ItemListSelect(itemholder,valID,valName,targetID,targetName) {
      try{
        document.getElementById(targetID).value = valID;
      }catch(err){}
      try{
        document.getElementById(targetName).value = valName;
      }catch(err){}
      try{
        document.getElementById(targetName).innerText = valName;
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListSelect_Subject(itemholder,value,targetSubject,targetSection,targetName,valCurrFaculty,targetCurrFaculty) {
      try{
        if(value.trim() != "") {
          var td = value.split("•");
          if(td.length > 0) {
            try{
              document.getElementById(targetSubject).value = td[0].trim();
            }catch(err){}
            try{
              document.getElementById(targetSection).value = td[1].trim();
            }catch(err){}
            try{
              var tname = "" + td[0].trim() + " : " + td[1].trim();
              try{
                document.getElementById(targetName).value = tname.trim();
              }catch(err){}
              try{
                document.getElementById(targetName).innerText = tname.trim();
              }catch(err){}
            }catch(err){}
          }
        }
      }catch(err){}
      try{
        try{
          document.getElementById(targetCurrFaculty).innerText = "";
        }catch(err){}
        if(valCurrFaculty.trim() != "") {
          document.getElementById(targetCurrFaculty).innerText = "Current Faculty: " + valCurrFaculty.trim();
        }
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListSelect_Faculty(itemholder,valID,valName,targetID,targetName) {
      try{
        document.getElementById(targetID).value = valID;
      }catch(err){}
      try{
        document.getElementById(targetName).value = valName;
      }catch(err){}
      try{
        document.getElementById(targetName).innerText = valName;
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListSelect_User(itemholder,valID,valName,targetID,targetName) {
      try{
        document.getElementById(targetID).value = valID;
      }catch(err){}
      try{
        document.getElementById(targetName).value = valName;
      }catch(err){}
      try{
        document.getElementById(targetName).innerText = valName;
      }catch(err){}
      try{
        document.getElementById(itemholder).classList.toggle("show-1");
      }catch(err){}
    }

    function ItemListLoad(targetSubject,targetFaculty) {
      try{
        var tshow = true;
        if(tshow == true) {
          //
          var tsy = "<?php echo $log_user_active_sy; ?>";
          var tsem = "<?php echo $log_user_active_sem; ?>";
          //
          var tsubj = document.getElementById(targetSubject);
          var tfac = document.getElementById(targetFaculty);
          //
          try{
            var cs = 'get-subjects-subjtag.php?sy=' + tsy + '&sem=' + tsem + '';
            $.get(cs, function(data) {
              //
              tsubj.innerHTML  = data;
              //
              //LOAD FACULTY
              try{
                var cs2 = 'get-faculty-subjtag.php' + '';
                $.get(cs2, function(data2) {
                  //
                  tfac.innerHTML  = data2;
                  //
                });

              }catch(err){}
              //
            });

          }catch(err){}
          //
        }
      }catch(err){}
    }

    function ItemListLoad_User(target, srcUserType) {
      try{
        var tshow = true;
        if(tshow == true) {
          //
          //
          var tar = document.getElementById(target);
          var utype = document.getElementById(srcUserType).value;
          //
          try{
            var cs = 'get-user-bu.php?ut=' + utype;
            $.get(cs, function(data) {
              //
              tar.innerHTML  = data;
              //
            });

          }catch(err){}
          //
        }
      }catch(err){}
    }


  </script>


</body>

</html>
