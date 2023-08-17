<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  if(trim($log_userid) != "") {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  if($_POST['btnregister']) {
    $studid = trim($_POST['studentid']);
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];
    $id = "";
    //
    $errn = 0;
    $errmsg = "";
    if($studid == "") {
      $errn++;
      $errmsg = $errmsg . "Student ID required. ";
    }
    if($user == "") {
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
    //CHECK IF USERNAME IS IN DATABASE
    $tn = 0;
    $result = pg_query($pgconn, "SELECT * from srgb.student where LOWER(TRIM(studid))='" . strtolower(trim($studid)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $id = trim($row['studid']);
        $tn++;
      }
    }
    if($tn <= 0) {
      $errn++;
      $errmsg = $errmsg . "Invalid Student ID. ";
    }
    //CHECK IF STUDENT ID IS REGISTERED
    $tn = 0;
    $result = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(studid))='" . strtolower(trim($studid)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $tn++;
      }
    }
    if($tn > 0) {
      $errn++;
      $errmsg = $errmsg . "Student ID already registered. ";
    }
    //CHECK IF USERNAME IS REGISTERED
    $tn = 0;
    $result = pg_query($pgconn, "SELECT * from web.student where LOWER(TRIM(username))='" . strtolower(trim($user)) . "'");
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        $tn++;
      }
    }
    if($tn > 0) {
      $errn++;
      $errmsg = $errmsg . "Username already registered. ";
    }
    //
    if($errn <= 0) {
      $qry = "insert into web.student (studid,username,password) values ('" . $id . "','" . $user . "','" . $pass . "')";
      $result = pg_query($pgconn, $qry);
      $_SESSION[$appid . "msg_result"] = '
                    <div class="alert alert-success alert-dismissible font-size-alert-1">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Success!</strong> Account created successfully.
                    </div>
      ';
      $_POST['studentid'] = "";
      $_POST['username'] = "";
      $_POST['password'] = "";
      $_POST['password2'] = "";
    }else{
      $_SESSION[$appid . "msg_result"] = '
                    <div class="alert alert-danger alert-dismissible font-size-alert-1">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Error!</strong> ' . trim($errmsg) . '
                    </div>
      ';
    }
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

<body id="page-top"  class="bg-gradient-1">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-5 col-lg-5 col-md-5">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="img/dssc-logo.png" class="img-logo-1">
                    <br/>
                    <br/>
                    <h1 class="h4 text-gray-900 mb-4"><strong>Create Account</strong></h1>

                    <?php
                      echo $_SESSION[$appid . "msg_result"];
                      $_SESSION[$appid . "msg_result"] = "";
                    ?>

                  </div>
                  <form class="user" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="studentid" name="studentid" aria-describedby="studentid" placeholder="Student ID" required 
                        <?php
                          echo " value='" . $_POST['studentid'] . "' ";
                        ?>
                      >
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="username" name="username" aria-describedby="username" placeholder="Username" required 
                        <?php
                          echo " value='" . $_POST['username'] . "' ";
                        ?>
                      >
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password" required
                        <?php
                          echo " value='" . $_POST['password'] . "' ";
                        ?>
                      >
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Repeat Password" required
                        <?php
                          echo " value='" . $_POST['password2'] . "' ";
                        ?>
                      >
                    </div>
                    <input type="submit" class="btn btn-primary btn-user btn-block bg-2" name="btnregister" value="Register">
                  </form>

                  <hr>
                  <div class="text-center">
                    <a class="small" href="login.php">Login Here</a>
                  </div>

                  <hr>
                  <div class="text-center">
                    An Employee ? <a class="small" href="register.php">Create account here</a>.
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>


</body>

</html>
