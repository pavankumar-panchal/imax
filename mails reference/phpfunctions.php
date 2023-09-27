<?php

//Include Database Configuration details
if(file_exists("../inc/dbconfig.php"))
{
	include("../inc/dbconfig.php");
}
else
	include("inc/dbconfig.php");


/* -------------------- Get local server time [by adding 5.30 hours] -------------------- 
function datetimelocal($format)
{
	$diff_timestamp = date('U');
	$date = date($format, $diff_timestamp);
	return $date;
}*/

/* -------------------- Upload ZIP file through PHP -------------------- */
function fileupload($filename,$filetempname)
{
//check that we have a file
  //Check if the file is JPEG image and it's size is less than 350Kb
  
  //retrieve the date.
  $date = datetimelocal('YmdHis-');
  $filebasename = $date.basename($filename);
  $ext = substr($filebasename, strrpos($filebasename, '.') + 1);
  if ($ext == "zip") 
  {
      $newname = $_SERVER['DOCUMENT_ROOT'].'/sssm-beta/upload/'.$filebasename;
	  $downloadlink = 'http://'.$_SERVER['HTTP_HOST'].'/sssm-beta/upload/'.$filebasename;
      if (!file_exists($newname)) 
	  {
        if ((move_uploaded_file($filetempname,$newname))) 
		{
           $result = $downloadlink; //Upload successfull
        } 
		else 
		{
           $result = 4; //Problem dusring upload
        }
      } 
	  else 
	  {
         $result = 3; //File already exists by same name
      }
  } 
  else 
  {
     $result = 2; //Extension doesn't match
  }
  return $result;
}


/* -------------------- Download any file through PHP header -------------------- */
function downloadfile($filelink)
{
	$filename = basename($filelink);
	header('Content-type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$filename);
	readfile($filelink);
}

/* -------------------- Run a query to database -------------------- */
function runmysqlquery($query)
{
	global $dbhost,$dbuser,$dbpwd,$dbname;

	//Connect to host
	$connection = mysqli_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");
	
	//Connect to Database
	mysqli_select_db($dbname) or die("Cannot connect to database");
	
	//Run the query
	$result = mysqli_query($query) or die(mysqli_error());
	
	//Close the database connection
	mysqli_close($connection);
	
	//Return the result
	return $result;
}

/* -------------------- Run a query to database with fetching from SELECT operation -------------------- */
function runmysqlqueryfetch($query)
{
	global $dbhost,$dbuser,$dbpwd,$dbname;

	//Connect to host
	$connection = mysqli_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");
	
	//Connect to Database
	mysqli_select_db($dbname) or die("Cannot connect to database");
	
	//Run the query
	$result = mysqli_query($query) or die("Query failed run queryfetch function".$query);
	
	//Fetch the Query to an array
	$fetchresult = mysqli_fetch_array($result) or die ("Cannot fetch the query result".$query);
	
	//Close the database connection
	mysqli_close($connection);
	
	//Return the result
	return $fetchresult;
}

function runsppdbquery($query)
{
	$sppdbhost = "localhost";
	$sppdbuser = "relyon_nithya";
	$sppdbpwd = "56705743";
	$sppdbname = "relyon_saralpaypack";
	
	$connection = mysqli_connect($sppdbhost, $sppdbuser, $sppdbpwd) or die("Cannot connect to Mysql server host");
	mysqli_select_db($sppdbname) or die("Cannot connect to database");
	$result = mysqli_query($query) or die(mysqli_error());
	mysqli_close($connection);
	return $result;
}


function runicicidbquery($query)
{
	$icicidbhost = "localhost";
	$icicidbuser = "relyon_newuser";
	$icicidbpwd = "hotelleela28";
	$icicidbname = "relyon_icici";
	
	$connection = mysqli_connect($icicidbhost, $icicidbuser, $icicidbpwd) or die("Cannot connect to Mysql server host");
	mysqli_select_db($icicidbname) or die("Cannot connect to database");
	$result = mysqli_query($query) or die(mysqli_error());
	mysqli_close($connection);
	return $result;
}


/* -------------------- To change the date format from DD-MM-YYYY to YYYY-MM-DD or reverse -------------------- */
function changedateformat($date)
{
	if($date <> "0000-00-00")
	{
		$result = explode("-",$date);
		$date = $result[2]."-".$result[1]."-".$result[0];
	}
	else
	{
		$date = "";
	}
	return $date;
}

/* -------------------- To trim the data for the grid, If it is more than 20 charecters [Say: "This problem is due to the problem in server" -> "This problem is due ..." -------------------- */
function gridtrim($value)
{
	$desiredlength = 20;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "...";
	}
	return $value;
}

function gridtrim1($value)
{
	$desiredlength = 20;
	$length = strlen($value);
	if($length >= $desiredlength)
	{
		$value = substr($value, 0, $desiredlength);
		$value .= "<br>";
	}
	return $value;
}

function changetime($time)
{
	if($time == "00:00:00")
	{
		$time = "";
	}
	return $time;
}

function generatepwd()
{
	$charecterset = "1234567890";
	for ($i=0; $i<8; $i++)
	{
		$usrpassword .= $charecterset[mt_rand(0,9)];
	}
	return $usrpassword;
}

function checkemailaddress($email) 
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{ 
		// Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) 
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) 
			{
				return false;
			}
		}
	}
	return true;
}

function replacemailvariable($content,$array)
{
	while ($item = current($array)) 
	{
		if($item == "" || $item == "0")
		$item = "-";
		$content = str_replace(key($array),$item,$content);
		next($array);
	}
	return $content;
}

function fullurl()
{
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

/*
function runmysqlquerydummy($query)
{
	global $dbhost,$dbuser,$dbpwd,$dbname;

	//Connect to host
	$connection = mysqli_connect($dbhost, $dbuser, $dbpwd) or die("Cannot connect to Mysql server host");
	
	//Connect to Database
	mysqli_select_db('relyon_userlogin') or die("Cannot connect to database");
	
	//Run the query
	$result = mysqli_query($query) or die(mysqli_error());
	
	//Return the result
	return $result;
	
	//Close the database connection
	mysqli_close($connection);
}
*/


?>