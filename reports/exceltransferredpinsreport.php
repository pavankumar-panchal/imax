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
    $url = '../home/index.php?a_link=transferredpinsreport';
    header("location:".$url);
    exit;
}
elseif($flag == 'true')
{
    //print_r($_POST);
    $id = $_GET['id'];
    $todate = $_POST['todate'];
    $fromdate = $_POST['fromdate'];
    $dealerregion = $_POST['dealerregion'];
    $usagetype = $_POST['usagetype'];
    $purchasetype = $_POST['purchasetype'];
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


        $productcodepiece = ($chks == "")?(""):(" AND  p1.productcode IN (".$value.") ");
        $schemepiece = ($scheme == "")?(""):(" AND inv_mas_scheme.slno = '".$scheme."' ");
        $usagetypepiece = ($usagetype == "")?(""):(" AND inv_logs_transferpin.transferfromusagetype = '".$usagetype."' ");
        $purchasetypepiece = ($purchasetype == "")?(""):(" AND `inv_logs_transferpin`.`transferfrompurchasetype` = '".$purchasetype."' ");
        $dealerregionpiece = ($dealerregion == "")?(""):(" AND d1.region = '".$dealerregion."' ");


        $dealeridpiece = ($dealerid == "")?(""):("  AND d1.slno = '".$dealerid."' ");
        $datepiece = "  left(inv_logs_transferpin.dateandtime,10) BETWEEN '".changedateformat($fromdate)."' AND '".changedateformat($todate)."'";

            $query = "select inv_logs_transferpin.cardid as pin_serial_number,inv_mas_scratchcard.scratchnumber as scratchnumber,left(inv_logs_transferpin.dateandtime,10) as `Date`, p1.productname as from_product,
                  p2.productname as to_product,d1.businessname as from_dealer,d2.businessname as to_dealer,inv_logs_transferpin.transferfromusagetype as from_usagetype,inv_logs_transferpin.transfertousagetype as to_usagetype,
                  `inv_logs_transferpin`.`transferfrompurchasetype` as from_purchasetype,`inv_logs_transferpin`.`transfertopurchasetype` as to_purchasetype,
                  c1.slno as from_attachcust ,c2.slno as to_attachcust,inv_mas_region.category as region,inv_logs_transferpin.transferremarks as remarks,inv_mas_users.fullname ,inv_mas_scheme.schemename as scheme
                    from `inv_logs_transferpin`
                    left join inv_dealercard on inv_dealercard.cardid = inv_logs_transferpin.cardid
                    left join inv_mas_dealer as d1 on d1.slno = inv_logs_transferpin.transferfromdealerid
                    left join inv_mas_dealer as d2 on d2.slno = inv_logs_transferpin.transfertodealerid
                    LEFT JOIN inv_mas_scratchcard on inv_mas_scratchcard.cardid = inv_logs_transferpin.cardid
                    LEFT JOIN inv_mas_product as p1 on p1.productcode = inv_logs_transferpin.transferfromproduct
                    LEFT JOIN inv_mas_product as p2 on p2.productcode = inv_logs_transferpin.transfertoproduct
                    left join inv_mas_users on inv_mas_users.slno = inv_logs_transferpin.transferuserid 
                    left join inv_mas_scheme on inv_mas_scheme.slno = inv_dealercard.scheme 
                    LEFT JOIN inv_mas_region on inv_mas_region.slno = d1.region
                    left join inv_mas_customer as c1 on c1.slno = `inv_logs_transferpin`.`transferfromattachedcust`
                    left join inv_mas_customer as c2 on c2.slno = `inv_logs_transferpin`.`transfertoattachedcust`
                    WHERE  " . $datepiece . $productcodepiece . $dealeridpiece . $schemepiece . $dealerregionpiece . $usagetypepiece . $purchasetypepiece ;
            //}

    }
    //echo($query);exit;
    $result = runmysqlquery($query);

    ?>
    <table class="table table-border table-condensed table-bordered" style="margin-top: 5%" id="example">
        <thead>
        <tr>
            <th>Sl No</th>
            <th>Cardid</th>
            <th>Pin Serial Number</th>
            <th>Date</th>
            <th>From Dealer</th>
            <th>To Dealer</th>
            <th>From Product</th>
            <th>To Product</th>
            <th>From Usagetype</th>
            <th>To Usagetype</th>
            <th>From Purchasetype</th>
            <th>To Purchasetype</th>
            <th>From Customer</th>
            <th>To Customer</th>
            <th>Scheme</th>
            <th>Region</th>
            <th>Remarks</th>
            <th>Transferred By</th>

        </tr>
        </thead>
        <tbody>
        <?php
        $slno = 1;
        while ($fetch = mysqli_fetch_array($result)) {
            //Fetch customer name
            if (($fetch['customerreference'] == '') || ($fetch['customerreference'] == 'null')) {
                $businessname = '';
            } else {
                if ($fetch['customerreference'] != "") {
                    $query2 = "select * from inv_mas_customer where slno = '" . $fetch['customerreference'] . "'";
                    $fetch2 = runmysqlqueryfetch($query2);
                    $businessname = $fetch2['businessname'];
                    if ($fetch['registereddate'] == '')
                        $registereddate = '';
                    else
                        $registereddate = changedateformat($fetch['registereddate']);
                } else
                    $registereddate = '';
            }

            ($fetch['from_usagetype']!= $fetch['to_usagetype'])?($tousagetype = $fetch['to_usagetype']):($tousagetype = " ");
            ($fetch['from_dealer']!= $fetch['to_dealer'])?($todealer = $fetch['to_dealer']):($todealer = " ");
            ($fetch['from_product']!= $fetch['to_product'])?($toproduct = $fetch['to_product']):($toproduct = " ");
            ($fetch['from_purchasetype']!= $fetch['to_purchasetype'])?($topurchasetype = $fetch['to_purchasetype']):($topurchasetype = " ");
            ($fetch['from_attachcust']!= $fetch['to_attachcust'])?($toattachcust = $fetch['to_attachcust']):($toattachcust = " ");
?>

            <tr>
                <td><?php echo $slno++; ?></td>
                <td><?php echo $fetch['pin_serial_number']; ?></td>
                <td><?php echo $fetch['scratchnumber']; ?></td>
                <td><?php echo changedateformat(substr($fetch['Date'], 0, 10)); ?></td>
                <td><?php echo $fetch['from_dealer']; ?> </td>
                <td><?php echo $todealer; ?> </td>
                <td><?php echo $fetch['from_product']; ?> </td>
                <td><?php echo $toproduct; ?> </td>
                <td><?php echo $fetch['from_usagetype']; ?></td>
                <td><?php echo $tousagetype; ?></td>
                <td><?php echo $fetch['from_purchasetype']; ?></td>
                <td><?php echo $topurchasetype; ?> </td>
                <td><?php echo $fetch['from_attachcust']; ?> </td>
                <td><?php echo $toattachcust; ?></td>
                <td><?php echo $fetch['scheme']; ?></td>
                <td><?php echo $fetch['region']; ?> </td>
                <td><?php echo $fetch['remarks']; ?> </td>
                <td><?php echo $fetch['fullname']; ?> </td>
           </tr>
        <?php } ?>
        </tbody>
    </table>
<?php

$queryres = 'select slno,username from inv_mas_users where slno = '.$userid.'';
$fetchres = runmysqlqueryfetch($queryres);
$localdate = datetimelocal('Ymd');
$localtime = datetimelocal('His');
$filebasename = "transferredpins".$localdate."-".$localtime."-".strtolower($fetchres['username']);
$username = $fetchres['username'];

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
                        messageTop: 'Transferred Pins Details.',
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
                            //alert("hi");
                            //$('row c:nth-child(2)', sheet).attr('s', '50');
                        }
                  }
                ]
            } );
        } );
    </script>


    <?php

    $query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','view_transferredpins_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
    $result = runmysqlquery($query1);

    $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','257','".date('Y-m-d').' '.date('H:i:s')."','view_transferredpins_report')";
    $eventresult = runmysqlquery($eventquery);

}
?>
<script>
    function insertdata()
    {
        //alert("in");
        $.ajax({
            url: 'eventype.php',
            data: "username=<?php $username ?>",
            type: 'POST',
            success:function (status) {
               // alert(status);
                return true;
            },
            error:function(response)
            {
                console.log(response);
            }
        })
    }
</script>
</body>
</html>
