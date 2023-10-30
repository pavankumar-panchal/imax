var customerarray = new Array();
var invoicearray = new Array();
var rowcountvalue = 0;

//Get the customer list
function refreshcustomerarray()
{
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall11 = createajax();
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/invoicing.php";
	ajaxcall11.open("POST", queryString, true);
	ajaxcall11.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall11.onreadystatechange = function()
	{
		if(ajaxcall11.readyState == 4)
		{
			if(ajaxcall11.status == 200)
			{
				var response = ajaxcall11.responseText.split('^*^');
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					$('#customerselectionprocess').html('');
					for( var i=0; i<response.length; i++)
					{
						customerarray[i] = response[i];
					}
					getcustomerlist1();
					document.getElementById('totalcount').innerHTML = customerarray.length;
				}
			}
			else
				document.getElementById('customerselectionprocess').innerHTML = scripterror();
		}
	}
	ajaxcall11.send(passData);
}

function getcustomerlist1()
{	
	disableformelemnts_invoicing();
	var form = document.submitform;
	var selectbox = document.getElementById('customerlist');
	var numberofcustomers = customerarray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	selectbox.options.length = 0;

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	
}


function displayalcustomer()
{	
	var form = document.submitform;
	var selectbox = document.getElementById('customerlist');
	document.getElementById('customerselectionprocess').innerHTML = successsearchmessage('All Customer...');
	var numberofcustomers = customerarray.length;
	document.filterform.detailsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;

	selectbox.options.length = 0;

	for( var i=0; i<limitlist; i++)
	{
		var splits = customerarray[i].split("^");
		selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
	}
	document.getElementById('totalcount').innerHTML = customerarray.length;
}


//Customer details to form
function customerdetailstoform(cusid)
{
	
	if(cusid != '')
	{
		$('#customerselectionprocess').html('');
		var form = $('#submitform');
		$("#submitform" )[0].reset();
		var passData = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
		ajaxcall3 = createajax();
		$('#customerdetailshide').show();
		$('#customerdetailsshow').hide();
		$('#customerdetailshide').html(getprocessingimage());
		var queryString = "../ajax/invoicing.php";
		ajaxcall3.open("POST", queryString, true);
		ajaxcall3.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall3.onreadystatechange = function()
		{
			if(ajaxcall3.readyState == 4)
			{
				if(ajaxcall3.status == 200)
				{
					$('#form-error').html('');
					$('#searchcustomerid').val('');
					var response = (ajaxcall3.responseText).split("^");
					if(response == 'Thinking to redirect')
					{
						window.location = "../logout.php";
						return false;
					}
					else if(response[0] == '1')
					{
						$('#customerdetailshide').hide();
						$('#customerdetailsshow').show();
						enableformelemnts();
						disableitemselection();
						$('#lastslno').val(response[1]);
						$('#displaycustomerid').html(response[2]);
						$('#displaycompanyname').val(response[3]);
						$('#displaycontactperson').val(response[4]);
						$('#displayaddress').val(response[5]);
						$('#displayphone').val(response[6]);
						$('#displaycell').val(response[7]);
						$('#displayemail').val(response[8]);
						if(response[11] == '')
							$('#displaytypeofcategory').html('Not Available');
						else
							$('#displaytypeofcategory').html(response[11]);
						if(response[12] == '')
							$('#displaytypeofcustomer').html('Not Available');
						else
							$('#displaytypeofcustomer').html(response[12]);
						$('#displaydealer').html(response[13]);
						generateinvoicedetails('');
						$("#cusnamehidden").val(response[3]);
						$("#addresshidden").val(response[5]);
						$("#contactpersonhidden").val(response[4]);
						$("#emailhidden").val(response[8]);
						$("#phonehidden").val(response[6]);
						$("#cellhidden").val(response[7]);
						$("#custypehidden").val(response[12]);
						$("#cuscategoryhidden").val(response[11]);
						$("#displaycustomerdetails").hide();
						$('#displaymarketingexe').html('Not Selected');
						$('#toggleimg1').attr('src',"../images/plus.jpg");
						resetdealername();
					} 
			}
			else
				document.getElementById('customerselectionprocess').innerHTML = scripterror();
		  }
		}
		ajaxcall3.send(passData);
	}
}

function selectfromlist()
{
	$('#messagerow').html('<div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div>');
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
	newproductentry();
	customerdetailstoform(selectbox.value);
	//$("#viewinvoicediv").hide();
	hideorshowpaymentdetailsdiv();
	showhidepaymentinfodiv();
	showhidepaymentdiv();
	enablebuttontype();
	$('#displaydealerdetailsicon').hide();
}

function selectacustomer(input)
{
	var selectbox = document.getElementById('customerlist');
	if(input == "")
	{
		getcustomerlist1();
	}
	else
	{
		selectbox.options.length = 0;
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
				if(pattern.test(customerarray[i].toLowerCase()))
				{
					var splits = customerarray[i].split("^");
					selectbox.options[selectbox.length] = new Option(splits[0], splits[1]);
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
		var form = document.submitform;
		var input = document.getElementById('detailsearchtext').value;
		selectacustomer(input);
	}
}

function scrollcustomer(type)
{
	var selectbox = document.getElementById('customerlist');
	var totalcus = selectbox.options.length;
	var selectedcus = selectbox.selectedIndex;
	if(type == 'up' && selectedcus != 0)
		selectbox.selectedIndex = selectedcus - 1;
	else if(type == 'down' && selectedcus != totalcus)
		selectbox.selectedIndex = selectedcus + 1;
	selectfromlist();
}

//Search customer by customer id
function searchbycustomerid(cusid)
{
	document.getElementById('form-error').innerHTML = '';
	var form = document.submitform;
	form.reset();
	var passData = "switchtype=searchbycustomerid&customerid=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxcall5 = createajax(passData);
	var queryString = "../ajax/customer.php";
	ajaxcall5.open("POST", queryString, true);
	ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall5.onreadystatechange = function()
	{
		if(ajaxcall5.readyState == 4)
		{
			if(ajaxcall5.status == 200)
			{
				var ajaxresponse = ajaxcall5.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var response = (ajaxresponse).split("^"); 
					if(response[0] == '')
					{
						alert('Customer Not Available.');
					}
					else
					{
						$('#detailsearchtext').val(response[2]);
						selectacustomer(response[2]);
						$('#customerlist').val(response[0]);
						customerdetailstoform(response[0]);
					}
				}
			}
			else
				document.getElementById('form-error').innerHTML = scripterror();
		}
	}
	ajaxcall5.send(passData);
}


//Search customer by Invoice no
function searchbyinvoiceno(invoiceno)
{
	$('#detailsearchtext').val('');
	document.getElementById('form-error').innerHTML = '';
	var form = document.submitform;
	form.reset();
	var passData = "switchtype=searchbyinvoiceno&invoiceno=" + encodeURIComponent(invoiceno) + "&dummy=" + Math.floor(Math.random()*100032680100);
	ajaxcall5 = createajax(passData);
	var queryString = "../ajax/invoicing.php";
	ajaxcall5.open("POST", queryString, true);
	ajaxcall5.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall5.onreadystatechange = function()
	{
		if(ajaxcall5.readyState == 4)
		{
			if(ajaxcall5.status == 200)
			{
				var ajaxresponse = ajaxcall5.responseText;
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var ajaxresponse = (ajaxcall5.responseText).split("#@#"); 
					var response1  = ajaxresponse[1];
					if(ajaxresponse[0] == '2')
					{
						alert('Invoice Not Available');
					}
					else
					{
						
						var response = response1.split('^*^');
						invoicearray = new Array();
						for( var i=0; i<response.length; i++)
						{
							invoicearray[i] = response[i];
						}
						getinvoicelistonsearch();
						$("#totalcount").html(invoicearray.length);
						$("#filter-form-error").html();
						$('#searchinvoiceno').val('');
						if(response.length > 1)
						{
							newproductentry();
							$('#lastslno').val('');
							resetdealerdisplaydetails();
							generateinvoicedetails('');
						}
						/*$('#detailsearchtext').val(response[2])
						selectacustomer(response[2])
						$('#customerlist').val(response[1]);
						customerdetailstoform(response[1]);
						$('#searchinvoiceno').val('');*/
					}
				}
			}
			else
				document.getElementById('form-error').innerHTML = scripterror();
		}
	}
	ajaxcall5.send(passData);
}

function getinvoicelistonsearch()
{	
	var form = $("#submitform" );
	var selectbox = $('#customerlist');
	var numberofcustomers = invoicearray.length;
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = invoicearray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	if(numberofcustomers == 1)
	{
		$('#detailsearchtext').val(splits[0])
		selectacustomer(splits[0])
		$('#customerlist').val(splits[1]);
		customerdetailstoform(splits[1]);
	}
}

function searchbycustomeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchcustomerid').value;
		searchbycustomerid(input);
	}
}


function searchbyinvoicenoevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = document.getElementById('searchinvoiceno').value;
		searchbyinvoiceno(input);
	}
}


