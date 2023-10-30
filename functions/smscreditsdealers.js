
var dealerarray = new Array();

function formsubmit(command)
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.smsaccount;
	if(!field.value) { error.innerHTML = errormessage("Select an Account."); field.focus(); return false; }
	var field = form.remarks;
	var field = form.privatenote;
	var field = form.sendinvoice;
	var sendinvoice;
	if(field.checked == true) sendinvoice = 'yes'; else  sendinvoice = 'no';
	var passData = "";
	if(form.invoiceno.value == 'New')
	{
		var field = form.quantity;
		if(!field.value) { error.innerHTML = errormessage("Enter the Quantity."); field.focus(); return false; }
		if(field.value)	{ if(!validateamount(field.value)) { error.innerHTML = errormessage('Quantity is not Valid.'); field.focus(); return false; } }
		if(field.value > 100000)
		{error.innerHTML = errormessage('Quantity should be between 1 and 100000.'); field.focus(); return false;	}
		var confirmation = confirm("The Invoice quantity once saved cannot be edited. Respective credits will be addded to the SMS Account. Do you really wish to continue?");
		if(confirmation)
		{
			passData =  "switchtype=save&dealerid=" + document.getElementById('dealerlist').value + "&quantity=" + encodeURIComponent(form.quantity.value) + "&privatenote=" + encodeURIComponent(form.privatenote.value) +  "&remarks=" + encodeURIComponent(form.remarks.value) +  "&smsuserid=" + encodeURIComponent(form.smsaccount.value)+  "&totalamount=" + encodeURIComponent(form.billamount.value)+  "&servicetax=" + encodeURIComponent(form.servicetax.value) +  "&sendinvoice=" + encodeURIComponent(sendinvoice) +  "&netamount=" + encodeURIComponent(form.netamount.value) +  "&lastslno=" + encodeURIComponent(form.lastslno.value) + "&dummy=" + Math.floor(Math.random()*100000000);
		}
		else
			return false;
	}
	else
	{
		passData =  "switchtype=save&dealerid=" + document.getElementById('dealerlist').value + "&privatenote=" + encodeURIComponent(form.privatenote.value) +  "&remarks=" + encodeURIComponent(form.remarks.value) +  "&smsuserid=" + encodeURIComponent(form.smsaccount.value)+  "&totalamount=" + encodeURIComponent(form.billamount.value)+  "&servicetax=" + encodeURIComponent(form.servicetax.value) +  "&sendinvoice=" + encodeURIComponent(sendinvoice) +  "&netamount=" + encodeURIComponent(form.netamount.value) +  "&lastslno=" + encodeURIComponent(form.lastslno.value) + "&dummy=" + Math.floor(Math.random()*100000000);
	}
	queryString = '../ajax/smscreditsdealers.php';
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
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						error.innerHTML = successmessage(response[1]);
						generatesmsgrid('');
						newcreditentry();
						gettotalcredit();
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

