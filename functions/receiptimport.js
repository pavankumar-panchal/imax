// JavaScript Document
function filter(startlimit)
{
	var startlimit = '';
	var importvalue = $("input[name='import']:checked").val();
	var region = $("#region option:selected").val();
	
	var passData = "switchtype=searchfilter&startlimit="+ encodeURIComponent(startlimit) + "&import=" + encodeURIComponent(importvalue) + "&region=" +encodeURIComponent(region)  + "&generatedby=" +encodeURIComponent($("#generatedby").val()) + "&dummy=" + Math.floor(Math.random()*100000000);
	$('#form-error').html(getprocessingimage());
	var queryString = "../ajax/receiptimport.php";
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
				$('#form-error').html('');	
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('div#tabgroupgridc1').css("height", "");
					$('#filterdiv').hide();
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					$('#tabgroupgridwb1').html("(Total Count :  " + response[2] +")");
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		
	});	
	
}

function getmoreinvoicedetails(startlimit,slnocount,showtype)
{
	var importvalue = $("input[name='import']:checked").val();
	var region = $("#region option:selected").val();
	
	var passData = "switchtype=searchfilter&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&import=" + encodeURIComponent(importvalue) + "&region=" +encodeURIComponent(region)  + "&generatedby=" +encodeURIComponent($("#generatedby").val()) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/receiptimport.php";
	$('#form-error').html(getprocessingimage());
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
				$('#form-error').html('');	
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#tabgroupgridc1link').html(response[3]);
					
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});		

}
function formsubmit(command)
{
	
	var form = $('#submitform');
	if(!$('input[type="checkbox"]:checked').is(':checked'))
	{
	  alert("Please select at least one checkbox.");
	  return false;
	}
	else
	{
	  var slno = $('input[name=slno]:checked').val();
	}
	 
	
	$('#submitform').attr("action","../reports/excelreceiptimport.php?id=toexcel");
	$('#submitform').submit();
	
}



function resetfunc()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
}

function selectchk()
{
		var chkvalues = $("input[name='slno[]']");
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}
			var var1 = $('#groupvalue').val().split('%');
			for( var j=0; j<var1.length; j++)
			{
				if($('#chk').is(':checked'))
					$(chkvalues[i]).attr('checked',true);
				
			}
		}
}