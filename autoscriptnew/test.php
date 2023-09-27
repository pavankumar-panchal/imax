<?php


	ini_set('memory_limit', '-1');
	set_time_limit(0);
	include("../functions/phpfunctions.php");
	
	// Get Yesterday's Date 	
	$querygetdate = "select curdate() - INTERVAL 1 DAY as currentdate;";
	$resultgetdate = runmysqlqueryfetch($querygetdate);
	$date = changedateformat($resultgetdate['currentdate']);
	
	$datetoday = "(curdate()- INTERVAL 1 DAY)";
	
/*	if(date('d') == '01' || date('m') == '01')
	{
		echo('1');
		
	}
	
	else
	{
		echo('2');
		$datethismonth = "'2011-03-01'";
	}*/
	$datethismonth = "DATE_FORMAT(".$datetoday.",'%Y-%m-01')";
	
	$todaysdatepiece = "left(invoicedetails.invoicedate,10) = ".$datetoday."";
	$todaysdatepiece1 = "left(inv_invoicenumbers.createddate,10) = ".$datetoday."";
	$monthsdatepiece = "left(inv_invoicenumbers.createddate,10) between ".$datethismonth."  and ".$datetoday."";
	$monthsdatepiece = "left(inv_invoicenumbers.createddate,10) between ".$datethismonth." and ".$datetoday."";
	$servicedatetoday = "left(services.createddate,10) = ".$datetoday."";
	$servicedatethismonth = "left(services.createddate,10) between ".$datethismonth." and ".$datetoday."";
	// Define managed area array
	
	
	$query = "Drop table if exists invoicedetails;";
	$result = runmysqlquery($query);

	$query = "Drop table if exists services;";
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
	
	
	// Create Temporary table to insert 'ITEM SOFTWARE' details
	$queryservices = "CREATE   TABLE `services` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		 PRIMARY KEY  (`slno`)    
	 );";
	$result = runmysqlquery($queryservices);
	// Fetch the invoices generated in this month.
	
	$query2 = "select * from inv_invoicenumbers where ".$monthsdatepiece."  and `status` <> 'CANCELLED'";//echo($query2);exit;
	$result2 = runmysqlquery($query2);
	$count = 0;
	$totalamount = 0;
	
	//echo($query2);exit;
	
	while($fetch2 = mysql_fetch_array($result2))
	{
		$serviceamount = 0;
				if($fetch2['servicedescription'] <> '')
				{
					$serviceamountsplit = explode('*',$fetch2['servicedescription']);
					for($k = 0 ;$k < count($serviceamountsplit);$k++)
					{
						$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
						$serviceamount = $serviceamount + $finalsplit[2];
						// Insert into services table 
						$insertservices = "INSERT INTO services(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."')";
						$result = runmysqlquery($insertservices);
					}
				}
	}
	
	$query2 = "select * from inv_invoicenumbers where ".$monthsdatepiece." and products <> '' and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	
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
			
			if($i == 0)
			{
				$totalamount = $amount ;
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
	
	$testq = "select inv_mas_service.servicename,ifnull(sum(serviceamount),'0') as netamount from services left join inv_mas_service on inv_mas_service.servicename = services.servicename group by inv_mas_service.servicename; ";
	$res = runmysqlquery($testq);
	
	$servicegrid = '<table width="100%" cellspacing="0" border = "1" cellpadding="2" align = "left" style="font-size:12px;">';
	
	$servicegrid .= '<tr ><td nowrap="nowrap"  width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" align="center" width = "40%"><strong>Total</strong></td></tr>';
	
	
	while($fetch = mysql_fetch_array($res))
	{
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetch['servicename'].'</td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetch['netamount']).'</td>';
		$servicegrid .= '</tr>';
	}
	$servicegrid .= '</table>';	
	echo($servicegrid);
	
?>