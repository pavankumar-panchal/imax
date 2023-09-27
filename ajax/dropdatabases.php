<?
//include('../functions/phpfunctions.php');

	$dbhost = "nithya";
	$dbuser = "root";
	$dbpwd = "SaRalSsM";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpwd);
	if($connection == true)
	{
		$query = "DROP DATABASE if exists `inventory4`;";
		$result = mysqli_query($query) or die(mysqli_error());
		$query = "DROP DATABASE if exists `inventory5`;";
		$result = mysqli_query($query) or die(mysqli_error());
		$query = "DROP DATABASE if exists `inventory6`;";
		$result = mysqli_query($query) or die(mysqli_error());
		$query = "DROP DATABASE if exists `inventory7`;";
		$result = mysqli_query($query) or die(mysqli_error());
		$query = "DROP DATABASE if exists `inventory8`;";
		$result = mysqli_query($query) or die(mysqli_error());
		$query = "DROP DATABASE if exists `inventory`;";
		$result = mysqli_query($query) or die(mysqli_error());
		echo("Dropped");
	}
?>
