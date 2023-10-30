var customerarray = new Array();


function formsubmit(command)
{
	//alert(document.getElementById('customerlist').value);
	var form = $('#submitform');
	var error = $('#form-error');
	var cusslno = $('#cusslno').val();
	var field = $('#productdecription');
	if(!field.val()) { error.html(errormessage("Enter the Product description. ")); field.focus(); return false; }
	var field = $('#billref');
	if(!field.val()) { error.html(errormessage("Enter the Bill References. ")); field.focus(); return false; }
	var field = $('#paymentamt');
	if(!field.val()) { error.html(errormessage("Enter the Amount. ")); field.focus(); return false; }
	if(!validateamount(field.val())) { error.html(errormessage('Enter a Valid Amount.')); field.focus(); return false; }
	if($("#paymentstatus option:selected").val())
	{
		var paymentstatus = $("#paymentstatus option:selected").val();
	}
	else
	{
		var paymentstatus = 'UNPAID';
	}
	/*var field = form.remarks;
	if(!field.value) { error.innerHTML = errormessage("Enter the Remarks. "); field.focus(); return false; }*/
	//else
	{//alert('test')
		var passData = "";
		 if(command == 'save'  )
		{
			//alert(command);
			passData =  "switchtype=save&customerreference=" + encodeURIComponent($('#cusslno').val()) + "&remarks=" + encodeURIComponent($('#remarks').val()) + "&billref=" + encodeURIComponent($('#billref').val()) +  "&paymentamt=" + encodeURIComponent($('#paymentamt').val())  + "&Paymentdesc=" + encodeURIComponent($('#productdecription').val())+ "&paymentstatus=" + encodeURIComponent(paymentstatus) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
			
		}
		
		else if(command == 'delete')
		{
			//alert(form.lastslno.value);
			passData =  "switchtype=delete&lastslno=" + $('#lastslno').val() + "&dummy=" + Math.floor(Math.random()*10000000000);
			//alert(passData);
		}
		queryString = '../ajax/custpayment.php';
		error.html(getprocessingimage());
		ajaxcall0 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(ajaxresponse)
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
						cuspaymentdatagrid('',cusslno);
						newentry();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						cuspaymentdatagrid('',cusslno);
						newentry();
					}
					else if(response[0] == '3')
					{
						error.html(errormessage(response[1]));
						newentry();
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

function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/custpayment.php";
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
				var response = ajaxresponse.split('^*^');//alert(response)
				customerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i];
				}
				getcustomerlist1();
				$('#customerselectionprocess').html('');
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
	var form = $('#filterform');
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

function newentry()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	enablesave();
	disabledelete();
	$('#paymentdate').html('Not Avaliable');
	$('#paymentstatus').html('UNPAID');
	displayentry();
	if($('#resendreq'))
		$('#resendreq').hide();
}

