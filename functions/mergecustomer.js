
var district1 = '';district2 = '';
var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();

var customerarray2 = new Array();
var customerarray12 = new Array();
var customerarray22 = new Array();
var customerarray32 = new Array();
var customerarray42= new Array();

var deletearray = new Array();
var contactarray = '';
var process1;var process2;var process3;var process4;
var process12;var process22;var process32;var process42;

//var t=setTimeout("refreshcustomerarray();",30000);


function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	var selectbox = $('#customerlist');//alert(selectbox.value);
	var selectbox2 = $('#customerlist2');//alert(selectbox2.value);
	var searchcustomerfrom = $('#detailsearchtext').val();
	var searchcustomerto = $('#detailsearchtext2').val();
	var field = $('#customerlist');
	if(!field.val()) { error.html(errormessage('Select the customer from the list to be Merged with.')); field.focus(); return false; }
	var field = $('#customerlist2');
	if(!field.val()) { error.html(errormessage('Select the customer from the list to Merge.')); field.focus(); return false; }
	tabopen5('1','tabg1');
	var field = $('#businessname');
	if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }


	var rowcount = $('#adddescriptionrows tr').length;
	tabopen5('2','tabg1');
	var l=1;
	
	while(l<=(rowcount/13))
	{
		if(!$("#selectiontype1").val())
		{
				error.html(errormessage("Minimum of ONE contact detail is mandatory")); return false;
		}
		else
		var field = $("#"+'selectiontype'+l);
		if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
		var field = $("#"+'phone'+l);
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid (One) Phone Number.')); field.focus(); return false; } }
		var field = $("#"+'cell'+l);
		if(field.val()) { if(!cellvalidation(field.val())) { error.html(errormessage('Enter the valid (One) Cell Number.')); field.focus(); return false; } }
		var field = $("#"+'emailid'+l);
		if(field.val()) { if(!checkemail(field.val())) { error.html(errormessage('Enter the valid (One) Email Id.')); field.focus(); return false; } }
		var field = $("#"+'name'+l);
		if(field.val()) { if(!contactpersonvalidate(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Numeric / space.')); field.focus(); return false; } }
		l++;
		
	}
	for(j=1;j<=(rowcount/13);j++)
	{
		var typefield = $("#"+'selectiontype'+j);

		var namefield = $("#"+'name'+j);
		if(namevalues == '')
			var namevalues = namefield.val();
		else
			var namevalues = namevalues + '~' + namefield.val();
		var phonefield = $("#"+'phone'+j);
		if(phonevalues == '')
			var phonevalues = phonefield.val();
		else
			var phonevalues = phonevalues + '~' + phonefield.val();
		var cellfield = $("#"+'cell'+j);
		if(cellvalues == '')
			var cellvalues = cellfield.val();
		else
			var cellvalues = cellvalues + '~' + cellfield.val();
		var emailfield = $("#"+'emailid'+j);
		if(emailvalues == '')
			var emailvalues = emailfield.val();
		else
			var emailvalues = emailvalues + '~' + emailfield.val();
		
		var slnofield = $("#"+'contactslno'+j);
		if(j == 1)
			contactarray = typefield.val() + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
		else
			contactarray = contactarray + '****' + typefield.val()  + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
	}
	//alert(cellvalues);return false;
	if(namevalues == '')
		{error.html(errormessage("Enter Atleast One Contact Person Name."));return false;}
	if(phonevalues == '')
		{error.html(errormessage("Enter Atleast One Phone Number."));return false;}
	if(cellvalues == '')
		{error.html(errormessage("Enter Atleast One Cell Number."));return false;}
	if(emailvalues == '')
		{error.html(errormessage("Enter Atleast One Email Id."));return false;}

	tabopen5('1','tabg1');
	var field = $('#place');
	if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
	var field = $('#state');
	if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
	var field = $('#district');
	if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
//	var field = $('#remarks');
//	if(!field.val()) { error.html(errormessage("Enter the Remarks. ")); field.focus(); return false; }
	var field = $('#pincode');
	if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
	if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
	var field = $('#stdcode');
	if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	var field = $('#dealer');
	if(!field.val()) { error.html(errormessage("Select the proper dealer name from the list.")); field.focus(); return false; }
	var field = $('#fax');
	if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
	var field = $('#website');
	if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
	var field = $('#region');
	if(!field.val()) { error.html(errormessage("Enter the Region.")); field.focus(); return false; }
	var field = $('#branch');
	if(!field.val()) { error.html(errormessage("Select the Branch.")); field.focus(); return false; }
	if($('#customerlist').val() == $('#customerlist2').val())
	{
		 error.html(errormessage("'From' and 'To' customers cannot be the same. Please select different customers.")); 
		 $('#detailsearchtext').focus(); return false; 
	}
	
	else
	{
		var value = "This will merge \'" + searchcustomerfrom + "\' to \'" + searchcustomerto   +"\'\n and deletes \'" + searchcustomerfrom +"\'. Are you sure continue?";
	//	alert(value);
		var confirmation = confirm (value);
		if (confirmation)
		{
			var passData = '';
			passData = "type=mergecustomer&customerfrom=" + encodeURIComponent($('#customerlist').val()) + "&customerto=" + encodeURIComponent($('#customerlist2').val()) + "&businessnamefrom=" + encodeURIComponent(searchcustomerfrom) + "&businessnameto=" + encodeURIComponent(searchcustomerto) + "&businessname=" + encodeURIComponent($('#businessname').val())  + "&contactperson=" + encodeURIComponent($('#contactperson').val()) + "&address=" + encodeURIComponent($('#address').val()) + "&place=" + encodeURIComponent($('#place').val()) + "&pincode=" + encodeURIComponent($('#pincode').val())+ "&remarks=" + encodeURIComponent($('#remarks').val()) + "&district=" + encodeURIComponent($('#district').val()) + "&region=" + encodeURIComponent($('#region').val()) + "&branch=" + encodeURIComponent($('#branch').val()) + "&categoryvalue=" + encodeURIComponent($('#category').val()) + "&typevalue=" + encodeURIComponent($('#type').val()) + "&stdcode=" + encodeURIComponent($('#stdcode').val()) + "&phone=" + encodeURIComponent($('#phone').val()) + "&fax=" + encodeURIComponent($('#fax').val()) + "&cell=" + encodeURIComponent($('#cell').val()) + "&emailid=" + encodeURIComponent($('#emailid').val()) + "&website=" + encodeURIComponent($('#website').val()) + "&currentdealer=" + encodeURIComponent($('#dealer').val()) + "&contactarray=" + encodeURIComponent(contactarray)+ "&deletearray=" + encodeURIComponent(deletearray) + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
			ajaxcall1 = createajax();//alert(passData);
			$('#form-error').html(getprocessingimage());
			//error.innerHTML = getprocessingimage();;
			var queryString = "../ajax/mergecustomer.php";
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
							if(response[0] == 1)
							{
								$('#form-error').html(successmessage(response[1]));
								clearform();
								$('#customer1').val('');
								$('#customer2').val(''); 
								gettotalcustomercount();
								gettotalcustomercount2();
							//	$('#displaydiv').hide();
								
							}
							else if(response[0] == '2')
							{
								error.html(errormessage(response[1]));
								clearform();
								$('#customer1').val(''); 
								$('#customer2').val(''); 
								gettotalcustomercount();
								gettotalcustomercount2();
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
			error.html('');
			$('#customerlist').val('');
			$('#customerlist2').val('');
			clearform();
			$('#displaydiv').hide();
		}
	}
}

function clearform()
{
	var form = $('#submitform');
	var error = $('#form-error');
	//error.html('');
	tabopen5('1','tabg1');
	$('#submitform')[0].reset();
	$('#customerlist').val('');
	$('#customerlist2').val('');
	$('#customer1').val('');
	$('#customer2').val('');
	//$('#displaydiv').hide();
	$('#m_customerid').html('') ;
	$('#m_businessname').html('') ;
	$('#m_address').html('');
	$('#m_place').html('');
	$('#m_district').html('');
	$('#m_state').html('');
	$('#m_pincode').html('');
	$('#m_remarks').html('');
	$('#m_stdcode').html('');
	$('#m_website').html('');
	$('#m_fax').html('');
	$('#m_category').html('');
	$('#m_type').html('');
	$('#m_region').html('');
	$('#m_branch').html('');
	$('#m_dealer').html('');
	var rowcount = $('#adddescriptionrows tr').length;
	for(j=1;j<=(rowcount/13);j++)
	{
		$("#"+'t_type'+(j)).html('');
		$("#"+'t_name'+(j)).html('');
		$("#"+'t_phone'+(j)).html('');
		$("#"+'t_cell'+(j)).html('');
		$("#"+'t_emailid'+(j)).html('');
		
		$("#"+'m_type'+(j)).html('');
		$("#"+'m_name'+(j)).html('');
		$("#"+'m_phone'+(j)).html('');
		$("#"+'m_cell'+(j)).html('');
		$("#"+'m_emailid'+(j)).html('');
	}
		$('#adddescriptionrows tr').remove();
		slno = 1;
		rowid = 'removedescriptionrow'+ slno;
		var value = 'contactname'+slno;
		
		var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select><input type="hidden" name="fromtype" id="fromtype" /><input type="hidden" name="totype" id="totype" /> <input type="hidden" name="fromtypename" id="fromtypename" /><input type="hidden" name="totypename" id="totypename" /></td></tr><tr><td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_name" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_phone" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left" id="m_cell" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left" id="m_emailid" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td>  </tr>';
		
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
	
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "type=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/mergecustomer.php";
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
				$("#totalcount1").html(response['count']);
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
	var form = $('#submitform');
	var passData = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/mergecustomer.php";
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
	
	queryString = "../ajax/mergecustomer.php";
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

	queryString = "../ajax/mergecustomer.php";
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
	
	queryString = "../ajax/mergecustomer.php";
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
		$("#customerselectionprocess").html('')
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
	$('#detailsearchtext',form).focus();
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


function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#lastslno').val(selectbox);
	customerdetailstoform(selectbox);
	//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration();     document.getElementById('delaerrep').disabled = true;
}

function selectacustomer(input)
{
	var selectbox = document.getElementById('customerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getcustomerlist1();
		$('#customer1').val('');
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
			if(pattern.test(customerarray[i].toLowerCase()))
			{
				var splits = customerarray[i].split("^");
				selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//customerdetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
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



function gettotalcustomercount2()
{
	var form = $('#customerselectionprocess2');
	var passData = "type=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/mergecustomer.php";
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
				$("#totalcount2").html(response['count']);
				refreshcustomerarray2(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess2").html(scripterror());
		}
	});	
}



function refreshcustomerarray2(customercount)
{
	var form = $('#customerselectionprocess2');
	var totalcustomercount = customercount;
	var limit = Math.round(totalcustomercount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#submitform');
	var passData = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "type=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess2').html(getprocessingimage());
	queryString = "../ajax/mergecustomer.php";
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
					customerarray12[i] = response[i];
				}
				process12 = true;
				compilecustomerarray2();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess2").html(scripterror());
		}
	});	
	
	queryString = "../ajax/mergecustomer.php";
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
					customerarray22[i] = response[i];
				}
				process22 = true;
				compilecustomerarray2();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess2").html(scripterror());
		}
	});	

	queryString = "../ajax/mergecustomer.php";
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
					customerarray32[i] = response[i];
				}
				process32 = true;
				compilecustomerarray2();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess2").html(scripterror());
		}
	});	
	
	queryString = "../ajax/mergecustomer.php";
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
					customerarray42[i] = response[i];
				}
				process42 = true;
				compilecustomerarray2();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess2").html(scripterror());
		}
	});	
				//compilecustomerarray2();

}

