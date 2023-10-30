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


function receiptsearchsettings()
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

	var m_value = '';
	var matrixvalue = new Array();
	var mchks = $("input[name='matrixarray[]']");
	for (var i = 0; i < mchks.length; i++)
	{
		if ($(mchks[i]).is(':checked'))
		{
			m_value += $(mchks[i]).val()+ '*';
		}
	}
	var matrixlist = m_value.substring(0,(m_value.length-1));
	
	//var selectproductgroup = $("#groupvalue").val();
	
	var passData = "switchtype=searchsettings&textfield=" + encodeURIComponent(textfield) + "&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&selection=" + encodeURIComponent(selection) + "&productslist=" + encodeURIComponent(productslist)+ "&matrixlist=" + encodeURIComponent(matrixlist) + "&itemlist=" + encodeURIComponent(itemlist)  + "&selectiontype=" + encodeURIComponent(selectiontype)+ "&cancelledinvoice=" + encodeURIComponent(cancelledinvoice)+ "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/receiptreconsilation.php";
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
				$('#form-error').html('');	
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					alert(response[1]);
					receiptloadselection(response[2]);
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

function receiptloadselection(userid)
{
	var passData = "switchtype=loadselection&userid=" + encodeURIComponent(userid) + "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/receiptreconsilation.php";
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

function receiptdisplayselection()
{
	if($('#loadselection').val() == 'default')
	{
		resetDefaultValues(document.forms["submitform"]);
	}
	else
	{
		var passData = "switchtype=displayselection&lastslno=" + encodeURIComponent($('#loadselection').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		$('#form-error').html(getprocessingimage());	
		queryString = "../ajax/receiptreconsilation.php";
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
			queryString = "../ajax/receiptreconsilation.php";
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
						var response = ajaxresponse.split('^');//alert(response)
						if(response[0] == '1')
						{
							alert(response[1]);
							receiptloadselection(response[2]);
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


//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var paymentmode = $('#paymentmode').val();
	
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var reconsilation = $("#reconsilation option:selected").val();
	
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

	var m_value = '';
	var mchks = $("input[name='matrixarray[]']");
	for (var i = 0; i < mchks.length; i++)
	{
		if ($(mchks[i]).is(':checked'))
		{
			m_value += $(mchks[i]).val()+ ',';
		}
	}
	var matrixlist = m_value.substring(0,(m_value.length-1));
	
	var field = $('#cancelledinvoice:checked').val();
	if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
	$('#hiddentotalreceipts').val('');
	$('#hiddentotalamount').val('');

	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&paymentmode=" + encodeURIComponent(paymentmode) + "&startlimit=" + encodeURIComponent(startlimit)+"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist)+  "&matrixlist=" +encodeURIComponent(matrixlist) +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val()) + "&itemlist=" + encodeURIComponent(itemlist) + "&reconsilation=" + encodeURIComponent(reconsilation) + "&dummy=" + Math.floor(Math.random()*1000782200000); 
	console.log(passData);
	error.html(getprocessingimage());
	var queryString = "../ajax/receiptreconsilation.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('div#tabgroupgridc1').css("height", "");
					$('#filterdiv').hide();
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					$('#tabgroupgridwb1').html("(Total Count :  " + response[2] +")");
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
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var paymentmode = $('#paymentmode').val();
	var error = $('#form-error');
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var reconsilation = $("#reconsilation option:selected").val();

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

	var m_value = '';
	var mchks = $("input[name='matrixarray[]']");
	for (var i = 0; i < mchks.length; i++)
	{
		if ($(mchks[i]).is(':checked'))
		{
			m_value += $(mchks[i]).val()+ ',';
		}
	}
	var matrixlist = m_value.substring(0,(m_value.length-1));
	
	var productslist = c_value.substring(0,(c_value.length-1));
	var field = $('#cancelledinvoice:checked').val();
	if(field != 'on') var cancelledinvoice = 'no'; else cancelledinvoice = 'yes';
	$('#hiddentotalreceipts').val('');
	$('#hiddentotalamount').val('');
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&paymentmode=" + encodeURIComponent(paymentmode) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val()) + "&itemlist=" + encodeURIComponent(itemlist) + "&reconsilation=" + encodeURIComponent(reconsilation) +  "&matrixlist=" +encodeURIComponent(matrixlist) +"&dummy=" + Math.floor(Math.random()*1000782200000);

	$('#tabgroupgridc1link').html(getprocessingimage());
	var queryString = "../ajax/receiptreconsilation.php";
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
					$('div#tabgroupgridc1').css("height", "")
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc1link').html(ajaxresponse[3]);
					$('#tabgroupgridwb1').html("(Total Count :  " + ajaxresponse[2] +")");
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

function generatetooltip(slno)
{
  if(slno == '')
  	return false;
  else
  {
	var passdata = "&switchtype=tooltip&lastslno=" +(slno)+"&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/receiptreconsilation.php";//alert(passdata)
	tipobj.style.display="block";
	tipobj.innerHTML = getprocessingimage();
	enabletip=true;
   // return false;
	ajaxobjext37 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,
		success: function(response,status)
		{	
			if(response == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
			  var ajaxresponse = response.split('^');
			  if(ajaxresponse[0] == '1')
			  {
				  if (ns6||ie) 
				  {
					//tipobj.style.display="block";
					tipobj.innerHTML = ajaxresponse[1]; 	//alert(tipobj.innerHTML); 
					enabletip=true;
					return false;
				  }
			  }
			}
		}, 
		error: function(a,b)
		{
			outputselect.html(scripterror());
		}
	});	
  }
}


function submitreconsileform(slno,id1,id2,selectid)
{
	var form = $('#submitform');
	var reconsilevalue = $('#'+selectid).val();
	if(reconsilevalue == 'notseen')
	{
		alert('Please selected Matched / Unmatched to Reconcile');
		return false;
	}
	else
	{
	
		$('#'+id1).html('');
		$('#'+id2).remove();
		$('#'+id1).attr("rowSpan",2);
		$('#'+id1).html('<img src="../images/ajax-loader.gif" border="0"/>');
		
		var passData = "&switchtype=reconsileform&lastslno=" + encodeURIComponent(slno) + "&reconsilevalue=" + encodeURIComponent(reconsilevalue) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		queryString = "../ajax/receiptreconsilation.php";
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
					$('#'+id1).html('');
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$('#'+id1).html(successmessage(response['errormsg']));
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

