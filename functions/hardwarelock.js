
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var customerarray = new Array();
var process1;
var process2;
var process3;
var process4;

//function to add values of the selected option to select box -Meghana[23/11/2009]
function addentry(productcode)
{
	//Get the Select Box as an object
	var selectbox = document.getElementById('productlist');
	
	//Check if any product is select. Else, prompt to select a product.
	if(selectbox.selectedIndex < 0)
	{
		alert("Select a Product to Add.");
		return false;
	}
	
	//If the option is disabled, then do nothing.
	if(selectbox.options[selectbox.selectedIndex].disabled == true)
	{
		return false;
	}
	
	//Take the value and Text of selected product from selected index.
	var addproductvalue = selectbox.options[selectbox.selectedIndex].value;
	var addproducttext = selectbox.options[selectbox.selectedIndex].text;

	//When double clicked on a disabled, the other selected will be passed. So, compare the double clicked product value with selected value and return false.
	if(productcode)
	{
		if(productcode != addproductvalue)
			return false;
	}
	
	//Get the second Select Box as an object
	var secondselectbox = document.getElementById('selectedproducts');
	
	//Add the value to second select box
	var newindexforadding = secondselectbox.length;
	secondselectbox.options[newindexforadding] = new Option(addproducttext,addproductvalue);
	secondselectbox.options[newindexforadding].setAttribute('ondblclick', 'deleteentry("' + addproductvalue + '");');

	
	//Disable the added option in first select box
	selectbox.options[selectbox.selectedIndex].disabled = true;

}	

//function to remove values of the selected option from select box -Meghana[23/11/2009]
function deleteentry(productcode)
{
	//alert(productcode);
	//Get the select boxes as objects
	var selectbox = document.getElementById('productlist');
	var secondselectbox = document.getElementById('selectedproducts');
	
	//Check if any product is select. Else, prompt to select a product.
	if(secondselectbox.selectedIndex < 0)
	{
		alert("Select a Product to Remove.");
		return false;
	}

	//Take the value and Text of selected product from selected index.
	var delproductvalue = secondselectbox.options[secondselectbox.selectedIndex].value;
	var delproducttext = secondselectbox.options[secondselectbox.selectedIndex].text;
	
	//Run a loop for whole select box [2] and remove the entry where value is deletable
	for(i = 0; i < secondselectbox.length; i++)
    {
		loopvalue = secondselectbox.options[i].value;
		if(loopvalue == delproductvalue)
		{
			secondselectbox.options[i] = null;
		}
	}
	
	//Run a loop for whole select box [1] and Enable the entry where value is deleted
	for(i = 0; i < selectbox.length; i++)
    {
		loopvalue = selectbox.options[i].value;
		if(loopvalue == delproductvalue)
		{
			selectbox.options[i].disabled = false;
		}
	}

}


//function to remove all values of the selected option from select box -Meghana[25/11/2009]
function deleteallentry(productcode)
{
		//Get the select boxes as objects
		var productarray = new Array();
		var allproductarray = new Array();
		var selectbox = document.getElementById('productlist');
		var secondselectbox = document.getElementById('selectedproducts');
		var secoundvalues = document.getElementById('productlist');
		for(var i=0; i < secoundvalues.length; i++ )
		{
			productarray[i] = secoundvalues[i].value;
		}
	
		var ckvalues = document.getElementById('selectedproducts');
		for(var i=0; i < ckvalues.length; i++ )
		{
			allproductarray[i] = ckvalues[i].value;
		}
	
		//Run a loop for whole select box [2] and remove the entry where value is deletable
		for(i = 0; i < allproductarray.length; i++)
		{
				secondselectbox.options[secondselectbox.length -1] = null;
		}
		//Run a loop for whole select box [1] and Enable the entry where value is deleted
		for(j = 0; j < selectbox.length; j++)
		{
				selectbox.options[j].disabled = false;
		}

}

