
var customerarray = new Array();

function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall2 = createajax();
	document.getElementById('customerselectionprocess').innerHTML = getprocessingimage();
	queryString = "../ajax/customerquotereq.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split('^*^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					customerarray = new Array();
					for( var i=0; i<response.length; i++)
					{
						customerarray[i] = response[i];
					}
					getcustomerlist1();
					document.getElementById('customerselectionprocess').innerHTML = '';
					document.getElementById('totalcount').innerHTML = customerarray.length;
				}
			}
			else
				document.getElementById('customerselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}

function getcustomerlist1()
{	
	disableformelemnts();
	var form = $('#filterform');
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}

function displaycustomername()
{
	var passData = "switchtype=displaycustomer&customerreference=" + encodeURIComponent(document.getElementById('customerlist').value) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall2 = createajax();
	queryString = "../ajax/customerquotereq.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText;//alert(response[1])
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response1 = response.split('^');
					if(response1[0] == '1')
					{
						document.getElementById('displaycustomername').innerHTML = response1[1];
						document.getElementById('lastslno').value = response[2];
					}
					else
					{
						document.getElementById('displaycustomername').innerHTML = errormessage('No datas found to be displayed.');
					}
				}
			}
			else
				document.getElementById('displaycustomername').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}
function selectfromlist()
{
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	//customerdetailstoform(selectbox.value);
	displaycustomername();
	//newentry();
	document.getElementById('form-error').innerHTML = '';
	//cusinteractiondatagrid('');
	enableformelemnts();
}

function selectacustomer(input)
{
	var selectbox = document.getElementById('customerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getcustomerlist1();
	}
		else
	{
		selectbox.options.length = 0;
		var addedcount = 0;
		for( var i=0; i < customerarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = customerarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = customerarray[i];
				}
			if(pattern.test(trimdotspaces(customerarray[i]).toLowerCase()))
			{
				var splits = customerarray[i].split("^");
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}

function selectacustomer(input) {
	var selectbox = $('#customerlist');
	if (input == "") {
		getcustomerlist1();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < customerarray.length; i++) {
			// Check if any part of the name contains the input string
			if (customerarray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = customerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if (addedcount == 100) break;
			}
		}
	}
}

function customersearch(e)
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
	var selectbox = document.getElementById('customerlist');
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

