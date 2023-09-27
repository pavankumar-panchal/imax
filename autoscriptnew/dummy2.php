<?
include('../functions/phpfunctions.php');


		// Create Temporary tables 
		
		$querydrop = "Drop table if exists invoicedetailssearch;";
		$result = runmysqlquery($querydrop);

		$querydrop1 = "Drop table if exists servicessearch;";
		$result1 = runmysqlquery($querydrop1);
	
	
		// Create Temporary table to insert 'ITEM SOFTWARE' details
		$queryservices = "CREATE TEMPORARY TABLE `servicessearch` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `invoiceno` int(10) default NULL,      
		 `servicename` varchar(100) collate latin1_general_ci default NULL, 
		 `serviceamount` varchar(10) collate latin1_general_ci default NULL, 
		 `createddate` datetime default '0000-00-00 00:00:00',
		`dealerid` varchar(25) collate latin1_general_ci default NULL, 
		`regionid` varchar(25) collate latin1_general_ci default NULL,   
		`branch` varchar(25) collate latin1_general_ci default NULL,  
		`branchname` varchar(25) collate latin1_general_ci default NULL, 
		`category` varchar(25) collate latin1_general_ci default NULL,   
		 PRIMARY KEY  (`slno`)    
	 );";
		$result0 = runmysqlquery($queryservices);

		
		$query = "CREATE TEMPORARY TABLE `invoicedetailssearch` (                                       
				  `slno` int(10) NOT NULL auto_increment,                             
				  `invoiceno` int(10) default NULL,                                   
				  `productcode` varchar(10) collate latin1_general_ci default NULL,   
				  `usagetype` varchar(50) collate latin1_general_ci default NULL,     
				  `amount` varchar(25) collate latin1_general_ci default NULL,        
				  `purchasetype` varchar(25) collate latin1_general_ci default NULL,
				  `dealerid` varchar(25) collate latin1_general_ci default NULL, 
				  `invoicedate` datetime default '0000-00-00 00:00:00',
				  `productgroup` varchar(25) collate latin1_general_ci default NULL, 
				  `regionid` varchar(25) collate latin1_general_ci default NULL,   
				  `branch` varchar(25) collate latin1_general_ci default NULL,  
				  `branchname` varchar(25) collate latin1_general_ci default NULL,  
				  `category` varchar(25) collate latin1_general_ci default NULL,
				  `scratchnumber` varchar(25) collate latin1_general_ci default NULL,   
				  `cardid` varchar(25) collate latin1_general_ci default NULL,      
				   PRIMARY KEY  (`slno`)                                               
				);";
		$result1 = runmysqlquery($query);	


		$query = "select * from inv_invoicenumbers where left(createddate,10) between '2011-02-01' and curdate() and status <> 'CANCELLED' ";
		$starttime = date('H:m:s');
		$result0 = runmysqlquery($query);
		// For all Search Result 
		while($fetch0 = mysql_fetch_array($result0))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice
			
			//$query2 = "select * from inv_invoicenumbers where slno = '".$fetch0['slno']."'";
			//$fetch0 = runmysqlqueryfetch($query2); //echo($query2);exit;
			// Insert data to services table
			$serviceamount = 0;
			if($fetch0['servicedescription'] <> '')
			{
				$serviceamountsplit = explode('*',$fetch0['servicedescription']);
				for($k = 0 ;$k < count($serviceamountsplit);$k++)
				{
					$finalsplit = explode('$',$serviceamountsplit[$k]); //echo($offerdescriptionsplit[$j]);exit;
					$serviceamount = $serviceamount + $finalsplit[2];
					// Insert into services table 
					$insertservices = "INSERT INTO servicessearch(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category) values('".$fetch0['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch0['createddate']."','".$fetch0['dealerid']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."')";
					$result = runmysqlquery($insertservices);
				}
			}
			// Insert data to invoice detals table 
			
			if($fetch0['products'] <> '')
			{
				$count++;
				$totalamount = 0;
				$products = explode('#',$fetch0['products']);
				for($i = 0 ; $i < count($products);$i++)
				{
					$totalamount = 0;
					$description = explode('*',$fetch0['description']);
					$splitdescription = explode('$',$description[$i]);
					
					$productcode = $products[$i];
					$usagetype = $splitdescription[3];
					$scratchnumber = $splitdescription[4];
					$cardid = $splitdescription[5];
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
					
					$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
					$result1 = runmysqlqueryfetch($query1);
					
					// Insert into invoice details table
					
					$query3 = "insert into invoicedetailssearch(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid) values('".$fetch0['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch0['dealerid']."','".$fetch0['createddate']."','".$result1['productgroup']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."','".$scratchnumber."','".$cardid."')"; 
					$result3 =  runmysqlquery($query3);
				}
			}	
		}
		$endtime = date('H:m:s');
		echo($starttime.'^^^'.$endtime);exit;

?>