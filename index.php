<link href="css/styles.css" rel="stylesheet" type="text/css" />
<?php
define('PROT',1);
include('lib/module.php');
if(isset($_POST['oklogin'])) //when pressed button for authorization 
	{
		$error = enter(); //enter on site
		if (!$error) //if no errors -> authorization
		{
			if(isset($_SESSION['id'])) {$action = 'congratulation';
			}
		}
	}
elseif(isset($_POST['okreg']))//when pressed button for registration 
	{
	$error = adduser();
	if($error == 'user already exist') {$action='signup';} //when user is already exist, we still standing on "signup" form
	}
if(!$action and !$_GET['action']) {
		$action = 'login';
		}
elseif(!$action and isset($_GET['action']))	{
		$action=$_GET['action'];
		}
include ('include/'.$action.'.html'); //file with form
session_destroy();
?>
