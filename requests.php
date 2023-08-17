<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  //
  //
  $gdivreqreplystyle = 0; //IDENTIFY IF REPLY DIV SHOULD BE SHOWN ON LOAD
  //
  if(trim($log_userid)!="") {
    if($_POST['btnadd']) {
      $request = trim($_POST['request']);
      $note = trim($_POST['note']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($request) == "") {
        $errn++;
        $errmsg = $errmsg . "Request required. ";
      }
      /*
      $query0 = "SELECT * FROM tblstudentrequest where LOWER(TRIM(requesttype))==LOWER(TRIM('" . $request . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Group already added. ";
        }
      }
      */
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        //$status = "pending";
        $status = "";
        //
        //GET STATUS LEVEL 0
        $query0 = "SELECT * FROM tblrequeststatus WHERE LOWER(TRIM(level))=LOWER(TRIM('0'))";
        $result0 = mysqli_query($conn, $query0);
        if ($result0) {
          while ($row0 = mysqli_fetch_array($result0)) {
            //$status = trim($row0['status']);
            $status = trim($row0['level']);
          }
        }
        //
        $query = "INSERT INTO tblstudentrequest (studid,requesttype,note,status) VALUES ('" . trim($log_userid) . "','" . $request . "','" . $note . "','" . $status . "') ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Request submitted.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btnupdate']) {
      $id = trim($_POST['id']);
      $group = trim($_POST['group']);
      $description = trim($_POST['description']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($group) == "") {
        $errn++;
        $errmsg = $errmsg . "Group required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tblrequestgroup set groupname='" . $group . "',description='" . $description . "' WHERE requestgroupid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Group updated.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btndelete']) {
      $id = trim($_POST['id']);
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "DELETE FROM tblrequestgroup  WHERE requestgroupid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Group deleted.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    }
    //
    if($_POST['btncancel']) {
      $id = trim($_POST['id']);
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "UPDATE tblstudentrequest SET status='-1' WHERE requestid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Request cancelled.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    } //END CANCEL
    //
    //
    if($_POST['btnmsgreplydelete']) {
      $id = trim($_POST['msgid']);
      //
      $errn = 0;
      $errmsg = "";
      //
      //
      if($errn <= 0) {
        $gdivreqreplystyle = 1;
        //echo $group . "  ". $description;
        $query = "UPDATE tblstudentrequestmsgs SET active='0'  WHERE TRIM(UPPER(msgid))='" . strtoupper(trim($id)) . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $drreplymsg = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Message deleted.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{
        $drreplymsg = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    } //END DELETE
    //
    if($_POST['btnreplysend']) {
      $reqid = trim($_POST['requestid']);
      $msg = trim($_POST['msgreply']);
      //
      $gdivreqreplystyle = 1;
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($msg) == "") {
        $errn++;
        $errmsg = $errmsg . "Message required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "INSERT INTO tblstudentrequestmsgs (requestid,senderid,sendertype,msg) 
                  VALUES ('" . $reqid . "','" . $log_userid . "','" . $log_user_type . "','" . $msg . "')
        ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
      }else{
        $drreplymsg = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    } //END REPLY SEND
    //
    //
    if($_POST['btnupdatestatus']) {
      $id = trim($_POST['id']);
      $status = trim($_POST['status']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($status) == "") {
        $errn++;
        $errmsg = $errmsg . "Status required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tblstudentrequest set status='" . $status . "' WHERE requestid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Status updated.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }else{ 
        $dr = '
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> ' . $errmsg . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        ';
      }
    } //END UPDATE STATUS
    //
    //
    //LOAD STATUS OPTIONS
    $statsopt = [];
    $query = "SELECT * FROM tblrequeststatus ORDER BY level ASC";
    $result = mysqli_query($conn, $query);
    if ($result) {
      $n = 0;
      while ($row = mysqli_fetch_array($result)) {
        $tv = trim($row['level']);
        $tvl = trim($row['status']);
        if(trim($tv)!="" && trim($tvl)!="") {
          $tn = count($statsopt);
          $statsopt[$tn][0] = $tv;
          $statsopt[$tn][1] = $tvl;
        }
      } //END WHILE
    } //END QUERY
    //
  }
  //
  //
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Request</title>

  <?php include "header-imports.php"; ?>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <?php include "header.php"; ?>
        <!-- End of Topbar -->


          <!-- Begin Page Content -->
          <div class="container-fluid" style="padding-left: 12px;padding-right: 12px;">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"></h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">



                          <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shad                                                                                                                                                                    ow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary"><span class="color-blue-1">Requests</span></h6>
                                </div>
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                <div align="center">
                                  
                                  <?php
                                    //
                                    echo $dr;
                                    //
                                    //
                                    if(strtolower(trim($log_user_type))==strtolower(trim("student"))) {
                                      //
                                      //
                                      $opt = "";
                                      //
                                      $query0 = "SELECT * FROM tblrequesttype ORDER BY requesttype ASC";
                                      $result0 = mysqli_query($conn, $query0);
                                      if ($result0) {
                                        while ($row0 = mysqli_fetch_array($result0)) {
                                          $active = "";
                                          if(strtolower(trim($row0['requesttypeid'])) == strtolower(trim($_POST['request']))) {
                                            $active = " selected ";
                                          }
                                          $opt = $opt . '<option value="' . trim($row0['requesttypeid']) . '" ' . $active . '>' . trim($row0['requesttype']) . '</option>';
                                        }
                                      }
                                      //
                                      $adata = "";
                                      //
                                      $adata = '
                                      <button type="button" class="btn btn-success btn-1 btn-width-min-1" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>Create Request</b></button>

                                      <!-- Modal -->
                                      <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header bg-3 color-white-1 modal-header-1">
                                              <h5 class="modal-title" id="">Create Request</h5>
                                              <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                              <form method="post">
                                            <div class="modal-body">
                                              <div align="left">

                                                <div class="form-group margin-top1">
                                                  <span class="label1">Request for: <span class="text-danger"></span></span>
                                                  <select class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="request" id="request" placeholder="Group" required>
                                                  
                                                    ' . $opt . '

                                                  
                                                  </select>
                                                </div>

                                                <div class="form-group margin-top1">
                                                  <span class="label1">Note: <span class="text-danger"></span></span>
                                                  <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="note" id="note" placeholder="Note"></textarea>
                                                </div>

                                              </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                              <input type="submit" class="btn btn-primary bg-2 font-size-o-1" name="btnadd" value="Save changes" />
                                            </div>
                                              </form>
                                          </div>
                                        </div>
                                      </div>
                                      ';
                                      //
                                      echo $adata;
                                    }
                                    //
                                  ?>
                                  


                                  <br/>
                                  <br/>

                                </div>


                                <?php
                                  //
                                  //
                                  //
                                  $ps_pagerows = trim($setting_default_request_rows);
                                  if (trim($ps_pagerows) == "") {
                                    $ps_pagerows = 10;
                                  }
                                  $ps_page = trim($_GET['page']);
                                  if (trim($ps_page) == "" || $ps_page < 1) {
                                    $ps_page = 1;
                                  }
                                  //
                                  if(trim($ps_pagerows)=="") {
                                    $ps_pagerows = 10;
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
                                  $nresult = mysqli_query($conn, "SELECT COUNT(*) from tblstudentrequest WHERE status>=0 AND active='1' ");
                                  if ($nresult) {
                                    $nrow = mysqli_fetch_array($nresult);
                                    $numrows = trim($nrow[0]);
                                  }
                                  $maxpage = ($numrows / $ps_pagerows);
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
                                  }
                                  //
                                  if ($ps_page > $maxpage) {
                                    $ps_page = $maxpage;
                                    $toffset = ($ps_page - 1) * $ps_pagerows;
                                  }
                                  //
                                  //
                                  $tpaging = "";
                                  if($ps_page >= 1) {
                                    //PAGING FIRST
                                    $tpaging = $tpaging . '<a class="paging-btn-1" href="requests.php?page=1"><i class="fas fa-angle-double-left"></i></a>';
                                    //PAGING PREV
                                    $tpaging = $tpaging . '<a class="paging-btn-1" href="requests.php?page=' . ($ps_page - 1) . '"><i class="fas fa-angle-left"></i></a>';
                                    //SPACE
                                    $tpaging = $tpaging . '';
                                  }
                                  for($i=1; $i<=$maxpage; $i++) {
                                    $tstyle = "";
                                    if(strtolower(trim($ps_page))==strtolower(trim($i))) {
                                      $tstyle = " active ";
                                    }
                                    $tpaging = $tpaging . '<a class="paging-btn-1 ' . $tstyle . '" href="requests.php?page=' . $i . '">' . $i . '</a>';
                                  }
                                  if($ps_page <= $maxpage) {
                                    //SPACE
                                    $tpaging = $tpaging . '';
                                    //PAGING NEXT
                                    $tpaging = $tpaging . '<a class="paging-btn-1" href="requests.php?page=' . ($ps_page + 1) . '"><i class="fas fa-angle-right"></i></a>';
                                    //PAGING LAST
                                    $tpaging = $tpaging . '<a class="paging-btn-1" href="requests.php?page=' . $maxpage . '"><i class="fas fa-angle-double-right"></i></a>';
                                  }
                                  //
                                  //
                                  //
                                  $reqrelpy_reqid = trim($_POST['requestid']);
                                  //
                                  $tsq = "";
                                  if(strtolower(trim($log_user_type))==strtolower(trim("student"))) {
                                    $tsq = " AND TRIM(UPPER(studid))='" . strtoupper(trim($log_userid)) . "' ";
                                  }
                                  //
                                  $query = "SELECT * FROM tblstudentrequest WHERE status>=0 " . $tsq . " ORDER BY entrydate DESC  LIMIT " . $toffset . "," . $ps_pagerows . "";
                                  $result = mysqli_query($conn, $query);
                                  if ($result) {
                                    $n = 0;
                                    while ($row = mysqli_fetch_array($result)) {
                                      $n++;
                                      //
                                      $id = trim($row['requestid']);
                                      $od = trim($row['entrydate']);
                                      $sod = explode(" ", $od);
                                      $ds = explode("-", $sod[0]);
                                      $ts = explode(":", $sod[1]);
                                      $dt = new DateTime($od);
                                      $dtf = $dt->format('F j, Y g:i A');
                                      $istat = trim($row['status']);
                                      $statclass = "";
                                      //
                                      //
                                      $sopt = "";
                                      //
                                      if($istat == -1) {
                                        $statclass = "btn-danger";
                                      }
                                      if($istat == 0) {
                                        $statclass = "btn-warning";
                                      }
                                      if($istat > 0) {
                                        $statclass = "btn-success";
                                      }
                                      $studid = trim($row['studid']);
                                      //
                                      $alink = "";
                                      $alink = "./profile.php?t=student&id=" . $studid;
                                      //
                                      $name = "";
                                      $fphoto = "";
                                      $request = "";
                                      $note = "";
                                      $status = "";
                                      //GET REQUEST TYPE
                                      $query0 = "SELECT * FROM tblrequesttype WHERE requesttypeid='" . trim($row['requesttype']) . "' limit 1 ";
                                      $result0 = mysqli_query($conn, $query0);
                                      if ($result0) {
                                        while ($row0 = mysqli_fetch_array($result0)) {
                                          $request = trim($row0['requesttype']);
                                          $note = trim($row0['note']);
                                        }
                                      }
                                      //GET STUDENT DATA
                                      $query0 = "SELECT * FROM srgb.student WHERE studid='" . trim($studid) . "' limit 1 ";
                                      $result0 = pg_query($pgconn, $query0);
                                      if ($result0) {
                                        while ($row0 = pg_fetch_array($result0)) {
                                          $name = trim($row0['studfirstname']) . " " . trim($row0['studlastname']);
                                        }
                                      }
                                      $query0 = "SELECT * FROM web.student WHERE studid='" . trim($studid) . "' limit 1 ";
                                      $result0 = pg_query($pgconn, $query0);
                                      if ($result0) {
                                        while ($row0 = pg_fetch_array($result0)) {
                                          $fphoto = trim($row0['profilephoto']);
                                        }
                                      }
                                      if(trim($fphoto)!="") {
                                        $fphoto = $setting_profilephoto_basepath_student . $fphoto;
                                      }
                                      if(trim($fphoto)=="") {
                                        $fphoto = "img/user-avatar.png";
                                      }
                                      //GET STATUS
                                      $query0 = "SELECT * FROM tblrequeststatus WHERE level='" . trim($istat) . "' limit 1 ";
                                      $result0 = mysqli_query($conn, $query0);
                                      if ($result0) {
                                        while ($row0 = mysqli_fetch_array($result0)) {
                                          $status = trim($row0['status']);
                                        }
                                      }
                                      //LOAD STATUS OPT
                                      for ($i=0;$i<count($statsopt);$i++) {
                                        $tsel = "";
                                        if(strtolower(trim($istat))==strtolower(trim($statsopt[$i][0])) || strtolower(trim($istat))==strtolower(trim($statsopt[$i][1]))) {
                                          //
                                          $tsel = " selected ";
                                          //
                                        }
                                        $tsov = '<option value="' . $statsopt[$i][0] . '" ' . $tsel . ' >' . $statsopt[$i][1] . '</option>';
                                        if(trim($sopt)=="") {
                                          $sopt = $tsov;
                                        }else{
                                          $sopt = $sopt . $tsov;
                                        }
                                      }
                                      //
                                      //
                                      $mbtn = "";
                                      if(strtolower(trim($log_user_type))==strtolower(trim("student"))) {
                                          $tbtncancel = '';
                                          if ( strtolower(trim($istat)) == strtolower(trim("0")) ) {
                                            $tbtncancel = '
                                                  <br/>
                                                  <button type="button" class="btn btn-danger btn-table-1" data-toggle="modal" data-target="#modalCancel_' . $n . '">Cancel</button>
                                            ';
                                          }
                                          $mbtn = '
                                                  <button type="button" class="btn ' . $statclass . ' btn-table-1 text-transform-capitalize" >' . strtolower($status) . '</button>
                                                  ' . $tbtncancel . '
                                                  ';
                                      }
                                      if(strtolower(trim($log_user_type))==strtolower(trim("employee"))) {
                                        $mbtn = '
                                                  <button type="button" class="btn ' . $statclass . ' btn-table-1 text-transform-capitalize" data-toggle="modal" data-target="#modalUpdateStatus_' . $n . '">' . strtolower($status) . '</button>';
                                      }
                                      //
                                      //
                                      $msgcount = 0;
                                      //LOAD MESSAGES
                                      $pmsg = "";
                                      $rmquery = "SELECT * FROM tblstudentrequestmsgs WHERE requestid='" . $id . "' AND active='1' ORDER BY entrydate DESC";
                                      $rmresult = mysqli_query($conn, $rmquery);
                                      $rmsepn = 0;
                                      if ($rmresult) {
                                        while ($rmrow = mysqli_fetch_array($rmresult)) {
                                          $trmmsgid = trim($rmrow['msgid']);
                                          $trmdate = trim($rmrow['entrydate']);
                                          $trmmsg = trim($rmrow['msg']);
                                          $trm_sender_id = trim($rmrow['senderid']);
                                          $trm_sender_type = trim($rmrow['sendertype']);
                                          $trmsep = "";
                                          $trm_photo = "";
                                          $trm_photo_bp = "";
                                          $trm_name = "";
                                          $trm_link = "";
                                          $msgcount++;
                                          //
                                          $trmdate_dt = new DateTime($trmdate);
                                          $trmdate_f = $trmdate_dt->format('F j, Y g:i A');
                                          //GET PHOTO BASE PATH
                                          if (strtolower(trim($trm_sender_type))==strtolower(trim("employee"))) {
                                            $trm_photo_bp = $setting_profilephoto_basepath_employee;
                                          }
                                          if (strtolower(trim($trm_sender_type))==strtolower(trim("student"))) {
                                            $trm_photo_bp = $setting_profilephoto_basepath_student;
                                          }
                                          //GET DATA
                                          if (strtolower(trim($trm_sender_type))==strtolower(trim("employee"))) {
                                            //EMPLOYEE
                                            //LINK
                                            $trm_link = "./profile.php?t=employee&id=" . $trm_sender_id;
                                            //GET PHOTO
                                            $rmquery1 = "SELECT * FROM web.employee WHERE TRIM(LOWER(empid))='" . strtolower(trim($trm_sender_id)) . "'  ";
                                            $rmresult1 = pg_query($pgconn, $rmquery1);
                                            if ($rmresult1) {
                                              while ($rmrow1 = pg_fetch_array($rmresult1)) {
                                                $trm_photo = trim($rmrow1['profilephoto']);
                                              }
                                            }
                                            //GET NAME
                                            $rmquery1 = "SELECT * FROM pis.employee WHERE TRIM(LOWER(empid))='" . strtolower(trim($trm_sender_id)) . "'  ";
                                            $rmresult1 = pg_query($pgconn, $rmquery1);
                                            if ($rmresult1) {
                                              while ($rmrow1 = pg_fetch_array($rmresult1)) {
                                                $trm_fn = trim($rmrow1['firstname']);
                                                $trm_mn = trim($rmrow1['middlename']);
                                                $trm_ln = trim($rmrow1['lastname']);
                                                $trm_name = $trm_fn . " " . $trm_ln;
                                                if(trim($trm_mn)!="") {
                                                  if(strlen(trim($trm_mn))==1) {
                                                    $trm_mn = trim($trm_mn) . ".";
                                                  }
                                                  $trm_name = $trm_fn . " " . $trm_mn . " " . $trm_ln;
                                                }
                                              }
                                            }
                                          }
                                          if (strtolower(trim($trm_sender_type))==strtolower(trim("student"))) {
                                            //STUDENT
                                            //LINK
                                            $trm_link = "./profile.php?t=student&id=" . $trm_sender_id;
                                            //GET PHOTO
                                            $rmquery1 = "SELECT * FROM web.student WHERE TRIM(LOWER(studid))='" . strtolower(trim($trm_sender_id)) . "'  ";
                                            $rmresult1 = pg_query($pgconn, $rmquery1);
                                            if ($rmresult1) {
                                              while ($rmrow1 = pg_fetch_array($rmresult1)) {
                                                $trm_photo = trim($rmrow1['profilephoto']);
                                              }
                                            }
                                            //GET NAME
                                            $rmquery1 = "SELECT * FROM srgb.student WHERE TRIM(LOWER(studid))='" . strtolower(trim($trm_sender_id)) . "'  ";
                                            $rmresult1 = pg_query($pgconn, $rmquery1);
                                            if ($rmresult1) {
                                              while ($rmrow1 = pg_fetch_array($rmresult1)) {
                                                $trm_fn = trim($rmrow1['studfirstname']);
                                                $trm_mn = trim($rmrow1['studmidname']);
                                                $trm_ln = trim($rmrow1['studlastname']);
                                                $trm_name = $trm_fn . " " . $trm_ln;
                                                if(trim($trm_mn)!="") {
                                                  if(strlen(trim($trm_mn))==1) {
                                                    $trm_mn = trim($trm_mn) . ".";
                                                  }
                                                  $trm_name = $trm_fn . " " . $trm_mn . " " . $trm_ln;
                                                }
                                              }
                                            }
                                          }
                                          //
                                          if(trim($trm_photo)=="") {
                                            $trm_photo = "img/user-avatar.png";
                                          }
                                          if(trim($trm_photo)!="") {
                                            $trm_photo = $trm_photo_bp . $trm_photo;
                                            //$trm_photo = $trm_photo;
                                          }
                                          if($rmsepn > 0){
                                            $trmsep = '<hr class="request-1-hr-2"/>';
                                          }
                                          //
                                          $trm_edit = "";
                                          if(strtolower(trim($trm_sender_type))==strtolower(trim($log_user_type)) &&
                                             strtolower(trim($trm_sender_id))==strtolower(trim($log_userid))) {
                                            $trm_edit = '
                                                      <div>
                                                        <a class="text-danger" href="#" data-toggle="modal" data-target="#modalReplyDelete_' . $id . "_" . $trmmsgid . '">Delete</a>
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalReplyDelete_' . $id . "_" . $trmmsgid . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                                <h5 class="modal-title" id="">Delete Reply</h5>
                                                                <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                                <form method="post">
                                                              <div class="modal-body">
                                                                <div align="left">

                                                                  <input type="hidden" name="requestid" value="' . trim($id) . '" hidden />
                                                                  <input type="hidden" name="msgid" value="' . trim($trmmsgid) . '" hidden />

                                                                  Delete reply ?


                                                                </div>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                                <input type="submit" class="btn btn-danger font-size-o-1" name="btnmsgreplydelete" value="Delete Reply" />
                                                              </div>
                                                                </form>
                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>
                                            ';
                                          }
                                          //
                                          $tmv = $trmsep . '
                                              <div class="">
                                                <table width="100%">
                                                  <tr>
                                                    <td class="table-td-1 vertical-align-top" style="min-width: 70px;">
                                                      <table width="100%">
                                                        <tr>
                                                          <td class="vertical-align-top">
                                                            <a href="' . $trm_link . '">
                                                                <img class="table-td-img-1" src="' . $trm_photo . '">
                                                            </a>
                                                            <br/>
                                                          </td>
                                                          <td> 
                                                          </td>
                                                        </tr>
                                                      </table>
                                                    </td>
                                                    <td class="table-td-2">
                                                      <a href="' . $trm_link . '"><span class="request-1-name">' . strtolower($trm_name) . '</span></a>
                                                      <span class="request-1-date">(' . $trmdate_f . ')</span> 
                                                      <br/>
                                                      <span class="request-1-content-2">' . $trmmsg . '</span>
                                                      <br/>
                                                      <br/>
                                                      ' . $trm_edit . '
                                                    </td>
                                                  </tr>
                                                </table>
                                              </div>
                                          ';
                                          //
                                          $pmsg = $pmsg . $tmv;
                                          $rmsepn++;
                                          //
                                        }
                                      } //END QUERY 
                                      //echo $pmsg;
                                      //
                                      $fm = '';
                                      if( strtolower(trim($log_user_type))==strtolower(trim("student")) && strtolower(trim($istat)) == strtolower(trim("0")) ) {
                                        $fm = '
                                              <!-- Modal -->
                                              <div class="modal fade" id="modalCancel_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                  <div class="modal-content">
                                                    <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                      <h5 class="modal-title" id="">Cancel Request</h5>
                                                      <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                      <form method="post">
                                                    <div class="modal-body">
                                                      <div align="left">

                                                        <input type="hidden" name="id" value="' . trim($id) . '" hidden />

                                                        Cancel <b>' . trim($request) . '</b> ?

                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                      <input type="submit" class="btn btn-danger font-size-o-1" name="btncancel" value="Cancel Request" />
                                                    </div>
                                                      </form>
                                                  </div>
                                                </div>
                                              </div>
                                        ';
                                      }
                                      if(strtolower(trim($log_user_type))==strtolower(trim("employee"))) {
                                        $fm = '
                                              <!-- Modal -->
                                              <div class="modal fade" id="modalUpdateStatus_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                  <div class="modal-content">
                                                    <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                      <h5 class="modal-title" id="">Update Request Status</h5>
                                                      <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                      </button>
                                                    </div>
                                                      <form method="post">
                                                    <div class="modal-body">
                                                      <div align="left">

                                                        <input type="hidden" name="id" value="' . trim($id) . '" hidden />

                                                        <div class="form-group margin-top1">
                                                          <span class="label1">Status: <span class="text-danger"></span></span>
                                                          <select class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="status" id="status" placeholder="Status" required>
                                                          ' . $sopt . '
                                                          </select>
                                                        </div>


                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                      <input type="submit" class="btn btn-primary bg-2 font-size-o-1" name="btnupdatestatus" value="Save changes" />
                                                    </div>
                                                      </form>
                                                  </div>
                                                </div>
                                              </div>
                                        ';
                                      }
                                      //
                                      //
                                      //DIV REPLY SHOW HIDE INDICATOR
                                      $reqrelpy_style = "none";
                                      if($gdivreqreplystyle > 0) {
                                        if(strtolower(trim($id))==strtolower(trim($reqrelpy_reqid))) {
                                          $reqrelpy_style = "block";
                                        }
                                      }
                                      //
                                      echo '
                                          <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">
                                            <div class="card shadow mb-4">
                                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                                <table width="100%">
                                                  <tr>
                                                    <td class="table-td-1 vertical-align-top">
                                                      <table width="100%">
                                                        <tr>
                                                          <td class="vertical-align-top">
                                                            <div align="center" style="padding: 0px; margin: 0px;">
                                                                <a href="' . $alink . '">
                                                                    <img class="table-td-img-1" src="' . $fphoto . '">
                                                                </a>
                                                            <br/>
                                                            <br/>
                                                            ' . $mbtn . '
                                                            ' . $fm . '
                                                            </div>
                                                          </td>
                                                          <td>
                                                          </td>
                                                        </tr>
                                                      </table>
                                                    </td>
                                                    <td class="table-td-2">
                                                      <a href="' . $alink . '" style="text-decoration: none;"><span class="request-1-name">' . strtolower($name) . '</span></a>
                                                      <br/>
                                                      <span class="request-1-date">' . $dtf . '</span>
                                                      <hr class="request-1-hr"/>
                                                      <span class="request-1-title">' . $request . '</span>
                                                      <br/>
                                                      <div class="request-1-content">' . $note . '</div>
                                                      <div class="request-1-bottom">
                                                        <a id="' . $n . '" style="color: #4e73df; cursor: pointer;"  onclick="showHideElement(' . "'" . 'div_msg_replies_' . $n . '' . "'" . ');">Replies (' . $msgcount . ')</a>
                                                        <div class="request-1-replies" id="div_msg_replies_' . $n . '" style="display:' . $reqrelpy_style . ';">
                                                          
                                                          <br/>
                                                          ' . $drreplymsg . '
                                                          <form id="fm_' . $n . '" method="post" action="#fm_' . $n . '">
                                                            <input type="hidden" name="requestid" value="' . trim($id) . '" hidden />
                                                            <div class="form-group margin-top1">
                                                              <span class="label1"><span class="text-danger"></span></span>
                                                              <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="msgreply" id="msgreply" placeholder="Write a reply...">' . trim($row['description']) . '</textarea>
                                                            </div>
                                                            <input type="submit" class="btn btn-primary bg-2 font-size-o-1 request-1-reply-btn-send" name="btnreplysend" value="Send" />
                                                          </form>

                                                          <br/>
                                                          <br/>

                                                          ' . $pmsg . '

                                                        </div>
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                          </div>
                                      ';
                                    }
                                  }
                                  //
                                  //
                                  echo '
                                      <hr>

                                      ' . $tpaging . '

                                  ';
                                  //
                                  //
                                ?>


                                
                              </div>
                            </div>

                          </div>



                          

                          <?php
                            //
                            $adata = "";
                            //
                            if(strtolower(trim($log_user_type))==strtolower(trim("student"))) {
                              //
                              $tcd = "";
                              //
                              $query = "SELECT A.requestid,A.studid,A.requesttype,A.note,A.status,A.active,A.entrydate,B.requesttypeid,B.requesttype AS requestname 
                                         FROM tblstudentrequest A 
                                         JOIN tblrequesttype B ON A.requesttype=B.requesttypeid  
                                         WHERE A.status='-1' AND TRIM(UPPER(studid))='" . strtoupper(trim($log_userid)) . "' 
                                         ORDER BY A.entrydate DESC,B.requesttype ASC";
                              $result = mysqli_query($conn, $query);
                              if ($result) {
                                $n = 0;
                                while ($row = mysqli_fetch_array($result)) {
                                  $n++;
                                  //
                                  $status = "";
                                  //
                                  $query0 = "SELECT * FROM tblrequeststatus WHERE LOWER(TRIM(level))=LOWER(TRIM('" . trim($row['status']) . "')) limit 1 ";
                                  $result0 = mysqli_query($conn, $query0);
                                  if ($result0) {
                                    while ($row0 = mysqli_fetch_array($result0)) {
                                      $status = trim($row0['status']);
                                    }
                                  }
                                  //
                                  $tcd = $tcd . '
                                    <tr>
                                      <th scope="row" class="table-row-width-1">

                                      </th>
                                      <td class="table-row-width-2">' . $n . '</th>
                                      <td>' . trim($row['requestname']) . '</td>
                                      <td>' . trim($row['note']) . '</td>
                                      <td>' . $status . '</td>
                                      <td>' . trim($row['entrydate']) . '</td>
                                    </tr>
                                  ';
                                }
                              }

                              //
                              $adata = '
                                    <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                                      <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                          <div align="left">
                                            <h6 class="m-0 font-weight-bold text-primary"><span class="color-blue-1">Cancelled Request</span></h6>
                                          </div>
                                        </div>
                                        <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                          <div align="left">

                                          </div>
                                          
                                          <table class="table table-striped table-hover">
                                          <thead class="thead-1 font-size-o-1">
                                            <tr>
                                              <th scope="col"></th>
                                              <th scope="col">#</th>
                                              <th scope="col">Request</th>
                                              <th scope="col">Note</th>
                                              <th scope="col">Status</th>
                                              <th scope="col">Request Date</th>
                                            </tr>
                                          </thead>
                                          <tbody class="font-size-o-1">
                                            ' . $tcd . '
                                          </tbody>
                                        </table>

                                          <hr>
                                          
                                        </div>
                                      </div>

                                    </div>
                              ';
                              //
                            }
                            //
                            echo $adata;
                            //
                          ?>
                          

                        </div>

                      </div>

            </div>



            </div>

          <!-- Content Row -->

          <div class="row">

          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-sm-12">
            <br/>
            <br/>
            <br/>
            </div>
          </div>
        <!-- /.container-fluid -->


      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <?php include "footer.php"; ?>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <?php include "footer-imports.php"; ?>

  <script>
    function showHideElement(e) {
      var x = document.getElementById(e);
      if (x.style.display === "none") {
        x.style.display = "block";
      } else {
        x.style.display = "none";
      }
      document.documentElement.scrollTop = window.pageYOffset;
      document.body.scrollTop = window.pageYOffset;
    }
  </script>


</body>

</html>
