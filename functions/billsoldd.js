var dealerarray = new Array();

function refreshdealerarray() {
	var form = $("#filterform");
	var relyonexcecutive_type = $("input[name='relyonexcecutive_type']:checked").val();
	var login_type = $("input[name='login_type']:checked").val();
	passData = "type=generatedealerlist&relyonexcecutive_type=" + encodeURIComponent(relyonexcecutive_type) +
		"&login_type=" + encodeURIComponent(login_type) + "&dealerregion=" + encodeURIComponent($("#dealerregion").val());
	$("#dealerselectionprocess").html(getprocessingimage());
	queryString = '../ajax/bills.php';
	ajaxcall1 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response = ajaxresponse;
					dealerarray = new Array();
					for (var i = 0; i < response.length; i++) {
						dealerarray[i] = response[i];
					}
					getdealerlist();
					$("#dealerselectionprocess").html('');
					$('#displayfilter').hide();
					$("#totalcount").html(dealerarray.length);
				}
			},
			error: function (a, b) {
				$("#schemeselectionprocess").html(scripterror());
			}
		});
}


function getdealerlist() {
	disableformelemnts();
	$('#viewcardsdisplaydiv').hide();
	var form = $('#submitform');
	var selectbox = $('#dealerlist');
	var numberofcustomers = dealerarray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit) ? actuallimit : numberofcustomers;

	$('option', selectbox).remove();
	var options = selectbox.attr('options');

	for (var i = 0; i < limitlist; i++) {
		var splits = dealerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}

function selectfromlist() {
	var selectbox = $("#dealerlist option:selected").val();
	$('#detailsearchtext').val($("#dealerlist option:selected").text());
	$('#detailsearchtext').select();
	onselectofdealer();
	$('#dealerdisplay').html($("#dealerlist option:selected").text());
	$('#resendmail').hide();
	getscheme('displayschemecode', selectbox);
	getproduct('displayproductcode', $('#scheme').val());

}

function selectadealer(input) {
	var selectbox = $('#dealerlist');
	if (input == "") {
		getdealerlist();
	}
	else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < dealerarray.length; i++) {
			if (input.charAt(0) == "%") {
				withoutspace = input.substring(1, input.length);
				pattern = new RegExp(withoutspace.toLowerCase());
				comparestringsplit = dealerarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else {
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = dealerarray[i];
			}
			var result1 = pattern.test(trimdotspaces(dealerarray[i]).toLowerCase());
			var result2 = pattern.test(dealerarray[i].toLowerCase());
			if (result1 || result2) {
				var splits = dealerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if (addedcount == 100)
					break;
			}
		}
	}

}


