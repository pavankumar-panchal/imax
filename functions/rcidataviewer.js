//Function to display records ------------------------------------------
function getrcidetails(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=rcidetails&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/rcidataviewer.php";
	$('#tabgroupgridc1_1').html(getprocessingimage());
	$('#tabgroupgridc1link').html('');
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
				var response = ajaxresponse.split('^');
				if(response[0] == '1')
				{
				$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
				$('#tabgroupgridc1_1').html(response[1]);
				$('#tabgroupgridc1link').html(response[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}

//Function for "show more records" or  "show all records" link ------------------------------------------  
function getmorercidetails(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=rcidetails&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/rcidataviewer.php";
	$('#tabgroupgridc1link').html(getprocessingimage());
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
				$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
				$('#resultgrid').html($('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1] );
				$('#tabgroupgridc1link').html(response[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1").html(scripterror());
		}
	});	
}


//Function to Search the data from Inventory------------------------------------------
function filterrcidata(startlimit)
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error');
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); field.focus(); return false;	}
	var textfield = $('#searchcriteria').val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=rcidatafilter&databasefield=" + encodeURIComponent(subselection) + "&operatingsystem=" + encodeURIComponent($('#os').val()) + "&processor=" + encodeURIComponent($('#processor').val()) + "&state=" + encodeURIComponent($('#state').val())  + "&region=" +encodeURIComponent($('#region').val())+ "&district=" +encodeURIComponent($('#district2').val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) + "&branch=" + encodeURIComponent($('#branch').val())+"&type=" +encodeURIComponent($('#type').val()) + "&category=" + encodeURIComponent($('#category').val()) + "&startlimit=" + encodeURIComponent(startlimit) + "&dummy=" + Math.floor(Math.random()*10054300000);
	$('#filter-form-error').html(getprocessingimage());
	queryString = "../ajax/rcidataviewer.php";
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
					$('#filter-form-error').html('');
					gridtab2(2,'tabgroupgrid','&nbsp; &nbsp;Search Results');
					$('#tabgroupgridwb1').html("Total Count :  " + response[2]);
					$('#tabgroupgridc2_1').html(response[1]);
					$('#tabgroupgridc2link').html(response[3]);
				}
				else
				{
					$('#tabgroupgridc1_1').html("No datas found to be displayed.");
				}		
			}
		}, 
		error: function(a,b)
		{
			$("#filter-form-error").html(scripterror());
		}
	});	
}

function getmorefilterrcidata(startlimit,slnocount,showtype)
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error');
	var values = validateproductcheckboxes();
	if(values == false)	{error.html(errormessage("Select A Product")); field.focus(); return false;	}
	var textfield = $('#searchcriteria').val();
	var subselection = $("input[name='databasefield']:checked").val();
	var c_value = '';
	var newvalue = new Array();
	var chks = $("input[name='productarray[]']");
	for (var i = 0; i < chks.length; i++)
	{
		if ($(chks[i]).is(':checked'))
		{
			c_value += "'" + $(chks[i]).val() + "'" + ',';
		}
	}
	var productslist = c_value.substring(0,(c_value.length-1));
	var passData = "switchtype=rcidatafilter&databasefield=" + encodeURIComponent(subselection) + "&operatingsystem=" + encodeURIComponent($('#os').val()) + "&processor=" + encodeURIComponent($('#processor').val()) + "&state=" + encodeURIComponent($('#state').val())  + "&region=" +encodeURIComponent($('#region').val())+ "&district=" +encodeURIComponent($('#district2').val()) + "&textfield=" +encodeURIComponent(textfield) +  "&productscode=" +encodeURIComponent(productslist) + "&branch=" + encodeURIComponent($('#branch').val())+"&type=" +encodeURIComponent($('#type').val()) + "&category=" + encodeURIComponent($('#category').val()) + "&startlimit=" + encodeURIComponent(startlimit)  + "&showtype=" + encodeURIComponent(showtype)+ "&slnocount=" + encodeURIComponent(slnocount)+ "&dummy=" + Math.floor(Math.random()*10054300000);
	error.html(getprocessingimage());
	var queryString = "../ajax/rcidataviewer.php";
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
				error.html('');
				var ajaxresponse = ajaxresponse.split('^');
				if(ajaxresponse[0] == '1')
				{
					$('#tabgroupgridwb1').html("Total Count :  " + ajaxresponse[2]);
					$('#searchresultgrid').html($('#tabgroupgridc2_1').html());
					$('#tabgroupgridc2_1').html($('#searchresultgrid').html().replace(/\<\/table\>/gi,'')+ ajaxresponse[1]) ;
					$('#tabgroupgridc2link').html(ajaxresponse[3]);
				}
				else
				{
					$('#tabgroupgridc2_1').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			error.html(scripterror());
		}
	});	
}


function validateproductcheckboxes()
{
var chksvalue = $("input[name='productarray[]']");
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


//Function to reset the from to the default value
function resetDefaultValues(oForm)
{
    var elements = oForm.elements; 
 	oForm.reset();
	$('#filter-form-error').html('');
	for (i=0; i<elements.length; i++) 
	{
		var field_type = elements[i].type.toLowerCase();
	}
	switch(field_type)
	{
	
		case "text": 
			elements[i].value = ""; 
			break;
		case "radio":
			if(elements[i].checked == 'databasefield1')
			{
				elements[i].checked = true;
			}
			else
			{
				elements[i].checked = false; 
			}
			break;
		case "checkbox":
  			if (elements[i].checked) 
			{
   				elements[i].checked = true; 
			}
			break;
		case "select-one":
		{
  			 for (var k=0, l=oForm.elements[i].options.length; k<l; k++)
			 {
             oForm.elements[i].options[k].selected = oForm.elements[i].options[k].defaultSelected;
			 //oForm.elements[i].selectedIndex = -1;
			 }
				
		}
		break;

		default:$('#districtcodedisplaysearch').html('<select name="district2" class="swiftselect" id="district2" style="width:180px;"><option value="">ALL</option></select>');
			
	}
}


function selectdeselectall(showtype)
{
	var selectproduct = $('#selectproduct');
	var chkvalues = $("input[name='productarray[]']");
	//var changestatus = (selectproduct.value == 'STO')?true:false;
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
	}else if(showtype == 'more')
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


function filtertoexcel(command)
{
	var form = $('#searchfilterform');
	var error = $('#filter-form-error');
	error.html(getprocessingimage());
	if(command == 'toexcel')
	{
		error.html('');
		$('#searchfilterform').attr("action", "../ajax/rcidatatoexcel.php") ;
		$('#searchfilterform').submit();
	}
}
