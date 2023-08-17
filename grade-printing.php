<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  //
  $g_sel_sy = trim($_GET['sy']);
  $g_sel_sem = trim($_GET['sem']);
  $g_sel_semt = $g_sel_sem;
  if(strtolower(trim($g_sel_sem)) == strtolower(trim("1"))) {
  	$g_sel_semt = "1st Sem";
  }
  if(strtolower(trim($g_sel_sem)) == strtolower(trim("2"))) {
  	$g_sel_semt = "2nd Sem";
  }
  if(strtolower(trim($g_sel_sem)) == strtolower(trim("S"))) {
  	$g_sel_semt = "Summer";
  }
  //
  $g_sel_subject = trim($_GET['subject']);
  $g_sel_section = trim($_GET['section']);
  $g_sel_subject_desc = "";
  $g_sel_inst = "";
  $g_sel_inst_name = "";
  $g_sel_inst_deanid = "";
  $g_sel_inst_deanname = "";
  $g_sel_dept = "";
  $g_sel_dept_chairid = "";
  $g_sel_dept_chairname = "";
  $g_sel_facid = "";
  $g_sel_facname = "";
  $g_sel_facname2 = "";
  $g_sel_subject_day = "";
  $g_sel_subject_time = "";
  $g_sel_subject_daytime = "";
  $g_sel_subject_room = "";
  //
  $g_desig_registrar = "";
  $g_desig_registrar_desig = "";
  //
  $g_sel_subject_locked = 0;
  //
  //PRINT SETTINGS
  $sett_print_rows_max = 37;
  //
  //GET DATA
  $g_republic = "";
  $g_name = "";
  $g_shortname = "";
  $g_fullname = "";
  $g_address1 = "";
  $g_address2 = "";
  //GET EST INFO
  $tquery1 = " SELECT * FROM tblestinfo WHERE active='1' LIMIT 1 ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $g_republic = trim($trow1['republic']);
      $g_name = trim($trow1['name']);
      $g_shortname = trim($trow1['shortname']);
      $g_address1 = trim($trow1['address1']);
      $g_address2 = trim($trow1['address2']);
    }
  }
  //
  $g_fullname = $g_name;
  if(trim($g_shortname) != "") {
  	$g_fullname = $g_name . " (" . $g_shortname . ")";
  }
  //GET EST INFO END
  //GET INSTITUTE
  $tquery1 = " SELECT * FROM srgb.semsubject WHERE TRIM(UPPER(sy))=TRIM(UPPER('" . $g_sel_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $g_sel_sem . "')) AND TRIM(UPPER(subjcode))=TRIM(UPPER('" . $g_sel_subject . "')) AND TRIM(UPPER(section))=TRIM(UPPER('" . $g_sel_section . "')) ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_sel_inst = trim($trow1['forcoll']);
      $g_sel_dept = trim($trow1['fordept']);
      $g_sel_facid = trim($trow1['facultyid']);
      $g_sel_subject_day = trim($trow1['days']);
      $g_sel_subject_time = trim($trow1['time']);
      $g_sel_subject_room = trim($trow1['room']);
    }
  }
  $g_sel_subject_daytime = $g_sel_subject_day;
  if(trim($g_sel_subject_daytime) == "") {
  	$g_sel_subject_daytime = $g_sel_subject_time;
  }else{
  	$g_sel_subject_daytime = $g_sel_subject_daytime . " / " . $g_sel_subject_time;
  }
  //GET INSTITUTE END
  //GET INSTITUTE NAME
  $tquery1 = " SELECT * FROM srgb.college WHERE TRIM(UPPER(collcode))=TRIM(UPPER('" . $g_sel_inst . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_sel_inst_name = trim($trow1['collname']);
      $g_sel_inst_deanid = trim($trow1['colldean']);
    }
  }
  //GET INSTITUTE NAME END
  //GET DEPT CHAIR
  $tquery1 = " SELECT * FROM srgb.department WHERE TRIM(UPPER(deptcode))=TRIM(UPPER('" . $g_sel_dept . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_sel_dept_chairid = trim($trow1['deptchairman']);
    }
  }
  //GET DEPT CHAIR END
  //GET SUBJECT DESC
  $tquery1 = " SELECT * FROM srgb.subject WHERE TRIM(UPPER(subjcode))=TRIM(UPPER('" . $g_sel_subject . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_sel_subject_desc = trim($trow1['subjdesc']);
    }
  }
  //GET SUBJECT DESC END
  //GET FACULTY NAME
  $tquery1 = " SELECT * FROM pis.employee WHERE TRIM(UPPER(empid))=TRIM(UPPER('" . $g_sel_facid . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_sel_facname = trim($trow1['fullname']);
      //
      $tfn = trim($trow1['firstname']);
      $tmn = trim($trow1['middlename']);
      $tln = trim($trow1['lastname']);
      if(trim($tmn) == "") {
      	$tmn = "";
      }else{
      	$tfl = substr($tmn, 0, 1);
      	if(trim($tfl) != "" && trim($tfl) != ".") {
      		$tmn = " " . $tfl . ".";
      	}
      }
      $g_sel_facname2 = $tfn . "" . $tmn . " " . $tln;
    }
  }
  //GET FACULTY NAME END
  //GET NAME DEAN
  $tquery1 = " SELECT * FROM pis.employee WHERE TRIM(UPPER(empid))=TRIM(UPPER('" . $g_sel_inst_deanid . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      //
      $tfn = trim($trow1['firstname']);
      $tmn = trim($trow1['middlename']);
      $tln = trim($trow1['lastname']);
      if(trim($tmn) == "") {
      	$tmn = "";
      }else{
      	$tfl = substr($tmn, 0, 1);
      	if(trim($tfl) != "" && trim($tfl) != ".") {
      		$tmn = " " . $tfl . ".";
      	}
      }
      $g_sel_inst_deanname = $tfn . "" . $tmn . " " . $tln;
    }
  }
  //GET NAME DEAN END
  //GET NAME DEPTCHAIR
  $tquery1 = " SELECT * FROM pis.employee WHERE TRIM(UPPER(empid))=TRIM(UPPER('" . $g_sel_dept_chairid . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      //
      $tfn = trim($trow1['firstname']);
      $tmn = trim($trow1['middlename']);
      $tln = trim($trow1['lastname']);
      if(trim($tmn) == "") {
      	$tmn = "";
      }else{
      	$tfl = substr($tmn, 0, 1);
      	if(trim($tfl) != "" && trim($tfl) != ".") {
      		$tmn = " " . $tfl . ".";
      	}
      }
      $g_sel_dept_chairname = $tfn . "" . $tmn . " " . $tln;
    }
  }
  //GET NAME DEPTCHAIR END
  //GET DESIG : REGISTRAR
  $tquery1 = " SELECT * FROM srgb.environment_variables WHERE TRIM(UPPER(attribute))=TRIM(UPPER('" . "registrar" . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_desig_registrar = trim($trow1['value']);
    }
  }
  $tquery1 = " SELECT * FROM srgb.environment_variables WHERE TRIM(UPPER(attribute))=TRIM(UPPER('" . "registrar_designation" . "'))  ";
  $tresult1 = pg_query($pgconn, $tquery1);
  if ($tresult1) {
    while ($trow1 = pg_fetch_array($tresult1)) {
      $g_desig_registrar_desig = trim($trow1['value']);
    }
  }
  //GET DESIG : REGISTRAR END
  //GET SUBJECT LOCK STATUS
  $qry = "SELECT subjcode,section,lock from srgb.semsubject where UPPER(TRIM(sy))='" . strtoupper(trim($g_sel_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($g_sel_sem)) . "' AND UPPER(TRIM(subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(section))='" . strtoupper(trim($g_sel_section)) . "'  ";
  $result = pg_query($pgconn, $qry);
  //echo $log_userid;
  if ($result) {
    $n = 0;
    while ($row = pg_fetch_array($result)) {
      //
      $tval = ($row['lock']);
      if(strtolower(trim($tval)) == strtolower(trim("t")) || strtolower(trim($tval)) == strtolower(trim("true"))) {
        $g_sel_subject_locked = 1;
      }else{
        $g_sel_subject_locked = 0;
      }
      //
    }
  }
  //GET SUBJECT LOCK STATUS END
  //
  //
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Grade Printing</title>

  <link rel="shortcut icon" type="image" href="img/dssc-logo.png">

  <style type="text/css">

  	body, html {
  		margin: 0px;
  		padding: 0px;
  		font-family: Arial;
  		font-size: 0.9rem;
		counter-reset: pagz;
		counter-reset: pagx;
  	}

  	.div-main {
  		position: relative;
  	}

	.header-1 {
		width: 100%;
		text-align: center;
		padding-top: 1px;
		padding-bottom	: 1px;
	}

	.header-logo-1 {
		width: 100px;
		position: absolute;
		top: 0;
		left: 0;
		margin-top: 6px;
		margin-left: 24px;
	}

	.header-status-1 {
		font-size: 2rem;
		font-weight: bold;
		position: absolute;
		top: 0;
		right: 0;
		margin-top: 12px;
		margin-right: 18px;
	}

	.header-table-1 {
		width: 100%;
	}

	.header-table-1-td-1 {
		width: 60%;
	}

	.header-table-1-td-2 {
		width: 40%;
	}

	.table-main-1 {
		width: 100%;
	}
	.table-main-1 tfoot {
		counter-increment: pagerr;
	}
	.table-main-1 tbody {
		counter-increment: pagerr;
	}

	.table-grade-1 {
		width: 100%;
	}
	.table-grade-1-td-1 {
		width: 5%;
	}
	.table-grade-1-td-2 {
		width: 50%;
	}
	.table-grade-1-td-3 {
		width: 15%;
	}
	.table-grade-1-td-4 {
		width: 15%;
	}
	.table-grade-1-td-5 {
		width: 15%;
	}

	.hr-1 {
		padding: 0px;
		border: none;
		border-bottom: 1px solid #000;
		margin-top: 4px;
		margin-bottom: 4px;
	}

	.div-eol-1 {
		text-align: center;
	}

	.footer-table-1 {
		width: 100%;
	}
	.footer-table-1-td-1 {
		width: 50%;
		padding-right: 10%;
	}
	.footer-table-1-td-2 {
		width: 50%;
		padding-left: 10%;
	}
	.footer-table-1-td-1-div-1 {
		padding-left: 5%;
	}
	.footer-table-1-td-2-div-1 {
		padding-left: 5%;
	}
	.footer-table-1-lbl-1 {
		font-style: italic;
	}
	.footer-table-1-value-1 {
		min-height: 18px;
	}

	.page-break-1 {
		display: block;
        page-break-after: always;
	}

	.page-header-1-space-1 {
		height: 0px;
		counter-increment: pagegg;
	}
	.page-header-1 {
		height: 248px;
	}

	.page-footer-1-space-1 {
		height: 0px;
	}
	.page-footer-1 {
		height: 200px;
	}

	.page-header-1 {
		position: relative;
		top: 0;
		width: 100%;
		counter-increment: pagexf;
	}
	.page-footer-1 {
		position: relative;
		bottom: 0;
		width: 100%;
		counter-increment: pagexf;
	}

	.page-number-1 {

	}

	.page-number-1::before {

	}

	.page-number-2 {
		position: absolute;
		top: 0;
		width: 100%;
		font-style: italic;
	}

	.page-number-2::after {
		counter-increment: pagx;
	}




	.page-header-1:before {
		counter-increment: pageqq;
	}



	@page {
	  size: A4;
	  margin: 11mm 17mm 17mm 17mm;
	  @bottom-left {
	    content: counter(page) ' of ' counter(pages);
	  }
	}

	@media print {

	   .table-main-1 thead {display: table-header-group;} 
	   .table-main-1 tfoot {display: table-footer-group;}

		html, body {
			width: 210mm;
			height: 297mm;
		}


		.page-header-1-space-1 {
			height: 248px;
		}
		.page-footer-1-space-1 {
			height: 200px;
		}

		.page-header-1 {
			position: fixed;
			top: 0;
			width: 100%;
		}
		.page-footer-1 {
			position: fixed;
			bottom: 0;
			width: 100%;
		}


		.page-footer-1::after {
            text-align: right;	
		}


		.page-number-2 {
		}
		.page-number-2::after {	
            content: "Page No.: " counter(pagx);
		}

		.table-main-1 tfoot:after {

		}


	}

  </style>

</head>
<body id="page-top" onload="window.print();">
	<div>

		<div class="page-header-1">
			
			<div class="header-1"><?php echo $g_republic; ?></div>
			<div class="header-1"><b><?php echo $g_fullname; ?></b></div>
			<div class="header-1"><?php echo $g_address1; ?></div>
			<div class="header-1"><?php echo $g_address2; ?></div>
			<div class="header-1"><b><?php echo $g_sel_inst_name; ?></b></div>
			<div class="header-1">oOo</div>
			<div class="header-1"><b><i>Report of Grades</i></b></div>
			<img class="header-logo-1" src="img/dssc-logo.png" alt="" />
			<div class="header-status-1">
				<?php
					if ($g_sel_subject_locked <= 0) {
						echo "DRAFT COPY";
					}
				?>
			</div>
			<hr class="hr-1" style="margin-top: 12px;" />

			<table class="header-table-1">
				<tr>
					<td class="header-table-1-td-1">Subject Course No.: <?php echo $g_sel_subject; ?></td>
					<td class="header-table-1-td-2">Semester: <?php echo $g_sel_semt; ?></td>
				</tr>
				<tr>
					<td class="header-table-1-td-1">Subject Description: <?php echo $g_sel_subject_desc; ?></td>
					<td class="header-table-1-td-2">School Year: <?php echo $g_sel_sy; ?></td>
				</tr>
				<tr>
					<td class="header-table-1-td-1">Course, Year & Section: <?php echo $g_sel_section; ?></td>
					<td class="header-table-1-td-2">Room No.: <?php echo $g_sel_subject_room; ?></td>
				</tr>
				<tr>
					<td class="header-table-1-td-1">Time / Day: <?php echo $g_sel_subject_daytime; ?></td>
					<td class="header-table-1-td-2">Instructor: <?php echo $g_sel_facname2; ?></td>
				</tr>
			</table>

			<hr class="hr-1" style="" />

			<table class="header-table-1">
				<tr style="font-weight: bold; font-style: italic;">
					<td class="table-grade-1-td-1"></td>
					<td class="table-grade-1-td-2">Name of Student</td>
					<td class="table-grade-1-td-3">Course & Year</td>
					<td class="table-grade-1-td-4">Final Grade</td>
					<td class="table-grade-1-td-5">Remarks</td>
				</tr>
			</table>

			<hr class="hr-1" style="" />

		</div>

	<table class="table-main-1">
	<thead>
	<tr>
	<td>
		
		<div class="page-header-1-space-1">

		</div>

	</td>
	</tr>
	</thead>
	<tbody>
	<tr>
	<td>


		<div style="">



				<?php
					//
					$tad = "";
					//
					$mrx = $sett_print_rows_max;
					$mrn = 0;
					$tpagen = 0;
					//
					$bpage_margin1 = 312;
					$bpage_margin = 316.5;
					//
	                $qry = "SELECT a.studid,b.studfullname,a.midterm,a.finalterm,a.grade,a.remarks 
	                        from srgb.registration AS a 
	                        LEFT JOIN srgb.student AS b ON b.studid=a.studid 
	                        where UPPER(TRIM(a.sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(a.sem))='" . strtoupper(trim($log_user_active_sem)) . "' AND UPPER(TRIM(a.subjcode))='" . strtoupper(trim($g_sel_subject)) . "' AND UPPER(TRIM(a.section))='" . strtoupper(trim($g_sel_section)) . "' 
	                        order by b.studfullname ASC ";
	                $result = pg_query($pgconn, $qry);
	                if ($result) {
	                	$n = 0;
	                	while ($row = pg_fetch_array($result)) {
		                    //
		                    $n++;
		                    $mrn++;
		                    //
		                    //
		                    $tstudid = trim($row['studid']);
		                    $tname = trim($row['studfullname']);
		                    $tcourse = "";
		                    $tgrade = trim($row['grade']);
		                    $tremark = trim($row['remarks']);
		                    //
		                    //GET STUDENT COURSE
							$tquery1 = " SELECT * FROM srgb.semstudent WHERE TRIM(UPPER(studid))=TRIM(UPPER('" . $tstudid . "')) AND UPPER(TRIM(sy))='" . strtoupper(trim($log_user_active_sy)) . "' AND UPPER(TRIM(sem))='" . strtoupper(trim($log_user_active_sem)) . "'  ";
							$tresult1 = pg_query($pgconn, $tquery1);
							if ($tresult1) {
								while ($trow1 = pg_fetch_array($tresult1)) {
									$tcrs = trim($trow1['studmajor']);
									$tlvl = trim($trow1['studlevel']);
									$tcourse = $tcrs;
									if(trim($tcourse) == "") {
										$tcourse = $tlvl;
									}else{
										$tcourse = $tcourse . " - " . $tlvl;
									}
								}
							}
		                    //
		                    $tcd = $tcd . '
		                    	<tr>
		                    		<td class="table-grade-1-td-1">' . $n . '</td>
		                    		<td class="table-grade-1-td-2">' . $tname . '</td>
		                    		<td class="table-grade-1-td-3">' . $tcourse . '</td>
		                    		<td class="table-grade-1-td-4">' . $tgrade . '</td>
		                    		<td class="table-grade-1-td-5">' . $tremark . '</td>
		                    	<tr>
		                    ';
		                    //
		                    //CHECK ROWS
		                    if($mrn >= $mrx) {
		                    	//
		                    	$tpagen++;
			                    if($tpagen == 1) {
			                    	$tpmargin = $bpage_margin1;
			                    }else{
			                    	$tpmargin = ($tpagen) * $bpage_margin;
			                    }
			                    //
		                    	$tfd = '
		                    		<div class="page-number-1"></div>
		                    		<div class="page-number-2" style="margin-top: ' . $tpmargin . 'mm;"></div>
		                    		<table class="table-grade-1">
		                    		<tbody>
		                    			' . $tcd . '
		                    		</tbody>
		                    		</table>
		                    	';
		                    	$tad = $tad . $tfd;
		                    	//
		                    	$tcd = "";
		                    	$mrn = 0;
		                    	//
		                    }
		                    //
	                	}
	                }
	                //
	                //IF THERES EXTRA DATA
                    if($mrn > 0) {
                    	//
                    	$tpagen++;
	                    if($tpagen == 1) {
	                    	$tpmargin = $bpage_margin1;
	                    }else{
	                    	$tpmargin = ($tpagen) * $bpage_margin;
	                    }
                    	//
                    	$tfd = '
		                    <div class="page-number-1"></div>
		                    <div class="page-number-2" style="margin-top: ' . $tpmargin . 'mm;"></div>
                    		<table class="table-grade-1">
                    		<tbody>
                    			' . $tcd . '
                    		</tbody>
                    		</table>
                    	';
                    	$tad = $tad . $tfd;
                    	//
                    	$tcd = "";
                    	$mrn = 0;
                    	//
                    }
	                //
	                echo $tad;
				?>


			<div class="div-eol-1">***** NOTHING FOLLOWS *****</div>

		</div>

	</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
	<td>

		<div class="page-footer-1-space-1">
			
		</div>

	</td>
	</tr>
	</tfoot>
	</table>


		<div class="page-footer-1">
			
			<hr class="hr-1" style="margin-bottom: 12px;" />

			<table class="footer-table-1">
				<tr>
					<td class="footer-table-1-td-1">
						<div align="left">
							<span class="footer-table-1-lbl-1">Submitted By:</span>
							<div class="footer-table-1-td-1-div-1" align="center">
								<div class="footer-table-1-value-1"><?php echo $g_sel_facname2; ?></div>
								<hr class="hr-1" style="" />
								Instructor
							</div>
						</div>
					</td>
					<td class="footer-table-1-td-1">
						<div align="left">
							<span class="footer-table-1-lbl-1">Checked and Found Correct:</span>
							<div class="footer-table-1-td-1-div-1" align="center">
								<div class="footer-table-1-value-1"><?php echo $g_sel_dept_chairname; ?></div>
								<hr class="hr-1" style="" />
								Dept. Chairman
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="footer-table-1-td-1">
						<div align="left">
							<span class="footer-table-1-lbl-1">Noted:</span>
							<div class="footer-table-1-td-1-div-1" align="center">
								<div class="footer-table-1-value-1"><?php echo $g_sel_inst_deanname; ?></div>
								<hr class="hr-1" style="" />
								Dean
							</div>
						</div>
					</td>
					<td class="footer-table-1-td-1">
						<div align="left">
							<span class="footer-table-1-lbl-1">Received By:</span>
							<div class="footer-table-1-td-1-div-1" align="center">
								<div class="footer-table-1-value-1"><?php echo $g_desig_registrar; ?></div>
								<hr class="hr-1" style="" />
								<?php echo $g_desig_registrar_desig; ?>
							</div>
						</div>
					</td>
				</tr>
			</table>

			<div align="left" style="margin-top: 8px;">
				<i>Date/Time Printed <?php echo $print_datetime; ?></i>
			</div>

		</div>


	</div>




</body>
</html>
