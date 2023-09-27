var dealerarray = new Array();

function hidematrixbilling()
{
	if($('#telecaller').is(':checked'))
	{
		$('#enablematrixbilling').removeAttr('checked');
		$('#enablematrixbilling').attr("disabled", true);
	}
	else
	{
		//$('#enablematrixbilling').attr('checked','checked');
		$('#enablematrixbilling').attr("disabled", false);
	}
}

function formsubmit(command)
{
	var form = $("#submitform");
	var error = $("#form-error");
	if(command == 'save')
	{
		
	    state_gst_code = $("#state_gst").html();
		state_gst_code = $.trim(state_gst_code);
	    //alert(state_gst_code);
	
	    var field = $("#gst_no" );
	    var fieldval = field.val();
		//alert(field.val());
		//if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		if(field.val()) { if(!validategstin(field.val())) { error.html(errormessage('For GSTIN only Alpha / Numeric are allowed.')); field.focus(); return false; } }
		if(field.val()) { if(!validategstinregex(field.val(),state_gst_code)) { error.html(errormessage('State GST Code Not Matching.')); field.focus(); return false; } }
	    if(field.val()) { if(fieldval.length != 15) { error.html(errormessage('GSTIN should be 15 chars.')); field.focus(); return false; } } 
		// if(fieldval!='')
		// {
		// 	var fieldpan = $("#panno");
		// 	if(fieldpan.val()!= '')
		// 	{
		// 		var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;    
		// 		if(!regex.test(fieldpan.val()))
		// 		{
		// 			error.html(errormessage('PAN NO is not valid.')); fieldpan.focus();  return false;
		// 		}
		// 	}
		// }
		// else
		// {
		// 	var fieldpan = $("#panno");
		// 	if(fieldpan.val() == '')
		// 	{
		// 		error.html(errormessage('PAN NO is mandatory for Non GSTIN dealer.')); fieldpan.focus();  return false;
		// 	}
		// 	else
		// 	{
		// 		var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;    
  		// 		if(!regex.test(fieldpan.val()))
		// 		{
		// 			error.html(errormessage('PAN NO is not valid.')); fieldpan.focus();  return false;
		// 		}
		// 	}
		// }
		
		
		var field = $("#businessname");
		if(!field.val()) { error.html(errormessage("Enter the Business Name [Company]. ")); field.focus(); return false; }
		//added on 17-sep-2019
		//var field1 = $("#dealertype");
		//alert($("#hiddealertype").val());
		//if($("#dealertype").val() == "") { error.html(errormessage("Select the Dealer Type.")); field1.focus(); return false; }
		var field = $("#billingname");
		if(!field.val()) { error.html(errormessage("Enter the Billing Name.")); field.focus(); return false; }
		var field = $("#contactperson");
		if(!field.val()) { error.html(errormessage("Enter the Name of the Contact Person. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecontactperson(field.val())) { error.html(errormessage('Contact person name contains special characters. Please use only Alpha / Numeric / space / comma.')); field.focus(); return false; } }
		var field = $("#place");
		if(!field.val()) { error.html(errormessage("Enter the Place. ")); field.focus(); return false; }
		var field = $("#state");
		if(!field.val()) { error.html(errormessage("Enter the State. ")); field.focus(); return false; }
		var field = $("#district");
		if(!field.val()) { error.html(errormessage("Enter the District. ")); field.focus(); return false; }
		var field = $("#phone");
		if(!field.val()) { error.html(errormessage("Enter the Phone Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatephone(field.val())) { error.html(errormessage('Enter the valid Phone Number.')); field.focus(); return false; } }
		var field = $("#cell");
		if(!field.val()) { error.html(errormessage("Enter the Mobile Number. ")); field.focus(); return false; }
		if(field.val()) { if(!validatecell(field.val())) { error.html(errormessage('Enter the valid Cell Number.')); field.focus(); return false; } }
		var field = $("#personalemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
		var field = $("#emailid");
		if(!field.val()) { error.html(errormessage("Enter the Email ID. ")); field.focus(); return false; }
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Email ID.')); field.focus(); return false; } }
		var field = $("#tlemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid TL Email ID.')); field.focus(); return false; } }
		var field = $("#mgremailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid Manager Email ID.')); field.focus(); return false; } }
		var field = $("#hoemailid");
		if(field.val())	{ if(!emailvalidation(field.val())) { error.html(errormessage('Enter the valid HO Email ID.')); field.focus(); return false; } }
		var field = $("#stdcode");
		if(field.val()) { if(!validatestdcode(field.val())) { error.html(errormessage('Enter the valid STD Code.')); field.focus(); return false; } }
		var field = $("#pincode");
		if(!field.val()) { error.html(errormessage("Enter the PinCode. ")); field.focus(); return false; }
		if(field.val()) { if(!validatepincode(field.val())) { error.html(errormessage('Enter the valid PIN Code.')); field.focus(); return false; } }
		var field = $('#relyonexecutive:checked').val();
		var relyonexecutive;
		if(field != 'on') relyonexecutive = 'no'; else  relyonexecutive = 'yes';
		var field = $('#disablelogin:checked').val();
		var disablelogin;
		if(field != 'on') disablelogin = 'no'; else disablelogin = 'yes';
		var field = $('#dealernotinuse:checked').val();
		var dealernotinuse;
		if(field != 'on') dealernotinuse = 'no'; else dealernotinuse = 'yes';
		var field = $('#telecaller:checked').val();
		var telecaller;
		if(field != 'on') telecaller = 'no'; else telecaller = 'yes';
		var field = $('#branchhead:checked').val();
		var branchhead;
		if(field != 'on') branchhead = 'no'; else branchhead = 'yes';


		
		//Added on 19th Jan
		
        		//var field = $('#maindealers:checked').val();
        		//var maindealers;
        		//if(field != 'on') maindealers = 'no'; else maindealers = 'yes';
        		
        		
       // var field = $('#dealerhead');
		//if(!field.val()) { error.html(errormessage("Enter the Branch.")); field.focus(); return false; }
		
		//Ends		
		
		var field = $('#enablebilling:checked').val();
		var enablebilling;
		if(field != 'on') enablebilling = 'no'; else enablebilling = 'yes';
		var field = $('#saifreepin:checked').val();
		var enablebilling;
		if(field != 'on') saifreepin = 'no'; else saifreepin = 'yes';

		var field = $('#enablematrixbilling:checked').val();
		var enablematrixbilling;
		if(field != 'on') enablematrixbilling = 'no'; else enablematrixbilling = 'yes';

		var field = $('#editcustdata:checked').val();
		var editcustdata;
		if(field != 'on') editcustdata = 'no'; else editcustdata = 'yes';

		var field = $('#forcesurrender:checked').val();
		var forcesurrender;
		if(field != 'on') forcesurrender = 'no'; else forcesurrender = 'yes';
		
		var field = $("#region");
		if(!field.val()) { error.html(errormessage("Enter the Region.")); field.focus(); return false; }
		var field = $("#branch");
		if(!field.val()) { error.html(errormessage("Enter the Branch.")); field.focus(); return false; }
		
		var field = $("#revenuesharenewsale");
		if(!field.val()) { error.html(errormessage("Enter the Revenue Share of the Dealer.")); field.focus(); return false; }
		if(field.val()){ if(!validatepercentage(field.val())) { error.html(errormessage("Revenue Share of the Dealer should be less than 100.")); field.focus(); return false; } }
		var field = $("#revenueshareupsale");
		if(!field.val()) { error.html(errormessage("Enter the Revenue Share of the Dealer.")); field.focus(); return false; }
		if(field.val()){ if(!validatepercentage(field.val())) { error.html(errormessage("Revenue Share of the Dealer should be less than 100.")); field.focus(); return false; } }
		var field = $("#taxname");
		if(!field.val()) { error.html(errormessage("Enter the Tax Name.")); field.focus(); return false; }
		var field = $("#dealerusername");
		if(!field.val()) { error.html(errormessage("Enter the User Name. ")); field.focus(); return false; }
		if(field.val())	{ if(!validateusername(field.val())) { error.html(errormessage('user Name should not contain space.')); field.focus(); return false; } }
		var field = $("#taxamount");
		if(field.val())	{ if(!validatepercentage(field.val())) { error.html(errormessage('Tax Rate should be less than 100.')); field.focus(); return false; } }
		if(!field.val()) { error.html(errormessage("Enter the Tax Rate.")); field.focus(); return false; }
			var passData = "";
			passData =  "type=save" + "&dealerid=" + encodeURIComponent($("#dealerid").val()) + "&gst_no=" + encodeURIComponent($("#gst_no").val()) + "&dealerusername=" + encodeURIComponent($("#dealerusername").val()) + "&businessname=" + encodeURIComponent($("#businessname").val()) + "&contactperson=" + encodeURIComponent($("#contactperson").val()) + "&address=" + encodeURIComponent($("#address").val()) + "&place=" + encodeURIComponent($("#place").val()) + "&district=" + encodeURIComponent($("#district").val()) + "&state=" + encodeURIComponent($("#state").val()) + "&pincode=" + encodeURIComponent($("#pincode").val()) + "&stdcode=" + encodeURIComponent($("#stdcode").val()) + "&phone=" + encodeURIComponent($("#phone").val()) + "&cell=" + encodeURIComponent($("#cell").val()) + "&region=" + encodeURIComponent($("#region").val()) + "&revenuesharenewsale=" + encodeURIComponent($("#revenuesharenewsale").val()) + "&revenueshareupsale=" + encodeURIComponent($("#revenueshareupsale").val()) + "&taxamount=" + encodeURIComponent($("#taxamount").val()) + "&taxname=" + encodeURIComponent($("#taxname").val()) + "&emailid=" + encodeURIComponent($("#emailid").val()) + "&website=" + encodeURIComponent($("#website").val()) + "&relyonexecutive=" + encodeURIComponent(relyonexecutive) + "&disablelogin=" + encodeURIComponent(disablelogin) + "&enablebilling=" + encodeURIComponent(enablebilling)+ "&enablematrixbilling=" + encodeURIComponent(enablematrixbilling) + "&telecaller=" + encodeURIComponent(telecaller) + "&dealernotinuse=" + encodeURIComponent(dealernotinuse) + "&remarks=" + encodeURIComponent($("#remarks").val()) + "&personalemailid=" + encodeURIComponent($("#personalemailid").val())  + "&tlemailid=" + encodeURIComponent($("#tlemailid").val())+ "&mgremailid=" + encodeURIComponent($("#mgremailid").val()) + "&hoemailid=" + encodeURIComponent($("#hoemailid").val()) + "&password=" + encodeURIComponent($("#password").val())  + "&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&branchhead=" + encodeURIComponent(branchhead) +"&saifreepin=" + encodeURIComponent(saifreepin)+ "&branch=" + encodeURIComponent($("#branch").val())+
				"&billingname=" + encodeURIComponent($("#billingname").val()) +"&dealertype=" + encodeURIComponent($("#dealertype").val()) + "&newdealertype=" + encodeURIComponent($("#newdealertype").val()) + "&panno=" + encodeURIComponent($("#panno").val()) + "&editcustdata=" + encodeURIComponent(editcustdata) + "&forcesurrender=" + encodeURIComponent(forcesurrender) + "&dummy=" + Math.floor(Math.random()*100000000);
		}
		else
		{
			var confirmation = confirm("Are you sure you want to delete the selected dealer?");
			if(confirmation)
			{
				passData =  "type=delete&lastslno=" + encodeURIComponent($("#lastslno").val()) + "&dummy=" + Math.floor(Math.random()*10000000000);
			
			}
			else
			return false;
		}
		queryString = '../ajax/dealer.php';
		error.html(getprocessingimage());
		console.log(passData);
		
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
					var response = ajaxresponse['errormessage'].split('^');
					
					if(response[0] == '1')
					{
						error.html(successmessage(response[1]));
						refreshdealerarray(); 
						newentry();
						cleargrid();
					}
					else if(response[0] == '2')
					{
						error.html(successmessage(response[1]));
						refreshdealerarray(); 
						newentry();
						cleargrid();

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

function changedealertype(deatype,deatypehead = "")
{
	var error = $("#form-error");
	var selectedValue = $("#dealertype").val();
	//alert(deatypehead);
	$("#hiddealertype").val($("#dealertype").find("option:selected").attr("value"));

		$.ajax({
			url: '../inc/dealertype.php',
			type: 'POST',
			data: {option : selectedValue},
			dataType: 'json',
			success: function(response)
			{
				//console.log(response);
				//alert(1+selectedValue);

				if(selectedValue == " ") {
					//alert("I m here!");
					$('#newdealertype').attr('disabled', true);
				} else {
					//alert("I m here2!");
					if ($('#newdealertype').is(':disabled'))
						$('#newdealertype').attr('disabled', false).css('background-color', '');

				}
				//alert(response);
				$("#newdealertype").html(response);
				$("#newdealertype").val(deatypehead);

			},
			error: function (response) {
				console.log("Something Went Wrong");
				//error.html(scripterror());
			}
	});
}

function newPopup(url) {
  var left = (screen.width/2)-(700/2);
  var top = (screen.height/2)-(600/2);
	popupWindow = window.open(url,'popUpWindow','height=600,width=700,top='+top+', left='+left+',toolbar=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,titlebar=no,location=no,');
		popupWindow.focus();
}

function refreshdealerarray()
{
	var form = $("#filterform");
	var relyonexcecutive_type = $("input[name='relyonexcecutive_type']:checked").val();
	var login_type = $("input[name='login_type']:checked").val();
	passData = "type=generatedealerlist&relyonexcecutive_type=" + encodeURIComponent(relyonexcecutive_type) + "&login_type=" + encodeURIComponent(login_type)  + "&dealerregion=" +encodeURIComponent($("#dealerregion").val());
	$('#dealerselectionprocess').html(getprocessingimage());
	queryString = "../ajax/dealer.php";
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
				dealerarray = new Array();
				for( var i=0; i<response.length; i++)
				{
					dealerarray[i] = response[i];
				}
				getdealerlist();
				$("#dealerselectionprocess").html('');
				$('#displayfilter').hide();
				$("#totalcount").html(dealerarray.length);
			}
		}, 
		error: function(a,b)
		{
			$('#dealerselectionprocess').html(scripterror());
		}
	});		
}



function getdealerlist()
{	
	//$('#tabgroupgridc3_2').hide();
	var form = $('#submitform');	
	var selectbox = $('#dealerlist');
	var numberofcustomers = dealerarray.length;
	//alert(dealerarray);
	$('#detailsearchtext').focus();
	var actuallimit = 500;
	var limitlist = (numberofcustomers > actuallimit)?actuallimit:numberofcustomers;
	
	$('option', selectbox).remove();
	var options = selectbox.attr('options');
	for( var i=0; i<limitlist; i++)
	{
		var splits = dealerarray[i].split("^");
		options[options.length] = new Option(splits[0], splits[1]);
	}
	
}

function selectfromlist()
{
	var selectbox = $("#dealerlist option:selected").val();
	$('#detailsearchtext').val($("#dealerlist option:selected").text());
	$('#detailsearchtext').select();
	enableproductbutton();
	dealerdetailstoform(selectbox);	
	dealerunassignedproduct(selectbox);	
	dealerassignedproduct(selectbox);	
}


function selectadealer(input)
{
	var selectbox = $('#dealerlist');
	var pattern = new RegExp("^" + input.toLowerCase());
	
	if(input == "")
	{
		getdealerlist();
	}
	else
	{
		//selectbox.options.length = 0;
		$('option', selectbox).remove();
		var options = selectbox.attr('options');
		
		var addedcount = 0;
		for( var i=0; i < dealerarray.length; i++)
		{
				if(input.charAt(0) == "%")
				{
					withoutspace = input.substring(1,input.length);
					pattern = new RegExp(withoutspace.toLowerCase());
					comparestringsplit = dealerarray[i].split("^");
					comparestring = comparestringsplit[1];
				}
				else
				{
					pattern = new RegExp("^" + input.toLowerCase());
					comparestring = dealerarray[i];
				}
				var result1 = pattern.test(trimdotspaces(dealerarray[i]).toLowerCase());
				var result2 = pattern.test(dealerarray[i].toLowerCase());
				if(result1 || result2)
				{
					var splits = dealerarray[i].split("^");
					options[options.length] = new Option(splits[0], splits[1]);
					addedcount++;
					if(addedcount == 100)
						break;
				}
		}
	}
}

function dealersearch(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 38)
		scrolldealer('up');
	else if(KeyID == 40)
		scrolldealer('down');
	else
	{
		var form = $('#submitform');
		var input = $('#detailsearchtext').val();
		selectadealer(input);
	}
}

function newentry()
{
	var form = $("#submitform");
	$("#submitform" )[0].reset();
	$("#lastslno").val('');
	$("#revenuesharenewsale").val('0');
	$("#revenueshareupsale").val('0');
	$("#taxname").val('GST');
	$("#taxamount").val('18');
	disabledelete();
	disablecopy();
	$('#displaypassworddfield').hide();
	$('#resetpwd').hide();
	$("#createddate").html('Not Available');
	$("#createdby").html('Not Available');
	$("#tabgroupgridwb2").html('');
	$("#tabgroupgridwb1").html('');
	gridtab4('1','tabgroupgrid','&nbsp;&nbsp; PIN Numbers Not Registered');
	$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory" id="district" style="width:200px;"><option value="">Select A State First</option></select>');
	$('#unassignedlist').html(' <select name="productlist" size="5" class="swiftselect" id="productlist" style="width:210px; height:200px;" ></select>');
	$('#assignedlist').html('<select name="selectedproducts" size="5" class="swiftselect" id="selectedproducts" style="width:210px; height:200px" ></select>');
	disableproductbutton();
	
}

function disablecopy()
{
	$("#copyadd").removeClass("swiftchoicebutton");
	$("#copyadd").addClass("swiftchoicebuttondisabled");

}

function cleargrid()
{
	$("#tabgroupgridc1_1").html('No datas found to be displayed.');
	$("#tabgroupgridc2_1").html('No datas found to be displayed.');
	$("#resultgridcardnotreg").html('');
	$("#resultgrid").html('');
	$("#tabgroupgridwb1").html('');
	$("#tabgroupgridwb2").html('');
	$("#getmorecardnotreglink").html('');
	$("#getmorecardlink").html('');
}

function generatedealercardregisted(id, command,startlimit)
{
	var form = $("#submitform");
	$("#lastslno").val(id);
	if(command == 'all')
		var passData = "type=dealercardregistered&lastslno="+ encodeURIComponent($("#lastslno").val()) + "&startlimit=" + startlimit;
	else
		var passData = "type=dealercardregistered&lastslno="+ encodeURIComponent($("#lastslno").val())+ "&startlimit=" + startlimit;
	var queryString = "../ajax/dealer.php";
	$("#tabgroupgridc2_1").html(getprocessingimage());
	$("#getmorecardlink").html('');
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					$("#tabgroupgridc2_1").html(response[1]);
					$("#getmorecardlink").html(response[3]);
					$("#tabgroupgridwb2").html("Total Count :  " + response[2]);
				}
				else
				{
					$("#tabgroupgridc2").html(errormessage('No data found to be displayed.'));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc2").html(scripterror());
		}
	});	
}


function getmoredealercardregisted(id, startlimit,slno,showtype)
{
	var form = $("#submitform");
	$("#lastslno").val(id);
	var passData = "type=dealercardregistered&lastslno="+ encodeURIComponent($("#lastslno").val()) + "&startlimit=" + startlimit+ "&slno=" + slno + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	var queryString = "../ajax/dealer.php";
	$("#getmorecardlink").html(getprocessingimage());
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
					$("#resultgrid").html($("#tabgroupgridc2_1").html());
					$("#tabgroupgridc2_1").html($("#resultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridwb2").html("Total Count :  " + response[2]);
					$("#getmorecardlink").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc2_1").html(errormessage("No datas found to be displayed."));
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc2_1").html(scripterror());
		}
	});	
}

function generatedealercardunregisted(id, command,startlimit)
{
	var form = $("#submitform");
	$("#lastslno").val(id);
	if(command == 'all')
		var passData = "type=dealercardunregistered&lastslno="+ encodeURIComponent($("#lastslno").val())+ "&startlimit=" + startlimit;
	else
		var passData = "type=dealercardunregistered&lastslno="+ encodeURIComponent($("#lastslno").val())+ "&startlimit=" + startlimit;
	var queryString = "../ajax/dealer.php";
	$("#tabgroupgridc1_1").html(getprocessingimage());
	$("#getmorecardnotreglink").html('');
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
				if(response[0] == '1')
				{
					gridtab4('1','tabgroupgrid','&nbsp; &nbsp;PIN Numbers Not Registered');
					$("#tabgroupgridc1_1").html(response[1]);
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					$("#getmorecardnotreglink").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

function getmoredealercardunregisted(id, startlimit,slno,showtype)
{
	var form = $("#submitform");
	$("#lastslno").val(id);
	var passData = "type=dealercardunregistered&lastslno="+ encodeURIComponent($("#lastslno").val()) + "&startlimit=" + startlimit+ "&slno=" + slno+ "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);//alert(passData);
	var queryString = "../ajax/dealer.php";
	$("#getmorecardnotreglink").html(getprocessingimage());
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
					gridtab4('1','tabgroupgrid','&nbsp; &nbsp;PIN Numbers Not Registered');
					$("#resultgridcardnotreg").html($("#tabgroupgridc1_1").html());
					$("#tabgroupgridc1_1").html($("#resultgridcardnotreg").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridwb1").html("Total Count :  " + response[2]);
					$("#getmorecardnotreglink").html(response[3]);
				}
				else
				{
					$("#tabgroupgridc1_1").html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


function showalldealercardunregisted(id,totalcount)
{
	var form = $("#submitform");
	$("#lastslno").val(id);
	var passData = "type=showalldealercardunregisted&lastslno="+ encodeURIComponent($("#lastslno").val()) + "&totalcount=" + encodeURIComponent(totalcount);
	var queryString = "../ajax/dealer.php";
	$("#getmorecardnotreglink").html(getprocessingimage());
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
				gridtab4('1','tabgroupgrid','&nbsp; &nbsp;PIN Numbers Not Registered');
				$("#resultgridcardnotreg").html($("#tabgroupgridc1_1").html());
				$("#tabgroupgridc1_1").html($("#resultgridcardnotreg").html().replace(/\<\/table\>/gi,'')+ response[0]);
				$("#getmorecardnotreglink").html(response[1]);
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


function dealerdetailstoform(deaid)
{
	if(deaid != '')
	{
		$("#form-error").html('');
		var form = $("#submitform");
		var passData = "type=dealerdetailstoform&lastslno=" + encodeURIComponent(deaid) + "&dummy=" + Math.floor(Math.random()*100032680100);
		$("#form-error").html(getprocessingimage());
		var queryString = "../ajax/dealer.php";
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
					var response = ajaxresponse;
					if(response['businessname'] == '')
						{
							
							alert('Dealer Not Available.');
							$("#districtcodedisplay").html('<select name="district" class="swiftselect-mandatory" id="district"><option value="">Select A State First</option></select>');
							$("#tabgroupgridc1").html('No datas found to be displayed.')
							gridtab4('1','tabgroupgrid','&nbsp; &nbsp;Current Registrations');
							$("#tabgroupgridc1").html('No datas found to be displayed.')						
							newentry();
						}
						else
						{
							//generatedealercardregisted(response['slno'], 'notall','');
							generatedealercardunregisted(response['slno'], 'notall','');
							$("#lastslno").val(response['slno']);
							$("#dealerid").val(response['slno']);
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
							$("#branch").val(response['branch']);
							$("#billingname").val(response['billingname']);
							$("#remarks").val(response['remarks']);
							autochecknew($("#saifreepin"),response['saifreepin']);
							autochecknew($("#relyonexecutive"),response['relyonexecutive']);
							//alert(response[14]);
							/*if(response['relyonexecutive'] == 'yes')
							{
								//block attach card permission
								//document.getElementById('tabgroupgridc3_2').style.display = 'block';
								$('#tabgroupgridc3_2').hide();
								//document.getElementById('tabgroupgridc3_1').style.display = 'none';
								$('#tabgroupgridc3_1').show();
							}
							else
							{
								$('#tabgroupgridc3_2').hide();
								//alert(document.getElementById('tabgroupgridc3').style.display);
								$('#tabgroupgridc3_1').show();
							}*/
							autochecknew($("#disablelogin"),response['disablelogin']);
							if(response['passwordchanged'] == 'n')
							{
								$('#initialpassworddfield').show();
								$("#initialpassword").val(response['initialpassword']);
								$('#displayresetpwd').hide();
							}
							else if(response['passwordchanged'] == 'y')
							{
								if(response['p_editdealerpassword'] == 'yes')
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
							$("#revenuesharenewsale").val(response['revenuesharenewsale']);
							$("#taxamount").val(response['taxamount']);
							$("#taxname").val(response['taxname']);
							$("#tlemailid").val(response['tlemailid']);
							$("#mgremailid").val(response['mgremailid']);
							$("#hoemailid").val(response['hoemailid']);
							$("#dealerusername").val(response['dealerusername']);
							$("#revenueshareupsale").val(response['revenueshareupsale']);
							$("#currentcredit").html(response['currentcreditavail']);
							$("#personalemailid").val(response['personalemailid']);
							$("#panno").val(response['panno']);

							autochecknew($("#dealernotinuse"),response['dealernotinuse']);
							autochecknew($("#telecaller"),response['telecaller']);
							autochecknew($("#enablebilling"),response['enablebilling']);
							autochecknew($("#enablematrixbilling"),response['enablematrixbilling']);
							autochecknew($("#branchhead"),response['branchhead']);
							autochecknew($("#editcustdata"),response['editcustdata']);
							autochecknew($("#forcesurrender"),response['forcesurrender']);
							
							//Addded on 19th Jan 2018
							//autochecknew($("#maindealers"),response['maindealers']);
							//$("#dealerhead").val(response['dealerhead']);

							//Added on 27th Aug 2019
							$("#dealertype").val(response['dealertype']);
							pspdealerlist(response['dealertype'],deaid);
							$('#pspdea').html(response['businessname']);
							var error = $('#dealer-form-error');
							error.html('');
							changedealertype(response['dealertype'],response['dealertypehead']);


							$("#createddate").html(response['createddate']);
							$("#createdby").html(response['fullname']);
							$("#gst_no").val(response['gst_no']);
					        $("#state_gst").text(response['state_gst']);

							$("#copyadd").removeClass("swiftchoicebuttondisabled");
							$("#copyadd").addClass("swiftchoicebutton");

							if(response['userid'] == '1'|| response['userid'] == '146')
							{
								enabledelete();
							}
							else
							{
								disabledelete();
							}
							if(response['p_editdealerpassword'] == 'yes') 
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
				$("#form-error").html(scripterror());
			}
		});	
	}
}

function validatepwd()
{ 
	var form = $("#submitform"); 
	var dealerid = $("#lastslno").val();
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
			var passData  = "type=resetpwd&password=" + encodeURIComponent($("#password").val()) + "&dealerid=" + dealerid + "&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
			var queryString = "../ajax/dealer.php"; 
			ajaxcall8 = $.ajax(
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
						$('#form-error').html('');
						if(response['passwordchanged'] == 'N')
						{
							$("#initialpassworddfield").show();
							$("#displayresetpwd").hide();
							$('#initialpassword').val(response['initialpassword']);
							$("#displaypassworddfield").show();
							$("#resetpwd").hide();
						}
						else
						{
							$('#initialpassword').val(response['initialpassword']);
							$("#displaypassworddfield").show();
							$("#resetpwd").hide();
							
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


function scrolldealer(type)
{	
	var selectbox = $('#dealerlist');
	var totalcus = $("#dealerlist option").length;
	var selectedcus = $("select#dealerlist").attr('selectedIndex');
	if(type == 'up' && selectedcus != 0)
		$("select#dealerlist").attr('selectedIndex', selectedcus - 1);
	else if(type == 'down' && selectedcus != totalcus)
		$("select#dealerlist").attr('selectedIndex', selectedcus + 1);
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
	passData = "type=dealersearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&region=" + encodeURIComponent(region) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	error.html(getprocessingimage());
	var queryString = "../ajax/dealer.php";
	ajaxcall9 = $.ajax(
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					error.html('');
					gridtab4('4','tabgroupgrid','&nbsp; &nbsp;Search Result');
					$("#tabgroupgridc4_1").html(response[1]);
					$("#tabgroupgridwb4").html("Total Count: " +  response[2]);
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
	passData = "type=dealersearch&textfield=" + encodeURIComponent(textfield) + "&subselection=" + encodeURIComponent(subselection) + "&orderby=" + encodeURIComponent(orderby) + "&region=" + encodeURIComponent(region) + "&startlimit=" + encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype) + "&dummy=" + Math.floor(Math.random()*1000782200000);
	 $("#tabgroupgridc1linksearch").html(getprocessingimage());
	var queryString = "../ajax/dealer.php";
	ajaxcall10 = $.ajax(
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
					gridtab4('4','tabgroupgrid','&nbsp; &nbsp;Search Result');
					$("#searchresultgrid").html($("#tabgroupgridc4_1").html());
					$("#tabgroupgridc4_1").html($("#searchresultgrid").html().replace(/\<\/table\>/gi,'')+ response[1]);
					$("#tabgroupgridwb4").html("Total Count: " + response[2]);
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


function searchbydealeridevent(e)
{ 
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	if(KeyID == 13)
	{
		var input = $('#searchbydealerid').val()
		dealerdetailstoform(input);
		dealerunassignedproduct(input);	
		dealerassignedproduct(input);	
	}
}

function attachnewcard(command)
{
	var form = $('#attachnewcardform');
	var error = $('#form-error-attachnew');
	var dealerid = $('#lastslno').val();
	
	var field = $('#lastslno');
	if(!field.val()) { error.html(errormessage('Select the Dealer from the list above.')); $('#searchdealer').focus(); return false; }
	var field = $('#ttproduct');
	if(!field.val()) { error.html(errormessage('Select the product from the list.')); field.focus(); return false; }
	var field = $('#forfree:checked').val(); 
	if(field == 'on') var forfree = 'yes'; else var forfree = 'no';
	var field = $('#scratchcardfrom');
	if(!field.val()) { error.html(errormessage('Select the Scratch Card Number from the list')); $('#scratchcardfromtext').focus(); return false; }
	
	else
	{
		var passData = '';
		if(command == 'attach')
		//alert(command);
		passData = "type=attachscratchcard&dealerid=" + encodeURIComponent(dealerid) + "&ttproduct=" + encodeURIComponent($('#ttproduct').val()) + "&date=" + encodeURIComponent($('#date').val())  + "&scratchcardfrom=" + encodeURIComponent($('#scratchcardfrom').val())  + "&formultiuser=" + encodeURIComponent($('#usagetype').val())  + "&forfree=" + encodeURIComponent(forfree)  + "&forupdate=" + encodeURIComponent($('#purchasetype').val()) + "&remarks=" + encodeURIComponent($('#remarks').val())  + "&dummy=" + Math.floor(Math.random()*1000782200000);
		
		
		error.html(getprocessingimage());
		var confirmation = confirm("Are you sure that you want to attach a card for the selected dealer.");
		if(confirmation)
		{
			var queryString = "../ajax/dealer.php";
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
						error.html('');
						var response = ajaxresponse.split('^');
						if(response[0] == '1')
						{
							error.html(successmessage(response[1]));
							generatedealercardunregisted(dealerid, 'notall','');
							$('$attachnewcardform')[0].reset();
						}
						else
						{
							error.html(errormessage('Card cannot be attached.'));
						}
					}
				}, 
				error: function(a,b)
				{
					error.html(scripterror());
				}
			});	
		}
		else
		{
			error.html('');
		}
	}
}

function selectbills()
{
	var dealerid = $('#lastslno').val();
	var field = $('$lastslno');
	if(!field.val()) { error.html(errormessage('Select the Dealer from the list above.')); $('#searchdealer').focus(); return false; }
	else
	{
		var passData = '';
		passData = "type=selectbillnumber&dealerid=" + encodeURIComponent(dealerid) + "&dummy=" + Math.floor(Math.random()*1000782200000);
		var queryString = "../ajax/dealer.php";
		ajaxcall12 = $.ajax(
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
					var response = ajaxresponse;
					$('#billnumberdisplay').html(ajaxresponse);
				}
			}, 
			error: function(a,b)
			{
				error.html(scripterror());
			}
		});	
	}
}
	
function attachcardtab()
{
	
	if($('tabgroupgridc3_1').is(':visible')) 
	{ 
		gridtab4('3_1','tabgroupgrid','&nbsp;&nbsp; Provision enable only if dealer type is a Relyon Executive2'); 
	} 
	else
	{
		gridtab4('3','tabgroupgrid','&nbsp;&nbsp; Provision enable only if dealer type is a Relyon Executive1');
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
	$("#password").focus();
	$("#displaypassworddfield").hide();
	return false;
}
function pspdealerlist(dealertype,dealerid)
{
	//alert("hi");
	//var dealertype = $("#hiddealertype").val();
	//alert(dealerid);
	if(dealerid!= '')
	{
		var passData = "type=pspdealerlist&dealerid=" + encodeURIComponent(dealerid) + "&dealertype=" + encodeURIComponent(dealertype);
		var queryString = "../ajax/dealer.php";
		ajaxcall = $.ajax({
			type: "POST", url: queryString, data: passData, dataType: "json",
			success: function (response,status) {
				//alert(response['mspdealeroptions']);
				$('#pspdealers').html('');
				$('#pspdealers').html(response['pspdealeroptions']);

				$('#mspdealers').html('');
				$('#mspdealers').html(response['mspdealeroptions']);
			}
		});
	}
}

function dealerunassignedproduct(deaid)
{
	if(deaid != '')
	{
		$("#product-form-error").html('');
		var form = $("#submitform");
		var passData = "type=dealerunassignedproduct&lastslno=" + encodeURIComponent(deaid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$("#product-form-error").html(getprocessingimage());
		var queryString = "../ajax/dealer.php";
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
					$("#product-form-error").html('');
					var response = ajaxresponse;
					$("#unassignedlist").html('');
					$("#unassignedlist").html(response['productlistoptions']);
				}
			}, 
			error: function(a,b)
			{
				$("#product-form-error").html(scripterror());
			}
		});	
	}
}

function dealerassignedproduct(deaid)
{
	if(deaid != '')
	{
		$("#product-form-error").html('');
		var form = $("#submitform");
		var passData = "type=dealerassignedproduct&lastslno=" + encodeURIComponent(deaid) + "&dummy=" + Math.floor(Math.random()*100032680100);//alert(passData)
		$("#product-form-error").html(getprocessingimage());
		var queryString = "../ajax/dealer.php";
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
					$("#product-form-error").html('');
					var response = ajaxresponse;
					$("#assignedlist").html('');
					$("#assignedlist").html(response['productlistoptions']);
				}
			}, 
			error: function(a,b)
			{
				$("#product-form-error").html(scripterror());
			}
		});	
	}
}


