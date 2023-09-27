<?
if($p_smscreditstocustomers <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/smscredits.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>

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
                        <td width="71%" height="34"  id="customerselectionprocess" align="left"  style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"> </select> 
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
              <tr><td>&nbsp;</td></tr>
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
                            <td width="30%" align="left" valign="middle" class="active-leftnav">Credits Available is:  <span id="creditamountdisplay">0</span> SMSes</td>
                            <td width="37%"><div align="right"></div></td>
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
                          <tr class="header-line">
                            <td width="100px" align="left"  style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                          <td width="100px"  style="padding-right:7px; text-align:right;"> <? if($p_smsaccounttocustomers == 'yes') { ?> <div>To Activate SMS Gateway<a href="./index.php?a_link=smsaccount" class="textlink"> Click Here</a></div><? } ?> </td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    <tr><td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                          <tr bgcolor="#f7faff">
                                            <td width="19%" align="left" valign="top">Customer Name:</td>
                                            <td width="81%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                           </table></td></tr>
                                    <tr>
                                      <td width="52%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" height="71" border="0" cellpadding="3" cellspacing="0">
                                      <tr bgcolor="#f7faff">
                                        <td align="left">Invoice No:</td>
                                        <td align="left"><input name="invoiceno" type="text" class="swifttext" id="invoiceno" size="30" maxlength="12" autocomplete="off" style="background:#FFFFCC" readonly="readonly" value="New"/><input name="lastslno" id="lastslno" type="hidden" /></td></tr>
                                        <tr bgcolor="#f7faff">
                                          <td align="left" valign="top">SMS Account:</td>
                                          <td align="left" valign="top" bgcolor="#f7faff" >
<div id="smsaccountlist"><select name="smsaccount" class="swiftselect-mandatory" id="smsaccount" style="width:200px;" >
                                    <option value="">Select an Account</option></select></div>                                  </td>
                                        </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="36%" align="left" valign="top">SMS Quantity:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#f7faff" height="25px"><div id="displayquantity"><input name="quantity" type="text" class="swifttext-mandatory" id="quantity" size="30" maxlength="6" autocomplete="off"  onkeyup="gettotalamount();"/></div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff">
                                              <textarea name="remarks" cols="27" rows="4" class="swifttextarea" id="remarks"></textarea>                                           </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                          </tr>
                                        
                                                                      
                                          
                                          
                                      </table></td>
                                      <td width="48%" valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                                                              <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Entered By:</td>
                                            <td align="left" valign="top" id="displayenteredby" bgcolor="#f7faff">Not Available</td>
                                      </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="34%" align="left" valign="top" bgcolor="#EDF4FF"> Date:</td>
                                            <td width="66%" align="left" valign="top" bgcolor="#EDF4FF"  id="crediteddate"  ><? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?></td>
                                          </tr>
                                           <tr bgcolor="#f7faff">
                                            <td width="34%" align="left" valign="top" bgcolor="#EDF4FF"> Credits Available:</td>
                                            <td width="66%" align="left" valign="top" bgcolor="#EDF4FF"  id="creditsavailableforaccount"  >Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Private Note:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff">
                                              <textarea name="privatenote" cols="27" rows="4" class="swifttextarea" id="privatenote"></textarea></td>
                                          </tr>
                                      </table></td>
                                    </tr>
<tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="53%" align="left" valign="middle" ></td>
                                            <td width="47%" align="right" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                              <tr>
                                                <td width="34%">Total Amount:</td>
                                                <td width="66%"><input name="billamount" type="text" class="swifttext" id="billamount" size="30" maxlength="12" autocomplete="off"  onkeyup="editamount();"/></td>
                                              </tr>
                                              <tr>
                                                <td>Service Tax:</td>
                                                <td><input name="servicetax" type="text" class="swifttext" id="servicetax" size="30" maxlength="12" autocomplete="off" style="background:#FFFFCC" readonly="readonly"/></td>
                                              </tr>
                                              <tr>
                                                <td>Net Amount:</td>
                                                <td><input name="netamount" type="text" class="swifttext" id="netamount" size="30" maxlength="12" autocomplete="off" style="background:#FFFFCC" readonly="readonly"/></td>
                                              </tr></table><div align="center"></div></td></tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle">&nbsp;&nbsp;
                                              <div align="center">
                                                <input name="new" type="button" class="swiftchoicebutton" id="new" value="New" onclick="newcreditentry();document.getElementById('form-error').innerHTML ='';"/> &nbsp;&nbsp;
  <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />

  &nbsp;&nbsp;</div></td>
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
                                <tr>
                                  <td width="37%" align="left" class="header-line" style="padding:0"><div id="tabdescription">&nbsp; SMS Details</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></td>
                                  <td width="4%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                  <td width="8%" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;">
</div></td>
  </tr>
</table>
</div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                    
                                    
                                    
                                    </td>
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

<? } ?>