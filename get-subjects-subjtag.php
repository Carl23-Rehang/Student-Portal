<?php include "connect.php"; error_reporting(0);
    //
    $sy = trim($_GET['sy']);
    $sem = trim($_GET['sem']);
    //
	$res = "";
    //
    $qry = "SELECT subjcode,section from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' GROUP BY subjcode,section ORDER BY subjcode,section  ";
    $result = pg_query($pgconn, $qry);
    if ($result) {
      $n = 0;
      while ($row = pg_fetch_array($result)) {
        //
        $tsubj = trim($row['subjcode']);
        $tsec = trim($row['section']);
        $tcfacid = "";
        $tcfacname = "";
        $tcfaculty = "";
        //
        $tv = $tsubj . "•" . $tsec;
        $tv2 = $tsubj . " : " . $tsec;
        //
        //GET FACULTY
        $sqry = "SELECT facultyid from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($tsubj)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($tsec)) . "' GROUP BY facultyid ORDER BY facultyid  ";
        $sresult = pg_query($pgconn, $sqry);
        if ($sresult) {
            while ($srow = pg_fetch_array($sresult)) {
                $tcfacid = trim($srow['facultyid']);
            }
        }
        //GET FACULTY NAME
        if($tcfacid != "") {
            $sqry = "SELECT fullname from pis.employee where UPPER(TRIM(empid))='" . strtoupper(trim($tcfacid)) . "'  GROUP BY fullname ORDER BY fullname  ";
            $sresult = pg_query($pgconn, $sqry);
            if ($sresult) {
                while ($srow = pg_fetch_array($sresult)) {
                    $tcfacname = trim($srow['fullname']);
                }
            }
        }
        //
        $tcfaculty = "(" . $tcfacid . ") " . $tcfacname;
        if($tcfacid == "") {
            $tcfaculty = $tcfacname;
        }
        if($tcfacname == "") {
            $tcfaculty = "(" . $tcfacid . ")";
        }
        if(trim($tcfacid) == "" && trim($tcfacname) == "") {
            $tcfaculty = "";
        }
        //
        //
        if($tsubj != "" && $tsec != "") {
            $res = $res . '<a class="dropdown-content-a-1" onclick="' . "ItemListSelect_Subject('st_subject_h_items','" . $tv . "','st_subject','st_section','st_subject_name','" . $tcfaculty . "','st_subject_faculty_current');" . '">' . $tv2 . '</a>';
        }
        //
      }
    }
    //
    echo $res;
    //
?>