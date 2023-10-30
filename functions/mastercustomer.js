
var customerarray = new Array();
var totalarray = new Array();
var contactarray = '';

function formsubmit(command)
{
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
		/*if(!field.value) { error.innerHTML = errormessage("Enter the STD Code. "); field.focus(); return false; }*/
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
		else
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
		var ajaxcall0 = createajax();
		error.html(getprocessingimage());
		ajaxcall0.open('POST', queryString, true);
		ajaxcall0.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		ajaxcall0.onreadystatechange = function()
		{
			if(ajaxcall0.readyState == 4)
			{
				if(ajaxcall0.status == 200)
				{
					var ajaxresponse = ajaxcall0.responseText;//alert(ajaxresponse);
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location= '../logout.php';
						return false;
					}
					else
					{
						tabopen5('1','tabg1');
						var response = ajaxresponse.split('^');
						if(response[0] == '1')
						{
							error.html(successmessage(response[1]));
							refreshcustomerarray();
							newentry();
							rowwdelete();
						}
						else if(response[0] == '4')
						{
							error.html(successmessage(response[1]));
							refreshcustomerarray();
							newentry();
							rowwdelete();
						}
						else if(response[0] == '2')
						{
							error.html(successmessage(response[1]));
							refreshcustomerarray();
							newentry();
							rowwdelete();
						}
						else if(response[0] == '3')
						{
							error.html(errormessage(response[1]));
						}
						else
						{
							error.html(errormessage('Unable to Connect...' + ajaxcall0.responseText));
						}
					}
					}
				else
					error.html(scripterror());
		   }
		}
		ajaxcall0.send(passData);	
}


function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall1 = createajax();
	$("#customerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/customer.php";
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText.split('^*^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					customerarray = new Array();
					for( var i=0; i<response.length; i++)
					{
						customerarray[i] = response[i]; 
					}
					flag = true;
					getcustomerlist1();
					$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
					$("#totalcount").html(customerarray.length);
				}
				}
			else
				$("#customerselectionprocess").html(scripterror());
		}
	}
	ajaxcall1.send(passData);
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
		var ajaxcall3 = createajax();
		$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/customer.php";
		ajaxcall3.open("POST", queryString, true);
		ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall3.onreadystatechange = function()
		{
			if(ajaxcall3.readyState == 4)
			{
				if(ajaxcall3.status == 200)
				{
					var response = ajaxcall3.responseText.split('^*^');//alert(response)
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
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
				}
				else
					$("#customerselectionprocess").html(scripterror());
			}
		}
		ajaxcall3.send(passData);
}

function getcustomerlistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customersearcharray.length;
	$('#detailsearchtext').focus();
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
	$("#districtcodedisplay" ).html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');
	gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
	$('#tabgroupgridc3').hide();
	if($('#resendmail'))
		$('#resendmail').hide();
	clearregistrationform();
	clearcarddetails();
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
		var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
	}
		
}

