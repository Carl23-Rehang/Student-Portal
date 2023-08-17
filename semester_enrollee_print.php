<?php session_start(); include "connect.php"; //error_reporting(0);
	include "gvars.php";
	//
	include "access_check.php";
	//
	$isadmin = 0;
	$adminroleid = "";
	//GET ADMIN ROLE ID
	$tquery1 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(isadmin))='" . strtolower(trim("1")) . "' AND active='1' ";
	$tresult1 = mysqli_query($conn, $tquery1);
	if ($tresult1) {
		while ($trow1 = mysqli_fetch_array($tresult1)) {
			$adminroleid = trim($trow1['roletypeid']);
		}
	}
	//
	//GET ADMIN ROLE ID
	$tquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(userrole))='" . strtolower(trim($adminroleid)) . "' AND active='1' ";
	$tresult1 = mysqli_query($conn, $tquery1);
	if ($tresult1) {
		while ($trow1 = mysqli_fetch_array($tresult1)) {
			$isadmin = 1;
		}
	}
	//
	if ($isadmin <= 0) {
		//echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
		//exit();
	}
	//
	// CHECK IF ALLOWED TO VIEW THIS PAGE
	if($log_user_sem_enroll_admin <= 0 && $log_user_sem_enroll_view <= 0) {
		echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
		exit();
	}
	//
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Print</title>
	</head>

  	<link rel="shortcut icon" type="image" href="img/dssc_logo.png">

	<style type="text/css">

		body {
			font-family: arial;
			font-size: 0.8rem;
			color: #303030;
		}

		.print-tbl-1 {
			width: 100%;
		}

		.print-tbl-1-thead-1 {
			background: #163763;
			color: #fff;
			text-transform: uppercase;
			font-size: 0.7rem;
		}

		.print-tbl-1-thead-1-tr-1 {
			
		}

		.print-tbl-1-thead-1-col-1-1 {
			text-align: left;
			padding: 6px 4px;
			padding-left: 10px;
		}

		.print-tbl-1-row-1 {
			border: 0px;
		}

		.print-tbl-1-row-1-col-1 {
			text-align: left;
			padding: 8px 4px;
			padding-left: 10px;
			border-bottom: 1px solid #f0f0f0;
			font-size: 0.75rem;
		}

		.print-header-1, .print-header-space-1, .print-footer-1, .print-footer-space-1 {
			height: 100px;
			width: 100%;
		}
		.print-header-1, .print-header-space-1 {
			height: 110px;
		}
		.print-header-space-1 {
			height: 0px;
		}
		.print-header-1 {
			background-image: url('img/dssc_header_a4_landscape.png');
			background-size: cover;
		}
		.print-footer-1, .print-footer-space-1 {
			height: 50px;
		}
		.print-footer-1 {
			background-image: url('img/dssc_footer_a4_landscape.png');
			background-size: cover;
		}
		.print-header-1 {
			position: relative;
			top: 0;
		}
		.print-footer-1 {
			position: relative;
			bottom: 0;
		}

		@media print {

			.print-header-1, .print-header-space-1 {
				height: 110px;
			}


			.print-header-1 {
				position: fixed;
				top: 0;
			}
			.print-footer-1 {
				position: fixed;
				bottom: 0;
			}

		}

	</style>
	<body onload="window.print();">
		<div align="left">

			<div class="print-header-1"></div>

			<table width="100%">
				<thead>
					<tr>
						<td>
							<div class="print-header-space-1">&nbsp;</div>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<div>
								
								<table class="print-tbl-1" cellspacing="0" cellpadding="0">
									<thead class="print-tbl-1-thead-1" style="">
										<tr class="print-tbl-1-thead-1-tr-1">
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 30px; min-width: 30px; max-width: 30px;">#</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 80px; min-width: 80px; max-width: 80px;">Student ID</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1">Name</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 90px; min-width: 90px; max-width: 90px;">Year Level</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 130px; min-width: 130px; max-width: 130px;">Program / Course</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 80px; min-width: 80px; max-width: 80px;">Section</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 100px; min-width: 100px; max-width: 100px;">Contact #</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 130px; min-width: 130px; max-width: 130px;">Date Submitted</th>
										</tr>
									</thead>
									<tbody class="font-size-o-1">
										<?php
											// CREATE SEARCH QRY
											$qs_sysem = "";
											$qs = "";
											$qs2 = "";
											//
											// QRY SY SEM
											$qs_sysem = " ( (TRIM(LOWER(sy)) LIKE TRIM(LOWER('%" . $setting_enrollment_sy . "%'))) AND (TRIM(LOWER(sem)) LIKE TRIM(LOWER('%" . $setting_enrollment_sem . "%'))) ) ";
											// QRY SEARCH
											//
											$fsearch = trim($_GET['search']);
											$fyearlevel = trim($_GET['yearlevel']);
											$fprogram = trim($_GET['program']);
											$fsection = trim($_GET['section']);
											//
											if($fsearch != "") {
												$qv = " ( (TRIM(LOWER(lastname)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) OR (TRIM(LOWER(firstname)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) OR (TRIM(LOWER(middlename)) LIKE TRIM(LOWER('%" . $fsearch . "%'))) ) ";
												if(trim($qs) == "") {
													$qs = $qv;
												}else{
													$qs = $qs . " AND " . $qv;
												}
											}
											if($fyearlevel != "") {
												$qv = " ( (TRIM(LOWER(yearlevel))=TRIM(LOWER('" . $fyearlevel . "'))) ) ";
												if(trim($qs) == "") {
													$qs = $qv;
												}else{
													$qs = $qs . " AND " . $qv;
												}
											}
											if($fprogram != "") {
												$qv = " ( (TRIM(LOWER(courseprogram))=TRIM(LOWER('" . $fprogram . "'))) ) ";
												if(trim($qs) == "") {
													$qs = $qv;
												}else{
													$qs = $qs . " AND " . $qv;
												}
											}
											if($fsection != "") {
												$qv = " ( (TRIM(LOWER(section))=TRIM(LOWER('" . $fsection . "'))) ) ";
												if(trim($qs) == "") {
													$qs = $qv;
												}else{
													$qs = $qs . " AND " . $qv;
												}
											}
											// FINALIZE
											if(trim($qs) != "") {
												$qs = " AND " . $qs;
											}
											//
										?>
										<?php
											//
											//
											$tall = trim($_GET['all']);
											if(trim($tall) == "") {
												$tall = 0;
											}
											//
											//$ps_pagerows = trim($setting_default_request_rows);
											//
											$ps_pagerows = trim($_GET['rows']);
											//
											//$ps_pagerows = 1;
											if (trim($ps_pagerows) == "") {
												$ps_pagerows = 20;
											}
											$ps_page = trim($_GET['page']);
											if (trim($ps_page) == "" || $ps_page < 1) {
												$ps_page = 1;
											}
											//echo $ps_page;
											//
											if(trim($ps_pagerows)=="") {
												$ps_pagerows = 20;
											}
											if($ps_pagerows < 1) {
												$ps_pagerows = 1;
											}
											//
											if(trim($ps_page)=="") {
												$ps_page = 1;
											}
											if($ps_page < 1) {
												$ps_page = 1;
											}
											//
											$toffset = 0;
											$toffset = ($ps_page - 1) * $ps_pagerows;
											//
											$numrows = 0;
											$maxpage = 1;
											//
											$tn = 0;
											//
											$nquery = " SELECT COUNT(*) FROM tblconstudent 
														WHERE active='1' " . " AND " . $qs_sysem . " " . $qs . " 
											";
											$nresult = mysqli_query($conn_21, $nquery);
											if ($nresult) {
												$nrow = mysqli_fetch_array($nresult);
												$numrows = trim($nrow[0]);
											}
											$maxpage = ($numrows / $ps_pagerows);
											//echo "XXX-" . $numrows;
											//echo $maxpage;
											//echo $maxpage;
											//
											$wdecimal = -1;
											$wdecimal = strpos($maxpage, ".");
											if( trim($wdecimal) != "" && $wdecimal >= 0 ) {
												$ts = explode(".", $maxpage);
												$maxpage = $ts[0] + 1;
											}
											if( ($maxpage * $ps_pagerows) < $numrows ) {
												$maxpage = $maxpage + 1;
											}
											if($maxpage < 1) {
												if($numrows > 0) {
													$maxpage = 1;
												}
												$maxpage = 1;
											}
											//
											if ($ps_page > $maxpage) {
												$ps_page = $maxpage;
												$toffset = ($ps_page - 1) * $ps_pagerows;
											}
											if($toffset < 0) {
												$toffset = 0;
											}
											//echo $maxpage;
											//
											//
											//
											$tlimit = " LIMIT " . $toffset . "," . $ps_pagerows . " ";
											if($tall > 0) {
												$tlimit = "";
											}
											//
											//
											$query = "SELECT * FROM tblconstudent 
														WHERE active='1' " . " AND " . $qs_sysem . " " . $qs . " 
														ORDER BY datesubmitted ASC 
														" . $tlimit . " 
											";
											$result = mysqli_query($conn_21, $query);
											if ($result) {
												$n = 0;
												//
												$n = ($ps_page-1) * $ps_pagerows;
												if (trim($n) == "") {
												$n = 0;
												}
												//
												while ($row = mysqli_fetch_array($result)) {
													$n++;
													//
													$studid = trim($row['studid']);
													$ln = trim($row['lastname']);
													$fn = trim($row['firstname']);
													$mn = trim($row['middlename']);
													$ext = trim($row['extension']);
													$fullname = $ln . ", " . $fn;
													if($mn != "") {
													$fullname = $fullname . " " . $mn;
													}
													if($ext != "") {
													$fullname = $fullname . " " . $ext;
													}
													//
													$contactno = trim($row['contactno']);
													//
													$yearlevel = trim($row['yearlevel']);
													$course = trim($row['courseprogram']);
													$section = trim($row['section']);
													//
													$datesubmitted = trim($row['datesubmitted']);
													//
													//
													/*
													$query0 = "SELECT studlevel,studmajor FROM srgb.semstudent WHERE TRIM(LOWER(studid))='" . trim(strtolower($studid)) . "' ORDER BY sy DESC, sem DESC LIMIT 1 ";
													$result0 = pg_query($pgconn, $query0);
													if ($result0) {
													while ($row0 = pg_fetch_array($result0)) {
													$yearlevel = trim($row0['studlevel']);
													$course = trim($row0['studmajor']);
													}
													}
													*/
													//
													//
													//
													$fm = '
													';
													echo '
														<tr style="print-tbl-1-row-1">
															<td class="print-tbl-1-row-1-col-1">' . $n . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $studid . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $fullname . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $yearlevel . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $course . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $section . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $contactno . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $datesubmitted . '</td>
														</tr>
													';
												}
											}

										?>
									</tbody>
								</table>

							</div>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<div class="print-footer-space-1">&nbsp;</div>
						</td>
					</tr>
				</tfoot>
			</table>

			<div class="print-footer-1"></div>

		</div>
	</body>
</html>