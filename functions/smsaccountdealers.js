
var dealerarray = new Array();
 

function formsubmit(command)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');

	var disablesmsaccount;
	var field = form.disablesmsaccount;
	if(field.checked == true) disablesmsaccount = 'yes'; else  disablesmsaccount = 'no';
	
	var croptext;
	var field = form.croptext;
	if(field.checked == true) croptext = 'yes'; else  croptext = 'no';
	
	var field = form.contactperson;
	if(!field.value) { error.innerHTML = errormessage("Enter the Contact Person Name . "); field.focus(); return false; }
	if(field.value) { if(!validatecontactperson(field.value)) { error.innerHTML = errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space.'); field.focus(); return false; } }
	
	var field = form.emailid;
	if(!field.value) { error.innerHTML = errormessage("Enter the Email ID. "); field.focus(); return false; }
	if(field.value)	{ if(!emailvalidation(field.value)) { error.innerHTML = errormessage('Enter the valid Email ID.'); field.focus(); return false; } }
	
	
	var field = form.cell;
	if(!field.value) { error.innerHTML = errormessage("Enter the Cell Number. "); field.focus(); return false; }
	if(field.value) { if(!validatecell(field.value)) { error.innerHTML = errormessage('Enter the valid Cell Number.'); field.focus(); return false; } }
	
	var field = form.username;
	if(!field.value) { error.innerHTML = errormessage("Enter the Username. "); field.focus(); return false; }
	if(field.value)	{ if(!validatesmsusername(field.value)) { error.innerHTML = errormessage('User Name is not valid (Allowed Aplhabhets, Numbers, Hyphen).'); field.focus(); return false; } }
	var field = form.fromname;
	if(!field.value) { error.innerHTML = errormessage("Enter the Fromname. "); field.focus(); return false; }
	if(field.value)	{ if(!validatesmsfromname(field.value)) { error.innerHTML = errormessage('From Name is not valid (Allowed Aplhabhets, Numbers, Hyphen).'); field.focus(); return false; } }
	var field = form.password;
	if(!field.value) { error.innerHTML = errormessage("Enter the Password. "); field.focus(); return false; }
	else
	{
		var passData = "";
		if(disablesmsaccount == 'no')
		{
			passData =  "switchtype=save&dealerid=" + document.getElementById('dealerlist').value + "&contactperson=" + encodeURIComponent(form.contactperson.value) + "&emailid=" + encodeURIComponent(form.emailid.value) + "&cell=" + encodeURIComponent(form.cell.value) + "&username=" + encodeURIComponent(form.username.value) + "&fromname=" + encodeURIComponent(form.fromname.value) +  "&password=" + encodeURIComponent(form.password.value)  + "&disablesmsaccount=" + encodeURIComponent(disablesmsaccount)+ "&croptext=" + encodeURIComponent(croptext) +  "&lastslno=" + encodeURIComponent(form.lastslno.value) +  "&smslastslno=" + encodeURIComponent(form.smslastslno.value) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		}
		else
		{
			passData =  "switchtype=disable&dealerid=" + document.getElementById('dealerlist').value + "&disablesmsaccount=" + encodeURIComponent(disablesmsaccount) +  "&lastslno=" + encodeURIComponent(form.lastslno.value)+  "&smslastslno=" + encodeURIComponent(form.smslastslno.value) +  "&smslastslno=" + encodeURIComponent(form.smslastslno.value) + "&dummy=" + Math.floor(Math.random()*100000000);	//alert(passData);
		}
		queryString = '../ajax/smsaccountdealers.php';
		var ajaxcall0 = createajax();//alert(passData);
		error.innerHTML = getprocessingimage();
		ajaxcall0.open('POST', queryString, true);
		ajaxcall0.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					
					var ajaxresponse = ajaxcall0.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');//alert(response)
						if(response[0] == '1')
						{
							error.innerHTML = successmessage(response[1]);
							generateaccountgrid('');
							newaccountentry();
						}
						else if(response[0] == '2')
						{
							error.innerHTML = errormessage(response[1]);
							//newentry();
						}
						else
						{
							error.innerHTML = errormessage('Unable to Connect...' + ajaxcall0.responseText);
						}
					}
				}
				else
					error.innerHTML = scripterror();
			}
		}
		ajaxcall0.send(passData);
	}
}

