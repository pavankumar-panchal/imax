var customerarray = new Array();
var totalarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var regcardarray = new Array();
var new_regcardarray = new Array();
var autoregcardarray = new Array();
var new_autoregcardarray = new Array();

var contactarray = '';

function formsubmit(command)
{
	$('#save').removeClass('button_enter1');
	var passData = "";
	var form = $("#submitform" );
	var error = $("#form-error" );
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';
	if(command == 'save')
	{
		tabopen5('1','tabg1');
		var field = $("#businessname" );
		if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
		var rowcount = $('#adddescriptionrows tr').length;
		tabopen5('2','tabg1');
		var l=1;
		while(l<=rowcount)
		{
			if(!$("#selectiontype1").val() && !$("#phone1").val() && !$("#cell1").val() && !$("#emailid1").val()&& !$("#name1").val())
			{
					error.html(errormessage("Minimum of ONE contact detail is mandatory")); return false;
			}
			else
			var field = $("#"+'selectiontype'+l);
			if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
			var field = $("#"+'phone'+l);
			if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
			var field = $("#"+'cell'+l);
			if(field.val()) { if(!cellvalidation(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
			var field = $("#"+'emailid'+l);
			if(field.val()) { if(!checkemail(field.val())) { error.html(errormessage('Enter the valid Email Id.')); field.focus(); return false; } }
			var field = $("#"+'name'+l);
			if(field.val()) { if(!contactpersonvalidate(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Numeric / space.')); field.focus(); return false; } }
			l++;
			
		}
		for(j=1;j<=rowcount;j++)
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
		
		if(namevalues == '')
			{error.html(errormessage("Enter Atleast One Contact Person Name."));return false;}
		if(phonevalues == '')
			{error.html(errormessage("Enter Atleast One Phone Number."));return false;}
		if(cellvalues == '')
			{error.html(errormessage("Enter Atleast One Cell Number."));return false;}
		if(emailvalues == '')
			{error.html(errormessage("Enter Atleast One Email Id."));return false;}

		tabopen5('1','tabg1');
		var field = $("#place" );
		if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
		var field = $("#state" );
		if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
		var field = $("#district" );
		if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
		var field = $("#pincode" );
		if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
		if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
		var field = $("#region");
		if(!field.val()) { error.html(errormessage("Enter the Region.")); field.focus(); return false; }
		var field = $("#branch");
		if(!field.val()) { error.html(errormessage("Select the Branch.")); field.focus(); return false; }
		var field = $("#currentdealer");
		if(!field.val()) { error.html(errormessage("Select the proper dealer name from the list.")); field.focus(); return false; }
		var field = $("#stdcode");
		if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
		var field = $("#fax");
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
		//Website validation - Rashmi -18/11/09
		var field = $("#website");
		if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
		var field = $('#disablelogin:checked').val();
		if(field != 'on') var disablelogin = 'no'; else disablelogin = 'yes';
		var field = $('#corporateorder:checked').val();
		if(field != 'on') var corporateorder = 'no'; else corporateorder = 'yes';
		var field = $('#companyclosed:checked').val();
		if(field != 'on') var companyclosed = 'no'; else companyclosed = 'yes';
		var field = $('#isdealer:checked').val();
		if(field != 'on') var isdealer = 'no'; else isdealer = 'yes';
		var field = $('#displayinwebsite:checked').val();
		if(field != 'on') var displayinwebsite = 'no'; else displayinwebsite = 'yes';
		var field = $('#promotionalsms:checked').val();
		if(field != 'on') var promotionalsms = 'no'; else promotionalsms = 'yes';
		var field = $('#promotionalemail:checked').val();
		if(field != 'on') var promotionalemail = 'no'; else promotionalemail = 'yes';

		
		passData =  "switchtype=save&businessname=" + encodeURIComponent($("#businessname").val()) + "&customerid=" + encodeURIComponent($("#customerid").val())  + "&address=" + encodeURIComponent($("#address").val()) + "&place=" + encodeURIComponent($("#place").val()) + "&pincode=" + encodeURIComponent($("#pincode").val()) + "&district=" + encodeURIComponent($("#district").val()) + "&region=" + encodeURIComponent($("#region").val()) + "&branch=" + encodeURIComponent($("#branch").val()) + "&category=" + encodeURIComponent($("#category").val()) + "&type=" + encodeURIComponent($("#type").val()) + "&stdcode=" + encodeURIComponent($("#stdcode").val()) + "&fax=" + encodeURIComponent($("#fax").val())  + "&website=" + encodeURIComponent($("#website").val()) + "&remarks=" + encodeURIComponent($("#remarks").val())   + "&currentdealer=" + encodeURIComponent($("#currentdealer").val()) + "&password=" + encodeURIComponent($("#password").val()) + "&createddate=" + encodeURIComponent($("#createddate").html()) + "&disablelogin=" + encodeURIComponent(disablelogin) + "&corporateorder=" + encodeURIComponent(corporateorder) + "&companyclosed=" + encodeURIComponent(companyclosed) + "&lastslno=" + encodeURIComponent($("#lastslno").val())+ "&isdealer=" + encodeURIComponent(isdealer)+ "&contactarray=" + encodeURIComponent(contactarray)+ "&totalarray=" + encodeURIComponent(totalarray)+ "&displayinwebsite=" + encodeURIComponent(displayinwebsite)+ "&promotionalsms=" + encodeURIComponent(promotionalsms)+ "&promotionalemail=" + encodeURIComponent(promotionalemail) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		}
		else if(command == 'delete')
		{
			var confirmation = confirm("Are you sure you want to delete the selected customer?");
			if(confirmation)
			{
				passData =  "switchtype=delete&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			}
			else
			return false;
		}
		queryString = '../ajax/customer.php';
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
					tabopen5('1','tabg1');
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errormessage']));
						gettotalcustomercount();
						newentry();
						rowwdelete();
					}
					else if(response['errorcode'] == '4')
					{
						
						error.html(successmessage(response['errormessage']));
						gettotalcustomercount();
						newentry();
						rowwdelete();
					}
					else if(response['errorcode'] == '2')
					{
						error.html(successmessage(response['errormessage']));
						gettotalcustomercount();
						newentry();
						rowwdelete();
					}
					else if(response['errorcode'] == '3')
					{
						error.html(errormessage(response['errormessage']));
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
	queryString = "../ajax/customer.php";
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
	queryString = "../ajax/customer.php";
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
	
	queryString = "../ajax/customer.php";
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

	queryString = "../ajax/customer.php";
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
	
	queryString = "../ajax/customer.php";
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

function searchcustomerarray(callstatus)
{
	var form = $("#searchfilterform");
	var form = $("#submitform");
	var error = $("#filter-form-error");
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); return false;	}
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent($("#region2").val())+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent($("#branch2").val())+"&type2=" +encodeURIComponent($("#type2").val()) + "&category2=" + encodeURIComponent($("#category2").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
		$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/customer.php";
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
					var response = ajaxresponse;
					if(response == '')
						{
							$('#filterdiv').show();
							customersearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customersearcharray[i] = response[i];
							}
							
							getcustomerlistonsearch();
							$("#customerselectionprocess").html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '))
							$("#totalcount").html('0');
							error.html(errormessage('No datas found to be displayed.')); 
						}
						else
						{
							$('#filterdiv').hide();//alert(response);
							customersearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customersearcharray[i] = response[i];
							}
							flag = false;
							getcustomerlistonsearch();
							$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="displayalcustomer()"></span> '));
							$("#totalcount").html(customersearcharray.length);
							$("#filter-form-error").html();

						}
				}
			}, 
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
			}
		});	
}

function getcustomerlistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customersearcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function getcustomerlist1()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1");
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
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



function displayalcustomer()
{	
	var form = $("#submitform" );
	flag = true;
	var selectbox = $('#customerlist');
	$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	var numberofcustomers = customerarray.length;
	$('#detailsearchtext').focus();
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("css_enter1"); 
	$('input.focus_redclass,select.focus_redclass,textarea.focus_redclass').removeClass("checkbox_enter1");
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	$('#totalcount').html(customerarray.length);
}

