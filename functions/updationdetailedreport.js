// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#group');
	if(!field.val())	{error.html(errormessage("Select a Group")); field.focus(); return false;	}
	var summarizevalues = validateproductcheckboxes();
	if(!summarizevalues)	{error.html(errormessage("Select at least one option to Summarize"));  return false;	}
	else
	{
		error.html('');
		$('#submitform').attr("action", "../reports/excelupdationdetailedreport.php") ;
		$('#submitform').submit();
	}
}


function validateproductcheckboxes()
{
var chksvalue = $("input[name='summarize[]']");
var hasChecked = false;
for (var i = 0; i < chksvalue.length; i++)
{
	if ($(chksvalue[i]).is(':checked'))
	{
		hasChecked = true;
		return true
	}
}
	if (!hasChecked)
	{
		return false
	}
}