function refreshdealerarray()
{
	var form = document.filterform;
	var relyonexcecutive_type = getradiovalue(form.relyonexcecutive_type);
	var login_type = getradiovalue(form.login_type);
	passData = "switchtype=generatedealerlist&relyonexcecutive_type=" + encodeURIComponent(relyonexcecutive_type) + "&login_type=" + encodeURIComponent(login_type)  + "&dealerregion=" +encodeURIComponent(form.dealerregion.value);
	var ajaxcall2 = createajax();
	document.getElementById('dealerselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/smsaccountdealers.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var ajaxresponse = ajaxcall2.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^*^');
					dealerarray = new Array();
					for( var i=0; i<response.length; i++)
					{
						dealerarray[i] = response[i];
					}
					getdealerlist();
					document.getElementById('dealerselectionprocess').innerHTML = '';
					document.getElementById('displayfilter').style.display = 'none';
					document.getElementById('totalcountdealer').innerHTML = dealerarray.length;
				}
				
			}
			else
				document.getElementById('dealerselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}


function getdealerlist()
{	
	disableformelemnts();
	var form = $('#submitform');
	var selectbox = $('#dealerlist');
	var numberofcustomers = dealerarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = dealerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}


function newaccountentry()
{
	var form = document.submitform;
	form.reset();
	form.lastslno.value='';
	form.smslastslno.value='';
}


function selectfromlist()
{
	var selectbox = $("#dealerlist option:selected").val();
	$('#detailsearchtext').val($("#dealerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaydealername').html($("#dealerlist option:selected").text());
	$('#form-error').html('');
	enableformelemnts();
	generateaccountgrid('');
	newaccountentry();
}

function selectadealer(input)
{
	var selectbox = $('#dealerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getdealerlist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for( var i=0; i < dealerarray.length; i++)
		{
			if(input.charAt(0) == "%")
			{
				withoutspace = input.substring(1,input.length);
				pattern = new RegExp(withoutspace.toLowerCase());
				comparestringsplit = dealerarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else
			{
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = dealerarray[i];
			}
			if(pattern.test(dealerarray[i].toLowerCase()))
			{
				var splits = dealerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}


function selectacustomer(input) {
	var selectbox = $('#dealerlist');
	if (input == "") {
		getdealerlist();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < dealerarray.length; i++) {
			// Check if any part of the name contains the input string
			if (dealerarray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = dealerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if (addedcount == 100) break;
			}
		}
	}
}


function dealersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrolldealer('up');
	else if(KeyID == 40)
		scrolldealer('down');
	else
	{
		var form = document.submitform;
		var input = document.getElementById('detailsearchtext').value;
		selectadealer(input);
	}
}
function scrolldealer(type)
{	
	var selectbox = document.getElementById('dealerlist');
	var totalcus = selectbox.options.length;
	var selectedcus = selectbox.selectedIndex;
	if(type == 'up' && selectedcus != 0)
		selectbox.selectedIndex = selectedcus - 1;
	else if(type == 'down' && selectedcus != totalcus)
		selectbox.selectedIndex = selectedcus + 1;
	selectfromlist();
}



function disableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
	}
}

function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
}

function gridtoform(slno)
{
	if(slno != '')
	{
		var form = document.submitform;
		var error = document.getElementById('form-error');
		var passData = "switchtype=gridtoform&smslastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		document.getElementById('form-error').innerHTML = getprocessingimage();
		ajaxcall5 = createajax();
		var queryString = "../ajax/smsaccountdealers.php";
		ajaxcall5.open("POST", queryString, true);
		ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall5.onreadystatechange = function()
		{
			if(ajaxcall5.readyState == 4)
			{
				if(ajaxcall5.status == 200)
				{
					document.getElementById('form-error').innerHTML = '';
					var ajaxresponse = ajaxcall5.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');		
						if(response[0] == '1')
						{
							form.smslastslno.value = response[1];
							document.getElementById('contactperson').value = response[2];
							document.getElementById('emailid').value = response[3];
							document.getElementById('cell').value = response[4];
							document.getElementById('fromname').value = response[5];
							document.getElementById('username').value = response[6];
							document.getElementById('password').value = response[7];
							autocheck(form.disablesmsaccount,response[8]);
							autocheck(form.croptext,response[9]);
						}
						else
						{
							error.innerHTML = errormessage('Cannot Connect'); 
						}
					}
				}
			}
			else
			{
				error.innerHTML = scripterror();
			}
		}
		ajaxcall5.send(passData);
	}
}


function generateaccountgrid(startlimit)
{
	var form = document.submitform;
	var startlimit = '';
	document.getElementById('cuslastslno').value = document.getElementById('dealerlist').value;
	var passData = "switchtype=generateaccountgrid&startlimit="+ encodeURIComponent(startlimit) + "&dealerid=" + encodeURIComponent(document.getElementById('cuslastslno').value);
	var queryString = "../ajax/smsaccountdealers.php";
	ajaxcall3 = createajax();
	document.getElementById('tabgroupgridc1_1').innerHTML = getprocessingimage();
	document.getElementById('tabgroupgridc1link').innerHTML ='';
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			if(ajaxcall3.status == 200)
			{
				var ajaxresponse = ajaxcall3.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
						document.getElementById('tabgroupgridc1_1').innerHTML =  response[1];
						document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
					}
					else
					{
						document.getElementById('tabgroupgridc1_1').innerHTML = "No datas found to be displayed.";
					}
				}
				
			}
			else
				document.getElementById('tabgroupgridc1_1').innerHTML =scripterror();
		}
	}
	ajaxcall3.send(passData);
}

