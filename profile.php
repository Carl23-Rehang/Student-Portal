<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  //
  $lid = trim($_GET['id']);
  $lt = trim($_GET['t']);
  if(trim($lt)=="") {
    //$lt = "student";
  }
  //
  //
  if(trim($log_userid) == "") {
    //echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    //exit();
  }
  //
  //echo "2";
  //
  $prof_photo = "";
  //
  $contact_email = "";
  $contact_no = "";
  //$bdate = "2020-12-12";
  $bdate = "";
  $bdated = explode("-", $bdate);
  $bdate_year = $bdated[0];
  $bdate_month = $bdated[1];
  $bdate_day = $bdated[2];
  //
  //echo "3";
  //
  $up_error = "";
  $file_upload_name = "";
  $log_file_upload_name = "";
  if( ( strtolower(trim($lid)) == strtolower(trim($log_userid)) && strtolower(trim($lt)) == strtolower(trim($log_user_type)) ) || ( $log_user_role_isadmin > 0 ) ) {
    if($_POST['btnsave']) {
      //
      $target_dir = "uploads/";
      //$target_file = $target_dir . basename($_FILES["fphoto"]["name"]);
      $target_file = $target_dir . basename($_FILES["fphoto"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      //
      $namepref = "";
      if(strtolower(trim($lt))==strtolower(trim("employee"))) {
        $namepref = "EMP_";
      }
      if(strtolower(trim($lt))==strtolower(trim("student"))) {
        $namepref = "STUD_";
      }
      //LOG
      $log_file_upload_name = $target_dir . basename($_FILES["fphoto"]["name"]);;
      //
      //$target_file = $target_dir . $namepref . strtoupper(trim($log_userid)) . "." . trim($imageFileType);
      $target_file = $target_dir . $namepref . strtoupper(trim($lid)) . "." . trim($imageFileType);


      // Check if image file is a actual image
      if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fphoto"]["tmp_name"]);
        if($check !== false) {
          //echo "File is an image - " . $check["mime"] . ".";
          //$uploadOk = 1;
        } else {
          //echo "File is not an image.";
          //$uploadOk = 0;
        }
      }

      // Check if file already exists
      if (file_exists($target_file)) {
        //echo "Sorry, file already exists.";
        //$uploadOk = 0;
      }

      // Check file size
      if ($_FILES["fphoto"]["size"] > 2000000) {
        //echo "Sorry, your file is too large.";
        $up_error = $up_error . "File too large to upload as profile photo. ";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" && $imageFileType != "bmp" && $imageFileType != "ico" ) {
        //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        //$up_error = $up_error . "Sorry, only JPG, JPEG, PNG, GIF, BMP, ICO files are allowed. ";
        $uploadOk = 0;
      }

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        //echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
        if(trim($up_error) != "") {
          $up_error = '
                    <div class="col-xl-8 col-lg-7">

                      <div class="alert alert-danger alert-dismissible font-size-alert-1">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        ' . $up_error . '
                      </div>
                            
                    </div>
          ';
        }
      } else {
        if (move_uploaded_file($_FILES["fphoto"]["tmp_name"], $target_file)) {
          //echo "The file ". basename( $_FILES["fphoto"]["name"]). " has been uploaded.";
          $file_upload_name = trim($target_file);
        } else {
          //echo "Sorry, there was an error uploading your file.";
        }
      }
      //
    } // END btnsave
  } // END IF PROFILE IS CURRENT USER
  //echo "1";
  //
  //
  if( ( strtolower(trim($lid)) == strtolower(trim($log_userid)) && strtolower(trim($lt)) == strtolower(trim($log_user_type)) ) || ( $log_user_role_isadmin > 0 ) ) {
    //LOGS
    $tsn = 0;
    $update_log_id = $log_userid;
    $update_log_user = $log_user;
    $update_log_pass = "";
    $update_log_email = trim($_POST['email']);
    $update_log_contactno = trim($_POST['contactno']);
    $update_log_photo = trim($log_file_upload_name);
    //
    if(strtolower(trim($lt))==strtolower(trim("employee"))) {
      if($_POST['btnsave']) {
        $cno = trim($_POST['contactno']);
        $email = trim($_POST['email']);
        //
        $aq = "";
        if(trim($file_upload_name) != "") {
          $aq = " ,profilephoto='" . $file_upload_name . "' ";
          //
          if(strtolower(trim($lid)) == strtolower(trim($log_userid))) {
            $_SESSION[$appid . "c_user_photo"] = $file_upload_name;
          }
        }
        //
        //$qry = "update web.employee set contactno='" . $cno . "', email='" . $email . "' " . $aq . " where empid='" . $log_userid . "' ";
        $qry = "update web.employee set contactno='" . $cno . "', email='" . $email . "' " . $aq . " where empid='" . $lid . "' ";
        //echo $qry;
        $result = pg_query($pgconn, $qry);
        //
        $_SESSION[$appid . "c_user_photo"] = $file_upload_name;
        //LOG COUNTER
        $tsn++;
        //SAVE LOGS
        if($tsn > 0){
          $qry = "insert into web.employee_update_logs (empid,username,password,email,contactno,profilephoto) 
                   VALUES ('" . $update_log_id . "','" . $update_log_user . "','" . $update_log_pass . "','" . $update_log_email . "','" . $update_log_contactno . "','" . $update_log_photo . "') ";
          $result = pg_query($pgconn, $qry);
        }
        //
      }
      //
      if($_POST['btnsavepass']) {
        $cpass = trim($_POST['cpass']);
        $npass = trim($_POST['npass']);
        $npass2 = trim($_POST['npass2']);
        //
        $errn = 0;
        $errmsg = "";
        //
        if($cpass=="") {
          //$errn++;
          //$errmsg = $errmsg . "Current password required. ";
        }
        //CHECK PASS
        $rowcount0 = 0;
        $query0 = "SELECT * FROM web.employee WHERE LOWER(TRIM(empid))=LOWER(TRIM('" . $log_userid . "')) AND password='" . $cpass . "' ";
        $result0 = pg_query($pgconn, $query0);
        if ($result0) {
          $rowcount0 = pg_num_rows($result0);
        }
        if($rowcount0 <= 0) {
          $errn++;
          $errmsg = $errmsg . "Incorrect current password. ";
        }
        //
        if($npass=="") {
          $errn++;
          $errmsg = $errmsg . "New password required. ";
        }
        if($npass!=$npass2) {
          $errn++;
          $errmsg = $errmsg . "New passwords don't match. ";
        }
        //
        if($errn <= 0) {
          //LOG
          $update_log_pass = $npass;
          //
          //$qry = "update web.employee set password='" . $npass . "'  where empid='" . $log_userid . "' ";
          $qry = "update web.employee set password='" . $npass . "'  where empid='" . $lid . "' ";
          $result = pg_query($pgconn, $qry);
          //
          $up_res = '
              <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px;">
                <div class="alert alert-success alert-dismissible font-size-alert-1">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  Password changed.
                </div>
              </div>
          ';
        }else{
          $up_res = '
              <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px;">
                <div class="alert alert-danger alert-dismissible font-size-alert-1">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  ' . $errmsg . '
                </div>
              </div>
          ';
        }
        //LOG COUNTER
        $tsn++;
        //SAVE LOGS
        if($tsn > 0){
          $qry = "insert into web.employee_update_logs (empid,username,password,email,contactno,profilephoto) 
                   VALUES ('" . $update_log_id . "','" . $update_log_user . "','" . $update_log_pass . "','" . $update_log_email . "','" . $update_log_contactno . "','" . $update_log_photo . "') ";
          $result = pg_query($pgconn, $qry);
        }
        //
      }
    } //END IF EMPLOYEE
    if(strtolower(trim($lt))==strtolower(trim("student"))) {
      if($_POST['btnsave']) {
        $cno = trim($_POST['contactno']);
        $email = trim($_POST['email']);
        //
        $bd_month = trim($_POST['bd_month']);
        $bd_day = trim($_POST['bd_day']);
        $bd_year = trim($_POST['bd_year']);
        if (strlen($bd_month) < 2) {
          $bd_month = "0" . $bd_month;
        }
        if (strlen($bd_day) < 2) {
          $bd_day = "0" . $bd_day;
        }
        $fdate = $bd_year . "-" . $bd_month . "-" . $bd_day;
        //
        //
        $aq = "";
        if(trim($file_upload_name) != "") {
          $aq = " ,profilephoto='" . $file_upload_name . "' ";
          //
          if(strtolower(trim($lid)) == strtolower(trim($log_userid))) {
            $_SESSION[$appid . "c_user_photo"] = $file_upload_name;
          }
        }
        //
        //$qry = "update web.student set contactno='" . $cno . "', email='" . $email . "' " . $aq . " where studid='" . $log_userid . "' ";
        $qry = "update web.student set contactno='" . $cno . "', email='" . $email . "' " . $aq . " where studid='" . $lid . "' ";
        $result = pg_query($pgconn, $qry);
        //echo $qry;
        //
        //UPDATE BDATE
        //$qry = "update srgb.student set studbirthdate='" . $fdate . "' where studid='" . $log_userid . "' ";
        $qry = "update srgb.student set studbirthdate='" . $fdate . "' where studid='" . $lid . "' ";
        $result = pg_query($pgconn, $qry);
        //echo $qry;
        //LOG COUNTER
        $tsn++;
        //SAVE LOGS
        if($tsn > 0){
          $qry = "insert into web.student_update_logs (studid,username,password,email,contactno,profilephoto) 
                   VALUES ('" . $update_log_id . "','" . $update_log_user . "','" . $update_log_pass . "','" . $update_log_email . "','" . $update_log_contactno . "','" . $update_log_photo . "') ";
          $result = pg_query($pgconn, $qry);
        }
        //
      }
      //
      if($_POST['btnsavepass']) {
        $cpass = trim($_POST['cpass']);
        $npass = trim($_POST['npass']);
        $npass2 = trim($_POST['npass2']);
        //
        $errn = 0;
        $errmsg = "";
        //
        if($cpass=="") {
          //$errn++;
          //$errmsg = $errmsg . "Current password required. ";
        }
        //CHECK PASS
        $rowcount0 = 0;
        $query0 = "SELECT * FROM web.student WHERE LOWER(TRIM(studid))=LOWER(TRIM('" . $log_userid . "')) AND password='" . $cpass . "' ";
        $result0 = pg_query($pgconn, $query0);
        if ($result0) {
          $rowcount0 = pg_num_rows($result0);
        }
        if($rowcount0 <= 0) {
          $errn++;
          $errmsg = $errmsg . "Incorrect current password. ";
        }
        //
        if($npass=="") {
          $errn++;
          $errmsg = $errmsg . "New password required. ";
        }
        if($npass!=$npass2) {
          $errn++;
          $errmsg = $errmsg . "New passwords don't match. ";
        }
        //
        if($errn <= 0) {
          //LOG
          $update_log_pass = $npass;
          //
          //$qry = "update web.student set password='" . $npass . "'  where studid='" . $log_userid . "' ";
          $qry = "update web.student set password='" . $npass . "'  where studid='" . $lid . "' ";
          $result = pg_query($pgconn, $qry);
          //
          $up_res = '
              <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px;">
                <div class="alert alert-success alert-dismissible font-size-alert-1">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  Password changed.
                </div>
              </div>
          ';
        }else{
          $up_res = '
              <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px;">
                <div class="alert alert-danger alert-dismissible font-size-alert-1">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  ' . $errmsg . '
                </div>
              </div>
          ';
        }
        //LOG COUNTER
        $tsn++;
        //SAVE LOGS
        if($tsn > 0){
          $qry = "insert into web.student_update_logs (studid,username,password,email,contactno,profilephoto) 
                   VALUES ('" . $update_log_id . "','" . $update_log_user . "','" . $update_log_pass . "','" . $update_log_email . "','" . $update_log_contactno . "','" . $update_log_photo . "') ";
          $result = pg_query($pgconn, $qry);
        }
        //
        //
      }
    } // IF STUDENT
  } // END IF PROFILE IS CURRENT USER
  //
  //
  //GET SY, SEM
  $csy = "";
  $csem = "";
  $result = pg_query($pgconn, "SELECT * from srgb.semester where current='true' OR current='t' OR current='1'");
  if ($result) {
    while ($row = pg_fetch_array($result)) {
      $csy = trim($row['sy']);
      $csem = trim($row['sem']);
    }
  }
  //GET NAME
  $dfn = "";
  $dmn = "";
  $dfn = "";
  $dname = "";
  if(strtolower(trim($lt))==strtolower(trim("employee"))) {
    $result = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($lid)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $dfn = trim($row['firstname']);
        $dmn = trim($row['middlename']);
        $dln = trim($row['lastname']);
        if(strlen($dmn) == 1) {
          $dmn = $dmn . ".";
        }
        //
      }
    }
  }
  if(strtolower(trim($lt))==strtolower(trim("student"))) {
    $result = pg_query($pgconn, "SELECT * from srgb.student where LOWER(TRIM(studid))='" . strtolower(trim($lid)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $dfn = trim($row['studfirstname']);
        $dmn = trim($row['studmidname']);
        $dln = trim($row['studlastname']);
        if(strlen($dmn) == 1) {
          $dmn = $dmn . ".";
        }
        //
        $bdate = trim($row['studbirthdate']);
        $bdated = explode("-", $bdate);
        $bdate_year = $bdated[0];
        $bdate_month = $bdated[1];
        $bdate_day = $bdated[2];
        //
      }
    }
  }
  $dname = $dfn;
  if(strlen($dmn) > 0) {
    $dname = $dname . " " . $dmn;
  }
  if(strlen($dln) > 0) {
    $dname = $dname . " " . $dln;
  }
  //
  $progcode = "";
  $progname = "";
  $deptcode = "";
  $deptname = "";
  $deptcoll = "";
  $collname = "";
  //
  //IF EMPLOYEE
  if(strtolower(trim($lt))==strtolower(trim("employee"))) {
    $result = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($lid)) . "' ");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $contact_email = trim($row['email']);
        $contact_no = trim($row['contactno']);
        //
        $prof_photo = trim($row['profilephoto']);
        //
      }
    }
    //
    $result = pg_query($pgconn, "SELECT * from srgb.faculty where LOWER(TRIM(empid))='" . strtolower(trim($lid)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $deptcode = trim($row['deptcode']);
      }
    }
  }
  //
  //
  $result = pg_query($pgconn, "SELECT * from srgb.department where LOWER(TRIM(deptcode))='" . strtolower(trim($deptcode)) . "'");
  if ($result) {
    while ($row = pg_fetch_array($result)) {
      $deptname = trim($row['deptname']);
      $deptcoll = trim($row['deptcoll']);
    }
  }
  $result = pg_query($pgconn, "SELECT * from srgb.college where LOWER(TRIM(collcode))='" . strtolower(trim($deptcoll)) . "'");
  if ($result) {
    while ($row = pg_fetch_array($result)) {
      $collname = trim($row['collname']);
    }
  }
  //
  //IF STUDENT
  if(strtolower(trim($lt))==strtolower(trim("student"))) {
    $result = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(studid))='" . strtolower(trim($lid)) . "' ");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $contact_email = trim($row['email']);
        $contact_no = trim($row['contactno']);
        //
        $prof_photo = trim($row['profilephoto']);
        //
      }
    }
    //
    $result = pg_query($pgconn, "SELECT * from srgb.semstudent where LOWER(TRIM(studid))='" . strtolower(trim($lid)) . "' order by sy DESC,sem DESC limit 1");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $progcode = trim($row['studmajor']);
      }
    }
    $result = pg_query($pgconn, "SELECT * from srgb.program where LOWER(TRIM(progcode))='" . strtolower(trim($progcode)) . "' ");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $progname = trim($row['progdesc']);
        $deptcode = trim($row['progdept']);
      }
    }
  }
  //
  //
  $result = pg_query($pgconn, "SELECT * from srgb.department where LOWER(TRIM(deptcode))='" . strtolower(trim($deptcode)) . "'");
  if ($result) {
    while ($row = pg_fetch_array($result)) {
      $deptname = trim($row['deptname']);
      $deptcoll = trim($row['deptcoll']);
    }
  }
  $result = pg_query($pgconn, "SELECT * from srgb.college where LOWER(TRIM(collcode))='" . strtolower(trim($deptcoll)) . "'");
  if ($result) {
    while ($row = pg_fetch_array($result)) {
      $collname = trim($row['collname']);
    }
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

  <title>Profile</title>

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


                  <?php
                    echo $up_error;
                    echo $up_res;
                  ?>


                  <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px;">
                    <div class="card shadow mb-4 bg-gradient-1">
                      <!-- Card Header - Dropdown -->
                      <div class="">
                        
                      </div>
                      <!-- Card Body -->
                      <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                        <div align="left">


                        </div>

                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>
                        <br/>

                      </div>
                    </div>
                  </div>

                </div>

              </div>

              <div class="col-sm-12 div-profile-holder-1" style="padding-left: 4px;padding-right: 4px; border-top-left-radius: 0px; border-top-right-radius: 0px;">

                <div align="center">


                  <!-- Area Chart -->
                  <div class="col-sm-8" style="padding-left: 4px;padding-right: 4px; border-top-left-radius: 0px; border-top-right-radius: 0px;">
                    <div class="card shadow mb-4">
                      <!-- Card Header - Dropdown -->
                      <div class="">
                        
                      </div>
                      <!-- Card Body -->
                      <div class="card-body" style="padding-left: 4px;padding-right: 4px; border-top-left-radius: 0px; border-top-right-radius: 0px;">

                        <div align="left">
                          
                          
                          <div class="row">
                          <div class="col-sm-12">
                          <?php
                            if(trim($prof_photo) == "") {
                              $prof_photo = "img/dssc_logo.png";
                            }
                            //
                            echo '<img class="img-profile-image-1" src="' . trim($prof_photo) . '">';
                            //
                          ?>
                          </div>

                          <div class="col-sm-12">
                          <div class="profile-text-1">
                            <?php 
                            echo $dname . "<br/>";
                            //
                            if(strtolower(trim($lt))==strtolower(trim("employee"))) {
                              echo "<div class='profile-text-2'>" . $deptname . "</div>";
                              echo "<div class='profile-text-2'>" . $collname . "</div>";
                            }
                            //
                            if(strtolower(trim($lt))==strtolower(trim("student"))) {
                              echo "<div class='profile-text-2 text-transform-upper'>" . $progname . "</div>";
                              echo "<div class='profile-text-2 text-transform-upper'>" . $deptname . "</div>";
                              echo "<div class='profile-text-2 text-transform-upper'>" . $collname . "</div>";
                            }
                            //
                            $ef1 = "";
                            if(strtolower(trim($lt))==strtolower(trim("student"))) {
                              $ef1 = '
                                    <div class="form-group">
                                      Birthdate:<br/>
                                      <table>
                                        <tr>
                                          <td>
                                            Month:<br/>
                                            <input type="number" class="form-control form-control-user font-size-g-2" id="bd_month" name="bd_month" placeholder="Month" value="' . $bdate_month . '" >
                                          </td>
                                          <td>
                                            Day:<br/>
                                            <input type="number" class="form-control form-control-user font-size-g-2" id="bd_day" name="bd_day" placeholder="Day" value="' . $bdate_day . '" >
                                          </td>
                                          <td>
                                            Year:<br/>
                                            <input type="number" class="form-control form-control-user font-size-g-2" id="bd_year" name="bd_year" placeholder="Year" value="' . $bdate_year . '" >
                                          </td>
                                        </tr>
                                      </table>
                                    </div>
                              ';
                            }
                            $ef = "";
                            if( ( strtolower(trim($lid)) == strtolower(trim($log_userid)) && strtolower(trim($lt)) == strtolower(trim($log_user_type)) ) || ( $log_user_role_isadmin > 0 ) ) {
                              $ef = '
                              <a href="#" data-toggle="modal" data-target="#modalContactEdit"><i class="fas fa-edit fa-sm fa-fw mr-2"></i></a>
                              <!-- Modal -->
                              <div class="modal fade" id="modalContactEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header modal-header-1 bg-3 color-white-1">
                                      <h5 class="modal-title modal-header-text-1" id="">Update Contact</h5>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span class="color-white-1" aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                      
                                      <div align="left">

                                        <div class="form-group">
                                          <br/>
                                          Profile Picture:
                                          <input class="form-control form-control-user font-size-g-2" type="file" name="fphoto" id="fphoto">
                                        </div>
                                        <br/>

                                        <div class="form-group">
                                          Contact #:
                                          <input type="text" class="form-control form-control-user font-size-g-2" id="contactno" name="contactno" placeholder="Contact #" value="' . $contact_no . '" >
                                        </div>

                                        <div class="form-group">
                                          E-mail:
                                          <input type="text" class="form-control form-control-user font-size-g-2" id="email" name="email" placeholder="E-mail" value="' . $contact_email . '" >
                                        </div>

                                        ' . $ef1 . '

                                      </div>

                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary font-size-g-1" data-dismiss="modal">Close</button>
                                      <input type="submit" class="btn btn-primary bg-3 font-size-g-1" name="btnsave" value="Save Changes" />
                                    </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                              ';
                            }
                            //
                            $istat = "";
                            if(strtolower(trim($lid)) == strtolower(trim($log_userid))) {
                              $istat = '
                                  <div class="profile-text-3">
                                  <i class="fas fa-id-card fa-sm fa-fw mr-2"></i> ID: 
                                  <div class="profile-text-4"><br/><br/>
                                    ' . strtoupper(trim($log_userid)) . '
                                    <br/>

                                    <a href="#" data-toggle="modal" data-target="#modalEditPassword">Change Password</a>
                                    <!-- Modal -->
                                    <div class="modal fade" id="modalEditPassword" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header modal-header-1 bg-3 color-white-1">
                                            <h5 class="modal-title modal-header-text-1" id="">Update Password</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span class="color-white-1" aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <form method="post" enctype="multipart/form-data">
                                          <div class="modal-body">
                                            
                                            <div align="left">

                                              <div class="form-group">
                                                Current Password:
                                                <input type="password" class="form-control form-control-user font-size-g-2" id="cpass" name="cpass" placeholder="Current Password" value="" >
                                              </div>

                                              <br/>

                                              <div class="form-group">
                                                New Password:
                                                <input type="password" class="form-control form-control-user font-size-g-2" id="npass" name="npass" placeholder="New Password" value="" >
                                              </div>

                                              <div class="form-group">
                                                Repeat New Password:
                                                <input type="password" class="form-control form-control-user font-size-g-2" id="npass2" name="npass2" placeholder="Repeat New Password" value="" >
                                              </div>

                                            </div>

                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary font-size-g-1" data-dismiss="modal">Close</button>
                                            <input type="submit" class="btn btn-primary bg-3 font-size-g-1" name="btnsavepass" value="Save Changes" />
                                          </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>

                                    <br/><br/>
                                  </div>
                              ';
                            }
                            //
                            echo '
                                  <br/>
                                  ' . $istat . '
                                  <div class="profile-text-3">
                                  <i class="fas fa-calendar fa-sm fa-fw mr-2"></i> Birthdate: 
                                  <div class="profile-text-4"><br/><br/>
                                    ' . $bdate . '<br/><br/>
                                  </div>
                                  </div>
                                  <div class="profile-text-3">
                                  <i class="fas fa-address-book fa-sm fa-fw mr-2"></i> Contact: 
                                  <div align="right">' . $ef . '<br/></div>
                                  <div class="profile-text-4">
                                    ' . $contact_no . '<br/>
                                    ' . $contact_email . '
                                  </div>
                                  </div>
                            ';
                            ?>
                          </div>
                          </div>
                          </div>


                        </div>

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
