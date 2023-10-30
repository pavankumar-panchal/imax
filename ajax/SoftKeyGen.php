<?php
include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
if(imaxgetcookie('userid')<> '') 
$userid = imaxgetcookie('userid');
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}
$custID=$_POST['customerid'];
$computerid=$_POST['computerid'];
## Edited by Bhavesh Patel##
$PINO=$_POST['pin'];
$PIN_array = explode(" | ", $PINO);
$PINARR = $PIN_array[0];
$PIN = $PINARR;
if(strlen($PIN) <> 14)
{ echo "2^"."Pin number format is not valid."; }

$cardid = $PIN_array[1];
//echo "this is card id ". $cardid;

## End Of Editing By Bhavesh Patel ##

//$acccode=$_POST['acccode'];
//$strPrdCode=$_POST['prdid'];
//$strAccessCodes=$computerid;
//$strAccessCodes=$strPrdCode.$acccode;
/*echo '<br>' . $custID;
echo '<br>' . $PIN;
echo '<br>' . $strAccessCodes;*/

if ($custID == "")
{
	echo "Customer ID is Not Generated";
}
else
{
				//$delaerrep = $_POST['delaerrep']; /* Dealer ID*/
				//$productcode = $_POST['productcode'];
				//$searchdelaerrep = $_POST['searchdelaerrep'];/* Dealer Name*/
				$date = datetimelocal('d-m-Y');
				$systemip = '';
				
				$computeridformat = substr($computerid, 5, 1);
				$computeridlength = strlen($computerid);
				if($computeridlength <> 11)
				{ echo  "2^"."Computer Id format is not valid digit."; }
				elseif($computeridformat <> '-')
				{ echo "2^"."Computer Id format is not valid."; }
				
				else
				{
					$errorfound = "";
					//Card Number present in Database
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and blocked = 'yes'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						echo '2 ^Pin is blocked';
					}
					
					if($errorfound = "")
					{
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and attached = 'yes' and blocked = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						echo '2 ^Invalid pin';
					}
								
					if($errorfound == "")
					{
						//Card No is not registered
						$query ="select * from inv_mas_scratchcard where cardid = '".$cardid."' and registered = 'no'";
						$result = runmysqlquery($query);
						if(mysqli_num_rows($result) == 0)
						echo'2^ This pin is already registered';
					}

					if($errorfound == "")
					{
						
						
						//$softkey  = generatesoftkeydummy($computerid, $prdtype);
						$query = "UPDATE inv_mas_scratchcard SET registered = 'yes', blocked = 'no', online = 'no', cancelled = 'no' WHERE cardid = '".$cardid."';";
						$result = runmysqlquery($query);
					
						//If this is the first registration for this customer, generate customerid, update it to customer master and send welcome email for future reference can get the function for creating customer ID  
						
						/*$sendcustomeridpassword = "";
						$query22 = "Select * from inv_mas_customer where slno ='".$custID."' and customerid = ''; ";
						$fetchresult = runmysqlquery($query22);
						if(mysqli_num_rows($fetchresult) <> 0)
						{
							$query1 = "Select * from inv_customerproduct where customerreference = '".$custID."'";
							$result = runmysqlquery($query1);
							if(mysqli_num_rows($result) == 0)
							{
								$newcustomerid = generatecustomerid($custID,$productcode,$delaerrep);
								$password = generatepwd();
								$query14 = "UPDATE inv_mas_customer SET customerid = '".$newcustomerid."',loginpassword = AES_ENCRYPT('".$password."','imaxpasswordkey'),initialpassword = '".$password."', firstproduct ='".$productcode."',firstdealer ='".$delaerrep."',passwordchanged = 'N' WHERE slno = '".$custID."'";
								$result = runmysqlquery($query14); 
								$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','42','".date('Y-m-d').' '.date('H:i:s')."','".$custID."')";
								$eventresult = runmysqlquery($eventquery);
								$sendcustomeridpassword = cusidcombine($newcustomerid)."%".$password;
								sendwelcomeemail($custID,$userid);
							}
						}*/
							
							$custID_array = explode("-", $custID);
							$custrefno = $custID_array[3];
							#echo "custID_array[3] ".$custrefno;
					
						// Generating SoftKey for Customer
						$output=GeneratePrdID($custID, $PIN, $computerid);
						#echo "1^Softkey : ". $output . " ## " . $custID. " ## " . $PIN. " ## " . $computerid .softKey($output); 
						$softkey  = softKey($output);
						// checking dealer ID 
						$query1 = "select inv_mas_scratchcard.scratchnumber, inv_dealercard.dealerid as dlrid, inv_dealercard.productcode, inv_dealercard.scheme as cardscheme 
from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
where inv_mas_scratchcard.cardid = '".$cardid."'";

						$fetch = runmysqlqueryfetch($query1);
						$delaerrep = $fetch['dlrid'];
						
						//Find the next slno to be inserted
						$query = "SELECT (MAX(slno) + 1) AS newslno FROM inv_customerproduct";
						$fetch = runmysqlqueryfetch($query);
						$customerproductslno = $fetch['newslno'];
						$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module,purchasetype,HDDID,ETHID,REGTYPE,COMPUTERNAME,COMPUTERIP,CREATEDBY,AUTOREGISTRATIONYN,ACTIVELICENSE) VALUES('".$customerproductslno."','".$custrefno."','".$cardid."','".$computerid."','".$softkey."','','','','".$delaerrep."','2','Web','".gmdate("Y-m-d", time()+$offset)."','".gmdate("H:i(worry)", time()+$offset)."','','no','','user_module','','".$output."','','','','','','Y','1')";
						//(SELECT purchasetype from inv_dealercard where cardid = '".$cardid."')
						#$query = "INSERT INTO inv_customerproduct(slno,customerreference,cardid,computerid,softkey,cusbillnumber,billnumber,billamount,dealerid,generatedby,system,date,time,remarks,reregistration,`type`,module,purchasetype,HDDID,ETHID,REGTYPE,COMPUTERNAME,COMPUTERIP,CREATEDBY,AUTOREGISTRATIONYN,ACTIVELICENSE) VALUES('".$customerproductslno."','".$custID."','".$cardid."','".$computerid."','".$softkey."','','','','".$delaerrep."','".$userid."','".$_SERVER['REMOTE_ADDR']."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','','no','newlicence','user_module');";
						$result = runmysqlquery($query);
						
						/*//Get the dealer Branch and Region
						$query111 = "select branch,region from inv_mas_dealer where slno = '".$delaerrep."';";
						$fetchresult111 = runmysqlqueryfetch($query111);
						$branchid = $fetchresult111['branch'];
						$regionid = $fetchresult111['region'];
						
						$query = "UPDATE inv_mas_customer SET currentdealer = '".$delaerrep."',activecustomer ='yes',branch = '".$branchid."',region = '".$regionid."' WHERE slno = '".$custID."'";
						$result = runmysqlquery($query); 
						
						sendregistrationemail($customerproductslno,$userid);*/
						
						$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('2','".$_SERVER['REMOTE_ADDR']."','44','".date('Y-m-d').' '.date('H:i:s')."','".$custrefno."')";
						$eventresult = runmysqlquery($eventquery);

						
						$query = "INSERT INTO inv_logs_softkeygen(generatedby,customerref,cardno,computerid,softkey,`date`,time,system,module) VALUES('".$userid."','".$custrefno."','".$cardid."','".$computerid."','".$softkey."','".datetimelocal('Y-m-d')."','".datetimelocal('H:i:s')."','".$_SERVER['REMOTE_ADDR']."','user_module');";
						$result = runmysqlquery($query);

							#$output=GeneratePrdID($custID, $PIN, $computerid);
							//echo "output : ". $output;
							//echo "Thinking to redirect";
							#echo "1^Softkey : ".$softkey;

						echo "1^Softkey is: ".$softkey;
					}
					
			}
}
			
