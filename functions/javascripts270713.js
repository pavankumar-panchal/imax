//Function to create Ajax Object-----------------------------------------------------------------------------------
function createajax()
{
   var objectname = false;	
	try { /*Internet Explorer Browsers*/ objectname = new ActiveXObject('Msxml2.XMLHTTP'); } 
	catch (e)
	{
		try { objectname = new ActiveXObject('Microsoft.XMLHTTP'); } 
		catch (e)  
		{
			try { /*// Opera 8.0+, Firefox, Safari*/ 
				objectname = new XMLHttpRequest();	
				} 
			catch (e) { /*Something went wrong*/ alert('Your browser is not responding for Javascripts.'); return false; }
		}
	}
	return objectname;
}
//Function to display the error message in box---------------------------------------------------------------------
function errormessage(message)
{
	var msg = '<div class="errorbox">' + message + '</div>';
	return msg;
}

//Function to display the success message in box-------------------------------------------------------------------
function successmessage(message)
{
	var msg = '<div class="successbox">' + message + '</div>';
	return msg;
}

function successsearchmessage(message)
{
	var msg = '<div class="successsearchbox">' + message + '</div>';
	return msg;
}





//Function to make the display as block as well as none-------------------------------------------------------------
function displayelement(displayelementid,hideelementid)
{
	var delement = document.getElementById(displayelementid);
	var helement = document.getElementById(hideelementid);
	delement.style.display = 'block'; helement.style.display = 'none'; 
}

//Function to enable the delete button------------------------------------------------------------------------------
function enabledelete()
{
	$('#delete').attr('disabled',false);
	$('#delete').removeClass('swiftchoicebuttondisabled');
	$('#delete').addClass('swiftchoicebutton');
}

//Function to enable the save button--------------------------------------------------------------------------------
function enablesave()
{
	$('#save').attr('disabled',false);
	$('#save').removeClass('swiftchoicebuttondisabled');
	$('#save').addClass('swiftchoicebutton');
}

//Function to disable the save button--------------------------------------------------------------------------------
function disablesave()
{
	$('#save').attr('disabled',true);
	$('#save').removeClass('swiftchoicebutton');
	$('#save').addClass('swiftchoicebuttondisabled');
}

//Function to enable the New button--------------------------------------------------------------------------------
function enablenew()
{
	$('#new').attr('disabled',false);
	$('#new').removeClass('swiftchoicebuttondisabled');
	$('#new').addClass('swiftchoicebutton');
	
}

//Function to disable the New button--------------------------------------------------------------------------------
function disablenew()
{
	$('#new').attr('disabled',true);
	$('new').removeClass('swiftchoicebutton');
	$('#new').addClass('swiftchoicebuttondisabled');
	//document.getElementById('new').style.cursor = '';
}

//Function to disable the delete button-----------------------------------------------------------------------------
function disabledelete()
{
	$('#delete').attr('disabled',true);
	$('#delete').removeClass('swiftchoicebutton');
	$('#delete').addClass('swiftchoicebuttondisabled');
	//document.getElementById('delete').style.cursor = '';
}

//function to enable leftarrow-------------------------------------------------------------------------------------
function enableleftarrow()
{
	$('#leftarrow').attr('disabled',false);
	$('#leftarrow').removeClass('leftarrowdisable');
	$('#leftarrow').addClass('leftarrowenable');
}

//function to disable leftarrow------------------------------------------------------------------------------------
function disableleftarrow()
{
	$('#leftarrow').attr('disabled',true);
	$('#leftarrow').removeClass('leftarrowenable');
	$('#leftarrow').addClass('leftarrowdisable');
}

//function to enable rightarrow-----------------------------------------------------------------------------------
function enablerightarrow()
{
	$('#rightarrow').attr('disabled',false);
	$('#rightarrow').removeClass('rightarrowdisable');
	$('#rightarrow').addClass('rightarrowenable');
}

//function to disable rightarrow-----------------------------------------------------------------------------------
function disablerightarrow()
{
	$('#rightarrow').attr('disabled',true);
	$('#rightarrow').removeClass('rightarrowenable');
	$('#rightarrow').addClass('rightarrowdisable');
}
//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtab3(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 3;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