function resetdealername()
{
	$("#displaydealername").html('<font color="#FF0000">Not Selected</font>');
	$("#displaydealerdetailsicon").hide();
}
//Adding the selected products to productlist
function addselectedproduct(command)
{
	var field = $("#product");
	var field1 = $("#product2");
	if(command == 'software')
	{
		
		if(!field.val()){ alert('Please select a Item(Software)'); field.focus();return false;}
	}
	else
	{
		if(!field1.val()){ alert('Please select a Item(Other)'); field1.focus();return false;}
	}
	$('#messagerow').remove();
	//$("#viewinvoicediv").hide();
	var rowcount = $('#seletedproductgrid tr').length;
	if(rowcount == 2)
		var k = 1;
	else
		var k = (rowcount-1);
	var form = $('#submitform');
	var productgrid ='';
	var editpurchasetypeid = 'editpurchasetype'+k;
	var editpurchasetypehtml = 'editpurchasetypehtml'+k;
	var editpurchasetypelinkid = 'editpurchasetypelinkid'+k;
	var editusagetypelinkid = 'editusagetypelinkid'+k;
	var purchasetypehidden = 'purchasetypehidden'+k;
	var editusagetypeid = 'editusagetype'+k;
	var editusagetypehtml = 'editusagetypehtml'+k;
	var usagetypehidden = 'usagetypehidden'+k;
	var productamount = 'productamount'+k;
	var productrowid = 'productrowid'+k;
	var productquantity = 'productquantity'+k;
	var productrowslnoid = 'productrowslnoid'+k;
	var productname =$("#product option:selected").text();
	var productvalue =  $("#product").val();
	var removelinkid = 'removelinkid'+k;
	var itemtype = 'itemtype'+k;
	var productselectedhidden = 'productselectedhidden'+k;
	var productnamehidden = 'productnamehidden'+k;
	var cardslnohidden = 'cardslnohidden'+k;
	if(field.val() != '')
	{
		productgrid = '<tr id=\'' + productrowid + '\'><td nowrap="nowrap" class="td-border-grid" id=\'' + productrowslnoid + '\' >' + k + '</td><td nowrap="nowrap" class="td-border-grid">' + productname + ' <input name="productselectedhidden[]" class="swiftselect" id=\'' + productselectedhidden + '\' type = "hidden" value = ' + productvalue + '><input name="productnamehidden[]" class="swiftselect" id=\'' + productnamehidden + '\' type = "hidden" value = \'' + productname + '\' ></td><td valign="top"  class="td-border-grid"> <div align="center"><span id=\'' + editpurchasetypehtml + '\' align = "center">New </span><br/><span id= "editdiv' + productvalue + '" style="display:block;" align = "center"><a id="' + editpurchasetypelinkid + '" style="cursor:pointer" onclick = "editamountonpurchasetype(\'' + editpurchasetypehtml + '\',\'' + purchasetypehidden + '\')" class = "r-text">( Change )</a> </span><input name="purchasetypehidden" class="swiftselect" id=\'' + purchasetypehidden + '\' type = "hidden" value = "new" ><input name="itemtype" class="swiftselect" id=\'' + itemtype + '\' type = "hidden" value = "product"></div></td><td valign="top" class="td-border-grid"> <div align="center"><span id=\'' + editusagetypehtml + '\' align = "center">Single User</span><br/><span id= \'' + editusagetypeid + '\' style="display:block;" align = "center"><a id="' + editusagetypelinkid + '" style="cursor:pointer" onclick = "editamountonusagetype(\'' + editusagetypehtml + '\',\'' + usagetypehidden + '\')" class="r-text">( Change )</a> </span><input name="usagetypehidden" class="swiftselect" id=\'' + usagetypehidden + '\' type = "hidden" value = "singleuser" ><input name="cardslnohidden" class="swiftselect" id=\'' + cardslnohidden + '\' type = "hidden" value = "" ></div></td><td nowrap="nowrap" class="td-border-grid" ><div align="center"><select name="productquantity" class="swiftselect" id=\'' + productquantity + '\' style="width:50px;" onchange ="calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="1" selected="selected">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></div></td><td nowrap="nowrap" class="td-border-grid" ><input name="productamount" type="text" class="swifttext" id=\'' + productamount + '\'  maxlength="6"  autocomplete="off" style="width:80px;text-align:right" onkeyup ="calculatenormalprice();" onchange ="calculatenormalprice();"/></td><td nowrap="nowrap" class="td-border-grid" ><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div></td></tr>';
	}
	else
	{
		var productname2 =$("#product2 option:selected").text();
		var productvalue =  $("#product2").val();
		var serviceselectedhidden = 'productselectedhidden'+k;
		productgrid = '<tr id=\'' + productrowid + '\'><td nowrap="nowrap" class="td-border-grid" id=\'' + productrowslnoid + '\' >' + k + '</td><td nowrap="nowrap" class="td-border-grid" colspan = "4">' + productname2 + ' <input name="itemtype" class="swiftselect" id=\'' + itemtype + '\' type = "hidden" value = "service"><input name="serviceselectedhidden[]" class="swiftselect" id=\'' + serviceselectedhidden + '\' type = "hidden" value =\'' + productname2 + '\'></td><td nowrap="nowrap" class="td-border-grid" ><input name="productamount" type="text" class="swifttext" id=\'' + productamount + '\'  maxlength="6"  autocomplete="off" style="width:80px;text-align:right" onkeyup ="calculatenormalprice();" onchange ="calculatenormalprice();"/></td><td nowrap="nowrap" class="td-border-grid" ><div align ="center"><font color = "#FF0000"><strong><a id="' + removelinkid + '" onclick = "deleteproductrow(\'' + k + '\');" style="cursor:pointer;" >X</a></strong></font></div></td></tr>';
	}
	$("#seletedproductgrid").append(productgrid);
	$('#resultdiv').show();//alert($('#seletedproductgrid tr').length);
	if($('#seletedproductgrid tr').length == 3)
	{
		//alert('here');
		adddescriptionrows();
	}
	$("#product" ).val('');
	$("#product2" ).val('');
	$("#onlineslno" ).val('');
}


//delete product from list
function deleteproductrow(id)
{
	$('#productrowid'+id).remove();
	var rowcount = $('#seletedproductgrid tr').length;
	rowcount = (rowcount-2);
	countval = 0;
	for(i=1;i<=(rowcount+1);i++)
	{
		var editpurchasetypeid = '#editpurchasetype'+i;
		var editpurchasetypehtml = '#editpurchasetypehtml'+i;
		var editpurchasetypelinkid = '#editpurchasetypelinkid'+i;
		var editusagetypelinkid = '#editusagetypelinkid'+i;
		var purchasetypehidden = '#purchasetypehidden'+i;
		var editusagetypeid = '#editusagetype'+i;
		var editusagetypehtml = '#editusagetypehtml'+i;
		var usagetypehidden = '#usagetypehidden'+i;
		var productamount = '#productamount'+i;
		var productrowid = '#productrowid'+i;
		var productquantity = '#productquantity'+i;
		var productrowslnoid = '#productrowslnoid'+i;
		var removelinkid = '#removelinkid'+i;
		var productselectedhidden = '#productselectedhidden'+i;
		var cardslnohidden = '#cardslnohidden'+i;
		if($(productrowid).length > 0)
		{
			countval++;
			if($(editpurchasetypeid).length > 0)
				$("#editpurchasetype"+ i).attr("id","editpurchasetype"+ countval);
			if($(editpurchasetypehtml).length > 0)
				$("#editpurchasetypehtml"+ i).attr("id","editpurchasetypehtml"+ countval);
			if($(purchasetypehidden).length > 0)
				$("#purchasetypehidden"+ i).attr("id","purchasetypehidden"+ countval);
			if($(editusagetypeid).length > 0)
				$("#editusagetype"+ i).attr("id","editusagetype"+ countval);
			if($(editusagetypehtml).length > 0)
				$("#editusagetypehtml"+ i).attr("id","editusagetypehtml"+ countval);
			if($(usagetypehidden).length > 0)
				$("#usagetypehidden"+ i).attr("id","usagetypehidden"+ countval);
			if($(cardslnohidden).length > 0)
				$("#cardslnohidden"+ i).attr("id","cardslnohidden"+ countval);
			$("#productamount"+ i).attr("id","productamount"+ countval);
			$("#productrowid"+ i).attr("id","productrowid"+ countval);
			$("#productrowslnoid"+ i).attr("id","productrowslnoid"+ countval);
			$("#productselectedhidden"+ i).attr("id","productselectedhidden"+ countval);
			$("#itemtype"+ i).attr("id","itemtype"+ countval);
			$("#productrowslnoid"+ countval).html(countval);
			
			if($("#productquantity"+ i).length > 0)
				$("#productquantity"+ i).attr("id","productquantity"+ countval);

			if($("#editpurchasetypelinkid"+ i).length > 0)
			{
				$("#editpurchasetypelinkid"+ i).attr("id","editpurchasetypelinkid"+ countval);
				document.getElementById("editpurchasetypelinkid" + countval).onclick = new Function('editamountonpurchasetype("editpurchasetypehtml' + countval + '" , "purchasetypehidden' + countval + '")') ;
			}
			if($("#editusagetypelinkid"+ i).length > 0)
			{
				$("#editusagetypelinkid"+ i).attr("id","editusagetypelinkid"+ countval);
				document.getElementById("editusagetypelinkid" + countval).onclick = new Function('editamountonusagetype("editusagetypehtml' + countval + '" , "usagetypehidden' + countval + '")') ;
			}
			$("#removelinkid"+ i).attr("id","removelinkid"+ countval);
			document.getElementById("removelinkid" + countval).onclick = new Function('deleteproductrow("' + countval + '")');
		}
	}
	var productarray=document.getElementsByName("productselectedhidden[]");
	var productvalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productvalues += productarray[i].value  + '#';
	}
	var productlist = productvalues.substring(0,(productvalues.length-1));//alert(rowcount);
	if(rowcount == 0)
	{
		 newproductentry();
	}
	calculatenormalprice();
}


function customerdetailsdisplayandhide()
{
	if($('#displaycustomer').is(':visible'))
	{
		$('#displaycustomer').hide();
		$('#hidemoredetails').show();
	}
	else
	{
		$('#displaycustomer').show();
		$('#hidemoredetails').hide();
	}
}

//To add description rows
function adddescriptionrows()
{
	//alert('1');
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 5)
	{
		$('#adddescriptionrowdiv').hide();
		var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="add" >Add</option><option value="less" >Less</option></select></td><td width="55%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:300px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
	}
	else
	{
		$('#adddescriptionrowdiv').show();
		var row = '<tr id="removedescriptionrow"><td width="19%"><select name="descriptiontype" class="swiftselect" id="descriptiontype" onchange="enableordisabledescriptionfields(\'' + rowcount + '\');calculatenormalprice();" onkeypress="calculatenormalprice();"><option value="" selected="selected">None</option><option value="add" >Add</option><option value="less" >Less</option></select></td><td width="55%"><input name=description type="text" class="swifttext-mandatory1" id=description   maxlength="150"  autocomplete="off" style="width:300px;" disabled="disabled" value="Additional Charges" /><td width="17%"><div align="left"><input name=descriptionamount type="text" class="swifttext-mandatory1" id=descriptionamount   maxlength="10"  autocomplete="off" style="width:80px;text-align:right" disabled="disabled" onkeyup="calculatenormalprice();" onchange ="calculatenormalprice();"/></div></td> <td width="9%"><div align ="center" id="removerowdiv" style="display:none;" ><font color = "#FF0000"><strong><a onclick ="removedescriptionrows();calculatenormalprice()" style="cursor:pointer;">X</a></strong></font></div></td></tr>';
	}
	$("#adddescriptionrows").append(row);
	$("#descriptiontype").attr("id","descriptiontype"+ rowcount)
	$("#description").attr("id","description"+ rowcount);
	$("#descriptionamount").attr("id","descriptionamount"+ rowcount);
	$("#removerowdiv").attr("id","removerowdiv"+ rowcount);
	$("#removedescriptionrow").attr("id","removedescriptionrow"+ rowcount);
	for(i=0,j=0; i<rowcount; i++,j++)
	{	
		if((rowcount-1) == j)
			$('#removerowdiv'+(j+1)).show();
		else
			$('#removerowdiv'+(j+1)).hide();
	}
}


