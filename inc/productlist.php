<?
	$query = "SELECT productcode,productname FROM inv_mas_product order by productname;";
	$result = runmysqlquery($query);
  $grid = '<select name="productselectlist" size="5" class="swiftselect" id="productselectlist" style="width:210px; height:200px;" >';
  while($fetch = mysqli_fetch_array($result))
  {
   $grid .= '<option value="'.$fetch['productcode'].'^'.$fetch['productname'].'">'.$fetch['productname'].'</option>';
  }
  $grid .= '</select>';
  echo($grid);
?>