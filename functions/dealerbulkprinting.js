//Function to Search the data from Inventory------------------------------------------
function searchfilter()
{
	$('#tabvalue').val('');
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
		
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
	
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)  +"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&usagetype=" +encodeURIComponent($("#usagetype").val())+ "&purchasetype=" +encodeURIComponent($("#purchasetype").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val())+ "&itemlist=" + encodeURIComponent(itemlist) + "&seriesnew=" +encodeURIComponent($("#seriesnew").val()) + "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData)
	error.html(getprocessingimage());
	var queryString = "../ajax/dealerbulkprinting.php";
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
				error.html('') ;
				var response = ajaxresponse.split('^');//alert(response[2])
				if(response[0] == '1')
				{
					gridtab6(1,'tabgroupgrid','&nbsp; &nbsp;Search Results'); 
					$('#filterdiv').hide();
					$("#searchcount").val(response[2]);
					$('#selectall').attr('checked',false);
					$('#tabgroupgridwb6').html("Total Count :  " + response[2]);
					$('#tabgroupgridc6_1').html(response[1]);
					$('#tabgroupgridc6link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totalsalevalue').val(response[5]);
					$('#totaltax').val(response[6]);
					$('#totalamount').val(response[7]);
					$('#searchcountvalue').val('0');
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


//Form Submit for duplicate copy
function formsubmitduplicate()
{
	var form = $('#submitform');
	var error = $('#form-error');
	if(!$('#tabvalue').val()) { error.html(errormessage("Please Select Atleast one Invoice")); return false; }
	else
	{
		error.html('');
		var searchcount = $('#searchcount').val();
		var searcharray = new Array();
		for(var i = 1;i <= searchcount; i++)
		{ 
			var checkbox = 'resultcheckbox' + i;
			if($('#'+checkbox).is(':checked') == true)
			{
				if(searcharray == '')
				{
					searcharray = searcharray + $('#'+checkbox).val();
				}
				else
				{
					searcharray = searcharray + ',' + $('#'+checkbox).val();
				}
			} 
		}
		$('#hiddenslno').val(searcharray);
		$('#submitform').attr("action", "../ajax/dealerbulkprintingpdfduplicate.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}
//form submit Ends

//Function to view the bill in pdf format----------------------------------------------------------------
function viewdealerinvoice(slno)
{
	if(slno != '')
		$('#onlineslno').val(slno);
		
	var form = $('#submitform');	
	if($('#onlineslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	}
	else
	{
		$('#submitform').attr("action", "../ajax/viewdealerinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}


function resenddealerinvoice(invoiceno,slno)
{
	//alert(slno); 
	//alert(invoiceno);
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
			
			var passData = "switchtype=resenddealerinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			$('#resendprocess'+slno).show();
			$('#resendemail'+slno).hide();
			$('#resendprocess'+slno).html(getprocessingimage());	
			var querystring = "../ajax/dealerbulkprinting.php";
			ajaxcall2 = $.ajax(
			{
				type: "POST",url: querystring, data: passData, cache: false,
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
							$('#resendprocess'+slno).hide();
							$('#resendemail'+slno).show();
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

//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$("#form-error").html('');
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

function searchsettings()
{
	var reply = prompt('Give a name for the current settings.');
	
	if(reply == null || reply == "")
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
		
	var seriesnew = $("#seriesnew option:selected").val();
	if(seriesnew == '')
		var seriesvaluenew = 'ALL';
	else
		var seriesvaluenew = seriesnew;	
		
	var status = $("#status option:selected").val();
	if(status == '')
		var statusvalue = 'ALL';
	else
		var statusvalue = status;
	var usagetype = $("#usagetype option:selected").val();
	if(usagetype == '')
		var usagetypevalue = 'ALL';
	else
		var usagetypevalue = usagetype;
	var purchasetype = $("#purchasetype option:selected").val();
	if(purchasetype == '')
		var purchasetypevalue = 'ALL';
	else
		var purchasetypevalue = purchasetype;
	var receiptstatus = $("#receiptstatus option:selected").val();
	if(receiptstatus == '')
		var receiptstatusvalue = 'ALL';
	else
		var receiptstatusvalue = receiptstatus;
	
	var selection = regionvalue + '#' + branchvalue + '#' + statevalue + '#' + districtvalue + '#' + dealervalue + '#' + generatedbyvalue + '#' + seriesvalue + '#' + statusvalue + '#' + usagetypevalue + '#' + purchasetypevalue + '#' + receiptstatusvalue + '#' + seriesvaluenew ;
	
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
	
	var passData = "switchtype=searchsettings&textfield=" + encodeURIComponent(textfield) + "&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&selection=" + encodeURIComponent(selection) + "&productslist=" + encodeURIComponent(productslist) + "&itemlist=" + encodeURIComponent(itemlist)  + "&selectiontype=" + encodeURIComponent(selectiontype)+ "&cancelledinvoice=" + encodeURIComponent(cancelledinvoice)+ "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/dealerbulkprinting.php";
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
				if(response[0] == '1')
				{
					alert(response[1]);
					loadselection(response[2]);
				}
				else if(response[0] == '2')
				{
					alert(response[1]);
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

function loadselection(userid)
{
	var passData = "switchtype=loadselection&userid=" + encodeURIComponent(userid) + "&category=" + encodeURIComponent($('#category').val()) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	$('#form-error').html(getprocessingimage());	
	queryString = "../ajax/dealerbulkprinting.php";
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

function displayselection()
{
	//var form = $('#submitform');
	if($('#loadselection').val() == 'default')
	{
		resetDefaultValues(document.forms["submitform"]);
	}
	else
	{
		var passData = "switchtype=displayselection&lastslno=" + encodeURIComponent($('#loadselection').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
		$('#form-error').html(getprocessingimage());	
		queryString = "../ajax/dealerbulkprinting.php";
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
					var response = ajaxresponse.split('*^*');
					$('#form-error').html('');
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
							$('#usagetype').val("");
						else
							$('#usagetype').val(s_respone[8]);
						if(s_respone[9] == 'ALL')
							$('#purchasetype').val("");
						else
							$('#purchasetype').val(s_respone[9]);
						if(s_respone[10] == 'ALL')
							$('#receiptstatus').val("");
						else
							$('#receiptstatus').val(s_respone[10]);
						
						if(s_respone[11] == 'ALL')
							$('#seriesnew').val("");
						else
							$('#seriesnew').val(s_respone[11]);
						
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
			queryString = "../ajax/dealerbulkprinting.php";
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
						var response = ajaxresponse.split('^');//alert(response)
						if(response[0] == '1')
						{
							alert(response[1]);
							loadselection(response[2]);
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

function displayproductdetails(slno)
{
	if(slno != '')
	{
		var form = $('#detailsform');
		$('#productslno',form).val(slno);
		generateproductdetails();
		$("").colorbox({ inline:true, href:"#productdetailsgrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
}

function generateproductdetails()
{
	var form = $('#detailsform');
	$('#productdetailsgridc1').show();
	//$('#detailsdiv').hide();
	var passData = "switchtype=productdetailsgrid&productslno="+ encodeURIComponent($('#productslno').val()) ;//alert(passData)
	var queryString = "../ajax/dealerbulkprinting.php";
	$('#productdetailsgridc1_1').html(getprocessingimage());
	$('#productdetailsgridc1link').html('');
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
				var response = ajaxresponse.split('^');//alert(response[2])
				if(response[0] == 1)
				{
					$('#productdetailsgridwb1').html("Total Count :  " + response[2]);
					$('#productdetailsgridc1_1').html(response[1]);
					$('#productdetailsgridc1link').html(response[3]);
					
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#productdetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productdetailsgridc1_1").html(scripterror());
		}
	});	
}

function generateservicedetails()
{
	var form = $('#detailsform');
	$('#productdetailsgridc1').show();
	//$('#detailsdiv').hide();
	var passData = "switchtype=servicedetailsgrid&productslno="+ encodeURIComponent($('#productslno').val()) ;//alert(passData)
	var queryString = "../ajax/dealerbulkprinting.php";//alert(passData)
	$('#productdetailsgridc1_1').html(getprocessingimage());
	$('#productdetailsgridc1link').html('');
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
				var response = ajaxresponse.split('^');//alert(response[2])
				if(response[0] == 1)
				{
					$('#productdetailsgridwb1').html("Total Count :  " + response[2]);
					$('#productdetailsgridc1_1').html(response[1]);
					$('#productdetailsgridc1link').html(response[3]);
					//$('#invoicelastslno').val(response[4]);
				}
				else
				{
					$('#productdetailsgridc1_1').html("No datas found to be displayed");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productdetailsgridc1_1").html(scripterror());
		}
	});	
}

function displayinvoicetotal()
{
	$('#totalinvoices').val($('#hiddentotalinvoices').val());
	$('#totalsalevalue').val($('#hiddentotalsalevalue').val());
	$('#totaltax').val($('#hiddentotaltax').val());
	$('#totalamount').val($('#hiddentotalamount').val());
}

function gridtab6(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 1;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		var tabviewbox = tabgroupname + 'view' + i;

		
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			if($('#'+tabcontent)) { $('#'+tabcontent).show(); }
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).hide(); }
		}
	}
}

function formsubmit()
{
	var form = $('#submitform');
	var error = $('#form-error');
	if(!$('#tabvalue').val()) { error.html(errormessage("Please Select Atleast one Invoice")); return false; }
	else
	{
		error.html('');
		var searchcount = $('#searchcount').val();
		var searcharray = new Array();
		for(var i = 1;i <= searchcount; i++)
		{ 
			var checkbox = 'resultcheckbox' + i;
			if($('#'+checkbox).is(':checked') == true)
			{
				if(searcharray == '')
				{
					searcharray = searcharray + $('#'+checkbox).val();
				}
				else
				{
					searcharray = searcharray + ',' + $('#'+checkbox).val();
				}
			} 
		}
		$('#hiddenslno').val(searcharray);
		$('#submitform').attr("action", "../ajax/dealerbulkprintingpdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

function selectanddeselect(selecttype)
{
	$('#tabvalue').val('');
	var count = 0;var recount = 0;
	var searchcount = $('#searchcount').val(); 
	if(selecttype == 'all' && $('#selectall').is(':checked') == true)
	{
		for(var i = 1;i <= searchcount; i++)
		{ 
			//totalcheckedcount++;
			var checkbox = 'resultcheckbox' + i;
			$('#' + checkbox).attr('checked',true) ;
			count++;
			if($('#'+checkbox).is(':checked') == true)
				$('#tabvalue').val('setvalue');
			else
				$('#tabvalue').val('');
		}
		$('#searchcountvalue').val(count);
		
	}
	else if(selecttype == 'all' && $('#selectall').is(':checked') == false)
	{
		for(var i = 1;i <= searchcount; i++)
		{
			var checkbox = 'resultcheckbox' + i;
			$('#' + checkbox).attr('checked',false);
		}
		$('#tabvalue').val('');
		$('#searchcountvalue').val(count);
	}
	else if(selecttype == 'countselected')
	{
		for(var i = 1;i <= searchcount; i++)
		{
			var checkbox = 'resultcheckbox' + i;
			if($('#'+checkbox).is(':checked') == true)
				recount++;
				
		}
		$('#tabvalue').val('setvalue');
		$('#searchcountvalue').val(recount);
	}
	
}