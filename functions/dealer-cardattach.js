// JavaScript Document
var regcardarray = new Array();
var regcardarray1 = new Array();
var regcardarray2 = new Array();
var regcardarray3 = new Array();
var regcardarray4 = new Array();


var process1 = false;
var process2 = false;
var process3 = false;
var process4 = false;

function getpin(product)
{
	var field = $("#dealerid");
	if(!field.val()){ alert("Please select Dealer"); $("#dealerid").val(''); field.focus(); return false; }

	var purtype = $("#purtype").val();
	gettotalcusattachcard(product,purtype);
}

function getpindetails()
{
	var field = $("#product");
	if(!field.val()){ alert("Please select Product"); $("#purtype").val(''); field.focus(); return false; }


	var product = $("#product").val();
	var purtype = $("#purtype").val();
	gettotalcusattachcard(product,purtype);
}

function gettotalcusattachcard(productcode,purtype)
{
	if(typeof productcode == 'undefined')
	{
		productcode = "";
	}
	if(typeof purtype == 'undefined')
	{
		purtype = "";
	}
		
	var form = $('#scratchcradloading');
	var passData = "switchtype=getcardcount&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&productcode="+ encodeURIComponent(productcode) + "&purtype="+ encodeURIComponent(purtype) + "&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/dealerattachcard.php";
	$('#scratchcradloading').html(getprocessingimage());
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			$('#scratchcradloading').html('');//alert(ajaxresponse)
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
				{
				    //alert (response['count']);
					$('#totalcountofcard').html(response['count']);
					$('#product').html(response['optiongrid']);
					if($("#dealerid").val() == "" || $("#product").val() == "")
						$("#purtype").val('');
					refreshdealerattachcardarray(response['count'],productcode,purtype);
					//$('#detailsonscratch').hide();
				}
				
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
}

function refreshdealerattachcardarray(cardcount,productcode,purtype)
{
	
	var totalcardcount = cardcount;
	//alert(totalcardcount);
	var limit = Math.round(totalcardcount/4);
	if(limit==0)
	{
		limit=totalcardcount;
	}
	//alert(limit);
	var startindex = 0;
	var startindex1 = (limit)+1;
	//alert (startindex1);
	var startindex2 = (limit*2)+1;
	var startindex3 = (limit*3)+1;
	var form = $('#cardsearchfilterform');
	var passData = "switchtype=getcardlist&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&productcode="+ encodeURIComponent(productcode) + "&purtype="+ encodeURIComponent(purtype) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit+1) + "&startindex=" + encodeURIComponent(startindex);
	var passData1 = "switchtype=getcardlist&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&productcode="+ encodeURIComponent(productcode) + "&purtype="+ encodeURIComponent(purtype) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex1);
	var passData2 = "switchtype=getcardlist&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&productcode="+ encodeURIComponent(productcode) + "&purtype="+ encodeURIComponent(purtype) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex2);
	var passData3 = "switchtype=getcardlist&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&productcode="+ encodeURIComponent(productcode) + "&purtype="+ encodeURIComponent(purtype) + "&dummy=" + Math.floor(Math.random()*10054300000) + "&limit=" + encodeURIComponent(limit) + "&startindex=" + encodeURIComponent(startindex3);
	
	regcardarray1=[];
	regcardarray2=[];
	regcardarray3=[];
	regcardarray4=[];
	$('#scratchcradloading').html(getprocessingimage());
	queryString = "../ajax/dealerattachcard.php";
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
					regcardarray1[i] = response[i];
				}
				process1 = true;
				//alert(process1);
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/dealerattachcard.php";
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
					regcardarray2[i] = response[i];
				}
				process2 = true;
				//alert(process2);
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	

	queryString = "../ajax/dealerattachcard.php";
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
					regcardarray3[i] = response[i];
				}
				process3 = true;
				//alert(process3);
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	queryString = "../ajax/dealerattachcard.php";
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
					regcardarray4[i] = response[i];
				}
				process4 = true;
				//alert(process4);
				compilecardarray();
			}
		}, 
		error: function(a,b)
		{
			$("#scratchcradloading").html(scripterror());
		}
	});	
	
	
}

function compilecardarray()
{
	if(process1 == true && process2 == true && process3 == true && process4 == true)
	{
		regcardarray = regcardarray1.concat(regcardarray2.concat(regcardarray3.concat(regcardarray4)));
		getregcardlist();
		$('#scratchcradloading').html('');
		
	}
	else
	return false;
}

/*function refreshdealerattachcardarray()
{
	$('#searchscratchnumber').val('');
	disabledetachcard();
	scratchdetailstoform('');
	disableattachcard();
	
	var passData = "switchtype=getcardlist&dealerid=" +encodeURIComponent($("#dealerid").val()) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
	queryString = "../ajax/dealerattachcard.php";
	
	ajaxnewrequest = $.ajax(
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
				regcardarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					regcardarray[i] = response[i];
					
				}
				getregcardlist();
				
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessing").html(scripterror());
		}
		
	});	
	
	
}
*/

