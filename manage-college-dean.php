<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  include "access_check.php";
  //
  $isadmin = 0;
  $adminroleid = "";
  //GET ADMIN ROLE ID
  $tquery1 = "SELECT * FROM tblroletype WHERE TRIM(LOWER(isadmin))='" . strtolower(trim("1")) . "' AND active='1' ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $adminroleid = trim($trow1['roletypeid']);
    }
  }
  //
  //GET ADMIN ROLE ID
  $tquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(userrole))='" . strtolower(trim($adminroleid)) . "' AND active='1' ";
  $tresult1 = mysqli_query($conn, $tquery1);
  if ($tresult1) {
    while ($trow1 = mysqli_fetch_array($tresult1)) {
      $isadmin = 1;
    }
  }
  //
  if ($isadmin <= 0) {
    echo '<meta http-equiv="refresh" content="0;URL=login.php" />';
    exit();
  }
  //
  //
  // SAVE SETTINGS
  if(trim($log_userid)!="") {
    if($_POST['btnsave']) {
      //
      $id = trim($_POST['id']);
      //
      $empid = trim($_POST['empid']);
      $employee = trim($_POST['employee']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($id) == "") {
        $errn++;
        $errmsg = $errmsg . "College required. ";
      }
      //
      //
      //
      if($errn <= 0) {
        //
        // UPDATE
        $query = "UPDATE srgb.college SET colldean='" . $empid . "' WHERE LOWER(TRIM(collcode))='" . strtolower(trim($id)) . "' ";
        $result = pg_query($pgconn,$query);
        //
        if ($result) {
          $dr = '
            <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> College dean updated.
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }else{
          $dr = '
            <div class="alert alert-danger alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
              <strong></strong> ' . $errmsg . '
              <button type="button" class="close" style="padding: 2px 12px;" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" style="font-size: 1.1rem">&times;</span>
              </button>
            </div>
          ';
        }
        //
      }
    }
    //
    //
  }
  //
  //
  // LOAD ITEMS FOR EMPLOYEES
  $gd_items_emp = "";
  $gd_items_emp_u = "";
  $n = 0;
  $gd_items_emp = $gd_items_emp . '
          <input type="hidden" id="emp_id_' . $n . '" value="" />
          <input type="hidden" id="emp_name_' . $n . '" value="" />
          <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'emp','empid','employee'" . ')"> --- None --- </a>
        ';
  $gd_items_emp_u = $gd_items_emp_u . '
          <input type="hidden" id="empu_id_' . $n . '" value="" />
          <input type="hidden" id="empu_name_' . $n . '" value="" />
          <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'empu','empidu','employeeu'" . ')"> --- None --- </a>
        ';
  $n++;
  $lquery2 = "SELECT empid,fullname,lastname,firstname,middlename FROM pis.employee ORDER BY fullname ASC";
  $lresult2 = pg_query($pgconn, $lquery2);
  if ($lresult2) {
    while ($lrow2 = pg_fetch_array($lresult2)) {
      $tid = trim($lrow2['empid']);
      $tname = trim($lrow2['fullname']);
      if($tid != "" && $tname != "") {
        $gd_items_emp = $gd_items_emp . '
                <input type="hidden" id="emp_id_' . $n . '" value="' . $tid . '" />
                <input type="hidden" id="emp_name_' . $n . '" value="' . $tname . '" />
                <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'emp','empid','employee'" . ')">' . $tname . '</a>
              ';
        //
        $gd_items_emp_u = $gd_items_emp_u . '
                <input type="hidden" id="empu_id_' . $n . '" value="' . $tid . '" />
                <input type="hidden" id="empu_name_' . $n . '" value="' . $tname . '" />
                <a class="text-a-filter-items-1" onclick="loadDataToField(' . "'" . $n . "'"  . ',' . "'empu','empidu','employeeu'" . ')">' . $tname . '</a>
              ';
        $n++;
      }
    }
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

  <title>Manage College Dean</title>

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
          <div class="container-fluid" style="padding-left: 10px;padding-right: 10px;">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"></h1>
            </div>

            <!-- Content Row -->
            <div class="row">

                      <div class="col-sm-12" style="padding-left: 4px;padding-right: 4px;">

                        <div align="center">

                          <div class="col-sm-6" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Manage College Dean</span></h6>
                                </div>
                                
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">
                                
                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>

                                  <div class="table-responsive">
                                    <table class="table">
                                      <thead class="font-size-o-1">
                                        <tr style="font-size: 0.6rem;">
                                          <th class="" scope="col">Code</th>
                                          <th class="" scope="col">College</th>
                                          <th scope="col">Dean</th>
                                          <th scope="col"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="font-size-o-1">
                                        <?php
                                          $query = "SELECT * FROM srgb.college ORDER BY collname ";
                                          $result = pg_query($pgconn, $query);
                                          if ($result) {
                                            $n = 0;
                                            while ($row = pg_fetch_array($result)) {
                                              $n++;
                                              //
                                              $code = trim($row['collcode']);
                                              $desc = trim($row['collname']);
                                              $deanid = trim($row['colldean']);
                                              $deanname = "";
                                              //
                                              $squery = "SELECT * FROM pis.employee WHERE TRIM(UPPER(empid))=TRIM(UPPER('" . $deanid . "')) ";
                                              $sresult = pg_query($pgconn, $squery);
                                              if ($sresult) {
                                                while ($srow = pg_fetch_array($sresult)) {
                                                  $deanname = trim($srow['fullname']);
                                                }
                                              }
                                              //
                                              echo '
                                                  <tr style="">
                                                    <td style="">' . $code . '</td>
                                                    <td style="">' . $desc . '</td>
                                                    <td style="">' . $deanname . '</td>
                                                    <td style="text-align: right;"><button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" onclick="' . "loadCollegeDeanToField('" . $code . "','" . $deanid . "','" . $deanname . "','id','empid','employee');" . '" data-toggle="modal" data-target="#modalEdit">Update</button></td>
                                                  </tr>
                                              ';
                                              //
                                            }
                                          }
                                        ?>
                                      </tbody>
                                    </table>
                                  </div>



                                  <!-- Modal -->
                                  <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                      <div class="modal-content">
                                        <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                          <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Dean</h5>
                                          <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                          </button>
                                        </div>
                                          <form method="post">
                                        <div class="modal-body" style="font-size: 0.7rem;">
                                          <div align="left">

                                            <input type="hidden" id="id" name="id" value="" hidden />

                                            <input type="hidden"  name="empid" id="empid" value="" required>
                                            <div id="divHolder" class="div-text-filter-holder-main-1">
                                              <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="employee" id="employee" value=""  onkeyup="filterFunction('employee','empItems')" placeholder="Employee" onfocus="elementShowHide('empItems')" onclick="" required>
                                              <div id="empItems" class="div-text-filter-holder-1" style="display: none;">
                                                <div class="div-text-filter-holder-wrapper-1">
                                                  <?php
                                                    echo $gd_items_emp;
                                                  ?>
                                                </div>
                                              </div>
                                            </div>

                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                          <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnsave" value="Save" />
                                        </div>
                                          </form>
                                      </div>
                                    </div>
                                  </div>




                                
                              </div>
                            </div>

                          </div>
                          
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


  <?php include "footer-imports.php"; ?>


  <script>
    function loadCollegeDeanToField(id,empid,empname,targetID,targetEmpID,targetEmpName){
      try{
        document.getElementById(targetID).value = id;
        document.getElementById(targetEmpID).value = empid;
        document.getElementById(targetEmpName).value = empname;
      }catch(err){}
    }
  </script>


</body>

</html>
