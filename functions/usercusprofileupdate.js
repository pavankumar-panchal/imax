

function productdetailstoform(slno,cusid)
{
	if(slno != '' && cusid != '')
	{
		document.getElementById('form-error').innerHTML = '';
		var form = document.submitform;
		form.reset();
		var passData = "changetype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid )+ "&lastupdateslno=" + encodeURIComponent(slno)  + "&dummy=" + Math.floor(Math.random()*100032680100);
		alert(passData);
		ajaxcall4 = createajax();
		document.getElementById('productselectionprocess').innerHTML = getprocessingimage();
		var queryString = "../ajax/usercusprofileupdate.php";
		ajaxcall4.open("POST", queryString, true);
		ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall4.onreadystatechange = function()
		{
			if(ajaxcall4.readyState == 4)
			{
				if(ajaxcall4.status == 200)
				{
					onloadenabled();
					document.getElementById('productselectionprocess').innerHTML = '';
					var response = (ajaxcall4.responseText).split("^");alert(response)
					form.extcusid.value = response[34];//alert( response[34])
					form.extbusinessname.value = response[18];
					form.extcontactperson.value = response[19];
					form.extaddress.value = response[20];
					form.extplace.value = response[21];
					form.extdistrict.value = response[32];//alert(response[32])
					form.extstate.value = response[33];//alert(response[33])
					form.extpincode.value = response[24];
					form.extstdcode.value = response[30];
					form.extphone.value = response[26];
					form.extcell.value = response[27];
					form.extcategory.value = response[37];
					form.extpassword.value = response[45];
					form.extregion.value = response[47];
					autocheck(form.extdisablelogin, response[41]);
					autocheck(form.extcorporateorder, response[43]);
					form.extcurrentdealer.value = response[39];
					form.extemailid.value = response[28];
					form.extwebsite.value = response[29];
					form.exttype.value = response[31];
					form.extcategory.value = response[25];
					form.newbusinessname.value = response[2];
					form.newcontactperson.value = response[3];
					form.newaddress.value = response[4];
					form.newplace.value = response[5];
					form.newstate.value = response[7];
					custnewdistrictcodeFunction('newdistrict',response[6]);//alert(response[7])
					form.newpincode.value = response[8];
					form.newstdcode.value = response[14];
					form.newphone.value = response[10];
					form.newcell.value = response[11];
					form.newfax.value = response[36];
					form.newcurrentdealer.value = response[38];
					form.newpassword.value = response[44];
					form.newregion.value = response[46];
					autocheck(form.newdisablelogin, response[40]);
					autocheck(form.newcorporateorder, response[42]);
					form.newemailid.value = response[12];
					form.newwebsite.value = response[13];//alert(response[15])
					form.newtype.value = response[15];
					form.newcategory.value = response[9];//alert(response[9])
					form.lastslno.value = response[17];
					form.extdistrictcode.value = response[22];
					form.extstatecode.value = response[23];
					document.getElementById('createddate').innerHTML = response[35];//alert(response[35])
					//alert(response);

				}
				else
					document.getElementById('productselectionprocess').innerHTML = scripterror();
			}
		}
		ajaxcall4.send(passData);
	}
}



