var customerarray = new Array();
var mypagenumber = 1;
var sourcecount = 0;
var destinationcount =0;
var ignorecount =0;
var sourcearray = new Array();    
var companynamearray = new Array();    
var addressarray = new Array();      
var placearray = new Array();        
var pincodearray = new Array();      
var remarksarray = new Array();       
var stdcodearray = new Array();       
var faxarray = new Array();                 
var websitearray = new Array();   
var deletearray = new Array();
var totalforms =0;
function mergearray()
{
	//tabopen5('1','tabg1');
	var form = $('#submitform');
	var error1 = $('#form-error1');
	error1.html('');
	var subselection = $('input[name=databasefield]:checked').val();
	var passData = "switchtype=mergelist&subselection=" + encodeURIComponent(subselection) +"&dummy=" + Math.floor(Math.random()*10054300000);//alert(passData);
	$('#form-error').html(getprocessingimage());
	queryString = "../ajax/mergecustomerlist.php";
	ajaxcall1 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			//alert(response);
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				if(subselection == 'Company')
				{
					$('#criteria').html('Company  Name') ;
				}
				else if(subselection == 'Emailid')
				{
					$('#criteria').html('Email Id') ;
				}
				else if(subselection == 'Phone')
				{
					$('#criteria').html('Phone'); 
				}
				else if(subselection == 'Cell')
				{
					$('#criteria').html('Cell');
				}
				else if(subselection == 'Website')
				{
					$('#criteria').html('Website');
				}
				customerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					customerarray[i] = response[i];
				}			
			}
			$('#hiddenField').val(customerarray);
			if(customerarray != '')
			{
				getcustomerlistarray();
			}
			else
			{
				alert("No records found to merge for the selected criteria.");
				$('#mergedetails').hide();
				$('tabc2').hide();
				$('tabc1').show();
				$('headerline').show();
				return false;
			}
		}, 
		error: function(a,b)
		{
			$('#form-error').html(scripterror());
		}
	});	
}

function getcustomerlistarray()
{
	$('#tabc1').hide();
	$('#headerline').hide();
	var numberofcustomers = customerarray.length;
	$('#tabc2').show();
	$('#displayrecords').html(numberofcustomers);
	$('#records').html(1 + ' ' + 'of' + ' ' +numberofcustomers);
	fetcheachrecord(mypagenumber);
}

//----------------------------------Function for navigation of records.------------------------------------
function getdata(customeraray,image)
{
	switch(image)
	{
		case 'leftarrow' :
						if(mypagenumber == '0' || mypagenumber == '1')
						{
							$('#form-error').html("No more records to display.");
							$('leftarrow').attr('disabled',true);
							//disableleftarrow();
						}
						else
						{
							$('#mergeform-error').html(getprocessingimage());
							mypagenumber--;
							$('#hiddenpagenumber').val(mypagenumber);
							var pageof = mypagenumber + ' ' + 'of' + ' ' + customerarray.length;
							$('#records').html(pageof);
							fetcheachrecord(mypagenumber);
							sourcecount = 0;
							destinationcount =0;
							sourcearray = '';
							companynamearray = '';
							addressarray = '';
							placearray = '';
							pincodearray = '';
							remarksarray = '';
							stdcodearray = '';
							faxarray = '';
							websitearray = '';
							
							$('#mergedetails').hide();
						}
						break;
							
		case 'rightarrow':
						if(mypagenumber >= customerarray.length)
						{
							$('#form-error').html("No more records to display.");
						}
						else
						{
							$('#mergeform-error').html(getprocessingimage());
							mypagenumber++;
							$('#hiddenpagenumber').val(mypagenumber);
							$('#leftarrow').attr('disabled',false);
							var pageof = mypagenumber + ' ' + 'of'  + ' ' + customerarray.length;
							$('#records').html(pageof);
							fetcheachrecord(mypagenumber);
							$('#mergedetails').hide();
							sourcecount = 0;
							destinationcount =0;
							sourcearray = '';
							companynamearray = '';
							addressarray = '';
							placearray = '';
							pincodearray = '';
							remarksarray = '';
							stdcodearray = '';
							faxarray = '';
							websitearray = '';
						}
						break;
	}
}

function imageclick(image)
{
	$('#mergeform-error').html(getprocessingimage());
	var hiddenfieldvalue = $('hiddenField').val();
	//tabopen5('1','tabg1');
	getdata(hiddenfieldvalue,image);
}