//Remove description row
function removedescriptionrows()
{
	//alert('1');
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 2)
	{
		$('#removedescriptionrow'+(rowcount-1)).remove();
		$('#removerowdiv'+(rowcount-2)).hide();
	}
	else
	{
		$('#removedescriptionrow'+(rowcount-1)).remove();
		$('#removerowdiv'+(rowcount-2)).show();	
		$('#adddescriptionrowdiv').show();
	}
	calculatenormalprice();
}

//Change purchase type
function editamountonpurchasetype(purchasetypehtml,purchasetypehidden)
{
	if(document.getElementById(purchasetypehidden).value == 'new')
	{
		document.getElementById(purchasetypehtml).innerHTML = 'Updation';
		document.getElementById(purchasetypehidden).value = 'updation';
	}
	else if(document.getElementById(purchasetypehidden).value == 'updation')
	{
		var rowname = purchasetypehidden;
		var rowid = rowname.substring('18'); alert(rowid);
		document.getElementById(purchasetypehtml).innerHTML = 'Convertion';
		document.getElementById(purchasetypehidden).value = 'convertion';
		getcarddetails('',rowid);
	}
	else
	{
		document.getElementById(purchasetypehtml).innerHTML = 'New';
		document.getElementById(purchasetypehidden).value = 'new';
	}
}

//Change Usagetype
function editamountonusagetype(usagetypehtml,usagetypehidden)
{
	if(document.getElementById(usagetypehidden).value == 'singleuser')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Multiuser';
		document.getElementById(usagetypehidden).value = 'multiuser';
	}
	else if(document.getElementById(usagetypehidden).value == 'multiuser')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Add License';
		document.getElementById(usagetypehidden).value = 'addlic';
	}
	else if(document.getElementById(usagetypehidden).value == 'addlic')
	{
		document.getElementById(usagetypehtml).innerHTML = 'Singleuser';
		document.getElementById(usagetypehidden).value = 'singleuser';
	}
}

//Function to calculate the amount
function calculatepricing(command,validate)
{
	var form = document.getElementById('submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;
	var offeramount = document.getElementById('offeramount').value;

	var pricingtype = getradiovalue(form.pricing);
	$('#form-error').html(getprocessingimage());
	$('#displayofferremarksdiv').show();
	if(pricingtype == command)
	{
		var purchasearrray = new Array();
		var purchasevalues = new Array();
		var usagearrray = new Array();
		var usagevalues = new Array();
		var productamountarrray = new Array();
		var productamountvalues = new Array();
		var productquantityarrray = new Array();
		var productquantityvalues = new Array();
		var descriptiontypearrray = new Array();
		var descriptiontypevalues = new Array();
		var descriptionarrray = new Array();
		var descriptionvalues = new Array();
		var descriptionamountarrray = new Array();
		var descriptionamountvalues = new Array();
		for(i=0,j=1; i<productcount,j<=(productcount); i++,j++)
		{	

			if($("#itemtype"+ j).val() == 'product')
			{
				purchasearrray[i] = 'purchasetypehidden' + j;
				purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
				usagearrray[i] = 'usagetypehidden' + j;
				usagevalues[i] = document.getElementById(usagearrray[i]).value;
				productquantityarrray[i] = 'productquantity' + j;
				productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
			}
			productamountarrray[i] = 'productamount' + j;
			productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
			if(purchasearrray == '')
			{
				 $('#form-error').html(''); alert('Please enter the amount'); return false;
			}
		}
		for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
		{	
			descriptiontypearrray[i] = 'descriptiontype' + j;
			descriptiontypevalues[i] = document.getElementById(descriptiontypearrray[i]).value;
			descriptionarrray[i] = 'description' + j;
			descriptionvalues[i] = document.getElementById(descriptionarrray[i]).value;
			descriptionamountarrray[i] = 'descriptionamount' + j;
			descriptionamountvalues[i] = document.getElementById(descriptionamountarrray[i]).value;
			if(descriptiontypevalues[i] != '')
			{
				var field = document.getElementById(descriptionarrray[i]);
				if(!field.value) {
					$('#form-error').html('');
					alert('Please Enter the Description...')
					field.focus();  return false;}
				var field = document.getElementById(descriptionamountarrray[i]);
				if(!field.value) {
					$('#form-error').html('');
					alert('Please Enter the amount...');
					field.focus();  return false;}
				if(field.value)	{ if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid.');
					field.focus(); return false; } }
			}
		}
		if(pricingtype == 'offer')
		{
			var field = $('#offeramount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the offer amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.')
				field.focus(); return false; } }
			var field = $('#offerremarks');
			if(!field.val())
			{
				$('#form-error').html('');

				alert('Please Enter the offer Description.');
				field.focus(); return false;
			}
		}
		else if(pricingtype == 'inclusivetax')
		{
			var field = $('#inclusivetaxamount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.');
				field.focus(); return false; } }
		}
		if((pricingtype == 'offer') && ($('#offerremarks').val() != ''))
		{
			$('#displayofferremarksdiv').html('Offer: '+$('#offerremarks').val());
			$('#displayofferremarksdiv').addClass('messagebox');
			$('#removeoffermegdiv').show();
			$('#offerremarkshidden').val($('#displayofferremarksdiv').html());
		}
		else
		{
			removeofferremarksdiv();
		}
		$('#pricingdiv').hide();
		var productarray=document.getElementsByName("productselectedhidden[]");

		var productvalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productvalues += productarray[i].value  + '#';
		}
		var productlist = productvalues.substring(0,(productvalues.length-1));//alert(descriptionamountvalues);
		
		
		var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
		var passData = "switchtype=calculateamount&pricingtype=" +(pricingtype) + "&purchasevalues=" + (purchasevalues) + "&usagevalues=" + (usagevalues) + "&productamountvalues=" + (productamountvalues) + "&productquantityvalues=" + (productquantityvalues)  + "&descriptiontypevalues=" + (descriptiontypevalues) + "&descriptionvalues=" + (descriptionvalues) + "&descriptionamountvalues=" + (descriptionamountvalues)+ "&offeramount=" + (offeramount)+ "&inclusivetaxamount=" + (inclusivetaxamount)+ "&selectedcookievalue=" + (productlist)+ "&dummy=" + Math.floor(Math.random()*10054300000);
		var ajaxcall6 = createajax();
		queryString = "../ajax/invoicing.php";
		ajaxcall6.open("POST", queryString, true);
		ajaxcall6.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall6.onreadystatechange = function()
		{
			if(ajaxcall6.readyState == 4)
			{
				if(ajaxcall6.status == 200)
				{//alert('7');
					$('#form-error').html('');
					var response = ajaxcall6.responseText.split('^');
					if(response[0] == 1)
					{
						document.getElementById("totalamount").value = response[1];
						document.getElementById("sericetaxamount").value = response[2];
						document.getElementById("netamount").value = response[3];
						$("#productcounthidden").val(response[6]);
					}
					else if(response[0] == 2)
					{
						productamountarray = response[1].split('*');
						productarraycount = productamountarray.length;
						k= 0;
						j= 0;
						for(i=0;i<productcount;i++)
						{
							k++;
							if($("#itemtype"+ k).val() == 'product')
							{
								productamountfield = 'productamount'+k;
								document.getElementById(productamountfield).value = productamountarray[j];
								j++;
							}
						}
						document.getElementById("totalamount").value = response[2];
						document.getElementById("sericetaxamount").value = response[3];
						document.getElementById("netamount").value = response[4];
						$("#productcounthidden").val(response[6]);
						calculatenormalprice();
					}
					else
					{
						document.getElementById('form-error').innerHTML = scripterror();
					}
				}
			}
		}
		ajaxcall6.send(passData);
	}
	else
		alert('Please select a Pricing type');
}


