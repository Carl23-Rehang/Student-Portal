<?php
  //
  //
  if(trim($log_userid) == "") {
    exit();
  }
  // CHECK ENROL ALLOWED
  if($setting_enrollment_enabled <= 0) {
    exit();
  }
  // CHECK IF STUDENT
  if (strtolower(trim($log_user_type)) != strtolower(trim("student"))) {
    exit();
  }
  //
  //
  // CHECK ENROLL STATUS
  $tecn = 0;
  $sql = " SELECT * from tblconstudent WHERE (active='1') AND ( TRIM(UPPER(sy))=TRIM(UPPER('" . $setting_enrollment_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $setting_enrollment_sem . "')) AND TRIM(UPPER(studid))=TRIM(UPPER('" . $log_userid . "')) ) LIMIT 1 ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    $tecn++;
    //
  }
  if($tecn > 0) {
    $_SESSION[$appid . "c_user_enroll_stat"] = 1;
  }else{
    $_SESSION[$appid . "c_user_enroll_stat"] = 0;
  }
  //
?>