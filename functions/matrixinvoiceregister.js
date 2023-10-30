//Function to display records ------------------------------------------
function getinvoicedetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=invoicedetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/matrixinvoiceregister.php";
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
						//console.log(response[6]);
						var totaltax_amount = response[6];						
						var newnumber = parseFloat(totaltax_amount.split(',').join(''));						
						var totaltax_amount = newnumber.toFixed(2);
						
						console.log(totaltax_amount);
						
						$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
						$('#tabgroupgridc1_1').html(response[1]);
						$('#tabgroupgridc1link').html(response[3]);
						$('#totalinvoices').val(response[4]);
						$('#totalsalevalue').val(response[5]);
						$('#totaltax').val(response[6]);
						//$('#totaltax').val(totaltax_amount);
						$('#totalamount').val(response[7]);
						$('#totalsalevalue').attr('title',response[8]);
						$('#totaltax').attr('title',response[9]);
						$('#totalamount').attr('title',response[10]);
						$('#productsummarydiv').html(response[11]);
						/*$('#totalproductamount').val(response[11]);
						$('#totalserviceamountamount').val(response[12]);
						$('#totalproductamount').attr('title',response[13]);
						$('#totalserviceamountamount').attr('title',response[14]);*/
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
	var queryString = "../ajax/matrixinvoiceregister.php";
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
						$('#totalsalevalue').val(response[5]);
						$('#totaltax').val(response[6]);
						$('#totalamount').val(response[7]);
						$('#totalsalevalue').attr('title',response[8]);
						$('#totaltax').attr('title',response[9]);
						$('#totalamount').attr('title',response[10]);
						$('#productsummarydiv').val(response[11]);
						$('#itemssummarydiv').html(response[12]);
						$('#itemsothersdiv').html(response[13]);
						/*$('#totalproductamount').val(response[11]);
						$('#totalserviceamountamount').val(response[12]);
						$('#totalproductamount').attr('title',response[13]);
						$('#totalserviceamountamount').attr('title',response[14]);*/
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

	if(typeof($('#datepicker1').val())!= 'undefined' &&  typeof($('#datepicker').val())!= 'undefined')
	{
		var todate = $('#datepicker1').val();
		var fromdate = $('#datepicker').val();
		//alert(fromdate);
	}
	else
	{
		var fromdate = $('#DPC_fromdate').val();
		//alert(fromdate);
		var todate = $('#DPC_todate').val();

		var field = $('#DPC_fromdate');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
		var field = $('#DPC_todate');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}

	if ($('#datepicker').val() == '')
	{
		var field = $('#datepicker');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}
	else if ($('#datepicker1').val() == '')
	{

		var field = $('#datepicker1');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}

	error.html('');
	$('#hiddentotalinvoices').val('');
	$('#hiddentotalsalevalue').val('');
	$('#hiddentotaltax').val('');
	$('#hiddentotalamount').val('');
	
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var itemvalues = validateitemcheckboxes();
	if(itemvalues == false)
	{
		$('#form-error').html(errormessage("Select A Product/Item")); return false;	
	}
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	var listchks_value = '';
	var listchks = $("input[name='matrixarray[]']");
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
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	
	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) +"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield)  +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice) + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val())+ "&itemlist=" + encodeURIComponent(itemlist)+ "&alltimecheck=" +encodeURIComponent(alltimecheck) + "&seriesnew=" +encodeURIComponent($("#seriesnew").val()) + "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData)
	error.html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				var response = ajaxresponse.split('^');//alert(response[12]);//alert(response[4]); //alert(response[5]); alert(response[6]); alert(response[7]);
				if(response[0] == '1')
				{
					gridtab6(6,'tabgroupgrid','&nbsp; &nbsp;Search Results'); 
					$('#filterdiv').hide();
					$('#tabgroupgridwb6').html("Total Count :  " + response[2]);
					$('#tabgroupgridc6_1').html(response[1]);
					$('#tabgroupgridc6link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totalsalevalue').val(response[5]);
					$('#totaltax').val(response[6]);
					$('#totalamount').val(response[7]);
					
					$('#hiddentotalinvoices').val(response[4]);
					$('#hiddentotalsalevalue').val(response[5]);
					$('#hiddentotaltax').val(response[6]);
					$('#hiddentotalamount').val(response[7]);
					$('#totalsalevalue').attr('title',response[8]);
					$('#totaltax').attr('title',response[9]);
					$('#totalamount').attr('title',response[10]);
					$('#productsummarydiv').html(response[11]);
					$('#itemssummarydiv').html(response[12]);
					$('#itemsothersdiv').html(response[13]);
					/*$('#totalproductamount').val(ajaxresponse[11]);
					$('#totalserviceamountamount').val(ajaxresponse[12]);
					$('#totalproductamount').attr('title',ajaxresponse[13]);
					$('#totalserviceamountamount').attr('title',ajaxresponse[14]);*/
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
	// var fromdate = $('#DPC_fromdate').val();
	// var field = $('#DPC_fromdate');
	// if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	// var todate = $('#DPC_todate').val();
	// var field = $('#DPC_todate');
	// if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }

	if(typeof($('#datepicker1').val())!= 'undefined' &&  typeof($('#datepicker').val())!= 'undefined')
	{
		var todate = $('#datepicker1').val();
		var fromdate = $('#datepicker').val();
		//alert(fromdate);
	}
	else
	{
		var fromdate = $('#DPC_fromdate').val();
		//alert(fromdate);
		var todate = $('#DPC_todate').val();

		var field = $('#DPC_fromdate');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
		var field = $('#DPC_todate');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}

	if ($('#datepicker').val() == '')
	{
		var field = $('#datepicker');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}
	else if ($('#datepicker1').val() == '')
	{

		var field = $('#datepicker1');
		if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	}
	var error = $('#form-error');
	var region = $("#region option:selected").val();
	var branch = $("#branch option:selected").val();
	
	var itemvalues = validateitemcheckboxes();
	if(itemvalues == false)
	{
		$('#form-error').html(errormessage("Select A Product")); return false;	
	}
	
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	var listchks_value = '';
	var listchks = $("input[name='matrixarray[]']");
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
	var field = $('#alltime:checked').val();
	if(field != 'on') var alltimecheck = 'no'; else alltimecheck = 'yes';
	
	$('#hiddentotalinvoices').val('');
	$('#hiddentotalsalevalue').val('');
	$('#hiddentotaltax').val('');
	$('#hiddentotalamount').val('');

	var passData = "switchtype=searchinvoices&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate)+ "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)+"&databasefield=" + encodeURIComponent(subselection) + "&state=" + encodeURIComponent($("#state2").val())  + "&region=" +encodeURIComponent(region)+ "&district=" +encodeURIComponent($("#district2").val()) + "&textfield=" +encodeURIComponent(textfield)  +"&dealer=" +encodeURIComponent($("#currentdealer").val()) + "&branch=" + encodeURIComponent(branch)+ "&generatedby=" +encodeURIComponent($("#generatedby").val())+ "&series=" +encodeURIComponent($("#series").val())+ "&status=" +encodeURIComponent($("#status").val())+ "&cancelledinvoice=" +encodeURIComponent(cancelledinvoice)  + "&receiptstatus=" +encodeURIComponent($("#receiptstatus").val())+ "&itemlist=" + encodeURIComponent(itemlist) + "&alltimecheck=" +encodeURIComponent(alltimecheck)+ "&seriesnew=" +encodeURIComponent($("#seriesnew").val())+ "&dummy=" + Math.floor(Math.random()*1000782200000);

	$('#tabgroupgridc6link').html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				$('#tabgroupgridc6link').html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb6').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgrid').html($('#tabgroupgridc6_1').html());
					$('#tabgroupgridc6_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc6link').html(ajaxresponse[3]);
					$('#totalinvoices').val(ajaxresponse[4]);
					$('#totalsalevalue').val(ajaxresponse[5]);
					$('#totaltax').val(ajaxresponse[6]);
					$('#totalamount').val(ajaxresponse[7]);
					
					$('#hiddentotalinvoices').val(ajaxresponse[4]);
					$('#hiddentotalsalevalue').val(ajaxresponse[5]);
					$('#hiddentotaltax').val(ajaxresponse[6]);
					$('#hiddentotalamount').val(ajaxresponse[7]);
					$('#totalsalevalue').attr('title',ajaxresponse[8]);
					$('#totaltax').attr('title',ajaxresponse[9]);
					$('#totalamount').attr('title',ajaxresponse[10]);
					$('#productsummarydiv').html(ajaxresponse[11]);
					$('#itemssummarydiv').html(ajaxresponse[12]);
					$('#itemsothersdiv').html(ajaxresponse[13]);
				/*	$('#totalproductamount').val(ajaxresponse[11]);
					$('#totalserviceamountamount').val(ajaxresponse[12]);
					$('#totalproductamount').attr('title',ajaxresponse[13]);
					$('#totalserviceamountamount').attr('title',ajaxresponse[14]);*/
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
		$('#submitform').attr("action", "../ajax/matrixinvoiceregistertoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}


//Function to view the bill in pdf format----------------------------------------------------------------
function viewinvoice(slno)
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
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}


