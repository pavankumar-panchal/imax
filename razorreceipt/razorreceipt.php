<?php
    include('../functions/phpfunctions.php');

    $query0 = "select * from inv_invoicenumbers where qrtype='upi' and left(createddate,10) = current_date();";
    $result0 = runmysqlquery($query0);

    while($fetch=mysqli_fetch_array($result0))
    {
        $paymenturl = "https://api.razorpay.com/v1/payments/qr_codes/".$fetch['qrid']."/payments";

        //open connection
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $paymenturl);
        // Set HTTP Header for POST request 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            )
        );

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($ch, CURLOPT_USERPWD, "rzp_test_TrI4NQWAhSKdMF:81her4cskVpJk56X7ABNITcc");
        curl_setopt($ch, CURLOPT_USERPWD, "rzp_live_WvFDji1WEQAsVD:RzYTKYRklvfyrtKOnUvdjJH0");

        //execute post
        $apicall0 = curl_exec($ch);
        //print_r($apicall0);
        //exit;
        //Print error if any
        if(curl_errno($ch))
        {
            'error:' . curl_error($ch);
        } 
        curl_close($ch);
        $count = 0;
        // $jayParsedAry[0] = [
        //     "entity" => "collection", 
        //     "count" => 1, 
        //     "items" => [
        //           [
        //              "id" => "pay_MDaSDTe1iNJMvn", 
        //              "entity" => "payment", 
        //              "amount" => 100, 
        //              "currency" => "INR", 
        //              "status" => "captured", 
        //              "order_id" => null, 
        //              "invoice_id" => null, 
        //              "international" => false, 
        //              "method" => "upi", 
        //              "amount_refunded" => 0, 
        //              "refund_status" => null, 
        //              "captured" => true, 
        //              "description" => "QRv2 Payment", 
        //              "card_id" => null, 
        //              "bank" => null, 
        //              "wallet" => null, 
        //              "vpa" => "9449599735@ybl", 
        //              "email" => null, 
        //              "contact" => null, 
        //              "notes" => [
        //              ], 
        //              "fee" => 1, 
        //              "tax" => 0, 
        //              "error_code" => null, 
        //              "error_description" => null, 
        //              "error_source" => null, 
        //              "error_step" => null, 
        //              "error_reason" => null, 
        //              "acquirer_data" => [
        //                    "rrn" => "319588429370" 
        //                 ], 
        //              "created_at" => 1689317106, 
        //              "upi" => [
        //                       "payer_account_type" => "bank_account", 
        //                       "vpa" => "9449599735@ybl" 
        //                    ] 
        //           ] 
        //        ] 
        //  ]; 
          
        //  $jayParsedAry[1] = [
        //     "entity" => "collection", 
        //     "count" => 2, 
        //     "items" => [] 
        //  ]; 
        
        for($i=0;$i<count((array)$apicall0);$i++)
        {
            $countarray = count((array)$apicall0);
            if($countarray == 1)
                $decodedata = json_decode($apicall0,true);
            else
                $decodedata = json_decode($apicall0[$i],true);
            
            $count =  $decodedata['count'];
            $items =  $decodedata['items'];
            if(!empty($items))
            {
                $captured = $decodedata['items'][0]['captured'];
                if($captured)
                {
                    // echo $status = $decodedata['items'][0]['status'];
                    // echo "<br>";
                    // echo $amount = $decodedata['items'][0]['amount']/100;

                    //Get the next record serial number for insertion in receipts table
                    $query1 ="select max(slno) + 1 as receiptslno from inv_mas_receipt";
                    $resultfetch1 = runmysqlqueryfetch($query1);
                    $receiptslno = $resultfetch1['receiptslno'];

                    $onlineinvoiceno = $fetch['slno'];
                    $custid = cusidsplit($fetch['customerid']);
                    
                    $query2 = "select slno from inv_mas_customer where customerid= '".$custid."'";
                    $fetch2 = runmysqlqueryfetch($query2);
                    $custreference = $fetch2['slno'];

                    //Insert Receipt Details
                    $query55 = "INSERT INTO inv_mas_receipt(slno,invoiceno,invoiceamount,receiptamount,paymentmode,receiptremarks,privatenote,createddate,createdby,createdip,lastmodifieddate,lastmodifiedby,lastmodifiedip,customerreference,receiptdate,receipttime,module,partialpayment) values('".$receiptslno."','".$onlineinvoiceno."','".$amount."','".$amount."','upi','','','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".date('Y-m-d').' '.date('H:i:s')."','2','".$_SERVER['REMOTE_ADDR']."','".$custreference."','".date('Y-m-d')."','".date('H:i:s')."','Online','no');";
                    $result55 = runmysqlquery($query55);

                    //Send receipt email
                    sendreceipt($receiptslno,'resend','');
                }
            }
        }
    }
    
?>