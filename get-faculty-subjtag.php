<?php include "connect.php"; error_reporting(0);
    //
    //
	$res = "";
    //
    $qry = "SELECT empid,fullname from pis.employee GROUP BY empid,fullname ORDER BY fullname  ";
    $result = pg_query($pgconn, $qry);
    if ($result) {
      $n = 0;
      while ($row = pg_fetch_array($result)) {
        //
        $tid = trim($row['empid']);
        $tname = trim($row['fullname']);
        //
        $tv = $tname;
        $tv2 = $tname;
        //
        if($tid != "" && $tname != "") {
            $res = $res . '<a class="dropdown-content-a-1" onclick="' . "ItemListSelect_Faculty('st_faculty_h_items','" . $tid . "','" . $tname . "','st_faculty_id','st_faculty_name');" . '">' . $tv2 . '</a>';
        }
        //
      }
    }
    //
    echo $res;
    //
?>