function compilecustomerarray2()
{
	if(process12 == true && process22 == true && process32 == true && process42 == true)
	{
		customerarray2 = customerarray12.concat(customerarray22.concat(customerarray32.concat(customerarray42)));
		flag = true;
		$("#customerselectionprocess2").html('')
		getcustomerlist2();
		
	}
	else
	return false;
}


function getcustomerlist2()
{	
	var form = $('#submitform');
	var selectbox = $('#customerlist2');
	var numberofcustomers = customerarray2.length;
	$('#detailsearchtext2').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');

	//selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray2[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}


function selectfromlist2()
{
	var selectbox = $("#customerlist2 option:selected").val();
	$('#detailsearchtext2').val($("#customerlist2 option:selected").text());
	$('#detailsearchtext2').select();
	$('#slno').val(selectbox);
	customerdetailstoform2(selectbox);
	//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); document.getElementById('delaerrep').disabled = true;
}

function selectacustomer2(input)
{
	var selectbox = $('#customerlist2');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getcustomerlist2();
		$('#customer2').val('');
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
			if(pattern.test(customerarray[i].toLowerCase()))
			{
				var splits = customerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//customerdetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
			}
		}
	}
}

function customersearch2(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcustomer2('up');
	else if(KeyID == 40)
		scrollcustomer2('down');
	else
	{
		var form =$('#submitform');
		var input = $('#detailsearchtext2').val();
		selectacustomer2(input);
	}
}

