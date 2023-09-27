var totalarray = new Array();
var customerarray = new Array();
var customerarray1 = new Array();
var customerarray2 = new Array();
var customerarray3 = new Array();
var customerarray4 = new Array();

var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;
var checksection;

var invoicearray = new Array();
var rowcountvalue = 0;
var invrequestno = '';
var getMessage = '';

//Get the customer list
function gettotalcustomercount()
{
	var form = $('#customerselectionprocess');
	var passData = "switchtype=getcustomercount&dummy=" + Math.floor(Math.random()*10054300000);
	queryString = "../ajax/matrixinvoicing.php";
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
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				$("#totalcount").html(response['count']);
				refreshcustomerarray(response['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
}

function refreshcustomerarray(customercount)
{
	var form = $('#customerselectionprocess');
	var totalcustomercount = customercount;
	var limit = Math.round(totalcustomercount/4);
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=generatecustomerlist&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	$('#customerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/matrixinvoicing.php";
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
				var response = ajaxresponse;
				for( var i=0; i<response.length; i++)
				{
					customerarray1[i] = response[i];
				}
				process1 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/matrixinvoicing.php";
	ajaxcall3 = $.ajax(
	{
		type: "POST",url: queryString, data: passData1, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray2[i] = response[i];
				}
				process2 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

	queryString = "../ajax/matrixinvoicing.php";
	ajaxcall4 = $.ajax(
	{
		type: "POST",url: queryString, data: passData2, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray3[i] = response[i];
				}
				process3 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	
	
	queryString = "../ajax/matrixinvoicing.php";
	ajaxcall5 = $.ajax(
	{
		type: "POST",url: queryString, data: passData3, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;//alert(response)
				for( var i=0; i<response.length; i++)
				{
					customerarray4[i] = response[i];
				}
				process4 = true;
				compilecustomerarray();
			}
		}, 
		error: function(a,b)
		{
			$("#customerselectionprocess").html(scripterror());
		}
	});	

}

function compilecustomerarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		customerarray = customerarray1.concat(customerarray2.concat(customerarray3.concat(customerarray4)));
		flag = true;
		getcustomerlist1();
		$('#customerselectionprocess').html(successsearchmessage('All Customers...'));
	}
	else
	return false;
}
//Function to add all customers to select box
function getcustomerlist1()
{	
	//disableformelemnts_invoicing();
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

function selectfromlist()
{
	var selectbox = document.getElementById('customerlist');
	var cusnamesearch = document.getElementById('detailsearchtext');
	cusnamesearch.value = selectbox.options[selectbox.selectedIndex].text;
	cusnamesearch.select();
    customerdetailstoform(selectbox.value);
    $('#displaycustomername').html($("#customerlist option:selected").text());
    $('#lastslno').val($("#customerlist option:selected").text());
}

function customerdetailstoform(cusid)
{
    //alert(cusid);
    var form = $("#submitform");
	removerows();
    $("#submitform")[0].reset();
    var error = $("#form-error");
    if(cusid!='')
    {
        var passdata = "switchtype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
        var queryString = "../ajax/matrixinvoicing.php";
        error.html('<img src="../images/imax-loading-image.gif" border="0" />');
		ajaxobjext12 = $.ajax(
		{
			type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
			success: function(ajaxresponse,status)
			{	
				var response = ajaxresponse;
				
				//console.log(response);
				if(ajaxresponse == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else if(response['errorcode'] == '1')
				{
                    error.html('');
					$('#lastslno').val(response['slno']);
                    //alert(response['gst_no']);
                    if(response['gst_no'])
                    {
                        $('#displaycustomergst').val(response['gst_no']);
                    }
                    else
                    {
                        $('#displaycustomergst').val('Not Registered Under GST');
                    }
					if($('#address').val()!='')
							$('#address').val('');
					
					$('#displaycustomerid').val(response['customerid']);
					$('#displaycompanyname').val(response['companyname']);
					$('#displaycontactperson').val(response['contactvalues']);
					$('#displayaddress').val(response['address']);
					$('#displayphone').val(response['phone']);
					$('#displaycell').val(response['cell']);
					$('#displayemail').val(response['emailidplit']);
					if(response['businesstype'] == null)
						$('#displaytypeofcategory').val('Not Available');
					else
						$('#displaytypeofcategory').val(response['businesstype']);
					if(response['customertype'] == null)
						$('#displaytypeofcustomer').val('Not Available');
					else
						$('#displaytypeofcustomer').val(response['customertype']);
					$('#state_gst_code').val(response['state_gst_code']);
					
					approverejectrequest(response['slno']);
					generateinvoicedetails(response['slno']);
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
    }
}

//functin to search customer
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

//function to search customer using arrow keys
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

//Function to select a customer from the list
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
				var result1 = pattern.test(trimdotspaces(customerarray[i]).toLowerCase());
				var result2 = pattern.test(customerarray[i].toLowerCase());
				if(result1 || result2)
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

function editinvoice(requestid)
{
	var editinvreqno =  $('#editinvreqno').val(requestid);
	var slno = 1;
	var error = $("#form-error");
    var passData = "switchtype=editinvoice&requestid=" + encodeURIComponent(requestid)  + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData);
	var queryString = "../ajax/matrixinvoicing.php";
	error.html('<img src="../images/imax-loading-image.gif" border="0" />');
	ajaxobjext12 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(response,status)
		{
			if(response['gst_no'])
			{
				$('#displaycustomergst').val(response['gst_no']);
			}
			else
			{
				$('#displaycustomergst').val('Not Registered Under GST');
			}
			
			$('#displaycustomerid').val(response['customerid']);
			$('#displaycompanyname').val(response['businessname']);
			$('#displaycontactperson').val(response['contactperson']);
			$('#displayaddress').val(response['address']);
			$('#address').val(response['address1']);
			$('#displayphone').val(response['phone']);
			$('#displaycell').val(response['cell']);
			$('#displayemail').val(response['emailid']);
			if(response['customercategory'] == null)
				$('#displaytypeofcategory').val('Not Available');
			else
				$('#displaytypeofcategory').val(response['customercategory']);
			if(response['customertype'] == null)
				$('#displaytypeofcustomer').val('Not Available');
			else
				$('#displaytypeofcustomer').val(response['customertype']);
			$('#DPC_startdate').val(response['podate']);
			$('#poreference').val(response['poreference']);
			$('#salesperson').val(response['dealerid']);
			$('#invoiceremarks').val(response['invoiceremarks']);
			$('#paymentamount').val(response['paymentamount']);
			$('#paymentremarks').val(response['remarks']);

			$("#seletedproductgrid").find("tr:gt(0)").remove();
			$('#igst_tax_amount').val(response['igst']);
			$('#cgst_tax_amount').val(response['cgst']);
			$('#sgst_tax_amount').val(response['sgst']);
			$('#branchhidden').val(response['state_info']);
			

			var totalamount = response['totalamount'].split('*');
			var actualamount = response['actualamount'].split('*');
			var description = response['description'].split('*');
			var products = response['products'].split('#');
			
			var  table = document.getElementById('seletedproductgrid'),tbody = table.getElementsByTagName('tbody')[0];
			var quantity = response['productquantity'].split(',');
			//alert(quantity.length);
			
			for(k=0;k<description.length;k++)
			{
				//alert(description.length);
				var descriptionsplit = description[k].split('$');
				
				if(k==0)
				{
					var row = tbody.rows[0];
					var i = tbody.rows.length;
					row.cells[6].id = 'textboxDiv' + i ;
				}
				else
				{
					var row = tbody.rows[0].cloneNode(true);
					var i = ++tbody.rows.length;
					row.cells[6].id = 'textboxDiv' + i ;
					
				}
				
				row.cells[0].innerHTML = i;
				var inp0 = row.cells[1].getElementsByTagName('select')[0];
				var inp1 = row.cells[2].getElementsByTagName('select')[0];
				var inp2 = row.cells[3].getElementsByTagName('input')[0];
				var inp3 = row.cells[4].getElementsByTagName('input')[0];
				var inp4 = row.cells[5].getElementsByTagName('input')[0];
				var inp5 = row.cells[7].getElementsByTagName('input')[0];
				
				inp0.id = 'purchasetype' + i;
				inp1.id = 'producttype' + i;
				inp2.id = 'quantity' + i;
				inp3.id = 'unitamt' + i;
				inp4.id = 'invamount' + i;
				inp5.id = 'productnamehidden' + i;
				//alert(descriptionsplit[1]);
				inp0.value = descriptionsplit[2];
				inp1.value = products[k];
				inp2.value = quantity[k];
				inp3.value = actualamount[k];
				inp4.value = totalamount[k];
				inp5.value = descriptionsplit[1]+'#'+products[k];
				var serialnosplit = descriptionsplit[3].split('/');
				var addelement = "";
				for(l=1;l<=quantity[k];l++)
				{
					//alert(slno);
					//console.log(serialnosplit[j]);
					 addelement += '<input name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+l+'" value="'+serialnosplit[l-1]+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />';
					//console.log(addelement)
					
					slno++;
				}
				row.cells[6].innerHTML = addelement;
				tbody.appendChild(row);
				error.html('');
			}
		}
	});
}

function additemgrid()
{
    var field = $('#displaycompanyname');
	if(field.val() == "")
	{
		alert("Pleast select Customer first.");
		return false;
	}
	var  table = document.getElementById('seletedproductgrid'),tbody = table.getElementsByTagName('tbody')[0],
    clone = tbody.rows[0].cloneNode(true);
	var gridlength = ++tbody.rows.length;
	if(gridlength == 11)
	{
		alert("Only 10 rows are allowed.");
		return false;
	}
    $('#productgrid').val(gridlength);
	var new_row = updateRow(clone.cloneNode(true), gridlength, true);
    tbody.appendChild(new_row);
    $("#textboxDiv"+gridlength).html('');
    $("#invamount"+gridlength).val('');
    //$('#productsubtype'+gridlength).find('option').not(':first').remove();
    
}

function updateRow(row, i, reset) 
{
    row.cells[7].id = 'textboxDiv' + i ;
	row.cells[0].innerHTML = i;
	var inp0 = row.cells[1].getElementsByTagName('select')[0];
	var inp1 = row.cells[2].getElementsByTagName('select')[0];
	var inp2 = row.cells[3].getElementsByTagName('input')[0];
	var inp3 = row.cells[4].getElementsByTagName('input')[0];
	var inp4 = row.cells[5].getElementsByTagName('input')[0];
	var inp6 = row.cells[6].getElementsByTagName('input')[0];
	var inp5 = row.cells[8].getElementsByTagName('input')[0];
	
    
	inp0.id = 'purchasetype' + i;
	inp1.id = 'producttype' + i;
	inp2.id = 'quantity' + i;
	inp3.id = 'unitamt' + i;
	inp4.id = 'invamount' + i;
	inp6.id = 'srlqty' + i;
	inp5.id = 'productnamehidden' + i;
	
    //alert(inp0.id);
	
  if (reset) {
    inp1.value = inp2.value = inp3.value = inp4.value = inp0.value = inp5.value = inp6.value = '';
  }
  //console.log(row);
  return row;
}

function removegrid(el)
{
	var table = document.getElementById('seletedproductgrid');
	var tbodyRowCount = table.getElementsByTagName('tbody')[0].rows.length;
	var i = el.parentNode.parentNode.parentNode.rowIndex;
	//alert(i);
	if(tbodyRowCount > 1)
	{
		table.deleteRow(i);
		k=0;
		for (row = 1; row <=table.rows.length; row++) 
		{
			table.rows[k].cells[0].innerHTML = row;
			table.rows[k].cells[1].getElementsByTagName('select')[0].id = 'purchasetype' + row;
			table.rows[k].cells[2].getElementsByTagName('select')[0].id = 'producttype' + row;
			table.rows[k].cells[3].getElementsByTagName('input')[0].id = 'quantity' + row;
			table.rows[k].cells[4].getElementsByTagName('input')[0].id = 'unitamt' + row;
			table.rows[k].cells[5].getElementsByTagName('input')[0].id = 'invamount' + row;
			table.rows[k].cells[6].getElementsByTagName('input')[0].id = 'srlqty' + row;
            table.rows[k].cells[7].id = 'textboxDiv' + row;
			table.rows[k].cells[8].getElementsByTagName('input')[0].id = 'productnamehidden' + row;

			k++;
		}
        $('#productgrid').val(table.rows.length);
	}
	else
	{
		alert("Minimum of ONE Row required to raise bill.");
		return false;
	}
}


function getSerialDiv(el)
{
	alert("hi");
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
    var srlVal = el.value;
    var srlid = el.id;
    $("#textboxDiv"+getid).html('');
    // $("#unitamt"+getid).val('');
    // $("#invamount"+getid).val('');
	var producttype = $("#producttype"+getid).val();
	var quantity = $("#srlqty"+getid).val();
	for(var i=1;i<=quantity;i++)
	{
		if($("#srlqty"+i).val() == 0)
		{
			$("#textboxDiv"+getid).append('<input name="fromsrlno[]" type="hidden" class="swifttext-mandatory" id="fromsrlno'+i+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />' );
		}
		else{
			$("#textboxDiv"+getid).append('<input name="fromsrlno[]" class="swifttext-mandatory" id="fromsrlno'+i+'" placeholder="Enter Serial No" size="18" style="margin: 1px;" />' );
		}
		
	}
}

function gettotalamount(el)
{
   var error = $("#form-error" );
    var emptyFields = false;
    $('input[name^="unitamt"]').each(function() {
        if(!(/^[0-9]*$/.test($(this).val())))
        {
            $(this).focus();
            $(this).val('');
            emptyFields = true;
        }
    });

    if (emptyFields) {
        alert('Digits only allowed!');
        //error.html(errormessage('Unit Amount must be digits'));
        return false;
    }
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
    var unitamt = el.value;
    var quantity = $("#quantity"+getid).val();
    var invamount = quantity*unitamt;
    $("#invamount"+getid).val(invamount);
}

function getproductname(el)
{
    var table = document.getElementById('seletedproductgrid');
	var trid = el.parentNode.parentNode.rowIndex;
    var getid = trid+1;
	var producttype = $("#producttype"+getid).val();
    //alert(getid);
    var productname = $("#producttype"+getid+" option:selected").text();
    $("#productnamehidden"+getid).val(productname+'#'+producttype);
	$("#quantity"+getid).val('');
}

function previewinvoice(invrequestno)
{
	var previewbutton = '';
	$("#previewproductgrid tr").remove();
	$("#previewbuttons td").remove();

    previewbutton = '<td  valign="top" style="text-align: right; padding-top:10px;"  ><div align="center"><input name="approve" type="button" class= "swiftchoicebutton" id="approve" value="Approve"  onclick="this.disabled=true;proceedforpurchase(\'' + invrequestno + '\');disableproceedbutton();"/>&nbsp;&nbsp;<input name="reject" type="button" class= "swiftchoicebutton" id="reject" value="Reject"  onclick="this.disabled=true;rejectpurchase(\'' + invrequestno + '\');disableproceedbutton();"/>&nbsp;&nbsp;<input name="cancel" type="button" class= "swiftchoicebutton" id="cancel" value="Cancel"  onclick="cancelpurchase();"/></div</td>';    
    //alert($('#tdsdeclarartion').val());
    $("#proceedprocessingdiv").html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=getpreviewdetails&invrequestno=" + encodeURIComponent(invrequestno) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&cgstrate=" + encodeURIComponent($('#cgstrate').val()) + "&sgstrate=" + encodeURIComponent($('#sgstrate').val()) + "&igstrate=" + encodeURIComponent($('#igstrate').val())  +  "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
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
                //alert(response['grid']);
                $('#proceedprocessingdiv').html('');
				$("#gstnidpreview").html(response['customer_gstno']);
				$("#customeridpreview").html(response['customerid']);
				$("#companynamepreview").html(response['businessname']);
				$("#contactpersonpreview").html(response['contactperson']);
				$("#addresspreview").html(response['address']);
				$("#emailpreview").html(response['emailid']);
				$("#phonepreview").html(response['phone']);
				$("#cellpreview").html(response['cell']);
				$('#custypepreview').html(response['customertype']);
				$('#cuscategorypreview').html(response['customercategory']);
				$('#podatepreview').html(response['podate']);
				$('#poreferencepreview').html(response['poreference']);
				$('#marketingexepreview').html(response['dealername']);
				$('#branchgstinpreview').html(response['branch_gstin']);

			    $("#previewproductgrid").append(response['grid']) ;
                $("#previewbuttons").append(previewbutton) ;
                $("#invoiceremarkspreview").html(response['invoiceremarks']);
                $("#paymentremarkspreview").html(response['paymentremarks']);
				enableproceedbutton();
                $("").colorbox({ inline:true, href:"#invoicepreviewdiv" , onLoad: function() { $('#cboxClose').hide()}});
            }
        }, 
        error: function(a,b)
        {
            $("#proceedprocessingdiv").html(scripterror());
        }
    });    
}

function previewuserinvoice()
{
    var error = $("#form-error");
    var field = $('#displaycompanyname');
	if(field.val() == "")
	{
		alert("Pleast select Customer first.");
		return false;
	}

	var field = $('#salesperson');
    if(!field.val()){ error.html(errormessage('Please select dealer.')); field.focus(); return false;}

    $('select[name^="purchasetype"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please select Purchase type.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('select[name^="producttype"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please select Product type.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('input[name^="quantity"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });


    if (emptyFields) {
        error.html(errormessage('Please select Quantity.'));
        //$(this).focus();
        return false;
     }

     var emptyFields = false;
     $('input[name^="unitamt"]').each(function() {
        if($(this).val()=="")
        {
            $(this).focus();
            emptyFields = true;
        }
    });

    if (emptyFields) {
        error.html(errormessage('Please Enter Unit Amount.'));
        //$(this).focus();
        return false;
     }

    //  var emptyFields = false;
    //  $('input[name^="fromsrlno"]').each(function() {
    //     if($(this).val()=="")
    //     {
    //         $(this).focus();
    //         emptyFields = true;
    //     }
    // });

    // if (emptyFields) {
    //     error.html(errormessage('Please Enter Serial No.'));
    //     //$(this).focus();
    //     return false;
    //  }

    //  var emptyFields1 = false;
    // $('input[name^="tosrlno"]').each(function() {
    //     if($(this).val()=="")
    //     {
    //         $(this).focus();
    //         emptyFields1 = true;
    //     }
    // });

    // if (emptyFields1) {
    //     error.html(errormessage('Please Enter Serial No.'));
    //     //$(this).focus();
    //     return false;
    // }

   var field = $('#paymentamount');
    //alert(paymentamt);
    if(!field.val()){ $('#paymentamount').val('0'); error.html(errormessage('Payment amount must be zero if receipt is not required.')); field.focus(); return false;}
    else if(!(/^[0-9]*$/.test(field.val())))
    {
        $('#paymentamount').val('0');
        error.html(errormessage('Payment amount can only be digits.'));
        field.focus(); return false;
    }

    var field = $('#paymentremarks');
    if(!field.val()){ error.html(errormessage('Please Enter Payment remarks.')); field.focus(); return false;}

    var total_product_price = 0;
    var igst_tax_amount = 0;
	var cgst_tax_amount = 0;
	var sgst_tax_amount = 0;
    var netamount = 0;
    if($('#displaycustomergst').val() != '')
	{
	    $("#gstnidpreview").html($('#displaycustomergst').val());
	}
	else
	{
	    $("#gstnidpreview").html('Not Registered Under GST');
	}
    $("#customeridpreview").html($("#displaycustomerid").val());
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
	$('#custypepreview').html($("#displaytypeofcustomer").val());
	$('#cuscategorypreview').html($("#displaytypeofcategory").val());
	if($("#DPC_startdate").val() != '')
		$('#podatepreview').html($("#DPC_startdate").val());
	else
		$('#podatepreview').html('Not Avaliable');
	if($("#poreference").val() != '')
		$('#poreferencepreview').html($("#poreference").val());
	else
		$('#poreferencepreview').html('Not Avaliable');
    
   $('#marketingexepreview').html($("#salesperson option:selected").text());
   $('#branchgstinpreview').html($("#branch_gstin").val());

    var PurchaseType = document.getElementsByName("purchasetype[]");
	var productnamehidden = document.getElementsByName("productnamehidden[]");
	var productType = document.getElementsByName("producttype[]");
	var quantity = document.getElementsByName("quantity[]");
	var unitAmt = document.getElementsByName("unitamt[]");
	var invoiceAmount = document.getElementsByName("invamount[]");
	var fromsrlno = document.getElementsByName("fromsrlno[]");
	// var tosrlno = document.getElementsByName("tosrlno[]");

    var purchasearray=Array.prototype.slice.call(PurchaseType);
    var purchaselist = purchasearray.map((o) => o.value).join("#");

    var producthiddenarray=Array.prototype.slice.call(productnamehidden);
    var producthiddenlist = producthiddenarray.map((o) => o.value).join("*");

    var productarray=Array.prototype.slice.call(productType);
    var productlist = productarray.map((o) => o.value).join("#");

    var quantityarray=Array.prototype.slice.call(quantity);
    var quantitylist = quantityarray.map((o) => o.value).join(",");

    var unitamtarray=Array.prototype.slice.call(unitAmt);
    var unitamtlist = unitamtarray.map((o) => o.value).join("*");

    var invoiceamountarray=Array.prototype.slice.call(invoiceAmount);
    var invoiceamountlist = invoiceamountarray.map(x => x.value).join("*");

    var fromsrlnoarray=Array.prototype.slice.call(fromsrlno);
    var fromsrlnolist = fromsrlnoarray.map(x => x.value).join("~");
	// var checkl = fromsrlnoarray.map(x => x.value);
	// console.log(fromsrlnolist);

	var srlvalues = $("input[name='fromsrlno[]']").map(function(){return $(this).val();}).get();
	// var checkarray = [];
	// checkarray = srlvalues;
	// console.log(checkarray.length);

    // var tosrlnoarray=Array.prototype.slice.call(tosrlno);
    // var tosrlnolist = tosrlnoarray.map(x => x.value).join("~");

    var igstrate = $('#igstrate').val();
    var cgstrate = $('#cgstrate').val();
    var sgstrate = $('#sgstrate').val();
	var state_gst_code = $('#state_gst_code').val();
    var branchhidden = $('#branchhidden').val();

    var productgridrow = '';
    var productgrid = '';
    $("#previewproductgrid tr").remove();
	$("#previewbuttons td").remove();
    var productgridheader = '<tr bgcolor="#cccccc" ><td width="7%"><div align="center" ><strong>Sl No</strong></div></td><td width="64%"><div align="center"><strong>Description</strong></div></td><td width="5%"><div align="center"><strong>Qty</strong></div></td><td width="12%"><div align="center"><strong>Rate</strong></div></td><td width="12%"><div align="center"><strong>Amount</strong></div></td></tr>';

    var purchasevalues = purchaselist.split('#');
    var productnames = productlist.split('#');
    var producthiddennames = producthiddenlist.split('*');
    var quantityvalues = quantitylist.split(',');
    var unitamvalues = unitamtlist.split('*');
    var invoiceamountvalues = invoiceamountlist.split('*');
    var fromsrlnovalues = fromsrlnolist.split('~');
    // var tosrlnovalues = tosrlnolist.split('~');
    // if(productnames!="")
    // {
        var k=0;
        var purchasetype = '';
		var serialno = '';
		var array = [];
		
        for(i=0;i<productnames.length;i++)
        {
            k++;
			var productsplit = producthiddennames[i].split('#');
            //console.log(purchasevalues[i]);
			purchasetype = purchasevalues[i];
            // if(purchasevalues[i]  == 'new')
            //     purchasetype = 'New';
            // else if(purchasevalues[i]  == 'updation')
            //     purchasetype = 'Updation';
			
									
			array = srlvalues.slice(0,quantityvalues[i]);
			var filterarray = array.filter((x ="") => x.trim());
			if(filterarray.length!= 0)
			{
				serialno = filterarray.join("/");
			}
			else
			{
				serialno = '';
			}
			
			srlvalues.splice(0,quantityvalues[i]);

            var productgrid = '<tr><td width="7%" class="grey-td-border-grid" style="">' + k +'</td>';
            productgrid += '<td width="64%" class="grey-td-border-grid" style="text-align: left;"><font color="#FF0000">'+ productsplit[0] +'</font> <br/> ';
            productgrid += 'Purchase Type: <font color="#FF0000">'+purchasetype+'</font> / ';
            productgrid += 'Serial No:<font color="#FF0000">'+serialno+'</font></td>';
            productgrid += '<td width="5%" class="grey-td-border-grid" style="text-align: right;">' + quantityvalues[i] +'</td>';
            productgrid += '<td width="12%" class="grey-td-border-grid"style="text-align: right;">' + intToFormat(unitamvalues[i]) +'</td>';
            productgrid += '<td width="12%" class="grey-td-border-grid" style="text-align: right;">' + intToFormat(invoiceamountvalues[i]) +'</td><tr>';
            
            var productgridrow = productgridrow + productgrid ;
            total_product_price = parseInt(total_product_price)+parseInt(invoiceamountvalues[i]);
        }
        //console.log(total_product_price);
        var emptyrow = '<td colspan="4" class="grey-td-border-grid"  style="border-right:none"><br/><br/></td><td style="text-align: right;" class="grey-td-border-grid" >&nbsp;</td>';

        if(state_gst_code == branchhidden)
        {
            sgst_tax_amount = (total_product_price * (cgstrate/100)).toFixed(2);
            cgst_tax_amount = (total_product_price * (sgstrate/100)).toFixed(2);
            igst_tax_amount = '0.00';
            netamount = total_product_price + parseFloat(cgst_tax_amount) + parseFloat(sgst_tax_amount);

            var amountgrid = '<tr><td colspan="3" class="grey-td-border-grid">Accounting Code For Service </td>';
            amountgrid += '<td valign="top" class="grey-td-border-grid"  width="15%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" class="grey-td-border-grid" valign="top" style="text-align: right;" ><span id="netamountpreview"></span></td></tr>';
            amountgrid +='<tr><td colspan="4"  class="grey-td-border-grid"  ><div align = "right">CGST @ '+cgstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="cgstpreview">&nbsp;</td></tr>';
            amountgrid += '<tr><td colspan="4"  class="grey-td-border-grid" ><div align = "right">SGST @ '+sgstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="sgstpreview">&nbsp;</td></tr>';
            amountgrid += '<tr><td colspan="4" class="grey-td-border-grid" ><div align = "right">Total</div></td><td nowrap="nowrap"  class="grey-td-border-grid" style="text-align: right;" id="totalamountpreview">&nbsp;</td></tr>';
            amountgrid += '<tr><td colspan="5" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td></tr>';
        
        }
        else
        {
            igst_tax_amount = (total_product_price * (igstrate/100)).toFixed(2);
            sgst_tax_amount = '0.00';
            cgst_tax_amount = '0.00';
			netamount = total_product_price + parseFloat(igst_tax_amount);

            var amountgrid = '<tr><td colspan="3" class="grey-td-border-grid">Accounting Code For Service </td>';
            amountgrid += '<td valign="top" class="grey-td-border-grid"  width="15%" style="border-left:none"><div align = "right">Net Amount</div></td><td nowrap="nowrap" class="grey-td-border-grid" valign="top" style="text-align: right;" ><span id="netamountpreview"></span></td></tr>';
            amountgrid +='<tr><td colspan="4"  class="grey-td-border-grid"  ><div align = "right">IGST @ '+igstrate+'%</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" id="igstpreview">&nbsp;</td></tr>';
            amountgrid += '<tr><td colspan="4" class="grey-td-border-grid" ><div align = "right">Total</div></td><td nowrap="nowrap" style="text-align: right;"  class="grey-td-border-grid" style="text-align: right;" id="totalamountpreview">&nbsp;</td></tr>';
            amountgrid += '<tr><td colspan="5" class="grey-td-border-grid" style="border-right:none">Rupee In Words: <span id="rupeeinwordspreview"></span></td></tr>';
           
        }
        netamount = Math.round(netamount);
		netamount = parseFloat(netamount).toFixed(2);

   // }
   previewbutton = '<td  valign="top" style="text-align: center; padding-top:10px;"  ><div align="center"><input name="proceed" type="button" class= "swiftchoicebutton" id="proceed" value="Proceed"  onclick="this.disabled=true;proceedforuserpurchase();disableproceedbutton();"/>&nbsp;&nbsp;<input name="cancel" type="button" class= "swiftchoicebutton" id="cancel" value="Cancel"  onclick="cancelpurchase();"/></div</td>';

    $("#previewproductgrid").append(productgridheader+productgridrow+emptyrow+amountgrid);
    $("#totalamountpreview").html(intToFormat(netamount));
	$("#rupeeinwordspreview").html(NumbertoWords(netamount));
    $("#netamountpreview").html(intToFormat(total_product_price));
    $("#cgstpreview").html(intToFormat(cgst_tax_amount));
	$("#sgstpreview").html(intToFormat(sgst_tax_amount));
    $("#igstpreview").html(intToFormat(igst_tax_amount));
    $("#cgst_tax_amount").val(cgst_tax_amount);
	$("#sgst_tax_amount").val(sgst_tax_amount);
    $("#igst_tax_amount").val(igst_tax_amount);
    var invoiceremarks = $("#invoiceremarks").val();
	if(invoiceremarks == '')
		invoiceremarks = 'None';
	$("#invoiceremarkspreview").html(invoiceremarks);
	$("#paymentremarkspreview").html($("#paymentremarks").val());
	$("#previewbuttons").append(previewbutton) ;
	$("#proceedprocessingdiv").html('');
	enableproceedbutton();
    $("").colorbox({ inline:true, href:"#invoicepreviewdiv" , onLoad: function() { $('#cboxClose').hide()}});
}

function rejectpurchase(invrequestno)
{
	var error = $("#proceedprocessingdiv");
    error.html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=rejectpurchase&invrequestno=" + encodeURIComponent(invrequestno) +   "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
    {
        type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
        success: function(ajaxresponse,status)
        {
            response = ajaxresponse.split('^');
            if(response[0] == 1)
            {
                alert('Request Requested!');
                error.html('');
                $().colorbox.close();
				approverejectrequest($('#lastslno').val());
            }
        }
    });
}

function proceedforpurchase(invrequestno)
{
    //alert(invrequestno);
	disableproceedbutton();
    var error = $("#proceedprocessingdiv");
    error.html('<img src="../images/imax-loading-image.gif" border="0" />');
	 
	if($('#tdsdeclaration'))
		var tdsdeclaration = 'yes';
	else
		var tdsdeclaration	= 'no';
	//alert(tdsdeclaration);

    var passData = "switchtype=proceedforpurchase&invrequestno=" + encodeURIComponent(invrequestno) + "&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&sez_enabled=" + encodeURIComponent($('#sez_enabled').val()) + "&cusaddress=" + encodeURIComponent($('#displayaddress').val()) + "&state_gst_code=" + encodeURIComponent($('#state_gst_code').val()) + "&tdsdeclaration=" + encodeURIComponent(tdsdeclaration) +   "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
    {
        type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
        success: function(ajaxresponse,status)
        {
            response = ajaxresponse.split('^');
            if(response[0] == 1)
            {
                alert('Invoice Generated!');
				enableproceedbutton();
                error.html('');
                $().colorbox.close();
				removerows();
				$("#submitform")[0].reset();
				approverejectrequest(response[2]);
				generateinvoicedetails(response[2]);
            }
            else if(response[0] == 2)
			{
			//alert("duplicate1");
				$('#proceedprocessingdiv').html(errormessage(response[1]));
			}
			else if(response[0] == 3)
			{
				var gstinconfirm = confirm("GSTIN is not valid. Do you want to proceed further?");
				if(gstinconfirm)
				{
					//alert('true');
					//$('#gstinconfirm').val('gstinconfirm');
					proceedforpurchase('gstinconfirm');
				}
				else
				{
					$('#proceedprocessingdiv').html('');
					return false;
				}
			}
			else
			{
				$('#proceedprocessingdiv').html(errormessage(response[1]));
			}
        }
    });

}

function removerows()
{
	$("#seletedproductgrid").find("tr:gt(0)").remove();
	$('#purchasetype1').val('');
	$('#producttype1').val('');
	var quantity = $('#quantity1').val();
	for(i=1;i<=quantity;i++)
	{
		//alert('#fromsrlno'+i);
		$('#fromsrlno'+i).remove();
	}
	$('#quantity1').val('');
	$('#unitamt1').val('');
	$('#invamount1').val('');
	$('#productnamehidden1').val('');	
}

function proceedforuserpurchase(getMessage)
{
	disableproceedbutton();
    var lastslno =  $('#lastslno').val();
    var displaycustomergst = $('#displaycustomergst').val();
    var displaycustomerid = $('#displaycustomerid').val();
    var displaycompanyname = $('#displaycompanyname').val();
    var displaycontactperson = $('#displaycontactperson').val();
    var displayaddress = $('#displayaddress').val();
    var displayphone = $('#displayphone').val();
    var displaycell = $('#displaycell').val();
    var displayemail = $('#displayemail').val();
    var displaytypeofcategory = '';
    var displaytypeofcustomer = '';
    if($('#displaytypeofcategory').val() == 'Not Available')
        displaytypeofcategory = '';
    else
        displaytypeofcategory=  $('#displaytypeofcategory').val();

    if($('#displaytypeofcustomer').val() == 'Not Available')
        displaytypeofcustomer = '';
    else
        displaytypeofcustomer =  $('#displaytypeofcustomer').val();
    var state_gst_code = $('#state_gst_code').val();

    var PurchaseType = document.getElementsByName("purchasetype[]");
	var productnamehidden = document.getElementsByName("productnamehidden[]");
	var productType = document.getElementsByName("producttype[]");
	var quantity = document.getElementsByName("quantity[]");
	var unitAmt = document.getElementsByName("unitamt[]");
	var invoiceAmount = document.getElementsByName("invamount[]");
	var fromsrlno = document.getElementsByName("fromsrlno[]");
	var tosrlno = document.getElementsByName("tosrlno[]");

    var purchasearray=Array.prototype.slice.call(PurchaseType);
    var purchaselist = purchasearray.map((o) => o.value).join("#");

    var producthiddenarray=Array.prototype.slice.call(productnamehidden);
    var producthiddenlist = producthiddenarray.map((o) => o.value).join("*");

    var productarray=Array.prototype.slice.call(productType);
    var productlist = productarray.map((o) => o.value).join("#");

    var quantityarray=Array.prototype.slice.call(quantity);
    var quantitylist = quantityarray.map((o) => o.value).join(",");

    var unitamtarray=Array.prototype.slice.call(unitAmt);
    var unitamtlist = unitamtarray.map((o) => o.value).join("*");

    var invoiceamountarray=Array.prototype.slice.call(invoiceAmount);
    var invoiceamountlist = invoiceamountarray.map(x => x.value).join("*");

    var fromsrlnoarray=Array.prototype.slice.call(fromsrlno);
    var fromsrlnolist = fromsrlnoarray.map(x => x.value).join("~");

	var srlvalues = Array.from($("input[name='fromsrlno[]']").map(function(){return $(this).val();}).get());

	//alert(fromsrlnolist);

    var tosrlnoarray=Array.prototype.slice.call(tosrlno);
    var tosrlnolist = tosrlnoarray.map(x => x.value).join("~");

	var dealername = $("#salesperson option:selected").text();
	var dealerid = $("#salesperson").val();

	if($('#tdsdeclaration').is(":checked"))
		var tdsdeclaration = 'yes';
	else
		var tdsdeclaration	= 'no';

    
    $("#proceedprocessingdiv").html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=proceedforuserpurchase&purchasevalues=" + encodeURIComponent(purchaselist) + "&producthiddenvalues=" + encodeURIComponent(producthiddenlist) + "&quantityvalues=" + encodeURIComponent(quantitylist) + "&unitamtvalues=" + encodeURIComponent(unitamtlist)+ "&invoiceamountvalues=" + encodeURIComponent(invoiceamountlist)+ "&fromsrlnovalues=" + encodeURIComponent(fromsrlnolist)+ "&fromsrlno=" + encodeURIComponent(srlvalues)+ "&invoiceremarks=" + encodeURIComponent($('#invoiceremarks').val())+ "&paymentremarks=" + encodeURIComponent($('#paymentremarks').val())+ "&paymentamount=" + encodeURIComponent($('#paymentamount').val())+ "&lastslno=" + encodeURIComponent(lastslno) + "&customer_gstno=" + encodeURIComponent(displaycustomergst) + "&igstamount=" + encodeURIComponent($('#igst_tax_amount').val()) + "&cgstamount=" + encodeURIComponent($('#cgst_tax_amount').val()) + "&sgstamount=" + encodeURIComponent($('#sgst_tax_amount').val()) + "&dealerid=" + encodeURIComponent(dealerid) + "&dealername=" + encodeURIComponent(dealername) + "&customerid=" + encodeURIComponent(displaycustomerid)+ "&cusname=" + encodeURIComponent(displaycompanyname)+ "&cuscontactperson=" + encodeURIComponent(displaycontactperson)+ "&address=" + encodeURIComponent($('#address').val())+ "&cusaddress=" + encodeURIComponent(displayaddress)+ "&cusemail=" + encodeURIComponent(displayemail)+ "&cusphone=" + encodeURIComponent(displayphone)+ "&cuscell=" + encodeURIComponent(displaycell)+ "&custype=" + encodeURIComponent(displaytypeofcustomer)+ "&cuscategory=" + encodeURIComponent(displaytypeofcategory)+ "&podate=" + encodeURIComponent($('#DPC_startdate').val())+ "&poreference=" + encodeURIComponent($('#poreference').val()) + "&tdsdeclaration=" + encodeURIComponent(tdsdeclaration) + "&state_gst_code=" + encodeURIComponent(state_gst_code)+ "&gstcheck=" + encodeURIComponent(getMessage) +  "&editinvreqno=" + encodeURIComponent($('#editinvreqno').val()) + "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
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
                var response = ajaxresponse.split('^');
                
                $('#proceedprocessingdiv').html('');
                if(response[0] == 1)
                {
                    alert(response[1]);
					enableproceedbutton();
                    $().colorbox.close();
                    $("#submitform")[0].reset();
					removerows();
					approverejectrequest(response[2]);
                    generateinvoicedetails(response[2]);
                }
				else if(response[0] == 2)
				{
				//alert("duplicate1");
					$('#proceedprocessingdiv').html(errormessage(response[1]));
				}
                else if(response[0] == 3)
                {
                    var gstinconfirm = confirm("GSTIN is not valid. Do you want to proceed further?");
					if(gstinconfirm)
					{
						//alert('true');
						//$('#gstinconfirm').val('gstinconfirm');
						proceedforuserpurchase('gstinconfirm');
					}
					else
					{
						$('#proceedprocessingdiv').html('');
						return false;
					}
                }
				else
				{
					$('#proceedprocessingdiv').html(errormessage(response[1]));
				}
                
            }
        }, 
        error: function(a,b)
        {
            $("#proceedprocessingdiv").html(scripterror());
        }
    });
}

