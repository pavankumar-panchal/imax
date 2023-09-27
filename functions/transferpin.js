// JavaScript Document

var cardarray = new Array();
function refreshcardarray()
{
	var form = $('#cardsearchfilterform');
	var passData = "type=getcardlist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#pinselectionprocess').html(getprocessingimage());
	queryString = "../ajax/transferpin.php";
	ajaxcall555 = $.ajax(
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
				$('#form-error').html('');
				var response = ajaxresponse;
				cardarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray[i] = response[i];
				}
				process1 = true;
				compilepinarray();
			}
		}, 
		error: function(a,b)
		{
			$("#pinselectionprocess").html(scripterror());
		}
	});	
}

function compilepinarray()
{
	if(process1 == true)
	{
		flag = true;
		$("#pinselectionprocess").html(successsearchmessage('All Pin Number...'))
		getcardlist();
	}
	else
	return false;
}

function getcardlist()
{	
	var form = $('#cardsearchfilterform');
	var selectbox = $('#cardlist');
	var numberofcards = cardarray.length;
	//document.cardsearchfilterform.cardsearchtext.focus();
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	//selectbox.options.length = 0;
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = cardarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);

	}
}

/*Fetching details function */
function scratchdetailstoform(cardid)
{
	if(cardid !='')
	{
		var passData = "type=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + 
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passData);
		$('#form-error').html(getprocessingimage());
		var queryString = "../ajax/transferpin.php";
		ajaxcall8 = $.ajax(
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
					$('#form-error').html('');
					$('#scratchcradloading').html('');
					var response = ajaxresponse;
					if(response['errorcode'] == '1')
					{
						$('#transfercardfield').val(response['scratchnumber']);
						$('#transfercardid').val(response['cardid']);
						$('#tfpurchasetype').val(response['purchasetype']);
						$('#tfusagetype').val(response['usagetype']);
						$('#tfdealer').val(response['attachedto']);
						$('#tfproduct').val(response['productname']);
						$('#productcode').val(response['productcode']);
						$('#delaerrep').val(response['dealerid']); 
						
						$('#tfpurchasetype').val(response['purchasetype']);
						$('#tfusagetype').val(response['usagetype']);
						$('#tfdealer').val(response['attachedto']);
						$('#tfproduct').val(response['productname']);
						
						$('#tfregisteration').val(response['registeredto']);
						//alert(response['registeredto']);
						if(response['registeredto'] == '' || response['registeredto'] == null)
						{
							$('#ttmoveregisteration').html('');
							$('#registerationspan').html('<font color="#00C400">NOT Registered Yet!!</font>');
						}
						else
						{
							$('#ttmoveregisteration').html('&nbsp;<input type="checkbox" name="moveregisterationcheck" id="moveregisterationcheck" />&nbsp;TO REG');
							$('#registerationspan').html('<font color="#FB0000"><b>Warning!! Registered PIN !!</b></font>');
						}
						
						if(response['customerid'] == '')
						{
							$('#tfattachedcust').attr("disabled", true);
							$('#tfattachedcust').addClass('swifttext-readonly');
							//$('#tfattachedcust').val(response['customerid']);
						}
						else
						{
							$('#tfattachedcust').val(response['customerid']);
							$('#tfattachedcust').addClass('swifttext');
						}
					}
					else
					{
						$('#form-error').html(errormessage('No datas found to be displayed.'));
					}
					
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});
	}
}

