<?php
	ini_set('memory_limit', '-1');
	set_time_limit(0);
	include("../functions/phpfunctions.php");

	// Get Yesterday's Date 	
	$yesterday=date("Y-m-d", time()-86400);
	//$yesterday = '2011-08-29';
	$date = changedateformat($yesterday);
	$dfc = $yesterday;
	$dfc_monthstart = substr($yesterday,0,7).'-01';
	$dfc_monthend = substr($yesterday,0,7).'-31';

	$currentmonth =  date('m');
	if($currentmonth > 3)
	{
		$currentyear = date('Y');
		$endyear =  date('Y')+ 1;
	}
	else
	{
		$endyear = date('Y');
		$currentyear = date('Y')-1;
	}
	$dfc_yearstart =$currentyear.'-04-01';
	$dfc_yearend = $endyear.'-03-31';
	
	$todaysdatepiece = "left(inv_mas_receipt.receiptdate,10) = '".$yesterday."'";
	$monthsdatepiece = "left(inv_mas_receipt.receiptdate,10) between '".$dfc_monthstart."' and '".$dfc_monthend."'";
	$yeardatepiece = "left(inv_mas_receipt.receiptdate,10) between '".$dfc_yearstart."' and '".$dfc_yearend."'";

	$managedareaarray = array(
						"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("paramesh.n@relyonsoft.com","nitinall@relyonsoft.com"),
										 "name" => array("Paramesh N","Nitin S Patel")),
						"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("raghavendra.n@relyonsoft.com","nitinall@relyonsoft.com"),
										 "name" => array("Raghavendra N","Nitin S Patel")),
						"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("nitinall@relyonsoft.com","pradeep.n@relyonsoft.com","dealers@relyonsoft.com"),
										 "name" => array("Nitin S Patel","Pradeep N","Usha"))			 
					);
					
	
	// Define Bcc Array

	$bccarray = array('Relyonimax' => 'relyonimax@gmail.com');
	

	
	/* ------------------ Day end summary email to all dealers ----------------- */

	// Fetch all dealer details 
	
	$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid from inv_mas_dealer where inv_mas_dealer.disablelogin = 'no' and inv_mas_dealer.enablebilling = 'yes' order by slno";
	$result = runmysqlquery($query);
	$cnt = 0;
	while($fetch = mysql_fetch_array($result))
	{
		$cnt++;
		$dealerid = $fetch['slno'];
		$dealername = $fetch['businessname'];
		$emailid = $fetch['emailid'];
			//$emailid = 'meghana.b@relyonsoft.com';
			
		// Fetch today's details
		$query4 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece." and  inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		$query5 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result5 = runmysqlquery($query5); 
		
		$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result6 = runmysqlquery($query6); 
		
		$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched') and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result7 = runmysqlquery($query7);
		
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "50%" align="center" >Name</td><td nowrap="nowrap"  align="center" width = "25%">Day Collection </td><td nowrap="nowrap"  align="center" width = "25%">Month to Date</td><td nowrap="nowrap"  align="center" width = "25%">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
		
		
		$slno = 0;
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		$servicestotaltoday = 0;
		$servicestotalthismonth = 0;
		$thisyearupdationtotal = 0;
		$thisyearnewtotal = 0;
		$thisseenunmatchedtotal = 0;
		
		if(mysql_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$todaynew = ($fetch4['receiptamount'] == '')?'0' : $fetch4['receiptamount'];
		}
		else
		{
			$todaynew = 0;
		}
		if(mysql_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$monthtilldate = ($fetch5['receiptamount'] == '')?'0' : $fetch5['receiptamount'];
		}
		else
			$monthtilldate = 0;
			
		if(mysql_num_rows($result6) > 0)
		{
			$fetch6 = runmysqlqueryfetch($query6);
			$yeartilldate = ($fetch6['receiptamount'] == '')?'0' : $fetch6['receiptamount'];
		}
		else
			$yeartilldate = 0;
			
		if(mysql_num_rows($result7) > 0)
		{
			$fetch7 = runmysqlqueryfetch($query7);
			$thisseenunmatchedtotal = ($fetch7['receiptamount'] == '')?'0' : $fetch7['receiptamount'];
		}
		else
			$thisseenunmatchedtotal = 0;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynew)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($monthtilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($yeartilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thisseenunmatchedtotal)).'</td>';
		$grid .= '</tr>';
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynew)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($monthtilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($yeartilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thisseenunmatchedtotal)).'</td>';
		$grid .= '</tr>';
		
		$grid .= '</table>';
		
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			
			$msg = file_get_contents("../mailcontents/dayendreceiptsummary.htm");
			$textmsg = file_get_contents("../mailcontents/dayendreceiptsummary-email.txt");
			
			$subject = "Collection Summary for ".$date." [".$dealername."]";
			//Create an array of replace parameters
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$dealername;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##RECEIPTDETAILS##%^%".$grid;
			$array[] = "##BRANCHWISEDETAILS##%^%".'';
			$array[] = "##SUBJECT##%^%".$subject;
			//empty($toarray);
			
			unset($emailidsdealer);
			unset($toarraydealer);
			$emailarray1 = explode(',',$emailid);
			$emailcount = count($emailarray1);
		
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray1[$i]))
				{
						$emailidsdealer[$emailarray1[$i]] = $emailarray1[$i];
				}
			}
			
			$toarraydealer = $emailidsdealer;
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
			
			$html = $msg;
			$text = $textmsg;
			$filearray = array(
						array('../images/relyon-logo.jpg','inline','1234567890')
					);
		rslmail($fromname, $fromemail, $toarraydealer, $subject, $text, $html,null,$bccarray,$filearray); 
	}
	/*----------------------------Day End Summary Email to Branch heads-------------------------*/

	// Fetch branch head details to send email.
	$query4 = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid,branchname,inv_mas_branch.slno as branch from inv_mas_dealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch where inv_mas_dealer.branchhead = 'yes' ";
	$result4 = runmysqlquery($query4);
	
	
	while($fetch4 = mysql_fetch_array($result4))
	{
		$todaynew = 0;
		$monthtilldate = 0;
		$yeartilldate = 0;
		$todaynewtotalall = 0;
		$todaymonthtilldatetotalall = 0;
		$todayyeartilldatetotalall = 0;
		$notseenunmatched = 0;
		$notseenunmatchedtotalall = 0;
		
		// Select the dealers under Branch head based on Branch 
		$emailid = $fetch4['emailid'];
			//$emailid = 'meghana.b@relyonsoft.com';
				
		$name = $fetch4['businessname']; 
		$query5 = "select * from inv_mas_dealer where branch = '".$fetch4['branch']."' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where branchid = '".$fetch4['branch']."'))";
		$result5 = runmysqlquery($query5);
		
		$slno = 0;
		
		// put the details to table to display in email content.
		$branchgrid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Collection</td><td nowrap="nowrap"  width = "20%" align="center">Month to Date</td><td nowrap="nowrap"  width = "20%" align="center">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
		
		while($fetch5 = mysql_fetch_array($result5))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch5['slno'];
			$dealername = $fetch5['businessname']; 
			
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			$query8 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result8 = runmysqlquery($query8); 
			
			$query9 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched') and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result9 = runmysqlquery($query9); 
			
			
			if(mysql_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['receiptamount'] == '')?'0' : $fetch6['receiptamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysql_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['receiptamount'] == '')?'0' : $fetch7['receiptamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
			if(mysql_num_rows($result8) > 0)
			{
				$fetch8 = runmysqlqueryfetch($query8);
				$yeartilldate = ($fetch8['receiptamount'] == '')?'0' : $fetch8['receiptamount'];
			}
			else
			{
				$yeartilldate = 0;
			}
			if(mysql_num_rows($result9) > 0)
			{
				$fetch9 = runmysqlqueryfetch($query9);
				$notseenunmatched = ($fetch9['receiptamount'] == '')?'0' : $fetch9['receiptamount'];
			}
			else
			{
				$notseenunmatched = 0;
			}
			// Get Totals 
			$todaynewtotalall = $todaynewtotalall + $todaynew;
			$todaymonthtilldatetotalall = $todaymonthtilldatetotalall + $monthtilldate;
			$todayyeartilldatetotalall = $todayyeartilldatetotalall + $yeartilldate;
			$notseenunmatchedtotalall = $notseenunmatchedtotalall + $notseenunmatched;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynew)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($monthtilldate)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($yeartilldate)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatched)).'</td>';
			$branchgrid .= '</tr>';
			
		}
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynewtotalall)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaymonthtilldatetotalall)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todayyeartilldatetotalall)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatchedtotalall)).'</td>';
		$branchgrid .= '</tr>';
		$branchgrid .= '</table>';
		$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			
			$msg = file_get_contents("../mailcontents/dayendreceiptsummary.htm");
			$textmsg = file_get_contents("../mailcontents/dayendreceiptsummary-email.txt");
			
			$subject = "Collection Summary for ".$date." [".$fetch4['branchname']."]";
			//Create an array of replace parameters
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$name;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##RECEIPTDETAILS##%^%".$branchgrid;
			$array[] = "##BRANCHWISEDETAILS##%^%".'';
			$array[] = "##SUBJECT##%^%".$subject;
			empty($toarray);
			unset($emailidsbranchhead);
			unset($toarraybranchhead);
			
			
			$emailarray = explode(',',$emailid);
			$emailcount = count($emailarray);
		
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray[$i]))
				{
						$emailidsbranchhead[$emailarray[$i]] = $emailarray[$i];
				}
			}
			$toarraybranchhead = $emailidsbranchhead;
			
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
			
			$html = $msg;
			$text = $textmsg;
			$filearray = array(
						array('../images/relyon-logo.jpg','inline','1234567890')
					);
			rslmail($fromname, $fromemail, $toarraybranchhead, $subject, $text, $html,null,$bccarray,$filearray); 
	}
	

	
	/*--------------------------------------Day End Summary Email to Region heads-----------------------*/
	
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
		$emailid = $arrayvalue['emailid'];
		$name = $arrayvalue['name'];
		
		$query4 = "select * from inv_mas_dealer where region = '".$regionid."' and inv_mas_dealer.disablelogin = 'no' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where regionid = '".$regionid."'));";
		
		$result4 = runmysqlquery($query4);
		$slno = 0;
		
		// put the details to table to display in email content.
		$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Dealer Wise Summary</strong></div>';
		$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Collection</td><td nowrap="nowrap"  width = "20%" align="center">Month to Date</td><td nowrap="nowrap"  width = "20%" align="center">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
		
		$todaynew = 0;
		$monthtilldate = 0;
		$yeartilldate = 0;
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		$todayyeartilldatetotal = 0;
		$notseenunmatched = 0;
		$notseenunmatchedtotalall = 0;
		
		while($fetch4 = mysql_fetch_array($result4))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch4['slno'];
			$dealername = $fetch4['businessname']; 
			
			// Fetch today's details 
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			
			// Fetch this year details
			
			$query8 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result8 = runmysqlquery($query8); 
			
			$query9 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  inv_invoicenumbers.dealerid = '".$dealerid."' and (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched')  and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result9 = runmysqlquery($query9); 


			if(mysql_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['receiptamount'] == '')?'0' : $fetch6['receiptamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysql_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['receiptamount'] == '')?'0' : $fetch7['receiptamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
			if(mysql_num_rows($result8) > 0)
			{
				$fetch8 = runmysqlqueryfetch($query8);
				$yeartilldate = ($fetch8['receiptamount'] == '')?'0' : $fetch8['receiptamount'];
			}
			else
			{
				$yeartilldate = 0;
			}
			if(mysql_num_rows($result9) > 0)
			{
				$fetch9 = runmysqlqueryfetch($query9);
				$notseenunmatched = ($fetch9['receiptamount'] == '')?'0' : $fetch9['receiptamount'];
			}
			else
			{
				$notseenunmatched = 0;
			}
			// Get Totals 
			
			$todaynewtotal = $todaynewtotal + $todaynew;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
			$todayyeartilldatetotal = $todayyeartilldatetotal + $yeartilldate;
			$notseenunmatchedtotalall = $notseenunmatchedtotalall + $notseenunmatched;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynew)).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($monthtilldate)).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($yeartilldate)).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatched)).'</td>';
			$grid .= '</tr>';
			
		}
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynewtotal)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaymonthtilldatetotal)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todayyeartilldatetotal)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatchedtotalall)).'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';
		
		// Get Branch wise summary for the area heads 
		
		$query = "select inv_mas_branch.branchname as branchname,today.receiptamount as todaysales,thismonth.receiptamount 
