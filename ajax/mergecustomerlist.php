<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
//include('../functions/getdistrictjsnew.php');
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}

$type = $_POST['switchtype'];

switch($type)
{
	case 'mergelist':
	{
		$responsearray1 = array();
		$subselection = $_POST['subselection'];
		switch($subselection)
			{
					case "Company":
					
					$query = "select name,businessname as cusname, count(*) as mycount, GROUP_CONCAT(slno SEPARATOR '^')as slnos from 
(select slno, replace(replace(businessname,' ',''),'.','') as businessname,businessname as name from inv_mas_customer) as inv_mas_customer group by businessname having mycount > 1;";
					break;
					
					case "Emailid":
					$query = "select name,emailid, count(*) as mycount, GROUP_CONCAT(slno SEPARATOR '^') as slnos from 
(select distinct inv_mas_customer.slno, group_concat(inv_contactdetails.emailid) as emailid,
inv_mas_customer.businessname as name from inv_mas_customer 
left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where emailid <> '' and emailid <> 'a@a.com' group by inv_mas_customer.slno) as inv_mas_customer
group by emailid having mycount > 1;";
					break;
					
					case "Phone": 
						$query = "select name,phone, count(*) as mycount, GROUP_CONCAT(slno SEPARATOR '^') as slnos from 
(select distinct inv_mas_customer.slno, GROUP_CONCAT(inv_contactdetails.phone) as phone,inv_mas_customer.businessname as name from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_contactdetails.phone <> '' and inv_contactdetails.phone not like '%999999%' 
and inv_contactdetails.phone not like '%000000%'and inv_contactdetails.phone not like '%111111%'
and inv_contactdetails.phone not like '%222222%' group by inv_mas_customer.slno) as inv_mas_customer
group by phone having mycount > 1;";
					break;
					
					case "Cell":
						$query = "select name,cell, count(*) as mycount, GROUP_CONCAT(slno SEPARATOR '^') as slnos from 
(select inv_mas_customer.slno, GROUP_CONCAT(inv_contactdetails.cell) as cell,inv_mas_customer.businessname as name 
from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_contactdetails.cell <> '' and inv_contactdetails.cell not like '%99999999%' and inv_contactdetails.cell not like '%9000000000%' and inv_contactdetails.cell not like '9111111111' group by inv_mas_customer.slno) as inv_mas_customer group by cell having mycount > 1;";
					break;
					
					case "Website":
						$query = "select name,website, count(*) as mycount, GROUP_CONCAT(slno SEPARATOR '^') as slnos from 
(select inv_contactdetails.cell as cell,inv_mas_customer.slno, inv_mas_customer.website,inv_mas_customer.businessname as name from inv_mas_customer left join inv_contactdetails on inv_contactdetails.customerid = inv_mas_customer.slno
where inv_mas_customer.website <> '' and inv_contactdetails.cell not like '%99999999%' group by inv_mas_customer.slno) as inv_mas_customer group by website having mycount > 1";;
					break;
					
			}
			$result = runmysqlquery($query);
			$count = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$responsearray1[$count] = $fetch['slnos'];
				$count++;
				
			}
			echo(json_encode($responsearray1));
		//echo($query);
	}
	break;
	case 'getdata': 
				$responsearray2 = array();
				$slnos = $_POST['slnos'];
				$newarray = explode('^',$slnos);
				for($j = 0;$j < count($newarray);$j++)
				{
					/*$query = "SELECT slno,customerid,businessname,address,place,district,pincode,contactperson,stdcode,phone,fax,cell,emailid,website,region,type,branch,state,currentdealer,category,lastmodifieddate,createddate  FROM inv_mas_customer WHERE slno='".$newarray[$j]."'";*/
					$query = "SELECT inv_mas_customer.slno, inv_mas_customer.customerid,inv_mas_customer.businessname, 
					GROUP_CONCAT(inv_contactdetails.contactperson) as contactperson, inv_mas_customer.address, inv_mas_customer.place, 
					inv_mas_customer.district,inv_mas_district.statecode as state,inv_mas_customer.pincode, inv_mas_customer.fax, inv_mas_customer.region,
					inv_mas_customer.branch,  inv_mas_customer.stdcode, GROUP_CONCAT(inv_contactdetails.phone) as phone,  
					GROUP_CONCAT(inv_contactdetails.cell) as cell,GROUP_CONCAT(inv_contactdetails.emailid) as emailid, 
					inv_mas_customer.website, inv_mas_customer.category, inv_mas_customer.type, inv_mas_customer.currentdealer,  inv_mas_branch.branchname as branchname, inv_mas_customercategory.businesstype as businesstype, inv_mas_customertype.customertype,inv_mas_customer.remarks,
					inv_mas_region.category as regionname, inv_mas_district.districtname as districtname,inv_mas_state.statename as statename, 
					inv_mas_dealer.businessname as dealerbusinessname,inv_mas_customer.lastmodifieddate,inv_mas_customer.category,
					max(inv_customerproduct.date) as lastregistrationdate FROM inv_mas_customer 
					LEFT JOIN inv_contactdetails ON inv_contactdetails.customerid = inv_mas_customer.slno 
					LEFT JOIN inv_mas_product ON inv_mas_product.productcode = inv_mas_customer.firstproduct  
					LEFT JOIN inv_mas_users ON inv_mas_customer.createdby = inv_mas_users.slno 
					left join inv_mas_district on inv_mas_customer.district = inv_mas_district.districtcode 
					left join inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
					left join inv_mas_customertype on inv_mas_customertype.slno = inv_mas_customer.type 
					left join inv_mas_customercategory on inv_mas_customercategory.slno = inv_mas_customer.category  
					left join inv_mas_region on inv_mas_region.slno = inv_mas_customer.region 
					left join inv_mas_branch on inv_mas_branch.slno = inv_mas_customer.branch 
					left join inv_mas_dealer on inv_mas_dealer.slno = inv_mas_customer.currentdealer 
					LEFT JOIN inv_customerproduct on inv_customerproduct.customerreference = inv_mas_customer.slno 
					where inv_mas_customer.slno = '".$newarray[$j]."' group by inv_mas_customer.slno;
					";
					
					$fetch = runmysqlqueryfetch($query);
					$valuecount = 0;
					// Query to fetch contact Details
					$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$newarray[$j]."'; ";
					$resultfetch = runmysqlquery($query1);
					$contactarray = '';
					// Add the details to array to store in hidden field
					while($fetchres = mysqli_fetch_array($resultfetch))
					{
						if($valuecount > 0)
						{
							$contactarray .= '****';
						}
						
						$selectiontype = $fetchres['selectiontype'];
						$contactperson = $fetchres['contactperson'];
						$phone = $fetchres['phone'];
						$cell = $fetchres['cell'];
						$emailid = $fetchres['emailid'];
						$slno = $fetchres['slno'];
						
						$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
					//	$refcontactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno;
						$valuecount++;
					}
					
					$slno = $fetch['slno'];
					$customerid = $fetch['customerid'];
					$businessname = $fetch['businessname'];
					$address = $fetch['address'];
					$place = $fetch['place'];
					$district = $fetch['district'];
					$pincode = $fetch['pincode'];
					$contactperson = $fetch['contactperson'];
					$stdcode = $fetch['stdcode'];
					$remarks=$fetch['remarks'];
					//Comma with space in phone and cell field
					$phone = $fetch['phone'];
					$phonearray = explode(',',$phone);
					$phonecount = count($phonearray);
					if($phonecount >= 2)
						$phonevalue = preg_replace('/, /',',',$phone);
					else
						$phonevalue = $phone;
					
					$cell = $fetch['cell'];
					$cellarray = explode(',',$cell);
					$cellcount = count($cellarray);
					if($cellcount >= 2)
						$cellvalue = preg_replace('/, /',',',$cell);
					else
						$cellvalue = $cell;
					
					$fax = $fetch['fax'];
					$emailid = $fetch['emailid'];
					$website = $fetch['website'];
					$region = $fetch['region'];
					$branch = $fetch['branch'];
					$type = $fetch['type'];
					$currentdealer = $fetch['currentdealer'];
					$category = $fetch['category'];
					$branchname = $fetch['branchname'];
					$districtname = $fetch['districtname'];
					$statename = $fetch['statename'];
					$dealername = $fetch['dealerbusinessname'];
					$type1 = $fetch['customertype'];
					$categorytype = $fetch['businesstype'];
					$lastregistration = $fetch['lastregistrationdate'];
					$regionname = $fetch['regionname'];
					$lastregistrationdate = changedateformat($lastregistration);
					$lastmodifieddate = $fetch['lastmodifieddate'];
					$changedateformat = changedateformatwithtime($lastmodifieddate);
					$query1 = 'select * from inv_mas_district where districtcode = "'.$district.'"';
					$resultfetch = runmysqlqueryfetch($query1);
					$state = $resultfetch['statecode'];
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="4">';
					$grid .= '<tr>';
					$grid .= '<td><table width="100%" border="0" cellspacing="0" cellpadding="0">';
 					$grid .= '<tr>';
    				$grid .= '<td><form id="customerdetails'.$j.'" name="customerdetails'.$j.'" method="post" onsubmit ="return false">';
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
  					$grid .= '<tr>';
    				$grid .= '<td><table width="100%" border="0" cellspacing="0" cellpadding="0">';
  					$grid .= '<tr >';
    				$grid .='<td align="left"><strong>'.$businessname.'</strong><input type="hidden" name="h_contactarray" id="h_contactarray" value="'.$contactarray.'"/></td>';
					$grid .='<td align="left"><div align="right">';
					if($j > 0)
					{
						$grid .='<label for="source'.$j.'">';
						$sourcetype = 'source'.'^'.$j;
						$grid .='<input type="radio" name="selectiontype'.$j.'" id="source'.$j.'" value="Source" onclick="sourceradioclick(\''.$j.'\',\''.count($newarray).'\')"  checked = "checked"  />';
					}
					else
					{
						$grid .='<label for="source'.$j.'">';
						$sourcetype = 'source'.'^'.$j;
						$grid .='<input type="radio" name="selectiontype'.$j.'" id="source'.$j.'" value="Source" onclick="sourceradioclick(\''.$j.'\',\''.count($newarray).'\')" />';
					}
					$grid .= '&nbsp;Source&nbsp;&nbsp;';
					$grid .='</label>';
					$destinationtype = 'destination'.'^'.$j;
					if($j == 0)
					{
						$grid .='<label for="destination'.$j.'">';
						$grid .='<input type="radio" name="selectiontype'.$j.'" id="destination'.$j.'" value="Destination" onclick="javascript:radiobuttonclick(\''.$j.'\',\''.count($newarray).'\');" checked = "checked"  />';
					}
					else
					{
						$grid .='<label for="destination'.$j.'">';
						$grid .='<input type="radio" name="selectiontype'.$j.'" id="destination'.$j.'" value="Destination" onclick="javascript:radiobuttonclick(\''.$j.'\',\''.count($newarray).'\');"  />';
					}
					$grid .= '&nbsp;Destination&nbsp;&nbsp';
					$grid .='</label>';
					$ignoretype = 'ignore'.'^'.$j;
					$grid .='<label for="ignore'.$j.'">';
					$grid .='<input type="checkbox" name="selectiontype'.$j.'" id="ignore'.$j.'" value="Ignore" onclick="javascript:ignorecheckbox(\''.$j.'\',\''.count($newarray).'\');" />';
					$grid .='&nbsp;Ignore</label></div></td>';
  					$grid .= '</tr>';
					$grid .= '<tr><td colspan = "2">&nbsp;</td></tr>';
					$grid .= '</table>';
					$grid .= '</td>';
  					$grid .= '</tr>';
					$grid .= '<tr>';
					$grid .= '<td>';
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">';
                    $grid .= '<tr>';
                    $grid .= '<td width="2%" height="22px" class="producttabheadnone"></td>';
                    $grid .= '<td width="18%" onclick="tabopen2(\'0\',\'tabg1'.$j.'\')" class="producttabheadactive" id="tabg1'.$j.'h0" style="cursor:pointer;"><div align="center"><strong>General Details</strong></div></td>';
                    $grid .= '<td width="2%" class="producttabheadnone"></td>';
					// Assign j to other variable (actually to increment it )
					$a = $j + 1;
                    $grid .= '<td width="18%" onclick="tabopen2(\'1\',\'tabg1'.$j.'\')" class="producttabhead" id="tabg1'.$j.'h1" style="cursor:pointer;"><div align="center"><strong>Contact Details</strong></div></td>';
                    $grid .= '<td width="2%" class="producttabheadnone">&nbsp;</td>';
                    $grid .= '<td width="18%" class="producttabhead" ></td>';
                    $grid .= '<td width="2%" class="producttabheadnone">&nbsp;</td>';
                    $grid .= '<td width="18%"  class="producttabhead" >&nbsp;</td>';
                    $grid .= '<td width="2%" class="producttabheadnone">&nbsp;</td>';
                    $grid .= '<td width="18%"  class="producttabhead">&nbsp;</td>';
                    $grid .= '</tr>';
                    $grid .= '</table>';
					$grid .= '</td>';
					$grid .= '</tr>';
					$grid .= '<tr>';
					$grid .= '<td><div style="display:block;"  align="justify" id="tabg1'.$j.'c0" >';
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="productcontent" style = "height:225px;">';
					$grid .='<tr>';
					$grid .='<td  width="50%" valign="top" align="left" style="border-right:1px solid #308ebc"><table width="100%" border="0" cellspacing="0" cellpadding="5" >';
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td width="24%" valign="top" bgcolor="#f7faff" align="left">Customer ID:</td>';
					$grid .='<td width="76%" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.cusidcombine($customerid).'<input type="hidden" name="slno" id="slno" value="'.$slno.'"/><input type="hidden" name="customerid" id="customerid" value="'.cusidcombine($customerid).'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr>';
					$grid .='<td width="24%" valign="top" bgcolor="#EDF4FF" align="left">Company:</td>';
					$grid .='<td width="76%" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$businessname.'<input type="hidden" name="h_businessname" id="h_businessname" value="'.$businessname.'"/> </td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					
					$grid .= '<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left" height = "25px">Address:</td>';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left" height = "25px"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$address.'</textarea><input type="hidden" name="h_address" id="h_address" value="'.$address.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left">Place:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$place.'<input type="hidden" name="h_place" id="h_place" value="'.$place.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left">State:</td>';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$statename.'<input type="hidden" name="h_state" id="h_state" value="'.$state.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td valign="top" bgcolor="#edf4ff " align="left">District:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td id="districtcodedisplay" align="left">'.$districtname.'<input type="hidden" name="h_district" id="h_district" value="'.$district.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left">Pincode:</td>';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr bgcolor=#f7faff">';
					$grid .='<td bgcolor="#f7faff" align="left">'.$pincode.'<input type="hidden" name="h_pincode" id="h_pincode" value="'.$pincode.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td width="24%" valign="top" align="left">Current Dealer:</td>';
					$grid .='<td width="76%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$dealername.'<input type="hidden" name="h_dealer" id="h_dealer" value="'.$currentdealer.'"/></td>';
					$grid .='</tr>';
					
					$grid .='</table></td>';
					$grid .= '</tr>';
						$grid .= '<tr bgcolor="#f7faff">';
					$grid .='<td width="30%" valign="top" align="left">Last registration date:</td>';
					$grid .='<td width="70%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$lastregistrationdate.'</td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='<td width="50%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">';
					$grid .='<tr bgcolor="#EDF4FF">';
					$grid .='<td valign="top" bgcolor="#EDF4FF" align="left">STD code:</td>';
					$grid .='<td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$stdcode.'<input type="hidden" name="h_stdcode" id="h_stdcode" value="'.$stdcode.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					
			
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" align="left">Fax:</td>';
					$grid .='<td valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$fax.'<input type="hidden" name="h_fax" id="h_fax" value="'.$fax.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left">Website:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$website.'<input type="hidden" name="h_website" id="h_website" value="'.$website.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left">Type:</td>';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$type1.'<input type="hidden" name="h_type" id="h_type" value="'.$type.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left">Category:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$categorytype.'<input type="hidden" name="h_category" id="h_category" value="'.$category.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left">Region:</td>';
					$grid .='<td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$regionname.'<input type="hidden" name="h_region" id="h_region" value="'.$region.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left">Branch:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$branchname.'<input type="hidden" name="h_branch" id="h_branch" value="'.$branch.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
					$grid .'<tr bgcolor="#edf4ff">';
					$grid .='<td width="30%" valign="top" bgcolor="#f7faff" align="left">Last Modified Date:</td>';
					$grid .='<td width="70%" valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr bgcolor="#edf4ff">';
					$grid .='<td bgcolor="#edf4ff" align="left">'.$changedateformat.'</td>';
					$grid .='</tr>';					
					$grid .='</table></td>';
					$grid .='</tr>';

					
					//changed by Nagamani
					
					$grid .='<tr bgcolor="#f7faff">';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left">Remarks:</td>';
					$grid .='<td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">';
					$grid .='<tr>';
					$grid .='<td align="left">'.$remarks.'<input type="hidden" name="h_remarks" id="h_remarks" value="'.$remarks.'"/></td>';
					$grid .='</tr>';
					$grid .='</table></td>';
					$grid .='</tr>';
				
					$grid .='</table></td>';
  					$grid .= '</tr>';
					$grid .= '</table>';
					$grid .= '</div>';
					$a = $j + 1;
					$grid .= '<div style="display:none;" align="justify" id="tabg1'.$j.'c1">';
					$grid .= '<table width="100%" border="0" cellspacing="0" cellpadding="3" class="productcontent" style=" height:225px;" >';
					
  					$grid .= '<tr>';
   					$grid .= '<td valign = "top" ><table width="100%" border="0" cellspacing="0" cellpadding="4"  style="color:#646464;" class="table-border-grid" >';
					
					$grid .= '<tr class="tr-grid-header">';
    				$grid .= '<td width="8%" align = "left" class="td-border-grid"><strong>Sl No</strong></td>';
    				$grid .= '<td width="14%" align = "left" class="td-border-grid"><strong>Type</strong></td>';
    				$grid .= '<td width="20%" align = "left" class="td-border-grid"><strong>Name</strong></td>';
    				$grid .= '<td width="20%" align = "left" class="td-border-grid"><strong>Phone</strong></td>';
    				$grid .= '<td width="15%" align = "left" class="td-border-grid"><strong>Cell</strong></td>';
   					$grid .= '<td width="23%" align = "left" class="td-border-grid"><strong>Email Id</strong></td>';
  					$grid .= '</tr>';
					$m = 0;
					$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$newarray[$j]."'; ";
					$resultfetch = runmysqlquery($query1);
					$slnumber = 0;
					$i_n = 0;
					while($fetchres = mysqli_fetch_array($resultfetch))
					{
						if($fetchres['selectiontype'] == 'general')
							$type = 'General';
						else if($fetchres['selectiontype'] == 'gm/director')
							$type = 'GM/Director';
						else if($fetchres['selectiontype'] == 'hrhead')
							$type = 'HR Head';
						else if($fetchres['selectiontype'] == 'ithead/edp')
							$type = 'IT-Head/EDP';
						else if($fetchres['selectiontype'] == 'softwareuser')
							$type = 'Software User';
						else if($fetchres['selectiontype'] == 'financehead')
							$type = 'Finance Head';
						else if($fetchres['selectiontype'] == 'others')
							$type = 'Others';  
						else if($fetchres['selectiontype'] == '')
							$type = '';
						$slnumber++;
						$i_n++;$slno++;
						$color;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";
						
  						$grid .= '<tr bgcolor = "'.$color.'">';						
						$grid .= '<td width="8%" class="td-border-grid">'.$slnumber.'</td>';
						$grid .= '<td width="14%" id = "m_contacttype'.$j.''.$m.'" class="td-border-grid">'.$type.'</td>';
						$grid .= '<td width="20%" id = "m_contactname'.$j.''.$m.'" class="td-border-grid">'.$fetchres['contactperson'].'</td>';
						$grid .= '<td width="20%" id = "m_contactphone'.$j.''.$m.'" class="td-border-grid">'.$fetchres['phone'].'</td>';
						$grid .= '<td width="15%" id = "m_contactcell'.$j.''.$m.'" class="td-border-grid">'.$fetchres['cell'].'</td>';
						$grid .= '<td width="23%" id = "m_contactemail'.$j.''.$m.'"  class="td-border-grid">'.$fetchres['emailid'].'<input type = "hidden" name = "m_contacthiddenslno'.$j.''.$m.'" id = "m_contacthiddenslno'.$j.''.$m.'" value = "'.$fetchres['slno'].'"></td>';
						
						
						$grid .= '</tr>';
						
					}
					$grid .= '</table>';
					$grid .= '</td>';
  					$grid .= '</tr>';
					$grid .= '</table>';
					$grid .= '</div>';
					$grid .= '</td>';
					$grid .= '</tr>';
					$grid .= '</table>';
					$grid .= '</form></td>';
  					$grid .= '</tr>';
					$grid .= '</table>';
					$grid .= '</td>';
					$grid .= '</tr>';
					$grid .= '</table>';

				
				}
				$responsearray2['count'] = $j;
				$responsearray2['grid'] = $grid;
				echo(json_encode($responsearray2));
				//echo($j.'@#'.$grid);
				break;
				
		case 'mergecustomer':
							$responsearray3 = array();
							$sourcecustomerid = $_POST['sourcecustomerid'];
							$destinationcustid =$_POST['destcustid'];
							//$destdisplay = substr($destinationcustid,15,20);
							$destdisplay = $_POST['destcustid'];
							$businessname =$_POST['businessname'];
							$address = $_POST['address'];
							$place = $_POST['place'];
							$state = $_POST['state'];
							$district = $_POST['district'];
							$pincode = $_POST['pincode'];
							$remarks = $_POST['remarks'];
							$dealer = $_POST['dealer'];
							$stdcode = $_POST['stdcode'];
							$fax = $_POST['fax'];
							$emailid = $_POST['emailid'];
							$website = $_POST['website'];
							$type = $_POST['type'];
							$category = $_POST['category'];
							$region = $_POST['region'];
							$branch = $_POST['branch'];
							$contactarray = $_POST['contactarray'];
							$contactsplit = explode('****',$contactarray);
							$contactcount = count($contactsplit);
							if($contactcount > 1)
							{
								for($i=0;$i<$contactcount;$i++)
								{
									$contactressplit[] = explode('#',$contactsplit[$i]);
								}
							}
							else
							{
								for($i=0;$i<$contactcount;$i++)
								{
									$contactressplit[] = explode('#',$contactsplit[$i]);
								}
							}
							$deletearray = $_POST['deletearray'];
							$sourcedisplay = split(',',$sourcecustomerid);
							
							for($j=0;$j < count($sourcedisplay); $j++)
							{
								
								$query456 = "Select * from inv_mas_customer where slno = '".$sourcedisplay[$j]."';";
								$fetchresult = runmysqlqueryfetch($query456);
								$sourcecustomerid = cusidcombine($fetchresult['customerid']);
								$sourcecusid = $fetchresult['customerid'];
								
								$query45 = "Select * from inv_mas_customer where slno = '".$destdisplay."';";
								$fetchval = runmysqlqueryfetch($query45);
								$destcustomerid = cusidcombine($fetchval['customerid']);
								$destcusid = $fetchval['customerid'];
								
							/*$recordflag1 = recordcheck($sourcedisplay[$j],'customerreference','inv_customerproduct');
								$recordflag2 = recordcheck($sourcedisplay[$j],'customerreference','inv_customeramc');
								$recordflag3 = recordcheck($sourcedisplay[$j],'customerid','inv_customerinteraction');
								$recordflag4 = recordcheck($sourcedisplay[$j],'customerid','inv_customerreqpending');
								$recordflag5 = recordcheck($sourcedisplay[$j],'custreferences','inv_custpaymentreq');
								$recordflag6 = recordcheck($sourcedisplay[$j],'customerreference','inv_dealercard');
								$recordflag7 = recordcheck($sourcedisplay[$j],'customerreference','inv_hardwarelock');
								$recordflag8 = recordcheck($sourcedisplay[$j],'customerid','inv_logs_save');
								$recordflag9 = recordcheck($sourcedisplay[$j],'userreference','inv_logs_sms_delete');
								$recordflag10 = recordcheck($sourcedisplay[$j],'customerref','inv_logs_softkeygen');
								$recordflag11 = recordcheck($sourcedisplay[$j],'userreference','inv_sms_bill');
								$recordflag15 = recordcheck($sourcedisplay[$j],'userreference','inv_smsactivation');
								$recordflag17 = recordcheck($sourcedisplay[$j],'custreference','pre_online_purchase');
								$recordflag18 = recordcheck($sourcedisplay[$j],'customerid','ssm_callregister');
								$recordflag19 = recordcheck($sourcedisplay[$j],'customerid','ssm_emailregister');
								$recordflag20 = recordcheck($sourcedisplay[$j],'customerid','ssm_errorregister');
								$recordflag21 = recordcheck($sourcedisplay[$j],'customerid','ssm_inhouseregister');
								$recordflag22 = recordcheck($sourcedisplay[$j],'customerid','ssm_invoice');
								$recordflag23 = recordcheck($sourcedisplay[$j],'customerid','ssm_onsiteregister');
								$recordflag24 = recordcheck($sourcedisplay[$j],'customerid','ssm_receipts');
								$recordflag25 = recordcheck($sourcedisplay[$j],'customerid','ssm_referenceregister');
								$recordflag26 = recordcheck($sourcedisplay[$j],'customerid','ssm_requirementregister');
								$recordflag27 = recordcheck($sourcedisplay[$j],'customerid','ssm_skyperegister');
								$recordflag28 = recordcheck($sourcedisplay[$j],'customerid','inv_crossproduct');
								$recordflag29 = recordcheck($sourcedisplay[$j],'customerid','inv_crossproductstatus');
								$recordflag30 = recordcheck($sourcedisplay[$j],'customerid','inv_contactreqpending');
								$recordflag31 = recordcheck($sourcedisplay[$j],'customerid','inv_logs_crossproductupdatelogs');
								
								//Invoicing related fields
								$recordflag32 = recordcheck($sourcedisplay[$j],'customerreference','inv_mas_receipt');
								$recordflag33 = recordcheck($customeridfrom,'customerreference','dealer_online_purchase');
								$recordflag34 = recordcheck($frmcustomerid,'customerid','inv_invoicenumbers');
								$recordflag35 = recordcheck($frmcusid,'customerid','inv_logs_webservices');*/
								
								
							$recordflag1 = recordcheck($sourcedisplay[$j],'customerreference','inv_customerproduct');
							$recordflag2 = recordcheck($sourcedisplay[$j],'customerreference','inv_customeramc');
							$recordflag3 = recordcheck($sourcedisplay[$j],'customerid','inv_customerinteraction');
							$recordflag4 = recordcheck($sourcedisplay[$j],'customerid','inv_customerreqpending');
							$recordflag5 = recordcheck($sourcedisplay[$j],'custreferences','inv_custpaymentreq');
							$recordflag6 = recordcheck($sourcedisplay[$j],'customerreference','inv_dealercard');
							$recordflag7 = recordcheck($sourcedisplay[$j],'customerreference','inv_hardwarelock');
							$recordflag8 = recordcheck($sourcedisplay[$j],'customerid','inv_logs_save');
							$recordflag9 = recordcheck($sourcedisplay[$j],'userreference','inv_logs_sms_delete');
							$recordflag10 = recordcheck($sourcedisplay[$j],'customerref','inv_logs_softkeygen');
							$recordflag11 = recordcheck($sourcedisplay[$j],'userreference','inv_sms_bill');
							$recordflag12 = recordcheck($sourcedisplay[$j],'userreference','inv_smsactivation');
							$recordflag13 = recordcheck($sourcedisplay[$j],'custreference','pre_online_purchase');
							$recordflag14 = recordcheck($sourcedisplay[$j],'customerid','ssm_callregister');
							$recordflag15 = recordcheck($sourcedisplay[$j],'customerid','ssm_emailregister');
							$recordflag16 = recordcheck($sourcedisplay[$j],'customerid','ssm_errorregister');
							$recordflag17 = recordcheck($sourcedisplay[$j],'customerid','ssm_inhouseregister');
							$recordflag18 = recordcheck($sourcedisplay[$j],'customerid','ssm_invoice');
							$recordflag19 = recordcheck($sourcedisplay[$j],'customerid','ssm_onsiteregister');
							$recordflag20 = recordcheck($sourcedisplay[$j],'customerid','ssm_receipts');
							$recordflag21 = recordcheck($sourcedisplay[$j],'customerid','ssm_referenceregister');
							$recordflag22 = recordcheck($sourcedisplay[$j],'customerid','ssm_requirementregister');
							$recordflag23 = recordcheck($sourcedisplay[$j],'customerid','ssm_skyperegister');
							$recordflag24 = recordcheck($sourcedisplay[$j],'customerid','inv_crossproduct');
							$recordflag25 = recordcheck($sourcedisplay[$j],'customerid','inv_crossproductstatus');
							$recordflag26 = recordcheck($sourcedisplay[$j],'customerid','inv_contactreqpending');
							$recordflag27 = recordcheck($sourcedisplay[$j],'customerid','inv_logs_crossproductupdatelogs');
							$recordflag28 = recordcheck($sourcedisplay[$j],'customerreference','inv_mas_receipt');
							$recordflag29 = recordcheck($sourcedisplay[$j],'customerreference','dealer_online_purchase');
							$recordflag30 = recordcheck($sourcecustomerid,'customerid','inv_invoicenumbers');
							$recordflag31 = recordcheck($sourcecusid,'customerid','inv_logs_webservices');
							$recordflag32 = recordcheck($sourcedisplay[$j],'customerreference','inv_customeralerts');
							$recordflag33 = recordcheck($sourcecustomerid[$j],'customerid','imp_cfentries');
							$recordflag34 = recordcheck($sourcedisplay[$j],'customerreference','imp_implementation');
							$recordflag35 = recordcheck($sourcedisplay[$j],'customerreference','imp_logs_implementation');
							$recordflag36 = recordcheck($sourcedisplay[$j],'customerreference','imp_rafiles');
							
							/*if($recordflag1 == true)
								{
									$query = "update inv_customerproduct set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag2 == true)
								{
									$query ="update inv_customeramc set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag3 == true)
								{
									$query = "update inv_customerinteraction set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag4 == true)
								{
									$query ="update inv_customerreqpending set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag5 == true)
								{
									$query ="update inv_custpaymentreq set custreferences = '".$destdisplay."' where custreferences = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag6 == true)
								{
									$query ="update inv_dealercard set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag7 == true)
								{
									$query ="update inv_hardwarelock set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag8 == true)
								{
									$query = "update inv_logs_save set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag9 == true)
								{
									$query = "update inv_logs_sms_delete set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag10 == true)
								{
									$query = "update inv_logs_softkeygen set customerref = '".$destdisplay."' where customerref = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag11 == true)
								{
									$query = "update inv_sms_bill set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag15 == true)
								{
									$query = "update inv_smsactivation set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag17 == true)
								{
									$query = "update pre_online_purchase set custreference = '".$destdisplay."' where custreference = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag18 == true)
								{
									$query = "update ssm_callregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag19 == true)
								{
									$query = "update ssm_emailregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag20== true)
								{
									$query = "update ssm_errorregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag21== true)
								{
									$query = "update ssm_inhouseregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag22== true)
								{
									$query = "update ssm_invoice set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag23== true)
								{
									$query = "update ssm_onsiteregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag24== true)
								{
									$query = "update ssm_receipts set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}if($recordflag25== true)
								{
									$query = "update ssm_referenceregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag26== true)
								{
									$query = "update ssm_requirementregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag27== true)
								{
									$query = "update ssm_skyperegister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag28== true)
								{
									$query = "update inv_crossproduct set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								
								if($recordflag29== true)
								{
									$query = "update inv_crossproductstatus set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								
								if($recordflag30== true)
								{
									$query = "update inv_contactreqpending set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								if($recordflag31== true)
								{
									$query = "update inv_logs_crossproductupdatelogs set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
									$result = runmysqlquery($query);
								}
								//Invoicing related fields
								if($recordflag32== true)
								{
									$query = "update inv_mas_receipt set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
									$result = runmysqlquery($query);
								}
								if($recordflag33== true)
								{
									$query = "update dealer_online_purchase set customerreference = '".$customeridto."' where customerreference = '".$customeridfrom."';";
									$result = runmysqlquery($query);
								}
								if($recordflag34== true)
								{
									$query = "update inv_invoicenumbers set customerid = '".$tocustomerid."' where customerid = '".$frmcustomerid."';";
									$result = runmysqlquery($query);
									
								}
								if($recordflag35== true)
								{
									$query = "update inv_logs_webservices set customerid = '".$tocusid."' where customerid = '".$frmcusid."';";
									$result = runmysqlquery($query);
			}*/
			
							if($recordflag1 == true)
							{
								$query11 = "update inv_customerproduct set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result11 = runmysqlquery($query11);
							}
							if($recordflag2 == true)
							{
								$query12 ="update inv_customeramc set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result12 = runmysqlquery($query12);
							}
							if($recordflag3 == true)
							{
								$query13 = "update inv_customerinteraction set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result13 = runmysqlquery($query13);
							}
							if($recordflag4 == true)
							{
								$query14 ="update inv_customerreqpending set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result14 = runmysqlquery($query14);
							}
							if($recordflag5 == true)
							{
								$query15 ="update inv_custpaymentreq set custreferences = '".$destdisplay."' where custreferences = '".$sourcedisplay[$j]."';";
								$result15 = runmysqlquery($query15);
							}
							if($recordflag6 == true)
							{
								$query16 ="update inv_dealercard set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result16 = runmysqlquery($query16);
							}
							if($recordflag7 == true)
							{
								$query17 ="update inv_hardwarelock set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result17 = runmysqlquery($query17);
							}
							if($recordflag8 == true)
							{
								$query18 = "update inv_logs_save set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result18 = runmysqlquery($query18);
							}
							if($recordflag9 == true)
							{
								$query19 = "update inv_logs_sms_delete set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
								$result19 = runmysqlquery($query19);
							}
							if($recordflag10 == true)
							{
								$query20 = "update inv_logs_softkeygen set customerref = '".$destdisplay."' where customerref = '".$sourcedisplay[$j]."';";
								$result20 = runmysqlquery($query20);
							}
							if($recordflag11 == true)
							{
								$query21 = "update inv_sms_bill set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
								$result21 = runmysqlquery($query21);
							}
							if($recordflag12 == true)
							{
								$query22 = "update inv_smsactivation set userreference = '".$destdisplay."' where userreference = '".$sourcedisplay[$j]."';";
								$result22 = runmysqlquery($query22);
							}
							if($recordflag13 == true)
							{
								$query23 = "update pre_online_purchase set custreference = '".$destdisplay."' where custreference = '".$sourcedisplay[$j]."';";
								$result23 = runmysqlquery($query23);
							}
							if($recordflag14 == true)
							{
								$query24 = "update ssm_callregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result24 = runmysqlquery($query24);
							}
							if($recordflag15 == true)
							{
								$query25 = "update ssm_emailregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result25 = runmysqlquery($query25);
							}
							if($recordflag16== true)
							{
								$query26 = "update ssm_errorregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result26 = runmysqlquery($query26);
							}
							if($recordflag17== true)
							{
								$query27 = "update ssm_inhouseregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result27 = runmysqlquery($query27);
							}
							if($recordflag18== true)
							{
								$query28 = "update ssm_invoice set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result28 = runmysqlquery($query28);
							}
							if($recordflag19== true)
							{
								$query29 = "update ssm_onsiteregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result29 = runmysqlquery($query29);
							}
							if($recordflag20== true)
							{
								$query30 = "update ssm_receipts set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result30 = runmysqlquery($query30);
							}
							if($recordflag21== true)
							{
								$query31 = "update ssm_referenceregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result31 = runmysqlquery($query31);
							}
							if($recordflag22== true)
							{
								$query32 = "update ssm_requirementregister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result32 = runmysqlquery($query32);
							}
							if($recordflag23== true)
							{
								$query33 = "update ssm_skyperegister set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result33 = runmysqlquery($query33);
							}
							if($recordflag24== true)
							{
								$query34 = "update inv_crossproduct set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result34 = runmysqlquery($query34);
							}
							
							if($recordflag25== true)
							{
								$query35 = "update inv_crossproductstatus set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result35 = runmysqlquery($query35);
							}
							if($recordflag26== true)
							{
								$query36 = "update inv_contactreqpending set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result36 = runmysqlquery($query36);
							}
							
							if($recordflag27== true)
							{
								$query37 = "update inv_logs_crossproductupdatelogs set customerid = '".$destdisplay."' where customerid = '".$sourcedisplay[$j]."';";
								$result37 = runmysqlquery($query37);
							}
							if($recordflag28== true)
							{
								$query28 = "update inv_mas_receipt set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result28 = runmysqlquery($query28);
							}
							if($recordflag29== true)
							{
								
								$query39 = "update dealer_online_purchase set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result39 = runmysqlquery($query39);
							}
							if($recordflag30== true)
							{
							
								$query40 = "update inv_invoicenumbers set customerid = '".$destcustomerid."' where customerid = '".$sourcecustomerid."';";
								$result40 = runmysqlquery($query40);
							}
							if($recordflag31== true)
							{
								$query41 = "update inv_logs_webservices set customerid = '".$destcusid."' where customerid = '".$sourcecusid."';";
								$result41 = runmysqlquery($query41);
							}
							if($recordflag32== true)
							{
								
								$query42 = "update inv_customeralerts set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result42 = runmysqlquery($query42);
							}
							if($recordflag33== true)
							{
								
								$query43 = "update imp_cfentries set customerid = '".$destcustomerid."' where customerid = '".$sourcecustomerid."';";
								$result43 = runmysqlquery($query43);
							}
							if($recordflag34== true)
							{
								
								$query44 = "update imp_implementation set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result44 = runmysqlquery($query44);
							}
							if($recordflag35== true)
							{
								
								$query45 = "update imp_logs_implementation set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result45 = runmysqlquery($query45);
							}
							if($recordflag36== true)
							{
								
								$query46 = "update imp_rafiles set customerreference = '".$destdisplay."' where customerreference = '".$sourcedisplay[$j]."';";
								$result46 = runmysqlquery($query46);
							}
								// Insert the merge details to logs
								$query1 ="SELECT customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$sourcedisplay[$j]."'; ";
								$resultfetch = runmysqlquery($query1);
								$valuecount = 0;
								while($fetchres = mysqli_fetch_array($resultfetch))
								{
									if($valuecount > 0)
										$fromcontactarray .= '*';
									
									$selectiontype = $fetchres['selectiontype'];
									$contactperson = $fetchres['contactperson'];
									$phone = $fetchres['phone'];
									$cell = $fetchres['cell'];
									$emailid = $fetchres['emailid'];
									
									$fromcontactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid;
									$valuecount++;
								}
								$query2 ="SELECT * FROM inv_mas_customer WHERE slno='".$sourcedisplay[$j]."'";
								$fetch = runmysqlqueryfetch($query2);
								
								$query3 = "Insert into inv_logs_customermerge(fromcompany,fromaddress, fromplace,frompincode,fromdistrict,fromregion,fromcategory,fromtype,fromstdcode,fromwebsite,fromcurrentdealer,fromfax,frombranch,mergeto,mergedby,mergedatetime,mergefrom,mergetype,fromcontactperson,fromremarks) values ('".$fetch['businessname']."','".addslashes($fetch['address'])."','".$fetch['place']."','".$fetch['pincode']."','".$fetch['district']."','".$fetch['region']."','".$fetch['category']."','".$fetch['type']."','".$fetch['stdcode']."','".$fetch['website']."','".$fetch['currentdealer']."','".$fetch['fax']."','".$fetch['branch']."','".$destdisplay."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$sourcedisplay[$j]."','Merge Suggestion','".$fromcontactarray."','".$fetch['remarks']."');"; 
								$result = runmysqlquery($query3);
								
								$query1 = "UPDATE inv_mas_customer SET businessname = '".trim($businessname)."',address = '".addslashes($address)."',place = '".$place."',pincode = '".$pincode."',district = '".$district."',region = '".$region."',category = '".$category."',type = '".$type."',stdcode = '".$stdcode."',website = '".$website."',currentdealer = '".$dealer."',fax ='".$fax."',branch = '".$branch."',remarks='".$remarks."' WHERE slno = '".$destdisplay."'";
								$result = runmysqlquery($query1);
								
								// Delete source 
								$querydel = "delete from inv_mas_customer where slno = '".$sourcedisplay[$j]."'";
								$result = runmysqlquery($querydel);
								
								// Delete the contact details that are not required.								
								$totalsplit = explode(',',$deletearray);
								for($i=0;$i<count($totalsplit);$i++)
								{
									$deleteslno = $totalsplit[$i];
									$query22 = "DELETE FROM inv_contactdetails WHERE slno = '".$deleteslno."'";
									$result = runmysqlquery($query22);
								}
								// Insert contact Details
								for($j=0;$j<count($contactressplit);$j++)
								{
									$selectiontype = $contactressplit[$j][0];
									$contactperson = trim($contactressplit[$j][1]);
									$phone = trim($contactressplit[$j][2]);
									$cell = trim($contactressplit[$j][3]);
									$emailid = trim($contactressplit[$j][4]);
									$slno = trim($contactressplit[$j][5]);

									if($slno <> '')
									{
										$queryuu = "UPDATE inv_contactdetails SET contactperson = '".$contactperson."',phone = '".$phone."',cell = '".$cell."',emailid = '".$emailid."',selectiontype = '".$selectiontype."',customerid = '".$destdisplay."' WHERE slno = '".$slno."'";
										$result = runmysqlquery($queryuu);
									}
									else
									{
										$queryii = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$destdisplay."','".$selectiontype."','".$contactperson."','".$phone."','".$cell."','".$emailid."');";
										$result = runmysqlquery($queryii);
									}
								}
							}
							
							
							$responsearray3['errorcount'] = "1";
							$responsearray3['errormsg'] = "Customer merged Successfully.";
							echo(json_encode($responsearray3));
						//	echo('1^'." Customer merged Successfully.");
							break;
						 
}


function recordcheck($fieldvalue,$fieldname,$tablename)
{
	$flag = false;
	$query = "SELECT COUNT(*) AS count FROM ".$tablename." WHERE ".$fieldname." = '".$fieldvalue."'";
	$fetch = runmysqlqueryfetch($query);
	if($fetch['count'] == 0)
	{
		$flag = false;
	}
	else
	{
		$flag = true;
	}
	return $flag ;
}


?>
