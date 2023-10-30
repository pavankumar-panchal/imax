function dealerdetailstoform(slno)
{
	if(slno != '' )
	{
		$('#form-error').html('');
		var form = $('#submitform');
		$('#submitform')[0].reset();
		var passData = "changetype=dealerdetailstoform&lastslno="  + encodeURIComponent(slno) + "&dummy=" + Math.floor(Math.random()*100032680100);
		//alert(passData);
		$('#productselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/dealerprofileupdate.php";
		ajaxcall1 = $.ajax(
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
					onloadenabled();
					$('#productselectionprocess').html('');
					var response = (ajaxresponse).split("^");
					$('#extbusinessname').val(response[30]);
					$('#extcontactperson').val(response[14]);
					$('#extaddress').val(response[15]);
					$('#extplace').val(response[16]);
					$('#extdistrict').val(response[26]);
					$('#extstate').val(response[27]);
					$('#extpincode').val(response[19]);
					$('#extstdcode').val(response[20]);
					$('#extphone').val(response[21]);
					$('#extcell').val(response[22]);
					$('#extemailid').val(response[23]);
					$('#extwebsite').val(response[24]);
					$('#extregion').val(response[25]);
					$('#extdealerid').val(response[30]);
					$('#extpersemailid').val(response[31]);
					$('#newbusinessname').val(response[29]);
					$('#newcontactperson').val(response[1]);
					$('#newaddress').val(response[2]);
					$('#newplace').val(response[3]);
					$('#newstate').val(response[5]);
					getdistrictlist('districtcodedisplay',response[5]);
					$('#newdistrict').val(response[4]);
					$('#newpincode').val(response[6]);
					$('#newstdcode').val(response[7]);
					$('#newphone').val(response[8]);
					$('#newcell').val(response[9]);
					$('#newemailid').val(response[10]);
					$('#newwebsite').val(response[11]);
					$('#newregion').val(response[12]);
					$('#lastslno').val(response[13]);
					$('#extdistrictcode').val(response[17]);
					$('#extstatecode').val(response[18]);
					$('#newpersemailid').val(response[32]);
					$('#createddate').html(response[28]);
					$('#lastupdateslno').val(response[33]);
				}
			}, 
			error: function(a,b)
			{
				$("#productselectionprocess").html(scripterror());
			}
		});	
	}
}


function dealerprofiledatagrid(startlimit)
{
	var passData = "changetype=generategrid&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/dealerprofileupdate.php";
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
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html(response[3]);	
				}
				else if(response[0] == '2')
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridc1_1').html(response[1]);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

