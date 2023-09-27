<?
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
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$paymentmode = $_POST['paymentmode'];
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer'];
		$branch = $_POST['branch'];
		$reconsilation = $_POST['reconsilation'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		$district = $_POST['district'];
		$productslist = $_POST['productscode'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$status = $_POST['status'];
		$series = $_POST['series'];
		$receiptstatus = $_POST['receiptstatus'];
		$cancelledinvoice = $_POST['cancelledinvoice'];
		$totalreceipts = '0';
		$totalamount = '0';
		$itemlist = $_POST['itemlist'];
		$itemlistsplit = explode(',',$itemlist);
		$itemlistsplitcount = count($itemlistsplit);

		$matrixlist = $_POST['matrixlist'];
		$matrixlistsplit = explode(',',$matrixlist);
		$matrixlistsplitcount = count($matrixlistsplit);
		
		if($productslist != '')
		{
			for($i = 0;$i< $productlistsplitcount; $i++)
			{
				if($i < ($productlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finalproductlist .= ' inv_invoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor."";
			}
		}

		if($matrixlist!= '')
		{
			for($i = 0;$i< $matrixlistsplitcount; $i++)
			{
				if($i < ($matrixlistsplitcount-1))
					$appendor = 'or'.' ';
				else
					$appendor = '';
					
				$finalmatrixlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$matrixlistsplit[$i].'%'.'" '.$appendor."";
			}
		}
		//echo $finalmatrixlist; exit;
		
		if($itemlist != '')
		{
			for($j = 0;$j< $itemlistsplitcount; $j++)
			{
				if($j < ($itemlistsplitcount-1))
					$appendor1 = 'or'.' ';
				else
					$appendor1 = '';
					
				$finalitemlist .= ' inv_invoicenumbers.servicedescription'.' '.'like'.' "'.'%'.$itemlistsplit[$j].'%'.'" '.$appendor1."";
			}
		}
		if($productslist != '' && $itemlist != '' && $matrixlist!= '')
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.' '.'OR'.' '.$finalmatrixlist.')';
		elseif(($itemlist != '') && ($productslist != ''))
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalitemlist.')';
		elseif($productslist != '' && $matrixlist!= '')
			$finallistarray = ' AND ('.$finalproductlist.' '.'OR'.' '.$finalmatrixlist.')';
		elseif($productslist == '')
			$finallistarray = ' AND ('.$finalitemlist.')';
		elseif($itemlist == '')
			$finallistarray = ' AND ('.$finalproductlist.')';
		elseif($matrixlist!= '')
			$finallistarray = ' AND ('.$finalmatrixlist.')';

		$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		$regionpiece = ($region == "")?(""):(" AND (inv_invoicenumbers.regionid = '".$region."' or inv_matrixinvoicenumbers.regionid = '".$region."') ");
		
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND (inv_invoicenumbers.dealerid = '".$dealer."' or inv_matrixinvoicenumbers.dealerid = '".$dealer."') ");
		$branchpiece = ($branch == "")?(""):(" AND (inv_invoicenumbers.branchid = '".$branch."' or inv_matrixinvoicenumbers.branchid = '".$branch."') ");
		$generatedpiece = ($generatedby == "")?(""):("AND inv_mas_receipt.createdby = '".$generatedbysplit[0]."'");
		$statuspiece = ($status == "")?(""):(" AND (inv_invoicenumbers.status = '".$status."' or inv_matrixinvoicenumbers.status = '".$status."')");
		$seriespiece = ($series == "")?(""):(" AND (inv_invoicenumbers.category = '".$series."' or inv_matrixinvoicenumbers.category = '".$series."') ");
		$paymentmodepiece = ($paymentmode == "")?(""):(" and inv_mas_receipt.paymentmode = '".$paymentmode."' ");
		$cancelledpiece = ($cancelledinvoice == "yes")?("AND (inv_invoicenumbers.status <> 'CANCELLED' or inv_matrixinvoicenumbers.status <> 'CANCELLED')"):("");
		$receiptstatuspiece = ($receiptstatus == "")?(""):(" and inv_mas_receipt.restatus = '".$receiptstatus."' ");
		$reconsilationpiece = ($reconsilation == "")?(""):(" and inv_mas_receipt.reconsilation = '".$reconsilation."' ");

		$resultcount = "select count(distinct inv_mas_receipt.slno) as count from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		left join inv_mas_customer as cust on cust.slno = right(inv_invoicenumbers.customerid,5)
		left join inv_mas_customer as mcust on mcust.slno = right(inv_matrixinvoicenumbers.customerid,5)
		left join inv_mas_district as dist on dist.districtcode = cust.district
		left join inv_mas_district as mdist on mdist.districtcode = mcust.district
		where inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece." order by inv_mas_receipt.slno;";
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		$totalreceipts = $fetchresultcount;
	
		
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

		$querycase  = "select distinct inv_mas_receipt.slno as receiptslno , inv_mas_receipt.receiptdate,inv_invoicenumbers.customerid,inv_matrixinvoicenumbers.customerid as mcust,inv_invoicenumbers.businessname,inv_matrixinvoicenumbers.businessname as mbusinessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_invoicenumbers.invoiceno,inv_matrixinvoicenumbers.invoiceno as minvoiceno,inv_invoicenumbers.dealername,inv_mas_receipt.createdby,inv_invoicenumbers.status,inv_matrixinvoicenumbers.status as mstatus,inv_mas_receipt.reconsilation,inv_mas_receipt.receiptremarks,
		inv_mas_receipt.chequedate,inv_mas_receipt.chequeno,inv_mas_receipt.drawnon,inv_mas_receipt.module from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno 
		left join inv_mas_customer as cust on cust.slno = right(inv_invoicenumbers.customerid,5) 
		left join inv_mas_customer as mcust on mcust.slno = right(inv_matrixinvoicenumbers.customerid,5) 
		left join inv_mas_district as dist on dist.districtcode = cust.district 
		left join inv_mas_district as mdist on mdist.districtcode = mcust.district 
		where inv_mas_receipt.receiptdate BETWEEN '".$fromdate."' and '".$todate."'";
		
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
					
				$query = $querycase." AND (right(inv_invoicenumbers.customerid,5) like '%".$customerid."%' or right(inv_matrixinvoicenumbers.customerid,5) like '%".$customerid."%') ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
		
			case "invoiceno":
				$query = $querycase." AND  (inv_invoicenumbers.invoiceno LIKE '%".$textfield."%' or inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%') ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			case "receiptno":
				$query = $querycase." AND inv_mas_receipt.slno LIKE '%".$textfield."%' ".$productlistarray.$itemlistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			case "chequeno":
				$query = $querycase." AND inv_mas_receipt.chequeno LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			case "chequedate":
				$query = $querycase." AND inv_mas_receipt.chequedate LIKE '%".changedateformat($textfield)."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			case "depositdate":
				$query = $querycase." AND inv_mas_receipt.depositdate LIKE '%".changedateformat($textfield)."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			case "drawnon":
				$query = $querycase." AND inv_mas_receipt.drawnon LIKE '%".$textfield."%' ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
				
			case "paymentamt":
				$query = $querycase." AND inv_mas_receipt.receiptamount LIKE '%".$textfield."%'  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  order by inv_mas_receipt.slno ";
				break;
			
			default:
				$query = $querycase." AND  (inv_invoicenumbers.businessname LIKE '%".$textfield."%' or inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%')  ".$finallistarray.$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece.$paymentmodepiece.$cancelledpiece.$receiptstatuspiece.$reconsilationpiece."  ORDER BY inv_mas_receipt.slno ";
				break;
		} 
		//echo $query; exit;
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','74','".date('Y-m-d').' '.date('H:i:s')."','view_receiptreconsile')";
		$eventresult = runmysqlquery($eventquery);
		$fetchresultcount = mysqli_num_rows($result);
		$addlimit = " LIMIT ".$startlimit.",".$limit.";";
		$query1 = $query.$addlimit;
		$result1 = runmysqlquery($query1);
		$grid = '';
		
		//echo $query1;
		//exit();
			
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid-new">';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result1))
		{
			$reconsilationtype1 = '';$reconsilationtype2 = '';$reconsilationtype3 = '';
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				if($fetch['receiptremarks'] <> '')
					$receiptremarks = $fetch['receiptremarks'];
				else
					$receiptremarks = 'Not Avaliable';
				if($fetch['reconsilation'] == 'notseen')
					$reconsilationtype1 = 'selected=selected';
				elseif($fetch['reconsilation'] == 'matched')
					$reconsilationtype2 = 'selected=selected';
				elseif($fetch['reconsilation'] == 'unmatched')
					$reconsilationtype3 = 'selected=selected';
				switch($fetch['paymentmode'])
				{
					case 'cash': $paymentdetails = 'Cash:'.' | '.gridtrim($receiptremarks).' '; break;
					case 'onlinetransfer': $paymentdetails = 'Online Transfer'.' | '.gridtrim($receiptremarks).' '; break;
					case 'chequeordd': $paymentdetails = 'Cheque / DD:'.' | '.'NO: '.$fetch['chequeno'].' | '.strtoupper(gridtrim($fetch['drawnon'])).' | '.changedateformat($fetch['chequedate']).''; break;
					case 'creditordebit': $paymentdetails = 'Credit / Debit Card'.' | '.gridtrim($receiptremarks).' '; break;
					case 'Netbanking': $paymentdetails = 'Online Transfer'.' | '.gridtrim($receiptremarks).' '; break;
				}

				if($fetch['invoiceno'] == '')
				{
					$invoiceno = $fetch['minvoiceno'];
					$businessname = $fetch['mbusinessname'];
					$status = $fetch['mstatus'];
				}
				else
				{
					$invoiceno = $fetch['invoiceno'];
					$businessname = $fetch['businessname'];
					$status = $fetch['status'];
				}
				$grid .= '<tr bgcolor='.$color.' align="left"  height="25px" >';
				$grid .= "<td height='25px'  width='2%' nowrap='nowrap' class='td-border-grid-new1' align='center'>".$slnocount."</td>";
				$grid .= "<td height='25px'  width='25%' nowrap='nowrap' class='td-border-grid-new1' align='left'><span style='color:#FF0000'><strong>".gridtrim($businessname)."</strong></span></td>";
				$grid .= "<td height='25px'  width='17%' nowrap='nowrap' class='td-border-grid-new1' align='left'>Invoice: ".trim($invoiceno)."</td>";
				$grid .= "<td height='25px' width='17%' nowrap='nowrap' class='td-border-grid-new1' align='left'>Receipt Date: ".changedateformat(trim($fetch['receiptdate']))."</td>";
				$grid .= "<td height='25px' width='12%' nowrap='nowrap' class='td-border-grid-new1' align='left'>Amount: ".$fetch['receiptamount']."</td>";
				$grid .= "<td height='25px' width='8%' nowrap='nowrap' class='td-border-grid-new1' align='left'><strong>".trim($status)."</strong></td>";				

				$grid .= '<td height="25px" width="18%" nowrap="nowrap" class="td-border-grid-new1" align="center" id="gridlist'.$slnocount.'"><select style="width: 150px;" id="reconsilation'.$slnocount.'" class="swiftselect" name="reconsilation'.$slnocount.'"><option value="notseen" '.$reconsilationtype1.'>NOT SEEN</option><option value="matched" '.$reconsilationtype2.'>MATCHED</option><option value="unmatched" '.$reconsilationtype3.'>UNMATCHED</option></select></td>';
				$grid .= "</tr>";
				
				$grid .= '<tr bgcolor='.$color.' align="left"  height="25px" >';
				$grid .= "<td height='25px' nowrap='nowrap' class='td-border-grid' align='center'><img src='../images/tooltip-arrow-image.gif' width='13' height='14'  onmouseover='generatetooltip(\"".$fetch['receiptslno']."\")' onmouseout='hidetooltip()' style='cursor:pointer'/></td>";
				$grid .= "<td height='25px' nowrap='nowrap' class='td-border-grid' align='left' colspan='5'><strong>Payment Details</strong>: ".$paymentdetails."</td>";
				$grid .= '<td height="25px" nowrap="nowrap" class="td-border-grid-new" align="center" id="gridbuttonlist'.$slnocount.'"><input name="reconsile" type="button" class="swiftchoicebutton-red" id="reconsile" value="Reconcile" onclick="submitreconsileform(\''.$fetch['receiptslno'].'\',\''.'gridlist'.$slnocount.'\',\''.'gridbuttonlist'.$slnocount.'\',\''.'reconsilation'.$slnocount.'\')" /></td>';
				$grid .= "</tr>";
				
				
		}
		$grid .= "</table>";
		$fetchcount = mysqli_num_rows($result);
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoresearchfilter(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
	
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;	
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
		
		case 'tooltip':
		{
			$lastslno = $_POST['lastslno'];
			$query5 = "select distinct inv_mas_receipt.receiptdate,inv_mas_receipt.receiptamount, inv_mas_receipt.privatenote, inv_mas_receipt.createddate as receiptcreteddate, inv_mas_receipt.createdby,inv_mas_receipt.module,inv_invoicenumbers.dealername, inv_invoicenumbers.netamount as invoicenetamount,inv_invoicenumbers.createddate as invoicecreateddate,inv_invoicenumbers.businessname,inv_invoicenumbers.invoiceno
			,inv_matrixinvoicenumbers.dealername as mdealername, inv_matrixinvoicenumbers.netamount as mnetamount,inv_matrixinvoicenumbers.createddate as mcreateddate,inv_matrixinvoicenumbers.businessname as mbusinessname,inv_matrixinvoicenumbers.invoiceno as minvoiceno
			from inv_mas_receipt 
			left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
			left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno 
			left join inv_mas_customer as cust on cust.slno = right(inv_invoicenumbers.customerid,5) 
			left join inv_mas_customer as mcust on mcust.slno = right(inv_matrixinvoicenumbers.customerid,5) 
			left join inv_mas_district as dist on dist.districtcode = cust.district 
			left join inv_mas_district as mdist on mdist.districtcode = mcust.district
			where inv_mas_receipt.slno = '".$lastslno."';";
			$fetch5 = runmysqlqueryfetch($query5);
			
			if($fetch5['module'] == 'user_module'|| $fetch5['module'] == 'Online'|| $fetch5['module'] == 'customer_module')
			{
				$quey13 = "select inv_mas_users.fullname as createdby from inv_mas_users where inv_mas_users.slno = '".$fetch5['createdby']."'";
				$fetch13 = runmysqlqueryfetch($quey13);
				$createdby = $fetch13['createdby'];
			}
			elseif($fetch5['module'] == "dealer_module")
			{
				$quey13 = "select inv_mas_dealer.businessname as createdby from inv_mas_dealer where inv_mas_dealer.slno = '".$fetch5['createdby']."'";
				$fetch13 = runmysqlqueryfetch($quey13);
				$createdby = $fetch13['createdby'];
			}
			if($fetch5['privatenote'] <> '')
				$privatenote = gridtrim($fetch5['privatenote']);
			else
				$privatenote = 'Not Avaliable';

			if($fetch5['invoiceno']!= '')
			{
				$businessname = $fetch5['businessname'];
				$dealername = $fetch5['dealername'];
				$createddate = $fetch5['invoicecreateddate'];
				$invoicenetamount = $fetch5['invoicenetamount'];
			}
			else
			{
				$businessname = $fetch5['mbusinessname'];
				$dealername = $fetch5['mdealername'];
				$createddate = $fetch5['mcreateddate'];
				$invoicenetamount = $fetch5['mnetamount'];
			}
			$tooltiptext = '<table width="100%" border="0"  cellspacing="0" cellpadding="0">';
			$tooltiptext .= '<tr><td ><strong>Company:</strong> '.$businessname.'</td></tr>';
			$tooltiptext .= '<tr><td ><strong>Sales Person:</strong> '.$dealername.'</td></tr>';
			$tooltiptext .= '<tr><td ><strong>Invoice Date:</strong> '.changedateformatwithtime($createddate).'</td></tr>';
			$tooltiptext .= '<tr><td ><strong>Invoice Amount:</strong> '.$invoicenetamount.'</td></tr>';
			$tooltiptext .= '<tr><td ><strong>Receipt Date:</strong> '.changedateformat($fetch5['receiptdate']).'</td></tr>';
			$tooltiptext .= '<tr><td><strong>Receipt Amount:</strong> '.$fetch5['receiptamount'].'</td></tr>';
			$tooltiptext .= '<tr><td><strong>Entered By:</strong> '.$createdby.'</td></tr>';
			$tooltiptext .= '<tr><td><strong>Entered Date:</strong> '.changedateformatwithtime($fetch5['receiptcreteddate']).'</td></tr>';
			$tooltiptext .= '<tr><td><strong>Private note:</strong> '.$privatenote.'</td></tr>';
			$tooltiptext .= '</table>';
			echo('1^'.$tooltiptext);
		}
		break;
		
		case 'reconsileform':
		{
			$lastslno = $_POST['lastslno'];
			$reconsilevalue = $_POST['reconsilevalue'];
			$query1 = "UPDATE inv_mas_receipt SET reconsilation ='".$reconsilevalue."' WHERE slno = '".$lastslno."';";
			$result = runmysqlquery($query1);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','75','".date('Y-m-d').' '.date('H:i:s')."','receipt_reconsile')";
			$eventresult = runmysqlquery($eventquery);
			$responsearray2 = array();
			$responsearray2['errorcode'] = '1';
			$responsearray2['errormsg'] = 'Succesfully Updated';
			$responsearray2['query'] = $query1;
			//echo('1^'.$result['initialpassword'].'^'.$result['passwordchanged']);
			echo(json_encode($responsearray2));
		}
		break;
	
}


?>