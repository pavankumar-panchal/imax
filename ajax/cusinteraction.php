<?php
ini_set("display_errors",0);
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
		$customerreference = $_POST['customerreference'];
		$interactiondate = datetimelocal('d-m-Y').' '.datetimelocal('H:i:s');
		$remarks = $_POST['remarks'];
		$interactiontype = $_POST['interactiontype'];
		$fetch56 = runmysqlqueryfetch("SELECT fullname,slno FROM inv_mas_users WHERE slno = '".$userid."'");
		$enteredby = $fetch56['slno'];
		if($lastslno == '')
		{
			$query = "Insert into inv_customerinteraction(customerid,createddate,
createdby,remarks,interactiontype,modulename,lastmodifieddate,lastmodifiedby,createdip,lastmodifiedip) values 
('".$customerreference."','".changedateformatwithtime($interactiondate)."','".$enteredby."','".$remarks."','".$interactiontype."',
'user_module','".changedateformatwithtime($interactiondate)."','".$enteredby."','".$_SERVER['REMOTE_ADDR']."','".$_SERVER['REMOTE_ADDR']."');";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','56','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$query4 = "SELECT MAX(slno) as slno FROM inv_customerinteraction";
			$fetch2 = runmysqlqueryfetch($query4);
			$lastslno = $fetch2['slno'];
		}
		else
		{
		
			$query = "UPDATE inv_customerinteraction SET remarks = '".$remarks."',interactiontype = '".$interactiontype."',
lastmodifieddate ='".date('Y-m-d').' '.date('H:i:s')."',lastmodifiedby ='".$enteredby."',lastmodifiedip = '".$_SERVER['REMOTE_ADDR']."' WHERE slno = '".$lastslno."'";
			$result = runmysqlquery($query);
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','57','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
		}
		//echo($query);
		echo(json_encode("1^"."Customer Interaction Record '".$customerreference."' Saved Successfully"));
	}
	break;

	case 'delete':
	{
		$lastslno = $_POST['lastslno'];
		$query = "DELETE FROM inv_customerinteraction WHERE slno = '".$lastslno."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','58','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		echo(json_encode("2^"."Customer Interaction Record Deleted Successfully"));
	}
	break;
	
	case 'generatecustomerlist':
	{
		$generatecustomerlistarray = array();
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname  LIMIT ".$startindex.",".$limit.";";
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
	case 'generategrid':
	{
		$startlimit = $_POST['startlimit'];
		$slno = $_POST['slno'];
		$showtype = $_POST['showtype'];
		$customerreference = $_POST['customerreference'];
		$query ="SELECT distinct count(*) as count from inv_customerinteraction where inv_customerinteraction.customerid = '".$customerreference."'  ";  
		$fetch1 = runmysqlqueryfetch($query);
		if($fetch1['count'] > 0)
		{
			$resultcount = "SELECT inv_customerinteraction.slno as slno,inv_mas_customer.businessname as businessname,inv_customerinteraction.createddate as createddate,inv_customerinteraction.createdby 
as createdby,inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as modulename ,inv_mas_interactiontype.interactiontype as interactiontype
FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid 
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype
WHERE inv_mas_customer.slno = '".$customerreference."' order by createddate desc ";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
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
		$query = "SELECT inv_customerinteraction.slno as slno,inv_mas_customer.businessname as businessname,inv_customerinteraction.createddate as createddate,inv_customerinteraction.createdby 
as createdby,inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as modulename,
inv_mas_interactiontype.interactiontype as interactiontype,inv_customerinteraction.lastmodifieddate as lastmodifieddate,inv_customerinteraction.lastmodifiedby as lastmodifiedby FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype WHERE inv_mas_customer.slno = '".$customerreference."' order by createddate desc  LIMIT ".$startlimit.",".$limit.";";
 		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Customer Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td><td nowrap = "nowrap" class="td-border-grid" align="left">Created By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Entered Through</td><td nowrap = "nowrap" class="td-border-grid" align="left">Interaction Category</td><td nowrap = "nowrap" class="td-border-grid" align="left">Last Modified Date</td></tr>';
		}
		$i_n = 0;
		$result2 = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result2))
		{
			$i_n++;$slno++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\')" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slno."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['createddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
				if($fetch['modulename'] == 'dealer_module')
				{
					$query2 ="select inv_mas_dealer.businessname as businessname from inv_mas_dealer 
	left join inv_customerinteraction on inv_customerinteraction.createdby = inv_mas_dealer.slno 
	WHERE inv_mas_dealer.slno = '".$fetch['createdby']."'";
					$fetchresult = runmysqlqueryfetch($query2);
					$businessname  = $fetchresult['businessname'];
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$businessname."</td>";
				}
				else
				{
					$query1 ="select fullname from inv_mas_users left join inv_customerinteraction on inv_customerinteraction .createdby = inv_mas_users.slno WHERE inv_mas_users.slno = '".$fetch['createdby']."'";
					$resultfetch = runmysqlqueryfetch($query1);
					$enteredby  = $resultfetch['fullname'];
					$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$enteredby."</td>";
				}
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".modulegropname($fetch['modulename'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['interactiontype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['lastmodifieddate'])."</td>";
			$grid .= "</tr>";
		}
			$grid .= "</table>";
			$fetchcount = mysqli_num_rows($result2);
			if($slno >= $fetchresultcount)
			
			$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
			else
			$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="cusmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="cusmoredatagrid(\''.$startlimit.'\',\''.$slno.'\',\'all\');" class="resendtext1"  style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
				echo('1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid);
			}
				else
			{
				echo('2^No datas found to be displayed.')	;
			}	
		}
	break;
	
	case 'gridtoform':
	{
		$gridtoformarray = array();
		$cusinteractionslno = $_POST['cusinteractionslno'];
		$query1 = "SELECT count(*) as count from inv_customerinteraction where slno = '".$cusinteractionslno."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_mas_customer.slno as slno,inv_mas_customer.businessname as businessname,inv_customerinteraction.createddate as interactiondate,
inv_customerinteraction.createdby as enteredby,inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as modulename ,inv_mas_interactiontype.slno as interactiontype
FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid 
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype 
WHERE inv_customerinteraction.slno = '".$cusinteractionslno."';";
			$fetch = runmysqlqueryfetch($query);
			$createddate = changedateformatwithtime($fetch['interactiondate']);
			$modulename = $fetch['modulename'];
			if($modulename == 'dealer_module' )
			{
				$query2 ="select inv_mas_dealer .businessname as businessname from inv_mas_dealer 
left join inv_customerinteraction on inv_customerinteraction.createdby = inv_mas_dealer.slno 
WHERE inv_customerinteraction.slno = '".$cusinteractionslno."'";
				$fetchresult = runmysqlqueryfetch($query2);
				$businessname  = $fetchresult['businessname'];
				$gridtoformarray['errorcode'] = '1';
				$gridtoformarray['businessname'] = $fetch['businessname'];
				$gridtoformarray['interactiondate'] = changedateformatwithtime($fetch['interactiondate']);
				$gridtoformarray['enteredby'] = $businessname;
				$gridtoformarray['remarks'] = $fetch['remarks'];
				$gridtoformarray['slno'] = $fetch['slno'];
				$gridtoformarray['modulename'] = modulegropname($modulename);
				$gridtoformarray['interactiontype'] = $fetch['interactiontype'];
				echo(json_encode($gridtoformarray));
				//echo('1^'.$fetch['businessname'].'^'.changedateformatwithtime($fetch['interactiondate']).'^'.$businessname.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype']);
			}
			else if($modulename == 'user_module')
			{
				$query1 = "select inv_mas_users.fullname as enteredby  from inv_mas_users left join inv_customerinteraction on inv_customerinteraction .createdby = inv_mas_users.slno WHERE inv_customerinteraction.slno = '".$cusinteractionslno."'";
				$fetch1 = runmysqlqueryfetch($query1);
				$enteredby =$fetch1['enteredby'];
				{	
					$gridtoformarray['errorcode'] = '2';
					$gridtoformarray['businessname'] = $fetch['businessname'];
					$gridtoformarray['interactiondate'] = changedateformatwithtime($fetch['interactiondate']);
					$gridtoformarray['enteredby'] = $enteredby;
					$gridtoformarray['remarks'] = $fetch['remarks'];
					$gridtoformarray['slno'] = $fetch['slno'];
					$gridtoformarray['modulename'] = modulegropname($modulename);
					$gridtoformarray['interactiontype'] = $fetch['interactiontype'];		
					echo(json_encode($gridtoformarray));//echo('2^'.$fetch['businessname'].'^'.changedateformatwithtime($fetch['interactiondate']).'^'.$enteredby.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype']);
					break;
				}
			}
		}
		else
		{
			$gridtoformarray['errorcode'] = '';
			$gridtoformarray['businessname'] = '';
			$gridtoformarray['interactiondate'] = '';
			$gridtoformarray['businessname'] = '';
			$gridtoformarray['remarks'] = '';
			$gridtoformarray['slno'] = '';
			$gridtoformarray['modulename'] ='';
			$gridtoformarray['interactiontype'] = '';		
			echo(json_encode($gridtoformarray));
		}
	}
	break;
	
	case 'displaycustomer':
	{
		$customerreference = $_POST['customerreference'];
		$query = "SELECT businessname from inv_mas_customer where slno = '".$customerreference."';";
		$fetch = runmysqlqueryfetch($query);
		echo(json_encode('1^'.$fetch['businessname'].'^'.$customerreference));
	}
	break;
	case 'searchbycustomerid':
	{
		$searchbycustomeridarray = array();
		$customerid = $_POST['customerid'];
		$query1 = "SELECT count(*) as count from inv_mas_customer where slno = '".$customerid."'";
		$fetch2 = runmysqlqueryfetch($query1);
		if($fetch2['count'] > 0)
		{
			$query1 = "SELECT count(*) as count from inv_customerinteraction where customerid = '".$customerid."'";
			$fetch1 = runmysqlqueryfetch($query1);
			if($fetch1['count'] > 0)
			{
				$query = "SELECT inv_customerinteraction.slno as slno,inv_mas_customer.businessname as businessname,inv_customerinteraction.createddate as interactiondate,inv_customerinteraction.customerid as customerid,
inv_customerinteraction.createdby as enteredby,inv_customerinteraction.remarks as remarks,inv_customerinteraction.modulename as modulename ,inv_mas_interactiontype.slno as interactiontype
FROM inv_customerinteraction LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerinteraction.customerid 
left join inv_mas_interactiontype on inv_mas_interactiontype.slno = inv_customerinteraction.interactiontype 
WHERE inv_customerinteraction.customerid = '".$customerid."';";
				$fetch = runmysqlqueryfetch($query);
				$createddate = changedateformatwithtime($fetch['interactiondate']);
				$modulename = $fetch['modulename'];
				if($modulename == 'dealer_module' )
				{
					$query2 ="select inv_mas_dealer .businessname as businessname from inv_mas_dealer 
	left join inv_customerinteraction on inv_customerinteraction.createdby = inv_mas_dealer.slno 
	WHERE inv_customerinteraction.customerid = '".$customerid."'";
					$fetchresult = runmysqlqueryfetch($query2);
					$businessname  = $fetchresult['businessname'];
					
					$gridtoformarray['errorcode'] = '1';
					$gridtoformarray['businessname'] = $fetch['businessname'];
					$gridtoformarray['interactiondate'] = changedateformatwithtime($fetch['interactiondate']);
					$gridtoformarray['enteredby'] = $enteredby;
					$gridtoformarray['remarks'] = $fetch['remarks'];
					$gridtoformarray['slno'] = $fetch['slno'];
					$gridtoformarray['modulename'] = modulegropname($modulename);
					$gridtoformarray['interactiontype'] = $fetch['interactiontype'];
					$gridtoformarray['customerid'] = $customerid;
					echo(json_encode($gridtoformarray));

					//echo'1^'.($fetch['businessname'].'^'.changedateformatwithtime($fetch['interactiondate']).'^'.$businessname.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype'].'^'.$customerid);
				}
				else if($modulename == 'user_module')
				{
					$query1 = "select inv_mas_users.fullname as enteredby  from inv_mas_users left join inv_customerinteraction on inv_customerinteraction .createdby = inv_mas_users.slno WHERE inv_customerinteraction.customerid = '".$customerid."'";
					$fetch1 = runmysqlqueryfetch($query1);
					$enteredby =$fetch1['enteredby'];
					{	
							
						$gridtoformarray['errorcode'] = '2';
						$gridtoformarray['businessname'] = $fetch['businessname'];
						$gridtoformarray['interactiondate'] = changedateformatwithtime($fetch['interactiondate']);
						$gridtoformarray['enteredby'] = $enteredby;
						$gridtoformarray['remarks'] = $fetch['remarks'];
						$gridtoformarray['slno'] = $fetch['slno'];
						$gridtoformarray['modulename'] = modulegropname($modulename);
						$gridtoformarray['interactiontype'] = $fetch['interactiontype'];
						$gridtoformarray['customerid'] = $customerid;	
						echo(json_encode($gridtoformarray));
						//echo('2^'.$fetch['businessname'].'^'.changedateformatwithtime($fetch['interactiondate']).'^'.$enteredby.'^'.$fetch['remarks'].'^'.$fetch['slno'].'^'.modulegropname($modulename).'^'.$fetch['interactiontype'].'^'.$customerid);
						break;
					}
				}
			}
			else
			{
				$query3 = "SELECT businessname from inv_mas_customer where slno = '".$customerid."'";
				$fetch3 = runmysqlqueryfetch($query3);
				$gridtoformarray['errorcode'] = '3';
				$gridtoformarray['businessname'] = $fetch['businessname'];
				echo(json_encode($gridtoformarray));
				//echo('3^'.$fetch3['businessname']);
			}
		}
		else
		{
			$gridtoformarray['customerid'] = $customerid;
			$gridtoformarray['errorcode'] = '';
			$gridtoformarray['businessname'] = '';
			$gridtoformarray['interactiondate'] = '';
			$gridtoformarray['enteredby'] = '';
			$gridtoformarray['remarks'] ='';
			$gridtoformarray['slno'] = '';
			$gridtoformarray['modulename'] = '';
			$gridtoformarray['interactiontype'] = '';
			echo(json_encode($gridtoformarray));//echo($customerid.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}

	}
	break;
}


?>