//Function for "show more records" or  "show all records" link  - to get registration records
function getmoregenerateaccountgrid(startlimit,slnocount,showtype)
{
	var form = document.submitform;
//	document.getElementById('lastslno').value = id;	
	var passData = "switchtype=generateaccountgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dealerid=" + encodeURIComponent(document.getElementById('cuslastslno').value)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/smsaccountdealers.php";
	ajaxcall14 = createajax();
	document.getElementById('tabgroupgridc1link').innerHTML = getprocessingimage();
	ajaxcall14.open("POST", queryString, true);
	ajaxcall14.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall14.onreadystatechange = function()
	{
		if(ajaxcall14.readyState == 4)
		{
			if(ajaxcall14.status == 200)
			{
				var ajaxresponse = ajaxcall14.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');//alert(response);
					if(response[0] == '1')
					{
						document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
						document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
						document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ response[1] ;
						document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
					}
					else
					{
						document.getElementById('tabgroupgridc1_1').innerHTML = "No datas found to be displayed.";
					}
				}
			}
			else
				document.getElementById('tabgroupgridc1_1').innerHTML = scripterror();
		}
		else
		{
			document.getElementById('tabgroupgridc1_1').innerHTML = scripterror();
		}
	}
	ajaxcall14.send(passData);
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchcustomerid').value;
		searchbycustomerid(input);
	}
}

function searchbycustomerid(dealerid)
{
	document.getElementById('form-error').innerHTML = '';
	var form = document.submitform;
	form.reset();
	var passData = "switchtype=searchbydealerid&dealerid=" + encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
	ajaxcall7 = createajax();
	var queryString = "../ajax/smsaccountdealers.php";
	ajaxcall7.open("POST", queryString, true);
	ajaxcall7.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall7.onreadystatechange = function()
	{
		if(ajaxcall7.readyState == 4)
		{
			if(ajaxcall7.status == 200)
			{
				var ajaxresponse = ajaxcall7.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = (ajaxresponse).split("^");
					if(response[0] == '1')
					{
						document.getElementById('detailsearchtext').value = response[1];
						selectadealer(response[1]);            
						document.getElementById('dealerlist').value = response[2];
						generateaccountgrid('');
						enableformelemnts();
						//document.getElementById('displaycustomername').innerHTML = document.getElementById('detailsearchtext')
					}
					else
					{
						alert('Dealer Not Available');
					}
				}
			}
			else
				document.getElementById('form-error').innerHTML = scripterror();
		}
	}
	ajaxcall7.send(passData);
}