function invoicelistBKG(type,startlimit)
{
	
	var error = $('#form-error');
	$("#category").val(type);
	var passData = "switchtype=getregionwiseinvoicelistBKG&type="+encodeURIComponent(type)+"&startlimit="+ encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*1000782200000); 
	var queryString = "../ajax/matrixinvoiceregister.php";
	error.html(getprocessingimage());
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
				var response = ajaxresponse.split('^'); //alert(response)
				if(type == 'BKG')
				{
					if(response[0] == '1')
					{
						gridtab6(2,'tabgroupgrid','&nbsp; &nbsp;BKG'); 
						$('#tabgroupgridwb2').html("Total Count :  " + response[2]);
						$('#tabgroupgridc2_1').html(response[1]);
						$('#tabgroupgridc2link').html(response[3]);
						$('#totalinvoices').val(response[4]);
						$('#totalsalevalue').val(response[5]);
						$('#totaltax').val(response[6]);
						$('#totalamount').val(response[7]);
						$('#totalsalevalue').attr('title',response[8]);
						$('#totaltax').attr('title',response[9]);
						$('#totalamount').attr('title',response[10]);
						$('#productsummarydiv').html(response[11]);
						$('#itemssummarydiv').html(response[12]);
						$('#itemsothersdiv').html(response[13]);
						/*$('#totalproductamount').val(response[11]);
						$('#totalserviceamountamount').val(response[12]);
						$('#totalproductamount').attr('title',response[13]);
						$('#totalserviceamountamount').attr('title',response[14]);*/
					}
					else
					{
						$('#tabgroupgridc2_1').html("No datas found to be displayed.");
					}
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}