function formsubmit(command)
{
	var form =$('#submitform');
	var error = $('#form-error');
	var productarray = new Array();
	var field = $('#billno');
	var field = $('#hardwareno');
	if(!field.val()) { error.html(errormessage("Enter the Hardware Lock.")); field.focus(); return false; }
	var field =$('#dealer');
	if(!field.val()) { error.html(errormessage("Select the dealer.")); field.focus(); return false; }
	var field = $('#DPC_startdate');
	if(!field.val()) { error.html(errormessage("Enter the date")); field.focus(); return false; }
	var field = $('#billamount');
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Enter a Valid Amount.')); field.focus(); return false; } } 
	//Check if any product is select. Else, prompt to select a product.
	var ckvalues = document.getElementById('selectedproducts');
	for(var i=0; i < ckvalues.length; i++ )
	{
		productarray[i] = ckvalues[i].value;
	}
	if (productarray.length <1) 
	 { error.html(errormessage("Select a Product.")); ckvalues.focus(); return false; }
	
	
		var passData = "";
		if(command == 'save')
		{	
			 passData =  "switchtype=save&lastslno=" + $('#customerlist').val()  + "&hardwareno=" + $('#hardwareno').val() + "&dealer=" + $('#dealer').val() + "&startdate=" + $('#startdate').val() + "&billno=" + $('#billno').val() + "&billamount=" + $('#billamount').val()+ "&remarks=" + $('#remarks').val() + "&productarray=" + productarray +"&dummy=" + Math.floor(Math.random()*100000000)+ "&hwlrecordno=" + $('#hwlrecordno').val() + "&lockno=" + $('#lockno').val();//alert(passData)
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete the Record?");
			if(confirmation)
			{
				passData =  "switchtype=delete&hwlrecordno=" + $('#hwlrecordno').val() + "&dummy=" + Math.floor(Math.random()*10000000000);//alert(passData)
			
			}
			else
			return false;
		}
		$('#form-error').html(getprocessingimage());
		queryString = "../ajax/hardwarelock.php";
		ajaxcall0 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));//alert(error.innerHTML)
						generatedatagrid('');
						newentry();
					}
					else if(response[0] == '2')
					{
						error.html(errormessage(response[1]));//alert(error.innerHTML)
					}
					else if(response[0] == '3')
					{
						error.html(successmessage(response[1]));
						generatedatagrid('');
						newentry();
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});	
}


//Function to Retrive details to the Form-Meghana[24/11/2009]
function productdetailstoform (hwlrecordno)
{
		$('#form-error').html('');
		var form = $('#submitform');
		var productarray = new Array();
		var productarraylist = new Array();
		var productboxlist = new Array();
		var list = new Array();
		var secondselectbox = document.getElementById('selectedproducts');
		var selectbox = document.getElementById('productlist');
		var passData = "switchtype=productdetailstoform&hwlrecordno=" + encodeURIComponent(hwlrecordno) +"&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
		
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/hardwarelock.php";
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
					$('#form-error').html('');
					var response = ajaxresponse;
					$('#form-error').html('');
					$('#dealer').val(response['dealer']);
					$('#startdate').val(response['createddate']);
					$('#billamount').val(response['amount']);
					$('#billno').val(response['billno']);
					$('#remarks').val(response['remarks']);
					$('#hardwareno').val(response['hardwarelockno']);
					$('#hwlrecordno').val(response['hwlrecordno']);
					$('#lockno').val(response['hardwarelockno']);
					$('#displayenteredby').html(response['enteredby']);
					$('#updateddate').html(response['createddate']);
					productarray =response['grid'].split('&*&');
					secondselectbox.options.length = 0;
					for( var i=0; i < selectbox.length; i++)
					{
						selectbox.options[i].disabled = false;
					}
					secondselectbox.options.length = 0;
					//Add the all element to the array
					for(var i =0;i<productarray.length;i++)
					{
						productarraylist[i]=productarray[i];
					}
					//To added the product to the select box that is in the grid
					for( var i=0; i<productarraylist.length; i++)
					{
						var splits = productarraylist[i].split("%");
						secondselectbox.setAttribute('ondblclick', 'deleteentry("'+ splits[1] +'");');
						secondselectbox.options[secondselectbox.length] = new Option(splits[0], splits[1]);
						
					}
				
					//Run a loop for whole select box [1] and Disable the entry where value is deleted
					for(var i =0;i < productarraylist.length;i++)
					{
						var splits3 = productarraylist[i].split("%");
						for(j = 0; j < selectbox.length; j++)
						{
							loopvalue = selectbox.options[j].value;
							if(loopvalue == splits3[1])
							{
								selectbox.options[j].disabled = true;
							}
						}
					}
					
					enabledelete();
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});	
}

//Function to entry new records -Meghana[23/11/2009]
function newentry()
{
	var form = $('#submitform');
	$("#submitform" )[0].reset();
	disabledelete();
	$('#hwlrecordno').val('');
	//document.getElementById('form-error').innerHTML='';
	$('#selectedproducts').html('');
	$('#updateddate').html('Not Available');
	//document.getElementById('tabgroupgridc1').innerHTML ='';
	var ckvalues = $('#productlist');
	for(var i=0; i < ckvalues.length; i++ )
	{
		ckvalues[i].disabled = false;
	}
	
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/hardwarelock.php";
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
	queryString = "../ajax/hardwarelock.php";
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
	
	queryString = "../ajax/hardwarelock.php";
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

	queryString = "../ajax/hardwarelock.php";
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
	
	queryString = "../ajax/hardwarelock.php";
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

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaycustomername').html($("#customerlist option:selected").text());
	$('#form-error').html('');
//	displaycustomername();
	enableformelemnts();
	generatedatagrid('');
	newentry();
	
}

//function to generate the data grid -Meghana[24/11/2009]
function generatedatagrid(startlimit)
{
	var passData = "switchtype=generateproductgrid&lastslno=" + $('#customerlist').val() + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/hardwarelock.php";
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
				if(response[0] == 1)
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);//alert(response[2])
				}
				else if(response[0] == 2)
				{
					$('#tabgroupgridwb1').html('');
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridc1_1').html(response[1]);	
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});	
}

