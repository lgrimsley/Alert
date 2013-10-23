<?php

function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

function dbconnect(){
	$hostname=  "localhost";
	$dbname = "grimsley_development";
	$dbuser = "grimsley_logan";
	$dbpw = "bigbang1";
	mysql_connect($hostname, $dbuser, $dbpw) or DIE(mysql_error());
	mysql_select_db($dbname) or die(mysql_error());
}


function displayServices($email, $phone, $provider, $type){
	 $goahead = false;
		if($type == "email"){
			if($email & !check_email_address($email)){
				echo "
				<div class='bs-docs-example'>
				  <div class='alert fade in'>
					<button type='button' class='close' data-dismiss='alert'>x</button>
					<center><strong>Please enter an email address!</strong> The email entered was invalid.
				  </div>
				</div>
				<br>";
				
			}else $goahead = true;	
		}elseif($type == "phone"){
			if($phone && $provider){
				if(strlen($phone) == 10 && is_numeric($phone)){
					$goahead = true;
				}else {$goahead = false;}
				
			}
		}else{

			$goahead = false;
		}
		
		if(!$goahead){include("template/emailform.php");}
			else{ include("template/services.php"); }
}
 
function addEmail($email, $services, $phone, $provider, $type){
	dbconnect();
	if(emailExists($email)){
		updateEmail($email, $services);
	} else{
		addNewEmail($email, $services);
	}
}
function formatEmail($phone, $provider){
	dbconnect();
	$query = mysql_query("SELECT `gateway` FROM `providers` WHERE `name`='$provider'")or die(mysql_error());
	$gateway = mysql_fetch_assoc($query);

	$email = $phone . $gateway['gateway'];
	
	return $email;
}
function formatPhone($phone){
	$remove = array("(",")"," ", "-");
	$phone = str_replace($remove,"", $phone);
	return $phone;
}
function emailExists($email){
	$query = mysql_query("SELECT `id` FROM `users` WHERE email='$email'");
	$id = mysql_fetch_row($query);
	if($id){ 
		return true;
	}else return false; 
}

function type($email){
	dbconnect();
	$domain = substr($email, strpos($email, "@"));
	$query = mysql_query("SELECT `gateway` FROM `providers` WHERE 1");
		while($g = mysql_fetch_array($query)){
		$gateways[] = $g[0];
		}
	
	if(in_array($domain, $gateways)){
		return "phone";
	}else return "email";
}

function addNewEmail($email, $services){
	$type = type($email);
	if($services) {
		$servicelist = implode(",", $services);
		$query = mysql_query("INSERT INTO `users` (`email`,`services`,`type`) VALUES ('$email','$servicelist','$type')")or DIE("Unable to Subscribe: " . mysql_error() . "<br><br><b>Please contact Iglou customer support with the error message above");
		if($query){
			echo "
			<div class='span9 '>
					<span class='page-header ' >
						<h2 class='offset1'>You have successfully subscribed to the Iglou Early Alert System!
							<p class=''>
									<small >
										You will be alerted regarding the selections highlighted in <font color='orange' style='font-weight:bold'>orange</font> below.
									
								</small>
							</p>
						</h2>
					</span>
			</div> 
			";
			echo showSubscription($email, "disabled");
			echo "<center><br> You may close this window, or <a href='index.php?a=services&email=$email'>Click here to change your subscription</a>";
			
		}
		
	} else{
		echo "You did not choose any services to be alerted about. <a href='index.php?a=services&email=$email'>Click here</a> to select at least one service, or close this window if you do not wish to subscribe";
	}


}
function showSubscription($email, $disabled, $type = "services"){  //This function will return a variable with the HTML code for the subscription input. 
		if($email){ 						   					   //Disabled variable makes it uneditable, good for showing status of current email subscription.
			dbconnect();										   //Optional argument (type) accepts: 'up', 'info', 'down'. Defaults to services (user end), used for POST variable names.
			if($email == 'admin') {
				$userv = array();
			}elseif(emailExists($email)){
				$query = mysql_query("SELECT `services` FROM `users` WHERE `email`='$email'");
				$userv = mysql_fetch_row($query);
				$slist = $userv[0];
				$userv = explode(",",$userv[0]);
			} else {
				$userv = array(8);
				$slist = "";
			}
		
		} else $userv = array(8);
			$query = mysql_query("SELECT * FROM `services`");
					
			$c = 0;
			
			if($disabled != 'disabled') {
					
				$classes = "overbox btn box";
				$allclass = " btn allbox";
				if($slist == "1,2,3,4,5,6,7,8"){
					$checked = 'checked';
					$allclass = "btn btn-warning box allbox";
					$classes = "overbox btn btn-warning box";
				}else{
					unset($checked);
					unset($allstyle);
				} 
				$html .= "<center><div class='boxes'>
				<div class='$allclass'  id='".$type."div' name='$type' style='$allstyle width:86.5%; height:10%;'>
					<span class='btntxt'>
						<input type='checkbox' class='checkbox' $checked  id='$type'>
						All / None
					</span>
				</div>";

				}else{
					$html .= "<center><div class='boxes'>";
					$classes = "btn box btntxt disabled";
				}
				
			while($services = mysql_fetch_array($query)){
			
			$divid = $type . $c;
			$chkboxid = "x" . $type . $c;
			
				if(in_array($services['id'], $userv)){
					$html .= "<div class='".$classes." btn-warning' id='$divid'>
					<span class='btntxt'>
					<input class='checkbox' id='$chkboxid' type='checkbox' checked $disabled name='".$type."[]' value='" . $services['id']. "'>
					". $services['name']. "</span></div>";
				}else{
					$html .= "<div class='$classes'  id='$divid' >
					<span class='btntxt'>
					<input class='checkbox' id='$chkboxid' type='checkbox' $disabled  name='".$type."[]' value='" . $services['id']. "'>"
					.$services['name'].
					 "</span></div>";
				}
				
				$c++;
			}	
			return $html;

}
function updateEmail($email, $services){
	if($services) {
		$servicelist = implode(",", $services);
		$query = mysql_query("UPDATE `users` SET `services`='$servicelist' WHERE `email` = '$email'")or DIE("Unable to Change Subscription: " . mysql_error() . "<br><br><b>Please contact Iglou customer support with the error message above");
		if($query){
			echo "
			<div class='span9 '>
					<span class='page-header ' >
						<h2 class='offset1'>Your subscription has been updated!
							<p class=''>
									<small >
										You will be alerted regarding the selections highlighted in <font color='orange' style='font-weight:bold'>orange</font> below.
									
								</small>
							</p>
						</h2>
					</span>
			</div> 
			";
		}
		echo showSubscription($email, "disabled");
		echo "<h2><small> You may close this window,<br> or <a href='index.php?a=services&email=$email'>Click here to change your subscription</a></small></h2>";
		
	} else{
		unsubscribe($email);
	}

}

