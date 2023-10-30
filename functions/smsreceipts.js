var customerarray = new Array();


function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#invoivcelist');
	if(!field.val()) { error.html(errormessage("Select an Invoivce No.")); field.focus(); return false; }
	var field = $('#receiptamount');
	if(!field.val()) { error.html(errormessage("Enter the Amount.")); field.focus(); return false; }
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	var paymentmode = $('input[name=paymentmode]:checked').val();
	var field = $('#remarks');
	var field = $('#privatenote');
	if($('#lastslno').val() == '')
	{
		if($('#receiptamount').val() > $('#totalamount').val())
		{
			error.html(errormessage('Receipt Amount is greater than invoice amount.')); return false; 
		}
	}
	var passData = "";
	passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&invoivcelist=" + encodeURIComponent($('#invoivcelist').val()) + "&privatenote=" + encodeURIComponent($('#privatenote').val()) +  "&remarks=" + encodeURIComponent($('#remarks').val()) +  "&paymentmode=" + encodeURIComponent(paymentmode) +  "&receiptamount=" + encodeURIComponent($('#receiptamount').val()) +  "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
	queryString = '../ajax/smsreceipts.php';
	error.html(getprocessingimage());
	ajaxcall1 = $.ajax(
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
				if(response[0] == '1')
				{
					error.html(successmessage(response[1]));
					generatereceiptgrid('');
					newcreditentry();

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

function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/smscredits.php";
	ajaxcall2 = $.ajax(
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
				var response = ajaxresponse.split('^*^');//alert(response)
				customerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i];
				}
				getcustomerlist1();
				$('#customerselectionprocess').html('');
				$('#totalcount').html(customerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});
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
	$('#invoiceamount').html('Not Available');
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
	generatereceiptgrid('');
	getuserinvoicelist();
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


function getuserinvoicelist()
{
	var form = $('#submitform');
	var passData = "switchtype=getuserinvoicelist&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/smsreceipts.php";
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#form-error').html('');	
					$('#smsaccountlist').html(response[1]);
				}
				else
				{
					$('#form-error').html(errormessage('Unable to Connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

function getinovoiceamount()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#invoivcelist');
	if(!field.val()) { error.html(errormessage("Select an Invoivce No.")); field.focus(); return false; }
	var passData = "switchtype=getinovoiceamount&invoiceno=" + encodeURIComponent($('#invoivcelist').val());
	$('#form-error').html( getprocessingimage());	
	queryString = "../ajax/smsreceipts.php";
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
				if(response[0] == '1')
				{
					$('#form-error').html('');	
					$('#invoiceamount').html(response[1]);
				}
				else
				{
					$('#invoiceamount').html("Not availale");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}

function gettotalamount()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#invoivcelist');
	if(!field.val()) { error.html(errormessage("Select an Invoivce No.")); field.focus(); return false; }
	var invoiceamount = $('#invoiceamount').html();
	var receiptamount = $('#receiptamount');
	if(receiptamount.val())	{ if(!validateamount(receiptamount.val())) { error.html(errormessage('Amount is not Valid.')); receiptamount.focus(); return false; } }
	var totalamount = invoiceamount - receiptamount.val();
	if(totalamount < 0){ error.html(errormessage('Receipt Amount is greater than invoice amount.')); return false; }
		
	$('#totalamount').val() = totalamount;
}

//Function to generate Receipt Grid
function generatereceiptgrid(startlimit)
{
	if($('#customerlist').val())
	{
		var form = $('#submitform');
		var startlimit = '';
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&customerreference=" + $('#customerlist').val();//alert(passData);
		var queryString = "../ajax/smsreceipts.php";
		$('#tabgroupgridc1_1').html(getprocessingimage());
		$('#tabgroupgridc1link').html('');
		ajaxcall6 = $.ajax(
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
					if(response[0] == '1')
					{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

//Function to "show all" or "Show More" Records
function getmoregeneratereceiptgrid(startlimit,slnocount,showtype)
{
	if($('#customerlist').val())
	{
		var form = $('#submitform');
	//	$('#lastslno').val() = id;	
		var passData = "switchtype=generatereceiptgrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&customerreference=" + $('#customerlist').val() + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/smsreceipts.php";
		$('#tabgroupgridc1link').html(getprocessingimage());
		ajaxcall7 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(response);
					if(response[0] == '1')
					{
						$('#tabgroupgridwb1').html("Total Count :  " + response[1]);
						$('#resultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
			}, 
			error: function(a,b)
			{
				$("#tabgroupgridc1_1").html(scripterror());
			}
		});
	}
}

function receiptgridtoform(slno)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		var passData = "switchtype=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		error.html(getprocessingimage());
		var queryString = "../ajax/smsreceipts.php";
		ajaxcall9 = $.ajax(
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
					error.html('');
					var response = ajaxresponse.split("^");
					if(response[0] == '1')
					{ 
						$('#lastslno').val(response[1]);
						$('#invoivcelist').val(response[2]);
						$('#receiptamount').val(response[3]);
						$('#invoiceamount').html(response[4]);
						$('#remarks').val(response[5]);
						$('#privatenote').val(response[6]);
						//alert(response[7]);
						//$('#paymentmode').is(':checked');
					}
					else
					{
						error.html(errormessage('Unable to Connect.'));
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
		$('#quantity').attr('readOnly',false);
}
