// JavaScript Document

var productarray = new Array();


function formsubmit(changetype)
{
	var form = $("#submitform");
	var error = $("#form-error");
	var field = $("#productname"); 
	if(!field.val()) { error.html(errormessage('Enter the Product Name')); field.focus(); return false; }
	var field = $("#productcode");                                                                                                                                                                                                                     
	if(!field.val()) { error.html(errormessage('Enter the Product code.')); field.focus(); return false; }
	if(field.val()) { if(!validateproductcode(field.val())) {error.html(errormessage('Enter a valid Product Code.')); field.focus(); return false; } };
	var field = $('#productnotinuse:checked').val();
	if(field != 'on') productnotinuse = 'no'; else productnotinuse = 'yes';
	var field = $('#allowdealerpurchase:checked').val();
	if(field != 'on') allowdealerpurchase = 'no'; else allowdealerpurchase = 'yes';
	var field = $("#producttype");
	if(!field.val()) { error.html(errormessage('Select the Product Type.')); field.focus(); return false; }
	var field = $("#productgroup");
	if(!field.val()) { error.html(errormessage('Select the Product Group.')); field.focus(); return false; }
	var field = $("#financialyear");
	if(!field.val()) { error.html(errormessage('Select the Financial Year.')); field.focus(); return false; }
	var field = $("#updationtype"); 
	if(!field.val()) { error.html(errormessage('Select the Updation Type.')); field.focus(); return false; }
	var field = $('#allowdealerpurchase:checked').val();
	if(field != 'on') var allowdealerpurchase = 'no'; else allowdealerpurchase = 'yes';
	if(allowdealerpurchase == 'yes' && !$("#dealerpurchasecaption").val()) {error.html(errormessage('Enter the Dealer Purchase Caption.')); $("#dealerpurchasecaption").focus(); return false; }

	else
	{
		var passData = '';
		passData = "changetype=" + encodeURIComponent(changetype) + "&productname="+ encodeURIComponent($("#productname").val())+ "&updationtype="+ encodeURIComponent($("#updationtype").val()) + "&productcode="+ encodeURIComponent($("#productcode").val()) + "&productnotinuse="+ encodeURIComponent(productnotinuse) + "&allowdealerpurchase="+ encodeURIComponent(allowdealerpurchase) + "&producttype="+ encodeURIComponent($("#producttype").val()) + "&productgroup="+ encodeURIComponent($("#productgroup").val()) + "&dealerpurchasecaption="+ encodeURIComponent($("#dealerpurchasecaption").val()) + "&lastslno=" + encodeURIComponent($("#lastslno").val())+"&financialyear="+encodeURIComponent($("#financialyear").val());
		queryString = '../ajax/products.php';
		error.html('<img src="../images/imax-loading-image.gif" border="0" />');
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
					if(response[0] == 1)
					{
						error.html(successmessage(response[1]));
						refreshproductarray();
						productdatagrid('');
						newentry();
					}
					else if(response[0] == 2)
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


function newentry()
{
	$("#submitform" )[0].reset();
	$('#lastslno').val('');
}


function refreshproductarray()
{	
	
	var passData = "changetype=generateproductlist&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/products.php";
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
				productarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					productarray[i] = response[i];
				}
				getproductlist();
				//$("#form-error").html(' ');
				$("#totalproductcount").html(productarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});		
}


function getproductlist()
{
	var form = $('#submitform');
	var selectbox = $('#productlist');
	var numberofproducts = productarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofproducts > actuallimit)?actuallimit:numberofproducts;
	
	//selectbox.options.length = 0;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = productarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}