//function to add values of the selected option to select box 
function addproductentry(productcode,arraytypefrom,arraytypeto,type)
{
	//Get the Select Box as an object
	var selectbox = document.getElementById(arraytypefrom);
	var secondselectbox = document.getElementById(arraytypeto);
	
	
	//Check if any product is select. Else, prompt to select a product.
	if(selectbox.selectedIndex < 0)
	{
		if(type == 'addtype')
		{
			alert("Select a Product to Add from UnAssigned Products.");
			return false;
		}
		else if(type == 'removetype')
		{
			alert("Select a Product to Remove from Assigned Products.");
			return false;
		}
	}
	
	var addproductvalue = selectbox.options[selectbox.selectedIndex].value;
	var addproducttext = selectbox.options[selectbox.selectedIndex].text;
	
	//Add the value to second select box
	var newindexforadding = secondselectbox.length;
	secondselectbox.options[newindexforadding] = new Option(addproducttext,addproductvalue);
	if(arraytypefrom == 'productlist')
	{
		secondselectbox.options[newindexforadding].setAttribute('ondblclick', 'addproductentry("' + addproductvalue + '", "selectedproducts", "productlist","addtype");');
	}
	else if(arraytypefrom == 'selectedproducts')
	{
		secondselectbox.options[newindexforadding].setAttribute('ondblclick', 'addproductentry("' + addproductvalue + '", "productlist", "selectedproducts","addtype");');
	}
	
	
	deleteentry(productcode,arraytypefrom,arraytypeto);
	//sortarray(arraytypefrom,arraytypeto);
	
}	

