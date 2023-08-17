<?php session_start(); include "connect.php"; include "gvars.php"; error_reporting(0);
    //
    $typ = trim($_GET['t']);
    //
	$res = "";
    //
    $n = 0;
    $lquery2 = "SELECT a.studid,b.studfullname FROM srgb.semstudent AS a 
                LEFT JOIN srgb.student AS b ON b.studid=a.studid 
                WHERE TRIM(UPPER(sy))=TRIM(UPPER('" . $log_user_active_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $log_user_active_sem . "')) 
                GROUP BY a.studid,b.studfullname 
                ORDER BY b.studfullname ASC 
                
                ";
    $lresult2 = pg_query($pgconn, $lquery2);
    if ($lresult2) {
      while ($lrow2 = pg_fetch_array($lresult2)) {
        $tid = trim($lrow2['studid']);
        $tname = trim($lrow2['studfullname']);
        if($tid != "" && $tname != "") {
            if(strtolower(trim($typ)) != strtolower(trim("u"))) {
                $res = $res . '
                      <input type="hidden" id="stud_id_' . $n . '" value="' . $tid . '" />
                      <input type="hidden" id="stud_name_' . $n . '" value="' . $tname . '" />
                      <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'stud','studid','studname'" . ')">' . $tname . '</a>
                    ';
                //
            }else{
                $res = $res . '
                      <input type="hidden" id="studu_id_' . $n . '" value="' . $tid . '" />
                      <input type="hidden" id="studu_name_' . $n . '" value="' . $tname . '" />
                      <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'studu','studidu','studnameu'" . ')">' . $tname . '</a>
                    ';
            }
          $n++;
        }
      }
    }
    //
    echo $res;
    //
?>