function unsubscribe($email){
	$query = mysql_query("DELETE FROM `users` WHERE `email`='$email'") or DIE("Unable to unsubscribe: " . mysql_error() . "<br><br><b>Please contact Iglou customer support with the error message above");
	if($query){	
		echo"
		<div class='span9 '>
					<span class='page-header ' >
						<h2 class='offset1'>You have successfully unsubscribed from the IgLou Early Warning system.
						</h2>
					</span>
			</div>";
			echo showSubscription("admin", "disabled");
			echo "<h2>
			<small >
					You may close this window, or <a href='index.php?a=services&email=$email'>Click here to subscribe to alerts.</a>	
			</small>
			</h2>";
	}
}

function login($password){ 
	if($password == "2580")
		return true;
	else return false;
}

function loadDefault($args = ""){  //This functions loads the defalt messages into an array.
	$query = mysql_query("SELECT * FROM `default` $args") or die(mysql_error());
	$i = 0;
	while($msg = mysql_fetch_array($query)){
		$default[$i] = $msg;
		$i++;
	}
	return $default;
	
}	

function getAdminUI($status){

$default = loadDefault("WHERE `status`='$status'");

$html = "

<div name='$status'  class='hiddendiv'>
			<form method='POST'  action='admin.php?a=preview'>
				";
				if($status != "info"){
					$html .="
						<div id='".$status."subject' style='display:none; width:100%; padding:0px; margin:0px'>
							<input type='text' class='input-search'  style='width:85%; height:1.5em; font-size:1.5em; ' name='".$status."select' placeholder='Alert Subject'>
							<div class='btn btn-danger subject' value='$status' style='width:7%; height:1.5em; font-weight:bold; line-height:150%; float:right; top:0px; padding:3px; font-size:1.5em;'>X</div>
						</div>
						<select class='select' id='".$status."select'  name='".$status."select'>
						<option value='' id=''>Select A Default Message</option>
						";
							
								if($default != null){
									foreach ($default as $u){
										$html .= "<option id='".$u['text']."' id='".$u['title']."'>".$u['title']."</option>";
									}
								}
								
								
									$html .="<option  value='' id='Custom Message' name='custom'>Custom Message</option>
							
											</select>";
				} else $html .= "<input type='text' class='input-search' style='width:95%; height:1.5em; font-size:2em;' name='".$status."select' placeholder='Email Subject'>";
$html .="
						<textarea name='msg' class='textarea'  id='".$status."text' ></textarea>
					
					<div >";						
						$html .= showSubscription("admin","",$status);
$html .="			</div>
					<div style='width:100%; height:20%;'>
						<input type='button' class='preview btn btn-success button disabled' disabled style='margin: 1%; padding:3px; height:45px; width:44.5%' value='Preview'>
						<input type='submit' class='btn btn-primary button' style='margin: 1%;padding:3px; height:45px; width:44.5%' value='Send Alert'>
						<input type='hidden' name='preview' id='preview' value='no'>
						<input type='hidden' name='status' value='$status'>
						
					</div>
			</form>
				
		</div>
		</div>";
		
		return $html;


}


function getAffected($affected){
	$i=0;
	$users = array();
	$first = array_pop($affected);
	$first = $first['id'];
	$sql = "SELECT * FROM `users` WHERE FIND_IN_SET('$first', `services`) ";
	foreach($affected as $service){
		$id = $service['id'];
		$sql .= " OR FIND_IN_SET('$id', `services`)";
	}
	$query = mysql_query($sql) or die(mysql_error());
	while($u = mysql_fetch_array($query)){
		array_push($users, $u);
	}
	return $users;
}

?>