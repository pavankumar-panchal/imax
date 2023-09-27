<?
	include('../functions/phpfunctions.php');
	
	$receiptno = $_POST['receiptno'];
	$dealerreceiptno = $_POST['dealerreceiptno'];
	$matrixreceiptno = $_POST['matrixreceiptno'];
	$receipttype = $_POST['receipttype'];
	//exit;
	if($receiptno == '')
	{
		if($dealerreceiptno == '')
		{
			if($matrixreceiptno == '')
			{
				$url = '../home/index.php?a_link=home_dashboard'; 
				header("location:".$url);
			}
			else
			{
				viewreceipt('','view',$matrixreceiptno);
			}
			
		}
		elseif($dealerreceiptno!= " ")
		{
			//echo "hi";
			if($receipttype == 'Online')
				viewdealeronlinereceipt($dealerreceiptno,'view');
			else
				viewdealerreceipt($dealerreceiptno,'view');
		}
	}
	else
	{
		viewreceipt($receiptno,'view','');
	}

?>