function validatepricingfield()
{
	var pricingtype = $("input[name='pricing']:checked").val();
	if(pricingtype == 'offer')
	{
		$('#offeramtdiv').show();
		$('#inclusivetaxamtdiv').hide();
		$('#offerdescriptiondiv').show();
		$('#displayapplylink').html('&nbsp;<a onclick="calculatepricing(\'offer\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else if(pricingtype == 'inclusivetax')
	{
		$('#inclusivetaxamtdiv').show();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('&nbsp;&nbsp;<a onclick="calculatepricing(\'inclusivetax\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else if(pricingtype == 'default')
	{
		$('#inclusivetaxamtdiv').hide();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('Make product prices to their Default price &nbsp;<a onclick="calculatepricing(\'default\');" class="r-text">Apply &#8250;&#8250;</a>');
	}
	else
	{
		$('#inclusivetaxamtdiv').hide();
		$('#offeramtdiv').hide();
		$('#offerdescriptiondiv').hide();
		$('#displayapplylink').html('Make product price Empty &nbsp;<a onclick="makefieldsempty();" class="r-text">Apply &#8250;&#8250;</a');
		//emptyfields();
	}
	$('#offeramount').val('');
	$('#inclusivetaxamount').val('');
	$('#offerremarks').val('');
	removeofferremarksdiv();
}

function makefieldsempty()
{
	var form = $('#submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;;
	var productamountarrray = new Array();
	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		document.getElementById('productamount'+j).value = '';
		document.getElementById('totalamount').value = '';
		document.getElementById('sericetaxamount').value = ''; 
		document.getElementById('netamount').value  = '';
	}	
}


function enableordisabledescriptionfields(rowcount)
{
	var i = rowcount;
	var descriptiontype = '#descriptiontype'+i;
	var description = 'description'+i;
	var descriptionamount = 'descriptionamount'+i;
	if($(descriptiontype).val() != '')
	{
		$('#'+description).val('');
		$('#'+description).attr("disabled", false); 
		$('#'+descriptionamount).attr("disabled", false); 
		description1 = '#description'+(rowcount-1);
		$('#'+description).removeClass('swifttext-mandatory1');
		$('#'+description).addClass('swifttext');
		$('#'+descriptionamount).removeClass('swifttext-mandatory1');
		$('#'+descriptionamount).addClass('swifttext');
	}
	else
	{
		$('#'+description).val('Additional Charges');
		$('#'+description).attr("disabled", true); 
		$('#'+descriptionamount).attr("disabled", true); 
		$('#'+description).addClass('swifttext-mandatory1');
		$('#'+description).removeClass('swifttext');
		$('#'+descriptionamount).addClass('swifttext-mandatory1');
		$('#'+descriptionamount).removeClass('swifttext');
		$('#'+descriptionamount).val('');
	}
}


//New Product Entry
function newproductentry()
{
	$('#adddescriptionrowdiv').hide();
	$('#pricingdiv').hide();
	$('#adddescriptionrows tr').remove();
	$("#submitform" )[0].reset();
	$("#productcounthidden" ).val('');
	$("#form-error" ).html('');
	//$("#lastslno" ).val('');
	$("#offerremarkshidden" ).val('');
	$('#seletedproductgrid tr').remove();
	$("#seletedproductgrid").append('<tr class="tr-grid-header"><td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td><td width="27%" nowrap="nowrap" class="td-border-grid">Product</td><td width="15%" nowrap="nowrap" class="td-border-grid">Purchase Type</td><td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td><td width="10%" nowrap="nowrap" class="td-border-grid">Quantity</td><td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td><td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td></tr><tr><td colspan="7" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Item First</font></strong></div></td></tr>');
	//hideorshowremarksdiv();
	removeofferremarksdiv();
	enableproceedbutton();
	hideorshowpaymentdetailsdiv();
	showhidepaymentinfodiv();
	showhidepaymentdiv();
	$('#displaydealerdetailsicon').hide();
	$('#resultdiv').hide();
	disableitemselection();
	resetdealername();
}

function newinvoiceentry()
{
	var confirmation = confirm("Are you sure you want to clear the existing invoice?");
	if(confirmation)
	{
		newproductentry();
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


function disableformelemnts_invoicing()
{
	var count = document.submitform.elements.length;
	for (i=0; i<count; i++) 
	{
		var element = document.submitform.elements[i]; 
		element.disabled=true; 
	}
}

function disableitemselection()
{
	$('#product').attr('disabled', 'disabled');
	$('#product2').attr('disabled', 'disabled');
}

function enableitemselection()
{
	$('#product').removeAttr('disabled');
	$('#product2').removeAttr('disabled');
}

function pricingdivhideshow()
{
	if($('#pricingdiv').is(':visible'))
		$('#pricingdiv').hide();
	else
	{
		$('#pricingdiv').show();
		validatepricingfield();
	}
}


function calculatenormalprice(command)
{
	//alert('here');
	var form = $('#submitform');
	var rowcount = $('#adddescriptionrows tr').length;
	var productcount = ($('#seletedproductgrid tr').length)-2;
	var count = document.getElementById('productcounthidden').value;;
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	var productamount = 0;
	var totalamount = 0;
	var servicetax = 0;
	var netamount = 0;
	for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
	{	
		descriptiontypearrray[i] = 'descriptiontype' + j;
		descriptiontypevalues[i] = document.getElementById(descriptiontypearrray[i]).value;
	
		descriptionarrray[i] = 'description' + j;
		descriptionvalues[i] = document.getElementById(descriptionarrray[i]).value;

		descriptionamountarrray[i] = 'descriptionamount' + j;
		if(isNaN(document.getElementById(descriptionamountarrray[i]).value))
			document.getElementById(descriptionamountarrray[i]).value = '0';
		descriptionamountvalues[i] = document.getElementById(descriptionamountarrray[i]).value;
		if(command == 'validate')
		{
			if(descriptiontypevalues[i] == 'add' || descriptiontypevalues[i] == 'less' )
			{
				var field = document.getElementById(descriptionarrray[i]);
				if(!field.value)
				{
					alert('Please Enter the Description...');
					field.focus(); 
					return false;
				}
				var field = document.getElementById(descriptionamountarrray[i]);
				if(!field.value)
				{ 
					alert('Please Enter the amount...');
					field.focus(); 	return false;
				}
				if(field.value)	{ if(!validateamountfield(field.value)) 
				{
					alert('Amount is not Valid.');
					field.focus(); return false; }
				}
			}
		}
	}
	for(i=0,j=1; i<productcount,j<=productcount; i++,j++)
	{	
		if($('#usagetypehidden' + j).length > 0)
		{
			purchasearrray[i] = 'purchasetypehidden' + j;
			purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
			usagearrray[i] = 'usagetypehidden' + j;
			usagevalues[i] = document.getElementById(usagearrray[i]).value;
			productquantityarrray[i] = 'productquantity' + j;
			productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
		}

		productamountarrray[i] = 'productamount' + j;
		productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
		//alert(productamountarrray);alert(productamountvalues)
		var field = document.getElementById(productamountarrray[i]);
		if(command == 'validate')
		{
			if(!field.value)
			{ 
				alert('Please Enter the amount');
				field.focus(); return false;
			}
			if(field.value){ if(!validateamountfield(field.value))
			{
				alert('Amount is not Valid.');
				field.focus(); return false; }
			}
		}
		else
		{
			if($('#productquantity' + j).length > 0)
				productamount += (productamountvalues[i])*1*productquantityvalues[i];
			else
				productamount += (productamountvalues[i])*1;
		}
	}
	totalamount = productamount;
	if(isNaN(totalamount))
	{
		$('#totalamount').val('0');		
		$('#sericetaxamount').val('0');	
		$('#netamount').val('0');	
	}
	if(productamount == 0)
	{
		//alert('here');
		$('#totalamount').val('0');		
		$('#sericetaxamount').val('0');	
		$('#netamount').val('0');	
		$('#paymentamount').val('0');	
	}

	else
	{
		if(isNaN(totalamount))
			totalamount = 0;
		if(descriptionamountvalues != '')
			amount = getdescriptionamount(descriptionamountvalues,descriptiontypevalues);
		else
			amount = 0;
			
		//alert(amount);
		if(isNaN(amount))
			amount = 0;
		totalamount = totalamount + (amount)*1;
		servicetax = Math.round(totalamount * 0.1236); // edited by bhavesh Math.round(totalamount * 0.103);
		netamount = totalamount + servicetax;
		$('#totalamount').val(totalamount);		
		$('#sericetaxamount').val(servicetax);	
		$('#netamount').val(netamount);	
		if($('#partialpayment').is(':checked') == false)
			$('#paymentamount').val(netamount);	
	}
	
}

function emptyfields()
{
	var rowcount = $('#adddescriptionrows tr').length;
	var count = document.getElementById('productcounthidden').value;
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	$('#totalamount').val('0');
	$('#sericetaxamount').val('0');	
	$('#netamount').val('0');	
	for(i=0,j=0; i<rowcount; i++,j++)
	{	
		descriptiontypearrray[i] = 'descriptiontype' + j;
		descriptionarrray[i] = 'description' + j;
		descriptionamountarrray[i] = 'descriptionamount' + j;
	}
	for(i=0,j=1; i<count; i++,j++)
	{	
		purchasearrray[i] = 'purchasetypehidden' + j;
		usagearrray[i] = 'usagetypehidden' + j;
		productamountarrray[i] = 'productamount' + j;
		document.getElementById(productamountarrray[i]).value = '0';
		productquantityarrray[i] = 'productquantity' + j;
	}
	enableordisabledescriptionfields();
}

function getdescriptionamount(descriptionamountvalues,descriptiontypevalues)
{
	amount = 0;
	descriptioncount = descriptionamountvalues.length;
	for(i=0;i<descriptioncount; i++)
	{
		if(descriptiontypevalues[i] == 'add')
			amount = (amount) + descriptionamountvalues[i] *1;
		else
		{
			amount = (amount) - descriptionamountvalues[i]*1;
			if(isNaN(amount))
				amount =0;
		}
	}
	return amount;
}


function removeofferremarksdiv()
{
	$('#displayofferremarksdiv').html('');
	$('#displayofferremarksdiv').hide();
	$('#removeoffermegdiv').hide();
	$('#offerremarkshidden').val('');
	$('#displayofferremarksdiv').removeClass('messagebox');
}

function resetdealerdisplaydetails()
{
	$('#displaycustomerid').html('');
	$('#displaycompanyname').html('');
	$('#displaycontactperson').html('');
	$('#displayaddress').html('');
	$('#displayphone').html('');
	$('#displaycell').html('');
	$('#displayemail').html('');
	$('#displayregion').html('');
	$('#displaybranch').html('');
	$('#displaytypeofcategory').html('Not Available');
	$('#displaytypeofcustomer').html('Not Available');
}
function generateinvoicedetails(startlimit)
{
	if($('#lastslno').val() == '')
	{
		$('#invoicedetailsgridwb1').html("");
		$('#invoicedetailsgridc1_1').html('<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;"><tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td><td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td><td nowrap = "nowrap" class="td-border-grid" align="left">Action<input type="hidden" name="invoicelastslno" id="invoicelastslno" /></td></tr><tr><td colspan="6" valign="top" class="td-border-grid"><div align="center">No datas found to display</div></td></tr></table>');
		$('#invoicedetailsgridc1link').html("");
		return false;
	}
	else
	{
		var form = $('#submitform');
		$('#invoicedetailsgridc1').show();
		$('#detailsdiv').hide();
		var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
		var queryString = "../ajax/invoicing.php";
		ajaxcall41 = createajax();
		$('#invoicedetailsgridc1_1').html(getprocessingimage());
		$('#invoicedetailsgridc1link').html('');
		ajaxcall41.open("POST", queryString, true);
		ajaxcall41.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall41.onreadystatechange = function()
		{
			if(ajaxcall41.readyState == 4)
			{
				if(ajaxcall41.status == 200)
					{
						var ajaxresponse = ajaxcall41.responseText;
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
								$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
								$('#invoicedetailsgridc1_1').html(response[1]);
								$('#invoicedetailsgridc1link').html(response[3]);
								enableproceedbutton();
							}
							else
							{
								$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
							}
						}
						
					}
				else
					$('#invoicedetailsgridc1_1').html(scripterror());
			}
		}
		ajaxcall41.send(passData);
	}
}

//Function for "show more records" link - to get registration records
function getmoreinvoicedetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=invoicedetailsgrid&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/invoicing.php";
	ajaxcall51 = createajax();
	$('#invoicedetailsgridc1link').html(getprocessingimage());
	ajaxcall51.open("POST", queryString, true);
	ajaxcall51.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall51.onreadystatechange = function()
	{
		if(ajaxcall51.readyState == 4)
		{
			if(ajaxcall51.status == 200)
			{
				var ajaxresponse = ajaxcall51.responseText;
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
						$('#invoicedetailsgridwb1').html("Total Count :  " + response[2]);
						$('#invoicedetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
						$('#invoicedetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
						$('#invoicedetailsgridc1link').html(response[3]);
					}
					else
					{
						$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
					}
				}
			}
			else
				$('#invoicedetailsgridc1_1').html(scripterror());
		}
	}
	ajaxcall51.send(passData);
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
		$('#submitform').attr("action", "../ajax/viewinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
}

//Function to make the display as block as well as none-------------------------------------------------------------

function displayDiv2(elementid,imgname)
{
	if($('#'+ elementid).is(':visible'))
	{
		$("#displaycustomerdetails").hide();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/plus.jpg");
	}
	else
	{
		$("#displaycustomerdetails").show();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/minus.jpg");
	}
}

function disableproceedbutton()
{
	$('#proceed').attr("disabled", true); 
	$('#proceed').removeClass('swiftchoicebutton');	
	$('#proceed').addClass('swiftchoicebuttondisabled');	
}

function enableproceedbutton()
{
	$('#proceed').attr("disabled", false); 
	$('#proceed').removeClass('swiftchoicebuttondisabled');	
	$('#proceed').addClass('swiftchoicebutton');	
}


function showhidepaymentdiv()
{
	var paymenttype = $("input[name='modeofpayment']:checked").val();
	if(paymenttype == 'others')
	{
		$("#paymentmadenow").show();
		$("#paymentdiv").show();
	}
	else
	{
		$("#paymentmadenow").hide();
		$("#paymentdiv").hide();
	}
}

function showhidepaymentinfodiv()
{
	var paymenttype = $("input[name='paymentmodeselect']:checked").val();
	if(paymenttype == 'paymentmadenow')
	{
		$("#paymentmadenow").show();
		$("#willpaylater").hide();
	}
	else
	{
		$("#willpaylater").show();
		$("#paymentmadenow").hide();
	}
}


function hideorshowpaymentdetailsdiv()
{
	var paymenttype = $("input[name='paymentmode']:checked").val();
	
	if(paymenttype == 'chequeordd')
	{
		$("#paymentdetailsdiv2").show();
		$("#paymentdetailsdiv1").hide();
		$("#cashwarningmessage").hide();
		$("#bankdetailstip").hide();
	}
	else if(paymenttype == 'onlinetransfer')
	{
		$("#paymentdetailsdiv1").show();
		$("#paymentdetailsdiv2").hide();
		$("#cashwarningmessage").hide();
		$("#bankdetailstip").show();
	}
	else
	{
		$("#cashwarningmessage").show();
		$("#paymentdetailsdiv1").show();
		$("#paymentdetailsdiv2").hide();
		$("#bankdetailstip").hide();
	}
}

function getdealerdetails()
{
	var form = $('#submitform');
	var field = $('#dealer');
	var customer = $('#customerlist');
	if(customer.val())
	{
		if(!field.val())
		{
			alert('Please select a dealer first'); field.focus(); return false;
		}
	}
	else
	{
		alert('Please select a Customer first'); field.focus(); return false;
	}
	var passData = "switchtype=getdealerdetails&dealerid="+ encodeURIComponent($('#dealer').val());//alert(passData);
	var queryString = "../ajax/invoicing.php";
	ajaxcall411 = createajax();
//	$('#displaydealerdetails').html(getprocessingimage());
	ajaxcall411.open("POST", queryString, true);
	ajaxcall411.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall411.onreadystatechange = function()
	{
		if(ajaxcall411.readyState == 4)
		{
			if(ajaxcall411.status == 200)
				{
					var ajaxresponse = ajaxcall411.responseText;
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
							enableitemselection();
							$('#displaydealerdetailsicon').show();
							$('#dealeridhidden').val(response[1]);
							$('#displaydealername').html(response[2]);
							$('#displaydealerdetails').html(response[3]);
							$('#displaymarketingexe').html(response[2]);
							$('#dealer').val('');
						}
						else
						{
							$('#invoicedetailsgridc1_1').html("No datas found to be displayed");
						}
					}
					
				}
			else
				$('#invoicedetailsgridc1_1').html(scripterror());
		}
	}
	ajaxcall411.send(passData);
}


function showdealerdetails()
{
	$("").colorbox({ inline:true, href:"#displaydealerdetails" });
}

function disableorenablepaymentamount()
{
	if($('#partialpayment').is(':checked') == true)
	{
		$('#paymentamount').attr('readonly', false);
		$('#paymentamount').val('');
		$('#paymentamount').addClass('swifttext-mandatory');
		$('#paymentamount').removeClass('swifttext-readonly');
	}
	else
	{
		$('#paymentamount').attr('readonly', true);	
		$('#paymentamount').val($('#netamount').val());
		$('#paymentamount').removeClass('swifttext-mandatory');
		$('#paymentamount').addClass('swifttext-readonly');
	}
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
			var ajaxcall10 = createajax();
			$('#resendprocess').show();
			$('#resendemail').hide();
			$('#resendprocess').html(getprocessingimage());	
			queryString = "../ajax/invoicing.php";
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
							if(response[0] == '1')
							{
								$('#resendprocess').hide();
								$('#resendemail').show();
								alert('Invoice sent successfully to the selected Customer');
							}
							else
								$('#form-error').html(errormessage('Connection Failed.'));
						}
					}
					else
						$('#form-error').html(errormessage('Connection Failed.'));
				}
			}
			ajaxcall10.send(passData);
		}
	else
		return false;
	}
}


