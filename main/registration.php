<?php

include('../functions/phpfunctions.php');
include "../aes/AESEncryption.php";

$HDDID = $_POST['HDDID'];
$ETHID = $_POST['ETHID'];
$CUSTID = $_POST['CUSTID'];
$PIN = $_POST['PIN'];
$PID = $_POST['PID'];
$REGTYPE = $_POST['REGTYPE'];
$custname="";
$date="";

//echo $HDDID ." - ". $ETHID ." - ". $CUSTID ." - ". $PIN ." - ". $PID ." - ". $REGTYPE ."<br><br>";
$output="";
if($REGTYPE=="new")
{
  $cardid=="";
  $valid1="false";
  $valid2="false";
  $pinvalid="false";
	
  $query1 = "select count(*) as con from inv_mas_scratchcard where scratchnumber='".$PIN."' and attached = 'yes' and registered = 'no' and blocked = 'no'";
  $result1 = runmysqlquery($query1);
  $fetch1 = mysqli_fetch_array($result1);
  if($fetch1['con'] > 0)
  {
	  $pinvalid="true";
  }
  /*
  if($cardid != "")
  {
	$query2 = "select count(*) as con from inv_dealercard  where cardid=".$cardid." and productcode = ".$PID."";
	//echo $query2."<br><br>";
	$result2 = runmysqlquery($query2);
	$fetch2 = mysqli_fetch_array($result2);
	$count1=  $fetch2['con'];
	if($count1>0)
	{
		$valid1="true";
	}
  }
  */
  if($pinvalid == "true")
  {
	  $query1 = "select inv_mas_scratchcard.cardid from inv_mas_scratchcard left join inv_dealercard on inv_mas_scratchcard.cardid = inv_dealercard.cardid where inv_mas_scratchcard.scratchnumber = '".$PIN."' and inv_dealercard.productcode = '".$PID."'";
	  $result1 = runmysqlquery($query1);
	  $resultcount = mysqli_num_rows($result1);
	  if($resultcount>0)
	  {
		  $valid1="true";
	  }
  }
  
 if($valid1=="true")
 {
	  $custvalid="false";
	  //$query3 = "select count(*) as con from dealer_online_purchase where customerreference = (select slno from inv_mas_customer where customerid='".$CUSTID."') and products like '%".$PID."'";
	  
	  $query3 = "select count(*) as con from dealer_online_purchase a 
  inner join inv_mas_customer on a.customerreference = inv_mas_customer.slno  
  where inv_mas_customer.customerid='".$CUSTID."' and a.products like '%".$PID."'";
	  //echo $query3."<br><br>";
	  $result3 = runmysqlquery($query3);	
	  $fetch3 = mysqli_fetch_array($result3);
	  $count2=  $fetch3['con'];
	  if($count2>0)
	  {
		  $valid2="true";
		  $custvalid="true";
	  }
	  if($valid2=="false")
	  {
		  $query4 = "select count(*) as con from inv_mas_customer where customerid='".$CUSTID."'";
		  $result4 = runmysqlquery($query4);	
		  $fetch4 = mysqli_fetch_array($result4);
		  $count3=  $fetch4['con'];
		  if($count3==0)
		  {
			  $custvalid="false";
		  }
	  }
  }
  
  if($valid1=="true" && $valid2=="true")
  {
	  $query5 = "select businessname from inv_mas_customer where customerid='".$CUSTID."'";
	  $result5 = runmysqlquery($query5);	
	  $fetch5 = mysqli_fetch_array($result5);
	  $custname=$fetch5['businessname'];
	  $output=$output .  "<ROOT>";
	  
		$output=$output .  "<LICENSE>";   
		  $output=$output .  "<HDDID>". AESEncryptCtr($HDDID,$AES_SEC_KEY,256)."</HDDID>";
		  $output=$output .  "<ETHID>". AESEncryptCtr($ETHID,$AES_SEC_KEY,256)."</ETHID>";
		  $output=$output .  "<CUSTID>". AESEncryptCtr($CUSTID,$AES_SEC_KEY,256)."</CUSTID>";
		  $output=$output .  "<CUSTNAME>".AESEncryptCtr($custname,$AES_SEC_KEY,256)."</CUSTNAME>";
		  $output=$output .  "<PIN>". AESEncryptCtr($PIN,$AES_SEC_KEY,256)."</PIN>";
		  $output=$output .  "<PID>". AESEncryptCtr($PID,$AES_SEC_KEY,256)."</PID>";
		  $output=$output .  "<DOR>". AESEncryptCtr(date("Y-m-d"),$AES_SEC_KEY,256)."</DOR>";
		$output=$output .  "</LICENSE>";
		  
	  $output=$output .  "</ROOT>";	  
  }
  else
  {
	  $output=$output .  "<ROOT>";	  
	  
		$output=$output .  "<ERROR>";
		if($valid1=="true" && $valid2=="false")
		{
		  if($custvalid=="false")
		  {
			  $output=$output .  "<DESC>Wrong customerid.</DESC>";
		  }
		  else
		  {
			  $output=$output .  "<DESC>Customerid and product are not matching.</DESC>";
		  }
		}
		else if($valid1=="false")
		{
			if($pinvalid=="false")
			{
			  $output=$output .  "<DESC>Invalid PIN Number.</DESC>";
			}
			else
			{
			  $output=$output .  "<DESC>PIN Number and product are not matching.</DESC>";
			}
		}
		else
		{
			  $output=$output .  "<DESC>Given details are wrong.</DESC>";
		}
		$output=$output .  "</ERROR>";
		
	  $output=$output .  "</ROOT>";
  }
}
echo $output;

/*
//echo "Encrypted : ".$output;

//Decryption

$HDDID = AESEncryptCtr($HDDID,$AES_SEC_KEY,256);
$ETHID = AESEncryptCtr($ETHID,$AES_SEC_KEY,256);
$CUSTID = AESEncryptCtr($CUSTID,$AES_SEC_KEY,256);
$PIN = AESEncryptCtr($PIN,$AES_SEC_KEY,256);
$PID = AESEncryptCtr($PID,$AES_SEC_KEY,256);
$REGTYPE = AESEncryptCtr($REGTYPE,$AES_SEC_KEY,256);
$custname=AESEncryptCtr($custname,$AES_SEC_KEY,256);
$date=AESEncryptCtr(date("Y-m-d"),$AES_SEC_KEY,256);

$output="";

$output=$output .  "<ROOT>";
  $output=$output .  "<LICENSE>";  
	$output=$output .  "<HDDID>". AESDecryptCtr($HDDID,$AES_SEC_KEY,256) ."</HDDID>";
	$output=$output .  "<ETHID>". AESDecryptCtr($ETHID,$AES_SEC_KEY,256) ."</ETHID>";
	$output=$output .  "<CUSTID>". AESDecryptCtr($CUSTID,$AES_SEC_KEY,256) ."</CUSTID>";
	$output=$output .  "<CUSTNAME>". AESDecryptCtr($custname,$AES_SEC_KEY,256) ."</CUSTNAME>";
	$output=$output .  "<PIN>". AESDecryptCtr($PIN,$AES_SEC_KEY,256) ."</PIN>";
	$output=$output .  "<PID>". AESDecryptCtr($PID,$AES_SEC_KEY,256) ."</PID>";
	$output=$output .  "<DOR>". AESDecryptCtr($date,$AES_SEC_KEY,256) ."</DOR>";
  $output=$output .  "</LICENSE>";	
$output=$output .  "</ROOT>";

echo "<br><br>Decryption : ".$output;
*/
?>