//Function for "show more records" of the dealer
function getmoredata(startlimit,slno,showtype)
{
	var form = $('#submitform');
	var passData = "changetype=generategrid&startlimit=" + encodeURIComponent(startlimit) + "&slno=" + encodeURIComponent(slno) + "&showtype=" + encodeURIComponent(showtype) +"&dummy=" + Math.floor(Math.random()*10054300000);
	//alert(passData);
	var queryString = "../ajax/dealerprofileupdate.php";
	$('#productselectionprocess').html(getprocessingimage());
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#resultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#tabgroupgridc1link').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#productselectionprocess').html(respose[1]);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}


function clearscreen()
{
	
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	//document.getElementById('form-error').innerHTML = '';
	$('#createddate').html('Not Available');
	onloaddisabled();
}
function screenclear()
{
	
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#form-error').html('');
	$('#createddate').html('Not Available');
	onloaddisabled();
}


//Function to select the particular option in <SELECT> Tag, with the compare value----------------------------------
function autoselect(selectid,comparevalue)
{
	var selection = document.getElementById(selectid);
	for(var i = 0; i < selection.length; i++) 
	{
		if(selection[i].value == comparevalue)
		{
			selection[i].selected = "1";
			return;
		}
	}
}


//function to get the radio value
function dealer_getradiovalue(radioname)
{
	
	if(radioname.value)
		return radioname.value;
	else
	for(var i = 0; i < radioname.length; i++) 
	{
		if(radioname[i].checked) {
			return radioname[i].value;
		}
	}
}

function onloadenabled()
{
	$('#newbusinessname').attr('disabled',false);
	$('#newcontactperson').attr('disabled',false);
	$('#newaddress').attr('disabled',false);
	$('#newplace').attr('disabled',false);
	$('#newstate').attr('disabled',false);
	$('#newdistrict').attr('disabled',false);
	$('#newpincode').attr('disabled',false);
	$('#newstdcode').attr('disabled',false);
	$('#newphone').attr('disabled',false);
	$('#newcell').attr('disabled',false);
	$('#newemailid').attr('disabled',false);
	$('#newpersemailid').attr('disabled',false);
	$('#newwebsite').attr('disabled',false);
	$('#newregion').attr('disabled',false);
}

function onloaddisabled()
{
	$('#newbusinessname').attr('disabled',true);
	$('#newcontactperson').attr('disabled',true);
	$('#newaddress').attr('disabled',true);
	$('#newplace').attr('disabled',true);
	$('#newstate').attr('disabled',true);
	$('#newdistrict').attr('disabled',true);
	$('#newpincode').attr('disabled',true);
	$('#newstdcode').attr('disabled',true);
	$('#newphone').attr('disabled',true);
	$('#newcell').attr('disabled',true);
	$('#newemailid').attr('disabled',true);
	$('#newpersemailid').attr('disabled',true);
	$('#newwebsite').attr('disabled',true);
	$('#newregion').attr('disabled',true);
}



function update()
{ 
	var form = $('#submitform'); 
	var error = $('#form-error');
	if($('#lastslno').val() == '')
	{
		error.html(errormessage('Please Select a Dealer Record from the Grid. '));return false;
	}
	{
	var dealerbusiness_action = $('input[name=dealerbusiness_type]:checked').val();
	if(dealerbusiness_action == 'none' || dealerbusiness_action == 'approve' )
	{
		var field = $('#newbusinessname');
		if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
	}
	var dealercontact_action = $('input[name=dealerbusiness_type]:checked').val();
	if(dealercontact_action == 'none' || dealercontact_action == 'approve')
	{
		var field = $('#newcontactperson');
		if(!field.val()) { error.html(errormessage("Enter the Name of the Contact Person. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecontactperson(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space / comma.')); field.focus(); return false; } }
	}
	var dealerplace_action = $('input[name=dealerplace_type]:checked').val();
	if(dealerplace_action == 'none' || dealerplace_action == 'approve')
	{
		var field = $('#newplace');
		if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
	}
	var dealerstate_action = $('input[name=dealerstate_type]:checked').val();
	if(dealerstate_action == 'none' || dealerstate_action == 'approve')
	{
		var field = $('#newstate');
		if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
	}
	var dealerdistrict_action = $('input[name=dealerdistrict_type]:checked').val();
	if(dealerdistrict_action == 'none' || dealerdistrict_action == 'approve')
	{
		var field = $('#newdistrict');
		if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
	}
	var dealerpincode_action = $('input[name=dealerpincode_type]:checked').val();
	if(dealerpincode_action == 'none' || dealerpincode_action == 'approve')
	{
		var field = $('#newpincode');
		if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
	}
	var dealerstd_action = $('input[name=dealerstd_type]:checked').val();
	if(dealerstd_action == 'none' || dealerstd_action == 'approve')
	{
		var field = $('#newstdcode');
		if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
	}
	var dealerphone_action = $('input[name=dealerphone_type]:checked').val();
	if(dealerphone_action == 'none' || dealerphone_action == 'approve')
	{
		var field = $('#newphone');
		if(!field.val()) { error.html(errormessage("Enter the Phone Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
	}
	var dealercell_action = $('input[name=dealercell_type]:checked').val();
	if(dealercell_action == 'none' || dealercell_action == 'approve')
	{
		var field = $('#newcell');
		if(!field.val()) { error.html(errormessage("Enter the Cell Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecell(field.val())) { error.html( errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
	}
	var dealeremail_action = $('input[name=dealeremail_type]:checked').val();
	if(dealeremail_action == 'none' || dealeremail_action == 'approve')
	{
		var field = $('#newemailid');
		if(!field.val()) { error.html( errormessage("Enter the Email ID. ")); field.focus(); return false; }
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
	}
	var dealerpersemail_action = $('input[name=dealerpersemailid_type]:checked').val();
	if(dealerpersemail_action == 'none' || dealerpersemail_action == 'approve')
	{
		var field = $('#newpersemailid');
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html( errormessage('Enter the valid Personal Email ID.')); field.focus(); return false; } }
	}
	var dealerwebsite_action = $('input[name=dealerwebsite_type]:checked').val();
	if(dealerwebsite_action == 'none' || dealerwebsite_action == 'approve')
	{
		var field = $('#newwebsite');
		if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
	}
	var dealerregion_action = $('input[name=dealerregion_type]:checked').val();
	if(dealerregion_action == 'none' || dealerregion_action == 'approve')
	{
		var field = $('#newregion');
		if(!field.val()) { error.html( errormessage("Select the Region. ")); field.focus(); return false; }
	}
	
	var dealerbusiness_action = $('input[name=dealerbusiness_type]:checked').val();
	if(dealerbusiness_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Business Name" Field '));return false;}
	var dealercontact_action = $('input[name=dealercontact_type]:checked').val();
	if(dealercontact_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Contact Person" Field '));return false;}	
	var dealeraddress_action = $('input[name=dealeraddress_type]:checked').val();
	if(dealeraddress_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Address" Field '));return false;}
	var dealerplace_action = $('input[name=dealerplace_type]:checked').val();
	if(dealerplace_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Place" Field '));return false;}	 
	var dealerstate_action = $('input[name=dealerstate_type]:checked').val();
	if(dealerstate_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "State" Field '));return false;}	 
	var dealerdistrict_action = $('input[name=dealerdistrict_type]:checked').val();
	if(dealerdistrict_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "District" Field '));return false;}	 
	var dealerpincode_action = $('input[name=dealerpincode_type]:checked').val();
	if(dealerpincode_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Pin code" Field '));return false;}	 
	var dealerstd_action = $('input[name=dealerstd_type]:checked').val();
	if(dealerstd_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "STD code" Field '));return false;}	 
	var dealerphone_action = $('input[name=dealerphone_type]:checked').val();
	if(dealerphone_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Phone Number" Field '));return false;}	 
	var dealercell_action = $('input[name=dealercell_type]:checked').val();
	if(dealercell_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Cell Number" Field '));return false;}	 
	var dealeremail_action = $('input[name=dealeremail_type]:checked').val();
	if(dealeremail_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Emailid" Field '));return false;}
	var dealerpersemail_action = $('input[name=dealerpersemailid_type]:checked').val();
	if(dealerpersemail_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Personal Emailid" Field '));return false;}	
	var dealerwebsite_action = $('input[name=dealerwebsite_type]:checked').val();
	if(dealerwebsite_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Website" Field '));return false;}	 
	var dealerregion_action = $('input[name=dealerregion_type]:checked').val();
	if(dealerregion_action == 'none')
		 { error.html( errormessage('You have to either Approve or Reject "Region" Field '));return false;}
		 
	
	var passData ="changetype=processupdate&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dealerbusiness_action=" + dealerbusiness_action +"&dealercontact_action=" + dealercontact_action + "&dealeraddress_action=" + dealeraddress_action + "&dealerplace_action=" + dealerplace_action + "&dealerstate_action=" + dealerstate_action + "&dealerdistrict_action=" + dealerdistrict_action + "&dealerpincode_action=" + dealerpincode_action + "&dealerstd_action=" + dealerstd_action + "&dealerphone_action=" + dealerphone_action + "&dealercell_action=" + dealercell_action + "&dealeremail_action=" + dealeremail_action + "&dealerpersemail_action=" + dealerpersemail_action + "&dealerwebsite_action=" + dealerwebsite_action + "&dealerregion_action=" + dealerregion_action +  "&newbusinessname=" + encodeURIComponent($('#newbusinessname').val()) +"&newcontactperson=" + encodeURIComponent($('#newcontactperson').val()) + "&newaddress=" + encodeURIComponent($('#newaddress').val()) + "&newplace=" + encodeURIComponent($('#newplace').val()) + "&newdistrict=" + encodeURIComponent($('#newdistrict').val()) + "&newpincode=" + encodeURIComponent($('#newpincode').val()) + "&newstdcode=" + encodeURIComponent($('#newstdcode').val()) + "&newphone=" + encodeURIComponent($('#newphone').val()) + "&newcell=" + encodeURIComponent($('#newcell').val()) + "&newemailid=" + encodeURIComponent($('#newemailid').val()) +  "&newpersemailid=" + encodeURIComponent($('#newpersemailid').val()) +"&newwebsite=" + encodeURIComponent($('#newwebsite').val()) +  "&newregion=" + encodeURIComponent($('#newregion').val()) +  "&lastupdateslno=" + encodeURIComponent($('#lastupdateslno').val())+  "&dummy=" + Math.floor(Math.random()*100032680100);
	//alert(passData);
	$('#form-error').html(getprocessingimage());
	queryString = "../ajax/dealerprofileupdate.php";
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
				var response1 = ajaxresponse.split('^');//alert(response);
				if(response1[0] == '1')
				{
					error.html(successmessage(response1[1]));
					dealerprofiledatagrid('');
					clearscreen();
				}
				else
				{
					error.html(errormessage('Cannot Connect'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
	}
	
}


function combineMenus() {
	
	var form= $('#submitform');
	$("input[@type=dealerselectall_reject][checked]").each( 
    function() { 
        $('#dealerbusiness_reject').attr('checked', true);
		$('#dealercontact_reject').attr('checked', true);
		$('#dealeraddress_reject').attr('checked', true);
		$('#dealerplace_reject').attr('checked', true);
		$('#dealerstate_reject').attr('checked', true);
		$('#dealerdistrict_reject').attr('checked', true);
		$('#dealerpincode_reject').attr('checked', true);
		$('#dealerstd_reject').attr('checked', true);
		$('#dealerphone_reject').attr('checked', true);
		$('#dealercell_reject').attr('checked', true);
		$('#dealeremail_reject').attr('checked', true);
		$('#dealerpersemailid_reject').attr('checked', true);
		$('#dealerwebsite_reject').attr('checked', true);
		$('#dealerregion_reject').attr('checked', true);
		} 
	);
}

function selectall() {
	
	var form= $('#submitform');
	$("input[@type=dealerselectall_approve][checked]").each( 
    function() { 
        $('#dealerbusiness_approve').attr('checked', true);
		$('#dealercontact_approve').attr('checked', true);
		$('#dealeraddress_approve').attr('checked', true);
		$('#dealerplace_approve').attr('checked', true);
		$('#dealerstate_approve').attr('checked', true);
		$('#dealerdistrict_approve').attr('checked', true);
		$('#dealerpincode_approve').attr('checked', true);
		$('#dealerstd_approve').attr('checked', true);
		$('#dealerphone_approve').attr('checked', true);
		$('#dealercell_approve').attr('checked', true);
		$('#dealeremail_approve').attr('checked', true);
		$('#dealerpersemailid_approve').attr('checked', true);
		$('#dealerwebsite_approve').attr('checked', true);
		$('#dealerregion_approve').attr('checked', true);
		} 
	);
	
}

function allnone() 
{
	var form= $('#submitform');
	$("input[@type=dealerselectall_none][checked]").each( 
    function() { 
        $('#dealerbusiness_none').attr('checked', true);
		$('#dealercontact_none').attr('checked', true);
		$('#dealeraddress_none').attr('checked', true);
		$('#dealerplace_none').attr('checked', true);
		$('#dealerstate_none').attr('checked', true);
		$('#dealerdistrict_none').attr('checked', true);
		$('#dealerpincode_none').attr('checked', true);
		$('#dealerstd_none').attr('checked', true);
		$('#dealerphone_none').attr('checked', true);
		$('#dealercell_none').attr('checked', true);
		$('#dealeremail_none').attr('checked', true);
		$('#dealerpersemailid_none').attr('checked', true);
		$('#dealerwebsite_none').attr('checked', true);
		$('#dealerregion_none').attr('checked', true);
		} 
	);
	
}