function paynow(onlineslno)
{
	$('#onlineslno').val(onlineslno);
	$('#submitform').attr("action", "http://imax.relyonsoft.com/user/makepayment/pay.php") ;
	$('#submitform').attr( 'target', '_blank' );
	$('#submitform').submit();
}



function proceedforpurchase()
{
	var form = document.getElementById('submitform');
	var confirmation = confirm("Please read the invoice contents above, once more. Invoice, once generated, cannot be edited or cancelled, subject to approval. To read it once more, click CANCEL. To proceed, click OK.");
	if(confirmation)
	{
		$('#proceedprocessingdiv').html(getprocessingimage());
		var rowcount = $('#adddescriptionrows tr').length;
		var count = $('#seletedproductgrid tr').length;
		count = (count-2);
		var pricingtype = getradiovalue(form.pricing);
		var paymenttype = getradiovalue(form.modeofpayment);
		var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
		var offeramount = document.getElementById('offeramount').value;
		var field = $('#dealeridhidden');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Select a Dealer and click Go.');
			field.focus(); return false;
		}
		var purchasearrray = new Array();
		var purchasevalues = new Array();
		var usagearrray = new Array();
		var usagevalues = new Array();
		var cardarray = new Array();
		var cardvalues = new Array();
		var productamountarrray = new Array();
		var productamountvalues = new Array();
		var productquantityarrray = new Array();
		var productquantityvalues = new Array();
		var serviceamountarrray = new Array();
		var serviceamountvalues = new Array();
		
		for(i=0,j=1; i<count; i++,j++)
		{	
			if($("#itemtype"+ j).val() == 'product')
			{
				purchasearrray[i] = 'purchasetypehidden' + j;
				purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
				usagearrray[i] = 'usagetypehidden' + j;
				usagevalues[i] = document.getElementById(usagearrray[i]).value;
				cardarray[i] = 'cardslnohidden' + j;
				cardvalues[i] = document.getElementById(cardarray[i]).value;
				productquantityarrray[i] = 'productquantity' + j;
				productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
				productamountarrray[i] = 'productamount' + j;
				productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
				var field = document.getElementById(productamountarrray[i]);
				if(!field.value) { 
					$('#form-error').html('');
					alert('Please Enter the amount');
					field.focus(); return false;}
				else if(field.value)	{ 
				if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid');
					field.focus(); return false; } }
				else
					return true;
			}
			else
			{
				if(serviceamountvalues == '')
					serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j).value;
				else
					serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j).value;
				var field =document.getElementById('productamount' + j);
				if(!field.value) { 
					$('#form-error').html('');
					alert('Please Enter the amount');
					field.focus(); return false;}
				else if(field.value)	{ 
				if(!validateamountfield(field.value)) {
					$('#form-error').html('');
					alert('Amount is not Valid');
					field.focus(); return false; } }
				else
					return true;
	
			}
		}
		var descriptiontypearrray = new Array();
		var descriptiontypevalues = new Array();
		var descriptionarrray = new Array();
		var descriptionvalues = new Array();
		var descriptionamountarrray = new Array();
		var descriptionamountvalues = new Array();
		for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
		{	//alert('here');
			if(document.getElementById('descriptiontype' + j).value != '')
			{
				if(descriptiontypearrray == '')
					descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
				else
					descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
				if(descriptiontypevalues == '')
					descriptiontypevalues = descriptiontypevalues + document.getElementById('descriptiontype' + j).value;
				else
					descriptiontypevalues = descriptiontypevalues + '~' + document.getElementById('descriptiontype' + j).value;
					
				if(descriptionarrray == '')
					descriptionarrray = descriptionarrray + 'description' + j;
				else
					descriptionarrray = descriptionarrray + '~' + 'description' + j;
				
				if(descriptionvalues == '')
					descriptionvalues = descriptionvalues + document.getElementById('description' + j).value;
				else
					descriptionvalues = descriptionvalues + '~' + document.getElementById('description' + j).value;
					
				if(descriptionamountarrray == '')
					descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
				else
					descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
				
				if(descriptionamountvalues == '')
					descriptionamountvalues =descriptionamountvalues + document.getElementById('descriptionamount' + j).value;
				else
					descriptionamountvalues = descriptionamountvalues + '~' + document.getElementById('descriptionamount' + j).value;
			}
		}
		if(descriptiontypearrray != '')
		{
			descriptiontypesplit = descriptiontypearrray.split('~');
			descriptiontypevaluesplit = descriptiontypevalues.split('~');
			descriptionarrraysplit = descriptionarrray.split('~');
			descriptionvaluessplit = descriptionvalues.split('~');
			descriptionamountarrraysplit = descriptionamountarrray.split('~');
			descriptionamountvaluessplit = descriptionamountvalues.split('~');
			for(var k=0;k<descriptiontypesplit.length;k++)
			{
				if(descriptiontypevaluesplit[k] != '')
				{
					var field = document.getElementById(descriptionarrraysplit[k]);
					if(!field.value) {
						$('#form-error').html(''); 
						alert('Please Enter the Description...')
						field.focus();  return false;}
					var field = document.getElementById(descriptionamountarrraysplit[k]);
					if(!field.value) {
						$('#form-error').html(''); 
						alert('Please Enter the amount');
						field.focus();  return false;}
					if(field.value)	{ if(!validateamountfield(field.value)) {
						$('#form-error').html('');  
						alert('Amount is not Valid.');
						field.focus(); return false; } }
				}
			}
		}
		if(pricingtype == 'offer')
		{
			var field = $('#offeramount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the offer amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.')
				field.focus(); return false; } }
		}
		else if(pricingtype == 'inclusivetax')
		{
			var field = $('#inclusivetaxamount');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the amount.');
				field.focus(); return false;
			}
			if(field.val())	{ if(!validateamountfield(field.val())) {
				$('#form-error').html('');
				alert('Amount is not Valid.');
				field.focus(); return false; } }
		}
		if(paymenttype == 'cheque/dd/neft')
		{
			var field = $('#paymentremarks');
			if(!field.val())
			{
				$('#form-error').html('');
				alert('Please Enter the Payment Remarks.');
				field.focus(); return false;
			}
		}
		//disableproceedbutton();
		var productarray=document.getElementsByName("productselectedhidden[]");
		var productvalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productvalues += productarray[i].value  + '#';
		}
		var productlist = productvalues.substring(0,(productvalues.length-1));//alert(productamountvalues);
		var productnamearray=document.getElementsByName("productnamehidden[]");
		var productnamevalues = '';
		for(i=0;i<productarray.length;i++)
		{
			productnamevalues += productnamearray[i].value  + '#';
		}
		var productnamelist = productnamevalues.substring(0,(productnamevalues.length-1));//alert(productamountvalues);
		var servicearray=document.getElementsByName("serviceselectedhidden[]");
		var servicevalues = '';
		for(i=0;i<servicearray.length;i++)
		{
			servicevalues += servicearray[i].value  + '#';
		}
		var servicelist = servicevalues.substring(0,(servicevalues.length-1));
	//	return false;
		var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
		if(paymentmodeselect == 'paymentmadelater')
			var paymentremarks = $('#remarks').val();
		else
			var paymentremarks = $('#paymentremarks').val();
			
		if(paymenttype == 'credit/debit')
		{
			disableproceedbutton();
		}
		else
		{
			if($("input[name='paymentmode']:checked").val() == 'chequeordd' && paymentmodeselect == 'paymentmadenow')
			{
				var field = $('#DPC_chequedate');
				if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
				var field = $('#chequeno');
				if(!field.val()) { $('#form-error').html(''); alert('Please enter the Cheque No'); field.focus(); return false; }
				if(field.val()){ if(!validateamountfield(field.val())) { $('#form-error').html(''); alert('Cheque No is not Valid.'); field.focus(); return false; }}
				var field = $('#drawnon');
				if(!field.val()) { $('#form-error').html(''); alert('Please enter the Drawn On'); field.focus(); return false; }
			}
		}
		var field = $('#paymentamount');
		if(field.val() == '')
		{
			$('#form-error').html(''); alert('Please enter the Payment amount'); field.focus(); return false;
		}
		$('#proceedprocessingdiv').html(getprocessingimage());
		var field = $('#invoicedated:checked').val();
		if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
		alert(cardvalues); //return false;
		disableproceedbutton();
		var passData = "switchtype=proceedforpurchase&pricingtype=" +encodeURIComponent(pricingtype) + "&purchasevalues=" + encodeURIComponent(purchasevalues) + "&usagevalues=" + encodeURIComponent(usagevalues) + "&cardvalues=" + encodeURIComponent(cardvalues) + "&productamountvalues=" + encodeURIComponent(productamountvalues) + "&productquantityvalues=" + encodeURIComponent(productquantityvalues)  + "&descriptiontypevalues=" + encodeURIComponent(descriptiontypevalues) + "&descriptionvalues=" + encodeURIComponent(descriptionvalues) + "&descriptionamountvalues=" + encodeURIComponent(descriptionamountvalues)+ "&offeramount=" + encodeURIComponent(offeramount)+ "&inclusivetaxamount=" + encodeURIComponent(inclusivetaxamount)+ "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val())+ "&paymentremarks=" + encodeURIComponent(paymentremarks)+ "&servicelist=" + encodeURIComponent(servicelist)+ "&serviceamountvalues=" + encodeURIComponent(serviceamountvalues)+ "&paymenttype=" + encodeURIComponent(paymenttype)+ "&lastslno=" + encodeURIComponent($('#lastslno').val())+ "&offerremarks=" + encodeURIComponent($('#offerremarkshidden').val()) + "&servicetaxamount=" + encodeURIComponent($('#sericetaxamount').val()) + "&selectedcookievalue=" + encodeURIComponent(productlist)+ "&paymenttypeselected=" + encodeURIComponent($("input[name='paymentmodeselect']:checked").val())+ "&paymentmode=" + encodeURIComponent($("input[name='paymentmode']:checked").val()) + "&chequedate=" + encodeURIComponent($('#DPC_chequedate').val()) + "&duedate=" + encodeURIComponent($('#DPC_duedate').val()) + "&chequeno=" + encodeURIComponent($('#chequeno').val()) + "&drawnon=" + encodeURIComponent($('#drawnon').val()) + "&depositdate=" + encodeURIComponent($('#DPC_depositdate').val())+ "&paymentamount=" + encodeURIComponent($('#paymentamount').val())+ "&dealerid=" + encodeURIComponent($('#dealeridhidden').val()) + "&cusname=" + encodeURIComponent($('#displaycompanyname').val())+ "&cuscontactperson=" + encodeURIComponent($('#displaycontactperson').val())+ "&cusaddress=" + encodeURIComponent($('#displayaddress').val())+ "&cusemail=" + encodeURIComponent($('#displayemail').val())+ "&cusphone=" + encodeURIComponent($('#displayphone').val())+ "&cuscell=" + encodeURIComponent($('#displaycell').val())+ "&custype=" + encodeURIComponent($('#displaytypeofcustomer').html())+ "&invoicedated=" + encodeURIComponent(invoicedated)+ "&cuscategory=" + encodeURIComponent($('#displaytypeofcategory').html()) + "&dummy=" + Math.floor(Math.random()*10054300000);
		
		var ajaxcall9 = createajax();
		queryString = "../ajax/invoicing.php";
		ajaxcall9.open("POST", queryString, true);
		ajaxcall9.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall9.onreadystatechange = function()
		{;
			if(ajaxcall9.readyState == 4)
			{
				if(ajaxcall9.status == 200)
				{
					var response = ajaxcall9.responseText.split('^');
					$('#proceedprocessingdiv').html('');
					if(response[0] == 1)
					{
						newproductentry();
						$('#onlineslno').val(response[2]);
						$("").colorbox({ inline:true, href:"#viewinvoicediv" , onLoad: function() {
    $('#cboxClose').show()}});
						generateinvoicedetails('');
					}
					else if(response[0] == 2)
					{
						$('#proceedprocessingdiv').html(errormessage(response[1]));
					}
					else if(response[0] == 3)
					{
						$('#onlineslno').val(response[2]);
						$("#submitform").attr("action", "http://imax.relyonsoft.com/user/makepayment/pay.php");
						$("#submitform").submit();	
					}
					else
					{
						$('#proceedprocessingdiv').html(errormessage(response[1]));
					}
				}
			}
		}
		ajaxcall9.send(passData);
	}
	else
		return false;
}


