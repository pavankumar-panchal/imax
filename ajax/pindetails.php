<?php

ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

ini_set('memory_limit', '-1');
if(imaxgetcookie('userid')<> '') 
{
    $userid = imaxgetcookie('userid');
}
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
include('../inc/checksession.php');

$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'pindetails':
	{
		$pinno = trim($_POST['pinno']);
		$cardid = trim($_POST['cardid']);
		
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		
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
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}
		
			if($cardid!= "")
			{
				$carddetails = "cardid = '".$cardid."'";
			}
			if($pinno!= "")
			{
				$carddetails = "scratchnumber = '".$pinno."'";
			}
			
			if($pinno!= "" && $cardid!= "")
			{
				$carddetails = "scratchnumber = '".$pinno."' and cardid = '".$cardid."'";
			}
			
			$classdefine = "swifttext-white";
			$disable = 'readonly="readonly"';
			
			$query = "select * from inv_mas_scratchcard  where  ".$carddetails . " LIMIT ".$startlimit.",".$limit."";
					
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
				style="font-size:12px">';
				$grid .= '<tr class="tr-grid-header">
				<td nowrap = "nowrap" class="td-border-grid" align="left" colspan = "3">Pin Details</td><tr>';
			}
			$result = runmysqlquery($query);
			if(mysqli_num_rows($result) > 0)
			{
				while($fetch = mysqli_fetch_array($result))
				{
					$card = $fetch['cardid'];
					$pinnumber = $fetch['scratchnumber'];
					$i_n++;
					
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
						
					$registerpin = registerpin($fetch['registered']);	
					
					$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
					
					$grid .= "<td nowrap='nowrap' 
					class='td-border-grid' align='left' style='width:32.33%'>
					<strong>Sl No : </strong>".$fetch['slno']."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'><strong>Card : 
					</strong>".$fetch['cardid']."<input name='cardidhidden' type='hidden' id='cardidhidden' size='20'
					 maxlength='25' class='swifttext'  autocomplete='off' value='".$fetch['cardid']."'/>
					</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='color:green' style='width:32.33%'> 
					<strong>Pin Number : 
					</strong>".$fetch['scratchnumber']."</td>
					</tr>
					<tr class='gridrow' bgcolor='".$color."' >
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'> 
					<strong>Attached : </strong>". $fetch['attached']."
					</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'><span style='float:left'>
					<strong>Registered : </strong>
					</span><span id='dealerinputdiv' style='display:block; float:left; padding-left:3px;' >". 
					$fetch['registered'] ."</span><span 
					style='display:none;' id='dealerselectiondiv'>". $registerpin ."</span></td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'> 
					<strong>Blocked : </strong>".$fetch['blocked']."
					<span style='text-align:left'><input name='blocked' type='hidden' 
					id='blocked' size='20' maxlength='400'  autocomplete='off' 
					value = '".$fetch['blocked']."'/>
					</span> 
					</td></tr>
					<tr class='gridrow' bgcolor='".$color."'>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'> 
					<strong>Online : </strong>".$fetch['online']."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'>
					<strong>Cancelled : </strong>".$fetch['cancelled']."
					<span style='text-align:left'><input name='cancelled' type='hidden' 
					id='cancelled' size='20' maxlength='400'  autocomplete='off' 
					value = '".$fetch['cancelled']."'/>
					</span></td>
					<td nowrap='nowrap'  class='td-border-grid' align='left' style='width:32.33%'>
					<strong>Remarks : </strong>".wordwrap($fetch['remarks'],20,"<br>\n")."
					</td></tr>";
				   }
				}
				else
				{
					if($card == "")
					{
						$value = "no record";
					}
					$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<input name='tabledata' type='hidden' 
					id='tabledata' size='20' maxlength='400'  autocomplete='off' 
					value = '".$value."'/>
					<strong>No Records Found</strong>
					</td>";
				}
				
			 $grid .= "</table><br>";
			
			
			//Dealer Details
			if($card!= "")
			{
				$query1 = "select inv_dealercard.slno,inv_dealercard.dealerid,inv_dealercard.productcode,inv_mas_customer.businessname,
				inv_dealercard.date,inv_dealercard.usagetype,inv_dealercard.purchasetype,inv_mas_product.productname,
				inv_dealercard.customerreference,inv_dealercard.cuscardattacheddate,inv_dealercard.invoiceid,
				d1.businessname as dealername,d2.businessname as sub_dealer
				from inv_dealercard
				left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
				left join inv_mas_customer on inv_dealercard.customerreference = inv_mas_customer.slno
				left join inv_mas_dealer d1 on inv_dealercard.dealerid = d1.slno
				left join inv_mas_dealer d2 on inv_dealercard.sub_dealer = d2.slno
				where inv_dealercard.cardid = ".$card;
				
				if($startlimit == 0)
				{
					$grid .= '<table width="100%"  cellpadding="3" cellspacing="0" class="table-border-grid" 
					style="font-size:12px;">';
					
					$grid .= '<tr class="tr-grid-header">
					<td nowrap = "nowrap" class="td-border-grid" align="left" colspan = "11">Dealer Details</td><tr>';
					
				}
				$result1 = runmysqlquery($query1);
				$i_n = 0;
				$count1 = mysqli_num_rows($result1);
				if($count1 > 0)
				{
					while($fetch1 = mysqli_fetch_array($result1))
					{
						$invoiceid = $fetch1['invoiceid'];
						$cardslno = $fetch1['slno'];
						$i_n++;
						$color;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						
						$grid .= '<tr class="gridrow" bgcolor='.$color.'>';

						if(!empty($fetch1['sub_dealer']))
						{
							$subdealer = "<br><strong>Sub Dealer: </strong>".wordwrap($fetch1['sub_dealer'],15,"<br>\n");
						}
						
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:34.33%'>
						<strong>Dealer : </strong><span 
						style='text-align:left'><input name='dealerid' type='text' class='".$classdefine."' id='dealerid' 
						size='6' maxlength='400'  autocomplete='off' value = '".$fetch1['dealerid']."' ".$disable."/>
						</span><br>
						<strong>Dealer Name : </strong>".wordwrap($fetch1['dealername'],15,"<br>\n").$subdealer."</td>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:34.33%'>
						<strong>Dealer AttachDate : 
						</strong>".$fetch1['date']."</td>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
						<strong>InvoiceId : </strong>".$fetch1[
						'invoiceid']."</td>";
						if($count1 > 1)
						{
							$grid .= "<td rowspan='3' class='td-border-grid' align='left' >";
							if($userid == '146')
								$grid .= "<input name='deletedealerdetails' type='button' class= 'swiftchoicebuttonbig' id='deletedealerdetails' value= 'Delete' onclick='deletedealerdata(\"".$cardslno."\");'  /></td>";
						}
						$grid .= "</tr>
						<tr class='gridrow' bgcolor='".$color."'>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>CustomerId : 
						</strong><span style='text-align:left;'><input name='customerid' type='text' 
						class='".$classdefine."' id='customerid' size='10' maxlength='400'  autocomplete='off' 
						value = '".$fetch1['customerreference']."' ".$disable."/></span>
						<span id='dealersearchdiv' style='display:none;' >
						<input name='search' type='button' id='search' value= 'Search' onclick='getcustname();'  /></span>
						</td>
						
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:34.33%'><strong>Customer Name : 
						</strong><span style='text-align:left'><input name='customername' type='text' 
						class='".$classdefine."' id='customername' size='30' maxlength='400'  autocomplete='off' 
						value = '".$fetch1['businessname']."' readonly='readonly'/></span></td>
						
						
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
						<strong>Attacheddate : </strong>".
						$fetch1['cuscardattacheddate']."</td></tr>
				
						<tr class='gridrow' bgcolor='".$color."'>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>Productcode : 
						</strong>".$fetch1['productcode']."</td>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
						<strong>Usage Type : </strong>".$fetch1[
						'usagetype']."<br>
						<strong>Purchase Type : </strong>".$fetch1['purchasetype']."
						</td>
						<td nowrap='nowrap' class='td-border-grid' align='left' style='width:34.33%'><strong>Product Name : 
						</strong>".$fetch1['productname']."</td></tr>";
						
					}
				}
				else
			    {
					$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<strong>No Records Found</strong>
					</td>";
				}
				
				$grid .= "</table><br>";
				
			}
				
			//Invoice Details	
			if($invoiceid!= "")
			{
				$query2 = "select * from inv_invoicenumbers where slno = ".$invoiceid;
				
				$i_n = 0;
				$result2 = runmysqlquery($query2);
				
				while($fetch2 = mysqli_fetch_array($result2))
				{
					$array1 = explode("*", $fetch2['description']);
					$i_n++;
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
						
						$query3 = "select sum(receiptamount) as receivedamount from inv_mas_receipt where 
						invoiceno = '".$fetch2['slno']."' and restatus != 'CANCELLED';";
					$resultfetch = runmysqlqueryfetch($query3);
					if($resultfetch['receivedamount'] == '')
					{
						$receivedamount = 0;
					}
					else
					{
						$receivedamount = $resultfetch['receivedamount'];
					}
					
					$balanceamount = $fetch2['netamount'] - $receivedamount;
					
					if($startlimit == 0)
					{
						$grid .= '<table width="100%"  cellpadding="3" cellspacing="0" class="table-border-grid" 
						style="font-size:12px">';
						$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" 
						align="left" colspan = "10">Invoice Details</td><tr>';
					}
					$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'>
					<strong>Invoice No : </strong>".$fetch2['invoiceno']."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'>
					<strong>Invoice Amount : 
					</strong>".$fetch2['netamount']."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<strong>Status : </strong>".$fetch2['status'].
					"</td></tr>
			
					<tr class='gridrow' bgcolor='".$color."'>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'>
					<strong>Date : </strong>". 
					changedateformatwithtime($fetch2['createddate']) ."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'>
					<strong>Received Amount : </strong>".
					$receivedamount."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<strong>Outstanding Amount : 
					</strong>".$balanceamount."</td>
					</tr>
					
					<tr class='gridrow' bgcolor='".$color."'>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'>
					<strong>Generated By : </strong>".$fetch2[
					'createdby']."</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:37.33%'>&nbsp;</td>
					<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>&nbsp;</td>
					</tr>";
					$grid .= "</table><br>";
							
					for($i=0;$i<sizeof($array1);$i++)
					{
						//echo $i;
						$array = explode("$",$array1[$i]);
					
						$i_n++;
						$color;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
							
						if($pinnumber == $array[4])
						{
							$tabledata = "<td nowrap='nowrap' style='color:green' class='td-border-grid' 
							align='left' style='width:33.33%'>
							<strong>PIN Number : </strong>".$array[4]."</td>";
						}
						else
						{
							$tabledata = "<td nowrap='nowrap' style='color:red' class='td-border-grid' 
							align='left' style='width:33.33%'>
							<strong>PIN Number : </strong>".$array[4]."</td>";
						}
						if($pinnumber == $array[4])
						{	
							if($startlimit == 0)
							{
								$grid .= '<table width="100%"  cellpadding="3" cellspacing="0" class="table-border-grid" 
								style="font-size:12px">';
								$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" 
								align="left" colspan = "10">Invoice Description Details</td></tr>';
							}	
							$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
							$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
							<strong>Product Name : </strong>". $array[1] ."</td><td nowrap='nowrap' 
							class='td-border-grid' align='left' style='width:32.33%'>
							<strong>Card : </strong>".$array[5]."
							</td>".$tabledata.
							"</tr>
					
							<tr class='gridrow' bgcolor='".$color."'>
							 <td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
							 <strong>Purchase Type : </strong>".$array[2]. "</td>
							<td nowrap='nowrap' class='td-border-grid' align='left' style='width:32.33%'><strong>Usage Type : 
							</strong>".$array[3]."
							</td>
							<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>Amount : 
							</strong>".$array[6]."</td>
							</tr>";
							$grid .= "</table><br>";
						}
					}
					
				}
			}
			
			//Invoice Dummy details
			if($card!= "")
			{
				$query5 = "select * from inv_invoicenumbers_dummy_regv2 where cardid = ".$card;
				if($startlimit == 0)
				{
					$grid .= '<table width="100%" cellpadding="3" cellspacing="0" 
					class="table-border-grid" style="font-size:12px">';
					$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" colspan = "10">
					Invoice Dummy Details</td></tr>';
				}
				$i_n = 0;
				$result5 = runmysqlquery($query5);
				if(mysqli_num_rows($result5) > 0)
				{	
					while($fetch5 = mysqli_fetch_array($result5))
					{
						$Array = explode("$", $fetch5['description']);
						$i_n++;
						$color;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
							
						$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
						
						if($pinnumber == $Array[4])
						{
							$tabledummydata = "<td nowrap='nowrap'style='color:green' class='td-border-grid' align='left'>
							<strong>PIN Number : </strong>".$Array[4]."</td>";
						}
						else
						{
							$tabledummydata = "<td nowrap='nowrap'style='color:red' class='td-border-grid' align='left'>
							<strong>PIN Number : </strong>".$Array[4]."</td>";
						}
						
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>Dealer : 
								</strong>". $fetch5['dealerid'] ."</td>
								<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
								<strong>Card : </strong>".$Array[5]."
								</td>".$tabledummydata."</tr>
						
								<tr class='gridrow' bgcolor='".$color."'>
								<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
								<strong>Product Name : 
								</strong>".$Array[1]."</td>
								<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
								<strong>Purchase Type : </strong>".$Array[2]."
								</td>
								<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>
								Usage Type : </strong>".$Array[3]."</td></tr>
								
								<tr class='gridrow' bgcolor='".$color."'>
								<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
								<strong>Date : </strong>". $fetch5['date'] ."
								</td>
								<td nowrap='nowrap' class='td-border-grid' align='left' colspan='2'>
								<input name='deletedummydetails' type='button' 
								class= 'swiftchoicebuttonbig' id='deletedummydetails' 
								value= 'Delete' onclick='deletedata();'  />
								</td>
								</tr>";
					   }
					}
					else
					{
						$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
						<strong>No Records Found</strong>
						<span style='display: none; padding-left:20px;' id='invoicedummydiv'>
						<input type='checkbox' name='invoicedummy' id='invoicedummy' value='1'></span></td>
						</tr>";
					}
				
				$grid .= "</table><br>";
				}
			
			
			//Old and new registration  details
			if($card!= "")
			{
				$query16 = "select * from inv_customerproduct where cardid = ".$card;
				if($startlimit == 0)
				{
					$grid .= '<table width="100%" cellpadding="3" cellspacing="0" 
					class="table-border-grid" style="font-size:12px">';
					
				}
				$i_n = 0;
				$result16 = runmysqlquery($query16);
				if(mysqli_num_rows($result16) > 0)
				{	
						while($fetch16 = mysqli_fetch_array($result16))
						{
							$autoregistration = $fetch16['AUTOREGISTRATIONYN'];
							$activelicense = $fetch16['ACTIVELICENSE'];
							
							if($activelicense == 1)
							{
								$activelicense = "Yes";
							}
							else
							{
								$activelicense = "No";
							}
							$i_n++;
							$color;
							if($i_n%2 == 0)
								$color = "#edf4ff";
							else
								$color = "#f7faff";
								
							if($autoregistration == "N")
							{	
								$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
								colspan = "10">Old Registration Details</td></tr>';
								$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
								$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										 <strong>CustomerId : </strong>". $fetch16['customerreference'] ."</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
										<strong>Dealer : </strong>".$fetch16['dealerid']."
										</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										<strong>Softkey : </strong>".$fetch16['softkey']."
										</td></tr>
								
										<tr class='gridrow' bgcolor='".$color."'>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
										<strong>Computer ID : </strong>".$fetch16['computerid']."
										</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
										<strong>Date : </strong>".$fetch16['date']."
										</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'><strong>
										Time : </strong>".$fetch16['time']."</td>";
							}
							else
							{
								$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
								colspan = "10">New Registration Details</td></tr>';
								$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
								$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										 <strong>HDDID : </strong>". wordwrap($fetch16['HDDID'],25,"<br>\n",TRUE) ."</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
										<strong>ETHID : </strong>".wordwrap($fetch16['ETHID'],25,"<br>\n",TRUE)."
										</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										<strong>RegType : </strong>".$fetch16['REGTYPE']."
										</td></tr>
								
										<tr class='gridrow' bgcolor='".$color."'>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										<strong>Computer Name : 
										</strong>".$fetch16['COMPUTERNAME']."</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
										<strong>ComputerIP : </strong>".$fetch16['COMPUTERIP']."
										</td>
										<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
										<strong>Active License : </strong>".$activelicense."</td>";
							}
								
						   }
						}
					 else
					{
						$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
						colspan = "10">Old/New Registration Details</td></tr>';
						$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
						<strong>No Records Found</strong></td></tr>";
					}
				
				    $grid .= "</table><br>";
					
				}

				echo '1^'.$grid;			
	}

	break;