function generatecustomerregistration(id,startlimit)
{
	
	var form = $('#submitform');
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);//alert(passData)
	var queryString = "../ajax/customer.php";
	ajaxcall4 = createajax();
	$("#tabgroupgridc1_1").html(getprocessingimage());
	$("#tabgroupgridc1link").html('');
	ajaxcall4.open("POST", queryString, true);
	ajaxcall4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall4.onreadystatechange = function()
	{
		if(ajaxcall4.readyState == 4)
			{
				if(ajaxcall4.status == 200)
					{
						var ajaxresponse = ajaxcall4.responseText;
						if(ajaxresponse == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else
						{
							var response = ajaxresponse.split('^');
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
						
					}
				else
					$("#tabgroupgridc1_1").html(scripterror());
			}
	}
	ajaxcall4.send(passData);
}

//Function for "show more records" link - to get registration records
function getmorecustomerregistration(id,startlimit,slno,showtype)
{
	var form = $("#submitform" );
	$('#lastslno').val(id);	
	var passData = "switchtype=customerregistration&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall5 = createajax();
	$("#tabgroupgridc1link").html(getprocessingimage());
	ajaxcall5.open("POST", queryString, true);
	ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall5.onreadystatechange = function()
	{
		if(ajaxcall5.readyState == 4)
		{
			if(ajaxcall5.status == 200)
			{
				var ajaxresponse = ajaxcall5.responseText;//alert(ajaxresponse);
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
			}
			else
				$("#tabgroupgridc1_1").html(scripterror());
		}
	}
	ajaxcall5.send(passData);
}



function customerdetailstoform(cusid)
{
	if(cusid != '')
	{
		totalarray = '';
		$("#tabgroupgridc1_1").html('');
		tabopen5('1','tabg1');
		var form = $("#submitform" );
		$("#submitform" )[0].reset();
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		ajaxcall6 = createajax();
		$("#form-error").html(getprocessingimage())
		var queryString = "../ajax/customer.php";
		ajaxcall6.open("POST", queryString, true);
		ajaxcall6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall6.onreadystatechange = function()
		{
			if(ajaxcall6.readyState == 4)
			{
				if(ajaxcall6.status == 200)
				{
				$("#form-error").html('');
				$("#searchcustomerid").val('');
				var response = (ajaxcall6.responseText).split("^");//alert(response)
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
					if(response[0] == '')
					{
						
						alert('Customer Not Available.');
						$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						clearregistrationform();
					} 
					generatecustomerregistration(response[0],'');//alert(response[40])
					$("#lastslno").val(response[0]);
					generatecustomerattachcards(response[0]);
					rereg_refreshcuscardarray(response[0]);
					if($("#resendmail")) 
						$('#resendmail').show();
					
					if(response[23] != 'yes') 
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
					$("#customerid").val(response[1]);
					$("#businessname").val(response[2]);
					$("#short_url").html(response[38] +"\r\n"+ response[2]+"\r\n"+ response[3]+"\n"+ response[4]+"\n"+ response[33]+"\n"+ response[34]+"\n" + response[7]+"\r\n"+ response[39]+"\n"+ response[40]+"\n"+ response[41]);
					$("#address").val(response[3]);
					$("#place").val(response[4]);
					$("#state").val(response[6]);
					getdistrict('districtcodedisplay', response[6]);
					$("#district").val(response[5]);
					$("#pincode").val(response[7]);
					$("#region").val(response[8]);
					$("#stdcode").val(response[9]);
					$("#website").val(response[10]);
					$("#category").val(response[11]);
					$("#type").val(response[12]);
					$("#remarks").val(response[13]);
					
					if(response[18] == '')
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
						$('#displaypassworddfield').hide();
					}else
					if(response[18] == 'n' )
					{
						$('#initialpassworddfield').show();
						$("#initialpassword").val(response[17]);
						$('#displayresetpwd').hide();
					}
					else if(response[18] == 'y')
					{
						if(response[28] == 'yes')
						{
							$('#displayresetpwd').show();
						}
						else
						{
							$('#displayresetpwd').hide();
						}
						$("#resetpassword").val(response[35]);
						$('#initialpassworddfield').hide();
					}
					autochecknew($("#disablelogin"),response[19]);
					autochecknew($("#corporateorder"),response[26]);
					autochecknew($("#isdealer"),response[36]);
					autochecknew($("#displayinwebsite"),response[42]);
					autochecknew($("#promotionalsms"),response[43]);
					autochecknew($("#promotionalemail"),response[44]);
					$("#currentdealer").val(response[27]);
					$("#fax").val(response[29]);
					
					$("#activecustomer").html(response[30]);
					autochecknew($("#companyclosed"),response[32]);
					$("#branch").val(response[31]);
					$("#createddate").html(response[20]);
					
					$('#tabgroupgridc4').hide();
					if(response[23] == 'yes')
					{ 
						generatecustomerregistration(response[0],''); 
						enableregistration();
					}
					if(response[24] == 'yes') { generatecustomerregistration(response[0],''); 	enableregistration(); }
					if(response[25] == '1') { enabledelete(); }
					if(response[28] == 'yes' && response[1] != '' )
					{
						$('#displaypassworddfield').show();
						$('#resetpwd').hide();
					}
					else
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
					}
					
					var countrow = response[37].split('****');
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
						$('#'+value).html(slno);
						
					}
				
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
				
			else
				$("#customerselectionprocess").html(scripterror());
		 }
		}
		ajaxcall6.send(passData);
	}
}

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#filterdiv').hide();
	$('#tabgroupgridwb1').html('');
	customerdetailstoform(selectbox);	
	$('#hiddenregistrationtype').val('newlicence');
	clearregistrationform(); 
	validatemakearegistration();   
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
				if(pattern.test(customerarray[i].toLowerCase()))
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
				if(pattern.test(customersearcharray[i].toLowerCase()))
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
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxcall7 = createajax();
	$("#filterdiv").hide();
	var queryString = "../ajax/customer.php";
	ajaxcall7.open("POST", queryString, true);
	ajaxcall7.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall7.onreadystatechange = function()
	{
		if(ajaxcall7.readyState == 4)
		{
			if(ajaxcall7.status == 200)
			{
				var ajaxresponse = ajaxcall7.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = (ajaxresponse).split("^"); //alert(response);
					if(response[0] == '')
					{
						
						alert('Customer Not Available.');
						$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						gridtabcus4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
						$("#tabgroupgridc1_1").html('No datas found to be displayed.');
						clearregistrationform();
						return false;
					} 
					generatecustomerregistration(response[0],'');//alert(response[40])
					$("#lastslno").val(response[0]);
					generatecustomerattachcards(response[0]);
					rereg_refreshcuscardarray(response[0]);
					if($("#resendmail")) 
						$('#resendmail').show();
					
					if(response[23] != 'yes') 
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
					$("#customerid").val(response[1]);
					$("#businessname").val(response[2]);
					$("#short_url").html(response[38] +"\r\n"+ response[2]+"\r\n"+ response[3]+"\n"+ response[4]+"\n"+ response[33]+"\n"+ response[34]+"\n" + response[7]+"\r\n"+ response[39]+"\n"+ response[40]+"\n"+ response[41]);
					$("#address").val(response[3]);
					$("#place").val(response[4]);
					$("#state").val(response[6]);
					getdistrict('districtcodedisplay', response[6]);
					$("#district").val(response[5]);
					$("#pincode").val(response[7]);
					$("#region").val(response[8]);
					$("#stdcode").val(response[9]);
					$("#website").val(response[10]);
					$("#category").val(response[11]);
					$("#type").val(response[12]);
					$("#remarks").val(response[13]);
					
					if(response[18] == '')
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
						$('#displaypassworddfield').hide();
					}else
					if(response[18] == 'n' )
					{
						$('#initialpassworddfield').show();
						$("#initialpassword").val(response[17]);
						$('#displayresetpwd').hide();
					}
					else if(response[18] == 'y')
					{
						if(response[28] == 'yes')
						{
							$('#displayresetpwd').show();
						}
						else
						{
							$('#displayresetpwd').hide();
						}
						$("#resetpassword").val(response[35]);
						$('#initialpassworddfield').hide();
					}
					autochecknew($("#disablelogin"),response[19]);
					autochecknew($("#corporateorder"),response[26]);
					autochecknew($("#isdealer"),response[36]);
					$("#currentdealer").val(response[27]);
					$("#fax").val(response[29]);
					
					$("#activecustomer").html(response[30]);
					autochecknew($("#companyclosed"),response[32]);
					$("#branch").val(response[31]);
					$("#createddate").html(response[20]);
					
					$('#tabgroupgridc4').hide();
					if(response[23] == 'yes')
					{ 
						generatecustomerregistration(response[0],''); 
						enableregistration();
					}
					if(response[24] == 'yes') { generatecustomerregistration(response[0],''); 	enableregistration(); }
					if(response[25] == '1') { enabledelete(); }
					if(response[28] == 'yes' && response[1] != '' )
					{
						$('#displaypassworddfield').show();
						$('#resetpwd').hide();
					}
					else
					{
						$('#initialpassworddfield').hide();
						$('#displayresetpwd').hide();
					}
					
					var countrow = response[37].split('****');
					$('#adddescriptionrows tr').remove();
					for(k=1;k<=countrow.length;k++)
					{
						slno = k;
						rowid = 'removedescriptionrow'+ slno;
						
						if(k == 10)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').hide();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else if(k == 1)
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general">General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						else
						{
							var value = 'contactname'+slno;
							$('#adddescriptionrowdiv').show();
							var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
						}
						$("#adddescriptionrows").append(row);
						$('#'+value).html(slno);
						
					}
				
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
			}
			else
				$('#form-error').html(scripterror());
		}
	}
	ajaxcall7.send(passData);
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
		ajaxcall8 = createajax();
		$('#reg-form-error').html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall8.open("POST", queryString, true);
		ajaxcall8.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall8.onreadystatechange = function()
		{
			if(ajaxcall8.readyState == 4)
			{
				if(ajaxcall8.status == 200)
				{
					
 					$('#reg-form-error').html('');
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					$("#transferimagespan").css({visibility:"visible"});

					var ajaxresponse = (ajaxcall8.responseText);
					if(ajaxresponse == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						var response = (ajaxresponse).split("^");
						if(response[0] == '1')
						{
							$('#cardnumberdisplay').html(response[1]);
							$('#scratchnodisplay').html(response[2]);
							$('#purchasetypedisplay').html(response[3]);
							$('#usagetypedisplay').html(response[4]);
							$('#attachedtodisplay').html(response[5]);
							$('#productdisplay').html(response[8] + ' [' + response[7] + ']');
							$('#registeredtodisplay').html(response[12]);
							$('#attachdatedisplay').html(response[9]);
							$('#registerdatedisplay').html(response[10]);
							$('#cardstatusdisplay').html(response[13]);
							$('#schemedisplay').html(response[14]);
							
							$('#delaerrep').val(response[6]);
							$('#productname').val(response[8] + ' [' + response[7] + ']');
							$('#productcode').val(response[7]);
							$('#tfpurchasetype').val(response[3]);
							$('#tfusagetype').val(response[4]);
							$('#tfdealer').val(response[5]);
							$('#tfproduct').val(response[8]);
							
						}
						else
						{
							$('#reg-form-error').html(errormessage('No datas found to be displayed.'));
						}
					}
					
				}
				else
					$('#reg-form-error').html(scripterror());
			}
		}
		ajaxcall8.send(passData);
	}
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
		ajaxcall9 = createajax();
		error.html(getprocessingimage());
		var queryString = "../ajax/customer.php"; 
		ajaxcall9.open("POST", queryString, true);
		ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall9.onreadystatechange = function()
		{
			if(ajaxcall9.readyState == 4)
			{
				if(ajaxcall9.status == 200)
				{
					error.html('');
					var response = (ajaxcall9.responseText).split("^"); alert(response)
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						if(response[0] == 2) 
						{ 
							error.html(errormessage(response[1])); $('#computerid').focus();
						}
						else
						{
							alert(response[1]);//response message when soft key is generated
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
				}
				else
					error.html(scripterror());
			}
		}
		ajaxcall9.send(passData);
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
		ajaxcall10 = createajax();
		error.html(getprocessingimage());
		var queryString = "../ajax/customer.php";
		ajaxcall10.open("POST", queryString, true);
		ajaxcall10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall10.onreadystatechange = function()
		{
			if(ajaxcall10.readyState == 4)
			{
				if(ajaxcall10.status == 200)
				{
					error.html('');
					var response = ajaxcall10.responseText;
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else
					{
						//error.innerHTML = successmessage(ajaxresponse);
						scratchdetailstoform($('#scratchcardlist').val());
						//$("#submitform" )[0].reset();
						displayelement('tabgroupgridc2','transferscratchcarddiv');
					}
				}
				else
					error.html(scripterror());
			}
		}
		ajaxcall10.send(passData);
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

/*function detailselection()
{
	document.getElementById('detailrecordselection').style.display='block'; 
	document.getElementById('simplerecordselection').style.display='none';  
	if(document.getElementById('listloadinghidden').value != '1') { getcustomerlist1(); }
	document.getElementById('searchtextid').value = '';
	document.getElementById('searchtext').value = '';
}

function simpleselection()
{
	document.getElementById('simplerecordselection').style.display='block'; 
	document.getElementById('detailrecordselection').style.display='none';
	document.getElementById('detailsearchtext').value = '';
	document.getElementById('detailcustomerlist').value = '';
}
*/

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
	ajaxcall11 = createajax();//alert(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall11.open("POST", queryString, true);
	ajaxcall11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall11.onreadystatechange = function()
	{
		if(ajaxcall11.readyState == 4)
		{
			if(ajaxcall11.status == 200)
			{
				var ajaxresponse = ajaxcall11.responseText; //alert(ajaxresponse);
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
			}
			else
				error.html(scripterror());
		}
	}
	ajaxcall11.send(passData);
}


