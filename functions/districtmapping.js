
var dealerarray = new Array();

function refreshdealerarray()
{
	var form = $("#filterform");
	var relyonexcecutive_type = $("input[name='relyonexcecutive_type']:checked").val();
	var login_type = $("input[name='login_type']:checked").val();
	var passData;
	passData = "type=generatedealerlist&relyonexcecutive_type=" + encodeURIComponent(relyonexcecutive_type) + "&login_type=" + encodeURIComponent(login_type)  + "&dealerregion=" +encodeURIComponent($("#dealerregion").val());
	$("#dealerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/districtmapping.php";
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
				$("#totalcount").html(dealerarray.length);
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
	var actuallimit = 50;
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
	$('#form-meg').html('');
	//getdealerbname();
	enableformelemnts();
	districtdatagrid('');
	newentry();
}

function selectadealer(input)
{
	var selectbox = $('#dealerlist');
	
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

function selectadealer(input) {
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
	selectfromlist();
}

function districtdatagrid(startlimit)
{
	if($("#dealerlist").val())
	{
		var form = $('#submitform');
		var startlimit = '';
		var passData = "type=generategrid&dealerid=" + encodeURIComponent($("#dealerlist").val()) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*10054300000);
		$("#tabgroupgridc1_1").html(getprocessingimage());	
		queryString = "../ajax/districtmapping.php";
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
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						$("#tabgroupgridc1_1").html(response[1]);
						$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
						$("#getmoredistrictlink").html(response[3]);
					}
					else
					{
						$("#form-meg").html(errormessage('No datas found to be displayed.'));
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-meg").html(scripterror());
			}
		});		
	}
}


function getmoredistrictdatagrid(startlimit,slnocount,showtype)
{
	if($("#dealerlist").val())
	{
		var form = $('#submitform');
		var passData = "type=generategrid&dealerid=" + encodeURIComponent($("#dealerlist").val())  + "&startlimit=" + encodeURIComponent(startlimit)  + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
		$("#getmoredistrictlink").val(getprocessingimage());	
		queryString = "../ajax/districtmapping.php";
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
					$("#form-meg").html('');
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						$("#districtresultgrid").html($("#tabgroupgridc1_1").html());
						$("#tabgroupgridc1_1").html($("#districtresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
						$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
						$("#getmoredistrictlink").html(response[3]);
					}
					else
					{
						$("#form-meg").html(errormessage('No datas found to be displayed.'));
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-meg").html(scripterror());
			}
		});	
	}
}


function gridtoform(slno)
{
	if(slno != '')
	{
		var form = $("#submitform");
		var error = $("#form-meg");
		error.html('');
		$("#form-meg").html('');
		var passData = "type=gridtoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		error.innerHTML = getprocessingimage();
		var queryString = "../ajax/districtmapping.php";
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
					var response1 = ajaxresponse;
					if(response1['errorcode'] == '1')
					{
						$("#lastslno").val(response1['slno']);
						getdistrict('districtcodedisplay', response1['statecode']);
						$("#state").val(response1['statecode']);
						$("#district").val(response1['districtcode']);
					}
					else
					{
						error.html(errormessage('No datas found to be displyed.'));
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
	var error = $("#form-meg");
	var field = $("#dealerlist");
	if(!field.val()) { error.html(errormessage("Select A dealer. ")); field.focus(); return false; }
	var field = $("#state");
	if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
	var field = $("#district");
	if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
	var passData = "";
	if(command == 'save')
	{
		passData =  "type=save&dealerid=" + encodeURIComponent($("#dealerlist").val())  + "&district=" + encodeURIComponent($("#district").val()) + "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10045606700000);
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
	queryString = '../ajax/districtmapping.php';
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
				var response = ajaxresponse['errormessage'].split('^');//alert(response)
				if(response[0] == '1')
				{
					error.html('');
					$("#form-meg").html(successmessage(response[1])); 
					districtdatagrid(''); 
					newentry();
				}
				else if(response[0] == '2')
				{
					error.html('');
					$("#form-meg").html(errormessage(response[1])); 
					districtdatagrid(''); newentry();
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}

function newentry()
{
	$("#submitform" )[0].reset();
	$('#lastslno').val('');
}


function getdealerbname()
{
	var form = $('#submitform');
	var passData = "type=getdealername&dealerid=" + encodeURIComponent($('#dealerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-meg').html(getprocessingimage());	
	queryString = "../ajax/credits.php";
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
					$('#form-meg').html('');	
					$('#dealerdisplay').html(response[1]);
				}
				else
				{
					$('#dealerdisplay').html("No dealer Selected.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-meg").html(scripterror());
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

function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
}

