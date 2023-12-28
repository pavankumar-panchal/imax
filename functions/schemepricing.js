var schemearray = new Array();

function refreshschemearray()
{
	var passData = "switchtype=generateschemelist&dummy=" + Math.floor(Math.random()*10054300000);
	$("#schemeselectionprocess").html(getprocessingimage());
	queryString = "../ajax/schemepricing.php";
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
	disableformelemnts();
	var form = $('#submitform');
	var selectbox = $('#schemelist');
	var numberofscheme = schemearray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofscheme > actuallimit)?actuallimit:numberofscheme;
	
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
	generateschemepricinggrid(selectbox,'');	
	$('#schemename').html($("#schemelist option:selected").text());
	$('#form-error').html('');
	enableformelemnts();
	disableamountfields();
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

function selectascheme(input) {
	var selectbox = $('#schemelist');
	if (input == "") {
		getschemelist();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < schemearray.length; i++) {
			// Check if any part of the name contains the input string
			if (schemearray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = schemearray[i].split("^");
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

function schemedetailstoform(slno)
{
	if(slno != '')
	{	//alert(slno);
		$("#form-error").html('');
		var form = $("#submitform");
		$("#submitform" )[0].reset();
		$("#lastslno").val(slno);
		var passData = "switchtype=schemedetailstoform&lastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/schemepricing.php";
		ajaxcall31 = $.ajax(
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
					var response = ajaxresponse;//alert(response)
					if(response['newsuprice'] != 'NA')
					{
						$("#singleusernewamt").val(response['newsuprice']);
						autochecknew($("#singleusernew"), 'yes');
					}
					if(response['updatesuprice'] != 'NA')
					{
						$("#singleuserupdationamt").val(response['updatesuprice']);
						autochecknew($("#singleuserupdation"), 'yes');
					}
					if(response['newmuprice'] != 'NA')
					{
						$("#multiusernewamt").val(response['newmuprice']);
						autochecknew($("#multiusernew"),'yes');
					}
					if(response['updatemuprice'] != 'NA')
					{ 
						$("#multiuserupdationamt").val(response['updatemuprice']);
						autochecknew($("#multiuserupdation"), 'yes');
					}
					if(response['newaddlicenseprice'] != 'NA')
					{
						$("#additionalnewamt").val(response['newaddlicenseprice']);
						autochecknew($("#additionalnew"), 'yes');
					}
					if(response['updationaddlicenseprice'] != 'NA')
					{
						$("#additionalupdationamt").val(response['updationaddlicenseprice']);
						autochecknew($("#additionalupdation"), 'yes');
					}
					$("#productcode").val(response['product']);
					$("#enteredby").html(response['fullname']);
					enabledelete();
					enabledisableamountfields();
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
	}
}

function generateschemepricinggrid(schemeid,startlimit)
{
	var form = $("#submitform");
	$("#schemelastslno").val(schemeid);
	var startlimit = '';
	var passData = "switchtype=generateschemepricinggrid&startlimit="+ encodeURIComponent(startlimit) + "&schemeid=" + schemeid;
	var queryString = "../ajax/schemepricing.php"; 
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
					$("#tabgroupgridc1_1").html(errormessage("No datas found to be displayed."));
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" or "show all records" link - to get registration records
function getmoregenerateschemepricinggrid(schemeid,startlimit,slno,showtype)
{
	var form = $("#submitform");
	var passData = "switchtype=generateschemepricinggrid&startlimit="+ encodeURIComponent(startlimit) + "&slno=" + slno+ "&schemeid=" + schemeid+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
//	alert(passData);
	var queryString = "../ajax/schemepricing.php";
	$("#tabgroupgridc1link").html(getprocessingimage());
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
					$("#tabgroupgridc1_1").html(errormessage("No datas found to be displayed."));
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
	var form = $("#submitform");
	var error = $("#form-error");
	if(command == 'save')
	{
		var field = $("#productcode");
		if(!field.val()) { error.html(errormessage("Select a product.")); field.focus(); return false; }
		var singleusernewamt = $("#singleusernewamt").val();
		var singleuserupdationamt = $("#singleuserupdationamt").val();
		var multiusernewamt = $("#multiusernewamt").val();
		var multiuserupdationamt = $("#multiuserupdationamt").val();
		var additionalnewamt = $("#additionalnewamt").val();
		var additionalupdationamt = $("#additionalupdationamt").val();
		var schemelist = $('#schemelist').val();
		
		var field = $('#singleusernew:checked').val();
		if(field == 'on')
		{
			var singleusernewamt = $('#singleusernewamt').val();
			if(!singleusernewamt) { error.html(errormessage('Enter the amount / Zero for free.'));  $('#singleusernewamt').focus(); return false; }
			if(!validateamount(singleusernewamt))
			{error.html(errormessage('Amount is not Valid.')); $('#singleusernewamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(singleusernewamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#singleusernewamt').focus(); return false;
				}
			}
		}
		var field = $('#singleuserupdation:checked').val();
		if(field == 'on')
		{
			var singleuserupdationamt = $('#singleuserupdationamt').val();
			if(!singleuserupdationamt) {error.html(errormessage('Enter the amount / Zero for free.')); $('#singleuserupdationamt').focus(); return false; }
			if(!validateamount(singleuserupdationamt))
			{error.html(errormessage('Amount is not Valid.')); $('#singleuserupdationamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(singleuserupdationamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#singleuserupdationamt').focus(); return false;
				}
			}
		}
		var field = $('#multiusernew:checked').val();
		if(field == 'on')
		{
			var multiusernewamt = $('#multiusernewamt').val();
			if(!multiusernewamt) {error.html(errormessage('Enter the amount / Zero for free.'));  $('#multiusernewamt').focus(); return false; }
			if(!validateamount(multiusernewamt))
			{error.html(errormessage('Amount is not Valid.'));  $('#multiusernewamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(multiusernewamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#multiusernewamt').focus(); return false;
				}
			}
		}
		var field = $('#multiuserupdation:checked').val();
		if(field == 'on')
		{
			var multiuserupdationamt = $('#multiuserupdationamt').val();
			if(!multiuserupdationamt) {error.html(errormessage('Enter the amount / Zero for free.')); $('#multiuserupdationamt').focus(); return false; }
			if(!validateamount(multiuserupdationamt))
			{error.html(errormessage('Enter the amount / Zero for free.')); $('#multiuserupdationamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(multiuserupdationamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#multiuserupdationamt').focus(); return false;
				}
			}
		}
		var field = $('#additionalnew:checked').val();
		if(field == 'on')
		{
			var additionalnewamt = $('#additionalnewamt').val();
			if(!additionalnewamt) {error.html(errormessage('Enter the amount / Zero for free.')); $('#additionalnewamt').focus(); return false; }
			if(!validateamount(additionalnewamt))
			{error.html(errormessage('Amount is not Valid.')); $('#additionalnewamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(additionalnewamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#additionalnewamt').focus(); return false;
				}
			}
		}
		var field = $('#additionalupdation:checked').val();
		if(field == 'on')
		{
			var additionalupdationamt = $('#additionalupdationamt').val();
			if(!additionalupdationamt) {error.html(errormessage('Enter the amount / Zero for free.')); $('#additionalupdationamt').focus(); return false; }
			if(!validateamount(additionalupdationamt))
			{error.html(errormessage('Amount is not Valid.')); $('#additionalupdationamt').focus(); return false; }
			if(schemelist == '1')
			{
				if(additionalupdationamt == 0)
				{
					error.html(errormessage('Min. amount must be 1 for General scheme.')); $('#additionalupdationamt').focus(); return false;
				}
			}
		}
		
		var checkedvalues = validateproductcheckboxes();
		if(checkedvalues == false)	{error.html(errormessage("Enter the Amount for atleast for one usage type"));  return false;	}

		passData =  "switchtype=save&productcode=" + encodeURIComponent($('#productcode').val()) + "&scheme=" + encodeURIComponent($('#schemelist').val())+ "&singleusernewamt=" + encodeURIComponent(singleusernewamt) + "&singleuserupdationamt=" + encodeURIComponent(singleuserupdationamt) + "&multiusernewamt=" + encodeURIComponent(multiusernewamt) + "&multiuserupdationamt=" + encodeURIComponent(multiuserupdationamt) + "&additionalnewamt=" + encodeURIComponent(additionalnewamt) + "&additionalupdationamt=" + encodeURIComponent(additionalupdationamt)+ "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete the selected Scheme?");
			if(confirmation)
			{
				passData =  "switchtype=delete&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			
			}
			else
			return false;
		}
		//alert(passData)
		queryString = '../ajax/schemepricing.php';
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
						error.html(successmessage(response[1]));
						generateschemepricinggrid($('#schemelist').val(),'');
						//refreshschemearray();
						newschemeentry();
					}
					else if(response[0] == '4')
					{
						error.html(successmessage(response[1]));
						generateschemepricinggrid($('#schemelastslno').val(),'');
						newschemeentry();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						generateschemepricinggrid($('#schemelastslno').val(),'');
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
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	disabledelete();
	disableamountfields();
}


function searchbyschemeidevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchschemeid').val();
		searchbyschemeid(input);
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

function disableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
	}
}


function validateproductcheckboxes()
{
var chksvalue = $("input[name='usagetype[]']");
var hasChecked = false;
for (var i = 0; i < chksvalue.length; i++)
{
	if ($(chksvalue[i]).is(':checked'))
	{
		hasChecked = true;
		return true
	}
}
	if (!hasChecked)
	{
		return false
	}
}


function disableamountfields()
{
	$('#singleusernewamt').attr('disabled',true);
	$('#singleuserupdationamt').attr('disabled',true);
	$('#multiusernewamt').attr('disabled',true);
	$('#multiuserupdationamt').attr('disabled',true);
	$('#additionalnewamt').attr('disabled',true);
	$('#additionalupdationamt').attr('disabled',true);
}

function enabledisableamountfields()
{
	if($('#singleusernew').is(':checked')  == true)
	{
		$('#singleusernewamt').attr('disabled',false);
		if($('#singleusernewamt').val() == 'NA')
		$('#singleusernewamt').val('');
	}
	else
	{
		$('#singleusernewamt').attr('disabled',true);
		$('#singleusernewamt').val('NA');
	}
	if($('#singleuserupdation').is(':checked')  == true)
	{
		$('#singleuserupdationamt').attr('disabled',false);
		if($('#singleuserupdationamt').val() == 'NA')
		$('#singleuserupdationamt').val('');
	}
	else
	{
		$('#singleuserupdationamt').attr('disabled',true);
		$('#singleuserupdationamt').val('NA');
	}
	if($('#multiusernew').is(':checked')  == true)
	{
		$('#multiusernewamt').attr('disabled',false);
		if($('#multiusernewamt').val() == 'NA')
		$('#multiusernewamt').val('');
	}
	else
	{
		$('#multiusernewamt').attr('disabled',true);
		$('#multiusernewamt').val('NA');
	}
	if($('#multiuserupdation').is(':checked') == true)
	{
		$('#multiuserupdationamt').attr('disabled',false);
		if($('#multiuserupdationamt').val() == 'NA')
		$('#multiuserupdationamt').val('');
	}
	else
	{
		$('#multiuserupdationamt').attr('disabled',true);
		$('#multiuserupdationamt').val('NA');
	}
	if($('#additionalnew').is(':checked')  == true)
	{
		$('#additionalnewamt').attr('disabled',false);
		if($('#additionalnewamt').val() == 'NA')
		$('#additionalnewamt').val('');
	}
	else
	{
		$('#additionalnewamt').attr('disabled',true);
		$('#additionalnewamt').val('NA');
	}
	if($('#additionalupdation').is(':checked')  == true)
	{
		$('#additionalupdationamt').attr('disabled',false);
		if($('#additionalupdationamt').val() == 'NA')
		$('#additionalupdationamt').val('');
	}
	else
	{
		$('#additionalupdationamt').attr('disabled',true);
		$('#additionalupdationamt').val('NA');
	}
}


