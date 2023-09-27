<?php
if($p_transfercard <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/transfercards.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="active-leftnav">PIN Number Selection</td>
              </tr>
              <tr>
                <td><form id="cardsearchfilterform" name="cardsearchfilterform" method="post" action="">
                    <table width="99%" border="0" cellspacing="0" cellpadding="3">
                     
                      <tr>
                        <td align="center">
    
                        <input name="cardsearchtext" type="text" class="swifttext" id="cardsearchtext" size="32" onkeyup="cardsearch(event);"  autocomplete="off"/>
                          <span style="display:none1">
                          <input name="cardlastslno" type="hidden" id="cardlastslno"  disabled="disabled"/>
                          </span>
                          <select name="cardlist" size="5" class="swiftselect" id="cardlist" style="width:210px; height:400px" onclick ="selectcardfromlist();" onchange="selectcardfromlist();"  >
                          </select>                 </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav"><!--Transfer Cards--></td>
                            <td width="40%"><div align="right"><!--Search By Dealer ID:--></div></td>
                            <td width="33%"><div align="right">
                                <!--<input name="searchbydealerid" type="text" class="swifttext" id="searchbydealerid" size="30" onKeyUp="searchbydealeridevent(event);" />
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="dealerdetailstoform(document.getElementById('searchbydealerid').value);" style="cursor:pointer" /> </div>--></td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Transfer the PIN Number Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top">PIN Serial Number:</td>
                                                <td align="left" valign="top" bgcolor="#f7faff"><input name="cradno" type="text" class="swifttext" id="cradno" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF">PIN Number:</td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="pinno" type="text" class="swifttext" id="pinno" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/>
                                                  <br /></td></tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Dealer:</td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="dealer" type="text" class="swifttext" id="dealer" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF">Registered:</td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="registered" type="text" class="swifttext" id="registered" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Usage Type:</td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF" id="districtcodedisplay"><input name="usagetype" type="text" class="swifttext" id="usagetype" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF">Purchase Type:</td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="purchasetype" type="text" class="swifttext" id="purchasetype" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Product:</td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="product" type="text" class="swifttext" id="product" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off"/></td>
                                              </tr>

                                          </table></td>
                                          <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr bgcolor="#f7faff">
                                                <td width="30%" align="left" valign="top"><strong>Change:</strong></td>
                                                <td width="70%" align="left" valign="top">&nbsp;</td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF">Dealer:</td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Usage Type:</td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><br /></td></tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF">Purchase Type:</td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Product:</td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><br /></td></tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><div align="right">
                                                  <input type="checkbox" name="detachcard" id="detachcard" />
                                                </div></td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF">Detach the PIN Number</td>
                                              </tr>

                                              <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                          </table></td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                              <tr>
                                                <td height="25" colspan="2" align="left" valign="middle"><div id="form-error2"></div></td>
                                              </tr>
                                              <tr>
                                                <td width="43%" height="35" align="left" valign="middle">&nbsp;</td>
                                                <td width="57%" height="35" align="right" valign="middle"><input name="transfer" type="button" class="swiftchoicebutton" id="transfer" value="Transfer" onclick="formsubmit();" />                                                  
                                                  <input name="clear" type="reset" class="swiftchoicebutton" id="clear" value="Clear" onclick="disableupdates();" />                                                  &nbsp;&nbsp;&nbsp;</td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php } ?>