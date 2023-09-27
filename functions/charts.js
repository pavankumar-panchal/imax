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
	queryString = "../ajax/dataforcharts.php";
	ajaxcall1.open("POST", queryString, true);
	ajaxcall1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxcall1.onreadystatechange = function()
	{
		if(ajaxcall1.readyState == 4)
		{
			if(ajaxcall1.status == 200)
			{
				var response = ajaxcall1.responseText;
				//alert(response)
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
						document.getElementById('year06').value = ajaxresponse1[2];
						document.getElementById('year07').value = ajaxresponse1[3];
						document.getElementById('year08').value = ajaxresponse1[4];
						document.getElementById('year09').value = ajaxresponse1[5];
						document.getElementById('year10').value = ajaxresponse1[6];
						document.getElementById('name').value = ajaxresponse1[7];//alert(ajaxresponse[7])
						diaplaygraph(field_type);
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

function diaplaygraph(field_type)
{
	switch(field_type)
	{
		case "branch":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:1000,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}    
						},
				title: {
					text: '<b>Distribution of Customer Branch wise',
						},
				series: [{
							name: '2004-05',
							type: 'bar',
							data: (function() {
							var data = [];
							var value = $("#year04").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						 },{
							name: '2005-06',
							type: 'bar',
							data: (function() {
							var data = [];
							value = $("#year05").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						},{
							name: '2006-07',
							type: 'bar',
							data: (function() {
							var data = [];
							value = $("#year06").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						},{
							name: '2007-08',
							type: 'bar',
							data: (function() {
							var data = [];
							value = $("#year07").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						},{
							name: '2008-09',
							type: 'bar',
							data: (function() {
							var data = [];
							value = $("#year08").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						},{
							name: '2010-11',
							type: 'bar',
							data: (function() {
							var data = [];
							value = $("#year09").val();
							var response = value.split(',')
							
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
								return data;})()
						}],
				xAxis:{
						categories: (function() {
						var data = [];
						value = $("#name").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push(response[i]
						);
						style: {
						font: 'normal 5px Verdana, sans-serif'
								}
						}
							return data;})()
					},
				yAxis:{
						 max: 4000,
						title: {
							text: '',
								 }
					  },
				
   });
			
});
		}
		break;
		
		case "dealer":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					height:10000,
					renderTo: 'graphdisplay',
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}
				},
				title: {
					text: '<b>Distribution of Customer Dealer wise',
				},
				series: [{
							name: '2004-05',
							type: 'bar',
							data: (function() {
							var data1 = [];
							var value1 = $("#year04").val();
							var response1 = value1.split(',')
							for( var i=0; i<response1.length; i++)
							{
								data1.push({y: response1[i]});
							}
								return data1;})()
						 },{
							name: '2005-06',
							type: 'bar',
							data: (function() {
							var data2 = [];
							value2 = $("#year05").val();
							var response2 = value2.split(',')
							for( var i=0; i<response2.length; i++)
							{
								data2.push({y: response2[i]});
							}
								return data2;})()
						},{
							name: '2006-07',
							type: 'bar',
							data: (function() {
							var data3 = [];
							value3 = $("#year06").val();
							var response3 = value3.split(',')
							for( var i=0; i<response3.length; i++)
							{
								data3.push({y: response3[i]});
							}
									
								return data3;})()
						},{
							name: '2007-08',
							type: 'bar',
							data: (function() {
							var data4 = [];
							value4 = $("#year07").val();
							var response4 = value4.split(',')
							for( var i=0; i<response4.length; i++)
							{
								data4.push({y: response4[i]});
							}
								return data4;})()
						},{
							name: '2008-09',
							type: 'bar',
							data: (function() {
							var data5 = [];
							value5 = $("#year08").val();
							var response5 = value5.split(',')
							for( var i=0; i<response5.length; i++)
							{
								data5.push({y: response5[i]});
							}
								return data5;})()
						},{
							name: '2010-11',
							type: 'bar',
							data: (function() {
							var data6 = [];
							value6 = $("#year09").val();
							var response6 = value6.split(',')
							for( var i=0; i<response6.length; i++)
							{
								data6.push({y: response6[i]});
							}
								return data6;})()
					}],
			xAxis:{
					categories: (function() {
					var data7 = [];
					value7 = $("#name").val();
					var response7 = value7.split(',')
					for( var i=0; i<response7.length; i++)
					{
						data7.push(response7[i]);
						style: {
						font: 'normal 5px Verdana, sans-serif'
								}
					}
				return data7;})()
				},
			yAxis:{
					max: 650,
					title: {
						text: '',
							 }
				 },
   });

});
		}
		break;
		
		case "state":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:2400,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}   
						},
				title: {
					text: '<b>Distribution of Customer State wise',
						},
				series: [{
						name: '2004-05',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#year04").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					 },{
						name: '2005-06',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year05").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2006-07',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year06").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2007-08',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year07").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2008-09',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year08").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
						
					},{
						name: '2010-11',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year09").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
				xAxis:{
						categories: (function() {
						var data = [];
						value = $("#name").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push(response[i]
						);
						style: {
						font: 'normal 5px Verdana, sans-serif'
								}
						}
								
							return data;
						})()
					 },
					yAxis: {
						 max: 5000,
						 title: {
							text: '',
								 }
							},
   });

});
		}
		break;
		case "customertype":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:700,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}   

						},
				title: {
					text: '<b>Distribution of Customer Type wise',
						},
				series: [{
						name: '2004-05',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#year04").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
								
							return data;})()
					 },{
						name: '2005-06',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year05").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
								
							return data;})()
					},{
						name: '2006-07',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year06").val();
						var response = value.split(',')
						
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
						
					},{
						name: '2007-08',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year07").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
								
							return data;})()
					},{
						name: '2008-09',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year08").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2010-11',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year09").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
				xAxis:{
						categories: (function() {
						var data = [];
						value = $("#name").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push(response[i]);
							style: {
							font: 'normal 5px Verdana, sans-serif'
									}
						}
						return data;})()
					},
				yAxis: {
					 	max: 5000,
						title: {
							text: '',
								 }
						},
   });

});
		}
		break;
		case "customercategory":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:3000,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}   

				},
				title: {
					text: '<b>Distribution of Customer Category wise',
				},
				series: [{
						name: '2004-05',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#year04").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					 },{
						name: '2005-06',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year05").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2006-07',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year06").val();
						var response = value.split(',')
						
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2007-08',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year07").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2008-09',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year08").val();
						var response = value.split(',')
						
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					},{
						name: '2010-11',
						type: 'bar',
						data: (function() {
						var data = [];
						value = $("#year09").val();
						var response = value.split(',')
						
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
				xAxis:{
						categories: (function() {
						var data = [];
						value = $("#name").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push(response[i]
						);
							style: {
							font: 'normal 5px Verdana, sans-serif'
									}
						}
					return data;})()
					},
			yAxis: {
					 max: 3000,
					 title: {
							text: '',
							 }
					},
   });

});
		}
		break;
	}
}


