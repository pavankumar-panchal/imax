<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');

if(imaxgetcookie('userid')<> '') 
{
    $userid = imaxgetcookie('userid');
}
else
{ 
	echo(json_encode('Thinking to redirect'));
	exit;
}

//echo "bhumika";
 $switchtype = $_POST['switchtype'];

 //$switchtype='checkuploaddata';

switch($switchtype)
{
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
					//print_r($receiptdata);
					//echo $num = count($receiptdata);
					$grid = '<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid" style="font-size:11px" id="griddata"><thead>';
					$grid .= '<tr class="tr-grid-header">
								<td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
								<td nowrap = "nowrap" class="td-border-grid" align="left">Receipt No</td>
								<td nowrap = "nowrap" class="td-border-grid" align="left">Status</td>
								</tr></thead><tbody>';
					$slnocount = 1;	
					$i_n = 0;
					$receiptdatacount = 0;
					$receiptdata=array();
					$a = array();
					 while (($receiptdata = fgetcsv($handle, 1000, ",")) !== FALSE) 
					  {
						 $num = count($receiptdata) ;
						 //echo "<p> $num fields in line $row: <br /></p>\n";
						   //echo $receiptdatacount = $num;
						   for($i=0;$i<$num;$i++) 
						   {
						        if($receiptdata[$i]!="")
						        {
							        $color;
							        $i_n++;
									if($i_n%2 == 0)
										$color = "#edf4ff";
									else
										$color = "#f7faff";

									//$receiptdatacount += $num ;
							      	$query = "select count(*) as receiptcount from inv_mas_receipt where slno ='".$receiptdata[$i]."'";
									$fetch = runmysqlqueryfetch($query);
									$count = $fetch['receiptcount'];
									if($count > 0)
									{
										
										$query3 = "update inv_mas_receipt set reconsilation = 'matched' where slno = '".$receiptdata[$i]."' and imported = 'N'";
										$result3 = runmysqlquery($query3);
										$matched = "Matched". "\n";
									}
									else
									{
										$matched = "Not Matched". "\n";
									}

									
									if(!in_array($receiptdata[$i],$a,true))
									{
										array_push($a,$receiptdata[$i]);
										//print_r($a);

									$grid .= '<tr bgcolor='.$color.' class="gridrow" align="left" >';
									$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$slnocount++."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$receiptdata[$i]."</td>";
									$grid .= "<td nowrap='nowrap' class='td-border-grid' align='left'>".$matched."</td>";
									$grid .= "</tr>";
								 }
								}
								//$receiptdatacount += $num ;
						    }
					  }
				 
					  fclose($handle);
					  $grid .= "</tbody></table>";
					  //echo $receiptdatacount;
					  $receiptdatacount = count($a);
					  echo "1^".$grid.'^'.$receiptdatacount;
					  //echo $grid;
				}
				else
				{
					echo "not readeable";
				}
			//}
			//else
			//{
				//echo "2^File is already exists.";
			//}

			//echo $numcount = count($num);
			
			//echo json_encode($getreceipt);
		}     
	}
	break;

}
?>