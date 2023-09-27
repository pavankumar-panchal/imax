function getdistrict(divid,statecode)
{
var districtcode = document.getElementById('district').value;

	if(checkdistrictlist(districtcode, statecode) == true)
        return false;

	switch(statecode)
	{
		case '':
			districtlist = '<select name="district" class="swiftselect-mandatory" id="district" style="width: 200px;"><option value="">Select A State First</option></select>';
			break;
			
<?php
include('../functions/phpfunctions.php');

$querystate = "SELECT distinct statecode FROM inv_mas_state order by statename;";
$resultstate = runmysqlquery($querystate);
while($fetchstate = mysql_fetch_array($resultstate))
{
	$grid .='case "'.$fetchstate['statecode'].'": districtlist = \'';
	$query = "SELECT districtcode,districtname FROM inv_mas_district WHERE statecode = '".$fetchstate['statecode']."' order by districtname;";
	$result = runmysqlquery($query);
	$grid .= '<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A District</option>';
	while($fetch = mysql_fetch_array($result))
	{
		$grid .='<option value="'.$fetch['districtcode'].'">'.$fetch['districtname'].'</option>';
	}
	echo('</select>\'; break; ');
}

?>

	}
	document.getElementById(divid).innerHTML = districtlist;
    return true;
}

function checkdistrictlist(districtcode, statecode)
{
    var fullstatearray = new Array();

<?php
		$query1 = "SELECT distinct statecode FROM inv_mas_state order by statename";
		$result = runmysqlquery($query1);
		while($fetchstate = mysql_fetch_array($result))
		{
			$statecode =$fetchstate['statecode'];
			echo("\n");
			echo("fullstatearray['".$statecode."'] = new Array(");
			$query = "SELECT districtcode FROM inv_mas_district WHERE statecode = '".$statecode."' order by districtname;";
			$result2 = runmysqlquery($query);
			$count = 1;
			while($fetch = mysql_fetch_array($result2))
			{
				if($count > 1)
					echo(",");
				echo("'");
				echo($fetch['districtcode']);
				echo("'");
				$count++;
			}
			echo(");");
			echo("\n");
		}
?>
    if(in_array(districtcode,fullstatearray[statecode]))
		return true;
    else
		return false;
}


