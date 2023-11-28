<?php
include('./functions/browsercheck.php');

if (_browser('firefox') || _browser('msie', '>= 7.0') || _browser('chrome') || _browser('safari')) {

  include('./functions/phpfunctions.php');

  session_start();
  $isloggedin = 'false';
  $cookie_logintype = '';

  if ((imaxgetcookie('sessionkind') <> false) && (imaxgetcookie('userid') <> false) && (imaxgetcookie('checkpermission') <> false)) {

    $cookie_logintype = imaxgetcookie('sessionkind');
    $isloggedin = 'true';
  }
  if ($cookie_logintype == 'logoutforthreemin' || $cookie_logintype == 'logoutforsixhr') {
    if ($_SESSION['verificationid'] == '4563464364365')
      $isloggedin = 'true';
    else
      $isloggedin = 'false';
  }

  if ($isloggedin == 'true') {
    header('Location:' . './home/index.php');
  }

  $date = datetimelocal('d-m-Y');
  $time = datetimelocal('H:i:s');

  $defaultusername = '';
  $message = '';
  if (isset($_POST["login"])) {
    $username = strtoupper($_POST['username']);
    $password = $_POST['password'];
    $loggintype = $_POST['loggintype'];

    if ($username == '' or $password == '') {
      $message = '<span class="error-message"> Enter the User Name or Password </span>';
      $defaultusername = $username;
    } else {
      $query = "SELECT *,AES_DECRYPT(loginpassword,'imaxpasswordkey') as loginpassword  FROM inv_mas_users WHERE username = '" . $username . "' and disablelogin = 'no'";
      $result = runmysqlquery($query);
      if (mysqli_num_rows($result) > 0) {
        $fetch = runmysqlqueryfetch($query);

        $user = $fetch['username'];
        $passwd = $fetch['loginpassword'];

        if ($password <> $passwd) {
          $message = ' : <span class="error-message"> Password does not match with the user </span> : ';
          $defaultusername = $username;
        } else {
          $query = runmysqlqueryfetch("SELECT * FROM inv_mas_users WHERE username = '" . $username . "'");
          $userid = $query['slno'];

          $permissions = $fetch['registration'] . "|^||" . $fetch['withoutscratchcard'] . "|^||" . $fetch['dealer'] . "|^||" . $fetch['bills'] . "|^||" . $fetch['credits'] . "|^||" . $fetch['editcustomercontact'] . "|^||" . $fetch['products'] . "|^||" . $fetch['blockcancel'] . "|^||" . $fetch['mergecustomer'] . "|^||" . $fetch['transfercard'] . "|^||" . $fetch['regreports'] . "|^||" . $fetch['productshippedreports'] . "|^||" . $fetch['dealerinvreports'] . "|^||" . $fetch['contactdetailsreport'] . "|^||" . $fetch['invoicedetailsreport'] . "|^||" . $fetch['updationdetailsreport'] . "|^||" . $fetch['editcustomerpassword'] . "|^||" . $fetch['customerpendingrequest'] . "|^||" . $fetch['dealerpendingrequest'] . "|^||" . $fetch['pendingrequestpermission'] . "|^||" . $fetch['hardwarelock'] . "|^||" . $fetch['cusattachcard'] . "|^||" . $fetch['districtmapping'] . "|^||" . $fetch['welcomemail'] . "|^||" . $fetch['scheme'] . "|^||" . $fetch['schemepricing'] . "|^||" . $fetch['producttodealer'] . "|^||" . $fetch['producttodealers'] . "|^||" . $fetch['schemetodealer'] . "|^||" . $fetch['customerpayment'] . "|^||" . $fetch['smsaccounttocustomers'] . "|^||" . $fetch['smsaccounttodealer'] . "|^||" . $fetch['smscreditstocustomers'] . "|^||" . $fetch['smscreditstodealer'] . "|^||" . $fetch['smssummary'] . "|^||" . $fetch['smsreceipttocustomers'] . "|^||" . $fetch['smsreceiptstodealer'] . "|^||" . $fetch['editdealerpassword'] . "|^||" . $fetch['cuspinattachedreport'] . "|^||" . $fetch['suggestedmerging'] . "|^||" . $fetch['labelprint'] . "|^||" . $fetch['viewinvoice'] . "|^||" . $fetch['updationsummaryreport'] . "|^||" . $fetch['salessummaryreport'] . "|^||" . $fetch['viewrcidata'] . "|^||" . $fetch['crossproductreport'] . "|^||" . $fetch['updationdetailedreport'] . "|^||" . $fetch['crossproductsales'] . "|^||" . $fetch['invoicing'] . "|^||" . $fetch['invoiceregister'] . "|^||" . $fetch['receiptsregister'] . "|^||" . $fetch['outstandingregister'] . "|^||" . $fetch['manageinvoice'] . "|^||" . $fetch['bulkprintinvoice'] . "|^||" . $fetch['masterimplementation'] . "|^||" . $fetch['createimplementation'] . "|^||" . $fetch['reregistration'] . "|^||" . $fetch['impsummaryreport'] . "|^||" . $fetch['datainaccuracyreport'] . "|^||" . $fetch['impstatusreport'] . "|^||" . $fetch['receiptreconsilation'] . "|^||" . $fetch['activitylog'] . "|^||" . $fetch['notregisteredreport'] . "|^||" . $fetch['categorysummary'] . "|^||" . $fetch['addinvoices'] . "|^||" . $fetch['addbills'] . "|^||" . $fetch['importinvoices'] . "|^||" . $fetch['forcesurrender'] . "|^||" . $fetch['surrenderreport'] . "|^||" . $fetch['newtransferpin'] . "|^||" . $fetch['transactionsreport'] . "|^||" . $fetch['importreceipt'] . "|^||" . $fetch['pindetails'] . "|^||" . $fetch['dealerattachcard'] . "|^||" . $fetch['usermanagement'] . "|^||" . $fetch['customuser'] . "|^||" . $fetch['mailamccustomer'] . "|^||" . $fetch['addproductsnew'] . "|^||" . $fetch['importinvoicesgst'] . "|^||" . $fetch['autoreceiptreconciliation'] . "|^||" . $fetch['newdealerinvreports'] . "|^||" . $fetch['transferredpinsreport'] . "|^||" . $fetch['managedealerinvoice'] . "|^||" . $fetch['dealerreceipts'] . "|^||" . $fetch['dealerreceiptreconciliation'] . "|^||" . $fetch['dealerinvoiceregister'] . "|^||" . $fetch['dealerbulkprintinvoice'] . "|^||" . $fetch['matrixinvoicing'] . "|^||" . $fetch['managematrixinvoice'] . "|^||" . $fetch['matrixbulkprintinvoice'] . "|^||" . $fetch['matrixinvoiceregister'];

          //Check for the Login type.
          if ($loggintype == 'logoutforthreemin') {
            session_start();
            $_SESSION['verificationid'] = '4563464364365';
          } elseif ($loggintype == 'logoutforsixhr') {
            session_start();
            $_SESSION['verificationid'] = '4563464364365';
          } elseif ($loggintype == 'logoutforever') {
            session_start();
          }

          imaxcreatecookie('sessionkind', $loggintype);
          imaxcreatecookie('userid', $userid);
          imaxcreatecookie('checkpermission', $permissions);

          $query1 = "INSERT INTO inv_logs_login(userid,`date`,`time`,`type`,system,device,browser) VALUES('" . $userid . "','" . datetimelocal('Y-m-d') . "','" . datetimelocal('H:i:s') . "','user_login','" . $_SERVER['REMOTE_ADDR'] . "','DESKTOP','" . $_SERVER['HTTP_USER_AGENT'] . "')";
          $result = runmysqlquery($query1);

          $eventquery = "Insert into inv_logs_event(userid,system,eventtype,eventdatetime) values('" . $userid . "','" . $_SERVER['REMOTE_ADDR'] . "','1','" . date('Y-m-d') . ' ' . date('H:i:s') . "')";
          $eventresult = runmysqlquery($eventquery);

          if (isset($_GET['link']) && isurl($_GET['link']) && isvalidhostname($_GET['link'])) {
            header('Location:' . $_GET['link']);
          } else {
            header('Location:' . './home/index.php?a_link=home_dashboard');
          }
        }
      } else {
        $message = '<span class="error-message"> Login not registered </span>';
      }
    }
  }

} else
  $message = '<span class="error-message"> This browser will not support this Application. Please use Mozilla Firefox or Google Chrome or Internet Explorer 7.0 and above. </span>';
