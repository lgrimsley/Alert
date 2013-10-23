<?php
include("functions.php");
session_start();
if(login($_POST['password'])){ $_SESSION['admin'] = true; }
include("adminheader.php");
dbconnect();
//end header

echo "<center>";
$action = $_GET['a']; 
if($action == "logout"){
	session_destroy();
	session_unset();
}
if(!$_SESSION['admin']){
	include("template/adminlogin.php");
}else{

	if($action == "preview"){
		//if(isset($_SESSION['alert'])) unset($_SESSION['alert']);
		$status = $_POST['status'];
		$services = $_POST[$status];
		$subject = $_POST[$status."select"];
		$message = $_POST["msg"];
		
		
		if($status == "down"){ 
			$label = "label-important";
		}elseif($status == "info"){
			$label = "label-warning";
		}else $label = "label-success";
		
		$squery = mysql_query("SELECT * FROM `services`") or die(mysql_error());
		$i = 0;
		while($serv = mysql_fetch_array($squery)){ //Compile all affect services into array
			if(in_array($serv['id'], $services)){
				$affected[$i] = $serv;
				$i++;
			}
		}
		
		$_SESSION['users'] = getAffected($affected);
		$num = count($_SESSION['users']);
		
		
		
		$_SESSION['alert'] = array(
				"status" => $status,
				"subject" => $subject,
				"num" => $num,
				"label" => $label,
				"message" => $message,
		);
		$_SESSION['affected'] = $affected;
		
$txtmsg = "Iglou Alert System
Services $status:
";
		foreach($affected as $s){
			$txtmsg .= "".$s['name']."
";

		
		}
		$txtmsg .= "More Info @ www.igalrt.com";
		$_SESSION['txtmsg'] = trim($txtmsg);
		
		$content .=  "
		<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
		<html>
		<head>
		  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
			<style>
				body {
				  margin: 0;
				  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
				  font-size: 14px;
				  line-height: 20px;
				  color: #333333;
				  background-color: #5C5C5C;
				}
				.well {
				  min-height: 20px;
				  padding: 19px;
				  margin-bottom: 20px;
				  background-color: #f5f5f5;
				  border: 1px solid #e3e3e3;
				  -webkit-border-radius: 4px;
					 -moz-border-radius: 4px;
						  border-radius: 4px;
				  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
					 -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
						  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
				}
				.form-signin {
					max-width: 300px;
					padding: 19px 29px 29px;
					margin: 0 auto 20px; 
					background-color: #fff;
					border: 1px solid #e5e5e5;
					-webkit-border-radius: 5px;
					   -moz-border-radius: 5px;
							border-radius: 5px;
					-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
					   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
							box-shadow: 0 1px 2px rgba(0,0,0,.05);
				}
				.hero-unit {
				  padding: 60px;
				  margin-bottom: 30px;
				  font-size: 18px;
				  font-weight: 200;
				  line-height: 30px;
				  color: inherit;
				  background-color: #eeeeee;
				  -webkit-border-radius: 6px;
					 -moz-border-radius: 6px;
						  border-radius: 6px;
				}

				.hero-unit h1 {
				  margin-bottom: 0;
				  font-size: 60px;
				  line-height: 1;
				  letter-spacing: -1px;
				  color: inherit;
				}
				label,
				input,
				button,
				select,
				textarea {
				  font-size: 14px;
				  font-weight: normal;
				  line-height: 20px;
				}
				label {
				  display: block;
				  margin-bottom: 5px;
				}
				.label,
				.badge {
				  display: inline-block;
				  padding: 2px 4px;
				  font-size: 11.844px;
				  font-weight: bold;
				  line-height: 14px;
				  color: #ffffff;
				  text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
				  white-space: nowrap;
				  vertical-align: baseline;
				  background-color: #999999;
				}
				.label {
				  -webkit-border-radius: 3px;
					 -moz-border-radius: 3px;
						  border-radius: 3px;
				}
				a.label:hover,
				a.label:focus,
				a.badge:hover,
				a.badge:focus {
				  color: #ffffff;
				  text-decoration: none;
				  cursor: pointer;
				}
				.label-important,
				.badge-important {
				  background-color: #b94a48;
				}

				.label-important[href],
				.badge-important[href] {
				  background-color: #953b39;
				}

				.label-warning,
				.badge-warning {
				  background-color: #f89406;
				}

				.label-warning[href],
				.badge-warning[href] {
				  background-color: #c67605;
				}

				.label-success,
				.badge-success {
				  background-color: #468847;
				}

				.label-success[href],
				.badge-success[href] {
				  background-color: #356635;
				}

				.label-info,
				.badge-info {
				  background-color: #3a87ad;
				}

				.label-info[href],
				.badge-info[href] {
				  background-color: #2d6987;
				}

				.label-inverse,
				.badge-inverse {
				  background-color: #333333;
				}

				.label-inverse[href],
				.badge-inverse[href] {
				  background-color: #1a1a1a;
				}
			</style>
		</head>
		<body>
		<div class='well form-signin' style='max-width:500px; padding:3%;'>
		<div class='hero-unit' style='max-width:600px; text-align:left; padding:5%'>
		<h2>$subject</h2>

		";
		
		$content .= "
		<p>
		$message
		</p>
		<center>
		<!--START--!>
		";
		$i=0;
		foreach($affected as $s){
			$content .= "<span class='label $label' style='width:135px;'>".$s['name']." </span> ";
			$i++;
			if($i==2){ $i=0; $content .="<br>";}
			
		}
		
		$content .=  "
		<!--END--!>
		</center>
		<p >
		<font style='font-size:10pt'>To modify or unsubscribe from IgLou Alert System, <a href='_URL_'>click here</a></font>
		</p>
		</div>
		</div>
		</body>
		</html>
		";
		
		file_put_contents("alertcontents.html", $content);
		$count = strlen($txtmsg);
		$msgcount = round(ceil($count/144));
		echo "
		<iframe seamless='seamless' style='max-width:510px; width:100%; height:450px;' src='alertcontents.html'></iframe>
		<center>
		Text Message:
		<pre style='text-align:left; width:300px;'>$txtmsg</pre>
		<small>
			$count characters, $msgcount messages.
		</small>
		
		<br>
			<a class='btn btn-primary btn-large sendmail' href='admin.php?a=sendmail'>
			  Send Alert <span class='badge badge-warning' style='font-weight:bold; font-size:13pt;'> $num </span>
			</a>
			
			
		 </p>
		
		";
		
		
		
		
		
	}elseif($action == "sendmail"){

			
			
			$start = '<!--START-->';
			$end  = '<!--END-->';
			
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
			// More headers
			$headers .= 'From: <alert@iglou.com>' . "\r\n";
			$label = $_SESSION['alert']['label'];
			$invalid = array();
			foreach($_SESSION['users'] as $user){
				if($user['type'] == "email"){
					$message = file_get_contents('alertcontents.html');
					$services = explode(",", $user['services']);
					$content = "";
					foreach($_SESSION['affected'] as $s){
						if(in_array($s['id'], $services)){
							$content .= "<span class='label $label' style='width:135px;'>".$s['name']." </span> ";
							$i++;
							if($i==2){ $i=0; $content .="<br>";}
						}
					}
				}else{
					$message = "Iglou Alert System
Services $status:
";
					foreach($_SESSION['affected'] as $s){
					$message .= "".$s['name']."
";
					}
					$message .= "More Info @ www.igalrt.com";
				}
				$message = preg_replace("'_URL_'", "http://lgrimsley.com/alert/index.php?a=unsubscribe&email=".$user['email'], $message);
			
				$message = preg_replace('#('.$start.')(.*)('.$end.')#si', $content, $message);
				file_put_contents("alert.htm", $message);
				/*
				if(!mail($user['email'],$_SESSION['alert']['subject'],$message, $headers)){
					echo "Something went wrong.";
				}else echo "Alert sent.";
				*/
			}
			
			
		
		
		
		
		//if(isset($_SESSION['alert'])) unset($_SESSION['alert']);
	}else{
		//if(isset($_SESSION['alert'])) unset($_SESSION['alert']);
		?>
		
		<!--  Start Admin Main Menu  -->
		<div class='row-fluid' style='max-width:500px; padding:0px; margin:0px;'>
		
			<ul class="nav nav-tabs bigtabmain" id="myTab" style="margin:0px;">
				<li style='width:25%; text-align:left;'>
					<a href="#down">
					Down
					</a>
				</li>
				<li style='width:15%;'>
					<a href="#up">
						Up
					</a>
				</li>
				<li style='width:35%;'>
					<a href="#info">
					Announce
					</a>
				</li>
				<li style=' width:23%; '>
					<a  href="admin.php?a=logout">
					Logout
					</a>
				</li>
			</ul>
			
		
		<div class="well" style="padding:5px;">	 
			<div class="tab-content">
			  <div class="tab-pane active" id="up">
				<? echo getAdminUI('up'); ?> 
			  </div>
			  <div class="tab-pane" id="down">
				<? echo getAdminUI('down'); ?>
			  </div>
			  <div class="tab-pane" id="info">
				<? echo getAdminUI('info'); ?> 
			  </div>
			</div>
		</div>
			 
			<script>
			  $(function () {
				$('#myTab a:first').tab('show');
			  })
			</script>
		<?
	
	
	}
}


?>