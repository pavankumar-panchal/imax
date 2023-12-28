var deployerarray = new Array();

function formsubmit(command)
{
	var form = $("#submitform");
	var error = $("#form-error");
	if(command == 'save')
	{
		var field = $("#businessname");
		if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		if(field.val()) { if(!validatebusinessname(field.val())) { error.html(errormessage('Business name contains special characters. Please use only Alpha / Numeric / space / hyphen / small brackets.')); field.focus(); return false; } }
		var field = $("#contactperson");
		if(!field.val()) { error.html(errormessage("Enter the Name of the Contact Person. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecontactperson(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space / comma.')); field.focus(); return false; } }
		var field = $("#place");
		if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
		var field = $("#state");
		if(!field.val()) { error.html(errormessage("Enter the State. ")); field.focus(); return false; }
		var field = $("#district");
		if(!field.val()) { error.html(errormessage("Enter the District. ")); field.focus(); return false; }
		var field = $("#pincode" );
		if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
		if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
		var field = $("#stdcode");
		if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
		var field = $("#phone");
		if(!field.val()) { error.html(errormessage("Enter the Phone Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
		var field = $("#cell");
		if(!field.val()) { error.html(errormessage("Enter the Mobile Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecell(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
		var field = $("#personalemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Personal Email ID.')); field.focus(); return false; } }
		var field = $("#emailid");
		if(!field.val()) { error.html(errormessage("Enter the Email ID. ")); field.focus(); return false; }
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
		var field = $("#tlemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid TL Email ID.')); field.focus(); return false; } }
		var field = $("#mgremailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Manager Email ID.')); field.focus(); return false; } }
		var field = $("#hoemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid HO Email ID.')); field.focus(); return false; } }
		var field = $("#region");
		if(!field.val()) { error.html(errormessage("Enter the Region.")); field.focus(); return false; }
		var field = $("#designtype");
		if(!field.val()) { error.html(errormessage("Enter the Type.")); field.focus(); return false; }
		var field = $("#deployerusername");
		if(!field.val()) { error.html(errormessage("Enter the User Name. ")); field.focus(); return false; }
		if(field.val())	{ if(!validateusername(field.val())) { error.html(errormessage('User Name should not contain space.')); field.focus(); return false; } }
		var field = $('#coordinator:checked').val();
		var coordinator;
		if(field != 'on') coordinator = 'no'; else  coordinator = 'yes';

		var field = $('#handhold:checked').val();
		var handhold;
		if(field != 'on') handhold = 'no'; else  handhold = 'yes';

		var field = $('#disablelogin:checked').val();
		var disablelogin;
		if(field != 'on') disablelogin = 'no'; else disablelogin = 'yes';
		var field = $('#deployernotinuse:checked').val();
		var deployernotinuse;
		if(field != 'on') deployernotinuse = 'no'; else deployernotinuse = 'yes';
		var branchid = $("#branchid").val();
		if(branchid == 'all' &&  coordinator == 'no')
		{error.html(errormessage('If branch is "Not Applicable", Co-ordinator must be checked.')); $("#coordinator").focus(); return false;}
		var passData = "";
		passData =  "type=save&deployerid=" + encodeURIComponent($("#deployerid").val()) + "&implementerusername=" + encodeURIComponent($("#deployerusername").val()) + "&businessname=" + encodeURIComponent($("#businessname").val()) + "&contactperson=" + encodeURIComponent($("#contactperson").val()) + "&address=" + encodeURIComponent($("#address").val()) + "&place=" + encodeURIComponent($("#place").val()) + "&district=" + encodeURIComponent($("#district").val()) + "&state=" + encodeURIComponent($("#state").val()) + "&pincode=" + encodeURIComponent($("#pincode").val()) + "&stdcode=" + encodeURIComponent($("#stdcode").val()) + "&phone=" + encodeURIComponent($("#phone").val()) + "&cell=" + encodeURIComponent($("#cell").val()) + "&region=" + encodeURIComponent($("#region").val()) + "&designtype=" + encodeURIComponent($("#designtype").val()) + "&emailid=" + encodeURIComponent($("#emailid").val()) + "&website=" + encodeURIComponent($("#website").val()) + "&coordinator=" + encodeURIComponent(coordinator) + "&disablelogin=" + encodeURIComponent(disablelogin) + "&implementernotinuse=" + encodeURIComponent(deployernotinuse) + "&remarks=" + encodeURIComponent($("#remarks").val()) + "&personalemailid=" + encodeURIComponent($("#personalemailid").val())  + "&tlemailid=" + encodeURIComponent($("#tlemailid").val())+ "&mgremailid=" + encodeURIComponent($("#mgremailid").val()) + "&hoemailid=" + encodeURIComponent($("#hoemailid").val()) + "&password=" + encodeURIComponent($("#password").val())  + "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&branchid=" + encodeURIComponent($("#branchid").val()) + "&handhold=" + encodeURIComponent(handhold) + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete the selected deployer?");
			if(confirmation)
			{
				passData =  "type=delete&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			
			}
			else
			return false;
		}
		queryString = '../ajax/deployment.php';
		error.html(getprocessingimage());
		ajaxcall0 = $.ajax(
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
					var response = ajaxresponse.split('^');//alert(response)
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
						refreshdeploymentarray(); 
						newentry();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						refreshdeploymentarray(); 
						newentry();
					}
					else if(response[0] == '3')
					{
						error.html(errormessage(response[1]));
					}
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});		
}


function refreshdeploymentarray()
{
	var form = $("#filterform");
	var coordinator_type = $("input[name='coordinator_type']:checked").val();
	var login_type = $("input[name='login_type']:checked").val();
	var	passData = "type=generatedeployerlist&coordinator_type=" + encodeURIComponent(coordinator_type) + "&login_type=" + encodeURIComponent(login_type)  + "&deployerregion=" +encodeURIComponent($("#deployerregion").val());//alert(passData)
	$('#deployerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/deployment.php";
	ajaxcall1 = $.ajax(
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
				deployerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					deployerarray[i] = response[i];
				}
				getdeployerlist();
				$("#deployerselectionprocess").html('');
				$('#displayfilter').hide();
				$("#totalcount").html(deployerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$("#deployerselectionprocess").html(scripterror());
		}
	});		
}



function getdeployerlist()
{	
	var form = $('#submitform');	
	var selectbox = $('#deployerlist');
	var numberofcustomers = deployerarray.length;
	//alert(dealerarray);
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	for( var i=0; i<limitlist; i++)
	{
		var splits = deployerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function selectfromlist()
{
	var selectbox = $("#deployerlist option:selected").val();
	$('#detailsearchtext').val($("#deployerlist option:selected").text());
	$('#detailsearchtext').select();
	deployerdetailstoform(selectbox);	
}

function selectadeployer(input)
{
	var selectbox = $('#deployerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getdeployerlist();
	}
	else
	{
		//selectbox.options.length = 0;
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		
		var addedcount = 0;
		for( var i=0; i < deployerarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = deployerarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = deployerarray[i];
				}
				var result1 = pattern.test(trimdotspaces(deployerarray[i]).toLowerCase());
				var result2 = pattern.test(deployerarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = deployerarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}

function selectadeployer(input) {
	var selectbox = $('#deployerlist');
	if (input == "") {
		getdeployerlist();
	} else {
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		var addedcount = 0;
		for (var i = 0; i < deployerarray.length; i++) {
			// Check if any part of the name contains the input string
			if (deployerarray[i].toLowerCase().includes(input.toLowerCase())) {
				var splits = deployerarray[i].split("^");
				options[options.length] = new Option(splits[0], splits[1]);
				addedcount++;
				if (addedcount == 100) break;
			}
		}
	}
}


function deployersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrolldeployer('up');
	else if(KeyID == 40)
		scrolldeployer('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectadeployer(input);
	}
}

function newentry()
{
	var form = $("#submitform");
	$("#submitform" )[0].reset();
	$("#lastslno").val('');
	disabledelete();
	$('#displaypassworddfield').hide();
	$('#resetpwd').hide();
	$("#createddate").html('Not Available');
	$("#createdby").html('Not Available');
	$("#tabgroupgridc1_1").html('No datas found to be displayed.');
	$("#tabgroupgridc1linksearch").html('');
	$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');
}

function deployerdetailstoform(deployerid)
{
	if(deployerid != '')
	{
		$("#form-error").html('');
		var form = $("#submitform");
		var passData = "type=deployerdetailstoform&lastslno=" + encodeURIComponent(deployerid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$('#filterdiv').hide();
		$('#displayfilter').hide();
		$("#form-error").html(getprocessingimage());
		var queryString = "../ajax/deployment.php";
		ajaxcall7 = $.ajax(
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
					$("#form-error").html('');
					var response = ajaxresponse;//alert(response)
					if(response['businessname'] == '')
						{
								
							alert('Deployer Not Available.');
							newentry();
						}
						else
						{
							$("#lastslno").val(response['slno']);
							$("#deployerid").val(response['slno']);
							$("#businessname").val(response['businessname']);
							$("#contactperson").val(response['contactperson']);
							$("#address").val(response['address']);
							$("#place").val(response['place']);
							getdistrict('districtcodedisplay', response['state']);
							$("#state").val(response['state']);
							$("#district").val(response['district']);
							$("#pincode").val(response['pincode']);
							$("#stdcode").val(response['stdcode']);
							$("#phone").val(response['phone']);
							$("#cell").val(response['cell']);
							$("#region").val(response['region']);
							$("#emailid").val(response['emailid']);
							$("#website").val(response['website']);
							$("#personalemailid").val(response['emailid']);
							autochecknew($("#coordinator"),response['coordinator']);
							autochecknew($("#disablelogin"),response['disablelogin']);
							$("#remarks").val(response['remarks']);
							//$("#initialpassword").val(response[17]);
							//$("#passwordchanged").val(response[18]);
							$("#tlemailid").val(response['tlemailid']);
							$("#mgremailid").val(response['mgremailid']);
							$("#hoemailid").val(response['hoemailid']);
							$("#createddate").html(response['createddate']);
							$("#createdby").html(response['fullname']);
							$("#deployerusername").val(response['implementerusername']);
							$("#personalemailid").val(response['personalemailid']);
							$("#branchid").val(response['branchid']);
							autochecknew($("#deployernotinuse"),response['implementernotinuse']);
							$("#designtype").val(response['implementertype']);
							autochecknew($("#handhold"),response['handhold']);
							if(response['passwordchanged'] == 'n')
							{
								$('#initialpassworddfield').show();
								$("#initialpassword").val(response['initialpassword']);
								$('#displayresetpwd').hide();
							}
							else if(response['passwordchanged'] == 'y')
							{
								if(response['resetpwd'] == 'yes')
								{
									$('#displayresetpwd').show();
								}
								else
								{
									$('#displayresetpwd').hide();
								}
								$("#resetpassword").val(response['initialpassword']); 
								$('#initialpassworddfield').hide();
							}
							
							enabledelete();
							/*if(response[27] == '1')
							{
								enabledelete();
							}
							else
							{
								disabledelete();
							}*/
							if(response['resetpwd'] == 'yes') 
							{
								$('#displaypassworddfield').show();
								$('#resetpwd').hide();
								
							}
							else
							{
								$('#displayresetpwd').hide();
								$('#resetpwd').hide();
							}
						}
				}
			}, 
			error: function(a,b)
			{
				$('#form-error').html(scripterror());
			}
		});		
	}
}

function validatepwd()
{ 
	var form = $("#submitform"); 
	var deployerid = $("#lastslno").val();
	var businessname = $("#businessname").val();
	var error = $("#form-error");
	var field = $("#password");
	if(!field.val()){error.html(errormessage("Enter the Password")); return false; field.focus(); }
	else
	if(dealerid != '')
	{
		var confirmation = confirm("Do you really want to reset the Dealer login password for " + businessname + " ??");
		if(confirmation)
		{
			var passData  = "type=resetpwd&password=" + encodeURIComponent($("#password").val()) + "&deployerid=" + deployerid + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
			var queryString = "../ajax/deployment.php"; 
			ajaxcall3 = $.ajax(
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
						$("#form-error").html('');
						var response = ajaxresponse.split('^');//alert(response)
						if($("#initialpassworddfield").is(":visible"))
						{
							$("#initialpassworddfield").show();
							$("#displayresetpwd").hide();
							$('#initialpassword').val(response[1]);
							
						}
						else
						{
							$('#initialpassword').val(response[1]);
						}
						$('#form-error').html(successmessage('Password Updated Successfully'));
						$('#password').val('');
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});		

		}
		else
		error.html('');
		return false;
	}
	
}


function scrolldeployer(type)
{	
	var selectbox = $('#deployerlist');
	var totalcus = $("#deployerlist option").length;
	var selectedcus = $("select#deployerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#deployerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#deployerlist").attr('selectedIndex', selectedcus + 1);
	selectfromlist();
}


function validateusername(username)
{
	for (var i = 0 ; i < username.length ; i++)
	{
		var searchThis = username.indexOf(" ", i);
		if (searchThis < 0)
		{
			return true; break;
		}
		else
		{
			return false;
		}
	}
}


//Function to Search the data from Inventory/Dealers/Out Station Employee------------------------------------------
function searchfilter(startlimit)
{
	var form = $("#searchfilterform");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var orderby = $("#orderby").val();
	var region = $("#searchregion").val();
	var error = $("#filter-form-error");
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); $('#searchcriteria').focus(); return false; }
	var passData = "type=deployersearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&region=" + encodeURIComponent(region) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	error.html(getprocessingimage());
	var queryString = "../ajax/deployment.php";
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
				error.html('');
				var response = ajaxresponse.split('^');//alert(response)
				if(response[0] == '1')
				{
					error.html('');
					$("#tabgroupgridc1_1").html(response[1]);
					$("#tabgroupgridwb1").html("Total Count: " +  response[2]);
					$("#tabgroupgridc1linksearch").html(response[3]);
				}
				else
				{
					error.html("No datas found to be displayed.");
				}
				
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}

//Function to Search the data from Inventory/Dealers/Out Station Employee------------------------------------------
function getmoresearchfilter(startlimit,slnocount,showtype)
{
	var form = $("#searchfilterform");
	var textfield = $("#searchcriteria").val();
	var subselection = $("input[name='databasefield']:checked").val();
	var orderby = $("#orderby").val();
	var region = $("#searchregion").val();
	var error = $("#filter-form-error");
	if(!textfield) { error.html(errormessage("Enter the Search Text.")); form.searchcriteria.focus(); return false; }
	var passData = "type=deployersearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&region=" + encodeURIComponent(region) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData)
	$("#tabgroupgridc1linksearch").html(getprocessingimage());
	var queryString = "../ajax/deployment.php";
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
				error.html('');
				var response = ajaxcall10.responseText.split('^');
				if(response[0] == '1')
				{
					$("#resultgrid").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#resultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridwb1").html("Total Count: " + response[2]);
					$("#tabgroupgridc1linksearch").html(response[3]);
				}
				else
				{
					error.html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});		
}


function searchbydeployeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchbydeployer').val()
		deployerdetailstoform(input);
	}
}


function closefunc()
{
	var error = $('#form-error');
	$("#resetpwd").hide();
	$("#displaypassworddfield").show();
	error.html('');
	return false;
}

function Displaydiv1()
{
	$("#resetpwd").show();
	$("#displaypassworddfield").hide();
	return false;
}


