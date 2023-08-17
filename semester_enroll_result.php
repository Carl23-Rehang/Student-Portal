<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  //
  if(trim($log_userid) == "") {
    echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    exit();
  }
  // CHECK ENROL ALLOWED
  if($setting_enrollment_enabled <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  if($setting_enrollment_show <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  // CHECK IF STUDENT
  if (strtolower(trim($log_user_type)) != strtolower(trim("student"))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  // CHECK ENROLL STATUS
  include_once "semester_enroll_check.php";
  $log_use_enroll_stat = trim($_SESSION[$appid . "c_user_enroll_stat"]);
  if($log_use_enroll_stat > 0) {
    echo '<meta http-equiv="refresh" content="0;URL=semester_enroll_status.php" />';
    exit();
  }
  //
  //
  //
  //
  $rok = -1;
  $rerrn = 0;
  //
  $r_note = "";
  //SAVE
  if($_POST['submit']) {
    //
    //
    $activesy = trim($setting_enrollment_sy);
    $activesem = trim($setting_enrollment_sem);
    //
    $tprevsy = "";
    $tprevsem = "";
    //
    //
    $yearlevel = "";
    $courseprogram = "";
    $section = "";
    // GET
    $query0 = "SELECT studlevel,studmajor FROM srgb.semstudent WHERE TRIM(LOWER(studid))='" . trim(strtolower($log_userid)) . "' ORDER BY sy DESC,sem DESC LIMIT 1 ";
    $result0 = pg_query($pgconn, $query0);
    if ($result0) {
        while ($row0 = pg_fetch_array($result0)) {
          $yearlevel = trim($row0['studlevel']);
          $courseprogram = trim($row0['studmajor']);
        }
    }
    // GET PREV SY, SEM
    $query0 = "SELECT sy,sem FROM srgb.registration WHERE TRIM(LOWER(studid))='" . trim(strtolower($log_userid)) . "' ORDER BY sy DESC,sem DESC LIMIT 1 ";
    $result0 = pg_query($pgconn, $query0);
    if ($result0) {
        while ($row0 = pg_fetch_array($result0)) {
            $tprevsy = trim($row0['sy']);
            $tprevsem = trim($row0['sem']);
            //
        }
    }
    // GET SECTION START ===
    $tsecs = [];
    $query0 = "SELECT section FROM srgb.registration WHERE TRIM(LOWER(studid))='" . trim(strtolower($log_userid)) . "' AND TRIM(LOWER(sy))='" . trim(strtolower($tprevsy)) . "' AND TRIM(LOWER(sem))='" . trim(strtolower($tprevsem)) . "' ORDER BY section ASC ";
    $result0 = pg_query($pgconn, $query0);
    if ($result0) {
        while ($row0 = pg_fetch_array($result0)) {
            $tv = trim($row0['section']);
            //
            $ten = 0;
            for($i=0; $i<count($tsecs); $i++) {
                $tec = $tsecs[$i][0];
                if(trim(strtolower($tec)) == trim(strtolower($tv))) {
                    //UPDATE COUNT
                    $tsecs[$i][1] += 1;
                    $ten++;
                }
            }
            // IF NOT IN LIST, ADD
            $tnewd = [];
            $tnewd[0] = $tv;
            $tnewd[1] = 1;
            $tsecs[count($tsecs)] = $tnewd;
            //
        }
    }
    // GET LARGEST SECTION COUNT
    $tsec_lcount = 0;
    $tsec_lval = "";
    for($i=0; $i<count($tsecs); $i++) {
        $tcv = $tsecs[$i][0];
        $tcn = $tsecs[$i][1];
        if($i == 0 || $tcn > $tsec_lcount) {
            $tsec_lval = $tcv;
            $tsec_lcount = $tcn;
        }
    }
    $section = $tsec_lval;
    // GET SECTION END ===
    //
    //
    $studid = trim($log_userid);
    //
    $ln = trim($_POST['ln']);
    $ln = str_replace("'", "", $ln);
    $ln = str_replace('"', "", $ln);
    //
    $fn = trim($_POST['fn']);
    $fn = str_replace("'", "", $fn);
    $fn = str_replace('"', "", $fn);
    //
    $mn = trim($_POST['mn']);
    $mn = str_replace("'", "", $mn);
    $mn = str_replace('"', "", $mn);
    //
    $ext = trim($_POST['extension']);
    $ext = str_replace("'", "", $ext);
    $ext = str_replace('"', "", $ext);
    //
    $gender = trim($_POST['gender']);
    $sexorient = trim($_POST['sexorient']);
    $nhgender = trim($_POST['nhgender']);
    //
    $email = trim($_POST['email']);
    $email = str_replace("'", "", $email);
    $email = str_replace('"', "", $email);
    //
    $acontactno = trim($_POST['contactno']);
    $bdate_month = trim($_POST['bdate_month']);
    $bdate_day = trim($_POST['bdate_day']);
    $bdate_year = trim($_POST['bdate_year']);
    $bdate = trim($bdate_month) . "/" . trim($bdate_day) . "/" . trim($bdate_year);
    $age = trim($_POST['age']);
    //
    $civilstatus = trim($_POST['civilstatus']);
    //
    $religion = trim($_POST['religion']);
    $religion = str_replace("'", "", $religion);
    $religion = str_replace('"', "", $religion);
    //
    $houseno = trim($_POST['houseno']);
    $houseno = str_replace("'", "", $houseno);
    $houseno = str_replace('"', "", $houseno);
    //
    $streetname = trim($_POST['streetname']);
    $streetname = str_replace("'", "", $streetname);
    $streetname = str_replace('"', "", $streetname);
    //
    $barangay = trim($_POST['barangay']);
    $barangay = str_replace("'", "", $barangay);
    $barangay = str_replace('"', "", $barangay);
    //
    $municipality = trim($_POST['municipality']);
    $municipality = str_replace("'", "", $municipality);
    $municipality = str_replace('"', "", $municipality);
    //
    $province = trim($_POST['province']);
    $province = str_replace("'", "", $province);
    $province = str_replace('"', "", $province);
    //
    $postalcode = trim($_POST['postalcode']);
    $postalcode = str_replace("'", "", $postalcode);
    $postalcode = str_replace('"', "", $postalcode);
    //
    //
    //
    $withdisability = trim($_POST['withdisability']);
    if(trim($withdisability) == "") {
        $withdisability = "0";
    }
    //
    $disabilitytype = trim($_POST['disabilitytype']);
    $disabilitytype = str_replace("'", "", $disabilitytype);
    $disabilitytype = str_replace('"', "", $disabilitytype);
    //
    //
    $soloparent = trim($_POST['soloparent']);
    if(trim($soloparent) == "") {
        $soloparent = "0";
    }
    //
    //
    $file_disability_id = trim($_POST['disability_image']);
    $file_soloparent_id = trim($_POST['soloparent_image']);
    //
    $uf_disability_id = "_DISABILITY_ID";
    $uf_soloparent_id = "_SOLOPARENT_ID";
    //
    //
    $fname = strtoupper(trim($ln)) . strtoupper(trim($fn)) . strtoupper(trim($mn)) . strtoupper(trim($ext));
    $dir = "uploads/preadm/";
    //
    //
    //
    $ec_name = trim($_POST['ec_name']);
    $ec_name = str_replace("'", "", $ec_name);
    $ec_name = str_replace('"', "", $ec_name);
    //
    $ec_relationship = trim($_POST['ec_relationship']);
    $ec_relationship = str_replace("'", "", $ec_relationship);
    $ec_relationship = str_replace('"', "", $ec_relationship);
    //
    $ec_contactno = trim($_POST['ec_contactno']);
    $ec_contactno = str_replace("'", "", $ec_contactno);
    $ec_contactno = str_replace('"', "", $ec_contactno);
    //
    //
    //
    $vaccinated = trim($_POST['vaccinated']);
    if(trim($vaccinated) == "") {
        $vaccinated = "0";
    }
    $vaccine_dose = trim($_POST['vaccine_dose']);
    $vaccine_name = trim($_POST['vaccine_name']);
    //
    $file_vaccine_id = "";
    $uf_vaccine_id = "_VACCINE";
    //
    //
    //
    //
    $iagree = trim($_POST['iagree']);
    //
    //
    // FILE UPLOAD START : DISABILITY ID =======
    //
    /* Location */
    $location3 = $dir . basename($_FILES["disability_image"]["name"]);
    $uploadOk3 = 1;
    $imageFileType3 = pathinfo($location3,PATHINFO_EXTENSION);
    //$file = $dir . $pren . "_" . $ln . $fn . "_SHSGRADE." . $imageFileType3;
    $file3 = $dir . $fname . $uf_disability_id . "." . $imageFileType3;
    /* Valid Extensions */
    $valid_extensions3 = array("jpg","jpeg","png","gif","bmp","pdf");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType3),$valid_extensions3) ) {
       $uploadOk3 = 0;
    }
    if($uploadOk3 == 0){
       //echo 0;
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['disability_image']['tmp_name'],$file3)){
          //echo 1;
            $file_disability_id = trim($file3);
       }else{
          //echo 0;
       }
    }
    // FILE UPLOAD END =======
    // FILE UPLOAD START : SOLO PARENT ID =======
    //
    /* Location */
    $location4 = $dir . basename($_FILES["soloparent_image"]["name"]);
    $uploadOk4 = 1;
    $imageFileType4 = pathinfo($location4,PATHINFO_EXTENSION);
    //$file = $dir . $pren . "_" . $ln . $fn . "_SHSGRADE." . $imageFileType4;
    $file4 = $dir . $fname . $uf_soloparent_id . "." . $imageFileType4;
    /* Valid Extensions */
    $valid_extensions4 = array("jpg","jpeg","png","gif","bmp","pdf");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType4),$valid_extensions4) ) {
       $uploadOk4 = 0;
    }
    if($uploadOk4 == 0){
       //echo 0;
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['soloparent_image']['tmp_name'],$file4)){
          //echo 1;
            $file_soloparent_id = trim($file4);
       }else{
          //echo 0;
       }
    }
    // FILE UPLOAD END =======
    // FILE UPLOAD START : VACCINE =======
    //
    $ce_err_msg_vaccine = "";
    /* Location */
    $location5 = $dir . basename($_FILES["vaccine_image"]["name"]);
    $uploadOk5 = 1;
    $imageFileType5 = pathinfo($location5,PATHINFO_EXTENSION);
    //$file = $dir . $pren . "_" . $ln . $fn . "_SHSGRADE." . $imageFileType5;
    $file5 = $dir . $fname . $uf_vaccine_id . "." . $imageFileType5;
    /* Valid Extensions */
    $valid_extensions5 = array("jpg","jpeg","png","gif","bmp","pdf","ico","apng","avif","svg","webp","tif","tiff","jpe","jif","jfif");
    /* Check file extension */
    if( !in_array(strtolower($imageFileType5),$valid_extensions5) ) {
       $uploadOk5 = 0;
       $ce_err_msg_vaccine = "Your vaccination proof file extension '" . $imageFileType5 . "' is not allowed.";
    }
    if($uploadOk5 == 0){
       //echo 0;
    }else{
       /* Upload file */
       if(move_uploaded_file($_FILES['vaccine_image']['tmp_name'],$file5)){
          //echo 1;
            $file_vaccine_id = trim($file5);
       }else{
          //echo 0;
       }
    }
    // FILE UPLOAD END =======
    //
    //
    //
    $errn = 0;
    $errmsg = "";
    //
    //CHECK'
    if(trim($ln) == "") {
        $errn++;
        $errmsg = $errmsg . "Lastname required. ";
    }
    if(trim($fn) == "") {
        $errn++;
        $errmsg = $errmsg . "Firstname required. ";
    }
    if(trim($gender) == "") {
        $errn++;
        $errmsg = $errmsg . "Gender required. ";
    }
    if(trim($email) == "") {
        $errn++;
        $errmsg = $errmsg . "E-mail required. ";
    }
    if(trim($acontactno) == "") {
        $errn++;
        $errmsg = $errmsg . "Active Contact # required. ";
    }
    if(trim($bdate) == "") {
        $errn++;
        $errmsg = $errmsg . "Birthdate required. ";
    }
    if(trim($municipality) == "") {
        $errn++;
        $errmsg = $errmsg . "Municipality required. ";
    }
    if(trim($province) == "") {
        $errn++;
        $errmsg = $errmsg . "Province required. ";
    }
    if(trim($ec_name) == "") {
        $errn++;
        $errmsg = $errmsg . "In case of Emergency Contact Name required. ";
    }
    if(trim($ec_relationship) == "") {
        $errn++;
        $errmsg = $errmsg . "In case of Emergency Contact Relationship required. ";
    }
    if(trim($ec_contactno) == "") {
        $errn++;
        $errmsg = $errmsg . "In case of Emergency Contact # required. ";
    }
    //
    //
    if(trim($vaccinated) == "") {
        $errn++;
        $errmsg = $errmsg . "Vaccination status required. ";
    }
    if(strtolower(trim($vaccinated)) == strtolower(trim("1"))) {
        if(trim($vaccine_dose) == "") {
            $errn++;
            $errmsg = $errmsg . "Vaccine dose required. ";
        }
        if(trim($vaccine_name) == "") {
            $errn++;
            $errmsg = $errmsg . "Vaccine name required. ";
        }
        if(trim($ce_err_msg_vaccine) == "") {
            if(trim($file_vaccine_id) == "") {
                //$errn++;
                //$errmsg = $errmsg . "Vaccine proof required. ";
            }
        }else{
            $errn++;
            $errmsg = $errmsg . $ce_err_msg_vaccine . " ";
        }
    }
    //
    //
    //
    if(trim($iagree) == "") {
        $errn++;
        $errmsg = $errmsg . "Privacy and policy confirmation required. ";
    }
    //
    //CHECK IF ENROLLED
    function checkEnrollee($sy, $sem, $dln, $dfn) {
        //
        include "connect.php";
        //
        $tn = 0;
        $sql = " select * from tblconstudent where TRIM(LOWER(sy))=TRIM(LOWER('" . $sy . "')) and TRIM(LOWER(sem))=TRIM(LOWER('" . $sem . "')) and TRIM(lastname)='$dln' and TRIM(firstname)='$dfn' ";
        $qry = mysqli_query($conn_21,$sql);
        while($dat=mysqli_fetch_array($qry)) {
            //
            $tn++;
            //
        }
        if($tn > 0) {
            return 1;
        }else{
            return 0;
        }
        //
    }
    //CHECK IF ENROLLED
    function checkEnrolleeAdmission($dln, $dfn) {
        //
        include "connect.php";
        //
        $tn = 0;
        $sql = " select * from tbleemasterlist where TRIM(lastname)='$dln' and TRIM(firstname)='$dfn' ";
        $qry = mysqli_query($conn_21,$sql);
        while($dat=mysqli_fetch_array($qry)) {
            //
            $tn++;
            //
        }
        if($tn > 0) {
            return 1;
        }else{
            return 0;
        }
        //
    }
    function isProgramOpen($sy, $sem, $program) {
        //
        include "connect.php";
        //
        $result = 0;
        if(trim($program) != "") {
            //
            $sql = " select * from tblprogram where TRIM(programtitle)='$program' order by programtitle ";
            $qry = mysqli_query($conn_21,$sql);
            while($dat=mysqli_fetch_array($qry)) {
                //
                $tpt = trim($dat['programtitle']);
                $tpn = trim($dat['programname']);
                $tplimit = trim($dat['studentLimit']);
                $tpcount = 0;
                //
                //
                $sql2 = " select * from tblconstudent where TRIM(LOWER(sy))=TRIM(LOWER('" . $sy . "')) and TRIM(LOWER(sem))=TRIM(LOWER('" . $sem . "')) and TRIM(courseprogram)='$tpt' ";
                $qry2 = mysqli_query($conn_21,$sql2);
                $rows2 = mysqli_num_rows($qry2);
                $tpcount = $rows2;
                //echo " $tplimit - $tpcount ";
                //
                //
                if($tpt != "" && $tpcount < $tplimit) {
                    $result = 1;
                }else{
                    $result = 0;
                }
                //
            }
        }
        return $result;
    }
    //
    if($ln != "" && $fn != "") {
        //if(checkEnrolleeAdmission($ln, $fn) == 0) {
            //$errn++;
            //$errmsg = $errmsg . "You are not qualified to submit enrollment form based on admission. ";
        //}
        //echo $activesy . " " . $activesem . " " . $ln . " " . $fn;
        if(checkEnrollee($activesy, $activesem, $ln, $fn) == 1) {
            $errn++;
            //
            // LOAD NOTES
            $ssql = " SELECT value from tblsettings WHERE TRIM(LOWER(name))=TRIM(LOWER('enrollment-result-msg-error')) ";
            $sqry = mysqli_query($conn,$ssql);
            while($sdat=mysqli_fetch_array($sqry)) {
                //
                $r_note = $r_note . ($sdat['value']);
                //
            }
            //
            //
            $errmsg = $errmsg . $r_note;
        }
    }
    //
    //CHECK PROGRAM STUDENT COUNT
    if($limitstudent == 1 && isProgramOpen($activesy, $activesem, $course) == 0) {
        //$errn++;
        //$errmsg = $errmsg . "The selected Course / Program is closed due to limited number of student slots. ";
    }
    //echo $errmsg;
    //
    if($errn <= 0) {
        //
        //
        // GENERATE ID START =====
        $gid = "";
        $lastidnum = "0";
        $fsql = " select * from tblids order by idnum DESC limit 1 ";
        $fqry = mysqli_query($conn_21,$fsql);
        while($fdat=mysqli_fetch_array($fqry)) {
            $lastidnum = trim($fdat['idnum']);
        }
        if(trim($lastidnum) == "") {
            $lastidnum = "0";
        }
        $lastidnum++;
        $zerocount = $idchars;
        $zerocount = ($idchars - strlen($lastidnum));
        $pgid = "";
        for ($i=1;$i<=$zerocount;$i++) {
            $pgid = $pgid . "0";
        }
        $pgid = $pgid . $lastidnum;
        $gid = $preidtag . $pgid;
        //echo $pgid . " <> " . $gid;
        $fsql2 = " insert into tblids (preidtag,genid,idnum,cid) values ('" . $preidtag . "','" . $pgid . "'," . $lastidnum . ",'" . $gid . "') ";
        $fqry2 = mysqli_query($conn_21,$fsql2);
        // GENERATE ID END =====
        //
        //
        $sql = " INSERT into tblconstudent 
                (sy,sem,admid,studid,lastname,firstname,middlename,extension,gender,sexualorientation,nhgender,email,contactno,birthdate,age,civilstatus,religion,barangay,municipality,province,houseno,streetname,postalcode,iagree,ec_name,ec_relationship,ec_contactno,withdisability,withdisabilitytype,withdisability_file,soloparent,soloparent_file,covid19_vaccinated,covid19_vaccine_dose,covid19_vaccine_name,covid19_vaccine_file,courseprogram,yearlevel,section) 
                values 
                ('" . $activesy . "','" . $activesem . "','" . $gid . "','" . $studid . "','" . $ln . "','" . $fn . "','" . $mn . "','" . $ext . "','" . $gender . "','" . $sexorient . "','" . $nhgender . "','" . $email ."','" . $acontactno . "','" . $bdate . "','" . $age . "','" . $civilstatus . "','" . $religion . "','" . $barangay . "','" . $municipality . "','" . $province . "','" . $houseno . "','" . $streetname . "','" . $postalcode . "','" . $iagree . "','" . $ec_name . "','" . $ec_relationship . "','" . $ec_contactno . "','" . $withdisability . "','" . $disabilitytype . "','" . $file_disability_id . "','" . $soloparent . "','" . $file_soloparent_id . "','" . $vaccinated . "','" . $vaccine_dose . "','" . $vaccine_name . "','" . $file_vaccine_id . "','" . $courseprogram . "','" . $yearlevel . "','" . $section . "' 
                 )
            ";
        $qry = mysqli_query($conn_21,$sql);
        //
        if($qry) {
            $rok = 1;
            //
            // LOAD NOTES
            $ssql = " SELECT value from tblsettings WHERE TRIM(LOWER(name))=TRIM(LOWER('enrollment-result-msg-success')) ";
            $sqry = mysqli_query($conn,$ssql);
            while($sdat=mysqli_fetch_array($sqry)) {
                //
                $r_note = $r_note . ($sdat['value']);
                //
            }
            //
        }
        //CHECK IF ADDED
        if(checkEnrollee($activesy, $activesem, $ln, $fn) == 1) {
            //echo "Your response has been validated and recorded. You are allowed to one registration only. ";
        }else{
            //echo "0";
        }
        //
    }else{
        //echo $errmsg;
        $rok = 0;
        $rerrn = 1;
    }
    //
    //
  }
  //
  //
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

  <title>Semester Enrollment Admission</title>

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
          <div class="container-fluid">

              <!-- Page Heading -->
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"></h1>
              </div>

              <!-- Content Row -->


          <div align="center">

            <div class="row align-items-center">

              <div class="col-sm-12">
                <!--  -->


                <?php



                    if ($rok >= 0) {

                        $td = "";

                        if ($rok == 1) {

                            $td = "
                                <table>
                                    <tr>
                                        <td style='padding-right: 12px;'>
                                            <b>Admission ID:</b>
                                        </td>
                                        <td class='result-val-1'>
                                            <b>" . $gid . "</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Name:</b>
                                        </td>
                                        <td>
                                            <b><span class='result-val-1' style='text-transform: capitalize;'>" . trim($ln) . ", " . trim($fn) . " " . trim($mn) . "</span></b>
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                                <br/>
                                <br/>
                                <div class='result-note-1'>" . $r_note . "</div>
                            ";

                        }
                        if ($rok == 0) {
                            $td = $errmsg;
                        }

                        $ts_class = "";
                        if($rerrn > 0) {
                            $ts_class = " text-danger ";
                        }

                        echo '
                            <div class="col-sm-6" style="margin-top: 2rem; margin-bottom: 16rem;">
                              <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                  <div>
                                    <h6 class="m-0 font-weight-bold text-primary-1">' . $setting_enrollment_title . '</h6>
                                  </div>
                                </div>
                                  <div class="div-description1" align="left">
                                    <span class="span-description1 text-danger"></span>
                                  </div>
                                <!-- Card Body -->
                                <div class="card-body padding-lr1" style="padding-top: 2rem; padding-bottom: 3rem;">

                                  <div class="result-note-1 ' . $ts_class . ' " style="font-size: 0.8rem;" align="left">
                                    
                                    ' . $td . '

                                    <br/>

                                  </div>

                                </div>
                              </div>
                            </div>
                        ';

                    }else{
                        echo '<div style="padding-top: 30rem;"></div>';
                    }

                ?>

              </div>

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
