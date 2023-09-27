<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Table Data</title>
</head>

<body>
<form action="" method="post">
Password: <input type="password" id="token" name="token" value="<?php print $_POST['token'] ?>"/>
<input type='Radio' name ='reportType' id='registered' value='registered' onClick="showRegControls()" <?php echo ($_POST['reportType'] == 'surrender' ? '' : 'checked') ?>>Registered Details
<input type="Radio" name ="reportType" id="surrender" value="surrender" onClick="hideRegControls()" <?php echo ($_POST['reportType'] == 'surrender' ? 'checked' : '') ?>>Surrendered Details
<p>
<input type="Radio" name ="filter1" id="All" value="ShowAll" <?php echo ($_POST['filter1'] == 'ShowAll' ? 'checked' : '') ?>>All
<input type="Radio" name ="filter1" id="blank1" value="blank1" <?php echo ($_POST['filter1'] == 'blank1' ? 'checked' : '') ?>>Blank Title1
<input type="Radio" name ="filter1" id="blank2" value="blank2" <?php echo ($_POST['filter1'] == 'blank2' ? 'checked' : '') ?>>Blank Title2
<input type="Radio" name ="filter1" id="blank3" value="blank3" <?php echo ($_POST['filter1'] == 'blank3' ? 'checked' : '') ?>>Both Blank
<input type="Radio" name ="filter1" id="duplicateTitle1" value="duplicateTitle1" <?php echo ($_POST['filter1'] == 'duplicateTitle1' ? 'checked' : '') ?>><span id="duplicateTitle1Text">Duplicate Title1</span>
<input type="Radio" name ="filter1" id="duplicateTitle2" value="duplicateTitle2" <?php echo ($_POST['filter1'] == 'duplicateTitle2' ? 'checked' : '') ?>><span id="duplicateTitle2Text">Duplicate Title2</span>
<input type="Radio" name ="filter1" id="doubleEntries" value="doubleEntries" <?php echo ($_POST['filter1'] == 'doubleEntries' ? 'checked' : '') ?>><span id="doubleEntriesText">Double Entries</span>
<input type="checkbox" name ="excludeSurrender" id="excludeSurrender" value="excludeSurrender" <?php echo ($_POST['excludeSurrender'] == 'excludeSurrender' ? 'checked' : '') ?>><span id="excludeSurrenderText">Exclude surrendered</span><br />
<input type="Radio" name ="regType" id="regTypeAll" value="regTypeAll" <?php echo ($_POST['regType'] == 'regTypeAll' ? 'checked' : '') ?>>All
<input type="Radio" name ="regType" id="Online" value="Online" <?php echo ($_POST['regType'] == 'Online' ? 'checked' : '') ?>>Online
<input type="Radio" name ="regType" id="Offline" value="Offline" <?php echo ($_POST['regType'] == 'Offline' ? 'checked' : '') ?>>Offline </input>
<input type="Radio" name ="regType" id="softkey" value="softkey" <?php echo ($_POST['regType'] == 'softkey' ? 'checked' : '') ?>>Softkey </input><br />
<input type="submit" value="Submit" id="submit" name="submit" />
</form>