function gridtabcus4(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 4;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+ tabhead).removeClass(tabheadclass);
			$('#'+ tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+ tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			//if(document.getElementById(tabwaitbox)) { document.getElementById(tabwaitbox).style.display = 'none'; }
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

function gridtabcus5(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 8;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=5; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+ tabhead).removeClass(tabheadclass);
			$('#'+ tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			$('#tabdescription1').html(tabdescription);
		}
		else
		{
			$('#'+ tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			//if(document.getElementById(tabwaitbox)) { document.getElementById(tabwaitbox).style.display = 'none'; }
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

function gridtab3_1(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 3;
	var activetabheadclass = 'grid-active-tabclasst6';
	var tabheadclass = 'grid-tabclasst6';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
			//document.getElementById('tabdescription').innerHTML = '';
		}
	}
}

//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtab6(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 6;
	var activetabheadclass = 'grid-active-tabclasst6';
	var tabheadclass = 'grid-tabclasst6';
	
	for(var i=1; i <= totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).show();
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
		}
	}
}

//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtab4(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 4;
	var activetabheadclass = 'grid-active-tabclassr6';
	var tabheadclass = 'grid-tabclassr6';
	
	for(var i=1; i <= totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		var tabviewbox = tabgroupname + 'view' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).hide(); }
		}
	}
}

//Function to change the css of active tab and select the tab in display grid part----------------------------------
function gridtab2(activetab,tabgroupname,tabdescription)
{
	var totaltabs = 2;
	var activetabheadclass = 'grid-active-tabclass';
	var tabheadclass = 'grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		var tabviewbox = tabgroupname + 'view' + i;

		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			if($('#'+tabcontent)) { $('#'+tabcontent).show(); }
			$('#'+tabcontent).show();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).show(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).show(); }
			$('#tabdescription').html(tabdescription);
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
			if($('#'+tabwaitbox)) { $('#'+tabwaitbox).hide(); }
			if($('#'+tabviewbox)) { $('#'+tabviewbox).hide(); }
		}
	}
}

function tabopen5(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "producttabheadactive";
	var tabheadclass = "producttabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			document.getElementById(tabhead).className = activetabheadclass;
			document.getElementById(tabcontent).style.display = 'block';
		}
		else
		{
			document.getElementById(tabhead).className = tabheadclass;
			document.getElementById(tabcontent).style.display = 'none';
		}
	}
}

function tabopenimp2(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "imptabheadactive";
	var tabheadclass = "imptabhead";
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			$('#'+tabhead).removeClass(tabheadclass);
			$('#'+tabhead).addClass(activetabheadclass);
			$('#'+tabcontent).show();
		}
		else
		{
			$('#'+tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			$('#'+ tabcontent).hide();
		}
	}
}

function tabopen2(activetab,tabgroupname)
{
	var totaltabs = 2;
	var activetabheadclass = "producttabheadactive";
	var tabheadclass = "producttabhead";
	for(var i=0; i<totaltabs; i++)
	{
		//alert(activetab+ 'AT')
		var tabhead = tabgroupname + 'h' + i;
		var tabcontent = tabgroupname + 'c' + i;
		if(i == activetab)
		{
			document.getElementById(tabhead).className = activetabheadclass;
			document.getElementById(tabcontent).style.display = 'block';
		}
		else
		{
			document.getElementById(tabhead).className = tabheadclass;
			document.getElementById(tabcontent).style.display = 'none';
		}
		
	}
}

//Function to generate random password------------------------------------------------------------------------------
function randomPassword()
{
	chars0 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"; chars1 = "1234567890";
	pass = ""; pass1 = ""; pass2 = "";
	for(x=0;x<4;x++) { i = Math.floor(Math.random() * 62); pass1 += chars0.charAt(i); }
	for(y=0;y<4;y++) { j = Math.floor(Math.random() * 62); pass2 += chars1.charAt(j); }
	pass = pass1 + pass2;
	return pass;
}