//deletedealerdata
case 'deletedealerdata':
{
	$cardidslno = trim($_POST['cardidslno']);

	$query = "delete from inv_dealercard where slno = ".$cardidslno;
	$result = runmysqlquery($query);
	echo '1^Record Deleted.';
}
break;

//rci details 
	case 'rcidetails':	
	{
	
	    $pinnum = trim($_POST['pinnum']);
		$cardnum = trim($_POST['cardnum']);
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
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
			$startlimit = $slnocount;
			$slnocount = $slnocount;
		}
        if($cardnum!= "")
		{
			$carddetails = "cardid = '".$cardnum."'";
		}
		if($pinnum!= "")
		{
			$carddetails = "scratchnumber = '".$pinnum."'";
		}
			
		if($pinnum!= "" && $cardnum!= "")
		{
			$carddetails = "scratchnumber = '".$pinnum."' and cardid = '".$cardnum."'";
		}
		
			$sql = runmysqlquery("select cardid,scratchnumber from inv_mas_scratchcard  where  ".$carddetails);
            $row = mysqli_fetch_row($sql) ;
            $getcard = $row[0];
            $getpin = $row[1];
		
			if($getcard!= "")
		   {
			//comparing computerid
			$sql1 = runmysqlquery("select computerid from inv_customerproduct where cardid = ".$getcard." AND activelicense='1'");
            $row1 = mysqli_fetch_row($sql1) ;
            $compid = $row1[0];
			if($startlimit == 0)
			{
				$grid .= '<table width="100%" cellpadding="3" cellspacing="0" 
						  class="table-border-grid" style="font-size:12px">';
			}
			//displaying last usage record of RCI Details
			if(mysqli_num_rows($sql1) > 0)
			{
				$queryrci1 = "select inv_logs_webservices.`date`,
				inv_mas_product.productname,inv_logs_webservices.registeredname,
				inv_logs_webservices.pinnumber,inv_logs_webservices.computerid 
				from inv_logs_webservices left join inv_mas_product on inv_mas_product.productcode =  
				left(inv_logs_webservices.computerid,3)		
				where inv_logs_webservices.pinnumber = '".$getpin."' order by `date` desc  LIMIT 1; ";
				
				if($startlimit == 0)
			    {
					 $grid .= '<table width="100%" cellpadding="3" cellspacing="0" 
							   class="table-border-grid" style="font-size:12px">';
					 $grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
							   colspan = "5">Last Usage</td></tr>';
		        }
				
				$i_n = 0;
		        $resultrci1 = runmysqlquery($queryrci1);
				if(mysqli_num_rows($resultrci1) > 0)
				{
				   while($fetchrci1 = mysqli_fetch_array($resultrci1))
				  {
							$i_n++;
							$slno++;
							$color;
							if($i_n%2 == 0)
								$color = "#edf4ff";
							else
								$color = "#f7faff";
							if($compid == $fetchrci1['computerid'])
							{
								$tabledata1 = "<td nowrap='nowrap' style='color:green' class='td-border-grid' 
								align='left' style='width:33.33%'><strong>Computer ID: </strong>
								".$fetchrci1['computerid']."</td>";
							}
							else
							{
								$tabledata1 = "<td nowrap='nowrap' style='color:red' class='td-border-grid' 
								align='left' style='width:33.33%'><strong>Computer ID: </strong>
								".$fetchrci1['computerid']."</td>";
							}
							/*$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
									colspan = "5">Last Usage</td></tr>';*/
							$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
							$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
									  <strong>Date : </strong>". $fetchrci1['date']."</td>
									  <td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'>
									  <strong>Product Name : </strong>".$fetchrci1['productname']."</td>
									  <td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
									  <strong>Registered Name : </strong>".$fetchrci1['registeredname']."</td>
									  </tr>
									  <tr class='gridrow' bgcolor='".$color."'>
									  <td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
									  <strong>Pin Number : 
									  </strong>".$fetchrci1['pinnumber']."</td>$tabledata1
									  <td nowrap='nowrap' class='td-border-grid' align='left' style='width:35.33%'></td>
									  </tr>";
				   }
			   
				}
		         else
				 {
					/*$grid .= '<table><tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
					colspan = "5">Last Usage</td></tr>';*/
					$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<strong>No Records Found</strong></td></tr>";
				 }
		        $grid .= "</table><br>";
		       //table
		      $comptid=$compid;
              $queryrcid="select                  
			  inv_logs_webservices.registeredname,inv_logs_webservices.computerid,
			  inv_logs_webservices.productversion,inv_mas_product.productname, count(inv_logs_webservices.slno) as UsageCount
			   from inv_logs_webservices 
               left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)
               where pinnumber='".$getpin."' 
               group by inv_logs_webservices.registeredname,inv_logs_webservices.computerid,
			   inv_logs_webservices.productversion,inv_mas_product.productname order by productversion desc,UsageCount desc;";
			
		       if($startlimit == 0)
			   {
				 $grid .= '<table width="100%" cellpadding="3" cellspacing="0" 
						   class="table-border-grid" style="font-size:12px">';
				 $grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left" 
						   colspan = "5">History of last used details</td></tr>';
		       }
		    
				$i_n = 0;
				$resultrcid = runmysqlquery($queryrcid);		
				if(mysqli_num_rows($resultrcid) > 0)
				{
					$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">
					Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Registered Name</td>
					<td nowrap = "nowrap"class="td-border-grid" align="left">Product Name</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Product Version</td>
					<td nowrap = "nowrap" class="td-border-grid" align="left">Count</td></tr>';		
				   while($fetchrcid = mysqli_fetch_array($resultrcid))
			       {             
						$i_n++;
						$slnonew++;
						$color;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						
							if($comptid == $fetchrcid['computerid'])
						{
							$tabledatat = "<td nowrap='nowrap' style='color:green' class='td-border-grid' 
							align='left' style='width:33.33%'>
							".$fetchrcid['computerid']."</td>";
						}
						else
						{
							$tabledatat = "<td nowrap='nowrap' style='color:red' class='td-border-grid' 
							align='left' style='width:33.33%'>
							".$fetchrcid['computerid']."</td>";
						}	
				
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .="$tabledatat";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>
					 <a href'#' Onclick ='displayrcicustdata(\"".$fetchrcid['registeredname']."\",\"".$fetchrcid['productversion']                     ."\",\"". $fetchrcid['computerid']."\");' class='resendtext' style='cursor:pointer;'>".$fetchrcid[ 
				  'registeredname']."</a></td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchrcid['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchrcid['productversion']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchrcid['UsageCount']."</td>";
			$grid .= "</tr>";
		
		           }
				}
		        else
				{
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
					<strong>No Records Found</strong></td></tr>";
				}
	
		      $grid .= "</table><br>";
			  
			}
			else
			{
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' style='width:33.33%'>
				<strong>No Records Found</strong></td></tr>";
			}
		}
		else
		{
			$grid .= "<table width='100%' cellpadding='3' cellspacing='0' class='table-border-grid' style='font-size:12px'><td 
			nowrap='nowrap' class='td-border-grid'  align='left' style='width:33.33%'>
			<strong>No Records Found</strong></td></tr></table>";
		}
		echo '1^'.$grid;
	}

	
	break;
	
	//RCI POPUP
	case 'rcidetailsgridcust':
	{
		$slno = $_POST['slno'];
		$regname=$_POST['regname'];
		$proversion=$_POST['proversion'];
		$comid=$_POST['comid'];

		$querynew = "select `date`,inv_mas_product.productname,inv_logs_webservices.productversion,inv_logs_webservices.operatingsystem,inv_logs_webservices.processor,inv_logs_webservices.registeredname,inv_logs_webservices.pinnumber,inv_logs_webservices.computerid,inv_logs_webservices.servicename from inv_logs_webservices left join inv_mas_product on inv_mas_product.productcode = left(inv_logs_webservices.computerid,3)	where productversion='".$proversion."' AND registeredname='".$regname."' AND inv_logs_webservices.computerid='".$comid."' order by productversion desc,date desc";
		$resultnew = runmysqlquery($querynew);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Version</td><td nowrap = "nowrap" class="td-border-grid" align="left">Operating System</td><td nowrap = "nowrap" class="td-border-grid" align="left">Processor</td><td nowrap = "nowrap" class="td-border-grid" align="left">Registered Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Computer ID</td><td nowrap = "nowrap" class="td-border-grid" align="left">Service Name</td></tr>';
		}
		$i_n = 0;
		if(mysqli_num_rows($resultnew) > 0)
		{
		while($fetchnew = mysqli_fetch_array($resultnew))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow1" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['date']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['productversion']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['operatingsystem']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['processor']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['registeredname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['pinnumber']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['computerid']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetchnew['servicename']."</td>";
			$grid .= "</tr>";
		}
		}
		$grid .= "</table>";
		echo '1^'.$grid;
	}
	break;
	
