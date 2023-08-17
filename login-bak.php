<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  //echo "" . $_SESSION[$appid . "c_user_id"];
  //
  if(trim($log_userid) != "") {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  $user = trim($_POST['username']);
  //
  if($_POST['btnlogin'] && trim($user) != "") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $utype = trim($_POST['utype']);
    if(trim($utype)=="") {
        $utype = "student";
    }
    $logid = "";
    $fphoto = "";
    //
    $inmdb = 0;
    $inlogindb = 0;
    //
    $dftype = $setting_default_pass_type;
    if (trim($dftype) == "") {
      $dftype = "id";
    }
    //
    $mdb_id = "";
    $mdb_dpass = "";
    $mdb_dpass2 = "";
    $mdb_email = "";
    //
    //
    //
    // CHECK IF REG IN MAIN DATABASE
    //CHECK IN STUDENT
    $sresult = pg_query($pgconn, "SELECT * from srgb.student where LOWER(TRIM(studid))='" . strtolower(trim($user)) . "' ");
    if ($sresult) {
      while ($srow = pg_fetch_array($sresult)) {
        //$inmdb = trim($srow[0]);
        $inmdb++;
        $utype = "student";
        //
        $mdb_id = trim($srow['studid']);
        $mdb_email = trim($srow['studemail']);
        //
        if (strtolower(trim($dftype)) == strtolower(trim("id"))) {
          $mdb_dpass = trim($srow['studid']);
        }
        if (strtolower(trim($dftype)) == strtolower(trim("lastname"))) {
          $mdb_dpass = trim($srow['studlastname']);
        }
        if (strtolower(trim($dftype)) == strtolower(trim("firstname"))) {
          $mdb_dpass = trim($srow['studfirstname']);
        }
        if (strtolower(trim($dftype)) == strtolower(trim("birthdate"))) {
          $mdb_dpass = trim($srow['studbirthdate']);
        }
        //
      }
    }
    //IF NOT IN DATABASE, CHECK IN WEB.STUDENT
    if( $inmdb <= 0 ) {
      $sresult = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(studid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $inmdb++;
          $utype = "student";
          //
          $mdb_id = trim($srow['studid']);
          if(trim($mdb_email) == "") {
            $mdb_email = trim($srow['email']);
          }
          //GET ADDITIONAL DATA
          $fresult = pg_query($pgconn, "SELECT * from srgb.student where LOWER(TRIM(studid))='" . strtolower(trim($user)) . "' ");
          if ($fresult) {
            while ($frow = pg_fetch_array($fresult)) {
              //
              if (strtolower(trim($dftype)) == strtolower(trim("id"))) {
                $mdb_dpass = trim($frow['studid']);
              }
              if (strtolower(trim($dftype)) == strtolower(trim("lastname"))) {
                $mdb_dpass = trim($frow['studlastname']);
              }
              if (strtolower(trim($dftype)) == strtolower(trim("firstname"))) {
                $mdb_dpass = trim($frow['studfirstname']);
              }
              if (strtolower(trim($dftype)) == strtolower(trim("birthdate"))) {
                $mdb_dpass = trim($frow['studbirthdate']);
              }
              //
            }
          }
          //
        }
      }
    }
    //IF INMDB = BLANK = DEFAULT TO 0
    if (trim($inmdb) == "") {
      $inmdb = 0;
    }
    //IF NOT IN STUDENT, CHECK IN EMPLOYEE
    /*
    if( $inmdb <= 0 ) {
      //CHECK IN EMPLOYEE
      $sresult = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          //$inmdb = trim($srow[0]);
          $inmdb++;
          $utype = "employee";
          //
          $mdb_id = trim($srow['empid']);
          $mdb_email = trim($srow['email']);
          //
          if (strtolower(trim($dftype)) == strtolower(trim("id"))) {
            $mdb_dpass = trim($srow['empid']);
          }
          if (strtolower(trim($dftype)) == strtolower(trim("lastname"))) {
            $mdb_dpass = trim($srow['lastname']);
          }
          if (strtolower(trim($dftype)) == strtolower(trim("firstname"))) {
            $mdb_dpass = trim($srow['firstname']);
          }
          if (strtolower(trim($dftype)) == strtolower(trim("birthdate"))) {
            $mdb_dpass = trim($srow['birthdate']);
          }
          //
        }
      }
      //IF NOT IN DATABASE, CHECK IN WEB.EMPLOYEE
      if( $inmdb <= 0 ) {
        $sresult = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(empid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
        if ($sresult) {
          while ($srow = pg_fetch_array($sresult)) {
            //$inmdb = trim($srow[0]);
            $inmdb++;
            $utype = "employee";
            //
            $mdb_id = trim($srow['empid']);
            if(trim($mdb_email) == "") {
              $mdb_email = trim($srow['email']);
            }
            //GET ADDITIONAL DATA
            $fresult = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($user)) . "' ");
            if ($fresult) {
              while ($frow = pg_fetch_array($fresult)) {
                //
                if (strtolower(trim($dftype)) == strtolower(trim("id"))) {
                  $mdb_dpass = trim($frow['empid']);
                }
                if (strtolower(trim($dftype)) == strtolower(trim("lastname"))) {
                  $mdb_dpass = trim($frow['lastname']);
                }
                if (strtolower(trim($dftype)) == strtolower(trim("firstname"))) {
                  $mdb_dpass = trim($frow['firstname']);
                }
                if (strtolower(trim($dftype)) == strtolower(trim("birthdate"))) {
                  $mdb_dpass = trim($srow['birthdate']);
                }
                //
              }
            }
            //
          }
        }
      }
    }
      */
    //
    //echo $inmdb . " -- " . $utype;
    //
    if (trim($setting_default_pass_is_auto) == "") {
      $setting_default_pass_is_auto = 1;
    }
    if($setting_default_pass_is_auto <= 0) {
      $mdb_dpass = $setting_default_pass_manual_value;
    }
    //
    // CHECK IF REG IN LOGIN DATABASE
    if ( strtolower(trim($utype)) == strtolower(trim("student")) ) {
      $sresult = pg_query($pgconn, "SELECT COUNT(studid) from web.student where LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(studid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          $inlogindb = trim($srow[0]);
        }
      }
    }
    if ( strtolower(trim($utype)) == strtolower(trim("employee")) ) {
      $sresult = pg_query($pgconn, "SELECT COUNT(empid) from web.employee where LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(empid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($sresult) {
        while ($srow = pg_fetch_array($sresult)) {
          $inlogindb = trim($srow[0]);
        }
      }
    }
    if (trim($inlogindb) == "") {
      $inlogindb = 0;
    }
    //echo $inlogindb;
    //
    // CHECK AND ADD TO LOGIN DB IF NEEDED
    if ($inmdb > 0 && $inlogindb <= 0) {
      //SAVE
      //STUDENT
      if ( strtolower(trim($utype)) == strtolower(trim("student")) ) {
        $tfpass = $mdb_dpass;
        if(trim($tfpass) == "") {
          $tfpass = $setting_default_pass_manual_value;
        }
        $aqry = " INSERT INTO web.student (studid,username,password,passtype,email) VALUES ('" . $mdb_id . "','" . $mdb_id . "','" . $tfpass . "','" . strtolower(trim($dftype)) . "','" . $mdb_email . "') ";
        $aresult = pg_query($pgconn, $aqry);
        //echo "SS";
      }
      //EMPLOYEE
      if ( strtolower(trim($utype)) == strtolower(trim("employee")) ) {
        $tfpass = $mdb_dpass;
        if(trim($tfpass) == "") {
          $tfpass = $setting_default_pass_manual_value_2;
        }
        $aqry = " INSERT INTO web.employee (empid,username,password,passtype,email) VALUES ('" . $mdb_id . "','" . $mdb_id . "','" . $tfpass . "','" . strtolower(trim($dftype)) . "','" . $mdb_email . "') ";
        $aresult = pg_query($pgconn, $aqry);
        //echo "EE";
      }
      //
    }
    //
    //
    $errn = 0;
    $errmsg = "";
    //CHECK IF USERNAME IS REGISTERED
    $tn = 0;
    //
    $dblogid = "";
    //
    //STUDENT
    if(strtolower(trim($utype))==strtolower(trim("student"))) {
      $result = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(studid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($result) {
          //echo " T1 ";
        while ($row = pg_fetch_array($result)) {
          //
          $dblogid = trim($row['studid']);
          //
          $cpass = $row['password'];
          $cpass2 = $cpass;
          $cpasstype = $row['passtype'];
          //
          if (strtolower(trim($cpasstype)) == strtolower(trim("birthdate"))) {
            $cpass2 = str_replace("-","",$cpass);
          }
          //
          if($cpass == $pass || $cpass2 == $pass) {
            $user = $row['username'];
            $logid = $row['studid'];
            $fphoto = $row['profilephoto'];
            //echo $fphoto;
          }
          //
          $tn++;
          //
        }
      }
    }
    //EMPLOYEE
    if(strtolower(trim($utype))==strtolower(trim("employee"))) {
      $result = pg_query($pgconn, "SELECT * from web.employee where LOWER(TRIM(username))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(empid))='" . strtolower(trim($user)) . "' OR LOWER(TRIM(email))='" . strtolower(trim($user)) . "' ");
      if ($result) {
          //echo " T2 ";
        while ($row = pg_fetch_array($result)) {
          //
          $dblogid = trim($row['empid']);
          //
          $cpass = $row['password'];
          $cpass2 = $cpass;
          $cpasstype = $row['passtype'];
          //
          if (strtolower(trim($cpasstype)) == strtolower(trim("birthdate"))) {
            $cpass2 = str_replace("-","",$cpass);
          }
          //
          if($cpass == $pass || $cpass2 == $pass) {
            $user = $row['username'];
            $logid = $row['empid'];
            $fphoto = $row['profilephoto'];
            $tn++;
          }
          //
          //
        }
      }
    }
    //
    //
    //LOGIN BLOCK CHECK
    $block_msg = "";
    if($tn > 0) {
      //echo " UUUU " . $setting_login_blocked_check;
      if($setting_login_blocked_check > 0) {
        $bn = 0;
        //CHECK IF BLOCKED
        //echo " TTT " . $dblogid . " " . $utype;
        $result = pg_query($pgconn, "SELECT * from web.users_blocked where LOWER(TRIM(userid))='" . strtolower(trim($dblogid)) . "' AND LOWER(TRIM(usertype))='" . strtolower(trim($utype)) . "' AND active='1' ");
        if ($result) {
          while ($row = pg_fetch_array($result)) {
            //
            $bn++;
            $tn = 0;
            $block_msg = "Login access not allowed.<br/>Reason: " . $row['reason'];
            break;

            //
          }
        }
      }
    }
    //
    // BLOCK BLANK PASSWORD
    if(trim($user) == "" || trim($pass) == "") {
        $tn = 0;
    }
    //
    //
    if($tn > 0) {
      //exit();
        //echo " cccc - " . $dblogid;
      //
      $dname = "";
      //
      //STUDENT
      if(strtolower(trim($utype))==strtolower(trim("student"))) {
        $result = pg_query($pgconn, "SELECT * from srgb.student where LOWER(TRIM(studid))='" . strtolower(trim($dblogid)) . "'");
        if ($result) {
          while ($row = pg_fetch_array($result)) {
              $dname = $row['studfirstname'];
          }
        }
        // GET WEB DATA
        $result = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(studid))='" . strtolower(trim($dblogid)) . "'");
        if ($result) {
          while ($row = pg_fetch_array($result)) {
              $fphoto = $row['profilephoto'];
          }
        }
        //GET ACCESS LEVEL
        //echo " STUDENT ";
      }
      //FACULTY
      if(strtolower(trim($utype))==strtolower(trim("employee"))) {
        $result = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($dblogid)) . "'");
        if ($result) {
          while ($row = pg_fetch_array($result)) {
              $dname = $row['firstname'];
          }
        }
        // GET WEB DATA
        $result = pg_query($pgconn, "SELECT * from pis.employee where LOWER(TRIM(empid))='" . strtolower(trim($dblogid)) . "'");
        if ($result) {
          while ($row = pg_fetch_array($result)) {
              $fphoto = $row['profilephoto'];
          }
        }
        //echo " EMPLOYEE ";
      }
      //exit;
      //
      $_SESSION[$appid . "c_user_id"] = $dblogid;
      $_SESSION[$appid . "c_user"] = $user;
      $_SESSION[$appid . "c_user_dn"] = trim($dname);
      $_SESSION[$appid . "c_level"] = "1";
      $_SESSION[$appid . "c_type"] = $utype;
      //
      $_SESSION[$appid . "c_user_photo"] = trim($fphoto);
      //
      $_POST['username'] = "";
      $_POST['password'] = "";
      //
      //echo "" . $_SESSION[$appid . "c_user_id"];
      echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
      exit();
    }else{
      $tmsg = "Incorrect username or password.";
      if(trim($block_msg) != "") {
        $tmsg = $block_msg;
      }
      $resmsg = '
                    <div class="alert alert-danger alert-dismissible font-size-alert-1">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong></strong> ' . $tmsg . '
                    </div>
      ';
    }
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

  <title>Login</title>

  <?php include "header-imports.php"; ?>