//function to remove values of the selected option from select box -Meghana[23/11/2009]
function deleteentry(productcode,arraytypefrom,arraytypeto)
{
	var selectbox = document.getElementById(arraytypefrom);
	var secondselectbox = document.getElementById(arraytypeto);
	

	//Take the value and Text of selected product from selected index.
	var delproductvalue = selectbox.options[selectbox.selectedIndex].value;
	var delproducttext = selectbox.options[selectbox.selectedIndex].text;
	
	
	//Run a loop for whole select box [2] and remove the entry where value is deletable
	for(i = 0; i < selectbox.length; i++)
	{
		loopvalue = selectbox.options[i].value;
		
		if(loopvalue == delproductvalue)
		{
			selectbox.options[i] = null;
		}
	}
	sortarray(arraytypefrom,arraytypeto);
}


//function to remove all values of the selected option from select box -Meghana[25/11/2009]
function deleteallentry(productcode)
{
		//Get the select boxes as objects
		var productarray = new Array();
		var allproductarray = new Array();
		var selectbox = document.getElementById('productlist');
		var secondselectbox = document.getElementById('selectedproducts');
		var secoundvalues = document.getElementById('productlist');
		for(var i=0; i < secoundvalues.length; i++ )
		{
			productarray[i] = secoundvalues[i].value;
		}
	
		var ckvalues = document.getElementById('selectedproducts');
		for(var i=0; i < ckvalues.length; i++ )
		{
			allproductarray[i] = ckvalues[i].value;
		}
	
		//Run a loop for whole select box [2] and remove the entry where value is deletable
		for(i = 0; i < allproductarray.length; i++)
		{
				secondselectbox.options[secondselectbox.length -1] = null;
		}
		//Run a loop for whole select box [1] and Enable the entry where value is deleted
		for(j = 0; j < selectbox.length; j++)
		{
				selectbox.options[j].disabled = false;
		}

}