function graphallyear(field_type)
{
	switch(field_type)
	{
		case "branch":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:500,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}

				},
				title: {
					text: '<b>Distribution of Customer Branch wise',
				},
			series: [{
						name: 'Branch wise',
						type: 'bar',
						data: (function() {
						var data = [];
							var value = $("#allyear").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push({y: response[i]});
							}
							return data;})()
					}],
			xAxis:{
					categories: (function() {
					var data = [];
					value = $("#name").val();
					var response = value.split(',')
					for( var i=0; i<response.length; i++)
					{
						data.push(response[i]
					);
					style: {
					font: 'normal 5px Verdana, sans-serif'
							}
						}
						return data;})()
					},
			yAxis: {
					max:7000,
					title: {
					text: '',
						}
				  },
   });

});
		}
		break;
		
		case "dealer":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:4000,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}
						},
				title: {
					text: '<b>Distribution of Customer Dealer wise',
						},
			series: [{
						name: 'Dealer wise',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#allyear").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
			xAxis:{
					categories: (function() {
					var data = [];
					value = $("#name").val();
					var response = value.split(',')
					for( var i=0; i<response.length; i++)
					{
						data.push(response[i]
					);
						style: {
						font: 'normal 5px Verdana, sans-serif'
								}
						}
						return data;})()
					},
			yAxis: {
					max: 1000,
					 title: {
						text: '',
							}
					},

   });

});
		}
		break;
		
		case "state":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:900,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}
						},
				title: {
					text: '<b>Distribution of Customer State wise',
						},
				series: [{
						name: 'State wise',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#allyear").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
			xAxis:{
					categories: (function() {
					var data = [];
					value = $("#name").val();
					var response = value.split(',')
					for( var i=0; i<response.length; i++)
					{
						data.push(response[i]
					);
						style: {
						font: 'normal 5px Verdana, sans-serif'
								}
							}
							return data;})()
						},
				yAxis: {
						max: 9000,
						title: {
							text: '',
							 }
						},
   });

});
		}
		break;
		case "customertype":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:400,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}

				},
				title: {
					text: '<b>Distribution of Customer Type wise',
				},
				series: [{
						name: 'CustomerType wise',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#allyear").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
				xAxis:{
							categories: (function() {
							var data = [];
							value = $("#name").val();
							var response = value.split(',')
							for( var i=0; i<response.length; i++)
							{
								data.push(response[i]);
								style: {
								font: 'normal 5px Verdana, sans-serif'
										}
							}
							return data;})()
						},
			yAxis: {
					max: 9000,
					title: {
							text: '',
							 }
					},
   });

});
		}
		break;
		case "customercategory":
		{
			$(document).ready(function() {
			var chart = new Highcharts.Chart({
				chart: {
					borderRadius: 10,
					borderWidth: 1,
					plotShadow: true,
					plotBackgroundColor: '#FCFFC5',
					renderTo: 'graphdisplay',
					height:1100,
					defaultSeriesType: 'bar',
					events: {
								load: function(event) {
									document.getElementById('form-error').innerHTML = '';
								}
								
							}

				},
				title: {
					text: '<b>Distribution of Customer Category wise',
				},
			series: [{
						name: 'Customercategory wise',
						type: 'bar',
						data: (function() {
						var data = [];
						var value = $("#allyear").val();
						var response = value.split(',')
						for( var i=0; i<response.length; i++)
						{
							data.push({y: response[i]});
						}
							return data;})()
					}],
			xAxis:{
					categories: (function() {
					var data = [];
					value = $("#name").val();
					var response = value.split(',')
					for( var i=0; i<response.length; i++)
					{
						data.push(response[i]);
						style: {
						font: 'normal 5px Verdana, sans-serif'
					}
					}
					return data;})()
						},
			yAxis: {
					max: 7000,
							 title: {
							text: '',
						 }
				},

   });

});
		}
		break;
	}
}






