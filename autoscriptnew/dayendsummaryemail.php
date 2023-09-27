<?

ini_set('memory_limit', '-1');
set_time_limit(0);
include("../functions/phpfunctions.php");


// Create Temporary Table to insert Invoice details

	$starttime = date('H:m:s');
	$query = "CREATE TEMPORARY TABLE `invoicedetails` (                                       
                  `slno` int(10) NOT NULL auto_increment,                             
                  `invoiceno` int(10) default NULL,                                   
                  `productcode` varchar(10) collate latin1_general_ci default NULL,   
                  `usagetype` varchar(10) collate latin1_general_ci default NULL,     
                  `amount` varchar(25) collate latin1_general_ci default NULL,        
                  `purchasetype` varchar(25) collate latin1_general_ci default NULL,  
                  PRIMARY KEY  (`slno`)                                               
                );";
	$result = runmysqlquery($query);

	
	// Fetch the invoices generated in this month.
	
	$query2 = "select * from inv_invoicenumbers where  left(inv_invoicenumbers.createddate,10) between '2011-02-01' and curdate() and products <> '' and `status` <> 'CANCELLED'";
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
						
			// Insert into invoice details table
			
			$query3 = "insert into invoicedetailstest(invoiceno,productcode,usagetype,amount,purchasetype,dealerid) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch2['dealerid']."')";
			
			$result3 =  runmysqlquery($query3);
		}
	}
	
	//$rowcount = "select count(*) as total from invoicedetails";
	//$fetchcnt = runmysqlqueryfetch($rowcount);
	
	$endtime = date('H:m:s');
	echo('Start Time :'.$starttime.'End Time:'.$endtime);
	exit;
	


?>