//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#filter-form-error").html('');
	for (i=0; i<elements.length; i++) 
	{
		field_type = elements[i].type.toLowerCase();
	}
	
	switch(field_type)
	{
	
		case "text": 
			elements[i].value = ""; 
			break;
		case "radio":
			if(elements[i].checked == 'databasefield1')
			{
				elements[i].checked = true;
			}
			else
			{
				elements[i].checked = false; 
			}
			break;
		case "checkbox":
  			if (elements[i].checked) 
			{
   				elements[i].checked = true; 
			}
			break;
		case "select-one":
		{
  			 for (var k=0, l=oForm.elements[i].options.length; k<l; k++)
			 {
				 oForm.elements[i].options[k].selected = oForm.elements[i].options[k].defaultSelected;
			 }
				
		}
		break;

		default:$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}


function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productarray[]']");
	if(showtype == 'one')
	{
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}
			if( $('#selectproduct').val() == 'ALL')
				$(chkvalues[i]).attr('checked',true);
			else if(selectproduct.value == 'NONE')
				$(chkvalues[i]).attr('checked',true);
			else if(chkvalues[i].getAttribute('producttype') == $('#selectproduct').val())
			{
				$(chkvalues[i]).attr('checked',true);
				$('#groupvalue').val('');
			}
		}
	}else if(showtype == 'more')
	{

		var addproductvalue = $("#selectproduct option:selected").val();
		if($('#groupvalue').val() == '')
			$('#groupvalue').val($('#groupvalue').val() +  addproductvalue);
		else
			$('#groupvalue').val($('#groupvalue').val() + '%' +  addproductvalue);
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}

			var var1 = $('#groupvalue').val().split('%');
			for( var j=0; j<var1.length; j++)
			{
				if($('#selectproduct').val() == 'ALL')
					$(chkvalues[i]).attr('checked',true);
				else if($('#selectproduct').val() == 'NONE')
					$(chkvalues[i]).attr('checked',false);
				if(chkvalues[i].getAttribute('producttype') == var1[j])
				{
					$(chkvalues[i]).attr('checked',true);
				}
			}
		}
	}
}


function newentry()
{
	var form = $("#submitform");
	totalarray = '';
	tabopen5('1','tabg1');
	$("#submitform" )[0].reset();
	$("#lastslno" ).val('');
	enablesave();
	disabledelete();
	disableregistration();
	$('#displaypassworddfield').hide();
	$('#resetpwd').hide();
	$("#activecustomer" ).html('Not Available');
	$("#createddate" ).html('Not Available');
	$("#districtcodedisplay" ).html('<select name="district" class="swiftselect-mandatory type_enter focus_redclass" id="district" style="width:200px;"><option value="">Select A State First</option></select>');
	gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
	gridtabcus4('5','tabgroupgrid','&nbsp; &nbsp;Current Auto Registrations');
	$('#tabgroupgridc3').hide();
	if($('#resendmail'))
		$('#resendmail').hide();
	clearregistrationform();
	clearcarddetails();
	$("#salessummary").html('<table width="100%" border="0" cellspacing="0" cellpadding="4" class="table-border-grid"> <tr class="tr-grid-header"> <td width="22%" class="td-border-grid">&nbsp;</td><td width="24%" class="td-border-grid" align="center"><strong>Bill</strong></td><td width="25%" class="td-border-grid" align="center"><strong>PIN</strong></td> <td width="29%" class="td-border-grid" align="center"><strong>Regn</strong></td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>XBRL</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#edf4ff"><strong>TDS</strong></td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#F7FAFF"><strong>SVI</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid"  bgcolor="#edf4ff"><strong>SVH</strong></td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid"  bgcolor="#edf4ff">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>STO</strong></td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td><td class="td-border-grid" bgcolor="#F7FAFF">&nbsp;</td></tr><tr><td class="td-border-grid" bgcolor="#F7FAFF"><strong>SPP</strong></td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td><td class="td-border-grid" bgcolor="#edf4ff">&nbsp;</td></tr></table>');
}
function rowwdelete()
{
	totalarray = '';
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount <=10)
	{
		slno =1;
		$('#adddescriptionrows tr').remove();
		rowid = 'removedescriptionrow'+ slno;
		var value = 'contactname'+slno;
		var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
		$("#adddescriptionrows").append(row);
		findlasttd();
		$('#'+value).html(slno);
	}
		
}


function generatecustomerregistration(id,startlimit)
{
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	$("#tabgroupgridc1_1").html(getprocessingimage());
	$("#tabgroupgridc1link").html('');
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
				$("#tabgroupgridc1_1").html('');
				if(response[0] == 1)
				{
					gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
					$("#tabgroupgridc1_1").html(response[1]);
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					$("#tabgroupgridc1link").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html('No datas found to be displayed...');
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

function generatecustomerautoregistration(id,startlimit)
{
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passData = "switchtype=customerautoregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	$("#tabgroupgridc5_5").html(getprocessingimage());
	$("#tabgroupgridc5link").html('');
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
				$("#tabgroupgridc5_5").html('');
				if(response[0] == 1)
				{
					gridtabcus5('5','tabgroupgrid','&nbsp; &nbsp;Current Auto Registrations');
					$("#tabgroupgridc5_5").html(response[1]);
					$("#tabgroupgridwb5").html("Total Count :  " + response[2]);
					$("#tabgroupgridc5link").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc5_5").html('No datas found to be displayed...');
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc5_5").html(scripterror());
		}
	});	
}


