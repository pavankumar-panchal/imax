<?php

	ini_set('memory_limit', '-1');
	set_time_limit(0);
	include("../functions/phpfunctions.php");
	
	// Get Yesterday's Date 	
	$querygetdate = "select curdate() - INTERVAL 1 DAY as currentdate;";
	$resultgetdate = runmysqlqueryfetch($querygetdate);
	$date = changedateformat($resultgetdate['currentdate']);
	
	$todaysdatepiece = "left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)";
	$todaysdatepiece1 = "left(inv_invoicenumbers.createddate,10) = (curdate()- INTERVAL 1 DAY)";
	$monthsdatepiece = "left(inv_invoicenumbers.createddate,10) between DATE_FORMAT(NOW() ,'%Y-%m-01') and (curdate()- INTERVAL 1 DAY )";
	
	
	// Define managed area array
	
	$managedareaarray = array(
						"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("webmaster@relyonsoft.com","rashmi.hk@relyonsoft.com"), "name" => array("Paramesh N","Nitin S Patel")),
						"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("webmaster@relyonsoft.com","rashmi.hk@relyonsoft.com"), "name" => array("Raghavendra N","Nitin S Patel")),
						"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("webmaster@relyonsoft.com","rashmi.hk@relyonsoft.com","meghana.b@relyonsoft.com"),
										 "name" => array("Vijay Hebbar","Shashidar MS","Vidyanand")),
						
					);


					
