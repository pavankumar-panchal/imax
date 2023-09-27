<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/charts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/excanvas.compiled.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/highcharts.js?dummy=<?php echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav">Charts - Graphical Representation</td>
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
                            <td class="header-line" style="padding:0">&nbsp;</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td height="5px"></td>
                                    </tr>
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td bgcolor="#f7faff"><fieldset style="border:1px solid #666666; padding:3px;">
                                              <legend><strong><font size="+1">Graphical Representation:</font></strong> </legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                <tr>
                                                  <td width="6%"></td>
                                                  <td width="12%"><label for="databasefield0">
                                                    <input type="radio" name="charts" id="databasefield0" value="dealer"/>
                                                    Dealer wise</label></td>
                                                  <td width="14%"><label for="databasefield1">
                                                    <input type="radio" name="charts" id="databasefield1" value="branch" checked="checked"/>
                                                    Branch wise</label></td>
                                                  <td width="12%"><label for="databasefield2">
                                                    <input type="radio" name="charts" id="databasefield2" value="state"/>
                                                    State wise</label></td>
                                                  <td width="13%"><label for="databasefield3">
                                                    <input type="radio" name="charts" value="type" id="databasefield3" />
                                                    Type wise</label></td>
                                                  <td width="14%"><label for="databasefield4">
                                                    <input type="radio" name="charts" value="category" id="databasefield4" />
                                                    Category wise</label></td>
                                                  <td width="29%"><label for="databasefield5">
                                                    <input type="radio" name="charts" id="databasefield5" value="group" />
                                                    Group wise</label></td>
                                                </tr>
                                              </table>
                                              </fieldset></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35">&nbsp;</td>
                                            <td width="43%" align="right" valign="middle">&nbsp;
                                              <input name="view" type="submit" class="swiftchoicebutton" id="view" value="View" onClick="formsubmit();"/></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td style="padding:10px" ><div id="graphdisplay"  ></div></td>
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