as thismonthsales,thisyear.receiptamount as thisyearsales,notseenunmatch.receiptamount as notseenunmatchsales from inv_mas_branch  
left join 
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where  ".$todaysdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' and inv_invoicenumbers.regionid = '".$regionid."' group by  branchid) as today on today.branchid = inv_mas_branch.slno 
left join
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where ".$monthsdatepiece." and  inv_invoicenumbers.`status` <> 'CANCELLED' and
inv_mas_receipt.`status` <> 'CANCELLED' and inv_invoicenumbers.regionid = '".$regionid."' group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno 
left join
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED'
 and inv_invoicenumbers.regionid = '".$regionid."' group by branchid)as thisyear on 
thisyear.branchid = inv_mas_branch.slno
left join
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
where (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched') and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' and ".$yeardatepiece."
 and inv_invoicenumbers.regionid = '".$regionid."' group by branchid)as notseenunmatch on 
notseenunmatch.branchid = inv_mas_branch.slno
 where region = '".$regionid."' order by inv_mas_branch.branchname;";

		$result = runmysqlquery($query);

		// Create Table to display brach wise Summary
		$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
		$branchgrid  .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Collection</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0 ;
		$todayyeartilldatetotal = 0 ;
		
		$todaysale = 0;
		$thismonthsale = 0 ;
		$thisyearsale = 0 ;
		$notseenunmatched = 0;
		$notseenunmatchedtotalall = 0;
		$slno = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$slno++;
			$todaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
			$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
			$thisyearsale = ($fetch['thisyearsales'] == '')? '0' : $fetch['thisyearsales'];
			$notseenunmatched = ($fetch['notseenunmatchsales'] == '')? '0' : $fetch['notseenunmatchsales'];
			
			$todaynewtotal = $todaynewtotal + $todaysale;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
			$todayyeartilldatetotal = $todayyeartilldatetotal + $thisyearsale;
			$notseenunmatchedtotalall = $notseenunmatchedtotalall + $notseenunmatched;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaysale)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thismonthsale)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thisyearsale)).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatched)).'</td>';
			$branchgrid .= '</tr>';
			
		}
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynewtotal)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaymonthtilldatetotal)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todayyeartilldatetotal)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatchedtotalall)).'</td>';
		$branchgrid .= '</tr>';
		
		
		$branchgrid .= '</table>';
		

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/dayendreceiptsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendreceiptsummary-email.txt");
		
		$subject = "Collection Summary for ".$date." [".$managedarea."]";
	

		unset($toarray);
		$emailiddisplay = '';
		for($emailidcount = 0; $emailidcount < count($emailid); $emailidcount++)
		{
			if($emailiddisplay == '')
			{
				$emailiddisplay .= $emailid[$emailidcount];
			}
			else
			{
				$emailiddisplay .= ', '.$emailid[$emailidcount];
			}
			$toarray[$name[$emailidcount]] = $emailid[$emailidcount];
		}		
		
		//Create an array of replace parameters
		$array = array();
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$name[0];
		$array[] = "##EMAILID##%^%".$emailiddisplay;
		$array[] = "##RECEIPTDETAILS##%^%".$grid;
		$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
		$array[] = "##SUBJECT##%^%".$subject;
		
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $msg;
		$text = $textmsg;
		$filearray = array(
					array('../images/relyon-logo.jpg','inline','1234567890')
				);
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 

	}
	/*--------------------------------------Day End Sumary Email to Accounts----------------------------------   */

	$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Region Wise Summary</strong></div>';
	$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Region</td><td nowrap="nowrap"  align="center">Day Collection</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
	
	
	
	$slno = 0;
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0;
	$todayyeartilldatetotal = 0;
	$todaynew = 0;
	$monthtilldate = 0;
	$yeartilldate = 0;
	$notseenunmatched = 0;
	$notseenunmatchedtotalall = 0;
	// Region wise and producyt wise Summary 
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$slno++;
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
	
		// Fetch details of current date
		$query4 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
 where inv_invoicenumbers.regionid = '".$regionid."' and ".$todaysdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' ";
		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		$query5 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_invoicenumbers.regionid = '".$regionid."' and ".$monthsdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED'"; 
		$result5 = runmysqlquery($query5);
		
		$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_invoicenumbers.regionid = '".$regionid."' and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED'"; 
		$result6 = runmysqlquery($query6);
		
		$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_mas_receipt.receiptamount),'0') as receiptamount
