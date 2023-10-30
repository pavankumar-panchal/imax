<?php
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

$switchtype = $_POST['switchtype'];
$hwlrecordno = $_POST['hwlrecordno'];
$lockno = $_POST['lockno'];

switch($switchtype)
{
	case 'save':
	{
		$lastslno = $_POST['lastslno'];
		$hardwareno = $_POST['hardwareno'];
		$dealer = $_POST['dealer'];
		$productcode = $_POST['productarray'];
		$createddate = $_POST['startdate'];
		$remarks = $_POST['remarks'];
		$billno = $_POST['billno'];
		$billamount = $_POST['billamount'];
		if($hwlrecordno == "")
		{
			$query5 = "SELECT  count(*) as count from inv_hardwarelock where hardwarelockno='".$hardwareno."'; "; 
			$fetch1 = runmysqlqueryfetch($query5);
			if($fetch1['count'] == 0)
			{
				$query = "Insert into inv_hardwarelock(productcode,createddate,amount, billno,hardwarelockno,dealer,remarks,customerreference,createdby,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip) 
values ('".$productcode."','".changedateformat($createddate)."','".$billamount."','".$billno."','".$hardwareno."','".$dealer."','".$remarks."','".$lastslno."','".$userid."','".changedateformat($createddate)."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."');";
				$result = runmysqlquery($query);
				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','49','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
				$eventresult = runmysqlquery($eventquery);
			}
			else
			{
				echo(json_encode('2^ Hardware lock Number already exist.'));
				break;
			}
		}
		else
		{
		 if($lockno == $hardwareno)
		 	{
					$query = "UPDATE inv_hardwarelock SET productcode = '".$productcode."',
amount = '".$billamount."',billno = '".$billno."',remarks = '".$remarks."',dealer = '".$dealer."' ,
lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$userid."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."'  WHERE slno  = '".$hwlrecordno."' ";
					$result = runmysqlquery($query);
					$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','50','".date('Y-m-d').' '.date('H:i:s')."','".$lastslno."')";
					$eventresult = runmysqlquery($eventquery);
					//Insert the updated entry into the inv_logs_locksupdate table
					$query5 = "select inv_hardwarelock.slno as hwlrecordno ,inv_hardwarelock.customerreference,inv_hardwarelock.productcode as productcode,inv_hardwarelock.lastmodifieddate,inv_hardwarelock.lastmodifiedby,
inv_hardwarelock.createddate as createddate,amount,billno,inv_mas_users.fullname as enteredby,inv_hardwarelock.remarks, 
hardwarelockno,inv_mas_dealer.businessname as dealer from inv_hardwarelock 
left join inv_mas_dealer on inv_mas_dealer.slno=inv_hardwarelock.dealer 
left join inv_mas_users on inv_mas_users.slno = inv_hardwarelock.createdby 
where inv_hardwarelock.slno= '".$hwlrecordno."'";
		$fetch = runmysqlqueryfetch($query5);
		$approvalarray = $fetch['productcode'];
		$query4= "SELECT productname,productcode from inv_mas_product where productcode in (".$approvalarray.") order by productname" ;
		$result = runmysqlquery($query4);
		$count = 1;
		while($fetch2 = mysqli_fetch_array($result))
		{
			if($count > 1)
				$grid .=',';
			$grid .= $fetch2['productname'];
			$count++;
		}
			$lastslno = $fetch['customerreference'];
			$hardwareno = $fetch['hardwareno'];
			$dealer = $fetch['dealer'];
			$productcode = $fetch['productarray'];
			$createddate = $fetch['createddate'];
			$remarks =$fetch['remarks'];
			$billno = $_POST['billno'];
			$billamount = $fetch['amount'];
			$enteredby = $fetch['enteredby'];
			$lastmodifieddate =$fetch['lastmodifieddate'];
			$lastmodifiedby =$fetch['enteredby'];
			
					$updatedata = $lastslno."|^|".$grid."|^|".$createddate."|^|".$hardwareno."|^|".$billno."|^|".$remarks."|^|".$dealer."|^|".$billamount."|^|".$enteredby."|^|".$lastmodifieddate."|^|".$lastmodifiedby;
					$query2 = "INSERT INTO inv_logs_locksupdate(date,time,updateddata,system) VALUES('".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$updatedata."','".$_SERVER['REMOTE_ADDR']."');";
				$result = runmysqlquery($query2);
			}
			else
			{
				echo(json_encode('2^ Hardware lock Number already exist.'));
				break;
			}
		
		}
		echo(json_encode('1^ Record saved sucessfully.'));
			//echo($query);
	}
	break;
	case 'generateproductgrid':
	{
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$lastslno = $_POST['lastslno'];
		$query ="SELECT distinct count(*) as count from inv_hardwarelock where inv_hardwarelock.customerreference = '".$lastslno."'  ";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$query2 ="select distinct inv_hardwarelock.slno as hwlrecordno ,inv_hardwarelock.customerreference,
productcode,inv_hardwarelock.createddate as createddate,amount,billno,inv_mas_users.fullname as enteredby,inv_hardwarelock.remarks, hardwarelockno,inv_mas_dealer.businessname as dealer,inv_hardwarelock.lastmodifiedby,inv_hardwarelock.lastmodifieddate from inv_hardwarelock left join inv_mas_dealer on inv_mas_dealer.slno=inv_hardwarelock.dealer left join inv_mas_users on inv_mas_users.slno = inv_hardwarelock.createdby 
where customerreference = '".$lastslno."' order by hwlrecordno";
			$fetch6 = runmysqlquery($query2);
			$fetchresultcount = mysqli_num_rows($fetch6);
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
				$startlimit = $slnocount ;
				$slnocount = $slnocount;
			}
		$query1 ="select distinct inv_hardwarelock.slno as hwlrecordno ,inv_hardwarelock.customerreference,
productcode,inv_hardwarelock.createddate as createddate,amount,billno,inv_mas_users.fullname as enteredby,
inv_hardwarelock.remarks, hardwarelockno,inv_mas_dealer.businessname as dealer,
inv_hardwarelock.lastmodifieddate,inv_hardwarelock.lastmodifiedby as lastmodifiedby
from inv_hardwarelock left join inv_mas_dealer on inv_mas_dealer.slno=inv_hardwarelock.dealer 
left join inv_mas_users on inv_mas_users.slno = inv_hardwarelock.createdby  
 where customerreference = '".$lastslno."' order by hwlrecordno LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
			{
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
				$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Hardwarelock no</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date of Issue</td><td nowrap = "nowrap" class="td-border-grid" align="left">Products</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill no</td><td nowrap = "nowrap" class="td-border-grid" align="left">Entered By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Bill amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td></tr>';
			}
			$i_n = 0;
			$result = runmysqlquery($query1);
				while($fetch = mysqli_fetch_array($result))
				{
					$slnocount++;
					$i_n++;
					$color;
					if($i_n%2 == 0)
						$color = "#edf4ff";
					else
						$color = "#f7faff";
					
					$grid .= '<tr align="left" class="gridrow" onclick ="productdetailstoform(\''.$fetch['hwlrecordno'].'\');" bgcolor='.$color.'>';
							$approvalproductarray = $fetch['productcode'];
							$query = "SELECT productname,productcode from inv_mas_product where productcode in
							(".$approvalproductarray.") order by productname";
							$result9 = runmysqlquery($query);
							$gridassign = '';
							$count = 1;
							while($fetch0 = mysqli_fetch_array($result9))
							{
								if($count > 1)
									$gridassign .=',';
								$gridassign .= $fetch0['productname'];
								$count++;
							}

					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['hardwarelockno']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['dealer']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformat($fetch['createddate'])."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$gridassign."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['billno']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['enteredby']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['amount']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td><td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
				
					$grid .= '</tr>';
				}
				$fetchcount = mysqli_num_rows($result);
				if($slnocount >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatemoregrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="generatemoregrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1"  style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
				//echo($query1);
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
			}
			else
			{
				echo('2^No datas found to be displayed.')	;
			}	
				
	}
	break;
	case 'getcustomercount':
	{
		$responsearray3 = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname";
		$result = runmysqlquery($query);
		$count = mysqli_num_rows($result);
		$responsearray3['count'] = $count;
		echo(json_encode($responsearray3));
	}
	break;
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$generatecustomerlistarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatecustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'];
			$count++;
		}
		echo(json_encode($generatecustomerlistarray));
	}
	break;
	case 'productdetailstoform':
	{
		$productdetailstoformarray =array();
		$hwlrecordno = $_POST['hwlrecordno'];
		$query = "select inv_hardwarelock.slno as hwlrecordno ,inv_hardwarelock.customerreference, 
inv_hardwarelock.productcode, inv_hardwarelock.createddate as createddate, 
inv_hardwarelock.amount,inv_hardwarelock.billno,inv_hardwarelock.remarks, inv_hardwarelock.hardwarelockno,inv_hardwarelock.dealer as dealer, inv_hardwarelock.lastmodifieddate as lastmodifieddate,inv_mas_users.fullname as enteredby from inv_hardwarelock left join inv_mas_users on inv_mas_users.slno = inv_hardwarelock.createdby where  inv_hardwarelock.slno = '".$hwlrecordno."' ";
		$fetch = runmysqlqueryfetch($query);
		$createddate = changedateformatwithtime($fetch['lastmodifieddate']);
		$approvalarray = $fetch['productcode'];
		$query4= "SELECT productname,productcode from inv_mas_product where productcode in (".$approvalarray.") order by productname" ;
		$result = runmysqlquery($query4);
		$count = 1;
		while($fetch2 = mysqli_fetch_array($result))
		{
			if($count > 1)
			$grid .='&*&';
		$grid .= $fetch2['productname'].'%'.$fetch2['productcode'];
		$count++;
		}
		$productdetailstoformarray['dealer'] = $fetch['dealer'];
		$productdetailstoformarray['createddate'] = changedateformat($fetch['createddate']);
		$productdetailstoformarray['amount'] = $fetch['amount'];
		$productdetailstoformarray['billno'] = $fetch['billno'];
		$productdetailstoformarray['remarks'] = $fetch['remarks'];
		$productdetailstoformarray['hardwarelockno'] = $fetch['hardwarelockno'];
		$productdetailstoformarray['grid'] = $grid;
		$productdetailstoformarray['enteredby'] = $fetch['enteredby'];
		$productdetailstoformarray['createddate'] = $createddate;
		$productdetailstoformarray['hwlrecordno'] = $fetch['hwlrecordno'];
		
		
		//echo($fetch['dealer'].'^'.changedateformat($fetch['createddate']).'^'.$fetch['amount'].'^'.$fetch['billno'].'^'.$fetch['remarks'].'^'.$fetch['hardwarelockno'].'^'.$grid.'^'.$fetch['enteredby'].'^'.$createddate.'^'.$fetch['hwlrecordno']);
	//	echo($query);
	echo(json_encode($productdetailstoformarray));
	}
	break;
	
	case 'delete':
	{
		$hwlrecordno = $_POST['hwlrecordno'];
		$query="Delete from inv_hardwarelock where inv_hardwarelock.slno = '".$hwlrecordno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','51','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		echo(json_encode('3^ Record Deleted Successfully.'));
	}
	break;
	
	case 'displaycustomer':
	{
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch = runmysqlqueryfetch($query);
		echo($fetch['businessname'].'^'.$customerreference);
	}
	break;
	case 'searchbycontractid':
	{
		$searchbycontractidarray = array();
		$contractid = $_POST['contractid'];
		$query1 = "SELECT count(*) as count from inv_hardwarelock where hardwarelockno = '".$contractid."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "select inv_hardwarelock.slno as hwlrecordno ,inv_hardwarelock.customerreference,productcode ,
inv_hardwarelock.createddate as createddate,amount,billno,inv_hardwarelock.remarks,
 hardwarelockno,inv_hardwarelock.dealer as dealer,inv_hardwarelock.lastmodifieddate,inv_mas_users.fullname as enteredby
from inv_hardwarelock left join inv_mas_users on inv_mas_users.slno = inv_hardwarelock.createdby
where hardwarelockno = '".$contractid."'";
			$fetch = runmysqlqueryfetch($query);
			$createddate = changedateformatwithtime($fetch['lastmodifieddate']);
			$query1 = "SELECT businessname from inv_mas_customer where slno = '".$fetch['customerreference']."';";
			$fetch3 = runmysqlqueryfetch($query1);
			$approvalarray = $fetch['productcode'];
			$query4= "SELECT productname,productcode from inv_mas_product where productcode in(".$approvalarray.") order by productname" ;
			$result = runmysqlquery($query4);
			$count = 1;
			while($fetch2 = mysqli_fetch_array($result))
			{
				if($count > 1)
				$grid .='&*&';
				$grid .= $fetch2['productname'].'%'.$fetch2['productcode'];
				$count++;
			}
			$searchbycontractidarray['errorcode'] = '1';
			$searchbycontractidarray['dealer'] = $fetch['dealer'];
			$searchbycontractidarray['createddate'] = changedateformat($fetch['createddate']);
			$searchbycontractidarray['amount'] = $fetch['amount'];
			$searchbycontractidarray['billno'] = $fetch['billno'];
			$searchbycontractidarray['remarks'] = $fetch['remarks'];
			$searchbycontractidarray['hardwarelockno'] = $fetch['hardwarelockno'];
			$searchbycontractidarray['grid'] = $grid;
			$searchbycontractidarray['enteredby'] = $fetch['enteredby'];
			$searchbycontractidarray['createddate'] = $createddate;
			$searchbycontractidarray['businessname'] = $fetch3['businessname'];
			$searchbycontractidarray['hwlrecordno'] = $fetch['hwlrecordno'];
			$searchbycontractidarray['customerreference'] = $fetch['customerreference'];
			//echo('1^'.$fetch['dealer'].'^'.changedateformat($fetch['createddate']).'^'.$fetch['amount'].'^'.$fetch['billno'].'^'.$fetch['remarks'].'^'.$fetch['hardwarelockno'].'^'.$grid.'^'.$fetch['enteredby'].'^'.$createddate.'^'.$fetch3['businessname'].'^'.$fetch['hwlrecordno'].'^'.$fetch['customerreference']);
			//echo($query);	
			
		}
		else
		{
			$searchbycontractidarray['errorcode'] = '2';
			$searchbycontractidarray['createddate'] = '';
			$searchbycontractidarray['amount'] = '';
			$searchbycontractidarray['billno'] = '';
			$searchbycontractidarray['remarks'] = '';
			$searchbycontractidarray['hardwarelockno'] = '';
			$searchbycontractidarray['grid'] = '';
			$searchbycontractidarray['enteredby'] = '';
			$searchbycontractidarray['createddate'] = '';
			$searchbycontractidarray['businessname'] = '';
			$searchbycontractidarray['hwlrecordno'] ='';
			$searchbycontractidarray['customerreference'] = '';
		}
		echo(json_encode($searchbycontractidarray));

	}
	break;
	

	
	//function for changing date format
/*function changedateformat($date)
{
    if($date <> '0000-00-00')
	{
	$datesplit = split('[/.-]',$date);
	$newdate = $datesplit[2]."-".$datesplit[1]."-".$datesplit[0];
	}
	else
	{	
	$newdate = '';
	}
	return $newdate;
}*/
	
}
?>