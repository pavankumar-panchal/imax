<?
	if(file_exists("../functions/phpfunctions.php")) { include('../functions/phpfunctions.php'); } elseif(file_exists("../../functions/phpfunctions.php")) { include('../../functions/phpfunctions.php'); } else { include('./functions/phpfunctions.php'); }
	if(file_exists("../inc/checksession.php")) { include('../inc/checksession.php'); } elseif(file_exists("../../inc/checksession.php")) { include('../../inc/checksession.php'); } else { include('./inc/checksession.php'); }
	
	/*if(file_exists("../inc/checkpermission.php")) { include('../inc/checkpermission.php'); } elseif(file_exists("../../inc/checkpermission.php")) { include('../../inc/checkpermission.php'); } else { include('./inc/checkpermission.php'); }*/

?>