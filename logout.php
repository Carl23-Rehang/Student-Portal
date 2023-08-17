<?php include "connect.php"; session_start(); error_reporting(0);
  	include "gvars.php";
  	//
	$_SESSION[$appid . "c_user_id"] = "";
	$_SESSION[$appid . "c_user"] = "";
	$_SESSION[$appid . "c_user_dn"] = "";
	$_SESSION[$appid . "c_level"] = "1";
	$_SESSION[$appid . "c_user_photo"] = "";
	$_SESSION[$appid . "c_type"] = "";
	//
	$_SESSION[$appid . "c_user_is_admin"] = 0;
	//
	echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
	//echo "logged out";
	exit();
?>