function cardsearchfilter()
{
	var form = $('#cardsearchfilterform');
	var textfield = $('#cardsearchcriteria').val();
	var error = $('#filter-form-error');
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $('#searchcriteria').focus(); return false; }
	var passData = "switchtype=cardsearch&textfield=" + textfield + "&dummy=" + Math.floor(Math.random()*1000782200000);
	ajaxcall12 = createajax();
	var queryString = "../ajax/customer.php";
	error.html(getprocessingimage());
	ajaxcall12.open("POST", queryString, true);
	ajaxcall12.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall12.onreadystatechange = function()
	{
		if(ajaxcall12.readyState == 4)
		{
			if(ajaxcall12.status == 200)
			{
				var response = ajaxcall12.responseText;
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				error.html('');
				//gridtabcus4(3,'tabgroupgrid','&nbsp; &nbsp;Search Results');
				//document.getElementById('displaysearchresult').style.display = 'block';
				$('#displaysearchresult').html(ajaxresponse);
			}
			else
				error.html('Unable to Connect.');
		}
	}
	ajaxcall12.send(passData);
}



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
			ajaxcall13 = createajax();
			var queryString = "../ajax/customer.php";
			ajaxcall13.open("POST", queryString, true);
			ajaxcall13.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxcall13.onreadystatechange = function()
			{
				if(ajaxcall13.readyState == 4)
				{
					if(ajaxcall13.status == 200)
					{
						var response = ajaxcall13.responseText;//alert(response)
						if(response == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else
						{
							var response1 = response.split('^');
							if(response1[0] == '1')
							{
								error.html(successmessage(response1[1]));
							}
							else if(response1[0] == '2')
							{
								error.html(errormessage(response1[1]));
							}
						//error.innerHTML ='';
						}
					}
					else
						error.html(scripterror());
				}
			}
			ajaxcall13.send(passData);
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
	ajaxcall14 = createajax();
	//document.getElementById('tabgroupgridc4').innerHTML = getprocessingimage();
	//document.getElementById('tabgroupgridc1link').innerHTML ='';
	ajaxcall14.open("POST", queryString, true);
	ajaxcall14.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall14.onreadystatechange = function()
	{//alert(passData);
		if(ajaxcall14.readyState == 4)
		{
			if(ajaxcall14.status == 200)
			{
				var ajaxresponse = ajaxcall14.responseText;
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
						//document.getElementById('tabgroupgridwb1').innerHTML = "Total Count :  " + response[1];
						//document.getElementById('tabgroupgridc4').innerHTML =  response[1];
					//	gridtabcus4(5,'tabgroupgrid','&nbsp; &nbsp;Card Details');
					}
					else
					{
						$("#tabgroupgridc4").html(scripterror());
						//document.getElementById('tabgroupgridc4').innerHTML = scripterror();
					}
				}
			}
			else
				$("#tabgroupgridc4").html(scripterror());
		}
	}
	ajaxcall14.send(passData);
}