//Function to get the district code according to the state selected---------------------------------------------
function districtcodeFunction(selectid, comparevalue)
{
	var statecode = document.getElementById('state').value;
	var districtDisplay = document.getElementById('districtcodedisplay');
	passData = "statecode=" + encodeURIComponent(statecode)  + "&dummy=" + Math.floor(Math.random()*1100011000000);
	ajaxcalld = createajax();
	var queryString = "../ajax/selectdistrictonstate.php";
	ajaxcalld.open("POST", queryString, true);
	ajaxcalld.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcalld.onreadystatechange = function()
	{
		if(ajaxcalld.readyState == 4)
		{
			districtDisplay.innerHTML = ajaxcalld.responseText;
			if(selectid && comparevalue)
			autoselect(selectid, comparevalue);
		}
	}
	ajaxcalld.send(passData);return true;
}

//Function to get the district code according to the state selected---------------------------------------------
function billnumberFunction(selectid, comparevalue)
{
	var dealerid = document.getElementById('dealerlist').value;
	var billDisplay = document.getElementById('billnumberdisplay');
	passData = "dealerid=" + encodeURIComponent(dealerid)  + "&dummy=" + Math.floor(Math.random()*1100011000000);
	ajaxcalld = createajax();
	var queryString = "../ajax/selectbillnumber.php";
	ajaxcalld.open("POST", queryString, true);
	ajaxcalld.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcalld.onreadystatechange = function()
	{
		if(ajaxcalld.readyState == 4)
		{
			billDisplay.innerHTML = ajaxcalld.responseText;
			if(selectid && comparevalue)
			autoselect(selectid, comparevalue);
		}
	}
	ajaxcalld.send(passData);
	return true;
}

//Function to check the particular option in <input type =check> Tag, with the compare value------------------------
function autochecknew(selectid,comparevalue)
{
		var selection = selectid;
		if('yes' == comparevalue)
		{
			$(selection).attr('checked',true)
			return;
		}
		else
		{
			$(selection).attr('checked',false)
			return;
		}
}

/*//Function to select the particular option in <SELECT> Tag, with the compare value----------------------------------
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
*/

//Function to call the function on load of the page if function exists---------------------------------------------
function bodyonload(userid)
{	
	if(typeof getproductlist == 'function') { getproductlist(); }
	if(typeof getcustomerlist == 'function') { getcustomerlist(); }
	if(typeof getcardlist == 'function') { getcardlist(); }
	if(typeof getcustomerlist1 == 'function') { getcustomerlist1(); } 
	if(typeof getcustomerlist2 == 'function') { getcustomerlist2(); } 
	//if(typeof gridtab3 == 'function') { gridtab3('1','tabgroupgrid','&nbsp; &nbsp;Current Registration'); }
	if(typeof disableregistration == 'function') { disableregistration(); }
	if(typeof getdealerlist == 'function') { getdealerlist(); }
	if(typeof getuserlist == 'function') { getuserlist(); }
	//if(typeof gridtab4 == 'function') { gridtab4('1','tabgroupgrid','&nbsp; &nbsp;Cards Not Registered'); }
	//if(typeof gridtab2 == 'function') { gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Product in Use'); }
	if(typeof disableregistration == 'function') { disableregistration(); }
	if(typeof implementationresetfunc == 'function') { implementationresetfunc(); }
	
	if(typeof datagrid == 'function') { datagrid(); }
	if(typeof productdatagrid == 'function') { productdatagrid(''); }
	if(typeof viewpurchasedatagrid == 'function') { viewpurchasedatagrid(''); }
	if(typeof customerprofiledatagrid == 'function') { customerprofiledatagrid(''); }
	if(typeof generateschemegrid == 'function') { generateschemegrid(''); }
	if(typeof dealerprofiledatagrid == 'function') { dealerprofiledatagrid(''); }
	if(typeof getsmscreditssummary == 'function') { getsmscreditssummary(''); }
	if(typeof getsmscreditssummarypromo == 'function') { getsmscreditssummarypromo(''); }
	if(typeof getinvoicedetails == 'function') { getinvoicedetails(''); }
	if(typeof getrcidetails == 'function') { getrcidetails(''); }
	if(typeof clearscreen == 'function') { clearscreen(); }
	if(typeof newentry == 'function') { newentry(); }
	if(typeof dasboard_short_keys == 'function') { dasboard_short_keys(); }
	if(typeof customer_short_keys == 'function') { customer_short_keys(); }
	if(typeof disableformelemnts_invoicing == 'function') { disableformelemnts_invoicing(); }
	if(typeof getimpalldatadetails == 'function') { getimpalldatadetails('all')};
	if(typeof getimpbranchduedatadetails == 'function') { getimpbranchduedatadetails('all')};
	if(typeof displaybranch == 'function') { displaybranch('1')};
	if(typeof displaybranchsaleswise == 'function') { displaybranchsaleswise('1')};
	//if(document.getElementById('searchtext')){ document.getElementById('searchtext').focus();}
	if($('#hiddenregistrationtype')) $('#hiddenregistrationtype').val($('input[name=registrationfieldradio]:checked').val());
	if($('#tabc2')){$('#tabc2').hide();}
	if(typeof getalldummydetails == 'function') { getalldummydetails()};
	//if(typeof disablethedates == 'function') { disablethedates()};
	// if(getcustomerlist()) getcustomerlist();
	//if(gridtab3('1','tabgroupgrid','&nbsp; &nbsp;Current Registration')) gridtab3('1','tabgroupgrid','&nbsp; &nbsp;Current Registration');
	// if(getdealerlist()) getdealerlist();
	 //if(gridtab4('1','tabgroupgrid','&nbsp; &nbsp;Cards Not Registered')) gridtab4('1','tabgroupgrid','&nbsp; &nbsp;Cards Not Registered');
	 if(typeof loadselection == 'function') { loadselection(userid); }
	 if(typeof receiptloadselection == 'function') { receiptloadselection(userid); }
	 if(typeof outreceiptloadselection == 'function') { outreceiptloadselection(userid); }
	 if(typeof gettodaysresult == 'function') { gettodaysresult(''); }
}