function scrollcustomer2(type)
{
	var selectbox = $('#customerlist2');
	var totalcus = $("#customerlist2 option").length;
	var selectedcus = $("select#customerlist2").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#customerlist2").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#customerlist2").attr('selectedIndex', selectedcus + 1);
	selectfromlist2()
}


function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		var form = document.submitform;
		var passData = "type=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		ajaxcall6 = createajax();
		//document.getElementById('displayprocessingimage').innerHTML = getprocessingimage();
		var queryString = "../ajax/mergecustomer.php";
		ajaxcall6.open("POST", queryString, true);
		ajaxcall6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall6.onreadystatechange = function()
		{
			if(ajaxcall6.readyState == 4)
			{
				if(ajaxcall6.status == 200)
				{
					var response = (ajaxcall6.responseText).split("^"); 
					if(response[0] == '1')
					{
						$('#frombusinessname').val(response[2]);
						$('#fromcontactperson').val(response[3]);
						$('#fromaddress').val(response[4]);
						$('#fromplace').val(response[5]);
						$('#fromdistrict').val(response[6]);
						$('#fromstate').val(response[7]);
						$('#frompincode').val(response[8]);
						$('#fromstdcode').val(response[9]);
						$('#fromphone').val(response[10]);
						$('#fromcell').val(response[11]);
						$('#fromemailid').val(response[12]);
						$('#fromwebsite').val( response[13]);
						$('#fromcategory').val(response[14]);
						$('#fromtype').val(response[15]);
						$('#fromregion').val( response[16]);
						$('#frombranch').val(response[17]);
						$('#fromdealer').val(response[18]);
						$('#fromdistrictname').val(response[19]);
						$('#fromstatename').val(response[20]);
						$('#frombranchname').val(response[21]);
						$('#fromcategoryname').val(response[22]);
						$('#fromtypename').val(response[23]);
						$('#fromregionname').val(response[24]);
						$('#fromdealername').val(response[25]);
						$('#fromfax').val(response[26]);
						$('#fromcustomerid').val(response[27]);
						$('#fromcontact').val(response[28]);
						$('#reffromcontact').val(response[29]);
						$('#customer1').val(response[27]);
						$('#fromremarks').val(response[30]);
						displaytoform();
					}
					else
					{
						$('#customerselectionprocess').html("No datas found to be displayed");
					}
				}
				else
				$('#customerselectionprocess').html(scripterror());
		 	}
		}
		ajaxcall6.send(passData);
	}
}


