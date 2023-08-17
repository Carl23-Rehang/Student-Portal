<?php error_reporting(0);
	date_default_timezone_set('Asia/Hong_Kong');
	$date_year = date('Y');
	$date_month = date('n');
	$date_day = date('j');
	$date_hour = date('h');
	$date_hour2 = date('H');
	$date_minute = date('i');
	$date_second = date('s');
	$connsn = "localhost";
	$connun = "dsscd85_ictcoreu";
	$connpass = "@#DSsc1234@@#";
	$conndbn = "dsscd85_ictcore";
	$connport = "3306";
	$conn = mysqli_connect($connsn,$connun,$connpass,$conndbn,$connport);
	if($conn) {
	    //echo " MYSQL: Connected ";
	}else{
	    //echo " MYSQL: Not Connected ";
	}
	//
	$pg_host = "localhost";
	$pg_un = "dsscd85_admin3";
	$pg_pass = "RtZxC2xjCn1yrui$";
	$pg_dbn = "dsscd85_dssc_esms";
	$pg_port = "5432";
	//$pgconn = pg_connect("host=" . $pg_host . " port=" . $pg_port . " dbname=" . $pg_dbn . " user=" . $pg_un . " password=" . $pg_pass . "");
	//$pgconn = pg_connect("host=" . $pg_host . " port=" . $pg_port . " user=" . $pg_un . " password=" . $pg_pass . "");
	//$pgconn = pg_connect("host=" . $pg_host . " port=" . $pg_port . " user=" . $pg_un . " password=" . $pg_pass . "");
	$pgconn = pg_connect("user=" . $pg_un . " password=" . $pg_pass . " dbname=" . $pg_dbn . "");
	//echo '1 ' . pg_connection_status($pgconn);
	//echo("<script>console.log('PHP: " . "host=" . $pg_host . " port=" . $pg_port . " user=" . $pg_un . " password=" . $pg_pass . "" . "');</script>");
	if($pgconn) {
	    //echo " PG: Connected ";
	}else{
	    //echo " PG: Not Connected ";
	}
	//$v = pg_version();
	//echo $v['client'] . " xx ";
	//
	$connsn_21 = "localhost";
	$connun_21 = "dsscd85_enrollu";
	$connpass_21 = "#@!Enroll1234##!";
	$conndbn_21 = "dsscd85_enroll_db";
	$connport_21 = "3306";
	$conn_21 = mysqli_connect($connsn_21,$connun_21,$connpass_21,$conndbn_21,$connport_21);
	if($conn_21) {
	    //echo " MYSQL2: Connected ";
	}else{
	    //echo " MYSQL2: Not Connected ";
	}
	//
?>

