//Function to Search the data from Inventory------------------------------------------
function searchfilter(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');
	$('#tabgroupgridc2link').html('');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	
	var passData = "switchtype=searchactivity&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) +"&databasefield=" + encodeURIComponent(subselection) + "&modulename=" + encodeURIComponent($("#modulename").val()) + "&username=" +encodeURIComponent($("#username").val()) + "&textfield=" +encodeURIComponent(textfield) +"&eventtype=" +encodeURIComponent($("#eventtype").val()) + "&pageshortname=" +encodeURIComponent($("#pageshortname").val()) + "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData)
	$('#tabgroupgridc1_2').html(getprocessingimage());
	var queryString = "../ajax/activitylog.php";
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
				error.html('') ;
				$('#tabgroupgridc1_2').html('');
				gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#filterdiv').hide();
					$('#tabgroupgridwb2').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_2').html(response[1]);
					$('#tabgroupgridc2link').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#form-error').html(errormessage(response[1]));
				}
				else
				{
					$('#tabgroupgridc1_2').html("No datas found to be displayed.");
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
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	
	
	var passData = "switchtype=searchactivity&fromdate=" + encodeURIComponent(fromdate) + "&todate=" + encodeURIComponent(todate) + "&startlimit=" + encodeURIComponent(startlimit) +"&databasefield=" + encodeURIComponent(subselection) + "&modulename=" + encodeURIComponent($("#modulename").val()) + "&username=" +encodeURIComponent($("#username").val()) + "&textfield=" +encodeURIComponent(textfield) +"&eventtype=" +encodeURIComponent($("#eventtype").val()) + "&pageshortname=" +encodeURIComponent($("#pageshortname").val())+ "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000); 

	$('#tabgroupgridc2link').html(getprocessingimage());
	var queryString = "../ajax/activitylog.php";
	ajaxcall4 = $.ajax(
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
				error.html('');
				$('#tabgroupgridc2link').html('');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#tabgroupgridwb2').html("Total Count :  " + response[2]);
					$('#resultgrid').html($('#tabgroupgridc1_2').html());
					$('#tabgroupgridc1_2').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#tabgroupgridc2link').html(response[3]);
					
				}
				else if(response[0] == '2')
				{
					$('#form-error').html(errormessage(response[1]));
				}
				else
				{
					$('#tabgroupgridc1_2').html("No datas found to be displayed.");
				}
				
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});
}

function filtertoexcel(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	//reverse the date string from dd-mm-yyyy format to yyyy-mm-dd format
	var fromdatepieces = fromdate.split('-');
	fromdatepieces.reverse();
	var fromdatereversed = fromdatepieces.join('-');
	
	var todatepieces = todate.split('-');
	todatepieces.reverse();
	var todatereversed = todatepieces.join('-');
	
	var start = new Date(fromdatereversed);
	var end =new Date(todatereversed);
	var datediff = new Date(end - start);
	var noofdays = datediff/1000/60/60/24;
	if(noofdays > 6)
	{$('#form-error').html(errormessage('Date limit should be within 7 days')); return false;}
	error.html(getprocessingimage());
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../ajax/activitylogtoexcel.php") ;
		$('#submitform').submit();
	}
}





//Function to reset the from to the default value-Meghana[21/12/2009]
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
	
 	oForm.reset();
	$("#filter-form-error").html('');
	for (i=0; i<elements.length; i++) 
	{
		field_type = elements[i].type.toLowerCase();
	}
	
	switch(field_type)
	{
	
		case "text": 
			elements[i].value = ""; 
			break;
		case "radio":
			if(elements[i].checked == 'databasefield1')
			{
				elements[i].checked = true;
			}
			else
			{
				elements[i].checked = false; 
			}
			break;
		case "checkbox":
  			if (elements[i].checked) 
			{
   				elements[i].checked = true; 
			}
			break;
		case "select-one":
		{
  			 for (var k=0, l=oForm.elements[i].options.length; k<l; k++)
			 {
				 oForm.elements[i].options[k].selected = oForm.elements[i].options[k].defaultSelected;
			 }
				
		}
		break;

		default:$("#districtcodedisplaysearch").html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>') ;
			
	}
}


function changedateformat(date)
{
	if(date != "0000-00-00")
	{
		if(indexOf(date, " "))
		var result = split(" ",date);
		else
		var result = split("[:./-]",date);
		var date = result[2]+"-"+result[1]+"-"+result[0];
	}
	else
	{
		var date = "";
	}
	return date;
}

function gettodaysresult(startlimit)
{
	var error = $('#form-error');
	var form = $('#submitform');
	gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Activity');
	$('#tabgroupgridc1link').html('');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	
	var passData = "switchtype=todayactivity&startlimit=" + encodeURIComponent(startlimit)  + "&dummy=" + Math.floor(Math.random()*1000782200000); //alert(passData)
	$('#tabgroupgridc1_1').html(getprocessingimage());
	var queryString = "../ajax/activitylog.php";
	ajaxcall56 = $.ajax(
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
				error.html('') ;
				$('#tabgroupgridc1_1').html('');
				gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Activity');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#filterdiv').hide();
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
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
			error.html(scripterror());
		}
	});
}

function getmoretodaysresult(startlimit,slnocount,showtype)
{
	var error = $('#form-error');
	var form = $('#submitform');
	var fromdate = $('#DPC_fromdate').val();
	var field = $('#DPC_fromdate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	var todate = $('#DPC_todate').val();
	var field = $('#DPC_todate');
	if(!field.val()) {$('#form-error').html(errormessage('Enter the Date.'));field.focus(); return false; }
	error.html('');
	
	var passData = "switchtype=todayactivity&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000); 

	$('#tabgroupgridc1link').html(getprocessingimage());
	var queryString = "../ajax/activitylog.php";
	ajaxcall569 = $.ajax(
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
				error.html('');
				$('#tabgroupgridc1link').html('');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#searchresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
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
			error.html(scripterror());
		}
	});
}

