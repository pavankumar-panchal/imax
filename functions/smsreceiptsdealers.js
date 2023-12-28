var dealerarray = new Array();


function formsubmit()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.invoivcelist;
	if(!field.value) { error.innerHTML = errormessage("Select an Invoivce No."); field.focus(); return false; }
	var field = form.receiptamount;
	if(!field.value) { error.innerHTML = errormessage("Enter the Amount."); field.focus(); return false; }
	if(field.value)	{ if(!validateamount(field.value)) { error.innerHTML = errormessage('Amount is not Valid.'); field.focus(); return false; } }
	var paymentmode = getradiovalue(form.paymentmode);
	var field = form.remarks;
	var field = form.privatenote;
	if(form.lastslno.value == '')
	{
		if(form.receiptamount.value > form.totalamount.value)
		{
			error.innerHTML = errormessage('Receipt Amount is greater than invoice amount.'); return false; 
		}
	}
	var passData = "";
	passData =  "switchtype=save&dealerid=" + document.getElementById('dealerlist').value + "&invoivcelist=" + encodeURIComponent(form.invoivcelist.value) + "&privatenote=" + encodeURIComponent(form.privatenote.value) +  "&remarks=" + encodeURIComponent(form.remarks.value) +  "&paymentmode=" + encodeURIComponent(paymentmode) +  "&receiptamount=" + encodeURIComponent(form.receiptamount.value) +  "&lastslno=" + encodeURIComponent(form.lastslno.value) + "&dummy=" + Math.floor(Math.random()*100000000);
	queryString = '../ajax/smsreceiptsdealers.php';
	var ajaxcall0 = createajax();
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
						generatereceiptgrid('');
						newcreditentry();

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
	queryString = "../ajax/smsreceiptsdealers.php";
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
					getdealerlist();
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
	document.getElementById('invoiceamount').innerHTML = 'Not Available';
}

function selectfromlist()
{
	var selectbox = document.getElementById('dealerlist');
	var dealersearch = document.getElementById('detailsearchtext');
	dealersearch.value = selectbox.options[selectbox.selectedIndex].text;
	document.getElementById('displaydealername').innerHTML = dealersearch.value;
	dealersearch.select();
	enableformelemnts();
	generatereceiptgrid('')
	getuserinvoicelist();
	newcreditentry();
}

function selectadealer(input)
{
	var selectbox = document.getElementById('dealerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getdealerlist();
	}
	else
	{
		selectbox.options.length = 0;
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
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
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



function getuserinvoicelist()
{
	var form = document.submitform;
	var passData = "switchtype=getuserinvoicelist&dealerid=" + encodeURIComponent(document.getElementById('dealerlist').value) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall7 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smsreceiptsdealers.php";
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
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						document.getElementById('form-error').innerHTML = '';	
						document.getElementById('smsaccountlist').innerHTML = response[1];
					}
					else
					{
						error.innerHTML = errormessage('Unable to Connect...');
					}
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall7.send(passData);
}

function getinovoiceamount()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.invoivcelist;
	if(!field.value) { error.innerHTML = errormessage("Select an Invoivce No."); field.focus(); return false; }
	var passData = "switchtype=getinovoiceamount&invoiceno=" + encodeURIComponent(document.getElementById('invoivcelist').value);
	var ajaxcall8 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smsreceiptsdealers.php";
	ajaxcall8.open("POST", queryString, true);
	ajaxcall8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall8.onreadystatechange = function()
	{
		if(ajaxcall8.readyState == 4)
		{
			if(ajaxcall8.status == 200)
			{
				var ajaxresponse = ajaxcall8.responseText;
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
						document.getElementById('form-error').innerHTML = '';	
						document.getElementById('invoiceamount').innerHTML = response[1];
					}
					else
					{
						document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
					}
				}
			}
			else
				document.getElementById('form-error').innerHTML =errormessage('Connection Failed.');
		}
	}
	ajaxcall8.send(passData);
}

function gettotalamount()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.invoivcelist;
	if(!field.value) { error.innerHTML = errormessage("Select an Invoivce No."); field.focus(); return false; }
	var invoiceamount = document.getElementById('invoiceamount').innerHTML;
	var receiptamount = form.receiptamount;
	if(receiptamount.value)	{ if(!validateamount(receiptamount.value)) { error.innerHTML = errormessage('Amount is not Valid.'); receiptamount.focus(); return false; } }
	var totalamount = invoiceamount - receiptamount.value;
	if(totalamount < 0){ error.innerHTML = errormessage('Receipt Amount is greater than invoice amount.'); return false; }
		
	document.getElementById('totalamount').value = totalamount;
}

//Function to generate Receipt Grid
function generatereceiptgrid(startlimit)
{
	if(document.getElementById('dealerlist').value)
	{
		var form = document.submitform;
		var startlimit = '';
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&dealerid=" + document.getElementById('dealerlist').value;//alert(passData);
		var queryString = "../ajax/smsreceiptsdealers.php";
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
					var ajaxresponse = ajaxcall4.responseText;
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
							document.getElementById('tabgroupgridc1_1').innerHTML = "No records found to be displayed.";
						}
					}
					
				}
				else
					document.getElementById('tabgroupgridc1_1').innerHTML =scripterror();
			}
		}
		ajaxcall4.send(passData);
	}
}

//Function to "show all" or "Show More" Records
function getmoregeneratereceiptgrid(startlimit,slnocount,showtype)
{
	if(document.getElementById('dealerlist').value)
	{
		var form = document.submitform;
	//	document.getElementById('lastslno').value = id;	
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dealerid=" + document.getElementById('dealerlist').value + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/smsreceiptsdealers.php";
		ajaxcall5 = createajax();
		document.getElementById('tabgroupgridc1link').innerHTML = getprocessingimage();
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
						var response = ajaxresponse.split('^');//alert(response);
						if(response[0] == '1')
						{
							document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[1];
							document.getElementById('resultgrid').innerHTML =  document.getElementById('tabgroupgridc1_1').innerHTML;
							document.getElementById('tabgroupgridc1_1').innerHTML =   document.getElementById('resultgrid').innerHTML.replace(/\<\/table\>/gi,'')+ response[1] ;
							document.getElementById('tabgroupgridc1link').innerHTML =  response[3];
						}
						else
						{
							document.getElementById('tabgroupgridc1_1').innerHTML = "No records found to be displayed.";
						}
					}
				}
				else
					document.getElementById('tabgroupgridc1_1').innerHTML = scripterror();
			}
		}
		ajaxcall5.send(passData);
	}
}

function receiptgridtoform(slno)
{
	if(slno != '')
	{
		var form = document.submitform;
		var error = document.getElementById('form-error');
		error.innerHTML = '';
		var passData = "switchtype=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		ajaxcall3 = createajax();
		error.innerHTML = getprocessingimage();
		var queryString = "../ajax/smsreceiptsdealers.php";
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
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = ajaxresponse.split("^");
						if(response[0] == '1')
						{
							form.lastslno.value = response[1];
							form.invoivcelist.value = response[2];
							form.receiptamount.value = response[3];
							document.getElementById('invoiceamount').innerHTML = response[4];
							form.remarks.value= response[5];
							form.privatenote.value= response[6];
							document.getElementById(response[7]).checked = true;
						}
						else
						{
							error.innerHTML = errormessage('No datas found to be displayed.');
						}
					}
				}
				else
					error.innerHTML =scripterror();
			}
		}
		ajaxcall3.send(passData);
	}
	else
		document.getElementById('quantity').readOnly  = false;
}
