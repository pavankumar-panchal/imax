
<?php
if($p_smsaccounttocustomers <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/smsaccount.js?dummy=<?php echo (rand());?>"></script>
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
                        <td width="71%" height="34"  id="customerselectionprocess" align="left"  style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/><input type="hidden" id="cuslastslno" name="cuslastslno" />
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
                            <td width="27%" align="left" valign="middle" class="active-leftnav">SMS Account</td>
                            <td width="45%"><div align="right">
                              <div align="right"><font color="#FF6B24">Customer ID</font></div>
                            </div></td>
                            <td width="28%"><div align="right">
                              <div align="left">
                                <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);" style="width:150px" maxlength="20"  autocomplete="off"/>
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                            </div></td>
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
                            <td width="100px" style="padding-right:7px; text-align:right"><?php if($p_smsaccounttocustomers == 'yes') { ?><a href="./index.php?a_link=smscredits" class="textlink"> SMS Credits Page &gt;&gt;</a> <?php } ?></td>
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
                                            <td width="36%" align="left" valign="top">Contact Person:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername"><input name="contactperson" type="text" class="swifttext-mandatory" id="contactperson" size="30" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top"><label>
                                              Email ID:
                                                <div align="right"></div>
                                              </label></td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername"><input name="emailid" type="text" class="swifttext-mandatory" id="emailid" size="30" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top"><label>Cell:
                                              <div align="right"></div>
                                            </label></td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername"><input name="cell" type="text" class="swifttext-mandatory" id="cell" size="30" maxlength="10" autocomplete="off" /></td>
                                          </tr>
                                           <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Account Type:
</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><select name="accounttype" class="swiftselect-mandatory" id="accounttype" style="width:200px;">
                                              <option value="" selected="selected">Select a Type</option>
                                              <option value="promotional">Promotional</option>
                                            </select></td>
                                           </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top"><label>
                                              <input type="checkbox" name="disablesmsaccount" id="disablesmsaccount" /> 
                                              Disable this account</label></td>
                                          </tr>
                                                                           
                                          
                                          
                                      </table></td>
                                      <td width="48%" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                        <tr bgcolor="#f7faff">
                                          <td width="34%" align="left" valign="top">User Name:</td>
                                          <td width="66%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername"><input name="username" type="text" class="swifttext-mandatory" id="username" size="30" maxlength="60" autocomplete="off" />
                                              <input name="lastslno" id="lastslno" type="hidden" />
                                            <input name="smslastslno" id="smslastslno" type="hidden" /></td>
                                        </tr>
                                        <tr bgcolor="#EDF4FF">
                                          <td align="left" valign="top"><label>
                                            From Name:
                                            <div align="right"></div>
                                            </label></td>
                                          <td align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername"><input name="fromname" type="text" class="swifttext-mandatory" id="fromname" size="30" maxlength="8" autocomplete="off" /></td>
                                        </tr>
                                      <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Password:</td>
                                            <td align="left" valign="top" id="displayenteredby" bgcolor="#f7faff" ><input name="password" type="password" class="swifttext-mandatory" id="password" size="30" maxlength="100" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff"><label>Customer Id:</label></td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="displaycustomerid">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff"><input type="checkbox" name="croptext" id="croptext" />
Crop text</td>
                                          </tr>
                                      </table></td>
                                    </tr>

                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class="swiftchoicebuttonbig" id="new" value="New" onclick="newaccountentry();document.getElementById('form-error').innerHTML ='';" />                                              &nbsp;&nbsp;
                                              <input name="save" type="button" class="swiftchoicebuttonbig" id="save" value="Save Settings" onClick="formsubmit('save');" />
&nbsp;&nbsp;</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                        <tr class="header-line">
                          <td width="94" align="left"  style="padding:0"><div id="tabdescription">&nbsp;  Details</div></td>
                          <td width="490"   style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                          <td width="45" align="left"  style="padding:0">&nbsp;</td>
                          <td width="103" align="left"  style="padding:0">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                </tr>
                                <tr>
                                  <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;"> </div></td>
                                </tr>
                              </table>
                          </div>
                              <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
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

<?php } ?>