function fetcheachrecord(mypagenumber)
{
	var passData = "switchtype=getdata&slnos="+customerarray[mypagenumber - 1]+"&dummy=" + Math.floor(Math.random()*10054300000);
	$('#form-error').html(getprocessingimage());
	ajaxcall2 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false,dataType: "json",
		success: function(ajaxresponse,status)
		{	
			if(ajaxresponse == 'Thinking to redirect')
			{
				window.location = "../logout.php";
				return false;
			}
			else
			{
				var response = ajaxresponse;
				var responsesplit = response; //alert(responsesplit)
				$('#displaydata').html(responsesplit['grid']);
				//alert(responsesplit[1]);
				totalforms = responsesplit['count']; 
				var numofcust = '(' + responsesplit['count'] + ' ' + 'Customers' + ')';
				$('#numofcust').html(numofcust);
				displaygrid(responsesplit['count']);
			}
		}, 
		error: function(a,b)
		{
			$("#form-error").html(scripterror());
		}
	});	
				
			
}
function displaygrid(responsevalue)
{
	$('#mergeform-error').html('');
	var i = 0;
	var source;
	var destination;
	sourcearray = '';
	companynamearray = '';
	addressarray = '';
	placearray = '';
	pincodearray = '';
	remarksarray = '';
	stdcodearray = '';
	faxarray = '';
	websitearray = '';
	
	$('#form-error').html(''); 
	while(i < responsevalue)
	{
		source = 'source' + i;
		destination = 'destination' + i;
		ignore = 'ignore' + 'i';
		if($('#'+source).is(':checked') == true)
		{
			var form = ('customerdetails' + i);
			gridtoform(form,'source');
		}
		else if($('#'+destination).is(':checked') == true)
		{
			var form = ('customerdetails' + i);
			gridtoform(form,'destination');
		}
		i++;
	}
}

//---------------------------------------Function called on click of destination radio button.------------------- 
function radiobuttonclick(index,totalforms)
{
	var i=0;
	faxarray = '';
	websitearray = '';
	sourcearray = '';
	var destination = 'destination' + index;
	$('#mergeform-error').html('');
	if($('#'+destination).is(':checked') == true)
	{
		var source = 'source' + i;
		var ignore = 'ignore' + index;
		$('#'+ignore).attr('checked',false);
		while(i < totalforms)
		{
			if(i == index)
			{
				if(i!=totalforms)
				{
					var j=0;
					while(j< totalforms)
					{
						source = 'source' + j;
						destination = 'destination' + j;
						ignore = 'ignore' + j;
						if(j == i)
						{
							$('#'+destination).attr('checked',true);
							var form = ('customerdetails' + j);
							gridtoform(form,'destination');
						}
						else if($('#'+ignore).is(':checked') == true)
						{
							$('#'+source).attr('checked',false);
							$('#'+destination).attr('checked',false);
						}
						else
						{
							$('#'+source).attr('checked',true);
							var form = ('customerdetails' + j);
							gridtoform(form,'source');
						}
						j++;
					}
				}

			}
			else
			{
				$('#'+source).attr('checked',true);
			}
			i++;
		}
	}
}

//------------------------Function to display records to mergedetails form based on click of source,dest or ignore---------------
function gridtoform(form,type)
{
	$('#mergedetails').show();
	var mergeform = $('#mergeform');
	var form = $('#'+ form);
	switch(type)
	{
		case 'source':
					if(sourcearray == '')
					{
						sourcearray = $('#slno',form).val();
						$('#srchiddencustomerid',mergeform).val(sourcearray);
					}
					else 
					{
						sourcearray = sourcearray + ',' + $('#slno',form).val();
						displayrecord('customerid',sourcearray);
					}
					var fax = $('#h_fax',form).val();
					if(faxarray  == '')
					{
						faxarray = fax;
						$('#fax',mergeform).val(fax);
					}
					else if(faxarray == fax)
					{
						$('#fax',mergeform).val(fax);
					}
					else
					{
						if(fax == '')
						{
							displayrecord('fax',faxarray);
						}
						else
						{
							faxarray = faxarray + ',' + fax;
							displayrecord('fax',faxarray);
						}
					}
					var website = $('#h_website',form).val();
					website = website.toLowerCase();
					if(websitearray  == '')
					{
						websitearray = website;
					}
					else if(websitearray == website)
					{
						$('#website',mergeform).val(website);
					}
					else
					{
						if(website == '')
						{
							displayrecord('website',websitearray);
						}
						else
						{
							websitearray = websitearray + ',' + website;
							displayrecord('website',websitearray);
						}
					}
					contactdetailstoform(totalforms);
					break;
			
		case 'destination' :
					$('#customerid',mergeform).val($('#customerid',form).val()); 
					$('#destcustomerid',mergeform).val($('#slno',form).val());
					$('#businessname',mergeform).val($('#h_businessname',form).val());
					$('#address',mergeform).val($('#h_address',form).val());
					$('#place',mergeform).val($('#h_place',form).val());
					var state = $('#h_state',form).val();
					$('#state',mergeform).val(state);
					getdistrictmerge('mergedistrictcodedisplay',$('#h_state',form).val());
					$('#district',mergeform).val($('#h_district',form).val());
					$('#pincode',mergeform).val($('#h_pincode',form).val());
					$('#remarks',mergeform).val($('#h_remarks',form).val());
					$('#dealer',mergeform).val($('#h_dealer',form).val());
					$('#stdcode',mergeform).val($('#h_stdcode',form).val());
					
					var fax = $('#h_fax',form).val();
					if(faxarray  == '')
					{
						faxarray = fax;
						$('#fax',mergeform).val(fax);
					}
					else if(faxarray == fax)
					{
						$('#fax',mergeform).val(fax);
					}
					else
					{
						if(fax == '')
						{
							displayrecord('fax',faxarray);
						}
						else
						{
							faxarray = faxarray + ',' + fax;
							displayrecord('fax',faxarray);
						}
					}
				
					var website = $('#h_website',form).val();
					website = website.toLowerCase();
					if(websitearray  == '')
					{
						websitearray = website;
						$('#website',mergeform).val(website);
					}
					else if(websitearray == website)
					{
						$('#website',mergeform).val(website);
					}
					else
					{
						websitearray = websitearray + ',' + website;
						displayrecord('website',websitearray);
					}
					$('#type',mergeform).val($('#h_type',form).val());
					$('#category',mergeform).val($('#h_category',form).val());
					//alert(form.h_category.value);
					$('#category',mergeform).val($('#h_category',form).val());
					$('#region',mergeform).val($('#h_region',form).val());
					$('#branch',mergeform).val($('#h_branch',form).val());
					contactdetailstoform(totalforms);
					break;
					
		case 'ignore':
					return false;
					break;
	}
	validateforemptyfields(totalforms);
	
}

