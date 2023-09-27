var schemearray = new Array();

function refreshschemearray()
{
	var passData = "switchtype=generateschemelist&dummy=" + Math.floor(Math.random()*10054300000);
	$("#schemeselectionprocess").html(getprocessingimage());
	queryString = "../ajax/scheme.php";
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
				schemearray = new Array();
				for( var i=0; i<response.length; i++)
				{
					schemearray[i] = response[i];
				}
				getschemelist();
				$("#schemeselectionprocess").html('');
				$("#totalcount").html(schemearray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#schemeselectionprocess").html(scripterror());
		}
	});		
}

function getschemelist()
{	
	var form = $('#submitform');
	var selectbox = $('#schemelist');
	var numberofscheme = schemearray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofscheme > actuallimit)?actuallimit:numberofscheme;
	//selectbox.options.length = 0;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = schemearray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function selectfromlist()
{
	
	var selectbox = $("#schemelist option:selected").val();
	$('#detailsearchtext').val($("#schemelist option:selected").text());
	$('#detailsearchtext').select();
	$('#tabgroupgridwb1').html('');
	schemedetailstoform(selectbox);	
}

function selectascheme(input)
{
	var selectbox = $('#schemelist');
	if(input == "")
	{
		getschemelist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');

		var addedcount = 0;
		for( var i=0; i < schemearray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = schemearray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = schemearray[i];
				}
				var result1 = pattern.test(trimdotspaces(schemearray[i]).toLowerCase());
				var result2 = pattern.test(schemearray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = schemearray[i].split("^");
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
		selectascheme(input);
	}
}

function scrollcustomer(type)
{
	var selectbox = $('#schemelist');
	var totalcus = $("#schemelist option").length;
	var selectedcus = $("select#schemelist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#schemelist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#schemelist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}

function schemedetailstoform(schemeid)
{
	if(schemeid != '')
	{
		$("#form-error").html('');
		var form = $("#submitform");
		$("#submitform" )[0].reset();
		$("#lastslno").val(schemeid);
		var passData = "switchtype=schemedetailstoform&lastslno=" + encodeURIComponent(schemeid) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$("#form-error").html(getprocessingimage());
		var queryString = "../ajax/scheme.php";
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
					$("#form-error").html('');
					var response = ajaxresponse;//alert(response)
					if(response['errorcode'] == '1')
					{
						$("#schemename").val(response['schemename']);
						$("#schemedescription").val(response['description']);
						autochecknew($("#disablescheme"), response['disablescheme']);
						$("#DPC_startdate").val(response['fromdate']);
						$("#DPC_enddate").val(response['todate']);
						$("#enteredby").html(response['fullname']);
						enabledelete();
					}
					else if(response['errorcode'] == '2')
					{
						$('#form-error').html(errormessage('No datas found to be displayed.'));
					}
				}
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});		
	}
}

function generateschemegrid(startlimit)
{
	var form = $("#submitform");
	var startlimit = '';
	var passData = "switchtype=generateschemegrid&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/scheme.php";
	$("#tabgroupgridc1_1").html(getprocessingimage());
	$("#tabgroupgridc1link").html('');
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
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					$("#tabgroupgridc1_1").html(response[1]);
					$("#tabgroupgridc1link").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html(errormessage("No datas found to be diaplayed."));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}

//Function for "show more records" or  "show all records" link  - to get registration records
function getmoregenerateschemegrid(startlimit,slnocount,showtype)
{
	var form = $("#submitform");
	var passData = "switchtype=generateschemegrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/scheme.php";
	$("#tabgroupgridc1link").html(getprocessingimage());
	ajaxobjext14 = $.ajax(
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
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					$("#resultgrid").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#resultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridc1link").html(response[3]);

				}
				else
				{
					$("#tabgroupgridc1_1").html(errormessage("No datas found to be diaplayed."));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		
}


function formsubmit(command)
{
	var passData = "";
	var form = $("#submitform");
	var error = $("#form-error");
	if(command == 'save')
	{
		var field = $("#schemename");
		if(!field.val()) { error.html(errormessage("Enter the Scheme Name. ")); field.focus(); return false; }
		if(field.val()) { if(!validateschemename(field.val())) { error.html(errormessage('Scheme name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
		var field = $("#schemedescription");
		var field = $("#DPC_startdate");
		if(!field.val()) { error.html(errormessage("Enter the From Date. ")); field.focus(); return false; }
		var field =  $("#DPC_enddate");
		if(!field.val()) { error.html(errormessage("Enter the To Date. ")); field.focus(); return false; }
		var field = $('#disablescheme:checked').val();
		if(field != 'on') var disablescheme = 'no'; else disablescheme = 'yes';
		passData =  "switchtype=save&schemename=" + encodeURIComponent($("#schemename").val()) + "&schemedescription=" + encodeURIComponent($("#schemedescription").val()) + "&startdate=" + encodeURIComponent($("#DPC_startdate").val()) + "&todate=" + encodeURIComponent($("#DPC_enddate").val()) + "&disablescheme=" + encodeURIComponent(disablescheme)+ "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete the selected Scheme?");
			if(confirmation)
			{
				passData =  "switchtype=delete&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			
			}
			else
			return false;
		}
		queryString = '../ajax/scheme.php';
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
					var response = ajaxresponse['errormessage'].split('^');//alert(response[0])
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
						generateschemegrid('');
						refreshschemearray();
						newschemeentry();
					}
					else if(response[0] == '4')
					{
						error.html(successmessage(response[1]));generateschemegrid('');newschemeentry();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						refreshschemearray();generateschemegrid('');
						newschemeentry();
					}
					else if(response[0] == '3')
					{
						error.html(errormessage(response[1]));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
}


function newschemeentry()
{
	var form = $("#submitform");
	$("#submitform" )[0].reset();
	$("#enteredby").html('');
	disabledelete();
	$("#lastslno").val('');
}


function searchbyschemeidevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchschemeid').val();
		schemedetailstoform(input);
	}
}

