<html>
<head>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
	<style>
		div.dt-buttons {
			position: relative;
			float: left;
			padding-top: 2%;
			padding-left: 2%;
		}
		.dataTables_wrapper .dataTables_filter {
			float: right;
			text-align: right;
			padding-top: 2%;
			padding-right: 5%;
		}


		.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
			color: #333;
			padding-left: 5%;
			padding-top: 2%;
		}
	</style>
</head>
<body>
<?php
ini_set('memory_limit', '2048M');

include('../functions/phpfunctions.php');


//PHPExcel 
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';

$flag = $_POST['flag'];
if($flag == '')
{
	$url = '../home/index.php?a_link=surrenderreport'; 
	header("location:".$url);
	exit;
}
elseif($flag == 'true')
{
	$userid = imaxgetcookie('userid');
	$id = $_GET['id'];
	$todate = $_POST['todate'];
	$fromdate = $_POST['fromdate'];
	$fsurrenderby = $_POST['fsurrenderby'];
	$fosurrender = $_POST['fosurrender'];
	$maxcount = $_POST['maxcount'];
	$searchrefresult = $_POST['searchrefresult'];
	$searchinput = $_POST['searchinput'];

	$chks = $_POST['productname'];
	for ($i = 0;$i < count($chks);$i++)
	{
		$c_value .= "'" . $chks[$i] . "'" ."," ;
	}
	$productslist = rtrim($c_value , ',');
	$value = str_replace('\\','',$productslist);
	
	$reportdate = datetimelocal('d-m-Y');
	
	$datepiece = " substring(inv_surrenderproduct.surrendertime,1,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";
	$productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
	$fsurrenderbypiece = ($fsurrenderby == "")?(""):("  AND inv_mas_users.slno = '".$fsurrenderby."' ");
	$fosurrenderpiece = ($fosurrender == "")?(""):("  AND inv_surrenderproduct.forces = '".$fosurrender."' ");
	
	if($searchrefresult == "all") { $searchrefresultpiece = ""; } 
	elseif($searchrefresult == "refslno") { $searchrefresultpiece = " AND inv_surrenderproduct.refslno = '".$searchinput."' " ; }
	elseif($searchrefresult == "businessname") { $searchrefresultpiece = " AND inv_mas_customer.businessname like '%".$searchinput."%' " ; }
	elseif($searchrefresult == "customerid") { $searchrefresultpiece = " AND  inv_customerproduct.customerreference ='".$searchinput."' " ; }
	elseif($searchrefresult == "cardid") { $searchrefresultpiece = " AND inv_customerproduct.cardid = '".$searchinput."' " ; }
			


	
	## Report fetching Details Query##
	$querydetail = "select inv_mas_customer.businessname,inv_mas_customer.customerid,
	inv_mas_scratchcard.scratchnumber,inv_mas_product.productname,inv_surrenderproduct.HDDID,
	inv_surrenderproduct.ETHID,inv_surrenderproduct.COMPUTERNAME,inv_surrenderproduct.networkip,
	inv_surrenderproduct.surrendertime,inv_surrenderproduct.REGDATE,inv_surrenderproduct.forces,
	inv_surrenderproduct.CREATEDBY,inv_mas_users.fullname,inv_surrenderproduct.forceremarks,
	inv_surrenderproduct.refslno 
	from  inv_surrenderproduct 
	left join inv_customerproduct on inv_surrenderproduct.refslno = inv_customerproduct.slno
	left join inv_mas_users on inv_mas_users.slno = inv_surrenderproduct.userref
	left join inv_mas_customer on inv_customerproduct.customerreference = SUBSTR(inv_mas_customer.slno,-5)
	left join inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_customerproduct.cardid
	left join inv_mas_product on inv_mas_product.productcode = SUBSTR(inv_customerproduct.computerid,1,3)
	where ";
	
		if($todate <> '' && $fromdate <> '')
		{
			
			##Max Count is validating ##
			if($searchrefresult == 'maxcount') 
			{ 
				// Max Count Got from POST field
				$maxcount; 
				
				$query1 = "select distinct(refslno) from inv_surrenderproduct";		
				$result1 = runmysqlquery($query1);
				$i=0;
				while($fetch1 = mysqli_fetch_array($result1))
				{
					/*##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					if($maxcount < $resultcount)
					{
						//echo 'refslno '.$refslno.'<br /> count '.$maxcount.'<br /> result'.$resultcount.'<br />';
						##Count Wise Fetching Record##
						$query =  $querydetail."inv_surrenderproduct.refslno =".$refslno." order by inv_surrenderproduct.slno desc";
					}*/
					
				/*	##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					//echo 'resultcount '.$resultcount;
					if($maxcount < $resultcount)
					{
						 $ref = $refslno;
					}
					else
					{
						$ref ='09'; 
					}
					##Count Wise Fetching Record##
					$query =  $querydetail.$datepiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece."
					 and inv_surrenderproduct.refslno = ".$ref." order by inv_surrenderproduct.refslno desc";
					//echo '<br />query'.$query ;exit;
					
					$ref = $refslno;
					//echo "<br />ref ".$ref;*/
					
					##Fetching Refslno##
					$refslno = $fetch1['refslno'];
					##Fetching Count of Refslno
					$resultcount = surrendercount($refslno);
					//echo 'resultcount '.$resultcount;
					if($maxcount < $resultcount)
					{
						$ref = $refslno;
						//echo "<br />ref ".$ref;
						if($i==0)
						{
							$ref_value = $ref;
							$i++;
						}
						else
						{
							$ref_value .=','.$ref;
						}
						//echo $ref_value;
						
					}
				}

			}
			if($maxcount <> '')
			{
				$refvalueresultpiece = (" AND inv_surrenderproduct.refslno IN (".$ref_value.") ");
				#Count Wise Fetching Record##
				$query =  $querydetail.$datepiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece.$refvalueresultpiece." order by inv_surrenderproduct.refslno desc ;";
				//echo '<br />query '.$query . '<br />';
			}
			else
			{
				$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece." order by inv_surrenderproduct.refslno desc ;";		
			}
		/*	else
			{
				
				#$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece." order by inv_surrenderproduct.slno desc";		
				$query = $querydetail.$datepiece.$searchrefresultpiece.$productcodepiece.$fsurrenderbypiece.$fosurrenderpiece." order by inv_surrenderproduct.slno desc";		
			}*/

		}
		//echo '<br />query '.$query . '<br />';
		$result = runmysqlquery($query);

?>
<table class="table table-border table-condensed table-bordered" style="margin-top: 5%" id="example">
	<thead>
	<tr>

		<th>Sl No</th>
		<th>Company Name</th>
		<th>Customer ID</th>
		<th>PIN Number</th>
		<th>Product Name</th>
		<th>HDDID</th>
		<th>ETHID</th>
		<th>Computer Name</th>
		<th>Network IP</th>
		<th>Surrender Date</th>
		<th>Registered Date</th>
		<th>Surrender Details</th>
		<th>Offline Remarks</th>
		<th>Reference Slno</th>


	</tr>
	</thead>
	<tbody>
	<?phpphp
	//$j =4;
	$slno = 1;
	while ($fetch = mysqli_fetch_array($result)) {
		//Fetch customer name
		##Surrender Date##
		if($fetch['surrendertime'] == '')
		{	$surrenderdate = '';}
		else
		{	$surrenderdate = changedateformatwithtime($fetch['surrendertime']);}
		##Register Date##
		if($fetch['REGDATE'] == '')
		{	$registereddate = '';}
		else
		{	$registereddate = changedateformatwithtime($fetch['REGDATE']);}
		##Surrender As Online Or Offline##
		if($fetch['forces'] == '0')
		{	$surrenderas = 'App Surrender';}
		else
		{	$surrenderas = 'Force Surrender' ;}
		##Created BY  Online Or Offline##
		if($fetch['CREATEDBY'] == '')
		{	$appsurrenderby = ''; }
		else
		{	$appsurrenderby = $surrenderas.'-'. $fetch['CREATEDBY'] ;}

		##Created BY  Username##
		if($fetch['fullname'] == '')
		{	$forsurrenderby = '';}
		else
		{	$forsurrenderby = $surrenderas.'-'. $fetch['fullname'] ;}

		$surrenderby = $forsurrenderby.$appsurrenderby;
		##Customer ID fromat##
		$custid = cusidcombine($fetch['customerid']);

		?>

		<tr>
			<td><?phpphp echo $slno++; ?></td>
			<td><?phpphp echo $fetch['businessname']; ?></td>
			<td><?phpphp echo $custid; ?></td>
			<td><?phpphp echo $fetch['scratchnumber']; ?></td>
			<td><?phpphp echo $fetch['productname']; ?></td>
			<td><?phpphp echo $fetch['HDDID']; ?> </td>
			<td><?phpphp echo $fetch['ETHID']; ?></td>
			<td><?phpphp echo $fetch['COMPUTERNAME']; ?></td>
			<td><?phpphp echo $fetch['networkip']; ?></td>
			<td><?phpphp echo $surrenderdate; ?></td>
			<td><?phpphp echo $registereddate; ?></td>
			<td><?phpphp echo $surrenderby; ?></td>
			<td><?phpphp echo $fetch['forceremarks']; ?></td>
			<td><?phpphp echo $fetch['refslno']; ?></td>

		</tr>
    <?phpphp } ?>
    </tbody>
</table>
<?phpphp

$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
$fetchres = runmysqlqueryfetch($query);
$localdate = datetimelocal('Ymd');
$localtime = datetimelocal('His');
$filebasename = "Customer-pinno-surrender-details".$localdate."-".$localtime."-".strtolower($fetchres['username']);

?>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script>

	$(document).ready(function() {
		//alert("passing");
		var filename="<?phpphp echo $filebasename; ?>";
		//alert(filename);
		$('#example').DataTable({
			dom: 'Blfrtip',
			buttons: [
				{
					extend: 'excelHtml5',

					title: 'Relyon Softech Limited, Bangalore.',
					messageTop: 'Surrendered PIN Details',
					filename: filename,
					customize: function( xlsx ) {
						var sheet = xlsx.xl.worksheets['sheet1.xml'];
						$('row:first c', sheet).attr( 's', '50');
						$('row:first c', sheet).attr( 's', '2');
						// $('row c[r*="2"]', sheet).attr('s', '50');
						// $('row c[r*="2"]', sheet).attr('s', '2');
						//$('row c[r*="3"]', sheet).attr('s', '27');
						// $('row c[r*="3"]', sheet).attr('s', '42');
						$('row:eq(1) c', sheet).attr( 's', '50');
						$('row:eq(1) c', sheet).attr( 's', '2');
						$('row:eq(2) c', sheet).attr( 's', '42');
						// $('row:eq(2) c', sheet).attr( 's', '17');
						//$('row:eq(2) c', sheet).attr( 's', '');
						insertdata();
						//$('row c:nth-child(2)', sheet).attr('s', '50');
					}
					// messageTop: 'Relyon Softech Limited, Bangalore.'

				}
			]
		} );
	} );
</script>


<?phpphp

$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_customer_pinno_surrender_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
$result = runmysqlquery($query1);

$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','254','".date('Y-m-d').' '.date('H:i:s')."','view_customer_pinno_surrender_report')";
$eventresult = runmysqlquery($eventquery);

?>
<script>
	function insertdata()
	{
		<?phpphp
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks)
			values('".$userid."','".$_SERVER['REMOTE_ADDR']."','253','".date('Y-m-d').' '.date('H:i:s')."','excel_customer_pinno_surrender_report".'-'.strtolower($fetchres['username'])."')";
		$eventresult = runmysqlquery($eventquery);
		?>
	}
</script>
</body>
</html>
<?phpphp } ?>