function sortarray(arraytypefrom,arraytypeto)
{
	var list1array = Array();
	var list2array = Array();

	var list1values = document.getElementById(arraytypefrom);
	for(var i=0; i < list1values.length; i++ )
	{
		
		list1array[i] = list1values[i].text+'^'+list1values[i].value;
	}
	list1array.sort();
	
	for( var i=0; i< list1array.length; i++)
	{
		var splits = list1array[i].split("^");
		list1values.options[i] = new Option(splits[0], splits[1]);
		list1values.options[i].setAttribute('ondblclick', 'addproductentry("' + splits[1] + '", "'+ arraytypefrom +'", "'+ arraytypeto +'","addtype");');
	}
	
	var list2values = document.getElementById(arraytypeto);
	for(var i=0; i < list2values.length; i++ )
	{
		
		list2array[i] = list2values[i].text+'^'+list2values[i].value;
	}
	list2array.sort();
	for( var i=0; i< list2array.length; i++)
	{
		var splits1 = list2array[i].split("^");
		list2values.options[i] = new Option(splits1[0], splits1[1]);
		list2values.options[i].setAttribute('ondblclick', 'addproductentry("' + splits[1] + '", "'+ arraytypeto +'", "'+ arraytypefrom +'","addtype");');
	}
	

}

