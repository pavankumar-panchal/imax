// JavaScript Document
function formsubmit(command)
{
	
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field = form.region;
	var field = form.dealerid;
	var field = form.todate;
	var values = validateproductcheckboxes();
	if(values == false)	{error.innerHTML = errormessage("Select A Product"); field.focus(); return false;	}
	if(!field.value) { error.innerHTML = errormessage("Enter the From Date."); field.focus(); return false; }
	var field = form.todate;
	if(!field.value) { error.innerHTML = errormessage("Enter the To Date."); field.focus(); return false; }
	else
	{
		if(command == 'view')
		{
			form.action = "../reports/viewcontactdetails.php";
			form.target = "_blank";
			form.submit();	
		}

		else
		{
			form.action = "../reports/contactdetailsreport.php";
			//form.target = "_blank";
			form.submit();	
		}
	}
}

function selectdeselectall()
{
	var selectall = document.getElementById('selectall');
	var chkvalues = document.getElementsByName('productname[]');
	var changestatus = (selectall.checked == true)?true:false;
	for (var i=0; i < chkvalues.length; i++)
	{
		chkvalues[i].checked = changestatus;
	}
}

function validateproductcheckboxes()
{
var chks = document.getElementsByName('productname[]');
var hasChecked = false;
for (var i = 0; i < chks.length; i++)
{
	if (chks[i].checked)
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


function enabledisablereregistartion()
{
	var form = document.submitform;
	if(form.reregenable.checked == false)
	{
		form.fromdate.disabled = true;	
		form.todate.disabled = true;
		form.rereg.disabled = true;
		document.getElementById('DPC_fromdate').className = 'diabledatefield';
		document.getElementById('DPC_todate').className = 'diabledatefield';
		//document.getElementById('disablefield').style.display = 'none';
	}
	else 
	{
		form.DPC_fromdate.disabled = false;	
		form.DPC_todate.disabled = false;
		form.rereg.disabled = false;
		document.getElementById('DPC_fromdate').className = 'swifttext-mandatory';
		document.getElementById('DPC_todate').className = 'swifttext-mandatory';
	}
		
}