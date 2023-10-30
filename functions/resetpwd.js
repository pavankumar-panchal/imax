function generatecustomerregistration(id,startlimit)
{
	
	var form = document.submitform;
	document.getElementById('lastslno').value = id;	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent(form.lastslno.value) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	ajaxcall4 = createajax();
	document.getElementById('tabgroupgridc1_1').innerHTML = getprocessingimage();
	document.getElementById('tabgroupgridc1link').innerHTML ='';
	ajaxcall4.open("POST", queryString, true);
	ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
		{
			if(ajaxcall4.status == 200)
			{
				var ajaxresponse = ajaxcall4.responseText;//alert(ajaxresponse)
				var response = ajaxresponse.split('^');
				gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
				document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[1];
				document.getElementById('tabgroupgridc1_1').innerHTML =  response[0];
				document.getElementById('tabgroupgridc1link').innerHTML =  response[2];
				
			}
			else
				document.getElementById('tabgroupgridc1_1').innerHTML =scripterror();
		}
	}
	ajaxcall4.send(passData);
}// JavaScript Document