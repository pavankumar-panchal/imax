//Function to make the display as block as well as none-------------------------------------------------------------
//Function to save the new customer or update the existing customer-------------------------------------------------
var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();
var process1;
var process2;
var process3;
var process4;

function formsubmit(command)
{
	
	var form = $('#submitform');
	var error = $('#form-error');//alert(form.cuslastslno.value);
	
	var passData = "";
	if(command == 'attachcard')
	{
		passData =  "switchtype=attachcard&customerreference=" + encodeURIComponent($('#customerlist').val()) + "&cardid=" + 
		encodeURIComponent($('#scratchcardlist').val()) + "&yearcount=" + encodeURIComponent($('#yearcount').val())
		+ "&purcheck=" + encodeURIComponent($('#purcheck').val()) + "&licensepurchase=" + encodeURIComponent($('#licensepurchase')
		.val()) + "&remarks=" +  encodeURIComponent($('#remarks').val()) + "&lastyearusagecheck=" +  encodeURIComponent($('#lastyearusagecheck').val()) +
		"&dummy=" + Math.floor(Math.random()*100000000) + 
		"&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&description=" + 
		encodeURIComponent($("#description").val());//alert(passData)
		
	}
	else
	{
		passData =  "switchtype=detachcard&cardid=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*10000000000)+ "&remarks=" + encodeURIComponent($('#remarks').val()) + "&dummy=" + Math.floor(Math.random()*10000000000);//alert(passData)
	}
	queryString = '../ajax/dealerattachcard.php';
	error.html(getprocessingimage());
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
				var splitajaxresponse = ajaxresponse.split('^'); 
				if(splitajaxresponse[0] == '1')
				{
					error.html(successmessage(splitajaxresponse[1]));
					generatedatagrid('');
					$('#detailsonscratch').hide();
					var productcode = $("#product").val();
					var purtype = $("#purtype").val();
					//alert(productcode);
					gettotalcusattachcard(productcode,purtype);
					//newentry();
				}
				else if(splitajaxresponse[0] == '2')
				{
					error.html(successmessage(splitajaxresponse[1]));
					generatedatagrid('');
					$('#detailsonscratch').hide();
					var productcode = $("#product").val();
					var purtype = $("#purtype").val();
					gettotalcusattachcard(productcode,purtype);
					//newentry();
				}
				else
				{
					error.html(errormessage('Unable to Connect...'));
					/*error.html(successmessage(splitajaxresponse[1]));
					generatedatagrid('');
					$('#detailsonscratch').hide();
					gettotalcusattachcard();*/
					//newentry();
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}

function getcustomerdetails(card)
{
		var customerid = $('#customerid').html();
		var passData = "switchtype=newupdationchange&card=" + encodeURIComponent(card) + "&customerid=" + encodeURIComponent($(		
		'#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData);
		var queryString = "../ajax/dealerattachcard.php";
		ajaxcall1 = $.ajax(
		{
			type: "POST",url: queryString, data: passData, cache: false,dataType:'json',
			success: function(ajaxresponse,status)
			{
				var response = (ajaxresponse);
				//var hiddenproductoflicense = $('#proname').val(response['productname']);
				//var hidproduct = $('#proname').val();
				
				var currentyearcard = $('#purcheck').val(response['currentyearcard']);
				//var hidpurtype0 =$('#purcheck').val(); 
				
				var totalcards = $('#licensepurchase').val(response['totalcards']);
				var totalcount = $('#licensepurchase').val();
				
				var yearcount = $('#yearcount').val(response['lasttwoyearcount']);
				var lasttwoyearcount = $('#yearcount').val();
				
				var usagecount = $('#lastyearusagecheck').val(response['lastyearusagecheck']);
				var lastyearusagecheck = $('#lastyearusagecheck').val();
				
				$("#remarks").val('');
			}
		 });
}

function newentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#detailsonscratch').hide();
}

function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/dealerattachcard.php";
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
	queryString = "../ajax/dealerattachcard.php";
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
	
	queryString = "../ajax/dealerattachcard.php";
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

	queryString = "../ajax/dealerattachcard.php";
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
	
	queryString = "../ajax/dealerattachcard.php";
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
	var form = $('#submitform');
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

function gridtoform(cardid)
{
	if(cardid != '')
	{
		scratchdetailstoform(cardid);
		enabledetachcard();
		disableattachcard();
		$('#lastslno').val(cardid);
	}
}

function generatedatagrid(startlimit)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	queryString = "../ajax/dealerattachcard.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					$('#customerid').html(response[4]);
					$('#lastslno').val($('#customerlist').val());
				}
				else
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

//function to display 'Show more records' or 'Show all records' 
function generatemordatagrid(startlimit,slnocount,showtype)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent($('#customerlist').val()) + "&startlimit=" + encodeURIComponent(startlimit)+ "&slnocount=" + encodeURIComponent(slnocount)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	queryString = "../ajax/dealerattachcard.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#custresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
					$('#tabgroupgridc1link').html(response[3]);
					$('#customerid').html(response[4]);
				}
				else
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

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaycustomername').html($("#customerlist option:selected").text());
	$('#customerid').val(selectbox);
	
	/*var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#displaycustomername').html($("#customerlist option:selected").text());
	*/
	//displaycustomername();
	enableformelemnts();
	generatedatagrid('');
	disabledetachcard();
	disableattachcard();
	newentry();
	$('#form-error').html('');
	$('#scratchcardlist').html('');
	$('#totalcountofcard').html('0');
}

function selectacustomer(input)
{
	var selectbox = $('#customerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
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