function customerdetailstoform2(cusid)
{
	if(cusid != '')
	{
		var form = $('#submitform');
		$('#submitform')[0].reset;
		var passData = "type=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		ajaxcall12 = createajax();
		var queryString = "../ajax/mergecustomer.php";
		ajaxcall12.open("POST", queryString, true);
		ajaxcall12.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall12.onreadystatechange = function()
		{
			if(ajaxcall12.readyState == 4)
			{
				if(ajaxcall12.status == 200)
				{
					var ajaxresponse = (ajaxcall12.responseText).split("^");//alert(response[6])
					if(ajaxresponse[0] == '1')
					{
						$('#tobusinessname').val(ajaxresponse[2]);
						$('#tocontactperson').val(ajaxresponse[3]);
						$('#toaddress').val(ajaxresponse[4]);
						$('#toplace').val(ajaxresponse[5]);
						$('#todistrict').val(ajaxresponse[6]);
						$('#tostate').val(ajaxresponse[7]);
						$('#topincode').val(ajaxresponse[8]);
						$('#tostdcode').val(ajaxresponse[9]);
						$('#tophone').val(ajaxresponse[10]);
						$('#tocell').val(ajaxresponse[11]);
						$('#toemailid').val(ajaxresponse[12]);
						$('#towebsite').val(ajaxresponse[13]);
						$('#tocategory').val(ajaxresponse[14]);
						$('#totype').val(ajaxresponse[15]);
						$('#toregion').val(ajaxresponse[16]);
						$('#tobranch').val(ajaxresponse[17]);
						$('#todealer').val(ajaxresponse[18]);
						$('#todistrictname').val(ajaxresponse[19]);
						$('#tostatename').val(ajaxresponse[20]);
						$('#tobranchname').val(ajaxresponse[21]);
						$('#tocategoryname').val(ajaxresponse[22]);
						$('#totypename').val(ajaxresponse[23]);
						$('#toregionname').val(ajaxresponse[24]);
						$('#todealername').val(ajaxresponse[25]);
						$('#tofax').val(ajaxresponse[26]);
						$('#tocustomerid').val(ajaxresponse[27]);
						$('#tocontact').val(ajaxresponse[28]);
						$('#reftocontact').val(ajaxresponse[29]);
						$('#customer2').val(ajaxresponse[27]);
						$('#toremarks').val(ajaxresponse[30]);
						displaytoform();
					}
					else
					{
						$('#customerselectionprocess').html("No datas found to be displayed");
					}
					
				}
				else
				$('#customerselectionprocess').html(scripterror());
		 	}
		}
		ajaxcall12.send(passData);
	}
}


