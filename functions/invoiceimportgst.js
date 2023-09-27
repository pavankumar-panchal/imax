// JavaScript Document
function formsubmit(command)
{
	
	var form = $('#submitform');
	var error = $('#form-error');

	
	//new invoice import code
	var field = $('#frominvoicetype');
	if(!field.val()) { error.html(errormessage("Select the From Invoice Generated Type.")); field.focus(); return false; }
	if($('#frominvoicetype').val() == 'R' || $('#frominvoicetype').val() == 'D')
	{
		var field = $('#fromstatetype');
		if(!field.val()) { error.html(errormessage("Select the From State Type (L or I).")); field.focus(); return false; }
	}
	var field = $('#frominvoicenonew');
	if(!field.val()) { error.html(errormessage("Enter the From Invoice No.")); field.focus(); return false; }
	var field = $('#toinvoicetype');
	if(!field.val()) { error.html(errormessage("Select the To Invoice Generated Type.")); field.focus(); return false; }
	if($('#toinvoicetype').val() == 'R' || $('#toinvoicetype').val() == 'D')
	{
		var field = $('#tostatetype');
		if(!field.val()) { error.html(errormessage("Select the To State Type (L or I).")); field.focus(); return false; }
	}
	var field = $('#toinvoicenonew');
	if(!field.val()) { error.html(errormessage("Enter the To Invoice No.")); field.focus(); return false; }
	// empty field check ends for new
	

	//validation for new
		if($('#frominvoicetype').val() != $('#toinvoicetype').val())
	{ error.html(errormessage("From Invoice Type and To Invoice Type should be same.")); field.focus(); return false; }
	
		if($('#fromstatetype').val() != $('#tostatetype').val())
	{ error.html(errormessage("From State Type and To State Type should be same.")); field.focus(); return false; }
	
	if($('#toinvoicenonew').val() < $('#frominvoicenonew').val())
	{ error.html(errormessage("From Invoice No should be less than To Invoice No .")); field.focus(); return false; }
	else
	{
		error.html('');
		$('#submitform').attr("action","../reports/excelinvoiceimportgst.php?id=toexcel");
		$('#submitform').submit();
	}
}



function resetfunc()
{
	var form = $('#submitform');
	$('#form-error').html('');
	$('#submitform')[0].reset();
}




