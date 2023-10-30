<?php
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/customeramc.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../inc/selectproductjs.php?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                         <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh customer Data"  /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
                                                      </select> 
                          </div>                       </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
 <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                <td width="55%" id="totalcount" align="left">&nbsp;</td>
              </tr>
</table></td>
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
                          <td width="27%" align="left" class="active-leftnav">Customer AMC Details</td>
                          <td width="40%"><div align="right">Search By Contract ID:</div></td>
                          <td width="33%"><div align="right">
                              <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycontractidevent(event);" style="width:200px" maxlength="40"  autocomplete="off"/>
                              <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycontractid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div></td>
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
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Business Name:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Product:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:180px;">
                                              <option value="">Make A Selection</option>
                                              </select>
                                            </select>
                                            <input type="hidden" name="lastslno" id="lastslno">
                                            <input type="hidden" name="displaycustomername" id="displaycustomername" />
                                            <input type="hidden" name="cuslastslno" id="cuslastslno" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Start Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="startdate" type="text" class="swifttext" id="DPC_startdate" size="30" maxlength="10"  autocomplete="off"/>
                                            </td>
                                          </tr><tr bgcolor="#f7faff">
                                            <td align="left" valign="top">End Date:</td>
                                            <td align="left" valign="top"><input name="enddate" type="text" class="swifttext" id="DPC_enddate" size="30" maxlength="10"  autocomplete="off"/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Bill Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="billdate" type="text" class="swifttext" id="billdate" size="30" maxlength="10"  autocomplete="off" onblur ="onfocusvalue()" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Entered By:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="useriddisplay">Not Available</td>
                                          </tr>
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      
                                             <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Bill No:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="billno" type="text" class="swifttext" id="billno" size="30" autocomplete="off" /></td>
                                          </tr>
                                             <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Bill Amount:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><input name="billamount" type="text" class="swifttext" id="billamount" size="30" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Contract ID:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="contractid">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Status:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="amcstatus">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Entered Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="createddate">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea></td>
                                          </tr>
                                          
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebuttondisabled" id="delete" value="Delete" disabled="disabled" onClick="formsubmit('delete');"/></td>
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
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="139" align="left"  style="padding:0"><div id="tabdescription">&nbsp;Customer AMCs</div></td> 
                                  <td width="413" style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="180" style="padding:0; text-align:center;">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="centre"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left"></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>                                   </td>
                                </tr>
                            </table></td>
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
<script>
gettotalcustomercount();
</script>