function validateduplicate(val1,val2) 
{
	if(val1 != val2)
	{
		if(val1 != '')
		{
			if(val2 != '')
			{
				val1 = val1 + ',' + val2;
			}
			else
			{
				val1 = val1;
			}
		}
		else
		{
			val1 = val2;
		}
	}
	else
	{
		val1 = val2;
	}
return val1;
}

function displaytoform()
{
	var form = $('#submitform');
	var error = $('#form-error');
	error.html('');
	if(($('#customer1').val() != '') && ($('#customer2').val() != '' ))
	{
		$('#displayprocessingimage').html(getprocessingimage());
		tabopen5('1','tabg1');
		$('#displaydiv').show();
		var district1 = $('#todistrict').val();
		var district2 = $('#fromdistrict').val();
		$('#displayprocessingimage').html('');
		$('#customerid').val($('#tocustomerid').val());
		$('#businessname').val($('#tobusinessname').val());
		
		if($('#toaddress').val() != '')
		{
			$('#address').val($('#toaddress').val());
		}
		else
		{
			$('#address').val($('#fromaddress').val());
		}
		if($('#toplace').val() != '')
		{
			$('#place').val($('#toplace').val());
		}
		else
		{
			$('#place').val($('#fromplace').val());
		}
		if($('#tostate').val() != '')
		{
			$('#state').val($('#tostate').val());
		}
		else
		{
			$('#state').val( $('#fromstate').val());
		}
		var statevalue = $('#state').val();
		getdistrict('districtcodedisplay',statevalue);
		if(district1 != '')
		{
			$('#district').val(district1);
		}
		else
		{
			$('#district').val(district2);
		}
		
		if($('#topincode').val() != '')
		{
			$('#pincode').val( $('#topincode').val());
		}
		else
		{
			$('#pincode').val( $('#frompincode').val());
		}
		
		if($('#toremarks').val() != '')
		{
			$('#remarks').val( $('#toremarks').val());
		}
		else
		{
			$('#remarks').val( $('#fromremarks').val());
		}
		if($('#tostdcode').val() != '')
		{
			$('#stdcode').val( $('#tostdcode').val());
		}
		else
		{
			$('#stdcode').val( $('#fromstdcode').val());
		}
		
		$('#fax').val(validateduplicate($('#tofax').val(),$('#fromfax').val()));
		$('#website').val(validateduplicate($('#towebsite').val(),$('#fromwebsite').val()));
		if($('#tocategory').val() != '')
		{
			$('#category').val( $('#tocategory').val());
		}
		else
		{
			$('#category').val( $('#fromcategory').val());
		}
		if($('#totype').val() != '')
		{
			$('#type').val( $('#totype').val());
		}
		else
		{
			$('#type').val( $('#fromtype').val());
		}
		if($('#toregion').val() != '')
		{
			$('#region').val( $('#toregion').val());
		}
		else
		{
			$('#region').val( $('#fromregion').val());
		}
		if($('#tobranch').val() != '')
		{
			$('#branch').val( $('#tobranch').val());
		}
		else
		{
			$('#branch').val( $('#frombranch').val());
		}
		if($('#todealer').val() != '')
		{
			$('#dealer').val( $('#todealer').val());
		}
		else
		{
			$('#dealer').val( $('#fromdealer').val());
		}
		contactdetails();
		$('#m_customerid').html(displayformat($('#tocustomerid').val(),$('#fromcustomerid').val()) ) ;
		$('#m_businessname').html(displayformat($('#tobusinessname').val(),$('#frombusinessname').val()) ) ;
		$('#m_contactperson').html(displayformat($('#tocontactperson').val(),$('#fromcontactperson').val()));
		$('#m_address').html(displayformat($('#toaddress').val(),$('#fromaddress').val()));
		$('#m_place').html(displayformat($('#toplace').val(),$('#fromplace').val()));
		$('#m_district').html(displayformat($('#todistrictname').val(),$('#fromdistrictname').val()));
		$('#m_state').html(displayformat($('#tostatename').val(),$('#fromstatename').val()));
		$('#m_pincode').html(displayformat($('#topincode').val(),$('#frompincode').val()));
		$('#m_remarks').html(displayformat($('#toremarks').val(),$('#fromremarks').val()));
		$('#m_stdcode').html(displayformat($('#tostdcode').val(),$('#fromstdcode').val()));
		$('#m_phone').html(displayformat($('#tophone').val(),$('#fromphone').val()));
		$('#m_cell').html(displayformat($('#tocell').val(),$('#fromcell').val() ));
		$('#m_fax').html(displayformat($('#tofax').val(),$('#fromfax').val() ));
		$('#m_emailid').html(displayformat($('#toemailid').val(),$('#fromemailid').val()));
		$('#m_website').html(displayformat($('#towebsite').val(),$('#fromwebsite').val()));
		$('#m_category').html(displayformat($('#tocategoryname').val(),$('#fromcategoryname').val()));
		$('#m_type').html(displayformat($('#totypename').val(),$('#fromtypename').val()));
		$('#m_region').html(displayformat($('#toregionname').val(),$('#fromregionname').val()));
		$('#m_branch').html(displayformat($('#tobranchname').val(),$('#frombranchname').val()));
		$('#m_dealer').html(displayformat($('#todealername').val(),$('#fromdealername').val()));
	}
	else
	{
		$('#displaydiv').hide();
	}
}