/*	$managedareaarray = array(
					"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("paramesh.n@relyonsoft.com","nitin@relyonsoft.com"),
									 "name" => array("Paramesh N","Nitin S Patel")),
					"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("raghavendra.n@relyonsoft.com","nitin@relyonsoft.com"),
									 "name" => array("Raghavendra N","Nitin S Patel")),
					"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("vijay@relyonsoft.com","shashidharms@relyonsoft.com","vidyananda.csd@relyonsoft.com"),
									 "name" => array("Vijay Hebbar","Shashidar MS","Vidyanand"))			 
					
				);*/
				
	// Define Bcc Array

	if($_SERVER['HTTP_HOST'] == 'archanaab')  
	{
		$bccarray = array('Vijay Kumar' =>'archana.ab@relyonsoft.com');
	}
	else
	{
		$bccarray = array('Webmaster' =>'webmaster@relyonsoft.com');
	}


	$query = "Drop table if exists invoicedetails;";
	$result = runmysqlquery($query);

	// Create Temporary Table to insert Invoice details

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
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				   `branch` varchar(25) collate latin1_general_ci default NULL,  
				    `branchname` varchar(25) collate latin1_general_ci default NULL,   
                  PRIMARY KEY  (`slno`)                                               
                );";
	$result = runmysqlquery($query);

	// Fetch the invoices generated in this month.
	
	$query2 = "select * from inv_invoicenumbers where ".$monthsdatepiece." and products <> '' and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	$count = 0;
	$totalamount = 0;
	while($fetch2 = mysql_fetch_array($result2))
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
			$purchasetype = $splitdescription[2];   
			
			$offerdescamount = '0';
			if($i == 0)
			{
				if($fetch2['offerdescription'] <> '')
				{
					$offerdescriptionsplit = explode('*',$fetch2['offerdescription']);
					for($j=0;$j<count($offerdescriptionsplit);$j++)
					{
						$finalsplit = explode('$',$offerdescriptionsplit[$j]);
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
						$finalsplit = explode('$',$serviceamountsplit[$k]); 
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
			
			$query3 = "insert into invoicedetails(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result0['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."')";
			
			$result3 =  runmysqlquery($query3);
		}
	}
	exit;

	/* ------------------ Day end summary email to all dealers ----------------- */

	// Fetch all dealer details 
	
	$query = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid from inv_mas_dealer  order by slno ";
	$result = runmysqlquery($query);
	$cnt = 0;
	while($fetch = mysql_fetch_array($result))
	{
		
		$cnt++;
		$dealerid = $fetch['slno'];
		$dealername = $fetch['businessname'];
		if($_SERVER['HTTP_HOST'] == 'archanaab' )  
		{
			$emailid = 'webmaster@relyonsoft.com';
			
		}
		else
		{
			$emailid = $fetch['emailid'];
			//$emailid = 'webmaster@relyonsoft.com';
			
		}	
		$emailid = $fetch['emailid'];	
	
		// Fetch today's details
		$query4 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";

		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		
		$query5 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result5 = runmysqlquery($query5); 
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "50%" align="center" >Name</td><td nowrap="nowrap"  align="center" width = "25%">Day Sales </td><td nowrap="nowrap"  align="center" width = "25%">Month till Date</td></tr>';
		
		
		$slno = 0;
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		
		if(mysql_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$todaynew = ($fetch4['netamount'] == '')?'0' : $fetch4['netamount'];
		}
		else
		{
			$todaynew = 0;
		}
		if(mysql_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$monthtilldate = ($fetch5['netamount'] == '')?'0' : $fetch5['netamount'];
		}
		else
			$monthtilldate = 0;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '</tr>';
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '</tr>';
		
		$grid .= '</table>';
			
		
		if($monthtilldate > 0)
		{	
			echo('Dealer - '.$fetch['emailid'].'<br/>');
			$productwisegrid  = '<table width="85%" border = "2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
			$productwisegrid .= '<tr style=" background-color:#b2cffe">';
			$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
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
			
			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS' and  ".$todaysdatepiece." " ;
			
			$result200 = runmysqlqueryfetch($query200);
		    $tdsnew = $result200['amount'];
			 
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP' and  ".$todaysdatepiece." " ;
			$result201 = runmysqlqueryfetch($query201);
		    $sppnew = $result201['amount'];
			
			
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO' and  ".$todaysdatepiece." " ;
			$result202 = runmysqlqueryfetch($query202);
		    $stonew = $result202['amount'];
			
			
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result203 = runmysqlqueryfetch($query203);
		    $svhnew = $result203['amount'];
			
			
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result204 = runmysqlqueryfetch($query204);
		    $svinew = $result204['amount'];
			
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
			$result205 = runmysqlqueryfetch($query205);
		    $sacnew = $result205['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
			$result206 = runmysqlqueryfetch($query206);
		    $tdsupdation = $result206['amount'];
			
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
			$result207 = runmysqlqueryfetch($query207);
		    $sppupdation = $result207['amount'];
			
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
			$result208 = runmysqlqueryfetch($query208);
		    $stoupdation = $result208['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result209 = runmysqlqueryfetch($query209);
		    $svhupdation = $result209['amount'];
			
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result210 = runmysqlqueryfetch($query210);
		    $sviupdation = $result210['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
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
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '</table>';
			
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			
			$msg = file_get_contents("../mailcontents/dayendsummary.htm");
			$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
			
			$subject = "Invoicing Summary for ".$date." [".$dealername."]";
			//Create an array of replace parameters
			$array = array();
			$array[] = "##DATE##%^%".$date;
			$array[] = "##NAME##%^%".$dealername;
			$array[] = "##EMAILID##%^%".$emailid;
			$array[] = "##SALESDETAILS##%^%".$grid;
			$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
			$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
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
			print_r($toarraydealer);
			//rslmail($fromname, $fromemail, $toarraydealer, $subject, $text, $html,null,$bccarray,$filearray); 
		}		
	}
		
	/*----------------------------Day End Summary Email to Branch heads-------------------------*/


	// Fetch branch head details to send email.


	$query4 = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid,branchname,inv_mas_branch.slno as branch from inv_mas_dealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch where branchhead = 'yes' ";
	$result4 = runmysqlquery($query4);
	
	while($fetch4 = mysql_fetch_array($result4))
	{
		// Select the dealers under Branch head based on Branch 
		$tdsnew = 0;
		$tdsupdation = 0;
		$sppnew = 0;
		$sppupdation = 0;
		$stonew = 0;
		$stoupdation = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svinew = 0;
		$sviupdation = 0;
		$sacnew = 0;
		$sacupdation = 0;
		$thismonthtdsnew = 0;
		$thismonthtdsupdation = 0;
		$thismonthsppnew = 0;
		$thismonthsppupdation = 0;
		$thismonthstonew = 0;
		$thismonthstoupdation = 0;
		$thismonthsvhnew = 0;
		$thismonthsvhupdation = 0;
		$thismonthsvinew = 0;
		$thismonthsviupdation = 0;
		$thismonthsacnew = 0;
		$thismonthsacupdation = 0;
		if($_SERVER['HTTP_HOST'] == 'archanaab')  
		{
			$emailid = 'webmaster@relyonsoft.com';
		}
		else
		{
			//$emailid = 'webmaster@relyonsoft.com';
			$emailid = $fetch4['emailid'];
		}
		//$emailid = $fetch4['emailid'];
		echo('Branch head : '.$fetch4['emailid'].'<br/>');
		$name = $fetch4['businessname']; 
		$query5 = "select * from inv_mas_dealer where branch = '".$fetch4['branch']."' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where branchid = '".$fetch4['branch']."'))";
		$result5 = runmysqlquery($query5);
		$slno = 0;
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Sales</td><td nowrap="nowrap"  width = "20%" align="center">Month till Date</td></tr>';
		
		$productwisegrid  = '<table width="85%" border="2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month till Date</div></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="22%" nowrap="nowrap" ><div align="center" >New</div></td>';
		$productwisegrid .= '<td width="21%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="23%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="24%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '</tr>';
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		while($fetch5 = mysql_fetch_array($result5))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch5['slno'];
			$dealername = $fetch5['businessname']; 
			
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."'  and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			if(mysql_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysql_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['netamount'] == '')?'0' : $fetch7['netamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
		
			// Get Totals 
			
			$todaynewtotal = $todaynewtotal + $todaynew;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
			$grid .= '</tr>';
			
			// Invoices raised today
			
			// New Purchases of dealer based on product group and purchase type
			
			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
			
			$result200 = runmysqlqueryfetch($query200);
			$tdsnew =  $tdsnew + $result200['amount'];
			 
			 
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
			$result201 = runmysqlqueryfetch($query201);
			$sppnew =  $sppnew + $result201['amount'];
			
			
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
			$result202 = runmysqlqueryfetch($query202);
			$stonew = $stonew + $result202['amount'];
			
			
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result203 = runmysqlqueryfetch($query203);
			$svhnew = $svhnew + $result203['amount'];
			
			
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result204 = runmysqlqueryfetch($query204);
			$svinew = $svinew + $result204['amount'];
			
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
			$result205 = runmysqlqueryfetch($query205);
			$sacnew =  $sacnew + $result205['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
			$result206 = runmysqlqueryfetch($query206);
			$tdsupdation = $tdsupdation + $result206['amount'];
			
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
			$result207 = runmysqlqueryfetch($query207);
			$sppupdation = $sppupdation + $result207['amount'];
			
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
			$result208 = runmysqlqueryfetch($query208);
			$stoupdation = $stoupdation + $result208['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result209 = runmysqlqueryfetch($query209);
			$svhupdation =  $svhupdation + $result209['amount'];
			
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result210 = runmysqlqueryfetch($query210);
			$sviupdation = $sviupdation + $result210['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
			$result211 = runmysqlqueryfetch($query211);
			$sacupdation = $sacupdation + $result211['amount'];
			
			
			
			// Details of month till date 
			
			// New Purchases of dealer based on product group and purchase type
			
			$query100 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS'" ;
			
			$result100 = runmysqlqueryfetch($query100);
			$thismonthtdsnew = $thismonthtdsnew + $result100['amount'];
			 
			 
			$query101 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP'" ;
			$result101 = runmysqlqueryfetch($query101);
			$thismonthsppnew = $thismonthsppnew + $result101['amount'];
			
			
			$query102= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO'" ;
			$result102 = runmysqlqueryfetch($query102);
			$thismonthstonew = $thismonthstonew + $result102['amount'];
			
			
			$query103 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH'" ;
			$result103 = runmysqlqueryfetch($query103);
			$thismonthsvhnew = $thismonthsvhnew + $result103['amount'];
			
			
			$query104 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI'" ;
			$result104 = runmysqlqueryfetch($query104);
			$thismonthsvinew = $thismonthsvinew + $result104['amount'];
			
			$query105 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC'" ;
			$result105 = runmysqlqueryfetch($query105);
			$thismonthsacnew =  $thismonthsacnew + $result105['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query106 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS'" ;
			$result106 = runmysqlqueryfetch($query106);
			$thismonthtdsupdation = $thismonthtdsupdation + $result106['amount'];
			
			
			$query107 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP'" ;
			$result107 = runmysqlqueryfetch($query107);
			$thismonthsppupdation =  $thismonthsppupdation + $result107['amount'];
			
			
			$query108= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO'" ;
			$result108 = runmysqlqueryfetch($query108);
			$thismonthstoupdation = $thismonthstoupdation + $result108['amount'];
			
			$query109 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH'" ;
			$result109 = runmysqlqueryfetch($query109);
			$thismonthsvhupdation = $thismonthsvhupdation + $result109['amount'];
			
			
			$query110 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI'" ;
			$result110 = runmysqlqueryfetch($query110);
			$thismonthsviupdation =  $thismonthsviupdation + $result110['amount'];
			
			$query111 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC'" ;
			$result111 = runmysqlqueryfetch($query111);
			$thismonthsacupdation = $thismonthsacupdation + $result111['amount'];
			
			
		}
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';
		
		// Calculate totals
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew ;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation;
		$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew;
		$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation ;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '</table>';
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		
		$msg = file_get_contents("../mailcontents/dayendsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
		
		$subject = "Invoicing Summary for ".$date." [".$fetch4['branchname']."]";
		//Create an array of replace parameters
		$array = array();
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$name;
		$array[] = "##EMAILID##%^%".$emailid;
		$array[] = "##SALESDETAILS##%^%".$grid;
		$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
		$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
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
		print_r($toarraybranchhead);
		//rslmail($fromname, $fromemail, $toarraybranchhead, $subject, $text, $html,null,$bccarray,$filearray); 
	}

	/*--------------------------------------Day End Summary Email to Region heads-----------------------*/
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$tdsnew = 0;
		$tdsupdation = 0;
		$sppnew = 0;
		$sppupdation = 0;
		$stonew = 0;
		$stoupdation = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svinew = 0;
		$sviupdation = 0;
		$sacnew = 0;
		$sacupdation = 0;
		$thismonthtdsnew = 0;
		$thismonthtdsupdation = 0;
		$thismonthsppnew = 0;
		$thismonthsppupdation = 0;
		$thismonthstonew = 0;
		$thismonthstoupdation = 0;
		$thismonthsvhnew = 0;
		$thismonthsvhupdation = 0;
		$thismonthsvinew = 0;
		$thismonthsviupdation = 0;
		$thismonthsacnew = 0;
		$thismonthsacupdation = 0;
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
		$emailid = $arrayvalue['emailid'];
		$name = $arrayvalue['name'];
		
		$query4 = "select * from inv_mas_dealer where region = '".$regionid."' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where regionid = '".$regionid."'));";
		
		$result4 = runmysqlquery($query4);
		$slno = 0;
		
		// put the details to table to display in email content.
		$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Dealerwise Summary</strong></div>';
		$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Sales</td><td nowrap="nowrap"  width = "20%" align="center">Month till Date</td></tr>';
		
		$productwisegrid  = '<table width="85%" border="2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month till Date</div></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="22%" nowrap="nowrap" ><div align="center" >New</div></td>';
		$productwisegrid .= '<td width="21%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="23%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="24%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '</tr>';
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		
		while($fetch4 = mysql_fetch_array($result4))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch4['slno'];
			$dealername = $fetch4['businessname']; 
			
			// Fetch today's details 
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			
			if(mysql_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysql_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['netamount'] == '')?'0' : $fetch7['netamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
		
			// Get Totals 
			
			$todaynewtotal = $todaynewtotal + $todaynew;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
			$grid .= '</tr>';
			
			// New Purchases of dealer based on product group and purchase type
		
			$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
			$result200 = runmysqlqueryfetch($query200);
			$tdsnew =  $tdsnew + $result200['amount'];
			 
			 
			$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
			$result201 = runmysqlqueryfetch($query201);
			$sppnew =  $sppnew + $result201['amount'];
			
			$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
			$result202 = runmysqlqueryfetch($query202);
			$stonew = $stonew + $result202['amount'];
			
			$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result203 = runmysqlqueryfetch($query203);
			$svhnew = $svhnew + $result203['amount'];
			
			$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result204 = runmysqlqueryfetch($query204);
			$svinew = $svinew + $result204['amount'];
			
			$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
			$result205 = runmysqlqueryfetch($query205);
			$sacnew =  $sacnew + $result205['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
			$result206 = runmysqlqueryfetch($query206);
			$tdsupdation = $tdsupdation + $result206['amount'];
			
			
			$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
			$result207 = runmysqlqueryfetch($query207);
			$sppupdation = $sppupdation + $result207['amount'];
			
			
			$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
			$result208 = runmysqlqueryfetch($query208);
			$stoupdation = $stoupdation + $result208['amount'];
			
			$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
			$result209 = runmysqlqueryfetch($query209);
			$svhupdation =  $svhupdation + $result209['amount'];
			
			
			$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
			$result210 = runmysqlqueryfetch($query210);
			$sviupdation = $sviupdation + $result210['amount'];
			
			$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
			$result211 = runmysqlqueryfetch($query211);
			$sacupdation = $sacupdation + $result211['amount'];
			
			// Details of month till date 
			
			// New Purchases of dealer based on product group and purchase type
			
			$query100 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'TDS'" ;
			
			$result100 = runmysqlqueryfetch($query100);
			$thismonthtdsnew = $thismonthtdsnew + $result100['amount'];
			 
			 
			$query101 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SPP'" ;
			$result101 = runmysqlqueryfetch($query101);
			$thismonthsppnew = $thismonthsppnew + $result101['amount'];
			
			
			$query102= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'STO'" ;
			$result102 = runmysqlqueryfetch($query102);
			$thismonthstonew = $thismonthstonew + $result102['amount'];
			
			
			$query103 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVH'" ;
			$result103 = runmysqlqueryfetch($query103);
			$thismonthsvhnew = $thismonthsvhnew + $result103['amount'];
			
			
			$query104 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SVI'" ;
			$result104 = runmysqlqueryfetch($query104);
			$thismonthsvinew = $thismonthsvinew + $result104['amount'];
			
			$query105 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'New' and productgroup = 'SAC'" ;
			$result105 = runmysqlqueryfetch($query105);
			$thismonthsacnew =  $thismonthsacnew + $result105['amount'];
			
			// Updations of dealer based on product group and purchase type
			
			$query106 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'TDS'" ;
			$result106 = runmysqlqueryfetch($query106);
			$thismonthtdsupdation = $thismonthtdsupdation + $result106['amount'];
			
			
			$query107 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SPP'" ;
			$result107 = runmysqlqueryfetch($query107);
			$thismonthsppupdation =  $thismonthsppupdation + $result107['amount'];
			
			
			$query108= "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'STO'" ;
			$result108 = runmysqlqueryfetch($query108);
			$thismonthstoupdation = $thismonthstoupdation + $result108['amount'];
			
			$query109 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVH'" ;
			$result109 = runmysqlqueryfetch($query109);
			$thismonthsvhupdation = $thismonthsvhupdation + $result109['amount'];
			
			
			$query110 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SVI'" ;
			$result110 = runmysqlqueryfetch($query110);
			$thismonthsviupdation =  $thismonthsviupdation + $result110['amount'];
			
			$query111 = "select ifnull(sum(amount),'0') as amount from invoicedetails where dealerid = '".$dealerid."' and purchasetype = 'Updation' and productgroup = 'SAC'" ;
			$result111 = runmysqlqueryfetch($query111);
			$thismonthsacupdation = $thismonthsacupdation + $result111['amount'];
			
			
		}
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';
		
		// Calculate totals
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew ;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation;
		$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew;
		$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation ;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		// Get Branch wise summary for the area heads 
		
		$query = "select inv_mas_branch.branchname as branchname,today.netamount as todaysales,thismonth.netamount as thismonthsales from inv_mas_branch 
left join (select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$todaysdatepiece1." and `status` <> 'CANCELLED' and regionid = '".$regionid."' group by  branchid) as today on today.branchid = inv_mas_branch.slno 

left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$monthsdatepiece." and `status` <> 'CANCELLED' and regionid = '".$regionid."' group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno where region = '".$regionid."' order by inv_mas_branch.branchname;";
		$result = runmysqlquery($query);

		// Create Table to display brach wise Summary
		$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branchwise Summary</strong></div>';
		$branchgrid  .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month till Date</td></tr>';
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0 ;
		$slno = 0;
		while($fetch = mysql_fetch_array($result))
		{
			$slno++;
			$tadaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
			$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
			
			$todaynewtotal = $todaynewtotal + $tadaysale;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tadaysale).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsale).'</td>';
			$branchgrid .= '</tr>';
			
		}
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
		$branchgrid .= '</tr>';
		
		$branchgrid .= '</table>';
		
		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/dayendsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
		
		$subject = "Invoicing Summary for ".$date." [".$managedarea."]";
		//Create an array of replace parameters
		$array = array();
		$array[] = "##DATE##%^%".$date;
		$array[] = "##NAME##%^%".$name[0];
		$array[] = "##EMAILID##%^%".$emailid[0];
		$array[] = "##SALESDETAILS##%^%".$grid;
		$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
		$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
		$array[] = "##SUBJECT##%^%".$subject;
		
		unset($toarray);
		for($emailidcount = 0; $emailidcount < count($emailid); $emailidcount++)
		{
			$toarray[$name[$emailidcount]] = $emailid[$emailidcount];
		}		
		$msg = replacemailvariable($msg,$array);
		$textmsg = replacemailvariable($textmsg,$array);
		$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
		print_r($toarray);
		$html = $msg;
		$text = $textmsg;
		$filearray = array(
					array('../images/relyon-logo.jpg','inline','1234567890')
				);
		//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 

	}	
	
	/*--------------------------------------Day End Sumary Email to Accounts----------------------------------   */
	
	$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Regionwise Summary</strong></div>';
	$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Region</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month till Date</td></tr>';
	
	$productwisegrid  = '<table width="85%" cellspacing="0" border = "2" cellpadding="0" align = "center" style="font-family:calibri">';
	$productwisegrid .= '<tr style=" background-color:#b2cffe">';
	$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
	$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
	$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month till Date</div></td>';
	$productwisegrid .= '</tr>';
	$productwisegrid .= '<tr style=" background-color:#b2cffe">';
	$productwisegrid .= '<td width="22%" nowrap="nowrap" ><div align="center" >New</div></td>';
	$productwisegrid .= '<td width="21%" nowrap="nowrap" ><div align="center">Updation</div></td>';
	$productwisegrid .= '<td width="23%" nowrap="nowrap" ><div align="center">New </div></td>';
	$productwisegrid .= '<td width="24%" nowrap="nowrap" ><div align="center">Updation</div></td>';
	$productwisegrid .= '</tr>';
	
	
	$slno = 0;
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0;
	// Region wise and producyt wise Summary 
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		
		$slno++;
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
	
			// Fetch details of current date
		$query4 = "select ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.regionid = '".$regionid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' ";
		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		$query5 = "select ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.regionid = '".$regionid."' and ".$monthsdatepiece." and `status` <> 'CANCELLED'"; 
		
		
		if(mysql_num_rows($result4) > 0)
		{
			$fetch4 = runmysqlqueryfetch($query4);
			$todaynew = ($fetch4['netamount'] == '')?'0' : $fetch4['netamount'];
		}
		else
		{
			$todaynew = 0;
		}
		if(mysql_num_rows($result5) > 0)
		{
			$fetch5 = runmysqlqueryfetch($query5);
			$monthtilldate = ($fetch5['netamount'] == '')?'0' : $fetch5['netamount'];
		}
		else
		{
			$monthtilldate = 0;
		}
		
		// Get Totals 
		
		$todaynewtotal = $todaynewtotal + $todaynew;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$managedarea.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '</tr>';
		
		// New Purchases of dealer based on product group and purchase type
		
		$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
		$result200 = runmysqlqueryfetch($query200);
		$tdsnew =  $tdsnew + $result200['amount'];
		 
		$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
		$result201 = runmysqlqueryfetch($query201);
		$sppnew =  $sppnew + $result201['amount'];
		
		$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
		$result202 = runmysqlqueryfetch($query202);
		$stonew = $stonew + $result202['amount'];
		
		$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
		$result203 = runmysqlqueryfetch($query203);
		$svhnew = $svhnew + $result203['amount'];
		
		$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
		$result204 = runmysqlqueryfetch($query204);
		$svinew = $svinew + $result204['amount'];
		
		$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
		$result205 = runmysqlqueryfetch($query205);
		$sacnew =  $sacnew + $result205['amount'];
		
		// Updations of dealer based on product group and purchase type
		
		$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  ".$todaysdatepiece."" ;
		$result206 = runmysqlqueryfetch($query206);
		$tdsupdation = $tdsupdation + $result206['amount'];
		
		$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  ".$todaysdatepiece."" ;
		$result207 = runmysqlqueryfetch($query207);
		$sppupdation = $sppupdation + $result207['amount'];
		
		$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'STO' and  ".$todaysdatepiece."" ;
		$result208 = runmysqlqueryfetch($query208);
		$stoupdation = $stoupdation + $result208['amount'];
		
		$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  ".$todaysdatepiece."" ;
		$result209 = runmysqlqueryfetch($query209);
		$svhupdation =  $svhupdation + $result209['amount'];
		
		$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  ".$todaysdatepiece."" ;
		$result210 = runmysqlqueryfetch($query210);
		$sviupdation = $sviupdation + $result210['amount'];
		
		$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  ".$todaysdatepiece."" ;
		$result211 = runmysqlqueryfetch($query211);
		$sacupdation = $sacupdation + $result211['amount'];
		
		
		// Details of month till date 
		
		// New Purchases of dealer based on product group and purchase type
		
		$query100 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'TDS'" ;
		$result100 = runmysqlqueryfetch($query100);
		$thismonthtdsnew = $thismonthtdsnew + $result100['amount'];
		 
		$query101 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SPP'" ;
		$result101 = runmysqlqueryfetch($query101);
		$thismonthsppnew = $thismonthsppnew + $result101['amount'];
		
		$query102= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'STO'" ;
		$result102 = runmysqlqueryfetch($query102);
		$thismonthstonew = $thismonthstonew + $result102['amount'];
		
		$query103 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVH'" ;
		$result103 = runmysqlqueryfetch($query103);
		$thismonthsvhnew = $thismonthsvhnew + $result103['amount'];
		
		$query104 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVI'" ;
		$result104 = runmysqlqueryfetch($query104);
		$thismonthsvinew = $thismonthsvinew + $result104['amount'];
		
		$query105 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SAC'" ;
		$result105 = runmysqlqueryfetch($query105);
		$thismonthsacnew =  $thismonthsacnew + $result105['amount'];
		
		// Updations of dealer based on product group and purchase type
		
		$query106 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'TDS'" ;
		$result106 = runmysqlqueryfetch($query106);
		$thismonthtdsupdation = $thismonthtdsupdation + $result106['amount'];
		
		
		$query107 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SPP'" ;
		$result107 = runmysqlqueryfetch($query107);
		$thismonthsppupdation =  $thismonthsppupdation + $result107['amount'];
		
		
		$query108= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'STO'" ;
		$result108 = runmysqlqueryfetch($query108);
		$thismonthstoupdation = $thismonthstoupdation + $result108['amount'];
		
		$query109 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVH'" ;
		$result109 = runmysqlqueryfetch($query109);
		$thismonthsvhupdation = $thismonthsvhupdation + $result109['amount'];
		
		
		$query110 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVI'" ;
		$result110 = runmysqlqueryfetch($query110);
		$thismonthsviupdation =  $thismonthsviupdation + $result110['amount'];
		
		$query111 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SAC'" ;
		$result111 = runmysqlqueryfetch($query111);
		$thismonthsacupdation = $thismonthsacupdation + $result111['amount'];
		
	}
	$grid .= '<tr>';
	$grid .= '<td nowrap="nowrap"  align="center">&nbsp;</td>';
	$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
	$grid .= '</tr>';
	$grid .= '</table>';
	
	// Calculate totals
	$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew ;
	$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation;
	$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew;
	$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation ;

	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '</table>';
	
	// Branch wise sales summary for Management
	
	// Fetch today's data
	// Fetch today's data
	$query = "select inv_mas_branch.branchname as branchname,today.netamount as todaysales,thismonth.netamount as thismonthsales from inv_mas_branch 
left join (select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by  branchid) as today on today.branchid = inv_mas_branch.slno 

left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$monthsdatepiece." and `status` <> 'CANCELLED'  group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno order by inv_mas_branch.branchname;";
	$result = runmysqlquery($query);
	
	// Create Table to display brach wise Summary
	$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branchwise Summary</strong></div>';
	$branchgrid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month till Date</td></tr>';
	
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0 ;
	$slno = 0;
	while($fetch = mysql_fetch_array($result))
	{
		$slno++;
		$tadaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
		$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
		
		$todaynewtotal = $todaynewtotal + $tadaysale;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tadaysale).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsale).'</td>';
		$branchgrid .= '</tr>';
		
	}
	$branchgrid .= '<tr>';
	$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
	$branchgrid .= '</tr>';
	
	$branchgrid .= '</table>';
	
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$msg = file_get_contents("../mailcontents/dayendsummaryaccounts.htm");
	$textmsg = file_get_contents("../mailcontents/dayendsummaryaccounts.txt");
	
	$subject = "Invoicing Summary for ".$date." [Management]";
	//Create an array of replace parameters
	$array = array();
	//$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".' Management';
	$array[] = "##EMAILID##%^%".'accounts@relyonsoft.com,nitin@relyonsoft.com,hsn@relyonsoft.com';
	$array[] = "##SALESDETAILS##%^%".$grid;
	$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
	$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
	$array[] = "##SUBJECT##%^%".$subject;
	//$emailarray = explode(',',$emailid);
	//$emailcount = count($emailid);
	if($_SERVER['HTTP_HOST'] == 'archanaab' )  
	{
		/*$emailid = array('name' =>array('Accounts','Nitin S Patel','H.S Nagendra'), 'emailid' => array('webmaster@relyonsoft.com', 'rashmi.hk@relyonsoft.com','meghana.b@relyonsoft.com'));*/
		$emailid = array('webmaster@relyonsoft.com','bopanna.kn@relyonsoft.com','meghana.b@relyonsoft.com');
	}
	else
	{
		//$emailid = array('webmaster@relyonsoft.com','rashmi.hk@relyonsoft.com','meghana.b@relyonsoft.com');
		$emailid = array('accounts@relyonsoft.com','nitin@relyonsoft.com','hsn@relyonsoft.com');
		//$emailid = array('Accounts' =>'accounts@relyonsoft.com','Nitin S Patel' => 'nitin@relyonsoft.com','H.S Nagendra' => 'hsn@relyonsoft.com');
	}
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
	$toarray = $emailidaccounts;print_r($toarray);
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
	
	$html = $msg;
	$text = $textmsg;
	$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890')
			);
		

	//rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
?>