function getregcardlist()
{	
	var form = $('#registrationform');
	var selectbox = $('#scratchcardlist');
	var numberofcards = regcardarray.length;
	//document.registrationform.searchscratchnumber.focus();
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = regcardarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
}

function reg_selectcardfromlist()
{
	var selectbox = $("#scratchcardlist option:selected").val();
	$('#searchscratchnumber').val($("#scratchcardlist option:selected").text());
	$('#searchscratchnumber').select();
    scratchdetailstoform(selectbox);	
	$('#form-error').html('');
	disabledetachcard();
}



function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		enableattachcard();
		var passData = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); //alert(passData)
		$('scratchcradloading').html(getprocessingimage());
		var queryString = "../ajax/dealerattachcard.php";
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
					$('#scratchcradloading').html('');
					$('#detailsonscratch').show();
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == 1)
					{
						$('#cardnumberdisplay').html(response[1]);
						$('#scratchnodisplay').html(response[2]);
						$('#purchasetypedisplay').html(response[3]);
						$('#usagetypedisplay').html(response[4]);
						$('#attachedtodisplay').html(response[5]);//alert(response[4])
						$('#productdisplay').html(response[8] + ' [' + response[7] + ']');
						$('#registeredtodisplay').html(response[13]);
						$('#attachdatedisplay').html(response[9]);
						//$('#registerdatedisplay').html(response[9];
						$('#cardstatusdisplay').html(response[13]);
						$('#cardattacheddate').html(response[14]);
						$('#remarks').html(response[15]);
						
						if (response[4]== 'singleuser') 
						{
							var usagetype= 'Single User';
						}
						else if (response[4]== 'multiuser')
						{
							var usagetype= 'Multi User';
						}
						else if (response[4]== 'additionallicense')
						{
							var usagetype= 'Additional License';
						}
						else
						{
							var usagetype='';
						}
						
						
						document.getElementById('description').value = "1$"+response[8]+"$"+response[3]+"$"+usagetype+"$"+response[2]+"$"+response[1]+"$0";
					}
				}
			}, 
			error: function(a,b)
			{
				$("#scratchcradloading").html(scripterror());
			}
		});	
	}
	else
	{
		$('#detailsonscratch').hide();	
	}
	
}


function enableattachcard()
{
	$('#attachcard').attr('disabled',false);
	$('#attachcard').addClass('swiftchoicebuttonbig');
	$('#detachcard').removeClass('swiftchoicebuttondisabledbig');
}
//Function to Enable the Attach card button--------------------------------------------------------------------------------
function disableattachcard()
{
	$('#attachcard').attr('disabled',true);
	$('#attachcard').addClass('swiftchoicebuttondisabledbig');
	$('#attachcard').removeClass('swiftchoicebuttonbig');
}
function reg_cardsearch(e)
{ 
var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		reg_scrollcard('up');
	else if(KeyID == 40)
		reg_scrollcard('down');
	else
	{
		var form = $('#registrationform');
		var input = $('#searchscratchnumber').val();
		reg_selectacard(input);
	}
}

function reg_scrollcard(type)
{
	var selectbox = $('#scratchcardlist');
	var totalcus = $("#scratchcardlist option").length;
	var selectedcus = $("select#scratchcardlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#scratchcardlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#scratchcardlist").attr('selectedIndex', selectedcus + 1);
	reg_selectcardfromlist();
}

function reg_selectacard(input)
{
	var selectbox = $('#scratchcardlist');
	
	if(input == "")
	{
		getregcardlist();
	}
	else
	{
		var initiallength = selectbox.length;
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
	
		var addedcount = 0;
		for( var i=0; i < regcardarray.length; i++)
		{
			
			if(input.charAt(0) == ".")
			{
				withoutdot = input.substring(1,input.length);
				pattern = new RegExp("^" + withoutdot.toLowerCase());
				comparestringsplit = regcardarray[i].split("^");
				comparestring = comparestringsplit[1];
			}
			else
			{
				pattern = new RegExp("^" + input.toLowerCase());
				comparestring = regcardarray[i];
			}
			var result1 = pattern.test(trimdotspaces(regcardarray[i]).toLowerCase());
			var result2 = pattern.test(regcardarray[i].toLowerCase());
			if(result1 || result2)
			{
				var splits = regcardarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
			}
		}
	}
}
//Function to enable the Detach card button--------------------------------------------------------------------------------
function enabledetachcard()
{
	$('#detachcard').attr('disabled',false);
	$('#detachcard').addClass('swiftchoicebuttonbig');
	$('#detachcard').removeClass('swiftchoicebuttondisabledbig');
}

//Function to Disable the Detach card button--------------------------------------------------------------------------------
function disabledetachcard()
{
	$('#detachcard').attr('disabled',true);
	$('#detachcard').addClass('swiftchoicebuttondisabledbig');
	$('#detachcard').removeClass('swiftchoicebuttonbig');
}