function refreshdealerarray()
{
	var form = document.filterform;
	passData = "switchtype=generatedealerlist&dummy=" +  Math.floor(Math.random()*100000000);
	var ajaxcall1 = createajax();
	document.getElementById('dealerselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/smscreditsdealers.php";
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText.split('^*^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					dealerarray = new Array();
					for( var i=0; i<response.length; i++)
					{
						dealerarray[i] = response[i];
					}
					getdealerlist();//alert(dealerarray.length)
					document.getElementById('dealerselectionprocess').innerHTML = '';
					document.getElementById('totalcountdealer').innerHTML = dealerarray.length;
				}
				
			}
			else
				document.getElementById('dealerselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall1.send(passData);
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


function newcreditentry()
{
	var form = document.submitform;
	form.reset();
	form.lastslno.value='';
	document.getElementById('displayenteredby').innerHTML = 'Not Available';
	document.getElementById('creditsavailableforaccount').innerHTML = 'Not Available';
	document.getElementById('smsaccount').disabled = false;
	document.getElementById('billamount').readOnly  = false;
	document.getElementById('sendinvoice').disabled = false;
	document.getElementById('displayquantity').innerHTML = '<input name="quantity" type="text" class="swifttext-mandatory" id="quantity" size="30" maxlength="8" autocomplete="off"  onkeyup="gettotalamount();"/>';
}


function selectfromlist()
{
	var selectbox = document.getElementById('dealerlist');
	var dealersearch = document.getElementById('detailsearchtext');
	dealersearch.value = selectbox.options[selectbox.selectedIndex].text;
	document.getElementById('displaydealername').innerHTML = dealersearch.value;
	dealersearch.select();
	enableformelemnts();
	generatesmsgrid('');
	gettotalcredit();
	getuseraccountlist();
	newcreditentry();
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


//function to get credit details to form
function smsgridtoform(slno)
{
	if(slno != '')
	{
		document.getElementById('billamount').readOnly  = true;
		document.getElementById('sendinvoice').disabled  = true;	
		var form = document.submitform;
		var error = document.getElementById('form-error');
		error.innerHTML = '';
		var passData = "switchtype=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		ajaxcall3 = createajax();
		error.innerHTML = getprocessingimage();
		var queryString = "../ajax/smscreditsdealers.php";
		ajaxcall3.open("POST", queryString, true);
		ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall3.onreadystatechange = function()
		{
			if(ajaxcall3.readyState == 4)
			{
				if(ajaxcall3.status == 200)
				{
					error.innerHTML = '';
					var ajaxresponse = (ajaxcall3.responseText);
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');
						if(response[0] == 1)
						{
							form.lastslno.value = response[1];
							document.getElementById('displayquantity').innerHTML = response[2];
							form.invoiceno.value = response[3];
							form.billamount.value = response[4];
							document.getElementById('crediteddate').innerHTML = response[5];
							form.remarks.value= response[6]
							document.getElementById('displayenteredby').innerHTML = response[7];
							document.getElementById('smsaccount').value = response[8];
							document.getElementById('smsaccount').disabled = true;
							form.privatenote.value= response[9];
							document.getElementById('servicetax').value = response[11];
							document.getElementById('netamount').value = response[12];
						}
						else
							error.innerHTML =scripterror();	
					}
				}
				else
					error.innerHTML =scripterror();
			}
		}
		ajaxcall3.send(passData);
	}
	else
	{
		//document.getElementById('quantity').readOnly  = false;
		document.getElementById('billamount').readOnly  = false;
	}
}


function generatesmsgrid(startlimit)
{
	if(document.getElementById('dealerlist').value)
	{

		var form = document.submitform;
		var startlimit = '';
		var passData = "switchtype=generatesmsgrid&startlimit="+ encodeURIComponent(startlimit) + "&dealerid=" + document.getElementById('dealerlist').value;//alert(passData);
		var queryString = "../ajax/smscreditsdealers.php";
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
						if(response[0] == 1)
						{
							document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
							document.getElementById('tabgroupgridc1_1').innerHTML =  response[1];
							document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
						}
						else
							document.getElementById('tabgroupgridc1_1').innerHTML =scripterror();
					}
					
				}
				else
					document.getElementById('tabgroupgridc1_1').innerHTML =scripterror();
			}
		}
		ajaxcall3.send(passData);
	}
}

function getmoregenerateschemegrid(startlimit,slnocount,showtype)
{
	if(document.getElementById('dealerlist').value)
	{
		var form = document.submitform;
		var passData = "switchtype=generatesmsgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dealerid=" + document.getElementById('dealerlist').value + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/smscreditsdealers.php";
		ajaxcall4 = createajax();
		document.getElementById('tabgroupgridc1link').innerHTML = getprocessingimage();
		ajaxcall4.open("POST", queryString, true);
		ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall4.onreadystatechange = function()
		{
			if(ajaxcall4.readyState == 4)
			{
				if(ajaxcall4.status == 200)
				{
					var ajaxresponse = ajaxcall4.responseText;
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');//alert(response);
						if(response[0] == 1)
						{
							document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[2];
							document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
							document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ response[1] ;
							document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
						}
						else
							document.getElementById('tabgroupgridc1_1').innerHTML = scripterror();
					}
				}
				else
					document.getElementById('tabgroupgridc1_1').innerHTML = scripterror();
			}
		}
		ajaxcall4.send(passData);
	}
}



