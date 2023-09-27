<?php

	ini_set('memory_limit', '-1');
	set_time_limit(0);
	include("../functions/phpfunctions.php");

	// Define managed area array
	
	$managedareaarray = array(
						"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("archana.ab@relyonsoft.com","meghana.b@relyonsoft.com"),
										 "name" => array("Paramesh N","Nitin S Patel")),
						"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("archana.ab@relyonsoft.com"),
										 "name" => array("Raghavendra N")),
						"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("archana.ab@relyonsoft.com"),
										 "name" => array("Vijay Hebbar"))
					);

	$query = "Drop table if exists invoicedetails;";
	$result = runmysqlquery($query);

	// Create Temporary Table to insert Invoice details

	// Create Temporary Table to insert Invoice details

	$query = "CREATE TEMPORARY TABLE `invoicedetails` (                                       
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
	
	$query2 = "select * from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) between DATE_FORMAT(NOW() ,'%Y-%m-01') and (curdate()- INTERVAL 1 DAY ) and products <> '' and `status` <> 'CANCELLED'";
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
			
			$query3 = "insert into invoicedetails(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result0['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."')";
			
			$result3 =  runmysqlquery($query3);
		}
	}
	
	/*--------------------------------------Day End Sumary Email to Accounts----------------------------------   */
	
	$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Region</td><td nowrap="nowrap"  align="center">Today Sales</td><td nowrap="nowrap"  align="center">Month till Date</td></tr>';
	
	$productwisegrid  = '<table width="85%" cellspacing="0" border = "2" cellpadding="0" align = "center" style="font-family:calibri">';
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
	
	
	$slno = 0;
	
	// Region wise and producyt wise Summary 
	foreach($managedareaarray as $currentarea => $arrayvalue)
	{
		$slno++;
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
	
		// Fetch details of current date
		$query4 = "select ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.regionid = '".$regionid."' and left(inv_invoicenumbers.createddate,10) = (curdate()- INTERVAL 1 DAY) and `status` <> 'CANCELLED' ";
		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		$query5 = "select ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.regionid = '".$regionid."' and left(inv_invoicenumbers.createddate,10) between DATE_FORMAT(NOW() ,'%Y-%m-01') and (curdate()- INTERVAL 1 DAY ) and `status` <> 'CANCELLED'";
		$result5 = runmysqlquery($query5); 
		
		/*if($regionid == '2')
		{
			echo($query5); exit;}*/
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
		
		$query200 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'TDS' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		
		$result200 = runmysqlqueryfetch($query200);
		$tdsnew =  $tdsnew + $result200['amount'];
		 
		 
		$query201 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SPP' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result201 = runmysqlqueryfetch($query201);
		$sppnew =  $sppnew + $result201['amount'];
		
		
		$query202= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'STO' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result202 = runmysqlqueryfetch($query202);
		$stonew = $stonew + $result202['amount'];
		
		
		$query203 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVH' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result203 = runmysqlqueryfetch($query203);
		$svhnew = $svhnew + $result203['amount'];
		
		
		$query204 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SVI' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result204 = runmysqlqueryfetch($query204);
		$svinew = $svinew + $result204['amount'];
		
		$query205 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and productgroup = 'SAC' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result205 = runmysqlqueryfetch($query205);
		$sacnew =  $sacnew + $result205['amount'];
		
		// Updations of dealer based on product group and purchase type
		
		$query206 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'TDS' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result206 = runmysqlqueryfetch($query206);
		$tdsupdation = $tdsupdation + $result206['amount'];
		
		
		$query207 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SPP' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result207 = runmysqlqueryfetch($query207);
		$sppupdation = $sppupdation + $result207['amount'];
		
		
		$query208= "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'STO' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result208 = runmysqlqueryfetch($query208);
		$stoupdation = $stoupdation + $result208['amount'];
		
		$query209 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVH' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result209 = runmysqlqueryfetch($query209);
		$svhupdation =  $svhupdation + $result209['amount'];
		
		
		$query210 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SVI' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
		$result210 = runmysqlqueryfetch($query210);
		$sviupdation = $sviupdation + $result210['amount'];
		
		$query211 = "select ifnull(sum(amount),'0') as amount from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and productgroup = 'SAC' and  left(invoicedetails.invoicedate,10) = (curdate()- INTERVAL 1 DAY)" ;
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
	$grid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
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
left join (select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) = (curdate()- INTERVAL 1 DAY) and `status` <> 'CANCELLED' group by  branch) as today on today.branchid = inv_mas_branch.slno 

left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where left(inv_invoicenumbers.createddate,10) between DATE_FORMAT(NOW() ,'%Y-%m-01') and (curdate()- INTERVAL 1 DAY ) and `status` <> 'CANCELLED'  group by  branch)as thismonth on thismonth.branchid = inv_mas_branch.slno order by inv_mas_branch.branchname;";
	$result = runmysqlquery($query);
	
	

	
	// Create Table to display brach wise Summary
	$branchgrid  = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Today Sales</td><td nowrap="nowrap"  align="center">Month till Date</td></tr>';
	
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
	
	echo($grid);
	echo($branchgrid);
	echo($productwisegrid);
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$msg = file_get_contents("../mailcontents/dayendsummary.htm");
	$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
	
	
	//Create an array of replace parameters
	$array = array();
	//$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".' Management';
	$array[] = "##EMAILID##%^%".'accounts@relyonsoft.com,nitinall@relyonsoft.com,hsn@relyonsoft.com';
	$array[] = "##SALESDETAILS##%^%".$grid;
	$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
	$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
	//$emailarray = explode(',',$emailid);
	//$emailcount = count($emailid);
	//echo($emailcount); 
	if($_SERVER['HTTP_HOST'] == 'archanaab' )  
	{
		/*$emailid = array('name' =>array('Accounts','Nitin S Patel','H.S Nagendra'), 'emailid' => array('archana.ab@relyonsoft.com', 'rashmi.hk@relyonsoft.com','meghana.b@relyonsoft.com'));*/
		$emailid = array('archana.ab@relyonsoft.com','bopanna.kn@relyonsoft.com','meghana.b@relyonsoft.com');
	}
	else
	{
		$emailid = array('archana.ab@relyonsoft.com','bopanna.kn@relyonsoft.com','meghana.b@relyonsoft.com');
		//$emailid = array('Accounts' =>'accounts@relyonsoft.com','Nitin S Patel' => 'nitin@relyonsoft.com','H.S Nagendra' => 'hsn@relyonsoft.com');
	}
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailid);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailid[$i]))
		{
				$emailids[$emailid[$i]] = $emailid[$i];
		}
	}
	$toarray = $emailids;
//$toarray = $emailid;
	
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$text = "This is a HTML format email. Please enable HTML viewing in your email client.";
	$subject = "Invoicing Summary for ".$date." [Management]";
	$html = $msg;
	$text = $textmsg;
	$filearray = array(
				array('../images/relyon-logo.jpg','inline','1234567890')
			);
	
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
?>