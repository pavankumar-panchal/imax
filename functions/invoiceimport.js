// JavaScript Document
function formsubmit(command)
{
	
	var form = $('#submitform');
	var error = $('#form-error');
	var field = $('#fromregiontype');
	if(!field.val()) { error.html(errormessage("Select the From Region Type.")); field.focus(); return false; }
	var field = $('#frominvoiceno');
	if(!field.val()) { error.html(errormessage("Enter the From Invoice No.")); field.focus(); return false; }
	var field = $('#toregiontype');
	if(!field.val()) { error.html(errormessage("Select the To Region Type.")); field.focus(); return false; }
	var field = $('#toinvoiceno');
	if(!field.val()) { error.html(errormessage("Enter the To Invoice No.")); field.focus(); return false; }
	if($('#fromregiontype').val() != $('#toregiontype').val())
	{ error.html(errormessage("From Region Type and To Region Type should be same.")); field.focus(); return false; }
	
	if($('#toinvoiceno').val() < $('#frominvoiceno').val())
	{ error.html(errormessage("From Invoice No should be less than To Invoice No .")); field.focus(); return false; }
	else
	{
		error.html('');
		$('#submitform').attr("action","../reports/excelinvoiceimport.php?id=toexcel");
		$('#submitform').submit();
	}
}



function resetfunc()
{
	var form = $('#submitform');
	$('#form-error').html('');
	$('#submitform')[0].reset();
}




