<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if (imaxgetcookie('userid') <> '')
	$userid = imaxgetcookie('userid');
else {
	echo (json_encode('Thinking to redirect'));
	exit;
}

include('../inc/checksession.php');
include('../inc/checkpermission.php');

$type = $_POST['type'];
$lastslno = $_POST['lastslno'];
$productlastslno = $_POST['productlastslno'];
$billlastslno = $_POST['billlastslno'];


switch ($type) {
	case 'productsave': {
			$responsearray = array();
			$cusbillnumber = $_POST['cusbillnumber'];
			$productcode = $_POST['productcode'];
			$productquantity = $_POST['productquantity'];
			$productamount = $_POST['productamount'];
			$usagetype = $_POST['usagetype'];
			$purchasetype = $_POST['purchasetype'];
			$dealerid = $_POST['dealerid'];
			$remarks = $_POST['remarks'];
			$billdate = datetimelocal('d-m-Y') . " " . datetimelocal('H:i');
			$addlicence = $_POST['addlicence'];
			$scheme = $_POST['scheme'];
			$productrate = $_POST['productrate'];

			$firstbillnumber = '';
			if ($productlastslno == '') {
				$query = "SELECT count(*) AS count FROM inv_bill WHERE slno = '" . $cusbillnumber . "'";
				$fetch = runmysqlqueryfetch($query);
				if ($fetch['count'] == 0 && $cusbillnumber == 'New') {
					$query = "INSERT INTO inv_bill(dealerid,total,billstatus,remarks,billdate,userid) VALUES('" . $dealerid . "','0','justcreated','" . $remarks . "','" . changedateformatwithtime($billdate) . "','" . $userid . "');";
					$result = runmysqlquery($query);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','35','" . date('Y-m-d') . ' ' . date('H:i:s') . "','" . $dealerid . "')";
					$eventresult = runmysqlquery($eventquery);

					$fetch1 = runmysqlqueryfetch("SELECT MAX(slno) AS cusbillnumber FROM inv_bill;");
					$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount, productrate , usagetype , purchasetype ,scheme ) VALUES ('" . $fetch1['cusbillnumber'] . "','" . $productcode . "','" . $productquantity . "','" . $productamount . "','" . $productrate . "','" . $usagetype . "','" . $purchasetype . "','" . $scheme . "');";
					$result = runmysqlquery($query);
					$firstbillnumber = $fetch1['cusbillnumber'];
				} else {
					$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount ,productrate, usagetype , purchasetype ,scheme ) VALUES ('" . $cusbillnumber . "','" . $productcode . "','" . $productquantity . "','" . $productamount . "','" . $productrate . "','" . $usagetype . "','" . $purchasetype . "','" . $scheme . "');";
					$result = runmysqlquery($query);
				}
			} else {
				$query0 = "SELECT billstatus FROM inv_bill WHERE slno = '" . $cusbillnumber . "'";
				$fetch0 = runmysqlqueryfetch($query0);
				if ($fetch0['billstatus'] == 'successful') {
					$query = "UPDATE inv_billdetail SET  productamount = '" . $productamount . "' ,productrate = '" . $productrate . "' WHERE slno = '" . $productlastslno . "'";
					$result = runmysqlquery($query);
				} else {
					$query = "UPDATE inv_billdetail SET productcode = '" . $productcode . "' , productquantity = '" . $productquantity . "', productamount = '" . $productamount . "' ,productrate = '" . $productrate . "', usagetype = '" . $usagetype . "' , purchasetype = '" . $purchasetype . "'  , scheme = '" . $scheme . "' WHERE slno = '" . $productlastslno . "'";
					$result = runmysqlquery($query);
				}
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','36','" . date('Y-m-d') . ' ' . date('H:i:s') . "','" . $dealerid . "')";
				$eventresult = runmysqlquery($eventquery);
			}
			$responsearray['errormessage'] = "1^ Record Saved Successfully. ^" . $firstbillnumber;
			echo (json_encode($responsearray));
			//echo("1^ Record Saved Successfully. ^".$firstbillnumber);
		}
		break;

	case 'productdelete': {
			$responsearray = array();
			$billlastslno = $_POST['cusbillnumber'];
			$recordflag = deleterecordcheck($billlastslno, 'cusbillnumber', 'inv_dealercard');
			if ($recordflag == true) {
				$query = "DELETE FROM inv_billdetail WHERE slno = '" . $productlastslno . "'";
				$result = runmysqlquery($query);

				$responsearray['errormessage'] = "2^ Record Deleted Successfully.";
			} else {
				$responsearray['errormessage'] = "3^" . "Data cannot be deleted as the cards are attached to it.";
				//echo("3^"."Bill cannot be deleted as the cards are attached to it.");
			}
			echo (json_encode($responsearray));
			//echo("2^ Record Deleted Successfully.");
		}
		break;

	case 'generatedealerlist': {
			$generatedealerlistarray = array();
			$relyonexcecutive_type = $_POST['relyonexcecutive_type'];
			$login_type = $_POST['login_type'];
			$dealerregion = $_POST['dealerregion'];
			$relyonexcecutive_typepiece = ($relyonexcecutive_type == "") ? ("") : (" AND inv_mas_dealer.relyonexecutive = '" . $relyonexcecutive_type . "' ");
			$login_typepiece = ($login_type == "") ? ("") : (" AND inv_mas_dealer.disablelogin = '" . $login_type . "' ");
			$dealerregionpiece = ($dealerregion == "") ? ("") : (" AND inv_mas_dealer.region = '" . $dealerregion . "' ");
			$query = "SELECT slno,businessname FROM inv_mas_dealer where slno <> '532568855' " . $relyonexcecutive_typepiece . $login_typepiece . $dealerregionpiece . " ORDER BY businessname";
			$result = runmysqlquery($query);
			$count = 0;
			while ($fetch = mysqli_fetch_array($result)) {
				$generatedealerlistarray[$count] = $fetch['businessname'] . '^' . $fetch['slno'];
				$count++;
			}
			echo (json_encode($generatedealerlistarray));
		}
		break;


	case 'generateproductgrid': {
			$cusbillnumber = $_POST['cusbillnumber'];
			$query = "SELECT inv_billdetail.slno,inv_billdetail.cusbillnumber,inv_mas_product.productname,
inv_billdetail.productquantity,inv_billdetail.productrate,inv_billdetail.productamount,inv_billdetail.usagetype,inv_billdetail.purchasetype, 
inv_mas_scheme.schemename FROM inv_billdetail LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_billdetail.productcode
LEFT JOIN inv_mas_scheme ON inv_mas_scheme.slno = inv_billdetail.scheme
WHERE inv_billdetail.cusbillnumber = '" . $cusbillnumber . "'";
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid">Bill Number</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Quantity</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Rate</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Amount</td>
		<td nowrap = "nowrap" class="td-border-grid">Usage Type</td>
		<td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">scheme</td></tr>';
			$i_n = 0;
			$slno = 0;
			$result = runmysqlquery($query);
			while ($fetch = mysqli_fetch_row($result)) {
				$i_n++;
				$slno++;
				$color;
				if ($i_n % 2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr class="gridrow" bgcolor=' . $color . ' onclick="productgridtoform(\'' . $fetch[0] . '\')">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>" . $slno . "</td>";
				for ($i = 1; $i < count($fetch); $i++) {

					$grid .= "<td nowrap='nowrap' class='td-border-grid'>" . $fetch[$i] . "</td>";
				}
				$grid .= "</tr>";
			}
			$grid .= "</table>";
			echo ("1^" . $grid);
		}
		break;

	case 'generategrid': {
			$startlimit = $_POST['startlimit'];
			$slno = $_POST['slno'];
			$showtype = $_POST['showtype'];
			$resultcount = "SELECT inv_bill.slno,inv_bill.dealerid,inv_bill.billdate,inv_bill.remarks,inv_bill.total,inv_bill.taxamount,
		inv_bill.netamount,inv_mas_users.username FROM inv_bill left join inv_mas_users on inv_mas_users.slno = inv_bill.userid WHERE dealerid = '" . $lastslno . "' and billstatus <> 'justcreated' order by billdate ";
			$resultfetch = runmysqlquery($resultcount);
			$fetchresultcount = mysqli_num_rows($resultfetch);
			if ($showtype == 'all')
				$limit = 100000;
			else
				$limit = 10;
			if ($startlimit == '') {
				$startlimit = 0;
				$slno = 0;
			} else {
				$startlimit = $slno;
				$slno = $slno;
			}
			$query = "SELECT inv_bill.slno,inv_bill.dealerid,inv_bill.billdate,inv_bill.remarks,inv_bill.total,inv_bill.taxamount,
		inv_bill.netamount,inv_mas_users.username FROM inv_bill left join inv_mas_users on inv_mas_users.slno = inv_bill.userid WHERE dealerid = '" . $lastslno . "' and billstatus <> 'justcreated' order by billdate DESC LIMIT " . $startlimit . "," . $limit . ";";
			if ($startlimit == 0) {
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Bill Number</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Invoices</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Dealer ID</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Bill Date</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Total Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Entered By</td></tr>';
			}
			$i_n = 0;
			$result = runmysqlquery($query);
			while ($fetch = mysqli_fetch_array($result)) {
				$i_n++;
				$slno++;
				$color;
				if ($i_n % 2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";

				$invoicequery = "select count(invoiceno) as invoicecount from inv_dealer_invoicenumbers where cusbillnumber = '" . $fetch['slno'] . "';";
				$invfetch = runmysqlqueryfetch($invoicequery);
				$totalinvoice = $invfetch['invoicecount'];

				$grid .= '<tr class="gridrow" bgcolor=' . $color . ' onclick="gridtoform(\'' . $fetch[1] . '\',\'' . $fetch[0] . '\'); gridtab2(\'1\',\'tabgroupgrid\',\'&nbsp; &nbsp;Bill Entry\');" align ="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $slno . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['slno'] . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $totalinvoice . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['dealerid'] . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . changedateformatwithtime($fetch['billdate']) . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . wordwrap($fetch['remarks'], 28, "<br>\n") . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['total'] . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['taxamount'] . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['netamount'] . "</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch['username'] . "</td>";
				/*for($i = 0; $i < count($fetch); $i++)
						 {*/

				/*if($i == 2)
							$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".changedateformatwithtime($fetch[$i])."</td>";
							else if($i == 3)
							$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim(wordwrap($fetch[$i],20,"<br>\n"))."</td>";
							else
							$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>".gridtrim($fetch[$i])."</td>";*/
				//}
				$grid .= "</tr>";
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if ($slno >= $fetchresultcount)

				$linkgrid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoreviewpurchasedatagrid(\'' . $startlimit . '\',\'' . $slno . '\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoreviewpurchasedatagrid(\'' . $startlimit . '\',\'' . $slno . '\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';

			echo '1^' . $grid . '^' . $linkgrid;
		}
		break;

	case 'productgridtoform': {
			$productgridtoformarray = array();
			$query = "SELECT slno,cusbillnumber,productcode,productquantity,productamount,productrate,usagetype,purchasetype,addlicence,scheme FROM inv_billdetail WHERE slno = '" . $productlastslno . "'";
			$fetch = runmysqlqueryfetch($query);
			$productgridtoformarray['errorcode'] = '1';
			$productgridtoformarray['slno'] = $fetch['slno'];
			$productgridtoformarray['cusbillnumber'] = $fetch['cusbillnumber'];
			$productgridtoformarray['productcode'] = $fetch['productcode'];
			$productgridtoformarray['productquantity'] = $fetch['productquantity'];
			$productgridtoformarray['productamount'] = $fetch['productamount'];
			$productgridtoformarray['usagetype'] = $fetch['usagetype'];
			$productgridtoformarray['purchasetype'] = $fetch['purchasetype'];
			$productgridtoformarray['scheme'] = $fetch['scheme'];
			$productgridtoformarray['productrate'] = $fetch['productrate'];
			echo (json_encode($productgridtoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['cusbillnumber'].'^'.$fetch['productcode'].'^'.$fetch['productquantity'].'^'.$fetch['productamount'].'^'.$fetch['usagetype'].'^'.$fetch['purchasetype'].'^'.$fetch['scheme']);
		}
		break;

	case 'gridtoform': {
			$gridtoformarray = array();
			$query = "SELECT inv_bill.slno,inv_bill.dealerid,inv_bill.billdate,inv_bill.remarks,inv_bill.total,inv_bill.taxamount,inv_bill.netamount,inv_mas_dealer.businessname, inv_mas_users.fullname,inv_bill.billstatus FROM inv_bill LEFT JOIN inv_mas_users ON inv_bill.userid = inv_mas_users.slno LEFT JOIN inv_mas_dealer ON inv_bill.dealerid = inv_mas_dealer.slno WHERE inv_bill.dealerid = '" . $lastslno . "' AND inv_bill.slno = '" . $billlastslno . "'";
			$fetch = runmysqlqueryfetch($query);

			$query1 = "SELECT inv_billdetail.slno,inv_billdetail.cusbillnumber,inv_mas_product.productname, 
		inv_billdetail.productquantity,inv_billdetail.productrate, inv_billdetail.productamount, inv_billdetail.usagetype, inv_billdetail.purchasetype, inv_mas_scheme.schemename
		FROM inv_billdetail LEFT JOIN inv_mas_product ON inv_billdetail.productcode = inv_mas_product.productcode 
		LEFT JOIN inv_mas_scheme ON inv_mas_scheme.slno = inv_billdetail.scheme 
		LEFT JOIN inv_bill on inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '" . $billlastslno . "';";

			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left">
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left"> Bill Number</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Billed Quantity</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Product Rate</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Billed Amount</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Usage Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Purchase Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Scheme</td></tr>';
			$i_n = 0;
			$slno = 0;
			$result1 = runmysqlquery($query1);
			while ($fetch1 = mysqli_fetch_row($result1)) {
				$i_n++;
				$slno++;
				$color;
				if ($i_n % 2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr class="gridrow" bgcolor=' . $color . ' onclick="productgridtoform(\'' . $fetch1[0] . '\')" align ="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $slno . "</td>";
				for ($i = 1; $i < count($fetch1); $i++) {
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $fetch1[$i] . "</td>";
				}
				$grid .= "</tr>";
			}
			$grid .= "</table>";

			$invoicequery = "select * from inv_dealer_invoicenumbers where cusbillnumber = '" . $billlastslno . "';";
			$invoiceresult = runmysqlquery($invoicequery);
			$invoicecount = mysqli_num_rows($invoiceresult);
			if ($invoicecount > 0) {
				$invoicegrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$invoicegrid .= '<tr class="tr-grid-header" align ="left">
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Date</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Number</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Amount</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Status</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Generated By</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Action</td></tr>';
				$i_n1 = 0;
				$slno1 = 0;
				while ($invoicefetch = mysqli_fetch_array($invoiceresult)) {
					$i_n1++;
					$slno1++;
					$color1;
					if ($i_n1 % 2 == 0)
						$color1 = "#edf4ff";
					else
						$color1 = "#f7faff";
					$invoicegrid .= '<tr class="gridrow1" bgcolor=' . $color1 . '>';
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . $slno1 . "</td> ";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . changedateformatwithtime($invoicefetch['createddate']) . "</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . $invoicefetch['invoiceno'] . "</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . $invoicefetch['netamount'] . "</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . $invoicefetch['status'] . "</td>";
					$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>" . $invoicefetch['createdby'] . "</td>";
					$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewdealerinvoice(\'' . $invoicefetch['slno'] . '\');" class="resendtext"> View >></a> </td>';
					$invoicegrid .= "</tr>";
				}
				$invoicequery1 = "select status from inv_dealer_invoicenumbers where cusbillnumber = '" . $billlastslno . "' and description!='' order by slno desc limit 1;";
				$invoicefetch1 = runmysqlqueryfetch($invoicequery1);
				$invoicestatus = $invoicefetch1['status'];
			}

			$gridtoformarray['errorcode'] = '1';
			$gridtoformarray['slno'] = $fetch['slno'];
			$gridtoformarray['dealerid'] = $fetch['dealerid'];
			$gridtoformarray['billdate'] = changedateformatwithtime($fetch['billdate']);
			$gridtoformarray['remarks'] = $fetch['remarks'];
			$gridtoformarray['total'] = $fetch['total'];
			$gridtoformarray['taxamount'] = $fetch['taxamount'];
			$gridtoformarray['netamount'] = $fetch['netamount'];
			$gridtoformarray['grid'] = $grid;
			$gridtoformarray['invoicegrid'] = $invoicegrid;
			$gridtoformarray['businessname'] = $fetch['businessname'];
			$gridtoformarray['fullname'] = $fetch['fullname'];
			$gridtoformarray['userid'] = $fetch['userid'];
			$gridtoformarray['billstatus'] = $fetch['billstatus'];
			$gridtoformarray['invoicecount'] = $invoicecount;
			$gridtoformarray['invoicestatus'] = $invoicestatus;

			echo (json_encode($gridtoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['dealerid'].'^'.changedateformatwithtime($fetch['billdate']).'^'.$fetch['remarks'].'^'.$fetch['total'].'^'.$fetch['taxamount'].'^'.$fetch['netamount'].'^'.$grid.'^'.$fetch['businessname'].'^'.$fetch['fullname'].'^'.$userid.'^'.$fetch['billstatus']);
		}
		break;

	case 'getproductgroup': {
			$productcode = $_POST['productcode'];
			$query0 = "select subgroup from inv_mas_product where productcode = '" . $productcode . "'";
			$fetch0 = runmysqlqueryfetch($query0);
			$subgroup = $fetch0['subgroup'];

			$getamountarray['errormessage'] = '1^' . $subgroup;
			echo (json_encode($getamountarray));

		}
		break;

	case 'getamount': {
			$getamountarray = array();
			$dealerid = $_POST['dealerid'];
			$scheme = $_POST['scheme'];
			$usagetype = $_POST['usagetype'];
			$purchasetype = $_POST['purchasetype'];
			$productcode = $_POST['productcode'];
			$productquantity = $_POST['productquantity'];

			if ($purchasetype == 'new' && $usagetype == 'singleuser') {
				$price = 'newsuprice';
				$revenueshare = 'revenuesharenewsale';
			}
			if ($purchasetype == 'new' && $usagetype == 'multiuser') {
				$price = 'newmuprice';
				$revenueshare = 'revenuesharenewsale';
			}
			if ($purchasetype == 'new' && $usagetype == 'additionallicense') {
				$price = 'newaddlicenseprice';
				$revenueshare = 'revenuesharenewsale';
			}
			if ($purchasetype == 'updation' && $usagetype == 'singleuser') {
				$price = 'updatesuprice';
				$revenueshare = 'revenueshareupsale';
			}
			if ($purchasetype == 'updation' && $usagetype == 'multiuser') {
				$price = 'updatemuprice';
				$revenueshare = 'revenueshareupsale';
			}
			if ($purchasetype == 'updation' && $usagetype == 'additionallicense') {
				$price = 'updationaddlicenseprice';
				$revenueshare = 'revenueshareupsale';
			}
			if ($price <> '') {
				$query = "SELECT " . $revenueshare . " as revenueshare FROM inv_mas_dealer WHERE slno = '" . $lastslno . "'";
				$fetch = runmysqlqueryfetch($query);
				$revenueshare = $fetch['revenueshare'];

				$query = "Select " . $price . " AS amount from inv_schemepricing where product = '" . $productcode . "' and scheme = '" . $scheme . "';";
				$fetch = runmysqlqueryfetch($query);
				if ($fetch['amount'] == 'NA') {
					$getamountarray['errormessage'] = '2^This Usage/Purchase type is invalid for Selected Scheme.';
					//echo('2^This Usage/Purchase type is invalid for Selected Scheme.');
				} else {
					$productrate = round($fetch['amount'] - ($fetch['amount'] * ($revenueshare / 100)));

					//$totalamount = $fetch['amount']*$productquantity;
					//$totalcalamount = round($totalamount - ($totalamount * ($revenueshare/100)));
					$totalcalamount = $productrate * $productquantity;
					$getamountarray['errormessage'] = '1^' . $totalcalamount . '^' . $productrate;
					//echo('1^'.$totalcalamount);
				}

			}

			echo (json_encode($getamountarray));
		}
		break;

	case 'save': {
			$responsearray1 = array();
			$cusbillnumber = $_POST['cusbillnumber'];
			$billdate = $_POST['billdate'];
			$dealerid = $_POST['dealerid'];
			$total = $_POST['total'];
			$taxamount = $_POST['taxamount'];
			$netamount = $_POST['netamount'];
			$remarks = $_POST['remarks'];
			//$totalcredit = $_POST['totalcredit'];

			$query = "SELECT count(*) AS count FROM inv_bill WHERE slno = '" . $cusbillnumber . "'";
			$fetch = runmysqlqueryfetch($query);

			$query0 = "SELECT billstatus FROM inv_bill WHERE slno = '" . $cusbillnumber . "'";
			$fetch0 = runmysqlqueryfetch($query0);
			$billstatus = $fetch0['billstatus'];

			$query1 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '" . $dealerid . "'";
			$fetchresult = runmysqlqueryfetch($query1);
			$totalcredit = $fetchresult['totalcredit'];


			if ($totalcredit == '') {
				$totalcreditavl = 0;
			} else
				//$totalcreditavl = $totalcredit - $billedamount;
				$totalcreditavl = getcurrentcredit($dealerid);
			if ($totalcreditavl >= $netamount) {
				if ($billstatus == 'successful') {
					$query1 = "UPDATE inv_bill SET total = '" . $total . "',remarks = '" . $remarks . "',netamount = '" . $netamount . "',taxamount = '" . $taxamount . "' WHERE slno = '" . $cusbillnumber . "'";
					$result1 = runmysqlquery($query1);
					$responsearray1['errormessage'] = "1^ Record Saved Successfully.";
				} else {
					$query = "UPDATE inv_bill SET dealerid = '" . $dealerid . "',total = '" . $total . "',remarks = '" . $remarks . "',netamount = '" . $netamount . "',taxamount = '" . $taxamount . "',billstatus = 'pending' WHERE slno = '" . $cusbillnumber . "'";
					$result = runmysqlquery($query);
					$responsearray1['errormessage'] = "1^ Record Saved Successfully.";
				}
			} else {
				$responsearray1['errormessage'] = "3^" . "Insufficient Credits. Please enhance the credit to proceed with.";
				//echo("3^"."Insufficient Credits. Please enhance the credit to proceed with.");
			}
			echo (json_encode($responsearray1));
		}
		break;

	case 'delete': {
			$responsearray1 = array();
			$recordflag = deleterecordcheck($billlastslno, 'cusbillnumber', 'inv_dealercard');
			if ($recordflag == true) {
				$query = "DELETE FROM inv_bill WHERE slno = '" . $billlastslno . "'";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','37','" . date('Y-m-d') . ' ' . date('H:i:s') . "')";
				$eventresult = runmysqlquery($eventquery);
				$responsearray1['errormessage'] = "2^ Record Deleted Successfully.";
				//echo("2^ Record Deleted Successfully.");
			} else {
				$responsearray1['errormessage'] = "3^" . "Bill cannot be deleted as the cards are attached to it.";
				//echo("3^"."Bill cannot be deleted as the cards are attached to it.");
			}
			echo (json_encode($responsearray1));
		}
		break;

	case 'searchbybills': {
			$searchbybillsarray = array();
			$cusbillnumber = $_POST['cusbillnumber'];
			$query = "SELECT count(*) AS count from inv_bill  where slno = '" . $cusbillnumber . "';";
			$fetch = runmysqlqueryfetch($query);
			$count = $fetch['count'];
			if ($count > 0) {
				$query = "SELECT inv_bill.slno,inv_bill.dealerid,inv_bill.billdate,inv_bill.remarks,inv_bill.total,inv_bill.taxamount,inv_bill.netamount,inv_billdetail.scheme,inv_bill.billstatus  FROM inv_bill LEFT JOIN inv_billdetail on  inv_billdetail.cusbillnumber = inv_bill.slno WHERE inv_bill.slno = '" . $cusbillnumber . "' and billstatus <> 'justcreated';";
				$fetchresult = runmysqlqueryfetch($query);
				$query2 = "SELECT businessname FROM inv_mas_dealer WHERE slno = '" . $fetchresult['dealerid'] . "'";
				$fetch2 = runmysqlqueryfetch($query2);
				$query1 = "SELECT inv_billdetail.slno , inv_billdetail.cusbillnumber , inv_mas_product.productname , inv_billdetail.productquantity , inv_billdetail.productamount , inv_billdetail.usagetype , inv_billdetail.purchasetype, inv_mas_scheme.schemename FROM inv_billdetail LEFT JOIN inv_bill on  inv_bill.slno = inv_billdetail.cusbillnumber LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_billdetail.productcode LEFT JOIN inv_mas_scheme ON inv_mas_scheme.slno = inv_billdetail.scheme  WHERE inv_bill.slno = '" . $cusbillnumber . "';";
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Bill Number</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Billed Quantity</td><td nowrap = "nowrap" class="td-border-grid">Billed Amount</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td></tr>';
				$i_n = 0;
				$slno = 0;
				$result = runmysqlquery($query1);
				while ($fetch = mysqli_fetch_row($result)) {
					$i_n++;
					$slno++;
					$color;
					if ($i_n % 2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					$grid .= '<tr class="gridrow" bgcolor=' . $color . ' onclick="productgridtoform(\'' . $fetch[0] . '\')">';
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>" . $slno . "</td>";
					for ($i = 1; $i < count($fetch); $i++) {
						if ($i == 3)
							$grid .= "<td nowrap='nowrap' class='td-border-grid'>" . changedateformatwithtime($fetch[$i]) . "</td>";
						else
							$grid .= "<td nowrap='nowrap' class='td-border-grid'>" . $fetch[$i] . "</td>";
					}
					$grid .= "</tr>";
				}
				$grid .= "</table>";
				$searchbybillsarray['errorcode'] = '1';
				$searchbybillsarray['slno'] = $fetchresult['slno'];
				$searchbybillsarray['dealerid'] = $fetchresult['dealerid'];
				$searchbybillsarray['billdate'] = changedateformatwithtime($fetchresult['billdate']);
				$searchbybillsarray['remarks'] = $fetchresult['remarks'];
				$searchbybillsarray['total'] = $fetchresult['total'];
				$searchbybillsarray['taxamount'] = $fetchresult['taxamount'];
				$searchbybillsarray['netamount'] = $fetchresult['netamount'];
				$searchbybillsarray['grid'] = $grid;
				$searchbybillsarray['scheme'] = $fetch2['scheme'];
				$searchbybillsarray['billstatus'] = $fetchresult['billstatus'];

				//echo('1^'.$fetchresult['slno'].'^'.$fetchresult['dealerid'].'^'.changedateformatwithtime($fetchresult['billdate']).'^'.$fetchresult['remarks'].'^'.$fetchresult['total'].'^'.$fetchresult['taxamount'].'^'.$fetchresult['netamount'].'^'.$grid.'^'.$fetch2['businessname'].'^'.$fetchresult['scheme'].'^'.$fetchresult['billstatus']);
			} else {
				$searchbybillsarray['errormessage'] = '1';
				$searchbybillsarray['slno'] = '';
				$searchbybillsarray['dealerid'] = '';
				$searchbybillsarray['billdate'] = '';
				$searchbybillsarray['remarks'] = '';
				$searchbybillsarray['total'] = '';
				$searchbybillsarray['taxamount'] = '';
				$searchbybillsarray['netamount'] = '';
				$searchbybillsarray['grid'] = '';
				$searchbybillsarray['scheme'] = '';
				$searchbybillsarray['billstatus'] = '';
				//echo('2^'.'Not Available'.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			}
			echo (json_encode($searchbybillsarray));
		}
		break;

	case 'generatebills': {
			$generatebillsarray = array();
			$query = "SELECT MAX(slno) AS lastbillnumber FROM inv_bill;";
			$fetch = runmysqlqueryfetch($query);
			$lastbillnumber = $fetch['lastbillnumber'];
			$nextbillnumber = $lastbillnumber + 1;
			$generatebillsarray['errormessage'] = '1^' . $nextbillnumber;
			echo (json_encode($generatebillsarray));
			//echo('1^'.$nextbillnumber);
		}
		break;

	case 'generateinv'; {
			$gstcheck = $_POST['gstcheck'];
			$dealerid = $_POST['dealerid'];
			$paymentamount = $_POST['paymentamount'];
			$paymentremarks = $_POST['paymentremarks'];
			$dealerpurchasebillno = $_POST['dealerpurchasebillno'];
			$product2 = $_POST['product2'];
			$servicelistsplit = explode('#', $product2);
			$itemamount = $_POST['itemamount'];
			$itemamountsplit = explode('~', $itemamount);
			$invoiceremarks = $_POST['invoiceremarks'];

			$query0 = "SELECT  count(*) AS count FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '" . $dealerpurchasebillno . "';";
			$fetch0 = runmysqlqueryfetch($query0);
			$count0 = $fetch0['count'];
			if ($count0 > 0) {
				$query3 = "select count(*) as count from inv_dealercard where cusbillnumber = '" . $dealerpurchasebillno . "'";
				$fetch3 = runmysqlqueryfetch($query3);
				$count3 = $fetch3['count'];
				if ($count3 > 0) {
					$fetch4 = runmysqlqueryfetch("select * from inv_bill where slno = '" . $dealerpurchasebillno . "'");
					$dealerid1 = $fetch4['dealerid'];

					$invoicequery = "select count(*) as invoicecount from inv_dealer_invoicenumbers where cusbillnumber = '" . $dealerpurchasebillno . "'";
					$invoicedetailsfetch = runmysqlqueryfetch($invoicequery);
					$invoicecount = $invoicedetailsfetch['invoicecount'];

					if ($invoicecount > 0) {
						$query = "select status from inv_dealer_invoicenumbers where cusbillnumber = '" . $dealerpurchasebillno . "' and description!='' order by slno desc limit 1;";
						$fetch = runmysqlqueryfetch($query);
						$status = $fetch['status'];
					}

					$serviceamount = 0;
					$proamount = 0;
					if ($invoicecount == 0 || ($invoicecount > 0 && $status == 'CANCELLED')) {
						$proamount = $fetch4['total'];
						$taxamount = $fetch4['taxamount'];
						if (!empty($product2)) {
							for ($i = 0; $i < count((array) $itemamountsplit); $i++) {
								$serviceamount += $itemamountsplit[$i];
							}
						}
					} else {
						if (!empty($product2)) {
							for ($i = 0; $i < count((array) $itemamountsplit); $i++) {
								$serviceamount += $itemamountsplit[$i];
							}
						}
					}

					//$netamount = $fetch4['netamount'];

					//for billing selected dealer details
					$fetch5 = runmysqlqueryfetch("select *,inv_mas_dealer.slno as dealerid,statename,districtname,inv_mas_district.statecode from inv_mas_dealer
				left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.slno
				left join inv_mas_state on inv_mas_district.statecode = inv_mas_state.statecode
				 where inv_mas_dealer.slno = '" . $dealerid1 . "'");
					$dealerslno = $fetch5['dealerid'];
					$businessname = $fetch5['billingname'];
					$contactperson = $fetch5['contactperson'];
					$place = $fetch5['place'];
					$district = $fetch5['district'];
					$pincode = $fetch5['pincode'];
					$stdcode = $fetch5['stdcode'];
					$phone = $fetch5['phone'];
					$cell = $fetch5['cell'];
					$emailid = $fetch5['emailid'];
					$pincode1 = ($fetch5['pincode'] == '') ? '' : ' Pin - ' . $fetch5['pincode'];
					$address = $fetch5['address'] . ', ' . $place . ', ' . $fetch5['districtname'] . ', ' . $fetch5['statename'] . $pincode1;
					//$address = $fetch5['address'].','.$place.','.$fetch5['districtname'].','.$fetch5['statename'].'-'.$pincode;
					$gst_no = $fetch5['gst_no'];

					//Get the current exec delaer in  dealer details
					$query6 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '" . $dealerid . "';";
					$fetch6 = runmysqlqueryfetch($query6);
					$dealername = $fetch6['billingname'];
					$dealerregion = $fetch6['region'];
					$dealeremailid = $fetch6['dealeremailid'];
					$regionid = $fetch6['regionid'];
					$branchid = $fetch6['branchid'];
					$dealerdistrict = $fetch6['dealerdistrict'];

					//Added for Branch Name correction in invoices	
					$query_branch_name = "select branchname from inv_mas_branch where slno = $branchid ";
					$fetch_branch_name = runmysqlqueryfetch($query_branch_name);
					$dealer_branch_name = $fetch_branch_name['branchname'];

					// //Added for Branch Name correction in invoices	Ends
					// $query7 = "select * from inv_billdetail where cusbillnumber = '".$dealerpurchasebillno."' ";
					// $result7 = runmysqlquery($query7);
					// while($fetch7 = mysqli_fetch_array($result7))
					// {
					// 	$productcode[] = $fetch7['productcode'];
					// 	$productquantity[] = $fetch7['productquantity'];
					// 	$productamount[] = $fetch7['productamount'];
					// }
					// $productcodearray = implode('#',$productcode);
					// $productquantityarray = implode(',',$productquantity);
					// $totalproductpricearray = implode('*',$productamount);

					//Get the next record serial number for insertion in invoicenumbers table
					// $query8 = "select ifnull(max(slno),0) + 1 as billref from inv_dealer_invoicenumbers";
					// $resultfetch8 = runmysqlqueryfetch($query8);
					// $onlineinvoiceslno = $resultfetch8['billref'];

					//to select state code of customer
					$querystatecode = "select statecode from inv_mas_district where districtcode  = '" . $district . "';";
					$querystatefetch = runmysqlqueryfetch($querystatecode);

					$statecode = $querystatefetch['statecode'];
					$querystategstcode = "select state_gst_code from inv_mas_state where statecode = '" . $statecode . "';";

					$customer_stategstno_fetch = runmysqlqueryfetch($querystategstcode);
					$customer_gstcode = $customer_stategstno_fetch['state_gst_code'];

					//get cgst and sgst detail
					$year = '2022';
					// if($customer_gstcode == '29')
					// {   
					// 	$state_info = 'L';
					// 	$varState = '2022DL';

					// 	$queryonlineinv = "select ifnull(max(onlineinvoiceno),442)+ 1 as invoicenotobeinserted from inv_dealer_invoicenumbers where invoiceno like '%".$varState."%'";

					// 	//Get the next invoice number from invoicenumbers table, for this new_invoice
					// 	$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
					// 	$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
					// 	$onlineinvoiceno=(string)$onlineinvoiceno;
					// 	$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
					// 	$invoicenoformat = 'RSL'.$year.'D'.$state_info.''.$onlineinvoiceno;
					// }
					// else
					// {
					// 	//$onlineinvoiceno='000100';
					// 	$state_info = 'I';
					// 	$varState = '2022DI';

					// 	$queryonlineinv = "select ifnull(max(onlineinvoiceno),924)+ 1 as invoicenotobeinserted from inv_dealer_invoicenumbers where invoiceno like '%".$varState."%'";	

					// 	$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
					// 	$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
					// 	$onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
					// 	$onlineinvoiceno=(string)$onlineinvoiceno;		
					// 	$invoicenoformat = 'RSL'.$year.'D'.$state_info.''.$onlineinvoiceno;
					// }

					$invoicecreated_date = date('Y-m-d');
					$querygst = "SELECT igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '" . $invoicecreated_date . "' AND to_date >= '" . $invoicecreated_date . "'";
					$fetchrate = runmysqlqueryfetch($querygst);

					$igst_tax_rate = $fetchrate['igst_rate'];
					$cgst_tax_rate = $fetchrate['cgst_rate'];
					$sgst_tax_rate = $fetchrate['sgst_rate'];

					//$productamount1 = ($productamount == "") ? '0':$productamount;
					$amount = $proamount + $serviceamount;

					//get cgst and sgst detail
					if ($customer_gstcode == '29') {
						$cgst_tax_amount = $amount * $cgst_tax_rate / 100;
						$sgst_tax_amount = $amount * $sgst_tax_rate / 100;

						$cgstamount = sprintf('%0.2f', $cgst_tax_amount);
						$sgstamount = sprintf('%0.2f', $sgst_tax_amount);
						$igstamount = '0.00';
						$gst_type = 'CSGST';
					} else {
						$cgstamount = $sgstamount = '0.00';
						$igst_tax_amount = $amount * $igst_tax_rate / 100;
						$igstamount = sprintf('%0.2f', $igst_tax_amount);
						$gst_type = 'IGST';
					}
					//echo $proamount;
					$netamount = $proamount + $serviceamount + $cgstamount + $sgstamount + $igstamount;
					$netamount = round($netamount);
					$totalproductprice = $proamount + $serviceamount;


					$query10 = "select * from inv_mas_users where slno = '" . $userid . "';";
					$resultfetch = runmysqlqueryfetch($query10);
					$username = $resultfetch['fullname'];

					$amountinwords = convert_number($netamount);
					$servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
					$invoiceheading = 'Tax Invoice';
					$invoicedate = date('Y-m-d') . ' ' . date('H:i:s');

					//Description of product
					//$query12 = "select productcode,count(productcode) as prdcodecount from inv_dealercard  where cusbillnumber = '".$dealerpurchasebillno."' group by productcode order by date;";
					//$result12 = runmysqlquery($query12);

					#einvoice start#
					$productitemgrid = $serviceitemgrid = $totalitemgrid = array();
					$itemcount = 1;
					$totalitemvalue = $totalitemval = 0;
					$finaltotalamt = $discount = 0;

					if ($igstamount != 0 || $igstamount != '0.00') {
						$igstamt = $igstamount;
						$cgstamt = 0;
						$sgstamt = 0;
					} else {
						$cgstamt = $cgstamount;
						$sgstamt = $sgstamount;
						$igstamt = 0;
					}
					$addition_amount = $totalproductprice + $igstamt + $cgstamt + $sgstamt;
					$roundoff_value = $netamount - round($addition_amount, 2);
					//echo $fetch['netamount'] . "amount ". $addition_amount;
					if ($roundoff_value != 0 || $roundoff_value != 0.00) {
						$roundoff = 'true';
					}
					if ($roundoff == 'true')
						$roundoff_value = $roundoff_value;
					else
						$roundoff_value = 0;
					#einvoice end#

					$slno = 0;
					if ($invoicecount == 0 || ($invoicecount > 0 && $status == 'CANCELLED')) {
						// $query744 = "update inv_dealercard set dealerinvoiceid = '".$onlineinvoiceslno."' where cusbillnumber = '".$dealerpurchasebillno."'";
						// $result177 = runmysqlquery($query744);

						$query12 = "select * from inv_billdetail where cusbillnumber = '" . $dealerpurchasebillno . "' ";
						$result12 = runmysqlquery($query12);


						$descriptioncount = 0;
						while ($fetch12 = mysqli_fetch_array($result12)) {
							$slno++;
							$productcode[] = $fetch12['productcode'];
							$productquantity[] = $fetch12['productquantity'];
							$productamount[] = $fetch12['productamount'];
							$productrate[] = $fetch12['productrate'];

							$carddetailsquery = "select * from inv_dealercard left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid  where cusbillnumber = '" . $dealerpurchasebillno . "' and inv_dealercard.productcode = '" . $fetch12['productcode'] . "' and cusbillid = '" . $fetch12['slno'] . "';";
							$carddetailsfetch = runmysqlqueryfetch($carddetailsquery);

							if ($carddetailsfetch['purchasetype'] == 'new')
								$purchasetype = 'New';
							else
								$purchasetype = 'Updation';

							if ($carddetailsfetch['addlicence'] == 'yes') {
								$usagetype = 'Additional License';
							} else {
								if ($carddetailsfetch['usagetype'] == 'singleuser' || $carddetailsfetch['usagetype'] == 'additionallicense')
									$usagetype = 'Single User';
								elseif ($carddetailsfetch['usagetype'] == 'multiuser')
									$usagetype = 'Multi User';
								else
									$usagetype = '';
							}

							if ($descriptioncount > 0)
								$description .= '*';
							$checkprdcode = $carddetailsfetch['productcode'];
							$checkprdcode1 = $fetch12['productcode'];

							$billid = $fetch12['slno'];
							$dealercusid = $carddetailsfetch['cusbillid'];

							if ($fetch12['productquantity'] > 1) {
								//echo "hi";
								if ($checkprdcode == $checkprdcode1) {
									//echo "hi";
									//get first and last cardid
									$query13 = "select min(cardid) as firstcardid, max(cardid) as lastcardid from inv_dealercard where cusbillnumber = '" . $dealerpurchasebillno . "' and productcode = '" . $fetch12['productcode'] . "' and cusbillid = '" . $fetch12['slno'] . "';";
									$fetch13 = runmysqlqueryfetch($query13);

									$query14 = "select * from inv_dealercard where cusbillnumber = '" . $dealerpurchasebillno . "' and productcode = '" . $fetch12['productcode'] . "' and purchasetype = '" . $carddetailsfetch['purchasetype'] . "' and usagetype= '" . $carddetailsfetch['usagetype'] . "';";
									$fetch14 = runmysqlqueryfetch($query14);

									if ($fetch14['purchasetype'] == 'new' && ($fetch14['usagetype'] == 'singleuser' || $fetch14['usagetype'] == 'additionallicense')) {
										$purchasetype = 'New';
										$usagetype = 'Single User';
										$cardid = $fetch13['firstcardid'] . '-' . $fetch13['lastcardid'];
										//$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.''.'$'.$cardid.'$'.$fetch12['productamount'];
									}
									if ($fetch14['purchasetype'] == 'new' && $fetch14['usagetype'] == 'multiuser') {
										$purchasetype = 'New';
										$usagetype = 'Multi User';
										$cardid = $fetch13['firstcardid'] . '-' . $fetch13['lastcardid'];
										//$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.''.'$'.$cardid.'$'.$fetch12['productamount'];
									}
									if ($fetch14['purchasetype'] == 'updation' && ($fetch14['usagetype'] == 'singleuser' || $fetch14['usagetype'] == 'additionallicense')) {
										$purchasetype = 'Updation';
										$usagetype = 'Single User';
										$cardid = $fetch13['firstcardid'] . '-' . $fetch13['lastcardid'];
										//$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.''.'$'.$cardid.'$'.$fetch12['productamount'];
									}
									if ($fetch14['purchasetype'] == 'updation' && $fetch14['usagetype'] == 'multiuser') {
										$purchasetype = 'Updation';
										$usagetype = 'Multi User';
										$cardid = $fetch13['firstcardid'] . '-' . $fetch13['lastcardid'];
										//$description .= $slno.'$'.$carddetailsfetch['productname'].' - ('.$carddetailsfetch['year'].')'.'$'.$purchasetype.'$'.$usagetype.'$'.''.'$'.$cardid.'$'.$fetch12['productamount'];
									}

									if (
										$checkprdcode == "690" || $checkprdcode == "222" || $checkprdcode == "221" || $checkprdcode == "219" || $checkprdcode == "223"
										|| $checkprdcode == "220" || $checkprdcode == "101"
									)
										$description .= $slno . '$' . $carddetailsfetch['productname'] . '$' . $purchasetype . '$' . $usagetype . '$' . '' . '$' . $cardid . '$' . $fetch12['productamount'];
									else
										$description .= $slno . '$' . $carddetailsfetch['productname'] . ' - (' . $carddetailsfetch['year'] . ')' . '$' . $purchasetype . '$' . $usagetype . '$' . '' . '$' . $cardid . '$' . $fetch12['productamount'];
								}
							} else {
								$cardid = $carddetailsfetch['cardid'];

								if (
									$checkprdcode == "690" || $checkprdcode == "222" || $checkprdcode == "221" || $checkprdcode == "219" || $checkprdcode == "223"
									|| $checkprdcode == "220" || $checkprdcode == "101"
								)
									$description .= $slno . '$' . $carddetailsfetch['productname'] . '$' . $purchasetype . '$' . $usagetype . '$' . $carddetailsfetch['scratchnumber'] . '$' . $carddetailsfetch['cardid'] . '$' . $fetch12['productamount'];
								else
									$description .= $slno . '$' . $carddetailsfetch['productname'] . ' - (' . $carddetailsfetch['year'] . ')' . '$' . $purchasetype . '$' . $usagetype . '$' . $carddetailsfetch['scratchnumber'] . '$' . $carddetailsfetch['cardid'] . '$' . $fetch12['productamount'];
							}
							#einvoice start#
							$totalamt = $fetch12['productamount'];
							$taxableamt = $totalamt;
							$discount = 0;

							if ($igstamount != 0) {
								$numberigst = ($taxableamt * 18) / 100;
								$unitigstamt = round($numberigst, 2);
								$unitcgstamt = $unitsgstamt = 0;
							} else {
								$numbercgstamt = ($taxableamt * 9) / 100;
								$unitcgstamt = round($numbercgstamt, 2);

								$numbersgstamt = ($totalamt * 9) / 100;
								$unitsgstamt = round($numbersgstamt, 2);
								$unitigstamt = 0;
							}
							//final value per product
							$totalitemval = $taxableamt + $unitigstamt + $unitcgstamt + $unitsgstamt;

							if ($unitcgstamt == 0 && $unitsgstamt == 0) {
								$num1 = (int) $unitcgstamt;
								$num2 = (int) $unitsgstamt;
								if (is_float($unitigstamt)) {
									$num3 = (float) $unitigstamt;
								} else
									$num3 = (int) $unitigstamt;
							} else {
								$num3 = (int) $unitigstamt;
								if (is_float($unitcgstamt) && is_float($unitsgstamt)) {
									$num1 = (float) $unitcgstamt;
									$num2 = (float) $unitsgstamt;
								} else {
									$num1 = (int) $unitcgstamt;
									$num2 = (int) $unitsgstamt;
								}
							}
							if (is_float($discount))
								$prodiscount = (float) $discount;
							else
								$prodiscount = (int) $discount;

							$productitemgrid[] = array(
								"SlNo" => (string) $itemcount++,
								"IsServc" => 'Y',
								"HsnCd" => '998434',
								"UnitPrice" => (int) $totalamt,
								//price per product
								"TotAmt" => (int) $totalamt,
								//unit price * quantity = toatlamt
								"AssAmt" => (int) $taxableamt,
								//gross amt(toatlamt) - discount
								"Discount" => $prodiscount,
								//consider if any
								"GstRt" => 18,
								"IgstAmt" => $num3,
								//calculate GST based on per product
								"CgstAmt" => $num1,
								//calculate GST based on per product
								"SgstAmt" => $num2,
								//calculate GST based on per product
								"TotItemVal" => round($totalitemval, 2) //taxableamt + gst
							);
							#einvoice end#

							$descriptioncount++;
						}
						//echo $description;
						$productcodearray = implode('#', $productcode);
						$productquantityarray = implode(',', $productquantity);
						$totalproductpricearray = implode('*', $productamount);
						$actualproductpricearray = implode('*', $productrate);

					}
					// if($product2!= "")
					// {
					// 	$itemslno =$slno+1;
					// 	$servicedescription = $itemslno.'$'.$product2.'$'.$itemamount;
					// }

					#einvoice start#
					if (!empty($product2)) {
						// $serviceitem = $product2;
						// $serviceamount  = $itemamount;
						$servicecount = 0;
						$itemothers = $itemper1 = 0;
						//$servicetype = array();
						$unititemigstamt = $unititemcgstamt = $unititemsgstamt = 0;
						for ($i = 0; $i < count((array) $servicelistsplit); $i++) {
							$slno++;
							$servicequery = "select servicecode,servicename from inv_mas_service where slno = '" . $servicelistsplit[$i] . "'";
							$servicefetch = runmysqlqueryfetch($servicequery);
							$servicecode = $servicefetch['servicecode'];
							$servicename = $servicefetch['servicename'];

							if ($i > 0)
								$servicedescription .= '*';
							$servicedescription .= $slno . '$' . $servicename . '$' . $itemamountsplit[$i];
							$servicetype[] = $servicename;

							$itemothers = $itemamountsplit[$i];
							$taxableitemamt = $itemothers;
							$itemdiscount = 0;

							if ($igstamount != 0) {
								$numberitemigstamt = ($taxableitemamt * 18) / 100;
								$unititemigstamt = round($numberitemigstamt, 2);
								$unititemcgstamt = $unititemsgstamt = 0;
							} else {
								$numberitemcgstamt = ($taxableitemamt * 9) / 100;
								$unititemcgstamt = round($numberitemcgstamt, 2);
								$numberitemsgstamt = ($taxableitemamt * 9) / 100;
								$unititemsgstamt = round($numberitemsgstamt, 2);
								$unititemigstamt = 0;
							}

							$totalitemvalue = $taxableitemamt + $unititemigstamt + $unititemcgstamt + $unititemsgstamt;

							if ($unititemcgstamt == 0 && $unititemsgstamt == 0) {
								$inum1 = (int) $unititemcgstamt;
								$inum2 = (int) $unititemsgstamt;
								if (is_float($unititemigstamt))
									$inum3 = (float) $unititemigstamt;
								else
									$inum3 = (int) $unititemigstamt;
							} else {
								$inum3 = (int) $unititemigstamt;
								if (is_float($unititemcgstamt) && is_float($unititemsgstamt)) {
									$inum1 = (float) $unititemcgstamt;
									$inum2 = (float) $unititemsgstamt;
								} else {
									$inum1 = (int) $unititemcgstamt;
									$inum2 = (int) $unititemsgstamt;
								}
							}
							if (is_float($itemdiscount))
								$itemdisc = (float) $itemdiscount;
							else
								$itemdisc = (int) $itemdiscount;

							$serviceitemgrid[] = array(
								"SlNo" => (string) $itemcount++,
								"IsServc" => 'Y',
								"HsnCd" => $servicecode,
								"UnitPrice" => (int) $itemothers,
								"TotAmt" => (int) $itemothers,
								"AssAmt" => (int) $taxableitemamt,
								"Discount" => $itemdisc,
								"GstRt" => 18,
								"IgstAmt" => $inum3,
								"CgstAmt" => $inum1,
								"SgstAmt" => $inum2,
								"TotItemVal" => round($totalitemvalue, 2)
							);
						}
						//$amount = $itemamount;
						$servicetype = implode('#', $servicetype);
					}


					//print_r($serviceitemgrid);
					//echo $servicetype;
					//exit;


					if ($product2 <> '' && ($invoicecount == 0 || ($invoicecount > 0 && $status == 'CANCELLED')))
						$totalitemgrid = array_merge($productitemgrid, $serviceitemgrid);
					elseif ($product2 == '' && ($invoicecount == 0 || ($invoicecount > 0 && $status == 'CANCELLED')))
						$totalitemgrid = $productitemgrid;
					elseif ($product2 <> '' && $invoicecount > 0)
						$totalitemgrid = $serviceitemgrid;
					//print_r($totalitemgrid); exit;

					if (!empty($gst_no)) {
						if ($gstcheck != 'gstinconfirm') {
							sleep(5);
							require_once('generateirn.php');

							$cgstval = $sgstval = $igstval = $rndoffamt = 0;
							if ($igstamt == '0' || $igstamt == '0.00') {
								$igstval = (int) $igstamt;
								if (is_float($cgstamt) && is_float($sgstamt)) {
									$cgstval = (float) $cgstamt;
									$sgstval = (float) $sgstamt;
								} else {
									$cgstval = (int) $cgstamt;
									$sgstval = (int) $sgstamt;
								}
							} else {
								$cgstval = (int) $cgstamt;
								$sgstval = (int) $sgstamt;
								if (is_float($igstamt))
									$igstval = (float) $igstamt;
								else
									$igstval = (int) $igstamt;
							}
							//echo $roundoff_value; exit;
							if ($roundoff_value == 0)
								$rndoffamt = (int) $roundoff_value;
							else
								$rndoffamt = (float) round($roundoff_value, 2);

							$getinvoiceno = getinvoiceno($customer_gstcode, $year);
							$invoicenoformat = $getinvoiceno[0];
							$onlineinvoiceno = $getinvoiceno[1];
							$state_info = $getinvoiceno[2];

							//third api call(Generate IRN)
							//Prepare you post parameters
							$postIrnData = [
								"Irn" => "",
								"Version" => "1.1",
								"TranDtls" => [
									"TaxSch" => "GST",
									"SupTyp" => "B2B",
									"RegRev" => "N",
									"IgstOnIntra" => "N",
								],
								"DocDtls" => [
									"Typ" => "INV",
									"No" => 'D2',
									//$invoicenoformat
									"Dt" => $date,
								],
								"SellerDtls" => [
									"Gstin" => "29AABCR7796N000",
									"LglNm" => "Relyon Softech Limited",
									"Addr1" => "No. 73, Shreelekha Complex, WOC Road",
									"Loc" => "BANGALORE",
									"Pin" => 560086,
									"Stcd" => "29",
								],
								"BuyerDtls" => [
									"Gstin" => $gst_no,
									"LglNm" => $businessname,
									"Pos" => $customer_gstcode,
									"Addr1" => $fetch5['address'],
									"Loc" => $place,
									"Pin" => (int) $pincode,
									"Stcd" => $customer_gstcode
								],
								"ValDtls" => [
									"AssVal" => (int) $totalproductprice,
									"CgstVal" => $cgstval,
									"SgstVal" => $sgstval,
									"IgstVal" => $igstval,
									"RndOffAmt" => $rndoffamt,
									"TotInvVal" => (int) $netamount
								]
							];
							$postIrnData['ItemList'] = $totalitemgrid;
							//print_r($postIrnData);
							$post_irn_data = json_encode($postIrnData);
							//print_r($post_irn_data);
							//exit;
							$irnurl = "https://demo.saralgsp.com/eicore/v1.03/Invoice";

							//open connection
							$irnCurl = curl_init();

							curl_setopt($irnCurl, CURLOPT_URL, $irnurl);

							//So that curl_exec returns the contents of the cURL; rather than echoing it
							curl_setopt($irnCurl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($irnCurl, CURLOPT_POST, true);

							// Set HTTP Header for POST request 
							curl_setopt(
								$irnCurl,
								CURLOPT_HTTPHEADER,
								array(
									"AuthenticationToken: $authenticationToken",
									"SubscriptionId: $subscriptionId",
									"UserName: $UserName",
									"AuthToken: $AuthToken",
									"sek: $sek",
									"Gstin: 29AABCR7796N000",
									"Content-Type: application/json",
								)
							);

							//to find the content-length for header we use postdata
							curl_setopt($irnCurl, CURLOPT_POSTFIELDS, $post_irn_data);

							//execute post
							$irnresult = curl_exec($irnCurl);

							//Print error if any
							if (curl_errno($irnCurl)) {
								echo 'error:' . curl_error($irnCurl);
							}
							curl_close($irnCurl);
							//echo $irnresult;
							//exit;
							$irndata = json_decode($irnresult, true);
							$status = $irndata['status'];
							$irndata['errorDetails'];
							if ($status === "ACT") {
								$ackNo = $irndata['ackNo'];
								$ackDt = $irndata['ackDt'];
								$irn = $irndata['irn'];
								$signedInvoice = $irndata['signedInvoice'];
								$signedQRCode = $irndata['signedQRCode'];
								$authgstin = $gst_no;

								$date = datetimelocal('YmdHis-');
								$filename = $date . $invoicenoformat;
								$filebasename = $filename . ".png";
								$addstring = "/imax/user";
								$filepath = $_SERVER['DOCUMENT_ROOT'] . $addstring . '/qrimages/' . $filebasename;
								$imagepath = "../dlrqrimages/" . $filebasename;
								QRcode::png($signedQRCode, $imagepath, QR_ECLEVEL_L, 2);
							}
							//if(isset($irndata['errorDetails']))
							else {
								for ($c = 0; $c < count($irndata['errorDetails']); $c++) {
									$errorDetails = $irndata['errorDetails'][$c]['errorCode'];
									switch ($errorDetails) {
										case '3028': {
												$authgstin = "";
												$errormsg = "GSTIN is not present in invoice system";
												$errorcode = '3028';
											}
											break; //0 set invoice as Not Registered under GSTIN
										case '3029': {
												$authgstin = "";
												$errormsg = "GSTIN is not active";
												$errorcode = '3029';
											}
											break; //0 set invoice as Not Registered under GSTIN
										case '2265': {
												$errormsg = "Recipient GSTIN state code does not match with the state code passed in recipient details";
												$errorcode = 2265;
											}
											break;
										case '3039': {
												$errormsg = "Pincode of Buyer does not belong to his/her State";
												$errorcode = 3039;
											}
											break;
										case '2150': {
												$errormsg = 'Duplicate IRN';
												$errorcode = 2150;
											}
											break;
										default: {
												$errormsg = $irndata['errorDetails'][$c]['errorMessage'];
												$errorcode = $errorDetails;
											}
											break;
									}
								}

							}
						} else {
							$authgstin = "";
						}
						//echo "IRN" . $irn; 
						//exit;
					} else {
						$authgstin = "";
					} //exit;

					$verifygstin = array('3028', '3029', '3074', '3075', '3076', '3077', '3078', '3079');
					if ($status === "ACT" || empty($gst_no) || $gstcheck == 'gstinconfirm') {
						//Get the next record serial number for insertion in invoicenumbers table
						$query8 = "select ifnull(max(slno),0) + 1 as billref from inv_dealer_invoicenumbers";
						$resultfetch8 = runmysqlqueryfetch($query8);
						$onlineinvoiceslno = $resultfetch8['billref'];

						if (empty($gst_no) || $gstcheck == 'gstinconfirm') {
							$getinvoiceno = getinvoiceno($customer_gstcode, $year);
							$invoicenoformat = $getinvoiceno[0];
							$onlineinvoiceno = $getinvoiceno[1];
							$state_info = $getinvoiceno[2];
						}

						if ($invoicecount == 0 || ($invoicecount > 0 && $status == 'CANCELLED')) {
							$query744 = "update inv_dealercard set dealerinvoiceid = '" . $onlineinvoiceslno . "' where cusbillnumber = '" . $dealerpurchasebillno . "'";
							$result177 = runmysqlquery($query744);
						}

						//Insert complete invoice details to invoicenumbers table 
						$query11 = "Insert into inv_dealer_invoicenumbers(slno,dealerreference,businessname,contactperson,address,
					place,pincode,emailid,description,invoiceno,cusbillnumber,dealername,createddate,createdby,amount,
					igst,cgst,sgst,netamount,phone,cell,region,purchasetype,
					category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,
					totalproductpricearray,actualproductpricearray,module,paymentmode,stdcode,branch,amountinwords,paymentamount,remarks,invoiceremarks,servicedescription,servicetype,serviceamount,servicetaxdesc,
					invoiceheading,branchid,regionid,year,invoice_type,state_info,gst_no,irn,signedqrcode,qrimagepath,ackno,ackdate) values('" . $onlineinvoiceslno . "','" . $dealerslno . "','" . $businessname . "','" . $contactperson . "',
					'" . addslashes($address) . "','" . $place . "','" . $pincode . "','" . $emailid . "','" . $description . "','" . $invoicenoformat . "','" . $dealerpurchasebillno . "',
					'" . $dealername . "','" . $invoicedate . "','" . $username . "','" . $amount . "','" . $igstamount . "','" . $cgstamount . "','" . $sgstamount . "','" . $netamount . "',
					'" . $phone . "','" . $cell . "','" . $dealerregion . "','Product',
					'" . $dealerregion . "','" . $onlineinvoiceno . "','" . $dealerid . "','" . $productcodearray . "','" . $productquantityarray . "',
					'default','" . $userid . "','" . $totalproductpricearray . "','" . $actualproductpricearray . "','user_module','','" . $stdcode . "','" . $dealer_branch_name . "',
					'" . $amountinwords . "','" . $paymentamount . "','" . addslashes($paymentremarks) . "','" . addslashes($invoiceremarks) . "','" . $servicedescription . "','" . $servicetype . "','" . $itemamount . "','" . $servicetaxdesc . "','" . $invoiceheading . "','" . $branchid . "','" . $regionid . "','" . $year . "','D','" . $state_info . "','" . $authgstin . "','" . $irn . "','" . $signedQRCode . "','" . $filebasename . "','" . $ackNo . "','" . $ackDt . "');";
						$result11 = runmysqlquery($query11);


						//Get the next record serial number for insertion in receipts table
						// $query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
						// $resultfetch45 = runmysqlqueryfetch($query45);
						// $receiptslno = $resultfetch45['receiptslno'];
						if ($paymentamount != 0) {
							//Insert Receipt Details
							$query55 = "INSERT INTO inv_mas_receipt(dealerinvoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) 
						values('" . $onlineinvoiceslno . "','" . $netamount . "','" . $paymentamount . "','onlinetransfer','" . $paymentremarks . "','','" . date('Y-m-d') . ' ' . date('H:i:s') . "','" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','" . date('Y-m-d') . ' ' . date('H:i:s') . "','" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $dealerid1 . "','" . date('Y-m-d') . "','" . date('H:i:s') . "','user_module','no');";
							$result55 = runmysqlquery($query55);
						}

						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','261','" . date('Y-m-d') . ' ' . date('H:i:s') . "','" . $dealerid1 . '$$' . $onlineinvoiceslno . "')";
						$eventresult = runmysqlquery($eventquery);

						$responsearray['responsecode'] = 1;
						$responsearray['billslno'] = $dealerpurchasebillno;
						$responsearray['slno'] = $dealerid1;
						$responsearray['invoicecount'] = $invoicecount;

						//Online bill Generation in PDF.
						$pdftype = 'send';
						$invoicedetails = vieworgeneratedealerpdfinvoice($onlineinvoiceslno, $pdftype);
						$invoicedetailssplit = explode('^', $invoicedetails);
						$filebasename = $invoicedetailssplit[0];
						senddealerpurchasesummaryemail($currentdealer, $onlineinvoiceslno, $filebasename);

						echo (json_encode($responsearray));
					} else if ($errorcode == '3028' || $errorcode == '3029') {
						$responsearray['responsecode'] = 2;
						$responsearray['errormsg'] = $errormsg;
						$responsearray['errorcode'] = $errorcode;
						echo (json_encode($responsearray));
					} else {
						//echo(json_encode('E invoice error'));
						$responsearray['responsecode'] = 3;
						$responsearray['errormsg'] = $errormsg;
						$responsearray['errorcode'] = $errorcode;
						echo (json_encode($responsearray));
					}
					#einvoice END#
				}
			}
		}
		break;

	case 'viewcards': {
			$message = "";
			$cusbillnumber = $_POST['cusbillnumber'];

			$fetch = runmysqlqueryfetch("SELECT  count(*) AS count FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '" . $cusbillnumber . "';");
			$count = $fetch['count'];

			if ($count > 0) {
				$fetchstatus = runmysqlqueryfetch("SELECT billstatus FROM inv_bill WHERE slno = '" . $cusbillnumber . "';");
				$billstatus = $fetchstatus['billstatus'];
				if ($billstatus == 'pending') {
					$query = "SELECT inv_billdetail.slno as slno, inv_billdetail.cusbillnumber as cusbillnumber, inv_billdetail.productcode as productcode, inv_billdetail.productquantity as productquantity, inv_billdetail.purchasetype as purchasetype, inv_billdetail.scheme as scheme, inv_billdetail.usagetype as usagetype, inv_bill.dealerid as dealerid,inv_bill.netamount as netamount FROM inv_billdetail LEFT JOIN inv_bill ON inv_bill.slno = inv_billdetail.cusbillnumber LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_billdetail.productcode WHERE inv_bill.slno = '" . $cusbillnumber . "';";
					$result = runmysqlquery($query);

					while ($fetch = mysqli_fetch_array($result)) {
						$scratchlimit = $fetch['productquantity'];
						$dealerid = $fetch['dealerid'];
						$cusbillid = $fetch['slno'];
						for ($i = 0; $i < $scratchlimit; $i++) {
							$query15 = "SELECT attachPIN() as cardid;";
							$result52 = runmysqlqueryfetch($query15);
							$query2 = "INSERT INTO inv_dealercard ( dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype, userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid,cusbillid) values('" . $fetch['dealerid'] . "', '" . $result52['cardid'] . "', '" . $fetch['productcode'] . "', '" . datetimelocal('Y-m-d') . " " . datetimelocal('H:i:s') . "', '" . $fetch['remarks'] . "', '" . $fetch['cusbillnumber'] . "', '" . $fetch['usagetype'] . "', '" . $fetch['purchasetype'] . "', '" . $userid . "', '" . $fetch['scheme'] . "','" . $fetch['usagetype'] . "','" . $fetch['purchasetype'] . "','" . $fetch['productcode'] . "','" . $fetch['dealerid'] . "','" . $cusbillid . "');";
							$result2 = runmysqlquery($query2);

							$query4 = "Insert into inv_logs_purchase(dealerid,cardid,productcode,billamount,usagetype,purchasetype,scheme,purchasedate,userid,system,module)
						values('" . $fetch['dealerid'] . "','" . $result52['cardid'] . "','" . $fetch['productcode'] . "','" . $fetch['netamount'] . "','" . $fetch['usagetype'] . "','" . $fetch['purchasetype'] . "','" . $fetch['scheme'] . "','" . datetimelocal('Y-m-d') . " " . datetimelocal('H:i:s') . "','" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','user_module')";
							$result4 = runmysqlquery($query4);
						}
						//$query3 = "update inv_mas_scratchcard set attached = 'yes' where attached = 'no' order by cardid limit  ".$scratchlimit." ;";			
						//$result3 = runmysqlquery($query3);
					}

					$query4 = "update inv_bill set billstatus = 'successful' where slno = '" . $cusbillnumber . "';";
					$result4 = runmysqlquery($query4);

					/*$query1 = "SELECT businessname as businessname,place as place,contactperson as contactperson ,tlemailid,mgremailid,hoemailid,emailid FROM inv_mas_dealer WHERE slno = '".$dealerid."'";
								$fetch = runmysqlqueryfetch($query1);
								$businessname = $fetch['businessname'];
								$place = $fetch['place'];
								$contactperson = $fetch['contactperson'];
								$tlemailid = $fetch['tlemailid'];
								$mgremailid = $fetch['mgremailid'];
								$hoemailid = $fetch['hoemailid'];
								$emailid = $fetch['emailid'];*/
					$query2 = "SELECT inv_mas_product.productname as productname,inv_mas_product.productcode as productcode, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_dealercard.scheme as scheme, inv_mas_scratchcard.cardid as cardno,
				inv_mas_scratchcard.scratchnumber as pinno,inv_mas_scheme.schemename FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid 
				LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme
				WHERE inv_dealercard.cusbillnumber='" . $cusbillnumber . "'";
					$grid = '<table width="700px" cellpadding="3" cellspacing="0" border="1" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" >';
					$grid .= '<tr bgcolor ="#E9E9D1"><td nowrap = "nowrap"><strong>Sl No</strong></td><td nowrap = "nowrap"><strong>Product Name</strong></td><td nowrap = "nowrap"><strong>Usage Type</strong></td><td nowrap = "nowrap" ><strong>Purchase Type</strong></td><td nowrap = "nowrap" ><strong>PIN Serial Number</strong></td><td nowrap = "nowrap"><strong>PIN Number</strong></td><td nowrap = "nowrap"><strong>Scheme</strong></td></tr>';

					$result2 = runmysqlquery($query2);
					$k = 0;
					while ($fetch2 = mysqli_fetch_array($result2)) {

						$k++;
						$grid .= '<tr>';
						$grid .= "<td nowrap = 'nowrap'>" . $k . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['productname'] . " (" . $fetch2['productcode'] . ")</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['usagetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['purchasetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['cardno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['pinno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch2['schemename'] . "</td>";
						$grid .= "</tr>";
					}
					$grid .= "</table>";
					$table = $grid;
					//sendpurchasemail($cusbillnumber,$dealerid,$table,'New');
					/*$query1 = "select sum(netamount) as totalpurchaseamount from inv_bill where inv_bill.slno = '".$cusbillnumber."';";
								   $fetch1 = runmysqlqueryfetch($query1);
								   $totalpurchaseamount = $fetch1['totalpurchaseamount'];
								   $totalcredit = getcurrentcredit($dealerid);
								   #########  Mailing Starts -----------------------------------
								   //$emailid = 'rashmi.hk@relyonsoft.com';
								   $bcceallemailid = $tlemailid.','.$mgremailid.','.$hoemailid;
								   $bccemailarray = explode(',',$bcceallemailid);
								   $bccemailcount = count($bccemailarray);
								   $emailarray = explode(',',$emailid);
								   $emailcount = count($emailarray);
								   
								   for($i = 0; $i < $emailcount; $i++)
								   {
									   if(checkemailaddress($emailarray[$i]))
									   {
										   if($i == 0)
											   $emailids[$contactperson] = $emailarray[$i];
										   else
											   $emailids[$emailarray[$i]] = $emailarray[$i];
									   }
								   }
								   
								   for($i = 0; $i < $bccemailcount; $i++)
								   {
									   if(checkemailaddress($bccemailarray[$i]))
									   {
										   if($i == 0)
											   $bccemailids[$contactperson] = $bccemailarray[$i];
										   else
											   $bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
									   }
								   }
								   
								   $fromname = "Relyon";
								   $fromemail = "imax@relyon.co.in";
								   $subject = "Testing";
								   require_once("../inc/RSLMAIL_MAIL.php");
								   $msg = file_get_contents("../mailcontents/newpurchase.htm");
								   $textmsg = file_get_contents("../mailcontents/newpurchase.txt");
								   $date = datetimelocal('d-m-Y');
								   $array = array();
								   $array[] = "##DATE##%^%".$date;
								   $array[] = "##NAME##%^%".$contactperson;
								   $array[] = "##COMPANY##%^%".$businessname;
								   $array[] = "##PLACE##%^%".$place;
								   $array[] = "##TABLE##%^%".$table;
								   $array[] = "##BILLNO##%^%".$cusbillnumber;
								   $array[] = "##EMAILID##%^%".$emailid;
								   $array[] = "##AMOUNT##%^%".$totalpurchaseamount;
								   $array[] = "##TOTALCREDIT##%^%".$totalcredit;
								   $filearray = array(
									   array('../images/relyon-logo.jpg','inline','1234567890')
									   //array('../inc/SPP_with_Online_Profile.pdf','attachment','1234567891')
								   );
								   $toarray = $emailids;
								   $bccemailids['vijaykumar'] ='vijaykumar@relyonsoft.com';
								   $bccarray = $bccemailids;
								   $msg = replacemailvariable($msg,$array);
								   $textmsg = replacemailvariable($textmsg,$array);
								   $subject = 'New products purchased by "'.$businessname.'"';
								   $html = $msg;
								   $text = $textmsg;
								   rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray); 
						   
							   #########  Mailing Ends ----------------------------------------*/
					$message1 = "1^ Cards Attached Successfully.";

				}
				$fetchstatus = runmysqlqueryfetch("SELECT billstatus FROM inv_bill WHERE slno = '" . $cusbillnumber . "';");
				$billstatus = $fetchstatus['billstatus'];
				if ($billstatus == 'successful') {
					$query = "SELECT inv_mas_product.productcode as productcode ,inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno,inv_mas_scratchcard.scratchnumber as pinno, inv_mas_scheme.schemename FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme WHERE inv_dealercard.cusbillnumber='" . $cusbillnumber . "';";
					$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
					$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Slno</td><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid">PIN Number</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td></tr>';

					$result = runmysqlquery($query);
					$i_n = 0;
					$slno = 0;
					while ($fetch = mysqli_fetch_array($result)) {
						$i_n++;
						$slno++;
						$color;
						if ($i_n % 2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						$grid .= '<tr class="gridrow1" bgcolor=' . $color . '>';
						$grid .= "<td nowrap = 'nowrap'>" . $slno . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['productname'] . " (" . $fetch['productcode'] . ")</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['usagetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['purchasetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['cardno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['pinno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['schemename'] . "</td>";
						$grid .= "</tr>";
					}
					$grid .= '<tr>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='right' colspan='7'><br />
				<input name='printrep' type='button' class='swiftchoicebutton-red' id='printrep' value='Print'  onclick='formsubmit(\"print\");'/>&nbsp;&nbsp;&nbsp;</td>";
					$grid .= "</tr>";
					$grid .= "</table>";
					$message = "1^" . $grid;
				}
				echo (json_encode($message));
			}
		}
		break;

	case 'getdealername': {
			$dealerid = $_POST['dealerid'];
			$query = "SELECT businessname,taxamount FROM inv_mas_dealer WHERE slno = '" . $dealerid . "'";
			$fetch = runmysqlqueryfetch($query);
			echo ('1^' . $fetch['businessname'] . '^' . $fetch['taxamount']);
		}
		break;

	case 'calculatenetamount': {
			$cusbillnumber = $_POST['cusbillnumber'];
			$dealerid = $_POST['dealerid'];

			$query = "SELECT sum(productamount) as totalamount FROM inv_billdetail WHERE cusbillnumber = '" . $cusbillnumber . "'";

			$fetch = runmysqlqueryfetch($query);

			$totalamount = $fetch['totalamount'];
			$query = "SELECT taxname,taxamount  FROM inv_mas_dealer WHERE slno = '" . $dealerid . "';";
			$fetch = runmysqlqueryfetch($query);

			$taxrate = $fetch['taxamount'];
			$taxname = $fetch['taxname'];

			$taxamount = round($totalamount * ($taxrate / 100));

			$netamount = $taxamount + $totalamount;
			$responsearray2 = array();
			//echo($totalamount.'^'.$totaltax.'^'.$netamount);
			$responsearray2['errormessage'] = '1^' . $totalamount . '^' . $taxname . ' (' . $taxrate . '%) ' . '^' . $taxamount . '^' . $netamount;
			echo (json_encode($responsearray2));
			//echo('1^'.$totalamount.'^'.$taxname.' ('.$taxrate.'%) '.'^'.$taxamount.'^'.$netamount);
		}
		break;

	case 'getcreditamount': {
			$responsearray3 = array();
			$dealerid = $_POST['dealerid'];
			/*$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '".$dealerid."'";
				  $resultfetch = runmysqlqueryfetch($query0);
				  $totalcredit = $resultfetch['totalcredit'];
				  $query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '".$dealerid."' AND billstatus ='successful'";
				  $resultfetch1 = runmysqlqueryfetch($query1);
				  $billedamount = $resultfetch1['billedamount'];
				  $totalcreditavl = $totalcredit - $billedamount;
				  //$query = "UPDATE inv_credits";
				  //$result = runmysqlquery($query);
				  echo($totalcreditavl);*/
			$totalcreditleft = getcurrentcredit($dealerid);
			$responsearray3['errormessage'] = '1^' . $totalcreditleft;
			echo (json_encode($responsearray3));
		}
		break;

	/*case 'gettheprint':
	   {
		   $dealerid = $_POST['dealerid'];
		   $cusbillnumber = $_POST['cusbillnumber'];
		   
		   $query = "";
		   $result = runmysqlquery($query);
	   }
	   break;*/
	case 'resendpurchaseemail': {
			$resendpurchaseemailarray = array();
			$cusbillnumber = $_POST['billno'];
			$query = "SELECT inv_mas_product.productcode as productcode ,inv_mas_product.productname as productname, inv_dealercard.usagetype as usagetype, inv_dealercard.purchasetype as purchasetype, inv_mas_scratchcard.cardid as cardno,inv_mas_scratchcard.scratchnumber as pinno, inv_mas_scheme.schemename,inv_dealercard.dealerid as dealerid FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno =inv_dealercard.scheme WHERE inv_dealercard.cusbillnumber='" . $cusbillnumber . "';";
			$grid = '<table width="700px" cellpadding="3" cellspacing="0" border="1" align="center" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px" >';
			$grid .= '<tr bgcolor ="#E9E9D1"><td nowrap = "nowrap"><strong>Sl No</strong></td><td nowrap = "nowrap"><strong>Product Name</strong></td><td nowrap = "nowrap"><strong>Usage Type</strong></td><td nowrap = "nowrap" ><strong>Purchase Type</strong></td><td nowrap = "nowrap" ><strong>PIN Serial Number</strong></td><td nowrap = "nowrap"><strong>PIN Number</strong></td><td nowrap = "nowrap"><strong>Scheme</strong></td></tr>';
			$result = runmysqlquery($query);
			$k = 0;
			while ($fetch2 = mysqli_fetch_array($result)) {
				$k++;
				$grid .= '<tr>';
				$grid .= "<td nowrap = 'nowrap'>" . $k . "</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['productname'] . " (" . $fetch2['productcode'] . ")</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['usagetype'] . "</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['purchasetype'] . "</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['cardno'] . "</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['pinno'] . "</td>";
				$grid .= "<td nowrap = 'nowrap'>" . $fetch2['schemename'] . "</td>";
				$grid .= "</tr>";
				$dealerid = $fetch2['dealerid'];
			}
			$grid .= "</table>";
			$table = $grid;
			//sendpurchasemail($cusbillnumber,$dealerid,$table,'Resent');
			$resendpurchaseemailarray['errormessage'] = "1^Resent Mail has been sent Successfully for the selected product.";
			//echo("1^Resent Mail has been sent Successfully for the selected product.");
			echo (json_encode($resendpurchaseemailarray));
		}
		break;
}


function getcurrentcredit($dealerid)
{
	$query0 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '" . $dealerid . "'";
	$resultfetch = runmysqlqueryfetch($query0);
	$totalcredit = $resultfetch['totalcredit'];
	$query1 = "SELECT sum(netamount) as billedamount FROM inv_bill WHERE dealerid = '" . $dealerid . "' AND billstatus ='successful'";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$billedamount = $resultfetch1['billedamount'];
	$totalcreditavl = $totalcredit - $billedamount;
	return $totalcreditavl;
}

function sendpurchasemail($cusbillnumber, $dealerid, $table, $type)
{
	$query1 = "select sum(netamount) as totalpurchaseamount from inv_bill where inv_bill.slno = '" . $cusbillnumber . "';";
	$fetch1 = runmysqlqueryfetch($query1);
	$totalpurchaseamount = $fetch1['totalpurchaseamount'];
	$query2 = "select remarks from inv_bill where inv_bill.slno = '" . $cusbillnumber . "';";
	$fetch2 = runmysqlqueryfetch($query2);
	$remarks = $fetch2['remarks'];
	if ($remarks == '')
		$remarks = 'None';
	$totalcredit = getcurrentcredit($dealerid);
	$query = "SELECT businessname as businessname,place as place,contactperson as contactperson ,tlemailid,mgremailid,hoemailid,emailid FROM inv_mas_dealer WHERE slno = '" . $dealerid . "'";
	$fetch = runmysqlqueryfetch($query);
	$businessname = $fetch['businessname'];
	$place = $fetch['place'];
	$contactperson = $fetch['contactperson'];
	$tlemailid = $fetch['tlemailid'];
	$mgremailid = $fetch['mgremailid'];
	$hoemailid = $fetch['hoemailid'];
	$emailid = $fetch['emailid'];

	#########  Mailing Starts -----------------------------------
	if (($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab"))
		$emailid = 'archana.ab@relyonsoft.com';
	else
		$emailid = $emailid;

	$bcceallemailid = $tlemailid . ',' . $mgremailid . ',' . $hoemailid;
	$bccemailarray = explode(',', $bcceallemailid);
	$bccemailcount = count($bccemailarray);
	$emailarray = explode(',', $emailid);
	$emailcount = count($emailarray);
	//Edited by Nagamani
	//to avoid duplicate mailIds in list
	$emailids = array();
	for ($i = 0; $i < $emailcount; $i++) {
		if (checkemailaddress($emailarray[$i])) {
			if (!in_array($emailarray[$i], $emailids)) {
				if ($contactperson != '')
					$emailids[$contactperson] = $emailarray[$i];
				else
					$emailids[$emailarray[$i]] = $emailarray[$i];
			}
		}
	}
	$bccemailids = array();
	for ($i = 0; $i < $bccemailcount; $i++) {
		if (checkemailaddress($bccemailarray[$i])) {
			if (!in_array($bccemailarray[$i], $bccemailids)) {
				$bccemailids[$bccemailarray[$i]] = $bccemailarray[$i];
			}
		}
	}

	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	//$subject = "Testing";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/newpurchase.htm");
	$textmsg = file_get_contents("../mailcontents/newpurchase.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%" . $date;
	$array[] = "##NAME##%^%" . $contactperson;
	$array[] = "##COMPANY##%^%" . $businessname;
	$array[] = "##PLACE##%^%" . $place;
	$array[] = "##TABLE##%^%" . $table;
	$array[] = "##BILLNO##%^%" . $cusbillnumber;
	$array[] = "##EMAILID##%^%" . $emailid;
	$array[] = "##AMOUNT##%^%" . $totalpurchaseamount;
	$array[] = "##TOTALCREDIT##%^%" . $totalcredit;
	$array[] = "##REMARKS##%^%" . $remarks;
	$filearray = array(
		array('../images/relyon-logo.jpg', 'inline', '8888888888'),
		array('../images/relyon-rupee-small.jpg', 'inline', '88888888898')
	);

	$toarray = $emailids;
	if (($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") || ($_SERVER['HTTP_HOST'] == "archanaab")) {
		$bccemailids['rashmi'] = 'meghana.b@relyonsoft.com';
	} else {
		$bccemailids['relyonimax'] = 'relyonimax@gmail.com';
		$bccemailids['bigmail'] = 'bigmail@relyonsoft.com';
	}
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg, $array);
	$textmsg = replacemailvariable($textmsg, $array);
	if ($type == 'New')
		$subject = 'New products purchased by "' . $businessname . '"';
	else
		$subject = 'New products purchased by "' . $businessname . '"(Resent)';
	$html = $msg;
	$text = $textmsg;
	$replyto = 'info@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html, null, $bccarray, $filearray, $replyto);
	//Insert the mail forwarded details to the logs table
	$bccmailid = $bcceallemailid . ',' . 'bigmail@relyonsoft.com';
	$resultvalue = str_replace(',,', ',', $bccmailid);
	$resultbcc = ltrim($resultvalue, ',');
	inserttologs(imaxgetcookie('userid'), $dealerid, $fromname, $fromemail, $emailid, null, $resultbcc, $subject);
	#########  Mailing Ends ----------------------------------------
}
function getinvoiceno($customer_gstcode, $year)
{
	if ($customer_gstcode == '29') {
		$state_info = 'L';
		$varState = '2022DL';

		$queryonlineinv = "select ifnull(max(onlineinvoiceno),442)+ 1 as invoicenotobeinserted from inv_dealer_invoicenumbers where invoiceno like '%" . $varState . "%'";

		//Get the next invoice number from invoicenumbers table, for this new_invoice
		$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
		$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
		$onlineinvoiceno = (string) $onlineinvoiceno;
		$onlineinvoiceno = sprintf('%06d', $onlineinvoiceno);
		$invoicenoformat = 'RSL' . $year . 'D' . $state_info . '' . $onlineinvoiceno;
	} else {
		//$onlineinvoiceno='000100';
		$state_info = 'I';
		$varState = '2022DI';

		$queryonlineinv = "select ifnull(max(onlineinvoiceno),924)+ 1 as invoicenotobeinserted from inv_dealer_invoicenumbers where invoiceno like '%" . $varState . "%'";

		$resultfetchinv = runmysqlqueryfetch($queryonlineinv);
		$onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
		$onlineinvoiceno = sprintf('%06d', $onlineinvoiceno);
		$onlineinvoiceno = (string) $onlineinvoiceno;
		$invoicenoformat = 'RSL' . $year . 'D' . $state_info . '' . $onlineinvoiceno;
	}
	return array($invoicenoformat, $onlineinvoiceno, $state_info);
}
?>