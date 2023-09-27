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

include('../inc/checksession.php');
include('../inc/checkpermission.php');
$lastslno = $_POST['lastslno'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
    case 'getcustomercount':
    {
        $customerarraycount = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$customerarraycount['count'] = $count;
        echo(json_encode($customerarraycount));
    }
    break;

    case 'generatecustomerlist':
    {
        $limit = $_POST['limit'];
        $startindex = $_POST['startindex'];
        $customerarray = array();
        $query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname  LIMIT ".$startindex.",".$limit.";";
        $result = runmysqlquery($query);
        $grid = '';
        $count = 0;
        while($fetch = mysqli_fetch_array($result))
        {
            $customerarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
            $count++;
        }
        //echo($grid);
        echo(json_encode($customerarray));
    }
    break;

    case 'customerdetailstoform':
    {
        $customerdetailstoformarray = array();
        $query = "select inv_mas_customer.slno as slno, inv_mas_customer.businessname as companyname,inv_mas_customer.place,
        inv_mas_customer.address,inv_mas_region.category as region,	inv_mas_branch.branchname as branch	,inv_mas_customer.gst_no as gst_no,inv_mas_customer.sez_enabled as sez_enabled,
        inv_mas_customercategory.businesstype as businesstype,inv_mas_customertype.customertype as customertype,inv_mas_dealer.businessname as dealername,
        inv_mas_customer.stdcode, inv_mas_customer.pincode,inv_mas_district.districtname, inv_mas_state.statename,inv_mas_state.state_gst_code,inv_mas_district.branchid,inv_mas_customer.customerid,inv_mas_customer.panno  
        from inv_mas_customer left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
        left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
        left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch 
        left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
        left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
        left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category 
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno =  '".$lastslno."';";
        $fetch = runmysqlqueryfetch($query);
        
        // Fetch Contact Details
        
        $querycontactdetails = "select  phone,cell, emailid,contactperson from inv_contactdetails where customerid = '".$lastslno."'";
        $resultcontactdetails = runmysqlquery($querycontactdetails);
        // contact Details
        $contactvalues = '';
        $phoneres = '';
        $cellres = '';
        $emailidres = '';

        $querygstgetdetail = "select gst_no as new_gst_no,gstin_id from customer_gstin_logs where customer_slno =".$lastslno." order by gstin_id desc limit 1";
        $ressultgstdetail = runmysqlquery($querygstgetdetail);
        $count_gst = mysqli_num_rows($ressultgstdetail);
        if($count_gst > 0)
        {
            $fetchgstgetdetail = runmysqlqueryfetch($querygstgetdetail);
            $new_gst_no = $fetchgstgetdetail['new_gst_no'];
            $gstin_id = $fetchgstgetdetail['gstin_id'];
        }
        else
        {
            $new_gst_no = $fetch_gstn['gst_no'];
        }
                
        while($fetchcontactdetails = mysqli_fetch_array($resultcontactdetails))
        {
            $contactperson = $fetchcontactdetails['contactperson'];
            $phone = $fetchcontactdetails['phone'];
            $cell = $fetchcontactdetails['cell'];
            $emailid = $fetchcontactdetails['emailid'];
            
            $contactvalues .= $contactperson;
            $contactvalues .= appendcomma($contactperson);
            $phoneres .= $phone;
            $phoneres .= appendcomma($phone);
            $cellres .= $cell;
            $cellres .= appendcomma($cell);
            $emailidres .= $emailid;
            $emailidres .= appendcomma($emailid);
        }
        $customerid = ($fetch['customerid'] == '')?'':cusidcombine($fetch['customerid']);	
        $pincode = ($fetch['pincode'] == '')?'':' Pin - '.$fetch['pincode'];
        $address = $fetch['address'].', '.$fetch['place'].', '.$fetch['districtname'].', '.$fetch['statename'].$pincode;
        $contactperson = trim($contactvalues,',');
        $phonenumber = explode(',',trim($phoneres,','));
        $phone = $phonenumber[0];
        $cellnumber = explode(',',trim($cellres,','));
        $cell = $cellnumber[0];
        $emailid = trim($emailidres,',');
        $emailidplit = explode(',', $emailid);

        $querygstgetdetail = "select gst_no as new_gst_no,gstin_id from customer_gstin_logs where customer_slno =".$lastslno." order by gstin_id desc limit 1";
        $ressultgstdetail = runmysqlquery($querygstgetdetail);
        $count_gst = mysqli_num_rows($ressultgstdetail);
        if($count_gst > 0)
        {
            $fetchgstgetdetail = runmysqlqueryfetch($querygstgetdetail);
            $new_gst_no = $fetchgstgetdetail['new_gst_no'];
            $gstin_id = $fetchgstgetdetail['gstin_id'];
        }
        else
            $new_gst_no = $fetch['gst_no'];
                
        
        $customerdetailstoformarray['errorcode'] = '1';	
        $customerdetailstoformarray['slno'] = $fetch['slno'];
        $customerdetailstoformarray['customerid'] = $customerid;
        $customerdetailstoformarray['companyname'] = $fetch['companyname'];
        $customerdetailstoformarray['contactvalues'] = trim($contactvalues,',');
        $customerdetailstoformarray['address'] = $address;
        $customerdetailstoformarray['phone'] = $phone;
        $customerdetailstoformarray['cell'] = $cell;
        $customerdetailstoformarray['emailidplit'] = $emailidplit[0];
        $customerdetailstoformarray['region'] = $fetch['region'];
        $customerdetailstoformarray['branch'] = $fetch['branch'];
        $customerdetailstoformarray['businesstype'] = $fetch['businesstype'];
        $customerdetailstoformarray['customertype'] = $fetch['customertype'];
        $customerdetailstoformarray['dealername'] = $fetch['dealername'];
        $customerdetailstoformarray['gst_no'] = $new_gst_no;
        //$customerdetailstoformarray['gst_no'] = $fetch['new_gst_no'];
        $customerdetailstoformarray['sez_enabled'] = $fetch['sez_enabled'];
        $customerdetailstoformarray['state_gst_code'] = $fetch['state_gst_code'];
        $customerdetailstoformarray['panno'] = $fetch['panno'];

        echo(json_encode($customerdetailstoformarray));		//echo('1^'.$fetch['slno'].'^'.$customerid.'^'.$fetch['companyname'].'^'.trim($contactvalues,',').'^'.$address.'^'.$phone.'^'.$cell.'^'.$emailidplit[0].'^'.$fetch['region'].'^'.$fetch['branch'].'^'.$fetch['businesstype'].'^'.$fetch['customertype'].'^'.$fetch['dealername'].'^'.$query2);
    }
    break;

    case 'getpreviewdetails' :
    {
        $customerdetailstoformarray = array();
        $invrequestno = $_POST['invrequestno'];
        $cgstrate = $_POST['cgstrate'];
        $gstrate = $_POST['sgstrate'];
        $igstrate = $_POST['igstrate'];
        $appendzero = '.00';
        $slno = 0;
        $query ="select * from inv_matrixreqpending where slno = ".$invrequestno ;
        $result = runmysqlquery($query);
        $grid = '<tr bgcolor="#cccccc" ><td width="7%"><div align="center" ><strong>Sl No</strong></div></td><td width="64%"><div align="center"><strong>Description</strong></div></td><td width="5%"><div align="center"><strong>Qty</strong></div></td><td width="12%"><div align="center"><strong>Rate</strong></div></td><td width="12%"><div align="center"><strong>Amount</strong></div></td></tr>';
        while($fetch = mysqli_fetch_array($result))
        {
            $description = explode('*',$fetch['description']);
            $productscode = explode('#',$fetch['products']);
            for($i=0;$i<count($description);$i++)
            {
                $descriptionsplit = explode('$',$description[$i]);

                $query1 = "select hsncode from inv_mas_matrixproduct where id = '".$productscode[$i]."'";
                $resultfetch = runmysqlqueryfetch($query1);
                $hsncode = $resultfetch['hsncode'];
                
                $grid .= '<tr><td width="7%" class="grey-td-border-grid" style="">'.$descriptionsplit[0].'</td>';
                $grid .= '<td width="64%" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'.$descriptionsplit[1].'</font> <br/>';
                $grid .= 'Purchase Type: <font color="#FF0000">'.$descriptionsplit[2].'</font> / ';
                $grid .= 'Serial No: <font color="#FF0000">'.$descriptionsplit[3].'</font> / ';
                $grid .= 'HSN: <font color="#FF0000">'.$hsncode.'</td>';

                $productquantity = explode(',',$fetch['productquantity']);
                $grid .= '<td width="5%" class="grey-td-border-grid" style="text-align: right;">'.$productquantity[$i].'</td>';
                $actualproductpricearray = explode('*',$fetch['actualproductpricearray']);
                $grid .= '<td width="12%" class="grey-td-border-grid"style="text-align: right;">'.formatnumber($actualproductpricearray[$i]).$appendzero.'</td>';
                $totalproductpricearray = explode('*',$fetch['totalproductpricearray']);
                $grid .= '<td width="12%" class="grey-td-border-grid"style="text-align: right;">'.formatnumber($totalproductpricearray[$i]).$appendzero.'</td></tr>';

            }
            $grid .= '<td colspan="4" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';
            if($fetch['cgst']!='0.00' && $fetch['sgst']!='0.00')
            {
                $grid .= '<tr><td colspan="3" class="grey-td-border-grid">Accounting Code For Service </td>';
                $grid .= '<td valign="top" class="grey-td-border-grid"  width="15%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" class="grey-td-border-grid" valign="top" style="text-align: right;" >'.formatnumber($fetch['amount']).$appendzero.'</td></tr>';
                $grid .= '<tr><td colspan="4"  class="grey-td-border-grid"  ><div align = "right">CGST @ '.$cgstrate.'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="cgstpreview">'.formatnumber($fetch['cgst']).'</td></tr>';
                $grid .= '<tr><td colspan="4"  class="grey-td-border-grid" ><div align = "right">SGST @ '.$sgstrate.'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="sgstpreview">'.formatnumber($fetch['sgst']).'</td></tr>';
                $grid .= '<tr><td colspan="4" class="grey-td-border-grid" ><div align = "right">Total</div></td><td nowrap="nowrap"  class="grey-td-border-grid" style="text-align: right;" id="totalamountpreview">'.formatnumber($fetch['netamount']).$appendzero.'</td></tr>';
                $grid .= '<tr><td colspan="5" class="grey-td-border-grid" style="border-right:none">Rupee In Words: '.$fetch['amountinwords'].'</td></tr>';
            }
            else{
                $grid .= '<tr><td colspan="3" class="grey-td-border-grid">Accounting Code For Service </td>';
                $grid .= '<td valign="top" class="grey-td-border-grid"  width="15%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" class="grey-td-border-grid" valign="top" style="text-align: right;" >'.formatnumber($fetch['amount']).$appendzero.'</td></tr>';
                $grid .= '<tr><td colspan="4"  class="grey-td-border-grid"  ><div align = "right">IGST @ '.$igstrate.'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="igstpreview">'.formatnumber($fetch['igst']).'</td></tr>';
                $grid .= '<tr><td colspan="4" class="grey-td-border-grid" ><div align = "right">Total</div></td><td nowrap="nowrap"  class="grey-td-border-grid" style="text-align: right;" id="totalamountpreview">'.formatnumber($fetch['netamount']).$appendzero.'</td></tr>';
                $grid .= '<tr><td colspan="5" class="grey-td-border-grid" style="border-right:none">Rupee In Words: '.$fetch['amountinwords'].'</td></tr>';

            }
            $invoiceremarks = ($fetch['invoiceremarks'] == '') ? 'None': $fetch['invoiceremarks'];
            $paymentremarks = ($fetch['remarks'] == '') ? 'None': $fetch['remarks'];
            $customer_gstno = ($fetch['gst_no'] == 0) ? 'Not Registered Under GST': $fetch['gst_no'];
            $customerid = $fetch['customerid'];
            $businessname = $fetch['businessname'];
            $contactperson = $fetch['contactperson'];
            $emailid = $fetch['emailid'];
            $phone = $fetch['phone'];
            $cell = $fetch['cell'];
            $customertype = $fetch['customertype'];
            $customercategory = $fetch['customercategory'];
            $podate = ($fetch['podate'] == '0000-00-00') ? 'Not Available' : $fetch['podate'];
            $poreference = ($fetch['poreference'] == '') ? 'Not Available' : $fetch['poreference'];
            $dealername = $fetch['dealername'];
            $seztaxtype = $fetch['seztaxtype'];

            $custquery = "select districtname,statename from inv_mas_customer  left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno=".$lastslno;
            $custfetch = runmysqlqueryfetch($custquery);
            $pincode = ($fetch['pincode'] == '')?'':' Pin - '.$fetch['pincode'];
            $address = $fetch['address'].', '.$fetch['place'].', '.$custfetch['districtname'].', '.$custfetch['statename'].$pincode;

            $query_branch_name = "select branch_gstin from inv_mas_branch where slno = ".$fetch['branchid']." ;";
            $fetch_branch_name = runmysqlqueryfetch($query_branch_name);
            $dealer_branch_name = $fetch_branch_name['branchname'];
            $branch_gstin = $fetch_branch_name['branch_gstin'];
            
        }

        $customerdetailstoformarray['errorcode'] = '1';	
        $customerdetailstoformarray['grid'] = $grid;
        $customerdetailstoformarray['customer_gstno'] = $customer_gstno;
        $customerdetailstoformarray['customerid'] = $customerid;
        $customerdetailstoformarray['businessname'] = $businessname;
        $customerdetailstoformarray['contactperson'] = $contactperson;
        $customerdetailstoformarray['address'] = $address;
        $customerdetailstoformarray['emailid'] = $emailid;
        $customerdetailstoformarray['cell'] = $cell;
        $customerdetailstoformarray['customertype'] = $customertype;
        $customerdetailstoformarray['phone'] = $phone;
        $customerdetailstoformarray['customercategory'] = $customercategory;
        $customerdetailstoformarray['podate'] = $podate;
        $customerdetailstoformarray['poreference'] = $poreference;
        $customerdetailstoformarray['dealername'] = $dealername;
        $customerdetailstoformarray['branch_gstin'] = $branch_gstin;
        $customerdetailstoformarray['invoiceremarks'] = $invoiceremarks;
        $customerdetailstoformarray['paymentremarks'] = $paymentremarks;
        $customerdetailstoformarray['seztaxtype'] = $seztaxtype;
        echo(json_encode($customerdetailstoformarray));

    }
    break;

    case 'approverejectrequest':
    {
        $customerdetailstoformarray = array();
        $lastslno = $_POST['lastslno'];
        $query1 = "select * from inv_matrixreqpending where right(customerid,5) = ".$lastslno." and reqstatus='pending' order by slno desc";
        $result1 = runmysqlquery($query1);
        $count = mysqli_num_rows( $result1);
        if($count > 0)
        {
            $invoicegrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$invoicegrid .= '<tr class="tr-grid-header" align ="left">
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Date</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Requested Id</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">customerid</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Customer Name</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Requested By</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">TDS Declaration</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Action</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Edit Invoice</td></tr>';
			$i_n1 = 0;$slno1 = 0;
			while($fetch1 = mysqli_fetch_array($result1))
			{
				$i_n1++;$slno1++;
				$color1;
				if($i_n1%2 == 0)
					$color1 = "#edf4ff";
				else
					$color1 = "#f7faff";
				$invoicegrid .= '<tr class="gridrow1" bgcolor='.$color1.'>';
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno1."</td> ";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch1['createddate'])."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['slno']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['customerid']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['businessname']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['createdby']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'><input type='checkbox' name='tdsdeclaration' id='tdsdeclaration'></td>";
				$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="previewinvoice(\''.$fetch1['slno'].'\');" class="resendtext"> Preview >></a> </td>';
				$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="editinvoice(\''.$fetch1['slno'].'\');" class="resendtext"> Edit Invoice</a> </td>';
				$invoicegrid .= "</tr>";
			}
        }
        $customerdetailstoformarray['errorcode'] = '1';
        $customerdetailstoformarray['invoicegrid'] = $invoicegrid;
        $customerdetailstoformarray['totalcount'] = $count;

        echo(json_encode($customerdetailstoformarray));

    }
    break;
    case 'getinvoicedetails':
    {
        $customerdetailstoformarray = array();
        $lastslno = $_POST['lastslno'];
        $query1 = "select * from inv_matrixinvoicenumbers where right(customerid,5) = ".$lastslno." order by slno desc";
        $result1 = runmysqlquery($query1);
        $count = mysqli_num_rows( $result1);
        if($count > 0)
        {
            $invoicegrid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$invoicegrid .= '<tr class="tr-grid-header" align ="left">
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Sl No</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Date</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Number</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Invoice Amount</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Status</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Generated By</td>
				<td nowrap = "nowrap" class="td-border-grid" align ="left">Action</td></tr>';
			$i_n1 = 0;$slno1 = 0;
			while($fetch1 = mysqli_fetch_array($result1))
			{
				$i_n1++;$slno1++;
				$color1;
				if($i_n1%2 == 0)
					$color1 = "#edf4ff";
				else
					$color1 = "#f7faff";
				$invoicegrid .= '<tr class="gridrow1" bgcolor='.$color1.'>';
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno1."</td> ";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch1['createddate'])."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['invoiceno']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['netamount']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['status']."</td>";
				$invoicegrid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['createdby']."</td>";
				$invoicegrid .= '<td nowrap="nowrap" class="td-border-grid" align="center"> <a onclick="viewmatrixinvoice(\''.$fetch1['slno'].'\');" class="resendtext"> View >></a> </td>';
				$invoicegrid .= "</tr>";
			}
        }
        $customerdetailstoformarray['errorcode'] = '1';
        $customerdetailstoformarray['invoicegrid'] = $invoicegrid;
        $customerdetailstoformarray['totalcount'] = $count;

        echo(json_encode($customerdetailstoformarray));
    }
    break;

    case 'proceedforpurchase':
    {
        $tdsdeclaration = $_POST['tdsdeclaration'];
        $state_gst_code = $_POST['state_gst_code'];
        $lastslno = $_POST['lastslno'];
        $invrequestno = $_POST['invrequestno'];
        $sezenabled = $_POST['sez_enabled'];
        $address1= $_POST['cusaddress'];
        $query ="select * from inv_matrixreqpending where slno = ".$invrequestno ;
        $fetch = runmysqlqueryfetch($query);

        $customer_gstno = $fetch['gst_no'];
        $igstamount = $fetch['igst'];
        $cgstamount = $fetch['cgst'];
        $sgstamount = $fetch['sgst'];
        $businessname = $fetch['businessname'];
        $customerid = $fetch['customerid'];
        $address = $fetch['address'];
        $place = $fetch['place'];
        //$pincode =$fetch['pincode'];
        $amount = $fetch['amount'];
        $netamount = $fetch['netamount'];
        $dealerid = $fetch['dealerid'];
        $branchid = $fetch['branchid'];
        $regionid = $fetch['regionid'];
        $branch_type = $fetch['invoice_type'];
        $branch_gst_code = $fetch['state_info'];
        $paymentamount = $fetch['paymentamount'];
        $paymentremarks = $fetch['remarks'];
        $products = $fetch['products'];
        $podate = (empty($fetch['podate'])) ? '' : changedateformat($fetch['podate']);
        $poreference = $fetch['poreference'];

        $custquery = "select pincode from inv_mas_customer where slno = '".$lastslno."'";
        $custfetch = runmysqlqueryfetch($custquery);
        $pincode = $custfetch['pincode'];

        $year = '2023';

        $branchdetails = "select * from inv_mas_branch where branch_gst_code = '".$branch_gst_code."'";
        $fetchdetails = runmysqlqueryfetch($branchdetails);
        $branch_gstin = $fetchdetails['branch_gstin'];
        $branch_add = $fetchdetails['branch_address'];
        $branch_place = $fetchdetails['branch_place'];
        $branch_pincode = $fetchdetails['branch_pincode'];
        $branch_gst_code = $fetchdetails['branch_gst_code'];
        
        if(!empty($customer_gstno))
        {
            $totalamt = $taxableamt = 0;
            $productitemgrid = array();
            $itemcount = 1;
            $totalitemvalue = 0;
            $discount =  0;

            if($igstamount!= 0 || $igstamount!= '0.00')
            {
                $igstamt = $igstamount;
                $cgstamt = 0;
                $sgstamt = 0;
            }
            else{
                $cgstamt = $cgstamount;
                $sgstamt = $sgstamount;
                $igstamt = 0;
            }
            
            $description = explode('*',$fetch['description']);
            
            for($i=0;$i<count($description);$i++)
            {
                $descriptionsplit = explode('$',$description[$i]);
                $actualproductpricearray = explode('*',$fetch['actualproductpricearray']);
                $unitprice = $actualproductpricearray[$i];
                $totalproductpricearray = explode('*',$fetch['totalproductpricearray']);
                $totalamt = $totalproductpricearray[$i];
                $taxableamt = $totalamt;
                $discount = 0;
                
                $addition_amount = $fetch['amount']+$fetch['igst']+$fetch['cgst']+$fetch['sgst'];
                //echo $addition_amount;
                $roundoff_value = $fetch['netamount']- round($addition_amount,2);
                //echo $fetch['netamount'] . "amount ". $addition_amount;
                if($roundoff_value != 0 || $roundoff_value != 0.00)
                {
                    $roundoff = 'true';
                }
                if($roundoff == 'true')
                    $roundoff_value = $roundoff_value;
                else 
                    $roundoff_value = 0;
                
                if($roundoff_value == 0)
                    $rndoffamt = (int)$roundoff_value;
                else
                    $rndoffamt = (float)round($roundoff_value,2);

                if($igstamount!= '0')
                {
                    $numberigst = ($taxableamt*18)/100;
                    $unitigstamt = round($numberigst,2);
                    $unitcgstamt = $unitsgstamt = 0;
                }
                else
                {
                    $numbercgstamt = ($taxableamt * 9)/100;
                    $unitcgstamt = round($numbercgstamt,2);

                    $numbersgstamt = ($totalamt * 9)/100;
                    $unitsgstamt = round($numbersgstamt,2);
                    $unitigstamt =  0;
                }
                //final value per product
                $totalitemvalue = $taxableamt + $unitigstamt + $unitcgstamt + $unitsgstamt;

                $productsplit = explode('#',$products);
                $servicequery = "select hsncode,stocktype from inv_mas_matrixproduct where id = '".$productsplit[$i]."'";
                $servicefetch = runmysqlqueryfetch($servicequery);
                $servicecode = $servicefetch['hsncode'];
                $stocktype = $servicefetch['stocktype'];
                $IsServc = ($stocktype == 'Service') ?  'Y' : 'N';

                if($unitcgstamt == 0 && $unitsgstamt == 0)
                {
                    $num1 = (int)$unitcgstamt;
                    $num2 = (int)$unitsgstamt;
                    if(is_float($unitigstamt))
                    {
                        $num3 = (float)$unitigstamt;
                    }
                    else
                        $num3 = (int)$unitigstamt;
                }
                else
                {
                    $num3 = (int)$unitigstamt;
                    if(is_float($unitcgstamt) && is_float($unitsgstamt))
                    {
                        $num1 = (float)$unitcgstamt;
                        $num2 = (float)$unitsgstamt;
                    }
                    else
                    {
                        $num1 = (int)$unitcgstamt;
                        $num2 = (int)$unitsgstamt;
                    }
                }
                if(is_float($discount))
                    $prodiscount = (float)$discount;
                else
                    $prodiscount = (int)$discount;

                $productitemgrid[] = array( "SlNo"=> (string)$itemcount++,
                "IsServc"=> $IsServc,
                "HsnCd"=> $servicecode,
                "Unit"=> "NOS",
                "UnitPrice"=> (int)$totalamt , //price per product
                "TotAmt"=> (int)$totalamt,		//unit price * quantity = toatlamt
                "AssAmt"=> (int)$taxableamt, //gross amt(toatlamt) - discount
                "Discount"=> $prodiscount,					//consider if any
                "GstRt"=> 18,
                "IgstAmt"=> $num3,		//calculate GST based on per product
                "CgstAmt"=> $num1,		//calculate GST based on per product
                "SgstAmt"=> $num2,		//calculate GST based on per product
                "TotItemVal"=> round($totalitemvalue,2) 	//taxableamt + gst
                );
            }
            if($sezenabled == 'yes')
            {
                $SupTyp = "SEZWP";
                $IgstOnIntra = "Y";
            }
            else
            {
                $SupTyp = "B2B";
                $IgstOnIntra = "N";
            }

            $cgstval = $sgstval = $igstval = $rndoffamt = 0;
            if($igstamt == '0' || $igstamt == '0.00')
            {
                $igstval = (int)$igstamt;
                if(is_float($cgstamt) && is_float($sgstamt))
                {
                    $cgstval = (float)$cgstamt;
                    $sgstval = (float)$sgstamt;
                }
                else{
                    $cgstval = (int)$cgstamt;
                    $sgstval = (int)$sgstamt;
                }
            }
            else{
                $cgstval = (int)$cgstamt;
                $sgstval = (int)$sgstamt;
                if(is_float($igstamt))
                    $igstval = (float)$igstamt;
                else
                    $igstval = (int)$igstamt;
            }
            
            include('../../getinvoiceirn/getmatrixinvoiceirn.php');


            if($status === "ACT")
            {
                $ackNo = $irndata['ackNo'];
                $ackDt = $irndata['ackDt'];
                $irn = $irndata['irn']; 
                $signedInvoice = $irndata['signedInvoice'];
                $signedQRCode = $irndata['signedQRCode'];
                $authgstin = $customer_gstno;
                
                //exit;
                //$addstring ="/imax/user";
                $date = datetimelocal('YmdHis-');
                $filename = $date.$invoicenoformat;
                $filebasename = $filename.".png";
                $imagepath = "../qrimages/".$filebasename; 
                QRcode::png($signedQRCode,$imagepath,QR_ECLEVEL_L, 2);
                
            }
            else
            {
                for($c=0;$c<count((array)$irndata['errorDetails']);$c++)
                {
                    $errorcode = $irndata['errorDetails'][$c]['errorCode'];
                    $errormsg=$irndata['errorDetails'][$c]['errorMessage'];
                }
            }
        }
        else
        {
            $authgstin = "";
        }
        
        $verifygstin = array('3028','3029','3074','3075','3076','3077','3078','3079');
        if($status === "ACT" || empty($customer_gstno) || $gstcheck == 'gstinconfirm')
        {
            //update currentdealer,region and branch of customer as per dealer
            $query11 = "update inv_mas_customer set currentdealer = '".$dealerid."',branch = '".$branchid."', region = '".$regionid."'  where slno = '".$lastslno."';";
            $result11 = runmysqlquery($query11);

            //Get the next record serial number for insertion in invoicenumbers table
            $query8 = "select ifnull(max(slno),0) + 1 as billref from inv_matrixinvoicenumbers";
            $resultfetch8 = runmysqlqueryfetch($query8);
            $onlineinvoiceslno = $resultfetch8['billref'];

            if(empty($customer_gstno) || $gstcheck == 'gstinconfirm')
            {
                $getinvoiceno = getinvoiceno($fetch['invoice_type'],$fetch['state_info'],$year);
                $invoicenoformat = $getinvoiceno[0];
                $onlineinvoiceno = $getinvoiceno[1];
            }
            
            $invoicedate = date('Y-m-d').' '.date('H:i:s');
            $servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
            $invoiceheading = 'Tax Invoice';

            $query10 = "select * from inv_mas_users where slno = '".$userid."';";
            $resultfetch = runmysqlqueryfetch($query10);
            $username = $resultfetch['fullname'];

            //Insert complete invoice details to invoicenumbers table 
           $query11 = "Insert into inv_matrixinvoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,emailid,description,invoiceno,dealername,createddate,createdby,amount,
            igst,cgst,sgst,netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,
            totalproductpricearray,actualproductpricearray,module,paymentmode,stdcode,branch,amountinwords,paymentamount,remarks,invoiceremarks,servicetaxdesc,
            invoiceheading,branchid,regionid,podate,poreference,seztaxtype,year,invoice_type,state_info,gst_no,irn,signedqrcode,qrimagepath,ackno,ackdate,tdsdeclaration,requestedid) values('".$onlineinvoiceslno."','".$customerid."','".$businessname."','".$fetch['contactperson']."',
            '".addslashes($address1)."','".$place."','".$pincode."','".$fetch['emailid']."','".$fetch['description']."','".$invoicenoformat."',
            '".$fetch['dealername']."','".$invoicedate."','".$username."','".$fetch['amount']."','".$igstamount."','".$cgstamount."','".$sgstamount."','".$netamount."',
            '".$fetch['phone']."','".$fetch['cell']."','".$fetch['customertype']."','".$fetch['customercategory']."','".$fetch['region']."','Product','".$fetch['category']."','".$onlineinvoiceno."','".$dealerid."','".$products."','".$fetch['productquantity']."',
            'default','".$userid."','".$fetch['totalproductpricearray']."','".$fetch['actualproductpricearray']."','user_module','','".$fetch['stdcode']."','".$fetch['branch']."',
            '".$fetch['amountinwords']."','".$paymentamount."','".$paymentremarks."','".$fetch['invoiceremarks']."','".$servicetaxdesc."','".$invoiceheading."','".$branchid."',
            '".$regionid."','".$podate."','".$poreference."','no','".$year."','".$branch_type."','".$branch_gst_code ."','".$authgstin."','".$irn."','".$signedQRCode."','".$filebasename."','".$ackNo."','".$ackDt."','".$tdsdeclaration."','".$invrequestno."');";
            $result11 = runmysqlquery($query11);

            $query12 = "update inv_matrixreqpending set approvedby = '".$userid."',approvedip = '".$_SERVER['REMOTE_ADDR']."',approvedmodule = 'user_module',reqstatus= 'Approved',approveddate='".date('Y-m-d').' '.date('H:i:s')."' where slno = ".$invrequestno;
            $result12 = runmysqlquery($query12);
            
            if($paymentamount!= 0)
            {
                //Insert Receipt Details
                $query55 = "INSERT INTO inv_mas_receipt(matrixinvoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) 
                values('".$onlineinvoiceslno."','".$netamount."','".$paymentamount."','onlinetransfer','".$paymentremarks."','','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$lastslno."','".date('Y-m-d')."','".date('H:i:s')."','user_module','no');";
                $result55 = runmysqlquery($query55);
            }
            
            $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','272','".date('Y-m-d').' '.date('H:i:s')."','".$onlineinvoiceslno."')";
            $eventresult = runmysqlquery($eventquery);
            
            // $responsearray['responsecode'] = 1;
            // $responsearray['billslno'] = $dealerpurchasebillno;
            // $responsearray['slno'] = $dealerid1;
            // $responsearray['invoicecount'] = $invoicecount;

            //Online bill Generation in PDF.
            $pdftype = 'send';
            $invoicedetails = vieworgeneratematrixpdfinvoice($onlineinvoiceslno,$pdftype);
            $invoicedetailssplit = explode('^',$invoicedetails);
            $filebasename = $invoicedetailssplit[0];
            sendmatrixpurchasesummaryemail($onlineinvoiceslno,$filebasename);
            echo(json_encode('1^Invoice Generated^'.$lastslno));
        }
        else if(in_array($errorcode, $verifygstin))
		{
			echo(json_encode('3^'.$errormsg.'^'.$errorcode));
		}
        else
        {
            echo(json_encode('2^'.$errormsg.'^'.$errorcode));
        }        
    }
    break;

    case 'proceedforuserpurchase':
    {
        $lastslno = $_POST['lastslno'];
        $gstcheck = $_POST['gstcheck'];
		$dealerid = $_POST['dealerid'];
		$dealername = $_POST['dealername'];
		$paymentamount = $_POST['paymentamount'];
		$paymentremarks = $_POST['paymentremarks'];
        $invoiceremarks = $_POST['invoiceremarks'];
        $tdsdeclaration = $_POST['tdsdeclaration'];

        $purchasevalues = $_POST['purchasevalues'];
		$purchasevaluessplit = explode('#',$purchasevalues);

		$producthiddenvalues = $_POST['producthiddenvalues'];
		$productvaluesplit = explode('*',$producthiddenvalues);

        $quantityvalues = $_POST['quantityvalues'];
		$quantityvaluessplit = explode(',',$quantityvalues);

        $unitamtvalues = $_POST['unitamtvalues'];
		$unitamtvaluessplit = explode('*',$unitamtvalues);

        $invoiceamountvalues = $_POST['invoiceamountvalues'];
		$invoiceamountvaluessplit = explode('*',$invoiceamountvalues);

        $fromsrlnovalues = $_POST['fromsrlnovalues'];
		$fromsrlnovaluessplit = explode('~',$fromsrlnovalues);
		//print_r($fromsrlnovaluessplit); exit;

        $fromsrlno = explode(",",$_POST['fromsrlno']);

        // $tosrlnovalues = $_POST['tosrlnovalues'];
		// $tosrlnovaluessplit = explode('~',$tosrlnovalues);
        $businessname = $_POST['cusname'];
        
        $state_gst_code = $_POST['state_gst_code'];
        $customer_gstno = ($_POST['customer_gstno'] == 'Not Registered Under GST' || $gstcheck == 'gstinconfirm') ? "0": $_POST['customer_gstno'];
        $contactperson = $_POST['cuscontactperson'];
        $address1 = $_POST['cusaddress'];
        $emailid = $_POST['cusemail'];
        $phone = $_POST['cusphone'];
        $cell = $_POST['cuscell'];
        $type = $_POST['custype'];
        $category = $_POST['cuscategory'];
        $podate = (empty($_POST['podate'])) ? '' : changedateformat($_POST['podate']);
        $poreference = $_POST['poreference'];

        $igstamount = $_POST['igstamount'];
        $cgstamount = $_POST['cgstamount'];
        $sgstamount = $_POST['sgstamount'];

        $fromsrlno = explode(",",$_POST['fromsrlno']);
        $editinvreqno = $_POST['editinvreqno'];
        $seztaxtype = $_POST['seztaxtype'];
		$seztaxfilepath = $_POST['seztaxfilepath'];
        $panno = $_POST['panno'];

        if($seztaxtype == 'yes')
		{
			$seztaxtype1 = $seztaxtype;
			$seztaxfilepath1 = $seztaxfilepath;
			$seztaxdate1 = date('Y-m-d').' '.date('H:i:s');
			$seztaxattachedby1 = $userid;
		}
		else
		{
			$seztaxtype1 = $seztaxtype;
			$seztaxfilepath1 = '';
			$seztaxdate1 = '';
			$seztaxattachedby1 = '';
		}

		//Get the customer details
		$query1 = "select * from inv_mas_customer left join inv_mas_district on inv_mas_district.districtcode =inv_mas_customer.district left join inv_mas_state on inv_mas_state.statecode =inv_mas_district.statecode left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region left join inv_mas_branch on  inv_mas_branch.slno = inv_mas_customer.branch left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  where inv_mas_customer.slno = '".$lastslno."';";
		$fetch = runmysqlqueryfetch($query1);

        $address = ($address == "") ? $fetch['address'] : $address;
		
		$finalemailids = $cusemail;
		$resultantemailid = remove_duplicates($finalemailids);
		//Fetched customer contact details
		$generatedcustomerid = $fetch['customerid'];
		$place = $fetch['place'];
		$district = $fetch['districtcode'];
		$state = $fetch['statename'];
		$pincode = $fetch['pincode'];
		$branchname = $fetch['branchname'];
		$custcontactperson = trim($cuscontactperson,',');
		$stdcode = $fetch['stdcode'];
		$stdcode = ($stdcode == '')?'':$stdcode.' - ';
		$customerid17digit = $fetch['customerid'];

		//If it is a new customer, generate new customer id and update it in Customer Master
		if($customerid17digit  == '')
		{
			if($selectedcookievalue <> '')
				$firstproduct = $selectedcookievaluesplit[0];
			else
				$firstproduct = '000';
			//Get new customer id
			$query = "select statecode from inv_mas_district where districtcode  = '".$district."';";
			$fetch = runmysqlqueryfetch($query);
			$statecode = $fetch['statecode'];
			$newcustomerid = $statecode.$district.$dealerid.$firstproduct.$lastslno;
			$password = generatepwd();
			//update customer master with customer product
			$query = "update inv_mas_customer set firstdealer = '".$dealerid."' , firstproduct = '".$firstproduct."', 
			initialpassword = '".$password."', loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),
			customerid = '".$newcustomerid."' where slno = '".$lastslno."';";
			$result = runmysqlquery($query);
			$generatedcustomerid = $newcustomerid;
			sendwelcomeemail($lastslno, $userid);
	
		}

        $slno = 0;
        $amount = 0;
        $description = "";
        $purchasetype = "";
        $array = $fromsrlno;
        $productsplit = []; 
        $serialfilter = [];
        $cardid = "";
        for($i=0;$i<count($productvaluesplit);$i++)
        {
			$slno++;
            $productsplit = explode('#',$productvaluesplit[$i]);
            $product[] = $productsplit[1];
            if($purchasevaluessplit[$i] == 'new')
			{
				$purchasetype = 'New';
			}
			elseif($purchasevaluessplit[$i] == 'updation')
			{
				$purchasetype = 'Updation';
			}

			$serialno = array_slice($array,0,$quantityvaluessplit[$i]);
            $serialfilter = array_filter($serialno);
            //print_r($serialno);
            if($serialfilter)
			    $cardid = implode("/",$serialfilter);
			

			$splicearray = array_splice($array,0,$quantityvaluessplit[$i]);

            if($i > 0)
                $description .= '*';
            
            $description .= $slno.'$'.$productsplit[0].'$'.$purchasetype.'$'.$cardid.'$'.$invoiceamountvaluessplit[$i];

            $amount += $invoiceamountvaluessplit[$i];
        }
    //     print_r($description);
    //    exit;
        $products = implode('#',$product);
		//echo $description; exit;
        
        $netamount = $amount +  $cgstamount + $sgstamount + $igstamount;
        $netamount = round($netamount);

        $amountinwords = convert_number($netamount);
        $servicetaxdesc = 'Service Tax Category: Information Technology Software (zzze), Support(zzzq), Training (zzc), Manpower(k), Salary Processing (22g), SMS Service (b)';
        $invoiceheading = 'Tax Invoice';
        $invoicedate = date('Y-m-d').' '.date('H:i:s');

        //Get the logged in  dealer details
        $query0 = "select billingname,inv_mas_region.category as region,inv_mas_dealer.emailid as dealeremailid,inv_mas_dealer.region as regionid,inv_mas_dealer.branch  as branchid,inv_mas_dealer.district as dealerdistrict  from inv_mas_dealer left join inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region  where inv_mas_dealer.slno = '".$dealerid."';";
        $fetch0 = runmysqlqueryfetch($query0);
        $dealername = $fetch0['billingname'];
        $dealerregion = $fetch0['region'];
        $dealeremailid = $fetch0['dealeremailid'];
        $regionid = $fetch0['regionid'];
        $branchid = $fetch0['branchid'];
        $dealerdistrict = $fetch0['dealerdistrict'];

        //Added for Branch Name correction in invoices	
        $query_branch_name = "select * from inv_mas_branch where slno = $branchid and slno NOT in(1,4);";
        $fetch_branch_name = runmysqlqueryfetch($query_branch_name);
        $dealer_branch_name = $fetch_branch_name['branchname'];
        $branch_type = $fetch_branch_name['branch_type'];
        $branch_gst_code = $fetch_branch_name['branch_gst_code'];
        $branch_gstin = $fetch_branch_name['branch_gstin'];
        $branch_add = $fetch_branch_name['branch_address'];
        $branch_place = $fetch_branch_name['branch_place'];
        $branch_pincode = $fetch_branch_name['branch_pincode'];

        //update region and branch of customer as per dealer
        $panno = ($_POST['panno'] == '') ? "": " , panno = '".$_POST['panno']."'";
		$query11 = "update inv_mas_customer set currentdealer = '".$dealerid."',branch = '".$branchid."', region = '".$regionid."'".$panno." where slno = '".$lastslno."';";
		$result11 = runmysqlquery($query11);

        $year = '2023';
        
        if(!empty($customer_gstno))
        {
            if($gstcheck!= 'gstconfirm')
			{
                $totalamt = $taxableamt = 0;
                $productitemgrid = array();
                $itemcount = 1;
                $totalitemvalue = 0;
                $discount =  0;

                if($igstamount!= 0 || $igstamount!= '0.00')
                {
                    $igstamt = $igstamount;
                    $cgstamt = 0;
                    $sgstamt = 0;
                }
                else{
                    $cgstamt = $cgstamount;
                    $sgstamt = $sgstamount;
                    $igstamt = 0;
                }
                            
                for($i=0;$i<count($productvaluesplit);$i++)
                {
                    $totalamt = $invoiceamountvaluessplit[$i];
                    $taxableamt = $totalamt;
                    $discount = 0;

                    $roundoff_value = $netamount- round($netamount,2);
                    //echo $fetch['netamount'] . "amount ". $addition_amount;
                    if($roundoff_value != 0 || $roundoff_value != 0.00)
                    {
                        $roundoff = 'true';
                    }
                    if($roundoff == 'true')
                        $roundoff_value = $roundoff_value;
                    else 
                        $roundoff_value = 0;
                    
                    if($roundoff_value == 0)
                        $rndoffamt = (int)$roundoff_value;
                    else
                        $rndoffamt = (float)round($roundoff_value,2);

                    if($seztaxtype == 'yes')
                    {
                        $unitcgstamt = $unitsgstamt = $unitigstamt = 0;
                    }
                    else
                    {
                        if($igstamount!= '0')
                        {
                            $numberigst = ($taxableamt*18)/100;
                            $unitigstamt = round($numberigst,2);
                            $unitcgstamt = $unitsgstamt = 0;
                        }
                        else
                        {
                            $numbercgstamt = ($taxableamt * 9)/100;
                            $unitcgstamt = round($numbercgstamt,2);

                            $numbersgstamt = ($totalamt * 9)/100;
                            $unitsgstamt = round($numbersgstamt,2);
                            $unitigstamt =  0;
                        }
                    }
                    //final value per product
                    $totalitemvalue = $taxableamt + $unitigstamt + $unitcgstamt + $unitsgstamt;

                    $productsplit = explode('#',$productvaluesplit[$i]);
                    $servicequery = "select hsncode,stocktype from inv_mas_matrixproduct where id = '".$productsplit[1]."'";
                    $servicefetch = runmysqlqueryfetch($servicequery);
                    $servicecode = $servicefetch['hsncode'];
                    $stocktype = $servicefetch['stocktype'];
                    $IsServc = ($stocktype == 'Service') ?  'Y' : 'N';

                    if($unitcgstamt == 0 && $unitsgstamt == 0)
                    {
                        $num1 = (int)$unitcgstamt;
                        $num2 = (int)$unitsgstamt;
                        if(is_float($unitigstamt))
                        {
                            $num3 = (float)$unitigstamt;
                        }
                        else
                            $num3 = (int)$unitigstamt;
                    }
                    else
                    {
                        $num3 = (int)$unitigstamt;
                        if(is_float($unitcgstamt) && is_float($unitsgstamt))
                        {
                            $num1 = (float)$unitcgstamt;
                            $num2 = (float)$unitsgstamt;
                        }
                        else
                        {
                            $num1 = (int)$unitcgstamt;
                            $num2 = (int)$unitsgstamt;
                        }
                    }
                    if(is_float($discount))
                        $prodiscount = (float)$discount;
                    else
                        $prodiscount = (int)$discount;

                    $productitemgrid[] = array( "SlNo"=> (string)$itemcount++,
                    "IsServc"=> $IsServc,
                    "HsnCd"=> $servicecode,
                    "Unit"=> "NOS",
                    "UnitPrice"=> (int)$totalamt , //price per product
                    "TotAmt"=> (int)$totalamt,		//unit price * quantity = toatlamt
                    "AssAmt"=> (int)$taxableamt, //gross amt(toatlamt) - discount
                    "Discount"=> $prodiscount,					//consider if any
                    "GstRt"=> 18,
                    "IgstAmt"=> $num3,		//calculate GST based on per product
                    "CgstAmt"=> $num1,		//calculate GST based on per product
                    "SgstAmt"=> $num2,		//calculate GST based on per product
                    "TotItemVal"=> round($totalitemvalue,2) 	//taxableamt + gst
                    );
                }
                $sezenabled = $fetch['sez_enabled'];
                if($sezenabled == 'yes')
                {
                    $SupTyp = "SEZWP";
                    $IgstOnIntra = "Y";
                }
                else if($seztaxtype == 'yes')
				{
					$SupTyp = "SEZWOP";
					$IgstOnIntra = "Y";
				}
                else
                {
                    $SupTyp = "B2B";
                    $IgstOnIntra = "N";
                }

                $cgstval = $sgstval = $igstval = $rndoffamt = 0;
                if($igstamt == '0' || $igstamt == '0.00')
                {
                    $igstval = (int)$igstamt;
                    if(is_float($cgstamt) && is_float($sgstamt))
                    {
                        $cgstval = (float)$cgstamt;
                        $sgstval = (float)$sgstamt;
                    }
                    else{
                        $cgstval = (int)$cgstamt;
                        $sgstval = (int)$sgstamt;
                    }
                }
                else{
                    $cgstval = (int)$cgstamt;
                    $sgstval = (int)$sgstamt;
                    if(is_float($igstamt))
                        $igstval = (float)$igstamt;
                    else
                        $igstval = (int)$igstamt;
                }
                
                include('../../getinvoiceirn/getmatrixinvoiceirn.php');


                if($status === "ACT")
                {
                    $ackNo = $irndata['ackNo'];
                    $ackDt = $irndata['ackDt'];
                    $irn = $irndata['irn']; 
                    $signedInvoice = $irndata['signedInvoice'];
                    $signedQRCode = $irndata['signedQRCode'];
                    $authgstin = $customer_gstno;
                    
                    //exit;
                    //$addstring ="/imax/user";
                    $date = datetimelocal('YmdHis-');
                    $filename = $date.$invoicenoformat;
                    $filebasename = $filename.".png";
                    $imagepath = "../qrimages/".$filebasename; 
                    QRcode::png($signedQRCode,$imagepath,QR_ECLEVEL_L, 2);
                    
                }
                else
                {
                    for($c=0;$c<count((array)$irndata['errorDetails']);$c++)
                    {
                        $errorcode = $irndata['errorDetails'][$c]['errorCode'];
                        $errormsg=$irndata['errorDetails'][$c]['errorMessage'];
                    }
                        
                }
            }
        }
        else
        {
            $authgstin = "";
        }
        
        $verifygstin = array('3028','3029','3074','3075','3076','3077','3078','3079');
        if($status === "ACT" || (empty($customer_gstno) && $seztaxtype!='yes') || $gstcheck == 'gstinconfirm')
        {
            //Get the next record serial number for insertion in invoicenumbers table
            $query8 = "select ifnull(max(slno),0) + 1 as billref from inv_matrixinvoicenumbers";
            $resultfetch8 = runmysqlqueryfetch($query8);
            $onlineinvoiceslno = $resultfetch8['billref'];

            if(empty($customer_gstno) || $gstcheck == 'gstinconfirm')
            {
                $getinvoiceno = getinvoiceno($branch_type,$branch_gst_code,$year);
                $invoicenoformat = $getinvoiceno[0];
                $onlineinvoiceno = $getinvoiceno[1];
            }
            
            $query10 = "select * from inv_mas_users where slno = '".$userid."';";
            $resultfetch = runmysqlqueryfetch($query10);
            $username = $resultfetch['fullname'];

            //Insert complete invoice details to invoicenumbers table 
            $query11 = "Insert into inv_matrixinvoicenumbers(slno,customerid,businessname,contactperson,address,place,pincode,emailid,description,invoiceno,dealername,createddate,createdby,amount,
            igst,cgst,sgst,netamount,phone,cell,customertype,customercategory,region,purchasetype,category,onlineinvoiceno,dealerid,products,productquantity,pricingtype,createdbyid,
            totalproductpricearray,actualproductpricearray,module,paymentmode,stdcode,branch,amountinwords,paymentamount,remarks,invoiceremarks,servicetaxdesc,
            invoiceheading,branchid,regionid,podate,poreference,seztaxtype,seztaxfilepath,seztaxdate,seztaxattachedby,year,invoice_type,state_info,gst_no,irn,signedqrcode,qrimagepath,ackno,ackdate,tdsdeclaration,requestedid) values('".$onlineinvoiceslno."','".cusidcombine($generatedcustomerid)."','".$businessname."','".$contactperson."',
            '".addslashes($address1)."','".$place."','".$pincode."','".$emailid."','".$description."','".$invoicenoformat."',
            '".$dealername."','".$invoicedate."','".$username."','".$amount."','".$igstamount."','".$cgstamount."','".$sgstamount."','".$netamount."',
            '".$phone."','".$cell."','".$type."','".$category."','".$dealerregion."','Product','".$dealerregion."','".$onlineinvoiceno."','".$dealerid."','".$products."','".$quantityvalues."',
            'default','".$userid."','".$invoiceamountvalues."','".$unitamtvalues."','user_module','','".$stdcode."','".$dealer_branch_name."',
            '".$amountinwords."','".$paymentamount."','".addslashes($paymentremarks)."','".addslashes($invoiceremarks)."','".$servicetaxdesc."','".$invoiceheading."','".$branchid."',
            '".$regionid."','".$podate."','".$poreference."','".$seztaxtype1."','".$seztaxfilepath1."','".$seztaxdate1."','".$seztaxattachedby1."','".$year."','".$branch_type."','".$branch_gst_code ."','".$authgstin."','".$irn."','".$signedQRCode."','".$filebasename."','".$ackNo."','".$ackDt."','".$tdsdeclaration."','".$editinvreqno."');";
            $result11 = runmysqlquery($query11);

            if($editinvreqno!="")
            {
                $query12 = "update inv_matrixreqpending set approvedby = '".$userid."',approvedip = '".$_SERVER['REMOTE_ADDR']."',approvedmodule = 'user_module',reqstatus= 'Approved',approveddate='".date('Y-m-d').' '.date('H:i:s')."' where slno = ".$editinvreqno;
                $result12 = runmysqlquery($query12);
            }

            //Get the next record serial number for insertion in receipts table
            // $query45 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
            // $resultfetch45 = runmysqlqueryfetch($query45);
            // $receiptslno = $resultfetch45['receiptslno'];
            if($paymentamount!= 0)
            {
                //Insert Receipt Details
                $query55 = "INSERT INTO inv_mas_receipt(matrixinvoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) 
                values('".$onlineinvoiceslno."','".$netamount."','".$paymentamount."','onlinetransfer','".$paymentremarks."','','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$lastslno."','".date('Y-m-d')."','".date('H:i:s')."','user_module','no');";
                $result55 = runmysqlquery($query55);
            }
            
            $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','270','".date('Y-m-d').' '.date('H:i:s')."','".$onlineinvoiceslno."')";
            $eventresult = runmysqlquery($eventquery);
            
                //Online bill Generation in PDF.
            $pdftype = 'send';
            $invoicedetails = vieworgeneratematrixpdfinvoice($onlineinvoiceslno,$pdftype);
            $invoicedetailssplit = explode('^',$invoicedetails);
            $filebasename = $invoicedetailssplit[0];
            sendmatrixpurchasesummaryemail($onlineinvoiceslno,$filebasename);
            echo(json_encode('1^Invoice Generated^'.$lastslno));
        }
        else if(in_array($errorcode, $verifygstin))
		{
			echo(json_encode('3^'.$errormsg.'^'.$errorcode));
		}
        else
        {
            if(empty($customer_gstno) && $seztaxtype =='yes')
			{
				$errormsg = 'Invoice cannot be generated for non GSTIN SEZ customer';
				echo(json_encode('2^'.$errormsg.'^'.$errorcode));
			}
			else
                echo(json_encode('2^'.$errormsg.'^'.$errorcode));
        }
        
    }
    break;

    case 'rejectpurchase':
    {
        $invrequestno = $_POST['invrequestno'];
        $query = "update inv_matrixreqpending set rejectedby = '".$userid."',rejectedip = '".$_SERVER['REMOTE_ADDR']."',rejecteddate='".date('Y-m-d').' '.date('H:i:s')."',rejectedmodule = 'user_module',reqstatus= 'Rejected' where slno = ".$invrequestno;
        $result = runmysqlquery($query);

        $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','271','".date('Y-m-d').' '.date('H:i:s')."','".$invrequestno."')";
        $eventresult = runmysqlquery($eventquery);
        sendinvoicerejectemail($invrequestno);
        echo(json_encode('1^Request Rejected!'));
    }
    break;

    case 'getdealerdetails':
    {
        $responsearray = array();
        $dealerid = $_POST['dealerid'];
        $query = "select branch from inv_mas_dealer where inv_mas_dealer.slno = '".$dealerid."'";
        $resultfetch = runmysqlqueryfetch($query);
        $branchid = $resultfetch['branch'];

        $query_branch_name = "select branch_gstin,branch_gst_code from inv_mas_branch where slno = $branchid ;";
        $fetch_branch_name = runmysqlqueryfetch($query_branch_name);
        $branch_gstin = $fetch_branch_name['branch_gstin'];
        $branch_gst_code = $fetch_branch_name['branch_gst_code'];

        $responsearray['errorcode'] = 1;
        $responsearray['branch_gst_code'] = $branch_gst_code;
        $responsearray['branch_gstin'] = $branch_gstin;
        echo json_encode($responsearray);
    }
    break;

    case 'gethsncode':
    {
        $responsearray = array();
        $productid = $_POST['productid'];
        $query = "select hsncode from inv_mas_matrixproduct where id = '".$productid."'";
        $resultfetch = runmysqlqueryfetch($query);
        $hsncode = $resultfetch['hsncode'];

        echo json_encode('1^'.$hsncode);
    }
    break;

    case 'editinvoice';
    {
        $responsearray = array();
        $requestid = $_POST['requestid'];
        $query = "select * from inv_matrixreqpending where slno =".$requestid;
        $fetch = runmysqlqueryfetch($query);
        $customerid = $fetch['customerid'];
        $description = $fetch['description'];
        $totalamount = $fetch['totalproductpricearray'];
        $actualamount = $fetch['actualproductpricearray'];
        $productquantity = $fetch['productquantity'];
        $products = $fetch['products'];
        $productsplit = explode('#',$products);
        for($i=0;$i<count($productsplit);$i++)
        {
            $query1 = "select hsncode from inv_mas_matrixproduct where id = '".$productsplit[$i]."'";
            $resultfetch = runmysqlqueryfetch($query1);
            $hsnarray[] = $resultfetch['hsncode'];
        }
        $hsncode = implode('#',$hsnarray);
        $podate = (empty($fetch['podate'])) ? '' : changedateformat($fetch['podate']);

        $query1 = "select inv_mas_district.districtname, inv_mas_state.statename
        from inv_mas_customer 
        left join inv_mas_district on inv_mas_district.districtcode = inv_mas_customer.district 
        left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode where inv_mas_customer.slno =  '".substr($customerid,-5)."';";
        $fetch1 = runmysqlqueryfetch($query1);

        $responsearray['errorcode'] = 1;
        $responsearray['description'] = $description;
        $responsearray['totalamount'] = $totalamount;
        $responsearray['actualamount'] = $actualamount;
        $responsearray['productquantity'] = $productquantity;
        $responsearray['products'] = $products;

        $responsearray['customerid'] = $fetch['customerid'];
        $responsearray['businessname'] = $fetch['businessname'];
        $responsearray['contactperson'] = $fetch['contactperson'];
        $responsearray['address1'] = $fetch['address'];
        $responsearray['address'] = $fetch['address'].', '.$fetch['place'].', '.$fetch1['districtname'].', '.$fetch1['statename'].' - '.$fetch['pincode'];
        $responsearray['emailid'] = $fetch['emailid'];
        $responsearray['phone'] = $fetch['phone'];
        $responsearray['cell'] = $fetch['cell'];
        $responsearray['customertype'] = $fetch['customertype'];
        $responsearray['customercategory'] = $fetch['customercategory'];
        $responsearray['paymentamount'] = $fetch['paymentamount'];
        $responsearray['remarks'] = $fetch['remarks'];
        $responsearray['invoiceremarks'] = $fetch['invoiceremarks'];
        $responsearray['dealerid'] = $fetch['dealerid'];

        $responsearray['podate'] = $podate;
        $responsearray['poreference'] = $fetch['poreference'];
        $responsearray['gst_no'] = $fetch['gst_no'];
        $responsearray['cgst'] = $fetch['cgst'];
        $responsearray['sgst'] = $fetch['sgst'];
        $responsearray['igst'] = $fetch['igst'];
        $responsearray['state_info'] = $fetch['state_info'];
        $responsearray['seztaxtype'] = $fetch['seztaxtype'];
        $responsearray['hsncode'] = $hsncode;
        echo json_encode($responsearray);

    }
    break;
}


