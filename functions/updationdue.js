// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#productgroup');
	var prdvalues = validateproductcheckboxes();
	if(!prdvalues)	{error.html(errormessage("Select a Product"));return false;	}
	else
	{
		if(command == 'view')
		{
			error.html('');
			$('#submitform').attr("action", "../reports/excelupdationreport.php?id=view") ;
			$('#submitform').attr( 'target', '_blank' );
			$('#submitform').submit();
		}
		else
		{
			error.html('');
			$('#submitform').attr("action", "../reports/excelupdationreport.php?id=toexcel") ;
			$('#submitform').submit();
		}
	}
}


function validateproductcheckboxes()
{
var chksvalue = $("input[name='productgroup[]']");
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