//Function to select all the check boxes in a group-----------------------------------------------------------------
function checkAll(checkallboxname, checkboxname)
{
	var field = document.getElementsByTagName('input');
	var selection = (document.getElementById(checkallboxname).checked == true)?true:false;
	for(i=0; i<field.length; i++)
	{
		if(field[i].type == 'checkbox' && field[i].name == checkboxname)
		field[i].checked = selection;
	}
}

//Function to the value of the check box which is in group----------------------------------------------------------
function get_check_value(checkboxname)
{
	var c_value = "";
	var checkbox = document.getElementsByName(checkboxname);
	for (var i=0; i < checkbox.length; i++)
	{
		if (checkbox[i].checked)
		{
			c_value = c_value + checkbox[i].value + "^^^";
		}
	}
return c_value;
}

function setradiovalue(radioObj, newValue) 
{
	if(!radioObj)
		return false;
	var radioLength = radioObj.length;
	if(radioLength == undefined) 
	{
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) 
	{
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) 
		{
			radioObj[i].checked = true;
		}
	}
}

/*function validateamount(amount)
{
	var numericExpression = /^[0-9]$\./i;
	if(amount.match(numericExpression)) return true;
	else return false;
}*/
function validateamount(amount)
{
	var numericExpression = /^[-+]?[0-9]\d{0,9}(\.\d{1,2})?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}


function validatestdcode(stdcodenumber)
{
	var numericExpression = /^[0]+[0-9]{2,4}$/i;
	if(stdcodenumber.match(numericExpression)) return true;
	else return false;
}

function validatepincode(pincodenumber)
{
	var numericExpression = /^(^\d{6})$/i;
	if(pincodenumber.match(numericExpression)) return true;
	else return false;
}

function validatecell(cellnumber)
{
	var numericExpression = /^[7|8|9]\d{9}(?:(?:([,][\s]|[;][\s]|[,;])[7|8|9]\d{9}))*$/i;
	//var numericExpression = /^((\+)?(\d{2}[-]))?(\d{10})?$/i ;
	if(cellnumber.match(numericExpression)) return true;
	else return false;
}

function cellvalidation(cellnumber)
{
 var numericExpression = /^[7|8|9]+[0-9]{9,9}$/i;
 if(cellnumber.match(numericExpression)) return true;
 else return false;
}