case 'editdata':
	{
		$customerid = $_POST['customerid'];
		$editcardidhidden = $_POST['cardidhidden'];
		$dealerid = $_POST['dealerid'];
		$registerpin = $_POST['registerpin'];
		$invoicedummy = $_POST['invoicedummy'];
		$pinno = trim($_POST['pinno']);
		//$cardid = $_POST['cardid'];
		$date = date('Y-m-d').' '.date('H:i:s');
		
		if($editcardidhidden!= "")
		{
			$carddetails = "cardid = ".$editcardidhidden;
		}
		if($pinno!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."'";
		}
		
		if($pinno!= "" && $editcardidhidden!= "")
		{
			$carddetails = "scratchnumber = '".$pinno."' and cardid = ".$editcardidhidden;
		}
		
		$query6 = "select * from inv_mas_scratchcard  where  ".$carddetails ;
		$result6 = runmysqlquery($query6);
		
		while($fetch6 = mysqli_fetch_array($result6))
		{
			$card = $fetch6['cardid'];
			$register = $fetch6['registered'];
			$scratchnumber = $fetch6['scratchnumber'];
		}
		
		$query7 = "select inv_dealercard.dealerid,inv_dealercard.productcode,inv_mas_customer.businessname,
				inv_dealercard.date,inv_dealercard.usagetype,inv_dealercard.purchasetype,inv_mas_product.productname,
				inv_dealercard.customerreference,inv_dealercard.cuscardattacheddate,inv_dealercard.invoiceid
				from inv_dealercard
				left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode
				left join inv_mas_customer on inv_dealercard.customerreference = inv_mas_customer.slno 
		        where inv_dealercard.cardid = ".$editcardidhidden;
		$result7 = runmysqlquery($query7);
		
		while($fetch7 = mysqli_fetch_array($result7))
		{
			$dealercard = $fetch7['cardid'];
			$custid = $fetch7['customerreference'];
			$productname = $fetch7['productname'];
			$usagetype = $fetch7['usagetype'];
			$purchasetype = $fetch7['purchasetype'];
			//$dummycustomerid = cusidcombine($fetch7['customerid']);
			$cuscardattacheddate = $fetch7['cuscardattacheddate'];
		}
		
		if($dealercard!= $dealerid)
		{
			$query8 = "update inv_dealercard set dealerid = ".$dealerid." where cardid = ".$card;
			$result8 = runmysqlquery($query8);
		}
		
		if($custid!= $customerid)
		{
			if($customerid!= "" && $cuscardattacheddate!= "")
			{
				$query9 = "update inv_dealercard set customerreference = ".$customerid."
				where cardid = ".$card;
				$result9 = runmysqlquery($query9);
			}
			else if(($custid == "" || $custid == NULL) && ($cuscardattacheddate == "" || $cuscardattacheddate == NULL))
			{
				$query9 = "update inv_dealercard set customerreference = ".$customerid.",cuscardattacheddate = 
				'".$date."' where cardid =".$card;
				$result9 = runmysqlquery($query9);
			}
			else if($customerid == "" && $cuscardattacheddate!= "")
			{
				$query9 = "update inv_dealercard set customerreference = '".$customerid."',cuscardattacheddate = 
				'".$date."' where cardid = ".$card;
				$result9 = runmysqlquery($query9);
			}
		}
		
		if($register!= $registerpin && $registerpin!= "")
		{
			$query10 = "update inv_mas_scratchcard set registered = '".$registerpin."'
			where cardid = ".$card;
			$result10 = runmysqlquery($query10);
		}
		
		if($invoicedummy == "1")
		{
			if($usagetype == "singleuser" || $usagetype == "additionallicense")
			{
			   $dummyusagetype = "Single User";
			}
			elseif($usagetype == "multiuser")
			{
				$dummyusagetype = "Multi User";
			}
			
			$invoiceformat = "1"."$".$productname."$".$purchasetype."$".$dummyusagetype."$".$scratchnumber."$".$card."$"."0";
			
			
			$query16 = "select inv_mas_customer.customerid from inv_mas_customer
				left join inv_dealercard on inv_mas_customer.slno = inv_dealercard.customerreference 
		        where inv_dealercard.cardid = ".$editcardidhidden;
			$result16 = runmysqlquery($query16);
			while($fetch16 = mysqli_fetch_array($result16))
			{
				$dummycustomerid = cusidcombine($fetch16['customerid']);
			}
			
			$query11 = "insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid)
			values(".$dealerid.",'".$dummycustomerid."','".$date."','".$invoiceformat."',".$card.")";
			//$result11 = mysqli_query($query11);
			$result11 = runmysqlquery($query11);
		}
		
	}
	break;
	
	case 'deletedata':
	{
		$cardidhidden = $_POST['cardidhidden'];
		
		$query13 = "delete from inv_invoicenumbers_dummy_regv2 where cardid = ".$cardidhidden;
		$result13 = runmysqlquery($query13);
	}
	break;
	
	case 'getcustname':
	{
		$customerid = $_POST['customerid'];
		
		if($customerid!= "")
		{
			$query15 = "select businessname from inv_mas_customer  
			where inv_mas_customer.slno  = ".$customerid;
			$result15 = runmysqlquery($query15);
			while($fetch15 = mysqli_fetch_array($result15))
			{
				echo $fetch15['businessname'];
			}
		}
	}
	break;
	
}

function registerpin($registerespin)
{
    
	$selectres = '<select name="registerpin" class="swiftselect" id="registerpin" style="width:160px;" class="swiftselect">
	<option  value="">Select</option>';
	if($registerespin == "yes")
	{
		$selectres .= '<option  value="yes">yes</option>';
	    $selectres .= '<option  value="no">no</option>';  
	}
	else
	{
		$selectres .= '<option  value="no">no</option>';
	    $selectres .= '<option  value="yes">yes</option>';
	}
	
	$selectres .=  '</select>';
	return $selectres;
}

?>
