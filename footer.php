
      <!-- Footer -->
      <footer class="footer1">
        
        <div class="row">

	    	<div class="col-md-3">
				<div align="center">
					
				</div>
	    	</div>
	      
	    	<div class="col-md-3">
				<div align="center">
					
				</div>
	    	</div>
	      
	    	<div class="col-md-3">
				<div align="center">

				</div>
	    	</div>
	      
			<div class="col-md-3">
				<div align="center">
					<div>
						<img class="footer-logo1" src="img/dssc_logo.png">
					</div>
					<div>
						
					</div>
				</div>
			</div>

		</div>

      </footer>

      <div class="footer1-2">
      	
      </div>
      <!-- End of Footer -->

      <?php 
      	if($setting_lrn_show_popup == 1) {
	      	echo '
			      <!-- Modal -->
			      <div class="modal fade" id="modalLRN" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			        <div class="modal-dialog modal-dialog-centered" role="document">
			          <div class="modal-content">
			            <div class="modal-header bg-3 color-white-1 modal-header-1">
			              <h5 class="modal-title" style="font-size: 0.7rem;" id="">Enter your Learner' . "'s" . ' Reference Number (LRN)</h5>
			            </div>
			              <form method="post">
			            <div class="modal-body">
			              <div align="left">

			                <div class="form-group margin-top1">

			                  <span class="label1" style="font-size: 0.6rem;">Learner' . "'s" . ' Reference Number (LRN): <span class="text-danger"></span></span>
			                  <input type="text" class="form-control form-control-user text-capitalize input-text-value font-size-o-1" style="border-radius: 0px;" name="lrn" id="lrn" placeholder="Learner' . "'s" . ' Reference Number (LRN)" />
			                  
			                  <span class="label1 text-danger" style="font-size: 0.6rem;"><span class="text-danger">You are required to submit a valid LRN. This will be used by the registrar' . "'s" . ' office.<br/>This is not your student ID. Please input a valid LRN, this will be used to track your academic records, and on submission for your scholarschip.</span></span>
			                </div>

			              </div>
			            </div>
			            <div class="modal-footer">

			            	<a href="logout.php" class="btn btn-secondary font-size-o-1" style="font-size: 0.6rem; border-radius: 0px; min-width: 80px; position: absolute; left: 0; margin-left: 16px;">Logout</a>

			            	<input type="submit" class="btn btn-primary bg-2 font-size-o-1" style="font-size: 0.6rem; border-radius: 0px; min-width: 80px;" name="btnlrnsave" value="Save LRN" />

			            </div>
			              </form>
			          </div>
			        </div>
			      </div>
	      	';
      	}
      	if($setting_assessment_check_show_popup == 1) {
      		//
      		//
	      	echo '
			      <!-- Modal -->
			      <div class="modal fade" id="modalAssessmentCheck" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
			        <div class="modal-dialog modal-dialog-centered" role="document">
			          <div class="modal-content">
			            <div class="modal-header bg-3 color-white-1 modal-header-1">
			              <h5 class="modal-title" style="font-size: 0.7rem;" id="">Assessment Alert</h5>
			            </div>
			              <form method="post">
			            <div class="modal-body">
			              <div align="left">

			                <div class="form-group margin-top1">

		                        <div class="table-responsive">
		                        <table class="table table-striped table-hover">
		                          <thead class="thead-1 font-size-o-1">
		                            <tr style="font-size: 0.6rem;">
		                              <th scope="col">#</th>
		                              <th scope="col">S.Y.</th>
		                              <th scope="col">Semester</th>
		                              <th>Total Assessment</th>
		                              <th>Total Paid</th>
		                            </tr>
		                          </thead>
		                          <tbody class="font-size-o-1">
		                            ' . $setting_assessment_check_popup_msg . '
		                          </tbody>
		                        </table>
		                        </div>

			                	<span class="label1 text-danger" style="font-size: 0.6rem;"><span class="text-danger">You need to pay first your old accounts to use the system. You can pay online by clicking the link below.</span></span>

			                	<div align="center">
			                	<a href="https://www.lbp-eservices.com/egps/portal/index.jsp"><img src="img/lb_bliz.png" /></a>
			                	</div>

			                </div>

			              </div>
			            </div>
			            <div class="modal-footer" style="padding-top: 30px; padding-bottom: 30px;">

			            	<a href="logout.php" class="btn btn-secondary font-size-o-1" style="font-size: 0.6rem; border-radius: 0px; min-width: 80px; position: absolute; left: 0; margin-left: 16px;">Logout</a>

			            </div>
			              </form>
			          </div>
			        </div>
			      </div>
	      	';
      	}
      ?>