function gridtoform(slno)
{
	if(slno != '')
	{//alert(slno);
		var form = $('#submitform');
		var error = $('#form-error');
		error.html('');
		$('#lastslno').val(slno);
		var passData = "switchtype=gridtoform&cusinteractionslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		var queryString = "../ajax/custpayment.php";
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
						if(response[0] == '1' )
						{
							$('#displaycustomername').html(response[1]);
							$('#paymentdate').html(response[2]);
							$('#remarks').val(response[3]);
							$('#productdecription').val(response[4]);
							$('#billref').val(response[5]);
							$('#paymentamt').val(response[6]);
							$('#paymentstatus').html(response[7]);
							$('#displayentereddate').html(response[9]);
							$('#lastslno').val(slno);
							$('#createddate').html(response[10]);
							enablesave();
							enabledelete();
							if($('#resendreq'))
								$('#resendreq').show();
						}
						else if(response[0] == '2')
						{
							$('#displaycustomername').html(response[1]);
							$('#paymentdate').html(response[2]);
							$('#remarks').val(response[3]);
							$('#productdecription').val(response[4]);
							$('#billref').val(response[5]);
							$('#paymentamt').val(response[6]);
							$('#paymentstatus').html(response[7]);
							$('#displayentereddate').html(response[9]);
							$('#createddate').html(response[10]);
							$('#lastslno').val(slno);
							disablesave();
							disabledelete();
							if($('#resendreq'))
								$('#resendreq').hide();
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

function cuspaymentdatagrid(startlimit,customerslno)
{
	var passData = "switchtype=generategrid&lastslno=" + encodeURIComponent(customerslno)  + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var queryString = "../ajax/custpayment.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == 1)
				{
					//document.getElementById('productselectionprocess').innerHTML='';
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);//alert(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
				}
				else if(response[0] == 2)
				{
					$('#tabgroupgridc1link').html('');
					$('#tabgroupgridwb1').html('');
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

//Function for "show more records" link - to get registration records
function cuspaymentgrid(startlimit,slno,showtype)
{
	var passData = "switchtype=generategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&lastslno=" + encodeURIComponent($('#cusslno').val())  + "&showtype=" + encodeURIComponent(showtype)  +"&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var queryString = "../ajax/custpayment.php";
	$('#tabgroupgridc1link').html('<img src="../images/inventory-processing.gif" border= "0">');
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
				}
				else if(response[0] == '2')
				{
					$('#tabgroupgridc1').html(errormessage(response[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});	
}

/*function displaycustomername()
{
	var passData = "switchtype=displaycustomer&customerreference=" + encodeURIComponent(document.getElementById('customerlist').value) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	var ajaxcall2 = createajax();
	var queryString = "../ajax/custpayment.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split("^");
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					document.getElementById('displaycustomername').innerHTML = response[0];
					document.getElementById('cusslno').value = response[1];
				}
			}
			else
				document.getElementById('displaycustomername').innerHTML = scripterror();
		}
	}
	ajaxcall2.send(passData);
}*/

function selectfromlist()
{
	var selectbox = $("#customerlist option:selected").val();
	$('#detailsearchtext').val($("#customerlist option:selected").text());
	$('#detailsearchtext').select();
	$('#form-error').html('');
	$('#displaycustomername').html($("#customerlist option:selected").text());
	$('#cusslno').val(selectbox);
	newentry();
	//displaycustomername();
	cuspaymentdatagrid('',selectbox);
	enableformelemnts();
	enabledelete();
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
			if(pattern.test(trimdotspaces(customerarray[i]).toLowerCase()))
			{
				var splits = customerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//customerdetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
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
	selectfromlist()}


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


function resendrequestemail()
{
	var customerid = $('#lastslno').val();
	var error = $('#form-error');
	if(customerid != '')
	{
		var confirmation = confirm("Are you sure you want to send a Payment Request Email to the selected customer?");
		if(confirmation)
		{
			var passData = "switchtype=resendrequestemail&customerslno=" + encodeURIComponent($('#lastslno').val());//alert(passData)
			error.html(getprocessingimage());
			var queryString = "../ajax/custpayment.php";
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
						var ajaxresponse = ajaxresponse.split('^');
						if(ajaxresponse[0] == 1)
						{
							error.html(successmessage(ajaxresponse[1]));
						}
						else if(ajaxresponse[0] == 2)
						{
							error.html(errormessage(ajaxresponse[1]));
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
	else
	error.html(errormessage('Cannot send mail.'));
	return false;
}


function searchbycustomerid(cusid)
{
	$('#form-error').html('');
	var form = $('#submitform');
	$('#cusslno').val(cusid);
	$('#submitform')[0].reset();
	var str = cusid.length;
	if(str == '20')
	{
		var cusid = cusid.substring(15);
		
	}else if(str == '17')
	{
		var cusid = cusid.substring(12,17);
	}else if(str == '5')
	{
		var cusid = cusid;
	}
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
	var queryString = "../ajax/custpayment.php";
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
				var response = (ajaxresponse).split("^");//alert(response)
					enableformelemnts();
					if(response[1] == '')
					{
						alert('Customer Not Available.');
						$('#displaycustomername').html('');
						$('#tabgroupgridc1_1').html('');
						$('#tabgroupgridwb1').html('');
						//newentry();
					}
					else if(response[0] == '1' )
					{
						$('#displaycustomername').html(response[1]);
						$('#paymentdate').html(response[2]);
						$('#remarks').val(response[3]);
						$('#productdecription').val(response[4]);
						$('#billref').val(response[5]);
						$('#paymentamt').val(response[6]);
						$('#paymentstatus').html(response[7]);
						$('#displayentereddate').html(response[9]);
						$('#createddate').html(response[10]);
						enablesave();
						enabledelete();
						if($('#resendreq'))
								$('#resendreq').show();
					}
					else if(response[0] == '2')
					{
						$('#displaycustomername').html(response[1]);
						$('#paymentdate').html(response[2]);
						$('#remarks').val(response[3]);
						$('#productdecription').val(response[4]);
						$('#billref').val(response[5]);
						$('#paymentamt').val(response[6]);
						$('#paymentstatus').html(response[7]);
						$('#displayentereddate').html(response[9]);
						$('#createddate').html(response[10]);
						disablesave();
						disabledelete();
						if($('#resendreq'))
								$('#resendreq').hide();
					}
					else if(response[0] == '3')
					{
						$('#displaycustomername').html(response[1]);
						if($('#resendreq'))
								$('#resendreq').hide();
					}
					cuspaymentdatagrid('',cusid);
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchcustomerid').val();
		searchbycustomerid(input);
	}
}
function displayentry()
{
	var passData = "switchtype=displayentry&dummy=" + Math.floor(Math.random()*10054300000);
	var queryString = "../ajax/custpayment.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('#displayentereddate').html(response[1]);
					$('#createddate').html(response[2]);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1").html(scripterror());
		}
	});	
}