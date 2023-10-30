<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
$cardid = $_POST['cardid'];
$switchtype = $_POST['switchtype'];

switch($switchtype)
{
	case 'attachcard':
	{
		$attacheddate = datetimelocal('Y-m-d').' '.datetimelocal('H:i:s');
		$customerreference = $_POST['customerreference'];
		$remarks = $_POST['remarks'];
		$dealerid = $_POST['dealerid'];
		$lastyearusagecheck = $_POST['lastyearusagecheck'];
		$usagecheck = explode("#",$lastyearusagecheck);
		$description = $_POST['description'];
		$prodescription = explode("$",$description);
		$proname = $prodescription[1];
		$purchasetype = $prodescription[2];
		if($prodescription[3] == "Single User")
		   $usatype = "singleuser";
		else
		   $usatype = "multiuser";   
		$scratchcard = $prodescription[5];
		
		$purcheck = $_POST['purcheck'];
		$licensepurchase = $_POST['licensepurchase'];
		$yearcount = $_POST['yearcount'];
		$remarks = $_POST['remarks'];
		//exit;
		//if($proname!= "" && $descriptiontwo == "updation")
		//{
			$progroup = array('SBSN','SBPN','SASN','SAPN','SBEN');
			if($purchasetype == "updation" && ($usagecheck[0]!="SBSN" && $usagecheck[0]!="SBSN" && $usagecheck[0]!="SASN" && $usagecheck[0]!="SAPN" && $usagecheck[0]!="SBEN"))
			{
				if($yearcount == 1)
				{
					if($purcheck < $licensepurchase)
					{
						//echo "hi";
						$query5 = "select usagetype,count(usagetype) as usagecount from inv_dealercard
						left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
                        where inv_dealercard.customerreference = ".$customerreference." and inv_mas_product.year  = '".$usagecheck[1]."'
						and inv_mas_product.subgroup  = '".$usagecheck[0]."' 
                        and inv_dealercard.usagetype = '".$usatype."' group by inv_dealercard.usagetype";
						//echo $query5;
						$result5 = runmysqlquery($query5);
						$count5 = mysqli_num_rows($result5);
						
						//echo '$count5 : '.$count5;
						
						if($count5 > 0)
						{
							$fetch5 = runmysqlqueryfetch($query5);
							$query6 = "select usagetype from inv_dealercard
							left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
							where inv_dealercard.customerreference = ".$customerreference." and 
							inv_mas_product.productname  = '".$proname."' and inv_dealercard.usagetype = '".$usatype."' ;";
							$result6 = runmysqlquery($query6);
							$count6 = mysqli_num_rows($result6);
							
							//echo '$fetch5[usagecount] : '. $fetch5['usagecount'];
							//echo '$count6: '. $count6;
							
							if($count6 < $fetch5['usagecount'])
							{
								checkattchdetails($attacheddate,$customerreference,$description,$remarks,$scratchcard,$dealerid,$userid);
							}
							else
							{
								echo(json_encode('1^'.'Not eligible to take Updation '.$prodescription[3].' card.'));
							}
						}
						else
						{
							echo(json_encode('1^'.'Not eligible to take Updation '.$prodescription[3].' card.'));
						}
					}
					else
					{
						echo(json_encode('1^'.'Customer has zero updation card.'));
					}
				}
				else
				{
					echo(json_encode('1^'.'Customer has not taken card from last two year.'));
				}
			}
			//else if($purchasetype == "new")
			else
			{
				checkattchdetails($attacheddate,$customerreference,$description,$remarks,$scratchcard,$dealerid,$userid);
			}
	
	}
	break;
	case 'newupdationchange':
	{
		//$customerlicenseid = substr($_POST['customerid'],-5);
		$customerlicenseid = $_POST['customerid'];
		$card = $_POST['card'];
		
		$query0 = "select inv_mas_product.subgroup from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.cardid = ".$card;
		$fetch0 = runmysqlqueryfetch($query0);
		//$fetch0 = mysqli_fetch_array($result0);
		$subgroup = $fetch0['subgroup']; 
				
		$currentyearquery = "select year from inv_mas_product where subgroup = '".$subgroup."' order by year desc limit 1;";
		$currentyearfetch = runmysqlqueryfetch($currentyearquery);
		//$currentyearfetch = mysqli_fetch_array($currentyearresult);
		$currentyear = $currentyearfetch['year'];
		
		//query for taking 	current year updation card count	
		$newquery1 = "select count(inv_dealercard.purchasetype)as purchasetype from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$currentyear."' 
		and inv_dealercard.purchasetype = 'updation' order by year desc";
		$newfetch1 = runmysqlqueryfetch($newquery1);
		//$newfetch1 = mysqli_fetch_array($newresult1);
		$currentyearcard = $newfetch1['purchasetype'];
		
		//query for taking last two year
        // if($subgroup == "GST")
        // {
        //     $yearcount = ['2019-20','2020-21'];
        // }
        // else {
            $yearquery = "select distinct(year) from inv_mas_product where year!= '" . $currentyear . "' order by year desc limit 2;";
            $yearresult = runmysqlquery($yearquery);
            while ($yearfetch = mysqli_fetch_array($yearresult)) {
                $yearcount[] = $yearfetch['year'];
            }
        //}
		
		
		//query for taking last two year count
		$totalcards = "";
		$querychange1 = "select inv_mas_product.year from inv_dealercard
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
		left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
		where inv_dealercard.customerreference = ".$customerlicenseid."
		and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year in 
		('".$yearcount[0]."','".$yearcount[1]."') order by inv_mas_product.year desc limit 1";
		$resultchange1 = runmysqlquery($querychange1);
		$count = mysqli_num_rows($resultchange1);
		//$lasttwoyearcount = $count;
		
		
		if($count == 1)
		{
			$fetchchange1 = mysqli_fetch_array($resultchange1);
			$lasttwoyear = $fetchchange1['year'];

			//query for taking  card count based on last two year count
		    $querychange2 = "select count(inv_dealercard.purchasetype) as purchasetype from inv_dealercard
			left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
			left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
			where inv_dealercard.customerreference = ".$customerlicenseid."
			and inv_mas_product.subgroup = '".$subgroup."' and inv_mas_product.year = '".$lasttwoyear."'";
			$fetchchange2 = runmysqlqueryfetch($querychange2);
			//$count2 = mysqli_num_rows($resultchange2);
			//$fetchchange2 = mysqli_fetch_array($resultchange2);
			$totalcards = $fetchchange2['purchasetype'];
		}
			
		$custprodetails['totalcards'] = $totalcards;
		$custprodetails['lasttwoyearcount'] = $count;
		$custprodetails['currentyearcard'] = $currentyearcard;
		$custprodetails['lastyearusagecheck'] = $subgroup."#".$lasttwoyear;
		
		echo(json_encode($custprodetails));
	}
	break;

	case 'detachcard':
	{
		$remarks = $_POST['remarks'];
			$query4 = "Delete from inv_invoicenumbers_dummy_regv2 where cardid = '".$cardid."'";
			$result4 = runmysqlquery($query4);
				
		$query = "Update inv_dealercard set customerreference = '',cuscardremarks = '".$remarks."' where cardid = '".$cardid."' ";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','53','".date('Y-m-d').' '.date('H:i:s')."')";
		$eventresult = runmysqlquery($eventquery);
		echo(json_encode('2^'.'Card Detached Successfully.'));
	}
	break;
	
	case 'generatecustomerlist':
	{
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$generatecustomerlistarray = array();
		$query = "SELECT slno,businessname,customerid FROM inv_mas_customer ORDER BY businessname 
		LIMIT ".$startindex.",".$limit.";";
		$result = runmysqlquery($query);
		$count = 0;
		while($fetch = mysqli_fetch_array($result))
		{
			$generatecustomerlistarray[$count] = $fetch['businessname'].'^'.$fetch['slno'].'^'.$fetch['customerid'];
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
	
	case 'getcardlist':
	{
		## To Enable New Products for Autoregistration and product code like inv_dealercard.productcode != "XXX" #
		$dealerid = $_POST['dealerid'];
		$limit = $_POST['limit'];
		$startindex = $_POST['startindex'];
		$productcode = $_POST['productcode'];
		$purtype = $_POST['purtype'];
		$products = (!empty($productcode)) ? " and inv_dealercard.productcode = '".$productcode."'" : '' ;
		$purtype = (!empty($purtype)) ? " and inv_dealercard.purchasetype = '".$purtype."'" : '' ;
		/*$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard left join
		inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.attached = 'yes' and
		inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is
		null) and inv_mas_scratchcard.blocked = 'no'  and inv_dealercard.dealerid = '".$dealerid."'  and
		(inv_dealercard.productcode=353 or inv_dealercard.productcode=308 or inv_dealercard.productcode=371 or
		inv_dealercard.productcode = 215 or inv_dealercard.productcode = 216 or inv_dealercard.productcode = 217 or
		inv_dealercard.productcode = 515 or inv_dealercard.productcode = 242 or inv_dealercard.productcode = 243 or
		inv_dealercard.productcode = 881 or inv_dealercard.productcode = 885 
		or inv_dealercard.productcode = 886 or inv_dealercard.productcode = 887 or inv_dealercard.productcode = 888 or
		inv_dealercard.productcode = 690 or inv_dealercard.productcode = 643 or inv_dealercard.productcode = 658 or inv_dealercard.productcode = 659 or inv_dealercard.productcode = 882 or inv_dealercard.productcode = 883 or inv_dealercard.productcode = 884 or inv_dealercard.productcode = 214 or inv_dealercard.productcode = 309 or  inv_dealercard.productcode = 001 or inv_dealercard.productcode = 372 or inv_dealercard.productcode = 354 or  inv_dealercard.productcode = 660 or inv_dealercard.productcode = 661 or inv_dealercard.productcode = 644 or  inv_dealercard.productcode = 484 or inv_dealercard.productcode = 485 or inv_dealercard.productcode = 483 or  inv_dealercard.productcode = 482 or inv_dealercard.productcode = 516 or inv_dealercard.productcode = 481 or  inv_dealercard.productcode = 244 or inv_dealercard.productcode = 245 or inv_dealercard.productcode = 818 or inv_dealercard.productcode = 691 or inv_dealercard.productcode = 219 or inv_dealercard.productcode = 220 or inv_dealercard.productcode = 221 or inv_dealercard.productcode = 218 or inv_dealercard.productcode = 222 or inv_dealercard.productcode = 223 or inv_dealercard.productcode = 224 or inv_dealercard.productcode = 889 or inv_dealercard.productcode = 890 or inv_dealercard.productcode = 891 or inv_dealercard.productcode = 892 or inv_dealercard.productcode = 662 or inv_dealercard.productcode = 664 or inv_dealercard.productcode = 667 or inv_dealercard.productcode = 246 or inv_dealercard.productcode = 247 or inv_dealercard.productcode = 373 or inv_dealercard.productcode = 310 or inv_dealercard.productcode = 355 or inv_dealercard.productcode = 486 or inv_dealercard.productcode = 517) ORDER BY scratchnumber LIMIT ".$startindex.",".$limit." ";*/
		
		$proquery="SELECT distinct inv_mas_product.productcode,inv_mas_product.productname FROM inv_mas_product 
		left join inv_dealercard on inv_dealercard.productcode = inv_mas_product.productcode 
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
		where inv_mas_scratchcard.attached = 'yes' and
		inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
		and inv_mas_scratchcard.blocked ='no' and inv_dealercard.dealerid = '".$dealerid."' and inv_mas_product.newproduct = 1 ORDER BY productname ";
		$proresult = runmysqlquery($proquery);
				
		while($fetch = mysqli_fetch_array($proresult))
		{
			$productcodes[] = $fetch['productcode'];
		}

		if(!in_array($productcode,$productcodes))
		{
			$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber 
			FROM inv_mas_scratchcard 
			left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
			left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
			where inv_mas_scratchcard.attached = 'yes' and
			inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
			and inv_mas_scratchcard.blocked = 'no'  and inv_dealercard.dealerid = '".$dealerid."' 
			and inv_mas_product.newproduct = 1 ORDER BY scratchnumber LIMIT ".$startindex.",".$limit;
		}
		else
		{
			$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber 
			FROM inv_mas_scratchcard 
			left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
			left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
			where inv_mas_scratchcard.attached = 'yes' and
			inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
			and inv_mas_scratchcard.blocked = 'no'  and inv_dealercard.dealerid = '".$dealerid."' 
			and inv_mas_product.newproduct = 1".$products.$purtype." ORDER BY scratchnumber LIMIT ".$startindex.",".$limit;
		}
		
		$result = runmysqlquery($query);
		$responsecardarray = array();
		$count = 0;
		//$grid = '';
		//$count = 1;
		while($fetch = mysqli_fetch_array($result))
		{
			/*if($count > 1)
				$grid .='^*^';*/
			$responsecardarray[$count] =  $fetch['scratchnumber'].' | '.$fetch['cardid'].'^'.$fetch['cardid'];
			$count++;
		}
		echo(json_encode($responsecardarray));
	 }
	break;

	case 'getcardcount':
	{
		$dealerid = $_POST['dealerid'];
		$productcode = $_POST['productcode'];
		$purtype = $_POST['purtype'];
		$products = (!empty($productcode)) ? " and inv_dealercard.productcode = '".$productcode."'" : '' ;
		$purtype = (!empty($purtype)) ? " and inv_dealercard.purchasetype = '".$purtype."'" : '' ;
		$responsearray3 = array();
		/*$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard left join
		inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.attached = 'yes' and
		inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is
		null) and inv_mas_scratchcard.blocked = 'no'  and inv_dealercard.dealerid = '".$dealerid."'  and
		(inv_dealercard.productcode=353 or inv_dealercard.productcode=308 or inv_dealercard.productcode=371 or
		inv_dealercard.productcode = 215 or inv_dealercard.productcode = 216 or inv_dealercard.productcode = 217 or
		inv_dealercard.productcode = 515 or inv_dealercard.productcode = 242 or inv_dealercard.productcode = 243 or
		inv_dealercard.productcode = 881 or inv_dealercard.productcode = 885 
		or inv_dealercard.productcode = 886 or inv_dealercard.productcode = 887 or inv_dealercard.productcode = 888 or
		inv_dealercard.productcode = 690 or inv_dealercard.productcode = 643 or inv_dealercard.productcode = 658 or inv_dealercard.productcode = 659 or inv_dealercard.productcode = 882 or inv_dealercard.productcode = 883 or inv_dealercard.productcode = 884 or inv_dealercard.productcode = 214 or inv_dealercard.productcode = 309 or  inv_dealercard.productcode = 001 or inv_dealercard.productcode = 372 or inv_dealercard.productcode = 354 or  inv_dealercard.productcode = 660 or inv_dealercard.productcode = 661 or inv_dealercard.productcode = 644 or  inv_dealercard.productcode = 484 or inv_dealercard.productcode = 485 or inv_dealercard.productcode = 483 or  inv_dealercard.productcode = 482 or inv_dealercard.productcode = 516 or inv_dealercard.productcode = 481 or  inv_dealercard.productcode = 244 or inv_dealercard.productcode = 245 or inv_dealercard.productcode = 818 or inv_dealercard.productcode = 691 or inv_dealercard.productcode = 219 or inv_dealercard.productcode = 220 or inv_dealercard.productcode = 221 or inv_dealercard.productcode = 218 or inv_dealercard.productcode = 222 or inv_dealercard.productcode = 223 or inv_dealercard.productcode = 224 or inv_dealercard.productcode = 889 or inv_dealercard.productcode = 890 or inv_dealercard.productcode = 891 or inv_dealercard.productcode = 892 or inv_dealercard.productcode = 662 or inv_dealercard.productcode = 664 or inv_dealercard.productcode = 667 or inv_dealercard.productcode = 246 or inv_dealercard.productcode = 247 or inv_dealercard.productcode = 373 or inv_dealercard.productcode = 310 or inv_dealercard.productcode = 355 or inv_dealercard.productcode = 486 or inv_dealercard.productcode = 517) ORDER BY scratchnumber";*/

		$proquery="SELECT distinct inv_mas_product.productcode,inv_mas_product.productname FROM inv_mas_product 
		left join inv_dealercard on inv_dealercard.productcode = inv_mas_product.productcode 
		left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
		where inv_mas_scratchcard.attached = 'yes' and
		inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
		and inv_mas_scratchcard.blocked ='no' and inv_dealercard.dealerid = '".$dealerid."' and inv_mas_product.newproduct = 1 ORDER BY productname ";
		$proresult = runmysqlquery($proquery);
		
		$optiongrid = '<option value="" >Select a Item</option>';
		
		while($fetch = mysqli_fetch_array($proresult))
		{
			$productcodes[] = $fetch['productcode'];
			if((!empty($productcode) && $productcode == $fetch['productcode']))
				$optiongrid .= '<option value="'.$fetch['productcode'].'" selected>'.$fetch['productname'].'</option>';
			else
				$optiongrid .= '<option value="'.$fetch['productcode'].'" >'.$fetch['productname'].'</option>';
		}
		// echo $products;
		// print_r($productcodes);
				
		//echo $procount;
		if(!in_array($productcode,$productcodes))
		{
			$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
			left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
			left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
			where inv_mas_scratchcard.attached = 'yes' and
			inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
			and inv_mas_scratchcard.blocked ='no' and inv_dealercard.dealerid = '".$dealerid."' and inv_mas_product.newproduct = 1 ORDER BY scratchnumber";
			$result = runmysqlquery($query);
		}
		else{
			$query = "SELECT inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber FROM inv_mas_scratchcard 
		left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
		left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
		where inv_mas_scratchcard.attached = 'yes' and
		inv_mas_scratchcard.registered = 'no' and (inv_dealercard.customerreference = '' or inv_dealercard.customerreference is null) 
		and inv_mas_scratchcard.blocked ='no' and inv_dealercard.dealerid = '".$dealerid."' and inv_mas_product.newproduct = 1".$products.$purtype." ORDER BY scratchnumber";
		$result = runmysqlquery($query);
		}
		
		$count = mysqli_num_rows($result);

		//$optiongrid .= '</select>';
		$responsearray3['count'] = $count;
		$responsearray3['optiongrid'] = $optiongrid;
		//echo $query;
		echo(json_encode($responsearray3));
	}
	break;
	
	case 'scratchdetailstoform':
	{
		$cardid = $_POST['cardid'];
		$query = "SELECT distinct inv_dealercard.cardid , inv_mas_scratchcard.scratchnumber, inv_mas_scratchcard.blocked,
inv_mas_scratchcard.cancelled,inv_mas_dealer.businessname as attachedto, inv_mas_dealer.slno as dealerid,
 inv_mas_product.productcode, inv_mas_product.productname, inv_dealercard.purchasetype, inv_dealercard.usagetype, 
inv_dealercard.date as attachdate,inv_dealercard.cuscardattacheddate as cuscardattacheddate, 
 inv_mas_customer.businessname as registeredto,inv_dealercard.cuscardremarks as cuscardremarks from inv_dealercard 
left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid 
left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno 
left join inv_mas_product on inv_dealercard.productcode = inv_mas_product.productcode 
left join inv_mas_customer on  inv_dealercard.customerreference = inv_mas_customer.slno where inv_dealercard.cardid = '".$cardid."';";
		$fetch = runmysqlqueryfetch($query);
		
		$attcheddate = substr($fetch['attachdate'] ,0,10);
		$registereddate =$fetch['registereddate'];
		if($registereddate != '')
			$registereddate = changedateformat($registereddate);
			
			
		if($fetch['blocked'] == 'yes')
		$cardstatus = 'Blocked';
		else if($fetch['cancelled'] == 'yes')
		$cardstatus = 'Cancelled';
		else
		{
		$cardstatus = 'Active';
		}
		echo('1^'.$fetch['cardid'].'^'.$fetch['scratchnumber'].'^'.$fetch['purchasetype'].'^'.$fetch['usagetype'].'^'.$fetch['attachedto'].'^'.$fetch['dealerid'].'^'.$fetch['productcode'].'^'.$fetch['productname'].'^'.changedateformat($attcheddate).'^'.''.'^'.''.'^'.$fetch['registeredto'].'^'.$cardstatus.'^'.changedateformatwithtime($fetch['cuscardattacheddate']).'^'.$fetch['cuscardremarks']);
		//echo($query);
	}
	break;
	
	case 'generategrid':
	{
		$lastslno = $_POST['lastslno'];
		$startlimit = $_POST['startlimit'];
		$slnocount = $_POST['slnocount'];
		$showtype = $_POST['showtype'];
		
		$query2 = "select * from inv_mas_customer where slno = '".$lastslno."'";
		$fetch2 = runmysqlqueryfetch($query2);
		$custidno = cusidcombine($fetch2['customerid']);
		
		
		$resultcount = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby where customerreference ='".$lastslno."' order by inv_dealercard.cuscardattacheddate;";
		$fetch10 = runmysqlquery($resultcount);
		$fetchresultcount = mysqli_num_rows($fetch10);
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
		$query = "select inv_mas_scratchcard.cardid,inv_mas_scratchcard.scratchnumber,inv_mas_dealer.businessname,inv_mas_product.productname,inv_dealercard.purchasetype,inv_dealercard.usagetype,inv_mas_scheme.schemename,inv_dealercard.cuscardattacheddate,inv_mas_users.fullname,inv_dealercard.cuscardremarks as remarks from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid =inv_mas_scratchcard.cardid left join inv_mas_dealer on inv_dealercard.dealerid = inv_mas_dealer.slno left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme left join inv_mas_users on inv_mas_users.slno = inv_dealercard.cuscardattachedby where customerreference ='".$lastslno."' order by inv_dealercard.cuscardattacheddate LIMIT ".$startlimit.",".$limit.";";
		if($startlimit == 0)
		{
			$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid">';
			$grid .= '<tr class="tr-grid-header" align="left"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Serial Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">PIN Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Dealer</td><td nowrap = "nowrap" class="td-border-grid" align="left">product</td><td nowrap = "nowrap" class="td-border-grid" align="left">Usage Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Purchase Type</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scheme</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Attached By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Remarks</td></tr>';
		}
		$i_n = 0;
		$result = runmysqlquery($query);
		while($fetch = mysqli_fetch_array($result))
		{
			$i_n++;
			$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch['cardid'].'\')" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['cardid']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['scratchnumber']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['businessname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['productname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['usagetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['purchasetype']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['schemename']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch['cuscardattacheddate'])."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['fullname']."</td>";
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$fetch['remarks']."</td>";
			$grid .= "</tr>";
		}
		
		/*while($fetch = mysqli_fetch_row($result))
		{
			$i_n++;$slnocount++;
			$color;
			if($i_n%2 == 0)
				$color = "#edf4ff";
			else
				$color = "#f7faff";
			$grid .= '<tr class="gridrow" bgcolor='.$color.' onclick="gridtoform(\''.$fetch[0].'\')" align="left">';
			$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount."</td>";
			for($i = 0; $i < count($fetch); $i++)
			{
				if($i == 8)
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".changedateformatwithtime($fetch[$i])."</td>";
				else
				$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".gridtrim($fetch[$i])."</td>";
				
			}
			$grid .= "</tr>";
		}*/
		$grid .= "</table>";
		if($slnocount >= $fetchresultcount)
			
		$linkgrid .='<table width="100%" border="0" cellspacing="0" cellpadding="0" height ="20px"><tr><td bgcolor="#FFFFD2"><font color="#FF4F4F">No More Records</font><div></div></td></tr></table>';
		else
		$linkgrid .= '<table><tr><td class="resendtext"><div align ="left" style="padding-right:10px"><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'more\');" style="cursor:pointer">Show More Records >></a><a onclick ="generatemordatagrid(\''.$startlimit.'\',\''.$slnocount.'\',\'all\');" class="resendtext1" style="cursor:pointer"><font color = "#000000">(Show All Records)</font></a></div></td></tr></table>';
			
		echo '1^'.$grid.'^'.$fetchresultcount.'^'.$linkgrid.'^'.$custidno;	
	}
	break;
	
	case 'gridtoform':
	{
		$customerreference = $_POST['customerreference'];
		$amclastslno = $_POST['amclastslno'];
		$query1 = "SELECT count(*) as count from inv_customeramc where customerreference = '".$customerreference."'";
		$fetch1 = runmysqlqueryfetch($query1);
		if($fetch1['count'] > 0)
		{
			$query = "SELECT inv_customeramc.slno,inv_customeramc.customerreference,inv_customeramc.productcode,inv_customeramc.startdate,inv_customeramc.enddate,inv_customeramc.remarks,inv_customeramc.createddate, inv_mas_users.fullname ,inv_customeramc.billno,inv_customeramc.billamount FROM inv_customeramc LEFT JOIN inv_mas_users On inv_customeramc.userid = inv_mas_users.slno WHERE inv_customeramc.customerreference = '".$customerreference."' AND inv_customeramc.slno = '".$amclastslno."';";
			$fetch = runmysqlqueryfetch($query);
			$startdate = $fetch['startdate']; 
			$enddate = $fetch['enddate']; 
			$todays_date = date("Y-m-d"); 
			$today = strtotime($todays_date); 
			$expiration_date1 = strtotime($startdate); 
			$expiration_date2 = strtotime($enddate); 
			if ($expiration_date1 > $today) { $msg = "Future"; } 
			elseif($expiration_date2 < $today) { $msg = "Expired"; }
			else { $msg = "Active"; }
			
			echo($fetch['slno'].'^'.$fetch['customerreference'].'^'.$fetch['productcode'].'^'.changedateformat($fetch['startdate']).'^'.changedateformat($fetch['enddate']).'^'.$fetch['remarks'].'^'.changedateformat($fetch['createddate']).'^'.$fetch['fullname'].'^'.$msg.'^'.$userid.'^'.$fetch['billno'].'^'.$fetch['billamount']);
		}
		else
		{
			echo(''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.''.'^'.'');
		}
	}
	break;
}

function sendfreecardemail($customerreference,$cardid)
{
		
		$query5 = "select inv_mas_customer.businessname,inv_mas_customer.customerid,
inv_mas_customer.place,inv_mas_customer.slno,
inv_mas_product.productname,inv_mas_scratchcard.scratchnumber as pinno,inv_mas_dealer.businessname as dealername
 from inv_dealercard left join inv_mas_scratchcard on inv_dealercard.cardid = inv_mas_scratchcard.cardid
 left join inv_mas_customer on inv_mas_customer.slno = inv_dealercard.customerreference 
left join inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode
left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
where inv_dealercard.customerreference = '".$customerreference."' and inv_dealercard.cardid = '".$cardid."';";
	$result = runmysqlqueryfetch($query5);
	
	// Fetch Contact Details
	$query1 ="SELECT slno,customerid,contactperson,selectiontype,phone,cell,emailid,slno from inv_contactdetails where customerid = '".$result['slno']."'; ";
	$resultfetch = runmysqlquery($query1);
	$valuecount = 0;
	while($fetchres = mysqli_fetch_array($resultfetch))
	{
		$contactperson = $fetchres['contactperson'];
		$emailid = $fetchres['emailid'];
		$contactvalues .= $contactperson;
		$contactvalues .= appendcomma($contactperson);
		$emailidres .= $emailid;
		$emailidres .= appendcomma($emailid);
		
	}
	$date = datetimelocal('d-m-Y');
	$businessname = $result['businessname'];
	$contactperson = trim($contactvalues,',');
	$customerslno = $result['slno'];
	$place = $result['place'];
	$customerid = $result['customerid'];
	$productname = $result['productname'];
	$pinno = $result['pinno'];
	$dealername = $result['dealername'];
	$emailid = trim($emailidres,',');
	
	if(($_SERVER['HTTP_HOST'] == "bhumika") ||  ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$emailid = 'bhumika.p@relyonsoft.com';
	}
	else
	{
		$emailid = $emailid;
	}
	$emailarray = explode(',',$emailid);
	$emailcount = count($emailarray);
	
	for($i = 0; $i < $emailcount; $i++)
	{
		if(checkemailaddress($emailarray[$i]))
		{
				$emailids[$emailarray[$i]] = $emailarray[$i];
		}
	}
	
	$toarray = $emailids;
	$fromname = "Relyon";
	$fromemail = "imax@relyon.co.in";
	require_once("../inc/RSLMAIL_MAIL.php");
	$msg = file_get_contents("../mailcontents/dealerpincardattach.htm");
	$textmsg = file_get_contents("../mailcontents/dealerpincardattach.txt");
	$date = datetimelocal('d-m-Y');
	$array = array();
	$array[] = "##DATE##%^%".$date;
	$array[] = "##NAME##%^%".$contactperson;
	$array[] = "##COMPANY##%^%".$businessname;
	$array[] = "##PLACE##%^%".$place;
	$array[] = "##CUSTOMERID##%^%".cusidcombine($customerid);
	$array[] = "##PRODUCTNAME##%^%".$productname;
	$array[] = "##SCRATCHCARDNO##%^%".$pinno;
	$array[] = "##CARDID##%^%".$cardid;
	$array[] = "##DEALERNAME##%^%".$dealername;
	$array[] = "##EMAILID##%^%".$emailid;
	
	$filearray = array(
		array('../images/relyon-logo.jpg','inline','8888888888'),
	);
	if(($_SERVER['HTTP_HOST'] == "bhumika") || ($_SERVER['HTTP_HOST'] == "192.168.2.79"))
	{
		$bccemailids['bhumika'] ='bhumika.p@relyonsoft.com';
	}
	else
	{
		$bccemailids['Relyonimax'] ='relyonimax@gmail.com';
		$bccemailids['bigmail'] ='bigmail@relyonsoft.com';
	}
	//$bccemailids['bigmail'] ='meghana.b@relyonsoft.com';
	$bccarray = $bccemailids;
	$msg = replacemailvariable($msg,$array);
	$textmsg = replacemailvariable($textmsg,$array);
	$subject = "You have been issued with a PIN Number for ".$productname." registration.";
	$html = $msg;
	$text = $textmsg;
	$replyto = 'support@relyonsoft.com';
	rslmail($fromname, $fromemail, $toarray, $subject, $text, $html,null,$bccarray,$filearray,$replyto); 
	
	//Insert the mail forwarded details to the logs table
	inserttologs(imaxgetcookie('dealeruserid'),$customerslno,$fromname,$fromemail,$emailid,null,$bccmailid ,$subject);
						
}

function generatecustomerid($customerreference,$productcode,$delaerrep)
{
	$query = "SELECT * FROM inv_mas_customer where slno = '".$customerreference."'";
	$fetch = runmysqlqueryfetch($query);
	$district = $fetch['district'];
	$query = runmysqlqueryfetch("SELECT distinct statecode from inv_mas_district where districtcode = '".$district."'");
	$cusstatecode = $query['statecode'];
	$newcustomerid = $cusstatecode.$district.$delaerrep.$productcode.$customerreference;
	return $newcustomerid;
}

function checkattchdetails($attacheddate,$customerreference,$description,$remarks,$scratchcard,$dealerid,$userid)
{
		#Added on 06.03.2018

	//echo "card" . $scratchcard; exit;
		
		// $selected_sub_dealer = "";
		
		// $select_sub_dealer = "select sub_dealer from inv_dealercard where cardid = '".$cardid."'";
		// $result_sub_dealer = runmysqlquery($select_sub_dealer);
		
		// while($fetch_sub_dealer = mysqli_fetch_array($result_sub_dealer)) {
		//    $selected_sub_dealer =  $fetch_sub_dealer['sub_dealer'];
		// }
		
		#Added on 06.03.2018 Ends	
		
		$query2 = "select * from inv_mas_customer where slno = '".$customerreference."'";
		$fetch2 = runmysqlqueryfetch($query2);
		if($fetch2['customerid']!= "")     
		{
		   $custidno = cusidcombine($fetch2['customerid']);
		}
		else
		{
			$custidno = $fetch2['customerid'];
		}
		
		if($custidno == "")
		{
			
			$query23 = "Select productcode, cardid, dealerid from inv_dealercard where cardid ='".$scratchcard."' and dealerid = '".$dealerid."'; ";
			$fetch23 = runmysqlqueryfetch($query23);
			$delaerrep = $fetch23['dealerid'];
			$productcode = $fetch23['productcode'];
			
			$newcustomerid = generatecustomerid($customerreference,$productcode,$delaerrep);
			$password = generatepwd();
			
			// updating new customerid
			$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."', firstproduct ='".$productcode."',firstdealer ='".$delaerrep."',passwordchanged = 'N' WHERE slno = '".$customerreference."'";
			$result = runmysqlquery($query14); 
			
			//inserting in log
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			$eventresult = runmysqlquery($eventquery);
			$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
			$custidno = cusidcombine($newcustomerid);
			
			// Updating into dealercard
			$query = "Update inv_dealercard set customerreference = '".$customerreference."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '".$userid."', usertype = 'user' where cardid = '".$scratchcard."' ";
			$result = runmysqlquery($query);
			
			//Fetching deatils from multiple table to insert into description (format slno$PN$PT$UT$PIN$CRDID$amt)
		
			
			//insert into inv_invoicenumbers_dummy_regv2
			$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) values('".$dealerid."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$scratchcard."')";
			
			// if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
   //  		//insert into inv_invoicenumbers_dummy_regv2
   //  		$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) 
   //  		values('".$selected_sub_dealer."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
			// }			
			$eventresult = runmysqlquery($regvquery);
			
			// inserting Log
			$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','52','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
			
			// if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
   //  		// inserting Log
   //  		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
   //  		values ('".$selected_sub_dealer."','".$_SERVER['REMOTE_ADDR']."','52','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')"; 
			// }  
					
			$eventresult = runmysqlquery($eventquery);
			
			//sending mail 
			$customerslno = $customerreference;
			sendwelcomeemail($customerslno,$userid);
			sendfreecardemail($customerreference,$scratchcard);
		}
		else
		{
			if($scratchcard!= 0)
			{
				//echo  $scratchcard ;
				//exit;
				$query15 = "Update inv_dealercard set customerreference = '".$customerreference."' ,cuscardattacheddate = '".$attacheddate."' ,cuscardremarks = '".$remarks."' ,cuscardattachedby = '".$userid."', usertype = 'user' where cardid = '".$scratchcard."' ";
				$result15 = runmysqlquery($query15);
				
				
				//insert into inv_invoicenumbers_dummy_regv2
				$regvquery1 = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) values('".$dealerid."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$scratchcard."')";
				$regvqueryresult = runmysqlquery($regvquery1);
				
				// if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
		    
    // 				//insert into inv_invoicenumbers_dummy_regv2
    // 				$regvquery = "Insert into inv_invoicenumbers_dummy_regv2(dealerid,customerid,date,description,cardid) 
    // 				values ('".$selected_sub_dealer."','".$custidno."','".date('Y-m-d').' '.date('H:i:s')."','".$description."','".$cardid."')";
		    
				// 	}					
				
				
				$eventquery1 = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','52','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
				$eventresult1 = runmysqlquery($eventquery1);
				
				// if(!is_null($selected_sub_dealer) && $selected_sub_dealer != '') {
		    
    // 				$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) 
    // 			values ('".$selected_sub_dealer."','".$_SERVER['REMOTE_ADDR']."','52','".date('Y-m-d').' '.date('H:i:s')."','".$customerreference."')";
		 
				// } 					
				
				sendfreecardemail($customerreference,$scratchcard);
				
		       echo(json_encode('1^'.'Card Attached Successfully'));
			}
			else
			{
				echo(json_encode('1^'.'Something went wrong please try to attach card again.'));
			}
		}
		
}
?>