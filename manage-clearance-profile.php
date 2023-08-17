<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  include "access_check.php";
  //
  if((strtolower(trim($log_user_type)) == strtolower(trim("student")) || (strtolower(trim($log_user_type)) == strtolower(trim("employee")) && $log_user_role_isadmin <= 0))) {
    echo '<meta http-equiv="refresh" content="0;URL=index.php" />';
    exit();
  }
  //
  //
  if(trim($log_userid)!="") {
    if($_POST['btnadd']) {
      $name = trim($_POST['name']);
      $description = trim($_POST['description']);
      $visible = trim($_POST['visible']);
      //
      $sy = trim($_POST['sy']);
      $sem = trim($_POST['sem']);
      //
      $forusertype = trim($_POST['forusertype']);
      //
      if(trim($visible) == "") {
        $visible = "0";
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($name) == "") {
        $errn++;
        $errmsg = $errmsg . "Name required. ";
      }
      $query0 = "SELECT * FROM tbl_clearance_profile where LOWER(TRIM(profilename))==LOWER(TRIM('" . $name . "')) AND LOWER(TRIM(sy))==LOWER(TRIM('" . $sy . "')) AND LOWER(TRIM(sem))==LOWER(TRIM('" . $sem . "')) AND LOWER(TRIM(forusertype))==LOWER(TRIM('" . $forusertype . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Same clearance details already added. ";
        }
      }
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "INSERT INTO tbl_clearance_profile (profilename,details,sy,sem,isvisible,addedby,forusertype) VALUES ('" . $name . "','" . $description . "','" . $sy . "','" . $sem . "','" . $visible . "','" . trim($log_userid) . "','" . $forusertype . "') ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Profile added.
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
    }
    //
    if($_POST['btnupdate']) {
      $id = trim($_POST['id']);
      $name = trim($_POST['name']);
      $description = trim($_POST['description']);
      $visible = trim($_POST['visible']);
      //
      $sy = trim($_POST['sy']);
      $sem = trim($_POST['sem']);
      //
      $forusertype = trim($_POST['forusertype']);
      //
      if(trim($visible) == "") {
        $visible = "0";
      }
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($name) == "") {
        $errn++;
        $errmsg = $errmsg . "Task required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tbl_clearance_profile set profilename='" . $name . "',details='" . $description . "',sy='" . $sy . "',sem='" . $sem . "',isvisible='" . $visible . "',forusertype='" . $forusertype . "' WHERE profileid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Profile updated.
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
        $query = "DELETE FROM tbl_clearance_profile  WHERE profileid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" style="font-size: 0.75rem; padding: 0.6rem;" role="alert">
            <strong></strong> Profile deleted.
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
    }
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

  <title>Clearance Profiles</title>

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

                          <div class="col-xl-7 col-lg-7 col-md-7" style="padding-left: 4px;padding-right: 4px;">

                            <div class="card shadow mb-4">
                              <div class="card-header py-3">
                                <div align="left">
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Profiles</span></h6>
                                </div>
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" style="font-size: 0.6rem; border-radius: 0px; border-top-right-radius: 16px; border-bottom-right-radius: 16px;" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                        <h5 class="modal-title" id="" style="font-size: 0.8rem;">Add New Profile</h5>
                                        <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body" style="font-size: 0.7rem;">
                                        <div align="left">

                                          <div class="form-group" >
                                            <span class="label1">Name: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="name" id="name" placeholder="Name" value="" required>
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Description: <span class="text-danger"></span></span>
                                            <textarea class="form-control form-control-user input-text-value font-size-o-1"  name="description" id="description" placeholder="Description"></textarea>
                                          </div>

                                          <div class="form-group" >
                                            <span class="label1">S.Y.: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user  input-text-value font-size-o-1"  name="sy" id="sy" placeholder="S.Y." value="" >
                                          </div>

                                          <div class="form-group margin-top1" >
                                            <span class="label1">Semester: <span class="text-danger"></span></span>
                                            <select class="form-control form-control-user  input-text-value font-size-o-1"  name="sem" id="sem" placeholder="Semester">
                                              <option value="">--- NONE ---</option>
                                              <option value="1">1</option>
                                              <option value="2">2</option>
                                              <option value="S">S</option>
                                            </select>
                                          </div>

                                          <div class="form-group" >
                                            <span class="label1">For User Type: <span class="text-danger"></span></span>
                                            <select class="form-control form-control-user  input-text-value font-size-o-1"  name="forusertype" id="forusertype" placeholder="">
                                              <option value="">All</option>
                                              <option value="employee">employee</option>
                                              <option value="student">student</option>
                                            </select>
                                          </div>

                                          <div class="form-group " >
                                            <div class="div-switch-holder-1">
                                              <div class="div-switch-label-1" style="">
                                               <span class="label1">Visible: <span class="text-danger"></span></span>
                                              </div>
                                              <div class="div-switch-1" style="margin-left: 60px;">
                                                <label class="switch">
                                                  <input type="checkbox" id="visible" name="visible" value="1">
                                                  <span class="slider round"></span>
                                                </label>
                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                        <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnadd" value="Save changes" />
                                      </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                                
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col" style="min-width: 80px; max-width: 80px; width: 80px;"></th>
                                    <th scope="col" style="min-width: 30px; max-width: 30px; width: 30px;">#</th>
                                    <th scope="col" style="min-width: 80px; max-width: 80px; width: 80px;">S.Y.</th>
                                    <th scope="col" style="min-width: 70px; max-width: 70px; width: 70px;">Semester</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" style="min-width: 100px; max-width: 100px; width: 100px;">For User Type</th>
                                    <th scope="col" style="min-width: 70px; max-width: 70px; width: 70px;">Visible</th>
                                    <th scope="col" style="min-width: 110px; max-width: 110px; width: 110px;"></th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    //
                                    //
                                    $tq = "";
                                    //
                                    $tq = " ( sy IS NULL ) OR ( sem IS NULL ) OR ( TRIM(sy)='' ) OR ( TRIM(sem)='' )  OR ( TRIM(UPPER(sy))=TRIM(UPPER('" . $log_user_active_sy . "')) AND TRIM(UPPER(sem))=TRIM(UPPER('" . $log_user_active_sem . "')) )";
                                    if(trim($tq) != "") {
                                      $tq = " WHERE " . $tq;
                                    }
                                    //
                                    $query = "SELECT * FROM tbl_clearance_profile " . $tq . " ORDER BY entrydate DESC";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                      $n = 0;
                                      while ($row = mysqli_fetch_array($result)) {
                                        $n++;
                                        //
                                        //
                                        $tvisible = trim($row['isvisible']);
                                        $tvisiblef = "No";
                                        $tvisible_c = "";
                                        if(trim(strtolower($tvisible))==trim(strtolower("1"))) {
                                          $tvisiblef = "Yes";
                                          $tvisible_c = " checked ";
                                        }
                                        //
                                        $taskcount = 0;
                                        $cquery = "SELECT COUNT(*) FROM tbl_clearance_profile_tasks where profileid='" . trim($row['profileid']) . "'";
                                        $cresult = mysqli_query($conn, $cquery);
                                        if ($result) {
                                          while ($crow = mysqli_fetch_array($cresult)) {
                                            if(trim($crow[0]) != "") {
                                              $taskcount = trim($crow[0]);
                                            }
                                          }
                                        }
                                        //
                                        // SEM
                                        $topt_sem = "";
                                        $tsem = trim($row['sem']);
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tsem)) == trim(strtolower(""))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_sem = $topt_sem . '<option value="" ' . $tsel . ' >--- NONE ---</option>';
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tsem)) == trim(strtolower("1"))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_sem = $topt_sem . '<option value="1" ' . $tsel . ' >1</option>';
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tsem)) == trim(strtolower("2"))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_sem = $topt_sem . '<option value="2" ' . $tsel . ' >2</option>';
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tsem)) == trim(strtolower("S"))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_sem = $topt_sem . '<option value="S" ' . $tsel . ' >S</option>';
                                        //
                                        // USER TYPE
                                        $topt_usertype = "";
                                        $tusertype = trim($row['forusertype']);
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tusertype)) == trim(strtolower(""))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_usertype = $topt_usertype . '<option value="" ' . $tsel . ' >All</option>';
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tusertype)) == trim(strtolower("employee"))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_usertype = $topt_usertype . '<option value="employee" ' . $tsel . ' >employee</option>';
                                        //
                                        $tsel = "";
                                        if(trim(strtolower($tusertype)) == trim(strtolower("student"))) {
                                          $tsel = " selected ";
                                        }
                                        $topt_usertype = $topt_usertype . '<option value="student" ' . $tsel . ' >student</option>';
                                        //
                                        //
                                        //
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                                    <h5 class="modal-title" id="" style="font-size: 0.8rem;">Update Profile</h5>
                                                    <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body" style="font-size: 0.7rem;">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['profileid']) . '" hidden />

                                                      <div class="form-group">
                                                        <span class="label1">Task: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user  input-text-value font-size-o-1" name="name" id="name" placeholder="Name" value="' . trim($row['profilename']) . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Description: <span class="text-danger"></span></span>
                                                        <textarea class="form-control form-control-user  input-text-value font-size-o-1" name="description" id="description" placeholder="Description">' . trim($row['details']) . '</textarea>
                                                      </div>

                                                      <div class="form-group">
                                                        <span class="label1">S.Y.: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user  input-text-value font-size-o-1" name="sy" id="sy" placeholder="S.Y." value="' . trim($row['sy']) . '" >
                                                      </div>

                                                      <div class="form-group margin-top1" >
                                                        <span class="label1">Semester: <span class="text-danger"></span></span>
                                                        <select class="form-control form-control-user  input-text-value font-size-o-1"  name="sem" id="sem" placeholder="Semester">
                                                          ' . $topt_sem . '
                                                        </select>
                                                      </div>

                                                      <div class="form-group" >
                                                        <span class="label1">For User Type: <span class="text-danger"></span></span>
                                                        <select class="form-control form-control-user  input-text-value font-size-o-1"  name="forusertype" id="forusertype" placeholder="">
                                                          ' . $topt_usertype . '
                                                        </select>
                                                      </div>

                                                      <div class="form-group" >
                                                        <div class="div-switch-holder-1">
                                                          <div class="div-switch-label-1" style="">
                                                           <span class="label1">Visible: <span class="text-danger"></span></span>
                                                          </div>
                                                          <div class="div-switch-1" style="margin-left: 60px;">
                                                            <label class="switch">
                                                              <input type="checkbox" id="visible" name="visible" value="1" ' . $tvisible_c . '>
                                                              <span class="slider round"></span>
                                                            </label>
                                                          </div>
                                                        </div>
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1" style="padding-top: 12px;">
                                                    <h5 class="modal-title" id="" style="font-size: 0.8rem;">Delete Profile</h5>
                                                    <button type="button" class="close color-white-1" style="padding-top: 4px; padding-bottom: 8px; margin-top: -12px;" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true" style="font-size: 1.1rem;">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body" style="font-size: 0.7rem;">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['profileid']) . '" hidden />

                                                      Delete <b>' . trim($row['profilename']) . '</b> ?

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-danger font-size-o-1" style="border-radius: 0px; font-size: 0.6rem;" name="btndelete" value="Delete" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <th scope="row" class="table-row-width-1">
                                              <button type="button" class="btn btn-success btn-table-1" style="border-radius: 0px;" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button>
                                              <button type="button" class="btn btn-danger btn-table-1" style="border-radius: 0px;" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="table-row-width-2">' . $n . '</th>
                                            <td>' . trim($row['sy']) . '</td>
                                            <td>' . trim($row['sem']) . '</td>
                                            <td>' . trim($row['profilename']) . '</td>
                                            <td>' . trim($row['details']) . '</td>
                                            <td>' . trim($row['forusertype']) . '</td>
                                            <td>' . trim($tvisiblef) . '</td>
                                            <td><a href="./manage-clearance-profile-task.php?pid=' . trim($row['profileid']) . '">Manage Tasks [' . $taskcount . ']</a></td>
                                          </tr>
                                        ';
                                      }
                                    }
                                    
                                  ?>
                                </tbody>
                              </table>
                              </div>

                                <hr>
                                
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


</body>

</html>
