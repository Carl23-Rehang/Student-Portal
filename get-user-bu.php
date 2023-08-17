<?php include "connect.php"; error_reporting(0);
    //
    //
	$res = "";
    //
    $utype = trim($_GET['ut']);
    //
    if($utype != "") {
        $qry = "";
        if (strtolower(trim($utype)) == strtolower(trim("employee"))) {
            $qry = "SELECT empid,fullname from pis.employee GROUP BY empid,fullname ORDER BY fullname  ";
        }
        if (strtolower(trim($utype)) == strtolower(trim("student"))) {
            $qry = "SELECT studid,studfullname from srgb.student GROUP BY studid,studfullname ORDER BY studfullname  ";
        }
        $result = pg_query($pgconn, $qry);
        if ($result) {
          $n = 0;
          while ($row = pg_fetch_array($result)) {
            //
            $tid = "";
            $tname = "";
            //
            if (strtolower(trim($utype)) == strtolower(trim("employee"))) {
                $tid = trim($row['empid']);
                $tname = trim($row['fullname']);
            }
            if (strtolower(trim($utype)) == strtolower(trim("student"))) {
                $tid = trim($row['studid']);
                $tname = trim($row['studfullname']);
            }
            //
            $tv = $tname;
            $tv2 = $tname;
            //
            if($tid != "" && $tname != "") {
                $res = $res . '<a class="dropdown-content-a-1" onclick="' . "ItemListSelect_User('st_user_h_items','" . $tid . "','" . $tname . "','st_user_id','st_user_name');" . '">' . $tv2 . '</a>';
            }
            //
          }
        }
    }
    //
    echo $res;
    //
?>