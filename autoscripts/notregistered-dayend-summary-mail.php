<?php
	ini_set('memory_limit', '-1');
	set_time_limit(0);
	include("../functions/phpfunctions.php");
	
	$yesterday=date("Y-m-d", time()-86400);

	$date = changedateformat($yesterday);
	
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
	$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid from inv_mas_dealer where inv_mas_dealer.disablelogin = 'no'  and inv_mas_dealer.enablebilling = 'yes' order by slno";
	$result = runmysqlquery($query);
	while($fetch = mysql_fetch_array($result))
	{
		
		$dealerid = $fetch['slno'];
		$dealername = $fetch['businessname'];
		$emailid = $fetch['emailid'];
			//$emailid = 'meghana.b@relyonsoft.com';
			
		// Fetch details for billed but Not Registered between 1-7(Days)
		$query1 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.dealerid = '".$dealerid."' group by inv_invoicenumbers.dealerid";
		$result1 = runmysqlquery($query1);
		
		// Fetch details for billed but Not Registered between 8-15(Days)
		$query2 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
		$result2 = runmysqlquery($query2); 
		
		// Fetch details for billed but Not Registered between 16-30(Days)
		$query3 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
		$result3 = runmysqlquery($query3); 
		
		// Fetch details for billed but Not Registered between 31-60(Days)
		$query4 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60')) and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
		$result4 = runmysqlquery($query4);
		
		// Fetch details for billed but Not Registered between >61(Days)
		$query5 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
		$result5 = runmysqlquery($query5);
		
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr><td nowrap="nowrap"  width="17%" align="center"><strong>Dealer Name</strong></td><td nowrap="nowrap" width="83%" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td>&nbsp;</td><td nowrap="nowrap"align="center">1-7</td><td nowrap="nowrap" align="center">8-15</td><td nowrap="nowrap" align="center">16-30</td><td nowrap="nowrap" align="center">31-60</td><td nowrap="nowrap" align="center">&gt; 61</td></tr>';
		
		
		$slno = 0;
		$newbillcount17 = 0;
		$newbillcount815 = 0;
		$newbillcount1630 = 0;
		$newbillcount3160 = 0;
		$newbillcount61 = 0;
		$overalltotal = 0;
		
		if(mysql_num_rows($result1) > 0)
		{
			$fetch1 = runmysqlqueryfetch($query1);
			$newbillcount17 = ($fetch1['billcount17'] == '')?'0' : $fetch1['billcount17'];
		}
		else
		{
			$newbillcount17 = 0;
		}
		if(mysql_num_rows($result2) > 0)
		{
			$fetch2 = runmysqlqueryfetch($query2);
			$newbillcount815 = ($fetch2['billcount815'] == '')?'0' : $fetch2['billcount815'];
		}
		else
		{
			$newbillcount815 = 0;
		}
		if(mysql_num_rows($result3) > 0)
		{
			$fetch3 = runmysqlqueryfetch($query3);
			$newbillcount1630 = ($fetch3['billcount1630'] == '')?'0' : $fetch3['billcount1630'];
		}
		else
		{
			$newbillcount1630 = 0;
		}
		if(mysql_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$newbillcount3160 = ($fetch4['billcount3160'] == '')?'0' : $fetch4['billcount3160'];
		}
		else
		{
			$newbillcount3160 = 0;
		}
		if(mysql_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$newbillcount61 = ($fetch5['billcount61'] == '')?'0' : $fetch5['billcount61'];
		}
		else
		{
			$newbillcount61 = 0;
		}
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount17.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount815.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount1630.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount3160.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount61.'</td>';
		$grid .= '</tr>';
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount17.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount815.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount1630.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount3160.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount61.'</td>';
		$grid .= '</tr>';
		
		$grid .= '</table>';
		
		$overalltotal = $newbillcount17 + $newbillcount815 + $newbillcount1630 + $newbillcount3160 + $newbillcount61;
		if($overalltotal > 0)
		{
			$filedetails = generatecustomerexcel($dealerid,'dealer');
			$filedetailssplit = explode('$^$',$filedetails);
			$filepath = $filedetailssplit[0];
			$filebasename = $filedetailssplit[1];
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			
			$msg = file_get_contents("../mailcontents/dayendnotregisteredsummary.htm");
			$textmsg = file_get_contents("../mailcontents/dayendnotregisteredsummary.txt");
			
			$subject = "Billed but Not Registered Summary for ".$date." [".$dealername."]";
			
			//Create an array of replace parameters
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$dealername;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##DEALERDETAILS##%^%".$grid;
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
						array('../images/relyon-logo.jpg','inline','1234567890'),
						array($filepath.$filebasename,'attachment','1234567891')
						);
		
				
			rslmail($fromname, $fromemail, $toarraydealer, $subject, $text, $html,null,$bccarray,$filearray);
			fileDelete($filepath,$filebasename); 
		}
		
	}
	
	/*----------------------------Day End Summary Email to Branch heads-------------------------*/

	// Fetch branch head details to send email.
	$query6 = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid,branchname,inv_mas_branch.slno as branch from inv_mas_dealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch where inv_mas_dealer.branchhead = 'yes' ";
	$result6 = runmysqlquery($query6);
	
	
	while($fetch6 = mysql_fetch_array($result6))
	{
		$slno = 0;
		$newbillcount17 = 0;
		$newbillcount815 = 0;
		$newbillcount1630 = 0;
		$newbillcount3160 = 0;
		$newbillcount61 = 0;
		
		$totalbillcount17 = 0;
		$totalbillcount815 = 0;
		$totalbillcount1630 = 0;
		$totalbillcount3160 = 0;
		$totalbillcount61 = 0;
		
		// Select the dealers under Branch head based on Branch 
		$emailid = $fetch6['emailid'];
			//$emailid = 'meghana.b@relyonsoft.com';
				
		$name = $fetch6['businessname']; 
		$query7 = "select * from inv_mas_dealer where branch = '".$fetch6['branch']."' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where branchid = '".$fetch6['branch']."'))";
		$result7 = runmysqlquery($query7);
		
		$filedetails = generatecustomerexcel($fetch6['branch'],'branch');
		$filedetailssplit = explode('$^$',$filedetails);
		$filepath = $filedetailssplit[0];
		$filebasename = $filedetailssplit[1];
		
		$slno = 0;
		
		// put the details to table to display in email content.
		$branchgrid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr><td width="7%"  align="center" nowrap="nowrap"><strong>Sl No</strong></td><td nowrap="nowrap"  width="14%" align="center"><strong>Dealer Name</strong></td><td nowrap="nowrap" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td align="center" >&nbsp;</td><td align="center">&nbsp;</td><td align="center" width="15%">1-7</td><td align="center" width="16%">8-15</td><td align="center" width="17%">15-30</td><td align="center" width="16%">31-60</td><td align="center" width="15%">&gt; 61</td></tr>';
		
		while($fetch7 = mysql_fetch_array($result7))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch7['slno'];
			$dealername = $fetch7['businessname']; 
			
			
			// Fetch details for billed but Not Registered between 1-7(Days)
			$query8 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17 from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7')) and inv_invoicenumbers.description <> ''  and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."' group by inv_invoicenumbers.dealerid";
			$result8 = runmysqlquery($query8);
			
			// Fetch details for billed but Not Registered between 8-15(Days)
			$query9 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815 from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result9 = runmysqlquery($query9); 
			
			// Fetch details for billed but Not Registered between 16-30(Days)
			$query10 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630 from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result10 = runmysqlquery($query10); 
			
			// Fetch details for billed but Not Registered between 31-60(Days)
			$query11 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result11 = runmysqlquery($query11);
			
			// Fetch details for billed but Not Registered between >61(Days)
			$query12 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result12 = runmysqlquery($query12);
			
			
			if(mysql_num_rows($result8) > 0)
			{
				$fetch8 = runmysqlqueryfetch($query8);
				$newbillcount17 = ($fetch8['billcount17'] == '')?'0' : $fetch8['billcount17'];
			}
			else
			{
				$newbillcount17 = 0;
			}
			if(mysql_num_rows($result9) > 0)
			{
				$fetch9 = runmysqlqueryfetch($query9);
				$newbillcount815 = ($fetch9['billcount815'] == '')?'0' : $fetch9['billcount815'];
			}
			else
			{
				$newbillcount815 = 0;
			}
			if(mysql_num_rows($result10) > 0)
			{
				$fetch10 = runmysqlqueryfetch($query10);
				$newbillcount1630 = ($fetch10['billcount1630'] == '')?'0' : $fetch10['billcount1630'];
			}
			else
			{
				$newbillcount1630 = 0;
			}
			if(mysql_num_rows($result11) > 0)
			{
				$fetch11 = runmysqlqueryfetch($query11);
				$newbillcount3160 = ($fetch11['billcount3160'] == '')?'0' : $fetch11['billcount3160'];
			}
			else
			{
				$newbillcount3160 = 0;
			}
			if(mysql_num_rows($result12) > 0)
			{
				$fetch12 = runmysqlqueryfetch($query12);
				$newbillcount61 = ($fetch12['billcount61'] == '')?'0' : $fetch12['billcount61'];
			}
			else
			{
				$newbillcount61 = 0;
			}
		
			// Get Totals 
			$totalbillcount17 = $totalbillcount17 + $newbillcount17;
			$totalbillcount815 = $totalbillcount815 + $newbillcount815;
			$totalbillcount1630 = $totalbillcount1630 + $newbillcount1630;
			$totalbillcount3160 = $totalbillcount3160 + $newbillcount3160;
			$totalbillcount61 = $totalbillcount61 + $newbillcount61;
			
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$newbillcount17.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$newbillcount815.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$newbillcount1630.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$newbillcount3160.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$newbillcount61.'</td>';
			$branchgrid .= '</tr>';
			
		}
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount17.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount815.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount1630.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount3160.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount61.'</td>';
		$branchgrid .= '</tr>';
		$branchgrid .= '</table>';
		//echo($branchgrid);exit;
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		
		$msg = file_get_contents("../mailcontents/dayendnotregisteredsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendnotregisteredsummary.txt");
		
		$subject = "Billed but Not Registered Summary for ".$date." [".$fetch6['branchname']."]";
		//Create an array of replace parameters
		$array = array();
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$name;
		$array[] = "##EMAILID##%^%".$emailid;
		$array[] = "##DEALERDETAILS##%^%".$branchgrid;
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
					array('../images/relyon-logo.jpg','inline','1234567890'),
					array($filepath.$filebasename,'attachment','1234567891')
				);
		rslmail($fromname, $fromemail, $toarraybranchhead, $subject, $text, $html,null,$bccarray,$filearray); 
		fileDelete($filepath,$filebasename);
	}
	

	
	/*--------------------------------------Day End Summary Email to Region heads-----------------------*/
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
		$emailid = $arrayvalue['emailid'];
		$name = $arrayvalue['name'];
		
		$query13 = "select * from inv_mas_dealer where region = '".$regionid."' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where regionid = '".$regionid."'));";
		
		$result13 = runmysqlquery($query13);
		$slno = 0;
		
		$filedetails = generatecustomerexcel($regionid,'region');
		$filedetailssplit = explode('$^$',$filedetails);
		$filepath = $filedetailssplit[0];
		$filebasename = $filedetailssplit[1];
		
		// put the details to table to display in email content.
		$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Dealer Wise Summary</strong></div>';
		$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		
		//Write the header Row of the table
		$grid .= '<tr><td width="7%"  align="center" nowrap="nowrap"><strong>Sl No</strong></td><td nowrap="nowrap"  width="14%" align="center"><strong>Dealer Name</strong></td><td nowrap="nowrap" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td align="center" >&nbsp;</td><td align="center">&nbsp;</td><td align="center" width="15%">1-7</td><td align="center" width="16%">8-15</td><td align="center" width="17%">15-30</td><td align="center" width="16%">31-60</td><td align="center" width="15%">&gt; 61</td></tr>';
		
		$newbillcount17 = 0;
		$newbillcount815 = 0;
		$newbillcount1630 = 0;
		$newbillcount3160 = 0;
		$newbillcount61 = 0;
		
		$totalbillcount17 = 0;
		$totalbillcount815 = 0;
		$totalbillcount1630 = 0;
		$totalbillcount3160 = 0;
		$totalbillcount61 = 0;
		
		while($fetch13 = mysql_fetch_array($result13))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch13['slno'];
			$dealername = $fetch13['businessname']; 
			
				// Fetch details for billed but Not Registered between 1-7(Days)
			$query14 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7')) and inv_invoicenumbers.description <> '' 	and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."' group by inv_invoicenumbers.dealerid";
			$result14 = runmysqlquery($query14);
			
			// Fetch details for billed but Not Registered between 8-15(Days)
			$query15 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result15 = runmysqlquery($query15); 
			
			// Fetch details for billed but Not Registered between 16-30(Days)
			$query16 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30')) and inv_invoicenumbers.description <> '' 	and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result16 = runmysqlquery($query16); 
			
			// Fetch details for billed but Not Registered between 31-60(Days)
			$query17 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and	DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60')) and inv_invoicenumbers.description <> '' 	and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 	and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result17 = runmysqlquery($query17);
			
			// Fetch details for billed but Not Registered between >61(Days)
			$query18 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 	where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and  inv_invoicenumbers.dealerid = '".$dealerid."'  group by inv_invoicenumbers.dealerid";
			$result18 = runmysqlquery($query18);
			
			
			if(mysql_num_rows($result14) > 0)
			{
				$fetch14 = runmysqlqueryfetch($query14);
				$newbillcount17 = ($fetch14['billcount17'] == '')?'0' : $fetch14['billcount17'];
			}
			else
			{
				$newbillcount17 = 0;
			}
			if(mysql_num_rows($result15) > 0)
			{
				$fetch15 = runmysqlqueryfetch($query15);
				$newbillcount815 = ($fetch15['billcount815'] == '')?'0' : $fetch15['billcount815'];
			}
			else
			{
				$newbillcount815 = 0;
			}
			if(mysql_num_rows($result16) > 0)
			{
				$fetch16 = runmysqlqueryfetch($query16);
				$newbillcount1630 = ($fetch16['billcount1630'] == '')?'0' : $fetch16['billcount1630'];
			}
			else
			{
				$newbillcount1630 = 0;
			}
			if(mysql_num_rows($result17) > 0)
			{
				$fetch17 = runmysqlqueryfetch($query17);
				$newbillcount3160 = ($fetch17['billcount3160'] == '')?'0' : $fetch17['billcount3160'];
			}
			else
			{
				$newbillcount3160 = 0;
			}
			if(mysql_num_rows($result18) > 0)
			{
				$fetch18 = runmysqlqueryfetch($query18);
				$newbillcount61 = ($fetch18['billcount61'] == '')?'0' : $fetch18['billcount61'];
			}
			else
			{
				$newbillcount61 = 0;
			}
		
			// Get Totals 
			$totalbillcount17 = $totalbillcount17 + $newbillcount17;
			$totalbillcount815 = $totalbillcount815 + $newbillcount815;
			$totalbillcount1630 = $totalbillcount1630 + $newbillcount1630;
			$totalbillcount3160 = $totalbillcount3160 + $newbillcount3160;
			$totalbillcount61 = $totalbillcount61 + $newbillcount61;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount17.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount815.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount1630.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount3160.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount61.'</td>';
			$grid .= '</tr>';
			
		}
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount17.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount815.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount1630.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount3160.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount61.'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';
		
		
		// Get Branch wise summary for the area heads 
		$query19 = "select inv_mas_branch.branchname as branchname,bill17.billcount17 as billcount17,bill815.billcount815 
