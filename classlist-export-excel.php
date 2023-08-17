<?php session_start(); include "connect.php"; error_reporting(0);
	include "gvars.php";
	//
	include "access_check.php";
	//
	//
	$td_cols = '"Name","Major","Year","Sex","Remarks"';
	$td_vals = "";
	//
	$tn = 0;
	//
	$fsy = trim($_GET['sy']);
	$fsem = trim($_GET['sem']);
	$fsubjcode = trim($_GET['subjcode']);
	$fsection = trim($_GET['section']);
	$fsems = "";
	if(strtolower(trim($fsem))==strtolower(trim("1"))) {
	$fsems = "1st Semester";
	}
	if(strtolower(trim($fsem))==strtolower(trim("2"))) {
	$fsems = "2nd Semester";
	}
	if(strtolower(trim($fsem))==strtolower(trim("s"))) {
	$fsems = "Summer";
	}
	//echo $fsy . "--" . $fsem . "--" . $fsubjcode . "--" . $fsection;
	//
	//echo $log_userid;
	//pg_set_client_encoding($pgconn, "ALT");
	$result = pg_query($pgconn, "SELECT A.sy,A.sem,A.studid,A.subjcode,A.section,A.remarks,B.studlastname,B.studfirstname,B.studmidname from srgb.registration A INNER JOIN srgb.student B ON A.studid=B.studid where  UPPER(TRIM(A.sy))='" . strtoupper(trim($fsy)) . "' and UPPER(TRIM(A.sem))='" . strtoupper(trim($fsem)) . "' and UPPER(TRIM(A.subjcode))='" . strtoupper(trim($fsubjcode)) . "' and UPPER(TRIM(A.section))='" . strtoupper(trim($fsection)) . "' order by B.studlastname ASC,B.studfirstname ASC,B.studmidname ASC ");
	//echo $log_userid;
	if ($result) {
		$sy = "";
		$tsy = "";
		$fd = "";
		$n = 0;
		//
		//
		while ($row = pg_fetch_array($result)) {
			$tn++;
			$n++;
			//echo $n;
			//
			$fd = "";
			//
			$subjdesc = "";
			$studcount = 0;
			//
			$studid = strtoupper(trim($row['studid']));
			$remarks = trim($row['remarks']);
			$studname = "";
			$gender = "";
			$studmajor = "";
			$studyear = "";
			$remarks = "";
			//
			//echo " -XXX- " . $studid;
			//GET NAME
			$result2 = pg_query($pgconn, "SELECT * from srgb.student where UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' limit 5");
			if ($result2) {
				while ($row2 = pg_fetch_array($result2)) {
					//$ln = utf8_encode(strtoupper(trim($row2['studfirstname'])));
					//$ln = utf8_decode(strtoupper(trim($row2['studfirstname'])));
					//$ln = utf8_decode($ln);

					$studname = strtoupper(trim($row2['studlastname'])) . ", " . strtoupper(trim($row2['studfirstname'])) . " " . strtoupper(trim($row2['studmidname']));
					$studname = mb_convert_encoding($studname, "UTF-8", "auto");
					$studname = str_replace("?", "Ñ", $studname);
					$gender = strtoupper(trim($row2['studgender']));
				}
			}
			//GET MAJOR YEAR
			$result2 = pg_query($pgconn, "SELECT * from srgb.semstudent where UPPER(TRIM(studid))='" . strtoupper(trim($studid)) . "' and UPPER(TRIM(sy))='" . strtoupper(trim($fsy)) . "' and UPPER(TRIM(sem))='" . strtoupper(trim($fsem)) . "' order by sy DESC,sem DESC limit 1");
			if ($result2) {
				while ($row2 = pg_fetch_array($result2)) {
					$studmajor = strtoupper(trim($row2['studmajor']));
					$studyear = strtoupper(trim($row2['studlevel']));
				}
			}
			//GET SUBJECT DESC
			$result2 = pg_query($pgconn, "SELECT * from srgb.subject where LOWER(TRIM(subjcode))='" . strtolower(trim($fsubjcode)) . "' limit 1");
			if ($result2) {
				while ($row2 = pg_fetch_array($result2)) {
					$subjdesc = trim($row2['subjdesc']);
				}
			}
			//
			//
			//
			$fd = '"' . $studid . '","' . $studname . '","' . $studmajor . '","' . $studyear . '","' . $gender . '","' . $remarks . '"';
			//
			if(trim($td_vals) == "") {
				$td_vals = $fd;
			}else{
				$td_vals = $td_vals . "\r\n" . $fd;
			}
			//
			//
		} //END WHILE
	}
	//
    //
    $fileName = "classlist-" . $fsubjcode . "-" . $fsection . ".csv";
    //
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Type: application/vnd.ms-excel");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    //
    echo $td_cols;
    echo "\r\n";
    echo $td_vals;
    //
	//
?>