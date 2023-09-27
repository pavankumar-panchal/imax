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
	case 'save':
	{
		$responsearray = array();
		$productcode = $_POST['productcode'];
		$scheme = $_POST['scheme'];
		$singleusernewamt = $_POST['singleusernewamt'];
		$singleuserupdationamt = $_POST['singleuserupdationamt'];
		$multiusernewamt = $_POST['multiusernewamt'];
		$multiuserupdationamt = $_POST['multiuserupdationamt'];
		$additionalnewamt = $_POST['additionalnewamt'];
		$additionalupdationamt = $_POST['additionalupdationamt'];
		if($lastslno == "")
		{
			$query33 = "SELECT productname,productcode,subgroup FROM inv_mas_product where productcode = '".$productcode."' ;";
			$result33 = runmysqlqueryfetch($query33);
			
			if($result33['subgroup']!= 'ESS')
			{
				$query = "select * from inv_schemepricing where product = '".$productcode."' and scheme = '".$scheme."';";
				$result = runmysqlquery($query);
				if(mysqli_num_rows($result) == 0)
				{
					$query = "Insert into inv_schemepricing(product,scheme,newsuprice,newmuprice,updatesuprice,updatemuprice,newaddlicenseprice,updationaddlicenseprice,userid,createddate,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip) values ('".$productcode."','".$scheme."','".$singleusernewamt."','".$multiusernewamt."','".$singleuserupdationamt."','".$multiuserupdationamt."','".$additionalnewamt."','".$additionalupdationamt."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','".$userid."','".$_SERVER['REMOTE_ADDR']."');";
					//echo($query);exit;
					$result = runmysqlquery($query);
				
				}
				else
				{
					$responsearray['errormessage'] = "3^"."The Product is already added to the Selected Scheme.";
					//echo("3^"."The Product is already added to the Selected Scheme.");
				}
			
			
				
				$query5 = "select * from inv_dealer_pricing where product = '".$productcode."';";
				$result5 = runmysqlquery($query5);
				if(mysqli_num_rows($result5) == 0)
				{
					$query66 = "Insert into inv_dealer_pricing(product,productname,newsuprice,newmuprice,updatesuprice,updatemuprice,newaddlicenseprice,updationaddlicenseprice) values ('".$productcode."','".$result33['productname']."','".$singleusernewamt."','".$multiusernewamt."','".$singleuserupdationamt."','".$multiuserupdationamt."','".$additionalnewamt."','".$additionalupdationamt."');";
					//echo($query);exit;
					$result = runmysqlquery($query66);
				}
				else{
					if($scheme == '1')
					{
						$query = "UPDATE inv_dealer_pricing SET product = '".$productcode."',newsuprice = '".$singleusernewamt."',newmuprice = '".$multiusernewamt."',updatesuprice = '".$singleuserupdationamt."',updatemuprice = '".$multiuserupdationamt."',newaddlicenseprice = '".$additionalnewamt."',updationaddlicenseprice = '".$additionalupdationamt."'  WHERE product = '".$productcode."'";
						$result = runmysqlquery($query);
					}
				}
					
					
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','17','".date('Y-m-d').' '.date('H:i:s')."')";
				$eventresult = runmysqlquery($eventquery);
				
				$responsearray['errormessage'] = "1^"."Record Saved Successfully";
			}
			else
			{
				$responsearray['errormessage'] = "3^"."ESS product cannot be added for Scheme pricing.";
			}
				//echo("1^"."Record Saved Successfully");
			

		}
		else
		{
						
			$query = "UPDATE inv_schemepricing SET product = '".$productcode."',newsuprice = '".$singleusernewamt."',newmuprice = '".$multiusernewamt."',updatesuprice = '".$singleuserupdationamt."',updatemuprice = '".$multiuserupdationamt."',newaddlicenseprice = '".$additionalnewamt."',updationaddlicenseprice = '".$additionalupdationamt."' ,lastmodifieddate = '".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip =  '".$_SERVER['REMOTE_ADDR']."' WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			
			if($scheme == '1')
			{
				$query123 = "select * from inv_dealer_pricing where product = '".$productcode."' ;";
				$result123 = runmysqlquery($query123);
				if(mysqli_num_rows($result123) <> 0)
				{
					$query = "UPDATE inv_dealer_pricing SET product = '".$productcode."',newsuprice = '".$singleusernewamt."',newmuprice = '".$multiusernewamt."',updatesuprice = '".$singleuserupdationamt."',updatemuprice = '".$multiuserupdationamt."',newaddlicenseprice = '".$additionalnewamt."',updationaddlicenseprice = '".$additionalupdationamt."'  WHERE product = '".$productcode."'";
					$result = runmysqlquery($query);
				}
			}
			
			
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','18','".date('Y-m-d').' '.date('H:i:s')."')";
			$eventresult = runmysqlquery($eventquery);
			$responsearray['errormessage'] = "4^"."Record Saved Successfully";
			//echo("4^"."Record Saved Successfully");

		}
		echo(json_encode($responsearray));
	
	}
	break;
	case 'delete':
	{
		$responsearray = array();
		$query = "delete from inv_schemepricing where slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','19','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		$responsearray['errormessage'] = "2^"."Record Deleted Successfully";
		echo(json_encode($responsearray));
		//echo("2^"."Record Deleted Successfully");
	}
	break;
	case 'generateschemelist':
	{
		$generateschemelistarray = array();
		$query = "SELECT todate,slno,schemename FROM inv_mas_scheme where todate > curdate()  ORDER BY schemename ";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generateschemelistarray [$count]= $fetch['schemename'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generateschemelistarray));
	}
	break;
	case 'schemedetailstoform':
	{
		$schemedetailstoformarray = array();
		$query1 = "SELECT count(*) as count from inv_schemepricing where slno = '".$lastslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "select inv_schemepricing.slno,inv_schemepricing.newsuprice,inv_schemepricing.updatesuprice,inv_schemepricing.newmuprice,
inv_schemepricing.updatemuprice,inv_schemepricing.newaddlicenseprice,inv_schemepricing.updationaddlicenseprice,
inv_schemepricing.product,inv_mas_users.fullname from inv_schemepricing left join inv_mas_users on inv_mas_users.slno = inv_schemepricing.userid where inv_schemepricing.slno = '".$lastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$schemedetailstoformarray['newsuprice'] = $fetch['newsuprice'];
			$schemedetailstoformarray['updatesuprice'] = $fetch['updatesuprice'];
			$schemedetailstoformarray['newmuprice'] = $fetch['newmuprice'];
			$schemedetailstoformarray['updatemuprice'] = $fetch['updatemuprice'];
			$schemedetailstoformarray['newaddlicenseprice'] = $fetch['newaddlicenseprice'];
			$schemedetailstoformarray['updationaddlicenseprice'] = $fetch['updationaddlicenseprice'];
			$schemedetailstoformarray['product'] = $fetch['product'];
			$schemedetailstoformarray['fullname'] = $fetch['fullname'];
			//echo($fetch['newsuprice'].'^'.$fetch['updatesuprice'].'^'.$fetch['newmuprice'].'^'.$fetch['updatemuprice'].'^'.$fetch['newaddlicenseprice'].'^'.$fetch['updationaddlicenseprice'].'^'.$fetch['product'].'^'.$fetch['fullname']);
			
		}
		else
		{
			$schemedetailstoformarray['newsuprice'] = '';
			$schemedetailstoformarray['updatesuprice'] = '';
			$schemedetailstoformarray['newmuprice'] = '';
			$schemedetailstoformarray['updatemuprice'] = '';
			$schemedetailstoformarray['newaddlicenseprice'] = '';
			$schemedetailstoformarray['updationaddlicenseprice'] = '';
			$schemedetailstoformarray['product'] = '';
			$schemedetailstoformarray['fullname'] = '';
			//echo($lastslno.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
		
		echo(json_encode($schemedetailstoformarray));

	}
	break;
	
	case 'generateschemepricinggrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$schemeid = $_POST['schemeid'];
		$showtype = $_POST['showtype'];
		$resultcount = "select inv_schemepricing.slno,inv_schemepricing.newsuprice,inv_schemepricing.newmuprice,inv_schemepricing.updatesuprice,