as billcount815,bill1630.billcount1630 as billcount1630,bill3160.billcount3160 as billcount3160,bill61.billcount61 as billcount61 from inv_mas_branch  
left join 
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.branchid) as bill17 on bill17.branchid = inv_mas_branch.slno 
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.branchid)as bill815 on bill815.branchid = inv_mas_branch.slno 
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.branchid)as bill1630 on 
bill1630.branchid = inv_mas_branch.slno
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.branchid) as bill3160 on 
bill3160.branchid = inv_mas_branch.slno
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
and  inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.branchid) as bill61 on 
bill61.branchid = inv_mas_branch.slno
 where region = '".$regionid."' order by inv_mas_branch.branchname;";

		$result19 = runmysqlquery($query19);

		// Create Table to display brach wise Summary
		$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
		$branchgrid  .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr><td width="7%"  align="center" nowrap="nowrap"><strong>Sl No</strong></td><td nowrap="nowrap"  width="14%" align="center"><strong>Branch Name</strong></td><td nowrap="nowrap" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td align="center" >&nbsp;</td><td align="center">&nbsp;</td><td align="center" width="15%">1-7</td><td align="center" width="16%">8-15</td><td align="center" width="17%">15-30</td><td align="center" width="16%">31-60</td><td align="center" width="15%">&gt; 61</td></tr>';
		
		$totalbillcount17 = 0;
		$totalbillcount815 = 0 ;
		$totalbillcount1630 = 0 ;
		$totalbillcount3160 = 0 ;
		$totalbillcount61 = 0 ;
		
		$billcount17 = 0;
		$billcount815 = 0 ;
		$billcount1630 = 0 ;
		$billcount3160 = 0;
		$billcount61 = 0;
		$slno = 0;
		while($fetch19 = mysql_fetch_array($result19))
		{
			$slno++;
			$billcount17 = ($fetch19['billcount17'] == '')? '0' : $fetch19['billcount17'];
			$billcount815 = ($fetch19['billcount815'] == '')? '0' : $fetch19['billcount815'];
			$billcount1630 = ($fetch19['billcount1630'] == '')? '0' : $fetch19['billcount1630'];
			$billcount3160 = ($fetch19['billcount3160'] == '')? '0' : $fetch19['billcount3160'];
			$billcount61 = ($fetch19['billcount61'] == '')? '0' : $fetch19['billcount61'];
			
			$totalbillcount17 = $totalbillcount17 + $billcount17;
			$totalbillcount815 = $totalbillcount815 + $billcount815;
			$totalbillcount1630 = $totalbillcount1630 + $billcount1630;
			$totalbillcount3160 = $totalbillcount3160 + $billcount3160;
			$totalbillcount61 = $totalbillcount61 + $billcount61;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch19['branchname'].'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount17.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount815.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount1630.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount3160.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount61.'</td>';
			$branchgrid .= '</tr>';
			
		}
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount17.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount815.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount1630.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount3160.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount61.'</td>';
		$branchgrid .= '</tr>';
		
		
		$branchgrid .= '</table>';
		

		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/dayendnotregisteredsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendnotregisteredsummary.txt");
		
		$subject = "Billed but Not Registered Summary for ".$date." [".$managedarea."]";
	

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
		$array[] = "##DEALERDETAILS##%^%".$grid;
		$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
		$array[] = "##SUBJECT##%^%".$subject;
		
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		$html = $msg;
		$text = $textmsg;
		$filearray = array(
					array('../images/relyon-logo.jpg','inline','1234567890'),
					array($filepath.$filebasename,'attachment','1234567891')
				);
		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
		fileDelete($filepath,$filebasename);

	}
	/*--------------------------------------Day End Sumary Email to HSN/Nitin----------------------------------   */

	$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Region Wise Summary</strong></div>';
	$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$grid .= '<tr><td width="7%"  align="center" nowrap="nowrap"><strong>Sl No</strong></td><td nowrap="nowrap"  width="14%" align="center"><strong>Region</strong></td><td nowrap="nowrap" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td align="center" >&nbsp;</td><td align="center">&nbsp;</td><td align="center" width="15%">1-7</td><td align="center" width="16%">8-15</td><td align="center" width="17%">15-30</td><td align="center" width="16%">31-60</td><td align="center" width="15%">&gt; 61</td></tr>';
	
	
	
	$slno = 0;
	$newbillcount17 = 0;
	$newbillcount815 = 0;
	$newbillcount1630 = 0;
	$newbillcount3160 = 0;
	$newbillcount61 = 0;
	
	$totalbillcount17 = 0;
	$totalbillcount815 = 0;
	$totalbillcount1630 = 0;
	$totalbillcount3160 = 0;
	$totalbillcount61 = 0;
	// Region wise and producyt wise Summary 
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$slno++;
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
	
			// Fetch details for billed but Not Registered between 1-7(Days)
			$query20 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.regionid";
			$result20 = runmysqlquery($query20);
			
			// Fetch details for billed but Not Registered between 8-15(Days)
			$query21 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.regionid";
			$result21 = runmysqlquery($query21); 
			
			// Fetch details for billed but Not Registered between 16-30(Days)
			$query22 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.regionid";
			$result22 = runmysqlquery($query22); 
			
			// Fetch details for billed but Not Registered between 31-60(Days)
			$query23 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60')) and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.regionid";
			$result23 = runmysqlquery($query23);
			
			// Fetch details for billed but Not Registered between >61(Days)
			$query24 = "select inv_invoicenumbers.dealerid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' and inv_invoicenumbers.regionid = '".$regionid."'  group by inv_invoicenumbers.regionid";
			$result24 = runmysqlquery($query24);
			
			
			if(mysql_num_rows($result20) > 0)
			{
				$fetch20 = runmysqlqueryfetch($query20);
				$newbillcount17 = ($fetch20['billcount17'] == '')?'0' : $fetch20['billcount17'];
			}
			else
			{
				$newbillcount17 = 0;
			}
			if(mysql_num_rows($result21) > 0)
			{
				$fetch21 = runmysqlqueryfetch($query21);
				$newbillcount815 = ($fetch21['billcount815'] == '')?'0' : $fetch21['billcount815'];
			}
			else
			{
				$newbillcount815 = 0;
			}
			if(mysql_num_rows($result22) > 0)
			{
				$fetch22 = runmysqlqueryfetch($query22);
				$newbillcount1630 = ($fetch22['billcount1630'] == '')?'0' : $fetch22['billcount1630'];
			}
			else
			{
				$newbillcount1630 = 0;
			}
			if(mysql_num_rows($result23) > 0)
			{
				$fetch23 = runmysqlqueryfetch($query23);
				$newbillcount3160 = ($fetch23['billcount3160'] == '')?'0' : $fetch23['billcount3160'];
			}
			else
			{
				$newbillcount3160 = 0;
			}
			if(mysql_num_rows($result24) > 0)
			{
				$fetch24 = runmysqlqueryfetch($query24);
				$newbillcount61 = ($fetch24['billcount61'] == '')?'0' : $fetch24['billcount61'];
			}
			else
			{
				$newbillcount61 = 0;
			}
		
			// Get Totals 
			$totalbillcount17 = $totalbillcount17 + $newbillcount17;
			$totalbillcount815 = $totalbillcount815 + $newbillcount815;
			$totalbillcount1630 = $totalbillcount1630 + $newbillcount1630;
			$totalbillcount3160 = $totalbillcount3160 + $newbillcount3160;
			$totalbillcount61 = $totalbillcount61 + $newbillcount61;
			
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$managedarea.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount17.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount815.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount1630.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount3160.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$newbillcount61.'</td>';
		$grid .= '</tr>';
		
	}
	$grid .= '<tr>';
	$grid .= '<td nowrap="nowrap"  align="center">&nbsp;</td>';
	$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount17.'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount815.'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount1630.'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount3160.'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount61.'</td>';
	$grid .= '</tr>';
	$grid .= '</table>';

	
	// Branch wise sales summary for Management
	$query25 = "select inv_mas_branch.branchname as branchname,bill17.billcount17 as billcount17,bill815.billcount815 