function contactpersonvalidate(contactname)
{
 var numericExpression = /^([A-Z\s\()]+[a-zA-Z\s()])$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}

function checkemail(mailid)
{
 var numericExpression = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}$/i;
 if(mailid.match(numericExpression)) return true;
 else return false;
}

function validatephone(phonenumber)
{
	var numericExpression = /^[^9]\d{5,7}(?:(?:([,][\s]|[;][\s]|[,;])[^9]\d{5,7}))*$/i;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}

//function to validate the contact person
function validatecontactperson(contactname)
{
 var numericExpression = /^([A-Z\s\()]+[a-zA-Z\s()])(?:(?:[,;]([A-Z\s()]+[a-zA-Z\s()])))*$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}

//function to validate the business name
function validatebusinessname(contactname)
{
 var numericExpression = /^([A-Z0-9\s\-()]+[a-zA-Z0-9\s-()])(?:(?:[,;]([A-Z0-9\s-()]+[a-zA-Z0-9\s-()])))*$/i;
 if(contactname.match(numericExpression)) return true;
 else return false;
}

function computeridvalidate(compid)
{
	var numericExpresion = /^[0-9]{3}0[0|9]-[0-9]{9}$/;
	if(compid.match(numericExpresion)) return true;
	return false;
}


function emailvalidation(emailid)
{
	var emailExp = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}(?:(?:[,;][A-Z0-9\._%-]+@[A-Z0-9\.-]+))*$/i;
	var emails = emailid.replace(/[,\s]*,[,\s]*/g, ",").replace(/^,/, "").replace(/,$/, "");
	if(emails.match(emailExp)) { return true; }
	else { return false; }
}


function validatepercentage(str)
{
	var flag = false;
	var x = parseFloat(str);
	if ((isNaN(x)) || (x < 0) || (x > 100))
    flag = false;
	else
	flag =  true;
	return flag;
}

function IsNumeric(sText)
{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char; 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
	  if(IsNumber == false)
	   sText = '';
   return sText;
}

function makefocus(field)
{
	document.getElementById(field).focus();
}

function printContent(id)
{
	str=document.getElementById(id).innerHTML
	newwin=window.open('','printwin','left=100,top=100,width=400,height=400')
	newwin.document.write('<HTML>\n<HEAD>\n')
	newwin.document.write('<TITLE>Print Page</TITLE>\n')
	newwin.document.write('<script>\n')
	newwin.document.write('function chkstate(){\n')
	newwin.document.write('if(document.readyState=="complete"){\n')
	newwin.document.write('window.close()\n')
	newwin.document.write('}\n')
	newwin.document.write('else{\n')
	newwin.document.write('setTimeout("chkstate()",2000)\n')
	newwin.document.write('}\n')
	newwin.document.write('}\n')
	newwin.document.write('function print_win(){\n');
	newwin.document.write('window.print();\n')
	newwin.document.write('chkstate();\n')
	newwin.document.write('}\n')
	newwin.document.write('<\/script>\n')
	newwin.document.write('</HEAD>\n')
	newwin.document.write('<BODY onload="print_win()">\n')
	newwin.document.write(str)
	newwin.document.write('</BODY>\n')
	newwin.document.write('</HTML>\n')
	newwin.document.close()
}

function getprocessingimage()
{
	var imagehtml = '<img src="../images/imax-loading-image.gif" border="0"/>';
	return imagehtml;
}
//Function to display a error message if the script failed-Meghana[11/12/2009]
function scripterror()
{
	var msghtml = '<div class="errorbox">Unable to Connect....</div>';
	return msghtml;
}


//Validation of website - common function -18/11/2009
function validatewebsite(website)
{
	var websiteExpression = /^(www\.)?[a-zA-Z0-9-\.,]+\.[a-zA-Z]{2,4}$/i;
	if(website.match(websiteExpression)) return true;
	else return false;
}


/*function validatesmsfromname(fromname)
{
	alert(fromname);
	var numericExpression = /^[a-zA-Z0-9]$/i;
	alert(fromname.match(numericExpression));
	if(fromname.match(numericExpression)) return true;
	else return false;
}
*/