//Function for "show more records" link - to get registration records
function getmorecustomerregistration(id,startlimit,slno,showtype)
{
	var form = $("#submitform" );
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	$("#tabgroupgridc1link").html(getprocessingimage());
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
				if(response[0] == '1')
				{
					$("#regresultgrid").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#regresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridc1link").html(response[3]);
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					
					gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
				}
				else
				{
					$("#tabgroupgridc1_1").html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


//Function for "show more records" link - to get registration records
function getmorecustomerautoregistration(id,startlimit,slno,showtype)
{
	var form = $("#submitform" );
	$('#lastslno').val(id);	
	var passData = "switchtype=customerautoregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	$("#tabgroupgridc5link").html(getprocessingimage());
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
				if(response[0] == '1')
				{
					$("#regresultgrid").html($("#tabgroupgridc5_5").html());
					$("#tabgroupgridc5_5").html($("#regresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridc5link").html(response[3]);
					$("#tabgroupgridwb5").html("Total Count :  " + response[2]);
					
					gridtabcus5(5,'tabgroupgrid','&nbsp; &nbsp;Current Auto Registrations');
				}
				else
				{
					$("#tabgroupgridc5_5").html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc5_5").html(scripterror());
		}
	});	
}


function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		totalarray = '';
		$("#tabgroupgridc1_1").html('');
		$("#tabgroupgridc5_5").html('');
		tabopen5('1','tabg1');
		var form = $("#submitform" );
		$("#submitform" )[0].reset();
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$("#form-error").html(getprocessingimage())
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
					$("#form-error").html('');
					$("#searchcustomerid").val('');
					var response = ajaxresponse;
					if(response['cusslno'] == '')
					{
						
						alert('Customer Not Available.');
						$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory type_enter focus_redclass" id="district"><option value="">Select A State First</option></select>');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						$("#tabgroupgridc5_5").html('No datas found to be displayed.');
						gridtabcus4('5','tabgroupgrid','&nbsp; &nbsp;Current Auto Registrations');
						$("#tabgroupgridc5_5").html('No datas found to be displayed.');
						clearregistrationform();
					} 
					generatecustomerautoregistration(response['cusslno'],'');
					generatecustomerregistration(response['cusslno'],'');//alert(response[40])
					$("#lastslno").val(response['cusslno']);
					generatecustomerattachcards(response['cusslno']);
					rereg_refreshcuscardarray(response['cusslno']);
					if($("#resendmail")) 
						$('#resendmail').show();
					
					if(response['p_editcustomercontact'] != 'yes') 
					{ 
						disablenew();disablesave(); disabledelete(); 
					}
					else 
					{ 
						enablenew();enablesave(); enabledelete(); 
					}
					
					if($("#registrationfieldradio0"))
					{
						$('#registrationfieldradio0').is(':checked');
						validatemakearegistration();
					}
					$("#customerid").val(response['customerid']);
					$("#businessname").val(response['businessname']);
					$("#short_url").html(response['rescontact'] +"\r\n"+ response['businessname']+"\r\n"+ response['address']+"\n"+ response['place']+"\n"+ response['districtname']+"\n"+ response['statename']+"\n" + response['pincode']+"\r\n"+ response['resphone']+"\n"+ response['rescell']+"\n"+ response['resemailid']);
					$("#address").val(response['address']);
					$("#place").val(response['place']);
					$("#state").val(response['state']);
					getdistrict('districtcodedisplay', response['state']);
					$("#district").val(response['district']);
					$("#pincode").val(response['pincode']);
					$("#region").val(response['region']);
					$("#stdcode").val(response['stdcode']);
					$("#website").val(response['website']);
					$("#category").val(response['category']);
					$("#type").val(response['type']);
					$("#remarks").val(response['remarks']);
					$("#salessummary").html(response['grid']);
					if(response['passwordchanged'] == '')
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
						$('#displaypassworddfield').hide();
					}
					else
					if(response['passwordchanged'] == 'n')
					{
						$('#initialpassworddfield').show();
						$("#initialpassword").val(response['password']);
						$('#displayresetpwd').hide();
					}
					else if(response['passwordchanged'] == 'y')
					{
						if(response['p_editcustomerpassword'] == 'yes')
						{
							$('#displayresetpwd').show();
						}
						else
						{
							$('#displayresetpwd').hide();
						}
						$("#resetpassword").val(response['password']);
						$('#initialpassworddfield').hide();
					}
					autochecknew($("#disablelogin"),response['disablelogin']);
					autochecknew($("#corporateorder"),response['corporateorder']);
					autochecknew($("#isdealer"),response['isdealer']);
					autochecknew($("#displayinwebsite"),response['displayinwebsite']);
					autochecknew($("#promotionalsms"),response['promotionalsms']);
					autochecknew($("#promotionalemail"),response['promotionalemail']);
					$("#currentdealer").val(response['currentdealer']);
					$("#fax").val(response['fax']);
					$("#activecustomer").html(response['activecustomer']);
					autochecknew($("#companyclosed"),response['companyclosed']);
					$("#branch").val(response['branch']);
					$("#createddate").html(response['createddate']);
					
					$('#tabgroupgridc4').hide();
					$('#tabgroupgridc8').hide();
					if(response['p_editcustomercontact'] == 'yes')
					{ 
						generatecustomerautoregistration(response['cusslno'],'');  
						generatecustomerregistration(response['cusslno'],'');
						enableregistration();
					}
					if(response['p_registration'] == 'yes') { generatecustomerautoregistration(response['cusslno'],''); generatecustomerregistration(response['cusslno'],''); 	enableregistration(); }
					if(response['userid'] == '1') { enabledelete(); }
					if(response['p_editcustomerpassword'] == 'yes' && response['customerid'] != '' )
					{
						$('#displaypassworddfield').show();
						$('#resetpwd').hide();
					}
					else
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
					}
					
					var countrow = response['contactarray'].split('****');
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).addClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).addClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).addClass("button_enter1"); 
						});
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).removeClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).removeClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).removeClass("button_enter1"); 
						});

						$('#'+value).html(slno);
						
					}
						
					findlasttd();

					splitvalue = new Array();
					for(var i=0;i<countrow.length;i++)
					{
						splitvalue[i] =  countrow[i].split('#');
						$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
						$("#"+'name'+(i+1)).val(splitvalue[i][1]);
						$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
						$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
						$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
						$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
					}
					
				}
			}, 
			error: function(a,b)
			{
				$("#customerselectionprocess").html(scripterror());
			}
		});	
	}
}

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#custslno').val(selectbox);
	$('#detailsearchtext').select();
	$('#filterdiv').hide();
	$('#tabgroupgridwb1').html('');
	customerdetailstoform(selectbox);	
	$('#hiddenregistrationtype').val('newlicence');
	clearregistrationform(); 
	validatemakearegistration();   
	new_attachedcardarray(selectbox);
	$('#delaerrep').attr("disabled", true); 
}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	if(flag == true)
	{
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
	else if(flag == false)
	{
		if(input == "")
		{
			getcustomerlistonsearch();
		}
		else
		{
			$('option', selectbox).remove();
			var options = selectbox.attr('options');
			var addedcount = 0;
			for( var i=0; i < customersearcharray.length; i++)
			{
					if(input.charAt(0) == "%")
					{
						withoutspace = input.substring(1,input.length);
						pattern = new RegExp(withoutspace.toLowerCase());
						comparestringsplit = customersearcharray[i].split("^");
						comparestring = comparestringsplit[1];
					}
					else
					{
						pattern = new RegExp("^" + input.toLowerCase());
						comparestring = customersearcharray[i];
					}
					var result1 = pattern.test(trimdotspaces(customersearcharray[i]).toLowerCase());
					var result2 = pattern.test(customersearcharray[i].toLowerCase());
					if(result1 || result2)
					{
						var splits = customersearcharray[i].split("^");
						options[options.length] = new Option(splits[0], splits[1]);
						addedcount++;
						if(addedcount == 100)
							break;
					}
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
	selectfromlist();
}



function searchbycustomerid(cusid)
{
	tabopen5('1','tabg1');
	$('#form-error').html('');
	var form = $('#submitform');
	$("#submitform" )[0].reset();
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	$("#filterdiv").hide();
	var queryString = "../ajax/customer.php";
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
					if(response['cusslno'] == '')
					{
						
						alert('Customer Not Available.');
						$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory type_enter focus_redclass" id="district"><option value="">Select A State First</option></select>');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						$("#tabgroupgridc5_5").html('No datas found to be displayed.');
						gridtabcus4('5','tabgroupgrid','&nbsp; &nbsp;Current Auto Registrations');
						$("#tabgroupgridc5_5").html('No datas found to be displayed.');
						clearregistrationform();
					} 
					generatecustomerautoregistration(response['cusslno'],'');//alert(response[40])
					generatecustomerregistration(response['cusslno'],'');//alert(response[40])
					$("#lastslno").val(response['cusslno']);
					generatecustomerattachcards(response['cusslno']);
					rereg_refreshcuscardarray(response['cusslno']);
					if($("#resendmail")) 
						$('#resendmail').show();
					
					if(response['p_editcustomercontact'] != 'yes') 
					{ 
						disablenew();disablesave(); disabledelete(); 
					}
					else 
					{ 
						enablenew();enablesave(); enabledelete(); 
					}
					
					if($("#registrationfieldradio0"))
					{
						$('#registrationfieldradio0').is(':checked');
						validatemakearegistration();
					}
					$("#customerid").val(response['customerid']);
					$("#businessname").val(response['businessname']);
					$("#short_url").html(response['rescontact'] +"\r\n"+ response['businessname']+"\r\n"+ response['address']+"\n"+ response['place']+"\n"+ response['districtname']+"\n"+ response['statename']+"\n" + response['pincode']+"\r\n"+ response['resphone']+"\n"+ response['rescell']+"\n"+ response['resemailid']);
					$("#address").val(response['address']);
					$("#place").val(response['place']);
					$("#state").val(response['state']);
					getdistrict('districtcodedisplay', response['state']);
					$("#district").val(response['district']);
					$("#pincode").val(response['pincode']);
					$("#region").val(response['region']);
					$("#stdcode").val(response['stdcode']);
					$("#website").val(response['website']);
					$("#category").val(response['category']);
					$("#type").val(response['type']);
					$("#remarks").val(response['remarks']);
					$("#salessummary").html(response['grid']);
					if(response['passwordchanged'] == '')
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
						$('#displaypassworddfield').hide();
					}
					else
					if(response['passwordchanged'] == 'n')
					{
						$('#initialpassworddfield').show();
						$("#initialpassword").val(response['password']);
						$('#displayresetpwd').hide();
					}
					else if(response['passwordchanged'] == 'y')
					{
						if(response['p_editcustomerpassword'] == 'yes')
						{
							$('#displayresetpwd').show();
						}
						else
						{
							$('#displayresetpwd').hide();
						}
						$("#resetpassword").val(response['password']);
						$('#initialpassworddfield').hide();
					}
					autochecknew($("#disablelogin"),response['disablelogin']);
					autochecknew($("#corporateorder"),response['corporateorder']);
					autochecknew($("#isdealer"),response['isdealer']);
					autochecknew($("#displayinwebsite"),response['displayinwebsite']);
					autochecknew($("#promotionalsms"),response['promotionalsms']);
					autochecknew($("#promotionalemail"),response['promotionalemail']);
					$("#currentdealer").val(response['currentdealer']);
					$("#fax").val(response['fax']);
					$("#activecustomer").html(response['activecustomer']);
					autochecknew($("#companyclosed"),response['companyclosed']);
					$("#branch").val(response['branch']);
					$("#createddate").html(response['createddate']);
					
					$('#tabgroupgridc4').hide();
					$('#tabgroupgridc8').hide();
					if(response['p_editcustomercontact'] == 'yes')
					{ 
						generatecustomerautoregistration(response['cusslno'],'');
						generatecustomerregistration(response['cusslno'],'');  
						enableregistration();
					}
					if(response['p_registration'] == 'yes') { generatecustomerautoregistration(response['cusslno'],''); generatecustomerregistration(response['cusslno'],''); 	enableregistration(); }
					if(response['userid'] == '1') { enabledelete(); }
					if(response['p_editcustomerpassword'] == 'yes' && response['customerid'] != '' )
					{
						$('#displaypassworddfield').show();
						$('#resetpwd').hide();
					}
					else
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
					}
					
					var countrow = response['contactarray'].split('****');
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).addClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).addClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).addClass("button_enter1"); 
						});
						$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
						if($(this).get(0).type == 'checkbox')
							$(this).removeClass("checkbox_enter1"); 
						else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
							$(this).removeClass("css_enter1");  
						else if($(this).get(0).type == 'button')
							$(this).removeClass("button_enter1"); 
						});
						$('#'+value).html(slno);
						
					}
					findlasttd();
					splitvalue = new Array();
					for(var i=0;i<countrow.length;i++)
					{
						splitvalue[i] =  countrow[i].split('#');
						$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
						$("#"+'name'+(i+1)).val(splitvalue[i][1]);
						$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
						$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
						$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
						$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
					}
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

