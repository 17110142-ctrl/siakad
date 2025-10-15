
        <script src="../botstrap-login/js/jquery.form.min.js"></script>
        <script src="../botstrap-login/js/bootstrap.min.js"></script>
        <script src="../botstrap-login/js/popper.min.js"></script>	
		<script src="../assets/plugins/perfectscroll/perfect-scrollbar.min.js"></script>
        <script src="../assets/plugins/pace/pace.min.js"></script>
        <script src="../botstrap-login/js/wow.min.js"></script>
        <script src='../assets/izitoast/js/iziToast.min.js'></script>
        <script src="../botstrap-login/js/front.min.js"></script>
		<script src="../assets/js/main.min.js"></script>
         <script src="../assets/js/custom.js"></script>
		 
       <script>
  $(document).ready(function() {
	var elapsedTime = 0;
	var interval = setInterval(function() {
	  timer()
	}, 10);

	function progressbar(percent) {
	  document.getElementById("bar1").style.width = percent + '%';
	  document.getElementById("percent1").innerHTML = percent + '%';
	}

	function timer() {
	  if (elapsedTime > 100) {
      var RDMData = decodeURIComponent(getCookie("rdmData"));
      if(RDMData !==""){
        var login = JSON.parse(RDMData);
        if(login.status !==""){
          clearInterval(interval);
          window.location.href="./"+login.status;
        }
      }
      if (elapsedTime >= 107) {
        clearInterval(interval);
        $(".pre-loader").hide();
      }
	  } else {
		  progressbar(elapsedTime);
	  }
	  elapsedTime++;
	}
	//setTimeout(function(){ $(".pre-loader").hide(); }, 2000);
  function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }	


});

  </script>
    </body>
</html>

			 
