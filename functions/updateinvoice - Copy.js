
var customerarray = new Array();


// display the customer list
function refreshinvoicenoarray()
{
	var passData = "switchtype=generateinvoicenolist&dummy=" + Math.floor(Math.random()*10054300000);alert(passData)
	$("#customerselectionprocess").html(getprocessingimage());
	queryString = "../ajax/updateinvoice.php";
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
				var response = ajaxresponse;alert(response)
				customerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i]; 
				}
				getcustomerlist1();
				$("#customerselectionprocess").html(successsearchmessage('All Customers...'))
				$("#totalcount").html(customerarray.length);
				flag = true;
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


function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#invoicenumber').html($("#customerlist option:selected").text());
	$('#displaydiv').hide();
	$('#displaydetails2').hide();
	$('#filterdiv').hide();
	$('#displaydetails1').show();
	enablebuttontype();
	listinvoicedetails('display',selectbox);
	
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
						pattern = new RegExp("\/*" + withoutspace.toLowerCase());
						comparestringsplit = customerarray[i].split("^");
						comparestring = comparestringsplit[0];
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
						pattern = new RegExp("\/*" + withoutspace.toLowerCase());
						comparestringsplit = customersearcharray[i].split("^");
						comparestring = comparestringsplit[0];
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


function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		invoicetabclose();
		searchbycustomerid(input);
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

function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
}


//Function for grid to form ------------------------------------------
function listinvoicedetails(type,invoiceno)
{
	enableformelemnts();
	$('#displaybutton').hide();
	//disablebuttontype()
	var form = $('#submitform');
	var passData = "switchtype=listinvoicedetails&slno="+ encodeURIComponent(invoiceno) +"&type="+ encodeURIComponent(type);//alert(passData)
	$('#form-error').html(getprocessingimage());
	var queryString = "../ajax/updateinvoice.php";
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
					if(response['errorcode'] == 1)
					{
						$('#form-error').html('');
						$('#displaydiv').show();
						$('#invoicedetailsdisplay').html(response['grid']);
						$('#remarksdisplay').html(response['statusremarks']);
						$('#statusresult').html(response['status']);
						$('#lastslno').val(response['slno']);
						$('#productcodevalues').val(response['productcode']);
						$('#displaycompanyname').html(response['businessname']);
						$('#dealervalue').val(response['dealerid']);
						if(response['status'] == 'CANCELLED')
						{
							disablebuttontype();
							$('#displaybutton').hide();
						}
						
					}
					else
						$('#form-error').html(scripterror());
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});
}


//Change purchase type
function editpurchasetype(purchasetypehtml,purchasetypehidden)
{
	if($('#'+purchasetypehidden).val() == 'new')
	{
		$('#'+purchasetypehtml).html('Updation');
		$('#'+purchasetypehidden).val('updation');
	}
	else
	{
		$('#'+purchasetypehtml).html('New');
		$('#'+purchasetypehidden).val('new');
	}
}

//Change Usagetype
function editusagetype(usagetypehtml,usagetypehidden)
{
	if($('#'+usagetypehidden).val() == 'singleuser')
	{
		$('#'+usagetypehtml).html('Multiuser');
		$('#'+usagetypehidden).val('multiuser');
	}
	else if($('#'+usagetypehidden).val() == 'multiuser')
	{
		$('#'+usagetypehtml).html('Add License');
		$('#'+usagetypehidden).val('addlicense');
	}
	else if($('#'+usagetypehidden).val() == 'addlicense')
	{
		$('#'+usagetypehtml).html('Singleuser');
		$('#'+usagetypehidden).val('singleuser');
	}
}


function disablebuttontype()
{
	$('#edit').attr("disabled", true); 
	$('#edit').removeClass('swiftchoicebuttonbig');	
	$('#edit').addClass('swiftchoicebuttondisabledbig');
	
	$('#cancelinvoice').attr("disabled", true); 
	$('#cancelinvoice').removeClass('swiftchoicebuttonbignew');	
	$('#cancelinvoice').addClass('swiftchoicebuttondisabledbignew');

}

function enablebuttontype()
{
	$('#edit').attr("disabled", false); 
	$('#edit').removeClass('swiftchoicebuttondisabledbig');	
	$('#edit').addClass('swiftchoicebuttonbig');
	
	$('#cancelinvoice').attr("disabled", false); 
	$('#cancelinvoice').removeClass('swiftchoicebuttondisabledbignew');	
	$('#cancelinvoice').addClass('swiftchoicebuttonbignew');
	
}



