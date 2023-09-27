<?

ob_start("ob_gzhandler");
ini_set('memory_limit', -1);

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

$type = $_POST['type'];
$changetype = $_POST['changetype'];
$scratchnumber = $_POST['scratchnumber'];
$remarks = $_POST['remarks'];
$pinremarksstatus = $_POST['pinremarksstatus'];
//$searchtext = $_POST['searchtext'];
//$frstchar = substr($searchtext, 0, 1);
//$liketext = ($frstchar == '.')?(" Where inv_mas_scratchcard.cardid LIKE '".$searchtextfield."%' "):("");
switch($type)
{
	
	case 'blockcard':
	{
		$query = "UPDATE inv_mas_scratchcard SET blocked = 'yes', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','68','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		$blockquery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Blocked','".$remarks."','".$pinremarksstatus."')";
		$blockresult = runmysqlquery($blockquery);
		echo(json_encode('1^Card blocked Successfully'));
	}
	break;
	
	case 'unblockcard':
	{
		$query = "UPDATE inv_mas_scratchcard SET blocked = 'no', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','68','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		echo(json_encode('1^Card Unblocked Successfully'));
	}
	break;
	
	case 'cancelcard':
	{
		$query = "UPDATE inv_mas_scratchcard SET cancelled = 'yes', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','68','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		$cancelquery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Cancelled','".$remarks."','".$pinremarksstatus."')";
		$cancelresult = runmysqlquery($cancelquery);
		echo(json_encode('1^Card Cancelled Successfully'));
	}
	break;
	
	case 'none':
	{
		$query = "UPDATE inv_mas_scratchcard SET blocked = 'no', cancelled = 'no', remarks ='".$remarks."'  WHERE cardid = '".$scratchnumber."';";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','69','".date('Y-m-d').' '.date('H:i:s')."','".$scratchnumber."')";
		$eventresult = runmysqlquery($eventquery);
		$activequery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks,pinremarksstatus) values('".$scratchnumber."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Active','".$remarks."','".$pinremarksstatus."')";
		$activeresult = runmysqlquery($activequery);
		echo(json_encode('1^Card Get Activated Successfully'));
	}
	break;

	case 'checkuploaddata':
	{
		//echo "hi";
		//exit;
		if ( $_FILES['file']['error'] > 0) 
			echo 'Error: ' . $_FILES['uploadfile']['error'] . '<br>';

		else
		{
			//echo "../filecreated/".$_FILES['uploadfile']['name'];
			//if(!file_exists('../filecreated/'.$_FILES['uploadfile']['name']))
			//{
			$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
			$fetchres = runmysqlqueryfetch($query);	
			$username = $fetchres['username'];
			$username = str_replace(' ', '', $username);
			$temp = explode(".", $_FILES["uploadfile"]["name"]);
			$newfilename = $username.round(microtime(true)) . '.' . end($temp);

			move_uploaded_file($_FILES['uploadfile']['tmp_name'],'../filecreated/'.$newfilename);

			$path = '../filecreated/'.$newfilename;

			
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
				
				$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:11px" id="griddata"><thead>';
				$grid .= '<tr class="tr-grid-header">
							<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
							<td nowrap = "nowrap" class="td-border-grid" align="left">Pins</td>
							<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
							</tr></thead><tbody>';
				$slnocount = 1;	
				$i_n = 0;
				$a = array();
				while (($pinsdata = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					//print_r($receiptdata);
					if($pinsdata[0]!= "" && is_numeric($pinsdata[0]))
					{
						$pinnumcount = array_push($a,$pinsdata[0]);;
						$color;
						$i_n++;
						if($i_n%2 == 0)
							$color = "#edf4ff";
						else
							$color = "#f7faff";

						//Blocked and cancelled the PIN 
						$query11 = "UPDATE inv_mas_scratchcard SET blocked = 'yes',cancelled = 'yes' ,remarks = '".$pinsdata[1]."' where cardid =
						'".$pinsdata[0]."' ";
						$resultvalue = runmysqlquery($query11);

						$blockquery = "insert into inv_blockcanceldetails(cardid,userid,datetime,status,remarks) 
						values('".$pinsdata[0]."','".$userid."','".date('Y-m-d').' '.date('H:i:s')."','Blocked','".$pinsdata[1]."')";
						$blockresult = runmysqlquery($blockquery);

						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','268','".date('Y-m-d').' '.date('H:i:s')."','Bulk_Pins_Blocked')";
						$eventresult = runmysqlquery($eventquery);

						$grid .= '<tr bgcolor='.$color.' class="gridrow" align="left" >';
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount++."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$pinsdata[0]."</td>";
						$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$pinsdata[1]."</td>";
						$grid .= "</tr>";
					}
				}
			
				fclose($handle);
				$grid .= "</tbody></table>";
				//echo $receiptdatacount;
				echo "1^".$grid.'^'.$pinnumcount;
				//echo $grid;
			}
			else
			{
				echo "not readeable";
			}
		}     
	}
	break;

	/*case 'getcardlist':
	{
		$getcardlistarray = array();
		$query = "SELECT cardid,scratchnumber FROM inv_mas_scratchcard  ORDER BY scratchnumber ";
		$result = runmysqlquery($query);
		$grid = '';
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$getcardlistarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($getcardlistarray));
	}
	break;*/
	
	case 'griddata':
	{
		$pinno = trim($_POST['pinno']);
		$cardid = trim($_POST['cardid']);
		
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
		
		$query = "select * from inv_mas_scratchcard  where  ".$carddetails;
		$fetch = runmysqlqueryfetch($query);
		$i_n = 0;
		$card = $fetch['cardid'];
		
		$query1 = "select inv_mas_users.fullname,inv_blockcanceldetails.datetime,remarks,inv_blockcanceldetails.status,inv_pinremarksstatus.status as pinremarksstatus
		from inv_blockcanceldetails
		left join inv_mas_users on inv_blockcanceldetails.userid = inv_mas_users.slno
		left join inv_pinremarksstatus on inv_blockcanceldetails.pinremarksstatus = inv_pinremarksstatus.id
		where cardid =  '".$card."' order by inv_blockcanceldetails.slno desc";
		$result1 = runmysqlquery($query1);
		$fetchresultcount = mysqli_num_rows($result1);
		$grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
		style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Username</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Pin Remarks Status</td>
		</tr>';
		if($fetchresultcount>0)
		{
			while($fetch1=mysqli_fetch_array($result1))
			{
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
					
				/*$activecard= "<a href'#' class='resendtext' Onclick = 'displaycard(\"active\",\"$pinserialno\");' 
				style='cursor:pointer;'>".$activecardcount."</a>";
				$blockcard= "<a href'#' class='resendtext' Onclick = 'displaycard(\"block\",\"$pinserialno\");' 
				style='cursor:pointer;'>".$blockcardcount."</a>";
				$cancelcard= "<a href'#' class='resendtext' Onclick = 'displaycard(\"cancel\",\"$pinserialno\");' 
				style='cursor:pointer;'>".$cancelcardcount."</a>";*/	
				
				$grid .= '<tr class="gridrow" bgcolor='.$color.'>';
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$i_n."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['fullname']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['datetime']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['status']."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".wordwrap($fetch1['remarks'],25,"<br>\n")."</td>";
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch1['pinremarksstatus']."</td>";
				$grid .= "</tr>";
			 }
		  }
		  else
		  {
			  $grid .= '<tr class="gridrow" bgcolor='.$color.'>';
			  $grid .= "<td nowrap='nowrap' class='td-border-grid' align='left' colspan='5'>
			  <strong>No Records Found</strong>
			  </td>
			  </tr>";
		  }
		   $grid .= "</table>";
		
		echo '1^'.$grid;
	}
     break;
	 
	 /*case 'active':
	 {
		 $scratchcard1 = $_POST['cardno'];
		 
		 $query5 = "select cardid,inv_mas_users.fullname,datetime,remarks from inv_blockcanceldetails
         left join inv_mas_users on inv_blockcanceldetails.userid = inv_mas_users.slno
         where cardid =  '".$scratchcard1."' and active = 'yes' order by inv_blockcanceldetails.slno desc;";
		 $result5 = runmysqlquery($query5);
		 $grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
		style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Username</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">cardid</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		</tr>';
		 $i_n = 0;
		 while($fetch5 = mysqli_fetch_array($result5))
		 {
			 
			
			$i_n++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				
		    $grid .= '<tr class="gridrow" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$i_n."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch5['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$scratchcard1."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch5['datetime']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch5['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		echo '1^'.$grid;
	 }
	 break;
	 
	 case 'block':
	 {
		$scratchcard2 = $_POST['cardno'];
		 
		 $query6 = "select cardid,inv_mas_users.fullname,datetime,remarks from inv_blockcanceldetails
         left join inv_mas_users on inv_blockcanceldetails.userid = inv_mas_users.slno
         where cardid =  '".$scratchcard."' and blocked = 'yes' order by inv_blockcanceldetails.slno desc;";
		 $result6 = runmysqlquery($query6);
		 $grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
		style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Username</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">cardid</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		</tr>';
		 $i_n = 0;
		 while($fetch6 = mysqli_fetch_array($result6))
		 {
			$i_n++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				
		    $grid .= '<tr class="gridrow" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$i_n."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch6['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$scratchcard2."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch6['datetime']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch6['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		echo '1^'.$grid; 
	 }
	 break;
	 
	 case 'cancel';
	 {
		 $scratchcard3 = $_POST['cardno'];
		 
		 $query7 = "select cardid,inv_mas_users.fullname,datetime,remarks from inv_blockcanceldetails
         left join inv_mas_users on inv_blockcanceldetails.userid = inv_mas_users.slno
         where cardid =  '".$scratchcard3."' and cancelled = 'yes' order by inv_blockcanceldetails.slno desc;";
		 $result7 = runmysqlquery($query7);
		 $grid = '<table width="100%" cellpadding="3" cellspacing="0"  class="table-border-grid" 
		style="font-size:12px">';
		$grid .= '<tr class="tr-grid-header">
		<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Username</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">cardid</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
		<td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td>
		</tr>';
		 $i_n = 0;
		 while($fetch7 = mysqli_fetch_array($result7))
		 {
			$i_n++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
				
		    $grid .= '<tr class="gridrow" bgcolor='.$color.'>';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$i_n."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch7['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$scratchcard3."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch7['datetime']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch7['remarks']."</td>";
			$grid .= "</tr>";
		}
		$grid .= "</table>";
		echo '1^'.$grid;
	 }
	 break;*/
	 
	case 'carddetailstoform':
	{
		$carddetailstoformarray = array();
		//$cardlastslno = $_POST['cardlastslno'];
		$pinno = trim($_POST['pinno']);
		$cardid = trim($_POST['cardid']);
		
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
		
		$query = "select * from inv_mas_scratchcard  where  ".$carddetails;
		$result = runmysqlquery($query);
		if(mysqli_num_rows($result) > 0)
		/*$result1 = runmysqlquery($query1);
		while($fetch1 = mysqli_fetch_array($result1))
		{
			$card = $fetch1['cardid'];*/
		
			/*$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid, inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, inv_dealercard.date as attachdate, inv_customerproduct.date as registereddate, inv_mas_customer.businessname as registeredto, inv_mas_scratchcard.remarks,inv_mas_scratchcard.blocked,inv_mas_scratchcard.cancelled ,inv_mas_scratchcard.attached,
	inv_mas_scratchcard.registered from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode left join (select distinct cardid, customerreference, MIN(date) AS date from inv_customerproduct GROUP BY date) AS inv_customerproduct on  inv_dealercard.cardid = inv_customerproduct.cardid left join inv_mas_customer on  inv_customerproduct.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$card."' order by cardid;";*/
	    {
			$fetch = runmysqlqueryfetch($query);
			$query1 = "select pinremarksstatus from inv_blockcanceldetails left join inv_pinremarksstatus on inv_blockcanceldetails.pinremarksstatus = inv_pinremarksstatus.id where cardid = '".$fetch['cardid']."' order by slno desc limit 1";
			$result1 = runmysqlquery($query1);
			if(mysqli_num_rows($result1))
			{
				$fetch1 = runmysqlqueryfetch($query1);
				if($fetch1['pinremarksstatus'] == null)
					$pinstatus = ' ';
				else
					$pinstatus = $fetch1['pinremarksstatus'];
			}
			else
				$pinstatus=" ";
			$cardstatus = "";
			if($fetch['blocked'] == 'yes')
			$cardstatus = 'Blocked';
			elseif($fetch['cancelled'] == 'yes')
			$cardstatus = 'Cancelled';
			else
			{
			$cardstatus = 'Active';
			}
	
			
			if($cardstatus == 'Active')
			$none = 'yes';
				else
			$none = '';
			
			$carddetailstoformarray['cardid'] = $fetch['cardid'];
			$carddetailstoformarray['scratchnumber'] = $fetch['scratchnumber'];
			$carddetailstoformarray['purchasetype'] = $fetch['purchasetype'];
			$carddetailstoformarray['usagetype'] = $fetch['usagetype'];
			$carddetailstoformarray['attachedto'] = $fetch['attachedto'];
			$carddetailstoformarray['dealerid'] = $fetch['dealerid'];
			$carddetailstoformarray['productcode'] = $fetch['productcode'];
			$carddetailstoformarray['productname'] = $fetch['productname'];
			$carddetailstoformarray['attachdate'] = $fetch['attachdate'];
			$carddetailstoformarray['registereddate'] = $fetch['registereddate'];
			$carddetailstoformarray['registeredto'] = $fetch['registeredto'];
			$carddetailstoformarray['remarks'] = $fetch['remarks'];
			$carddetailstoformarray['cardstatus'] = $cardstatus;
			$carddetailstoformarray['attached'] = $fetch['attached'];
			$carddetailstoformarray['registered'] = $fetch['registered'];
			$carddetailstoformarray['blocked'] = $fetch['blocked'];
			$carddetailstoformarray['cancelled'] = $fetch['cancelled'];
			$carddetailstoformarray['pinstatusremarks'] = $pinstatus;
			$carddetailstoformarray['none'] = $none;
			
			echo(json_encode($carddetailstoformarray));	
		}
		else
		{
			$carddetailstoformarray['nodata'] = "no data";
			
			echo(json_encode($carddetailstoformarray));	
		}

//echo($fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.$fetch['attachdate'].'^'.$fetch['registereddate'].'^'.$fetch['registeredto'].'^'.$fetch['remarks'].'^'.$cardstatus.'^'.$fetch['attached'].'^'.$fetch['registered'].'^'.$fetch['blocked'].'^'.$fetch['cancelled'].'^'.$none);
		///}
	}
	break;
	
	
	/*case 'searchfilter':
	{
		$textfield = $_POST['textfield'];
		$subselection = $_POST['subselection'];
		$orderby = $_POST['orderby'];
		
		if(strlen($textfield) > 0)
		{
			switch($orderby)
			{
				case "cardid": $orderbyfield = "cardid"; break;
				case "scratchnumber": $orderbyfield = "scratchnumber"; break;
				case "dealerid": $orderbyfield = "dealerid"; break;
				case "productname": $orderbyfield = "productname"; break;
			}
			switch($subselection)
			{
				case "cardid":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "scratchnumber":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "productname":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "dealerid":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "registered":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "online":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "blocked":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "cancelled":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
				case "attached":
					$query = " LIKE '%".$textfield."%' ORDER BY ".$orderbyfield;
				break;
			}
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" >';
			
			$grid .= '<tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid">Sl No</td><td nowrap = "nowrap" class="td-border-grid">Customer ID</td><td nowrap = "nowrap" class="td-border-grid">Business Name</td><td nowrap = "nowrap" class="td-border-grid">Contact Person</td><td nowrap = "nowrap" class="td-border-grid">Phone</td><td nowrap = "nowrap" class="td-border-grid">Place</td><td nowrap = "nowrap" class="td-border-grid">Email</td><td nowrap = "nowrap" class="td-border-grid">Region</td></tr>';
			
			$result = runmysqlquery($query);
			while($fetch = mysqli_fetch_array($result))
			{
				$i_n++;
				$color;
				if($i_n%2 == 0)
					$color = "#edf4ff";
				else
					$color = "#f7faff";
				static $count = 0;
				$count++;
				$radioid = 'nameloadcustomerradio'.$count;
				$grid .= '<tr class="gridrow" onclick ="customerdetailstoform(\''.$fetch['slno'].'\');"  bgcolor='.$color.'>';
				
				$grid .= "<td nowrap='nowrap' class='td-border-grid'>".$fetch['slno']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['customerid']."</td><td nowrap='nowrap' class='td-border-grid'>".addslashes($fetch['businessname'])."</td><td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['contactperson'])."</td><td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['phone'])."</td><td nowrap='nowrap' class='td-border-grid'>".gridtrim($fetch['place'])."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['emailid']."</td><td nowrap='nowrap' class='td-border-grid'>".$fetch['region']."</td>";
			
				$grid .= '</tr>';
			}
			$grid .= '</table>';
			echo($grid);
		}
	}
	break;*/
}
?>