/*$output=GeneratePrdID($custID, $PIN, $computerid);
//echo "output : ". $output;
//echo "Thinking to redirect";
echo "1^Softkey : ".softKey($output);*/

## End Of Editing Bhavesh Patel ##


function GeneratePrdID($custID, $PIN, $strAccessCodes)
{

	$result= "";
    $intPos= 0;
	if (strlen($custID) == 20)
	{
		$custID = str_replace('-','',$custID);
	}
	
	if (strlen($PIN) == 14)
	{
		$PIN = str_replace('-','',$PIN);
	}
	
	if (strlen($strAccessCodes) == 11)
	{
		$strAccessCodes = str_replace('-','',$strAccessCodes);
	}
	
	
	
	if (strlen($custID) != 17)

        return "";
		
	
	if (strlen($PIN) != 12)

        return "";
		
    if (strlen($strAccessCodes) != 10)

        return "";

        
    $result = substr($strAccessCodes, 5, 4);
    $result = $result . UniqueSerial($custID);
    $result = $result . UniqueSerial($PIN);
    $result = $result . UniqueSerial(substr($strAccessCodes, 0, 5));
    
	$intPos = intval(BaseDigit($result, 17));

    switch ($intPos)
{
	case 1:
        $result = substr($result,10,1) . substr($result,9,1) . substr($result,5,1) . substr($result,12,1) . substr($result,6,1) . substr($result,13,1) . substr($result,8,1) . substr($result,4,1) . substr($result,0,1) . substr($result,15,1) . substr($result,3,1) . substr($result,7,1) . substr($result,2,1) . substr($result,14,1) . substr($result,1,1) . substr($result,11,1);
break;
case 2:
$result = substr($result,1,1) . substr($result,15,1) . substr($result,12,1) . substr($result,2,1) . substr($result,0,1) . substr($result,4,1) . substr($result,14,1) 

. substr($result,10,1) . substr($result,6,1) . substr($result,5,1) . substr($result,9,1) . substr($result,8,1) . substr($result,13,1) . substr($result,11,1) . 

substr($result,7,1) . substr($result,3,1);
break;
case 3:
$result = substr($result,13,1) . substr($result,12,1) . substr($result,8,1) . substr($result,15,1) . substr($result,9,1) . substr($result,0,1) . substr($result,11,1) . substr($result,7,1) . substr($result,3,1) . substr($result,2,1) . substr($result,6,1) . substr($result,10,1) . substr($result,4,1) . substr($result,5,1) . substr($result,14,1) . substr($result,1,1);
break;

case 4:
$result = substr($result,6,1) . substr($result,5,1) . substr($result,1,1) . substr($result,7,1) . substr($result,4,1) . substr($result,2,1) . substr($result,9,1) . substr($result,0,1) . substr($result,11,1) . substr($result,15,1) . substr($result,3,1) . substr($result,13,1) . substr($result,14,1) . substr($result,8,1) . substr($result,12,1) . substr($result,10,1);
break;

case 5:
$result = substr($result,15,1) . substr($result,13,1) . substr($result,10,1) . substr($result,0,1) . substr($result,2,1) . substr($result,12,1) . substr($result,8,1) . substr($result,4,1) . substr($result,3,1) . substr($result,7,1) . substr($result,6,1) . substr($result,11,1) . substr($result,5,1) . substr($result,9,1) . substr($result,14,1) . substr($result,1,1);
break;

case 6:
$result = substr($result,12,1) . substr($result,11,1) . substr($result,8,1) . substr($result,14,1) . substr($result,0,1) . substr($result,10,1) . substr($result,6,1) . substr($result,2,1) . substr($result,1,1) . substr($result,5,1) . substr($result,4,1) . substr($result,9,1) . substr($result,13,1) . substr($result,3,1) . substr($result,7,1) . substr($result,15,1);
break;

case 7:
$result = substr($result,0,1) . substr($result,15,1) . substr($result,11,1) . substr($result,1,1) . substr($result,14,1) . substr($result,12,1) . substr($result,3,1) . substr($result,10,1) . substr($result,6,1) . substr($result,5,1) . substr($result,9,1) . substr($result,13,1) . substr($result,7,1) . substr($result,8,1) . substr($result,2,1) . substr($result,4,1);
break;

case 8:
$result = substr($result,3,1) . substr($result,2,1) . substr($result,15,1) . substr($result,5,1) . substr($result,7,1) . substr($result,1,1) . substr($result,13,1) . substr($result,9,1) . substr($result,8,1) . substr($result,12,1) . substr($result,11,1) . substr($result,0,1) . substr($result,4,1) . substr($result,14,1) . substr($result,10,1) . substr($result,6,1);
break;

case 9:
$result = substr($result,2,1) . substr($result,1,1) . substr($result,13,1) . substr($result,4,1) . substr($result,14,1) . substr($result,5,1) . substr($result,0,1) . substr($result,12,1) . substr($result,8,1) . substr($result,7,1) . substr($result,11,1) . substr($result,15,1) . substr($result,10,1) . substr($result,3,1) . substr($result,9,1) . substr($result,6,1);
break;

case 10:
$result = substr($result,14,1) . substr($result,13,1) . substr($result,10,1) . substr($result,0,1) . substr($result,2,1) . substr($result,12,1) . substr($result,8,1) . substr($result,4,1) . substr($result,3,1) . substr($result,7,1) . substr($result,6,1) . substr($result,11,1) . substr($result,15,1) . substr($result,5,1) . substr($result,9,1) . substr($result,1,1);
break;

case 11:
$result = substr($result,4,1) . substr($result,3,1) . substr($result,15,1) . substr($result,6,1) . substr($result,0,1) . substr($result,7,1) . substr($result,2,1) . substr($result,14,1) . substr($result,10,1) . substr($result,9,1) . substr($result,13,1) . substr($result,1,1) . substr($result,12,1) . substr($result,5,1) . substr($result,11,1) . substr($result,8,1);
break;

case 12:
$result = substr($result,5,1) . substr($result,7,1) . substr($result,2,1) . substr($result,13,1) . substr($result,3,1) . substr($result,15,1) . substr($result,10,1) . substr($result,11,1) . substr($result,8,1) . substr($result,12,1) . substr($result,0,1) . substr($result,6,1) . substr($result,4,1) . substr($result,14,1) . substr($result,1,1) . substr($result,9,1);
break;

case 13:
$result = substr($result,7,1) . substr($result,6,1) . substr($result,3,1) . substr($result,9,1) . substr($result,11,1) . substr($result,5,1) . substr($result,1,1) . substr($result,13,1) . substr($result,12,1) . substr($result,0,1) . substr($result,15,1) . substr($result,4,1) . substr($result,8,1) . substr($result,2,1) . substr($result,14,1) . substr($result,10,1);
break;

case 14:
$result = substr($result,8,1) . substr($result,7,1) . substr($result,3,1) . substr($result,9,1) . substr($result,6,1) . substr($result,4,1) . substr($result,11,1) . substr($result,2,1) . substr($result,14,1) . substr($result,13,1) . substr($result,1,1) . substr($result,5,1) . substr($result,15,1) . substr($result,0,1) . substr($result,10,1) . substr($result,12,1);
break;

case 15:
$result = substr($result,11,1) . substr($result,10,1) . substr($result,7,1) . substr($result,13,1) . substr($result,15,1) . substr($result,9,1) . substr($result,5,1) . substr($result,1,1) . substr($result,0,1) . substr($result,4,1) . substr($result,3,1) . substr($result,12,1) . substr($result,8,1) . substr($result,14,1) . substr($result,6,1) . substr($result,2,1);
break;

default:
$result = substr($result,9,1) . substr($result,7,1) . substr($result,4,1) . substr($result,10,1) . substr($result,12,1) . substr($result,3,1) . substr($result,14,1) . substr($result,2,1) . substr($result,6,1) . substr($result,0,1) . substr($result,1,1) . substr($result,15,1) . substr($result,8,1) . substr($result,5,1) . substr($result,13,1) . substr($result,11,1);
break;
}
	return ($result . substr($strAccessCodes, 9) . ((strlen($intPos) == 1) ? '0'.$intPos:$intPos));
}

