var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;
var process2;
var process3;
var process4;
//var t=setTimeout("refreshcustomerarray();",30000);

function formsubmit(command)
{
	
	var form = $('#submitform');
	var error = $('#form-error');//alert(form.cuslastslno.value);
	var cuslastslno =$('#cuslastslno').val();
	if(cuslastslno == '')
	{
		var field = $('#customerlist');
		if(!field.val()) { error.html(errormessage("Select the Customer from the list. ")); field.focus(); return false; }
	}
	else
	{
		var field = $('#billno');
		var field = $('#billamount');
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
		var field = $('#productcode');
		if(!field.val()) { error.html(errormessage("Select the Product. ")); field.focus(); return false; }
		var field = $('#DPC_startdate');
		if(!field.val()) { error.html(errormessage("Enter the Start Date. ")); field.focus(); return false; }
		var field = $('#DPC_enddate');
		if(!field.val()) { error.html(errormessage("Enter the End Date. ")); field.focus(); return false; }
		var field = $('#billdate');
		if(!field.val()) { error.html(errormessage("Enter the Bill Date. ")); field.focus(); return false; }
	
		else
		{
			var passData = "";
			if(command == 'save')
			{
				passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#cuslastslno').val()) + "&productcode=" + encodeURIComponent($('#productcode').val()) + "&startdate=" + encodeURIComponent($('#DPC_startdate').val()) + "&enddate=" + encodeURIComponent($('#DPC_enddate').val()) + "&remarks=" + encodeURIComponent($('#remarks').val()) + "&billno=" + encodeURIComponent($('#billno').val()) + "&billamount=" + encodeURIComponent($('#billamount').val()) +  "&billdate=" + encodeURIComponent($('#billdate').val()) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
				
			}
			else
			{
				passData =  "switchtype=delete&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			}
			queryString = '../ajax/customeramc.php';
			error.html(getprocessingimage());
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
						var response = ajaxresponse['errormessage'].split('^');
						if(response[0] == '1')
						{
							error.html(successmessage(response[1]));
							generatedatagrid('');
							newentry();
						}
						else if(response[0] == '2')
						{
							error.html(successmessage(response[1]));
							generatedatagrid('');
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
}


function onfocusvalue()
{
	var form = $('#submitform');
	if($('#DPC_startdate').val() == '' && $('#DPC_enddate').val() == '') 
	{
		var Field = $('#billdate').val();
		$('#DPC_startdate').val(Field);
		var dateString = Field.substr(6);
		var theForm = (dateString * 1) + 1 ;
		$('#DPC_enddate').val(Field.substr(0,6)+ theForm);
	}
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/customeramc.php";
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
	queryString = "../ajax/customeramc.php";
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
	
	queryString = "../ajax/customeramc.php";
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

	queryString = "../ajax/customeramc.php";
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
	
	queryString = "../ajax/customeramc.php";
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
	var form = $('#submitform');
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
	$('#contractid').html('');
	$('#lastslno').val('');
	$('#createddate').html('Not Available');
	$('#useriddisplay').html('');
	$('#amcstatus').html('Not Available');
	$('#billno').val('');
	$('#billamount').val('');
	$('#remarks').val('');
	
}


function newamcentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#contractid').html('');
	$('#lastslno').val('');
	$('#tabgroupgridc1').val('');
}

function gridtoform(slno,customerid)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		var passData = "switchtype=gridtoform&customerreference=" + encodeURIComponent(customerid) + "&amclastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		var queryString = "../ajax/customeramc.php";
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
					enableformelemnts();
					//alert(passData);
					$('#lastslno').val(response[0]);
					$('#contractid').html(response[0]);
					$('#customerlist').val(response[1]);
					$('#productcode').val(response[2]);
					//alert(response[3]);
					//alert(response[4]);
					//alert(response[5]);
					$('#DPC_startdate').val(response[3]);
					//alert(response[4] + form.enddate.value);
					$('#DPC_enddate').val(response[4]);
					//alert(response[4] + form.enddate.value);
					$('#remarks').val(response[5]);
					$('#createddate').html(response[6]);
					$('#useriddisplay').html(response[7]);
					$('#amcstatus').html(response[8]);
					$('#billno').val(response[10]);
					$('#billamount').val(response[11]);
					$('#billdate').val(response[12]);
					$('#displaycustomername').html(response[17]);
					if(response[9] == '1')
					{
						enabledelete();
					}
					else
					{
						disabledelete();
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

function generatedatagrid(startlimit)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData);
	queryString = "../ajax/customeramc.php";
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
				$('#tabgroupgridwb1').html("Total Count :  " + response[1]);
				$('#tabgroupgridc1_1').html(response[0]);
				$('#tabgroupgridc1link').html(response[2]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

//function to display 'Show more records' or 'Show all records' 
function generatemoredatagrid(startlimit,slno,showtype)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	queryString = "../ajax/customeramc.php";
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
				$('#tabgroupgridwb1').html("Total Count :  " + response[1]);
				$('#custresultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[0]) ;
				$('#tabgroupgridc1link').html(response[2]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


function displaycustomername()
{
	var passData = "switchtype=displaycustomer&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	//document.getElementById('lastslno').value = document.getElementById('customerlist').value;
	queryString = "../ajax/customeramc.php";
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
				var response = ajaxresponse.split('^');
				$('#displaycustomername').html(response[1]);
				$('#cuslastslno').val(response[2]);
				$('#productcode').html(response[0]);
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
	//customerdetailstoform(selectbox.value);
	displaycustomername();
	//document.getElementById('cuslastslno').value = selectbox.value;
	//newentry();
	$('#form-error').html('');
	$('#amcstatus').html('Not Available');
	$('#createddate').html('Not Available');
	$('#useriddisplay').html('Not Available');
	generatedatagrid('');
	enableformelemnts();
	
}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	
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


function searchbycontractid(contractid)
{
	$('#form-error').html('');
	var form = $('#submitform');
	$('#submitform')[0].reset();
	var passData = "switchtype=searchbycontractid&contractid=" + encodeURIComponent(contractid) + "&dummy=" + Math.floor(Math.random()*100032680100)//;alert(passData)
	var queryString = "../ajax/customeramc.php";
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
				if(response[1] == '')
				{
					alert('Contract ID Not Available.');

				}
				$('#lastslno').val(response['slno']);
				$('#contractid').html(response['slno']);
				$('#displaycustomername').html(response['businessname']);
				$('#productcode').html(response['product']);
				$('#DPC_startdate').val(response['startdate']);
				$('#DPC_enddate').val(response['enddate']);
				$('#remarks').val(response['remarks']);
				$('#createddate').html(response['createddate']);
				$('#useriddisplay').html(response['fullname']);
				$('#amcstatus').html(response['msg']);
				$('#customerlist').val(response['customerreference']);
				$('#billno').val(response['billno']);
				$('#billamount').val(response['billamount']);
				$('#cuslastslno').val(response['customerreference']);
				$('#billdate').val(response['billdate']);
				$('#tabgroupgridwb1').html('');
				$('#tabgroupgridc1_1').html('');
				$('#tabgroupgridc1link').html('');
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function searchbycontractidevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycontractid(input);
	}
}