function deaformsubmit_update()
{
	//alert("hi");
	//var dealerlist = $('#pspdealerslist option[value=' + objValue + ']').attr('selected', true);
	//alert(dealerlist);

	var psparray = new Array();
	var error = $('#dealer-form-error');

	//Check if any product is select. Else, prompt to select a product.

	var pspdealerlength = $('[name="pspdealerslist[]"]:checked').length;
	//alert(pspdealerlength);

	if(pspdealerlength < 1 && pspdealerlength ==0) {
		//alert('Select a PSP dealer');
		error.html(errormessage('Select a PSP dealer'));
	}

	var pspdealers = $("input[name='pspdealerslist[]']:checked").map(function() {
		return this.value;
	}).get().join(', ');

	//  alert(pspdealers);

	if($("#mspdealerslist").val() == "") {
		//alert('Select a MSP dealer');
		error.html(errormessage('Select a MSP dealer'));
	}

	//var chkmspid = $('input[name=mspdealerslist]:checked').val();
	var chkmspid = $("#mspdealerslist").val();
	//alert(chkmspid);
	//alert($("#dealerlist option:selected").val());



  	var passData;
	passData = "type=updatepspdealers&lastno=" + $("#dealerlist option:selected").val() + "&mspdealerid=" + chkmspid + "&pspdealerlistid=" + pspdealers;
	var queryString = "../ajax/dealer.php";
	//alert(passData);

  	$.ajax({
		type:"POST", data: passData, url: queryString , dataType: "json",
		success: function (ajaxresponse) {
			//alert('Updated');
			error.html(successmessage('Updated'));
			pspdealerlist($("#dealertype").val(),$("#dealerlist option:selected").val());
		}
  });
}

