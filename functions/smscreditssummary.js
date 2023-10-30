function getsmscreditssummary(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=smscreditssummarygrid&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/smscreditssummary.php";
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
					$('#totalpurchased').html(response[4]);
					$('#totalutilized').html(response[5]);
					$('#stockavailable').html(response[6]);
					$('#totalallocatd').html(response[7]);
					$('#totalusedbyusers').html(response[8]);
					$('#unusedwithusers').html(response[9]);
					$('#balanceavailable').html(response[10]);
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

//Function for "show more records" or  "show all records" link  - to get registration records
function getmoresmscreditssummary(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=smscreditssummarygrid&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/smscreditssummary.php";
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
				$('#resultgrid').html( $('#tabgroupgridc1_1').html());
				$('#tabgroupgridc1_1').html($('#resultgrid').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
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


function getsmscreditssummarypromo(startlimit)
{
	var form = $('#submitform');
	var startlimit = '';
	var passData = "switchtype=smscreditssummarygridpromo&startlimit="+ encodeURIComponent(startlimit);
	var queryString = "../ajax/smscreditssummary.php";
	$('#tabgroupgridc1_1promo').html(getprocessingimage());
	$('#tabgroupgridc1linkpromo').html('');
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
					$('#tabgroupgridwb1promo').html("Total Count :  " + response[2]);
					$('#tabgroupgridc1_1promo').html(response[1]);
					$('#tabgroupgridc1linkpromo').html(response[3]);
					$('#totalpurchasedpromo').html(response[4]);
					$('#totalutilizedpromo').html(response[5]);
					$('#stockavailablepromo').html(response[6]);
					$('#totalallocatdpromo').html(response[7]);
					$('#totalusedbyuserspromo').html(response[8]);
					$('#unusedwithuserspromo').html(response[9]);
					$('#balanceavailablepromo').html(response[10]);
				}
				else
				{
					$('#tabgroupgridc1_1promo').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1promo").html(scripterror());
		}
	});
}

function filtertoexcel(command)
{
	var form = $('#submitform');
	var error = $('#form-error');
	error.html(getprocessingimage());
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../ajax/smscreditssummarytoexcel.php?id=toexcel") ;
		$('#submitform').submit();
	}
}

//Function for "show more records" or  "show all records" link  - to get registration records
function getmoresmscreditssummarypromo(startlimit,slnocount,showtype)
{
	var form = $('#submitform');
	var passData = "switchtype=smscreditssummarygridpromo&startlimit="+ encodeURIComponent(startlimit) + "&slnocount=" + encodeURIComponent(slnocount) + "&showtype=" + encodeURIComponent(showtype)  + "&dummy=" + Math.floor(Math.random()*1000782200000);
	//alert(passData);
	var queryString = "../ajax/smscreditssummary.php";
	$('#tabgroupgridc1linkpromo').html(getprocessingimage());
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
				var response = ajaxresponse.split('^');//alert(response);
				if(response[0] == '1')
				{
				$('#tabgroupgridwb1promo').html("Total Count :  " + response[2]);
				$('#resultgridpromo').html($('#tabgroupgridc1_1promo').html());
				$('#tabgroupgridc1_1promo').html($('#resultgridpromo').html().replace(/\<\/table\>/gi,'')+ response[1]) ;
				$('#tabgroupgridc1linkpromo').html(response[3]);
				}
				else
				{
					$('#tabgroupgridc1_1promo').html("No datas found to be displayed.");
				}
			}
		}, 
		error: function(a,b)
		{
			$("#tabgroupgridc1_1promo").html(scripterror());
		}
	});
}