function displayformat(char1,char2)
{
	if(char1 != '' && char2 != '')
	{
		char1 = '<font color="#009F00">' + char1 + '</font>' + ', ' + '<font color="#FF0000">' + char2 + '</font>';
	}
	else if(char1 != '')
	{
		char1 = '<font color="#009F00">' + char1 + '</font>' ;
	}
	else if(char2 != '')
	{
		char1 = '<font color="#FF0000">' + char2 + '</font>' ;
	}
return char1;
}




//To add description rows
function adddescriptionrows()
{
	$("#form-error").html('');
	var rowcount = ($('#adddescriptionrows tr').length);
	if(rowcount == 13)
	slno = (rowcount/13) + 1;
	else
	slno = (rowcount/13) + 1;
	rowid = 'removedescriptionrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select><input type="hidden" name="fromtype" id="fromtype" /><input type="hidden" name="totype" id="totype" /> <input type="hidden" name="fromtypename" id="fromtypename" /><input type="hidden" name="totypename" id="totypename" /></td></tr><tr><td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_name" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_phone" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left" id="m_cell" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left" id="m_emailid" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td>  </tr>';
	
	$("#adddescriptionrows").append(row);
	$('#'+value).html(slno);
	if(slno == 10)
		$('#adddescriptionrowdiv').hide();
	else 
		$('#adddescriptionrowdiv').show();
}