//------------------------Function to check the duplicates and to display them on merge details form---------------
function displayrecord(tdname,myarray)
{
	var mergeform = $('#mergeform');
	var array= new Array;
	var splitarray = myarray.split(',');
	if(tdname == 'customerid')
	{
		$('#srchiddencustomerid',mergeform).val(myarray);
	}
	else if(tdname == 'fax')
	{
		var count=1;
		var splitarray = myarray.split(',');
		for(var i=0;i < splitarray.length;i++)
		{
			if(splitarray[i] == splitarray[i+1])
			{
				splitarray[i] = '' ;
				if(count ==  1)
				{
					array = array + splitarray[i];
				}
			}
			else
			{
				if(array ==  '')
				{
					array = splitarray[i];
				}
				else
				{
					array = array + ',' + splitarray[i];
				}
				
			}
		}
		$('#fax',mergeform).val(array);
	}
	else if(tdname == 'website')
	{
		var count=1;
		var splitarray = myarray.split(',');
		for(var i=0;i < splitarray.length;i++)
		{
			if(splitarray[i] == splitarray[i+1])
			{
				splitarray[i] = '' ;
				if(count ==  1)
				{
					array = array + splitarray[i];
				}
			}
			else
			{
				if(array ==  '')
				{
					array = splitarray[i];
				}
				else
				{
					array = array + ',' + splitarray[i];
				}
				
			}
		}
		$('#website',mergeform).val(array);
	}
}

//-------------Function called on click of Ignore Check Box------------------------------------
function ignorecheckbox(id,totalformcount)
{
	var destination = 'destination' + id;
	var source = 'source' + id;
	var ignore = 'ignore' + id;
	
	if($('#'+destination).is(':checked') == true)
	{
		alert('You cannot ignore the record which is destination.Please select other destination');
		$('#'+ignore).attr('checked',false);
	}
	else
	{
		var resultmsg = validateonignore(totalformcount);
		if(resultmsg)
		{
			alert(resultmsg);
			return false;
		}
		else
		{
			/*var destination = 'destination' + id;
			var source = 'source' + id;
			var ignore = 'ignore' + id;*/
			 if($('#'+source).is(':checked') == true)
			{
				$('#'+source).attr('checked',false);
			}
			else if($('#'+ignore).is(':checked') == false)
			{
				$('#'+source).attr('checked',true);
			}
			else if($('#'+ignore).is(':checked') == true)
			{
				$('#'+source).attr('checked',false);
				$('#'+destination).attr('checked',false);
			}
			gridtoformonignore(totalformcount);
		}
	}
}