//function to display 'Show more records' or 'Show all records' 
function generatemoregrid(startlimit,slno,showtype)
{
	
	var passData = "switchtype=generateproductgrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&lastslno=" + encodeURIComponent(document.getElementById('customerlist').value)  + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	//alert(passData);
	queryString = "../ajax/hardwarelock.php";
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#custresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html()).replace(/\<\/table\>/gi,'')+ response[1] ;
					$('#tabgroupgridc1link').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#tabgroupgridc1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});	
}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	
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

function selectacustomer(input) {
	var selectbox = $('#customerlist');
	if (input == "") {
		getcustomerlist1();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < customerarray.length; i++) {
			// Check if any part of the name contains the input string
			if (customerarray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = customerarray[i].split("^");
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
		selectacustomer(input);
	}
}



function scrollcustomer(type)
{
	var selectbox = document.getElementById('customerlist');
	var totalcus = selectbox.options.length;
	var selectedcus = selectbox.selectedIndex;
	if(type == 'up' && selectedcus != 0)
		selectbox.selectedIndex = selectedcus - 1;
	else if(type == 'down' && selectedcus != totalcus)
		selectbox.selectedIndex = selectedcus + 1;
	selectfromlist();
}

//Function to enable the form fields
function enableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=false; 
	}
	var secondselectbox = document.getElementById('selectedproducts');
	var selectbox = document.getElementById('productlist');

	for( var i=0; i < secondselectbox.length; i++)
	{
		secondselectbox.disabled = false;
	}
	for( var i=0; i < selectbox.length; i++)
	{
		selectbox.options[i].disabled = false;
	}
}
//Function to disable the form fields
function disableformelemnts()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
	}
	var secondselectbox = document.getElementById('selectedproducts');
	var selectbox = document.getElementById('productlist');

	for( var i=0; i < secondselectbox.length; i++)
	{
		secondselectbox.disabled = true;
	}
	for( var i=0; i < selectbox.length; i++)
	{
		selectbox.options[i].disabled = true;
	}
}


//Function to search the record based on the Hardware lock no-Meghana[27/11/2009]
function searchbycontractid(contractid)
{
	enableformelemnts();
	//form.lastslno.value='';
	$('#form-error').html('');
	var form = $('#submitform');
	var productarray = new Array();
	var productarraylist = new Array();
	var productboxlist = new Array();
	var secondselectbox = document.getElementById('selectedproducts');
	var selectbox = document.getElementById('productlist');
	$("#submitform" )[0].reset();
	var passData = "switchtype=searchbycontractid&contractid=" + encodeURIComponent(contractid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
	var queryString = "../ajax/hardwarelock.php";
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
				var response = (ajaxresponse); 
				if(response['errorcode'] == 2)
				{
					alert('Hardwarelock number Not Available.');
					newentry();

				}
				else if(response['errorcode'] == 1)
				{
					//var response = ajaxcall5.responseText;
					$('#form-error').html('');
					$('#dealer').val(response['dealer']);
					$('#startdate').val(response['createddate']);
					$('#billamount').val(response['amount']);
					$('#billno').val(response['billno']);
					$('#remarks').val(response['remarks']);
					$('#hardwareno').val(response['hardwarelockno']);
					$('#customerlist').val(response['customerreference']);//alert(response[12])
					$('#hwlrecordno').val(response['hardwarelockno']);
					$('#displayenteredby').html(response['enteredby']);//alert(response[7])
					$('#updateddate').html(response['createddate']);
					$('#displaycustomername').html(response['businessname']);//alert(response[8])
					productarray =response['grid'].split('&*&');//alert(productarray)
					for( var i=0; i < productarray.length; i++)
					{
						secondselectbox.options[secondselectbox.length - 1] = null;
					}
					for( var i=0; i < selectbox.length; i++)
					{
						selectbox.options[i].disabled = false;
					}
					secondselectbox.options.length = 0;
					//Add the all element to the array
					for(var i =0;i<productarray.length;i++)
					{
						productarraylist[i]=productarray[i];
					}
					//To added the product to the select box that is in the grid
					for( var i=0; i<productarraylist.length; i++)
					{
						var splits = productarraylist[i].split("%");
						secondselectbox.setAttribute('ondblclick', 'deleteentry("'+ splits[1] +'");');
						secondselectbox.options[secondselectbox.length] = new Option(splits[0], splits[1]);
						
					}
				
					//Run a loop for whole select box [1] and Disable the entry where value is deleted
					for(var i =0;i < productarraylist.length;i++)
					{
						var splits3 = productarraylist[i].split("%");
						for(j = 0; j < selectbox.length; j++)
						{
							loopvalue = selectbox.options[j].value;
							if(loopvalue == splits3[1])
							{
								selectbox.options[j].disabled = true;
							}
						}
					}

					//generatedatagrid('');
					enabledelete();
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function searchbycontractidevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchhardwareno').val();//alert(input)
		searchbycontractid(input);
	}
}


