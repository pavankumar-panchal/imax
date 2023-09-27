<?php
session_start();
if((imaxgetcookie('sessionkind') <> false) && (imaxgetcookie('userid') <> false) && (imaxgetcookie('checkpermission') <> false))
{
	$cookie_logintype = imaxgetcookie('sessionkind');
	$cookie_username = imaxgetcookie('userid');
	$cookie_permissiontype = imaxgetcookie('checkpermission'); 
}
else
{
	imaxuserlogoutredirect();
}

$checklogin_data = "select disablelogin from inv_mas_users where slno = '$cookie_username'";
$result_checklogin = runmysqlqueryfetch($checklogin_data);
$result_check_disable = $result_checklogin['disablelogin'];
if($result_check_disable == 'yes')
{
    header("Location: http://imax.relyonsoft.net/user/logout.php");
}

switch($cookie_logintype)
{
	case "logoutforthreemin":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',180);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 180;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			imaxuserlogoutredirect();
		}
		break;

	case "logoutforsixhr":
		if($_SESSION['verificationid'] == '4563464364365')
		{
			ini_set('session.gc_maxlifetime',21600);
			ini_set('session.gc_probability',1);
			ini_set('session.gc_divisor',1);
			$sessionCookieExpireTime = 21600;
			session_start();
			setcookie(session_name(), $_COOKIE[session_name()], time() + $sessionCookieExpireTime, "/");
		}
		else
		{
			imaxuserlogoutredirect();
		}
		break;

	case "logoutforever":
		//session_start();
		break;
	case "logoutforonehour":
		header("Location: http://imax.relyonsoft.net/user/logout.php");
		break;	
	
	default:
		imaxuserlogoutredirect();		
}
?>