function formsubmit(command)
{
	var form = $('#submitform');
	var passData = "";
	var error = $('#form-error',form);
	if(command == 'edit')
	{
		if(!$('#editremarks').val()) { alert("Enter the Edit Remarks"); $('#editremarks').focus(); return false; }
		var purchasearrray = new Array();
		var purchasevalues = new Array();
		var usagearrray = new Array();
		var usagevalues = new Array();
		var productamountarrray = new Array();
		var productamountvalues = new Array();
		var productslnoarrray = new Array();
		var productslnovalues = new Array();
		var serviceslnoarrray = new Array();
		var serviceslnovalues = new Array();
		var serviceamountarrray = new Array();
		var serviceamountvalues = new Array();
		

		var form1 = $('#colorform2');
		var invoiceno = $('#lastslno',form).val();
		
		var field = $('#businessname',form);
		if(!field.val()) { alert("Enter the Business Name [Company]. "); field.focus();$().colorbox.close(); return false; }
		if(field.val()) { if(!validatebusinessname(field.val())) { alert('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.'); field.focus();$().colorbox.close(); return false; } }
		
		var address = $('#address',form).val();
		var field = $('#contactperson',form);
		if(!field.val()) { alert("Enter the Contact person. "); field.focus();$().colorbox.close(); return false; }
		if(field.val()) { if(!contactpersonvalidate(field.val())) { alert('Contact person name contains special characters. Please use only Numeric / space.'); field.focus();$().colorbox.close(); return false; } }
		
		var field = $('#emailid',form);
		if(!field.val()) { alert("Enter the Email ID. "); field.focus();$().colorbox.close(); return false; }
		if(field.val()) { if(!checkemail(field.val())) { alert('Enter Only One valid Email Id.'); field.focus();$().colorbox.close(); return false; } }
		
		var field = $('#phone',form);
		if(!field.val()) { alert("Enter the Phone Number. "); field.focus();$().colorbox.close(); return false; }
			if(field.val()) { if(!validatephone(field.val())) { alert('Enter Only One valid Phone Number.'); field.focus();$().colorbox.close(); return false; } }
		
		var field = $('#cell',form);
		if(!field.val()) { alert("Enter the Cell. "); field.focus();$().colorbox.close(); return false; }
		if(field.val()) { if(!cellvalidation(field.val())) { alert('Enter Only One valid Cell Number.'); field.focus();$().colorbox.close(); return false; } }
		
		var field = $('#selectdealer',form);
		if(!field.val()) { alert("Select a Dealer name. "); field.focus(); $().colorbox.close();return false; }

		var count = $('#seletedproductgrid',form).length;
		var rowcount = $('#adddescriptionrows',form).length;
		for(i=0,j=1; i<count; i++,j++)
		{	
			if($("#itemtype"+ j,form).val() == 'product')
			{
				purchasearrray[i] = 'purchasetypehidden' + j;
				purchasevalues[i] = document.getElementById(purchasearrray[i],form).value;
				usagearrray[i] = 'usagetypehidden' + j;
				usagevalues[i] = document.getElementById(usagearrray[i],form).value;
				productslnoarrray[i] = 'slno' + j;
				productslnovalues[i] = document.getElementById(productslnoarrray[i],form).innerHTML;
				productamountarrray[i] = 'productamount' + j;
				productamountvalues[i] = document.getElementById(productamountarrray[i],form).value;
				var field = document.getElementById(productamountarrray[i],form);
			}
			else
			{
				if(serviceamountvalues == '')
					serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j,form).value;
				else
					serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j,form).value;
				serviceslnoarrray[i] = 'serviceslno' + j;
				serviceslnovalues[i] = document.getElementById(serviceslnoarrray[i],form).innerHTML;

			}
		}
		var descriptiontypearrray = new Array();
		var descriptiontypevalues = new Array();
		var descriptionarrray = new Array();
		var descriptionvalues = new Array();
		var descriptionamountarrray = new Array();
		var descriptionamountvalues = new Array();
		for(i=1,j=1; i<=rowcount,j<=rowcount; i++,j++)
		{	
			if($('#'+'descriptiontype' + j,form).html() != '')
			{
				if(descriptiontypearrray == '')
					descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
				else
					descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
				if(descriptiontypevalues == '')
					descriptiontypevalues = descriptiontypevalues + ($('#'+'descriptiontype' + j,form).html()).toLowerCase();
				else
					descriptiontypevalues = descriptiontypevalues + '~' + ($('#'+'descriptiontype' + j,form).html()).toLowerCase();
					
				if(descriptionarrray == '')
					descriptionarrray = descriptionarrray + 'description' + j;
				else
					descriptionarrray = descriptionarrray + '~' + 'description' + j;
				
				if(descriptionvalues == '')
					descriptionvalues = descriptionvalues + $('#'+'description' + j,form).val();
				else
					descriptionvalues = descriptionvalues + '~' + $('#'+'description' + j,form).val();
					
				if(descriptionamountarrray == '')
					descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
				else
					descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
				
				if(descriptionamountvalues == '')
					descriptionamountvalues =descriptionamountvalues + $('#'+'descriptionamount' + j,form).val();
				else
					descriptionamountvalues = descriptionamountvalues + '~' + $('#'+'descriptionamount' + j,form).val();
					
			}
		}
		var productslnoarray=document.getElementsByName("productslnoarrayhidden[]");
		var cardarray=document.getElementsByName("productselectedhidden[]");
		var productcodevalues = '';
		for(j=0;j<productslnoarray.length;j++)
		{
			var field = $('#'+'productselectvalue'+productslnoarray[j].value,form);
			if(!field.val()) { alert("Select a Product. "); field.focus(); $().colorbox.close();return false; }
			
			productcodevalues += $('#'+'productselectvalue'+productslnoarray[j].value,form).val()  + '#';
		}
		var productcodelist = productcodevalues.substring(0,(productcodevalues.length-1));//alert(productamountvalues);
		
		var cardvalues = '';
		for(i=0;i<cardarray.length;i++)
		{
			cardvalues += cardarray[i].value  + '#';
		}
		var cardlist = cardvalues.substring(0,(cardvalues.length-1));//alert(productamountvalues);
		
		var servicearray=document.getElementsByName("serviceselectedhidden[]");
		var servicevalues = '';
		for(i=0;i<servicearray.length;i++)
		{
			servicevalues += servicearray[i].value  + '#';
		}
		var servicelist = servicevalues.substring(0,(servicevalues.length-1));
		
		passData = "switchtype=editinvoice&invoiceno=" +encodeURIComponent(invoiceno) + "&purchasevalues=" + encodeURIComponent(purchasevalues) + "&usagevalues=" + encodeURIComponent(usagevalues) + "&productamountvalues=" + encodeURIComponent(productamountvalues) + "&descriptiontypevalues=" + encodeURIComponent(descriptiontypevalues) + "&descriptionvalues=" + encodeURIComponent(descriptionvalues) + "&descriptionamountvalues=" + encodeURIComponent(descriptionamountvalues) + "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val(),form)+ "&servicelist=" + encodeURIComponent(servicelist)+ "&serviceamountvalues=" + encodeURIComponent(serviceamountvalues)+ "&cardlist=" + encodeURIComponent(cardlist)+ "&lastslno=" + encodeURIComponent($('#lastslno').val(),form) + "&servicetaxamount=" + encodeURIComponent($('#sevicetax').val(),form)+ "&editremarks=" + encodeURIComponent($('#editremarks').val())+ "&businessname=" + encodeURIComponent($("#businessname").val(),form) + "&contactperson=" + encodeURIComponent($("#contactperson").val(),form)  + "&address=" + encodeURIComponent($("#address").val(),form) +"&emailid=" + encodeURIComponent($("#emailid").val(),form) + "&phone=" + encodeURIComponent($("#phone").val(),form) + "&cell=" + encodeURIComponent($("#cell").val(),form) + "&dealerid=" + encodeURIComponent($("#selectdealer").val(),form)+ "&serviceslnovalues=" + encodeURIComponent(serviceslnovalues)+ "&productslnovalues=" + encodeURIComponent(productslnovalues) + "&productcodevalues=" + encodeURIComponent(productcodelist)+ "&amountinwords=" + encodeURIComponent($('#amountinwords').html(),form) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		$('#form-error2').html(getprocessingimage());
		$('#editbutton').attr("disabled", true); 
		$('#editbutton').removeClass('swiftchoicebuttonbig');	
		$('#editbutton').addClass('swiftchoicebuttondisabledbig');

		
	}
	else if(command == 'cancel')
	{
		var form1 = $('#colorform1');
		var invoiceno = $('#lastslno',form).val();
		var productarray=document.getElementsByName("productselectedhidden[]");
		var productvalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productvalues += productarray[i].value  + '#';
		}
		var productlist = productvalues.substring(0,(productvalues.length-1));
		
		if(!$('#cancelremarks').val()) { alert("Enter the Cancellation Remarks"); $('#cancelremarks').focus(); return false; }
		passData =  "switchtype=cancelinvoice&invoiceno=" + encodeURIComponent(invoiceno)+ "&cancelremarks=" + encodeURIComponent($('#cancelremarks').val()) + "&productlist=" + encodeURIComponent(productlist)  + "&dummy=" + Math.floor(Math.random()*10000000000);
		$('#form-error1').html(getprocessingimage());//alert(passData)
		$('#cancelbutton').attr("disabled", true); 
		$('#cancelbutton').removeClass('swiftchoicebuttonbignew');	
		$('#cancelbutton').addClass('swiftchoicebuttondisabledbignew');
		

	}
	queryString = '../ajax/updateinvoice.php';
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
				var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						$('#form-error2').html('');
						alert(response[1]);
						$().colorbox.close();
						$('#displaybutton').hide();
						$('#statusresult').html(response[2]);
						$('#remarksdisplay').html(response[3]);
						enablebuttontype()
						listinvoicedetails('display',$('#lastslno').val());
						
					}
					else if(response[0] == '2')
					{
						$('#form-error1').html('');
						alert(response[1]);
						$().colorbox.close();
						$('#statusresult').html(response[2]);
						$('#remarksdisplay').html(response[3]);
						disablebuttontype();
						listinvoicedetails('display',$('#lastslno').val());
					}
					else
					{
						alert('Unable to Connect...' + ajaxcall0.responseText);
					}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}