<script type="text/javascript" runat="server">
function showRegControls()
{
    document.getElementById('excludeSurrender').style.display = 'inline';
	document.getElementById('excludeSurrenderText').style.display = 'inline';
	document.getElementById('duplicateTitle1').style.display = 'inline';
	document.getElementById('duplicateTitle1Text').style.display = 'inline';
	document.getElementById('duplicateTitle2').style.display = 'inline';
	document.getElementById('duplicateTitle2Text').style.display = 'inline';
	document.getElementById('doubleEntries').style.display = 'inline';
	document.getElementById('doubleEntriesText').style.display = 'inline';

}
function hideRegControls()
{
	document.getElementById('excludeSurrender').style.display = 'none';
    document.getElementById('excludeSurrenderText').style.display = 'none';
		document.getElementById('duplicateTitle1').style.display = 'none';
	document.getElementById('duplicateTitle1Text').style.display = 'none';
		document.getElementById('duplicateTitle2').style.display = 'none';
	document.getElementById('duplicateTitle2Text').style.display = 'none';
	document.getElementById('doubleEntries').style.display = 'none';
	document.getElementById('doubleEntriesText').style.display = 'none';
}
</script>
<?php
if ( isset( $_POST['submit'] ) ) 
{ 
	include('../functions/phpfunctions.php');
	
	$token=$_POST['token'];
	if ($token != "madumaga#1234")
	{
		echo "Invalid Password";
		exit;
	}
	
	$reportType=$_POST['reportType'];
	if($reportType == "surrender")
	{
		//$query="select * from inv_surrenderproduct";
		//$query="select (select customerid from inv_mas_customer where slno = A234.customerreference) custid, (select scratchnumber from inv_mas_scratchcard where cardid = A234.cardid) PIN,A123.HDDID,A123.ETHID,surrendertime,A123.networkip,A123.systemip,A123.REGTYPE,A123.COMPUTERNAME,A123.COMPUTERIP,A123.CREATEDBY from inv_surrenderproduct A123 LEFT JOIN inv_customerproduct A234 ON A123.refslno = A234.slno where customerreference <> '41975' and AUTOREGISTRATIONYN='Y'";
		$query="select a2.customerreference, a2.computerid, a2.cardid, a1.HDDID Title1,a1.ETHID Title2,surrendertime,a1.networkip,a1.systemip,a1.REGTYPE,a1.COMPUTERNAME,a1.COMPUTERIP,a1.CREATEDBY from inv_surrenderproduct a1 left join inv_customerproduct a2 ON a1.refslno = a2.slno where customerreference <> '41975' and AUTOREGISTRATIONYN='Y'";
	}
	else	
	{
		//$query="select * from ".$table;
		//$query="select (select customerid from inv_mas_customer where slno = A123.customerreference) custid, (select scratchnumber from inv_mas_scratchcard where cardid = A123.cardid) PIN, computerid, softkey, date, time, purchasetype, HDDID, ETHID, REGTYPE, COMPUTERNAME, COMPUTERIP, ACTIVELICENSE FROM inv_customerproduct A123 where customerreference <> '41975' and AUTOREGISTRATIONYN='Y'";
		$query="select a1.customerreference, a1.computerid, a1.cardid, HDDID Title1, ETHID Title2, date, time,  REGTYPE, COMPUTERNAME, COMPUTERIP FROM inv_customerproduct a1 where customerreference <> '41975' and AUTOREGISTRATIONYN='Y'";
	}
	
	$filter1=$_POST['filter1'];
	$excludeSurrender=$_POST['excludeSurrender'];
	
	if 	($filter1 == "blank1" || $filter1 == "blank3")
	{
		$query = $query. " AND (a1.HDDID = '' OR a1.HDDID IS NULL)";
	}
	if ($filter1 == "blank2" || $filter1 == "blank3")
	{
		$query = $query. " AND (a1.ETHID = '' OR a1.ETHID IS NULL)";
	}
	
	if ($filter1 == "duplicateTitle1")
	{
		$query = "select a1.customerreference, dup.customerreference, a1.computerid, dup.computerid,a1.cardid,dup.cardid, a1.HDDID Title1, dup.HDDID dupTitle1,a1.ETHID Title2, dup.ETHID DupTitle2, a1.date, dup.date dupdate, a1.time,dup.time dupTime,  a1.REGTYPE, dup.REGTYPE dupRegType, a1.COMPUTERNAME, dup.COMPUTERNAME dupCompuetrName, a1.COMPUTERIP, dup.COMPUTERIP dupComputerIP FROM inv_customerproduct a1
		INNER JOIN (SELECT * FROM inv_customerproduct
		GROUP BY HDDID HAVING count(HDDID) > 1) dup ON a1.HDDID = dup.HDDID and a1.customerreference <> dup.customerreference Where a1.computerid not in (88400,88409,10100,10109) and (a1.HDDID <> '' and a1.HDDID IS not NULL) and a1.customerreference <> '41975' and a1.AUTOREGISTRATIONYN='Y'";//and a1.customerreference = dup.customerreference and a1.computerid <> dup.computerid ";
	}
	else if ($filter1 == "duplicateTitle2")
	{
		$query = "select a1.customerreference,dup.customerreference,a1.computerid, dup.computerid,a1.cardid,dup.cardid, a1.HDDID Title1, dup.HDDID DupTitle1,a1.ETHID Title2, dup.ETHID dupTitle2,a1.date, dup.date dupdate, a1.time,dup.time dupTime,  a1.REGTYPE, dup.REGTYPE dupRegType, a1.COMPUTERNAME, dup.COMPUTERNAME dupCompuetrName, a1.COMPUTERIP, dup.COMPUTERIP dupComputerIP FROM inv_customerproduct a1
		INNER JOIN (SELECT * FROM inv_customerproduct
		GROUP BY ETHID HAVING count(ETHID) > 1) dup ON a1.ETHID = dup.ETHID and a1.customerreference <> dup.customerreference Where a1.computerid not in (88400,88409,10100,10109) and (a1.ETHID <> '' and a1.ETHID IS not NULL) and a1.customerreference <> '41975' and a1.AUTOREGISTRATIONYN='Y'";//and a1.customerreference = dup.customerreference and a1.computerid <> dup.computerid ";
	}
	
	else if ($filter1 == "doubleEntries")
	{
		$query = "select a1.slno, a1.customerreference, a1.computerid, a1.cardid, a1.HDDID Title1, a1.ETHID Title2, a1.date, a1.time, a1.REGTYPE, a1.COMPUTERNAME, a1.COMPUTERIP FROM inv_customerproduct a1
		INNER JOIN (SELECT customerreference,computerid, cardid FROM inv_customerproduct
		GROUP BY customerreference,computerid, cardid HAVING count(customerreference) > 1) dup ON a1.cardid = dup.cardid and a1.computerid = dup.computerid and a1.customerreference = dup.customerreference Where a1.computerid not in (88400,88409,10100,10109) and a1.customerreference <> '41975' and a1.AUTOREGISTRATIONYN='Y'";//and a1.customerreference = dup.customerreference and a1.computerid <> dup.computerid ";
		
		//$query = "select * from inv_invoicenumbers_dummy_regv2 where customerid='1013-2110-2303-15389' order by slno";
		//$query = "select * from inv_invoicenumbers where customerid='1013-2110-2303-15389' order by slno";
		
		//$query = "select * from inv_customerproduct a1 where AUTOREGISTRATIONYN='Y'";//where a1.computerid = '192.168.1.99'";
		//$query = "select * from inv_mas_customer where slno = '29450' or slno = '22266'";
	}
	
	if ($reportType != "surrender" && $excludeSurrender == "excludeSurrender")
	{
		$query = $query. " AND (a1.slno not in (select refslno from inv_surrenderproduct))";
	}
	$regType = $_POST['regType'];
	if ($regType == "Online")
	{
		$query = $query. " AND (a1.REGTYPE = 1)";
	}
	else if ($regType == "Offline")
	{
		$query = $query. " AND (a1.REGTYPE = 2)";
	}
	else if ($regType == "softkey")
	{
		$query = $query. " AND (a1.softkey <> '' and a1.softkey IS not NULL)";
	}
$result=runmysqlquery($query);
	$i = 0;
	echo '<table border="1" cellpadding="3" cellspacing="0" style="border-color:#fff"><tr bgcolor="#666666" style="color:#fff">';
	while ($i < mysql_num_fields($result))
	{
		$meta = mysql_fetch_field($result, $i);
		echo '<th>' . $meta->name . '</th>';
		$i = $i + 1;
	}
	echo '</tr>';
	$i = 0;
	while ($row = mysql_fetch_row($result)) 
	{
		if($i%2 == 0)
			$color = "#edf4ff";
		else
			$color = "#f7faff";
			
		echo '<tr bgcolor='.$color.'>';
		//echo '<tr>';
		$count = count($row);
		$y = 0;
		while ($y < $count)
		{
			$c_row = current($row);
			echo '<td>' . $c_row . '</td>';
			next($row);
			$y = $y + 1;
		}
		echo '</tr>';
		$i = $i + 1;
	}
	echo '</table>';
	echo $i;
	mysql_free_result($result);
}


?>

</body>
</html>