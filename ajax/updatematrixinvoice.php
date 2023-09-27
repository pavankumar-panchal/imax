<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

include('../inc/checkpermission.php');
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'generateinvoicenolist':
	{
		$generateinvoicenolistarray = array();
		$query = "SELECT slno,invoiceno FROM inv_matrixinvoicenumbers where purchasetype = 'Product'  ORDER BY slno";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generateinvoicenolistarray[$count] = $fetch['invoiceno'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generateinvoicenolistarray));
	}
	break;
	/*case 'getuserinvoicelist':
	{
		$customerreference = $_POST['customerreference'];
		$query = "select slno, invoiceno from inv_matrixinvoicenumbers where right(customerid,5) = '".$customerreference."'";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		{
			$grid = '<select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" > <option value="">Select a Invoice</option>';
			while($fetch = mysqli_fetch_array($result))
			{
				$grid .='<option value="'.$fetch['slno'].'">'.$fetch['invoiceno'].'</option>';
			}
			$grid .= '</select>';
			echo('1^'.$grid);
		}
		else
		{
			$grid = 'No Invoice Available';
			echo('2^'.$grid);
		}
		
	}
	break;*/
	
	
	case 'listinvoicedetails':
	{
		$slno = $_POST['slno'];
		$type = $_POST['type'];
		$query1 = "SELECT * from inv_matrixinvoicenumbers where slno = '".$slno."'";
		$result = runmysqlquery($query1);
		$fetchresult = runmysqlqueryfetch($query1);
		$appendzero = '.00';
		$total_productamount =0;
		if($type == 'display')
		{
			$classdefine = "swifttext-white";
			$disable = 'readonly="readonly"';
			
		}
		
		$dealerlist = selectdealer($fetchresult['dealerid']);
		
		// $grid = '<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF"><tr><td style="padding:3px"><div  align="center"><font color="#FF0000"><font size="2px"><strong>'.$fetchresult['invoiceheading'].'</strong></font></font></div></td></tr><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%" valign="top">';
		// $grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class = "table-border-grid">';
		// $grid .= '<tr class="tr-grid-header"><td width="60%" colspan="2" align="left" valign="top" ><strong>Customer Details</strong></td><td width="46%" align="left" valign="top" ><strong>Invoice Details</strong></td></tr>';
		// $grid .= '<tr>
		// <td colspan="2" style="text-align:left" class ="td-border-grid"><strong>Customer ID:</strong> <span id="customerid">'.$fetchresult['customerid'].'</span><input type="hidden"  style="width: 80px; text-align: right;" maxlength="10" id="seztax" name="seztax" value="'.$fetchresult['seztaxtype'].'"></td><td style="text-align:left" class ="td-border-grid"><strong>Date: </strong><span id="billdate">'.changedateformatwithtime($fetchresult['createddate']).'</span></td></tr>';
		// $grid .= '<tr height="30px"><td width="60%" colspan="2" align="left" valign="top"  class ="td-border-grid" ><span style="text-align:left"><input name="businessname" type="text" class="'.$classdefine.'" id="businessname" size="53" maxlength="400"  autocomplete="off" value = "'.$fetchresult['businessname'].'" '.$disable.'/></span></td><td width="46%" align="left" valign="top"   class ="td-border-grid"><span style="text-align:left"><strong>No:</strong> <span style="text-align:right" id="billnumber">'.$fetchresult['invoiceno'].'</span></span></td></tr>';
		// $grid .= '<tr height="60px"><td width="60%" colspan="2" align="left" valign="top"  class ="td-border-grid"><span style="text-align:left"><textarea name="address" type="text" class="'.$classdefine.'" id="address"   autocomplete="off" rows="2" cols="50" '.$disable.' >'.stripslashes(stripslashes($fetchresult['address'])).'</textarea></span></td> <td style="text-align:left" width="40%"    class ="td-border-grid" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
		// <tr><td>Marketing Exe:</td><td><span id="dealerinputdiv"style="display:block" ><input type="text" name="displaydealer" id="displaydealer" value="'.$fetchresult['dealername'].'"  '.$disable.'  class="swifttext"  style="width:160px;"/></span> <span style="display: none;" id="dealerselectiondiv">'.$dealerlist.'</span></td></tr></table></td></tr>';
		// $grid .= '<tr height="30px"><td width="60%" colspan="2" style="text-align:left"   class ="td-border-grid"><strong>Contact Person:</strong> <input type="text" name="contactperson" id="contactperson" class="'.$classdefine.'" size="35" value = "'.$fetchresult['contactperson'].'" '.$disable.'/></td><td style="text-align:left" width="46%" class ="td-border-grid"><strong>Service Tax No:</strong> AABCR7796NST001</td></tr>';
		// $grid .= '<tr height="30px" ><td width="60%" colspan="2"  style="text-align:left"   class ="td-border-grid"><strong>Email:</strong>&nbsp;<input type="text" name="emailid" id="emailid" class="'.$classdefine.'" size="44" value = "'.$fetchresult['emailid'].'" '.$disable.'/></td><td style="text-align:left" width="46%" class ="td-border-grid"><strong>Region: </strong> <span id="region">'.$fetchresult['region'].'</span> / <span id="branch">'.$fetchresult['branch'].'</span></td></tr>';
		// $grid .= '<tr height="30px"><td style="text-align:left"   class ="td-border-grid"><strong>Phone:</strong>&nbsp;<input type="text" name="phone" id="phone" class="'.$classdefine.'" size="20" value = "'.$fetchresult['phone'].'" '.$disable.'/></td><td style="text-align:left"   class ="td-border-grid"><strong>Cell:</strong>&nbsp;<input type="text" name="cell" id="cell" class="'.$classdefine.'" size="20"  value = "'.$fetchresult['cell'].'" '.$disable.'/></td><td style="text-align:left"  class ="td-border-grid"><strong>Company\'s PAN:</strong> AABCR7796N</td></tr>';
		// $grid .= '<tr height="30px"><td colspan="2" style="text-align:left"   class ="td-border-grid"><strong>Type of Customer: </strong><span id="customertype">'.$fetchresult['customertype'].'</span></td><td style="text-align:left" class ="td-border-grid"><strong>Company\'s VAT TIN:</strong> 29730052233</td></tr>';
		// $grid .= '<tr ><td colspan="2" style="text-align:left" class ="td-border-grid"><strong>Category of Customer: </strong><span id="customercategory">'.$fetchresult['customercategory'].'</span></td><td style="text-align:left"  class ="td-border-grid"><strong>CST No:</strong> 71684955 &nbsp; <strong>w.e.f. :</strong> 16/1/2001<br /></td></tr>';
		
		$pingrid = '<table width="100%" cellspacing="0" cellpadding="4" border="0" class="grey-table-border-grid" >
		<tr bgcolor="#cccccc">
		<td width="3%"  class="grey-td-border-grid" style="text-align:left;">Sl No</td>
		<td width="20%"  class="grey-td-border-grid" style="text-align:left;">Product</td>
		<td width="10%"  class="grey-td-border-grid" style="text-align:left;">Purchase Type</td>
		<td width="3%"  class="grey-td-border-grid" style="text-align:left;">Qty</td>
		<td width="12%"  class="grey-td-border-grid" style="text-align:left;">Rate</td>
		<td width="10%"  class="grey-td-border-grid" style="text-align:left;">Amount</td></tr>';
    
 		$count = 0;$servicecount = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$description = $fetch['description'];
			$descriptionsplit = explode('*',$description);
			
			$prdcode = $fetch['products'];
			$prdcodesplit = explode('#',$prdcode);

			$productquantity = $fetch['productquantity'];
			$productquantitysplit = explode(',',$productquantity);

			$actualproductpricearray = $fetch['actualproductpricearray'];
			$productratesplit = explode('*',$actualproductpricearray);
			
			for($i=0,$j=0;$i<count($descriptionsplit),$j<count($productquantitysplit);$i++,$j++)
			{
				$descriptionline = explode('$',$descriptionsplit[$i]);
				{
					if($description <> '')
					{
						$count++;
						$productcodearray[] = $prdcodesplit[$i];
						$productslnoarray[] = $count;
						$productlistarray = selectionforproduct($productcodearray,$productslnoarray);
						$pingrid .= '<tr id="seletedproductgrid" height="30px">';
						$pingrid .= '<td style="text-align:centre;" class="grey-td-border-grid" width="3%" id="slno'.$descriptionline[0].'">'.$descriptionline[0].'</td>';
						$pingrid .= '<td  class="grey-td-border-grid"  style="text-align:left; " width="20%" id="productdespt"><div id="productinputdiv'.$count.'" style="display:block;padding-bottom:3px"><input type="text" size="45" autocomplete="off" maxlength="300" id="productvalue'.$count.'" class="'.$classdefine.'" name="productvalue'.$count.'" value="'.$descriptionline[1].'" '.$disable.' ></div><div id="productselectiondiv'.$count.'" style="display:none;padding-bottom:3px">'.$productlistarray.'<br/></div><strong>Serial</strong> : <font color="#FF3300">'.$descriptionline[3].'</span></span><input name="productselectedhidden[]" class="swiftselect" id="productselectedhidden " type = "hidden" value = "'.$descriptionline[3].'%%'.$descriptionline[4].'"> <input name="productslnoarrayhidden[]" class="swiftselect" id="productslnoarrayhidden" type = "hidden" value = "'.$count.'"><input name="itemtype'.$count.'" class="swiftselect" id="itemtype'.$count.'" type = "hidden" value = "product"></td>';
						$pingrid .= '<td nowrap="nowrap" class="grey-td-border-grid" width="10%"  align="center"><span align="center" id="editpurchasetype'.$count.'">'.$descriptionline[2].'</span><div align="center" id="displaypurchasediv'.$count.'" style="display:none"><a class="r-text" onclick ="editpurchasetype(\''.'editpurchasetype'.$count.'\',\''.'purchasetypehidden'.$count.'\');" style="cursor: pointer;" id="editpurchasetypelinkid'.$count.'">( Change )</a> </div> <input name="purchasetypehidden'.$count.'" class="swiftselect" id="purchasetypehidden'.$count.'" type = "hidden" value = "'.strtolower($descriptionline[2]).'" '.$disable.'></td>';
						$pingrid .= '<td  class="grey-td-border-grid" style="text-align: right;" width="3%">'.$productquantitysplit[$j].'<input name="proquantity'.$count.'" class="swiftselect" id="proquantity'.$count.'" type = "hidden" value = "'.$productquantitysplit[$j].'"></td>';
						$pingrid .= '<td  class="grey-td-border-grid" style="text-align: right;" width="12%"><input type="text"  style="width:57px; text-align: right;" autocomplete="off" maxlength="10" id="productrate'.$count.'" class="'.$classdefine.'" name="productrate'.$count.'" value="'.$productratesplit[$j].$appendzero.'" '.$disable.'  onkeyup ="amountchange(\'\',\''.$count.'\');" onchange ="amountchange(\'validate\',\''.$count.'\');"></td>';
						$pingrid .= '<td  class="grey-td-border-grid" style="text-align: right;" width="10%"><input type="text"  style="width:75px; text-align: right;" autocomplete="off" maxlength="10" id="productamount'.$count.'" class="'.$classdefine.'" name="productamount'.$count.'" value="'.$descriptionline[4].$appendzero.'" '.$disable.'  onkeyup ="calculatenormalprice(\'\',\''.$count.'\');" onchange ="calculatenormalprice(\'validate\',\''.$count.'\');"></td>';
						$pingrid .= "</tr>";
						$total_productamount = $total_productamount + $descriptionline[6];
					}
					
				}
			}

			$servicedescriptionsplit = explode('*',$fetch['servicedescription']);
			$servicedescriptioncount = count($servicedescriptionsplit);
			
			if($fetch['servicedescription'] <> '')
			{
				for($i=0; $i<$servicedescriptioncount; $i++)
				{
					$count++;
					$servicedescriptionline = explode('$',$servicedescriptionsplit[$i]);
					$pingrid .= '<tr id="seletedproductgrid" height="30px">';
					$pingrid .= '<td  style="text-align:centre;" class="grey-td-border-grid" width="3%" id="serviceslno'.$servicedescriptionline[0].'">'.$servicedescriptionline[0].'</td>';
					$pingrid .= '<td  style="text-align:left;" class="grey-td-border-grid" width="20%">'.$servicedescriptionline[1].' <input name="itemtype'.$count.'" class="swiftselect" id="itemtype'.$count.'" type = "hidden" value = "service"><input name="serviceselectedhidden[]" class="swiftselect" id="serviceselectedhidden" type = "hidden" value = "'.$servicedescriptionline[1].'"></td>';
					$pingrid .= '<td style="text-align:left;" class="grey-td-border-grid" width="10%">&nbsp;</td>';
					$pingrid .= '<td style="text-align:left;" class="grey-td-border-grid" width="3%">&nbsp;</td>';
					$pingrid .= '<td style="text-align:left;" class="grey-td-border-grid" width="12%">&nbsp;</td>';
					$pingrid .= '<td  style="text-align:right;" class="grey-td-border-grid" width="10%"><input type="text"  style="width: 80px; text-align: right;" autocomplete="off" maxlength="10" id="productamount'.$count.'" class="'.$classdefine.'" name="productamount'.$count.'" value="'.$servicedescriptionline[2].$appendzero.'" '.$disable.'  onkeyup ="calculatenormalprice();getpercentvalue();" onchange ="calculatenormalprice();getpercentvalue();"></td>';
					$pingrid .= "</tr>";
					$total_productamount = $total_productamount + $servicedescriptionline[2];
				}
			}
			
			$descriptioncount = count($descriptionsplit);
			if($fetch['servicedescription'] == '')
				$servicedescriptioncount = 0;
			else
				$servicedescriptioncount = count($servicedescriptionsplit);
			$rowcount =  $descriptioncount + $servicedescriptioncount ;
			if($rowcount < 8)
			{
				$pingrid .= updateaddlinebreak($rowcount);
	
			}			
		}
		  if($fetchresult['status'] == 'EDITED')
			{
				$query011 = "select * from inv_mas_users where slno = '".$fetchresult['editedby']."';";
				$resultfetch011 = runmysqlqueryfetch($query011);
				$changedby = $resultfetch011['fullname'];
				$statusremarks = 'Last updated by  '.$changedby.' on '.changedateformatwithtime($fetchresult['editeddate']).' <br/>'.$fetchresult['editedremarks'];
			}
			elseif($fetchresult['status'] == 'CANCELLED')
			{
				$query011 = "select * from inv_mas_users where slno = '".$fetchresult['cancelledby']."';";
				$resultfetch011 = runmysqlqueryfetch($query011);
				$changedby = $resultfetch011['fullname'];
				$statusremarks = 'Cancelled by '.$changedby.' on '.changedateformatwithtime($fetchresult['cancelleddate']).'  <br/>'.$fetchresult['cancelledremarks'];
	
			}
			elseif($fetchresult['status'] == 'ACTIVE')
				$statusremarks = 'Not Avaliable.';
		$invoiceremarks = ($fetchresult['invoiceremarks'] == '')?'None':$fetchresult['invoiceremarks'];
		$pingrid .= '<tr height="30px">
		<td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>Total</strong></td>
		<td width="10%" style="text-align: right;"  class="grey-td-border-grid">
		<input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="20" name="totalamount" id="totalamount" class="'.$classdefine.'" value = "'.$fetchresult['amount'].$appendzero.'" '.$disable.'></td></tr>';
		
		//added on 23.03.15
		$currentdate = strtotime(substr($fetchresult['createddate'],0,10));
		$expirydate = strtotime('2015-06-01');
		$sb_expirydate = strtotime('2015-11-15');
        
        //added on 17.06.2017
        $currentdate_invoice = substr($fetchresult['createddate'],0,10);
        //$gst_date = '2017-06-08'; // used to get date from gst_rates
        $gst_date = date('Y-m-d');//2017-06-08';
		$gst_tax_date = strtotime('2017-07-01');
		
        //gst rate fetching
		
		$gst_tax_query= "select igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '$gst_date' AND to_date >= '$gst_date'";
		//$gst_result = runmysqlquery($gst_tax_query);
		//$gst_count = mysqli_num_rows($gst_result);
		$gst_tax_result = runmysqlqueryfetch($gst_tax_query);
		$igst_tax_rate = $gst_tax_result['igst_rate'];
		$cgst_tax_rate = $gst_tax_result['cgst_rate'];
		$sgst_tax_rate = $gst_tax_result['sgst_rate'];
		
		//gst rate fetching ends
		/*----------------------------*/
       
        // $search_customer =  str_replace("-","",$fetchresult['customerid']);
        // $customer_details = "select inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        // inv_mas_district.statecode as state_code,inv_mas_state.statename as statename
        // ,inv_mas_state.state_gst_code as state_gst_code from inv_mas_customer 
        // left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode
        // left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
        // where inv_mas_customer.customerid like '%".$search_customer."%'";
		
        // $fetch_customer_details = runmysqlqueryfetch($customer_details);
        
        /*----------------------------*/  	
		#commeneted to enable new GSTIN
		
		/*if($fetch_customer_details['gst_no'] == '')
		{
			$gst_customer = 'Not Registered Under GST';
		}
		else
		{
			$gst_customer = $fetch_customer_details['gst_no'];
		}*/
		
		#aaded for multiple GSTIN 
		if($fetchresult['gst_no'] == '0' || $fetchresult['gst_no'] == '')
		{
			$gst_customer = 'Not Registered Under GST';
			//$customer_gst_code = $fetch_first_gst_details['state_gst_code'];
		}
		else
		{
			$gst_customer = $fetchresult['gst_no'];
			$customer_gst_code = substr($fetchresult['gst_no'], 0, 2);
		}
    
	     #aaded for multiple GSTIN Ends 
		 
		$branchdetails = "select * from inv_mas_branch where branch_gst_code = '".$fetchresult['state_info']."'";
        $fetchdetails = runmysqlqueryfetch($branchdetails);
        $branch_gstin = $fetchdetails['branch_gstin'];
  
  
		/*--------------Generating Main Grid -----------------*/
		
		$grid = '<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF"><tr><td style="padding:3px"><div  align="center"><font color="#FF0000"><font size="2px"><strong>'.$fetchresult['invoiceheading'].'</strong></font></font></div></td></tr><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="100%" valign="top">';
		$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4" class = "table-border-grid">';
		$grid .= '<tr class="tr-grid-header"><td width="60%" colspan="2" align="left" valign="top" ><strong>Customer Details</strong></td><td width="46%" align="left" valign="top" ><strong>Invoice Details</strong></td></tr>';
		$grid .= '<tr><td colspan="2" style="text-align:left" class ="td-border-grid"><strong>Customer GSTIN:</strong> <span id="customerid">'.$gst_customer.'</span></td><td style="text-align:left" class ="td-border-grid"><strong></strong></td></tr>';
		$grid .= '<tr><td colspan="2" style="text-align:left" class ="td-border-grid"><strong>Customer ID:</strong> <span id="customerid">'.$fetchresult['customerid'].'</span><input type="hidden"  style="width: 80px; text-align: right;" maxlength="10" id="seztax" name="seztax" value="'.$fetchresult['seztaxtype'].'"></td><td style="text-align:left" class ="td-border-grid"><strong>Date: </strong><span id="billdate">'.changedateformatwithtime($fetchresult['createddate']).'</span></td></tr>';
		$grid .= '<tr height="30px"><td width="60%" colspan="2" align="left" valign="top"  class ="td-border-grid" ><span style="text-align:left"><input name="businessname" type="text" class="'.$classdefine.'" id="businessname" size="53" maxlength="400"  autocomplete="off" value = "'.$fetchresult['businessname'].'" '.$disable.'/></span></td><td width="46%" align="left" valign="top"   class ="td-border-grid"><span style="text-align:left"><strong>No:</strong> <span style="text-align:right" id="billnumber">'.$fetchresult['invoiceno'].'</span></span></td></tr>';
		$grid .= '<tr height="60px"><td width="60%" colspan="2" align="left" valign="top"  class ="td-border-grid"><span style="text-align:left"><textarea name="address" type="text" class="'.$classdefine.'" id="address"   autocomplete="off" rows="2" cols="50" '.$disable.' >'.stripslashes(stripslashes($fetchresult['address'])).'</textarea></span></td> <td style="text-align:left" width="40%"    class ="td-border-grid" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td>Marketing Exe:</td><td><span id="dealerinputdiv"style="display:block" ><input type="text" name="displaydealer" id="displaydealer" value="'.$fetchresult['dealername'].'"  '.$disable.'  class="swifttext"  style="width:160px;"/></span> <span style="display: none;" id="dealerselectiondiv">'.$dealerlist.'</span></td></tr></table></td></tr>';
		$grid .= '<tr height="30px"><td width="60%" colspan="2" style="text-align:left"   class ="td-border-grid"><strong>Contact Person:</strong> <input type="text" name="contactperson" id="contactperson" class="'.$classdefine.'" size="35" value = "'.$fetchresult['contactperson'].'" '.$disable.'/></td><td style="text-align:left" width="46%" class ="td-border-grid"><strong>Company\'s GSTIN:</strong>'.$branch_gstin.'</td></tr>';
		$grid .= '<tr height="30px" ><td width="60%" colspan="2"  style="text-align:left"   class ="td-border-grid"><strong>Email:</strong>&nbsp;<input type="text" name="emailid" id="emailid" class="'.$classdefine.'" size="44" value = "'.$fetchresult['emailid'].'" '.$disable.'/></td><td style="text-align:left" width="46%" class ="td-border-grid"><strong>Region: </strong> <span id="region">'.$fetchresult['region'].'</span> / <span id="branch">'.$fetchresult['branch'].'</span></td></tr>';
		$grid .= '<tr height="30px"><td style="text-align:left"   class ="td-border-grid"><strong>Phone:</strong>&nbsp;<input type="text" name="phone" id="phone" class="'.$classdefine.'" size="20" value = "'.$fetchresult['phone'].'" '.$disable.'/></td><td style="text-align:left"   class ="td-border-grid"><strong>Cell:</strong>&nbsp;<input type="text" name="cell" id="cell" class="'.$classdefine.'" size="20"  value = "'.$fetchresult['cell'].'" '.$disable.'/></td><td style="text-align:left"  class ="td-border-grid"><strong>Company\'s PAN:</strong> AABCR7796N</td></tr>';
		$grid .= '<tr height="30px"><td colspan="2" style="text-align:left"   class ="td-border-grid"><strong>Type of Customer: </strong><span id="customertype">'.$fetchresult['customertype'].'</span></td><td style="text-align:left" class ="td-border-grid"></td></tr>';
		$grid .= '<tr ><td colspan="2" style="text-align:left" class ="td-border-grid"><strong>Category of Customer: </strong><span id="customercategory">'.$fetchresult['customercategory'].'</span></td><td style="text-align:left"  class ="td-border-grid"><br /></td></tr>';
    
    	/*---------------Main Grid Edit -------------------------*/
    
		if(($fetchresult['cgst'] != '0') &&  ($fetchresult['sgst'] != '0') || ($customer_gst_code == 29 && $fetchresult['cgst'] == '0' &&  $fetchresult['sgst'] == '0' && ($fetch['seztaxtype'] == 'yes' || $fetch_first_gst_details['sez_enabled'] == 'yes')))
		{
			$net_amount = $fetchresult['netamount'];
			$pingrid .= '<tr height="30px"><td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>CGST @ '.$cgst_tax_rate.'%</strong></td><td width="10%" style="text-align: right;"  class="grey-td-border-grid"><input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="20" id="cgst_tax" class="'.$classdefine.'" name="cgst_tax" value = "'.$fetchresult['cgst'].'" '.$disable.'></td></tr>';
			$pingrid .= '<tr height="30px"><td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>SGST @ '.$sgst_tax_rate.'%</strong></td><td width="10%" style="text-align: right;"  class="grey-td-border-grid"><input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="20" id="sgst_tax" class="'.$classdefine.'" name="sgst_tax" value = "'.$fetchresult['sgst'].'" '.$disable.'></td></tr>';
			$pingrid .= '<tr height="30px"><td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>Net Amount</strong></td><td width="10%" style="text-align: right;"  class="grey-td-border-grid"><input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="10" id="netamount" class="'.$classdefine.'" name="netamount"  value = "'.$net_amount.$appendzero.'" '.$disable.'></td></tr>';
			$pingrid .= '<tr><td style="text-align: left;" colspan="6"  class="grey-td-border-grid" id="rupeeinwords"><strong>Rupee In Words</strong>: <span id="amountinwords">'.$fetchresult['amountinwords'].'</span> only</td></tr></table>';
		}
		else
		{
			$net_amount = $fetchresult['netamount'];
			$pingrid .= '<tr height="30px"><td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>IGST @ '.$igst_tax_rate.'%</strong></td><td width="10%" style="text-align: right;"  class="grey-td-border-grid"><input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="20" id="igst_tax" class="'.$classdefine.'" name="igst_tax" value = "'.$fetchresult['igst'].'" '.$disable.'></td></tr>';
			$pingrid .= '<tr height="30px"><td style="text-align: right;" colspan="5"  class="grey-td-border-grid"><strong>Net Amount</strong></td><td width="10%" style="text-align: right;"  class="grey-td-border-grid"><input type="text" style="width: 80px; text-align: right;" autocomplete="off" maxlength="10" id="netamount" class="'.$classdefine.'" name="netamount"  value = "'.$net_amount.$appendzero.'" '.$disable.'></td></tr>';
			$pingrid .= '<tr><td style="text-align: left;" colspan="6"  class="grey-td-border-grid" id="rupeeinwords"><strong>Rupee In Words</strong>: <span id="amountinwords">'.$fetchresult['amountinwords'].'</span> only</td></tr></table>';    
		}
		
		//end gstif		

			$grid .= '<tr ><td colspan="3" align="left" valign="top" class ="td-border-grid">'.$pingrid.'</td></tr>';
			$grid .= '<tr><td colspan="2" style="text-align:left" valign="top" class ="td-border-grid" >
			<table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td ><div align="left"><strong>Invoice Remarks: </strong><input name="invoiceremarks" type="text" class="'.$classdefine.'" id="invoiceremarks" size="42" maxlength="200"  autocomplete="off" value = "'.$invoiceremarks.'" '.$disable.'/></div></td></tr><tr><td><div align="left"><strong> Payment Remarks: </strong><span id="paymentremarks">'.$fetchresult['remarks'].'</span></div></td></tr></table></td><td style="text-align:left" class ="td-border-grid"><div align="center"><font color="#FF0000">For <strong>RELYON SOFTECH LTD</strong></font> <br /><br /><br />Authorised Signatory </div></td></tr>';
			
			$productcode = $fetchresult['products'];
			$irn = $fetchresult['irn'];
			if($irn!= "")
			{
				$irnstatus = '1';
			}
			
			$listinvoicedetailsarray = array();
			$listinvoicedetailsarray['errorcode'] = '1';
			$listinvoicedetailsarray['grid'] = $grid;

			$listinvoicedetailsarray['statusremarks'] = $statusremarks;
			$listinvoicedetailsarray['status'] = $fetchresult['status'];
			$listinvoicedetailsarray['slno'] = $fetchresult['slno'];
			$listinvoicedetailsarray['productcode'] = $productcode;
			$listinvoicedetailsarray['productquantity'] = $fetchresult['productquantity'];
			$listinvoicedetailsarray['businessname'] = $fetchresult['businessname'];
			$listinvoicedetailsarray['dealerid'] = $fetchresult['dealerid'];
			$listinvoicedetailsarray['irnstatus'] = $irnstatus;
			
			echo(json_encode($listinvoicedetailsarray));
		//echo('1^'.$grid.'^'.$statusremarks.'^'.$fetchresult['status'].'^'.$fetchresult['slno'].'^'.$productcode.'^'.$fetchresult['businessname'].'^'.$fetchresult['dealerid']);
	}
	break;
	
	case 'editinvoice':
	{		
		$igst_taxamount = $_POST['igsttaxamount'];
		$cgst_taxamount = $_POST['cgsttaxamount'];
		$sgst_taxamount = $_POST['sgsttaxamount'];
		
		$invoiceno = $_POST['invoiceno'];
		$invoiceremarks = $_POST['invoiceremarks'];
		$invoiceremarks = ($invoiceremarks == '')?'None':$invoiceremarks;
		$amountinwords = $_POST['amountinwords'];
		
		//offer descrption values
		$descriptiontypevalues = removedoublecomma($_POST['descriptiontypevalues']);
		$descriptionvalues = removedoublecomma($_POST['descriptionvalues']);
		
		//$descriptionamountvalues =str_replace(".00","",removedoublecomma($_POST['descriptionamountvalues']));
		
		$descriptionamountvalues = removedoublecomma($_POST['descriptionamountvalues']);
		$descriptionamountvaluessplit = explode('~',$descriptionamountvalues);
		$descriptionvaluesplit = explode('~',$descriptionvalues);
		$descriptiontypevaluessplit = explode('~',$descriptiontypevalues);
		$descriptiontypevaluescount = count($descriptiontypevaluessplit);
				
		//Product values
		$purchasevalues = removedoublecomma($_POST['purchasevalues']);
		$cardlist = $_POST['cardlist'];
		$cardlistsplit = explode('#',$cardlist);
		$cardlistsplitcount = count($cardlistsplit);
		$productamountvalues = str_replace(".00","",removedoublecomma($_POST['productamountvalues']));
		$productlist = $_POST['productcodevalues'];
		$productlistsplit = explode('#',$productlist);
		$productlistsplitcount =  count($productlistsplit);
		$purchasevaluesplit = explode(',',$purchasevalues);
		$productamountsplit = explode(',',$productamountvalues);
		$productslnovalues = trim(removedoublecomma($_POST['productslnovalues']),',');
		$productslnovaluessplit = explode(',',$productslnovalues);
		$productratevalues = str_replace(".00","",removedoublecomma($_POST['productratevalues']));
		$productratesplit = explode(',',$productratevalues);

		$editremarks = $_POST['editremarks'];
		$businessname = $_POST['businessname'];
		$address = $_POST['address'];
		$contactperson = $_POST['contactperson'];
		$emailid = $_POST['emailid'];
		$phone = $_POST['phone'];
		$cell = $_POST['cell'];
		$dealerid = $_POST['dealerid'];
		$productarraycount = 0;
		
		for($i=1;$i<count($productamountsplit);$i++)
		{
			if($productamountsplit[$i] == '')
			{
				echo('2^Invalid Entry1^'.$productamountvalues); exit;
			}
		}
		
		for($i=0;$i<count($descriptiontypevaluessplit);$i++)
		{
			if($descriptiontypevaluessplit[$i] <> '')
			{
				if($descriptionvaluesplit[$i] == '')
				{
					echo('2^Invalid Entry2^'.$descriptiontypevalues); exit;
				}
				if($descriptionamountvaluessplit[$i] == '')
				{
					echo('2^Invalid Entry3^'.$descriptionamountvalues); exit;
				}
			}
		}
		$descamt = getdescriptionamount($descriptionamountvalues,$descriptiontypevalues);

		$calculatedprice = calculatenormalprice($productamountsplit,$productratesplit);
		$calculatedpricesplit = explode('$',$calculatedprice);
		$totalproductprice = $calculatedpricesplit[0];
		$totalproductpricearray = $calculatedpricesplit[1];
		$actualproductpricearray = $calculatedpricesplit[2];
		$totalproductprice = $totalproductprice + $descamt ;
		
		$igsttax = $igst_taxamount;
		$cgsttax = $cgst_taxamount;
		$sgsttax = $sgst_taxamount;
		
		$netamount =  $igsttax + $cgsttax + $sgsttax + $totalproductprice;
		$purchasetypesplit = explode(',',$purchasevalues);
		$usagetypesplit = explode(',',$usagevalues);

		
		if($productslnovalues <> '')
		{
			//retrive pin number
			for($i=0;$i<$productlistsplitcount;$i++)
			{
				$query = "select * from inv_mas_matrixproduct where id = '".$productlistsplit[$i]."'";
				$result666 = runmysqlqueryfetch($query);
				$productname[] = $result666['productname'];
			}
		}
		for($i=0;$i<$cardlistsplitcount;$i++)
		{
			$productpinvalue[] = explode('%%',$cardlistsplit[$i]);
		}
		
		if($productslnovalues <> '')
		{
			//Product description
			for($i=0; $i<$productlistsplitcount; $i++)
			{
			
				if($productarraycount > 0)
					$description .= '*';
				
				if($purchasevaluesplit[$i] == 'new')
					$purchasetype = 'New';
				else
					$purchasetype = 'Updation';
				
				$description .= $productslnovaluessplit[$i].'$'.$productname[$i].'$'.$purchasetype.'$'.$productpinvalue[$i][0].'$'.$productamountsplit[$i];
				$productarraycount++;
				
			}
		}
		//echo $description; exit;
		//Fetch dealer Name from dealer master
		$queryres = "Select businessname as dealername from inv_mas_dealer where slno = '".$dealerid."'";
		$result55 = runmysqlqueryfetch($queryres);
		
		//$igsttax = $igsttax.'.00';
		//$cgsttax = $cgsttax.'.00';
		//$sgsttax = $sgsttax.'.00';
		
		$netamount = round($netamount);
		
		//Update the inv_matrixinvoicenumbers table
		$invoicequery = "update inv_matrixinvoicenumbers set description = '".$description."', amount = '".$totalproductprice."',igst = '".$igsttax."',cgst = '".$cgsttax."',sgst = '".$sgsttax."', netamount = '".$netamount."',businessname = '".$businessname."',phone =  '".$phone."',cell = '".$cell."',emailid = '".$emailid."',contactperson = '".$contactperson."',address ='".addslashes($address)."', amountinwords = '".$amountinwords."',  invoiceremarks = '".$invoiceremarks."', dealername = '".$result55['dealername']."', dealerid = '".$dealerid."',status = 'EDITED', editeddate = '".date('Y-m-d').' '.date('H:i:s')."',editedip = '".$_SERVER['REMOTE_ADDR']."',editedby = '".$userid ."',editedremarks = '".$editremarks."',amountinwords = '".$amountinwords."',totalproductpricearray = '".$totalproductpricearray."',actualproductpricearray='".$actualproductpricearray."',products = '".$productlist."'  where slno  ='".$invoiceno."';";
		$invoiceresult = runmysqlquery($invoicequery);
		
		//echo "this is messis";
		//exit();
		
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','276','".date('Y-m-d').' '.date('H:i:s')."','Matrix_Invoice_editing')";
		$eventresult = runmysqlquery($eventquery);
		
		
		//Get dealer details
		$query0 = "select inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid from inv_mas_dealer  where inv_mas_dealer.slno = '".$dealerid."';";
		$fetch0 = runmysqlqueryfetch($query0);
		$regionid = $fetch0['regionid'];
		$branchid = $fetch0['branchid'];
		
	 	//resend the mail after sucessfully updated	
		resendinvoice('',$invoiceno);	
		
		$queryresult88 = "Select `status`,editedby,editeddate,editedremarks from inv_matrixinvoicenumbers where slno  ='".$invoiceno."';";
		$result221 = runmysqlqueryfetch($queryresult88);
		
		if($result221['status'] == 'EDITED')
		{
			$query066 = "select * from inv_mas_users where slno = '".$result221['editedby']."';";
			$resultfetch022 = runmysqlqueryfetch($query066);
			$changedby1 = $resultfetch022['fullname'];
			$statusremarks1 = 'Last updated by  '.$changedby1.' on '.changedateformatwithtime($result221['editeddate']).' <br/>'.$result221['editedremarks'];
		}
		elseif($result221['status'] == 'CANCELLED')
		{
			$query066 = "select * from inv_mas_users where slno = '".$result221['cancelledby']."';";
			$resultfetch022 = runmysqlqueryfetch($query066);
			$changedby1 = $resultfetch022['fullname'];
			$statusremarks1 = 'Cancelled by '.$changedby1.' on '.changedateformatwithtime($result221['cancelleddate']).'  <br/>'.$result221['cancelledremarks'];

		}
		elseif($result221['status'] == 'ACTIVE')
			$statusremarks1 = 'Not Avaliable.';
		
		
		echo(json_encode('1^'.'Record Edited Successfully.'.'^'.$result221['status'].'^'.$statusremarks1));
		
	}
	break;
	
	//einvoice cannot be canceled after time limit
	case 'cancelinvoice':
	{
		$irnstatus = "";
		$invoiceno = $_POST['invoiceno'];
		$productquantity = explode(',',$_POST['productquantity']);
		$cancelremarks = $_POST['cancelremarks'];
		$productlist = $_POST['productlist'];
		$productlistsplit = explode('#',$productlist);
		$productlistsplitcount =  count($productlistsplit);
		$cancelreason = $_POST['cancelreason']; //exit;

		$cnlquery = "select * from inv_matrixinvoicenumbers where slno  ='".$invoiceno."';";
		$cnlquery = runmysqlqueryfetch($cnlquery);
		$irn = $cnlquery['irn'];
		$branch_gst_code = $fetchdetails['state_info'];

		if(!empty($irn) && $irn!="")
		{
			require_once('generatematrixirn.php');
			//api call(cancel IRN)
			//Prepare you post parameters
			$postIrnData = [
				"Irn"=> $irn,
				"CnlRsn"=> $cancelreason,
				"CnlRem"=> $cancelremarks
			];
			$post_irn_data = json_encode($postIrnData); 
			//print_r($post_irn_data);
			//exit;
			$irnurl = "https://demo.saralgsp.com/eicore/v1.03/Invoice/Cancel";

			//open connection
			$irnCurl = curl_init();

			curl_setopt($irnCurl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($irnCurl, CURLOPT_URL, $irnurl);

			//So that curl_exec returns the contents of the cURL; rather than echoing it
			curl_setopt($irnCurl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($irnCurl,CURLOPT_POST, true);

			// Set HTTP Header for POST request 
			curl_setopt($irnCurl, CURLOPT_HTTPHEADER, array(
				"AuthenticationToken: $authenticationToken",
				"SubscriptionId: $subscriptionId",
				"UserName: $UserName",
				"AuthToken: $AuthToken",
				"sek: $sek",
				"Gstin: $gstin",
				"Content-Type: application/json",
				)
			);

			//to find the content-length for header we use postdata
			curl_setopt($irnCurl, CURLOPT_POSTFIELDS, $post_irn_data);

			//execute post
			$irnresult = curl_exec($irnCurl);

			//Print error if any
			if(curl_errno($irnCurl))
			{
				echo 'error:' . curl_error($irnCurl);
			} 
			curl_close($irnCurl);
			$irndata = json_decode($irnresult,true); 
			$status = $irndata['status'];
			if($status === 0)
			{
				$errorcode=$irndata['errorDetails'][0]['errorCode'];
				$errormsg=$irndata['errorDetails'][0]['errorMessage'];
			}
			else
				$irnstatus = 1;
					
		}
		else{
			$irnstatus = 1;
		}
		//echo $status; exit;
		if($irnstatus == 1)
		{
			//Update the inv_matrixinvoicenumbers table
			$invoicequery = "update inv_matrixinvoicenumbers set status = 'CANCELLED',cancelleddate = '".date('Y-m-d').' '.date('H:i:s')."',cancelledip = '".$_SERVER['REMOTE_ADDR']."',cancelledby = '".$userid ."',cancelledremarks = '".$cancelremarks."'  where slno  ='".$invoiceno."';";
			$invoiceresult = runmysqlquery($invoicequery);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','277','".date('Y-m-d').' '.date('H:i:s')."','Matrix_Invoice_cancellation')";
			$eventresult = runmysqlquery($eventquery);
			
			//resend the mail after Cancelled sucessfully	
			resendinvoice('',$invoiceno);	
			
			$queryresult55 = "Select `status`,cancelledby,cancelleddate,cancelledremarks from inv_matrixinvoicenumbers where slno  ='".$invoiceno."';";
			$result221 = runmysqlqueryfetch($queryresult55);
			
			if($result221['status'] == 'EDITED')
			{
				$query077 = "select * from inv_mas_users where slno = '".$result221['cancelledby']."';";
				$resultfetch022 = runmysqlqueryfetch($query077);
				$changedby1 = $resultfetch022['fullname'];
				$statusremarks1 = 'Last updated by  '.$changedby1.' on '.changedateformatwithtime($result221['cancelleddate']).' <br/>'.$result221['cancelledremarks'];
			}
			elseif($result221['status'] == 'CANCELLED')
			{
				$query077 = "select * from inv_mas_users where slno = '".$result221['cancelledby']."';";
				$resultfetch022 = runmysqlqueryfetch($query077);
				$changedby1 = $resultfetch022['fullname'];
				$statusremarks1 = 'Cancelled by '.$changedby1.' on '.changedateformatwithtime($result221['cancelleddate']).'  <br/>'.$result221['cancelledremarks'];

			}
			elseif($result221['status'] == 'ACTIVE')
				$statusremarks1 = 'Not Avaliable.';

			echo(json_encode('2^'.'Record Cancelled Successfully.'.'^'.$result221['status'].'^'.$statusremarks1));
		}
		else{
			echo(json_encode('3^'.$errormsg.'^'.$errorcode));
		}
	}
	break;

	//einvoice cancellation after 24hrs in Imax
	case 'canceleinv':
	{
		$irnstatus = "";
		$invoiceno = $_POST['invoiceno'];
		$productquantity = explode(',',$_POST['productquantity']);
		$cancelremarks = $_POST['cancelremarks'];
		$productlist = $_POST['productlist'];
		$productlistsplit = explode('#',$productlist);
		$productlistsplitcount =  count($productlistsplit);
		$cancelreason = $_POST['cancelreason'];
		$cancelconfirm = $_POST['cancelconfirm']; //exit;

		$cnlquery = "select * from inv_matrixinvoicenumbers where slno  ='".$invoiceno."';";
		$cnlquery = runmysqlqueryfetch($cnlquery);
		$irn = $cnlquery['irn'];
		$branch_gst_code = $cnlquery['state_info'];
		
		if($cancelconfirm!= 'cancelconfirm')
		{
			if(!empty($irn) && $irn!="")
			{
				require_once('generatematrixirn.php');
				//api call(cancel IRN)
				//Prepare you post parameters
				$postIrnData = [
					"Irn"=> $irn,
					"CnlRsn"=> $cancelreason,
					"CnlRem"=> $cancelremarks
				];
				$post_irn_data = json_encode($postIrnData); 
				//print_r($post_irn_data);
				//exit;
				$irnurl = "https://demo.saralgsp.com/eicore/v1.03/Invoice/Cancel";
	
				//open connection
				$irnCurl = curl_init();
	
				curl_setopt($irnCurl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($irnCurl, CURLOPT_URL, $irnurl);
	
				//So that curl_exec returns the contents of the cURL; rather than echoing it
				curl_setopt($irnCurl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($irnCurl,CURLOPT_POST, true);
	
				// Set HTTP Header for POST request 
				curl_setopt($irnCurl, CURLOPT_HTTPHEADER, array(
					"AuthenticationToken: $authenticationToken",
					"SubscriptionId: $subscriptionId",
					"UserName: $UserName",
					"AuthToken: $AuthToken",
					"sek: $sek",
					"Gstin: $gstin",
					"Content-Type: application/json",
					)
				);
	
				//to find the content-length for header we use postdata
				curl_setopt($irnCurl, CURLOPT_POSTFIELDS, $post_irn_data);
	
				//execute post
				$irnresult = curl_exec($irnCurl);
	
				//Print error if any
				if(curl_errno($irnCurl))
				{
					echo 'error:' . curl_error($irnCurl);
				} 
				curl_close($irnCurl);
				$irndata = json_decode($irnresult,true); 
				$status = $irndata['status'];
				if($status === 0)
				{
					$errorcode=$irndata['errorDetails'][0]['errorCode'];
					$errormsg=$irndata['errorDetails'][0]['errorMessage'];
				}
				else
					$irnstatus = 1;
			}
			else{
				$irnstatus = 1;
			}
		}
		else{
			$irnstatus = 1;
		}
		//echo $status; exit;
		if($irnstatus == 1)
		{
			
			//Update the inv_matrixinvoicenumbers table
			$invoicequery = "update inv_matrixinvoicenumbers set status = 'CANCELLED',cancelleddate = '".date('Y-m-d').' '.date('H:i:s')."',cancelledip = '".$_SERVER['REMOTE_ADDR']."',cancelledby = '".$userid ."',cancelledremarks = '".$cancelremarks."'  where slno  ='".$invoiceno."';";
			$invoiceresult = runmysqlquery($invoicequery);
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','277','".date('Y-m-d').' '.date('H:i:s')."','Matrix_Invoice_cancellation')";
			$eventresult = runmysqlquery($eventquery);
			
			//resend the mail after Cancelled sucessfully	
			resendinvoice('',$invoiceno);	
			
			$queryresult55 = "Select `status`,cancelledby,cancelleddate,cancelledremarks from inv_matrixinvoicenumbers where slno  ='".$invoiceno."';";
			$result221 = runmysqlqueryfetch($queryresult55);
			
			if($result221['status'] == 'EDITED')
			{
				$query077 = "select * from inv_mas_users where slno = '".$result221['cancelledby']."';";
				$resultfetch022 = runmysqlqueryfetch($query077);
				$changedby1 = $resultfetch022['fullname'];
				$statusremarks1 = 'Last updated by  '.$changedby1.' on '.changedateformatwithtime($result221['cancelleddate']).' <br/>'.$result221['cancelledremarks'];
			}
			elseif($result221['status'] == 'CANCELLED')
			{
				$query077 = "select * from inv_mas_users where slno = '".$result221['cancelledby']."';";
				$resultfetch022 = runmysqlqueryfetch($query077);
				$changedby1 = $resultfetch022['fullname'];
				$statusremarks1 = 'Cancelled by '.$changedby1.' on '.changedateformatwithtime($result221['cancelleddate']).'  <br/>'.$result221['cancelledremarks'];

			}
			elseif($result221['status'] == 'ACTIVE')
				$statusremarks1 = 'Not Avaliable.';

			echo(json_encode('2^'.'Record Cancelled Successfully.'.'^'.$result221['status'].'^'.$statusremarks1));
		}
		else{
			echo(json_encode('4^'.$errormsg.'^'.$errorcode));
		}
	}
	break;
	
	case 'searchdealerlist':
	{
		$searchcustomerlistarray = array();
		$databasefield = $_POST['databasefield'];
		$textfield = $_POST['textfield'];
		$state = $_POST['state'];
		$region = $_POST['region'];
		$dealer = $_POST['dealer2'];
		$branch2 = $_POST['branch2'];
		$type2 = $_POST['type2'];
		$category2 = $_POST['category2'];
		$generatedby = $_POST['generatedby'];
		$generatedbysplit = explode('^',$generatedby);
		$district = $_POST['district'];
		$productslist = $_POST['productscode'];
		$productlistsplit = explode(',',$productslist);
		$productlistsplitcount = count($productlistsplit);
		$fromdate = changedateformat($_POST['fromdate']);
		$todate = changedateformat($_POST['todate']);
		$status = $_POST['status'];
		$series = $_POST['series'];
		
		for($i = 0;$i< $productlistsplitcount; $i++)
		{
			if($i < ($productlistsplitcount-1))
				$appendor = 'or'.' ';
			else
				$appendor = '';
			$finalproductlist .= ' inv_matrixinvoicenumbers.products'.' '.'like'.' "'.'%'.$productlistsplit[$i].'%'.'" '.$appendor;
		}
		$modulepiece = ($generatedbysplit[1] == "[U]")?("user_module"):("dealer_module");
		$regionpiece = ($region == "")?(""):(" AND inv_matrixinvoicenumbers.regionid = '".$region."' ");
		
		$state_typepiece = ($state == "")?(""):(" AND inv_mas_district.statecode = '".$state."' ");
		$district_typepiece = ($district == "")?(""):(" AND inv_mas_customer.district = '".$district."' ");
		$dealer_typepiece = ($dealer == "")?(""):(" AND inv_matrixinvoicenumbers.dealerid = '".$dealer."' ");
		$branchpiece = ($branch2 == "")?(""):(" AND inv_matrixinvoicenumbers.branchid = '".$branch2."' ");
		$generatedpiece = ($generatedby == "")?(""):(" AND inv_matrixinvoicenumbers.createdbyid = '".$generatedbysplit[0]."' and inv_matrixinvoicenumbers.module = '".$modulepiece."'");
		$statuspiece = ($status == "")?(""):(" AND inv_matrixinvoicenumbers.status = '".$status."' ");
		$seriespiece = ($series == "")?(""):(" AND inv_matrixinvoicenumbers.category = '".$series."' ");
		
		switch($databasefield)
		{
			case "contactperson": 
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.contactperson LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
				break;
			case "phone":
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.phone LIKE '%".$textfield."%' OR inv_matrixinvoicenumbers.cell LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno ";
				break;
			case "place":
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.place LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
				break;
			case "emailid":
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.emailid LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
				break;
				case "cardid":
					$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_matrixinvoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid
where inv_mas_scratchcard.cardid LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
					break;
			case "scratchnumber":
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
left join inv_dealercard on inv_dealercard.invoiceid = inv_matrixinvoicenumbers.slno
LEFT JOIN inv_mas_scratchcard ON inv_mas_scratchcard.cardid = inv_dealercard.cardid where
inv_mas_scratchcard.scratchnumber LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
				break;
			
			case "invoiceno":
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.invoiceno LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno ";
				break;
			
			default:
				$query = "select inv_matrixinvoicenumbers.invoiceno,inv_matrixinvoicenumbers.slno from inv_matrixinvoicenumbers 
left join inv_mas_customer on inv_mas_customer.slno = right(inv_matrixinvoicenumbers.customerid,5)
left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district
where inv_matrixinvoicenumbers.businessname LIKE '%".$textfield."%' AND left(inv_matrixinvoicenumbers.createddate,10) between '".$fromdate."' and '".$todate."' AND  (".$finalproductlist.") ".$regionpiece.$district_typepiece.$state_typepiece.$dealer_typepiece.$branchpiece.$typepiece.$categorypiece.$generatedpiece.$statuspiece.$seriespiece."  ORDER BY invoiceno";
				break;
		}

			$result = runmysqlquery($query);
			$count = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$searchcustomerlistarray[$count] = $fetch['invoiceno'].'^'.$fetch['slno'];
				$count++;
				
			}
		echo(json_encode($searchcustomerlistarray));
		//echo($query);
	}
	break;
	case 'resendmatrixinvoice':
	{
		$invoiceno = $_POST['invoiceno'];
		$sent = resendinvoice('',$invoiceno);
		echo(json_encode($sent));
	}
	break;
		
}


function updateaddlinebreak($linecount)
{
	switch($linecount)
	{
		case '1':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td>
			<td  class="grey-td-border-grid">&nbsp;</td>
			<td  class="grey-td-border-grid">&nbsp;</td>
			<td  class="grey-td-border-grid">&nbsp;</td>
			<td  class="grey-td-border-grid">&nbsp;</td>
			<td  class="grey-td-border-grid">&nbsp;</td>
			</tr>';
		}
		break;
		case '2':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		case '3':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		case '4':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		case '5':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		case '6':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		case '7':
		{
			$linebreak = '<tr><td  class="grey-td-border-grid"><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br></td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td><td  class="grey-td-border-grid">&nbsp;</td></tr>';
		}
		break;
		
	}
	return $linebreak;
}



function calculatenormalprice($productamountsplit,$productratesplit)
{
	for($i = 0,$j=0; $i < count($productamountsplit),$j<count($productratesplit); $i++,$j++)
	{
		$productprice = $productamountsplit[$i] ;
		$productrateprice = $productratesplit[$j] ;
		if($prdcount1 > 0)
		{
			$totalproductpricearray .= '*';
			$actualproductpricearray .= '*';
		}
		
		$totalproductpricearray .= ($productprice);
		$actualproductpricearray .= ($productrateprice);
		$prdcount1++;

		$productprice = ($productprice);
		$totalproductprice += (int)$productprice ;
	}
	return $totalproductprice.'$'.$totalproductpricearray.'$'.$actualproductpricearray;
}

function getdescriptionamount($descriptionamountvalues,$descriptiontypevalues)
{
	$descriptionamountsplit = explode('~',$descriptionamountvalues);
	$descriptiontypesplit = explode('~',$descriptiontypevalues);
	$descriptioncount = count($descriptionamountsplit);
	$amount = 0;
	for($i=0;$i<$descriptioncount; $i++)
	{
		if($descriptiontypesplit[$i] == 'add')
			$amount = ($amount) + $descriptionamountsplit[$i];
		else if($descriptiontypesplit[$i] == 'less' || $descriptiontypesplit[$i] == 'percentage' || $descriptiontypesplit[$i] == 'amount')
			$amount = ($amount) - $descriptionamountsplit[$i];
		else
			$amount;
	}
	return roundnearest($amount);
}

function roundnearest($amount)
{
	$firstamount = round($amount,1);
	$amount1 = round($firstamount);
	return $amount1;
}

function selectionforproduct($productcodelist,$productslnolist)
{

	$productslnolistsplitcount = count($productslnolist);
	for($i=0; $i<$productslnolistsplitcount; $i++)
	{
		$count = 1;
		$query11 = "select * from inv_mas_matrixproduct;";
		$result11 = runmysqlquery($query11);
		$dealerselectres = '';
		$dealerselectres = '<select name="productselectvalue'.$productslnolist[$i].'" class="swiftselect" id="productselectvalue'.$productslnolist[$i].'" style="width:295px;" ><option value="">Select A Product</option>';
		while($fetch = mysqli_fetch_array($result11))
		{
				$dealerselectres .= '<option  style="width:295px;" ' . ($productcodelist[$i] == $fetch['id'] ? 'selected' : '') . ' value="'.$fetch['id'].'">'.$fetch['productname'].'</option>'; 
		}
		$dealerselectres .=  '</select>';
	}
	
	return $dealerselectres;
}

function selectdealer($dealerid)
{
	$query = "SELECT slno,businessname FROM inv_mas_dealer order by businessname;";
	$result = runmysqlquery($query);
	$selectres = '<select name="selectdealer" class="swiftselect" id="selectdealer" style="width:160px;" class="swiftselect"><option value="">Select A Dealer</option>';
	while($fetch = mysqli_fetch_array($result))
	{
		$selectres .= '<option  style="width:310px;" ' . ($dealerid == $fetch['slno'] ? 'selected' : '') . ' value="'.$fetch['slno'].'">'.wordwrap($fetch['businessname'], 25, "<br />\n").'</option>'; 
	}
	$selectres .=  '</select>';
	return $selectres;
}
?>