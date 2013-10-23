<?php 
include("header.php");
include("functions.php");
dbconnect(); 
//end header
$action = $_GET['a'];
$email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
$phone = filter_input(INPUT_GET, 'phoneUS', FILTER_SANITIZE_SPECIAL_CHARS);
$provider = filter_input(INPUT_GET, 'l_p', FILTER_SANITIZE_SPECIAL_CHARS);
$type = filter_input(INPUT_GET, 'l_t', FILTER_SANITIZE_SPECIAL_CHARS);
$phone = formatPhone($phone);
if(!$type) $type = $_POST['type'];
if(!$email) $email = $_POST['email'];
if(!$email && $phone && $type ="phone") $email = formatEmail($phone, $provider);
$services = $_POST['services'];

if($action != "" && check_email_address($email)){
?> 
<center>
	<div class='fluid-container' style=" width:95%;">
		<div class='well fluid-container' style="padding:0px; text-align:left;width:95%; max-width:800px;background-color:white;" >

<?
}

if($action == "unsubscribe"){
	unsubscribe($email);
} elseif($action == "add"){
	addEmail($email, $services, $phone, $provider, $type);
}elseif($action == "services"){
	if($type == "phone"){
		displayServices($email, $phone, $provider, $type);
	}else{
		displayServices($email, "", "" ,"email");
	}
}else{
	include("template/emailform.php");
}

//footer
include("footer.php"); 
?>