function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		var passData = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#reg-form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
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
					$('#reg-form-error').html('');
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					$("#autodetailsonscratch").show();
					$("#transferimagespan").css({visibility:"visible"});
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$('#cardnumberdisplay').html(response['cardid']);
						$('#scratchnodisplay').html(response['scratchnumber']);
						$('#purchasetypedisplay').html(response['purchasetype']);
						$('#usagetypedisplay').html(response['usagetype']);
						$('#attachedtodisplay').html(response['attachedto']);
						$('#productdisplay').html(response['productname'] + ' [' + response['productcode'] + ']');
						$('#registeredtodisplay').html(response['registeredto']);
						$('#attachdatedisplay').html(response['attcheddate']);
						$('#registerdatedisplay').html(response['registereddate']);
						$('#cardstatusdisplay').html(response['cardstatus']);
						$('#schemedisplay').html(response['schemename']);
						
						$('#delaerrep').val(response['dealerid']);
						$('#productname').val(response['productname'] + ' [' + response['productcode'] + ']');
						$('#productcode').val(response['productcode']);
						$('#tfpurchasetype').val(response['purchasetype']);
						$('#tfusagetype').val(response['usagetype']);
						$('#tfdealer').val(response['attachedto']);
						$('#tfproduct').val(response['productname']);
						
						// Auto Registrstion
						$('#autocardnumberdisplay').html(response['cardid']);
						$('#autoscratchnodisplay').html(response['scratchnumber']);
						$('#autopurchasetypedisplay').html(response['purchasetype']);
						$('#autousagetypedisplay').html(response['usagetype']);
						$('#autoattachedtodisplay').html(response['attachedto']);
						$('#autoproductdisplay').html(response['productname'] + ' [' + response['productcode'] + ']');
						$('#autoregisteredtodisplay').html(response['registeredto']);
						$('#autoattachdatedisplay').html(response['attcheddate']);
						$('#autoregisterdatedisplay').html(response['registereddate']);
						$('#autocardstatusdisplay').html(response['cardstatus']);
						$('#autoschemedisplay').html(response['schemename']);
						
						$('#autodelaerrep').val(response['dealerid']);
						$('#autoproductname').val(response['productname'] + ' [' + response['productcode'] + ']');
						$('#autoproductcode').val(response['productcode']);
						$('#tfpurchasetype').val(response['purchasetype']);
						$('#tfusagetype').val(response['usagetype']);
						$('#tfdealer').val(response['attachedto']);
						$('#tfproduct').val(response['productname']);
						
						
					}
					else
					{
						$('#reg-form-error').html(errormessage('No datas found to be displayed.'));
					}
					
				}
			}, 
			error: function(a,b)
			{
				$("#reg-form-error").html(scripterror());
			}
		});
	}
}

function validatemakearegistration1()
{
	var form = $('#autoregistrationform');
	var error = $('#reg-form-error');
		//$('#delaerrep').attr("disabled", true); 
		$('#pinno').attr("readonly", false); 
		$('#searchscratchnumber1').attr("readonly", false);
		$("#scratchdisplay").show();
		autoregcardarray = new_autoregcardarray;
		getregcardlist1();
}

function validatemakearegistration()
{
	var form = $('#registrationform');
	var error = $('#reg-form-error');
	var registrationfieldradio = $('input[name=registrationfieldradio]:checked').val();
	if(registrationfieldradio == 'newlicence')
	{
		$('#delaerrep').attr("disabled", true); 
		$('#scratchnumber').attr("readonly", false); 
		$('#searchscratchnumber').attr("readonly", false);
		$("#scratchdisplay").show();
		regcardarray = new_regcardarray;
		getregcardlist();
	}
	else if(registrationfieldradio == 'updationlicense')
	{
		$('#delaerrep').attr("disabled", true); 
		$('#scratchnumber').attr("readonly", false); 
		$('#searchscratchnumber').attr("readonly", false); 
		$("#scratchdisplay").show();
		regcardarray = up_regcardarray;
		getregcardlist();
	}
	else if(registrationfieldradio == 'reregistration')
	{
		$('#delaerrep').attr("disabled", true); 
		$('#scratchnumber').attr("readonly", false); 
		$('#searchscratchnumber').attr("readonly", false); 
		$("#scratchdisplay").show();
		regcardarray = rereg_regcardarray;getregcardlist();
	}
	else if(registrationfieldradio == 'withoutcard')
	{
		$('#delaerrep').attr("disabled", false); 
		$('#scratchnumber').attr("readonly", true); 
		$('#searchscratchnumber').attr("readonly", true); 
		$("#scratchdisplay").hide();
	}
}

function checkpin()
{
	var error = $("#reg-form-error");
	var passData = "switchtype=checkpin&pin=" + encodeURIComponent($('#pinno').val()) + "&custslno=" + encodeURIComponent($('#custslno').val()) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	error.html(getprocessingimage());
  var queryString = "../ajax/customer.php"; 
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
			  error.html('');
			  var response = ajaxresponse.split('^');
			  if(response[0] != "")
			  {
				 alert(response[1]);
			  }
			  if(response[0] == 1)
			  {
				  $('#generateregistration').removeAttr("disabled");
			  }
		  }
	  }, 
	  error: function(a,b)
	  {
		  error.html(scripterror());
	  }
  });
}


function makeautoaregistration()
{
	
	var error = $("#reg-form-error");
	var passData = "switchtype=generate&pin=" + encodeURIComponent($('#searchscratchnumber1').val()) + "&customerid=" + encodeURIComponent($('#customerid').val()) + "&computerid=" + encodeURIComponent($('#computerid2').val()) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	error.html(getprocessingimage());
  var queryString = "../ajax/SoftKeyGen.php"; 
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
			  //alert(ajaxresponse);
			  error.html('');
			  
			  var response = ajaxresponse.split('^');
			  if(response[0] != "")
			  {
				 alert(response[1]);
			  }
			  if(response[0] == 1)
			  {
				  $('#generateregistration').removeAttr("disabled");
			  }
		  }
	  }, 
	  error: function(a,b)
	  {
		  error.html(scripterror());
	  }
  });
}

