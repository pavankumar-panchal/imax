<?php
		echo('<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">');
	if(file_exists("../style/main.css")) echo('<LINK href="../style/main.css" rel=stylesheet>');
	elseif(file_exists("../../style/main.css")) echo('<LINK href="../../style/main.css" rel=stylesheet>');
	elseif(file_exists("../../../style/main.css")) echo('<LINK href="../../../style/main.css" rel=stylesheet>');
	elseif(file_exists("./style/main.css")) echo('<LINK href="./style/main.css" rel=stylesheet>');
	
	/*if(file_exists("../functions/jquery.js")) echo('<SCRIPT src="../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery.js")) echo('<SCRIPT src="../../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery.js")) echo('<SCRIPT src="../../../functions/jquery.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery.js")) echo('<SCRIPT src="./functions/jquery.js" type=text/javascript></SCRIPT>');*/
	
		if(file_exists("../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="../../../functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery-1.4.2.min.js")) echo('<SCRIPT src="./functions/jquery-1.4.2.min.js" type=text/javascript></SCRIPT>');
	

	
	
	if(file_exists("../functions/jquery-xtra.js")) echo('<SCRIPT src="../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery-xtra.js")) echo('<SCRIPT src="../../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery-xtra.js")) echo('<SCRIPT src="../../../functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery-xtra.js")) echo('<SCRIPT src="./functions/jquery-xtra.js" type=text/javascript></SCRIPT>');
	
	
	if(file_exists("../functions/main.js")) echo('<SCRIPT src="../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/main.js")) echo('<SCRIPT src="../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/main.js")) echo('<SCRIPT src="../../../functions/main.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/main.js")) echo('<SCRIPT src="./functions/main.js" type=text/javascript></SCRIPT>');


	/* File Required to display Color Box  */
	/*if(file_exists("../functions/jquery.colorbox-min.js")) echo('<SCRIPT src="../functions/jquery.colorbox-min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/jquery.colorbox-min.js")) echo('<SCRIPT src="../../functions/jquery.colorbox-min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/jquery.colorbox-min.js")) echo('<SCRIPT src="../../../functions/jquery.colorbox-min.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/jquery.colorbox-min.js")) echo('<SCRIPT src="./functions/jquery.colorbox-min.js" type=text/javascript></SCRIPT>');*/
	

	if(file_exists("../functions/datepickercontrol.js")) echo('<SCRIPT src="../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/datepickercontrol.js")) echo('<SCRIPT src="../../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/datepickercontrol.js")) echo('<SCRIPT src="../../../functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/datepickercontrol.js")) echo('<SCRIPT src="./functions/datepickercontrol.js" type=text/javascript></SCRIPT>');
	
	
	if(file_exists("../functions/cookies.js")) echo('<SCRIPT src="../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/cookies.js")) echo('<SCRIPT src="../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/cookies.js")) echo('<SCRIPT src="../../../functions/cookies.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/cookies.js")) echo('<SCRIPT src="./functions/cookies.js" type=text/javascript></SCRIPT>');

	if(file_exists("../functions/javascripts.js")) echo('<SCRIPT src="../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/javascripts.js")) echo('<SCRIPT src="../../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/javascripts.js")) echo('<SCRIPT src="../../../functions/javascripts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/javascripts.js")) echo('<SCRIPT src="./functions/javascripts.js" type=text/javascript></SCRIPT>');
	
	if(file_exists("../functions/lookout.js")) echo('<SCRIPT src="../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/lookout.js")) echo('<SCRIPT src="../../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/lookout.js")) echo('<SCRIPT src="../../../functions/lookout.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/lookout.js")) echo('<SCRIPT src="./functions/lookout.js" type=text/javascript></SCRIPT>');
	
	if(file_exists("../functions/FusionCharts.js")) echo('<SCRIPT src="../functions/FusionCharts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../functions/FusionCharts.js")) echo('<SCRIPT src="../../functions/FusionCharts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("../../../functions/FusionCharts.js")) echo('<SCRIPT src="../../../functions/FusionCharts.js" type=text/javascript></SCRIPT>');
	elseif(file_exists("./functions/FusionCharts.js")) echo('<SCRIPT src="./functions/FusionCharts.js" type=text/javascript></SCRIPT>');
	

echo('<script language="JavaScript"> if (navigator.platform.toString().toLowerCase().indexOf("linux") != -1) { document.write(\'<link type="text/css" rel="stylesheet" href="../functions/datepickercontrol_lnx.css">\'); } else { document.write(\'<link type="text/css" rel="stylesheet" href="../functions/datepickercontrol.js">\'); } </script>');
?>