function disableproceedbutton()
{
	$('#proceed').removeClass('swiftchoicebutton');	
	$('#proceed').addClass('swiftchoicebuttondisabled');

	$('#approve').removeClass('swiftchoicebutton');	
	$('#approve').addClass('swiftchoicebuttondisabled');

	$('#reject').removeClass('swiftchoicebutton');	
	$('#reject').addClass('swiftchoicebuttondisabled');
}

function enableproceedbutton()
{
	$('#proceed').attr("disabled", false); 
	$('#proceed').removeClass('swiftchoicebuttondisabled');	
	$('#proceed').addClass('swiftchoicebutton');
	
	$('#approve').attr("disabled", false); 
	$('#approve').removeClass('swiftchoicebuttondisabled');	
	$('#approve').addClass('swiftchoicebutton');

	$('#reject').attr("disabled", false); 
	$('#reject').removeClass('swiftchoicebuttondisabled');	
	$('#reject').addClass('swiftchoicebutton');
}

function approverejectrequest(cusid)
{
	var error = $("#form-error");
    error.html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=approverejectrequest&lastslno=" + encodeURIComponent(cusid) +   "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
    {
        type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
        success: function(ajaxresponse,status)
        {
            response = ajaxresponse;
            if(response['errorcode'] == 1)
            {
                var invoicegrid = response['invoicegrid'];
				//alert(invoicegrid);
				if(!invoicegrid)
				{
					gridtab4('1','tabgroupgrid','&nbsp;&nbsp;Approve/Reject');
					$('#tabgroupgridwb1').html("Total Count :  " + '0');
					$('#invoicedetailsgridc1_1').html('No datas found to be displayed.');
				}
				else
				{
					gridtab4('1','tabgroupgrid','&nbsp;&nbsp;Approve/Reject');
					$('#tabgroupgridwb1').html("Total Count :  " + response['totalcount']);
					$('#invoicedetailsgridc1_1').html(invoicegrid);
				}
                error.html('');
				//generateinvoicedetails();
            }
        }
    });

}