function dealersearch(e) {
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if (KeyID == 38)
		scrolldealer('up');
	else if (KeyID == 40)
		scrolldealer('down');
	else {
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectadealer(input);
	}
}
function scrolldealer(type) {
	var selectbox = $('#dealerlist');
	var totalcus = $("#dealerlist option").length;
	var selectedcus = $("select#dealerlist").attr('selectedIndex');
	if (type == 'up' && selectedcus != 0)
		$("select#dealerlist").attr('selectedIndex', selectedcus - 1);
	else if (type == 'down' && selectedcus != totalcus)
		$("select#dealerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}


function productformsubmit(command) {
	var form = $("#submitform");
	var error = $("#prd-form-error");

	var field = $("#creditamountfield")
	if (field.val() <= '0') { error.html(errormessage('You dont have enough credit, to get credit Contact Accounts depart.')); field.focus(); return false; }
	if (field.val() < $("#productamount").val()) { error.html(errormessage('You dont have enough credit to purchase Product.')); field.focus(); return false; }

	var field = $("#cusbillnumber");
	if (!field.val()) { error.html(errormessage('Enter the Bill Number.')); field.focus(); return false; }

	var field = $("#productcode");
	if (!field.val()) { error.html(errormessage('Select the Product.')); field.focus(); return false; }

	var field = $("#productquantity");
	if (!field.val()) { error.html(errormessage('Enter the Billed Quantity.')); field.focus(); return false; }
	if (field.val()) { if (isNaN(field.val())) { error.html(errormessage('Billed Quantity should be only Integers.')); field.focus(); return false; } }

	var field = $("#productamount");
	if (!field.val()) { error.html(errormessage('Enter the Billed Amount.')); field.focus(); return false; }
	if (field.val()) { if (!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }

	var field = $("#usagetype");
	if (!field.val()) { error.html(errormessage('Select the Usage type.')); field.focus(); return false; }

	var field = $("#purchasetype");//alert(form.scheme.value);
	if (!field.val()) { error.html(errormessage('Select the Purchase type.')); field.focus(); return false; }
	else {
		var passData = "";
		if (command == 'save') {
			passData = "type=productsave&cusbillnumber=" + $("#cusbillnumber").val() + "&productcode=" + $("#productcode").val() + "&productquantity=" + $("#productquantity").val() + "&productamount=" + $("#productamount").val() + "&productlastslno=" + $("#productlastslno").val() + "&usagetype=" + $("#usagetype").val() + "&dealerid=" + $("#dealerlist").val() + "&purchasetype=" + $("#purchasetype").val() + "&scheme=" + $("#scheme").val() + "&billdate=" + $("#billdate").val() + "&dummy=" + Math.floor(Math.random() * 100000000);//alert(passData)
		}
		else {
			var confirmation = confirm("Are you sure you want to delete the selected product?");
			if (confirmation) {
				passData = "type=productdelete&cusbillnumber=" + $("#cusbillnumber").val() + "&dummy=" + Math.floor(Math.random() * 10000000000);
			}
			else
				return false;
		}
		queryString = '../ajax/bills.php';
		ajaxcall2 = $.ajax(
			{
				type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
				success: function (ajaxresponse, status) {
					if (ajaxresponse == 'Thinking to redirect') {
						window.location = "../logout.php";
						return false;
					}
					else {
						var response = ajaxresponse['errormessage'].split('^');
						if (response[0] == '1') {
							if ($("#cusbillnumber").val() == 'New') {
								$("#cusbillnumber").val(response[2]);
							}
							error.html(successmessage(response[1]));
							productdatagrid(); getnetamount(); productnewentry();
						}
						else if (response[0] == '2') {
							error.html(successmessage(response[1]));
							productdatagrid(); getnetamount();
							productnewentry();
						}
					}
				},
				error: function (a, b) {
					error.html(scripterror());
				}
			});
	}
}

function productdatagrid() {
	var form = $("#submitform");
	var passData = "type=generateproductgrid&cusbillnumber=" + $("#cusbillnumber").val() + "&dummy=" + Math.floor(Math.random() * 10054300000);//alert(passData)
	$("#prd-form-error").html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall3 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false,
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					$("#prd-form-error").html('');
					var splitresponse = ajaxresponse.split('^');
					if (splitresponse[0] == '1') {
						$("#displayproductresult").html(splitresponse[1]);
					}
					else {
						$("#prd-form-error").html(errormessage('No data to display.'));
					}
				}
			},
			error: function (a, b) {
				$("#prd-form-error").html(scripterror());
			}
		});
}

function viewpurchasedatagrid(startlimit) {
	var form = $("#submitform");
	var passData = "type=generategrid&lastslno=" + encodeURIComponent($("#dealerlist").val()) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random() * 10054300000);//alert(passData)
	$("#form-error").html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall4 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false,
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					$("#form-error").html('');
					var response = ajaxresponse.split('^');
					if (response[0] == '1') {
						$("#tabgroupgridc2_1").html(response[1]);
						$("#getmorerecordslink").html(response[2]);
					}
					else {
						$("#form-error").html(errormessage('No datas found to be diaplayed.'));
					}
				}
			},
			error: function (a, b) {
				$("#form-error").html(scripterror());
			}
		});

}