function getmaxslno()
{
	$query1 = "select ifnull(max(slno),0) + 1 as billref from inv_matrixinvoicenumbers";
	$resultfetch1 = runmysqlqueryfetch($query1);
	$onlineinvoiceslno = $resultfetch1['billref'];

	return $onlineinvoiceslno;
}
function getinvoiceno($invoice_type,$state_gst_code,$year)
{
    $varState = '2023'.$invoice_type;
    $queryonlineinv = "select ifnull(max(onlineinvoiceno),0)+ 1 as invoicenotobeinserted from inv_matrixinvoicenumbers where invoiceno like '%".$varState."%'";

    //Get the next invoice number from invoicenumbers table, for this new_invoice
    $resultfetchinv = runmysqlqueryfetch($queryonlineinv);
    $onlineinvoiceno = $resultfetchinv['invoicenotobeinserted'];
    $onlineinvoiceno=(string)$onlineinvoiceno;
    $onlineinvoiceno=sprintf('%06d', $onlineinvoiceno);
    $invoicenoformat = 'RSL'.$year.$invoice_type.''.$onlineinvoiceno;

	return array($invoicenoformat,$onlineinvoiceno);
}

function checkcustomer($lastslno)
{
	$userid = imaxgetcookie('dealeruserid');
	$query = "select inv_mas_dealer.relyonexecutive, inv_mas_dealer.district, inv_mas_district.statecode,inv_mas_dealer.telecaller,inv_mas_dealer.branch,inv_mas_dealer.region
	from inv_mas_dealer left join inv_mas_district on inv_mas_dealer.district = inv_mas_district.districtcode
 	where inv_mas_dealer.slno = '".$userid."';";
	$fetch = runmysqlqueryfetch($query);
	$relyonexecutive = $fetch['relyonexecutive'];
	$telecaller = $fetch['telecaller'];
	$region = $fetch['region'];
	$branch = $fetch['branch'];
	$district = $fetch['district'];
	$state = $fetch['statecode'];
	if($telecaller == 'yes')
	{
		$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) == 0)
		{
			$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '2' order by businessname;";
			$result = runmysqlquery($query);
		}
	}
	else
	{
		if($relyonexecutive == 'no')
		{
			$query = "select distinct inv_mas_customer.slno as slno, inv_mas_customer.businessname as businessname  from inv_mas_customer where inv_mas_customer.currentdealer = '".$userid."' order by businessname;";
			$result = runmysqlquery($query);
		}
		else
		{
			if(($region == '1') || ($region == '3'))
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where region = '1' or region = '3' order by businessname;";
				$result = runmysqlquery($query);
			}
			else
			{
				$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch in( select branchid from inv_dealer_branch_mapping where dealerid = '".$userid."') order by businessname;";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "select slno as slno, businessname as businessname from inv_mas_customer where branch = '".$branch."' order by businessname;";
					$result = runmysqlquery($query);
				}
			}
		}
	}
	//echo($query); exit;
	$grid = '';
	$count = 1;
	$resultvalue = 'true';
	while($fetch = mysqli_fetch_array($result))
	{			
		if($lastslno == $fetch['slno'])
		{

			$resultvalue = 'true';
			break;
		}
		else
		{
			$resultvalue = 'false';
		}
	}
	return $resultvalue;
}
?>