as billcount815,bill1630.billcount1630 as billcount1630,bill3160.billcount3160 as billcount3160,bill61.billcount61 as billcount61 from inv_mas_branch  
left join 
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount17
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '1' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '7'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
 group by inv_invoicenumbers.branchid) as bill17 on bill17.branchid = inv_mas_branch.slno 
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount815
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '8' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '15'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
 group by inv_invoicenumbers.branchid)as bill815 on bill815.branchid = inv_mas_branch.slno 
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount1630
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '16' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '30'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
group by inv_invoicenumbers.branchid)as bill1630 on 
bill1630.branchid = inv_mas_branch.slno
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount3160
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where ((DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '31' and
DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10))<= '60'))  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
group by inv_invoicenumbers.branchid) as bill3160 on 
bill3160.branchid = inv_mas_branch.slno
left join
(select inv_invoicenumbers.branchid, ifnull(count(distinct inv_invoicenumbers.slno),'0') as billcount61
from inv_invoicenumbers left join inv_dealercard on inv_dealercard.invoiceid = inv_invoicenumbers.slno 
left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where (DATEDIFF(CURDATE(),left(inv_invoicenumbers.createddate,10)) >= '61' )  and inv_invoicenumbers.description <> '' 
and inv_invoicenumbers.products <> '' and inv_invoicenumbers.status <> 'CANCELLED' and inv_mas_scratchcard.registered = 'no' 
group by inv_invoicenumbers.branchid) as bill61 on 
bill61.branchid = inv_mas_branch.slno order by inv_mas_branch.branchname;";
	$result25 = runmysqlquery($query25);
	
	// Create Table to display brach wise Summary
	$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
	$branchgrid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$branchgrid .= '<tr><td width="7%"  align="center" nowrap="nowrap"><strong>Sl No</strong></td><td nowrap="nowrap"  width="14%" align="center"><strong>Branch Name</strong></td><td nowrap="nowrap" colspan="5" align="center"><strong>Billed  but Not Registered for (Days)</strong></td></tr><tr><td align="center" >&nbsp;</td><td align="center">&nbsp;</td><td align="center" width="15%">1-7</td><td align="center" width="16%">8-15</td><td align="center" width="17%">15-30</td><td align="center" width="16%">31-60</td><td align="center" width="15%">&gt; 61</td></tr>';
		
		$totalbillcount17 = 0;
		$totalbillcount815 = 0 ;
		$totalbillcount1630 = 0 ;
		$totalbillcount3160 = 0 ;
		$totalbillcount61 = 0 ;
		
		$billcount17 = 0;
		$billcount815 = 0 ;
		$billcount1630 = 0 ;
		$billcount3160 = 0;
		$billcount61 = 0;
		$slno = 0;
		while($fetch25 = mysql_fetch_array($result25))
		{
			$slno++;
			$billcount17 = ($fetch25['billcount17'] == '')? '0' : $fetch25['billcount17'];
			$billcount815 = ($fetch25['billcount815'] == '')? '0' : $fetch25['billcount815'];
			$billcount1630 = ($fetch25['billcount1630'] == '')? '0' : $fetch25['billcount1630'];
			$billcount3160 = ($fetch25['billcount3160'] == '')? '0' : $fetch25['billcount3160'];
			$billcount61 = ($fetch25['billcount61'] == '')? '0' : $fetch25['billcount61'];
			
			$totalbillcount17 = $totalbillcount17 + $billcount17;
			$totalbillcount815 = $totalbillcount815 + $billcount815;
			$totalbillcount1630 = $totalbillcount1630 + $billcount1630;
			$totalbillcount3160 = $totalbillcount3160 + $billcount3160;
			$totalbillcount61 = $totalbillcount61 + $billcount61;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch25['branchname'].'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount17.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount815.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount1630.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount3160.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.$billcount61.'</td>';
			$branchgrid .= '</tr>';
			
		}
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount17.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount815.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount1630.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount3160.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.$totalbillcount61.'</td>';
		$branchgrid .= '</tr>';
		
		
		$branchgrid .= '</table>';


	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$filedetails = generatecustomerexcel('','management');
	$filedetailssplit = explode('$^$',$filedetails);
	$filepath = $filedetailssplit[0];
	$filebasename = $filedetailssplit[1];
		
	$msg = file_get_contents("../mailcontents/dayendnotregisteredsummary.htm");
	$textmsg = file_get_contents("../mailcontents/dayendnotregisteredsummary.txt");
	
	$subject = "Billed but Not Registered Summary for ".$date." [Management] ";
	//Create an array of replace parameters
	$array = array();
	//$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".' Management';
	$array[] = "##EMAILID##%^%".'nitinall@relyonsoft.com, hsn@relyonsoft.com';
	$array[] = "##DEALERDETAILS##%^%".$branchgrid;
	$array[] = "##BRANCHWISEDETAILS##%^%".$grid;
	$array[] = "##SUBJECT##%^%".$subject;
	//$emailarray = explode(',',$emailid);
	//$emailcount = count($emailid);
	$emailid = array('nitinall@relyonsoft.com','hsn@relyonsoft.com');
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
				array('../images/relyon-logo.jpg','inline','1234567890'),
				array($filepath.$filebasename,'attachment','1234567891')
			);
		
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
	fileDelete($filepath,$filebasename);

?>