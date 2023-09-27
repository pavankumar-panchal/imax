<?php
if($p_salessummaryreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/salessummary.js?dummy=<?php echo (rand());?>"></script>
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
                            <td  class="active-leftnav">Report - Sales  Summary                            </td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report                            </td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">

                                        <tr bgcolor="#edf4ff">
                                          <td width="100%" valign="top" bgcolor="#F7FAFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="5px">
                                                <tr bgcolor="#f7faff">
                                                  <td width="25%" valign="top">From Date:</td>
                                                  <td width="75%" align="left" valign="top" bgcolor="#f7faff"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />
                                                      <br /></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td width="25%" valign="top" bgcolor="#EDF4FF">To Date:</td>
                                                  <td width="75%" valign="top" bgcolor="#EDF4FF" align="left"><input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />
                                                  </td>
                                                </tr>
  
</table>
</td>
                                              <td width="50%" valign="top" bgcolor="#F7FAFF"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr>
                                                  <td><fieldset style="border:1px solid #666666; padding:2px;">
                                                  <legend><strong>Conversion</strong></legend>
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td align="left"><label for="acctholder">
                                                        <input name="groupon" type="radio" id="acctholder" value="acctholder" checked="checked" />
                                                        Account Holder</label>
                                                          
                                                          <label for="regngiven">
                                                          <input type="radio" name="groupon" id="regngiven" value="regngiven" />
                                                            Registration Given </label>
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan="2" height="10px"></td></tr>
                                                  </table>
                                                  </fieldset></td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                            <tr><td ><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                              <tr bgcolor="#f7faff">
                                                <td width="25%" valign="top">Dealer:</td>
                                                <td width="75%" align="left" valign="top" bgcolor="#f7faff"><select name="dealerid" class="swiftselect" id="dealerid" style=" width:225px">
                                                  <option value="">ALL</option>
                                                  <?php include('../inc/firstdealer.php'); ?>
                                                </select>
                                                  <br /></td></tr>
                                            </table></td><td >&nbsp;</td></tr>
                                          </table></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="formsubmit('view');" />
                                              &nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/></td>
                                          </tr>
                                      </table></td>
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
<?php } ?>
