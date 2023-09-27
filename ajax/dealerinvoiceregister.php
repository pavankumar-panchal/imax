<?
//ini_set("display_errors",0);
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

	$todaysdatepiece = "left(invoicedetailstoday.invoicedate,10) = curdate()";
	$servicedatetoday = "left(servicestoday.createddate,10) = curdate()";
	// Get all invoices of today into a temporary table.  
			
	$querydrop = "Drop table if exists invoicedetailstoday;";
	$result = runmysqlquery($querydrop);

	$querydrop1 = "Drop table if exists servicestoday;";
	$result1 = runmysqlquery($querydrop1);
	
	$querydrop2 = "Drop table if exists addlessdesctoday;";
	$result2 = runmysqlquery($querydrop2);
		
	// Create Temporary table to insert 'ITEM SOFTWARE' details
	$queryservices = "CREATE TEMPORARY TABLE `servicestoday` ( 
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
	$result = runmysqlquery($queryservices);

	$query2 = "select * from inv_dealer_invoicenumbers where left(inv_dealer_invoicenumbers.createddate,10) = curdate()  and `status` <> 'CANCELLED'";
	$result2 = runmysqlquery($query2);
	$count = 0;
	$totalamount1 = 0;
	
	// Insert data to services table
	while($fetch2 = mysqli_fetch_array($result2))
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
				$insertservices = "INSERT INTO servicestoday(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category) values('".$fetch2['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch2['createddate']."','".$fetch2['dealerid']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
				$result = runmysqlquery($insertservices);
			}
		}
	} 

	// Create Temporary Table to insert Invoice details

	$query = "CREATE TEMPORARY TABLE `invoicedetailstoday` (                                       
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
					`category` varchar(25) collate latin1_general_ci default NULL,   
				  PRIMARY KEY  (`slno`)                                               
				);";
	$result = runmysqlquery($query);
	
	
	// Create Temporary Table to insert Invoice details
	$queryaddless = "CREATE TEMPORARY TABLE `addlessdesctoday` ( 
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
	
	
	// Insert data to invoicedetails table
	$query0 = "select * from inv_dealer_invoicenumbers where left(inv_dealer_invoicenumbers.createddate,10) = curdate() and products <> '' and `status` <> 'CANCELLED'";
	$result0 = runmysqlquery($query0);
	
	while($fetch2 = mysqli_fetch_array($result0))
	{
		$count++;
		$products = explode('#',$fetch2['products']);
		$description = explode('*',$fetch2['description']);

		$productquantity = explode(',',$fetch2['productquantity']);
		$k=0;
		for($i = 0 ; $i < count($description);$i++)
		{
			for($j = 0 ; $j < $productquantity[$i];$j++)
			{
			  $totalamount = 0;
			  $splitdescription = explode('$',$description[$k]);
			  $productcode = $products[$i];
			  $usagetype = $splitdescription[3];
			  $amount = $splitdescription[6];
			  $purchasetype = $splitdescription[2];   
			  $totalamount1 = $amount ;
			  $k++;	  
			  // Fetch Product 	
			  $query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
			  $result1 = runmysqlqueryfetch($query1);
			  
			  // Insert into invoice details table
			  $query3 = "insert into invoicedetailstoday(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount1."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
			  $result3 =  runmysqlquery($query3);
			}
		}
	}	
	
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'invoicedetails':
	{	
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0; 
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$servicestotaltoday = 0; 
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0;
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select count(distinct inv_dealer_invoicenumbers.slno) as count from inv_dealer_invoicenumbers  
		where  left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.slno";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_dealer_invoicenumbers.slno,sum(inv_dealer_invoicenumbers.amount) as amount,
		sum(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,sum(inv_dealer_invoicenumbers.netamount) as netamount
		from inv_dealer_invoicenumbers 
		where left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_dealer_invoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
		$fetchresult1 =runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalsalevalue = $fetchresult['amount'];
			$totaltax = $fetchresult['servicetax'];
			$totalamount = $fetchresult['netamount'];
		}
		else
		{
			$totalinvoices = '0';
			$totalsalevalue = '0';
			$totaltax = '0';
			$totalamount = '0';
		}
		
		if($showtype == 'all')
		$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		$query = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,
		inv_dealer_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount,
		(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,inv_dealer_invoicenumbers.products as products,
		inv_dealer_invoicenumbers.servicedescription as servicedescription,inv_dealer_invoicenumbers.productquantity from inv_dealer_invoicenumbers 
		where  left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; //echo($query);exit;
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount = count($productquantity);
			for($i=0; $i< $productcount; $i++)
			{
				$quantity += (int)$productquantity[$i];
			}
			
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
				$totalcount = $productsplitcount;
			if($i_n%2 == 0)
			
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
			$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['invoiceno'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($quantity)."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['amount'])."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
			
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		
		//Display product wise details in a table
		
		//Table headers
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
		//Fetch Group by Product Amount on Type NEW
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{
			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'GST')
				$gstnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'GST')
				$gstupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
				
		}
		$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
		$spptotal = $sppnewpurchase + $sppupdationpurchase;
		$stototal = $stonewpurchase + $stoupdationpurchase;
		$svhtotal = $svhnewpurchase + $svhupdationpurchase;
		$svitotal = $svinewpurchase + $sviupdationpurchase;
		$sactotal = $sacnewpurchase + $sacupdationpurchase;
		$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
		$gsttotal = $gstnewpurchase + $gstupdationpurchase;
		$otherstotal = $othersnewpurchase + $othersupdationpurchase;
		
		
		$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal+ $xbrltotal + $gsttotal;
		
			$productwisegrid .= '<tr bgcolor="#F7FAFF">';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr  bgcolor="#edf4ff">';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
			$productwisegrid .= '</tr>';

			$productwisegrid .= '<tr>';
		    $productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
		    $productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
		    $productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
		    $productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
		    $productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
			$productwisegrid .= '</tr>';

		// Calculate totals
		$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $xbrlnewpurchase + $gstnewpurchase;;
		$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase+ $xbrlupdationpurchase + $gstupdationpurchase;;
		$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal+ $xbrltotal + $gsttotal;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		// Prepare Services Summary 
		
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestoday where  ".$servicedatetoday." group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
		$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
	//Write the header Row of the table
	$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
	
	$addlessamount = 0;
	
	// Get Today's services 
	$addlessquerytoday = 'select  ifnull(sum(descamount),"0") as amount,descname from addlessdesctoday group by descname order by descname;';
	
	//Get Month's services
	$addlessquerymonth = 'select ifnull(sum(descamount),"0") as amount,descname from addlessdesctoday  group by descname;';
	
	$addlessresultmonth = runmysqlquery($addlessquerymonth);
	$addlessresulttoday = runmysqlquery($addlessquerytoday);
	$addlessresultmonthcount = mysqli_num_rows($addlessresultmonth);
	$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
	$differencevalue = 0;
	$addlessgridcount = mysqli_num_rows($addlessresultmonth);
	if($addlessgridcount > 0)
	{
	  while($addlessfetchmonth = mysqli_fetch_array($addlessresultmonth))
	  {
						  
		  if($addlessresultmonthcount == 2)	
		  {	
			  if($differencevalue == 0)
			  {
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
			  }
			  else
			  {
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
			  }
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			  $addlessgrid .= '</tr>';
			  $differencevalue++;
  
		  }
		  else
		  {
			if($addlessfetchmonth['descname'] == 'add')
			{
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td>';
				$addlessgrid .= '</tr>';
			}
			else if($addlessfetchmonth['descname'] == 'less')
			{
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
			}
		  }
		  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
		  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
  
	  }
	}
	else
	{
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
	}
	$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total: </strong></td><td  nowrap="nowrap" class="td-border-grid"   align="right"><strong>'.formatnumber($differencetoday).'</strong></td><td  nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($differencemonth).'</strong></td></tr>';
	$addlessgrid .= '</table>';
	
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;	
	}
	break;
	case 'searchinvoices':
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0;
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0;
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		
		$totalinvoices = '0';
		$totalsalevalue = '0';
		$totaltax = '0';
		$totalamount = '0';
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer'];
		$branch = $_POST['branch'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		$district = $_POST['district'];
		$productslist = $_POST['productscode'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$status = $_POST['status'];
		$series = $_POST['series'];
		
		$seriesnew = $_POST['seriesnew'];
		
		$usagetype = $_POST['usagetype'];
		$purchasetype = $_POST['purchasetype'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$receiptstatus = $_POST['receiptstatus'];
		$itemlist = $_POST['itemlist'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		$alltimecheck = $_POST['alltimecheck'];
		
		
		$querydrop = "Drop table if exists productgroups;";
		$result = runmysqlquery($querydrop);
		// Create a temporary table which holds product group data
		
		$queryproducts = "CREATE  TABLE `productgroups` ( 
		`slno` int(10) NOT NULL auto_increment, 
		 `productcode` int(10) default NULL,      
		 `productgroup` varchar(100) collate latin1_general_ci default NULL, 
		 PRIMARY KEY  (`slno`)    
	 );";
		$result0 = runmysqlquery($queryproducts);
		
		
		if($usagetype == 'addlic')
		{
			$usagetypevalue = 'singleuser';
			$addlicence = "AND inv_dealercard.addlicence = 'yes'";
		}
		elseif($usagetype == 'singleuser')
		{
			$usagetypevalue = 'singleuser';
			$addlicence = '';
		}elseif($usagetype == 'multiuser')
		{
			$usagetypevalue = 'multiuser';
			$addlicence = '';
		}
		$starttime = date('H:m:s');
		if($productslist != '')
		{
			for($i = 0;$i< $productlistsplitcount; $i++)
			{
				if($i < ($productlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				// Get the product group of each product 
				
				$querygetproductgroup = "select * from inv_mas_product where productcode = '".$productlistsplit[$i]."'";
				$resultproductgroup = runmysqlqueryfetch($querygetproductgroup);
				 
				//echo($querygetproductgroup);exit;
				// Insert to temporary table 
				
				$queryinsertproductgroup = "insert into `productgroups`(productcode,productgroup) values('".$resultproductgroup['productcode']."','".$resultproductgroup['group']."')";
				$resultofinsert = runmysqlquery($queryinsertproductgroup);
				
				$finalproductlist .= ' inv_dealer_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
			}
		}
		$endtime = date('H:m:s');
		//echo($starttime.'^^^^'.$endtime);
		
		if($itemlist != '')
		{
			for($j = 0;$j< $itemlistsplitcount; $j++)
			{
				if($j < ($itemlistsplitcount-1))
					$appendor1 = 'or'.' ';
				else
					$appendor1 = '';
					
				$finalitemlist .= ' inv_dealer_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
			}
			
		}
		
		if(($itemlist != '') && ($productslist != ''))
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
		elseif($productslist == '')
			$finallistarray = ' AND ('.$finalitemlist.')';
		elseif($itemlist == '')
			$finallistarray = ' AND ('.$finalproductlist.')';
		$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		$regionpiece = ($region == "")?(""):(" AND inv_dealer_invoicenumbers.regionid = '".$region."' ");
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_dealer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_dealer_invoicenumbers.dealerreference = '".$dealer."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_dealer_invoicenumbers.branchid = '".$branch."' ");
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_dealer_invoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_dealer_invoicenumbers.module = '".$modulepiece."'");
		
		$seriespiece = ($series == "")?(""):(" AND inv_dealer_invoicenumbers.category = '".$series."' ");
		
		$seriespiecenew = ($seriesnew == "")?(""):(" AND inv_dealer_invoicenumbers.state_info = '".$seriesnew."' ");
		
		$usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetypevalue."' ".$addlicence."  ");
		$purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
		$cancelledpiece = ($cancelledinvoice == "yes")?("AND inv_dealer_invoicenumbers.status <> 'CANCELLED'"):("");
		$statuspiece = ($status == "")?(""):(" AND inv_dealer_invoicenumbers.status = '".$status."'");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");
		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (left(inv_dealer_invoicenumbers.createddate,10) between '".$fromdate."' AND '".$todate."') ");	
		
		// Create Temporary tables 
		
		$querydrop = "Drop table if exists invoicedetailssearch;";
		$result = runmysqlquery($querydrop);

		$querydrop1 = "Drop table if exists servicessearch;";
		$result1 = runmysqlquery($querydrop1);
		
		$querydrop2 = "Drop table if exists addlessdescsearch;";
		$result2 = runmysqlquery($querydrop2);
	
		// Create Temporary table to insert 'ITEM SOFTWARE' details
		$queryservices = "CREATE   TABLE `servicessearch` ( 
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
		`state_info` varchar(25) collate latin1_general_ci default NULL,  
		 PRIMARY KEY  (`slno`)    
	 );";
		$result0 = runmysqlquery($queryservices);

		
		$query = "CREATE  TABLE `invoicedetailssearch` (                                       
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
				  `state_info` varchar(25) collate latin1_general_ci default NULL, 
				   PRIMARY KEY  (`slno`)                                               
				);";
		$result1 = runmysqlquery($query);	
	

	// Create Temporary Table to insert Invoice details
	$queryaddless = "CREATE  TABLE `addlessdescsearch` ( 
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
	

		
		if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}

		$querycase = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,
		inv_dealer_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.cusbillnumber,inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount,
		(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,
		inv_dealer_invoicenumbers.products as products,inv_dealer_invoicenumbers.description,inv_dealer_invoicenumbers.servicedescription,
		inv_dealer_invoicenumbers.branch,inv_dealer_invoicenumbers.branchid,inv_dealer_invoicenumbers.category,inv_dealer_invoicenumbers.dealerid,
		inv_dealer_invoicenumbers.regionid,inv_dealer_invoicenumbers.productquantity
		from inv_dealer_invoicenumbers
		left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealer_invoicenumbers.dealerreference
		left join inv_mas_district on inv_mas_district.districtcode = inv_mas_dealer.district
		left join inv_mas_receipt on inv_mas_receipt.dealerinvoiceno = inv_dealer_invoicenumbers.slno
		left join inv_dealercard on inv_dealercard.invoiceid = inv_dealer_invoicenumbers.slno";
		
		switch($databasefield)
		{
			case "contactperson": 
				$query = $querycase." where inv_dealer_invoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece." order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
			case "phone":
				$query = $querycase." where inv_dealer_invoicenumbers.phone LIKE '%".$textfield."%' OR inv_dealer_invoicenumbers.cell LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc ";
				break;
			case "place":
				$query = $querycase." where inv_dealer_invoicenumbers.place LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
			case "emailid":
				$query = $querycase." where inv_dealer_invoicenumbers.emailid LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece." order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
				case "cardid":
					$query = $querycase." where inv_mas_scratchcard.cardid LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
					break;
			case "scratchnumber":
				$query = $querycase." where inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiecenew.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
			
			case "invoiceno":
				$query = $querycase." where inv_dealer_invoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
			case "invoiceamt":
				$query = $querycase." where inv_dealer_invoicenumbers.netamount LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_dealer_invoicenumbers.createddate,inv_dealer_invoicenumbers.slno desc";
				break;
			
			default:
				$query = $querycase." where inv_dealer_invoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  ORDER BY createddate,inv_dealer_invoicenumbers.slno desc";
				break;
		} 
		//	echo($query); exit;	

		$totalresult = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','267','".date('Y-m-d').' '.date('H:i:s')."','view_dealerinvoiceregister')";
		$eventresult = runmysqlquery($eventquery);
		$fetchresultcount1 = mysqli_num_rows($totalresult);
		$totalinvoices = $fetchresultcount1; 
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result2 = runmysqlquery($query1);
		$grid = '';
		$starttime = date('H:m:s');
		//$result0 = runmysqlquery($query);
		// For all Search Result 
		while($fetch0 = mysqli_fetch_array($totalresult))
		{
			// Now insert selected invoice details to temporary table condidering all details of the each invoice

			$totalsalevalue += $fetch0['amount'];
			$totaltax += $fetch0['servicetax'];
			$totalamountall += $fetch0['netamount'];
			
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
					$insertservices = "INSERT INTO servicessearch(invoiceno,servicename,serviceamount,createddate,dealerid,regionid,branch,branchname,category,state_info) values('".$fetch0['slno']."','". $finalsplit[1]."','". $finalsplit[2]."','".$fetch0['createddate']."','".$fetch0['dealerid']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."','".$fetch0['state_info']."')";
					$result = runmysqlquery($insertservices);
				}
			}
			
				//Insert to add/less description table
	
			// if($fetch0['offerdescription'] <> '')
			// {
			// 	$offeramount = 0;
			// 	$addlesssplit = explode('*',$fetch0['offerdescription']);
			// 	for($k = 0 ;$k < count($addlesssplit);$k++)
			// 	{
			// 		$addlesssplitdesc = explode('$',$addlesssplit[$k]); 
			// 		$descamount = $addlesssplitdesc[2];
			// 		$descname = $addlesssplitdesc[0];
			// 		// Insert into Add/Less table 
			// 		$insertoffer = "INSERT INTO addlessdescsearch(invoiceno,descname,descamount,createddate,dealerid,regionid,branch,branchname) values('".$fetch0['slno']."','". $descname."','". $descamount."','".$fetch0['createddate']."','".$fetch0['dealerid']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."')";
			// 		$result = runmysqlquery($insertoffer);
			// 	}
			// }
		
			
			// Insert data to invoice detals table 
			
			if($fetch0['products'] <> '')
			{
				$count++;
				$totalamount = 0;
				$products = explode('#',$fetch0['products']);
				$description = explode('*',$fetch0['description']);
				$productquantity = explode(',',$fetch0['productquantity']);
				for($i = 0,$j = 0 ; $i < count($description),$j < count($productquantity);$i++,$j++)
				{
					//echo $productquantity[$j];
					$totalamount = 0;
					$amount = 0;
					//echo $description[$i];
					$splitdescription = explode('$',$description[$i]);
					$cardidsplit = $splitdescription[5];
					$scratchnumber = $splitdescription[4];
					$amount = $splitdescription[6];
					// $cardidsplit = explode('-',$cardid);
					// echo $cardidsplit[0];
					if($productquantity[$j] == 1)
					{
						$productcode = $products[$i];
						$usagetype = $splitdescription[3];
						$cardid = $splitdescription[5];
						$purchasetype = $splitdescription[2];
						$totalamount = $amount ;

						// Fetch Product 	
						$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
						$result1 = runmysqlqueryfetch($query1);
						
						// Insert into invoice details table
						$query3 = "insert into invoicedetailssearch(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid,state_info) values('".$fetch0['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch0['dealerid']."','".$fetch0['createddate']."','".$result1['productgroup']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."','".$scratchnumber."','".$cardid."','".$fetch0['state_info']."')"; 
						$result3 =  runmysqlquery($query3);
					}
					else
					{
						$cardidsplitarray = explode('-',$cardidsplit);
						//echo $cardidsplitarray[0]." card ".$cardidsplitarray[1]."<br>";							
						$querycard = "select * from inv_dealercard where cardid between ".$cardidsplitarray[0]." AND ".$cardidsplitarray[1];
						$resultcard = runmysqlquery($querycard);
						$cardamtcount = 0;
						while($fetchcard = mysqli_fetch_array($resultcard))
						{
							$productcode = $fetchcard['productcode'];
							$usagetype = $fetchcard['usagetype'];
							$cardid = $fetchcard['cardid'];
							$purchasetype = $fetchcard['purchasetype'];
							if($cardamtcount == 0)
								$totalamount = $amount ;
							else 
								$totalamount = 0;
							// Fetch Product 	
							$query1 = "select inv_mas_product.group as productgroup from inv_mas_product where productcode = '".$productcode."' ";
							$result1 = runmysqlqueryfetch($query1);
							
							// Insert into invoice details table
							
							$query3 = "insert into invoicedetailssearch(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,scratchnumber,cardid,state_info) values('".$fetch0['slno']."','".$productcode."','".$usagetype."','".$totalamount."','".$purchasetype."','".$fetch0['dealerid']."','".$fetch0['createddate']."','".$result1['productgroup']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."','".$scratchnumber."','".$cardid."','".$fetch0['state_info']."')"; 
							$result3 =  runmysqlquery($query3);
							$cardamtcount++;
						}
					}
					  
				}
			}	
		}
		//exit;
		$endtime = date('H:m:s');
		//exit;
		// Add indexing
		
		$queryindex1 = "ALTER TABLE invoicedetailssearch ADD INDEX (slno);";
		$resultindex1 = runmysqlquery($queryindex1);
		$queryindex2 = "ALTER TABLE invoicedetailssearch ADD INDEX (invoiceno);";
		$resultindex2 = runmysqlquery($queryindex2);
		$queryindex3 = "ALTER TABLE invoicedetailssearch ADD INDEX (productcode);";
		$resultindex3 = runmysqlquery($queryindex3);
		$queryindex4 = "ALTER TABLE invoicedetailssearch ADD INDEX (usagetype);";
		$resultindex4 = runmysqlquery($queryindex4);
		$queryindex5 = "ALTER TABLE invoicedetailssearch ADD INDEX (amount);";
		$resultindex5 = runmysqlquery($queryindex5);
		$queryindex6 = "ALTER TABLE invoicedetailssearch ADD INDEX (purchasetype);";
		$resultindex6 = runmysqlquery($queryindex6);
		$queryindex7 = "ALTER TABLE invoicedetailssearch ADD INDEX (dealerid);";
		$resultindex7 = runmysqlquery($queryindex7);
		$queryindex8 = "ALTER TABLE invoicedetailssearch ADD INDEX (invoicedate);";
		$resultindex8 = runmysqlquery($queryindex8);
		$queryindex9 = "ALTER TABLE invoicedetailssearch ADD INDEX (productgroup);";
		$resultindex9 = runmysqlquery($queryindex9);
		$queryindex10 = "ALTER TABLE invoicedetailssearch ADD INDEX (regionid);";
		$resultindex10 = runmysqlquery($queryindex10);
		$queryindex11 = "ALTER TABLE invoicedetailssearch ADD INDEX (branch);";
		$resultindex11 = runmysqlquery($queryindex11);
		$queryindex12 = "ALTER TABLE invoicedetailssearch ADD INDEX (branchname);";
		$resultindex12 = runmysqlquery($queryindex12);
		$queryindex13 = "ALTER TABLE invoicedetailssearch ADD INDEX (scratchnumber);";
		$resultindex13 = runmysqlquery($queryindex13);
		$queryindex14 = "ALTER TABLE invoicedetailssearch ADD INDEX (cardid);";
		$resultindex14 = runmysqlquery($queryindex14);
	
		$queryindex15 = "ALTER TABLE addlessdescsearch ADD INDEX (slno);";
		$resultindex15 = runmysqlquery($queryindex15);
		$queryindex16 = "ALTER TABLE addlessdescsearch ADD INDEX (invoiceno);";
		$resultindex16 = runmysqlquery($queryindex16);
		$queryindex17 = "ALTER TABLE addlessdescsearch ADD INDEX (descname);";
		$resultindex17 = runmysqlquery($queryindex17);
		$queryindex18 = "ALTER TABLE addlessdescsearch ADD INDEX (descamount);";
		$resultindex18 = runmysqlquery($queryindex18);
		$queryindex19 = "ALTER TABLE addlessdescsearch ADD INDEX (createddate);";
		$resultindex19 = runmysqlquery($queryindex19);
		$queryindex20 = "ALTER TABLE addlessdescsearch ADD INDEX (dealerid);";
		$resultindex20 = runmysqlquery($queryindex20);
		$queryindex21 = "ALTER TABLE addlessdescsearch ADD INDEX (regionid);";
		$resultindex21 = runmysqlquery($queryindex21);
		$queryindex23 = "ALTER TABLE addlessdescsearch ADD INDEX (branch);";
		$resultindex23 = runmysqlquery($queryindex23);
		$queryindex24 = "ALTER TABLE addlessdescsearch ADD INDEX (branchname);";
		$resultindex24 = runmysqlquery($queryindex24);
		
		$queryindex151 = "ALTER TABLE servicessearch ADD INDEX (slno);";
		$resultindex151 = runmysqlquery($queryindex151);
		$queryindex161 = "ALTER TABLE servicessearch ADD INDEX (servicename);";
		$resultindex161 = runmysqlquery($queryindex161);
		$queryindex171 = "ALTER TABLE servicessearch ADD INDEX (serviceamount);";
		$resultindex171 = runmysqlquery($queryindex171);
		$queryindex181 = "ALTER TABLE servicessearch ADD INDEX (createddate);";
		$resultindex181 = runmysqlquery($queryindex181);
		$queryindex191 = "ALTER TABLE servicessearch ADD INDEX (dealerid);";
		$resultindex191 = runmysqlquery($queryindex191);
		$queryindex201 = "ALTER TABLE servicessearch ADD INDEX (regionid);";
		$resultindex201 = runmysqlquery($queryindex201);
		$queryindex211 = "ALTER TABLE servicessearch ADD INDEX (branch);";
		$resultindex211 = runmysqlquery($queryindex211);
		$queryindex231 = "ALTER TABLE servicessearch ADD INDEX (branchname);";
		$resultindex231 = runmysqlquery($queryindex231);
		$queryindex241 = "ALTER TABLE servicessearch ADD INDEX (category);";
		$resultindex241 = runmysqlquery($queryindex241);
		
		
		
		//echo($starttime.'^^^'.$endtime);exit;
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		
		while($fetch = mysqli_fetch_array($result2))
		{
			$i_n++;
			$slnocount++;
			$color;
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount = count($productquantity);
			for($i=0; $i< $productcount; $i++)
			{
				$quantity += (int)$productquantity[$i];
			}
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
				$totalcount = $productsplitcount;
				
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$quantity."</td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
		
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				
				$grid .= "</tr>";
		}
		
		$grid .= "</table>";
		
		// Fetch the selected product groups 
		
		$querygetgroups = "select distinct productgroup from productgroups";
		$resultgetgroups = runmysqlquery($querygetgroups);
		$countgroups = mysqli_num_rows($resultgetgroups);
		$groups = '';
		while($fetch10 = mysqli_fetch_array($resultgetgroups))
		{
			if($groups == '')
				$groups = $fetch10['productgroup'];
			else
				$groups = $groups.','.$fetch10['productgroup'];
		}
		$splitgroup = explode(',',$groups);
		//echo($countgroups); exit;
		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';

		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearch where purchasetype = 'New' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$tdsnewpurchase= 0;$sppnewpurchase = 0;$stonewpurchase=0;$svhnewpurchase=0;$svinewpurchase=0;$sacnewpurchase=0;$othersnewpurchase=0;$gstnewpurchase=0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'GST')
				$gstnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearch where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$tdsupdationpurchase= 0;$sppupdationpurchase = 0;$stoupdationpurchase=0;$svhupdationpurchase=0;$sviupdationpurchase=0;$sacupdationpurchase=0;$othersupdationpurchase=0;$gstupdationpurchase=0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'GST')
				$gstupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
		}
			$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
			$spptotal = $sppnewpurchase + $sppupdationpurchase;
			$stototal = $stonewpurchase + $stoupdationpurchase;
			$svhtotal = $svhnewpurchase + $svhupdationpurchase;
			$svitotal = $svinewpurchase + $sviupdationpurchase;
			$sactotal = $sacnewpurchase + $sacupdationpurchase;

			$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
			$gsttotal = $gstnewpurchase + $gstupdationpurchase;
			$otherstotal = $othersnewpurchase + $othersupdationpurchase;
			
			$productwisegrid .= '<tr bgcolor="#F7FAFF">';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr  bgcolor="#edf4ff">';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
			$productwisegrid .= '</tr>';

			$productwisegrid .= '<tr>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
			$productwisegrid .= '</tr>';

		// Calculate totals
		$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $xbrlnewpurchase + $gstnewpurchase ;
		$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase + $xbrlupdationpurchase + $gstupdationpurchase;
		$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal + $gsttotal;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		$servicegrid = '';
		// Prepare Services Summary 
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicessearch  group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
			// Generate grid for Add/Less 
	
	$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
	//Write the header Row of the table
	$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
	
	$addlessamount = 0;
	
	// Get Today's services 
	$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdescsearch group by descname order by descname;';
	
	//Get Month's services
	$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdescsearch  group by descname order by descname;';
	
	$addlessresultmonth = runmysqlquery($addlessquerymonth);
	$addlessresulttoday = runmysqlquery($addlessquerytoday);
	$addlessresultmonthcount = mysqli_num_rows($addlessresultmonth);
	$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
	$differencevalue = 0;
	$addlessgridcount = mysqli_num_rows($addlessresultmonth);
	if($addlessgridcount > 0)
	{
	  while($addlessfetchmonth = mysqli_fetch_array($addlessresultmonth))
	  {
						  
		  if($addlessresultmonthcount == 2)	
		  {	
			  if($differencevalue == 0)
			  {
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
			  }
			  else
			  {
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
			  }
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			  $addlessgrid .= '</tr>';
			  $differencevalue++;
  
		  }
		  else
		  {
			if($addlessfetchmonth['descname'] == 'add')
			{
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td>';
				$addlessgrid .= '</tr>';
			}
			else if($addlessfetchmonth['descname'] == 'less')
			{
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
			}
		  }
		  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
		  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
  
	  }
	}
	else
	{
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
	}
	$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total: </strong></td><td  nowrap="nowrap" class="td-border-grid"   align="right"><strong>'.formatnumber($differencetoday).'</strong></td><td  nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($differencemonth).'</strong></td></tr>';
	$addlessgrid .= '</table>';
		
		//$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount1)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		echo '1^'.$grid.'^'.$fetchresultcount1.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamountall).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamountall).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;	
	}
	break;
	
	case "getregionwiseinvoicelistBKG":
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0; 
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0; 
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		
		$type = $_POST['type'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		if($type == 'BKG')
		{
			$addcategory = " inv_dealer_invoicenumbers.category = 'BKG'";
		}
		
		$resultcount = "select count(distinct inv_dealer_invoicenumbers.slno) as count 
		from inv_dealer_invoicenumbers  
		where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_dealer_invoicenumbers.slno,sum(inv_dealer_invoicenumbers.amount) as amount,
		sum(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		sum(inv_dealer_invoicenumbers.netamount) as netamount
		from inv_dealer_invoicenumbers 
		where   ".$addcategory." and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_dealer_invoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
		$fetchresult1 =runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalsalevalue = $fetchresult['amount'];
			$totaltax = $fetchresult['servicetax'];
			$totalamount = $fetchresult['netamount'];
		}
		else
		{
			$totalinvoices = '0';
			$totalsalevalue = '0';
			$totaltax = '0';
			$totalamount = '0';
		}
		
		if($showtype == 'all')
			$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		$query = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,
		inv_dealer_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount,
		(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,
		inv_dealer_invoicenumbers.products as products,inv_dealer_invoicenumbers.servicedescription as servicedescription,
		inv_dealer_invoicenumbers.productquantity from inv_dealer_invoicenumbers
		where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount = count($productquantity);
			for($i=0; $i< $productcount; $i++)
			{
				$quantity += (int)$productquantity[$i];
			}
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
				$totalcount = $productsplitcount;
				
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($quantity)."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				
				$grid .= "</tr>";
		}
		$grid .= "</table>";

		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
		
		
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'BKG' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$tdsnewpurchase= 0;$sppnewpurchase = 0;$stonewpurchase=0;$svhnewpurchase=0;$svinewpurchase=0;$sacnewpurchase=0;$othersnewpurchase=0; $gstnewpurchase=0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'GST')
				$gstnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'BKG' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$tdsupdationpurchase= 0;$sppupdationpurchase = 0;$stoupdationpurchase=0;$svhupdationpurchase=0;$sviupdationpurchase=0;$sacupdationpurchase=0;$othersupdationpurchase=0; $gstupdationpurchase=0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'GST')
				$gstupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
		}
		$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
		$spptotal = $sppnewpurchase + $sppupdationpurchase;
		$stototal = $stonewpurchase + $stoupdationpurchase;
		$svhtotal = $svhnewpurchase + $svhupdationpurchase;
		$svitotal = $svinewpurchase + $sviupdationpurchase;
		$sactotal = $sacnewpurchase + $sacupdationpurchase;
		$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
		$gsttotal = $gstnewpurchase + $gstupdationpurchase;
		$otherstotal = $othersnewpurchase + $othersupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
		$productwisegrid .= '</tr>';

		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
		$productwisegrid .= '</tr>';

	// Calculate totals
	$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $xbrlnewpurchase + $gstnewpurchase;
	$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase + $xbrlupdationpurchase + $gstupdationpurchase;
	$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal + $gsttotal ;

	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
	$productwisegrid .= '</tr>';
	$productwisegrid .= '</table>'; 
		
	  // Prepare Services Summary 
	  
	  $servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
	  
	  //Write the header Row of the table
	  $servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
	  
	  $servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestoday where  ".$servicedatetoday." and category = 'BKG' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
	  $resultallservices = runmysqlquery($servicesall);
	  $totalservices = 0;
	  $i_n = 0;
	  //echo(mysqli_num_rows($resultallservices));exit;
	  while($fetchallservices = mysqli_fetch_array($resultallservices))
	  {
		  $i_n++;
		  if($i_n%2 == 0)
			  $color = "#edf4ff";
		  else
			  $color = "#f7faff";
		  $totalservices = $totalservices + $fetchallservices['amount'];
		  $servicegrid .= '<tr bgcolor='.$color.'>';
		  $servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
		  $servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
		  $servicegrid .= '</tr>';			
	  }
	  $servicegrid .= '<tr >';
	  $servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
	  $servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
	  $servicegrid .= '</table>';	
	  
				  // Generate grid for Add/Less 
  
	$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
	//Write the header Row of the table
	$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
	
	$addlessamount = 0;
	
	// Get Today's services 
	$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesctoday where  regionid = "1" group by descname order by descname;';
	
	//Get Month's services
	$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesctoday  where   regionid = "1" group by descname;';
	
	$addlessresultmonth = runmysqlquery($addlessquerymonth);
	$addlessresulttoday = runmysqlquery($addlessquerytoday);
	$addlessresultmonthcount = mysqli_num_rows($addlessresultmonth);
	$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
	$differencevalue = 0;
	$addlessgridcount = mysqli_num_rows($addlessresultmonth);
	if($addlessgridcount > 0)
	{
	  while($addlessfetchmonth = mysqli_fetch_array($addlessresultmonth))
	  {
						  
		  if($addlessresultmonthcount == 2)	
		  {	
			  if($differencevalue == 0)
			  {
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
			  }
			  else
			  {
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
			  }
			  $addlessgrid .= '<tr>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
			  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
			  $addlessgrid .= '</tr>';
			  $differencevalue++;
  
		  }
		  else
		  {
			if($addlessfetchmonth['descname'] == 'add')
			{
				$addlessfetchtodayadd = $addlessfetchtoday['amount'];
				$addlessfetchmonthadd = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td>';
				$addlessgrid .= '</tr>';
			}
			else if($addlessfetchmonth['descname'] == 'less')
			{
				$addlessfetchtodayless = $addlessfetchtoday['amount'];
				$addlessfetchmonthless = $addlessfetchmonth['amount'];
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
				$addlessgrid .= '</tr>';
				$addlessgrid .= '<tr>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				$addlessgrid .= '</tr>';
			}
		  }
		  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
		  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
  
	  }
	}
	else
	{
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
			
	}
	$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total: </strong></td><td  nowrap="nowrap" class="td-border-grid"   align="right"><strong>'.formatnumber($differencetoday).'</strong></td><td  nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($differencemonth).'</strong></td></tr>';
	$addlessgrid .= '</table>';
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistBKG(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistBKG(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;
	}
		break;
		
	case "getregionwiseinvoicelistBKM":
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$tdstotal = 0;
		$tdsnew = 0; 
		$tdsupdation = 0;
		$spptotal = 0; 
		$sppnew = 0; 
		$sppupdation = 0;
		$stototal = 0;
		$stonew = 0 ; 
		$stoupdation = 0;
		$svhtotal = 0;
		$svhnew = 0;
		$svhupdation = 0;
		$svitotal = 0; 
		$svinew = 0 ; 
		$sviupdation = 0;
		$sactotal = 0;
		$sacnew = 0;
		$sacupdation = 0; 
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$otherstotal = 0;
		$othersnew = 0 ; 
		$othersupdation = 0;
		$overalltotal = 0;	
		$xbrltotal = 0;
		$xbrlnew = 0;
		$xbrlupdation = 0; 
		$gsttotal = 0;
		$gstnew = 0;
		$gstupdation = 0; 
		
		$type = $_POST['type'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		if($type == 'BKM')
		{
			$addcategory = "inv_dealer_invoicenumbers.category = 'BKM'";
		}
		
		$resultcount = "select count(distinct inv_dealer_invoicenumbers.slno) as count from inv_dealer_invoicenumbers  
		where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_dealer_invoicenumbers.slno,sum(inv_dealer_invoicenumbers.amount) as amount,
		sum(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		sum(inv_dealer_invoicenumbers.netamount) as netamount
		from inv_dealer_invoicenumbers 
		where   ".$addcategory." and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_dealer_invoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
		$fetchresult1 =runmysqlquery($invoiceresult);
		if(mysqli_num_rows($fetchresult1) > 0)
		{
			$fetchresult = runmysqlqueryfetch($invoiceresult);
			$totalinvoices = $fetchresultcount;
			$totalsalevalue = $fetchresult['amount'];
			$totaltax = $fetchresult['servicetax'];
			$totalamount = $fetchresult['netamount'];
		}
		else
		{
			$totalinvoices = '0';
			$totalsalevalue = '0';
			$totaltax = '0';
			$totalamount = '0';
		}
		
		if($showtype == 'all')
			$limit = 100000;
		else
		$limit = 10;
		if($startlimit == '')
		{
			$startlimit = 0;
			$slnocount = 0;
		}
		else
		{
			$startlimit = $slnocount ;
			$slnocount = $slnocount;
		}
		$query = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,inv_dealer_invoicenumbers.invoiceno,
		inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount,
		(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
		inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,
		inv_dealer_invoicenumbers.products as products,inv_dealer_invoicenumbers.servicedescription as servicedescription,inv_dealer_invoicenumbers.productquantity from inv_dealer_invoicenumbers
		where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			
			$quantity = 0;
			$productquantity = explode(',',$fetch['productquantity']);
			$productcount = count($productquantity);
			for($i=0; $i< $productcount; $i++)
			{
				$quantity += (int)$productquantity[$i];
			}
			
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['products'] == '')
				$totalcount = $servicedescriptioncount;
			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
				$totalcount = $servicedescriptioncount + $productsplitcount;
			else
				$totalcount = $productsplitcount;
				
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$quantity."</td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				
				$grid .= "</tr>";
		}
		$grid .= "</table>";

		// Get products and services totals seperately.
		
		/*$queryproducts = "select ifnull(sum(amount),'0') as amount from invoicedetailstoday where left(invoicedetailstoday.invoicedate,10) = curdate() and category = 'BKM' ";
		$fetchresultproducts = runmysqlqueryfetch($queryproducts);
		$productstotal = $fetchresultproducts['amount'];
		
		$queryservices = "select ifnull(sum(serviceamount),'0') as amount from servicestoday where left(servicestoday.createddate,10) = curdate() and category = 'BKM'";
		$fetchresultservices = runmysqlqueryfetch($queryservices);
		$servicestotaltotal = $fetchresultservices['amount'];*/

		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
	   $querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'BKM' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$tdsnewpurchase= 0;$sppnewpurchase = 0;$stonewpurchase=0;$svhnewpurchase=0;$svinewpurchase=0;$sacnewpurchase=0;$othersnewpurchase=0;$gstnewpurchase=0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'GST')
				$gstnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'BKM' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$tdsupdationpurchase= 0;$sppupdationpurchase = 0;$stoupdationpurchase=0;$svhupdationpurchase=0;$sviupdationpurchase=0;$sacupdationpurchase=0;$othersupdationpurchase=0; $gstupdationpurchase=0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'GST')
				$gstupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
		}
		$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
		$spptotal = $sppnewpurchase + $sppupdationpurchase;
		$stototal = $stonewpurchase + $stoupdationpurchase;
		$svhtotal = $svhnewpurchase + $svhupdationpurchase;
		$svitotal = $svinewpurchase + $sviupdationpurchase;
		$sactotal = $sacnewpurchase + $sacupdationpurchase;
		$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
		$gsttotal = $gstnewpurchase + $gstupdationpurchase;
		$otherstotal = $othersnewpurchase + $othersupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
		$productwisegrid .= '</tr>';

                $productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
		$productwisegrid .= '</tr>';
		
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
		$productwisegrid .= '</tr>';

	// Calculate totals
	$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $gstnewpurchase;
	$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase + $gstupdationpurchase;
	$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $gsttotal;

	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
	$productwisegrid .= '</tr>';
	$productwisegrid .= '</table>';
		
		// Prepare Services Summary 
		
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestoday where  ".$servicedatetoday." and category = 'BKM' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
		 // Generate grid for Add/Less 
	  
		$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		//Write the header Row of the table
		$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
		
		$addlessamount = 0;
		
		// Get Today's services 
		$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesctoday where   regionid = "3" group by descname order by descname;';
		
		//Get Month's services
		$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesctoday  where    regionid = "3" group by descname;';
		
		$addlessresultmonth = runmysqlquery($addlessquerymonth);
		$addlessresulttoday = runmysqlquery($addlessquerytoday);
		$addlessresultmonthcount = mysqli_num_rows($addlessresultmonth);
		$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
		$differencevalue = 0;
		$addlessgridcount = mysqli_num_rows($addlessresultmonth);
		if($addlessgridcount > 0)
		{
		  while($addlessfetchmonth = mysqli_fetch_array($addlessresultmonth))
		  {
							  
			  if($addlessresultmonthcount == 2)	
			  {	
				  if($differencevalue == 0)
				  {
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
				  }
				  else
				  {
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
				  }
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				  $addlessgrid .= '</tr>';
				  $differencevalue++;
	  
			  }
			  else
			  {
				if($addlessfetchmonth['descname'] == 'add')
				{
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td>';
					$addlessgrid .= '</tr>';
				}
				else if($addlessfetchmonth['descname'] == 'less')
				{
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '</tr>';
				}
			  }
			  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
			  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
	  
		  }
		}
		else
		{
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
		}
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total: </strong></td><td  nowrap="nowrap" class="td-border-grid"   align="right"><strong>'.formatnumber($differencetoday).'</strong></td><td  nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($differencemonth).'</strong></td></tr>';
		$addlessgrid .= '</table>';
		
		if($type == 'BKM')
		{
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistBKM(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistBKM(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		}
	}	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;

		break;
		
		case "getregionwiseinvoicelistCSD":
		{
			$todaynewtotal = 0 ;
			$todayupdationtotal = 0;
			$tdstotal = 0;
			$tdsnew = 0; 
			$tdsupdation = 0;
			$spptotal = 0; 
			$sppnew = 0; 
			$sppupdation = 0;
			$stototal = 0;
			$stonew = 0 ; 
			$stoupdation = 0;
			$svhtotal = 0;
			$svhnew = 0;
			$svhupdation = 0;
			$svitotal = 0; 
			$svinew = 0 ; 
			$sviupdation = 0;
			$sactotal = 0;
			$sacnew = 0;
			$sacupdation = 0; 
			$otherstotal = 0;
			$othersnew = 0 ; 
			$othersupdation = 0;
			$overalltotal = 0;	
			$xbrltotal = 0;
			$xbrlnew = 0;
			$xbrlupdation = 0;
			$gsttotal = 0;
		        $gstnew = 0;
		        $gstupdation = 0; 
			
			$type = $_POST['type'];
			$startlimit = $_POST['startlimit'];
			$slnocount = $_POST['slnocount'];
			$showtype = $_POST['showtype'];
		
			if($type == 'CSD')
			{
				$addcategory = " inv_dealer_invoicenumbers.category = 'CSD'";
			}
		
			$resultcount = "select count(distinct inv_dealer_invoicenumbers.slno) as count from inv_dealer_invoicenumbers  
			where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.slno;";
			$fetch10 = runmysqlqueryfetch($resultcount);
			$fetchresultcount = $fetch10['count'];
			
			// To fetch invoice totals and other details
			$invoiceresult = "select distinct inv_dealer_invoicenumbers.slno,sum(inv_dealer_invoicenumbers.amount) as amount,
			sum(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
			sum(inv_dealer_invoicenumbers.netamount) as netamount
			from inv_dealer_invoicenumbers where  ".$addcategory." and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_dealer_invoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
			$fetchresult1 =runmysqlquery($invoiceresult);
			if(mysqli_num_rows($fetchresult1) > 0)
			{
				$fetchresult = runmysqlqueryfetch($invoiceresult);
				$totalinvoices = $fetchresultcount;
				$totalsalevalue = $fetchresult['amount'];
				$totaltax = $fetchresult['servicetax'];
				$totalamount = $fetchresult['netamount'];
			}
			else
			{
				$totalinvoices = '0';
				$totalsalevalue = '0';
				$totaltax = '0';
				$totalamount = '0';
			}
			
			if($showtype == 'all')
				$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slnocount = 0;
			}
			else
			{
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
			$query = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,
			inv_dealer_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount, 
			(IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,
			inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,inv_dealer_invoicenumbers.products as products,
			inv_dealer_invoicenumbers.servicedescription as servicedescription,inv_dealer_invoicenumbers.productquantity from inv_dealer_invoicenumbers 
			where ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
			$result = runmysqlquery($query);
			
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
				<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
			}
			$i_n = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slnocount++;
				$color;
				
				$quantity = 0;
				$productquantity = explode(',',$fetch['productquantity']);
				$productcount = count($productquantity);
				for($i=0; $i< $productcount; $i++)
				{
					$quantity += (int)$productquantity[$i];
				}
			
				$productsplit = explode('#',$fetch['products']);
				$productsplitcount = count($productsplit);
				
				$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
				$servicedescriptioncount = count($servicedescriptionsplit);
				
				if($fetch['products'] == '')
					$totalcount = $servicedescriptioncount;
				elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
					$totalcount = $servicedescriptioncount + $productsplitcount;
				else
					$totalcount = $productsplitcount;
					
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
					$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$quantity."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
		
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
					
					$grid .= "</tr>";
			}
			$grid .= "</table>";
		
		// Get products and services totals seperately.
		
		/*$queryproducts = "select ifnull(sum(amount),'0') as amount from invoicedetailstoday where left(invoicedetailstoday.invoicedate,10) = curdate() and category = 'CSD' ";
		$fetchresultproducts = runmysqlqueryfetch($queryproducts);
		$productstotal = $fetchresultproducts['amount'];
		
		$queryservices = "select ifnull(sum(serviceamount),'0') as amount from servicestoday where left(servicestoday.createddate,10) = curdate() and category = 'CSD'";
		$fetchresultservices = runmysqlqueryfetch($queryservices);
		$servicestotaltotal = $fetchresultservices['amount'];*/

		
		$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		$productwisegrid .= '<tr class="tr-grid-header">';
		$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
		$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
		$productwisegrid .= '</tr>';
		
		// New Purchases of dealer based on product group and purchase type
		
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'CSD' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$tdsnewpurchase= 0;$sppnewpurchase = 0;$stonewpurchase=0;$svhnewpurchase=0;$svinewpurchase=0;$sacnewpurchase=0;$othersnewpurchase=0;$gstnewpurchase=0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'TDS')
				$tdsnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SPP')
				$sppnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'STO')
				$stonewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVH')
				$svhnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SVI')
				$svinewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'SAC')
				$sacnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'XBRL')
				$xbrlnewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'GST')
				$gstnewpurchase = $fetchnewpurchase['amount'];
			else	
				$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'CSD' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$tdsupdationpurchase= 0;$sppupdationpurchase = 0;$stoupdationpurchase=0;$svhupdationpurchase=0;$sviupdationpurchase=0;$sacupdationpurchase=0;$othersupdationpurchase=0;$gstupdationpurchase=0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'TDS')
				$tdsupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SPP')
				$sppupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'STO')
				$stoupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVH')
				$svhupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SVI')
				$sviupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'SAC')
				$sacupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'XBRL')
				$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'GST')
				$gstupdationpurchase = $fetchupdationpurchase['amount'];
			else	
				$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
		}
		$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
		$spptotal = $sppnewpurchase + $sppupdationpurchase;
		$stototal = $stonewpurchase + $stoupdationpurchase;
		$svhtotal = $svhnewpurchase + $svhupdationpurchase;
		$svitotal = $svinewpurchase + $sviupdationpurchase;
		$sactotal = $sacnewpurchase + $sacupdationpurchase;
		$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
		$gsttotal = $gstnewpurchase + $gstupdationpurchase;
		$otherstotal = $othersnewpurchase + $othersupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
		$productwisegrid .= '</tr>';

		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
		$productwisegrid .= '</tr>';

	// Calculate totals
	$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $xbrlnewpurchase + $gstnewpurchase;
	$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase + $xbrlupdationpurchase  + $gstupdationpurchase;
	$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal  + $gsttotal;

	$productwisegrid .= '<tr>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
	$productwisegrid .= '</tr>';
	$productwisegrid .= '</table>';

		
		// Prepare Services Summary 
		
		$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
		//Write the header Row of the table
		$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
		$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestoday where  ".$servicedatetoday." and category = 'CSD' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
		$resultallservices = runmysqlquery($servicesall);
		$totalservices = 0;
		$i_n = 0;
		//echo(mysqli_num_rows($resultallservices));exit;
		while($fetchallservices = mysqli_fetch_array($resultallservices))
		{
			$i_n++;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$totalservices = $totalservices + $fetchallservices['amount'];
			$servicegrid .= '<tr bgcolor='.$color.'>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
			$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
			$servicegrid .= '</tr>';			
		}
		$servicegrid .= '<tr >';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
		$servicegrid .= '</table>';	
		
				 // Generate grid for Add/Less 
	  
		$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
		//Write the header Row of the table
		$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
		
		$addlessamount = 0;
		
		// Get Today's services 
		$addlessquerytoday = 'select sum(descamount) as amount,descname from addlessdesctoday where   regionid = "2" group by descname order by descname;';
		
		//Get Month's services
		$addlessquerymonth = 'select sum(descamount) as amount,descname from addlessdesctoday  where    regionid = "2" group by descname;';
		
		$addlessresultmonth = runmysqlquery($addlessquerymonth);
		$addlessresulttoday = runmysqlquery($addlessquerytoday);
		$addlessresultmonthcount = mysqli_num_rows($addlessresultmonth);
		$differencetoday = 0;$differencemonth =0;$addlessfetchtodayadd =0;$addlessfetchtodayless=0;$addlessfetchmonthadd =0; $addlessfetchmonthless = 0;
		$differencevalue = 0;
		$addlessgridcount = mysqli_num_rows($addlessresultmonth);
		if($addlessgridcount > 0)
		{
		  while($addlessfetchmonth = mysqli_fetch_array($addlessresultmonth))
		  {
							  
			  if($addlessresultmonthcount == 2)	
			  {	
				  if($differencevalue == 0)
				  {
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
				  }
				  else
				  {
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
				  }
				  $addlessgrid .= '<tr>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
				  $addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
				  $addlessgrid .= '</tr>';
				  $differencevalue++;
	  
			  }
			  else
			  {
				if($addlessfetchmonth['descname'] == 'add')
				{
					$addlessfetchtodayadd = $addlessfetchtoday['amount'];
					$addlessfetchmonthadd = $addlessfetchmonth['amount'];
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td>';
					$addlessgrid .= '</tr>';
				}
				else if($addlessfetchmonth['descname'] == 'less')
				{
					$addlessfetchtodayless = $addlessfetchtoday['amount'];
					$addlessfetchmonthless = $addlessfetchmonth['amount'];
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
					$addlessgrid .= '</tr>';
					$addlessgrid .= '<tr>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.strtoupper($addlessfetchmonth['descname']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($addlessfetchtoday['amount']).'</td>';
					$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">'.formatnumber($addlessfetchmonth['amount']).'</td>';
					$addlessgrid .= '</tr>';
				}
			  }
			  $differencetoday = $addlessfetchtodayadd - $addlessfetchtodayless;
			  $differencemonth = $addlessfetchmonthadd - $addlessfetchmonthless;
	  
		  }
		}
		else
		{
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
			$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td><td  nowrap="nowrap" class="td-border-grid"   align="right">0</td><td  nowrap="nowrap" class="td-border-grid"  align="right">0</td></tr>';
		}
		$addlessgrid .= '<tr><td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total: </strong></td><td  nowrap="nowrap" class="td-border-grid"   align="right"><strong>'.formatnumber($differencetoday).'</strong></td><td  nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($differencemonth).'</strong></td></tr>';
		$addlessgrid .= '</table>';
		
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistCSD(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistCSD(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
			
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;
		}
		break;
		
	// 	case "getregionwiseinvoicelistOnline":
	// 	{
	// 		$todaynewtotal = 0 ;
	// 		$todayupdationtotal = 0;
	// 		$tdstotal = 0;
	// 		$tdsnew = 0; 
	// 		$tdsupdation = 0;
	// 		$spptotal = 0; 
	// 		$sppnew = 0; 
	// 		$sppupdation = 0;
	// 		$stototal = 0;
	// 		$stonew = 0 ; 
	// 		$stoupdation = 0;
	// 		$svhtotal = 0;
	// 		$svhnew = 0;
	// 		$svhupdation = 0;
	// 		$svitotal = 0; 
	// 		$svinew = 0 ; 
	// 		$sviupdation = 0;
	// 		$sactotal = 0;
	// 		$sacnew = 0;
	// 		$sacupdation = 0; 
	// 		$otherstotal = 0;
	// 		$othersnew = 0 ; 
	// 		$othersupdation = 0;
	// 		$overalltotal = 0;	
	// 		$amcttoday = 0;
	// 		$attinttoday =0 ; $custtoday =0 ; $eiptoday = 0; $implementationtoday = 0; $pptoday = 0; $smstoday = 0; 
	// 		$supporttoday = 0; $tastoday = 0; $trainingtoday = 0; 
	// 		$xbrltotal = 0;
	// 		$xbrlnew = 0;
	// 		$xbrlupdation = 0; 
	// 		$gsttotal = 0;
	// 	        $gstnew = 0;
	// 	        $gstupdation = 0; 
			
	// 		$type = $_POST['type'];
	// 		$startlimit = $_POST['startlimit'];
	// 		$slnocount = $_POST['slnocount'];
	// 		$showtype = $_POST['showtype'];
			
	// 		if($type == 'Online')
	// 		{
	// 			$addcategory = "inv_dealer_invoicenumbers.category = 'Online'";
	// 		}
	// 		$resultcount = "select count(distinct inv_dealer_invoicenumbers.slno) as count from inv_dealer_invoicenumbers  
	// left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_dealer_invoicenumbers.slno
	// where  ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.slno;";
	// 		$fetch10 = runmysqlqueryfetch($resultcount);
	// 		$fetchresultcount = $fetch10['count'];
			
	// 		// To fetch invoice totals and other details
	// 		$invoiceresult = "select distinct inv_dealer_invoicenumbers.slno,sum(inv_dealer_invoicenumbers.amount) as amount,sum(inv_dealer_invoicenumbers.servicetax+IFNULL(inv_dealer_invoicenumbers.sbtax,0)+IFNULL(inv_dealer_invoicenumbers.kktax,0)+IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,sum(inv_dealer_invoicenumbers.netamount) as netamount
	// from inv_dealer_invoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_dealer_invoicenumbers.slno where ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."'   group by inv_dealer_invoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
	// 		$fetchresult1 =runmysqlquery($invoiceresult);
	// 		if(mysqli_num_rows($fetchresult1) > 0)
	// 		{
	// 			$fetchresult = runmysqlqueryfetch($invoiceresult);
	// 			$totalinvoices = $fetchresultcount;
	// 			$totalsalevalue = $fetchresult['amount'];
	// 			$totaltax = $fetchresult['servicetax'];
	// 			$totalamount = $fetchresult['netamount'];
	// 		}
	// 		else
	// 		{
	// 			$totalinvoices = '0';
	// 			$totalsalevalue = '0';
	// 			$totaltax = '0';
	// 			$totalamount = '0';
	// 		}
			
	// 		if($showtype == 'all')
	// 			$limit = 100000;
	// 		else
	// 		$limit = 10;
	// 		if($startlimit == '')
	// 		{
	// 			$startlimit = 0;
	// 			$slnocount = 0;
	// 		}
	// 		else
	// 		{
	// 			$startlimit = $slnocount ;
	// 			$slnocount = $slnocount;
	// 		}
	// 		$query = "select distinct inv_dealer_invoicenumbers.slno,left(inv_dealer_invoicenumbers.createddate,10) as createddate,inv_dealer_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.customerid,inv_dealer_invoicenumbers.businessname,inv_dealer_invoicenumbers.amount,(inv_dealer_invoicenumbers.servicetax+IFNULL(inv_dealer_invoicenumbers.sbtax,0)+IFNULL(inv_dealer_invoicenumbers.kktax,0)+IFNULL(inv_dealer_invoicenumbers.igst,0)+IFNULL(inv_dealer_invoicenumbers.sgst,0)+IFNULL(inv_dealer_invoicenumbers.cgst,0)) as servicetax,inv_dealer_invoicenumbers.netamount,inv_dealer_invoicenumbers.dealername,inv_dealer_invoicenumbers.createdby,inv_dealer_invoicenumbers.status,inv_dealer_invoicenumbers.products as products,inv_dealer_invoicenumbers.servicedescription as servicedescription,inv_dealer_invoicenumbers.productquantity,inv_dealer_invoicenumbers.seztaxtype as seztaxtype,inv_dealer_invoicenumbers.seztaxfilepath as seztaxfilepath from inv_dealer_invoicenumbers
	// left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_dealer_invoicenumbers.slno
	//  where ".$addcategory."  and left(inv_dealer_invoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_dealer_invoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
	// 		$result = runmysqlquery($query);
			
	// 		if($startlimit == 0)
	// 		{
	// 			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
	// 			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action</td><td nowrap = "nowrap" class="td-border-grid" align="left">Email</td><td nowrap = "nowrap" class="td-border-grid" align="left">SEZ Download</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
	// 		}
	// 		$i_n = 0;
	// 		while($fetch = mysqli_fetch_array($result))
	// 		{
	// 			$i_n++;
	// 			$slnocount++;
	// 			$color;
				
	// 			$quantity = 0;
	// 			$productquantity = explode(',',$fetch['productquantity']);
	// 			$productcount = count($productquantity);
	// 			for($i=0; $i< $productcount; $i++)
	// 			{
	// 				$quantity += $productquantity[$i];
	// 			}
			
	// 			$productsplit = explode('#',$fetch['products']);
	// 			$productsplitcount = count($productsplit);
				
	// 			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
	// 			$servicedescriptioncount = count($servicedescriptionsplit);
				
	// 			if($fetch['products'] == '')
	// 				$totalcount = $servicedescriptioncount;
	// 			elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
	// 				$totalcount = $servicedescriptioncount + $productsplitcount;
	// 			else
	// 				$totalcount = $productsplitcount;
				
	// 			if($i_n%2 == 0)
	// 				$color = "#edf4ff";
	// 			else
	// 				$color = "#f7faff";
	// 				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
	// 				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
	// 				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess" style ="display:none;"></div></td>';
	// 				if($fetch['seztaxtype'] == 'yes')
	// 				{
	// 					$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><a href="'.$fetch['seztaxfilepath'].'"><img src="../images/sez_download.gif" width="15" height="15" border="0" align="absmiddle" style="cursor:pointer" alt="Download" title="Download"/></a></td>';
	// 				}
	// 				else
	// 				{
	// 					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>Not Avaliable</td>";
		
	// 				}
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$quantity."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['status']."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
			
	// 			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['servicetax'])."</td>";
	// 			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
			
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
	// 				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
					
	// 				$grid .= "</tr>";
	// 		}
	// 		$grid .= "</table>";
			
	// 		/*// Get products and services totals seperately.
		
	// 		$queryproducts = "select ifnull(sum(amount),'0') as amount from invoicedetailstoday where left(invoicedetailstoday.invoicedate,10) = curdate() and category = 'Online' ";
	// 		$fetchresultproducts = runmysqlqueryfetch($queryproducts);
	// 		$productstotal = $fetchresultproducts['amount'];
			
	// 		$queryservices = "select ifnull(sum(serviceamount),'0') as amount from servicestoday where left(servicestoday.createddate,10) = curdate() and category = 'Online'";
	// 		$fetchresultservices = runmysqlqueryfetch($queryservices);
	// 		$servicestotaltotal = $fetchresultservices['amount'];*/
			
	// 	$productwisegrid  = '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
	
	// 	$productwisegrid .= '<tr class="tr-grid-header">';
	// 	$productwisegrid .= '<td width = "22%" class="td-border-grid" nowrap="nowrap" ><div align="center" ><strong>Product</strong></div></td>';
	// 	$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center" ><strong>New</strong></div></td>';
	// 	$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Updation</strong></div></td>';
	// 	$productwisegrid .= '<td width = "26%" class="td-border-grid" nowrap="nowrap" style = "font-size:12px;"><div align="center"><strong>Total</strong></div></td>';
	// 	$productwisegrid .= '</tr>';
	
	// // New Purchases of dealer based on product group and purchase type
	
	// 	$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'Online' group by productgroup;";
	// 	$resultnewpurchase = runmysqlquery($querynewpurchase);
	// 	$tdsnewpurchase= 0;$sppnewpurchase = 0;$stonewpurchase=0;$svhnewpurchase=0;$svinewpurchase=0;$sacnewpurchase=0;$othersnewpurchase=0;$gstnewpurchase=0;
	// 	while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
	// 	{

	// 		if($fetchnewpurchase['productgroup'] == 'TDS')
	// 			$tdsnewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'SPP')
	// 			$sppnewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'STO')
	// 			$stonewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'SVH')
	// 			$svhnewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'SVI')
	// 			$svinewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'SAC')
	// 			$sacnewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'XBRL')
	// 			$xbrlnewpurchase = $fetchnewpurchase['amount'];
	// 		else if($fetchnewpurchase['productgroup'] == 'GST')
	// 			$gstnewpurchase = $fetchnewpurchase['amount'];
	// 		else	
	// 			$othersnewpurchase = $othersnewpurchase + $fetchnewpurchase['amount'];
	// 	}
		
	// 	//Fetch Group by Product Amount on Type UPDATION
	// 	$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'Online' group by productgroup;";
	// 	$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
	// 	$tdsupdationpurchase= 0;$sppupdationpurchase = 0;$stoupdationpurchase=0;$svhupdationpurchase=0;$sviupdationpurchase=0;$sacupdationpurchase=0;$othersupdationpurchase=0; $gstupdationpurchase=0;
	// 	while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
	// 	{

	// 		if($fetchupdationpurchase['productgroup'] == 'TDS')
	// 			$tdsupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'SPP')
	// 			$sppupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'STO')
	// 			$stoupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'SVH')
	// 			$svhupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'SVI')
	// 			$sviupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'SAC')
	// 			$sacupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'XBRL')
	// 			$xbrlupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else if($fetchupdationpurchase['productgroup'] == 'GST')
	// 			$gstupdationpurchase = $fetchupdationpurchase['amount'];
	// 		else	
	// 			$othersupdationpurchase = $othersupdationpurchase + $fetchupdationpurchase['amount'];
	// 	}
	// 	$tdstotal = $tdsnewpurchase + $tdsupdationpurchase;
	// 	$spptotal = $sppnewpurchase + $sppupdationpurchase;
	// 	$stototal = $stonewpurchase + $stoupdationpurchase;
	// 	$svhtotal = $svhnewpurchase + $svhupdationpurchase;
	// 	$svitotal = $svinewpurchase + $sviupdationpurchase;
	// 	$sactotal = $sacnewpurchase + $sacupdationpurchase;
	// 	$xbrltotal = $xbrlnewpurchase + $xbrlupdationpurchase;
	// 	$gsttotal = $gstnewpurchase + $gstupdationpurchase;
	// 	$otherstotal = $othersnewpurchase + $othersupdationpurchase;
		
	// 	$productwisegrid .= '<tr bgcolor="#F7FAFF">';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">TDS</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($tdsnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdsupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($tdstotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr  bgcolor="#edf4ff">';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">SPP</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($sppupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($spptotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">STO</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stonewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($stoupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($stototal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff">SVH</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($svhupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($svhtotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left" bgcolor="#F7FAFF">SVI</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($svinewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($sviupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($svitotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">SAC</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($sacupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($sactotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">XBRL</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($xbrlupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($xbrltotal).'</td>';
	// 	$productwisegrid .= '</tr>';

	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#edf4ff">GST</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff">'.formatnumber($gstupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#edf4ff">'.formatnumber($gsttotal).'</td>';
	// 	$productwisegrid .= '</tr>';
		
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left" bgcolor="#F7FAFF">OTHERS</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersnewpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($othersupdationpurchase).'</td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($otherstotal).'</td>';
	// 	$productwisegrid .= '</tr>';
	
	// 	// Calculate totals
	// 	$todaynewtotal = $tdsnewpurchase + $sppnewpurchase + $stonewpurchase + $svhnewpurchase + $svinewpurchase + $sacnewpurchase + $othersnewpurchase + $xbrlnewpurchase;  + $gstnewpurchase;
	// 	$todayupdationtotal = $tdsupdationpurchase + $sppupdationpurchase+ $stoupdationpurchase + $svhupdationpurchase + $sviupdationpurchase + $sacupdationpurchase + $othersupdationpurchase + $xbrlupdationpurchase  + $gstupdationpurchase; 
	// 	$overalltotal = $tdstotal + $spptotal + $stototal + $svhtotal + $svitotal + $sactotal + $otherstotal + $xbrltotal + $gsttotal;
	
	// 	$productwisegrid .= '<tr>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
	// 	$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
	// 	$productwisegrid .= '</tr>';
	// 	$productwisegrid .= '</table>';
			
	// 		// Prepare Services Summary 
			
	// 	$servicegrid = '<table width="100%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;">';
		
	// 	//Write the header Row of the table
	// 	$servicegrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "60%" align="center" ><strong>Service Name</strong/></td><td nowrap="nowrap" class="td-border-grid" align="center" width = "40%"><strong>Total</strong></td></tr>';
		
	// 	$servicesall = "select ifnull(services.netamount,'0') as amount,inv_mas_service.servicename from inv_mas_service left join (select ifnull(sum(serviceamount),'0') as netamount,servicename from servicestoday where  ".$servicedatetoday." and category = 'Online' group by servicename) as services on services.servicename = inv_mas_service.servicename order by inv_mas_service.servicename";
	// 	$resultallservices = runmysqlquery($servicesall);
	// 	$totalservices = 0;
	// 	$i_n = 0;
	// 	//echo(mysqli_num_rows($resultallservices));exit;
	// 	while($fetchallservices = mysqli_fetch_array($resultallservices))
	// 	{
	// 		$i_n++;
	// 		if($i_n%2 == 0)
	// 			$color = "#edf4ff";
	// 		else
	// 			$color = "#f7faff";
	// 		$totalservices = $totalservices + $fetchallservices['amount'];
	// 		$servicegrid .= '<tr bgcolor='.$color.'>';
	// 		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">'.$fetchallservices['servicename'].'</td>';
	// 		$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($fetchallservices['amount']).'</td>';
	// 		$servicegrid .= '</tr>';			
	// 	}
	// 	$servicegrid .= '<tr >';
	// 	$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left"><strong>Total</strong></td>';
	// 	$servicegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right"><strong>'.formatnumber($totalservices).'</strong></td></tr>';
	// 	$servicegrid .= '</table>';	
			
	// 	$addlessgrid .= '<table width="95%" cellspacing="0" border = "0" cellpadding="2" align = "left" class="table-border-grid" style="font-size:12px;" >';
	// 	//Write the header Row of the table
	// 	$addlessgrid .= '<tr class="tr-grid-header"><td nowrap="nowrap" class="td-border-grid" width = "31%" align="center" >Add/Less</td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Day Sales </td><td nowrap="nowrap" class="td-border-grid"  align="center" width = "27%">Month till Date</td></tr>';
	// 	$addlessgrid .= '<tr>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">ADD</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right">0</td></tr>';
	// 	$addlessgrid .= '<tr>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">LESS</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right"><strong>0</strong></td></tr>';
	// 	$addlessgrid .= '<tr>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="left">Total</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">0</td>';
	// 	$addlessgrid .= '<td nowrap="nowrap" class="td-border-grid" align="right"><strong>0</strong></td></tr>';
		
	// 	$addlessgrid .= '</tr>';
	// 	if($slnocount >= $fetchresultcount)
	// 		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
	// 	else
	// 		$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistOnline(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistOnline(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
	// 	}
	// 	echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;

	// 	break; 
		
		case 'resendinvoice':
		{
			$invoiceno = $_POST['invoiceno'];
			$sent = resenddealerinvoice($invoiceno);
			echo($sent);
		}
		break;
		
		case 'searchsettings':
		{
			$textfield = $_POST['textfield'];
			$subselection = $_POST['subselection'];
			$cancelledinvoice = $_POST['cancelledinvoice'];
			$selection = $_POST['selection'];
			$productslist = $_POST['productslist'];
			$itemlist = $_POST['itemlist'];
			$selectiontype = $_POST['selectiontype'];
			$category = $_POST['category'];
			$categorysplit = explode('/',$category);
			$categoryvalue = substr($categorysplit[2],0,-4);
			
			$selectioncombine = $textfield.'$@$'.$subselection.'$@$'.$cancelledinvoice.'$@$'.$selection.'$@$'.$productslist.'$@$'.$itemlist;
			
			$query11 = "Select selectiontype from inv_selectionsettings where userid = '".$userid."' and module = 'user_module' and category = '".$categoryvalue."'";
			$result22 = runmysqlquery($query11);
			if(mysqli_num_rows($result22) > 0)
			{
				while($fetch22 = mysqli_fetch_array($result22))
				{
					$selectiontypearray[] = strtolower($fetch22['selectiontype']);
				}
				if(in_array(strtolower($selectiontype),$selectiontypearray,true))
				{ 
					echo("2^"."Enter a different settings name, the name already exists.");
					exit;
	
				}
			}
			$query = "Insert into inv_selectionsettings(category,selection,
userid,module,selectiontype,createdip,createddate,lastmodifieddate,lastmodifiedby,lastmodifiedip) values 
('".$categoryvalue."','".$selectioncombine."','".$userid."','user_module','".$selectiontype."',
'".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			
			echo('1^'.'Record Inserted Successfully.'.'^'.$userid);
		}
		break;
		
		case 'loadselection':
		{
			$lastslno = $_POST['userid'];
			$category = $_POST['category'];
			$categorysplit = explode('/',$category);
			$categoryvalue = substr($categorysplit[2],0,-4);
			
			$query = "SELECT * from inv_selectionsettings where userid = '".$lastslno."' and category = '".$categoryvalue."' and module = 'user_module'";
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) == 0)
			{
				$grid .='<select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">';
				$grid .= '<option value="default">DEFAULT</option>';
			}
			else
			{
				$grid .='<select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">';
				$grid .= '<option value="default">DEFAULT</option>';
				while($fetch = mysqli_fetch_array($result))
				{
					$grid .=	'<option value="'.$fetch['slno'].'">'.$fetch['selectiontype'].'</option>';
				}
				$grid.='</select>';
			}
			
			echo('1^'.$grid);
		}
		break;
		
		case 'displayselection':
		{
			$lastslno = $_POST['lastslno'];
			
			$query = "SELECT * from inv_selectionsettings where slno = '".$lastslno."' and module = 'user_module'";
			$fetch = runmysqlqueryfetch($query);
			
			echo('1*^*'.$fetch['selection']);
		}
		break;
		case 'deleteselection':
		{
			$lastslno = $_POST['lastslno'];
			
			$query = "DELETE  from  inv_selectionsettings  where slno = '".$lastslno."' and module = 'user_module'";
			$fetch = runmysqlquery($query);
			
			echo('1^'.'Record Deleted Successfully'.'^'.$userid);
		}
		break;
		
		case 'productdetailsgrid':
		{
			$productslno = $_POST['productslno'];
			$query = "select * from inv_dealer_invoicenumbers where slno = '".$productslno."' ;";
			$result = runmysqlquery($query);
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
			
			$i_n = 0;$slnocount = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$description = $fetch['description'];
				$descriptionsplit = explode('*',$description);
				$descriptionsplitcount = count($descriptionsplit);
				
				$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
				$servicedescriptioncount = count($servicedescriptionsplit);

				if($fetch['products'] == '')
				$fetchresultantcount = $servicedescriptioncount;
				elseif(($fetch['products'] != '') && ($fetch['servicedescription'] != ''))
					$fetchresultantcount = $servicedescriptioncount + $descriptionsplitcount;
				else
					$fetchresultantcount = $descriptionsplitcount;
					
				if($description != '')
				{
					for($i=0;$i<$descriptionsplitcount;$i++)
					{
						$descriptionline = explode('$',$descriptionsplit[$i]);
						$grid .= '<tr bgcolor='.$color.'>';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[0]."</td> ";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[1]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[2]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[3]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[4]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[5]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$descriptionline[6]."</td>";
						$grid .= "</tr>";
					}
				}
				
				if($fetch['servicedescription'] <> '')
				{
					for($i=0; $i<$servicedescriptioncount; $i++)
					{
						$count++;
						$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
						$grid .= '<tr bgcolor='.$color.' >';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[0]."</td> ";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[1]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>&nbsp;</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$servicedescriptionline[2]."</td>";
						$grid .= "</tr>";
					}
				}				
			}
			$grid .= "</table>";
	
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			
			echo '1^'.$grid.'^'.$fetchresultantcount.'^'.$linkgrid;
		}
	break;
}

?>