// echo(" This browser will not support this Application. Please use Mozilla Firefox or Google Chrome or Internet Explorer 7.0 and above.");
?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <meta http-equiv="cache-control" content="no-cache,no-store,must-revalidate">
  <meta http-equiv="pragma" content="no-cache">
  <meta http-equiv="expires" content="0">
  <title>Saral iMax Login</title>
  <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
  <script language="javascript" src="./functions/cookies.js?dummy=<?php echo (rand()); ?>"></script>
  <script language="javascript">
    function checknavigatorproperties() {
      if ((navigator.cookieEnabled == false) || (!navigator.javaEnabled())) { document.getElementById('username').focus(); return false; }
      else {
        return true;
        form.submit();
      }
    }

  </script>
  <?php include('./inc/scriptsandstyles.php'); ?>
  <script language="javascript" src="./functions/main-enter-shortcut.js?dummy=<?php echo (rand()); ?>"></script>

</head>

<body
  onload="document.submitform.username.focus(); SetCookie('logincookiejs','logincookiejs'); if(!GetCookie('logincookiejs')) document.getElementById('form-error').innerHTML = '<span class=\'error-message\'>Enable cookies for this site </span>';">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer">
    <tr>
      <td valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="headercontainer">
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF">
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                <tr>
                  <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <div id=logo><span style="vertical-align:middle"></span></div>
                          <div id=relyonlogo><span style="vertical-align:middle"></span></div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainbg">
                <tr>
                  <td align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main">
                      <tr>
                        <td align="center">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                            <tr>
                              <td class="bannerbg" height="118">&nbsp;</td>
                            </tr>
                            <tr>
                              <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="youarehere">
                                  <tr>
                                    <td align="left"><img class="arrow" alt="arrow" src="./images/herearrow.gif" />
                                      <p>Welcome to Saral iMax. Please enter your login information to proceed with.</p>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
                                  <tr>
                                    <td width="23%" valign="top" style="border-bottom:#1f4f66 1px solid;">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
                                        <tr>
                                          <td>
                                            <form id="submitform" name="submitform" method="post" action="">
                                              <table width="100%" border="0" align="center" cellpadding="4"
                                                cellspacing="0">
                                                <tr>
                                                  <td align="center">
                                                    <table width="43%" border="0" cellspacing="0" cellpadding="5"
                                                      style="border:solid 1px #B9DCFF">
                                                      <tr>
                                                        <td colspan="2">
                                                          <div align="center" id="form-error" style="height:18px">
                                                            <noscript>
                                                              <div class="error-message"> Enable cookies/javscript/both
                                                                in your browser, then </div>
                                                            </noscript>
                                                            <?php if ($message <> '')
                                                              echo ($message); ?>
                                                          </div>
                                                        </td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left" valign="top">User Name:</td>
                                                        <td align="left" valign="top"><label>
                                                            <input name="username" type="text"
                                                              class="swifttext type_enter" id="username" size="30"
                                                              maxlength="40"
                                                              value="<?php echo ($defaultusername); ?>" />
                                                          </label></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left" valign="top">Password:</td>
                                                        <td align="left" valign="top"><input name="password"
                                                            type="password" class="swifttext type_enter" id="password"
                                                            size="30" maxlength="20" /></td>
                                                      </tr>
                                                      <tr>
                                                        <td colspan="2">
                                                          <table width="75%" border="0" cellspacing="0" cellpadding="5">
                                                            <tr>
                                                              <td align="right"><input type="radio" name="loggintype"
                                                                  id="logoutforthreemin" value="logoutforthreemin"
                                                                  checked="checked" /></td>
                                                              <td align="left"><label for="logoutforthreemin">Logout
                                                                  automatically on 3 Minutes idle time</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td align="right"><input type="radio" name="loggintype"
                                                                  id="logoutforsixhr" value="logoutforsixhr" /></td>
                                                              <td align="left"><label for="logoutforsixhr">Logout
                                                                  automatically on 6 Hours idle time</label></td>
                                                            </tr>
                                                            <tr>
                                                              <td align="right"><input type="radio" name="loggintype"
                                                                  id="logoutforever" value="logoutforever" /></td>
                                                              <td align="left"><label for="logoutforever">Logged in
                                                                  forever (until manual logout)</label></td>
                                                            </tr>
                                                            <tr></tr>
                                                          </table>
                                                        </td>
                                                      </tr>


                                                    </table>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td valign="top">
                                                    <div align="center">
                                                      <input name="login" type="submit"
                                                        class="swiftchoicebutton-red type_enter default" id="login"
                                                        value="Login" onclick="checknavigatorproperties()" />
                                                      &nbsp;&nbsp;&nbsp;
                                                      <input name="clear" type="reset" class="swiftchoicebutton-red"
                                                        id="clear" value="Clear"
                                                        oonClick="document.getElementById('form-error').innerHTML = '';document.getElementById('username').focus()" />
                                                    </div>
                                                  </td>
                                                </tr>
                                              </table>
                                            </form>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" id="footer">
                            <tr>
                              <td align="left">
                                <p>A product of Relyon Web Management | Copyright &copy; 2012 Relyon Softech Ltd. All
                                  rights reserved.</p>
                              </td>
                              <td align="left">
                                <div align="right">
                                  <font color="#FFFFFF">Version 1.04</font>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>