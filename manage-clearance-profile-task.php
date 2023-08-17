<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  include "access_check.php";
  //
  if((strtolower(trim($log_user_type)) == strtolower(trim("student")) || (strtolower(trim($log_user_type)) == strtolower(trim("employee")) && $log_user_role_isadmin <= 0))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  //
  $gd_items_tasks = "";
  $gd_items_tasks_u = "";
  $gd_items_emp = "";
  $gd_items_emp_u = "";
  $gd_items_stud = "";
  //
  $g_prof_id = "";
  $g_prof_id = trim($_GET['pid']);
  //
  if(trim($log_userid)!="") {
    if($_POST['btnadd']) {
      //
      $task = trim($_POST['taskid']);
      $employee = trim($_POST['empid']);
      $order = trim($_POST['order']);
      $autoemp = trim($_POST['autoemp']);
      $approved = trim($_POST['approved']);
      $lockifprevunapproved = trim($_POST['lockifprevunapproved']);
      $locknextifunapproved = trim($_POST['locknextifunapproved']);
      $unlockable = trim($_POST['unlockable']);
      //
      if(trim($order) == "") {
        $order = "1";
      }
      if(trim($autoemp) == "") {
        $autoemp = "0";
      }
      if(trim($approved) == "") {
        $approved = "0";
      }
      if(trim($lockifprevunapproved) == "") {
        $lockifprevunapproved = "0";
      }
      if(trim($locknextifunapproved) == "") {
        $locknextifunapproved = "0";
      }
      if(trim($unlockable) == "") {
        $unlockable = "0";
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($task) == "") {
        $errn++;
        $errmsg = $errmsg . "Task required. ";
      }
      //
      $query0 = "SELECT * FROM tbl_clearance_profile_tasks where LOWER(TRIM(profileid))=LOWER(TRIM('" . $g_prof_id . "')) AND LOWER(TRIM(tasklistid))=LOWER(TRIM('" . $task . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Task already added. ";
        }
      }
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = " INSERT INTO tbl_clearance_profile_tasks (profileid,tasklistid,empid,autoemployee,approv,lockifprevnotapproved,locknextifnotapproved,unlockble,norder,addedby) VALUES ('" . $g_prof_id . "','" . $task . "','" . $employee . "','" . $autoemp . "','" . $approved . "','" . $lockifprevunapproved . "','" . $locknextifunapproved . "','" . $unlockable . "','" . $order . "','" . trim($log_userid) . "') ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Task added.
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> ' . $errmsg . '
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
      //
      $task = trim($_POST['taskidu']);
      $employee = trim($_POST['empidu']);
      $order = trim($_POST['order']);
      $autoemp = trim($_POST['autoemp']);
      $approved = trim($_POST['approved']);
      $lockifprevunapproved = trim($_POST['lockifprevunapproved']);
      $locknextifunapproved = trim($_POST['locknextifunapproved']);
      $unlockable = trim($_POST['unlockable']);
      //
      if(trim($order) == "") {
        $order = "1";
      }
      if(trim($autoemp) == "") {
        $autoemp = "0";
      }
      if(trim($approved) == "") {
        $approved = "0";
      }
      if(trim($lockifprevunapproved) == "") {
        $lockifprevunapproved = "0";
      }
      if(trim($locknextifunapproved) == "") {
        $locknextifunapproved = "0";
      }
      if(trim($unlockable) == "") {
        $unlockable = "0";
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($task) == "") {
        $errn++;
        $errmsg = $errmsg . "Task required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tbl_clearance_profile_tasks set tasklistid='" . $task . "',empid='" . $employee . "',norder='" . $order . "',autoemployee='" . $autoemp . "',approv='" . $approved . "',lockifprevnotapproved='" . $lockifprevunapproved . "',locknextifnotapproved='" . $locknextifunapproved . "',unlockble='" . $unlockable . "' WHERE profiletaskid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Task updated.
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> ' . $errmsg . '
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
        $query = "DELETE FROM tbl_clearance_profile_tasks  WHERE profiletaskid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Task deleted.
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> ' . $errmsg . '
            <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    //
    ///
    //
    //LOAD ITEMS FOR TASKS
    $n = 0;
    $lquery = "SELECT * FROM tbl_clearance_tasklist ORDER BY taskname ASC";
    $lresult = mysqli_query($conn, $lquery);
    if ($lresult) {
      while ($lrow = mysqli_fetch_array($lresult)) {
        $tid = trim($lrow['tasklistid']);
        $tname = trim($lrow['taskname']);
        if($tid != "" && $tname != "") {
          $gd_items_tasks = $gd_items_tasks . '
                  <input type="hidden" id="task_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="task_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'task','taskid','task'" . ')">' . $tname . '</a>
                ';
          //
          $gd_items_tasks_u = $gd_items_tasks_u . '
                  <input type="hidden" id="tasku_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="tasku_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'tasku','taskidu','tasku'" . ')">' . $tname . '</a>
                ';
          $n++;
        }
      }
    }
    //LOAD ITEMS FOR EMPLOYEES
    $n = 0;
    $gd_items_emp = $gd_items_emp . '
            <input type="hidden" id="emp_id_' . $n . '" value="" />
            <input type="hidden" id="emp_name_' . $n . '" value="" />
            <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'emp','empid','employee'" . ')"> --- None --- </a>
          ';
    $gd_items_emp_u = $gd_items_emp_u . '
            <input type="hidden" id="empu_id_' . $n . '" value="" />
            <input type="hidden" id="empu_name_' . $n . '" value="" />
            <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'empu','empidu','employeeu'" . ')"> --- None --- </a>
          ';
    $n++;
    $lquery2 = "SELECT empid,fullname,lastname,firstname,middlename FROM pis.employee ORDER BY fullname ASC";
    $lresult2 = pg_query($pgconn, $lquery2);
    if ($lresult2) {
      while ($lrow2 = pg_fetch_array($lresult2)) {
        $tid = trim($lrow2['empid']);
        $tname = trim($lrow2['fullname']);
        if($tid != "" && $tname != "") {
          $gd_items_emp = $gd_items_emp . '
                  <input type="hidden" id="emp_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="emp_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'emp','empid','employee'" . ')">' . $tname . '</a>
                ';
          //
          $gd_items_emp_u = $gd_items_emp_u . '
                  <input type="hidden" id="empu_id_' . $n . '" value="' . $tid . '" />
                  <input type="hidden" id="empu_name_' . $n . '" value="' . $tname . '" />
                  <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'empu','empidu','employeeu'" . ')">' . $tname . '</a>
                ';
          $n++;
        }
      }
    }
    //
  } //END CHECK LOG USER ID
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

  <title>Profile Tasks</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Profile Tasks</span></h6>
                                </div>
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" style="font-size: 0.6rem; border-radius: 0px;" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                        <h5 class="modal-title" id="" style="font-size: 0.8rem;">Add New Task</h5>
                                        <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body" style="font-size: 0.7rem;">
                                        <div align="left">

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Order: <span class="text-danger"></span></span>
                                            <input type="number" class="form-control form-control-user  input-text-value font-size-o-1" style="max-width: 20%; min-width: 100px;"  name="order" id="order" min="1" placeholder="Task" value="1"  required>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Task: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="taskid" id="taskid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="task" id="task" value=""  onkeyup="filterFunction('task','taskItems')" placeholder="Task" onfocus="elementShowHide('taskItems')" onclick="" required>
                                              <div id="taskItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  <?php
                                                    echo $gd_items_tasks;
                                                  ?>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Assigned Employee: <span class="text-danger"></span></span>
                                            <input type="hidden"  name="empid" id="empid" value="" >
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="employee" id="employee" value=""  onkeyup="filterFunction('employee','empItems')" placeholder="Employee" onfocus="elementShowHide('empItems')" onclick="" >
                                              <div id="empItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  <?php
                                                    echo $gd_items_emp;
                                                  ?>
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Auto-load Employee: <span class="text-danger"></span><br/><span class="switch-label-1-1">(For department and dean only.)</span></span>
                                              </div>
                                              <div class="div-switch-1" style="">
                                                <label class="switch">
                                                  <input type="checkbox" id="autoemp" name="autoemp" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                          <br/>


                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Approved: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="">
                                                <label class="switch">
                                                  <input type="checkbox" id="approved" name="approved" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Lock if previous task is un-approved: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="">
                                                <label class="switch">
                                                  <input type="checkbox" id="lockifprevunapproved" name="lockifprevunapproved" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Lock next task if this is un-approved: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="">
                                                <label class="switch">
                                                  <input type="checkbox" id="locknextifunapproved" name="locknextifunapproved" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Unlockable: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="">
                                                <label class="switch">
                                                  <input type="checkbox" id="unlockable" name="unlockable" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>



                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnadd" value="Save changes" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="table-responsive div-table-wrapper-1">
                                <div class="div-min-width-1">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col" style="min-width: 140px;"></th>
                                    <th scope="col">#</th>
                                    <th scope="col">Task</th>
                                    <th scope="col">Assigned Employee</th>
                                    <th scope="col">Auto-load Employee</th>
                                    <th scope="col">Default Status</th>
                                    <th scope="col">Lock if Prev not Approved</th>
                                    <th scope="col">Lock Next if not Approved</th>
                                    <th scope="col">Unlockable</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    //
                                    //
                                    $tid = trim($_GET['pid']);
                                    //
                                    $query = " SELECT 
                                                  a.profiletaskid,a.tasklistid,c.taskname,a.empid,a.autoemployee,a.approv,a.lockifprevnotapproved,a.locknextifnotapproved,a.unlockble,a.norder 
                                               FROM tbl_clearance_profile_tasks AS a  
                                               LEFT JOIN tbl_clearance_profile AS b ON b.profileid=a.profileid 
                                               LEFT JOIN tbl_clearance_tasklist AS c ON c.tasklistid=a.tasklistid 
                                               WHERE a.profileid='" . $tid . "' 
                                               ORDER BY a.norder ASC,c.taskname ASC 
                                    ";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                      $n = 0;
                                      while ($row = mysqli_fetch_array($result)) {
                                        $n++;
                                        //
                                        $torder = trim($row['norder']);
                                        if(trim($torder) == "") {
                                          $torder = "1";
                                        }else{
                                          if($torder < 1) {
                                            $torder = "1";
                                          }
                                        }
                                        //
                                        $ttaskid = trim($row['tasklistid']);
                                        $ttaskname = trim($row['taskname']);
                                        //
                                        $tautoemp = trim($row['autoemployee']);
                                        $tautoempf = "No";
                                        $tautoemp_c = "";
                                        if(trim(strtolower($tautoemp))==trim(strtolower("1"))) {
                                          $tautoempf = "Yes";
                                          $tautoemp_c = " checked ";
                                        }
                                        //
                                        $tapprove = trim($row['approv']);
                                        $tapprovef = "Not Approved";
                                        $tapprove_c = "";
                                        if(trim(strtolower($tapprove))==trim(strtolower("1"))) {
                                          $tapprovef = "Approved";
                                          $tapprove_c = " checked ";
                                        }
                                        //
                                        $tlockifprevna = trim($row['lockifprevnotapproved']);
                                        $tlockifprevnaf = "No";
                                        $tlockifprevna_c = "";
                                        if(trim(strtolower($tlockifprevna))==trim(strtolower("1"))) {
                                          $tlockifprevnaf = "Yes";
                                          $tlockifprevna_c = " checked ";
                                        }
                                        //
                                        $tlocknextifna = trim($row['locknextifnotapproved']);
                                        $tlocknextifnaf = "No";
                                        $tlocknextifna_c = "";
                                        if(trim(strtolower($tlocknextifna))==trim(strtolower("1"))) {
                                          $tlocknextifnaf = "Yes";
                                          $tlocknextifna_c = " checked ";
                                        }
                                        //
                                        $tunlockable = trim($row['unlockble']);
                                        $tunlockablef = "No";
                                        $tunlockable_c = "";
                                        if(trim(strtolower($tunlockable))==trim(strtolower("1"))) {
                                          $tunlockablef = "Yes";
                                          $tunlockable_c = " checked ";
                                        }
                                        //
                                        $empid = trim($row['empid']);
                                        $empname = "";
                                        $fresult = pg_query($pgconn, "SELECT fullname from pis.employee where UPPER(TRIM(empid))='" . strtoupper(trim($empid)) . "' group by fullname order by fullname ASC ");
                                        //echo $log_userid;
                                        if ($fresult) {
                                          while ($frow = pg_fetch_array($fresult)) {
                                            $empname = trim($frow['fullname']);
                                          }
                                        }
                                        //
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                                    <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Task</h5>
                                                    <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body" style="font-size: 0.7rem;">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['profiletaskid']) . '" hidden />

                                                      <div class="form-group margin-top1" >
                                                        <span class="label1">Order: <span class="text-danger"></span></span>
                                                        <input type="number" class="form-control form-control-user  input-text-value font-size-o-1" style="max-width: 20%; min-width: 100px;"  name="order" id="order" min="1" placeholder="Task" value="' . $torder . '"  required>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <span class="label1">Task: <span class="text-danger"></span></span>
                                                        <input type="hidden"  name="taskidu" id="taskidu_' . $n . '" value="' . $ttaskid . '" required>
                                                        <div id="divHolder">
                                                          <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="tasku" id="tasku_' . $n . '" value="' . $ttaskname . '"  onkeyup="filterFunction(' . "'tasku_" . $n . "','taskuItems_" . $n . "'" . ')" placeholder="Task" onfocus="elementShowHide2(' . "'taskuItems_" . $n . "','taskidu_" . $n . "','tasku_" . $n . "'" . ')" onclick="elementShowHide2(' . "'taskuItems_" . $n . "','taskidu_" . $n . "','tasku_" . $n . "'" . ')" required>
                                                          <div id="taskuItems_' . $n . '" class="div-text-filter-holder-1" style="display: none;">
                                                            <div class="div-text-filter-holder-wrapper-1">
                                                              ' . $gd_items_tasks_u . '
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <span class="label1">Assigned Employee: <span class="text-danger"></span></span>
                                                        <input type="hidden"  name="empidu" id="empidu_' . $n . '" value="' . $empid . '" >
                                                        <div id="divHolder">
                                                          <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="employeeu" id="employeeu_' . $n . '" value="' . $empname . '"  onkeyup="filterFunction(' . "'employeeu_" . $n . "','empuItems_" . $n . "'" . ')" placeholder="Employee" onfocus="elementShowHide2(' . "'empuItems_" . $n . "','empidu_" . $n . "','employeeu_" . $n . "'" . ')" onclick="elementShowHide2(' . "'empuItems_" . $n . "','empidu_" . $n . "','employeeu_" . $n . "'" .  ')" >
                                                          <div id="empuItems_' . $n . '" class="div-text-filter-holder-1" style="display: none;">
                                                            <div class="div-text-filter-holder-wrapper-1">
                                                              ' . $gd_items_emp_u . '
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Auto-load Employee: <span class="text-danger"></span><br/><span class="switch-label-1-1">(For department and dean only.)</span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="">
                                                            <label class="switch">
                                                              <input type="checkbox" id="autoemp" name="autoemp" value="1" ' . $tautoemp_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <br/>

                                                      <div class="form-group margin-top1" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Approved: <span class="text-danger"></span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="">
                                                            <label class="switch">
                                                              <input type="checkbox" id="approved" name="approved" value="1" ' . $tapprove_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Lock if previous task is un-approved: <span class="text-danger"></span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="">
                                                            <label class="switch">
                                                              <input type="checkbox" id="lockifprevunapproved" name="lockifprevunapproved" value="1" ' . $tlockifprevna_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Lock next task if this is un-approved: <span class="text-danger"></span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="">
                                                            <label class="switch">
                                                              <input type="checkbox" id="locknextifunapproved" name="locknextifunapproved" value="1" ' . $tlocknextifna_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Unlockable: <span class="text-danger"></span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="">
                                                            <label class="switch">
                                                              <input type="checkbox" id="unlockable" name="unlockable" value="1" ' . $tunlockable_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                                    <h5 class="modal-title" id="" style="font-size: 0.8rem;">Delete Task</h5>
                                                    <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body" style="font-size: 0.7rem;">
                                                    <div align="left">
                                                      
                                                      <input type="hidden" name="id" value="' . trim($row['profiletaskid']) . '" hidden />

                                                      Delete <b>' . trim($row['taskname']) . '</b> ?
                                                      
                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-danger font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btndelete" value="Delete" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <th scope="row" class="table-row-width-1">
                                              <button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button>
                                              <button type="button" class="btn btn-danger btn-table-1" style="border-radius: 0px;" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="table-row-width-2">' . $n . '</th>
                                            <td>' . trim($row['taskname']) . '</td>
                                            <td>' . trim($empname) . '</td>
                                            <td>' . trim($tautoempf) . '</td>
                                            <td>' . trim($tapprovef) . '</td>
                                            <td>' . trim($tlockifprevnaf) . '</td>
                                            <td>' . trim($tlocknextifnaf) . '</td>
                                            <td>' . trim($tunlockablef) . '</td>
                                          </tr>
                                        ';
                                      }
                                    }
                                    
                                  ?>
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
