// JavaScript Document
var regcardarray = new Array();


function refreshcusattachcardarray()
{
	var passData = "switchtype=getcardlist&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData)
	queryString = "../ajax/cusattachcard.php";
	$('#scratchcradloading').html(getprocessingimage());
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
				$('#scratchcradloading').html('');
			}
		}, 
		error: function(a,b)
		{
			$("#gridprocessing").html(scripterror());
		}
	});	
}

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

function reg_selectacard(input) {
	var selectbox = $('#scratchcardlist');
	if (input == "") {
		getregcardlist();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < regcardarray.length; i++) {
			// Check if any part of the name contains the input string
			if (regcardarray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = regcardarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if (addedcount == 100) break;
			}
		}
	}
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

function scratchdetailstoform(cardid)
{
	if(cardid != '')
	{
		enableattachcard();
		var passData = "switchtype=scratchdetailstoform&cardid=" + encodeURIComponent(cardid) + "&dummy=" + Math.floor(Math.random()*100032680100); //alert(passData)
		$('scratchcradloading').html(getprocessingimage());
		var queryString = "../ajax/cusattachcard.php";
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
					}
				}
			}, 
			error: function(a,b)
			{
				$("#scratchcradloading").html(scripterror());
			}
		});	
	}
}
//Function to enable the Attach card button--------------------------------------------------------------------------------
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