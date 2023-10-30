<?php
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checkpermission.php');

$lastslno = $_POST['lastslno'];
$type = $_POST['type'];

switch($type)
{
	case 'generatedealerlist':
	{
		$query = "SELECT slno,businessname FROM inv_mas_dealer ORDER BY businessname";
		$result = runmysqlquery($query);
		$grid = '<select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:400px;" onclick ="selectfromlist(); showunregdcards();" onchange="selectfromlist(); "  >';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .= '<option value="'.$fetch['slno'].'">'.$fetch['businessname'].'</option>';
		}
		$grid .= '</select>';
		echo($grid);
	}
	break;	
	
	case 'registered':
	{
		$cardidfrom = $_POST['cardidfrom'];
		$cardidto = $_POST['cardidto'];
		$updatedealer = $_POST['updatedealer'];
		
		if($updatedealer == 'yes')
		{
			$billnumber = $_POST['billnumber'];
			$dealerid = $_POST['dealerid'];
			
			$query = "UPDATE inv_dealercard SET dealerid = '".$dealerid."', cusbillnumber = '".$billnumber."' WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
		}
		echo("Dealer Updated Successfully.");
	}
	break;
	
	case 'unregistered':
	{
		$msg = '';
		$cardidfrom = $_POST['cardidfrom'];
		$cardidto = $_POST['cardidto'];
		$usagetype = $_POST['usagetype'];
		$purchasetype = $_POST['purchasetype'];
		$detachcard = $_POST['detachcard'];
		$updatedealer = $_POST['updatedealer'];
		$updateproduct = $_POST['updateproduct'];
		
		if($usagetype == 'yes')
		{
			$query = "SELECT cardid FROM inv_dealercard WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			//$count = $fetch['count'];
			while($fetch = mysqli_fetch_row($result))
			{
				for($i=0; $i < count($fetch); $i++)
				{
					$query1 = "SELECT usagetype FROM inv_dealercard WHERE cardid = '".$fetch[$i]."';";
					$fetch1 = runmysqlqueryfetch($query1);
					if($fetch1['usagetype'] == 'singleuser') $updusagetype = 'multiuser'; else $updusagetype = 'singleuser';
					$query = "UPDATE inv_dealercard SET usagetype = '".$updusagetype."' WHERE cardid = '".$fetch[$i]."';";
					$result = runmysqlquery($query);
				}
			}
			$msg .= 'Usage Type [Single or Multi User],  ';
		}
		
		if($purchasetype == 'yes')
		{
			$query = "SELECT cardid FROM inv_dealercard WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			//$count = $fetch['count'];
			while($fetch = mysqli_fetch_row($result))
			{
				for($i=0; $i < count($fetch); $i++)
				{
					$query1 = "SELECT purchasetype FROM inv_dealercard WHERE cardid = '".$fetch[$i]."';";
					$fetch1 = runmysqlqueryfetch($query1);
					if($fetch1['purchasetype'] == 'new')  $updpurchasetype = 'updation'; else $updpurchasetype = 'new';
					$query = "UPDATE inv_dealercard SET purchasetype = '".$updpurchasetype."' WHERE cardid = '".$fetch[$i]."';";
					$result = runmysqlquery($query);
				}
			}
			$msg .= 'Purchase Type [New or Updation],  ';
		}
		
		if($detachcard == 'yes')
		{
			$query = "UPDATE inv_mas_scratchcard SET attached = 'no' WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			
			$query = "DELETE FROM inv_dealercard WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			
			$msg .= 'Cards Detached,  ';
		}
		
		if($updatedealer == 'yes')
		{
			$billnumber = $_POST['billnumber'];
			$dealerid = $_POST['dealerid'];
			
			$query = "UPDATE inv_dealercard SET dealerid = '".$dealerid."', cusbillnumber = '".$billnumber."' WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			$msg .= 'Dealer,  ';
		}
		
		if($updateproduct == 'yes')
		{
			$productcode = $_POST['productcode'];
			
			$query = "UPDATE inv_dealercard SET productcode = '".$productcode."' WHERE cardid BETWEEN '".$cardidfrom."' AND '".$cardidto."'";
			$result = runmysqlquery($query);
			$msg .= 'Product,  ';
		}
	}
	echo($msg." Updated Successfully.");
	break;
	case 'getcardlist':
	{
		$query = "SELECT cardid,scratchnumber FROM inv_mas_scratchcard  ORDER BY scratchnumber";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .='^*^';
			$grid .=  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo($grid);
	}
	break;
}
?>