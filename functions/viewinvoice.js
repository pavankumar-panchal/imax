//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/viewinvoice.php";
	ajaxcall3 = createajax();
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			if(ajaxcall3.status == 200)
			{
				var ajaxresponse = ajaxcall3.responseText;
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
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
				
			}
			else
				$('#tabgroupgridc1_1').html(scripterror());
		}
	}
	ajaxcall3.send(passData);
}

//Function for "show more records" or  "show all records" link ------------------------------------------  
function getmoreinvoicedetails(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/viewinvoice.php";
	ajaxcall14 = createajax();
	$('#tabgroupgridc1link').html(getprocessingimage());
	ajaxcall14.open("POST", queryString, true);
	ajaxcall14.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall14.onreadystatechange = function()
	{
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
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#resultgrid').html($('#tabgroupgridc1_1').html());
						$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
						$('#tabgroupgridc1link').html(response[3]);
					}
					else
					{
						$('#tabgroupgridc1_1').html("No datas found to be displayed.");
					}
				}
			}
			else
				$('#tabgroupgridc1_1').html(scripterror());
		}
	}
	ajaxcall14.send(passData);
}

//Function for grid to form ------------------------------------------
function gridtoform(slno)
{
	if(slno != '')
	{
		var form = $('#submitform');
		var passData = "switchtype=gridtoform&slno="+ encodeURIComponent(slno);
		$('#productselectionprocess').html(getprocessingimage());
		ajaxcall3 = createajax();
		var queryString = "../ajax/viewinvoice.php";
		ajaxcall3.open("POST", queryString, true);
		ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall3.onreadystatechange = function()
		{
			if(ajaxcall3.readyState == 4)
			{
				if(ajaxcall3.status == 200)
				{
					$('#productselectionprocess').html('');
					var ajaxresponse = ajaxcall3.responseText;
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
							$('#displayinvoicetext').hide();
							$('#displaygridinfo').show();
							$('#displaygridinfo').html(response[1]);
							$('#lastslno').val(slno);
						}
						else
							$('#productselectionprocess').html(scripterror());
				   }
				   
				}
				else
					$('#productselectionprocess').html(scripterror());
			}
		}
		ajaxcall3.send(passData);
	}
}