function transferpindatagrid(startlimit,cardid)
{
	var passData = "type=generategrid&cardid=" + encodeURIComponent(cardid) + 
	"&startlimit=" + encodeURIComponent(startlimit)+ 
	"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/transferpin.php";
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
					$('#tabgroupgridc1_1').html(response[1]);
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

function selectcardfromlist()
{
	selectbox = $("#cardlist option:selected").val();
	// alert(selectbox); 
	// console.log(selectbox);
	
	if(typeof selectbox === 'undefined')
	{
		alert('Please Select Pin!');
		return false;
	}
	else
	{
		$('#cardsearchtext').val($("#cardlist option:selected").text());
		$('#cardsearchtext').select();
		transferpindatagrid('',selectbox);
		scratchdetailstoform(selectbox);
	}
	
}

function selectacard(input)
{
	var selectbox = $('#cardlist');
	
	if(input == "")
	{
		getcardlist();
	}
	else
	{
		$('option', selectbox).remove();
		var options = selectbox.attr('options');

		var addedcount = 0;
		for( var i=0; i < cardarray.length; i++)
		{
			
				if(input.charAt(0) == ".")
				{
					withoutdot = input.substring(1,input.length);
					pattern = new RegExp("^" + withoutdot.toLowerCase());
					comparestringsplit = cardarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = cardarray[i];
				}
				var result1 = pattern.test(trimdotspaces(cardarray[i]).toLowerCase());
				var result2 = pattern.test(cardarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = cardarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}
function cardsearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrollcard('up');
	else if(KeyID == 40)
		scrollcard('down');
	else
	{
		var form = $('#cardsearchfilterform');
		var input = $('#cardsearchtext').val();
		selectacard(input);
	}
}

function scrollcard(type)
{
	var selectbox = $('#cardlist');
	var totalcus = $("#cardlist option").length;
	var selectedcus = $("select#cardlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#cardlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#cardlist").attr('selectedIndex', selectedcus + 1);
	selectcardfromlist();
}

function clearcarddetails()
{
	disabletranfer()
}

function newentry()
{
	
	var form = $('#cardsearchfilterform');
	$('#form-error').html('');
	$('#cardsearchtext').val('');
	$('#cardlastslno').val('');
	$('#transfercardfield').val('');
	$('#transfercardid').val('');
	$('#tfdealer').val('');
	$('#tfproduct').val('');
	$('#tfpurchasetype').val('');
	$('#tfusagetype').val('');
	$('#tfusagetype').val('');
	$('#tfattachedcust').val('');
	$('#remarks').val('');
	$('#ttdealerto').val('');
	$('#ttproductto').val('');
	$('#ttpurchasetype').val('');
	$('#ttusagetype').val('');
	$('#ttattachedcust').val('');
	$('#tfregisteration').val('');
	$('#registerationspan').html('');
	
	$('#ttattachedcustcheck').removeAttr('checked');
	$('#ttdealercheck').removeAttr('checked');
	$('#ttproductcheck').removeAttr('checked');
	$('#ttusagetypecheck').removeAttr('checked');
	$('#ttpurchasetypecheck').removeAttr('checked');
	disabletranfer();
}

function dealercheckbox()
{
	var form = $('#transferscratchform');
	var ttdealercheck = $('#ttdealercheck');
	if($('#ttdealercheck').is(':checked'))
	{
		$('#ttdealerto').attr("disabled", false);
	}
	else
	{
		$('#ttdealerto').attr("disabled", true);
	}
}


function transferscratchdetails()
{
	var form = $("#transferscratchform");
	var error = $("#form-error");
	var ttdealercheck = $("#ttdealercheck");
	var ttproductcheck = $("#ttproductcheck");
	var ttpurchasetypecheck = $("#ttpurchasetypecheck");
	var ttusagetypecheck = $("#ttusagetypecheck");
	var ttdealerto = $("#ttdealerto");
	var ttproductto = $("#ttproductto");
	var cardid = $('#transfercardid').val();
	
	if($('#moveregisterationcheck').is(':checked') == true) var moveregisterationcheck = 'yes'; else var moveregisterationcheck = 'no';
	
	var field = $("#transfercardfield" );
	if(!field.val()) { error.html(errormessage("Please Select Pin Number. ")); field.focus(); return false; }
	
	if(($('#ttdealercheck').is(':checked')) == false && ($('#ttproductcheck').is(':checked')) == false 
  && ($('#ttpurchasetypecheck').is(':checked')) == false && ($('#ttusagetypecheck').is(':checked')) == false 
  && ($('#ttattachedcustcheck').is(':checked')) == false)
	{
		error.html(errormessage("Select Transfer To")); ttdealercheck.focus(); return false;
	}
	if(($('#ttdealercheck').is(':checked')) == true) 
	{
		$('#ttdealerto').attr("disabled", false);
		var field = $('#ttdealerto'); if(!field.val()) { error.html(errormessage("Please select the Dealer from the list")); $('#ttdealerto').focus(); return false; } 
		var ttdealerto1 = $('#ttdealerto').val();
	}
	else
	{
		var ttdealerto1 = '';
	}
	
	if(($('#ttproductcheck').is(':checked')) == true) 
	{  
		$('#ttproductto').attr("disabled", false); 
		var field = $('#ttproductto'); if(!field.val()) { error.html(errormessage("Please select the Prduct.")); $('#ttproductto').focus(); return false; }
		var ttproductto1 = $('#ttproductto').val();
	}
	else
	{
		var ttproductto1 = '';
	}
	
	if(($('#ttusagetypecheck').is(':checked')) == true) 
	{
		$('#ttusagetype').attr("disabled", false);
		var field = $('#ttusagetype'); if(!field.val()) { error.html(errormessage("Please select the Usage Type.")); $('#ttdealerto').focus(); return false; } 
		var ttusagetype1 = $('#ttusagetype').val();
	}
	else
	{
		var ttusagetype1 = '';
	}	
	
	if(($('#ttpurchasetypecheck').is(':checked')) == true) 
	{
		$('#ttpurchasetype').attr("disabled", false); 
		var field = $('#ttpurchasetype'); if(!field.val()) { error.html(errormessage("Please select the Purchase Type.")); field.focus(); return false; }
		var ttpurchasetype1 = $('#ttpurchasetype').val();

	}
	else
	{
		var ttpurchasetype1 = '';
	}
	
	if(($('#ttattachedcustcheck').is(':checked')) == true) 
	{
		$('#ttattachedcust').attr("disabled", false);
		var field = $('#ttattachedcust'); if(!field.val()) { error.html(errormessage("Please select the Attach Customer")); $('#ttattachedcust').focus(); return false; } 
		if(field.val()) { if(isNaN(field.val())) { error.html(errormessage('Attach Customer should be only Integers.')); field.focus(); return false; } }
		var ttattachedcust1 = $('#ttattachedcust').val();
	}
	else
	{
		var ttattachedcust1 = '';
	}

		var passData = "type=scratchtransfer&dealerid=" + encodeURIComponent(ttdealerto1) +
		"&productcode=" + encodeURIComponent(ttproductto1) +
		"&usagetype=" + encodeURIComponent(ttusagetype1) + 
		"&purchasetype=" + encodeURIComponent(ttpurchasetype1) +
		"&attachcust=" + encodeURIComponent(ttattachedcust1) +
		
		"&moveregisterationcheck=" + encodeURIComponent(moveregisterationcheck) +
		"&tfregisteration=" + encodeURIComponent($('#tfregisteration').val()) +
		
		"&tfdealername=" + encodeURIComponent($('#tfdealer').val()) +
		"&tfproductname=" + encodeURIComponent($('#tfproduct').val()) +
		"&tfproduct=" + encodeURIComponent($('#productcode').val()) +
		"&tfdealer=" + encodeURIComponent($('#delaerrep').val()) +
		"&tfpurchasetype=" + encodeURIComponent($('#tfpurchasetype').val()) +
		"&tfusagetype=" + encodeURIComponent($('#tfusagetype').val()) +
		"&tfattachedcust=" + encodeURIComponent($('#tfattachedcust').val()) +
		
		"&pinnumber=" + encodeURIComponent($('#transfercardfield').val()) + 
		"&cardid=" + encodeURIComponent(cardid) + 
		"&transferremarks=" + encodeURIComponent($('#remarks').val()) + 
		"&transferby=" + encodeURIComponent($('#transferby').val()) +
		"&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passData)
		error.html(getprocessingimage());
		var queryString = "../ajax/transferpin.php";
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
					error.html('');
					var response = ajaxresponse;
					if(response['errorcode']== 1)
					{
						alert(response['successmessage']);
						error.html(successmessage(response['successmessage']));
						transferpindatagrid('',cardid);
						newentry();
						refreshcardarray();
					}

				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
}

function tranfervalues()
{
	disabletranfer();
	$('#tranfer-form-error').html('');
	$('#transfercardfield').val($('#searchscratchnumber').val());
	$('#tfpurchasetype').val($('#purchasetypedisplay').html());
	$('#tfusagetype').val($('#usagetypedisplay').html());
	$('#tfdealer').val($('#attachedtodisplay').html());
	$('#tfproduct').val($('#productname').val());
	scratchdetailstoform($('#scratchnumber').val());
}


function disabletranfer()
{
	$('#ttdealerto').attr("disabled", true);
	$('#ttproductto').attr("disabled", true);

	$('#ttpurchasetype').attr("disabled", true);
	$('#ttusagetype').attr("disabled", true);
	$('#ttattachedcust').attr("disabled", true);
}
//Function to disable and enable the the selectbox on checked box value is true=
function productcheckbox()
{
	var form = $('#transferscratchform');
	var ttproductcheck = $('#ttproductcheck');
	if($('#ttproductcheck').is(':checked'))
	{
		$('#ttproductto').attr("disabled", false);
	}
	else
	{
		$('#ttproductto').attr("disabled", true);
	}
}

function purchasecheckbox()
{
	var form = $('#transferscratchform');
	var ttpurchasetypecheck = $('#ttpurchasetypecheck');
	if($('#ttpurchasetypecheck').is(':checked'))
	{
		$('#ttpurchasetype').attr("disabled", false);
	}
	else
	{
		$('#ttpurchasetype').attr("disabled", true);
	}
}

function usagecheckbox()
{
	var form = $('#transferscratchform');
	var ttusagetypecheck = $('#ttusagetypecheck');
	if($('#ttusagetypecheck').is(':checked'))
	{
		$('#ttusagetype').attr("disabled", false);
	}
	else
	{
		$('#ttusagetype').attr("disabled", true);
	}
}

function attachedcustcheckbox()
{
	var form = $('#transferscratchform');
	var ttattachedcustcheck = $('#ttattachedcustcheck');
	if($('#ttattachedcustcheck').is(':checked'))
	{
		$('#ttattachedcust').attr("disabled", false);
		$('#ttattachedcust').removeClass('swifttext-readonly');
		$('#ttattachedcust').addClass('swifttext');
	}
	else
	{
		$('#ttattachedcust').attr("disabled", true);
		$('#ttattachedcust').addClass('swifttext-readonly');
	}
}

function checkcustomerid()
{
	var error = $("#form-error");
	
	var passData = "type=checkcustomerid&attachcust=" + encodeURIComponent($("#ttattachedcust").val()) 
			+ "&dummy=" + Math.floor(Math.random()*10000000000);
			
	error.html(getprocessingimage());
	var queryString = "../ajax/transferpin.php";
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
				error.html('');
				var response = ajaxresponse;
				if(response['errorcode']== 2)
				{
					alert(response['errormessage']);
					error.html(errormessage(response['errormessage']));
				}
			
			}
		}, 
		error: function(a,b)
		{
		error.html(scripterror());
		}
	});	
}
		

