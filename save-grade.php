<?php include "connect.php"; //error_reporting(0);
    //
    $sy = trim($_GET['sy']);
    $sem = trim($_GET['sem']);
    $subj = trim($_GET['subject']);
    $section = trim($_GET['section']);
    $studid = trim($_GET['studid']);
    //
    $mt = trim($_GET['mt']);
    $ft = trim($_GET['ft']);
    $grade = trim($_GET['grade']);
    $remark = "";
    //
    $res = "";
	//
    //GET REMARK
    $qry = "SELECT * from srgb.validgrades where UPPER(TRIM(grade))='" . strtoupper(trim($grade)) . "'  ";
    $result = pg_query($pgconn, $qry);
    if ($result) {
      $n = 0;
      while ($row = pg_fetch_array($result)) {
        //
        $remark = trim($row['remarks']);
        //
      }
    }
    //GET REMARK END
    //
    // GET RECENT DATA
    $updated = 0;
    $ed_mt = "";
    $ed_ft = "";
    $ed_grade = "";
    $ed_remark = "";
    $ed_updated = 0;
    $qry = "SELECT * from srgb.registration_grade_updates2 where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' AND UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($subj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($section)) . "'  ";
    $result = pg_query($pgconn, $qry);
    if ($result) {
      while ($row = pg_fetch_array($result)) {
        //
        $ed_mt = trim($row['midterm']);
        $ed_ft = trim($row['finalterm']);
        $ed_grade = trim($row['grade']);
        $ed_remark = trim($row['remarks']);
        $ed_updated = trim($row['updated']);
        //
      }
    }
    $updated = $ed_updated;
    if(trim(strtolower($mt)) != trim(strtolower($ed_mt)) || trim(strtolower($ft)) != trim(strtolower($ed_ft)) ||
        trim(strtolower($grade)) != trim(strtolower($ed_grade)) || trim(strtolower($remark)) != trim(strtolower($ed_remark))) {
        if($ed_updated <= 0) {
            $updated = 1;
        }
    }
    //
    // SAVE
    $qry = " UPDATE srgb.registration SET midterm='" . $mt . "',finalterm='" . $ft . "',grade='" . $grade . "',remarks='" . $remark . "' 
             where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' AND UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($subj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($section)) . "'  ";
    $result = pg_query($pgconn, $qry);
    if($result) {
        $res = "success";
        //ADD TO LOGS
        $texist = 0;
        $sqry = " SELECT * FROM srgb.registration_grade_updates2 
                 where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' AND UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($subj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($section)) . "' 
                 LIMIT 1 
                ";
        $sresult = pg_query($pgconn, $sqry);
        //echo $sresult;
        if($sresult) {
            while ($srow = pg_fetch_array($sresult)) {
                $texist++;
            }
        }
        //IF NOT EXIST
        //ADD
        if($texist <= 0) {
            $qry2 = " INSERT INTO srgb.registration_grade_updates2 (sy,sem,studid,subjcode,section,midterm,finalterm,grade,remarks,updated) VALUES 
                     ('" . $sy . "','" . $sem . "','" . $studid . "','" . $subj . "','" . $section . "','" . $mt . "','" . $ft . "','" . $grade . "','" . $remark . "','" . $updated . "') 
                       ";
            $result2 = pg_query($pgconn, $qry2);
            //echo $result2;
        }else{
            $qry2 = " UPDATE srgb.registration_grade_updates2 SET midterm='" . $mt . "',finalterm='" . $ft . "',grade='" . $grade . "',remarks='" . $remark . "',updated='" . $updated . "' 
                     where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' AND UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($subj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($section)) . "'  ";
            $result2 = pg_query($pgconn, $qry2);
            //echo "exist";
        }
        //ELSE, UPDATE
        //
    }
    //
    //
    echo $res;
    //
?>