function UniqueSerial($strPrdCode)
{
    $intTot1=0; $intTot2=0; $intTot3=0; $intI=1;
	
    $intLen=0; $intTot4=0;$intTmpAsc=0;
	
	$output = "";
    $intLen = strlen($strPrdCode);
    while ($intI <= strlen($strPrdCode))
	{
        
		$intTmpAsc = ord(substr($strPrdCode, ($intI-1), 1)) + $intI;
		$intTot1 = $intTot1 + $intTmpAsc;
        if ($intI % 2 == 0)
		{
            $intTot2 = $intTot2 + $intTmpAsc;
		}
        else
		{
            $intTot3 = $intTot3 + $intTmpAsc;
	}
        $intTot4 = $intTot4 + (($intTmpAsc) ^ ($intLen * $intI));
        $intI = $intI + 1;
	}
	$output = BaseDigit($intTot1);
    $output = $output . ($intTot2 % 10);
    $output = $output . ($intTot3 % 10);
    $output = $output . ($intTot4 % 10);
	return $output;
}

function BaseDigit($strTot, $Base = 10)
{
    $intI=1;
	$result = 0;
    if (strlen($strTot) == 0 || intval($strTot) == 0)
	{
        return 3;
	}
    else if (strlen($strTot) == 1)
	{
        $result = $strTot;
        return $result;
	}
    while ($intI <= strlen($strTot))
	{
        $result = ($result) + intval(substr($strTot, ($intI-1), 1));
        $intI = $intI + 1;
	}
	return ($result % $Base);
}

