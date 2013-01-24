<?php

/*

 	ANTI BOT JCE
 	prevent any script to be executed in the images folder and delete it
 	prepare an .htaccess like this in the images folder (no.php is this script name)
 	
 	RewriteEngine on

 	RewriteCond %{REQUEST_URI} !no.php$
 	RewriteRule ^(.*\.php)$ no.php?file=$1 [L]


 */

$file = trim(stripslashes($_GET['file']), ".\x00..\x20");

$info = pathinfo($file);
$me = pathinfo($_SERVER['SCRIPT_NAME']);

if(
	$me['basename']==$info['basename'] || 
	$info['extension']!='php' || 
	!file_exists($file) 
){
	header("HTTP/1.1 404 Not Found");
	exit();
}

$file = $_SERVER['DOCUMENT_ROOT']."{$me['dirname']}/$file";

$done = unlink($file);

$to      = "your@mail.here";
$subject = "Possible attack to {$_SERVER['SERVER_NAME']}";
$message = "IP: {$_SERVER['REMOTE_ADDR']} <br/>SERVER: {$_SERVER['SERVER_NAME']} <br/> File $file ".($done? '':'not').'removed.';
$headers = "From: nophp@{$_SERVER['SERVER_NAME']}\r\n" .
    "Reply-To: noreply@{$_SERVER['SERVER_NAME']}\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);


?>