function productdetailstoform(prdid)
{
	if(prdid != '')
	{
		$("#productselectionprocess").html('');
		var form = $("#submitform");
		$("#submitform" )[0].reset();
		var passData = "changetype=productdetailstoform&lastslno=" + encodeURIComponent(prdid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$("#form-error").html(getprocessingimage());
		var queryString = "../ajax/products.php";
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
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						if(response['productcode'] == '')
						alert('Product Code Not Available.');
						$("#lastslno").val(response['slno']);
						$("#productcode").val(response['productcode']);
						$("#productname").val(response['productname']);
						autochecknew($("#productnotinuse"),response['notinuse']);
						$("#producttype").val(response['type']);
						$("#productgroup").val(response['group']);
						autochecknew($("#allowdealerpurchase"),response['allowdealerpurchase']);
						$("#dealerpurchasecaption").val(response['dealerpurchasecaption']);
						$("#updationtype").val(response['updation']);
						$("#financialyear").val(response['year']);
					}
					else
					{
						$("#form-error").html(errormessage("No datas found to be displayed."));
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

function selectfromlist()
{
	var selectbox = $("#productlist option:selected").val();
	$('#detailsearchtext').val($("#productlist option:selected").text());
	$('#detailsearchtext').select();
	productdetailstoform(selectbox);	
}


function selectaproduct(input)
{
	var selectbox = $('#productlist');
	if(input == "")
	{
		getproductlist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');

		var addedcount = 0;
		for( var i=0; i < productarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = productarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = productarray[i];
				}
			var result1 = pattern.test(trimdotspaces(productarray[i]).toLowerCase());
			var result2 = pattern.test(productarray[i].toLowerCase());
			if(result1 || result2)
			{
				var splits = productarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}



function productsearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollproduct('up');
	else if(KeyID == 40)
		scrollproduct('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectaproduct(input);
	}
}

function scrollproduct(type)
{
	var selectbox = $('#productlist');
	var totalcus = $("#productlist option").length;
	var selectedcus = $("select#productlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#productlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#productlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}

function searchfilter(startlimit)
{	
	var form = $("#searchfilterform");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var orderby = $("#orderby").val();
	var error = $("#filter-form-error");
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $("#searchcriteria").focus(); return false; }
	passData = "changetype=productsearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/products.php";
	error.html(getprocessingimage());
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
				error.html('');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$("#error").html('');
					gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
					$("#tabgroupgridc2_1").html(response[1]);
					$("#gridcount").html("Total Count :  " + response[2]);
					$("#tabgroupgridc1linksearch").html(response[3]);
				}
				else
				{
					$("#error").html(errormessage('Product code not available.'));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}

function getmoresearchfilter(startlimit,slnocount,showtype)
{	
	var form = $("#searchfilterform");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	$("#totalproductcount").val('');
	var orderby = $("#orderby").val();
	var error = $("#filter-form-error");
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $("#searchcriteria").focus(); return false; }
	var passData = "changetype=productsearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/products.php";
	$("#tabgroupgridc1linksearch").html(getprocessingimage());
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
				var response = ajaxresponse.split('^');//alert(response);						
				if(response[0] == '1')
				{
					error.html(' ');
					gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
					$("#searchresultgrid").html($("#tabgroupgridc2_1").html());
					$("#tabgroupgridc2_1").html($("#searchresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridc1linksearch").html(response[3]);
					$("#gridcount").html("Total Count :  " + response[2]);
				}
				else
				{
					error.html(errormessage('No datas found to be displayed.'));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}


function searchbyproductcodeevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchproductcode').val();
		productdetailstoform(input);
	}
}


function productdatagrid(startlimit)
{
	var passData = "changetype=generategrid&dummy=" + Math.floor(Math.random()*10054300000) + "&startlimit=" + encodeURIComponent(startlimit);
	$("#productselectionprocess").html(getprocessingimage());
	queryString = "../ajax/products.php";
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
					$("#productselectionprocess").html(' ');
					$("#tabgroupgridc1_1").html(response[1]);
					$("#gridcount").html("Total Count :  " + response[2]);
					$("#tabgroupgridc1link").html(response[3]);
				}
				else
				{
					$("#productselectionprocess").html(errormessage('No datas found to be displayed.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}
//function to display 'Show more records' or 'Show all records' 
function getmoreproductdatagrid(startlimit,slnocount,showtype)
{
	var passData = "changetype=generategrid&dummy=" + Math.floor(Math.random()*10054300000) + "&startlimit=" + encodeURIComponent(startlimit)+ "&slnocount=" + encodeURIComponent(slnocount)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$("#tabgroupgridc1link").html(getprocessingimage());
	queryString = "../ajax/products.php";
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
					$("#gridcount").html("Total Count :  " + response[2]);
					$("#productresultgrid").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#productresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridc1link").html(response[3]);
					
				}
				else
				{
					$("#productselectionprocess").html(errormessage('No datas found to be displayed.'));					
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}


function dealerpurchasecaptionmandatory()
{
  	var form = $("#submitform");
	var field = $('#allowdealerpurchase:checked').val();
	if(field == 'on') $("#dealerpurchasecaption").addClass('swifttext-mandatory');
	else  $("#dealerpurchasecaption").removeClass('swifttext-mandatory');
}

