function cookiecheck()
{
<?
include('../functions/phpfunctions.php');
$userid = imaxgetcookie('userid');
if(imaxgetcookie('userid') == false)
		echo('cookie not found');
?>

}
  
function getcookie()
{
  	if(cookiecheck() == 'cookie not found')
    window.location = 'http://meghanab/saralimax-user/index.php';
    else
    return false;
}