function searchbyschemeid(scheme)
{
	$('#form-error').html('');
	var form = $('#submitform');
	$('#submitform')[0].reset();
	//form.cusinteractionslno.value = cusid;
	var passData = "switchtype=searchbyschemeid&scheme=" + encodeURIComponent(scheme) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	var queryString = "../ajax/schemepricing.php";
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
				var response = ajaxresponse;//alert(response)
				if(response['newsuprice'] != 'NA')
				{
					$('#singleusernewamt').val(response['newsuprice']);
					autochecknew($('#singleusernew'), 'yes');
				}
				if(response['newmuprice'] != 'NA')
				{
					$('#multiusernewamt').val(response['newmuprice']);
					autochecknew($('#multiusernew'), 'yes');
				}
				if(response['updatesuprice'] != 'NA')
				{
					$('#singleuserupdationamt').val(response['updatesuprice']);
					autochecknew($('#singleuserupdation'), 'yes');
				}
				if(response['updatemuprice'] != 'NA')
				{ 
					$('#multiuserupdationamt').val(response['updatemuprice']);
					autochecknew($('#multiuserupdation'), 'yes');
				}
				if(response['newaddlicenseprice'] != 'NA')
				{
					$('#additionalnewamt').val(response['newaddlicenseprice']);
					autochecknew($('#additionalnew'), 'yes');

				}
				if(response['updationaddlicenseprice'] != 'NA')
				{
					$('#additionalupdationamt').val(response['updationaddlicenseprice']);
					autochecknew(form.additionalupdation, 'yes');
				}
				$('#productcode').val(response['product']);
				$('#enteredby').html(response['fullname']);
				$('#schemename').html(response['schemename']);
				enabledelete();
				enabledisableamountfields();
				generateschemepricinggrid(scheme,'');
			
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		

}