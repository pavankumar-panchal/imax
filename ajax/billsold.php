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
					$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount , usagetype , purchasetype ,scheme ) VALUES ('" . $fetch1['cusbillnumber'] . "','" . $productcode . "','" . $productquantity . "','" . $productamount . "','" . $usagetype . "','" . $purchasetype . "','" . $scheme . "');";
					$result = runmysqlquery($query);
					$firstbillnumber = $fetch1['cusbillnumber'];
				} else {
					$query = "INSERT INTO inv_billdetail (cusbillnumber , productcode , productquantity , productamount , usagetype , purchasetype ,scheme ) VALUES ('" . $cusbillnumber . "','" . $productcode . "','" . $productquantity . "','" . $productamount . "','" . $usagetype . "','" . $purchasetype . "','" . $scheme . "');";
					$result = runmysqlquery($query);
				}
			} else {
				$query = "UPDATE inv_billdetail SET productcode = '" . $productcode . "' , productquantity = '" . $productquantity . "', productamount = '" . $productamount . "' , usagetype = '" . $usagetype . "' , purchasetype = '" . $purchasetype . "'  , scheme = '" . $scheme . "' WHERE slno = '" . $productlastslno . "'";
				$result = runmysqlquery($query);
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
			$cusbillnumber = $_POST['cusbillnumber'];
			$query = "DELETE FROM inv_billdetail WHERE cusbillnumber = '" . $cusbillnumber . "'";
			$result = runmysqlquery($query);

			$responsearray['errormessage'] = "2^ Record Deleted Successfully.";
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
inv_billdetail.productquantity,inv_billdetail.productamount,inv_billdetail.usagetype,inv_billdetail.purchasetype, 
inv_mas_scheme.schemename FROM inv_billdetail LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_billdetail.productcode
LEFT JOIN inv_mas_scheme ON inv_mas_scheme.slno = inv_billdetail.scheme
WHERE inv_billdetail.cusbillnumber = '" . $cusbillnumber . "'";
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid">Bill Number</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Quantity</td>
		<td nowrap = "nowrap" class="td-border-grid">Product Amount</td>
		<td nowrap = "nowrap" class="td-border-grid">Usage Type</td>
		<td nowrap = "nowrap" class="td-border-grid">Purchase Type</td>
		<td nowrap = "nowrap" class="td-border-grid">scheme</td></tr>';
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
				$grid .= '<tr class="tr-grid-header" align ="left"><td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Bill Number</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Dealer ID</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Bill Date</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Total Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Tax Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Net Amount</td><td nowrap = "nowrap" class="td-border-grid" align ="left">Entered By</td></tr>';
			}
			$i_n = 0;
			$result = runmysqlquery($query);
			while ($fetch = mysqli_fetch_row($result)) {
				$i_n++;
				$slno++;
				$color;
				if ($i_n % 2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				$grid .= '<tr class="gridrow" bgcolor=' . $color . ' onclick="gridtoform(\'' . $fetch[1] . '\',\'' . $fetch[0] . '\'); gridtab2(\'1\',\'tabgroupgrid\',\'&nbsp; &nbsp;Bill Entry\');" align ="left">';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . $slno . "</td>";
				for ($i = 0; $i < count($fetch); $i++) {
					if ($i == 2)
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . changedateformatwithtime($fetch[$i]) . "</td>";
					else
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align ='left'>" . gridtrim($fetch[$i]) . "</td>";
				}
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
			$query = "SELECT slno,cusbillnumber,productcode,productquantity,productamount,usagetype,purchasetype,addlicence,scheme FROM inv_billdetail WHERE slno = '" . $productlastslno . "'";
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
			echo (json_encode($productgridtoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['cusbillnumber'].'^'.$fetch['productcode'].'^'.$fetch['productquantity'].'^'.$fetch['productamount'].'^'.$fetch['usagetype'].'^'.$fetch['purchasetype'].'^'.$fetch['scheme']);
		}
		break;

	case 'gridtoform': {
			$gridtoformarray = array();
			$query = "SELECT inv_bill.slno,inv_bill.dealerid,inv_bill.billdate,
		inv_bill.remarks,inv_bill.total,inv_bill.taxamount,inv_bill.netamount,inv_mas_dealer.businessname, 
		inv_mas_users.fullname,inv_bill.billstatus 
		FROM inv_bill 
		LEFT JOIN inv_mas_users ON inv_bill.userid = inv_mas_users.slno 
		LEFT JOIN inv_mas_dealer ON inv_bill.dealerid = inv_mas_dealer.slno
		 WHERE inv_bill.dealerid = '" . $lastslno . "' AND inv_bill.slno = '" . $billlastslno . "'";
			$fetch = runmysqlqueryfetch($query);

			$query1 = "SELECT inv_billdetail.slno,inv_billdetail.cusbillnumber,inv_mas_product.productname, 
inv_billdetail.productquantity, inv_billdetail.productamount, inv_billdetail.usagetype, inv_billdetail.purchasetype,
 inv_mas_scheme.schemename
FROM inv_billdetail LEFT JOIN inv_mas_product ON inv_billdetail.productcode = inv_mas_product.productcode 
LEFT JOIN inv_mas_scheme ON inv_mas_scheme.slno = inv_billdetail.scheme 
LEFT JOIN inv_bill on inv_bill.slno = inv_billdetail.cusbillnumber WHERE inv_bill.slno = '" . $billlastslno . "';";

			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align ="left">
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left"> Bill Number</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Product Name</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Billed Quantity</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Billed Amount</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Usage Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Purchase Type</td>
		<td nowrap = "nowrap" class="td-border-grid" align ="left">Scheme</td>
		</tr>';
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
			$gridtoformarray['errorcode'] = '1';
			$gridtoformarray['slno'] = $fetch['slno'];
			$gridtoformarray['dealerid'] = $fetch['dealerid'];
			$gridtoformarray['billdate'] = changedateformatwithtime($fetch['billdate']);
			$gridtoformarray['remarks'] = $fetch['remarks'];
			$gridtoformarray['total'] = $fetch['total'];
			$gridtoformarray['taxamount'] = $fetch['taxamount'];
			$gridtoformarray['netamount'] = $fetch['netamount'];
			$gridtoformarray['grid'] = $fetch['grid'];
			$gridtoformarray['businessname'] = $fetch['businessname'];
			$gridtoformarray['fullname'] = $fetch['fullname'];
			$gridtoformarray['userid'] = $fetch['userid'];
			$gridtoformarray['billstatus'] = $fetch['billstatus'];

			echo (json_encode($gridtoformarray));
			//echo('1^'.$fetch['slno'].'^'.$fetch['dealerid'].'^'.changedateformatwithtime($fetch['billdate']).'^'.$fetch['remarks'].'^'.$fetch['total'].'^'.$fetch['taxamount'].'^'.$fetch['netamount'].'^'.$grid.'^'.$fetch['businessname'].'^'.$fetch['fullname'].'^'.$userid.'^'.$fetch['billstatus']);
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
					$totalamount = $fetch['amount'] * $productquantity;
					$totalcalamount = round($totalamount - ($totalamount * ($revenueshare / 100)));
					$getamountarray['errormessage'] = '1^' . $totalcalamount;
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
			$query1 = "SELECT sum(creditamount) as totalcredit FROM inv_credits WHERE dealerid = '" . $dealerid . "'";
			$fetchresult = runmysqlqueryfetch($query1);
			$totalcredit = $fetchresult['totalcredit'];

			if ($totalcredit == '') {
				$totalcreditavl = 0;
			} else
				//$totalcreditavl = $totalcredit - $billedamount;
				$totalcreditavl = getcurrentcredit($dealerid);
			if ($totalcreditavl >= $netamount) {
				$query = "UPDATE inv_bill SET dealerid = '" . $dealerid . "',total = '" . $total . "',remarks = '" . $remarks . "',netamount = '" . $netamount . "',taxamount = '" . $taxamount . "',billstatus = 'pending' WHERE slno = '" . $cusbillnumber . "'";
				$result = runmysqlquery($query);
				$responsearray1['errormessage'] = "1^ Record Saved Successfully.";
				//echo("1^ Record Saved Successfully.");

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
						for ($i = 0; $i < $scratchlimit; $i++) {
							$query15 = "SELECT attachPIN() as cardid;";
							$result52 = runmysqlqueryfetch($query15);
							$query2 = "INSERT INTO inv_dealercard ( dealerid, cardid, productcode, date, remarks, cusbillnumber, usagetype, purchasetype, userid,scheme,initialusagetype,initialpurchasetype,initialproduct,initialdealerid) values('" . $fetch['dealerid'] . "', '" . $result52['cardid'] . "', '" . $fetch['productcode'] . "', '" . datetimelocal('Y-m-d') . " " . datetimelocal('H:i:s') . "', '" . $fetch['remarks'] . "', '" . $fetch['cusbillnumber'] . "', '" . $fetch['usagetype'] . "', '" . $fetch['purchasetype'] . "', '" . $userid . "', '" . $fetch['scheme'] . "','" . $fetch['usagetype'] . "','" . $fetch['purchasetype'] . "','" . $fetch['productcode'] . "','" . $fetch['dealerid'] . "');";
							$result2 = runmysqlquery($query2);

							$query4 = "Insert into 
	inv_logs_purchase(dealerid,cardid,productcode,billamount,usagetype,purchasetype,scheme,purchasedate,userid,system,module)
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
					/*		$query1 = "select sum(netamount) as totalpurchaseamount from inv_bill where inv_bill.slno = '".$cusbillnumber."';";
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
					$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid">PIN Number</td><td nowrap = "nowrap" class="td-border-grid">Scheme</td></tr>';

					$result = runmysqlquery($query);
					$i_n = 0;
					while ($fetch = mysqli_fetch_array($result)) {
						$i_n++;
						$color;
						if ($i_n % 2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						$grid .= '<tr class="gridrow1" bgcolor=' . $color . '>';
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['productname'] . " (" . $fetch['productcode'] . ")</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['usagetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['purchasetype'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['cardno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['pinno'] . "</td>";
						$grid .= "<td nowrap = 'nowrap'>" . $fetch['schemename'] . "</td>";
						$grid .= "</tr>";
					}
					$grid .= '<tr>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='right' colspan='6'><br />
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
?>