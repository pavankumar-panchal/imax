// JavaScript Document

var cardarray = new Array();
refreshcardarray();
var t;

keeprefreshingcard();

function keeprefreshingcard()
{
	t=setTimeout("refreshcardarray();",3000000000);	
	refreshcardarray();
}


function refreshcardarray()
{
	var form = $('#cardsearchfilterform');
	var passData = "type=getcardlist&dummy=" + Math.floor(Math.random()*10054300000);
	var ajaxcall2 = createajax();
	queryString = "../ajax/transfercards.php";
	ajaxcall2.open("POST", queryString, true);
	ajaxcall2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall2.onreadystatechange = function()
	{
		if(ajaxcall2.readyState == 4)
		{
			if(ajaxcall2.status == 200)
			{
				var response = ajaxcall2.responseText.split('^*^');
				cardarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray[i] = response[i];
				}
				getcardlist();
			}
			else
				$('#cardselectionprocess').html(scripterror());
		}
	}
	ajaxcall2.send(passData);
}

function getcardlist()
{	
	var form = $('#cardsearchfilterform');
	var selectbox = $('#cardlist');
	var numberofcards = cardarray.length;
	$('#cardsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcards > actuallimit)?actuallimit:numberofcards;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	
	for( var i=0; i<limitlist; i++)
	{
		var splits = cardarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);

	}
}




function selectcardfromlist()
{
	var selectbox = $("#cardlist option:selected").val();
	$('#cardsearchtext').val($("#cardlist option:selected").text());
	$('#cardsearchtext').select();
	carddetailstoform(selectbox);
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
			if(pattern.test(comparestring.toLowerCase()))
			{
				var splits = cardarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if(addedcount == 100)
					break;
				//selectbox.options[0].selected= true;
				//carddetailstoform(selectbox.options[0].value); //document.getElementById('delaerrep').disabled = true;
				//document.getElementById('hiddenregistrationtype').value = 'newlicence'; clearregistrationform(); validatemakearegistration(); 
			}
			//i=1234567;
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
		var input = $('#cardsearchtext').val();;
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
	$('#displaycardnumber').html('');
	$('#displayscratchno').html('');
	$('#displaypurchasetype').html('');
	$('#displayusagetype').html('');
	$('#displayattachedto').html('');
	$('#displayattachdate').html('');
	$('#displayregisteredto').html('');
	$('#displayregisterdate').html('');
	$('#displaycardremarks').html('');
	$('#displaycardstatus').html('');
	$('#cardselectionprocess').html('');
}

function newentry()
{
	
	var form = $('#cardsearchfilterform');
	$('#form-error').html('');
	$('#scratchnumber').value = '';
	$('#remarks').value = '';
	$('#cardattached').html('');
	$('#cardregistered').html('');
	$('#cardstatus').html('');
	$('#cardsearchtext').value = '';
	$('#cardslist').value = '';
}


