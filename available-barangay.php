<?php include "connect.php"; //error_reporting(0);
    //
    include "gvars.php";
	//
	$s = trim($_GET['s']);
    //
    //
    //echo "$s";
    $sq = "";
    //
    if(trim($s) != "") {
        $tv = " TRIM(LOWER(municipality))=TRIM(LOWER('" . $s . "')) ";
        if( trim($sq) == "" ) {
            $sq = $tv;
        }else{
            $sq = $sq . " AND " . $tv;
        }
    }
    //
    if( trim($sq) != "" ) {
        $sq = " where " . $sq . " ";
    }
	//
    $result = "";
    //
    $sql = " select brgy from tbladdress " . $sq . " group by brgy order by brgy ASC ";
    //echo($sql);
    $qry = mysqli_query($conn_21,$sql);
    while($dat=mysqli_fetch_array($qry)) {
    	//
    	$tpt = trim($dat['brgy']);
        //
        //echo $tpt;
        //
        if($tpt != "") {
            $tv = "<option value='" . $tpt . "'>" . $tpt . "</option>";
            if(trim($result) == "") {
                $result = $tv;
            }else{
                $result = $result . $tv;
            }
        }
    	//
    }
    //
    echo $result;
    //
?>