function formsubmit_update()
{
	var form =$('#productsubmitform');
	var error = $('#product-form-error');
	var productarray = new Array();
	
	//Check if any product is select. Else, prompt to select a product.
	var ckvalues = document.getElementById('selectedproducts');
	for(var i=0; i < ckvalues.length; i++ )
	{
		productarray[i] = ckvalues[i].value;
	}
	if (productarray.length <1) 
	 { error.html(errormessage("Select a Product.")); ckvalues.focus(); return false; }
	
		var passData = "";
		passData =  "type=updateproducts&lastslno=" + $("#dealerlist option:selected").val() + "&productarray=" + productarray +"&dummy=" + Math.floor(Math.random()*100000000);//alert(passData)
		
		$('#product-form-error').html(getprocessingimage());
		queryString = "../ajax/dealer.php";
		ajaxcalls55 = $.ajax(
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
					if(response['errorcode'] == '1')
					{
						error.html(successmessage(response['errormsg']));//alert(error.innerHTML)
					}
				}
			}, 
			error: function(a,b)
			{
				$("#product-form-error").html(scripterror());
			}
		});	
}

function resetfunc()
{
	var selectbox = $("#dealerlist option:selected").val();
	dealerunassignedproduct(selectbox);	
	dealerassignedproduct(selectbox);	
	var form =$('#productsubmitform');
	$("#productsubmitform" )[0].reset();
	var error = $('#product-form-error');
	error.html('');
}
function dearesetfunc() {
	var form = $(dealersubmitform);
	$('#dealersubmitform')[0].reset();
	var error = $('#dealer-form-error');
	error.html('');
}