function getmoreofinvoicelistBKG(startlimit,slnocount,showtype)
{
	var error = $('#form-error');
	var passData = "switchtype=getregionwiseinvoicelistBKG&type="+encodeURIComponent($("#category").val())+"&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000); 

	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				error.html('');
				$('#tabgroupgridc2link').html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb2').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgridBKG').html($('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#searchresultgridBKG').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc2link').html(ajaxresponse[3]);
					$('#totalinvoices').val(ajaxresponse[4]);
					$('#totalsalevalue').val(ajaxresponse[5]);
					$('#totaltax').val(ajaxresponse[6]);
					$('#totalamount').val(ajaxresponse[7]);
					$('#totalsalevalue').attr('title',ajaxresponse[8]);
					$('#totaltax').attr('title',ajaxresponse[9]);
					$('#totalamount').attr('title',ajaxresponse[10]);
					$('#productsummarydiv').html(ajaxresponse[11]);
					$('#itemssummarydiv').html(ajaxresponse[12]);
					$('#itemsothersdiv').html(response[13]);
					/*$('#totalproductamount').val(response[11]);
					$('#totalserviceamountamount').val(response[12]);
					$('#totalproductamount').attr('title',response[13]);
					$('#totalserviceamountamount').attr('title',response[14]);*/
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

function invoicelistBKM(type,startlimit)
{
	var error = $('#form-error');
	$("#category").val(type);
	var passData = "switchtype=getregionwiseinvoicelistBKM&type="+encodeURIComponent(type)+"&startlimit="+ encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*1000782200000); 
	var queryString = "../ajax/matrixinvoiceregister.php";
	error.html(getprocessingimage());
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
				error.html('') ;
				var response = ajaxresponse.split('^'); 
				if(response[0] == '1')
				{
					gridtab6(3,'tabgroupgrid','&nbsp; &nbsp;BKM'); 
					$('#tabgroupgridwb3').html("Total Count :  " + response[2]);
					$('#tabgroupgridc3_1').html(response[1]);
					$('#tabgroupgridc3link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totalsalevalue').val(response[5]);
					$('#totaltax').val(response[6]);
					$('#totalamount').val(response[7]);
					$('#totalsalevalue').attr('title',response[8]);
					$('#totaltax').attr('title',response[9]);
					$('#totalamount').attr('title',response[10]);
					$('#productsummarydiv').html(response[11]);
					$('#itemssummarydiv').html(response[12]);
					$('#itemsothersdiv').html(response[13]);
					/*$('#totalproductamount').val(response[11]);
					$('#totalserviceamountamount').val(response[12]);
					$('#totalproductamount').attr('title',response[13]);
					$('#totalserviceamountamount').attr('title',response[14]);*/
				}
				else
				{
					$('#tabgroupgridc3_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
	
}


function getmoreofinvoicelistBKM(startlimit,slnocount,showtype)
{
	var error = $('#form-error');
	var passData = "switchtype=getregionwiseinvoicelistBKM&type="+encodeURIComponent($("#category").val())+"&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000); 

	$('#tabgroupgridc3link').html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				error.html('');
				$('#tabgroupgridc3link').html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb3').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgridBKM').html($('#tabgroupgridc3_1').html());
					$('#tabgroupgridc3_1').html($('#searchresultgridBKM').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc3link').html(ajaxresponse[3]);
					$('#totalinvoices').val(ajaxresponse[4]);
					$('#totalsalevalue').val(ajaxresponse[5]);
					$('#totaltax').val(ajaxresponse[6]);
					$('#totalamount').val(ajaxresponse[7]);
					$('#totalsalevalue').attr('title',ajaxresponse[8]);
					$('#totaltax').attr('title',ajaxresponse[9]);
					$('#totalamount').attr('title',ajaxresponse[10]);
					$('#productsummarydiv').html(ajaxresponse[11]);
					$('#itemssummarydiv').html(ajaxresponse[12]);
					$('#itemsothersdiv').html(response[13]);
				/*	$('#totalproductamount').val(ajaxresponse[11]);
					$('#totalserviceamountamount').val(ajaxresponse[12]);
					$('#totalproductamount').attr('title',ajaxresponse[13]);
					$('#totalserviceamountamount').attr('title',ajaxresponse[14]);*/
				}
				else
				{
					$('#tabgroupgridc3_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function invoicelistCSD(type,startlimit)
{
	var error = $('#form-error');
	$("#category").val(type);
	var passData = "switchtype=getregionwiseinvoicelistCSD&type="+encodeURIComponent(type)+"&startlimit="+ encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*1000782200000); 
	var queryString = "../ajax/matrixinvoiceregister.php";
	error.html(getprocessingimage());
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
				error.html('') ;
				var response = ajaxresponse.split('^');  //alert(response[11])
				if(response[0] == '1')
				{
					gridtab6(4,'tabgroupgrid','&nbsp; &nbsp;CSD'); 
					$('#tabgroupgridwb4').html("Total Count :  " + response[2]);
					$('#tabgroupgridc4_1').html(response[1]);
					$('#tabgroupgridc4link').html(response[3]);
					$('#totalinvoices').val(response[4]);
					$('#totalsalevalue').val(response[5]);
					$('#totaltax').val(response[6]);
					$('#totalamount').val(response[7]);
					$('#totalsalevalue').attr('title',response[8]);
					$('#totaltax').attr('title',response[9]);
					$('#totalamount').attr('title',response[10]);
					$('#productsummarydiv').html(response[11]);
					$('#itemssummarydiv').html(response[12]);
					$('#itemsothersdiv').html(response[13]);
				/*	$('#totalproductamount').val(response[11]);
					$('#totalserviceamountamount').val(response[12]);
					$('#totalproductamount').attr('title',response[13]);
					$('#totalserviceamountamount').attr('title',response[14]);*/
				}
				else
				{
					$('#tabgroupgridc4_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
	
}


function getmoreofinvoicelistCSD(startlimit,slnocount,showtype)
{
	var error = $('#form-error');
	var passData = "switchtype=getregionwiseinvoicelistCSD&type="+encodeURIComponent($("#category").val())+"&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000); 
	$('#tabgroupgridc4link').html(getprocessingimage());
	var queryString = "../ajax/matrixinvoiceregister.php";
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
				error.html('');
				$('#tabgroupgridc4link').html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb4').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgridCSD').html($('#tabgroupgridc4_1').html());
					$('#tabgroupgridc4_1').html($('#searchresultgridCSD').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]);
					$('#tabgroupgridc4link').html(ajaxresponse[3]);
					$('#totalinvoices').val(ajaxresponse[4]);
					$('#totalsalevalue').val(ajaxresponse[5]);
					$('#totaltax').val(ajaxresponse[6]);
					$('#totalamount').val(ajaxresponse[7]);
					$('#totalsalevalue').attr('title',ajaxresponse[8]);
					$('#totaltax').attr('title',ajaxresponse[9]);
					$('#totalamount').attr('title',ajaxresponse[10]);
					$('#productsummarydiv').html(ajaxresponse[11]);
					$('#itemssummarydiv').html(ajaxresponse[12]);
					$('#itemsothersdiv').html(response[13]);
				/*	$('#totalproductamount').val(ajaxresponse[11]);
					$('#totalserviceamountamount').val(ajaxresponse[12]);
					$('#totalproductamount').attr('title',ajaxresponse[13]);
					$('#totalserviceamountamount').attr('title',ajaxresponse[14]);*/
				}
				else
				{
					$('#tabgroupgridc4_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});

}

function resendinvoice(invoiceno)
{
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
			var passData = "switchtype=resendinvoice&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*10054300000);
			$('#resendprocess').show();
			$('#resendemail').hide();
			$('#resendprocess').html(getprocessingimage());	
			queryString = "../ajax/matrixinvoiceregister.php";
			ajaxcall11 = $.ajax(
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
							$('#resendprocess').hide();
							$('#resendemail').show();
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


function gridtab6(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 6;
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
	}
	else if(showtype == 'more')
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
var chks_value = $("input[name='matrixarray[]']");
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
	$('#usagetype').val('');
	$('#purchasetype').val('');
	$('#receiptstatus').val('');
	$('#selectproduct').val('ALL');
	$('#loadselection').val('default');
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
	
	var selection = regionvalue + '#' + branchvalue + '#' + statevalue + '#' + districtvalue + '#' + dealervalue + '#' + generatedbyvalue + '#' + seriesvalue + '#' + statusvalue + '#' + usagetypevalue + '#' + purchasetypevalue + '#' + receiptstatusvalue ;
	
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
	queryString = "../ajax/matrixinvoiceregister.php";
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
				$('#form-error').html('');
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
	queryString = "../ajax/matrixinvoiceregister.php";
	ajaxcall13 = $.ajax(
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
		queryString = "../ajax/matrixinvoiceregister.php";
		ajaxcall14 = $.ajax(
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
			queryString = "../ajax/matrixinvoiceregister.php";
			ajaxcall15 = $.ajax(
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
	var queryString = "../ajax/matrixinvoiceregister.php";
	$('#productdetailsgridc1_1').html(getprocessingimage());
	$('#productdetailsgridc1link').html('');
	ajaxcall16 = $.ajax(
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
	var queryString = "../ajax/matrixinvoiceregister.php";//alert(passData)
	$('#productdetailsgridc1_1').html(getprocessingimage());
	$('#productdetailsgridc1link').html('');
	
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

function togglediv()
{
	$('#productwisesummary').toggle();
}



function displayproductwisesummary(elementid,imgname)
{
	if($('#'+ elementid).is(':visible'))
	{
		$('#'+ elementid).hide();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/plus.jpg");
	}
	else
	{
		$('#'+ elementid).show();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/minus.jpg");
		
	}
}



function disablethedates()
{
	if($('#alltime').is(':checked'))
	{
		$('#DPC_fromdate').attr('disabled',true);	
		$('#DPC_todate').attr('disabled',true);
		$('#DPC_fromdate').removeClass('swifttext-mandatory');
		$('#DPC_todate').removeClass('swifttext-mandatory');
		$('#DPC_fromdate').addClass('swifttext-mandatory-register');
		$('#DPC_todate').addClass('swifttext-mandatory-register');
	}
	else
	{
		$('#DPC_fromdate').attr('disabled',false);	
		$('#DPC_todate').attr('disabled',false);
		$('#DPC_fromdate').removeClass('swifttext-mandatory-register');
		$('#DPC_todate').removeClass('swifttext-mandatory-register');
		$('#DPC_fromdate').addClass('swifttext-mandatory');
		$('#DPC_todate').addClass('swifttext-mandatory');
	}
}

function viewdownloadpath(filepath)
{
	$('#filepathinvoiceregister').val('');
	if(filepath != '')
		$('#filepathinvoiceregister').val(filepath);
		
	var form = $('#submitform');	
	$('#submitform').attr("action", "../ajax/downloadfile.php?id=invoiceregister") ;
	//$('#submitformgrid').attr( 'target', '_blank' );
	$('#submitform').submit();
}