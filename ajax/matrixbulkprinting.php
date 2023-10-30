<?php
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


$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'searchinvoices':
	{
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer'];
		$branch = $_POST['branch'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		$district = $_POST['district'];
		$status = $_POST['status'];
		$series = $_POST['series'];
		$seriesnew = $_POST['seriesnew'];
		$purchasetype = $_POST['purchasetype'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$receiptstatus = $_POST['receiptstatus'];
		$itemlist = $_POST['itemlist'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);
		
		
		if($itemlist != '')
		{
			for($j = 0;$j< $itemlistsplitcount; $j++)
			{
				if($j < ($itemlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';

					$finalmatrixlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor."";
			}
			$finallistarray = 'AND ( '.$finalmatrixlist .' ) ';
		}
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
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");

		$querycase = "select distinct inv_matrixinvoicenumbers.slno,left(inv_matrixinvoicenumbers.createddate,10) as createddate,inv_matrixinvoicenumbers.netamount,inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.customerid,inv_matrixinvoicenumbers.businessname,inv_matrixinvoicenumbers.amount,inv_matrixinvoicenumbers.dealername,inv_matrixinvoicenumbers.createdby,inv_matrixinvoicenumbers.status,inv_matrixinvoicenumbers.products as products from inv_matrixinvoicenumbers
		left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
		left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
		left join inv_mas_receipt on inv_mas_receipt.matrixinvoiceno = inv_matrixinvoicenumbers.slno
		where left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."'";
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
					
				$query = $querycase." AND right(inv_matrixinvoicenumbers.customerid,5) like '%".$customerid."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno";
				break;
		
			case "contactperson": 
				$query = $querycase." AND inv_matrixinvoicenumbers.contactperson LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_matrixinvoicenumbers.slno";
				break;
			case "phone":
				$query = $querycase." AND inv_matrixinvoicenumbers.phone LIKE '%".$textfield."%' OR inv_matrixinvoicenumbers.cell LIKE '%".$textfield."%' ".$productlistarray.$itemlistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno ";
				break;
			case "place":
				$query = $querycase." AND inv_matrixinvoicenumbers.place LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno";
				break;
			case "emailid":
				$query = $querycase." AND inv_matrixinvoicenumbers.emailid LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew." order by inv_matrixinvoicenumbers.slno";
				break;
			case "invoiceno":
				$query = $querycase." AND  inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno";
				break;
			case "invoiceamt":
				$query = $querycase." AND  inv_matrixinvoicenumbers.netamount LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno";
				break;
			
			default:
				$query = $querycase." AND inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$usagetypepiece.$purchasetypepiece.$cancelledpiece.$receiptstatuspiece.$seriespiecenew."  order by inv_matrixinvoicenumbers.slno";
				break;
		} 
		//echo($query);exit;
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','84','".date('Y-m-d').' '.date('H:i:s')."','view_bulk_print(Invoices)')";
		$eventresult = runmysqlquery($eventquery);
		$result1 = runmysqlquery($query);
		
		$fetchresultcount = mysqli_num_rows($result1);
		$grid = '';
			
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Action</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Email</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Products</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Customer ID</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sale Value</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sales Person</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td></tr>';
		}
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$i_n++;
			$slnocount++;
			$color;
			$productsplit = explode('#',$fetch['products']);
			$productsplitcount = count($productsplit);
			
			$totalcount = $productsplitcount;
			
			
			
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$countselected = 'countselected';
				$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><input type="checkbox" name="resultcheckbox'.$slnocount.'" id ="resultcheckbox'.$slnocount.'" value = "'.$fetch['slno'].'" onclick = "javascript:selectanddeselect(\''.$countselected.'\')" />';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewinvoice(\''.$fetch['slno'].'\');" class="resendtext" style = "cursor:pointer"> View >></a> </td>';
				$grid .= '<td nowrap="nowrap" class="td-border-grid" align="center"><div id= "resendemail'.$slnocount.'" style ="display:block;"> <a onclick="resendinvoice(\''.$fetch['slno'].'\',\''.$slnocount.'\');" class="resendtext" style = "cursor:pointer"> Resend </a> </div><div id ="resendprocess'.$slnocount.'" style ="display:none;"></div></td>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['createddate']))."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['invoiceno']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='center'><a onclick='displayproductdetails(\"".$fetch['slno']."\");' class='resendtext' style = 'cursor:pointer'>".$totalcount."</a></td>";

				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['status'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['customerid'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['businessname'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['netamount'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['dealername'])."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".trim($fetch['createdby'])."</td>";
				
				$grid .= "</tr>";
		}
		$grid .= "</table>";
		
		
		echo '1^'.$grid.'^'.$fetchresultcount;	
	}
	break;
	case 'resendmatrixinvoice':
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
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Product Amount</td></tr>';
		
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