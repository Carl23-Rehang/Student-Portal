<?php include "connect.php"; //error_reporting(0);
	$appid = "my-dssc-edu-ph_";
	//
	// UPDATE SY, SEM
	if(isset($_POST['btnupdatesysem'])) {
		$syf = trim($_POST['syf']);
		$syt = $syf + 1;
		$tsy = trim($syf) . "-" . trim($syt);
		$sem = trim($_POST['sem']);
		if(trim($syf) != "" && trim($sem) != "") {
			$_SESSION[$appid . "c_active_sy"] = $tsy;
			$_SESSION[$appid . "c_active_sem"] = $sem;
		}
	}
	
	//
	$log_userid = trim($_SESSION[$appid . "c_user_id"]);
	$log_user = trim($_SESSION[$appid . "c_user"]);
	$log_user_dn = trim($_SESSION[$appid . "c_user_dn"]);
	$log_user_photo = trim($_SESSION[$appid . "c_user_photo"]);
	$log_user_level = trim($_SESSION[$appid . "c_level"]);
	$log_user_type = trim($_SESSION[$appid . "c_type"]);
	$log_user_lrn = "";
	$log_user_studyearlevel = "";
	$log_user_program = "";
	$log_user_program_type = "";
	$log_user_department = "";
	$log_user_department_chairman = "";
	$log_user_college = "";
	$log_user_college_dean = "";
	//
	//
	$log_use_enroll_stat = trim($_SESSION[$appid . "c_user_enroll_stat"]);
	//
	//
	$log_user_roles = [];
	$log_user_sem_enroll_admin = 0;
	$log_user_sem_enroll_view = 0;
	$log_user_sem_enroll_editor = 0;
	//
	//
	//echo "" . $log_userid . " " . $log_user . " " . $log_user_type . " " . $log_user_level;
	//exit();
	//
	//
	// SAVE SETTINGS
	if(trim($log_userid)!="") {
		if($_POST['btnsettsave']) {
			//
			$tag = trim($_POST['sett_id']);
			//
			$value = trim($_POST['sett_value']);
			//
			$errn = 0;
			$errmsg = "";
			//
			if(trim($tag) == "") {
				$errn++;
				$errmsg = $errmsg . "Setting ID required. ";
			}
			//
			//
			//
			if ( trim(strtolower($tag)) != trim(strtolower("cus_active_sy")) && trim(strtolower($tag)) != trim(strtolower("cus_active_sem")) ) {
				// NORMAL SETTING
				// IF SETTING EXIST
				$ten = 0;
				$sresult = mysqli_query($conn, "SELECT * from tblsettings where LOWER(TRIM(name))='" . strtolower(trim($tag)) . "' LIMIT 1 ");
				if ($sresult) {
					while ($srow = mysqli_fetch_array($sresult)) {
						//
						$ten++;
						//
					}
				}
				//
				//
				if($errn <= 0) {
					//
					if ($ten > 0) {
						//
						// UPDATE
						$query = "UPDATE tblsettings SET value='" . $value . "' WHERE LOWER(TRIM(name))='" . strtolower(trim($tag)) . "' ";
						$result = mysqli_query($conn,$query);
					}else{
						// IF NOT EXIST, CREATE
						$query = "INSERT INTO tblsettings (name,value) VALUES ('" . $tag . "','" . $value . "') ";
						$result = mysqli_query($conn,$query);
					}
					//
				}
				//
			}else{
				// ACTIVE STUDENT SY, SEM
				//
				// UPDATE
				// RESET ALL CURRENT TO FALSE
				$query = "UPDATE srgb.semester SET current=false ";
				$result = pg_query($pgconn,$query);
				// UPDATE CURRENT
				//
				$value_sy = trim($_POST['sett_value_sy']);
				$value_sem = trim($_POST['sett_value_sem']);
				//
				$query = "UPDATE srgb.semester SET current=true WHERE TRIM(UPPER(sy))=TRIM(UPPER('" . $value_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $value_sem . "')) ";
				$result = pg_query($pgconn,$query);
				//
				//
			}
		}
		//
		//
	}
	//
	//
	//
	// UPDATE LRN
	if(trim(strtolower($log_user_type)) == trim(strtolower("student"))) {
		if(isset($_POST['btnlrnsave'])) {
			$lrn = trim($_POST['lrn']);
			//echo " XXX - " . $lrn;
			if($lrn != "") {
				$qry = " UPDATE srgb.student SET studlrn='" . $lrn . "' WHERE studid='" . $log_userid . "'  ";
				$result = pg_query($pgconn, $qry);
			}
		}
	}
	//GET LRN
	$tqry = "SELECT studlrn from srgb.student WHERE studid='" . $log_userid . "'  LIMIT 1  ";
	$tresult = pg_query($pgconn, $tqry);
	if ($tresult) {
		while ($trow = pg_fetch_array($tresult)) {
			$log_user_lrn = trim($trow['studlrn']);
		}
	}
	//GET STUDENT YEAR LEVEL
	$tqry = "SELECT studlevel,studmajor from srgb.semstudent WHERE studid='" . $log_userid . "' ORDER BY sy DESC, sem DESC  LIMIT 1  ";
	$tresult = pg_query($pgconn, $tqry);
	if ($tresult) {
		while ($trow = pg_fetch_array($tresult)) {
			$log_user_studyearlevel = trim($trow['studlevel']);
			$log_user_program = trim($trow['studmajor']);
		}
	}
	if(trim($log_user_studyearlevel) == "") {
		$log_user_studyearlevel = "0";
	}
	//GET COURSE / PROGRAM TYPE
	$tqry = "SELECT * from tblprogramclassification WHERE TRIM(LOWER(program))='" . trim(strtolower($log_user_program)) . "'   LIMIT 1  ";
	$tresult = mysqli_query($conn, $tqry);
	if ($tresult) {
		while ($trow = mysqli_fetch_array($tresult)) {
			$log_user_program_type = trim($trow['programtype']);
		}
	}
	//
	// GET PROGRAM INFO
	if (trim($log_user_program) != "") {
		$tqry = "SELECT * from srgb.program WHERE TRIM(UPPER(progcode))=TRIM(UPPER('" . $log_user_program . "'))  LIMIT 1  ";
		$tresult = pg_query($pgconn, $tqry);
		if ($tresult) {
			while ($trow = pg_fetch_array($tresult)) {
				$log_user_department = trim($trow['progdept']);
			}
		}
	}
	// GET DEPARTMENT INFO
	if (trim($log_user_department) != "") {
		$tqry = "SELECT * from srgb.department WHERE TRIM(UPPER(deptcode))=TRIM(UPPER('" . $log_user_department . "'))  LIMIT 1  ";
		$tresult = pg_query($pgconn, $tqry);
		if ($tresult) {
			while ($trow = pg_fetch_array($tresult)) {
				$log_user_college = trim($trow['deptcoll']);
				$log_user_department_chairman = trim($trow['deptchairman']);
			}
		}
	}
	// GET COLLEGE INFO
	if (trim($log_user_college) != "") {
		$tqry = "SELECT * from srgb.college WHERE TRIM(UPPER(collcode))=TRIM(UPPER('" . $log_user_college . "'))  LIMIT 1  ";
		$tresult = pg_query($pgconn, $tqry);
		if ($tresult) {
			while ($trow = pg_fetch_array($tresult)) {
				$log_user_college_dean = trim($trow['colldean']);
			}
		}
	}
	//
	//
	//
	$log_user_active_sy = trim($_SESSION[$appid . "c_active_sy"]);
	$log_user_active_sem = trim($_SESSION[$appid . "c_active_sem"]);
	//GET SEMESTER FROM DB
	if(trim($log_user_active_sy) == "" || trim($log_user_active_sem) == "") {
		$qry = "SELECT * from srgb.semester WHERE current=true ORDER BY sy DESC, sem DESC LIMIT 1  ";
		$result = pg_query($pgconn, $qry);
		if ($result) {
			$n = 0;
			while ($row = pg_fetch_array($result)) {
				//
				$tvsy = trim($row['sy']);
				$tvsem = trim($row['sem']);
				if($tvsy != "" && $tvsem != "") {
					$log_user_active_sy = $tvsy;
					$log_user_active_sem = $tvsem;
					//
					$_SESSION[$appid . "c_active_sy"] = $log_user_active_sy;
					$_SESSION[$appid . "c_active_sem"] = $log_user_active_sem;
					//
					//echo "XXXXXXX " . $log_user_active_sy . " " . $log_user_active_sem;
				}
				//
			}
		}
	}
	//GET SEMESTER FROM DB
	//
	if(trim($_SESSION[$appid . "c_active_sy"]) == "") {
		$_SESSION[$appid . "c_active_sy"] = $date_year . "-" . ($date_year + 1);
	}
	if(trim($_SESSION[$appid . "c_active_sem"]) == "") {
		$_SESSION[$appid . "c_active_sem"] = "1";
	}
	$log_user_active_sy = trim($_SESSION[$appid . "c_active_sy"]);
	$log_user_active_sem = trim($_SESSION[$appid . "c_active_sem"]);
	//GET SEMESTER FROM DB
	if(trim($log_user_active_sy) == "" || trim($log_user_active_sem) == "") {
		$qry = "SELECT * from srgb.semester WHERE current=true ORDER BY sy DESC, sem DESC LIMIT 1  ";
		$result = pg_query($pgconn, $qry);
		if ($result) {
			$n = 0;
			while ($row = pg_fetch_array($result)) {
				//
				$tvsy = trim($row['sy']);
				$tvsem = trim($row['sem']);
				if($tvsy != "" && $tvsem != "") {
					$log_user_active_sy = $tvsy;
					$log_user_active_sem = $tvsem;
					//
					$_SESSION[$appid . "c_active_sy"] = $log_user_active_sy;
					$_SESSION[$appid . "c_active_sem"] = $log_user_active_sem;
				}
				//
			}
		}
	}
	//GET SEMESTER FROM DB
	$gc_syf = "";
	$gc_syt = "";
	$gc_semt = "";
	$gc_sem_opt = "";
	$tesy = explode("-", $log_user_active_sy);
	$gc_syf = trim($tesy[0]);
	$gc_syt = trim($tesy[1]);
	//
	$tsel = "";
	if(strtolower(trim($log_user_active_sem)) == strtolower(trim("1"))) {
		$gc_semt = "1st Semester";
		$tsel = " selected ";
	}
	$gc_sem_opt = $gc_sem_opt . '<option value="1" ' . $tsel . ' >1st Semester</option>';
	$tsel = "";
	if(strtolower(trim($log_user_active_sem)) == strtolower(trim("2"))) {
		$gc_semt = "2nd Semester";
		$tsel = " selected ";
	}
	$gc_sem_opt = $gc_sem_opt . '<option value="2" ' . $tsel . ' >2nd Semester</option>';
	$tsel = "";
	if(strtolower(trim($log_user_active_sem)) == strtolower(trim("S"))) {
		$gc_semt = "Summer";
		$tsel = " selected ";
	}
	$gc_sem_opt = $gc_sem_opt . '<option value="S" ' . $tsel . ' >Summer</option>';
	//
	//
	if(trim($log_user_photo)=="") {
		$log_user_photo = "img/user-avatar.png";
	}
	//
	$log_user_role_id = "";
	$log_user_role_code = "";
	$log_user_role_name = "";
	$log_user_role_isadmin = 0;
	$zxquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(usertype))='" . strtolower(trim($log_user_type)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$log_user_role_id = trim($zxrow1['userrole']);
		}
	}
	$zxquery1 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(roletypeid))='" . strtolower(trim($log_user_role_id)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			//
			$trcode = trim($zxrow1['rolecode']);
			$trname = trim($zxrow1['rolename']);
			$trisadmin = trim($zxrow1['isadmin']);
			//
			if(strtolower(trim($log_user_role_code)) == "" || $trisadmin > 0) {
				$log_user_role_code = $trcode;
				$log_user_role_name = $trname;
				$log_user_role_isadmin = $trisadmin;
			}
			//
			//$log_user_role_code = trim($zxrow1['rolecode']);
			//$log_user_role_name = trim($zxrow1['rolename']);
			//$log_user_role_isadmin = trim($zxrow1['isadmin']);
		}
	}
	if(trim($log_user_role_isadmin) == "") {
		$log_user_role_isadmin = 0;
	}
	//
    $adminroleid = "";
    //GET ADMIN ROLE ID
    $tquery1 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(isadmin))='" . strtolower(trim("1")) . "' AND active='1' ";
    $tresult1 = mysqli_query($conn, $tquery1);
    if ($tresult1) {
      while ($trow1 = mysqli_fetch_array($tresult1)) {
        $adminroleid = trim($trow1['roletypeid']);
      }
    }
    //GET ADMIN ROLE ID
    $tquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(usertype))='" . strtolower(trim("employee")) . "' AND TRIM(LOWER(userrole))='" . strtolower(trim($adminroleid)) . "' AND active='1' ";
    $tresult1 = mysqli_query($conn, $tquery1);
    if ($tresult1) {
      while ($trow1 = mysqli_fetch_array($tresult1)) {
        $log_user_role_isadmin = 1;
      }
    }
    //
    //
	$_SESSION[$appid . "c_user_is_admin"] = $log_user_role_isadmin;
	//
	//
	//
	$log_user_active_student_sy = trim($_SESSION[$appid . "c_active_sy"]);
	$log_user_active_student_sem = trim($_SESSION[$appid . "c_active_sem"]);
	//
	$zxquery1 = "SELECT sy,sem FROM srgb.semester WHERE current=true ORDER BY sy,sem DESC LIMIT 1 ";
	$zxresult1 = pg_query($pgconn,$zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = pg_fetch_array($zxresult1)) {
			$log_user_active_student_sy = trim($zxrow1['sy']);
			$log_user_active_student_sem = trim($zxrow1['sem']);
		}
	}
	//
	//
	// USER ROLE : ENROLL
	$rolecode_enroll_sem_admin = "SEADM";
	$rolecode_enroll_sem_admin_id = "";
	$rolecode_enroll_sem_view = "SEVWR";
	$rolecode_enroll_sem_view_id = "";
	$rolecode_enroll_sem_editor = "SEEDT";
	$rolecode_enroll_sem_editor_id = "";
	// GET USER ROLES
	$zxquery1 = "SELECT a.userroleid,b.rolecode,b.rolename,b.isadmin,a.alevel FROM tbluserroles AS a 
				 LEFT JOIN tblroletype AS b ON b.roletypeid=a.userrole 
				 WHERE TRIM(LOWER(a.userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(a.usertype))='" . strtolower(trim($log_user_type)) . "' AND a.active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$trcode = trim($zxrow1['rolecode']);
			if(trim($trcode) != "") {
				$log_user_roles[count($log_user_roles)] = $trcode;
				//
				//echo $trcode;
				if(strtolower(trim($trcode)) == strtolower(trim($rolecode_enroll_sem_admin))) {
					$log_user_sem_enroll_admin = 1;
				}
				if(strtolower(trim($trcode)) == strtolower(trim($rolecode_enroll_sem_view))) {
					$log_user_sem_enroll_view = 1;
				}
				if(strtolower(trim($trcode)) == strtolower(trim($rolecode_enroll_sem_editor))) {
					$log_user_sem_enroll_editor = 1;
				}
				//
			}
		}
	}
	//
	//
	//
	//
	//GET SETTINGS : BASE PATH EMPLOYEE
	$setting_settname_profilephoto_basepath_employee = "basepath-profile-photo-employee";
	$setting_settdesc_profilephoto_basepath_employee = "";
	$setting_profilephoto_basepath_employee = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_profilephoto_basepath_employee)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_profilephoto_basepath_employee = trim($zxrow1['value']);
			$setting_settdesc_profilephoto_basepath_employee = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : BASE PATH STUDENT
	$setting_settname_profilephoto_basepath_student = "basepath-profile-photo-student";
	$setting_settdesc_profilephoto_basepath_student = "";
	$setting_profilephoto_basepath_student = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_profilephoto_basepath_student)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_profilephoto_basepath_student = trim($zxrow1['value']);
			$setting_settdesc_profilephoto_basepath_student = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : DEFAULT PASS AUTO / MANUAL
	$setting_settname_default_pass_is_auto = "my-default-pass-auto";
	$setting_settdesc_default_pass_is_auto = "";
	$setting_default_pass_is_auto = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_default_pass_is_auto)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_default_pass_is_auto = trim($zxrow1['value']);
			$setting_settdesc_default_pass_is_auto = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : DEFAULT PASS TYPE
	$setting_settname_default_pass_type = "my-default-pass-type";
	$setting_settdesc_default_pass_type = "";
	$setting_default_pass_type = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_default_pass_type)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_default_pass_type = trim($zxrow1['value']);
			$setting_settdesc_default_pass_type = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : DEFAULT PASS : MANUAL VALUE
	$setting_settname_default_pass_manual_value = "my-default-pass-manual-value";
	$setting_settdesc_default_pass_manual_value = "";
	$setting_default_pass_manual_value = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_default_pass_manual_value)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_default_pass_manual_value = $zxrow1['value'];
			$setting_settdesc_default_pass_manual_value = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : DEFAULT PASS 2 : MANUAL VALUE
	$setting_settname_default_pass_manual_value_2 = "";
	$setting_settdesc_default_pass_manual_value_2 = "";
	$setting_default_pass_manual_value_2 = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim("my-default-pass-manual-value-2")) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_default_pass_manual_value_2 = $zxrow1['value'];
			$setting_settdesc_default_pass_manual_value_2 = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : REQUEST ROWS PER PAGE
	$setting_settname_default_request_rows = "my-default-request-rows";
	$setting_settdesc_default_request_rows = "";
	$setting_default_request_rows = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_default_request_rows)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_default_request_rows = $zxrow1['value'];
			$setting_settdesc_default_request_rows = trim($zxrow1['details']);
		}
	}
	//GET SETTINGS : GRADING MODULE : ALLOWED ENCODING : SY
	$setting_settname_grade_encoding_allowed_sy = "grading-module-encoding-allowed-sy";
	$setting_grade_encoding_allowed_sy = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_grade_encoding_allowed_sy)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_grade_encoding_allowed_sy = $zxrow1['value'];
		}
	}
	//GET SETTINGS : GRADING MODULE : ALLOWED ENCODING : SEM
	$setting_settname_grade_encoding_allowed_sem = "grading-module-encoding-allowed-sem";
	$setting_grade_encoding_allowed_sem = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_grade_encoding_allowed_sem)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_grade_encoding_allowed_sem = $zxrow1['value'];
		}
	}
	//GET SETTINGS : GRADING MODULE : ENCODING ALLOWED
	$setting_settname_grade_encoding_allowed = "grading-module-encoding-allowed";
	$setting_grade_encoding_allowed = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_grade_encoding_allowed)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_grade_encoding_allowed = $zxrow1['value'];
		}
	}
	if(trim($setting_grade_encoding_allowed) == "") {
		$setting_grade_encoding_allowed = "0";
	}
	//GET SETTINGS : CLEARANCE : CLEARANCE ALLOWED
	$setting_settname_clearance_allowed = "clearance-enabled";
	$setting_settdesc_clearance_allowed = "";
	$setting_clearance_allowed = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_allowed)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_allowed = $zxrow1['value'];
			$setting_settdesc_clearance_allowed = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_allowed) == "") {
		$setting_clearance_allowed = "0";
	}
	//GET SETTINGS : CLEARANCE : STUDENT CREATE
	$setting_settname_clearance_allow_student_create = "clearance-enabled-student-create";
	$setting_settdesc_clearance_allow_student_create = "";
	$setting_clearance_allow_student_create = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_allow_student_create)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_allow_student_create = $zxrow1['value'];
			$setting_settdesc_clearance_allow_student_create = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_allow_student_create) == "") {
		$setting_clearance_allow_student_create = "0";
	}
	//GET SETTINGS : CLEARANCE : ADMIN CREATE STUDENT CLEARANCE
	$setting_settname_clearance_allow_admin_create = "clearance-enabled-admin-create";
	$setting_settdesc_clearance_allow_admin_create = "";
	$setting_clearance_allow_admin_create = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_allow_admin_create)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_allow_admin_create = $zxrow1['value'];
			$setting_settdesc_clearance_allow_admin_create = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_allow_admin_create) == "") {
		$setting_clearance_allow_admin_create = "0";
	}
	//GET SETTINGS : CLEARANCE : FIELD : DEPT CHAIR
	$setting_settname_clearance_field_deptchair = "clearance-field-deptchair";
	$setting_settdesc_clearance_field_deptchair = "";
	$setting_clearance_field_deptchair = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_field_deptchair)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_field_deptchair = $zxrow1['value'];
			$setting_settdesc_clearance_field_deptchair = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_field_deptchair) == "") {
		$setting_clearance_field_deptchair = "";
	}
	//GET SETTINGS : CLEARANCE : FIELD : COLLEGE DEAN
	$setting_settname_clearance_field_collegedean = "clearance-field-collegedean";
	$setting_settdesc_clearance_field_collegedean = "";
	$setting_clearance_field_collegedean = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_field_collegedean)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_field_collegedean = $zxrow1['value'];
			$setting_settdesc_clearance_field_collegedean = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_field_collegedean) == "") {
		$setting_clearance_field_collegedean = "";
	}
	//GET SETTINGS : CLEARANCE : RESTRICT : SY SEM : STUDENT
	$setting_settname_clearance_restrict_sysem_student = "clearance-restrict-sysem-student";
	$setting_settdesc_clearance_restrict_sysem_student = "";
	$setting_clearance_restrict_sysem_student = "1";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_restrict_sysem_student)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_restrict_sysem_student = $zxrow1['value'];
			$setting_settdesc_clearance_restrict_sysem_student = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_restrict_sysem_student) == "") {
		$setting_clearance_restrict_sysem_student = "1";
	}
	//GET SETTINGS : CLEARANCE : RESTRICT : SY SEM : STUDENT
	$setting_settname_clearance_restrict_sysem_employee = "clearance-restrict-sysem-employee";
	$setting_settdesc_clearance_restrict_sysem_employee = "";
	$setting_clearance_restrict_sysem_employee = "1";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_clearance_restrict_sysem_employee)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_clearance_restrict_sysem_employee = $zxrow1['value'];
			$setting_settdesc_clearance_restrict_sysem_employee = trim($zxrow1['details']);
		}
	}
	if(trim($setting_clearance_restrict_sysem_employee) == "") {
		$setting_clearance_restrict_sysem_employee = "1";
	}
	//
	//
	//GET SETTINGS : ENROLLMENT : ENABLE
	$setting_settname_enrollment_enabled = "enrollment-enabled";
	$setting_settdesc_enrollment_enabled = "";
	$setting_enrollment_enabled = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_enabled)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_enabled = $zxrow1['value'];
			$setting_settdesc_enrollment_enabled = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_enabled) == "") {
		$setting_enrollment_enabled = "0";
	}
	//
	//GET SETTINGS : ENROLLMENT : SY
	$setting_settname_enrollment_sy = "enrollment-sy";
	$setting_settdesc_enrollment_sy = "";
	$setting_enrollment_sy = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_sy)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_sy = $zxrow1['value'];
			$setting_settdesc_enrollment_sy = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_sy) == "") {
		$setting_enrollment_sy = "";
	}
	//
	//GET SETTINGS : ENROLLMENT : SEM
	$setting_settname_enrollment_sem = "enrollment-sem";
	$setting_settdesc_enrollment_sem = "";
	$setting_enrollment_sem = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_sem)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_sem = $zxrow1['value'];
			$setting_settdesc_enrollment_sem = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_sem) == "") {
		$setting_enrollment_sem = "";
	}
	//
	//GET SETTINGS : ENROLLMENT : TITLE
	$setting_settname_enrollment_title = "enrollment-form-title";
	$setting_settdesc_enrollment_title = "";
	$setting_enrollment_title = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_title)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_title = $zxrow1['value'];
			$setting_settdesc_enrollment_title = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_title) == "") {
		$setting_enrollment_title = "";
	}
	//
	//GET SETTINGS : ENROLLMENT : RESULT : MSG SUCCESS
	$setting_settname_enrollment_msg_success = "enrollment-result-msg-success";
	$setting_settdesc_enrollment_msg_success = "";
	$setting_enrollment_msg_success = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_msg_success)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_msg_success = $zxrow1['value'];
			$setting_settdesc_enrollment_msg_success = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_msg_success) == "") {
		$setting_enrollment_msg_success = "";
	}
	//
	//GET SETTINGS : ENROLLMENT : RESULT : MSG ERROR
	$setting_settname_enrollment_msg_error = "enrollment-result-msg-error";
	$setting_settdesc_enrollment_msg_error = "";
	$setting_enrollment_msg_error = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_msg_error)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_msg_error = $zxrow1['value'];
			$setting_settdesc_enrollment_msg_error = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_msg_error) == "") {
		$setting_enrollment_msg_error = "";
	}
	//
	//GET SETTINGS : ENROLLMENT : RESTRICT ENROLLEE : BY PREV SEM
	$setting_settname_enrollment_restrict_enrollee_by_prev_sem = "enrollment-restrict-enrollee-by-prev-sem";
	$setting_settdesc_enrollment_restrict_enrollee_by_prev_sem = "";
	$setting_enrollment_restrict_enrollee_by_prev_sem = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_enrollment_restrict_enrollee_by_prev_sem)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_enrollment_restrict_enrollee_by_prev_sem = $zxrow1['value'];
			$setting_settdesc_enrollment_restrict_enrollee_by_prev_sem = trim($zxrow1['details']);
		}
	}
	if(trim($setting_enrollment_restrict_enrollee_by_prev_sem) == "") {
		$setting_enrollment_restrict_enrollee_by_prev_sem = "0";
	}
    //
	//GET SETTINGS : ENROLLMENT : ID SETTINGS
    $preidtag = "0";
    $sql = " select * from tblsettings where name='preidtag'";
    $qry = mysqli_query($conn_21,$sql);
    while($dat=mysqli_fetch_array($qry)) {
        //
        $preidtag = trim($dat['value']);
        //
    }
    //
    $idchars = "0";
    $sql = " select * from tblsettings where name='idchars'";
    $qry = mysqli_query($conn_21,$sql);
    while($dat=mysqli_fetch_array($qry)) {
        //
        $idchars = trim($dat['value']);
        //
    }
    if (trim($idchars) == "") {
        $idchars = "5";
    }
    //
	//GET SETTINGS : ENROLLMENT : CHECK IF STUDENT IS REGISTERED IN THIS SEM
	$setting_enrollment_show = 0;
    if($setting_enrollment_enabled > 0) {
    	if(trim(strtolower($log_user_type)) == trim(strtolower("student"))) {
	    	if(trim(strtolower($setting_enrollment_restrict_enrollee_by_prev_sem)) == trim(strtolower("1"))) {
	    		//
	    		$tcn = 0;
	    		// GET ACTIVE SYSTEM SY, SEM
	    		$ta_sy = "";
	    		$ta_sem = "";
				$qry = "SELECT * from srgb.semester WHERE current=true ORDER BY sy DESC, sem DESC LIMIT 1  ";
				$result = pg_query($pgconn, $qry);
				if ($result) {
					while ($row = pg_fetch_array($result)) {
						//
						$tvsy = trim($row['sy']);
						$tvsem = trim($row['sem']);
						if($tvsy != "" && $tvsem != "") {
							$ta_sy = $tvsy;
							$ta_sem = $tvsem;
							//
						}
						//
					}
				}
				//echo $ta_sy . " " . $ta_sem;
	    		//
	    		if(trim($ta_sy) != "" && trim($ta_sem) != "") {
					$qry = "SELECT * from srgb.semstudent WHERE TRIM(UPPER(sy))=TRIM(UPPER('" . $ta_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $ta_sem . "')) AND TRIM(UPPER(studid))=TRIM(UPPER('" . $log_userid . "')) LIMIT 1  ";
					$result = pg_query($pgconn, $qry);
					if ($result) {
						while ($row = pg_fetch_array($result)) {
							//
							$tcn++;
							//
						}
					}
	    		}
	    		// CHECK
	    		if($tcn > 0) {
	    			//
	    			$setting_enrollment_show = 1;
	    			//
	    		}
	    		//
	    	}else{
	    		//
	    		$tcn = 0;
				$qry = "SELECT * from srgb.semstudent WHERE TRIM(UPPER(studid))=TRIM(UPPER('" . $log_userid . "')) LIMIT 1  ";
				$result = pg_query($pgconn, $qry);
				if ($result) {
					while ($row = pg_fetch_array($result)) {
						//
						$tcn++;
						//
					}
				}
				//
	    		// CHECK
	    		if($tcn > 0) {
	    			//
	    			$setting_enrollment_show = 1;
	    			//
	    		}
	    		//
	    	}
    	}
    }
    //
    //
	//GET SETTINGS : LRN : REQUIRED
	$setting_settname_lrn_required = "lrn-required";
	$setting_settdesc_lrn_required = "";
	$setting_lrn_required = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_lrn_required)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_lrn_required = $zxrow1['value'];
			$setting_settdesc_lrn_required = trim($zxrow1['details']);
		}
	}
	if(trim($setting_lrn_required) == "") {
		$setting_lrn_required = "0";
	}
	//GET SETTINGS : LRN : REQUIRED : YEAR LEVEL
	$setting_settname_lrn_required_yearlevel = "lrn-required-yearlevel";
	$setting_settdesc_lrn_required_yearlevel = "";
	$setting_lrn_required_yearlevel = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_lrn_required_yearlevel)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_lrn_required_yearlevel = $zxrow1['value'];
			$setting_settdesc_lrn_required_yearlevel = trim($zxrow1['details']);
		}
	}
	if(trim($setting_lrn_required_yearlevel) == "") {
		$setting_lrn_required_yearlevel = "0";
	}
	//GET SETTINGS : LRN : EXEMPTION PROGRAM
	$setting_settname_lrn_exemption_program_allowed = "lrn-allow-exempted-program";
	$setting_settdesc_lrn_exemption_program_allowed = "";
	$setting_lrn_exemption_program_allowed = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_lrn_exemption_program_allowed)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_lrn_exemption_program_allowed = $zxrow1['value'];
			$setting_settdesc_lrn_exemption_program_allowed = trim($zxrow1['details']);
		}
	}
	if(trim($setting_lrn_exemption_program_allowed) == "") {
		$setting_lrn_exemption_program_allowed = "0";
	}
	//
	//
	// LRN CHECK
	$setting_lrn_show_popup = "0";
	if($setting_lrn_required == 1 && trim($log_user_lrn) == "" && trim(strtolower($log_user_type)) == trim(strtolower("student")) &&
		($setting_lrn_required_yearlevel == 0 || ($setting_lrn_required_yearlevel == $log_user_studyearlevel) ) ) {
		$setting_lrn_show_popup = 1;
	}
	//LRN CHECK IN EXEMPTION
	if ($setting_lrn_show_popup > 0 && $setting_lrn_exemption_program_allowed == 1) {
		$tcn = 0;
		$zxquery1 = "SELECT * FROM tblexempted_lrn_program WHERE TRIM(LOWER(program))='" . strtolower(trim($log_user_program)) . "' AND active='1' ";
		$zxresult1 = mysqli_query($conn, $zxquery1);
		if ($zxresult1) {
			while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
				$tcn++;
			}
		}
		if($tcn > 0){
			$setting_lrn_show_popup = 0;
		}
	}
	//
	//
	//GET SETTINGS : LOGIN BLOCKED : 
	$setting_settname_login_blocked_check = "login-blocked-check";
	$setting_settdesc_login_blocked_check = "";
	$setting_login_blocked_check = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_login_blocked_check)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_login_blocked_check = $zxrow1['value'];
			$setting_settdesc_login_blocked_check = trim($zxrow1['details']);
		}
	}
	if(trim($setting_login_blocked_check) == "") {
		$setting_login_blocked_check = "0";
	}
	//
	//
	//GET SETTINGS : POPUP : ASSESSMENT : REQUIRED
	$setting_settname_popup_assessment_required = "popup-assessment-required";
	$setting_settdesc_popup_assessment_required = "";
	$setting_popup_assessment_required = "0";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_popup_assessment_required)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_popup_assessment_required = $zxrow1['value'];
			$setting_settdesc_popup_assessment_required = trim($zxrow1['details']);
		}
	}
	if(trim($setting_popup_assessment_required) == "") {
		$setting_popup_assessment_required = "0";
	}
	//GET SETTINGS : POPUP : ASSESSMENT : PROGRAM TYPES
	$setting_settname_popup_assessment_programtype = "popup-assessment-programtype";
	$setting_settdesc_popup_assessment_programtype = "";
	$setting_popup_assessment_programtype = "";
	$zxquery1 = "SELECT * FROM tblsettings WHERE TRIM(LOWER(name))='" . strtolower(trim($setting_settname_popup_assessment_programtype)) . "' AND active='1' ";
	$zxresult1 = mysqli_query($conn, $zxquery1);
	if ($zxresult1) {
		while ($zxrow1 = mysqli_fetch_array($zxresult1)) {
			$setting_popup_assessment_programtype = $zxrow1['value'];
			$setting_settdesc_popup_assessment_programtype = trim($zxrow1['details']);
		}
	}
	// POPUP : ASSESSMENT CHECK
	$setting_assessment_check_show_popup = "0";
	$setting_assessment_check_popup_msg = "";
	if($setting_popup_assessment_required == 1 && ( trim(strtolower($log_user_type)) != trim(strtolower("employee")) ) ) {
		if(trim($setting_popup_assessment_programtype) != "") {
			//SPECIFY PROGRAM TYPES
			$tcn = 0;
			$tmp_programtypes = [];
			$tmp_programtypes = explode(",", trim($setting_popup_assessment_programtype));
			for ($i = 0; $i < count($tmp_programtypes); $i++) {
				if(strtolower(trim($tmp_programtypes[$i])) == strtolower(trim($log_user_program_type))) {
					$tcn++;
					break;
				}
			}
			if($tcn > 0) {
				$setting_assessment_check_show_popup = "1";
			}
		}else{
			//EMPTY PROGRAM TYPES, APPLY TO ALL
			$setting_assessment_check_show_popup = "1";
		}
		//
		//
		if ($setting_assessment_check_show_popup == 1) {
			//
      		//
            $setting_assessment_check_popup_msg = "";
            //
            //
            $tmp_sy = "";
            $tmp_sem = "";
            //
			$qry = "SELECT * from srgb.semester WHERE current=true ORDER BY sy DESC, sem DESC LIMIT 1  ";
			$result = pg_query($pgconn, $qry);
			if ($result) {
				$n = 0;
				while ($row = pg_fetch_array($result)) {
					//
					$tmp_sy = trim($row['sy']);
					$tmp_sem = trim($row['sem']);
					//
				}
			}
			//
            $result = pg_query($pgconn, "SELECT sy,sem from srgb.ass_details where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' group by sy,sem order by sy ASC, sem ASC");
            //echo $log_userid;
            if ($result) {
              //echo "XXX";
              $sy = "";
              $tsy = "";
              $sem = "";
              $tsem = "";
              $show_ass = 1;
              $show_paid = 1;
              //
              $total_ass = 0;
              $total_paid = 0;
              //
              $n = 0;
              //
              while ($row = pg_fetch_array($result)) {
              	//
              	$n++;
                //
                $tsy = strtoupper(trim($row['sy']));
                $tsem = strtoupper(trim($row['sem']));
                //
                $semt = "";
                if(strtolower(trim($tsem)) == strtolower(trim("1"))) {
                  $semt = "1st Semester";
                }
                if(strtolower(trim($tsem)) == strtolower(trim("2"))) {
                  $semt = "2nd Semester";
                }
                if(strtolower(trim($tsem)) == strtolower(trim("S"))) {
                  $semt = "Summer";
                }
                //
                if(strtolower(trim($tsy)) == strtolower(trim($tmp_sy)) && strtolower(trim($tsem)) == strtolower(trim($tmp_sem))) {
                	$show_ass = 0;
                }
                //
                $tass = 0;
                $tpaid = 0;
                //
                //
                //GET TOTAL ASSESSMENT
                $result1 = pg_query($pgconn, "SELECT SUM(amt) from srgb.ass_details where UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' AND UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($tsem)) . "' ");
                //echo $log_userid;
                if ($result1) {
                  while ($row1 = pg_fetch_array($result1)) {
                    $tass = trim($row1[0]);
                  }
                }
                //GTE TOTAL PAID
                $qry1 = "
                          SELECT 
                            SUM(b.amt) 
                          FROM srgb.collection_header AS a 
                          LEFT JOIN srgb.collection_details AS b ON b.orno=a.orno 
                          WHERE UPPER(TRIM(sy))='" . strtoupper(trim($tsy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($tsem)) . "' 
                          AND UPPER(TRIM(studid))='" . strtoupper(trim($log_userid)) . "' 
                ";
                $result1 = pg_query($pgconn, $qry1);
                //echo $log_userid;
                if ($result1) {
                  while ($row1 = pg_fetch_array($result1)) {
                    $tpaid = trim($row1[0]);
                  }
                }
                //
                //
                $f_ass = number_format($tass, 2);
                $f_paid = number_format($tpaid, 2);
                //TOTAL
                if($show_ass == 1) {
                	$total_ass = $total_ass + $tass;
                }
                if($show_paid == 1) {
                	$total_paid = $total_paid + $tpaid;
                }
                //
                if($show_ass != 1) {
                	$f_ass = "";
                }
                if($show_paid != 1) {
                	$f_paid = "";
                }
                //
                $setting_assessment_check_popup_msg = $setting_assessment_check_popup_msg . '
                    <tr style="font-size: 0.7rem;">
                      <th scope="row">' . $n . '</th>
                      <td>' . $tsy . '</td>
                      <td>' . $tsem . '</td>
                      <td><span class="font-highlight-1">' . $f_ass . '</span></td>
                      <td><span class="font-highlight-1">' . $f_paid . '</span></td>
                    </tr>
                ';
                //
                //
                //
              } //END WHILE
              //
              //ADD TOTAL TO TABLE
	            $setting_assessment_check_popup_msg = $setting_assessment_check_popup_msg . '
	                <tr style="font-size: 0.7rem;">
	                  <th scope="row"></th>
	                  <td></td>
	                  <td><b>Total: </b></td>
	                  <td><span class="font-highlight-1"><b>' . number_format($total_ass, 2) . '</b></span></td>
	                  <td><span class="font-highlight-1"><b>' . number_format($total_paid, 2) . '</b></span></td>
	                </tr>
	            ';
              //
            } //END RESULT
            //
            if ((float)$total_paid >= (float)$total_ass) {
            	$setting_assessment_check_show_popup = 0;
            }
			//
		}
		//
		//
	}
	//
	//
	//CHECK IF HAS LOGGED USER AND IF BLOCKED
	if($setting_login_blocked_check > 0) {
		if(trim($log_userid) != "") {
			//echo "XXX " . $log_userid . " " . $log_user_type;
	        $bn = 0;
	        $block_msg = "";
	        //CHECK IF BLOCKED
	        $result = pg_query($pgconn, "SELECT * from web.users_blocked where LOWER(TRIM(userid))='" . strtolower(trim($log_userid)) . "' AND LOWER(TRIM(usertype))='" . strtolower(trim($log_user_type)) . "' AND active='1' ");
	        if ($result) {
	          while ($row = pg_fetch_array($result)) {
	            //
	            $bn++;
	            $block_msg = "Account suspended.<br/>Reason: " . $row['reason'];
	            break;
	            //
	          }
	        }
	        if($bn > 0) {
	        	$tmsg = '
                    <div class="alert alert-danger alert-dismissible font-size-alert-1">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong></strong> ' . $block_msg . '
                    </div>
	        	';
	        	$_SESSION[$appid . "c_g_msg"] = $tmsg;
	        	echo "blocked";
		      //echo '<meta http-equiv="refresh" content="0;URL=logout.php" />';
		      //exit();
	        }
		}
	}
	//
    //
?>