function getproduct(divid,schemeid)
{
	switch(schemeid)
	{
			
<?
include('../functions/phpfunctions.php');
	
	$queryproduct = "SELECT distinct slno FROM inv_mas_scheme ORDER BY schemename;";
	$resultproduct = runmysqlquery($queryproduct);
	while($fetchproduct = mysqli_fetch_array($resultproduct))
	{
		echo('case "'.$fetchproduct['slno'].'": productlist = \'');
		$query = "select distinct inv_mas_scheme.todate,inv_mas_product.productcode,inv_mas_product.productname 
		from  inv_schemepricing 
		left join inv_mas_product on inv_mas_product.productcode = inv_schemepricing.product
		left join inv_mas_scheme on inv_schemepricing.scheme = inv_mas_scheme.slno where inv_mas_product.allowdealerpurchase = 'yes' and  inv_schemepricing.scheme = '".$fetchproduct['slno']."'  and inv_mas_scheme.todate > curdate()
		union
		select subgroup,productcode,productname from inv_mas_product where subgroup = 'ESS' ;";

		$result = runmysqlquery($query);

		$grid = '<select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:200px;"  onchange="getamount(); getproductgroup();";><option value="">Select A product</option>';
		while($fetch = mysqli_fetch_array($result))
		{
			$grid .= '<option value="'.$fetch['productcode'].'">'.$fetch['productname'].'</option>';
		}
		$grid .='</select>\'; break; ';
		echo $grid;
	}

	

?>

	}
	$('#'+divid).html(productlist);
}