function cleargrid()
{
	$("#tabgroupgridc1_1").html('No datas found to be displayed.');
	$("#tabgroupgridc1link").html('');
	$("#regresultgrid").html('');
	$("#tabgroupgridwb1").html('');
	$("#tabgroupgridc4").html('No datas found to be displayed.');

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
			ajaxcall15 = createajax(); 
			var queryString = "../ajax/customer.php"; 
			ajaxcall15.open("POST", queryString, true); 
			ajaxcall15.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxcall15.onreadystatechange = function()
			{
				if(ajaxcall15.readyState == 4)
					{
						if(ajaxcall15.status == 200)
							{
								var response = ajaxcall15.responseText;
								if(response == 'Thinking to redirect')
								{
									window.location = "../logout.php";
									return false;
								}
								else
								{
									var response1 = response.split('^');
									if(response1[0] == '1')
									{
										$('#form-error').html('');
										if($('#initialpassworddfield').is(':visible'))
										{
											$('#initialpassworddfield').show();
											$('#displayresetpwd').hide();
											$('#initialpassword').val(response1[1]);
											$("#displaypassworddfield").show();
											$("#resetpwd").hide();
											
										}
										else
										{
											$('#initialpassword').val(response1[1]);
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
							}	
							else
								error.html(scripterror());
					}
			}
			ajaxcall15.send(passData);

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
	ajaxcall14 = createajax();
	$('#rcidetailsgridc1_1').html(getprocessingimage());
	$('#rcidetailsgridc1link').html('');
	ajaxcall14.open("POST", queryString, true);
	ajaxcall14.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall14.onreadystatechange = function()
	{
		if(ajaxcall14.status == 200)
			{
				var ajaxresponse = ajaxcall14.responseText;
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
				
			}
		else
			$('#rcidetailsgridc1_1').html(scripterror());
	}
	ajaxcall14.send(passData);
}

//Function for "show more records" link - to get registration records
function getmorercidetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=rcidetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	ajaxcall15 = createajax();
	$('#rcidetailsgridc1link').html(getprocessingimage());
	ajaxcall15.open("POST", queryString, true);
	ajaxcall15.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall15.onreadystatechange = function()
	{
		if(ajaxcall15.readyState == 4)
		{
			if(ajaxcall15.status == 200)
			{
				var ajaxresponse = ajaxcall15.responseText;
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
			}
			else
				$('rcidetailsgridc1_1').html(scripterror());
		}
	}
	ajaxcall15.send(passData);
}




function generateinvoicedetails(startlimit)
{
	
	var form = $('#submitform');
	$('#invoicedetailsgridc1').show();
	$("#hiddeninvoiceslno").val($("#lastslno").val()); 
	//$('#detailsdiv').hide();
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
	var queryString = "../ajax/customer.php";
	ajaxcall41 = createajax();
	$('#invoicedetailsgridc1_1').html(getprocessingimage());
	$('#invoicedetailsgridc1link').html('');
	ajaxcall41.open("POST", queryString, true);
	ajaxcall41.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall41.onreadystatechange = function()
	{
		if(ajaxcall41.status == 200)
			{
				var ajaxresponse = ajaxcall41.responseText;
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
						$('#invoicelastslno').val(response[4]);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
				
			}
		else
			$('#invoicedetailsgridc1_1').html(scripterror());
	}
	ajaxcall41.send(passData);
}

//Function for "show more records" link - to get registration records
function getmoreinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/customer.php";
	ajaxcall51 = createajax();
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall51.open("POST", queryString, true);
	ajaxcall51.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall51.onreadystatechange = function()
	{
		if(ajaxcall51.readyState == 4)
		{
			if(ajaxcall51.status == 200)
			{
				var ajaxresponse = ajaxcall51.responseText;
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
						$('#invoicelastslno').val(response[4]);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
			}
			else
				$('#invoicedetailsgridc1_1').html(scripterror());
		}
	}
	ajaxcall51.send(passData);
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice()
{
	var form = $('#detailsform');	
	if($('#invoicelastslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#detailsform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#detailsform').attr( 'target', '_blank' );
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
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td  width="5%"><div align="left"><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td><td width="11%"><div align="center"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:110px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></div></td><td width="16%"><div align="center"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"  style="width:115px"  maxlength="130"  autocomplete="off"/></div></td><td width="18%"><div align="center"><input name="phone'+ slno+'" type="text"class="swifttext" id="phone'+ slno+'" style="width:110px" maxlength="100"  autocomplete="off" /></div></td><td width="15%"><div align="center"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'" style="width:100px"  maxlength="10"  autocomplete="off"/></div></td><td width="27%"><div align="center"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'" style="width:140px"  maxlength="200"  autocomplete="off"/></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td></tr>';
	
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
		$("").colorbox({ inline:true, href:"#rcidatagrid" });
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
		$("").colorbox({ inline:true, href:"#invoicedetailsgrid" });
	}
	else
	{
		return false;
	}
}