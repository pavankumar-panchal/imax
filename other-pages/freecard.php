<?php 
include('../functions/phpfunctions.php');

$query = "select * from inv_dealercard where cardid in ('94827','96503','96846','95993','96560','96048','96275','96051','96226','96189','96058','95931','95638','95808','95577','95453','95157','95118','95134','95085','94341','88532','94340','94332','94120','93956','93915','93939','	89840','42522','93547','93497','93528','93518','89739','93285','92558','92925','92991','92885','92601','92506	','92245','92174','92191','89802','89802','92056','89821','91225','88554','91207','89650','89822','89883','89432','90286','90289','90179','90024','89956','89945','89919','89885','89899','89715','89887','89768','89884','89882');";
$result = runmysqlquery($query);
$count = 0;
while($fetch = mysqli_fetch_array($result))
{
	$oldcard = $fetch['cardid'];
	$count++;
	$query0 = "select * from inv_customerproduct where cardid = '".$oldcard."'";
	$fetch0 = runmysqlqueryfetch($query0);
	$customerid = $fetch0['customerreference'];
	$query1 = "select cardid as cardid from inv_mas_scratchcard where attached = 'no' order by cardid limit 1 ;";
	$result1 = runmysqlqueryfetch($query1);
	$cardid = $result1['cardid'];
	$dealerid = $fetch['dealerid'];
	$newproductcode = freeupdateproductcode($fetch['productcode']);
	$usagetype = $fetch['usagetype'];
	$purchasetype = $fetch['purchasetype'];
	
	$query2 = "INSERT INTO inv_dealercard (dealerid, cardid, productcode, date, usagetype, purchasetype, free, userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,customerreference,cuscardattacheddate) values('".$dealerid."','".$cardid."','".$newproductcode."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."','".$usagetype."','updation','yes','1','7','".$usagetype."','updation','".$newproductcode."','".$dealerid."','".$customerid."','".datetimelocal('Y-m-d')." ".datetimelocal('H:i:s')."');";
	$result2 = runmysqlquery($query2);
	$query3 = "update inv_mas_scratchcard set attached = 'yes', registered='no', blocked='no', online='no', cancelled='no'  where attached = 'no' and cardid = ".$cardid.";";			
	$result3 = runmysqlquery($query3);
	sendfreeupdationcardemail($cardid,$oldcard);
	echo($count.'^'.$customerid.'^New card - '.$cardid.'^Old card - '.$oldcard. " DONE <br>\n");
}


function freeupdateproductcode($productcode)
{
	switch($productcode)
	{
		case'639': return '641'; break;
		case'650': return '652'; break;
		case'651': return '653'; break;
		case'350': return '351'; break;
		case'305': return '306'; break;
		default: return 'codenotfound';
	}
}


function sendfreeupdationcardemail($cardid,$oldcard)
{
		
		$query1 = "select inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername,inv_mas_dealer.emailid as dealeremailid,inv_mas_product.productname as newproductname from inv_dealercard left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid 
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode where inv_dealercard.cardid = '".$cardid."' ";
		$resultfetch = runmysqlqueryfetch($query1);
		$pinno = $resultfetch['pinno'];
		$dealername = $resultfetch['dealername'];
		$newproductname = $resultfetch['newproductname'];
		$dealeremailid = $resultfetch['dealeremailid'];
	//	$dealeremailid = 'meghana.b@relyonsoft.com';
		
		$query = "Select inv_mas_customer.slno,inv_mas_customer.businessname as businessname, inv_mas_customer.place as place,inv_mas_customer.customerid as customerid, inv_mas_product.productname as oldproductname ,inv_customerproduct.date as regdate , inv_mas_customer.slno as cusslno from inv_customerproduct Left join inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference  left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid	left join inv_mas_product on inv_mas_product.productcode = left(inv_customerproduct.computerid,3) where inv_customerproduct.cardid = '".$oldcard."'";
	$result = runmysqlqueryfetch($query);
	
	$query2 = "select * from inv_contactdetails where customerid = '".$result['cusslno']."';";
	$result2 = runmysqlquery($query2);
	$contactpersonarray  = ''; $emailidarray = ''; $valuecount = 0;
	while($fetch2 = mysqli_fetch_array($result2))
	{
		if($valuecount > 0)
			$contactpersonarray .= ',';
		$contactpersonarray .= $fetch2['contactperson'];
		if($valuecount > 0)
			$emailidarray .= ',';
		$emailidarray .= $fetch2['emailid'];
		$valuecount++;
	}
	$contactperson = removedoublecomma($contactpersonarray);
	$emailidall = removedoublecomma($emailidarray);

	$regdate = $result['regdate'];
	$businessname = $result['businessname'];
	
	$place = $result['place'];
	$customerid = $result['customerid'];
	$oldproductname = $result['oldproductname'];

	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
		$emailid = 'rashmi.hk@relyonsoft.com';

	else
		$emailid = $emailidall;
		
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	// CC mail to dealer 
	$ccemailarray = explode(',',$dealeremailid);
	$ccemailcount = count($ccemailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
			if($i == 0)
				$emailids[$contactperson] = $emailarray[$i];
			else
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	for($i = 0; $i < $ccemailcount; $i++)
	{
		if(checkemailaddress($ccemailarray[$i]))
		{
			$ccemailids[$ccemailarray[$i]] = $ccemailarray[$i];
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/customercardinfo1.htm");
	$textmsg = file_get_contents("../mailcontents/customercardinfo1.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##OLDPRODUCTNAME##%^%".$oldproductname;
	$array[] = "##NEWPRODUCTNAME##%^%".$newproductname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##DEALERNAME##%^%".$dealername;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','1234567890')
	);
	$toarray = $emailids;
	if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
	{
	//	$bccemailids['rashmi'] ='rashmi.hk@relyonsoft.com';
		$bccemailids['archanaab'] ='archana.ab@relyonsoft.com';
	}
	else
	{
		$bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
	}
	$bccarray = $bccemailids;
	$ccarray = $ccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "Free PIN No for ".$newproductname." (2011-12)";
	$html = $msg;
	$text = $textmsg;
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray); 
	//inserttologs($userid,$slno,$fromname,$fromemail,$emailid,$dealeremailid,'vijaykumar@relyonsoft.com',$subject);					
}

?>