//Validation SMS From Name
function validatesmsusername(fromname)
{
	if (fromname.match(/^[a-zA-Z0-9.@_-]+$/))
	{
		return true;
	}
	else
	{
		return false;
	} 
}

//Validation SMS User Name
function validatesmsfromname(fromname)
{
	if (fromname.match(/^[a-zA-Z0-9-]+$/))
	{
		return true;
	}
	else
	{
		return false;
	} 
}

function displayfilterdiv()
{
	if($('#displayfilter').is(':visible'))
		$('#displayfilter').hide();
	else
		$('#displayfilter').show();
}


function in_array(checkvalue, arrayobject) 
{
	for(var i = 0, l = arrayobject.length; i < l; i++) 
	{
		if(arrayobject[i] == checkvalue) 
		{
			return true;
		}
	}
	return false;
}

function displayDiv()
{
	if($('#filterdiv').is(':visible'))
		$("#filterdiv").hide();
	else
		$("#filterdiv").show();
}

/*function loadingprocessimage()
{
	setTimeout("document.getElementById('loadingimagedisplay').style.display = 'none'",3000);	
}
*/

function validateproductcode(productcode)
{
	var numericExpression = /^[0-9]{3}$/;
	if(productcode.match(numericExpression)) return true;
	else return false;
}

function validateschemename(contactname)
{
	var numericExpression = /^([A-Z0-9\s\-()\/]+[a-zA-Z0-9\s-()\/])(?:(?:[,;]([A-Z0-9\s-()\/]+[a-zA-Z0-9\s-()\/])))*$/i;
	if(contactname.match(numericExpression)) return true;
	else return false;
}



//Function to get the value of selected radio element---------------------------------------------------------------
function getradiovalue(radioname)
{
	if(radioname.value)
		return radioname.value;
	else
	{
		for(var i = 0; i < radioname.length; i++) 
		{
			if(radioname[i].checked) 
				return radioname[i].value;
		}
	}

}

//Function to make the display as block as well as none-------------------------------------------------------------

function displayDiv2(elementid,imgname)
{
	if($('#'+ elementid).is(':visible'))
	{
		$("#filterdiv").hide();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/plus.jpg");
	}
	else
	{
		$("#filterdiv").show();
		if($('#'+ imgname))
			$('#'+ imgname).attr('src',"../images/minus.jpg");
	}
}

function displaylabelDiv()
{
	if($('#labeldiv').is(':visible') )
		$("#labeldiv").hide();
	else
		$("#labeldiv").show();
}
function opendiv(divid)
{
	if($('#'+ divid).is(':visible') )
		$('#'+ divid).hide();
	else
		$('#'+ divid).show();
}


function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function validatepositivenumbers(amount)
{
	var numericExpression = /^[+]?[0-9]\d{0,9}(\.\d{1,2})?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}

function validateamountfield(amount)
{
	var numericExpression = /^[+]?[0-9]\d{0,9}?%?$/;
	if(amount.match(numericExpression)) return true;
	else return false;
}

function checkdate(datevalue) //dd-mm-yyyy Eg: 01-04-2008
{
 if(datevalue.length == 10)
 {
  if(isanumber(datevalue.charAt(0)) && isanumber(datevalue.charAt(1)) && isanumber(datevalue.charAt(3)) && isanumber(datevalue.charAt(4)) && isanumber(datevalue.charAt(6)) && isanumber(datevalue.charAt(7)) && isanumber(datevalue.charAt(8)) && isanumber(datevalue.charAt(9)) && datevalue.charAt(2) == '-' && datevalue.charAt(5) == '-')
   return true;
  else
   return false;
 }
 else
  return false;
}