//function to display 'Show more records' or 'Show all records' 
function getmoreviewpurchasedatagrid(startlimit, slno, showtype) {
	var form = $("#submitform");
	var passData = "type=generategrid&lastslno=" + $("#dealerlist").val() + "&startlimit=" + encodeURIComponent(startlimit) + "&slno=" + encodeURIComponent(slno) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random() * 1000782200000);//alert(passData);
	$("#getmorerecordslink").html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall5 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false,
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					$("#form-error").html('');
					var response = ajaxresponse.split('^');
					if (response[0] == '1') {
						$("#resultgrid").html($("#tabgroupgridc2_1").html());
						$("#tabgroupgridc2_1").html($("#resultgrid").html().replace(/\<\/table\>/gi, '') + response[1]);
						$("#getmorerecordslink").html(response[2]);
					}
					else {
						$("#form-error").html(errormessage('No datas found to be diaplayed.'));
					}
				}
			},
			error: function (a, b) {
				$("#form-error").html(scripterror());
			}
		});

}



function productgridtoform(slno) {
	if (slno != '') {
		var form = $("#submitform");
		var error = $("#prd-form-error");
		error.html('');
		var passData = "type=productgridtoform&productlastslno=" + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random() * 100032680100);
		error.html(getprocessingimage());
		var queryString = "../ajax/bills.php";
		ajaxcall6 = $.ajax(
			{
				type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
				success: function (ajaxresponse, status) {
					if (ajaxresponse == 'Thinking to redirect') {
						window.location = "../logout.php";
						return false;
					}
					else {
						error.html('');
						var response1 = ajaxresponse;
						if (response1['errorcode'] == '1') {
							$("#productlastslno").val(response1['slno']);
							$("#billlastslno").val(response1['cusbillnumber']);
							$("#cusbillnumber").val(response1['cusbillnumber']);
							$("#productcode").val(response1['productcode']);
							$("#productquantity").val(response1['productquantity']);
							$("#productamount").val(response1['productamount']);
							$("#usagetype").val(response1['usagetype']);
							$("#purchasetype").val(response1['purchasetype']);
							$("#scheme").val(response1['scheme']);
						}
						else {
							$("#form-error").html(errormessage('No datas found to be diaplayed.'));
						}
					}
				},
				error: function (a, b) {
					error.html(scripterror());
				}
			});
	}
}

function gridtoform(slno, billslno) {

	if (slno != '' && billslno != '') {
		var form = $("#submitform");
		var error = $("#form-error");
		error.html('');
		var passData = "type=gridtoform&lastslno=" + encodeURIComponent(slno) + "&billlastslno=" + encodeURIComponent(billslno) + "&dummy=" + Math.floor(Math.random() * 100032680100);
		error.html(getprocessingimage());
		var queryString = "../ajax/bills.php";
		ajaxcall7 = $.ajax(
			{
				type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
				success: function (ajaxresponse, status) {
					if (ajaxresponse == 'Thinking to redirect') {
						window.location = "../logout.php";
						return false;
					}
					else {
						error.html('');
						var response = ajaxresponse;
						if (response['errorcode'] == '1') {
							error.html('');
							$("#lastslno").val(response['dealerid']);
							$("#billlastslno").val(response['slno']);
							$("#cusbillnumber").val(response['slno']);
							$("#dealerdisplay").html(response['businessname']);
							$("#billdate").val(response['billdate']);
							$("#remarks").val(response['remarks']);
							$("#total").val(response['total']);
							$("#taxamount").val(response['taxamount']);
							$("#netamount").val(response['netamount']);
							$("#displayproductresult").html(response['grid']);
							$("#userid").html(response['fullname']);
							disablebuttonsonview();
							disableviewcards();
							if (response['billstatus'] == 'successful') {
								$('#resendmail').show();;
							}
							else {
								$('#resendmail').hide();
							}
						}
					}
				},
				error: function (a, b) {
					error.html(scripterror());
				}
			});
	}
}

/*function gettotalamount()
{
	var form = document.submitform;
	var passData = "type=gettotalamount&cusbillnumber=" + form.cusbillnumber.value + "&lastslno=" + form.lastslno.value + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall3 = createajax();
	document.getElementById('prd-form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/bills.php";
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			document.getElementById('prd-form-error').innerHTML = '';	
			var response = ajaxcall3.responseText;
			form.total.value = response;
		}
	}
	ajaxcall3.send(passData);
}*/