function makearegistration()
{
	$("#transferimagespan:visible").show("");
	var form = $("#registrationform");
	var error = $("#reg-form-error");
	var registrationfieldradio = $('input[name=registrationfieldradio]:checked').val();
	if(registrationfieldradio == 'newlicence')
	{
		$('#hiddenregistrationtype').val('newlicence');
	
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above.")); field.focus(); return false; }
		var scratchnumber = $("#scratchcardlist").val();
		if(!scratchnumber) { error.html(errormessage("Please Select the Scratch Number from the list.")); $("#scratchcardlist").focus(); return false; }
		var field = $("#computerid");
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }
		var field = $("#billno");
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Number.")); field.focus(); return false; }
		var field = $("#billamount");
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Amount.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	}
	else if(registrationfieldradio == 'updationlicense')
	{
		$('#hiddenregistrationtype').val('updationlicense');
		
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above.")); field.focus(); return false; }
		var scratchnumber = $('#scratchcardlist').val();
		if(!scratchnumber) { error.html(errormessage("Please Select the Scratch Number from the list.")); field.focus(); return false; }
		var field = $("#computerid");
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }
		var field = $("#billno");
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Number.")); field.focus(); return false; }
		var field = $("#billamount");
		if(!field.val()) { error.html(errormessage("Please Enter the Bill Amount.")); field.focus(); return false; }
		if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	}
	else if(registrationfieldradio == 'reregistration')
	{
		$('#hiddenregistrationtype').val('reregistration');
	//	document.getElementById('dispreregcardlist').style.display = 'block';
		if(!$('#lastslno').val()) { error.html(errormessage("Please Select a Customer from list above.")); field.focus(); return false; }

		var scratchnumber = $('#scratchcardlist').val();
		if(!scratchnumber) { error.html(errormessage("Please Select the Scratch Number from the list.")); field.focus(); return false; }
		var field = $("#computerid");
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; }
		if(field.val()) { if(!computeridvalidate(field.val())) { error.innerHTML = errormessage("Enter the valid Computerid "); field.focus(); return false; } }
		var field = $("#regremarks");
		if(!field.val()) { error.html(errormessage("Please Enter the Remarks.")); field.focus(); return false; }
	}
	else if(registrationfieldradio == 'withoutcard')
	{
		$('#hiddenregistrationtype').val('withoutcard');
	//	document.getElementById('dispreregcardlist').style.display = 'none';
		//form.scratchnumber.readOnly = true;
		var field =  $("#delaerrep");
		if(!field.val()) { error.html(errormessage("Please Select the dealer from the List.")); field.focus(); return false; }
		var field = $("#computerid");
		if(!field.val()) { error.html(errormessage("Please Enter the Computer ID.")); field.focus(); return false; 
		if(field.val()) { if(!computeridvalidate(field.val())) { error.html(errormessage("Enter the valid Computerid ")); field.focus(); return false; } }}
		var field = $("#regremarks");
		if(!field.val()) { error.html(errormessage("Please Enter the Remarks.")); field.focus(); return false; }
	}
		var passData = "switchtype=generatesoftkey&registrationtype=" + encodeURIComponent($('#hiddenregistrationtype').val()) + "&scratchnumber=" + encodeURIComponent(scratchnumber) + "&customerid=" + encodeURIComponent($('#lastslno').val()) + "&delaerrep=" + encodeURIComponent($('#delaerrep').val()) + "&productcode=" + encodeURIComponent($('#productcode').val())+  "&productname=" + encodeURIComponent($('#productname').val()) + "&computerid=" + encodeURIComponent($('#computerid').val()) + "&billno=" + encodeURIComponent($('#billno').val()) + "&billamount=" + encodeURIComponent($('#billamount').val()) + "&regremarks=" + encodeURIComponent($('#regremarks').val()) + "&usagetype=" + encodeURIComponent($('#usagetypedisplay').html()) + "&purchasetype=" +  encodeURIComponent($('#purchasetypedisplay').html()) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		error.html(getprocessingimage());
		var queryString = "../ajax/customer.php"; 
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
					error.html('');
					var response = ajaxresponse.split('^');
					if(response[0] == 2) 
					{ 
						error.html(errormessage(response[1])); $('#computerid').focus();
					}
					else
					{
						alert(response[1]);//response message when soft key is generated
						generatecustomerautoregistration($('#lastslno').val(),''); 
						generatecustomerregistration($('#lastslno').val(),''); 
						generatecustomerattachcards($('#lastslno').val());
						customerdetailstoform($('#lastslno').val());
						//Update Customer ID and Password field, if it is a new cusotmer
						if(response[2] != "")
						{
							var customeridpassword = response[2].split("%");
							$('#customerid').val(customeridpassword[0]);
							$('#password').val(customeridpassword[1]);
						}
						
						gridtabcus4(1,'tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$("#registrationform" )[0].reset();
						clearregistrationform();
					//disableregistration();
				//error.innerHTML = successmessage(response[1]);
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});
}

function enableregistration()
{
	//document.getElementByNames('registrationfieldradio').disabled = false;
	if($("#registrationfieldradio0")) $('#registrationfieldradio0').attr("disabled", false);
	if($("#registrationfieldradio1")) $('#registrationfieldradio1').attr("disabled", false);
	if($("#registrationfieldradio2")) $('#registrationfieldradio2').attr("disabled", false);
	if($("#registrationfieldradio3")) $('#registrationfieldradio3').attr("disabled", false);

	$('#searchscratchnumber').attr("disabled", false);
	$('#scratchnumber').attr("disabled", false);
	$('#delaerrep').attr("disabled", true);
	
	$('#productname').attr("disabled", false);
	$('#productcode').attr("disabled", false);
	$('#computerid').attr("disabled", false);
	$('#billno').attr("disabled", false);
	$('#billamount').attr("disabled", false);
	$('#regremarks').attr("disabled", false);
	$('#generateregistration').attr("disabled", false);
	$('#registrationclearall').attr("disabled", false);
	$('#closereg').attr("disabled", false);
	
}

function disableregistration()
{
	//document.getElementByNames('registrationfieldradio').disabled = true;
	if($("#registrationfieldradio0")) $('#registrationfieldradio0').attr("disabled", true);
	if($("#registrationfieldradio1")) $('#registrationfieldradio1').attr("disabled", true);
	if($("#registrationfieldradio2")) $('#registrationfieldradio2').attr("disabled", true);
	if($("#registrationfieldradio3")) $('#registrationfieldradio3').attr("disabled", true);
	
	$('#searchscratchnumber').attr("disabled", true);
	$('#scratchnumber').attr("disabled", true);
	$('#delaerrep').attr("disabled", true);
	
	$('#productname').attr("disabled", true);
	$('#productcode').attr("disabled", true);
	$('#computerid').attr("disabled", true);
	$('#billno').attr("disabled", true);
	$('#billamount').attr("disabled", true);
	$('#regremarks').attr("disabled", true);
	$('#generateregistration').attr("disabled", true);
	$('#registrationclearall').attr("disabled", true);
	$('#closereg').attr("disabled", true);

}

function clearregistrationform()
{
	$("#detailsonscratch").hide();
	$("#autodetailsonscratch").hide();
	
	$("#reg-form-error").html('');
	$("#searchscratchnumber").val('');
	$("#scratchnumber").val('');
	$("#delaerrep").val('');
	$("#productname").val('');
	$("#productcode").val('');
	$("#computerid").val('');
	$("#billno").val('');
	$("#billamount").val('');
	$("#regremarks").val('');
	$("#cardnumberdisplay").html('');
	$("#scratchnodisplay").html('');
	$("#purchasetypedisplay").html('');
	$("#usagetypedisplay").html('');
	$("#attachedtodisplay").html('');
	$("#registeredtodisplay").html('');
	$("#attachdatedisplay").html('');
	$("#registerdatedisplay").html('');
	
	$("#transferimagespan").css("visibility", "hidden");


}


