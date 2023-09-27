function formsubmit(command)
{
	var form = document.submitform;
	var field = form.fromdate;
	if(!field.value) { error.innerHTML = errormessage("Enter the From Date."); field.focus(); return false; }
	var field = form.todate;
	if(!field.value) { error.innerHTML = errormessage("Enter the To Date."); field.focus(); return false; }
	else
	{
		if(command == 'view')
		{
			form.action = "../reports/excelsalessummaryreport.php?id=view";
			form.target = "_blank";
			form.submit();	
		}
		else
		{
			form.action = "../reports/excelsalessummaryreport.php?id=toexcel";
			form.submit();	
		}
	}
}



