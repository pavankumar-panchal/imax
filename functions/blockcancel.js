// JavaScript Document

var cardarray = new Array();
//refreshcardarray();
/*var t;

keeprefreshingcard();

function keeprefreshingcard()
{
	t=setTimeout("refreshcardarray();",3000000000);	
	//refreshcardarray();
}*/


function formsubmit(changetype)
{
	
	var form = $('#cardsearchfilterform');
	var error = $('#form-error');
	var field = $('#remarks');
	var field = $('#scratchnumber');
	var field = $('#pinremarksstatus');
	var actionfield = $('input[name=actiontype]:checked').val();
	if(actionfield == 'block')
	{
		var cardregistered = $('#cardregistered').html();
	if(cardregistered == 'yes')
	{ error.html(errormessage('As the card is registered so cannot be blocked.')); field.focus(); return false; }
		else
		var changetype = 'blockcard';
	}
	
	else if(actionfield == 'cancel')
	{
	var cardregistered = $('#cardregistered').html();
	if(cardregistered == 'no')
	{ 
		error.html(errormessage('As the card is not registered so cannot be cancelled.')); field.focus(); return false; }
		else
		 changetype = 'cancelcard';
	}
	
	else 
	 changetype = 'none';
	if(!field.val()) { error.html(errormessage('Select the Scratch card details.')); field.focus(); return false; }
	else
	{
		var passData = '';
		passData = "&type=" + encodeURIComponent(changetype) + "&scratchnumber=" + encodeURIComponent($('#scratchnumber').val()) + "&pinremarksstatus=" + encodeURIComponent($('#pinremarksstatus').val()) + "&remarks="+ encodeURIComponent($('#remarks').val()) ;//alert(passData);
		queryString = '../ajax/blockcancel.php';
		error.html(getprocessingimage());
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
					var responsesplit = ajaxresponse.split('^');
					if(responsesplit[0] == '1')
					{
						error.html(successmessage(responsesplit[1]));
						$('#scratchnumber').val('');
						$('#pinremarksstatus').val('');
						griddata();
					}
					else
					{
						error.html(errormessage('Unable to Connect...' + response));
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



/*function refreshcardarray()
{
	var form = $('#cardsearchfilterform');
	var passData = "type=getcardlist&dummy=" + Math.floor(Math.random()*10054300000);
	$('#cardselectionprocess').html(getprocessingimage());
	queryString = "../ajax/blockcancel.php";
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
				
				var response = ajaxresponse;
				cardarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					cardarray[i] = response[i];
					$('#cardselectionprocess').html('');
				}
				getcardlist();
			}
		}, 
		error: function(a,b)
		{
			$("#cardselectionprocess").html(scripterror());
		}
	});	
}
*/
/*function getcardlist()
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
}*/


function carddetailstoform()
{
	$('#form-error').html('');
	var error = $('#form-error');
	var form = $('#cardsearchfilterform');
	var field = $.trim($('#cardid').val());
	//alert(field);
	if(!field)
	{
		var field1 = $('#pinno');
		if(!field1.val()) 
		{ 
		  alert("Enter the Card/Pin Number."); 
		  $('#pinno').focus(); 
		  return false; 
		}
	}
	else
	{
		if(!IsNumeric(field))
		{
			alert("Enter only digits.");
			//$('#cardid').val('');
			newentry();
			$('#pinno').focus();
			return false;
		}
	}
	//	form.reset();
		var passData = "type=carddetailstoform&pinno=" + encodeURIComponent($("#pinno").val()) 
		+ "&cardid=" + encodeURIComponent($("#cardid").val())
		+ "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#cardselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/blockcancel.php";
		error.html(getprocessingimage());
		ajaxcall23 = $.ajax(
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
					$("#form-error").html('');
					var response = ajaxresponse; //alert(response)
					if(response['nodata'] == "no data")
					{
						alert("Enter valid Pin");
						//$('#pinno').val('');
						newentry();
					}
					else
					{
						
						$('#cardlist').val(response['cardid']);
						$('#cardsearchtext').val(response['scratchnumber'] + ' | ' + response['cardid']);
						$('#cardselectionprocess').html('');
						$('#scratchnumber').val(response['cardid']);
						$('#remarks').val(response['remarks']);
						$('#cardattached').html(response['attached']);
						$('#cardregistered').html(response['registered']);
						//if(response['pinstatusremarks']!= "")
						$('#pinremarksstatus').val(response['pinstatusremarks']);
						if(response['blocked'] == 'yes')
							$('#actiontype1').prop("checked", true); 
						else
							$('#actiontype1').prop("checked", false); 
							
						if(response['cancelled'] == 'yes')
							$('#actiontype2').prop("checked", true);
						else
							$('#actiontype2').prop("checked", false);
							
						if(response['none'] == 'yes')
							$('#actiontype0').prop("checked", true);
							
						$('#cardstatus').html(response['cardstatus']);
					}
				}
			}, 
			error: function(a,b)
			{
				$("#form-error").html(scripterror());
			}
		});
	
}