function usercustomerprofiledatagrid(startlimit)
{
	var passData = "changetype=customergenerategrid&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall0 = createajax(); 
	document.getElementById('productselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/usercusprofileupdate.php";
	ajaxcall0.open("POST", queryString, true);
	ajaxcall0.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall0.onreadystatechange = function()
	{//alert(passData);
		if(ajaxcall0.readyState == 4)
		{
			if(ajaxcall0.status == 200)
			{
				document.getElementById('productselectionprocess').innerHTML = '';
				var ajaxresponse = ajaxcall0.responseText;
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{//alert(response[1])
					document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
					document.getElementById('tabgroupgridc1_1').innerHTML =  response[1];
					document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
				}
				else if(response[0] == 2)
				{
					document.getElementById('productselectionprocess').innerHTML = '';
					document.getElementById('tabgroupgridc1_1').innerHTML =  response[1];
					document.getElementById('tabgroupgridc1link').innerHTML =  '';
					document.getElementById('tabgroupgridwb1').innerHTML ='';
				}
				
			}
			else
				document.getElementById('productselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall0.send(passData);
}

//Function to "Show more records" of Customer module
function getmorecustdetails(startlimit,slno)
{
	var passData = "changetype=customergenerategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno) +"&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall1 = createajax(); 
	document.getElementById('productselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/usercusprofileupdate.php";
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{//alert(passData);
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var ajaxresponse = ajaxcall1.responseText;//alert(ajaxresponse);
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
					document.getElementById('productselectionprocess').innerHTML ='';
					document.getElementById('custresultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
					document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('custresultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ response[1] ;
					document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
				}
				else if(response[0] == '2')
				{
					document.getElementById('productselectionprocess').innerHTML =response[1];
				}
			}
			else
				document.getElementById('productselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall1.send(passData);
}




function customeractioncheck(radiovalue, fieldcaption)
{
	if(radiovalue == 'customerbusiness_none')
	{
		error.innerHTML = errormessage('You have to either Approve or Reject "' + fieldcaption + '" Field ');
		return false;
	}
	return true;
}
function clearscreen()
{
	var form = document.submitform;
	form.reset();
	form.lastslno.value = '';
	//document.getElementById('form-error').innerHTML = '';
	document.getElementById('createddate').innerHTML = 'Not Available';
	onloaddisabled();
}
function screenclear()
{
	var form = document.submitform;
	form.reset();
	form.lastslno.value = '';
	document.getElementById('form-error').innerHTML = '';
	document.getElementById('createddate').innerHTML = 'Not Available';
	onloaddisabled();
}

function update()
{ 

	var form = document.submitform; 
	var error = document.getElementById('form-error');
	if(form.lastslno.value == '')
	{
		error.innerHTML = errormessage('Please Select a Customer Record from the Grid. ');return false;
	}
	{
	var customerbusiness_action = customer_getradiovalue(form.customerbusiness_type);
	if(customerbusiness_action == 'none' || customerbusiness_action == 'approve' )
	{
		var field = form.newbusinessname;
		if(!field.value) { error.innerHTML = errormessage("Enter the Business Name [Company]. "); field.focus(); return false; }
		if(field.value) { if(!validatebusinessname(field.value)) { error.innerHTML = errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.'); field.focus(); return false; } }
	}
	var customercontact_action = customer_getradiovalue(form.customercontact_type);
	if(customercontact_action == 'none' || customercontact_action == 'approve')
	{
		var field = form.newcontactperson;
		if(!field.value) { error.innerHTML = errormessage("Enter the Name of the Contact Person. "); field.focus(); return false; }
		if(field.value) { if(!validatecontactperson(field.value)) { error.innerHTML = errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space / comma.'); field.focus(); return false; } }
	}
	var customerplace_action = customer_getradiovalue(form.customerplace_type);
	if(customerplace_action == 'none' || customerplace_action == 'approve')
	{
		var field = form.newplace;
		if(!field.value) { error.innerHTML = errormessage("Enter the Place. "); field.focus(); return false; }
	}
	var customerstate_action = customer_getradiovalue(form.customerstate_type);
	if(customerstate_action == 'none' || customerstate_action == 'approve')
	{
		var field = form.newstate;
		if(!field.value) { error.innerHTML = errormessage("Select the State. "); field.focus(); return false; }
	}
	var customerdistrict_action = customer_getradiovalue(form.customerdistrict_type);
	if(customerdistrict_action == 'none' || customerdistrict_action == 'approve')
	{
		var field = form.newdistrict;
		if(!field.value) { error.innerHTML = errormessage("Select the District. "); field.focus(); return false; }
	}
	var customerpincode_action = customer_getradiovalue(form.customerpincode_type);
	if(customerpincode_action == 'none' || customerpincode_action == 'approve')
	{
		var field = form.newpincode;
		if(field.value) { if(!validatepincode(field.value)) { error.innerHTML = errormessage('Enter the valid PIN Code.'); field.focus(); return false; } }
	}
	var customerregion_action = customer_getradiovalue(form.customerregion_type);
	if(customerregion_action == 'none' || customerregion_action == 'approve')
	{
		var field = form.newregion;
		if(!field.value) { error.innerHTML = errormessage("Select a Region"); field.focus(); return false; }

	}
	var customerstd_action = customer_getradiovalue(form.customerstd_type);
	if(customerstd_action == 'none' || customerstd_action == 'approve')
	{
		var field = form.newstdcode;
		if(field.value) { if(!validatestdcode(field.value)) { error.innerHTML = errormessage('Enter the valid STD Code.'); field.focus(); return false; } }
	}
	var customerphone_action = customer_getradiovalue(form.customerphone_type);
	if(customerphone_action == 'none' || customerphone_action == 'approve')
	{
		var field = form.newphone;
		if(!field.value) { error.innerHTML = errormessage("Enter the Phone Number. "); field.focus(); return false; }
		if(field.value) { if(!validatephone(field.value)) { error.innerHTML = errormessage('Enter the valid Phone Number.'); field.focus(); return false; } }
	}
	var customercell_action = customer_getradiovalue(form.customercell_type);
	if(customercell_action == 'none' || customercell_action == 'approve')
	{
		var field = form.newcell;
		if(!field.value) { error.innerHTML = errormessage("Enter the Cell Number. "); field.focus(); return false; }
		if(field.value) { if(!validatecell(field.value)) { error.innerHTML = errormessage('Enter the valid Cell Number.'); field.focus(); return false; } }
	}
	var customeremail_action = customer_getradiovalue(form.customeremail_type);
	if(customeremail_action == 'none' || customeremail_action == 'approve')
	{
		var field = form.newemailid;
		if(!field.value) { error.innerHTML = errormessage("Enter the Email ID. "); field.focus(); return false; }
		if(field.value)	{ if(!emailvalidation(field.value)) { error.innerHTML = errormessage('Enter the valid Email ID.'); field.focus(); return false; } }
	}
	var customerwebsite_action = customer_getradiovalue(form.customerwebsite_type);
	if(customerwebsite_action == 'none' || customerwebsite_action == 'approve')
	{
		var field = form.newwebsite;
		if(field.value)	{ if(!validatewebsite(field.value)) { error.innerHTML = errormessage('Enter the valid Website.'); field.focus(); return false; } }
	}
	var customerdealer_action = customer_getradiovalue(form.customerdealer_type);
	if(customerdealer_action == 'none' || customerdealer_action == 'approve')
	{
		var field = form.newcurrentdealer;
		if(!field.value) { error.innerHTML = errormessage("Select a Dealer "); field.focus(); return false; }
	}
	var customerpassword_action = customer_getradiovalue(form.customerpassword_type);
	if(customerpassword_action == 'none' || customerpassword_action == 'approve')
	{
		var field = form.newpassword;
	}
	var customerdisablelogin_action = customer_getradiovalue(form.customerdisablelogin_type);
	if(customerdisablelogin_action == 'none' || customerdisablelogin_action == 'approve')
	{
		var field = form.newdisablelogin;
		if(field.checked == true) var newdisablelogin = 'yes'; else newdisablelogin = 'no';
	}
	var customercoporateorder_action = customer_getradiovalue(form.customercoporateorder_type);
	if(customercoporateorder_action == 'none' || customercoporateorder_action == 'approve')
	{
		var field = form.newcorporateorder;
		if(field.checked == true) var newcorporateorder = 'yes'; else newcorporateorder = 'no';
	}
	var customerbusiness_action = customer_getradiovalue(form.customerbusiness_type);
	if(customerbusiness_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Business Name" Field ');return false;}
	var customercontact_action = customer_getradiovalue(form.customercontact_type);
	if(customercontact_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Contact Person" Field ');return false;}	
	var customeraddress_action = customer_getradiovalue(form.customeraddress_type);
	if(customeraddress_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Address" Field ');return false;}
	var customerplace_action = customer_getradiovalue(form.customerplace_type);
	if(customerplace_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Place" Field ');return false;}	 
	var customerstate_action = customer_getradiovalue(form.customerstate_type);
	if(customerstate_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "State" Field ');return false;}	 
	var customerdistrict_action = customer_getradiovalue(form.customerdistrict_type);
	if(customerdistrict_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "District" Field ');return false;}	 
	var customerpincode_action = customer_getradiovalue(form.customerpincode_type);
	if(customerpincode_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Pin code" Field ');return false;}	 
	if(customerregion_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Region" Field ');return false;}	 	 
	var customerstd_action = customer_getradiovalue(form.customerstd_type);
	if(customerstd_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "STD code" Field ');return false;}	 
	var customerphone_action = customer_getradiovalue(form.customerphone_type);
	if(customerphone_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Phone Number" Field ');return false;}	 
	var customercell_action = customer_getradiovalue(form.customercell_type);
	if(customercell_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Cell Number" Field ');return false;}
	var customerfax_action = customer_getradiovalue(form.customerfax_type);
	 if(customerfax_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Fax" Field ');return false;}	
	var customeremail_action = customer_getradiovalue(form.customeremail_type);
	if(customeremail_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Emailid" Field ');return false;}	 
	var customerwebsite_action = customer_getradiovalue(form.customerwebsite_type);
	if(customerwebsite_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Website" Field ');return false;}	 
	var customertype_action = customer_getradiovalue(form.customertype_type);
	if(customertype_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Type" Field ');return false;}	
	var customercategory_action = customer_getradiovalue(form.customercategory_type);
	if(customercategory_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Category" Field ');return false;}

		 
	var customerdealer_action = customer_getradiovalue(form.customerdealer_type);
	if(customerdealer_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Dealer" Field ');return false;}
	var customerpassword_action = customer_getradiovalue(form.customerpassword_type);
	if(customerpassword_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Password" Field ');return false;}
		 
	var customerdisablelogin_action = customer_getradiovalue(form.customerdisablelogin_type);
	if(customerdisablelogin_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Disable Login" Field ');return false;}
		 
	var customercoporateorder_action = customer_getradiovalue(form.customercoporateorder_type);
	if(customercoporateorder_action == 'none')
		 { error.innerHTML = errormessage('You have to either Approve or Reject "Corporate Order" Field ');return false;}
		 
	
	var passdata ="changetype=processupdate&lastslno=" + encodeURIComponent(form.lastslno.value) + "&customerbusiness_action=" + customerbusiness_action + "&customercontact_action=" + customercontact_action + "&customeraddress_action=" + customeraddress_action + "&customerplace_action=" + customerplace_action + "&customerstate_action=" + customerstate_action + "&customerdistrict_action=" + customerdistrict_action + "&customerpincode_action=" + customerpincode_action + "&customerstd_action=" + customerstd_action + "&customerphone_action=" + customerphone_action + "&customercell_action=" + customercell_action + "&customerfax_action=" + customerfax_action + "&customeremail_action=" + customeremail_action + "&customerwebsite_action=" + customerwebsite_action + "&customertype_action=" + customertype_action + "&customercategory_action=" + customercategory_action+ "&customerdealer_action=" + customerdealer_action+ "&customerpassword_action=" + customerpassword_action + "&customerdisablelogin_action=" + customerdisablelogin_action + "&customerregion_action=" + customerregion_action+ "&customercoporateorder_action=" + customercoporateorder_action+ "&customerregion_action=" + customerregion_action + "&newbusinessname=" + encodeURIComponent(form.newbusinessname.value) + "&newcontactperson=" + encodeURIComponent(form.newcontactperson.value) + "&newaddress=" + encodeURIComponent(form.newaddress.value) + "&newplace=" + encodeURIComponent(form.newplace.value) + "&newdistrict=" + encodeURIComponent(form.newdistrict.value) + "&newpincode=" + encodeURIComponent(form.newpincode.value) + "&newstdcode=" + encodeURIComponent(form.newstdcode.value) + "&newphone=" + encodeURIComponent(form.newphone.value) + "&newcell=" + encodeURIComponent(form.newcell.value) + "&newfax=" + encodeURIComponent(form.newfax.value) + "&newemailid=" + encodeURIComponent(form.newemailid.value) + "&newwebsite=" + encodeURIComponent(form.newwebsite.value) + "&newtype=" + encodeURIComponent(form.newtype.value) + "&newcategory=" + encodeURIComponent(form.newcategory.value) + "&newpassword=" + encodeURIComponent(form.newpassword.value)+ "&newcorporateorder=" + encodeURIComponent(newcorporateorder)+ "&newcurrentdealer=" + encodeURIComponent(form.newcurrentdealer.value)+ "&newregion=" + encodeURIComponent(form.newregion.value)+ "&newdisablelogin=" + encodeURIComponent(newdisablelogin) + "&requestfrom=" + encodeURIComponent(form.requesthiddenfrom.value) +  "&dummy=" + Math.floor(Math.random()*100032680100);
	alert(passdata);
	var ajaxcall9 = createajax(); 
	document.getElementById('form-error').innerHTML = getprocessingimage();
	queryString = "../ajax/usercusprofileupdate.php";
	ajaxcall9.open("POST", queryString, true);
	ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall9.onreadystatechange = function()
	{
		if(ajaxcall9.readyState == 4)
		{
			if(ajaxcall9.status == 200)
			{
				requestfrom = form.requesthiddenfrom.value;//alert(requestfrom)
				document.getElementById('form-error').innerHTML = '';
				var response = ajaxcall9.responseText.split('^');
				if(response[0] == '1')
				{
					error.innerHTML = successmessage(response[1]);
					clearscreen();//alert(response)
					usercustomerprofiledatagrid('');
				}
				else
				{
					error.innerHTML = errormessage('Unable to process customer record.');
				}
			}
			else
				document.getElementById('productselectionprocess').innerHTML = scripterror();
		}
		
	}
	ajaxcall9.send(passdata);
	
	}
	
	
}


//Function to get the district code according to the state selected---------------------------------------------
function custnewdistrictcodeFunction(selectid, comparevalue)
{
	var statecode = document.getElementById('newstate').value; 
	var districtDisplay = document.getElementById('districtcodedisplay');
	var passData = "statecode=" + statecode  + "&dummy=" + Math.floor(Math.random()*1100011000000);//alert(passData)
	ajaxcalld = createajax();
	var queryString = "../ajax/selectdistrictonstatenew.php";//alert(queryString)
	ajaxcalld.open("POST", queryString, true);
	ajaxcalld.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcalld.onreadystatechange = function()
	{
		if(ajaxcalld.readyState == 4)
		{
			districtDisplay.innerHTML = ajaxcalld.responseText;
			if(selectid && comparevalue)
			autoselect(selectid, comparevalue);
		}
	}
	ajaxcalld.send(passData);
}	


//Function to select the particular option in <SELECT> Tag, with the compare value----------------------------------
function autoselect(selectid,comparevalue)
{
	var selection = document.getElementById(selectid);
	for(var i = 0; i < selection.length; i++) 
	{
		if(selection[i].value == comparevalue)
		{
			selection[i].selected = "1";
			return;
		}
	}
}

function onloadenabled()
{
	document.getElementById('newbusinessname').disabled = false;
	document.getElementById('newcontactperson').disabled = false;
	document.getElementById('newaddress').disabled = false;
	document.getElementById('newplace').disabled = false;
	document.getElementById('newstate').disabled = false;
	document.getElementById('newdistrict').disabled = false;
	document.getElementById('newpincode').disabled = false;
	document.getElementById('newstdcode').disabled = false;
	document.getElementById('newphone').disabled = false;
	document.getElementById('newcell').disabled = false;
	document.getElementById('newfax').disabled = false;
	document.getElementById('newemailid').disabled = false;
	document.getElementById('newwebsite').disabled = false;
	document.getElementById('newtype').disabled = false;
	document.getElementById('newcategory').disabled = false;
	document.getElementById('newcurrentdealer').disabled = false;
	document.getElementById('newpassword').disabled = false;
	document.getElementById('newregion').disabled = false;
}

function onloaddisabled()
{
	document.getElementById('newbusinessname').disabled = true;
	document.getElementById('newcontactperson').disabled = true;
	document.getElementById('newaddress').disabled = true;
	document.getElementById('newplace').disabled = true;
	document.getElementById('newstate').disabled = true;
	document.getElementById('newdistrict').disabled = true;
	document.getElementById('newpincode').disabled = true;
	document.getElementById('newstdcode').disabled = true;
	document.getElementById('newphone').disabled = true;
	document.getElementById('newcell').disabled = true;
	document.getElementById('newfax').disabled = true;
	document.getElementById('newemailid').disabled = true;
	document.getElementById('newwebsite').disabled = true;
	document.getElementById('newtype').disabled = true;
	document.getElementById('newcategory').disabled = true;
	document.getElementById('newcurrentdealer').disabled = true;
	document.getElementById('newpassword').disabled = true;
	document.getElementById('newregion').disabled = true;
}



//function to get the radio value
function customer_getradiovalue(radioname)
{
	
	if(radioname.value)
		return radioname.value;
	else
	for(var i = 0; i < radioname.length; i++) 
	{
		if(radioname[i].checked) {
			return radioname[i].value;
		}
	}
}


//function to validate the contact person
function validatecontactperson(contactname)
{
 var numericExpression = /^([A-Z\s-()]+[a-zA-Z\s-()])(?:(?:[,;]([A-Z\s-()]+[a-zA-Z\s-()])))*$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}
//function to validate the business name
function validatebusinessname(contactname)
{
 var numericExpression = /^([A-Z0-9\s\-()]+[a-zA-Z0-9\s-()])(?:(?:[,;]([A-Z0-9\s-()]+[a-zA-Z0-9\s-()])))*$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}
/*function validatewebsite(website)
{
	var websiteExpression = /^(www\.)?[a-zA-Z0-9-\.,]+\.(com|org|net|mil|edu|ca|co.uk|com.au|gov|co.in|in)$/i;
	if(website.match(websiteExpression)) return true;
	else return false;
}*/
function emailvalidation(emailid)
{
	var emailExp = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}(?:(?:[,;][A-Z0-9\._%-]+@[A-Z0-9\.-]+))*$/i;
	if(emailid.match(emailExp)) { return true; }
	else { return false; }
}
function validatestdcode(stdcodenumber)
{
	var numericExpression = /^[0]+[0-9]{2,4}$/i;
	if(stdcodenumber.match(numericExpression)) return true;
	else return false;
}
function validatepincode(pincodenumber)
{
	var numericExpression = /^[^0]+[0-9]{5}$/i;
	if(pincodenumber.match(numericExpression)) return true;
	else return false;
}
function validatephone(phonenumber)
{
	var numericExpression = /^([^9]\d{5,9})(?:(?:[,;]([^9]\d{5,9})))*$/i;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}
function validatecell(cellnumber)
{
	var numericExpression = /^[9]+[0-9]{9,9}(?:(?:[,;][9]+[0-9]{9,9}))*$/i;
	if(cellnumber.match(numericExpression)) return true;
	else return false;
}



function combineMenus() {
	
	var form= document.submitform;
	var customerselectall = document.getElementById('customerselectall_reject');
	var changestatus = (customerselectall.checked == true)?true:false;
	for (var i=0; i < form.customerbusiness_type.length; i++)
	{
			form.customerbusiness_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customercontact_type.length; i++)
	{
			form.customercontact_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customeraddress_type.length; i++)
	{
			form.customeraddress_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerplace_type.length; i++)
	{
			form.customerplace_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerstate_type.length; i++)
	{
			form.customerstate_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerdistrict_type.length; i++)
	{
			form.customerdistrict_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerpincode_type.length; i++)
	{
			form.customerpincode_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerregion_type.length;i++)
	{
			form.customerregion_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerstd_type.length; i++)
	{
			form.customerstd_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerphone_type.length; i++)
	{
			form.customerphone_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customercell_type.length; i++)
	{
			form.customercell_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerfax_type.length; i++)
	{
			form.customerfax_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customeremail_type.length; i++)
	{
			form.customeremail_type[i].checked = changestatus;
	}for (var i=0; i < form.customerwebsite_type.length; i++)
	{
			form.customerwebsite_type[i].checked = changestatus;
	}for (var i=0; i < form.customertype_type.length; i++)
	{
			form.customertype_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customercategory_type.length; i++)
	{
			form.customercategory_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerdealer_type.length;i++)
	{
			form.customerdealer_type[i].checked = changestatus;
	}
		for (var i=0; i < form.customerpassword_type.length;i++)
	{
			form.customerpassword_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customerdisablelogin_type.length;i++)
	{
			form.customerdisablelogin_type[i].checked = changestatus;
	}
	for (var i=0; i < form.customercoporateorder_type.length;i++)
	{
			form.customercoporateorder_type[i].checked = changestatus;
	}

}

function selectall() {
	
	var form= document.submitform;
	var customerselectall = document.getElementById('customerselectall_approve');
	var changestatus = (customerselectall.checked == true)?true:false;
	for (var i=0; i < 2;i++)
	{
			form.customerbusiness_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customercontact_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customeraddress_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerplace_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerstate_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerdistrict_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerpincode_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerstd_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerphone_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customercell_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerfax_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customeremail_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customerwebsite_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customertype_type[i].checked = changestatus;
	}
	for (var i=0; i < 2;i++)
	{
			form.customercategory_type[i].checked = changestatus;
	}
		for (var i=0; i < 2;i++)
	{
			form.customerdealer_type[i].checked = changestatus;
	}
		for (var i=0; i < 2;i++)
	{
			form.customerpassword_type[i].checked = changestatus;
	}
		for (var i=0; i < 2;i++)
	{
			form.customerdisablelogin_type[i].checked = changestatus;
	}
		for (var i=0; i < 2;i++)
	{
			form.customercoporateorder_type[i].checked = changestatus;
	}
		for (var i=0; i < 2;i++)
	{
			form.customerregion_type[i].checked = changestatus;
	}

}

function allnone() {
	
	var form= document.submitform;
	var customerselectall = document.getElementById('customerselectall_none');
	var changestatus = (customerselectall.checked == true)?true:false;
	for (var i=2; i < form.customerbusiness_type.length;i--)
	{
			form.customerbusiness_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customercontact_type.length;i--)
	{
			form.customercontact_type[0].checked = changestatus;
			break;
	}
	
	for (var i=2; i <form.customeraddress_type.length;i--)
	{
			form.customeraddress_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerplace_type.length;i--)
	{
			form.customerplace_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i <form.customerstate_type.length;i--)
	{
			form.customerstate_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i <form.customerdistrict_type.length;i--)
	{
			form.customerdistrict_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerpincode_type.length;i--)
	{
			form.customerpincode_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerstd_type.length;i--)
	{
			form.customerstd_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerphone_type.length;i--)
	{
			form.customerphone_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customercell_type.length;i--)
	{
			form.customercell_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerfax_type.length;i--)
	{
			form.customerfax_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customeremail_type.length;i--)
	{
			form.customeremail_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerwebsite_type.length;i--)
	{
			form.customerwebsite_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customertype_type.length;i--)
	{
			form.customertype_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customercategory_type.length;i--)
	{
			form.customercategory_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerdealer_type.length;i--)
	{
			form.customerdealer_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerpassword_type.length;i--)
	{
			form.customerpassword_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerdisablelogin_type.length;i--)
	{
			form.customerdisablelogin_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customercoporateorder_type.length;i--)
	{
			form.customercoporateorder_type[0].checked = changestatus;
			break;
	}
	for (var i=2; i < form.customerregion_type.length;i--)
	{
			form.customerregion_type[0].checked = changestatus;
			break;
	}
}
//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtabcus3(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 3;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();//alert(document.getElementById(tabcontent).style.display)
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();//alert(document.getElementById(tabcontent).style.display)
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