function customerdetailsremovereadonly()
{
	disablebuttontype();
	$("#displaycompanyname").removeClass("swifttext-readonly-border-h");
	$("#displaycompanyname").addClass("swifttext-mandatory");
	$("#displaycompanyname").removeAttr("readonly");
	
	$("#displayaddress").removeClass("swifttext-readonly-border");
	$("#displayaddress").addClass("swifttext-mandatory");
	$("#displayaddress").removeAttr("readonly"); 
	

	$("#displaycontactperson").removeClass("swifttext-readonly-border");
	$("#displaycontactperson").addClass("swifttext-mandatory");
	$("#displaycontactperson").removeAttr("readonly"); 
	
	$("#displayemail").removeClass("swifttext-readonly-border");
	$("#displayemail").addClass("swifttext-mandatory");
	$("#displayemail").removeAttr("readonly"); 
	
	$("#displayphone").removeClass("swifttext-readonly-border");
	$("#displayphone").addClass("swifttext-mandatory");
	$("#displayphone").removeAttr("readonly"); 
	
	$("#displaycell").removeClass("swifttext-readonly-border");
	$("#displaycell").addClass("swifttext-mandatory");
	$("#displaycell").removeAttr("readonly");

}

function customerdetailsmakereadonly()
{
	enablebuttontype();
	$("#displaycompanyname").removeClass("swifttext-mandatory");
	$("#displaycompanyname").addClass("swifttext-readonly-border-h");
	$("#displaycompanyname").attr("readonly","readonly"); 
	
	$("#displayaddress").removeClass("swifttext-mandatory");
	$("#displayaddress").addClass("swifttext-readonly-border");
	$("#displayaddress").attr("readonly","readonly"); 
	
	$("#displaycontactperson").removeClass("swifttext-mandatory");
	$("#displaycontactperson").addClass("swifttext-readonly-border");
	$("#displaycontactperson").attr("readonly","readonly"); 
	
	$("#displayemail").removeClass("swifttext-mandatory");
	$("#displayemail").addClass("swifttext-readonly-border");
	$("#displayemail").attr("readonly","readonly"); 
	
	$("#displayphone").removeClass("swifttext-mandatory");
	$("#displayphone").addClass("swifttext-readonly-border");
	$("#displayphone").attr("readonly","readonly"); 
	
	$("#displaycell").removeClass("swifttext-mandatory");
	$("#displaycell").addClass("swifttext-readonly-border");
	$("#displaycell").attr("readonly","readonly"); 
	
	
	$("#displaycompanyname").val($("#cusnamehidden").val());
	$("#displaycontactperson").val($("#contactpersonhidden").val());
	$("#displayemail").val($("#emailhidden").val());
	$("#displayphone").val($("#phonehidden").val());
	$("#displaycell").val($("#cellhidden").val());
	$("#displaytypeofcustomer").val($("#custypehidden").val());
	$("#displaytypeofcategory").val($("#cuscategoryhidden").val());
	$("#displayaddress").val($("#addresshidden").val());

}

function disablebuttontype()
{
	$('#edit').attr("disabled", true); 
	$('#edit').removeClass('swiftchoicebutton');	
	$('#edit').addClass('swiftchoicebuttondisabled');
	
	$('#cancelinvoice').attr("disabled", true); 
	$('#cancelinvoice').removeClass('swiftchoicebutton');	
	$('#cancelinvoice').addClass('swiftchoicebuttondisabled');
}

function enablebuttontype()
{
	$('#edit').attr("disabled", false); 
	$('#edit').removeClass('swiftchoicebuttondisabled');	
	$('#edit').addClass('swiftchoicebutton');
	
	$('#cancelinvoice').attr("disabled", false); 
	$('#cancelinvoice').removeClass('swiftchoicebuttondisabled');	
	$('#cancelinvoice').addClass('swiftchoicebutton');
	
}