function uploadpins()
{
	$("#submitform").on('submit',(function(e) {
		e.preventDefault();
		var bar = $('.bar');
		var percent = $('.percent');
		var status = $('#status');
		var data1=new FormData(this);
		data1.append('type', 'checkuploaddata');
		$.ajax({
			url: "../ajax/blockcancel.php",
			type: "POST",
			data: data1,
			contentType: false,
			enctype: 'multipart/form-data',
			//dataType: json,
			cache: false,
			processData:false,

		target:   '#targetLayer', 
				beforeSubmit: function() {
				status.empty();
					var percentVal = '0%';
					bar.width(percentVal)
					percent.html(percentVal);
				},
				uploadProgress: function (event, position, total, percentComplete){	
					var percentVal = percentComplete + '%';
					bar.width(percentVal)
					percent.html(percentVal);
				},

			success: function(response)
			{
			// var data = [tabledata];
				//alert("success");
				
				var ajaxresponse = response.split('^');
				//alert(ajaxresponse[0]);
				if(ajaxresponse[0] == 1)
				{
					// alert(ajaxresponse[2]);
					$('#uploadfile').val('');
					bar.width("100%");
					percent.html("100%");
					$('#pinscountid').html("Total Count :" + ajaxresponse[2]);
					//var res = jQuery.unique(ajaxresponse[1]);
					$("#tabgroupgridc1_1").html(ajaxresponse[1]);
					$('#griddata').DataTable();
					alert("Pins are blocked.");
					//$('#resultgrid').html($('#tabgroupgridc1_1').html());
					//$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
		},
		complete: function(xhr) {
		//status.html(xhr.responseText);
		},
		error: function(data)
		{
			alert("error");
			//console.log(data);
		}
		});
		e.stopImmediatePropagation();
    	return false;
		//$('#filter').one('click', clickHandler);
	}));
}

function griddata()
{
	var passData = "type=griddata&cardid=" + $("#cardid").val() +
	"&pinno=" + $("#pinno").val() +
	"&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/blockcancel.php";
	
	ajaxcall24 = $.ajax(
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
					
					//alert(response[1]);
					$('#tabgroupgridc3_1').html(response[1]);
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
				}
			}
		}
	});
}

/*function displaycard(command,card)
{
	//alert(command);
	if(card != '')
	{
		cardcountdetails(command,card);
		$("").colorbox({ inline:true, href:"#cardgrid", onLoad: function() { $('#cboxClose').hide()}});
	}
	else
	{
		return false;
	}
	
}

function cardcountdetails(command,card)
{
	if(command == "active")
	{
		//alert("active");
		var passData = "type=active&cardno=" + card ;
	}
	else if(command == "block")
	{
		var passData = "type=block&cardno=" + card ;
	}
	else if(command == "cancel")
	{
		var passData = "type=cancel&cardno=" + card ;
	}
	
	queryString = "../ajax/blockcancel.php";
	ajaxcall25 = $.ajax(
	{
		type :"POST",data: passData,url:queryString,cache:false,
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
					$("#cardgridc7").html(response[1]);
				}
				//else{ $("#surrendergridc7").html(scripterror()); }
			}
		}
	});
}*/
/*function selectcardfromlist()
{
	var selectbox = $("#cardlist option:selected").val();
	$('#cardsearchtext').val($("#cardlist option:selected").text());
	$('#cardsearchtext').select();
	carddetailstoform(selectbox);
}*/

/*function selectacard(input)
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
}*/
/*function cardsearch(e)
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
}*/

/*function scrollcard(type)
{
	var selectbox = $('#cardlist');
	var totalcus = $("#cardlist option").length;
	var selectedcus = $("select#cardlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#cardlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#cardlist").attr('selectedIndex', selectedcus + 1);
	selectcardfromlist();
}*/

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
	$('#scratchnumber').val('');
	$('#remarks').val('');
	$('#cardattached').html('');
	$('#cardregistered').html('');
	$('#cardstatus').html('');
	$('#cardsearchtext').val('');
	$('#pinno').val('');
	$('#cardid').val('');
	$('#tabgroupgridc3_1').html('');
	$('#pinremarksstatus').val('');
	
}