from inv_mas_receipt left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where inv_invoicenumbers.regionid = '".$regionid."' and (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched') and ".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED'"; 
		$result7 = runmysqlquery($query7);
		
		if(mysql_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$todaynew = ($fetch4['receiptamount'] == '')?'0' : $fetch4['receiptamount'];
		}
		else
		{
			$todaynew = 0;
		}
		if(mysql_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$monthtilldate = ($fetch5['receiptamount'] == '')?'0' : $fetch5['receiptamount'];
		}
		else
		{
			$monthtilldate = 0;
		}
		if(mysql_num_rows($result6) > 0)
		{
			$fetch6 = runmysqlqueryfetch($query6);
			$yeartilldate = ($fetch6['receiptamount'] == '')?'0' : $fetch6['receiptamount'];
		}
		else
		{
			$yeartilldate = 0;
		}
		if(mysql_num_rows($result7) > 0)
		{
			$fetch7 = runmysqlqueryfetch($query7);
			$notseenunmatched = ($fetch7['receiptamount'] == '')?'0' : $fetch7['receiptamount'];
		}
		else
		{
			$notseenunmatched = 0;
		}
		// Get Totals 
		
		$todaynewtotal = $todaynewtotal + $todaynew;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
		$todayyeartilldatetotal = $todayyeartilldatetotal + $yeartilldate;
		$notseenunmatchedtotalall = $notseenunmatchedtotalall + $notseenunmatched;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$managedarea.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynew)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($monthtilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($yeartilldate)).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatched)).'</td>';
		$grid .= '</tr>';
		
	}
	$grid .= '<tr>';
	$grid .= '<td nowrap="nowrap"  align="center">&nbsp;</td>';
	$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynewtotal)).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaymonthtilldatetotal)).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todayyeartilldatetotal)).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatchedtotalall)).'</td>';
	$grid .= '</tr>';
	$grid .= '</table>';
	

	// Branch wise sales summary for Management
	
	// Fetch today's data
	$query = "select inv_mas_branch.branchname as branchname,today.receiptamount as todaysales,thismonth.receiptamount as thismonthsales,thisyear.receiptamount as thisyearsales,notseenunmatch.receiptamount as notseenunmatchsales  from inv_mas_branch 
