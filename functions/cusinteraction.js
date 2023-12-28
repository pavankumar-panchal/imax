var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;var process2;var process3;var process4;

var isInProgress = false;
//var t=setTimeout("refreshcustomerarray();",30000);
function formsubmit(command)
{
	var form = $('#submitform');
	var  cusslno = $('#cusinteractionslno').val();
	var error = $('#form-error');
	var field = $('#remarks');
	if(!field.val()) { error.html(errormessage("Enter the Remarks. ")); field.focus(); return false; }
	else
	{
		var passData = "";
		if(command == 'save')
		{
			passData =  "switchtype=save&customerreference=" +  encodeURIComponent($('#cusinteractionslno').val()) + "&remarks=" + encodeURIComponent($('#remarks').val()) + "&interactiontype=" + encodeURIComponent($('#interaction').val()) +  "&cusinteractionslno=" + encodeURIComponent($('#cusinteractionslno').val())  +  "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
			
		}
		else
		{
			//alert(form.lastslno.value);
			passData =  "switchtype=delete&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			//alert(passData);
		}
		queryString = '../ajax/cusinteraction.php';
		ajaxcall1 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
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
						error.html(successmessage(response[1]));
						cusinteractiondatagrid('',cusslno);
						newentry();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						cusinteractiondatagrid('',cusslno);
						newentry();
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
	}
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/cusinteraction.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$("#totalcount").html(response['count']);
				refreshcustomerarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}



function refreshcustomerarray(customercount)
{
	var form = $('#customerselectionprocess');
	var totalcustomercount = customercount;
	var limit = Math.round(totalcustomercount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusinteraction.php";
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				for( var i=0; i<response.length; i++)
				{
					customerarray1[i] = response[i];
				}
				process1 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/cusinteraction.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray2[i] = response[i];
				}
				process2 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/cusinteraction.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray3[i] = response[i];
				}
				process3 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/cusinteraction.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray4[i] = response[i];
				}
				process4 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

}

function compilecustomerarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		customerarray = customerarray1.concat(customerarray2.concat(customerarray3.concat(customerarray4)));
		flag = true;
		$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
		getcustomerlist1();
		
	}
	else
	return false;
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
function newentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#enteredthrough').html('Not Available');
	$('#displayenteredby').html('');
	enablesave();
	
}

function gridtoform(slno)
{
	if(slno != '')
	{//alert(slno);
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		$('#lastslno').val(slno);
		var passData = "switchtype=gridtoform&cusinteractionslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#productselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/cusinteraction.php";
		ajaxcall3 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$('#productselectionprocess').html('');
					var response = ajaxresponse;//alert(ajaxresponse)
					if(response['errorcode'] == '1' )
					{
						$('#displaycustomername').html(response['businessname']);
						$('#interactiondate').val(response['interactiondate']);
						$('#displayenteredby').html(response['enteredby']);
						$('#remarks').val(response['remarks']);
						$('#cusinteractionslno').val(response['slno']);
						$('#enteredthrough').html(response['modulename']);
						$('#interaction').val(response['interactiontype']);//alert(response[4])
						$('#lastslno').val(slno);
						enablesave();
					}
					else if(response['errorcode'] == '2')
					{
						$('#displaycustomername').html(response['businessname']);
						$('#interactiondate').val(response['interactiondate']);
						$('#displayenteredby').html(response['enteredby']);
						$('#remarks').val(response['remarks']);
						$('#cusinteractionslno').val(response['slno']);
						$('#enteredthrough').html(response['modulename']);
						$('#interaction').val(response['interactiontype']);//alert(response[4])
						$('#lastslno').val(slno);
						enablesave();
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
	}
}

function cusinteractiondatagrid(startlimit,customerslno)
{
	var passData = "switchtype=generategrid&customerreference=" + encodeURIComponent(customerslno) + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/cusinteraction.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == 1)
				{
					//document.getElementById('productselectionprocess').innerHTML='';
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					
				}
				else if(response[0] == 2)
				{
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridwb1').html('');
					$('#tabgroupgridc1_1').html(response[1]);	
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});		
}

//function to display 'Show more records' or 'Show all records' 
function cusmoredatagrid(startlimit,slno,showtype)
{
	var passData = "switchtype=generategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&customerreference=" + encodeURIComponent($('#cusinteractionslno').val()) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$('#tabgroupgridc1link').html('<img src="../images/inventory-processing.gif" border= "0">');
	//alert(passData);
	queryString = "../ajax/cusinteraction.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split('^');
				$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
				$('#custresultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
				$('#tabgroupgridc1link').html(response[3]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});		
}


function displaycustomername()
{
	var passData = "switchtype=displaycustomer&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000)//;alert(passData)
	queryString = "../ajax/cusinteraction.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse.split("^");
				$('#displaycustomername').html(response[1]);
				$('#cusinteractionslno').val(response[2]);
			}
		}, 
		error: function(a,b)
		{
			$("#displaycustomername").html(scripterror());
		}
	});	
}

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	
	newentry();
	$('#form-error').html('');
	//customerdetailstoform(selectbox.value);
	displaycustomername();
	cusinteractiondatagrid('',selectbox);
	enableformelemnts();
	enabledelete();
}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getcustomerlist1();
	}
		else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		
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
				var result1 = pattern.test(trimdotspaces(customerarray[i]).toLowerCase());
				var result2 = pattern.test(customerarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = customerarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
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
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectacustomer(input);
	}
}

function scrollcustomer(type)
{
	var selectbox = $('#customerlist');
	var totalcus = $("#customerlist option").length;
	var selectedcus = $("select#customerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#customerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#customerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist()
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


function searchbycustomerid(cusid)
{
	$('#form-error').html('');
	var form = $('#submitform');
	$('#cusinteractionslno').val(cusid);
	$('#submitform')[0].reset();
	var str = cusid.length;
	if(str == '20')
	{
		var cusid = cusid.substring(15);
		
	}else if(str == '17')
	{
		var cusid = cusid.substring(12,17);
	}else if(str == '5')
	{
		var cusid = cusid;
	}
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	var queryString = "../ajax/cusinteraction.php";
	ajaxcall6 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = (ajaxresponse);
				enableformelemnts();
				if(response['customerid'] == '')
				{
					alert('Customer Not Available.');
					$('#displaycustomername').html('');
					$('#tabgroupgridc1_1').html('');
					$('#tabgroupgridwb1').html('');
					$('#tabgroupgridc1link').html('');
					return false;
					//newentry();
				}
				if(response['errorcode'] == '1' )
				{
					$('#displaycustomername').html(response['businessname']);
					$('#interactiondate').html(response['interactiondate']);
					$('#displayenteredby').html(response['enteredby']);
					$('#remarks').val(response['remarks']);
					$('#lastslno').val(response['slno']);
					$('#enteredthrough').html(response['modulename']);
					$('#interaction').val(response['interactiontype']);
					$('#cusinteractionslno').val(cusid);
					enablesave();
				}
				else if(response['errorcode'] == '2')
				{
					$('#displaycustomername').html(response['businessname']);
					$('#interactiondate').html(response['interactiondate']);
					$('#displayenteredby').html(response['enteredby']);
					$('#remarks').val(response['remarks']);
					$('#lastslno').val(response['slno']);
					$('#enteredthrough').html(response['modulename']);
					$('#interaction').val(response['interactiontype']);//alert(response[4])
					$('#cusinteractionslno').val(cusid);
					enablesave();
				}else if(response['errorcode'] == '3')
				{
					$('#displaycustomername').html(response['businessname']);
				}
				cusinteractiondatagrid('',cusid);
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}