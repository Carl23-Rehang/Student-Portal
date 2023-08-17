<?php session_start(); include "connect.php"; //error_reporting(0);
  include "gvars.php";
  //
  if(trim($log_userid)!="") {
    if($_POST['btnadd']) {
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
      $query0 = "SELECT * FROM tblrequestgroup where LOWER(TRIM(group))==LOWER(TRIM('" . $group . "'))";
      $result0 = mysqli_query($conn, $query0);
      if ($result0) {
        $rowcount = mysqli_num_rows($result0);
        if($rowcount > 0) {
          $errn++;
          $errmsg = $errmsg . "Group already added. ";
        }
      }
      //
      if($errn <= 0) {
        //echo $group . "  ". $description;
        $query = "INSERT INTO tblrequestgroup (groupname,description) VALUES ('" . $group . "','" . $description . "') ";
        $result = mysqli_query($conn,$query);
        //echo $result;
        //
        $dr = '
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Group added.
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

  <title>Request Types</title>

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
                                  <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.7rem;"><span class="color-blue-1">Request Groups</span></h6>
                                </div>
                              </div>
                              <div class="card-body" style="padding-left: 4px;padding-right: 4px;">

                                <div align="left">

                                  <?php
                                    echo $dr;
                                  ?>
                                
                                  <button type="button" class="btn btn-success btn-1 btn-width-min-1" data-toggle="modal" data-target="#modalAdd"><i class="fas fa-plus fa-sm"></i> <b>ADD</b></button>

                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header bg-3 color-white-1 modal-header-1">
                                        <h5 class="modal-title" id="">Add New Group</h5>
                                        <button type="button" class="close color-white-1" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                        <form method="post">
                                      <div class="modal-body">
                                        <div align="left">

                                          <div class="form-group margin-top1">
                                            <span class="label1">Group: <span class="text-danger"></span></span>
                                            <input type="text" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="group" id="group" placeholder="Group" value="" required>
                                          </div>

                                          <div class="form-group margin-top1">
                                            <span class="label1">Description: <span class="text-danger"></span></span>
                                            <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description"></textarea>
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
                                    <th scope="col"></th>
                                    <th scope="col">#</th>
                                    <th scope="col">Group</th>
                                    <th scope="col">Description</th>
                                  </tr>
                                </thead>
                                <tbody class="font-size-o-1">
                                  <?php
                                    
                                    $query = "SELECT * FROM tblrequestgroup ORDER BY groupname ASC";
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

                                                      <input type="hidden" name="id" value="' . trim($row['requestgroupid']) . '" hidden />

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Group: <span class="text-danger"></span></span>
                                                        <input type="text" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="group" id="group" placeholder="Group" value="' . trim($row['groupname']) . '" required>
                                                      </div>

                                                      <div class="form-group margin-top1">
                                                        <span class="label1">Description: <span class="text-danger"></span></span>
                                                        <textarea class="form-control form-control-user text-capitalize input-text-value font-size-o-1" name="description" id="description" placeholder="Description">' . trim($row['description']) . '</textarea>
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

                                                      <input type="hidden" name="id" value="' . trim($row['requestgroupid']) . '" hidden />

                                                      Delete <b>' . trim($row['groupname']) . '</b> ?

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
                                            <td>' . trim($row['groupname']) . '</td>
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
