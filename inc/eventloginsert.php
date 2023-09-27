<?
if(imaxgetcookie('userid') <> '' || imaxgetcookie('userid') <>  false) 
{
	$pagelinksplit = explode('/',$pagelink);
	$pagelinkvalue = substr($pagelinksplit[2],0,-4);
	$userid = imaxgetcookie('userid');
	switch($pagelinkvalue)
	{
		case 'dashboard':  $pagetextvalue = '172'; break;
		case 'cusprofileupdate':  $pagetextvalue = '173'; break;
		case 'dealerprofileupdate':  $pagetextvalue = '174'; break;
		case 'products':  $pagetextvalue = '175'; break;
		case 'scheme':  $pagetextvalue = '176'; break;
		case 'schememapping':  $pagetextvalue = '177'; break;
		case 'productmapping':  $pagetextvalue = '178'; break;
		case 'schemepricing':  $pagetextvalue = '179'; break;
		case 'deployment':  $pagetextvalue = '180'; break;
		case 'implementation':  $pagetextvalue =  '181'; break;
		case 'dealer':  $pagetextvalue = '182'; break;
		case 'credit':  $pagetextvalue = '183'; break;
		case 'bills':  $pagetextvalue = '184'; break;
		case 'districtmapping':  $pagetextvalue = '185'; break;
		case 'customer':  $pagetextvalue = '186'; break;
		case 'customeramc':  $pagetextvalue = '187'; break;
		case 'hardwarelock':  $pagetextvalue ='188'; break;
		case 'cusattachcard':  $pagetextvalue ='189'; break;
		case 'mergecustomer':  $pagetextvalue ='190'; break;
		case 'mergecustomerlist':  $pagetextvalue ='191'; break;
		case 'cusinteraction':  $pagetextvalue ='192'; break;
		case 'rcidataviewer':  $pagetextvalue ='193'; break;
		case 'crossproductinfo':  $pagetextvalue ='194'; break;
		case 'smscreditssummary':  $pagetextvalue ='195'; break;
		case 'smsaccount':  $pagetextvalue ='196'; break;
		case 'smscredits':  $pagetextvalue ='197'; break;
		case 'smsreceipt':  $pagetextvalue ='198'; break;
		case 'cardsearch':  $pagetextvalue ='199'; break;
		case 'blockcancel':  $pagetextvalue ='200'; break;
		case 'invoicing':  $pagetextvalue ='201'; break;
		case 'receipts':  $pagetextvalue ='202'; break;
		case 'receiptreconsilation':  $pagetextvalue ='203'; break;
		case 'invoiceregister':  $pagetextvalue ='204'; break;
		case 'receiptregister':  $pagetextvalue ='205'; break;
		case 'outstandingregister':  $pagetextvalue ='206'; break;
		case 'updateinvoice':  $pagetextvalue ='207'; break;
		case 'bulkprinting':  $pagetextvalue ='208'; break;
		case 'custpayment':  $pagetextvalue ='209'; break;
		case 'changepw':  $pagetextvalue ='223'; break;
		case 'contactdetailsreport':  $pagetextvalue ='210'; break;
		case 'labelsforcontactdetails':  $pagetextvalue ='211'; break;
		case 'updationdue':  $pagetextvalue ='212'; break;
		case 'cuscardattachreport':  $pagetextvalue ='213'; break;
		case 'registration':  $pagetextvalue ='214'; break;
		case 'productshipped':  $pagetextvalue ='215'; break;
		case 'dealerreport':  $pagetextvalue ='216'; break;
		case 'updationdetailedreport':  $pagetextvalue ='217'; break;
		case 'crossproductreport':  $pagetextvalue ='218'; break;
		case 'updationsummary':  $pagetextvalue ='219'; break;
		case 'implementationreport':  $pagetextvalue ='220'; break;
		case 'implementationstatusreport':  $pagetextvalue ='221'; break;
		case 'custdata':  $pagetextvalue ='222'; break;
		case 'notregistered':  $pagetextvalue ='251'; break;
		case 'dealerattachcard':  $pagetextvalue ='252'; break;
		case 'customuser':  $pagetextvalue ='253'; break;
	    case 'mailamccustomer':  $pagetextvalue ='254'; break;
        case 'addproductsnew':  $pagetextvalue ='255'; break;
		case 'transferredpinsreport':  $pagetextvalue ='256'; break;
		case 'updatedealerinvoice':  $pagetextvalue ='257'; break;
		case 'dealerreceipts':  $pagetextvalue ='258'; break;
		case 'dealerreceiptreconciliation':  $pagetextvalue ='259'; break;
		case 'dealerinvoiceregister':  $pagetextvalue ='260'; break;
		case 'dealerbulkprintinvoice':  $pagetextvalue ='261'; break;
		case 'matrixinvoicing':  $pagetextvalue ='262'; break;
		case 'updatematrixinvoice':  $pagetextvalue ='263'; break;
		case 'matrixbulkprintinvoice':  $pagetextvalue ='264'; break;
		case 'matrixinvoiceregister':  $pagetextvalue ='265'; break;
	}
	$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','".$pagetextvalue."','".date('Y-m-d').' '.date('H:i:s')."')";
	$eventresult = runmysqlquery($eventquery);
}
	
?>