function validaterequest(command)
{
	
	if(command == 'cancel')
	{
		$("").colorbox({ inline:true, href:"#inline_example1" });
		$('#cancelremarks').val('');
		$('#cancelbutton').attr("disabled", false); 
		$('#cancelbutton').removeClass('swiftchoicebuttondisabledbignew');	
		$('#cancelbutton').addClass('swiftchoicebuttonbignew');
	}
	else if(command == 'edit')
	{
		$("").colorbox({ inline:true, href:"#inline_example2" });
		$('#editremarks').val('');
		$('#editbutton').attr("disabled", false); 
		$('#editbutton').removeClass('swiftchoicebuttondisabledbig');	
		$('#editbutton').addClass('swiftchoicebuttonbig');
		

	}
}



function calculatenormalprice(command)
{
	//alert('here');
	var form = $('#submitform');
	var productcount = $('#seletedproductgrid',form).length;
	var rowcount = $('#adddescriptionrows',form).length;
	//var count = document.getElementById('productcounthidden').value;;
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	var productamount = 0;
	var totalamount = 0;
	var servicetax = 0;
	var netamount = 0;
	for(i=0,j=1; i<rowcount,j<=rowcount; i++,j++)
	{	
		descriptiontypearrray[i] = 'descriptiontype' + j;
		descriptiontypevalues[i] = (document.getElementById(descriptiontypearrray[i]).innerHTML).toLowerCase();
	
		descriptionarrray[i] = 'description' + j;
		descriptionvalues[i] = document.getElementById(descriptionarrray[i]).value;

		descriptionamountarrray[i] = 'descriptionamount' + j;
		if(isNaN(document.getElementById(descriptionamountarrray[i]).value))
			document.getElementById(descriptionamountarrray[i]).value = '0';
		descriptionamountvalues[i] = document.getElementById(descriptionamountarrray[i]).value;
		if(command == 'validate')
		{
			if(descriptiontypevalues[i] == 'add' || descriptiontypevalues[i] == 'less' )
			{
				var field = document.getElementById(descriptionarrray[i]);
				if(!field.value)
				{
					alert('Please Enter the Description...');
					field.focus(); 
					return false;
				}
				var field = document.getElementById(descriptionamountarrray[i]);
				if(!field.value)
				{ 
					alert('Please Enter the amount...');
					field.focus(); 	return false;
				}
				if(field.value)	{ if(!validateamount(field.value)) 
				{
					alert('Amount is not Valid.');
					field.focus(); return false; }
				}
			}
		}
	}
	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		if($('#usagetypehidden' + j).length > 0)
		{
			purchasearrray[i] = 'purchasetypehidden' + j;
			purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
			usagearrray[i] = 'usagetypehidden' + j;
			usagevalues[i] = document.getElementById(usagearrray[i]).value;
		}

		productamountarrray[i] = 'productamount' + j;
		productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
		//alert(productamountarrray);alert(productamountvalues)
		var field = document.getElementById(productamountarrray[i]);
		if(command == 'validate')
		{
			if(!field.value)
			{ 
				alert('Please Enter the amount');
				field.focus(); return false;
			}
			if(field.value){ if(!validateamount(field.value))
			{
				alert('Amount is not Valid.');
				field.focus(); return false; }
			}
		}
		else
		{
				productamount += (productamountvalues[i])*1;
		}
		
	}
	totalamount = productamount;
	if(isNaN(totalamount))
	{
		$('#totalamount').val('0');		
		$('#sericetaxamount').val('0');	
		$('#netamount').val('0');	
	}
	if(productamount == 0)
	{
		//alert('here');
		$('#totalamount').val('0');		
		$('#sericetaxamount').val('0');	
		$('#netamount').val('0');	
		$('#paymentamount').val('0');	
	}

	else
	{
		if(isNaN(totalamount))
			totalamount = 0;
		if(descriptionamountvalues != '')
			amount = getdescriptionamount(descriptionamountvalues,descriptiontypevalues);
		else
			amount = 0;
			
		//alert(amount);
		if(isNaN(amount))
			amount = 0;
		totalamount = totalamount + (amount)*1;
		servicetax = Math.round(totalamount * 0.103);
		netamount = totalamount + servicetax;
		$('#totalamount').val(totalamount);		
		$('#sevicetax').val(servicetax);	
		$('#netamount').val(netamount);	
		$('#paymentamount').val(netamount);	
		var words = NumbertoWords(netamount);
		$('#amountinwords').html(words);	
	}
	
}

