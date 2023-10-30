var userarray = new Array();


function refreshuserarray()
{
	var form = $('#filterform');
	var login_type = $("input[name='login_type']:checked").val();
	var passData = "type=generateuserlist&login_type=" + encodeURIComponent(login_type)  +  "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$('#userselectionprocess').html(getprocessingimage());
	queryString = "../ajax/usereditor.php";
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
				var response = ajaxresponse.split('^*^');
				userarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					userarray[i] = response[i];
				}
				getuserlist();
				$('#userselectionprocess').html('');
				$('#displayfilter').hide();
				$('#totalcount').html(userarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#userselectionprocess").html(scripterror());
		}
	});		
}


function getuserlist()
{	
	var form = $('#submitform');
	var selectbox = $('#userlist');
	var numberofusers = userarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofusers > actuallimit)?actuallimit:numberofusers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = userarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}


function selectfromlist()
{
	var selectbox = $("#userlist option:selected").val();
	$('#detailsearchtext').val($("#userlist option:selected").text());
	$('#detailsearchtext').select();
	userdetailstoform(selectbox);	
}


function selectauser(input)
{
	var selectbox = $('#userlist');
	
	if(input == "")
	{
		getuserlist();
	}
	else
	{
		
		$('option', selectbox).remove();
		var options = selectbox.attr('options');

		var addedcount = 0;
		for( var i=0; i < userarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = userarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = userarray[i];
				}
				var result1 = pattern.test(trimdotspaces(userarray[i]).toLowerCase());
				var result2 = pattern.test(userarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = userarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}




function usersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38) { 
		scrolluser('up');
		}
	else if(KeyID == 40)
	{
		scrolluser('down');
	}
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectauser(input);
	}
}

function scrolluser(type)
{	
	var selectbox = $('#userlist');
	var totalcus = $("#userlist option").length;
	var selectedcus = $("select#userlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#userlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#userlist").attr('selectedIndex', selectedcus + 1)
	selectfromlist();
}

