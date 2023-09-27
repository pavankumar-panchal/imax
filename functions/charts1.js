function formsubmit()
{
	var form = document.submitform;
	var error = document.getElementById('form-error');
	var field_type = getradiovalue(form.charts);
	var field = form.splityear;
	if(field.checked == true) var splityear = 'yes'; else splityear = 'no';
	if(splityear == 'yes')
	{
		var passData = "type=chartdata&groupwise=" + encodeURIComponent(form.groupwise.value) + "&dealerwise=" + encodeURIComponent(form.dealerwise.value) + "&statewise=" + encodeURIComponent(form.statewise.value) + "&branchwise=" + encodeURIComponent(form.branchwise.value) + "&typewise=" + encodeURIComponent(form.typewise.value) + "&categorywise=" + encodeURIComponent(form.categorywise.value) + "&field_type=" + encodeURIComponent(field_type)  + "&dummy=" + Math.floor(Math.random()*100000000);
	}
	else if(splityear == 'no')
	{
		var passData = "type=datadisplay&groupwise=" + encodeURIComponent(form.groupwise.value) + "&dealerwise=" + encodeURIComponent(form.dealerwise.value) + "&statewise=" + encodeURIComponent(form.statewise.value) + "&branchwise=" + encodeURIComponent(form.branchwise.value) + "&typewise=" + encodeURIComponent(form.typewise.value) + "&categorywise=" + encodeURIComponent(form.categorywise.value) + "&field_type=" + encodeURIComponent(field_type)  + "&dummy=" + Math.floor(Math.random()*100000000);
	}
	
	var ajaxcall1 = createajax();
	document.getElementById('form-error').innerHTML = getprocessingimage();
	queryString = "../ajax/dataforcharts11.php";
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText;
				document.getElementById('form-error').innerHTML = '';
				
				if(response == 'Thinking to redirect')
				{
					window.location = "../logout.php";
					return false;
				}
				else
				{
					var ajaxresponse = response.split('^');
					if(ajaxresponse[0] == '1')
					{
						var ajaxresponse1 = ajaxresponse[1].split('$');
						document.getElementById('year04').value = ajaxresponse1[0];
						document.getElementById('year05').value = ajaxresponse1[1];
						/*document.getElementById('year06').value = ajaxresponse1[2];
						document.getElementById('year07').value = ajaxresponse1[3];
						document.getElementById('year08').value = ajaxresponse1[4];
						document.getElementById('year09').value = ajaxresponse1[5];
						document.getElementById('year10').value = ajaxresponse1[6];
						document.getElementById('name').value = ajaxresponse1[7];//alert(ajaxresponse[7])*/
						graph();
					}
					else if(ajaxresponse[0] == '2')
					{
						var ajaxresponse1 = ajaxresponse[1].split('$');
						document.getElementById('allyear').value = ajaxresponse1[0];
						document.getElementById('name').value = ajaxresponse1[1];
						graphallyear(field_type);
					}
				}
					
			}
			else
				document.getElementById('form-error').innerHTML = scripterror();
		}
	}
	ajaxcall1.send(passData);
	
}
  
  
  
function graph()
{
	$(document).ready(function(){
	$.jqplot.config.enablePlugins = true;
	line1 = (function() {
						var data = [];
						value = $("#year04").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
	alert(line1)
      plot = $.jqplot('graphdisplay', [line1], 
      {legend:{show:true, location:'ne'}, 
      title:'Horizontally Oriented Bar Chart',  
      series:[
        {
          label:'Cats', 
          renderer:$.jqplot.BarRenderer, 
          rendererOptions:{barDirection:'horizontal', barPadding: 6, barMargin:15}, 
          shadowAngle:135
        }, 
        {
          label:'Dogs', 
          renderer:$.jqplot.BarRenderer, 
          rendererOptions:{barDirection:'horizontal', barPadding: 6, barMargin:15}, 
          shadowAngle:135
        }, 
        {showMarker: false, label:'min'}, 
        {showMarker: false, label:'max'}
      ], 
      axes:{xaxis:{min:0}, yaxis:{renderer:$.jqplot.CategoryAxisRenderer, ticks:[]}}});
      
});

}
