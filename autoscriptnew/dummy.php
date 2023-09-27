<?php

ini_set('memory_limit', '-1');
set_time_limit(0);
include("../functions/phpfunctions.php");

$query = "Drop table if exists invoicedetails;";
$result = runmysqlquery($query);

// Create Temporary Table to insert Invoice details

	$starttime = date('H:m:s');
	$query = "CREATE  TABLE `invoicedetails` (                                       
                  `slno` int(10) NOT NULL auto_increment,                             
                  `invoiceno` int(10) default NULL,                                   
                  `productcode` varchar(10) collate latin1_general_ci default NULL,   
                  `usagetype` varchar(10) collate latin1_general_ci default NULL,     
                  `amount` varchar(25) collate latin1_general_ci default NULL,        
                  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL,   
                  PRIMARY KEY  (`slno`)                                               
                );";
	$result = runmysqlquery($query);

	
	// Fetch the invoices generated in this month.
	
	$query2 = "select * from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) between '2011-02-01' and curdate() and products <> '' and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	$count = 0;
	$totalamount = 0;
	while($fetch2 = mysqli_fetch_array($result2))
	{
		$count++;
		$totalamount = 0;
		$products = explode('#',$fetch2['products']);
		for($i = 0 ; $i < count($products);$i++)
		{
			$totalamount = 0;
			$description = explode('*',$fetch2['description']);
			$splitdescription = explode('$',$description[$i]);
			
			$productcode = $products[$i];
			$usagetype = $splitdescription[3];
			$amount = $splitdescription[6];
			$purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
			
			$offerdescamount = '0';
			if($i == 0)
			{
				if($fetch2['offerdescription'] <> '')
				{
					$offerdescriptionsplit = explode('*',$fetch2['offerdescription']);
					for($j=0;$j<count($offerdescriptionsplit);$j++)
					{
						$finalsplit = explode('$',$offerdescriptionsplit[$j]); //echo($offerdescriptionsplit[$j]);exit;
						if($finalsplit[0] == 'add')
							$offerdescamount = $offerdescamount + $finalsplit[2];
						else if($finalsplit[0] == 'less')
							$offerdescamount = $offerdescamount - $finalsplit[2];
					}
				}
				// Add service amount if any
				$serviceamount = 0;
				if($fetch2['servicedescription'] <> '')
				{
					$serviceamountsplit = explode('*',$fetch2['servicedescription']);
					for($k = 0 ;$k < count($serviceamountsplit);$k++)
					{
						$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
						$serviceamount = $serviceamount + $finalsplit[2];
					}
				}
				$totalamount = $amount +  $offerdescamount + $serviceamount;
			}
			else 
			{
				$totalamount = $amount;
			}
					
			// Fetch Product 	
			
			$query0 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
					
			$result0 = runmysqlqueryfetch($query0);
			
			// Insert into invoice details table
			
			$query3 = "insert into invoicedetails(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result0['productgroup']."')";
			
			$result3 =  runmysqlquery($query3);
		}
	}
	exit;
	
	$rowcount = "select count(*) as total from invoicedetails";
	$fetchcnt = runmysqlqueryfetch($rowcount);
	//echo($fetchcnt['total']);
	/* ------------------ Day end summary email to all dealers ----------------- */

	// Fetch all dealer details 
	
	$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid from inv_mas_dealer  order by slno ";
	$result = runmysqlquery($query);
	$cnt = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		
		$cnt++;
		$dealerid = $fetch['slno'];
		$dealername = $fetch['businessname'];
		$emailid = $fetch['emailid'];
				
		
		$query4 = "select invoicedetails.dealerid,ifnull(sum(invoicedetails.amount),'0') as netamount from invoicedetails 
where invoicedetails.dealerid = '".$dealerid."' and left(invoicedetails.invoicedate,10) = curdate()  group by invoicedetails.dealerid";

		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		
		$query5 = "select invoicedetails.dealerid,ifnull(sum(invoicedetails.amount),'0') as netamount from invoicedetails 
