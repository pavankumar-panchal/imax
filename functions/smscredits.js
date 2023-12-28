var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;var process2;var process3;var process4;




function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#smsaccount');
	if(!field.val()) { error.html(errormessage("Select an Account.")); field.focus(); return false; }
	var field = $('#remarks');
	var field = $('#privatenote');
	//var field = $('#sendinvoice').is(':checked');
	//if(field == true) var sendinvoice = 'yes'; else  var sendinvoice = 'no';
	var passData = "";
	if($('#invoiceno').val() == 'New')
	{
		var field = $('#quantity');
		if(!field.val()) { error.html(errormessage("Enter the Quantity.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Quantity is not Valid.')); field.focus(); return false; } }
		if(field.val() > 100000)
		{
			error.html(errormessage('Quantity should be between 1 and 100000.')); field.focus(); return false;	}
			var confirmation = confirm("The Invoice quantity once saved cannot be edited. Respective credits will be addded to the SMS Account. Do you really wish to continue?");
			if(confirmation)
			{
				passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&quantity=" + encodeURIComponent($('#quantity').val()) + "&privatenote=" + encodeURIComponent($('#privatenote').val()) +  "&remarks=" + encodeURIComponent($('#remarks').val()) +  "&smsuserid=" + encodeURIComponent($('#smsaccount').val())+  "&totalamount=" + encodeURIComponent($('#billamount').val())+  "&servicetax=" + encodeURIComponent($('#servicetax').val())+  "&netamount=" + encodeURIComponent($('#netamount').val())  +  "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
			}
			else
				return false;
		}
		else
		{
			passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#customerlist').val())  + "&privatenote=" + encodeURIComponent($('#privatenote').val()) +  "&remarks=" + encodeURIComponent($('#remarks').val()) +  "&smsuserid=" + encodeURIComponent($('#smsaccount').val())+  "&totalamount=" + encodeURIComponent($('#billamount').val())+  "&servicetax=" + encodeURIComponent($('#servicetax').val())+  "&netamount=" + encodeURIComponent($('#netamount').val())  +  "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
		}
		queryString = '../ajax/smscredits.php';
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
					var response = ajaxresponse.split('^');
						if(response[0] == '1')
						{
							error.html(successmessage(response[1]));
							generatesmsgrid('');
							newcreditentry();
							gettotalcredit();
							/*if(form.lastslno.value != '')
							{
								$('#billamount').readOnly  = true;
							}
							else
							{
								$('#quantity').innerHTML = '<input name="quantity" type="text" class="swifttext-mandatory" id="quantity" size="30" maxlength="8" autocomplete="off"  onkeyup="gettotalamount();"/>';
								$('#billamount').readOnly  = false;
							}*/
						}
						else
						{
							error.html(errormessage('Unable to Connect...' + ajaxresponse));
						}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/smscredits.php";
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
	queryString = "../ajax/smscredits.php";
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
	
	queryString = "../ajax/smscredits.php";
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

	queryString = "../ajax/smscredits.php";
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
	
	queryString = "../ajax/smscredits.php";
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
	//selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}

function newcreditentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#displayenteredby').html('Not Available');
	$('#creditsavailableforaccount').html('Not Available');
	$('#smsaccount').attr('disabled',false);
	$('#billamount').attr('readOnly',false);
	//$('#sendinvoice').attr('disabled',false);
	$('#displayquantity').html('<input name="quantity" type="text" class="swifttext-mandatory" id="quantity" size="30" maxlength="6" autocomplete="off"  onkeyup="gettotalamount();"/>');
}


function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaycustomername').html($("#customerlist option:selected").text());
	newcreditentry();
	$('#form-error').html('');
	enableformelemnts();
	generatesmsgrid('');
	gettotalcredit();
	getuseraccountlist();
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

//function to get credit details to form
function smsgridtoform(slno)
{
	if(slno != '')
	{
		$('#billamount').attr('readOnly',true);
		//$('sendinvoice').attr('disabled',true);
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		var passData = "switchtype=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		error.html(getprocessingimage());
		var queryString = "../ajax/smscredits.php";
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
					error.html('');
					var response = (ajaxresponse).split("^");
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						if(response['errorcode'] == 1)
						{
							$('#lastslno').val(response['slno']);
							$('#displayquantity').html(response['quantity']);
							$('#invoiceno').val(response['invoiceno']);
							$('#billamount').val(response['total']);
							$('#crediteddate').html(response['createddate']);
							$('#remarks').val(response['remarks']);
							$('#displayenteredby').html(response['fullname']);
							$('#smsaccount').val(response['smsuserid']);
							$('#smsaccount').attr('disabled',true);
							$('#privatenote').val(response['privatenote']);
							$('#servicetax').val(response['taxamount']);
							$('#netamount').val(response['netamount']);
						}
						else
							error.html(scripterror());
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
	}
	else
	{
		//$('#quantity').readOnly  = false;
		$('#billamount').attr('readOnly',false);
	}
}

//function to generate credit grid for the paricu;ar customer
function generatesmsgrid(startlimit)
{
	if($('#customerlist').val())
	{
		var form = $('#submitform');
		var startlimit = '';
		var passData = "switchtype=generatesmsgrid&startlimit="+ encodeURIComponent(startlimit) + "&customerreference=" + encodeURIComponent($('#customerlist').val());
		var queryString = "../ajax/smscredits.php";
		$('#tabgroupgridc1_1').html(getprocessingimage());
		$('#tabgroupgridc1link').html('');
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
					if(response[0] == 1)
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#tabgroupgridc1_1').html(response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
						$('#tabgroupgridc1_1').html(scripterror());
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

//function to "show more/show all" records in grid
function getmoregenerateschemegrid(startlimit,slnocount,showtype)
{
	if($('#customerlist').val())
	{
		var form = $('#submitform');
	//	$('#lastslno').value = id;	
		var passData = "switchtype=generatesmsgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*1000782200000);
		var queryString = "../ajax/smscredits.php";
		$('#tabgroupgridc1link').html(getprocessingimage());
		ajaxcall5 = $.ajax(
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
					if(response[0] == 1)
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#resultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
						$('#tabgroupgridc1_1').html(scripterror());
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

//function to get the total credits available for the customer
function gettotalcredit()
{
	var form = $('#submitform');
	var passData = "switchtype=gettotalcredits&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/smscredits.php";
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
				var response = ajaxresponse.split('^');
				//document.getElementById('form-error').innerHTML = '';	
				if(response[0] == 1)
				{
					$('#creditamountdisplay').html(response[1]);
				}
				else
					$('#form-error').html(errormessage(scripterror()));
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

//functio to get the account list belonging to that particular customer
function getuseraccountlist()
{
	var form = $('#submitform');
	var passData = "switchtype=getuseraccountlist&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/smscredits.php";
	ajaxcall7 = $.ajax(
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
				if(response[0] == 1)
				{
					$('#form-error').html('');	
					$('#smsaccountlist').html(response[1]);
				}
				else
					$('#form-error').html(errormessage(scripterror()));
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

//function to calucate the amount depending on the quantity entered
function gettotalamount()
{
	var form = $('#submitform');
	var error =$('#form-error');
	var field = $('#quantity');
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Quantity is not Valid.')); field.focus(); return false; } }
	if(field.val() > 100000)
	{error.html(errormessage('Quantity should be between 1 and 100000.')); field.focus(); return false;	}
	var passData = "switchtype=gettotalamount&quantity=" + encodeURIComponent($('#quantity').val());//alert(passData);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/smscredits.php";
	ajaxcall8 = $.ajax(
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
				var response = ajaxresponse.split('^');//alert(response);
				$('#form-error').html('');	
				if(response[0] == 1)
				{
					$('#billamount').val(response[1]);
					$('#servicetax').val(response[2]);
					$('#netamount').val(response[3]);
				}
				else
					$('#form-error').html(errormessage(scripterror()));
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});

}


//Function to edit Total amount
function editamount()
{
	var form = $('#submitform');
	var billamount = $('#billamount');
	var passData = "switchtype=gettotalamount&billamount=" + encodeURIComponent($('#billamount').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//if(billamount.value == 0)
	//	$('#sendinvoice').disabled = true;
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/smscredits.php";
	ajaxcall9 = $.ajax(
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
				$('#form-error').html('');	
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{
					$('#servicetax').val(response[2]);
					$('#netamount').val(response[3]);
				}
				else
					$('#form-error').html(errormessage(scripterror()));
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}


//function to calculate total credits for selected SMS Account
function gettotalcreditforthataccount()
{
	var form = $('#submitform');
	var error =$('#form-error');
	var field = $('#smsaccount');
	var passData = "switchtype=gettotalcreditforthataccount&smsuserid=" + encodeURIComponent($('#smsaccount').val());
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/smscredits.php";
	ajaxcall10 = $.ajax(
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
				$('#form-error').html('');	
				if(response[0] == 1)
				{
					$('#creditsavailableforaccount').html(response[1] + ' SMSes');
				}
				else
					$('#form-error').html(errormessage(scripterror()));
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});

}