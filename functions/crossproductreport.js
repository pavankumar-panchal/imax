// JavaScript Document
function formsubmit(command)
{
	
	var form = $('#submitform');
	var error = $('#form-error');
	var field =  $('#region');
	var field =  $('#dealerid');
	if(command == 'toexcel')
	{
		error.html('');
		$('#submitform').attr("action", "../reports/excelcrossproductreport.php?id=toexcel") ;
		$('#submitform').submit();
	}
}