function FindUN($strInput)
{
	$result = "";
	switch ($strInput)
	{
		
case "001": $result = "908";break;
case "002": $result = "825";break;
case "003": $result = "605";break;
case "004": $result = "001";break;
case "005": $result = "814";break;
case "006": $result = "637";break;
case "007": $result = "904";break;
case "008": $result = "103";break;
case "009": $result = "772";break;
case "010": $result = "765";break;
case "011": $result = "520";break;
case "012": $result = "257";break;
case "013": $result = "780";break;
case "014": $result = "210";break;
case "015": $result = "451";break;
case "016": $result = "726";break;
case "017": $result = "370";break;
case "018": $result = "705";break;
case "019": $result = "434";break;
case "020": $result = "513";break;
case "021": $result = "011";break;
case "022": $result = "782";break;
case "023": $result = "733";break;
case "024": $result = "616";break;
case "025": $result = "968";break;
case "026": $result = "910";break;
case "027": $result = "349";break;
case "028": $result = "769";break;
case "029": $result = "242";break;
case "030": $result = "355";break;
case "031": $result = "281";break;
case "032": $result = "505";break;
case "033": $result = "566";break;
case "034": $result = "850";break;
case "035": $result = "263";break;
case "036": $result = "291";break;
case "037": $result = "473";break;
case "038": $result = "662";break;
case "039": $result = "562";break;
case "040": $result = "129";break;
case "041": $result = "430";break;
case "042": $result = "790";break;
case "043": $result = "178";break;
case "044": $result = "547";break;
case "045": $result = "438";break;
case "046": $result = "235";break;
case "047": $result = "844";break;
case "048": $result = "018";break;
case "049": $result = "029";break;
case "050": $result = "996";break;
case "051": $result = "093";break;
case "052": $result = "804";break;
case "053": $result = "669";break;
case "054": $result = "808";break;
case "055": $result = "391";break;
case "056": $result = "641";break;
case "057": $result = "626";break;
case "058": $result = "936";break;
case "059": $result = "007";break;
case "060": $result = "061";break;
case "061": $result = "900";break;
case "062": $result = "381";break;
case "063": $result = "673";break;
case "064": $result = "530";break;
case "065": $result = "225";break;
case "066": $result = "876";break;
case "067": $result = "921";break;
case "068": $result = "317";break;
case "069": $result = "865";break;
case "070": $result = "953";break;
case "071": $result = "221";break;
case "072": $result = "419";break;
case "073": $result = "822";break;
case "074": $result = "082";break;
case "075": $result = "836";break;
case "076": $result = "573";break;
case "077": $result = "097";break;
case "078": $result = "526";break;
case "079": $result = "502";break;
case "080": $result = "043";break;
case "081": $result = "686";break;
case "082": $result = "022";break;
case "083": $result = "750";break;
case "084": $result = "829";break;
case "085": $result = "327";break;
case "086": $result = "833";break;
case "087": $result = "050";break;
case "088": $result = "932";break;
case "089": $result = "285";break;
case "090": $result = "227";break;
case "091": $result = "665";break;
case "092": $result = "086";break;
case "093": $result = "558";break;
case "094": $result = "406";break;
case "095": $result = "331";break;
case "096": $result = "556";break;
case "097": $result = "882";break;
case "098": $result = "167";break;
case "099": $result = "579";break;
case "100": $result = "342";break;
case "101": $result = "524";break;
case "102": $result = "978";break;
case "103": $result = "878";break;
case "104": $result = "445";break;
case "105": $result = "481";break;
case "106": $result = "107";break;
case "107": $result = "494";break;
case "108": $result = "598";break;
case "109": $result = "754";break;
case "110": $result = "552";break;
case "111": $result = "161";break;
case "112": $result = "334";break;
case "113": $result = "345";break;
case "114": $result = "313";break;
case "115": $result = "409";break;
case "116": $result = "854";break;
case "117": $result = "985";break;
case "118": $result = "125";break;
case "119": $result = "708";break;
case "120": $result = "957";break;
case "121": $result = "942";break;
case "122": $result = "253";break;
case "123": $result = "323";break;
case "124": $result = "377";break;
case "125": $result = "217";break;
case "126": $result = "697";break;
case "127": $result = "989";break;
case "128": $result = "846";break;
case "129": $result = "541";break;
case "130": $result = "193";break;
case "131": $result = "238";break;
case "132": $result = "633";break;
case "133": $result = "182";break;
case "134": $result = "270";break;
case "135": $result = "537";break;
case "136": $result = "470";break;
case "137": $result = "139";break;
case "138": $result = "398";break;
case "139": $result = "886";break;
case "140": $result = "889";break;
case "141": $result = "413";break;
case "142": $result = "577";break;
case "143": $result = "818";break;
case "144": $result = "359";break;
case "145": $result = "737";break;
case "146": $result = "338";break;
case "147": $result = "801";break;
case "148": $result = "146";break;
case "149": $result = "644";break;
case "150": $result = "150";break;
case "151": $result = "366";break;
case "152": $result = "249";break;
case "153": $result = "601";break;
case "154": $result = "278";break;
case "155": $result = "716";break;
case "156": $result = "402";break;
case "157": $result = "609";break;
case "158": $result = "722";break;
case "159": $result = "648";break;
case "160": $result = "872";break;
case "161": $result = "199";break;
case "162": $result = "483";break;
case "163": $result = "630";break;
case "164": $result = "658";break;
case "165": $result = "840";break;
case "166": $result = "295";break;
case "167": $result = "195";break;
case "168": $result = "761";break;
case "169": $result = "797";break;
case "170": $result = "423";break;
case "171": $result = "545";break;
case "172": $result = "914";break;
case "173": $result = "071";break;
case "174": $result = "868";break;
case "175": $result = "477";break;
case "176": $result = "385";break;
case "177": $result = "396";break;
case "178": $result = "363";break;
case "179": $result = "460";break;
case "180": $result = "171";break;
case "181": $result = "302";break;
case "182": $result = "441";break;
case "183": $result = "758";break;
case "184": $result = "274";break;
case "185": $result = "259";break;
case "186": $result = "569";break;
case "187": $result = "374";break;
case "188": $result = "428";break;
case "189": $result = "267";break;
case "190": $result = "748";break;
case "191": $result = "306";break;
case "192": $result = "897";break;
case "193": $result = "857";break;
case "194": $result = "509";break;
case "195": $result = "289";break;
case "196": $result = "684";break;
case "197": $result = "498";break;
case "198": $result = "321";break;
case "199": $result = "588";break;
case "200": $result = "786";break;
case "201": $result = "455";break;
case "202": $result = "449";break;
case "203": $result = "203";break;
case "204": $result = "206";break;
case "205": $result = "729";break;
case "206": $result = "893";break;
case "207": $result = "135";break;
case "208": $result = "676";break;
case "209": $result = "054";break;
case "210": $result = "654";break;
case "211": $result = "118";break;
case "212": $result = "462";break;
case "213": $result = "694";break;
case "214": $result = "466";break;
case "215": $result = "417";break;
case "216": $result = "299";break;
case "217": $result = "652";break;
case "218": $result = "594";break;
case "219": $result = "033";break;
case "220": $result = "718";break;
case "221": $result = "925";break;
case "222": $result = "039";break;
case "223": $result = "964";break;
case "224": $result = "189";break;
case "225": $result = "515";break;
case "226": $result = "534";break;
case "227": $result = "946";break;
case "228": $result = "974";break;
case "229": $result = "157";break;
case "230": $result = "612";break;
case "231": $result = "246";break;
case "232": $result = "812";break;
case "233": $result = "114";break;
case "234": $result = "740";break;
case "235": $result = "861";break;
case "236": $result = "231";break;
case "237": $result = "387";break;
case "238": $result = "185";break;
case "239": $result = "793";break;
case "240": $result = "701";break;
case "241": $result = "712";break;
case "242": $result = "680";break;
case "243": $result = "776";break;
case "244": $result = "488";break;
case "245": $result = "353";break;
case "246": $result = "492";break;
case "247": $result = "075";break;
case "248": $result = "590";break;
case "249": $result = "310";break;
case "250": $result = "620";break;
case "251": $result = "690";break;
case "252": $result = "744";break;
case "253": $result = "584";break;
case "254": $result = "065";break;
case "255": $result = "622";break;
case "256": $result = "214";break;
case "257": $result = "600";break;
case "258": $result = "688";break;
case "259": $result = "954";break;
case "260": $result = "154";break;
case "261": $result = "557";break;
case "262": $result = "816";break;
case "263": $result = "570";break;
case "264": $result = "308";break;
case "265": $result = "830";break;
case "266": $result = "261";break;
case "267": $result = "237";break;
case "268": $result = "777";break;
case "269": $result = "421";break;
case "270": $result = "756";break;
case "271": $result = "485";break;
case "272": $result = "564";break;
case "273": $result = "062";break;
case "274": $result = "568";break;
case "275": $result = "784";break;
case "276": $result = "666";break;
case "277": $result = "019";break;
case "278": $result = "961";break;
case "279": $result = "400";break;
case "280": $result = "820";break;
case "281": $result = "293";break;
case "282": $result = "141";break;
case "283": $result = "066";break;
case "284": $result = "290";break;
case "285": $result = "617";break;
case "286": $result = "901";break;
case "287": $result = "314";break;
case "288": $result = "077";break;
case "289": $result = "258";break;
case "290": $result = "713";break;
case "291": $result = "613";break;
case "292": $result = "180";break;
case "293": $result = "216";break;
case "294": $result = "841";break;
case "295": $result = "229";break;
case "296": $result = "333";break;
case "297": $result = "489";break;
case "298": $result = "286";break;
case "299": $result = "894";break;
case "300": $result = "069";break;
case "301": $result = "079";break;
case "302": $result = "047";break;
case "303": $result = "143";break;
case "304": $result = "589";break;
case "305": $result = "720";break;
case "306": $result = "858";break;
case "307": $result = "442";break;
case "308": $result = "692";break;
case "309": $result = "677";break;
case "310": $result = "986";break;
case "311": $result = "058";break;
case "312": $result = "111";break;
case "313": $result = "950";break;
case "314": $result = "432";break;
case "315": $result = "724";break;
case "316": $result = "581";break;
case "317": $result = "276";break;
case "318": $result = "926";break;
case "319": $result = "972";break;
case "320": $result = "368";break;
case "321": $result = "916";break;
case "322": $result = "005";break;
case "323": $result = "272";break;
case "324": $result = "205";break;
case "325": $result = "873";break;
case "326": $result = "133";break;
case "327": $result = "621";break;
case "328": $result = "624";break;
case "329": $result = "147";break;
case "330": $result = "312";break;
case "331": $result = "553";break;
case "332": $result = "094";break;
case "333": $result = "472";break;
case "334": $result = "073";break;
case "335": $result = "536";break;
case "336": $result = "880";break;
case "337": $result = "378";break;
case "338": $result = "884";break;
case "339": $result = "101";break;
case "340": $result = "982";break;
case "341": $result = "336";break;
case "342": $result = "013";break;
case "343": $result = "450";break;
case "344": $result = "137";break;
case "345": $result = "344";break;
case "346": $result = "457";break;
case "347": $result = "382";break;
case "348": $result = "606";break;
case "349": $result = "933";break;
case "350": $result = "218";break;
case "351": $result = "365";break;
case "352": $result = "393";break;
case "353": $result = "574";break;
case "354": $result = "030";break;
case "355": $result = "929";break;
case "356": $result = "496";break;
case "357": $result = "532";break;
case "358": $result = "158";break;
case "359": $result = "280";break;
case "360": $result = "649";break;
case "361": $result = "805";break;
case "362": $result = "602";break;
case "363": $result = "212";break;
case "364": $result = "120";break;
case "365": $result = "130";break;
case "366": $result = "098";break;
case "367": $result = "194";break;
case "368": $result = "905";break;
case "369": $result = "037";break;
case "370": $result = "175";break;
case "371": $result = "493";break;
case "372": $result = "009";break;
case "373": $result = "993";break;
case "374": $result = "304";break;
case "375": $result = "109";break;
case "376": $result = "162";break;
case "377": $result = "002";break;
case "378": $result = "482";break;
case "379": $result = "041";break;
case "380": $result = "632";break;
case "381": $result = "592";break;
case "382": $result = "244";break;
case "383": $result = "023";break;
case "384": $result = "418";break;
case "385": $result = "233";break;
case "386": $result = "056";break;
case "387": $result = "322";break;
case "388": $result = "521";break;
case "389": $result = "190";break;
case "390": $result = "184";break;
case "391": $result = "937";break;
case "392": $result = "940";break;
case "393": $result = "464";break;
case "394": $result = "628";break;
case "395": $result = "869";break;
case "396": $result = "410";break;
case "397": $result = "788";break;
case "398": $result = "389";break;
case "399": $result = "852";break;
case "400": $result = "197";break;
case "401": $result = "429";break;
case "402": $result = "201";break;
case "403": $result = "152";break;
case "404": $result = "034";break;
case "405": $result = "386";break;
case "406": $result = "329";break;
case "407": $result = "766";break;
case "408": $result = "453";break;
case "409": $result = "660";break;
case "410": $result = "773";break;
case "411": $result = "698";break;
case "412": $result = "922";break;
case "413": $result = "250";break;
case "414": $result = "269";break;
case "415": $result = "681";break;
case "416": $result = "709";break;
case "417": $result = "890";break;
case "418": $result = "346";break;
case "419": $result = "980";break;
case "420": $result = "546";break;
case "421": $result = "848";break;
case "422": $result = "474";break;
case "423": $result = "596";break;
case "424": $result = "965";break;
case "425": $result = "122";break;
case "426": $result = "918";break;
case "427": $result = "528";break;
case "428": $result = "436";break;
case "429": $result = "446";break;
case "430": $result = "414";break;
case "431": $result = "510";break;
case "432": $result = "222";break;
case "433": $result = "088";break;
case "434": $result = "226";break;
case "435": $result = "809";break;
case "436": $result = "325";break;
case "437": $result = "045";break;
case "438": $result = "354";break;
case "439": $result = "425";break;
case "440": $result = "478";break;
case "441": $result = "318";break;
case "442": $result = "798";break;
case "443": $result = "357";break;
case "444": $result = "948";break;
case "445": $result = "642";break;
case "446": $result = "560";break;
case "447": $result = "340";break;
case "448": $result = "734";break;
case "449": $result = "549";break;
case "450": $result = "372";break;
case "451": $result = "638";break;
case "452": $result = "837";break;
case "453": $result = "506";break;
case "454": $result = "500";break;
case "455": $result = "254";break;
case "456": $result = "990";break;
case "457": $result = "514";break;
case "458": $result = "944";break;
case "459": $result = "186";break;
case "460": $result = "461";break;
case "461": $result = "105";break;
case "462": $result = "440";break;
case "463": $result = "169";break;
case "464": $result = "248";break;
case "465": $result = "745";break;
case "466": $result = "517";break;
case "467": $result = "468";break;
case "468": $result = "350";break;
case "469": $result = "702";break;
case "470": $result = "645";break;
case "471": $result = "083";break;
case "472": $result = "504";break;
case "473": $result = "976";break;
case "474": $result = "090";break;
case "475": $result = "015";break;
case "476": $result = "239";break;
case "477": $result = "301";break;
case "478": $result = "585";break;
case "479": $result = "997";break;
case "480": $result = "026";break;
case "481": $result = "207";break;
case "482": $result = "397";break;
case "483": $result = "297";break;
case "484": $result = "862";break;
case "485": $result = "165";break;
case "486": $result = "525";break;
case "487": $result = "912";break;
case "488": $result = "282";break;
case "489": $result = "173";break;
case "490": $result = "969";break;
case "491": $result = "578";break;
case "492": $result = "752";break;
case "493": $result = "762";break;
case "494": $result = "730";break;
case "495": $result = "826";break;
case "496": $result = "538";break;
case "497": $result = "404";break;
case "498": $result = "542";break;
case "499": $result = "126";break;
case "500": $result = "376";break;
case "501": $result = "361";break;
case "502": $result = "670";break;
case "503": $result = "741";break;
case "504": $result = "794";break;
case "505": $result = "634";break;
case "506": $result = "115";break;
case "507": $result = "408";break;
case "508": $result = "265";break;
case "509": $result = "958";break;
case "510": $result = "610";break;
case "511": $result = "656";break;
case "512": $result = "051";break;
case "513": $result = "783";break;
case "514": $result = "623";break;
case "515": $result = "104";break;
case "516": $result = "947";break;
case "517": $result = "599";break;
case "518": $result = "040";break;
case "519": $result = "943";break;
case "520": $result = "296";break;
case "521": $result = "819";break;
case "522": $result = "983";break;
case "523": $result = "144";break;
case "524": $result = "208";break;
case "525": $result = "655";break;
case "526": $result = "008";break;
case "527": $result = "123";break;
case "528": $result = "016";break;
case "529": $result = "055";break;
case "530": $result = "279";break;
case "531": $result = "247";break;
case "532": $result = "168";break;
case "533": $result = "204";break;
case "534": $result = "951";break;
case "535": $result = "275";break;
case "536": $result = "883";break;
case "537": $result = "791";break;
case "538": $result = "802";break;
case "539": $result = "770";break;
case "540": $result = "866";break;
case "541": $result = "847";break;
case "542": $result = "975";break;
case "543": $result = "834";break;
case "544": $result = "674";break;
case "545": $result = "155";break;
case "546": $result = "264";break;
case "547": $result = "915";break;
case "548": $result = "695";break;
case "549": $result = "091";break;
case "550": $result = "727";break;
case "551": $result = "994";break;
case "552": $result = "855";break;
case "553": $result = "136";break;
case "554": $result = "300";break;
case "555": $result = "823";break;
case "556": $result = "706";break;
case "557": $result = "059";break;
case "558": $result = "439";break;
case "559": $result = "332";break;
case "560": $result = "371";break;
case "561": $result = "595";break;
case "562": $result = "563";break;
case "563": $result = "219";break;
case "564": $result = "268";break;
case "565": $result = "591";break;
case "566": $result = "200";break;
case "567": $result = "108";break;
case "568": $result = "119";break;
case "569": $result = "087";break;
case "570": $result = "183";break;
case "571": $result = "759";break;
case "572": $result = "898";break;
case "573": $result = "027";break;
case "574": $result = "151";break;
case "575": $result = "471";break;
case "576": $result = "315";break;
case "577": $result = "232";break;
case "578": $result = "012";break;
case "579": $result = "407";break;
case "580": $result = "044";break;
case "581": $result = "311";break;
case "582": $result = "179";break;
case "583": $result = "172";break;
case "584": $result = "663";break;
case "585": $result = "187";break;
case "586": $result = "112";break;
case "587": $result = "919";break;
case "588": $result = "140";break;
case "589": $result = "375";break;
case "590": $result = "755";break;
case "591": $result = "176";break;
case "592": $result = "687";break;
case "593": $result = "911";break;
case "594": $result = "879";break;
case "595": $result = "535";break;
case "596": $result = "251";break;
case "597": $result = "424";break;
case "598": $result = "435";break;
case "599": $result = "403";break;
case "600": $result = "499";break;
case "601": $result = "211";break;
case "602": $result = "076";break;
case "603": $result = "215";break;
case "604": $result = "048";break;
case "605": $result = "343";break;
case "606": $result = "467";break;
case "607": $result = "307";break;
case "608": $result = "787";break;
case "609": $result = "080";break;
case "610": $result = "631";break;
case "611": $result = "283";break;
case "612": $result = "328";break;
case "613": $result = "723";break;
case "614": $result = "360";break;
case "615": $result = "627";break;
case "616": $result = "243";break;
case "617": $result = "979";break;
case "618": $result = "503";break;
case "619": $result = "236";break;
case "620": $result = "240";break;
case "621": $result = "456";break;
case "622": $result = "339";break;
case "623": $result = "691";break;
case "624": $result = "072";break;
case "625": $result = "738";break;
case "626": $result = "962";break;
case "627": $result = "930";break;
case "628": $result = "851";break;
case "629": $result = "887";break;
case "630": $result = "567";break;
case "631": $result = "751";break;
case "632": $result = "719";break;
case "633": $result = "815";break;
case "634": $result = "392";break;
case "635": $result = "531";break;
case "636": $result = "364";break;
case "637": $result = "659";break;
case "638": $result = "891";break;
case "639": $result = "970";break;
case "640": $result = "191";break;
case "641": $result = "426";break;
case "642": $result = "806";break;
case "643": $result = "699";break;
case "644": $result = "696";break;
case "645": $result = "024";break;
case "646": $result = "664";break;
case "647": $result = "020";break;
case "648": $result = "586";break;
case "649": $result = "635";break;
case "650": $result = "739";break;
case "651": $result = "895";break;
case "652": $result = "475";break;
case "653": $result = "486";break;
case "654": $result = "454";break;
case "655": $result = "550";break;
case "656": $result = "995";break;
case "657": $result = "127";break;
case "658": $result = "266";break;
case "659": $result = "099";break;
case "660": $result = "084";break;
case "661": $result = "394";break;
case "662": $result = "518";break;
case "663": $result = "358";break;
case "664": $result = "838";break;
case "665": $result = "131";break;
case "666": $result = "987";break;
case "667": $result = "682";break;
case "668": $result = "379";break;
case "669": $result = "774";break;
case "670": $result = "411";break;
case "671": $result = "678";break;
case "672": $result = "611";break;
case "673": $result = "539";break;
case "674": $result = "028";break;
case "675": $result = "031";break;
case "676": $result = "554";break;
case "677": $result = "959";break;
case "678": $result = "479";break;
case "679": $result = "287";break;
case "680": $result = "507";break;
case "681": $result = "390";break;
case "682": $result = "742";break;
case "683": $result = "856";break;
case "684": $result = "543";break;
case "685": $result = "863";break;
case "686": $result = "771";break;
case "687": $result = "799";break;
case "688": $result = "902";break;
case "689": $result = "938";break;
case "690": $result = "618";break;
case "691": $result = "443";break;
case "692": $result = "582";break;
case "693": $result = "899";break;
case "694": $result = "415";break;
case "695": $result = "710";break;
case "696": $result = "888";break;
case "697": $result = "447";break;
case "698": $result = "998";break;
case "699": $result = "650";break;
case "700": $result = "824";break;
case "701": $result = "639";break;
case "702": $result = "728";break;
case "703": $result = "927";break;
case "704": $result = "347";break;
case "705": $result = "870";break;
case "706": $result = "035";break;
case "707": $result = "795";break;
case "708": $result = "603";break;
case "709": $result = "835";break;
case "710": $result = "607";break;
case "711": $result = "792";break;
case "712": $result = "735";break;
case "713": $result = "174";break;
case "714": $result = "859";break;
case "715": $result = "067";break;
case "716": $result = "330";break;
case "717": $result = "675";break;
case "718": $result = "116";break;
case "719": $result = "298";break;
case "720": $result = "952";break;
case "721": $result = "255";break;
case "722": $result = "003";break;
case "723": $result = "934";break;
case "724": $result = "842";break;
case "725": $result = "731";break;
case "726": $result = "760";break;
case "727": $result = "831";break;
case "728": $result = "763";break;
case "729": $result = "966";break;
case "730": $result = "746";break;
case "731": $result = "955";break;
case "732": $result = "778";break;
case "733": $result = "906";break;
case "734": $result = "920";break;
case "735": $result = "351";break;
case "736": $result = "867";break;
case "737": $result = "511";break;
case "738": $result = "575";break;
case "739": $result = "923";break;
case "740": $result = "874";break;
case "741": $result = "052";break;
case "742": $result = "490";break;
case "743": $result = "383";break;
case "744": $result = "422";break;
case "745": $result = "646";break;
case "746": $result = "707";break;
case "747": $result = "991";break;
case "748": $result = "614";break;
case "749": $result = "803";break;
case "750": $result = "703";break;
case "751": $result = "571";break;
case "752": $result = "931";break;
case "753": $result = "319";break;
case "754": $result = "984";break;
case "755": $result = "159";break;
case "756": $result = "810";break;
case "757": $result = "767";break;
case "758": $result = "148";break;
case "759": $result = "522";break;
case "760": $result = "671";break;
case "761": $result = "017";break;
case "762": $result = "063";break;
case "763": $result = "458";break;
case "764": $result = "095";break;
case "765": $result = "362";break;
case "766": $result = "963";break;
case "767": $result = "223";break;
case "768": $result = "714";break;
case "769": $result = "667";break;
case "770": $result = "643";break;
case "771": $result = "827";break;
case "772": $result = "163";break;
case "773": $result = "781";break;
case "774": $result = "813";break;
case "775": $result = "653";break;
case "776": $result = "685";break;
case "777": $result = "749";break;
case "778": $result = "845";break;
case "779": $result = "717";break;
case "780": $result = "999";break;
case "781": $result = "721";break;
case "782": $result = "604";break;
case "783": $result = "956";break;
case "784": $result = "337";break;
case "785": $result = "757";break;
case "786": $result = "004";break;
case "787": $result = "228";break;
case "788": $result = "555";break;
case "789": $result = "839";break;
case "790": $result = "252";break;
case "791": $result = "196";break;
case "792": $result = "651";break;
case "793": $result = "551";break;
case "794": $result = "117";break;
case "795": $result = "153";break;
case "796": $result = "779";break;
case "797": $result = "271";break;
case "798": $result = "427";break;
case "799": $result = "224";break;
case "800": $result = "832";break;
case "801": $result = "081";break;
case "802": $result = "527";break;
case "803": $result = "657";break;
case "804": $result = "796";break;
case "805": $result = "380";break;
case "806": $result = "629";break;
case "807": $result = "615";break;
case "808": $result = "924";break;
case "809": $result = "049";break;
case "810": $result = "369";break;
case "811": $result = "661";break;
case "812": $result = "519";break;
case "813": $result = "213";break;
case "814": $result = "864";break;
case "815": $result = "909";break;
case "816": $result = "305";break;
case "817": $result = "941";break;
case "818": $result = "209";break;
case "819": $result = "811";break;
case "820": $result = "559";break;
case "821": $result = "561";break;
case "822": $result = "085";break;
case "823": $result = "491";break;
case "824": $result = "032";break;
case "825": $result = "817";break;
case "826": $result = "316";break;
case "827": $result = "273";break;
case "828": $result = "388";break;
case "829": $result = "395";break;
case "830": $result = "320";break;
case "831": $result = "544";break;
case "832": $result = "871";break;
case "833": $result = "156";break;
case "834": $result = "303";break;
case "835": $result = "512";break;
case "836": $result = "967";break;
case "837": $result = "433";break;
case "838": $result = "469";break;
case "839": $result = "096";break;
case "840": $result = "587";break;
case "841": $result = "743";break;
case "842": $result = "540";break;
case "843": $result = "149";break;
case "844": $result = "057";break;
case "845": $result = "068";break;
case "846": $result = "036";break;
case "847": $result = "132";break;
case "848": $result = "843";break;
case "849": $result = "973";break;
case "850": $result = "113";break;
case "851": $result = "431";break;
case "852": $result = "241";break;
case "853": $result = "100";break;
case "854": $result = "939";break;
case "855": $result = "420";break;
case "856": $result = "529";break;
case "857": $result = "181";break;
case "858": $result = "960";break;
case "859": $result = "356";break;
case "860": $result = "992";break;
case "861": $result = "260";break;
case "862": $result = "459";break;
case "863": $result = "128";break;
case "864": $result = "121";break;
case "865": $result = "875";break;
case "866": $result = "877";break;
case "867": $result = "401";break;
case "868": $result = "565";break;
case "869": $result = "807";break;
case "870": $result = "348";break;
case "871": $result = "725";break;
case "872": $result = "367";break;
case "873": $result = "089";break;
case "874": $result = "971";break;
case "875": $result = "324";break;
case "876": $result = "704";break;
case "877": $result = "597";break;
case "878": $result = "711";break;
case "879": $result = "636";break;
case "880": $result = "860";break;
case "881": $result = "188";break;
case "882": $result = "619";break;
case "883": $result = "647";break;
case "884": $result = "828";break;
case "885": $result = "284";break;
case "886": $result = "484";break;
case "887": $result = "785";break;
case "888": $result = "412";break;
case "889": $result = "533";break;
case "890": $result = "903";break;
case "891": $result = "060";break;
case "892": $result = "465";break;
case "893": $result = "373";break;
case "894": $result = "384";break;
case "895": $result = "352";break;
case "896": $result = "448";break;
case "897": $result = "160";break;
case "898": $result = "025";break;
case "899": $result = "164";break;
case "900": $result = "747";break;
case "901": $result = "292";break;
case "902": $result = "416";break;
case "903": $result = "256";break;
case "904": $result = "736";break;
case "905": $result = "580";break;
case "906": $result = "497";break;
case "907": $result = "277";break;
case "908": $result = "672";break;
case "909": $result = "487";break;
case "910": $result = "309";break;
case "911": $result = "576";break;
case "912": $result = "775";break;
case "913": $result = "444";break;
case "914": $result = "437";break;
case "915": $result = "192";break;
case "916": $result = "928";break;
case "917": $result = "452";break;
case "918": $result = "881";break;
case "919": $result = "124";break;
case "920": $result = "399";break;
case "921": $result = "683";break;
case "922": $result = "405";break;
case "923": $result = "288";break;
case "924": $result = "640";break;
case "925": $result = "583";break;
case "926": $result = "021";break;
case "927": $result = "177";break;
case "928": $result = "523";break;
case "929": $result = "935";break;
case "930": $result = "145";break;
case "931": $result = "335";break;
case "932": $result = "800";break;
case "933": $result = "463";break;
case "934": $result = "849";break;
case "935": $result = "220";break;
case "936": $result = "907";break;
case "937": $result = "516";break;
case "938": $result = "689";break;
case "939": $result = "700";break;
case "940": $result = "668";break;
case "941": $result = "764";break;
case "942": $result = "476";break;
case "943": $result = "341";break;
case "944": $result = "480";break;
case "945": $result = "064";break;
case "946": $result = "608";break;
case "947": $result = "679";break;
case "948": $result = "732";break;
case "949": $result = "572";break;
case "950": $result = "053";break;
case "951": $result = "896";break;
case "952": $result = "548";break;
case "953": $result = "593";break;
case "954": $result = "988";break;
case "955": $result = "625";break;
case "956": $result = "892";break;
case "957": $result = "092";break;
case "958": $result = "495";break;
case "959": $result = "753";break;
case "960": $result = "508";break;
case "961": $result = "245";break;
case "962": $result = "768";break;
case "963": $result = "715";break;
case "964": $result = "693";break;
case "965": $result = "501";break;
case "966": $result = "170";break;
case "967": $result = "078";break;
case "968": $result = "202";break;
case "969": $result = "981";break;
case "970": $result = "014";break;
case "971": $result = "142";break;
case "972": $result = "110";break;
case "973": $result = "046";break;
case "974": $result = "949";break;
case "975": $result = "042";break;
case "976": $result = "821";break;
case "977": $result = "074";break;
case "978": $result = "917";break;
case "979": $result = "010";break;
case "980": $result = "913";break;
case "981": $result = "789";break;
case "982": $result = "977";break;
case "983": $result = "138";break;
case "984": $result = "853";break;
case "985": $result = "945";break;
case "986": $result = "234";break;
case "987": $result = "885";break;
case "988": $result = "106";break;
case "989": $result = "134";break;
case "990": $result = "326";break;
case "991": $result = "070";break;
case "992": $result = "166";break;
case "993": $result = "230";break;
case "994": $result = "198";break;
case "995": $result = "294";break;
case "996": $result = "006";break;
case "997": $result = "262";break;
case "998": $result = "102";break;
case "999": $result = "038";break;
	}
	return $result;
}