//----------------------- Reformation of Merging Details on click of ignore.-------------------------------
function gridtoformonignore(totalformcount)
{
	var error = $('#mergeform-error');
	error.html('');
	var i = 0;
	var source;
	var destination;
	var ignore;
	sourcearray = '';
	faxarray = '';
	websitearray = '';
	while(i < totalformcount)
	{
		source = 'source' + i;
		destination = 'destination' + i;
		ignore = 'ignore' + i;
		if($('#'+source).is(':checked') == true)
		{
			var form = ('customerdetails' + i);
			gridtoform(form,'source');
		}
		else if($('#'+destination).is(':checked') == true)
		{
			var form = ('customerdetails' + i);
			gridtoform(form,'destination');
		}
		else if($('#'+ignore).is(':checked') == true)
		{
			var form = ('customerdetails' + i);
			gridtoform(form,'ignore');
		}
		i++;
	}
}

//-------------Function called on click of source radio button-------------------------
function sourceradioclick(id,totalformcount)
{
	var resultmsg = validate(totalformcount);
	if(resultmsg)
	{
		alert(resultmsg);
		return false;
	}
	else
	{
		gridtoformonignore(totalformcount);
	}
}

//---------------------Validations on ignore Click--------------------------------------
function validateonignore(totalformcount)
{
	var message = '';
	sourcecount = 0;
	destinationcount = 0;
	ignorecount = 0;
	for(var i=0; i<totalformcount ;i++)
	{
		var source = 'source' + i;
		var destination = 'destination' + i;
		var ignore = 'ignore' + i;
		if($('#'+source).is(':checked') == true)
		{
			sourcecount++;
		}
	}
	if(sourcecount -1== 0)
	{
		for(var j=0; j<totalformcount ;j++)
		{
			source = 'source' + j;
			destination = 'destination' + j;
			ignore = 'ignore' + j;
			if($('#'+source).is(':checked') == true && $('#'+ignore).is(':checked') == true)
			{
				message = "Cannot Ignore. There should be minimum one source to perform a merge.";
				$('#'+ignore).attr('checked',false);
			}
		}
	}
	return message;
}

//--------------------------Validations on click of source and destination-----------------------------
function validate(totalformcount)
{
	var message; 
	sourcecount = 0;
	destinationcount = 0;
	var i=0,source,destination,ignore;
	
	while(i < totalformcount)
	{
		source = 'source' + i;
		destination = 'destination' + i;
		ignore = 'ignore' + i;
		
		if($('#'+source).is(':checked') == true)
		{
			sourcecount++;
			if(sourcecount == totalformcount )
			{
				--i;
				source = 'source' + i;
				destination = 'destination' + i;
				message = 'There should be one destination for merging.';
				$('#'+source).attr('checked',false);
				$('#'+destination).attr('checked',true);
			
			}
			
			else if(sourcecount-1 == 0)
			{
				if($('#'+source).is(':checked') == true && $('#'+ignore).is(':checked') == true)
				{
					$('#'+ignore).attr('checked',false);
				}
				else
				{
					message = 'There should be atleast one source for merging.You cannot ignore. ';
				}
			}
			/*else
			{
				message ='';
			}*/
		}
		
		else if($('#'+destination).is(':checked') == true)
		{
			destinationcount++;
			if(destinationcount > 1)
			{
				message = 'There can be only one destination for merging.';
			}
			/*else
			{
				message = '';
			}*/
			
		}
		else if($('#'+ignore).is(':checked') == true)
		{
			ignorecount++;
			if(ignorecount == totalformcount )
			{
				message = 'No values found to merge';
			}
			/*else 
			{
				message = '';
			}*/
		}
		i++;
	}
	return message;
}

//-----------------------------Validations on click of Merge button----------------------------
function validateonmergeclick(totalformcount)
{
	var message = '';
	var sourcecount = 0;
	var destinationcount = 0;
	var i;
	for(i=0; i<totalformcount; i++)
	{
		var source = 'source' + i;
		var destination = 'destination' + i;
		var ignore = 'ignore' + i;
		if($('#'+source).is(':checked') == true)
		{
			sourcecount++;
		}
		else if($('#'+destination).is(':checked')== true)
		{
			destinationcount++;
		}
		else if($('#'+ignore).is(':checked') == true)
		{
			ignorecount++;
		}
	}
	if(sourcecount == 0)
	{
		message = 'There should be atleast one source for merging.';
	}
	else if(totalformcount == sourcecount)
	{
		message = 'There should be one destination for merging.';
		$('#'+source).attr('checked',false);
	}
	else if(destinationcount > 1)
	{
		message = 'There can be only one source for merging.';
	}
	else if(ignorecount == totalformcount )
	{
		message = 'No values found to merge';
	}
	return message;
	
}