where invoicedetails.dealerid = '".$dealerid."' and left(invoicedetails.invoicedate,10) between '2011-02-01' and curdate()  group by invoicedetails.dealerid";
		$result5 = runmysqlquery($query5); //echo($query5);exit;
		
		// put the details to table to display in email content.
		$grid = '<table width="85%" style="font-family:calibri;" cellspacing="0" cellpadding="0" border = "1" align = "center">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "60%" align="center" >Name</td><td nowrap="nowrap"  align="center" width = "20%">Today Sales </td><td nowrap="nowrap"  align="center" width = "20%">Month till Date</td></tr>';
		
		
		$slno = 0;
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		
		if(mysqli_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$todaynew = ($fetch4['netamount'] == '')?'0' : $fetch4['netamount'];
		}
		else
		{
			$todaynew = 0;
		}
		if(mysqli_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$monthtilldate = ($fetch5['netamount'] == '')?'0' : $fetch5['netamount'];
		}
		else
			$monthtilldate = 0;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$todaynew.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$monthtilldate.'</td>';
		$grid .= '</tr>';
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$todaynew.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.$monthtilldate.'</td>';
		$grid .= '</tr>';
		
		$grid .= '</table>';
			
		//echo($grid);
		
		if($monthtilldate > 0)
		{
			$productwisegrid  = '<table width="85%" border="1" cellspacing="0" cellpadding="0" align = "center">';
			$productwisegrid .= '<tr style=" background-color:#b2cffe">';
			$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Todays</div></td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month till Date</div></td>';
			$productwisegrid .= '</tr>';
			$productwisegrid .= '<tr style=" background-color:#b2cffe">';
			$productwisegrid .= '<td width="22%" nowrap="nowrap" ><div align="center" >New</div></td>';
			$productwisegrid .= '<td width="21%" nowrap="nowrap" ><div align="center">Updation</div></td>';
			$productwisegrid .= '<td width="23%" nowrap="nowrap" ><div align="center">New </div></td>';
			$productwisegrid .= '<td width="24%" nowrap="nowrap" ><div align="center">Updation</div></td>';
			$productwisegrid .= '</tr>';
			
			// Invoices raised today
			
			// New Purchases of dealer based on product group and purchase type
			
			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			
			$result200 = runmysqlqueryfetch($query200);
		    $tdsnew = $result200['amount'];
			 
			 
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result201 = runmysqlqueryfetch($query201);
		    $sppnew = $result201['amount'];
			
			
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result202 = runmysqlqueryfetch($query202);
		    $stonew = $result202['amount'];
			
			
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result203 = runmysqlqueryfetch($query203);
		    $svhnew = $result203['amount'];
			
			
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result204 = runmysqlqueryfetch($query204);
		    $svinew = $result204['amount'];
			
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result205 = runmysqlqueryfetch($query205);
		    $sacnew = $result205['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result206 = runmysqlqueryfetch($query206);
		    $tdsupdation = $result206['amount'];
			
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result207 = runmysqlqueryfetch($query207);
		    $sppupdation = $result207['amount'];
			
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result208 = runmysqlqueryfetch($query208);
		    $stoupdation = $result208['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result209 = runmysqlqueryfetch($query209);
		    $svhupdation = $result209['amount'];
			
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result210 = runmysqlqueryfetch($query210);
		    $sviupdation = $result210['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  left(invoicedetails.invoicedate,10) = curdate()" ;
			$result211 = runmysqlqueryfetch($query211);
		    $sacupdation = $result211['amount'];
			
			
			
			// Details of month till date 
			
			// New Purchases of dealer based on product group and purchase type
			
			$query100 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS'" ;
			
			$result100 = runmysqlqueryfetch($query100);
		    $thismonthtdsnew = $result100['amount'];
			 
			 
			$query101 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP'" ;
			$result101 = runmysqlqueryfetch($query101);
		    $thismonthsppnew = $result101['amount'];
			
			
			$query102= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO'" ;
			$result102 = runmysqlqueryfetch($query102);
		    $thismonthstonew = $result102['amount'];
			
			
			$query103 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH'" ;
			$result103 = runmysqlqueryfetch($query103);
		    $thismonthsvhnew = $result103['amount'];
			
			
			$query104 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI'" ;
			$result104 = runmysqlqueryfetch($query104);
		    $thismonthsvinew = $result104['amount'];
			
			$query105 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC'" ;
			$result105 = runmysqlqueryfetch($query105);
		    $thismonthsacnew = $result105['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query106 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS'" ;
			$result106 = runmysqlqueryfetch($query106);
		    $thismonthtdsupdation = $result106['amount'];
			
			
			$query107 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP'" ;
			$result107 = runmysqlqueryfetch($query107);
		    $thismonthsppupdation = $result107['amount'];
			
			
			$query108= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO'" ;
			$result108 = runmysqlqueryfetch($query108);
		    $thismonthstoupdation = $result108['amount'];
			
			$query109 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH'" ;
			$result109 = runmysqlqueryfetch($query109);
		    $thismonthsvhupdation = $result109['amount'];
			
			
			$query110 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI'" ;
			$result110 = runmysqlqueryfetch($query110);
		    $thismonthsviupdation = $result110['amount'];
			
			$query111 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC'" ;
			$result111 = runmysqlqueryfetch($query111);
		    $thismonthsacupdation = $result111['amount'];
			
			
			
			$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew ;
			$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation;
			$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew;
			$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation ;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$tdsnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$tdsupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthtdsnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthtdsupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$sppnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$sppupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsppnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsppupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$stonew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$stoupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthstonew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthstoupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$svhnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$svhupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsvhnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsvhupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$svinew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$sviupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsvinew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsviupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$sacnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$sacupdation.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsacnew.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthsacupdation.'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$todaynewtotal.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$todayupdationtotal.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthnewtotal.'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.$thismonthupdationtotal.'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '</table>';
			
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			
			$msg = file_get_contents("../mailcontents/dayendsummary.htm");
			$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
			
			
			//Create an array of replace parameters
			$array = array();
			$date = datetimelocal('d-m-Y');
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$dealername;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##SALESDETAILS##%^%".$grid;
			$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
			
			$emailarray = explode(',',$emailid);
			$emailcount = count($emailarray);
		
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray[$i]))
				{
						$emailids[$emailarray[$i]] = $emailarray[$i];
				}
			}
			$toarray = $emailids;
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
			$subject = "Day End Invoice Summary ".$date;
			$html = $msg;
			$text = $textmsg;
			$filearray = array(
						array('../images/relyon-logo.jpg','inline','1234567890')
					);
	//		rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
			
			echo($grid);
			echo($productwisegrid);	
		}		
	}
	
	
	
	
?>