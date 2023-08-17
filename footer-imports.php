
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <script>
  	$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
	  if (!$(this).next().hasClass('show')) {
	    $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
	  }
	  var $subMenu = $(this).next('.dropdown-menu');
	  $subMenu.toggleClass('show');


	  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
	    $('.dropdown-submenu .show').removeClass('show');
	  });


	  return false;
	});
  </script>

  <script>

  	function checkLRN(){
	  	var reqlrn = "<?php echo $setting_lrn_show_popup; ?>";
	  	try{
	  		if(reqlrn == "1") {
	  			$('#modalLRN').modal({
				    backdrop: 'static',
				    keyboard: false
				});
	  			$('#modalLRN').modal('show');
	  		}
	  	}catch(err){}
  	}

  	function checkAssessment(){
	  	var reqlrn = "<?php echo $setting_assessment_check_show_popup; ?>";
	  	try{
	  		if(reqlrn == "1") {
	  			$('#modalAssessmentCheck').modal({
				    backdrop: 'static',
				    keyboard: false
				});
	  			$('#modalAssessmentCheck').modal('show');
	  		}
	  	}catch(err){}
  	}

  	window.onload = function() {
  		checkLRN();
  		checkAssessment();
  	};

  </script>


	<script>
	$(document).ready(function(){
	  $('[data-toggle="tooltip"]').tooltip();
	});
	</script>


  <script>

    var cdd_tag = "";
    var cdd_tag_id = "";
    var cdd_tag_name = "";

    function elementShowHide(id){
      try{
        var te = document.getElementById(id);
        if (te.style.display === "none") {
          te.style.display = "block";
          //
          try{
            cdd_tag = id;
          }catch(err){}
          //
        } else {
          te.style.display = "none";
        }
      }catch(err){}
    }
    function elementShowHide2(id,tid,tname){
      try{
        cdd_tag_id = tid;
        cdd_tag_name = tname;
        elementShowHide(id);
      }catch(err){}
    }
    function elementHide(id){
      try{
        var te = document.getElementById(id);
        if (te.style.display === "none") {
          
        } else {
          te.style.display = "none";
        }
      }catch(err){}
    }
    function filterFunction(idin,idholder) {
      var input, filter, ul, li, a, i;
      input = document.getElementById(idin);
      filter = input.value.toUpperCase();
      div = document.getElementById(idholder);
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }
    function loadTaskDataToField(source, target1, target2) {
      try{
          document.getElementById(target1).value = document.getElementById("task_id_" + source).value;
      }catch(err){}
      try{
          document.getElementById(target2).value = document.getElementById("task_name_" + source).value;
      }catch(err){}
      try{
          elementShowHide('taskItems');
      }catch(err){}
    }
    function loadDataToField(source, sourcetype, target1, target2) {
      try{
          document.getElementById(target1).value = document.getElementById(sourcetype.trim() + "_id_" + source).value;
      }catch(err){}
      try{
          document.getElementById(target2).value = document.getElementById(sourcetype.trim() + "_name_" + source).value;
      }catch(err){}
      //
      try{
          document.getElementById(cdd_tag_id).value = document.getElementById(sourcetype.trim() + "_id_" + source).value;
      }catch(err){}
      try{
          document.getElementById(cdd_tag_name).value = document.getElementById(sourcetype.trim() + "_name_" + source).value;
      }catch(err){}
      //
      try{
          elementHide(sourcetype.trim() + 'Items');
      }catch(err){}
      try{
          elementHide(cdd_tag);
      }catch(err){}
    }




    function checkEmptyRequiredInput(){
      var elements = document.forms['admissionform'].elements;
      var te = 0;
      for (i=0; i<elements.length; i++){
        if(elements[i].hasAttribute('required')) {
          if(elements[i].value == null || elements[i].value.trim() == "") {
            te++;
          }
        }
      }
      return te;
    }

    function checkEmptyRequiredInput2(target,btn){
      var elements = document.forms[target].elements;
      var te = 0;
      for (i=0; i<elements.length; i++){
        if(elements[i].hasAttribute('required')) {
          if(elements[i].value == null || elements[i].value.trim() == "") {
            te++;
          }
        }
      }
      //
      var tbtn = null;
      try{
        tbtn = document.getElementById(btn);
        if(tbtn != null) {
          tbtn.disabled = true;
          if(te <= 0) {
            tbtn.disabled = false;
          }else{
            tbtn.disabled = true;
          }
        }
      }catch(err){}
      //
    }


  function updateMunicipality(src,target) {

    var dsrc = document.getElementById(src).value;

    var dtar = document.getElementById(target);

    if(dsrc != null && dtar != null) {


      var cs = 'available-municipality.php?s=' + dsrc + '';

      $.get(cs, function(data) {
        if(data != null && data.trim() != "") {
          dtar.innerHTML = data;
        }

      });

    }

  }

  function updateBarangay(src,target) {

    var dsrc = document.getElementById(src).value;

    var dtar = document.getElementById(target);

    if(dsrc != null && dtar != null) {


      var cs = 'available-barangay.php?s=' + dsrc + '';

      $.get(cs, function(data) {
        if(data != null && data.trim() != "") {
          dtar.innerHTML = data;
        }

      });

    }

  }


    function calculateAge() {
      var bdate_month = document.getElementById("bdate_month").value;
      var bdate_day = document.getElementById("bdate_day").value;
      var bdate_year = document.getElementById("bdate_year").value;
      //var tbd = bdate.split("-");
      var today = new Date();
      var tmonth = today.getMonth() + 1;
      var tday = today.getDate();
      var tage = today.getFullYear() - bdate_year;
      //alert(tage);
      if(bdate_month > tmonth) {
        tage = tage - 1;
      }else{
        if(tmonth == bdate_month) {
          if(bdate_day > tday) {
            tage = tage - 1;
          }
        }
      }
      if(tage < 0) {
        tage = 0;
      }
      document.getElementById("age").value = tage;
    }

    function preview_image(event, target) 
    {
      var reader = new FileReader();
      reader.onload = function()
      {
        var output = document.getElementById(target);
        output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }

    function FormSubmit(target) {
      try{
        //
        var tar = document.getElementById(target);
        tar.submit();
        //
      }catch(err){}
    }

    function FormButtonClick(target) {
      try{
        //
        var tar = document.getElementById(target);
        tar.click();
        //
      }catch(err){}
    }


  </script>

