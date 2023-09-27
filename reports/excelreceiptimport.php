<?
ini_set('memory_limit', '2048M');
include('../functions/phpfunctions.php');

$userid = imaxgetcookie('userid');
$slno = $_POST['slno'];	
for ($i = 0;$i < count($slno);$i++)
	{
		$slno_value .= "'" . $slno[$i] . "'" ."," ;
	}
	$slvalue = rtrim($slno_value , ',');
	$value = str_replace('\\','',$slvalue);
	
	$query = "update inv_mas_receipt set imported = 'Y' where slno In (".$value.")";	
	$result = runmysqlquery($query);
	
	// $query1 ="select inv_mas_receipt.slno as recieptslno,inv_invoicenumbers.slno as invoiceslno,inv_invoicenumbers.invoiceno as invoicenumber,inv_invoicenumbers.netamount,
	// inv_invoicenumbers.businessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_mas_receipt.chequeno,
	// inv_mas_receipt.chequedate,
	// inv_mas_receipt.drawnon,inv_mas_receipt.receiptremarks,inv_mas_receipt.invoiceno from inv_mas_receipt
	// left join inv_invoicenumbers on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
	// where inv_mas_receipt.slno In (".$value.")";	
	$query1 ="select inv_mas_receipt.slno as recieptslno,inv_mas_receipt.dealerinvoiceno as dealerinvoiceno,inv_mas_receipt.matrixinvoiceno as matrixinvoiceno,inv_invoicenumbers.slno as invoiceslno,inv_dealer_invoicenumbers.slno as dlrinvoiceslno,inv_matrixinvoicenumbers.slno as matrixinvslno,
	inv_invoicenumbers.invoiceno as invoicenumber,inv_dealer_invoicenumbers.invoiceno as dlrinvoicenumber,inv_matrixinvoicenumbers.invoiceno as matrixinvoicenumber,inv_invoicenumbers.netamount,inv_dealer_invoicenumbers.netamount as dlrnetamount,inv_matrixinvoicenumbers.netamount as matrixnetamount,
	inv_invoicenumbers.businessname,inv_dealer_invoicenumbers.businessname as dlrbusinessname,inv_matrixinvoicenumbers.businessname as matrixbusinessname,inv_mas_receipt.receiptamount,inv_mas_receipt.paymentmode,inv_mas_receipt.chequeno,
	inv_mas_receipt.chequedate,
	inv_mas_receipt.drawnon,inv_mas_receipt.receiptremarks,inv_mas_receipt.invoiceno,inv_mas_receipt.customerreference as dealerid
	from inv_mas_receipt
	left join inv_invoicenumbers on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
	left join inv_matrixinvoicenumbers on inv_mas_receipt.matrixinvoiceno = inv_matrixinvoicenumbers.slno
	left join inv_dealer_invoicenumbers on inv_dealer_invoicenumbers.slno = inv_mas_receipt.dealerinvoiceno 
	where inv_mas_receipt.slno In (".$value.")";
	$result1 = runmysqlquery($query1); 
	if(mysqli_num_rows($result1) <> 0)
	{
		while($fetch = mysqli_fetch_array($result1))
		{
			//$slno=$fetch['invoiceslno'];
			$recieptslno=$fetch['recieptslno'];
			if($fetch['dealerinvoiceno'] == "")
			{
				if($fetch['matrixinvoiceno']!="")
				{
					$invoiceslno = $fetch['matrixinvslno'];
					$invoiceno=$fetch['matrixinvoicenumber'];
					$invoiceamount=$fetch['matrixnetamount'];
					$businessname=$fetch['matrixbusinessname'];
					$receiptamount=$fetch['receiptamount'];
					$paymentmode = $fetch['paymentmode'];
				}
				else
				{
					$invoiceslno = $fetch['invoiceslno'];
					$invoiceno=$fetch['invoicenumber'];
					$invoiceamount=$fetch['netamount'];
					$businessname=$fetch['businessname'];
				}
			}
			else{
				$invoiceslno = $fetch['dlrinvoiceslno'];
				$invoiceno=$fetch['dlrinvoicenumber'];
				$invoiceamount=$fetch['dlrnetamount'];
				$receiptamount=$fetch['receiptamount'];
				$paymentmode = $fetch['paymentmode'];
				$businessname=$fetch['dlrbusinessname'];
			}
			if(empty($fetch['dealerinvoiceno'])  && empty($fetch['matrixinvoiceno']))
			{
				if(empty($fetch['invoiceno']))
				{
					$invoiceamount=$fetch['receiptamount'];
					$query0 = "select inv_mas_receipt.slno ,receiptamount from inv_mas_receipt where inv_mas_receipt.paymentmode in ('Netbanking','creditordebit') and inv_mas_receipt.slno = " . $recieptslno;
				}
				else
				{
					$query0 = "select inv_mas_receipt.slno ,netamount from inv_mas_receipt 
			         left join inv_invoicenumbers on inv_mas_receipt.invoiceno = inv_invoicenumbers.slno
			         where inv_mas_receipt.paymentmode in ('Netbanking','creditordebit')
					 and inv_mas_receipt.slno = " . $recieptslno;

				}
				
				$result0 = runmysqlquery($query0);
				$count = mysqli_num_rows($result0);
				if($count > 0)
				{
					
					while($fetch0 = mysqli_fetch_array($result0))
					{
						//round($number,2);number_format("1000000",2)
						$receiptno = $fetch0['slno'];

						/*if($invoiceno!= "")
						{
							$online_invoiceno = "invoicenumber = '$invoiceno'";
						}
						else
						{*/
							//$online_invoiceno = "recordreference = ".$invoiceslno;
						//}

						if(empty($fetch['invoiceno']))
							$query4= "select citrus,razorpay,customerid,recordreference from transactions where receiptnumber = ".$receiptno." and responsecode = 0";
						else	
							$query4= "select citrus,razorpay,customerid,recordreference from transactions where recordreference = ".$invoiceslno." and responsecode = 0";
						
						$result4 = runicicidbquery($query4);
					  	$count_invoice = mysqli_num_rows($result4);
					  	if($count_invoice > 0)
					  	{
					  		while($fetch4= mysqli_fetch_array($result4))
				       		{
					       		if ($fetch4['citrus'] == "Y") 
					       		{
					       			$paymentmode="citrus";
					       	   		$commission0 = ($invoiceamount * 0.0271);
					       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.0271));
					       		}
					       		else if($fetch4['razorpay'] == 'Y')
					       		{
					       			//$paymentmode="Razorpay";
					       			$paymentmode="ICICI";
					       	   		$commission0 = ($invoiceamount * 0.0236);
					       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.0236));
					       		}
					       		else
					       		{
					       			$paymentmode="ICICI";
					       	   		$commission0 = ($invoiceamount * (0.02729));
					       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.02729));
					       		}
					       		
					       		//$commission = number_format($commission0,2);
								//$received = number_format($received0,2);
							}
					  	}
					  	else
					  	{
		              
			                 $query2 = "select custreference,pre_online_purchase.onlineinvoiceno,`pre_online_purchase`.slno from pre_online_purchase
				 						Left join inv_invoicenumbers on pre_online_purchase.onlineinvoiceno = inv_invoicenumbers.slno
			   						    where pre_online_purchase.onlineinvoiceno =".$invoiceslno;
			    			  $result2 = runmysqlquery($query2);
			    			  $count_online_invoice = mysqli_num_rows($result2);

			    			  if($count_online_invoice > 0)
			    			  {

			    			  	    $fetch2 = runmysqlqueryfetch($query2);
				    			  	$query3 = "select citrus,razorpay,customerid,recordreference from transactions 
				    			  	where recordreference = ".$fetch2['slno']." and responsecode = 0";
				    			  	$result3 = runicicidbquery($query3);
				    			  	$count3 = mysqli_num_rows($result3);

				    			  	//$fetch3 = runmysqlqueryfetch($query2);

					    			  if($count3 > 0)
								      {
								       		while($fetch3 = mysqli_fetch_array($result3))
								       		{
									       		if ($fetch3['citrus'] == "Y") 
									       		{
									       			$paymentmode="citrus";
									       	   		$commission0 = ($invoiceamount * 0.0271);
									       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.0271));
									       		}
									       		else if($fetch3['razorpay'] == 'Y')
									       		{
									       			//$paymentmode="Razorpay";
									       			$paymentmode="ICICI";
									       	   		$commission0 = ($invoiceamount * 0.0236);
									       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.0236));
									       		}
									       		else
									       		{
									       			$paymentmode="ICICI";
									       	   		$commission0 = ($invoiceamount * (0.02729));
									       	   		$received0 = ($invoiceamount - ($invoiceamount * 0.02729));
									       		}
									       		
									       		//$commission = number_format($commission0,2);
												//$received = number_format($received0,2);
											}
								       }
							     }
							     else if($fetch['paymentmode'] == "Netbanking")
								 {
								    //$paymentmode="Razorpay";
								    $paymentmode="ICICI";
								    $commission0 = ($invoiceamount * 0.0236);
								    $received0 = ($invoiceamount - ($invoiceamount * 0.0236));
								 }
								       
								 else
								 {
								 	$paymentmode="ICICI";
								    $commission0 = ($invoiceamount * 0.02729);
								    $received0 = ($invoiceamount - ($invoiceamount * 0.02729));
								 }

							 
					 		}
					 		$commission = number_format($commission0,2);
							$received = number_format($received0,2);
				     }
					
				}
					   
				else
				{
					$invoiceamount=$fetch['receiptamount'];
					$commission = "";
					$received = "";
				}

			}
			elseif(!empty($fetch['matrixinvoiceno']))
			{
				$invoiceamount=$fetch['receiptamount'];
				$commission = "";
				$received = $receiptamount;
			}
			else
			{
				$invoiceamount=$fetch['receiptamount'];
				$commission = "";
				$received = $receiptamount;
			}
			
			
			
			
			/*if($fetch['paymentmode'] == "Netbanking")
			{
			    $paymentmode="Razorpay";
			    $commission = (netamount * 0.0206);
			    $received = (netamount - (netamount * 0.0206));
			 }
			       
			 else
			 {
			 	$paymentmode="ICICI";
			    $commission = (netamount * 0.02729);
			    $received = (netamount - (netamount * 0.02729));
			 }*/
			
			
			//if($fetch['paymentmode'] == "Netbanking")
			    //$paymentmode="creditordebit";
			//else
			    //$paymentmode = $fetch['paymentmode'];
			$chequeno=$fetch['chequeno'];
			$chequedate=$fetch['chequedate'];
			$drawnon=$fetch['drawnon'];
			$receiptremarks=$fetch['receiptremarks'];
			
			$customerdetails = $recieptslno.'^'.$invoiceno.'^'.$invoiceamount.'^'.$commission.'^'.$received.'^'.$paymentmode.'^'.$chequeno.'^'.$chequedate.'^'.$drawnon.'^'.trim($receiptremarks).'^'.$businessname . PHP_EOL; 
			$customerdetails .= PHP_EOL;
			
			$overallcontent .= $customerdetails ;
						
		}
	}
		 
	$values = $overallcontent;
	
	if($_SERVER['HTTP_HOST'] == "localhost")
		$addstring = "/imax/user";
	else
		$addstring = "/user";
		
	$query = 'select slno,username from inv_mas_users where slno = '.$userid.'';
	$fetchres = runmysqlqueryfetch($query);
	$localdate = datetimelocal('Ymd');
	$localtime = datetimelocal('His');
	$filebasename = "Invoiceimport".$localdate."-".$localtime."-".strtolower($fetchres['username']).".txt";
	
	$query1 ="INSERT INTO inv_logs_reports(userid,`date`,`time`,`type`,`data`,system) VALUES('".$userid."','".datetimelocal('Y-m-d')."','".datetimelocal('H-i')."','excel_receiptimport_report',\"".$query."\",'".$_SERVER['REMOTE_ADDR']."')";
	$result = runmysqlquery($query1);
	
	$filepath = $_SERVER['DOCUMENT_ROOT'].$addstring.'/filecreated/'.$filebasename;
	$downloadlink = 'http://'.$_SERVER['HTTP_HOST'].$addstring.'/filecreated/'.$filebasename;
	
	$fp = fopen($filepath, "w") ; 
	$numBytes = fwrite($fp, $values); 
	if($fp)
	{
		$line = fgets($fp);
		downloadfile($filepath);
		fclose($fp);
	}

?>