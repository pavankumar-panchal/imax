<?
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
include('../inc/checksession.php');
include('../inc/checkpermission.php');

if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo('Thinking to redirect');
	exit;
}
include('../inc/checkpermission.php');

$changetype = $_POST['changetype'];
$lastslno = $_POST['lastslno'];

switch($changetype)
{
	case 'customergenerategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$query ="SELECT distinct count(*) as count from inv_customerreqpending where inv_customerreqpending.requestfrom = 'customer_module' and inv_customerreqpending.customerstatus='pending' ";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$query2 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.contactperson as contactperson,
inv_customerreqpending.address as address,inv_customerreqpending.place as place,
inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,
inv_mas_customercategory.businesstype as category,inv_customerreqpending.phone as phone,
inv_customerreqpending.cell as cell,inv_customerreqpending.fax as fax,inv_customerreqpending.emailid as emailid,
inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as `type`,
inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,
inv_customerreqpending.remarks as remarks,inv_customerreqpending.gst_no as gst_no FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'customer_module' order by createddate";
			$fetch6 = runmysqlquery($query2);
			$fetchresultcount = mysqli_num_rows($fetch6);
			if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slno = 0;
			}
			else
			{
				$startlimit = $slno ;
				$slno = $slno;
			}
			$query1 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.contactperson as contactperson,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.phone as phone,inv_customerreqpending.cell as cell,inv_customerreqpending.fax as fax,inv_customerreqpending.emailid as emailid,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks, inv_customerreqpending.gst_no as gst_no FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN 
inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'customer_module' order by createddate LIMIT ".$startlimit.",".$limit.";";

			
			if($startlimit == 0)
			{		
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Address</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Cell</td><td nowrap = "nowrap" class="td-border-grid">Fax</td><td nowrap = "nowrap" class="td-border-grid">Place</td><td nowrap = "nowrap" class="td-border-grid">District</td><td nowrap = "nowrap" class="td-border-grid">Pincode</td><td nowrap = "nowrap" class="td-border-grid">STD Code</td><td nowrap = "nowrap" class="td-border-grid">State</td><td nowrap = "nowrap" class="td-border-grid">Category</td><td nowrap = "nowrap" class="td-border-grid">Email ID</td><td nowrap = "nowrap" class="td-border-grid">Website</td><td nowrap = "nowrap" class="td-border-grid">Type</td><td nowrap = "nowrap" class="td-border-grid">Request date/time</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td><td nowrap = "nowrap" class="td-border-grid">GSTIN</td></tr>';
			}
			$result = runmysqlquery($query1);
			$i_n = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$contactvalues = '';$phoneres = '';$cellres = '';$emailidres = '';
				$query1 ="SELECT refslno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['slno']."'; ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno1 = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno1;
					$valuecount++;
					$contactvalues .= $contactperson;
					$contactvalues .= appendcomma($contactperson);
					$phoneres .= $phone;
					$phoneres .= appendcomma($phone);
					$cellres .= $cell;
					$cellres .= appendcomma($cell);
					$emailidres .= $emailid;
					$emailidres .= appendcomma($emailid);
					
				}
				$rescontact = trim($contactvalues,',');
				$resphone = trim($phoneres,',');
				$rescell = trim($cellres,',');
				$resemailid = trim($emailidres,',');
				$slno++;
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['slno'].'\',\''.$fetch['customerid'].'\',\''.$fetch['requestfrom'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['customerid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td><td nowrap='nowrap' class='td-border-grid'>".$rescontact."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['address']."</td><td nowrap='nowrap' class='td-border-grid'>".$resphone."</td><td nowrap='nowrap' class='td-border-grid'>".$rescell."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['fax']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['place']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['district']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['pincode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['state']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['category']."</td><td nowrap='nowrap' class='td-border-grid'>".$resemailid."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['website']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['type']."</td><td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['gst_no']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";

			$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustdetails(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorecustdetails(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
		}
		else
		{
			echo('2^No datas found to be displayed.');
		}

	}
	break;
	
	case 'dealergenerategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$query = "SELECT distinct count(*) as count from inv_customerreqpending where inv_customerreqpending.requestfrom = 'dealer_module'  and inv_customerreqpending.customerstatus='pending'"; 
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$query2 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.fax as fax,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks,inv_customerreqpending.companyclosed as companyclosed FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN 
inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending' and inv_customerreqpending.requestfrom = 'dealer_module' order by createddate";
			$fetch6 = runmysqlquery($query2);
			$fetchresultcount = mysqli_num_rows($fetch6);
			if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slno = 0;
			}
			else
			{
				$startlimit = $slno;
				$slno = $slno;
			}
				$query1 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks ,inv_customerreqpending.companyclosed as companyclosed FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN 
inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending' and inv_customerreqpending.requestfrom = 'dealer_module' order by createddate LIMIT ".$startlimit.",".$limit.";";
			if($startlimit == 0)
			{	
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Address</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Cell</td><td nowrap = "nowrap" class="td-border-grid">Fax</td><td nowrap = "nowrap" class="td-border-grid">Place</td><td nowrap = "nowrap" class="td-border-grid">District</td><td nowrap = "nowrap" class="td-border-grid">Pincode</td><td nowrap = "nowrap" class="td-border-grid">STD Code</td><td nowrap = "nowrap" class="td-border-grid">State</td><td nowrap = "nowrap" class="td-border-grid">Category</td><td nowrap = "nowrap" class="td-border-grid">Email ID</td><td nowrap = "nowrap" class="td-border-grid">Website</td><td nowrap = "nowrap" class="td-border-grid">Type</td><td nowrap = "nowrap" class="td-border-grid">Request date/time</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
			}
			$result = runmysqlquery($query1);
			while($fetch = mysqli_fetch_array($result))
			{
				$contactvalues = '';$phoneres = '';$cellres = '';$emailidres = '';
				$query1 ="SELECT refslno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['slno']."'; ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno1 = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno1;
					$valuecount++;
					$contactvalues .= $contactperson;
					$contactvalues .= appendcomma($contactperson);
					$phoneres .= $phone;
					$phoneres .= appendcomma($phone);
					$cellres .= $cell;
					$cellres .= appendcomma($cell);
					$emailidres .= $emailid;
					$emailidres .= appendcomma($emailid);
				}
				$rescontact = trim($contactvalues,',');
				$resphone = trim($phoneres,',');
				$rescell = trim($cellres,',');
				$resemailid = trim($emailidres,',');
				$i_n++;
				$slno++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['slno'].'\',\''.$fetch['customerid'].'\',\''.$fetch['requestfrom'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['customerid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td><td nowrap='nowrap' class='td-border-grid'>".$rescontact."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['address']."</td><td nowrap='nowrap' class='td-border-grid'>".$resphone."</td><td nowrap='nowrap' class='td-border-grid'>".$rescell."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['fax']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['place']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['district']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['pincode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['state']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['category']."</td><td nowrap='nowrap' class='td-border-grid'>".$resemailid."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['website']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['type']."</td><td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoredealdetails(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >>&nbsp;&nbsp;&nbsp;</a><a onclick ="getmoredealdetails(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
		}
		else
		{
			echo('2^No datas found to be displayed.');
		}
		
	}
	break;
	
	case 'webgenerategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$query = "SELECT distinct count(*) as count from inv_customerreqpending where inv_customerreqpending.requestfrom = 'web_module'  and inv_customerreqpending.customerstatus='pending'"; 
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{	
			$query2 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks  FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'web_module' order by createddate";
			$fetch6 = runmysqlquery($query2);
			$fetchresultcount = mysqli_num_rows($fetch6);
			if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slno = 0;
			}
			else
			{
				$startlimit = $slno;
				$slno = $slno;
			}
		$query1 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks  FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'web_module' order by createddate LIMIT ".$startlimit.",".$limit.";";
			if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Address</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Cell</td><td nowrap = "nowrap" class="td-border-grid">Fax</td><td nowrap = "nowrap" class="td-border-grid">Place</td><td nowrap = "nowrap" class="td-border-grid">District</td><td nowrap = "nowrap" class="td-border-grid">Pincode</td><td nowrap = "nowrap" class="td-border-grid">STD Code</td><td nowrap = "nowrap" class="td-border-grid">State</td><td nowrap = "nowrap" class="td-border-grid">Category</td><td nowrap = "nowrap" class="td-border-grid">Email ID</td><td nowrap = "nowrap" class="td-border-grid">Website</td><td nowrap = "nowrap" class="td-border-grid">Type</td><td nowrap = "nowrap" class="td-border-grid">Request date/time</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
			}
			$result = runmysqlquery($query1);
			while($fetch = mysqli_fetch_array($result))
			{
				$contactvalues = '';$phoneres = '';$cellres = '';$emailidres = '';
				$query1 ="SELECT refslno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['slno']."'; ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno1 = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno1;
					$valuecount++;
					$contactvalues .= $contactperson;
					$contactvalues .= appendcomma($contactperson);
					$phoneres .= $phone;
					$phoneres .= appendcomma($phone);
					$cellres .= $cell;
					$cellres .= appendcomma($cell);
					$emailidres .= $emailid;
					$emailidres .= appendcomma($emailid);
					
				}
				$rescontact = trim($contactvalues,',');
				$resphone = trim($phoneres,',');
				$rescell = trim($cellres,',');
				$resemailid = trim($emailidres,',');
								
				$slno++;
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['slno'].'\',\''.$fetch['customerid'].'\',\''.$fetch['requestfrom'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['customerid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td><td nowrap='nowrap' class='td-border-grid'>".$rescontact."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['address']."</td><td nowrap='nowrap' class='td-border-grid'>".$resphone."</td><td nowrap='nowrap' class='td-border-grid'>".$rescell."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['fax']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['place']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['district']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['pincode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['state']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['category']."</td><td nowrap='nowrap' class='td-border-grid'>".$resemailid."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['website']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['type']."</td><td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
			
				$grid .= '</tr>';
			}
			$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmorewebdetails(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmorewebdetails(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
		}
		else
		{
			echo('2^No datas found to be displayed.');
		}
	}
	break;
	
	case 'supportgenerategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$query ="SELECT distinct count(*) as count from inv_customerreqpending where inv_customerreqpending.requestfrom = 'support_module' and inv_customerreqpending.customerstatus='pending' ";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$query2 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.fax as fax,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks,inv_customerreqpending.companyclosed as companyclosed FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'support_module' order by createddate";
			$fetch6 = runmysqlquery($query2);
			$fetchresultcount = mysqli_num_rows($fetch6);
			if($showtype == 'all')
			$limit = 100000;
			else
			$limit = 10;
			if($startlimit == '')
			{
				$startlimit = 0;
				$slno = 0;
			}
			else
			{
				$startlimit = $slno ;
				$slno = $slno;
			}
			$query1 = "SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.customerid,
inv_customerreqpending.businessname as businessname,inv_customerreqpending.address as address,
inv_customerreqpending.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_customerreqpending.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_customerreqpending.website as website,inv_customerreqpending.stdcode as stdcode,inv_mas_customertype.customertype as type,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.remarks as remarks,inv_customerreqpending.companyclosed as companyclosed FROM inv_customerreqpending LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district LEFT JOIN 
inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
where inv_customerreqpending.customerstatus = 'pending'  and inv_customerreqpending.requestfrom = 'support_module' order by createddate LIMIT ".$startlimit.",".$limit.";";
			if($startlimit == 0)
			{		
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Address</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Cell</td><td nowrap = "nowrap" class="td-border-grid">Fax</td><td nowrap = "nowrap" class="td-border-grid">Place</td><td nowrap = "nowrap" class="td-border-grid">District</td><td nowrap = "nowrap" class="td-border-grid">Pincode</td><td nowrap = "nowrap" class="td-border-grid">STD Code</td><td nowrap = "nowrap" class="td-border-grid">State</td><td nowrap = "nowrap" class="td-border-grid">Category</td><td nowrap = "nowrap" class="td-border-grid">Email ID</td><td nowrap = "nowrap" class="td-border-grid">Website</td><td nowrap = "nowrap" class="td-border-grid">Type</td><td nowrap = "nowrap" class="td-border-grid">Request date/time</td><td nowrap = "nowrap" class="td-border-grid">Remarks</td></tr>';
			}
			$result = runmysqlquery($query1);
			$i_n = 0;
			while($fetch = mysqli_fetch_array($result))
			{
				$contactvalues = '';$phoneres = '';$cellres = '';$emailidres = '';
				$query1 ="SELECT refslno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where refslno = '".$fetch['slno']."'; ";
				$resultfetch = runmysqlquery($query1);
				$valuecount = 0;
				while($fetchres = mysqli_fetch_array($resultfetch))
				{
					if($valuecount > 0)
						$contactarray .= '****';
					$selectiontype = $fetchres['selectiontype'];
					$contactperson = $fetchres['contactperson'];
					$phone = $fetchres['phone'];
					$cell = $fetchres['cell'];
					$emailid = $fetchres['emailid'];
					$slno1 = $fetchres['slno'];
					
					$contactarray .= $selectiontype.'#'.$contactperson.'#'.$phone.'#'.$cell.'#'.$emailid.'#'.$slno1;
					$valuecount++;
					$contactvalues .= $contactperson;
					$contactvalues .= appendcomma($contactperson);
					$phoneres .= $phone;
					$phoneres .= appendcomma($phone);
					$cellres .= $cell;
					$cellres .= appendcomma($cell);
					$emailidres .= $emailid;
					$emailidres .= appendcomma($emailid);
					
				}
				$rescontact = trim($contactvalues,',');
				$resphone = trim($phoneres,',');
				$rescell = trim($cellres,',');
				$resemailid = trim($emailidres,',');
								
				$slno++;
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				
				$grid .= '<tr class="gridrow" onclick ="productdetailstoform(\''.$fetch['slno'].'\',\''.$fetch['customerid'].'\',\''.$fetch['requestfrom'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$slno."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['customerid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['businessname']."</td><td nowrap='nowrap' class='td-border-grid'>".$rescontact."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['address']."</td><td nowrap='nowrap' class='td-border-grid'>".$resphone."</td><td nowrap='nowrap' class='td-border-grid'>".$rescell."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['fax']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['place']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['district']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['pincode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['stdcode']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['state']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['category']."</td><td nowrap='nowrap' class='td-border-grid'>".$resemailid."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['website']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['type']."</td><td nowrap='nowrap' class='td-border-grid'>".changedateformatwithtime($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['remarks']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result);
			if($slno >= $fetchresultcount)
				$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
				$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px">&nbsp;&nbsp;&nbsp;<a onclick ="getmoresuppdetails(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoresuppdetails(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
		}
		else
		{
			echo('2^No datas found to be displayed.');
		}

	}
	break;
	
	case 'customerdetailstoform':
	{
		$lastupdateslno = $_POST['lastupdateslno'];
		$requestfrom = $_POST['requestfrom'];
		$query1 = "SELECT count(*) as count from inv_customerreqpending where customerid = '".$lastslno."' and inv_customerreqpending.customerstatus ='pending'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0) 		
		{
			$query = "select tempcus.slno as tempslno,tempcus.customerid as tempcustomerid,tempcus.businessname as tempbusinessname,tempcus.dealerbusinessname as tempdealerbusinessname,tempcus.customerstatus as tempcustomerstatus,
			tempcus.address as tempaddress,tempcus.place as tempplace,tempcus.businesstype 
			as tempbusinesstype,tempcus.customertype as tempcustomertype,tempcus.district as tempdistrict,tempcus.fax as tempfax,
			tempcus.districtname as tempdistrictname,tempcus.statename as tempstatename,tempcus.promotionalsms as temppromotionalsms,tempcus.promotionalemail as temppromotionalemail,tempcus.state as tempstate,tempcus.pincode as temppincode,tempcus.category as tempcategory,tempcus.website as tempwebsite,tempcus.requestby as temprequestby,tempcus.remarks as tempremarks,tempcus.companyclosed as tempcompanyclosed,
			tempcus.stdcode as tempstdcode,tempcus.`type` as temptype,tempcus.requestfrom as requestfrom,
			tempcus.createddate as tempcreateddate,tempcus.contact_gst_no as tempgst_no,tempcus.tanno as temptanno,newcus.slno as newslno,newcus.customer_gst_no as gst_no,newcus.customerid as customerid,newcus.fax as fax,
			newcus.businessname as businessname,newcus.dealerbusinessname as dealerbusinessname,
			newcus.address as address,newcus.place as place,newcus.district as district,
			newcus.districtname as districtname,newcus.statename as statename,newcus.state as state,newcus.pincode as pincode,
			newcus.category as category,newcus.fax as fax,newcus.remarks as remarks,
			newcus.website as website,newcus.companyclosed as companyclosed,newcus.tanno as tanno,
			newcus.stdcode as stdcode,newcus.type as `type`,newcus.promotionalsms as promotionalsms,newcus.promotionalemail as promotionalemail from (SELECT distinct inv_customerreqpending.slno as slno,inv_customerreqpending.gst_no as contact_gst_no,inv_customerreqpending.customerid,inv_mas_dealer.businessname as dealerbusinessname,inv_customerreqpending.businessname as businessname, inv_customerreqpending.customerstatus as customerstatus,inv_customerreqpending.address as address,inv_customerreqpending.place as 
			place,inv_mas_district.districtcode as district,inv_mas_state.statecode as state,inv_customerreqpending.pincode as pincode,inv_customerreqpending.category as category,inv_mas_dealer.businessname as dealbusinessname,
			inv_customerreqpending.fax as fax, inv_customerreqpending.promotionalsms as promotionalsms, inv_customerreqpending.promotionalemail as promotionalemail, inv_customerreqpending.website as website ,ssm_users.username as requestby,inv_customerreqpending.remarks as remarks ,inv_customerreqpending.stdcode as stdcode,inv_customerreqpending.type as `type`,inv_mas_district.districtname,inv_mas_customercategory.businesstype,inv_mas_customertype.customertype,
			inv_mas_state.statename,inv_customerreqpending.requestfrom,inv_customerreqpending.createddate as createddate,inv_customerreqpending.companyclosed as companyclosed,inv_customerreqpending.tanno as tanno FROM inv_customerreqpending 
			LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_customerreqpending.district 
			LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode
			LEFT JOIN inv_mas_customer on inv_mas_customer.slno =  inv_customerreqpending.customerid 
			LEFT JOIN inv_mas_dealer on  inv_customerreqpending.requestby = inv_mas_dealer.slno
			LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_customerreqpending.type 
			LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_customerreqpending.category
			left join ssm_users on ssm_users.slno = inv_customerreqpending.requestby
			where inv_customerreqpending.customerid = '".$lastslno."' and requestfrom='".$requestfrom."' and customerstatus='pending')as tempcus
			join(SELECT distinct inv_mas_customer.slno as slno,inv_mas_customer.customerid,inv_mas_customer.businessname as businessname,
			inv_mas_dealer.businessname as dealerbusinessname,inv_mas_customer.address as address,inv_mas_customer.promotionalsms as promotionalsms,inv_mas_customer.promotionalemail as promotionalemail,
			inv_mas_customer.place as place,inv_mas_customer.gst_no as customer_gst_no,inv_mas_customer.district as district,inv_mas_state.statecode as state,
			inv_mas_customer.pincode as pincode,inv_mas_customercategory.businesstype as category,
			inv_mas_customertype.customertype as `type`,inv_mas_customer.remarks as remarks,
			inv_mas_customer.fax as fax,inv_mas_customer.companyclosed as companyclosed,inv_mas_customer.website as website,inv_mas_customer.stdcode as stdcode,
			inv_mas_district.districtname,inv_mas_state.statename as statename,inv_mas_customer.gst_no as gst_no,inv_mas_customer.tanno as tanno FROM inv_mas_customer 
			LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_customer.district 
			LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
			LEFT JOIN inv_mas_dealer on  inv_mas_customer.currentdealer = inv_mas_dealer.slno 
			LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_mas_customer.type 
			LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category
			where inv_mas_customer.slno = '".$lastslno."') as  newcus";
			$fetch = runmysqlqueryfetch($query);
			
			$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."'";
			$resultfetch = runmysqlquery($query1);
			$newvaluecount = 0;
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				if($newvaluecount > 0)
					$newcontactarray .= '****';
				$newselectiontype = $fetchres['selectiontype'];
				$newcontactperson = $fetchres['contactperson'];
				$newphone = $fetchres['phone'];
				$newcell = $fetchres['cell'];
				$newemailid = $fetchres['emailid'];
				$newslno = $fetchres['slno'];
				
				$newcontactarray .= $newselectiontype.'#'.$newcontactperson.'#'.$newphone.'#'.$newcell.'#'.$newemailid.'#'.$newslno;
				$newvaluecount++;
				
			}
			
			$query11 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where customerid = '".$lastslno."'  and requestfrom='".$requestfrom."' and customerstatus='pending' and editedtype = 'edit_type' and refslno = '".$fetch['tempslno']."'";
			$resultfetch1 = runmysqlquery($query11);
			$tempvaluecount = 0;
			while($fetchres1 = mysqli_fetch_array($resultfetch1))
			{
				if($tempvaluecount > 0)
					$tempcontactarray .= '****';
				$tempselectiontype = $fetchres1['selectiontype'];
				$tempcontactperson = $fetchres1['contactperson'];
				$tempphone = $fetchres1['phone'];
				$tempcell = $fetchres1['cell'];
				$tempemailid = $fetchres1['emailid'];
				$tempslno = $fetchres1['slno'];
				
				$tempcontactarray .= $tempselectiontype.'#'.$tempcontactperson.'#'.$tempphone.'#'.$tempcell.'#'.$tempemailid.'#'.$tempslno;
				$tempvaluecount++;
				
				
			}
			$countquery = "SELECT COUNT(*) as count FROM inv_contactreqpending where customerid = '".$lastslno."'  and requestfrom='".$requestfrom."' and customerstatus='pending' and editedtype = 'delete_type' and refslno = '".$fetch['tempslno']."';";
			$countfetch = runmysqlqueryfetch($countquery);
			if($countfetch['count'] > 0)
			{
				$query19 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactreqpending where customerid = '".$lastslno."'  and requestfrom='".$requestfrom."' and customerstatus='pending' and editedtype = 'delete_type' and refslno = '".$fetch['tempslno']."'";
				$resultfetch2 = runmysqlquery($query19);
				$tempdelvaluecount = 0;
				while($fetchres2 = mysqli_fetch_array($resultfetch2))
				{
					if($tempdelvaluecount > 0)
						$tempdelecontactarray .= '****';
					$tempdelselectiontype = $fetchres2['selectiontype'];
					$tempdelcontactperson = $fetchres2['contactperson'];
					$tempdelphone = $fetchres2['phone'];
					$tempdelcell = $fetchres2['cell'];
					$tempdelemailid = $fetchres2['emailid'];
					$tempdelslno = $fetchres2['slno'];
					
					$tempdelecontactarray .= $tempdelselectiontype.'#'.$tempdelcontactperson.'#'.$tempdelphone.'#'.$tempdelcell.'#'.$tempdelemailid.'#'.$tempdelslno;
					$tempdelvaluecount++;
					
				}
			}

			$oldbusquery = "select oldbusinessname from inv_mas_customer where slno = '".$lastslno."'";
			$oldbusresult = runmysqlquery($oldbusquery);
			$oldbuscount = mysqli_num_rows($oldbusresult);
			if($oldbuscount > 0)
			{
				$fetcholdbus = runmysqlqueryfetch($oldbusquery);
				$oldbusinessname = $fetcholdbus['oldbusinessname'];
			}

			if(!empty($fetch['gst_no']))
			{
				if(is_numeric($fetch['gst_no']))
	        	{
	        		//$gst_customer = $fetch['gst_no'];
	        		$get_gst_first_no = "select gst_no from customer_gstin_logs where gstin_id = '".$fetch['gst_no']."' ";
                    $fetch_first_gst_no = runmysqlqueryfetch($get_gst_first_no);
                    $gst_no = $fetch_first_gst_no['gst_no'];
	        	}
	        	else if($fetch['gst_no']!= $fetch['tempgst_no'])
	        	{
   					$gst_no = $fetch['gst_no'];
	        	}
	        	else
	        	{
	        		$get_gst_latest_no = "select gst_no from customer_gstin_logs where customer_slno = '".$lastslno."' order by gstin_id desc limit 1";
	        		$get_gst_result = runmysqlquery($get_gst_latest_no);
	        		$get_gst_count = mysql_num_rows($get_gst_result);
	        		if($get_gst_count > 0)
	        		{
	        			$get_gst_latest_no = runmysqlqueryfetch($get_gst_latest_no);
                    	$gst_no = $get_gst_latest_no['gst_no'];
	        		}
	        		else
	        			$gst_no = $fetch['gst_no'];

	        	}
			}

			$requestfrom = $fetch['requestfrom'];
			$createddate = changedateformatwithtime($fetch['tempcreateddate']);
			if($fetch['tempcompanyclosed'] == '')
				$tempcompanyclosed = 'no';
			else
				$tempcompanyclosed = $fetch['tempcompanyclosed'];
				
			if($requestfrom == 'customer_module' || $requestfrom == 'web_module' )
			{
				echo($fetch['tempslno'].'^'.$fetch['tempcustomerid'].'^'.$fetch['tempbusinessname'].'^'.$fetch['tempbusinessname'].'^'.$fetch['tempaddress'].'^'.$fetch['tempplace'].'^'.$fetch['tempbusinesstype'].'^'.$fetch['tempcustomertype'].'^'.$fetch['tempdistrictname'].'^'.$fetch['tempstatename'].'^'.$fetch['temppincode'].'^'.$fetch['tempcategory'].'^'.$fetch['tempwebsite'].'^'.$fetch['tempremarks'].'^'.$tempcompanyclosed.'^'.$fetch['tempstdcode'].'^'.$fetch['temptype'].'^'.$fetch['tempfax'].'^'.$fetch['tempstate'].'^'.$fetch['tempdistrict'].'^'.$createddate.'^'.$fetch['newslno'].'^'.$fetch['customerid'].'^'.$fetch['businessname'].'^'.$fetch['dealerbusinessname'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['pincode'].'^'.$fetch['website'].'^'.$fetch['companyclosed'].'^'.$fetch['stdcode'].'^'.$fetch['type'].'^'.$fetch['fax'].'^'.$fetch['category'].'^'.$fetch['remarks'].'^'.$tempcontactarray.'^'.$newcontactarray.'^'.$fetch['temppromotionalsms'].'^'.$fetch['temppromotionalemail'].'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$fetch['tempgst_no'].'^'.$gst_no.'^'.$tempdelecontactarray.'^'.$oldbusinessname.'^'.''.'^'.'');
			}
			else
			if($requestfrom == 'dealer_module')
			{
				echo($fetch['tempslno'].'^'.$fetch['tempcustomerid'].'^'.$fetch['tempbusinessname'].'^'.$fetch['tempdealerbusinessname'].'^'.$fetch['tempaddress'].'^'.$fetch['tempplace'].'^'.$fetch['tempbusinesstype'].'^'.$fetch['tempcustomertype'].'^'.$fetch['tempdistrictname'].'^'.$fetch['tempstatename'].'^'.$fetch['temppincode'].'^'.$fetch['tempcategory'].'^'.$fetch['tempwebsite'].'^'.$fetch['tempremarks'].'^'.$tempcompanyclosed.'^'.$fetch['tempstdcode'].'^'.$fetch['temptype'].'^'.$fetch['tempfax'].'^'.$fetch['tempstate'].'^'.$fetch['tempdistrict'].'^'.$createddate.'^'.$fetch['newslno'].'^'.$fetch['customerid'].'^'.$fetch['businessname'].'^'.$fetch['dealerbusinessname'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['pincode'].'^'.$fetch['website'].'^'.$fetch['companyclosed'].'^'.$fetch['stdcode'].'^'.$fetch['type'].'^'.$fetch['fax'].'^'.$fetch['category'].'^'.$fetch['remarks'].'^'.$tempcontactarray.'^'.$newcontactarray.'^'.$fetch['temppromotionalsms'].'^'.$fetch['temppromotionalemail'].'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$fetch['tempgst_no'].'^'.$gst_no.'^'.$tempdelecontactarray.'^'.$oldbusinessname.'^'.$fetch['temptanno'].'^'.$fetch['tanno']);
			}else
			if($requestfrom == 'support_module' )
			{
				echo($fetch['tempslno'].'^'.$fetch['tempcustomerid'].'^'.$fetch['tempbusinessname'].'^'.$fetch['temprequestby'].'^'.$fetch['tempaddress'].'^'.$fetch['tempplace'].'^'.$fetch['tempbusinesstype'].'^'.$fetch['tempcustomertype'].'^'.$fetch['tempdistrictname'].'^'.$fetch['tempstatename'].'^'.$fetch['temppincode'].'^'.$fetch['tempcategory'].'^'.$fetch['tempwebsite'].'^'.$fetch['tempremarks'].'^'.$tempcompanyclosed.'^'.$fetch['tempstdcode'].'^'.$fetch['temptype'].'^'.$fetch['tempfax'].'^'.$fetch['tempstate'].'^'.$fetch['tempdistrict'].'^'.$createddate.'^'.$fetch['newslno'].'^'.$fetch['customerid'].'^'.$fetch['businessname'].'^'.$fetch['dealerbusinessname'].'^'.$fetch['address'].'^'.$fetch['place'].'^'.$fetch['district'].'^'.$fetch['state'].'^'.$fetch['districtname'].'^'.$fetch['statename'].'^'.$fetch['pincode'].'^'.$fetch['website'].'^'.$fetch['companyclosed'].'^'.$fetch['stdcode'].'^'.$fetch['type'].'^'.$fetch['fax'].'^'.$fetch['category'].'^'.$fetch['remarks'].'^'.$tempcontactarray.'^'.$newcontactarray.'^'.$fetch['temppromotionalsms'].'^'.$fetch['temppromotionalemail'].'^'.$fetch['promotionalsms'].'^'.$fetch['promotionalemail'].'^'.$fetch['tempgst_no'].'^'.$gst_no.'^'.$tempdelecontactarray.'^'.$oldbusinessname.'^'.''.'^'.'');
			}
		}
		else
		{
			echo('2^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
		
	}
	break;
	case 'processupdate':
	{
		//Receive the values
		$lastslno = $_POST['lastslno'];
		
		
		//query added to overcome the remarks overwrite issue
		$query20 = "select remarks,gst_no from inv_mas_customer where slno = '".$lastslno."'";           
		$resultfetch20 = runmysqlquery($query20);	       
		while($fetchres20 = mysqli_fetch_array($resultfetch20))   {
				$upremarks = $fetchres20['remarks'];
				$pending_gst = $fetchres20['gst_no'];		
       }
		
		$newbusinessname = $_POST['newbusinessname'];
		$newaddress = $_POST['newaddress'];
		$newplace = $_POST['newplace'];
		$newdistrict = $_POST['newdistrict'];
		$newpincode = $_POST['newpincode'];
		$newstdcode = $_POST['newstdcode'];
		$newfax = $_POST['newfax'];
		$newwebsite = $_POST['newwebsite'];
		$newtype = $_POST['newtype'];
		$newcategory = $_POST['newcategory'];
		$newremarks = $_POST['newremarks'];
		$newgst = $_POST['newgst'];
		
        	//added on 16-07-2019
        	$oldbusinessname = $_POST['oldbusinessname'];
		
        $remarkslength = strlen($newremarks);
		   if($remarkslength == 0) {
				$newremarks = $upremarks;                
			}
           else if($remarkslength != 0)
           {
               if ($upremarks==$newremarks)
                 {
                    $newremarks =$newremarks;                
		         }
                 else
                 {
					$newremarks .= " ".$upremarks;                
		         }
          	}
		
		$requestfrom = $_POST['requestfrom'];
		$processedemail = $_POST['processedemail'];
		$ccmail = $_POST['ccmail'];
		$cusproductslno = $_POST['cuslno'];
		
		$contactarray = $_POST['contactarray'];
		$totalarray = $_POST['deletearray'];
		$totalsplit = explode(',',$totalarray);
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
		
		$processeddatetime = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$approvalarray = array();
		$approvalarray[] = "businessname^".trim($newbusinessname);
		$approvalarray[] = "address^".$newaddress;
		$approvalarray[] = "place^".$newplace;
		$approvalarray[] = "district^".$newdistrict;
		$approvalarray[] = "pincode^".$newpincode;
		$approvalarray[] = "stdcode^".$newstdcode;
		$approvalarray[] = "fax^".$newfax;
		$approvalarray[] = "website^".$newwebsite;
		$approvalarray[] = "type^".$newtype;
		$approvalarray[] = "category^".$newcategory;
		$approvalarray[] = "remarks^".$newremarks;
		$approvalarray[] = "gst_no^".$newgst;
		$processeddatetime = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		#added on 16-07-2019
        	$approvalarray[] = "oldbusinessname^".trim($oldbusinessname);

		#added for new GST number 
		
		$gstin_id_inserted = '';
		$select_gstin_count = "select count(*) as countgstin from customer_gstin_logs where customer_slno = ".$lastslno." and gst_no = '".$newgst."'";
	    $fetch_gstin_count = runmysqlqueryfetch($select_gstin_count);
	    
	    if($fetch_gstin_count['countgstin'] == 0 && !empty($newgst) && $newgst != '') { 
    		$effective_from = date('Y-m-d');
    		
    		$insert_gst = "insert into customer_gstin_logs (gstin_id,customer_slno,effective_from,gst_no,created_by,updated_by,usertype,created_at,updated_at) 
    		values (NULL,$lastslno,'$effective_from','$newgst','$userid','$userid','user_module',NOW(),NOW())";
    		$result_gst_new = runmysqlquery($insert_gst);
    			
    		$query_gst1 = runmysqlqueryfetch("SELECT (MAX(gstin_id)) AS gstin_id_inserted FROM customer_gstin_logs where customer_slno = '$lastslno'");
    		$gstin_id_inserted = $query_gst1['gstin_id_inserted'];
	    }
	    else {
	        $query_new_gstinq = "select gstin_id from customer_gstin_logs where customer_slno = ".$lastslno." and gst_no = '".$newgst."' order by gstin_id desc limit 1";
            $result_new_gstinq = runmysqlquery($query_new_gstinq);
            
            while($fetchres_new_gstinq = mysqli_fetch_array($result_new_gstinq)) {
    			$row_new_gstinq = $fetchres_new_gstinq['gstin_id'];
    		}
    		$gstin_id_inserted = $row_new_gstinq;
	    }

	    if($pending_gst== "" && $gstin_id_inserted!= "")
		{
			$approvalarray[] = "gst_no^".$gstin_id_inserted;
		}
    			
		#ends
		
		//Frame a proper update query
		$approvalcount = count($approvalarray);
		if($approvalcount > 0)
		{
			$updatepiece = "";
			for($i = 0; $i < $approvalcount; $i++)
			{
				if($i > 0)
					$updatepiece .= ", ";
				$approvalsplit = explode("^", $approvalarray[$i]);
				$updatepiece .= $approvalsplit[0]." = '".$approvalsplit[1]."'";
				
			}
		}
		$updatequery = "update inv_mas_customer set ".$updatepiece.",lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby= '".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."'  where slno = '".$lastslno."'";
		$result = runmysqlquery($updatequery);
		
		$query1 = "DELETE FROM inv_contactdetails WHERE customerid = '".$lastslno."'";
		$resultfetch = runmysqlquery($query1);
		
		
		for($j=0;$j<count($contactressplit);$j++)
		{
			$selectiontype = $contactressplit[$j][0];
			$contactperson = $contactressplit[$j][1];
			$phone = $contactressplit[$j][2];
			$cell = $contactressplit[$j][3];
			$emailid = $contactressplit[$j][4];
			//Added Space after comma is not avaliable in phone, cell and emailid fields
			$phonespace = str_replace(", ", ",",$phone);
			$phonevalue = str_replace(',',', ',$phonespace);
			
			$cellspace = str_replace(", ", ",",$cell);
			$cellvalue = str_replace(',',', ',$cellspace);
			
			$emailidspace = str_replace(", ", ",",$emailid);
			$emailidvalue = str_replace(',',', ',$emailidspace);
			
			$query2 = "Insert into inv_contactdetails(customerid,selectiontype,contactperson,phone,cell,emailid) values  ('".$lastslno."','".$selectiontype."','".$contactperson."','".$phonevalue."','".$cellvalue."','".$emailidvalue."');";
			$result = runmysqlquery($query2);
		}
		
			
		if($requestfrom == 'customer_module' || $requestfrom == 'web_module' || $requestfrom == 'support_module')
		{
			$query7 = "update inv_customerreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where inv_customerreqpending.slno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query7);
		
			$query2 = "update inv_contactreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where refslno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query2);
		}
		else
		if($requestfrom == 'dealer_module')
		{
			$query7 = "update inv_customerreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query7);
		
			$query2 = "update inv_contactreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where refslno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query2);
		}
		$result = runmysqlquery($query7);
		$updatedata =$newbusinessname."|^|".$newaddress."|^|".$newgst."|^|".$newplace."|^|".$newpincode."|^|".$newdistrict."|^|".$newcategory."|^|".$newtype."|^|".$newstdcode."|^|".$newphone."|^|".$newcell."|^|".$newfax."|^|".$newemailid."|^|".$newwebsite."|^|".$contactarray;
			
			$query2 = "INSERT INTO inv_process_logs(date,time,type,action,updateddata,system,userid) VALUES('".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','Customer Profile','Processed','".$updatedata."','".$_SERVER['REMOTE_ADDR']."','".$userid."');";
			$result = runmysqlquery($query2);
			#########  Mailing Starts -----------------------------------
			$query1 = "SELECT createddate,processeddatetime,requestfrom from inv_customerreqpending 
			where inv_customerreqpending.slno = '".$cusproductslno."' and inv_customerreqpending.customerid = '".$lastslno."' and requestfrom = '".$requestfrom."' and customerstatus = 'processed'";
			
			$resultfetch = runmysqlquery($query1);
			$fetch1 = mysqli_fetch_array($resultfetch);
	
			//$fetch1 = runmysqlqueryfetch($query1);
			$createddate = changedateformatwithtime($fetch1['createddate']);
			$datecreated = substr($createddate,0,10);
			$timecreated = substr($createddate,11);
			$processeddatetime = changedateformatwithtime($fetch1['processeddatetime']);
			$processeddate =  substr($processeddatetime,0,10);
			$processedtime =  substr($processeddatetime,11);
			$requestfrom = $fetch1['requestfrom'];
			if($requestfrom == 'customer_module' || $requestfrom == 'web_module')
			{
				$query ="SELECT  inv_mas_customer.slno as slno,inv_mas_customer.customerid,inv_mas_customer.gst_no as gst_no,inv_mas_customer.businessname as businessname,inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_district.districtname as district,inv_mas_state.statename as state,inv_mas_customer.pincode as pincode,inv_mas_customercategory.businesstype as category,inv_mas_customertype.customertype as type,inv_mas_customer.fax as fax,inv_mas_customer.website as website,inv_mas_customer.stdcode as stdcode FROM inv_mas_customer LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_customer.district LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode LEFT JOIN inv_mas_dealer on inv_mas_customer.currentdealer = inv_mas_dealer.slno LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_mas_customer.type LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category
LEFT JOIN inv_customerreqpending on  inv_customerreqpending.customerid = inv_mas_customer.slno
where  inv_customerreqpending.slno = '".$cusproductslno."' and inv_mas_customer.slno = '".$lastslno."' and inv_customerreqpending.customerstatus = 'processed' ";
			}
			else if($requestfrom == 'dealer_module')
			{
				$query ="SELECT  inv_mas_customer.slno as slno,inv_mas_customer.customerid,inv_mas_customer.businessname as businessname,inv_mas_customer.gst_no as gst_no,inv_customerreqpending.requestby as dealerbusinessname,
inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_district.districtname as district,
inv_mas_state.statename as state,inv_mas_customer.pincode as pincode,inv_mas_customer.currentdealer,
inv_mas_customercategory.businesstype as category,inv_mas_customertype.customertype as type,
inv_mas_customer.fax as fax,inv_mas_customer.website as website,
inv_mas_customer.stdcode as stdcode,inv_mas_dealer.emailid as dealeremailid FROM inv_mas_customer 
LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_customer.district 
LEFT JOIN inv_customerreqpending on  inv_customerreqpending.customerid = inv_mas_customer.slno
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_dealer on  inv_customerreqpending.requestby = inv_mas_dealer.slno
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_mas_customer.type 
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category
where inv_customerreqpending.slno = '".$cusproductslno."' and inv_mas_customer.slno ='".$lastslno."' and inv_customerreqpending.customerstatus = 'processed'" ;
			}
			else if($requestfrom == 'support_module')
			{
				$query ="SELECT  inv_mas_customer.slno as slno,inv_mas_customer.customerid,
inv_mas_customer.businessname as businessname,
inv_mas_customer.address as address,inv_mas_customer.place as place,inv_mas_district.districtname as district,
inv_mas_state.statename as state,inv_mas_customer.pincode as pincode,ssm_users.officialemail as requestedemail,inv_mas_customercategory.businesstype as category,
inv_mas_customertype.customertype as type,inv_mas_customer.fax as fax,
inv_mas_customer.website as website,inv_mas_customer.stdcode as stdcode ,inv_mas_customer.gst_no as gst_no
FROM inv_mas_customer LEFT JOIN inv_mas_district ON inv_mas_district.districtcode = inv_mas_customer.district 
LEFT JOIN inv_mas_state on inv_mas_state.statecode = inv_mas_district.statecode 
LEFT JOIN inv_mas_dealer on inv_mas_customer.currentdealer = inv_mas_dealer.slno 
LEFT JOIN inv_mas_customertype on inv_mas_customertype.slno =inv_mas_customer.type
left join inv_customerreqpending on inv_customerreqpending.customerid = inv_mas_customer.slno
LEFT JOIN inv_mas_customercategory on inv_mas_customercategory.slno =inv_mas_customer.category
left join ssm_users on ssm_users.slno =inv_customerreqpending.requestby 
where inv_customerreqpending.slno = '".$cusproductslno."' and inv_mas_customer.slno = '".$lastslno."' and inv_customerreqpending.customerstatus = 'processed'";
			}
			$fetch = runmysqlqueryfetch($query);
			$contactvalues = '';$phoneres = '';$cellres = '';$emailidres = '';
			$query1 ="SELECT contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$lastslno."'; ";
			$resultfetch = runmysqlquery($query1);
			while($fetchres = mysqli_fetch_array($resultfetch))
			{
				$contactperson = $fetchres['contactperson'];
				$phone = $fetchres['phone'];
				$cell = $fetchres['cell'];
				$emailid = $fetchres['emailid'];
				
				$contactvalues .= $contactperson;
				$contactvalues .= appendcomma($contactperson);
				$phoneres .= $phone;
				$phoneres .= appendcomma($phone);
				$cellres .= $cell;
				$cellres .= appendcomma($cell);
				$emailidres .= $emailid;
				$emailidres .= appendcomma($emailid);
			}

			if($fetch['gst_no']!= "")
			{
				if(is_numeric($fetch['gst_no']))
	        	{
	        		//$gst_customer = $fetch['gst_no'];
	        		$get_gst_first_no = "select gst_no from customer_gstin_logs where gstin_id = '".$fetch['gst_no']."' ";
                    $fetch_first_gst_no = runmysqlqueryfetch($get_gst_first_no);
                    $gst_no = $fetch_first_gst_no['gst_no'];
	        	}
	        	else
	        	{
   					$gst_no = $fetch['gst_no'];
	        	}
			}

			$dealerbusinessname = $fetch['dealerbusinessname'];
			$customerid = $fetch['customerid'];
			$businessname = $fetch['businessname'];
			$contactperson = trim($contactvalues,',');
			$address = $fetch['address'];
			$state = $fetch['state'];
			$district = $fetch['district'];
			$pincode = $fetch['pincode'];
			$stdcode = $fetch['stdcode'];
			//$gst_no = $fetch['gst_no'];
			$place = $fetch['place'];
			$phone = trim($phoneres,',');
			$cell = trim($cellres,',');
			$fax = $fetch['fax'];
			$requestedemail = $fetch['requestedemail'];
			$dealeremailid = $fetch['dealeremailid'];
			$website = $fetch['website'];
			$type = $fetch['type'];
			$category = $fetch['category'];
			$slno = $fetch['slno'];
			$emailid = trim($emailidres,',');
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
				$emailid = 'meghana.b@relyonsoft.com';
			else
				$emailid = $emailid;
				
			$emailarray = explode(',',$emailid);
			$emailcount = count($emailarray);
			//Convert email IDs to an array. First email ID will eb assigned with Contact person name. Others will be assigned with email IDs itself as Name.
			for($i = 0; $i < $emailcount; $i++)
			{
				if(checkemailaddress($emailarray[$i]))
				{
						$emailids[$emailarray[$i]] = $emailarray[$i];
				}
			}
			
			$emailarraydealer = explode(',',$dealeremailid);
			$emailcountdealer = count($emailarraydealer);
			$bccarrayemail = explode(',',$requestedemail);
			$bccemailcount = count($bccarrayemail);
			for($i = 0; $i < $emailcountdealer; $i++)
			{
				if(checkemailaddress($emailarraydealer[$i]))
				{
						$dealeremailids[$emailarraydealer[$i]] = $emailarraydealer[$i];
						$ccmailid .= $emailarraydealer[$i];
						if($i == 0 && $emailarraydealer[$i] <> '')
							$ccids = $emailarraydealer[$i];
						else if($emailarraydealer[$i] <> '')
							$ccids .= ','.$emailarraydealer[$i];
				}
			}
			for($i = 0; $i < $bccemailcount; $i++)
			{
				if(checkemailaddress($bccarrayemail[$i]))
				{
						$bccemailids[$bccarrayemail[$i]] = $bccarrayemail[$i];
						$bccids .= $bccarrayemail[$i];
						if($i == 0 && $bccarrayemail[$i] <> '')
							$bccids = $bccarrayemail[$i];
						else if($bccarrayemail[$i] <> '')
							$bccids .= ','.$bccarrayemail[$i];
				}
			}
			
			$fromname = "Relyon";
			$fromemail = "imax@relyon.co.in";
			require_once("../inc/RSLMAIL_MAIL.php");
			$msg = file_get_contents("../mailcontents/cusprofileupdate.htm");
			$textmsg = file_get_contents("../mailcontents/cusprofileupdate.txt");
			$array = array();
			$date = datetimelocal('d-m-Y');
			if($pincode == '')
			{
				$pincode = 'Not Available';
			}
			if($stdcode == '')
			{
				$stdcode = 'Not Available';
			}
			if($address == '')
			{
				$address = 'Not Available';
			}
			if($fax == '')
			{
				$fax = 'Not Available';
			}
			if($website == '')
			{
				$website = 'Not Available';
			}
			if($type == '')
			{
				$type = 'Not Available';
			}
			if($category == '')
			{
				$category = 'Not Available';
			}
			$array[] = "##DATE##%^%".$date;
			$array[] = "##REQUESTDATE##%^%".$datecreated;
			$array[] = "##REQUESTTIME##%^%".$timecreated;
			$array[] = "##PROCESSEDDATE##%^%".$processeddate;
			$array[] = "##PROCESSEDTIME##%^%".$processedtime;
			$array[] = "##COMPANY##%^%".$businessname;
			$array[] = "##NAME##%^%".$contactperson;
			$array[] = "##ADDRESS##%^%".$address;
			$array[] = "##PLACE##%^%".$place;
			$array[] = "##DISTRICT##%^%".$district;
			$array[] = "##STATE##%^%".$state;
			$array[] = "##GSTIN##%^%".$gst_no;
			$array[] = "##PINCODE##%^%".$pincode;
			$array[] = "##STDCODE##%^%".$stdcode;
			$array[] = "##PHONE##%^%".$phone;
			$array[] = "##CELL##%^%".$cell;
			$array[] = "##FAX##%^%".$fax;
			$array[] = "##EMAILID##%^%". trim($emailidres,',');
			$array[] = "##WEBSITE##%^%".$website;
			$array[] = "##TYPE##%^%".$type;
			$array[] = "##CATEGORY##%^%".$category;
			$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
			
			$filearray = array(
					array('../images/relyon-logo.jpg','inline','8888888888'),
					array('../images/contact-info.gif','inline','33333333333'),
				);
			$toarray = $emailids;
			$ccemailids = $dealeremailids;
			
			if($ccmail == 'yes')
			{
				$ccarray = $ccemailids;
				for($j = 0; $j < count($ccarray); $j++)
				{
					$ccmailid .= $ccarray[$i];
				}
			}
			else
			{
				$ccarray = null;
				unset($bccemailids);
			}
			if(($_SERVER['HTTP_HOST'] == "meghanab") || ($_SERVER['HTTP_HOST'] == "rashmihk") ||  ($_SERVER['HTTP_HOST'] == "archanaab") )
			{
				$bccemailids['archana'] ='archana.ab@relyonsoft.com';
			}
			else
			{
				$bccemailids['relyonimax'] ='relyonimax@gmail.com';
				$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
			}
			
			$bccarray = $bccemailids;
		
			$msg = replacemailvariable($msg,$array);
			$textmsg = replacemailvariable($textmsg,$array);
			$subject = 'Profile Update Request has been processed by Relyon.';
			$html = $msg;
			$text = $textmsg;
			$replyto = 'info@relyonsoft.com';
			if($processedemail == 'yes')
			{
		    	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,$ccarray,$bccarray,$filearray,$replyto);
				
				//Insert the mail forwarded details to the logs table
				$bccmailid = $bccids.','.'bigmail@relyonsoft.com'; 
				inserttologs(imaxgetcookie('userid'),$slno,$fromname,$fromemail,$emailid,$ccids,$bccmailid,$subject);
			}
			
			#########  Mailing Ends ----------------------------------------
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','2','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$cusproductslno."');";
		$eventresult = runmysqlquery($eventquery);
		echo("Customer Record has been Processed Successfully [".$newbusinessname."]");
		//echo($query);
		
	}
	
	break;
	case 'rejectrequest':
	{
		$lastslno = $_POST['lastslno'];
		$requestfrom = $_POST['requestfrom'];
		$cusproductslno = $_POST['cuslno'];
		
		if($requestfrom == 'customer_module' || $requestfrom == 'web_module' || $requestfrom == 'support_module')
		{
			$query7 = "update inv_customerreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where slno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query7);
		
			$query2 = "update inv_contactreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where refslno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query2);
		}
		else
		if($requestfrom == 'dealer_module')
		{
			$query7 = "update inv_customerreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query7);
		
			$query2 = "update inv_contactreqpending set customerstatus = 'processed',
		processeddatetime = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."',lastmodifiedby = '".$userid."',lastmodifiedmodule ='".$requestfrom."' where refslno = '".$cusproductslno."' and  customerid = '".$lastslno."' and customerstatus = 'pending' AND requestfrom = '".$requestfrom."'";
			$result = runmysqlquery($query2);
		}
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','3','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno.'$$'.$cusproductslno."')";
		$eventresult = runmysqlquery($eventquery);
		echo('1^'.'Record has been Rejected');
	}
	break;
}

?>
