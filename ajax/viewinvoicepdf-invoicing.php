<? 
	include('../functions/phpfunctions.php');
	//echo($_POST['onlineslno'].'here'); exit;
	if($_POST['onlineslno'] <> '')
	{
		$lastslno  = $_POST['onlineslno'];
	}
	if($lastslno == '')
	{
		$url = '../home/index.php?a_link=home_dashboard'; 
		header("location:".$url);
	}
	else
	{
		vieworgeneratepdfinvoice($lastslno,'view');
	}
?>