inv_schemepricing.newaddlicenseprice,inv_schemepricing.updationaddlicenseprice,inv_mas_product.productname
 from inv_schemepricing left join inv_mas_product on inv_mas_product.productcode = inv_schemepricing.product 
where inv_schemepricing.scheme = '".$schemeid."' and inv_mas_product.allowdealerpurchase = 'yes';
";
		$resultfetch = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($resultfetch);
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
		$query = "select inv_schemepricing.slno,inv_schemepricing.newsuprice,inv_schemepricing.newmuprice,inv_schemepricing.updatesuprice,inv_schemepricing.updatemuprice,inv_schemepricing.newaddlicenseprice,inv_schemepricing.updationaddlicenseprice,inv_mas_product.productname,inv_mas_users.fullname from inv_schemepricing left join inv_mas_product on inv_mas_product.productcode = inv_schemepricing.product left join inv_mas_users on inv_mas_users.slno = inv_schemepricing.userid where inv_schemepricing.scheme = '".$schemeid."' and inv_mas_product.allowdealerpurchase = 'yes' LIMIT ".$startlimit.",".$limit."; ";
		$result = runmysqlquery($query);
		if($startlimit == 0)
		{
		$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
		//$grid = '<tr><td><table width="100%" cellpadding="3" cellspacing="0">';
		$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid"  align="left"  align="left">product Name</td><td nowrap = "nowrap" class="td-border-grid"  align="left">New Single User Price</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Updation Single User Price</td><td nowrap = "nowrap" class="td-border-grid"  align="left">New Multi User Price</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation Multi User Price</td><td nowrap = "nowrap" class="td-border-grid"  align="left">New Additional License Price</td><td nowrap = "nowrap" class="td-border-grid" align="left">Updation Additional License Price</td><td nowrap = "nowrap" class="td-border-grid"  align="left">Entered By</td></tr>';
		}
		
		$i_n = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr bgcolor='.$color.' class="gridrow" onclick ="schemedetailstoform(\''.$fetch['slno'].'\');" >';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['productname'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['newsuprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['updatesuprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['newmuprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['updatemuprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['newaddlicenseprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['updationaddlicenseprice'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch['fullname'])."</td>";
			$grid .= "</tr>";
		}
			$grid .= "</table>";

		$fetchcount = mysqli_num_rows($result);
		if($slno >= $fetchresultcount)
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';

		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="getmoregenerateschemepricinggrid(\''.$schemeid.'\',\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a>&nbsp;&nbsp;&nbsp;<a onclick ="getmoregenerateschemepricinggrid(\''.$schemeid.'\',\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
	
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid;
	}
	break;
	case 'searchbyschemeid':
	{
		$searchbyschemeidarray = array();
		$schemeid = $_POST['scheme'];
		$query1 = "SELECT count(*) as count from inv_schemepricing where scheme = '".$schemeid."'";
		$fetch2 = runmysqlqueryfetch($query1);
		if($fetch2['count'] > 0)
		{
			$query = "select inv_schemepricing.slno,inv_schemepricing.newsuprice,inv_schemepricing.newmuprice,inv_schemepricing.updatesuprice,
inv_schemepricing.updatemuprice,inv_schemepricing.newaddlicenseprice,inv_schemepricing.updationaddlicenseprice,
inv_schemepricing.product,inv_mas_users.fullname,inv_mas_scheme.schemename  from inv_schemepricing left join inv_mas_users on inv_mas_users.slno = inv_schemepricing.userid left join inv_mas_scheme on inv_mas_scheme.slno = inv_schemepricing.slno where inv_schemepricing.slno = '".$schemeid."';";
			$fetch = runmysqlqueryfetch($query);
			$searchbyschemeidarray['newsuprice'] = $fetch['newsuprice'];
			$searchbyschemeidarray['newmuprice'] = $fetch['newmuprice'];
			$searchbyschemeidarray['updatesuprice'] = $fetch['updatesuprice'];
			$searchbyschemeidarray['updatemuprice'] = $fetch['updatemuprice'];
			$searchbyschemeidarray['newaddlicenseprice'] = $fetch['newaddlicenseprice'];
			$searchbyschemeidarray['updationaddlicenseprice'] = $fetch['updationaddlicenseprice'];
			$searchbyschemeidarray['product'] = $fetch['product'];
			$searchbyschemeidarray['fullname'] = $fetch['fullname'];
			$searchbyschemeidarray['schemename'] = $fetch['schemename'];
			//echo($fetch['newsuprice'].'^'.$fetch['newmuprice'].'^'.$fetch['updatesuprice'].'^'.$fetch['updatemuprice'].'^'.$fetch['newaddlicenseprice'].'^'.$fetch['updationaddlicenseprice'].'^'.$fetch['product'].'^'.$fetch['fullname'].'^'.$fetch['schemename']);
			
		}
		else
		{
			//echo($schemeid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
			$searchbyschemeidarray['newsuprice'] = '';
			$searchbyschemeidarray['newmuprice'] = '';
			$searchbyschemeidarray['updatesuprice'] = '';
			$searchbyschemeidarray['updatemuprice'] = '';
			$searchbyschemeidarray['newaddlicenseprice'] = '';
			$searchbyschemeidarray['updationaddlicenseprice'] = '';
			$searchbyschemeidarray['product'] = '';
			$searchbyschemeidarray['fullname'] = '';
			$searchbyschemeidarray['schemename'] = '';
		}
		echo(json_encode($searchbyschemeidarray));
	}
	break;
	
}



?>