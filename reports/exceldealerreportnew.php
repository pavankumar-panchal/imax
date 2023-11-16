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
ini_set('memory_limit', '4048M');

include('../functions/phpfunctions.php');

//PHPExcel
//require_once '../phpgeneration/PHPExcel.php';

//PHPExcel_IOFactory
//require_once '../phpgeneration/PHPExcel/IOFactory.php';
$flag = $_POST['flag'];
if($flag == '')
{
    $url = '../home/index.php?a_link=newdealerdetails';
    header("location:".$url);
    exit;
}
elseif($flag == 'true')
{
    //print_r($_POST);
    $id = $_GET['id'];
    $todate = $_POST['todate'];
    $fromdate = $_POST['fromdate'];
    $geography = $_POST['geography'];
    $region = $_POST['region'];
    $dealerregion = $_POST['dealerregion'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $usagetype = $_POST['usagetype'];
    $purchasetype = $_POST['purchasetype'];
    $registrationtype = $_POST['registration'];
    //$surrenderpin = $_POST['surrenderpin']; //exit;
    $pintype = $_POST['pintype'];
    $dealertype = $_POST['dealertype'];
    $chks = $_POST['productname'];
    for ($i = 0;$i < count($chks);$i++)
    {
        $c_value .= "'" . $chks[$i] . "'" ."," ;
    }
    $productslist = rtrim($c_value , ',');
    $value = str_replace('\\','',$productslist);

    $dealerid = $_POST['dealerid'];
    $reportdate = datetimelocal('d-m-Y');
    $scheme = $_POST['scheme'];
    $userid = imaxgetcookie('userid');

    $branch = $_POST['branch'];
    if($todate <> '' && $fromdate <> '') {
        //echo($reportdate);
        if ($geography == "") {
            $geographypiece = "";
        } elseif ($geography == "region") {
            $geographypiece = " AND inv_mas_dealer.region = '" . $region . "' ";
        } elseif ($geography == "state") {
            $geographypiece = " AND inv_mas_district.statecode = '" . $state . "'  AND inv_mas_dealer.district = '" . $district . "'";
        } elseif ($geography == "branch") {
            $geographypiece = " AND inv_mas_branch.slno = '" . $branch . "' ";
        }

        $productcodepiece = ($chks == "")?(""):(" AND  inv_mas_product.productcode IN (".$value.") ");
        $schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
        $usagetypepiece = ($usagetype == "")?(""):(" AND inv_dealercard.usagetype = '".$usagetype."' ");
        $purchasetypepiece = ($purchasetype == "")?(""):(" AND inv_dealercard.purchasetype = '".$purchasetype."' ");
        $dealerregionpiece = ($dealerregion == "")?(""):(" AND inv_mas_dealer.region = '".$dealerregion."' ");
        $registrationtypepiece = ($registrationtype == "")?(""):(" AND inv_mas_scratchcard.registered = '".$registrationtype."' ");

        if ($pintype == "") {
            $pintypepiece = "";
        } else if ($pintype == "blocked") {
            $pintypepiece = "AND inv_mas_scratchcard.blocked = 'yes'";
        } else if ($pintype == "cancelled") {
            $pintypepiece = "AND inv_mas_scratchcard.cancelled = 'yes'";
        }
        else if($pintype == "active") {$pintypepiece = "AND inv_mas_scratchcard.cancelled = 'no' AND  inv_mas_scratchcard.blocked = 'no'";}

        if($dealertype == "msp")
            $dealertypepiece = " AND inv_mas_dealer.dealertype = 'msp'";
        else if($dealertype == "psp")
            $dealertypepiece = " AND inv_mas_dealer.dealertype = 'psp'";
        else if($dealertype == "ssp")
            $dealertypepiece = " AND inv_mas_dealer.dealertype = 'ssp'";

        $dealeridpiece = ($dealerid == "")?(""):("  AND inv_mas_dealer.slno = '".$dealerid."' ");
        $datepiece = " AND left(inv_dealercard.date,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";

        if ($pintype == "unregistered") {
//            $query0 = "select max(inv_dealercard.cardid) as dealercard from inv_dealercard
//                LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode
//                left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
//                where inv_dealercard.slno <> '9449599733' " . $datepiece . $productcodepiece . $dealeridpiece . "
//                group by inv_dealercard.dealerid;";
//            $result0 = runmysqlquery($query0);
//            $cardcount = mysqli_num_rows($result0);
//            if ($cardcount > 0) {
//                while ($fetch0 = mysqli_fetch_array($result0)) {
//                    //echo "cardid :" . $fetch0['dealercard'];
//                    $latestdealer[] = $fetch0['dealercard'];
//                }
//                //print_r($latestdealer);
//                for ($i = 0; $i < count($latestdealer); $i++) {
//                    //echo $latestdealer[$i]."<br>";
//                    $c1_value .= "'" . $latestdealer[$i] . "'" . ",";
//                }
//                //echo $c1_value;
//                $value = rtrim($c1_value, ',');
//                $latestdealerpiece = " AND inv_dealercard.cardid  in (".$value.")";


                    $query = "select inv_mas_dealer.businessname as dealername,inv_dealercard.cusbillnumber as billno,inv_dealer_invoicenumbers.invoiceno as invoiceno,
                    inv_mas_scratchcard.cardid as cardid, inv_mas_product.productname as productname,inv_dealercard.date as attcheddate,
                    inv_mas_scratchcard.scratchnumber as scratchnumber,(CASE WHEN inv_mas_scratchcard.blocked = 'yes' THEN 'Blocked' WHEN inv_mas_scratchcard.cancelled = 'yes' THEN 'Cancelled' 
                    ELSE 'ACTIVE' END ) `Pin Status` ,
                    inv_dealercard.usagetype as usagetype,
                    inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname ,inv_mas_scheme.schemename as scheme,
                    inv_mas_region.category as region,inv_bill.remarks as billremarks ,inv_mas_branch.branchname as branch,inv_mas_district.districtname as district,inv_mas_state.statename as state
                    from inv_dealercard 
                    left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
                    LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid
                    LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
                    left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
                    left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
                    LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region
                    left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber
                    left join inv_dealer_invoicenumbers on inv_dealercard.cusbillnumber = inv_dealer_invoicenumbers.cusbillnumber
                    left join inv_mas_branch  on inv_mas_branch.slno = inv_mas_dealer.branch
                    left join inv_mas_district  on inv_mas_district.districtcode = inv_mas_dealer.district
                    left join inv_mas_state  on inv_mas_district.statecode = inv_mas_state.statecode
                    WHERE inv_dealercard.slno <> '9449599733' and (customerreference is NULL or customerreference = '') and (inv_mas_scratchcard.blocked= 'no' or inv_mas_scratchcard.cancelled = '')" . $dealertypepiece . $datepiece . $productcodepiece . $dealeridpiece . $geographypiece . $schemepiece . $dealerregionpiece . $usagetypepiece . $purchasetypepiece . $registrationtypepiece;
            //}
        } else {
        $query = "select inv_mas_customer.businessname as cusname,inv_mas_dealer.businessname as dealername,inv_dealer_invoicenumbers.invoiceno as invoiceno,
	inv_mas_scratchcard.cardid as cardid, inv_mas_product.productname as productname,inv_dealercard.date as attcheddate, inv_customerproduct.billnumber as billnumber , inv_customerproduct.date as registereddate,
	inv_mas_scratchcard.scratchnumber as scratchnumber,(CASE WHEN inv_mas_scratchcard.blocked = 'yes' THEN 'Blocked' WHEN inv_mas_scratchcard.cancelled = 'yes' THEN 'Cancelled' ELSE 'ACTIVE' END ) `Pin Status` ,
	inv_dealercard.usagetype as usagetype,
	inv_dealercard.purchasetype as purchasetype,inv_mas_users.fullname ,inv_mas_scheme.schemename as scheme,
                inv_mas_region.category as region,inv_bill.remarks as billremarks,inv_dealercard.customerreference,inv_mas_branch.branchname as branch,inv_mas_district.districtname as district,inv_mas_state.statename as state from inv_dealercard 
	left join inv_mas_dealer on inv_mas_dealer.slno = inv_dealercard.dealerid
	LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_dealercard.cardid 
	LEFT JOIN inv_customerproduct on inv_dealercard.cardid = inv_customerproduct.cardid and inv_customerproduct.reregistration = 'no' 
	LEFT JOIN inv_mas_customer on inv_mas_customer.slno = inv_customerproduct.customerreference 
	LEFT JOIN inv_mas_product on inv_mas_product.productcode = inv_dealercard.productcode 
	left join inv_mas_users on inv_mas_users.slno = inv_dealercard.userid 
	left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
	LEFT JOIN inv_mas_region on inv_mas_region.slno = inv_mas_dealer.region
	left join inv_mas_branch  on inv_mas_branch.slno = inv_mas_dealer.branch
	left join inv_mas_district  on inv_mas_district.districtcode = inv_mas_dealer.district
	left join inv_mas_state  on inv_mas_district.statecode = inv_mas_state.statecode
	left join inv_bill on inv_bill.slno = inv_dealercard.cusbillnumber 
    left join inv_dealer_invoicenumbers on inv_dealercard.cusbillnumber = inv_dealer_invoicenumbers.cusbillnumber
    WHERE inv_dealercard.cardid!=0 and inv_dealercard.slno <> '9449599733' ".$dealertypepiece.$datepiece.$geographypiece.$productcodepiece.$dealeridpiece.$schemepiece.$dealerregionpiece.$usagetypepiece.$purchasetypepiece.$registrationtypepiece.$pintypepiece;
        }
    }
    //echo($query);exit;
    $result = runmysqlquery($query);
    $attachcustname = ($registrationtype == "no")?(""):($fetch['cusname']);

    ?>
    <table class="table table-border table-condensed table-bordered" style="margin-top: 5%" id="example">
        <thead>
        <tr>
            <th>Sl No</th>
            <th>Dealer</th>
            <th>Pin Serial Number</th>
            <th>Product</th>
            <th>Date</th>
            <th>Bill No</th>
            <th>Invoice No</th>
            <th>Registered On</th>
            <th>Attached To</th>
            <th>Registered To</th>
            <th>PIN Number</th>
            <th>Pin Status</th>
            <th>Usage Type</th>
            <th>Purchase Typ</th>
            <th>Attached By</th>
            <th>Scheme</th>
            <th>Region</th>
            <th>State</th>
            <th>District</th>
            <th>Branch</th>
            <th>Purchase Remarks</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $slno = 1;
            while ($fetch = mysqli_fetch_array($result)) {
                //Fetch customer name
                if (($fetch['customerreference'] == '') || ($fetch['customerreference'] == 'null') || empty($fetch['customerreference'])) {
                    $businessname = '';
                    $registereddate = '';
                } else {
                    if ($fetch['customerreference'] != "") 
                    {
                        $query2 = "select * from inv_mas_customer where slno = '" . $fetch['customerreference'] . "'";
                        $result2 = runmysqlquery($query2);
                        $count2 = mysqli_num_rows($result2);
                        if($count2 > 0)
                        {
                            $fetch2 = runmysqlqueryfetch($query2);
                            $businessname = $fetch2['businessname'];
                            $regdate = $fetch['registereddate'];
                            if ($regdate == '' || empty($regdate))
                                $registereddate = '';
                            else
                                $registereddate = changedateformat($fetch['registereddate']);
                        }
                        
                    } 
                    else
                        $registereddate = '';
                }
                ?>

                <tr>
                    <td><?php echo $slno++; ?></td>
                    <td><?php echo $fetch['dealername']; ?></td>
                    <td><?php echo $fetch['cardid']; ?></td>
                    <td><?php echo $fetch['productname']; ?></td>
                    <td><?php echo changedateformat(substr($fetch['attcheddate'], 0, 10)); ?></td>
                    <td><?php echo $fetch['billnumber']; ?> </td>
                    <td><?php echo $fetch['invoiceno']; ?> </td>
                    <td><?php echo $registereddate; ?></td>
                    <td><?php echo $businessname; ?></td>
                    <td><?php echo $fetch['cusname']; ?></td>
                    <td><?php echo $fetch['scratchnumber']; ?></td>
                    <td><?php echo $fetch['Pin Status']; ?></td>
                    <td><?php echo $fetch['usagetype']; ?> </td>
                    <td><?php echo $fetch['purchasetype']; ?> </td>
                    <td><?php echo $fetch['fullname']; ?></td>
                    <td><?php echo $fetch['scheme']; ?></td>
                    <td><?php echo $fetch['region']; ?> </td>
                    <td><?php echo $fetch['state']; ?> </td>
                    <td><?php echo $fetch['district']; ?> </td>
                    <td><?php echo $fetch['branch']; ?> </td>
                    <td><?php echo $fetch['billremarks']; ?></td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php

    $queryres = 'select slno,username from inv_mas_users where slno = '.$userid.'';
    $fetchres = runmysqlqueryfetch($queryres);
    $localdate = datetimelocal('Ymd');
    $localtime = datetimelocal('His');
    $filebasename = "DealerDetails".$localdate."-".$localtime."-".strtolower($fetchres['username']);

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
            var filename="<?php echo $filebasename; ?>";
            //alert(filename);
            $('#example').DataTable({
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',

                        title: 'Relyon Softech Limited, Bangalore.',
                        messageTop: 'Dealer Inventory Details.',
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


    <?php

    $query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_newdealerdetails_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
    $result = runmysqlquery($query1);

    $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','100','".date('Y-m-d').' '.date('H:i:s')."','view_dealerdetails_report')";
    $eventresult = runmysqlquery($eventquery);

?>
<script>
    function insertdata()
    {
        <?php
        $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','101','".date('Y-m-d').' '.date('H:i:s')."','excel_dealerdetails_report".'-'.strtolower($fetchres['username'])."')";
        $eventresult = runmysqlquery($eventquery);
        ?>
    }
</script>
</body>
</html>
<?php } ?>