function previewinvoice()
{
	var form = document.getElementById('submitform');
	var field = $("#displaycompanyname" );
	if(!field.val()) { alert("Enter the Business Name [Company]. "); field.focus(); return false; }
	if(field.val()) { if(!validatebusinessname(field.val())) { alert('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets. '); field.focus(); return false; } }
	var field = $("#displaycontactperson");
	if(!field.val()) { alert("Enter the Contact Person Name. "); field.focus(); return false; }
	if(field.val()) { if(!validatecontactperson(field.val())) {alert('Contact person name contains special characters. Please use only Numeric / space.'); field.focus(); return false; } }
	var field = $("#displayemail");
	if(!field.val()) { alert("Enter the Email Id. "); field.focus(); return false; }
	if(field.val()) { if(!emailvalidation(field.val())) { alert('Enter the valid Email Id.'); field.focus(); return false; } }
	var field = $("#displayphone");
	if(!field.val()) { alert("Enter the Phone No. "); field.focus(); return false; }
	if(field.val()) { if(!validatephone(field.val())) { alert('Enter the valid Phone Number.'); field.focus(); return false; } }
	var field = $("#displaycell");
	if(!field.val()) { alert("Enter the Cell No. "); field.focus(); return false; }
	if(field.val()) { if(!validatecell(field.val())) { alert('Enter the valid Cell Number.'); field.focus(); return false; } }
	//$('#form-error').html(getprocessingimage());
	var rowcount = $('#adddescriptionrows tr').length;
	var count = $('#seletedproductgrid tr').length;
	count = (count-2);
	var pricingtype = getradiovalue(form.pricing);
	var paymenttype = getradiovalue(form.modeofpayment);
	var inclusivetaxamount = document.getElementById('inclusivetaxamount').value;
	var offeramount = document.getElementById('offeramount').value;
	var field = $('#dealeridhidden');
	if(!field.val())
	{
		$('#form-error').html('');
		alert('Please Select a Dealer and click Go.');
		field.focus(); return false;
	}
	var purchasearrray = new Array();
	var purchasevalues = new Array();
	var usagearrray = new Array();
	var usagevalues = new Array();
	var productamountarrray = new Array();
	var productamountvalues = new Array();
	var productquantityarrray = new Array();
	var productquantityvalues = new Array();
	var serviceamountarrray = new Array();
	var serviceamountvalues = new Array();
	
	for(i=0,j=1; i<count; i++,j++)
	{	
		if($("#itemtype"+ j).val() == 'product')
		{
			purchasearrray[i] = 'purchasetypehidden' + j;
			purchasevalues[i] = document.getElementById(purchasearrray[i]).value;
			usagearrray[i] = 'usagetypehidden' + j;
			usagevalues[i] = document.getElementById(usagearrray[i]).value;
			productquantityarrray[i] = 'productquantity' + j;
			productquantityvalues[i] = document.getElementById(productquantityarrray[i]).value;
			productamountarrray[i] = 'productamount' + j;
			productamountvalues[i] = document.getElementById(productamountarrray[i]).value;
			var field = document.getElementById(productamountarrray[i]);
			if(!field.value) { 
				$('#form-error').html('');
				alert('Please Enter the amount');
				field.focus(); return false;}
			else if(field.value)	{ 
			if(!validateamountfield(field.value)) {
				$('#form-error').html('');
				alert('Amount is not Valid');
				field.focus(); return false; } }
			else
				return true;
		}
		else
		{
			if(serviceamountvalues == '')
				serviceamountvalues = serviceamountvalues + document.getElementById('productamount' + j).value;
			else
				serviceamountvalues = serviceamountvalues + '~' + document.getElementById('productamount' + j).value;
			var field =document.getElementById('productamount' + j);
			if(!field.value) { 
				$('#form-error').html('');
				alert('Please Enter the amount');
				field.focus(); return false;}
			else if(field.value)	{ 
			if(!validateamountfield(field.value)) {
				$('#form-error').html('');
				alert('Amount is not Valid');
				field.focus(); return false; } }
			else
				return true;

		}
	}
	var descriptiontypearrray = new Array();
	var descriptiontypevalues = new Array();
	var descriptionarrray = new Array();
	var descriptionvalues = new Array();
	var descriptionamountarrray = new Array();
	var descriptionamountvalues = new Array();
	for(i=0,j=0; i<rowcount,j<rowcount; i++,j++)
	{	//alert('here');
		if(document.getElementById('descriptiontype' + j).value != '')
		{
			if(descriptiontypearrray == '')
				descriptiontypearrray = descriptiontypearrray + 'descriptiontype' + j;
			else
				descriptiontypearrray = descriptiontypearrray + '~' + 'descriptiontype' + j;
			if(descriptiontypevalues == '')
				descriptiontypevalues = descriptiontypevalues + document.getElementById('descriptiontype' + j).value;
			else
				descriptiontypevalues = descriptiontypevalues + '~' + document.getElementById('descriptiontype' + j).value;
				
			if(descriptionarrray == '')
				descriptionarrray = descriptionarrray + 'description' + j;
			else
				descriptionarrray = descriptionarrray + '~' + 'description' + j;
			
			if(descriptionvalues == '')
				descriptionvalues = descriptionvalues + document.getElementById('description' + j).value;
			else
				descriptionvalues = descriptionvalues + '~' + document.getElementById('description' + j).value;
				
			if(descriptionamountarrray == '')
				descriptionamountarrray = descriptionamountarrray + 'descriptionamount' + j;
			else
				descriptionamountarrray = descriptionamountarrray + '~' + 'descriptionamount' + j;;
			
			if(descriptionamountvalues == '')
				descriptionamountvalues =descriptionamountvalues + document.getElementById('descriptionamount' + j).value;
			else
				descriptionamountvalues = descriptionamountvalues + '~' + document.getElementById('descriptionamount' + j).value;
		}
	}
	if(descriptiontypearrray != '')
	{
		descriptiontypesplit = descriptiontypearrray.split('~');
		descriptiontypevaluesplit = descriptiontypevalues.split('~');
		descriptionarrraysplit = descriptionarrray.split('~');
		descriptionvaluessplit = descriptionvalues.split('~');
		descriptionamountarrraysplit = descriptionamountarrray.split('~');
		descriptionamountvaluessplit = descriptionamountvalues.split('~');
		for(var k=0;k<descriptiontypesplit.length;k++)
		{
			if(descriptiontypevaluesplit[k] != '')
			{
				var field = document.getElementById(descriptionarrraysplit[k]);
				if(!field.value) {
					$('#form-error').html(''); 
					alert('Please Enter the Description...')
					field.focus();  return false;}
				var field = document.getElementById(descriptionamountarrraysplit[k]);
				if(!field.value) {
					$('#form-error').html(''); 
					alert('Please Enter the amount');
					field.focus();  return false;}
				if(field.value)	{ if(!validateamountfield(field.value)) {
					$('#form-error').html('');  
					alert('Amount is not Valid.');
					field.focus(); return false; } }
			}
		}
	}
	if(pricingtype == 'offer')
	{
		var field = $('#offeramount');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the offer amount.');
			field.focus(); return false;
		}
		if(field.val())	{ if(!validateamountfield(field.val())) {
			$('#form-error').html('');
			alert('Amount is not Valid.')
			field.focus(); return false; } }
	}
	else if(pricingtype == 'inclusivetax')
	{
		var field = $('#inclusivetaxamount');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the amount.');
			field.focus(); return false;
		}
		if(field.val())	{ if(!validateamountfield(field.val())) {
			$('#form-error').html('');
			alert('Amount is not Valid.');
			field.focus(); return false; } }
	}
	if(paymenttype == 'cheque/dd/neft')
	{
		var field = $('#paymentremarks');
		if(!field.val())
		{
			$('#form-error').html('');
			alert('Please Enter the Payment Remarks.');
			field.focus(); return false;
		}
	}
		//disableproceedbutton();
	var productarray=document.getElementsByName("productselectedhidden[]");
	var productvalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productvalues += productarray[i].value  + '#';
	}
	var productlist = productvalues.substring(0,(productvalues.length-1));//alert(productamountvalues);
	var productnamearray=document.getElementsByName("productnamehidden[]");
	var productnamevalues = '';
	for(i=0;i<productarray.length;i++)
	{
		productnamevalues += productnamearray[i].value  + '#';
	}
	var productnamelist = productnamevalues.substring(0,(productnamevalues.length-1));//alert(productamountvalues);
	var servicearray=document.getElementsByName("serviceselectedhidden[]");
	var servicevalues = '';
	for(i=0;i<servicearray.length;i++)
	{
		servicevalues += servicearray[i].value  + '#';
	}
	var servicelist = servicevalues.substring(0,(servicevalues.length-1));