function generateinvoicedetails(cusid)
{
	var error = $("#form-error");
    error.html('<img src="../images/imax-loading-image.gif" border="0" />');
    var passData = "switchtype=getinvoicedetails&lastslno=" + encodeURIComponent(cusid) +   "&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
    var queryString = "../ajax/matrixinvoicing.php";
    ajaxcall10 = $.ajax(
    {
        type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
        success: function(ajaxresponse,status)
        {
            response = ajaxresponse;
            if(response['errorcode'] == 1)
            {
                var invoicegrid = response['invoicegrid'];
				//alert(invoicegrid);
				if(!invoicegrid)
				{
					$('#tabgroupgridwb2').html("Total Count :  " + '0');
					$('#tabgroupgridc1_1').html('No datas found to be displayed.');
				}
				else
				{
					$('#tabgroupgridwb2').html("Total Count :  " + response['totalcount']);
					$('#tabgroupgridc1_1').html(invoicegrid);
				}
                error.html('');
            }
        }
    });
}

function getdealerdetails(dealerval)
{
	var error = $('#form-error');
	var dealerid = $("#salesperson").val();
	var passdata = "switchtype=getdealerdetails&dealerid="+ encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*10054300000);
	var queryString = "../ajax/matrixinvoicing.php";
	ajaxcall41 = $.ajax(
	{
		type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
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
				if(response['errorcode'] == 1)
				{
					$('#branchhidden').val(response['branch_gst_code']);
					$('#branch_gstin').val(response['branch_gstin']);
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}

function cancelpurchase()
{
	$('#form-error').html('');
	$().colorbox.close();
}

//Function to view the bill in pdf format----------------------------------------------------------------
function viewmatrixinvoice(slno)
{
	$('#invoicelastslno').val(slno);
	var form = $('#submitform');	
	// if($('#onlineslno').val() == '')
	// {
	// 	$('#productselectionprocess').html(errormessage('Please select a Customer.')); return false;
	// }
	// else
	// {
		$('#submitform').attr("action", "../ajax/viewmatrixinvoicepdf.php") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	//}
}