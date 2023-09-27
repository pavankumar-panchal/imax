// JavaScript Document
function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');

	var field1 = $('input[name=geography]:checked').val();
	if(field1 == 'region') 
	{ 
		var field = $('#region'); if(!field.val()) { error.html(errormessage('Select A Region.')); field.focus(); return false; }
	}
	if(field1 == 'district') 
	{
		var field = $('#district'); if(!$('#state').val()) { error.html(errormessage('Select A State First.')); $('#state').focus(); return false; } if(!field.val()) { error.html(errormessage('Select A District.')); field.focus(); return false; } 
	}
	if(field1 == 'state') 
	{ 
		var field = $('#state'); if(!field.val()) { error.html(errormessage('Select A State.')); field.focus(); return false; } 
	}
	var field = $('#DPC_fromdate');
	if(!field.val()) { error.html(errormessage("Enter the From Date.")); field.focus(); return false; }
	var field = $('#DPC_todate');
	if(!field.val()) { error.html(errormessage("Enter the To Date.")); field.focus(); return false; }
	else
	{
		if(command == 'view')
		{
			error.html('');
			$('#submitform').attr("action", "../reports/productshippedreport.php?id=view") ;
			$('#submitform').attr( 'target', '_blank' );
			$('#submitform').submit();
		}
		else
		{
			error.html('');
			$('#submitform').attr("action", "../reports/productshippedreport.php?id=toexcel") ;
			$('#submitform').submit();
		}
	}
}


function enablegeography()
{
	var form = $('#submitform');
	var error = $('#form-error');
	
	var geography = $('input[name=geography]:checked').val();
	if(geography == 'all')
	{
		$('#region').attr('disabled', true)
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#state').attr('disabled', true)	
		$('#district').attr('disabled', true)	
		$('#regiondiv').hide();
		$('#statediv').hide();
		$('#districtdiv').hide();
	}
	if(geography == 'region')
	{
		$('#region').attr('disabled', false)
		$('#state').attr('disabled', true)
		$('#district').attr('disabled', true)
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').show();
		$('#statediv').hide();
		$('#districtdiv').hide();
	}
	if(geography == 'state')
	{
		$('#region').attr('disabled', true)
		$('#state').attr('disabled', false)
		$('#district').attr('disabled', true)
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').hide();
		$('#statediv').show();
		$('#districtdiv').hide();
	}
	if(geography == 'district')
	{
		$('#region').attr('disabled', true)
		$('#state').attr('disabled', false)
		$('#district').attr('disabled', false)
		$('#region').val(''); $('#state').val(''); $('#district').val('');
		$('#regiondiv').hide();
		$('#statediv').show();
		$('#districtdiv').show();
	}
}

function enablecustomersearch()
{
	var form = $('#submitform');
	var error = $('#form-error');
	
	var customerselection = $('input[name=customerselection]:checked').val();
	if(customerselection == 'allcustomer')
	{
		$('#searchtextid').attr('disabled',true);
		$('#searchtextid').val('');
		$('#searchtext').attr('disabled',true);
		$('#searchtext').val('');
	}
	else
	{
		$('#searchtextid').attr('disabled',false);
		$('#searchtext').attr('disabled',false);
	}
}
