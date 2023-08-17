<?php session_start(); include "connect.php"; error_reporting(0);
  include "gvars.php";
  //
  if(trim($log_userid)!="") {
    //
    //
    //
    if($_POST['btnadd']) {
      $status = trim($_POST['status']);
      $description = trim($_POST['description']);
      $level = trim($_POST['level']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($status) == "") {
        $errn++;
        $errmsg = $errmsg . "Status required. ";
      }
      if(trim($level) == "") {
        $errn++;
        $errmsg = $errmsg . "Level required. ";
      }
      $query0 = "SELECT * FROM tblrequeststatus where LOWER(TRIM(status))=LOWER(TRIM('" . $status . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Status already added. ";
        }
      }
      $query0 = "SELECT * FROM tblrequeststatus where LOWER(TRIM(level))=LOWER(TRIM('" . $level . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Level already added. ";
        }
      }
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "INSERT INTO tblrequeststatus (status,description,level) VALUES ('" . $status . "','" . $description . "','" . $level . "') ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Status added.
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
      $status = trim($_POST['status']);
      $description = trim($_POST['description']);
      $level = trim($_POST['level']);
      //
      $errn = 0;
      $errmsg = "";
      //
      if(trim($status) == "") {
        $errn++;
        $errmsg = $errmsg . "Status required. ";
      }
      if(trim($level) == "") {
        $errn++;
        $errmsg = $errmsg . "Level required. ";
      }
      //
      if($errn <= 0) {
        //echo $id . "  " . $group . "  " . $description;
        $query = "UPDATE tblrequeststatus set status='" . $status . "',description='" . $description . "',level='" . $level . "' WHERE statusid='" . $id . "' ";
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
        $query = "DELETE FROM tblrequeststatus  WHERE statusid='" . $id . "' ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Status deleted.
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
    //
    //CHECK IF NO LEVEL 0 STATUS
    $errmsg = "";
    //
    $rcount = 0;
    $query0 = "SELECT * FROM tblrequeststatus where LOWER(TRIM(level))=LOWER(TRIM('0'))";
    $result0 = mysqli_query($conn, $query0);
    if ($result0) {
      $rcount = mysqli_num_rows($result0);
    }
    if($rcount <= 0) {
      $errmsg = $errmsg . "There is no status level 0 (Pending). ";
    }
    $rcount = 0;
    $query0 = "SELECT * FROM tblrequeststatus where LOWER(TRIM(level))=LOWER(TRIM('-1'))";
    $result0 = mysqli_query($conn, $query0);
    if ($result0) {
      $rcount = mysqli_num_rows($result0);
    }
    if($rcount <= 0) {
      $errmsg = $errmsg . "There is no status level -1 (Cancelled). ";
    }
    //
    if(trim($errmsg)!="") {
      $gerr = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Error!</strong> ' . $errmsg . '
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        ';
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

  <title>Request Status</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Request Status</span></h6>
                                </div>
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                <div align="left">

                                  <?php
                                    echo $gerr;
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>CREATE STATUS</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1">
                                        <h5 class="modal-title" id="">Add New Status</h5>
                                        <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <div class="form-group margin-top1">
                                            <span class="label1">Status: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="status" id="status" placeholder="Status" value="" required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Description: <span class="text-danger"></span></span>
                                            <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description"></textarea>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Level: <span class="text-danger"></span></span>
                                            <input type="number" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="level" id="level" placeholder="Level" value="1" required>
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

                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                <thead class="thead-1 font-size-o-1">
                                  <tr style="font-size: 0.6rem;">
                                    <th scope="col" style="min-width: 70px;"></th>
                                    <th scope="col">#</th>
                                    <th scope="col">Level</th>
                                    <th scope="col" style="max-width: 100px;">Status</th>
                                    <th scope="col">Description</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    
                                    $query = "SELECT * FROM tblrequeststatus ORDER BY level ASC,status ASC";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                      $n = 0;
                                      while ($row = mysqli_fetch_array($result)) {
                                        $n++;
                                        $fm = '
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalEdit_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" id="">Update Group</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['statusid']) . '" hidden />

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Status: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="status" id="status" placeholder="Status" value="' . trim($row['status']) . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Description: <span class="text-danger"></span></span>
                                                        <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description">' . trim($row['description']) . '</textarea>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Level: <span class="text-danger"></span></span>
                                                        <input type="number" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="level" id="level" placeholder="Level" value="' . trim($row['level']) . '" required>
                                                      </div>

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-primary bg-2 font-size-o-1" name="btnupdate" value="Save changes" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDelete_' . $n . '" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header bg-3 color-white-1 modal-header-1">
                                                    <h5 class="modal-title" id="">Delete Group</h5>
                                                    <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                    <form method="post">
                                                  <div class="modal-body">
                                                    <div align="left">

                                                      <input type="hidden" name="id" value="' . trim($row['statusid']) . '" hidden />

                                                      Delete <b>' . trim($row['status']) . '</b> ?

                                                    </div>
                                                  </div>
                                                  <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary font-size-o-1" data-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-danger bg-2 font-size-o-1" name="btndelete" value="Delete" />
                                                  </div>
                                                    </form>
                                                </div>
                                              </div>
                                            </div>
                                        ';
                                        echo '
                                          <tr style="font-size: 0.7rem;">
                                            <th scope="row" class="table-row-width-1">
                                              <button type="button" class="btn btn-success btn-table-1" data-toggle="modal" data-target="#modalEdit_' . $n . '">Edit</button>
                                              <button type="button" class="btn btn-danger btn-table-1" data-toggle="modal" data-target="#modalDelete_' . $n . '">Delete</button>
                                              ' . $fm . '
                                            </th>
                                            <td class="table-row-width-2">' . $n . '</th>
                                            <td>' . trim($row['level']) . '</td>
                                            <td>' . trim($row['status']) . '</td>
                                            <td>' . trim($row['description']) . '</td>
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


</body>

</html>
