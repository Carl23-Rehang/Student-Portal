

        <!-- Topbar -->
        <nav class="navbar1 navbar-expand1 bg-1 shadow header-l-1">
        </nav>
        <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

          <div class="row nav-row1">
              <div class="col-lg-8">
                <div class="nav-div1" align="left">
                  <a href="index.php">
                  <img class="header-logo2" src="img/header_logo.png">
                  </a>
                </div>
              </div>
              
          </div>

          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">

            </li>


            <?php
              if (strtolower(trim($log_user_type)) == strtolower(trim("employee"))) {
                echo '
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                      <a class="nav-link dropdown-toggle c-link-g-1" id="userDropdown2" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div align="right">
                          <span class="text-gray-600 small" style="display: block; text-align: left; font-size: 0.7rem;"><b>Active:</b></span>
                          <span class="text-gray-600 small" style="display: block; text-align: left; margin: 0px; padding: 0px;"><b>' . $log_user_active_sy . '</b></span>
                          <span class="text-gray-600 small" style="display: block; text-align: left; font-size: 0.6rem; margin: 0px; padding: 0px; margin-top: -7px;"><b>' . $gc_semt . '</b></span>
                        </div>
                      </a>
                ';
              }
            ?>

              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" style="border-radius: 0px; padding: 8px 8px; width: 190px;" aria-labelledby="userDropdown2">
                <div align="center">
                  <form method="post">
                    <input type="number" class="c-input-1" style="display: inline-block; width: 80px;" id="syf" name="syf" min="0" value="<?php echo $gc_syf; ?>" onchange="updateSYTo('syf','syt')" onkeyup="updateSYTo('syf','syt')" />
                    <input type="number" class="c-input-1" style="display: inline-block; width: 80px;" id="syt" name="syt" min="0" value="<?php echo $gc_syt; ?>" disabled />
                    <select class="c-input-1" style="display: inline-block; width: 164px;" id="sem" name="sem">
                      <?php echo $gc_sem_opt; ?>
                    </select>
                    <input type="submit" class="btn btn-primary bg-2" style="width: 100%; border-radius: 0px; width: 164px; font-size: 0.6rem; margin-top: 4px;" id="btnupdatesysem" name="btnupdatesysem" value="Update" />
                  </form>
                  <script>
                    function updateSYTo(syf, syt) {
                      try{
                        var tsyf = parseInt(document.getElementById(syf).value);
                        document.getElementById(syt).value = tsyf + 1;
                      }catch(err){}
                    }
                  </script>
                </div>
              </div>
            </li>

            <?php
              //
              if(trim($log_userid) != "") {
                //
                echo '
                  <div class="topbar-divider d-none d-sm-block"></div>

                  <!-- Nav Item - User Information -->
                  <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $log_user_dn; ?></span>
                      <img class="img-profile rounded-circle img-avatar-1" src="' . $log_user_photo . '" >
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                      <a class="dropdown-item" href="profile.php?t=' . trim($log_user_type) . '&id=' . trim($log_userid) . '" >
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        My Account
                      </a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="logout.php">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                      </a>
                    </div>
                  </li>
                ';
                //
              }
              //
            ?>


          </ul>

        </nav>
        <!-- End of Topbar -->
        <div class="nav-top2 shadow-bottom" style="padding-left: 12px;" align="left">


          <?php
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
            //GET ADMIN ROLE ID
            $tquery1 = "SELECT * FROM tbluserroles WHERE TRIM(LOWER(userid))='" . strtolower(trim($log_userid)) . "' AND TRIM(LOWER(usertype))='" . strtolower(trim("employee")) . "' AND TRIM(LOWER(userrole))='" . strtolower(trim($adminroleid)) . "' AND active='1' ";
            $tresult1 = mysqli_query($conn, $tquery1);
            if ($tresult1) {
              while ($trow1 = mysqli_fetch_array($tresult1)) {
                $isadmin = 1;
              }
            }
            //
            //echo $adminroleid . " " . $isadmin;
            //
            if (strtolower(trim($log_user_type)) == strtolower(trim("employee"))) {
              
              echo '

                <div class="dropdown nav-top1-dropdown-1">
                  <a class="" href="#" role="button" id="menuAcademic" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-top1-linkdiv-1">
                      <i class="fas fa-file-alt header-icon-1"></i> ACADEMIC
                    </div>
                  </a>
                  <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuAcademic">
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="subjectload.php">Subject Load</a>
                    <div class="dropdown-item-divider-1"></div>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="grading-module.php">Grading Module</a>
                  </div>
                </div>


                ';

              // ENROLLMENT
              $tp = '';
              if($log_user_sem_enroll_view > 0) {
                $tp = '
                  <div class="dropdown nav-top1-dropdown-1">
                    <a class="" href="#" role="button" id="menuSemEnroll" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-top1-linkdiv-1">
                        <i class="fas fa-sign header-icon-1"></i> ENROLLMENT
                      </div>
                    </a>
                    <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuSemEnroll">
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage_semester_enrollee.php"> View Enrollee</a>
                    </div>
                  </div>
                ';
                echo $tp;
              }

              $canuserequests = 0;
              $tad = "";
              if ($log_user_role_isadmin > 0) {
                $tad = '
                    <div class="dropdown-item-divider-1"></div>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-request-types.php">Manage Request Types</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-request-groups.php">Manage Request Groups</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-request-status.php">Manage Request Status</a>
                ';
                $canuserequests = 1;
              }
              if (trim(strtolower($log_user_role_code)) == trim(strtolower("REG"))) {
                $canuserequests = 1;
              }
              //
              if ($canuserequests > 0) {
                echo '
                  <div class="dropdown nav-top1-dropdown-1">
                    <a class="" href="#" role="button" id="menuHRIS" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-top1-linkdiv-1">
                        <i class="fas fa-list header-icon-1"></i> REQUEST
                      </div>  
                    </a>
                    <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuHRIS">
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="requests.php">Requests</a>
                      ' . $tad . '
                    </div>
                  </div>
                ';
              }


              $canuseclearance = 1;
              $tad = "";
              if ($log_user_role_isadmin > 0) {
                $tad = '
                    <div class="dropdown-item-divider-1"></div>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance.php">Manage Clearance</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance-tasklist.php">Manage Task List</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance-profile.php">Manage Profiles</a>
                ';
                $canuseclearance = 1;
              }
              if (trim(strtolower($log_user_role_code)) == trim(strtolower("REG"))) {
                $canuseclearance = 1;
              }
              //
              if ($canuseclearance > 0 && ($setting_clearance_allowed > 0 || ($setting_clearance_allowed <= 0 && $log_user_role_isadmin > 0))) {
                echo '
                  <div class="dropdown nav-top1-dropdown-1">
                    <a class="" href="#" role="button" id="menuClearance" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-top1-linkdiv-1">
                        <i class="fas fa-list header-icon-1"></i> CLEARANCE
                      </div>  
                    </a>
                    <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuClearance">
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="clearance.php">Clearance</a>
                      ' . $tad . '
                    </div>
                  </div>
                ';
              }



              if($isadmin > 0){
                echo '
                  <div class="dropdown nav-top1-dropdown-1">
                    <a class="" href="#" role="button" id="menuAdminManage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-top1-linkdiv-1">
                        <i class="fas fa-edit header-icon-1"></i> MANAGE
                      </div>
                    </a>
                    <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuAdminManage">
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-users.php">Manage Users</a>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-users-role.php">Manage User Roles</a>
                      <div class="dropdown-item-divider-1"></div>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-blocked-users.php">Manage Blocked User</a>
                      <div class="dropdown-item-divider-1"></div>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-lrn-exemption-programs.php">Manage LRN Exemption Programs</a>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-program-classification.php">Manage Program Classification</a>
                      <div class="dropdown-item-divider-1"></div>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-department-chairman.php">Manage Department Chairman</a>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-college-dean.php">Manage College Dean</a>
                      <div class="dropdown-item-divider-1"></div>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="search-student.php">Search Student</a>
                      <div class="dropdown-item-divider-1"></div>
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-settings.php">Manage Settings</a>
                    </div>
                  </div>
                ';
              }

              
            } //END EMPLOYEE
            //
            // STUDENT
            if (strtolower(trim($log_user_type)) == strtolower(trim("student"))) {
              //
              //
              $tmclearance = "";
              //
              $canuseclearance = 1;
              $tad = "";
              if ($log_user_role_isadmin > 0) {
                $tad = '
                    <div class="dropdown-item-divider-1"></div>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance.php">Manage Clearance</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance-tasklist.php">Manage Task List</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="manage-clearance-profile.php">Manage Profiles</a>
                ';
                $canuseclearance = 1;
              }
              if (trim(strtolower($log_user_role_code)) == trim(strtolower("REG"))) {
                $canuseclearance = 1;
              }
              //
              if ($canuseclearance > 0 && $setting_clearance_allowed > 0) {
                $tmclearance = '
                  <div class="dropdown nav-top1-dropdown-1">
                    <a class="" href="#" role="button" id="menuClearance" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="nav-top1-linkdiv-1">
                        <i class="fas fa-list header-icon-1"></i> CLEARANCE
                      </div>  
                    </a>
                    <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuClearance">
                      <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="clearance.php">Clearance</a>
                      ' . $tad . '
                    </div>
                  </div>
                ';
              }

              $tp = '';
              if($setting_enrollment_enabled > 0 && $setting_enrollment_show > 0) {
                $tp = '
                    <div class="dropdown nav-top1-dropdown-1">
                      <a class="" href="#" role="button" id="menuEnrollment" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="nav-top1-linkdiv-1">
                          <i class="fa fa-sign header-icon-1"></i> ENROLLMENT
                        </div>
                      </a>
                      <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuEnrollment">
                        <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="semester_enroll.php">Enroll Now</a>
                      </div>
                    </div>
                ';
              }

              //
              echo '

                <div class="dropdown nav-top1-dropdown-1">
                  <a class="" href="#" role="button" id="menuAcademic" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-top1-linkdiv-1">
                      <i class="fas fa-file-alt header-icon-1"></i> ACADEMIC
                    </div>
                  </a>
                  <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuAcademic">
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="grades.php">Grades</a>
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="scholarship.php">Scholarship</a>
                  </div>
                </div>

                ' . $tp . '

              <div class="dropdown nav-top1-dropdown-1">
                <div class="dropdown nav-top1-dropdown-1">
                  <a class="" href="#" role="button" id="menuRequest" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-top1-linkdiv-1">
                      <i class="fas fa-list header-icon-1"></i> REQUEST
                    </div>
                  </a>
                  <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuRequest">
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="requests.php">Requests</a>
                  </div>
                </div>
                <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuRequest">
                  <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="subjectload.php">Subject Load</a>
                  <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="#">Clearance</a>
                </div>
              </div>

              ' . $tmclearance . '

                <div class="dropdown nav-top1-dropdown-1">
                  <a class="" href="#" role="button" id="menuAssessment" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="nav-top1-linkdiv-1">
                      <i class="fas fa-file-invoice header-icon-1"></i> ASSESSMENT
                    </div>
                  </a>
                  <div class="dropdown-menu nav-top1-dropdown-content-1" aria-labelledby="menuAssessment">
                    <a class="dropdown-item nav-top1-dropdown-content-item-1 header-dropdown-item-1" href="assessment.php">Assessment</a>
                  </div>
                </div>

              ';
              //
            } //END STUDENT
            //
          ?>


        </div>

