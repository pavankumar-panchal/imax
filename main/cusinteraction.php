<?php
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/cusinteraction.js?dummy=<?php echo (rand());?>"></script>
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
                        <td width="29%" style="padding:0"><div align="right"><a onclick="abortRequest();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
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
                            <td width="27%" align="left" valign="middle" class="active-leftnav">Customer Interaction Details</td>
                            <td width="41%"   align="right"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="32%"  valign="top"><div align="left" style="padding:2px">
                                <div align="right">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"style="width:175px" maxlength="20"  autocomplete="off"/>
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
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    
                                    <tr>
                                      <td width="52%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" height="71" border="0" cellpadding="3" cellspacing="0">
                                          <tr bgcolor="#f7faff">
                                            <td width="36%" align="left" valign="top">Business Name:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#f7faff" id="displaycustomername"></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top">Interaction Category:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><select name="interaction" class="swiftselect-mandatory" id="interaction" style="width:200px">
                                                <?php 
											include('../inc/interactiontype.php');
											?>
                                              </select><input type="hidden" name="lastslno" id="lastslno" />
                                              <input type="hidden" name="cusinteractionslno" id="cusinteractionslno" /></td>
                                          </tr>
                                          
                                          
                                          
                                      </table></td>
                                      <td width="48%" valign="top"><table width="100%" height="74" border="0" cellpadding="3" cellspacing="0">
                                      <tr >
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Entered By:</td>
                                            <td align="left" valign="top" id="displayenteredby" bgcolor="#EDF4FF" ><?php $fetch = runmysqlqueryfetch("SELECT fullname FROM inv_mas_users WHERE slno = '".imaxgetcookie('userid')."'"); echo($fetch['fullname']); ?></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Entered Through:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="enteredthrough"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="34%" align="left" valign="top" bgcolor="#EDF4FF">Entered Date:</td>
                                            <td width="66%" align="left" valign="top" bgcolor="#EDF4FF"  id="interactiondate"  ><?php echo(datetimelocal('d-m-Y')."  "."(".datetimelocal('H:i')) .")"?></td>
                                          </tr>
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr bgcolor="#f7faff">
                                            <td width="18%" align="left" valign="top" bgcolor="#f7faff">Remarks:</td>
                                            <td width="82%" align="left" valign="top" bgcolor="#f7faff" ><textarea name="remarks" cols="75" class="swifttextarea" id="remarks" rows="1"  ></textarea></td>
                                    </table></td></tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                      </tr>
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;&nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
&nbsp;&nbsp; <input name="delete" type="button" class="swiftchoicebutton" id="delete" value="Delete" onClick="formsubmit('delete');" />                                     </td>
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
                                  <td width="194" align="left"  style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Customer Interactions</div></td>
                                  <td width="304"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="234"  style="padding:0; text-align:left;">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left" ></div></td>
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