//Remove description row
function removedescriptionrows(rowid,rowslno)
{
	if(deletearray == '')
		deletearray = $('#contactslno'+rowslno).val();
	else
		deletearray = deletearray  + ',' + $('#contactslno'+rowslno).val();
	var error = $("#form-error");
	
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 13)
	{
		error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
		return false;
	}
	else
	{
		$('#'+rowid).remove();
		var countval = 0;
		for(i=1;i<=(rowcount/13);i++)
		{
			if(((rowcount/13)-1) == 10)
				$('#adddescriptionrowdiv').hide();	
			else if(((rowcount/13)-1) < 10)
				$('#adddescriptionrowdiv').show();	
			else
				$('#adddescriptionrowdiv').hide();
			var selectiontype = '#selectiontype'+i;
			var designationtype = '#designationtype'+i;
			var name = '#name'+i;
			var phone = '#phone'+i;
			var cell = '#cell'+i;
			var emailid = '#emailid'+i;
			var removedescriptionrow = '#removedescriptionrow'+i;
			var contactslno =  '#contactslno'+i;
			var removerowdiv = '#removerowdiv'+i;
			if($(removedescriptionrow).length > 0)
			{
				countval++;
				$("#selectiontype"+ i).attr("name","selectiontype"+ countval);
				$("#selectiontype"+ i).attr("id","selectiontype"+ countval);
				
				$("#name"+ i).attr("name","name"+ countval);
				$("#name"+ i).attr("id","name"+ countval);
				
				$("#phone"+ i).attr("name","phone"+ countval);
				$("#phone"+ i).attr("id","phone"+ countval);
				
				$("#cell"+ i).attr("name","cell"+ countval);
				$("#cell"+ i).attr("id","cell"+ countval);
				
				$("#emailid"+ i).attr("name","emailid"+ countval);
				$("#emailid"+ i).attr("id","emailid"+ countval);
				
				$("#removedescriptionrow"+ i).attr("name","removedescriptionrow"+ countval);
				$("#removedescriptionrow"+ i).attr("id","removedescriptionrow"+ countval);
				
				$("#contactslno"+ i).attr("name","contactslno"+ countval);
				$("#contactslno"+ i).attr("id","contactslno"+ countval);
				
				$("#contactname"+ i).attr("id","contactname"+ countval);
				$("#contactname"+ countval).html(countval);
				
				$("#removerowdiv"+ i).attr("id","removerowdiv"+ countval);
				document.getElementById("removerowdiv"+ countval).onclick = new Function('removedescriptionrows("removedescriptionrow' + countval + '" ,"' + countval + '")') ;
						
			}
		}
	}
}