function validatecustid(totalformcount)
{
	var message = '';
	var sourcecount = 0;
	var destinationcount = 0;
	var i;
	for(i=0; i<totalformcount; i++)
	{
		var source = 'source' + i;
		var destination = 'destination' + i;
		var ignore = 'ignore' + i;
		var form = ('customerdetails' + i);
		var form1 = $('#'+form);
		if($('#'+destination).is(':checked') == true)
		{
			if($('#customerid',form1).val() == '---')
			{
				message = "Destination Customer details is not having Customer ID. Please select a different destination.";
			}
		}	
	}
	return message;		
}

//--------------------------------Function to merge the customers.-----------------------------
function mergecustomers()
{
	var error = $('#form-error1');
	var mergeform = $('#mergeform');
	var resultmessage = validateonmergeclick(totalforms);
	var namevalues = '';
	var phonevalues = '';
	var cellvalues = '';
	var emailvalues = '';
	var contactarray = '';
	if(resultmessage != '')
	{
		alert(resultmessage);
		return false;
	}
	else
	{
		var resultmessage = validatecustid(totalforms);
		if(resultmessage != '')
		{
			alert(resultmessage);
			return false;
		}
		else
		{
			var field = $('#businessname',mergeform);
			if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
			else if(!validatebusinessname(field.val())) { alert('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.'); field.focus(); return false; } 
			var field = $("#place",mergeform);
			if(!field.val()) { alert("Enter the Place. "); field.focus(); return false; }
			var field = $("#state",mergeform);
			if(!field.val()) {alert("Select the State. "); field.focus(); return false; }
			var field = $("#district",mergeform);
			if(!field.val()) { alert("Select the District. "); field.focus(); return false; }
			var field = $("#pincode",mergeform);
			if(!field.val()) { alert("Enter the PinCode. "); field.focus(); return false; }
			else if(!validatepincode(field.val())) { alert('Enter the valid PIN Code.'); field.focus(); return false; } 
			//var field = $("#remarks",mergeform);
			//if(!field.val()) { alert("Select the Remarks. "); field.focus(); return false; }
			
			var field = $("#stdcode",mergeform);
			if(field.val()) { if(!validatestdcode(field.val())) { alert('Enter the valid STD Code.'); field.focus(); return false; } }
			var field = $("#dealer",mergeform);
			if(!field.val()) {alert("Select the proper dealer name from the list."); field.focus(); return false;}
			var field = $("#fax",mergeform);
			if(field.val()) { if(!validatephone(field.val())) { alert('Enter the valid Fax Number.'); field.focus(); return false; } }
			var field = $("#website",mergeform)
			if(field.val())	{ if(!validatewebsite(field.val())) { alert('Enter the valid Website.'); field.focus(); return false; } }
			var field = $("#region",mergeform);
			if(!field.val()) { alert("Enter the Region."); field.focus(); return false; }
			var field = $("#branch",mergeform);
			if(!field.val()) { alert("Select the Branch."); field.focus(); return false; }
			
			var rowcount = $('#adddescriptionrows tr').length;
			
			var l=1;
			if(rowcount != '')
			{
				tabopen5('2','tabg1');
				while(l<=(rowcount/13))
				{
					
					if(!$("#selectiontype1").val())
					{
							alert("Minimum of ONE contact detail is mandatory"); return false;
					}
					else
					var field = $("#"+'selectiontype'+l);
					if(!field.val()) { alert("Select the Type. "); field.focus(); return false; }
					var field = $("#"+'phone'+l);
					if(field.val()) { if(!validatephone(field.val())) { alert('Enter the valid (One) Phone Number.'); field.focus(); return false; } }
					var field = $("#"+'cell'+l);
					if(field.val()) { if(!cellvalidation(field.val())) { alert('Enter the valid (One) Cell Number.'); field.focus(); return false; } }
					var field = $("#"+'emailid'+l);
					if(field.val()) { if(!checkemail(field.val())) { alert('Enter the valid (One) Email Id.'); field.focus(); return false; } }
					var field = $("#"+'name'+l);
					if(field.val()) { if(!contactpersonvalidate(field.val())) { alert('Contact person name contains special characters. Please use only Numeric / space.'); field.focus(); return false; } }
					l++;
				}
				for(j=1;j<=(rowcount/13);j++)
				{
					var typefield = $("#"+'selectiontype'+j);
					var namefield = $("#"+'name'+j); 
					if(namevalues == '')
						 namevalues = namefield.val();
					else
						 namevalues = namevalues + '~' + namefield.val();
					var phonefield = $("#"+'phone'+j);
					if(phonevalues == '')
						 phonevalues = phonefield.val();
					else
						 phonevalues = phonevalues + '~' + phonefield.val();
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
			}	
			error.html('');
			
			var value = "This will merge all records to "+$('#businessname').val()+" .Are you Sure to continue?";
			var confirmation = confirm (value);
			if(confirmation)
			{
				var passdata = "switchtype=mergecustomer&sourcecustomerid=" + encodeURIComponent($('#srchiddencustomerid',mergeform).val()) +"&destcustid="+encodeURIComponent($('#destcustomerid',mergeform).val())+"&businessname="+encodeURIComponent($('#businessname',mergeform).val())+"&address="+encodeURIComponent($('#address',mergeform).val())+"&place="+encodeURIComponent($('#place',mergeform).val())+"&state="+encodeURIComponent($('#state',mergeform).val())+"&district="+encodeURIComponent($('#district',mergeform).val())+"&pincode="+encodeURIComponent($('#pincode',mergeform).val())+"&remarks="+encodeURIComponent($('#remarks',mergeform).val())+"&dealer="+encodeURIComponent($('#dealer',mergeform).val())+"&stdcode="+encodeURIComponent($('#stdcode',mergeform).val())+"&fax="+encodeURIComponent($('#fax',mergeform).val())+"&website="+encodeURIComponent($('#website',mergeform).val())+"&type="+encodeURIComponent($('#type',mergeform).val())+"&category="+encodeURIComponent($('#category',mergeform).val())+"&region="+encodeURIComponent($('#region',mergeform).val())+"&branch="+encodeURIComponent($('#branch',mergeform).val())+"&contactarray="+encodeURIComponent(contactarray)+"&deletearray="+encodeURIComponent(deletearray)+"&dummy=" + Math.floor(Math.random()*10054300000);
				//alert(passdata);
				$('#form-error1').html(getprocessingimage());
				var queryString = "../ajax/mergecustomerlist.php";//alert(queryString);
				ajaxcall3 = $.ajax(
				{
					type: "POST",url: queryString, data: passdata, cache: false,dataType: "json",
					success: function(ajaxresponse,status)
					{	
						if(ajaxresponse == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else
						var response = ajaxresponse; 
						if(response == 'Thinking to redirect')
						{
							window.location = "../logout.php";
							return false;
						}
						else if(response['errorcount'] == '1')
						{
							alert(response['errormsg']);
							mergearray();
						}
					}, 
					error: function(a,b)
					{
						$("#form-error").html(scripterror());
					}
				});
			}
			else
			{
				error.html('');
			}
		}
	}
}

function validateforemptyfields(totalformcount)
{
	//alert('here'+totalformcount);
	var i=0;
	var j=0;
	var mergeform = $('#mergeform');
	while(i < totalformcount)
	{
		source = 'source' + i;
		destination = 'destination' + i;
		ignore = 'ignore' + i;
		if($('#'+source).is(':checked') == true)
		{
			break;
		}
		i++;
	}
	var form = ('customerdetails' + i);
	var form1 = $('#'+form);
	if($('#address',mergeform).val() == '')
	{
		$('#address',mergeform).val($('#h_address',form1).val());
	}
	if($('#pincode',mergeform).val() == '')
	{
		$('#pincode',mergeform).val($('#h_pincode',form1).val());
	}
	if($('#remarks',mergeform).val() == '')
	{
		$('#remarks',mergeform).val($('#h_remarks',form1).val());
	}
	if($('#stdcode',mergeform).val() == '')
	{
		$('#stdcode',mergeform).val($('#h_stdcode',form1).val());
	}
	if($('#fax',mergeform).val() == '')
	{
		$('#fax',mergeform).val($('#h_fax',form1).val());
	}
	if($('#website',mergeform).val() == '')
	{
		$('#website',mergeform).val($('#h_website',form1).val());
	} //alert($('#h_type',form1).val());alert($('#h_category',form1).val());
	if($('#type',mergeform).value == '' && $('#h_type',form1).val() != '')
	{
		$('#type',mergeform).val($('#h_type',form1).val());
	}
	if($('#category',mergeform).val() == '' && $('#h_category',form1).val() != '')
	{
		$('#category',mergeform).val($('#h_category',form1).val()); 
	}
	if($('#region',mergeform).val() == '' && $('#h_region',form1).val() != '')
	{
		$('#region',mergeform).val($('#h_region',form1).val());
	}
	if($('#branch',mergeform).val() == '' && $('#h_branch',form1).val() != '')
	{
		$('#branch',mergeform).val($('#h_branch',form1).val());
	}
	else if(i < totalformcount)
	{
		while(i < totalformcount)
		{
			source = 'source' + i;
			if($('#'+source).is(':checked'))
			{
				form = $('#customerdetails' + i);
				if($('#address',mergeform).val() == '' && $('#h_address',form1).val() != '')
				{
					$('#address',mergeform).val($('#h_address',form1).val());
				}
				if($('#pincode',mergeform).val() == '' && $('#h_pincode',form1).val() != '')
				{
					$('#pincode',mergeform).val($('#h_pincode',form1).val());
				}
				if($('#remarks',mergeform).val() == '' && $('#h_remarks',form1).val() != '')
				{
					$('#remarks',mergeform).val($('#h_remarks',form1).val());
				}
				if($('#stdcode',mergeform).val() == '' && $('#h_stdcode',form1).val() != '')
				{
					$('#stdcode',mergeform).val($('#h_stdcode',form1).val());
				}
				if($('#fax',mergeform).val() == '' && $('#h_fax',form1).val() != '')
				{
					$('#fax',mergeform).val($('#h_fax',form1).val());
				}
				if($('#type',mergeform).val() == '' && $('#h_website',form1).val() != '')
				{
					$('#type',mergeform).val($('#h_website',form1).val());
				}
				if($('#type',mergeform).val() == '' && $('#h_type',form1).val() != '')
				{
					$('#type',mergeform).val($('#h_type',form1).val());
				}
				if($('#category',mergeform).val() == '' && $('#h_category',form1).val() != '')
				{
					$('#category',mergeform).val($('#h_category',form1).val());
				}
				if($('#region',mergeform).val() == '' && $('#h_region',form1).val() != '')
				{
					$('#region',mergeform).val($('#h_region',form1).val());
				}
				if($('#branch',mergeform).val() == '' && $('#h_branch',form1).val() != '')
				{
					$('#branch',mergeform).val($('#h_branch',form1).val());
				}
				else 
				{
					break;
				}
			}
			i++;
		}
	}
}


function clearform()
{
	$('#mergedetails').hide();
	$('#tabc2').hide();
	$('#tabc1').show();
	$('#headerline').show();
}


//To add description rows
function adddescriptionrows()
{
	$('#form-error1').html('');
	var rowcount = ($('#adddescriptionrows tr').length);
	if(rowcount == 13)
	slno = (rowcount/13) + 1;
	else
	slno = (rowcount/13) + 1;

	rowid = 'removedescriptionrow'+ slno;
	var value = 'contactname'+slno;
	
	var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></td></tr><tr><td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_name" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" id="m_phone" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left" id="m_cell" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left" id="m_emailid" style="font-weight:bold" height="15px">&nbsp;</td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" /></td>  </tr>';
	
	$("#adddescriptionrows").append(row);
	$('#'+value).html(slno);
	if(slno == 10)
		$('#adddescriptionrowdiv').hide();
	else
		$('#adddescriptionrowdiv').show();
}

//Remove description row
function removedescriptionrows(rowid,rowslno)
{
	
	if(deletearray == '')
		deletearray = $('#contactslno'+rowslno).val();
	else
		deletearray = deletearray  + ',' + $('#contactslno'+rowslno).val(); 
	var error = $('#form-error1');
	$('#adddescriptionrowdiv').show();
	var rowcount = $('#adddescriptionrows tr').length;
	if(rowcount == 13)
	{
		alert("Minimum of ONE contact detail is mandatory"); 
		return false;
	}
	else
	{
		$('#'+rowid).remove();
		var countval = 0;
		for(i=1;i<=(rowcount/13);i++)
		{
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

// Contact details to form 

function contactdetailstoform(totalformcount)
{
	// put the source and destination contact details to an array seperately
	var sourcecontactdetails = '';
	var destinationcontactdetails = '';
	for(var i = 0;i < totalformcount;i++)
	{
		source = 'source' + i;
		destination = 'destination' + i;
		ignore = 'ignore' + i;
		var form = ('customerdetails' + i);
		var form1 = $('#'+form);
		if($('#'+source).is(':checked') == true)
		{
			if(sourcecontactdetails == '')
				sourcecontactdetails = $('#h_contactarray',form1).val();
			else 
				sourcecontactdetails = sourcecontactdetails + '****' + $('#h_contactarray',form1).val();
		}
		else if($('#'+destination).is(':checked') == true)
		{
			if(destinationcontactdetails == '')
				destinationcontactdetails = $('#h_contactarray',form1).val();
			else 
				destinationcontactdetails = destinationcontactdetails + '****' + $('#h_contactarray',form1).val();
			//destinationcontactdetails = $('#h_contactarray',form1).val();
		}
		
	} 
	allcontactdetails = sourcecontactdetails + '****' + destinationcontactdetails; //alert(allcontactdetails)
	splitcustomers = allcontactdetails.split("****");
	valuespartarray = new Array();
	slnopartarray = new Array();
	for(i=0; i < splitcustomers.length; i++)
	{
		splitvalues = splitcustomers[i].split("#");
		part1 = splitvalues[0] + "#" + splitvalues[1] + "#" + splitvalues[2] + "#" + splitvalues[3]+ "#" + splitvalues[4]; 
		part2 = splitvalues[5];	
		valuespartarray[i] = part1;
		slnopartarray[i] = part2;
	}
	finalarrayvalue = new Array();
	finalarrayslno = new Array();
	//alert(allcontactdetails)
	for(i=0; i < splitcustomers.length; i++)
	{
		if(finalarrayvalue.length > 0 && inArray(valuespartarray[i],finalarrayvalue))
		{
			nextelement = deletearray.length;
			deletearray[nextelement] = slnopartarray[i];
			
		}
		else
		{
			nextelement = finalarrayvalue.length;
			finalarrayvalue[nextelement] = valuespartarray[i];
			finalarrayslno[nextelement] = slnopartarray[i];
		}
	}
	finalstring = "";deletestring = "";
	for(i=0; i < finalarrayvalue.length; i++)
	{
		finalstring = finalstring + finalarrayvalue[i] + "#" + finalarrayslno[i];
		if(i < finalarrayvalue.length - 1)
			finalstring = finalstring + "*";
	}
	//alert(finalarrayvalue.length)
	// To add all contact details to merge form
	if(finalarrayvalue.length > 0)
	{
		var totalrow = finalstring.split('*');
		$('#adddescriptionrows tr').remove();
		for(k=1;k<=totalrow.length;k++)
		{
			slno = k;
			rowid = 'removedescriptionrow'+ slno;
			var value = 'contactname'+slno;
			if(k == 10)
				$('#adddescriptionrowdiv').hide();
			else if(k < 10)
				$('#adddescriptionrowdiv').show();
			else 
				$('#adddescriptionrowdiv').hide();
			var row = '<tr id="removedescriptionrow'+ slno+'"><td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td><span id="contactname'+ slno+'" style="font-weight:bold">&nbsp;</span></td></tr><tr><td>&nbsp;</td></tr></table></div></td><td width="11%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><select name="selectiontype'+ slno+'" id="selectiontype'+ slno+'" style="width:120px" class="swiftselect-mandatory"><option value="" selected="selected" >--Select--</option><option value="general" >General</option><option value="gm/director">GM/Director</option><option value="hrhead">HR Head</option><option value="ithead/edp">IT-Head/EDP</option><option value="softwareuser" >Software User</option><option value="financehead">Finance Head</option><option value="others" >Others</option></select></td></tr><tr><td  style="font-weight:bold" height="15px" align="left"><span id="m_type'+ slno+'"></span ><span id="t_type'+ slno+'"></span></td></tr></table></div></td><td width="16%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="name'+ slno+'" type="text" class="swifttext" id="name'+ slno+'"   autocomplete="off" style="width:170px" /></td></tr><tr><td align="left" style="font-weight:bold" height="15px"><span id="m_name'+ slno+'"></span><span id="t_name'+ slno+'"></span></td></tr></table></div></td><td width="18%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="phone'+ slno+'" type="text" class="swifttext" id="phone'+ slno+'"  autocomplete="off" style="width:170px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_phone'+ slno+'"></span><span id="t_phone'+ slno+'"></span></td></tr></table></div></td><td width="15%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="cell'+ slno+'" type="text" class="swifttext" id="cell'+ slno+'"   autocomplete="off" style="width:120px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_cell'+ slno+'"></span><span id="t_cell'+ slno+'"></span></td></tr></table></div></td><td width="27%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2"><tr><td align="left"><input name="emailid'+ slno+'" type="text" class="swifttext" id="emailid'+ slno+'"  autocomplete="off" style="width:200px" /></td></tr><tr><td align="left"  style="font-weight:bold" height="15px"><span id="m_emailid'+ slno+'"></span><span id="t_emailid'+ slno+'"></span></td></tr></table></div></td><td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv'+ slno+'" onclick ="removedescriptionrows(\'' + rowid +'\',\'' + slno +'\')" style="cursor:pointer;">X</a></strong></font><input type="hidden" name="contactslno'+ slno+'" id="contactslno'+ slno+'" value=""/></td>  </tr>';
		$("#adddescriptionrows").append(row);
		$('#'+value).html(slno);
			
		}
		splitvalue = new Array();
		for(var i=0;i<totalrow.length;i++)
		{
			splitvalue[i] =  totalrow[i].split('#');
			$("#"+'selectiontype'+(i+1)).val(splitvalue[i][0]);
			$("#"+'name'+(i+1)).val(splitvalue[i][1]);
			$("#"+'phone'+(i+1)).val(splitvalue[i][2]);
			$("#"+'cell'+(i+1)).val(splitvalue[i][3]);
			$("#"+'emailid'+(i+1)).val(splitvalue[i][4]);
			$("#"+'contactslno'+(i+1)).val(splitvalue[i][5]);
		}	
	}
}