</head>

<body id="page-top"  class="">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="img/dssc_logo.png" class="img-logo-1">
                    <br/>
                    <br/>
                    <h1 class="h4 text-gray-900 mb-4"><strong>My Student Portal</strong></h1>

                    <?php
                      if(trim($_SESSION[$appid . "c_g_msg"]) != "") {
                        echo $_SESSION[$appid . "c_g_msg"];
                        $_SESSION[$appid . "c_g_msg"] = "";
                      }
                      echo $resmsg;
                    ?>

                  </div>
                  <form class="user" method="post">
                    <div class="form-group">
                      <input type="text" class="c-input-3" id="username" name="username" aria-describedby="username" placeholder="Username / Student ID / DSSC E-mail" required  
                        <?php
                          echo " value='" . $_POST['username'] . "' ";
                        ?>
                      >
                    </div>
                    <div class="form-group">
                      <input type="password" class="c-input-3" id="password" name="password" placeholder="Password">
                    </div>
                    

                    <input type="submit" class="btn btn-primary c-input-f-1 btn-block bg-2" name="btnlogin" value="Login">
                  </form>

                  <hr>
                  <div class="text-center">

                    <a href="facultylogin.php">Faculty Login</a>

                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <?php include "footer-imports.php"; ?>

</body>

</html>