function transferscratchdetails()
{
	var form = $("#transferscratchform");
	var error = $("#tranfer-form-error");
	var ttdealercheck = $("#ttdealercheck");
	var ttproductcheck = $("#ttproductcheck");
	var ttpurchasetypecheck = $("#ttpurchasetypecheck");
	var ttusagetypecheck = $("#ttusagetypecheck");
	var ttdealerto = $("#ttdealerto");
	var ttproductto = $("#ttproductto");
	if(($('#ttdealercheck').is(':checked')) == false && ($('#ttproductcheck').is(':checked')) == false 
  && ($('#ttpurchasetypecheck').is(':checked')) == false && ($('#ttusagetypecheck').is(':checked')) == false)
	{
		error.html(errormessage("Select Transfer To")); ttdealercheck.focus(); return false;
	}
	if(($('#ttdealercheck').is(':checked')) == true) 
	{
		$('#ttdealerto').attr("disabled", false);
		var field = $('#ttdealerto'); if(!field.val()) { error.html(errormessage("Please select the Dealer from the list")); $('#ttdealerto').focus(); return false; } 
		var ttdealerto1 = $('#ttdealerto').val();
	}
	else
	{
		var ttdealerto1 = '';
	}
	
	if(($('#ttproductcheck').is(':checked')) == true) 
	{  
		$('#ttproductto').attr("disabled", false); 
		var field = $('#ttproductto'); if(!field.val()) { error.html(errormessage("Please select the Prduct.")); $('#ttproductto').focus(); return false; }
		var ttproductto1 = $('#ttproductto').val();
	}
	else
	{
		var ttproductto1 = '';
	}
	
	if(($('#ttusagetypecheck').is(':checked')) == true) 
	{
		$('#ttusagetype').attr("disabled", false);
		var field = $('#ttusagetype'); if(!field.val()) { error.html(errormessage("Please select the Usage Type.")); $('#ttdealerto').focus(); return false; } 
		var ttusagetype1 = $('#ttusagetype').val();
	}
	else
	{
		var ttusagetype1 = '';
	}	
	
	if(($('#ttpurchasetypecheck').is(':checked')) == true) 
	{
		$('#ttpurchasetype').attr("disabled", false); 
		var field = $('#ttpurchasetype'); if(!field.val()) { error.html(errormessage("Please select the Purchase Type.")); field.focus(); return false; }
		var ttpurchasetype1 = $('#ttpurchasetype').val();

	}
	else
	{
		var ttpurchasetype1 = '';
	}
	
		var passData = "switchtype=scratchtransfer&dealerid=" + encodeURIComponent(ttdealerto1) + "&productcode=" + encodeURIComponent(ttproductto1) + "&usagetype=" + encodeURIComponent(ttusagetype1) + "&purchasetype=" + encodeURIComponent(ttpurchasetype1) + "&tfproduct=" + encodeURIComponent($('#productcode').val()) + "&tfdealer=" + encodeURIComponent($('#delaerrep').val()) + "&tfpurchasetype=" + encodeURIComponent($('#tfpurchasetype').val()) + "&tfusagetype=" + encodeURIComponent($('#tfusagetype').val()) + "&cardid=" + encodeURIComponent($('#cardnumberdisplay').html()) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		error.html(getprocessingimage());
		var queryString = "../ajax/customer.php";
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
					error.html('');
					var response = ajaxresponse;
					if(response['errorcode'] == 1)
					{
					  scratchdetailstoform($('#scratchcardlist').val());
					  displayelement('tabgroupgridc2','transferscratchcarddiv');
					}

				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
}

function tranfervalues()
{
	disabletranfer();
	$('#tranfer-form-error').html('');
	displayelement('transferscratchcarddiv','tabgroupgridc2'); 
	$('#transfercardfield').val($('#searchscratchnumber').val());
	$('#tfpurchasetype').val($('#purchasetypedisplay').html());
	$('#tfusagetype').val($('#usagetypedisplay').html());
	$('#tfdealer').val($('#attachedtodisplay').html());
	$('#tfproduct').val($('#productname').val());
	scratchdetailstoform($('#scratchnumber').val());
}


function disabletranfer()
{
	$('#ttdealerto').attr("disabled", true);
	$('#ttproductto').attr("disabled", true);
	$('#ttpurchasetype').attr("disabled", true);
	$('#ttusagetype').attr("disabled", true);
}
//Function to disable and enable the the selectbox on checked box value is true=
function productcheckbox()
{
	var form = $('#transferscratchform');
	var ttproductcheck = $('#ttproductcheck');
	if($('#ttproductcheck').is(':checked'))
	{
		$('#ttproductto').attr("disabled", false);
	}
	else
	{
		$('#ttproductto').attr("disabled", true);
	}
}

function purchasecheckbox()
{
	var form = $('#transferscratchform');
	var ttpurchasetypecheck = $('#ttpurchasetypecheck');
	if($('#ttpurchasetypecheck').is(':checked'))
	{
		$('#ttpurchasetype').attr("disabled", false);
	}
	else
	{
		$('#ttpurchasetype').attr("disabled", true);
	}
}

function usagecheckbox()
{
	var form = $('#transferscratchform');
	var ttusagetypecheck = $('#ttusagetypecheck');
	if($('#ttusagetypecheck').is(':checked'))
	{
		$('#ttusagetype').attr("disabled", false);
	}
	else
	{
		$('#ttusagetype').attr("disabled", true);
	}
}

function dealercheckbox()
{
	var form = $('#transferscratchform');
	var ttdealercheck = $('#ttdealercheck');
	if($('#ttdealercheck').is(':checked'))
	{
		$('#ttdealerto').attr("disabled", false);
	}
	else
	{
		$('#ttdealerto').attr("disabled", true);
	}
}


function getreregscratchcards()
{
	
	var lastslno = $('#lastslno').val();
	var passData = "switchtype=getreregscratchcardlist&lastslno=" + encodeURIComponent($('#lastslno').val());
	var queryString = "../ajax/customer.php";
	ajaxcall11 = $.ajax(
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
				var response = ajaxresponse;
				if(response['errorcode'] == 1)
				{
					$('#dispreregcardlist').html(response['grid']);
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


/*function cardsearchfilter()
{
	var form = $('#cardsearchfilterform');
	var textfield = $('#cardsearchcriteria').val();
	var error = $('#filter-form-error');
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $('#searchcriteria').focus(); return false; }
	var passData = "switchtype=cardsearch&textfield=" + textfield + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	error.html(getprocessingimage());
	ajaxcall12 = $.ajax(
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
					$('#dispreregcardlist').html(response[1]);
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
*/


function enableregbuttons()
{
	//alert('reg');
	if($('#registrationform'))
	{
		var form = $('#registrationform');
		$('#registrationfieldradio0').attr("disabled", false);
		$('#registrationfieldradio1').attr("disabled", false);
		$('#registrationfieldradio2').attr("disabled", false);
		$('#registrationfieldradio3').attr("disabled", false);
	}

}

function resendwelcomeemail()
{
	var customerid = $('#customerid').val();
	var error = $('#form-error');
	if(customerid != '')
	{
		var confirmation = confirm("Are you sure you want to send a Welcome Email to the selected customer?");
		if(confirmation)
		{
			var passData = "switchtype=resendwelcomeemail&customerid=" + encodeURIComponent(customerid);
			error.html(getprocessingimage());//alert(passData)
			var queryString = "../ajax/customer.php";
			ajaxcall13 = $.ajax(
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
						if(response['errorcode'] == 1)
							$('#displaysearchresult').html(response['errormessage']);
						else
							$('#displaysearchresult').html(response['errormessage']);
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});	
		}
	}
	else
	error.html(errormessage('Cannot send mail to a customer who do not have Registration'));
	return false;
}



function generatecustomerattachcards(customerid)
{
	
	var form = $('#submitform');
	$("#lastslno").val(customerid);
	var passData = "switchtype=generatecustomerattachcards&lastslno="+ encodeURIComponent(customerid);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	//document.getElementById('tabgroupgridc4').innerHTML = getprocessingimage();
	//document.getElementById('tabgroupgridc1link').innerHTML ='';
	ajaxcall14 = $.ajax(
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
					$("#tabgroupgridc4").html(response[1]);
					$("#tabgroupgridc8").html(response[1]);
				}
				else
				{
					$("#tabgroupgridc4").html(scripterror());
					$("#tabgroupgridc8").html(scripterror());
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc4").html(scripterror());
			$("#tabgroupgridc8").html(scripterror());
		}
	});	
}
// Bhavesh Patel created 
function surrenderhistory(pin,customerid)
{
	
	var form = $('#submitform');
	$("#lastslno").val(customerid);
	var passData = "switchtype=surrenderhistory&lastslno="+ encodeURIComponent(customerid)
	+ "&pin=" + encodeURIComponent(pin);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	$("#surrendergridc7").html('');
	$('#surrendergridc7').html(getprocessingimage());
	ajaxcall14 = $.ajax(
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
					$("#surrendergridc7").html(response[1]);
				}
				/*else{ $("#surrendergridc7").html(scripterror()); }*/
			}
		}, 
		error: function(a,b)
		{
			$("#surrendergridc7").html(scripterror());
		}
	});	
}