//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var c_value = '';
	var error = $('#filter-form-error');
	var form = $('#searchfilterform');
	var textfield = $('#searchcriteria').val();
	var subselection = $('input[name=databasefield]:checked').val();
	var orderby = $('#orderby').val();
	var fromdate = $('#DPC_attachfromdate').val();
	var todate = $('#DPC_attachtodate').val();
	var region = $('#region').val();
	var reporttype = $('#reporttype').val();
	var dealerid = $('#dealerid').val();
	var users = $('#users').val();
	var branch = $('#branch').val();
	var generatedby = $('#generatedby').val();
	var chks = $("input[name='productname[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select atleast one Product")); return false;}
	var productslist = c_value.substring(0,(c_value.length-1));
	$('#searchtexthiddenfield').val(textfield); //alert($('#searchtexthiddenfield').val())
	$('#subselectionhiddenfield').val(subselection); //alert($('#subselectionhiddenfield').val())
	$('#orderbyhiddenfield').val(orderby);
	$('#hiddenfromdate').val(fromdate);
	$('#hiddentodate').val(todate);
	$('#hiddengeneratedby').val(generatedby);
	$('#hiddenregion').val(region);
	$('#hiddenbranch').val(branch);
	$('#hiddenreporttype').val(reporttype);
	$('#hiddendealerid').val(dealerid);
	$('#hiddenuserid').val(users);
	$('#hiddenproduct').val(productslist);
	
	error.html('');
	var passData = "switchtype=search&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&reporttype=" + encodeURIComponent(reporttype)+ "&region=" + encodeURIComponent(region) + "&orderby=" + encodeURIComponent(orderby)+ "&dealerid=" + encodeURIComponent(dealerid)+ "&users=" + encodeURIComponent(users) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" + encodeURIComponent(generatedby)+ "&productslist=" + encodeURIComponent(productslist)+ "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	ajaxcall9 = createajax();
	error.html(getprocessingimage());
	var queryString = "../ajax/viewinvoice.php";
	ajaxcall9.open("POST", queryString, true);
	ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall9.onreadystatechange = function()
	{
		if(ajaxcall9.readyState == 4)
		{
			if(ajaxcall9.status == 200)
			{
				error.html('') ;
				var response = ajaxcall9.responseText.split('^');//alert(response[4]);
				if(response[0] == '1')
				{
					gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
					$('#tabgroupgridwb2').html("Total Count :  " + response[2]);
					$('#tabgroupgridc2_1').html(response[1]);
					$('#tabgroupgridc2link').html(response[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
			else
				error.html(scripterror());
		}
	}
	ajaxcall9.send(passData);
}

function getmoresearchfilter(startlimit,slnocount,showtype)
{
	textfield = $('#searchtexthiddenfield').val();
	subselection = $('#subselectionhiddenfield').val();
	orderby = $('#orderbyhiddenfield').val();
	fromdate = $('#hiddenfromdate').val();
	todate = $('#hiddentodate').val();
	generatedby = $('#hiddengeneratedby').val();
	region = $('#hiddenregion').val();
	branch = $('#hiddenbranch').val();
	reporttype = $('#hiddenreporttype').val();
	dealerid = $('#hiddendealerid').val();
	users = $('#hiddenuserid').val();
	productslist = $('#hiddenproduct').val();
	
	var error = $('#filter-form-error');
	var passData = "switchtype=search&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&reporttype=" + encodeURIComponent(reporttype)+ "&region=" + encodeURIComponent(region) + "&orderby=" + encodeURIComponent(orderby)+ "&dealerid=" + encodeURIComponent(dealerid)+ "&users=" + encodeURIComponent(users) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" + encodeURIComponent(generatedby)+ "&productslist=" + encodeURIComponent(productslist)+ "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	
	
/*	var passData = "switchtype=search&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&reporttype=" + encodeURIComponent(reporttype)+ "&region=" + encodeURIComponent(region) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);*/
	ajaxcall10 = createajax();
	error.html(getprocessingimage());
	var queryString = "../ajax/viewinvoice.php";
	ajaxcall10.open("POST", queryString, true);
	ajaxcall10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall10.onreadystatechange = function()
	{
		if(ajaxcall10.readyState == 4)
		{
			if(ajaxcall10.status == 200)
			{
				error.html('');
				var ajaxresponse = ajaxcall10.responseText.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb2').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgrid').html($('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc2link').html(ajaxresponse[3]);
				}
				else
				{
					$('#tabgroupgridc2_1').html("No datas found to be displayed.");
				}
			}
			else
				error.html(scripterror());
		}
	}
	ajaxcall10.send(passData);
}


//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice()
{
	var form = $('#submitform');
	if($('#lastslno').val() == '')
	{
		$('#productselectionprocess').html(errormessage('Select a Invoice first.')); return false;
	}
	else
	{
	//var passData = "switchtype=sendinvoice&invoiceno=" + encodeURIComponent(document.getElementById('lastslno').value ) + "&dummy=" + Math.floor(Math.random()*10054300000);
		$('#productselectionprocess').html('');
		$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}

}

function showhidefilterdiv()
{
	if($('#filterdiv').is(':visible'))
		$('#filterdiv').hide();
	else
		$('#filterdiv').show();
}


function resendinvoice()
{
	var form = $('#submitform');
	var invoiceno = $('#lastslno').val();
	if(invoiceno == '')
	{
		$('#productselectionprocess').html(errormessage('Select a Invoice first.')); return false;
	}
	else
	{
		var confirmation = confirm('Are you sure you want to resend the Invoice?');
		if(confirmation)
		{
			var passData = "switchtype=resendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			var ajaxcall10 = createajax();
			$('#productselectionprocess').html(getprocessingimage());	
			queryString = "../ajax/viewinvoice.php";
			ajaxcall10.open("POST", queryString, true);
			ajaxcall10.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajaxcall10.onreadystatechange = function()
			{
				if(ajaxcall10.readyState == 4)
				{
					if(ajaxcall10.status == 200)
					{
						var ajaxresponse = ajaxcall10.responseText;
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
								$('#productselectionprocess').html(successmessage('Invoice sent successfully to the selected Customer'));
							}
							else
								$('#productselectionprocess').html(errormessage('Connection Failed.'));
						}
					}
					else
						$('#productselectionprocess').html(errormessage('Connection Failed.'));
				}
			}
			ajaxcall10.send(passData);
		}
	else
		return false;
	}
}


function filtertoexcel(command)
{
	var form = $('#filterdiv');
	var error = $('#form-error');
	if(command == 'toexcel')
	{
		var values = validateproductcheckboxes();
		if(values == false)	
		{
			error.html(errormessage('Select atleast one Product'));
			return false;
		}
		else
		{
			error.html('');
			$('#searchfilterform').attr("action", "../ajax/invoicetoexcel.php?id=toexcel") ;
			$('#searchfilterform').submit();
		}
	}
}

function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productname[]']");
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

function displaygeneratedby()
{
	var selectedvalue = $('#generatedby').val();
	if(selectedvalue == 'user')
	{
		$('#userdiv').show();
		$('#dealerdiv').hide();
	}
	else if(selectedvalue == 'dealer')
	{
		$('#dealerdiv').show();
		$('#userdiv').hide();
	}
	else
	{
		$('#dealerdiv').hide();
		$('#userdiv').hide();
	}
}


function validateproductcheckboxes()
{
var chksvalue = $("input[name='productname[]']");
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