function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#username');
	if(!field.val()) {error.html(errormessage('Enter the Username.')); field.focus(); return false; }
	var field = $('#cellno');
	if(field.val()) { if(!validatecell(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
	var field = $('#emailid');
	if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
	if($('#registration').is(':checked') == true) var registration = 'yes'; else var registration = 'no';
	if($('#withoutscratchcard').is(':checked')== true) var withoutscratchcard = 'yes'; else var withoutscratchcard = 'no';
	if($('#dealer').is(':checked')== true) var dealer = 'yes'; else var dealer = 'no';
	if($('#bills').is(':checked') == true) var bills = 'yes'; else var bills = 'no';
	if($('#credits').is(':checked') == true) credits = 'yes'; else credits = 'no';
	if($('#editcustomercontact').is(':checked') == true) var editcustomercontact = 'yes'; else var editcustomercontact = 'no';
	if($('#products').is(':checked') == true) var products = 'yes'; else var products = 'no';
	if($('#mergecustomer').is(':checked') == true)var mergecustomer = 'yes'; else var mergecustomer = 'no';
	if($('#blockcancel').is(':checked') == true) var blockcancel = 'yes'; else var blockcancel = 'no';
	if($('#transfercard').is(':checked') == true) var transfercard = 'yes'; else var transfercard = 'no';
	if($('#disablelogin').is(':checked') == true) var disablelogin = 'yes'; else var disablelogin = 'no';
	if($('#regreports').is(':checked') == true) var regreports = 'yes'; else var regreports = 'no';
	if($('#contactdetails').is(':checked') == true) var contactdetails = 'yes'; else var contactdetails = 'no';
	if($('#dealerreports').is(':checked') == true) var dealerreports = 'yes'; else var dealerreports = 'no';
	if($('#productshipped').is(':checked') == true) var productshipped = 'yes'; else var productshipped = 'no';
	if($('#invoicedetails').is(':checked') == true) var invoicedetails = 'yes'; else var invoicedetails = 'no';
	if($('#updationduedetails').is(':checked') == true) var updationduedetails = 'yes'; else var updationduedetails = 'no';
	if($('#editcustomerpassword').is(':checked') == true) var editcustomerpassword = 'yes'; else var editcustomerpassword = 'no';
	if($('#editdealerpassword').is(':checked') == true) var editdealerpassword = 'yes'; else var editdealerpassword = 'no';
	if($('#customerpendingrequest').is(':checked') == true) var customerpendingrequest = 'yes'; else var customerpendingrequest = 'no';
	if($('#dealerpendingrequest').is(':checked') == true)var dealerpendingrequest = 'yes'; else var dealerpendingrequest = 'no';
	if($('#cusattachcard').is(':checked') == true) var cusattachcard = 'yes'; else var cusattachcard = 'no';
	if($('#hardwarelock').is(':checked') == true) var hardwarelock = 'yes'; else var hardwarelock = 'no';
	if($('#districtmapping').is(':checked') == true) var districtmapping = 'yes'; else var districtmapping = 'no';
	if($('#customerpayment').is(':checked') == true) var customerpayment = 'yes'; else var customerpayment = 'no';
	if($('#welcomemail').is(':checked') == true) var welcomemail = 'yes'; else var welcomemail = 'no';
	if($('#scheme').is(':checked') == true) var scheme = 'yes'; else var scheme = 'no';
	if($('#schemepricing').is(':checked') == true) var schemepricing = 'yes'; else var schemepricing = 'no';
	if($('#producttodealer').is(':checked') == true)var producttodealer = 'yes'; else var producttodealer = 'no';
	if($('#producttodealers').is(':checked') == true)var producttodealers = 'yes'; else var producttodealers = 'no';
	if($('#schemetodealer').is(':checked') == true) var schemetodealer = 'yes'; else var schemetodealer = 'no';
	if($('#smscreditstocustomers').is(':checked') == true) var smscreditstocustomers = 'yes'; else var smscreditstocustomers = 'no';
	if($('#smscreditstodealer').is(':checked') == true) var smscreditstodealer = 'yes'; else var smscreditstodealer = 'no';
	if($('#smsaccounttocustomers').is(':checked') == true) var smsaccounttocustomers = 'yes'; else var smsaccounttocustomers = 'no';
	if($('#smsaccounttodealer').is(':checked') == true) var smsaccounttodealer = 'yes'; else var smsaccounttodealer = 'no';
	if($('#smscreditssummary').is(':checked') == true) var smscreditssummary = 'yes'; else var smscreditssummary = 'no';
	if($('#smsreceiptstocustomers').is(':checked')  == true) var smsreceiptstocustomers = 'yes'; else var smsreceiptstocustomers = 'no';
	if($('#smsreceiptstodealers').is(':checked') == true) var smsreceiptstodealers = 'yes'; else var smsreceiptstodealers = 'no';
	if($('#pinnoattachedreport').is(':checked') == true) var pinnoattachedreport = 'yes'; else var pinnoattachedreport = 'no';
	if($('#suggestedmerging').is(':checked') == true) var suggestedmerging = 'yes'; else var suggestedmerging = 'no';
	if($('#labelprint').is(':checked') == true) var labelprint = 'yes'; else var labelprint = 'no';
	if($('#viewinvoice').is(':checked') == true) var viewinvoice = 'yes'; else var viewinvoice = 'no';
	if($('#updationsummaryreport').is(':checked') == true) var updationsummaryreport = 'yes'; else var updationsummaryreport = 'no';
	if($('#salessummaryreport').is(':checked') == true) var salessummaryreport = 'yes'; else var salessummaryreport = 'no';
	if($('#viewrcidata').is(':checked') == true) var viewrcidata = 'yes'; else var viewrcidata = 'no';
	if($('#crossproductreport').is(':checked') == true) var crossproductreport = 'yes'; else var crossproductreport = 'no';
	if($('#updationdetailedreport').is(':checked') == true) var updationdetailedreport = 'yes'; else var updationdetailedreport = 'no';
	if($('#crossproductsales').is(':checked') == true) var crossproductsales = 'yes'; else var crossproductsales = 'no';
	if($('#invoicing').is(':checked') == true) var invoicing = 'yes'; else var invoicing = 'no';
	if($('#invoice_register').is(':checked') == true) var invoice_register = 'yes'; else var invoice_register = 'no';
	if($('#outstanding_register').is(':checked') == true) var outstanding_register = 'yes'; else var outstanding_register = 'no';
	if($('#receipt_register').is(':checked') == true) var receipt_register = 'yes'; else var receipt_register = 'no';
	if($('#manageinvoice').is(':checked') == true) var manageinvoice = 'yes'; else var manageinvoice = 'no';
	if($('#bulkprintinvoice').is(':checked') == true) var bulkprintinvoice = 'yes'; else var bulkprintinvoice = 'no';
	if($('#masterimplementation').is(':checked') == true) var masterimplementation = 'yes'; else var masterimplementation = 'no';
	if($('#createimplementation').is(':checked') == true) var createimplementation = 'yes'; else var createimplementation = 'no';
	if($('#reregistration').is(':checked') == true) var reregistration = 'yes'; else var reregistration = 'no';
	if($('#impsummaryreport').is(':checked') == true) var impsummaryreport = 'yes'; else var impsummaryreport = 'no';
	if($('#datainaccuracyreport').is(':checked') == true) var datainaccuracyreport = 'yes'; else var datainaccuracyreport = 'no';
	if($('#impstatusreport').is(':checked') == true) var impstatusreport = 'yes'; else var impstatusreport = 'no';
	if($('#receiptreconsilation').is(':checked') == true) var receiptreconsilation = 'yes'; else var receiptreconsilation = 'no';
	if($('#activitylog').is(':checked') == true) var activitylog = 'yes'; else var activitylog = 'no';
	if($('#notregisteredreport').is(':checked') == true) var notregisteredreport = 'yes'; else var notregisteredreport = 'no';
	if($('#categorysummary').is(':checked') == true) var categorysummary = 'yes'; else var categorysummary = 'no';
	if($('#addinvoice').is(':checked') == true) var addinvoice = 'yes'; else var addinvoice = 'no';
	if($('#addbills').is(':checked') == true) var addbills = 'yes'; else var addbills = 'no';
	var field = $('#fullname');
	if(!field.val()) { error.html(errormessage('Enter the Full Name.')); field.focus(); return false; }
	else
	{
		var passData = "";

		if(command == 'save')
		{
			passData =  "type=save&username=" + encodeURIComponent($('#username').val()) + "&fullname=" + encodeURIComponent($('#fullname').val()) + "&password=" + encodeURIComponent($('#password').val()) + "&description=" + encodeURIComponent($('#description').val())+ "&cellno=" + encodeURIComponent($('#cellno').val()) + "&emailid=" + encodeURIComponent($('#emailid').val())+ "&registration=" + encodeURIComponent(registration) + "&withoutscratchcard=" + encodeURIComponent(withoutscratchcard) + "&dealer=" + encodeURIComponent(dealer) + "&bills=" + encodeURIComponent(bills) + "&credits=" + encodeURIComponent(credits) + "&editcustomercontact=" + encodeURIComponent(editcustomercontact)  + "&products=" + encodeURIComponent(products)  + "&mergecustomer=" + encodeURIComponent(mergecustomer)  + "&blockcancel=" + encodeURIComponent(blockcancel) + "&disablelogin=" + encodeURIComponent(disablelogin) + "&regreports=" + encodeURIComponent(regreports) + "&contactdetails=" + encodeURIComponent(contactdetails) + "&dealerreports=" + encodeURIComponent(dealerreports) + "&productshipped=" + encodeURIComponent(productshipped) + "&invoicedetails=" + encodeURIComponent(invoicedetails) + "&updationduedetails=" + encodeURIComponent(updationduedetails) + "&editcustomerpassword=" + encodeURIComponent(editcustomerpassword)+ "&editdealerpassword=" + encodeURIComponent(editdealerpassword) + "&customerpendingrequest=" + encodeURIComponent(customerpendingrequest) + "&dealerpendingrequest=" + encodeURIComponent(dealerpendingrequest) + "&createddate=" + encodeURIComponent(document.getElementById('createddate').innerHTML) + "&transfercard=" + encodeURIComponent(transfercard) + "&cusattachcard=" + encodeURIComponent(cusattachcard) + "&hardwarelock=" + encodeURIComponent(hardwarelock)+ "&customerpayment=" + encodeURIComponent(customerpayment)+ "&welcomemail=" + encodeURIComponent(welcomemail) + "&schemetodealer=" + encodeURIComponent(schemetodealer) + "&producttodealer=" + encodeURIComponent(producttodealer)+ "&producttodealers=" + encodeURIComponent(producttodealers)  + "&schemepricing=" + encodeURIComponent(schemepricing)+ "&scheme=" + encodeURIComponent(scheme) +"&districtmapping=" + encodeURIComponent(districtmapping) +"&smscreditstocustomers=" + encodeURIComponent(smscreditstocustomers) +"&smscreditstodealer=" + encodeURIComponent(smscreditstodealer) +"&smsaccounttocustomers=" + encodeURIComponent(smsaccounttocustomers) +"&smsaccounttodealer=" + encodeURIComponent(smsaccounttodealer) +"&smscreditssummary=" + encodeURIComponent(smscreditssummary)+"&smsreceiptstocustomers=" + encodeURIComponent(smsreceiptstocustomers)+"&smsreceiptstodealers=" + encodeURIComponent(smsreceiptstodealers)+"&pinnoattachedreport=" + encodeURIComponent(pinnoattachedreport) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&suggestedmerging=" + encodeURIComponent(suggestedmerging) + "&labelprint=" + encodeURIComponent(labelprint) + "&viewinvoice=" + encodeURIComponent(viewinvoice)  + "&updationsummaryreport=" + encodeURIComponent(updationsummaryreport)+ "&salessummaryreport=" + encodeURIComponent(salessummaryreport)+ "&viewrcidata=" + encodeURIComponent(viewrcidata)+ "&crossproductreport=" + encodeURIComponent(crossproductreport)+ "&updationdetailedreport=" + encodeURIComponent(updationdetailedreport) +"&crossproductsales=" + encodeURIComponent(crossproductsales)+"&invoicing=" + encodeURIComponent(invoicing)+"&invoice_register=" + encodeURIComponent(invoice_register)+"&outstanding_register=" + encodeURIComponent(outstanding_register)+"&receipt_register=" + encodeURIComponent(receipt_register)+"&manageinvoice=" + encodeURIComponent(manageinvoice)+"&bulkprintinvoice=" + encodeURIComponent(bulkprintinvoice)+"&masterimplementation=" + encodeURIComponent(masterimplementation)+"&createimplementation=" + encodeURIComponent(createimplementation)+"&reregistration=" + encodeURIComponent(reregistration)+"&impsummaryreport=" + encodeURIComponent(impsummaryreport)+"&datainaccuracyreport=" + encodeURIComponent(datainaccuracyreport)+"&impstatusreport=" + encodeURIComponent(impstatusreport)+"&receiptreconsilation=" + encodeURIComponent(receiptreconsilation)+"&activitylog=" + encodeURIComponent(activitylog)+"&notregisteredreport=" + encodeURIComponent(notregisteredreport)+"&categorysummary=" + encodeURIComponent(categorysummary)+"&addinvoice=" + encodeURIComponent(addinvoice)+"&addbills=" + encodeURIComponent(addbills)+ "&dummy=" + Math.floor(Math.random()*100000000);
			//alert(passData);
		}
		else
		{
			passData =  "type=delete&lastslno=" + $('#lastslno').val() + "&dummy=" + Math.floor(Math.random()*10000000000);//alert(passData)
		}
		queryString = '../ajax/usereditor.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
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
						error.html(successmessage(response[1]));
						refreshuserarray();
						newentry();
					}
					else if(response[0] == '2')
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
}


function userdetailstoform(userid)
{
	if(userid != '')
	{
		$('#form-error').html('');
		var form = $('#submitform');
		var passData = "type=userdetailstoform&lastslno=" + encodeURIComponent(userid) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#form-error').html('<img src="../images/imax-loading-image.gif" border="0"/>');
		var queryString = "../ajax/usereditor.php";
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
					$('#form-error').html('');
					var response = ajaxresponse.split("^");
					$('#lastslno').val(response[0]);
					$('#username').val(response[1]);
					$('#fullname').val(response[2]);
					$('#description').val(response[4]);
					autochecknew($('#registration'),response[5]);
					autochecknew($('#withoutscratchcard'),response[6]);
					autochecknew($('#dealer'),response[7]);
					autochecknew($('#bills'),response[8]);
					autochecknew($('#credits'),response[9]);
					autochecknew($('#editcustomercontact'),response[10]);
					autochecknew($('#products'),response[11]);
					autochecknew($('#mergecustomer'),response[12]);
					autochecknew($('#blockcancel'),response[13]);
					autochecknew($('#transfercard'),response[14]);
					autochecknew($('#disablelogin'),response[15]);
					autochecknew($('#regreports'),response[17]);
					autochecknew($('#contactdetails'),response[18]);
					autochecknew($('#dealerreports'),response[19]);
					autochecknew($('#productshipped'),response[20]);
					autochecknew($('#invoicedetails'),response[21]);
					autochecknew($('#updationduedetails'),response[22]);
					autochecknew($('#editcustomerpassword'),response[23]);
					$('#cellno').val(response[24]);
					$('#emailid').val(response[25]);
					autochecknew($('#customerpendingrequest'),response[26]);
					autochecknew($('#dealerpendingrequest'),response[27]);
					autochecknew($('#cusattachcard'),response[28]);
					autochecknew($('#hardwarelock'),response[29]);
					autochecknew($('#districtmapping'),response[30])
					autochecknew($('#customerpayment'),response[31])
					autochecknew($('#welcomemail'),response[32])
					autochecknew($('#scheme'),response[33]);
					autochecknew($('#schemepricing'),response[34]);
					autochecknew($('#producttodealer'),response[35]);
					autochecknew($('#producttodealers'),response[36]);
					autochecknew($('#schemetodealer'),response[37]);
					autochecknew($('#smscreditstocustomers'),response[38]);
					autochecknew($('#smscreditstodealer'),response[39]);
					autochecknew($('#smsaccounttocustomers'),response[40]);
					autochecknew($('#smsaccounttodealer'),response[41]);
					autochecknew($('#smscreditssummary'),response[44]);
					autochecknew($('#smsreceiptstocustomers'),response[45]);
					autochecknew($('#smsreceiptstodealers'),response[46]);
					autochecknew($('#editdealerpassword'),response[47]);
					autochecknew($('#pinnoattachedreport'),response[48]);
					autochecknew($('#suggestedmerging'),response[49]);
					autochecknew($('#labelprint'),response[50]);
					autochecknew($('#viewinvoice'),response[51]);
					autochecknew($('#updationsummaryreport'),response[52]);
					autochecknew($('#salessummaryreport'),response[53]);
					autochecknew($('#viewrcidata'),response[54]);
					autochecknew($('#crossproductreport'),response[55]);
					autochecknew($('#updationdetailedreport'),response[56]);
					autochecknew($('#crossproductsales'),response[57]);
					autochecknew($('#invoicing'),response[58]);
					autochecknew($('#invoice_register'),response[59]);
					autochecknew($('#receipt_register'),response[60]);
					autochecknew($('#outstanding_register'),response[61]);
					autochecknew($('#manageinvoice'),response[62]);
					autochecknew($('#bulkprintinvoice'),response[63]);
					autochecknew($('#masterimplementation'),response[64]);
					autochecknew($('#createimplementation'),response[65]);
					autochecknew($('#reregistration'),response[66]);
					autochecknew($('#impsummaryreport'),response[67]);
					autochecknew($('#datainaccuracyreport'),response[68]);
					autochecknew($('#impstatusreport'),response[69]);
					autochecknew($('#receiptreconsilation'),response[70]);
					autochecknew($('#activitylog'),response[71]);
					autochecknew($('#notregisteredreport'),response[72]);
					autochecknew($('#categorysummary'),response[73]);
					autochecknew($('#addinvoice'),response[74]);
					autochecknew($('#addbills'),response[75]);
					if(response[42] == 'n' )
					{
						$('#initialpassworddfield').show();
						$('#initialpassword').val(response[43]); 
						$('#displayresetpwd').hide();
					}
					else
					{
						$('#displayresetpwd').show();
						$('#resetpassword').val(response[43]); 
						$('#initialpassworddfield').hide();
					}
					$('#displaypassworddfield').show();
					$('#resetpwd').hide();
					$('#createddate').html(response[16]);
					if(response[0] == '1')
					{
						disableformelemnts(); disabledelete();
					}
					else
					{
						enableformelemnts();enabledelete();
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

function newentry()
{
	$('#submitform')[0].reset();
	$('#createddate').html('');
	$('#lastslno').val('');
	$('#displaypassworddfield').hide();
	$('#resetpwd').hide();
	
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

function validatepwd()
{ 
	var form = $('#submitform'); 
	var lastslno = $('#lastslno').val();
	var username = $('#username').val();
	var error = $('#form-error');
	var field = $('#password');
	if(!field.val()){error.html(errormessage("Enter the Password")); return false; field.focus(); }
	else
	if(lastslno != '')
	{
		var confirmation = confirm("Do you really want to reset the User login password for " + username + " ??");
		if(confirmation)
		{
			var passData  = "type=resetpwd&password=" + encodeURIComponent($('#password').val()) + "&lastslno=" + lastslno + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData) 
			var queryString = "../ajax/usereditor.php"; 
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
						var response = ajaxresponse;
						$('#form-error').html('');
						if($('#initialpassworddfield').is(':visible'))
						{
							$('#initialpassword').val(response);
							$("#displaypassworddfield").show();
							$("#resetpwd").hide();
						}
						else
						{
							$("#initialpassworddfield").show();
							$("#displayresetpwd").hide();
							$('#initialpassword').val(response);
							$("#displaypassworddfield").show();
							$("#resetpwd").hide();
						}
						$('#form-error').html(successmessage('Password Updated Successfully'));
						$('#password').val('');
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});		
		}
		else
		error.html('');
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