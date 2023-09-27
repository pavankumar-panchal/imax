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
	
	$todaysdatepiece = "left(invoicedetails.invoicedate,10) = '".$yesterday."'";
	$todaysdatepiece1 = "left(inv_invoicenumbers.createddate,10) = '".$yesterday."'";
	$monthsdatepiece = "left(inv_invoicenumbers.createddate,10) between '".$dfc_monthstart."' and '".$dfc_monthend."'";
	$monthsdatepiece1 = "left(invoicedetails.invoicedate,10) between '".$dfc_monthstart."' and '".$dfc_monthend."'";
	$yeardatepiece = "left(inv_invoicenumbers.createddate,10) between '".$dfc_yearstart."' and '".$dfc_yearend."'";
	$servicedatetoday = "left(services.createddate,10) = '".$yesterday."'";
	$servicedatethismonth = "left(services.createddate,10) between '".$dfc_monthstart."' and '".$dfc_monthend."'";
	$servicedatethisyear = "left(services.createddate,10) between '".$dfc_yearstart."' and '".$dfc_yearend."'";
	$addlessdatetoday = "left(createddate,10) = '".$yesterday."'";
	$addlessdatethismonth = "left(createddate,10) between '".$dfc_monthstart."' and '".$dfc_monthend."'";
	$addlessedatethisyear = "left(createddate,10) between '".$dfc_yearstart."' and '".$dfc_yearend."'";
	// Define managed area array
	
	/*$managedareaarray = array(
						"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("rashmi.hk@relyonsoft.com"), "name" => array("Paramesh N","Nitin S Patel")),
						"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("rashmi.hk@relyonsoft.com"), "name" => array("Raghavendra N","Nitin S Patel")),
						"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("rashmi.hk@relyonsoft.com","meghana.b@relyonsoft.com"),
						"name" => array("Vijay Hebbar","Pradeep N","Vidyanand")),
						
					);*/


					
	$managedareaarray = array(
					"BKG" => array("regionid" => '1',"area" => "BKG", "emailid" => array("paramesh.n@relyonsoft.com","nitinall@relyonsoft.com"),
									 "name" => array("Paramesh N","Nitin S Patel")),
					"BKM" => array("regionid" => '3',"area" => "BKM", "emailid" => array("raghavendra.n@relyonsoft.com","nitinall@relyonsoft.com"),
									 "name" => array("Raghavendra N","Nitin S Patel")),
					"CSD" => array("regionid" => '2',"area" => "CSD", "emailid" => array("nitinall@relyonsoft.com","pradeep.n@relyonsoft.com"),
									 "name" => array("Nitin S Patel","Pradeep N"))			 
					
				);
	// Define Bcc Array

	if($_SERVER['HTTP_HOST'] == '192.168.2.132')  
	{
		//$bccarray = array('webmaster@relyonsoft.com');
	}
	else
	{
		$bccarray = array('Relyonimax' => 'relyonimax@gmail.com');
		//$bccarray = array('meghana.b@relyonsoft.com');
	}


	$query = "Drop table if exists invoicedetails;";
	$result = runmysqlquery($query);

	$query = "Drop table if exists services;";
	$result = runmysqlquery($query);
	
	$query = "Drop table if exists addlessdesc;";
	$result = runmysqlquery($query);
	
	
	// Create Temporary Table to insert Invoice details

	$query = "CREATE temporary TABLE `invoicedetails` (                                       
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
	$queryservices = "CREATE temporary TABLE `services` ( 
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
	
			
	// Create Temporary table to insert 'ADD/LESS' details
	
	$queryaddless = "CREATE temporary  TABLE `addlessdesc` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `descname` varchar(100) collate latin1_general_ci default NULL, 
		 `descamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		 PRIMARY KEY  (`slno`)    
	 );";
	$result = runmysqlquery($queryaddless);
	
	$query2 = "select * from inv_invoicenumbers where ".$yeardatepiece."  and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	$count = 0;
	$totalamount = 0;
	
	while($fetch2 = mysqli_fetch_array($result2))
	{
		$serviceamount = 0;
		if($fetch2['servicedescription'] <> '')
		{
			$serviceamountsplit = explode('*',$fetch2['servicedescription']);
			for($k = 0 ;$k < count($serviceamountsplit);$k++)
			{
				$finalsplit = explode('$',$serviceamountsplit[$k]); 
				$serviceamount = $serviceamount + $finalsplit[2];
				// Insert into services table 
				$insertservices = "INSERT INTO services(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."')";
				$result = runmysqlquery($insertservices);
			}
		}
	}
	
	$query2 = "select * from inv_invoicenumbers where ".$yeardatepiece." and products <> '' and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	while($fetch2 = mysqli_fetch_array($result2))
	{
		$count++;
		$totalamount = 0;
		$products = explode('#',$fetch2['products']);
		$description = explode('*',$fetch2['description']);
		$productquantity = explode(',',$fetch2['productquantity']);
		$k=0;
		for($i = 0 ; $i < count($description);$i++)
		{

			for($j = 0 ; $j < $productquantity[$i];$j++)
			{	
			  $totalamount = 0;
			  $amount = 0;
			  $splitdescription = explode('$',$description[$k]);
			  $productcode = $products[$i];
			  $usagetype = $splitdescription[3];
			  $amount = $splitdescription[6];
			  $purchasetype = $splitdescription[2];   
			  $totalamount = $amount ;
			  $k++;	
			  // Fetch Product 	
			  
			  $query0 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
			  $result0 = runmysqlqueryfetch($query0);
			  
			  // Insert into invoice details table
			  
			  $query3 = "insert into invoicedetails(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result0['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."')";
			  $result3 =  runmysqlquery($query3);
		  }
		}
	}

	//Insert to add/less description table
	$query32 = "select * from inv_invoicenumbers where ".$yeardatepiece." and offerdescription <> '' and `status` <> 'CANCELLED'";
	$result32 = runmysqlquery($query32);
	
	while($fetch32 = mysqli_fetch_array($result32))
	{
		$offeramount = 0;
		$addlesssplit = explode('*',$fetch32['offerdescription']);
		for($k = 0 ;$k < count($addlesssplit);$k++)
		{
			$addlesssplitdesc = explode('$',$addlesssplit[$k]); 
			$descamount = $addlesssplitdesc[2];
			$descname = $addlesssplitdesc[0];
			// Insert into services table 
			$insertoffer = "INSERT INTO addlessdesc(invoiceno,descname,descamount,createddate,dealerid,regionid,branch,branchname) values('".$fetch32['slno']."','". $descname."','". $descamount."','".$fetch32['createddate']."','".$fetch32['dealerid']."','".$fetch32['regionid']."','".$fetch32['branchid']."','".$fetch32['branch']."')";
			$result = runmysqlquery($insertoffer);
		}
	}
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
		if($_SERVER['HTTP_HOST'] == '192.168.2.132' )  
		{
			$emailid = '';
		}
		else
		{
			$emailid = $fetch['emailid'];
			//$emailid = 'rashmi.hk@relyonsoft.com';
		}	
		// Fetch today's details
		$query4 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result4 = runmysqlquery($query4);
		
		// Fetch this month details
		
		$query5 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result5 = runmysqlquery($query5); 
		
		$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers 
where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$yeardatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
		$result6 = runmysqlquery($query6); 
		
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "50%" align="center" >Name</td><td nowrap="nowrap"  align="center" width = "25%">Day Sales </td><td nowrap="nowrap"  align="center" width = "25%">Month to Date</td><td nowrap="nowrap"  align="center" width = "25%">Year to Date</td></tr>';
		
		
		$slno = 0;
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		$servicestotaltoday = 0;
		$servicestotalthismonth = 0;
		$thisyearupdationtotal = 0;
		$thisyearnewtotal = 0;
		
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
			
		if(mysqli_num_rows($result6) > 0)
		{
			$fetch6 = runmysqlqueryfetch($query6);
			$yeartilldate = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
		}
		else
			$yeartilldate = 0;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($yeartilldate).'</td>';
		$grid .= '</tr>';
		
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($yeartilldate).'</td>';
		$grid .= '</tr>';
		
		$grid .= '</table>';
			
		if($yeartilldate > 0)
		{	
			$productwisegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Software)</strong></div>';
			$productwisegrid .= '<table width="85%" border = "2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
			$productwisegrid .= '<tr style=" background-color:#b2cffe">';
			$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month to Date</div></td>';
			$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Year to Date</div></td>';
			$productwisegrid .= '</tr>';
			$productwisegrid .= '<tr style=" background-color:#b2cffe">';
			$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center" >New</div></td>';
			$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
			$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
			$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
			$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
			$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
			$productwisegrid .= '</tr>';

			// New Purchases of dealer based on product group and purchase type
			$tdsnew = 0;
			$sppnew = 0;
			$stonew = 0;
			$svhnew = 0;
			$svinew = 0;
			$sacnew = 0;
			$othersnew = 0;
			
			$query200 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."' and ".$todaysdatepiece." and purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result200 = runmysqlquery($query200);
			while($fetch200 = mysqli_fetch_array($result200))
			{
				if($fetch200['productgroup'] == 'TDS')
					$tdsnew += $fetch200['amount'];
				else if($fetch200['productgroup'] == 'SPP')
					$sppnew += $fetch200['amount'];
				else if($fetch200['productgroup'] == 'STO')
					$stonew += $fetch200['amount'];
				else if($fetch200['productgroup'] == 'SVH')
					$svhnew += $fetch200['amount'];
				else if($fetch200['productgroup'] == 'SVI')
					$svinew += $fetch200['amount'];
				else if($fetch200['productgroup'] == 'SAC')
					$sacnew += $fetch200['amount'];
				else
					$othersnew += $fetch200['amount'];
					
			}
			// Updations of dealer based on product group and purchase type
			$tdsupdation = 0;
			$sppupdation = 0;
			$stoupdation = 0;
			$svhupdation = 0;
			$sviupdation = 0;
			$sacupdation = 0;
			$othersupdation = 0;
			
			$query201 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."' and ".$todaysdatepiece." and purchasetype = 'Updation' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group; ";
			$result201 = runmysqlquery($query201);
			while($fetch201 = mysqli_fetch_array($result201))
			{
				if($fetch201['productgroup'] == 'TDS')
					$tdsupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SPP')
					$sppupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'STO')
					$stoupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVH')
					$svhupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVI')
					$sviupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'OTHERS')
					$sacupdation += $fetch201['amount'];
				else
					$othersupdation += $fetch201['amount'];
					
			}
			// Details of Month to Date 
			// New Purchases of dealer based on product group and purchase type

			$thismonthtdsnew = 0;
			$thismonthsppnew = 0;
			$thismonthstonew = 0;
			$thismonthsvhnew = 0;
			$thismonthsvinew = 0;
			$thismonthsacnew = 0;
			$thismonthothersnew = 0;
			
			$query100 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New' and ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result100 = runmysqlquery($query100);
			while($fetch201 = mysqli_fetch_array($result100))
			{
				if($fetch201['productgroup'] == 'TDS')
					$thismonthtdsnew += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SPP')
					$thismonthsppnew += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'STO')
					$thismonthstonew += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVH')
					$thismonthsvhnew += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVI')
					$thismonthsvinew += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SAC')
					$thismonthsacnew += $fetch201['amount'];
				else
					$thismonthothersnew += $fetch201['amount'];
					
			}
			// Updation Purchases of dealer based on product group and purchase type
			$thismonthtdsupdation = 0;
			$thismonthsppupdation = 0;
			$thismonthstoupdation = 0;
			$thismonthsvhupdation = 0;
			$thismonthsviupdation = 0;
			$thismonthsacupdation = 0;
			$thismonthothersupdation = 0;
			
			$query101 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation' and ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result101 = runmysqlquery($query101);
			while($fetch201 = mysqli_fetch_array($result101))
			{
				if($fetch201['productgroup'] == 'TDS')
					$thismonthtdsupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SPP')
					$thismonthsppupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'STO')
					$thismonthstoupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVH')
					$thismonthsvhupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SVI')
					$thismonthsviupdation += $fetch201['amount'];
				else if($fetch201['productgroup'] == 'SAC')
					$thismonthsacupdation += $fetch201['amount'];
				else
					$thismonthothersupdation += $fetch201['amount'];
					
			}
			
			
			// Details of Year to Date 
			// New Purchases of dealer based on product group and purchase type

			$thisyeartdsnew = 0;
			$thisyearsppnew = 0;
			$thisyearstonew = 0;
			$thisyearsvhnew = 0;
			$thisyearsvinew = 0;
			$thisyearsacnew = 0;
			$thisyearothersnew = 0;
			
			$query122 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and  purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result122 = runmysqlquery($query122);
			while($fetch122 = mysqli_fetch_array($result122))
			{
				if($fetch122['productgroup'] == 'TDS')
					$thisyeartdsnew += $fetch122['amount'];
				else if($fetch122['productgroup'] == 'SPP')
					$thisyearsppnew += $fetch122['amount'];
				else if($fetch122['productgroup'] == 'STO')
					$thisyearstonew += $fetch122['amount'];
				else if($fetch122['productgroup'] == 'SVH')
					$thisyearsvhnew += $fetch122['amount'];
				else if($fetch122['productgroup'] == 'SVI')
					$thisyearsvinew += $fetch122['amount'];
				else if($fetch122['productgroup'] == 'SAC')
					$thisyearsacnew += $fetch122['amount'];
				else
					$thisyearothersnew += $fetch122['amount'];
					
			}
			
			// Updation Purchases of dealer based on product group and purchase type
			$thisyeartdsupdation = 0;
			$thisyearsppupdation = 0;
			$thisyearstoupdation = 0;
			$thisyearsvhupdation = 0;
			$thisyearsviupdation = 0;
			$thisyearsacupdation = 0;
			$thisyearothersupdation = 0;
			
			$query133 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result133 = runmysqlquery($query133);
			while($fetch133 = mysqli_fetch_array($result133))
			{
				if($fetch133['productgroup'] == 'TDS')
					$thisyeartdsupdation += $fetch133['amount'];
				else if($fetch133['productgroup'] == 'SPP')
					$thisyearsppupdation += $fetch133['amount'];
				else if($fetch133['productgroup'] == 'STO')
					$thisyearstoupdation += $fetch133['amount'];
				else if($fetch133['productgroup'] == 'SVH')
					$thisyearsvhupdation += $fetch133['amount'];
				else if($fetch133['productgroup'] == 'SVI')
					$thisyearsviupdation += $fetch133['amount'];
				else if($fetch133['productgroup'] == 'SAC')
					$thisyearsacupdation += $fetch133['amount'];
				else
					$thisyearothersupdation += $fetch133['amount'];
					
			}
			
			$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew +$othersnew ;
			$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation +$othersupdation;
			$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew + $thismonthothersnew;
			$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation+ $thismonthothersupdation ;
			$thisyearnewtotal = $thisyeartdsnew + $thisyearsppnew + $thisyearstonew + $thisyearsvhnew + $thisyearsvinew + $thisyearsacnew + $thisyearothersnew;
			$thisyearupdationtotal = $thisyeartdsupdation + $thisyearsppupdation + $thisyearstoupdation + $thisyearsvhupdation + $thisyearsviupdation + $thisyearsacupdation+ $thisyearothersupdation ;
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstonew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstoupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvinew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsviupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacupdation).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center">OTHERS</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersupdation).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersnew).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersupdation).'</td>';
			$productwisegrid .= '</tr>';
	
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearnewtotal).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearupdationtotal).'</td>';
			$productwisegrid .= '</tr>';
			$productwisegrid .= '</table>';
			
			// Generate grid for services
			
			$servicegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Services)</strong></div>';
			$servicegrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
			//Write the header Row of the table
			$servicegrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Service Name</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
			
			$serviceamount = 0;
			
			// Get Today's services 
			$servicesquerytoday = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where dealerid = "'.$dealerid.'" and '.$servicedatetoday.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
			
			//Get Month's services
			$servicesquerymonth = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where dealerid = "'.$dealerid.'"  and '.$servicedatethismonth.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
			
			//Get year's services
			$servicesqueryyear = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where dealerid = "'.$dealerid.'"  and '.$servicedatethisyear.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
			
			$servicesresultyear = runmysqlquery($servicesqueryyear);
			$servicesresultmonth = runmysqlquery($servicesquerymonth);
			$serviceamounttoday = 0;$serviceamountmonth =0;$serviceamountyear=0;
			$servicesresulttoday = runmysqlquery($servicesquerytoday);
			while($sevicesfetchyear = mysqli_fetch_array($servicesresultyear))
			{
				$sevicesfetchmonth = mysqli_fetch_array($servicesresultmonth);
				$sevicesfetchtoday =  mysqli_fetch_array($servicesresulttoday);
				$servicegrid .= '<tr>';
				$servicegrid .= '<td nowrap="nowrap"  align="left">'.$sevicesfetchmonth['servicename'].'</td>';
				$serviceamounttoday += $sevicesfetchtoday['amount'];
				$serviceamountmonth += $sevicesfetchmonth['amount'];
				$serviceamountyear += $sevicesfetchyear['amount'];
				$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchtoday['amount']).'</td>';
				$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchmonth['amount']).'</td>';
				$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchyear['amount']).'</td>';
				$servicegrid .= '</tr>';
			}
			$servicegrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamounttoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountmonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountyear).'</td></tr>';
			$servicegrid .= '</table>';
			
			// Generate grid for Add/Less 
			
			$addlessgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Others)</strong></div>';
			$addlessgrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
			//Write the header Row of the table
			$addlessgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
			
			$addlessamount = 0;
			
			// Get Today's services 
			$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesc where dealerid = "'.$dealerid.'" and '.$addlessdatetoday.' group by descname order by descname;';
			
			//Get Month's services
			$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesc  where dealerid = "'.$dealerid.'"  and '.$addlessdatethismonth.' group by descname;';
			
			//Get year's services
			$addlessqueryyear = 'select sum(descamount) as amount,descname from addlessdesc  where dealerid = "'.$dealerid.'"  and '.$addlessedatethisyear.' group by descname;';
			
			$addlessresultyear = runmysqlquery($addlessqueryyear);
			$addlessresultmonth = runmysqlquery($addlessquerymonth);
			$addlessresulttoday = runmysqlquery($addlessquerytoday);
			$addlessresultyearcount = mysqli_num_rows($addlessresultyear);
			$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0; $addlessfetchyearadd =0;$addlessfetchyearless=0;$differenceyear=0;
			$differencevalue = 0;
			while($addlessfetchyear = mysqli_fetch_array($addlessresultyear))
			{
								
				if($addlessresultyearcount == 2)	
				{	
					if($differencevalue == 0)
					{
					  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
					  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
					  $addlessfetchyearadd = $addlessfetchyear['amount'];
					}
					else
					{
					  $addlessfetchtodayless = $addlessfetchtoday['amount'];
					  $addlessfetchmonthless = $addlessfetchmonth['amount'];
					  $addlessfetchyearless = $addlessfetchyear['amount'];
					}
				 	$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
					$addlessgrid .= '</tr>';
					$differencevalue++;

				}
				else
				{
				  if($addlessfetchyear['descname'] == 'add')
				  {
					  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
					  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
					  $addlessfetchyearadd = $addlessfetchyear['amount'];
					  $addlessgrid .= '<tr>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
					  $addlessgrid .= '</tr>';
					  $addlessgrid .= '<tr>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="left">LESS</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '</tr>';
				  }
				  else if($addlessfetchyear['descname'] == 'less')
				  {
					  $addlessfetchtodayless = $addlessfetchtoday['amount'];
					  $addlessfetchmonthless = $addlessfetchmonth['amount'];
					  $addlessfetchyearless = $addlessfetchyear['amount'];
					  $addlessgrid .= '<tr>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="left">ADD</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					  $addlessgrid .= '</tr>';
					  $addlessgrid .= '<tr>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					   $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
					  $addlessgrid .= '</tr>';
				  }
				}
				$differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
				$differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
				$differenceyear = $addlessfetchyearadd - $addlessfetchyearless;

			}
			$addlessgrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($differencetoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differencemonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differenceyear).'</td></tr>';
			$addlessgrid .= '</table>';

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
			$array[] = "##SERVICESSALES##%^%".$servicegrid;
			$array[] = "##ADDLESSGRID##%^%".$addlessgrid;
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
	}

	/*----------------------------Day End Summary Email to Branch heads-------------------------*/

	// Fetch branch head details to send email.

	
	$query4 = "select inv_mas_dealer.slno,inv_mas_dealer.businessname,inv_mas_dealer.emailid,branchname,inv_mas_branch.slno as branch from inv_mas_dealer left join inv_mas_branch on inv_mas_branch.slno = inv_mas_dealer.branch where branchhead = 'yes' ";
	$result4 = runmysqlquery($query4);

	while($fetch4 = mysqli_fetch_array($result4))
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
		$othersnew = 0;
		$othersupdation = 0;
		$thismonthothersnew = 0;
		$thismonthothersupdation = 0;
		$thisyeartdsnew = 0;
		$thisyearsppnew = 0;
		$thisyearstonew = 0;
		$thisyearsvhnew = 0;
		$thisyearsvinew = 0;
		$thisyearsacnew = 0;
		$thisyearothersnew = 0;
		$thisyeartdsupdation = 0;
		$thisyearsppupdation = 0;
		$thisyearstoupdation = 0;
		$thisyearsvhupdation = 0;
		$thisyearsviupdation = 0;
		$thisyearsacupdation = 0;
		$thisyearothersupdation = 0;
		
		
		// Select the dealers under Branch head based on Branch 
		if($_SERVER['HTTP_HOST'] == '192.168.2.132')  
		{
			$emailid = '';
		}
		else
		{
			$emailid = $fetch4['emailid'];
			//$emailid = 'rashmi.hk@relyonsoft.com';
		}		
		$name = $fetch4['businessname']; 
		$query5 = "select * from inv_mas_dealer where branch = '".$fetch4['branch']."' AND  inv_mas_dealer.disablelogin = 'no' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where branchid = '".$fetch4['branch']."'))";
		$result5 = runmysqlquery($query5);
		
		$slno = 0;
		
		// put the details to table to display in email content.
		$grid = '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Sales</td><td nowrap="nowrap"  width = "20%" align="center">Month to Date</td><td nowrap="nowrap"  width = "20%" align="center">Year to Date</td></tr>';
		
		$productwisegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Software)</strong></div>';
		$productwisegrid  .= '<table width="85%" border="2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month to Date</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Year to Date</div></td>';

		$productwisegrid .= '</tr>';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center" >New</div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '</tr>';
		
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		$todaynewtotalall = 0;
		$todaymonthtilldatetotalall = 0;
		$todayyeartilldatetotalall = 0;
		while($fetch5 = mysqli_fetch_array($result5))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch5['slno'];
			$dealername = $fetch5['businessname']; 
			
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."'  and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			$query8 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."'  and ".$yeardatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result8 = runmysqlquery($query8); 
			
			
			if(mysqli_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysqli_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['netamount'] == '')?'0' : $fetch7['netamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
			if(mysqli_num_rows($result8) > 0)
			{
				$fetch8 = runmysqlqueryfetch($query8);
				$yeartilldate = ($fetch8['netamount'] == '')?'0' : $fetch8['netamount'];
			}
			else
			{
				$yeartilldate = 0;
			}
		
			// Get Totals 
			
			$todaynewtotalall = $todaynewtotalall + $todaynew;
			$todaymonthtilldatetotalall = $todaymonthtilldatetotalall + $monthtilldate;
			$todayyeartilldatetotalall = $todayyeartilldatetotalall + $yeartilldate;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($yeartilldate).'</td>';
			$grid .= '</tr>';
			
			// Invoices raised today
			
			// New Purchases of dealer based on product group and purchase type

		
			$query2001 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New' and  ".$todaysdatepiece." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result2001 = runmysqlquery($query2001);
			while($fetch2001 = mysqli_fetch_array($result2001))
			{
				if($fetch2001['productgroup'] == 'TDS')
					$tdsnew += $fetch2001['amount'];
				else if($fetch2001['productgroup'] == 'SPP')
					$sppnew += $fetch2001['amount'];
				else if($fetch2001['productgroup'] == 'STO')
					$stonew += $fetch2001['amount'];
				else if($fetch2001['productgroup'] == 'SVH')
					$svhnew += $fetch2001['amount'];
				else if($fetch2001['productgroup'] == 'SVI')
					$svinew += $fetch2001['amount'];
				else if($fetch2001['productgroup'] == 'SAC')
					$sacnew += $fetch2001['amount'];
				else
					$othersnew += $fetch2001['amount'];
					
			}
			// Updations of dealer based on product group and purchase type

		
			$query2061 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation' and  ".$todaysdatepiece." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group; ";
			$result2061 = runmysqlquery($query2061);
			while($fetch2061 = mysqli_fetch_array($result2061))
			{
				if($fetch2061['productgroup'] == 'TDS')
					$tdsupdation += $fetch2061['amount'];
				else if($fetch2061['productgroup'] == 'SPP')
					$sppupdation += $fetch2061['amount'];
				else if($fetch2061['productgroup'] == 'STO')
					$stoupdation += $fetch2061['amount'];
				else if($fetch2061['productgroup'] == 'SVH')
					$svhupdation += $fetch2061['amount'];
				else if($fetch2061['productgroup'] == 'SVI')
					$sviupdation += $fetch2061['amount'];
				else if($fetch2061['productgroup'] == 'SAC')
					$sacupdation += $fetch2061['amount'];
				else
					$othersupdation += $fetch2061['amount'];
					
			}
			// Details of Month to Date 
			
			// New Purchases of dealer based on product group and purchase type

			$query1001 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New' and  ".$monthsdatepiece1."  group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result1001 = runmysqlquery($query1001);
			while($fetch1001 = mysqli_fetch_array($result1001))
			{
				if($fetch1001['productgroup'] == 'TDS')
					$thismonthtdsnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SPP')
					$thismonthsppnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'STO')
					$thismonthstonew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SVH')
					$thismonthsvhnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SVI')
					$thismonthsvinew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SAC')
					$thismonthsacnew += $fetch1001['amount'];
				else
					$thismonthothersnew += $fetch1001['amount'];
			}
			// Updations of dealer based on product group and purchase type

			$query1061 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation' and  ".$monthsdatepiece1."  group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result1061 = runmysqlquery($query1061);
			while($fetch1061 = mysqli_fetch_array($result1061))
			{
				if($fetch1061['productgroup'] == 'TDS')
					$thismonthtdsupdation += $fetch1061['amount'];
				else if($fetch1061['productgroup'] == 'SPP')
					$thismonthsppupdation += $fetch1061['amount'];
				else if($fetch1061['productgroup'] == 'STO')
					$thismonthstoupdation += $fetch1061['amount'];
				else if($fetch1061['productgroup'] == 'SVH')
					$thismonthsvhupdation += $fetch1061['amount'];
				else if($fetch1061['productgroup'] == 'SVI')
					$thismonthsviupdation += $fetch1061['amount'];
				else if($fetch1061['productgroup'] == 'SAC')
					$thismonthsacupdation += $fetch1061['amount'];
				else
					$$thismonthothersupdation += $fetch1061['amount'];
			}
			
			// Details of Year to Date 
			
			// New Purchases of dealer based on product group and purchase type

			$query1022 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result1022 = runmysqlquery($query1022);
			while($fetch1022 = mysqli_fetch_array($result1022))
			{
				if($fetch1022['productgroup'] == 'TDS')
					$thisyeartdsnew += $fetch1022['amount'];
				else if($fetch1022['productgroup'] == 'SPP')
					$thisyearsppnew += $fetch1022['amount'];
				else if($fetch1022['productgroup'] == 'STO')
					$thisyearstonew += $fetch1022['amount'];
				else if($fetch1022['productgroup'] == 'SVH')
					$thisyearsvhnew += $fetch1022['amount'];
				else if($fetch1022['productgroup'] == 'SVI')
					$thisyearsvinew += $fetch1022['amount'];
				else if($fetch1022['productgroup'] == 'SAC')
					$thisyearsacnew += $fetch1022['amount'];
				else
					$thisyearothersnew += $fetch1022['amount'];
			}
			
			$query1066 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation'  group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result1066 = runmysqlquery($query1066);
			while($fetch1066 = mysqli_fetch_array($result1066))
			{
				if($fetch1066['productgroup'] == 'TDS')
					$thisyeartdsupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SPP')
					$thisyearsppupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'STO')
					$thisyearstoupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SVH')
					$thisyearsvhupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SVI')
					$thisyearsviupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SAC')
					$thisyearsacupdation += $fetch1066['amount'];
				else
					$thisyearothersupdation += $fetch1066['amount'];
			}

		}
		$todaynewtotal = 0;
		$todayupdationtotal = 0;
		$thismonthnewtotal = 0;
		$thismonthupdationtotal = 0;
		$thisyearnewtotal = 0;
		$thisyearupdationtotal = 0;
		// Calculate totals
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew + $othersnew;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation + $othersupdation;
		$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew +$thismonthothersnew ;
		$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation + $thismonthothersupdation;
		$thisyearnewtotal = $thisyeartdsnew + $thisyearsppnew + $thisyearstonew + $thisyearsvhnew + $thisyearsvinew + $thisyearsacnew +$thisyearothersnew ;
		$thisyearupdationtotal = $thisyeartdsupdation + $thisyearsppupdation + $thisyearstoupdation + $thisyearsvhupdation + $thisyearsviupdation + $thisyearsacupdation + $thisyearothersupdation;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotalall).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotalall).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayyeartilldatetotalall).'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';

	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstoupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsviupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearupdationtotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '</table>';
		// Create a table for services

		$servicegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Services)</strong></div>';
		$servicegrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$servicegrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Service Name</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
		
	// Get Today's services 
		$servicesquerytoday = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where branch = "'.$fetch4['branch'].'" and '.$servicedatetoday.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		//Get Month's services
		$servicesquerymonth = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where branch = "'.$fetch4['branch'].'" and '.$servicedatethismonth.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		//Get Year's services
		$servicesqueryyear = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where branch = "'.$fetch4['branch'].'" and '.$servicedatethisyear.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		
		$servicesresultyear = runmysqlquery($servicesqueryyear);
		$servicesresultmonth = runmysqlquery($servicesquerymonth);
		$servicesresulttoday = runmysqlquery($servicesquerytoday);
		$serviceamounttoday = 0; $serviceamountmonth =0;$serviceamountyear=0;
		while($sevicesfetchyear = mysqli_fetch_array($servicesresultyear))
		{
			$sevicesfetchmonth = mysqli_fetch_array($servicesresultmonth);
			$sevicesfetchtoday =  mysqli_fetch_array($servicesresulttoday);
			$servicegrid .= '<tr>';
			$servicegrid .= '<td nowrap="nowrap"  align="left">'.$sevicesfetchmonth['servicename'].'</td>';
			$serviceamounttoday += $sevicesfetchtoday['amount'];
			$serviceamountmonth += $sevicesfetchmonth['amount'];
			$serviceamountyear += $sevicesfetchyear['amount'];
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchtoday['amount']).'</td>';
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchmonth['amount']).'</td>';
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchyear['amount']).'</td>';
			$servicegrid .= '</tr>';
		}
		$servicegrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamounttoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountmonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountyear).'</td></tr>';
		$servicegrid .= '</table>';
	
		// Generate grid for Add/Less 
		  
		$addlessgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Others)</strong></div>';
		$addlessgrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$addlessgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
		
		$addlessamount = 0;
		
		  // Get Today's services 
		$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesc where branch = "'.$fetch4['branch'].'" and '.$addlessdatetoday.' group by descname order by descname;';
		  
		  //Get Month's services
		$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesc  where branch = "'.$fetch4['branch'].'"  and '.$addlessdatethismonth.' group by descname;';
		
		  //Get Month's services
		$addlessqueryyear = 'select sum(descamount) as amount,descname from addlessdesc  where branch = "'.$fetch4['branch'].'"  group by descname;';
		
		$addlessresultyear = runmysqlquery($addlessqueryyear);
		$addlessresultmonth = runmysqlquery($addlessquerymonth);
		$addlessresulttoday = runmysqlquery($addlessquerytoday);
		$addlessresultyearcount = mysqli_num_rows($addlessresultyear);
		$addlessfetchmonth = mysqli_fetch_array($addlessresultmonth);
		$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;$addlessfetchyearadd=0; $addlessfetchyearless=0;$differenceyear=0;
		$differencevalue = 0;
		while($addlessfetchyear = mysqli_fetch_array($addlessresultyear))
		{
							
			if($addlessresultyearcount == 2)	
			{	
				if($differencevalue == 0)
				{
				  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
				  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
				  $addlessfetchyearadd = $addlessfetchyear['amount'];
				}
				else
				{
				  $addlessfetchtodayless = $addlessfetchtoday['amount'];
				  $addlessfetchmonthless = $addlessfetchmonth['amount'];
				  $addlessfetchyearless = $addlessfetchyear['amount'];
				}
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
				$addlessgrid .= '</tr>';
				$differencevalue++;

			}
			else
			{
			  if($addlessfetchyear['descname'] == 'add')
			  {
				  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
				  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
				  $addlessfetchyearadd = $addlessfetchyear['amount'];
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
				  $addlessgrid .= '</tr>';
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="left">LESS</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '</tr>';
			  }
			  else if($addlessfetchyear['descname'] == 'less')
			  {
				  $addlessfetchtodayless = $addlessfetchtoday['amount'];
				  $addlessfetchmonthless = $addlessfetchmonth['amount'];
				  $addlessfetchyearless = $addlessfetchyear['amount'];
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="left">ADD</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
				  $addlessgrid .= '</tr>';
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
				  $addlessgrid .= '</tr>';
			  }
			}
			$differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
			$differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
			$differenceyear = $addlessfetchyearadd - $addlessfetchyearless;

		}
		$addlessgrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($differencetoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differencemonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differenceyear).'</td></tr>';
		$addlessgrid .= '</table>';
	

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
		$array[] = "##SERVICESSALES##%^%".$servicegrid;
		$array[] = "##ADDLESSGRID##%^%".$addlessgrid;
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
		$othersnew = 0;
		$othersupdation = 0;
		$thismonthothersnew = 0;
		$thismonthothersupdation = 0;
		$thisyeartdsnew = 0;
		$thisyearsppnew = 0;
		$thisyearstonew = 0;
		$thisyearsvhnew = 0;
		$thisyearsvinew = 0;
		$thisyearsacnew = 0;
		$thisyearothersnew = 0;
		$thisyeartdsupdation = 0;
		$thisyearsppupdation = 0;
		$thisyearstoupdation = 0;
		$thisyearsvhupdation = 0;
		$thisyearsviupdation = 0;
		$thisyearsacupdation = 0;
		$thisyearothersupdation = 0;
		
		
		$regionid = $arrayvalue['regionid'];
		$managedarea = $arrayvalue['area'];
		$emailid = $arrayvalue['emailid'];
		$name = $arrayvalue['name'];
		
		$query4 = "select * from inv_mas_dealer where region = '".$regionid."' AND  inv_mas_dealer.disablelogin = 'no' and (enablebilling = 'yes' or inv_mas_dealer.slno in(select distinct dealerid from inv_invoicenumbers where regionid = '".$regionid."'));";
		
		$result4 = runmysqlquery($query4);
		$slno = 0;
		
		// put the details to table to display in email content.
		$grid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Dealer Wise Summary</strong></div>';
		$grid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		
		//Write the header Row of the table
		$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "10%" align="center">Sl No</td><td nowrap="nowrap" width = "50%" align="center">Name</td><td nowrap="nowrap" width = "20%" align="center">Day Sales</td><td nowrap="nowrap"  width = "20%" align="center">Month to Date</td><td nowrap="nowrap"  width = "20%" align="center">Year to Date</td></tr>';
		
		$productwisegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Software)</strong></div>';
		$productwisegrid  .= '<table width="85%" border="2" cellspacing="0" cellpadding="2" align = "center" style="font-family:calibri">';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month to Date</div></td>';
		$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Year to Date</div></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '<tr style=" background-color:#b2cffe">';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center" >New</div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
		$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
		$productwisegrid .= '</tr>';
		
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0;
		$todayyeartilldatetotal = 0;
		while($fetch4 = mysqli_fetch_array($result4))
		{
			$slno++;
			// Consider each dealer and add them to grid .
			$dealerid = $fetch4['slno'];
			$dealername = $fetch4['businessname']; 
			
			// Fetch today's details 
			
			$query6 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result6 = runmysqlquery($query6);
			
			// Fetch this month details
			
			$query7 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$monthsdatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result7 = runmysqlquery($query7); 
			
			
			// Fetch this year details
			
			$query8 = "select inv_invoicenumbers.dealerid,ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.dealerid = '".$dealerid."' and ".$yeardatepiece." and `status` <> 'CANCELLED' group by inv_invoicenumbers.dealerid";
			$result8 = runmysqlquery($query8); 


			if(mysqli_num_rows($result6) > 0)
			{
				$fetch6 = runmysqlqueryfetch($query6);
				$todaynew = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
			}
			else
			{
				$todaynew = 0;
			}
			if(mysqli_num_rows($result7) > 0)
			{
				$fetch7 = runmysqlqueryfetch($query7);
				$monthtilldate = ($fetch7['netamount'] == '')?'0' : $fetch7['netamount'];
			}
			else
			{
				$monthtilldate = 0;
			}
			if(mysqli_num_rows($result8) > 0)
			{
				$fetch8 = runmysqlqueryfetch($query8);
				$yeartilldate = ($fetch8['netamount'] == '')?'0' : $fetch8['netamount'];
			}
			else
			{
				$yeartilldate = 0;
			}
		
			// Get Totals 
			
			$todaynewtotal = $todaynewtotal + $todaynew;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
			$todayyeartilldatetotal = $todayyeartilldatetotal + $yeartilldate;
			
			$grid .= '<tr>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$grid .= '<td nowrap="nowrap"  align="left">'.$dealername.'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
			$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($yeartilldate).'</td>';
			$grid .= '</tr>';
			
			// New Purchases of dealer based on product group and purchase type
			

			$query2002 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."' and ".$todaysdatepiece." and purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result2002 = runmysqlquery($query2002);
			while($fetch2002 = mysqli_fetch_array($result2002))
			{
				if($fetch2002['productgroup'] == 'TDS')
					$tdsnew += $fetch2002['amount'];
				else if($fetch2002['productgroup'] == 'SPP')
					$sppnew += $fetch2002['amount'];
				else if($fetch2002['productgroup'] == 'STO')
					$stonew += $fetch2002['amount'];
				else if($fetch2002['productgroup'] == 'SVH')
					$svhnew += $fetch2002['amount'];
				else if($fetch2002['productgroup'] == 'SVI')
					$svinew += $fetch2002['amount'];
				else if($fetch2002['productgroup'] == 'SAC')
					$sacnew += $fetch2002['amount'];
				else
					$othersnew += $fetch2002['amount'];
					
			}
			// Updations of dealer based on product group and purchase type
			
			$query203 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."' and ".$todaysdatepiece." and purchasetype = 'Updation' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result203 = runmysqlquery($query203);
			while($fetch203 = mysqli_fetch_array($result203))
			{
				if($fetch203['productgroup'] == 'TDS')
					$tdsupdation += $fetch203['amount'];
				else if($fetch203['productgroup'] == 'SPP')
					$sppupdation += $fetch203['amount'];
				else if($fetch203['productgroup'] == 'STO')
					$stoupdation += $fetch203['amount'];
				else if($fetch203['productgroup'] == 'SVH')
					$svhupdation += $fetch203['amount'];
				else if($fetch203['productgroup'] == 'SVI')
					$sviupdation += $fetch203['amount'];
				else if($fetch203['productgroup'] == 'SAC')
					$sacupdation += $fetch203['amount'];
				else
					$othersupdation += $fetch203['amount'];
					
			}
		
			// Details of Month to Date 

			
			$query1001 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New'  and ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result1001 = runmysqlquery($query1001);
			while($fetch1001 = mysqli_fetch_array($result1001))
			{
				if($fetch1001['productgroup'] == 'TDS')
					$thismonthtdsnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SPP')
					$thismonthsppnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'STO')
					$thismonthstonew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SVH')
					$thismonthsvhnew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SVI')
					$thismonthsvinew += $fetch1001['amount'];
				else if($fetch1001['productgroup'] == 'SAC')
					$thismonthsacnew += $fetch1001['amount'];
				else
					$thismonthothersnew += $fetch1001['amount'];
					
			}
			// Updation Purchases of dealer based on product group and purchase type

			$query1011 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation'  and ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result1011 = runmysqlquery($query1011);
			while($fetch1011 = mysqli_fetch_array($result1011))
			{
				if($fetch1011['productgroup'] == 'TDS')
					$thismonthtdsupdation += $fetch1011['amount'];
				else if($fetch1011['productgroup'] == 'SPP')
					$thismonthsppupdation += $fetch1011['amount'];
				else if($fetch1011['productgroup'] == 'STO')
					$thismonthstoupdation += $fetch1011['amount'];
				else if($fetch1011['productgroup'] == 'SVH')
					$thismonthsvhupdation += $fetch1011['amount'];
				else if($fetch1011['productgroup'] == 'SVI')
					$thismonthsviupdation += $fetch1011['amount'];
				else if($fetch1011['productgroup'] == 'SAC')
					$thismonthsacupdation += $fetch1011['amount'];
				else
					$thismonthothersupdation += $fetch1011['amount'];
					
			}
			
			$query1055 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
			$result1055 = runmysqlquery($query1055);
			while($fetch1055 = mysqli_fetch_array($result1055))
			{
				if($fetch1055['productgroup'] == 'TDS')
					$thisyeartdsnew += $fetch1055['amount'];
				else if($fetch1055['productgroup'] == 'SPP')
					$thisyearsppnew += $fetch1055['amount'];
				else if($fetch1055['productgroup'] == 'STO')
					$thisyearstonew += $fetch1055['amount'];
				else if($fetch1055['productgroup'] == 'SVH')
					$thisyearsvhnew += $fetch1055['amount'];
				else if($fetch1055['productgroup'] == 'SVI')
					$thisyearsvinew += $fetch1055['amount'];
				else if($fetch1055['productgroup'] == 'SAC')
					$thisyearsacnew += $fetch1055['amount'];
				else
					$thisyearothersnew += $fetch1055['amount'];
					
			}
			
			// Updation Purchases of dealer based on product group and purchase type

			$query1066 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where dealerid = '".$dealerid."'  and purchasetype = 'Updation'  group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
			$result1066 = runmysqlquery($query1066);
			while($fetch1066 = mysqli_fetch_array($result1066))
			{
				if($fetch1066['productgroup'] == 'TDS')
					$thisyeartdsupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SPP')
					$thisyearsppupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'STO')
					$thisyearstoupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SVH')
					$thisyearsvhupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SVI')
					$thisyearsviupdation += $fetch1066['amount'];
				else if($fetch1066['productgroup'] == 'SAC')
					$thisyearsacupdation += $fetch1066['amount'];
				else
					$thisyearothersupdation += $fetch1066['amount'];
			}
			
			
			
		}
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayyeartilldatetotal).'</td>';
		$grid .= '</tr>';
		$grid .= '</table>';
		
		// Calculate totals
		$thisyearnewtotal =0;$thisyearupdationtotal =0;$thismonthupdationtotal =0;$thismonthnewtotal =0;$todayupdationtotal =0;$todaynewtotal =0;
		$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew + $othersnew;
		$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation + $othersupdation;
		$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew + $thismonthothersnew;
		$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation + $thismonthothersupdation;
		
		$thisyearnewtotal = $thisyeartdsnew + $thisyearsppnew + $thisyearstonew + $thisyearsvhnew + $thisyearsvinew + $thisyearsacnew + $thisyearothersnew;
		$thisyearupdationtotal = $thisyeartdsupdation + $thisyearsppupdation + $thisyearstoupdation + $thisyearsvhupdation + $thisyearsviupdation + $thisyearsacupdation + $thisyearothersupdation;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstonew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstoupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';

		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvinew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsviupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersupdation).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersnew).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersupdation).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearnewtotal).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearupdationtotal).'</td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		// Get Branch wise summary for the area heads 
		
		$query = "select inv_mas_branch.branchname as branchname,today.netamount as todaysales,thismonth.netamount as thismonthsales,thisyear.netamount as thisyearsales from inv_mas_branch left join (select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$todaysdatepiece1." and `status` <> 'CANCELLED' and regionid = '".$regionid."' group by  branchid) as today on today.branchid = inv_mas_branch.slno 
left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$monthsdatepiece." and `status` <> 'CANCELLED' and regionid = '".$regionid."' group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$yeardatepiece." and `status` <> 'CANCELLED' and regionid = '".$regionid."' group by branchid)as thisyear on thisyear.branchid = inv_mas_branch.slno where region = '".$regionid."' order by inv_mas_branch.branchname;";

		$result = runmysqlquery($query);

		// Create Table to display brach wise Summary
		$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
		$branchgrid  .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td></tr>';
		
		$todaynewtotal = 0;
		$todaymonthtilldatetotal = 0 ;
		$todayyeartilldatetotal = 0 ;
		$slno = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$slno++;
			$tadaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
			$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
			$thisyearsale = ($fetch['thisyearsales'] == '')? '0' : $fetch['thisyearsales'];
			
			$todaynewtotal = $todaynewtotal + $tadaysale;
			$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
			$todayyeartilldatetotal = $todayyeartilldatetotal + $thisyearsale;
			
			$branchgrid .= '<tr>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tadaysale).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsale).'</td>';
			$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsale).'</td>';
			$branchgrid .= '</tr>';
			
		}
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayyeartilldatetotal).'</td>';
		$branchgrid .= '</tr>';
		
		
		$branchgrid .= '</table>';
		
			
		// Create a table for services
		
		$servicegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Services)</strong></div>';	
		$servicegrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
		//Write the header Row of the table
		$servicegrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Service Name</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
		
		// Get Today's services 
		$servicesquerytoday = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where regionid = "'.$regionid.'" and '.$servicedatetoday.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		//Get Month's services
		$servicesquerymonth = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where regionid = "'.$regionid.'" and '.$servicedatethismonth.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		
		//Get Year's services
		$servicesqueryyear = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where regionid = "'.$regionid.'" and '.$servicedatethisyear.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
		$servicesresultmonth = runmysqlquery($servicesquerymonth);
		$servicesresulttoday = runmysqlquery($servicesquerytoday);
		$servicesresultyear = runmysqlquery($servicesqueryyear);
		
		$serviceamounttoday = 0; $serviceamountmonth = 0;$serviceamountyear=0;
		while($sevicesfetchyear = mysqli_fetch_array($servicesresultyear))
		{
			$sevicesfetchmonth = mysqli_fetch_array($servicesresultmonth);
			$sevicesfetchtoday =  mysqli_fetch_array($servicesresulttoday);
			$servicegrid .= '<tr>';
			$servicegrid .= '<td nowrap="nowrap"  align="left">'.$sevicesfetchmonth['servicename'].'</td>';
			$serviceamounttoday += $sevicesfetchtoday['amount'];
			$serviceamountmonth += $sevicesfetchmonth['amount'];
			$serviceamountyear += $sevicesfetchyear['amount'];
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchtoday['amount']).'</td>';
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchmonth['amount']).'</td>';
			$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchyear['amount']).'</td>';
			$servicegrid .= '</tr>';
		}
		$servicegrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamounttoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountmonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountyear).'</td></tr>';
		$servicegrid .= '</table>';


		$addlessgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Others)</strong></div>';
					$addlessgrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
					//Write the header Row of the table
					$addlessgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
					
					$addlessamount = 0;
		  // Get Today's services 
		  $addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesc where regionid = "'.$regionid.'" and '.$addlessdatetoday.' group by descname order by descname;';
		  
		  //Get Month's services
		  $addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesc  where regionid = "'.$regionid.'"  and '.$addlessdatethismonth.' group by descname;';
		  
		  //Get Year's services
		  $addlessqueryyear = 'select sum(descamount) as amount,descname from addlessdesc  where regionid = "'.$regionid.'"  and '.$addlessedatethisyear.' group by descname;';
		  
		  $addlessresultmonth = runmysqlquery($addlessquerymonth);
		  $addlessresulttoday = runmysqlquery($addlessquerytoday);
		  $addlessresultyear = runmysqlquery($addlessqueryyear);
		   
		  $addlessresultyearcount = mysqli_num_rows($addlessresultyear);
		  $differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;$addlessfetchyearadd  =0;$addlessfetchyearless=0;$differenceyear=0;
		  $differencevalue = 0;
		  while($addlessfetchyear = mysqli_fetch_array($addlessresultyear))
		  {
							  
				$addlessfetchtoday = mysqli_fetch_array($addlessresulttoday);
				$addlessfetchmonth = mysqli_fetch_array($addlessresultmonth);
			  if($addlessresultyearcount == 2)	
			  {	
				  if($differencevalue == 0)
				  {
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
					$addlessfetchyearadd = $addlessfetchyear['amount'];
				  }
				  else
				  {
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
					$addlessfetchyearless = $addlessfetchyear['amount'];
				  }
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
				  $addlessgrid .= '</tr>';
				  $differencevalue++;

			  }
			  else
			  {
				if($addlessfetchyear['descname'] == 'add')
				{
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
					$addlessfetchyearadd = $addlessfetchyear['amount'];
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap"  align="left">LESS</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '</tr>';
				}
				else if($addlessfetchyear['descname'] == 'less')
				{
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
					$addlessfetchyearless = $addlessfetchyear['amount'];
					
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap"  align="left">ADD</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
					$addlessgrid .= '</tr>';
				}
			  }
			  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
			  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
			  $differenceyear = $addlessfetchyearadd - $addlessfetchyearless;

		  }
		  $addlessgrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($differencetoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differencemonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differenceyear).'</td></tr>';
		  $addlessgrid .= '</table>';


		$fromname = "Relyon";
		$fromemail = "imax@relyon.co.in";
		require_once("../inc/RSLMAIL_MAIL.php");
		$msg = file_get_contents("../mailcontents/dayendsummary.htm");
		$textmsg = file_get_contents("../mailcontents/dayendsummary-email.txt");
		
		$subject = "Invoicing Summary for ".$date." [".$managedarea."]";
	

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
		$array[] = "##SALESDETAILS##%^%".$grid;
		$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
		$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
		$array[] = "##SERVICESSALES##%^%".$servicegrid;
		$array[] = "##ADDLESSGRID##%^%".$addlessgrid;
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
	$grid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Region</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td></tr>';
	
	
	$productwisegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Software)</strong></div>';
	$productwisegrid  .= '<table width="85%" cellspacing="0" border = "2" cellpadding="0" align = "center" style="font-family:calibri">';
	$productwisegrid .= '<tr style=" background-color:#b2cffe">';
	$productwisegrid .= '<td width="5%" rowspan="2">&nbsp;</td>';
	$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Day Sales</div></td>';
	$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Month to Date</div></td>';
	$productwisegrid .= '<td colspan="2" nowrap="nowrap" ><div align="center">Year to Date</div></td>';
	$productwisegrid .= '</tr>';
	$productwisegrid .= '<tr style=" background-color:#b2cffe">';
	$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center" >New</div></td>';
	$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
	$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
	$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
	$productwisegrid .= '<td width="15%" nowrap="nowrap" ><div align="center">New </div></td>';
	$productwisegrid .= '<td width="16%" nowrap="nowrap" ><div align="center">Updation</div></td>';
	$productwisegrid .= '</tr>';
	
	
	$slno = 0;
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0;
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
	$othersupdation = 0;
	$othersnew = 0;
	$thismonthothersnew = 0;
	$thismonthothersupdation = 0;
	$yeartilldate =0;
	$thisyeartdsnew = 0;
	$thisyearsppnew = 0;
	$thisyearstonew = 0;
	$thisyearsvhnew = 0;
	$thisyearsvinew = 0;
	$thisyearsacnew = 0;
	$thisyearothersnew = 0;
	$thisyeartdsupdation = 0;
	$thisyearsppupdation = 0;
	$thisyearstoupdation = 0;
	$thisyearsvhupdation = 0;
	$thisyearsviupdation = 0;
	$thisyearsacupdation = 0;
	$thisyearothersupdation = 0;
	$thisyearupdationtotal = 0;
	$thisyearnewtotal = 0;
	$todayyeartilldatetotal = 0;
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
		$result5 = runmysqlquery($query5);
		
		$query6 = "select ifnull(sum(inv_invoicenumbers.amount),'0') as netamount from inv_invoicenumbers where inv_invoicenumbers.regionid = '".$regionid."' and ".$yeardatepiece." and `status` <> 'CANCELLED'"; 
		$result6 = runmysqlquery($query6);
		
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
		{
			$monthtilldate = 0;
		}
		if(mysqli_num_rows($result6) > 0)
		{
			$fetch6 = runmysqlqueryfetch($query6);
			$yeartilldate = ($fetch6['netamount'] == '')?'0' : $fetch6['netamount'];
		}
		else
		{
			$yeartilldate = 0;
		}
		
		// Get Totals 
		
		$todaynewtotal = $todaynewtotal + $todaynew;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $monthtilldate;
		$todayyeartilldatetotal = $todayyeartilldatetotal + $yeartilldate;
		
		$grid .= '<tr>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$grid .= '<td nowrap="nowrap"  align="left">'.$managedarea.'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynew).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($monthtilldate).'</td>';
		$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($yeartilldate).'</td>';
		$grid .= '</tr>';
		
		// New Purchases of dealer based on product group and purchase type

		$query2005 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and  ".$todaysdatepiece." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ; ";
		$result2005 = runmysqlquery($query2005);
		while($fetch2005 = mysqli_fetch_array($result2005))
		{
			if($fetch2005['productgroup'] == 'TDS')
				$tdsnew += $fetch2005['amount'];
			else if($fetch2005['productgroup'] == 'SPP')
				$sppnew += $fetch2005['amount'];
			else if($fetch2005['productgroup'] == 'STO')
				$stonew += $fetch2005['amount'];
			else if($fetch2005['productgroup'] == 'SVH')
				$svhnew += $fetch2005['amount'];
			else if($fetch2005['productgroup'] == 'SVI')
				$svinew += $fetch2005['amount'];
			else if($fetch2005['productgroup'] == 'SAC')
				$sacnew += $fetch2005['amount'];
			else
				$othersnew += $fetch2005['amount'];
				
		}
		// Updations of dealer based on product group and purchase type
		
		$query2016 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and  ".$todaysdatepiece." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group;";
		$result2016 = runmysqlquery($query2016);
		while($fetch2016 = mysqli_fetch_array($result2016))
		{
			if($fetch2016['productgroup'] == 'TDS')
				$tdsupdation += $fetch2016['amount'];
			else if($fetch2016['productgroup'] == 'SPP')
				$sppupdation += $fetch2016['amount'];
			else if($fetch2016['productgroup'] == 'STO')
				$stoupdation += $fetch2016['amount'];
			else if($fetch2016['productgroup'] == 'SVH')
				$svhupdation += $fetch2016['amount'];
			else if($fetch2016['productgroup'] == 'SVI')
				$sviupdation += $fetch2016['amount'];
			else if($fetch2016['productgroup'] == 'SVI')
				$sacupdation += $fetch2016['amount'];
			else
				$othersupdation += $fetch2016['amount'];
				
		}
		// Details of Month to Date 
		// New Purchases of dealer based on product group and purchase type

		
		$query1003 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' and  ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
		$result1003 = runmysqlquery($query1003);
		while($fetch1003 = mysqli_fetch_array($result1003))
		{
			if($fetch1003['productgroup'] == 'TDS')
				$thismonthtdsnew += $fetch1003['amount'];
			else if($fetch1003['productgroup'] == 'SPP')
				$thismonthsppnew += $fetch1003['amount'];
			else if($fetch1003['productgroup'] == 'STO')
				$thismonthstonew += $fetch1003['amount'];
			else if($fetch1003['productgroup'] == 'SVH')
				$thismonthsvhnew += $fetch1003['amount'];
			else if($fetch1003['productgroup'] == 'SVI')
				$thismonthsvinew += $fetch1003['amount'];
			else if($fetch1003['productgroup'] == 'SAC')
				$thismonthsacnew += $fetch1003['amount'];
			else
				$thismonthothersnew += $fetch1003['amount'];
				
		}
		// Updation Purchases of dealer based on product group and purchase type
		/*$thismonthtdsupdation = 0;
		$thismonthsppupdation = 0;
		$thismonthstoupdation = 0;
		$thismonthsvhupdation = 0;
		$thismonthsviupdation = 0;
		$thismonthsacupdation = 0;*/
		$query1004 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' and  ".$monthsdatepiece1." group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
		$result1004 = runmysqlquery($query1004);
		while($fetch1004 = mysqli_fetch_array($result1004))
		{
			if($fetch1004['productgroup'] == 'TDS')
				$thismonthtdsupdation += $fetch1004['amount'];
			else if($fetch1004['productgroup'] == 'SPP')
				$thismonthsppupdation += $fetch1004['amount'];
			else if($fetch1004['productgroup'] == 'STO')
				$thismonthstoupdation += $fetch1004['amount'];
			else if($fetch1004['productgroup'] == 'SVH')
				$thismonthsvhupdation += $fetch1004['amount'];
			else if($fetch1004['productgroup'] == 'SVI')
				$thismonthsviupdation += $fetch1004['amount'];
			else if($fetch1004['productgroup'] == 'SAC')
				$thismonthsacupdation += $fetch1004['amount'];
			else
				$thismonthothersupdation += $fetch1004['amount'];
				
		}
		
		
		// Details of Year to Date 
		// New Purchases of dealer based on product group and purchase type

		
		$query1077 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'New' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
		$result1077 = runmysqlquery($query1077);
		while($fetch1077 = mysqli_fetch_array($result1077))
		{
			if($fetch1077['productgroup'] == 'TDS')
				$thisyeartdsnew += $fetch1077['amount'];
			else if($fetch1077['productgroup'] == 'SPP')
				$thisyearsppnew += $fetch1077['amount'];
			else if($fetch1077['productgroup'] == 'STO')
				$thisyearstonew += $fetch1077['amount'];
			else if($fetch1077['productgroup'] == 'SVH')
				$thisyearsvhnew += $fetch1077['amount'];
			else if($fetch1077['productgroup'] == 'SVI')
				$thisyearsvinew += $fetch1077['amount'];
			else if($fetch1077['productgroup'] == 'SAC')
				$thisyearsacnew += $fetch1077['amount'];
			else
				$thisyearothersnew += $fetch1077['amount'];
				
		}
		
		$query1088 = "select distinct inv_mas_product.group as productgroup, ifnull(invoicedetails.amount,'0') as amount from inv_mas_product left join (select ifnull(sum(amount),'0') as amount, productgroup from invoicedetails where regionid = '".$regionid."' and purchasetype = 'Updation' group by productgroup)as invoicedetails on invoicedetails.productgroup = inv_mas_product.group ;";
		$result1088 = runmysqlquery($query1088);
		while($fetch1088 = mysqli_fetch_array($result1088))
		{
			if($fetch1088['productgroup'] == 'TDS')
				$thisyeartdsupdation += $fetch1088['amount'];
			else if($fetch1088['productgroup'] == 'SPP')
				$thisyearsppupdation += $fetch1088['amount'];
			else if($fetch1088['productgroup'] == 'STO')
				$thisyearstoupdation += $fetch1088['amount'];
			else if($fetch1088['productgroup'] == 'SVH')
				$thisyearsvhupdation += $fetch1088['amount'];
			else if($fetch1088['productgroup'] == 'SVI')
				$thisyearsviupdation += $fetch1088['amount'];
			else if($fetch1088['productgroup'] == 'SAC')
				$thisyearsacupdation += $fetch1088['amount'];
			else
				$thisyearothersupdation += $fetch1088['amount'];
				
		}
	}
	$grid .= '<tr>';
	$grid .= '<td nowrap="nowrap"  align="center">&nbsp;</td>';
	$grid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
	$grid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayyeartilldatetotal).'</td>';
	$grid .= '</tr>';
	$grid .= '</table>';
	
	// Calculate totals
	$todaynewtotal = $tdsnew + $sppnew + $stonew + $svhnew + $svinew + $sacnew +$othersnew;
	$todayupdationtotal = $tdsupdation + $sppupdation+ $stoupdation + $svhupdation + $sviupdation + $sacupdation +$othersupdation ;
	$thismonthnewtotal = $thismonthtdsnew + $thismonthsppnew + $thismonthstonew + $thismonthsvhnew + $thismonthsvinew + $thismonthsacnew+ $thismonthothersnew;
	$thismonthupdationtotal = $thismonthtdsupdation + $thismonthsppupdation + $thismonthstoupdation + $thismonthsvhupdation + $thismonthsviupdation + $thismonthsacupdation + $thismonthothersupdation;
	$thisyearnewtotal = $thisyeartdsnew + $thisyearsppnew + $thisyearstonew + $thisyearsvhnew + $thisyearsvinew + $thisyearsacnew+ $thisyearothersnew;
$thisyearupdationtotal = $thisyeartdsupdation + $thisyearsppupdation + $thisyearstoupdation + $thisyearsvhupdation + $thisyearsviupdation + $thisyearsacupdation + $thisyearothersupdation;

	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">TDS</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tdsupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthtdsupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyeartdsupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SPP</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sppupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsppupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsppupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">STO</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stonew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($stoupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstonew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthstoupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstonew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearstoupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SVH</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svhupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvhupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvhupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SVI</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($svinew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sviupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsvinew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsviupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsvinew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsviupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">SAC</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sacupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsacupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsacupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center">OTHERS</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($othersupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthothersupdation).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersnew).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearothersupdation).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap"  align="center"><strong>Total</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayupdationtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthnewtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthupdationtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearnewtotal).'</td>';
	$productwisegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearupdationtotal).'</td>';
	$productwisegrid .= '</tr>';
	
	$productwisegrid .= '</table>';

	// Branch wise sales summary for Management
	
	// Fetch today's data
	$query = "select inv_mas_branch.branchname as branchname,today.netamount as todaysales,thismonth.netamount as thismonthsales,thisyear.netamount as thisyearsales  from inv_mas_branch left join (select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$todaysdatepiece1." and `status` <> 'CANCELLED' group by  branchid) as today on today.branchid = inv_mas_branch.slno left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$monthsdatepiece." and `status` <> 'CANCELLED'  group by  branchid)as thismonth on thismonth.branchid = inv_mas_branch.slno left join(select branchid,branch,sum(amount) as netamount from inv_invoicenumbers where ".$yeardatepiece." and `status` <> 'CANCELLED'  group by branchid)as thisyear on thisyear.branchid = inv_mas_branch.slno order by inv_mas_branch.branchname;";
	$result = runmysqlquery($query);
	
	// Create Table to display brach wise Summary
	$branchgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Branch Wise Summary</strong></div>';
	$branchgrid .= '<table width="85%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$branchgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap"  align="center">Sl No</td><td nowrap="nowrap"  align="center">Branch</td><td nowrap="nowrap"  align="center">Day Sales</td><td nowrap="nowrap"  align="center">Month to Date</td><td nowrap="nowrap"  align="center">Year to Date</td></tr>';
	
	$todaynewtotal = 0;
	$todaymonthtilldatetotal = 0 ;$thisyearsale=0;$todayyeartilldatetotal=0;
	$slno = 0;
	while($fetch = mysqli_fetch_array($result))
	{
		$slno++;
		$tadaysale = ($fetch['todaysales'] == '')? '0' : $fetch['todaysales'];
		$thismonthsale = ($fetch['thismonthsales'] == '')? '0' : $fetch['thismonthsales'];
		$thisyearsale = ($fetch['thisyearsales'] == '')? '0' : $fetch['thisyearsales'];
		
		$todaynewtotal = $todaynewtotal + $tadaysale;
		$todaymonthtilldatetotal = $todaymonthtilldatetotal + $thismonthsale;
		$todayyeartilldatetotal = $todayyeartilldatetotal + $thisyearsale;
		
		$branchgrid .= '<tr>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$slno.'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="left">'.$fetch['branchname'].'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($tadaysale).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thismonthsale).'</td>';
		$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($thisyearsale).'</td>';
		$branchgrid .= '</tr>';
		
	}
	$branchgrid .= '<tr>';
	$branchgrid .= '<td nowrap="nowrap"  align="left">&nbsp;</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="left"><strong>Total</strong></td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaynewtotal).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todaymonthtilldatetotal).'</td>';
	$branchgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($todayyeartilldatetotal).'</td>';
	$branchgrid .= '</tr>';
	
	$branchgrid .= '</table>';
	
		
	// Create a table for services
		
	$servicegrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Services)</strong></div>';	
	$servicegrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$servicegrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Service Name</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
	
	// Get Today's services 
	$servicesquerytoday = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where  '.$servicedatetoday.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
	//Get Month's services
	$servicesquerymonth = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where '.$servicedatethismonth.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
	//Get Year's services
	$servicesqueryyear = 'select ifnull(services.netamount,"0") as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),"0") as netamount,servicename from services where '.$servicedatethisyear.' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename;';
	
	$serviceamounttoday = 0;$serviceamountmonth =0;$serviceamountyear=0;
	$servicesresulttoday = runmysqlquery($servicesquerytoday);
	$servicesresultmonth = runmysqlquery($servicesquerymonth);
	$servicesresultyear = runmysqlquery($servicesqueryyear);
	while($sevicesfetchyear = mysqli_fetch_array($servicesresultyear))
	{
		$sevicesfetchmonth = mysqli_fetch_array($servicesresultmonth);
		$sevicesfetchtoday =  mysqli_fetch_array($servicesresulttoday);
		$servicegrid .= '<tr>';
		$servicegrid .= '<td nowrap="nowrap"  align="left">'.$sevicesfetchyear['servicename'].'</td>';
		$serviceamounttoday += $sevicesfetchtoday['amount'];
		$serviceamountmonth += $sevicesfetchmonth['amount'];
		$serviceamountyear += $sevicesfetchyear['amount'];
		$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchtoday['amount']).'</td>';
		$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchmonth['amount']).'</td>';
		$servicegrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($sevicesfetchyear['amount']).'</td>';
		$servicegrid .= '</tr>';
	}
	$servicegrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamounttoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountmonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($serviceamountyear).'</td></tr>';
	$servicegrid .= '</table>';
	
	// Generate grid for Add/Less 
	
	$addlessgrid = '<div align = "center" style="font-family:calibri;font-size:14px"><strong>Items (Others)</strong></div>';
	$addlessgrid .= '<table width="65%"  cellspacing="0" cellpadding="2" border = "2" align = "center" style="font-family:calibri">';
	//Write the header Row of the table
	$addlessgrid .= '<tr style=" background-color:#b2cffe"><td nowrap="nowrap" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap"  align="center" width = "27%">Month to Date</td><td nowrap="nowrap"  align="center" width = "27%">Year to Date</td></tr>';
	
	$addlessamount = 0;
	
	// Get Today's services 
	$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesc where  '.$addlessdatetoday.' group by descname order by descname;';
	
	//Get Month's services
	$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesc  where  '.$addlessdatethismonth.' group by descname;';
	
	//Get Year's services
	$addlessqueryyear = 'select sum(descamount) as amount,descname from addlessdesc  where  '.$addlessedatethisyear.' group by descname;';
	
	$addlessresultmonth = runmysqlquery($addlessquerymonth);
	$addlessresulttoday = runmysqlquery($addlessquerytoday);
	$addlessresultyear = runmysqlquery($addlessqueryyear);
	$addlessresultyearcount = mysqli_num_rows($addlessresultyear);
	$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
	$differencevalue = 0;$differenceyear=0; $addlessfetchyearless=0;$addlessfetchyearadd=0;
	while($addlessfetchyear  = mysqli_fetch_array($addlessresultyear))
	{
		$addlessfetchtoday = mysqli_fetch_array($addlessresulttoday);
		$addlessfetchmonth = mysqli_fetch_array($addlessresultmonth);
		if($addlessresultyearcount == 2)	
		{	
			if($differencevalue == 0)
			{
			  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
			  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
			  $addlessfetchyearadd = $addlessfetchyear['amount'];
			}
			else
			{
			  $addlessfetchtodayless = $addlessfetchtoday['amount'];
			  $addlessfetchmonthless = $addlessfetchmonth['amount'];
			  $addlessfetchyearless = $addlessfetchyear['amount'];
			}
			$addlessgrid .= '<tr>';
			$addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
			$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			$addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
			$addlessgrid .= '</tr>';
			$differencevalue++;
		}
		else
		{
		  if($addlessfetchyear['descname'] == 'add')
		  {
			  $addlessfetchtodayadd = $addlessfetchtoday['amount'];
			  $addlessfetchmonthadd = $addlessfetchmonth['amount'];
			  $addlessfetchyearadd = $addlessfetchyear['amount'];
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
			  $addlessgrid .= '</tr>';
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="left">LESS</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '</tr>';
		  }
		  else if($addlessfetchyear['descname'] == 'less')
		  {
			  $addlessfetchtodayless = $addlessfetchtoday['amount'];
			  $addlessfetchmonthless = $addlessfetchmonth['amount'];
			  $addlessfetchyearless = $addlessfetchyear['amount'];
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="left">ADD</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">0</td>';
			  $addlessgrid .= '</tr>';
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="left">'.strtoupper($addlessfetchyear['descname']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			   $addlessgrid .= '<td nowrap="nowrap"  align="right">'.formatnumber($addlessfetchyear['amount']).'</td>';
			  $addlessgrid .= '</tr>';
		  }
		}
		$differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
		$differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
		$differenceyear = $addlessfetchyearadd - $addlessfetchyearless;
	}
	$addlessgrid .= '<tr><td nowrap="nowrap"  align="left"><strong>Total: </strong></td><td nowrap="nowrap"  align="right">'.formatnumber($differencetoday).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differencemonth).'</td><td nowrap="nowrap"  align="right">'.formatnumber($differenceyear).'</td></tr>';
	$addlessgrid .= '</table>';
	

	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	
	$msg = file_get_contents("../mailcontents/dayendsummaryaccounts.htm");
	$textmsg = file_get_contents("../mailcontents/dayendsummaryaccounts.txt");
	
	$subject = "Invoicing Summary for ".$date." [Management] ";
	//Create an array of replace parameters
	$array = array();
	//$date = datetimelocal('d-m-Y');
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".' Management';
	$array[] = "##EMAILID##%^%".'accounts@relyonsoft.com, nitinall@relyonsoft.com, hsn@relyonsoft.com';
	$array[] = "##SALESDETAILS##%^%".$grid;
	$array[] = "##PRODUCTWISESALES##%^%".$productwisegrid;
	$array[] = "##BRANCHWISEDETAILS##%^%".$branchgrid;
	$array[] = "##SERVICESSALES##%^%".$servicegrid;
	$array[] = "##ADDLESSGRID##%^%".$addlessgrid;
	$array[] = "##SUBJECT##%^%".$subject;
	//$emailarray = explode(',',$emailid);
	//$emailcount = count($emailid);
	if($_SERVER['HTTP_HOST'] == '192.168.2.132')  
	{
		//$emailid = array('webmaster@relyonsoft.com');
	}
	else
	{
		$emailid = array('accounts@relyonsoft.com','nitinall@relyonsoft.com','hsn@relyonsoft.com');
		//$emailid = array('rashmi.hk@relyonsoft.com');
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