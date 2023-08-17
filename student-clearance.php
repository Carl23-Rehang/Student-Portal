<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  include "access_check.php";
  //
  //
  //
  $g_clearanceid = trim($_GET['cid']);
  $cstudid = trim($_GET['studid']);
  //
  if( trim($g_clearanceid) == "" || trim($cstudid) == "" ) {
    echo '<meta http-equiv="refresh" content="0;URL=clearance.php" />';
    exit();
  }
  //
  //
  if(trim($log_userid)!="") {
    if($_POST['btnapprove']) {
      //
      $taskid = trim($_POST['taskid']);
      $approved = trim($_POST['approved']);
      $notes = htmlentities(trim($_POST['notes']));
      //
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        //INSERT CLEARANCE
        $query = "UPDATE tbl_clearance_tasks SET approv='" . $approved . "',approvedby='" . trim($log_userid) . "',approveddate=CURRENT_TIMESTAMP,notes='" . $notes . "'  WHERE taskid='" . $taskid . "' AND clearanceid='" . $g_clearanceid . "'  ";
        $result = mysqli_query($conn,$query);
        if ($result) {
          $dr = '
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong>Success!</strong> Clearance updated.
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btnupdate']) {
      $id = trim($_POST['id']);
      $name = trim($_POST['name']);
      $description = trim($_POST['description']);
      $visible = trim($_POST['visible']);
      //
      if(trim($visible) == "") {
        $visible = "0";
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($name) == "") {
        $errn++;
        $errmsg = $errmsg . "Task required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tbl_clearance_profile set profilename='" . $name . "',details='" . $description . "',isvisible='" . $visible . "' WHERE profileid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong>Success!</strong> Profile updated.
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
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
        $query = "DELETE FROM tbl_clearance_profile  WHERE profileid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong>Success!</strong> Profile deleted.
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    //
    //LOAD ITEMS FOR CLEARANCE PROFILE
    $n = 0;
    $lquery2 = "SELECT * FROM tbl_clearance_profile WHERE isvisible='1' ORDER BY profilename ASC";
    $lresult2 = mysqli_query($conn, $lquery2);
    if ($lresult2) {
      while ($lrow2 = mysqli_fetch_array($lresult2)) {
        $tid = trim($lrow2['profileid']);
        $tname = trim($lrow2['profilename']);
        if($tid != "" && $tname != "") {
          $gd_items_cprof = $gd_items_cprof . '
                  <input type="hidden" id="cprof_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="cprof_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'cprof','cprofid','cprofname'" . ')">' . $tname . '</a>
                ';
          //
          $gd_items_cprof_u = $gd_items_cprof_u . '
                  <input type="hidden" id="cprofu_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="cprofu_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'cprofu','cprofidu','cprofnameu'" . ')">' . $tname . '</a>
                ';
          $n++;
        }
      }
    }
    //END LOAD ITEMS FOR CLEARANCE PROFILE
    //
    //
  } //END CHECK USER
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

  <title>Clearance</title>

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

                        <div align="left">

                                <?php
                                  $bt = trim($_GET['bt']);
                                  $tlink = './clearance-tasks.php?cid=' . $g_clearanceid . '';
                                  if(trim(strtolower($bt)) == trim(strtolower("c"))) {
                                    $tlink = './clearance.php';
                                  }
                                  echo '
                                      <a class="btn btn-success btn-1 btn-width-min-1" style="font-size: 0.6rem; border-radius: 0px; border-top-right-radius: 16px; border-bottom-right-radius: 16px; margin-left: 12px; margin-bottom: 12px;" href="' . $tlink . '"><i class="fas fa-angle-double-left fa-sm"></i> <b>BACK</b></a>
                                  ';
                                ?>

                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                        <h5 class="modal-title" id="" style="font-size: 0.8rem;">Add New Clearance</h5>
                                        <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body" style="font-size: 0.7rem;">
                                        <div align="left">

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Clearance For: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="cprofid" id="cprofid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="cprofname" id="cprofname" value=""  onkeyup="filterFunction('cprofname','cprofItems')" placeholder="Clearance Type" onfocus="elementShowHide('cprofItems')" onclick="elementShowHide('cprofItems')" required>
                                              <div id="cprofItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  <?php
                                                    echo $gd_items_cprof;
                                                  ?>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Approved: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="margin-left: 80px;">
                                                <label class="switch">
                                                  <input type="checkbox" id="approved" name="approved" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-success  font-size-o-1" name="btnadd" value="Add Clearance" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                

                        </div>

                      </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                      <div class="col-sm-12" style="padding-left: 12px;padding-right: 12px;">

                        <div align="left">

                          <div class="row">

                          <?php
                            //
                            //
                            $cstudid = trim($_GET['studid']);
                            //
                            // 
                            if ( trim($cstudid) != "" ) {
                              //
                              //
                              $clearance_name = "";
                              $clearance_item_count = 0;
                              $clearance_item_count_notapprov = 0;
                              $clearance_items = "";
                              $clearance_status = "";
                              //
                              $res_notapproved = "";
                              $res_approved = "";
                              //
                              $n = 0;
                              $n2 = 0;
                              //
                              $query = "SELECT 
                                          a.taskid,c.profilename,c.details AS profdesc,a.clearanceid,a.tasklistid,d.taskname,d.details AS taskdesc,a.empid,a.notes,a.approv,a.approveddate,a.lockifprevnotapproved,a.locknextifnotapproved,a.unlockble,a.norder  
                                          FROM tbl_clearance_tasks AS a 
                                          LEFT JOIN tbl_clearance AS b ON b.id=a.clearanceid 
                                          LEFT JOIN tbl_clearance_profile AS c ON c.profileid=b.clearanceid 
                                          LEFT JOIN tbl_clearance_tasklist AS d ON d.tasklistid=a.tasklistid 
                                          WHERE a.active='1' AND TRIM(UPPER(a.clearanceid))='" . trim(strtoupper($g_clearanceid)) . "' AND TRIM(UPPER(a.studid))='" . trim(strtoupper($cstudid)) . "' 
                                          ORDER BY a.norder ASC, d.taskname ASC ";
                              $result = mysqli_query($conn, $query);
                              if ($result) {
                                while ($row = mysqli_fetch_array($result)) {
                                  //
                                  //
                                  $tid = trim($row['taskid']);
                                  $tnorder = trim($row['norder']);
                                  $tprofname = trim($row['profilename']);
                                  $tprofdesc = trim($row['profdesc']);
                                  //
                                  $ttaskid = trim($row['tasklistid']);
                                  $ttaskname = trim($row['taskname']);
                                  $ttaskdesc = trim($row['taskdesc']);
                                  //
                                  $empid = trim($row['empid']);
                                  $empname = "";
                                  $tnotes = trim($row['notes']);
                                  $approv = trim($row['approv']);
                                  //
                                  $approvdate = trim($row['approveddate']);
                                  //
                                  $tlipna = trim($row['lockifprevnotapproved']);
                                  $tlnina = trim($row['locknextifnotapproved']);
                                  //echo "$tlipna";
                                  //
                                  $clearance_name = $tprofname;
                                  //
                                  //
                                  $fresult = pg_query($pgconn, "SELECT fullname from pis.employee where UPPER(TRIM(empid))='" . strtoupper(trim($empid)) . "' group by fullname order by fullname ASC ");
                                  //echo $log_u serid;
                                  if ($fresult) {
                                    while ($frow = pg_fetch_array($fresult)) {
                                      $empname = trim($frow['fullname']);
                                    }
                                  }
                                  //
                                  $tunlockable = trim($row['unlockble']);
                                  $tlock = "0";
                                  //
                                  if($tunlockable != "0" && $tunlockable != "1") {
                                    $tunlockable = "0";
                                  }
                                  //
                                  // IF NOT APPROVED
                                  if($approv != "1") {
                                    //
                                    $n++;
                                    //echo $tlock;
                                    if($n > 1 && $tunlockable == 0) {
                                      //echo " [$n] ";
                                      //
                                      //CHECK RECENT lnina
                                      $squery = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($g_clearanceid)) . "' AND TRIM(UPPER(studid))='" . trim(strtoupper($log_userid)) . "' AND TRIM(taskid)!='" . trim($tid) . "' AND norder<" . trim($tnorder) . " ORDER BY norder DESC LIMIT 1";
                                      $sresult = mysqli_query($conn, $squery);
                                      if ($sresult) {
                                        while ($srow = mysqli_fetch_array($sresult)) {
                                          $tsval = trim($srow['locknextifnotapproved']);
                                          //echo $srow['taskid'] . " -- " . $tid . " -- " . $tsval;
                                          if(trim($tsval) == trim("1")) {
                                            $tlock = "1";
                                          }
                                        }
                                      }
                                      //echo $tlock;
                                      //
                                    }
                                    if($n > 1 && $tunlockable == 0) {
                                      //
                                      //CHECK RECENT lipna
                                      if($tlipna == "1") {
                                        $squery = "SELECT * FROM tbl_clearance_tasks WHERE  active='1' AND TRIM(UPPER(clearanceid))='" . trim(strtoupper($g_clearanceid)) . "' AND TRIM(UPPER(studid))='" . trim(strtoupper($log_userid)) . "' AND TRIM(taskid)!='" . trim($tid) . "' AND norder<" . trim($tnorder) . " ORDER BY norder DESC LIMIT 1";
                                        $sresult = mysqli_query($conn, $squery);
                                        if ($sresult) {
                                          while ($srow = mysqli_fetch_array($sresult)) {
                                            $tsval = trim($srow['approv']);
                                            //echo $srow['taskid'] . " -- " . $tid . " -- " . $tsval;
                                            if(trim($tsval) != trim("1")) {
                                              $tlock = "1";
                                            }
                                          }
                                        }
                                      }
                                      //echo $tlock;
                                      //
                                    }
                                    if($tlock != "0" && $tlock != "1") {
                                      $tlock = "0";
                                    }
                                    //
                                    //
                                    $tstyle1 = "";
                                    $tclass1 = " text-primary ";
                                    $tclass2 = " text-gray-800 ";
                                    $tcardclass1 = " border-left-primary ";
                                    //
                                    $ttooltip = "";
                                    $tcursor = " cursor: pointer; ";
                                    //
                                    $ticon = "fas fa-bookmark";
                                    $ticonstyle = ' style="color: #dddfeb;" ';
                                    if($n == 1) {
                                      $ticon = "fas fa-map-marker-alt";
                                    }
                                    if($approv == "1") {
                                      $ticon = "far fa-check-circle";
                                      $ticonstyle = ' style="color: #28a745;" ';
                                    }
                                    //
                                    // CHECK IF NEEDS TO DISABLE
                                    if($n > 1 && $approv != "1" && $tlock == 1) {
                                      $ticon = "fas fa-ban";
                                      $ticonstyle = ' style="color: #dddfeb;" ';
                                      //
                                      $tstyle1 = " color: #bababf; ";
                                      $tclass1 = "  ";
                                      $tclass2 = "  ";
                                      $tcardclass1 = " border-left-secondary ";
                                      //
                                      $ttooltip = ' data-toggle="tooltip" title="This task is locked. You must accomplish the previous task to unlock it."  data-placement="bottom" ';
                                      //
                                      $tcursor = " cursor: not-allowed; ";
                                    }
                                    //
                                    //
                                    if($ttaskid != "" && $ttaskname != "") {
                                      //
                                      $res_approved = $res_approved . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card ' . $tcardclass1 . ' shadow h-100 py-2">
                                            <a class="link-card-1" style="' . $tcursor . ' color: #bababf;" href="#"  ' . $ttooltip . ' >
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 62px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold ' . $tclass1 . ' text-uppercase mb-1" style="' . $tstyle1 . '">' . trim($ttaskname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold ' . $tclass2 . '" style="font-weight: normal; font-style: italic; font-size: 0.7rem;' . $tstyle1 . '">' . trim($empname) . '</div>
                                                    <div class="h5 mb-0" style="font-weight: normal; font-style: italic; font-size: 0.6rem; color: #bababf;">' . trim($ttaskdesc) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                      ';
                                      //
                                      //
                                      // FOR CLEARANCE FORM
                                      $clearance_item_count++;
                                      $clearance_items = $clearance_items . '
                                                                            <tr>
                                                                              <td class="clearance-form-tbl-1-td-0" style="font-weight: bold;">' . $clearance_item_count . '.</td><td class="clearance-form-tbl-1-td-1" style="font-weight: bold;">' . trim($ttaskname) . '<br/>' . '<span class="clearance-form-details-1">' . trim($ttaskdesc) . '</span>' . '</td><td class="clearance-form-tbl-1-td-2"></td>
                                                                            </tr>
                                      ';
                                      $clearance_item_count_notapprov++;
                                      //
                                    }
                                  } // END IF NOT APPROVED
                                  // IF APPROVED
                                  if($approv == "1") {
                                    //
                                    $n2++;
                                    //
                                    $tstyle1 = "";
                                    $tclass1 = " text-primary ";
                                    $tclass2 = " text-gray-800 ";
                                    $tcardclass1 = " border-left-primary ";
                                    //
                                    $ttooltip = "";
                                    $tcursor = " cursor: default; ";
                                    //
                                    $ticon = "far fa-check-circle";
                                    $ticonstyle = ' style="color: #28a745;" ';
                                    //
                                    $res_notapproved = $res_notapproved . '
                                        <div class="col-xl-2 col-md-6 mb-4">
                                          <div class="card ' . $tcardclass1 . ' shadow h-100 py-2">
                                            <a class="link-card-1" style="' . $tcursor . ' color: #bababf;" href="#"  ' . $ttooltip . ' >
                                              <div class="card-body" style="padding-top: 8px; padding-bottom: 8px; min-height: 62px;">
                                                <div class="row no-gutters align-items-center">
                                                  <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold ' . $tclass1 . ' text-uppercase mb-1" style="' . $tstyle1 . '">' . trim($ttaskname) . '</div>
                                                    <div class="h5 mb-0 font-weight-bold ' . $tclass2 . '" style="font-weight: normal; font-style: italic; font-size: 0.7rem;' . $tstyle1 . '">' . trim($empname) . '</div>
                                                    <div class="h5 mb-0" style="font-weight: normal; font-style: italic; font-size: 0.6rem; color: #bababf;">' . trim($ttaskdesc) . '</div>
                                                  </div>
                                                  <div class="col-auto">
                                                    <i class=" ' . $ticon . ' fa-lg " ' . $ticonstyle . ' ></i>
                                                  </div>
                                                </div>
                                              </div>
                                            </a>
                                          </div>
                                        </div>
                                    ';
                                    //
                                    // FOR CLEARANCE FORM
                                    $clearance_item_count++;
                                    $clearance_items = $clearance_items . '
                                                                          <tr>
                                                                            <td class="clearance-form-tbl-1-td-0" style="font-weight: bold;">' . $clearance_item_count . '.</td><td class="clearance-form-tbl-1-td-1" style="font-weight: bold;">' . trim($ttaskname) . '<br/>' . '<span class="clearance-form-details-1">' . '</td><td class="clearance-form-tbl-1-td-2"><i class="far fa-check-circle fa-lg " style="color: #28a745;"></i> <span class="clearance-form-date-1">' . $approvdate . '</span></td>
                                                                          </tr>
                                    ';
                                    //
                                    //
                                  }// END IF APPROVED
                                }
                              }
                              //
                              //
                              // CLEARANCER BASIC FORM
                              if($clearance_item_count_notapprov <= 0) {
                                $clearance_status = '<span class="" style="color: #28a745;">CLEARED</span>';
                              }else{
                                $clearance_status = '<span class="text-danger">NOT CLEARED</span>';
                              }
                              echo '
                                  <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                                    <div align="center">

                                      <div class="col-sm-4" style="padding-left: 4px;padding-right: 4px;">

                                        <div class="card shadow mb-4">
                                          <div class="card-header py-3">
                                            <div align="center">
                                              <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">' . strtoupper($clearance_name) . '</span></h6>
                                            </div>
                                            
                                          </div>
                                          <div class="card-body" style="padding-left: 0px;padding-right: 0px;" align="left">
                                            
                                            <div class="" style="padding-left: 20px; font-size: 0.75rem; color: #606060;">
                                              <table class="clearance-form-tbl-1">
                                              ' . $clearance_items . ' 
                                              </table>
                                            </div>   

                                            <hr>

                                            <div style="padding-right: 20px; font-size: 0.7rem; color: #606060; font-weight: bold;" align="right">
                                              STATUS: ' . $clearance_status . '
                                            </div>
                                            
                                          </div>
                                        </div>

                                      </div>
                                      
                                    </div>

                                  </div>
                              ';
                              // SHOW RESULT
                              if($n > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 10px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>On-going Tasks</b></div>
                                      </div>
                                ';
                                // END TILE
                                // SHOW RESULT
                                echo $res_approved;
                              }
                              // SHOW RESULT
                              if($n2 > 0) {
                                // TILE
                                echo '
                                      <div class="col-sm-12" style="margin-top: 20px; margin-bottom: 10px;">
                                        <div style=" width: 0; height: 0; border-bottom: 28px solid #163763; border-right: 18px solid transparent; position: absolute; margin-left: 120px;"></div>
                                        <div class="btn-c-1 btn-width-min-1" style="font-size: 0.6rem; color: #fff; background-color: #163763; border-radius: 0px; height: 28px; width: 120px; text-align: center; padding-top: 6px;"> <b>Accomplished</b></div>
                                      </div>
                                ';
                                // END TILE
                                // ECHO APPROVED
                                echo $res_notapproved;
                              }
                              //
                            } // END CHECK IF STUDENT
                            //
                          ?>
                          <!--  -->

                          </div>

                        </div>

                      </div>
            </div>

            <!-- Content Row -->
            <div class="row">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          
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
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
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