function contactdetails()
{
	var countrow = $('#tocontact').val();
	var rowcount = $('#fromcontact').val();

	totalcustomer = countrow + "****" + rowcount;
	splitcustomers = totalcustomer.split("****");
	valuespartarray = new Array();
	slnopartarray = new Array();
	for(i=0; i < splitcustomers.length; i++)
	{
		splitvalues = splitcustomers[i].split("#");
		
		part1 = splitvalues[0] + "#" + splitvalues[1] + "#" + splitvalues[2] + "#" + splitvalues[3]+ "#" + splitvalues[4]; 
		part2 = splitvalues[5];	
		valuespartarray[i] = part1;
		slnopartarray[i] = part2;
	}
	finalarrayvalue = new Array();
	finalarrayslno = new Array();
	for(i=0; i < splitcustomers.length; i++)
	{
		if(finalarrayvalue.length > 0 && inArray(valuespartarray[i],finalarrayvalue))
		{
			nextelement = deletearray.length;
			deletearray[nextelement] = slnopartarray[i];
			
		}
		else
		{
			nextelement = finalarrayvalue.length;
			finalarrayvalue[nextelement] = valuespartarray[i];
			finalarrayslno[nextelement] = slnopartarray[i];
		}
	}
	finalstring = "";deletestring = "";
	for(i=0; i < finalarrayvalue.length; i++)
	{
		finalstring = finalstring + finalarrayvalue[i] + "#" + finalarrayslno[i];
		if(i < finalarrayvalue.length - 1)
			finalstring = finalstring + "*";
		
	}
	
	var totalrow = finalstring.split('*');
	$('#adddescriptionrows tr').remove();
	for(k=1;k<=totalrow.length;k++)
	{
		slno = k;
		rowid = 'removedescriptionrow'+ slno;
		

			var value = 'contactname'+slno;
			if(k == 10)
				$('#adddescriptionrowdiv').hide();
			else if(k < 10)
				$('#adddescriptionrowdiv').show();
			else 
				$('#adddescriptionrowdiv').hide();
		var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select><input type="hidden" name="fromtype" id="fromtype" /><input type="hidden" name="totype" id="totype" /></td></tr><tr><td  style="font-weight:bold" height="15px" align="left"><span id="m_type'+ slno+'"></span ><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" style="font-weight:bold" height="15px"><span id="m_name'+ slno+'"></span><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_phone'+ slno+'"></span><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_cell'+ slno+'"></span><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_emailid'+ slno+'"></span><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td>  </tr>';
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
		
	}
	
	splitvalue = new Array();
	
	for(var i=0;i<totalrow.length;i++)
	{
		splitvalue[i] =  totalrow[i].split('#');
		$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
		$("#"+'name'+(i+1)).val(splitvalue[i][1]);
		$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
		$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
		$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
		$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
	}
	var countrow = $('#tocontact').val().split('****');
	var rowcount = $('#fromcontact').val().split('****');
	var countrowvalues = new Array();
	var rowcountvalue = new Array();
	
	if($('#customer1').val() != $('#customer2').val())
	{
		for(j=1;j<=totalrow.length;j++)
		{
			for(i=0;i<countrow.length;i++)
			{
				countrowvalues[i] = countrow[i].split('#');
				if($('#contactslno'+ (j)).val() == countrowvalues[i][5])
				{
					$("#"+'m_type'+(j)).html('<font color="#009F00">' + countrowvalues[i][0] + '</font>');
					$("#"+'m_name'+(j)).html('<font color="#009F00">' + countrowvalues[i][1] + '</font>');
					$("#"+'m_phone'+(j)).html('<font color="#009F00">' + countrowvalues[i][2] + '</font>');
					$("#"+'m_cell'+(j)).html('<font color="#009F00">' +  countrowvalues[i][3] + '</font>');
					$("#"+'m_emailid'+(j)).html('<font color="#009F00">' + countrowvalues[i][4] + '</font>');
				}
			}
		}
		
		for(j=1;j<=totalrow.length;j++)
		{
			for(i=0;i<rowcount.length;i++)
			{
				rowcountvalue[i] = rowcount[i].split('#');
				if($('#contactslno'+ (j)).val() == rowcountvalue[i][5])
				{
					$("#"+'t_type'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][0] + '</font>');
					$("#"+'t_name'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][1] + '</font>');
					$("#"+'t_phone'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][2] + '</font>');
					$("#"+'t_cell'+(j)).html('<font color="#FF0000">' +  rowcountvalue[i][3] + '</font>');
					$("#"+'t_emailid'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][4] + '</font>');
				}
			}
			
		}
	
	}
	else if($('#customer1').val()== $('#customer2').val())
	{
		for(j=1;j<=totalrow.length;j++)
		{
			for(i=0;i<countrow.length;i++)
			{
				countrowvalues[i] = countrow[i].split('#');
				if($('#contactslno'+ (j)).val() == countrowvalues[i][5])
				{
					$("#"+'m_type'+(j)).html('<font color="#009F00">' + countrowvalues[i][0] + '</font><br/>');
					$("#"+'m_name'+(j)).html('<font color="#009F00">' + countrowvalues[i][1] + '</font><br/>');
					$("#"+'m_phone'+(j)).html('<font color="#009F00">' + countrowvalues[i][2] + '</font><br/>');
					$("#"+'m_cell'+(j)).html('<font color="#009F00">' +  countrowvalues[i][3] + '</font><br/>');
					$("#"+'m_emailid'+(j)).html('<font color="#009F00">' + countrowvalues[i][4] + '</font><br/>');
				}
			}
		}
		
		for(j=1;j<=totalrow.length;j++)
		{
			for(i=0;i<rowcount.length;i++)
			{
				rowcountvalue[i] = rowcount[i].split('#');
				if($('#contactslno'+ (j)).val() == rowcountvalue[i][5])
				{
					$("#"+'t_type'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][0] + '</font>');
					$("#"+'t_name'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][1] + '</font>');
					$("#"+'t_phone'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][2] + '</font>');
					$("#"+'t_cell'+(j)).html('<font color="#FF0000">' +  rowcountvalue[i][3] + '</font>');
					$("#"+'t_emailid'+(j)).html('<font color="#FF0000">' + rowcountvalue[i][4] + '</font>');
				}
			}
			
		}
	}
	
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