function pindetailsinform(pin)
{
	
	var form = $('#submitform');
	var passData = "switchtype=pininform&pin=" + encodeURIComponent(pin);
	//alert(passData);
	if(pin != '')
	{	
		
		var passData = "switchtype=pininform&pinumber=" + encodeURIComponent(pin) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert('here')
		$('#pindetails').hide();
		$('#pindetailsgridc7').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
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
					$('#pindetails').show();
					$('#pindetailsdisplaycardnumber').html(response['cardid']);
					$('#pindetailsdisplayscratchno').html(response['scratchnumber']);
					$('#pindetailsdisplaypurchasetype').html(response['purchasetype']);
					$('#pindetailsdisplayusagetype').html(response['usagetype']);
					$('#pindetailsdisplayattachedto').html(response['attachedto']);
					$('#pindetailsdisplayproductname').html(response['productname']);
					$('#pindetailsdisplayattachdate').html(response['attcheddate']);
					$('#pindetailsdisplayregisteredto').html(response['registeredto']);
					$('#pindetailsdisplayregisterdate').html(response['registereddate']);
					$('#pindetailsdisplaycardstatus').html(response['cardstatus']);
					$('#pindetailsdisplaycardremarks').html(response['remarks']);
					$('#pindetailsdisplayscheme').html(response['schemename']);
					$('#pindetailsdisplayattachedtocust').html(response['businessname']);
					$('#pindetailsgridc7').html('');
				}
			}, 
			error: function(a,b)
			{
				$("#pindetailsgridc7").html(scripterror());
			}
		});	
	}
}

function cleargrid()
{
	$("#tabgroupgridc1_1").html('No datas found to be displayed.');
	$("#tabgroupgridc5_5").html('No datas found to be displayed.');
	$("#tabgroupgridc1link").html('');
	$("#tabgroupgridc5link").html('');
	$("#regresultgrid").html('');
	$("#tabgroupgridwb1").html('');
	$("#tabgroupgridwb5").html('');
	$("#tabgroupgridc4").html('No datas found to be displayed.');
	$("#tabgroupgridc8").html('No datas found to be displayed.');

}


function resetregdetails()
{
	$("#scratchdisplay").show();
	$('#registrationfieldradio0').attr('checked', true);
	$("#hiddenregistrationtype").val('newlicence');
	validatemakearegistration();
}

//function to get the radio value
function getradiovalue(radioname)
{
	//alert(radioname)
	if(radioname.value)
		return radioname.value;
	else
	for(var i = 0; i < radioname.length; i++) 
	{
		if(radioname[i].checked) {
			return radioname[i].value;//alert(radioname.value)
		}
	}
}

//Function select the tab in display-Meghana[18/12/2009]

function displayDiv()
{
	if($('#filterdiv').is(':visible'))
	{
		$('#filterdiv').show();
	}
	else
	{
		$('#filterdiv').hide();
	}
}