function softKey($input1)
{    
    if (strlen($input1) != 19) return "";
        
    if (intval($input1) <= 0) return "";
    
    $result = FindUN(substr($input1, 0, 3)) . FindUN(substr($input1, 3, 3)) . FindUN(substr($input1, 6, 3)) . FindUN(substr($input1, 9, 3)) . FindUN(substr($input1, 12, 3));
    
    $result = $result . substr(FindUN(substr($input1, 15, 3)), 0, 1);
	$intPos = intval(substr($input1, 17));
	switch ($intPos)
	{
	case 1:
	$result = substr($result,10,1) . substr($result,8,1) . substr($result,5,1) . substr($result,11,1) . substr($result,13,1) . substr($result,7,1) . substr($result,3,1) . substr($result,15,1) . substr($result,2,1) . substr($result,1,1) . substr($result,6,1) . substr($result,0,1) . substr($result,4,1) . substr($result,9,1) . substr($result,14,1) . substr($result,12,1);
	break;
case 2:
$result = substr($result,1,1) . substr($result,0,1) . substr($result,13,1) . substr($result,3,1) . substr($result,5,1) . substr($result,15,1) . substr($result,11,1) . substr($result,7,1) . substr($result,6,1) . substr($result,10,1) . substr($result,14,1) . substr($result,9,1) . substr($result,2,1) . substr($result,8,1) . substr($result,12,1) . substr($result,4,1);break;
case 3:
$result = substr($result,13,1) . substr($result,11,1) . substr($result,8,1) . substr($result,14,1) . substr($result,12,1) . substr($result,0,1) . substr($result,10,1) . substr($result,6,1) . substr($result,2,1) . substr($result,1,1) . substr($result,5,1) . substr($result,4,1) . substr($result,9,1) . substr($result,7,1) . substr($result,3,1) . substr($result,15,1);break;
case 4:
$result = substr($result,6,1) . substr($result,5,1) . substr($result,1,1) . substr($result,8,1) . substr($result,2,1) . substr($result,9,1) . substr($result,4,1) . substr($result,0,1) . substr($result,12,1) . substr($result,11,1) . substr($result,15,1) . substr($result,3,1) . substr($result,14,1) . substr($result,7,1) . substr($result,13,1) . substr($result,10,1);break;
case 5:
$result = substr($result,15,1) . substr($result,14,1) . substr($result,10,1) . substr($result,0,1) . substr($result,13,1) . substr($result,11,1) . substr($result,2,1) . substr($result,9,1) . substr($result,4,1) . substr($result,8,1) . substr($result,12,1) . substr($result,6,1) . substr($result,7,1) . substr($result,1,1) . substr($result,5,1) . substr($result,3,1);
break;
case 6:
$result = substr($result,12,1) . substr($result,11,1) . substr($result,7,1) . substr($result,13,1) . substr($result,10,1) . substr($result,8,1) . substr($result,15,1) . substr($result,6,1) . substr($result,1,1) . substr($result,5,1) . substr($result,9,1) . substr($result,3,1) . substr($result,4,1) . substr($result,2,1) . substr($result,0,1) . substr($result,14,1);break;
case 7:
$result = substr($result,0,1) . substr($result,15,1) . substr($result,12,1) . substr($result,2,1) . substr($result,4,1) . substr($result,14,1) . substr($result,10,1) . substr($result,6,1) . substr($result,5,1) . substr($result,9,1) . substr($result,8,1) . substr($result,13,1) . substr($result,1,1) . substr($result,11,1) . substr($result,7,1) . substr($result,3,1);break;
case 8:
$result = substr($result,3,1) . substr($result,2,1) . substr($result,14,1) . substr($result,4,1) . substr($result,1,1) . substr($result,15,1) . substr($result,6,1) . substr($result,13,1) . substr($result,8,1) . substr($result,12,1) . substr($result,0,1) . substr($result,10,1) . substr($result,11,1) . substr($result,5,1) . substr($result,9,1) . substr($result,7,1);break;
case 9:
$result = substr($result,2,1) . substr($result,1,1) . substr($result,13,1) . substr($result,4,1) . substr($result,14,1) . substr($result,5,1) . substr($result,0,1) . substr($result,12,1) . substr($result,8,1) . substr($result,7,1) . substr($result,11,1) . substr($result,15,1) . substr($result,10,1) . substr($result,3,1) . substr($result,9,1) . substr($result,6,1);break;
case 10:
$result = substr($result,14,1) . substr($result,13,1) . substr($result,9,1) . substr($result,15,1) . substr($result,10,1) . substr($result,1,1) . substr($result,12,1) . substr($result,8,1) . substr($result,4,1) . substr($result,3,1) . substr($result,7,1) . substr($result,11,1) . substr($result,5,1) . substr($result,6,1) . substr($result,0,1) . substr($result,2,1);break;
case 11:
$result = substr($result,4,1) . substr($result,3,1) . substr($result,0,1) . substr($result,6,1) . substr($result,7,1) . substr($result,2,1) . substr($result,14,1) . substr($result,10,1) . substr($result,9,1) . substr($result,13,1) . substr($result,1,1) . substr($result,12,1) . substr($result,5,1) . substr($result,11,1) . substr($result,15,1) . substr($result,8,1);break;
case 12:
$result = substr($result,5,1) . substr($result,15,1) . substr($result,12,1) . substr($result,2,1) . substr($result,1,1) . substr($result,4,1) . substr($result,14,1) . substr($result,11,1) . substr($result,6,1) . substr($result,9,1) . substr($result,8,1) . substr($result,10,1) . substr($result,7,1) . substr($result,0,1) . substr($result,13,1) . substr($result,3,1);break;
case 13:
$result = substr($result,7,1) . substr($result,6,1) . substr($result,2,1) . substr($result,9,1) . substr($result,3,1) . substr($result,10,1) . substr($result,5,1) . substr($result,1,1) . substr($result,13,1) . substr($result,12,1) . substr($result,0,1) . substr($result,4,1) . substr($result,15,1) . substr($result,8,1) . substr($result,14,1) . substr($result,11,1);break;
case 14:
$result = substr($result,8,1) . substr($result,7,1) . substr($result,3,1) . substr($result,10,1) . substr($result,4,1) . substr($result,11,1) . substr($result,6,1) . substr($result,2,1) . substr($result,14,1) . substr($result,13,1) . substr($result,1,1) . substr($result,5,1) . substr($result,15,1) . substr($result,9,1) . substr($result,12,1) . substr($result,0,1);break;
case 15:
$result = substr($result,11,1) . substr($result,9,1) . substr($result,6,1) . substr($result,12,1) . substr($result,14,1) . substr($result,5,1) . substr($result,0,1) . substr($result,4,1) . substr($result,8,1) . substr($result,2,1) . substr($result,3,1) . substr($result,1,1) . substr($result,10,1) . substr($result,7,1) . substr($result,15,1) . substr($result,13,1);break;
default:
$result = substr($result,9,1) . substr($result,7,1) . substr($result,4,1) . substr($result,10,1) . substr($result,12,1) . substr($result,6,1) . substr($result,2,1) . substr($result,14,1) . substr($result,13,1) . substr($result,1,1) . substr($result,0,1) . substr($result,5,1) . substr($result,15,1) . substr($result,3,1) . substr($result,8,1) . substr($result,11,1);break;
	}
	return $result;
}
?>