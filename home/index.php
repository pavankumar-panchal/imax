<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

include('../functions/phpfunctions.php');
include('../inc/sessioncheck.php');
include('../inc/checkpermission.php');
include("../inc/FusionCharts.php");
$userid = imaxgetcookie('userid');
?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>
    <?php $pagetilte = getpagetitle($_GET['a_link']);
    echo ($pagetilte); ?>
  </title>
  <link rel="shortcut icon" type="image/x-icon" href="../favicon.ico" />
  <?php include('../inc/scriptsandstyles.php'); ?>
</head>

<body onload="bodyonload(<?php echo ($userid) ?>);">
  <div style="display:none; visibility:hidden;"><img src="../images/imax-loading-image.gif" border="0" /></div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="maincontainer">
    <tr>
      <td valign="top" align="center">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="headercontainer">
                <!--              <tr>
                <td height="22px"><div id="loadingimagedisplay" style="display:block;" align="center"><img src="../images/imax-loading-image.gif" border="0"/></div></td>
              </tr>-->
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="wrapper">
                <tr>
                  <td align="center" bgcolor="#FFFFFF">
                    <?php include('../inc/header.php'); ?>
                  </td>
                </tr>
                <tr>
                  <td align="center">
                    <?php include('../inc/navigation.php'); ?>
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
                            <!--<tr>
        <td class="bannerbg" height="118">&nbsp;</td>
      </tr>-->
                            <?php
                            $query = "Select fullname,username from inv_mas_users where slno = '" . $userid . "'";
                            $fetch = runmysqlqueryfetch($query);
                            $fullname = $fetch['fullname'];
                            $username = $fetch['username'];
                            ?>
                            <tr>
                              <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="youarehere">
                                  <tr>
                                    <td width="68%" align="left"><img class="arrow" alt="arrow"
                                        src="../images/herearrow.gif" />
                                      <p>You are here: Saral iMax »
                                        <?php $pageheader = getpageheader($_GET['a_link']);
                                        echo ($pageheader); ?>
                                      </p>
                                    </td>
                                    <td width="32%" align="left" class="logindisplay">
                                      <p align="right">Logged in as:
                                        <?php echo ($fullname);
                                        echo (' [' . $username . ']') ?>
                                      </p>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <?php $pagelink = getpagelink($_GET['a_link']);
                                include($pagelink); ?>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <?php include('../inc/footer.php'); ?>
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