//	return false;
	var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
	if(paymentmodeselect == 'paymentmadelater')
		var paymentremarks = $('#remarks').val();
	else
		var paymentremarks = $('#paymentremarks').val();
		
	if(paymenttype == 'credit/debit')
	{
		disableproceedbutton();
	}
	else
	{
		if($("input[name='paymentmode']:checked").val() == 'chequeordd' && paymentmodeselect == 'paymentmadenow')
		{
			var field = $('#DPC_chequedate');
			if(!field.val()) {$('#form-error').html(''); alert('Please enter the Cheque Date'); field.focus(); return false; }
			var field = $('#chequeno');
			if(!field.val()) { $('#form-error').html(''); alert('Please enter the Cheque No'); field.focus(); return false; }
			if(field.val()){ if(!validateamountfield(field.val())) { $('#form-error').html(''); alert('Cheque No is not Valid.'); field.focus(); return false; }}
			var field = $('#drawnon');
			if(!field.val()) { $('#form-error').html(''); alert('Please enter the Drawn On'); field.focus(); return false; }
		}
	}
	var field = $('#paymentamount');
	if(field.val() == '')
	{
		$('#form-error').html(''); alert('Please enter the Payment amount'); field.focus(); return false;
	}
	$("#previewproductgrid tr").remove();
	$("#customeridpreview").html($("#displaycustomerid").html());
	$("#companynamepreview").html($("#displaycompanyname").val());
	var contactperson = $("#displaycontactperson").val().split(',');
	$("#contactpersonpreview").html(contactperson[0]);
	$("#addresspreview").html($("#displayaddress").val());
	var emailid = $("#displayemail").val().split(',');
	$("#emailpreview").html(emailid[0]);
	var phone = $("#displayphone").val().split(',');
	$("#phonepreview").html(phone[0]);
	var cell = $("#displaycell").val().split(',');
	$("#cellpreview").html(cell[0]);
	$('#custypepreview').html($("#displaytypeofcustomer").html());
	$('#cuscategorypreview').html($("#displaytypeofcategory").html());
	$('#marketingexepreview').html($("#displaymarketingexe").html());
	var field = $('#invoicedated:checked').val();
	if(field != 'on') var invoicedated = 'no'; else invoicedated = 'yes';
	var currentTime = new Date();
	var month = currentTime.getMonth() + 1;
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	var currentdate = day + "-" + month + "-" + year; 
	if(currentdate == '4-4-2011')
		$('#invoicedatepreview').html(' 31-03-2011 (23:55)');
	else
	{
		if(hours < 10)
			hours = "0" + hours;
		if(minutes < 10)
			minutes = "0" + minutes;
		$('#invoicedatepreview').html(" " +day + "-" + month + "-" + year + " (" + hours + ":" + minutes + ")");
	}
	
	
	k=0;
	var productgridheader = '<tr bgcolor="#cccccc" ><td width="9%" nowrap="nowrap" style="text-align: left;" class="grey-td-border-grid">Sl No</td><td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">Description</td><td width="19%" nowrap="nowrap" style="text-align: left;" class="grey-td-border-grid">Amount</td></tr>';
	productgridrow = '';
	if(productlist != '')
	{
		var productcount = productlist.split('#');
		var productnames = productnamelist.split('#');
		var productgridrow = '';
		var productgrid = ''
		var productamountvalues = removedoublecomma(productamountvalues);
		for(i=0;i<productcount.length;i++)
		{
			k++; 
			if(purchasevalues[i]  == 'new')
				var purchasetype = 'New';
			else if(purchasevalues[i]  == 'updation')
				var purchasetype = 'Updation';
			else
				var purchasetype = 'Convertion';
				
			if(usagevalues[i]  == 'singleuser')
				var usagetype = 'Singleuser';
			else if(usagevalues[i]  == 'multiuser')
				var usagetype = 'Multiuser';
			else
				var usagetype = 'Add license';
			var productgrid = '<tr ><td class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productnames[i] +'</font> <br/> Purchase Type: <font color="#FF0000">'+purchasetype+'</font> / Usage Type: <font color="#FF0000">'+usagetype+'</font>  </td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(productamountvalues[i]) +'</td> </tr>';
			var productgridrow = productgridrow + productgrid ;
		}
	}
	if(servicelist != '')
	{
		var servicelistcount = servicelist.split('#');
		var serviceamountvaluessplit = serviceamountvalues.split('~');
		for(i=0;i<servicelistcount.length;i++)
		{
			k++; 
			var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ servicelistcount[i] +'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(serviceamountvaluessplit[i]) +'</td> </tr>';
			productgridrow = productgridrow+productgrid;
		}
	}
	if(descriptiontypevalues != '')
	{
		var descriptiontypevaluessplit = descriptiontypevalues.split('~');
		var descriptionvaluessplit = descriptionvalues.split('~');
		var descriptionamountvaluessplit = descriptionamountvalues.split('~');
		for(i=0;i<descriptiontypevaluessplit.length;i++)
		{
			k++;
			var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">' + k +'</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ descriptiontypevaluessplit[i] +': '+descriptionvaluessplit[i]+'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">'+ intToFormat(descriptionamountvaluessplit[i]) +'</td> </tr>';
			productgridrow = productgridrow+productgrid;
		}
	}
	var offerremarks= $('#offerremarkshidden').val();
	if(offerremarks != '')
	{
		var productgrid = '<tr><td width="9%" class="grey-td-border-grid" style="">&nbsp;</td> <td colspan="2" nowrap="nowrap" class="grey-td-border-grid" style="text-align: left;">'+ offerremarks +'</td><td width="19%" nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid">&nbsp;</td> </tr>';
		productgridrow = productgridrow+productgrid;
	}
	var emptyrow = '<td colspan="3" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';
	var amountgrid = '<tr><td colspan="2" class="grey-td-border-grid">&nbsp;</td><td valign="top" class="grey-td-border-grid"  width="12%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" valign="top" ><span id="netamountpreview"></span></td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none">&nbsp;</td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">Service Tax</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="servicetaxpreview">&nbsp;</td></tr><tr><td colspan="2" class="grey-td-border-grid"  style="border-right:none">&nbsp;</td><td valign="top" class="grey-td-border-grid" style="border-left:none"><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;" class="grey-td-border-grid" id="totalamountpreview">&nbsp;</td></tr><tr><td colspan="4" class="grey-td-border-grid" style="border-right:n">Rupee In Words: <span id="rupeeinwordspreview"></span></td> </tr>';
	$("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
	$("#netamountpreview").html(intToFormat($("#totalamount").val()));
	$("#servicetaxpreview").html(intToFormat($("#sericetaxamount").val()));
	$("#totalamountpreview").html(intToFormat($("#netamount").val()));
	$("#rupeeinwordspreview").html(NumbertoWords($("#netamount").val()));
	//$("#generatedbypreview").html($("#displaymarketingexe").html());
	var invoiceremarks = $("#invoiceremarks").val();
	if(invoiceremarks == '')
		invoiceremarks = 'None';
	$("#invoiceremarkspreview").html(invoiceremarks);
	$("#proceedprocessingdiv").html('');
	getpaymentremarks();
	enableproceedbutton();
	$("").colorbox({ inline:true, href:"#invoicepreviewdiv" , onLoad: function() {
    $('#cboxClose').hide()}});
}

function getpaymentremarks()
{
	var form = document.getElementById('submitform');
	var paymenttype = getradiovalue(form.modeofpayment);
	var chequedate = $("#DPC_chequedate").val();
	var chequeno = $("#chequeno").val();
	var drawnon = $("#drawnon").val();
	var paymentamount = $("#paymentamount").val();
	var duedate = $("#DPC_duedate").val();
	var paymentremarks;
	if(paymenttype == 'credit/debit')
		paymentremarks = 'Selected for Credit/Debit Card Payment. This is subject to successful transaction';
	else
	{
		var paymentmodeselect = $("input[name='paymentmodeselect']:checked").val();
		if(paymentmodeselect == 'paymentmadelater')
			var paymentremarks = 'Payment Due!! (Due Date: '+ duedate + ') ' + $("#remarks").val();
		else
		{
			var paymentmode = $("input[name='paymentmode']:checked").val();
			if(paymentmode == 'chequeordd')
				paymentremarks = 'Received Cheque No: '+ chequeno+', dated '+chequedate+', drawn on '+drawnon+', for amount '+paymentamount+'. Cheques received are subject to realization.';
			else if(paymentmode == 'cash')
				paymentremarks = $("#paymentremarks").val();
			else
				paymentremarks = 'Payment through Online Transfer. '+$("#paymentremarks").val()+'';
		}
	}
	$("#paymentremarkspreview").html(paymentremarks);
}

function cancelpurchase()
{
	$('#form-error').html('');
	$().colorbox.close();
}

function getcarddetails(startlimit,rowid)
{
	if($('#lastslno').val() == '')
	{
		$('#carddetailsgridwb1').html("");
		$('#carddetailsgridc1_1').html('<table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;"><tr class="tr-grid-header"><td nowrap = "nowrap" class="td-border-grid" align="left">&nbsp;</td><td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td><td nowrap = "nowrap" class="td-border-grid" align="left">Product Name</td><td nowrap = "nowrap" class="td-border-grid" align="left">Card Id</td><td nowrap = "nowrap" class="td-border-grid" align="left">Scratch Number</td><td nowrap = "nowrap" class="td-border-grid" align="left">Date<input type="hidden" name="cardslno" id="cardslno" /></td></tr><tr><td colspan="6" valign="top" class="td-border-grid"><div align="center">No datas found to display</div></td></tr></table>');
		$('#carddetailsgridc1link').html("");
		return false;
	}
	else
	{
		var form = $('#submitform');
		$('#carddetailsgridc1').show();
		$('#rowid').val(rowid);
		//$('#detailsdiv').hide();
		var passData = "switchtype=getcarddetails&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + encodeURIComponent(startlimit);
		var queryString = "../ajax/invoicing.php";
		ajaxcall41 = createajax();
		$('#carddetailsgridc1_1').html(getprocessingimage());
		$('#carddetailsgridc1link').html('');
		ajaxcall41.open("POST", queryString, true);
		ajaxcall41.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajaxcall41.onreadystatechange = function()
		{
			if(ajaxcall41.readyState == 4)
			{
				if(ajaxcall41.status == 200)
					{
						var ajaxresponse = ajaxcall41.responseText;
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
								$('#carddetailsgridwb1').html("Total Count :  " + response[2]);
								$('#carddetailsgridc1_1').html(response[1]);
								$('#carddetailsgridc1link').html(response[3]);
								$("").colorbox({ inline:true, href:"#cardpreviewdiv" , onLoad: function() {
    $('#cboxClose').hide()}});
								//enableproceedbutton();
							}
							else
							{
								$('#carddetailsgridc1_1').html("No datas found to be displayed");
							}
						}
						
					}
				else
					$('#carddetailsgridc1_1').html(scripterror());
			}
		}
		ajaxcall41.send(passData);
	}
}

//Function for "show more records" link - to get registration records
function getmorecarddetails(id,startlimit,slno,showtype)
{
	var form = $('#submitform');
	$('#lastslno').val(id);
	var passData = "switchtype=getcarddetails&lastslno="+ encodeURIComponent($('#lastslno').val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/invoicing.php";
	ajaxcall51 = createajax();
	$('#carddetailsgridc1link').html(getprocessingimage());
	ajaxcall51.open("POST", queryString, true);
	ajaxcall51.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall51.onreadystatechange = function()
	{
		if(ajaxcall51.readyState == 4)
		{
			if(ajaxcall51.status == 200)
			{
				var ajaxresponse = ajaxcall51.responseText;
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
						$('#carddetailsgridwb1').html("Total Count :  " + response[2]);
						$('#carddetailsresultgrid').html($('#invoicedetailsgridc1_1').html());
						$('#carddetailsgridc1_1').html($('#invoicedetailsresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
						$('#carddetailsgridc1link').html(response[3]);
					}
					else
					{
						$('#carddetailsgridc1_1').html("No datas found to be displayed");
					}
				}
			}
			else
				$('#carddetailsgridc1_1').html(scripterror());
		}
	}
	ajaxcall51.send(passData);
}


function selectconvertioncard()
{
	var form = document.getElementById('cardselectionform');
	var pinslno = getradiovalue(form.selectcard); alert(pinslno);
	if(pinslno == undefined)
	{
		alert('Please select a card for convertion'); return false;
	}
	var rowid = $('#rowid').val();
	$('#cardslnohidden'+ rowid).val(pinslno); alert($('#cardslnohidden'+ rowid).val());
	$.colorbox.close();
}

function cancelconvertioncard()
{
	$('#cardlastslno').val('');
	$.colorbox.close();
}