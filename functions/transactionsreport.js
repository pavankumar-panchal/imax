// JavaScript Document
function customeridfivedigit(fieldvalue)
{
	 if(fieldvalue.length != 5)
		return false;
	else if(isNaN(fieldvalue))
		return false;
	else
		return true;
}

function formsubmit(command)
{
	var form = $('#submitform');
	var error = $('#form-error');

	var field = $('#searchref');
	var field1 =  $('input[name=searchref]:checked').val();
	
	var field = $('#DPC_surrendertodate');
	if(!field.val()) { error.html(errormessage("Enter the Surrender To Date.")); field.focus(); return false; }
	
	var field = $('#DPC_surrenderfromdate');
	if(!field.val()) { error.html(errormessage("Enter the Surrender From Date.")); field.focus(); return false; }
	
	/*var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); return false;	}*/
	else
	{
		if(command == 'view')
		{
			error.html('');
			$('#submitform').attr("action", "../reports/exceltransactionsreport.php?id=view") ;
			$('#submitform').attr( 'target', '_blank' );
			$('#submitform').submit();
		}
		else
		{
			error.html('');
			$('#submitform').attr("action", "../reports/exceltransactionsreport.php?id=toexcel") ;
			$('#submitform').submit();
		}
	}
}


function enablesearchref()
{
	var form = $('#submitform');
	var error = $('#form-error');
	
	var searchref = $('input[name=searchref]:checked').val();
	if(searchref == 'all')
	{
		$('#searchrefresult').val('all');
		$('#searchinput').val('');
		$('#maxcountdiv').hide();
		$('#searchdiv').hide();
	}
	if(searchref == 'refslno')
	{
		$('#searchrefresult').val('refslno');
		$('#searchtextheader').html('Enter Reference slno ');
		$('#searchinput').val('');
		$('#maxcount').val('');
		$('#maxcountdiv').hide();
		$('#searchdiv').show();
	}
	if(searchref == 'businessname')
	{
		$('#searchrefresult').val('businessname');
		$('#searchtextheader').html('Enter Business Name ');
		$('#searchinput').val('');
		$('#maxcount').val('');
		$('#maxcountdiv').hide();
		$('#searchdiv').show();
	}
	if(searchref == 'customerid')
	{
		$('#searchrefresult').val('customerid');
		$('#searchtextheader').html('Enter Customer ID <font color="red">(last 5 digit)</font>');
		$('#searchinput').val('');
		$('#maxcount').val('');
		$('#maxcountdiv').hide();
		$('#searchdiv').show();
	}
	if(searchref == 'cardid')
	{
		$('#searchrefresult').val('cardid');
		$('#searchtextheader').html('Enter Card ID');
		$('#searchinput').val('');
		$('#maxcount').val('');
		$('#maxcountdiv').hide();
		$('#searchdiv').show();
	}
	if(searchref == 'maxcount')
	{
		$('#searchrefresult').val('maxcount');
		$('#maxcounttextheader').html('Enter Count Number');
		$('#searchinput').val('');
		$('#searchdiv').hide();
		$('#maxcountdiv').show();
	}

}

function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productname[]']");
	if(showtype == 'one')
	{
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}
			if( $('#selectproduct').val() == 'ALL')
				$(chkvalues[i]).attr('checked',true);
			else if(selectproduct.value == 'NONE')
				$(chkvalues[i]).attr('checked',true);
			else if(chkvalues[i].getAttribute('producttype') == $('#selectproduct').val())
			{
				$(chkvalues[i]).attr('checked',true);
				$('#groupvalue').val('');
			}
		}
	}
	else if(showtype == 'more')
	{

		var addproductvalue = $("#selectproduct option:selected").val();
		if($('#groupvalue').val() == '')
			$('#groupvalue').val($('#groupvalue').val() +  addproductvalue);
		else
			$('#groupvalue').val($('#groupvalue').val() + '%' +  addproductvalue);
		for (var i=0; i < chkvalues.length; i++)
		{
			if($(chkvalues[i]).is(':checked'))
			{
				$(chkvalues[i]).attr('checked',false);
			}

			var var1 = $('#groupvalue').val().split('%');
			for( var j=0; j<var1.length; j++)
			{
				if($('#selectproduct').val() == 'ALL')
					$(chkvalues[i]).attr('checked',true);
				else if($('#selectproduct').val() == 'NONE')
					$(chkvalues[i]).attr('checked',false);
				if(chkvalues[i].getAttribute('producttype') == var1[j])
				{
					$(chkvalues[i]).attr('checked',true);
				}
			}
		}
	}
}


function validateproductcheckboxes()
{
	var chksvalue = $("input[name='productname[]']");
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
