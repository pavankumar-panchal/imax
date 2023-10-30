//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/outstandingregister.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totaloutstanding').val(response[5]);
					$('#totaloutstanding').attr('title',response[6]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" or  "show all records" link ------------------------------------------  
function getmoreinvoicedetails(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/outstandingregister.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totaloutstanding').val(response[5]);
					$('#totaloutstanding').attr('title',response[6]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var sortby = $('#sortby').val();
	var sortby1 = $('#sort').val();
	var field = $('#aged');
	if(!field.val()) {error.html(errormessage('Please enter the number of Days')); field.focus(); return false;  }
	if(field.val())	{ if(!validatepositivenumbers(field.val())) { error.html(errormessage('Days are not valid.')); field.focus(); return false; } }
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var values = validateproductcheckboxes();
	var itemvalues = validateitemcheckboxes();
	if((values == false))
	{
		if(itemvalues == false)
		{
			$('#form-error').html(errormessage("Select A Product/Item")); return false;	
		}
	}	
	
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
	
	var listchks_value = '';
	var listchks = $("input[name='itemarray[]']");
	for (var i = 0; i < listchks.length; i++)
	{
		if ($(listchks[i]).is(':checked'))
		{
			listchks_value += $(listchks[i]).val() + ',';
		}
	}
	
	var itemlist = listchks_value.substring(0,(listchks_value.length-1));
	
	var field = $('#cancelledinvoice:checked').val();
	if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';

	error.html('');
	$('#hiddentotalinvoices').val('');
	$('#hiddentotaloutstanding').val('');
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&startlimit=" + encodeURIComponent(startlimit) + "&sortby=" + encodeURIComponent(sortby) + "&aged=" + encodeURIComponent($('#aged').val()) + "&sortby1=" + encodeURIComponent(sortby1)+"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice)  + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val()) + "&itemlist=" + encodeURIComponent(itemlist) + "&dummy=" + Math.floor(Math.random()*1000782200000); 
	error.html(getprocessingimage());
	var queryString = "../ajax/outstandingregister.php";
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
				error.html('') ;
				var response = ajaxresponse.split('^');//alert(response[2])
				if(response[0] == '1')
				{
					gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
					$('#filterdiv').hide()
					$('#tabgroupgridwb2').html("Total Count :  " + response[2]);
					$('#tabgroupgridc2_1').html(response[1]);
					$('#tabgroupgridc2link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totaloutstanding').val(response[5]);
					$('#totaloutstanding').attr('title',response[6]);
					
					$('#hiddentotalinvoices').val(response[4]);
					$('#hiddentotaloutstanding').val(response[5]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
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
	var fromdate = $('#DPC_fromdate').val();
	var sortby = $('#sortby').val();
	var sortby1 = $('#sort').val();
	var error = $('#form-error');
	var field = $('#aged');
	if(!field.val()) {error.html(errormessage('Please enter the number of Days')); field.focus(); return false;  }
	if(field.val())	{ if(!validateamount(field.val())) { error.html(errormessage('Days are not valid.')); field.focus(); return false; } }
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var values = validateproductcheckboxes();
	var itemvalues = validateitemcheckboxes();
	if((values == false))
	{
		if(itemvalues == false)
		{
			$('#form-error').html(errormessage("Select A Product/Item")); return false;	
		}
	}	
	
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
	
	var listchks_value = '';
	var listchks = $("input[name='itemarray[]']");
	for (var i = 0; i < listchks.length; i++)
	{
		if ($(listchks[i]).is(':checked'))
		{
			listchks_value += $(listchks[i]).val() + ',';
		}
	}
	
	var itemlist = listchks_value.substring(0,(listchks_value.length-1));
	
	var field = $('#cancelledinvoice:checked').val();
	if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
	$('#hiddentotalinvoices').val('');
	$('#hiddentotaloutstanding').val('');
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&sortby=" + encodeURIComponent(sortby) + "&aged=" + encodeURIComponent($('#aged').val())+ "&sortby1=" + encodeURIComponent(sortby1) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)+"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val()) + "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice)  + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val()) + "&itemlist=" + encodeURIComponent(itemlist) + "&dummy=" + Math.floor(Math.random()*1000782200000);

	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/outstandingregister.php";
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
				error.html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb2').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgrid').html($('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc2link').html(ajaxresponse[3]);
					$('#totalinvoices').val(ajaxresponse[4]);
					$('#totaloutstanding').val(ajaxresponse[5]);
					$('#totaloutstanding').attr('title',ajaxresponse[6]);
					
					$('#hiddentotalinvoices').val(ajaxresponse[4]);
					$('#hiddentotaloutstanding').val(ajaxresponse[5]);
				}
				else
				{
					$('#tabgroupgridc2_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}

function filtertoexcel(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	error.html(getprocessingimage());
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../ajax/outstandingregistertoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}


function sendoutstandingemail(invoiceno)
{
	var error = $('#form-error');
	var confirmation = confirm('Are you sure to send outstanding email to the selected customer??');
	if(confirmation)
	{
		var passData = "switchtype=sendoutstandingmail&invoiceno="+invoiceno+"&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData)
		error.html(getprocessingimage());
		var queryString = "../ajax/outstandingregister.php";
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
					error.html('') ;
					var response =ajaxresponse.split('^');// alert(response);
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
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
		return false;
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
				$('#groupvalue').val($("#selectproduct option:selected").val());
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

/*function resetDefaultValues()
{
    $('#searchcriteria').val('');
	$('#businessname').attr("checked","checked");
	$('#cancelledinvoice').attr('checked',true);
	$('#region').val('');
	$('#branch').val('');
	$('#state2').val('');
	$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>')
	$('#currentdealer').val('');
	$('#generatedby').val('');
	$('#series').val('');
	$('#status').val('');
	$('#receiptstatus').val('');
	$('#selectproduct').val('ALL');
	$('#loadselection').val('default');
	$('#sortby').val('age');
	$('#sort').val('asc');
	$('#aged').val('0');
	$("input[name='productarray[]']").attr('checked',true);
}*/


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

function outreceiptsearchsettings()
{
	var reply =  prompt("Give a name for the current settings.");
	if (reply == null || reply == "") 
	{
		alert("Please enter the name for the current settings.");
		var reply = prompt('Give a name for the current settings.');
		return false;
	}
	else 
	{
		var selectiontype = reply;
	}
	
	var textfield = $("#searchcriteria").val();
	
	var subselection = $("input[name='databasefield']:checked").val();
	
	var region = $("#region option:selected").val();
	if(region == '')
		var regionvalue = 'ALL';
	else
		var regionvalue = region;
	var branch = $("#branch option:selected").val();
	if(branch == '')
		var branchvalue = 'ALL';
	else
		var branchvalue = branch;
	var state = $("#state2 option:selected").val();
	if(state == '')
		var statevalue = 'ALL';
	else
		var statevalue = state;
	var district = $("#district2 option:selected").val();
	if(district == '')
		var districtvalue = 'ALL';
	else
		var districtvalue = district;
	var dealer = $("#currentdealer option:selected").val();
	if(dealer == '')
		var dealervalue = 'ALL';
	else
		var dealervalue = dealer;
	var generatedby = $("#generatedby option:selected").val();
	if(generatedby == '')
		var generatedbyvalue = 'ALL';
	else
		var generatedbyvalue = generatedby;
	var series = $("#series option:selected").val();
	if(series == '')
		var seriesvalue = 'ALL';
	else
		var seriesvalue = series;
	var status = $("#status option:selected").val();
	if(status == '')
		var statusvalue = 'ALL';
	else
		var statusvalue = status;
	
	var receiptstatus = $("#receiptstatus option:selected").val();
	if(receiptstatus == '')
		var receiptstatusvalue = 'ALL';
	else
		var receiptstatusvalue = receiptstatus;
	
	var selection = regionvalue + '#' + branchvalue + '#' + statevalue + '#' + districtvalue + '#' + dealervalue + '#' + generatedbyvalue + '#' + seriesvalue + '#' + statusvalue + '#' + receiptstatusvalue ;
	
	var field = $('#cancelledinvoice:checked').val();
	if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
	
	var values = validateproductcheckboxes();
	var itemvalues = validateitemcheckboxes();
	if((values == false))
	{
		if(itemvalues == false)
		{
			$('#form-error').html(errormessage("Select A Product/Item")); return false;	
		}
	}		
	
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += $(chks[i]).val()+ '*';
		}
	}
	var productslist = c_value.substring(0,(c_value.length-1));
	
	var listchks_value = '';
	var listchks = $("input[name='itemarray[]']");
	for (var i = 0; i < listchks.length; i++)
	{
		if ($(listchks[i]).is(':checked'))
		{
			listchks_value += $(listchks[i]).val() + '#';
		}
	}
	var itemlist = listchks_value.substring(0,(listchks_value.length-1));
	//var selectproductgroup = $("#groupvalue").val();
	
	var passData = "switchtype=searchsettings&textfield=" + encodeURIComponent(textfield) + "&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&selection=" + encodeURIComponent(selection) + "&productslist=" + encodeURIComponent(productslist) + "&itemlist=" + encodeURIComponent(itemlist)  + "&selectiontype=" + encodeURIComponent(selectiontype)+ "&cancelledinvoice=" + encodeURIComponent(cancelledinvoice)+ "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/outstandingregister.php";
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
					alert(response[1]);
					outreceiptloadselection(response[2]);
				}
				else if(response[0] == '2')
				{
					$('#form-error').html(errormessage(response[1]));
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

function outreceiptloadselection(userid)
{
	var passData = "switchtype=loadselection&userid=" + encodeURIComponent(userid) + "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/outstandingregister.php";
	ajaxcall7 = $.ajax(
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#displayloadselection').html(response[1]);
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

function outreceiptdisplayselection()
{
	if($('#loadselection').val() == 'default')
	{
		resetDefaultValues(document.forms["submitform"]);
	}
	else
	{
		var passData = "switchtype=displayselection&lastslno=" + encodeURIComponent($('#loadselection').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		$('#form-error').html(getprocessingimage());	
		queryString = "../ajax/outstandingregister.php";
		ajaxcall8 = $.ajax(
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
					var response = ajaxresponse.split('*^*');//alert(response)
					if(response[0] == '1')
					{
						var a_respone = response[1].split('$@$');
						if(a_respone[0] == '')
							$('#searchcriteria').val("");
						else
							$('#searchcriteria').val(a_respone[0]);
						$('#'+a_respone[1]).attr("checked","checked");
						if (a_respone[2] == 'no')
							$('#cancelledinvoice').attr('checked',false);
						else if (a_respone[2] == 'yes')
							$('#cancelledinvoice').attr('checked',true);
						var s_respone = a_respone[3].split('#');
						if(s_respone[0] == 'ALL')
							$('#region').val("");
						else
							$('#region').val(s_respone[0]);
						$('#region').val();
						
						if(s_respone[1] == 'ALL')
							$('#branch').val("");
						else
							$('#branch').val(s_respone[1]);
						if(s_respone[2] == 'ALL')
						{
							$('#state2').val("");
						}
						else
						{
							$('#state2').val(s_respone[2]);
							getdistrictfilter('districtcodedisplaysearch',s_respone[2]);
						}
						if(s_respone[3] == 'ALL')
							$('#district2').val("");
						else
							$('#district2').val(s_respone[3]);
						if(s_respone[4] == 'ALL')
							$('#currentdealer').val("");
						else
							$('#currentdealer').val(s_respone[4]);
						if(s_respone[5] == 'ALL')
							$('#generatedby').val("");
						else
							$('#generatedby').val(s_respone[5]);
						if(s_respone[6] == 'ALL')
							$('#series').val("");
						else
							$('#series').val(s_respone[6]);
						if(s_respone[7] == 'ALL')
							$('#status').val("");
						else
							$('#status').val(s_respone[7]);
						
						if(s_respone[8] == 'ALL')
							$('#receiptstatus').val("");
						else
							$('#receiptstatus').val(s_respone[8]);
						
						var unselect =  $("input[name='productarray[]']").attr('checked',false);
						var chkvalues =  a_respone[4].split('*');
						for (var i=0; i < chkvalues.length; i++)
						{
							$('input[value=\'' + chkvalues[i] + '\']').attr('checked', true);
						}
						$('#selectproduct').val('ALL');
						
						var unselectitem =  $("input[name='itemarray[]']").attr('checked',false);
						var chkvalues_item =  a_respone[5].split('#');
						for (var i=0; i < chkvalues_item.length; i++)
						{
							$('input[value=\'' + chkvalues_item[i] + '\']').attr('checked', true);
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
}

function deleteselectedsettings()
{
	var form = $('#submitform');
	var loadselectionname = $('#loadselection option:selected').text();
	if($('#loadselection').val() == 'default')
	{
		alert('Default settings cannot be Deleted.');return false;
	}
	else
	{
		var confirmation = confirm("Are you sure you want to delete the selected Selection.");
		if(confirmation)
		{
			var passData = "switchtype=deleteselection&lastslno=" + encodeURIComponent($('#loadselection').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
			$('#form-error').html(getprocessingimage());	
			queryString = "../ajax/outstandingregister.php";
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
						$('#form-error').html('');
						var response = ajaxresponse.split('^');//alert(response)
						if(response[0] == '1')
						{
							alert(response[1]);
							outreceiptloadselection(response[2]);
							resetDefaultValues(document.forms["submitform"]);
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
		else
		return false;
	}
}

function validateitemcheckboxes()
{
var chks_value = $("input[name='itemarray[]']");
var h_Checked = false;
for (var i = 0; i < chks_value.length; i++)
{
	if ($(chks_value[i]).is(':checked'))
	{
		h_Checked = true;
		return true
	}
}
	if (!h_Checked)
	{
		return false
	}
}


function updateremarks(invoiceslno,remarks,id,displaytype)
{
	$('#cboxClose').remove();
	$('#remarks1progress').html('');
	$('#remarks2progress').html('');
	$('#outstandingremarks1').val('');
	$('#outstandingremarks2').val('');
	if(displaytype == 'default')
	{
		if(remarks == 'remarks1')
		{
			$('#invoiceslno1').val(invoiceslno); 
			var remarksid1 = 'hiddenremarks1'+id;
			$('#outstandingremarks1').val($('#hiddenremarks1'+id).val());
			$("").colorbox({ inline:true, href:"#remarks1" , onLoad: function() { $('#cboxClose').hide()}});
		}
		else if(remarks == 'remarks2')
		{
			$('#invoiceslno2').val(invoiceslno);
			var remarksid1 = 'hiddenremarks1'+id;
			$('#outstandingremarks2').val($('#hiddenremarks2'+id).val());
			$("").colorbox({ inline:true, href:"#remarks2" , onLoad: function() { $('#cboxClose').hide()}});
		}
	}
	else if(displaytype == 'search')
	{
		if(remarks == 'remarks1')
		{
			$('#invoiceslno1').val(invoiceslno); //alert($('#hiddensearchremarks1'+id).val());
			$('#outstandingremarks1').val($('#hiddensearchremarks1'+id).val());
			$("").colorbox({ inline:true, href:"#remarks1" , onLoad: function() { $('#cboxClose').hide()}});
		}
		else if(remarks == 'remarks2')
		{
			$('#invoiceslno2').val(invoiceslno);
			$('#outstandingremarks2').val($('#hiddensearchremarks2'+id).val());
			$("").colorbox({ inline:true, href:"#remarks2" , onLoad: function() { $('#cboxClose').hide()}});
		}
	}
}

function closecolorbox()
{
	$().colorbox.close();
}


function enterremarks(type)
{
	if(type == '1')
	{
		if($('#outstandingremarks1').val() == '')
		{
			$('#remarks1progress').html(errormessage('Enter the remarks'));
			$('#outstandingremarks1').focus();
			return false;
		}
		$('#remarks1progress').html(getprocessingimage()); //alert($('#outstandingremarks1').val());
		var passData = "switchtype=updateremarks&remarks=" + encodeURIComponent($('#outstandingremarks1').val())+"&type="+type+ "&invoiceno="+encodeURIComponent($('#invoiceslno1').val())+"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		queryString = "../ajax/outstandingregister.php";
		ajaxcall10 = $.ajax(
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
					$('#remarks1progress').html('');
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == '1')
					{
						$('#remarks1progress').html(successmessage(response[1]));
						$().colorbox.close();
						gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default');
						getinvoicedetails('');
						
					}
					else
					   $('#remarks1progress').html(scripterror());
				}
			}, 
			error: function(a,b)
			{
				$("#remarks1progress").html(scripterror());
			}
		});	
	}
	else if(type == '2')
	{
		if($('#outstandingremarks2').val() == '')
		{
			$('#remarks2progress').html(errormessage('Enter the remarks'));
			$('#outstandingremarks2').focus();
			return false;
		}
		$('#remarks2progress').html(getprocessingimage());
		var passData = "switchtype=updateremarks&remarks=" + encodeURIComponent($('#outstandingremarks2').val())+"&type="+type+ "&invoiceno="+encodeURIComponent($('#invoiceslno2').val())+"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		queryString = "../ajax/outstandingregister.php";
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
					$('#remarks2progress').html('');
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == '1')
					{
						$('#remarks2progress').html(successmessage(response[1]));
						$().colorbox.close();
						gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default');
						getinvoicedetails('');
						
					}
					else
					   $('#remarks2progress').html(scripterror());
				}
			}, 
			error: function(a,b)
			{
				$("#remarks2progress").html(scripterror());
			}
		});	
	}
}

function displayoutstdingtotal()
{
	$('#totalinvoices').val($('#hiddentotalinvoices').val());
	$('#totaloutstanding').val($('#hiddentotaloutstanding').val());
}