/*function getnetamount()
{
	var form = document.submitform;
	var passData = "type=getnetamount&total=" + form.total.value + "&taxamount=" + form.taxamount.value + "&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall3 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/bills.php";
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			document.getElementById('form-error').innerHTML = '';	
			var response = ajaxcall3.responseText;
			form.netamount.value = response;
		}
	}
	ajaxcall3.send(passData);
}*/

function getamount() {
	var form = $("#submitform");
	{
		var passData = "type=getamount&usagetype=" + encodeURIComponent($("#usagetype").val()) + "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&purchasetype=" + encodeURIComponent($("#purchasetype").val()) + "&productcode=" + encodeURIComponent($("#productcode").val()) + "&productquantity=" + encodeURIComponent($("#productquantity").val()) + "&scheme=" + encodeURIComponent($("#scheme").val()) + "&dummy=" + Math.floor(Math.random() * 10054300000);
		if ($("#usagetype").val() != '' && $("#purchasetype").val() != '' && $("#scheme").val() != '' && $("#productcode").val() != '' && $("#productquantity").val() != '') {
			$("#prd-form-error").html(getprocessingimage());
			queryString = "../ajax/bills.php";
			ajaxcall8 = $.ajax(
				{
					type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
					success: function (ajaxresponse, status) {
						if (ajaxresponse == 'Thinking to redirect') {
							window.location = "../logout.php";
							return false;
						}
						else {
							$("#prd-form-error").html('');
							var response = ajaxresponse['errormessage'].split('^');
							if (response == 'Thinking to redirect') {
								window.location = "../logout.php";
								return false;
							}
							else
								if (response[0] == 2) {
									$("#prd-form-error").html(errormessage(response[1]));
									$("#productamount").val('');
									return false;
								}
								else {
									$("#productamount").val(response[1]);
								}
						}
					},
					error: function (a, b) {
						$("#prd-form-error").html(scripterror());
					}
				});
		}
	}
}

function formsubmit(command) {
	disablebuttonsonview();
	disableviewcards();
	var form = $("#submitform");
	var error = $("#form-error");
	$("#save-form-error").html('');
	var field = $("#cusbillnumber");
	if (!field.val()) { error.html(errormessage('Enter the Bill Number.')); field.focus(); return false; }
	var field = $("#total");
	if (!field.val()) {
		var field = $("#productcode");
		if (!field.val()) { error.html(errormessage('Select the Product.')); field.focus(); return false; }
		var field = $("#productquantity");
		if (!field.value) { error.html(errormessage('Enter the Billed Quantity.')); field.focus(); return false; }
		var field = $("#productamount");
		if (!field.val()) { error.html(errormessage('Enter the Billed Amount.')); field.focus(); return false; }
		if (field.val()) { if (!validateamount(field.val())) { error.html(errormessage('Amount is not Valid.')); field.focus(); return false; } }
	}
	var passData = "";
	if (command == 'print') {
		form.action = "../reports/printattchedcards.php";
		form.target = "_blank";
		form.submit();
	}
	else {
		var displayproductresult = $("#displayproductresult").html();//alert(displayproductresult);
		if (displayproductresult == '') {
			error.html(errormessage('Save the Product first.'));
			return false;
		}
		else {
			if (command == 'save') {
				passData = "type=save&cusbillnumber=" + encodeURIComponent($("#cusbillnumber").val()) +
					"&dealerid=" + encodeURIComponent($("#lastslno").val()) +
					"&billdate=" + encodeURIComponent($("#billdate").val()) +
					"&remarks=" + encodeURIComponent($("#remarks").val()) +
					"&total=" + encodeURIComponent($("#total").val()) +
					"&taxamount=" + encodeURIComponent($("#taxamount").val()) +
					"&netamount=" + encodeURIComponent($("#netamount").val()) +
					"&totalcredit=" + encodeURIComponent($("#creditamountdisplay").html()) +
					"&billlastslno=" + encodeURIComponent($("#billlastslno").val()) +
					"&dummy=" + Math.floor(Math.random() * 10045606700000);
			}
			else {
				var confirmation = confirm("Are you sure you want to delete the selected product?");
				if (confirmation) {
					passData = "type=delete&billlastslno=" + encodeURIComponent($("#billlastslno").val()) + "&dummy=" + Math.floor(Math.random() * 100006454000000);
				}
				else
					return false;
			}
			queryString = '../ajax/bills.php';
			error.html(getprocessingimage());
			ajaxcall1 = $.ajax(
				{
					type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
					success: function (ajaxresponse, status) {
						if (ajaxresponse == 'Thinking to redirect') {
							window.location = "../logout.php";
							return false;
						}
						else {
							var response = ajaxresponse['errormessage'].split('^');
							if (response[0] == '1') {
								$("#save-form-error").html(successmessage(response[1]));
								error.html(successmessage(response[1]));
								viewpurchasedatagrid(''); creditamount();
							}
							else if (response[0] == '2') {
								error.html(successmessage(response[1])); newentry(); datagrid(); creditamount();
							}
							else if (response[0] == '3') {
								error.html(errormessage(response[1]));
								//	document.getElementById('viewcards').disabled = true;
								$("#viewcards").attr("disabled", "true");
								$("#viewcards").addClass('swiftchoicebuttondisabledbig');

							}
							else {
								error.html(errormessage('Unable to Connect...' + ajaxresponse));
							}
						}
					},
					error: function (a, b) {
						error.html(scripterror());
					}
				});
		}
	}
}