function getdescriptionamount(descriptionamountvalues,descriptiontypevalues)
{
	amount = 0;
	descriptioncount = descriptionamountvalues.length;
	for(i=0;i<descriptioncount; i++)
	{
		if(descriptiontypevalues[i] == 'add')
			amount = (amount) + descriptionamountvalues[i] *1;
		else
		{
			amount = (amount) - descriptionamountvalues[i]*1;
			if(isNaN(amount))
				amount =0;
		}
	}
	return amount;
}




//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
		
	var form = $('#submitform');	
	if($('#onlineslno').val() == '')
	{
		$('#form-error').html(errormessage('Please select a Invoice.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

function editform()
{
	
	$('#displaybutton').show();
	disablebuttontype();
	$("#businessname").removeClass("swifttext-white");
	$("#businessname").addClass("swifttext");
	$("#businessname").removeAttr("readonly"); 
	
	$("#address").removeClass("swifttext-white");
	$("#address").addClass("swifttext");
	$("#address").removeAttr("readonly"); 
	
	$("#contactperson").removeClass("swifttext-white");
	$("#contactperson").addClass("swifttext");
	$("#contactperson").removeAttr("readonly"); 
	
	$("#emailid").removeClass("swifttext-white");
	$("#emailid").addClass("swifttext");
	$("#emailid").removeAttr("readonly"); 
	
	$("#phone").removeClass("swifttext-white");
	$("#phone").addClass("swifttext");
	$("#phone").removeAttr("readonly"); 
	
	$("#cell").removeClass("swifttext-white");
	$("#cell").addClass("swifttext");
	$("#cell").removeAttr("readonly");
	
	$("#totalamount").removeClass("swifttext-white");
	$("#totalamount").addClass("swifttext");
	$("#totalamount").removeAttr("readonly"); 
	
	$("#sevicetax").removeClass("swifttext-white");
	$("#sevicetax").addClass("swifttext");
	$("#sevicetax").removeAttr("readonly"); 
	
	$("#netamount").removeClass("swifttext-white");
	$("#netamount").addClass("swifttext");
	$("#netamount").removeAttr("readonly"); 
	
	$("#invoiceremarks").removeClass("swifttext-white");
	$("#invoiceremarks").addClass("swifttext");
	$("#invoiceremarks").removeAttr("readonly");
	
	
	var form = $('#submitform');
	var productcount = $('#seletedproductgrid',form).length;
	var rowcount = $('#adddescriptionrows',form).length;
	for(i=1; i<=productcount;i++)
	{	
		$('#'+'productamount'+ i).removeClass("swifttext-white");
		$('#'+'productamount'+ i).addClass("swifttext");
		$('#'+'productamount'+ i).removeAttr("readonly"); 
		
		$('#'+'displaypurchasediv'+ i).show();
		$('#'+'displayusagediv'+ i).show();
		
		$('#productinputdiv'+ i).hide();
		$('#productselectiondiv'+ i).show();
		
	}
	for(j=1; j<=rowcount;j++)
	{	
		$("#"+'descriptionamount'+ j).removeClass("swifttext-white");
		$("#"+'descriptionamount'+ j).addClass("swifttext");
		$("#"+'descriptionamount'+ j).removeAttr("readonly");
		
		$("#"+'description'+ j).removeClass("swifttext-white");
		$("#"+'description'+ j).addClass("swifttext");
		$("#"+'description'+ j).removeAttr("readonly"); 
	}
		$('#dealerinputdiv').hide();
		$('#dealerselectiondiv').show();
	
}




function canceleditform()
{
	listinvoicedetails('display',$('#lastslno').val());
	//selectdealer($('#dealervalue').val(),'inputtext');
	$('#displaybutton').hide();
	enablebuttontype();
}


function searchbycustomerid(cusid)
{
	invoicetabclose();
	var form = $("#submitform");
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/updateinvoice.php";
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
				var response = ajaxresponse;//alert(response)
				flag = false;
					if(response == '')
					{
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						
						getcustomerlistonsearch();
						$("#customerselectionprocess").html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshinvoicenoarray()"></span> '))
						$("#totalcount").html('0');
					}
					else
					{
						customersearcharray = new Array();
						for( var i=0; i<response.length; i++)
						{
							customersearcharray[i] = response[i];
						}
						getcustomerlistonsearch();
						$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshinvoicenoarray()"></span> '));
						$("#totalcount").html(customersearcharray.length);
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

function invoicetabclose()
{
	$('#displaydiv').hide();
	$('#displaydetails2').show();
	$('#displaydetails1').hide();
}


function searchcustomerarray()
{
	var flag = 'advsearch';
	var form = $("#searchfilterform");
	var form = $("#submitform");
	var error = $("#filter-form-error");
	
	var region = $("#region2 option:selected").val();
	var branch = $("#branch2 option:selected").val();
	
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {error.html(errormessage('Enter the From Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {error.html(errormessage('Enter the To Date.'));field.focus(); return false; }

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
			c_value += $(chks[i]).val() + ',';
		}
	}
	
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=searchcustomerlist&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer2=" +encodeURIComponent($("#currentdealer2").val()) + "&branch2=" + encodeURIComponent(branch)+"&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData)
		$('#customerselectionprocess').html(getprocessingimage());
		queryString = "../ajax/updateinvoice.php";
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
					if(response == '')
						{
							$('#filterdiv').show();
							customeradvsearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customeradvsearcharray[i] = response[i];
							}
							
							getadvanceserachlist();
							$("#customerselectionprocess").html(errormessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshinvoicenoarray()"></span> '))
							$("#totalcount").html('0');
							error.html(errormessage('No datas found to be displayed.')); 
						}
						else
						{
							$('#filterdiv').hide();//alert(response);
							customeradvsearcharray = new Array();
							for( var i=0; i<response.length; i++)
							{
								customeradvsearcharray[i] = response[i];
							}
							getadvanceserachlist();
							$("#customerselectionprocess").html(successmessage("Search Result"  + '<span style="padding-left: 15px;"><img src="../images/close-button.jpg" width="15" height="15" align="absmiddle" style="cursor: pointer; padding-bottom:2px" onclick="refreshinvoicenoarray()"></span> '));
							$("#totalcount").html(customeradvsearcharray.length);
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

function getadvanceserachlist()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = customeradvsearcharray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = customeradvsearcharray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
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

function resendinvoice(invoiceno)
{
	var form = $('#submitform');
	if(invoiceno == '')
	{
		$('#form-error').html(errormessage('Select a Invoice first.')); return false;
	}
	else
	{
		var confirmation = confirm('Are you sure you want to resend the Invoice?');
		if(confirmation)
		{
			var passData = "switchtype=resendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			$('#form-error').html(getprocessingimage());	
			queryString = "../ajax/updateinvoice.php";
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
						$('#form-error').html('');
						var response = ajaxresponse.split('^'); 
						if(response[0] == '1')
						{
							$('#form-error').html(successmessage('Invoice sent successfully to the selected Customer'));
						}
						else
						{
							$('#form-error').html(scripterror());
						}
					}
				}, 
				error: function(a,b)
				{
					$("#form-error").html(scripterror());
				}
			});
		}
	else
		return false;
	}
}