function gettotalcredit()
{
	var form = document.submitform;
	var passData = "switchtype=gettotalcredits&dealerid=" + encodeURIComponent(document.getElementById('dealerlist').value) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall5 = createajax();
	//document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscreditsdealers.php";
	ajaxcall5.open("POST", queryString, true);
	ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall5.onreadystatechange = function()
	{
		if(ajaxcall5.readyState == 4)
		{
			if(ajaxcall5.status == 200)
			{
				var ajaxresponse = ajaxcall5.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					if(response[0] == 1)
					{
						document.getElementById('creditamountdisplay').innerHTML = response[1];
					}
					else
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall5.send(passData);
}

//function to get user account list
function getuseraccountlist()
{
	var form = document.submitform;
	var passData = "switchtype=getuseraccountlist&dealerid=" + encodeURIComponent(document.getElementById('dealerlist').value) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall7 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscreditsdealers.php";
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
					document.getElementById('form-error').innerHTML = '';	
					var response = ajaxresponse.split('^');
					if(response[0] == 1)
					{
						document.getElementById('smsaccountlist').innerHTML = response[1];
					}
					else
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall7.send(passData);
}

function dealersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcustomer('up');
	else if(KeyID == 40)
		scrollcustomer('down');
	else
	{
		var form = document.submitform;
		var input = document.getElementById('detailsearchtext').value;
		selectacustomer(input);
	}
}

function scrollcustomer(type)
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

//function to calucate the amount depending on the quantity entered
function gettotalamount()
{

	var form = document.submitform;
	var error =document.getElementById('form-error');
	var field = form.quantity;
	if(field.value)	{ if(!validateamount(field.value)) { error.innerHTML = errormessage('Quantity is not Valid.'); field.focus(); return false; } }
	if(field.value > 100000)
	{error.innerHTML = errormessage('Quantity should be between 1 and 100000.'); field.focus(); return false;	}
	var passData = "switchtype=gettotalamount&quantity=" + encodeURIComponent(form.quantity.value);
	var ajaxcall9 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscreditsdealers.php";
	ajaxcall9.open("POST", queryString, true);
	ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall9.onreadystatechange = function()
	{
		if(ajaxcall9.readyState == 4)
		{
			if(ajaxcall9.status == 200)
			{
				var ajaxresponse = ajaxcall9.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					document.getElementById('form-error').innerHTML = '';	
					if(response[0] == 1)
					{
						document.getElementById('billamount').value = response[1];
						document.getElementById('servicetax').value = response[2];
						document.getElementById('netamount').value = response[3];
					}
					else
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall9.send(passData);

}


//Function to edit total amount
function editamount()
{
	var form = document.submitform;
	var billamount = form.billamount;
	var passData = "switchtype=gettotalamount&billamount=" + encodeURIComponent(form.billamount.value) + "&dummy=" + Math.floor(Math.random()*10054300000);
	if(billamount.value == 0)
		document.getElementById('sendinvoice').disabled = true;
	var ajaxcall11 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscreditsdealers.php";
	ajaxcall11.open("POST", queryString, true);
	ajaxcall11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall11.onreadystatechange = function()
	{
		if(ajaxcall11.readyState == 4)
		{
			if(ajaxcall11.status == 200)
			{
				var ajaxresponse = ajaxcall11.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					document.getElementById('form-error').innerHTML = '';	
					if(response[0] == 1)
					{
						document.getElementById('servicetax').value = response[2];
						document.getElementById('netamount').value = response[3];
					}
					else
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall11.send(passData);
}

//function to send/resend invoice through mail
function sendorresendinvoice()
{
	var form = document.submitform;
	var invoiceno = document.getElementById('lastslno').value;
	var confirmation = confirm('Are you sure you want to resend the Invoice?');
	if(confirmation)
	{
		var passData = "switchtype=sendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		var ajaxcall10 = createajax();
		document.getElementById('form-error').innerHTML = getprocessingimage();	
		queryString = "../ajax/smscreditsdealers.php";
		ajaxcall10.open("POST", queryString, true);
		ajaxcall10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall10.onreadystatechange = function()
		{
			if(ajaxcall10.readyState == 4)
			{
				if(ajaxcall10.status == 200)
				{
					var ajaxresponse = ajaxcall10.responseText;//alert(ajaxresponse)
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split('^');
						if(response[0] == 1)
						{
							document.getElementById('form-error').innerHTML = successmessage('Invoice sent successfully to the selected Customer');
						}
						else
							document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
					}
				}
				else
					document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
			}
		}
		ajaxcall10.send(passData);
	}
}

//function to calculate total credits for selected SMS Account
function gettotalcreditforthataccount()
{
	var form = document.submitform;
	var error =document.getElementById('form-error');
	var field = form.smsaccount;
	var passData = "switchtype=gettotalcreditforthataccount&smsuserid=" + encodeURIComponent(form.smsaccount.value);
	var ajaxcall12 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscredits.php";
	ajaxcall12.open("POST", queryString, true);
	ajaxcall12.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall12.onreadystatechange = function()
	{
		if(ajaxcall12.readyState == 4)
		{
			if(ajaxcall12.status == 200)
			{
				var ajaxresponse = ajaxcall12.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = ajaxresponse.split('^');
					document.getElementById('form-error').innerHTML = '';	
					if(response[0] == 1)
					{
						document.getElementById('creditsavailableforaccount').innerHTML = response[1] + ' SMSes';
					}
					else
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall12.send(passData);

}