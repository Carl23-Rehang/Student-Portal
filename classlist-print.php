<?php session_start(); include "connect.php"; error_reporting(0);
	include "gvars.php";
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
	//
	$fsubjdesc = "";
	$fsubjdesc2 = "";
	$finstructor_id = "";
	$finstructor_name = "";
	$fdays = "";
	$ftime = "";
	$froom = "";
	$fbldg = "";
	//
	// GET SEM SUBJECT DATA
	$qry = "SELECT * FROM srgb.semsubject WHERE TRIM(LOWER(sy))=TRIM(LOWER('" . $fsy . "')) AND TRIM(LOWER(sem))=TRIM(LOWER('" . $fsem . "')) AND TRIM(LOWER(subjcode))=TRIM(LOWER('" . $fsubjcode . "')) AND TRIM(LOWER(section))=TRIM(LOWER('" . $fsection . "')) LIMIT 1";
	$result = pg_query($pgconn,$qry);
	if($result) {
		while ($row = pg_fetch_array($result)) {
			//
			$finstructor_id = trim($row['facultyid']);
			$fdays = trim($row['days']);
			$ftime = trim($row['time']);
			$froom = trim($row['room']);
			$fbldg = trim($row['bldg']);
			//
		}
	}
	// GET SUBJECT DATA
	$qry = "SELECT * FROM srgb.subject WHERE TRIM(LOWER(subjcode))=TRIM(LOWER('" . $fsubjcode . "')) LIMIT 1";
	$result = pg_query($pgconn,$qry);
	if($result) {
		while ($row = pg_fetch_array($result)) {
			//
			$fsubjdesc = trim($row['subjdesc']);
			//
		}
	}
	// GET SUBJECT DATA
	if(trim($finstructor_id) != "") {
		$qry = "SELECT * FROM pis.employee WHERE TRIM(LOWER(empid))=TRIM(LOWER('" . $finstructor_id . "')) LIMIT 1";
		$result = pg_query($pgconn,$qry);
		if($result) {
			while ($row = pg_fetch_array($result)) {
				//
				$finstructor_name = trim($row['fullname']);
				//
			}
		}
	}
	//
	$fsubjdesc2 = $fsubjdesc;
	if(trim($fsubjdesc2) != "") {
		$fsubjdesc2 = " - " . $fsubjdesc2;
	}
	//
	$fdatetime = "" . $date_day . "/" . $date_month . "/" . $date_year . " " . $date_hour . ":" . $date_minute . ":" . $date_second;
	//
	//
	//
?>
<!DOCTYPE html>
<html>
<head>
	<title>Print</title>
	<link rel="shortcut icon" type="image" href="img/dssc_logo.png">
	<style type="text/css">
		
		.tbl-1 {
			width: 100%;
		}

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
			height: 70px;
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

		.ttl-1 {
			font-size: 0.75rem;
		}

		.ttl-2 {
			font-size: 0.75rem;
		}

		.bottom-lbl-1 {
			font-weight: normal;
			font-style: italic;
		}

	</style>
</head>
<body onload="window.print();">
	<div align="center">
		
		<div>
			
			<div class="print-header-1"></div>

			<table class="tbl-1">
				<thead>
					<tr>
						<th>
							<div class="print-header-space-1">&nbsp;</div>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>

							<div>

								<div style="margin-bottom: 14px;" align="center">
									<div class="ttl-1" style="margin-bottom: 4px;"><b>CLASS LIST</b></div>
									<div class="ttl-2" style="margin-bottom: 4px; padding-left: 2px;" align="left"><b>Subject: <?php echo $fsubjcode . $fsubjdesc2; ?></b></div>
									<div class="ttl-2" style="margin-bottom: 4px; padding: 0px;" align="left"><b>
										<table width="100%" style="padding: 0px;">
											<tr style="padding: 0px; margin: 0px;">
												<td style="padding: 0px; margin: 0px;">Section: <?php echo $fsection; ?></td>
												<td style="padding: 0px; margin: 0px;">Time: <?php echo $ftime; ?></td>
												<td style="padding: 0px; margin: 0px;">Days: <?php echo $fdays; ?></td>
												<td style="padding: 0px; margin: 0px;">Room: <?php echo $froom; ?></td>
												<td style="padding: 0px; margin: 0px;">Bldg: <?php echo $fbldg; ?></td>
											</tr>
										</table>
										
										</b></div>
									<div class="ttl-2" style="margin-bottom: 4px; padding-left: 4px;" align="left"><b>Instructor: <?php echo $finstructor_name; ?></b></div>
								</div>
								
								<table class="print-tbl-1" cellspacing="0" cellpadding="0">
									<thead class="print-tbl-1-thead-1" style="">
										<tr class="print-tbl-1-thead-1-tr-1">
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 30px; min-width: 30px; max-width: 30px;">#</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 80px; min-width: 80px; max-width: 80px;">Student ID</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1">Name</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 80px; min-width: 80px; max-width: 80px;">Major</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 50px; min-width: 50px; max-width: 50px;">Year</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 50px; min-width: 50px; max-width: 50px;">Sex</th>
											<th scope="col" class="print-tbl-1-thead-1-col-1-1" style="width: 90px; min-width: 90px; max-width: 90px;">Remarks</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												
								              <?php
								                $tn = 0;
								                //
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
								                    $link = "profile.php?t=student&id=" . $studid;
								                    $fd = $fd . '
														<tr style="print-tbl-1-row-1">
															<td class="print-tbl-1-row-1-col-1">' . $n . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $studid . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $studname . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $studmajor . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $studyear . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $gender . '</td>
															<td class="print-tbl-1-row-1-col-1">' . $remarks . '</td>
														</tr>
								                    ';


								                    //
								                  } //END WHILE
								                  //
								                  //
								                  $tlink = "classlist-print.php?sy=" . $fsy . "&sem=" . $fsem . "&subjcode=" . $fsubjcode . "&section=" . $fsection . "";
								                  //
								                  //
								                  if(trim($fd) != "") {
								                    echo $fd;
								                    //
								                    $fd = "";
								                  }
								                  //
								                }

								              ?>

											</td>
										</tr>
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

			<div class="print-footer-1">
				<div class="bottom-lbl-1" style="padding-top: 50px;" align="right"><?php echo $fdatetime; ?></div>
			</div>

		</div>

	</div>
</body>
</html>