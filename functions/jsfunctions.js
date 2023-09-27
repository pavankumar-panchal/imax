// JavaScript Document

//Function to create Ajax Object [Common Function]
function createajax()
{
   var objectname = false;	
	try{
		// Internet Explorer Browsers
		objectname = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e){
		try{
			objectname = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (e) {
			try{
		// Opera 8.0+, Firefox, Safari
				objectname = new XMLHttpRequest();
			} catch (e){
				// Something went wrong
				alert("Your browser is not responding for Javascripts.");
				return false;
			}
		}
	}
	return objectname;
}

//-----------------------------------------------------------------------------------------------------------------

var rotateMsg = true;
function MsgStatus() 
{
	if(rotateMsg) 
	{
		window.status = '';
		window.defaultStatus = ' Relyon User Login Area';
	}
	if(!rotateMsg) 
	{
		window.status = '';
		window.defaultStatus = ' All rights reserved for Relyon Softech Ltd';
	}
	setTimeout("MsgStatus();rotateMsg=!rotateMsg", 1500);
}
MsgStatus();

//-----------------------------------------------------------------------------------------------------------------

function checkemail(a)
{
  var r1 = new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
  var r2 = new RegExp("^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,3}|[0-9]{1,3})(\\]?)$");
  return (!r1.test(a) && r2.test(a));
}

function chkNumeric(objName)
{
	var checkOK = "0123456789";
	var checkStr = objName;
	var allValid = false;
	
	for (i = 0;  i < checkStr.length;  i++)
		{
			ch = checkStr.charAt(i);
			for (j = 0;  j < checkOK.length;  j++)
				{
					if (ch == checkOK.charAt(j) && ch != "," && ch != ".")
						{
							allValid = true;	
							break;
						}
					allValid = false;	
				}
		}
	return allValid;
}

function tog(divid1,obj) {
 if (document.getElementById) 
 {
  var a = document.getElementById(divid1).style;
  if (a.display == "block") {
   a.display = "none"; 
    } else {
   a.display = "block";	
  }
  return false;
  } else {
  return true;
 }
}

function getradiovalue(radioname)
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

function validatecell(cellnumber)
{
	var numericExpression = /^[7|8|9]+[0-9]{9,9}(?:(?:[,;][9]+[0-9]{9,9}))*$/i;
	if(cellnumber.match(numericExpression)) return true;
	else return false;
}

function validatephone(phonenumber)
{
	var numericExpression = /^([^9]\d{5,9})(?:(?:[,;]([^9]\d{5,9})))*$/i;
	if(phonenumber.match(numericExpression)) return true;
	else return false;
}

function validatestdcode(stdcodenumber)
{
	var numericExpression = /^[0]+[0-9]{2,4}$/i;
	if(stdcodenumber.match(numericExpression)) return true;
	else return false;
}

//Function to display the error message in box---------------------------------------------------------------------
function errormessage(message)
{
	var msg = '<div class="errorbox">' + message + '</div>';
	return msg;
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