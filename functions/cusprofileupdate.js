var deletearray = new Array();
var contactarray = '';

function productdetailstoform(slno,cusid,requestfrom)
{
	$('#m_gst').html("");
	if(slno != '' && cusid != '')
	{
		$('#form-error').html('');
		var form = $('#submitform');
		$('#submitform')[0].reset();
		$('#requesthiddenfrom').val(requestfrom);
		var passData = "changetype=customerdetailstoform&lastslno=" + encodeURIComponent(cusid )+ "&lastupdateslno=" + encodeURIComponent(slno)  + "&requestfrom=" + encodeURIComponent(requestfrom) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#productselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/cusprofileupdate.php";
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
					closealldiv();
					$('#productselectionprocess').html('');
					var response = (ajaxresponse).split("^");
					if(response[0] == 2)
						{
							alert('Entry not found');
						}
						else
						{
							//console.log(response);
							//console.log('45: '+response[45]);console.log('46: '+response[46]);
							$('#newbusinessname').val(response[2]);
							$('#requestby').val(response[3]);
							$('#newaddress').val(response[4]);
							$('#newplace').val(response[5]);
							$('#newpincode').val(response[10]);
							$('#newcategory').val(response[11]);
							$('#newwebsite').val(response[12]);
							$('#newremarks').val(response[13]);
							$('#newcompanyclosed').val(response[14]);
							$('#newstdcode').val(response[15]);
							$('#newtype').val(response[16]);
							$('#newfax').val(response[17]);
							$('#newstate').val(response[18]);
							getdistrictlist('districtcodedisplay',response[18]);
							$('#newdistrict').val(response[19]);
							$('#createddate').html(response[20]);							   
							$('#cuslno').val(slno);
							$('#lastslno').val(response[21]);
							//$('#newgst').val(response[46]);
							$('#oldbusinessname').val(response[48]);
							//$('#newgst').val(response[45]);
							//added on 03/11/2017 for gstin
							/*if((response[46] == "") || response[46]== null)	{	
									if((response[45] == "") || response[45]== null) {
										 document.getElementById("newgst").readOnly = false;
										 $('#display16').show();
										 $("#newgst").css('background','#fff');
										 $('#m_gst').html(displayformat(response[45],response[46]));
									}
									else {
										  $("#newgst").css('background','#FEFFE6');
										  document.getElementById("newgst").readOnly = false;
										  //$('#newgst').val(response[45]);
										  $('#display16').show();
										  $('#m_gst').html(displayformat(response[45],response[46]));
									}
							}
							else {	
									if((response[45] == "") || response[45]== null) {
										 document.getElementById("newgst").readOnly = false;
										 $("#newgst").css('background','#fff');
										 $('#newgst').val(response[46]);
										 $('#display16').show();
										 $('#m_gst').html(displayformat(response[45],response[46]));
									}
									else {
										if(response[45] == response[46]) {
											$("#newgst").css('background','#FEFFE6');
											document.getElementById("newgst").readOnly = false;
											$('#newgst').val(response[46]);
											$('#display16').hide();
											//$('#m_gst').html(displayformat(response[45],response[46]));
										}
										else {
											$("#newgst").css('background','#FEFFE6');
											document.getElementById("newgst").readOnly = false;
											$('#newgst').val(response[46]);
											$('#display16').show();
											$('#m_gst').html(displayformat(response[45],response[46]));
										}
									}
							}*/
							//contactdetails(response[39],response[40],response[47]);							
							tabopen5('1','tabg1');
							//console.log(response[2].toLowerCase());
							//console.log(response[23].toLowerCase());
							if(response[46].toLowerCase() != response[45].toLowerCase())
							{
								//alert(response[46]);
								$('#newgst').val(response[46]);
								$('#display16').show();
								$('#m_gst').html(displayformat(response[45],response[46]));
								//first is current, second is new
							}
							else {
								$('#newgst').val(response[46]);
							}
							//added on 4/2/2021
							if(response[50].toLowerCase() != response[49].toLowerCase())
							{
								$('#display17').show();
								$('#m_tanno').html(displayformat(response[50],response[49]));
								
							}
							else
							{
								$('#m_tanno').html('');
							}
							if(response[2].toLowerCase() != response[23].toLowerCase())
							{
								$('#display1').show();
								$('#m_businessname').html(displayformat(response[2],response[23]));
								
							}
							if(response[4].toLowerCase() != response[25].toLowerCase())
							{
								$('#display2').show();
								$('#m_address').html(displayformat(response[4],response[25]));
								
							}
							if(response[5].toLowerCase() != response[26].toLowerCase())
							{
								$('#display3').show();
								$('#m_place').html(displayformat(response[5],response[26]));
								
							}
							if(response[8].toLowerCase() != response[29].toLowerCase())
							{
								$('#display5').show();
								$('#m_district').html(displayformat(response[8],response[29]));
								
							}
							if(response[9].toLowerCase() != response[30].toLowerCase())
							{
								$('#display4').show();
								$('#m_state').html(displayformat(response[9],response[30]));
								
							}
							if(response[10].toLowerCase() != response[31].toLowerCase())
							{
								$('#display6').show();
								$('#m_pincode').html(displayformat(response[10],response[31]));
								
							}
							if(response[15].toLowerCase() != response[34].toLowerCase())
							{
								$('#display7').show();
								$('#m_stdcode').html(displayformat(response[15],response[34]));
								
							}
							if(response[17].toLowerCase() != response[36].toLowerCase())
							{
								$('#display8').show();
								$('#m_fax').html(displayformat(response[17],response[36]));
								
							}
							if(response[7].toLowerCase() != response[35].toLowerCase())
							{
								$('#display11').show();
								$('#m_type').html(displayformat(response[7],response[35]));
								
							}
							if(response[6].toLowerCase() != response[37].toLowerCase())
							{
								$('#display12').show();
								$('#m_category').html(displayformat(response[6],response[37]));
								
							}
							if(response[13].toLowerCase() != response[38].toLowerCase())
							{
								$('#display9').show();
								$('#m_remarks').html(displayformat(response[13],response[38]));
								
							}
							if(response[12].toLowerCase() != response[32].toLowerCase())
							{
								$('#display10').show();
								$('#m_website').html(displayformat(response[12],response[32]));
								
							}
							if(response[14].toLowerCase() != response[33].toLowerCase())
							{
								$('#display13').show();
								$('#m_companyclosed').html(displayformat(response[14],response[33]));
								
							}
							//if(response[45].toLowerCase() != response[46].toLowerCase())
							//{
								//$('#display16').show();
								//$('#m_gst').html(displayformat(response[45],response[46]));
								
							//}
							contactdetails(response[39],response[40],response[47]);	
							autochecknew($("#promotionalsms"),response[41]);
							autochecknew($("#promotionalemail"),response[42]);
							/*$('#m_businessname').html(displayformat(response[2],response[23]));
							$('#m_address').html(displayformat(response[4],response[25]));
							$('#m_place').html(displayformat(response[5],response[26]));
							$('#m_district').html(displayformat(response[8],response[29]));
							$('#m_state').html(displayformat(response[9],response[30]));
							$('#m_pincode').html(displayformat(response[10],response[31]));
							$('#m_stdcode').html(displayformat(response[15],response[34]));
							$('#m_fax').html(displayformat(response[17],response[36]));
							$('#m_type').html(displayformat(response[7],response[35]));
							$('#m_category').html(displayformat(response[6],response[37]));
							$('#m_remarks').html(displayformat(response[13],response[38]));
							$('#m_website').html(displayformat(response[12],response[32]));
							$('#m_companyclosed').html(displayformat(response[14],response[33]));
							contactdetails(response[39],response[40]);*/
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



function customerprofiledatagrid(startlimit)
{
	var passData = "changetype=customergenerategrid&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
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
				$('#productselectionprocess').html('');
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{//alert(response[1])
					gridtabcus3('1','tabgroupgrid','&nbsp; &nbsp;Customer Profile');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1').html(response[1]);
					$('#tabgroupgridc1link').html( response[3]);
				}
				else if(response[0] == 2)
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridc1').html( response[1]);
					$('#tabgroupgridwb1').html("Total Count :  " + 0);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

//Function to "Show more records" or 'Show all records'  of Customer module
function getmorecustdetails(startlimit,slno,showtype)
{
	var passData = "changetype=customergenerategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
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
				$('#productselectionprocess').html('')
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#productselectionprocess').html('');
					$('#custresultgrid').html($('#tabgroupgridc1_1').html());
					$('#tabgroupgridc1_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#tabgroupgridc1link').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#form-error').html(greentext(response[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}



function dealererprofiledatagrid(startlimit)
{
	var passData = "changetype=dealergenerategrid&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
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
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{
					$('#productselectionprocess').html('');
					gridtabcus3('2','tabgroupgrid','&nbsp; &nbsp;Dealer Profile');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc2_1').html(response[1]);
					$('#getmoredealerlink').html(response[3]); 
				}
				else if(response[0] == 2)
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridc2').html(response[1]);
					$('#tabgroupgridwb1').html("Total Count :  " + 0);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

//Function to "Show more records" or 'Show all records' of dealer module
function getmoredealdetails(startlimit,slno,showtype)
{
	var passData = "changetype=dealergenerategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
	ajaxcall5 = $.ajax(
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#productselectionprocess').html('');
					$('#custresultgrid').html( $('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#getmoredealerlink').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#productselectionprocess').html(errormessage(response[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

function webprofiledatagrid(startlimit)
{
	var passData = "changetype=webgenerategrid&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
	ajaxcall6 = $.ajax(
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
				if(response[0] == 1)
				{
					$('#productselectionprocess').html('');
					gridtabcus3('3','tabgroupgrid','&nbsp; &nbsp;Web Profile');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc3_1').html(response[1]);
					$('#getmoreweblink').html(response[3]); 
				}
				else if(response[0] == 2)
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridc3').html(response[1]);
					$('#tabgroupgridwb1').html("Total Count :  " + 0);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

//Function to "Show more records" or 'Show all records' of web module
function getmorewebdetails(startlimit,slno,showtype)
{
	var passData = "changetype=webgenerategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno)+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
	ajaxcall7 = $.ajax(
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
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#productselectionprocess').html('');
					$('#custresultgrid').html( $('#tabgroupgridc3_1').html());
					$('#tabgroupgridc3_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#getmoreweblink').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#productselectionprocess').html(errormessage(response[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}


//Function to display the request from support module -Meghana[16/12/2009]
function supportprofiledatagrid(startlimit)
{
	var passData = "changetype=supportgenerategrid&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
	ajaxcall8 = $.ajax(
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
				$('#productselectionprocess').html('');
				var response = ajaxresponse.split('^');
				if(response[0] == 1)
				{//alert(response[1])
					gridtabcus3('4','tabgroupgrid','&nbsp; &nbsp;Support Profile');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc4_1').html(response[1]);
					$('#getmoresupportlink').html(response[3]);
				}
				else if(response[0] == 2)
				{
					$('#productselectionprocess').html('');
					$('#tabgroupgridc4').html(response[1]);
					$('#tabgroupgridwb1').html("Total Count :  " + 0);
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}

//Function to "Show more records" or "Show all records" of Support module
function getmoresuppdetails(startlimit,slno,showtype)
{
	var passData = "changetype=supportgenerategrid&startlimit=" + encodeURIComponent(startlimit)+ "&slno=" + encodeURIComponent(slno) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	$('#productselectionprocess').html(getprocessingimage());
	queryString = "../ajax/cusprofileupdate.php";
	ajaxcall8 = $.ajax(
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
				$('#productselectionprocess').html('');
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#productselectionprocess').html('');
					$('#custresultgrid').html($('#tabgroupgridc4_1').html());
					$('#tabgroupgridc4_1').html($('#custresultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]);
					$('#getmoresupportlink').html(response[3]);
				}
				else if(response[0] == '2')
				{
					$('#productselectionprocess').html(errormessage(response[1]));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#productselectionprocess").html(scripterror());
		}
	});	
}



function customeractioncheck(radiovalue, fieldcaption)
{
	if(radiovalue == 'customerbusiness_none')
	{
		error.html(errormessage('You have to either Approve or Reject "' + fieldcaption + '" Field '));
		return false;
	}
	return true;
}

function clearscreen()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#cuslno').val('');
	$('#requesthiddenfrom').val('');
	//$('#form-error').html('';
	$('#createddate').html('Not Available');
	onloaddisabled();
	updatecontact();
}

function screenclear()
{
	var form = $('#submitform');
	$('#submitform')[0].reset();
	$('#lastslno').val('');
	$('#cuslno').val('');
	$('#requesthiddenfrom').val('');
	$('#form-error').html('');
	$('#createddate').html('Not Available');
	onloaddisabled();
	updatecontact();
}



function validatecheckbox()
{
	if($('#processedemail').is(':checked'))
   {
      $("#ccemail").attr('disabled',false);
	  $('#ccemail').attr('checked',true);
   }
   else 
   {
      $("#ccemail").attr('disabled',true);
	  $('#ccemail').attr('checked',false);
   }
}


function update()
{ 
	var form = $('#submitform'); 
	var error = $('#form-error');
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var namevalues = '';

	//alert($('#lastslno.value);
	if($('#lastslno').val() == '')
	{
		error.html(errormessage('Please Select a Customer Record from the Grid. '));return false;
	}
	{
		
		var field = $('#validategeneral:checked').val();
		if(field != 'on') var validategeneral = 'no'; else validategeneral = 'yes';
		var field = $('#validatecontact:checked').val();
		if(field != 'on') var validatecontact = 'no'; else validatecontact = 'yes';
		if((validategeneral == 'yes') && (validatecontact == 'yes'))
		{
	
			var field = $('#newbusinessname');
			if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
			if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }

			var field = $('#oldbusinessname');
			if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Old Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
			
			var field = $('#newgst')
			//if(!field.val()) { error.html(errormessage("Enter the GSTIN. ")); field.focus(); return false; }
	if(field.val()) { if(!validategstin(field.val())) { error.html(errormessage('For GSTIN only Alpha / Numeric are allowed .')); field.focus(); return false; } }
	
			var rowcount = $('#adddescriptionrows tr').length;
			tabopen5('2','tabg1');
			var l=1;
			while(l<=(rowcount/18))
			{
				if(!$("#selectiontype1").val())
				{
						error.html(errormessage("Minimum of ONE contact detail is mandatory")); return false;
				}
				else
				var field = $("#"+'selectiontype'+l);
				if(!field.val()) { error.html(errormessage("Select the Type. ")); field.focus(); return false; }
				var field = $("#"+'phone'+l);
				if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid (One) Phone Number.')); field.focus(); return false; } }
				var field = $("#"+'cell'+l);
				if(field.val()) { if(!cellvalidation(field.val())) { error.html(errormessage('Enter the valid (One) Cell Number.')); field.focus(); return false; } }
				var field = $("#"+'emailid'+l);
				if(field.val()) { if(!checkemail(field.val())) { error.html(errormessage('Enter the valid (One) Email Id.')); field.focus(); return false; } }
				var field = $("#"+'name'+l);
				if(field.val()) { if(!contactpersonvalidate(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Numeric / space.')); field.focus(); return false; } }
				l++;
				
			}
			for(j=1;j<=(rowcount/18);j++)
			{
				var typefield = $("#"+'selectiontype'+j);
		
				var namefield = $("#"+'name'+j);
				if(namevalues == '')
					var namevalues = namefield.val();
				else
					var namevalues = namevalues + '~' + namefield.val();
				var phonefield = $("#"+'phone'+j);
				if(phonevalues == '')
					var phonevalues = phonefield.val();
				else
					var phonevalues = phonevalues + '~' + phonefield.val();
				var cellfield = $("#"+'cell'+j);
				if(cellvalues == '')
					var cellvalues = cellfield.val();
				else
					var cellvalues = cellvalues + '~' + cellfield.val();
				var emailfield = $("#"+'emailid'+j);
				if(emailvalues == '')
					var emailvalues = emailfield.val();
				else
					var emailvalues = emailvalues + '~' + emailfield.val();
				
				var slnofield = $("#"+'contactslno'+j);
				if(j == 1)
					contactarray = typefield.val() + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
				else
					contactarray = contactarray + '****' + typefield.val()  + '#' + namefield.val() + '#' +phonefield.val() + '#' + cellfield.val() + '#' + emailfield.val() + '#' + slnofield.val();
			}
			
			if(namevalues == '')
				{error.html(errormessage("Enter Atleast One Contact Person Name."));return false;}
			if(phonevalues == '')
				{error.html(errormessage("Enter Atleast One Phone Number."));return false;}
			if(cellvalues == '')
				{error.html(errormessage("Enter Atleast One Cell Number."));return false;}
			if(emailvalues == '')
				{error.html(errormessage("Enter Atleast One Email Id."));return false;}
		
			tabopen5('1','tabg1');
			var field = $('#newplace');
			if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
			var field = $('#newstate');
			if(!field.val()) { error.html(errormessage("Select the State. ")); field.focus(); return false; }
			var field = $('#newdistrict');
			if(!field.val()) { error.html(errormessage("Select the District. ")); field.focus(); return false; }
			var field = $('#newpincode');
			if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
			if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
			// var field = $('#newstdcode');
			// if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
			var field = $('#newfax');
			if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Fax Number.')); field.focus(); return false; } }
			var field = $('#newwebsite');
			if(field.val())	{ if(!validatewebsite(field.val())) { error.html(errormessage('Enter the valid Website.')); field.focus(); return false; } }
			//fetching the checkbox value and checkin if it is checked or not
			var field1 = $('#processedemail').is(':checked');
			var processedemail;
			if(field1 == true) var processedemail = 'yes'; else var processedemail = 'no';
			var field2 = $('#ccemail').is(':checked');
			if(field2== true) var ccmail = 'yes'; else var ccmail = 'no';
			
			
			var passData ="changetype=processupdate&lastslno=" + encodeURIComponent($('#lastslno').val()) +"&newbusinessname=" + encodeURIComponent($('#newbusinessname').val()) +"&oldbusinessname=" + encodeURIComponent($('#oldbusinessname').val()) +"&newgst=" + encodeURIComponent($('#newgst').val())  + "&newaddress=" + encodeURIComponent($('#newaddress').val()) + "&newplace=" + encodeURIComponent($('#newplace').val()) + "&newdistrict=" + encodeURIComponent($('#newdistrict').val()) + "&newpincode=" + encodeURIComponent($('#newpincode').val()) + "&newstdcode=" + encodeURIComponent($('#newstdcode').val()) + "&newphone=" + encodeURIComponent($('#newphone').val()) + "&newcell=" + encodeURIComponent($('#newcell').val()) + "&newfax=" + encodeURIComponent($('#newfax').val()) + "&newemailid=" + encodeURIComponent($('#newemailid').val()) + "&newwebsite=" + encodeURIComponent($('#newwebsite').val()) + "&newtype=" + encodeURIComponent($('#newtype').val()) + "&newcategory=" + encodeURIComponent($('#newcategory').val()) + "&newremarks=" + encodeURIComponent($('#newremarks').val()) +"&requestfrom=" + encodeURIComponent($('#requesthiddenfrom').val()) +  "&processedemail=" + encodeURIComponent(processedemail) + "&ccmail=" + encodeURIComponent(ccmail)+ "&cuslno=" + encodeURIComponent($('#cuslno').val())+ "&contactarray=" + encodeURIComponent(contactarray)+ "&deletearray=" + encodeURIComponent(deletearray) + "&requestfrom=" + encodeURIComponent($('#requesthiddenfrom').val())  + "&dummy=" + Math.floor(Math.random()*100032680100);
			$('#form-error').html(getprocessingimage());
			queryString = "../ajax/cusprofileupdate.php";
			ajaxcall0 = $.ajax(
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
						$("#ccemail").attr('disabled',false);
						requestfrom = $('#requesthiddenfrom').val();
						$('#form-error').html('');
						var response = ajaxresponse;
						if(response == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else
						{
							error.html(successmessage(response));
							clearscreen();//alert(response)
							if(requestfrom == 'customer_module')
							{
								customerprofiledatagrid('');
							}
							else if(requestfrom == 'dealer_module')
							{
								dealererprofiledatagrid('');
							}
							else if(requestfrom == 'web_module')
							{
								webprofiledatagrid('');
							}
							else if(requestfrom == 'support_module')
							{
								supportprofiledatagrid('');
							}
						}
					}
				}, 
				error: function(a,b)
				{
					$("#productselectionprocess").html(scripterror());
				}
			});	
		}
		else if((validategeneral == 'yes') && (validatecontact == 'no'))
		{
			tabopen5('2','tabg1');
			error.html(errormessage('If you want to Update the profile, please confirm the checkbox of Contact details are verified.' ));
		}
		else if(validategeneral == 'no' &&  validatecontact == 'yes')
		{
			tabopen5('1','tabg1');
			error.html(errormessage('If you want to Update the profile, please confirm the checkbox of General details are verified.' ));
		}
		else if(validategeneral == 'no' &&  validatecontact == 'no')
		{
			tabopen5('1','tabg1');
			error.html(errormessage('If you want to Update the profile, please confirm the checkbox of General and Contact details are verified.' ));
		}
		
	}
}



function onloadenabled()
{
	$('#newbusinessname').attr('disabled',false);
	$('#newaddress').attr('disabled',false);
	$('#newplace').attr('disabled',false);
	$('#newstate').attr('disabled',false);
	$('#newdistrict').attr('disabled',false);
	$('#newpincode').attr('disabled',false);
	$('#newstdcode').attr('disabled',false);
	$('#newfax').attr('disabled',false);
	$('#newwebsite').attr('disabled',false);
	$('#newtype').attr('disabled',false);
	$('#newcategory').attr('disabled',false);
	$('#newremarks').attr('disabled',false);
	$('#newcompanyclosed').attr('disabled',false);
	$('#processedemail').attr('disabled',false);
	$('#ccemail').attr('disabled',false);
	$('#newgst').attr('disabled',false);
	
}

function onloaddisabled()
{
	$('#newbusinessname').attr('disabled',true);
	$('#newaddress').attr('disabled',true);
	$('#newplace').attr('disabled',true);
	$('#newstate').attr('disabled',true);
	$('#newdistrict').attr('disabled',true);
	$('#newpincode').attr('disabled',true);
	$('#newstdcode').attr('disabled',true);
	$('#newfax').attr('disabled',true);
	$('#newwebsite').attr('disabled',true);
	$('#newtype').attr('disabled',true);
	$('#newcategory').attr('disabled',true);
	$('#newremarks').attr('disabled',true);
	$('#newcompanyclosed').attr('disabled',true);
	$('#processedemail').attr('disabled',true);
	$('#ccemail').attr('disabled',true);
	$('#newgst').attr('disabled',true);
	
}



//function to get the radio value
function customer_getradiovalue(radioname)
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


//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtabcus3(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 4;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();//alert(document.getElementById(tabcontent).style.display)
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();//alert(document.getElementById(tabcontent).style.display)
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

function displayformat(char1,char2)
{
	if(char1 != '' && char2 != '')
	{
		char1 = '<font color="#009F00">' + char1 + '</font>' + ', ' + '<font color="#FF0000">' + char2 + '</font>';
	}
	//else if(char1 != '' || !char1)
	else if(char1 != '')
	{
		char1 = '<font color="#009F00">' + char1 + '</font>' ;
	}
	else if(char2 != '' || !char2)
	{
		char1 = '<font color="#FF0000">' + char2 + '</font>' ;
	}
return char1;
}


function contactdetails(tempcontactperson,newcontactperson,deletearray)
{
	var countrow = tempcontactperson;
	var rowcount = newcontactperson;
		splitvalue = new Array();
	var totalrow = tempcontactperson.split('****');
	var countrow = tempcontactperson.split('****');
	var rowcount = newcontactperson.split('****');
	
	$('#adddescriptionrows tr').remove();
	for(k=0;k<totalrow.length;k++)
	{
		slno = (k+1);
		rowid = 'removedescriptionrow'+ slno;
		

			var value = 'contactname'+slno;
			if(k == 10)
				$('#adddescriptionrowdiv').hide();
			else if(k < 10)
				$('#adddescriptionrowdiv').show();
			else 
				$('#adddescriptionrowdiv').hide();
		var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold;font-size:9px">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="manager" >MANAGER</option><option value="CA">CA</option><option value="others" >Others</option></select></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="m_type'+ slno+'"></span ></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="m_name'+ slno+'"></span></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr> <td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_phone'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_cell'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_emailid'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td></tr>';
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
		
		splitvalue[k] =  totalrow[k].split('#');
		$("#"+'selectiontype'+(slno)).val(splitvalue[k][0]);
		$("#"+'name'+(slno)).val(splitvalue[k][1]);
		$("#"+'phone'+(slno)).val(splitvalue[k][2]);
		$("#"+'cell'+(slno)).val(splitvalue[k][3]);
		$("#"+'emailid'+(slno)).val(splitvalue[k][4]);
		$("#"+'contactslno'+(slno)).val(splitvalue[k][5]);
		
	
			
		
	}
	if(deletearray != '')
	{
		deletevalue = new Array();
		var deleterow = deletearray.split('****');
		for(i=0;i<deleterow.length;i++)
		{
			var rowcount = ($('#adddescriptionrows tr').length);
			if(rowcount == 18)
				slno = (rowcount/18) + 1;
			else
				slno = (rowcount/18) + 1;
			rowid = 'removedescriptionrow'+ slno;
			var value = 'contactname'+slno;
			
			var row = '<tr id="removedescriptionrow'+ slno+'" ><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2" ><tr><td><span id="contactname'+ slno+'" style="font-weight:bold;font-size:9px">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px;background-color:#FFD2D2" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="manager" >MANAGER</option><option value="CA">CA</option><option value="others" >Others</option></select></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="m_type'+ slno+'"></span ></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px;background-color:#FFD2D2" /></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="m_name'+ slno+'"></span></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px;background-color:#FFD2D2" /></td></tr><tr> <td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_phone'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px;background-color:#FFD2D2" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_cell'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px;background-color:#FFD2D2" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_emailid'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td></tr>';
			
			
			$("#adddescriptionrows").append(row);
			$('#'+value).html(slno);
			if(slno == 10)
				$('#adddescriptionrowdiv').hide();
			else 
				$('#adddescriptionrowdiv').show();
			
			deletevalue[i] =  deleterow[i].split('#');
			$("#"+'selectiontype'+(slno)).val(deletevalue[i][0]);
			$("#"+'name'+(slno)).val(deletevalue[i][1]);
			$("#"+'phone'+(slno)).val(deletevalue[i][2]);
			$("#"+'cell'+(slno)).val(deletevalue[i][3]);
			$("#"+'emailid'+(slno)).val(deletevalue[i][4]);
			$("#"+'contactslno'+(slno)).val(deletevalue[i][5]);
			
		}
	}
	
	
	
	var countrow = tempcontactperson.split('****');
	var rowcount = newcontactperson.split('****');
	var countrowvalues = new Array();
	var rowcountvalue = new Array();
	
	/*if(rowcount.length < countrow.length)
	{
		for(i=0;i<rowcount.length;i++)
		{
			rowcountvalue[i] = rowcount[i].split('#');
			for(j=0;j<countrow.length;j++)
			{
				
				countrowvalues[j] = countrow[j].split('#');
				$("#"+'m_type'+(i+1)).html('<font color="#009F00">' + rowcountvalue[i][0] + '</font>');
				$("#"+'t_type'+(j+1)).html('<font color="#FF0000"">' + countrowvalues[j][0] + '</font>');
				$("#"+'m_name'+(i+1)).html('<font color="#009F00">' + rowcountvalue[i][1] + '</font>');
				$("#"+'t_name'+(j+1)).html('<font color="#FF0000"">' + countrowvalues[j][1] + '</font>');
				$("#"+'m_phone'+(i+1)).html('<font color="#009F00">' + rowcountvalue[i][2] + '</font>');
				$("#"+'t_phone'+(j+1)).html('<font color="#FF0000"">' + countrowvalues[j][2] + '</font>');
				$("#"+'m_cell'+(i+1)).html('<font color="#009F00">' + rowcountvalue[i][3] + '</font>');
				$("#"+'t_cell'+(j+1)).html('<font color="#FF0000"">' + countrowvalues[j][3] + '</font>');
				$("#"+'m_emailid'+(i+1)).html('<font color="#009F00">' + rowcountvalue[i][4] + '</font>');
				$("#"+'t_emailid'+(j+1)).html('<font color="#FF0000"">' + countrowvalues[j][4] + '</font>');
				
			}
		}
		
	}
	else 
	{
		for(i=0;i<countrow.length;i++)
		{
			countrowvalues[i] = countrow[i].split('#');
			for(j=0;j<rowcount.length;j++)
			{
				
				rowcountvalue[j] = rowcount[j].split('#');
				$("#"+'m_type'+(i+1)).html('<font color="#009F00">' + countrowvalues[i][0] + '</font>');
				$("#"+'t_type'+(j+1)).html('<font color="#FF0000">' + rowcountvalue[j][0] + '</font>');
				$("#"+'m_name'+(i+1)).html('<font color="#009F00">' + countrowvalues[i][1] + '</font>');
				$("#"+'t_name'+(j+1)).html('<font color="#FF0000">' + rowcountvalue[j][1] + '</font>');
				$("#"+'m_phone'+(i+1)).html('<font color="#009F00">' + countrowvalues[i][2] + '</font>');
				$("#"+'t_phone'+(j+1)).html('<font color="#FF0000">' + rowcountvalue[j][2] + '</font>');
				$("#"+'m_cell'+(i+1)).html('<font color="#009F00">' + countrowvalues[i][3] + '</font>');
				$("#"+'t_cell'+(j+1)).html('<font color="#FF0000">' + rowcountvalue[j][3] + '</font>');
				$("#"+'m_emailid'+(i+1)).html('<font color="#009F00">' + countrowvalues[i][4] + '</font>');
				$("#"+'t_emailid'+(j+1)).html('<font color="#FF0000">' + rowcountvalue[j][4] + '</font>');
				
			}
		}
	}*/
	for(k=0,i=0;k<countrow.length,i<rowcount.length;k++,i++)
	{
		countrowvalues[k] = countrow[k].split('#');
		rowcountvalue[i] = rowcount[i].split('#');
		
		
		if(countrowvalues[k][0].toLowerCase()!= rowcountvalue[i][0].toLowerCase())
		{
			$("#"+'m_type'+(k+1)).html('<font color="#009F00">' + countrowvalues[k][0] + '</font>');
			$("#"+'t_type'+(i+1)).html('<font color="#FF000">' + rowcountvalue[i][0] + '</font>');
		}
		
		if(countrowvalues[k][1].toLowerCase()!= rowcountvalue[i][1].toLowerCase())
		{
			$("#"+'m_name'+(k+1)).html('<font color="#009F00">' + countrowvalues[k][1] + '</font>');
			$("#"+'t_name'+(i+1)).html('<font color="#FF000">' + rowcountvalue[i][1] + '</font>');
		}
		
		if(countrowvalues[k][2].toLowerCase()!= rowcountvalue[i][2].toLowerCase())
		{
			$("#"+'m_phone'+(k+1)).html('<font color="#009F00">' + countrowvalues[k][2] + '</font>');
			$("#"+'t_phone'+(i+1)).html('<font color="#FF000">' + rowcountvalue[i][2] + '</font>');
		}
		
		
		if(countrowvalues[k][3].toLowerCase()!= rowcountvalue[i][3].toLowerCase())
		{
			$("#"+'m_cell'+(k+1)).html('<font color="#009F00">' + countrowvalues[k][3] + '</font>');
			$("#"+'t_cell'+(i+1)).html('<font color="#FF000">' + rowcountvalue[i][3] + '</font>');
		}
		
		if(countrowvalues[k][4].toLowerCase()!= rowcountvalue[i][4].toLowerCase())
		{
		    $("#"+'m_emailid'+(k+1)).html('<font color="#009F00">' + countrowvalues[k][4] + '</font>');
		    $("#"+'t_emailid'+(i+1)).html('<font color="#FF000">' + rowcountvalue[i][4] + '</font>');
		}
				
	}
	
	if(countrow.length > rowcount.length)
	{
		var totallength = rowcount.length;
		
		for(var m=totallength;m<countrow.length;m++)
		{
			countrowvalues[m] = countrow[m].split('#');
			
			$("#"+'m_type'+(m+1)).html('<font color="#009F00">' + countrowvalues[m][0] + '</font>');
			$("#"+'m_name'+(m+1)).html('<font color="#009F00">' + countrowvalues[m][1] + '</font>');
			$("#"+'m_phone'+(m+1)).html('<font color="#009F00">' + countrowvalues[m][2] + '</font>');
			$("#"+'m_cell'+(m+1)).html('<font color="#009F00">' + countrowvalues[m][3] + '</font>');
			$("#"+'m_emailid'+(m+1)).html('<font color="#009F00">' + countrowvalues[m][4] + '</font>');
		}
	}
}


function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

//Remove description row
function removedescriptionrows(rowid,rowslno)
{
	if(deletearray == '')
		deletearray = $('#contactslno'+rowslno).val();
	else
		deletearray = deletearray  + ',' + $('#contactslno'+rowslno).val();
	var error = $("#form-error");
	
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 18)
	{
		error.html(errormessage("Minimum of ONE contact detail is mandatory")); 
		return false;
	}
	else
	{
		$('#'+rowid).remove();
		var countval = 0;
		for(i=1;i<=(rowcount/18);i++)
		{
			if(((rowcount/18)-1) == 10)
				$('#adddescriptionrowdiv').hide();	
			else if(((rowcount/18)-1) < 10)
				$('#adddescriptionrowdiv').show();	
			else
				$('#adddescriptionrowdiv').hide();
			var selectiontype = '#selectiontype'+i;
			var designationtype = '#designationtype'+i;
			var name = '#name'+i;
			var phone = '#phone'+i;
			var cell = '#cell'+i;
			var emailid = '#emailid'+i;
			var removedescriptionrow = '#removedescriptionrow'+i;
			var contactslno =  '#contactslno'+i;
			var removerowdiv = '#removerowdiv'+i;
			if($(removedescriptionrow).length > 0)
			{
				countval++;
				$("#selectiontype"+ i).attr("name","selectiontype"+ countval);
				$("#selectiontype"+ i).attr("id","selectiontype"+ countval);
				
				$("#name"+ i).attr("name","name"+ countval);
				$("#name"+ i).attr("id","name"+ countval);
				
				$("#phone"+ i).attr("name","phone"+ countval);
				$("#phone"+ i).attr("id","phone"+ countval);
				
				$("#cell"+ i).attr("name","cell"+ countval);
				$("#cell"+ i).attr("id","cell"+ countval);
				
				$("#emailid"+ i).attr("name","emailid"+ countval);
				$("#emailid"+ i).attr("id","emailid"+ countval);
				
				$("#removedescriptionrow"+ i).attr("name","removedescriptionrow"+ countval);
				$("#removedescriptionrow"+ i).attr("id","removedescriptionrow"+ countval);
				
				$("#contactslno"+ i).attr("name","contactslno"+ countval);
				$("#contactslno"+ i).attr("id","contactslno"+ countval);
				
				$("#contactname"+ i).attr("id","contactname"+ countval);
				$("#contactname"+ countval).html(countval);
				
				$("#removerowdiv"+ i).attr("id","removerowdiv"+ countval);
				document.getElementById("removerowdiv"+ countval).onclick = new Function('removedescriptionrows("removedescriptionrow' + countval + '" ,"' + countval + '")') ;
						
			}
		}
	}
}

//To add description rows
function adddescriptionrows()
{
	$("#form-error").html('');
	var rowcount = ($('#adddescriptionrows tr').length);
	if(rowcount == 18)
	slno = (rowcount/18) + 1;
	else
	slno = (rowcount/18) + 1;
	rowid = 'removedescriptionrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold;font-size:9px">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="manager" >MANAGER</option><option value="CA" >CA</option><option value="others" >Others</option></select></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="m_type'+ slno+'"></span ></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="m_name'+ slno+'"></span></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr> <td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_phone'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_cell'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_emailid'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td></tr>';
	
	$("#adddescriptionrows").append(row);
	$('#'+value).html(slno);
	if(slno == 10)
		$('#adddescriptionrowdiv').hide();
	else 
		$('#adddescriptionrowdiv').show();
}


function rejectrequest()
{
	$('#form-error').html('');
	var form = $('#submitform');
	var value = "Are you sure you want to reject the request of the selected customer?";
	var confirmation = confirm (value);
	if (confirmation)
	{
		var passData = "changetype=rejectrequest&cuslno=" + encodeURIComponent($('#cuslno').val()) +"&requestfrom=" + encodeURIComponent($('#requesthiddenfrom').val())+"&lastslno=" + encodeURIComponent($('#lastslno').val()) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$('#productselectionprocess').html(getprocessingimage());
		var queryString = "../ajax/cusprofileupdate.php";
		ajaxcall11 = $.ajax(
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
					$('#productselectionprocess').html('');
					requestfrom = $('#requesthiddenfrom').val();
					var response = (ajaxresponse).split("^");
					$('#form-error').html(successmessage(response[1]));
					clearscreen();
					if(requestfrom == 'customer_module')
					{
						customerprofiledatagrid('');
					}
					else if(requestfrom == 'dealer_module')
					{
						dealererprofiledatagrid('');
					}
					else if(requestfrom == 'web_module')
					{
						webprofiledatagrid('');
					}
					else if(requestfrom == 'support_module')
					{
						supportprofiledatagrid('');
					}
				}
			}, 
			error: function(a,b)
			{
				$("#productselectionprocess").html(scripterror());
			}
		});	
	}
	else
	{
		return false;
	}
}


function updatecontact()
{
	totalarray = '';
	$('#m_businessname').html('') ;
	$('#m_address').html('');
	$('#m_place').html('');
	$('#m_district').html('');
	$('#m_state').html('');
	$('#m_pincode').html('');
	$('#m_stdcode').html('');
	$('#m_website').html('');
	$('#m_category').html('');
	$('#m_type').html('');
	$('#m_branch').html('');
	$('#m_remarks').html('');
	$('#m_companyclosed').html('');
	$('#m_fax').html('');
	$('#m_gst').html('');
	var rowcount = $('#adddescriptionrows tr').length;
	for(j=1;j<=(rowcount/18);j++)
	{
		$("#"+'t_type'+(j)).html('');
		$("#"+'t_name'+(j)).html('');
		$("#"+'t_phone'+(j)).html('');
		$("#"+'t_cell'+(j)).html('');
		$("#"+'t_emailid'+(j)).html('');
		
		$("#"+'m_type'+(j)).html('');
		$("#"+'m_name'+(j)).html('');
		$("#"+'m_phone'+(j)).html('');
		$("#"+'m_cell'+(j)).html('');
		$("#"+'m_emailid'+(j)).html('');
	}
		slno = 1;
		$('#adddescriptionrows tr').remove();
		rowid = 'removedescriptionrow'+ slno;
		var value = 'contactname'+slno;
		
		var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold;font-size:9px">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="manager" >MANAGER</option><option value="CA" >CA</option><option value="others" >Others</option></select></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="m_type'+ slno+'"></span ></td></tr><tr><td  style="font-weight:bold;font-size:9px" height="15px" align="left"><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="m_name'+ slno+'"></span></td></tr><tr><td align="left" style="font-weight:bold;font-size:9px" height="15px"><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr> <td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_phone'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_cell'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="m_emailid'+ slno+'"></span></td></tr><tr><td align="left"  style="font-weight:bold;font-size:9px" height="15px"><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td></tr>';
		
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
}


function closealldiv()
{
	for(i=1;i<=13;i++)
	{
		$("#display"+i).hide();
	}
}