function productnewentry() {
	var form = $("#submitform");
	//form.reset();
	$("#productcode").val('');
	$("#productquantity").val('');
	$("#productamount").val('');
	$("#productlastslno").val('');
	$("#purchasetype").val('');
	$("#usagetype").val('');
	$("#scheme").val('');
	$("#form-error").html('');
	$("#prd-form-error").html('');
	$('#viewcardsdisplaydiv').hide();
}

function newentry() {
	var form = $("#submitform");
	$("#submitform")[0].reset();
	$("#prd-form-error").html('');
	$("#displayproductresult").html('');
	$("#billlastslno").val('');
	$("#taxratedisplay").html('Tax Amount: ');
	$('#viewcardsdisplaydiv').hide();
	$("#save-form-error").html('');
	$("#form-error").html('');
	enablebuttonsonview();
	enableviewcard();
	$('#resendmail').hide();
}

/*function selectbilltype(type)
{
	var billinnerhtml = document.getElementById('cusbillnumberid');
	if(type == 'new')
	{
		billinnerhtml.innerHTML = '<input name="cusbillnumber" type="text" class="swifttext-mandatory" id="cusbillnumber" size="30" autocomplete="off" /><div id="loadcusbillmberlist" class="loadlist"></div>';
	}
	else
	{
		billinnerhtml.innerHTML = '<input name="cusbillnumber" type="hidden" id="cusbillnumber" /><input name="searchcusbillnumber" type="text" class="swifttext-mandatory" id="searchcusbillnumber" size="30" onkeyup="cusbillnumberlookout(event); " onblur = "hideResult( document.getElementById(\'loadcusbillnumberlist\'));" autocomplete="off"/><div id="loadcusbillnumberlist" class="loadlist"></div>';
	}
}*/

