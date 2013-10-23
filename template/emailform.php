<center>



<div class="fluid-container">
		<script src="js/jquery.validate.min.js"></script>
		<script src="js/jquery.maskedinput.min.js"></script>
	<div class="form-signin" style='max-width:400px; padding-left:1%; padding-right:1%;'>
      <form  id="emailform"  class="padding: 19px 29px 29px;" method="GET" action="index.php?a=services&">
	  <h2>
		IgLou Internet Services<br>
        <small>
			Notification System
		</small>
	  </h1>
		<input type="hidden" name="a" value="services">
		<input type="hidden" style='display:none' id='l_p' name="l_p">
		<input type="hidden" id='l_t' name="l_t" value="email">
		
		<div class="input-prepend input-append">
  <div class="btn-group" >
    <button class="btn dropdown-toggle" style='width:100px'  data-toggle="dropdown">
      <span id='l_type'>Email</span>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style='min-width:100px;'>
		<li ><a href='#' id='lemail'>Email</a></li>
		<li ><a href='#' id='ltxt'>Text</a></li>
    </ul> 
  </div>
  <input type="email" name="email" id="PrependedDropdownButton" style='width:220px; margin-top:0px;!important' required="required" class=" span2 email" placeholder="Email">
  <input type="tel" name="phoneUS" id="phoneUS" style='width:120px; display:none; margin-top:0px; !important' class=" span2 phone" placeholder="Phone Number">
  <div class="btn-group" id='l_provider' style='display:none;' >
    <button class="btn dropdown-toggle" style='width:100px'  data-toggle="dropdown">
      <span id='l_provtxt'>Provider</span>
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" style='min-width:100px;'>
	<?php
	dbconnect();
	$query = mysql_query("SELECT * FROM `providers`") or die(mysql_error());
		while($providers = mysql_fetch_array($query)){
			echo "<li><a href='#' class='lprovider' id='".$providers['name']."'>".$providers['name']."</a><li>";
		}
	?>
	 <li><a href='#' class='lother' id='Cricket'>Other</a></li>
    </ul>
  </div>
</div>
        
        <br><button class="btn btn-large btn-primary" type="submit" >Next ></button>
</form>
		 <span id='invalid'></span> <!-- This thing to the left here is where error messages go. Please don't touch.-->
		 
		 <div class="alert alert-danger" style=' margin-top:5%; margin-bottom:0%; display:none;' id="otheralert">
		  <button type="button" class="close" data-dismiss="alert">&times;</button>
		  <h4>Is your mobile provider not listed?</h4> 
		   <a href='mailto:vip@iglou.com'>Send us an email</a> and let us know. 
		</div>
		<div class="alert alert-info" style='display:none; margin-top:5%; ' id='txtalert'>
			<h4>Attention!</h4>
			Message and data rates may apply.
		</div>
		<noscript>
			<div class="alert alert-danger" style=' margin-top:5%; margin-bottom:0%; display:none;' id="otheralert">
			  <button type="button" class="close" data-dismiss="alert">&times;</button>
			  <h4>Javascript Required</h4> 
			   Javascript is required to view this website. You appear to have it disabled, please enable it to continue. 
			</div>
		</noscript>
		
</div>
     
		<script>
		
	$("#phoneUS").mask("(999) 999-9999");

		$('#emailform').validate({
			 ignore: "",
			  messages: {
					l_p: $("#l_provider").css('color','red')+"sdsdsdd",
					PrependedDropdownButton: "Enter an email address.<br>",
					phoneUS: "Enter a 10 digit phone number<br>",
			  },
			 errorLabelContainer: '#invalid',
		});
		</script> 
 </div>