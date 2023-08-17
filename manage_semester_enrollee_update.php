<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  //
  // CHECK IF HAS ID 
  $g_studid = trim($_GET['studid']);
  if($g_studid == "") {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  $activesy = trim($setting_enrollment_sy);
  $activesem = trim($setting_enrollment_sem);
  //
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
  // FOR STUDENT
  if($setting_enrollment_show <= 0) {
    //echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    //exit();
  }
  // CHECK IF EMPLOYEE
  if (strtolower(trim($log_user_type)) != strtolower(trim("employee"))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  // CHECK IF ENROLLMENT EDITOR
  if($log_user_sem_enroll_editor <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  //
  if(trim($log_userid) != "") {
    if($log_user_sem_enroll_editor > 0) {
      //
      if(isset($_POST['submit'])) {
        //
        //
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
        $fname = strtoupper(trim($ln)) . strtoupper(trim($fn));
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
        if(trim(basename($_FILES["disability_image"]["name"])) == "") {
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
        if(trim(basename($_FILES["soloparent_image"]["name"])) == "") {
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
        if(trim(basename($_FILES["vaccine_image"]["name"])) == "") {
          $uploadOk5 = 0;
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
        }
        //
        //
        if(trim($iagree) == "") {
            $errn++;
            $errmsg = $errmsg . "Privacy and policy confirmation required. ";
        }
        //
        //
        //
        $rok = 0;
        $rerrn = 0;
        //
        if($errn <= 0) {
          //
          // ADDITIONAL QUERY
          // FILE UPLOAD
          // DISABILITY
          $aq_disability = "";
          if(trim(strtolower($withdisability)) == trim(strtolower("1"))) {
            if(trim($file_disability_id) != "") {
              $aq_disability = " ,withdisability_file='" . $file_disability_id . "' ";
            }
          }
          // SOLO PARENT
          $aq_soloparent = "";
          if(trim(strtolower($soloparent)) == trim(strtolower("1"))) {
            if(trim($file_soloparent_id) != "") {
              $aq_soloparent = " ,soloparent_file='" . $file_soloparent_id . "' ";
            }
          }
          // VACCINE ID
          $aq_vaccine = "";
          if(trim(strtolower($vaccinated)) == trim(strtolower("1"))) {
            if(trim($file_vaccine_id) != "") {
              $aq_vaccine = " ,covid19_vaccine_file='" . $file_vaccine_id . "' ";
            }
          }
          //
          //
          $sql = " UPDATE tblconstudent SET 
                  lastname='" . $ln . "',firstname='" . $fn . "',middlename='" . $mn . "',extension='" . $ext . "',gender='" . $gender . "',sexualorientation='" . $sexorient . "',nhgender='" . $nhgender . "',email='" . $email . "',contactno='" . $acontactno . "',birthdate='" . $bdate . "',age='" . $age . "',civilstatus='" . $civilstatus . "',religion='" . $religion . "',houseno='" . $houseno . "',streetname='" . $streetname . "',barangay='" . $barangay . "',municipality='" . $municipality . "',province='" . $province . "',postalcode='" . $postalcode . "',withdisability='" . $withdisability . "',withdisabilitytype='" . $disabilitytype . "',soloparent='" . $soloparent . "',ec_name='" . $ec_name . "',ec_relationship='" . $ec_relationship . "',ec_contactno='" . $ec_contactno . "',covid19_vaccinated='" . $vaccinated . "',covid19_vaccine_dose='" . $vaccine_dose . "',covid19_vaccine_name='" . $vaccine_name . "' " . " " . $$aq_disability . " " . $$aq_soloparent . " " . $$aq_vaccine . " 
                  WHERE active='1' AND TRIM(UPPER(sy))=TRIM(UPPER('" . $activesy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $activesem . "')) AND TRIM(UPPER(studid))=TRIM(UPPER('" . $g_studid . "')) 
              ";
          $result = mysqli_query($conn_21,$sql);
          //
          if($result) {
            //
            $rok = 1;
            //
          }else{
            //
            //
          }
          //
        }else{
          $rok = 0;
          $rerrn = 1;
        }
        //
        //
      }
      //
    }
  }
  //
  //
  //
  // GET EXISTING DATA
  $ed_ln = "";
  $ed_fn = "";
  $ed_mn = "";
  $ed_ext = "";
  $ed_fullname = "";
  //ESMS
  $qry = "SELECT * from srgb.student WHERE TRIM(UPPER(studid))=TRIM(UPPER('" . $log_userid . "')) LIMIT 1  ";
  $result = pg_query($pgconn, $qry);
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $ed_ln = trim($row['studlastname']);
      $ed_fn = trim($row['studfirstname']);
      $ed_mn = trim($row['studmidname']);
      $ed_ext = trim($row['studsuffix']);
      //
    }
  }
  //
  //
  //
  // LOAD SAVED DATA
  //
  $ed_gender = "";
  $ed_sexorient = "";
  $ed_nhgender = "";
  $ed_email = "";
  $ed_contactno = "";
  //
  $ed_bdate = "";
  $ed_bdate_year = "";
  $ed_bdate_month = "";
  $ed_bdate_day = "";
  //
  $ed_age = "0";
  $ed_civilstatus = "";
  $ed_religion = "";
  //
  $ed_addr_province = "";
  $ed_addr_municipality = "";
  $ed_addr_barangay = "";
  $ed_addr_houseno = "";
  $ed_addr_streetname = "";
  $ed_addr_postalcode = "";
  //
  $ed_withdisability = "";
  $ed_withdisability_type = "";
  $ed_withdisability_file = "";
  //
  $ed_soloparent = "";
  $ed_soloparent_file = "";
  //
  $ed_ec_name = "";
  $ed_ec_relation = "";
  $ed_ec_contactno = "";
  //
  $ed_cov19_vaccine = "";
  $ed_cov19_vaccine_dose = "";
  $ed_cov19_vaccine_name = "";
  $ed_cov19_vaccine_file = "";
  //
  $ed_iagree = "1";
  //
  //
  $query1 = "SELECT * FROM tblconstudent WHERE TRIM(LOWER(studid))='" . strtolower(trim($g_studid)) . "' AND TRIM(LOWER(sy))='" . strtolower(trim($activesy)) . "' AND TRIM(LOWER(sem))='" . strtolower(trim($activesem)) . "' AND active='1'  ORDER BY datesubmitted DESC LIMIT 1 ";
  $result1 = mysqli_query($conn_21, $query1);
  if ($result1) {
    while ($row1 = mysqli_fetch_array($result1)) {
      //
      $ed_ln = trim($row1['lastname']);
      $ed_fn = trim($row1['firstname']);
      $ed_mn = trim($row1['middlename']);
      $ed_ext = trim($row1['extension']);
      //
      $ed_fullname = $ed_ln . ", " . $ed_fn;
      if($ed_mn != "") {
        $ed_fullname = $ed_fullname . " " . $ed_mn;
      }
      if($ed_ext != "") {
        $ed_fullname = $ed_fullname . " " . $ed_ext;
      }
      //
      $ed_gender = trim($row1['gender']);
      $ed_sexorient = trim($row1['sexualorientation']);
      $ed_nhgender = trim($row1['nhgender']);
      $ed_email = trim($row1['email']);
      $ed_contactno = trim($row1['contactno']);
      //
      $ed_bdate = trim($row1['birthdate']);
      if($ed_bdate != "") {
        $tv = explode("/", $ed_bdate);
        $ed_bdate_month = trim($tv[0]);
        $ed_bdate_day = trim($tv[1]);
        $ed_bdate_year = trim($tv[2]);
      }
      //
      $ed_age = trim($row1['age']);
      $ed_civilstatus = trim($row1['civilstatus']);
      $ed_religion = trim($row1['religion']);
      //
      $ed_addr_province = trim($row1['province']);
      $ed_addr_municipality = trim($row1['municipality']);
      $ed_addr_barangay = trim($row1['barangay']);
      $ed_addr_houseno = trim($row1['houseno']);
      $ed_addr_streetname = trim($row1['streetname']);
      $ed_addr_postalcode = trim($row1['postalcode']);
      //
      $ed_withdisability = trim($row1['withdisability']);
      $ed_withdisability_type = trim($row1['withdisabilitytype']);
      $ed_withdisability_file = trim($row1['withdisability_file']);
      //
      $ed_soloparent = trim($row1['soloparent']);
      $ed_soloparent_file = trim($row1['soloparent_file']);
      //
      $ed_ec_name = trim($row1['ec_name']);
      $ed_ec_relation = trim($row1['ec_relationship']);
      $ed_ec_contactno = trim($row1['ec_contactno']);
      //
      $ed_cov19_vaccine = trim($row1['covid19_vaccinated']);
      $ed_cov19_vaccine_dose = trim($row1['covid19_vaccine_dose']);
      $ed_cov19_vaccine_name = trim($row1['covid19_vaccine_name']);
      $ed_cov19_vaccine_file = trim($row1['covid19_vaccine_file']);
      //
      $ed_iagree = trim($row1['iagree']);
      //
      //echo " MONTH: " . $ed_bdate_month . " DAY: " . $ed_bdate_day . " YEAR: " . $ed_bdate_year;
      //
    }
  }
  //
  //
  //
  //
  //
  $gender = "";
  $opt_sexorient = "";
  $opt_nhgender = "";
  //LOAD GENDER
  $sql = " select * from tblgender order by gender ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['gender']) != "") {
      //
      $tv = trim($dat['gender']);
      $tsel = "";
      if(trim(strtolower($tv)) == trim(strtolower($ed_gender))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $tv . "' " . $tsel . " >" . $tv . "</option>";
      if(trim($gender) == "") {
        $gender = $cv;
      }else{
        $gender = $gender . $cv;
      }
    }
    //
  }
  //LOAD SEX ORIENT
  $sql = " select * from tblsexualorientation order by orientation ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['orientation']) != "") {
      //
      $tv = trim($dat['orientation']);
      $tsel = "";
      if(trim(strtolower($tv)) == trim(strtolower($ed_sexorient))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $tv . "' " . $tsel . " >" . $tv . "</option>";
      if(trim($opt_sexorient) == "") {
        $opt_sexorient = $cv;
      }else{
        $opt_sexorient = $opt_sexorient . $cv;
      }
    }
    //
  }
  //LOAD NH GENDER
  $opt_nhgender = $opt_nhgender . "<option value=''></option>";
  $sql = " select * from tblnhgender order by gender ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['gender']) != "") {
      //
      $tv = trim($dat['gender']);
      $tsel = "";
      if(trim(strtolower($tv)) == trim(strtolower($ed_nhgender))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $tv . "' " . $tsel . " >" . $tv . "</option>";
      if(trim($opt_nhgender) == "") {
        $opt_nhgender = $cv;
      }else{
        $opt_nhgender = $opt_nhgender . $cv;
      }
    }
    //
  }
  //
  //
  $incomerange = "";
  //LOAD INCOME
  $sql = " select * from tblincomerange order by id ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['incomemin']) != "" && trim($dat['incomemax']) != "") {
      $v = trim($dat['incomemin']) . " - " . trim($dat['incomemax']);
      //
      $tsel = "";
      //
      $cv = "<option value='" . $v . "' " . $tsel . " >" . $v . "</option>";
      if(trim($incomerange) == "") {
        $incomerange = $cv;
      }else{
        $incomerange = $incomerange . $cv;
      }
    }
    //
  }
  //
  //
  $opt_civilstatus = "";
  //LOAD CIVIL STATUS
  $sql = " select * from tblcivilstatus ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['civilstatus']) != "") {
      $v = trim($dat['civilstatus']);
      //
      $tsel = "";
      if(trim(strtolower($v)) == trim(strtolower($ed_civilstatus))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $v . "' " . $tsel . " >" . $v . "</option>";
      if(trim($opt_civilstatus) == "") {
        $opt_civilstatus = $cv;
      }else{
        $opt_civilstatus = $opt_civilstatus . $cv;
      }
    }
    //
  }
  //
  //
  $opt_studenttype = "";
  //LOAD STUDENT TYPE
  $sql = " select * from tblstudenttypes ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['studenttype']) != "") {
      $v = trim($dat['studenttype']);
      //
      $tsel = "";
      //
      $cv = "<option value='" . $v . "' " . $tsel . " >" . $v . "</option>";
      if(trim($opt_studenttype) == "") {
        $opt_studenttype = $cv;
      }else{
        $opt_studenttype = $opt_studenttype . $cv;
      }
    }
    //
  }
  //
  //
  $opt_employmentstatus = "";
  //LOAD STUDENT TYPE
  $sql = " select * from tblemploymentstatus ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['employmentstatus']) != "") {
      $v = trim($dat['employmentstatus']);
      $cv = "<option value='" . $v . "'>" . $v . "</option>";
      if(trim($opt_employmentstatus) == "") {
        $opt_employmentstatus = $cv;
      }else{
        $opt_employmentstatus = $opt_employmentstatus . $cv;
      }
    }
    //
  }
  //
  //
  $opt_vaccines = "";
  //LOAD VACCINES
  $sql = " SELECT * from tblvaccines where active='1' ORDER BY name ASC ";
  $qry = mysqli_query($conn_21,$sql);
  //ADD EMPTY
  $opt_vaccines = $opt_vaccines . "<option value=''></option>";
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['name']) != "") {
      $v = trim($dat['name']);
      //
      $tsel = "";
      if(trim(strtolower($v)) == trim(strtolower($ed_cov19_vaccine_name))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $v . "' " . $tsel . " >" . $v . "</option>";
      if(trim($opt_vaccines) == "") {
        $opt_vaccines = $cv;
      }else{
        $opt_vaccines = $opt_vaccines . $cv;
      }
    }
    //
  }
  //
  $opt_vaccine_dose = "";
  //LOAD VACCINE DOSE
  $sql = " SELECT * from tblvaccinedose ";
  $qry = mysqli_query($conn_21,$sql);
  //ADD EMPTY
  $opt_vaccine_dose = $opt_vaccine_dose . "<option value=''></option>";
  while($dat=mysqli_fetch_array($qry)) {
    //
    if(trim($dat['dose']) != "") {
      $v = trim($dat['dose']);
      //
      $tsel = "";
      if(trim(strtolower($v)) == trim(strtolower($ed_cov19_vaccine_dose))) {
        $tsel = " selected ";
      }
      //
      $cv = "<option value='" . $v . "' " . $tsel . " >" . $v . "</option>";
      if(trim($opt_vaccine_dose) == "") {
        $opt_vaccine_dose = $cv;
      }else{
        $opt_vaccine_dose = $opt_vaccine_dose . $cv;
      }
    }
    //
  }
  //
  //
  $opt_province = "";
  $opt_municipality = "";
  $opt_barangay = "";
  //LOAD ADDRESS : PROVINCE
  $sql = " SELECT province from tbladdress group by province order by province ASC ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    $tv = trim($dat['province']);
    if(trim($tv) != "") {
      //
      $tsel = "";
      if(trim(strtolower($tv)) == trim(strtolower($ed_addr_province))) {
        $tsel = " selected ";
      }
      //
      $opt_province = $opt_province . '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
    }
    //
  }
  //LOAD ADDRESS : MUNICIPALITY
  if($ed_addr_province != "") {
    $sql = " SELECT municipality from tbladdress WHERE TRIM(LOWER(province))=TRIM(UPPER('" . $ed_addr_province . "')) group by municipality order by municipality ASC ";
    $qry = mysqli_query($conn_21,$sql);
    while($dat=mysqli_fetch_array($qry)) {
      //
      $tv = trim($dat['municipality']);
      if(trim($tv) != "") {
        //
        $tsel = "";
        if(trim(strtolower($tv)) == trim(strtolower($ed_addr_municipality))) {
          $tsel = " selected ";
        }
        //
        $opt_municipality = $opt_municipality . '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
      }
      //
    }
  }
  //LOAD ADDRESS : BARANGAY
  if($ed_addr_province != "" && $ed_addr_municipality != "") {
    $sql = " SELECT brgy from tbladdress WHERE TRIM(LOWER(municipality))=TRIM(UPPER('" . $ed_addr_municipality . "')) group by brgy order by brgy ASC ";
    $qry = mysqli_query($conn_21,$sql);
    while($dat=mysqli_fetch_array($qry)) {
      //
      $tv = trim($dat['brgy']);
      if(trim($tv) != "") {
        //
        $tsel = "";
        if(trim(strtolower($tv)) == trim(strtolower($ed_addr_barangay))) {
          $tsel = " selected ";
        }
        //
        $opt_barangay = $opt_barangay . '<option value="' . $tv . '" ' . $tsel . ' >' . $tv . '</option>';
      }
      //
    }
  }
  //
  //
  // LOAD POLICY
  $v_policy = "";
  //LOAD ADDRESS
  $sql = " SELECT details from tblprivacypolicy WHERE active='1' ";
  $qry = mysqli_query($conn_21,$sql);
  while($dat=mysqli_fetch_array($qry)) {
    //
    $v_policy = $v_policy . ($dat['details']);
    //
  }
  if(trim($v_policy) != "") {
    $v_policy = nl2br(trim($v_policy));
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

  <title>Update Enrollee</title>

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

          <div style="margin-top: 4rem; margin-bottom: 10rem;" align="center">

            <div class="row align-items-center">

              <div class="col-xl-12 col-lg-12 col-md-12">
                <!--  -->
                <div class="col-xl-5 col-lg-7 col-md-8">

                  <div align="left">
                    <?php
                      //
                      if(isset($_POST['submit'])) {
                        if($errn <= 0) {
                          if($rok > 0) {
                            echo '
                                <div class="alert alert-success alert-dismissible c-alert-1-1 fade show" role="alert">
                                  <strong></strong> Student updated.
                                  <button type="button" class="close c-alert-close-1" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            ';
                          }else{
                            echo '
                                <div class="alert alert-danger alert-dismissible c-alert-1-1 fade show" role="alert">
                                  <strong></strong> Unable to save update(s).
                                  <button type="button" class="close c-alert-close-1" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                            ';
                          }
                        }else{
                          if(trim($errmsg) == "") {
                            $errmsg = "An error occured while saving.";
                          }
                          echo '
                              <div class="alert alert-danger alert-dismissible c-alert-1-1 fade show" role="alert">
                                <strong></strong> ' . trim($errmsg) . '
                                <button type="button" class="close c-alert-close-1" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                          ';
                        }
                      }
                      //
                    ?>
                  </div>

                  <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <div align="left">
                        <h6 class="m-0 font-weight-bold text-primary-1">Update Student: <?php echo "(" . $g_studid . ") " . $ed_fullname; ?></h6>
                      </div>
                    </div>
                      <br/>
                      <div class="div-description1" align="left">
                        <span class="span-description1 text-danger">* Required</span>
                      </div>
                    <!-- Card Body -->
                    <div class="card-body padding-lr1" style="overflow: hidden;">

                      <div align="left">
                        
                        <form class="user" method="post" name="enrollform" id="enrollform" enctype="multipart/form-data">


                          <div class="v3-input-title-1">A.  PERSONAL INFORMATION</div>

                          <div class="row">
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Last Name: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="ln" id="ln" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  required 
                                  <?php echo ' value="' . $ed_ln . '" '; ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">First Name: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="fn" id="fn" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  required 
                                  <?php echo ' value="' . $ed_fn . '" '; ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Middle Name: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="mn" id="mn" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" 
                                  <?php echo ' value="' . $ed_mn . '" '; ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Suffixes: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="extension" id="extension" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" 
                                  <?php echo ' value="' . $ed_ext . '" '; ?>
                                >
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Sex: <span class="text-danger">*</span></span>
                            <select class="v3-input-txt-1 input-text-value input-select-value" id="gender" name="gender" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              <?php echo $gender;
                              ?>
                            </select>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Sexual Orientation: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="sexorient" name="sexorient" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                                  <?php echo $opt_sexorient;
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Gender, if non-heterosexual: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="nhgender" name="nhgender" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                                  <?php echo "$opt_nhgender";
                                  ?>
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Birthdate: <span class="text-danger">*</span></span>
                          </div>
                          <table width="100%">
                            <tr>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Month: <span class="text-danger">*</span></span>
                                  <select class="v3-input-txt-1 input-text-value input-select-value" name="bdate_month" id="bdate_month" onchange="calculateAge()" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php
                                      for ($i=1; $i<=12; $i++) {
                                        $tsel = "";
                                        if(trim(strtolower($i)) == trim(strtolower($ed_bdate_month))) {
                                          $tsel = " selected ";
                                        }
                                        echo "<option value='" . $i . "' " . $tsel . " >" . $i . "</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                              </td>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Day: <span class="text-danger">*</span></span>
                                  <select class="v3-input-txt-1 input-text-value input-select-value" name="bdate_day" id="bdate_day" onchange="calculateAge()" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php
                                      for ($i=1; $i<=31; $i++) {
                                        $tsel = "";
                                        if(trim(strtolower($i)) == trim(strtolower($ed_bdate_day))) {
                                          $tsel = " selected ";
                                        }
                                        echo "<option value='" . $i . "' " . $tsel . " >" . $i . "</option>";
                                      }
                                    ?>
                                  </select>
                                </div>
                              </td>
                              <td>
                                <div class="form-group margin-top1">
                                  <span class="v3-input-lbl-1">Year: <span class="text-danger">*</span></span>
                                  <input type="number" class="v3-input-txt-1 input-text-value" name="bdate_year" id="bdate_year" onchange="calculateAge()" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required 
                                    <?php
                                      $ty = date("Y") - 15;
                                      if(trim($ed_bdate_year) != "") {
                                        $ty = $ed_bdate_year;
                                      }
                                      echo " value='". $ty . "' ";
                                    ?>
                                  >
                                </div>
                              </td>
                            </tr>
                          </table>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Age: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="age" id="age" <?php
                                  $tv = "0";
                                  if(trim($ed_age) != "") {
                                    $tv = $ed_age;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> placeholder="" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Religion: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="religion" id="religion" <?php
                                  $tv = "";
                                  if(trim($ed_religion) != "") {
                                    $tv = $ed_religion;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> placeholder="" >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Active Contact #: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="contactno" id="contactno" placeholder="" maxlength="11" oninput="maxLengthCheck(this)" <?php
                                  $tv = "";
                                  if(trim($ed_contactno) != "") {
                                    $tv = $ed_contactno;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">E-mail: <span class="text-danger">*</span></span>
                                <input type="email" class="v3-input-txt-1 input-text-value" name="email" id="email" placeholder="" <?php
                                  $tv = "";
                                  if(trim($ed_email) != "") {
                                    $tv = $ed_email;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Civil Status: <span class="text-danger"></span></span>
                            <select class="v3-input-txt-1 input-text-value input-select-value" id="civilstatus" name="civilstatus" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                              <?php echo $opt_civilstatus;
                              ?>
                            </select>
                          </div>

                          <div class="form-group margin-top1" style="margin-top: 4px;">
                            <span class="v3-input-lbl-1">Present Address: <span class="text-danger">*</span></span>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">House No.: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="houseno" id="houseno" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  <?php
                                  $tv = "";
                                  if(trim($ed_addr_houseno) != "") {
                                    $tv = $ed_addr_houseno;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?>
                                >
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Street Name: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="streetname" id="streetname" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  <?php
                                  $tv = "";
                                  if(trim($ed_addr_streetname) != "") {
                                    $tv = $ed_addr_streetname;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?>
                                >
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Province: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="province" name="province" placeholder="" onchange="updateMunicipality('province','municipality'); checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php echo $opt_province; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Municipality: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="municipality" name="municipality" placeholder="" onchange="updateBarangay('municipality','barangay'); checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php echo $opt_municipality; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Barangay: <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="barangay" name="barangay" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" required>
                                    <?php echo $opt_barangay; ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Postal Code: <span class="text-danger"></span></span>
                            <input type="text" class="v3-input-txt-1 input-text-value" name="postalcode" id="postalcode" placeholder="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  <?php
                                  $tv = "";
                                  if(trim($ed_addr_postalcode) != "") {
                                    $tv = $ed_addr_postalcode;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?>
                            >
                          </div>


                          <div class="form-group">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary" style="width: 16px; height: 16px; margin-top: 6px;" id="withdisability" name="withdisability" value="1" placeholder="" <?php
                                      $tsel = "";
                                      if(trim($ed_withdisability) != "") {
                                        if(trim(strtolower($ed_withdisability)) == trim(strtolower("1"))) {
                                          $tsel = " checked ";
                                        }
                                      }
                                      echo " " . $tsel . " ";
                                    ?> >
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.9rem;">I belong to person's with disability.<span class="text-danger"></span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">If yes, please specify disability: <span class="text-danger"></span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="disabilitytype" id="disabilitytype" placeholder="If yes, please specify disability" <?php
                                  $tv = "";
                                  if(trim($ed_withdisability_type) != "") {
                                    $tv = $ed_withdisability_type;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" >
                              </div>
                            </div>
                            <div class="col-sm-6" style="">
                              <div class="form-group margin-top1">
                                <span class="v3-input-lbl-1">Disability I.D. (Image): <span class="text-danger"></span></span>
                                <br/>
                                <input type="file" class="input-text-value" name="disability_image" id="disability_image" onchange="preview_image(event,'disability_image_preview')" accept="image/*" placeholder="">
                                <br/>
                                <div align="left">
                                  <img class="image-preview2" style="" id="disability_image_preview">
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="form-group margin-top1">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary" style="width: 16px; height: 16px; margin-top: 6px;" id="soloparent" name="soloparent" value="1" placeholder="" <?php
                                      $tsel = "";
                                      if(trim($ed_soloparent) != "") {
                                        if(trim(strtolower($ed_soloparent)) == trim(strtolower("1"))) {
                                          $tsel = " checked ";
                                        }
                                      }
                                      echo " " . $tsel . " ";
                                    ?> >
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.9rem;">Solo Parent<span class="text-danger"></span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">Solo Parent I.D. (Image): <span class="text-danger"></span></span>
                            <br/>
                            <input type="file" class="input-text-value" name="soloparent_image" id="soloparent_image" onchange="preview_image(event,'soloparent_image_preview')" accept="image/*" placeholder="">
                            <br/>
                            <div align="left">
                              <img class="image-preview1" style="" id="soloparent_image_preview">
                            </div>
                          </div>


                          <br/>

                          <div class="v3-input-title-1">IN CASE OF EMERGENCY, CONTACT:</div>
                          
                          <div class="form-group">
                            <span class="v3-input-lbl-1">Name: <span class="text-danger">*</span></span>
                            <input type="text" class="v3-input-txt-1 input-text-value" name="ec_name" id="ec_name" placeholder="" <?php
                                  $tv = "";
                                  if(trim($ed_ec_name) != "") {
                                    $tv = $ed_ec_name;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                          </div>

                          <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Relationship: <span class="text-danger">*</span></span>
                                <input type="text" class="v3-input-txt-1 input-text-value" name="ec_relationship" id="ec_relationship" placeholder="" <?php
                                  $tv = "";
                                  if(trim($ed_ec_relation) != "") {
                                    $tv = $ed_ec_relation;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <span class="v3-input-lbl-1">Contact No.: <span class="text-danger">*</span></span>
                                <input type="number" class="v3-input-txt-1 input-text-value" name="ec_contactno" id="ec_contactno" placeholder="" <?php
                                  $tv = "";
                                  if(trim($ed_ec_contactno) != "") {
                                    $tv = $ed_ec_contactno;
                                  }
                                  echo ' value="' . $tv . '" ';
                                ?> onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required>
                              </div>
                            </div>
                          </div>

                          <br/>

                          <div class="v3-input-title-1">VACCINATION:</div>
                          
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">Vaccinated ? <span class="text-danger">*</span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccinated" name="vaccinated" placeholder="" value="" onchange="checkVaccineRequiredt3('vaccinated','vaccine_dose','vaccine_name','vaccine_image'); checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')" required >
                                  <?php
                                    //
                                    $tv = "0";
                                    $tsel = "";
                                    if(trim(strtolower($tv)) == trim(strtolower($ed_cov19_vaccine))) {
                                      $tsel = " selected ";
                                    }
                                    echo '<option value="' . $tv . '" ' . $tsel . ' >No</option>';
                                    //
                                    $tv = "1";
                                    $tsel = "";
                                    if(trim(strtolower($tv)) == trim(strtolower($ed_cov19_vaccine))) {
                                      $tsel = " selected ";
                                    }
                                    echo '<option value="' . $tv . '" ' . $tsel . ' >Yes</option>';
                                    //
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">If yes, vaccine dose: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccine_dose" name="vaccine_dose" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  >
                                  <?php echo $opt_vaccine_dose; ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group ">
                                <span class="v3-input-lbl-1">If yes, vaccine name: <span class="text-danger"></span></span>
                                <select class="v3-input-txt-1 input-text-value input-select-value" id="vaccine_name" name="vaccine_name" placeholder="" value="" onchange="checkEmptyRequiredInput2('enrollform','submit')" onkeyup="checkEmptyRequiredInput2('enrollform','submit')"  >
                                  <?php echo $opt_vaccines; ?>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="form-group margin-top1">
                            <span class="v3-input-lbl-1">If yes, Vaccination Proof (Image): <span class="text-danger"></span></span>
                            <br/>
                            <input type="file" class="input-text-value" name="vaccine_image" id="vaccine_image" onchange="preview_image(event,'vaccine_image_preview')" accept="image/*" placeholder="">
                            <br/>
                            <div align="left">
                              <img class="image-preview1" style="" id="vaccine_image_preview">
                            </div>
                          </div>


                          <br/>
                          <br/>
                          <br/>


                          <div class="form-group margin-top1">
                            <div class="form-checkbox-div1">
                              <table class="form-checkbox-div1">
                                <tr>
                                  <td class="form-checkbox-div1">
                                    <input type="checkbox" class="form-control-user input-text-value text-primary form-checkbox-box1" id="iagree" name="iagree" value="1" placeholder="" required  <?php
                                      $tsel = " checked ";
                                      if(trim($ed_iagree) != "") {
                                        if(trim(strtolower($ed_iagree)) == trim(strtolower("1"))) {
                                          $tsel = " checked ";
                                        }
                                      }
                                      echo " " . $tsel . " ";
                                    ?> >
                                  </td>
                                  <td class="">
                                    <div class="">
                                      <span class="label1 form-checkbox-text1" style="font-size: 0.8rem;"><?php echo $v_policy; ?> <span class="text-danger">*</span></span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                              
                            </div>
                          </div>



                          <br/>
                          <br/>


                          <input type="submit" class="btn btn-primary btn-user btn-block bg-2" name="submit" id="submit" value="Submit" >

                          <hr/>

                          <div align="center">
                            
                          </div>
                          



                        </form>

                        <br/>

                      </div>

                    </div>
                  </div>
                </div>

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

  <script>


    function checkVaccineRequired(srcval,target1,target2){
      try{
        var val = document.getElementById(srcval).value;
        if(val.toLowerCase().trim() == "yes".toLowerCase().trim() || val.toLowerCase().trim() == "1".toLowerCase().trim()) {
          try{
            document.getElementById(target1).required = true;
            document.getElementById(target1).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target2).required = true;
            document.getElementById(target2).setAttribute("required", "");
          }catch(err){}
        }else{
          try{
            document.getElementById(target1).required = false;
            document.getElementById(target1).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target2).required = false;
            document.getElementById(target2).removeAttribute("required");
          }catch(err){}
        }
      }catch(err){}
    }

    function checkVaccineRequiredt3(srcval,target1,target2,target3){
      try{
        var val = document.getElementById(srcval).value;
        if(val.toLowerCase().trim() == "yes".toLowerCase().trim() || val.toLowerCase().trim() == "1".toLowerCase().trim()) {
          try{
            document.getElementById(target1).required = true;
            document.getElementById(target1).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target2).required = true;
            document.getElementById(target2).setAttribute("required", "");
          }catch(err){}
          try{
            document.getElementById(target3).required = true;
            document.getElementById(target3).setAttribute("required", "");
          }catch(err){}
        }else{
          try{
            document.getElementById(target1).required = false;
            document.getElementById(target1).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target2).required = false;
            document.getElementById(target2).removeAttribute("required");
          }catch(err){}
          try{
            document.getElementById(target3).required = false;
            document.getElementById(target3).removeAttribute("required");
          }catch(err){}
        }
      }catch(err){}
    }


  </script>


</body>

</html>