function validateproductcheckboxes()
{
var chksvalue = $("input[name='productarray[]']");
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


function validatepwd()
{ 
	var form = $('#submitform'); 
	var cusid = $('#lastslno').val();
	var businessname = $('#businessname').val();
	var error = $('#form-error');
	var field = $('#password');
	if(!field.val()){error.html(errormessage("Enter the Password")); return false; field.focus(); }
	else
	if(cusid != '')
	{
		var confirmation = confirm("Do you really want to reset the customer login password for " + businessname + " ??");
		if(confirmation)
		{
			var passData  = "switchtype=resetpwd&password=" + encodeURIComponent($('#password').val()) + "&cusid=" + cusid + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData) 
			var queryString = "../ajax/customer.php"; 
			ajaxcall15 = $.ajax(
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
							$('#form-error').html('');
							if(response1['passwordchanged'] == 'N')
							{
								$('#initialpassworddfield').show();
								$('#displayresetpwd').hide();
								$('#initialpassword').val(response1['initialpassword']);
								$("#displaypassworddfield").show();
								$("#resetpwd").hide();
								
							}
							else
							{
								$('#initialpassword').val(response1['initialpassword']);
								$("#displaypassworddfield").show();
								$("#resetpwd").hide();
								
							}
							$('#form-error').html(successmessage('Password Updated Successfully'));
							$('#password').val('');
						}
						else
						{
							error.html(errormessage('Cannot reset Password.'));
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
		error.innerHTML = '';
		return false;
	}
}

function closefunc()
{
	var error = $('#form-error');
	$("#resetpwd").hide();
	$("#displaypassworddfield").show();
	error.html('');
	return false;
}

function Displaydiv1()
{
	$("#resetpwd").show();
	$("#password").focus();
	$("#displaypassworddfield").hide();
	return false;
}


function displaycustomerdetails()
{
	$("#invoicedetailscdiv").show();
	$("#invoicedetailshdiv").hide();
	//alert(document.getElementById('lastslno').value);
}

function generatercidetails(startlimit)
{
	var form = $('#submitform');
	//$('#rcidetailsgridc1').show();
	//$('#detailsdiv').hide();
	var passData = "switchtype=rcidetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	$('#rcidetailsgridc1_1').html(getprocessingimage());
	$('#rcidetailsgridc1link').html('');
	ajaxcall16 = $.ajax(
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
					$('#rcidetailsgridwb1').html("Total Count :  " + response[2]);
					$('#rcidetailsgridc1_1').html(response[1]);
					$('#rcidetailsgridc1link').html(response[3]);
				}
				else
				{
					$('#rcidetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#rcidetailsgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" link - to get registration records
function getmorercidetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=rcidetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#rcidetailsgridc1link').html(getprocessingimage());
	ajaxcall16 = $.ajax(
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
					$('#rcidetailsgridwb1').html("Total Count :  " + response[2]);
					$('#rcidetailsresultgrid').html($('#rcidetailsgridc1_1').html());
					$('#rcidetailsgridc1_1').html($('#rcidetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
					$('#rcidetailsgridc1link').html(response[3]);
				}
				else
				{
					$('#rcidetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#rcidetailsgridc1_1").html(scripterror());
		}
	});	
}




function generateinvoicedetails(startlimit)
{
	
	var form = $('#submitform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	//$('#detailsdiv').hide();
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall17 = $.ajax(
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
				var response = ajaxresponse.split('^');//alert(response[0]);
				if(response[0] == 1)
				{
					$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
					$('#invoicedetailsgridc1_1').html(response[1]);
					$('#invoicedetailsgridc1link').html(response[3]);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" link - to get registration records
function getmoreinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall18 = $.ajax(
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
					$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
					$('#invoicedetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
					$('#invoicedetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
					$('#invoicedetailsgridc1link').html(response[3]);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});	
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(id)
{
	$('#invoicelastslno').val(id);
	var form = $('#detailsform');	
	if($('#invoicelastslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#detailsform').attr("action","../ajax/viewinvoicepdf.php") ;
		$('#detailsform').attr('target','_blank');
		$('#detailsform').submit();
	}
}



//To add description rows
function adddescriptionrows()
{
	$("#form-error").html('');
	var rowcount = ($('#adddescriptionrows tr').length);
	if(rowcount == 1)
		slno  = (rowcount+1);
	else
		slno = rowcount + 1;

	rowid = 'removedescriptionrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory type_enter focus_redclass"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext type_enter focus_redclass" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext type_enter focus_redclass" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
	$("#adddescriptionrows").append(row);
	$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').focus(function() {
	if($(this).get(0).type == 'checkbox')
		$(this).addClass("checkbox_enter1"); 
	else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
		$(this).addClass("css_enter1");  
	else if($(this).get(0).type == 'button')
		$(this).addClass("button_enter1"); 
	});
	$('input[type=text].focus_redclass, select.focus_redclass, textarea.focus_redclass, input[type=checkbox].focus_redclass, input[type=button].focus_redclass').blur(function() {
	if($(this).get(0).type == 'checkbox')
		$(this).removeClass("checkbox_enter1"); 
	else if($(this).get(0).type == 'text' || $(this).get(0).type == 'textarea' || $(this).get(0).type == 'select')
		$(this).removeClass("css_enter1");  
	else if($(this).get(0).type == 'button')
		$(this).removeClass("button_enter1"); 
	});

	$('#'+value).html(slno);
	findlasttd();
	if(slno == 10)
		$('#adddescriptionrowdiv').hide();
	else
		$('#adddescriptionrowdiv').show();
}

//Remove description row
function removedescriptionrows(rowid,rowslno)
{
	if(totalarray == '')
		totalarray = $('#contactslno'+rowslno).val();
	else
		totalarray = totalarray  + ',' + $('#contactslno'+rowslno).val();
	var error = $("#form-error");
	$('#adddescriptionrowdiv').show();
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 1)
	{
		error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
		return false;
	}
	else
	{
		$('#'+rowid).remove();
		findlasttd();
		var countval = 0;
		for(i=1;i<=(rowcount+1);i++)
		{
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



function displayrcidata()
{
	if($('#lastslno').val() != '')
	{
		generatercidetails('');
		$("").colorbox({ inline:true, href:"#rcidatagrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
	
}

function displaysurrender(pin,lastslno)
{
	if($('#lastslno').val() != '')
	{
		surrenderhistory(pin,lastslno);
		$("").colorbox({ inline:true, href:"#surrendergrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}

function displaypindetails(pin)
{
	if($('#lastslno').val() != '')
	{
		pindetailsinform(pin);
		$("").colorbox({ inline:true, href:"#pindetailsgrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}

function displayinvoicedetails()
{
	if($('#lastslno').val() != '')
	{
		generateinvoicedetails('');
		$("").colorbox({ inline:true, href:"#invoicedetailsgrid" , onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}


function displaycustomeralertdetails()
{
	if($('#lastslno').val() != '')
	{
		$("#customernameforalert").html($("#businessname").val());
		//tinyMCE.execCommand('mceRemoveControl',false, "alertcontent"); 
		generatecustomeralertgrid('');
		$("").colorbox({ inline:true, href:"#customeralertsgrid", onCleanup: function() {tinyMCE.execCommand('mceRemoveControl',true, "alertcontent")}, onLoad: function() { $('#cboxClose').hide()}});
		tinyMCE.execCommand("mceAddControl", true, "alertcontent");
	}
	else
	{
		return false;
	}
}


function findlasttd()
{
	if($("#adddescriptionrows td").find('input').is('.default'))
	{
		$("#adddescriptionrows td").find('input').removeClass("default");
		$("#adddescriptionrows td:last").prev("td").find('input').addClass("default");
	}
	else
	{
		$("#adddescriptionrows td:last").prev("td").find('input').addClass("default");
	}
}
function newentryforform(permissiontype)
{
	if(permissiontype == 'permission_no')
	{
		newentry('false'); 
		$('#form-error').html('');
		enablesave();

	}
	else if(permissiontype == 'permission_yes')
	{
		newentry('true'); 
		$('#form-error').html('');
		cleargrid();
		enablesave();

	}
}

function customeralertsave(type)
{
	if($('#lastslno').val() != '')
	{
	  var form = $('#customeralertform');
	  var error = $("#form-error-alert" );
	  var field = $("#alertsubject" );
	  if(!field.val()) { error.html(errormessage("Please enter the subject. ")); field.focus(); return false; }
	  var field = tinyMCE.get('alertcontent').getContent();
	  if(field == '') { error.html(errormessage("Please enter the Content. ")); field.focus(); return false; }
	  if(type == 'save')
		var passData = "switchtype=customeralertsave&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&alertsubject=" + encodeURIComponent($("#alertsubject" ).val())+ "&alertcontent=" + encodeURIComponent(tinyMCE.get('alertcontent').getContent());
	else
		var passData = "switchtype=customeralertdelete&messageid="+ encodeURIComponent($('#messageidhidden').val());
	  var queryString = "../ajax/customer.php";
	  $('#form-error-alert').html(getprocessingimage());
	  ajaxcall17 = $.ajax(
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
				  var response = ajaxresponse.split('^');//alert(response[0]);
				  if(response[0] == 1)
				  {
					  $('#form-error-alert').html(successmessage(response[1]));
					  $("#alertsubject" ).val('');
					  tinyMCE.activeEditor.setContent('');
					  generatecustomeralertgrid('')
					  
				  }
				  else
					  $("#form-error-alert").html(scripterror());
			  }
		  }, 
		  error: function(a,b)
		  {
			  $("#form-error-alert").html(scripterror());
		  }
	  });	
	}
	else
		 $("#form-error-alert").html('Please select a customer from the list.');
}

function newcustomeralert()
{
 $("#alertsubject" ).val('');
//  $("#alertcontent" ).val('');
  tinyMCE.activeEditor.setContent('');
  $("#form-error-alert").html('');

}



function generatecustomeralertgrid(startlimit)
{
	
	var form = $('#customeralertform');
	//$('#invoicedetailsgridc1').show();
	var passData = "switchtype=customeralertgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	$('#customeralertsgridc1_1').html(getprocessingimage());
	$('#customeralertsgridc1link').html('');
	ajaxcall17 = $.ajax(
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
				var response = ajaxresponse.split('^');//alert(response[0]);
				if(response[0] == 1)
				{
					$('#customeralertsgridwb1').html("Total Count :  " + response[2]);
					$('#customeralertsgridc1_1').html(response[1]);
					$('#customeralertsgridc1link').html(response[3]);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#customeralertsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#invoicedetailsgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" link - to get registration records
function getmorecustomeralertgrid(id,startlimit,slno,showtype)
{
	var form = $('#customeralertform');
	$('#lastslno').val(id);
	var passData = "switchtype=customeralertgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	$('#customeralertsgridc1link').html(getprocessingimage());
	ajaxcall18 = $.ajax(
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
					$('#customeralertsgridwb1').html("Total Count :  " + response[2]);
					$('#customeralertsgridresultgrid').html($('#customeralertsgridc1_1').html());
					$('#customeralertsgridc1_1').html($('#customeralertsgridresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
					$('#customeralertsgridc1link').html(response[3]);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#customeralertsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#customeralertsgridc1_1").html(scripterror());
		}
	});	
}



function customeralertgridtoform(messageid)
{
	
	var form = $('#customeralertform');
	$('#messageidhidden').val(messageid);
	var passData = "switchtype=customeralertgridtoform&messageid="+ encodeURIComponent($('#messageidhidden').val());
	var queryString = "../ajax/customer.php";
	$('#form-error-alert').html(getprocessingimage());
	$('#customeralertsgridc1link').html('');
	ajaxcall17 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false, dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response[0]);
				if(response['errorcode'] == 1)
				{
					$('#form-error-alert').html('');
					$('#alertsubject').val(response['subject']);
					//$('#alertcontent').val(response['content']);
					tinyMCE.activeEditor.setContent(response['content']);
				}
				else
				{
					$('#form-error-alert').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error-alert").html(scripterror());
		}
	});	
}