function searchbills(billno) {
	$("#form-error").html('');
	$("#prd-form-error").html('');
	var form = $("#submitform");
	var passData = "type=searchbybills&cusbillnumber=" + encodeURIComponent(billno) + "&dummy=" + Math.floor(Math.random() * 100032680100);
	var queryString = "../ajax/bills.php";
	ajaxcall10 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response = (ajaxresponse);
					$("#lastslno").val(response[1]);
					if (response['errorcode'] == 'Not Available') {
						disableformelemnts();
					}
					else {
						if (response['errorcode'] == '1') {
							$("#cusbillnumber").val(response['slno']);
							$("#billlastslno").val(response['slno']);
							$("#dealerlist").val(response['dealerid']);
							$("#billdate").val(response['billdate']);
							$("#remarks").val(response['remarks']);
							$("#total").val(response['total']);
							$("#taxamount").val(response['taxamount']);
							$("#netamount").val(response['netamount']);
							$("#displayproductresult").html(response['grid']);
							$("#dealerdisplay").html(response['businessname']);
							$("#scheme").val(response['scheme'])
							$("#userid").html(response['fullname']);
							enableformelemnts();
							viewpurchasedatagrid('');
							gridtab2('1', 'tabgroupgrid', '&nbsp; &nbsp;Bill Entry');
							getscheme('displayschemecode', response['dealerid']);
							getproduct('displayproductcode', response['billstatus']);
							creditamount();
							if (response['billstatus'] == 'successful') {
								$('#resendmail').show();
							}
							else {
								$('#resendmail').hide();
							}
						}
						else if (response[0] == '2') {
							$("#prd-form-error").html(errormessage('No datas found to be displayed.'));
						}
					}
				}
			},
			error: function (a, b) {
				$("#prd-form-error").html(scripterror());
			}
		});
}

function searchbybillsevent(e) {
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if (KeyID == 13) {
		var input = $('#searchbill').val();
		searchbills(input);
	}
}

function disableformelemnts() {
	var count = document.submitform.elements.length;
	for (i = 0; i < count; i++) {
		var element = document.submitform.elements[i];
		element.disabled = true;
	}
}

function enableformelemnts() {
	var count = document.submitform.elements.length;
	for (i = 0; i < count; i++) {
		var element = document.submitform.elements[i];
		element.disabled = false;
	}
}

function generatebillnumber() {
	var form = $("#submitform");
	var passData = "type=generatebills&dummy=" + Math.floor(Math.random() * 10054300000);
	$("#form-error").html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall11 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response1 = ajaxresponse['errormessage'].split('^');
					if (response1[0] == '1') {
						$("#cusbillnumber").val(response1[1]);
					}
					else {
						$("#cusbillnumber").val("Not available.");
					}
				}
			},
			error: function (a, b) {
				$("#form-error").html(scripterror());
			}
		});
}

function onselectofdealer() {
	var form = $("#submitform");
	$("#submitform")[0].reset();
	//document.getElementById('dealerdisplay').innerHTML = document.getElementById('dealerid').value;
	$("#displayproductresult").html('');
	$("#form-error").html('');
	$("#prd-form-error").html('');
	//$("#save-form-error").html('');
	$("#billlastslno").val('');
	$("#productlastslno").val('');
	$("#userid").val('Not Available');
	$('#viewcardsdisplaydiv').hide();
	creditamount();
	enableformelemnts();
	//generatebillnumber();
	$("#lastslno").val($("#dealerlist").val());
	viewpurchasedatagrid('');
	enablebuttonsonview();
	enableviewcard();
}

function viewattachedcards(command) {
	$("#save-form-error").html('');
	var form = $("#submitform");
	var error = $("#form-error");
	if ($("#cusbillnumber").val() == 'New') {
		error.html(errormessage('Select the Bill from the View Bills Tab or Search the Bill Number.')); $("#searchbill").focus(); return false;
	}
	var passData = "type=viewcards&cusbillnumber=" + encodeURIComponent($("#cusbillnumber").val()) + "&dummy=" + Math.floor(Math.random() * 10054300000);
	error.html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall12 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					error.html('');
					var response = ajaxresponse.split('^');//alert(response)
					if (response[0] == '1') {
						error.html('');
						$('#viewcardsdisplaydiv').show();
						$("#displayviewcards").html(response[1]);
						disableviewcards();
						creditamount();
					}
					else {
						error.html(errormessage('Unable to Connect...' + ajaxresponse));
					}
				}
			},
			error: function (a, b) {
				error.html(scripterror());
			}
		});
}

