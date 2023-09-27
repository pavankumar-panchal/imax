<?php


if (isset($_REQUEST['submit'])) {
	echo "start";
	$email = $_REQUEST['email'];

	include("RSLMAIL_MAIL.php");

	$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
	$subject = "Imax testing email.";

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	$toarray['Bhumika'] = $email;

	$msg = "Test Email for " . $email . " .";
	echo "sending mail";
	echo rslmail($fromname, $fromemail, $toarray, $subject, $text, $msg, null, null, null);
	echo "Sent Successfully";
}
?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<title>Testing mail</title>
</head>

<body>
	<form method="post">
		<label for="email">Enter Email : </label>
		<input type="text" name="email" id="email" value="bhumika.p@relyonsoft.com" size="34">
		<input type="submit" name="submit" id="submit" value="Submit">
	</form>
</body>

</html>