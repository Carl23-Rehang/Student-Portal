<?php include "connect.php"; error_reporting(0);
    //
    $value = trim($_GET['v']);
	//
	$res = "";
    //
    $qry = "SELECT * from srgb.validgrades where UPPER(TRIM(grade))='" . strtoupper(trim($value)) . "'  ";
    $result = pg_query($pgconn, $qry);
    if ($result) {
      $n = 0;
      while ($row = pg_fetch_array($result)) {
        //
        $res = trim($row['remarks']);
        //
      }
    }
    //
    if(trim($res) == "") {
        $res = "";
    }
    echo $res;
    //
?>