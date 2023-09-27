function formsubmit(command)
{
	var form = $('#submitform');
	if(command == 'view')
	{
		$('#submitform').attr("action", "../reports/excelupdationsummary.php?id=view") ;
		$('#submitform').attr( 'target', '_blank' );
		$('#submitform').submit();
	}
	else
	{
		$('#submitform').attr("action", "../reports/excelupdationsummary.php?id=toexcel") ;
		$('#submitform').submit();
	}
}