left join 
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where 
".$todaysdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by  branchid) as today on today.branchid = inv_mas_branch.slno 
left join
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where 
".$monthsdatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno 
left join
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where 
".$yeardatepiece." and inv_invoicenumbers.`status` <> 'CANCELLED' and inv_mas_receipt.`status` <> 'CANCELLED' group by  branchid)as thisyear on thisyear.branchid = inv_mas_branch.slno 
left join 
(select branchid,branch,sum(receiptamount) as receiptamount 
from inv_mas_receipt 
left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno where 
 (inv_mas_receipt.reconsilation = 'notseen' or inv_mas_receipt.reconsilation = 'unmatched') and inv_invoicenumbers.`status` <> 'CANCELLED' and ".$yeardatepiece." and inv_mas_receipt.`status` <> 'CANCELLED' group by  branchid)as notseenunmatch on notseenunmatch.branchid = inv_mas_branch.slno 
order by inv_mas_branch.branchname;";
	$result = runmysqlquery($query);
	
	// Create Table to display brach wise Summary
	$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
	$branchgrid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td><td nowrap="nowrap"  align="center" width = "25%">Unmatched</td></tr>';
	
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0 ;$thisyearsale=0;$todayyeartilldatetotal=0;
	$slno = 0;$notseenunmatched = 0;$notseenunmatchedtotalall = 0;
	while($fetch = mysql_fetch_array($result))
	{
		$slno++;
		$tadaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
		$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
		$thisyearsale = ($fetch['thisyearsales'] == '')? '0' : $fetch['thisyearsales'];
		$notseenunmatched = ($fetch['notseenunmatchsales'] == '')? '0' : $fetch['notseenunmatchsales'];
		
		$todaynewtotal = $todaynewtotal + $tadaysale;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
		$todayyeartilldatetotal = $todayyeartilldatetotal + $thisyearsale;
		$notseenunmatchedtotalall = $notseenunmatchedtotalall + $notseenunmatched;
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($tadaysale)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thismonthsale)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($thisyearsale)).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatched)).'</td>';
		$branchgrid .= '</tr>';
		
	}
	$branchgrid .= '<tr>';
	$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaynewtotal)).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todaymonthtilldatetotal)).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($todayyeartilldatetotal)).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber(round($notseenunmatchedtotalall)).'</td>';
	$branchgrid .= '</tr>';
	
	$branchgrid .= '</table>';
	

	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$msg = file_get_contents("../mailcontents/dayendreceiptsummaryaccounts.htm");
	$textmsg = file_get_contents("../mailcontents/dayendreceiptsummaryaccounts-email.txt");
	
	$subject = "Collection Summary for ".$date." [Management] ";
	//Create an array of replace parameters
	$array = array();
	//$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".' Management';
	$array[] = "##EMAILID##%^%".'accounts@relyonsoft.com, nitinall@relyonsoft.com, hsn@relyonsoft.com';
	$array[] = "##RECEIPTDETAILS##%^%".$grid;
	$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
	$array[] = "##SUBJECT##%^%".$subject;
	//$emailarray = explode(',',$emailid);
	//$emailcount = count($emailid);
	$emailid = array('accounts@relyonsoft.com','nitinall@relyonsoft.com','hsn@relyonsoft.com');
		//$emailid = array('meghana.b@relyonsoft.com');
	
	unset($toarray);
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailid);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailid[$i]))
		{
				$emailidaccounts[$emailid[$i]] = $emailid[$i];
		}
	}
	$toarray = $emailidaccounts;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
	
	$html = $msg;
	$text = $textmsg;
	$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890')
			);
		
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 

?>