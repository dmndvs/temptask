<?php
defined('PROT') or die();

//import user in database
function adduser() {
$error = '';//for errors
if ($_POST['login'] != "" or $_POST['pass'] != "") {
	include('lib/config.php');//mysql user-password
	//generate salt
	$i = 0;
	$symbol = array_merge(range('A','Z'),range('a','z'),range('0','9')); 
	$c = count($symbol); 
	do {$sym = $symbol[rand(0,$c)];
    $salt .= $sym;
	++$i;
	} while ($i<7);

	//adduser	
	$username=$_POST['login'];
	$userpassword=sha1(sha1($_POST['pass']).$salt);
	$mysql_connect = mysql_connect($mysqlserver,$dbuser,$dbpass);
	if(!$mysql_connect) {$error='mysql connectrion error: '.mysql_error();return $error;}
	mysql_select_db($dbname,$mysql_connect);
	$rez = mysql_query("SELECT * FROM users WHERE username='".$username."'"); //check username in mysql, if exist -> error	
	if (mysql_num_rows($rez) == 1) {$error='user already exist'; return $error;}
	else {
	mysql_query('CREATE TABLE IF NOT EXISTS users(
				id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				username CHAR(12),
				userpassword CHAR(100),
				salt CHAR(10))');
	if(mysql_query("INSERT INTO users (username,userpassword,salt) 
		VALUES ('$username','$userpassword','$salt')")) {
			$error = 'User has been registred';
	} 
	else{	$error = 'user is not added: '.mysql_error();
			mysql_close($mysql_connect); 
			return $error;}
		mysql_close($mysql_connect);
		return $error;}}
else {
	$error = 'emty fields';
	return $error;}
}

//user autorization 
function enter ()
 {
$error = '';//for errors
if ($_POST['login'] != "" and $_POST['pass'] != "") //check empty fields
{ 		
	$login = $_POST['login']; 
	$password = $_POST['pass'];
	include('lib/config.php');//mysql user-password
	$mysql_connect = mysql_connect($mysqlserver,$dbuser,$dbpass);//connect to mysql
	if(!$mysql_connect) {$error='mysql connectrion error: '.mysql_error();return $error;}
	mysql_select_db($dbname,$mysql_connect);
	$rez = mysql_query("SELECT * FROM users WHERE username='".$login."'"); //find username in mysql	
	if (mysql_num_rows($rez) == 1)		

	{ 			
		$row = mysql_fetch_assoc($rez);
		if (sha1(sha1($password).$row['salt']) == $row['userpassword']) //check password

		{ 
		session_start();                 //open session
		$_SESSION['id'] = $row['id'];				
		$id = $_SESSION['id'];
		mysql_close($mysql_connect);
		return $error; 			
	}
	else //if password doesn't match	
	{ 				
		$error = "incorrect password"; 										
		mysql_close($mysql_connect);
		return $error; 			
	} 		
} 		
	else //if user is not exist	

	{ 			
		$error = "incorrect user and password"; 			
		mysql_close($mysql_connect);
		return $error; 		
	} 	
} 	
	else //if fields are emty
	{ 		
		$error = "emty fields"; 				
		return $error; 	
	} 
}
