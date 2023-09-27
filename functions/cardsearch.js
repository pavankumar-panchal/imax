// JavaScript Document

function searchfilter(startlimit)
{
	var form = $('#submitform');
	var textfield = $('#searchcriteria').val();
	var subselection = $('input[name=databasefield]:checked').val();
	var orderby = $('#orderby').val();
	var scheme = $('#scheme').val();
	var error = $('#filter-form-error');
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $('#searchcriteria').focus(); return false; }
	var passData = "switchtype=cardsearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby)+ "&scheme=" + encodeURIComponent(scheme) + "&startlimit=" + encodeURIComponent(startlimit)+ "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	var queryString = "../ajax/cardsearch.php";
	error.html(getprocessingimage());
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
					error.html('');
					$('#tabgroupgridc3_1').html(response[1]);
					//document.getElementById('gridcount').innerHTML = "Total Count :  " + response[1];
					$('#tabgroupgridc1linksearch').html(response[3]);
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
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
	var form = $('#submitform');
	var textfield = $('#searchcriteria').val();
	var subselection = $('input[name=databasefield]:checked').val();
	var orderby = $('#orderby').val();
	var scheme = $('#scheme').val();
	var error = $('#filter-form-error');
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $('#searchcriteria').focus(); return false; }
	var passData = "switchtype=cardsearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby)+ "&scheme=" + encodeURIComponent(scheme) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) +  "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/cardsearch.php";
	$('#tabgroupgridc1linksearch').html(getprocessingimage());
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
				//document.getElementById('gridcount').innerHTML = "Total Count :  " + response[1];
				if(response[0] == '1')
				{
					$('#searchresultgrid').html($('#tabgroupgridc3_1').html());
					$('#tabgroupgridc3_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
					$('#tabgroupgridc1linksearch').html(response[3]);
				}
				else
				{
					error.html(errormessage('Unable to connect.'));
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function clearsearchform()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#filter-form-error').html('');
	$('#tabgroupgridc3_1').html('');
	$('#tabgroupgridc1linksearch').html('');
}
