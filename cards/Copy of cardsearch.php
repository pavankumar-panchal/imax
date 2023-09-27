<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/cardsearch.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>

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
                            <td width="27%" class="active-leftnav">Search PIN Numbers</td>
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
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#edf4ff">
                                            <td width="14%" align="left" valign="top">Search Text: </td>
                                            <td width="86%" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3"style="border:solid 1px #CCCCCC ">
                                                <tr >
                                                  <td width="14%" valign="top">Look In: </td>
                                                  <td width="86%"><label for="databasefield0">
                                                    <input type="radio" name="databasefield" id="databasefield0" value="cardid"/>
                                                    PIN Serial Number</label>
                                                    <label for="databasefield1">
                                                    <input type="radio" name="databasefield" id="databasefield1" value="scratchnumber" checked="checked"/>
                                                    PIN Number</label>
                                                    <label for="databasefield2">
                                                    <input type="radio" name="databasefield" id="databasefield2" value="attached"/>
                                                    Attached</label>
                                                    <label for="databasefield3">
                                                    <input type="radio" name="databasefield" value="registered" id="databasefield3" />
                                                    Registered</label>
                                                    <label for="databasefield4">
                                                    <input type="radio" name="databasefield" value="blocked" id="databasefield4" />
                                                    Blocked</label>
                                                    <label for="databasefield5">
                                                    <input type="radio" name="databasefield" id="databasefield5" value="online" />
                                                    Online</label>
                                                    <label for="databasefield6">
                                                    <input type="radio" name="databasefield" value="cancelled" id="databasefield6" />
                                                    Cancelled</label>
                                                  </td>
                                                </tr>
                                                 
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top">Order By:</td>
                                            <td align="left" valign="top"><select name="orderby" id="orderby" onchange="javascript:nameloadsearch('nameloadform');" class="swiftselect">
                                                <option value="cardid">PIN Serial Number</option>
                                                <option value="scratchnumber" selected="selected">PIN Number</option>
                                                <option value="attached">Attached</option>
                                                <option value="registered">Registered</option>
                                                <option value="blocked">Blocked</option>
                                                <option value="online">Online</option>
                                                <option value="cancelled">Cancelled</option>
                                              </select>
                                            </td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="69%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                            <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Filter" onclick="searchfilter('');" />
                                              &nbsp;&nbsp;
                                              <input name="clear" type="button" class="swiftchoicebutton-red" id="clear" value="Clear" onclick="reset();document.getElementById('displaysearchresult').innerHTML=''" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                        <tr>
                                          <td width="15%" class="header-line" style="padding:0"><div id="tabdescription">Results</div></td>
                                        </tr>
                                        <tr>
                                          <td style="padding:0"><div id="displaysearchresult" style="overflow:auto; height:150px; width:928px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc3_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1linksearch"  align="left">
</div></td>
  </tr>
</table></div><div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div></td>
                                        </tr>
                                        

                                      </table></td></tr>
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