//Function to Convert Number to Words
function NumbertoWords(s)
{
	var th = ['','Thousand','Million', 'Billion','Trillion'];

	var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine']; 
	var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen']; 
	var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety']; 
	s = s.toString();
	s = s.replace(/[\, ]/g,''); 
	if (s != parseFloat(s)) return 'not a number'; 
	var x = s.indexOf('.'); if (x == -1) x = s.length; 
	if (x > 15) return 'too big'; var n = s.split(''); 
	var str = ''; 
	var sk = 0; 
	for (var i=0; i < x; i++) 
	{
		if ((x-i)%3==2) 
		{
			if (n[i] == '1') 
			{
				str += tn[Number(n[i+1])] + ' '; i++; sk=1;
			}
			else 
			if (n[i]!=0) 
			{
				str += tw[n[i]-2] + ' ';
				sk=1;
			}
		} 
		else 
		if (n[i]!=0) 
		{
			str += dg[n[i]] +' '; 
			if ((x-i)%3==0) 
			str += 'hundred ';sk=1;
		} 
		if ((x-i)%3==1) 
		{
			if (sk) 
				str += th[(x-i-1)/3] + ' ';sk=0;
		}
	} 
	if (x != s.length)
	{
		var y = s.length; 
		str += 'point '; 
		for (var i=x+1; i<y; i++) 
			str += dg[n[i]] +' ';
	} 
	return str.replace(/\s+/g,' ');
}

function removedoublecomma(string)
{
	finalstring = string;
	var newArr = new Array();for (k in finalstring) if(finalstring[k]) newArr.push(finalstring[k])
	return newArr;
}

function intToFormat(nStr)
{
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	var z = 0;
	var len = String(x1).length;
	var num = parseInt((len/2)-1);
	while (rgx.test(x1))
	{
		if(z > 0)
		{
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		else
		{
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
			rgx = /(\d+)(\d{2})/;
		}
		z++;
		num--;
		if(num == 0)
		{
			break;
		}
	}
	return x1 + x2;
}

function trimdotspaces(text)
{
	var output = text.replace(/ /g,""); 
	var output2 = output.replace(/\./g,"");
	return output2;
}


function gridtabvertical(activetab,tabgroupname)
{
	var totaltabs = $('#branchcount').val();
	var activetabheadclass = 'imp_grid-active-tabclass';
	var tabheadclass = 'imp_grid-tabclass';
	
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		//var tabcontent = tabgroupname + 'c' + i;
		//var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+ tabhead).removeClass(tabheadclass);
			$('#'+ tabhead).addClass(activetabheadclass);
			//$('#'+tabcontent).show();
		}
		else
		{
			$('#'+ tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			//$('#'+tabcontent).hide();
		}
	}
}

function gridtabvertical5(activetab,tabgroupname)
{
	var totaltabs = $('#branchcount').val();
	var activetabheadclass = 'imp_grid-active-tabclass';
	var tabheadclass = 'imp_grid-tabclass';
	for(var i=1; i<=totaltabs; i++)
	{
		var tabhead = tabgroupname + 'h' + i;
		//var tabcontent = tabgroupname + 'c' + i;
		var tabwaitbox = tabgroupname + 'wb' + i;
		if(i == activetab)
		{
			$('#'+ tabhead).removeClass(tabheadclass);
			$('#'+ tabhead).addClass(activetabheadclass);
			//$('#'+tabcontent).show();
		}
		else
		{
			$('#'+ tabhead).removeClass(activetabheadclass);
			$('#'+tabhead).addClass(tabheadclass);
			//$('#'+tabcontent).hide();
		}
	}
}



function selectdeselectcommon(selectionid,checkboxname)
{
	var selectproduct = $('#' + selectionid);
	var chkvalues = $("input[name='"+ checkboxname +"']");
	for (var i=0; i < chkvalues.length; i++)
	{
		if($(chkvalues[i]).is(':checked'))
		{
			$(chkvalues[i]).attr('checked',false);
		}
		if(($('#'+selectionid).is(':checked')) == true) 
			$(chkvalues[i]).attr('checked',true);
		else if(($('#'+selectionid).is(':checked')) == false) 
			$(chkvalues[i]).attr('checked',false);
	}
}

function validatecustomerid(cusid)
{
	
	var numericExpresion1 = /^\d{17}$/;
	var numericExpresion = /^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{5}$/;
	var numericExpresion2 = /^[0-9]{4}(\s)[0-9]{4}(\s)[0-9]{4}(\s)[0-9]{5}$/;
	if(cusid.match(numericExpresion)) return true;
	else if(cusid.match(numericExpresion1)) return true;
	else if(cusid.match(numericExpresion2)) return true;
	else return false;
}