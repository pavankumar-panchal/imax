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

$switchtype = $_POST['switchtype'];
$localdate = '2014-02-01';
switch($switchtype)
{
	case 'searchfilter':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		$import = $_POST['import'];
		$region = $_POST['region'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		
		if($generatedby == "")
		{
			$modulepiece = "";
		}
		else
		{
			$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		}
		
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_mas_receipt.createdby = '".$generatedbysplit[0]."'");
		$regionpiece = ($region == "")?(""):(" AND (inv_invoicenumbers.regionid = '".$region."' OR inv_matrixinvoicenumbers.regionid = '".$region."') ");
		
		$resultcount = "select count(inv_mas_receipt.slno) as count from inv_mas_receipt 
		left join inv_invoicenumbers on inv_invoicenumbers.slno = inv_mas_receipt.invoiceno
		left join inv_matrixinvoicenumbers on inv_matrixinvoicenumbers.slno = inv_mas_receipt.matrixinvoiceno
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno
		where inv_mas_receipt.reconsilation = 'matched'  and inv_mas_receipt.receiptdate > '".$localdate."'
		". $regionpiece.$generatedpiece."  ";
		if($import == 'import')
		{
		 $resultcount .= " and inv_mas_receipt.imported = 'Y' order by inv_mas_receipt.slno desc";
		}
		else if($import == 'notimport')
		{
			$resultcount .= " and inv_mas_receipt.imported = 'N' order by inv_mas_receipt.slno desc";
		}
		else
		{
			$resultcount .= "order by inv_mas_receipt.slno desc"; 
		}
		
		$fetch10 = runmysqlqueryfetch($resultcount);
		$fetchresultcount = $fetch10['count'];
		
		
		if($showtype == 'all')
		{
			$limit = 100000;
		}
		else
		{
			$limit = 10;
		}
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
		$query = "select inv_mas_receipt.receiptdate,inv_mas_receipt.customerreference as dealerid ,inv_mas_receipt.dealerinvoiceno,inv_mas_receipt.matrixinvoiceno,inv_invoicenumbers.businessname,inv_dealer_invoicenumbers.businessname as dlrbusinessname,inv_matrixinvoicenumbers.businessname as mbusinessname,inv_mas_receipt.receiptamount,
        inv_invoicenumbers.invoiceno,inv_dealer_invoicenumbers.invoiceno as dlrinvoiceno,inv_matrixinvoicenumbers.invoiceno as minvoiceno,inv_mas_receipt.receiptremarks ,inv_mas_receipt.reconsilation,inv_mas_receipt.slno
		,inv_mas_receipt.paymentmode, inv_mas_receipt.createdby AS createdbyid ,inv_mas_receipt.module 
		from inv_mas_receipt 
		left join inv_invoicenumbers on inv_mas_receipt.invoiceno =  inv_invoicenumbers.slno 
		left join inv_matrixinvoicenumbers on inv_mas_receipt.matrixinvoiceno =  inv_matrixinvoicenumbers.slno 
		left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno 
		where inv_mas_receipt.reconsilation = 'matched' and inv_mas_receipt.receiptdate > '".$localdate."' ".$regionpiece.$generatedpiece." " ;
		
		if($import == 'import')
		{
		 $query .= " and inv_mas_receipt.imported = 'Y' order by inv_mas_receipt.slno DESC LIMIT ".$startlimit.",".$limit.";";
		}
		else if($import == 'notimport')
		{
			$query .= " and inv_mas_receipt.imported = 'N' order by inv_mas_receipt.slno DESC LIMIT ".$startlimit.",".$limit.";";
		}
		else
		{
			$query .= "order by inv_mas_receipt.slno DESC LIMIT ".$startlimit.",".$limit." ";
		}
		//echo $query;;
		$result = runmysqlquery($query);
		
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		$grid .= '<tr class="tr-grid-header">
					<td nowrap = "nowrap" class="td-border-grid" align="left"><input type="checkbox" name="chk" id="chk" onclick = "selectchk();"/><input type="hidden" name="groupvalue" id="groupvalue"  /></td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">ReceiptNo</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Company</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Date</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Receipt Amount</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Payment Mode</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Prepared By</td>
				</tr>';
		}
		$i_n = 0;		
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
			{
				$color = "#edf4ff";
			}
			else
			{
				$color = "#f7faff";
			}
			if($modulepiece == "")
			{
				if($fetch['module'] == 'user_module'|| $fetch['module'] == 'Online'|| $fetch['module'] == 'customer_module')
				{
					$queryfetch = "SELECT inv_mas_users.fullname as createdby from  inv_mas_users where slno = 
					'".$fetch['createdbyid']."';";
					$resultvalue = runmysqlqueryfetch($queryfetch);
				}
				else
				{
					$queryfetch = "SELECT inv_mas_dealer.businessname as createdby from  inv_mas_dealer where slno 
					= '".$fetch['createdbyid']."';";
					$resultvalue = runmysqlqueryfetch($queryfetch);
					
				}
			}
			elseif($modulepiece == "user_module")
			{
				$queryfetch = "SELECT inv_mas_users.fullname as createdby from  inv_mas_users where slno = '".$generatedbysplit[0]."';";
				$resultvalue = runmysqlqueryfetch($queryfetch);
			}
			elseif($modulepiece == "dealer_module")
			{
				$queryfetch = "SELECT inv_mas_dealer.businessname as createdby from  inv_mas_dealer where slno = '".$generatedbysplit[0]."';";
				$resultvalue = runmysqlqueryfetch($queryfetch);
			}

			if($fetch['dealerinvoiceno']!= "")
			{
				$businessname = trim($fetch['dlrbusinessname']);
				$invoiceno = trim($fetch['dlrinvoiceno']);
			}
			else if($fetch['dealerinvoiceno'] == "" && $fetch['businessname'] == "" && $fetch['mbusinessname'] == "")
			{
				$query1 = "select businessname from inv_mas_dealer where slno = ".$fetch['dealerid'];
				$fetch1 = runmysqlqueryfetch($query1);
				$businessname = $fetch1['businessname'];
				$invoiceno = "";
			}
			else{
				if($fetch['matrixinvoiceno']!="")
				{
					$businessname = trim($fetch['mbusinessname']);
					$invoiceno = trim($fetch['minvoiceno']);
				}
				else
				{
					$businessname = trim($fetch['businessname']);
					$invoiceno = trim($fetch['invoiceno']);
				}
			}
			
			$grid .= '<tr bgcolor='.$color.' class="gridrow1" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:50px'><input type='checkbox' name='slno[]' id='slno' value = '".$fetch['slno']."'/></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".($fetch['slno'])."</td>";
			$grid .= "<td class='td-border-grid' align='left' >".$businessname."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$invoiceno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat(trim($fetch['receiptdate']))."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['receiptamount']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['paymentmode']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$resultvalue['createdby']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		
		//echo $fetchresultcount;
		if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
			$linkgrid .= '<table><tr><td ><div align ="left" style="padding-right:10px"><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer" class="resendtext">Show More Records >> </a><a onclick ="getmoreinvoicedetails(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
		
	
			echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
}