// JavaScript Document
var dealerarray = new Array();


function refreshdealerarray()
{
	var form = $("#filterform");
	var relyonexcecutive_type = $("input[name='relyonexcecutive_type']:checked").val();
	var login_type = $("input[name='login_type']:checked").val();
	passData = "type=generatedealerlist&relyonexcecutive_type=" + encodeURIComponent(relyonexcecutive_type) + "&login_type=" + encodeURIComponent(login_type)  + "&dealerregion=" +encodeURIComponent($("#dealerregion").val());
	$("#dealerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/credits.php";
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
				var response = ajaxresponse;//alert(response)
				dealerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					dealerarray[i] = response[i];
				}
				getdealerlist();
				$("#dealerselectionprocess").html('');
				$('#displayfilter').hide();
				$("#totalcountdealer").html(dealerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#dealerselectionprocess").html(scripterror());
		}
	});		
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


function selectfromlist()
{
	var selectbox = $("#dealerlist option:selected").val();
	$('#detailsearchtext').val($("#dealerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#dealerdisplay').html($("#dealerlist option:selected").text());
	getdealerbname();
	enableformelemnts();
	creditdatagrid('');
	newentry();
	$("#form-meg").html('')
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
				var result1 = pattern.test(trimdotspaces(dealerarray[i]).toLowerCase());
				var result2 = pattern.test(dealerarray[i].toLowerCase());
				if(result1 || result2)
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
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectadealer(input);
	}
}

function scrolldealer(type)
{	
	var selectbox = $('#dealerlist');
	var totalcus = $("#dealerlist option").length;
	var selectedcus = $("select#dealerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#dealerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#dealerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();;
}

function creditdatagrid(startlimit)
{
	if($("#dealerlist").val())
	{
		var form = $("#submitform");
		var passData = "type=generategrid&dealerid=" + encodeURIComponent($("#dealerlist").val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);
		$("#form-error").html(getprocessingimage());	
		queryString = "../ajax/credits.php";
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
					$("#form-error").html('');	
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						$("#tabgroupgridc1_1").html(response[1]);
						$("#getmorerecordslink").html(response[2]);
						$("#tabgroupcount").html('Total Count: ' + response[3]);
					}
					else
					{
						$("#form-error").html(errormessage('Unable to Connect...' + ajaxresponse));
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});	
	}
}


function getmoredatagrid(startlimit,slno,showtype)
{
	if($("#dealerlist").val())
	{
		var form = $("#submitform");
		var passData = "type=generategrid&dealerid=" + encodeURIComponent($("#dealerlist").val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
		$("#getmorerecordslink").html(getprocessingimage());	
		queryString = "../ajax/credits.php";
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
					if(response[0] == '1')
					{
						$("#resultgrid").html($("#tabgroupgridc1_1").html());
						$("#tabgroupgridc1_1").html($("#resultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
						$("#getmorerecordslink").html(response[2]);
						$("#tabgroupcount").html('Total Count: ' + response[3]);
					}
					else
					{
						$("#form-error").html(errormessage('Unable to Connect...'));
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});	
	}
}




function gridtoform(slno)
{
	if(slno != '')
	{
		var form = $("#submitform");
		var error = $("#form-error");
		error.html('');
		$("#form-meg").html('');
		var passData = "type=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		error.html(getprocessingimage());
		var queryString = "../ajax/credits.php";
		ajaxcall4 = $.ajax(
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
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$("#lastslno").val(response['slno']);
						$("#dealerdisplay").html(response['businessname']);
						$("#credittype").html(response['credittype']);
						$("#creditamount").val(response['creditamount']);
						$("#creditdate").val(response['createddate']);
						$("#remarks").val(response['remarks']);
						$("#userid").html(response['fullname']);
						if(response['createdby'] == '1')
						{
							enabledelete();
						}
						else
						{
							disabledelete();
						}
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

function formsubmit(command)
{
	var form = $("#submitform");
	var error = $("#form-error");
	$("#form-meg").html('');
	var field = $("#creditamount");
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	if(!field.val()) { error.html(errormessage('Enter the Credit Amount.')); field.focus(); return false; }
	else
	{
		var passData = "";
		if(command == 'save')
		{
			passData =  "type=save&dealerid=" + encodeURIComponent($("#dealerlist").val()) + "&creditamount=" + encodeURIComponent($("#creditamount").val()) + "&credittype=manual"  + "&creditdate=" + encodeURIComponent($("#creditdate").val()) + "&remarks=" + encodeURIComponent($("#remarks").val()) + "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10045606700000);
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete?");
			if(confirmation)
			{
				passData =  "type=delete&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*100006454000000);
			}
			else
			return false;
		}
		queryString = '../ajax/credits.php';
		error.html(getprocessingimage());
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
					error.html('');
					var response = ajaxresponse['errormessage'].split('^');//alert(response)
					if(response[0] == '1')
					{
						$("#form-meg").html(successmessage(response[1]));
						newentry(); 
						creditdatagrid(''); 
						getdealerbname();
					}
					else if(response[0] == '2')
					{
						$("#form-meg").html(successmessage(response[1])); creditdatagrid(''); newentry(); //getdealerbname();
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

function newentry()
{
	var form = $("#submitform");
	$("#submitform" )[0].reset();
	$("#lastslno").val('');
	$("#userid").html('Not Available');
	//$("#form-meg").html('')
}

function searchrecord(recordno)
{
	$("#form-error").html('');
	var form = $("#submitform");
	var passData = "type=searchbyrecordno&recordno=" + encodeURIComponent(recordno) + "&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/credits.php";
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
				var response = ajaxresponse;//alert(response)
				if(response['errorcode'] == '1')
				{
					$("#lastslno").val(response['dealerid']);
					$("#dealerlist").val(response['credittype']);
					enableformelemnts();
					creditdatagrid('');
					$("#dealerdisplay").html(response['businessname']);
					$("#credittype").html(response['credittype']);
					$("#creditamount").val(response['creditamount']);
					$("#creditdate").val(response['createddate']);
					$("#remarks").val(response['remarks']);
				}
				else
				{
					$("#form-error").html(errormessage('Unable to Connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});		
}

function searchbyrecordsevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchrecord').val();
		searchrecord(input);
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

function getdealerbname()
{
	var form = $('#submitform');
	var passData = "type=getdealername&dealerid=" + encodeURIComponent($('#dealerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/credits.php";
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
				var response = ajaxresponse;
				if(response['errorcode'] == '1')
				{
					$('#form-error').html('');	
					$('#dealerdisplay').html(response['businessname']);
					$('#creditamountdisplay').html(response['totalcredit']);
				}
				else
				{
					$('#form-error').html(errormessage('Cannot Connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});		
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
