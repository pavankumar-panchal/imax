// JavaScript Document

function getpindetails(startlimit)
{
	var form = $('#submitform');
	var error = $('#filter-form-error');
	
	//var field = $('#pinno');
	//if(!field.val()) { error.html(errormessage("Enter the Pin Number.")); $('#pinno').focus(); return false; }
	
	var field = $.trim($('#cardid').val());
	if(!field)
	{
		var field1 = $('#pinno');
		if(!field1.val()) 
		{ 
		  alert("Enter the Card/Pin Number."); 
		  $('#pinno').focus(); 
		  return false; 
		}
	}
	else
	{
		if(!IsNumeric(field))
		{
			alert("Enter only digits.");
			$('#cardid').val('');
			$('#pinno').focus();
			return false;
		}
	}
	
	var passData = "switchtype=pindetails&pinno=" + encodeURIComponent($("#pinno").val()) +
	 "&cardid=" + encodeURIComponent($("#cardid").val()) + "&startlimit=" + encodeURIComponent(startlimit)+ 
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData)
	var queryString = "../ajax/pindetails.php";
	error.html(getprocessingimage());
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					
					error.html('');
					$('#tabgroupgridc3_1').html(response[1]);
					$('#tabgroupgridc1linksearch').html(response[3]);
					
					hidebuttons();
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

//Delete extra delear details
function deletedealerdata(cardidslno)
{
	//alert(cardidslno); 
	var form = $('#submitform');
	var error = $('#filter-form-error');
	var confirmdelete = confirm('Are you sure want to delete?');
	if(confirmdelete)
	{
		var passData = "switchtype=deletedealerdata&cardidslno=" + encodeURIComponent(cardidslno) + "&dummy=" + Math.floor(Math.random()*1000782200000);
		//alert(passData)
		var queryString = "../ajax/pindetails.php";
		error.html(getprocessingimage());
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
					var response = ajaxresponse.split('^');
					if(response[0] == '1')
					{
						
						getpindetails('');
						alert('Record Deleted');
					}
					else
					{
						error.html(errormessage('Unable to connect.'));
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
		return false;
	
}

//Divya RCI
function rcidetails(startlimit)
{
	var form = $('#submitform');
	var error = $('#filter-form-error');
	var field = $('#cardid');
	if(!field.val())
	{
		var field1 = $('#pinno');
		if(!field1.val()) 
		{ 
		  return false; 
		}
	}
	else
	{
		if(!IsNumeric(field.val()))
		{
			return false;
		}
	}
	
	var passData = "switchtype=rcidetails&pinnum=" + encodeURIComponent($("#pinno").val()) +
	 "&cardnum=" + encodeURIComponent($("#cardid").val()) + "&startlimit=" + encodeURIComponent(startlimit) + 
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/pindetails.php";
	error.html(getprocessingimage());
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					
					error.html('');
					$('#tabgroupgridrcic3_1').html(response[1]);
					$('#tabgroupgridc1linkrcisearch').html(response[3]);
					hidebuttons();
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

//POPUP RCI
function displayrcicustdata(regname,proversion,comid)
{
	   if($('#regname').val() != '')
	{
		
		rcifunc(regname,proversion,comid);
		$("").colorbox({ inline:true, href:"#rcidatagrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
	
}

function rcifunc(regname,proversion,comid)
{
		var form = $('#submitform');
		var passData = "switchtype=rcidetailsgridcust&regname="+ encodeURIComponent(regname)
+ "&proversion=" + encodeURIComponent(proversion)+ "&comid=" + encodeURIComponent(comid);
	var queryString = "../ajax/pindetails.php";
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
				var response = ajaxresponse.split('^');//alert(response);
				if(response[0] == '1')
				{
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

function updatedata()
{
	var form = $('#submitform');
	var passData = "";
	if($('#invoicedummy').is(':checked'))
	{
		var invoicedummy = encodeURIComponent($("#invoicedummy").val());
	}
	
	var passData = "switchtype=editdata&customerid=" + encodeURIComponent($("#customerid").val()) +
	"&pinno=" + encodeURIComponent($("#pinno").val()) + 
	//"&cardid=" + encodeURIComponent($("#cardid").val()) + 
	"&dealerid=" + encodeURIComponent($("#dealerid").val()) +
	"&registerpin=" + encodeURIComponent($("#registerpin").val()) +
	"&cardidhidden=" + encodeURIComponent($("#cardidhidden").val()) +
	"&invoicedummy=" + invoicedummy +
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	//$('#form-error2').html(getprocessingimage());
	var queryString = "../ajax/pindetails.php";
	
	
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			getpindetails('');
			alert('Record Edited');
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
	
}

function deletedata()
{
	var form = $('#submitform');
	var passData = "";
	
	var passData = "switchtype=deletedata&cardid=" + encodeURIComponent($("#cardid").val()) + 
	"&cardidhidden=" + encodeURIComponent($("#cardidhidden").val()) +
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	//$('#form-error2').html(getprocessingimage());
	var queryString = "../ajax/pindetails.php";
	
	
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			getpindetails('');
			alert('Record Deleted');
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
	
}

function getcustname()
{
	var form = $('#submitform');
	var passData = "";
	
	var passData = "switchtype=getcustname&customerid=" + encodeURIComponent($("#customerid").val()) + 
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	//$('#form-error2').html(getprocessingimage());
	var queryString = "../ajax/pindetails.php";
	
	
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,
		success: function(ajaxresponse,status)
		{	
			$("#customername").val(ajaxresponse);
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function editformdata()
{
	
	$("#dealerid").removeClass("swifttext-white");
	$("#dealerid").addClass("swifttext");
	$("#dealerid").removeAttr("readonly");
	
	$("#customerid").removeClass("swifttext-white");
	$("#customerid").addClass("swifttext");
	$("#customerid").removeAttr("readonly");
	
	$('#dealerinputdiv').hide();
	$('#dealerselectiondiv').show();
	
	$('#invoicedummydiv').show();
	$('#dealersearchdiv').show();
	
}

function canceleditform()
{
	getpindetails('');
}

function hidebuttons()
{
	//alert($("#tabledata").val());
	if($("#blocked").val() == "yes" && $("#cancelled").val() == "yes")
	{
		$('#displaydiv').hide();
		$('#displaybutton').hide();
	}
	if($("#blocked").val() == "no" && $("#cancelled").val() == "no")
	{
		$('#displaydiv').show();
		$('#displaybutton').show();
	}
	if($("#blocked").val() == "yes" && $("#cancelled").val() == "no")
	{
		$('#displaydiv').hide();
		$('#displaybutton').hide();
	}
	if($("#blocked").val() == "no" && $("#cancelled").val() == "yes")
	{
		$('#displaydiv').hide();
		$('#displaybutton').hide();
	}
	if($("#tabledata").val() == "no record")
	{
		$('#displaydiv').hide();
		$('#displaybutton').hide();
	}
	
}

function clearsearchform()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#filter-form-error').html('');
	$('#tabgroupgridc3_1').html('');
	$('#tabgroupgridc1linksearch').html('');
	$('#tabgroupgridrcic3_1').html('');
	$('#tabgroupgridc1linkrcisearch').html('');
	$('#displaydiv').hide();
	$('#displaybutton').hide();
}