function disableproductbutton()
{
	$('#add').attr('disabled',true);
	$('#removeall').attr('disabled',true);
	$('#remove').attr('disabled',true);
	$('#updateproduct').attr('disabled',true);
	$('#clear').attr('disabled',true);
	
	$('#add').removeClass('swiftchoicebutton');
	$('#removeall').removeClass('swiftchoicebutton');
	$('#remove').removeClass('swiftchoicebutton');
	$('#updateproduct').removeClass('swiftchoicebutton');
	$('#clear').removeClass('swiftchoicebutton');
	
	$('#add').addClass('swiftchoicebuttondisabled');
	$('#removeall').addClass('swiftchoicebuttondisabled');
	$('#remove').addClass('swiftchoicebuttondisabled');
	$('#updateproduct').addClass('swiftchoicebuttondisabled');
	$('#clear').addClass('swiftchoicebuttondisabled');
}

function enableproductbutton()
{
	$('#add').attr('disabled',false);
	$('#removeall').attr('disabled',false);
	$('#remove').attr('disabled',false);
	$('#updateproduct').attr('disabled',false);
	$('#clear').attr('disabled',false);

	$('#add').removeClass('swiftchoicebuttondisabled');
	$('#removeall').removeClass('swiftchoicebuttondisabled');
	$('#remove').removeClass('swiftchoicebuttondisabled');
	$('#updateproduct').removeClass('swiftchoicebuttondisabled');
	$('#clear').removeClass('swiftchoicebuttondisabled');
	
	$('#add').addClass('swiftchoicebutton');
	$('#removeall').addClass('swiftchoicebutton');
	$('#remove').addClass('swiftchoicebutton');
	$('#updateproduct').addClass('swiftchoicebutton');
	$('#clear').addClass('swiftchoicebutton');
}
function getgstcode(statecode){
    //alert(statecode);
    var passData = "type=dealergstcode&stateid="+ encodeURIComponent(statecode);
	var queryString = "../ajax/dealer.php";
    ajaxcall17 = $.ajax(
	{
		type: "POST",url: queryString, data: passData, cache: false, dataType: "json",
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
				//alert(response['state_gst_code'] );
				if(response['state_gst_code'] != '')
				{
					//$('#form-error-alert').html('');
					var gstcheck = $('#state_gst').html(response['state_gst_code']);
					console.log(gstcheck);
					//alert($("#state_gst").html());
				}
				else
				{
					alert('No GST Code For State' );
				}
			}
		}, 
		error: function(a,b)
		{
			$("#form-error-alert").html(scripterror());
		}
	});	
}