/*function calculatetaxamount()
{
	var form = document.submitform;
	var passData = "type=gettaxamount&total=" + form.total.value + "&dealerid=" + document.getElementById('dealerid').value + "&dummy=" + Math.floor(Math.random()*10054300566000);
	var ajaxcall3 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();	
	queryString = "../ajax/bills.php";
	ajaxcall3.open("POST", queryString, true);
	ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall3.onreadystatechange = function()
	{
		if(ajaxcall3.readyState == 4)
		{
			document.getElementById('form-error').innerHTML = '';	
			var ajaxresponse = ajaxcall3.responseText;
			var response = ajaxresponse.split("^");
			form.taxamount.value = response[1];
			document.getElementById('taxratedisplay').innerHTML = response[0];
		}
	}
	ajaxcall3.send(passData);

}*/

function getdealerbname() {
	var form = $("#submitform");
	var passData = "type=getdealername&dealerid=" + $('#dealerlist').val() + "&dummy=" + Math.floor(Math.random() * 10054300000);//alert(passData)
	$('#form-error').html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall13 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false,
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response = ajaxresponse.split('^');
					if (respones[0] == '1') {
						$('#form-error').html('');
						$('#dealerdisplay').html(response[1]);
						//	document.getElementById('taxratehidden').value = response[1];
					}
					else {
						$('#form-error').html(errormessage('Unable to connect.'));
					}
				}
			},
			error: function (a, b) {
				$("#form-error").html(scripterror());
			}
		});
}


function getnetamount() {
	var form = $("#submitform");
	var error = $("#form-error");
	passData = "type=calculatenetamount&cusbillnumber=" + encodeURIComponent($("#cusbillnumber").val()) + "&dealerid=" + encodeURIComponent($("#lastslno").val());
	var queryString = "../ajax/bills.php";
	ajaxcall14 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response = ajaxresponse['errormessage'].split('^');
					if (response[0] == '1') {
						$("#total").val(response[1]);
						$("#taxratedisplay").html(response[2]);
						$("#taxamount").val(response[3]);
						$("#netamount").val(response[4]);
					}
					else {
						error.html(errormessage('Unable to connect.'));
					}
				}
			},
			error: function (a, b) {
				error.html(scripterror());
			}
		});
}

function creditamount() {
	var form = $("#submitform");
	var passData = "type=getcreditamount&dealerid=" + $("#dealerlist").val() + "&dummy=" + Math.floor(Math.random() * 10054300000);
	$("#form-error").html(getprocessingimage());
	queryString = "../ajax/bills.php";
	ajaxcall15 = $.ajax(
		{
			type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
			success: function (ajaxresponse, status) {
				if (ajaxresponse == 'Thinking to redirect') {
					window.location = "../logout.php";
					return false;
				}
				else {
					var response = ajaxresponse['errormessage'].split('^');
					if (response[0] == '1') {
						$("#form-error").html('');
						$("#creditamountdisplay").html(response[1]);
						$("#creditamountfield").val(response[1]);
					}
					else {
						$("#form-error").html(errormessage('Unable to connect.'));
					}
				}
			},
			error: function (a, b) {
				$("#form-error").html(scripterror());
			}
		});
}

function disableviewcards() {
	var form = $('#submitform');
	$("#productnew").attr("disabled", true);
	$("#productsave").attr("disabled", true);
	$("#productdelete").attr("disabled", true);

}
function enableviewcard() {
	//alert('enable');
	var form = $('#submitform');
	$("#productnew").attr("disabled", false);
	$("#productsave").attr("disabled", false);
	$("#productdelete").attr("disabled", false);

}


