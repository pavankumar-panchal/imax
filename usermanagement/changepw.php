<?php
include('../inc/eventloginsert.php');
$userid = imaxgetcookie('userid');
if(isset($_POST["update"]))
{
	$password = trim($_POST["password"]);
	$newpassword = trim($_POST["newpassword"]);
	$confirmpassword = trim($_POST["confirmpassword"]);
	$message = '';

	$query = "SELECT AES_DECRYPT(loginpassword,'imaxpasswordkey') as loginpassword  FROM inv_mas_users where slno = '".$userid."'";
	$fetch = runmysqlqueryfetch($query);
	if($password == '' || $newpassword == '' || $confirmpassword == '')
		$message = '<div class="errorbox"> Enter the old and/or new and/or confirm passwords </div>';
	elseif($password <> $fetch['loginpassword'])
		$message = '<div class="errorbox"> The Password does not match with the user </div>';
	elseif($newpassword == $fetch['loginpassword'])
		$message = '<div class="errorbox"> Please Enter a different password as old and new passwords are same </div>';
	elseif($newpassword <> $confirmpassword)
		$message = '<div class="errorbox"> New and confirm password does not match. </div>';
	else
	{
		$query = "UPDATE inv_mas_users SET loginpassword = AES_ENCRYPT('".$newpassword."','imaxpasswordkey'),passwordchanged ='Y' WHERE slno = '".$userid."'";
		$result = runmysqlquery($query);
		$eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime,remarks) values('".$userid."','".$_SERVER['REMOTE_ADDR']."','110','".date('Y-m-d').' '.date('H:i:s')."','password changed sucessfully')";
		$eventresult = runmysqlquery($eventquery);
				$message = '<div class="successbox"> Password changed sucessfully. </div>';
	}
}
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">Change Password</td>
                            <td width="40%"><div align="right"></div></td>
                            <td width="33%"><div align="right"></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">Change Password</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv" >
                              <form action="" method="post" name="submitform" id="submitform">
                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                  <tr>
                                    <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr bgcolor="#f7faff">
                                          <td align="left" valign="top">Old Password:</td>
                                          <td align="left" valign="top"><input name="password" type="password" class="swifttext" id="password" size="30" maxlength="20" /></td>
                                        </tr>
                                        <tr bgcolor="#edf4ff">
                                          <td align="left" valign="top">New Password:</td>
                                          <td align="left" valign="top"><input name="newpassword" type="password" class="swifttext" id="newpassword" size="30" maxlength="20" /></td>
                                        </tr>
                                        <tr bgcolor="#f7faff">
                                          <td align="left" valign="top"  style="border-bottom:1px solid #d1dceb;">Confirm Password:</td>
                                          <td align="left" valign="top" style="border-bottom:1px solid #d1dceb;"><input name="confirmpassword" type="password" class="swifttext" id="confirmpassword" size="30" maxlength="20" /></td>
                                        </tr>
                                    </table></td>
                                  </tr>
                                  <tr>
                                    <td width="71%" height="35" align="left" valign="middle" style="padding-right:15px; "><div id="form-error"><?php echo($message); ?></div></td>
                                    <td width="29%" align="right" valign="middle" style="padding-right:15px; "><input name="update" type="submit" class="swiftchoicebutton" id="update" value="Update" />
&nbsp;&nbsp;&nbsp;
<input name="clear" type="reset" class="swiftchoicebutton" id="clear" value="Clear" onclick="reset();document.getElementById('form-error').innerHTML = ''" /></td>
                                  </tr>
                                </table>
                              </form>
                            </div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

