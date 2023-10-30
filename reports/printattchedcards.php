<link rel="stylesheet" type="text/css" href="../style/global.css?dummy=<?php echo (rand());?>">
<link rel="stylesheet" type="text/css" href="../style/main.css?dummy=<?php echo (rand());?>">
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>

<?php
include('../functions/phpfunctions.php');

$dealerid = $_POST['lastslno'];
$cusbillnumber = $_POST['cusbillnumber'];
if($dealerid <> '' && $cusbillnumber <> '')
{
//echo($dealerid);
//echo($cusbillnumber);
echo('<table width="100%" border="0" cellspacing="0" cellpadding="0">

</table>');
$query0 = "SELECT billdate FROM inv_bill WHERE slno = '".$cusbillnumber."';";
$fetch0 = runmysqlqueryfetch($query0);
$query1 = "SELECT inv_mas_dealer.businessname,inv_mas_dealer.contactperson, inv_mas_dealer.address,inv_mas_dealer.phone,inv_mas_dealer.cell, inv_mas_dealer.place, inv_mas_district.districtname, inv_mas_state.statename FROM inv_mas_dealer LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_dealer.district LEFT JOIN inv_mas_state ON inv_mas_state.statecode = inv_mas_district.statecode WHERE inv_mas_dealer.slno = '".$dealerid."'";
$fetch1 = runmysqlqueryfetch($query1);
$datetime =changedateformatwithtime($fetch0['billdate']);
$datesplit = explode(" ",$datetime);
$date = $datesplit[0];
$time = $datesplit[1];

$query2 = "SELECT  inv_billdetail.slno, inv_billdetail.cusbillnumber, inv_mas_product.productname,inv_mas_product.productcode, inv_billdetail.productquantity, inv_billdetail.productamount, inv_billdetail.usagetype,inv_billdetail.purchasetype,inv_billdetail.addlicence FROM inv_billdetail LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_billdetail.productcode WHERE inv_billdetail.cusbillnumber = '".$cusbillnumber."'";

$grid = '<div id="printdata"><table width="800" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><table width="100%" border="0" cellpadding="3" cellspacing="0" style="border:1px solid #000000;"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="30%" style="border-bottom:1px solid #000000;"><div align="left"><img src="../images/imax-logo.jpg" width="228" height="61" /></div></td><td width="40%" style="border-bottom:1px solid #000000;"><div align="center"><strong><font color="#000000">Delivery Note</font></strong></div></td><td width="30%" style="border-bottom:1px solid #000000;"><div align="right"><img src="../images/inv_relyon-logo.gif" width="143" height="50" /></div></td></tr></table></td></tr><tr><td><table width="100%" border="0" cellpadding="4" cellspacing="0" style="border:1px solid #333333;font-size:14px"><tr><td width="56%" style="border-right: 1px solid  #000000;">Dealer:</td><td width="44%">Date:&nbsp;&nbsp;'.$date.'</td></tr><tr><td style="border-right: 1px solid  #000000">'.$fetch1['businessname'].'</td><td>Time:&nbsp;&nbsp;'.$time.'</td></tr><tr><td style="border-right: 1px solid  #000000">'.$fetch1['address'].'&nbsp;</td><td>Ref No: '.$cusbillnumber.'</td></tr><tr><td style="border-right: 1px solid  #000000">'.$fetch1['place'].'&nbsp;'.$fetch1['districtname'].'&nbsp;'.$fetch1['statename'].'</td><td>&nbsp;</td></tr><tr><td style="border-right: 1px solid  #000000;border-bottom: 1px solid  #000000">Phone: '.$fetch1['phone'].','.$fetch1['cell'].'</td><td style="border-bottom: 1px solid  #000000">&nbsp;</td></tr><tr><td colspan="2" valign="top"><strong>Purchase Details:</strong></td></tr><tr><td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" ><tr><td width="15%" style="padding:0"><div id="displayproductresult2" style="overflow:auto; padding:2px;" align="center">';
$grid .= '<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#000000">';
$grid .= '<tr bgcolor="#CCCCCC" style="font-size:12px"><td nowrap = "nowrap" ><div align="center"><strong>Sl No</strong></div></td><td nowrap = "nowrap" ><div align="center"><strong>product Name</strong></div></td><td nowrap = "nowrap"><div align="center"><strong>Usage Type</strong></div></td><td nowrap = "nowrap"><div align="center"><strong>Purchase Type</strong></div></td><td nowrap = "nowrap" ><div align="center"><strong>Add Licence</strong></div></td><td nowrap = "nowrap" ><div align="center"><strong>Quantity</strong></div></td><td nowrap = "nowrap" ><div align="center"><strong>Amount</strong></div></td></tr>';
		$i_n = 0;
		$slno = 0;
		$result2 = runmysqlquery($query2);
		while($fetch2 = mysqli_fetch_array($result2))
		{
			$i_n++;
			$slno++;
			$grid .= '<tr style="font-size:14px">';
			$grid .= "<td nowrap='nowrap'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap'>".$fetch2['productname']." (".$fetch2['productcode'].")</td>";
			$grid .= "<td nowrap='nowrap'>".$fetch2['usagetype']."</td>";
			$grid .= "<td nowrap='nowrap'>".$fetch2['purchasetype']."</td>";
			$grid .= "<td nowrap='nowrap'>".$fetch2['addlicence']."</td>";
			$grid .= "<td nowrap='nowrap'>".$fetch2['productquantity']."</td>";
			$grid .= "<td nowrap='nowrap'><div align='right'>".$fetch2['productamount']."</div></td>";
			$grid .= "</tr>";
		}
				
		$query3 = "SELECT total,taxamount,netamount FROM inv_bill WHERE slno = '".$cusbillnumber."';";
		$fetch3 = runmysqlqueryfetch($query3);
		$grid .= '<tr style="font-size:14px"><td colspan="6">&nbsp;</td><td>Total Amount:</td><td><div align="right">'.$fetch3['netamount'].'</div></td></tr>';
		$grid .= '<tr style="font-size:14px"><td colspan="6" style="border-top:none">&nbsp;</td><td  style="border-top:none">Tax:</td><td style="border-top:none"><div align="right">'.$fetch3['taxamount'].'</div></td></tr>';
		$grid .= '<tr style="font-size:14px"><td colspan="6" style="border-top:none">&nbsp;</td><td  style="border-top:none">Net Amount:</td><td style="border-top:none"><strong><div align="right">'.$fetch3['netamount'].'</div></strong></td></tr>';
		$grid .= "</table></div></td></tr></table></td></tr>";
		//$grid .= '</div></td></tr></table></td></tr><tr><td colspan="4">&nbsp;</td></tr>';

	//	$grid .= '<tr><td colspan="2" valign="top">&nbsp;</td></tr><tr><td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td width="73%">&nbsp;</td><td width="19%" align="right" style="padding-right:10px;">Total Amount:</td><td width="8%">'.$fetch3['total'].'</td></tr><tr><td>&nbsp;</td><td align="right" style="padding-right:10px;">Tax Amount:</td><td>'.$fetch3['taxamount'].'</td></tr><tr><td>&nbsp;</td><td align="right" style="padding-right:10px;">Net Amount:</td><td>'.$fetch3['netamount'].'</td></tr></table></td></tr>';
		
		//$grid .= '<tr><td colspan="2">&nbsp;</td><td>Tax Amount:</td><td>'.$fetch3['taxamount'].'</td></tr>';
		//$grid .= '<tr><td colspan="2">&nbsp;</td><td>Net Amount:</td><td>'.$fetch3['netamount'].'</td></tr>';
		//$grid .= '<tr><td colspan="4">&nbsp;</td></tr>';
		
		$query4 = "SELECT inv_mas_product.productname,inv_mas_product.productcode, inv_dealercard.usagetype, inv_dealercard.purchasetype, inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_dealercard LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_dealercard.productcode WHERE inv_dealercard.cusbillnumber='".$cusbillnumber."';";
		$grid .= '<tr><td colspan="2" valign="top"><strong>Delivery Details:</strong></td></tr><tr><td colspan="2" valign="top"><tr><td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="15%" style="padding:0"><div id="displayproductresult3" style="overflow:auto; padding:2px;" align="center"><table width="100%" cellpadding="2" cellspacing="0" border="1px" bordercolor="#000000">';
		$grid .= '<tr bgcolor="#CCCCCC" style="font-size:12px"><td nowrap = "nowrap"><strong>Product Name</strong></td><td nowrap = "nowrap"><strong>Usage Type</strong></td><td nowrap = "nowrap"><strong>Purchase Type</strong></td><td nowrap = "nowrap"><strong>PIN Serial Number</strong></td><td nowrap = "nowrap"><strong>PIN Number</strong></td></tr>';

				$result4 = runmysqlquery($query4);
				$i_n = 0;
				while($fetch4 = mysqli_fetch_array($result4))
				{
					$i_n++;

					$grid .= '<tr style="font-size:14px">';
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch4['productname']." (".$fetch4['productcode'].")</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch4['usagetype']."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch4['purchasetype']."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch4['cardid']."</td>";
					$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch4['scratchnumber']."</td>";
					$grid .= "</tr>";
				}
				$grid .= '</table></div></td></tr></table></td></tr><tr><td colspan="4">&nbsp;</td></tr></table>';		
				echo($grid);
}
?>

  <!--<tr>
    <td width="14%">Dealer:</td>
    <td width="53%">&nbsp;</td>
    <td width="14%">Date (Time):</td>
    <td width="19%">&nbsp;</td>
  </tr>
  <tr>
    <td>Address:</td>
    <td colspan="3">&nbsp;</td>
  </tr><tr>
    <td colspan="4">&nbsp;</td>
  </tr><tr>
    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
      <tr>
        <td width="15%" style="padding:0"><div id="displayproductresult2" style="overflow:auto; height:150px; padding:2px;" align="center">
  
  Billdetail table.</div></td>
      </tr>
    </table></td>
  </tr>
  
  
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>Total Amount:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>Tax Amount:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>Net Amount:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  Scratch card details-->
<!--<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><table width="100%" border="0" cellpadding="3" cellspacing="0" style="border:1px solid #3399FF;">
<tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="31%" style="border-bottom:1px solid #3399FF;"><div align="left"><img src="../images/imax-logo.jpg" width="228" height="61" /></div></td>
<td width="48%" style="border-bottom:1px solid #3399FF;"><div align="center"><strong><font color="#3366CC">Relyon Softech Limited, Bangalore</font></strong><br />
<strong>Scratch Card Details</strong></div></td>
<td width="21%" style="border-bottom:1px solid #3399FF;"><div align="right"><img src="../images/inv_relyon-logo.gif" width="143" height="50" /></div></td>
</tr>
</table></td>
</tr>
<tr>
<td><table width="100%" border="0" cellpadding="4" cellspacing="0" style="border:1px solid #333333">
<tr>
<td valign="top" style="border-right:1px solid #333333">Dealer Business Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fetch1['businessname'].'<br />
<br />
Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fetch1['address'].'<br />
<br />
Place:&nbsp;&nbsp;&nbsp;&nbsp;'.$fetch1['place'].'&nbsp;&nbsp;&nbsp;District:&nbsp;&nbsp;&nbsp;&nbsp;'.$fetch1[districtname].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;State:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$fetch1['statename'].'</td>
<td valign="top"><div align="right">Date Time:&nbsp;&nbsp;&nbsp;&nbsp;'.changedateformatwithtime($fetch0['billdate']).'&nbsp;</div></td>
</tr>
<tr>
<td colspan="2" valign="top">&nbsp;</td>
</tr>
<tr>
<td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;"><tr><td width="15%" style="padding:0"><div id="displayproductresult2" style="overflow:auto; padding:2px;" align="center">';
$grid .= '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid"><div align="center">Sl No</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Bill Number</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Product Name</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Product Quantity</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Product Amount</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Usage Type</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Purchase Type</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Free</div></td><td nowrap = "nowrap" class="td-border-grid"><div align="center">Add Licence</div></td></tr></table></td>
          </tr>
        </table></td>
        </tr>
<tr>
<td colspan="2" valign="top">&nbsp;</td>
</tr>
<tr>
<td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="78%">&nbsp;</td>
<td width="9%">Total:</td>
<td width="13%">'.$fetch3['total'].'</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Tax Amount:</td>
<td>'.$fetch3['taxamount'].'</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Net Amount:</td>
<td>'.$fetch3['netamount'].'</td>
</tr>
</table></td>
</tr>
<tr>
  <td colspan="2" valign="top">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" valign="top"><tr>
    <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
      <tr>
        <td width="15%" style="padding:0"><div id="displayproductresult3" style="overflow:auto; padding:2px;" align="center"><table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Product Name</td><td nowrap = "nowrap" class="td-border-grid">Usage Type</td><td nowrap = "nowrap" class="td-border-grid">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid">Card Number</td><td nowrap = "nowrap" class="td-border-grid">Pin Number</td></tr></table></div></td>
</tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
</table>-->