/*function disablebuttonsonview()
{
	$("#productnew").attr("disabled", "true"); 
	$("#productsave").attr("disabled", "true"); 
	$("#productdelete").attr("disabled", "true"); 
	$("#save").attr("disabled", "true"); 
	$("#delete").attr("disabled", "true"); 
	$("#viewcards").attr("disabled", "false"); 
	$("#save").addClass('swiftchoicebuttondisabled');
	$("#productdelete").addClass('swiftchoicebuttondisabled');
	$("#productsave").addClass('swiftchoicebuttondisabled');
	$("#productnew").addClass('swiftchoicebuttondisabled');
	$("#delete").addClass('swiftchoicebuttondisabled');
	$("#viewcards").addClass('swiftchoicebuttonbig');
}

function enablebuttonsonview()
{
	$("#productnew").attr("disabled", "false"); 
	$("#productsave").attr("disabled", "false"); 
	$("#productdelete").attr("disabled", "false"); 
	$("#save").attr("disabled", "false"); 
	$("#delete").attr("disabled", "false");
	$("#viewcards").attr("disabled", "false");
	$("#save").removeClass('swiftchoicebuttondisabled');alert('2');
	$("#productdelete").removeClass('swiftchoicebuttondisabled');
	$("#productsave").removeClass('swiftchoicebuttondisabled');
	$("#productnew").removeClass('swiftchoicebuttondisabled');
	$("#delete").removeClass('swiftchoicebuttondisabled');
	$("#viewcards").removeClass('swiftchoicebuttonbig');

}*/


function disablebuttonsonview() {
	$('#productnew').disabled = true;
	$('#productsave').disabled = true;
	$('#productdelete').disabled = true;
	$('#save').disabled = true;
	$('#delete').disabled = true;
	$('#viewcards').disabled = false;

	$('#save').removeClass('swiftchoicebutton');
	$('#productdelete').removeClass('swiftchoicebutton');
	$('#productsave').removeClass('swiftchoicebutton');
	$('#productnew').removeClass('swiftchoicebutton');
	$('#delete').removeClass('swiftchoicebutton');
	$('#viewcards').removeClass('swiftchoicebuttondisabledbig');


	$('#save').addClass('swiftchoicebuttondisabled');
	$('#productdelete').addClass('swiftchoicebuttondisabled');
	$('#productsave').addClass('swiftchoicebuttondisabled');
	$('#productnew').addClass('swiftchoicebuttondisabled');
	$('#delete').addClass('swiftchoicebuttondisabled');
	$('#viewcards').addClass('swiftchoicebuttonbig');
}

function enablebuttonsonview() {
	$('#productnew').disabled = false;
	$('#productsave').disabled = false;
	$('#productdelete').disabled = false;
	$('#save').disabled = false;
	$('#delete').disabled = false;
	$('#viewcards').disabled = false;

	$('#save').removeClass('swiftchoicebuttondisabled');
	$('#productdelete').removeClass('swiftchoicebuttondisabled');
	$('#productsave').removeClass('swiftchoicebuttondisabled');
	$('#productnew').removeClass('swiftchoicebuttondisabled');
	$('#delete').removeClass('swiftchoicebuttondisabled');
	$('#viewcards').removeClass('swiftchoicebuttonbig');


	$('#save').addClass('swiftchoicebutton');
	$('#productdelete').addClass('swiftchoicebutton');
	$('#productsave').addClass('swiftchoicebutton');
	$('#productnew').addClass('swiftchoicebutton');
	$('#delete').addClass('swiftchoicebutton');
	$('#viewcards').addClass('swiftchoicebuttondisabledbig');
}
function resendpurchaseemail() {
	var form = $("#submitform");
	var billno = $("#billlastslno").val();
	var error = $("#form-error");
	if (dealerid != '') {
		var confirmation = confirm("Are you sure you want to resend the Purchase Email for the selected products?");
		if (confirmation) {
			var passData = "type=resendpurchaseemail&billno=" + encodeURIComponent(billno);//alert(passData)
			error.html(getprocessingimage());
			var queryString = "../ajax/bills.php";
			ajaxcall16 = $.ajax(
				{
					type: "POST", url: queryString, data: passData, cache: false, dataType: "json",
					success: function (ajaxresponse, status) {
						if (ajaxresponse == 'Thinking to redirect') {
							window.location = "../logout.php";
							return false;
						}
						else {
							var response1 = ajaxresponse['errormessage'].split('^');
							if (response1[0] == '1') {
								error.html(successmessage(response1[1]));
							}
						}
					},
					error: function (a, b) {
						error.html(scripterror());
					}
				});
		}
	}
	else
		error.html(errormessage('Cannot resend mail to the selected products'));
	return false;
}
