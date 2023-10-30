<?php
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

	$servicedatetoday = "left(servicestoday.createddate,10) = curdate()";
	// Get all invoices of today into a temporary table.  
			
	$querydrop = "Drop table if exists invoicedetailstoday;";
	$result = runmysqlquery($querydrop);

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
		
	// Insert data to invoicedetails table
	$query0 = "select * from inv_matrixinvoicenumbers where left(inv_matrixinvoicenumbers.createddate,10) = curdate() and products <> '' and `status` <> 'CANCELLED'";
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
			// for($j = 0 ; $j < $productquantity[$i];$j++)
			// {
			  $totalamount = 0;
			  $splitdescription = explode('$',$description[$k]);
			  $productcode = $products[$i];
			  $amount = $splitdescription[4];
			  $purchasetype = $splitdescription[2];   
			  $totalamount1 = $amount ;
			  $k++;	  
			  // Fetch Product 	
			  $query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
			  $result1 = runmysqlqueryfetch($query1);
			  
			  // Insert into invoice details table
			  $query3 = "insert into invoicedetailstoday(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category) values('".$fetch2['slno']."','".$productcode."','".$usagetype."','".$totalamount1."','".$purchasetype."','".$fetch2['dealerid']."','".$fetch2['createddate']."','".$result1['productgroup']."','".$fetch2['regionid']."','".$fetch2['branchid']."','".$fetch2['branch']."','".$fetch2['category']."')";
			  $result3 =  runmysqlquery($query3);
			//}
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
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;	
		
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers  
		where  left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.slno";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_matrixinvoicenumbers.slno,sum(inv_matrixinvoicenumbers.amount) as amount,
		(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,sum(inv_matrixinvoicenumbers.netamount) as netamount
		from inv_matrixinvoicenumbers 
		where left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_matrixinvoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,
		inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,
		(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,
		inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,
		inv_matrixinvoicenumbers.productquantity
		from inv_matrixinvoicenumbers 
		where  left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; //echo($query);exit;
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
		<td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td>
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
				$quantity += $productquantity[$i];
			}
			
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			
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
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['customerid']."</td>";
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
			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{
			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
				
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		
		
		$overalltotal = $softwaretotal + $hardwaretotal ;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
		$productwisegrid .= '</tr>';
			

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal ;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		
		
		
	
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid;	
	}
	break;
	case 'searchinvoices':
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;	

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
		$productslist = $_POST['itemlist'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$status = $_POST['status'];
		$series = $_POST['series'];
		
		$seriesnew = $_POST['seriesnew'];
		
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$receiptstatus = $_POST['receiptstatus'];
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
				
				$querygetproductgroup = "select * from inv_mas_matrixproduct where id = '".$productlistsplit[$i]."'";
				$resultproductgroup = runmysqlqueryfetch($querygetproductgroup);
				 
				//echo($querygetproductgroup);exit;
				// Insert to temporary table 
				
				$queryinsertproductgroup = "insert into `productgroups`(productcode,productgroup) values('".$resultproductgroup['productcode']."','".$resultproductgroup['group']."')";
				$resultofinsert = runmysqlquery($queryinsertproductgroup);
				
				$finalproductlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
			}
		}
		$endtime = date('H:m:s');
		//echo($starttime.'^^^^'.$endtime);
		
		$finallistarray = ' AND ('.$finalproductlist.')';

		$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		$regionpiece = ($region == "")?(""):(" AND inv_matrixinvoicenumbers.regionid = '".$region."' ");
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_matrixinvoicenumbers.dealerid = '".$dealer."' ");
		$branchpiece = ($branch == "")?(""):(" AND inv_matrixinvoicenumbers.branchid = '".$branch."' ");
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_matrixinvoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_matrixinvoicenumbers.module = '".$modulepiece."'");
		
		$seriespiece = ($series == "")?(""):(" AND inv_matrixinvoicenumbers.category = '".$series."' ");
		
		$seriespiecenew = ($seriesnew == "")?(""):(" AND inv_matrixinvoicenumbers.invoice_type = '".$seriesnew."' ");
		
		$cancelledpiece = ($cancelledinvoice == "yes")?("AND inv_matrixinvoicenumbers.status <> 'CANCELLED'"):("");
		$statuspiece = ($status == "")?(""):(" AND inv_matrixinvoicenumbers.status = '".$status."'");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.status = '".$receiptstatus."' ");
		$datepiece = ($alltimecheck == 'yes')?(""):(" AND (left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' AND '".$todate."') ");	
		
		// Create Temporary tables 
		
		$querydrop = "Drop table if exists invoicedetailssearch;";
		$result = runmysqlquery($querydrop);

		
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
				  `state_info` varchar(25) collate latin1_general_ci default NULL, 
				   PRIMARY KEY  (`slno`)                                               
				);";
		$result1 = runmysqlquery($query);	
	

		
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

		$querycase = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,inv_matrixinvoicenumbers.description,inv_matrixinvoicenumbers.branch,inv_matrixinvoicenumbers.branchid,inv_matrixinvoicenumbers.category,inv_matrixinvoicenumbers.dealerid,inv_matrixinvoicenumbers.regionid,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers
		left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
		left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
		left join inv_mas_receipt on inv_mas_receipt.matrixinvoiceno = inv_matrixinvoicenumbers.slno
		";

		
		switch($databasefield)
		{
			case "slno":
				$cusid = strlen($textfield);
				if($cusid == 17)
					$customerid = substr($textfield,12);
				else if($cusid == 20)
					$customerid = substr($textfield,15);
				else
					$customerid = $textfield;
					
				$query = $querycase." where right(inv_matrixinvoicenumbers.customerid,5) like '%".$customerid."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;
		
			case "contactperson": 
				$query = $querycase." where inv_matrixinvoicenumbers.contactperson LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece." order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;
			case "phone":
				$query = $querycase." where inv_matrixinvoicenumbers.phone LIKE '%".$textfield."%' OR inv_matrixinvoicenumbers.cell LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc ";
				break;
			case "place":
				$query = $querycase." where inv_matrixinvoicenumbers.place LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;
			case "emailid":
				$query = $querycase." where inv_matrixinvoicenumbers.emailid LIKE '%".$textfield."%'  ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece." order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;			
			case "invoiceno":
				$query = $querycase." where inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;
			case "invoiceamt":
				$query = $querycase." where inv_matrixinvoicenumbers.netamount LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  order by inv_matrixinvoicenumbers.createddate,inv_matrixinvoicenumbers.slno desc";
				break;
			
			default:
				$query = $querycase." where inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%' ".$datepiece.$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$seriespiecenew.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece."  ORDER BY createddate,inv_matrixinvoicenumbers.slno desc";
				break;
		} 
		//	echo($query); exit;	

		$totalresult = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','274','".date('Y-m-d').' '.date('H:i:s')."','view_matrixinvoiceregister')";
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
		
			
			// Insert data to invoice detals table 
			
			if($fetch0['products'] <> '')
			{
				$count++;
				$products = explode('#',$fetch0['products']);
				$description = explode('*',$fetch0['description']);
				$productquantity = explode(',',$fetch0['productquantity']);
				$k=0;
				for($i = 0 ; $i < count($description);$i++)
				{

					for($j = 0 ; $j < $productquantity[$i];$j++)
					{		
					
					  $amount = 0;
					  $splitdescription = explode('$',$description[$k]);
					  $productcode = $products[$i];
					  $amount = $splitdescription[4];
					  $purchasetype = $splitdescription[2];   //echo($usagetype.'^'.$amount.'^'.$purchasetype); exit;
					  $totalamount = $amount ;
					  

					  // Fetch Product 	
					  
					  $query1 = "select inv_mas_matrixproduct.group as productgroup from inv_mas_matrixproduct where id = '".$productcode."' ";
					  $result1 = runmysqlqueryfetch($query1);
					  
					  // Insert into invoice details table
					  
					  $query3 = "insert into invoicedetailssearch(invoiceno,productcode,usagetype,amount,purchasetype,dealerid,invoicedate,productgroup,regionid,branch,branchname,category,state_info) values('".$fetch0['slno']."','".$productcode."','".$usagetype."','".$amount."','".$purchasetype."','".$fetch0['dealerid']."','".$fetch0['createddate']."','".$result1['productgroup']."','".$fetch0['regionid']."','".$fetch0['branchid']."','".$fetch0['branch']."','".$fetch0['category']."','".$fetch0['state_info']."')"; 
					  $result3 =  runmysqlquery($query3);
					  $k++;
					}
				}
			}	
		}
		$endtime = date('H:m:s');
		
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
			<td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td>
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
				$quantity += $productquantity[$i];
			}
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			$totalcount = $productsplitcount;
				
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$quantity."</td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
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
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailssearch where purchasetype = 'Updation'  group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}
			$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
			$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;

			$productwisegrid .= '<tr bgcolor="#F7FAFF">';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
			$productwisegrid .= '</tr>';
			
			$productwisegrid .= '<tr  bgcolor="#edf4ff">';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
			$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
			$productwisegrid .= '</tr>';
			

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal;
	
		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';

		if($slnocount >= $fetchresultcount1)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		
		echo '1^'.$grid.'^'.$fetchresultcount1.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamountall).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamountall).'^'.$productwisegrid;	
	}
	break;
	
	case "getregionwiseinvoicelistBKG":
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;	
		
		$type = $_POST['type'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		if($type == 'BKG')
		{
			$addcategory = " inv_matrixinvoicenumbers.category = 'BKG'";
		}
		
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers  
		where  ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_matrixinvoicenumbers.slno,sum(inv_matrixinvoicenumbers.amount) as amount,sum(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,sum(inv_matrixinvoicenumbers.netamount) as netamount
		from inv_matrixinvoicenumbers where   ".$addcategory." and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_matrixinvoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers
		where  ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
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
				$quantity += $productquantity[$i];
			}
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
						
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
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
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
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'BKG' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
		$productwisegrid .= '</tr>';

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal;

		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>'; 
		
	  
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid;
	}
	break;
		
	case "getregionwiseinvoicelistBKM":
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0;
		
		$type = $_POST['type'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		if($type == 'BKM')
		{
			$addcategory = "inv_matrixinvoicenumbers.category = 'BKM'";
		}
		
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers  
		where  ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_matrixinvoicenumbers.slno,sum(inv_matrixinvoicenumbers.amount) as amount,sum(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,sum(inv_matrixinvoicenumbers.netamount) as netamount
		from inv_matrixinvoicenumbers where   ".$addcategory." and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_matrixinvoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers
		where  ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
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
				$quantity += $productquantity[$i];
			}
			
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
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
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
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
		
	   $querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'BKM' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'BKM' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
		$productwisegrid .= '</tr>';

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase ;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal;

		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		
		if($type == 'BKM')
		{
			if($slnocount >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistBKM(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistBKM(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		}

		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid;
	}	
	break;
		
	case "getregionwiseinvoicelistCSD":
	{
		$todaynewtotal = 0 ;
		$todayupdationtotal = 0;
		$softwaretotal = 0;
		$softwareupdation = 0;
		$softwarenew = 0;
		$hardwaretotal = 0; 
		$hardwarenew = 0; 
		$hardwareupdation = 0;
		$overalltotal = 0; 
		
		$type = $_POST['type'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
	
		if($type == 'CSD')
		{
			$addcategory = " inv_matrixinvoicenumbers.category = 'CSD'";
		}
	
		$resultcount = "select count(distinct inv_matrixinvoicenumbers.slno) as count from inv_matrixinvoicenumbers  
		where  ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		// To fetch invoice totals and other details
		$invoiceresult = "select distinct inv_matrixinvoicenumbers.slno,sum(inv_matrixinvoicenumbers.amount) as amount,sum(IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,sum(inv_matrixinvoicenumbers.netamount) as netamount
		from inv_matrixinvoicenumbers where  ".$addcategory." and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."'  group by inv_matrixinvoicenumbers.slno with rollup  limit ".($fetchresultcount).",1 ";
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
		$query = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount, (IFNULL(inv_matrixinvoicenumbers.igst,0)+IFNULL(inv_matrixinvoicenumbers.sgst,0)+IFNULL(inv_matrixinvoicenumbers.cgst,0)) as servicetax,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products,inv_matrixinvoicenumbers.productquantity from inv_matrixinvoicenumbers left join dealer_online_purchase on dealer_online_purchase.onlineinvoiceno = inv_matrixinvoicenumbers.slno
		where ".$addcategory."  and left(inv_matrixinvoicenumbers.createddate,10) = '".date('Y-m-d')."' order by inv_matrixinvoicenumbers.createddate desc LIMIT ".$startlimit.",".$limit." ; "; 
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
			<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Quantity</td><td nowrap = "nowrap" class="td-border-grid" align="left">Status</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td><td nowrap = "nowrap" class="td-border-grid" align="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td><td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
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
				$quantity += $productquantity[$i];
			}
		
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
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
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
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
		
		// New Purchases of dealer based on product group and purchase type
		
		$querynewpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'New'  and category = 'CSD' group by productgroup;";
		$resultnewpurchase = runmysqlquery($querynewpurchase);
		$softwarenewpurchase= 0;$hardwarenewpurchase = 0;
		while($fetchnewpurchase = mysqli_fetch_array($resultnewpurchase))
		{

			if($fetchnewpurchase['productgroup'] == 'Software')
				$softwarenewpurchase = $fetchnewpurchase['amount'];
			else if($fetchnewpurchase['productgroup'] == 'Hardware')
				$hardwarenewpurchase = $fetchnewpurchase['amount'];
		}
		
		//Fetch Group by Product Amount on Type UPDATION
		$queryupdationpurchase = "select ifnull(sum(amount),'0') as amount,productgroup from invoicedetailstoday where purchasetype = 'Updation' and category = 'CSD' group by productgroup;";
		$resultupdationpurchase = runmysqlquery($queryupdationpurchase);
		$softwareupdationpurchase= 0;$hardwareupdationpurchase = 0;
		while($fetchupdationpurchase = mysqli_fetch_array($resultupdationpurchase))
		{

			if($fetchupdationpurchase['productgroup'] == 'Software')
				$softwareupdationpurchase = $fetchupdationpurchase['amount'];
			else if($fetchupdationpurchase['productgroup'] == 'Hardware')
				$hardwareupdationpurchase = $fetchupdationpurchase['amount'];
		}
		$softwaretotal = $softwarenewpurchase + $softwareupdationpurchase;
		$hardwaretotal = $hardwarenewpurchase + $hardwareupdationpurchase;
		
		$productwisegrid .= '<tr bgcolor="#F7FAFF">';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#F7FAFF">Software</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#F7FAFF">'.formatnumber($softwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right" bgcolor="#F7FAFF">'.formatnumber($softwaretotal).'</td>';
		$productwisegrid .= '</tr>';
		
		$productwisegrid .= '<tr  bgcolor="#edf4ff">';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="left">Hardware</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwarenewpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap"  class="td-border-grid" align="right" >'.formatnumber($hardwareupdationpurchase).'</td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid"  align="right">'.formatnumber($hardwaretotal).'</td>';
		$productwisegrid .= '</tr>';

		// Calculate totals
		$todaynewtotal = $softwarenewpurchase + $hardwarenewpurchase;
		$todayupdationtotal = $softwareupdationpurchase + $hardwareupdationpurchase;
		$overalltotal = $softwaretotal + $hardwaretotal;

		$productwisegrid .= '<tr>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="left" bgcolor="#edf4ff"><strong>Total</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todaynewtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($todayupdationtotal).'</strong></td>';
		$productwisegrid .= '<td nowrap="nowrap" class="td-border-grid" align="right" bgcolor="#edf4ff"><strong>'.formatnumber($overalltotal).'</strong></td>';
		$productwisegrid .= '</tr>';
		$productwisegrid .= '</table>';
		
		if($slnocount >= $fetchresultcount)
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreofinvoicelistCSD(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreofinvoicelistCSD(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
		
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$totalinvoices.'^'.formatnumber($totalsalevalue).'^'.number_format($totaltax,2).'^'.formatnumber($totalamount).'^'.convert_number($totalsalevalue).'^'.convert_number($totaltax).'^'.convert_number($totalamount).'^'.$productwisegrid.'^'.$servicegrid.'^'.$addlessgrid;
	}
	break;
		
		
	case 'resendinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice('',$invoiceno);
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
		$query = "select * from inv_matrixinvoicenumbers where slno = '".$productslno."' ;";
		$result = runmysqlquery($query);
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Pin Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
		
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